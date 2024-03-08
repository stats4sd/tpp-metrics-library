<?php

namespace App\Providers;

use App\Filament\Resources\MethodResource;
use App\Filament\Resources\MetricPropertyResource;
use App\Filament\Resources\PropertyTypeResource;
use Filament\Forms\Components\Textarea;
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
        // set text area default number of rows across entire app
        Textarea::configureUsing(fn($config) => $config->rows(6));
    }
}
