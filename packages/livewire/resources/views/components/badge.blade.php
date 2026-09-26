@props(['tone' => 'neutral', 'variant' => null, 'size' => null, 'dot' => false, 'pulse' => false])
<span {{ $attributes->class('nx-badge')->merge([
    'data-tone' => $tone === 'neutral' ? null : $tone,
    'data-variant' => $variant && $variant !== 'soft' ? $variant : null,
    'data-size' => $size === 'lg' ? 'lg' : null,
    'data-dot' => $dot || $pulse ? '' : null,
    'data-pulse' => $pulse ? '' : null,
]) }}>{{ $slot }}</span>
