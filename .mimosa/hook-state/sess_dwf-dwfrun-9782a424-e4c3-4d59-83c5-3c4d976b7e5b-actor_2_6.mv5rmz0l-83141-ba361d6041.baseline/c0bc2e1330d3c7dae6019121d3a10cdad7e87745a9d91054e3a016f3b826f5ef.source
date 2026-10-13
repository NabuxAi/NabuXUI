{{--
    <section style="position: relative; isolation: isolate"> <x-nx::dither-backdrop /> … </section>

    An animated ordered-dither field in the theme's colours, painted on a canvas by the
    core dither() (Alpine: nxDither). It pauses off-screen and in hidden tabs and holds
    one still frame under reduced motion. cell: CSS px per dither cell · matrix: 4 | 8 ·
    speed · fps. Put it first inside a positioned section, like <x-nx::backdrop>.
--}}
@props(['cell' => null, 'matrix' => null, 'speed' => null, 'fps' => null])
@php
    $options = array_map(
        fn ($value) => is_numeric($value) ? $value + 0 : $value,
        array_filter(['cell' => $cell, 'matrix' => $matrix, 'speed' => $speed, 'fps' => $fps], fn ($value) => $value !== null),
    );
@endphp
<div {{ $attributes->class('nx-backdrop')->merge(['data-variant' => 'dither']) }} aria-hidden="true" x-data="nxDither(@js((object) $options))" wire:ignore><canvas class="nx-backdrop-canvas" x-ref="canvas"></canvas></div>
