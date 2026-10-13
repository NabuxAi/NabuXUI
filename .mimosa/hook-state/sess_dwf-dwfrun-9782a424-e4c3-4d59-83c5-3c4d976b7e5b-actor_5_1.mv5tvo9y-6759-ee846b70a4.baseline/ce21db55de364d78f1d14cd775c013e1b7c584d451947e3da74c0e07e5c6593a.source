{{--
    <x-nx::backdrop-mesh class="py-24"> …content… </x-nx::backdrop-mesh>

    The block is the section: a living mesh gradient — five blurred colour
    fields (lapis, violet, cyan, gold and a violet-cyan weave) drifting,
    scaling and blending, soft-light on light pages and screen on dark, behind
    the slot's content (pure CSS — css/blocks/backdrops.css). Mesh paints five
    layers, not the frame's default three. No props of its own: the slot is
    the content, and extra classes (and wire:* attributes) merge onto the
    container through the attribute bag.
--}}
<div {{ $attributes->class('nx-backdrop-mesh') }}>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    {{ $slot }}
</div>
