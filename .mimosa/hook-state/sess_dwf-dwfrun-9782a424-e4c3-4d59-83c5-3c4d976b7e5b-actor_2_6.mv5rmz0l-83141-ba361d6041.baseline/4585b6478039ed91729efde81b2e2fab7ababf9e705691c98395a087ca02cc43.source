<?php

namespace NabuXUI\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

/**
 * An array field rendered as the nx multi-select — the searchable dropdown
 * with checkmark rows; the state binds an array of option values.
 */
class NxMultiSelect extends Field
{
    protected string $view = 'nabuxui-filament::forms.multi-select';

    protected array | Closure $options = [];

    protected string | \Closure | null $placeholder = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);
    }

    public function options(array | Closure $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function placeholder(string | \Closure | null $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /** @return array<string, string> */
    public function getOptions(): array
    {
        return $this->evaluate($this->options);
    }

    public function getPlaceholder(): ?string
    {
        return $this->evaluate($this->placeholder);
    }
}
