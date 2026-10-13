{{--
    <x-nx::backdrop-aurora class="py-24"> …content… </x-nx::backdrop-aurora>

    The block is the section: three drifting aurora curtains of lapis, violet
    and cyan (pure CSS — css/blocks/backdrops.css) behind the slot's content.
    No props of its own: the slot is the content, and extra classes (and
    wire:* attributes) merge onto the container through the attribute bag.
--}}
<div {{ $attributes->class('nx-backdrop-aurora') }}>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    {{ $slot }}
</div>
