{{--
    The full Mac desktop — the group's capstone: a live menu bar (Apple and
    File menus with ⌘ shortcuts, Control Centre with a real brightness slider
    and Wi-Fi toggle, a ticking Persian clock) over a Sonoma-ish wallpaper,
    three working windows — Finder, Mail, Notes — that drag, stack by z-order,
    breathe with focus, minimise into the dock and zoom, plus a magnifying
    dock with launch bounce, running dots, hover bubbles and a wiggling trash.
    Pure inline Alpine; no NabuXUI components, no external JS.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $appNames = [
        'finder' => ['en' => 'Finder', 'fa' => 'یافتنر'],
        'mail' => ['en' => 'Mail', 'fa' => 'ایمیل'],
        'notes' => ['en' => 'Notes', 'fa' => 'یادداشت‌ها'],
    ];
    $appName = fn (string $id) => $fa ? $appNames[$id]['fa'] : $appNames[$id]['en'];
@endphp

<style>
    .mcdesk-root {
        --mcdesk-winbg: #ececec;
        --mcdesk-wintext: #1d1d1f;
        --mcdesk-muted: #7d7d80;
        --mcdesk-accent: #007aff;
        --mcdesk-hairline: rgba(0, 0, 0, .12);
        --mcdesk-ring: rgba(0, 0, 0, .22);
        --mcdesk-menubg: rgba(255, 255, 255, .55);
        --mcdesk-menutext: #1d1d1f;
        --mcdesk-panelbg: rgba(248, 248, 250, .86);
        --mcdesk-dockbg: rgba(255, 255, 255, .35);
        --mcdesk-rim: rgba(255, 255, 255, .62);
        --mcdesk-dot: rgba(15, 15, 20, .55);
        --mcdesk-sidebg: rgba(255, 255, 255, .5);
        --mcdesk-paper: #fff8c4;
        --mcdesk-papertext: #4a4210;
        --mcdesk-ico: 46px;
        --mcdesk-dockgap: 8px;
        position: relative;
        overflow: clip;
        block-size: clamp(26rem, 60vh, 34rem);
        border-radius: 12px;
        isolation: isolate;
        background: #070b18;
        font-size: 13px;
        line-height: 1.55;
    }
    html[data-theme='dark'] .mcdesk-root {
        --mcdesk-winbg: #282828;
        --mcdesk-wintext: #f2f2f5;
        --mcdesk-muted: #9d9da6;
        --mcdesk-accent: #0a84ff;
        --mcdesk-hairline: rgba(255, 255, 255, .14);
        --mcdesk-ring: rgba(0, 0, 0, .55);
        --mcdesk-menubg: rgba(28, 28, 30, .6);
        --mcdesk-menutext: #f2f2f5;
        --mcdesk-panelbg: rgba(44, 44, 46, .88);
        --mcdesk-dockbg: rgba(40, 40, 40, .45);
        --mcdesk-rim: rgba(255, 255, 255, .14);
        --mcdesk-dot: rgba(255, 255, 255, .78);
        --mcdesk-sidebg: rgba(255, 255, 255, .055);
        --mcdesk-paper: #3a3417;
        --mcdesk-papertext: #efe9c6;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme='light']) .mcdesk-root {
            --mcdesk-winbg: #282828;
            --mcdesk-wintext: #f2f2f5;
            --mcdesk-muted: #9d9da6;
            --mcdesk-accent: #0a84ff;
            --mcdesk-hairline: rgba(255, 255, 255, .14);
            --mcdesk-ring: rgba(0, 0, 0, .55);
            --mcdesk-menubg: rgba(28, 28, 30, .6);
            --mcdesk-menutext: #f2f2f5;
            --mcdesk-panelbg: rgba(44, 44, 46, .88);
            --mcdesk-dockbg: rgba(40, 40, 40, .45);
            --mcdesk-rim: rgba(255, 255, 255, .14);
            --mcdesk-dot: rgba(255, 255, 255, .78);
            --mcdesk-sidebg: rgba(255, 255, 255, .055);
            --mcdesk-paper: #3a3417;
            --mcdesk-papertext: #efe9c6;
        }
    }

    .mcdesk-root [x-cloak] { display: none !important; }
    .mcdesk-root :where(button) { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .mcdesk-root :where(textarea) { font: inherit; }
    .mcdesk-root :focus-visible { outline: 2px solid var(--mcdesk-accent); outline-offset: 2px; }

    .mcdesk-wall {
        position: absolute; inset: 0; z-index: 0;
        opacity: var(--mcdesk-bright, 1); transition: opacity .25s ease;
        background:
            radial-gradient(90% 70% at 20% 0%, rgba(138, 127, 240, .8) 0%, transparent 55%),
            radial-gradient(80% 60% at 88% 6%, rgba(79, 109, 245, .65) 0%, transparent 55%),
            radial-gradient(110% 90% at 78% 100%, rgba(42, 30, 110, .7) 0%, transparent 60%),
            radial-gradient(70% 60% at 8% 85%, rgba(18, 58, 122, .7) 0%, transparent 60%),
            linear-gradient(168deg, #35418f 0%, #1b2150 48%, #0a0e24 100%);
    }
    .mcdesk-grain {
        position: absolute; inset: 0; z-index: 1; pointer-events: none; opacity: .55; mix-blend-mode: overlay;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='g'><feTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='2'/><feColorMatrix type='saturate' values='0'/></filter><rect width='140' height='140' filter='url(%23g)' opacity='.14'/></svg>");
    }

    /* ---- menu bar ---- */
    .mcdesk-menubar {
        position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 400;
        display: flex; align-items: center; gap: 2px; block-size: 24px; padding-inline: 6px;
        background: var(--mcdesk-menubg); color: var(--mcdesk-menutext);
        backdrop-filter: blur(20px) saturate(1.8); -webkit-backdrop-filter: blur(20px) saturate(1.8);
        box-shadow: 0 1px 0 var(--mcdesk-hairline);
        font-size: 12px; user-select: none; -webkit-user-select: none;
    }
    .mcdesk-mbwrap { position: relative; display: inline-flex; }
    .mcdesk-mbitem { display: inline-flex; align-items: center; gap: 4px; block-size: 20px; padding-inline: 9px; border-radius: 5px; white-space: nowrap; }
    .mcdesk-mbitem:hover, .mcdesk-mbitem[data-open] { background: rgba(125, 125, 135, .28); }
    .mcdesk-mbapp { font-weight: 700; padding-inline: 9px; white-space: nowrap; }
    .mcdesk-mbstatus { margin-inline-start: auto; display: inline-flex; align-items: center; gap: 8px; }
    .mcdesk-mbico[data-off] { opacity: .35; }
    .mcdesk-mbclock { font-variant-numeric: tabular-nums; padding-inline-start: 4px; white-space: nowrap; }
    .mcdesk-menupop {
        position: absolute; inset-block-start: calc(100% + 6px); inset-inline-start: 0; z-index: 20;
        min-inline-size: 216px; padding: 5px; border-radius: 8px;
        background: var(--mcdesk-panelbg); color: var(--mcdesk-wintext);
        backdrop-filter: blur(30px) saturate(1.6); -webkit-backdrop-filter: blur(30px) saturate(1.6);
        box-shadow: 0 12px 40px rgba(0, 0, 0, .3), 0 0 0 .5px var(--mcdesk-hairline);
        animation: mcdesk-in .16s ease;
    }
    .mcdesk-ccpop { inset-inline-start: auto; inset-inline-end: 0; min-inline-size: 232px; padding: 10px 12px; display: grid; gap: 10px; }
    .mcdesk-mrow {
        display: grid; grid-template-columns: 16px 1fr auto; align-items: center; gap: 8px;
        inline-size: 100%; padding: 4px 8px; border-radius: 5px; text-align: start; white-space: nowrap;
    }
    .mcdesk-mrow:hover { background: var(--mcdesk-accent); color: #fff; }
    .mcdesk-mrow kbd { font: inherit; font-size: 12px; opacity: .6; }
    .mcdesk-mrow .mcdesk-tick { color: var(--mcdesk-accent); font-weight: 700; }
    .mcdesk-mrow:hover .mcdesk-tick, .mcdesk-mrow:hover kbd { color: #fff; opacity: 1; }
    .mcdesk-msep { block-size: 1px; margin-block: 4px; border: 0; background: var(--mcdesk-hairline); }
    .mcdesk-ccrow { display: flex; align-items: center; gap: 9px; font-size: 13px; }
    .mcdesk-ccrow input[type='range'] { flex: 1; accent-color: var(--mcdesk-accent); block-size: 4px; }
    .mcdesk-switch {
        position: relative; inline-size: 34px; block-size: 20px; border-radius: 999px; flex: none;
        background: rgba(125, 125, 135, .4); transition: background .2s ease;
    }
    .mcdesk-switch::after {
        content: ''; position: absolute; inset-block-start: 2px; inset-inline-start: 2px;
        inline-size: 16px; block-size: 16px; border-radius: 50%; background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .35); transition: translate .2s ease;
    }
    .mcdesk-switch[aria-pressed='true'] { background: #30d158; }
    .mcdesk-switch[aria-pressed='true']::after { translate: 14px 0; }

    /* ---- windows ---- */
    .mcdesk-win {
        position: absolute; display: flex; flex-direction: column;
        inline-size: min(100%, 24rem); border-radius: 10px; overflow: clip;
        background: var(--mcdesk-winbg); color: var(--mcdesk-wintext);
        box-shadow: 0 10px 30px rgba(0, 0, 0, .22), 0 0 0 .5px var(--mcdesk-ring);
        transition: box-shadow .35s ease, transform .3s cubic-bezier(.4, 0, .2, 1), opacity .25s ease, filter .3s ease;
    }
    .mcdesk-win:not([data-dragging]) {
        transition: box-shadow .35s ease, transform .3s cubic-bezier(.4, 0, .2, 1), opacity .25s ease, filter .3s ease,
            left .28s ease, top .28s ease, inline-size .28s ease, block-size .28s ease;
    }
    .mcdesk-win[data-active] { box-shadow: 0 22px 70px 4px rgba(0, 0, 0, .25), 0 0 0 .5px var(--mcdesk-ring); }
    .mcdesk-win:not([data-active]) { filter: saturate(.88); }
    .mcdesk-win[data-anim='min'] { transform: translate(var(--mx, 0), var(--my, 0)) scale(.05); opacity: 0; }
    .mcdesk-win[data-anim='open'], .mcdesk-win[data-anim='restore'] { animation: mcdesk-pop .32s cubic-bezier(.2, .9, .3, 1); }
    .mcdesk-bar {
        position: relative; display: flex; align-items: center; gap: 10px; flex: none;
        block-size: 52px; padding-inline: 12px; border-block-end: 1px solid var(--mcdesk-hairline);
        background: linear-gradient(to bottom, rgba(255, 255, 255, .05), transparent);
        touch-action: none; user-select: none; -webkit-user-select: none; cursor: grab;
    }
    .mcdesk-bar:active { cursor: grabbing; }
    .mcdesk-lights { display: flex; gap: 8px; flex: none; }
    .mcdesk-light { position: relative; inline-size: 12px; aspect-ratio: 1; border-radius: 50%; flex: none; box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .2); }
    .mcdesk-light[data-close] { background: #ff5f57; }
    .mcdesk-light[data-min] { background: #febc2e; }
    .mcdesk-light[data-zoom] { background: #28c840; }
    .mcdesk-light i {
        position: absolute; inset: 0; display: grid; place-items: center;
        font-style: normal; font-size: 9.5px; line-height: 1; font-weight: 700;
        color: rgba(0, 0, 0, .55); opacity: 0; transition: opacity .12s ease;
    }
    .mcdesk-light i svg { display: block; }
    .mcdesk-lights:hover .mcdesk-light i, .mcdesk-lights:focus-within .mcdesk-light i { opacity: 1; }
    .mcdesk-wtitle {
        position: absolute; left: 50%; translate: -50% 0; pointer-events: none;
        font-size: 13px; font-weight: 600; white-space: nowrap;
        opacity: .55; transition: opacity .3s ease;
    }
    .mcdesk-win[data-active] .mcdesk-wtitle { opacity: 1; }
    .mcdesk-tools { margin-inline-start: auto; display: flex; gap: 4px; }
    .mcdesk-tool {
        display: grid; place-items: center; inline-size: 26px; block-size: 24px; border-radius: 5px;
        color: var(--mcdesk-muted); font-size: 13px; opacity: .55; transition: opacity .3s ease;
    }
    .mcdesk-win[data-active] .mcdesk-tool { opacity: 1; }
    .mcdesk-tool:hover { background: rgba(125, 125, 135, .18); color: var(--mcdesk-wintext); }
    .mcdesk-body { flex: 1 1 auto; min-block-size: 0; overflow: auto; }
    .mcdesk-finder, .mcdesk-mail { display: flex; }
    .mcdesk-finder { block-size: 16.5rem; }
    .mcdesk-mail { block-size: 15.5rem; }
    .mcdesk-note {
        display: flex; flex-direction: column; gap: 6px; block-size: 12rem;
        padding: 10px 14px 14px; background: var(--mcdesk-paper); color: var(--mcdesk-papertext);
    }

    /* finder */
    .mcdesk-side { inline-size: 110px; flex: none; padding: 8px 6px; background: var(--mcdesk-sidebg); border-inline-end: 1px solid var(--mcdesk-hairline); }
    .mcdesk-side h5 { margin: 2px 8px 6px; font-size: 10.5px; font-weight: 700; color: var(--mcdesk-muted); }
    .mcdesk-srow {
        display: flex; align-items: center; gap: 6px; inline-size: 100%;
        padding: 4px 8px; border-radius: 5px; font-size: 12px; text-align: start; white-space: nowrap;
    }
    .mcdesk-srow i { inline-size: 10px; aspect-ratio: 1; border-radius: 3px; flex: none; }
    .mcdesk-srow > span { min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; }
    .mcdesk-srow:hover { background: rgba(125, 125, 135, .16); }
    .mcdesk-srow[data-current] { background: var(--mcdesk-accent); color: #fff; }
    .mcdesk-grid { flex: 1; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px 8px; align-content: start; padding: 10px; }
    .mcdesk-thumb { display: grid; gap: 4px; justify-items: center; font-size: 10.5px; color: var(--mcdesk-muted); min-inline-size: 0; }
    .mcdesk-thumb > span { max-inline-size: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .mcdesk-thumb b {
        inline-size: 100%; aspect-ratio: 4 / 3; border-radius: 6px;
        box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .12), 0 1px 3px rgba(0, 0, 0, .18);
    }

    /* mail */
    .mcdesk-mlist { inline-size: 134px; flex: none; overflow: auto; background: var(--mcdesk-sidebg); border-inline-end: 1px solid var(--mcdesk-hairline); }
    .mcdesk-msgrow { display: grid; gap: 1px; inline-size: 100%; padding: 7px 9px; text-align: start; border-block-end: 1px solid var(--mcdesk-hairline); }
    .mcdesk-msgrow b { display: flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 600; min-inline-size: 0; }
    .mcdesk-msgrow b .mcdesk-msgname { min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .mcdesk-msgrow b time { margin-inline-start: auto; font-size: 9.5px; font-weight: 400; color: var(--mcdesk-muted); flex: none; }
    .mcdesk-msgrow span { font-size: 10.5px; color: var(--mcdesk-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .mcdesk-msgrow[data-current] { background: color-mix(in oklab, var(--mcdesk-accent) 16%, transparent); }
    .mcdesk-msgrow[data-current] b .mcdesk-unread { background: var(--mcdesk-accent); }
    .mcdesk-unread { inline-size: 6px; aspect-ratio: 1; border-radius: 50%; background: var(--mcdesk-accent); flex: none; }
    .mcdesk-mread { flex: 1; display: grid; gap: 8px; align-content: start; padding: 12px 14px; overflow: auto; }
    .mcdesk-mread h4 { margin: 0; font-size: 13.5px; }
    .mcdesk-mmeta { margin: 0; font-size: 10.5px; color: var(--mcdesk-muted); }
    .mcdesk-mread p { margin: 0; font-size: 12px; line-height: 1.9; }

    /* notes */
    .mcdesk-notehead { display: flex; justify-content: space-between; margin: 0; font-size: 10.5px; opacity: .65; }
    .mcdesk-notearea {
        flex: 1; resize: none; border: 0; padding: 0; background: transparent;
        color: inherit; font-size: 12.5px; line-height: 2; outline: none; min-block-size: 0;
    }

    /* ---- dock ---- */
    .mcdesk-dock {
        position: absolute; inset-block-end: 10px; left: 50%; translate: -50% 0; z-index: 300;
        display: flex; align-items: flex-end; gap: var(--mcdesk-dockgap); padding: 6px 8px;
        border-radius: 20px; background: var(--mcdesk-dockbg);
        backdrop-filter: blur(24px) saturate(1.6); -webkit-backdrop-filter: blur(24px) saturate(1.6);
        box-shadow: inset 0 0 0 .5px var(--mcdesk-rim), 0 10px 30px rgba(0, 0, 0, .3);
        touch-action: none; user-select: none; -webkit-user-select: none;
    }
    .mcdesk-dockitem { position: relative; display: grid; justify-items: center; gap: 3px; }
    .mcdesk-dockicon {
        position: relative; display: grid; place-items: center;
        inline-size: calc(var(--mcdesk-ico) * var(--mag, 1)); aspect-ratio: 1; border-radius: 22%;
        transition: inline-size .13s ease-out;
    }
    .mcdesk-dockicon > svg { inline-size: 62%; block-size: 62%; }
    .mcdesk-trash { background: none !important; }
    .mcdesk-trash > svg { inline-size: 78%; block-size: 78%; }
    .mcdesk-dockicon[data-bouncing] { animation: mcdesk-bounce .24s ease-in-out 2; }
    .mcdesk-trash[data-wiggle] { animation: mcdesk-wiggle .55s ease-in-out; transform-origin: 50% 88%; }
    .mcdesk-dot { inline-size: 4px; aspect-ratio: 1; border-radius: 50%; background: var(--mcdesk-dot); opacity: 0; scale: .4; transition: opacity .2s ease, scale .2s ease; }
    .mcdesk-dot[data-on] { opacity: 1; scale: 1; }
    .mcdesk-docksep { inline-size: 1px; align-self: stretch; margin-block: 8px 7px; background: var(--mcdesk-rim); }
    .mcdesk-tip {
        position: absolute; bottom: calc(100% + 9px); left: 50%; translate: -50% 4px; z-index: 5;
        padding: 3px 9px; border-radius: 6px; font-size: 11px; white-space: nowrap;
        background: var(--mcdesk-panelbg); color: var(--mcdesk-wintext);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, .25), 0 0 0 .5px var(--mcdesk-hairline);
        opacity: 0; pointer-events: none;
        transition: opacity .15s ease 0s, translate .15s ease 0s;
    }
    .mcdesk-dockicon:hover .mcdesk-tip, .mcdesk-dockicon:focus-visible .mcdesk-tip { opacity: 1; translate: -50% 0; transition-delay: .45s; }

    @keyframes mcdesk-in { from { opacity: 0; scale: .98; } }
    @keyframes mcdesk-pop { from { transform: scale(.68); opacity: 0; } }
    @keyframes mcdesk-bounce { 0%, 100% { translate: 0 0; } 45% { translate: 0 -17px; } }
    @keyframes mcdesk-wiggle { 0%, 100% { rotate: 0deg; } 25% { rotate: 9deg; } 55% { rotate: -8deg; } 80% { rotate: 4deg; } }

    /* The props table's rows reveal on scroll. In static renders (screenshot,
       print, a load without scrolling) the observer never fires and the body
       stays invisible under the header — keep this page's rows visible. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }

    @media (max-width: 480px) {
        /* Let the scene breathe into this card's padding instead of the
           contents poking past its edges. */
        .mcdesk-root { --mcdesk-ico: clamp(22px, 7vw, 30px); --mcdesk-dockgap: 5px; margin-inline: -.75rem; }
        .mcdesk-menubar { font-size: 10.5px; padding-inline: 3px; }
        .mcdesk-mbitem { padding-inline: 5px; }
        .mcdesk-mbapp { padding-inline: 6px; min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; }
        .mcdesk-mbstatus { gap: 6px; }
        .mcdesk-mbclock { padding-inline-start: 2px; }
        .mcdesk-mbedit { display: none; }
        .mcdesk-side { inline-size: 84px; }
        .mcdesk-mlist { inline-size: 108px; }
        .mcdesk-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .mcdesk-dock { max-inline-size: calc(100% - 12px); }
        /* The page's own furniture: wrap table cells and code so nothing is
           clipped at the card edge on a phone. */
        .nx-data-table th, .nx-data-table td { white-space: normal; }
        .pg-box pre { white-space: pre-wrap; overflow-wrap: anywhere; }
    }

    @media (max-width: 360px) {
        .mcdesk-mbbatt { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .mcdesk-dockicon { transition: scale .15s ease; }
        .mcdesk-dockicon:hover, .mcdesk-dockicon:focus-visible { scale: 1.07; }
        .mcdesk-dockicon[data-bouncing], .mcdesk-trash[data-wiggle] { animation: none; }
        .mcdesk-win { transition: opacity .2s ease, box-shadow .2s ease; }
        .mcdesk-win:not([data-dragging]) { transition: opacity .2s ease, box-shadow .2s ease; }
        .mcdesk-win[data-anim='open'], .mcdesk-win[data-anim='restore'] { animation: none; }
        .mcdesk-menupop { animation: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The full Mac desktop', 'میزکار کامل مک') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag the windows by their toolbars, sweep the dock to magnify it, minimise a window into its icon, and dim the wallpaper from Control Centre — the clock is genuinely ticking.', 'پنجره‌ها را از نوار ابزارشان بکشید، داک را زیر اشاره‌گر بگردید تا بزرگ شود، یک پنجره را کوچک‌به‌داک بفرستید و از مرکز کنترل نورِ کاغذدیواری را کم کنید — ساعت هم واقعاً تیک می‌زند.') }}
        </p>
    </div>

    <div class="mcdesk-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            zTop: 12, active: 'finder', menu: null, cc: false, bright: 100, wifi: true,
            clock: '', trashWiggle: false, drag: null, _icons: null,
            still: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            names: { finder: '{{ $appName('finder') }}', mail: '{{ $appName('mail') }}', notes: '{{ $appName('notes') }}' },
            apps: {
                finder: { open: true, min: false, zoom: false, anim: null, bounce: false, x: 20, y: 34, mx: 0, my: 0, z: 12 },
                mail: { open: true, min: false, zoom: false, anim: null, bounce: false, x: 120, y: 110, mx: 0, my: 0, z: 11 },
                notes: { open: false, min: false, zoom: false, anim: null, bounce: false, x: 220, y: 26, mx: 0, my: 0, z: 10 },
                safari: { open: false, stub: true, bounce: false },
                music: { open: false, stub: true, bounce: false },
                calendar: { open: false, stub: true, bounce: false },
            },
            init() {
                this._icons = this.$refs.dock ? this.$refs.dock.querySelectorAll('.mcdesk-dockicon') : [];
                const sw = this.$el.clientWidth, sh = this.$el.clientHeight, ww = Math.min(sw, 384);
                const fx = (f) => Math.max(0, Math.min(sw * f, sw - ww - 8));
                this.apps.finder.x = fx(.03); this.apps.finder.y = 34;
                this.apps.mail.x = fx(.3); this.apps.mail.y = Math.max(34, Math.min(120, sh - 320));
                this.apps.notes.x = fx(.55); this.apps.notes.y = 26;
                this.tick();
                this._clockTimer = setInterval(() => this.tick(), 30000);
            },
            tick() {
                const d = new Date();
                if (this.fa) {
                    // The weekday only fits on a wide scene; on a narrow card the bare clock stays legible.
                    const hm = new Intl.DateTimeFormat('fa-IR', { hour: '2-digit', minute: '2-digit', hour12: false }).format(d);
                    this.clock = this.$el.clientWidth >= 420
                        ? new Intl.DateTimeFormat('fa-IR', { weekday: 'long' }).format(d) + '، ' + hm
                        : hm;
                } else {
                    this.clock = new Intl.DateTimeFormat('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false }).format(d);
                }
            },
            focus(id) {
                const st = this.apps[id];
                if (! st || ! st.open || st.stub) return;
                this.active = id; st.z = ++this.zTop;
            },
            topOpen(except) {
                let best = null, bz = -1;
                for (const [id, st] of Object.entries(this.apps)) {
                    if (st.open && ! st.min && ! st.stub && id !== except && st.z > bz) { bz = st.z; best = id; }
                }
                return best;
            },
            closeWin(id) {
                const st = this.apps[id];
                st.open = false; st.min = false; st.zoom = false; st.anim = null;
                if (this.active === id) this.active = this.topOpen();
                this.wiggleTrash();
            },
            wiggleTrash() {
                this.trashWiggle = true;
                setTimeout(() => this.trashWiggle = false, 650);
            },
            minWin(id) {
                const st = this.apps[id], win = this.$refs['w-' + id];
                if (win && this.$refs['d-' + id]) {
                    const wr = win.getBoundingClientRect(), ir = this.$refs['d-' + id].getBoundingClientRect();
                    st.mx = Math.round(ir.left + ir.width / 2 - wr.left - wr.width / 2);
                    st.my = Math.round(ir.top + ir.height / 2 - wr.top - wr.height / 2);
                }
                st.anim = 'min';
                setTimeout(() => { st.min = true; st.anim = null; if (this.active === id) this.active = this.topOpen(); }, 300);
            },
            restore(id) {
                const st = this.apps[id];
                st.min = false; this.focus(id);
                st.anim = 'restore';
                setTimeout(() => st.anim = null, 340);
            },
            launch(id) {
                const st = this.apps[id];
                if (st.stub) {
                    st.bounce = true;
                    setTimeout(() => { st.bounce = false; st.open = ! st.open; }, 500);
                    return;
                }
                if (st.min) { this.restore(id); return; }
                if (st.open) { this.focus(id); return; }
                st.bounce = true;
                setTimeout(() => {
                    st.bounce = false;
                    st.open = true; st.min = false;
                    st.anim = 'open';
                    setTimeout(() => st.anim = null, 340);
                    this.focus(id);
                }, 480);
            },
            zoomWin(id) {
                const st = this.apps[id];
                st.zoom = ! st.zoom; this.focus(id);
            },
            winStyle(id) {
                const st = this.apps[id];
                const pos = st.zoom
                    ? 'left:8px;top:32px;inline-size:calc(100% - 16px);block-size:calc(100% - 7.75rem);'
                    : 'left:' + st.x + 'px;top:' + st.y + 'px;';
                return pos + '--mx:' + (st.mx || 0) + 'px;--my:' + (st.my || 0) + 'px;z-index:' + st.z;
            },
            startDrag(e, id) {
                if (e.target.closest('button, input, textarea, a')) return;
                this.focus(id);
                const win = e.currentTarget.closest('.mcdesk-win');
                const stage = this.$el.getBoundingClientRect();
                const wr = win.getBoundingClientRect();
                this.drag = { id: id, dx: e.clientX - wr.left, dy: e.clientY - wr.top, sx: stage.left, sy: stage.top, ww: wr.width, sw: stage.width, sh: stage.height };
                e.currentTarget.setPointerCapture(e.pointerId);
                e.preventDefault();
            },
            moveDrag(e) {
                const d = this.drag;
                if (! d) return;
                const st = this.apps[d.id];
                st.x = Math.min(Math.max(e.clientX - d.sx - d.dx, 60 - d.ww), d.sw - 60);
                st.y = Math.min(Math.max(e.clientY - d.sy - d.dy, 26), d.sh - 46);
            },
            stopDrag(e) {
                if (! this.drag) return;
                try { e.currentTarget.releasePointerCapture(e.pointerId); } catch (err) {}
                this.drag = null;
            },
            dockMag(e) {
                if (this.still || ! this._icons) return;
                const max = window.matchMedia('(max-width: 480px)').matches ? 1.22 : 1.5;
                this._icons.forEach((ic) => {
                    const r = ic.getBoundingClientRect();
                    const dd = Math.abs(e.clientX - (r.left + r.width / 2));
                    ic.style.setProperty('--mag', (1 + (max - 1) * Math.exp(- (dd * dd) / 4900)).toFixed(3));
                });
            },
            dockReset() {
                if (! this._icons) return;
                this._icons.forEach((ic) => ic.style.removeProperty('--mag'));
            },
        }"
        x-init="init()"
        :style="'--mcdesk-bright:' + (bright / 100)"
        x-on:pointerdown.capture="if (! $event.target.closest('.mcdesk-menubar')) { menu = null; cc = false }"
        x-on:keydown.escape.window="menu = null; cc = false">

        <div class="mcdesk-wall" aria-hidden="true"></div>
        <div class="mcdesk-grain" aria-hidden="true"></div>

        {{-- Finder --}}
        <article class="mcdesk-win" x-show="apps.finder.open && ! apps.finder.min" x-ref="w-finder"
            :style="winStyle('finder')"
            :data-active="active === 'finder' ? '' : null"
            :data-anim="apps.finder.anim"
            :data-zoom="apps.finder.zoom ? '' : null"
            :data-dragging="drag && drag.id === 'finder' ? '' : null"
            x-on:pointerdown="focus('finder')"
            aria-label="{{ $say('Finder window', 'پنجرهٔ یافتنر') }}">
            <header class="mcdesk-bar"
                x-on:pointerdown="startDrag($event, 'finder')"
                x-on:pointermove="moveDrag($event)"
                x-on:pointerup="stopDrag($event)"
                x-on:pointercancel="stopDrag($event)">
                <div class="mcdesk-lights">
                    <button type="button" class="mcdesk-light" data-close x-on:click="closeWin('finder')" aria-label="{{ $say('Close Finder', 'بستن یافتنر') }}"><i aria-hidden="true">×</i></button>
                    <button type="button" class="mcdesk-light" data-min x-on:click="minWin('finder')" aria-label="{{ $say('Minimise Finder', 'کوچک‌کردن یافتنر') }}"><i aria-hidden="true">−</i></button>
                    <button type="button" class="mcdesk-light" data-zoom x-on:click="zoomWin('finder')" aria-label="{{ $say('Zoom Finder', 'بزرگ‌نمایی پنجرهٔ یافتنر') }}"><i aria-hidden="true"><svg viewBox="0 0 10 10" width="9" height="9" fill="none" stroke="rgba(0,0,0,.55)" stroke-width="1.4" stroke-linecap="round"><path d="M6.2 1.2h2.6v2.6M8.8 1.2 5.6 4.4M3.8 8.8H1.2V6.2M1.2 8.8l3.2-3.2"/></svg></i></button>
                </div>
                <span class="mcdesk-wtitle">{{ $appName('finder') }}</span>
                <div class="mcdesk-tools">
                    <button type="button" class="mcdesk-tool" aria-label="{{ $say('View as icons', 'نمای آیکنی') }}" x-on:click.stop>▦</button>
                    <button type="button" class="mcdesk-tool" aria-label="{{ $say('View as list', 'نمای فهرستی') }}" x-on:click.stop>☰</button>
                </div>
            </header>
            <div class="mcdesk-body mcdesk-finder">
                <aside class="mcdesk-side">
                    <h5>{{ $say('Favourites', 'موردعلاقه‌ها') }}</h5>
                    <button type="button" class="mcdesk-srow" data-current><i style="background: linear-gradient(135deg, #4facfe, #00c6fb)" aria-hidden="true"></i><span>{{ $say('Recents', 'آخرین‌ها') }}</span></button>
                    <button type="button" class="mcdesk-srow"><i style="background: linear-gradient(135deg, #a78bfa, #7c3aed)" aria-hidden="true"></i><span>{{ $say('Applications', 'برنامه‌ها') }}</span></button>
                    <button type="button" class="mcdesk-srow"><i style="background: linear-gradient(135deg, #34d399, #059669)" aria-hidden="true"></i><span>{{ $say('Desktop', 'میزکار') }}</span></button>
                    <button type="button" class="mcdesk-srow"><i style="background: linear-gradient(135deg, #fbbf24, #d97706)" aria-hidden="true"></i><span>{{ $say('Documents', 'اسناد') }}</span></button>
                </aside>
                <div class="mcdesk-grid">
                    <button type="button" class="mcdesk-thumb"><b style="background: linear-gradient(135deg, #22d3ee, #6366f1)" aria-hidden="true"></b><span>{{ $say('Designs', 'طرح‌ها') }}</span></button>
                    <button type="button" class="mcdesk-thumb"><b style="background: linear-gradient(135deg, #a78bfa, #ec4899)" aria-hidden="true"></b><span>{{ $say('Screenshots', 'نماگرفت‌ها') }}</span></button>
                    <button type="button" class="mcdesk-thumb"><b style="background: linear-gradient(135deg, #fbbf24, #f97316)" aria-hidden="true"></b><span>{{ $say('Budget', 'بودجه') }}</span></button>
                    <button type="button" class="mcdesk-thumb"><b style="background: linear-gradient(135deg, #34d399, #0ea5e9)" aria-hidden="true"></b><span>{{ $say('Report', 'گزارش') }}</span></button>
                    <button type="button" class="mcdesk-thumb"><b style="background: linear-gradient(135deg, #60a5fa, #4338ca)" aria-hidden="true"></b><span>{{ $say('Photos', 'عکس‌ها') }}</span></button>
                    <button type="button" class="mcdesk-thumb"><b style="background: linear-gradient(135deg, #fb7185, #f43f5e)" aria-hidden="true"></b><span>{{ $say('Templates', 'قالب‌ها') }}</span></button>
                </div>
            </div>
        </article>

        {{-- Mail --}}
        <article class="mcdesk-win" x-show="apps.mail.open && ! apps.mail.min" x-ref="w-mail"
            :style="winStyle('mail')"
            :data-active="active === 'mail' ? '' : null"
            :data-anim="apps.mail.anim"
            :data-zoom="apps.mail.zoom ? '' : null"
            :data-dragging="drag && drag.id === 'mail' ? '' : null"
            x-on:pointerdown="focus('mail')"
            aria-label="{{ $say('Mail window', 'پنجرهٔ ایمیل') }}">
            <header class="mcdesk-bar"
                x-on:pointerdown="startDrag($event, 'mail')"
                x-on:pointermove="moveDrag($event)"
                x-on:pointerup="stopDrag($event)"
                x-on:pointercancel="stopDrag($event)">
                <div class="mcdesk-lights">
                    <button type="button" class="mcdesk-light" data-close x-on:click="closeWin('mail')" aria-label="{{ $say('Close Mail', 'بستن ایمیل') }}"><i aria-hidden="true">×</i></button>
                    <button type="button" class="mcdesk-light" data-min x-on:click="minWin('mail')" aria-label="{{ $say('Minimise Mail', 'کوچک‌کردن ایمیل') }}"><i aria-hidden="true">−</i></button>
                    <button type="button" class="mcdesk-light" data-zoom x-on:click="zoomWin('mail')" aria-label="{{ $say('Zoom Mail', 'بزرگ‌نمایی پنجرهٔ ایمیل') }}"><i aria-hidden="true"><svg viewBox="0 0 10 10" width="9" height="9" fill="none" stroke="rgba(0,0,0,.55)" stroke-width="1.4" stroke-linecap="round"><path d="M6.2 1.2h2.6v2.6M8.8 1.2 5.6 4.4M3.8 8.8H1.2V6.2M1.2 8.8l3.2-3.2"/></svg></i></button>
                </div>
                <span class="mcdesk-wtitle">{{ $appName('mail') }}</span>
                <div class="mcdesk-tools">
                    <button type="button" class="mcdesk-tool" aria-label="{{ $say('New message', 'پیام تازه') }}" x-on:click.stop>✎</button>
                </div>
            </header>
            <div class="mcdesk-body mcdesk-mail">
                <div class="mcdesk-mlist">
                    <button type="button" class="mcdesk-msgrow" data-current>
                        <b><i class="mcdesk-unread" aria-hidden="true"></i><span class="mcdesk-msgname">{{ $say('Sara Ahmadi', 'سارا احمدی') }}</span><time>{{ $fa ? '۱۴:۰۲' : '14:02' }}</time></b>
                        <span>{{ $say('Sidebar design review', 'بازبینی طراحی نوار کنار') }}</span>
                        <span>{{ $say('I went through the final files…', 'فایل‌های نهایی را دیدم…') }}</span>
                    </button>
                    <button type="button" class="mcdesk-msgrow">
                        <b><span class="mcdesk-msgname">{{ $say('Reza Karimi', 'رضا کریمی') }}</span><time>{{ $fa ? '۱۲:۴۵' : '12:45' }}</time></b>
                        <span>{{ $say('Tomorrow’s meeting', 'قرار جلسهٔ فردا') }}</span>
                        <span>{{ $say('Shall we move it to 10?', 'ببریمش روی ۱۰ صبح؟') }}</span>
                    </button>
                    <button type="button" class="mcdesk-msgrow">
                        <b><span class="mcdesk-msgname">{{ $say('Mahsa Nouri', 'مهسا نوری') }}</span><time>{{ $say('Yesterday', 'دیروز') }}</time></b>
                        <span>{{ $say('Sprint summary', 'خلاصهٔ اسپرینت') }}</span>
                        <span>{{ $say('Eleven issues closed…', 'یازده مسئله بسته شد…') }}</span>
                    </button>
                </div>
                <div class="mcdesk-mread">
                    <h4>{{ $say('Sidebar design review', 'بازبینی طراحی نوار کنار') }}</h4>
                    <p class="mcdesk-mmeta">{{ $say('Sara Ahmadi · today 14:02 · to me', 'سارا احمدی · امروز ۱۴:۰۲ · به من') }}</p>
                    <p>{{ $say('Hi! I went through the final sidebar files. The blue selection reads far better than the last round, and the row spacing finally feels right. Could you set the counter beside Recents in the lighter weight before the review? Thanks!', 'سلام! فایل‌های نهایی نوار کنار را دیدم. رنگ آبیِ انتخاب خیلی بهتر از دور قبل درآمده و فاصلهٔ ردیف‌ها هم دیگر درست است. فقط لطفاً شمارندهٔ کنار «آخرین‌ها» را با وزن نازک‌تر بچینید. مرسی!') }}</p>
                </div>
            </div>
        </article>

        {{-- Notes --}}
        <article class="mcdesk-win" x-show="apps.notes.open && ! apps.notes.min" x-ref="w-notes" x-cloak
            :style="winStyle('notes')"
            :data-active="active === 'notes' ? '' : null"
            :data-anim="apps.notes.anim"
            :data-zoom="apps.notes.zoom ? '' : null"
            :data-dragging="drag && drag.id === 'notes' ? '' : null"
            x-on:pointerdown="focus('notes')"
            aria-label="{{ $say('Notes window', 'پنجرهٔ یادداشت‌ها') }}">
            <header class="mcdesk-bar"
                x-on:pointerdown="startDrag($event, 'notes')"
                x-on:pointermove="moveDrag($event)"
                x-on:pointerup="stopDrag($event)"
                x-on:pointercancel="stopDrag($event)">
                <div class="mcdesk-lights">
                    <button type="button" class="mcdesk-light" data-close x-on:click="closeWin('notes')" aria-label="{{ $say('Close Notes', 'بستن یادداشت‌ها') }}"><i aria-hidden="true">×</i></button>
                    <button type="button" class="mcdesk-light" data-min x-on:click="minWin('notes')" aria-label="{{ $say('Minimise Notes', 'کوچک‌کردن یادداشت‌ها') }}"><i aria-hidden="true">−</i></button>
                    <button type="button" class="mcdesk-light" data-zoom x-on:click="zoomWin('notes')" aria-label="{{ $say('Zoom Notes', 'بزرگ‌نمایی پنجرهٔ یادداشت‌ها') }}"><i aria-hidden="true"><svg viewBox="0 0 10 10" width="9" height="9" fill="none" stroke="rgba(0,0,0,.55)" stroke-width="1.4" stroke-linecap="round"><path d="M6.2 1.2h2.6v2.6M8.8 1.2 5.6 4.4M3.8 8.8H1.2V6.2M1.2 8.8l3.2-3.2"/></svg></i></button>
                </div>
                <span class="mcdesk-wtitle">{{ $appName('notes') }}</span>
                <div class="mcdesk-tools">
                    <button type="button" class="mcdesk-tool" aria-label="{{ $say('New note', 'یادداشت تازه') }}" x-on:click.stop>+</button>
                </div>
            </header>
            <div class="mcdesk-body mcdesk-note">
                <p class="mcdesk-notehead"><span>{{ $say('Ideas', 'ایده‌ها') }}</span><span>{{ $say('Today', 'امروز') }}</span></p>
                <textarea class="mcdesk-notearea" spellcheck="false" aria-label="{{ $say('Note body', 'متن یادداشت') }}">{{ $say("— Gaussian dock magnification\n— Persian digits for the menu-bar clock\n— Window shadows on dark wallpaper", "— بزرگ‌نمایی گاوسی داک\n— ارقام فارسی ساعت منوبار\n— سایهٔ پنجره‌ها روی کاغذدیواری تیره") }}</textarea>
            </div>
        </article>

        {{-- Dock --}}
        <div class="mcdesk-dock" x-ref="dock" x-on:pointermove="dockMag($event)" x-on:pointerleave="dockReset()" role="toolbar" aria-label="{{ $say('Dock', 'داک') }}">
            <div class="mcdesk-dockitem">
                <button type="button" class="mcdesk-dockicon" x-ref="d-finder" style="background: linear-gradient(160deg, #9bd1ff 0 49%, #2c7bf2 51%)"
                    :data-bouncing="apps.finder.bounce ? '' : null" x-on:click="launch('finder')" aria-label="{{ $say('Open Finder', 'باز کردن یافتنر') }}">
                    <svg viewBox="0 0 48 48" aria-hidden="true" fill="none" stroke="#fff" stroke-linecap="round"><path d="M15.5 16.5v8M32.5 16.5v8" stroke-width="3.6"/><path d="M14.5 30.5c3.2 3.8 6.4 5.3 9.5 5.3s6.3-1.5 9.5-5.3" stroke-width="3"/></svg>
                    <span class="mcdesk-tip">{{ $appName('finder') }}</span>
                </button>
                <span class="mcdesk-dot" :data-on="apps.finder.open ? '' : null" aria-hidden="true"></span>
            </div>
            <div class="mcdesk-dockitem">
                <button type="button" class="mcdesk-dockicon" x-ref="d-mail" style="background: linear-gradient(180deg, #6ec3ff, #0a6cff)"
                    :data-bouncing="apps.mail.bounce ? '' : null" x-on:click="launch('mail')" aria-label="{{ $say('Open Mail', 'باز کردن ایمیل') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3.8" y="6" width="16.4" height="12" rx="2.4"/><path d="m5.5 8.2 6.5 4.9 6.5-4.9"/></svg>
                    <span class="mcdesk-tip">{{ $appName('mail') }}</span>
                </button>
                <span class="mcdesk-dot" :data-on="apps.mail.open ? '' : null" aria-hidden="true"></span>
            </div>
            <div class="mcdesk-dockitem">
                <button type="button" class="mcdesk-dockicon" x-ref="d-notes" style="background: linear-gradient(180deg, #ffd60a 0 27%, #fbfbf4 27%)"
                    :data-bouncing="apps.notes.bounce ? '' : null" x-on:click="launch('notes')" aria-label="{{ $say('Open Notes', 'باز کردن یادداشت‌ها') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="#b0872a" stroke-width="1.6" stroke-linecap="round"><path d="M8.2 13.4h7.6M8.2 16.6h4.8"/></svg>
                    <span class="mcdesk-tip">{{ $appName('notes') }}</span>
                </button>
                <span class="mcdesk-dot" :data-on="apps.notes.open ? '' : null" aria-hidden="true"></span>
            </div>
            <div class="mcdesk-dockitem">
                <button type="button" class="mcdesk-dockicon" style="background: radial-gradient(circle at 50% 40%, #ffffff 0 38%, #dbeeff 68%, #a8d4ff 100%)"
                    :data-bouncing="apps.safari.bounce ? '' : null" x-on:click="launch('safari')" aria-label="{{ $say('Open Safari', 'باز کردن سافاری') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="#2f7cf6" stroke-width="1.6"/><path d="M16.6 7.4 13.06 10.94 10.94 13.06Z" fill="#ff453a"/><path d="M7.4 16.6 13.06 10.94 10.94 13.06Z" fill="#f5f6f8"/></svg>
                    <span class="mcdesk-tip">{{ $say('Safari', 'سافاری') }}</span>
                </button>
                <span class="mcdesk-dot" :data-on="apps.safari.open ? '' : null" aria-hidden="true"></span>
            </div>
            <div class="mcdesk-dockitem">
                <button type="button" class="mcdesk-dockicon" style="background: linear-gradient(180deg, #fb5c74, #f3123f)"
                    :data-bouncing="apps.music.bounce ? '' : null" x-on:click="launch('music')" aria-label="{{ $say('Open Music', 'باز کردن موسیقی') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9.8 17.2V7l7.6-1.8v10"/><circle cx="7.7" cy="17.3" r="2.1" fill="#fff" stroke="none"/><circle cx="15.3" cy="15.3" r="2.1" fill="#fff" stroke="none"/></svg>
                    <span class="mcdesk-tip">{{ $say('Music', 'موسیقی') }}</span>
                </button>
                <span class="mcdesk-dot" :data-on="apps.music.open ? '' : null" aria-hidden="true"></span>
            </div>
            <div class="mcdesk-dockitem">
                <button type="button" class="mcdesk-dockicon" style="background: linear-gradient(180deg, #ff5147 0 27%, #ffffff 27%)"
                    :data-bouncing="apps.calendar.bounce ? '' : null" x-on:click="launch('calendar')" aria-label="{{ $say('Open Calendar', 'باز کردن تقویم') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><text x="12" y="17" text-anchor="middle" font-size="9.5" font-weight="700" fill="#3a3a3c">{{ $fa ? '۸' : '8' }}</text></svg>
                    <span class="mcdesk-tip">{{ $say('Calendar', 'تقویم') }}</span>
                </button>
                <span class="mcdesk-dot" :data-on="apps.calendar.open ? '' : null" aria-hidden="true"></span>
            </div>
            <span class="mcdesk-docksep" aria-hidden="true"></span>
            <div class="mcdesk-dockitem">
                <button type="button" class="mcdesk-dockicon mcdesk-trash" :data-wiggle="trashWiggle ? '' : null" x-on:click="wiggleTrash()" aria-label="{{ $say('Trash', 'سطل زباله') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="rgba(240,240,248,.92)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4.8 6.8h14.4"/><path d="M9.4 6.6V5.2c0-.7.5-1.2 1.2-1.2h2.8c.7 0 1.2.5 1.2 1.2v1.4"/><path d="m6.6 6.8.7 12c.06.9.8 1.6 1.7 1.6h6c.9 0 1.64-.7 1.7-1.6l.7-12"/><path d="M10.1 10.4v6M13.9 10.4v6"/></svg>
                    <span class="mcdesk-tip">{{ $say('Trash', 'سطل زباله') }}</span>
                </button>
            </div>
        </div>

        {{-- Menu bar --}}
        <nav class="mcdesk-menubar" aria-label="{{ $say('Menu bar', 'نوار منو') }}">
            <div class="mcdesk-mbwrap">
                <button type="button" class="mcdesk-mbitem" :data-open="menu === 'apple' ? '' : null" :aria-expanded="menu === 'apple'" aria-haspopup="menu"
                    x-on:click="menu = menu === 'apple' ? null : 'apple'" aria-label="{{ $say('Apple menu', 'منوی اپل') }}">
                    <svg width="13" height="15" viewBox="0 0 814 1000" fill="currentColor" aria-hidden="true"><path d="M788.1 340.9c-5.8 4.5-108.2 62.2-108.2 190.5 0 148.4 130.3 200.9 134.2 202.2-.6 3.2-20.7 71.9-68.7 141.9-42.8 61.6-87.5 123.1-155.5 123.1s-85.5-39.5-164-39.5c-76.5 0-103.7 40.8-165.9 40.8s-105.6-57-155.5-127C46.7 790.7 0 663 0 541.8c0-194.4 126.4-297.5 250.8-297.5 66.1 0 121.2 43.4 162.7 43.4 39.5 0 101.1-46 176.3-46 28.5 0 130.9 2.6 198.3 99.2zm-234-181.5c31.1-36.9 53.1-88.1 53.1-139.3 0-7.1-.6-14.3-1.9-20.1-50.6 1.9-110.8 33.7-147.1 75.8-28.5 32.4-55.1 83.6-55.1 135.5 0 7.8 1.3 15.6 1.9 18.1 3.2.6 8.4 1.3 13.6 1.3 45.4 0 102.5-30.4 135.5-71.3z"/></svg>
                </button>
                <div class="mcdesk-menupop" x-show="menu === 'apple'" x-cloak role="menu">
                    <button type="button" class="mcdesk-mrow" role="menuitem" x-on:click="menu = null"><span></span><span>{{ $say('About This Mac', 'دربارهٔ این مک') }}</span><span></span></button>
                    <hr class="mcdesk-msep">
                    <button type="button" class="mcdesk-mrow" role="menuitem" x-on:click="menu = null"><span></span><span>{{ $say('Sleep', 'خواب') }}</span><span></span></button>
                    <button type="button" class="mcdesk-mrow" role="menuitem" x-on:click="menu = null"><span></span><span>{{ $say('Restart…', 'راه‌اندازی دوباره…') }}</span><span></span></button>
                </div>
            </div>
            <span class="mcdesk-mbapp" x-text="active && names[active] ? names[active] : names.finder">{{ $appName('finder') }}</span>
            <div class="mcdesk-mbwrap">
                <button type="button" class="mcdesk-mbitem" :data-open="menu === 'file' ? '' : null" :aria-expanded="menu === 'file'" aria-haspopup="menu"
                    x-on:click="menu = menu === 'file' ? null : 'file'">{{ $say('File', 'پرونده') }}</button>
                <div class="mcdesk-menupop" x-show="menu === 'file'" x-cloak role="menu">
                    <button type="button" class="mcdesk-mrow" role="menuitem" x-on:click="launch('finder'); menu = null"><span></span><span>{{ $say('New Finder Window', 'پنجرهٔ تازهٔ یافتنر') }}</span><kbd>⌘N</kbd></button>
                    <button type="button" class="mcdesk-mrow" role="menuitem" x-on:click="menu = null"><span></span><span>{{ $say('Open…', 'باز کردن…') }}</span><kbd>⌘O</kbd></button>
                    <hr class="mcdesk-msep">
                    <button type="button" class="mcdesk-mrow" role="menuitemcheckbox" aria-checked="true" x-on:click="menu = null"><span class="mcdesk-tick" aria-hidden="true">✓</span><span>{{ $say('Show Hidden Files', 'نمایش موارد پنهان') }}</span><kbd>⇧⌘.</kbd></button>
                    <button type="button" class="mcdesk-mrow" role="menuitem" x-on:click="if (active) closeWin(active); menu = null"><span></span><span>{{ $say('Close Window', 'بستن پنجره') }}</span><kbd>⌘W</kbd></button>
                    <hr class="mcdesk-msep">
                    <button type="button" class="mcdesk-mrow" role="menuitem" x-on:click="wiggleTrash(); menu = null"><span></span><span>{{ $say('Empty Trash…', 'پاک‌سازی سطل زباله…') }}</span><kbd>⇧⌘⌫</kbd></button>
                </div>
            </div>
            <button type="button" class="mcdesk-mbitem mcdesk-mbedit" x-on:click="menu = null; cc = false">{{ $say('Edit', 'ویرایش') }}</button>
            <div class="mcdesk-mbstatus">
                <svg class="mcdesk-mbico mcdesk-mbbatt" width="23" height="11" viewBox="0 0 23 11" aria-hidden="true"><rect x=".5" y=".5" width="18" height="10" rx="3" fill="none" stroke="currentColor" opacity=".45"/><rect x="2" y="2" width="12.5" height="7" rx="1.5" fill="currentColor"/><path d="M20.2 3.6v3.8c1.1-.3 1.8-1 1.8-1.9s-.7-1.6-1.8-1.9Z" fill="currentColor" opacity=".45"/></svg>
                <svg class="mcdesk-mbico" :data-off="! wifi ? '' : null" width="16" height="12" viewBox="0 0 16 12" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M1.6 4.1a10 10 0 0 1 12.8 0"/><path d="M4 6.7a6.4 6.4 0 0 1 8 0"/><path d="M6.4 9.2a3 3 0 0 1 3.2 0"/><circle cx="8" cy="11" r=".7" fill="currentColor" stroke="none"/></svg>
                <div class="mcdesk-mbwrap">
                    <button type="button" class="mcdesk-mbitem" :data-open="cc ? '' : null" :aria-expanded="cc" aria-haspopup="dialog"
                        x-on:click="cc = ! cc" aria-label="{{ $say('Control Centre', 'مرکز کنترل') }}">
                        <svg width="15" height="15" viewBox="0 0 15 15" aria-hidden="true"><g stroke="currentColor" stroke-width="1.4" stroke-linecap="round" fill="none"><path d="M4.6 2.2v10.6M10.4 2.2v10.6"/></g><circle cx="4.6" cy="9.6" r="2" fill="currentColor"/><circle cx="10.4" cy="5" r="2" fill="currentColor"/></svg>
                    </button>
                    <div class="mcdesk-menupop mcdesk-ccpop" x-show="cc" x-cloak role="dialog" aria-label="{{ $say('Control Centre', 'مرکز کنترل') }}">
                        <div class="mcdesk-ccrow">
                            <svg width="13" height="13" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" aria-hidden="true"><circle cx="7" cy="7" r="2.6" fill="currentColor" stroke="none"/><path d="M7 1v1.6M7 11.4V13M1 7h1.6M11.4 7H13M2.8 2.8l1.1 1.1M10.1 10.1l1.1 1.1M11.2 2.8l-1.1 1.1M3.9 10.1l-1.1 1.1"/></svg>
                            <input type="range" min="35" max="100" value="100" x-on:input="bright = +$event.target.value" aria-label="{{ $say('Brightness', 'روشنایی') }}">
                        </div>
                        <div class="mcdesk-ccrow" style="justify-content: space-between">
                            <span style="display: inline-flex; align-items: center; gap: 7px">
                                <svg width="14" height="11" viewBox="0 0 16 12" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M1.6 4.1a10 10 0 0 1 12.8 0"/><path d="M4 6.7a6.4 6.4 0 0 1 8 0"/><path d="M6.4 9.2a3 3 0 0 1 3.2 0"/><circle cx="8" cy="11" r=".7" fill="currentColor" stroke="none"/></svg>
                                {{ $say('Wi-Fi', 'وای‌فای') }}
                            </span>
                            <button type="button" class="mcdesk-switch" :aria-pressed="wifi" x-on:click="wifi = ! wifi" aria-label="{{ $say('Wi-Fi', 'وای‌فای') }}"></button>
                        </div>
                    </div>
                </div>
                <span class="mcdesk-mbclock" x-text="clock"></span>
            </div>
        </nav>
    </div>
</section>
