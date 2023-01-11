<?php

namespace App\Filament\Resources\AltNameResource\Pages;

use App\Filament\Resources\AltNameResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAltName extends EditRecord
{
    protected static string $resource = AltNameResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
