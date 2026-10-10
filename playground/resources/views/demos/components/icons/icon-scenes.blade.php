{{--
    The 3D icon language carrying real Northlight scenes: an empty state whose
    open-box icon tells the story (the create action reports inline), a feature
    row where bolt / chart / shield icons are the backbone of each tile, and
    pricing plans whose rocket / layers / crown icons anchor three cards that
    recompute live with the monthly/annual segment.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .i3sc-root {
        --i3sc-c1: #c7b8ff; --i3sc-c2: #6c4cf1;
        --i3sc-c3: #8df0ff; --i3sc-c4: #0ea5e9;
        --i3sc-deep: color-mix(in oklab, var(--i3sc-c2) 55%, #14123a);
        --i3sc-ink: #2a1e5e; --i3sc-muted: #645d97;
        --i3sc-plate: color-mix(in oklab, var(--i3sc-c1) 10%, transparent);
        --i3sc-line: color-mix(in oklab, var(--i3sc-c2) 20%, transparent);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .i3sc-root { --i3sc-ink: #e7e4ff; --i3sc-muted: #a9a3d8; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .i3sc-root { --i3sc-ink: #e7e4ff; --i3sc-muted: #a9a3d8; }
    }
    .i3sc-defs { position: absolute; inline-size: 0; block-size: 0; overflow: hidden; pointer-events: none; }
    .i3sc-root .i3sc-s1 { stop-color: var(--i3sc-c1); }
    .i3sc-root .i3sc-s2 { stop-color: var(--i3sc-c2); }
    .i3sc-root .i3sc-s3 { stop-color: var(--i3sc-c3); }
    .i3sc-root .i3sc-s4 { stop-color: var(--i3sc-c4); }
    .i3sc-root .i3sc-s5 { stop-color: var(--i3sc-deep); }
    .i3sc-root .i3sc-sh { fill: url(#i3sc-shadow); }
    .i3sc-head { display: grid; gap: .4rem; justify-items: center; text-align: center; }
    .i3sc-head p { margin: 0; max-inline-size: 54ch; color: var(--nx-text-muted); }
    .i3sc-empty { display: grid; justify-items: center; text-align: center; gap: .8rem;
                  inline-size: min(100%, 34rem); padding: 2.75rem 1.25rem 2.25rem; border-radius: 1.5rem;
                  background: radial-gradient(120% 90% at 50% 0%, var(--i3sc-plate), transparent 70%); }
    .i3sc-empty h4 { margin: .4rem 0 0; font-size: var(--nx-text-xl, 1.375rem); color: var(--i3sc-ink); }
    .i3sc-empty > p { margin: 0; max-inline-size: 42ch; color: var(--i3sc-muted); }
    .i3sc-empty-icon { inline-size: 6.5rem; block-size: 6.5rem; }
    .i3sc-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: .6rem; margin-block-start: .4rem; }
    .i3sc-btn { appearance: none; border: none; cursor: pointer; border-radius: 999px; padding: .68rem 1.35rem;
                font: 600 .875rem/1 var(--nx-font, inherit); color: #fff;
                background: linear-gradient(135deg, var(--i3sc-c1), var(--i3sc-c2));
                box-shadow: 0 5px 16px color-mix(in oklab, var(--i3sc-c2) 38%, transparent);
                transition: translate .18s ease, box-shadow .18s ease; }
    .i3sc-btn:hover { translate: 0 -2px; box-shadow: 0 9px 22px color-mix(in oklab, var(--i3sc-c2) 46%, transparent); }
    .i3sc-btn-ghost { background: var(--i3sc-plate); color: var(--i3sc-ink); box-shadow: none;
                      border: 1px solid var(--i3sc-line); }
    .i3sc-btn-ghost:hover { box-shadow: none; background: color-mix(in oklab, var(--i3sc-c1) 22%, transparent); }
    .i3sc-btn:focus-visible { outline: 2px solid var(--i3sc-c2); outline-offset: 2px; }
    .i3sc-note { margin: .2rem 0 0; font-size: .8rem; color: var(--i3sc-muted); }
    .i3sc-feats { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15.5rem), 1fr));
                  gap: 1rem; inline-size: 100%; max-inline-size: 54rem; }
    .i3sc-feat { display: grid; align-content: start; gap: .55rem; padding: 1.4rem 1.35rem; border-radius: 1.25rem;
                 background: var(--i3sc-plate); border: 1px solid var(--i3sc-line);
                 transition: translate .18s ease, background-color .18s ease, box-shadow .18s ease; }
    .i3sc-feat:hover { translate: 0 -3px; background: color-mix(in oklab, var(--i3sc-c1) 20%, transparent);
                       box-shadow: 0 14px 28px color-mix(in oklab, var(--i3sc-c2) 20%, transparent); }
    .i3sc-feat svg { inline-size: 3.25rem; block-size: 3.25rem; }
    .i3sc-feat h4 { margin: .3rem 0 0; font-size: 1rem; color: var(--i3sc-ink); }
    .i3sc-feat p { margin: 0; font-size: .84rem; line-height: 1.65; color: var(--i3sc-muted); }
    .i3sc-link { justify-self: start; display: inline-flex; align-items: center; gap: .35rem; margin-block-start: .3rem;
                 font-size: .82rem; font-weight: 700; text-decoration: none; color: var(--i3sc-c2); }
    .i3sc-link:hover { text-decoration: underline; }
    .i3sc-billing { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: .8rem; }
    .i3sc-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px;
                background: var(--i3sc-plate); border: 1px solid var(--i3sc-line); }
    .i3sc-seg button { appearance: none; border: none; border-radius: 999px; padding: .45rem 1.1rem; cursor: pointer;
                       font: 500 .8125rem/1 var(--nx-font, inherit); color: var(--i3sc-ink); background: transparent;
                       transition: background-color .18s ease, color .18s ease, box-shadow .18s ease; }
    .i3sc-seg button[aria-checked="true"] { background: linear-gradient(135deg, var(--i3sc-c1), var(--i3sc-c2)); color: #fff;
                                             box-shadow: 0 3px 10px color-mix(in oklab, var(--i3sc-c2) 40%, transparent); }
    .i3sc-seg button:focus-visible { outline: 2px solid var(--i3sc-c2); outline-offset: 2px; }
    .i3sc-save { font-size: .74rem; font-weight: 700; padding: .28rem .6rem; border-radius: 999px; color: var(--i3sc-c2);
                 background: var(--i3sc-plate); border: 1px solid var(--i3sc-line); }
    .i3sc-plans { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 16rem), 1fr));
                  gap: 1rem; align-items: stretch; inline-size: 100%; max-inline-size: 58rem; }
    .i3sc-plan { position: relative; display: grid; align-content: start; gap: .65rem; padding: 1.6rem 1.5rem;
                 border-radius: 1.4rem; background: var(--nx-surface); border: 1px solid var(--i3sc-line);
                 transition: translate .18s ease, box-shadow .18s ease; }
    .i3sc-plan:hover { translate: 0 -3px; }
    .i3sc-plan[data-featured] { border: 2px solid var(--i3sc-c2);
                                box-shadow: 0 18px 40px color-mix(in oklab, var(--i3sc-c2) 24%, transparent); }
    .i3sc-badge { position: absolute; inset-block-start: -.85rem; inset-inline-start: 50%; translate: -50% 0;
                  padding: .3rem .8rem; border-radius: 999px; font-size: .72rem; font-weight: 700; color: #fff;
                  background: linear-gradient(135deg, var(--i3sc-c1), var(--i3sc-c2)); }
    html[dir="rtl"] .i3sc-badge { translate: 50% 0; }
    .i3sc-plan svg { inline-size: 3.5rem; block-size: 3.5rem; }
    .i3sc-plan h4 { margin: .25rem 0 0; font-size: 1.05rem; color: var(--i3sc-ink); }
    .i3sc-price { display: flex; align-items: baseline; gap: .45rem; margin: 0; flex-wrap: wrap; }
    .i3sc-price b { font-size: 1.9rem; font-weight: 800; color: var(--i3sc-ink); }
    .i3sc-price small { font-size: .76rem; color: var(--i3sc-muted); }
    .i3sc-perks { display: grid; gap: .45rem; margin: .2rem 0 .5rem; padding: 0; list-style: none; }
    .i3sc-perks li { display: flex; align-items: start; gap: .5rem; font-size: .84rem; color: var(--i3sc-muted); }
    .i3sc-perks li::before { content: '✓'; flex: none; display: grid; place-items: center; inline-size: 1.15rem; aspect-ratio: 1;
                             border-radius: 50%; font-size: .68rem; font-weight: 800; color: var(--i3sc-c2);
                             background: var(--i3sc-plate); border: 1px solid var(--i3sc-line); }
    .i3sc-plan .i3sc-btn { justify-self: stretch; text-align: center; }
    .i3sc-plan[data-featured] .i3sc-btn-ghost { color: #fff; }
    @media (max-width: 480px) {
        .i3sc-empty { padding: 2rem 1rem 1.75rem; }
        .i3sc-empty-icon { inline-size: 5.25rem; block-size: 5.25rem; }
        .i3sc-plan svg { inline-size: 3rem; block-size: 3rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .i3sc-root *, .i3sc-root *::before, .i3sc-root *::after {
            transition-duration: .01ms !important; animation: none !important;
        }
        .i3sc-feat:hover, .i3sc-plan:hover, .i3sc-btn:hover { translate: none; }
    }
</style>

<div class="i3sc-root"
    x-data="{
        created: false,
        cycle: 'annual',
        plans: {
            launch: { m: '{{ $say('$12', $num('12') . '$') }}', a: '{{ $say('$9', $num('9') . '$') }}' },
            growth: { m: '{{ $say('$29', $num('29') . '$') }}', a: '{{ $say('$23', $num('23') . '$') }}' },
            scale:  { m: '{{ $say('$99', $num('99') . '$') }}', a: '{{ $say('$79', $num('79') . '$') }}' },
        },
        labelM: '{{ $say('per month', 'در هر ماه') }}',
        labelA: '{{ $say('per month, billed yearly', 'در هر ماه، صورتحساب سالانه') }}',
    }">

    {{-- one hidden defs block colours every icon in every scene below --}}
    <svg class="i3sc-defs" width="0" height="0" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="i3sc-body" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" class="i3sc-s1"/><stop offset="1" class="i3sc-s2"/>
            </linearGradient>
            <linearGradient id="i3sc-accent" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" class="i3sc-s3"/><stop offset="1" class="i3sc-s4"/>
            </linearGradient>
            <linearGradient id="i3sc-gloss" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#fff" stop-opacity=".85"/><stop offset="1" stop-color="#fff" stop-opacity="0"/>
            </linearGradient>
            <radialGradient id="i3sc-shadow">
                <stop offset="0" stop-color="#0f172a" stop-opacity=".26"/>
                <stop offset=".65" stop-color="#0f172a" stop-opacity=".12"/>
                <stop offset="1" stop-color="#0f172a" stop-opacity="0"/>
            </radialGradient>
        </defs>
    </svg>

    <section class="pg-box" style="justify-items: center">
        <div class="i3sc-head">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Scene 1 — the empty state', 'صحنهٔ ۱ — حالت خالی') }}</h3>
            <p>
                {{ $say('An empty screen is a promise, not an apology. The open-box icon lands first and the copy is composed around it; the primary action answers inline, right where you clicked.', 'صفحهٔ خالی یک وعده است، نه عذرخواهی. آیکون جعبهٔ باز اول می‌نشیند و متن دورش چیده می‌شود؛ اکشن اصلی هم همان‌جا که کلیک کردید، درجا جواب می‌دهد.') }}
            </p>
        </div>

        <div class="i3sc-empty">
            <svg class="i3sc-empty-icon" viewBox="0 0 64 64" role="img" aria-label="{{ $say('An open, empty box', 'یک جعبهٔ باز و خالی') }}">
                <ellipse class="i3sc-sh" cx="32" cy="53" rx="17" ry="4.5"/>
                <path d="M14 30h36v14a5 5 0 0 1-5 5H19a5 5 0 0 1-5-5Z" fill="url(#i3sc-body)"/>
                <path d="M14 30 6 24v9l8 5Z" fill="url(#i3sc-accent)"/>
                <path d="M50 30l8-6v9l-8 5Z" fill="url(#i3sc-accent)"/>
                <rect x="18" y="27" width="28" height="4.5" rx="2.25" fill="#0f172a" opacity=".22"/>
                <path d="M41 7l1.9 4.3L47 13l-4.1 1.7L41 19l-1.9-4.3L35 13l4.1-1.7Z" fill="url(#i3sc-accent)"/>
                <path d="M20 13.5l1.2 2.7 2.7 1.2-2.7 1.2-1.2 2.7-1.2-2.7-2.7-1.2 2.7-1.2Z" fill="url(#i3sc-accent)" opacity=".7"/>
                <rect x="18" y="33" width="14" height="4" rx="2" fill="url(#i3sc-gloss)" opacity=".55"/>
            </svg>
            <h4>{{ $say('Your workspace is empty', 'فضای کاری شما خالی است') }}</h4>
            <p>
                {{ $say('Create your first Northlight project or start from a ready template — setup takes under two minutes.', 'اولین پروژهٔ Northlight را بسازید یا با یک قالب آماده شروع کنید؛ راه‌اندازی کمتر از دو دقیقه طول می‌کشد.') }}
            </p>
            <div class="i3sc-actions">
                <button type="button" class="i3sc-btn" x-on:click="created = true">{{ $say('Create first project', 'ساخت اولین پروژه') }}</button>
                <button type="button" class="i3sc-btn i3sc-btn-ghost">{{ $say('Browse templates', 'مرور قالب‌ها') }}</button>
            </div>
            <p class="i3sc-note" x-show="created" x-cloak role="status">
                {{ $say('Preparing your repository in Frankfurt… just a few seconds.', 'در حال آماده‌سازی مخزن شما در فرانکفورت… چند ثانیه بیشتر نیست.') }}
            </p>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center">
        <div class="i3sc-head">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Scene 2 — the feature row', 'صحنهٔ ۲ — ردیف قابلیت‌ها') }}</h3>
            <p>
                {{ $say('Three pillars of the Northlight pitch. The icon is the first thing the eye lands on and the column is built under it — remove the icon and the row loses its skeleton.', 'سه ستون پیچ محصول Northlight. آیکون اولین چیزی است که چشم رویش می‌ایستد و ستون زیرش ساخته می‌شود — آیکون را بردارید، ردیف اسکلتش را از دست می‌دهد.') }}
            </p>
        </div>

        <div class="i3sc-feats">
            <article class="i3sc-feat">
                <svg viewBox="0 0 64 64" role="img" aria-label="{{ $say('Deploy in 90 seconds', 'استقرار در ۹۰ ثانیه') }}">
                    <ellipse class="i3sc-sh" cx="32" cy="54" rx="13" ry="4"/>
                    <path d="M36 6 16 36h12l-4 22 20-30H32l4-22Z" fill="url(#i3sc-body)"/>
                    <path d="M36 6 16 36" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" opacity=".55"/>
                    <circle cx="47" cy="14" r="2.4" fill="url(#i3sc-accent)"/>
                </svg>
                <h4>{{ $say('Deploy in 90 seconds', 'استقرار در ۹۰ ثانیه') }}</h4>
                <p>{{ $say('Every commit builds and ships automatically to Frankfurt, Dublin or Singapore — no pipeline to babysit.', 'هر کامیت به‌صورت خودکار در فرانکفورت، دوبلین یا سنگاپور ساخته و منتشر می‌شود — بدون نظارت روی هیچ خط لوله‌ای.') }}</p>
                <a class="i3sc-link" href="/components">{{ $say('How deploys work', 'استقرار چطور کار می‌کند') }} <span aria-hidden="true">→</span></a>
            </article>

            <article class="i3sc-feat">
                <svg viewBox="0 0 64 64" role="img" aria-label="{{ $say('Growth forecasting', 'پیش‌بینی رشد') }}">
                    <ellipse class="i3sc-sh" cx="33" cy="55" rx="17" ry="4"/>
                    <polygon points="20,40 24,36 24,48 20,52" fill="url(#i3sc-accent)"/>
                    <polygon points="10,40 14,36 24,36 20,40" fill="#fff" opacity=".5"/>
                    <rect x="10" y="40" width="10" height="12" rx="2" fill="url(#i3sc-body)"/>
                    <polygon points="34,32 38,28 38,48 34,52" fill="url(#i3sc-accent)"/>
                    <polygon points="24,32 28,28 38,28 34,32" fill="#fff" opacity=".5"/>
                    <rect x="24" y="32" width="10" height="20" rx="2" fill="url(#i3sc-body)"/>
                    <path d="M13 30 27 16l8 6 16-13" fill="none" stroke="url(#i3sc-accent)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M52.5 5.5 54 15l-9.5-3.5Z" fill="url(#i3sc-accent)"/>
                </svg>
                <h4>{{ $say('Growth forecasting', 'پیش‌بینی رشد') }}</h4>
                <p>{{ $say('See each service’s spend 30 days ahead and get warned before the invoice, not after.', 'هزینهٔ هر سرویس را ۳۰ روز جلوتر ببینید و پیش از رسیدن فاکتور خبردار شوید، نه بعد از آن.') }}</p>
                <a class="i3sc-link" href="/components">{{ $say('See the forecast model', 'مدل پیش‌بینی را ببینید') }} <span aria-hidden="true">→</span></a>
            </article>

            <article class="i3sc-feat">
                <svg viewBox="0 0 64 64" role="img" aria-label="{{ $say('Enterprise-grade security', 'امنیت سازمانی') }}">
                    <ellipse class="i3sc-sh" cx="32" cy="54" rx="16" ry="4"/>
                    <path d="M32 8l18 6.5V29c0 11.5-7.4 19.9-18 25.2C23.4 48.9 14 40.5 14 29V14.5Z" fill="url(#i3sc-body)"/>
                    <path d="M25 29l5.2 5.2L40 24" fill="none" stroke="#fff" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" opacity=".9"/>
                    <path d="M24.5 12.7 32 10l7.5 2.7" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" opacity=".5"/>
                </svg>
                <h4>{{ $say('Enterprise-grade security', 'امنیت در سطح سازمانی') }}</h4>
                <p>{{ $say('End-to-end encryption, SSO and audit logs from day one — compliance without the paperwork marathon.', 'رمزنگاری سرتاسری، SSO و گزارش ممیزی از روز اول — انطباق بدون ماراتن کاغذبازی.') }}</p>
                <a class="i3sc-link" href="/components">{{ $say('Read the security brief', 'خلاصهٔ امنیتی را بخوانید') }} <span aria-hidden="true">→</span></a>
            </article>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center">
        <div class="i3sc-head">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Scene 3 — the pricing plans', 'صحنهٔ ۳ — پلن‌های قیمت') }}</h3>
            <p>
                {{ $say('Rocket for the launch, stacked layers for growth, a crown for scale. Flip the billing segment and every card recomputes in place — annual saves roughly 20%.', 'موشک برای شروع، لایه‌های روی هم برای رشد، تاج برای مقیاس. سگمنت صورتحساب را بزنید تا هر سه کارت درجا بازمحاسبه شوند — سالانه حدود ۲۰٪ کم‌تر است.') }}
            </p>
        </div>

        <div class="i3sc-billing">
            <div class="i3sc-seg" role="radiogroup" aria-label="{{ $say('Billing cycle', 'چرخهٔ صورتحساب') }}">
                <button type="button" role="radio" :aria-checked="cycle === 'monthly'" x-on:click="cycle = 'monthly'">
                    {{ $say('Monthly', 'ماهانه') }}
                </button>
                <button type="button" role="radio" :aria-checked="cycle === 'annual'" x-on:click="cycle = 'annual'">
                    {{ $say('Annual', 'سالانه') }}
                </button>
            </div>
            <span class="i3sc-save">{{ $say('save ~20% annually', 'سالانه حدود ۲۰٪ سود') }}</span>
        </div>

        <div class="i3sc-plans">
            <article class="i3sc-plan">
                <svg viewBox="0 0 64 64" role="img" aria-label="{{ $say('Launch plan', 'پلن پرواز') }}">
                    <ellipse class="i3sc-sh" cx="32" cy="55" rx="15" ry="4.5"/>
                    <path d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z" fill="url(#i3sc-accent)"/>
                    <path d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z" fill="url(#i3sc-accent)"/>
                    <path d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z" fill="url(#i3sc-accent)"/>
                    <path d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z" fill="url(#i3sc-body)"/>
                    <circle cx="32" cy="26" r="5.5" fill="url(#i3sc-accent)"/>
                    <circle cx="30.2" cy="24.2" r="1.6" fill="#fff" opacity=".8"/>
                    <ellipse cx="27.5" cy="20" rx="2.3" ry="6" transform="rotate(14 27.5 20)" fill="url(#i3sc-gloss)"/>
                </svg>
                <h4>{{ $say('Launch', 'پرواز') }}</h4>
                <p class="i3sc-price">
                    <b x-text="cycle === 'monthly' ? plans.launch.m : plans.launch.a"></b>
                    <small x-text="cycle === 'monthly' ? labelM : labelA"></small>
                </p>
                <ul class="i3sc-perks">
                    <li>{{ $say('3 projects', $num('3') . ' پروژه') }}</li>
                    <li>{{ $say('GitHub deploys', 'استقرار از GitHub') }}</li>
                    <li>{{ $say('Community support', 'پشتیبانی جامعه') }}</li>
                </ul>
                <button type="button" class="i3sc-btn i3sc-btn-ghost">{{ $say('Start with Launch', 'شروع با پرواز') }}</button>
            </article>

            <article class="i3sc-plan" data-featured>
                <span class="i3sc-badge">{{ $say('Most popular', 'محبوب‌ترین') }}</span>
                <svg viewBox="0 0 64 64" role="img" aria-label="{{ $say('Growth plan', 'پلن رشد') }}">
                    <ellipse class="i3sc-sh" cx="32" cy="54" rx="18" ry="4"/>
                    <path d="M13 29.5 32 39l19-9.5v8L32 47.5 13 37.5Z" fill="url(#i3sc-s5)"/>
                    <path d="M13 21.5 32 31l19-9.5v8L32 39 13 29.5Z" fill="url(#i3sc-body)"/>
                    <path d="M32 12l19 9.5L32 31l-19-9.5Z" fill="url(#i3sc-accent)"/>
                    <path d="M32 12l19 9.5L32 31" fill="none" stroke="#fff" stroke-width="1.8" stroke-linejoin="round" opacity=".4"/>
                </svg>
                <h4>{{ $say('Growth', 'رشد') }}</h4>
                <p class="i3sc-price">
                    <b x-text="cycle === 'monthly' ? plans.growth.m : plans.growth.a"></b>
                    <small x-text="cycle === 'monthly' ? labelM : labelA"></small>
                </p>
                <ul class="i3sc-perks">
                    <li>{{ $say('Unlimited projects', 'پروژهٔ نامحدود') }}</li>
                    <li>{{ $say('Preview environments', 'محیط‌های پیش‌نمایش') }}</li>
                    <li>{{ $say('4-hour support', 'پشتیبانی ۴ ساعته') }}</li>
                </ul>
                <button type="button" class="i3sc-btn">{{ $say('Choose Growth', 'انتخاب رشد') }}</button>
            </article>

            <article class="i3sc-plan">
                <svg viewBox="0 0 64 64" role="img" aria-label="{{ $say('Scale plan', 'پلن مقیاس') }}">
                    <ellipse class="i3sc-sh" cx="32" cy="54" rx="16" ry="4"/>
                    <path d="M13 42 9 22l12.5 8L32 15l10.5 15L55 22l-4 20Z" fill="url(#i3sc-body)"/>
                    <path d="M13 42h38v6a3 3 0 0 1-3 3H16a3 3 0 0 1-3-3Z" fill="url(#i3sc-accent)"/>
                    <circle cx="9" cy="19.5" r="2.4" fill="url(#i3sc-accent)"/>
                    <circle cx="32" cy="12.5" r="2.4" fill="url(#i3sc-accent)"/>
                    <circle cx="55" cy="19.5" r="2.4" fill="url(#i3sc-accent)"/>
                    <circle cx="22" cy="47" r="1.7" fill="#fff" opacity=".75"/>
                    <circle cx="32" cy="47" r="1.7" fill="#fff" opacity=".75"/>
                    <circle cx="42" cy="47" r="1.7" fill="#fff" opacity=".75"/>
                    <path d="M14 38 11 23.5" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" opacity=".4"/>
                </svg>
                <h4>{{ $say('Scale', 'مقیاس') }}</h4>
                <p class="i3sc-price">
                    <b x-text="cycle === 'monthly' ? plans.scale.m : plans.scale.a"></b>
                    <small x-text="cycle === 'monthly' ? labelM : labelA"></small>
                </p>
                <ul class="i3sc-perks">
                    <li>{{ $say('Dedicated regions', 'نواحی اختصاصی') }}</li>
                    <li>{{ $say('Audit log', 'گزارش ممیزی') }}</li>
                    <li>{{ $say('1-hour support', 'پشتیبانی ۱ ساعته') }}</li>
                </ul>
                <button type="button" class="i3sc-btn i3sc-btn-ghost">{{ $say('Talk to sales', 'گفت‌وگو با فروش') }}</button>
            </article>
        </div>
    </section>
</div>
