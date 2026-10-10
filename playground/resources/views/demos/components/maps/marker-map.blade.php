{{--
    marker-map's real scenario: a hand-drawn harbour city in pure SVG — six
    POI pins that swell and find their label on hover, a details popover with
    a gradient thumbnail, rating and closing hour on click (one open at a
    time, Escape to fold), and five districts that light up under the pointer
    or from their legend chips. No tiles, no SDK.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $pois = [
        ['id' => 'anchor', 'x' => 22, 'y' => 78, 'district' => 'old-harbor',
            'name' => $say('The Anchor', 'لنگر'), 'kind' => $say('Café', 'کافه'),
            'rating' => $say('4.8', '۴٫۸'), 'hours' => $say('until 22:00', 'تا ۲۲:۰۰'),
            'dist' => $say('350 m away', '۳۵۰ متر فاصله'),
            'grad' => 'linear-gradient(135deg, #38bdf8, #6366f1)'],
        ['id' => 'northlight', 'x' => 58, 'y' => 28, 'district' => 'museum-mile',
            'name' => $say('North Light Gallery', 'گالری نور شمالی'), 'kind' => $say('Gallery', 'گالری'),
            'rating' => $say('4.9', '۴٫۹'), 'hours' => $say('until 18:00', 'تا ۱۸:۰۰'),
            'dist' => $say('1.2 km away', '۱٫۲ کیلومتر فاصله'),
            'grad' => 'linear-gradient(135deg, #f59e0b, #ef4444)'],
        ['id' => 'kessel', 'x' => 44, 'y' => 52, 'district' => 'old-town',
            'name' => $say('Kessel Records', 'رسانه‌های کسل'), 'kind' => $say('Vinyl shop', 'پلاک‌فروشی'),
            'rating' => $say('4.6', '۴٫۶'), 'hours' => $say('until 21:00', 'تا ۲۱:۰۰'),
            'dist' => $say('640 m away', '۶۴۰ متر فاصله'),
            'grad' => 'linear-gradient(135deg, #34d399, #0ea5e9)'],
        ['id' => 'glasshouse', 'x' => 76, 'y' => 58, 'district' => 'botanic-ring',
            'name' => $say('Botanic Glasshouse', 'گرمابهٔ گیاه‌شناسی'), 'kind' => $say('Garden', 'باغ'),
            'rating' => $say('4.7', '۴٫۷'), 'hours' => $say('until 19:00', 'تا ۱۹:۰۰'),
            'dist' => $say('2.1 km away', '۲٫۱ کیلومتر فاصله'),
            'grad' => 'linear-gradient(135deg, #22c55e, #84cc16)'],
        ['id' => 'wharf7', 'x' => 12, 'y' => 74, 'district' => 'old-harbor',
            'name' => $say('Ferry Wharf 7', 'اسکلهٔ کشتی ۷'), 'kind' => $say('Transport', 'حمل‌ونقل'),
            'rating' => $say('4.4', '۴٫۴'), 'hours' => $say('05:00 to 24:00', '۰۵:۰۰ تا ۲۴:۰۰'),
            'dist' => $say('900 m away', '۹۰۰ متر فاصله'),
            'grad' => 'linear-gradient(135deg, #0ea5e9, #6366f1)'],
        ['id' => 'aurora', 'x' => 68, 'y' => 12, 'district' => 'north-ridge',
            'name' => $say('Aurora Viewpoint', 'چشم‌انداز اورورا'), 'kind' => $say('Viewpoint', 'بلندی'),
            'rating' => $say('4.9', '۴٫۹'), 'hours' => $say('always open', 'همیشه باز'),
            'dist' => $say('1.6 km away', '۱٫۶ کیلومتر فاصله'),
            'grad' => 'linear-gradient(135deg, #8b5cf6, #ec4899)'],
    ];

    $districts = [
        ['id' => 'old-harbor', 'label' => $say('Old Harbor', 'بندر قدیمی'),
            'points' => '0,286 186,326 424,296 424,400 0,400', 'lx' => 130, 'ly' => 368],
        ['id' => 'old-town', 'label' => $say('Old Town', 'شهر قدیمی'),
            'points' => '0,94 330,118 470,226 424,296 186,326 0,286', 'lx' => 170, 'ly' => 210],
        ['id' => 'museum-mile', 'label' => $say('Museum Mile', 'خیابان موزه‌ها'),
            'points' => '330,118 640,86 640,204 470,226', 'lx' => 508, 'ly' => 162],
        ['id' => 'botanic-ring', 'label' => $say('Botanic Ring', 'حلقهٔ گیاه‌شناسی'),
            'points' => '470,226 640,204 640,400 424,400 424,296', 'lx' => 500, 'ly' => 340],
        ['id' => 'north-ridge', 'label' => $say('North Ridge', 'پشتهٔ شمالی'),
            'points' => '0,0 640,0 640,86 330,118 0,94', 'lx' => 470, 'ly' => 44],
    ];
@endphp

<style>
    .mpm-root {
        --mpm-bg: #f6f7f9; --mpm-block: #e8eaef; --mpm-park: #d9ead3; --mpm-water: #cfe3f7;
        --mpm-line: #d4d8e0; --mpm-accent: #4f46e5; --mpm-text: #1c1917; --mpm-muted: #78716c;
        --mpm-card: #ffffff;
        display: grid; gap: 1rem; justify-items: center;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; color: var(--mpm-text);
    }
    html[data-theme="dark"] .mpm-root {
        --mpm-bg: #191d24; --mpm-block: #232830; --mpm-park: #1f2d22; --mpm-water: #1d2c3f;
        --mpm-line: #2e343d; --mpm-accent: #818cf8; --mpm-text: #f4f4f5; --mpm-muted: #a1a1aa;
        --mpm-card: #20252d;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mpm-root {
            --mpm-bg: #191d24; --mpm-block: #232830; --mpm-park: #1f2d22; --mpm-water: #1d2c3f;
            --mpm-line: #2e343d; --mpm-accent: #818cf8; --mpm-text: #f4f4f5; --mpm-muted: #a1a1aa;
            --mpm-card: #20252d;
        }
    }
    .mpm-stage {
        position: relative; inline-size: min(100%, 44rem); aspect-ratio: 16 / 10;
        overflow: clip; border: 1px solid var(--mpm-line); border-radius: 1rem;
        background: var(--mpm-bg); touch-action: manipulation;
    }
    .mpm-city { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
    .mpm-base { fill: var(--mpm-bg); }
    .mpm-street { fill: none; stroke: var(--mpm-line); stroke-width: 4; opacity: .55; }
    .mpm-river { fill: none; stroke: var(--mpm-water); stroke-width: 26; stroke-linecap: round; }
    .mpm-block { fill: var(--mpm-block); }
    .mpm-leaf { fill: color-mix(in oklab, var(--mpm-park) 62%, var(--mpm-block)); }
    .mpm-area { fill: transparent; stroke: none; cursor: default;
                transition: fill .25s ease; }
    .mpm-area:hover, .mpm-area[data-hi="true"] {
        fill: color-mix(in oklab, var(--mpm-accent) 16%, transparent); }
    .mpm-area-name { fill: var(--mpm-muted); font-size: 11px; font-weight: 600;
                     letter-spacing: .04em; text-anchor: middle; pointer-events: none; }
    .mpm-pin {
        position: absolute; translate: -50% -50%; inline-size: 1.7rem; aspect-ratio: 1;
        border: none; padding: 0; background: transparent; cursor: pointer; z-index: 2;
    }
    .mpm-bead {
        position: relative; display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%;
        border: 3px solid var(--mpm-card); background: var(--mpm-accent);
        box-shadow: 0 2px 8px rgba(15, 23, 42, .28);
        transition: scale .2s cubic-bezier(.3, .7, .2, 1);
    }
    .mpm-pin:hover .mpm-bead, .mpm-pin:focus-visible .mpm-bead { scale: 1.3; }
    .mpm-pin[aria-expanded="true"] .mpm-bead { scale: 1.3; }
    .mpm-pin[aria-expanded="true"] .mpm-bead::after {
        content: ""; position: absolute; inset: -7px; border-radius: 50%;
        border: 2px solid var(--mpm-accent); animation: mpm-halo 1.6s ease-out infinite;
    }
    .mpm-pin:focus-visible { outline: 2px solid var(--mpm-accent); outline-offset: 3px; border-radius: 50%; }
    .mpm-label {
        position: absolute; inset-block-end: calc(100% + .5rem); inset-inline-start: 50%;
        translate: -50%; white-space: nowrap; padding: .22rem .65rem; border-radius: .6rem;
        background: var(--mpm-card); color: var(--mpm-text); border: 1px solid var(--mpm-line);
        font-size: .72rem; font-weight: 600; box-shadow: 0 4px 14px rgba(15, 23, 42, .16);
        opacity: 0; visibility: hidden; transition: opacity .2s ease, visibility .2s ease;
        pointer-events: none;
    }
    .mpm-pin:hover .mpm-label, .mpm-pin:focus-visible .mpm-label,
    .mpm-pin[aria-expanded="true"] .mpm-label { opacity: 1; visibility: visible; }
    .mpm-pop {
        position: absolute; translate: -50% calc(-100% - 1.1rem); z-index: 3;
        inline-size: 12rem; display: grid; gap: .45rem; padding: .65rem .7rem;
        border: 1px solid var(--mpm-line); border-radius: .9rem; background: var(--mpm-card);
        color: var(--mpm-text); box-shadow: 0 14px 34px rgba(15, 23, 42, .22);
        animation: mpm-pop-in .24s cubic-bezier(.3, .7, .2, 1);
    }
    .mpm-pop[data-below="true"] { translate: -50% 1.6rem; }
    .mpm-thumb { block-size: 3.4rem; border-radius: .55rem; }
    .mpm-pop-name { display: flex; align-items: baseline; gap: .4rem; margin: 0; font-size: .85rem; }
    .mpm-pop-name b { font-weight: 700; }
    .mpm-pop-name small { color: var(--mpm-muted); font-size: .7rem; }
    .mpm-pop-meta {
        display: flex; flex-wrap: wrap; gap: .3rem .45rem; margin: 0; padding: 0; list-style: none;
    }
    .mpm-pop-meta li {
        padding: .14rem .5rem; border-radius: 999px; font-size: .68rem; font-weight: 600;
        background: color-mix(in oklab, var(--mpm-text) 7%, transparent); color: var(--mpm-muted);
    }
    .mpm-pop-meta li[data-tone="star"] {
        background: color-mix(in oklab, #f59e0b 18%, transparent); color: #b45309;
    }
    html[data-theme="dark"] .mpm-pop-meta li[data-tone="star"] { color: #fbbf24; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mpm-pop-meta li[data-tone="star"] { color: #fbbf24; }
    }
    .mpm-close {
        position: absolute; inset-block-start: .45rem; inset-inline-end: .45rem;
        inline-size: 1.35rem; aspect-ratio: 1; display: grid; place-items: center;
        border: none; border-radius: 50%; background: color-mix(in oklab, var(--mpm-text) 8%, transparent);
        color: var(--mpm-muted); font-size: .8rem; line-height: 1; cursor: pointer;
    }
    .mpm-close:hover { color: var(--mpm-text); }
    .mpm-close:focus-visible { outline: 2px solid var(--mpm-accent); outline-offset: 1px; }
    .mpm-legend {
        display: flex; flex-wrap: wrap; justify-content: center; gap: .45rem; margin: 0; padding: 0; list-style: none;
    }
    .mpm-chip {
        display: inline-flex; align-items: center; gap: .4rem; padding: .3rem .75rem;
        border: 1px solid var(--mpm-line); border-radius: 999px; background: var(--mpm-card);
        color: var(--mpm-muted); font: 600 .72rem/1 inherit; cursor: pointer;
        transition: color .2s ease, border-color .2s ease, background-color .2s ease;
    }
    .mpm-chip i { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: var(--mpm-accent); opacity: .5; }
    .mpm-chip:hover, .mpm-chip[data-hi="true"] {
        color: var(--mpm-text); border-color: var(--mpm-accent);
        background: color-mix(in oklab, var(--mpm-accent) 10%, transparent);
    }
    .mpm-chip[data-hi="true"] i, .mpm-chip:hover i { opacity: 1; }
    .mpm-chip:focus-visible { outline: 2px solid var(--mpm-accent); outline-offset: 2px; }
    @keyframes mpm-halo {
        0% { scale: .7; opacity: .9; } 100% { scale: 1.5; opacity: 0; }
    }
    @keyframes mpm-pop-in {
        from { opacity: 0; scale: .92; translate: -50% calc(-100% - .6rem); }
        to { opacity: 1; scale: 1; translate: -50% calc(-100% - 1.1rem); }
    }
    .mpm-pop[data-below="true"] { animation-name: mpm-pop-in-below; }
    @keyframes mpm-pop-in-below {
        from { opacity: 0; scale: .92; translate: -50% 1rem; }
        to { opacity: 1; scale: 1; translate: -50% 1.6rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .mpm-bead, .mpm-label, .mpm-area, .mpm-chip { transition: none; }
        .mpm-pin[aria-expanded="true"] .mpm-bead::after { animation: none; opacity: .55; }
        .mpm-pop { animation: none; }
    }
    @media (max-width: 480px) {
        .mpm-pop { inline-size: 10rem; padding: .55rem .6rem; }
        .mpm-thumb { block-size: 2.8rem; }
    }
</style>

<div class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A city you can poke', 'شهری که می‌شود لمسش کرد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Six points of interest over a hand-drawn harbour town: hover a pin and it swells with its label, click and the details popover opens — gradient thumbnail, rating, closing hour, distance. One popover at a time; Escape folds it. The five districts light up under the pointer or from the legend chips.', 'شش مکان دیدنی روی شهری بندری و دست‌کشیده: هاور روی پین، آن را با لیبلش متورم می‌کند و کلیک، پاپ‌اور جزئیات را باز می‌کند — عکس‌بندانگشتی گرادیانی، امتیاز، ساعت پایانی و فاصله. هم‌زمان فقط یک پاپ‌اور باز است؛ Escape می‌بنددش. پنج ناحیهٔ شهر هم زیر اشاره‌گر یا از چیپ‌های راهنما روشن می‌شوند.') }}
        </p>
    </div>

    <div class="mpm-root"
        x-data="{ open: null, area: null }"
        x-on:keydown.escape.window="open = null">
        <div class="mpm-stage" dir="ltr"
            role="img" aria-label="{{ $say('Stylised city map of a harbour town with six points of interest', 'نقشهٔ استیلیزهٔ شهری بندری با شش مکان دیدنی') }}">
            <svg class="mpm-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <rect class="mpm-base" x="0" y="0" width="640" height="400"/>
                <path class="mpm-river" d="M-10 214 C130 192 210 252 330 232 S520 188 650 218"/>
                <path class="mpm-street" d="M0 128 C160 122 300 132 640 108"/>
                <path class="mpm-street" d="M258 0 C250 120 242 260 236 400"/>
                <g>
                    <rect class="mpm-block" x="40" y="40" width="110" height="64" rx="10"/>
                    <rect class="mpm-block" x="300" y="30" width="120" height="52" rx="10"/>
                    <rect class="mpm-block" x="556" y="120" width="60" height="50" rx="10"/>
                    <rect class="mpm-block" x="384" y="60" width="86" height="56" rx="10"/>
                    <rect class="mpm-block" x="64" y="150" width="90" height="66" rx="10"/>
                    <rect class="mpm-block" x="180" y="146" width="88" height="58" rx="10"/>
                    <rect class="mpm-block" x="300" y="152" width="110" height="52" rx="10"/>
                    <rect class="mpm-block" x="64" y="238" width="104" height="48" rx="10"/>
                    <rect class="mpm-block" x="196" y="252" width="92" height="44" rx="10"/>
                    <rect class="mpm-block" x="316" y="246" width="96" height="40" rx="10"/>
                    <rect class="mpm-block" x="64" y="340" width="150" height="40" rx="10"/>
                    <rect class="mpm-block" x="248" y="336" width="150" height="40" rx="10"/>
                    <rect class="mpm-block" x="492" y="320" width="110" height="56" rx="10"/>
                    <rect class="mpm-block" x="500" y="120" width="40" height="150" rx="10"/>
                </g>
                <g>
                    <rect class="mpm-block" x="508" y="196" width="80" height="98" rx="10" fill="var(--mpm-park)"/>
                    <circle class="mpm-leaf" cx="530" cy="222" r="7"/>
                    <circle class="mpm-leaf" cx="560" cy="252" r="8"/>
                    <circle class="mpm-leaf" cx="542" cy="272" r="6"/>
                </g>
                @foreach ($districts as $d)
                    <polygon class="mpm-area" points="{{ $d['points'] }}"
                        x-bind:data-hi="area === '{{ $d['id'] }}' ? 'true' : null">
                        <title>{{ $d['label'] }}</title>
                    </polygon>
                    <text class="mpm-area-name" x="{{ $d['lx'] }}" y="{{ $d['ly'] }}">{{ $d['label'] }}</text>
                @endforeach
            </svg>

            @foreach ($pois as $p)
                <button type="button" class="mpm-pin"
                    style="inset-inline-start: {{ $p['x'] }}%; inset-block-start: {{ $p['y'] }}%"
                    x-bind:aria-expanded="(open === '{{ $p['id'] }}').toString()"
                    x-on:pointerenter="area = '{{ $p['district'] }}'"
                    x-on:pointerleave="area = null"
                    x-on:focus="area = '{{ $p['district'] }}'"
                    x-on:click="open = open === '{{ $p['id'] }}' ? null : '{{ $p['id'] }}'">
                    <span class="mpm-bead" aria-hidden="true"></span>
                    <span class="mpm-label" aria-hidden="true">{{ $p['name'] }} · {{ $p['kind'] }}</span>
                </button>
                <div class="mpm-pop" x-cloak x-show="open === '{{ $p['id'] }}'"
                    data-below="{{ $p['y'] < 25 ? 'true' : 'false' }}"
                    style="inset-inline-start: {{ $p['x'] }}%; inset-block-start: {{ $p['y'] }}%"
                    role="dialog" aria-label="{{ $p['name'] }}">
                    <span class="mpm-thumb" style="background: {{ $p['grad'] }}" aria-hidden="true"></span>
                    <p class="mpm-pop-name"><b>{{ $p['name'] }}</b><small>{{ $p['kind'] }}</small></p>
                    <ul class="mpm-pop-meta">
                        <li data-tone="star">★ {{ $p['rating'] }}</li>
                        <li>{{ $p['hours'] }}</li>
                        <li>{{ $p['dist'] }}</li>
                    </ul>
                    <button type="button" class="mpm-close" x-on:click="open = null"
                        aria-label="{{ $say('Close', 'بستن') }}">×</button>
                </div>
            @endforeach
        </div>

        <ul class="mpm-legend" aria-label="{{ $say('Districts', 'ناحیه‌ها') }}">
            @foreach ($districts as $d)
                <li>
                    <button type="button" class="mpm-chip"
                        x-bind:data-hi="area === '{{ $d['id'] }}' ? 'true' : null"
                        x-on:pointerenter="area = '{{ $d['id'] }}'"
                        x-on:pointerleave="area = null"
                        x-on:click="area = area === '{{ $d['id'] }}' ? null : '{{ $d['id'] }}'">
                        <i aria-hidden="true"></i>{{ $d['label'] }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
</div>
