{{--
    Fluent progress in two cards: the 4px determinate bar showing a real
    percent (replayable from a demo button), the indeterminate bar with five
    staggered slides, the famous indeterminate ProgressRing whose five dots
    breathe apart on an orbit, and InfoBadges — a dot and a count that clamps
    at ۹+ — pinned to two buttons.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .flprg-root {
        --fl-accent: #005FB8;
        --fl-accent-hover: #1A75C5;
        --fl-on-accent: #FFFFFF;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
        --fl-control: rgba(255, 255, 255, .70);
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-control-stroke: rgba(0, 0, 0, .12);
        --fl-control-edge: rgba(0, 0, 0, .22);
        --fl-divider: rgba(0, 0, 0, .08);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-press: rgba(0, 0, 0, .06);
        --fl-critical: #C42B1C;
        --fl-focus: #1B1B1B;
        --fl-badge-ink: #FFFFFF;
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        gap: .75rem;
        /* This root is a grid item of .pg-box; keep min-inline-size at 0 so the
           demo grid's intrinsic two-track width can never push it past the stage. */
        min-inline-size: 0;
    }
    html[data-theme="dark"] .flprg-root {
        --fl-accent: #4CC2FF;
        --fl-accent-hover: #6BCFFF;
        --fl-on-accent: #000000;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-layer: rgba(255, 255, 255, .05);
        --fl-control: rgba(255, 255, 255, .06);
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-control-stroke: rgba(255, 255, 255, .10);
        --fl-control-edge: rgba(255, 255, 255, .18);
        --fl-divider: rgba(255, 255, 255, .06);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-press: rgba(255, 255, 255, .03);
        --fl-critical: #FF99A4;
        --fl-focus: #FFFFFF;
        --fl-badge-ink: #000000;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flprg-root {
            --fl-accent: #4CC2FF;
            --fl-accent-hover: #6BCFFF;
            --fl-on-accent: #000000;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-layer: rgba(255, 255, 255, .05);
            --fl-control: rgba(255, 255, 255, .06);
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-control-stroke: rgba(255, 255, 255, .10);
            --fl-control-edge: rgba(255, 255, 255, .18);
            --fl-divider: rgba(255, 255, 255, .06);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-press: rgba(255, 255, 255, .03);
            --fl-critical: #FF99A4;
            --fl-focus: #FFFFFF;
            --fl-badge-ink: #000000;
        }
    }
    .flprg-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .flprg-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }

    /* min() inside minmax: at card-stage widths a bare 15rem minimum makes the
       auto-fit grid keep two tracks (~31rem intrinsic) even when its container
       is one track wide, and the second card leaves the page at phone widths. */
    .flprg-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(15rem, 100%), 1fr)); gap: 1rem; max-inline-size: 34rem; margin-inline: auto; inline-size: 100%; }
    .flprg-card {
        padding: 1rem 1.25rem 1.25rem; border-radius: 8px; background: var(--fl-window);
        box-shadow: 0 24px 48px rgba(0, 0, 0, .14), 0 0 0 1px var(--fl-card-stroke);
        display: grid; gap: .8rem; align-content: start;
    }
    .flprg-card h4 { margin: 0; font-size: .9rem; }
    .flprg-card p { margin: -.5rem 0 0; font-size: .7rem; line-height: 1.8; color: var(--fl-text-2); }

    .flprg-bar { position: relative; block-size: 4px; border-radius: 999px; background: var(--fl-divider); overflow: clip; }
    .flprg-bar > i { position: absolute; inset-block: 0; inset-inline-start: 0; border-radius: 999px; background: var(--fl-accent); transition: inline-size .12s linear; }
    .flprg-ind { position: relative; block-size: 4px; border-radius: 999px; background: var(--fl-divider); overflow: clip; }
    .flprg-ind i { position: absolute; inset-block: 0; inline-size: 28%; border-radius: 999px; background: var(--fl-accent); animation: flprg-run 2.1s cubic-bezier(.4, 0, .6, 1) infinite; animation-delay: calc(var(--i) * -.35s); }
    @keyframes flprg-run {
        0% { inset-inline-start: -30%; }
        60% { inset-inline-start: 102%; }
        100% { inset-inline-start: 102%; }
    }

    .flprg-row { display: flex; align-items: center; gap: .75rem; }
    .flprg-row > :first-child { flex: 1; min-inline-size: 0; }
    .flprg-pct { flex: none; min-inline-size: 2.4rem; text-align: end; font-size: .75rem; color: var(--fl-text-2); font-variant-numeric: tabular-nums; }
    .flprg-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: .4rem;
        block-size: 1.9rem; padding-inline: .7rem; border-radius: 4px; font-size: .75rem;
        background: var(--fl-control);
        box-shadow: inset 0 0 0 1px var(--fl-control-stroke), inset 0 -1px 0 var(--fl-control-edge);
        transition: background .12s, color .12s;
    }
    .flprg-btn:hover { background: color-mix(in srgb, var(--fl-hover) 100%, var(--fl-control)); }
    .flprg-btn:active { background: var(--fl-control); color: var(--fl-text-2); box-shadow: inset 0 0 0 1px var(--fl-control-stroke), inset 0 -1px 0 var(--fl-control-edge), inset 0 0 0 100px var(--fl-press); }
    .flprg-btn:disabled { opacity: .45; cursor: default; }

    .flprg-ring { flex: none; }
    .flprg-ring circle { fill: var(--fl-accent); }
    .flprg-orb { transform-box: view-box; transform-origin: 50% 50%; animation: flprg-spin var(--flprg-dur, 2.2s) cubic-bezier(.42, 0, .58, 1) infinite; animation-delay: calc(var(--i) * -.14s); }
    .flprg-orb:nth-of-type(1) { --flprg-dur: 2.2s; }
    .flprg-orb:nth-of-type(2) { --flprg-dur: 2.32s; }
    .flprg-orb:nth-of-type(3) { --flprg-dur: 2.44s; }
    .flprg-orb:nth-of-type(4) { --flprg-dur: 2.56s; }
    .flprg-orb:nth-of-type(5) { --flprg-dur: 2.68s; }
    @keyframes flprg-spin { to { transform: rotate(360deg); } }

    .flprg-badges { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .flprg-badgebtn {
        position: relative; display: grid; place-items: center; inline-size: 2.5rem; block-size: 2.5rem;
        border-radius: 4px; background: var(--fl-control); font-size: 1.05rem;
        box-shadow: inset 0 0 0 1px var(--fl-control-stroke), inset 0 -1px 0 var(--fl-control-edge);
        transition: background .12s;
    }
    .flprg-badgebtn:hover { background: color-mix(in srgb, var(--fl-hover) 100%, var(--fl-control)); }
    .flprg-badge {
        position: absolute; inset-block-start: -7px; inset-inline-end: -7px;
        min-inline-size: 1.05rem; block-size: 1.05rem; padding-inline: 3px;
        display: grid; place-items: center; border-radius: 999px;
        background: var(--fl-critical); color: var(--fl-badge-ink);
        font-size: .62rem; font-weight: 700; line-height: 1;
        box-shadow: 0 0 0 2px var(--fl-window);
    }
    .flprg-badge[data-dot="true"] { min-inline-size: .55rem; block-size: .55rem; padding: 0; inset-block-start: -3px; inset-inline-end: -3px; }
    .flprg-hint { margin: 0; text-align: center; font-size: .72rem; color: var(--fl-text-2); }

    @media (prefers-reduced-motion: reduce) {
        .flprg-ind i, .flprg-orb { animation: none; opacity: .6; }
        .flprg-bar > i, .flprg-btn { transition: none; }
    }

    /* The shared «Important props» table this page renders below the stage
       reveals its rows on scroll: tbody rows sit at opacity: 0 until an
       IntersectionObserver stamps [data-nx-revealed] on the table, and once
       .nx-live is set the 2.5s CSS failsafe is off — so a full-page capture,
       which never scrolls, sees a header with an empty body. Keep this page's
       rows visible; scoped through :has(.flprg-root) so it never reaches
       another demo page. */
    :where(.nx-js) .pg:has(.flprg-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the last header column is read as cut off; let this page's cells wrap. */
    @media (max-width: 480px) {
        .pg:has(.flprg-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Waiting, made visible', 'انتظار، اما دیدنی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The determinate bar counts a real percent, the indeterminate one slides five staggered strips, the ring scatters its five dots with springy spacing, and the badges clamp at ۹+.', 'نوار معین درصد واقعی می‌شمارد، نوار نامعین پنج نوارِ پلکانی می‌لغزاند، حلقه پنج نقطه‌اش را با فاصلهٔ فنری می‌کارد و نشان‌ها در ۹+ مهار می‌شوند.') }}
        </p>
    </div>

    <div class="flprg-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            pct: 62, running: false, count: 3, runT: null,
            toFa(n) { const s = String(n); return this.fa ? s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : s },
            start() {
                if (this.running) return;
                this.running = true; this.pct = 0;
                this.runT = setInterval(() => {
                    this.pct = Math.min(100, this.pct + 3);
                    if (this.pct >= 100) { clearInterval(this.runT); this.running = false }
                }, 50);
            },
            bump() { this.count = this.count >= 15 ? 3 : this.count + 1 },
        }" x-on:keydown.escape.window="clearInterval(runT); running = false">
        <div class="flprg-grid">
            <div class="flprg-card">
                <h4>{{ $say('Determinate bar', 'نوار معین') }}</h4>
                <p>{{ $say('A real percent of a real download.', 'درصد واقعی از یک بارگیری واقعی.') }}</p>
                <div class="flprg-row">
                    <div class="flprg-bar" role="progressbar" aria-label="{{ $say('Download progress', 'پیشرفت بارگیری') }}"
                        aria-valuemin="0" aria-valuemax="100" :aria-valuenow="pct">
                        <i :style="'inline-size:' + pct + '%'"></i>
                    </div>
                    <span class="flprg-pct" x-text="toFa(pct) + (fa ? '٪' : '%')">۶۲٪</span>
                </div>
                <div class="flprg-row" style="justify-content: flex-end">
                    <button type="button" class="flprg-btn" :disabled="running" x-on:click="start()">{{ $say('Replay download', 'اجرای دوبارهٔ بارگیری') }}</button>
                </div>

                <h4 style="margin-block-start: .3rem">{{ $say('Indeterminate bar', 'نوار نامعین') }}</h4>
                <p>{{ $say('Five staggered slides for work with no known length.', 'پنج لغزش پلکانی برای کارهایی که طولشان معلوم نیست.') }}</p>
                <div class="flprg-ind" role="progressbar" aria-label="{{ $say('Work in progress', 'کار در جریان است') }}">
                    <i style="--i: 0"></i><i style="--i: 1"></i><i style="--i: 2"></i><i style="--i: 3"></i><i style="--i: 4"></i>
                </div>
            </div>

            <div class="flprg-card">
                <h4>{{ $say('Indeterminate ring', 'حلقهٔ نامعین') }}</h4>
                <p>{{ $say('Five dots on an orbit; their different periods make the spacing breathe.', 'پنج نقطه روی یک مدار؛ دوره‌های متفاوت‌شان فاصله‌ها را نفس‌دار می‌کند.') }}</p>
                <div class="flprg-row">
                    <svg class="flprg-ring" viewBox="0 0 40 40" width="40" height="40" role="img"
                        aria-label="{{ $say('Working — indeterminate ring', 'در حال انجام — حلقهٔ نامعین') }}">
                        <g class="flprg-orb" style="--i: 0"><circle cx="20" cy="4.5" r="3.1"/></g>
                        <g class="flprg-orb" style="--i: 1"><circle cx="20" cy="4.5" r="3.1"/></g>
                        <g class="flprg-orb" style="--i: 2"><circle cx="20" cy="4.5" r="3.1"/></g>
                        <g class="flprg-orb" style="--i: 3"><circle cx="20" cy="4.5" r="3.1"/></g>
                        <g class="flprg-orb" style="--i: 4"><circle cx="20" cy="4.5" r="3.1"/></g>
                    </svg>
                    <span style="font-size: .75rem; color: var(--fl-text-2)" x-text="fa ? 'در حال آماده‌سازی…' : 'Preparing…'">{{ $say('Preparing…', 'در حال آماده‌سازی…') }}</span>
                </div>

                <h4 style="margin-block-start: .3rem">{{ $say('Info badges', 'نشان‌های اطلاع') }}</h4>
                <p>{{ $say('A dot for «something new», a count for «this many» — clamped at ۹+.', 'نقطه برای «چیز تازه‌ای هست»، عدد برای «این‌همه» — با مهار در ۹+.') }}</p>
                <div class="flprg-badges">
                    <button type="button" class="flprg-badgebtn" :aria-label="fa ? 'ایمیل — یکی خوانده‌نشده' : 'Mail — one unread'">
                        <span aria-hidden="true">✉️</span>
                        <span class="flprg-badge" data-dot="true" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="flprg-badgebtn" :aria-label="(fa ? 'اعلان‌ها: ' : 'Notifications: ') + (count > 9 ? '۹+' : toFa(count))">
                        <span aria-hidden="true">🔔</span>
                        <span class="flprg-badge" x-text="count > 9 ? (fa ? '۹+' : '9+') : toFa(count)">۳</span>
                    </button>
                    <button type="button" class="flprg-btn" x-on:click="bump()">{{ $say('Add a notification', 'یک اعلان اضافه کن') }}</button>
                </div>
            </div>
        </div>
        <p class="flprg-hint">
            {{ $say('Replay the download, or stack notifications until the badge clamps.', 'بارگیری را دوباره اجرا کنید یا اعلان‌ها را تا مهارشدن نشان روی هم بگذارید.') }}
        </p>
    </div>
</section>
