<?php

namespace App\Filament\Resources\FramingResource\Pages;

use App\Filament\Resources\FramingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class ListFramings extends ListRecords
{
    protected static string $resource = FramingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected ?string $heading = "View on Sustainability";

    public function getSubheading(): string|Htmlable|null
    {
        return new HtmlString('Holistic Tools, grouped by "View on Sustainability" categories from <a class="text-blue-700 underline" target="_blank" href="https://link.springer.com/article/10.1007/s13593-021-00674-3#Fig3">Chopin et al. (2021)</a>');
    }

}
