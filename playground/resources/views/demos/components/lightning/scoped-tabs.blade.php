{{--
    Scoped Tabs framing a record page: four record sections, each painted
    in its own marketing colour that repaints the whole tab bar when
    selected, the active tab lit white with its colour bar on top, the
    content panel framed underneath, and the bar scrolling in place when the
    tabs outgrow the width. The specimen row shows the full palette.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap');
    .sly-root {
        --sly-blue: #0176D3; --sly-green: #04844B; --sly-orange: #FE9339; --sly-purple: #6739B7;
        --sly-teal: #0B827C; --sly-pink: #B32D69; --sly-yellow: #FFB75D; --sly-red: #BA0517;
        --sly-text: #181818; --sly-weak: #444444; --sly-muted: #706E6B;
        --sly-border: #DDDBDA; --sly-bg: #F3F3F3; --sly-card: #FFFFFF;
        font-family: 'Source Sans 3', 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        color: var(--sly-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .sly-root {
        --sly-blue: #0D9DDA; --sly-green: #0E9E5B; --sly-orange: #FE9339; --sly-purple: #A57DE8;
        --sly-teal: #12A39B; --sly-pink: #E96BA8; --sly-yellow: #FFB75D; --sly-red: #FE5C4C;
        --sly-text: #F3F3F3; --sly-weak: #CECECE; --sly-muted: #A5A5A5;
        --sly-border: #474747; --sly-bg: #181818; --sly-card: #232323;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .sly-root {
            --sly-blue: #0D9DDA; --sly-green: #0E9E5B; --sly-orange: #FE9339; --sly-purple: #A57DE8;
            --sly-teal: #12A39B; --sly-pink: #E96BA8; --sly-yellow: #FFB75D; --sly-red: #FE5C4C;
            --sly-text: #F3F3F3; --sly-weak: #CECECE; --sly-muted: #A5A5A5;
            --sly-border: #474747; --sly-bg: #181818; --sly-card: #232323;
        }
    }
    .sly-root, .sly-root *, .sly-root *::before, .sly-root *::after { box-sizing: border-box; }
    .sly-root :focus-visible { outline: 2px solid var(--sly-blue); outline-offset: 2px; }

    .sly-card { inline-size: min(100%, 46rem); margin-inline: auto; border: 1px solid var(--sly-tint, var(--sly-blue));
                border-radius: .25rem; background: var(--sly-card); overflow: clip; }
    .sly-bar { display: flex; inline-size: 100%; background: var(--sly-tint, var(--sly-blue)); padding-inline: .3rem;
               overflow-x: auto; scrollbar-width: none; transition: background .2s ease; }
    .sly-bar::-webkit-scrollbar { display: none; }
    .sly-tab { flex: none; position: relative; padding: .7rem 1.15rem; border: 0; background: transparent;
               color: color-mix(in srgb, #FFFFFF 88%, transparent); font: inherit; font-size: .85rem; font-weight: 600;
               cursor: pointer; white-space: nowrap; transition: color .15s ease, background .15s ease; }
    .sly-tab:hover { color: #FFFFFF; }
    .sly-tab[aria-selected="true"] { background: var(--sly-card); color: var(--sly-text); }
    .sly-tab[aria-selected="true"]::after { content: ''; position: absolute; inset-inline: 0; inset-block-start: 0;
               block-size: 3px; background: var(--sly-tint, var(--sly-blue)); }
    .sly-panel { padding: 1rem; min-block-size: 13rem; border-block-start: 1px solid var(--sly-tint, var(--sly-blue));
                 animation: sly-fade .2s ease-out; }
    @keyframes sly-fade { from { opacity: 0; translate: 0 3px; } to { opacity: 1; translate: 0 0; } }
    .sly-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr)); gap: .8rem 1.25rem; margin: 0; }
    .sly-fields div dt { font-size: .72rem; color: var(--sly-muted); }
    .sly-fields div dd { margin: .1rem 0 0; font-size: .88rem; font-weight: 700; }
    .sly-mini { margin: 0; padding: 0; list-style: none; display: grid; gap: .5rem; }
    .sly-mini li { display: flex; align-items: center; gap: .6rem; padding: .5rem .65rem; border: 1px solid var(--sly-border);
               border-radius: .25rem; font-size: .82rem; }
    .sly-mini li small { margin-inline-start: auto; color: var(--sly-muted); white-space: nowrap; }
    .sly-log { margin: 0; padding: 0; list-style: none; display: grid; gap: .75rem; }
    .sly-log li { position: relative; padding-inline-start: 1.25rem; font-size: .84rem; }
    .sly-log li::before { content: ''; position: absolute; inset-block-start: .3rem; inset-inline-start: 0; inline-size: .55rem;
               aspect-ratio: 1; border-radius: 50%; background: var(--sly-tint, var(--sly-blue)); }
    .sly-log li time { display: block; font-size: .72rem; color: var(--sly-muted); }
    .sly-legend { display: flex; align-items: center; gap: .4rem; margin: 0 0 .75rem; font-size: .78rem; color: var(--sly-muted); }
    .sly-legend b { padding: .08rem .5rem; border-radius: .9rem; color: #FFFFFF; font-size: .72rem;
               background: var(--sly-tint, var(--sly-blue)); font-weight: 700; }

    .sly-spec { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; justify-content: center; align-items: flex-start; }
    .sly-spec-cell { display: grid; gap: .4rem; justify-items: center; }
    .sly-spec-cell > small { font-size: .72rem; color: var(--sly-muted); }
    .sly-chip { padding: .45rem 1.1rem; border-radius: .25rem .25rem 0 0; color: #FFFFFF; font-size: .78rem;
               font-weight: 600; box-shadow: inset 0 3px 0 color-mix(in srgb, #FFFFFF 55%, transparent); }
    :where(.nx-js) .pg:has(.sly-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .sly-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="sly-root"
    x-data="{
        cur: 0,
        tabs: [
            { fa: 'جزئیات', en: 'Details', tint: '#0176D3' },
            { fa: 'مرتبط', en: 'Related', tint: '#04844B' },
            { fa: 'اخبار', en: 'News', tint: '#FE9339' },
            { fa: 'فعالیت', en: 'Activity', tint: '#6739B7' },
        ],
        get t() { return document.documentElement.lang === 'fa' ? 'fa' : 'en' },
        name(i) { return this.t === 'fa' ? this.tabs[i].fa : this.tabs[i].en },
        get tint() { return this.tabs[this.cur].tint },
    }"
    x-bind:style="'--sly-tint: ' + tint">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Tabs that own their colour', 'تب‌هایی که رنگ خودشان را دارند') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Pick a section and the whole bar repaints with its marketing colour — frame, legend chip and timeline dots follow. On narrow widths the bar scrolls in place.', 'یک بخش را انتخاب کنید تا کل نوار با رنگ مارکتینگش رنگ شود — قاب، نشانِ راهنما و نقطه‌های خط زمان هم دنبالش می‌آیند. در عرض کم، نوار در جایش اسکرول می‌شود.') }}
            </p>
        </div>

        <div class="sly-card">
            <div class="sly-bar" role="tablist" aria-label="{{ $say('Record sections', 'بخش‌های رکورد') }}">
                <template x-for="(tab, i) in tabs" :key="i">
                    <button type="button" class="sly-tab" role="tab" :aria-selected="cur === i" x-on:click="cur = i">
                        <span x-text="name(i)"></span>
                    </button>
                </template>
            </div>

            <div class="sly-panel" role="tabpanel" x-show="cur === 0">
                <p class="sly-legend">{{ $say('Opportunity:', 'فرصت:') }} <b x-text="name(0)"></b></p>
                <dl class="sly-fields">
                    <div><dt>{{ $say('Account', 'حساب') }}</dt><dd>{{ $say('Acme Industrial', 'صنایع آکمی') }}</dd></div>
                    <div><dt>{{ $say('Amount', 'مبلغ') }}</dt><dd>{{ $say('€240,000,000', '۲٬۴۰۰٬۰۰۰٬۰۰۰ ریال') }}</dd></div>
                    <div><dt>{{ $say('Owner', 'مالک') }}</dt><dd>{{ $say('Sara Lindqvist', 'سارا احمدی') }}</dd></div>
                    <div><dt>{{ $say('Close date', 'تاریخ بستن') }}</dt><dd>{{ $say('Feb 28, 2026', '۲۸ اسفند ۱۴۰۴') }}</dd></div>
                </dl>
            </div>

            <div class="sly-panel" role="tabpanel" x-show="cur === 1" x-cloak>
                <p class="sly-legend">{{ $say('Related records', 'رکوردهای مرتبط') }}</p>
                <ul class="sly-mini">
                    <li>{{ $say('Contact · Reza Kazemi', 'مخاطب · رضا کاظمی') }} <small>{{ $say('Technical reviewer', 'کارشناس فنی') }}</small></li>
                    <li>{{ $say('Quote · 2026/Q-18', 'پیش‌فاکتور · ۱۴۰۴/Q-18') }} <small>{{ $say('Sent yesterday', 'ارسال‌شده دیروز') }}</small></li>
                    <li>{{ $say('Task · Follow up on pricing', 'وظیفه · پیگیری قیمت') }} <small>{{ $say('Due Sunday', 'مهلت یکشنبه') }}</small></li>
                </ul>
            </div>

            <div class="sly-panel" role="tabpanel" x-show="cur === 2" x-cloak>
                <p class="sly-legend">{{ $say('News about', 'اخبار دربارهٔ') }} <b>{{ $say('Acme Industrial', 'صنایع آکمی') }}</b></p>
                <ul class="sly-log">
                    <li>{{ $say('Acme opened a second plant in Istanbul — expansion budget approved.', 'آکمی کارخانهٔ دوم را در استانبول باز کرد — بودجهٔ توسعه تصویب شد.') }}<time>{{ $say('2 days ago', '۲ روز پیش') }}</time></li>
                    <li>{{ $say('Q3 earnings call: industrial automation up 18%.', 'تماس گزارش فصل: اتوماسیون صنعتی ۱۸٪ رشد داشت.') }}<time>{{ $say('1 week ago', '۱ هفته پیش') }}</time></li>
                </ul>
            </div>

            <div class="sly-panel" role="tabpanel" x-show="cur === 3" x-cloak>
                <p class="sly-legend">{{ $say('Recent activity', 'فعالیت اخیر') }}</p>
                <ul class="sly-log">
                    <li>{{ $say('Sara sent the revised quote.', 'سارا پیش‌فاکتور بازبینی‌شده را فرستاد.') }}<time>{{ $say('2 hours ago', '۲ ساعت پیش') }}</time></li>
                    <li>{{ $say('Stage moved to Negotiation/Review.', 'مرحله به «مذاکره و بازبینی» رفت.') }}<time>{{ $say('yesterday', 'دیروز') }}</time></li>
                    <li>{{ $say('Reza attached the pricing sheet.', 'رضا جدول قیمت را پیوست کرد.') }}<time>{{ $say('3 days ago', '۳ روز پیش') }}</time></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The full scope palette', 'پالت کامل اسکوپ') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Eight marketing colours a record can wear; whichever is selected paints the bar and its content frame.', 'هشت رنگ مارکتینگ که یک رکورد می‌پوشد؛ هر کدام انتخاب شود، نوار و قاب محتوا با همان رنگ می‌شود.') }}
            </p>
        </div>
        <div class="sly-spec" style="inline-size: 100%">
            <div class="sly-spec-cell"><span class="sly-chip" style="background: var(--sly-blue)">{{ $say('Details', 'جزئیات') }}</span><small dir="ltr">#0176D3</small></div>
            <div class="sly-spec-cell"><span class="sly-chip" style="background: var(--sly-green)">{{ $say('Related', 'مرتبط') }}</span><small dir="ltr">#04844B</small></div>
            <div class="sly-spec-cell"><span class="sly-chip" style="background: var(--sly-orange)">{{ $say('News', 'اخبار') }}</span><small dir="ltr">#FE9339</small></div>
            <div class="sly-spec-cell"><span class="sly-chip" style="background: var(--sly-purple)">{{ $say('Activity', 'فعالیت') }}</span><small dir="ltr">#6739B7</small></div>
            <div class="sly-spec-cell"><span class="sly-chip" style="background: var(--sly-teal)">{{ $say('Team', 'تیم') }}</span><small dir="ltr">#0B827C</small></div>
            <div class="sly-spec-cell"><span class="sly-chip" style="background: var(--sly-pink)">{{ $say('Campaign', 'کمپین') }}</span><small dir="ltr">#B32D69</small></div>
            <div class="sly-spec-cell"><span class="sly-chip" style="background: var(--sly-yellow); color: #181818">{{ $say('Forecast', 'پیش‌بینی') }}</span><small dir="ltr">#FFB75D</small></div>
            <div class="sly-spec-cell"><span class="sly-chip" style="background: var(--sly-red)">{{ $say('Risk', 'خطر') }}</span><small dir="ltr">#BA0517</small></div>
        </div>
    </section>
</div>
