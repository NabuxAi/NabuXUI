<?php

namespace NabuXUI\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

/** A single-select field rendered as the nx combobox — searchable dropdown. */
class NxCombobox extends Field
{
    protected string $view = 'nabuxui-filament::forms.combobox';

    protected array | Closure $options = [];

    protected string | \Closure | null $placeholder = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();
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
