<?php

namespace NabuXUI\Filament\Forms\Components;

use Filament\Forms\Components\Field;

/**
 * A boolean field rendered as the nx switch. The label rides the switch
 * itself, so the wrapper's label stays hidden by default.
 */
class NxToggle extends Field
{
    protected string $view = 'nabuxui-filament::forms.toggle';

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
