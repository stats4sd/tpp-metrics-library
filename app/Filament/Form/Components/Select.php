<?php

namespace App\Filament\Form\Components;

use Filament\Forms\Components\Actions\Action;

class Select extends \Filament\Forms\Components\Select
{
    public function getSuffixAction(): ?Action
    {
        $action = $this->getBaseSuffixAction();

        if ($action) {
            return $action;
        }

        $createOptionAction = $this->getCreateOptionAction();

        if (!$createOptionAction) {
            return null;
        }

        return $createOptionAction;
    }
}
