{{--
    <x-nx::pulse-button href="#reel" icon="play">Play the reel</x-nx::pulse-button>
    <x-nx::pulse-button icon="arrow-right" aria-label="Get started" wire:click="start" />

    A round call to action with rings pulsing out of it, pulled toward the pointer. The
    slot is the label under the disc; without one, give it an aria-label.
    size: sm | md | lg · tone: accent | gold | inverse · static: no pull, hover or press scaling.
--}}
@props(['href' => null, 'icon' => 'arrow-right', 'size' => 'md', 'tone' => 'accent', 'type' => 'button', 'disabled' => false, 'static' => false])
@php
    $label = trim((string) $slot);
    $tag = $href ? 'a' : 'button';
    $data = [
        'href' => $href,
        'type' => $href ? null : $type,
        'disabled' => ! $href && $disabled ? true : null,
        'aria-disabled' => $href && $disabled ? 'true' : null,
        'data-size' => $size === 'md' ? null : $size,
        'data-tone' => $tone === 'accent' ? null : $tone,
        'data-static' => $static ? '' : null,
    ];
@endphp
<{{ $tag }} {{ $attributes->class('nx-pulse-button')->merge($data) }}>
    {{-- magnetic() marks the disc; wire:ignore.self keeps that mark through Livewire re-renders. --}}
    <span class="nx-pulse-button-disc" @unless ($static || $disabled) x-data x-nx-magnetic="0.3" wire:ignore.self @endunless>
        <span class="nx-pulse-button-rings" aria-hidden="true"><i></i><i></i><i></i></span>
        <span class="nx-pulse-button-face">@if (\NabuXUI\NabuXUI::hasIcon($icon)){{ \NabuXUI\NabuXUI::icon($icon) }}@else{{ $icon }}@endif</span>
    </span>
    @if ($label !== '')<span class="nx-pulse-button-label">{{ $slot }}</span>@endif
</{{ $tag }}>
