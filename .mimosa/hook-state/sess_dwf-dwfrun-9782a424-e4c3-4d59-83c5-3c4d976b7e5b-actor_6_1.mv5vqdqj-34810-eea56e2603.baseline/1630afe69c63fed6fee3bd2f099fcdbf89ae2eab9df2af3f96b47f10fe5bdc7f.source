{{--
    Image-backed CTA in its real habitat: the scenic band between the feature
    grid and the footer of a landing page — a hand-drawn SVG dusk (sun, layered
    hills, converging grid) under a dark overlay, a bright headline, three
    trust stats, a glass button, and a pointer parallax that moves each layer
    against the cursor.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .ctim-root { display: grid; gap: 1rem; justify-items: center; font-family: var(--nx-font-sans); }
    .ctim {
        --ctim-veil: linear-gradient(to top, #0a0c17cf, #0a0c177f 52%, #0a0c1742);
        position: relative; overflow: clip; isolation: isolate; color: #fff;
        border-radius: var(--nx-radius-xl); min-block-size: clamp(21rem, 46vw, 27rem);
    }
    .ctim-art { position: absolute; inset: 0; z-index: 0; inline-size: 100%; block-size: 100%; }
    .ctim-art .ctim-stars, .ctim-art .ctim-sun, .ctim-art .ctim-hill-b, .ctim-art .ctim-hill-f {
        transition: transform .35s ease-out;
    }
    .ctim-art .ctim-stars { transform: translate(calc(var(--ctim-x, 0) * 4px), calc(var(--ctim-y, 0) * 3px)); }
    .ctim-art .ctim-sun { transform: translate(calc(var(--ctim-x, 0) * 10px), calc(var(--ctim-y, 0) * 6px)); }
    .ctim-art .ctim-hill-b { transform: translate(calc(var(--ctim-x, 0) * -16px), 0); }
    .ctim-art .ctim-hill-f { transform: translate(calc(var(--ctim-x, 0) * -22px), 0); }
    .ctim-veil { position: absolute; inset: 0; z-index: 1; pointer-events: none; background: var(--ctim-veil); }
    .ctim-body { position: relative; z-index: 2; display: grid; gap: 1.35rem; justify-items: center; text-align: center;
                 padding: clamp(3.5rem, 9vw, 5.5rem) clamp(1.25rem, 5vw, 3rem); }
    .ctim-title { margin: 0; max-inline-size: 20ch; font: 800 clamp(1.8rem, 4.4vw, 2.9rem) / 1.15 var(--nx-font-display);
                  letter-spacing: var(--nx-tracking-tight); text-wrap: balance; }
    .ctim-sub { margin: 0; max-inline-size: 44ch; color: #ffffffbf; font-size: var(--nx-text-md); text-wrap: pretty; }
    .ctim-stats { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem 2.75rem; margin-block-start: .25rem; }
    .ctim-stats div { display: grid; gap: .2rem; }
    .ctim-stats b { font: 800 var(--nx-text-2xl) / 1.1 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); }
    .ctim-stats small { color: #ffffffa8; font-size: .78rem; }
    .ctim-glass { display: inline-flex; align-items: center; gap: .55rem; block-size: 3rem; padding-inline: 1.7rem;
                  border-radius: 999px; background: #ffffff2e; color: #fff;
                  font: 700 var(--nx-text-sm) / 1 var(--nx-font-sans); text-decoration: none; cursor: pointer;
                  box-shadow: inset 0 0 0 1px #ffffff59; backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
                  transition: background-color .18s ease, translate .18s ease, box-shadow .18s ease; }
    .ctim-glass svg { inline-size: 1.05rem; block-size: 1.05rem; }
    html[dir="rtl"] .ctim-glass svg { transform: scaleX(-1); }
    .ctim-glass:hover { background: #ffffff42; translate: 0 -2px; box-shadow: inset 0 0 0 1px #ffffff7d, 0 12px 28px #0a0c174d; }
    .ctim-glass:active { translate: 0 0; }
    .ctim-glass:focus-visible { outline: 2px solid #7df3ff; outline-offset: 3px; }
    html[data-theme="dark"] .ctim { --ctim-veil: linear-gradient(to top, #0a0c17e8, #0a0c178f 52%, #0a0c1755); }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ctim { --ctim-veil: linear-gradient(to top, #0a0c17e8, #0a0c178f 52%, #0a0c1755); }
    }
    @media (prefers-reduced-motion: reduce) {
        .ctim-art .ctim-stars, .ctim-art .ctim-sun, .ctim-art .ctim-hill-b, .ctim-art .ctim-hill-f {
            transition: none; transform: none;
        }
        .ctim-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="ctim-root">
    <section aria-label="{{ $say('Image-backed CTA banner', 'بنر دعوت به کنش تصویری') }}"
        class="ctim"
        x-data="{ x: 0, y: 0 }"
        x-on:pointermove="const r = $el.getBoundingClientRect(); x = Math.max(-1, Math.min(1, (($event.clientX - r.left) / r.width) * 2 - 1)).toFixed(3); y = Math.max(-1, Math.min(1, (($event.clientY - r.top) / r.height) * 2 - 1)).toFixed(3)"
        x-on:pointerleave="x = 0; y = 0"
        :style="'--ctim-x: ' + x + '; --ctim-y: ' + y">
        <svg class="ctim-art" viewBox="0 0 960 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
            <defs>
                <linearGradient id="ctim-sky" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="#1e1a4a"/><stop offset=".55" stop-color="#7c3aed"/><stop offset="1" stop-color="#f472b6"/>
                </linearGradient>
            </defs>
            <rect width="960" height="400" fill="url(#ctim-sky)"/>
            <g class="ctim-stars" fill="#ffffff">
                <circle cx="112" cy="58" r="2" opacity=".8"/><circle cx="318" cy="112" r="1.5" opacity=".6"/>
                <circle cx="508" cy="52" r="1.2" opacity=".5"/><circle cx="702" cy="132" r="1.5" opacity=".5"/>
                <circle cx="836" cy="48" r="2" opacity=".7"/><circle cx="898" cy="150" r="1.2" opacity=".45"/>
            </g>
            <g class="ctim-sun">
                <circle cx="600" cy="192" r="98" fill="#ffd68a" opacity=".22"/>
                <circle cx="600" cy="192" r="58" fill="#ffd68a"/>
            </g>
            <path class="ctim-hill-b" d="M0 292 C160 232 300 268 460 260 C640 250 760 286 960 268 V400 H0 Z" fill="#3b31a3"/>
            <g class="ctim-hill-f">
                <path d="M0 340 C200 296 380 330 560 322 C740 314 840 336 960 326 V400 H0 Z" fill="#1e1a4a"/>
                <g stroke="#7df3ff" stroke-width="1.5" opacity=".18" fill="none">
                    <path d="M480 258 L-60 400"/><path d="M480 258 L160 400"/><path d="M480 258 L400 400"/>
                    <path d="M480 258 L560 400"/><path d="M480 258 L800 400"/><path d="M480 258 L1020 400"/>
                    <path d="M0 356 H960"/><path d="M0 378 H960"/><path d="M0 396 H960"/>
                </g>
            </g>
        </svg>
        <span class="ctim-veil" aria-hidden="true"></span>
        <div class="ctim-body">
            <h3 class="ctim-title">{{ $say('Launch. Watch. Learn.', 'منتشر کن. تماشا کن. یاد بگیر.') }}</h3>
            <p class="ctim-sub">
                {{ $say('The dusk between shipping and knowing — Meridian lights up every rollout as it crosses your fleet.', 'گرگ‌ومیشِ میان انتشار و دانستن — مریدین هر رِلیز را همان‌طور که از ناوگان شما می‌گذرد روشن می‌کند.') }}
            </p>
            <div class="ctim-stats">
                <div><b>{{ $say('99.99%', '۹۹٫۹۹٪') }}</b><small>{{ $say('relay uptime', 'آپ‌تایم شبکهٔ رله') }}</small></div>
                <div><b>{{ $say('38ms', '۳۸ms') }}</b><small>{{ $say('median latency · Frankfurt', 'میانهٔ تأخیر · فرانکفورت') }}</small></div>
                <div><b>{{ $say('4,200', '۴٬۲۰۰') }}</b><small>{{ $say('active teams', 'تیم فعال') }}</small></div>
            </div>
            <a class="ctim-glass" href="/components">
                {{ $say('Start free', 'شروع رایگان') }}{{ \NabuXUI\NabuXUI::icon('arrow-right') }}
            </a>
        </div>
    </section>
    <p style="margin: 0; max-inline-size: 56ch; color: var(--nx-text-muted); font-size: var(--nx-text-sm); text-align: center">
        {{ $say('Move the pointer over the scene — the stars, sun and hills each carry their own factor and drift against the cursor; under prefers-reduced-motion everything holds still.', 'نشانگر را روی صحنه بچرخانید — ستاره‌ها، خورشید و تپه‌ها هر کدام ضریب خودشان را دارند و مخالف موس می‌روند؛ با prefers-reduced-motion همه ساکن می‌مانند.') }}
    </p>
</div>
