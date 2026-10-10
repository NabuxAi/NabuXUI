{{--
    route-map's real scenario: the express line from Central Station to
    Airport T3 over the same hand-drawn harbour city. The dotted route inks
    itself through a wiping SVG mask, the numbered stops settle in behind the
    pen tip, the sidebar counts every leg with its ETA and distance, and the
    "other route" button swaps to the riverside line and re-draws.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫']) : $s;

    $routes = [
        'a' => [
            'name' => $say('Harbor Express', 'اکسپرس بندر'),
            'eta' => $say('38 min', '۳۸ دقیقه'), 'dist' => $say('21.4 km', '۲۱٫۴ کیلومتر'),
            'd' => 'M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80',
            'stops' => [
                ['n' => '1', 'x' => 70, 'y' => 330, 'name' => $say('Central Station', 'ایستگاه مرکزی'), 'seg' => $say('Origin', 'مبدأ')],
                ['n' => '2', 'x' => 170, 'y' => 255, 'name' => $say('Old Harbor', 'بندر قدیمی'), 'seg' => $say('7 min · 4.1 km', '۷ دقیقه · ۴٫۱ کیلومتر')],
                ['n' => '3', 'x' => 300, 'y' => 180, 'name' => $say('Museum Mile', 'خیابان موزه‌ها'), 'seg' => $say('9 min · 5.2 km', '۹ دقیقه · ۵٫۲ کیلومتر')],
                ['n' => '4', 'x' => 450, 'y' => 150, 'name' => $say('Botanic Ring', 'حلقهٔ گیاه‌شناسی'), 'seg' => $say('11 min · 6.3 km', '۱۱ دقیقه · ۶٫۳ کیلومتر')],
                ['n' => '5', 'x' => 580, 'y' => 80, 'name' => $say('Airport T3', 'فرودگاه T3'), 'seg' => $say('11 min · 5.8 km', '۱۱ دقیقه · ۵٫۸ کیلومتر')],
            ],
        ],
        'b' => [
            'name' => $say('Riverside Line', 'خط رودخانه'),
            'eta' => $say('46 min', '۴۶ دقیقه'), 'dist' => $say('24.8 km', '۲۴٫۸ کیلومتر'),
            'd' => 'M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80',
            'stops' => [
                ['n' => '1', 'x' => 70, 'y' => 330, 'name' => $say('Central Station', 'ایستگاه مرکزی'), 'seg' => $say('Origin', 'مبدأ')],
                ['n' => '2', 'x' => 190, 'y' => 345, 'name' => $say('Kessel Bridge', 'پل کسل'), 'seg' => $say('8 min · 4.4 km', '۸ دقیقه · ۴٫۴ کیلومتر')],
                ['n' => '3', 'x' => 330, 'y' => 295, 'name' => $say('Signal Wharf', 'اسکلهٔ سیگنال'), 'seg' => $say('10 min · 5.6 km', '۱۰ دقیقه · ۵٫۶ کیلومتر')],
                ['n' => '4', 'x' => 470, 'y' => 230, 'name' => $say('Freight Bypass', 'میان‌بر بار'), 'seg' => $say('13 min · 6.7 km', '۱۳ دقیقه · ۶٫۷ کیلومتر')],
                ['n' => '5', 'x' => 580, 'y' => 80, 'name' => $say('Airport T3', 'فرودگاه T3'), 'seg' => $say('15 min · 8.1 km', '۱۵ دقیقه · ۸٫۱ کیلومتر')],
            ],
        ],
    ];
@endphp

<style>
    .mrt-root {
        --mrt-bg: #f6f7f9; --mrt-block: #e8eaef; --mrt-park: #d9ead3; --mrt-water: #cfe3f7;
        --mrt-line: #d4d8e0; --mrt-accent: #e11d48; --mrt-text: #1c1917; --mrt-muted: #78716c;
        --mrt-card: #ffffff;
        display: grid; gap: 1.25rem; justify-items: center;
        grid-template-columns: minmax(0, 1.55fr) minmax(15.5rem, 1fr);
        align-items: start;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; color: var(--mrt-text);
    }
    html[data-theme="dark"] .mrt-root {
        --mrt-bg: #191d24; --mrt-block: #232830; --mrt-park: #1f2d22; --mrt-water: #1d2c3f;
        --mrt-line: #2e343d; --mrt-accent: #fb7185; --mrt-text: #f4f4f5; --mrt-muted: #a1a1aa;
        --mrt-card: #20252d;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mrt-root {
            --mrt-bg: #191d24; --mrt-block: #232830; --mrt-park: #1f2d22; --mrt-water: #1d2c3f;
            --mrt-line: #2e343d; --mrt-accent: #fb7185; --mrt-text: #f4f4f5; --mrt-muted: #a1a1aa;
            --mrt-card: #20252d;
        }
    }
    .mrt-layer {
        position: relative; inline-size: 100%; aspect-ratio: 16 / 10; overflow: clip;
        border: 1px solid var(--mrt-line); border-radius: 1rem; background: var(--mrt-bg);
    }
    .mrt-city { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
    .mrt-base { fill: var(--mrt-bg); }
    .mrt-street { fill: none; stroke: var(--mrt-line); stroke-width: 4; opacity: .55; }
    .mrt-river { fill: none; stroke: var(--mrt-water); stroke-width: 22; stroke-linecap: round; }
    .mrt-block { fill: var(--mrt-block); }
    .mrt-leaf { fill: color-mix(in oklab, var(--mrt-park) 62%, var(--mrt-block)); }
    .mrt-ghost { fill: none; stroke: var(--mrt-line); stroke-width: 3; stroke-dasharray: 2 7;
                 stroke-linecap: round; opacity: .9; }
    .mrt-line { fill: none; stroke: var(--mrt-accent); stroke-width: 4.5; stroke-linecap: round;
                stroke-dasharray: .012 .03; }
    .mrt-wipe { fill: none; stroke: #fff; stroke-width: 18; stroke-dasharray: 1; stroke-dashoffset: 1; }
    .mrt-layer[data-alt="a"] .mrt-wipe-a { animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
    .mrt-layer[data-alt="b"] .mrt-wipe-b { animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
    @keyframes mrt-draw { to { stroke-dashoffset: 0; } }
    .mrt-stops { display: none; }
    .mrt-layer[data-alt="a"] .mrt-stops-a, .mrt-layer[data-alt="b"] .mrt-stops-b { display: block; }
    .mrt-stop { opacity: 0; scale: .5; transform-box: fill-box; transform-origin: center; }
    .mrt-layer[data-alt="a"] .mrt-stops-a .mrt-stop,
    .mrt-layer[data-alt="b"] .mrt-stops-b .mrt-stop {
        animation: mrt-stop-in .3s ease both; animation-delay: calc(.25s + var(--i) * .38s);
    }
    @keyframes mrt-stop-in { to { opacity: 1; scale: 1; } }
    .mrt-stop circle { fill: var(--mrt-card); stroke: var(--mrt-accent); stroke-width: 3; }
    .mrt-stop text { fill: var(--mrt-accent); font-size: 9px; font-weight: 700; text-anchor: middle; }
    .mrt-stop .mrt-stop-name {
        fill: var(--mrt-text); font-size: 11px; font-weight: 600;
        paint-order: stroke; stroke: var(--mrt-bg); stroke-width: 3px; stroke-linejoin: round;
    }
    .mrt-panel {
        position: relative; display: grid; gap: .8rem; align-content: start;
        inline-size: 100%; padding: 1.1rem 1.2rem 1.25rem;
        border: 1px solid var(--mrt-line); border-radius: 1rem; background: var(--mrt-card);
    }
    .mrt-head { display: grid; gap: .3rem; margin: 0; }
    .mrt-route-name { margin: 0; font-size: 1.05rem; font-weight: 700; }
    .mrt-badges { display: flex; flex-wrap: wrap; gap: .4rem; margin: 0; padding: 0; list-style: none; }
    .mrt-badge {
        display: inline-flex; align-items: center; gap: .3rem; padding: .22rem .65rem;
        border-radius: 999px; font-size: .74rem; font-weight: 700;
        background: color-mix(in oklab, var(--mrt-accent) 14%, transparent); color: var(--mrt-accent);
    }
    .mrt-steps { display: grid; gap: 0; margin: 0; padding: 0; list-style: none; }
    .mrt-steps li {
        position: relative; display: grid; grid-template-columns: 1.7rem 1fr; gap: .1rem .7rem;
        align-items: baseline; padding-block: .5rem;
    }
    .mrt-steps li:not(:last-child)::before {
        content: ""; position: absolute; inset-inline-start: .8rem; inset-block: 1.4rem 0;
        border-inline-start: 2px dotted color-mix(in oklab, var(--mrt-accent) 45%, transparent);
    }
    .mrt-steps i {
        display: grid; place-items: center; inline-size: 1.7rem; aspect-ratio: 1; border-radius: 50%;
        border: 2px solid var(--mrt-accent); background: var(--mrt-card);
        font-style: normal; font-size: .72rem; font-weight: 700; color: var(--mrt-accent);
        translate: 0 .15rem;
    }
    .mrt-steps b { font-size: .86rem; font-weight: 700; }
    .mrt-steps small { grid-column: 2; color: var(--mrt-muted); font-size: .72rem; }
    .mrt-steps li[data-end="true"] i { background: var(--mrt-accent); color: #fff; }
    .mrt-swap {
        display: inline-flex; align-items: center; justify-content: center; gap: .45rem;
        padding: .62rem 1.15rem; border: none; border-radius: 999px;
        background: var(--mrt-accent); color: #fff; font: 600 .82rem/1 inherit; cursor: pointer;
        transition: translate .15s ease, box-shadow .15s ease;
    }
    .mrt-swap:hover { translate: 0 -1px; box-shadow: 0 6px 18px color-mix(in oklab, var(--mrt-accent) 35%, transparent); }
    .mrt-swap:focus-visible { outline: 2px solid var(--mrt-accent); outline-offset: 2px; }
    .mrt-swap svg { inline-size: .95rem; block-size: .95rem; }
    .mrt-note { margin: 0; font-size: .72rem; color: var(--mrt-muted); }
    @media (prefers-reduced-motion: reduce) {
        .mrt-wipe { stroke-dashoffset: 0; animation: none; }
        .mrt-stop { animation: none; opacity: 1; scale: 1; }
        .mrt-swap { transition: none; }
        .mrt-swap:hover { translate: none; }
    }
    @media (max-width: 720px) {
        .mrt-root { grid-template-columns: 1fr; }
        .mrt-stop .mrt-stop-name { font-size: 10px; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Central Station to Airport T3, self-inking', 'از ایستگاه مرکزی تا فرودگاه، با قلم خودکار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The dotted route draws itself through a wiping SVG mask, the five numbered stops settle in behind the pen tip, and the sidebar counts every leg — ETA and distance per segment. The “other route” button swaps the Harbor Express for the Riverside Line and the whole draw replays.', 'مسیر نقطه‌چین از دل یک ماسک SVG که خودش پاک می‌شود ترسیم می‌شود، پنج ایستگاهٔ شماردار پشت نوک قلم می‌نشینند و سایدبار هر بازو را می‌شمارد — ETA و فاصلهٔ هر قطعه. دکمهٔ «مسیر دیگر» اکسپرس بندر را با خط رودخانه عوض می‌کند و کل ترسیم از نو اجرا می‌شود.') }}
        </p>
    </div>

    <div class="mrt-root" x-data="{ alt: 'a' }">
        <div class="mrt-layer" dir="ltr" x-bind:data-alt="alt"
            role="img" aria-label="{{ $say('Stylised city map with a transit route from Central Station to the airport', 'نقشهٔ استیلیزهٔ شهر با مسیر حمل‌ونقل از ایستگاه مرکزی تا فرودگاه') }}">
            <svg class="mrt-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <rect class="mrt-base" x="0" y="0" width="640" height="400"/>
                <path class="mrt-river" d="M-10 366 C120 350 250 330 360 314 S540 268 650 224"/>
                <path class="mrt-street" d="M0 128 C160 122 300 132 640 108"/>
                <path class="mrt-street" d="M258 0 C250 120 242 260 236 400"/>
                <g>
                    <rect class="mrt-block" x="60" y="60" width="110" height="70" rx="10"/>
                    <rect class="mrt-block" x="240" y="46" width="96" height="60" rx="10"/>
                    <rect class="mrt-block" x="420" y="40" width="120" height="56" rx="10"/>
                    <rect class="mrt-block" x="330" y="220" width="110" height="60" rx="10"/>
                    <rect class="mrt-block" x="120" y="150" width="90" height="58" rx="10"/>
                    <rect class="mrt-block" x="120" y="240" width="90" height="44" rx="10"/>
                    <rect class="mrt-block" x="500" y="300" width="110" height="60" rx="10"/>
                    <rect class="mrt-block" x="560" y="150" width="56" height="90" rx="10"/>
                </g>
                <g>
                    <rect class="mrt-block" x="380" y="120" width="72" height="64" rx="10" fill="var(--mrt-park)"/>
                    <circle class="mrt-leaf" cx="402" cy="146" r="7"/>
                    <circle class="mrt-leaf" cx="430" cy="162" r="6"/>
                </g>

                <path class="mrt-ghost" d="{{ $routes['a']['d'] }}"/>
                <path class="mrt-ghost" d="{{ $routes['b']['d'] }}"/>

                <defs>
                    <mask id="mrt-wipe-a" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
                        <path class="mrt-wipe mrt-wipe-a" pathLength="1" d="{{ $routes['a']['d'] }}"/>
                    </mask>
                    <mask id="mrt-wipe-b" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
                        <path class="mrt-wipe mrt-wipe-b" pathLength="1" d="{{ $routes['b']['d'] }}"/>
                    </mask>
                </defs>
                <path class="mrt-line" pathLength="1" mask="url(#mrt-wipe-a)" d="{{ $routes['a']['d'] }}"/>
                <path class="mrt-line" pathLength="1" mask="url(#mrt-wipe-b)" d="{{ $routes['b']['d'] }}"/>

                @foreach ($routes as $key => $route)
                    <g class="mrt-stops mrt-stops-{{ $key }}">
                        @foreach ($route['stops'] as $i => $stop)
                            <g class="mrt-stop" style="--i: {{ $i }}">
                                <circle cx="{{ $stop['x'] }}" cy="{{ $stop['y'] }}" r="{{ $stop['n'] === '1' || $stop['n'] === '5' ? '8' : '7' }}"/>
                                <text x="{{ $stop['x'] }}" y="{{ $stop['y'] + 3.5 }}">{{ $stop['n'] }}</text>
                                <text class="mrt-stop-name" x="{{ $stop['x'] }}" y="{{ $stop['y'] - 14 }}">{{ $stop['name'] }}</text>
                            </g>
                        @endforeach
                    </g>
                @endforeach
            </svg>
        </div>

        <aside class="mrt-panel" role="status" aria-live="polite">
            @foreach ($routes as $key => $route)
                <div class="mrt-head" x-cloak x-show="alt === '{{ $key }}'">
                    <h4 class="mrt-route-name">{{ $route['name'] }}</h4>
                    <ul class="mrt-badges">
                        <li class="mrt-badge">{{ $say('ETA', 'رسیدن') }} {{ $route['eta'] }}</li>
                        <li class="mrt-badge">{{ $route['dist'] }}</li>
                    </ul>
                </div>
                <ol class="mrt-steps" x-cloak x-show="alt === '{{ $key }}'">
                    @foreach ($route['stops'] as $i => $stop)
                        <li data-end="{{ $i === count($route['stops']) - 1 ? 'true' : 'false' }}">
                            <i>{{ $stop['n'] }}</i>
                            <b>{{ $stop['name'] }}</b>
                            <small>{{ $stop['seg'] }}</small>
                        </li>
                    @endforeach
                </ol>
            @endforeach
            <button type="button" class="mrt-swap" x-on:click="alt = alt === 'a' ? 'b' : 'a'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 12h16"/><path d="m13 6 6 6-6 6"/>
                </svg>
                <span x-text="alt === 'a'
                    ? '{{ $say('Other route: Riverside Line', 'مسیر دیگر: خط رودخانه') }}'
                    : '{{ $say('Other route: Harbor Express', 'مسیر دیگر: اکسپرس بندر') }}'"></span>
            </button>
            <p class="mrt-note">
                {{ $say('Same city, two lines — the ghost of each route stays on the map while the other draws.', 'یک شهر، دو خط — شبح هر مسیر روی نقشه می‌ماند و دیگری ترسیم می‌شود.') }}
            </p>
        </aside>
    </div>
</section>
