{{--
    Explorer's command bar: four icon+label buttons that repack into the
    «⋯» overflow as the stage narrows (driven by a width slider and live
    measurement), with vertical separators, an acrylic overflow menu, a
    compact icon-over-label mode, and the classic File/Edit/View menu bar
    with one working acrylic dropdown.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $cmds = [
        ['icon' => '✚', 'label' => $say('New', 'جدید')],
        ['icon' => '✂', 'label' => $say('Cut', 'برش')],
        ['icon' => '⧉', 'label' => $say('Copy', 'کپی')],
        ['icon' => '✎', 'label' => $say('Rename', 'تغییر نام')],
    ];
    $fileItems = [
        $say('Open folder…', 'باز کردن پوشه…'),
        $say('Save as…', 'ذخیره به‌عنوان…'),
    ];
    $files = [
        ['icon' => '📁', 'name' => $say('Projects', 'پروژه‌ها'), 'meta' => $say('24 items', '۲۴ آیتم')],
        ['icon' => '📊', 'name' => $say('Sales report', 'گزارش فروش'), 'meta' => $say('2 MB', '۲ مگابایت')],
        ['icon' => '🖼', 'name' => $say('Trip photos', 'عکس‌های سفر'), 'meta' => $say('48 MB', '۴۸ مگابایت')],
        ['icon' => '🗒', 'name' => $say('Meeting note', 'یادداشت جلسه'), 'meta' => $say('12 KB', '۱۲ کیلوبایت')],
    ];
@endphp
<style>
    .flcmd-root {
        --fl-accent: #005FB8;
        --fl-on-accent: #FFFFFF;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
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
        gap: .75rem;
    }
    html[data-theme="dark"] .flcmd-root {
        --fl-accent: #4CC2FF;
        --fl-on-accent: #000000;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-layer: rgba(255, 255, 255, .05);
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-control-stroke: rgba(255, 255, 255, .10);
        --fl-divider: rgba(255, 255, 255, .06);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-press: rgba(255, 255, 255, .03);
        --fl-focus: #FFFFFF;
        --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .08);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flcmd-root {
            --fl-accent: #4CC2FF;
            --fl-on-accent: #000000;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-layer: rgba(255, 255, 255, .05);
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-control-stroke: rgba(255, 255, 255, .10);
            --fl-divider: rgba(255, 255, 255, .06);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-press: rgba(255, 255, 255, .03);
            --fl-focus: #FFFFFF;
            --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .08);
        }
    }
    /* :where(button) holds this reset at (0,1,0). A plain `.flcmd-root button`
       sits at (0,1,1) and outranks .flcmd-btn/.flcmd-menubtn below, zeroing
       their padding — that is what glued File/Edit/View into «FileEditView». */
    .flcmd-root :where(button) { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .flcmd-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }

    .flcmd-widthctl { display: flex; align-items: center; justify-content: center; gap: .6rem; font-size: .75rem; color: var(--fl-text-2); }
    .flcmd-widthctl input { inline-size: min(14rem, 60vw); accent-color: var(--fl-accent); }

    .flcmd-stage {
        position: relative; overflow: clip; inline-size: min(100%, 34rem); margin-inline: auto;
        border-radius: 8px; background: var(--fl-window);
        box-shadow: 0 24px 48px rgba(0, 0, 0, .16), 0 0 0 1px var(--fl-card-stroke);
    }
    .flcmd-titlebar { display: flex; align-items: center; gap: .5rem; block-size: 2.25rem; padding-inline: .875rem; border-block-end: 1px solid var(--fl-divider); font-size: .75rem; }
    .flcmd-addr { display: flex; align-items: center; gap: .4rem; margin: .5rem .875rem 0; padding: .3rem .7rem; border-radius: 4px; background: var(--fl-layer); box-shadow: inset 0 0 0 1px var(--fl-control-stroke); font-size: .72rem; color: var(--fl-text-2); white-space: nowrap; overflow: hidden; }
    .flcmd-addr b { color: var(--fl-text); font-weight: 600; }

    .flcmd-bar { position: relative; display: flex; align-items: center; gap: 2px; padding: 4px 6px; margin-block-start: .5rem; border-block: 1px solid var(--fl-divider); overflow: visible; }
    .flcmd-btn { position: relative; display: inline-flex; flex-direction: row; align-items: center; justify-content: center; gap: .5rem; block-size: 34px; padding-inline: 10px; border-radius: 4px; font-size: .78rem; white-space: nowrap; flex: none; transition: background .12s, color .12s; }
    .flcmd-btn:hover { background: var(--fl-hover); }
    .flcmd-btn:active { background: var(--fl-press); color: var(--fl-text-2); }
    .flcmd-ico { font-size: .85rem; line-height: 1; }
    .flcmd-lbl { font-size: .72rem; }
    .flcmd-bar[data-compact="true"] .flcmd-btn { flex-direction: column; gap: 1px; block-size: 44px; padding-inline: 8px; }
    .flcmd-bar[data-compact="true"] .flcmd-lbl { font-size: .62rem; }
    .flcmd-sep { inline-size: 1px; align-self: stretch; margin-block: 6px; background: var(--fl-control-stroke); flex: none; }
    .flcmd-overwrap { display: grid; flex: none; }
    .flcmd-more { min-inline-size: 34px; padding-inline: 0; font-size: .9rem; letter-spacing: 1px; }

    .flcmd-menu {
        position: absolute; inset-block-start: calc(100% + 3px); inset-inline-end: 6px; z-index: 25;
        min-inline-size: 11rem; max-inline-size: calc(100% - 12px); padding: .3rem; border-radius: 8px; display: grid;
        background: var(--fl-layer);
        backdrop-filter: blur(30px) saturate(125%); -webkit-backdrop-filter: blur(30px) saturate(125%);
        box-shadow: var(--fl-flyout-shadow);
    }
    .flcmd-menu button { display: flex; align-items: center; gap: .6rem; padding: .45rem .6rem; border-radius: 4px; font-size: .78rem; text-align: start; }
    .flcmd-menu button:hover { background: var(--fl-hover); }
    .flcmd-menu button:active { background: var(--fl-press); color: var(--fl-text-2); }
    .flcmd-mi-wrap { display: contents; }
    .flcmd-menusep { block-size: 1px; margin: .25rem .4rem; background: var(--fl-divider); }

    .flcmd-menubar { display: flex; align-items: center; gap: .3rem; padding: 2px 6px; border-block-end: 1px solid var(--fl-divider); }
    .flcmd-menuwrap { position: relative; }
    .flcmd-menubtn { padding: .3rem .6rem; border-radius: 4px; font-size: .75rem; transition: background .12s; }
    .flcmd-menubtn:hover, .flcmd-menubtn[aria-expanded="true"] { background: var(--fl-hover); }

    .flcmd-files { display: grid; padding: .35rem .5rem .5rem; }
    .flcmd-file { display: flex; align-items: center; gap: .7rem; padding: .45rem .6rem; border-radius: 4px; font-size: .78rem; transition: background .12s; }
    .flcmd-file:hover { background: var(--fl-hover); }
    .flcmd-file[aria-current="true"] { background: color-mix(in srgb, var(--fl-accent) 12%, transparent); box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--fl-accent) 35%, transparent); }
    .flcmd-file small { margin-inline-start: auto; font-size: .68rem; color: var(--fl-text-2); }
    .flcmd-status { margin: 0; padding: .45rem .875rem .6rem; border-block-start: 1px solid var(--fl-divider); font-size: .7rem; color: var(--fl-text-2); }
    .flcmd-hint { margin: 0; text-align: center; font-size: .72rem; color: var(--fl-text-2); }

    /* The demo page around this partial: the props table hides its rows until a
       scroll-reveal observer fires — the served nabuxui.css sets
       `.nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 0 }`
       at specificity (0,3,2), and .nx-live (added client-side) disables the 2.5s
       CSS failsafe — so a static capture shows a header over an empty body. Its
       nowrap cells also clip the last header column on phones. Pin the readable
       state with !important (a plain override loses the (0,3,2) fight); :has()
       keeps this scoped to pages hosting this partial. */
    body:has(.flcmd-root) .nx-data-table tbody tr { opacity: 1 !important; translate: none !important; animation: none !important; }
    body:has(.flcmd-root) .nx-data-table th,
    body:has(.flcmd-root) .nx-data-table td { white-space: normal; overflow-wrap: anywhere; }

    @media (prefers-reduced-motion: reduce) {
        .flcmd-btn, .flcmd-file, .flcmd-menubtn { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A command bar that repacks itself', 'نوار فرمانی که خودش جابه‌جا می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag the width slider (or resize the window) and the commands that no longer fit drop into the «⋯» overflow. Squeeze further and labels slide under the icons; the classic File/Edit/View row is below.', 'لغزندهٔ عرض را بکشید (یا پنجره را کوچک کنید) تا فرمان‌های جا‌نشدنی به سرریز «⋯» بروند. اگر بیشتر باریک شود، لیبل‌ها زیر آیکن می‌روند؛ ردیف کلاسیک «پرونده/ویرایش/نمایش» هم پایین است.') }}
        </p>
    </div>

    <div class="flcmd-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            w: 0, mw: 520, overflowOpen: false, fileMenu: false,
            cmds: {{ json_encode($cmds, JSON_UNESCAPED_UNICODE) }},
            fileItems: {{ json_encode($fileItems, JSON_UNESCAPED_UNICODE) }},
            get fit() { return this.mw >= 430 ? 4 : this.mw >= 330 ? 3 : this.mw >= 240 ? 2 : 1 },
            get visible() { return this.cmds.slice(0, this.fit) },
            get hidden() { return this.cmds.slice(this.fit) },
            init() { this.measure(); this._onR = () => this.measure(); window.addEventListener('resize', this._onR) },
            destroy() { window.removeEventListener('resize', this._onR) },
            measure() { this.$nextTick(() => { this.mw = this.$refs.stage.clientWidth }) },
            slide(e) { this.w = +e.target.value; this.measure() },
            run(label) { this.msg = this.fa ? ('«' + label + '» اجرا شد') : ('Ran: ' + label) },
            msg: {{ json_encode($say('Ready', 'آماده')) }},
        }"
        x-on:keydown.escape.window="overflowOpen = false; fileMenu = false">
        <div class="flcmd-widthctl">
            <label for="flcmd-w">{{ $say('Stage width', 'عرض صحنه') }}</label>
            <input id="flcmd-w" type="range" min="240" max="560" step="10" value="540" x-on:input="slide($event)">
        </div>

        <div class="flcmd-stage" x-ref="stage" :style="w ? ('inline-size:' + w + 'px') : ''">
            <div class="flcmd-titlebar">
                <span aria-hidden="true">📁</span>
                <span style="min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap">{{ $say('Files — This PC › Documents › Projects', 'فایل‌ها — این کامپیوتر › اسناد › پروژه‌ها') }}</span>
            </div>
            <div class="flcmd-addr">
                {{ $say('This PC', 'این کامپیوتر') }} <span aria-hidden="true">›</span> {{ $say('Documents', 'اسناد') }} <span aria-hidden="true">›</span> <b>{{ $say('Projects', 'پروژه‌ها') }}</b>
            </div>

            <div class="flcmd-bar" role="toolbar" aria-label="{{ $say('Folder commands', 'فرمان‌های پوشه') }}"
                :data-compact="mw < 360 ? 'true' : 'false'">
                <template x-for="c in visible" :key="c.label">
                    <button type="button" class="flcmd-btn" x-on:click="run(c.label)">
                        <span class="flcmd-ico" aria-hidden="true" x-text="c.icon"></span>
                        <span class="flcmd-lbl" x-text="c.label"></span>
                    </button>
                </template>
                <span class="flcmd-sep" aria-hidden="true" x-show="hidden.length"></span>
                <div class="flcmd-overwrap" x-ref="overwrap" x-show="hidden.length" x-cloak>
                    <button type="button" class="flcmd-btn flcmd-more" aria-haspopup="menu"
                        :aria-expanded="overflowOpen ? 'true' : 'false'"
                        :aria-label="hidden.length + (fa ? ' فرمان در سرریز' : ' more commands in overflow')"
                        x-on:click="overflowOpen = !overflowOpen">⋯</button>
                    <div class="flcmd-menu" role="menu" :aria-label="fa ? 'منوی سرریز' : 'Overflow menu'" x-show="overflowOpen" x-transition.opacity.duration.150ms
                        x-on:click.outside="if (! $refs.overwrap.contains($event.target)) overflowOpen = false">
                        <template x-for="c in hidden" :key="c.label">
                            <div class="flcmd-mi-wrap">
                                <button type="button" role="menuitem" x-on:click="overflowOpen = false; run(c.label)">
                                    <span aria-hidden="true" x-text="c.icon"></span>
                                    <span x-text="c.label"></span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flcmd-menubar" role="menubar" aria-label="{{ $say('Menu bar', 'نوار منو') }}">
                <div class="flcmd-menuwrap" x-ref="filewrap">
                    <button type="button" class="flcmd-menubtn" aria-haspopup="menu" :aria-expanded="fileMenu ? 'true' : 'false'"
                        x-on:click="fileMenu = !fileMenu">{{ $say('File', 'پرونده') }}</button>
                    <div class="flcmd-menu" role="menu" :aria-label="fa ? 'منوی پرونده' : 'File menu'" x-show="fileMenu" x-cloak x-transition.opacity.duration.150ms
                        x-on:click.outside="if (! $refs.filewrap.contains($event.target)) fileMenu = false">
                        <template x-for="item in fileItems" :key="item">
                            <div class="flcmd-mi-wrap">
                                <button type="button" role="menuitem" x-on:click="fileMenu = false; run(item)">
                                    <span x-text="item"></span>
                                </button>
                            </div>
                        </template>
                        <span class="flcmd-menusep" aria-hidden="true"></span>
                        <button type="button" role="menuitem" x-on:click="fileMenu = false; run({{ json_encode($say('Exit', 'خروج')) }})">
                            {{ $say('Exit', 'خروج') }}
                        </button>
                    </div>
                </div>
                <button type="button" class="flcmd-menubtn" x-on:click="run({{ json_encode($say('Edit', 'ویرایش')) }})">{{ $say('Edit', 'ویرایش') }}</button>
                <button type="button" class="flcmd-menubtn" x-on:click="run({{ json_encode($say('View', 'نمایش')) }})">{{ $say('View', 'نمایش') }}</button>
            </div>

            <div class="flcmd-files">
                @foreach ($files as $i => $f)
                    <div class="flcmd-file" {{ $i === 1 ? 'aria-current="true"' : '' }}>
                        <span aria-hidden="true">{{ $f['icon'] }}</span>
                        <span>{{ $f['name'] }}</span>
                        <small>{{ $f['meta'] }}</small>
                    </div>
                @endforeach
            </div>
            <p class="flcmd-status" aria-live="polite" x-text="msg">{{ $say('Ready', 'آماده') }}</p>
        </div>
        <p class="flcmd-hint">
            {{ $say('The overflow is measured from the real stage width, so it keeps working when the page itself is narrow.', 'سرریز از پهنای واقعی صحنه اندازه‌گیری می‌شود؛ پس وقتی خود صفحه باریک است هم درست کار می‌کند.') }}
        </p>
    </div>
</section>
