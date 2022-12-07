<?php

namespace App\Filament\Resources\DimensionResource\Pages;

use App\Filament\Resources\DimensionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDimension extends EditRecord
{
    protected static string $resource = DimensionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
