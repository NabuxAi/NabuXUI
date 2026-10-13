{{--
    <x-nx::resizable-pane :size="30" :min="20" :max="60" collapsible>…</x-nx::resizable-pane>

    One pane of <x-nx::resizable-panels>. `size` is the starting size in
    percent (panes without one share the rest); `min` / `max` limit it;
    `collapsible` lets it fold to `collapsed-size` (default 0).
--}}
@props(['size' => null, 'min' => null, 'max' => null, 'collapsible' => false, 'collapsedSize' => null])
<div {{ $attributes->class('nx-resizable-pane')->merge([
    'data-size' => $size,
    'data-min' => $min,
    'data-max' => $max,
    'data-collapsible' => $collapsible ? '' : null,
    'data-collapsed-size' => $collapsedSize,
    'style' => $size !== null ? 'flex: '.((float) $size).' 1 0px' : null,
]) }} wire:ignore.self>{{ $slot }}</div>
