{{--
    Fluent's three messengers staged in one app: a ContentDialog («ذخیره
    تغییرات؟») with Primary/Secondary/Close over a scrim and Escape to
    dismiss, an InfoBar trio (info/success/critical) each with its own ✕,
    and a TeachingTip with a 9px directional tail anchored to its share
    button, dismissed by ✕ or an outside click.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $bars = [
        ['id' => 'info', 'tone' => 'info', 'icon' => 'ⓘ', 'text' => $say('A new build is available — it will update in the background.', 'نسخهٔ تازهٔ برنامه موجود است — در پس‌زمینه به‌روزرسانی می‌شود.')],
        ['id' => 'ok', 'tone' => 'success', 'icon' => '✓', 'text' => $say('«Sales report» was saved successfully.', '«گزارش فروش» با موفقیت ذخیره شد.')],
        ['id' => 'crit', 'tone' => 'critical', 'icon' => '⚠', 'text' => $say('The server connection dropped — local changes are kept.', 'اتصال به سرور قطع شد — تغییرات محلی نگه داشته می‌شود.')],
    ];
@endphp
<style>
    .fldlg-root {
        --fl-accent: #005FB8;
        --fl-accent-hover: #1A75C5;
        --fl-accent-press: #005399;
        --fl-on-accent: #FFFFFF;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
        --fl-control: rgba(255, 255, 255, .70);
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-control-stroke: rgba(0, 0, 0, .12);
        --fl-control-edge: rgba(0, 0, 0, .22);
        --fl-divider: rgba(0, 0, 0, .08);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-press: rgba(0, 0, 0, .06);
        --fl-success: #0F7B0F;
        --fl-caution: #9D5D00;
        --fl-critical: #C42B1C;
        --fl-focus: #1B1B1B;
        --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .20), 0 0 0 1px rgba(0, 0, 0, .07);
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        gap: .75rem;
    }
    html[data-theme="dark"] .fldlg-root {
        --fl-accent: #4CC2FF;
        --fl-accent-hover: #6BCFFF;
        --fl-accent-press: #2FA8DC;
        --fl-on-accent: #000000;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-layer: rgba(255, 255, 255, .05);
        --fl-control: rgba(255, 255, 255, .06);
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-control-stroke: rgba(255, 255, 255, .10);
        --fl-control-edge: rgba(255, 255, 255, .18);
        --fl-divider: rgba(255, 255, 255, .06);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-press: rgba(255, 255, 255, .03);
        --fl-success: #6CCB5F;
        --fl-caution: #FCE100;
        --fl-critical: #FF99A4;
        --fl-focus: #FFFFFF;
        --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .08);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .fldlg-root {
            --fl-accent: #4CC2FF;
            --fl-accent-hover: #6BCFFF;
            --fl-accent-press: #2FA8DC;
            --fl-on-accent: #000000;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-layer: rgba(255, 255, 255, .05);
            --fl-control: rgba(255, 255, 255, .06);
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-control-stroke: rgba(255, 255, 255, .10);
            --fl-control-edge: rgba(255, 255, 255, .18);
            --fl-divider: rgba(255, 255, 255, .06);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-press: rgba(255, 255, 255, .03);
            --fl-success: #6CCB5F;
            --fl-caution: #FCE100;
            --fl-critical: #FF99A4;
            --fl-focus: #FFFFFF;
            --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .08);
        }
    }
    .fldlg-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .fldlg-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }

    .fldlg-stage {
        position: relative; overflow: clip; min-block-size: 24rem;
        display: flex; flex-direction: column; border-radius: 8px;
        background: var(--fl-window);
        box-shadow: 0 24px 48px rgba(0, 0, 0, .16), 0 0 0 1px var(--fl-card-stroke);
    }
    .fldlg-titlebar { display: flex; align-items: center; gap: .5rem; block-size: 2.25rem; padding-inline: .875rem; border-block-end: 1px solid var(--fl-divider); font-size: .75rem; }
    .fldlg-unsaved { margin-inline-start: auto; font-size: .66rem; padding: .12rem .5rem; border-radius: 999px; background: var(--fl-hover); color: var(--fl-text-2); white-space: nowrap; }
    .fldlg-unsaved[data-saved="true"] { background: color-mix(in srgb, var(--fl-success) 14%, transparent); color: var(--fl-success); }

    .fldlg-bar {
        position: relative; overflow: clip; display: flex; align-items: flex-start; gap: .6rem;
        margin: .5rem .75rem 0; padding: .55rem .75rem; border-radius: 4px;
        font-size: .75rem; line-height: 1.8;
        background: color-mix(in srgb, var(--fldlg-tone) 9%, var(--fl-window));
        box-shadow: inset 0 0 0 1px var(--fl-card-stroke);
    }
    .fldlg-bar::before { content: ""; position: absolute; inset-block: 0; inset-inline-start: 0; inline-size: 3px; background: var(--fldlg-tone); }
    .fldlg-bar[data-tone="info"] { --fldlg-tone: var(--fl-accent); }
    .fldlg-bar[data-tone="success"] { --fldlg-tone: var(--fl-success); }
    .fldlg-bar[data-tone="critical"] { --fldlg-tone: var(--fl-critical); }
    .fldlg-baricon { flex: none; color: var(--fldlg-tone); }
    .fldlg-barbtn { flex: none; margin-inline-start: auto; display: grid; place-items: center; inline-size: 1.4rem; block-size: 1.4rem; border-radius: 3px; }
    .fldlg-barbtn:hover { background: var(--fl-hover); }

    .fldlg-doc { flex: 1; padding: .9rem 1.25rem; }
    .fldlg-doc p { margin: 0; font-size: .8rem; line-height: 2; }
    .fldlg-doc p + p { margin-block-start: .3rem; }

    .fldlg-foot { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; margin-block-start: auto; padding: .75rem; border-block-start: 1px solid var(--fl-divider); }
    .fldlg-btn {
        position: relative; display: inline-flex; align-items: center; justify-content: center;
        min-inline-size: 5rem; block-size: 2rem; padding-inline: .8rem; border-radius: 4px;
        font-size: .8125rem; background: var(--fl-control);
        box-shadow: inset 0 0 0 1px var(--fl-control-stroke), inset 0 -1px 0 var(--fl-control-edge);
        transition: background .12s, color .12s;
    }
    .fldlg-btn::after { content: ""; position: absolute; inset: 0; border-radius: inherit; }
    .fldlg-btn:hover::after { background: var(--fl-hover); }
    .fldlg-btn:active::after { background: var(--fl-press); }
    .fldlg-btn:active { color: var(--fl-text-2); }
    .fldlg-btn[data-accent] { background: var(--fl-accent); color: var(--fl-on-accent); box-shadow: inset 0 0 0 1px var(--fl-accent); }
    .fldlg-btn[data-accent]::after { display: none; }
    .fldlg-btn[data-accent]:hover { background: var(--fl-accent-hover); }
    .fldlg-btn[data-accent]:active { background: var(--fl-accent-press); }

    .fldlg-tipwrap { position: relative; margin-inline-start: auto; }
    .fldlg-tip {
        position: absolute; inset-block-end: calc(100% + 12px); inset-inline-end: -.35rem; z-index: 30;
        inline-size: min(15rem, 74vw); padding: .85rem .9rem .75rem; border-radius: 8px;
        background: var(--fl-layer);
        backdrop-filter: blur(30px) saturate(125%); -webkit-backdrop-filter: blur(30px) saturate(125%);
        box-shadow: var(--fl-flyout-shadow);
    }
    .fldlg-tip::after {
        content: ""; position: absolute; inset-block-end: -4.5px; inset-inline-end: 2.1rem;
        inline-size: 9px; block-size: 9px; rotate: 45deg;
        background: var(--fl-layer); box-shadow: 1px 1px 0 0 var(--fl-card-stroke);
    }
    .fldlg-tiphead { display: flex; align-items: center; gap: .5rem; font-size: .8rem; font-weight: 600; }
    .fldlg-tiphead button { margin-inline-start: auto; display: grid; place-items: center; inline-size: 1.3rem; block-size: 1.3rem; border-radius: 3px; }
    .fldlg-tiphead button:hover { background: var(--fl-hover); }
    .fldlg-tip p { margin: .3rem 0 0; font-size: .72rem; line-height: 1.8; color: var(--fl-text-2); }

    .fldlg-status { margin: 0; padding: 0 .875rem .6rem; font-size: .7rem; color: var(--fl-text-2); }

    .fldlg-scrim { position: absolute; inset: 0; z-index: 40; display: grid; place-items: center; padding: 1rem; background: rgba(0, 0, 0, .35); }
    .fldlg-dialog {
        inline-size: min(100%, 24rem); padding: 1.5rem; border-radius: 8px;
        background: var(--fl-window);
        box-shadow: 0 32px 64px rgba(0, 0, 0, .4), 0 0 0 1px var(--fl-card-stroke);
    }
    .fldlg-dialog h4 { margin: 0; font-size: 1.05rem; }
    .fldlg-dialog > p { margin: .6rem 0 0; font-size: .8rem; line-height: 1.9; color: var(--fl-text-2); }
    .fldlg-dlgbtns { display: grid; grid-template-columns: repeat(3, 1fr); gap: .5rem; margin-block-start: 1.4rem; }
    .fldlg-dlgbtns .fldlg-btn { min-inline-size: 0; padding-inline: .4rem; font-size: .78rem; }
    .fldlg-dlgbtns [data-close-strong] { background: var(--fl-accent); color: var(--fl-on-accent); box-shadow: inset 0 0 0 1px var(--fl-accent); }
    .fldlg-dlgbtns [data-close-strong]::after { display: none; }
    .fldlg-dlgbtns [data-close-strong]:hover { background: var(--fl-accent-hover); }
    .fldlg-dlgbtns [data-close-strong]:active { background: var(--fl-accent-press); }

    .fldlg-hint { margin: 0; text-align: center; font-size: .72rem; color: var(--fl-text-2); }

    @media (prefers-reduced-motion: reduce) {
        .fldlg-btn, .fldlg-btn::after { transition: none; }
    }

    /* The shared «Important props» table below this stage (rendered by
       livewire/component-demo.blade.php) hides its body rows until a
       scroll-reveal observer stamps [data-nx-revealed] on the table — and
       .nx-live switches the 2.5s CSS failsafe off — so a full-page capture
       that never scrolls records a header over an empty body. Its nowrap
       cells also push the fourth header column past the phone edge. Pin this
       page's table rows to the readable state and let them wrap when narrow;
       scoped through :has(.fldlg-root), so it never reaches another demo. */
    :where(.nx-js) .pg:has(.fldlg-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.fldlg-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem;
        }
        .pg:has(.fldlg-root) .nx-data-table :is(th, td):last-child {
            overflow-wrap: anywhere;
        }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Three messengers, one stage', 'سه پیام‌رسان، یک صحنه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Save opens the ContentDialog with its three buttons over a scrim; the info bars dismiss one by one; the teaching tip points its tail at the share button and folds on ✕ or an outside click. Escape closes everything.', 'ذخیره، ContentDialog را با سه دکمه‌اش روی پردهٔ تیره باز می‌کند؛ نوارهای اطلاعات یکی‌یکی بسته می‌شوند؛ راهنما دُمش را به سمت دکمهٔ اشتراک می‌گیرد و با ✕ یا کلیک بیرون جمع می‌شود. Escape همه را می‌بندد.') }}
        </p>
    </div>

    <div class="fldlg-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            dlg: false, tip: false, saved: false,
            bars: {{ json_encode($bars, JSON_UNESCAPED_UNICODE) }},
            allBars: {{ json_encode($bars, JSON_UNESCAPED_UNICODE) }},
            savedMsg: '',
            tSaved: {{ json_encode($say('Saved', 'ذخیره شد')) }},
            tUnsaved: {{ json_encode($say('Unsaved changes', 'تغییرات ذخیره‌نشده')) }},
            tSaveDone: {{ json_encode($say('Changes saved to «Sales report»', 'تغییرات در «گزارش فروش» ذخیره شد')) }},
            tSkip: {{ json_encode($say('Closed without saving', 'بدون ذخیره بسته شد')) }},
            tCancel: {{ json_encode($say('Cancelled', 'لغو شد')) }},
            tRestored: {{ json_encode($say('Info bars restored', 'نوارهای اطلاعات برگشتند')) }},
            dismiss(id) { this.bars = this.bars.filter(b => b.id !== id) },
            restore() { this.bars = this.allBars; this.savedMsg = this.tRestored },
            act(kind) {
                this.dlg = false;
                if (kind === 'save') { this.saved = true; this.savedMsg = this.tSaveDone }
                else if (kind === 'skip') { this.saved = false; this.savedMsg = this.tSkip }
                else { this.savedMsg = this.tCancel }
            },
        }"
        x-on:keydown.escape.window="dlg = false; tip = false">
        <div class="fldlg-stage">
            <div class="fldlg-titlebar">
                <span aria-hidden="true">📝</span>
                <span style="min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap">{{ $say('Sales report — Editor', 'گزارش فروش — ویرایشگر') }}</span>
                <span class="fldlg-unsaved" :data-saved="saved ? 'true' : 'false'" x-text="saved ? tSaved : tUnsaved">{{ $say('Unsaved changes', 'تغییرات ذخیره‌نشده') }}</span>
            </div>

            <div style="display: grid">
                <template x-for="b in bars" :key="b.id">
                    <div class="fldlg-bar" :data-tone="b.tone">
                        <span class="fldlg-baricon" aria-hidden="true" x-text="b.icon"></span>
                        <span x-text="b.text"></span>
                        <button type="button" class="fldlg-barbtn" :aria-label="(fa ? 'بستن اطلاع‌بار — ' : 'Dismiss info bar — ') + b.tone" x-on:click="dismiss(b.id)">
                            <svg width="7" height="7" viewBox="0 0 8 8" aria-hidden="true"><path d="M1 1l6 6M7 1l-6 6" stroke="currentColor" stroke-width="1.1" fill="none"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <div class="fldlg-doc">
                <p>{{ $say('Fourth-quarter sales grew 18% year over year, led by the northern region.', 'فروش فصل چهارم نسبت به سال قبل ۱۸٪ رشد داشت، با پیشتازی منطقهٔ شمال.') }}</p>
                <p>{{ $say('Churn fell to 2.1% and the average basket grew by one item.', 'نرخ ریزش به ۲٫۱٪ رسید و میانگین سبد خرید یک قلم بزرگ‌تر شد.') }}</p>
            </div>

            <div class="fldlg-foot">
                <button type="button" class="fldlg-btn" data-accent x-on:click="dlg = true; $nextTick(() => $refs.prim && $refs.prim.focus())">{{ $say('Save', 'ذخیره') }}</button>
                <button type="button" class="fldlg-btn" x-on:click="restore()">{{ $say('Restore info bars', 'برگرداندن نوارها') }}</button>
                <div class="fldlg-tipwrap" x-ref="tipwrap">
                    <button type="button" class="fldlg-btn" :aria-expanded="tip ? 'true' : 'false'"
                        aria-label="{{ $say('Share — opens a tip', 'اشتراک‌گذاری — راهنما را باز می‌کند') }}"
                        x-on:click="tip = !tip">{{ $say('Share', 'اشتراک‌گذاری') }}</button>
                    <div class="fldlg-tip" role="dialog" aria-label="{{ $say('Quick tip', 'نکتهٔ سریع') }}" x-show="tip" x-cloak x-transition.opacity.duration.150ms
                        x-on:click.outside="if (! $refs.tipwrap.contains($event.target)) tip = false">
                        <div class="fldlg-tiphead">
                            {{ $say('Quick tip', 'نکتهٔ سریع') }}
                            <button type="button" aria-label="{{ $say('Dismiss tip', 'بستن راهنما') }}" x-on:click="tip = false">
                                <svg width="7" height="7" viewBox="0 0 8 8" aria-hidden="true"><path d="M1 1l6 6M7 1l-6 6" stroke="currentColor" stroke-width="1.1" fill="none"/></svg>
                            </button>
                        </div>
                        <p>{{ $say('Use Share to send this document as a link — permissions follow the folder.', 'با «اشتراک‌گذاری» سند را به‌شکل پیوند بفرستید — مجوزها از پوشه پیروی می‌کنند.') }}</p>
                    </div>
                </div>
            </div>
            <p class="fldlg-status" aria-live="polite" x-text="savedMsg"></p>

            <div class="fldlg-scrim" x-show="dlg" x-cloak x-transition.opacity.duration.150ms>
                <div class="fldlg-dialog" role="dialog" aria-modal="true" aria-labelledby="fldlg-dtitle">
                    <h4 id="fldlg-dtitle">{{ $say('Save your changes?', 'ذخیره تغییرات؟') }}</h4>
                    <p>{{ $say('Do you want to save the changes to «Sales report»? Cancelling keeps the document open and unsaved.', 'می‌خواهید تغییرات «گزارش فروش» ذخیره شود؟ با لغو، سند باز و ذخیره‌نشده می‌ماند.') }}</p>
                    <div class="fldlg-dlgbtns">
                        <button type="button" class="fldlg-btn" x-ref="prim" x-on:click="act('save')">{{ $say('Save', 'ذخیره') }}</button>
                        <button type="button" class="fldlg-btn" x-on:click="act('skip')">{{ $say("Don't save", 'ذخیره‌نکردن') }}</button>
                        <button type="button" class="fldlg-btn" data-close-strong x-on:click="act('cancel')">{{ $say('Cancel', 'لغو') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <p class="fldlg-hint">
            {{ $say('The dialog, the bars and the tip all live inside the stage — nothing ever escapes it.', 'دیالوگ، نوارها و راهنما همه داخل صحنه می‌مانند — هیچ‌کدام بیرون نمی‌زنند.') }}
        </p>
    </div>
</section>
