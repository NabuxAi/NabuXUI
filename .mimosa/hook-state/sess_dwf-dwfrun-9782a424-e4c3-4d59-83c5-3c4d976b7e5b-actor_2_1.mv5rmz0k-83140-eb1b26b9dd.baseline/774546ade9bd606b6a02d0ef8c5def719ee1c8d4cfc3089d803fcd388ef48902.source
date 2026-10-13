{{--
    Forecast line — actual values, then a dashed projection with a confidence band:
        <x-nx::forecast-line title="Revenue" :labels="$months" :actual="[…]" :forecast="[…]"
            :lower="[…]" :upper="[…]" animated today-label="Today" />
    `labels` covers both parts (actual first). The "today" line sits on the last
    actual value, which pings; max / min markers name the extremes of the actuals.
--}}
@props([
    'labels' => [],
    'actual' => [],
    'forecast' => [],
    'lower' => null,
    'upper' => null,
    'title' => null,
    'subtitle' => null,
    'animated' => false,
    'curve' => 'smooth',
    'height' => 280,
    'format' => null,
    'markers' => true,
    'ping' => true,
    'todayLabel' => null,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $words = [
        'fa' => ['actual' => 'واقعی', 'forecast' => 'پیش‌بینی', 'today' => 'امروز', 'low' => 'برآورد پایین', 'high' => 'برآورد بالا'],
        'ar' => ['actual' => 'الفعلي', 'forecast' => 'التوقع', 'today' => 'اليوم', 'low' => 'التقدير الأدنى', 'high' => 'التقدير الأعلى'],
    ][$lang] ?? ['actual' => 'Actual', 'forecast' => 'Forecast', 'today' => 'Today', 'low' => 'Low estimate', 'high' => 'High estimate'];
    $labels = array_values($labels);
    $actual = array_values($actual);
    $forecast = array_values($forecast);
    $band = is_array($lower) && is_array($upper);
    $k = count($actual);
    $fmt = fn ($v) => NabuXUI::formatNumber((float) $v, floor((float) $v) == (float) $v ? 0 : 1, $locale);
    $config = [
        'labels' => $labels, 'actual' => $actual, 'forecast' => $forecast,
        'lower' => $band ? array_values($lower) : null, 'upper' => $band ? array_values($upper) : null,
        'animated' => (bool) $animated, 'curve' => $curve, 'height' => (int) $height, 'format' => $format,
        'markers' => (bool) $markers, 'ping' => (bool) $ping, 'todayLabel' => $todayLabel ?? $words['today'], 'locale' => $locale,
    ];
    $chartId = NabuXUI::id('nx-forecast');
@endphp
<figure {{ $attributes->class('nx-chart nx-forecast')->merge(['data-type' => 'line', 'data-nx-reveal' => '', 'aria-labelledby' => $title ? "{$chartId}-title" : null]) }}
    x-data="nxForecast(@js($config))" wire:ignore>
    <div class="nx-chart-head">
        <div>
            @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
            @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
        </div>
        <ul class="nx-chart-legend">
            <li class="nx-legend-item"><span class="nx-legend-swatch" data-shape="line" style="--nx-series: var(--nx-chart-1)"></span>{{ $words['actual'] }}</li>
            <li class="nx-legend-item"><span class="nx-legend-swatch" data-shape="dash" style="--nx-series: var(--nx-chart-1)"></span>{{ $words['forecast'] }}</li>
        </ul>
    </div>
    <div class="nx-chart-plot" x-ref="plot" tabindex="0" aria-label="{{ $title ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
        x-on:pointermove="move($event)" x-on:pointerleave="active = null" x-on:keydown="key($event)" x-on:blur="active = null">
        <svg class="nx-chart-svg" x-ref="svg" aria-hidden="true"></svg>
        <div class="nx-chart-tooltip" data-frost x-ref="tip" aria-hidden="true"></div>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead>
            <tr>
                <td></td><th scope="col">{{ $words['actual'] }}</th><th scope="col">{{ $words['forecast'] }}</th>
                @if ($band)<th scope="col">{{ $words['low'] }}</th><th scope="col">{{ $words['high'] }}</th>@endif
            </tr>
        </thead>
        <tbody>
            @foreach ($labels as $row => $label)
                @php $j = $row - $k; @endphp
                <tr>
                    <th scope="row">{{ $label }}</th>
                    <td>{{ $row < $k ? $fmt($actual[$row] ?? 0) : '' }}</td>
                    <td>{{ $row >= $k ? $fmt($forecast[$j] ?? 0) : '' }}</td>
                    @if ($band)
                        <td>{{ $row >= $k ? $fmt($lower[$j] ?? 0) : '' }}</td>
                        <td>{{ $row >= $k ? $fmt($upper[$j] ?? 0) : '' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</figure>
