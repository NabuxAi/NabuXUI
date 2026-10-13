@props(['name', 'src' => null, 'size' => null, 'status' => null, 'shape' => null])
@php $initials = mb_strtoupper(implode('', array_map(fn ($p) => mb_substr($p, 0, 1), array_slice(preg_split('/\s+/u', trim($name)), 0, 2)))); @endphp
<span {{ $attributes->class('nx-avatar')->merge([
    'data-size' => $size && $size !== 'md' ? $size : null,
    'data-status' => $status,
    'data-shape' => $shape === 'square' ? 'square' : null,
    'role' => 'img',
    'aria-label' => $status ? "{$name} ({$status})" : $name,
]) }} @if ($src) x-data="{ failed: false }" @endif>
    @if ($src)
        <img class="nx-avatar-image" src="{{ $src }}" alt="" x-show="! failed" x-on:error="failed = true">
        <span aria-hidden="true" x-show="failed" x-cloak>{{ $initials }}</span>
    @else
        <span aria-hidden="true">{{ $initials }}</span>
    @endif
</span>
