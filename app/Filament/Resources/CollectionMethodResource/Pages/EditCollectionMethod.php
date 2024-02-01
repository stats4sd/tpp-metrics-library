<?php

namespace App\Filament\Resources\CollectionMethodResource\Pages;

use App\Models\Property;
use Filament\Pages\Actions;
use App\Models\PropertyLink;
use App\Models\CollectionMethod;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CollectionMethodResource;
use Illuminate\Database\Eloquent\Model;

class EditCollectionMethod extends EditRecord
{
    protected static string $resource = CollectionMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

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

    public function handleRecordUpdate(CollectionMethod|Model $record, array $data): Model
    {
        $propertiesOfCollectionMethods = Property::where('default_type', CollectionMethod::class)->get();

        $propertyKeys = $propertiesOfCollectionMethods->map(fn($prop) => 'property_' . $prop->id)->toArray();
        $propertyNotesKeys = $propertiesOfCollectionMethods->map(fn($prop) => 'property_notes_' . $prop->id)->toArray();

        $collectionMethodData = collect($data)->except(array_merge($propertyKeys, $propertyNotesKeys))->toArray();

        $propertyData = collect($data)->only($propertyKeys);
        $propertyNotesData = collect($data)->only($propertyNotesKeys);


        // update collection method with collection method-level data
        $record->update($collectionMethodData);

        // handle propertydata
        $propertyData = collect($data)->only($propertyKeys);

        // For the next thing to work, we need to confirm that all the collection method properties are indeed linked to the collection method;

        $record->properties()->syncWithoutDetaching($propertiesOfCollectionMethods->pluck('id')->toArray());

        // go through and add any prop values from the creation form (if they exist)
        foreach ($propertyData as $key => $value) {

            $propertyId = str_replace('property_', '', $key);

            $link = PropertyLink::where('property_id', $propertyId)
                ->where('linked_id', $record->id)
                ->where('linked_type', CollectionMethod::class)
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
                ->where('linked_type', CollectionMethod::class)
                ->first();

            // handle free-text by putting the free-text into the 'notes' of the property_link table
            $record->properties()->updateExistingPivot($propertyId, [
                'relation_notes' => $value
            ]);
        }

        return $record;
    }

}
