{{--
    <x-nx::fill-button icon-end="arrow-right" href="/explore">Explore</x-nx::fill-button>
    <x-nx::fill-button variant="gold" hover-label="Let's begin" wire:click="start">Start free</x-nx::fill-button>

    A circle grows from where the pointer came in until it fills the button, the
    label slides up for its copy, and on leave the circle retreats toward where
    the pointer went out. variant: accent | inverse | gold.
--}}
@props([
    'href' => null,
    'variant' => 'accent',
    'hoverLabel' => null,
    'icon' => null,
    'iconEnd' => null,
    'size' => 'md',
    'shape' => 'pill',
    'type' => 'button',
    'static' => false,
])
@php
    $renderIcon = fn ($i) => is_string($i) && \NabuXUI\NabuXUI::hasIcon($i) ? \NabuXUI\NabuXUI::icon($i) : $i;
    $data = [
        'data-variant' => $variant === 'accent' ? null : $variant,
        'data-size' => $size === 'md' ? null : $size,
        'data-shape' => $shape === 'rounded' ? 'rounded' : null,
        'data-static' => $static ? '' : null,
    ];
@endphp
@if ($href)
<a href="{{ $href }}" {{ $attributes->class('nx-fill-button')->merge($data) }} x-data="nxFillButton">
@else
<button type="{{ $type }}" {{ $attributes->class('nx-fill-button')->merge($data) }} x-data="nxFillButton">
@endif
    <span class="nx-fill-button-fill" aria-hidden="true"></span>
    <span class="nx-fill-button-label">
        <span>@if ($icon){{ $renderIcon($icon) }}@endif{{ $slot }}@if ($iconEnd){{ $renderIcon($iconEnd) }}@endif</span>
        <span data-copy aria-hidden="true">@if ($icon){{ $renderIcon($icon) }}@endif{{ $hoverLabel ?? $slot }}@if ($iconEnd){{ $renderIcon($iconEnd) }}@endif</span>
    </span>
@if ($href)
</a>
@else
</button>
@endif
