{{--
    <x-nx::analytics-card title="Visitors" active="7d" :periods="[
        ['id' => '7d', 'label' => '7d', 'value' => 18420, 'delta' => 12.5, 'values' => […7], 'labels' => ['Mon', …], 'caption' => 'vs previous 7 days'],
        ['id' => '30d', 'label' => '30d', 'value' => 76100, 'delta' => 4.2, 'values' => […30]],
        ['id' => '90d', 'label' => '90d', 'value' => 214300, 'delta' => -2.1, 'values' => […13]],
    ]" />

    The period switch slides its thumb; the headline rolls and the bars grow or shrink to the new
    period, one after another. `format` is an Intl.NumberFormat options array for the numbers.
--}}
@props(['title' => null, 'periods' => [], 'active' => null, 'format' => null, 'invertDelta' => false])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', app()->getLocale());
    $periods = array_values(array_map(fn ($p) => [
        'id' => (string) $p['id'],
        'label' => (string) ($p['label'] ?? $p['id']),
        'value' => (float) ($p['value'] ?? 0),
        'delta' => isset($p['delta']) ? (float) $p['delta'] : null,
        'values' => array_map('floatval', array_values($p['values'] ?? [])),
        'labels' => isset($p['labels']) ? array_values(array_map('strval', $p['labels'])) : null,
        'caption' => $p['caption'] ?? null,
    ], $periods));
    $active = (string) ($active ?? ($periods[0]['id'] ?? ''));
    $period = collect($periods)->firstWhere('id', $active) ?? ($periods[0] ?? ['id' => '', 'label' => '', 'value' => 0, 'delta' => null, 'values' => [], 'labels' => null, 'caption' => null]);
    $slots = max(array_map(fn ($p) => count($p['values']), $periods) ?: [0]);
    $count = count($period['values']);
    $max = max($period['values'] ?: [0]) ?: 1;
    $nameOf = fn (array $p, int $i) => $p['labels'][$i] ?? (string) ($i + 1);
    $digits = (int) ($format['maximumFractionDigits'] ?? 0);
    $number = fn ($v) => NabuXUI::formatNumber($v, $digits, $locale);
    $good = $period['delta'] === null ? true : ($invertDelta ? $period['delta'] <= 0 : $period['delta'] >= 0);
    $config = ['periods' => $periods, 'active' => $active, 'locale' => $locale, 'format' => $format, 'invertDelta' => (bool) $invertDelta, 'title' => is_string($title) ? $title : null];
@endphp
<article {{ $attributes->class('nx-analytics-card')->merge(['data-nx-reveal' => '']) }} x-data="nxAnalyticsCard(@js($config))">
    <header class="nx-analytics-card-head">
        <p class="nx-analytics-card-title">{{ $title }}</p>
        <x-nx::segmented size="sm" :label="is_string($title) ? $title : null" :value="$active" :options="collect($periods)->mapWithKeys(fn ($p) => [$p['id'] => $p['label']])->all()"
            x-on:change="current = $event.target.value" />
    </header>
    <div class="nx-analytics-card-kpi" wire:ignore>
        <span class="nx-analytics-card-value">
            <span class="nx-number" x-ref="value"><span class="nx-visually-hidden">{{ $number($period['value']) }}</span><span class="nx-number-roll" aria-hidden="true">{{ $number($period['value']) }}</span></span>
        </span>
        <span class="nx-delta" x-ref="delta" @if ($period['delta'] === null) hidden @else data-trend="{{ $good ? 'up' : 'down' }}" @endif>
            @if ($period['delta'] !== null){{ NabuXUI::icon($period['delta'] >= 0 ? 'trend-up' : 'trend-down') }}{{ ($period['delta'] > 0 ? '+' : '').NabuXUI::formatNumber($period['delta'], 1, $locale) }}%@endif
        </span>
    </div>
    <p class="nx-analytics-card-caption" x-text="period.caption ?? ''">{{ $period['caption'] }}</p>
    <div class="nx-analytics-card-frame" x-ref="frame">
        <div class="nx-analytics-card-bars" x-ref="bars" tabindex="0" aria-label="{{ is_string($title) ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
            x-on:pointerover="pick($event)" x-on:pointerleave="hover = null" x-on:keydown="key($event)" x-on:blur="hover = null">
            @for ($i = 0; $i < $slots; $i++)
                <span class="nx-analytics-card-slot" data-index="{{ $i }}" data-state="{{ $i < $count ? 'on' : 'off' }}" @if ($i === $count - 1) data-current @endif
                    style="--nx-i: {{ $i }}; --nx-v: {{ $i < $count ? max(0.02, round($period['values'][$i] / $max, 4)) : 0 }}"
                    x-bind:data-state="{{ $i }} < period.values.length ? 'on' : 'off'" x-bind:data-current="{{ $i }} === period.values.length - 1 ? '' : null" x-bind:style="slotStyle({{ $i }})">
                    <span class="nx-analytics-card-bar"><span class="nx-analytics-card-fill"></span></span>
                </span>
            @endfor
        </div>
        <div class="nx-chart-tooltip" x-ref="tip" aria-hidden="true" wire:ignore></div>
    </div>
    <div class="nx-analytics-card-axis" aria-hidden="true" x-show="period.values.length > 1">
        <span x-text="nameOf(0)">{{ $count ? $nameOf($period, 0) : '' }}</span>
        <span x-text="nameOf(period.values.length - 1)">{{ $count ? $nameOf($period, $count - 1) : '' }}</span>
    </div>
    @foreach ($periods as $p)
        <table class="nx-visually-hidden" @if ($p['id'] !== $active) hidden @endif x-bind:hidden="current !== @js($p['id'])">
            <caption>{{ is_string($title) ? $title.' · ' : '' }}{{ $p['label'] }}</caption>
            <tbody>
                @foreach ($p['values'] as $i => $v)<tr><th scope="row">{{ $nameOf($p, $i) }}</th><td>{{ $number($v) }}</td></tr>@endforeach
            </tbody>
        </table>
    @endforeach
</article>
