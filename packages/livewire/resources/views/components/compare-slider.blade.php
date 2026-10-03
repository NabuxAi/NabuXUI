{{--
    <x-nx::compare-slider :before="['src' => '/raw.jpg', 'alt' => 'Unedited']" :after="['src' => '/graded.jpg', 'alt' => 'Colour graded']"
        before-label="Before" after-label="After" wire:model.live="split" />

    Before/after split by a divider. Underneath is a real range input (the
    keyboard, the dragging and the right-to-left mapping are native), so
    wire:model lands on it; `orientation="vertical"` splits top/bottom.
--}}
@props([
    'before' => [],
    'after' => [],
    'beforeLabel' => 'Before',
    'afterLabel' => 'After',
    'value' => 50,
    'orientation' => 'horizontal',
    'ratio' => null,
    'label' => 'Divider position',
])
@php
    use NabuXUI\NabuXUI;
    $style = '--_pos: '.((float) $value).'%'.($ratio ? '; --_ratio: '.$ratio : '');
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-compare')->merge(['data-orientation' => $orientation, 'style' => $style]) }}
    x-data="nxCompare()" wire:ignore.self>
    <img class="nx-compare-img" data-side="after" src="{{ $after['src'] ?? '' }}" alt="{{ $after['alt'] ?? '' }}" draggable="false">
    <img class="nx-compare-img" data-side="before" src="{{ $before['src'] ?? '' }}" alt="{{ $before['alt'] ?? '' }}" draggable="false">
    <span class="nx-compare-tag" data-side="before" aria-hidden="true">{{ $beforeLabel }}</span>
    <span class="nx-compare-tag" data-side="after" aria-hidden="true">{{ $afterLabel }}</span>
    <span class="nx-compare-divider" aria-hidden="true"><span class="nx-compare-knob">{{ NabuXUI::icon('chevron-left') }}{{ NabuXUI::icon('chevron-right') }}</span></span>
    <input type="range" class="nx-compare-range" min="0" max="100" step="0.5" value="{{ (float) $value }}" aria-label="{{ $label }}" aria-orientation="{{ $orientation }}"
        {{ $attributes->whereStartsWith('wire:model') }}>
</div>
