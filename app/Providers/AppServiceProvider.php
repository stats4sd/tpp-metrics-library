<?php

namespace App\Providers;

use App\Filament\Resources\MethodResource;
use App\Filament\Resources\MetricPropertyResource;
use App\Filament\Resources\PropertyTypeResource;
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


//        FilamentEnvironmentIndicator::configureUsing(function (FilamentEnvironmentIndicator $indicator) {
//            $indicator->visible = fn() => auth()->user()?->hasRole('admin');
//        }, isImportant: true);

        // Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
        //     return $builder
        //         ->item(...MetricResource::getNavigationItems())
        //         ->item(...PropertyResource::getNavigationItems())
        //         ->groups([
        //             NavigationGroup::make('Review Tools')
        //                 ->items([
        //                     ...DiscussionPointResource::getNavigationItems(),
        //                     ...FlagResource::getNavigationItems(),
        //                     ...FeedbackResource::getNavigationItems(),
        //                 ]),
        //             NavigationGroup::make('Topics')
        //                 ->items([
        //                     ...TopicResource::getNavigationItems(),
        //                     ...DimensionResource::getNavigationItems(),
        //                     ...SubDimensionResource::getNavigationItems(),
        //                 ]),
        //             NavigationGroup::make('Scales, Users, Use Cases')
        //                 ->items([
        //                     ...ScaleResource::getNavigationItems(),
        //                     ...MetricUserResource::getNavigationItems(),
        //                 ]),
        //             NavigationGroup::make('Tools, Methods + Frameworks')
        //                 ->items([
        //                     ...ToolResource::getNavigationItems(),
        //                     ...CollectionMethodResource::getNavigationItems(),
        //                     ...FrameworkResource::getNavigationItems(),
        //                 ]),
        //             NavigationGroup::make('Systems and Geographies')
        //                 ->items([
        //                     ...FarmingSystemResource::getNavigationItems(),
        //                     ...GeographyResource::getNavigationItems(),
        //                 ]),
        //             NavigationGroup::make('References')
        //                 ->items([
        //                     ...ReferenceResource::getNavigationItems(),
        //                 ]),
        //             NavigationGroup::make('Imports')
        //                 ->items([
        //                     ...ImportResource::getNavigationItems(),
        //                 ]),
        //             NavigationGroup::make('Users and Roles')
        //             ->items([
        //                 ...UserResource::getNavigationItems(),
        //                 ...RoleResource::getNavigationItems(),
        //                 ...PermissionResource::getNavigationItems(),
        //             ]),
        //         ]);
        // });
    }
}
