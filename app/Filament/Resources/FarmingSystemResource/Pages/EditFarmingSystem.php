<?php

namespace App\Filament\Resources\FarmingSystemResource\Pages;

use App\Filament\Resources\FarmingSystemResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFarmingSystem extends EditRecord
{
    protected static string $resource = FarmingSystemResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
