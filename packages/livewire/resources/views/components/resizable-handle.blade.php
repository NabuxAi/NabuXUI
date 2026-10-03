{{--
    <x-nx::resizable-handle label="Resize sidebar" />

    The separator between two panes of <x-nx::resizable-panels>: a focusable
    role="separator" whose aria-value* follow the pane before it.
--}}
@props(['label' => 'Resize'])
<div {{ $attributes->class('nx-resizable-handle')->merge(['role' => 'separator', 'tabindex' => '0', 'aria-label' => $label]) }} wire:ignore.self></div>
