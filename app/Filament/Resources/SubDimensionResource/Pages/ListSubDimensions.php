<?php

namespace App\Filament\Resources\SubDimensionResource\Pages;

use App\Filament\Resources\SubDimensionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubDimensions extends ListRecords
{
    protected static string $resource = SubDimensionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
