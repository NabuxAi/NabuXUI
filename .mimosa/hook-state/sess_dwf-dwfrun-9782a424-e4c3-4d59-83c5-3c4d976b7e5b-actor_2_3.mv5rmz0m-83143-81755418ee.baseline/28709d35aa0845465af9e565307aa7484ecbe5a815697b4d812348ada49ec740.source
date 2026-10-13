{{--
    mask-media's real scenarios: the poster word of a design-week exhibition
    with an animated pattern running inside the glyphs — dots, fish-scale
    waves or a checkerboard on a switch — and the sibling line looping a
    harbour-dusk scene gradient. Each pattern marches by exactly one tile a
    step so the loop never shows a seam.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .mmx-root {
        --mmx-bg: #ffffff; --mmx-ink: #1c1917; --mmx-muted: #78716c;
        --mmx-border: #e7e5e4; --mmx-card: #fafaf9;
        --mmx-deep: #0f766e; --mmx-mid: #14b8a6; --mmx-light: #99f6e4;
        display: grid; gap: 2rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--mmx-ink);
    }
    html[data-theme="dark"] .mmx-root {
        --mmx-bg: #171514; --mmx-ink: #fafaf9; --mmx-muted: #a8a29e;
        --mmx-border: #292524; --mmx-card: #1c1917;
        --mmx-deep: #5eead4; --mmx-mid: #2dd4bf; --mmx-light: #134e4a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mmx-root {
            --mmx-bg: #171514; --mmx-ink: #fafaf9; --mmx-muted: #a8a29e;
            --mmx-border: #292524; --mmx-card: #1c1917;
            --mmx-deep: #5eead4; --mmx-mid: #2dd4bf; --mmx-light: #134e4a;
        }
    }
    .mmx-poster {
        display: grid; gap: 1.25rem; justify-items: center; text-align: center;
        padding: clamp(1.75rem, 5vw, 3.25rem) 1.25rem;
        border: 1px solid var(--mmx-border); border-radius: 1.25rem;
        background:
            radial-gradient(90% 120% at 50% -20%, color-mix(in oklab, var(--mmx-deep) 10%, transparent), transparent 70%),
            var(--mmx-bg);
        overflow: clip;
    }
    .mmx-kicker {
        margin: 0; padding: .3rem .85rem; border: 1px solid var(--mmx-border); border-radius: 999px;
        font-size: .74rem; font-weight: 600; color: var(--mmx-muted); letter-spacing: .1em;
    }
    .mmx-word, .mmx-scene {
        margin: 0; color: transparent;
        -webkit-background-clip: text; background-clip: text;
    }
    .mmx-word {
        font-weight: 900; letter-spacing: .015em; line-height: 1.15;
        font-size: clamp(2.7rem, 13vw, 5.75rem); overflow-wrap: anywhere;
        animation: mmx-march 3.2s linear infinite;
    }
    .mmx[data-pat='dots'] .mmx-word {
        background-image: radial-gradient(circle, var(--mmx-deep) 28%, transparent 30%);
        background-size: 18px 18px;
    }
    .mmx[data-pat='waves'] .mmx-word {
        background-image:
            radial-gradient(circle at 50% 110%, transparent 8px, var(--mmx-deep) 9px 11px, transparent 12px),
            radial-gradient(circle at 50% -10%, transparent 8px, var(--mmx-mid) 9px 11px, transparent 12px);
        background-size: 24px 24px;
    }
    .mmx[data-pat='checks'] .mmx-word {
        background: repeating-conic-gradient(var(--mmx-deep) 0 25%, var(--mmx-light) 0 50%) 0 0 / 24px 24px;
    }
    .mmx-scene {
        font-weight: 800; font-size: clamp(.85rem, 2.6vw, 1.05rem);
        background: linear-gradient(100deg, #1e1b4b, #f43f5e, #f59e0b, #f43f5e, #1e1b4b);
        background-size: 200% auto;
        animation: mmx-drift 9s linear infinite;
    }
    .mmx-meta { display: flex; flex-wrap: wrap; justify-content: center; gap: .4rem 1rem; margin: 0; font-size: .78rem; color: var(--mmx-muted); }
    .mmx-chips { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; }
    .mmx-chip {
        display: inline-flex; align-items: center; gap: .5rem; padding: .42rem 1rem;
        border: 1px solid var(--mmx-border); border-radius: 999px; background: var(--mmx-card);
        color: var(--mmx-ink); font: 600 .78rem/1.4 inherit; cursor: pointer;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .mmx-chip i { display: inline-block; inline-size: 1.15rem; block-size: .9rem; border-radius: .25rem; }
    .mmx-chip .mmx-sw-dots { background: radial-gradient(circle, var(--mmx-deep) 32%, transparent 34%); background-size: 7px 7px; background-color: var(--mmx-card); }
    .mmx-chip .mmx-sw-waves {
        background-image:
            radial-gradient(circle at 50% 110%, transparent 3px, var(--mmx-deep) 3.4px 4.4px, transparent 4.8px),
            radial-gradient(circle at 50% -10%, transparent 3px, var(--mmx-mid) 3.4px 4.4px, transparent 4.8px);
        background-size: 9px 9px; background-color: var(--mmx-card);
    }
    .mmx-chip .mmx-sw-checks { background: repeating-conic-gradient(var(--mmx-deep) 0 25%, var(--mmx-light) 0 50%) 0 0 / 9px 9px; }
    .mmx-chip:hover { border-color: var(--mmx-deep); }
    .mmx-chip:focus-visible { outline: 2px solid var(--mmx-deep); outline-offset: 2px; }
    .mmx-chip[aria-pressed='true'] { border-color: var(--mmx-deep); box-shadow: 0 0 0 1px var(--mmx-deep); }
    .mmx-note {
        margin: 0; text-align: center; font-size: .76rem; color: var(--mmx-muted);
        padding: .55rem .95rem; border: 1px dashed var(--mmx-border); border-radius: .85rem;
    }
    @keyframes mmx-march { to { background-position: 24px 24px; } }
    @keyframes mmx-drift { to { background-position: -200% center; } }
    @media (prefers-reduced-motion: reduce) {
        .mmx-word, .mmx-scene { animation: none; }
        .mmx-chip { transition: none; }
    }
</style>

<div class="mmx-root">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The exhibition word, hollowed out', 'واژهٔ نمایشگاهی، از درون خالی شده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The glyphs are a window: whatever media sits behind them shows through, clipped by background-clip. The march advances the background by exactly one tile a step, so dots, scales and checks loop without a seam — and the Persian word carries the same mask untouched.', 'حروف پنجره‌اند: هر رسانه‌ای که پشتشان باشد از میانشان پدیدار می‌شود و با background-clip بریده می‌شود. رژه پس‌زمینه را دقیقاً به اندازهٔ یک کاشی جلو می‌برد، پس نقاط، فلس‌ها و شطرنجی بدون درز می‌چرخند — و واژهٔ فارسی همان ماسک را دست‌نخورده حمل می‌کند.') }}
            </p>
        </div>

        <div class="mmx" x-data="{ pat: 'dots' }" x-bind:data-pat="pat">
            <div class="mmx-poster">
                <p class="mmx-kicker">{{ $say('TIDE & STONE · ROTTERDAM DESIGN WEEK', 'جزر و مد و سنگ · هفته طراحی روتردام') }}</p>
                <h4 class="mmx-word" aria-label="{{ $say('CURRENTS', 'جریان‌ها') }}">{{ $say('CURRENTS', 'جریان‌ها') }}</h4>
                <p class="mmx-scene">{{ $say('Harbour dusk — the scene gradient sibling', 'غروب بندر — خواهرِ گرادیان صحنه‌ای') }}</p>
                <p class="mmx-meta">
                    <span>{{ $say('Oct 14–18 · Fenix Museum', '۱۴ تا ۱۸ اکتبر · موزهٔ فنیکس') }}</span>
                    <span>{{ $say('Curators: Maastricht & Oslo studios', 'سرپرستان: استودیوهای ماستریخت و اسلو') }}</span>
                </p>
            </div>

            <div class="mmx-chips" role="group" aria-label="{{ $say('Pattern inside the letters', 'الگوی درون حروف') }}">
                @foreach ([
                    'dots' => ['Dots', 'نقاط', 'mmx-sw-dots'],
                    'waves' => ['Waves', 'موج', 'mmx-sw-waves'],
                    'checks' => ['Checker', 'شطرنجی', 'mmx-sw-checks'],
                ] as $key => [$en, $faName, $swatch])
                    <button type="button" class="mmx-chip" x-bind:aria-pressed="pat === '{{ $key }}' ? 'true' : 'false'"
                            x-on:click="pat = '{{ $key }}'"><i class="{{ $swatch }}" aria-hidden="true"></i>{{ $say($en, $faName) }}</button>
                @endforeach
            </div>

            <p class="mmx-note">
                {{ $say('Every step moves the background by one full tile of the active pattern — that is the whole secret of the seamless loop.', 'هر گام پس‌زمینه را به اندازهٔ یک کاشی کاملِ الگوی فعال جلو می‌برد — تمام راز حلقهٔ بدون درز همین است.') }}
            </p>
        </div>
    </section>
</div>
