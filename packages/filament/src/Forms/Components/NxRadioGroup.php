<?php

namespace NabuXUI\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

/** A single-select field rendered as the nx radio group. */
class NxRadioGroup extends Field
{
    protected string $view = 'nabuxui-filament::forms.radio-group';

    protected array | Closure $options = [];

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

    /** @return array<string, string> */
    public function getOptions(): array
    {
        return $this->evaluate($this->options);
    }
}
