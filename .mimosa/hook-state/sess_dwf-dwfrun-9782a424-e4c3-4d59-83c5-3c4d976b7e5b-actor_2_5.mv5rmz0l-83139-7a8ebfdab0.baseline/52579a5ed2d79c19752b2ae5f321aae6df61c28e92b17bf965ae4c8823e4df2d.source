{{--
    Material's progress family: the determinate linear bar with the 4 px gap
    and stop-indicator dot, the indeterminate two-segment bar, the spinning
    dashed ring, and the M3 Expressive waves — a wavy determinate bar driven
    by the same play/pause clock and a counter-rotating wavy ring. Every
    specimen has a working play/pause control.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3prg-root {
        --m3prg-primary: #6750A4; --m3prg-on-primary: #FFFFFF;
        --m3prg-primary-container: #EADDFF; --m3prg-on-primary-container: #21005D;
        --m3prg-secondary-container: #E8DEF8; --m3prg-on-secondary-container: #1D192B;
        --m3prg-surface: #FEF7FF; --m3prg-surface-container: #F3EDF7;
        --m3prg-surface-container-high: #ECE6F0; --m3prg-surface-container-highest: #E6E0E9;
        --m3prg-on-surface: #1D1B20; --m3prg-on-surface-variant: #49454F;
        --m3prg-outline-variant: #CAC4D0;
        --m3prg-ease: cubic-bezier(.2, 0, 0, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3prg-root {
        --m3prg-primary: #D0BCFF; --m3prg-on-primary: #381E72;
        --m3prg-primary-container: #4F378B; --m3prg-on-primary-container: #EADDFF;
        --m3prg-secondary-container: #4A4458; --m3prg-on-secondary-container: #E8DEF8;
        --m3prg-surface: #141218; --m3prg-surface-container: #211F26;
        --m3prg-surface-container-high: #2B2930; --m3prg-surface-container-highest: #36343B;
        --m3prg-on-surface: #E6E0E9; --m3prg-on-surface-variant: #CAC4D0;
        --m3prg-outline-variant: #49454F;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3prg-root {
            --m3prg-primary: #D0BCFF; --m3prg-on-primary: #381E72;
            --m3prg-primary-container: #4F378B; --m3prg-on-primary-container: #EADDFF;
            --m3prg-secondary-container: #4A4458; --m3prg-on-secondary-container: #E8DEF8;
            --m3prg-surface: #141218; --m3prg-surface-container: #211F26;
            --m3prg-surface-container-high: #2B2930; --m3prg-surface-container-highest: #36343B;
            --m3prg-on-surface: #E6E0E9; --m3prg-on-surface-variant: #CAC4D0;
            --m3prg-outline-variant: #49454F;
        }
    }
    .m3prg-card { inline-size: min(100%, 24rem); display: grid; gap: 1rem; padding: 1.25rem 1.35rem 1.35rem; border-radius: 1.75rem; background: var(--m3prg-surface-container); }
    .m3prg-card-head { display: flex; align-items: center; gap: .75rem; }
    .m3prg-card-head b { font: 500 .95rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3prg-on-surface); }
    .m3prg-card-head small { display: block; font: 400 .76rem/1.4 Roboto, system-ui, sans-serif; color: var(--m3prg-on-surface-variant); }
    .m3prg-card-head output { margin-inline-start: auto; font: 500 1.05rem/1 Roboto, system-ui, sans-serif; color: var(--m3prg-primary); font-variant-numeric: tabular-nums; }
    .m3prg-track { display: flex; align-items: center; }
    .m3prg-way { position: relative; flex: 1; block-size: .25rem; border-radius: 999px; background: var(--m3prg-surface-container-highest); }
    .m3prg-fill { position: absolute; inset-block: 0; inset-inline-start: 0; border-radius: 999px; background: var(--m3prg-primary); transition: inline-size .12s linear; }
    .m3prg-stopdot { flex: none; inline-size: .25rem; aspect-ratio: 1; margin-inline-start: .25rem; border-radius: 50%; background: var(--m3prg-primary); }
    .m3prg-wavy { position: relative; block-size: 1rem; }
    .m3prg-wavy-way, .m3prg-wavy-fill { position: absolute; inset-block: 0; mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='16'%3E%3Cpath d='M0 8Q5 2 10 8T20 8' fill='none' stroke='%23000' stroke-width='2.6' stroke-linecap='round'/%3E%3C/svg%3E") 0 0 / 20px 16px repeat-x; -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='16'%3E%3Cpath d='M0 8Q5 2 10 8T20 8' fill='none' stroke='%23000' stroke-width='2.6' stroke-linecap='round'/%3E%3C/svg%3E") 0 0 / 20px 16px repeat-x; animation: m3prg-flow .9s linear infinite; }
    .m3prg-wavy-way { inset-inline: 0; background: var(--m3prg-surface-container-highest); }
    .m3prg-wavy-fill { inset-inline-start: 0; inline-size: 0%; background: var(--m3prg-primary); transition: inline-size .12s linear; }
    @keyframes m3prg-flow { to { mask-position: -20px 0; -webkit-mask-position: -20px 0; } }
    .m3prg-ctrls { display: flex; flex-wrap: wrap; gap: .5rem; }
    .m3prg-btn { position: relative; display: inline-flex; align-items: center; gap: .45rem; block-size: 2.25rem; padding-inline: 1rem; border: 1px solid var(--m3prg-outline-variant); border-radius: 999px; background: var(--m3prg-surface); color: var(--m3prg-primary); cursor: pointer; font: 500 .8rem/1 Roboto, system-ui, sans-serif; }
    .m3prg-btn::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: currentColor; opacity: 0; transition: opacity .15s; pointer-events: none; }
    .m3prg-btn:hover::before { opacity: .08; }
    .m3prg-btn:active::before { opacity: .12; }
    .m3prg-btn:focus-visible { outline: 2px solid var(--m3prg-primary); outline-offset: 2px; }
    .m3prg-spec { display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; align-items: flex-start; }
    .m3prg-cell { display: grid; gap: .75rem; justify-items: center; inline-size: min(100%, 15rem); }
    .m3prg-cell small { font: 500 .72rem/1.4 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); text-align: center; }
    .m3prg-cell[data-paused='true'] .m3prg-ind i, .m3prg-cell[data-paused='true'] .m3prg-ring, .m3prg-cell[data-paused='true'] .m3prg-wring svg { animation-play-state: paused; }
    .m3prg-ind { position: relative; overflow: clip; inline-size: min(100%, 13rem); block-size: .25rem; border-radius: 999px; background: var(--m3prg-surface-container-highest); }
    .m3prg-ind i { position: absolute; inset-block: 0; border-radius: 999px; background: var(--m3prg-primary); }
    .m3prg-ind i:first-child { animation: m3prg-ind1 1.8s cubic-bezier(.65, 0, .35, 1) infinite; }
    .m3prg-ind i:last-child { animation: m3prg-ind2 1.8s cubic-bezier(.65, 0, .35, 1) infinite; }
    @keyframes m3prg-ind1 { 0% { inset-inline-start: 0%; inline-size: 0%; } 50% { inset-inline-start: 28%; inline-size: 46%; } 100% { inset-inline-start: 100%; inline-size: 0%; } }
    @keyframes m3prg-ind2 { 0% { inset-inline-start: 0%; inline-size: 0%; } 60% { inset-inline-start: 62%; inline-size: 38%; } 100% { inset-inline-start: 100%; inline-size: 0%; } }
    .m3prg-ring { inline-size: 3rem; aspect-ratio: 1; rotate: 0deg; animation: m3prg-rot 1.4s linear infinite; }
    .m3prg-ring circle { fill: none; stroke: var(--m3prg-primary); stroke-width: 4; stroke-linecap: round; animation: m3prg-dash 1.4s ease-in-out infinite; }
    @keyframes m3prg-rot { to { rotate: 360deg; } }
    @keyframes m3prg-dash { 0% { stroke-dasharray: 6px 119.7px; stroke-dashoffset: 0; } 50% { stroke-dasharray: 90px 35.7px; stroke-dashoffset: -24px; } 100% { stroke-dasharray: 90px 35.7px; stroke-dashoffset: -114px; } }
    .m3prg-wring { position: relative; inline-size: 3rem; aspect-ratio: 1; color: var(--m3prg-primary); }
    .m3prg-wring svg { position: absolute; inset: 0; animation: m3prg-rot 1.5s linear infinite; }
    .m3prg-wring svg:last-child { animation: m3prg-rot-rev 2.1s linear infinite; color: color-mix(in srgb, var(--m3prg-primary) 45%, var(--m3prg-surface-container-highest)); }
    @keyframes m3prg-rot-rev { to { rotate: -360deg; } }
    .m3prg-note { margin: 0; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); max-width: 56ch; }
    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3prg-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3prg-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column reads as cut off; let this page's table and snippet
       wrap so nothing is read as cut off. */
    @media (max-width: 480px) {
        .pg:has(.m3prg-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.m3prg-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3prg-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        .m3prg-ind i, .m3prg-ring, .m3prg-ring circle, .m3prg-wring svg { animation: none !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Syncing, visibly', 'هم‌گام‌سازی، دیده‌شدنی') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Play the sync and both determinate bars advance on one clock — the plain bar with its 4 px gap and stop dot, and the expressive wave that undulates even while it fills.', 'هم‌گام‌سازی را پخش کنید و هر دو نوار معین با یک ساعت پیش می‌روند — نوار ساده با گپ ۴ پیکسلی و نقطهٔ توقفش، و موجِ اکسپرسیو که حتی حین پُر شدن هم موج می‌زند.') }}
        </p>
    </div>

    <div class="m3prg-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            v: 0, run: false, timer: null,
            fd(n) { return this.fa ? String(n).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(n) },
            play() { this.run = true; clearInterval(this.timer); this.timer = setInterval(() => { this.v += 2; if (this.v >= 100) { this.v = 100; this.stop(); } }, 90); },
            stop() { this.run = false; clearInterval(this.timer); },
            reset() { this.stop(); this.v = 0; },
        }"
        x-on:pointercancel.window="stop()">
        <div class="m3prg-card">
            <div class="m3prg-card-head">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="var(--m3prg-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg>
                <span>
                    <b>{{ $say('Syncing your library', 'هم‌گام‌سازی کتابخانه') }}</b>
                    <small>{{ $say('1,142 tracks · Nabu cloud', '۱۱۴۲ قطعه · ابر نابو') }}</small>
                </span>
                <output x-text="fd(v) + '٪'" aria-hidden="true">۰٪</output>
            </div>

            <div class="m3prg-track" role="progressbar" x-bind:aria-valuenow="v" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $say('Sync progress', 'پیشرفت هم‌گام‌سازی') }}">
                <span class="m3prg-way"><span class="m3prg-fill" x-bind:style="{ inlineSize: v + '%' }" style="inline-size: 0%"></span></span>
                <span class="m3prg-stopdot" aria-hidden="true"></span>
            </div>

            <div class="m3prg-wavy" role="presentation">
                <span class="m3prg-wavy-way" aria-hidden="true"></span>
                <span class="m3prg-wavy-fill" aria-hidden="true" x-bind:style="{ inlineSize: v + '%' }" style="inline-size: 0%"></span>
            </div>

            <div class="m3prg-ctrls">
                <button type="button" class="m3prg-btn" x-on:click="run ? stop() : play()" x-bind:aria-pressed="run.toString()">
                    <svg x-show="!run" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5z"/></svg>
                    <svg x-show="run" x-cloak width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 5h3.5v14H7zM13.5 5H17v14h-3.5z"/></svg>
                    {{ $say('Play', 'پخش') }} / {{ $say('Pause', 'توقف') }}
                </button>
                <button type="button" class="m3prg-btn" x-on:click="reset()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12a8 8 0 1 0 2.3-5.6"/><path d="M4 4v4h4"/></svg>
                    {{ $say('Reset', 'از نو') }}
                </button>
            </div>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The spinners', 'چرخنده‌ها') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Empty value means indeterminate: two segments chase each other on the bar, the ring spins and swells its arc, and the wavy ring turns one way and then the other. Pause each at will.', 'مقدار خالی یعنی نامعین: دو پاره روی نوار یکدیگر را تعقیب می‌کنند، حلقه می‌چرخد و کمانش را می‌ساند، و حلقهٔ موجی یک‌سو و سپس سوی دیگر می‌گردد. هرکدام را بخواهید متوقف کنید.') }}
        </p>
    </div>

    <div class="m3prg-root" style="inline-size: 100%">
        <div class="m3prg-spec">
            <div class="m3prg-cell" x-data="{ run: true }" x-bind:data-paused="(!run).toString()">
                <div class="m3prg-ind" role="progressbar" aria-label="{{ $say('Loading, indeterminate', 'بارگیری نامعین') }}"><i></i><i></i></div>
                <button type="button" class="m3prg-btn" x-on:click="run = !run" x-bind:aria-pressed="run.toString()">{{ $say('Pause', 'توقف') }} / {{ $say('Play', 'پخش') }}</button>
                <small>linear · indeterminate</small>
            </div>
            <div class="m3prg-cell" x-data="{ run: true }" x-bind:data-paused="(!run).toString()">
                <svg class="m3prg-ring" viewBox="0 0 48 48" role="progressbar" aria-label="{{ $say('Loading ring', 'حلقهٔ بارگیری') }}">
                    <circle cx="24" cy="24" r="20"/>
                </svg>
                <button type="button" class="m3prg-btn" x-on:click="run = !run" x-bind:aria-pressed="run.toString()">{{ $say('Pause', 'توقف') }} / {{ $say('Play', 'پخش') }}</button>
                <small>circular · indeterminate</small>
            </div>
            <div class="m3prg-cell" x-data="{ run: true }" x-bind:data-paused="(!run).toString()">
                <div class="m3prg-wring" role="progressbar" aria-label="{{ $say('Wavy ring', 'حلقهٔ موجی') }}" aria-hidden="false">
                    <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="19" fill="none" stroke="currentColor" stroke-width="3.6" stroke-linecap="round" stroke-dasharray="3 8.4"/></svg>
                    <svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="13.5" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-dasharray="4 7"/></svg>
                </div>
                <button type="button" class="m3prg-btn" x-on:click="run = !run" x-bind:aria-pressed="run.toString()">{{ $say('Pause', 'توقف') }} / {{ $say('Play', 'پخش') }}</button>
                <small>wavy circular · expressive</small>
            </div>
        </div>
        <p class="m3prg-note">{{ $say('The stop dot is the 2025 signature: the track never quite touches the end — 4 px of respect for what is left.', 'نقطهٔ توقف امضای ۲۰۲۵ است: ریل هرگز تا انتها نمی‌رسد — ۴ پیکسل احترام به باقی‌مانده.') }}</p>
    </div>
</section>
