<?php

namespace App\Filament\Resources\ToolResource\Pages;

use App\Filament\Resources\ToolResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTools extends ListRecords
{
    protected static string $resource = ToolResource::class;

        public function getSubheading(): string
    {
        return 'This page is a placeholder. It will eventually allow a review of the information within the library per assessment tool, e.g. showing metrics + references etc grouped by tool.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
