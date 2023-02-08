<?php

namespace App\Filament\Resources\GeographyResource\Pages;

use App\Filament\Resources\GeographyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeographies extends ListRecords
{
    protected static string $resource = GeographyResource::class;

            public function getSubheading(): string
    {
        return 'This page is a placeholder. It will eventually allow a review of the information within the library per geography, e.g. showing metrics + references etc grouped by region/region type.';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
