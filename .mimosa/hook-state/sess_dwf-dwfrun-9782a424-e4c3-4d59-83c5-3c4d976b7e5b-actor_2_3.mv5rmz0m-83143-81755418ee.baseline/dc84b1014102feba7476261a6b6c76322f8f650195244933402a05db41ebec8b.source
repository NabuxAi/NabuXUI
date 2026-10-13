{{--
    The M3 search bar growing into a search view inside the stage: the 56 px
    resting bar (search icon, hint, tinted mic, avatar) opens the full view —
    back arrow, focused input, recent suggestions, three filter chips and
    results that really filter as you type; the system arrow returns.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    [x-cloak] { display: none !important; }
    .m3srch-root {
        --m3srch-primary: #6750A4; --m3srch-on-primary: #FFFFFF;
        --m3srch-primary-container: #EADDFF; --m3srch-on-primary-container: #21005D;
        --m3srch-secondary-container: #E8DEF8; --m3srch-on-secondary-container: #1D192B;
        --m3srch-surface: #FEF7FF; --m3srch-surface-container: #F3EDF7;
        --m3srch-surface-container-high: #ECE6F0;
        --m3srch-on-surface: #1D1B20; --m3srch-on-surface-variant: #49454F;
        --m3srch-outline-variant: #CAC4D0;
        --m3srch-ease: cubic-bezier(.2, 0, 0, 1); --m3srch-spring: cubic-bezier(.05, .7, .1, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
        /* The implicit auto track sizes itself to the widest min-content
           (the resting bar's nowrap hint) and blows past the card at 375px;
           pin it to the container like .pg-box does with minmax(0, 1fr). */
        grid-template-columns: minmax(0, 1fr);
    }
    html[data-theme="dark"] .m3srch-root {
        --m3srch-primary: #D0BCFF; --m3srch-on-primary: #381E72;
        --m3srch-primary-container: #4F378B; --m3srch-on-primary-container: #EADDFF;
        --m3srch-secondary-container: #4A4458; --m3srch-on-secondary-container: #E8DEF8;
        --m3srch-surface: #141218; --m3srch-surface-container: #211F26;
        --m3srch-surface-container-high: #2B2930;
        --m3srch-on-surface: #E6E0E9; --m3srch-on-surface-variant: #CAC4D0;
        --m3srch-outline-variant: #49454F;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3srch-root {
            --m3srch-primary: #D0BCFF; --m3srch-on-primary: #381E72;
            --m3srch-primary-container: #4F378B; --m3srch-on-primary-container: #EADDFF;
            --m3srch-secondary-container: #4A4458; --m3srch-on-secondary-container: #E8DEF8;
            --m3srch-surface: #141218; --m3srch-surface-container: #211F26;
            --m3srch-surface-container-high: #2B2930;
            --m3srch-on-surface: #E6E0E9; --m3srch-on-surface-variant: #CAC4D0;
            --m3srch-outline-variant: #49454F;
        }
    }
    .m3srch-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3srch-screen { position: relative; overflow: clip; block-size: 27rem; border-radius: 2.5rem; background: var(--m3srch-surface); display: flex; flex-direction: column; }
    .m3srch-status { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.4rem .2rem; color: var(--m3srch-on-surface); font: 600 .72rem/1 Roboto, system-ui, sans-serif; }
    .m3srch-greet { padding: .6rem 1.25rem .4rem; }
    .m3srch-greet h4 { margin: 0 0 .15rem; font: 500 1.3rem/1.25 Roboto, system-ui, sans-serif; color: var(--m3srch-on-surface); }
    .m3srch-greet small { display: block; font: 400 .78rem/1.4 Roboto, system-ui, sans-serif; color: var(--m3srch-on-surface-variant); }
    .m3srch-bar { display: flex; align-items: center; gap: .75rem; inline-size: auto; margin: .5rem 1.25rem .4rem; block-size: 3.5rem; padding-inline: 1rem; border: none; border-radius: 999px; background: var(--m3srch-surface-container-high); color: var(--m3srch-on-surface-variant); cursor: pointer; text-align: start; font: 400 .88rem/1 Roboto, system-ui, sans-serif; -webkit-tap-highlight-color: transparent; }
    .m3srch-bar:focus-visible { outline: 2px solid var(--m3srch-primary); outline-offset: 2px; }
    .m3srch-bar > svg { flex: none; }
    /* :not() keeps this rule off the mic/avatar spans — at (0,1,1) it used to
       beat their own flex: none (0,1,0) and let the avatar grow past the bar. */
    .m3srch-bar > span:not(.m3srch-mic):not(.m3srch-ava) { flex: 1; min-inline-size: 0; white-space: nowrap; overflow: clip; text-overflow: ellipsis; }
    .m3srch-mic { color: var(--m3srch-primary); flex: none; display: grid; place-items: center; }
    .m3srch-ava { flex: none; display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border-radius: 50%; background: linear-gradient(140deg, #c4b5fd, #6d28d9); color: #ffffff; font: 600 .8rem/1 Roboto, system-ui, sans-serif; }
    .m3srch-rest { flex: 1; padding: .4rem 1.25rem 1rem; overflow-y: auto; }
    .m3srch-rest h5 { margin: .8rem 0 .5rem; font: 500 .8rem/1 Roboto, system-ui, sans-serif; color: var(--m3srch-primary); }
    .m3srch-rest .m3srch-row { border: none; }
    .m3srch-view { position: absolute; inset: 0; z-index: 5; display: flex; flex-direction: column; background: var(--m3srch-surface); animation: m3srch-in .35s var(--m3srch-spring); }
    @keyframes m3srch-in { from { opacity: 0; translate: 0 3%; } }
    .m3srch-view-top { display: flex; align-items: center; gap: .5rem; padding: 1.4rem 1.1rem .6rem; border-block-end: 1px solid var(--m3srch-outline-variant); }
    .m3srch-back { position: relative; flex: none; display: grid; place-items: center; inline-size: 2.75rem; aspect-ratio: 1; border: none; border-radius: 50%; background: transparent; color: var(--m3srch-on-surface); cursor: pointer; }
    .m3srch-back:hover::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: currentColor; opacity: .08; }
    .m3srch-back:focus-visible { outline: 2px solid var(--m3srch-primary); outline-offset: 2px; }
    .m3srch-input { flex: 1; block-size: 2.75rem; border: none; border-radius: 999px; padding-inline: 1rem; background: var(--m3srch-surface-container-high); color: var(--m3srch-on-surface); font: 400 .92rem Roboto, system-ui, sans-serif; }
    .m3srch-input:focus-visible { outline: 2px solid var(--m3srch-primary); outline-offset: 1px; }
    .m3srch-body { flex: 1; overflow-y: auto; padding: .8rem 1.25rem 1.25rem; }
    .m3srch-cap { margin: 0 0 .6rem; font: 500 .78rem/1 Roboto, system-ui, sans-serif; color: var(--m3srch-primary); }
    .m3srch-chips { display: flex; flex-wrap: wrap; gap: .5rem; margin-block-end: .9rem; }
    .m3srch-chip { position: relative; display: inline-flex; align-items: center; gap: .45rem; block-size: 2rem; padding-inline: 1rem; border-radius: .5rem; border: 1px solid var(--m3srch-outline-variant); background: var(--m3srch-surface); color: var(--m3srch-on-surface-variant); cursor: pointer; font: 500 .78rem/1 Roboto, system-ui, sans-serif; }
    .m3srch-chip:focus-visible { outline: 2px solid var(--m3srch-primary); outline-offset: 2px; }
    .m3srch-chip[aria-pressed='true'] { background: var(--m3srch-secondary-container); border-color: transparent; color: var(--m3srch-on-secondary-container); }
    .m3srch-row { display: flex; align-items: center; gap: .75rem; inline-size: 100%; padding: .55rem .4rem; border-radius: .9rem; border: none; background: transparent; cursor: pointer; text-align: start; }
    .m3srch-row:hover { background: color-mix(in srgb, var(--m3srch-on-surface) 5%, transparent); }
    .m3srch-row:focus-visible { outline: 2px solid var(--m3srch-primary); outline-offset: 1px; }
    .m3srch-row i { flex: none; display: grid; place-items: center; inline-size: 2.4rem; aspect-ratio: 1; border-radius: 50%; background: var(--m3srch-primary-container); color: var(--m3srch-on-primary-container); }
    .m3srch-row b { display: block; font: 500 .86rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3srch-on-surface); }
    .m3srch-row small { display: block; font: 400 .73rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3srch-on-surface-variant); }
    .m3srch-row svg:last-child { margin-inline-start: auto; color: var(--m3srch-on-surface-variant); flex: none; }
    .m3srch-none { padding: 1.6rem .5rem; text-align: center; font: 400 .82rem/1.5 Roboto, system-ui, sans-serif; color: var(--m3srch-on-surface-variant); }
    .m3srch-hint { margin: 0; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); max-width: 56ch; }
    [dir='rtl'] .m3srch-flip { transform: scaleX(-1); }
    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3srch-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3srch-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column is read as cut off; let this page's table wrap. */
    @media (max-width: 480px) {
        .pg:has(.m3srch-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3srch-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The bar becomes the view', 'نوار می‌شود نما') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Tap the resting bar — the whole screen becomes the search view with your recents, three filter chips and results that thin out as you type. Write «دل» or «چای» and watch. The system arrow takes you back.', 'نوارِ آرام را لمس کنید — کل صفحه به نمای جست‌وجو تبدیل می‌شود با بازدیدهای اخیر، سه چیپ فیلتر و نتایجی که با تایپ شما کم‌رنگ می‌شوند. بنویسید «دل» یا «چای» و تماشا کنید. فلش سیستم برمی‌گرداند.') }}
        </p>
    </div>

    <div class="m3srch-root" x-data="{
            open: false, q: '', tab: 'all',
            lib: [
                { n: 'دلتنگی', a: 'همایون شجریان', k: 'song' },
                { n: 'چای نیمه‌شب', a: 'کیهان کلهر', k: 'song' },
                { n: 'باران پشت پنجره', a: 'شهرام ناظری', k: 'song' },
                { n: 'کویر دل', a: 'سیما بینا', k: 'song' },
                { n: 'همایون شجریان', a: '۸ آلبوم در کتابخانه', k: 'artist' },
                { n: 'سیما بینا', a: '۵ آلبوم در کتابخانه', k: 'artist' },
                { n: 'جادهٔ شمال', a: 'رستاک', k: 'song' },
                { n: 'چای‌خانهٔ قدیمی', a: 'گروه رستاک', k: 'song' },
            ],
            recents: ['دلتنگی', 'چای', 'کویر'],
            get results() {
                const s = this.q.trim();
                return this.lib.filter(x => (s === '' || x.n.includes(s) || x.a.includes(s)) && (this.tab === 'all' || x.k === this.tab));
            },
            go() { this.open = true; $nextTick(() => this.$refs.inp.focus()); },
            back() { this.open = false; this.q = ''; this.tab = 'all'; },
        }"
        x-on:keydown.escape.window="back()">
        <div class="m3srch-frame">
            <div class="m3srch-screen">
                <div class="m3srch-status" aria-hidden="true">
                    <span>{{ $say('9:41', '۰۹:۴۱') }}</span>
                    <svg width="44" height="12" viewBox="0 0 44 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="5" width="3" height="7" rx="1"/><rect x="10" y="3" width="3" height="9" rx="1"/><rect x="15" y="1" width="3" height="11" rx="1"/><rect x="35" y="2" width="8" height="8" rx="2" opacity=".35"/><rect x="36.5" y="3.5" width="4" height="5" rx="1"/></svg>
                </div>
                <div class="m3srch-greet">
                    <h4>{{ $say('Nabu library', 'کتابخانهٔ نابو') }}</h4>
                    <small>{{ $say('What do you want to hear tonight?', 'امشب چه چیزی می‌خواهید بشنوید؟') }}</small>
                </div>
                <button type="button" class="m3srch-bar" x-on:click="go()" aria-label="{{ $say('Search the library', 'جست‌وجو در کتابخانه') }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                    <span>{{ $say('Songs, artists, moods…', 'آهنگ‌ها، هنرمندان، حال‌وهوا…') }}</span>
                    <span class="m3srch-mic" aria-hidden="true">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="3" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3"/></svg>
                    </span>
                    <span class="m3srch-ava" aria-hidden="true">{{ $say('S', 'س') }}</span>
                </button>
                <div class="m3srch-rest">
                    <h5>{{ $say('Made for tonight', 'برای امشب') }}</h5>
                    <button type="button" class="m3srch-row">
                        <i aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V6l10-2v12"/><circle cx="6.5" cy="18" r="2.5"/><circle cx="16.5" cy="16" r="2.5"/></svg></i>
                        <span><b>{{ $say('Rainy-tea afternoons', 'عصرهای بارانیِ چای') }}</b><small>{{ $say('Playlist · 18 tracks', 'لیست پخش · ۱۸ قطعه') }}</small></span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5z"/></svg>
                    </button>
                    <button type="button" class="m3srch-row">
                        <i aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V6l10-2v12"/><circle cx="6.5" cy="18" r="2.5"/><circle cx="16.5" cy="16" r="2.5"/></svg></i>
                        <span><b>{{ $say('Night tar suite', 'سوئیت شبِ تار') }}</b><small>{{ $say('Album · 7 tracks', 'آلبوم · ۷ قطعه') }}</small></span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5z"/></svg>
                    </button>
                </div>

                <div class="m3srch-view" x-show="open" x-cloak role="dialog" aria-modal="true" aria-label="{{ $say('Search', 'جست‌وجو') }}">
                    <div class="m3srch-view-top">
                        <button type="button" class="m3srch-back" x-on:click="back()" aria-label="{{ $say('Back', 'بازگشت') }}">
                            <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="m3srch-flip" aria-hidden="true"><path d="M19 12H5"/><path d="M11 18l-6-6 6-6"/></svg>
                        </button>
                        <input type="search" class="m3srch-input" x-ref="inp" x-model="q" placeholder="{{ $say('Search the library', 'جست‌وجو در کتابخانه') }}" aria-label="{{ $say('Search query', 'عبارت جست‌وجو') }}">
                    </div>
                    <div class="m3srch-body">
                        <template x-if="!q.trim()">
                            <div>
                                <p class="m3srch-cap">{{ $say('Recent searches', 'جست‌وجوهای اخیر') }}</p>
                                <template x-for="r in recents" :key="r">
                                    <button type="button" class="m3srch-row" x-on:click="q = r">
                                        <i aria-hidden="true"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M12 7v5l3.5 2"/></svg></i>
                                        <span><b x-text="r"></b></span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M9 7h8v8"/></svg>
                                    </button>
                                </template>
                            </div>
                        </template>
                        <template x-if="q.trim()">
                            <div>
                                <div class="m3srch-chips" role="group" aria-label="{{ $say('Result filters', 'فیلتر نتایج') }}">
                                    <button type="button" class="m3srch-chip" aria-pressed="true" x-bind:aria-pressed="(tab === 'all').toString()" x-on:click="tab = 'all'">{{ $say('Everything', 'همه') }}</button>
                                    <button type="button" class="m3srch-chip" aria-pressed="false" x-bind:aria-pressed="(tab === 'song').toString()" x-on:click="tab = 'song'">{{ $say('Songs', 'آهنگ‌ها') }}</button>
                                    <button type="button" class="m3srch-chip" aria-pressed="false" x-bind:aria-pressed="(tab === 'artist').toString()" x-on:click="tab = 'artist'">{{ $say('Artists', 'هنرمندان') }}</button>
                                </div>
                                <template x-for="x in results" :key="x.n + x.k">
                                    <button type="button" class="m3srch-row">
                                        <i aria-hidden="true">
                                            <svg x-show="x.k === 'song'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V6l10-2v12"/><circle cx="6.5" cy="18" r="2.5"/><circle cx="16.5" cy="16" r="2.5"/></svg>
                                            <svg x-show="x.k === 'artist'" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
                                        </i>
                                        <span><b x-text="x.n"></b><small x-text="x.a"></small></span>
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5z"/></svg>
                                    </button>
                                </template>
                                <p class="m3srch-none" x-show="!results.length">{{ $say('Nothing in the library matches.', 'در کتابخانه چیزِ همسانی نیست.') }}</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <p class="m3srch-hint">{{ $say('The resting bar is 56 px with the tinted mic and your avatar; the view keeps the 32 px chips and lists everything the library really has.', 'نوار آرام ۵۶ پیکسل است با میکروفنِ رنگی و آواتار شما؛ نما هم چیپ‌های ۳۲ پیکسلی را نگه می‌دارد و همان چیزهایی را می‌آورد که واقعاً در کتابخانه هست.') }}</p>
    </div>
</section>
