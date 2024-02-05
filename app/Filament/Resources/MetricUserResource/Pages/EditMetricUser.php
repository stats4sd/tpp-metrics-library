<?php

namespace App\Filament\Resources\MetricUserResource\Pages;

use App\Filament\Resources\MetricUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetricUser extends EditRecord
{
    protected static string $resource = MetricUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
