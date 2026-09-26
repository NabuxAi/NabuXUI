{{-- <x-nx::popover label="Details"> <x-slot:trigger><x-nx::button>Info</x-nx::button></x-slot:trigger> … </x-nx::popover> --}}
@props(['side' => 'bottom', 'align' => 'center', 'label' => null])
@php $id = \NabuXUI\NabuXUI::id('nx-pop'); @endphp
<div style="display: contents" x-data="nxPopover(@js($side), @js($align))">
    <span x-ref="trigger" style="display: contents">{{ $trigger }}</span>
    <div x-ref="panel" id="{{ $id }}" {{ $attributes->class('nx-popover')->merge(['role' => 'dialog', 'aria-label' => $label, 'popover' => 'auto']) }}>{{ $slot }}</div>
</div>
