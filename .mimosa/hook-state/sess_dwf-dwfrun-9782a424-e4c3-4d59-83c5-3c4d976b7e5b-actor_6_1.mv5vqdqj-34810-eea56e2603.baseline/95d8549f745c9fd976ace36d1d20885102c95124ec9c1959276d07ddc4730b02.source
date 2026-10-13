{{--
    A Sonoma window over a dusk wallpaper: 12px traffic lights whose glyphs
    appear on hover or group focus, a 52px unified toolbar with a centred
    title, a vibrant mini sidebar behind a hairline, five Persian project
    rows in the content list, and the big soft shadow real windows cast.
    The zoom button toggles a subtle scale animation.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .mcwin-root {
        --mcwin-win: #ECECEC; --mcwin-text: #1E1E1E; --mcwin-text2: #6D6D72; --mcwin-accent: #007AFF;
        --mcwin-hair: rgba(0, 0, 0, .15); --mcwin-div: rgba(0, 0, 0, .1);
        --mcwin-ctrl: #FFFFFF; --mcwin-side: rgba(236, 236, 236, .72);
        --mcwin-red: #FF5F57; --mcwin-yellow: #FEBC2E; --mcwin-green: #28C840;
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcwin-root {
        --mcwin-win: #282828; --mcwin-text: #F5F5F5; --mcwin-text2: #A5A5AA; --mcwin-accent: #0A84FF;
        --mcwin-hair: rgba(255, 255, 255, .15); --mcwin-div: rgba(255, 255, 255, .1);
        --mcwin-ctrl: #333336; --mcwin-side: rgba(40, 40, 40, .72);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcwin-root {
            --mcwin-win: #282828; --mcwin-text: #F5F5F5; --mcwin-text2: #A5A5AA; --mcwin-accent: #0A84FF;
            --mcwin-hair: rgba(255, 255, 255, .15); --mcwin-div: rgba(255, 255, 255, .1);
            --mcwin-ctrl: #333336; --mcwin-side: rgba(40, 40, 40, .72);
        }
    }

    .mcwin-stage {
        position: relative; overflow: clip; display: grid; place-items: center;
        container-type: inline-size;
        min-block-size: 27rem; padding: 2.25rem 1rem; border-radius: var(--nx-radius-2xl);
        background: linear-gradient(140deg, #33507E 0%, #7C5E93 52%, #D99976 100%);
    }
    .mcwin-stage i { position: absolute; inline-size: 46%; aspect-ratio: 1; border-radius: 50%; filter: blur(46px); opacity: .55; animation: mcwin-drift 16s ease-in-out infinite alternate; }
    .mcwin-stage i:nth-child(1) { inset-block-start: -12%; inset-inline-start: -6%; background: #5AC8FA; }
    .mcwin-stage i:nth-child(2) { inset-block-end: -18%; inset-inline-end: -8%; background: #FF9F0A; animation-delay: -8s; }
    @keyframes mcwin-drift { to { translate: 6% -7%; scale: 1.12; } }
    @media (prefers-reduced-motion: reduce) { .mcwin-stage i { animation: none; } }

    .mcwin-frame {
        position: relative; z-index: 1; display: grid; grid-template-rows: 52px minmax(0, 1fr);
        inline-size: min(100%, 30rem); block-size: 23rem; border-radius: 10px; overflow: clip;
        color: var(--mcwin-text); background: color-mix(in srgb, var(--mcwin-win) 84%, transparent);
        backdrop-filter: blur(20px) saturate(1.4);
        box-shadow: 0 22px 70px 4px rgba(0, 0, 0, .28), 0 0 0 .5px rgba(0, 0, 0, .26);
        transition: scale .5s cubic-bezier(.2, .9, .3, 1);
    }
    .mcwin-frame[data-zoomed] { scale: 1.04; }

    .mcwin-bar {
        position: relative; z-index: 2; display: flex; align-items: center; gap: 12px;
        padding-inline: 12px; background: color-mix(in srgb, var(--mcwin-win) 76%, transparent);
        border-block-end: 1px solid var(--mcwin-hair);
    }
    .mcwin-title {
        position: absolute; inset-inline: 0; margin-inline: auto; inline-size: max-content;
        font: 600 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; pointer-events: none;
    }
    .mcwin-lights { display: flex; gap: 8px; }
    .mcwin-light {
        display: grid; place-items: center; inline-size: 12px; aspect-ratio: 1; padding: 0;
        border: 0; border-radius: 50%; cursor: pointer; box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .22);
    }
    .mcwin-light[data-close] { background: var(--mcwin-red); }
    .mcwin-light[data-min] { background: var(--mcwin-yellow); }
    .mcwin-light[data-zoom] { background: var(--mcwin-green); }
    .mcwin-light svg { inline-size: 8px; block-size: 8px; fill: none; stroke: rgba(0, 0, 0, .55); stroke-width: 1.6; stroke-linecap: round; opacity: 0; transition: opacity .12s; }
    .mcwin-light[data-zoom] svg { fill: rgba(0, 0, 0, .55); stroke: none; }
    .mcwin-lights:hover .mcwin-light svg, .mcwin-lights:focus-within .mcwin-light svg { opacity: 1; }

    .mcwin-tool {
        display: grid; place-items: center; inline-size: 28px; aspect-ratio: 1; padding: 0;
        border: 0; border-radius: 6px; cursor: pointer; color: var(--mcwin-text2); background: transparent;
    }
    .mcwin-tool:hover { background: rgba(0, 0, 0, .08); color: var(--mcwin-text); }
    html[data-theme="dark"] .mcwin-tool:hover { background: rgba(255, 255, 255, .1); }
    .mcwin-tool svg { inline-size: 15px; block-size: 15px; fill: none; stroke: currentColor; stroke-width: 1.7; stroke-linecap: round; stroke-linejoin: round; }
    .mcwin-tool[data-active] { color: var(--mcwin-accent); background: color-mix(in srgb, var(--mcwin-accent) 16%, transparent); }
    .mcwin-end { margin-inline-start: auto; }

    .mcwin-body { display: grid; grid-template-columns: 168px minmax(0, 1fr); min-block-size: 0; }
    .mcwin-body[data-noside] { grid-template-columns: 0 minmax(0, 1fr); }
    .mcwin-body[data-noside] .mcwin-side { padding-inline: 0; border-inline-end-color: transparent; }
    /* A narrow stage (phones) folds the vibrant strip away like the toggle does,
       so the file list keeps real room and its badges are never half-cut, and
       hides the centred title where it would cross the toolbar tools. */
    @container (max-width: 26rem) {
        .mcwin-body, .mcwin-body[data-noside] { grid-template-columns: minmax(0, 1fr); }
        .mcwin-side { display: none; }
    }
    @container (max-width: 17.5rem) {
        .mcwin-title { display: none; }
    }
    .mcwin-side {
        overflow: clip; padding: 10px 8px; background: var(--mcwin-side);
        border-inline-end: 1px solid var(--mcwin-hair); transition: padding .25s;
    }
    .mcwin-side h4 { margin: 0 0 4px; padding-inline: 8px; font: 700 11px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcwin-text2); }
    .mcwin-nav {
        display: flex; align-items: center; gap: 7px; block-size: 26px; padding-inline: 8px;
        border: 0; border-radius: 5px; inline-size: 100%; cursor: pointer;
        font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcwin-text); background: transparent; text-align: start;
    }
    .mcwin-nav:hover { background: rgba(0, 0, 0, .06); }
    .mcwin-nav[data-current] { background: var(--mcwin-accent); color: #fff; }
    .mcwin-nav svg { inline-size: 14px; block-size: 14px; flex: none; }

    .mcwin-list { overflow: auto; padding: 6px; }
    .mcwin-file {
        display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 6px;
        font: 400 13px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    .mcwin-file + .mcwin-file { border-block-start: 1px solid var(--mcwin-div); border-radius: 0; }
    .mcwin-file:first-child { border-start-start-radius: 6px; border-start-end-radius: 6px; }
    .mcwin-file:last-child { border-end-start-radius: 6px; border-end-end-radius: 6px; }
    .mcwin-file[data-current] { background: color-mix(in srgb, var(--mcwin-accent) 18%, transparent); }
    .mcwin-file b { font-weight: 500; }
    .mcwin-file small { margin-inline-start: auto; color: var(--mcwin-text2); font-size: 11px; white-space: nowrap; }
    .mcwin-badge {
        flex: none; display: grid; place-items: center; inline-size: 26px; aspect-ratio: 1;
        border-radius: 7px; font-size: 13px; color: #fff; background: linear-gradient(160deg, #6EC6FF, #0A63C9);
    }
    .mcwin-root :is(button, input):focus-visible { outline: 2px solid var(--mcwin-accent); outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .mcwin-frame { transition: none; } }
</style>

<section class="pg-box mcwin-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The Mac window', 'پنجرهٔ مک') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Hover the traffic lights to see their glyphs; the zoom button gently scales the frame and the sidebar toggle folds the vibrant strip away.', 'چراغ‌های راهنما را hover کنید تا گلیف‌هایشان دیده شود؛ دکمهٔ بزرگ‌نمایی قاب را با ظرافت مقیاس می‌دهد و کلید نوار کنار، نوارِ پرنور را جمع می‌کند.') }}
        </p>
    </div>

    <div class="mcwin-stage" aria-hidden="false">
        <i aria-hidden="true"></i><i aria-hidden="true"></i>
        <div class="mcwin-frame" x-data="{ zoomed: false, side: true }" :data-zoomed="zoomed ? '' : null">
            <header class="mcwin-bar">
                <div class="mcwin-lights" role="group" aria-label="{{ $say('Window controls', 'کنترل‌های پنجره') }}">
                    <button type="button" class="mcwin-light" data-close aria-label="{{ $say('Close', 'بستن') }}">
                        <svg viewBox="0 0 8 8" aria-hidden="true"><path d="M1.6 1.6l4.8 4.8M6.4 1.6L1.6 6.4"/></svg>
                    </button>
                    <button type="button" class="mcwin-light" data-min aria-label="{{ $say('Minimise', 'کوچک‌کردن') }}">
                        <svg viewBox="0 0 8 8" aria-hidden="true"><path d="M1.4 4h5.2"/></svg>
                    </button>
                    <button type="button" class="mcwin-light" data-zoom :aria-pressed="zoomed ? 'true' : 'false'"
                            aria-label="{{ $say('Zoom', 'بزرگ‌نمایی') }}" x-on:click="zoomed = ! zoomed">
                        <svg viewBox="0 0 8 8" aria-hidden="true"><path d="M4.4 1H7v2.6zM3.6 7H1V4.4z"/></svg>
                    </button>
                </div>
                <span class="mcwin-title">{{ $say('Projects', 'پروژه‌ها') }}</span>
                <button type="button" class="mcwin-tool" :data-active="side ? '' : null"
                        aria-label="{{ $say('Toggle sidebar', 'نمایش یا پنهان‌سازی نوار کنار') }}" x-on:click="side = ! side">
                    <svg viewBox="0 0 16 16" aria-hidden="true"><rect x="1.5" y="2.5" width="13" height="11" rx="2"/><path d="M6 2.5v11"/></svg>
                </button>
                <span class="mcwin-end"></span>
                <button type="button" class="mcwin-tool" aria-label="{{ $say('Share', 'هم‌رسانی') }}">
                    <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 10V2M5.2 4.4L8 1.6l2.8 2.8M3 8.5V12a1.5 1.5 0 0 0 1.5 1.5h7A1.5 1.5 0 0 0 13 12V8.5"/></svg>
                </button>
            </header>

            <div class="mcwin-body" :data-noside="side ? null : ''">
                <aside class="mcwin-side">
                    <h4>{{ $say('Favourites', 'موردعلاقه‌ها') }}</h4>
                    <button type="button" class="mcwin-nav" data-current>
                        <svg viewBox="0 0 16 16" aria-hidden="true"><path fill="#4A9DF8" d="M1.5 4.2c0-.9.7-1.6 1.6-1.6h2.3l1.3 1.6h6.2c.9 0 1.6.7 1.6 1.6v5.9c0 .9-.7 1.6-1.6 1.6H3.1c-.9 0-1.6-.7-1.6-1.6z"/></svg>
                        {{ $say('Projects', 'پروژه‌ها') }}
                    </button>
                    <button type="button" class="mcwin-nav">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><circle cx="8" cy="8" r="6.2" fill="none" stroke="#8E8E93" stroke-width="1.6"/><path d="M8 4.6V8l2.3 1.6" fill="none" stroke="#8E8E93" stroke-width="1.6" stroke-linecap="round"/></svg>
                        {{ $say('Recent', 'اخیر') }}
                    </button>
                    <button type="button" class="mcwin-nav">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><g fill="#BF5AF2"><rect x="2" y="2" width="5" height="5" rx="1.2"/><rect x="9" y="2" width="5" height="5" rx="1.2"/><rect x="2" y="9" width="5" height="5" rx="1.2"/><rect x="9" y="9" width="5" height="5" rx="1.2"/></g></svg>
                        {{ $say('Applications', 'برنامه‌ها') }}
                    </button>
                    <button type="button" class="mcwin-nav">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 2v7M5.2 6.4L8 9.2l2.8-2.8M2.5 11v1.6c0 .8.6 1.4 1.4 1.4h8.2c.8 0 1.4-.6 1.4-1.4V11" fill="none" stroke="#32A852" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ $say('Downloads', 'دانلودها') }}
                    </button>
                </aside>

                <main class="mcwin-list" aria-label="{{ $say('Project files', 'پرونده‌های پروژه') }}">
                    <div class="mcwin-file" data-current>
                        <span class="mcwin-badge" aria-hidden="true">🌐</span>
                        <b>{{ $say('Nabu website redesign', 'بازطراحی وب‌سایت نابو') }}</b>
                        <small>۲۴ {{ $say('items', 'مورد') }} · ۱۴:۰۵</small>
                    </div>
                    <div class="mcwin-file">
                        <span class="mcwin-badge" style="background: linear-gradient(160deg, #FF9F0A, #E0780A)" aria-hidden="true">🛍</span>
                        <b>{{ $say('Basket app', 'اپلیکیشن سبد خرید') }}</b>
                        <small>۱۸ {{ $say('items', 'مورد') }} · ۱۲:۳۰</small>
                    </div>
                    <div class="mcwin-file">
                        <span class="mcwin-badge" style="background: linear-gradient(160deg, #BF5AF2, #8E3AD6)" aria-hidden="true">📘</span>
                        <b>{{ $say('Library docs', 'مستندات کتابخانه') }}</b>
                        <small>۴۱ {{ $say('items', 'مورد') }} · دیروز</small>
                    </div>
                    <div class="mcwin-file">
                        <span class="mcwin-badge" style="background: linear-gradient(160deg, #32D74B, #1E9E38)" aria-hidden="true">🌱</span>
                        <b>{{ $say('Nowruz ۱۴۰۵ campaign', 'کمپین نوروز ۱۴۰۵') }}</b>
                        <small>۱۲ {{ $say('items', 'مورد') }} · دیروز</small>
                    </div>
                    <div class="mcwin-file">
                        <span class="mcwin-badge" style="background: linear-gradient(160deg, #FF6482, #E0245E)" aria-hidden="true">📊</span>
                        <b>{{ $say('Autumn quarterly report', 'گزارش سه‌ماههٔ پاییز') }}</b>
                        <small>۷ {{ $say('items', 'مورد') }} · ۲ مهر</small>
                    </div>
                </main>
            </div>
        </div>
    </div>
</section>
