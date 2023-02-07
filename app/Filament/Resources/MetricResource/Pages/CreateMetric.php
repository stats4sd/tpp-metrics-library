<?php

namespace App\Filament\Resources\MetricResource\Pages;

use App\Filament\Resources\MetricResource;
use App\Models\Metric;
use App\Models\Property;
use App\Models\PropertyLink;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMetric extends CreateRecord
{
    protected static string $resource = MetricResource::class;

    protected static ?string $title = "Create a new metric entry";

    public function getSubheading(): string
    {
        return "Enter details of the new metric entry below. The minimum required is the title. Everything else can be added later.";
    }

    protected function handleRecordCreation(array $data): Model
    {
        // find the property keys
        $propertiesOfMetrics = Property::where('default_type', Metric::class)->get();


        $propertyKeys = $propertiesOfMetrics->map(fn($prop) => 'property_' . $prop->id)->toArray();
        $propertyNotesKeys = $propertiesOfMetrics->map(fn($prop) => 'property_notes_' . $prop->id)->toArray();

        $metricData = collect($data)->except(array_merge($propertyKeys, $propertyNotesKeys))->toArray();

        $propertyData = collect($data)->only($propertyKeys);
        $propertyNotesData = collect($data)->only($propertyNotesKeys);

        //create the new metric
        $metric = Metric::create($metricData);

        // setup 'empty' links between the metric and *all* properties that should be linked to metrics by default.
        $metric->properties()->sync($propertiesOfMetrics->pluck('id')->toArray());

        // go through and add any prop values from the creation form (if they exist)
        foreach ($propertyData as $key => $value) {

            $propertyId = str_replace('property_', '', $key);

            $link = PropertyLink::where('property_id', $propertyId)
                ->where('linked_id', $metric->id)
                ->where('linked_type', Metric::class)
                ->first();

            // handle select multiples and select_ones by ensuring Value is an array

            if (!is_array($value)) {
                $value = [$value];
            }


            $link->propertyOptions()->sync($value);

        }

        // handle adding of property notes to property_links
        foreach ($propertyNotesData as $key => $value) {
            $propertyId = str_replace('property_notes_', '', $key);

            $link = PropertyLink::where('property_id', $propertyId)
                ->where('linked_id', $metric->id)
                ->where('linked_type', Metric::class)
                ->first();

            // handle free-text by putting the free-text into the 'notes' of the property_link table
            $metric->properties()->updateExistingPivot($propertyId, [
                'notes' => $value
            ]);
        }

        return $metric;


    }

}
