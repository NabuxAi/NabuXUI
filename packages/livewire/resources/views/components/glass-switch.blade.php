{{--
    A switch whose knob clears to glass while held. <x-nx::glass-switch label="Refraction" wire:model.live="refraction" />
    Every other attribute (wire:model, name, checked, disabled) goes on the native checkbox.
--}}
@props(['label' => null])
<label @class(['nx-glass-switch', $attributes->get('class')]) x-data x-nx-glass-switch>
    <span class="nx-glass-switch-control">
        <span class="nx-glass-switch-track"><span class="nx-glass-switch-fill"></span></span>
        <span class="nx-lens nx-glass-switch-thumb" aria-hidden="true"></span>
        <input type="checkbox" role="switch" {{ $attributes->except('class')->class('nx-glass-switch-input') }}>
    </span>
    @if ($label)<span class="nx-glass-switch-label">{{ $label }}</span>@endif
</label>
