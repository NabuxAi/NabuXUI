{{--
    <x-nx::scroll-progress />                                   page bar across the top
    <x-nx::scroll-progress target="#article" />                 reading progress of one article
    <x-nx::scroll-progress variant="circle" label="Back to top" />

    Reading progress, driven by a CSS scroll timeline where the browser has one
    (the page bar) and by scroll measurements otherwise. `container` points at a
    scrolling box (then use position="sticky"). The circle is a back-to-top
    button whose ring fills; it appears once reading has started.
--}}
@props(['variant' => 'bar', 'target' => null, 'container' => null, 'position' => 'fixed', 'label' => null])
@php
    $common = [
        'data-variant' => $variant === 'circle' ? 'circle' : 'bar',
        'data-position' => $position === 'fixed' ? null : $position,
        'data-source' => $target || $container ? 'element' : null,
    ];
@endphp
@if ($variant === 'circle')
    <button type="button" {{ $attributes->class('nx-scroll-progress')->merge($common + ['aria-label' => $label ?? 'Back to top']) }}
        x-data="nxScrollProgress(@js($target), @js($container))" x-on:click="toTop()" wire:ignore.self>
        <svg class="nx-scroll-progress-ring" viewBox="0 0 48 48" aria-hidden="true" focusable="false">
            <circle class="nx-scroll-progress-ring-track" cx="24" cy="24" r="21" />
            <circle class="nx-scroll-progress-ring-fill" cx="24" cy="24" r="21" pathLength="100" />
        </svg>
        {{ \NabuXUI\NabuXUI::icon('arrow-up', 'nx-scroll-progress-icon') }}
    </button>
@else
    <div {{ $attributes->class('nx-scroll-progress')->merge($common + [
        'role' => 'progressbar',
        'aria-label' => $label ?? 'Reading progress',
        'aria-valuemin' => '0',
        'aria-valuemax' => '100',
        'aria-valuenow' => '0',
    ]) }} x-data="nxScrollProgress(@js($target), @js($container))" wire:ignore.self><span class="nx-scroll-progress-fill"></span></div>
@endif
