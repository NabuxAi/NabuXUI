{{--
    Material You's dynamic color: six seed swatches (plus a free picker) set
    one --m3dyn-seed on the root; eight roles derive from it with color-mix
    in oklab and the staged mini-app — wallpaper, bar, card, chips, switch,
    progress and FAB — recolours in one animated sweep (the seed interpolates
    via @property). The dark mirror derives from the same seed.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    @property --m3dyn-seed { syntax: '<color>'; inherits: true; initial-value: #6750A4; }
    .m3dyn-root {
        --m3dyn-seed: #6750A4;
        --m3dyn-primary: var(--m3dyn-seed);
        --m3dyn-on-primary: #FFFFFF;
        --m3dyn-primary-container: color-mix(in oklab, var(--m3dyn-seed) 18%, #FFFFFF);
        --m3dyn-on-primary-container: color-mix(in oklab, var(--m3dyn-seed) 62%, #14101F);
        --m3dyn-secondary-container: color-mix(in oklab, var(--m3dyn-seed) 12%, #F5F0F6);
        --m3dyn-on-secondary-container: color-mix(in oklab, var(--m3dyn-seed) 45%, #1D192B);
        --m3dyn-surface: color-mix(in oklab, var(--m3dyn-seed) 4%, #FFFFFF);
        --m3dyn-outline: color-mix(in oklab, var(--m3dyn-seed) 25%, #CAC4D0);
        --m3dyn-on-surface-variant: color-mix(in oklab, var(--m3dyn-seed) 32%, #49454F);
        transition: --m3dyn-seed .6s ease;
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3dyn-root {
        --m3dyn-primary: color-mix(in oklab, var(--m3dyn-seed) 55%, #FFFFFF);
        --m3dyn-on-primary: color-mix(in oklab, var(--m3dyn-seed) 70%, #141218);
        --m3dyn-primary-container: color-mix(in oklab, var(--m3dyn-seed) 38%, #211F26);
        --m3dyn-on-primary-container: color-mix(in oklab, var(--m3dyn-seed) 22%, #EADDFF);
        --m3dyn-secondary-container: color-mix(in oklab, var(--m3dyn-seed) 24%, #211F26);
        --m3dyn-on-secondary-container: color-mix(in oklab, var(--m3dyn-seed) 18%, #E8DEF8);
        --m3dyn-surface: color-mix(in oklab, var(--m3dyn-seed) 6%, #141218);
        --m3dyn-outline: color-mix(in oklab, var(--m3dyn-seed) 25%, #49454F);
        --m3dyn-on-surface-variant: color-mix(in oklab, var(--m3dyn-seed) 22%, #CAC4D0);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3dyn-root {
            --m3dyn-primary: color-mix(in oklab, var(--m3dyn-seed) 55%, #FFFFFF);
            --m3dyn-on-primary: color-mix(in oklab, var(--m3dyn-seed) 70%, #141218);
            --m3dyn-primary-container: color-mix(in oklab, var(--m3dyn-seed) 38%, #211F26);
            --m3dyn-on-primary-container: color-mix(in oklab, var(--m3dyn-seed) 22%, #EADDFF);
            --m3dyn-secondary-container: color-mix(in oklab, var(--m3dyn-seed) 24%, #211F26);
            --m3dyn-on-secondary-container: color-mix(in oklab, var(--m3dyn-seed) 18%, #E8DEF8);
            --m3dyn-surface: color-mix(in oklab, var(--m3dyn-seed) 6%, #141218);
            --m3dyn-outline: color-mix(in oklab, var(--m3dyn-seed) 25%, #49454F);
            --m3dyn-on-surface-variant: color-mix(in oklab, var(--m3dyn-seed) 22%, #CAC4D0);
        }
    }
    .m3dyn-swatches { display: flex; flex-wrap: wrap; gap: .8rem; justify-content: center; align-items: center; }
    .m3dyn-swatch { position: relative; display: grid; place-items: center; inline-size: 2.5rem; aspect-ratio: 1; border: none; border-radius: 50%; cursor: pointer; transition: box-shadow .2s ease; }
    .m3dyn-swatch:focus-visible { outline: 2px solid var(--nx-text); outline-offset: 3px; }
    .m3dyn-swatch[aria-pressed='true'] { box-shadow: 0 0 0 2px var(--m3dyn-surface), 0 0 0 4px currentColor; }
    .m3dyn-swatch svg { color: #ffffff; filter: drop-shadow(0 1px 1px rgba(0, 0, 0, .5)); opacity: 0; scale: .5; transition: opacity .15s, scale .2s cubic-bezier(.2, 0, 0, 1); }
    .m3dyn-swatch[aria-pressed='true'] svg { opacity: 1; scale: 1; }
    .m3dyn-custom { inline-size: 2.5rem; aspect-ratio: 1; border: none; border-radius: 50%; padding: 0; cursor: pointer; background: conic-gradient(#f43f5e, #f59e0b, #10b981, #3b82f6, #8b5cf6, #f43f5e); overflow: clip; }
    .m3dyn-custom::-webkit-color-swatch-wrapper { padding: 0; }
    .m3dyn-custom::-webkit-color-swatch { border: none; border-radius: 50%; opacity: .55; }
    .m3dyn-custom::-moz-color-swatch { border: none; border-radius: 50%; opacity: .55; }
    .m3dyn-custom:focus-visible { outline: 2px solid var(--nx-text); outline-offset: 3px; }
    .m3dyn-app { position: relative; inline-size: min(100%, 22rem); border-radius: 1.75rem; overflow: clip; background: var(--m3dyn-surface); border: 1px solid var(--m3dyn-outline); box-shadow: 0 16px 32px -20px rgba(0, 0, 0, .4); }
    .m3dyn-app * { transition: background-color .6s ease, color .6s ease, border-color .6s ease, box-shadow .3s ease; }
    .m3dyn-wall { display: flex; align-items: flex-end; block-size: 3.4rem; padding: .5rem .9rem; background: linear-gradient(120deg, var(--m3dyn-seed), color-mix(in oklab, var(--m3dyn-seed) 45%, #FDF7FF)); }
    .m3dyn-wall small { margin-inline-start: auto; padding: .25rem .6rem; border-radius: 999px; background: rgba(255, 255, 255, .35); color: #1D1B20; font: 500 .68rem/1 Roboto, system-ui, sans-serif; backdrop-filter: blur(4px); }
    .m3dyn-bar { display: flex; align-items: center; gap: .6rem; padding: .8rem 1.1rem; background: var(--m3dyn-primary); color: var(--m3dyn-on-primary); }
    .m3dyn-bar h4 { margin: 0; font: 500 1.05rem/1.2 Roboto, system-ui, sans-serif; }
    .m3dyn-bar svg:last-child { margin-inline-start: auto; }
    .m3dyn-body { display: grid; gap: .8rem; padding: 1rem 1.1rem 4.2rem; }
    .m3dyn-card { display: grid; gap: .3rem; padding: .9rem 1rem; border-radius: 1rem; background: var(--m3dyn-secondary-container); color: var(--m3dyn-on-secondary-container); }
    .m3dyn-card b { font: 500 .92rem/1.3 Roboto, system-ui, sans-serif; }
    .m3dyn-card small { font: 400 .76rem/1.4 Roboto, system-ui, sans-serif; opacity: .85; }
    .m3dyn-card button { justify-self: start; margin-block-start: .35rem; border: none; border-radius: 999px; padding: .45rem .8rem; background: transparent; color: var(--m3dyn-primary); cursor: pointer; font: 600 .8rem/1 Roboto, system-ui, sans-serif; }
    .m3dyn-card button:hover { background: color-mix(in srgb, currentColor 10%, transparent); }
    .m3dyn-card button:focus-visible { outline: 2px solid var(--m3dyn-primary); outline-offset: 2px; }
    .m3dyn-chips { display: flex; flex-wrap: wrap; gap: .5rem; }
    .m3dyn-chip { display: inline-flex; align-items: center; gap: .4rem; block-size: 2rem; padding-inline: .95rem; border-radius: .5rem; border: 1px solid var(--m3dyn-outline); background: transparent; color: var(--m3dyn-on-surface-variant); cursor: pointer; font: 500 .78rem/1 Roboto, system-ui, sans-serif; }
    .m3dyn-chip:focus-visible { outline: 2px solid var(--m3dyn-primary); outline-offset: 2px; }
    .m3dyn-chip[aria-pressed='true'] { background: var(--m3dyn-secondary-container); border-color: transparent; color: var(--m3dyn-on-secondary-container); }
    .m3dyn-chip svg { color: var(--m3dyn-primary); }
    .m3dyn-tune { display: flex; align-items: center; gap: .9rem; }
    .m3dyn-tune b { font: 500 .85rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3dyn-on-surface-variant); }
    .m3dyn-switch { display: inline-flex; margin-inline-start: auto; }
    .m3dyn-switch input { position: absolute; opacity: 0; inline-size: 1px; block-size: 1px; }
    .m3dyn-track { display: flex; align-items: center; inline-size: 3.25rem; block-size: 2rem; border-radius: 999px; border: 2px solid var(--m3dyn-outline); background: transparent; transition: background-color .2s, border-color .2s; }
    .m3dyn-switch input:checked + .m3dyn-track { background: var(--m3dyn-primary); border-color: var(--m3dyn-primary); }
    .m3dyn-switch input:focus-visible + .m3dyn-track { outline: 2px solid var(--m3dyn-primary); outline-offset: 2px; }
    .m3dyn-thumb { inline-size: 1rem; aspect-ratio: 1; margin-inline-start: .25rem; border-radius: 50%; background: var(--m3dyn-outline); transition: inline-size .2s, margin-inline-start .2s, background-color .2s; }
    .m3dyn-switch input:checked + .m3dyn-track .m3dyn-thumb { inline-size: 1.5rem; margin-inline-start: 1.25rem; background: var(--m3dyn-on-primary); }
    .m3dyn-prog { block-size: .3rem; border-radius: 999px; background: color-mix(in srgb, var(--m3dyn-outline) 55%, transparent); overflow: clip; }
    .m3dyn-prog i { display: block; block-size: 100%; inline-size: 62%; border-radius: 999px; background: var(--m3dyn-primary); }
    .m3dyn-fab { position: absolute; inset-block-end: 1rem; inset-inline-end: 1rem; display: grid; place-items: center; inline-size: 3.5rem; aspect-ratio: 1; border: none; border-radius: 1rem; background: var(--m3dyn-primary-container); color: var(--m3dyn-on-primary-container); box-shadow: 0 4px 8px 3px rgba(0, 0, 0, .15), 0 1px 3px rgba(0, 0, 0, .3); cursor: pointer; }
    .m3dyn-fab::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: currentColor; opacity: 0; transition: opacity .15s; pointer-events: none; }
    .m3dyn-fab:hover::before { opacity: .08; }
    .m3dyn-fab:focus-visible { outline: 2px solid var(--m3dyn-primary); outline-offset: 2px; }
    .m3dyn-note { margin: 0; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); max-width: 56ch; }
    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-js is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3dyn-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3dyn-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column is read as cut off; let this page's table and snippet
       wrap so nothing is read as cut off. */
    @media (max-width: 480px) {
        .pg:has(.m3dyn-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.m3dyn-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3dyn-root, .m3dyn-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One seed, a whole palette', 'یک دانه، یک پالت کامل') }}</h3>
        <p style="margin: 0; max-width: 54ch; color: var(--nx-text-muted)">
            {{ $say('Pick a wallpaper seed — eight roles recompute with color-mix in oklab and the little radio app recolours in one sweep: bar, card, chips, switch, progress and FAB. The last swatch is a free picker.', 'دانهٔ کاغذدیواری را انتخاب کنید — هشت نقش با color-mix در oklab از نو حساب می‌شوند و رادیوی کوچک با یک حرکت رنگ عوض می‌کند: نوار، کارت، چیپ‌ها، سوییچ، نوار پیشرفت و FAB. واترشی آخر، انتخابگر آزاد است.') }}
        </p>
    </div>

    <div class="m3dyn-root"
        x-data="{
            seed: '#6750A4',
            seeds: [
                { n: 'متریال بنفش', hex: '#6750A4' },
                { n: 'سبز', hex: '#1B6B3A' },
                { n: 'آبی', hex: '#0B57D0' },
                { n: 'نارنجی', hex: '#B45309' },
                { n: 'صورتی', hex: '#A63A6B' },
                { n: 'آجری', hex: '#8C4A2F' },
            ],
        }"
        x-bind:style="'--m3dyn-seed: ' + seed"
        style="--m3dyn-seed: #6750A4">
        <div class="m3dyn-swatches" role="group" aria-label="{{ $say('Wallpaper seed', 'دانهٔ کاغذدیواری') }}">
            <template x-for="s in seeds" :key="s.hex">
                <button type="button" class="m3dyn-swatch" x-bind:aria-pressed="(seed === s.hex).toString()" x-bind:aria-label="s.n"
                    x-bind:style="{ background: s.hex, color: s.hex }"
                    x-on:click="seed = s.hex">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                </button>
            </template>
            <input type="color" class="m3dyn-custom" value="#6750A4" x-on:input="seed = $event.target.value" aria-label="{{ $say('Custom seed colour', 'رنگ دانهٔ دلخواه') }}">
        </div>

        <div class="m3dyn-app" role="group" aria-label="{{ $say('Mini app preview', 'پیش‌نمایش برنامهٔ کوچک') }}">
            <div class="m3dyn-wall">
                <small>{{ $say('Wallpaper', 'کاغذدیواری') }}</small>
            </div>
            <div class="m3dyn-bar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V6l10-2v12"/><circle cx="6.5" cy="18" r="2.5"/><circle cx="16.5" cy="16" r="2.5"/></svg>
                <h4>{{ $say('Nabu radio', 'رادیو نابو') }}</h4>
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
            </div>
            <div class="m3dyn-body">
                <div class="m3dyn-card">
                    <b>{{ $say('Album of the week', 'آلبوم هفته') }}</b>
                    <small>{{ $say('«Rain on Shiraz» · 12 tracks · 44 min', '«باران روی شیراز» · ۱۲ قطعه · ۴۴ دقیقه') }}</small>
                    <button type="button">{{ $say('Listen now', 'همین حالا گوش بده') }}</button>
                </div>
                <div class="m3dyn-chips" role="group" aria-label="{{ $say('Moods', 'حال‌وهوا') }}">
                    <button type="button" class="m3dyn-chip" aria-pressed="true">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                        {{ $say('Calm', 'آرام') }}
                    </button>
                    <button type="button" class="m3dyn-chip" aria-pressed="false">{{ $say('Focus', 'تمرکز') }}</button>
                    <button type="button" class="m3dyn-chip" aria-pressed="false">{{ $say('Road trip', 'جاده') }}</button>
                </div>
                <div class="m3dyn-tune">
                    <b>{{ $say('Auto-mix at night', 'میکس خودکار شبانه') }}</b>
                    <label class="m3dyn-switch">
                        <input type="checkbox" role="switch" checked aria-label="{{ $say('Auto-mix at night', 'میکس خودکار شبانه') }}">
                        <span class="m3dyn-track"><span class="m3dyn-thumb"></span></span>
                    </label>
                </div>
                <div class="m3dyn-prog" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $say('Buffer', 'بافر') }}"><i></i></div>
            </div>
            <button type="button" class="m3dyn-fab" aria-label="{{ $say('Add station', 'افزودن ایستگاه') }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            </button>
        </div>

        <p class="m3dyn-note">{{ $say('Roles keep the 40/100 and 90/10 tone pairings, so text on every container stays legible whatever the seed — even the free picker.', 'نقش‌ها جفت‌تُن‌های ۴۰/۱۰۰ و ۹۰/۱۰ را نگه می‌دارند تا متن روی هر محفظه‌ای خوانا بماند، با هر دانه‌ای — حتی انتخابگر آزاد.') }}</p>
    </div>
</section>
