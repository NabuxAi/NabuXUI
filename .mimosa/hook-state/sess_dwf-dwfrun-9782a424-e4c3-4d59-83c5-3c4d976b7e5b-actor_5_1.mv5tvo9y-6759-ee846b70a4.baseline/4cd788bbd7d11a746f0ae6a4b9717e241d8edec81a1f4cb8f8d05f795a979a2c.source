{{--
    <x-nx::terminal title="deploy · production" :lines="[
        ['id' => 1, 'time' => '10:24:01', 'text' => 'Building assets…'],
        ['id' => 2, 'time' => '10:24:03', 'text' => 'WARN: bundle is 512kb', 'level' => 'warn'],
    ]" name="deploy" height="20rem" />

    level: info | warn | error | success | debug — guessed from `ERROR:` / `[warn]` / `✓`
    prefixes when omitted. Follows the end while you are there; scroll up to read and a
    "Jump to latest" button appears. Filter by level with the header chips.
    Feed it from Livewire (re-render `lines`; keep ids stable) or from JS:
    window.dispatchEvent(new CustomEvent('nx-terminal-push', { detail: { to: 'deploy', text: '✓ done' } })).
    `script` plays lines out over time ([['text' => …, 'delay' => 400], …]) — demos and replays.
--}}
@props(['lines' => [], 'title' => null, 'prompt' => '$', 'command' => '', 'filter' => 'all', 'filters' => true, 'height' => null, 'name' => null, 'script' => null, 'labels' => []])
@php
    use NabuXUI\NabuXUI;

    $words = array_merge(['all' => 'All', 'info' => 'Info', 'warn' => 'Warn', 'error' => 'Error', 'jump' => 'Jump to latest', 'empty' => 'No lines', 'log' => 'Output'], is_array($labels) ? $labels : []);
    $filter = in_array($filter, ['all', 'info', 'warn', 'error'], true) ? $filter : 'all';
    $lines = array_values(array_map(fn ($line, $i) => array_filter([
        'id' => (string) ($line['id'] ?? 'l'.$i),
        'text' => (string) ($line['text'] ?? ''),
        'level' => $line['level'] ?? null,
        'time' => isset($line['time']) ? (string) $line['time'] : null,
    ], fn ($value) => $value !== null), (array) $lines, array_keys((array) $lines)));
@endphp
<div {{ $attributes->class('nx-terminal')->merge([
        'dir' => 'ltr',
        'data-lines' => json_encode($lines, JSON_UNESCAPED_UNICODE),
        'style' => $height ? "--nx-terminal-height: {$height}" : null,
    ]) }}
    x-data="nxTerminal(@js(['filter' => $filter, 'name' => $name, 'script' => $script]))"
    x-on:nx-terminal-push.window="fromEvent($event.detail)">
    <div class="nx-terminal-head">
        <span class="nx-terminal-lights" aria-hidden="true"><i></i><i></i><i></i></span>
        @if ($title)
            <span class="nx-terminal-title">{{ $title }}</span>
        @endif
        @if ($filters)
            <span class="nx-terminal-filters" role="group">
                @foreach (['all', 'info', 'warn', 'error'] as $option)
                    <button type="button" class="nx-terminal-filter" data-level="{{ $option }}"
                        aria-pressed="{{ $filter === $option ? 'true' : 'false' }}" x-bind:aria-pressed="filter === @js($option) ? 'true' : 'false'"
                        x-on:click="filter = @js($option)">{{ $words[$option] }}<b x-text="count(@js($option))"></b></button>
                @endforeach
            </span>
        @endif
    </div>
    <div class="nx-terminal-screen" x-ref="screen" role="log" aria-live="polite" aria-label="{{ $title ?? $words['log'] }}" tabindex="0" wire:ignore>
        <div class="nx-terminal-empty" x-show="shown.length === 0" x-cloak>{{ $words['empty'] }}</div>
        <template x-for="line in shown" :key="line.id">
            <div class="nx-terminal-line" x-bind:data-level="line.level" x-bind:data-fresh="line.fresh ? '' : null">
                <span class="nx-terminal-time" x-text="line.time ?? ''"></span>
                <span class="nx-terminal-level" x-text="line.level"></span>
                <span class="nx-terminal-text" x-text="line.text"></span>
            </div>
        </template>
        @if ($prompt !== null)
            <div class="nx-terminal-prompt" aria-hidden="true">
                <span class="nx-terminal-ps">{{ $prompt }}</span>
                <span>{{ $command }}<span class="nx-terminal-caret"></span></span>
            </div>
        @endif
    </div>
    <button type="button" class="nx-terminal-jump" x-bind:data-show="pinned ? null : ''" x-bind:tabindex="pinned ? -1 : 0"
        x-bind:aria-hidden="pinned ? 'true' : null" tabindex="-1" aria-hidden="true" x-on:click="jump()">
        {{ NabuXUI::icon('arrow-up') }}{{ $words['jump'] }}
    </button>
</div>
