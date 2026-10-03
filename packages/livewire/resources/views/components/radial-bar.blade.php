{{--
    Radial bar — concentric progress rings that sweep in, with a labelled list beside them:
        <x-nx::radial-bar title="Quarter goals" :data="[
            ['label' => 'Revenue', 'value' => 82],
            ['label' => 'New users', 'value' => 1240, 'max' => 2000, 'hint' => 'Target 2,000'],
        ]" sweep="270" />
    Each ring is its share of its own `max` (the chart's `max`, 100, by default). The
    centre reads the average share, or the hovered ring.
--}}
@props([
    'data' => [],
    'title' => null,
    'subtitle' => null,
    'max' => 100,
    'sweep' => 270,
    'centerValue' => null,
    'centerLabel' => null,
    'size' => null,
    'format' => null,
    'dir' => null,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $words = [
        'fa' => ['of' => ':value از :max', 'value' => 'مقدار', 'share' => 'سهم'],
        'ar' => ['of' => ':value من :max', 'value' => 'القيمة', 'share' => 'الحصة'],
    ][$lang] ?? ['of' => ':value of :max', 'value' => 'Value', 'share' => 'Share'];
    $items = array_values(array_map(fn ($d) => [
        'label' => (string) ($d['label'] ?? ''),
        'value' => (float) ($d['value'] ?? 0),
        'max' => (float) ($d['max'] ?? $max) ?: 100.0,
        'hint' => $d['hint'] ?? null,
    ], $data));
    $fmt = fn ($v) => NabuXUI::formatNumber((float) $v, floor((float) $v) == (float) $v ? 0 : 1, $locale);
    $of = fn ($item) => strtr($words['of'], [':value' => $fmt($item['value']), ':max' => $fmt($item['max'])]);
    $share = fn ($item) => max(0, min(100, round($item['value'] / $item['max'] * 100)));
    $percentSign = in_array($lang, ['fa', 'ar'], true) ? '٪' : '%';
    $config = [
        'values' => array_column($items, 'value'), 'maxes' => array_column($items, 'max'), 'labels' => array_column($items, 'label'),
        'sweep' => (float) $sweep, 'centerValue' => $centerValue, 'centerLabel' => $centerLabel ?? $words['share'],
        'format' => $format, 'dir' => $dir, 'locale' => $locale,
    ];
    $average = count($items) ? array_sum(array_map($share, $items)) / count($items) : 0;
    $chartId = NabuXUI::id('nx-radial');
@endphp
<figure {{ $attributes->class('nx-chart nx-radial-bar')->merge([
    'data-type' => 'radial',
    'data-nx-reveal' => '',
    'aria-labelledby' => $title ? "{$chartId}-title" : null,
    'style' => $size ? "--nx-radial-size: {$size}" : null,
]) }} x-data="nxRadialBar(@js($config))" wire:ignore>
    @if ($title || $subtitle)
        <div class="nx-chart-head">
            <div>
                @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
                @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
            </div>
        </div>
    @endif
    <div class="nx-radial-bar-body">
        <div class="nx-radial-bar-dial" x-on:pointerleave="active = null">
            <svg class="nx-chart-svg" viewBox="0 0 100 100" x-ref="svg" aria-hidden="true"></svg>
            <div class="nx-radial-bar-center" aria-hidden="true">
                <span class="nx-radial-bar-value" x-text="centerValue()">{{ $centerValue ?? NabuXUI::formatNumber(round($average), 0, $locale).$percentSign }}</span>
                <span class="nx-radial-bar-label" x-text="centerLabel()">{{ $centerLabel ?? $words['share'] }}</span>
            </div>
        </div>
        <ul class="nx-radial-bar-list">
            @foreach ($items as $i => $item)
                <li class="nx-radial-bar-item" x-bind:data-active="active === {{ $i }} ? '' : null"
                    x-on:pointerenter="active = {{ $i }}" x-on:pointerleave="active = null">
                    <span class="nx-legend-swatch" style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>
                    <span>{{ $item['label'] }}</span>
                    <strong>{{ $of($item) }}</strong>
                    @if ($item['hint'])<small>{{ $item['hint'] }}</small>@endif
                </li>
            @endforeach
        </ul>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td><th scope="col">{{ $words['value'] }}</th><th scope="col">{{ $words['share'] }}</th></tr></thead>
        <tbody>
            @foreach ($items as $item)
                <tr><th scope="row">{{ $item['label'] }}</th><td>{{ $of($item) }}</td><td>{{ NabuXUI::formatNumber($share($item), 0, $locale).$percentSign }}</td></tr>
            @endforeach
        </tbody>
    </table>
</figure>
