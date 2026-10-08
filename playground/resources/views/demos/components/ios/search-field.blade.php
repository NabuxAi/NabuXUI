{{--
    The iOS search field on a grouped music list: the grey pill squeezes when
    it takes focus, Cancel slides in from the far side, a round clear button
    appears while there is text, and the results filter live beneath.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .iosrch-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        --iosrch-field-bg: #7676801f; --iosrch-in: 10px;
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    [dir="rtl"] .iosrch-root { --iosrch-in: -10px; }
    html[data-theme="dark"] .iosrch-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .iosrch-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        }
    }
    .iosrch-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    .iosrch-panel { inline-size: min(100%, 22rem); margin-inline: auto; padding: .9rem; border-radius: 16px; background: var(--ios-bg);
                    box-shadow: 0 14px 36px rgba(0, 0, 0, .12), inset 0 0 0 1px rgba(0, 0, 0, .05); }
    .iosrch-row { display: flex; align-items: center; }
    .iosrch-field { flex: 1; display: flex; align-items: center; gap: 6px; block-size: 36px; padding-inline: 8px; border-radius: 10px;
                    background: var(--iosrch-field-bg); min-inline-size: 0; transition: box-shadow .2s ease; }
    .iosrch-field:focus-within { box-shadow: 0 0 0 2px var(--ios-blue); }
    .iosrch-field svg { flex: none; color: var(--ios-label-2); }
    .iosrch-field input { flex: 1; min-inline-size: 0; block-size: 100%; border: 0; background: transparent; color: var(--ios-label);
                          font: 400 17px/-apple-system, system-ui, sans-serif; }
    .iosrch-field input:focus { outline: none; }
    .iosrch-field input::placeholder { color: var(--ios-label-2); }
    .iosrch-field input::-webkit-search-cancel-button { display: none; }
    .iosrch-clear { flex: none; display: grid; place-items: center; inline-size: 19px; aspect-ratio: 1; border-radius: 50%;
                    background: var(--ios-gray); color: #fff; transition: scale .12s ease; }
    .iosrch-clear:active { scale: .88; }

    .iosrch-cancel { flex: none; max-inline-size: 0; overflow: hidden; opacity: 0; translate: var(--iosrch-in) 0; color: var(--ios-blue);
                     font: 400 17px/-apple-system, system-ui, sans-serif;
                     transition: max-inline-size .28s ease, opacity .28s ease, translate .28s ease; }
    .iosrch-cancel span { display: block; white-space: nowrap; padding-inline: .55rem; }
    .iosrch-cancel.on { max-inline-size: 6rem; opacity: 1; translate: 0 0; }

    .iosrch-cap { display: flex; justify-content: space-between; margin: .9rem .2rem .35rem; font: 600 .78rem/-apple-system, system-ui, sans-serif;
                  color: var(--ios-label-2); text-transform: uppercase; }
    .iosrch-list { margin: 0; padding: 0 .2rem; list-style: none; max-block-size: 17rem; overflow-y: auto; scrollbar-width: thin; }
    .iosrch-item { display: flex; align-items: center; gap: .7rem; padding: .5rem .2rem; }
    .iosrch-item + .iosrch-item { border-block-start: .5px solid var(--ios-sep); }
    .iosrch-art { flex: none; inline-size: 42px; aspect-ratio: 1; border-radius: 8px; }
    .iosrch-item b { display: block; font-size: .95rem; font-weight: 600; }
    .iosrch-item span { font-size: .8rem; color: var(--ios-label-2); }
    .iosrch-item time { margin-inline-start: auto; font-size: .78rem; color: var(--ios-label-2); font-variant-numeric: tabular-nums; }
    .iosrch-empty { padding: 1.2rem .2rem; text-align: center; font-size: .9rem; color: var(--ios-label-2); }

    /* The shared «Important props» table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked empty. Pin this page's table rows visible — scoped
       through :has(.iosrch-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.iosrch-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .iosrch-cancel, .iosrch-clear { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Search that makes room for itself', 'جست‌وجویی که برای خودش جا باز می‌کند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Tap the grey pill: it squeezes to full width, the Cancel button slides in from the far side, the round ✕ appears as soon as there is text, and the song list filters live beneath. Cancel or the ✕ puts everything back.', 'روی قرص خاکستری بزنید: به تمام‌عرض جمع می‌شود، دکمهٔ لغو از سمت مقابل می‌آید، تا متنی باشد دکمهٔ گرد ✕ پیدا می‌شود و فهرست آهنگ‌ها زیر آن زنده فیلتر می‌شود. لغو یا ✕ همه‌چیز را برمی‌گرداند.') }}
        </p>
    </div>

    <div class="iosrch-root" x-data="{
            q: '', focused: false, rtl: document.documentElement.dir === 'rtl',
            faN(s) { return this.rtl ? String(s).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(s) },
            songs: [
                { t: ['Tehran Alleys', 'کوچه‌های تهران'], a: ['Bachar Choir', 'کر باچار'], d: '3:45', g: 'linear-gradient(135deg, #FF9500, #FF3B30)' },
                { t: ['Rainy Night', 'شبِ باران'], a: ['Homa & Reza', 'هما و رضا'], d: '4:12', g: 'linear-gradient(135deg, #5856D6, #0A2A6B)' },
                { t: ['Southern Sea', 'دریای جنوب'], a: ['Bandari Nights', 'شب‌های بندری'], d: '2:58', g: 'linear-gradient(135deg, #30D158, #007AFF)' },
                { t: ['Morning of Pardis', 'صبح پردیس'], a: ['Sara Ahmadi', 'سارا احمدی'], d: '3:21', g: 'linear-gradient(135deg, #64D2FF, #5856D6)' },
                { t: ['Moon Over Damavand', 'ماه بر دماوند'], a: ['Alireza Ghasemi', 'علیرضا قاسمی'], d: '5:06', g: 'linear-gradient(135deg, #FF3B75, #FF9F0A)' },
                { t: ['Crossroads', 'چهارراه'], a: ['Aveh Band', 'گروه آوه'], d: '3:57', g: 'linear-gradient(135deg, #FF9F0A, #FF453A)' },
                { t: ['Blue Alley', 'کوچهٔ آبی'], a: ['Nima Rad', 'نیما راد'], d: '4:33', g: 'linear-gradient(135deg, #007AFF, #64D2FF)' },
                { t: ['Tehran Rain', 'باران تهران'], a: ['Arash Mir', 'آرش میر'], d: '3:12', g: 'linear-gradient(135deg, #5E5CE6, #30D158)' },
            ],
            get list() { const q = this.q.trim().toLowerCase();
                return q ? this.songs.filter(s => (s.t[0] + ' ' + s.t[1] + ' ' + s.a[0] + ' ' + s.a[1]).toLowerCase().includes(q)) : this.songs },
            cancel() { this.q = ''; this.focused = false; this.$refs.inp.blur() },
        }">
        <div class="iosrch-panel">
            <div class="iosrch-row">
                <div class="iosrch-field">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><circle cx="7" cy="7" r="4.6"/><path d="m13.6 13.6-3.3-3.3"/></svg>
                    <input type="search" x-ref="inp" x-model="q" x-on:focus="focused = true" x-on:blur="focused = false"
                           :placeholder="$say('Songs, artists, albums', 'آهنگ، هنرمند، آلبوم')"
                           :aria-label="$say('Search music', 'جست‌وجوی موسیقی')">
                    <button type="button" class="iosrch-clear" x-show="q.length > 0" x-on:click="q = ''; $refs.inp.focus()"
                            :aria-label="$say('Clear search', 'پاک‌کردن جست‌وجو')">
                        <svg width="9" height="9" viewBox="0 0 9 9" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="m1.2 1.2 6.6 6.6M7.8 1.2 1.2 7.8"/></svg>
                    </button>
                </div>
                <button type="button" class="iosrch-cancel" :class="{ on: focused }" x-on:click="cancel()">
                    <span>{{ $say('Cancel', 'لغو') }}</span>
                </button>
            </div>

            <div class="iosrch-cap">
                <span x-text="q.trim() ? (rtl ? 'نتایج' : 'Results') : (rtl ? 'همهٔ آهنگ‌ها' : 'All songs')">…</span>
                <span aria-live="polite" x-text="faN(list.length) + (rtl ? ' قطعه' : ' songs')">…</span>
            </div>

            <ul class="iosrch-list">
                <template x-for="(s, i) in list" :key="i">
                    <li class="iosrch-item">
                        <span class="iosrch-art" :style="'background: ' + s.g" aria-hidden="true"></span>
                        <div>
                            <b x-text="rtl ? s.t[1] : s.t[0]"></b>
                            <span x-text="rtl ? s.a[1] : s.a[0]"></span>
                        </div>
                        <time x-text="rtl ? faN(s.d) : s.d">۳:۴۵</time>
                    </li>
                </template>
                <li class="iosrch-empty" x-show="list.length === 0" x-cloak>
                    {{ $say('Nothing found — try «باران» or «sea».', 'چیزی پیدا نشد — «باران» یا «sea» را امتحان کنید.') }}
                </li>
            </ul>
        </div>
    </div>
</section>
