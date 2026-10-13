{{--
    Flowing gradient button's real scenarios: the upgrade row of a SaaS
    pricing footer (the gradient drifts under hover, the label leans out of
    its way and a switch keeps the current alive for heroes), then the size
    ladder, the three palettes and the link mode — mirrored for RTL.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp

<style>
    .ghb-root {
        --ghb-focus: #7c3aed;
        --ghb-shadow: 0 12px 32px -12px #db2777;
        display: grid;
        gap: 2rem;
        justify-items: center;
        inline-size: 100%;
    }
    html[data-theme="dark"] .ghb-root {
        --ghb-focus: #a78bfa;
        --ghb-shadow: 0 14px 38px -14px #a855f7;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ghb-root {
            --ghb-focus: #a78bfa;
            --ghb-shadow: 0 14px 38px -14px #a855f7;
        }
    }
    .ghb-row { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; align-items: center; justify-content: center; }
    .ghb-stack { display: grid; gap: .9rem; justify-items: center; }
    .ghb-btn {
        display: inline-flex; align-items: center; justify-content: center;
        padding: .7rem 1.6rem; border: 0; border-radius: 999px; cursor: pointer;
        color: #fff; font-weight: 600; font-size: .95rem; line-height: 1.4; text-decoration: none;
        --ghb-a: #7c3aed; --ghb-b: #db2777; --ghb-c: #f59e0b;
        background-image: linear-gradient(115deg, var(--ghb-a), var(--ghb-b) 45%, var(--ghb-c));
        background-size: 220% 220%;
        background-position: 12% 50%;
        transition: background-position .6s ease, box-shadow .35s ease, scale .25s ease;
    }
    .ghb-btn[data-palette="ocean"] { --ghb-a: #06b6d4; --ghb-b: #3b82f6; --ghb-c: #6366f1; }
    .ghb-btn[data-palette="meadow"] { --ghb-a: #10b981; --ghb-b: #84cc16; --ghb-c: #facc15; }
    .ghb-btn[data-size="sm"] { padding: .55rem 1.15rem; font-size: .85rem; }
    .ghb-btn[data-size="lg"] { padding: .95rem 2.1rem; font-size: 1.1rem; }
    .ghb-btn:hover, .ghb-btn:focus-visible { background-position: 88% 50%; box-shadow: var(--ghb-shadow); }
    .ghb-btn:active { scale: .97; }
    .ghb-btn:focus-visible { outline: 2px solid var(--ghb-focus); outline-offset: 3px; }
    .ghb-label { transition: translate .35s ease; }
    .ghb-btn:hover .ghb-label,
    .ghb-btn:focus-visible .ghb-label { translate: 2px -1px; }
    html[dir="rtl"] .ghb-btn:hover .ghb-label,
    html[dir="rtl"] .ghb-btn:focus-visible .ghb-label { translate: -2px -1px; }
    .ghb-btn[data-flow="true"] { animation: ghb-flow 3.2s ease-in-out infinite alternate; }
    @keyframes ghb-flow {
        from { background-position: 12% 50%; }
        to { background-position: 88% 50%; }
    }
    .ghb-switch {
        display: inline-flex; align-items: center; gap: .6rem; cursor: pointer;
        font-size: var(--nx-text-sm); color: var(--nx-text-muted);
    }
    .ghb-switch input {
        appearance: none; position: relative; margin: 0; cursor: pointer;
        inline-size: 2.4rem; block-size: 1.3rem; border-radius: 999px;
        background: var(--nx-border, #cbd5e1);
        transition: background-color .25s ease;
    }
    .ghb-switch input::before {
        content: ''; position: absolute; inset-block-start: .2rem; inset-inline-start: .2rem;
        inline-size: .9rem; block-size: .9rem; border-radius: 50%; background: #fff;
        transition: translate .25s ease;
    }
    .ghb-switch input:checked { background: #7c3aed; }
    .ghb-switch input:checked::before { translate: 1.1rem 0; }
    html[dir="rtl"] .ghb-switch input:checked::before { translate: -1.1rem 0; }
    .ghb-switch input:focus-visible { outline: 2px solid var(--ghb-focus); outline-offset: 2px; }
    .ghb-status { margin: 0; min-block-size: 1.4em; font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
    .ghb-caption { font-size: .75rem; color: var(--nx-text-muted); }
    @media (prefers-reduced-motion: reduce) {
        .ghb-btn, .ghb-label, .ghb-switch input, .ghb-switch input::before { transition-duration: .01ms !important; }
        .ghb-btn[data-flow="true"] { animation: none; background-position: 50% 50%; }
    }
</style>

<div class="ghb-root" x-data="{ flow: false, upgraded: false }">
    <section class="pg-box" style="gap: 1.25rem; justify-items: center; background:
        radial-gradient(120% 120% at 50% 0%, var(--nx-surface-2), var(--nx-surface))">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The upgrade row', 'ردیف ارتقای پلن') }}</h3>
            <p style="margin: 0; max-inline-size: 52ch; color: var(--nx-text-muted)">
                {{ $say('The gradient is painted at 220% and hover slides it across while the label steps out of its way. The switch keeps the current flowing with no pointer — the hero treatment.', 'گرادیان با عرض ۲۲۰٪ رنگ می‌خورد و هاور آن را سُر می‌دهد، برچسب هم قدری از مسیرش کنار می‌خزد. سوییچ جریان را بی‌موس زنده نگه می‌دارد — همان رفتاری که هیرو می‌خواهد.') }}
            </p>
        </div>
        <div class="ghb-row">
            <button type="button" class="ghb-btn" data-size="lg" x-bind:data-flow="flow" x-on:click="upgraded = true">
                <span class="ghb-label">{{ $say('Upgrade to the Pro plan', 'ارتقا به پلن پرو') }}</span>
            </button>
            <x-nx::button variant="ghost" href="/components">{{ $say('Compare the plans', 'مقایسهٔ پلن‌ها') }}</x-nx::button>
        </div>
        <label class="ghb-switch">
            <input type="checkbox" x-model="flow">
            <span>{{ $say('Keep the gradient flowing', 'جریان گرادیان ادامه داشته باشد') }}</span>
        </label>
        <p class="ghb-status" role="status" x-show="upgraded" x-cloak style="text-align: center">
            {{ $say('Pro starts at the top of next month — the invoice lands in your inbox.', 'پرو از اول ماه بعد فعال می‌شود — فاکتور به ایمیلتان می‌آید.') }}
        </p>
    </section>

    <div class="pg-grid">
        <section class="pg-box" style="gap: 1.25rem">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Three sizes, three palettes', 'سه سایز، سه پالت') }}</h3>
            <p style="margin: 0; color: var(--nx-text-muted)">
                {{ $say('sm keeps a quiet corner action, md is the workhorse and lg carries the hero; the palettes only swap three colour stops, nothing else moves.', 'sm برای کنج آرام فرم‌هاست، md اسب بارکش و lg هیرو را حمل می‌کند؛ پالت‌ها فقط سه ایستگاه رنگ را عوض می‌کنند و هیچ چیز دیگری جابه‌جا نمی‌شود.') }}
            </p>
            <div class="ghb-stack">
                <button type="button" class="ghb-btn" data-size="sm">
                    <span class="ghb-label">{{ $say('Remind me tomorrow', 'فردا یادم کن') }}</span>
                </button>
                <button type="button" class="ghb-btn">
                    <span class="ghb-label">{{ $say('Book a product demo', 'دموی محصول رزرو کن') }}</span>
                </button>
                <button type="button" class="ghb-btn" data-size="lg">
                    <span class="ghb-label">{{ $say('Start the free trial', 'شروع آزمایش رایگان') }}</span>
                </button>
            </div>
            <div class="ghb-row" style="gap: .75rem 1.25rem">
                <button type="button" class="ghb-btn"><span class="ghb-label">{{ $say('Aurora', 'شفق') }}</span></button>
                <button type="button" class="ghb-btn" data-palette="ocean"><span class="ghb-label">{{ $say('Ocean', 'اقیانوس') }}</span></button>
                <button type="button" class="ghb-btn" data-palette="meadow"><span class="ghb-label">{{ $say('Meadow', 'چمنزار') }}</span></button>
            </div>
            <p class="ghb-caption" style="margin: 0; text-align: center">
                {{ $say('aurora · ocean · meadow', 'شفق · اقیانوس · چمنزار') }}
            </p>
        </section>

        <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">حالت لینک و جهت دیگر</h3>
            <p style="margin: 0; color: var(--nx-text-muted)">
                لینک هم همان دکمه است — گرادیان در راست‌به‌چپ از سوی مقابل سُر می‌خورد و برچسب به سمت دیگرِ مسیر خم می‌شود.
            </p>
            <div class="ghb-row">
                <a class="ghb-btn" data-palette="ocean" href="#">
                    <span class="ghb-label">دیدن همهٔ پلن‌ها</span>
                </a>
                <a class="ghb-btn" data-size="sm" href="#">
                    <span class="ghb-label">مستندات API</span>
                </a>
            </div>
            <p class="ghb-caption" style="margin: 0; text-align: center">
                آزمایش رایگان {{ $num('14') }} روزه — بدون کارت بانکی
            </p>
        </section>
    </div>
</div>
