<?php

namespace App\Filament\Resources\FarmingSystemResource\Pages;

use App\Filament\Resources\FarmingSystemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFarmingSystems extends ListRecords
{
    protected static string $resource = FarmingSystemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
