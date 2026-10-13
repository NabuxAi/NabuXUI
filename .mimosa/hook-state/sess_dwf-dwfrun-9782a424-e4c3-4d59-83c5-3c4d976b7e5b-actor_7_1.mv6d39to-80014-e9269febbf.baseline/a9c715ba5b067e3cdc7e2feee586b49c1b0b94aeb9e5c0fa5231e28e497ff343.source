{{--
    Windows 11 window chrome over a desktop hint: a Notepad-like frame whose
    caption cluster keeps the official 46×32 hit areas, close glows #C42B1C
    under the pointer, and holding the maximize button opens the six snap
    layouts — picking a zone flashes the window into that shape. Escape
    closes the flyout.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    [x-cloak] { display: none !important; }
    .flwin-root {
        --fl-accent: #005FB8;
        --fl-accent-hover: #1A75C5;
        --fl-accent-press: #005399;
        --fl-on-accent: #FFFFFF;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
        --fl-control: rgba(255, 255, 255, .70);
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-control-stroke: rgba(0, 0, 0, .12);
        --fl-control-edge: rgba(0, 0, 0, .22);
        --fl-divider: rgba(0, 0, 0, .08);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-press: rgba(0, 0, 0, .06);
        --fl-close: #C42B1C;
        --fl-focus: #1B1B1B;
        --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .20), 0 0 0 1px rgba(0, 0, 0, .07);
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        /* minmax(0, 1fr), not auto: an auto track sizes to the titlebar's
           min-content (the nowrap title + the fixed 138px caption cluster),
           which at phone widths balloons the demo past the card and clips
           the desktop's end edge against the screen. */
        grid-template-columns: minmax(0, 1fr);
        gap: .75rem;
    }
    .flwin-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .flwin-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }
    .flwin-root svg { display: block; }

    .flwin-desktop {
        position: relative; overflow: clip; display: grid; place-items: center;
        /* Same clamp one level down: an auto track would size to the
           window's min-content and spill past the clamped root track. */
        grid-template-columns: minmax(0, 1fr);
        min-block-size: 23rem; padding: 2.25rem 1.25rem 3.25rem; border-radius: 8px;
        background:
            radial-gradient(120% 90% at 78% 8%, rgba(80, 160, 255, .45), transparent 55%),
            radial-gradient(90% 80% at 12% 92%, rgba(30, 90, 190, .5), transparent 65%),
            linear-gradient(158deg, #0B2C52, #11407A 52%, #1A5BA6);
    }
    .flwin-clock {
        /* inset-inline: 0 + margin-inline: auto centers in both directions;
           translate: -50% would flip meaning in RTL and poke the pill past
           the desktop's clipped start edge. */
        position: absolute; inset-block-end: .75rem; inset-inline: 0;
        margin-inline: auto; inline-size: fit-content;
        display: flex; align-items: center; gap: .5rem; padding: .3rem .85rem; border-radius: 999px;
        font-size: .7rem; color: #EAF3FF; background: rgba(255, 255, 255, .12);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .16);
        backdrop-filter: blur(20px) saturate(125%); -webkit-backdrop-filter: blur(20px) saturate(125%);
        white-space: nowrap;
    }

    .flwin-window {
        position: relative; z-index: 1; inline-size: min(100%, 30rem);
        border-radius: 8px; overflow: clip; background: var(--fl-window);
        box-shadow: 0 32px 64px rgba(0, 0, 0, .45), 0 0 0 1px rgba(0, 0, 0, .18);
        transition: inline-size .5s cubic-bezier(.2, .9, .25, 1), opacity .4s, translate .4s, scale .4s, block-size .3s;
    }
    .flwin-window[data-shape="half"] { inline-size: 54%; }
    .flwin-window[data-shape="third"] { inline-size: 38%; }
    .flwin-window[data-shape="big"] { inline-size: 68%; }
    .flwin-window[data-shape="quarter"] { inline-size: 30%; }
    .flwin-window[data-shape="wide"] { inline-size: 82%; }
    .flwin-window[data-shape="mid"] { inline-size: 62%; }
    .flwin-window[data-shape="full"] { inline-size: 100%; }
    .flwin-window.flwin-min { block-size: 2.5rem; }
    .flwin-window.flwin-bye { opacity: 0; translate: 0 12px; scale: .97; }

    .flwin-titlebar { display: flex; align-items: center; gap: .5rem; block-size: 2.5rem; padding-inline-start: .875rem; }
    .flwin-appicon { font-size: .8rem; }
    .flwin-title { flex: 1; min-inline-size: 0; font-size: .75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .flwin-caption { position: relative; display: flex; align-self: center; }
    .flwin-caption button { inline-size: 46px; block-size: 32px; display: grid; place-items: center; color: var(--fl-text); transition: background .12s, color .12s; border-radius: 0; }
    .flwin-caption button:hover { background: var(--fl-hover); }
    .flwin-caption button:active { background: var(--fl-press); color: var(--fl-text-2); }
    .flwin-caption .flwin-close:hover { background: var(--fl-close); color: #FFFFFF; }
    .flwin-caption .flwin-close:active { background: color-mix(in srgb, var(--fl-close) 78%, #000000); color: rgba(255, 255, 255, .8); }

    .flwin-snap { position: relative; display: grid; }
    .flwin-flyout {
        position: absolute; inset-block-start: calc(100% + 8px); inset-inline-end: -8px; z-index: 30;
        inline-size: 17rem; max-inline-size: calc(100vw - 3rem); padding: .875rem; border-radius: 8px;
        background: var(--fl-layer);
        backdrop-filter: blur(30px) saturate(125%); -webkit-backdrop-filter: blur(30px) saturate(125%);
        box-shadow: var(--fl-flyout-shadow);
    }
    .flwin-flyout > p { margin: 0 0 .625rem; font-size: .7rem; color: var(--fl-text-2); }
    .flwin-layouts { display: grid; grid-template-columns: repeat(3, 1fr); gap: .5rem; }
    .flwin-layout { display: grid; gap: 3px; block-size: 2.6rem; padding: 4px; border-radius: 4px; background: var(--fl-hover); }
    .flwin-layout button { min-inline-size: 0; min-block-size: 0; border-radius: 2px; background: var(--fl-window); box-shadow: inset 0 0 0 1px var(--fl-control-stroke); transition: background .12s, box-shadow .12s; }
    .flwin-layout button:hover { background: color-mix(in srgb, var(--fl-accent) 22%, var(--fl-window)); box-shadow: inset 0 0 0 1px var(--fl-accent); }
    .flwin-half2 { grid-template-columns: 1fr 1fr; }
    .flwin-third3 { grid-template-columns: 1fr 1fr 1fr; }
    .flwin-big-left { grid-template-columns: 2fr 1fr; grid-template-rows: 1fr 1fr; }
    .flwin-big-left button:first-child { grid-row: span 2; }
    .flwin-quads { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
    .flwin-big-top { grid-template-columns: 1fr 1fr; grid-template-rows: 2fr 1fr; }
    .flwin-big-top button:first-child { grid-column: span 2; }
    .flwin-mixed { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
    .flwin-mixed button:first-child { grid-row: span 2; }

    .flwin-body { padding: 1rem 1.25rem 1.25rem; min-block-size: 8.5rem; background: var(--fl-layer); border-block: 1px solid var(--fl-divider); }
    .flwin-body p { margin: 0; font-size: .8rem; line-height: 2; }
    .flwin-body p + p { margin-block-start: .35rem; }
    .flwin-status { display: flex; flex-wrap: wrap; gap: .35rem 1.1rem; align-items: center; padding: .35rem .875rem; font-size: .68rem; color: var(--fl-text-2); }
    .flwin-hint { margin: 0; text-align: center; font-size: .72rem; color: var(--fl-text-2); }

    /* Demo-page chrome repair, scoped to this page's props table: the shared
       data-table keeps every cell on one line, so at phone widths the long
       "What it does" notes run past the card and read cut off mid-word. Pages
       hosting this partial let the cells wrap instead — off this page, where
       .flwin-root never exists, it never loads. Only the note column (the
       last) may break inside a word; the short columns keep whole words. */
    @media (max-width: 48rem) {
        body:has(.flwin-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; }
        body:has(.flwin-root) .nx-data-table :is(th, td):last-child { overflow-wrap: anywhere; }
    }

    @media (prefers-reduced-motion: reduce) {
        .flwin-window, .flwin-caption button, .flwin-layout button { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The window, fully chromed', 'پنجره با قاب کامل') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Hold the maximize button and the six snap layouts appear; pick a zone and the window flashes into it. The close button glows red under the pointer.', 'دکمهٔ بزرگ‌نمایی را نگه دارید تا شش چیدمان snap بیاید؛ خانه‌ای را انتخاب کنید تا پنجره به همان شکل درآید. دکمهٔ بستن هم زیر اشاره‌گر سرخ می‌شود.') }}
        </p>
    </div>

    <div class="flwin-root" x-data="{
            snapOpen: false, armT: null, rstT: null, minT: null, byeT: null,
            shape: 'rest', mini: false, bye: false,
            hold() { this.cancel(); this.armT = setTimeout(() => { this.snapOpen = true }, 500) },
            cancel() { clearTimeout(this.armT) },
            retract() { this.cancel(); this.snapOpen = false },
            pick(s) { this.shape = s; this.snapOpen = false; this.cancel(); clearTimeout(this.rstT); this.rstT = setTimeout(() => { this.shape = 'rest' }, 950) },
            squish() { this.mini = true; clearTimeout(this.minT); this.minT = setTimeout(() => { this.mini = false }, 1100) },
            vanish() { this.bye = true; clearTimeout(this.byeT); this.byeT = setTimeout(() => { this.bye = false }, 800) },
        }"
        x-on:keydown.escape.window="retract()">
        <div class="flwin-desktop">
            <div class="flwin-window" :data-shape="shape" :class="bye ? 'flwin-bye' : (mini ? 'flwin-min' : '')">
                <div class="flwin-titlebar">
                    <span class="flwin-appicon" aria-hidden="true">📝</span>
                    <span class="flwin-title">{{ $say('Untitled — Notepad', 'بدون عنوان — Notepad') }}</span>
                    <div class="flwin-caption">
                        <button type="button" aria-label="{{ $say('Minimize', 'کوچک‌نمایی') }}" x-on:click="squish()">
                            <svg width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"><path d="M0 5.5h10" stroke="currentColor" stroke-width="1" fill="none"/></svg>
                        </button>
                        <div class="flwin-snap" x-on:mouseleave="retract()">
                            <button type="button" aria-label="{{ $say('Maximize — hold for snap layouts', 'بزرگ‌نمایی — برای snap نگه دارید') }}"
                                aria-haspopup="true" :aria-expanded="snapOpen ? 'true' : 'false'"
                                x-on:mouseenter="hold()" x-on:mouseleave="cancel()"
                                x-on:focus="hold()" x-on:blur="cancel()"
                                x-on:click="pick('full')">
                                <svg width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"><rect x=".5" y=".5" width="9" height="9" rx="1" stroke="currentColor" stroke-width="1" fill="none"/></svg>
                            </button>
                            <div class="flwin-flyout" role="menu" aria-label="{{ $say('Snap layouts', 'چیدمان‌های snap') }}" x-show="snapOpen" x-cloak x-transition.opacity.duration.150ms>
                                <p>{{ $say('Pick a zone to snap', 'خانه‌ای را برای چیدمان انتخاب کنید') }}</p>
                                <div class="flwin-layouts">
                                    <div class="flwin-layout flwin-half2" role="none">
                                        <button type="button" role="menuitem" aria-label="{{ $say('Left half', 'نیمهٔ ابتدا') }}" x-on:click="pick('half')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Right half', 'نیمهٔ انتها') }}" x-on:click="pick('half')"></button>
                                    </div>
                                    <div class="flwin-layout flwin-third3" role="none">
                                        <button type="button" role="menuitem" aria-label="{{ $say('First third', 'یک‌سوم اول') }}" x-on:click="pick('third')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Middle third', 'یک‌سوم میانی') }}" x-on:click="pick('third')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Last third', 'یک‌سوم آخر') }}" x-on:click="pick('third')"></button>
                                    </div>
                                    <div class="flwin-layout flwin-big-left" role="none">
                                        <button type="button" role="menuitem" aria-label="{{ $say('Large left', 'بزرگ سمت ابتدا') }}" x-on:click="pick('big')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Top right quarter', 'ربع بالای انتها') }}" x-on:click="pick('quarter')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Bottom right quarter', 'ربع پایین انتها') }}" x-on:click="pick('quarter')"></button>
                                    </div>
                                    <div class="flwin-layout flwin-quads" role="none">
                                        <button type="button" role="menuitem" aria-label="{{ $say('Top start quadrant', 'ربع بالای ابتدا') }}" x-on:click="pick('quarter')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Top end quadrant', 'ربع بالای انتها') }}" x-on:click="pick('quarter')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Bottom start quadrant', 'ربع پایین ابتدا') }}" x-on:click="pick('quarter')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Bottom end quadrant', 'ربع پایین انتها') }}" x-on:click="pick('quarter')"></button>
                                    </div>
                                    <div class="flwin-layout flwin-big-top" role="none">
                                        <button type="button" role="menuitem" aria-label="{{ $say('Large top', 'بزرگ بالا') }}" x-on:click="pick('wide')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Bottom start', 'پایین ابتدا') }}" x-on:click="pick('quarter')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Bottom end', 'پایین انتها') }}" x-on:click="pick('quarter')"></button>
                                    </div>
                                    <div class="flwin-layout flwin-mixed" role="none">
                                        <button type="button" role="menuitem" aria-label="{{ $say('Left half, tall', 'نیمهٔ ابتدا، بلند') }}" x-on:click="pick('mid')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Top end', 'بالای انتها') }}" x-on:click="pick('quarter')"></button>
                                        <button type="button" role="menuitem" aria-label="{{ $say('Bottom end', 'پایین انتها') }}" x-on:click="pick('quarter')"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="flwin-close" aria-label="{{ $say('Close', 'بستن') }}" x-on:click="vanish()">
                            <svg width="10" height="10" viewBox="0 0 10 10" aria-hidden="true"><path d="M.5.5l9 9M9.5.5l-9 9" stroke="currentColor" stroke-width="1" fill="none"/></svg>
                        </button>
                    </div>
                </div>
                <div class="flwin-body">
                    <p><strong>{{ $say('Product meeting notes — Mehr 7', 'یادداشت جلسهٔ محصول — ۷ مهر') }}</strong></p>
                    <p>
                        {{ $say('1. Command bar redesign lands by Mehr 14.', '۱. بازطراحی نوار فرمان تا ۱۴ مهر تکمیل شود.') }}<br>
                        {{ $say('2. Review the acrylic feedback thread.', '۲. بازخورد کاربران دربارهٔ آکریلیک بررسی شود.') }}<br>
                        {{ $say('3. Next call: Tuesday, 10:00.', '۳. قرار بعدی: سه‌شنبه ساعت ۱۰ صبح.') }}
                    </p>
                </div>
                <div class="flwin-status">
                    <span>{{ $say('Ln 4, Col 18', 'خط ۴، ستون ۱۸') }}</span>
                    <span>UTF-8</span>
                    <span>{{ $say('100%', '۱۰۰٪') }}</span>
                </div>
            </div>
            <div class="flwin-clock" aria-hidden="true">{{ $say('Tuesday, Mehr 15 — 14:32', 'سه‌شنبه ۱۵ مهر — ۱۴:۳۲') }}</div>
        </div>
        <p class="flwin-hint">
            {{ $say('Hold the maximize button for about half a second, then pick a zone — or try minimize and close.', 'دکمهٔ بزرگ‌نمایی را نیم‌ثانیه نگه دارید و خانه‌ای برگزینید؛ کوچک‌نمایی و بستن را هم امتحان کنید.') }}
        </p>
    </div>
</section>
