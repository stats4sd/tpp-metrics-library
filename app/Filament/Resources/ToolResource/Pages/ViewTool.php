<?php

namespace App\Filament\Resources\ToolResource\Pages;

use App\Filament\Resources\ToolResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewTool extends ViewRecord
{
    protected static string $resource = ToolResource::class;

    public function getHeading(): string|Htmlable
    {
        $record = $this->getRecord();

        return "{$record->acronym}: {$record->name}";

    }

    public function getSubheading(): string|Htmlable|null
    {
        $metricsCount = '# Metrics: ' . $this->getRecord()->metrics->count();
        $frameworksCount = '# Frameworks: ' . $this->getRecord()->frameworks->count();
        $referencesCount = '# References: ' . $this->getRecord()->references->count();
        $dimensionCount = '# Dimensions: ' . $this->getRecord()->dimensions->count();
        $metricUsersCount = '# Metric Users: ' . $this->getRecord()->metricUsers->count();
        $themeCount = '# Themes: ' . $this->getRecord()->themes->count();
        $scaleCount = '# Scales: ' . $this->getRecord()->scales->count();

        return implode(' | ', [$metricsCount, $frameworksCount, $referencesCount, $dimensionCount, $metricUsersCount, $themeCount, $scaleCount]);
    }


}
