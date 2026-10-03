{{--
    <x-nx::streaming-text :text="$answer" :streaming="$isStreaming" :sources="[
        ['title' => 'Tehran — Wikipedia', 'url' => 'https://en.wikipedia.org/wiki/Tehran', 'snippet' => '…'],
    ]" />

    Text with [n] markers; each becomes a button that opens its source (title, domain,
    snippet, link) in a popover. New tokens fade and un-blur in — by word, never by
    letter, so Persian keeps its joins. Grow `text` from Livewire (each render appends)
    with `streaming` on; `simulate` plays the full text out client-side (demos, replays —
    call play() or dispatch nx-replay on the element).
--}}
@props(['text' => '', 'streaming' => false, 'sources' => [], 'simulate' => false, 'interval' => 55, 'citeLabel' => 'Source :n', 'openLabel' => 'Open source'])
@php
    use NabuXUI\NabuXUI;

    $id = NabuXUI::id('nx-stream');
    $text = (string) $text;
    $sources = array_map(fn ($source) => [
        'title' => (string) ($source['title'] ?? ''),
        'url' => isset($source['url']) ? (string) $source['url'] : null,
        'domain' => isset($source['domain']) ? (string) $source['domain'] : null,
        'snippet' => isset($source['snippet']) ? (string) $source['snippet'] : null,
    ], (array) $sources);
    // Arrays are 1-based for [n]; keyed arrays stay keyed by n.
    $sources = array_is_list($sources) ? array_values($sources) : $sources;
    $live = $streaming || $simulate;
@endphp
<div {{ $attributes->class('nx-stream')->merge([
        'data-text' => $text,
        'data-streaming-in' => $streaming ? '1' : '0',
        'data-streaming' => $live ? '' : null,
        'dir' => NabuXUI::direction($text),
    ]) }}
    x-data="nxStreamingText(@js(['sources' => array_is_list($sources) ? $sources : (object) $sources, 'simulate' => (bool) $simulate, 'interval' => (int) $interval, 'cite' => $citeLabel]))"
    x-bind:data-streaming="streaming ? '' : null" x-on:nx-replay="play()">
    <p class="nx-stream-body" x-ref="body" wire:ignore x-on:click="pick($event)">@if (! $simulate)<span class="nx-stream-ssr" x-init="$el.remove()">{{ preg_replace('/\[\d*$/', '', $text) }}</span>@endif<span class="nx-stream-caret" aria-hidden="true"></span></p>
    <div x-ref="source" id="{{ $id }}-source" class="nx-popover nx-stream-source" popover="auto" role="dialog" wire:ignore
        x-on:toggle="$event.newState === 'closed' && closed()">
        <span class="nx-stream-source-domain">{{ NabuXUI::icon('globe') }}<span x-ref="domain"></span></span>
        <p class="nx-stream-source-title" dir="auto" x-ref="title"></p>
        <p class="nx-stream-source-snippet" dir="auto" x-ref="snippet"></p>
        <a class="nx-stream-source-link" x-ref="link" href="#" target="_blank" rel="noopener noreferrer" hidden>{{ $openLabel }}</a>
    </div>
</div>
