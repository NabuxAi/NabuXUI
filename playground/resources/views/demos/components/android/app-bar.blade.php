{{--
    The M3 top app bars in a scrollable phone stage: the 152 px large bar
    collapses to 64 px on scroll — the big title rides the bottom edge up
    into the icon row at 22 px while the surface colour and the level-2
    shadow appear the moment you move. Two action icons plus the overflow
    menu; the small and medium heights sit below as specimens.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3bar-root {
        --m3bar-primary: #6750A4; --m3bar-on-primary: #FFFFFF;
        --m3bar-primary-container: #EADDFF; --m3bar-on-primary-container: #21005D;
        --m3bar-secondary-container: #E8DEF8; --m3bar-on-secondary-container: #1D192B;
        --m3bar-surface: #FEF7FF; --m3bar-surface-container: #F3EDF7;
        --m3bar-surface-container-high: #ECE6F0;
        --m3bar-on-surface: #1D1B20; --m3bar-on-surface-variant: #49454F;
        --m3bar-outline-variant: #CAC4D0;
        --m3bar-ease: cubic-bezier(.2, 0, 0, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3bar-root {
        --m3bar-primary: #D0BCFF; --m3bar-on-primary: #381E72;
        --m3bar-primary-container: #4F378B; --m3bar-on-primary-container: #EADDFF;
        --m3bar-secondary-container: #4A4458; --m3bar-on-secondary-container: #E8DEF8;
        --m3bar-surface: #141218; --m3bar-surface-container: #211F26;
        --m3bar-surface-container-high: #2B2930;
        --m3bar-on-surface: #E6E0E9; --m3bar-on-surface-variant: #CAC4D0;
        --m3bar-outline-variant: #49454F;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3bar-root {
            --m3bar-primary: #D0BCFF; --m3bar-on-primary: #381E72;
            --m3bar-primary-container: #4F378B; --m3bar-on-primary-container: #EADDFF;
            --m3bar-secondary-container: #4A4458; --m3bar-on-secondary-container: #E8DEF8;
            --m3bar-surface: #141218; --m3bar-surface-container: #211F26;
            --m3bar-surface-container-high: #2B2930;
            --m3bar-on-surface: #E6E0E9; --m3bar-on-surface-variant: #CAC4D0;
            --m3bar-outline-variant: #49454F;
        }
    }
    .m3bar-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3bar-screen { position: relative; overflow: clip; border-radius: 2.5rem; background: var(--m3bar-surface); display: flex; flex-direction: column; }
    .m3bar-scroll { overflow-y: auto; max-block-size: 24rem; border-radius: 2.5rem; scrollbar-width: none; }
    .m3bar-scroll::-webkit-scrollbar { display: none; }
    .m3bar { position: sticky; inset-block-start: 0; z-index: 2; block-size: 9.5rem; background: transparent; transition: block-size .3s var(--m3bar-ease), background-color .3s var(--m3bar-ease), box-shadow .3s var(--m3bar-ease); }
    .m3bar[data-size='small'] { block-size: 4rem; background: var(--m3bar-surface-container); box-shadow: 0 1px 2px rgba(0, 0, 0, .3), 0 2px 6px 2px rgba(0, 0, 0, .15); }
    .m3bar-row { display: flex; align-items: center; gap: .2rem; padding: .7rem .8rem 0; }
    .m3bar-acts { display: flex; align-items: center; gap: .2rem; margin-inline-start: auto; }
    .m3bar-ico { position: relative; display: grid; place-items: center; inline-size: 2.75rem; aspect-ratio: 1; border: none; border-radius: 50%; background: transparent; color: var(--m3bar-on-surface); cursor: pointer; }
    .m3bar-ico::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: currentColor; opacity: 0; transition: opacity .15s; pointer-events: none; }
    .m3bar-ico:hover::before { opacity: .08; }
    .m3bar-ico:active::before { opacity: .12; }
    .m3bar-ico:focus-visible { outline: 2px solid var(--m3bar-primary); outline-offset: 2px; }
    .m3bar-title { position: absolute; inset-inline-start: 1.1rem; inset-block-end: 1.6rem; margin: 0; font: 400 1.75rem/1.2 Roboto, system-ui, sans-serif; color: var(--m3bar-on-surface); white-space: nowrap; transition: inset-inline-start .3s var(--m3bar-ease), inset-block-end .3s var(--m3bar-ease), font-size .3s var(--m3bar-ease); }
    .m3bar[data-size='small'] .m3bar-title { inset-inline-start: 3.65rem; inset-block-end: 1.15rem; font-size: 1.375rem; }
    .m3bar-menu { position: absolute; inset-block-start: 3.2rem; inset-inline-end: .9rem; z-index: 3; display: grid; min-inline-size: 11rem; padding: .4rem 0; border-radius: .75rem; background: var(--m3bar-surface-container-high); box-shadow: 0 2px 6px 2px rgba(0, 0, 0, .15), 0 1px 2px rgba(0, 0, 0, .3); animation: m3bar-pop .18s var(--m3bar-ease); }
    @keyframes m3bar-pop { from { opacity: 0; scale: .92; } }
    .m3bar-menu button { position: relative; padding: .65rem 1rem; border: none; background: transparent; color: var(--m3bar-on-surface); cursor: pointer; text-align: start; font: 400 .84rem/1.3 Roboto, system-ui, sans-serif; }
    .m3bar-menu button:hover { background: color-mix(in srgb, var(--m3bar-on-surface) 8%, transparent); }
    .m3bar-menu button:focus-visible { outline: 2px solid var(--m3bar-primary); outline-offset: -2px; }
    .m3bar-list { display: grid; align-content: start; gap: .1rem; padding: .6rem .8rem 1rem; }
    .m3bar-track { display: flex; align-items: center; gap: .7rem; padding: .45rem .55rem; border-radius: .8rem; }
    .m3bar-track:hover { background: color-mix(in srgb, var(--m3bar-on-surface) 5%, transparent); }
    .m3bar-track i { flex: none; inline-size: 2.5rem; aspect-ratio: 1; border-radius: .55rem; }
    .m3bar-track b { display: block; font: 500 .84rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3bar-on-surface); }
    .m3bar-track small { display: block; font: 400 .72rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3bar-on-surface-variant); }
    .m3bar-track time { margin-inline-start: auto; font: 400 .72rem/1 Roboto, system-ui, sans-serif; color: var(--m3bar-on-surface-variant); font-variant-numeric: tabular-nums; }
    [dir='rtl'] .m3bar-flip { transform: scaleX(-1); }
    .m3bar-spec { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; }
    .m3bar-spec-cell { display: grid; gap: .5rem; inline-size: min(100%, 17rem); }
    .m3bar-spec-cell small { font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); }
    .m3bar-mini { border-radius: 1.25rem; overflow: clip; background: var(--m3bar-surface); border: 1px solid var(--nx-border); }
    .m3bar-mini .m3bar { position: relative; background: var(--m3bar-surface-container); }
    .m3bar-md { block-size: 7rem; }
    .m3bar-md .m3bar-title { inset-inline-start: 1.1rem; inset-block-end: 1.1rem; font-size: 1.375rem; }
    @media (prefers-reduced-motion: reduce) {
        .m3bar-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
    /* The "Important props" table this page renders (via the shared demo
       template) hides its rows until a scroll-reveal observer fires — in a
       full-page capture below the fold that never happens, so the body
       renders empty. Show the rows unconditionally; this partial only loads
       on this page, so the override is page-scoped. */
    .nx-page .pg .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    /* At phone widths the shared "Important props" table's nowrap cells run
       past the inline edge: the wide Default values ('64 · 112 · 152',
       'collapse') plus the nowrap «What it does» header push the last column
       off a 375px screen, so every note line is cut mid-word. Let this
       page's table cells wrap — and slim the cell padding there — scoped
       through :has(.m3bar-root), so it never reaches another demo page. */
    @media (max-width: 480px) {
        .pg:has(.m3bar-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A bar that folds on scroll', 'نواری که با اسکرول تا می‌شود') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Scroll the list — the 152 px large title settles onto the bar line at 22 px, the surface colour fills in and the level-2 shadow appears the moment you move. The ⋮ menu works.', 'فهرست را اسکرول کنید — تیتر بزرگِ ۱۵۲ پیکسلی با اندازهٔ ۲۲ روی خط نوار می‌نشیند، رنگ سطح پر می‌شود و سایهٔ سطح ۲ به‌محض حرکت ظاهر می‌شود. منوی ⋮ هم کار می‌کند.') }}
        </p>
    </div>

    <div class="m3bar-root" x-data="{ down: false, menu: false }">
        <div class="m3bar-frame">
            <div class="m3bar-screen">
                <div class="m3bar-scroll" x-on:scroll.passive="down = $el.scrollTop > 8" x-on:scroll.passive.once="menu = false">
                    <header class="m3bar" x-bind:data-size="down ? 'small' : 'large'" data-size="large">
                        <div class="m3bar-row">
                            <button type="button" class="m3bar-ico" aria-label="{{ $say('Menu', 'منو') }}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                            </button>
                            <span class="m3bar-acts">
                                <button type="button" class="m3bar-ico" aria-label="{{ $say('Search', 'جست‌وجو') }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                                </button>
                                <button type="button" class="m3bar-ico" aria-haspopup="menu" x-bind:aria-expanded="menu.toString()" aria-label="{{ $say('More options', 'گزینه‌های بیشتر') }}" x-on:click="menu = !menu">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5.5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="18.5" r="1.7"/></svg>
                                </button>
                            </span>
                        </div>
                        <h1 class="m3bar-title">{{ $say('My library', 'کتابخانهٔ من') }}</h1>
                        <div class="m3bar-menu" x-show="menu" x-cloak role="menu">
                            <button type="button" role="menuitem" x-on:click="menu = false">{{ $say('Sort by recent', 'مرتب‌سازی بر اساس تازه‌ها') }}</button>
                            <button type="button" role="menuitem" x-on:click="menu = false">{{ $say('Grid view', 'نمای شبکه‌ای') }}</button>
                            <button type="button" role="menuitem" x-on:click="menu = false">{{ $say('Refresh library', 'به‌روزرسانی کتابخانه') }}</button>
                        </div>
                    </header>

                    <div class="m3bar-list">
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#c4b5fd,#6d28d9)" aria-hidden="true"></i><span><b>{{ $say('Night tar suite', 'سوئیت شبِ تار') }}</b><small>کیهان کلهر</small></span><time>12:40</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#fdba74,#c2410c)" aria-hidden="true"></i><span><b>{{ $say('Shiraz alleys', 'کوچه‌های شیراز') }}</b><small>سیما بینا</small></span><time>3:52</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#86efac,#15803d)" aria-hidden="true"></i><span><b>{{ $say('Road to the north', 'جادهٔ شمال') }}</b><small>رستاک</small></span><time>4:31</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#93c5fd,#1d4ed8)" aria-hidden="true"></i><span><b>{{ $say('Tea night', 'شبِ چای') }}</b><small>کیهان کلهر</small></span><time>7:18</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#f9a8d4,#9d174d)" aria-hidden="true"></i><span><b>{{ $say('Rain on the window', 'باران پشت پنجره') }}</b><small>شهرام ناظری</small></span><time>5:04</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#67e8f9,#0e7490)" aria-hidden="true"></i><span><b>{{ $say('Caspian wind', 'باد خزر') }}</b><small>محمد رضا شجریان</small></span><time>6:22</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#fde68a,#a16207)" aria-hidden="true"></i><span><b>{{ $say('Harvest song', 'ترانهٔ خرمن') }}</b><small>پریسا</small></span><time>3:37</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#a5b4fc,#4338ca)" aria-hidden="true"></i><span><b>{{ $say('Quiet piano', 'پیانوی آرام') }}</b><small>-&nbsp;{{ $say('sleep mix', 'میکس خواب') }}</small></span><time>44:00</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#5eead4,#0f766e)" aria-hidden="true"></i><span><b>{{ $say('Long road', 'جادهٔ دراز') }}</b><small>قباد</small></span><time>4:03</time></div>
                        <div class="m3bar-track"><i style="background:linear-gradient(140deg,#fca5a5,#b91c1c)" aria-hidden="true"></i><span><b>{{ $say('Old friends', 'دوستان قدیمی') }}</b><small>داریوش</small></span><time>4:44</time></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The other two heights', 'دو ارتفاع دیگر') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Small — 64 px, title beside the icon — and medium — 112 px, title settled at the bottom; the collapsed bar above ends its life as the small one.', 'small با ۶۴ پیکسل و تیتر کنار آیکن، و medium با ۱۱۲ پیکسل و تیتری که ته نوار می‌نشیند — نوار جمع‌شدهٔ بالا عمرش را به‌عنوان همین small تمام می‌کند.') }}
        </p>
    </div>
    <div class="m3bar-root" style="inline-size: 100%">
        <div class="m3bar-spec">
            <div class="m3bar-spec-cell">
                <div class="m3bar-mini">
                    <header class="m3bar" data-size="small">
                        <div class="m3bar-row">
                            <button type="button" class="m3bar-ico" aria-label="{{ $say('Back', 'بازگشت') }}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="m3bar-flip" aria-hidden="true"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg>
                            </button>
                            <h1 class="m3bar-title">{{ $say('Now playing', 'در حال پخش') }}</h1>
                            <span class="m3bar-acts">
                                <button type="button" class="m3bar-ico" aria-label="{{ $say('Search', 'جست‌وجو') }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                                </button>
                            </span>
                        </div>
                    </header>
                </div>
                <small>small · 64</small>
            </div>
            <div class="m3bar-spec-cell">
                <div class="m3bar-mini">
                    <header class="m3bar m3bar-md" data-size="medium">
                        <div class="m3bar-row">
                            <button type="button" class="m3bar-ico" aria-label="{{ $say('Menu', 'منو') }}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                            </button>
                            <span class="m3bar-acts">
                                <button type="button" class="m3bar-ico" aria-label="{{ $say('Search', 'جست‌وجو') }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                                </button>
                                <button type="button" class="m3bar-ico" aria-label="{{ $say('More options', 'گزینه‌های بیشتر') }}">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5.5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="18.5" r="1.7"/></svg>
                                </button>
                            </span>
                        </div>
                        <h1 class="m3bar-title">{{ $say('Playlists', 'لیست‌های پخش') }}</h1>
                    </header>
                </div>
                <small>medium · 112</small>
            </div>
        </div>
    </div>
</section>
