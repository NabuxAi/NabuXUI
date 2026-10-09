<?php

namespace NabuXUI\Filament\Forms\Components;

use Filament\Forms\Components\Field;

/**
 * A numeric field rendered as the nx star rating. Values are 0–max; the
 * keyboard walks star by star like the playground demo.
 */
class NxRating extends Field
{
    protected string $view = 'nabuxui-filament::forms.rating';

    protected int | \Closure $max = 5;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default(0);
        $this->rule('integer');
        $this->hiddenLabel();
    }

    public function max(int | \Closure $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function getMaxValue(): int
    {
        return $this->evaluate($this->max);
    }

    public function getLabel(): ?string
    {
        return parent::getLabel() ?? $this->getName();
    }
}
