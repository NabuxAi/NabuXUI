{{--
    <x-nx::backdrop-starfield class="…"> …content… </x-nx::backdrop-starfield>

    A section that carries its own parallax starfield: three box-shadow star
    layers falling behind the content at their own speeds — pure CSS, no
    Alpine. The layers are decorative (aria-hidden, pointer-transparent) and
    sit under the slot; they stop under prefers-reduced-motion. Style the sky
    with --_tile (the loop length) and --nx-backdrop-intensity.
--}}
<div {{ $attributes->class('nx-backdrop-starfield') }}>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    <span class="nx-backdrop-layer" aria-hidden="true"></span>
    {{ $slot }}
</div>
