<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Dimension;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Form\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\DimensionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DimensionResource\RelationManagers;

class DimensionResource extends Resource
{
    protected static ?string $model = Dimension::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    Select::make('topicId')->relationship('topic', 'name')->required(),
                    TextInput::make('name')->required(),
                    Textarea::make('definition'),
                    Textarea::make('notes'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('definition'),
                TextColumn::make('metrics_count')->counts('metrics')->sortable(),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('de-duplicate')
                                    ->icon('heroicon-s-document-duplicate')
                                    ->label('De-duplicate selected')
                                    ->form(
                                        function (Collection $records) {
                                            $tableName = $records->first()->getTable();
                                            $modelName = Str::lower(ltrim(get_class($records->first()), 'App\Models'));
                                            return [
                                                Select::make('remaining_record')
                                                ->inlineLabel()
                                                ->label('The following ' . $tableName . ' have been selected for de-duplication')
                                                ->hint('Select one ' . $modelName . ' to remain. All other ' . $tableName . ' will be deleted and their links to other entities will be merged with the remaining ' . $modelName)
                                                ->placeholder('Select a ' . $modelName)
                                                ->options($records->pluck('name', 'id'))
                                            ];
                                        }
                                    )
                                    ->action(
                                        function (Collection $records, array $data) {
                                        
                                            $record_remain = $data['remaining_record'];
                                            $records_remove = [];
                                            foreach ($records as $record) {
                                                if (strval($record->id) !== $record_remain) {
                                                    $records_remove[] = $record->id;
                                                };
                                            }

                                            $metrics_array=[];
                                            $references_array=[];

                                            foreach($records as $record) {

                                                $metrics = $record->metrics()->get();
                                                foreach($metrics as $metric) {
                                                    if(isset($metrics_array[$metric->pivot->metric_id])) {
                                                        if ($metrics_array[$metric->pivot->metric_id]['relation_notes']=='') {
                                                            $metrics_array[$metric->pivot->metric_id]['relation_notes'] = $metric->pivot->relation_notes;
                                                        }
                                                        else {
                                                            $metrics_array[$metric->pivot->metric_id]['relation_notes'] = $metrics_array[$metric->pivot->metric_id]['relation_notes'] . '. ' . $metric->pivot->relation_notes;
                                                        }
                                                    }
                                                    else {
                                                        $metrics_array[$metric->pivot->metric_id]['relation_notes'] = $metric->pivot->relation_notes;
                                                    }
                                                }

                                                $references = $record->references()->get();
                                                foreach($references as $reference) {
                                                    if(isset($references_array[$reference->pivot->reference_id])) {
                                                        if ($references_array[$reference->pivot->reference_id]['relation_notes']=='') {
                                                            $references_array[$reference->pivot->reference_id]['relation_notes'] = $reference->pivot->relation_notes;
                                                        }
                                                        else {
                                                            $references_array[$reference->pivot->reference_id]['relation_notes'] = $references_array[$reference->pivot->reference_id]['relation_notes'] . '. ' . $reference->pivot->relation_notes;
                                                        }
                                                    }
                                                    else {
                                                        $references_array[$reference->pivot->reference_id]['relation_notes'] = $reference->pivot->relation_notes;
                                                    }
                                                }
                                            };
 
                                            // TODO: fix sync
                                            // Dimension::where('id', $record_remain)->sync($metrics_array);
                                            // Dimension::where('id', $record_remain)->sync($references_array);
                                            
                                            Dimension::whereIn('id', $records_remove)->delete();
                                            
                                        }
                                    )
            ]);                       
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDimensions::route('/'),
            'create' => Pages\CreateDimension::route('/create'),
            'edit' => Pages\EditDimension::route('/{record}/edit'),
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