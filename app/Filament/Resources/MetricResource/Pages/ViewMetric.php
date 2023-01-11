<?php

namespace App\Filament\Resources\MetricResource\Pages;

use App\Filament\Resources\MetricResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMetric extends ViewRecord
{
    protected static string $resource = MetricResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
