<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\MetricUserResource;
use App\Filament\Templates\MetricLinkRelationManager;
use Filament\Resources\Form;

class MetricUsersRelationManager extends MetricLinkRelationManager
{
    protected static string $relationship = 'metricUsers';
    protected static ?string $title = 'User Types';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return MetricUserResource::form($form);
    }

}
