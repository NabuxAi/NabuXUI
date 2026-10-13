{{--
    counter-load in its real field: the export card of an analytics product.
    The ring counts smoothly, the stage label trades on thresholds, cancel
    genuinely aborts the count (the ring greys out where it stopped), and at
    100% the file is ready with a download button.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ldc-root {
        --ldc-fg: #1c1917; --ldc-muted: #78716c; --ldc-border: #e7e5e4; --ldc-bg: #ffffff;
        --ldc-accent: #0f766e; --ldc-accent-2: #14b8a6; --ldc-cancel: #a8a29e;
        display: grid; gap: 1.6rem; justify-items: center;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--ldc-fg);
    }
    html[data-theme="dark"] .ldc-root {
        --ldc-fg: #fafaf9; --ldc-muted: #a8a29e; --ldc-border: #292524; --ldc-bg: #1c1917;
        --ldc-accent: #2dd4bf; --ldc-accent-2: #5eead4; --ldc-cancel: #78716c;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ldc-root {
            --ldc-fg: #fafaf9; --ldc-muted: #a8a29e; --ldc-border: #292524; --ldc-bg: #1c1917;
            --ldc-accent: #2dd4bf; --ldc-accent-2: #5eead4; --ldc-cancel: #78716c;
        }
    }
    .ldc-card {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: 1.6rem 2rem; padding: 1.6rem; inline-size: min(100%, 34rem);
        border: 1px solid var(--ldc-border); border-radius: 1.1rem; background: var(--ldc-bg);
    }
    .ldc-wrap {
        position: relative; display: grid; place-items: center; flex: none;
        inline-size: 9rem; aspect-ratio: 1; font: 700 1.55rem/1 inherit;
    }
    .ldc-wrap svg { position: absolute; inset: 0; }
    .ldc-center { position: relative; display: grid; gap: .35rem; justify-items: center; text-align: center; }
    .ldc-center b { font-variant-numeric: tabular-nums; }
    .ldc-center small { font-size: .62rem; font-weight: 600; color: var(--ldc-muted); max-inline-size: 7rem; }
    .ldc-center small[data-tone='ok'] { color: var(--ldc-accent); }
    .ldc-track, .ldc-arc { fill: none; }
    .ldc-track { stroke: color-mix(in oklab, var(--ldc-fg) 10%, transparent); stroke-width: 7; }
    .ldc-arc {
        stroke: var(--ldc-accent); stroke-width: 7; stroke-linecap: round;
        stroke-dasharray: 339.3; transform: rotate(-90deg); transform-origin: 50% 50%;
    }
    .ldc-wrap[data-state='cancelled'] .ldc-arc { stroke: var(--ldc-cancel); }
    .ldc-wrap .ldc-tick { inline-size: 2.2rem; block-size: 2.2rem; color: var(--ldc-accent); }
    .ldc-wrap .ldc-tick path {
        fill: none; stroke: currentColor; stroke-width: 2.6; stroke-linecap: round; stroke-linejoin: round;
        stroke-dasharray: 26; stroke-dashoffset: 26; animation: ldc-draw .5s ease-out forwards;
    }
    .ldc-body { flex: 1 1 13rem; display: grid; gap: .9rem; justify-items: center; text-align: center; }
    .ldc-body h4 { margin: 0; font-size: .95rem; font-weight: 700; }
    .ldc-body > p { margin: 0; font-size: .78rem; color: var(--ldc-muted); }
    .ldc-stages { display: grid; gap: .45rem; justify-items: start; font-size: .8rem; }
    .ldc-stage { display: flex; align-items: center; gap: .55rem; color: var(--ldc-muted); }
    .ldc-stage i { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
    .ldc-stage[data-s='done'] { color: var(--ldc-accent); }
    .ldc-stage[data-s='active'] { color: var(--ldc-fg); font-weight: 700; }
    .ldc-stage[data-s='active'] i { animation: ldc-pulse 1.1s ease-in-out infinite; }
    .ldc-foot { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: .7rem; }
    .ldc-btn {
        display: inline-flex; align-items: center; justify-content: center;
        block-size: 2.5rem; padding-inline: 1.2rem; border: none; border-radius: 999px;
        font: 600 .84rem/1 inherit; cursor: pointer;
        transition: background-color .2s ease, color .2s ease, border-color .2s ease;
    }
    .ldc-btn[data-kind='ghost'] {
        border: 1px solid var(--ldc-border); background: transparent; color: var(--ldc-fg);
    }
    .ldc-btn[data-kind='ghost']:hover { border-color: var(--ldc-muted); }
    .ldc-btn[data-kind='solid'] { background: var(--ldc-accent); color: #04211d; }
    html[data-theme="dark"] .ldc-btn[data-kind='solid'] { color: #042f2a; }
    .ldc-btn:focus-visible { outline: 2px solid var(--ldc-accent); outline-offset: 2px; }
    .ldc-hint { font-size: .74rem; color: var(--ldc-muted); }
    @keyframes ldc-draw { to { stroke-dashoffset: 0; } }
    @keyframes ldc-pulse { 0%, 100% { scale: 1; opacity: 1; } 50% { scale: 1.5; opacity: .55; } }
    @media (prefers-reduced-motion: reduce) {
        .ldc-wrap .ldc-tick path { animation: none; stroke-dashoffset: 0; }
        .ldc-stage[data-s='active'] i { animation: none; }
        .ldc-btn { transition: none; }
    }
</style>

<div class="ldc-root"
    x-data="{
        fa: @js($fa),
        state: 'idle',
        p: 0,
        raf: 0,
        stages: [
            { at: 0,  label: '{{ $say('Gathering data', 'جمع‌آوری داده') }}' },
            { at: 35, label: '{{ $say('Compressing', 'فشرده‌سازی') }}' },
            { at: 75, label: '{{ $say('Uploading', 'بارگذاری') }}' },
        ],
        fmt(n, dec) {
            let s = dec !== undefined ? Number(n).toFixed(dec) : String(n);
            if (this.fa) s = s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]).replace(/\./g, '٫');
            return s;
        },
        get stageIdx() { return Math.max(0, this.stages.reduce((acc, s, i) => (this.p >= s.at ? i : acc), 0)); },
        get stageOf() {
            if (this.state === 'done') return '{{ $say('Ready', 'آماده') }}';
            if (this.state === 'cancelled') return '{{ $say('Cancelled', 'لغو شد') }}';
            if (this.state === 'idle') return '{{ $say('Ready', 'آماده') }}';
            return this.stages[this.stageIdx].label + '…';
        },
        run() {
            if (this.state === 'working') return;
            this.state = 'working';
            this.p = 0;
            const t0 = performance.now();
            const tick = (now) => {
                const t = Math.min(1, (now - t0) / 6400);
                const eased = t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
                this.p = eased * 100;
                if (t >= 1) { this.state = 'done'; this.p = 100; return; }
                this.raf = requestAnimationFrame(tick);
            };
            this.raf = requestAnimationFrame(tick);
        },
        cancel() {
            if (this.state !== 'working') return;
            cancelAnimationFrame(this.raf);
            this.state = 'cancelled';
        },
        init() { this.run(); },
        destroy() { cancelAnimationFrame(this.raf); },
    }">

    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A percent that tells the truth', 'درصدی که راست می‌گوید') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The export card of an analytics product: the ring eases round while the number counts and the stage label trades underneath. Cancel honestly aborts — the ring greys out exactly where it stopped and offers a restart; at 100% the download wakes.', 'کارت خروجی یک محصول تحلیلی: حلقه با شتاب نرم پر می‌شود، عدد می‌شمارد و لیبل مرحله زیرش عوض می‌شود. «لغو» واقعاً قطع می‌کند — حلقه همان‌جا که بود خاکستری می‌شود و شروع دوباره پیشنهاد می‌دهد؛ در ۱۰۰٪ دانلود بیدار می‌شود.') }}
            </p>
        </div>

        <div class="ldc-card">
            <div class="ldc-wrap" x-bind:data-state="state" role="progressbar"
                 x-bind:aria-valuenow="Math.round(p)" aria-valuemin="0" aria-valuemax="100"
                 aria-label="{{ $say('Export progress', 'پیشرفت خروجی') }}">
                <svg viewBox="0 0 128 128" aria-hidden="true">
                    <circle class="ldc-track" cx="64" cy="64" r="54"/>
                    <circle class="ldc-arc" cx="64" cy="64" r="54"
                        x-bind:style="'stroke-dashoffset: ' + (339.3 * (1 - p / 100))"/>
                </svg>
                <div class="ldc-center">
                    <template x-if="state === 'done'">
                        <svg class="ldc-tick" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                    </template>
                    <template x-if="state !== 'done'">
                        <b x-text="fmt(Math.round(p)) + (fa ? '٪' : '%')"></b>
                    </template>
                    <small x-bind:data-tone="state === 'done' ? 'ok' : ''" aria-live="polite" x-text="stageOf"></small>
                </div>
            </div>

            <div class="ldc-body">
                <h4>{{ $say('Compass · Q4 report export', 'Compass · خروجی گزارش Q4') }}</h4>
                <p>Q4-report.pdf · <span x-text="fmt(3.8 * p / 100, 1)"></span>MB / <span x-text="fmt(3.8, 1)"></span>MB</p>
                <div class="ldc-stages" aria-hidden="true">
                    <template x-for="(s, i) in stages" :key="s.at">
                        <span class="ldc-stage"
                            x-bind:data-s="state === 'done' || i < stageIdx ? 'done' : (i === stageIdx && state === 'working' ? 'active' : '')">
                            <i aria-hidden="true"></i><span x-text="s.label"></span>
                        </span>
                    </template>
                </div>
                <div class="ldc-foot">
                    <template x-if="state === 'working'">
                        <button type="button" class="ldc-btn" data-kind="ghost" x-on:click="cancel()">{{ $say('Cancel', 'لغو') }}</button>
                    </template>
                    <template x-if="state === 'idle' || state === 'cancelled'">
                        <button type="button" class="ldc-btn" data-kind="solid" x-on:click="run()"
                            x-text="state === 'cancelled' ? '{{ $say('Start over', 'شروع دوباره') }}' : '{{ $say('Start export', 'شروع خروجی') }}'"></button>
                    </template>
                    <template x-if="state === 'done'">
                        <button type="button" class="ldc-btn" data-kind="solid">{{ $say('Download PDF', 'دانلود PDF') }}</button>
                    </template>
                    <template x-if="state === 'done'">
                        <button type="button" class="ldc-btn" data-kind="ghost" x-on:click="run()">{{ $say('Export again', 'خروجی دوباره') }}</button>
                    </template>
                    <span class="ldc-hint" x-show="state === 'cancelled'" x-cloak
                        x-text="'{{ $say('Stopped at', 'متوقف شد در') }} ' + fmt(Math.round(p)) + (fa ? '٪' : '%')"></span>
                </div>
            </div>
        </div>
    </section>
</div>
