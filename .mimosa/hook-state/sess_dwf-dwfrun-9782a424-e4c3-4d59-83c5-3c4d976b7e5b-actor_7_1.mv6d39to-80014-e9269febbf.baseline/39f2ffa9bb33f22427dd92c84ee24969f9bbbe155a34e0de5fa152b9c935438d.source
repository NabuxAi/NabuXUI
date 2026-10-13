{{-- <x-nx::chart.area title="Conversations" :labels="$months" :series="[['name' => 'Telegram', 'values' => […]]]" variant="area|line" /> --}}
@props(['labels' => [], 'series' => [], 'title' => null, 'subtitle' => null, 'variant' => 'area', 'format' => null, 'height' => 260, 'smooth' => true])
@php $type = $variant === 'line' ? 'line' : 'area'; @endphp
@php
    $title ??= null;
    $subtitle ??= null;
    $locale = str_replace('_', '-', app()->getLocale());
    $formatter = fn ($v) => \NabuXUI\NabuXUI::formatNumber($v, 0);
    $config = ['type' => $type, 'labels' => array_values($labels), 'series' => $series, 'locale' => $locale, 'format' => $format, 'height' => $height, 'smooth' => $smooth ?? true];
    $chartId = \NabuXUI\NabuXUI::id('nx-chart');
@endphp
<figure {{ $attributes->class('nx-chart')->merge(['data-type' => $type, 'data-nx-reveal' => '']) }} x-data="nxChart(@js($config))" wire:ignore @if ($title) aria-labelledby="{{ $chartId }}-title" @endif>
    @if ($title || count($series) > 1)
        <div class="nx-chart-head">
            <div>
                @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
                @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
            </div>
            @if (count($series) > 1)
                <ul class="nx-chart-legend">
                    @foreach ($series as $i => $s)
                        <li class="nx-legend-item"><span class="nx-legend-swatch" data-shape="{{ $type === 'line' ? 'line' : 'rect' }}" style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>{{ $s['name'] }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
    <div class="nx-chart-plot" x-ref="plot" tabindex="0" aria-label="{{ $title ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
        @pointermove="move($event)" @pointerleave="active = null" @keydown="key($event)" @blur="active = null">
        <svg class="nx-chart-svg" x-ref="svg" aria-hidden="true"></svg>
        <div class="nx-chart-tooltip" x-ref="tip" aria-hidden="true"></div>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td>@foreach ($series as $s)<th scope="col">{{ $s['name'] }}</th>@endforeach</tr></thead>
        <tbody>
            @foreach (array_values($labels) as $row => $label)
                <tr><th scope="row">{{ $label }}</th>@foreach ($series as $s)<td>{{ $formatter($s['values'][$row] ?? 0) }}</td>@endforeach</tr>
            @endforeach
        </tbody>
    </table>
</figure>
