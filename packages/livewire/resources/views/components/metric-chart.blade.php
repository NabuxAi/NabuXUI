{{--
    <x-nx::metric-chart title="Workspace health" caption="vs last month" :labels="$months" :metrics="[
        ['id' => 'revenue', 'label' => 'Revenue', 'values' => […], 'format' => ['style' => 'currency', 'currency' => 'USD', 'maximumFractionDigits' => 0], 'delta' => 12.4],
        ['id' => 'users', 'label' => 'Users', 'values' => […], 'delta' => 4.1],
        ['id' => 'latency', 'label' => 'Latency', 'values' => […], 'format' => ['style' => 'unit', 'unit' => 'millisecond'], 'delta' => -8.2, 'invertDelta' => true],
    ]" />

    Tabs switch the metric: the line morphs into the new one, the headline rolls. Every metric
    needs the same number of values as there are labels. `format` is an Intl.NumberFormat options
    array; `value` overrides the headline (the last value by default).
--}}
@props(['labels' => [], 'metrics' => [], 'active' => null, 'title' => null, 'subtitle' => null, 'caption' => null, 'height' => 200])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', app()->getLocale());
    $id = NabuXUI::id('nx-metric');
    $labels = array_values(array_map('strval', $labels));
    $metrics = array_values(array_map(fn ($m) => [
        'id' => (string) $m['id'],
        'label' => (string) $m['label'],
        'values' => array_map('floatval', array_values($m['values'] ?? [])),
        'format' => $m['format'] ?? null,
        'value' => isset($m['value']) ? (float) $m['value'] : null,
        'delta' => isset($m['delta']) ? (float) $m['delta'] : null,
        'invertDelta' => (bool) ($m['invertDelta'] ?? $m['invert_delta'] ?? false),
    ], $metrics));
    $index = array_search((string) ($active ?? ($metrics[0]['id'] ?? '')), array_column($metrics, 'id'), true);
    $index = $index === false ? 0 : (int) $index;
    $metric = $metrics[$index] ?? null;
    $headline = $metric ? ($metric['value'] ?? ($metric['values'] ? end($metric['values']) : 0)) : 0;

    // The server's first paint of a number in a metric's format (the script rolls it from here).
    $formatValue = function (float $value, ?array $format) use ($locale) {
        $digits = (int) ($format['maximumFractionDigits'] ?? (floor($value) == $value ? 0 : 1));
        if (class_exists(\NumberFormatter::class)) {
            $style = $format['style'] ?? 'decimal';
            if ($style === 'currency') {
                $f = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
                $f->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $digits);
                return (string) $f->formatCurrency($value, strtoupper($format['currency'] ?? 'USD'));
            }
            if ($style === 'percent') {
                $f = new \NumberFormatter($locale, \NumberFormatter::PERCENT);
                $f->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $digits);
                return (string) $f->format($value);
            }
            $f = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
            $f->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $digits);
            return (string) $f->format($value).($style === 'unit' && ! empty($format['unit']) ? ' '.$format['unit'] : '');
        }
        return NabuXUI::formatNumber($value, $digits, $locale);
    };
    $percent = fn (float $delta) => ($delta > 0 ? '+' : '').NabuXUI::formatNumber($delta, floor($delta) == $delta ? 0 : 1, $locale).'%';
@endphp
<section {{ $attributes->class('nx-metric-chart')->merge([
    'data-nx-reveal' => '',
    'aria-labelledby' => $title ? "{$id}-title" : null,
    'style' => '--nx-series: var(--nx-chart-'.(min($index, 6) + 1).')',
]) }} x-data="nxMetricChart(@js(['labels' => $labels, 'metrics' => $metrics, 'active' => $index, 'locale' => $locale, 'height' => (int) $height]))"
    x-bind:style="{ '--nx-series': 'var(--nx-chart-' + (Math.min(current, 6) + 1) + ')' }">
    <header class="nx-metric-chart-head">
        @if ($title || $subtitle)
            <div>
                @if ($title)<p class="nx-metric-chart-title" id="{{ $id }}-title">{{ $title }}</p>@endif
                @if ($subtitle)<p class="nx-metric-chart-subtitle">{{ $subtitle }}</p>@endif
            </div>
        @endif
        <div class="nx-metric-chart-tabs" role="tablist" x-ref="tabs" @if (is_string($title)) aria-label="{{ $title }}" @endif x-on:keydown="tabKey($event)">
            <span class="nx-indicator" aria-hidden="true"></span>
            @foreach ($metrics as $i => $m)
                <button type="button" role="tab" id="{{ $id }}-tab-{{ $i }}" class="nx-metric-chart-tab" data-index="{{ $i }}" aria-controls="{{ $id }}-panel"
                    aria-selected="{{ $i === $index ? 'true' : 'false' }}" tabindex="{{ $i === $index ? 0 : -1 }}"
                    x-bind:aria-selected="current === {{ $i }} ? 'true' : 'false'" x-bind:tabindex="current === {{ $i }} ? 0 : -1"
                    style="--nx-series: var(--nx-chart-{{ min($i, 6) + 1 }})" x-on:click="select({{ $i }})">
                    <span class="nx-metric-chart-key" aria-hidden="true"></span>{{ $m['label'] }}
                </button>
            @endforeach
        </div>
    </header>

    <div class="nx-metric-chart-panel" role="tabpanel" id="{{ $id }}-panel" aria-labelledby="{{ $id }}-tab-{{ $index }}" x-bind:aria-labelledby="'{{ $id }}-tab-' + current">
        <div class="nx-metric-chart-summary" wire:ignore>
            <span class="nx-metric-chart-value">
                <span class="nx-number" x-ref="value"><span class="nx-visually-hidden">{{ $formatValue((float) $headline, $metric['format'] ?? null) }}</span><span class="nx-number-roll" aria-hidden="true">{{ $formatValue((float) $headline, $metric['format'] ?? null) }}</span></span>
            </span>
            <span class="nx-delta" x-ref="delta" @if (($metric['delta'] ?? null) === null) hidden @else data-trend="{{ ($metric['invertDelta'] ? $metric['delta'] <= 0 : $metric['delta'] >= 0) ? 'up' : 'down' }}" @endif>
                @if (($metric['delta'] ?? null) !== null){{ NabuXUI::icon($metric['delta'] >= 0 ? 'trend-up' : 'trend-down') }}{{ $percent($metric['delta']) }}@endif
            </span>
            @if ($caption)<span class="nx-metric-chart-caption">{{ $caption }}</span>@endif
        </div>
        <div class="nx-metric-chart-plot" x-ref="plot" tabindex="0" data-hint="{{ __('nabuxui::ui.chartHint') }}" aria-label="{{ $metric['label'] ?? '' }}. {{ __('nabuxui::ui.chartHint') }}"
            x-on:pointermove="move($event)" x-on:pointerleave="hover = null" x-on:keydown="key($event)" x-on:blur="hover = null" wire:ignore>
            <svg class="nx-chart-svg" x-ref="svg" height="{{ (int) $height }}" aria-hidden="true">
                <defs>
                    <linearGradient id="{{ $id }}-fill" x1="0" x2="0" y1="0" y2="1">
                        <stop class="nx-metric-chart-stop" offset="0%" stop-opacity="0.24"/>
                        <stop class="nx-metric-chart-stop" offset="100%" stop-opacity="0.01"/>
                    </linearGradient>
                </defs>
                <g x-ref="ticks"></g>
                <g x-ref="labels"></g>
                <path x-ref="area" class="nx-metric-chart-area" fill="url(#{{ $id }}-fill)"/>
                <path x-ref="line" class="nx-metric-chart-line" pathLength="1"/>
                <line x-ref="cross" class="nx-chart-crosshair"/>
                <circle x-ref="point" class="nx-metric-chart-point" r="4"/>
                <circle x-ref="end" class="nx-metric-chart-point" r="4" data-end/>
            </svg>
            <div class="nx-chart-tooltip" x-ref="tip" aria-hidden="true"></div>
        </div>
    </div>

    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td>@foreach ($metrics as $m)<th scope="col">{{ $m['label'] }}</th>@endforeach</tr></thead>
        <tbody>
            @foreach ($labels as $row => $label)
                <tr><th scope="row">{{ $label }}</th>@foreach ($metrics as $m)<td>{{ $formatValue((float) ($m['values'][$row] ?? 0), $m['format']) }}</td>@endforeach</tr>
            @endforeach
        </tbody>
    </table>
</section>
