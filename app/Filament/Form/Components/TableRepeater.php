<?php

namespace App\Filament\Form\Components;

use Filament\Forms\Components\Concerns\HasAffixes;

class TableRepeater extends \Awcodes\FilamentTableRepeater\Components\TableRepeater
{
    use HasAffixes;
    protected string $view = 'forms::components.repeater-table';
}
