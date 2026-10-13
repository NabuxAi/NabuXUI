{{--
    <x-nx::backdrop-dither class="py-24"> …content… </x-nx::backdrop-dither>

    The block is the section: a wave rolling through a field of tiny lapis and
    violet dots over a soft pool of accent light (pure CSS —
    css/blocks/backdrops.css) behind the slot's content. Dot pitch:
    style="--nx-dither-cell: 12px" on the container. Still, no wave, under
    reduced motion. For the canvas-painted ordered dither keep using
    <x-nx::dither-backdrop />. No props of its own: the slot is the content,
    and extra classes (and wire:* attributes) merge onto the container through
    the attribute bag.
--}}
<div {{ $attributes->class('nx-backdrop-dither') }}>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    {{ $slot }}
</div>
