{{--
    The four Material 3 chips staged as a photo-gallery filter sheet: the
    assist chip summons a map strip, three filter chips multi-select with a
    sliding checkmark and really filter the grid, the AI input chip removes
    itself, and the suggestion chips swap the caption underneath.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3chip-root {
        --m3chip-primary: #6750A4; --m3chip-on-primary-container: #21005D;
        --m3chip-primary-container: #EADDFF;
        --m3chip-secondary-container: #E8DEF8; --m3chip-on-secondary-container: #1D192B;
        --m3chip-surface: #FEF7FF; --m3chip-surface-container: #F3EDF7;
        --m3chip-surface-container-high: #ECE6F0;
        --m3chip-on-surface: #1D1B20; --m3chip-on-surface-variant: #49454F;
        --m3chip-outline-variant: #CAC4D0; --m3chip-error: #B3261E;
        --m3chip-ease: cubic-bezier(.2, 0, 0, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; grid-template-columns: minmax(0, 1fr); gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3chip-root {
        --m3chip-primary: #D0BCFF; --m3chip-on-primary-container: #EADDFF;
        --m3chip-primary-container: #4F378B;
        --m3chip-secondary-container: #4A4458; --m3chip-on-secondary-container: #E8DEF8;
        --m3chip-surface: #141218; --m3chip-surface-container: #211F26;
        --m3chip-surface-container-high: #2B2930;
        --m3chip-on-surface: #E6E0E9; --m3chip-on-surface-variant: #CAC4D0;
        --m3chip-outline-variant: #49454F; --m3chip-error: #F2B8B5;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3chip-root {
            --m3chip-primary: #D0BCFF; --m3chip-on-primary-container: #EADDFF;
            --m3chip-primary-container: #4F378B;
            --m3chip-secondary-container: #4A4458; --m3chip-on-secondary-container: #E8DEF8;
            --m3chip-surface: #141218; --m3chip-surface-container: #211F26;
            --m3chip-surface-container-high: #2B2930;
            --m3chip-on-surface: #E6E0E9; --m3chip-on-surface-variant: #CAC4D0;
            --m3chip-outline-variant: #49454F; --m3chip-error: #F2B8B5;
        }
    }
    .m3chip-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3chip-screen { position: relative; overflow: clip; block-size: 27rem; border-radius: 2.5rem; background: var(--m3chip-surface); display: flex; flex-direction: column; }
    .m3chip-status { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.4rem .2rem; color: var(--m3chip-on-surface); font: 600 .72rem/1 Roboto, system-ui, sans-serif; }
    .m3chip-head { display: flex; align-items: center; gap: .6rem; padding: .55rem 1.1rem .35rem; }
    .m3chip-head h4 { margin: 0; font: 500 1.2rem/1.2 Roboto, system-ui, sans-serif; color: var(--m3chip-on-surface); }
    .m3chip-head small { margin-inline-start: auto; font: 400 .74rem/1 Roboto, system-ui, sans-serif; color: var(--m3chip-on-surface-variant); }
    .m3chip-strip { display: flex; gap: .5rem; padding: .35rem .9rem .5rem; overflow-x: auto; scrollbar-width: none; }
    .m3chip-strip::-webkit-scrollbar { display: none; }
    .m3chip { position: relative; display: inline-flex; align-items: center; gap: .5rem; flex: none; block-size: 2rem; padding-inline: 1rem; border-radius: .5rem; border: 1px solid var(--m3chip-outline-variant); background: var(--m3chip-surface); color: var(--m3chip-on-surface-variant); box-shadow: 0 1px 2px rgba(0, 0, 0, .14); cursor: pointer; font: 500 .8rem/1 Roboto, system-ui, sans-serif; -webkit-tap-highlight-color: transparent; }
    .m3chip:focus-visible { outline: 2px solid var(--m3chip-primary); outline-offset: 2px; }
    .m3chip::after { content: ''; position: absolute; inset: 0; border-radius: inherit; background: currentColor; opacity: 0; transition: opacity .15s; pointer-events: none; }
    .m3chip:hover::after { opacity: .08; }
    .m3chip:active::after { opacity: .12; }
    .m3chip[aria-pressed='true'] { background: var(--m3chip-secondary-container); border-color: transparent; color: var(--m3chip-on-secondary-container); box-shadow: none; }
    .m3chip-check { display: inline-grid; place-items: center; inline-size: 0; overflow: clip; transition: inline-size .2s var(--m3chip-ease); color: var(--m3chip-primary); }
    .m3chip[aria-pressed='true'] .m3chip-check { inline-size: 1.125rem; }
    .m3chip[data-input] { padding-inline-end: .4rem; }
    .m3chip-x { display: grid; place-items: center; inline-size: 1.4rem; aspect-ratio: 1; margin-inline-start: .15rem; border: none; border-radius: 50%; background: transparent; color: inherit; cursor: pointer; }
    .m3chip-x:focus-visible { outline: 2px solid var(--m3chip-primary); outline-offset: 1px; }
    .m3chip-x:hover { background: color-mix(in srgb, currentColor 12%, transparent); }
    .m3chip-map { margin: 0 .9rem .5rem; block-size: 4.25rem; border-radius: .75rem; overflow: clip; position: relative; background: linear-gradient(140deg, #a7cbb7, #5f8f76); }
    .m3chip-map::before { content: ''; position: absolute; inset: 0; background: repeating-linear-gradient(0deg, rgba(255, 255, 255, .25) 0 1px, transparent 1px 22px), repeating-linear-gradient(90deg, rgba(255, 255, 255, .25) 0 1px, transparent 1px 26px); }
    .m3chip-pin { position: absolute; inset-block-start: 50%; inset-inline-start: 55%; translate: -50% -85%; color: #ffffff; filter: drop-shadow(0 2px 2px rgba(0, 0, 0, .35)); }
    .m3chip-map figcaption { position: absolute; inset-block-end: .4rem; inset-inline-start: .6rem; font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: #ffffff; }
    .m3chip-caption { padding: 0 1.1rem; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--m3chip-on-surface-variant); }
    .m3chip-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .4rem; padding: .5rem .9rem 1rem; overflow-y: auto; align-content: start; flex: 1; }
    .m3chip-photo { position: relative; aspect-ratio: 1; border-radius: .75rem; overflow: clip; }
    .m3chip-photo figcaption { position: absolute; inset-block-end: 0; inset-inline: 0; padding: 1.1rem .4rem .3rem; font: 500 .64rem/1.2 Roboto, system-ui, sans-serif; color: #ffffff; background: linear-gradient(transparent, rgba(0, 0, 0, .55)); white-space: nowrap; overflow: clip; text-overflow: ellipsis; }
    .m3chip-empty { grid-column: 1 / -1; padding: 1.5rem .5rem; text-align: center; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--m3chip-on-surface-variant); }
    .m3chip-spec { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .m3chip-cell { display: grid; gap: .5rem; justify-items: center; }
    .m3chip-cell small { font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); }
    /* This page's props table sits far below the fold: the shared scroll-in
       reveal leaves its rows at opacity 0 until scrolled, so a full-page
       capture shows header columns over an empty body. Keep the rows of the
       table on this page (guarded by :has so the rule never leaks) visible. */
    .pg:has(.m3chip-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    /* At phone widths the shared "Important props" table's nowrap cells run
       past the inline edge, so the "What it does" column starts outside the
       viewport (a capture shows only «ass…», «Un…», «Th…» and a clipped
       «Wh…» header). Let this page's table cells wrap — scoped through
       :has(.m3chip-root), so it never reaches another demo page. */
    @media (max-width: 480px) {
        .pg:has(.m3chip-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3chip-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A gallery filtered by chips', 'گالری‌ای که چیپ‌ها فیلترش می‌کنند') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The assist chip opens the map, the three filter chips really narrow the grid, the AI input chip removes itself, and the suggestions rewrite the caption.', 'چیپ کمکی نقشه را باز می‌کند، سه چیپ فیلتر واقعاً شبکه را تنگ‌تر می‌کنند، چیپ ورودیِ هوش مصنوعی خودش را حذف می‌کند و پیشنهادها شرح‌تصویر را عوض می‌کنند.') }}
        </p>
    </div>

    <div class="m3chip-root" x-data="{
            cats: { nature: true, city: false, people: false },
            map: false, kept: true,
            caption: '{{ $say('Your summer in 12 frames: mountain, sea, city.', 'تابستان شما در ۱۲ قاب: کوه، دریا، شهر.') }}',
            photos: [
                { n: 'کوه دماوند', c: 'nature', g: 'linear-gradient(160deg,#90a8d8,#39508c)' },
                { n: 'پل خواجو', c: 'city', g: 'linear-gradient(160deg,#e8b98a,#9c6b3f)' },
                { n: 'چای‌خانهٔ قدیمی', c: 'people', g: 'linear-gradient(160deg,#c9a27e,#6f4f37)' },
                { n: 'جنگل هیرکانی', c: 'nature', g: 'linear-gradient(160deg,#9ecf9a,#33684a)' },
                { n: 'میدان نقش جهان', c: 'city', g: 'linear-gradient(160deg,#7ec8d8,#2f7d95)' },
                { n: 'رقص محلی گیلان', c: 'people', g: 'linear-gradient(160deg,#e8a0a8,#a04a5a)' },
                { n: 'کویر لوت', c: 'nature', g: 'linear-gradient(160deg,#f0c98d,#b06a35)' },
                { n: 'بازار تبریز', c: 'city', g: 'linear-gradient(160deg,#b6a0d8,#5d4a8c)' },
                { n: 'عروسی کرمان', c: 'people', g: 'linear-gradient(160deg,#e8c9a0,#9c7248)' },
            ],
            picked() { return this.cats.nature || this.cats.city || this.cats.people },
            shown() { return this.photos.filter(p => ! this.picked() || this.cats[p.c]) },
        }">
        <div class="m3chip-frame">
            <div class="m3chip-screen">
                <div class="m3chip-status" aria-hidden="true">
                    <span>{{ $say('9:41', '۰۹:۴۱') }}</span>
                    <svg width="44" height="12" viewBox="0 0 44 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="5" width="3" height="7" rx="1"/><rect x="10" y="3" width="3" height="9" rx="1"/><rect x="15" y="1" width="3" height="11" rx="1"/><rect x="35" y="2" width="8" height="8" rx="2" opacity=".35"/><rect x="36.5" y="3.5" width="4" height="5" rx="1"/></svg>
                </div>
                <div class="m3chip-head">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--m3chip-on-surface)" aria-hidden="true"><path d="M19 12H5"/><path class="m3chip-arrow" d="M11 18l-6-6 6-6"/></svg>
                    <h4>{{ $say('Gallery', 'گالری') }}</h4>
                    <small x-text="shown().length + ' {{ $say('photos', 'عکس') }}'">۹ عکس</small>
                </div>

                <div class="m3chip-strip" role="group" aria-label="{{ $say('Filters', 'فیلترها') }}">
                    <button type="button" class="m3chip" aria-pressed="false" x-bind:aria-pressed="map.toString()" x-on:click="map = !map">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.4"/></svg>
                        {{ $say('On the map', 'روی نقشه') }}
                    </button>
                    <button type="button" class="m3chip" aria-pressed="true" x-bind:aria-pressed="cats.nature.toString()" x-on:click="cats.nature = !cats.nature">
                        <span class="m3chip-check" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
                        {{ $say('Nature', 'طبیعت') }}
                    </button>
                    <button type="button" class="m3chip" aria-pressed="false" x-bind:aria-pressed="cats.city.toString()" x-on:click="cats.city = !cats.city">
                        <span class="m3chip-check" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
                        {{ $say('City', 'شهر') }}
                    </button>
                    <button type="button" class="m3chip" aria-pressed="false" x-bind:aria-pressed="cats.people.toString()" x-on:click="cats.people = !cats.people">
                        <span class="m3chip-check" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
                        {{ $say('People', 'مردم') }}
                    </button>
                    <template x-if="kept">
                        <span class="m3chip" data-input style="cursor: default">
                            {{ $say('Suggested set: Damavand', 'مجموعهٔ پیشنهادی: دماوند') }}
                            <button type="button" class="m3chip-x" x-on:click="kept = false" aria-label="{{ $say('Remove suggestion', 'حذف پیشنهاد') }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                            </button>
                        </span>
                    </template>
                </div>

                <figure class="m3chip-map" x-show="map" style="margin-block: 0 .5rem" aria-label="{{ $say('Map of the shots', 'نقشهٔ عکس‌ها') }}">
                    <span class="m3chip-pin" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22s-7.5-6-7.5-12a7.5 7.5 0 0 1 15 0c0 6-7.5 12-7.5 12z"/><circle cx="12" cy="10" r="3" fill="#33684a"/></svg></span>
                    <figcaption>{{ $say('Damavand · 14 shots nearby', 'دماوند · ۱۴ عکس نزدیک') }}</figcaption>
                </figure>

                <p class="m3chip-caption" x-text="caption" aria-live="polite"></p>

                <div class="m3chip-grid">
                    <template x-for="p in shown()" :key="p.n">
                        <figure class="m3chip-photo" style="margin: 0" x-bind:style="{ background: p.g }">
                            <figcaption x-text="p.n"></figcaption>
                        </figure>
                    </template>
                    <p class="m3chip-empty" x-show="!shown().length">{{ $say('Nothing matches this filter.', 'با این فیلتر عکسی نمی‌ماند.') }}</p>
                </div>
            </div>
        </div>

        <div class="pg-row" style="justify-content: center">
            <button type="button" class="m3chip" x-on:click="caption = '{{ $say('Shall I turn these into a short travelogue?', 'از این عکس‌ها یک سفرنامهٔ کوتاه بسازم؟') }}'">{{ $say('Build a travelogue', 'سفرنامه بساز') }}</button>
            <button type="button" class="m3chip" x-on:click="caption = '{{ $say('42 shots this season — 18% more than spring.', '۴۲ عکس این فصل — ۱۸٪ بیشتر از بهار.') }}'">{{ $say('Summarise the season', 'خلاصهٔ فصل') }}</button>
            <button type="button" class="m3chip" x-on:click="caption = '{{ $say('A gallery link is ready for the family group.', 'لینک گالری برای گروه خانواده آماده است.') }}'">{{ $say('Share with family', 'هم‌رسانی با خانواده') }}</button>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Four roles, one shape', 'چهار نقش، یک شکل') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('32 px tall, 8 px corners, level-1 shadow at rest — selected filter chips rise to the secondary container and their checkmark nudges the label aside.', '۳۲ پیکسل بلند، گوشهٔ ۸ پیکسل، سایهٔ ظریف در حالت آرام — چیپ فیلترِ انتخاب‌شده به محفظهٔ ثانویه می‌رود و تیکش برچسب را کنار می‌زند.') }}
        </p>
    </div>
    <div class="m3chip-root" x-data="{ demo: false }" style="inline-size: 100%">
        <div class="m3chip-spec">
            <div class="m3chip-cell">
                <button type="button" class="m3chip">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.4"/></svg>
                    {{ $say('Open the map', 'باز کردن نقشه') }}
                </button>
                <small>assist</small>
            </div>
            <div class="m3chip-cell">
                <button type="button" class="m3chip" aria-pressed="false" x-bind:aria-pressed="demo.toString()" x-on:click="demo = !demo">
                    <span class="m3chip-check" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
                    {{ $say('Genre: sci-fi', 'ژانر: علمی‌تخیلی') }}
                </button>
                <small>filter</small>
            </div>
            <div class="m3chip-cell">
                <span class="m3chip" data-input style="cursor: default">
                    {{ $say('AI: summarise', 'هوش مصنوعی: خلاصه کن') }}
                    <button type="button" class="m3chip-x" aria-label="{{ $say('Remove', 'حذف') }}">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                </span>
                <small>input</small>
            </div>
            <div class="m3chip-cell">
                <button type="button" class="m3chip">{{ $say('Try: plant care', 'پیشنهاد: نگهداری گیاه') }}</button>
                <small>suggestion</small>
            </div>
        </div>
    </div>
</section>
