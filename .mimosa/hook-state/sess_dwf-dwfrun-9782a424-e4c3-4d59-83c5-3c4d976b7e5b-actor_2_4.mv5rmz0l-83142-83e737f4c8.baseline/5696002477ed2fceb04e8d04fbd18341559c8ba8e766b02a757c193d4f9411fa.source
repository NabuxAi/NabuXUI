{{-- Optimistic like with a burst. Pair with wire:click to persist it. --}}
@props(['liked' => false, 'count' => null, 'variant' => 'ghost', 'label' => null])
<button type="button" {{ $attributes->class('nx-button nx-like')->merge(['data-variant' => $variant, 'aria-label' => $label ?? __('nabuxui::ui.like')]) }}
    x-data="nxLike(@js((bool) $liked), @js($count), @js(app()->getLocale()))" x-bind:aria-pressed="liked ? 'true' : 'false'" aria-pressed="{{ $liked ? 'true' : 'false' }}" @click="toggle()">
    <span class="nx-button-label">
        {{ \NabuXUI\NabuXUI::icon('heart', 'nx-like-heart') }}
        @if ($count !== null)<span class="nx-button-text"><x-nx::number :value="$count + ($liked ? 1 : 0)" :reveal="false" /></span>@endif
    </span>
</button>
