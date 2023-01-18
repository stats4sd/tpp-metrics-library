<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\MetricPropertyResource;
use App\Filament\Templates\MetricLinkRelationManager;
use Filament\Resources\Form;

class MetricPropertiesRelationManager extends MetricLinkRelationManager
{
    protected static string $relationship = 'metricProperties';
    protected static ?string $title = 'Properties';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return MetricPropertyResource::form($form);
    }


}
