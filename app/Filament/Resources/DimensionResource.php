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
                    ->modalDescription('Please select other possible duplicates then click Submit button to de-duplicate them')
                    ->form([
                        CheckboxList::make('Other dimensions')
                            ->options(function (Model $record) {
                                return Dimension::where('id', '!=', $record->id)->where('soundex', $record->soundex)->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable(),
                    ])
                    ->action(function (array $data, Dimension $record): void {
                        // TODO: Deduplicate user selected records and their relationships
                        logger($record);
                        logger($data);
                    }),

                // add table row button "Mark as not duplicate"
                Action::make('mark_as_not_duplicate')
                    ->modalDescription('Please select other possible duplicates then click Submit button to mark them as not duplicates')
                    ->form([
                        CheckboxList::make('selectedEntries')
                            ->label('Other dimensions')
                            ->options(function (Model $record) {
                                return Dimension::where('soundex', $record->soundex)->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable(),
                    ])
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
