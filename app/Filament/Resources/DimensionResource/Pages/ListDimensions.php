<?php

namespace App\Filament\Resources\DimensionResource\Pages;

use App\Filament\Resources\DimensionResource;
use App\Models\Dimension;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDimensions extends ListRecords
{
    protected static string $resource = DimensionResource::class;

    public function getSubheading(): string
    {
         return '# Dimensions: ' . Dimension::count();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
