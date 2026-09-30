{{--
    Metal button's real scenarios: the voice bar of a chat app (the round mic
    toggle plus a listening pill), then the same toggle bound to Livewire so
    the server hears every press.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A voice bar in chat', 'نوار صدا در چت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Press the mic: the rim turns iridescent and the icon becomes live wave bars — it is a real aria-pressed toggle, not a visual trick.', 'میکروفن را فشار دهید: rim رنگین‌کمانی می‌شود و آیکون به میله‌های موجِ زنده بدل می‌شود — یک تاگل واقعیِ aria-pressed است، نه ترفند بصری.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: 1rem 1.25rem">
        <x-nx::metal-button :label="$say('Record a voice message', 'ضبط پیام صوتی')" wire:click="ping(@js($say('Recording is local to the browser', 'ضبط محلی مرورگر است')))" />
        <div class="pg-row">
            <x-nx::metal-button :label="$say('Listen', 'گوش دادن')" icon="play" shape="pill" wire:click="ping(@js($say('Playing the last message', 'در حال پخش آخرین پیام')))" />
            <x-nx::metal-button :label="$say('Mute', 'بی‌صدا')" icon="bell" active-icon="x" size="sm" />
        </div>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Bound to Livewire', 'متصل به Livewire') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('wire:model.live keeps the server in step, and every press dispatches nx-change too.', 'با wire:model.live سرور هم‌قدم می‌ماند و هر فشار رویداد nx-change را هم پخش می‌کند.') }}
        </p>
        <div class="pg-row">
            <x-nx::metal-button :label="$say('Voice input', 'ورودی صدا')" wire:model.live="state.mic" :pressed="(bool) ($state['mic'] ?? false)" />
            <span class="nx-badge" data-tone="{{ ($state['mic'] ?? false) ? 'success' : 'neutral' }}">
                {{ ($state['mic'] ?? false) ? $say('listening', 'در حال شنیدن') : $say('idle', 'ساکت') }}
            </span>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pinned on', 'سنجاق‌شده') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('A pill can ship already pressed — the "on air" state of a room, held for the session.', 'یک pill می‌تواند از قبل فشرده برسد — حالت «آن‌آن» یک اتاق، برای کل نشست نگه داشته می‌شود.') }}
        </p>
        <div class="pg-row">
            <x-nx::metal-button :label="$say('On air', 'آن‌آن')" icon="mic" shape="pill" :pressed="true" size="lg" />
            <x-nx::metal-button :label="$say('Round, small', 'گرد و کوچک')" size="sm" wire:click="ping(@js($say('Toggled', 'زدوده شد')))" />
        </div>
    </section>
</div>
