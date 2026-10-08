{{--
    macOS Spotlight on a fake desktop: ⌘K (or the magnifier button) dims and
    blurs the stage and opens a centred glass panel with a big field, grouped
    and live-filtered results, ↑/↓ selection with the blue row, Enter flashing
    the hit open, and a preview pane showing the selected item's meta.
    Escape closes and focus returns to the field on open. The panel starts
    open so the demo reads as Spotlight at a glance.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $spotItems = [
        ['id' => 'calc', 'g' => 'apps', 'icon' => '🧮', 'grad' => 'linear-gradient(160deg, #5A5A5E, #26262A)', 'fa' => 'ماشین‌حساب', 'en' => 'Calculator',
         'kind' => ['fa' => 'برنامه — کاربردی', 'en' => 'Application — Utilities'], 'meta' => ['fa' => 'نسخه ۱۵٫۰ · آخرین بازدید دیروز', 'en' => 'Version 15.0 · opened yesterday']],
        ['id' => 'notes', 'g' => 'apps', 'icon' => '📝', 'grad' => 'linear-gradient(160deg, #FFFFFF 0%, #FFFFFF 34%, #FFE873 34%, #F0C21B 100%)', 'fa' => 'یادداشت‌ها', 'en' => 'Notes',
         'kind' => ['fa' => 'برنامه — بهره‌وری', 'en' => 'Application — Productivity'], 'meta' => ['fa' => '۴۲ یادداشت · ۳ پین‌شده', 'en' => '42 notes · 3 pinned']],
        ['id' => 'report', 'g' => 'docs', 'icon' => '📄', 'grad' => 'linear-gradient(160deg, #8E8E93, #5A5A5E)', 'fa' => 'گزارش فصلی', 'en' => 'Quarterly report',
         'kind' => ['fa' => 'سند — PDF', 'en' => 'Document — PDF'], 'meta' => ['fa' => '۲٫۴ مگابایت · ۸ مهر ۱۴۰۵', 'en' => '2.4 MB · 30 Sep 2026']],
        ['id' => 'budget', 'g' => 'docs', 'icon' => '📊', 'grad' => 'linear-gradient(160deg, #32D74B, #1E9E38)', 'fa' => 'بودجهٔ سالانه', 'en' => 'Annual budget',
         'kind' => ['fa' => 'سند — Numbers', 'en' => 'Document — Numbers'], 'meta' => ['fa' => '۱٫۱ مگابایت · ویرایش ۲ ساعت پیش', 'en' => '1.1 MB · edited 2h ago']],
    ];
@endphp
<style>
    .mcspot-root {
        --mcspot-text: #1E1E1E; --mcspot-text2: #6D6D72; --mcspot-accent: #007AFF;
        --mcspot-hair: rgba(0, 0, 0, .15); --mcspot-div: rgba(0, 0, 0, .1);
        --mcspot-glass: rgba(245, 245, 245, .92);
        --mcspot-wall: linear-gradient(140deg, #2C3F68 0%, #71538B 52%, #DD9A75 100%);
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcspot-root {
        --mcspot-text: #F5F5F5; --mcspot-text2: #A5A5AA; --mcspot-accent: #0A84FF;
        --mcspot-hair: rgba(255, 255, 255, .15); --mcspot-div: rgba(255, 255, 255, .1);
        --mcspot-glass: rgba(40, 40, 40, .85);
        --mcspot-wall: linear-gradient(140deg, #182338 0%, #3D2F4E 52%, #66422D 100%);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcspot-root {
            --mcspot-text: #F5F5F5; --mcspot-text2: #A5A5AA; --mcspot-accent: #0A84FF;
            --mcspot-hair: rgba(255, 255, 255, .15); --mcspot-div: rgba(255, 255, 255, .1);
            --mcspot-glass: rgba(40, 40, 40, .85);
            --mcspot-wall: linear-gradient(140deg, #182338 0%, #3D2F4E 52%, #66422D 100%);
        }
    }

    .mcspot-stage {
        position: relative; overflow: clip; min-block-size: 26rem; border-radius: var(--nx-radius-2xl);
        background: var(--mcspot-wall); display: grid; place-items: center; padding: 1.5rem;
        transition: filter .3s;
    }
    .mcspot-stage[data-hot] { filter: blur(5px) brightness(.55); }
    .mcspot-mag {
        position: relative; z-index: 1; display: grid; place-items: center; gap: 8px;
        padding: 18px; border: 0; border-radius: 50%; cursor: pointer;
        inline-size: 58px; aspect-ratio: 1;
        background: rgba(255, 255, 255, .18); backdrop-filter: blur(20px); color: #fff;
        box-shadow: inset 0 0 0 .5px rgba(255, 255, 255, .35), 0 10px 30px rgba(0, 0, 0, .3);
    }
    .mcspot-mag svg { inline-size: 22px; block-size: 22px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; }
    .mcspot-mag small { position: absolute; inset-block-start: calc(100% + 8px); white-space: nowrap; color: #fff; text-shadow: 0 1px 5px rgba(0, 0, 0, .6), 0 0 2px rgba(0, 0, 0, .4); font: 500 12px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif; }

    .mcspot-overlay {
        position: absolute; inset: 0; z-index: 40; display: grid; place-items: start center;
        padding: 2.5rem 1rem; background: rgba(10, 10, 12, .25); backdrop-filter: blur(2px);
    }
    .mcspot-panel {
        display: grid; grid-template-rows: auto minmax(0, 1fr); inline-size: min(90vw, 680px); max-inline-size: 100%;
        max-block-size: 100%; border-radius: 12px; overflow: clip;
        background: var(--mcspot-glass); backdrop-filter: blur(40px) saturate(1.5);
        box-shadow: 0 24px 80px rgba(0, 0, 0, .35), 0 0 0 .5px var(--mcspot-hair);
        color: var(--mcspot-text);
    }
    .mcspot-fieldrow { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-block-end: 1px solid var(--mcspot-div); }
    .mcspot-fieldrow svg { inline-size: 20px; block-size: 20px; flex: none; fill: none; stroke: var(--mcspot-text2); stroke-width: 2; stroke-linecap: round; }
    .mcspot-fieldrow input {
        flex: 1; min-inline-size: 0; block-size: 44px; border: 0; background: transparent;
        color: var(--mcspot-text); font: 400 19px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    .mcspot-fieldrow input:focus { outline: none; }
    .mcspot-fieldrow input::placeholder { color: var(--mcspot-text2); }
    .mcspot-kbd {
        flex: none; padding: 3px 7px; border-radius: 5px; box-shadow: 0 0 0 .5px var(--mcspot-hair);
        font: 600 10px/1 system-ui, sans-serif; color: var(--mcspot-text2);
    }
    .mcspot-body { display: grid; grid-template-columns: minmax(0, 1fr) 216px; min-block-size: 0; }
    .mcspot-results { margin: 0; padding: 8px; list-style: none; overflow: auto; max-block-size: 248px; }
    .mcspot-results h4 {
        margin: 8px 0 3px; padding-inline: 9px;
        font: 700 11px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcspot-text2);
    }
    .mcspot-results h4:first-child { margin-block-start: 0; }
    .mcspot-hit {
        display: flex; align-items: center; gap: 9px; inline-size: 100%; padding: 5px 9px;
        border: 0; border-radius: 6px; cursor: pointer; background: transparent; text-align: start;
        font: 400 13px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcspot-text);
    }
    .mcspot-hit:hover { background: rgba(0, 0, 0, .06); }
    html[data-theme="dark"] .mcspot-hit:hover { background: rgba(255, 255, 255, .08); }
    .mcspot-hit[data-current] { background: var(--mcspot-accent); color: #fff; }
    .mcspot-hit .mcspot-tile {
        display: grid; place-items: center; inline-size: 26px; aspect-ratio: 1; flex: none;
        border-radius: 7px; font-size: 14px; box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .18);
    }
    .mcspot-hit small { margin-inline-start: auto; font-size: 11px; opacity: .65; white-space: nowrap; }
    .mcspot-hit[data-flash] { animation: mcspot-pop .32s ease; }
    @keyframes mcspot-pop { 40% { scale: 1.04; } }
    @media (prefers-reduced-motion: reduce) { .mcspot-hit[data-flash] { animation: none; } }
    .mcspot-empty { padding: 14px 9px; color: var(--mcspot-text2); font-size: 12px; }

    .mcspot-preview {
        display: grid; align-content: start; justify-items: center; gap: 6px; padding: 18px 14px;
        border-inline-start: 1px solid var(--mcspot-div); text-align: center; overflow: auto;
    }
    .mcspot-preview .mcspot-tile {
        display: grid; place-items: center; inline-size: 64px; aspect-ratio: 1; border-radius: 16px;
        font-size: 30px; box-shadow: 0 10px 26px rgba(0, 0, 0, .22), inset 0 0 0 .5px rgba(0, 0, 0, .18);
    }
    .mcspot-preview b { font: 600 14px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcspot-preview small { color: var(--mcspot-text2); font-size: 11.5px; }
    .mcspot-preview .mcspot-enter {
        margin-block-start: 8px; padding: 3px 9px; border-radius: 5px;
        background: color-mix(in srgb, var(--mcspot-text2) 18%, transparent);
        font-size: 10.5px; color: var(--mcspot-text2);
    }
    .mcspot-root :is(button, input):focus-visible { outline: 2px solid var(--mcspot-accent); outline-offset: 2px; }
    @media (max-width: 620px) { .mcspot-body { grid-template-columns: minmax(0, 1fr); } .mcspot-preview { display: none; } }
</style>

<section class="pg-box mcspot-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">Spotlight</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Press ⌘K (or Ctrl+K) or click the magnifier: the desktop dims, results group under headers, ↑/↓ walk the blue row and Enter flashes the hit open — with a live preview beside it.', '⌘K (یا Ctrl+K) بزنید یا روی ذره‌بین کلیک کنید: رومیزی کم‌نور می‌شود، نتایج زیر سربرگ‌ها گروه می‌شوند، ↑/↓ ردیف آبی را جابه‌جا می‌کند و Enter نتیجه را با فلاش «باز» می‌کند — با پیش‌نمایش زنده در کنارش.') }}
        </p>
    </div>

    <div class="mcspot-stage"
         x-data="{
                fa: {{ $fa ? 'true' : 'false' }},
                open: true, q: '', sel: 'calc', flash: null, items: {{ json_encode($spotItems) }},
                groups: [
                    { key: 'apps', label: { fa: 'برنامه‌ها', en: 'Applications' } },
                    { key: 'docs', label: { fa: 'اسناد', en: 'Documents' } },
                    { key: 'web', label: { fa: 'جست‌وجوی وب', en: 'Web search' } },
                ],
                label(it) { return this.fa ? it.fa : it.en },
                n2(v) { const s = String(v); return this.fa ? s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : s },
                hit(it) {
                    if (! this.q.trim()) return true;
                    const s = this.q.trim().toLowerCase();
                    return it.fa.includes(this.q.trim()) || it.en.toLowerCase().includes(s);
                },
                inGroup(g) {
                    if (g.key === 'web') return [{ id: 'web', icon: '🌐', grad: 'linear-gradient(160deg, #5AC8FA, #0A63C9)', fa: 'جست‌وجوی وب برای «' + (this.q.trim() || 'نابو') + '»', en: 'Search the web for “' + (this.q.trim() || 'Nabu') + '”', kind: { fa: 'Safari', en: 'Safari' }, meta: { fa: 'موتور جست‌وجوی پیش‌فرض', en: 'Default search engine' } }];
                    return this.items.filter(it => it.g === g.key && this.hit(it));
                },
                get flat() { return this.groups.flatMap(g => this.inGroup(g)) },
                get cur() { return this.flat.find(it => it.id === this.sel) || this.flat[0] },
                get curIndex() { return this.flat.findIndex(it => it.id === this.sel) },
                move(d) {
                    if (! this.flat.length) return;
                    const i = Math.min(this.flat.length - 1, Math.max(0, this.curIndex + d));
                    this.sel = this.flat[i].id;
                },
                fire() { if (! this.cur) return; this.flash = this.cur.id; setTimeout(() => { this.flash = null }, 340) },
                show() { this.open = true; this.q = ''; this.sel = this.flat[0] ? this.flat[0].id : ''; this.$nextTick(() => this.$refs.field && this.$refs.field.focus()) },
                hot(e) { if ((e.metaKey || e.ctrlKey) && ! e.shiftKey && ! e.altKey && e.key.toLowerCase() === 'k') { e.preventDefault(); this.open ? this.open = false : this.show() } },
            }"
         :data-hot="open ? '' : null"
         x-on:keydown.window="hot($event)"
         x-on:keydown.escape.window="open = false">
        <button type="button" class="mcspot-mag" :aria-expanded="open ? 'true' : 'false'"
                aria-label="{{ $say('Open Spotlight', 'باز کردن Spotlight') }}" x-on:click="show()">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l5 5"/></svg>
            <small aria-hidden="true">⌘K</small>
        </button>

        <div class="mcspot-overlay" x-show="open" x-cloak x-transition.opacity.duration.180ms x-on:click.self="open = false">
            <div class="mcspot-panel" role="dialog" aria-modal="true" aria-label="Spotlight">
                <div class="mcspot-fieldrow">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l5 5"/></svg>
                    <input type="text" x-ref="field" x-model="q"
                           :placeholder="fa ? 'جست‌وجوی Spotlight' : 'Spotlight Search'"
                           aria-label="{{ $say('Spotlight search', 'جست‌وجوی Spotlight') }}"
                           x-on:keydown.down.prevent="move(1)"
                           x-on:keydown.up.prevent="move(-1)"
                           x-on:keydown.enter.prevent="fire()">
                    <span class="mcspot-kbd" aria-hidden="true">esc</span>
                </div>
                <div class="mcspot-body">
                    <ul class="mcspot-results" role="listbox" aria-label="{{ $say('Results', 'نتایج') }}">
                        <template x-for="g in groups" :key="g.key">
                            <li style="display: contents">
                                <div style="display: contents">
                                    <template x-if="inGroup(g).length">
                                        <h4 x-text="fa ? g.label.fa : g.label.en"></h4>
                                    </template>
                                    <template x-for="it in inGroup(g)" :key="it.id">
                                        <button type="button" class="mcspot-hit" role="option"
                                                :data-current="sel === it.id ? '' : null"
                                                :data-flash="flash === it.id ? '' : null"
                                                :aria-selected="sel === it.id ? 'true' : 'false'"
                                                x-on:click="sel = it.id" x-on:dblclick="fire()"
                                                x-on:pointerenter="sel = it.id">
                                            <span class="mcspot-tile" :style="'background:' + it.grad" aria-hidden="true" x-text="it.icon"></span>
                                            <span x-text="label(it)"></span>
                                            <small x-show="sel === it.id">⏎</small>
                                        </button>
                                    </template>
                                </div>
                            </li>
                        </template>
                        <li class="mcspot-empty" x-show="! flat.length">{{ $say('No result — try «گزارش» or «notes».', 'نتیجه‌ای نیست — «گزارش» یا «notes» را امتحان کنید.') }}</li>
                    </ul>
                    <aside class="mcspot-preview" aria-live="polite">
                        <template x-if="cur">
                            <div style="display: grid; gap: 6px; justify-items: center">
                                <span class="mcspot-tile" :style="'background:' + cur.grad" aria-hidden="true" x-text="cur.icon"></span>
                                <b x-text="label(cur)"></b>
                                <small x-text="fa ? cur.kind.fa : cur.kind.en"></small>
                                <small x-text="fa ? cur.meta.fa : cur.meta.en"></small>
                                <span class="mcspot-enter" data-flash-indicator="1">⏎ {{ $say('Open', 'باز کردن') }}</span>
                            </div>
                        </template>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>
