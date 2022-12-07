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

        Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder->groups([
                NavigationGroup::make("Metrics and Indicators")
                ->items([
                    ...MetricResource::getNavigationItems(),
                ]),
                NavigationGroup::make("AE Dimensions")
                ->items([
                    ...TopicResource::getNavigationItems(),
                    ...DimensionResource::getNavigationItems(),
                ]),
                NavigationGroup::make("Properties")
                ->items([
                    ...PropertyTypeResource::getNavigationItems(),
                    ...MetricPropertyResource::getNavigationItems(),
                ]),
            ]);
        });
    }
}
