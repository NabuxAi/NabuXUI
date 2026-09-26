@props(['min' => null, 'gap' => null, 'stagger' => true])
<div {{ $attributes->class('nx-grid')->merge(['data-nx-reveal' => $stagger ? 'group' : null, 'style' => trim(($min ? "--nx-grid-min: {$min};" : '').($gap ? " --nx-grid-gap: {$gap};" : '')) ?: null]) }} @if ($stagger) x-data x-nx-reveal.group @endif>{{ $slot }}</div>
