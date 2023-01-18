<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\MethodResource;
use App\Filament\Templates\MetricLinkRelationManager;
use Filament\Resources\Form;

class MetricMethodsRelationManager extends MetricLinkRelationManager
{
    protected static string $relationship = 'metricMethods';
    protected static ?string $title = 'Methods';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return MethodResource::form($form);
    }

}
