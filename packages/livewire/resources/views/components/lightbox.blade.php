{{--
    <x-nx::lightbox :images="[['src' => '/a.jpg', 'thumb' => '/a-sm.jpg', 'alt' => 'Pier at dawn', 'caption' => 'Bandar Abbas, 6:12'], …]" :columns="3" />

    A thumbnail grid whose image grows into a full-screen viewer (a View
    Transition where supported, a FLIP otherwise): wheel / pinch / double-tap
    zoom, drag to pan, swipe for next and previous (or down to close), arrow
    keys, captions and a counter — inside a native <dialog>.
--}}
@props([
    'images' => [],
    'columns' => 3,
    'ratio' => '4 / 3',
    'label' => 'Gallery',
    'zoomInLabel' => 'Zoom in',
    'zoomOutLabel' => 'Zoom out',
    'openLabel' => '{alt}, image {index} of {total}',
])
@php
    use NabuXUI\NabuXUI;
    $images = array_values(array_map(fn ($image) => [
        'src' => (string) ($image['src'] ?? ''),
        'thumb' => (string) ($image['thumb'] ?? $image['src'] ?? ''),
        'alt' => (string) ($image['alt'] ?? ''),
        'caption' => (string) ($image['caption'] ?? ''),
    ], (array) $images));
    $total = count($images);
    $first = $images[0] ?? ['src' => '', 'alt' => '', 'caption' => ''];
@endphp
<div {{ $attributes->class('nx-lightbox-gallery') }} x-data="nxLightbox(@js($images))" wire:ignore>
    <ul class="nx-lightbox-grid" style="--_cols: {{ (int) $columns }}">
        @foreach ($images as $i => $image)
            <li>
                <button type="button" class="nx-lightbox-thumb" style="--_ratio: {{ $ratio }}" aria-haspopup="dialog" x-on:click="open({{ $i }})"
                    aria-label="{{ strtr($openLabel, ['{alt}' => $image['alt'], '{index}' => NabuXUI::formatNumber($i + 1), '{total}' => NabuXUI::formatNumber($total)]) }}">
                    <img src="{{ $image['thumb'] }}" alt="" loading="lazy" decoding="async" draggable="false">
                </button>
            </li>
        @endforeach
    </ul>

    <dialog class="nx-lightbox" x-ref="dialog" aria-label="{{ $label }}" x-on:cancel.prevent="close()" x-on:keydown="key($event)">
        <div class="nx-lightbox-bar">
            <span class="nx-lightbox-counter" x-text="num(index + 1) + ' / ' + num(images.length)">{{ NabuXUI::formatNumber(1) }} / {{ NabuXUI::formatNumber($total) }}</span>
            <div class="nx-lightbox-tools">
                <button type="button" class="nx-lightbox-tool" aria-label="{{ $zoomOutLabel }}" x-bind:disabled="! zoomed" disabled x-on:click="zoomBy(1 / 1.5)">{{ NabuXUI::icon('minus') }}</button>
                <button type="button" class="nx-lightbox-tool" aria-label="{{ $zoomInLabel }}" x-on:click="zoomBy(1.5)">{{ NabuXUI::icon('plus') }}</button>
                <button type="button" class="nx-lightbox-tool" aria-label="{{ __('nabuxui::ui.close') }}" x-on:click="close()">{{ NabuXUI::icon('x') }}</button>
            </div>
        </div>
        <div class="nx-lightbox-stage" x-ref="stage">
            <img class="nx-lightbox-image" x-ref="hero" src="{{ $first['src'] }}" alt="{{ $first['alt'] }}" draggable="false"
                x-bind:src="current()?.src" x-bind:alt="current()?.alt">
        </div>
        @if ($total > 1)
            <button type="button" class="nx-lightbox-nav" data-dir="prev" aria-label="{{ __('nabuxui::ui.previous') }}" x-on:click="prev()">{{ NabuXUI::icon('chevron-left') }}</button>
            <button type="button" class="nx-lightbox-nav" data-dir="next" aria-label="{{ __('nabuxui::ui.next') }}" x-on:click="next()">{{ NabuXUI::icon('chevron-right') }}</button>
        @endif
        <p class="nx-lightbox-caption" aria-live="polite" x-text="current()?.caption || ''">{{ $first['caption'] }}</p>
    </dialog>
</div>
