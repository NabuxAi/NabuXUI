{{--
    Composed chart — bars and lines on one plot with two value axes:
        <x-nx::composed-chart title="Revenue & conversion" :labels="$months"
            :bars="[['name' => 'Revenue', 'values' => […]]]" :lines="[['name' => 'Conversion', 'values' => […]]]"
            bar-axis="M toman" line-axis="%" :line-format="['style' => 'percent']" markers />
    The bars' axis sits at inline-start (the right in RTL, where categories also run
    right to left); the lines' axis faces it. One tooltip reads both.
--}}
@props([
    'labels' => [],
    'bars' => [],
    'lines' => [],
    'title' => null,
    'subtitle' => null,
    'barAxis' => null,
    'lineAxis' => null,
    'format' => null,
    'lineFormat' => null,
    'curve' => 'smooth',
    'height' => 280,
    'markers' => false,
    'ping' => true,
    'dir' => null,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $labels = array_values($labels);
    $bars = array_values($bars);
    $lines = array_values($lines);
    $fmt = fn ($v) => NabuXUI::formatNumber((float) $v, floor((float) $v) == (float) $v ? 0 : 2, $locale);
    $config = [
        'labels' => $labels, 'bars' => $bars, 'lines' => $lines, 'barAxis' => $barAxis, 'lineAxis' => $lineAxis,
        'format' => $format, 'lineFormat' => $lineFormat, 'curve' => $curve, 'height' => (int) $height,
        'markers' => (bool) $markers, 'ping' => (bool) $ping, 'dir' => $dir, 'locale' => $locale,
    ];
    $chartId = NabuXUI::id('nx-composed');
    $offset = count($bars);
@endphp
<figure {{ $attributes->class('nx-chart nx-composed-chart')->merge(['data-type' => 'composed', 'data-nx-reveal' => '', 'aria-labelledby' => $title ? "{$chartId}-title" : null]) }}
    x-data="nxComposedChart(@js($config))" wire:ignore>
    <div class="nx-chart-head">
        <div>
            @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
            @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
        </div>
        <ul class="nx-chart-legend">
            @foreach ($bars as $i => $s)
                <li class="nx-legend-item"><span class="nx-legend-swatch" style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>{{ $s['name'] }}</li>
            @endforeach
            @foreach ($lines as $i => $s)
                <li class="nx-legend-item"><span class="nx-legend-swatch" data-shape="line" style="--nx-series: var(--nx-chart-{{ ($offset + $i) % 7 + 1 }})"></span>{{ $s['name'] }}</li>
            @endforeach
        </ul>
    </div>
    <div class="nx-chart-plot" x-ref="plot" tabindex="0" aria-label="{{ $title ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
        x-on:pointermove="move($event)" x-on:pointerleave="active = null" x-on:keydown="key($event)" x-on:blur="active = null">
        <svg class="nx-chart-svg" x-ref="svg" aria-hidden="true"></svg>
        <div class="nx-chart-tooltip" data-frost x-ref="tip" aria-hidden="true"></div>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td>@foreach ([...$bars, ...$lines] as $s)<th scope="col">{{ $s['name'] }}</th>@endforeach</tr></thead>
        <tbody>
            @foreach ($labels as $row => $label)
                <tr><th scope="row">{{ $label }}</th>@foreach ([...$bars, ...$lines] as $s)<td>{{ $fmt($s['values'][$row] ?? 0) }}</td>@endforeach</tr>
            @endforeach
        </tbody>
    </table>
</figure>
