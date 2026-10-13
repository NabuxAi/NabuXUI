{{-- <x-nx::stat-card label="Revenue" :value="48260" :delta="12.4" :trend="[12, 18, 14, 22]" caption="vs last month" /> --}}
@props(['label', 'value' => 0, 'decimals' => 0, 'prefix' => null, 'suffix' => null, 'delta' => null, 'invertDelta' => false, 'trend' => null, 'caption' => null])
@php $good = $delta === null ? null : ($invertDelta ? $delta <= 0 : $delta >= 0); @endphp
<article {{ $attributes->class('nx-stat') }}>
    <div class="nx-stat-head">
        <span class="nx-stat-label">{{ $label }}</span>
        @if ($delta !== null)
            <span class="nx-delta" data-trend="{{ $good ? 'up' : 'down' }}">
                {{ \NabuXUI\NabuXUI::icon($delta >= 0 ? 'trend-up' : 'trend-down') }}{{ ($delta > 0 ? '+' : '').\NabuXUI\NabuXUI::formatNumber($delta, 1) }}%
            </span>
        @endif
    </div>
    <div class="nx-stat-value">{{ $prefix }}<x-nx::number :value="$value" :decimals="$decimals" />{{ $suffix }}</div>
    @if ($trend && count($trend) > 1)<x-nx::sparkline :data="$trend" :trend="$good === null ? null : ($good ? 'up' : 'down')" />@endif
    @if ($caption)<p class="nx-stat-caption">{{ $caption }}</p>@endif
</article>
