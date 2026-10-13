{{--
    The pricing section as a real product page slice: the interval toggle
    bumps all three prices at once and flashes the savings badge, the Growth
    card stands out on a dark surface, the comparison grid answers the
    "what exactly do I get" question and the guarantee row closes the deal.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $plans = [
        'starter' => [
            'name' => $say('Starter', 'استارتر'), 'tag' => $say('For side projects', 'برای پروژه‌های شخصی'),
            'monthly' => 12, 'yearly' => 9,
            'perks' => [
                $say('3 team seats', '۳ عضو تیم'),
                $say('5 active projects', '۵ پروژهٔ فعال'),
                $say('50K API calls / mo', '۵۰ هزار درخواست API در ماه'),
                $say('Community support', 'پشتیبانی انجمن'),
            ],
        ],
        'growth' => [
            'name' => $say('Growth', 'رشد'), 'tag' => $say('For shipping teams', 'برای تیم‌های در حال عرضه'),
            'monthly' => 29, 'yearly' => 23, 'featured' => true,
            'perks' => [
                $say('10 team seats', '۱۰ عضو تیم'),
                $say('25 active projects', '۲۵ پروژهٔ فعال'),
                $say('500K API calls / mo', '۵۰۰ هزار درخواست API در ماه'),
                $say('Priority support', 'پشتیبانی اولویت‌دار'),
                $say('Usage alerts', 'هشدار مصرف'),
            ],
        ],
        'scale' => [
            'name' => $say('Scale', 'مقیاس'), 'tag' => $say('For platforms', 'برای پلتفرم‌ها'),
            'monthly' => 79, 'yearly' => 63,
            'perks' => [
                $say('Unlimited seats', 'عضو نامحدود'),
                $say('Unlimited projects', 'پروژهٔ نامحدود'),
                $say('SSO / SAML', 'ورود تک‌نشان SSO/SAML'),
                $say('Audit log', 'گزارش حسابرسی'),
                $say('Dedicated engineer', 'مهندس اختصاصی'),
            ],
        ],
    ];

    // Feature comparison rows: label + [starter, growth, scale]; values are
    // 'yes' (tick), 'no' (dash) or a literal string.
    $comparison = [
        [$say('Team seats', 'عضو تیم'), $num('3'), $num('10'), $say('Unlimited', 'نامحدود')],
        [$say('Active projects', 'پروژهٔ فعال'), $num('5'), $num('25'), $say('Unlimited', 'نامحدود')],
        [$say('API calls / month', 'درخواست API در ماه'), $num('50K'), $num('500K'), $say('Unlimited', 'نامحدود')],
        [$say('Realtime dashboards', 'داشبورد زمان‌واقعی'), 'yes', 'yes', 'yes'],
        [$say('SSO / SAML', 'ورود تک‌نشان SSO/SAML'), 'no', 'no', 'yes'],
        [$say('Audit log', 'گزارش حسابرسی'), 'no', 'yes', 'yes'],
        [$say('Support', 'پشتیبانی'), $say('Community', 'انجمن'), $say('Priority', 'اولویت‌دار'), $say('Dedicated', 'اختصاصی')],
    ];
@endphp
<style>
    .secpr-root {
        --secpr-accent: #4F46E5; --secpr-accent-ink: #EEF2FF;
        --secpr-page: #FAFAF9; --secpr-card: #FFFFFF; --secpr-border: #E7E5E4;
        --secpr-text: #1C1917; --secpr-muted: #78716C;
        --secpr-dark: #171512; --secpr-dark-text: #FAFAF9; --secpr-dark-muted: #A8A29E;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--secpr-text);
        display: grid;
        gap: 2rem;
    }
    html[data-theme="dark"] .secpr-root {
        --secpr-accent: #818CF8; --secpr-accent-ink: #312E81;
        --secpr-page: #0C0A09; --secpr-card: #1C1917; --secpr-border: #292524;
        --secpr-text: #FAFAF9; --secpr-muted: #A8A29E;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .secpr-root {
            --secpr-accent: #818CF8; --secpr-accent-ink: #312E81;
            --secpr-page: #0C0A09; --secpr-card: #1C1917; --secpr-border: #292524;
            --secpr-text: #FAFAF9; --secpr-muted: #A8A29E;
        }
    }
    .secpr {
        inline-size: 100%; padding: clamp(1.75rem, 4vw, 3.25rem) clamp(1.25rem, 4vw, 2.5rem);
        border: 1px solid var(--secpr-border); border-radius: 1.4rem; background: var(--secpr-page);
        display: grid; gap: 2rem; justify-items: center; overflow: clip;
    }
    .secpr-head { display: grid; gap: .9rem; justify-items: center; text-align: center; }
    .secpr-eyebrow { display: inline-flex; align-items: center; gap: .45rem; padding: .3rem .8rem;
                     border: 1px solid var(--secpr-border); border-radius: 999px;
                     font-size: .75rem; font-weight: 600; color: var(--secpr-muted); }
    .secpr-eyebrow i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: #22C55E; }
    .secpr-head h3 { margin: 0; font-size: clamp(1.5rem, 3.4vw, 2.15rem); font-weight: 800;
                     letter-spacing: -.02em; line-height: 1.2; }
    .secpr-head p { margin: 0; max-inline-size: 46ch; font-size: .92rem; line-height: 1.7; color: var(--secpr-muted); }
    .secpr-toggle { position: relative; display: inline-grid; grid-auto-flow: column;
                    border: 1px solid var(--secpr-border); border-radius: 999px; background: var(--secpr-card); }
    .secpr-toggle::before { content: ''; position: absolute; inset-block: 3px; inset-inline-start: 3px;
                            inline-size: calc(50% - 3px); border-radius: 999px; background: var(--secpr-accent);
                            transition: translate .28s cubic-bezier(.2, 0, 0, 1); }
    .secpr-toggle[data-yearly="true"]::before { translate: 100% 0; }
    html[dir="rtl"] .secpr-toggle[data-yearly="true"]::before { translate: -100% 0; }
    .secpr-toggle button { position: relative; z-index: 1; display: inline-flex; align-items: center; gap: .5rem;
                           padding: .6rem 1.25rem; border: none; background: transparent; border-radius: 999px;
                           font: 600 .85rem/1.2 inherit; color: var(--secpr-muted); cursor: pointer;
                           transition: color .28s ease; }
    .secpr-toggle button[aria-pressed="true"] { color: #fff; }
    .secpr-save { display: inline-flex; padding: .18rem .55rem; border-radius: 999px;
                  background: var(--secpr-accent-ink); color: var(--secpr-accent);
                  font-size: .68rem; font-weight: 700; font-style: normal; white-space: nowrap; }
    .secpr-toggle button[aria-pressed="true"] .secpr-save { background: rgba(255, 255, 255, .18); color: #fff; }
    html[data-theme="dark"] .secpr-toggle button[aria-pressed="true"] { color: #1C1917; }
    html[data-theme="dark"] .secpr-toggle button[aria-pressed="true"] .secpr-save { background: rgba(0, 0, 0, .22); color: #1C1917; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .secpr-toggle button[aria-pressed="true"] { color: #1C1917; }
        html:not([data-theme="light"]) .secpr-toggle button[aria-pressed="true"] .secpr-save { background: rgba(0, 0, 0, .22); color: #1C1917; }
    }
    .secpr-cards { display: grid; gap: 1.25rem; grid-template-columns: repeat(3, minmax(0, 1fr));
                   align-items: start; inline-size: 100%; max-inline-size: 62rem; }
    .secpr-card { position: relative; display: grid; gap: 1rem; align-content: start;
                  padding: 1.6rem 1.4rem; border: 1px solid var(--secpr-border); border-radius: 1.25rem;
                  background: var(--secpr-card); transition: border-color .2s ease, translate .2s ease, box-shadow .2s ease; }
    .secpr-card:hover { border-color: color-mix(in oklab, var(--secpr-accent) 45%, var(--secpr-border)); }
    .secpr-card[data-featured="true"] { background: var(--secpr-dark); border-color: var(--secpr-dark); color: var(--secpr-dark-text);
                                        translate: 0 -1rem; box-shadow: 0 24px 48px -20px rgba(23, 21, 18, .45); }
    html[data-theme="dark"] .secpr-card[data-featured="true"] {
        box-shadow: inset 0 0 0 1px color-mix(in oklab, var(--secpr-accent) 45%, transparent);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .secpr-card[data-featured="true"] {
            box-shadow: inset 0 0 0 1px color-mix(in oklab, var(--secpr-accent) 45%, transparent);
        }
    }
    .secpr-pop { position: absolute; inset-block-start: -.7rem; inset-inline-start: 50%; translate: -50% 0;
                 padding: .28rem .8rem; border-radius: 999px; background: var(--secpr-accent); color: #fff;
                 font-size: .7rem; font-weight: 700; white-space: nowrap; }
    html[dir="rtl"] .secpr-pop { translate: 50% 0; }
    .secpr-card h4 { margin: 0; display: grid; gap: .2rem; font-size: 1.02rem; font-weight: 700; }
    .secpr-card h4 small { font-size: .74rem; font-weight: 500; color: var(--secpr-muted); }
    .secpr-card[data-featured="true"] h4 small { color: var(--secpr-dark-muted); }
    .secpr-price { margin: 0; display: flex; align-items: baseline; gap: .4rem; flex-wrap: wrap; }
    .secpr-amount { display: inline-block; font-size: 2.6rem; font-weight: 800; letter-spacing: -.03em; line-height: 1; }
    .secpr-amount.secpr-bump { animation: secpr-bump .38s cubic-bezier(.2, 0, 0, 1); }
    @keyframes secpr-bump { from { opacity: 0; translate: 0 .35em; } to { opacity: 1; translate: 0 0; } }
    .secpr-price small { font-size: .74rem; color: var(--secpr-muted); }
    .secpr-card[data-featured="true"] .secpr-price small { color: var(--secpr-dark-muted); }
    .secpr-perks { margin: 0; padding: 0; list-style: none; display: grid; gap: .5rem;
                   font-size: .84rem; line-height: 1.5; }
    .secpr-perks li { display: flex; align-items: start; gap: .5rem; }
    .secpr-perks svg { flex: none; inline-size: 1rem; block-size: 1rem; margin-block-start: .12rem;
                       stroke: var(--secpr-accent); stroke-width: 2.2; fill: none;
                       stroke-linecap: round; stroke-linejoin: round; }
    .secpr-cta { margin-top: .2rem; padding: .68rem 1rem; border: 1px solid var(--secpr-border); border-radius: .8rem;
                 background: transparent; color: inherit; font: 600 .85rem/1.2 inherit; cursor: pointer;
                 transition: background-color .2s ease, border-color .2s ease, color .2s ease; }
    .secpr-cta:hover { border-color: var(--secpr-accent); color: var(--secpr-accent); }
    .secpr-card[data-featured="true"] .secpr-cta { background: var(--secpr-accent); border-color: var(--secpr-accent); color: #fff; }
    .secpr-card[data-featured="true"] .secpr-cta:hover { filter: brightness(1.1); }
    .secpr-cta:focus-visible, .secpr-toggle button:focus-visible { outline: 2px solid var(--secpr-accent); outline-offset: 2px; }
    .secpr-cmpwrap { inline-size: 100%; max-inline-size: 62rem; display: grid; gap: .9rem; }
    .secpr-cmpwrap > h4, .secpr-guarantee-title { margin: 0; font-size: .95rem; font-weight: 700; }
    .secpr-cmp { overflow-x: auto; border: 1px solid var(--secpr-border); border-radius: 1.1rem;
                 background: var(--secpr-card); scrollbar-width: thin; }
    .secpr-cmp table { inline-size: 100%; min-inline-size: 34rem; border-collapse: collapse; font-size: .84rem; }
    .secpr-cmp th, .secpr-cmp td { padding: .72rem 1rem; text-align: start;
                                   border-block-start: 1px solid var(--secpr-border); }
    .secpr-cmp thead th { border-block-start: none; font-size: .74rem; color: var(--secpr-muted); font-weight: 600; }
    .secpr-cmp thead th[data-featured="true"] { color: var(--secpr-accent); }
    .secpr-cmp tbody th { font-weight: 500; }
    .secpr-cmp td { text-align: center; color: var(--secpr-muted); }
    .secpr-cmp td[data-featured="true"] { color: var(--secpr-text); background: color-mix(in oklab, var(--secpr-accent) 6%, transparent); }
    .secpr-cmp svg { inline-size: 1.05rem; block-size: 1.05rem; vertical-align: -.18em; }
    .secpr-cmp svg[data-tick] { stroke: var(--secpr-accent); }
    .secpr-cmp svg[data-dash] { stroke: var(--secpr-muted); opacity: .5; }
    .secpr-note { margin: 0; font-size: .74rem; color: var(--secpr-muted); }
    .secpr-guarantee { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: .6rem 1.25rem;
                       padding: 1rem 1.25rem; border: 1px dashed var(--secpr-border); border-radius: 1.1rem;
                       font-size: .84rem; color: var(--secpr-muted); text-align: center; }
    .secpr-guarantee > span { display: inline-flex; align-items: center; gap: .5rem; color: var(--secpr-text); font-weight: 600; }
    .secpr-guarantee svg { inline-size: 1.15rem; block-size: 1.15rem; stroke: var(--secpr-accent); stroke-width: 2;
                           fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .secpr-guarantee a { color: var(--secpr-accent); font-weight: 600; text-decoration: none; }
    .secpr-guarantee a:hover { text-decoration: underline; }
    @media (max-width: 640px) {
        .secpr-cards { grid-template-columns: 1fr; }
        .secpr-card[data-featured="true"] { translate: none; }
        .secpr-toggle { inline-size: 100%; }
        .secpr-toggle button { flex: 1; justify-content: center; }
    }
    @media (prefers-reduced-motion: reduce) {
        .secpr-root *, .secpr-root *::before { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="secpr-root"
    x-data="{
        yearly: true,
        touched: false,
        isFa: @js($fa),
        fmt(n) { return this.isFa ? String(n).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n); },
        bump(el) {
            if (!this.touched) { return; }
            el.classList.remove('secpr-bump');
            void el.offsetWidth;
            el.classList.add('secpr-bump');
        },
    }">
    <section class="secpr" :data-yearly="yearly" aria-labelledby="secpr-title">
        <header class="secpr-head">
            <span class="secpr-eyebrow"><i aria-hidden="true"></i>{{ $say('Beacon · Billing', 'بیکِن · صورت‌حساب') }}</span>
            <h3 id="secpr-title">{{ $say('Pricing that scales with you, not against you', 'قیمتی که با شما مقیاس می‌گیرد، نه علیه شما') }}</h3>
            <p>{{ $say('Start free, upgrade when the team grows. Every plan runs on the same infrastructure — Frankfurt, Dublin and Singapore — only the limits differ.', 'از رایگان شروع کنید و با رشد تیم ارتقا بدهید. همهٔ پلن‌ها روی همان زیرساخت اجرا می‌شوند — فرانکفورت، دوبلین و سنگاپور — فقط سقف‌ها فرق دارند.') }}</p>
        </header>

        <div class="secpr-toggle" :data-yearly="yearly" role="group" aria-label="{{ $say('Billing interval', 'دورهٔ پرداخت') }}">
            <button type="button" :aria-pressed="String(!yearly)"
                x-on:click="touched = true; yearly = false">{{ $say('Monthly', 'ماهانه') }}</button>
            <button type="button" :aria-pressed="String(yearly)"
                x-on:click="touched = true; yearly = true">
                {{ $say('Yearly', 'سالانه') }}
                <em class="secpr-save" x-show="yearly" x-cloak>{{ $say('2 months free', '۲ ماه رایگان') }}</em>
            </button>
        </div>

        <div class="secpr-cards">
            @foreach ($plans as $plan)
                <article class="secpr-card" data-featured="{{ isset($plan['featured']) ? 'true' : 'false' }}">
                    @if (isset($plan['featured']))
                        <span class="secpr-pop">{{ $say('Most popular', 'محبوب‌ترین') }}</span>
                    @endif
                    <h4>{{ $plan['name'] }} <small>{{ $plan['tag'] }}</small></h4>
                    <p class="secpr-price">
                        <span class="secpr-amount" x-text="fmt(yearly ? {{ $plan['yearly'] }} : {{ $plan['monthly'] }})"
                            x-effect="bump($el)"></span>
                        <small>{{ $say('$ per seat / month, billed ', 'دلار به‌ازای هر نفر در ماه، ') }}<span x-text="yearly ? {{ $say("'yearly'", "'سالانه'") }} : {{ $say("'monthly'", "'ماهانه'") }}"></span></small>
                    </p>
                    <ul class="secpr-perks">
                        @foreach ($plan['perks'] as $perk)
                            <li>
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                                {{ $perk }}
                            </li>
                        @endforeach
                    </ul>
                    <button type="button" class="secpr-cta"
                        @if (isset($plan['featured'])) {{ $say('Start the 14-day trial', 'شروع دورهٔ ۱۴ روزهٔ آزمایشی') }} @else {{ $say('Choose', 'انتخاب') }} {{ $plan['name'] }} @endif></button>
                </article>
            @endforeach
        </div>

        <div class="secpr-cmpwrap">
            <h4>{{ $say('Compare what each plan includes', 'مقایسهٔ آنچه هر پلن در بر دارد') }}</h4>
            <div class="secpr-cmp" tabindex="0" role="group" aria-label="{{ $say('Feature comparison', 'مقایسهٔ ویژگی‌ها') }}">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">{{ $say('Feature', 'ویژگی') }}</th>
                            <th scope="col">{{ $plans['starter']['name'] }}</th>
                            <th scope="col" data-featured="true">{{ $plans['growth']['name'] }}</th>
                            <th scope="col">{{ $plans['scale']['name'] }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comparison as [$label, $a, $b, $c])
                            <tr>
                                <th scope="row">{{ $label }}</th>
                                @foreach ([$a, $b, $c] as $i => $value)
                                    <td data-featured="{{ $i === 1 ? 'true' : 'false' }}">
                                        @if ($value === 'yes')
                                            <svg viewBox="0 0 24 24" data-tick aria-hidden="true" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 12.5l5 5 10-11"/></svg>
                                            <span class="sr-only">{{ $say('Included', 'موجود') }}</span>
                                        @elseif ($value === 'no')
                                            <svg viewBox="0 0 24 24" data-dash aria-hidden="true" fill="none" stroke-width="2.4" stroke-linecap="round"><path d="M6 12h12"/></svg>
                                            <span class="sr-only">{{ $say('Not included', 'موجود نیست') }}</span>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="secpr-note">{{ $say('Prices exclude VAT. Switching plans is prorated to the minute — no restarts, no migrations.', 'قیمت‌ها بدون مالیات بر ارزش افزوده است. تغییر پلن تا دقیقه دقیق‌حساب می‌شود — بدون ری‌استارت، بدون مهاجرت.') }}</p>
        </div>

        <p class="secpr-guarantee">
            <span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7 3v5c0 4.4-3 8.4-7 9.5C8 19.4 5 15.4 5 11V6l7-3z"/><path d="M9 11.5l2 2 4-4.5"/></svg>
                {{ $say('30-day money-back guarantee', 'ضمانت بازگشت وجه ۳۰ روزه') }}
            </span>
            {{ $say('— no questions asked. Still deciding?', '— بدون هیچ پرسشی. هنوز مطمئن نیستید؟') }}
            <a href="#">{{ $say('Talk to our Dublin team', 'با تیم دوبلین ما حرف بزنید') }}</a>
        </p>
    </section>
</div>
