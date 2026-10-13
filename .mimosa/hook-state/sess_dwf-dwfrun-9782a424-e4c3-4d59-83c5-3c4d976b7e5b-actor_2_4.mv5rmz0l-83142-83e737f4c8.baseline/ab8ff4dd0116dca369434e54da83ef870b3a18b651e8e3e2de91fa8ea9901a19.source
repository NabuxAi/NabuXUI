<?php

namespace NabuXUI\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

/**
 * An array field rendered as the nx tag input — free-form chips offered
 * suggestions while typing; the state binds an array of tag strings.
 */
class NxTagInput extends Field
{
    protected string $view = 'nabuxui-filament::forms.tag-input';

    protected array | Closure $suggestions = [];

    protected int | \Closure | null $max = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);
        $this->hiddenLabel();
    }

    public function suggestions(array | Closure $suggestions): static
    {
        $this->suggestions = $suggestions;

        return $this;
    }

    public function max(int | \Closure | null $max): static
    {
        $this->max = $max;

        return $this;
    }

    /** @return list<string> */
    public function getSuggestions(): array
    {
        return $this->evaluate($this->suggestions);
    }

    public function getMaxValue(): ?int
    {
        return $this->evaluate($this->max);
    }

    public function getLabel(): ?string
    {
        return parent::getLabel() ?? $this->getName();
    }
}
