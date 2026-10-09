{{--
    Carbon's combo button on a deploy toolbar: the labelled half always runs
    the primary action while the arrow half unfolds the alternates in a flat,
    squared menu (Escape and outside taps close it); a status line reports each
    run, and the variants row shows all four kinds.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .cbcb-root {
        --cbcb-primary: #0f62fe; --cbcb-primary-hover: #0353e9; --cbcb-primary-active: #0043ce;
        --cbcb-secondary: #393939; --cbcb-secondary-hover: #4c4c4c;
        --cbcb-danger: #da1e28; --cbcb-danger-hover: #b81920;
        --cbcb-text: #161616; --cbcb-text-secondary: #525252;
        --cbcb-border: #e0e0e0; --cbcb-border-strong: #8d8d8d;
        --cbcb-layer: #f4f4f4; --cbcb-layer-hover: #e8e8e8;
        --cbcb-font: 'IBM Plex Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-family: var(--cbcb-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbcb-root {
        --cbcb-primary: #4589ff; --cbcb-primary-hover: #6ea6ff; --cbcb-primary-active: #0f62fe;
        --cbcb-secondary: #6f6f6f; --cbcb-secondary-hover: #8d8d8d;
        --cbcb-danger: #fa4d56; --cbcb-danger-hover: #ff8389;
        --cbcb-text: #f4f4f4; --cbcb-text-secondary: #c6c6c6;
        --cbcb-border: #393939; --cbcb-border-strong: #8d8d8d;
        --cbcb-layer: #262626; --cbcb-layer-hover: #333333;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbcb-root {
            --cbcb-primary: #4589ff; --cbcb-primary-hover: #6ea6ff; --cbcb-primary-active: #0f62fe;
            --cbcb-secondary: #6f6f6f; --cbcb-secondary-hover: #8d8d8d;
            --cbcb-danger: #fa4d56; --cbcb-danger-hover: #ff8389;
            --cbcb-text: #f4f4f4; --cbcb-text-secondary: #c6c6c6;
            --cbcb-border: #393939; --cbcb-border-strong: #8d8d8d;
            --cbcb-layer: #262626; --cbcb-layer-hover: #333333;
        }
    }
    .cbcb { position: relative; display: inline-flex; vertical-align: top; }
    .cbcb-main, .cbcb-arrow { block-size: 3rem; border: none; font: 400 .875rem/1 var(--cbcb-font); cursor: pointer;
                              transition: background-color .11s ease-in, color .11s ease-in; }
    .cbcb-main { display: inline-flex; align-items: center; gap: .5rem; padding-inline: 1rem; }
    .cbcb-arrow { display: grid; place-items: center; inline-size: 3rem; }
    .cbcb-main:focus-visible, .cbcb-arrow:focus-visible { outline: 2px solid var(--cbcb-primary); outline-offset: 1px; z-index: 1; }
    .cbcb-arrow svg { transition: rotate 240ms cubic-bezier(.2, 0, 0, 1); }
    .cbcb[data-open='true'] .cbcb-arrow svg { rotate: 180deg; }
    .cbcb[data-kind='primary'] .cbcb-main, .cbcb[data-kind='primary'] .cbcb-arrow { background: var(--cbcb-primary); color: #fff; }
    .cbcb[data-kind='primary'] .cbcb-main:hover, .cbcb[data-kind='primary'] .cbcb-arrow:hover { background: var(--cbcb-primary-hover); }
    .cbcb[data-kind='primary'] .cbcb-main:active, .cbcb[data-kind='primary'] .cbcb-arrow:active { background: var(--cbcb-primary-active); }
    .cbcb[data-kind='secondary'] .cbcb-main, .cbcb[data-kind='secondary'] .cbcb-arrow { background: var(--cbcb-secondary); color: #fff; }
    .cbcb[data-kind='secondary'] .cbcb-main:hover, .cbcb[data-kind='secondary'] .cbcb-arrow:hover { background: var(--cbcb-secondary-hover); }
    .cbcb[data-kind='tertiary'] .cbcb-main, .cbcb[data-kind='tertiary'] .cbcb-arrow { background: transparent; color: var(--cbcb-primary); box-shadow: inset 0 0 0 1px var(--cbcb-primary); }
    .cbcb[data-kind='tertiary'] .cbcb-main:hover, .cbcb[data-kind='tertiary'] .cbcb-arrow:hover { background: var(--cbcb-primary); color: #fff; }
    .cbcb[data-kind='danger'] .cbcb-main, .cbcb[data-kind='danger'] .cbcb-arrow { background: var(--cbcb-danger); color: #fff; }
    .cbcb[data-kind='danger'] .cbcb-main:hover, .cbcb[data-kind='danger'] .cbcb-arrow:hover { background: var(--cbcb-danger-hover); }
    .cbcb-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline: 0; z-index: 5; margin: 0; padding: 0;
                 list-style: none; background: var(--cbcb-layer); box-shadow: 0 2px 6px rgba(0, 0, 0, .2); }
    .cbcb[data-open='false'] .cbcb-menu { display: none; }
    .cbcb-menu button { inline-size: 100%; display: flex; align-items: center; gap: .5rem; padding: .65rem 1rem; border: none;
                        background: transparent; color: var(--cbcb-text); font: 400 .875rem/1.3 var(--cbcb-font);
                        text-align: start; cursor: pointer; border-block-end: 1px solid var(--cbcb-border); }
    .cbcb-menu li:last-child button { border-block-end: none; }
    .cbcb-menu button:hover { background: var(--cbcb-layer-hover); }
    .cbcb-menu button:focus-visible { outline: 2px solid var(--cbcb-primary); outline-offset: -2px; }
    .cbcb-log { inline-size: min(100%, 34rem); min-block-size: 2.75rem; display: flex; align-items: center; gap: .625rem;
                padding: .5rem 1rem; background: var(--cbcb-layer); border-block-start: 2px solid var(--cbcb-primary);
                font-size: .875rem; color: var(--cbcb-text); }
    .cbcb-log svg { flex: none; color: #24a148; }
    .cbcb-log small { margin-inline-start: auto; color: var(--cbcb-text-secondary); white-space: nowrap; }
    .cbcb-stage { position: relative; inline-size: min(100%, 34rem); padding: 1rem; padding-block-end: 4.5rem;
                  background: var(--cbcb-layer); }
    .cbcb-stage .cbcb { position: absolute; inset-inline: 1rem; inset-block-end: 1rem; inline-size: calc(100% - 2rem); }
    .cbcb-stage .cbcb-menu { inset-block-start: auto; inset-block-end: calc(100% + 2px); box-shadow: 0 -2px 6px rgba(0, 0, 0, .2); }
    .cbcb-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .cbcb-spec-row { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .cbcb-spec-cell { display: grid; gap: .5rem; justify-items: center; }
    .cbcb-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    :where(.nx-js) .pg:has(.cbcb-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .pg:has(.cbcb-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
        .cbcb-log small { white-space: normal; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbcb-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbcb-root"
    x-data="{
        open: false,
        log: null,
        at: '',
        clock() { return new Date().toLocaleTimeString('{{ $fa ? 'fa-IR' : 'en-GB' }}', { hour: '2-digit', minute: '2-digit' }) },
        run(msg) { this.log = msg; this.at = this.clock(); this.open = false; },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One button, five actions', 'یک دکمه، پنج اقدام') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Deploy is always one click away on the labelled half; the arrow unfolds the alternates beneath. Escape, an outside tap or picking an item folds it back.', 'استقرار همیشه روی نیمهٔ برچسب‌دار یک کلیک فاصله دارد؛ پیکان، جایگزین‌ها را زیرش باز می‌کند. Escape، لمس بیرون یا برداشتن یک آیتم آن را جمع می‌کند.') }}
            </p>
        </div>

        <div class="cbcb" data-kind="primary" x-bind:data-open="open.toString()"
            x-on:keydown.escape.window="open = false"
            x-on:click.outside="open = false">
            <button type="button" class="cbcb-main" x-on:click="run('{{ $say('Build ۸۴۲ deployed to production', 'بیلد ۸۴۲ روی تولید استقرار یافت') }}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5"/><path d="M5 12l7 7 7-7"/></svg>
                {{ $say('Deploy', 'استقرار') }}
            </button>
            <button type="button" class="cbcb-arrow" x-on:click="open = !open" x-bind:aria-expanded="open.toString()" aria-haspopup="menu" aria-label="{{ $say('More deploy actions', 'اقدام‌های بیشتر استقرار') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <ul class="cbcb-menu" role="menu" aria-label="{{ $say('Alternate deploy actions', 'اقدام‌های جایگزین استقرار') }}">
                <li role="none"><button type="button" role="menuitem" x-on:click="run('{{ $say('Preview build ۸۴۲ staged', 'بیلد ۸۴۲ در محیط آزمایشی نشست') }}')">{{ $say('Deploy to staging', 'استقرار آزمایشی') }}</button></li>
                <li role="none"><button type="button" role="menuitem" x-on:click="run('{{ $say('Migrations ran in 1.8s', 'مهاجرت‌ها در ۱٫۸ ثانیه اجرا شد') }}')">{{ $say('Run migrations', 'اجرای مهاجرت‌ها') }}</button></li>
                <li role="none"><button type="button" role="menuitem" x-on:click="run('{{ $say('Application cache cleared', 'کش برنامه پاک شد') }}')">{{ $say('Clear cache', 'پاک‌سازی کش') }}</button></li>
                <li role="none"><button type="button" role="menuitem" x-on:click="run('{{ $say('۲۱۴ tests passed', '۲۱۴ تست سبز شد') }}')">{{ $say('Run test suite', 'اجرای تست‌ها') }}</button></li>
            </ul>
        </div>

        <div class="cbcb-log" role="status">
            <template x-if="log">
                <span style="display: inline-flex; align-items: center; gap: .625rem">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9.5"/></svg>
                    <span x-text="log"></span>
                </span>
            </template>
            <template x-if="!log">
                <span style="color: var(--cbcb-text-secondary)">{{ $say('Pick an action — the log lands here.', 'اقدامی بردارید — گزارش همین‌جا می‌نشیند.') }}</span>
            </template>
            <small x-show="log" x-cloak x-text="at"></small>
        </div>

        <div class="cbcb-stage">
            <p style="margin: 0 0 .75rem; font-size: .875rem; color: var(--cbcb-text-secondary)">
                {{ $say('Near the bottom edge the menu opens upward — direction flips, the flat frame stays.', 'نزدیک لبهٔ پایین، منو به بالا باز می‌شود — جهت برمی‌گردد، قاب تخت سرِ جایش است.') }}
            </p>
            <div class="cbcb" data-kind="secondary" x-bind:data-open="open.toString()" x-data="{ open: false }"
                x-on:keydown.escape.window="open = false" x-on:click.outside="open = false">
                <button type="button" class="cbcb-main" x-on:click="$dispatch('cbcb:secondary-run', '{{ $say('Backup started — snapshot ۱۹', 'پشتیبان‌گیری آغاز شد — اسنپ‌شات ۱۹') }}')">
                    {{ $say('Back up now', 'پشتیبان‌گیری فوری') }}
                </button>
                <button type="button" class="cbcb-arrow" x-on:click="open = !open" x-bind:aria-expanded="open.toString()" aria-haspopup="menu" aria-label="{{ $say('More backup actions', 'اقدام‌های بیشتر پشتیبان‌گیری') }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <ul class="cbcb-menu" role="menu">
                    <li role="none"><button type="button" role="menuitem" x-on:click="$dispatch('cbcb:secondary-run', '{{ $say('Restore point selected — ۰۹:۱۴', 'نقطهٔ بازیابی انتخاب شد — ۰۹:۱۴') }}')">{{ $say('Restore from snapshot', 'بازیابی از اسنپ‌شات') }}</button></li>
                    <li role="none"><button type="button" role="menuitem" x-on:click="$dispatch('cbcb:secondary-run', '{{ $say('Snapshot ۱۸ downloaded (2.4GB)', 'اسنپ‌شات ۱۸ دانلود شد (۲٫۴GB)') }}')">{{ $say('Download latest', 'دانلود آخرین نسخه') }}</button></li>
                </ul>
            </div>
            <div class="cbcb-log" style="inline-size: 100%" role="status" x-data="{ msg: null }" x-on:cbcb:secondary-run.window="msg = $event.detail">
                <span x-show="!msg" style="color: var(--cbcb-text-secondary)">{{ $say('Secondary combo — same anatomy, gray kind.', 'کمبوی ثانویه — همان کالبدشکنی، ردهٔ خاکستری.') }}</span>
                <span x-show="msg" x-cloak x-text="msg"></span>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbcb-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Primary, secondary, tertiary and danger — both halves always share one colour and one hover.', 'primary، secondary، tertiary و danger — هر دو نیمه همیشه یک رنگ و یک هاور دارند.') }}
            </p>
        </div>
        <div class="cbcb-spec">
        <div class="cbcb-spec-row">
            @foreach ([['primary', 'استقرار', 'Deploy'], ['secondary', 'دعوت', 'Invite'], ['tertiary', 'صادرکردن', 'Export'], ['danger', 'حذف', 'Delete']] as [$kind, $faLabel, $enLabel])
                <div class="cbcb-spec-cell">
                    <div class="cbcb" data-kind="{{ $kind }}" x-data="{ open: false }" x-bind:data-open="open.toString()"
                        x-on:keydown.escape.window="open = false" x-on:click.outside="open = false">
                        <button type="button" class="cbcb-main">{{ $say($enLabel, $faLabel) }}</button>
                        <button type="button" class="cbcb-arrow" x-on:click="open = !open" x-bind:aria-expanded="open.toString()" aria-haspopup="menu" aria-label="{{ $say('More actions', 'اقدام‌های بیشتر') }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                        <ul class="cbcb-menu" role="menu">
                            <li role="none"><button type="button" role="menuitem">{{ $say('Alternate action', 'اقدام جایگزین') }}</button></li>
                            <li role="none"><button type="button" role="menuitem">{{ $say('Second alternative', 'جایگزین دوم') }}</button></li>
                        </ul>
                    </div>
                    <small>{{ $kind }}</small>
                </div>
            @endforeach
        </div>
        </div>
    </div>
</section>
