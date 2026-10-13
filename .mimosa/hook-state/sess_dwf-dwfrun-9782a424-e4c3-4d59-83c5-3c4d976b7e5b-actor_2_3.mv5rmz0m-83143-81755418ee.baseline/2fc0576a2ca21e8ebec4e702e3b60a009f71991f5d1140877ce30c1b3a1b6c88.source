@props(['index' => 0])
<article {{ $attributes->class('nx-swipe-card') }} data-card="{{ $index }}"
    x-show="depth({{ $index }}) !== -1 && depth({{ $index }}) < 4"
    x-bind:style="`--i: ${depth({{ $index }})}`"
    x-bind:data-state="leaving === {{ $index }} ? 'leaving' : (depth({{ $index }}) === 0 ? 'top' : null)"
    x-bind:aria-hidden="depth({{ $index }}) === 0 ? null : 'true'" style="--i: {{ $index }}">
    {{ $slot }}
    <span class="nx-swipe-hint" data-side="accept" aria-hidden="true" x-show="depth({{ $index }}) === 0">{{ __('nabuxui::ui.accept') }}</span>
    <span class="nx-swipe-hint" data-side="reject" aria-hidden="true" x-show="depth({{ $index }}) === 0">{{ __('nabuxui::ui.reject') }}</span>
</article>
