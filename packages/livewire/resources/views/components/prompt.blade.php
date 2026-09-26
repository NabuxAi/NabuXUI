{{--
    <x-nx::prompt wire:submit="ask" wire:model="message" streaming-property="streaming" @nx-stop="$wire.stop()" />
    Enter sends, Shift+Enter breaks the line; while `streaming` is true the send button stops instead.
--}}
@props(['placeholder' => null, 'streaming' => false, 'streamingProperty' => null, 'glow' => true, 'maxRows' => 12])
@php
    $form = $attributes->filter(fn ($v, $k) => str_starts_with($k, 'wire:submit') || str_starts_with($k, 'x-on:') || str_starts_with($k, '@') || in_array($k, ['class', 'style', 'action', 'method']));
    $area = $attributes->filter(fn ($v, $k) => ! $form->has($k));
@endphp
<form {{ $form->class('nx-prompt')->merge(['data-glow' => $glow ? '' : null]) }}
    @if ($streamingProperty) x-data="nxPrompt(@entangle($streamingProperty).live)" @else x-data="nxPrompt(@js((bool) $streaming))" @endif>
    @isset($attachments)<div class="nx-prompt-attachments">{{ $attachments }}</div>@endisset
    <textarea x-ref="area" rows="1" {{ $area->class('nx-prompt-textarea')->merge([
        'placeholder' => $placeholder,
        'aria-label' => $placeholder,
        'style' => "--nx-prompt-max: {$maxRows}lh",
    ]) }} @keydown="key($event)" x-nx-autogrow></textarea>
    <div class="nx-prompt-toolbar">
        {{ $toolbar ?? '' }}
        <span class="nx-prompt-spacer"></span>
        <button type="submit" class="nx-prompt-send"
            x-bind:data-state="streaming ? 'streaming' : (ready ? 'ready' : null)"
            x-bind:disabled="!streaming && !ready"
            x-bind:aria-label="streaming ? @js(__('nabuxui::ui.stop')) : @js(__('nabuxui::ui.send'))"
            @click="if (streaming) { $event.preventDefault(); $dispatch('nx-stop') }">
            {{ \NabuXUI\NabuXUI::icon('arrow-up', 'nx-prompt-arrow') }}
            {{ \NabuXUI\NabuXUI::icon('stop', 'nx-prompt-stop') }}
        </button>
    </div>
</form>
