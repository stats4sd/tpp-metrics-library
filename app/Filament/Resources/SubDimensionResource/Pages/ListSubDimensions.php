<?php

namespace App\Filament\Resources\SubDimensionResource\Pages;

use App\Filament\Resources\SubDimensionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubDimensions extends ListRecords
{
    protected static string $resource = SubDimensionResource::class;

    public function getSubheading(): string
    {
        return 'This page is a placeholder. It will eventually allow a review of the information within the library per sub-dimension, e.g. showing metrics + references etc grouped by sub-dimension. This could potentially be combined with "dimensions" to give a "map" / "network" view of the links between dimensions + dimensions.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
