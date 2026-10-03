{{--
    Once per page: <x-nx::undo-snackbar />
    Push one: $this->dispatch('nx-undo', message: 'Invoice archived', undo: 'restoreInvoice', params: [$id]);
          or: $dispatch('nx-undo', { message: 'Deleted', undo: 'restore', params: [3] }) from Alpine.

    A stack of "Done · Undo" bars. Each one drains a thin timer and leaves on
    its own; hovering or focusing the stack pauses every timer. Undo calls the
    Livewire method `undo` with `params` and fires `nx-undone` on window
    (detail: { id, params }) for pages that undo in the browser.
--}}
@props([
    'position' => 'bottom-center',
    'duration' => 6000,
    'undoLabel' => 'Undo',
    'label' => null,
])
@php
    use NabuXUI\NabuXUI;
    $icons = collect(['check', 'trash', 'mail', 'file', 'folder', 'user', 'bell', 'info', 'check-circle', 'alert-circle'])
        ->mapWithKeys(fn ($name) => [$name => (string) NabuXUI::icon($name)])->all();
@endphp
<section {{ $attributes->class('nx-snackbars')->merge(['data-position' => $position, 'aria-label' => $label ?? __('nabuxui::ui.notifications')]) }}
    x-data="nxSnackbars(@js(['duration' => (int) $duration]))" x-on:nx-undo.window="push($event.detail)" wire:ignore>
    <ol class="nx-snackbar-list" aria-live="polite">
        <template x-for="item in items" x-bind:key="item.id">
            <li class="nx-snackbar" x-bind:data-state="item.state" x-init="mount($el, item)">
                <template x-if="item.icon && @js($icons)[item.icon]"><span class="nx-snackbar-icon" x-html="@js($icons)[item.icon]"></span></template>
                <p class="nx-snackbar-message" x-text="item.message"></p>
                <template x-if="item.undoable">
                    <button type="button" class="nx-snackbar-undo" x-on:click="undo(item)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 14L4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11"/></svg>
                        {{ $undoLabel }}
                    </button>
                </template>
                <button type="button" class="nx-snackbar-close" aria-label="{{ __('nabuxui::ui.dismiss') }}" x-on:click="dismiss(item.id, 'close')">{{ NabuXUI::icon('x') }}</button>
                <template x-if="item.duration > 0"><span class="nx-snackbar-timer" aria-hidden="true"><i></i></span></template>
            </li>
        </template>
    </ol>
</section>
