<?php

namespace App\Filament\Resources\MetricResource\Pages;

use App\Filament\Resources\MetricResource;
use App\Models\Metric;
use App\Models\Property;
use App\Models\PropertyLink;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMetric extends CreateRecord
{
    protected static string $resource = MetricResource::class;

    protected static ?string $title = "Create a new metric entry";

    public function getSubheading(): string
    {
        return "Enter details of the new metric entry below. The minimum required is the title. Everything else can be added later.";
    }

}
