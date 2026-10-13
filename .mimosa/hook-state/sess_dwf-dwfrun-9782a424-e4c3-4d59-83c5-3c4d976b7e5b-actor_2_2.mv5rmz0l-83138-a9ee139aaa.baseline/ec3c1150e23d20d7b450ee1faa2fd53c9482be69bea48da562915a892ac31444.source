<?php

namespace NabuXUI\Filament\Forms\Components;

use Filament\Forms\Components\Field;

/** A time field rendered as the nx time picker. */
class NxTimePicker extends Field
{
    protected string $view = 'nabuxui-filament::forms.time-picker';

    protected int | \Closure $minuteStep = 1;

    public function minuteStep(int | \Closure $step): static
    {
        $this->minuteStep = $step;

        return $this;
    }

    public function getMinuteStep(): int
    {
        return $this->evaluate($this->minuteStep);
    }
}
