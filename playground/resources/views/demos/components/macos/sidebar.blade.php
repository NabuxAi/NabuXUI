{{--
    Finder's source list inside a mini window: «Favourites» and «iCloud»
    sections with small grey headers, 28px icon rows with counters, the blue
    selected row with white text, and a filter field at the foot that
    live-filters the rows. The content pane mirrors the selection.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $rows = [
        'projects'  => ['fa' => 'پروژه‌ها', 'en' => 'Projects', 'count' => 24, 'kind' => 'fav', 'size' => '۱٫۲'],
        'docs'      => ['fa' => 'اسناد', 'en' => 'Documents', 'count' => 128, 'kind' => 'fav', 'size' => '۴٫۸'],
        'downloads' => ['fa' => 'دانلودها', 'en' => 'Downloads', 'count' => 9, 'kind' => 'fav', 'size' => '۰٫۶'],
        'drive'     => ['fa' => 'iCloud Drive', 'en' => 'iCloud Drive', 'count' => 31, 'kind' => 'cloud', 'size' => '۲٫۱'],
        'shared'    => ['fa' => 'مشترک‌ها', 'en' => 'Shared', 'count' => 5, 'kind' => 'cloud', 'size' => '۰٫۳'],
    ];
@endphp
<style>
    .mcside-root {
        --mcside-win: #ECECEC; --mcside-text: #1E1E1E; --mcside-text2: #6D6D72; --mcside-accent: #007AFF;
        --mcside-hair: rgba(0, 0, 0, .15); --mcside-div: rgba(0, 0, 0, .1);
        --mcside-side: rgba(236, 236, 236, .72);
        --mcside-red: #FF5F57; --mcside-yellow: #FEBC2E; --mcside-green: #28C840;
        --mcside-wall: linear-gradient(140deg, #3E5C86 0%, #8A6396 55%, #DE9A74 100%);
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcside-root {
        --mcside-win: #282828; --mcside-text: #F5F5F5; --mcside-text2: #A5A5AA; --mcside-accent: #0A84FF;
        --mcside-hair: rgba(255, 255, 255, .15); --mcside-div: rgba(255, 255, 255, .1);
        --mcside-side: rgba(40, 40, 40, .72);
        --mcside-wall: linear-gradient(140deg, #1D2C46 0%, #46304F 55%, #6E4630 100%);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcside-root {
            --mcside-win: #282828; --mcside-text: #F5F5F5; --mcside-text2: #A5A5AA; --mcside-accent: #0A84FF;
            --mcside-hair: rgba(255, 255, 255, .15); --mcside-div: rgba(255, 255, 255, .1);
            --mcside-side: rgba(40, 40, 40, .72);
            --mcside-wall: linear-gradient(140deg, #1D2C46 0%, #46304F 55%, #6E4630 100%);
        }
    }

    .mcside-stage {
        position: relative; overflow: clip; display: grid;
        /* Pin the single column to the stage: an auto track would size to the
           window's max-content and let it poke out of narrow stages. */
        grid-template-columns: minmax(0, 1fr); place-items: center;
        min-block-size: 27rem; padding: 2.25rem 1rem; border-radius: var(--nx-radius-2xl);
        background: var(--mcside-wall);
    }
    .mcside-win {
        position: relative; z-index: 1; display: grid; grid-template-rows: 38px minmax(0, 1fr);
        inline-size: min(100%, 30rem); block-size: 22rem; border-radius: 10px; overflow: clip;
        color: var(--mcside-text); background: color-mix(in srgb, var(--mcside-win) 84%, transparent);
        backdrop-filter: blur(20px) saturate(1.4);
        box-shadow: 0 22px 70px 4px rgba(0, 0, 0, .28), 0 0 0 .5px rgba(0, 0, 0, .26);
    }
    .mcside-bar {
        display: flex; align-items: center; padding-inline: 12px;
        background: color-mix(in srgb, var(--mcside-win) 72%, transparent);
        border-block-end: 1px solid var(--mcside-hair);
    }
    .mcside-lights { display: flex; gap: 8px; }
    .mcside-lights i { inline-size: 12px; aspect-ratio: 1; border-radius: 50%; box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .22); }
    .mcside-lights i:nth-child(1) { background: var(--mcside-red); }
    .mcside-lights i:nth-child(2) { background: var(--mcside-yellow); }
    .mcside-lights i:nth-child(3) { background: var(--mcside-green); }
    .mcside-wtitle { margin-inline: auto; translate: -20px 0; font: 600 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    [dir="rtl"] .mcside-wtitle { translate: 20px 0; }

    .mcside-body { display: grid; grid-template-columns: clamp(7.5rem, 55%, 12.25rem) minmax(0, 1fr); min-block-size: 0; }
    .mcside-list {
        display: flex; flex-direction: column; overflow: auto; padding: 8px;
        background: var(--mcside-side); border-inline-end: 1px solid var(--mcside-hair);
    }
    .mcside-list h4 {
        margin: 2px 0 4px; padding-inline: 8px; flex: none;
        font: 700 11px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcside-text2);
    }
    .mcside-row {
        display: flex; align-items: center; gap: 7px; flex: none;
        block-size: 28px; padding-inline: 8px; border: 0; border-radius: 5px;
        cursor: pointer; inline-size: 100%; text-align: start; background: transparent;
        font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcside-text);
    }
    .mcside-row:hover { background: rgba(0, 0, 0, .06); }
    html[data-theme="dark"] .mcside-row:hover { background: rgba(255, 255, 255, .08); }
    .mcside-row[data-current] { background: var(--mcside-accent); color: #fff; }
    .mcside-row svg { inline-size: 16px; block-size: 16px; flex: none; }
    /* Finder truncates long source names; the trailing count always stays whole. */
    .mcside-row span { min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .mcside-row b { margin-inline-start: auto; font: 500 11px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; font-variant-numeric: tabular-nums; opacity: .75; }
    .mcside-row[data-current] b { opacity: .95; }
    .mcside-empty { margin: 2px 0 6px; padding-inline: 8px; font: 400 12px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcside-text2); }

    .mcside-filter { position: relative; flex: none; margin-top: auto; padding-block-start: 6px; }
    .mcside-filter svg { position: absolute; inset-block-start: calc(50% - 1px); inset-inline-start: 9px; translate: 0 -50%; inline-size: 12px; block-size: 12px; fill: none; stroke: var(--mcside-text2); stroke-width: 1.8; stroke-linecap: round; }
    .mcside-filter input {
        inline-size: 100%; block-size: 26px; padding-inline: 26px 9px; border: 0; border-radius: 5px;
        background: color-mix(in srgb, var(--mcside-win) 55%, transparent);
        box-shadow: 0 0 0 .5px var(--mcside-hair);
        color: var(--mcside-text); font: 400 12px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    .mcside-filter input::placeholder { color: var(--mcside-text2); }

    /* `safe` keeps centred content reachable when the pane is ever narrower than it. */
    .mcside-pane { display: grid; place-content: center; place-content: safe center; justify-items: center; gap: 6px; padding: 16px; text-align: center; overflow: auto; }
    .mcside-pane-ico {
        display: grid; place-items: center; inline-size: 58px; aspect-ratio: 1; border-radius: 16px;
        background: linear-gradient(160deg, #6EC6FF, #0A63C9); box-shadow: 0 10px 24px rgba(10, 99, 201, .35);
        font-size: 26px;
    }
    .mcside-pane b { font: 600 15px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcside-pane small { color: var(--mcside-text2); font-size: 12px; }
    .mcside-root :is(button, input):focus-visible { outline: 2px solid var(--mcside-accent); outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .mcside-list, .mcside-row, .mcside-pane { transition: none; scroll-behavior: auto; } }

    /* The shared snippet below this stage sets the mono stack, which has no
       Persian glyphs; the sample Persian words fell through to a mismatched
       system fallback — reordered mid-word («هاپروژه») and colliding with the
       adjacent code punctuation (<h4>…, <span>…, <button>…) in either theme.
       Let Persian resolve into Vazirmatn, already loaded by the layout, while
       Latin code keeps the mono face — scoped through :has(.mcside-root), so
       it never reaches another demo page. */
    .pg:has(.mcside-root) pre code { font-family: var(--nx-font-mono), "Vazirmatn", sans-serif; }

    /* At phone widths the shared "Important props" table's nowrap cells and
       the snippet's long lines run past the inline edge — the fourth column
       read as "Wha…/Tigh…" and the snippet itself clipped. Let this page's
       table and snippet wrap so every value stays visible — same remedy the
       controls page ships for its own table. */
    @media (max-width: 480px) {
        .pg:has(.mcside-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.mcside-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
</style>

<section class="pg-box mcside-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Source-list sidebar', 'نوار کنارِ فهرست') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Click a row to select it — the blue row with white text — and type in the filter field to live-filter both sections; the content pane follows the selection.', 'برای انتخاب، روی ردیفی بزنید — ردیف آبی با متن سفید — و در فیلد پایین بنویسید تا هر دو بخش زنده فیلتر شوند؛ قاب محتوا هم از انتخاب پیروی می‌کند.') }}
        </p>
    </div>

    <div class="mcside-stage"
         x-data="{
                fa: {{ $fa ? 'true' : 'false' }},
                q: '', current: 'projects',
                rows: {{ json_encode($rows) }},
                hit(id) {
                    if (!this.q.trim()) return true;
                    const r = this.rows[id], s = this.q.trim().toLowerCase();
                    return r.fa.includes(this.q.trim()) || r.en.toLowerCase().includes(s);
                },
                name(id) { const r = this.rows[id]; return this.fa ? r.fa : r.en },
                n2(v) { const s = String(v); return this.fa ? s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : s },
            }">
        <div class="mcside-win">
            <div class="mcside-bar">
                <span class="mcside-lights" aria-hidden="true"><i></i><i></i><i></i></span>
                <span class="mcside-wtitle">{{ $say('Finder', 'فایندر') }}</span>
            </div>
            <div class="mcside-body">
                <aside class="mcside-list" role="navigation" aria-label="{{ $say('Sources', 'منبع‌ها') }}">
                    <h4 x-show="['projects', 'docs', 'downloads'].some(id => hit(id))">{{ $say('Favourites', 'موردعلاقه‌ها') }}</h4>
                    <button type="button" class="mcside-row" x-show="hit('projects')" x-on:click="current = 'projects'"
                            :data-current="current === 'projects' ? '' : null">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><path fill="#4A9DF8" d="M1.5 4.2c0-.9.7-1.6 1.6-1.6h2.3l1.3 1.6h6.2c.9 0 1.6.7 1.6 1.6v5.9c0 .9-.7 1.6-1.6 1.6H3.1c-.9 0-1.6-.7-1.6-1.6z"/></svg>
                        <span x-text="name('projects')">پروژه‌ها</span>
                        <b x-text="n2(rows.projects.count)">۲۴</b>
                    </button>
                    <button type="button" class="mcside-row" x-show="hit('docs')" x-on:click="current = 'docs'"
                            :data-current="current === 'docs' ? '' : null">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><path fill="#8E8E93" d="M3.5 1.5h6L13 5v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2.5a1 1 0 0 1 1-1z"/><path fill="#C9C9CE" d="M9.5 1.5L13 5H9.5z"/></svg>
                        <span x-text="name('docs')">اسناد</span>
                        <b x-text="n2(rows.docs.count)">۱۲۸</b>
                    </button>
                    <button type="button" class="mcside-row" x-show="hit('downloads')" x-on:click="current = 'downloads'"
                            :data-current="current === 'downloads' ? '' : null">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8 2v7M5.2 6.4L8 9.2l2.8-2.8M2.5 11v1.6c0 .8.6 1.4 1.4 1.4h8.2c.8 0 1.4-.6 1.4-1.4V11" fill="none" stroke="#32A852" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span x-text="name('downloads')">دانلودها</span>
                        <b x-text="n2(rows.downloads.count)">۹</b>
                    </button>

                    <h4 x-show="['drive', 'shared'].some(id => hit(id))">iCloud</h4>
                    <button type="button" class="mcside-row" x-show="hit('drive')" x-on:click="current = 'drive'"
                            :data-current="current === 'drive' ? '' : null">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><path fill="#3E9BFF" d="M12.4 7.1A4 4 0 0 0 4.7 6 3.3 3.3 0 0 0 5 12.5h7a2.7 2.7 0 0 0 .4-5.4z"/></svg>
                        <span>iCloud Drive</span>
                        <b x-text="n2(rows.drive.count)">۳۱</b>
                    </button>
                    <button type="button" class="mcside-row" x-show="hit('shared')" x-on:click="current = 'shared'"
                            :data-current="current === 'shared' ? '' : null">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><g fill="#30B0C7"><circle cx="5.6" cy="5.6" r="2.3"/><circle cx="11" cy="6.4" r="1.8"/><path d="M1.8 12.6c0-2 1.7-3.4 3.8-3.4s3.8 1.4 3.8 3.4v.4H1.8zM10 12.9v-.3c0-1.2-.5-2.2-1.3-2.9.7-.4 1.5-.6 2.3-.6 1.8 0 3.2 1.1 3.2 2.8v1z"/></g></svg>
                        <span x-text="name('shared')">مشترک‌ها</span>
                        <b x-text="n2(rows.shared.count)">۵</b>
                    </button>

                    <div class="mcside-filter">
                        <svg viewBox="0 0 16 16" aria-hidden="true"><circle cx="7" cy="7" r="4.4"/><path d="M10.4 10.4L14 14"/></svg>
                        <input type="search" x-model="q"
                               :placeholder="fa ? 'جست‌وجو' : 'Search'"
                               aria-label="{{ $say('Filter sources', 'فیلتر منبع‌ها') }}">
                    </div>
                </aside>

                <main class="mcside-pane">
                    <span class="mcside-pane-ico" aria-hidden="true">
                        <span x-show="current !== 'shared'">📂</span>
                        <span x-cloak x-show="current === 'shared'">👥</span>
                    </span>
                    <b x-text="name(current)">پروژه‌ها</b>
                    <small>
                        <span x-text="n2(rows[current].count)"></span>
                        {{ $say('items ·', 'مورد ·') }}
                        <span x-text="rows[current].size"></span> {{ $say('GB in iCloud', 'گیگابایت در آی‌کلاود') }}
                    </small>
                </main>
            </div>
        </div>
    </div>
</section>
