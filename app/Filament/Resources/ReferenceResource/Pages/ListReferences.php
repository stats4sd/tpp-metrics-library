<?php

namespace App\Filament\Resources\ReferenceResource\Pages;

use App\Filament\Resources\ReferenceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReferences extends ListRecords
{
    protected static string $resource = ReferenceResource::class;

    public function getSubheading(): string
    {
        return 'This page is a placeholder. It will eventually allow a review of the information within the library per reference, e.g. showing metrics + dimensions etc grouped by reference.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
