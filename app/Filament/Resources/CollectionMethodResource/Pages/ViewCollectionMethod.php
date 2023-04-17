<?php

namespace App\Filament\Resources\CollectionMethodResource\Pages;

use App\Filament\Resources\CollectionMethodResource;
use App\Models\CollectionMethod;
use Filament\Resources\Pages\ViewRecord;

class ViewCollectionMethod extends ViewRecord
{
    protected static string $resource = CollectionMethodResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // get property information

        $collectionMethod = $this->getRecord();
        $properties = $collectionMethod->properties;

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
                        ->where('linked_id', $collectionMethod->id)
                        ->where('linked_type', CollectionMethod::class)
                        ->first()
                        ?->propertyOptions
                        ->pluck('id')
                        ->toArray();

                    continue;
                }

                // final case of single select

                $data['property_' . $property->id] = $property
                        ->propertyLinks
                        ->where('linked_id', $collectionMethod->id)
                        ->where('linked_type', CollectionMethod::class)
                        ->first()
                        ?->propertyOptions
                        // there should be only one (or none)
                        ->first()
                        ?->id ?? null;

            }
        }

        return $data;

    }
}
