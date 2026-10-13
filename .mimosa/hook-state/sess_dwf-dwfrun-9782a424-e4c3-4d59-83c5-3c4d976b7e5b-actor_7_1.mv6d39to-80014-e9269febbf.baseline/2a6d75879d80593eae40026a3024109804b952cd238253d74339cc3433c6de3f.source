{{--
    <x-nx::blob-button icon="sparkles" wire:click="start">Talk to Nabu</x-nx::blob-button>

    A dark pill with lapis, violet and gold blobs drifting inside; under a mouse
    they follow the pointer and gather around it. Button, or a link with href.
--}}
@props([
    'href' => null,
    'size' => 'md',
    'icon' => null,
    'iconEnd' => null,
    'type' => 'button',
    'static' => false,
])
@php
    $renderIcon = fn ($i) => is_string($i) && \NabuXUI\NabuXUI::hasIcon($i) ? \NabuXUI\NabuXUI::icon($i) : $i;
    $data = [
        'data-size' => $size === 'md' ? null : $size,
        'data-static' => $static ? '' : null,
    ];
@endphp
@if ($href)
<a href="{{ $href }}" {{ $attributes->class('nx-blob-button')->merge($data) }} x-data x-nx-spotlight>
@else
<button type="{{ $type }}" {{ $attributes->class('nx-blob-button')->merge($data) }} x-data x-nx-spotlight>
@endif
    <span class="nx-blob-button-blobs" aria-hidden="true"><i></i><i></i><i></i></span>
    <span class="nx-blob-button-label">
        @if ($icon){{ $renderIcon($icon) }}@endif
        {{ $slot }}
        @if ($iconEnd){{ $renderIcon($iconEnd) }}@endif
    </span>
@if ($href)
</a>
@else
</button>
@endif
