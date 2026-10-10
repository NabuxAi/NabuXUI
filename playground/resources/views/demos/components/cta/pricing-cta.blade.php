{{--
    Pricing CTA in its real habitat: the conversion card of a SaaS pricing
    page — monthly/yearly toggle, a price that rolls over when the period
    flips, a savings badge that only shows on yearly, the primary upgrade
    button and the money-back guarantee line, with a trust row underneath.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .ctpr-root {
        --ctpr-card: #ffffff; --ctpr-border: #e5e7eb; --ctpr-text: #141726; --ctpr-muted: #6c7088;
        --ctpr-well: #eeeff4; --ctpr-accent: #5647e6; --ctpr-accent-deep: #4739ca;
        --ctpr-green-soft: #10b98126; --ctpr-green: #047857;
        display: grid; gap: 1.75rem; justify-items: center;
        font-family: var(--nx-font-sans);
    }
    html[data-theme="dark"] .ctpr-root {
        --ctpr-card: #141726; --ctpr-border: #2a2d40; --ctpr-text: #f7f7fa; --ctpr-muted: #9a9db3;
        --ctpr-well: #1d2031; --ctpr-accent: #8482fb; --ctpr-accent-deep: #6a63f4;
        --ctpr-green-soft: #10b98126; --ctpr-green: #3ddc97;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ctpr-root {
            --ctpr-card: #141726; --ctpr-border: #2a2d40; --ctpr-text: #f7f7fa; --ctpr-muted: #9a9db3;
            --ctpr-well: #1d2031; --ctpr-accent: #8482fb; --ctpr-accent-deep: #6a63f4;
            --ctpr-green-soft: #10b98126; --ctpr-green: #3ddc97;
        }
    }
    .ctpr-card { position: relative; display: grid; gap: 1.15rem; justify-items: center; text-align: center;
                 inline-size: min(100%, 24.5rem); padding: 2rem 1.6rem 1.6rem; border: 1px solid var(--ctpr-border);
                 border-radius: var(--nx-radius-xl); background: var(--ctpr-card);
                 box-shadow: 0 18px 44px #0a0c171a; overflow: clip; }
    .ctpr-card::before { content: ''; position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 4px;
                         background: linear-gradient(90deg, var(--ctpr-accent), #c026d3); }
    .ctpr-head { display: grid; gap: .55rem; justify-items: center; }
    .ctpr-fav { display: inline-flex; align-items: center; gap: .35rem; padding: .3rem .75rem; border-radius: 999px;
                background: var(--nx-accent-soft, #5647e61f); color: var(--ctpr-accent); font-size: .72rem; font-weight: 700;
                letter-spacing: .04em; text-transform: uppercase; }
    .ctpr-fav svg { inline-size: .9rem; block-size: .9rem; }
    .ctpr-plan { margin: 0; font: 700 var(--nx-text-2xl) / 1.2 var(--nx-font-display); color: var(--ctpr-text); }
    .ctpr-desc { margin: 0; max-inline-size: 30ch; color: var(--ctpr-muted); font-size: var(--nx-text-sm); }
    .ctpr-toggle { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; background: var(--ctpr-well); }
    .ctpr-seg { display: inline-flex; align-items: center; gap: .4rem; block-size: 2.3rem; padding-inline: 1.05rem;
                border: none; border-radius: 999px; background: transparent; color: var(--ctpr-muted);
                font: 600 var(--nx-text-sm) / 1 var(--nx-font-sans); cursor: pointer;
                transition: background-color .18s ease, color .18s ease, box-shadow .18s ease; }
    .ctpr-seg:hover { color: var(--ctpr-text); }
    .ctpr-seg:focus-visible { outline: 2px solid var(--ctpr-accent); outline-offset: 2px; }
    .ctpr-seg.ctpr-on { background: var(--ctpr-card); color: var(--ctpr-text); box-shadow: 0 2px 8px #0a0c1726; }
    .ctpr-seg-emu { font-size: .68rem; font-weight: 700; color: var(--ctpr-green); }
    .ctpr-figure { display: flex; flex-wrap: wrap; align-items: baseline; justify-content: center; gap: .35rem .45rem; }
    .ctpr-cur { font-weight: 700; color: var(--ctpr-text); font-size: var(--nx-text-xl); }
    .ctpr-nums { display: grid; }
    .ctpr-num { grid-area: 1 / 1; font: 800 clamp(2.9rem, 8vw, 3.4rem) / 1 var(--nx-font-display);
                letter-spacing: var(--nx-tracking-tight); color: var(--ctpr-text); }
    .ctpr-per { color: var(--ctpr-muted); font-size: var(--nx-text-sm); }
    html[dir="rtl"] .ctpr-cur { order: 2; }
    html[dir="rtl"] .ctpr-nums { order: 1; }
    html[dir="rtl"] .ctpr-per { order: 3; }
    .ctpr-roll { transition: opacity .24s ease, translate .24s ease; }
    .ctpr-from-below { opacity: 0; translate: 0 .45em; }
    .ctpr-to-above { opacity: 0; translate: 0 -.45em; }
    .ctpr-fade { transition: opacity .2s ease, scale .2s ease; }
    .ctpr-fade-start { opacity: 0; scale: .92; }
    .ctpr-save { display: inline-flex; align-items: center; gap: .45rem; padding: .38rem .85rem; border-radius: 999px;
                 background: var(--ctpr-green-soft); color: var(--ctpr-green); font-size: .78rem; font-weight: 600; }
    .ctpr-save svg { inline-size: 1rem; block-size: 1rem; }
    .ctpr-bill { margin: 0; color: var(--ctpr-muted); font-size: .75rem; min-block-size: 1.1em; }
    .ctpr-feats { display: grid; gap: .5rem; justify-items: start; inline-size: 100%;
                  padding-block: .35rem; list-style: none; margin: 0; }
    .ctpr-feats li { display: flex; align-items: center; gap: .55rem; color: var(--ctpr-text); font-size: var(--nx-text-sm); }
    .ctpr-feats svg { flex: none; inline-size: 1.05rem; block-size: 1.05rem; color: var(--ctpr-green); }
    .ctpr-go { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; inline-size: 100%;
               block-size: 3rem; border: none; border-radius: 999px; background: var(--ctpr-accent); color: #fff;
               font: 700 var(--nx-text-md) / 1 var(--nx-font-sans); text-decoration: none; cursor: pointer;
               transition: translate .18s ease, box-shadow .18s ease, background-color .18s ease; }
    .ctpr-go svg { inline-size: 1.05rem; block-size: 1.05rem; }
    html[dir="rtl"] .ctpr-go svg { transform: scaleX(-1); }
    .ctpr-go:hover { translate: 0 -2px; background: var(--ctpr-accent-deep); box-shadow: 0 12px 26px #5647e63d; }
    .ctpr-go:active { translate: 0 0; }
    .ctpr-go:focus-visible { outline: 2px solid var(--ctpr-accent); outline-offset: 3px; }
    .ctpr-guarantee { display: inline-flex; align-items: center; gap: .45rem; margin: 0; color: var(--ctpr-muted);
                      font-size: .76rem; }
    .ctpr-guarantee svg { flex: none; inline-size: .95rem; block-size: .95rem; color: var(--ctpr-accent); }
    .ctpr-trust { display: flex; flex-wrap: wrap; justify-content: center; gap: .6rem 1.75rem; }
    .ctpr-trust span { display: inline-flex; align-items: center; gap: .4rem; color: var(--nx-text-muted); font-size: var(--nx-text-sm); }
    .ctpr-trust svg { inline-size: .95rem; block-size: .95rem; color: var(--ctpr-accent); }
    @media (prefers-reduced-motion: reduce) {
        .ctpr-root *, .ctpr-root *::before { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="ctpr-root"
    x-data="{
        yearly: false,
        monthly: '{{ $num('19') }}',
        yearlyPrice: '{{ $num('15') }}',
        billMonthly: '{{ $say('billed monthly — cancel anytime', 'صورتحساب ماهانه — هر زمان لغو کنید') }}',
        billYearly: '{{ $say('billed once a year', 'یک‌بار در سال صورتحساب می‌شود') }}',
    }">
    <section aria-label="{{ $say('Pricing CTA card', 'کارت دعوت به کنش قیمتی') }}">
        <div class="ctpr-card">
            <div class="ctpr-head">
                <span class="ctpr-fav">{{ \NabuXUI\NabuXUI::icon('star') }}{{ $say('Most popular', 'محبوب‌ترین') }}</span>
                <h3 class="ctpr-plan">{{ $say('Pro plan', 'پلن Pro') }}</h3>
                <p class="ctpr-desc">{{ $say('For teams that take their releases seriously.', 'برای تیم‌هایی که انتشارشان را جدی می‌گیرند.') }}</p>
                <div class="ctpr-toggle" role="group" aria-label="{{ $say('Billing period', 'دورهٔ صورتحساب') }}">
                    <button type="button" class="ctpr-seg" :class="{ 'ctpr-on': !yearly }" :aria-pressed="!yearly" x-on:click="yearly = false">
                        {{ $say('Monthly', 'ماهانه') }}
                    </button>
                    <button type="button" class="ctpr-seg" :class="{ 'ctpr-on': yearly }" :aria-pressed="yearly" x-on:click="yearly = true">
                        {{ $say('Yearly', 'سالانه') }}<span class="ctpr-seg-emu">−{{ $num('20') }}٪</span>
                    </button>
                </div>
            </div>

            <div class="ctpr-figure">
                <span class="ctpr-cur">{{ $say('$', 'دلار') }}</span>
                <span class="ctpr-nums">
                    <span class="ctpr-num" x-show="!yearly" x-text="monthly"
                        x-transition:enter="ctpr-roll" x-transition:enter-start="ctpr-from-below"
                        x-transition:leave="ctpr-roll" x-transition:leave-end="ctpr-to-above"></span>
                    <span class="ctpr-num" x-show="yearly" x-cloak x-text="yearlyPrice"
                        x-transition:enter="ctpr-roll" x-transition:enter-start="ctpr-from-below"
                        x-transition:leave="ctpr-roll" x-transition:leave-end="ctpr-to-above"></span>
                </span>
                <span class="ctpr-per" x-text="yearly ? billYearly : billMonthly"></span>
            </div>

            <span class="ctpr-save" x-show="yearly" x-cloak
                x-transition:enter="ctpr-fade" x-transition:enter-start="ctpr-fade-start"
                x-transition:leave="ctpr-fade" x-transition:leave-end="ctpr-fade-start">
                {{ \NabuXUI\NabuXUI::icon('check-circle') }}
                {{ $say('You save $96 a year', $num('96') . ' دلار در سال صرفه‌جویی می‌کنید') }}
            </span>

            <ul class="ctpr-feats">
                <li>{{ \NabuXUI\NabuXUI::icon('check') }}{{ $say('Unlimited dashboards per workspace', 'داشبورد نامحدود در هر ورک‌اسپیس') }}</li>
                <li>{{ \NabuXUI\NabuXUI::icon('check') }}{{ $say('Slack alerts and webhooks', 'هشدارهای اسلک و وب‌هوک') }}</li>
                <li>{{ \NabuXUI\NabuXUI::icon('check') }}{{ $say('90 days of event retention', 'نگهداری ' . $num('90') . ' روزهٔ رویدادها') }}</li>
            </ul>

            <a class="ctpr-go" href="/components">
                {{ $say('Upgrade to Pro', 'ارتقای پلن Pro') }}{{ \NabuXUI\NabuXUI::icon('arrow-right') }}
            </a>
            <p class="ctpr-guarantee">
                {{ \NabuXUI\NabuXUI::icon('shield') }}
                {{ $say('30-day money-back guarantee — if it doesn’t click, one email is enough.', $num('30') . ' روز ضمانت بازگشت وجه — اگر جا نیفتاد، یک ایمیل کافی است.') }}
            </p>
        </div>

        <div class="ctpr-trust" style="margin-block-start: 1.25rem">
            <span>{{ \NabuXUI\NabuXUI::icon('check-circle') }}{{ $say('Cancel anytime', 'لغو در هر زمان') }}</span>
            <span>{{ \NabuXUI\NabuXUI::icon('lock') }}SOC 2 Type II</span>
            <span>{{ \NabuXUI\NabuXUI::icon('globe') }}{{ $say('EU & Singapore data residency', 'نگهداری داده در اروپا و سنگاپور') }}</span>
        </div>
    </section>
</div>
