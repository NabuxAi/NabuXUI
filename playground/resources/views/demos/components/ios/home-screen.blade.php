{{--
    The whole iOS shell inside one phone: two icon pages with a glass dock and
    page dots, apps that zoom open (a real Settings and a real Weather, the
    rest placeholders), jiggle edit mode with delete badges, a home bar that
    swipes back — and a Control Centre that pulls down and really dims the
    wallpaper through --ioh-dims.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $fn = fn ($n) => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;

    // Tiny SF-like stroke glyphs that the icon set does not carry.
    $P = fn (string $d) => '<path d="' . $d . '"/>';
    $C = fn (string $cx, string $cy, string $r) => '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '"/>';
    $glyph = fn (string $inner) => '<svg class="nx-icon iohome-glyph" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
    $io = fn (string $name) => \NabuXUI\NabuXUI::icon($name)->toHtml();

    $dCam = $glyph($P('M8.6 7l1.2-2h4.4l1.2 2H19a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z') . $C('12', '12.8', '3.4'));
    $dClock = $glyph($C('12', '12', '8.6') . $P('M12 7.4V12l3 2.1'));
    $dCalc = $glyph($P('M6.5 3h11A1.5 1.5 0 0 1 19 4.5v15a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 5 19.5v-15A1.5 1.5 0 0 1 6.5 3z') . $P('M8.2 7h7.6') . $P('M8.6 11h.01M12 11h.01M15.4 11h.01M8.6 14.4h.01M12 14.4h.01M15.4 14.4h.01M8.6 17.8h.01M12 17.8h.01M15.4 17.8h.01'));
    $dWallet = $glyph($P('M17 9.2V7.4A1.4 1.4 0 0 0 15.6 6H5.4A1.4 1.4 0 0 0 4 7.4v9.2A1.4 1.4 0 0 0 5.4 18h12.2a1.4 1.4 0 0 0 1.4-1.4v-1') . $P('M16 9.2h3a1 1 0 0 1 1 1v2.6a1 1 0 0 1-1 1h-3a2.3 2.3 0 0 1 0-4.6z'));
    $dPin = $glyph($P('M12 21s-6.4-5.1-6.4-9.9a6.4 6.4 0 0 1 12.8 0C18.4 15.9 12 21 12 21z') . $C('12', '10.8', '2.3'));
    $dPlane = $glyph($P('M12 2.9c.6 0 1 .8 1 1.9v4.4l7 3.9v2l-7-1.9v4.1l2.2 1.7v1.4L12 19.5l-3.2.9V19l2.2-1.7v-4.1L4 15.1v-2l7-3.9V4.8c0-1.1.4-1.9 1-1.9z'));
    $dWifi = $glyph($P('M4.4 10.5a11.4 11.4 0 0 1 15.2 0') . $P('M7.2 13.6a7.2 7.2 0 0 1 9.6 0') . $P('M10.1 16.6a3.2 3.2 0 0 1 3.8 0') . $P('M12 19.3h.01'));
    $dBt = $glyph($P('M6.6 7.7l10.8 8.5L12 20.4V3.6l5.4 4.2L6.6 16.3'));
    $dTorch = $glyph($P('M8.2 3.5h7.6l-1.1 3.8H9.3z') . $P('M9.7 7.3V20a.9.9 0 0 0 .9.9h2.8a.9.9 0 0 0 .9-.9V7.3') . $P('M12 11.2v2.6'));
    $dCell = $glyph($P('M4.6 18.6v-2.4M9.2 18.6v-5M13.8 18.6v-7.6M18.4 18.6V6.2'));
    $dChev = $glyph($P('M9.4 5.6l6.4 6.4-6.4 6.4'));
    $dChevDown = $glyph($P('M5.8 9.2l6.2 6.2 6.2-6.2'));
    $dCloudSun = $glyph($P('M8.6 3.4v1.4M4 5.4l1 1M2.6 10.2H4M13.2 5.4l-1 1') . $C('8.4', '10', '2.5') . $P('M12.4 20a3.6 3.6 0 1 0-.4-7.2 4.6 4.6 0 0 0-8.8 1.9A3.1 3.1 0 0 0 4.6 20z'));
    $dCloud = $glyph($P('M17.4 19a4.4 4.4 0 0 0 .4-8.8 6.4 6.4 0 0 0-12.4 1.7A3.9 3.9 0 0 0 6.2 19z'));
    $dRain = $glyph($P('M17.4 16.4a4.4 4.4 0 0 0 .4-8.8 6.4 6.4 0 0 0-12.4 1.7 3.9 3.9 0 0 0 .8 7.1') . $P('M8.4 19l-.7 1.9M12.2 19l-.7 1.9M16 19l-.7 1.9'));
    $dMoon = $glyph($P('M19.6 13.2A7.8 7.8 0 1 1 10.8 4.4a6.2 6.2 0 0 0 8.8 8.8z'));
    $xSvg = '<svg class="nx-icon iohome-glyph" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" aria-hidden="true">' . $P('M6.5 6.5l11 11M17.5 6.5l-11 11') . '</svg>';

    $appsData = [
        ['id' => 'settings', 'label' => $say('Settings', 'تنظیمات'), 'kind' => 'settings', 'g' => 'linear-gradient(180deg,#b0b3ba,#55575e)', 'svg' => $io('settings')],
        ['id' => 'messages', 'label' => $say('Messages', 'پیام‌ها'), 'g' => 'linear-gradient(180deg,#71e574,#1fb434)', 'svg' => $io('message')],
        ['id' => 'photos', 'label' => $say('Photos', 'عکس‌ها'), 'tile' => 'photos', 'svg' => ''],
        ['id' => 'music', 'label' => $say('Music', 'موسیقی'), 'g' => 'linear-gradient(180deg,#fb5c74,#f3213c)', 'svg' => $io('music')],
        ['id' => 'calendar', 'label' => $say('Calendar', 'تقویم'), 'tile' => 'cal', 'svg' => ''],
        ['id' => 'maps', 'label' => $say('Maps', 'نقشه'), 'g' => 'linear-gradient(160deg,#79d787,#3ec1e0)', 'svg' => $dPin],
        ['id' => 'camera', 'label' => $say('Camera', 'دوربین'), 'g' => 'linear-gradient(180deg,#8a8c93,#4a4b52)', 'svg' => $dCam],
        ['id' => 'mail', 'label' => $say('Mail', 'ایمیل'), 'g' => 'linear-gradient(180deg,#2fa3ff,#0a63e8)', 'svg' => $io('mail')],
        ['id' => 'notes', 'label' => $say('Notes', 'یادداشت‌ها'), 'g' => 'linear-gradient(180deg,#fddc50,#f8b81e)', 'svg' => $io('edit')],
        ['id' => 'clock', 'label' => $say('Clock', 'ساعت'), 'g' => 'linear-gradient(180deg,#3c3c41,#17171a)', 'svg' => $dClock],
        ['id' => 'weather', 'label' => $say('Weather', 'آب‌وهوا'), 'kind' => 'weather', 'g' => 'linear-gradient(180deg,#46a0ff,#155fb8)', 'svg' => $dCloudSun],
        ['id' => 'calc', 'label' => $say('Calculator', 'ماشین‌حساب'), 'g' => 'linear-gradient(180deg,#3c3c41,#17171a)', 'svg' => $dCalc],
        ['id' => 'files', 'label' => $say('Files', 'فایل‌ها'), 'g' => 'linear-gradient(180deg,#54bdfd,#1c7ce0)', 'svg' => $io('folder')],
        ['id' => 'wallet', 'label' => $say('Wallet', 'کیف پول'), 'g' => 'linear-gradient(180deg,#605df0,#34319e)', 'svg' => $dWallet],
    ];
    $dockData = [
        ['id' => 'dk-messages', 'label' => $say('Messages', 'پیام‌ها'), 'g' => 'linear-gradient(180deg,#71e574,#1fb434)', 'svg' => $io('message')],
        ['id' => 'dk-music', 'label' => $say('Music', 'موسیقی'), 'g' => 'linear-gradient(180deg,#fb5c74,#f3213c)', 'svg' => $io('music')],
        ['id' => 'dk-maps', 'label' => $say('Maps', 'نقشه'), 'g' => 'linear-gradient(160deg,#79d787,#3ec1e0)', 'svg' => $dPin],
        ['id' => 'dk-camera', 'label' => $say('Camera', 'دوربین'), 'g' => 'linear-gradient(180deg,#8a8c93,#4a4b52)', 'svg' => $dCam],
    ];
    $json = fn (array $a) => json_encode($a, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

    $calDay = $fn(now()->format('j'));
    $calDow = now()->locale($fa ? 'fa' : 'en')->translatedFormat('D');

    $hours = [
        ['t' => $say('Now', 'اکنون'), 'd' => $fn(18) . '°', 'g' => $dCloudSun],
        ['t' => $fn(15), 'd' => $fn(19) . '°', 'g' => $dCloudSun],
        ['t' => $fn(16), 'd' => $fn(19) . '°', 'g' => $dCloud],
        ['t' => $fn(17), 'd' => $fn(18) . '°', 'g' => $dCloud],
        ['t' => $fn(18), 'd' => $fn(17) . '°', 'g' => $dRain],
        ['t' => $fn(19), 'd' => $fn(16) . '°', 'g' => $dRain],
        ['t' => $fn(20), 'd' => $fn(15) . '°', 'g' => $dMoon],
        ['t' => $fn(21), 'd' => $fn(14) . '°', 'g' => $dMoon],
    ];
    $days = [
        ['n' => $say('Today', 'امروز'), 'g' => $dCloudSun, 'lo' => $fn(12) . '°', 'hi' => $fn(24) . '°', 'l' => '30%', 'w' => '52%'],
        ['n' => $say('Tomorrow', 'فردا'), 'g' => $dCloud, 'lo' => $fn(13) . '°', 'hi' => $fn(22) . '°', 'l' => '38%', 'w' => '42%'],
        ['n' => $say('Friday', 'جمعه'), 'g' => $dRain, 'lo' => $fn(11) . '°', 'hi' => $fn(19) . '°', 'l' => '22%', 'w' => '38%'],
        ['n' => $say('Saturday', 'شنبه'), 'g' => $dCloudSun, 'lo' => $fn(10) . '°', 'hi' => $fn(21) . '°', 'l' => '18%', 'w' => '50%'],
    ];
@endphp

<style>
    .iohome-root {
        --ioh-blue: #007AFF; --ioh-green: #34C759; --ioh-red: #FF3B30; --ioh-orange: #FF9500;
        --ioh-fill: rgba(120,120,128,.2); --ioh-fill2: rgba(120,120,128,.36);
        --ioh-fg: #0b0b10; --ioh-muted: #6d6d72; --ioh-card: #ffffff; --ioh-appbg: #f2f2f7;
        --ioh-sep: rgba(60,60,67,.16);
        --ioh-glass: rgba(248,248,252,.72); --ioh-rim: rgba(255,255,255,.75);
        --ioh-status: #ffffff; --ioh-label: #ffffff; --ioh-bar: #f5f5f7; --ioh-focus: #ffffff;
        --ioh-cc: rgba(30,32,42,.52);
        --ioh-spring: cubic-bezier(.32,1.25,.36,1);
        position: relative; isolation: isolate; overflow: clip;
        inline-size: min(100%, 21rem); aspect-ratio: 9 / 19; border-radius: 2.75rem;
        background: #0d1122; font-family: inherit; user-select: none; -webkit-tap-highlight-color: transparent;
        box-shadow: 0 34px 80px -34px rgba(6,10,32,.6);
    }
    .iohome-root:focus { outline: none; }
    .iohome-root :where(button, input, [tabindex]):focus-visible { outline: 2px solid var(--ioh-focus); outline-offset: 2px; border-radius: 10px; }
    [x-cloak] { display: none !important; }

    .iohome-root::after { content: ''; position: absolute; inset: 0; z-index: 60; border-radius: inherit; pointer-events: none;
        box-shadow: inset 0 0 0 5px #0a0a0f, inset 0 0 0 6.5px rgba(210,215,235,.28), inset 0 0 18px rgba(0,0,0,.42); }

    .iohome-wall { position: absolute; inset: 0; z-index: 0;
        background: radial-gradient(130% 80% at 84% -10%, rgba(126,166,255,.52), transparent 62%),
                    radial-gradient(120% 90% at 10% 110%, rgba(128,82,255,.46), transparent 62%),
                    radial-gradient(70% 46% at 50% 44%, rgba(64,84,150,.35), transparent 72%),
                    linear-gradient(168deg, #2c3c6c 0%, #1b2444 46%, #0c1020 100%); }
    .iohome-flash { position: absolute; inset: 0; z-index: 1; pointer-events: none; opacity: 0; transition: opacity .3s;
        background: radial-gradient(95% 62% at 50% -12%, rgba(255,255,248,.8), rgba(255,255,248,0) 62%); }
    .iohome-root[data-torch] .iohome-flash { opacity: 1; }
    .iohome-dim { position: absolute; inset: 0; z-index: 50; pointer-events: none; background: #04060d;
        opacity: var(--ioh-dims, 0); transition: opacity .25s; }

    /* ---- status bar + island ---- */
    .iohome-status { position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 40; block-size: 46px;
        display: flex; align-items: center; justify-content: space-between; padding-inline: 26px 20px;
        color: var(--ioh-status); pointer-events: none; transition: color .35s; }
    .iohome-root[data-app] .iohome-status { color: var(--ioh-fg); }
    .iohome-time { font-size: 14.5px; font-weight: 650; letter-spacing: .02em; }
    .iohome-island { position: absolute; inset-block-start: 11px; left: 50%; translate: -50% 0; z-index: 41;
        inline-size: 84px; block-size: 24px; border-radius: 999px; background: #050508; }
    .iohome-island i { position: absolute; inset-inline-end: 7px; inset-block-start: 50%; translate: 0 -50%;
        inline-size: 9px; aspect-ratio: 1; border-radius: 50%;
        background: radial-gradient(circle at 35% 35%, #2c3a5c, #0a0d16 72%); }
    .iohome-sigs { display: inline-flex; align-items: center; gap: 5.5px; }
    .iohome-sigs svg { inline-size: 15px; block-size: 15px; display: block; }
    .iohome-bars { display: inline-flex; align-items: flex-end; gap: 1.6px; }
    .iohome-bars i { inline-size: 3px; border-radius: 1px; background: currentColor; }
    .iohome-bars i:nth-child(1) { block-size: 4.5px; } .iohome-bars i:nth-child(2) { block-size: 6.5px; }
    .iohome-bars i:nth-child(3) { block-size: 8.5px; } .iohome-bars i:nth-child(4) { block-size: 10.5px; }
    .iohome-cell[data-nocell] .iohome-bars { opacity: .35; }
    .iohome-cell:not([data-air]) .iohome-plane { display: none; }
    .iohome-cell[data-air] .iohome-bars { display: none; }
    .iohome-plane { color: var(--ioh-orange); }
    .iohome-batt { position: relative; inline-size: 25px; block-size: 12.5px; border-radius: 4px;
        border: 1px solid currentColor; padding: 1.6px; opacity: .92; }
    .iohome-batt i { display: block; block-size: 100%; inline-size: 74%; border-radius: 2px; background: currentColor; }
    .iohome-batt::after { content: ''; position: absolute; inset-inline-end: -4px; inset-block-start: 50%; translate: 0 -50%;
        inline-size: 2px; block-size: 4.5px; border-radius: 1px; background: currentColor; opacity: .5; }

    /* ---- home layer: pages + dots + dock ---- */
    .iohome-under { position: absolute; inset: 0; z-index: 2; display: flex; flex-direction: column;
        padding: 52px 13px 24px; gap: 9px; }
    .iohome-home { position: relative; flex: 1; overflow: hidden; touch-action: pan-y; min-block-size: 0; }
    .iohome-track { display: flex; block-size: 100%; will-change: translate;
        transition: translate .45s cubic-bezier(.3,.9,.28,1); }
    .iohome-page { flex: 0 0 100%; block-size: 100%; min-inline-size: 0; }
    .iohome-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 18px 6px;
        align-content: start; padding-block-start: 17px; }
    .iohome-cell { position: relative; display: grid; justify-items: center; gap: 5px; min-inline-size: 0;
        transition: scale .22s, opacity .22s; }
    .iohome-cell[data-out] { scale: .25; opacity: 0; }
    .iohome-app { display: grid; justify-items: center; gap: 5px; background: none; border: none; padding: 0;
        margin: 0; color: var(--ioh-label); cursor: pointer; min-inline-size: 0; font: inherit; }
    .iohome-tile { position: relative; inline-size: min(58px, 100%); aspect-ratio: 1; border-radius: 22.5%;
        display: grid; place-items: center; color: #fff; overflow: hidden;
        box-shadow: 0 7px 16px -7px rgba(4,8,22,.55), inset 0 0 0 .5px rgba(255,255,255,.22);
        transition: scale .18s cubic-bezier(.3,.8,.4,1.25), filter .18s; }
    .iohome-app:active .iohome-tile { scale: .9; filter: brightness(1.1); }
    .iohome-tile i { display: grid; place-items: center; inline-size: 100%; block-size: 100%; }
    .iohome-tile svg { inline-size: 55%; block-size: 55%; }
    .iohome-lab { font-size: 11px; font-weight: 500; line-height: 1.25; max-inline-size: 100%;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-shadow: 0 1px 3px rgba(0,0,0,.4); }
    .iohome-tile[data-tile='cal'] { background: var(--ioh-card); color: var(--ioh-fg); }
    .iohome-cal { display: grid; justify-items: center; line-height: 1.05; }
    .iohome-cal small { font-size: 8.5px; font-weight: 700; text-transform: uppercase; color: var(--ioh-red); }
    .iohome-cal b { font-size: 24px; font-weight: 650; }
    .iohome-tile[data-tile='photos'] { background: radial-gradient(circle at 50% 50%, var(--ioh-card) 0 29%, transparent 31%),
        conic-gradient(#ff453a, #ff9f0a, #ffd60a, #30d158, #64d2ff, #0a84ff, #bf5af2, #ff375f, #ff453a); }

    .iohome-dots { display: flex; justify-content: center; gap: 7px; padding-block: 3px; }
    .iohome-dot { inline-size: 7px; block-size: 7px; padding: 0; border-radius: 999px; border: none; cursor: pointer;
        background: rgba(255,255,255,.42); transition: inline-size .3s var(--ioh-spring), background .3s; }
    .iohome-dot.iohome-on { inline-size: 19px; background: #fff; }

    .iohome-dock { display: flex; gap: 7px; padding: 8px; border-radius: 27px;
        background: var(--ioh-glass); backdrop-filter: blur(24px) saturate(1.7); -webkit-backdrop-filter: blur(24px) saturate(1.7);
        box-shadow: inset 0 0 0 .5px var(--ioh-rim), 0 10px 28px -10px rgba(3,6,18,.55); }
    .iohome-dock .iohome-app { flex: 1; }
    .iohome-dock .iohome-tile { inline-size: min(54px, 100%); }

    /* ---- jiggle edit mode ---- */
    @keyframes iohome-jig { from { rotate: -1.2deg; } to { rotate: 1.2deg; } }
    .iohome-root[data-jiggle] .iohome-app { animation: iohome-jig .3s ease-in-out infinite alternate;
        animation-delay: calc(var(--i, 0) * -.075s); }
    .iohome-x { position: absolute; inset-block-start: -5px; inset-inline-start: -5px; z-index: 3;
        inline-size: 19px; aspect-ratio: 1; border-radius: 50%; border: none; padding: 0; cursor: pointer;
        display: none; place-items: center; background: #d7d8dd; color: #3c3d42;
        box-shadow: 0 1px 4px rgba(0,0,0,.35), inset 0 0 0 .5px rgba(0,0,0,.08); }
    .iohome-x svg { inline-size: 9px; block-size: 9px; }
    .iohome-root[data-jiggle] .iohome-x { display: grid; }
    .iohome-done { position: absolute; inset-inline-end: 16px; inset-block-start: 56px; z-index: 5;
        display: none; border: 1px solid rgba(255,255,255,.22); cursor: pointer; font: inherit;
        border-radius: 999px; padding: 4.5px 14px; font-size: 13px; font-weight: 600; color: #fff;
        background: rgba(28,30,40,.55); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    .iohome-root[data-jiggle] .iohome-done { display: inline-block; }

    /* ---- app views ---- */
    .iohome-appview { position: absolute; inset: 0; z-index: 6; visibility: hidden; opacity: 0; scale: .22;
        background: var(--ioh-appbg); color: var(--ioh-fg);
        display: grid; grid-template-rows: minmax(0, 1fr); padding: 52px 11px 26px;
        transition: scale .34s cubic-bezier(.4,.7,.4,1), opacity .26s ease, visibility 0s linear .34s; }
    .iohome-root[data-app] .iohome-appview { visibility: visible; opacity: 1; scale: 1;
        transition: scale .34s var(--ioh-spring), opacity .2s ease, visibility 0s; }
    .iohome-notrans { transition: none !important; }

    .iohome-app { display: grid; min-block-size: 0; }
    .iohome-apphead { padding: 0 7px 8px; }
    .iohome-apphead b { font-size: 27px; font-weight: 800; letter-spacing: -.01em; }
    .iohome-appscroll { overflow-y: auto; min-block-size: 0; display: grid; gap: 7px; align-content: start;
        padding-block-end: 12px; scrollbar-width: none; }
    .iohome-appscroll::-webkit-scrollbar { display: none; }
    .iohome-ghead { font-size: 12px; font-weight: 600; color: var(--ioh-muted); padding-inline: 15px; margin-block-start: 5px; }
    .iohome-gfoot { font-size: 11.5px; color: var(--ioh-muted); line-height: 1.65; padding-inline: 15px; }
    .iohome-card { background: var(--ioh-card); border-radius: 12px; overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,.05); }
    .iohome-row { display: flex; align-items: center; gap: 11px; min-block-size: 45px; padding: 7px 13px; }
    .iohome-row + .iohome-row { border-block-start: .5px solid var(--ioh-sep); }
    .iohome-rico { flex: none; inline-size: 29px; aspect-ratio: 1; border-radius: 7px; display: grid;
        place-items: center; color: #fff; }
    .iohome-rico svg { inline-size: 17px; block-size: 17px; }
    .iohome-rlab { font-size: 14.5px; }
    .iohome-rval { margin-inline-start: auto; font-size: 13px; color: var(--ioh-muted); }
    .iohome-chev { margin-inline-start: auto; color: rgba(140,140,148,.85); display: grid; }
    .iohome-chev svg { inline-size: 15px; block-size: 15px; }
    [dir='rtl'] .iohome-chev svg { scale: -1 1; }
    .iohome-grow { margin-inline-start: auto; }

    .iohome-sw { position: relative; flex: none; margin-inline-start: auto; inline-size: 44px; block-size: 27px;
        border-radius: 999px; border: none; padding: 0; cursor: pointer; background: var(--ioh-fill2); transition: background .25s; }
    .iohome-sw i { position: absolute; inset-block-start: 2px; inset-inline-start: 2px; inline-size: 23px; aspect-ratio: 1;
        border-radius: 50%; background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,.28), 0 0 .5px rgba(0,0,0,.15);
        transition: translate .25s cubic-bezier(.3,.9,.4,1.12); }
    .iohome-sw[aria-pressed='true'] { background: var(--ioh-green); }
    .iohome-sw[aria-pressed='true'] i { translate: 17px 0; }
    [dir='rtl'] .iohome-sw[aria-pressed='true'] i { translate: -17px 0; }

    .iohome-range { -webkit-appearance: none; appearance: none; inline-size: 100%; block-size: 28px;
        background: transparent; cursor: pointer; }
    .iohome-range::-webkit-slider-runnable-track { block-size: 4px; border-radius: 999px; background: var(--ioh-fill2); }
    .iohome-range::-webkit-slider-thumb { -webkit-appearance: none; inline-size: 22px; block-size: 22px;
        border-radius: 50%; background: #fff; box-shadow: 0 1px 5px rgba(0,0,0,.32); margin-block-start: -9px; }
    .iohome-range::-moz-range-track { block-size: 4px; border-radius: 999px; background: var(--ioh-fill2); }
    .iohome-range::-moz-range-thumb { inline-size: 22px; block-size: 22px; border: none; border-radius: 50%;
        background: #fff; box-shadow: 0 1px 5px rgba(0,0,0,.32); }

    .iohome-generic { place-content: center; justify-items: center; gap: 13px; text-align: center; padding-inline: 22px; }
    .iohome-bigtile { inline-size: 86px; aspect-ratio: 1; border-radius: 22.5%; display: grid; place-items: center;
        color: #fff; box-shadow: 0 12px 28px -10px rgba(4,8,22,.5), inset 0 0 0 .5px rgba(255,255,255,.22); }
    .iohome-bigtile > span { display: grid; place-items: center; inline-size: 100%; block-size: 100%; }
    .iohome-bigtile svg { inline-size: 46%; block-size: 46%; }
    .iohome-bigtile[data-tile='cal'] { background: var(--ioh-card); color: var(--ioh-fg); }
    .iohome-bigtile[data-tile='cal'] .iohome-cal b { font-size: 38px; }
    .iohome-bigtile[data-tile='cal'] .iohome-cal small { font-size: 12px; }
    .iohome-bigtile[data-tile='photos'] { background: radial-gradient(circle at 50% 50%, var(--ioh-card) 0 29%, transparent 31%),
        conic-gradient(#ff453a, #ff9f0a, #ffd60a, #30d158, #64d2ff, #0a84ff, #bf5af2, #ff375f, #ff453a); }
    .iohome-generic b { font-size: 21px; font-weight: 700; }
    .iohome-generic p { margin: 0; font-size: 13px; line-height: 1.7; color: var(--ioh-muted); }
    .iohome-backbtn { border: none; cursor: pointer; font: inherit; font-size: 14px; font-weight: 600;
        border-radius: 999px; padding: 8px 22px; background: var(--ioh-fill); color: var(--ioh-blue); }

    .iohome-weather { overflow-y: auto; scrollbar-width: none; border-radius: 22px; color: #fff; padding: 16px 13px;
        background: linear-gradient(180deg, #3e7fcc 0%, #26497f 58%, #152a4d 100%);
        display: grid; gap: 11px; align-content: start; }
    .iohome-weather::-webkit-scrollbar { display: none; }
    .iohome-wxhead { display: grid; justify-items: center; gap: 2px; padding-block: 4px; }
    .iohome-wxhead b { font-size: 26px; font-weight: 600; text-shadow: 0 1px 6px rgba(0,0,0,.25); }
    .iohome-wxtemp { font-size: 62px; font-weight: 250; line-height: 1.02; }
    .iohome-wxcond { font-size: 15px; opacity: .92; }
    .iohome-wxhl { font-size: 13px; opacity: .85; }
    .iohome-wxcard { border-radius: 16px; padding: 11px 12px; background: rgba(255,255,255,.15);
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        box-shadow: inset 0 0 0 .5px rgba(255,255,255,.18); }
    .iohome-wxlabel { font-size: 11px; opacity: .78; display: flex; gap: 5px; align-items: center;
        margin-block-end: 9px; }
    .iohome-hours { display: flex; gap: 4px; overflow-x: auto; scrollbar-width: none; touch-action: pan-x; }
    .iohome-hours::-webkit-scrollbar { display: none; }
    .iohome-hour { flex: none; inline-size: 47px; display: grid; justify-items: center; gap: 5px;
        font-size: 11.5px; font-weight: 600; }
    .iohome-hour svg { inline-size: 17px; block-size: 17px; }
    .iohome-hour b { font-size: 13px; font-weight: 650; }
    .iohome-days { display: grid; }
    .iohome-day { display: grid; grid-template-columns: 3.6rem 20px 2.1rem 1fr 2.1rem; align-items: center;
        gap: 7px; font-size: 13.5px; min-block-size: 33px; }
    .iohome-day + .iohome-day { border-block-start: .5px solid rgba(255,255,255,.22); }
    .iohome-day svg { inline-size: 17px; block-size: 17px; }
    .iohome-daytrack { position: relative; block-size: 4px; border-radius: 999px; background: rgba(8,12,26,.35); }
    .iohome-daytrack i { position: absolute; inset-block: 0; inset-inline-start: var(--l); inline-size: var(--w);
        border-radius: inherit; background: linear-gradient(90deg, #6fd6ff, #ffd60a); }
    .iohome-daylo { opacity: .72; text-align: end; }
    .iohome-dayhi { font-weight: 650; }

    /* ---- control centre ---- */
    .iohome-pullzone { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 60px; z-index: 25; }
    .iohome-handle { position: absolute; inset-block-start: 47px; left: 50%; translate: -50% 0;
        inline-size: 34px; block-size: 17px; border-radius: 999px; border: none; cursor: pointer; padding: 0;
        display: grid; place-items: center; color: #fff; background: rgba(120,120,128,.3);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); opacity: .75; }
    .iohome-handle svg { inline-size: 11px; block-size: 11px; }
    .iohome-scrim { position: absolute; inset: 0; z-index: 29; background: rgba(5,8,16,.24);
        opacity: 0; pointer-events: none; transition: opacity .3s; border: none; padding: 0; }
    .iohome-root[data-cc] .iohome-scrim { opacity: 1; pointer-events: auto; }

    .iohome-cc { position: absolute; inset-inline: 8px; inset-block-start: 8px; z-index: 30; border-radius: 30px;
        padding: 12px; background: var(--ioh-cc); color: #fff;
        backdrop-filter: blur(30px) saturate(1.6); -webkit-backdrop-filter: blur(30px) saturate(1.6);
        box-shadow: inset 0 0 0 .5px rgba(255,255,255,.24), 0 26px 60px -22px rgba(0,0,0,.65);
        translate: 0 -112%; opacity: 0; transition: translate .42s var(--ioh-spring), opacity .3s; }
    .iohome-root[data-cc] .iohome-cc { translate: 0 0; opacity: 1; }
    .iohome-ccgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .iohome-mod { border-radius: 21px; padding: 11px; background: rgba(120,120,128,.3);
        box-shadow: inset 0 0 0 .5px rgba(255,255,255,.1); min-inline-size: 0; }
    .iohome-conn { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 9px; justify-items: center; }
    .iohome-ctog { inline-size: 41px; aspect-ratio: 1; border-radius: 50%; border: none; padding: 0; cursor: pointer;
        display: grid; place-items: center; color: rgba(255,255,255,.9);
        background: rgba(255,255,255,.13); transition: background .25s, color .25s, scale .18s; }
    .iohome-ctog:active { scale: .92; }
    .iohome-ctog svg { inline-size: 19px; block-size: 19px; }
    .iohome-ctog[aria-pressed='true'] { background: var(--ioh-blue); color: #fff;
        box-shadow: 0 3px 10px -2px rgba(10,132,255,.55); }
    .iohome-ctog[data-tone='orange'][aria-pressed='true'] { background: var(--ioh-orange);
        box-shadow: 0 3px 10px -2px rgba(255,149,0,.55); }
    .iohome-ctog[data-tone='green'][aria-pressed='true'] { background: var(--ioh-green);
        box-shadow: 0 3px 10px -2px rgba(52,199,89,.5); }
    .iohome-np { display: grid; gap: 9px; align-content: space-between; }
    .iohome-nprow { display: flex; align-items: center; gap: 8px; min-inline-size: 0; }
    .iohome-art { flex: none; inline-size: 31px; aspect-ratio: 1; border-radius: 8px; display: grid; place-items: center;
        color: #fff; box-shadow: inset 0 0 0 .5px rgba(255,255,255,.25); }
    .iohome-art svg { inline-size: 15px; block-size: 15px; }
    .iohome-npstack { min-inline-size: 0; display: grid; line-height: 1.3; }
    .iohome-npstack b { font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .iohome-npstack small { font-size: 9.5px; opacity: .7; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .iohome-play { flex: none; margin-inline-start: auto; inline-size: 26px; aspect-ratio: 1; border-radius: 50%;
        border: none; padding: 0; cursor: pointer; display: grid; place-items: center; color: #fff;
        background: rgba(255,255,255,.16); }
    .iohome-play i { display: grid; place-items: center; }
    .iohome-play svg { inline-size: 13px; block-size: 13px; }
    @keyframes iohome-np { from { inline-size: 38%; } to { inline-size: 100%; } }
    .iohome-npbar { block-size: 4px; border-radius: 999px; background: rgba(255,255,255,.26); overflow: hidden; }
    .iohome-npbar i { display: block; block-size: 100%; inline-size: 38%; border-radius: inherit; background: #fff;
        animation: iohome-np 240s linear infinite; animation-play-state: paused; }
    .iohome-root[data-playing] .iohome-npbar i { animation-play-state: running; }
    .iohome-vs { display: grid; justify-items: center; gap: 8px; }
    .iohome-vpill { position: relative; inline-size: min(100%, 86px); block-size: 128px; border-radius: 999px;
        overflow: hidden; background: rgba(255,255,255,.15); box-shadow: inset 0 0 0 .5px rgba(255,255,255,.14);
        cursor: ns-resize; touch-action: none; }
    .iohome-vpill i { position: absolute; inset-inline: 0; inset-block-end: 0; block-size: var(--f, 0%);
        background: #fff; border-radius: 999px; transition: block-size .12s linear; }
    .iohome-vico { position: absolute; inset-block-start: 12px; left: 50%; translate: -50% 0;
        mix-blend-mode: exclusion; color: #fff; display: grid; }
    .iohome-vico svg { inline-size: 18px; block-size: 18px; }
    .iohome-ccrow { display: flex; gap: 10px; justify-content: center; margin-block-start: 10px; }
    .iohome-rbtn { inline-size: 47px; aspect-ratio: 1; border-radius: 50%; border: none; padding: 0; cursor: pointer;
        display: grid; place-items: center; color: #fff; background: rgba(120,120,128,.34);
        box-shadow: inset 0 0 0 .5px rgba(255,255,255,.12); transition: background .25s, color .25s, scale .18s; }
    .iohome-rbtn:active { scale: .92; }
    .iohome-rbtn svg { inline-size: 20px; block-size: 20px; }
    .iohome-rbtn[aria-pressed='true'] { background: #fff; color: #101014; }

    /* ---- home bar ---- */
    .iohome-barzone { position: absolute; inset-inline: 0; inset-block-end: 0; block-size: 26px; z-index: 45;
        display: grid; place-items: center; touch-action: none; cursor: grab; }
    .iohome-homebar { inline-size: 128px; block-size: 5px; border-radius: 999px; background: var(--ioh-bar);
        box-shadow: 0 .5px 3px rgba(0,0,0,.3); transition: background .35s; }

    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.iohome-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.iohome-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .iohome-track, .iohome-appview, .iohome-cc, .iohome-dim, .iohome-tile, .iohome-sw i,
        .iohome-cell, .iohome-dot, .iohome-vpill i { transition: none !important; }
        .iohome-root[data-jiggle] .iohome-app { animation: none !important; }
        .iohome-npbar i { animation: none !important; inline-size: 38%; }
    }

    html[data-theme='dark'] .iohome-root {
        --ioh-blue: #0A84FF; --ioh-green: #30D158; --ioh-red: #FF453A;
        --ioh-fill: rgba(120,120,128,.24); --ioh-fill2: rgba(120,120,128,.4);
        --ioh-fg: #f5f5f7; --ioh-muted: #98989e; --ioh-card: #1c1c1f; --ioh-appbg: #000000;
        --ioh-sep: rgba(84,84,88,.55);
        --ioh-glass: rgba(38,40,48,.58); --ioh-rim: rgba(255,255,255,.16);
        --ioh-status: #f5f5f7; --ioh-bar: #e9e9ee; --ioh-focus: #0A84FF;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme='light']) .iohome-root {
            --ioh-blue: #0A84FF; --ioh-green: #30D158; --ioh-red: #FF453A;
            --ioh-fill: rgba(120,120,128,.24); --ioh-fill2: rgba(120,120,128,.4);
            --ioh-fg: #f5f5f7; --ioh-muted: #98989e; --ioh-card: #1c1c1f; --ioh-appbg: #000000;
            --ioh-sep: rgba(84,84,88,.55);
            --ioh-glass: rgba(38,40,48,.58); --ioh-rim: rgba(255,255,255,.16);
            --ioh-status: #f5f5f7; --ioh-bar: #e9e9ee; --ioh-focus: #0A84FF;
        }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The whole shell, one phone', 'تمام پوسته، یک گوشی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Swipe between pages, tap an app to zoom it open, long-press to jiggle, drag the home bar to go back — and pull the top edge for a Control Centre that really dims the wallpaper.', 'بین صفحه‌ها بکشید، برای باز شدنِ زوم‌شده روی اپ بزنید، با لمسِ طولانی حالت لرزش را ببینید، نوار خانه را برای برگشتن بکشید — و از لبهٔ بالا کنترل سنتری را بیاورید که واقعاً کاغذدیواری را تاریک می‌کند.') }}
        </p>
    </div>

    <div style="display: grid; justify-items: center; padding-block: .75rem">
        <section
            class="iohome-root"
            tabindex="-1"
            role="group"
            aria-label="{{ $say('Interactive iPhone demo', 'دموی تعاملی آیفون') }}"
            x-data="{
                apps: {{ $json($appsData) }},
                dock: {{ $json($dockData) }},
                page: 0, per: 7, dir: -1, w: 0,
                app: null, ox: 50, oy: 88,
                jiggle: false, removing: null, lp: null, lpFired: false, moved: false,
                hOn: false, hX0: 0, hY0: 0, dxp: 0, v: 0, tPrev: 0, drag: { on: false, px: 0 },
                cc: false, pOn: false, pCap: false, pY0: 0, ccY: 0,
                cOn: false, cY0: 0, cY: 0,
                dims: 0, vol: .65, torch: false,
                airplane: false, cell: true, wifi: true, bt: true,
                playing: false, bOn: false, bY0: 0, bY: 0,
                pageNames: {{ json_encode([$say('Page 1', 'صفحهٔ ۱'), $say('Page 2', 'صفحهٔ ۲'), $say('Page 3', 'صفحهٔ ۳')], JSON_UNESCAPED_UNICODE) }},
                get pagedApps() { const vis = this.apps.filter(a => !a.gone); const out = []; for (let i = 0; i < vis.length; i += this.per) out.push(vis.slice(i, i + this.per)); return out; },
                get pageCount() { return Math.max(1, this.pagedApps.length); },
                get cur() { return Math.min(this.page, this.pageCount - 1); },
                get allApps() { return this.apps.concat(this.dock); },
                init() { this.$nextTick(() => { const p = this.$refs.home.querySelectorAll('.iohome-page'); if (p.length > 1 && p[1].getBoundingClientRect().left < p[0].getBoundingClientRect().left) this.dir = 1; }); },
                trackStyle() { const base = this.dir * this.cur * 100; return this.drag.on ? 'translate: calc(' + base + '% + ' + this.drag.px + 'px) 0px' : 'translate: ' + base + '% 0px'; },
                hDown(e) { this.hOn = true; this.hX0 = e.clientX; this.hY0 = e.clientY; this.dxp = 0; this.v = 0; this.tPrev = performance.now(); clearTimeout(this.lp); this.lp = setTimeout(() => this.enterJiggle(), 500); },
                hMove(e) { if (!this.hOn) return; const dx = e.clientX - this.hX0, dy = e.clientY - this.hY0;
                    if (!this.drag.on) { if (Math.abs(dx) > 8 && Math.abs(dx) > Math.abs(dy)) { this.drag.on = true; this.w = this.$refs.home.getBoundingClientRect().width || 1; this.moved = true; try { e.currentTarget.setPointerCapture(e.pointerId); } catch (err) {} clearTimeout(this.lp); } else if (Math.abs(dy) > 12) { this.hOn = false; clearTimeout(this.lp); } return; }
                    const t = performance.now(); this.v = (dx - this.dxp) / Math.max(1, t - this.tPrev); this.tPrev = t; this.dxp = dx;
                    let pos = this.cur + this.dir * dx / this.w; const last = this.pageCount - 1;
                    if (pos < 0) pos *= .32; else if (pos > last) pos = last + (pos - last) * .32;
                    this.drag.px = this.dir * (pos - this.cur) * this.w; },
                hEnd() { this.hOn = false; clearTimeout(this.lp);
                    if (!this.drag.on) return; const proj = this.dxp + this.v * 90;
                    let next = this.cur + Math.round(this.dir * proj / this.w);
                    this.page = Math.max(0, Math.min(this.pageCount - 1, next));
                    this.drag.on = false; this.drag.px = 0;
                    setTimeout(() => { this.moved = false; }, 80); },
                homeClick() { if (this.lpFired) { this.lpFired = false; return; } this.moved = false; if (this.jiggle) this.exitJiggle(); },
                open(a, ev) { if (this.jiggle || this.removing) { return; } if (this.moved) { this.moved = false; return; }
                    const fr = this.$el.getBoundingClientRect(); this.ox = 50; this.oy = 86;
                    if (ev && ev.currentTarget) { const r = ev.currentTarget.getBoundingClientRect();
                        this.ox = Math.round(Math.max(4, Math.min(96, (r.left + r.width / 2 - fr.left) / fr.width * 100)));
                        this.oy = Math.round(Math.max(4, Math.min(96, (r.top + r.height / 2 - fr.top) / fr.height * 100))); }
                    this.app = a; this.$nextTick(() => { if (this.$refs.appview) this.$refs.appview.focus({ preventScroll: true }); }); },
                closeApp() { if (!this.app) return; this.app = null; this.bOn = false; this.bY = 0;
                    this.$nextTick(() => this.$el.focus({ preventScroll: true })); },
                enterJiggle() { if (this.app || this.jiggle) return; this.jiggle = true; this.lpFired = true; },
                exitJiggle() { this.jiggle = false; },
                removeApp(id) { if (this.removing) return;
                    const gridCount = this.apps.filter(a => !a.gone).length;
                    if (this.apps.some(a => a.id === id) && gridCount <= 6) return;
                    this.removing = id;
                    setTimeout(() => { const a = this.allApps.find(x => x.id === id); if (a) a.gone = true; this.removing = null; }, 230); },
                pDown(e) { if (this.cc) return; this.pOn = true; this.pCap = false; this.pY0 = e.clientY; this.ccY = 0; },
                pMove(e) { if (!this.pOn) return; const dy = e.clientY - this.pY0;
                    if (!this.pCap && dy > 3) { this.pCap = true; try { e.currentTarget.setPointerCapture(e.pointerId); } catch (err) {} }
                    if (this.pCap) this.ccY = Math.max(0, dy); },
                pEnd() { if (!this.pOn) return; this.pOn = false; if (this.ccY > 84) { this.cc = true; } this.ccY = 0; this.pCap = false; },
                cDown(e) { if (e.target.closest('.iohome-vpill, button, input')) return; this.cOn = true; this.cY0 = e.clientY;
                    try { e.currentTarget.setPointerCapture(e.pointerId); } catch (err) {} },
                cMove(e) { if (this.cOn) this.cY = Math.min(0, e.clientY - this.cY0); },
                cEnd() { if (!this.cOn) return; this.cOn = false; if (this.cY < -58) { this.cc = false; } this.cY = 0; },
                ccStyle() { if (this.pOn && this.pCap) return 'translate: 0px calc(-112% + ' + this.ccY + 'px); opacity:' + Math.min(1, this.ccY / 150);
                    if (this.cOn && this.cY < 0) return 'translate: 0px ' + this.cY + 'px'; return ''; },
                sDown(e, k) { this.slOn = k; this.slR = e.currentTarget.getBoundingClientRect();
                    try { e.currentTarget.setPointerCapture(e.pointerId); } catch (err) {} this.sApply(e, k); },
                sMove(e, k) { if (this.slOn === k) this.sApply(e, k); },
                sEnd() { this.slOn = null; },
                sApply(e, k) { const r = this.slR; if (!r) return;
                    const f = Math.max(0, Math.min(1, (r.bottom - e.clientY) / r.height));
                    if (k === 'dims') this.dims = Math.round(f * 55) / 100; else this.vol = Math.round(f * 100) / 100; },
                fill(k) { const f = k === 'dims' ? this.dims / 0.55 : this.vol; return Math.round(Math.max(0, Math.min(1, f)) * 100) + '%'; },
                bDown(e) { if (!this.app) return; this.bOn = true; this.bY0 = e.clientY;
                    try { e.currentTarget.setPointerCapture(e.pointerId); } catch (err) {} },
                bMove(e) { if (this.bOn) this.bY = Math.min(0, e.clientY - this.bY0); },
                bEnd(e) { if (!this.bOn) return; const t = Math.abs(e.clientY - this.bY0) < 7; this.bOn = false;
                    if (this.bY < -34 || t) this.closeApp(); this.bY = 0; },
                openCalc() { this.cc = false; const c = this.allApps.find(a => a.id === 'calc' && !a.gone); if (c) this.open(c, null); },
                tileStyle(a) { return a.tile ? '' : 'background:' + a.g; },
                onEsc() { const el = document.activeElement; if (!el || (!this.$el.contains(el) && !this.app && !this.jiggle && !this.cc)) return;
                    if (this.cc) this.cc = false; else if (this.app) this.closeApp(); else if (this.jiggle) this.exitJiggle(); },
            }"
            :style="'--ioh-dims: ' + dims"
            :data-app="app ? '' : null"
            :data-jiggle="jiggle ? '' : null"
            :data-cc="cc ? '' : null"
            :data-torch="torch ? '' : null"
            :data-playing="playing ? '' : null"
            x-on:keydown.escape.window="onEsc()"
            x-effect="if (this.page > this.pageCount - 1) this.page = this.pageCount - 1"
        >
            <div class="iohome-wall" aria-hidden="true"></div>
            <div class="iohome-flash" aria-hidden="true"></div>

            {{-- status bar --}}
            <div class="iohome-status" role="img" aria-label="{{ $say('9:41, full signal, Wi-Fi on, 74% battery', '۹:۴۱، آنتن کامل، وای‌فای روشن، ۷۴٪ باتری') }}">
                <b class="iohome-time">{{ $say('9:41', '۹:۴۱') }}</b>
                <span class="iohome-island" aria-hidden="true"><i></i></span>
                <span class="iohome-sigs" aria-hidden="true">
                    <span class="iohome-cell" :data-air="airplane ? '' : null" :data-nocell="cell ? null : ''">
                        <span class="iohome-bars"><i></i><i></i><i></i><i></i></span>
                        <span class="iohome-plane">{!! $dPlane !!}</span>
                    </span>
                    <span :style="wifi ? '' : 'opacity:.35'">{!! $dWifi !!}</span>
                    <span class="iohome-batt"><i></i></span>
                </span>
            </div>

            {{-- home layer: pages, dots, dock --}}
            <div class="iohome-under"
                x-on:pointerdown="clearTimeout(lp); lp = setTimeout(() => enterJiggle(), 500)"
                x-on:pointerup="clearTimeout(lp)"
                x-on:pointercancel="clearTimeout(lp)"
                x-on:click="homeClick()">
                <button type="button" class="iohome-done" x-on:click="exitJiggle()">{{ $say('Done', 'تمام') }}</button>

                <div class="iohome-home" x-ref="home"
                    x-on:pointerdown="hDown($event)"
                    x-on:pointermove="hMove($event)"
                    x-on:pointerup="hEnd()"
                    x-on:pointercancel="hEnd()">
                    <div class="iohome-track" :class="drag.on ? 'iohome-notrans' : ''" :style="trackStyle()">
                        <template x-for="(grp, gi) in pagedApps" :key="gi">
                            <div class="iohome-page" role="group" :aria-label="pageNames[gi] || pageNames[0]">
                                <div class="iohome-grid">
                                    <template x-for="(a, ix) in grp" :key="a.id">
                                        <div class="iohome-cell" :style="'--i:' + ((gi * per + ix) % 9)" :data-out="removing === a.id ? '' : null">
                                            <button type="button" class="iohome-app" :aria-label="a.label" x-on:click.stop="open(a, $event)">
                                                <span class="iohome-tile" :data-tile="a.tile || null" :style="tileStyle(a)">
                                                    <i x-show="a.svg" x-html="a.svg"></i>
                                                    <span class="iohome-cal" x-show="a.tile === 'cal'">
                                                        <small>{{ $calDow }}</small>
                                                        <b>{{ $calDay }}</b>
                                                    </span>
                                                </span>
                                                <span class="iohome-lab" x-text="a.label"></span>
                                            </button>
                                            <button type="button" class="iohome-x" :aria-label="a.label + ' — {{ $say('remove', 'حذف') }}'" x-on:click.stop="removeApp(a.id)">{!! $xSvg !!}</button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="iohome-dots">
                    <template x-for="i in pageCount" :key="'dot' + i">
                        <button type="button" class="iohome-dot" :class="cur === i - 1 ? 'iohome-on' : ''"
                            :aria-label="pageNames[i - 1]" :aria-current="cur === i - 1 ? 'true' : null"
                            x-on:click.stop="page = i - 1"></button>
                    </template>
                </div>

                <div class="iohome-dock" role="group" aria-label="{{ $say('Dock', 'داک') }}">
                    <template x-for="a in dock.filter(x => !x.gone)" :key="a.id">
                        <div class="iohome-cell" :style="'--i:' + (dock.indexOf(a) % 9)" :data-out="removing === a.id ? '' : null">
                            <button type="button" class="iohome-app" :aria-label="a.label" x-on:click.stop="open(a, $event)">
                                <span class="iohome-tile" :data-tile="a.tile || null" :style="tileStyle(a)">
                                    <i x-show="a.svg" x-html="a.svg"></i>
                                </span>
                            </button>
                            <button type="button" class="iohome-x" :aria-label="a.label + ' — {{ $say('remove', 'حذف') }}'" x-on:click.stop="removeApp(a.id)">{!! $xSvg !!}</button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- app views (zoom open/close) --}}
            <div class="iohome-appview" x-ref="appview" tabindex="-1" role="group" :aria-label="app ? app.label : ''"
                :class="bOn ? 'iohome-notrans' : ''"
                :style="(bOn ? 'translate: 0px ' + bY + 'px; ' : '') + 'transform-origin: ' + ox + '% ' + oy + '%'">

                <template x-if="app && app.kind === 'settings'">
                    <div class="iohome-app">
                        <header class="iohome-apphead"><b>{{ $say('Settings', 'تنظیمات') }}</b></header>
                        <div class="iohome-appscroll" style="--ioh-focus: var(--ioh-blue)">
                            <h4 class="iohome-ghead">{{ $say('Wireless', 'اتصال‌ها') }}</h4>
                            <div class="iohome-card">
                                <div class="iohome-row">
                                    <span class="iohome-rico" style="background: var(--ioh-orange)">{!! $dPlane !!}</span>
                                    <span class="iohome-rlab">{{ $say('Airplane Mode', 'حالت پرواز') }}</span>
                                    <button type="button" class="iohome-sw" :aria-pressed="airplane ? 'true' : 'false'"
                                        aria-label="{{ $say('Airplane Mode', 'حالت پرواز') }}" x-on:click="airplane = !airplane"><i></i></button>
                                </div>
                                <div class="iohome-row">
                                    <span class="iohome-rico" style="background: var(--ioh-blue)">{!! $dWifi !!}</span>
                                    <span class="iohome-rlab">{{ $say('Wi-Fi', 'وای‌فای') }}</span>
                                    <span class="iohome-rval" x-show="wifi">{{ $say('NabuX-5G', 'نابو‌اکس-۵G') }}</span>
                                    <button type="button" class="iohome-sw" :aria-pressed="wifi ? 'true' : 'false'"
                                        aria-label="{{ $say('Wi-Fi', 'وای‌فای') }}" x-on:click="wifi = !wifi"><i></i></button>
                                </div>
                                <div class="iohome-row">
                                    <span class="iohome-rico" style="background: var(--ioh-blue)">{!! $dBt !!}</span>
                                    <span class="iohome-rlab">{{ $say('Bluetooth', 'بلوتوث') }}</span>
                                    <span class="iohome-rval" x-show="bt">{{ $say('On', 'روشن') }}</span>
                                    <button type="button" class="iohome-sw" :aria-pressed="bt ? 'true' : 'false'"
                                        aria-label="{{ $say('Bluetooth', 'بلوتوث') }}" x-on:click="bt = !bt"><i></i></button>
                                </div>
                            </div>
                            <p class="iohome-gfoot">{{ $say('Airplane mode turns off the radios. Wi-Fi and Bluetooth can be turned back on on their own.', 'حالت پرواز رادیوها را خاموش می‌کند. وای‌فای و بلوتوث می‌توانند دوباره خودشان روشن شوند.') }}</p>

                            <h4 class="iohome-ghead">{{ $say('Display', 'نمایش') }}</h4>
                            <div class="iohome-card">
                                <div class="iohome-row">
                                    <span class="iohome-rico" style="background: #f2b23e">{!! $io('sun') !!}</span>
                                    <span class="iohome-rlab">{{ $say('Brightness', 'روشنایی') }}</span>
                                    <input type="range" class="iohome-range iohome-grow" min="0" max="55" step="1"
                                        :value="Math.round(dims * 100)" x-on:input="dims = $event.target.value / 100"
                                        aria-label="{{ $say('Brightness', 'روشنایی') }}">
                                </div>
                            </div>
                            <p class="iohome-gfoot">{{ $say('This slider really dims the demo’s wallpaper — the vertical one in Control Centre moves the same variable.', 'این اسلایدر واقعاً کاغذدیواری دمو را تاریک می‌کند — اسلایدر عمودی کنترل سنتر همان متغیر را جابه‌جا می‌کند.') }}</p>

                            <h4 class="iohome-ghead">{{ $say('General', 'عمومی') }}</h4>
                            <div class="iohome-card">
                                <div class="iohome-row">
                                    <span class="iohome-rico" style="background: #8a8c93">{!! $io('settings') !!}</span>
                                    <span class="iohome-rlab">{{ $say('General', 'عمومی') }}</span>
                                    <span class="iohome-chev" aria-hidden="true">{!! $dChev !!}</span>
                                </div>
                                <div class="iohome-row">
                                    <span class="iohome-rico" style="background: #5c5ea0">{!! $io('info') !!}</span>
                                    <span class="iohome-rlab">{{ $say('About', 'درباره') }}</span>
                                    <span class="iohome-chev" aria-hidden="true">{!! $dChev !!}</span>
                                </div>
                            </div>
                            <p class="iohome-gfoot">{{ $say('NabuXOS 26.1 · a loving forgery', 'نابو‌اکس‌او‌اس ۲۶.۱ · یک جعل دوستانه') }}</p>
                        </div>
                    </div>
                </template>

                <template x-if="app && app.kind === 'weather'">
                    <div class="iohome-app iohome-weather" style="--ioh-focus: #fff">
                        <div class="iohome-wxhead">
                            <b>{{ $say('Istanbul', 'استانبول') }}</b>
                            <span class="iohome-wxtemp">{{ $fn(18) }}°</span>
                            <span class="iohome-wxcond">{{ $say('Clear', 'صاف') }}</span>
                            <span class="iohome-wxhl">{{ $say('H:24° · L:12°', 'بیشینه ۲۴° · کمینه ۱۲°') }}</span>
                        </div>
                        <div class="iohome-wxcard">
                            <div class="iohome-wxlabel">{{ $say('Hourly forecast', 'پیش‌بینی ساعتی') }}</div>
                            <div class="iohome-hours">
                                @foreach ($hours as $h)
                                    <span class="iohome-hour">
                                        <span>{{ $h['t'] }}</span>
                                        <span aria-hidden="true">{!! $h['g'] !!}</span>
                                        <b>{{ $h['d'] }}</b>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="iohome-wxcard">
                            <div class="iohome-wxlabel">{{ $say('4-day forecast', 'پیش‌بینی ۴ روز') }}</div>
                            <div class="iohome-days">
                                @foreach ($days as $d)
                                    <div class="iohome-day">
                                        <span>{{ $d['n'] }}</span>
                                        <span aria-hidden="true">{!! $d['g'] !!}</span>
                                        <span class="iohome-daylo">{{ $d['lo'] }}</span>
                                        <span class="iohome-daytrack" aria-hidden="true"><i style="--l: {{ $d['l'] }}; --w: {{ $d['w'] }}"></i></span>
                                        <span class="iohome-dayhi">{{ $d['hi'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="app && app.kind !== 'settings' && app.kind !== 'weather'">
                    <div class="iohome-app iohome-generic" style="--ioh-focus: var(--ioh-blue)">
                        <span class="iohome-bigtile" :data-tile="app.tile || null" :style="tileStyle(app)">
                            <span x-html="app.svg || ''"></span>
                            <span class="iohome-cal" x-show="app.tile === 'cal'">
                                <small>{{ $calDow }}</small>
                                <b>{{ $calDay }}</b>
                            </span>
                        </span>
                        <b x-text="app ? app.label : ''"></b>
                        <p>{{ $say('A placeholder app — the zoom that opened it, the home bar below and the shell around it are the real demo.', 'یک اپ جانشین — زومی که آن را باز کرد، نوار خانهٔ پایین و پوستهٔ دورش، دموی واقعی‌اند.') }}</p>
                        <button type="button" class="iohome-backbtn" x-on:click="closeApp()">{{ $say('Close', 'بستن') }}</button>
                    </div>
                </template>
            </div>

            {{-- control centre --}}
            <button type="button" class="iohome-scrim" tabindex="-1" aria-hidden="true" x-on:click="cc = false"></button>
            <div class="iohome-cc" role="dialog" aria-label="{{ $say('Control Centre', 'کنترل سنتر') }}"
                :class="(pOn && pCap) || (cOn && cY < 0) ? 'iohome-notrans' : ''"
                :style="ccStyle()"
                :inert="cc ? null : ''"
                x-on:pointerdown="cDown($event)"
                x-on:pointermove="cMove($event)"
                x-on:pointerup="cEnd()"
                x-on:pointercancel="cEnd()">
                <div class="iohome-ccgrid">
                    <div class="iohome-mod">
                        <div class="iohome-conn">
                            <button type="button" class="iohome-ctog" data-tone="orange" :aria-pressed="airplane ? 'true' : 'false'"
                                aria-label="{{ $say('Airplane mode', 'حالت پرواز') }}" x-on:click="airplane = !airplane">{!! $dPlane !!}</button>
                            <button type="button" class="iohome-ctog" data-tone="green" :aria-pressed="cell ? 'true' : 'false'"
                                aria-label="{{ $say('Cellular data', 'دیتای همراه') }}" x-on:click="cell = !cell">{!! $dCell !!}</button>
                            <button type="button" class="iohome-ctog" :aria-pressed="wifi ? 'true' : 'false'"
                                aria-label="{{ $say('Wi-Fi', 'وای‌فای') }}" x-on:click="wifi = !wifi">{!! $dWifi !!}</button>
                            <button type="button" class="iohome-ctog" :aria-pressed="bt ? 'true' : 'false'"
                                aria-label="{{ $say('Bluetooth', 'بلوتوث') }}" x-on:click="bt = !bt">{!! $dBt !!}</button>
                        </div>
                    </div>
                    <div class="iohome-mod iohome-np">
                        <div class="iohome-nprow">
                            <span class="iohome-art" style="background: linear-gradient(140deg, #7c4dff, #38b9e8)" aria-hidden="true">{!! $io('music') !!}</span>
                            <span class="iohome-npstack">
                                <b>{{ $say('Midnight Drive', 'راندن در نیمه‌شب') }}</b>
                                <small>{{ $say('Neon Tales — Vahid', 'قصه‌های نئون — وحید') }}</small>
                            </span>
                            <button type="button" class="iohome-play" :aria-pressed="playing ? 'true' : 'false'"
                                :aria-label="playing ? '{{ $say('Pause', 'توقف') }}' : '{{ $say('Play', 'پخش') }}'"
                                x-on:click="playing = !playing">
                                <i x-show="!playing" {!! $io('play') !!}</i>
                                <i x-show="playing" x-cloak style="display: grid; place-items: center">{!! $io('pause') !!}</i>
                            </button>
                        </div>
                        <span class="iohome-npbar" aria-hidden="true"><i></i></span>
                    </div>
                    <div class="iohome-mod iohome-vs">
                        <span class="iohome-vpill" role="slider" tabindex="0" aria-orientation="vertical"
                            aria-label="{{ $say('Brightness', 'روشنایی') }}" :aria-valuemin="0" :aria-valuemax="55" :aria-valuenow="Math.round(dims * 100)"
                            :style="'--f: ' + fill('dims')"
                            x-on:pointerdown="sDown($event, 'dims')" x-on:pointermove="sMove($event, 'dims')"
                            x-on:pointerup="sEnd()" x-on:pointercancel="sEnd()"
                            x-on:keydown.down.prevent="dims = Math.max(0, dims - .05)"
                            x-on:keydown.up.prevent="dims = Math.min(.55, dims + .05)">
                            <i aria-hidden="true"></i>
                            <span class="iohome-vico" aria-hidden="true">{!! $io('sun') !!}</span>
                        </span>
                    </div>
                    <div class="iohome-mod iohome-vs">
                        <span class="iohome-vpill" role="slider" tabindex="0" aria-orientation="vertical"
                            aria-label="{{ $say('Volume', 'بلندی صدا') }}" :aria-valuemin="0" :aria-valuemax="100" :aria-valuenow="Math.round(vol * 100)"
                            :style="'--f: ' + fill('vol')"
                            x-on:pointerdown="sDown($event, 'vol')" x-on:pointermove="sMove($event, 'vol')"
                            x-on:pointerup="sEnd()" x-on:pointercancel="sEnd()"
                            x-on:keydown.down.prevent="vol = Math.max(0, vol - .05)"
                            x-on:keydown.up.prevent="vol = Math.min(1, vol + .05)">
                            <i aria-hidden="true"></i>
                            <span class="iohome-vico" aria-hidden="true">
                                <svg class="nx-icon iohome-glyph" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 9.6v4.8h3.4L12 18.6V5.4L7.4 9.6z"/><path d="M15 9.2a4.4 4.4 0 0 1 0 5.6M17.6 6.8a8 8 0 0 1 0 10.4"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="iohome-ccrow">
                    <button type="button" class="iohome-rbtn" :aria-pressed="torch ? 'true' : 'false'"
                        aria-label="{{ $say('Torch', 'چراغ‌قوه') }}" x-on:click="torch = !torch">{!! $dTorch !!}</button>
                    <button type="button" class="iohome-rbtn" aria-label="{{ $say('Calculator', 'ماشین‌حساب') }}"
                        x-on:click="openCalc()">{!! $dCalc !!}</button>
                </div>
            </div>

            {{-- pull-down zone for the control centre --}}
            <div class="iohome-pullzone"
                x-on:pointerdown="pDown($event)"
                x-on:pointermove="pMove($event)"
                x-on:pointerup="pEnd()"
                x-on:pointercancel="pEnd()">
                <button type="button" class="iohome-handle" x-on:click="cc = !cc"
                    :aria-expanded="cc ? 'true' : 'false'" aria-label="{{ $say('Control Centre', 'کنترل سنتر') }}">{!! $dChevDown !!}</button>
            </div>

            {{-- home bar --}}
            <div class="iohome-barzone"
                :aria-label="app ? '{{ $say('Go home', 'بازگشت به خانه') }}' : null"
                x-on:pointerdown="bDown($event)"
                x-on:pointermove="bMove($event)"
                x-on:pointerup="bEnd($event)"
                x-on:pointercancel="bEnd()">
                <span class="iohome-homebar" aria-hidden="true"></span>
            </div>

            <div class="iohome-dim" aria-hidden="true"></div>
        </section>
    </div>
</section>
