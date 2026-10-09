{{--
    Carbon's tearsheet inside a desktop app frame: it tears up from the bottom
    edge over a 50% scrim, carries the influencer side-rail with a three-step
    progress indicator, swaps the body per step and turns the primary action
    into "Finish" at the end. Escape, the scrim and Cancel all close it. The
    variants row shows wide, narrow and rail-less silhouettes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .cbts-root {
        --cbts-accent: #0f62fe; --cbts-accent-hover: #0353e9;
        --cbts-text: #161616; --cbts-text-secondary: #525252;
        --cbts-border: #e0e0e0; --cbts-border-strong: #8d8d8d;
        --cbts-layer: #f4f4f4; --cbts-layer-2: #e8e8e8; --cbts-page: #ffffff;
        --cbts-danger: #da1e28;
        --cbts-font: 'IBM Plex Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-family: var(--cbts-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbts-root {
        --cbts-accent: #4589ff; --cbts-accent-hover: #78a9ff;
        --cbts-text: #f4f4f4; --cbts-text-secondary: #c6c6c6;
        --cbts-border: #393939; --cbts-border-strong: #8d8d8d;
        --cbts-layer: #262626; --cbts-layer-2: #333333; --cbts-page: #161616;
        --cbts-danger: #fa4d56;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbts-root {
            --cbts-accent: #4589ff; --cbts-accent-hover: #78a9ff;
            --cbts-text: #f4f4f4; --cbts-text-secondary: #c6c6c6;
            --cbts-border: #393939; --cbts-border-strong: #8d8d8d;
            --cbts-layer: #262626; --cbts-layer-2: #333333; --cbts-page: #161616;
            --cbts-danger: #fa4d56;
        }
    }
    .cbts-open { block-size: 3rem; padding-inline: 1rem; border: none; background: var(--cbts-accent); color: #fff;
                 font: 400 .875rem/1 var(--cbts-font); cursor: pointer; transition: background-color .11s ease-in; }
    .cbts-open:hover { background: var(--cbts-accent-hover); }
    .cbts-open:focus-visible { outline: 2px solid var(--cbts-accent); outline-offset: 2px; }
    .cbts-desktop { position: relative; overflow: clip; inline-size: min(100%, 56rem); aspect-ratio: 16 / 9.5; min-block-size: 24rem;
                    border: 1px solid var(--cbts-border-strong); background: var(--cbts-page); }
    .cbts-app { position: absolute; inset: 0; display: grid; grid-template-rows: 2.75rem 1fr; font-size: .75rem; color: var(--cbts-text); }
    .cbts-app-bar { display: flex; align-items: center; gap: .75rem; padding-inline: 1rem; border-block-end: 1px solid var(--cbts-border); background: var(--cbts-layer); }
    .cbts-app-bar b { font-size: .8125rem; font-weight: 600; }
    .cbts-app-body { display: grid; gap: 1rem; align-content: start; padding: 1.5rem 2rem; }
    .cbts-app-body h3 { margin: 0; font: 600 1.5rem/1.2 var(--cbts-font); letter-spacing: 0; }
    .cbts-app-body p { margin: 0; max-width: 60ch; color: var(--cbts-text-secondary); }
    .cbts-app-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-block-start: .5rem; }
    .cbts-app-card { padding: 1rem; background: var(--cbts-layer); border-block-start: 2px solid var(--cbts-accent); }
    .cbts-app-card b { display: block; font: 600 1.25rem/1.3 var(--cbts-font); }
    .cbts-app-card small { color: var(--cbts-text-secondary); }
    .cbts-scrim { position: absolute; inset: 0; z-index: 8; background: rgba(22, 22, 22, .5); cursor: pointer; }
    .cbts-sheet { position: absolute; z-index: 9; inset-inline: 2rem; inset-block-end: 0; block-size: min(calc(100% - 2rem), 26rem);
                  display: grid; grid-template-columns: 13.5rem 1fr; grid-template-rows: 1fr auto;
                  background: var(--cbts-layer); box-shadow: 0 -2px 14px rgba(0, 0, 0, .35);
                  transition: translate 240ms cubic-bezier(.2, 0, 0, 1); }
    .cbts[data-open='false'] .cbts-sheet, .cbts[data-open='false'] .cbts-scrim { display: none; }
    .cbts[data-open='true'] .cbts-sheet { animation: cbts-tear 240ms cubic-bezier(.2, 0, 0, 1); }
    @keyframes cbts-tear { from { translate: 0 100%; } }
    .cbts-rail { grid-row: 1 / 3; padding: 1.5rem 1.25rem; border-inline-end: 1px solid var(--cbts-border); overflow-y: auto; }
    .cbts-rail-label { display: block; padding-block-end: .75rem; font-size: .75rem; letter-spacing: .32px; color: var(--cbts-text-secondary); }
    .cbts-steps { display: grid; gap: 0; margin: 0; padding: 0; list-style: none; counter-reset: cbts-step; }
    .cbts-step { position: relative; display: grid; grid-template-columns: 2rem 1fr; gap: .5rem .625rem; padding-block-end: 1.75rem;
                 border: none; background: transparent; cursor: pointer; text-align: start; font: 400 .8125rem/1.35 var(--cbts-font);
                 color: var(--cbts-text-secondary); }
    .cbts-step::before { counter-increment: cbts-step; content: counter(cbts-step); display: grid; place-items: center;
                         grid-row: 1; inline-size: 2rem; block-size: 2rem; border: 1px solid var(--cbts-border-strong);
                         border-radius: 50%; font-weight: 600; color: var(--cbts-text); background: var(--cbts-layer); }
    .cbts-step::after { content: ''; position: absolute; inset-block-start: 2rem; inset-inline-start: 1rem; block-size: calc(100% - 2rem);
                        inline-size: 1px; background: var(--cbts-border-strong); }
    .cbts-step:last-child { padding-block-end: 0; }
    .cbts-step:last-child::after { display: none; }
    .cbts-step span { grid-column: 2; padding-block-start: .3rem; }
    .cbts-step[data-state='current']::before { border-color: var(--cbts-accent); box-shadow: inset 0 0 0 1px var(--cbts-accent); }
    .cbts-step[data-state='current'] span { color: var(--cbts-text); font-weight: 600; }
    .cbts-step[data-state='complete']::before { background: var(--cbts-accent); border-color: var(--cbts-accent); color: #fff;
                                                content: '✓'; font-size: .875rem; }
    .cbts-step:focus-visible { outline: 2px solid var(--cbts-accent); outline-offset: 2px; }
    .cbts-main { grid-column: 2; display: flex; flex-direction: column; overflow: hidden; }
    .cbts-title { padding: 1.5rem 2rem 0; }
    .cbts-title small { display: block; font-size: .75rem; letter-spacing: .32px; color: var(--cbts-text-secondary); }
    .cbts-title h2 { margin: .25rem 0 .25rem; font: 400 1.375rem/1.25 var(--cbts-font); color: var(--cbts-text); }
    .cbts-title p { margin: 0; max-width: 52ch; font-size: .8125rem; color: var(--cbts-text-secondary); }
    .cbts-form { flex: 1; overflow-y: auto; padding: 1.25rem 2rem 1.5rem; }
    .cbts-field { display: grid; gap: .25rem; max-inline-size: 22rem; padding-block-end: 1rem; }
    .cbts-field label { font-size: .75rem; letter-spacing: .32px; color: var(--cbts-text-secondary); }
    .cbts-field input, .cbts-field select { block-size: 2.5rem; padding-inline: .75rem; border: none; border-block-end: 1px solid var(--cbts-border-strong);
                                            background: transparent; color: var(--cbts-text); font: 400 .875rem/1 var(--cbts-font); }
    .cbts-field input:focus-visible, .cbts-field select:focus-visible { outline: none; border-block-end: 2px solid var(--cbts-accent); }
    .cbts-review { display: grid; gap: 0; max-inline-size: 22rem; margin: 0; font-size: .8125rem; }
    .cbts-review div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .5rem; border-block-end: 1px solid var(--cbts-border); }
    .cbts-review dt { color: var(--cbts-text-secondary); }
    .cbts-review dd { margin: 0; font-weight: 600; color: var(--cbts-text); }
    .cbts-footer { grid-column: 2; display: flex; align-items: center; gap: .75rem; padding: .875rem 2rem; border-block-start: 1px solid var(--cbts-border); }
    .cbts-btn { block-size: 2.5rem; padding-inline: 1rem; border: none; font: 400 .875rem/1 var(--cbts-font); cursor: pointer;
                transition: background-color .11s ease-in; }
    .cbts-btn[data-primary] { background: var(--cbts-accent); color: #fff; }
    .cbts-btn[data-primary]:hover { background: var(--cbts-accent-hover); }
    .cbts-btn[data-secondary] { background: transparent; color: var(--cbts-text); border-inline-start: none;
                                box-shadow: inset -1px 0 0 var(--cbts-border-strong), inset 1px 0 0 var(--cbts-border-strong), inset 0 1px 0 var(--cbts-border-strong), inset 0 -1px 0 var(--cbts-border-strong); }
    .cbts-btn[data-secondary]:hover { background: var(--cbts-layer-2); }
    .cbts-btn[data-ghost] { background: transparent; color: var(--cbts-accent); margin-inline-start: auto; }
    .cbts-btn[data-ghost]:hover { background: color-mix(in srgb, var(--cbts-accent) 8%, transparent); }
    .cbts-btn:focus-visible { outline: 2px solid var(--cbts-accent); outline-offset: 1px; }
    .cbts-note { margin: 0; font-size: .8125rem; color: var(--nx-text-muted); max-width: 60ch; text-align: center; }
    .cbts-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .cbts-spec-row { display: flex; flex-wrap: wrap; gap: 1.5rem; justify-content: center; align-items: flex-start; }
    .cbts-spec-cell { display: grid; gap: .5rem; }
    .cbts-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .cbts-mini { position: relative; inline-size: 15rem; block-size: 7rem; background: var(--cbts-layer-2); border: 1px solid var(--cbts-border); }
    .cbts-mini i { position: absolute; inset-inline: 15%; inset-block-end: 0; block-size: 78%; background: var(--cbts-layer);
                   border: 1px solid var(--cbts-border-strong); border-block-end: none; display: block; }
    .cbts-mini[data-rail] i { inset-inline-start: 8%; inset-inline-end: 15%; }
    .cbts-mini[data-rail] i::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: 0; inline-size: 32%; background: var(--cbts-layer-2); border-inline-end: 1px solid var(--cbts-border); }
    .cbts-mini[data-narrow] i { inset-inline: 30%; }
    :where(.nx-js) .pg:has(.cbts-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 768px) {
        .cbts-sheet { inset-inline: .5rem; grid-template-columns: 1fr; }
        .cbts-rail { display: none; }
        .cbts-title, .cbts-form, .cbts-footer { padding-inline: 1.25rem; }
        .cbts-footer { flex-wrap: wrap; }
        .cbts-footer .cbts-btn[data-ghost] { margin-inline-start: auto; }
        .cbts-app-cards { grid-template-columns: 1fr; }
    }
    @media (max-width: 480px) {
        .pg:has(.cbts-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbts-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbts-root"
    x-data="{
        open: false, step: 1, done: false, region: 'fra',
        name: 'nabu-media', size: '500',
        steps: [
            { t: '{{ $say('Storage details', 'جزئیات فضای ذخیره') }}' },
            { t: '{{ $say('Choose a region', 'انتخاب ناحیه') }}' },
            { t: '{{ $say('Review and attach', 'بازبینی و پیوست') }}' },
        ],
        openSheet() { this.open = true; this.step = 1; this.done = false; $nextTick(() => this.$refs.sheet.focus()) },
        close() { this.open = false },
        go(n) { this.step = Math.min(3, Math.max(1, n)) },
        stateOf(n) { return n < this.step ? 'complete' : (n === this.step ? 'current' : 'incomplete') },
        next() { if (this.step === 3) { this.done = true; this.close() } else this.go(this.step + 1) },
        zone() { return { fra: 'eu-de-2', dub: 'eu-ie-1', sin: 'ap-se-1' }[this.region] },
    }"
    x-on:keydown.escape.window="close()">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A whole task, one surface', 'یک کارِ کامل، یک سطح') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The tearsheet tears up from the bottom edge of the window over a half scrim, with the influencer rail walking the three steps. The primary action turns into «Finish» on the last step; Escape, the scrim and Cancel all fold it back down.', 'تیرشیت از لبهٔ پایین پنجره روی پردهٔ نیمه‌شفاف بالا می‌آید و ریلِ کناری سه مرحله را قدم‌به‌قدم می‌برد. اکشن اصلی در مرحلهٔ آخر «پایان» می‌شود؛ Escape، پرده و انصراف همه آن را پایین برمی‌گردانند.') }}
            </p>
        </div>

        <button type="button" class="cbts-open" x-on:click="openSheet()">{{ $say('Add storage…', 'افزودن فضای ذخیره…') }}</button>

        <div class="cbts-desktop">
            <div class="cbts-app" aria-hidden="true">
                <div class="cbts-app-bar"><b>{{ $say('nabu console', 'کنسول نابو') }}</b><span style="color: var(--cbts-text-secondary)">{{ $say('storage / volumes', 'فضای ذخیره / دیسک‌ها') }}</span></div>
                <div class="cbts-app-body">
                    <h3>{{ $say('Storage', 'فضای ذخیره') }}</h3>
                    <p>{{ $say('Three volumes are attached to the app service. Peak write throughput this week: 240 MB/s.', 'سه دیسک به سرویس برنامه متصل است. بیشینهٔ توان نوشتن این هفته: ۲۴۰ مگابایت بر ثانیه.') }}</p>
                    <div class="cbts-app-cards">
                        <div class="cbts-app-card"><b>۲۰۰GB</b><small>{{ $say('media volume', 'دیسک رسانه') }}</small></div>
                        <div class="cbts-app-card"><b>۸۰GB</b><small>{{ $say('database volume', 'دیسک پایگاه‌داده') }}</small></div>
                        <div class="cbts-app-card"><b>۲۰GB</b><small>{{ $say('backup volume', 'دیسک پشتیبان') }}</small></div>
                    </div>
                </div>
            </div>

            <div class="cbts-scrim" x-show="open" x-cloak x-on:click="close()" aria-hidden="true"></div>
            <section class="cbts" x-bind:data-open="open.toString()">
                <div class="cbts-sheet" role="dialog" aria-modal="true" aria-label="{{ $say('Add storage', 'افزودن فضای ذخیره') }}" tabindex="-1" x-ref="sheet">
                    <aside class="cbts-rail">
                        <small class="cbts-rail-label">{{ $say('Storage — 3 steps', 'فضای ذخیره — ۳ مرحله') }}</small>
                        <ol class="cbts-steps">
                            <template x-for="(s, i) in steps" :key="i">
                                <li style="display: contents">
                                    <button type="button" class="cbts-step" x-bind:data-state="stateOf(i + 1)" x-on:click="go(i + 1)">
                                        <span x-text="s.t"></span>
                                    </button>
                                </li>
                            </template>
                        </ol>
                    </aside>

                    <div class="cbts-main">
                        <header class="cbts-title">
                            <small>{{ $say('nabu console', 'کنسول نابو') }}</small>
                            <h2>{{ $say('Add storage', 'افزودن فضای ذخیره') }}</h2>
                            <p>{{ $say('A new volume attaches to the app service without a restart; billing starts when data lands.', 'دیسک تازه بی‌ری‌استارت به سرویس برنامه متصل می‌شود؛ صورتحساب از لحظهٔ نشستن داده آغاز می‌شود.') }}</p>
                        </header>

                        <div class="cbts-form" x-show="step === 1">
                            <div class="cbts-field">
                                <label for="cbts-name">{{ $say('Volume name', 'نام دیسک') }}</label>
                                <input id="cbts-name" type="text" x-model="name" autocomplete="off">
                            </div>
                            <div class="cbts-field">
                                <label for="cbts-size">{{ $say('Size — GB', 'اندازه — گیگابایت') }}</label>
                                <input id="cbts-size" type="number" min="20" max="2000" step="20" x-model="size">
                            </div>
                        </div>

                        <div class="cbts-form" x-show="step === 2" x-cloak>
                            <div class="cbts-field" style="gap: .5rem">
                                <label>{{ $say('Region', 'ناحیه') }}</label>
                                <label style="display: flex; align-items: center; gap: .5rem; font-size: .875rem; color: var(--cbts-text)">
                                    <input type="radio" value="fra" x-model="region" style="accent-color: var(--cbts-accent)"> {{ $say('Frankfurt — same as the app', 'فرانکفورت — هم‌ناحیهٔ برنامه') }}
                                </label>
                                <label style="display: flex; align-items: center; gap: .5rem; font-size: .875rem; color: var(--cbts-text)">
                                    <input type="radio" value="dub" x-model="region" style="accent-color: var(--cbts-accent)"> {{ $say('Dublin — cheaper, +۷ms', 'دوبلین — ارزان‌تر، ۷+ms') }}
                                </label>
                                <label style="display: flex; align-items: center; gap: .5rem; font-size: .875rem; color: var(--cbts-text)">
                                    <input type="radio" value="sin" x-model="region" style="accent-color: var(--cbts-accent)"> {{ $say('Singapore — for the APAC mirror', 'سنگاپور — برای آینهٔ آسیا') }}
                                </label>
                            </div>
                        </div>

                        <div class="cbts-form" x-show="step === 3" x-cloak>
                            <dl class="cbts-review">
                                <div><dt>{{ $say('Volume name', 'نام دیسک') }}</dt><dd x-text="name"></dd></div>
                                <div><dt>{{ $say('Size', 'اندازه') }}</dt><dd><span x-text="Number(size || 0).toLocaleString('fa-IR')"></span> GB</dd></div>
                                <div><dt>{{ $say('Region', 'ناحیه') }}</dt><dd x-text="zone()"></dd></div>
                                <div><dt>{{ $say('Monthly cost', 'بهای ماهانه') }}</dt><dd><span x-text="Math.round((size || 0) * 420).toLocaleString('fa-IR')"></span> {{ $say('toman', 'تومان') }}</dd></div>
                            </dl>
                        </div>
                    </div>

                    <footer class="cbts-footer">
                        <button type="button" class="cbts-btn" data-primary x-on:click="next()" x-text="step === 3 ? '{{ $say('Finish', 'پایان') }}' : '{{ $say('Next', 'بعدی') }}'">بعدی</button>
                        <button type="button" class="cbts-btn" data-secondary x-show="step > 1" x-cloak x-on:click="go(step - 1)">{{ $say('Previous', 'پیشین') }}</button>
                        <button type="button" class="cbts-btn" data-ghost x-on:click="close()">{{ $say('Cancel', 'انصراف') }}</button>
                    </footer>
                </div>
            </section>
        </div>
        <p class="cbts-note" x-show="done" x-cloak role="status">
            {{ $say('Volume attached — the tearsheet folded back down and the console is exactly where you left it.', 'دیسک متصل شد — تیرشیت پایین رفت و کنسول دقیقاً همان‌جایی است که رهایش کردید.') }}
        </p>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbts-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Wide with a rail, narrow for a single decision, and rail-less for short flows — all three tear from the same bottom edge.', 'عریض با ریل، باریک برای یک تصمیم، و بی‌ریل برای جریان‌های کوتاه — هر سه از همان لبهٔ پایین پاره می‌شوند.') }}
            </p>
        </div>
        <div class="cbts-spec">
        <div class="cbts-spec-row">
            <div class="cbts-spec-cell"><span class="cbts-mini" data-rail aria-hidden="true"><i></i></span><small>{{ $say('wide · influencer', 'عریض · ریل کناری') }}</small></div>
            <div class="cbts-spec-cell"><span class="cbts-mini" data-narrow aria-hidden="true"><i></i></span><small>{{ $say('narrow', 'باریک') }}</small></div>
            <div class="cbts-spec-cell"><span class="cbts-mini" aria-hidden="true"><i></i></span><small>{{ $say('no rail', 'بی‌ریل') }}</small></div>
        </div>
        </div>
    </div>
</section>
