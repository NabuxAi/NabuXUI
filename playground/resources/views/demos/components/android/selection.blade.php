{{--
    Material 3 selection controls as a notification-settings sheet: the 52×32
    switch whose thumb grows 16→24 and swaps dash for tick, the checkbox that
    draws its tick as an SVG path (plus an error row), three radios that
    ripple from centre, and the 12% state layer on every row.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3sel-root {
        --m3sel-primary: #6750A4; --m3sel-on-primary: #FFFFFF;
        --m3sel-primary-container: #EADDFF; --m3sel-on-primary-container: #21005D;
        --m3sel-secondary-container: #E8DEF8; --m3sel-on-secondary-container: #1D192B;
        --m3sel-surface: #FEF7FF; --m3sel-surface-container: #F3EDF7;
        --m3sel-surface-container-high: #ECE6F0; --m3sel-surface-container-highest: #E6E0E9;
        --m3sel-on-surface: #1D1B20; --m3sel-on-surface-variant: #49454F;
        --m3sel-outline: #79747E; --m3sel-outline-variant: #CAC4D0;
        --m3sel-error: #B3261E;
        --m3sel-ease: cubic-bezier(.2, 0, 0, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3sel-root {
        --m3sel-primary: #D0BCFF; --m3sel-on-primary: #381E72;
        --m3sel-primary-container: #4F378B; --m3sel-on-primary-container: #EADDFF;
        --m3sel-secondary-container: #4A4458; --m3sel-on-secondary-container: #E8DEF8;
        --m3sel-surface: #141218; --m3sel-surface-container: #211F26;
        --m3sel-surface-container-high: #2B2930; --m3sel-surface-container-highest: #36343B;
        --m3sel-on-surface: #E6E0E9; --m3sel-on-surface-variant: #CAC4D0;
        --m3sel-outline: #938F99; --m3sel-outline-variant: #49454F;
        --m3sel-error: #F2B8B5;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3sel-root {
            --m3sel-primary: #D0BCFF; --m3sel-on-primary: #381E72;
            --m3sel-primary-container: #4F378B; --m3sel-on-primary-container: #EADDFF;
            --m3sel-secondary-container: #4A4458; --m3sel-on-secondary-container: #E8DEF8;
            --m3sel-surface: #141218; --m3sel-surface-container: #211F26;
            --m3sel-surface-container-high: #2B2930; --m3sel-surface-container-highest: #36343B;
            --m3sel-on-surface: #E6E0E9; --m3sel-on-surface-variant: #CAC4D0;
            --m3sel-outline: #938F99; --m3sel-outline-variant: #49454F;
            --m3sel-error: #F2B8B5;
        }
    }
    .m3sel-sheet { inline-size: min(100%, 22rem); border-radius: 1.75rem; background: var(--m3sel-surface-container); padding: 1.1rem .35rem 1.2rem; }
    .m3sel-group-title { margin: .35rem 1.15rem .45rem; font: 500 .78rem/1 Roboto, system-ui, sans-serif; color: var(--m3sel-primary); letter-spacing: .3px; }
    .m3sel-row { position: relative; display: flex; align-items: center; gap: .9rem; padding: .7rem 1.15rem; cursor: pointer; -webkit-tap-highlight-color: transparent; }
    .m3sel-row::before { content: ''; position: absolute; inset: 0; background: var(--m3sel-on-surface); opacity: 0; transition: opacity .12s; pointer-events: none; }
    .m3sel-row:hover::before { opacity: .06; }
    .m3sel-row:active::before { opacity: .12; }
    .m3sel-text { display: grid; gap: .1rem; }
    .m3sel-text b { font: 500 .9rem/1.35 Roboto, system-ui, sans-serif; color: var(--m3sel-on-surface); }
    .m3sel-text small { font: 400 .74rem/1.4 Roboto, system-ui, sans-serif; color: var(--m3sel-on-surface-variant); }
    .m3sel-row[data-error='true'] .m3sel-text small { color: var(--m3sel-error); }
    .m3sel-ctl { position: relative; overflow: clip; flex: none; }
    .m3sel-wave { position: absolute; border-radius: 50%; background: var(--m3sel-primary); opacity: .18; pointer-events: none; animation: m3sel-boil .45s ease-out forwards; }
    @keyframes m3sel-boil { from { scale: 0; opacity: .18; } to { scale: 1; opacity: 0; } }
    .m3sel-switch { display: inline-flex; }
    .m3sel-switch input, .m3sel-check input, .m3sel-radio input { position: absolute; opacity: 0; inline-size: 1px; block-size: 1px; }
    .m3sel-track { display: flex; align-items: center; inline-size: 3.25rem; block-size: 2rem; padding: 0; border-radius: 999px; border: 2px solid var(--m3sel-outline); background: var(--m3sel-surface-container-highest); transition: background-color .2s var(--m3sel-ease), border-color .2s var(--m3sel-ease); }
    .m3sel-switch input:checked + .m3sel-track { background: var(--m3sel-primary); border-color: var(--m3sel-primary); }
    .m3sel-switch input:focus-visible + .m3sel-track { outline: 2px solid var(--m3sel-primary); outline-offset: 2px; }
    .m3sel-thumb { position: relative; display: grid; place-items: center; inline-size: 1rem; aspect-ratio: 1; margin-inline-start: .25rem; border-radius: 50%; background: var(--m3sel-outline); transition: inline-size .2s var(--m3sel-ease), margin-inline-start .2s var(--m3sel-ease), background-color .2s var(--m3sel-ease); }
    .m3sel-switch input:checked + .m3sel-track .m3sel-thumb { inline-size: 1.5rem; margin-inline-start: 1.25rem; background: var(--m3sel-on-primary); }
    .m3sel-thumb svg { position: absolute; transition: opacity .15s, scale .2s var(--m3sel-ease); }
    .m3sel-thumb .m3sel-ico-off { color: var(--m3sel-surface-container-highest); opacity: 1; scale: 1; }
    .m3sel-thumb .m3sel-ico-on { color: var(--m3sel-primary); opacity: 0; scale: .4; }
    .m3sel-switch input:checked + .m3sel-track .m3sel-ico-off { opacity: 0; scale: .4; }
    .m3sel-switch input:checked + .m3sel-track .m3sel-ico-on { opacity: 1; scale: 1; }
    .m3sel-box, .m3sel-dot { display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1; border-radius: .55rem; border: 2px solid var(--m3sel-on-surface-variant); background: transparent; transition: background-color .18s var(--m3sel-ease), border-color .18s var(--m3sel-ease); }
    .m3sel-box svg path { stroke: var(--m3sel-on-primary); stroke-dasharray: 24; stroke-dashoffset: 24; transition: stroke-dashoffset .22s var(--m3sel-ease) .05s; }
    .m3sel-check input:checked ~ .m3sel-box { background: var(--m3sel-primary); border-color: var(--m3sel-primary); }
    .m3sel-check input:checked ~ .m3sel-box svg path { stroke-dashoffset: 0; }
    .m3sel-check input:focus-visible ~ .m3sel-box, .m3sel-radio input:focus-visible ~ .m3sel-dot { outline: 2px solid var(--m3sel-primary); outline-offset: 2px; }
    .m3sel-row[data-error='true'] .m3sel-box { border-color: var(--m3sel-error); }
    .m3sel-row[data-error='true'] .m3sel-check input:checked ~ .m3sel-box { background: var(--m3sel-error); }
    .m3sel-row[data-error='true'] .m3sel-check input:checked ~ .m3sel-box svg path { stroke: var(--m3sel-on-primary); }
    .m3sel-dot { border-radius: 50%; }
    .m3sel-dot i { inline-size: .625rem; aspect-ratio: 1; border-radius: 50%; background: var(--m3sel-primary); scale: 0; transition: scale .2s var(--m3sel-ease); }
    .m3sel-radio input:checked ~ .m3sel-dot { border-color: var(--m3sel-primary); }
    .m3sel-radio input:checked ~ .m3sel-dot i { scale: 1; }
    .m3sel-divider { margin: .45rem 1.15rem; border: 0; border-block-start: 1px solid var(--m3sel-outline-variant); }
    .m3sel-spec { display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; align-items: center; }
    .m3sel-cell { display: grid; gap: .55rem; justify-items: center; }
    .m3sel-cell small { font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); }
    /* The shared props table below the demo holds its rows at opacity 0 until a
       scroll observer marks it revealed — a screenshot without scrolling never
       gets there, so the body renders empty. These page-scoped overrides (they
       only match where this partial's .m3sel-root exists) keep the rows always
       visible, and let the long “What it does” column wrap instead of running
       off the container edge on narrow screens. */
    .nx-page:has(.m3sel-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    /* Narrow screens: the stock nowrap cells overflow the container and clip the
       last column at the edge. A fixed layout with set shares keeps all four
       columns inside the box; the prose column wraps instead of being cut. */
    @media (max-width: 56rem) {
        .nx-page:has(.m3sel-root) .nx-data-table { table-layout: fixed; }
        .nx-page:has(.m3sel-root) .nx-data-table th, .nx-page:has(.m3sel-root) .nx-data-table td { white-space: normal; overflow-wrap: anywhere; padding-inline: .55rem; }
        .nx-page:has(.m3sel-root) .nx-data-table thead th:nth-child(1) { width: 24%; }
        .nx-page:has(.m3sel-root) .nx-data-table thead th:nth-child(2) { width: 20%; }
        .nx-page:has(.m3sel-root) .nx-data-table thead th:nth-child(3) { width: 25%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3sel-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Notification settings', 'تنظیمات اعلان') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The switch thumb grows from 16 to 24 px and its dash becomes a tick, the checkbox draws its SVG tick, the radios ripple from centre, and every row carries the 12% state layer.', 'انگشت سوییچ از ۱۶ به ۲۴ پیکسل می‌رسد و خط‌علامتش تیک می‌شود، چک‌باکس تیکِ مسیر SVG را می‌کشد، رادیوها از مرکز موج می‌گیرند و هر ردیف لایهٔ حالت ۱۲٪ دارد.') }}
        </p>
    </div>

    <div class="m3sel-root" x-data="{
            wave(e) {
                const c = e.target.closest('.m3sel-ctl');
                if (!c) return;
                const r = c.getBoundingClientRect();
                const s = document.createElement('span');
                s.className = 'm3sel-wave';
                s.style.inlineSize = s.style.blockSize = Math.max(r.width, r.height) * 2.4 + 'px';
                s.style.left = (r.width / 2 - r.width * 1.2) + 'px';
                s.style.top = (r.height / 2 - r.width * 1.2) + 'px';
                c.appendChild(s);
                s.addEventListener('animationend', () => s.remove());
            },
        }" x-on:pointerdown="wave($event)">
        <div class="m3sel-sheet" role="group" aria-label="{{ $say('Notifications', 'اعلان‌ها') }}">
            <p class="m3sel-group-title">{{ $say('Delivery', 'رساندن') }}</p>
            <label class="m3sel-row">
                <span class="m3sel-text">
                    <b>{{ $say('Instant notifications', 'اعلان‌های فوری') }}</b>
                    <small>{{ $say('Messages arrive the second they are sent', 'پیام‌ها همان لحظهٔ ارسال می‌رسند') }}</small>
                </span>
                <span class="m3sel-switch m3sel-ctl">
                    <input type="checkbox" role="switch" checked aria-label="{{ $say('Instant notifications', 'اعلان‌های فوری') }}">
                    <span class="m3sel-track">
                        <span class="m3sel-thumb">
                            <svg class="m3sel-ico-off" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M6 12h12"/></svg>
                            <svg class="m3sel-ico-on" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        </span>
                    </span>
                </span>
            </label>
            <label class="m3sel-row">
                <span class="m3sel-text">
                    <b>{{ $say('Night reminder', 'یادآور شبانه') }}</b>
                    <small>{{ $say('A quiet summary at 22:00', 'خلاصه‌ای آرام ساعت ۲۲:۰۰') }}</small>
                </span>
                <span class="m3sel-switch m3sel-ctl">
                    <input type="checkbox" role="switch" aria-label="{{ $say('Night reminder', 'یادآور شبانه') }}">
                    <span class="m3sel-track">
                        <span class="m3sel-thumb">
                            <svg class="m3sel-ico-off" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M6 12h12"/></svg>
                            <svg class="m3sel-ico-on" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        </span>
                    </span>
                </span>
            </label>

            <hr class="m3sel-divider">
            <p class="m3sel-group-title">{{ $say('Digest', 'خلاصه') }}</p>
            <label class="m3sel-row">
                <span class="m3sel-check m3sel-ctl">
                    <input type="checkbox" checked aria-label="{{ $say('Daily digest', 'خلاصهٔ روزانه') }}">
                    <span class="m3sel-box"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                </span>
                <span class="m3sel-text">
                    <b>{{ $say('Daily digest', 'خلاصهٔ روزانه') }}</b>
                    <small>{{ $say('One email every morning at 8', 'روزی یک ایمیل، هر صبح ساعت ۸') }}</small>
                </span>
            </label>
            <label class="m3sel-row" data-error="true">
                <span class="m3sel-check m3sel-ctl">
                    <input type="checkbox" aria-label="{{ $say('Weekly report percentages', 'درصدهای گزارش هفتگی') }}">
                    <span class="m3sel-box"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                </span>
                <span class="m3sel-text">
                    <b>{{ $say('Weekly report percentages', 'درصدهای گزارش هفتگی') }}</b>
                    <small>{{ $say('Required for the analytics module', 'برای ماژول گزارش‌ها لازم است') }}</small>
                </span>
            </label>

            <hr class="m3sel-divider">
            <p class="m3sel-group-title">{{ $say('Alert style', 'سبک هشدار') }}</p>
            <label class="m3sel-row">
                <span class="m3sel-radio m3sel-ctl">
                    <input type="radio" name="m3sel-style" checked aria-label="{{ $say('All alerts', 'همهٔ هشدارها') }}">
                    <span class="m3sel-dot"><i></i></span>
                </span>
                <span class="m3sel-text">
                    <b>{{ $say('All alerts', 'همهٔ هشدارها') }}</b>
                    <small>{{ $say('With sound and vibration', 'با صدا و لرزش') }}</small>
                </span>
            </label>
            <label class="m3sel-row">
                <span class="m3sel-radio m3sel-ctl">
                    <input type="radio" name="m3sel-style" aria-label="{{ $say('Mentions only', 'فقط اشاره‌ها') }}">
                    <span class="m3sel-dot"><i></i></span>
                </span>
                <span class="m3sel-text">
                    <b>{{ $say('Mentions only', 'فقط اشاره‌ها') }}</b>
                    <small>{{ $say('When someone writes your name', 'وقتی کسی نام شما را بنویسد') }}</small>
                </span>
            </label>
            <label class="m3sel-row">
                <span class="m3sel-radio m3sel-ctl">
                    <input type="radio" name="m3sel-style" aria-label="{{ $say('Silent', 'بی‌صدا') }}">
                    <span class="m3sel-dot"><i></i></span>
                </span>
                <span class="m3sel-text">
                    <b>{{ $say('Silent', 'بی‌صدا') }}</b>
                    <small>{{ $say('A badge on the icon, nothing more', 'فقط یک نشان روی آیکن') }}</small>
                </span>
            </label>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The three controls', 'سه کنترل') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Off state uses the outline colour, on state pairs tone 90 with tone 10 — the exact Material pairing for contrast.', 'حالت خاموش رنگ خط‌ها را می‌گیرد و حالت روشن تُن ۹۰ را با تُن ۱۰ جفت می‌کند — دقیقاً جفت‌رنگ متریال برای کنتراست.') }}
        </p>
    </div>
    <div class="m3sel-root" x-data="{ wave(e) { const c = e.target.closest('.m3sel-ctl'); if (!c) return; const r = c.getBoundingClientRect(); const s = document.createElement('span'); s.className = 'm3sel-wave'; s.style.inlineSize = s.style.blockSize = Math.max(r.width, r.height) * 2.4 + 'px'; s.style.left = (r.width / 2 - r.width * 1.2) + 'px'; s.style.top = (r.height / 2 - r.width * 1.2) + 'px'; c.appendChild(s); s.addEventListener('animationend', () => s.remove()); } }" x-on:pointerdown="wave($event)">
        <div class="m3sel-spec">
            <div class="m3sel-cell">
                <label class="m3sel-switch m3sel-ctl">
                    <input type="checkbox" role="switch" checked aria-label="{{ $say('Specimen switch', 'سوییچ نمونه') }}">
                    <span class="m3sel-track">
                        <span class="m3sel-thumb">
                            <svg class="m3sel-ico-off" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M6 12h12"/></svg>
                            <svg class="m3sel-ico-on" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        </span>
                    </span>
                </label>
                <small>switch · 52×32</small>
            </div>
            <div class="m3sel-cell">
                <label class="m3sel-check m3sel-ctl">
                    <input type="checkbox" checked aria-label="{{ $say('Specimen checkbox', 'چک‌باکس نمونه') }}">
                    <span class="m3sel-box"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                </label>
                <small>checkbox</small>
            </div>
            <div class="m3sel-cell">
                <label class="m3sel-radio m3sel-ctl">
                    <input type="radio" name="m3sel-spec" checked aria-label="{{ $say('Specimen radio', 'رادیو نمونه') }}">
                    <span class="m3sel-dot"><i></i></span>
                </label>
                <small>radio</small>
            </div>
        </div>
    </div>
</section>
