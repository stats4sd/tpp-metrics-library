<?php

namespace App\Filament\Resources\FrameworkResource\Pages;

use App\Filament\Resources\FrameworkResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFrameworks extends ListRecords
{
    protected static string $resource = FrameworkResource::class;

    public function getSubheading(): string
    {
        return 'This page is a placeholder. It will eventually allow a review of the information within the library per framework, e.g. showing metrics + references etc grouped by framework.';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
