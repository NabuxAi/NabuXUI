{{--
    The glass navbar over a colourful hero: 14px of backdrop blur at 140%
    saturation with a one-pixel light-catching rim, links that grow glass
    pills on hover and focus, and on the far side a day/night mood button
    plus a gradient-ringed avatar. The mood flips the hero palette and the
    glass follows — blur is proven by the orbs drifting beneath the bar.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap');
    .gln-root {
        --gln-accent: #4f46e5; --gln-muted: #64748b; --gln-border: #e2e8f0; --gln-ink: #0f172a;
        --gln-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        font-family: var(--gln-font);
        color: var(--gln-ink);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .gln-root {
        --gln-accent: #818cf8; --gln-muted: #94a3b8; --gln-border: #24324f; --gln-ink: #e2e8f0;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .gln-root {
            --gln-accent: #818cf8; --gln-muted: #94a3b8; --gln-border: #24324f; --gln-ink: #e2e8f0;
        }
    }
    .gln-root, .gln-root *, .gln-root *::before, .gln-root *::after { box-sizing: border-box; }
    .gln-root :focus-visible { outline: 2px solid #fff; outline-offset: 2px; }
    [x-cloak] { display: none !important; }

    /* ——— the stage: a colourful hero the glass floats above ——— */
    .gln-stage { position: relative; inline-size: min(100%, 46rem); margin-inline: auto; min-block-size: 26.25rem;
                 overflow: clip; border-radius: 1rem; color: #fff;
                 background: linear-gradient(140deg, #0ea5e9 0%, #818cf8 52%, #f472b6 100%);
                 transition: background 1.2s ease; }
    .gln-stage[data-mood='night'] { background: linear-gradient(140deg, #0b1120 0%, #3730a3 58%, #9d174d 100%); }
    .gln-orb { position: absolute; border-radius: 50%; background: rgba(255, 255, 255, .3);
               filter: blur(2px); animation: gln-drift 16s ease-in-out infinite alternate; pointer-events: none; }
    .gln-orb[data-o='2'] { animation-delay: -6s; background: rgba(255, 255, 255, .18); }
    .gln-orb[data-o='3'] { animation-delay: -11s; background: rgba(255, 255, 255, .22); }
    @keyframes gln-drift {
        from { translate: 0 0; }
        to { translate: 2.5rem -1.75rem; }
    }

    /* ——— the glass itself ——— */
    .gln-bar { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 5;
               display: flex; align-items: center; gap: .75rem 1.25rem; flex-wrap: wrap;
               padding: .8rem 1.25rem; color: #fff;
               background: rgba(255, 255, 255, .12);
               border-block-end: 1px solid rgba(255, 255, 255, .18);
               backdrop-filter: blur(14px) saturate(140%);
               -webkit-backdrop-filter: blur(14px) saturate(140%);
               box-shadow: inset 0 1px 0 rgba(255, 255, 255, .35); }
    .gln-brand { display: inline-flex; align-items: center; gap: .45rem; font-weight: 700; font-size: .95rem; }
    .gln-brand i { inline-size: 1.15rem; block-size: 1.15rem; border-radius: .35rem;
                   background: rgba(255, 255, 255, .3); box-shadow: inset 0 1px 0 rgba(255, 255, 255, .45); }
    .gln-bar nav { display: flex; align-items: center; gap: .125rem; flex-wrap: wrap; }
    .gln-bar nav a { padding: .5rem .9rem; border-radius: 999px; color: rgba(255, 255, 255, .88);
                     font: 500 .84rem/1 var(--gln-font); text-decoration: none; white-space: nowrap;
                     transition: background-color .18s ease, box-shadow .18s ease, color .18s ease; }
    .gln-bar nav a:hover, .gln-bar nav a:focus-visible { color: #fff; background: rgba(255, 255, 255, .16);
                     box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
    .gln-bar nav a[aria-current='page'] { color: #fff; background: rgba(255, 255, 255, .2); font-weight: 600; }
    .gln-end { margin-inline-start: auto; display: inline-flex; align-items: center; gap: .6rem; }
    .gln-mood { display: grid; place-items: center; inline-size: 2.15rem; block-size: 2.15rem; border: 0;
                border-radius: 999px; background: rgba(255, 255, 255, .16); color: #fff; cursor: pointer;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); transition: background-color .18s ease, translate .18s ease; }
    .gln-mood:hover { background: rgba(255, 255, 255, .26); }
    .gln-mood:active { translate: 0 1px; }
    .gln-mood .nx-icon { inline-size: 1.05rem; block-size: 1.05rem; }
    .gln-ava { position: relative; display: grid; place-items: center; inline-size: 2.15rem; block-size: 2.15rem;
               border-radius: 50%; background: rgba(255, 255, 255, .18); font-size: .72rem; font-weight: 700;
               letter-spacing: .02em; }
    .gln-ava::after { content: ''; position: absolute; inset: -3px; border-radius: 50%; z-index: -1;
                      background: conic-gradient(#22d3ee, #818cf8, #f472b6, #22d3ee); }

    /* ——— hero content beneath the glass ——— */
    .gln-hero { position: relative; z-index: 1; padding: 10.5rem 1.5rem 3rem; text-align: center;
                display: grid; gap: .9rem; justify-items: center; }
    .gln-hero h4 { margin: 0; font-size: clamp(1.3rem, 3.4vw, 1.9rem); font-weight: 800; letter-spacing: -.02em;
                   text-wrap: balance; }
    .gln-hero > p { margin: 0; max-inline-size: 42ch; font-size: .92rem; line-height: 1.7; opacity: .92; }
    .gln-chips { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; }
    .gln-chip { display: inline-flex; align-items: center; gap: .35rem; padding: .45rem .85rem; border-radius: 999px;
                background: rgba(255, 255, 255, .14); border: 1px solid rgba(255, 255, 255, .22);
                backdrop-filter: blur(8px); box-shadow: inset 0 1px 0 rgba(255, 255, 255, .25);
                font: 500 .76rem/1 var(--gln-font); }
    .gln-chip .nx-icon { inline-size: .95em; block-size: .95em; }
    .gln-strip { position: relative; z-index: 1; display: grid; grid-template-columns: repeat(auto-fit, minmax(min(10rem, 100%), 1fr));
                 gap: .6rem; padding: 0 1.25rem 1.75rem; }
    .gln-tile { padding: .85rem .95rem; border-radius: .85rem; background: rgba(255, 255, 255, .12);
                border: 1px solid rgba(255, 255, 255, .2); backdrop-filter: blur(10px);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .28); }
    .gln-tile b { display: flex; align-items: center; gap: .4rem; font-size: .82rem; }
    .gln-tile b .nx-icon { inline-size: 1rem; block-size: 1rem; }
    .gln-tile span { display: block; margin-block-start: .3rem; font-size: .74rem; opacity: .85; }

    /* ——— specimen: both moods and the glass states ——— */
    .gln-spec { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .gln-spec-cell { display: grid; gap: .5rem; justify-items: center; }
    .gln-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .gln-mini { position: relative; inline-size: min(19rem, 100%); overflow: clip; border-radius: .9rem;
                background: linear-gradient(140deg, #0ea5e9, #818cf8 52%, #f472b6); }
    .gln-mini[data-mood='night'] { background: linear-gradient(140deg, #0b1120, #3730a3 58%, #9d174d); }
    .gln-mini .gln-bar { position: static; }
    :where(.nx-js) .pg:has(.gln-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .gln-bar nav a:not([aria-current='page']) { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .gln-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<div class="gln-root" x-data="{ mood: 'day', tab: 'product' }">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Glass that proves itself by what shines through', 'شیشه‌ای که آنچه از آن می‌گذرد، خودش را اثبات می‌کند') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Blurring 14px at 140% saturation over a hero that keeps moving — links grow glass pills on hover, and the mood button flips day to night while the avatar holds its gradient ring.', 'بلور ۱۴ پیکسلی با اشباع ۱۴۰٪ روی هیرویی که حرکت می‌کند — لینک‌ها با هاور پیل شیشه‌ای می‌گیرند و دکمهٔ تم روز را به شب می‌برد، در حالی که آواتار حلقهٔ گرادیانی‌اش را نگه می‌دارد.') }}
            </p>
        </div>

        <div class="gln-stage" x-bind:data-mood="mood" role="img"
            aria-label="{{ $say('Simulated landing page with a glass navbar', 'صفحهٔ فرود شبیه‌سازی‌شده با نوبار شیشه‌ای') }}">
            <span class="gln-orb" data-o="1" style="inset-block-start: 4.5rem; inset-inline-start: 10%; inline-size: 6.5rem; block-size: 6.5rem;" aria-hidden="true"></span>
            <span class="gln-orb" data-o="2" style="inset-block-start: 9rem; inset-inline-end: 8%; inline-size: 4.5rem; block-size: 4.5rem;" aria-hidden="true"></span>
            <span class="gln-orb" data-o="3" style="inset-block-end: 5rem; inset-inline-start: 55%; inline-size: 3.5rem; block-size: 3.5rem;" aria-hidden="true"></span>

            <header class="gln-bar">
                <b class="gln-brand"><i aria-hidden="true"></i>{{ $say('Meridian', 'مریدین') }}</b>
                <nav aria-label="{{ $say('Primary', 'اصلی') }}">
                    <a href="#product" x-bind:aria-current="tab === 'product' ? 'page' : null" x-on:click.prevent="tab = 'product'">{{ $say('Product', 'محصول') }}</a>
                    <a href="#pricing" x-bind:aria-current="tab === 'pricing' ? 'page' : null" x-on:click.prevent="tab = 'pricing'">{{ $say('Pricing', 'قیمت') }}</a>
                    <a href="#docs" x-bind:aria-current="tab === 'docs' ? 'page' : null" x-on:click.prevent="tab = 'docs'">{{ $say('Docs', 'مستندات') }}</a>
                </nav>
                <span class="gln-end">
                    <button type="button" class="gln-mood"
                        x-bind:aria-label="mood === 'day' ? '{{ $say('Night mood', 'حالت شب') }}' : '{{ $say('Day mood', 'حالت روز') }}'"
                        x-on:click="mood = mood === 'day' ? 'night' : 'day'">
                        <x-nx::icon name="moon" x-show="mood === 'day'" />
                        <x-nx::icon name="sun" x-show="mood === 'night'" x-cloak />
                    </button>
                    <span class="gln-ava" title="{{ $say('Lena Kovács', 'لنا کواچ') }}">LK</span>
                </span>
            </header>

            <div class="gln-hero">
                <h4>{{ $say('Latency you can feel, dashboards you can read', 'تأخیری که حس می‌شود، داشبوردی که خوانده می‌شود') }}</h4>
                <p>{{ $say('Meridian watches every rollout from Frankfurt to Singapore — and speaks human when something dips.', 'مریدین هر انتشار را از فرانکفورت تا سنگاپور زیر نظر دارد — و وقتی چیزی افت می‌کند، آدمیزاد حرف می‌زند.') }}</p>
                <div class="gln-chips">
                    <span class="gln-chip"><x-nx::icon name="globe" />{{ $num('12') }} {{ $say('regions', 'ناحیه') }}</span>
                    <span class="gln-chip"><x-nx::icon name="zap" />{{ $num('214') }}ms p95</span>
                    <span class="gln-chip"><x-nx::icon name="shield" />SOC 2</span>
                </div>
            </div>

            <div class="gln-strip" aria-hidden="true">
                <div class="gln-tile"><b><x-nx::icon name="chart" />{{ $say('Throughput', 'توان عبوری') }}</b><span>{{ $num('3.2') }}M spans/s</span></div>
                <div class="gln-tile"><b><x-nx::icon name="trend-up" />{{ $say('Deploys today', 'استقرار امروز') }}</b><span>{{ $num('48') }}</span></div>
                <div class="gln-tile"><b><x-nx::icon name="bell" />{{ $say('Open incidents', 'رخداد باز') }}</b><span>{{ $num('0') }}</span></div>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Day glass, night glass', 'شیشهٔ روز، شیشهٔ شب') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('The same bar over both hero palettes — hover the links and press the mood button; the pills stay glass either way.', 'همان نوار روی هر دو پالت هیرو — لینک‌ها را هاور کنید و دکمهٔ تم را بزنید؛ پیل‌ها در هر دو حالت شیشه می‌مانند.') }}
            </p>
        </div>
        <div class="gln-spec">
            <div class="gln-spec-cell">
                <span class="gln-mini" data-mood="day" aria-hidden="true">
                    <span class="gln-bar">
                        <span class="gln-brand"><i></i>{{ $say('Meridian', 'مریدین') }}</span>
                        <nav>
                            <a href="#product" aria-current="page" tabindex="-1" style="cursor: default">{{ $say('Product', 'محصول') }}</a>
                            <a href="#pricing" tabindex="-1" style="cursor: default">{{ $say('Pricing', 'قیمت') }}</a>
                        </nav>
                        <span class="gln-end">
                            <span class="gln-mood" style="cursor: default"><x-nx::icon name="moon" /></span>
                            <span class="gln-ava">LK</span>
                        </span>
                    </span>
                </span>
                <small>mood = day</small>
            </div>
            <div class="gln-spec-cell">
                <span class="gln-mini" data-mood="night" aria-hidden="true">
                    <span class="gln-bar">
                        <span class="gln-brand"><i></i>{{ $say('Meridian', 'مریدین') }}</span>
                        <nav>
                            <a href="#product" aria-current="page" tabindex="-1" style="cursor: default">{{ $say('Product', 'محصول') }}</a>
                            <a href="#pricing" tabindex="-1" style="cursor: default">{{ $say('Pricing', 'قیمت') }}</a>
                        </nav>
                        <span class="gln-end">
                            <span class="gln-mood" style="cursor: default"><x-nx::icon name="sun" /></span>
                            <span class="gln-ava">LK</span>
                        </span>
                    </span>
                </span>
                <small>mood = night</small>
            </div>
        </div>
    </section>
</div>
