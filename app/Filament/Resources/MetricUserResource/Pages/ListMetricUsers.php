<?php

namespace App\Filament\Resources\MetricUserResource\Pages;

use App\Filament\Resources\MetricUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetricUsers extends ListRecords
{
    protected static string $resource = MetricUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
