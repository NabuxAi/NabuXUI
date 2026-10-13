{{--
    The logos section: the trust band of Beacon's landing page. Eight brand
    marks rest in grayscale and take their colour back on hover; one switch
    turns the same grid into a seamless slow marquee (hover holds it), and
    the fine stats row beneath carries the proof.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $logos = [
        ['id' => 'lumina', 'name' => 'Lumina', 'color' => '#0EA5E9', 'mark' => 'ring'],
        ['id' => 'orbital', 'name' => 'Orbital', 'color' => '#8B5CF6', 'mark' => 'orbit'],
        ['id' => 'kanso', 'name' => 'Kanso', 'color' => '#57534E', 'mark' => 'enso'],
        ['id' => 'vantage', 'name' => 'Vantage', 'color' => '#2563EB', 'mark' => 'peak'],
        ['id' => 'helios', 'name' => 'Helios', 'color' => '#F59E0B', 'mark' => 'sun'],
        ['id' => 'northwind', 'name' => 'Northwind', 'color' => '#475569', 'mark' => 'north'],
        ['id' => 'quantia', 'name' => 'Quantia', 'color' => '#DB2777', 'mark' => 'wave'],
        ['id' => 'fabrik', 'name' => 'Fabrik', 'color' => '#EA580C', 'mark' => 'grid'],
    ];

    $stats = [
        ['value' => $num('8,200').'+', 'label' => $say('teams on board', 'تیم روی برد')],
        ['value' => $num('142'), 'label' => $say('countries served', 'کشور تحت پوشش')],
        ['value' => $num('99.99').'%', 'label' => $say('measured uptime', 'آپتایم اندازه‌گیری‌شده')],
        ['value' => $num('4.9'), 'label' => $say('average review score', 'میانگین امتیاز نظرها')],
    ];
@endphp
<style>
    .seclg-root {
        --seclg-page: #FFFFFF; --seclg-border: #E7E5E4; --seclg-text: #1C1917;
        --seclg-muted: #78716C; --seclg-chip: #FAFAF9;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--seclg-text);
        display: grid;
        gap: 2rem;
    }
    html[data-theme="dark"] .seclg-root {
        --seclg-page: #0C0A09; --seclg-border: #292524; --seclg-text: #FAFAF9;
        --seclg-muted: #A8A29E; --seclg-chip: #1C1917;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .seclg-root {
            --seclg-page: #0C0A09; --seclg-border: #292524; --seclg-text: #FAFAF9;
            --seclg-muted: #A8A29E; --seclg-chip: #1C1917;
        }
    }
    .seclg {
        inline-size: 100%; padding: clamp(1.75rem, 4.5vw, 3.25rem) clamp(1.25rem, 4vw, 2.5rem);
        border: 1px solid var(--seclg-border); border-radius: 1.4rem; background: var(--seclg-page);
        display: grid; gap: 1.9rem; justify-items: center; overflow: clip;
    }
    .seclg-head { display: grid; gap: .6rem; justify-items: center; text-align: center; }
    .seclg-head p { margin: 0; font-size: .78rem; font-weight: 700; letter-spacing: .14em;
                    text-transform: uppercase; color: var(--seclg-muted); }
    .seclg-head h3 { margin: 0; font-size: clamp(1.2rem, 2.6vw, 1.65rem); font-weight: 800; letter-spacing: -.02em; }
    .seclg-switch { display: inline-flex; padding: .25rem; gap: .25rem; border: 1px solid var(--seclg-border);
                    border-radius: 999px; background: var(--seclg-chip); }
    .seclg-switch button { padding: .42rem 1.05rem; border: none; border-radius: 999px; background: transparent;
                           font: 600 .78rem/1 inherit; color: var(--seclg-muted); cursor: pointer;
                           transition: background-color .25s ease, color .25s ease; }
    .seclg-switch button[aria-pressed="true"] { background: var(--seclg-text); color: var(--seclg-page); }
    .seclg-switch button:focus-visible { outline: 2px solid var(--seclg-text); outline-offset: 2px; }

    .seclg-logo { display: inline-flex; align-items: center; gap: .55rem; color: var(--seclg-brand, currentColor);
                  font-weight: 700; font-size: 1.02rem; letter-spacing: -.01em; white-space: nowrap;
                  filter: grayscale(1); opacity: .55;
                  transition: filter .3s ease, opacity .3s ease; }
    .seclg-logo:hover, .seclg-logo:focus-visible { filter: grayscale(0); opacity: 1; }
    .seclg-logo svg { inline-size: 1.5rem; block-size: 1.5rem; flex: none; }

    .seclg-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.6rem 1.25rem;
                  inline-size: 100%; max-inline-size: 58rem; margin: 0; padding: 0; list-style: none;
                  justify-items: center; align-items: center; }
    .seclg-grid .seclg-logo { justify-self: center; }

    .seclg-marquee { inline-size: 100%; max-inline-size: 62rem; overflow: clip;
                     -webkit-mask-image: linear-gradient(to right, transparent, #000 8%, #000 92%, transparent);
                     mask-image: linear-gradient(to right, transparent, #000 8%, #000 92%, transparent); }
    .seclg-track { display: flex; gap: 3.25rem; inline-size: max-content; margin: 0; padding: 0;
                   padding-inline: 1.6rem; list-style: none; animation: seclg-run 36s linear infinite; }
    .seclg-marquee:hover .seclg-track, .seclg-marquee:focus-within .seclg-track { animation-play-state: paused; }
    @keyframes seclg-run { to { transform: translateX(-50%); } }

    .seclg-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem;
                   inline-size: 100%; max-inline-size: 58rem; margin: 0; padding: 1.25rem 0 0;
                   border-block-start: 1px solid var(--seclg-border); list-style: none; }
    .seclg-stats li { display: grid; gap: .2rem; justify-items: center; text-align: center; }
    .seclg-stats b { font-size: clamp(1.25rem, 2.6vw, 1.7rem); font-weight: 800; letter-spacing: -.02em; }
    .seclg-stats small { font-size: .72rem; color: var(--seclg-muted); }
    .seclg-note { margin: 0; font-size: .7rem; color: var(--seclg-muted); }
    @media (max-width: 640px) {
        .seclg-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.4rem 1rem; }
        .seclg-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); row-gap: 1.4rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .seclg-track { animation: none; }
        .seclg-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="seclg-root" x-data="{ scroll: false }">
    <section class="seclg" aria-labelledby="seclg-title">
        <header class="seclg-head">
            <p>{{ $say('Trusted from Oslo to Singapore', 'مورد اعتماد از اسلو تا سنگاپور') }}</p>
            <h3 id="seclg-title">{{ $say('Beacon runs where your team already works', 'بیکِن همان‌جا می‌چرخد که تیم شما کار می‌کند') }}</h3>
        </header>

        <div class="seclg-switch" role="group" aria-label="{{ $say('Logo display mode', 'حالت نمایش لوگوها') }}">
            <button type="button" :aria-pressed="String(!scroll)" x-on:click="scroll = false">{{ $say('Grid', 'گرید') }}</button>
            <button type="button" :aria-pressed="String(scroll)" x-on:click="scroll = true">{{ $say('Slow scroll', 'اسکرول آرام') }}</button>
        </div>

        <ul class="seclg-grid" x-show="!scroll">
            @foreach ($logos as $logo)
                <li class="seclg-logo" tabindex="0" style="--seclg-brand: {{ $logo['color'] }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        @if ($logo['mark'] === 'ring')
                            <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="2.4"/>
                        @elseif ($logo['mark'] === 'orbit')
                            <circle cx="12" cy="12" r="4" fill="currentColor"/><ellipse cx="12" cy="12" rx="10" ry="4.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                        @elseif ($logo['mark'] === 'enso')
                            <path d="M17.5 7A8 8 0 1 0 19 13.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                        @elseif ($logo['mark'] === 'peak')
                            <path d="M3 19L10 5l4 7 3-4 4 11z" fill="currentColor"/>
                        @elseif ($logo['mark'] === 'sun')
                            <circle cx="12" cy="12" r="4.5" fill="currentColor"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        @elseif ($logo['mark'] === 'north')
                            <path d="M12 3l7 18-7-4-7 4z" fill="currentColor"/>
                        @elseif ($logo['mark'] === 'wave')
                            <path d="M2 14c2.5-6 5-6 7.5 0s5 6 7.5 0 5-6 5-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                        @else
                            <rect x="3" y="3" width="8" height="8" fill="currentColor"/><rect x="13" y="13" width="8" height="8" fill="currentColor"/>
                        @endif
                    </svg>
                    <span dir="ltr">{{ $logo['name'] }}</span>
                </li>
            @endforeach
        </ul>

        <div class="seclg-marquee" x-show="scroll" x-cloak dir="ltr">
            <ul class="seclg-track">
                @foreach ([false, true] as $ghost)
                    @foreach ($logos as $logo)
                        <li class="seclg-logo" tabindex="{{ $ghost ? '-1' : '0' }}" @if ($ghost) aria-hidden="true" @endif
                            style="--seclg-brand: {{ $logo['color'] }}">
                            <svg viewBox="0 0 24 24" @if (! $ghost) aria-hidden="true" @endif>
                                @if ($logo['mark'] === 'ring')
                                    <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="2.4"/>
                                @elseif ($logo['mark'] === 'orbit')
                                    <circle cx="12" cy="12" r="4" fill="currentColor"/><ellipse cx="12" cy="12" rx="10" ry="4.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                @elseif ($logo['mark'] === 'enso')
                                    <path d="M17.5 7A8 8 0 1 0 19 13.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                                @elseif ($logo['mark'] === 'peak')
                                    <path d="M3 19L10 5l4 7 3-4 4 11z" fill="currentColor"/>
                                @elseif ($logo['mark'] === 'sun')
                                    <circle cx="12" cy="12" r="4.5" fill="currentColor"/><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                @elseif ($logo['mark'] === 'north')
                                    <path d="M12 3l7 18-7-4-7 4z" fill="currentColor"/>
                                @elseif ($logo['mark'] === 'wave')
                                    <path d="M2 14c2.5-6 5-6 7.5 0s5 6 7.5 0 5-6 5-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                                @else
                                    <rect x="3" y="3" width="8" height="8" fill="currentColor"/><rect x="13" y="13" width="8" height="8" fill="currentColor"/>
                                @endif
                            </svg>
                            <span>{{ $logo['name'] }}</span>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>

        <ul class="seclg-stats">
            @foreach ($stats as $stat)
                <li><b>{{ $stat['value'] }}</b><small>{{ $stat['label'] }}</small></li>
            @endforeach
        </ul>
        <p class="seclg-note">{{ $say('All marks belong to their owners. Numbers measured October 2026 across 34 regions.', 'همهٔ نشان‌ها متعلق به صاحبانشان است. اعداد مهر ۲۰۲۶ در ۳۴ ناحیه اندازه‌گیری شده‌اند.') }}</p>
    </section>
</div>
