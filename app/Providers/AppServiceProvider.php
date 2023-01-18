<?php

namespace App\Providers;

use App\Filament\Resources\DimensionResource;
use App\Filament\Resources\FrameworkResource;
use App\Filament\Resources\MethodResource;
use App\Filament\Resources\MetricPropertyResource;
use App\Filament\Resources\MetricResource;
use App\Filament\Resources\MetricUserResource;
use App\Filament\Resources\PropertyTypeResource;
use App\Filament\Resources\ScaleResource;
use App\Filament\Resources\ToolResource;
use App\Filament\Resources\TopicResource;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;
use pxlrbt\FilamentEnvironmentIndicator\FilamentEnvironmentIndicator;

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
        FilamentEnvironmentIndicator::configureUsing(function (FilamentEnvironmentIndicator $indicator) {
            $indicator->visible = fn() => auth()->user()?->hasRole('admin');
        }, isImportant: true);

        Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder
                ->item(...MetricResource::getNavigationItems())
                ->groups([
                    NavigationGroup::make('Topics')
                        ->items([
                            ...TopicResource::getNavigationItems(),
                            ...DimensionResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Tools, Methods + Frameworks')
                        ->items([
                            ...FrameworkResource::getNavigationItems(),
                            ...MethodResource::getNavigationItems(),
                            ...ToolResource::getNavigationItems(),
                        ]),
                    ])
                ->items([
                    ...MetricPropertyResource::getNavigationItems(),
                    ...MetricUserResource::getNavigationItems(),
                    ...ScaleResource::getNavigationItems(),
                ]);
        });
    }
}
