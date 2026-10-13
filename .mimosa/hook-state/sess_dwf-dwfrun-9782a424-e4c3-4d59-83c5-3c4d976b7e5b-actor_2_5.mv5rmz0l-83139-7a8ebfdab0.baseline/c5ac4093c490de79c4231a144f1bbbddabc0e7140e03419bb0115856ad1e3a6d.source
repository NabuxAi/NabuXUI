{{--
    Chart loading — the skeleton a chart card shows while its data loads:
        <x-nx::chart-loading variant="line|area|bar|donut" height="15rem" :title="$title" />
    A shimmer sweeps toward inline-end (a slow pulse under reduced motion). It is a
    polite status region that says "Loading chart"; swap it for the real chart
    (e.g. with wire:loading / @if ($ready)) when the data lands. No JavaScript.
--}}
@props(['variant' => 'line', 'height' => null, 'label' => null, 'title' => null, 'legend' => 2, 'locale' => null])
@php
    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $text = $label ?? (['fa' => 'در حال بارگذاری نمودار', 'ar' => 'جارٍ تحميل المخطط'][$lang] ?? 'Loading chart');
    $bars = [46, 68, 38, 82, 58, 74, 52, 90, 64];
@endphp
<div {{ $attributes->class('nx-chart-loading')->merge([
    'role' => 'status',
    'aria-busy' => 'true',
    'aria-live' => 'polite',
    'data-variant' => $variant,
    'style' => $height ? "--nx-chart-loading-height: {$height}" : null,
]) }}>
    <span class="nx-visually-hidden">{{ $text }}</span>
    <div class="nx-chart-loading-head" aria-hidden="true">
        <div class="nx-chart-loading-lines">
            @if ($title)
                <p class="nx-chart-title">{{ $title }}</p>
            @else
                <span class="nx-chart-loading-bone" data-size="lg" style="--_w: 9rem"></span>
            @endif
            <span class="nx-chart-loading-bone" style="--_w: 6rem"></span>
        </div>
        @if ($variant !== 'donut' && (int) $legend > 0)
            <div class="nx-chart-loading-legend">
                @for ($i = 0; $i < (int) $legend; $i++)<span class="nx-chart-loading-bone" style="--_w: 3.5rem"></span>@endfor
            </div>
        @endif
    </div>
    <div class="nx-chart-loading-plot" aria-hidden="true">
        @if ($variant === 'donut')
            <svg viewBox="0 0 100 100"><circle class="nx-chart-loading-ring" cx="50" cy="50" r="38" /></svg>
        @else
            <svg viewBox="0 0 300 120" preserveAspectRatio="none">
                @foreach ([20, 50, 80, 110] as $y)<line class="nx-chart-loading-grid" x1="0" x2="300" y1="{{ $y }}" y2="{{ $y }}" />@endforeach
                @if ($variant === 'bar')
                    @foreach ($bars as $i => $h)<rect class="nx-chart-loading-shape" x="{{ 12 + $i * 32 }}" y="{{ 110 - $h }}" width="18" height="{{ $h }}" rx="3" />@endforeach
                @else
                    @if ($variant === 'area')<path class="nx-chart-loading-wash" d="M0,84 C40,70 60,92 100,66 S160,40 200,52 S260,30 300,24 L300,120 L0,120 Z" />@endif
                    <path class="nx-chart-loading-line" d="M0,84 C40,70 60,92 100,66 S160,40 200,52 S260,30 300,24" />
                @endif
            </svg>
        @endif
    </div>
</div>
