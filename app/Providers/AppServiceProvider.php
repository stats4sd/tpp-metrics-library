<?php

namespace App\Providers;

use App\Filament\Resources\DimensionResource;
use App\Filament\Resources\MetricPropertyResource;
use App\Filament\Resources\MetricResource;
use App\Filament\Resources\PropertyTypeResource;
use App\Filament\Resources\TopicResource;
use App\Models\Metric;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // probably a better place for this...
    }
}
