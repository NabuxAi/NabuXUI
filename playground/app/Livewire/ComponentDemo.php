<?php

namespace App\Livewire;

use App\Livewire\Concerns\SwitchesDemoLocale;
use App\Support\DemoCatalog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\Livewire\WithToasts;

/**
 * /components/{group}/{slug} — one component: its real scenarios (the partial
 * at demos/components/{group}/{slug}), the important props and a copyable
 * snippet, with the previous/next demo of the same group.
 */
#[Layout('layouts.app')]
class ComponentDemo extends Component
{
    use SwitchesDemoLocale;
    use WithToasts;

    public string $group = '';

    public string $slug = '';

    /** Free-form state the scenario partials can bind with wire:model/$set. */
    public array $state = [];

    public function mount(string $group, string $slug): void
    {
        abort_unless(DemoCatalog::find($group, $slug) !== null, 404);

        $this->group = $group;
        $this->slug = $slug;
        $this->rememberLocale();
    }

    /** The scenarios' toast hook, like the block demos' ping(). */
    public function ping(string $message = 'Done'): void
    {
        $this->toast($message, tone: 'success');
    }

    /** The scenarios' slow action, so wire:loading can show a button's spinner. */
    public function save(string $message = 'Saved'): void
    {
        usleep(900_000);
        $this->toast($message, tone: 'success');
    }

    public function render()
    {
        $neighbors = DemoCatalog::neighbors($this->group, $this->slug);

        return view('livewire.component-demo', [
            'demo' => DemoCatalog::find($this->group, $this->slug),
            'prev' => $neighbors['prev'],
            'next' => $neighbors['next'],
            'hasScenarios' => DemoCatalog::hasScenarios($this->group, $this->slug),
        ]);
    }
}
