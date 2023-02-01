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


    protected function handleRecordCreation(array $data): Model
    {
        // find the property keys
        $propertyKeys = preg_grep("/property_/", array_keys($data));

        $metricData = collect($data)->except($propertyKeys)->toArray();
        $propertyData = collect($data)->only($propertyKeys);

        //create the new metric (Dont save it for now)
        $metric = Metric::create($metricData);

        // sync properties
        $propertyIds = collect($propertyKeys)->map(fn($key) => str_replace('property_', '', $key))->toArray();

        $metric->properties()->sync($propertyIds);

        foreach($propertyData as $key  => $value) {

            $propertyId = str_replace('property_', '', $key);

            $link = PropertyLink::where('property_id', $propertyId)
                ->where('linked_id', $metric->id)
                ->where('linked_type', Metric::class)
                ->first();

            // handle select multiples
            if(is_array($value)) {
                //sync all values with $link
                $link->propertyOptions()->sync($value);
                continue;
            }

            // handle select one
            if(!Property::find($propertyId)->free_text && $value !== null) {


                $link->propertyOptions()->sync([$value]);
            }

            // handle free-text by putting the free-text into the 'notes' of the property_link table
            $metric->properties()->updateExistingPivot($propertyId, [
                'notes' => $value
            ]);

        }

        return $metric;


    }

}
