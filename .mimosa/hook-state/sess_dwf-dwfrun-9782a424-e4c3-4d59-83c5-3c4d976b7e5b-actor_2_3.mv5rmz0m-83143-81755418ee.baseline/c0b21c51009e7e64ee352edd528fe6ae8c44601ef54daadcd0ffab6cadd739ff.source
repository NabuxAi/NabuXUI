<?php

namespace NabuXUI\Filament\Forms\Components;

use Filament\Forms\Components\Field;

/** A boolean field rendered as the nx checkbox; the label rides the box. */
class NxCheckbox extends Field
{
    protected string $view = 'nabuxui-filament::forms.checkbox';

    protected function setUp(): void
    {
        parent::setUp();

        $this->default(false);
        $this->hiddenLabel();
        $this->rule('boolean');
    }

    public function getLabel(): ?string
    {
        return parent::getLabel() ?? $this->getName();
    }
}
