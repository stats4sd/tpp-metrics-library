<?php

namespace App\Filament\Resources\DiscussionPointResource\Pages;

use App\Filament\Resources\DiscussionPointResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiscussionPoints extends ListRecords
{
    protected static string $resource = DiscussionPointResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
