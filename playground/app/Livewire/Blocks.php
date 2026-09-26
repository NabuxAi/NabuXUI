<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\Livewire\WithToasts;

/** /blocks/{group}: the Livewire demos of one group of morphin-parity blocks. */
#[Layout('layouts.app')]
class Blocks extends Component
{
    use WithToasts;

    public string $group = 'text';

    /** Free-form state the demos can bind with wire:model. */
    public array $state = [];

    public function mount(string $group): void
    {
        abort_unless(in_array($group, ['glass', 'text', 'actions', 'cards', 'data', 'menus'], true), 404);
        $this->group = $group;

        if ($group === 'glass') {
            $this->state = ['period' => 'week', 'volume' => 64, 'refraction' => true, 'iridescence' => false, 'tab' => 'home'];
        }
    }

    public function ping(string $message = 'Done'): void
    {
        $this->toast($message, tone: 'success');
    }

    public function render()
    {
        return view('livewire.blocks');
    }
}
