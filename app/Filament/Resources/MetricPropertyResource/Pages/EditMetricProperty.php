<?php

namespace App\Filament\Resources\MetricPropertyResource\Pages;

use App\Filament\Resources\MetricPropertyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetricProperty extends EditRecord
{
    protected static string $resource = MetricPropertyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
