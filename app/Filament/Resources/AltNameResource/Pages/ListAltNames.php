<?php

namespace App\Filament\Resources\AltNameResource\Pages;

use App\Filament\Resources\AltNameResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAltNames extends ListRecords
{
    protected static string $resource = AltNameResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
