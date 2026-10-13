{{--
    The slider with a large rolling number, fine ticks that fill with the value, and the range's two ends.

    <x-nx::precision-slider label="Temperature" min="0" max="2" step="0.01" value="0.7" decimals="2" wire:model.live="temperature" />
    <x-nx::precision-slider label="Budget" prefix="$" min="0" max="5000" step="50" value="1200" />

    Attributes go to the native range (min, max, step, value, wire:model, name…). unit / prefix sit around the
    number; decimals fixes its fraction digits; ticks (intervals) and major-every shape the scale.
--}}
@props(['label' => null, 'unit' => null, 'prefix' => null, 'decimals' => null, 'ticks' => null, 'majorEvery' => null])
@php
    $min = (float) ($attributes->get('min') ?? 0);
    $max = (float) ($attributes->get('max') ?? 100);
    $step = (float) ($attributes->get('step') ?? 1) ?: 1.0;
    $value = (float) ($attributes->get('value') ?? $min);
    $fraction = max(0, min(1, ($value - $min) / (($max - $min) ?: 1)));
    $steps = (int) round(($max - $min) / $step);
    $intervals = max(2, (int) round($ticks ?? ($steps > 0 && $steps <= 60 ? $steps : 40)));
    $major = max(1, (int) ($majorEvery ?? ($intervals % 10 === 0 ? $intervals / 10 : ($intervals % 5 === 0 ? 5 : 1))));
    $digits = (int) ($decimals ?? 0);
    $format = $decimals !== null ? ['minimumFractionDigits' => $digits, 'maximumFractionDigits' => $digits] : null;
    $locale = str_replace('_', '-', app()->getLocale());
    $id = $attributes->get('id') ?? \NabuXUI\NabuXUI::id('nx-precision');
    $text = fn ($n) => trim(($prefix ?? '').\NabuXUI\NabuXUI::formatNumber($n, $digits).($unit !== null && $unit !== '' ? ' '.$unit : ''));
@endphp
<div class="nx-precision" style="--nx-frac: {{ round($fraction, 4) }}"
    x-data="nxPrecisionSlider(@js($locale), @js($format), @js((string) ($prefix ?? '')), @js((string) ($unit ?? '')))" wire:ignore.self>
    <div class="nx-precision-head">
        @if ($label)<label class="nx-precision-label" for="{{ $id }}">{{ $label }}</label>@endif
        <div class="nx-precision-readout" aria-hidden="true">
            @if ($prefix)<span class="nx-precision-affix">{{ $prefix }}</span>@endif
            <x-nx::number :value="$value" :decimals="$digits" :reveal="false" x-ref="number" wire:ignore />
            @if ($unit)<span class="nx-precision-affix">{{ $unit }}</span>@endif
        </div>
    </div>
    <div class="nx-precision-ticks" aria-hidden="true">@for ($i = 0; $i <= $intervals; $i++)<i style="--p: {{ round($i / $intervals, 4) }}" @if ($i % $major === 0) data-major @endif></i>@endfor</div>
    <x-nx::slider :decimals="$decimals" {{ $attributes->merge(['id' => $id, 'aria-valuetext' => $text($value)]) }} />
    <div class="nx-precision-scale" aria-hidden="true"><span>{{ $text($min) }}</span><span>{{ $text($max) }}</span></div>
</div>
