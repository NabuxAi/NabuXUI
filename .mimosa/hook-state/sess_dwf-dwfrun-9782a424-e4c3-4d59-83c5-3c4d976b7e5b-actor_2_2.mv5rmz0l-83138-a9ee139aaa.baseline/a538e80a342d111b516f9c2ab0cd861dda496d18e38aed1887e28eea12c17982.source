@props(['data' => [], 'trend' => null, 'area' => true])
@php $paths = \NabuXUI\NabuXUI::sparkline(array_values($data)); @endphp
<svg {{ $attributes->class('nx-sparkline nx-chart')->merge(['data-trend' => $trend, 'data-nx-reveal' => '']) }} x-data x-nx-reveal viewBox="0 0 120 40" preserveAspectRatio="none" aria-hidden="true">
    @if ($area)<path class="nx-chart-area" d="{{ $paths['area'] }}"/>@endif
    <path class="nx-chart-line" d="{{ $paths['line'] }}" pathLength="1"/>
    <circle class="nx-chart-point" cx="{{ $paths['end'][0] }}" cy="{{ $paths['end'][1] }}" r="3" data-end/>
</svg>
