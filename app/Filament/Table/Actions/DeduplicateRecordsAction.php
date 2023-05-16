<?php

namespace App\Filament\Table\Actions;

use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class DeduplicateRecordsAction extends BulkAction
{


    public static function getDefaultName(): ?string
    {
        return 'de-duplicate';
    }

    public function setUp(): void
    {

        parent::setUp();

        $this->label('De-duplicate selected');
        $this->icon('heroicon-s-document-duplicate');

        $this->deselectRecordsAfterCompletion();
        $this->form(
            function (Collection $records) {
                $tableName = $records->first()->getTable();
                $modelName = $this->getModelLabel();

                // This assumes that the 1st entry in the list table is the identifiable "title" / "name" of the record.
                $titleAttribute = array_keys($this->getTable()->getColumns())[0];
                
                return [
                    Select::make('remaining_record')
                        ->inlineLabel()
                        ->label('The following ' . $tableName . ' have been selected for de-duplication')
                        ->hint('Select one ' . $modelName . ' to remain. All other ' . $tableName . ' will be deleted and their links to other entities will be merged with the remaining ' . $modelName)
                        ->placeholder('Select a ' . $modelName)
                        ->options($records->pluck($titleAttribute, 'id'))
                ];
            }
        );

        $this->action(
            function (Collection $records, array $data) {

                $record_remain_id = $data['remaining_record'];
                $records_remove = $records->filter(fn($record) => $record->id != $record_remain_id)->pluck('id');

                $relations = array_keys($records[0]->getAvailableManyToManyRelations());


                // pre-load relations to avoid too many db calls
                $records->load($relations);

                $related_array = [];


                // pre-fill related_array with keys, so we don't need to check if $related_array[$relation] exists or not;
                foreach ($relations as $relation) {
                    $related_array[$relation] = [];
                }

                foreach ($records as $record) {

                    foreach ($relations as $relation) {

                        $related_items = $record->$relation;

                        foreach ($related_items as $related_item) {
                            if (isset($related_array[$relation][$related_item->id]) && $related_array[$relation][$related_item->id]['relation_notes'] !== '') {
                                $related_array[$relation][$related_item->id]['relation_notes'] .= '. ' . $related_item->pivot->relation_notes;
                            } else {
                                $related_array[$relation][$related_item->id]['relation_notes'] = $related_item->pivot->relation_notes;
                            }
                        }
                    }

                }

                $class = get_class($records->first());
                $remaining_record = $class::findOrFail($record_remain_id);

                foreach ($relations as $relation) {
                    $remaining_record->$relation()->sync($related_array[$relation]);
                }

                $class::whereIn('id', $records_remove)->delete();

            }
        );


    }

}
