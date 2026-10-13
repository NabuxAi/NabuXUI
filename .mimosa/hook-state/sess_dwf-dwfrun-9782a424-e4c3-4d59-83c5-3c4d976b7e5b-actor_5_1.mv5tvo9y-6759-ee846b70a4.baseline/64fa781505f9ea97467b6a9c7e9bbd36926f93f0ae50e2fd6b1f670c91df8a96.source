{{--
    Gradient glow button's real scenarios: the seat-reservation CTA of a
    product launch (a pulsing halo, a light sweep on hover, a springy press
    and a live counter), then the everyday sizes beside quieter actions and
    the RTL cut where the sweep crosses from the other side.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp

<style>
    .ggb-root {
        --ggb-a: #6366f1;
        --ggb-b: #a855f7;
        --ggb-c: #ec4899;
        --ggb-halo-op: .45;
        display: grid;
        gap: 2rem;
        justify-items: center;
        inline-size: 100%;
    }
    html[data-theme="dark"] .ggb-root { --ggb-halo-op: .5; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ggb-root { --ggb-halo-op: .5; }
    }
    .ggb-row { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; align-items: center; justify-content: center; }
    .ggb-wrap { position: relative; display: inline-block; isolation: isolate; }
    .ggb-halo {
        position: absolute; inset: -8px -14px; z-index: -1; border-radius: 999px;
        background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
        filter: blur(20px); opacity: var(--ggb-halo-op);
        animation: ggb-pulse 2.6s ease-in-out infinite;
    }
    .ggb-btn {
        position: relative; overflow: hidden; border: 0; border-radius: 999px; cursor: pointer;
        padding: .75rem 1.7rem; color: #fff; font-weight: 700; font-size: .95rem; line-height: 1.4;
        text-decoration: none; font-family: inherit;
        background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
        transition: scale .45s cubic-bezier(.34, 1.56, .64, 1);
    }
    .ggb-btn::after {
        content: ''; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
        background: linear-gradient(105deg, transparent 42%, rgba(255, 255, 255, .5) 50%, transparent 58%);
        translate: -130% 0;
    }
    .ggb-btn:hover::after, .ggb-btn:focus-visible::after { animation: ggb-sweep .9s ease; }
    .ggb-btn:hover, .ggb-btn:focus-visible { scale: 1.03; }
    .ggb-btn:active { scale: .93; }
    .ggb-btn:focus-visible { outline: 2px solid #e9d5ff; outline-offset: 4px; }
    .ggb-btn[data-size="cta"] { padding: 1.1rem 2.6rem; font-size: 1.25rem; }
    .ggb-wrap:has(.ggb-btn[data-size="cta"]) .ggb-halo { inset: -12px -20px; filter: blur(26px); }
    html[dir="rtl"] .ggb-btn::after { translate: 130% 0; }
    html[dir="rtl"] .ggb-btn:hover::after,
    html[dir="rtl"] .ggb-btn:focus-visible::after { animation-name: ggb-sweep-rtl; }
    .ggb-count { margin: 0; min-block-size: 1.4em; font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
    @keyframes ggb-pulse {
        0%, 100% { opacity: .32; scale: .97; }
        50% { opacity: .62; scale: 1.05; }
    }
    @keyframes ggb-sweep { to { translate: 130% 0; } }
    @keyframes ggb-sweep-rtl { to { translate: -130% 0; } }
    @media (prefers-reduced-motion: reduce) {
        .ggb-halo { animation: none; opacity: var(--ggb-halo-op); }
        .ggb-btn::after { display: none; }
        .ggb-btn { transition-duration: .01ms; }
        .ggb-btn:hover, .ggb-btn:focus-visible, .ggb-btn:active { scale: 1; }
    }
</style>

<div class="ggb-root" x-data="{ count: 0, locale: '{{ $fa ? 'fa-IR' : 'en-US' }}' }">
    <section class="pg-box" style="gap: 1.25rem; justify-items: center; text-align: center; background:
        radial-gradient(120% 120% at 50% 0%, var(--nx-surface-2), var(--nx-surface))">
        <div style="display: grid; gap: .35rem; justify-items: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The launch CTA', 'CTA اعلام عرضه') }}</h3>
            <p style="margin: 0; max-inline-size: 52ch; color: var(--nx-text-muted)">
                {{ $say('The halo breathes on its own; hover sends a band of light across the face and the press lands on a spring. Every reservation counts you into the Singapore batch.', 'هاله خودش نفس می‌کشد؛ هاور جاروی نوری از روی دکمه می‌گذراند و فشردن روی فنر می‌نشیند. هر رزرو شما را به دستهٔ سنگاپور می‌شمارد.') }}
            </p>
        </div>
        <span class="ggb-wrap">
            <span class="ggb-halo" aria-hidden="true"></span>
            <button type="button" class="ggb-btn" data-size="cta" x-on:click="count++">
                <span class="ggb-label">{{ $say('Reserve your seat', 'جای خودت را رزرو کن') }}</span>
            </button>
        </span>
        <p class="ggb-count" role="status">
            <span x-show="count === 0">
                {{ $say('The batch caps at 250 — Singapore, January 21.', 'ظرفیت دسته ۲۵۰ نفر است — سنگاپور، ۲۱ ژانویه.') }}
            </span>
            <span x-show="count > 0" x-cloak>
                {{ $say('You are number', 'شما نفر') }}
                <b x-text="count.toLocaleString(locale)"></b>
                {{ $say('in this batch.', 'از این دسته هستید.') }}
            </span>
        </p>
    </section>

    <div class="pg-grid">
        <section class="pg-box" style="gap: 1rem">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Everyday sizes', 'سایزهای روزمره') }}</h3>
            <p style="margin: 0; color: var(--nx-text-muted)">
                {{ $say('The same halo, quieter, for rows and toolbars — it sits beside ghost actions without drowning them.', 'همان هاله، آرام‌تر، برای ردیف‌ها و نوار ابزار — کنار اکشن‌های شبح می‌نشیند بدون اینکه آنها را غرق کند.') }}
            </p>
            <div class="ggb-row">
                <span class="ggb-wrap">
                    <span class="ggb-halo" aria-hidden="true"></span>
                    <button type="button" class="ggb-btn" x-on:click="count++">{{ $say('Start free', 'شروع رایگان') }}</button>
                </span>
                <span class="ggb-wrap">
                    <span class="ggb-halo" aria-hidden="true"></span>
                    <button type="button" class="ggb-btn" x-on:click="count++">{{ $say('Talk to sales', 'گفت‌وگو با فروش') }}</button>
                </span>
                <x-nx::button variant="ghost">{{ $say('Read the FAQ', 'خواندن پرسش‌های پرتکرار') }}</x-nx::button>
            </div>
            <p class="ggb-count" style="text-align: center">
                {{ $say('Presses across the page all feed the same counter.', 'همهٔ فشردن‌های صفحه شمارندهٔ واحدی را پر می‌کنند.') }}
            </p>
        </section>

        <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">جهت دیگر</h3>
            <p style="margin: 0; color: var(--nx-text-muted)">
                جاروی نور در راست‌به‌چپ از سوی دیگر می‌گذرد؛ هاله و فنر همان‌ها می‌مانند. لینک هم گرادیان و هاله را به ارث می‌برد.
            </p>
            <div class="ggb-row">
                <span class="ggb-wrap">
                    <span class="ggb-halo" aria-hidden="true"></span>
                    <a class="ggb-btn" href="#">افزودن به تقویم</a>
                </span>
                <span class="ggb-wrap">
                    <span class="ggb-halo" aria-hidden="true"></span>
                    <a class="ggb-btn" href="#">یادداشت برنامهٔ رویداد</a>
                </span>
            </div>
            <p class="ggb-count" style="text-align: center">
                {{ $num('2') }} روز — سنگاپور و دوبلین
            </p>
        </section>
    </div>
</div>
