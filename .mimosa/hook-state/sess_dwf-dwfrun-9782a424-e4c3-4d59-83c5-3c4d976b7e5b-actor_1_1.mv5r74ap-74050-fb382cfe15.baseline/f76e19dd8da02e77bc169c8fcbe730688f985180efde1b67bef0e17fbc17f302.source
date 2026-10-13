<?php

namespace NabuXUI\Filament\Forms\Components;

use Filament\Forms\Components\Field;

/** A short-code field rendered as the nx OTP boxes. */
class NxOtp extends Field
{
    protected string $view = 'nabuxui-filament::forms.otp';

    protected int | \Closure $length = 6;

    protected bool | \Closure $alphanumeric = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();
        $this->rule('string');
    }

    public function length(int | \Closure $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function alphanumeric(bool | \Closure $condition = true): static
    {
        $this->alphanumeric = $condition;

        return $this;
    }

    public function getLength(): int
    {
        return $this->evaluate($this->length);
    }

    public function isAlphanumeric(): bool
    {
        return $this->evaluate($this->alphanumeric);
    }

    public function getLabel(): ?string
    {
        return parent::getLabel() ?? $this->getName();
    }
}
