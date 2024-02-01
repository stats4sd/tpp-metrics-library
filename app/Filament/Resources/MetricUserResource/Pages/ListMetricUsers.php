<?php

namespace App\Filament\Resources\MetricUserResource\Pages;

use App\Filament\Resources\MetricUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetricUsers extends ListRecords
{
    protected static string $resource = MetricUserResource::class;

    public function getSubheading(): string
    {
        return 'This page is a placeholder. It will eventually allow a review of the information within the library per user group / use case, e.g. showing metrics grouped by user group.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
