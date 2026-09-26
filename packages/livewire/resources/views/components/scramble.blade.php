{{-- Glyphs cycle before settling. trigger: view | mount | hover · charset: latin | persian | cuneiform | any string --}}
@props(['as' => 'span', 'trigger' => 'view', 'charset' => null, 'duration' => null])
@php $text = trim((string) $slot); @endphp
<{{ $as }} {{ $attributes->class('nx-scramble') }} x-data="nxScramble(@js($text), @js($trigger), @js($charset), @js($duration))">
    <span class="nx-visually-hidden">{{ $text }}</span>
    <span x-ref="text" aria-hidden="true">{{ $text }}</span>
</{{ $as }}>
