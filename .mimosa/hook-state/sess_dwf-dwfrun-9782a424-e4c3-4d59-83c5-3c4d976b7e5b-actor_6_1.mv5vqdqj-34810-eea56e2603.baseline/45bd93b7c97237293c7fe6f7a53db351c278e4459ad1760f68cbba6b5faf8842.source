{{--
    A row of sticks over a native range: they grow on hover and dip into a pit under the thumb while it is dragged
    (or nudged with the keys), with the value floating above.

    <x-nx::pit-slider label="Volume" min="0" max="100" value="40" wire:model.live="volume" />

    Attributes go to the native <input type="range"> (min, max, step, value, wire:model, name…).
    bars: how many sticks. decimals, prefix, suffix: how the value reads. `dark` (default) keeps the panel dark in
    both themes; :dark="false" follows the page.
--}}
@props(['label' => null, 'bars' => 36, 'decimals' => null, 'prefix' => '', 'suffix' => '', 'dark' => true])
@php
    $format = $decimals !== null ? ['minimumFractionDigits' => (int) $decimals, 'maximumFractionDigits' => (int) $decimals] : null;
    $count = max(2, (int) $bars);
    $id = $attributes->get('id') ?? \NabuXUI\NabuXUI::id('nx-pit');
    $min = (float) ($attributes->get('min') ?? 0);
    $max = (float) ($attributes->get('max') ?? 100);
    $value = (float) ($attributes->get('value') ?? $min);
    $fraction = max(0, min(1, ($value - $min) / (($max - $min) ?: 1)));
    $shown = trim($prefix.\NabuXUI\NabuXUI::formatNumber($value, (int) ($decimals ?? 0)).($suffix !== '' ? ' '.$suffix : ''));
    $locale = str_replace('_', '-', app()->getLocale());
@endphp
<div class="nx-pit" @if ($dark) data-theme="dark" @endif style="--nx-frac: {{ round($fraction, 4) }}; --nx-pct: {{ round($fraction * 100, 2) }}%"
    x-data="nxPitSlider(@js($locale), @js($format), @js((string) $prefix), @js((string) $suffix))" wire:ignore.self>
    @if ($label)
        <div class="nx-pit-head">
            <label class="nx-pit-label" for="{{ $id }}">{{ $label }}</label>
            <span class="nx-pit-readout" aria-hidden="true" x-text="shown">{{ $shown }}</span>
        </div>
    @endif
    <div class="nx-pit-track">
        <div class="nx-pit-bars" aria-hidden="true" wire:ignore>@for ($i = 0; $i < $count; $i++)<span class="nx-pit-bar" style="--p: {{ round($i / ($count - 1), 4) }}"></span>@endfor</div>
        <span class="nx-pit-thumb" aria-hidden="true"></span>
        <output class="nx-pit-value" aria-hidden="true" x-text="shown">{{ $shown }}</output>
        <input type="range" {{ $attributes->class('nx-pit-input')->merge(['id' => $id]) }}>
    </div>
</div>
