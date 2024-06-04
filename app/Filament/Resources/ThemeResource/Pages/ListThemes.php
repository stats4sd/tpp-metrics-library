<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Models\Theme;
use Filament\Actions;
use App\Filament\Resources\ThemeResource;
use Filament\Resources\Pages\ListRecords;

class ListThemes extends ListRecords
{
    protected static string $resource = ThemeResource::class;

    public function getSubheading(): string
    {
        return '# Themes: ' . Theme::count();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
