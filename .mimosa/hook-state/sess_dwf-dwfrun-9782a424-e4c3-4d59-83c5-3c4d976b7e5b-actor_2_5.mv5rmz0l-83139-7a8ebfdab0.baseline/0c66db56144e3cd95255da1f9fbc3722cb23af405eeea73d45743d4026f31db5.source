{{--
    A native range whose thumb turns into a magnifier while dragged.
    <x-nx::glass-slider label="Volume" wire:model.live="volume" suffix="%" />
--}}
@props(['label' => null, 'value' => null, 'min' => 0, 'max' => 100, 'step' => 1, 'ticks' => 11, 'showValue' => true, 'suffix' => ''])
@php
    $model = \NabuXUI\NabuXUI::model($attributes);
    $live = collect(array_keys($attributes->getAttributes()))->contains(fn ($key) => str_starts_with($key, 'wire:model') && str_contains($key, '.live'));
    $value ??= ($min + $max) / 2;
    $p = $max > $min ? (min($max, max($min, $value)) - $min) / ($max - $min) : 0;
@endphp
<div @class(['nx-glass-slider', $attributes->get('class')]) style="--p: {{ round($p, 4) }}"
    @if ($model) x-data="nxGlassSlider(@entangle($model){{ $live ? '.live' : '' }})" @else x-data="nxGlassSlider(@js((float) $value))" @endif>
    <div class="nx-glass-slider-bed">
        @if ($ticks > 1)
            <div class="nx-glass-slider-ticks" aria-hidden="true">@for ($i = 0; $i < $ticks; $i++)<span></span>@endfor</div>
        @endif
        <div class="nx-glass-slider-track"><div class="nx-glass-slider-fill"></div></div>
    </div>
    <span class="nx-lens nx-glass-slider-thumb" aria-hidden="true"></span>
    @if ($showValue)
        <span class="nx-glass-slider-value" aria-hidden="true" x-text="value + @js($suffix)">{{ $value }}{{ $suffix }}</span>
    @endif
    <input type="range" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" value="{{ $value }}" x-model.number="value"
        {{ $attributes->except('class')->whereDoesntStartWith('wire:model')->class('nx-glass-slider-input')->merge(['aria-label' => $label]) }}>
</div>
