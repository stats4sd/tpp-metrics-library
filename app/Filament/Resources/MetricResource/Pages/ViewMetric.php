<?php

namespace App\Filament\Resources\MetricResource\Pages;

use App\Filament\Resources\MetricResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMetric extends ViewRecord
{
    protected static string $resource = MetricResource::class;

    public function getHeading(): string
    {
        return 'Metric: ' . $this->getRecord()->title;
    }

    public function getSubheading(): string
    {
        $dimensionCount = '# Dimensions: ' . $this->getRecord()->dimensions->count();
        $scaleCount = '# Scales: ' . $this->getRecord()->scales->count();
        $usersCount = '# Users: ' . $this->getRecord()->metricUsers->count();
        $toolsCount = '# Tools: ' . $this->getRecord()->tools->count();
        $frameworksCount = '# Frameworks: ' . $this->getRecord()->frameworks->count();
        $geoCount = '# Geographies: ' . $this->getRecord()->geographies->count();
        $referencesCount = '# References: ' . $this->getRecord()->references->count();

        return implode(' | ', [$dimensionCount, $scaleCount, $usersCount, $toolsCount, $frameworksCount, $geoCount, $referencesCount]);
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
