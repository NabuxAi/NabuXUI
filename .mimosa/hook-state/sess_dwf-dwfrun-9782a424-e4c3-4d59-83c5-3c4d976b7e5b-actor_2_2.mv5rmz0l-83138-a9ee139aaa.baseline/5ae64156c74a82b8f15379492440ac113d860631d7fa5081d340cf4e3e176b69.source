@props(['shape' => 'text', 'lines' => 1, 'width' => null, 'height' => null])
@php $style = trim(($width ? "inline-size: {$width};" : '').($height ? " block-size: {$height};" : '').($shape === 'circle' && $width ? " --nx-skeleton-size: {$width};" : '')); @endphp
@if ($shape === 'text' && $lines > 1)
    <span style="display: grid; gap: 0.6em">@for ($i = 0; $i < $lines; $i++)<span {{ $attributes->class('nx-skeleton')->merge(['data-shape' => 'text', 'aria-hidden' => 'true', 'style' => $style ?: null]) }}></span>@endfor</span>
@else
    <span {{ $attributes->class('nx-skeleton')->merge(['data-shape' => $shape === 'block' ? null : $shape, 'aria-hidden' => 'true', 'style' => $style ?: null]) }}></span>
@endif
