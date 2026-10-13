{{--
    <x-nx::pixel-loader />                                                  a 5×5 diagonal wave
    <x-nx::pixel-loader variant="center" rows="7" cols="7" size="sm" />    a ripple from the middle
    <x-nx::pixel-loader variant="chaos" rows="3" cols="24" label="Loading orders" />   a table row

    CSS-only: each cell carries its column, row, distance from the centre and two
    fixed pseudo-random numbers (the same integer noise as the core's pixelNoise).
    `decorative` drops the status role and label, for use inside another control.
--}}
@props([
    'rows' => 5,
    'cols' => 5,
    'variant' => 'wave',
    'size' => 'md',
    'label' => null,
    'decorative' => false,
])
@php
    $rows = max(1, (int) $rows);
    $cols = max(1, (int) $cols);
    $cx = ($cols - 1) / 2;
    $cy = ($rows - 1) / 2;
    $far = hypot($cx, $cy) ?: 1;
    $noise = function (int $index, int $salt): float {
        $q = ($index * 7919 + $salt * 3571 + 17) % 10007;

        return round((($q * $q) % 10007) / 10007, 3);
    };
@endphp
<span {{ $attributes->class('nx-pixel-loader')->merge([
    'role' => $decorative ? null : 'status',
    'aria-hidden' => $decorative ? 'true' : null,
    'data-variant' => $variant,
    'data-size' => $size === 'md' ? null : $size,
    'style' => "--nx-rows: {$rows}; --nx-cols: {$cols}",
]) }}>
    <span class="nx-pixel-loader-grid" aria-hidden="true">
        @for ($y = 0; $y < $rows; $y++)
            @for ($x = 0; $x < $cols; $x++)
                <i style="--_pl-x: {{ $x }}; --_pl-y: {{ $y }}; --_pl-d: {{ round(hypot($x - $cx, $y - $cy) / $far, 3) }}; --_pl-r: {{ $noise($y * $cols + $x, 1) }}; --_pl-s: {{ $noise($y * $cols + $x, 2) }}"></i>
            @endfor
        @endfor
    </span>
    @unless ($decorative)
        <span class="nx-visually-hidden">{{ $label ?? __('nabuxui::ui.loading') }}</span>
    @endunless
</span>
