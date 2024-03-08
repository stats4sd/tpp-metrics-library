<?php

namespace App\Filament\Widgets;

use App\Models\Tool;
use App\Models\Scale;
use App\Models\Theme;
use App\Models\Metric;
use App\Models\Country;
use App\Models\Dimension;
use App\Models\Framework;
use App\Models\Geography;
use App\Models\MetricUser;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $metricCount = Stat::make('Total Metrics', Metric::count());
        $themeCount = Stat::make('Total Themes', Theme::count());
        $dimensionCount = Stat::make('Total Dimensions', Dimension::count());
        $geographyCount = Stat::make('Total Geographies', Geography::count());
        $countryCount = Stat::make('Total Countries', Country::count());
        $toolCount = Stat::make('Total Tools', Tool::count());
        $frameworkCount = Stat::make('Total Frameworks', Framework::count());
        $scaleCount = Stat::make('Total Scales', Scale::count());
        $metricUserCount = Stat::make('Total Metric Users', MetricUser::count());

        return [$metricCount, $themeCount, $dimensionCount, $geographyCount, $countryCount, $toolCount, $frameworkCount, $scaleCount, $metricUserCount];
    }
}
