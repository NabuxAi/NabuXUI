{{--
    Radar (spider) chart — several series over shared spokes, drawn in from the centre:
        <x-nx::radar-chart title="Model scorecard" :axes="['Speed', 'Accuracy', …]"
            :series="[['name' => 'v2', 'values' => […]], …]" :max="100" rings="5" />
    Spokes run clockwise from the top (counter-clockwise in RTL). Arrow keys walk the
    spokes; legend buttons hide or show a series.
--}}
@props([
    'axes' => [],
    'series' => [],
    'title' => null,
    'subtitle' => null,
    'max' => null,
    'rings' => 4,
    'size' => 340,
    'format' => null,
    'hiddenSeries' => [],
    'dir' => null,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $legend = ['fa' => 'نمایش یا پنهان‌کردن سری‌ها', 'ar' => 'إظهار السلاسل أو إخفاؤها'][$lang] ?? 'Show or hide series';
    $axes = array_values($axes);
    $series = array_values($series);
    $fmt = fn ($v) => NabuXUI::formatNumber((float) $v, floor((float) $v) == (float) $v ? 0 : 1, $locale);
    $config = [
        'axes' => $axes, 'series' => $series, 'max' => $max !== null ? (float) $max : null, 'rings' => (int) $rings,
        'size' => (int) $size, 'format' => $format, 'hidden' => array_values($hiddenSeries), 'dir' => $dir, 'locale' => $locale,
    ];
    $chartId = NabuXUI::id('nx-radar');
@endphp
<figure {{ $attributes->class('nx-chart nx-radar')->merge(['data-type' => 'radar', 'data-nx-reveal' => '', 'aria-labelledby' => $title ? "{$chartId}-title" : null]) }}
    x-data="nxRadar(@js($config))" wire:ignore>
    <div class="nx-chart-head">
        <div>
            @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
            @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
        </div>
        @if (count($series) > 1)
            <ul class="nx-chart-legend" aria-label="{{ $legend }}">
                @foreach ($series as $i => $s)
                    <li>
                        <button type="button" class="nx-chart-toggle" x-on:click="toggle({{ $i }})"
                            aria-pressed="{{ in_array($s['name'], $hiddenSeries, true) ? 'false' : 'true' }}"
                            x-bind:aria-pressed="off.includes({{ $i }}) ? 'false' : 'true'">
                            <span class="nx-legend-swatch" style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>{{ $s['name'] }}
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="nx-chart-plot" x-ref="plot" tabindex="0" aria-label="{{ $title ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
        x-on:keydown="key($event)" x-on:blur="active = null">
        <svg class="nx-chart-svg" x-ref="svg" viewBox="0 0 {{ (int) $size }} {{ (int) $size }}" aria-hidden="true"
            x-on:pointermove="move($event)" x-on:pointerleave="active = null"></svg>
        <div class="nx-chart-tooltip" data-frost x-ref="tip" aria-hidden="true"></div>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td>@foreach ($series as $s)<th scope="col">{{ $s['name'] }}</th>@endforeach</tr></thead>
        <tbody>
            @foreach ($axes as $row => $axis)
                <tr><th scope="row">{{ $axis }}</th>@foreach ($series as $s)<td>{{ $fmt($s['values'][$row] ?? 0) }}</td>@endforeach</tr>
            @endforeach
        </tbody>
    </table>
</figure>
