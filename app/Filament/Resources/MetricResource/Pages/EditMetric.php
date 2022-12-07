<?php

namespace App\Filament\Resources\MetricResource\Pages;

use App\Filament\Resources\MetricResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetric extends EditRecord
{
    protected static string $resource = MetricResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
