<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\FlagResource;
use App\Filament\Resources\ToolResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\ScaleResource;
use App\Filament\Resources\TopicResource;
use App\Filament\Resources\ImportResource;
use App\Filament\Resources\MethodResource;
use App\Filament\Resources\MetricResource;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\FeedbackResource;
use App\Filament\Resources\PropertyResource;
use App\Filament\Resources\DimensionResource;
use App\Filament\Resources\FrameworkResource;
use App\Filament\Resources\GeographyResource;
use App\Filament\Resources\ReferenceResource;
use App\Filament\Resources\MetricUserResource;
use App\Filament\Resources\PropertyTypeResource;
use App\Filament\Resources\SubDimensionResource;
use App\Filament\Resources\FarmingSystemResource;
use App\Filament\Resources\MetricPropertyResource;
use App\Filament\Resources\DiscussionPointResource;
use App\Filament\Resources\CollectionMethodResource;
use Phpsa\FilamentAuthentication\Resources\RoleResource;
use Phpsa\FilamentAuthentication\Resources\UserResource;
use Phpsa\FilamentAuthentication\Resources\PermissionResource;

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
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');
        });

//        FilamentEnvironmentIndicator::configureUsing(function (FilamentEnvironmentIndicator $indicator) {
//            $indicator->visible = fn() => auth()->user()?->hasRole('admin');
//        }, isImportant: true);

        Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder
                ->item(...MetricResource::getNavigationItems())
                ->item(...PropertyResource::getNavigationItems())
                ->groups([
                    NavigationGroup::make('Review Tools')
                        ->items([
                            ...DiscussionPointResource::getNavigationItems(),
                            ...FlagResource::getNavigationItems(),
                            ...FeedbackResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Topics')
                        ->items([
                            ...TopicResource::getNavigationItems(),
                            ...DimensionResource::getNavigationItems(),
                            ...SubDimensionResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Scales, Users, Use Cases')
                        ->items([
                            ...ScaleResource::getNavigationItems(),
                            ...MetricUserResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Tools, Methods + Frameworks')
                        ->items([
                            ...ToolResource::getNavigationItems(),
                            ...CollectionMethodResource::getNavigationItems(),
                            ...FrameworkResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Systems and Geographies')
                        ->items([
                            ...FarmingSystemResource::getNavigationItems(),
                            ...GeographyResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('References')
                        ->items([
                            ...ReferenceResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Imports')
                        ->items([
                            ...ImportResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Users and Roles')
                    ->items([
                        ...UserResource::getNavigationItems(),
                        ...RoleResource::getNavigationItems(),
                        ...PermissionResource::getNavigationItems(),
                    ]),
                ]);
        });
    }
}
