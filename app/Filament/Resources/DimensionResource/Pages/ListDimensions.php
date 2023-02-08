<?php

namespace App\Filament\Resources\DimensionResource\Pages;

use App\Filament\Resources\DimensionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDimensions extends ListRecords
{
    protected static string $resource = DimensionResource::class;

    public function getSubheading(): string
    {
        return 'This page is a placeholder. It will eventually allow a review of the information within the library per dimension, e.g. showing metrics + references etc grouped by dimension. This could potentially be combined with "sub-dimensions" to give a "map" / "network" view of the links between dimensions + sub-dimensions.';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
