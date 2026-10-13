{{--
    gradient-flow's real scenarios: the launch hero of Meridian Pay with a
    two-line flowing-gradient headline and a same-family subtitle, a control
    card bending the flow speed and flipping the palette, and the type-family
    specimen showing the effect at every scale.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫']) : $s;
@endphp

<style>
    .tgf-root {
        --tgf-bg: #ffffff; --tgf-ink: #1c1917; --tgf-muted: #78716c;
        --tgf-border: #e7e5e4; --tgf-card: #fafaf9; --tgf-accent: #a78bfa;
        display: grid; gap: 2rem; justify-items: stretch;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--tgf-ink);
    }
    .tgf-root[data-pal='aurora'] { --tgf-c1: #22d3ee; --tgf-c2: #a78bfa; --tgf-c3: #f472b6; }
    .tgf-root[data-pal='sunset'] { --tgf-c1: #fbbf24; --tgf-c2: #fb7185; --tgf-c3: #e879f9; }
    .tgf-root[data-pal='ocean']  { --tgf-c1: #67e8f9; --tgf-c2: #38bdf8; --tgf-c3: #6366f1; }
    .tgf-root[data-pal='neon']   { --tgf-c1: #4ade80; --tgf-c2: #22d3ee; --tgf-c3: #818cf8; }
    html[data-theme="dark"] .tgf-root {
        --tgf-bg: #171514; --tgf-ink: #fafaf9; --tgf-muted: #a8a29e;
        --tgf-border: #292524; --tgf-card: #1c1917; --tgf-accent: #c4b5fd;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .tgf-root {
            --tgf-bg: #171514; --tgf-ink: #fafaf9; --tgf-muted: #a8a29e;
            --tgf-border: #292524; --tgf-card: #1c1917; --tgf-accent: #c4b5fd;
        }
    }
    .tgf-hero {
        display: grid; gap: 1.1rem; justify-items: center; text-align: center;
        padding: clamp(1.5rem, 4vw, 3rem) 1.25rem;
        border: 1px solid var(--tgf-border); border-radius: 1.25rem;
        background:
            radial-gradient(60% 90% at 50% 0%, color-mix(in oklab, var(--tgf-c2) 14%, transparent), transparent 70%),
            var(--tgf-bg);
        overflow: clip;
    }
    .tgf-kicker {
        display: inline-flex; align-items: center; gap: .45rem; margin: 0;
        padding: .3rem .85rem; border: 1px solid var(--tgf-border); border-radius: 999px;
        font-size: .74rem; font-weight: 600; color: var(--tgf-muted);
    }
    .tgf-kicker i { inline-size: .5rem; aspect-ratio: 1; border-radius: 50%; background: var(--tgf-c2); }
    .tgf-flow {
        margin: 0; font-weight: 800; letter-spacing: -.01em;
        background: linear-gradient(100deg, var(--tgf-c1), var(--tgf-c2), var(--tgf-c3), var(--tgf-c2), var(--tgf-c1));
        background-size: 200% auto;
        -webkit-background-clip: text; background-clip: text; color: transparent;
        animation: tgf-move var(--tgf-dur, 8s) linear infinite;
        overflow-wrap: break-word;
    }
    .tgf-title { font-size: clamp(2.1rem, 8vw, 4rem); line-height: 1.12; }
    .tgf-sub { font-size: clamp(.85rem, 2.4vw, 1rem); font-weight: 700; }
    .tgf-meta {
        display: flex; flex-wrap: wrap; justify-content: center; gap: .4rem .9rem; margin: 0;
        font-size: .78rem; color: var(--tgf-muted);
    }
    .tgf-meta span { display: inline-flex; align-items: center; gap: .35rem; }
    .tgf-ctl {
        display: grid; gap: 1rem; justify-items: center; text-align: center;
        padding: 1.25rem 1rem; border: 1px solid var(--tgf-border);
        border-radius: 1.25rem; background: var(--tgf-card);
    }
    .tgf-ctl-row { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: .6rem 1rem; }
    .tgf-slider { display: inline-grid; gap: .3rem; font-size: .78rem; font-weight: 600; color: var(--tgf-muted); }
    .tgf-slider output { color: var(--tgf-ink); font-variant-numeric: tabular-nums; }
    .tgf-slider input { inline-size: min(11rem, 58vw); accent-color: var(--tgf-c2); }
    .tgf-swatches { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; }
    .tgf-sw {
        display: inline-flex; align-items: center; gap: .45rem; padding: .4rem .9rem;
        border: 1px solid var(--tgf-border); border-radius: 999px; background: var(--tgf-bg);
        color: var(--tgf-ink); font: 600 .78rem/1.4 inherit; cursor: pointer;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .tgf-sw i {
        inline-size: 1rem; block-size: .75rem; border-radius: 999px;
        background: linear-gradient(100deg, var(--tgf-c1), var(--tgf-c2), var(--tgf-c3));
    }
    .tgf-sw:hover { border-color: var(--tgf-c2); }
    .tgf-sw:focus-visible { outline: 2px solid var(--tgf-c2); outline-offset: 2px; }
    .tgf-sw[aria-pressed='true'] { border-color: var(--tgf-c2); box-shadow: 0 0 0 1px var(--tgf-c2); }
    .tgf-spec { display: grid; gap: 1rem; }
    .tgf-spec-row {
        display: grid; gap: .8rem; padding: 1.25rem 1.25rem; text-align: center; justify-items: center;
        border: 1px solid var(--tgf-border); border-radius: 1.25rem; background: var(--tgf-card);
    }
    .tgf-spec small { font-size: .72rem; font-weight: 600; color: var(--tgf-muted); letter-spacing: .04em; }
    @keyframes tgf-move { to { background-position: -200% center; } }
    @media (prefers-reduced-motion: reduce) {
        .tgf-flow { animation: none; }
        .tgf-sw { transition: none; }
    }
</style>

<div class="tgf-root"
    x-data="{
        fa: @js($fa),
        speed: 1,
        pal: 'aurora',
        fmt(s) { return this.fa ? String(s).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]).replace('.', '٫') : s; },
        get dur() { return (8 / this.speed).toFixed(1) + 's'; },
    }"
    x-bind:data-pal="pal"
    x-bind:style="`--tgf-dur: ${dur}`">

    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A headline that never sits still', 'تیتری که هرگز آرام نمی‌گیرد') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The symmetric gradient (colour out and back) runs under background-clip: text ink, so the loop closes without a seam. Title, second line and the subtitle below it are one family — only the size changes.', 'گرادیان متقارن (رنگ که می‌رود و برمی‌گردد) زیر جوهرِ background-clip: text می‌چرخد و حلقه بدون درز بسته می‌شود. تیتر، خط دوم و زیرتیتر یک خانواده‌اند — فقط اندازه عوض می‌شود.') }}
            </p>
        </div>

        <div class="tgf-hero">
            <p class="tgf-kicker"><i aria-hidden="true"></i>{{ $say('Meridian Pay · now generally available', 'مریدین پی · از امروز در دسترس عمومی') }}</p>
            <h4 class="tgf-flow tgf-title">
                {{ $say('Payments that flow', 'پرداخت‌هایی که جاری‌اند') }}<br>
                {{ $say('in 40 markets', 'در ۴۰ بازار') }}
            </h4>
            <p class="tgf-flow tgf-sub">{{ $say('One dashboard for every currency you settle — live reconciliation included.', 'یک داشبورد برای هر ارزی که تسویه می‌کنید — با تطبیق زندهٔ حساب‌ها.') }}</p>
            <p class="tgf-meta">
                <span>{{ $say('Frankfurt · EU', 'فرانکفورت · اروپا') }}</span>
                <span>{{ $say('Dublin · EU', 'دوبلین · اروپا') }}</span>
                <span>{{ $say('Singapore · APAC', 'سنگاپور · آسیا-اقیانوسیه') }}</span>
            </p>
        </div>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div class="tgf-ctl">
            <div class="tgf-ctl-row">
                <label class="tgf-slider">
                    <span>{{ $say('Flow speed', 'سرعت جریان') }} · <output x-text="fmt(speed) + '×'"></output></span>
                    <input type="range" min="0.5" max="3" step="0.25" x-model.number="speed">
                </label>
                <span class="tgf-slider">
                    <span>{{ $say('One lap', 'یک دور کامل') }}</span>
                    <output x-text="fmt(dur)"></output>
                </span>
            </div>
            <div class="tgf-swatches" role="group" aria-label="{{ $say('Palette', 'پالت رنگ') }}">
                @foreach ([
                    'aurora' => ['Aurora', 'شفق'],
                    'sunset' => ['Sunset', 'غروب'],
                    'ocean' => ['Ocean', 'اقیانوس'],
                    'neon' => ['Neon', 'نئون'],
                ] as $key => [$en, $faName])
                    <button type="button" class="tgf-sw" x-bind:aria-pressed="pal === '{{ $key }}' ? 'true' : 'false'"
                            x-on:click="pal = '{{ $key }}'"><i aria-hidden="true"></i>{{ $say($en, $faName) }}</button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The family at every scale', 'خانواده در همهٔ اندازه‌ها') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The same two rules — clip the gradient to the glyphs, drift the background — from an 800-weight display line down to a 700-weight eyebrow.', 'همان دو قاعده — بریدن گرادیان به شکل حروف و راندن پس‌زمینه — از خط نمایشی وزن ۸۰۰ تا سطر نازک وزن ۷۰۰.') }}
            </p>
        </div>
        <div class="tgf-spec">
            <div class="tgf-spec-row">
                <span class="tgf-flow tgf-title" style="font-size: clamp(1.7rem, 6vw, 2.6rem)">{{ $say('Borderless payouts', 'پرداخت‌های بدون مرز') }}</span>
                <small>{{ $say('display · 800 · clamp(1.7rem, 6vw, 2.6rem)', 'نمایشی · ۸۰۰ · clamp(1.7rem, 6vw, 2.6rem)') }}</small>
            </div>
            <div class="tgf-spec-row">
                <span class="tgf-flow tgf-sub">{{ $say('Settle in EUR, USD, SGD and 37 more', 'تسویه به یورو، دلار، دلار سنگاپور و ۳۷ ارز دیگر') }}</span>
                <small>{{ $say('subtitle · 700 · inherits size', 'زیرتیتر · ۷۰۰ · وارث اندازه') }}</small>
            </div>
        </div>
    </section>
</div>
