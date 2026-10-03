{{--
    Brush chart — a line / area zoomed by a range selector under it:
        <x-nx::brush-chart title="Daily active users" :labels="$days" :series="[['name' => 'DAU', 'values' => […]]]"
            :range="[60, 89]" min-span="5" x-on:nx-range="$wire.set('range', $event.detail.range)" />
    Drag the window to pan (arrow keys on it too; Shift = a whole window, Home/End),
    drag either edge to resize (they are native range inputs, so the keyboard works
    as everywhere). Every change dispatches `nx-range` with { range, from, to }.
--}}
@props([
    'labels' => [],
    'series' => [],
    'title' => null,
    'subtitle' => null,
    'variant' => 'area',
    'curve' => 'smooth',
    'range' => null,
    'minSpan' => 2,
    'height' => 240,
    'brushHeight' => 56,
    'format' => null,
    'markers' => true,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $words = [
        'fa' => ['range' => 'بازهٔ انتخاب‌شده', 'start' => 'آغاز بازه', 'end' => 'پایان بازه', 'move' => 'جابه‌جایی بازهٔ انتخاب‌شده'],
        'ar' => ['range' => 'النطاق المحدد', 'start' => 'بداية النطاق', 'end' => 'نهاية النطاق', 'move' => 'تحريك النطاق المحدد'],
    ][$lang] ?? ['range' => 'Selected range', 'start' => 'Range start', 'end' => 'Range end', 'move' => 'Move the selected range'];
    $labels = array_values($labels);
    $series = array_values($series);
    $last = max(0, count($labels) - 1);
    $fmt = fn ($v) => NabuXUI::formatNumber((float) $v, floor((float) $v) == (float) $v ? 0 : 1, $locale);
    $config = [
        'labels' => $labels, 'series' => $series, 'variant' => $variant, 'curve' => $curve, 'range' => $range,
        'minSpan' => (int) $minSpan, 'height' => (int) $height, 'brushHeight' => (int) $brushHeight,
        'format' => $format, 'markers' => (bool) $markers, 'locale' => $locale,
    ];
    $chartId = NabuXUI::id('nx-brush');
@endphp
<figure {{ $attributes->class('nx-chart nx-brush-chart')->merge(['data-type' => $variant, 'data-nx-reveal' => '', 'aria-labelledby' => $title ? "{$chartId}-title" : null]) }}
    x-data="nxBrushChart(@js($config))" wire:ignore>
    <div class="nx-chart-head">
        <div>
            @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
            @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
        </div>
        @if (count($series) > 1)
            <ul class="nx-chart-legend">
                @foreach ($series as $i => $s)
                    <li class="nx-legend-item"><span class="nx-legend-swatch" data-shape="{{ $variant === 'line' ? 'line' : 'rect' }}" style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>{{ $s['name'] }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="nx-chart-plot" x-ref="plot" tabindex="0" aria-label="{{ $title ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
        x-on:pointermove="move($event)" x-on:pointerleave="active = null" x-on:keydown="key($event)" x-on:blur="active = null">
        <svg class="nx-chart-svg" x-ref="svg" aria-hidden="true"></svg>
        <div class="nx-chart-tooltip" data-frost x-ref="tip" aria-hidden="true"></div>
    </div>
    <div class="nx-brush" data-brush-track style="--nx-brush-height: {{ (int) $brushHeight }}px">
        <svg class="nx-brush-svg" x-ref="mini" preserveAspectRatio="none" aria-hidden="true"></svg>
        <span class="nx-brush-shade" data-edge="start" x-ref="shadeStart"></span>
        <span class="nx-brush-shade" data-edge="end" x-ref="shadeEnd"></span>
        <button type="button" class="nx-brush-window" x-ref="window" aria-label="{{ $words['move'] }}" x-bind:aria-label="@js($words['move']) + ': ' + readout()"></button>
        <input class="nx-brush-input" type="range" min="0" max="{{ $last }}" step="1" aria-label="{{ $words['start'] }}"
            x-bind:value="range[0]" x-bind:aria-valuetext="@js($labels)[range[0]]" x-on:input="edge('start', $event)">
        <input class="nx-brush-input" type="range" min="0" max="{{ $last }}" step="1" aria-label="{{ $words['end'] }}"
            x-bind:value="range[1]" x-bind:aria-valuetext="@js($labels)[range[1]]" x-on:input="edge('end', $event)">
    </div>
    <p class="nx-brush-readout"><span>{{ $words['range'] }}</span><output aria-live="polite" x-text="readout()"></output></p>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td>@foreach ($series as $s)<th scope="col">{{ $s['name'] }}</th>@endforeach</tr></thead>
        <tbody>
            @foreach ($labels as $row => $label)
                <tr><th scope="row">{{ $label }}</th>@foreach ($series as $s)<td>{{ $fmt($s['values'][$row] ?? 0) }}</td>@endforeach</tr>
            @endforeach
        </tbody>
    </table>
</figure>
