{{--
    <x-nx::metal-button label="Voice input" wire:model.live="listening" />
    <x-nx::metal-button label="Listen" icon="play" shape="pill" :pressed="true" />

    A liquid-metal toggle (aria-pressed): pressed, the rim turns iridescent and the
    icon swaps for `active-icon` (live wave bars by default). With wire:model it
    follows the Livewire property; without, the state is the browser's own (a
    re-render leaves it alone). It dispatches `nx-change` with the new state.
--}}
@props([
    'label' => '',
    'icon' => 'mic',
    'activeIcon' => null,
    'pressed' => false,
    'size' => 'md',
    'shape' => 'round',
    'type' => 'button',
    'static' => false,
])
@php
    $model = \NabuXUI\NabuXUI::model($attributes);
    $live = collect(array_keys($attributes->getAttributes()))->contains(fn ($key) => str_starts_with($key, 'wire:model') && str_contains($key, '.live'));
    $pill = $shape === 'pill';
    $renderIcon = fn ($i) => is_string($i) && \NabuXUI\NabuXUI::hasIcon($i) ? \NabuXUI\NabuXUI::icon($i) : $i;
    $data = [
        'data-size' => $size === 'md' ? null : $size,
        'data-shape' => $pill ? 'pill' : null,
        'data-static' => $static ? '' : null,
        'aria-label' => $pill ? null : $label,
        'title' => $pill ? null : $label,
    ];
@endphp
<button type="{{ $type }}" {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-metal-button')->merge($data) }}
    @if ($model) x-data="nxMetalButton(@entangle($model){{ $live ? '.live' : '' }})" @else x-data="nxMetalButton(@js((bool) $pressed))" wire:ignore.self @endif
    x-bind:aria-pressed="pressed ? 'true' : 'false'" aria-pressed="{{ $pressed ? 'true' : 'false' }}" x-on:click="toggle()">
    <span class="nx-metal-button-rim" aria-hidden="true"></span>
    <span class="nx-metal-button-face" aria-hidden="true"></span>
    <span class="nx-metal-button-icons" aria-hidden="true">
        <span data-when="off">{{ $renderIcon($icon) }}</span>
        <span data-when="on">@if ($activeIcon){{ $renderIcon($activeIcon) }}@else<span class="nx-metal-bars"><i></i><i></i><i></i><i></i><i></i></span>@endif</span>
    </span>
    @if ($pill)<span class="nx-metal-button-label">{{ $label }}</span>@endif
</button>
