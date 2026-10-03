{{--
    A voice assistant bar cycling through its states (tap a state, or let it run), and
    the orbs where they usually live: a chat input's status line and a sidebar row.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $states = [
        'idle' => $say('Idle', 'بیکار'),
        'listening' => $say('Listening', 'در حال شنیدن'),
        'thinking' => $say('Thinking', 'در حال فکر'),
        'speaking' => $say('Speaking', 'در حال صحبت'),
    ];
@endphp
<style>
    .agd-voice { display: flex; align-items: center; gap: 1rem; max-inline-size: 30rem; padding: .875rem 1.125rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-full); background: var(--nx-surface); box-shadow: var(--nx-shadow-md); }
    .agd-voice-text { display: grid; gap: .125rem; flex: 1; min-inline-size: 0; }
    .agd-voice-text strong { font: 650 var(--nx-text-sm) / 1.3 var(--nx-font-sans); }
    .agd-voice-text span { color: var(--nx-text-subtle); font-size: var(--nx-text-xs); }
    .agd-sizes { display: flex; align-items: center; gap: 2rem; flex-wrap: wrap; }
    .agd-sizes figure { display: grid; justify-items: center; gap: .625rem; margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-xs); }
</style>

<div style="display: grid; gap: 1.5rem"
    x-data="{
        order: @js(array_keys($states)),
        words: @js($states),
        current: 'listening',
        auto: true,
        timer: null,
        init() { this.timer = setInterval(() => { if (this.auto) this.current = this.order[(this.order.indexOf(this.current) + 1) % this.order.length] }, 2600) },
        destroy() { clearInterval(this.timer) },
        pick(state) { this.auto = false; this.current = state },
    }">
    <div class="agd-voice">
        <x-nx::thinking-orbs size="lg" state="listening" x-bind:data-state="current" />
        <div class="agd-voice-text" aria-live="polite">
            <strong x-text="words[current]">{{ $states['listening'] }}</strong>
            <span>{{ $say('Nabu voice · Persian / English', 'صدای نابو · فارسی / انگلیسی') }}</span>
        </div>
        <x-nx::icon-button icon="mic" :label="$say('Microphone', 'میکروفون')" x-on:click="pick(current === 'listening' ? 'thinking' : 'listening')" />
    </div>

    <div class="pg-row" role="group" aria-label="{{ $say('State', 'حالت') }}">
        @foreach ($states as $key => $word)
            <x-nx::button size="sm" variant="ghost" x-on:click="pick('{{ $key }}')" x-bind:aria-pressed="current === '{{ $key }}' ? 'true' : 'false'">{{ $word }}</x-nx::button>
        @endforeach
    </div>

    <div class="agd-sizes">
        @foreach ($states as $key => $word)
            <figure>
                <div class="pg-row" style="gap: 1rem">
                    <x-nx::thinking-orbs :state="$key" size="sm" :label="$word" />
                    <x-nx::thinking-orbs :state="$key" :label="$word" />
                    <x-nx::thinking-orbs :state="$key" size="lg" :label="$word" />
                </div>
                <figcaption>{{ $word }}</figcaption>
            </figure>
        @endforeach
    </div>

    <p class="pg-row" style="gap: .5rem; margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
        <x-nx::thinking-orbs state="thinking" size="sm" :label="$states['thinking']" />
        {{ $say('Nabu is reading 3 files…', 'نابو در حال خواندن ۳ فایل است…') }}
    </p>
</div>
