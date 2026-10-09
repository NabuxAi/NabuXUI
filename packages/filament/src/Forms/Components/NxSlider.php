<?php

namespace NabuXUI\Filament\Forms\Components;

use Filament\Forms\Components\Field;

/**
 * A numeric field rendered as the nx slider, with the live value bubble on
 * while dragging. State comes back as a numeric string; cast with ->numeric()
 * or ->integer() as needed.
 */
class NxSlider extends Field
{
    protected string $view = 'nabuxui-filament::forms.slider';

    protected int | float | \Closure $min = 0;

    protected int | float | \Closure $max = 100;

    protected int | float | \Closure $step = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();
    }

    public function min(int | float | \Closure $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function max(int | float | \Closure $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function step(int | float | \Closure $step): static
    {
        $this->step = $step;

        return $this;
    }

    public function getMinValue(): int | float
    {
        return $this->evaluate($this->min);
    }

    public function getMaxValue(): int | float
    {
        return $this->evaluate($this->max);
    }

    public function getStep(): int | float
    {
        return $this->evaluate($this->step);
    }

    public function getLabel(): ?string
    {
        return parent::getLabel() ?? $this->getName();
    }
}
