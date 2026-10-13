{{--
    The launcher capstone: the whole Android shell — Material 3 / Material You —
    inside one phone frame. Two home pages with an at-a-glance widget and page
    dots, a dock under a search pill, a notification shade that follows the
    finger down from the status bar with quick-settings tiles and expandable
    notifications, an app drawer that pulls up from the gesture pill with a
    live-filtering search, a zoom-open music player and notes app, and three
    wallpapers whose hue seeds the accent that recolours tiles, switches,
    sliders and handles — Material You's dynamic colour, live.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // A tiny local glyph set for the Android-specific icons the core set lacks.
    $own = [
        'phone' => '<path d="M21 16.9v2.1a1.9 1.9 0 0 1-2.1 1.9 18.6 18.6 0 0 1-8.1-2.9 18.3 18.3 0 0 1-5.6-5.6A18.6 18.6 0 0 1 2.3 4.2 1.9 1.9 0 0 1 4.2 2.1h2.1a1.9 1.9 0 0 1 1.9 1.7c.1 1 .4 2 .7 2.9a1.9 1.9 0 0 1-.4 2L7.3 10a15 15 0 0 0 5.6 5.6l1.3-1.2a1.9 1.9 0 0 1 2-.4c.9.3 1.9.6 2.9.7a1.9 1.9 0 0 1 1.9 2.2z"/>',
        'camera' => '<path d="M22 18.5a1.8 1.8 0 0 1-1.8 1.8H3.8A1.8 1.8 0 0 1 2 18.5V7.8a1.8 1.8 0 0 1 1.8-1.8h3.4l1.9-2.7h7.8l1.9 2.7h3.4A1.8 1.8 0 0 1 22 7.8z"/><circle cx="12" cy="13" r="3.8"/>',
        'clock' => '<circle cx="12" cy="12" r="9.2"/><path d="M12 6.8V12l3.4 2"/>',
        'calendar' => '<rect x="3.5" y="5" width="17" height="16" rx="2.4"/><path d="M16 2.8V7M8 2.8V7M3.5 11h17"/>',
        'map' => '<path d="M2.5 6.2 9 3.5l6 2.7 6.5-2.7v14.3L15 20.5l-6-2.7-6.5 2.7z"/><path d="M9 3.5v14.3M15 6.2v14.3"/>',
        'weather' => '<circle cx="7.8" cy="7" r="2.9"/><path d="M7.8 1.8v1.3M2.6 7h1.3M4.1 3.3l.9.9M11.5 3.3l-.9.9"/><path d="M11.5 20.2a3.9 3.9 0 0 1-.5-7.8 5.3 5.3 0 0 1 10.2 1.7 3.1 3.1 0 0 1-.9 6.1z"/>',
        'calc' => '<rect x="4.5" y="2.5" width="15" height="19" rx="2.4"/><path d="M8.5 6.5h7"/><path d="M8.5 11h.01M12 11h.01M15.5 11h.01M8.5 14.5h.01M12 14.5h.01M15.5 14.5h.01M8.5 18h.01M12 18h.01M15.5 18h.01"/>',
        'store' => '<path d="M6.3 2.8 3.8 6.6a1.6 1.6 0 0 0-.3 1v13a1.4 1.4 0 0 0 1.4 1.4h14.2a1.4 1.4 0 0 0 1.4-1.4v-13a1.6 1.6 0 0 0-.3-1l-2.5-3.8a1.5 1.5 0 0 0-1.2-.6H7.5a1.5 1.5 0 0 0-1.2.6z"/><path d="M3.5 7.5h17"/><path d="M15.5 11.5a3.5 3.5 0 0 1-7 0"/>',
        'wallet' => '<path d="M19.5 7.5V6a2 2 0 0 0-2-2H4.5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h15a2 2 0 0 0 2-2V9.5a2 2 0 0 0-2-2H4.5"/><path d="M16.5 13h.01"/>',
        'translate' => '<path d="M4 5.5h9M8.2 3.5v2M11 5.5a12 12 0 0 1-6.3 7"/><path d="M6.3 8.2a13.4 13.4 0 0 0 6.2 4.3"/><path d="m13 21 4.4-9.5L21.8 21M14.7 17.5h5.4"/>',
        'radio' => '<circle cx="12" cy="12" r="2.2"/><path d="M16.2 7.8a6 6 0 0 1 0 8.4M7.8 16.2a6 6 0 0 1 0-8.4M19 5a10 10 0 0 1 0 14M5 19A10 10 0 0 1 5 5"/>',
        'book' => '<path d="M4.5 19.2a2.3 2.3 0 0 1 2.3-2.3h12.7"/><path d="M6.8 2.8h12.7v18.4H6.8a2.3 2.3 0 0 1-2.3-2.3V5.1a2.3 2.3 0 0 1 2.3-2.3z"/>',
        'compass' => '<circle cx="12" cy="12" r="9.2"/><path d="m15.7 8.3-1.9 5.5-5.5 1.9 1.9-5.5z"/>',
        'game' => '<rect x="2.2" y="6.8" width="19.6" height="10.4" rx="5.2"/><path d="M7.6 10.2v3.6M5.8 12h3.6"/><path d="M15.4 12h.01M18.2 10.2h.01"/>',
        'wifi' => '<path d="M3.4 9.8a14 14 0 0 1 17.2 0"/><path d="M6.5 13.4a9 9 0 0 1 11 0"/><path d="M9.6 16.9a4.6 4.6 0 0 1 4.8 0"/><path d="M12 20h.01"/>',
        'bt' => '<path d="m6.5 7 11 10-5.5 4.5v-19L17.5 7l-11 10"/>',
        'torch' => '<path d="M10 2.5h4v3.2l-1.6 2.2v13a.9.9 0 0 1-1.8 0v-13L9 5.7V2.5z"/><path d="M9.2 5.7h5.6"/>',
        'dnd' => '<circle cx="12" cy="12" r="9.2"/><path d="M8 12h8"/>',
        'rotate' => '<path d="M20.5 12a8.5 8.5 0 1 1-2.5-6"/><path d="M18.6 2.6v3.9h-3.9"/>',
        'tether' => '<circle cx="12" cy="18.6" r="1.6"/><path d="M8.6 15a4.8 4.8 0 0 1 6.8 0"/><path d="M5.9 12.2a8.6 8.6 0 0 1 12.2 0"/><path d="M3.2 9.4a12.4 12.4 0 0 1 17.6 0"/>',
        'download' => '<path d="M20.5 15.5v3a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-3"/><path d="m7.5 10.5 4.5 4.5 4.5-4.5"/><path d="M12 15V3.5"/>',
        'lens' => '<circle cx="12" cy="12" r="3.4"/><path d="M4.5 8.2V6.3a1.8 1.8 0 0 1 1.8-1.8h1.9M15.8 4.5h1.9a1.8 1.8 0 0 1 1.8 1.8v1.9M19.5 15.8v1.9a1.8 1.8 0 0 1-1.8 1.8h-1.9M8.2 19.5H6.3a1.8 1.8 0 0 1-1.8-1.8v-1.9"/>',
        'palette' => '<path d="M12 3a9 9 0 1 0 .3 18c1.2 0 1.9-.8 1.9-1.7 0-.8-.6-1.3-.6-2.1 0-1 .8-1.7 1.9-1.7h1.9A4.6 4.6 0 0 0 22 11c-.4-4.4-4.7-8-10-8z"/><path d="M7.5 10.5h.01M11 7h.01M15.5 8.5h.01M7.8 14.5h.01"/>',
        'prev' => '<path d="M18 18.5 9.5 12 18 5.5z"/><path d="M6.5 5.5v13"/>',
        'next' => '<path d="M6 5.5 14.5 12 6 18.5z"/><path d="M17.5 5.5v13"/>',
    ];
    $ico = fn (string $n) => isset($own[$n])
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$own[$n].'</svg>'
        : (string) \NabuXUI\NabuXUI::icon($n);

    // The app catalogue: ids, search keys, gradient hue, glyph, bilingual name.
    $appsAll = [
        ['id' => 'phone', 'name' => $say('Phone', 'تلفن'), 'latin' => 'phone dial', 'hue' => 145, 'glyph' => $ico('phone')],
        ['id' => 'messages', 'name' => $say('Messages', 'پیام‌ها'), 'latin' => 'messages sms', 'hue' => 210, 'glyph' => $ico('message')],
        ['id' => 'browser', 'name' => $say('Browser', 'مرورگر'), 'latin' => 'browser web chrome', 'hue' => 262, 'glyph' => $ico('globe')],
        ['id' => 'music', 'name' => $say('Music', 'موسیقی'), 'latin' => 'music player', 'hue' => 332, 'glyph' => $ico('music')],
        ['id' => 'notes', 'name' => $say('Notes', 'یادداشت‌ها'), 'latin' => 'notes keep memo', 'hue' => 44, 'glyph' => $ico('edit')],
        ['id' => 'camera', 'name' => $say('Camera', 'دوربین'), 'latin' => 'camera photo', 'hue' => 24, 'glyph' => $ico('camera')],
        ['id' => 'photos', 'name' => $say('Photos', 'عکس‌ها'), 'latin' => 'photos gallery', 'hue' => 192, 'glyph' => $ico('image')],
        ['id' => 'clock', 'name' => $say('Clock', 'ساعت'), 'latin' => 'clock alarm', 'hue' => 356, 'glyph' => $ico('clock')],
        ['id' => 'weather', 'name' => $say('Weather', 'آب‌وهوا'), 'latin' => 'weather forecast', 'hue' => 205, 'glyph' => $ico('weather')],
        ['id' => 'calendar', 'name' => $say('Calendar', 'تقویم'), 'latin' => 'calendar agenda', 'hue' => 282, 'glyph' => $ico('calendar')],
        ['id' => 'maps', 'name' => $say('Maps', 'نقشه‌ها'), 'latin' => 'maps navigation', 'hue' => 112, 'glyph' => $ico('map')],
        ['id' => 'mail', 'name' => $say('Mail', 'ایمیل'), 'latin' => 'mail gmail', 'hue' => 6, 'glyph' => $ico('mail')],
        ['id' => 'files', 'name' => $say('Files', 'فایل‌ها'), 'latin' => 'files folder', 'hue' => 36, 'glyph' => $ico('folder')],
        ['id' => 'settings', 'name' => $say('Settings', 'تنظیمات'), 'latin' => 'settings preferences', 'hue' => 224, 'glyph' => $ico('settings')],
        ['id' => 'calc', 'name' => $say('Calculator', 'ماشین‌حساب'), 'latin' => 'calculator math', 'hue' => 160, 'glyph' => $ico('calc')],
        ['id' => 'store', 'name' => $say('Store', 'فروشگاه'), 'latin' => 'store play market', 'hue' => 150, 'glyph' => $ico('store')],
        ['id' => 'wallet', 'name' => $say('Wallet', 'کیف پول'), 'latin' => 'wallet pay', 'hue' => 48, 'glyph' => $ico('wallet')],
        ['id' => 'translate', 'name' => $say('Translate', 'ترجمه'), 'latin' => 'translate language', 'hue' => 260, 'glyph' => $ico('translate')],
        ['id' => 'health', 'name' => $say('Health', 'سلامت'), 'latin' => 'health fit', 'hue' => 348, 'glyph' => $ico('heart')],
        ['id' => 'radio', 'name' => $say('Radio', 'رادیو'), 'latin' => 'radio fm', 'hue' => 16, 'glyph' => $ico('radio')],
        ['id' => 'books', 'name' => $say('Books', 'کتاب‌ها'), 'latin' => 'books read epub', 'hue' => 30, 'glyph' => $ico('book')],
        ['id' => 'ai', 'name' => $say('Nabu AI', 'هوش نابو'), 'latin' => 'ai assistant nabu', 'hue' => 276, 'glyph' => $ico('sparkles')],
        ['id' => 'compass', 'name' => $say('Compass', 'قطب‌نما'), 'latin' => 'compass north', 'hue' => 200, 'glyph' => $ico('compass')],
        ['id' => 'recorder', 'name' => $say('Recorder', 'ضبط صدا'), 'latin' => 'recorder voice memo', 'hue' => 320, 'glyph' => $ico('mic')],
        ['id' => 'game', 'name' => $say('Games', 'بازی‌ها'), 'latin' => 'games play fun', 'hue' => 128, 'glyph' => $ico('game')],
    ];
    $byId = fn (array $ids) => array_values(array_filter($appsAll, fn ($a) => in_array($a['id'], $ids, true)));
    $page1 = $byId(['camera', 'photos', 'clock', 'weather', 'calendar', 'maps', 'mail', 'files']);
    $page2 = $byId(['settings', 'calc', 'store', 'wallet', 'translate', 'health', 'radio', 'books', 'ai', 'compass', 'recorder', 'game']);
    $dock = $byId(['phone', 'messages', 'browser', 'music', 'notes']);

    $drawerApps = $byId(['phone', 'messages', 'browser', 'camera', 'photos', 'clock', 'weather', 'calendar', 'maps', 'mail', 'files', 'settings', 'calc', 'store', 'music', 'notes']);
    usort($drawerApps, fn ($a, $b) => strcmp($a['name'], $b['name']));
    $catalog = array_map(fn ($a) => ['id' => $a['id'], 'name' => $a['name'], 'latin' => $a['latin'], 'hue' => $a['hue'], 'glyph' => $a['glyph']], $appsAll);

    $tiles = [
        ['id' => 'wifi', 'name' => $say('Wi-Fi', 'وای‌فای'), 'glyph' => $ico('wifi'), 'on' => true],
        ['id' => 'bt', 'name' => $say('Bluetooth', 'بلوتوث'), 'glyph' => $ico('bt'), 'on' => false],
        ['id' => 'torch', 'name' => $say('Flashlight', 'چراغ‌قوه'), 'glyph' => $ico('torch'), 'on' => false],
        ['id' => 'dnd', 'name' => $say('Do not disturb', 'مزاحم نشوید'), 'glyph' => $ico('dnd'), 'on' => false],
        ['id' => 'rotate', 'name' => $say('Auto-rotate', 'چرخش خودکار'), 'glyph' => $ico('rotate'), 'on' => true],
        ['id' => 'tether', 'name' => $say('Hotspot', 'اشتراک اینترنت'), 'glyph' => $ico('tether'), 'on' => false],
    ];

    $tracks = [
        ['title' => $say('Desert Rain', 'باران کویر'), 'artist' => $say('Ava Bennett', 'آوا رستمی'), 'dur' => 214],
        ['title' => $say('Midnight Metro', 'متروی نیمه‌شب'), 'artist' => $say('The Nabu Tapes', 'نوارهای نابو'), 'dur' => 187],
        ['title' => $say('Paper Kites', 'بادبادک‌های کاغذی'), 'artist' => $say('Sara and the Reeds', 'سارا و نی‌ها'), 'dur' => 232],
    ];

    $notes = [
        ['id' => 1, 'hue' => 44, 'title' => $say('Groceries', 'خرید'), 'body' => $say('Saffron, cardamom, fresh naan from the corner bakery.', 'زعفران، هل، نان تازه از نانوایی سرِ کوچه.')],
        ['id' => 2, 'hue' => 196, 'title' => $say('Demo checklist', 'چک‌لیست دمو'), 'body' => $say('Tune the shade spring, record the drawer swipe, ship it.', 'فنر سایه را تمیز کن، کشیدن دراور را ضبط کن، منتشر کن.')],
        ['id' => 3, 'hue' => 332, 'title' => $say('Book ideas', 'ایدهٔ کتاب'), 'body' => $say('A launcher that learns the hour; an interface that borrows its colour from the sky.', 'لانچری که ساعت را یاد می‌گیرد؛ رابطی که رنگش را از آسمان قرض می‌گیرد.')],
    ];
    $hues = [44, 196, 332, 128, 260, 16];
    $noteTpl = [$say('New note', 'یادداشت تازه'), $say('Written just now in the launcher demo.', 'همین حالا در دموی لانچر نوشته شد.')];

    $walls = [
        ['id' => 'night', 'name' => $say('Night purple', 'بنفش شب')],
        ['id' => 'forest', 'name' => $say('Forest green', 'سبز جنگل')],
        ['id' => 'dawn', 'name' => $say('Orange dawn', 'طلوع نارنجی')],
    ];
@endphp

<style>
    [x-cloak] { display: none !important; }

    /* ---- Material You roles: light defaults + wallpaper-seeded accent trio ---- */
    .m3home-root {
        --m3home-surface: #FEF7FF;
        --m3home-surface-container: #F3EDF7;
        --m3home-on-surface: #1D1B20;
        --m3home-on-surface-var: #49454F;
        --m3home-outline: #79747E;
        --m3home-accent: #6D4FD2;
        --m3home-on-accent: #FFFFFF;
        --m3home-container: color-mix(in oklab, var(--m3home-accent) 16%, var(--m3home-surface));
        --m3home-on-container: color-mix(in oklab, var(--m3home-accent) 60%, var(--m3home-on-surface));
        --m3home-flow: right;
        display: grid;
        justify-items: center;
    }
    .m3home-root[data-wp='night'] { --m3home-accent: #6D4FD2; --m3home-on-accent: #FFFFFF; }
    .m3home-root[data-wp='forest'] { --m3home-accent: #2E7D46; --m3home-on-accent: #FFFFFF; }
    .m3home-root[data-wp='dawn'] { --m3home-accent: #BC5A1B; --m3home-on-accent: #FFFFFF; }

    html[data-theme='dark'] .m3home-root {
        --m3home-surface: #141218;
        --m3home-surface-container: #211F26;
        --m3home-on-surface: #E6E0E9;
        --m3home-on-surface-var: #CAC4D0;
        --m3home-outline: #938F99;
        --m3home-flow: right;
    }
    html[data-theme='dark'] .m3home-root[data-wp='night'] { --m3home-accent: #C3B0FF; --m3home-on-accent: #2A1653; }
    html[data-theme='dark'] .m3home-root[data-wp='forest'] { --m3home-accent: #9CDCA0; --m3home-on-accent: #0E3117; }
    html[data-theme='dark'] .m3home-root[data-wp='dawn'] { --m3home-accent: #FFB77E; --m3home-on-accent: #532004; }

    @media (prefers-color-scheme: dark) {
        html:not([data-theme='light']) .m3home-root {
            --m3home-surface: #141218;
            --m3home-surface-container: #211F26;
            --m3home-on-surface: #E6E0E9;
            --m3home-on-surface-var: #CAC4D0;
            --m3home-outline: #938F99;
            --m3home-flow: right;
        }
        html:not([data-theme='light']) .m3home-root[data-wp='night'] { --m3home-accent: #C3B0FF; --m3home-on-accent: #2A1653; }
        html:not([data-theme='light']) .m3home-root[data-wp='forest'] { --m3home-accent: #9CDCA0; --m3home-on-accent: #0E3117; }
        html:not([data-theme='light']) .m3home-root[data-wp='dawn'] { --m3home-accent: #FFB77E; --m3home-on-accent: #532004; }
    }

    [dir='rtl'] .m3home-root { --m3home-flow: left; }

    /* ---- The frame ---- */
    .m3home-frame {
        position: relative;
        inline-size: min(100%, 21rem);
        aspect-ratio: 9 / 19;
        border: 10px solid #0b0b10;
        border-radius: 2.25rem;
        overflow: clip;
        isolation: isolate;
        background: #0d0a22;
        box-shadow: 0 30px 70px -30px #0b071699, 0 0 0 1px #ffffff14;
        user-select: none;
        -webkit-user-select: none;
        outline: none;
    }
    .m3home-frame :focus-visible { outline: 2px solid var(--m3home-accent); outline-offset: 2px; }
    .m3home-frame svg { inline-size: 24px; block-size: 24px; stroke-width: 1.9; }
    .m3home-cam { position: absolute; inset-block-start: 13px; inset-inline-start: 50%; translate: -50% 0; z-index: 70; inline-size: 11px; aspect-ratio: 1; border-radius: 50%; background: #000; box-shadow: inset 0 0 3px 1px #23233d; pointer-events: none; }

    .m3home-wall { position: absolute; inset: 0; z-index: 0; }
    .m3home-wall i { position: absolute; inset: 0; opacity: 0; transition: opacity .6s ease; }
    .m3home-wall i[data-wp='night'] { background:
        radial-gradient(120% 80% at 82% 0%, #574299 0%, transparent 55%),
        radial-gradient(130% 90% at 12% 96%, #2a1d63 8%, transparent 62%),
        linear-gradient(168deg, #2c2164 0%, #141034 52%, #0c0920 100%); }
    .m3home-wall i[data-wp='forest'] { background:
        radial-gradient(120% 80% at 20% 0%, #3f8f68 0%, transparent 55%),
        radial-gradient(120% 90% at 88% 100%, #14432e 10%, transparent 60%),
        linear-gradient(166deg, #17442f 0%, #0c291c 55%, #071710 100%); }
    .m3home-wall i[data-wp='dawn'] { background:
        radial-gradient(150% 75% at 50% 112%, #ffb066 0%, #e07040 34%, transparent 68%),
        radial-gradient(110% 60% at 85% -6%, #7c3a63 0%, transparent 60%),
        linear-gradient(180deg, #381640 0%, #6e2f43 62%, #b3502e 100%); }
    .m3home-root[data-wp='night'] .m3home-wall i[data-wp='night'],
    .m3home-root[data-wp='forest'] .m3home-wall i[data-wp='forest'],
    .m3home-root[data-wp='dawn'] .m3home-wall i[data-wp='dawn'] { opacity: 1; }
    .m3home-dim { position: absolute; inset: 0; z-index: 1; background: #000; opacity: 0; pointer-events: none; }

    /* ---- Status bar ---- */
    .m3home-status { position: absolute; inset-block-start: 0; inset-inline: 0; block-size: 44px; z-index: 30; display: flex; align-items: center; justify-content: space-between; padding-inline: 24px 18px; color: #fff; pointer-events: none; touch-action: none; }
    .m3home-time { font-size: 14px; font-weight: 600; letter-spacing: .02em; }
    .m3home-sig { display: inline-flex; align-items: center; gap: 6px; }
    .m3home-sig svg { inline-size: 17px; block-size: 15px; }

    /* ---- Home ---- */
    .m3home-home { position: absolute; inset: 0; z-index: 10; display: flex; flex-direction: column; padding-block-start: 44px; color: #fff; }
    .m3home-wpbtn { position: absolute; inset-block-start: 52px; inset-inline-end: 12px; z-index: 25; inline-size: 36px; aspect-ratio: 1; border-radius: 50%; border: 0; cursor: pointer; display: grid; place-items: center; color: #fff; background: #ffffff24; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); box-shadow: inset 0 0 0 1px #ffffff26; }
    .m3home-wpbtn svg { inline-size: 19px; block-size: 19px; }
    .m3home-wpmenu { position: absolute; inset-block-start: 94px; inset-inline-end: 12px; z-index: 25; inline-size: 172px; padding: 6px; border-radius: 18px; background: color-mix(in oklab, #14121d 78%, transparent); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); box-shadow: 0 14px 34px -14px #000000a6, inset 0 0 0 1px #ffffff1c; display: grid; gap: 2px; }
    .m3home-wpitem { display: flex; align-items: center; gap: 10px; padding: 7px 8px; border-radius: 12px; border: 0; background: none; color: #fff; font: inherit; font-size: 13px; cursor: pointer; text-align: start; }
    .m3home-wpitem:hover { background: #ffffff17; }
    .m3home-wpitem i { inline-size: 22px; aspect-ratio: 1; border-radius: 50%; flex: none; box-shadow: inset 0 0 0 1px #ffffff30; }
    .m3home-wpitem[aria-pressed='true'] { background: #ffffff21; font-weight: 600; }
    .m3home-wpitem i[data-wp='night'] { background: linear-gradient(140deg, #8f74e8, #241a52); }
    .m3home-wpitem i[data-wp='forest'] { background: linear-gradient(140deg, #59b98a, #0f2418); }
    .m3home-wpitem i[data-wp='dawn'] { background: linear-gradient(140deg, #ffb066, #b3502e); }

    .m3home-pages { flex: 1; min-block-size: 0; overflow: clip; touch-action: none; }
    .m3home-track { display: flex; inline-size: 200%; block-size: 100%; will-change: transform; }
    .m3home-page { inline-size: 50%; padding: 10px 14px 0; display: flex; flex-direction: column; gap: 14px; }
    .m3home-settling .m3home-track { transition: transform .45s cubic-bezier(.2, 0, 0, 1); }

    .m3home-glance { border-radius: 24px; padding: 14px 18px 15px; background: #ffffff1f; backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); box-shadow: inset 0 0 0 1px #ffffff22; }
    .m3home-glance-time { font-size: 44px; font-weight: 300; line-height: 1.05; letter-spacing: .01em; }
    .m3home-glance-date { margin-block-start: 2px; font-size: 13px; opacity: .88; }
    .m3home-glance-row { display: flex; align-items: center; gap: 7px; margin-block-start: 9px; font-size: 13px; opacity: .92; }
    .m3home-glance-row svg { inline-size: 16px; block-size: 16px; color: #ffd479; }

    .m3home-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px 6px; justify-items: center; align-content: start; }
    .m3home-appbtn { display: grid; justify-items: center; gap: 5px; inline-size: 100%; padding: 0; border: 0; background: none; color: #fff; cursor: pointer; font: inherit; }
    .m3home-icon { position: relative; inline-size: 54px; aspect-ratio: 1; border-radius: 26%; display: grid; place-items: center; color: #fff; overflow: clip; background: linear-gradient(145deg, hsl(var(--h) 72% 62%), hsl(var(--h) 78% 40%)); box-shadow: inset 0 1px 0 #ffffff42, 0 4px 10px -4px #00000066; transition: scale .18s cubic-bezier(.2, 0, 0, 1); }
    .m3home-icon::after { content: ''; position: absolute; inset: 0; border-radius: inherit; background: radial-gradient(circle at 50% 42%, #ffffff 0%, transparent 62%); opacity: 0; }
    .m3home-appbtn:active .m3home-icon { scale: .88; }
    .m3home-appbtn:active .m3home-icon::after { opacity: .32; }
    .m3home-icon svg { inline-size: 25px; block-size: 25px; }
    .m3home-appname { max-inline-size: 100%; padding-inline: 2px; font-size: 12px; line-height: 1.15; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-shadow: 0 1px 5px #00000066; }

    .m3home-dots { display: flex; justify-content: center; gap: 8px; padding-block: 10px 2px; }
    .m3home-dot { inline-size: 8px; block-size: 8px; padding: 0; border: 0; border-radius: 999px; background: #ffffff59; cursor: pointer; transition: inline-size .3s cubic-bezier(.2, 0, 0, 1), background .3s; }
    .m3home-dot[aria-current='true'] { inline-size: 22px; background: var(--m3home-accent); }

    .m3home-search { margin: 8px 14px 0; block-size: 48px; flex: none; display: flex; align-items: center; gap: 11px; padding-inline: 17px; border: 0; border-radius: 999px; cursor: pointer; color: #fff; font: inherit; font-size: 14px; background: #ffffff21; backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); box-shadow: inset 0 0 0 1px #ffffff1f; touch-action: none; }
    .m3home-search > span:not(.m3home-lens) { flex: 1; min-inline-size: 0; text-align: start; opacity: .92; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .m3home-search svg { inline-size: 20px; block-size: 20px; }
    .m3home-search .m3home-lens { color: #ffd479; }
    .m3home-dock { margin: 10px 14px 22px; block-size: 78px; flex: none; display: grid; grid-template-columns: repeat(5, 1fr); place-items: center; border-radius: 26px; background: #ffffff1c; backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); box-shadow: inset 0 0 0 1px #ffffff1a; touch-action: none; }
    .m3home-dock .m3home-icon { inline-size: 50px; }
    .m3home-dock .m3home-icon svg { inline-size: 23px; block-size: 23px; }

    /* ---- Gesture pill ---- */
    .m3home-pillzone { position: absolute; inset-block-end: 5px; inset-inline: 0; block-size: 26px; z-index: 60; display: grid; place-items: center; pointer-events: none; touch-action: none; }
    .m3home-pill { inline-size: 108px; block-size: 4px; border-radius: 999px; background: #ffffffd9; box-shadow: 0 1px 4px #00000059; transition: transform .3s cubic-bezier(.2, 0, 0, 1); }

    /* ---- Notification shade ---- */
    .m3home-shade { position: absolute; inset: 0; z-index: 35; display: flex; flex-direction: column; padding: 12px 13px 0; color: var(--m3home-on-surface); background: color-mix(in oklab, var(--m3home-surface) 80%, transparent); backdrop-filter: blur(26px) saturate(1.25); -webkit-backdrop-filter: blur(26px) saturate(1.25); touch-action: none; }
    .m3home-settling .m3home-shade { transition: translate .38s cubic-bezier(.2, 0, 0, 1); }
    .m3home-shade-head { display: flex; align-items: center; justify-content: space-between; padding: 2px 8px 10px; font-size: 13px; color: var(--m3home-on-surface-var); }
    .m3home-qs { display: grid; grid-template-columns: repeat(3, 1fr); gap: 9px; }
    .m3home-tile { display: grid; justify-items: center; gap: 6px; padding: 13px 5px 10px; border: 0; border-radius: 20px; cursor: pointer; font: inherit; font-size: 11.5px; line-height: 1.25; text-align: center; background: var(--m3home-surface-container); color: var(--m3home-on-surface-var); transition: background .2s ease, color .2s ease, scale .15s; }
    .m3home-tile:active { scale: .95; }
    .m3home-tile[aria-pressed='true'] { background: var(--m3home-accent); color: var(--m3home-on-accent); }
    .m3home-tile svg { inline-size: 21px; block-size: 21px; }
    .m3home-bright { display: flex; align-items: center; gap: 10px; margin-block: 13px 4px; padding-inline: 6px; color: var(--m3home-on-surface-var); }
    .m3home-bright > svg { inline-size: 19px; block-size: 19px; flex: none; }
    .m3home-range { appearance: none; -webkit-appearance: none; flex: 1; inline-size: 100%; block-size: 28px; background: transparent; cursor: pointer; }
    .m3home-range::-webkit-slider-runnable-track { block-size: 26px; border-radius: 999px; background: linear-gradient(to var(--m3home-flow), var(--m3home-accent) var(--m3home-fill, 84%), color-mix(in oklab, var(--m3home-on-surface) 14%, transparent) var(--m3home-fill, 84%)); }
    .m3home-range::-webkit-slider-thumb { -webkit-appearance: none; inline-size: 22px; block-size: 22px; margin-block-start: 2px; border: 0; border-radius: 50%; background: #fff; box-shadow: 0 1px 5px #00000045; }
    .m3home-range::-moz-range-track { block-size: 26px; border-radius: 999px; background: linear-gradient(to var(--m3home-flow), var(--m3home-accent) var(--m3home-fill, 84%), color-mix(in oklab, var(--m3home-on-surface) 14%, transparent) var(--m3home-fill, 84%)); }
    .m3home-range::-moz-range-thumb { inline-size: 22px; block-size: 22px; border: 0; border-radius: 50%; background: #fff; box-shadow: 0 1px 5px #00000045; }
    .m3home-notifs { display: grid; gap: 9px; margin-block-start: 8px; }
    .m3home-notif { border-radius: 20px; overflow: clip; background: var(--m3home-surface-container); }
    .m3home-notif-head { display: flex; align-items: center; gap: 12px; inline-size: 100%; padding: 11px 13px; border: 0; background: none; color: inherit; font: inherit; cursor: pointer; text-align: start; }
    .m3home-notif-ava { inline-size: 38px; aspect-ratio: 1; flex: none; border-radius: 13px; display: grid; place-items: center; background: var(--m3home-container); color: var(--m3home-on-container); }
    .m3home-notif-ava svg { inline-size: 19px; block-size: 19px; }
    .m3home-notif-titles { display: grid; gap: 1px; min-inline-size: 0; }
    .m3home-notif-titles strong { font-size: 13.5px; font-weight: 600; }
    .m3home-notif-titles span { font-size: 12px; color: var(--m3home-on-surface-var); }
    .m3home-notif-when { margin-inline-start: auto; flex: none; font-size: 11px; color: var(--m3home-on-surface-var); }
    .m3home-notif-body { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .32s cubic-bezier(.2, 0, 0, 1); }
    .m3home-notif.is-open .m3home-notif-body { grid-template-rows: 1fr; }
    .m3home-notif-body > div { overflow: hidden; padding-inline: 14px; }
    .m3home-notif-body p { margin: 0; font-size: 12.5px; line-height: 1.65; color: var(--m3home-on-surface-var); }
    .m3home-notif-actions { display: flex; gap: 16px; padding: 10px 0 13px; }
    .m3home-notif-actions button { padding: 0; border: 0; background: none; color: var(--m3home-accent); font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; }
    .m3home-shade-tail { flex: 1; border: 0; background: none; cursor: pointer; }

    /* ---- App drawer ---- */
    .m3home-drawer { position: absolute; inset-inline: 0; inset-block-end: 0; block-size: 85%; z-index: 45; display: flex; flex-direction: column; padding: 7px 13px 12px; border-radius: 28px 28px 0 0; color: var(--m3home-on-surface); background: color-mix(in oklab, var(--m3home-surface) 92%, transparent); backdrop-filter: blur(30px) saturate(1.15); -webkit-backdrop-filter: blur(30px) saturate(1.15); box-shadow: 0 -18px 44px -20px #0000008c; }
    .m3home-settling .m3home-drawer { transition: translate .42s cubic-bezier(.2, 0, 0, 1); }
    .m3home-grip { inline-size: 34px; block-size: 4px; margin: 3px auto 10px; border-radius: 999px; background: var(--m3home-accent); touch-action: none; }
    .m3home-dsearch { display: flex; align-items: center; gap: 9px; block-size: 46px; flex: none; padding-inline: 8px 14px; border-radius: 999px; background: var(--m3home-surface-container); }
    .m3home-dsearch svg { inline-size: 19px; block-size: 19px; color: var(--m3home-on-surface-var); }
    .m3home-dsearch input { flex: 1; min-inline-size: 0; border: 0; background: none; outline: none; color: inherit; font: inherit; font-size: 14px; user-select: text; -webkit-user-select: text; }
    .m3home-dsearch input::placeholder { color: var(--m3home-on-surface-var); }
    .m3home-dclose { inline-size: 34px; aspect-ratio: 1; flex: none; border: 0; border-radius: 50%; background: none; color: var(--m3home-on-surface-var); display: grid; place-items: center; cursor: pointer; }
    .m3home-dclose:hover { background: color-mix(in oklab, var(--m3home-on-surface) 8%, transparent); }
    .m3home-dclose svg { inline-size: 18px; block-size: 18px; }
    .m3home-dgrid { flex: 1; min-block-size: 0; overflow-y: auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px 4px; align-content: start; justify-items: center; padding-block: 10px 6px; touch-action: pan-y; }
    .m3home-dgrid .m3home-appbtn { color: var(--m3home-on-surface); }
    .m3home-dgrid .m3home-appname { color: var(--m3home-on-surface-var); text-shadow: none; }
    .m3home-dempty { padding: 18px 8px; font-size: 13px; color: var(--m3home-on-surface-var); }

    /* ---- Open app ---- */
    .m3home-app { position: absolute; inset: 0; z-index: 50; display: grid; color: var(--m3home-on-surface); background: var(--m3home-surface); opacity: 0; visibility: hidden; scale: .6; pointer-events: none; transition: scale .42s cubic-bezier(.2, 0, 0, 1), opacity .3s ease, visibility 0s .42s; }
    .m3home-app.is-open { opacity: 1; visibility: visible; scale: 1; pointer-events: auto; transition: scale .42s cubic-bezier(.2, 0, 0, 1), opacity .22s ease; }
    .m3home-appview { position: absolute; inset: 0; display: flex; flex-direction: column; }
    .m3home-appbar { display: flex; align-items: center; gap: 7px; block-size: 62px; flex: none; padding-inline: 8px 18px; }
    .m3home-backbtn { inline-size: 42px; aspect-ratio: 1; flex: none; border: 0; border-radius: 50%; background: none; color: inherit; display: grid; place-items: center; cursor: pointer; }
    .m3home-backbtn:hover { background: color-mix(in oklab, var(--m3home-on-surface) 8%, transparent); }
    .m3home-backbtn svg { inline-size: 22px; block-size: 22px; }
    .m3home-apptitle { flex: 1; margin: 0; font-size: 19px; font-weight: 500; text-align: start; }
    .m3home-backhint { position: absolute; inset-block-start: 50%; inset-inline-start: 0; z-index: 55; inline-size: 5px; block-size: 64px; border-radius: 0 999px 999px 0; background: var(--m3home-accent); opacity: 0; pointer-events: none; }

    /* Music */
    .m3music { flex: 1; min-block-size: 0; display: flex; flex-direction: column; align-items: center; gap: 7px; padding: 2px 22px 16px; }
    .m3music-art { position: relative; inline-size: min(58%, 180px); aspect-ratio: 1; border-radius: 26px; overflow: clip; background: linear-gradient(140deg, color-mix(in oklab, var(--m3home-accent) 82%, #000) 0%, color-mix(in oklab, var(--m3home-accent) 38%, #160b2e) 100%); box-shadow: 0 18px 36px -16px #00000080; }
    .m3music-art i { position: absolute; inset: 30%; border-radius: 50%; border: 2px solid #ffffff2b; }
    .m3music-art i::after { content: ''; position: absolute; inset: 38%; border-radius: 50%; background: #ffffff30; }
    .m3music-title { margin: 6px 0 0; font-size: 17px; font-weight: 600; }
    .m3music-artist { font-size: 13px; color: var(--m3home-on-surface-var); }
    .m3music-bar { inline-size: 100%; block-size: 24px; display: flex; align-items: center; cursor: pointer; }
    .m3music-track { position: relative; inline-size: 100%; block-size: 4px; border-radius: 999px; background: color-mix(in oklab, var(--m3home-on-surface) 14%, transparent); }
    .m3music-fill { position: absolute; inset-block: 0; inset-inline-start: 0; border-radius: 999px; background: var(--m3home-accent); }
    .m3music-times { inline-size: 100%; display: flex; justify-content: space-between; font-size: 11.5px; color: var(--m3home-on-surface-var); }
    .m3music-ctl { display: flex; align-items: center; gap: 18px; margin-block: 4px 12px; }
    .m3music-skip { inline-size: 46px; aspect-ratio: 1; border: 0; border-radius: 50%; background: none; color: inherit; display: grid; place-items: center; cursor: pointer; }
    .m3music-skip:hover { background: color-mix(in oklab, var(--m3home-on-surface) 8%, transparent); }
    .m3music-skip svg { inline-size: 25px; block-size: 25px; }
    .m3music-play { inline-size: 64px; aspect-ratio: 1; border: 0; border-radius: 50%; background: var(--m3home-accent); color: var(--m3home-on-accent); display: grid; place-items: center; cursor: pointer; box-shadow: 0 10px 22px -10px color-mix(in oklab, var(--m3home-accent) 80%, #000); transition: scale .15s; }
    .m3music-play:active { scale: .93; }
    .m3music-play svg { inline-size: 28px; block-size: 28px; }
    .m3music-mini { margin-block-start: auto; inline-size: 100%; display: flex; align-items: center; gap: 12px; padding: 9px 14px; border-radius: 18px; background: var(--m3home-surface-container); }
    .m3music-mini-art { inline-size: 34px; aspect-ratio: 1; flex: none; border-radius: 10px; background: linear-gradient(140deg, color-mix(in oklab, var(--m3home-accent) 82%, #000), color-mix(in oklab, var(--m3home-accent) 38%, #160b2e)); }
    .m3music-mini-body { flex: 1; min-inline-size: 0; display: grid; }
    .m3music-mini-body strong { font-size: 12.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .m3music-mini-body span { font-size: 11px; color: var(--m3home-on-surface-var); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .m3music-mini button { inline-size: 38px; aspect-ratio: 1; flex: none; border: 0; border-radius: 50%; background: none; color: var(--m3home-on-container); display: grid; place-items: center; cursor: pointer; }
    .m3music-mini button svg { inline-size: 20px; block-size: 20px; }

    /* Notes */
    .m3notes { flex: 1; min-block-size: 0; overflow-y: auto; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; align-content: start; padding: 2px 16px 92px; touch-action: pan-y; }
    .m3notes-card { border-radius: 18px; padding: 12px 13px; min-block-size: 100px; background: color-mix(in oklab, hsl(var(--h) 62% 55%) 20%, var(--m3home-surface-container)); }
    .m3notes-card strong { display: block; margin-block-end: 4px; font-size: 13.5px; }
    .m3notes-card p { margin: 0; font-size: 12px; line-height: 1.7; color: var(--m3home-on-surface-var); }
    .m3home-fab { position: absolute; inset-block-end: 20px; inset-inline-end: 18px; z-index: 5; display: inline-flex; align-items: center; gap: 10px; block-size: 54px; padding-inline: 20px 22px; border: 0; border-radius: 17px; background: var(--m3home-container); color: var(--m3home-on-container); font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; box-shadow: 0 8px 18px -8px #00000066; transition: scale .15s; }
    .m3home-fab:active { scale: .95; }
    .m3home-fab svg { inline-size: 21px; block-size: 21px; }
    .m3home-fab[data-icon] { inline-size: 54px; padding: 0; justify-content: center; }

    /* Generic placeholder app */
    .m3generic { flex: 1; min-block-size: 0; display: grid; gap: 14px; align-content: start; padding: 4px 16px 92px; }
    .m3generic-hero { block-size: 118px; border-radius: 20px; background: color-mix(in oklab, var(--m3home-on-surface) 8%, transparent) linear-gradient(100deg, transparent 32%, color-mix(in oklab, var(--m3home-on-surface) 7%, transparent) 50%, transparent 68%); background-size: 200% 100%, 200% 100%; animation: m3home-shimmer 1.6s linear infinite; }
    .m3generic-row { display: flex; align-items: center; gap: 12px; }
    .m3generic-row i { inline-size: 42px; aspect-ratio: 1; flex: none; border-radius: 50%; background: color-mix(in oklab, var(--m3home-on-surface) 9%, transparent); }
    .m3generic-row span { display: grid; gap: 6px; flex: 1; }
    .m3generic-row span::before, .m3generic-row span::after { content: ''; border-radius: 999px; background: color-mix(in oklab, var(--m3home-on-surface) 10%, transparent); }
    .m3generic-row span::before { block-size: 10px; inline-size: 68%; }
    .m3generic-row span::after { block-size: 8px; inline-size: 42%; }
    @keyframes m3home-shimmer { to { background-position: -200% 0, -200% 0; } }

    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3home-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3home-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column reads as cut off; let this page's table and snippet
       wrap so nothing is read as cut off. */
    @media (max-width: 480px) {
        /* Narrow viewports squeeze the frame: tighter pill spacing leaves the
           search label more room on its single line before it ellipsises. */
        .m3home-search { gap: 8px; padding-inline: 13px; }
        .pg:has(.m3home-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.m3home-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }

    @media (prefers-reduced-motion: reduce) {
        .m3home-root *, .m3home-root *::before, .m3home-root *::after { transition: none !important; animation: none !important; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A whole Android shell in one frame', 'یک شل کامل اندروید در یک قاب') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag down from the status bar and the shade rides your finger, pull up from the pill for the drawer, swipe between pages, and switch the wallpaper to watch the whole interface re-tint itself Material-You style. Escape or the top-bar arrow goes home.', 'از نوار وضعیت به پایین بکشید تا سایه با انگشتتان بیاید، از قرص پایین به بالا بکشید تا اپ‌دراور باز شود، بین صفحه‌ها سوایپ کنید و کاغذدیواری را عوض کنید تا کل رابط به سبک Material You رنگ عوض کند. Escape یا فلشِ نوار بالا به خانه برمی‌گردد.') }}
        </p>
    </div>

    <div
        class="m3home-root"
        :data-wp="wp"
        :class="{ 'm3home-settling': settling }"
        tabindex="0"
        role="group"
        aria-label="{{ $say('Android launcher demo', 'دموی لانچر اندروید') }}"
        x-data='{
            fa: {{ $fa ? "true" : "false" }},
            wp: "night", wpMenu: false, page: 0, dragX: 0, settling: false, suppress: false,
            shade: 0, drawer: 0, bright: 84, app: null, appOrigin: "50% 50%", backX: 0, pillY: 0,
            q: "", notif: null, playing: false, pos: 96, track: 0, timer: null, rtl: false, d: null,
            catalog: {{ json_encode($catalog) }},
            drawerAppsAll: {{ json_encode($drawerApps) }},
            tiles: {{ json_encode($tiles) }},
            tracks: {{ json_encode($tracks) }},
            notes: {{ json_encode($notes) }},
            noteTpl: {{ json_encode($noteTpl) }},
            hues: {{ json_encode($hues) }},
            playGlyph: {{ json_encode($ico("play")) }},
            pauseGlyph: {{ json_encode($ico("pause")) }},
            init() {
                this.rtl = getComputedStyle(this.$refs.frame).direction === "rtl";
                this._move = (e) => this.move(e);
                this._up = (e) => this.drop(e);
            },
            num(n) { return this.fa ? String(n).replace(/\d/g, (c) => "۰۱۲۳۴۵۶۷۸۹"[+c]) : String(n); },
            mmss(v) { v = Math.max(0, Math.floor(v)); return this.num(Math.floor(v / 60)) + ":" + this.num(String(v % 60).padStart(2, "0")); },
            get drawerApps() { const s = this.q.trim().toLowerCase(); return this.drawerAppsAll.filter((a) => !s || a.name.toLowerCase().includes(s) || a.latin.includes(s)); },
            get current() { return this.catalog.find((a) => a.id === this.app) || null; },
            get shift() {
                const el = this.$refs.pages;
                const w = el ? el.getBoundingClientRect().width : 320;
                const s = this.rtl ? 1 : -1;
                let along = this.page * w + s * this.dragX;
                if (along < 0) along *= 0.28;
                if (along > w) along = w + (along - w) * 0.28;
                return s * along;
            },
            get dim() { return ((100 - this.bright) / 100) * 0.5; },
            get pillStyle() { return "transform: translateX(-50%) translateY(" + (-this.pillY) + "px) scaleX(" + (1 + this.pillY / 46).toFixed(3) + ")"; },
            get backHintStyle() { const off = (this.backX * 0.5).toFixed(1); return "opacity: " + Math.min(1, this.backX / 42).toFixed(2) + "; transform: translateY(-50%) translateX(" + (this.rtl ? -off : off) + "px)"; },
            openApp(id, ev) {
                if (this.suppress) return;
                const f = this.$refs.frame.getBoundingClientRect();
                const r = ev.currentTarget.getBoundingClientRect();
                this.appOrigin = (((r.left + r.width / 2 - f.left) / f.width) * 100).toFixed(1) + "% " + (((r.top + r.height / 2 - f.top) / f.height) * 100).toFixed(1) + "%";
                this.settling = true;
                this.drawer = 0;
                this.app = id;
            },
            openDrawer() { this.settling = true; this.drawer = 1; this.$nextTick(() => { if (this.$refs.dsearch) this.$refs.dsearch.focus(); }); },
            goHome() { this.settling = true; this.app = null; this.notif = null; this.wpMenu = false; this.drawer = 0; this.shade = 0; this.pillY = 0; this.backX = 0; this.dragX = 0; },
            onEsc() { if (this.wpMenu) this.wpMenu = false; else this.goHome(); },
            togglePlay() {
                this.playing = !this.playing;
                window.clearInterval(this.timer);
                if (this.playing) this.timer = window.setInterval(() => {
                    if (this.pos + 1 >= this.tracks[this.track].dur) { this.track = (this.track + 1) % this.tracks.length; this.pos = 0; }
                    else this.pos += 1;
                }, 1000);
            },
            skip(n) { this.track = (this.track + n + this.tracks.length) % this.tracks.length; this.pos = 0; },
            seek(ev) {
                const r = ev.currentTarget.getBoundingClientRect();
                const f = this.rtl ? r.right - ev.clientX : ev.clientX - r.left;
                this.pos = Math.round(this.tracks[this.track].dur * Math.min(1, Math.max(0, f / r.width)));
            },
            addNote() { this.notes.unshift({ id: Date.now(), hue: this.hues[this.notes.length % this.hues.length], title: this.noteTpl[0], body: this.noteTpl[1] }); },
            grab(e) {
                if (this.suppress || (e.button !== undefined && e.button > 0)) return;
                const f = this.$refs.frame.getBoundingClientRect();
                const x = e.clientX - f.left, y = e.clientY - f.top;
                this.settling = false;
                if (this.shade > 0.005) {
                    const p = getComputedStyle(this.$refs.shade).translate.split(/[\s,]+/);
                    const v = parseFloat(p[1] !== undefined ? p[1] : p[0]);
                    if (!isNaN(v)) this.shade = Math.min(1, Math.max(0, 1 + v / f.height));
                }
                if (this.drawer > 0.005) {
                    const p = getComputedStyle(this.$refs.drawer).translate.split(/[\s,]+/);
                    const v = parseFloat(p[1] !== undefined ? p[1] : p[0]);
                    if (!isNaN(v)) this.drawer = Math.min(1, Math.max(0, 1 - v / (f.height * 0.85)));
                }
                const ctl = e.target.closest("input, button, a");
                let mode = null, pill = false, s0 = this.shade, r0 = this.drawer;
                if (this.app) {
                    const fromStart = this.rtl ? f.width - x : x;
                    if (fromStart <= 26 && !ctl) mode = "back";
                    else if (y >= f.height - 46) { mode = "homepill"; pill = true; }
                } else if (this.shade > 0.02) {
                    if (!ctl) mode = "shade";
                } else if (y <= 48) {
                    mode = "shade";
                } else if (this.drawer > 0.02) {
                    const top = f.height * 0.15;
                    if (y >= f.height - 42) { mode = "homepill"; pill = true; }
                    else if (!ctl && y >= top - 8 && y <= top + 46) mode = "drawer";
                } else if (y >= f.height - 150) {
                    mode = "drawer";
                    if (y >= f.height - 42) pill = true;
                } else {
                    mode = "pages";
                }
                if (!mode) return;
                this.d = { mode, pill, s0, r0, x0: e.clientX, y0: e.clientY, lx: e.clientX, ly: e.clientY, lt: performance.now(), vx: 0, vy: 0, moved: false, w: f.width, h: f.height };
                window.addEventListener("pointermove", this._move, { passive: false });
                window.addEventListener("pointerup", this._up);
                window.addEventListener("pointercancel", this._up);
            },
            move(e) {
                const d = this.d; if (!d) return;
                const dx = e.clientX - d.x0, dy = e.clientY - d.y0;
                if (!d.moved && Math.hypot(dx, dy) < 7) return;
                d.moved = true;
                const now = performance.now(), dt = Math.max(1, now - d.lt);
                d.vx = d.vx * 0.7 + ((e.clientX - d.lx) / dt) * 0.3;
                d.vy = d.vy * 0.7 + ((e.clientY - d.ly) / dt) * 0.3;
                d.lx = e.clientX; d.ly = e.clientY; d.lt = now;
                if (d.mode === "shade") {
                    this.shade = Math.min(1, Math.max(0, d.s0 + dy / (d.h * 0.7)));
                    e.preventDefault();
                } else if (d.mode === "drawer") {
                    this.drawer = Math.min(1, Math.max(0, d.r0 - dy / (d.h * 0.8)));
                    if (d.pill) this.pillY = Math.min(22, Math.max(0, -dy));
                    e.preventDefault();
                } else if (d.mode === "homepill") {
                    this.pillY = Math.min(22, Math.max(0, -dy));
                    e.preventDefault();
                } else if (d.mode === "pages") {
                    if (Math.abs(dx) >= Math.abs(dy) - 4) { this.dragX = dx; e.preventDefault(); }
                } else if (d.mode === "back") {
                    this.backX = Math.max(0, this.rtl ? -dx : dx);
                    if (this.backX > 8) e.preventDefault();
                }
            },
            drop() {
                const d = this.d; if (!d) return;
                this.d = null;
                window.removeEventListener("pointermove", this._move);
                window.removeEventListener("pointerup", this._up);
                window.removeEventListener("pointercancel", this._up);
                this.suppress = d.moved;
                window.setTimeout(() => { this.suppress = false; }, 0);
                this.settling = true;
                const dy = d.ly - d.y0;
                if (d.mode === "shade") {
                    const proj = this.shade + (d.vy * 130) / (d.h * 0.7);
                    this.shade = proj >= 0.42 ? 1 : 0;
                } else if (d.mode === "drawer") {
                    const proj = this.drawer + ((-d.vy) * 130) / (d.h * 0.8);
                    this.drawer = proj >= 0.35 ? 1 : 0;
                    this.pillY = 0;
                } else if (d.mode === "homepill") {
                    this.pillY = 0;
                    if (-dy > 26 || d.vy < -0.4) this.goHome();
                } else if (d.mode === "pages") {
                    const s = this.rtl ? 1 : -1;
                    const frac = (s * this.dragX) / d.w;
                    let t = this.page;
                    if (frac > 0.28 || (frac > 0.06 && s * d.vx > 0.45)) t += 1;
                    else if (frac < -0.28 || (frac < -0.06 && s * d.vx < -0.45)) t -= 1;
                    this.page = Math.min(1, Math.max(0, t));
                    this.dragX = 0;
                } else if (d.mode === "back") {
                    const bx = this.backX;
                    this.backX = 0;
                    if (bx > 56) this.goHome();
                }
            },
        }'
        x-on:pointerdown="grab($event)"
        x-on:keydown.escape="onEsc()"
        x-on:click.capture="if (suppress) { $event.stopPropagation(); $event.preventDefault(); suppress = false }">
        <div x-ref="frame" class="m3home-frame">
            <div class="m3home-wall" aria-hidden="true">
                <i data-wp="night"></i><i data-wp="forest"></i><i data-wp="dawn"></i>
            </div>
            <div class="m3home-dim" :style="{ opacity: dim }" aria-hidden="true"></div>
            <span class="m3home-cam" aria-hidden="true"></span>

            <div class="m3home-status" :style="{ opacity: 1 - shade * 0.9 }">
                <span class="m3home-time">{{ $say('9:41', '۹:۴۱') }}</span>
                <span class="m3home-sig" aria-hidden="true">
                    <svg viewBox="0 0 18 14" fill="none" stroke="currentColor" stroke-linecap="round"><path d="M2 12.5h.01"/><path d="M6.5 12.5V9"/><path d="M11 12.5V5.5"/><path d="M15.5 12.5V2"/></svg>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"><path d="M4 10a13 13 0 0 1 16 0"/><path d="M7.5 13.5a8 8 0 0 1 9 0"/><path d="M11 17a3 3 0 0 1 2 0"/><path d="M12 19.5h.01"/></svg>
                    <svg viewBox="0 0 28 14" fill="none" stroke="currentColor"><rect x="1" y="1.5" width="22" height="11" rx="3" stroke-width="1.5"/><rect x="3" y="3.5" width="15" height="7" rx="1.5" fill="currentColor" stroke="none"/><path d="M25.5 5v4" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
            </div>

            <div class="m3home-home">
                <button type="button" class="m3home-wpbtn" x-on:click="wpMenu = ! wpMenu" :aria-expanded="wpMenu.toString()" aria-label="{{ $say('Change wallpaper', 'تغییر کاغذدیواری') }}">{!! $ico('palette') !!}</button>
                <div class="m3home-wpmenu" x-show="wpMenu" x-on:click.outside="wpMenu = false" x-cloak role="menu" aria-label="{{ $say('Wallpapers', 'کاغذدیواری‌ها') }}">
                    @foreach ($walls as $wall)
                        <button type="button" class="m3home-wpitem" role="menuitemradio" :aria-pressed="(wp === '{{ $wall['id'] }}').toString()" x-on:click="wp = '{{ $wall['id'] }}'; wpMenu = false">
                            <i data-wp="{{ $wall['id'] }}" aria-hidden="true"></i>
                            <span>{{ $wall['name'] }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="m3home-pages" x-ref="pages">
                    <div class="m3home-track" :style="{ transform: 'translateX(' + shift + 'px)' }">
                        <div class="m3home-page">
                            <div class="m3home-glance">
                                <div class="m3home-glance-time">{{ $say('2:05 PM', '۱۴:۰۵') }}</div>
                                <div class="m3home-glance-date">{{ $say('Tuesday, 7 Oct', 'سه‌شنبه، ۱۷ مهر') }}</div>
                                <div class="m3home-glance-row">
                                    {!! $ico('weather') !!}
                                    <span>{{ $say('Istanbul 28° Sunny', 'استانبول ۲۸° آفتابی') }}</span>
                                </div>
                            </div>
                            <div class="m3home-grid">
                                @foreach ($page1 as $a)
                                    <button type="button" class="m3home-appbtn" x-on:click="openApp('{{ $a['id'] }}', $event)" aria-label="{{ $a['name'] }}">
                                        <span class="m3home-icon" style="--h: {{ $a['hue'] }}">{!! $a['glyph'] !!}</span>
                                        <span class="m3home-appname">{{ $a['name'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="m3home-page">
                            <div class="m3home-grid">
                                @foreach ($page2 as $a)
                                    <button type="button" class="m3home-appbtn" x-on:click="openApp('{{ $a['id'] }}', $event)" aria-label="{{ $a['name'] }}">
                                        <span class="m3home-icon" style="--h: {{ $a['hue'] }}">{!! $a['glyph'] !!}</span>
                                        <span class="m3home-appname">{{ $a['name'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m3home-dots">
                    <button type="button" class="m3home-dot" :aria-current="(page === 0).toString()" x-on:click="page = 0; settling = true" aria-label="{{ $say('Page 1', 'صفحهٔ ۱') }}"></button>
                    <button type="button" class="m3home-dot" :aria-current="(page === 1).toString()" x-on:click="page = 1; settling = true" aria-label="{{ $say('Page 2', 'صفحهٔ ۲') }}"></button>
                </div>

                <button type="button" class="m3home-search" x-on:click="openDrawer()">
                    {!! $ico('search') !!}
                    <span>{{ $say('Search apps and more', 'جست‌وجو در برنامه‌ها') }}</span>
                    {!! $ico('mic') !!}
                    <span class="m3home-lens">{!! $ico('lens') !!}</span>
                </button>

                <div class="m3home-dock">
                    @foreach ($dock as $a)
                        <button type="button" class="m3home-appbtn" x-on:click="openApp('{{ $a['id'] }}', $event)" aria-label="{{ $a['name'] }}">
                            <span class="m3home-icon" style="--h: {{ $a['hue'] }}">{!! $a['glyph'] !!}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="m3home-pillzone" aria-hidden="true">
                <span class="m3home-pill" :style="pillStyle"></span>
            </div>

            <div class="m3home-shade" x-ref="shade" x-show="shade > 0.001" x-cloak role="region" :aria-hidden="(shade < 0.5).toString()" :style="{ translate: '0 ' + (shade - 1) * 105 + '%' }" aria-label="{{ $say('Notifications and quick settings', 'اعلان‌ها و تنظیمات سریع') }}">
                <div class="m3home-shade-head">
                    <span>{{ $say('Tue, 7 Oct', 'سه‌شنبه ۱۷ مهر') }}</span>
                    <span>{{ $say('Battery 82%', 'باتری ۸۲٪') }}</span>
                </div>
                <div class="m3home-qs">
                    <template x-for="t in tiles" :key="t.id">
                        <button type="button" class="m3home-tile" :aria-pressed="t.on.toString()" x-on:click="t.on = ! t.on">
                            <span class="m3home-tile-glyph" x-html="t.glyph"></span>
                            <span x-text="t.name"></span>
                        </button>
                    </template>
                </div>
                <div class="m3home-bright">
                    {!! $ico('sun') !!}
                    <input class="m3home-range" type="range" min="10" max="100" x-model.number="bright" :style="{ '--m3home-fill': bright + '%' }" aria-label="{{ $say('Brightness', 'روشنایی') }}">
                </div>
                <div class="m3home-notifs">
                    <div class="m3home-notif" :class="{ 'is-open': notif === 'msg' }">
                        <button type="button" class="m3home-notif-head" x-on:click="notif = notif === 'msg' ? null : 'msg'" :aria-expanded="(notif === 'msg').toString()">
                            <span class="m3home-notif-ava">{!! $ico('message') !!}</span>
                            <span class="m3home-notif-titles">
                                <strong>{{ $say('Message from Sara', 'پیام از سارا') }}</strong>
                                <span>{{ $say('Is the meeting room booked?', 'سالن جلسات رزرو شد؟') }}</span>
                            </span>
                            <span class="m3home-notif-when">{{ $say('now', 'الان') }}</span>
                        </button>
                        <div class="m3home-notif-body">
                            <div>
                                <p>{{ $say('Ten oclock tomorrow, the big room — I can move it to Wednesday if you like.', 'فردا ساعت ده، سالن بزرگ — اگر بخواهی به چهارشنبه منتقلش می‌کنم.') }}</p>
                                <div class="m3home-notif-actions">
                                    <button type="button">{{ $say('Reply', 'پاسخ') }}</button>
                                    <button type="button" x-on:click="notif = null">{{ $say('Mark as read', 'خوانده شد') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m3home-notif" :class="{ 'is-open': notif === 'sys' }">
                        <button type="button" class="m3home-notif-head" x-on:click="notif = notif === 'sys' ? null : 'sys'" :aria-expanded="(notif === 'sys').toString()">
                            <span class="m3home-notif-ava">{!! $ico('download') !!}</span>
                            <span class="m3home-notif-titles">
                                <strong>{{ $say('System update', 'به‌روزرسانی سیستم') }}</strong>
                                <span>{{ $say('The new Material You build is ready.', 'نسخهٔ تازهٔ Material You آماده است.') }}</span>
                            </span>
                            <span class="m3home-notif-when">{{ $say('8:12', '۸:۱۲') }}</span>
                        </button>
                        <div class="m3home-notif-body">
                            <div>
                                <p>{{ $say('About 1.6 GB. Keep Wi-Fi on tonight and it installs while you sleep.', 'حدود ۱٫۶ گیگابایت. امشب وای‌فای را روشن نگه دارید تا در خواب نصب شود.') }}</p>
                                <div class="m3home-notif-actions">
                                    <button type="button">{{ $say('Install tonight', 'امشب نصب کن') }}</button>
                                    <button type="button" x-on:click="notif = null">{{ $say('Remind me', 'یادآوری کن') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="m3home-shade-tail" x-on:click="shade = 0; settling = true" aria-label="{{ $say('Close shade', 'بستن سایه') }}"></button>
            </div>

            <div class="m3home-drawer" x-ref="drawer" x-show="drawer > 0.001" x-cloak role="region" :aria-hidden="(drawer < 0.5).toString()" :style="{ translate: '0 ' + (1 - drawer) * 110 + '%' }" aria-label="{{ $say('All apps', 'همهٔ برنامه‌ها') }}">
                <span class="m3home-grip" aria-hidden="true"></span>
                <div class="m3home-dsearch">
                    <button type="button" class="m3home-dclose" x-on:click="drawer = 0; settling = true" aria-label="{{ $say('Close drawer', 'بستن دراور') }}">{!! $ico('arrow-left') !!}</button>
                    {!! $ico('search') !!}
                    <input type="search" x-ref="dsearch" x-model="q" placeholder="{{ $say('Search apps', 'جست‌وجوی برنامه‌ها') }}" aria-label="{{ $say('Search apps', 'جست‌وجوی برنامه‌ها') }}">
                </div>
                <div class="m3home-dgrid">
                    <template x-for="a in drawerApps" :key="a.id">
                        <button type="button" class="m3home-appbtn" x-on:click="openApp(a.id, $event)" :aria-label="a.name">
                            <span class="m3home-icon" :style="'--h: ' + a.hue" x-html="a.glyph"></span>
                            <span class="m3home-appname" x-text="a.name"></span>
                        </button>
                    </template>
                </div>
                <p class="m3home-dempty" role="status" x-show="drawerApps.length === 0" x-cloak>{{ $say('No app matches.', 'برنامه‌ای پیدا نشد.') }}</p>
            </div>

            <div class="m3home-app" :class="{ 'is-open': !! app }" :style="{ transformOrigin: appOrigin }">
                <div class="m3home-backhint" x-show="app" :style="backHintStyle" aria-hidden="true"></div>

                <template x-if="app === 'music'">
                    <div class="m3home-appview" role="region" aria-label="{{ $say('Music player', 'پخش‌کنندهٔ موسیقی') }}">
                        <div class="m3home-appbar">
                            <button type="button" class="m3home-backbtn" x-on:click="goHome()" aria-label="{{ $say('Back home', 'بازگشت به خانه') }}">{!! $ico('arrow-left') !!}</button>
                            <h4 class="m3home-apptitle">{{ $say('Music', 'موسیقی') }}</h4>
                        </div>
                        <div class="m3music">
                            <span class="m3music-art" aria-hidden="true"><i></i></span>
                            <strong class="m3music-title" x-text="tracks[track].title"></strong>
                            <span class="m3music-artist" x-text="tracks[track].artist"></span>
                            <div class="m3music-bar" x-on:click="seek($event)" role="slider" :aria-valuenow="Math.round(pos / tracks[track].dur * 100)" aria-valuemin="0" aria-valuemax="100" :aria-valuetext="mmss(pos)" tabindex="0" x-on:keydown.arrow-right.prevent="pos = Math.min(tracks[track].dur, pos + 5)" x-on:keydown.arrow-left.prevent="pos = Math.max(0, pos - 5)" :aria-label="'{{ $say('Seek', 'جابه‌جایی') }}'">
                                <span class="m3music-track">
                                    <span class="m3music-fill" :style="{ inlineSize: (pos / tracks[track].dur * 100) + '%' }"></span>
                                </span>
                            </div>
                            <div class="m3music-times">
                                <span x-text="mmss(pos)"></span>
                                <span x-text="mmss(tracks[track].dur)"></span>
                            </div>
                            <div class="m3music-ctl">
                                <button type="button" class="m3music-skip" x-on:click="skip(-1)" aria-label="{{ $say('Previous track', 'ترک قبلی') }}">{!! $ico('prev') !!}</button>
                                <button type="button" class="m3music-play" x-on:click="togglePlay()" :aria-label="playing ? '{{ $say('Pause', 'توقف') }}' : '{{ $say('Play', 'پخش') }}'" x-html="playing ? pauseGlyph : playGlyph"></button>
                                <button type="button" class="m3music-skip" x-on:click="skip(1)" aria-label="{{ $say('Next track', 'ترک بعدی') }}">{!! $ico('next') !!}</button>
                            </div>
                            <div class="m3music-mini">
                                <span class="m3music-mini-art" aria-hidden="true"></span>
                                <span class="m3music-mini-body">
                                    <strong x-text="tracks[track].title"></strong>
                                    <span x-text="tracks[track].artist"></span>
                                </span>
                                <button type="button" x-on:click="togglePlay()" :aria-label="playing ? '{{ $say('Pause', 'توقف') }}' : '{{ $say('Play', 'پخش') }}'" x-html="playing ? pauseGlyph : playGlyph"></button>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="app === 'notes'">
                    <div class="m3home-appview" role="region" aria-label="{{ $say('Notes', 'یادداشت‌ها') }}">
                        <div class="m3home-appbar">
                            <button type="button" class="m3home-backbtn" x-on:click="goHome()" aria-label="{{ $say('Back home', 'بازگشت به خانه') }}">{!! $ico('arrow-left') !!}</button>
                            <h4 class="m3home-apptitle">{{ $say('Notes', 'یادداشت‌ها') }}</h4>
                        </div>
                        <div class="m3notes">
                            <template x-for="n in notes" :key="n.id">
                                <article class="m3notes-card" :style="'--h: ' + n.hue">
                                    <strong x-text="n.title"></strong>
                                    <p x-text="n.body"></p>
                                </article>
                            </template>
                        </div>
                        <button type="button" class="m3home-fab" x-on:click="addNote()">
                            {!! $ico('plus') !!}
                            <span>{{ $say('New note', 'یادداشت جدید') }}</span>
                        </button>
                    </div>
                </template>

                <template x-if="app && app !== 'music' && app !== 'notes'">
                    <div class="m3home-appview" role="region" :aria-label="current ? current.name : ''">
                        <div class="m3home-appbar">
                            <button type="button" class="m3home-backbtn" x-on:click="goHome()" aria-label="{{ $say('Back home', 'بازگشت به خانه') }}">{!! $ico('arrow-left') !!}</button>
                            <h4 class="m3home-apptitle" x-text="current ? current.name : ''"></h4>
                            <span class="m3home-notif-ava" style="inline-size: 32px" aria-hidden="true" x-html="current ? current.glyph : ''"></span>
                        </div>
                        <div class="m3generic" aria-hidden="true">
                            <span class="m3generic-hero"></span>
                            <div class="m3generic-row"><i></i><span></span></div>
                            <div class="m3generic-row"><i></i><span></span></div>
                            <div class="m3generic-row"><i></i><span></span></div>
                            <div class="m3generic-row"><i></i><span></span></div>
                        </div>
                        <button type="button" class="m3home-fab" data-icon aria-label="{{ $say('Create', 'ساخت') }}">{!! $ico('plus') !!}</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>
