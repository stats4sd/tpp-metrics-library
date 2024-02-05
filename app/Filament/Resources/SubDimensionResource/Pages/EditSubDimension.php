<?php

namespace App\Filament\Resources\SubDimensionResource\Pages;

use App\Filament\Resources\SubDimensionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubDimension extends EditRecord
{
    protected static string $resource = SubDimensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
