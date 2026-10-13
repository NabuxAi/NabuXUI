@props(['value', 'variant' => 'ghost', 'size' => 'md'])
<button type="button" {{ $attributes->class('nx-button nx-copy')->merge(['data-variant' => $variant, 'data-size' => $size === 'md' ? null : $size, 'data-icon-only' => trim((string) $slot) === '' ? '' : null]) }}
    x-data="nxCopy(@js($value))" x-bind:data-copied="copied ? '' : null" @click="copy()"
    @if (trim((string) $slot) === '') aria-label="{{ __('nabuxui::ui.copy') }}" @endif>
    <span class="nx-button-label">
        <span class="nx-copy-icons" aria-hidden="true">
            {{ \NabuXUI\NabuXUI::icon('copy', 'nx-copy-idle') }}
            {{ \NabuXUI\NabuXUI::icon('check', 'nx-copy-done') }}
        </span>
        @if (trim((string) $slot) !== '')<span class="nx-button-text">{{ $slot }}</span>@endif
        <span class="nx-visually-hidden" aria-live="polite" x-text="copied ? @js(__('nabuxui::ui.copied')) : ''"></span>
    </span>
</button>
