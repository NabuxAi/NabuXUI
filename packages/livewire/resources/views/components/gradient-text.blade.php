@props(['as' => 'span', 'variant' => 'text', 'animate' => false])
<{{ $as }} {{ $attributes->class('nx-gradient-text')->merge(['data-variant' => $variant === 'text' ? null : $variant, 'data-animate' => $animate ? '' : null]) }}>{{ $slot }}</{{ $as }}>
