<?php

namespace App\Filament\Table\Actions;

use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
                        $pivot_vars = collect($pivot_vars)->filter(fn($var) => $var !== 'id');


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
                                                    return collect([$current, $new])->filter(fn($i) => $i !== null)->join('. ');
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

            }
        );


    }

}
