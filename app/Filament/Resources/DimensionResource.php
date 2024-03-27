<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Dimension;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\DimensionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Table\Actions\DeduplicateRecordsAction;
use App\Filament\Resources\DimensionResource\RelationManagers;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;

class DimensionResource extends Resource
{
    protected static ?string $model = Dimension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'THEMES';
    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('name')->required(),
                        Textarea::make('definition'),
                        Textarea::make('notes'),
                        Radio::make('unreviewed_import')
                            ->options([
                                0 => 'No - it has been reviewed',
                                1 => 'Yes - Needs Review',
                            ])
                            ->label('Does this dimension entry need review?')
                            ->visible(function (Model $record): bool {
                                $visible = $record->unreviewed_import == 1;
                                return $visible;
                            })
                    ])
            ]);
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Key Info')
                ->schema([
                    TextEntry::make('name')->inlineLabel(),
                    TextEntry::make('definition')->inlineLabel()
                        ->state(fn (Model $record): string => $record->definition ?? '-'),
                    TextEntry::make('notes')->inlineLabel()
                        ->state(fn (Model $record): string => $record->notes ?? '-'),
                ])
                ->columnSpanFull(),
        ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('definition'),
                TextColumn::make('metrics_count')->counts('metrics')->sortable(),
                IconColumn::make('unreviewed_import')
                    ->options(['heroicon-o-exclamation-circle' => fn ($state): bool => (bool)$state])
                    ->color('danger')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('unreviewed_import')
                    ->query(fn (Builder $query): Builder => $query->where('unreviewed_import', true))
                    ->label('Unreviewed imported records'),
                TrashedFilter::make(),
            ])
            ->actions([

                // add table row button "Deduplicate"
                Action::make('deduplicate')
                    ->modalDescription('Please select possible duplicates then click Submit button for de-duplication')
                    ->form([
                        CheckboxList::make('selectedEntries')
                            ->label('Other dimensions')
                            ->options(function (Model $record) {
                                return Dimension::where('id', '!=', $record->id)
                                    ->where('soundex', '!=', 'NOT_DUPLICATE')
                                    ->where('soundex', $record->soundex)
                                    ->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable(),
                    ])
                    ->disabled((function (Model $record) {
                            return Dimension::where('id', '!=', $record->id)
                                ->where('soundex', '!=', 'NOT_DUPLICATE')
                                ->where('soundex', $record->soundex)
                                ->count() == 0;
                        })
                    )
                    ->action(function (array $data, Dimension $record): void {

                        // assume to merge selected entries into current entry
                        $record_remain_id = $record->id;
                        $records_remove = $data['selectedEntries'];

                        $recordsId = $data['selectedEntries'];
                        array_push($recordsId, $record->id);

                        $records = Dimension::whereIn('id', $recordsId)->get();
                        $relations = array_keys($records[0]->getAvailableManyToManyRelations());

                        // pre-load relations to avoid too many db calls
                        $records->load($relations);
                        $class = get_class($records->first());
                        $remaining_record = $class::findOrFail($record_remain_id);

                        // work 1 relation at a time
                        foreach ($relations as $relation) {

                            // records
                            $all_related_records = $records->mapWithKeys(function (Model $record) use ($relation) {
                                $related_entities = $record->$relation;

                                $pivot_vars = $record->$relation()->getPivotColumns();

                                // we have 'id' as a pivot Column on relationships where a single 'related' model can be linked to an entry multiple times via different relationships. (E.g. "references" vs "computation guidance" on Metrics).
                                // this cannot be duplicated to a new pivot table entry, so filter it out.
                                $pivot_vars = collect($pivot_vars)->filter(fn ($var) => $var !== 'id');


                                $values = $related_entities->mapWithKeys(function (Model $related_entity) use ($pivot_vars) {
                                    $pivot_values = $pivot_vars->mapWithKeys(function ($pivot_var) use ($related_entity) {
                                        return [$pivot_var => $related_entity->pivot->$pivot_var];
                                    });

                                    return [$related_entity->id => $pivot_values];
                                });

                                // cast record ID as string so merging doesn't remove them later.
                                return [(string)$record->id => $values];
                            })->reduce(function ($carry, $item) use ($relation) {

                                return $item->mapWithKeys(function (\Illuminate\Support\Collection $pivot_values, int $model_id) use ($carry) {

                                    // if model is already linked via relationship
                                    if (isset($carry[$model_id])) {

                                        $pivot_values = $pivot_values->mergeRecursive($carry[$model_id])
                                            ->mapWithKeys(function ($value, $key) {

                                                // if value now has multiple entries from the merge, merge/reduce them to a single value;
                                                if (is_array($value)) {
                                                    $value = collect($value)->reduce(function ($current, $new) {
                                                        // if entries are identical, merge
                                                        if ($current === $new) {
                                                            return $current;
                                                        }

                                                        // if entries are non-identical strings, concatenate;
                                                        if (is_string($new)) {
                                                            return collect([$current, $new])->filter(fn ($i) => $i !== null)->join('. ');
                                                        }

                                                        // for numeric, abritrarily return highest value (???)
                                                        if ($current && $current > $new) {
                                                            return $current;
                                                        }

                                                        return $new;
                                                    });
                                                }

                                                return [$key => $value];
                                            });
                                    }

                                    return [$model_id => $pivot_values];
                                })
                                    ->union($carry)

                                    // reduce to array for final sync()
                                    ->toArray();
                            }, collect([]));


                            $remaining_record->$relation()->sync($all_related_records);
                        }


                        $class::whereIn('id', $records_remove)->delete();

                        // }

                    }),

                // add table row button "Mark as not duplicate"
                Action::make('mark_as_not_duplicate')
                    ->modalDescription('Please select other possible duplicates then click Submit button to mark them as not duplicates')
                    ->form([
                        CheckboxList::make('selectedEntries')
                            ->label('Other dimensions')
                            ->options(function (Model $record) {
                                return Dimension::where('soundex', '!=', 'NOT_DUPLICATE')
                                    ->where('soundex', $record->soundex)
                                    ->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable(),
                    ])
                    ->disabled((function (Model $record) {
                            return Dimension::where('soundex', '!=', 'NOT_DUPLICATE')
                                ->where('soundex', $record->soundex)
                                ->count() <= 1;
                        })
                    )
                    ->action(function (array $data, Dimension $record): void {
                        // mark soundex column as "NOT_DUPLICATE"
                        foreach ($data['selectedEntries'] as $entry) {
                            $dimension = Dimension::find($entry);
                            $dimension->soundex = 'NOT_DUPLICATE';
                            $dimension->save();
                        }
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                DeduplicateRecordsAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DimensionMetricsRelationManager::class,
            RelationManagers\ReferencesRelationManager::class,
            RelationManagers\ToolsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDimensions::route('/'),
            'view' => Pages\ViewDimension::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
