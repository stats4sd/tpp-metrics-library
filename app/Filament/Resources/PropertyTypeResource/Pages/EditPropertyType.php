<?php

namespace App\Filament\Resources\PropertyTypeResource\Pages;

use App\Filament\Resources\PropertyTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyType extends EditRecord
{
    protected static string $resource = PropertyTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
