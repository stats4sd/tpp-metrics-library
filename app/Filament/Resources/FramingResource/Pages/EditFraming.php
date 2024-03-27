<?php

namespace App\Filament\Resources\FramingResource\Pages;

use App\Filament\Resources\FramingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFraming extends EditRecord
{
    protected static string $resource = FramingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
