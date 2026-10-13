{{--
    <x-nx::hold-to-confirm label="Hold to delete" done-label="Deleted" action="deleteProject" />
    <x-nx::hold-to-confirm label="Hold to revoke" variant="ring" x-on:nx-confirm="…" />

    A destructive button that only confirms after it has been held (pointer, or
    Space/Enter held down). Releasing early drains the fill. On confirm it
    dispatches `nx-confirm` and, with `action`, calls that Livewire method.
--}}
@props([
    'label' => '',
    'doneLabel' => 'Done',
    'hint' => 'Press and hold to confirm',
    'duration' => 1200,
    'resetAfter' => 2400,
    'variant' => 'bar',
    'tone' => 'danger',
    'icon' => 'trash',
    'doneIcon' => 'check',
    'size' => 'md',
    'action' => null,
    'static' => false,
])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-hold');
    $label = $label !== '' ? $label : trim(strip_tags((string) $slot));
    $data = [
        'data-variant' => $variant,
        'data-tone' => $tone === 'danger' ? null : $tone,
        'data-size' => $size === 'md' ? null : $size,
        'data-static' => $static ? '' : null,
        'data-state' => 'idle',
        'aria-describedby' => "{$id}-hint",
    ];
@endphp
<button type="button" {{ $attributes->class('nx-hold')->merge($data) }}
    x-data="nxHold(@js(['duration' => (int) $duration, 'resetAfter' => (int) $resetAfter, 'action' => $action]))" wire:ignore.self>
    <span class="nx-hold-fill" aria-hidden="true"></span>
    <span class="nx-hold-icon" aria-hidden="true">
        <svg class="nx-hold-ring" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10.5"/><circle class="nx-hold-ring-bar" cx="12" cy="12" r="10.5" pathLength="1"/></svg>
        <span>{{ NabuXUI::icon($icon) }}</span>
        <span data-off>{{ NabuXUI::icon($doneIcon) }}</span>
    </span>
    <span class="nx-hold-label">
        <span>{{ $label }}</span>
        <span data-off aria-hidden="true">{{ $doneLabel }}</span>
    </span>
    <span class="nx-visually-hidden" id="{{ $id }}-hint">{{ $hint }}</span>
    <span class="nx-visually-hidden" aria-live="polite" x-text="state === 'done' ? @js($doneLabel) : ''"></span>
</button>
