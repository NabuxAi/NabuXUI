{{-- <x-nx::chart.donut title="Channels" center-label="messages" :data="['Telegram' => 4700, 'WhatsApp' => 3300]" /> --}}
@props(['data' => [], 'title' => null, 'centerLabel' => null, 'size' => null, 'thickness' => 14])
@php
    $labels = array_keys($data);
    $values = array_values($data);
    $segments = \NabuXUI\NabuXUI::donut($values);
    $total = array_sum($values);
    $radius = 50 - $thickness / 2 - 4;
    $fmt = fn ($v) => \NabuXUI\NabuXUI::formatNumber($v);
@endphp
<figure {{ $attributes->class('nx-chart')->merge(['data-type' => 'donut', 'data-nx-reveal' => '']) }}
    x-data="{ active: null, values: @js(array_map($fmt, $values)), labels: @js($labels) }" x-nx-reveal>
    <div class="nx-donut" style="{{ $size ? "--nx-donut-size: {$size};" : '' }} --nx-donut-thickness: {{ $thickness }}">
        <svg class="nx-chart-svg" viewBox="0 0 100 100" aria-hidden="true" @pointerleave="active = null">
            @foreach ($segments as $i => $segment)
                <circle class="nx-donut-segment" cx="50" cy="50" r="{{ $radius }}" pathLength="100" x-bind:data-active="active === {{ $i }} ? '' : null" @pointerenter="active = {{ $i }}"
                    style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }}); --nx-len: {{ $segment['length'] }}; --nx-offset: {{ $segment['offset'] }}; --nx-i: {{ $i }}"/>
            @endforeach
        </svg>
        <div class="nx-donut-center" aria-hidden="true">
            <span class="nx-donut-value" x-text="active === null ? @js($fmt($total)) : values[active]">{{ $fmt($total) }}</span>
            <span class="nx-donut-label" x-text="active === null ? @js($centerLabel ?? $title) : labels[active]">{{ $centerLabel ?? $title }}</span>
        </div>
    </div>
    <ul class="nx-chart-legend" style="justify-content: center">
        @foreach ($labels as $i => $label)
            <li class="nx-legend-item" @pointerenter="active = {{ $i }}" @pointerleave="active = null"><span class="nx-legend-swatch" style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>{{ $label }} · {{ \NabuXUI\NabuXUI::formatNumber($segments[$i]['share'] * 100) }}%</li>
        @endforeach
    </ul>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <tbody>@foreach ($labels as $i => $label)<tr><th scope="row">{{ $label }}</th><td>{{ $fmt($values[$i]) }}</td></tr>@endforeach</tbody>
    </table>
</figure>
