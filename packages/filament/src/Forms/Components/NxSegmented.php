<?php

namespace NabuXUI\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

/**
 * A single-value field rendered as the nx segmented control — the springy
 * thumb glides under the chosen option and the value binds the state path.
 */
class NxSegmented extends Field
{
    protected string $view = 'nabuxui-filament::forms.segmented';

    protected array | Closure $options = [];

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
