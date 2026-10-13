{{-- A native range with a value that rides the thumb. <x-nx::slider min="0" max="2" step="0.1" wire:model.live="temperature" /> --}}
@props(['label' => null, 'showValue' => false, 'startLabel' => null, 'endLabel' => null, 'decimals' => null])
@php $format = $decimals !== null ? ['minimumFractionDigits' => (int) $decimals, 'maximumFractionDigits' => (int) $decimals] : null; @endphp
<div class="nx-slider" x-data="nxSlider(@js(str_replace('_', '-', app()->getLocale())), @js($format))" @if ($showValue) data-show-value @endif>
    <input type="range" x-ref="input" {{ $attributes->class('nx-slider-input')->merge(['aria-label' => $label]) }}>
    <output class="nx-slider-value" aria-hidden="true" x-text="shown"></output>
    @if ($startLabel || $endLabel)
        <div class="nx-slider-labels" aria-hidden="true"><span>{{ $startLabel }}</span><span>{{ $endLabel }}</span></div>
    @endif
</div>
