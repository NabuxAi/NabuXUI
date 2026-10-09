{{--
    The Mac's corner banners on a desktop stage: three glass cards (Mail from
    Sara, a calendar reminder and a software update) stepped behind each other
    at the trailing corner, each 380px max with a gradient app icon and a
    two-line clamped body that expands on hover or focus to reveal the full
    text and the «Reply / Later» actions. «Dismiss all» slides them out;
    «New banner» slides one in.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $pool = [
        ['key' => 'mail', 'app' => 'Mail', 'icon' => '✉️', 'grad' => 'linear-gradient(160deg, #6EC6FF, #0A63C9)', 'when' => ['fa' => '۱۰:۴۲', 'en' => '10:42'],
         'title' => ['fa' => 'سارا احمدی', 'en' => 'Sara Novak'],
         'body' => ['fa' => 'جلسهٔ فردا ساعت ۱۰ به اتاق نارنجی منتقل شد؛ دستور جلسه پیوست است.', 'en' => 'Tomorrow’s 10:00 moved to the Orange room; agenda attached.'],
         'full' => ['fa' => 'جلسهٔ فردا ساعت ۱۰ به اتاق نارنجی منتقل شد؛ دستور جلسه پیوست است. اگر وقت داری قبل از ظهر نسخهٔ نهایی اسلایدها را بفرست تا برای مشتری بفرستم.', 'en' => 'Tomorrow’s 10:00 moved to the Orange room; agenda attached. If you can, send the final slides before noon so I can forward them to the client.']],
        ['key' => 'cal', 'app' => 'Calendar', 'icon' => '📅', 'grad' => 'linear-gradient(160deg, #FFFFFF, #E8E8ED)', 'when' => ['fa' => '۹:۱۵', 'en' => '9:15'],
         'title' => ['fa' => 'یادآوری تقویم', 'en' => 'Calendar reminder'],
         'body' => ['fa' => 'تمرین تیم — امروز ۱۷:۰۰، سالن مرکزی.', 'en' => 'Team rehearsal — today 17:00, main hall.'],
         'full' => ['fa' => 'تمرین تیم — امروز ۱۷:۰۰، سالن مرکزی. لباس تمرین دوم را فراموش نکن؛ بعد از تمرین ۳۰ دقیقه جلسهٔ کوتاه داریم.', 'en' => 'Team rehearsal — today 17:00, main hall. Don’t forget the second kit; there’s a short 30-minute debrief afterwards.']],
        ['key' => 'sys', 'app' => 'System', 'icon' => '⚙️', 'grad' => 'linear-gradient(160deg, #A8B2C0, #5E6878)', 'when' => ['fa' => '۸:۰۵', 'en' => '8:05'],
         'title' => ['fa' => 'به‌روزرسانی نرم‌افزار', 'en' => 'Software update'],
         'body' => ['fa' => 'macOS ۱۵٫۲ آمادهٔ نصب است.', 'en' => 'macOS 15.2 is ready to install.'],
         'full' => ['fa' => 'macOS ۱۵٫۲ آمادهٔ نصب است. نصب خودکار امشب ساعت ۲ بامداد زمان‌بندی شده؛ قبل از آن پرونده‌های باز را ذخیره کنید.', 'en' => 'macOS 15.2 is ready to install. Automatic install is scheduled tonight at 2 AM; save open documents before then.']],
    ];
@endphp
<style>
    .mcnotif-root {
        --mcnotif-text: #1E1E1E; --mcnotif-text2: #6D6D72; --mcnotif-accent: #007AFF;
        --mcnotif-hair: rgba(0, 0, 0, .15);
        --mcnotif-glass: rgba(245, 245, 245, .9);
        --mcnotif-dir: 1;
        --mcnotif-wall: linear-gradient(140deg, #2E4570 0%, #7C5B92 52%, #E39A72 100%);
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcnotif-root {
        --mcnotif-text: #F5F5F5; --mcnotif-text2: #A5A5AA; --mcnotif-accent: #0A84FF;
        --mcnotif-hair: rgba(255, 255, 255, .15);
        --mcnotif-glass: rgba(40, 40, 40, .86);
        --mcnotif-wall: linear-gradient(140deg, #1A2740 0%, #43314F 52%, #66422D 100%);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcnotif-root {
            --mcnotif-text: #F5F5F5; --mcnotif-text2: #A5A5AA; --mcnotif-accent: #0A84FF;
            --mcnotif-hair: rgba(255, 255, 255, .15);
            --mcnotif-glass: rgba(40, 40, 40, .86);
            --mcnotif-wall: linear-gradient(140deg, #1A2740 0%, #43314F 52%, #66422D 100%);
        }
    }
    [dir="rtl"] .mcnotif-root { --mcnotif-dir: -1; }

    .mcnotif-stage {
        position: relative; overflow: clip; min-block-size: 27rem; border-radius: var(--nx-radius-2xl);
        background: var(--mcnotif-wall);
        /* Label rests at the bottom, clear of the banner stack in the top corner. */
        display: grid; place-items: end center; padding-block-end: 3rem;
    }
    @media (max-width: 480px) { .mcnotif-stage { min-block-size: 30rem; } }
    .mcnotif-desktop { color: rgba(255, 255, 255, .8); text-align: center; font: 500 13px/1.7 system-ui, -apple-system, "Vazirmatn", sans-serif; text-shadow: 0 1px 8px rgba(0, 0, 0, .35); }
    .mcnotif-desktop b { display: block; font-size: 17px; }

    .mcnotif-stack {
        position: absolute; inset-block-start: 14px; inset-inline-end: 14px; z-index: 5;
        display: grid; gap: 10px; justify-items: end;
        inline-size: min(380px, calc(100% - 28px));
    }
    .mcnotif-card {
        position: relative; display: flex; gap: 11px; inline-size: 100%; padding: 12px 14px;
        border-radius: 14px; cursor: default;
        color: var(--mcnotif-text); background: var(--mcnotif-glass);
        backdrop-filter: blur(30px) saturate(1.5);
        box-shadow: 0 8px 32px rgba(0, 0, 0, .3), inset 0 0 0 .5px var(--mcnotif-hair);
        translate: 0 calc(var(--mcnotif-i) * 4px);
        scale: calc(1 - var(--mcnotif-i) * .025);
        opacity: calc(1 - var(--mcnotif-i) * .14);
        transition: translate .2s, scale .2s, opacity .2s;
    }
    .mcnotif-card:hover, .mcnotif-card:focus-within { translate: 0 0; scale: 1; opacity: 1; z-index: 2; }
    .mcnotif-card[data-enter] { animation: mcnotif-in .4s cubic-bezier(.2, .9, .3, 1); }
    .mcnotif-card[data-leaving] { animation: mcnotif-out .26s ease-in forwards; pointer-events: none; }
    @keyframes mcnotif-in { from { translate: calc(120% * var(--mcnotif-dir)) 0; opacity: 0; } }
    @keyframes mcnotif-out { to { translate: calc(130% * var(--mcnotif-dir)) 0; opacity: 0; } }
    @media (prefers-reduced-motion: reduce) { .mcnotif-card[data-enter], .mcnotif-card[data-leaving] { animation: none; } .mcnotif-card[data-leaving] { opacity: 0; } }

    .mcnotif-ico {
        flex: none; display: grid; place-items: center; inline-size: 38px; aspect-ratio: 1;
        border-radius: 9px; font-size: 19px; box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .18), 0 2px 8px rgba(0, 0, 0, .18);
    }
    .mcnotif-txt { min-inline-size: 0; flex: 1; display: grid; gap: 2px; }
    .mcnotif-txt header { display: flex; align-items: baseline; gap: 8px; }
    .mcnotif-txt header b { font: 600 13px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; white-space: nowrap; overflow: clip; text-overflow: ellipsis; }
    .mcnotif-txt header time { margin-inline-start: auto; flex: none; font-size: 11px; color: var(--mcnotif-text2); font-variant-numeric: tabular-nums; }
    .mcnotif-txt p {
        margin: 0; color: var(--mcnotif-text2); font: 400 12px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: clip;
    }
    .mcnotif-card:hover .mcnotif-txt p, .mcnotif-card:focus-within .mcnotif-txt p { display: block; }
    .mcnotif-more { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .25s ease; }
    .mcnotif-card:hover .mcnotif-more, .mcnotif-card:focus-within .mcnotif-more { grid-template-rows: 1fr; }
    .mcnotif-more > div { overflow: hidden; min-block-size: 0; }
    .mcnotif-actions { display: flex; gap: 8px; padding-block-start: 8px; }
    .mcnotif-act {
        block-size: 26px; padding-inline: 12px; border: 0; border-radius: 6px; cursor: pointer;
        font: 500 12px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
        color: var(--mcnotif-text); background: var(--mcnotif-glass); box-shadow: 0 .5px 1.5px rgba(0, 0, 0, .2), 0 0 0 .5px var(--mcnotif-hair);
    }
    .mcnotif-act[data-tone] { color: #fff; background: var(--mcnotif-accent); }
    .mcnotif-x {
        position: absolute; inset-block-start: -6px; inset-inline-end: -6px; z-index: 3;
        display: grid; place-items: center; inline-size: 20px; aspect-ratio: 1; padding: 0;
        border: 0; border-radius: 50%; cursor: pointer; opacity: 0;
        background: rgba(90, 90, 96, .85); color: #fff;
    }
    .mcnotif-card:hover .mcnotif-x, .mcnotif-card:focus-within .mcnotif-x { opacity: 1; }
    .mcnotif-x svg { inline-size: 8px; block-size: 8px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; }
    .mcnotif-root :is(button):focus-visible { outline: 2px solid var(--mcnotif-accent); outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .mcnotif-more { transition: none; } .mcnotif-card { transition: none; } }
</style>

<section class="pg-box mcnotif-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Corner notifications', 'اعلان‌های گوشه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Hover (or Tab into) a banner to expand it — the full text and «Reply / Later» appear. Banners stack three deep in steps, «Dismiss all» slides them out and «New banner» slides one back in.', 'روی بنر hover کنید (یا با Tab بروید) تا باز شود — متن کامل و «پاسخ / بعداً» ظاهر می‌شوند. سه بنر پله‌پله پشت هم می‌ایستند، «بستن همه» با سرخوردن بیرون‌شان می‌برد و «اعلان تازه» یکی را برمی‌گرداند.') }}
        </p>
    </div>

    <div style="display: flex; gap: .75rem; flex-wrap: wrap; justify-content: center">
        <button type="button" class="nx-button" data-variant="secondary" data-size="sm"
                x-data x-on:click="$dispatch('mcnotif-add')">{{ $say('New banner', 'اعلان تازه') }}</button>
        <button type="button" class="nx-button" data-variant="ghost" data-size="sm"
                x-data x-on:click="$dispatch('mcnotif-clear')">{{ $say('Dismiss all', 'بستن همه') }}</button>
    </div>

    <div class="mcnotif-stage"
         x-data="{
                fa: {{ $fa ? 'true' : 'false' }},
                list: [], seq: 0, cursor: 0, note: '', noteT: null,
                pool: {{ json_encode($pool) }},
                init() {
                    ['mail', 'cal', 'sys'].forEach(k => this.push(k, true));
                    this.$on('mcnotif-add', () => this.push(this.nextKey()));
                    this.$on('mcnotif-clear', () => this.clearAll());
                },
                p(key) { return this.pool.find(b => b.key === key) || this.pool[0] },
                nextKey() {
                    for (let i = 0; i < this.pool.length; i++) {
                        const k = this.pool[(this.cursor + i) % this.pool.length].key;
                        if (! this.list.some(b => b.key === k)) { this.cursor = (this.cursor + i + 1) % this.pool.length; return k }
                    }
                    this.cursor = (this.cursor + 1) % this.pool.length;
                    return this.pool[this.cursor].key;
                },
                push(key, instant) {
                    if (this.list.length >= 3) this.drop(this.list[0].id);
                    this.list.push({ id: ++this.seq, key: key, enter: ! instant, leaving: false });
                },
                drop(id) {
                    const b = this.list.find(x => x.id === id);
                    if (! b || b.leaving) return;
                    b.leaving = true;
                    setTimeout(() => { this.list = this.list.filter(x => x.id !== id) }, 260);
                },
                clearAll() { [...this.list].forEach(b => this.drop(b.id)) },
                reply(b) {
                    const p = this.p(b.key);
                    this.note = this.fa ? 'پاسخ به ' + p.title.fa + '…' : 'Replying to ' + p.title.en + '…';
                    clearTimeout(this.noteT); this.noteT = setTimeout(() => { this.note = '' }, 2400);
                    this.drop(b.id);
                },
                body(b, full) { const p = this.p(b.key); return this.fa ? (full ? p.full.fa : p.body.fa) : (full ? p.full.en : p.body.en) },
                when(b) { const p = this.p(b.key); return this.fa ? p.when.fa : p.when.en },
                label(b) { const p = this.p(b.key); return this.fa ? p.title.fa : p.title.en },
            }">
        <div class="mcnotif-desktop">
            <b>{{ $say('Elm-o Sanat — desktop', 'علم‌وصنعت — رومیزی') }}</b>
            {{ $say('Banners land at the trailing corner', 'بنرها گوشهٔ انتهایی می‌نشینند') }}
        </div>

        <div class="mcnotif-stack" aria-live="polite" aria-label="{{ $say('Notifications', 'اعلان‌ها') }}">
            <template x-for="(b, i) in list" :key="b.id">
                <article class="mcnotif-card" tabindex="0"
                         :style="'--mcnotif-i:' + i"
                         :data-enter="b.enter ? '' : null"
                         :data-leaving="b.leaving ? '' : null">
                    <span class="mcnotif-ico" :style="'background:' + p(b.key).grad" aria-hidden="true" x-text="p(b.key).icon"></span>
                    <div class="mcnotif-txt">
                        <header><b x-text="label(b)"></b><time x-text="when(b)">۱۰:۴۲</time></header>
                        <p x-text="body(b, false)"></p>
                        <div class="mcnotif-more">
                            <div>
                                <p x-text="body(b, true)" style="display: block"></p>
                                <div class="mcnotif-actions">
                                    <button type="button" class="mcnotif-act" data-tone x-on:click.stop="reply(b)">{{ $say('Reply', 'پاسخ') }}</button>
                                    <button type="button" class="mcnotif-act" x-on:click.stop="drop(b.id)">{{ $say('Later', 'بعداً') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="mcnotif-x" :aria-label="$say('Dismiss', 'بستن')" x-on:click.stop="drop(b.id)">
                        <svg viewBox="0 0 8 8" aria-hidden="true"><path d="M1.5 1.5l5 5M6.5 1.5l-5 5"/></svg>
                    </button>
                </article>
            </template>
        </div>

        <p x-cloak x-show="note" x-text="note" role="status"
           style="position: absolute; inset-block-end: 12px; inset-inline: 0; margin: 0; text-align: center; color: #fff; text-shadow: 0 1px 8px rgba(0, 0, 0, .45); font: 500 12px/1.6 system-ui, -apple-system, Vazirmatn, sans-serif"></p>
    </div>
</section>
