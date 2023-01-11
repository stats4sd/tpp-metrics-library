<?php

namespace App\Filament\Resources\MethodResource\Pages;

use App\Filament\Resources\MethodResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMethods extends ListRecords
{
    protected static string $resource = MethodResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
