<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\ToolResource;
use App\Filament\Templates\MetricLinkRelationManager;
use Filament\Resources\Form;

class MetricToolsRelationManager extends MetricLinkRelationManager
{
    protected static string $relationship = 'metricTools';
    protected static ?string $title = 'Tools';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return ToolResource::form($form);
    }

}
