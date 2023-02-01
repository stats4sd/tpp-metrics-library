<?php

namespace App\Filament\Resources\MetricResource\Pages;

use App\Filament\Resources\MetricResource;
use App\Models\Metric;
use Filament\Resources\Pages\ViewRecord;

class ViewMetric extends ViewRecord
{
    protected static string $resource = MetricResource::class;

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

            // handle free text

            if ($property->free_text) {
                $data['property_' . $property->id] = $property->pivot->notes ?? null;
                continue;
            }

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
            continue;
        }

        return $data;

    }
}
