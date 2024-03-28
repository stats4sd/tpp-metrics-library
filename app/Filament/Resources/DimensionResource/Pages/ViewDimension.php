<?php

namespace App\Filament\Resources\DimensionResource\Pages;

use App\Filament\Resources\DimensionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

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
        $toolCount =  '# Tools: ' . $this->getRecord()->tools->count();

        return $metricCount . ' | ' . $referenceCount . ' | ' . $toolCount;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
