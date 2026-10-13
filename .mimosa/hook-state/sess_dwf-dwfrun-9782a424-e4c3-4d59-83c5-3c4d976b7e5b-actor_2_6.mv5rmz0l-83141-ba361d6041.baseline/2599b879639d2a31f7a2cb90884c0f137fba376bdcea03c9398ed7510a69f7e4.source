{{--
    A CSS bar chart — bars grow in steps when the chart scrolls into view:
        <x-nx::bar-chart title="Tickets closed" :data="['Mon' => 42, 'Tue' => 58]" />
    Several series, grouped side by side or stacked:
        <x-nx::bar-chart :labels="['Mon', 'Tue', 'Wed']" stacked
            :series="[['name' => 'Web', 'values' => [12, 18, 9]], ['name' => 'Mobile', 'values' => [8, 11, 14]]]" />

    No JavaScript: heights ride custom properties, the tooltip opens on hover and
    on keyboard focus above each group's tallest bar, and the value axis is a
    server-computed nice-ticks ladder in the locale's digits. Values below zero
    are clamped to the baseline. The same data ships as a visually-hidden table.
--}}
@props(['data' => null, 'labels' => [], 'series' => null, 'stacked' => false, 'title' => null, 'subtitle' => null, 'height' => 240, 'locale' => null])
@php
    use NabuXUI\NabuXUI;

    $stacked = filter_var($stacked, FILTER_VALIDATE_BOOL);
    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The chart's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $chartId = NabuXUI::id('nx-bar-chart');

    if ($data !== null) {
        $labels = array_keys(is_array($data) ? $data : []);
        $series = [['name' => null, 'values' => array_values(is_array($data) ? $data : [])]];
    }
    $series = array_values(array_map(fn ($s) => [
        'name' => isset($s['name']) && $s['name'] !== '' ? (string) $s['name'] : null,
        'values' => array_values(array_map(fn ($v) => max(0.0, (float) $v), is_array($s['values'] ?? null) ? $s['values'] : [])),
    ], is_array($series) ? $series : []));
    $labels = array_values(array_map(fn ($l) => (string) $l, is_array($labels) ? $labels : []));
    if ($labels === [] && $series !== []) {
        $longest = max(1, max(0, ...array_map(fn ($s) => count($s['values']), $series)));
        $labels = array_map(fn ($i) => (string) ($i + 1), range(0, $longest - 1));
    }

    // One decimal only when a value asks for it (heatmap rule).
    $decimals = 0;
    foreach ($series as $s) {
        foreach ($s['values'] as $v) {
            if (floor($v) != $v) { $decimals = 1; break 2; }
        }
    }
    $fmt = fn ($v) => NabuXUI::formatNumber($v, $decimals, $locale);

    // Nice ticks over [0, max] — the same ladder the core's niceTicks() builds.
    // Stacks are measured by their totals, grouped bars by the largest single value.
    $maxValue = 0.0;
    if ($stacked) {
        foreach ($labels as $i => $label) {
            $sum = 0.0;
            foreach ($series as $s) $sum += $s['values'][$i] ?? 0;
            $maxValue = max($maxValue, $sum);
        }
    } else {
        foreach ($series as $s) $maxValue = max($maxValue, ...array_merge([0.0], $s['values']));
    }
    $ticks = [0, 1];
    if ($maxValue > 0) {
        $rough = $maxValue / 4;
        $magnitude = 10 ** floor(log10($rough));
        $step = 10 * $magnitude;
        foreach ([1, 2, 2.5, 5, 10] as $m) {
            if ($m * $magnitude >= $rough) { $step = $m * $magnitude; break; }
        }
        $ticks = [];
        for ($v = 0, $end = ceil($maxValue / $step) * $step; $v <= $end + $step / 2; $v += $step) $ticks[] = round($v, 10);
    }
    $top = (float) end($ticks);
    $percent = fn ($v) => $top > 0 ? round($v / $top * 100, 2) : 0;
    $tickDecimals = 0;
    foreach ($ticks as $tick) {
        if (floor($tick) != $tick) { $tickDecimals = 1; break; }
    }

    // A group per category: its values, its total and where its tooltip parks.
    $groups = [];
    foreach ($labels as $i => $label) {
        $values = array_map(fn ($s) => $s['values'][$i] ?? 0.0, $series);
        $groups[] = [
            'label' => $label,
            'values' => $values,
            'total' => array_sum($values),
            'top' => $percent($stacked ? array_sum($values) : max(array_merge([0.0], $values))),
        ];
    }
    $nameOf = fn ($s) => $s['name'] ?? $say('barChartSeries');
@endphp
<figure {{ $attributes->class('nx-chart nx-bar-chart')->merge([
    'aria-labelledby' => $title ? "{$chartId}-title" : null,
    'data-nx-reveal' => '',
    'style' => is_numeric($height) ? "--nx-bar-chart-height: {$height}px" : (string) $height,
]) }} x-data x-nx-reveal>
    @if ($title || $subtitle || count($series) > 1)
        <div class="nx-chart-head">
            <div>
                @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
                @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
            </div>
            @if (count($series) > 1)
                <ul class="nx-chart-legend">
                    @foreach ($series as $j => $s)
                        <li class="nx-legend-item"><span class="nx-legend-swatch" style="--nx-series: var(--nx-chart-{{ $j % 7 + 1 }})"></span>{{ $nameOf($s) }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
    <div class="nx-bar-chart-frame">
        <div class="nx-bar-chart-plot">
            @foreach ($ticks as $tick)
                <span class="nx-bar-chart-gridline" @if ($tick == 0) data-zero @endif style="--_p: {{ $percent($tick) }}" aria-hidden="true"></span>
            @endforeach
            @foreach ($ticks as $tick)
                <span class="nx-bar-chart-tick" style="--_p: {{ $percent($tick) }}" aria-hidden="true">{{ NabuXUI::formatNumber($tick, $tickDecimals, $locale) }}</span>
            @endforeach
            <div class="nx-bar-chart-row">
                @foreach ($groups as $i => $group)
                    @php
                        // "{label}: 12" for one unnamed series, "{label}: Web 12 · Mobile 8" otherwise.
                        $spoken = count($series) === 1 && $series[0]['name'] === null
                            ? $fmt($group['values'][0] ?? 0)
                            : implode(' · ', array_map(fn ($s, $v) => $nameOf($s).' '.$fmt($v), $series, $group['values']));
                    @endphp
                    <div class="nx-bar-chart-group" tabindex="0" role="img"
                        aria-label="{{ $group['label'] }}: {{ $spoken }}"
                        style="--nx-i: {{ $i }}; --_top: {{ $group['top'] }}">
                        <div class="nx-bar-chart-bars" @if ($stacked) data-stacked @endif aria-hidden="true">
                            @foreach ($group['values'] as $j => $value)
                                <span class="nx-bar-chart-bar" @if (! $stacked || $j === count($group['values']) - 1) data-cap @endif
                                    style="--_v: {{ $percent($value) }}; --nx-j: {{ $j }}; --nx-series: var(--nx-chart-{{ $j % 7 + 1 }})"></span>
                            @endforeach
                        </div>
                        <div class="nx-chart-tooltip nx-bar-chart-tip" aria-hidden="true">
                            <p class="nx-chart-tooltip-title">{{ $group['label'] }}</p>
                            @foreach ($group['values'] as $j => $value)
                                <div class="nx-chart-tooltip-row">
                                    <span class="nx-chart-tooltip-key" style="--nx-series: var(--nx-chart-{{ $j % 7 + 1 }})"></span>
                                    <strong>{{ $fmt($value) }}</strong>
                                    <span>{{ $nameOf($series[$j]) }}</span>
                                </div>
                            @endforeach
                            @if ($stacked && count($series) > 1)
                                <div class="nx-chart-tooltip-row">
                                    <strong>{{ $fmt($group['total']) }}</strong>
                                    <span>{{ $say('barChartTotal') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="nx-bar-chart-names" aria-hidden="true">
            @foreach ($groups as $group)<span class="nx-bar-chart-name">{{ $group['label'] }}</span>@endforeach
        </div>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td>@foreach ($series as $s)<th scope="col">{{ $nameOf($s) }}</th>@endforeach</tr></thead>
        <tbody>
            @foreach ($groups as $group)
                <tr><th scope="row">{{ $group['label'] }}</th>@foreach ($group['values'] as $value)<td>{{ $fmt($value) }}</td>@endforeach</tr>
            @endforeach
        </tbody>
    </table>
</figure>
