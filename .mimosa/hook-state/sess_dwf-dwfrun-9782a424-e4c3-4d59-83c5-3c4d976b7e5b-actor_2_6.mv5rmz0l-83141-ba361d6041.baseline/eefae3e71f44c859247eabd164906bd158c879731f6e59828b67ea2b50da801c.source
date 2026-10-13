{{-- Reveal on scroll. variant: rise | fade | scale | blur | start | end; group staggers the children. --}}
@props(['as' => 'div', 'variant' => 'rise', 'group' => false, 'repeat' => false, 'delay' => null])
<{{ $as }} {{ $attributes->merge(['data-nx-reveal' => $group ? 'group' : $variant, 'style' => $delay ? "--nx-delay: {$delay}ms" : null]) }}
    x-data x-nx-reveal{{ $group ? '.group' : '' }}{{ $repeat ? '.repeat' : '' }}>{{ $slot }}</{{ $as }}>
