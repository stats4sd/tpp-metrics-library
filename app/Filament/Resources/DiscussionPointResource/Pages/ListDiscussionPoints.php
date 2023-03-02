<?php

namespace App\Filament\Resources\DiscussionPointResource\Pages;

use App\Filament\Resources\DiscussionPointResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Layout;

class ListDiscussionPoints extends ListRecords
{
    protected static string $resource = DiscussionPointResource::class;

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContentCollapsible;
    }

    protected function getActions(): array
    {
        return [
        ];
    }
}
