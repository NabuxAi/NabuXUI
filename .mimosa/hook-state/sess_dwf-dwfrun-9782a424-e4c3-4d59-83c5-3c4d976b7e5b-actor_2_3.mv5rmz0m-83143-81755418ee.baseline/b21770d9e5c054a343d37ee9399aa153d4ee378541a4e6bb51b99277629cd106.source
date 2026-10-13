{{--
    morph-ring in its real fields: a support console's refund card where the
    ring morphs while its percent climbs to the final tick, and a wallet
    top-up whose first gateway call fails — the ring freezes red at 58% until
    Retry turns the same ring again.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ldm-root {
        --ldm-fg: #1c1917; --ldm-muted: #78716c; --ldm-border: #e7e5e4; --ldm-bg: #ffffff;
        --ldm-accent: #7c3aed; --ldm-track: #ede9fe; --ldm-ok: #059669; --ldm-err: #dc2626;
        display: grid; gap: 1.6rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--ldm-fg);
    }
    html[data-theme="dark"] .ldm-root {
        --ldm-fg: #fafaf9; --ldm-muted: #a8a29e; --ldm-border: #292524; --ldm-bg: #1c1917;
        --ldm-accent: #a78bfa; --ldm-track: #3a3055; --ldm-ok: #34d399; --ldm-err: #f87171;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ldm-root {
            --ldm-fg: #fafaf9; --ldm-muted: #a8a29e; --ldm-border: #292524; --ldm-bg: #1c1917;
            --ldm-accent: #a78bfa; --ldm-track: #3a3055; --ldm-ok: #34d399; --ldm-err: #f87171;
        }
    }
    .ldm-card {
        display: flex; flex-wrap: wrap; align-items: center; gap: 1.4rem;
        padding: 1.4rem; border: 1px solid var(--ldm-border); border-radius: 1.1rem;
        background: var(--ldm-bg);
    }
    .ldm-ring {
        position: relative; display: grid; place-items: center; flex: none;
        inline-size: 7.5rem; aspect-ratio: 1; color: var(--ldm-accent);
        font: 700 1.35rem/1 inherit;
    }
    .ldm-ring svg { position: absolute; inset: 0; }
    .ldm-ring b { position: relative; font-variant-numeric: tabular-nums; }
    .ldm-track, .ldm-morph, .ldm-fill { fill: none; }
    .ldm-track { stroke: var(--ldm-track); stroke-width: 3; }
    .ldm-fill {
        stroke: color-mix(in oklab, currentColor 40%, transparent); stroke-width: 3;
        stroke-dasharray: 100; stroke-linecap: round;
        transform: rotate(-90deg); transform-origin: 50% 50%;
    }
    .ldm-morph {
        stroke: currentColor; stroke-linecap: round;
        animation: ldm-morph 2.6s ease-in-out infinite, ldm-spin 1.8s linear infinite;
    }
    .ldm-ring[data-state='idle'] .ldm-morph { animation-play-state: paused; opacity: .3; }
    .ldm-ring[data-state='error'] { color: var(--ldm-err); }
    .ldm-ring[data-state='error'] .ldm-morph { animation-play-state: paused; }
    .ldm-ring[data-state='done'] { color: var(--ldm-ok); }
    .ldm-ring[data-state='done'] .ldm-morph { display: none; }
    .ldm-ring .ldm-tick { inline-size: 2rem; block-size: 2rem; }
    .ldm-ring .ldm-tick path {
        fill: none; stroke: currentColor; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
        stroke-dasharray: 26; stroke-dashoffset: 26; animation: ldm-draw .5s ease-out forwards;
    }
    .ldm-body { flex: 1 1 14rem; display: grid; gap: .8rem; }
    .ldm-body h4 { margin: 0; font-size: .95rem; font-weight: 700; }
    .ldm-rows { display: grid; gap: .45rem; }
    .ldm-rows div {
        display: flex; flex-wrap: wrap; justify-content: space-between; gap: .3rem 1rem;
        font-size: .82rem;
    }
    .ldm-rows small { color: var(--ldm-muted); }
    .ldm-foot { display: flex; flex-wrap: wrap; align-items: center; gap: .7rem 1rem; }
    .ldm-btn {
        display: inline-flex; align-items: center; justify-content: center;
        block-size: 2.6rem; padding-inline: 1.25rem; border: none; border-radius: 999px;
        background: var(--ldm-accent); color: #fff; font: 600 .86rem/1 inherit; cursor: pointer;
        transition: background-color .2s ease;
    }
    .ldm-btn:hover:not(:disabled) { background: color-mix(in oklab, var(--ldm-accent) 84%, #000); }
    .ldm-btn:focus-visible { outline: 2px solid var(--ldm-accent); outline-offset: 2px; }
    .ldm-btn:disabled { cursor: default; }
    .ldm-btn[data-tone='retry'] { background: var(--ldm-err); }
    .ldm-btn[data-tone='retry']:hover:not(:disabled) { background: color-mix(in oklab, var(--ldm-err) 84%, #000); }
    .ldm-btn[data-tone='replay'] { background: var(--ldm-ok); }
    .ldm-status { font-size: .78rem; color: var(--ldm-muted); }
    .ldm-status[data-tone='ok'] { color: var(--ldm-ok); }
    .ldm-status[data-tone='err'] { color: var(--ldm-err); }
    @keyframes ldm-morph {
        0%, 100% { stroke-width: 3;  stroke-dasharray: 58 42; }
        28%      { stroke-width: 10; stroke-dasharray: 22 30; }
        52%      { stroke-width: 5;  stroke-dasharray: 84 16; }
        76%      { stroke-width: 12; stroke-dasharray: 10 26; }
    }
    @keyframes ldm-spin { to { rotate: 360deg; } }
    @keyframes ldm-draw { to { stroke-dashoffset: 0; } }
    @media (prefers-reduced-motion: reduce) {
        .ldm-morph { animation: none; stroke-width: 6; stroke-dasharray: 58 42; }
        .ldm-ring .ldm-tick path { animation: none; stroke-dashoffset: 0; }
        .ldm-btn { transition: none; }
    }
    @media (max-width: 480px) {
        .ldm-card { justify-content: center; text-align: center; }
        .ldm-body { justify-items: center; }
        .ldm-rows div { justify-content: center; }
    }
</style>

<div class="ldm-root"
    x-data="{
        fa: @js($fa),
        refund: { state: 'idle', p: 0, retried: true },
        topup: { state: 'idle', p: 0, retried: false },
        ticks: [],
        fmt(n) { const s = String(n); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        pct(job) { return this.fmt(Math.round(job.p)) + (this.fa ? '٪' : '%'); },
        start(job, failFirst) {
            if (job.state === 'working') return;
            job.state = 'working';
            job.p = 0;
            const shouldFail = failFirst && !job.retried;
            const step = 100 / (4200 / 55);
            const t = setInterval(() => {
                job.p = Math.min(100, job.p + step * (0.7 + Math.random() * 0.6));
                if (shouldFail && job.p >= 58) {
                    clearInterval(t); job.p = 58; job.retried = true; job.state = 'error'; return;
                }
                if (job.p >= 100) { clearInterval(t); job.state = 'done'; }
            }, 55);
            this.ticks.push(t);
        },
        destroy() { this.ticks.forEach((t) => clearInterval(t)); },
    }">

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A ring that never holds one shape', 'حلقه‌ای که هیچ‌وقت در یک فرم نمی‌ماند') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The morph ring trades weight for break — 3px to 12px, a long arc for tiny cracks — while a thin inner arc keeps the honest percent. In this refund card it runs to the tick; the top-up below dies at the gateway.', 'حلقهٔ مورف ضخامت را با شکست معامله می‌کند — از ۳ تا ۱۲ پیکسل، کمان بلاد با ترک‌های ریز — و کمان نازک درونی درصد درست را نگه می‌دارد. در کارت بازپرداخت تا تیک نهایی می‌رود؛ شارژ زیرش سر درگاه می‌میرد.') }}
            </p>
        </div>

        <div class="ldm-card">
            <div class="ldm-ring" x-bind:data-state="refund.state" role="progressbar"
                 x-bind:aria-valuenow="Math.round(refund.p)" aria-valuemin="0" aria-valuemax="100"
                 aria-label="{{ $say('Refund progress', 'پیشرفت بازپرداخت') }}">
                <svg viewBox="0 0 120 120" aria-hidden="true">
                    <circle class="ldm-track" cx="60" cy="60" r="50" pathLength="100"/>
                    <circle class="ldm-morph" cx="60" cy="60" r="50" pathLength="100"/>
                    <circle class="ldm-fill" cx="60" cy="60" r="50" pathLength="100"
                        x-bind:style="'stroke-dashoffset: ' + (100 - Math.round(refund.p))"/>
                </svg>
                <template x-if="refund.state === 'done'">
                    <svg class="ldm-tick" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                </template>
                <template x-if="refund.state !== 'done'">
                    <b x-text="pct(refund)"></b>
                </template>
            </div>
            <div class="ldm-body">
                <h4>{{ $say('Refund · order #NB-2841', 'بازپرداخت · سفارش #NB-2841') }}</h4>
                <div class="ldm-rows">
                    <div><small>{{ $say('Amount', 'مبلغ') }}</small><span>{{ $say('€124.50', '€۱۲۴٫۵۰') }}</span></div>
                    <div><small>{{ $say('Destination', 'مقصد') }}</small><span>{{ $say('Visa card ··4242', 'کارت ویزا ··۴۲۴۲') }}</span></div>
                    <div><small>{{ $say('Processor', 'پردازشگر') }}</small><span>{{ $say('Stripe · Frankfurt', 'Stripe · فرانکفورت') }}</span></div>
                </div>
                <div class="ldm-foot">
                    <button type="button" class="ldm-btn"
                        x-bind:data-tone="refund.state === 'done' ? 'replay' : ''"
                        x-bind:disabled="refund.state === 'working'"
                        x-on:click="start(refund, false)"
                        x-text="refund.state === 'done' ? '{{ $say('Process another', 'پردازش دوباره') }}' : '{{ $say('Process refund', 'پردازش بازپرداخت') }}'"></button>
                    <span class="ldm-status" x-bind:data-tone="refund.state === 'done' ? 'ok' : ''" aria-live="polite" x-text="refund.state === 'working'
                        ? '{{ $say('Processing…', 'در حال پردازش…') }}'
                        : refund.state === 'done' ? '{{ $say('Refund sent · 3–5 business days', 'بازپرداخت ارسال شد · ۳ تا ۵ روز کاری') }}'
                        : '{{ $say('Ready when you are', 'آمادهٔ شروع') }}'"></span>
                </div>
            </div>
        </div>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The failure nobody designs for', 'شکستی که هیچ‌کس برایش طراحی نمی‌کند') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The first charge here hits a 502: the ring turns red mid-lap, every animation pauses, the percent freezes at 58 — and Retry runs the exact same ring to green.', 'شارژ نخست این‌جا به خطای ۵۰۲ می‌خورد: حلقه وسط راه سرخ می‌شود، همهٔ انیمیشن‌ها می‌ایستند، درصد روی ۵۸ ساکن می‌ماند — و «تلاش دوباره» همان حلقه را تا سبز می‌راند.') }}
            </p>
        </div>

        <div class="ldm-card">
            <div class="ldm-ring" x-bind:data-state="topup.state" role="progressbar"
                 x-bind:aria-valuenow="Math.round(topup.p)" aria-valuemin="0" aria-valuemax="100"
                 aria-label="{{ $say('Top-up progress', 'پیشرفت شارژ') }}">
                <svg viewBox="0 0 120 120" aria-hidden="true">
                    <circle class="ldm-track" cx="60" cy="60" r="50" pathLength="100"/>
                    <circle class="ldm-morph" cx="60" cy="60" r="50" pathLength="100"/>
                    <circle class="ldm-fill" cx="60" cy="60" r="50" pathLength="100"
                        x-bind:style="'stroke-dashoffset: ' + (100 - Math.round(topup.p))"/>
                </svg>
                <template x-if="topup.state === 'done'">
                    <svg class="ldm-tick" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                </template>
                <template x-if="topup.state !== 'done'">
                    <b x-text="pct(topup)"></b>
                </template>
            </div>
            <div class="ldm-body">
                <h4>{{ $say('Nomad wallet · top-up', 'کیف پول Nomad · شارژ') }}</h4>
                <div class="ldm-rows">
                    <div><small>{{ $say('Amount', 'مبلغ') }}</small><span>{{ $say('€50.00', '€۵۰٫۰۰') }}</span></div>
                    <div><small>{{ $say('Method', 'روش') }}</small><span>{{ $say('Mastercard ··7017', 'مسترکارد ··۷۰۱۷') }}</span></div>
                    <div><small>{{ $say('Gateway', 'درگاه') }}</small><span>{{ $say('Dublin · eu-ie-1', 'دوبلین · eu-ie-1') }}</span></div>
                </div>
                <div class="ldm-foot">
                    <button type="button" class="ldm-btn"
                        x-bind:data-tone="topup.state === 'error' ? 'retry' : (topup.state === 'done' ? 'replay' : '')"
                        x-bind:disabled="topup.state === 'working'"
                        x-on:click="start(topup, true)"
                        x-text="topup.state === 'error' ? '{{ $say('Retry', 'تلاش دوباره') }}'
                            : topup.state === 'done' ? '{{ $say('Top up again', 'شارژ دوباره') }}'
                            : '{{ $say('Top up wallet', 'شارژ کیف پول') }}'"></button>
                    <span class="ldm-status" x-bind:data-tone="topup.state === 'error' ? 'err' : (topup.state === 'done' ? 'ok' : '')" aria-live="polite" x-text="topup.state === 'working'
                        ? '{{ $say('Charging…', 'در حال شارژ…') }}'
                        : topup.state === 'error' ? '{{ $say('Gateway did not answer (502).', 'درگاه پرداخت پاسخ نداد (۵۰۲).') }}'
                        : topup.state === 'done' ? '{{ $say('Balance updated.', 'موجودی به‌روز شد.') }}'
                        : '{{ $say('Ready when you are', 'آمادهٔ شروع') }}'"></span>
                </div>
            </div>
        </div>
    </section>
</div>
