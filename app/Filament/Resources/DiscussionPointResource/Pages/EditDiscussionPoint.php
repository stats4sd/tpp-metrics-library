<?php

namespace App\Filament\Resources\DiscussionPointResource\Pages;

use App\Filament\Resources\DiscussionPointResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscussionPoint extends EditRecord
{
    protected static string $resource = DiscussionPointResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
