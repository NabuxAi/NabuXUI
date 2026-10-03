{{--
    <x-nx::hover-card :open-delay="400">
        <x-slot:trigger><a href="/u/sara">@sara</a></x-slot:trigger>
        …the card: avatar, bio, stats…
    </x-nx::hover-card>

    A rich preview that opens after `open-delay` ms of hovering (or on
    keyboard focus of the trigger), stays open while the pointer is inside the
    card, and closes `close-delay` ms after it leaves both — or on Escape.
    Touch never opens it, so the trigger should still work on its own (a
    link). Placed with core `place` (side: bottom | top | inline-start |
    inline-end; align: start | center | end) and scaled out of the trigger.
--}}
@props(['trigger' => null, 'openDelay' => 500, 'closeDelay' => 250, 'side' => 'bottom', 'align' => 'center', 'cardClass' => null])
@php
    $id = \NabuXUI\NabuXUI::id('nx-hc');
    $config = ['openDelay' => (int) $openDelay, 'closeDelay' => (int) $closeDelay, 'side' => (string) $side, 'align' => (string) $align];
@endphp
<span {{ $attributes->class('nx-hovercard') }} x-data="nxHoverCard(@js($config))">
    <span class="nx-hovercard-trigger" x-ref="trigger" aria-describedby="{{ $id }}">{{ $trigger }}</span>
    <span x-ref="card" id="{{ $id }}" class="nx-picker-pop nx-hovercard-card {{ $cardClass }}" popover="manual">{{ $slot }}</span>
</span>
