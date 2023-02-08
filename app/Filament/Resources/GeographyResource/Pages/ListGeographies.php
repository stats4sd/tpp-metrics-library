<?php

namespace App\Filament\Resources\GeographyResource\Pages;

use App\Filament\Resources\GeographyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeographies extends ListRecords
{
    protected static string $resource = GeographyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
