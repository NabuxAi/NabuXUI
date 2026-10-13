{{--
    <div style="position: relative"> <x-nx::infinite-grid>
        <h2 class="nx-text-display">Built on a grid that never stops</h2>
        <p>Move the pointer: the dots it touches light up.</p>
    </x-nx::infinite-grid> </div>

    An infinite drifting dot field with a pointer spotlight (core spotlight()
    writes --nx-px/--nx-py) and a density stepper in the corner that changes the
    cell size. Put it inside a positioned container, like <x-nx::backdrop>.
--}}
@props(['cell' => 28, 'min' => 12, 'max' => 48, 'step' => 4])
<div {{ $attributes->class('nx-infinite-grid') }}
    style="--_cell: {{ (int) $cell }}px"
    x-data="nxInfiniteGrid(@js((int) $cell), @js((int) $min), @js((int) $max), @js((int) $step))"
    x-ref="root"
    x-bind:style="'--_cell: ' + cell + 'px'"
    wire:ignore.self
>
    <div class="nx-infinite-grid-field" aria-hidden="true"></div>
    <div class="nx-infinite-grid-glow" aria-hidden="true"></div>
    <div class="nx-infinite-grid-veil" aria-hidden="true"></div>
    <div class="nx-infinite-grid-body">{{ $slot }}</div>
    <div class="nx-infinite-grid-controls" role="group" aria-label="Dot density">
        <button type="button" x-bind:disabled="! canShrink" x-on:click="nudge(-1)" aria-label="Fewer dots">{{ NabuXUI\NabuXUI::icon('minus') }}</button>
        <button type="button" x-bind:disabled="! canGrow" x-on:click="nudge(1)" aria-label="More dots">{{ NabuXUI\NabuXUI::icon('plus') }}</button>
    </div>
</div>
