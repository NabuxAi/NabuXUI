@props(['words' => [], 'interval' => 2600])
@php $words = array_values($words); @endphp
<span {{ $attributes->class('nx-word-rotate') }} x-data="nxWordRotate(@js($words), {{ (int) $interval }})" @pointerenter="paused = true" @pointerleave="paused = false">
    <span class="nx-visually-hidden">{{ implode(', ', $words) }}</span>
    {{-- Shown until Alpine takes over, so the first word is there without JavaScript. --}}
    <span class="nx-word-rotate-word" aria-hidden="true" x-init="$el.remove()">{{ $words[0] ?? '' }}</span>
    <template x-for="i in (previous !== null && previous !== index ? [previous] : [])" :key="'out-' + i">
        <span class="nx-word-rotate-word" data-state="exit" aria-hidden="true" x-text="word(i)" @animationend="previous = null"></span>
    </template>
    <template x-for="i in [index]" :key="'in-' + i">
        <span class="nx-word-rotate-word" aria-hidden="true" x-bind:data-state="previous === null && index === 0 ? null : 'enter'" x-text="word(i)"></span>
    </template>
</span>
