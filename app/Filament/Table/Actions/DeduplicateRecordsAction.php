<?php

namespace App\Filament\Table\Actions;

use App\Models\Dimension;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

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
        );

        $this->action(
            function (Collection $records, array $data) {

                $record_remain = $data['remaining_record'];
                $records_remove = [];
                foreach ($records as $record) {
                    if (strval($record->id) !== $record_remain) {
                        $records_remove[] = $record->id;
                    };
                }

                $metrics_array = [];
                $references_array = [];

                foreach ($records as $record) {

                    $metrics = $record->metrics()->get();
                    foreach ($metrics as $metric) {
                        if (isset($metrics_array[$metric->pivot->metric_id])) {
                            if ($metrics_array[$metric->pivot->metric_id]['relation_notes'] == '') {
                                $metrics_array[$metric->pivot->metric_id]['relation_notes'] = $metric->pivot->relation_notes;
                            } else {
                                $metrics_array[$metric->pivot->metric_id]['relation_notes'] = $metrics_array[$metric->pivot->metric_id]['relation_notes'] . '. ' . $metric->pivot->relation_notes;
                            }
                        } else {
                            $metrics_array[$metric->pivot->metric_id]['relation_notes'] = $metric->pivot->relation_notes;
                        }
                    }

                    $references = $record->references()->get();
                    foreach ($references as $reference) {
                        if (isset($references_array[$reference->pivot->reference_id])) {
                            if ($references_array[$reference->pivot->reference_id]['relation_notes'] == '') {
                                $references_array[$reference->pivot->reference_id]['relation_notes'] = $reference->pivot->relation_notes;
                            } else {
                                $references_array[$reference->pivot->reference_id]['relation_notes'] = $references_array[$reference->pivot->reference_id]['relation_notes'] . '. ' . $reference->pivot->relation_notes;
                            }
                        } else {
                            $references_array[$reference->pivot->reference_id]['relation_notes'] = $reference->pivot->relation_notes;
                            $references_array[$reference->pivot->reference_id]['reference_type'] = $reference->pivot->reference_type;
                        }
                    }
                };

                Dimension::where('id', $record_remain)->first()->metrics()->sync($metrics_array);
                Dimension::where('id', $record_remain)->first()->references()->sync($references_array);
                Dimension::whereIn('id', $records_remove)->delete();

            }
        );
        

    }

}
