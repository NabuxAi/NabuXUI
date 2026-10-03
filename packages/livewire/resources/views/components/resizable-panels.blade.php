{{--
    <x-nx::resizable-panels storage-key="editor-layout" style="block-size: 28rem">
        <x-nx::resizable-pane :size="22" :min="15" collapsible>…files…</x-nx::resizable-pane>
        <x-nx::resizable-handle label="Resize sidebar" />
        <x-nx::resizable-pane>
            <x-nx::resizable-panels orientation="vertical">…nested…</x-nx::resizable-panels>
        </x-nx::resizable-pane>
    </x-nx::resizable-panels>

    Split panes with draggable separators. Drag a handle, or focus it and use
    the arrows (Shift: bigger steps), Home / End; double-click or Enter folds a
    collapsible neighbour. Right-to-left aware. `storage-key` remembers the
    sizes; every change dispatches `nx-resize` { sizes } (percent).
--}}
@props(['orientation' => 'horizontal', 'storageKey' => null, 'step' => null])
<div {{ $attributes->class('nx-resizable')->merge(['data-orientation' => $orientation === 'vertical' ? 'vertical' : 'horizontal']) }}
    x-data="nxResizablePanels(@js($storageKey), @js($step))" wire:ignore.self>{{ $slot }}</div>
