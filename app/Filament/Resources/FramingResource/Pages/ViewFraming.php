<?php

namespace App\Filament\Resources\FramingResource\Pages;

use App\Filament\Resources\FramingResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewFraming extends ViewRecord
{
    protected static string $resource = FramingResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'View on Sustainability';
    }

    public function getSubheading(): string|Htmlable
    {
        return $this->getRecord()->name;
    }

}
