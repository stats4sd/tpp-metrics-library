<?php

namespace App\Filament\Resources\GeographyResource\Pages;

use App\Filament\Resources\GeographyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeography extends EditRecord
{
    protected static string $resource = GeographyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
