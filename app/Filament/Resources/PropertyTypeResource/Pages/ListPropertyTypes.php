<?php

namespace App\Filament\Resources\PropertyTypeResource\Pages;

use App\Filament\Resources\PropertyTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyTypes extends ListRecords
{
    protected static string $resource = PropertyTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
