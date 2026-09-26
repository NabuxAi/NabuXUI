<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Second extends Component
{
    public function render()
    {
        return <<<'BLADE'
        <div>
            <main class="nx-page" wire:transition.navigate="nx-page" style="padding: 4rem 1rem; display: grid; gap: 1.5rem; justify-items: center">
                <x-nx::badge tone="accent" dot>Page two</x-nx::badge>
                <x-nx::text-reveal as="h1" style="margin: 0; font-size: var(--nx-text-5xl)">Arrived with a view transition</x-nx::text-reveal>
                <x-nx::button variant="primary" shape="pill" href="/livewire" wire:navigate icon="arrow-left">Back</x-nx::button>
            </main>
        </div>
        BLADE;
    }
}
