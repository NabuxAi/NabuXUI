{{--
    iOS home-screen widgets on a dark wallpaper: a small clock widget with a
    battery ring whose time ticks live, a medium Istanbul weather widget with an
    hourly strip, a large up-next calendar, and a glassy lock-screen accessory
    pill — the official 22px radius on the 4-column grid.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int $n): string => \NabuXUI\NabuXUI::formatNumber($n, 0);
    $faDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $hm = fn (int $h, int $m): string => $fa ? strtr(sprintf('%02d:%02d', $h, $m), array_combine(range(0, 9), $faDigits)) : sprintf('%02d:%02d', $h, $m);
@endphp

<style>
    .iowdg-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .iowdg-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .iowdg-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        }
    }
    .iowdg-root :focus-visible { outline: 2px solid var(--ios-teal); outline-offset: 2px; }

    .iowdg-stage { position: relative; overflow: clip; inline-size: min(100%, 24rem); margin-inline: auto; padding: .9rem .9rem 1rem;
                   border-radius: 26px; color: #fff;
                   background: radial-gradient(120% 90% at 78% 4%, #2e3f7a 0%, transparent 55%),
                               radial-gradient(110% 80% at 12% 88%, #143a52 0%, transparent 58%),
                               linear-gradient(168deg, #0b1030, #05060f 70%);
                   box-shadow: 0 24px 60px rgba(0, 0, 0, .4), inset 0 0 0 1px rgba(255, 255, 255, .08); }
    .iowdg-blobs { position: absolute; inset: 0; z-index: 0; filter: blur(34px) saturate(1.3); pointer-events: none; }
    .iowdg-blobs i { position: absolute; inline-size: 46%; aspect-ratio: 1; border-radius: 50%; opacity: .55;
                     animation: iowdg-drift 26s ease-in-out infinite alternate; }
    .iowdg-blobs i:nth-child(1) { inset-block-start: -14%; inset-inline-start: -10%; background: #5856D6; }
    .iowdg-blobs i:nth-child(2) { inset-block-end: -18%; inset-inline-end: -12%; background: #0A84FF; animation-delay: -13s; }
    @keyframes iowdg-drift { to { translate: calc(6% * var(--nx-motion)) calc(-8% * var(--nx-motion)); } }
    .iowdg-inner { position: relative; z-index: 1; }

    .iowdg-status { display: flex; align-items: center; justify-content: space-between; padding: .35rem .6rem 0; font: 600 .82rem/-apple-system, system-ui, sans-serif; }
    .iowdg-status .batt { position: relative; inline-size: 21px; block-size: 10px; border: 1px solid rgba(255, 255, 255, .45); border-radius: 3px; }
    .iowdg-status .batt::before { content: ''; position: absolute; inset: 1.5px; inset-inline-end: 5px; border-radius: 1.5px; background: #fff; }
    .iowdg-status .batt::after { content: ''; position: absolute; inset-block: 2.5px; inset-inline-end: -3px; inline-size: 1.5px; border-radius: 0 1.5px 1.5px 0; background: rgba(255, 255, 255, .45); }

    .iowdg-pill { display: flex; inline-size: fit-content; max-inline-size: 100%; align-items: center; gap: .5rem; margin: 1rem auto .4rem;
                  padding: .5rem 1.1rem; border-radius: 999px; font-size: .88rem; font-weight: 600;
                  background: rgba(255, 255, 255, .16); backdrop-filter: blur(24px) saturate(1.8); -webkit-backdrop-filter: blur(24px) saturate(1.8);
                  box-shadow: inset 0 0 0 .5px rgba(255, 255, 255, .35), 0 6px 20px rgba(0, 0, 0, .25); }
    .iowdg-pill svg { color: #64D2FF; }

    .iowdg-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .7rem; margin-block-start: .8rem; }
    .iowdg { border-radius: 22px; padding: .85rem; background: var(--ios-card); color: var(--ios-label);
             box-shadow: 0 1px 3px rgba(0, 0, 0, .18), 0 8px 22px rgba(0, 0, 0, .18);
             overflow: clip; }
    .iowdg small { display: block; font-size: .68rem; font-weight: 600; color: var(--ios-label-2); text-transform: uppercase; letter-spacing: .02em; }
    .iowdg-sm { grid-column: span 2; aspect-ratio: 1; display: flex; flex-direction: column; }
    .iowdg-sm .clock { font: 600 2rem/-apple-system, system-ui, sans-serif; font-variant-numeric: tabular-nums; margin-block-start: .2rem; }
    .iowdg-sm .date { font-size: .72rem; color: var(--ios-label-2); }
    .iowdg-ring-row { display: flex; align-items: center; gap: .55rem; margin-block-start: auto; }
    .iowdg-ring { position: relative; inline-size: 46px; aspect-ratio: 1; }
    .iowdg-ring svg { transform: rotate(-90deg); }
    .iowdg-ring b { position: absolute; inset: 0; display: grid; place-items: center; font-size: .62rem; font-weight: 700; }
    .iowdg-ring-row span { font-size: .72rem; color: var(--ios-label-2); }
    .iowdg-md { grid-column: span 4; aspect-ratio: 334 / 158; display: flex; flex-direction: column; }
    .iowdg-md header { display: flex; align-items: baseline; gap: .45rem; }
    .iowdg-md header b { font: 600 1.2rem/-apple-system, system-ui, sans-serif; }
    .iowdg-md header .deg { margin-inline-start: auto; font: 500 1.5rem/-apple-system, system-ui, sans-serif; }
    .iowdg-md .cond { font-size: .72rem; color: var(--ios-label-2); }
    .iowdg-hours { display: flex; gap: .2rem; margin-block-start: auto; overflow-x: auto; scrollbar-width: none; }
    .iowdg-hours::-webkit-scrollbar { display: none; }
    .iowdg-hours div { flex: none; inline-size: 2.9rem; display: grid; justify-items: center; gap: .2rem; font-size: .68rem; color: var(--ios-label-2); }
    .iowdg-hours div b { color: var(--ios-label); font-weight: 600; font-size: .74rem; }
    .iowdg-hours svg { color: var(--ios-orange); }
    .iowdg-lg { grid-column: span 4; aspect-ratio: 1; display: flex; flex-direction: column; }
    .iowdg-lg header { display: flex; align-items: baseline; justify-content: space-between; margin-block-end: .6rem; }
    .iowdg-lg header b { font: 600 .95rem/-apple-system, system-ui, sans-serif; color: var(--ios-red); }
    .iowdg-ev { display: flex; gap: .6rem; padding-block: .45rem; }
    .iowdg-ev + .iowdg-ev { border-block-start: .5px solid var(--ios-sep); }
    .iowdg-ev .bar { flex: none; inline-size: 4px; border-radius: 999px; }
    .iowdg-ev b { display: block; font-size: .82rem; font-weight: 600; }
    .iowdg-ev span { font-size: .7rem; color: var(--ios-label-2); }
    .iowdg-ev time { margin-inline-start: auto; font-size: .7rem; color: var(--ios-label-2); font-variant-numeric: tabular-nums; }

    .iowdg-dots { display: flex; justify-content: center; gap: .4rem; padding-block-start: .9rem; }
    .iowdg-dots i { inline-size: .42rem; block-size: .42rem; border-radius: 999px; background: rgba(255, 255, 255, .35); }
    .iowdg-dots i:first-child { inline-size: 1.3rem; background: rgba(255, 255, 255, .8); }

    @media (prefers-reduced-motion: reduce) {
        .iowdg-blobs i { animation: none; }
    }

    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the 2.5s CSS failsafe is off once .nx-live is set).
       A full-page capture never scrolls, so the table stayed header-only.
       Pin this page's table rows visible — scoped through :has(.iowdg-root),
       so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.iowdg-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Widgets on the home screen', 'ویجت‌ها روی صفحهٔ اصلی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A pocket of an iOS home screen on a dark wallpaper: the small widget pairs a live ticking clock with a battery ring, the medium one keeps Istanbul’s next hours in a swipeable strip, the large one lists today’s calendar, and the glassy accessory pill stays from the lock screen — all with the official 22px continuous radius.', 'گوشه‌ای از صفحهٔ اصلی آی‌او‌اس روی کاغذدیواری تیره: ویجت کوچک ساعت زنده را با حلقهٔ باتری جفت کرده، ویجت متوسط ساعات بعدی استانبول را در نوار کش‌دان نگه می‌دارد، ویجت بزرگ تقویم امروز را می‌دهد، و قرص شیشه‌ایِ استندبای از قفل صفحه مانده — همه با گردی پیوستهٔ رسمی ۲۲ پیکسل.') }}
        </p>
    </div>

    <div class="iowdg-root" x-data="{
            now: new Date(), locale: document.documentElement.lang || 'en',
            boot() { setInterval(() => this.now = new Date(), 1000) },
            get clock() { return new Intl.DateTimeFormat(this.locale === 'fa' ? 'fa-IR-u-nu-arabext' : this.locale, { hour: '2-digit', minute: '2-digit', hour12: false }).format(this.now) },
            get dateStr() { return new Intl.DateTimeFormat(this.locale === 'fa' ? 'fa-IR-u-ca-persian' : this.locale, { weekday: 'long', day: 'numeric', month: 'long' }).format(this.now) },
        }"
         x-init="boot()">
        <div class="iowdg-stage">
            <div class="iowdg-blobs" aria-hidden="true"><i></i><i></i></div>
            <div class="iowdg-inner">
                <div class="iowdg-status" aria-hidden="true">
                    <b>{{ $say('9:41', '۰۹:۴۱') }}</b>
                    <span style="display: inline-flex; align-items: center; gap: .35rem">
                        <span style="display: inline-flex; align-items: flex-end; gap: 1.5px">
                            <i style="inline-size: 3px; block-size: 4px; border-radius: 1px; background: #fff"></i>
                            <i style="inline-size: 3px; block-size: 6px; border-radius: 1px; background: #fff"></i>
                            <i style="inline-size: 3px; block-size: 8px; border-radius: 1px; background: #fff"></i>
                            <i style="inline-size: 3px; block-size: 10px; border-radius: 1px; background: rgba(255,255,255,.4)"></i>
                        </span>
                        <span class="batt"></span>
                    </span>
                </div>

                <p class="iowdg-pill">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><circle cx="8" cy="8" r="5.8"/><path d="M8 4.8V8l2.2 1.6"/></svg>
                    <span aria-live="off">{{ $say('Thursday 16 Oct', 'پنجشنبه ۱۶ مهر') }} · <span x-text="clock">۰۹:۴۱</span></span>
                </p>

                <div class="iowdg-grid">
                    <div class="iowdg iowdg-sm">
                        <small>{{ $say('Istanbul', 'استانبول') }}</small>
                        <span class="clock" x-text="clock">۰۹:۴۱</span>
                        <span class="date" x-text="dateStr">پنجشنبه ۱۶ مهر</span>
                        <div class="iowdg-ring-row">
                            <span class="iowdg-ring" role="img" aria-label="{{ $say('Battery: 76 percent', 'باتری: ۷۶ درصد') }}">
                                <svg width="46" height="46" viewBox="0 0 46 46" fill="none" aria-hidden="true">
                                    <circle cx="23" cy="23" r="19" stroke="var(--ios-gray5)" stroke-width="5"/>
                                    <circle cx="23" cy="23" r="19" stroke="#34C759" stroke-width="5" stroke-linecap="round"
                                            stroke-dasharray="119.4" stroke-dashoffset="28.7"/>
                                </svg>
                                <b>{{ $say('76%', '۷۶٪') }}</b>
                            </span>
                            <span>{{ $say('Battery', 'باتری') }}</span>
                        </div>
                    </div>

                    <div class="iowdg iowdg-sm">
                        <small>{{ $say('Suggestion', 'پیشنهاد') }}</small>
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#FF9F0A" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin-block-start: .35rem"><path d="M12 3.2 14.5 9l6.3.5-4.8 4.1 1.5 6.2-5.5-3.4-5.5 3.4 1.5-6.2L3.2 9.5 9.5 9Z"/></svg>
                        <p style="margin: .35rem 0 0; font-size: .8rem; font-weight: 600; line-height: 1.5">
                            {{ $say('Leave early — rain is due at 18:00 in Taksim.', 'زودتر حرکت کن — ساعت ۱۸ باران در تاکسیم می‌بارد.') }}
                        </p>
                    </div>

                    <div class="iowdg iowdg-md">
                        <header>
                            <b>{{ $say('Istanbul', 'استانبول') }}</b>
                            <span class="cond">{{ $say('Partly cloudy · H 24° L 11°', 'نیم‌ابری · بیشینه ۲۴° کمینه ۱۱°') }}</span>
                            <span class="deg">{{ $num(18) }}°</span>
                        </header>
                        <div class="iowdg-hours" aria-label="{{ $say('Hourly forecast', 'پیش‌بینی ساعتی') }}">
                            <div><span>{{ $say('Now', 'اکنون') }}</span><b>{{ $num(18) }}°</b>
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><circle cx="10" cy="10" r="3.6"/><path d="M10 2.6v1.8M10 15.6v1.8M2.6 10h1.8M15.6 10h1.8M4.8 4.8l1.3 1.3M13.9 13.9l1.3 1.3M15.2 4.8l-1.3 1.3M6.1 13.9l-1.3 1.3"/></svg>
                            </div>
                            <div><span>{{ $num(14) }}</span><b>{{ $num(19) }}°</b>
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 14.5a3.5 3.5 0 0 1-.4-7 4.6 4.6 0 0 1 9-.6 3.2 3.2 0 0 1-.6 6.4Z"/></svg>
                            </div>
                            <div><span>{{ $num(15) }}</span><b>{{ $num(20) }}°</b>
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 13.5a3.5 3.5 0 0 1-.4-7 4.6 4.6 0 0 1 9-.6 3.2 3.2 0 0 1-.6 6.4Z"/><path d="M7.5 16.2l-.7 1.6M11 16.2l-.7 1.6M14.5 16.2l-.7 1.6"/></svg>
                            </div>
                            <div><span>{{ $num(16) }}</span><b>{{ $num(18) }}°</b>
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 13.5a3.5 3.5 0 0 1-.4-7 4.6 4.6 0 0 1 9-.6 3.2 3.2 0 0 1-.6 6.4Z"/><path d="M7.5 16.2l-.7 1.6M11 16.2l-.7 1.6M14.5 16.2l-.7 1.6"/></svg>
                            </div>
                            <div><span>{{ $num(17) }}</span><b>{{ $num(16) }}°</b>
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 14.5a3.5 3.5 0 0 1-.4-7 4.6 4.6 0 0 1 9-.6 3.2 3.2 0 0 1-.6 6.4Z"/></svg>
                            </div>
                            <div><span>{{ $num(18) }}</span><b>{{ $num(14) }}°</b>
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="10" cy="10" r="3.6"/><path d="M10 2.6v1.8M10 15.6v1.8M2.6 10h1.8M15.6 10h1.8M4.8 4.8l1.3 1.3M13.9 13.9l1.3 1.3M15.2 4.8l-1.3 1.3M6.1 13.9l-1.3 1.3"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="iowdg iowdg-lg">
                        <header>
                            <b>{{ $say('Today', 'امروز') }}</b>
                            <span style="font-size: .78rem; color: var(--ios-label-2)" x-text="dateStr">پنجشنبه ۱۶ مهر</span>
                        </header>
                        <div>
                            <div class="iowdg-ev">
                                <span class="bar" style="background: var(--ios-blue)" aria-hidden="true"></span>
                                <div><b>{{ $say('Design review', 'جلسهٔ طراحی') }}</b><span>{{ $say('Room 3 · online', 'اتاق ۳ · آنلاین') }}</span></div>
                                <time>{{ $hm(10, 30) }}</time>
                            </div>
                            <div class="iowdg-ev">
                                <span class="bar" style="background: var(--ios-orange)" aria-hidden="true"></span>
                                <div><b>{{ $say('Lunch with Sara', 'ناهار با سارا') }}</b><span>{{ $say('Cafe Istanbul', 'کافه استانبول') }}</span></div>
                                <time>{{ $hm(13, 0) }}</time>
                            </div>
                            <div class="iowdg-ev">
                                <span class="bar" style="background: var(--ios-green)" aria-hidden="true"></span>
                                <div><b>{{ $say('Volleyball practice', 'تمرین والیبال') }}</b><span>{{ $say('Beyoğlu Sports Hall', 'سالن بی‌اوغلو') }}</span></div>
                                <time>{{ $hm(18, 0) }}</time>
                            </div>
                            <div class="iowdg-ev">
                                <span class="bar" style="background: var(--ios-indigo)" aria-hidden="true"></span>
                                <div><b>{{ $say('Reading: Liquid Glass', 'خواندن: شیشهٔ مایع') }}</b><span>{{ $say('HIG · 30 min', 'اچ‌آی‌جی · ۳۰ دقیقه') }}</span></div>
                                <time>{{ $hm(21, 30) }}</time>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="iowdg-dots" aria-hidden="true"><i></i><i></i></div>
            </div>
        </div>
    </div>
</section>
