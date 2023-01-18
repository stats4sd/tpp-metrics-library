<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\ScaleResource;
use App\Filament\Templates\MetricLinkRelationManager;
use Filament\Resources\Form;

class MetricScalesRelationManager extends MetricLinkRelationManager
{
    protected static string $relationship = 'metricScales';
    protected static ?string $title = 'Scales';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return ScaleResource::form($form);
    }


}
