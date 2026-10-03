{{--
    <x-nx::border-beam radius="var(--nx-radius-xl)" tone="gold" :duration="8">
        <x-nx::card>…</x-nx::card>
    </x-nx::border-beam>

    A glowing light segment travelling around its border. Wraps any element (or
    put the `nx-beam` class on one directly). Paused while off screen; a still,
    even glow under reduced motion. `size` is the beam's length in degrees,
    `duration` seconds per lap, `width` the ring in px, `delay` an offset in
    seconds for several beams on one page.
--}}
@props([
    'size' => null,
    'duration' => null,
    'width' => null,
    'tone' => 'accent',
    'colorFrom' => null,
    'colorTo' => null,
    'reverse' => false,
    'delay' => null,
    'radius' => null,
    'alwaysRun' => false,
    'as' => 'div',
])
@php
    $tag = in_array($as, ['div', 'span', 'section', 'article', 'li'], true) ? $as : 'div';
    $vars = collect([
        '--nx-beam-size' => $size !== null ? ((float) $size).'deg' : null,
        '--nx-beam-duration' => $duration !== null ? ((float) $duration).'s' : null,
        '--nx-beam-width' => $width !== null ? ((float) $width).'px' : null,
        '--nx-beam-delay' => $delay !== null ? (-abs((float) $delay)).'s' : null,
        '--nx-beam-from' => $colorFrom,
        '--nx-beam-to' => $colorTo,
        'border-radius' => $radius,
    ])->filter(fn ($v) => $v !== null && $v !== '')->map(fn ($v, $k) => "{$k}: {$v}")->implode('; ');
@endphp
<{{ $tag }} {{ $attributes->class('nx-beam')->merge([
    'data-tone' => $tone && $tone !== 'accent' ? $tone : null,
    'data-reverse' => $reverse ? '' : null,
    'style' => $vars ?: null,
]) }} x-data="nxBorderBeam(@js(! $alwaysRun))" wire:ignore.self>{{ $slot }}</{{ $tag }}>
