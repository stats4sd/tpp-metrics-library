<?php

namespace App\Filament\Resources\ToolResource\Pages;

use App\Filament\Resources\ToolResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTool extends EditRecord
{
    protected static string $resource = ToolResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
