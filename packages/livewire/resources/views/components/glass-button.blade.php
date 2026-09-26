{{--
    A pressable pane. <x-nx::glass-button icon="sparkles" shimmer wire:click="save">Continue</x-nx::glass-button>
    shimmer: send the light once around every glass rim when pressed. static: no press scale.
--}}
@props([
    'href' => null,
    'size' => 'md',
    'icon' => null,
    'iconEnd' => null,
    'preset' => 'regular',
    'iridescent' => false,
    'refract' => true,
    'shimmer' => false,
    'static' => false,
    'type' => 'button',
])
@php
    $text = trim((string) $slot);
    $data = [
        'data-interactive' => '',
        'data-size' => $size === 'md' ? null : $size,
        'data-preset' => $preset === 'regular' ? null : $preset,
        'data-iridescent' => $iridescent ? '' : null,
        'data-static' => $static ? '' : null,
        'data-icon-only' => $text === '' && ($icon || $iconEnd) ? '' : null,
    ];
@endphp
@if ($href)
<a href="{{ $href }}" {{ $attributes->class('nx-glass nx-glass-button')->merge($data) }} x-data @if ($refract) x-nx-glass @endif @if ($shimmer) x-on:pointerdown="$nxSweepLight()" @endif>
@else
<button type="{{ $type }}" {{ $attributes->class('nx-glass nx-glass-button')->merge($data) }} x-data @if ($refract) x-nx-glass @endif @if ($shimmer) x-on:pointerdown="$nxSweepLight()" @endif>
@endif
    @if ($icon){{ \NabuXUI\NabuXUI::icon($icon) }}@endif{{ $slot }}@if ($iconEnd){{ \NabuXUI\NabuXUI::icon($iconEnd) }}@endif
@if ($href)
</a>
@else
</button>
@endif
