<?php

namespace App\Filament\Resources\FarmingSystemResource\Pages;

use App\Filament\Resources\FarmingSystemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFarmingSystems extends ListRecords
{
    protected static string $resource = FarmingSystemResource::class;

    public function getSubheading(): string
    {
        return 'This page is a placeholder. It will eventually allow a review of the information within the library per farming system, e.g. showing metrics + references etc grouped by farming system.';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
