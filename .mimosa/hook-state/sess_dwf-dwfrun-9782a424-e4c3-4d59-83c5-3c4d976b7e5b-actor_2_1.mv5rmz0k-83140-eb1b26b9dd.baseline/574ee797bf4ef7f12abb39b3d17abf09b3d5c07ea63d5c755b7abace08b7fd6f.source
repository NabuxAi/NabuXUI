{{--
    <x-nx::island-view name="call" size="expanded">…</x-nx::island-view>

    One view of <x-nx::dynamic-island>. size: compact (default) | expanded.
--}}
@props(['name', 'size' => 'compact'])
<div {{ $attributes->class('nx-island-view')->merge(['data-view' => $name, 'data-size' => $size]) }} wire:ignore.self>{{ $slot }}</div>
