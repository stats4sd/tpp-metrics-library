<?php

namespace App\Filament\Resources\CollectionMethodResource\Pages;

use App\Filament\Resources\CollectionMethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCollectionMethods extends ListRecords
{
    protected static string $resource = CollectionMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
