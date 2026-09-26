{{--
    One series:  <x-nx::dot-matrix-chart title="Tickets" :data="['Mon' => 42, 'Tue' => 58, …]" />
    Several:     <x-nx::dot-matrix-chart title="Tickets by language" :labels="['EN', 'ES', …]" :series="[
                     ['name' => 'Solved by the agent', 'values' => […]],
                     ['name' => 'Handed to a human', 'values' => […]],
                 ]" />

    Each column is a stack of `rows` dots (10) lit up to its total; series stack in fixed colour
    order. One dot is `unit` (a round number that fits the tallest column by default).
--}}
@props(['data' => null, 'labels' => [], 'series' => null, 'rows' => 10, 'unit' => null, 'title' => null, 'subtitle' => null])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', app()->getLocale());
    $lang = substr($locale, 0, 2);
    $perDotText = ['fa' => 'هر نقطه برابر :value است', 'ar' => 'كل نقطة تساوي :value'][$lang] ?? 'Each dot is :value';
    if ($data !== null) {
        $labels = array_keys($data);
        $series = [['name' => $title ?? 'Value', 'values' => array_values($data)]];
    }
    $labels = array_values(array_map('strval', $labels));
    $series = array_values(array_map(fn ($s) => ['name' => (string) $s['name'], 'values' => array_map('floatval', array_values($s['values']))], $series ?? []));
    $rows = max(1, (int) $rows);
    $totals = array_map(fn ($c) => array_sum(array_map(fn ($s) => max(0, $s['values'][$c] ?? 0), $series)), array_keys($labels));
    if (! $unit) {
        $rough = (max($totals ?: [0]) ?: 1) / $rows;
        $magnitude = 10 ** floor(log10($rough));
        $unit = 10 * $magnitude;
        foreach ([1, 2, 2.5, 5, 10] as $m) {
            if ($m * $magnitude >= $rough - 1e-9) {
                $unit = $m * $magnitude;
                break;
            }
        }
    }
    // Which series lights each dot, bottom to top (largest remainders keep the total true).
    $stack = function (array $values) use ($unit, $rows) {
        $exact = array_map(fn ($v) => max(0, $v) / $unit, $values);
        $total = min($rows, (int) round(array_sum($exact)));
        $counts = array_map(fn ($v) => (int) floor($v), $exact);
        $spare = $total - array_sum($counts);
        for ($i = count($counts) - 1; $i >= 0 && $spare < 0; $i--) {
            $cut = min($counts[$i], -$spare);
            $counts[$i] -= $cut;
            $spare += $cut;
        }
        $order = array_keys($exact);
        usort($order, fn ($a, $b) => (($exact[$b] - floor($exact[$b])) <=> ($exact[$a] - floor($exact[$a]))) ?: $a <=> $b);
        foreach ($order as $i) {
            if ($spare <= 0) break;
            $counts[$i]++;
            $spare--;
        }
        $dots = [];
        foreach ($counts as $s => $count) for ($k = 0; $k < $count && count($dots) < $rows; $k++) $dots[] = $s;
        return array_pad($dots, $rows, null);
    };
    $every = max(1, (int) ceil(count($labels) / 12));
    $chartId = NabuXUI::id('nx-dots');
    $fmt = fn ($v) => NabuXUI::formatNumber($v, floor($v) == $v ? 0 : 1, $locale);
@endphp
<figure {{ $attributes->class('nx-chart nx-dot-matrix')->merge(['data-nx-reveal' => '', 'style' => "--nx-rows: {$rows}", 'aria-labelledby' => $title ? "{$chartId}-title" : null]) }}
    x-data="nxDotMatrix(@js(['labels' => $labels, 'series' => $series, 'locale' => $locale]))">
    @if ($title || $subtitle || count($series) > 1)
        <div class="nx-chart-head">
            <div>
                @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
                @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
            </div>
            @if (count($series) > 1)
                <ul class="nx-chart-legend">
                    @foreach ($series as $i => $s)<li class="nx-legend-item"><span class="nx-legend-swatch" style="--nx-series: var(--nx-chart-{{ min($i, 6) + 1 }}); border-radius: 50%"></span>{{ $s['name'] }}</li>@endforeach
                </ul>
            @endif
        </div>
    @endif
    <div class="nx-dot-matrix-frame" x-ref="frame">
        <div class="nx-dot-matrix-plot" x-ref="plot" tabindex="0" aria-label="{{ $title ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
            x-on:pointerover="pick($event)" x-on:pointerleave="active = null" x-on:keydown="key($event)" x-on:blur="active = null">
            @foreach ($labels as $c => $label)
                <div class="nx-dot-matrix-col" data-index="{{ $c }}" style="--nx-c: {{ $c }}">
                    <span class="nx-dot-matrix-dots" aria-hidden="true">
                        @foreach ($stack(array_map(fn ($s) => $s['values'][$c] ?? 0, $series)) as $r => $s)<i class="nx-dot-matrix-dot" @if ($s !== null) data-series="{{ $s }}" style="--nx-r: {{ $r }}; --nx-series: var(--nx-chart-{{ min($s, 6) + 1 }})" @else style="--nx-r: {{ $r }}" @endif></i>@endforeach
                    </span>
                    <span class="nx-dot-matrix-label" aria-hidden="true">{{ $c % $every === 0 || $c === count($labels) - 1 ? $label : '' }}</span>
                </div>
            @endforeach
        </div>
        <div class="nx-chart-tooltip" x-ref="tip" aria-hidden="true" wire:ignore></div>
    </div>
    <p class="nx-dot-matrix-note">{{ str_replace(':value', $fmt($unit), $perDotText) }}</p>
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
