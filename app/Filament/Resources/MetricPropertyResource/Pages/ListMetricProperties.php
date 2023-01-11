<?php

namespace App\Filament\Resources\MetricPropertyResource\Pages;

use App\Filament\Resources\MetricPropertyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetricProperties extends ListRecords
{
    protected static string $resource = MetricPropertyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
