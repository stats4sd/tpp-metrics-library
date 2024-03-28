<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Filament\Resources\ThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewTheme extends ViewRecord
{
    protected static string $resource = ThemeResource::class;

    public function getHeading(): string|Htmlable
    {
        return 'Theme: ' . $this->getRecord()->name;
    }

    public function getSubheading(): string|Htmlable|null
    {
        $metricCount =  '# Metrics: ' . $this->getRecord()->metrics->count();
        $themeTypeCount = '# Theme Types: ' . $this->getRecord()->themeTypes->count();
        $toolCount = '# Tools: ' . $this->getRecord()->tools->count();

        return $metricCount . ' | ' . $themeTypeCount . ' | ' . $toolCount;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
