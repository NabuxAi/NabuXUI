{{--
    Stacked area chart — layers piled from zero, normalised to 100% or centred as a stream:
        <x-nx::stacked-area title="Traffic" :labels="$months" :series="[['name' => 'Web', 'values' => […]], …]"
            mode="stacked|percent|expanded" curve="smooth|linear|step" fill="gradient|solid|hatched|duotone" markers />
    The legend buttons switch series on and off (aria-pressed); the stack retweens to
    its new shape. Arrow keys read each column. The same data ships as a hidden table.
--}}
@props([
    'labels' => [],
    'series' => [],
    'title' => null,
    'subtitle' => null,
    'mode' => 'stacked',
    'curve' => 'smooth',
    'fill' => 'gradient',
    'height' => 280,
    'format' => null,
    'hiddenSeries' => [],
    'ping' => true,
    'markers' => false,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $words = [
        'fa' => ['legend' => 'نمایش یا پنهان‌کردن سری‌ها', 'total' => 'جمع'],
        'ar' => ['legend' => 'إظهار السلاسل أو إخفاؤها', 'total' => 'المجموع'],
    ][$lang] ?? ['legend' => 'Show or hide series', 'total' => 'Total'];
    $labels = array_values($labels);
    $series = array_values($series);
    $fmt = fn ($v) => NabuXUI::formatNumber((float) $v, floor((float) $v) == (float) $v ? 0 : 1, $locale);
    $config = [
        'labels' => $labels, 'series' => $series, 'mode' => $mode, 'curve' => $curve, 'fill' => $fill,
        'height' => (int) $height, 'format' => $format, 'hidden' => array_values($hiddenSeries),
        'ping' => (bool) $ping, 'markers' => (bool) $markers, 'locale' => $locale,
    ];
    $chartId = NabuXUI::id('nx-stacked-area');
    $swatchFill = in_array($fill, ['hatched', 'duotone'], true) ? $fill : null;
@endphp
<figure {{ $attributes->class('nx-chart nx-stacked-area')->merge(['data-type' => 'area', 'data-mode' => $mode, 'data-nx-reveal' => '', 'aria-labelledby' => $title ? "{$chartId}-title" : null]) }}
    x-data="nxStackedArea(@js($config))" wire:ignore>
    <div class="nx-chart-head">
        <div>
            @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
            @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
        </div>
        @if (count($series) > 1)
            <ul class="nx-chart-legend" aria-label="{{ $words['legend'] }}">
                @foreach ($series as $i => $s)
                    <li>
                        <button type="button" class="nx-chart-toggle" x-on:click="toggle({{ $i }})"
                            aria-pressed="{{ in_array($s['name'], $hiddenSeries, true) ? 'false' : 'true' }}"
                            x-bind:aria-pressed="off.includes({{ $i }}) ? 'false' : 'true'">
                            <span class="nx-legend-swatch" @if ($swatchFill) data-fill="{{ $swatchFill }}" @endif style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>{{ $s['name'] }}
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="nx-chart-plot" x-ref="plot" tabindex="0" aria-label="{{ $title ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
        x-on:pointermove="move($event)" x-on:pointerleave="active = null" x-on:keydown="key($event)" x-on:blur="active = null">
        <svg class="nx-chart-svg" x-ref="svg" aria-hidden="true"></svg>
        <div class="nx-chart-tooltip" data-frost x-ref="tip" aria-hidden="true"></div>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td>@foreach ($series as $s)<th scope="col">{{ $s['name'] }}</th>@endforeach<th scope="col">{{ $words['total'] }}</th></tr></thead>
        <tbody>
            @foreach ($labels as $row => $label)
                <tr>
                    <th scope="row">{{ $label }}</th>
                    @foreach ($series as $s)<td>{{ $fmt($s['values'][$row] ?? 0) }}</td>@endforeach
                    <td>{{ $fmt(array_sum(array_map(fn ($s) => max(0, $s['values'][$row] ?? 0), $series))) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</figure>
