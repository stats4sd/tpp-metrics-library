<?php

namespace App\Filament\Resources\MetricResource\Pages;

use App\Filament\Resources\MetricResource;
use App\Models\Metric;
use App\Models\Property;
use App\Models\PropertyLink;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMetric extends EditRecord
{
    protected static string $resource = MetricResource::class;

    public function getTitle(): string
    {
        return "{$this->getRecord()?->title}";
    }

    public function getSubheading(): string
    {
        if ($metric = $this->getRecord()) {
            return "Last Updated on {$metric->updated_at}";
        }

        return '';
    }

    public function getContentTabLabel(): string
    {
        return 'Metric';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // get property information

        $metric = $this->getRecord();
        $properties = $metric->properties;

        foreach ($properties as $property) {

            // handle free text notes fields
            if ($property->free_text) {
                $data['property_notes_' . $property->id] = $property->pivot->relation_notes ?? null;

            }

            // handle select fields
            if ($property->select_options) {

                if ($property->select_multiple) {
                    $data['property_' . $property->id] = $property
                        ->propertyLinks
                        ->where('linked_id', $metric->id)
                        ->where('linked_type', Metric::class)
                        ->first()
                        ?->propertyOptions
                        ->pluck('id')
                        ->toArray();

                    continue;
                }

                // final case of single select

                $data['property_' . $property->id] = $property
                        ->propertyLinks
                        ->where('linked_id', $metric->id)
                        ->where('linked_type', Metric::class)
                        ->first()
                        ?->propertyOptions
                        // there should be only one (or none)
                        ->first()
                        ?->id ?? null;

            }
        }

        return $data;

    }

    public function handleRecordUpdate(Metric|Model $record, array $data): Model
    {
        $propertiesOfMetrics = Property::where('default_type', Metric::class)->get();

        $propertyKeys = $propertiesOfMetrics->map(fn($prop) => 'property_' . $prop->id)->toArray();
        $propertyNotesKeys = $propertiesOfMetrics->map(fn($prop) => 'property_notes_' . $prop->id)->toArray();

        $metricData = collect($data)->except(array_merge($propertyKeys, $propertyNotesKeys))->toArray();

        $propertyData = collect($data)->only($propertyKeys);
        $propertyNotesData = collect($data)->only($propertyNotesKeys);


        // update metric with metric-level data
        $record->update($metricData);

        // handle propertydata
        $propertyData = collect($data)->only($propertyKeys);

        // For the next thing to work, we need to confirm that all the metric properties are indeed linked to the metric;

        $record->properties()->syncWithoutDetaching($propertiesOfMetrics->pluck('id')->toArray());

        // go through and add any prop values from the creation form (if they exist)
        foreach ($propertyData as $key => $value) {

            $propertyId = str_replace('property_', '', $key);

            $link = PropertyLink::where('property_id', $propertyId)
                ->where('linked_id', $record->id)
                ->where('linked_type', Metric::class)
                ->first();

            // handle select multiples and select_ones by ensuring Value is an array

            if ($value && !is_array($value)) {
                $value = [$value];
            }

            $link->propertyOptions()->sync($value);

        }

        // handle adding of property notes to property_links
        foreach ($propertyNotesData as $key => $value) {
            $propertyId = str_replace('property_notes_', '', $key);

            $link = PropertyLink::where('property_id', $propertyId)
                ->where('linked_id', $record->id)
                ->where('linked_type', Metric::class)
                ->first();

            // handle free-text by putting the free-text into the 'notes' of the property_link table
            $record->properties()->updateExistingPivot($propertyId, [
                'relation_notes' => $value
            ]);
        }

        return $record;
    }
}
