<?php

namespace NabuXUI\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

/**
 * A single-select field rendered as the nx chip filter — the trending-tags
 * row with the accent thumb, one chip per option and optional counts.
 */
class NxChipFilter extends Field
{
    protected string $view = 'nabuxui-filament::forms.chip-filter';

    protected array | Closure $options = [];

    protected array | Closure $counts = [];

    public function options(array | Closure $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function counts(array | Closure $counts): static
    {
        $this->counts = $counts;

        return $this;
    }

    /** @return array<string, string> */
    public function getOptions(): array
    {
        return $this->evaluate($this->options);
    }

    /** @return array<string, int|string> */
    public function getCounts(): array
    {
        return $this->evaluate($this->counts);
    }
}
