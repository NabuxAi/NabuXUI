{{--
    <x-nx::pull-cord label="Theme toggle" x-on:nx-cord-pull="$nxTheme.toggle()" />
    <x-nx::pull-cord action="lightSwitch" tone="gold" trigger="90" height="16rem" />

    A cord hanging from the top edge of its box with a real rope: verlet
    integration, gravity, wobble. The rope rests about 55% down the box (the
    rest of the box is pull room); drag the knob down until it glows and let
    go — the cord arms while pulled (data-nx-armed) and releasing fires
    nx-cord-pull (plus the optional Livewire `action`). The `trigger` is the
    ideal pull in px, clamped to what the box allows. Reduced motion turns the
    sim off: the rope stretches and snaps back without physics.
--}}
@props([
    'label' => null,
    'action' => null,
    'tone' => 'accent',
    'trigger' => 72,
    'segments' => 12,
    'height' => null,
    'icon' => 'zap',
])
@php
    $vars = collect([
        'block-size' => $height,
    ])->filter(fn ($v) => $v !== null && $v !== '')->map(fn ($v, $k) => "{$k}: {$v}")->implode('; ');
@endphp
<div {{ $attributes->class('nx-pull-cord')->merge([
    'data-tone' => $tone !== 'accent' ? $tone : null,
    'style' => $vars ?: null,
]) }} x-data="nxPullCord(@js(['trigger' => (int) $trigger, 'segments' => (int) $segments, 'action' => $action]))" wire:ignore.self>
    <svg class="nx-pull-cord-rope" aria-hidden="true" fill="none"><path /></svg>
    <button type="button" class="nx-pull-cord-knob"
        aria-label="{{ $label ?? __('nabuxui::ui.pullCord') }}">
        {{ \NabuXUI\NabuXUI::icon($icon) }}
    </button>
</div>
