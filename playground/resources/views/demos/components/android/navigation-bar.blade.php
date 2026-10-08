{{--
    Material's bottom navigation bar in a staged music app: one absolutely
    positioned 64×32 pill glides between the four destinations on the
    emphasized curve, labels only fill under the active destination, and the
    icons carry a dot badge and a 3+ count badge.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3nav-root {
        --m3nav-primary: #6750A4; --m3nav-on-primary: #FFFFFF;
        --m3nav-primary-container: #EADDFF; --m3nav-on-primary-container: #21005D;
        --m3nav-secondary-container: #E8DEF8; --m3nav-on-secondary-container: #1D192B;
        --m3nav-surface: #FEF7FF; --m3nav-surface-container: #F3EDF7;
        --m3nav-surface-container-high: #ECE6F0;
        --m3nav-on-surface: #1D1B20; --m3nav-on-surface-variant: #49454F;
        --m3nav-outline-variant: #CAC4D0; --m3nav-error: #B3261E;
        --m3nav-ease: cubic-bezier(.2, 0, 0, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3nav-root {
        --m3nav-primary: #D0BCFF; --m3nav-on-primary: #381E72;
        --m3nav-primary-container: #4F378B; --m3nav-on-primary-container: #EADDFF;
        --m3nav-secondary-container: #4A4458; --m3nav-on-secondary-container: #E8DEF8;
        --m3nav-surface: #141218; --m3nav-surface-container: #211F26;
        --m3nav-surface-container-high: #2B2930;
        --m3nav-on-surface: #E6E0E9; --m3nav-on-surface-variant: #CAC4D0;
        --m3nav-outline-variant: #49454F; --m3nav-error: #F2B8B5;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3nav-root {
            --m3nav-primary: #D0BCFF; --m3nav-on-primary: #381E72;
            --m3nav-primary-container: #4F378B; --m3nav-on-primary-container: #EADDFF;
            --m3nav-secondary-container: #4A4458; --m3nav-on-secondary-container: #E8DEF8;
            --m3nav-surface: #141218; --m3nav-surface-container: #211F26;
            --m3nav-surface-container-high: #2B2930;
            --m3nav-on-surface: #E6E0E9; --m3nav-on-surface-variant: #CAC4D0;
            --m3nav-outline-variant: #49454F; --m3nav-error: #F2B8B5;
        }
    }
    .m3nav-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3nav-screen { position: relative; overflow: clip; block-size: 27rem; border-radius: 2.5rem; background: var(--m3nav-surface); display: flex; flex-direction: column; }
    .m3nav-status { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.4rem .2rem; color: var(--m3nav-on-surface); font: 600 .72rem/1 Roboto, system-ui, sans-serif; }
    .m3nav-page { flex: 1; overflow-y: auto; padding: .5rem 1.1rem .75rem; animation: m3nav-fade .25s var(--m3nav-ease); }
    @keyframes m3nav-fade { from { opacity: 0; translate: 0 .4rem; } }
    .m3nav-page h4 { margin: .3rem 0 .6rem; font: 500 1.3rem/1.25 Roboto, system-ui, sans-serif; color: var(--m3nav-on-surface); }
    .m3nav-page small { display: block; font: 400 .78rem/1.4 Roboto, system-ui, sans-serif; color: var(--m3nav-on-surface-variant); margin-block-end: .75rem; }
    .m3nav-tile { display: flex; align-items: center; gap: .7rem; padding: .5rem .55rem; border-radius: .9rem; background: var(--m3nav-surface-container-high); margin-block-end: .4rem; }
    .m3nav-tile i { flex: none; inline-size: 2.4rem; aspect-ratio: 1; border-radius: .55rem; }
    .m3nav-tile b { display: block; font: 500 .82rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3nav-on-surface); }
    .m3nav-tile small { display: block; font: 400 .7rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3nav-on-surface-variant); }
    .m3nav-genres { display: grid; grid-template-columns: repeat(2, 1fr); gap: .5rem; }
    .m3nav-genres span { display: grid; place-items: center; block-size: 3.6rem; border-radius: .9rem; font: 500 .8rem/1 Roboto, system-ui, sans-serif; color: #ffffff; }
    .m3nav-bar { position: relative; display: flex; block-size: 5rem; flex: none; background: var(--m3nav-surface-container); }
    .m3nav-pill { position: absolute; inset-block-start: .65rem; inline-size: 4rem; block-size: 2rem; border-radius: 999px; background: var(--m3nav-secondary-container); translate: -50% 0; transition: inset-inline-start .3s var(--m3nav-ease); }
    .m3nav-item { position: relative; z-index: 1; flex: 1; display: grid; justify-items: center; align-content: start; gap: .3rem; padding: .65rem .25rem .5rem; border: none; background: transparent; cursor: pointer; color: var(--m3nav-on-surface-variant); font: 500 .72rem/1 Roboto, system-ui, sans-serif; -webkit-tap-highlight-color: transparent; transition: color .2s var(--m3nav-ease); }
    .m3nav-item:focus-visible { outline: 2px solid var(--m3nav-primary); outline-offset: -2px; border-radius: 1rem; }
    .m3nav-item[data-current='true'] { color: var(--m3nav-on-surface); font-weight: 700; }
    .m3nav-ico { position: relative; display: grid; place-items: center; inline-size: 4rem; block-size: 2rem; }
    .m3nav-dot { position: absolute; inset-block-start: .15rem; inset-inline-end: 1.15rem; inline-size: .5rem; aspect-ratio: 1; border-radius: 50%; background: var(--m3nav-error); }
    .m3nav-count { position: absolute; inset-block-start: -.1rem; inset-inline-end: .85rem; min-inline-size: 1rem; padding: .1rem .25rem; border-radius: 999px; background: var(--m3nav-error); color: var(--m3nav-on-primary); font: 700 .56rem/1 Roboto, system-ui, sans-serif; }
    .m3nav-note { margin: 0; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); max-width: 56ch; }

    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3nav-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3nav-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column is read as cut off; let this page's table wrap. */
    @media (max-width: 480px) {
        .pg:has(.m3nav-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3nav-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Four destinations, one pill', 'چهار مقصد، یک قرص') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Switch tabs — the secondary-container pill glides under the icons on Material’s emphasized curve, labels fill only for the active destination, and the badges ride along.', 'تب‌ها را عوض کنید — قرص ثانویه‌رنگ روی منحنی تأکیدی متریال زیر آیکن‌ها می‌سُرد، فقط برچسب مقصد فعال پُر می‌شود و نشان‌ها هم همراه می‌آیند.') }}
        </p>
    </div>

    <div class="m3nav-root" x-data="{ tab: 0 }">
        <div class="m3nav-frame">
            <div class="m3nav-screen">
                <div class="m3nav-status" aria-hidden="true">
                    <span>{{ $say('9:41', '۰۹:۴۱') }}</span>
                    <svg width="44" height="12" viewBox="0 0 44 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="5" width="3" height="7" rx="1"/><rect x="10" y="3" width="3" height="9" rx="1"/><rect x="15" y="1" width="3" height="11" rx="1"/><rect x="35" y="2" width="8" height="8" rx="2" opacity=".35"/><rect x="36.5" y="3.5" width="4" height="5" rx="1"/></svg>
                </div>

                <div class="m3nav-page" x-show="tab === 0">
                    <h4>{{ $say('Good evening, Sara', 'عصر بخیر، سارا') }}</h4>
                    <small>{{ $say('Continue where you stopped last night', 'از همان‌جا ادامه بده که دیشب ایستادی') }}</small>
                    <div class="m3nav-tile"><i style="background: linear-gradient(140deg,#c4b5fd,#6d28d9)" aria-hidden="true"></i><span><b>{{ $say('Night tar suite', 'سوئیت شبِ تار') }}</b><small>{{ $say('Kayhan Kalhor · 12 min left', 'کیهان کلهر · ۱۲ دقیقه مانده') }}</small></span></div>
                    <div class="m3nav-tile"><i style="background: linear-gradient(140deg,#fdba74,#c2410c)" aria-hidden="true"></i><span><b>{{ $say('Shiraz alleys', 'کوچه‌های شیراز') }}</b><small>{{ $say('Sima Bina · track 4 of 9', 'سیما بینا · قطعهٔ ۴ از ۹') }}</small></span></div>
                    <div class="m3nav-tile"><i style="background: linear-gradient(140deg,#86efac,#15803d)" aria-hidden="true"></i><span><b>{{ $say('Morning run mix', 'میکس دو صبحگاهی') }}</b><small>{{ $say('Rastak · fresh this week', 'رستاک · تازهٔ این هفته') }}</small></span></div>
                </div>
                <div class="m3nav-page" x-show="tab === 1" x-cloak>
                    <h4>{{ $say('Explore', 'کاوش') }}</h4>
                    <small>{{ $say('Genres tuned to your listening', 'ژانرها بر اساس شنیده‌های شما') }}</small>
                    <div class="m3nav-genres">
                        <span style="background: linear-gradient(140deg,#818cf8,#3730a3)">{{ $say('Traditional', 'سنتی') }}</span>
                        <span style="background: linear-gradient(140deg,#fb7185,#9f1239)">{{ $say('Pop', 'پاپ') }}</span>
                        <span style="background: linear-gradient(140deg,#34d399,#065f46)">{{ $say('Nature sounds', 'صدای طبیعت') }}</span>
                        <span style="background: linear-gradient(140deg,#fbbf24,#b45309)">{{ $say('Podcasts', 'پادکست') }}</span>
                    </div>
                </div>
                <div class="m3nav-page" x-show="tab === 2" x-cloak>
                    <h4>{{ $say('Library', 'کتابخانه') }}</h4>
                    <small>{{ $say('142 tracks · 18 albums · downloaded: 9', '۱۴۲ قطعه · ۱۸ آلبوم · دانلودشده: ۹') }}</small>
                    <div class="m3nav-tile"><i style="background: linear-gradient(140deg,#93c5fd,#1d4ed8)" aria-hidden="true"></i><span><b>{{ $say('Hirkanic mornings', 'صبح‌های هیرکانی') }}</b><small>{{ $say('Playlist · 31 tracks', 'لیست پخش · ۳۱ قطعه') }}</small></span></div>
                    <div class="m3nav-tile"><i style="background: linear-gradient(140deg,#f9a8d4,#9d174d)" aria-hidden="true"></i><span><b>{{ $say('Albums of the year', 'آلبوم‌های امسال') }}</b><small>{{ $say('Playlist · 24 tracks', 'لیست پخش · ۲۴ قطعه') }}</small></span></div>
                </div>
                <div class="m3nav-page" x-show="tab === 3" x-cloak>
                    <h4>{{ $say('Profile', 'پروفایل') }}</h4>
                    <small>{{ $say('Nabu Premium · renews on Azar 15', 'نابو پرمیوم · تمدید ۱۵ آذر') }}</small>
                    <div class="m3nav-tile"><i style="background: linear-gradient(140deg,#d8b4fe,#7e22ce)" aria-hidden="true"></i><span><b>{{ $say('Sara Ahmadi', 'سارا احمدی') }}</b><small>{{ $say('4 hours of listening this week', '۴ ساعت شنیدن این هفته') }}</small></span></div>
                </div>

                <nav class="m3nav-bar" aria-label="{{ $say('App destinations', 'مقصدهای برنامه') }}">
                    <span class="m3nav-pill" aria-hidden="true" x-bind:style="{ insetInlineStart: 'calc(' + tab + ' * 25% + 12.5%)' }" style="inset-inline-start: 12.5%"></span>
                    <button type="button" class="m3nav-item" data-current="true" x-bind:data-current="(tab === 0).toString()" x-on:click="tab = 0" x-bind:aria-current="tab === 0 ? 'page' : null">
                        <span class="m3nav-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 11 12 4l8 7"/><path d="M6 10v9h12v-9"/></svg></span>
                        {{ $say('Home', 'خانه') }}
                    </button>
                    <button type="button" class="m3nav-item" x-bind:data-current="(tab === 1).toString()" x-on:click="tab = 1" x-bind:aria-current="tab === 1 ? 'page' : null">
                        <span class="m3nav-ico">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="8.5"/><path d="M15.5 8.5 13.5 13.5 8.5 15.5 10.5 10.5z"/></svg>
                            <span class="m3nav-dot" aria-label="{{ $say('New in Explore', 'کاوش تازه دارد') }}"></span>
                        </span>
                        {{ $say('Explore', 'کاوش') }}
                    </button>
                    <button type="button" class="m3nav-item" x-bind:data-current="(tab === 2).toString()" x-on:click="tab = 2" x-bind:aria-current="tab === 2 ? 'page' : null">
                        <span class="m3nav-ico">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V6l10-2v12"/><circle cx="6.5" cy="18" r="2.5"/><circle cx="16.5" cy="16" r="2.5"/></svg>
                            <span class="m3nav-count" aria-label="{{ $say('3 new items', '۳ مورد تازه') }}">۳+</span>
                        </span>
                        {{ $say('Library', 'کتابخانه') }}
                    </button>
                    <button type="button" class="m3nav-item" x-bind:data-current="(tab === 3).toString()" x-on:click="tab = 3" x-bind:aria-current="tab === 3 ? 'page' : null">
                        <span class="m3nav-ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg></span>
                        {{ $say('Profile', 'پروفایل') }}
                    </button>
                </nav>
            </div>
        </div>
        <p class="m3nav-note">{{ $say('Badges: a dot for the quiet hint, a count clamped like 3+ for the noisy one — both in the error colour, per spec.', 'نشان‌ها: نقطه برای خبرِ آرام و عددِ بسته‌بندی‌شده مثل ۳+ برای خبرِ پرسر‌وصدا — هر دو به رنگ خطا، طبق مشخصات.') }}</p>
    </div>
</section>
