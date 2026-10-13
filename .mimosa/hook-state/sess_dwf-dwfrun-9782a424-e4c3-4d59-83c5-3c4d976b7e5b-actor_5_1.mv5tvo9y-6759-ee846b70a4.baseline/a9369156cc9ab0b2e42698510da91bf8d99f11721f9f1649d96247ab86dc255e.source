{{--
    Fluent's two signature materials over a live wallpaper: a mica card that
    only tints the desktop hue beneath it (for window bodies) and an acrylic
    card that truly blurs what is behind it with 30px blur, 125% saturation
    and a whisper of noise (for flyouts). A toggle swaps the wallpaper hue so
    the mica tint change is visible.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .flmat-root {
        --fl-accent: #005FB8;
        --fl-accent-hover: #1A75C5;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-control-stroke: rgba(0, 0, 0, .12);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-focus: #1B1B1B;
        --fl-on-accent: #FFFFFF;
        --flmat-acrylic: rgba(249, 249, 249, .74);
        --flmat-acrylic-edge: rgba(255, 255, 255, .50);
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        gap: .875rem;
    }
    html[data-theme="dark"] .flmat-root {
        --fl-accent: #4CC2FF;
        --fl-accent-hover: #6BCFFF;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-control-stroke: rgba(255, 255, 255, .10);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-focus: #FFFFFF;
        --fl-on-accent: #000000;
        --flmat-acrylic: rgba(44, 44, 44, .74);
        --flmat-acrylic-edge: rgba(255, 255, 255, .09);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flmat-root {
            --fl-accent: #4CC2FF;
            --fl-accent-hover: #6BCFFF;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-control-stroke: rgba(255, 255, 255, .10);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-focus: #FFFFFF;
            --fl-on-accent: #000000;
            --flmat-acrylic: rgba(44, 44, 44, .74);
            --flmat-acrylic-edge: rgba(255, 255, 255, .09);
        }
    }
    .flmat-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .flmat-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }

    .flmat-stage {
        position: relative; overflow: clip; display: grid; place-items: center;
        min-block-size: 24rem; padding: 2.5rem 1.25rem; border-radius: 8px;
        background: linear-gradient(168deg, #0E1B30, #14263F 55%, #1A3153);
        --flmat-blob1: #2E7CD6; --flmat-blob2: #38C7D8; --flmat-tint: #2A5E9E;
        transition: background .5s;
    }
    .flmat-stage[data-hue="dusk"] {
        background: linear-gradient(168deg, #291A0C, #3F2810 55%, #553716);
        --flmat-blob1: #E09B3C; --flmat-blob2: #C75B33; --flmat-tint: #8A5A26;
    }
    .flmat-blobs { position: absolute; inset: -12%; z-index: 0; filter: blur(42px) saturate(1.25); }
    .flmat-blobs i {
        position: absolute; inset-block-start: -14%; inset-inline-start: -8%;
        inline-size: 52%; aspect-ratio: 1; border-radius: 50%; opacity: .85;
        background: var(--flmat-blob1); transition: background .5s;
        animation: flmat-drift 18s ease-in-out infinite alternate;
    }
    .flmat-blobs i:nth-child(2) { inset-block-start: auto; inset-inline-start: auto; inset-block-end: -12%; inset-inline-end: -6%; background: var(--flmat-blob2); animation-delay: -9s; }
    @keyframes flmat-drift { to { translate: calc(9% * var(--nx-motion)) calc(-8% * var(--nx-motion)); scale: calc(1 + .12 * var(--nx-motion)); } }

    .flmat-cards { position: relative; z-index: 1; display: flex; flex-wrap: wrap; justify-content: center; gap: 1.5rem; }
    .flmat-card {
        position: relative; overflow: clip; inline-size: min(100%, 15rem);
        padding: 1.1rem 1.25rem 1rem; border-radius: 8px;
        box-shadow: 0 24px 48px rgba(0, 0, 0, .35);
    }
    .flmat-card h4 { margin: 0; display: flex; align-items: center; gap: .5rem; font-size: .95rem; }
    .flmat-card p { margin: .4rem 0 0; font-size: .75rem; line-height: 1.9; }
    .flmat-chip { margin-inline-start: auto; padding: .12rem .55rem; border-radius: 999px; font-size: .6rem; white-space: nowrap; }
    .flmat-card[data-kind="mica"] {
        background: color-mix(in srgb, var(--flmat-tint) 50%, var(--fl-window));
        border: 1px solid var(--fl-card-stroke); color: #FFFFFF; transition: background .5s;
    }
    .flmat-card[data-kind="mica"] .flmat-chip { background: rgba(255, 255, 255, .18); color: #FFFFFF; }
    .flmat-card[data-kind="acrylic"] {
        background: var(--flmat-acrylic);
        border: 1px solid var(--flmat-acrylic-edge);
        color: var(--fl-text);
        backdrop-filter: blur(30px) saturate(125%); -webkit-backdrop-filter: blur(30px) saturate(125%);
    }
    .flmat-card[data-kind="acrylic"]::after {
        content: ""; position: absolute; inset: 0; pointer-events: none; opacity: .55;
        background:
            repeating-linear-gradient(0deg, rgba(255, 255, 255, .045) 0 1px, transparent 1px 2px),
            repeating-linear-gradient(90deg, rgba(0, 0, 0, .03) 0 1px, transparent 1px 3px);
    }
    .flmat-card[data-kind="acrylic"] .flmat-chip { background: var(--fl-hover); color: var(--fl-text-2); }
    @supports not ((backdrop-filter: blur(1px)) or (-webkit-backdrop-filter: blur(1px))) {
        .flmat-card[data-kind="acrylic"] { background: #F3F3F3; }
    }
    @supports not ((backdrop-filter: blur(1px)) or (-webkit-backdrop-filter: blur(1px))) {
        html[data-theme="dark"] .flmat-card[data-kind="acrylic"] { background: #202020; }
    }
    @media (prefers-color-scheme: dark) {
        @supports not ((backdrop-filter: blur(1px)) or (-webkit-backdrop-filter: blur(1px))) {
            html:not([data-theme="light"]) .flmat-card[data-kind="acrylic"] { background: #202020; }
        }
    }

    .flmat-controls {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: .6rem; font-size: .75rem; color: var(--fl-text-2);
    }
    .flmat-seg { display: inline-flex; gap: 3px; padding: 3px; border-radius: 6px; background: var(--fl-hover); box-shadow: inset 0 0 0 1px var(--fl-card-stroke); }
    .flmat-seg button { padding: .3rem .8rem; border-radius: 4px; font-size: .75rem; }
    .flmat-seg button[aria-pressed="true"] { background: var(--fl-accent); color: var(--fl-on-accent); font-weight: 600; }
    .flmat-note { margin: 0; text-align: center; font-size: .72rem; line-height: 1.8; color: var(--fl-text-2); }

    @media (prefers-reduced-motion: reduce) {
        .flmat-blobs i { animation: none; }
        .flmat-stage, .flmat-blobs i, .flmat-card[data-kind="mica"] { transition: none; }
    }

    /* The props table of this demo page (rendered by the shared component-demo
       wrapper) ships with data-nx-reveal, but nothing on the playground wires
       the reveal observer to it — the Alpine nx-reveal directive is never
       placed on the table, and Alpine booting adds .nx-live, which silences
       the 2.5s CSS failsafe. Until the table is scrolled into view its tbody
       rows sit at opacity: 0, so any full-page capture shows only the header
       row with an empty gap under it. Page-local patch: show the rows
       outright. The selector matches only the not-yet-revealed state, so it
       stops applying the moment a real reveal lands. */
    [aria-labelledby="demo-props-title"] .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr {
        opacity: 1;
        translate: none;
    }

    /* Same table on narrow screens: the cells are nowrap by default, so the
       four columns cannot fit a phone viewport and the last header cell was
       clipped mid-letter at the page edge. Let cells wrap at spaces, and let
       the long "What it does" column break inside words too (tokens like
       backdrop-filter) — on wide screens every column still gets its full
       single-line width, so nothing changes there. Ease the cell padding
       under 30rem so all four columns truly fit 375px. */
    [aria-labelledby="demo-props-title"] .nx-data-table th,
    [aria-labelledby="demo-props-title"] .nx-data-table td {
        white-space: normal;
    }
    [aria-labelledby="demo-props-title"] .nx-data-table th:last-child,
    [aria-labelledby="demo-props-title"] .nx-data-table td:last-child {
        overflow-wrap: anywhere;
    }
    @media (max-width: 30rem) {
        [aria-labelledby="demo-props-title"] .nx-data-table th,
        [aria-labelledby="demo-props-title"] .nx-data-table td {
            padding: .625rem .75rem;
        }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Two materials, one wallpaper', 'دو متریال، یک کاغذدیواری') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Mica only tints the desktop beneath it — it belongs to window bodies. Acrylic truly blurs what is behind it — it belongs to flyouts. Swap the wallpaper and watch the mica tint follow.', 'میکا فقط رنگ دسکتاپ را ته‌مایه می‌گیرد و جای پنجره‌هاست. آکریلیک پشتِ خودش را واقعاً بلور می‌کند و جای فای‌اوت‌هاست. کاغذدیواری را عوض کنید و رنگ‌گرفتن میکا را ببینید.') }}
        </p>
    </div>

    <div class="flmat-root" x-data="{ hue: 'sea' }">
        <div class="flmat-stage" :data-hue="hue">
            <div class="flmat-blobs" aria-hidden="true"><i></i><i></i></div>
            <div class="flmat-cards">
                <article class="flmat-card" data-kind="mica">
                    <h4>{{ $say('Mica', 'میکا') }} <span class="flmat-chip">{{ $say('for windows', 'برای پنجره‌ها') }}</span></h4>
                    <p>{{ $say('A 50% tint of the desktop colour over a neutral — no blur. It keeps every window rooted in the machine’s wallpaper.', 'تینت ۵۰٪ از رنگ دسکتاپ روی یک سطح خنثی؛ بدون بلور. هر پنجره را به کاغذدیواری همان دستگاه گره می‌زند.') }}</p>
                </article>
                <article class="flmat-card" data-kind="acrylic">
                    <h4>{{ $say('Acrylic', 'آکریلیک') }} <span class="flmat-chip">{{ $say('for flyouts', 'برای فای‌اوت‌ها') }}</span></h4>
                    <p>{{ $say('A 30px blur + 125% saturation + fine noise to fight banding. Content behind it stays honestly, softly visible.', 'بلور ۳۰ پیکسل + اشباع ۱۲۵٪ + نویز ظریف برای جلوگیری از banding. آنچه پشتش است، نرم و واقعی دیده می‌شود.') }}</p>
                </article>
            </div>
        </div>

        <div class="flmat-controls">
            <span>{{ $say('Wallpaper', 'کاغذدیواری') }}</span>
            <div class="flmat-seg" role="group" aria-label="{{ $say('Wallpaper hue', 'رنگ کاغذدیواری') }}">
                <button type="button" :aria-pressed="hue === 'sea' ? 'true' : 'false'" x-on:click="hue = 'sea'">{{ $say('Ocean blue', 'آبی اقیانوسی') }}</button>
                <button type="button" :aria-pressed="hue === 'dusk' ? 'true' : 'false'" x-on:click="hue = 'dusk'">{{ $say('Sandy dusk', 'غروب شنی') }}</button>
            </div>
        </div>
        <p class="flmat-note">
            {{ $say('The mica card re-tints with the wallpaper; the acrylic card keeps blurring it. Where backdrop-filter is missing, a solid colour stands in.', 'کارت میکا با کاغذدیواری دوباره رنگ می‌گیرد؛ کارت آکریلیک به بلور کردن ادامه می‌دهد. هرجا backdrop-filter نبود، رنگ ثابت جایگزین می‌شود.') }}
        </p>
    </div>
</section>
