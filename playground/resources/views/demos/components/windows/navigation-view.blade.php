{{--
    A Settings-like NavigationView: the hamburger collapses the pane between
    256px labels and a 48px icon rail (with title tooltips while collapsed),
    five items plus a footer Settings pinned at the bottom, and the 3×16
    selection pill glued to the start edge of the active item. The content
    heading follows the selection.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $navItems = [
        ['id' => 'home', 'icon' => '🏠', 'label' => $say('Home', 'خانه')],
        ['id' => 'accounts', 'icon' => '👤', 'label' => $say('Accounts', 'حساب‌ها')],
        ['id' => 'personal', 'icon' => '🎨', 'label' => $say('Personalisation', 'شخصی‌سازی')],
        ['id' => 'apps', 'icon' => '🧩', 'label' => $say('Apps', 'برنامه‌ها')],
        ['id' => 'network', 'icon' => '📶', 'label' => $say('Network', 'شبکه')],
    ];
    $specs = [
        [$say('Device name', 'نام دستگاه'), 'Nabu-Book Pro'],
        [$say('Processor', 'پردازنده'), 'Nava 9'],
        [$say('Memory', 'حافظه'), $say('16 GB', '۱۶ گیگابایت')],
        [$say('Edition', 'ویرایش'), $say('Windows 11 Pro, 24H2', 'ویندوز ۱۱ پرو، ۲۴H۲')],
    ];
@endphp
<style>
    .flnav-root {
        --fl-accent: #005FB8;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-divider: rgba(0, 0, 0, .08);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-focus: #1B1B1B;
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        gap: .75rem;
        /* As a grid item with min-width:auto this root once refused to shrink
           below its content's intrinsic width (429px at 375px viewport) and
           the whole window overflowed the demo card. Zero the automatic
           minimum so the minmax(0, 1fr) track can always clamp it. */
        min-inline-size: 0;
    }
    html[data-theme="dark"] .flnav-root {
        --fl-accent: #4CC2FF;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-layer: rgba(255, 255, 255, .05);
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-divider: rgba(255, 255, 255, .06);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-focus: #FFFFFF;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flnav-root {
            --fl-accent: #4CC2FF;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-layer: rgba(255, 255, 255, .05);
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-divider: rgba(255, 255, 255, .06);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-focus: #FFFFFF;
        }
    }
    .flnav-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .flnav-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }

    .flnav-app {
        inline-size: min(100%, 30rem); margin-inline: auto; block-size: 23rem;
        min-inline-size: 0;
        display: flex; flex-direction: column; border-radius: 8px; overflow: clip;
        background: var(--fl-window);
        box-shadow: 0 24px 48px rgba(0, 0, 0, .16), 0 0 0 1px var(--fl-card-stroke);
    }
    .flnav-titlebar { display: flex; align-items: center; gap: .4rem; block-size: 2.5rem; padding-inline: .5rem; border-block-end: 1px solid var(--fl-divider); }
    .flnav-burger { inline-size: 2.5rem; block-size: 2.5rem; display: grid; place-items: center; border-radius: 4px; transition: background .12s; }
    .flnav-burger:hover { background: var(--fl-hover); }
    .flnav-apptitle { font-size: .8rem; font-weight: 600; }

    .flnav-shell { flex: 1; display: flex; min-block-size: 0; }
    .flnav-pane {
        flex: none; inline-size: 16rem; padding: .25rem .5rem 1rem;
        display: flex; flex-direction: column; gap: 2px; overflow: clip;
        transition: inline-size .25s cubic-bezier(.2, .9, .25, 1), padding .25s;
    }
    .flnav-app[data-collapsed] .flnav-pane { inline-size: 3rem; padding-inline: .25rem; }
    .flnav-list { display: grid; gap: 2px; align-content: start; }
    .flnav-footer { margin-block-start: auto; display: grid; gap: 2px; }

    .flnav-item {
        position: relative; display: flex; align-items: center; gap: .875rem;
        inline-size: 100%; block-size: 2.25rem; padding-inline: .75rem; border-radius: 4px;
        font-size: .8rem; text-align: start; transition: background .12s;
    }
    .flnav-item:hover { background: var(--fl-hover); }
    .flnav-item:active { background: var(--fl-hover); color: var(--fl-text-2); }
    .flnav-item[aria-current="page"] { background: var(--fl-hover); font-weight: 600; }
    .flnav-pill {
        position: absolute; inset-inline-start: 0; inset-block-start: 50%; translate: 0 -50%;
        inline-size: 3px; block-size: 16px; border-radius: 999px; background: transparent; transition: background .15s;
    }
    .flnav-item[aria-current="page"] .flnav-pill { background: var(--fl-accent); }
    .flnav-ico { flex: none; inline-size: 1.2rem; text-align: center; font-size: .95rem; }

    .flnav-content {
        flex: 1; min-inline-size: 0; margin-block: .75rem; margin-inline-end: .75rem;
        padding: 1rem 1.1rem; border-radius: 8px; overflow: auto;
        background: var(--fl-layer); box-shadow: inset 0 0 0 1px var(--fl-card-stroke);
    }
    .flnav-content h4 { margin: 0; font-size: 1rem; }
    .flnav-content > p { margin: .3rem 0 0; font-size: .72rem; color: var(--fl-text-2); line-height: 1.8; }
    .flnav-specs { display: grid; gap: .45rem; margin-block-start: .9rem; }
    .flnav-specs div { display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; font-size: .75rem; padding-block: .3rem; border-block-end: 1px solid var(--fl-divider); }
    .flnav-specs div:last-child { border-block-end: 0; }
    .flnav-specs span { color: var(--fl-text-2); }
    .flnav-hint { margin: 0; text-align: center; font-size: .72rem; color: var(--fl-text-2); }

    /* On phones the demo card is ~243px wide inside its padding, so the
       expanded 256px pane (272px with its padding) can never fit beside the
       details panel. Real NavigationView answers the same squeeze by dropping
       to its compact rail, so below 29rem — the width under which the card
       interior drops under ~331px — mirror the data-collapsed styling. */
    @media (max-width: 29rem) {
        .flnav-app .flnav-pane { inline-size: 3rem; padding-inline: .25rem; }
        .flnav-app .flnav-lbl { display: none; }
    }

    /* The "Important props" table that the demo page renders after this
       partial hides its rows until a scroll observer marks it revealed
       (data-nx-revealed); a full-page capture never scrolls, so the body was
       captured empty in both themes, and its nowrap cells pushed the fourth
       column past the viewport edge on phones. This rule ships only with this
       partial, so it is page-scoped: show the rows unconditionally and let the
       cells wrap on narrow screens. */
    .nx-js .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 48rem) {
        .nx-data-table thead th, .nx-data-table tbody td { white-space: normal; padding-inline: .5rem; }
    }

    @media (prefers-reduced-motion: reduce) {
        .flnav-pane, .flnav-pill, .flnav-item, .flnav-burger { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The backbone of a Win11 app', 'ستون فقرات یک اپ وین ۱۱') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The hamburger folds the pane down to a 48px icon rail — hover a collapsed icon for its name. The 3×16 pill hugs the start edge of the selection, and Settings stays pinned at the foot.', 'همبرگر پنل را تا ریل ۴۸ پیکسلی آیکن‌ها جمع می‌کند — روی آیکن جمع‌شده بمانید تا نامش را ببینید. قرص ۳×۱۶ به لبهٔ ابتدای آیتم انتخاب‌شده می‌چسبد و «تنظیمات» پایین پنل می‌ماند.') }}
        </p>
    </div>

    <div class="flnav-root" x-data="{
            open: true, current: 'home',
            items: {{ json_encode($navItems, JSON_UNESCAPED_UNICODE) }},
            all: [],
            init() { this.all = this.items.concat([{ id: 'settings', label: {{ json_encode($say('Settings', 'تنظیمات')) }} }]) },
            get currentLabel() { const hit = this.all.find(i => i.id === this.current); return hit ? hit.label : this.items[0].label },
        }">
        <div class="flnav-app" :data-collapsed="open ? null : 'true'">
            <div class="flnav-titlebar">
                <button type="button" class="flnav-burger" :aria-expanded="open ? 'true' : 'false'" aria-controls="flnav-pane"
                    :aria-label="open ? {{ json_encode($say('Collapse pane', 'جمع کردن پنل')) }} : {{ json_encode($say('Expand pane', 'باز کردن پنل')) }}"
                    x-on:click="open = !open">
                    <svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M2 4.5h12M2 8h12M2 11.5h12" stroke="currentColor" stroke-width="1.2" fill="none"/></svg>
                </button>
                <span class="flnav-apptitle">{{ $say('Settings', 'تنظیمات') }}</span>
            </div>
            <div class="flnav-shell">
                <nav class="flnav-pane" id="flnav-pane" aria-label="{{ $say('Sections', 'بخش‌ها') }}">
                    <div class="flnav-list">
                        <template x-for="item in items" :key="item.id">
                            <button type="button" class="flnav-item"
                                :aria-current="current === item.id ? 'page' : null"
                                :title="open ? null : item.label"
                                x-on:click="current = item.id">
                                <span class="flnav-pill" aria-hidden="true"></span>
                                <span class="flnav-ico" aria-hidden="true" x-text="item.icon"></span>
                                <span class="flnav-lbl" x-text="item.label" x-show="open"></span>
                            </button>
                        </template>
                    </div>
                    <div class="flnav-footer">
                        <button type="button" class="flnav-item" :aria-current="current === 'settings' ? 'page' : null"
                            :title="open ? null : {{ json_encode($say('Settings', 'تنظیمات')) }}"
                            x-on:click="current = 'settings'">
                            <span class="flnav-pill" aria-hidden="true"></span>
                            <span class="flnav-ico" aria-hidden="true">⚙️</span>
                            <span class="flnav-lbl" x-show="open">{{ $say('Settings', 'تنظیمات') }}</span>
                        </button>
                    </div>
                </nav>
                <div class="flnav-content">
                    <h4 x-text="currentLabel">{{ $say('Home', 'خانه') }}</h4>
                    <p>{{ $say('Pick a section from the pane — this heading follows your selection.', 'بخشی را از پنل انتخاب کنید — این تیتر انتخاب شما را دنبال می‌کند.') }}</p>
                    <div class="flnav-specs">
                        @foreach ($specs as $spec)
                            <div><span>{{ $spec[0] }}</span><strong>{{ $spec[1] }}</strong></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <p class="flnav-hint">
            {{ $say('Expanded is 256px with labels; compact is a 48px rail with tooltips.', 'حالت باز ۲۵۶ پیکسل با لیبل است؛ حالت جمع ریل ۴۸ پیکسلی با tooltip.') }}
        </p>
    </div>
</section>
