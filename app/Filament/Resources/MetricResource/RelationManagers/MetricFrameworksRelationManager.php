<?php

namespace App\Filament\Resources\MetricResource\RelationManagers;

use App\Filament\Resources\FrameworkResource;
use App\Filament\Templates\MetricLinkRelationManager;
use Filament\Resources\Form;

class MetricFrameworksRelationManager extends MetricLinkRelationManager
{
    protected static string $relationship = 'metricFrameworks';
    protected static ?string $title = 'Frameworks';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return FrameworkResource::form($form);
    }

}
