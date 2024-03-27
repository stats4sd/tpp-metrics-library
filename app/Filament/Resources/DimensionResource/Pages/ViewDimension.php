<?php

namespace App\Filament\Resources\DimensionResource\Pages;

use Filament\Actions;
use App\Models\Dimension;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\DimensionResource;

class ViewDimension extends ViewRecord
{
    protected static string $resource = DimensionResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Dimension: ' . $this->getRecord()->name;
    }

    public function getSubheading(): string|Htmlable|null
    {
        $metricCount =  '# Metrics: ' . $this->getRecord()->metrics->count();
        $referenceCount = '# References: ' . $this->getRecord()->references->count();

        return $metricCount . ' | ' . $referenceCount;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
