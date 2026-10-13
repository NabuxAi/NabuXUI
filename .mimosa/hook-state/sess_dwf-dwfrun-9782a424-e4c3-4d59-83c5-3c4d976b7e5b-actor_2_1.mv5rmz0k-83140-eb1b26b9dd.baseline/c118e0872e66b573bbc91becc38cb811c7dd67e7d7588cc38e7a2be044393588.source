{{--
    Win11 tabs as document cards: rounded tabs with document icons, a close
    button that only appears under the pointer or on the active tab, a «+»
    that appends up to five tabs, a preview peek card while you hold a tab,
    and a content line that follows the selection. Full tablist/tab/tabpanel
    semantics with arrow-key walking.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $tabs = [
        ['id' => 1, 'icon' => '📄', 'title' => $say('Sales report', 'گزارش فروش'), 'body' => $say('Fourth-quarter sales grew 18% over last year; the charts were refreshed this morning.', 'فروش فصل چهارم نسبت به سال گذشته ۱۸٪ رشد داشته؛ نمودارها امروز صبح به‌روز شدند.'), 'wc' => 214],
        ['id' => 2, 'icon' => '🗒', 'title' => $say('Meeting note', 'یادداشت جلسه'), 'body' => $say('Decisions: move the command bar up, retire the old menu, add an overflow.', 'تصمیم‌ها: نوار فرمان بالا برود، منوی قدیمی بازنشسته شود و سرریز اضافه شود.'), 'wc' => 96],
        ['id' => 3, 'icon' => '📝', 'title' => $say('Shopping list', 'لیست خرید'), 'body' => $say('Bread, feta cheese, walnuts, black tea and two packs of biscuits.', 'نان، پنیر، گردو، چای سیاه و دو بسته بیسکویت.'), 'wc' => 12],
    ];
@endphp
<style>
    .fltab-root {
        --fl-accent: #005FB8;
        --fl-on-accent: #FFFFFF;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
        --fl-control: rgba(255, 255, 255, .70);
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-control-stroke: rgba(0, 0, 0, .12);
        --fl-divider: rgba(0, 0, 0, .08);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-press: rgba(0, 0, 0, .06);
        --fl-focus: #1B1B1B;
        --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .20), 0 0 0 1px rgba(0, 0, 0, .07);
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        /* minmax(0, 1fr), not auto: an auto track sizes to the strip's
           min-content (three 9.5rem tabs + the «+»), which at phone widths
           balloons the window card past the «Real scenarios» card. */
        grid-template-columns: minmax(0, 1fr);
        gap: .75rem;
    }
    html[data-theme="dark"] .fltab-root {
        --fl-accent: #4CC2FF;
        --fl-on-accent: #000000;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-layer: rgba(255, 255, 255, .05);
        --fl-control: rgba(255, 255, 255, .06);
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-control-stroke: rgba(255, 255, 255, .10);
        --fl-divider: rgba(255, 255, 255, .06);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-press: rgba(255, 255, 255, .03);
        --fl-focus: #FFFFFF;
        --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .08);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .fltab-root {
            --fl-accent: #4CC2FF;
            --fl-on-accent: #000000;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-layer: rgba(255, 255, 255, .05);
            --fl-control: rgba(255, 255, 255, .06);
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-control-stroke: rgba(255, 255, 255, .10);
            --fl-divider: rgba(255, 255, 255, .06);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-press: rgba(255, 255, 255, .03);
            --fl-focus: #FFFFFF;
            --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .08);
        }
    }
    .fltab-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .fltab-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }

    .fltab-app {
        position: relative; inline-size: min(100%, 30rem); margin-inline: auto;
        min-inline-size: 0; /* grid-item auto minimum would keep the card at strip min-content */
        border-radius: 8px; overflow: clip; background: var(--fl-window);
        box-shadow: 0 24px 48px rgba(0, 0, 0, .16), 0 0 0 1px var(--fl-card-stroke);
    }
    .fltab-strip {
        position: relative; display: flex; align-items: center; gap: 2px;
        padding: .35rem .5rem 0; border-block-end: 1px solid var(--fl-divider); overflow: hidden;
    }
    .fltab-tab {
        display: inline-flex; align-items: center; gap: .4rem; min-inline-size: 0; max-inline-size: 9.5rem;
        flex: 0 1 auto; block-size: 2rem; padding-inline: .7rem; border-radius: 4px;
        font-size: .78rem; transition: background .12s, box-shadow .12s;
    }
    .fltab-tab:hover { background: var(--fl-hover); }
    .fltab-tab:active { background: var(--fl-press); }
    .fltab-tab[aria-selected="true"] { background: var(--fl-control); box-shadow: inset 0 0 0 1px var(--fl-card-stroke), 0 2px 4px rgba(0, 0, 0, .08); font-weight: 600; }
    .fltab-ico { flex: none; font-size: .8rem; }
    .fltab-title { min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .fltab-x {
        display: grid; place-items: center; flex: none; inline-size: 1.15rem; block-size: 1.15rem;
        border-radius: 3px; opacity: 0; transition: opacity .12s, background .12s;
    }
    .fltab-tab:hover .fltab-x, .fltab-tab[aria-selected="true"] .fltab-x { opacity: 1; }
    .fltab-x:hover { background: var(--fl-hover); }
    .fltab-tab[aria-selected="true"] .fltab-x:hover { background: rgba(0, 0, 0, .08); }
    .fltab-add {
        flex: none; display: grid; place-items: center; inline-size: 1.8rem; block-size: 1.8rem;
        margin-inline-start: .15rem; margin-block-end: .15rem; border-radius: 4px; transition: background .12s;
    }
    .fltab-add:hover { background: var(--fl-hover); }
    .fltab-add:disabled { opacity: .35; cursor: default; background: none; }

    .fltab-preview {
        position: absolute; inset-inline: .75rem; inset-block-start: 2.7rem; z-index: 25;
        padding: .8rem 1rem; border-radius: 8px; pointer-events: none;
        background: var(--fl-layer);
        backdrop-filter: blur(30px) saturate(125%); -webkit-backdrop-filter: blur(30px) saturate(125%);
        box-shadow: var(--fl-flyout-shadow);
    }
    .fltab-preview strong { display: block; font-size: .78rem; }
    .fltab-preview p { margin: .25rem 0 0; font-size: .72rem; line-height: 1.8; color: var(--fl-text-2); }

    .fltab-page { padding: 1rem 1.25rem 1.2rem; min-block-size: 8.5rem; }
    .fltab-page h4 { margin: 0; font-size: .95rem; }
    .fltab-page > p { margin: .4rem 0 0; font-size: .8rem; line-height: 2; }
    .fltab-meta { display: flex; flex-wrap: wrap; gap: .35rem 1.1rem; margin-block-start: .8rem; padding-block-start: .5rem; border-block-start: 1px solid var(--fl-divider); font-size: .68rem; color: var(--fl-text-2); }
    .fltab-hint { margin: 0; text-align: center; font-size: .72rem; color: var(--fl-text-2); }

    /* The shared «Important props» table this page renders below the stage
       reveals its rows on scroll: tbody rows sit at opacity: 0 until an
       IntersectionObserver stamps [data-nx-revealed] on the table, and once
       .nx-live is set the 2.5s CSS failsafe is off — so a full-page capture,
       which never scrolls, sees a header with an empty body. Keep this page's
       rows visible; scoped through :has(.fltab-root) so it never reaches
       another demo page. */
    :where(.nx-js) .pg:has(.fltab-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge, and
       the tab chrome needs to tighten so three tabs plus the «+» fit inside
       the window card. */
    @media (max-width: 480px) {
        .pg:has(.fltab-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .fltab-tab { padding-inline: .45rem; gap: .3rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .fltab-tab, .fltab-x, .fltab-add { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Documents as window cards', 'سندها به‌شکل کارت پنجره') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The close ✕ appears only under the pointer or on the active tab, the «+» adds up to five tabs, and resting on a tab peeks a preview. Arrow keys walk the strip; Delete closes.', 'بستن ✕ فقط زیر اشاره‌گر یا روی تب فعال دیده می‌شود، «+» تا پنج تب اضافه می‌کند و مکث روی هر تب پیش‌نمایشی نشان می‌دهد. کلیدهای جهت در نوار می‌روند و Delete می‌بندد.') }}
        </p>
    </div>

    <div class="fltab-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            tabs: {{ json_encode($tabs, JSON_UNESCAPED_UNICODE) }},
            sel: 0, nextId: 4, preview: null, holdT: null,
            closeWord: {{ json_encode($say('Close', 'بستن')) }},
            get cur() { return this.tabs[this.sel] },
            get canClose() { return this.tabs.length > 1 },
            get canAdd() { return this.tabs.length < 5 },
            toFa(n) { const s = String(n); return this.fa ? s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : s },
            close(i) { if (this.tabs.length < 2) return; this.tabs.splice(i, 1); this.sel = Math.min(this.sel, this.tabs.length - 1); this.preview = null },
            add() {
                if (!this.canAdd) return;
                const n = this.nextId++;
                this.tabs.push({
                    id: n, icon: '📄',
                    title: this.fa ? ('سند تازهٔ ' + this.toFa(n - 3)) : ('New document ' + (n - 3)),
                    body: this.fa ? 'این سند تازه است؛ نوشتن را شروع کنید.' : 'A fresh document — start writing.',
                    wc: 0,
                });
                this.sel = this.tabs.length - 1;
            },
            hold(i) { clearTimeout(this.holdT); this.holdT = setTimeout(() => { this.preview = i }, 550) },
            unhold() { clearTimeout(this.holdT); this.preview = null },
            move(d) { this.sel = (this.sel + d + this.tabs.length) % this.tabs.length; this.$nextTick(() => { const all = this.$root.querySelectorAll('.fltab-tab'); if (all[this.sel]) all[this.sel].focus() }) },
        }">
        <div class="fltab-app">
            <div class="fltab-strip" role="tablist" aria-label="{{ $say('Open documents', 'سندهای باز') }}">
                <template x-for="(t, i) in tabs" :key="t.id">
                    <button type="button" class="fltab-tab" role="tab" :id="'fltab-t' + t.id"
                        :aria-selected="i === sel ? 'true' : 'false'" :tabindex="i === sel ? 0 : -1"
                        x-on:click="sel = i; unhold()"
                        x-on:mouseenter="hold(i)" x-on:mouseleave="unhold()"
                        x-on:keydown.arrow-right.prevent="move(fa ? -1 : 1)"
                        x-on:keydown.arrow-left.prevent="move(fa ? 1 : -1)"
                        x-on:keydown.delete.prevent="close(i)">
                        <span class="fltab-ico" aria-hidden="true" x-text="t.icon"></span>
                        <span class="fltab-title" x-text="t.title"></span>
                        <span class="fltab-x" role="button" tabindex="-1" x-show="canClose"
                            :aria-label="closeWord + ' ' + t.title"
                            x-on:click.stop="close(i)">
                            <svg width="7" height="7" viewBox="0 0 8 8" aria-hidden="true"><path d="M1 1l6 6M7 1l-6 6" stroke="currentColor" stroke-width="1.1" fill="none"/></svg>
                        </span>
                    </button>
                </template>
                <button type="button" class="fltab-add" :disabled="!canAdd" aria-label="{{ $say('New tab', 'تب جدید') }}"
                    title="{{ $say('New tab — up to 5', 'تب جدید — تا ۵ تب') }}"
                    x-on:click="add()">
                    <svg width="11" height="11" viewBox="0 0 12 12" aria-hidden="true"><path d="M6 1.5v9M1.5 6h9" stroke="currentColor" stroke-width="1.3" fill="none"/></svg>
                </button>
            </div>

            <div class="fltab-preview" x-show="preview !== null && tabs[preview]" x-cloak aria-hidden="true">
                <strong x-text="tabs[preview]?.title"></strong>
                <p x-text="tabs[preview]?.body"></p>
            </div>

            <div class="fltab-page" role="tabpanel" :aria-labelledby="'fltab-t' + cur.id">
                <h4 x-text="cur.title">{{ $tabs[0]['title'] }}</h4>
                <p x-text="cur.body">{{ $tabs[0]['body'] }}</p>
                <div class="fltab-meta">
                    <span x-text="(fa ? 'واژه‌ها: ' : 'Words: ') + toFa(cur.wc)">{{ $say('Words: 214', 'واژه‌ها: ۲۱۴') }}</span>
                    <span x-text="fa ? 'ذخیرهٔ خودکار: روشن' : 'Autosave: on'">{{ $say('Autosave: on', 'ذخیرهٔ خودکار: روشن') }}</span>
                    <span x-text="toFa(tabs.length) + (fa ? ' از ۵ تب' : ' of 5 tabs')">{{ $say('3 of 5 tabs', '۳ از ۵ تب') }}</span>
                </div>
            </div>
        </div>
        <p class="fltab-hint">
            {{ $say('Rest on a tab for the preview peek; the last tab refuses to close.', 'برای پیش‌نمایش روی تب مکث کنید؛ آخرین تب بسته نمی‌شود.') }}
        </p>
    </div>
</section>
