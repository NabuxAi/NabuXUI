{{--
    The Mac's two modals on one mini window: the toolbar's «New scene» button
    slides a form sheet down from under the 52px toolbar (300ms spring-ish
    curve) while the parent content dims, and the «Close project» button
    raises the 260px centred alert with a filled blue default — Enter accepts,
    Escape cancels, focus lands on Cancel when it opens.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .mcsheet-root {
        --mcsheet-win: #ECECEC; --mcsheet-text: #1E1E1E; --mcsheet-text2: #6D6D72; --mcsheet-accent: #007AFF;
        --mcsheet-hair: rgba(0, 0, 0, .15); --mcsheet-div: rgba(0, 0, 0, .1);
        --mcsheet-ctrl: #FFFFFF;
        --mcsheet-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .2), 0 0 0 .5px rgba(0, 0, 0, .15);
        --mcsheet-red: #FF5F57; --mcsheet-yellow: #FEBC2E; --mcsheet-green: #28C840;
        --mcsheet-wall: linear-gradient(140deg, #2F4A75 0%, #7A5B92 55%, #D69877 100%);
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcsheet-root {
        --mcsheet-win: #282828; --mcsheet-text: #F5F5F5; --mcsheet-text2: #A5A5AA; --mcsheet-accent: #0A84FF;
        --mcsheet-hair: rgba(255, 255, 255, .15); --mcsheet-div: rgba(255, 255, 255, .1);
        --mcsheet-ctrl: #333336;
        --mcsheet-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .55), 0 0 0 .5px rgba(255, 255, 255, .14);
        --mcsheet-wall: linear-gradient(140deg, #1B2A44 0%, #3D2F4E 55%, #6B4530 100%);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcsheet-root {
            --mcsheet-win: #282828; --mcsheet-text: #F5F5F5; --mcsheet-text2: #A5A5AA; --mcsheet-accent: #0A84FF;
            --mcsheet-hair: rgba(255, 255, 255, .15); --mcsheet-div: rgba(255, 255, 255, .1);
            --mcsheet-ctrl: #333336;
            --mcsheet-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .55), 0 0 0 .5px rgba(255, 255, 255, .14);
            --mcsheet-wall: linear-gradient(140deg, #1B2A44 0%, #3D2F4E 55%, #6B4530 100%);
        }
    }

    .mcsheet-stage {
        position: relative; overflow: clip; display: grid; place-items: center;
        min-block-size: 27rem; padding: 2.25rem 1rem; border-radius: var(--nx-radius-2xl);
        background: var(--mcsheet-wall);
        container-type: inline-size;
    }
    .mcsheet-win {
        position: relative; z-index: 1; display: grid; grid-template-rows: 52px minmax(0, 1fr);
        inline-size: 100%; max-inline-size: 30rem; max-block-size: 23rem;
        border-radius: 10px; overflow: clip;
        color: var(--mcsheet-text); background: var(--mcsheet-win);
        box-shadow: 0 22px 70px 4px rgba(0, 0, 0, .3), 0 0 0 .5px rgba(0, 0, 0, .26);
    }
    /* Title sits in flow between the lights and the actions, centred in the space
       left over; it truncates with an ellipsis, so it can never reach a button.
       Lights and actions are flex:none, so buttons keep one line of text. */
    .mcsheet-bar {
        position: relative; z-index: 6; display: flex; align-items: center; gap: 12px;
        padding-inline: 12px; background: var(--mcsheet-win); border-block-end: 1px solid var(--mcsheet-hair);
    }
    .mcsheet-lights { display: flex; gap: 8px; flex: none; }
    .mcsheet-lights i { inline-size: 12px; aspect-ratio: 1; border-radius: 50%; box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .22); }
    .mcsheet-lights i:nth-child(1) { background: var(--mcsheet-red); }
    .mcsheet-lights i:nth-child(2) { background: var(--mcsheet-yellow); }
    .mcsheet-lights i:nth-child(3) { background: var(--mcsheet-green); }
    .mcsheet-title {
        flex: 1 1 auto; min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align: center;
        font: 600 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; pointer-events: none;
    }
    .mcsheet-act { flex: none; display: inline-flex; gap: 6px; }
    .mcsheet-btn {
        block-size: 24px; padding-inline: 10px; border: 0; border-radius: 5px; cursor: pointer; white-space: nowrap;
        font: 400 12px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
        color: var(--mcsheet-text); background: var(--mcsheet-ctrl); box-shadow: var(--mcsheet-ctrl-sh);
    }
    .mcsheet-btn:hover { background: color-mix(in srgb, var(--mcsheet-ctrl) 88%, var(--mcsheet-text) 12%); }
    /* Narrow window: tighter toolbar so the centred title keeps real room.
       The bar is a nowrap flex row, so below ~19.5rem of stage the decorative
       lights step aside (a real Mac window is never this small) — otherwise the
       two toolbar buttons overflow the window and get clipped at the demo box
       edge on phones. */
    @container (max-inline-size: 22rem) {
        .mcsheet-bar { gap: 8px; padding-inline: 8px; }
        .mcsheet-lights { gap: 6px; }
        .mcsheet-btn { padding-inline: 5px; font-size: 10.5px; }
        .mcsheet-panel { padding: 12px; }
        .mcsheet-go { padding-inline: 12px; }
        .mcsheet-row { gap: 8px; padding: 8px 9px; }
    }
    @container (max-inline-size: 19.5rem) {
        .mcsheet-lights { display: none; }
        .mcsheet-title { text-align: start; }
    }
    @container (max-inline-size: 14rem) {
        .mcsheet-title { display: none; }
    }

    .mcsheet-content { position: relative; display: grid; align-content: start; gap: 2px; padding: 10px; overflow: auto; transition: opacity .3s, filter .3s; }
    .mcsheet-content[data-dim] { opacity: .4; filter: blur(1px); pointer-events: none; }
    .mcsheet-row {
        display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 6px;
        background: var(--mcsheet-ctrl); box-shadow: var(--mcsheet-ctrl-sh);
        font: 400 13px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    .mcsheet-row:nth-child(odd) { background: color-mix(in srgb, var(--mcsheet-ctrl) 55%, var(--mcsheet-win)); box-shadow: none; }
    .mcsheet-row small { margin-inline-start: auto; color: var(--mcsheet-text2); font-size: 11px; white-space: nowrap; }
    .mcsheet-row .mcsheet-fresh { inline-size: 6px; aspect-ratio: 1; border-radius: 50%; background: var(--mcsheet-accent); flex: none; }

    .mcsheet-panel {
        position: absolute; z-index: 5; inset-inline: 0; inset-block-start: 52px;
        padding: 18px; background: var(--mcsheet-win);
        border-block-end: 1px solid var(--mcsheet-hair);
        box-shadow: 0 14px 38px rgba(0, 0, 0, .3);
        border-end-end-radius: 10px; border-end-start-radius: 10px;
        translate: 0 -104%; transition: translate .3s cubic-bezier(.2, .9, .3, 1);
        display: grid; gap: 12px;
    }
    .mcsheet-panel[data-open] { translate: 0 0; }
    .mcsheet-panel label { font: 600 13px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcsheet-panel input {
        inline-size: 100%; block-size: 30px; padding-inline: 9px; border: 0; border-radius: 6px;
        background: var(--mcsheet-ctrl); box-shadow: inset 0 0 0 .5px var(--mcsheet-hair);
        color: var(--mcsheet-text); font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    .mcsheet-foot { display: flex; justify-content: flex-end; gap: 8px; }
    .mcsheet-go {
        block-size: 28px; padding-inline: 16px; border: 0; border-radius: 6px; cursor: pointer;
        font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
        color: var(--mcsheet-text); background: var(--mcsheet-ctrl); box-shadow: var(--mcsheet-ctrl-sh);
    }
    .mcsheet-go[data-default] { color: #fff; background: var(--mcsheet-accent); }

    .mcsheet-alert {
        position: absolute; inset: 0; z-index: 20; display: grid; place-items: center;
        background: rgba(0, 0, 0, .18); padding: 16px;
    }
    .mcsheet-card {
        inline-size: min(260px, 100%); padding: 18px 18px 14px; border-radius: 12px; text-align: center;
        background: color-mix(in srgb, var(--mcsheet-win) 92%, transparent);
        backdrop-filter: blur(30px) saturate(1.5);
        box-shadow: 0 18px 60px rgba(0, 0, 0, .35), 0 0 0 .5px var(--mcsheet-hair);
        display: grid; gap: 4px;
    }
    .mcsheet-card b { font: 700 13px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcsheet-card p { margin: 0; color: var(--mcsheet-text2); font: 400 11px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcsheet-cardbtns { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-block-start: 12px; }
    .mcsheet-cardbtns .mcsheet-go { inline-size: 100%; }
    .mcsheet-note { margin: 10px 0 0; min-block-size: 1.1rem; text-align: center; font: 500 12px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcsheet-accent); }
    .mcsheet-root :is(button, input):focus-visible { outline: 2px solid var(--mcsheet-accent); outline-offset: 2px; }

    /* The demo page around this partial: the props table hides its rows until a
       scroll-reveal observer fires (and .nx-live disables the 2.5s CSS failsafe),
       so a static capture shows a header with an empty body, and the platform's
       nowrap cells cut at the edge on phones. Letting cells wrap at spaces only
       (plain normal — never anywhere, whose 1ch min-content crushed "Prop" into
       "Pr op") keeps every column at its longest word: sheet, position and
       'under toolbar' stay whole. The snippet also wraps at spaces, slightly
       smaller, so its lines never cut at the edge. Scoped here: off this demo's
       page it never loads. */
    body:has(.mcsheet-root) .nx-data-table tbody tr { opacity: 1; translate: none; animation: none; }
    body:has(.mcsheet-root) .nx-data-table th,
    body:has(.mcsheet-root) .nx-data-table td { white-space: normal; }
    body:has(.mcsheet-root) pre code { white-space: pre-wrap; overflow-wrap: normal; font-size: .9em; }

    @media (prefers-reduced-motion: reduce) { .mcsheet-panel { transition: none; } .mcsheet-content { transition: none; } }
</style>

<section class="pg-box mcsheet-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Sheet & alert', 'شیت و آلرت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('«New scene» slides the form down from under the toolbar while the list dims behind it; «Close project» raises the alert — Enter answers the filled default, Escape cancels.', '«صحنهٔ تازه» فرم را از زیر toolbar پایین می‌آورد و فهرست پشتش کم‌رنگ می‌شود؛ «بستن پروژه» آلرت را بالا می‌آورد — Enter دکمهٔ پرِ پیش‌فرض را می‌زند و Escape رد می‌کند.') }}
        </p>
    </div>

    <div class="mcsheet-stage"
         x-data="{
                sheet: true, alert: false, name: '', note: '',
                scenes: [
                    { t: { fa: 'نمای عمومی', en: 'Overview' }, when: { fa: 'دیروز', en: 'Yesterday' } },
                    { t: { fa: 'تنظیم روشنایی', en: 'Lighting setup' }, when: { fa: '۲ مهر', en: '23 Sep' } },
                    { t: { fa: 'کارت‌های عنوان', en: 'Title cards' }, when: { fa: '۲۸ شهریور', en: '18 Sep' } },
                    { t: { fa: 'درجه‌بندی رنگ', en: 'Colour grade' }, when: { fa: '۲۱ شهریور', en: '11 Sep' } },
                    { t: { fa: 'میکس صدا', en: 'Sound mix' }, when: { fa: '۱۴ شهریور', en: '4 Sep' } },
                ],
                fa: {{ $fa ? 'true' : 'false' }},
                t(s) { return this.fa ? s.t.fa : s.t.en },
                w(s) { return this.fa ? s.when.fa : s.when.en },
                openSheet() { this.sheet = true; this.$nextTick(() => this.$refs.field && this.$refs.field.focus()) },
                closeSheet() { this.sheet = false; this.name = '' },
                saveSheet() {
                    const v = this.name.trim();
                    if (v) { this.scenes.unshift({ t: { fa: v, en: v }, when: { fa: 'اکنون', en: 'Just now' } }); this.note = this.fa ? '«' + v + '» ذخیره شد' : 'Saved “' + v + '”' }
                    this.closeSheet();
                },
                openAlert() { this.alert = true; this.$nextTick(() => this.$refs.cancel && this.$refs.cancel.focus()) },
                alertSave() { this.alert = false; this.note = this.fa ? 'خروجی با موفقیت ذخیره شد' : 'Project saved on exit' },
                alertCancel() { this.alert = false; this.note = this.fa ? 'خروج لغو شد' : 'Exit cancelled' },
                esc() { if (this.alert) this.alertCancel(); else if (this.sheet) this.closeSheet() },
                enter(e) {
                    if (this.alert) { e.preventDefault(); this.alertSave(); return }
                    if (this.sheet && e.target.closest('input')) { e.preventDefault(); this.saveSheet() }
                },
            }"
         x-on:keydown.escape.window="esc()"
         x-on:keydown.enter.window="enter($event)">
        <div class="mcsheet-win">
            <header class="mcsheet-bar">
                <span class="mcsheet-lights" aria-hidden="true"><i></i><i></i><i></i></span>
                <span class="mcsheet-title">{{ $say('Scene document', 'مستند صحنه') }}</span>
                <span class="mcsheet-act">
                    <button type="button" class="mcsheet-btn" :aria-expanded="sheet ? 'true' : 'false'" x-on:click="sheet ? closeSheet() : openSheet()">{{ $say('New scene', 'صحنهٔ تازه') }}</button>
                    <button type="button" class="mcsheet-btn" :aria-expanded="alert ? 'true' : 'false'" x-on:click="openAlert()">{{ $say('Close project', 'بستن پروژه') }}</button>
                </span>
            </header>

            <div class="mcsheet-content" data-dim :data-dim="sheet ? '' : null">
                <template x-for="(s, i) in scenes" :key="i">
                    <div class="mcsheet-row">
                        <i class="mcsheet-fresh" x-show="i === 0" aria-hidden="true"></i>
                        <b style="font-weight: 500" x-text="t(s)"></b>
                        <small x-text="w(s)"></small>
                    </div>
                </template>
            </div>

            <form class="mcsheet-panel" role="dialog" aria-modal="true" aria-label="{{ $say('New scene', 'صحنهٔ تازه') }}"
                  data-open :data-open="sheet ? '' : null" x-on:submit.prevent="saveSheet()">
                <label for="mcsheet-name">{{ $say('Scene name', 'نام صحنه') }}</label>
                <input id="mcsheet-name" type="text" x-ref="field" x-model="name" x-on:keydown.enter.prevent="saveSheet()"
                       placeholder="{{ $say('e.g. Night view', 'مثلاً: نمای شب') }}">
                <div class="mcsheet-foot">
                    <button type="button" class="mcsheet-go" x-on:click="closeSheet()">{{ $say('Cancel', 'انصراف') }}</button>
                    <button type="submit" class="mcsheet-go" data-default x-on:click="saveSheet()">{{ $say('Save', 'ذخیره') }}</button>
                </div>
            </form>

            <div class="mcsheet-alert" role="alertdialog" aria-modal="true" aria-labelledby="mcsheet-q"
                 x-show="alert" x-cloak x-transition.opacity.duration.150ms
                 x-on:keydown.escape.prevent="alertCancel()">
                <div class="mcsheet-card">
                    <b id="mcsheet-q">{{ $say('Save changes before quitting?', 'خروج ذخیره شود؟') }}</b>
                    <p>{{ $say('Your scene «Studio» has unsaved lighting changes.', 'صحنهٔ «استودیو» شما تغییرات ذخیره‌نشدهٔ نورپردازی دارد.') }}</p>
                    <div class="mcsheet-cardbtns">
                        <button type="button" class="mcsheet-go" x-ref="cancel" x-on:click="alertCancel()">{{ $say('Cancel', 'لغو') }}</button>
                        <button type="button" class="mcsheet-go" data-default x-on:click="alertSave()">{{ $say('Save', 'ذخیره') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <p class="mcsheet-note" x-cloak x-show="note" x-text="note" role="status" style="position: absolute; inset-block-end: 10px; inset-inline: 0; margin: 0; color: #fff; text-shadow: 0 1px 8px rgba(0,0,0,.4)"></p>
    </div>
</section>
