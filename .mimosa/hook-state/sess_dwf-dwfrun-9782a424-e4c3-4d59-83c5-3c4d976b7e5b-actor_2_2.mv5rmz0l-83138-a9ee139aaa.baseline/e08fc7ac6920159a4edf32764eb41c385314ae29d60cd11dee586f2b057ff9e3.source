<?php

namespace NabuXUI\Filament\Forms\Components;

use Filament\Forms\Components\Field;

/**
 * A numeric field rendered as the nx number field — the scrubbing stepper
 * input. State comes back as a numeric string; cast with ->numeric().
 */
class NxNumberField extends Field
{
    protected string $view = 'nabuxui-filament::forms.number-field';

    protected int | float | \Closure | null $min = null;

    protected int | float | \Closure | null $max = null;

    protected int | float | \Closure $step = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();
    }

        public function min(int | float | \Closure | null $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function max(int | float | \Closure | null $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function step(int | float | \Closure $step): static
    {
        $this->step = $step;

        return $this;
    }

    public function getMinValue(): int | float | null
    {
        return $this->evaluate($this->min);
    }

    public function getMaxValue(): int | float | null
    {
        return $this->evaluate($this->max);
    }

    public function getStep(): int | float
    {
        return $this->evaluate($this->step);
    }
}
