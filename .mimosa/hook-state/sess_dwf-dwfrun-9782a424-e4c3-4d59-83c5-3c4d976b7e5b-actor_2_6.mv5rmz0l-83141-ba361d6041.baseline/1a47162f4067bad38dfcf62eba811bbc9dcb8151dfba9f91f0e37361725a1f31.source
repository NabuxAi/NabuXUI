{{--
    orbit-dots in its real fields: the save button of a workspace settings
    card that really works for two seconds and then ticks, and a calendar
    sync chip whose first attempt fails — the dots halt dead in red until
    the retry goes through.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ldo-root {
        --ldo-fg: #1c1917; --ldo-muted: #78716c; --ldo-border: #e7e5e4; --ldo-bg: #ffffff;
        --ldo-accent: #4f46e5; --ldo-ok: #059669; --ldo-err: #dc2626;
        display: grid; gap: 1.6rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--ldo-fg);
    }
    html[data-theme="dark"] .ldo-root {
        --ldo-fg: #fafaf9; --ldo-muted: #a8a29e; --ldo-border: #292524; --ldo-bg: #1c1917;
        --ldo-accent: #818cf8; --ldo-ok: #34d399; --ldo-err: #f87171;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ldo-root {
            --ldo-fg: #fafaf9; --ldo-muted: #a8a29e; --ldo-border: #292524; --ldo-bg: #1c1917;
            --ldo-accent: #818cf8; --ldo-ok: #34d399; --ldo-err: #f87171;
        }
    }
    .ldo-card {
        display: grid; gap: 1.1rem; padding: 1.4rem;
        border: 1px solid var(--ldo-border); border-radius: 1.1rem; background: var(--ldo-bg);
    }
    .ldo-card h4 { margin: 0; font-size: .95rem; font-weight: 700; }
    .ldo-card > p { margin: 0; font-size: .8rem; color: var(--ldo-muted); }
    .ldo-rows { display: grid; gap: .55rem; }
    .ldo-row {
        display: flex; flex-wrap: wrap; gap: .3rem 1rem; justify-content: space-between;
        padding: .55rem .8rem; border: 1px solid var(--ldo-border); border-radius: .6rem;
        font-size: .82rem;
    }
    .ldo-row small { color: var(--ldo-muted); }
    .ldo-actions { display: flex; flex-wrap: wrap; align-items: center; gap: .8rem 1rem; }
    .ldo-save {
        display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
        min-inline-size: 11.5rem; block-size: 2.8rem; padding-inline: 1.3rem;
        border: none; border-radius: 999px; background: var(--ldo-accent); color: #fff;
        font: 600 .88rem/1 inherit; cursor: pointer;
        transition: background-color .25s ease, scale .25s ease;
    }
    .ldo-save:hover:not(:disabled) { background: color-mix(in oklab, var(--ldo-accent) 86%, #000); }
    .ldo-save:focus-visible { outline: 2px solid var(--ldo-accent); outline-offset: 2px; }
    .ldo-save:disabled { cursor: default; }
    .ldo-save[data-state='done'] { background: var(--ldo-ok); }
    .ldo-save[data-state='done']:hover:not(:disabled) { background: var(--ldo-ok); }
    .ldo-orbit { position: relative; inline-size: 22px; block-size: 14px; flex: none; }
    .ldo-orbit i {
        position: absolute; inset: 0;
        offset-path: path('M2 7 A9 5.5 0 1 1 20 7 A9 5.5 0 1 1 2 7');
        animation: ldo-lap 1.15s cubic-bezier(.62,.05,.37,.95) infinite;
    }
    .ldo-orbit i::after {
        content: ''; position: absolute; inset-block-start: 50%; inset-inline-start: 50%;
        inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: currentColor;
        translate: -50% -50%;
    }
    .ldo-orbit i:nth-child(2) { animation-delay: -.16s; } .ldo-orbit i:nth-child(2)::after { opacity: .7; scale: .8; }
    .ldo-orbit i:nth-child(3) { animation-delay: -.32s; } .ldo-orbit i:nth-child(3)::after { opacity: .5; scale: .6; }
    .ldo-orbit i:nth-child(4) { animation-delay: -.48s; } .ldo-orbit i:nth-child(4)::after { opacity: .35; scale: .45; }
    .ldo-check { inline-size: 1.15rem; block-size: 1.15rem; flex: none; }
    .ldo-check path {
        fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; stroke-linejoin: round;
        stroke-dasharray: 24; stroke-dashoffset: 24; animation: ldo-draw .45s ease-out forwards;
    }
    .ldo-note { font-size: .76rem; color: var(--ldo-muted); }
    .ldo-chip {
        display: inline-flex; align-items: center; gap: .55rem; padding: .55rem 1rem;
        border: 1px solid var(--ldo-border); border-radius: 999px; background: transparent;
        color: inherit; font: 600 .8rem/1 inherit; cursor: pointer; justify-self: start;
        transition: border-color .2s ease, color .2s ease;
    }
    .ldo-chip:hover { border-color: var(--ldo-accent); color: var(--ldo-accent); }
    .ldo-chip:focus-visible { outline: 2px solid var(--ldo-accent); outline-offset: 2px; }
    .ldo-chip[data-state='working'] { border-color: var(--ldo-accent); color: var(--ldo-accent); cursor: default; }
    .ldo-chip[data-state='error'] { border-color: var(--ldo-err); color: var(--ldo-err); }
    .ldo-chip[data-state='done'] { border-color: var(--ldo-ok); color: var(--ldo-ok); cursor: default; }
    .ldo-chip .ldo-orbit { inline-size: 20px; block-size: 13px; scale: .82; }
    .ldo-chip svg { inline-size: 1rem; block-size: 1rem; flex: none; }
    .ldo-chip svg path { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; stroke-linejoin: round; }
    .ldo-fail {
        display: flex; flex-wrap: wrap; align-items: center; gap: .5rem 1rem;
        padding: .7rem .9rem; border-radius: .7rem; font-size: .8rem; color: var(--ldo-err);
        background: color-mix(in oklab, var(--ldo-err) 9%, transparent);
    }
    .ldo-retry {
        margin-inline-start: auto; border: none; background: transparent; color: var(--ldo-err);
        font: 700 .8rem/1 inherit; cursor: pointer; text-decoration: underline; text-underline-offset: .2em;
    }
    .ldo-retry:focus-visible { outline: 2px solid var(--ldo-err); outline-offset: 2px; }
    @keyframes ldo-lap { from { offset-distance: 0%; } to { offset-distance: 100%; } }
    @keyframes ldo-draw { to { stroke-dashoffset: 0; } }
    @media (prefers-reduced-motion: reduce) {
        .ldo-orbit i { animation: none; }
        .ldo-orbit i:nth-child(1) { offset-distance: 0%; }
        .ldo-orbit i:nth-child(2) { offset-distance: 25%; }
        .ldo-orbit i:nth-child(3) { offset-distance: 50%; }
        .ldo-orbit i:nth-child(4) { offset-distance: 75%; }
        .ldo-check path { animation: none; stroke-dashoffset: 0; }
        .ldo-save, .ldo-chip { transition: none; }
    }
    @media (max-width: 480px) {
        .ldo-save { inline-size: 100%; }
        .ldo-retry { margin-inline-start: 0; }
    }
</style>

<div class="ldo-root"
    x-data="{
        saveState: 'idle',
        syncState: 'idle',
        syncTries: 0,
        savedNow: false,
        timers: [],
        later(fn, ms) { this.timers.push(setTimeout(fn, ms)); },
        save() {
            if (this.saveState === 'working') return;
            this.saveState = 'working';
            this.later(() => {
                this.saveState = 'done';
                this.savedNow = true;
                this.later(() => { this.saveState = 'idle'; }, 1800);
            }, 2000);
        },
        sync() {
            if (this.syncState === 'working') return;
            const firstShot = this.syncTries === 0;
            this.syncTries++;
            this.syncState = 'working';
            this.later(() => { this.syncState = firstShot ? 'error' : 'done'; }, firstShot ? 2400 : 1700);
        },
        destroy() { this.timers.forEach((t) => clearTimeout(t)); },
    }">

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A button that shows its work', 'دکمه‌ای که کارش را نشان می‌دهد') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Four dots chase each other round an elliptical orbit — the stagger reads as a comet tail. Press save: the dots really work for two seconds, then a tick draws itself in and the button settles green.', 'چهار نقطه در مداری بیضوی همدیگر را تعقیب می‌کنند — فاصلهٔ افتادگی‌شان مثل دنبالهٔ دنب‌دار خوانده می‌شود. ذخیره را بزنید: نقاط واقعاً ۲ ثانیه کار می‌کنند، بعد تیک درون دکمه ترسیم می‌شود و دکمه سبز می‌نشیند.') }}
            </p>
        </div>

        <div class="ldo-card">
            <h4>{{ $say('Atlas Design · workspace settings', 'Atlas Design · تنظیمات ورک‌اسپیس') }}</h4>
            <p>{{ $say('Frankfurt region · team plan', 'ناحیهٔ فرانکفورت · پلن تیمی') }}</p>
            <div class="ldo-rows">
                <div class="ldo-row">
                    <small>{{ $say('Workspace name', 'نام ورک‌اسپیس') }}</small>
                    <span>Atlas Design</span>
                </div>
                <div class="ldo-row">
                    <small>{{ $say('Host region', 'ناحیهٔ میزبان') }}</small>
                    <span>{{ $say('Frankfurt · eu-central-1', 'فرانکفورت · eu-central-1') }}</span>
                </div>
                <div class="ldo-row">
                    <small>{{ $say('Plan', 'پلن') }}</small>
                    <span>{{ $say('Team · 12 seats', 'تیمی · ۱۲ صندلی') }}</span>
                </div>
            </div>
            <div class="ldo-actions">
                <button type="button" class="ldo-save"
                    x-bind:data-state="saveState" x-bind:disabled="saveState === 'working'" x-on:click="save()">
                    <template x-if="saveState === 'working'">
                        <span class="ldo-orbit" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
                    </template>
                    <template x-if="saveState === 'done'">
                        <svg class="ldo-check" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                    </template>
                    <span aria-live="polite" x-text="saveState === 'working'
                        ? '{{ $say('Saving…', 'در حال ذخیره…') }}'
                        : saveState === 'done' ? '{{ $say('Saved', 'ذخیره شد') }}' : '{{ $say('Save changes', 'ذخیرهٔ تغییرات') }}'"></span>
                </button>
                <span class="ldo-note" x-text="savedNow
                    ? '{{ $say('Saved just now', 'همین حالا ذخیره شد') }}'
                    : '{{ $say('Last save · 2 minutes ago', 'آخرین ذخیره · ۲ دقیقه پیش') }}'"></span>
            </div>
        </div>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('When the server goes quiet', 'وقتی سرور پاسخ نمی‌دهد') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The same orbit, shrunk into a sync chip. The first attempt here fails with a 504 — the dots stop dead and the chip reads the error out loud; Retry walks the same path and goes green.', 'همان مدار، کوچک‌شده داخل تراشهٔ همگام‌سازی. تلاش نخست این‌جا با خطای ۵۰۴ می‌شکند — نقاط می‌ایستند و تراشه خطا را با صدای بلند می‌خواند؛ «تلاش دوباره» همان مسیر را می‌رود و سبز می‌شود.') }}
            </p>
        </div>

        <div class="ldo-card">
            <h4>{{ $say('Vector Calendar · sync', 'Vector Calendar · همگام‌سازی') }}</h4>
            <button type="button" class="ldo-chip"
                x-bind:data-state="syncState" x-on:click="syncState !== 'working' && sync()"
                x-bind:disabled="syncState === 'working' || syncState === 'done'">
                <template x-if="syncState === 'working'">
                    <span class="ldo-orbit" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
                </template>
                <template x-if="syncState === 'error'">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </template>
                <template x-if="syncState === 'done'">
                    <svg class="ldo-check" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                </template>
                <span aria-live="polite" x-text="syncState === 'working'
                    ? '{{ $say('Syncing…', 'در حال همگام‌سازی…') }}'
                    : syncState === 'error' ? '{{ $say('Connection failed', 'اتصال برقرار نشد') }}'
                    : syncState === 'done' ? '{{ $say('Synced · 48 events', 'همگام شد · ۴۸ رویداد') }}'
                    : '{{ $say('Sync with Google', 'همگام‌سازی با Google') }}'"></span>
            </button>
            <div class="ldo-fail" x-show="syncState === 'error'" x-cloak>
                <span>{{ $say('Server did not answer (504). Check the connection and try again.', 'سرور پاسخ نداد (۵۰۴). اتصال را بررسی کنید و دوباره تلاش کنید.') }}</span>
                <button type="button" class="ldo-retry" x-on:click="sync()">{{ $say('Retry', 'تلاش دوباره') }}</button>
            </div>
        </div>
    </section>
</div>
