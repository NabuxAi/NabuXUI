{{--
    liquid-fill in its real fields: the cloud-migration tank that fills with
    two out-of-phase waves while the percent counts, ending in its ready
    label — and a small sibling backup tank frozen mid-fill at the disk-full
    error until its retry goes through.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ldl-root {
        --ldl-fg: #1c1917; --ldl-muted: #78716c; --ldl-border: #e7e5e4; --ldl-bg: #ffffff;
        --ldl-liquid-a: #2dd4bf; --ldl-liquid-b: #0f766e; --ldl-wave-back: #99f6e4;
        --ldl-ok: #0f766e; --ldl-err: #dc2626;
        display: grid; gap: 1.6rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--ldl-fg);
    }
    html[data-theme="dark"] .ldl-root {
        --ldl-fg: #fafaf9; --ldl-muted: #a8a29e; --ldl-border: #292524; --ldl-bg: #1c1917;
        --ldl-liquid-a: #14b8a6; --ldl-liquid-b: #134e4a; --ldl-wave-back: #0d9488;
        --ldl-ok: #2dd4bf; --ldl-err: #f87171;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ldl-root {
            --ldl-fg: #fafaf9; --ldl-muted: #a8a29e; --ldl-border: #292524; --ldl-bg: #1c1917;
            --ldl-liquid-a: #14b8a6; --ldl-liquid-b: #134e4a; --ldl-wave-back: #0d9488;
            --ldl-ok: #2dd4bf; --ldl-err: #f87171;
        }
    }
    .ldl-card {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: 1.5rem 2rem; padding: 1.5rem; inline-size: min(100%, 34rem);
        border: 1px solid var(--ldl-border); border-radius: 1.1rem; background: var(--ldl-bg);
    }
    .ldl-tank {
        position: relative; display: grid; place-items: center; flex: none;
        inline-size: 10.5rem; aspect-ratio: 1; font: 700 1.6rem/1 inherit;
    }
    .ldl-tank svg { position: absolute; inset: 0; }
    .ldl-tank--mini { inline-size: 6.5rem; font-size: 1rem; }
    .ldl-glass, .ldl-rim { fill: none; }
    .ldl-glass { stroke: color-mix(in oklab, var(--ldl-fg) 12%, transparent); stroke-width: 3; }
    .ldl-rim { stroke: color-mix(in oklab, var(--ldl-fg) 9%, transparent); stroke-width: 1.5; }
    .ldl-level { translate: 0 var(--ldl-level); }
    .ldl-wave { animation: ldl-drift 3.2s linear infinite; }
    .ldl-wave-b { fill: var(--ldl-wave-back); opacity: .5; animation-delay: -1.6s; }
    .ldl-wave-f { fill: url(#ldl-grad-main); }
    .ldl-tank--mini .ldl-wave-f { fill: var(--ldl-liquid-a); }
    .ldl-tank[data-state='error'] .ldl-wave { animation-play-state: paused; }
    .ldl-tank[data-state='error'] {
        --ldl-liquid-a: #9ca3af; --ldl-liquid-b: #6b7280; --ldl-wave-back: #d1d5db;
    }
    .ldl-read { position: relative; display: grid; gap: .4rem; justify-items: center; text-align: center; }
    .ldl-read b { font-variant-numeric: tabular-nums; }
    .ldl-read small { font-size: .6rem; font-weight: 700; color: var(--ldl-muted); max-inline-size: 6.5rem; }
    .ldl-read[data-wet='true'] { color: #fff; text-shadow: 0 1px 4px rgb(0 0 0 / .4); }
    .ldl-read[data-wet='true'] small { color: #fff; }
    .ldl-read small[data-ready='true'] {
        padding: .22rem .6rem; border-radius: 999px;
        background: rgb(255 255 255 / .25);
    }
    .ldl-read .ldl-tick { inline-size: 2rem; block-size: 2rem; }
    .ldl-read .ldl-tick path {
        fill: none; stroke: currentColor; stroke-width: 2.6; stroke-linecap: round; stroke-linejoin: round;
        stroke-dasharray: 26; stroke-dashoffset: 26; animation: ldl-draw .5s ease-out forwards;
    }
    .ldl-body { flex: 1 1 13rem; display: grid; gap: .9rem; justify-items: center; text-align: center; }
    .ldl-body h4 { margin: 0; font-size: .95rem; font-weight: 700; }
    .ldl-rows { display: grid; gap: .45rem; inline-size: 100%; }
    .ldl-rows div { display: flex; flex-wrap: wrap; justify-content: space-between; gap: .3rem 1rem; font-size: .8rem; }
    .ldl-rows small { color: var(--ldl-muted); }
    .ldl-status { font-size: .78rem; color: var(--ldl-muted); min-block-size: 1.1em; }
    .ldl-status[data-tone='ok'] { color: var(--ldl-ok); font-weight: 700; }
    .ldl-status[data-tone='err'] { color: var(--ldl-err); font-weight: 700; }
    .ldl-replay {
        border: none; background: transparent; color: var(--ldl-ok); font: 700 .78rem/1 inherit;
        cursor: pointer; text-decoration: underline; text-underline-offset: .22em;
    }
    .ldl-replay:focus-visible { outline: 2px solid var(--ldl-ok); outline-offset: 2px; }
    .ldl-retry {
        display: inline-flex; align-items: center; block-size: 2.4rem; padding-inline: 1.2rem;
        border: none; border-radius: 999px; background: var(--ldl-err); color: #fff;
        font: 600 .82rem/1 inherit; cursor: pointer; transition: background-color .2s ease;
    }
    .ldl-retry:hover { background: color-mix(in oklab, var(--ldl-err) 84%, #000); }
    .ldl-retry:focus-visible { outline: 2px solid var(--ldl-err); outline-offset: 2px; }
    @keyframes ldl-drift { to { translate: -100px 0; } }
    @keyframes ldl-draw { to { stroke-dashoffset: 0; } }
    @media (prefers-reduced-motion: reduce) {
        .ldl-wave { animation: none; }
        .ldl-read .ldl-tick path { animation: none; stroke-dashoffset: 0; }
        .ldl-retry { transition: none; }
    }
</style>

<div class="ldl-root"
    x-data="{
        fa: @js($fa),
        main: { state: 'idle', p: 0 },
        mini: { state: 'error', p: 34 },
        raf: 0,
        fmt(n, dec) {
            let s = dec !== undefined ? Number(n).toFixed(dec) : String(n);
            if (this.fa) s = s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]).replace(/\./g, '٫');
            return s;
        },
        level(job) { return 200 - (job.p / 100) * 188; },
        fill(job, dur, from) {
            if (job.state === 'working') return;
            job.state = 'working';
            if (from !== undefined) job.p = from;
            const p0 = job.p;
            const t0 = performance.now();
            const tick = (now) => {
                const t = Math.min(1, (now - t0) / dur);
                job.p = p0 + (100 - p0) * (1 - Math.pow(1 - t, 3));
                if (t >= 1) { job.p = 100; job.state = 'done'; return; }
                this.raf = requestAnimationFrame(tick);
            };
            this.raf = requestAnimationFrame(tick);
        },
        init() { this.fill(this.main, 5200, 0); },
        destroy() { cancelAnimationFrame(this.raf); },
    }">

    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A tank that tells you how full you are', 'مخزنی که می‌گوید چقدر پر شده‌ای') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The migration tank of a cloud move: two same-frequency waves run out of phase — the back one paler, half a wavelength behind — while the level climbs and the number counts with it. At the top the number yields to a tick and the ready label rises out of the liquid.', 'مخزن انتقال یک جابه‌جایی ابری: دو موج هم‌فرکانس بی‌ساز می‌روند — عقب‌تر کم‌رنگ‌تر و نصف‌موج عقب‌تر — و سطح با عدد همراهش بالا می‌آید. در سقف، عدد جای‌اش را به تیک می‌دهد و برچسب «آماده شد» از دل مایع بیرون می‌آید.') }}
            </p>
        </div>

        <div class="ldl-card">
            <div class="ldl-tank" x-bind:data-state="main.state"
                 x-bind:style="'--ldl-level: ' + level(main) + 'px'"
                 role="progressbar" x-bind:aria-valuenow="Math.round(main.p)" aria-valuemin="0" aria-valuemax="100"
                 aria-label="{{ $say('Migration progress', 'پیشرفت انتقال') }}">
                <svg viewBox="0 0 200 200" aria-hidden="true">
                    <defs>
                        <clipPath id="ldl-clip-main"><circle cx="100" cy="100" r="88"/></clipPath>
                        <linearGradient id="ldl-grad-main" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" style="stop-color: var(--ldl-liquid-a)"/>
                            <stop offset="1" style="stop-color: var(--ldl-liquid-b)"/>
                        </linearGradient>
                    </defs>
                    <circle class="ldl-glass" cx="100" cy="100" r="88"/>
                    <g clip-path="url(#ldl-clip-main)">
                        <g class="ldl-level">
                            <path class="ldl-wave ldl-wave-b" d="M0 0 Q25 -6 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z"/>
                            <path class="ldl-wave ldl-wave-f" d="M0 0 Q25 -9 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z"/>
                        </g>
                    </g>
                    <circle class="ldl-rim" cx="100" cy="100" r="88"/>
                </svg>
                <div class="ldl-read" x-bind:data-wet="main.p > 53">
                    <template x-if="main.state === 'done'">
                        <svg class="ldl-tick" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                    </template>
                    <template x-if="main.state !== 'done'">
                        <b x-text="fmt(Math.round(main.p)) + (fa ? '٪' : '%')"></b>
                    </template>
                    <small x-bind:data-ready="main.state === 'done'" aria-live="polite" x-text="main.state === 'done'
                        ? '{{ $say('Ready', 'آماده شد') }}'
                        : '{{ $say('Migrating…', 'در حال انتقال…') }}'"></small>
                </div>
            </div>

            <div class="ldl-body">
                <h4>{{ $say('Atlas · cloud migration', 'Atlas · انتقال به فضای ابری') }}</h4>
                <div class="ldl-rows">
                    <div><small>{{ $say('Source', 'منبع') }}</small><span>{{ $say('Shared host · Frankfurt', 'میزبان مشترک · فرانکفورت') }}</span></div>
                    <div><small>{{ $say('Destination', 'مقصد') }}</small><span>{{ $say('S3 bucket · Singapore', 'باکت S3 · سنگاپور') }}</span></div>
                    <div><small>{{ $say('Transferred', 'منتقل‌شده') }}</small><span x-text="fmt(3.8 * main.p / 100, 2) + 'GB / ' + fmt(3.8, 1) + 'GB'"></span></div>
                </div>
                <span class="ldl-status" x-bind:data-tone="main.state === 'done' ? 'ok' : ''" x-text="main.state === 'done'
                    ? '{{ $say('Migration complete · DNS switch scheduled', 'انتقال کامل شد · تعویض DNS زمان‌بندی شد') }}'
                    : '{{ $say('Two waves, one level — nothing drowns, nothing rushes.', 'دو موج، یک سطح — هیچ‌چیز غرق نمی‌شود و هیچ‌چیز شتاب نمی‌کند.') }}'"></span>
                <button type="button" class="ldl-replay" x-show="main.state === 'done'" x-cloak x-on:click="fill(main, 5200, 0)">
                    {{ $say('Migrate again', 'انتقال دوباره') }}
                </button>
            </div>
        </div>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The tank that froze mid-fill', 'مخزنی که وسط پُرشدن یخ زد') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The nightly backup tank stopped at 34%: the destination disk ran full, the waves halted and the liquid turned grey. Retry frees the space and the same tank runs on to green.', 'مخزن پشتیبان‌گیری شبانه روی ۳۴٪ ایستاد: دیسک مقصد پر شد، موج‌ها شکستند و مایع خاکستری شد. «تلاش دوباره» فضا را آزاد می‌کند و همان مخزن تا سبز ادامه می‌دهد.') }}
            </p>
        </div>

        <div class="ldl-card">
            <div class="ldl-tank ldl-tank--mini" x-bind:data-state="mini.state"
                 x-bind:style="'--ldl-level: ' + level(mini) + 'px'"
                 role="progressbar" x-bind:aria-valuenow="Math.round(mini.p)" aria-valuemin="0" aria-valuemax="100"
                 aria-label="{{ $say('Backup progress', 'پیشرفت پشتیبان‌گیری') }}">
                <svg viewBox="0 0 200 200" aria-hidden="true">
                    <defs>
                        <clipPath id="ldl-clip-mini"><circle cx="100" cy="100" r="88"/></clipPath>
                    </defs>
                    <circle class="ldl-glass" cx="100" cy="100" r="88"/>
                    <g clip-path="url(#ldl-clip-mini)">
                        <g class="ldl-level">
                            <path class="ldl-wave ldl-wave-b" d="M0 0 Q25 -6 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z"/>
                            <path class="ldl-wave ldl-wave-f" d="M0 0 Q25 -9 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z"/>
                        </g>
                    </g>
                    <circle class="ldl-rim" cx="100" cy="100" r="88"/>
                </svg>
                <div class="ldl-read" x-bind:data-wet="mini.p > 53">
                    <template x-if="mini.state === 'done'">
                        <svg class="ldl-tick" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                    </template>
                    <template x-if="mini.state !== 'done'">
                        <b x-text="fmt(Math.round(mini.p)) + (fa ? '٪' : '%')"></b>
                    </template>
                    <small x-bind:data-ready="mini.state === 'done'" aria-live="polite" x-text="mini.state === 'error'
                        ? '{{ $say('Failed', 'خطا') }}'
                        : mini.state === 'done' ? '{{ $say('Ready', 'آماده شد') }}'
                        : '{{ $say('Resuming…', 'در حال ادامه…') }}'"></small>
                </div>
            </div>

            <div class="ldl-body">
                <h4>{{ $say('Nightly backup · Dublin vault', 'پشتیبان شبانه · خزانهٔ دوبلین') }}</h4>
                <span class="ldl-status" x-bind:data-tone="mini.state === 'error' ? 'err' : (mini.state === 'done' ? 'ok' : '')" x-text="mini.state === 'error'
                    ? '{{ $say('Destination disk full (28). Free space and retry.', 'دیسک مقصد پر است (۲۸). فضا را آزاد کنید و دوباره تلاش کنید.') }}'
                    : mini.state === 'done' ? '{{ $say('Space freed · backup complete.', 'فضا آزاد شد · پشتیبان‌گیری کامل شد.') }}'
                    : '{{ $say('Resuming from 34% — nothing restarts from zero.', 'از ۳۴٪ ادامه می‌دهد — هیچ‌چیز از صفر شروع نمی‌شود.') }}'"></span>
                <button type="button" class="ldl-retry" x-show="mini.state !== 'working'" x-cloak x-on:click="fill(mini, 2600)"
                    x-text="mini.state === 'error' ? '{{ $say('Free space & retry', 'آزادسازی فضا و تلاش دوباره') }}' : '{{ $say('Back up again', 'پشتیبان‌گیری دوباره') }}'"></button>
            </div>
        </div>
    </section>
</div>
