<?php

namespace NabuXUI\Livewire;

use NabuXUI\NabuXUI;

/**
 * Toasts from a Livewire component.
 *
 *   use NabuXUI\Livewire\WithToasts;
 *
 *   $this->toast('Saved', 'Your agent is live.', tone: 'success');
 *   $this->toastAfterRedirect('Welcome back');  // survives $this->redirect(...)
 *
 * The <x-nx::toaster> on the page listens for the `nx-toast` browser event.
 */
trait WithToasts
{
    /**
     * @param  'neutral'|'accent'|'success'|'warning'|'danger'|'info'  $tone
     * @param  array{label: string, href?: string}|null  $action
     */
    public function toast(string $title, ?string $description = null, string $tone = 'neutral', ?int $duration = null, ?array $action = null): void
    {
        $this->dispatch('nx-toast', ...array_filter(compact('title', 'description', 'tone', 'duration', 'action'), fn ($v) => $v !== null));
    }

    public function toastAfterRedirect(string $title, ?string $description = null, string $tone = 'neutral', ?int $duration = null): void
    {
        NabuXUI::flashToast($title, $description, $tone, $duration);
    }
}
