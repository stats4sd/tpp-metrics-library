<?php

namespace App\Filament\Form\Components;

use Closure;
use Filament\Forms\Components\Concerns\CanBeAutocapitalized;
use Filament\Forms\Components\Concerns\CanBeAutocompleted;
use Filament\Forms\Components\Concerns\CanBeLengthConstrained as CanBeLengthConstrainedConcern;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Contracts\CanBeLengthConstrained;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;

class Textarea extends Field implements CanBeLengthConstrained
{
    use CanBeAutocapitalized;
    use CanBeAutocompleted;
    use CanBeLengthConstrainedConcern;
    use HasExtraInputAttributes;
    use HasPlaceholder;
    use HasExtraAlpineAttributes;
    use HasAffixes;

    protected string $view = 'forms::components.textarea';

    protected int | Closure | null $cols = null;

    protected int | Closure | null $rows = null;

    protected bool | Closure $shouldAutosize = false;

    public function autosize(bool | Closure $condition = true): static
    {
        $this->shouldAutosize = $condition;

        return $this;
    }

    public function cols(int | Closure | null $cols): static
    {
        $this->cols = $cols;

        return $this;
    }

    public function rows(int | Closure | null $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    public function getCols(): ?int
    {
        return $this->evaluate($this->cols);
    }

    public function getRows(): ?int
    {
        return $this->evaluate($this->rows);
    }

    public function shouldAutosize(): bool
    {
        return $this->rows === null || ((bool) $this->evaluate($this->shouldAutosize));
    }
}
