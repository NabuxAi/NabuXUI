{{--
    The PR conversation rail: commit events condense under "and 2 more
    commits", reviews and force-pushes ride the same hairline, the comment
    box appends a real card to the timeline — plus the bubble palette.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int|string $n): string => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;
    $oct = function (string $name): string {
        $p = [
            'commit' => '<path fill-rule="evenodd" d="M11.93 8.5a4.002 4.002 0 0 1-7.86 0H.75a.75.75 0 0 1 0-1.5h3.32a4.002 4.002 0 0 1 7.86 0h3.32a.75.75 0 0 1 0 1.5Zm-1.43-.75a2.5 2.5 0 1 0-5 0 2.5 2.5 0 0 0 5 0Z"/>',
            'comment' => '<path fill-rule="evenodd" d="M1 2.75C1 1.784 1.784 1 2.75 1h10.5c.966 0 1.75.784 1.75 1.75v7.5A1.75 1.75 0 0 1 13.25 12H9.06l-2.573 2.573A1.458 1.458 0 0 1 4 13.543V12H2.75A1.75 1.75 0 0 1 1 10.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h2a.75.75 0 0 1 .75.75v2.19l2.72-2.72a.749.749 0 0 1 .53-.22h4.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"/>',
            'check' => '<path d="M13.78 4.22a.75.75 0 0 1 0 1.06l-7.25 7.25a.75.75 0 0 1-1.06 0L2.22 9.28a.751.751 0 0 1 .018-1.042.751.751 0 0 1 1.042-.018L6 10.94l6.72-6.72a.75.75 0 0 1 1.06 0Z"/>',
            'x' => '<path d="M3.72 3.72a.75.75 0 0 1 1.06 0L8 6.94l3.22-3.22a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L9.06 8l3.22 3.22a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215L8 9.06l-3.22 3.22a.751.751 0 0 1-1.042-.018.751.751 0 0 1-.018-1.042L6.94 8 3.72 4.78a.75.75 0 0 1 0-1.06Z"/>',
            'push' => '<path d="M8.53 2.47a.75.75 0 0 0-1.06 0L3.22 6.72a.75.75 0 0 0 1.06 1.06l2.97-2.97v8.44a.75.75 0 0 0 1.5 0V4.81l2.97 2.97a.75.75 0 1 0 1.06-1.06Z"/>',
            'merge' => '<path fill-rule="evenodd" d="M5.45 5.154A4.25 4.25 0 0 0 9.25 7.5h1.378a2.251 2.251 0 1 1 0 1.5H9.25A5.734 5.734 0 0 1 5 7.123v3.505a2.25 2.25 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.95-.218ZM4.25 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm8.5-4.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM5 3.25a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0Z"/>',
        ];
        return '<svg aria-hidden="true" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display:block">' . ($p[$name] ?? '') . '</svg>';
    };
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');
    .prt-root {
        --prt-canvas: #ffffff; --prt-subtle: #f6f8fa; --prt-fg: #1f2328; --prt-muted: #59636e;
        --prt-border: #d1d9e0; --prt-accent: #0969da;
        --prt-success: #1a7f37; --prt-done: #8250df; --prt-danger: #cf222e; --prt-attention: #9a6700;
        font-family: 'Figtree', 'Inter', 'Vazirmatn', sans-serif;
        color: var(--prt-fg);
    }
    html[data-theme="dark"] .prt-root {
        --prt-canvas: #0d1117; --prt-subtle: #151b23; --prt-fg: #f0f6fc; --prt-muted: #9198a1;
        --prt-border: #3d444d; --prt-accent: #4493f8;
        --prt-success: #3fb950; --prt-done: #ab7df8; --prt-danger: #f85149; --prt-attention: #d29922;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .prt-root {
            --prt-canvas: #0d1117; --prt-subtle: #151b23; --prt-fg: #f0f6fc; --prt-muted: #9198a1;
            --prt-border: #3d444d; --prt-accent: #4493f8;
            --prt-success: #3fb950; --prt-done: #ab7df8; --prt-danger: #f85149; --prt-attention: #d29922;
        }
    }
    .prt-root :focus-visible { outline: 2px solid var(--prt-accent); outline-offset: 2px; border-radius: 6px; }

    .prt-card { inline-size: min(100%, 42rem); margin-inline: auto; padding: 1rem 1rem 1.25rem; border: 1px solid var(--prt-border); border-radius: 6px; background: var(--prt-canvas); }
    .prt-mergeinto { margin: 0 0 1rem; font: 400 .8125rem/1.5 inherit; color: var(--prt-muted); }
    .prt-mergeinto b { color: var(--prt-fg); font-weight: 600; }
    .prt-item { position: relative; display: flex; gap: .75rem; padding-block-end: 1.25rem; }
    .prt-item::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: 15px; inline-size: 2px; background: var(--prt-border); }
    .prt-item[data-last="true"]::before { content: none; }
    .prt-badge { position: relative; z-index: 1; flex: none; display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border-radius: 50%; border: 1px solid var(--prt-border); background: var(--prt-subtle); color: var(--prt-muted); }
    .prt-badge[data-kind="commit"] { background: color-mix(in srgb, var(--prt-accent) 12%, var(--prt-canvas)); color: var(--prt-accent); border-color: color-mix(in srgb, var(--prt-accent) 30%, transparent); }
    .prt-badge[data-kind="approve"] { background: color-mix(in srgb, var(--prt-success) 12%, var(--prt-canvas)); color: var(--prt-success); border-color: color-mix(in srgb, var(--prt-success) 30%, transparent); }
    .prt-badge[data-kind="request"] { background: color-mix(in srgb, var(--prt-danger) 12%, var(--prt-canvas)); color: var(--prt-danger); border-color: color-mix(in srgb, var(--prt-danger) 30%, transparent); }
    .prt-badge[data-kind="push"] { background: color-mix(in srgb, var(--prt-attention) 14%, var(--prt-canvas)); color: var(--prt-attention); border-color: color-mix(in srgb, var(--prt-attention) 30%, transparent); }
    .prt-badge[data-kind="merged"] { background: color-mix(in srgb, var(--prt-done) 12%, var(--prt-canvas)); color: var(--prt-done); border-color: color-mix(in srgb, var(--prt-done) 30%, transparent); }
    .prt-body { flex: 1; min-inline-size: 0; }
    .prt-event { margin: .2rem 0 .35rem; font: 400 .8125rem/1.5 inherit; }
    .prt-event b { font-weight: 600; }
    .prt-event time { color: var(--prt-muted); }
    .prt-comment { border: 1px solid var(--prt-border); border-radius: 6px; background: var(--prt-canvas); }
    .prt-comment > header { display: flex; flex-wrap: wrap; gap: .25rem .5rem; align-items: center; padding: .5rem .75rem; border-block-end: 1px solid var(--prt-border); background: var(--prt-subtle); border-start-start-radius: 6px; border-start-end-radius: 6px; font: 400 .75rem/1.4 inherit; }
    .prt-comment > header b { font-weight: 600; }
    .prt-comment > header time { color: var(--prt-muted); }
    .prt-comment > header .prt-ref { margin-inline-start: auto; color: var(--prt-muted); font: 500 .6875rem/1 inherit; border: 1px solid var(--prt-border); border-radius: 2em; padding: .15rem .5rem; }
    .prt-comment > p { margin: 0; padding: .75rem; font: 400 .875rem/1.65 inherit; }
    .prt-condense { border: 0; background: none; padding: .15rem 0; margin-block-end: 1.1rem; color: var(--prt-accent); font: 500 .75rem/1.4 inherit; cursor: pointer; }
    .prt-condense:hover { text-decoration: underline; }
    .prt-composer { display: grid; gap: .6rem; padding-block-start: .25rem; }
    .prt-composer textarea { inline-size: 100%; min-block-size: 4.5rem; padding: .6rem .75rem; border: 1px solid var(--prt-border); border-radius: 6px; background: var(--prt-canvas); color: var(--prt-fg); font: 400 .875rem/1.6 inherit; resize: vertical; }
    .prt-composer-actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; justify-content: flex-end; }
    .prt-hint { margin: 0; margin-inline-end: auto; font: 400 .6875rem/1.4 inherit; color: var(--prt-muted); }
    .prt-btn { block-size: 2rem; padding-inline: .9rem; border: 0; border-radius: 6px; background: #1f883d; color: #fff; font: 500 .8125rem/1 inherit; cursor: pointer; }
    html[data-theme="dark"] .prt-btn { background: #238636; }
    .prt-btn:hover { background: #1a7f37; }
    .prt-btn:disabled { opacity: .55; cursor: not-allowed; }
    .prt-palette { display: grid; grid-template-columns: repeat(auto-fit, minmax(8.5rem, 1fr)); gap: .75rem; inline-size: min(100%, 38rem); margin-inline: auto; }
    .prt-chip { display: grid; gap: .4rem; justify-items: center; padding: .8rem .5rem; border: 1px solid var(--prt-border); border-radius: 6px; }
    .prt-chip small { font: 400 .6875rem/1.4 inherit; color: var(--prt-muted); text-align: center; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.prt-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .prt-item { gap: .6rem; }
        .prt-badge { inline-size: 1.75rem; }
        .prt-item::before { inset-inline-start: 13px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .prt-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="prt-root" x-data="{
        rtl: document.documentElement.dir === 'rtl',
        open: false,
        comments: [],
        draft: '',
        send() {
            const t = this.draft.trim();
            if (!t) return;
            this.comments.push({ who: this.who, when: this.when, body: t });
            this.draft = '';
        },
        who: '{{ $say('You', 'شما') }}',
        when: '{{ $say('just now', 'همین حالا') }}',
        num(n) { return '{{ $fa ? 'yes' : 'no' }}' === 'yes' ? String(n).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) },
    }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Every PR is just this rail', 'هر PR فقط همین ریل است') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('Unfold the side commits, then write a comment — it lands on the rail with its own bubble, the way every review and push before it did.', 'کامیت‌های کناری را باز کنید، بعد کامنتی بنویسید — با حباب خودش روی همان ریل می‌نشیند؛ همان‌طور که هر ریویو و پوش قبلی نشسته است.') }}
            </p>
        </div>

        <div class="prt-card">
            <p class="prt-mergeinto">
                <b>{{ $say('Sara', 'سارا') }}</b>
                {{ $say('wants to merge 3 commits into', 'می‌خواهد ۳ کامیت را در') }}
                <b>main</b> {{ $say('from', 'از') }} <b>feature/pdf</b>{{ $say('', ' ادغام کند') }}
            </p>

            <div class="prt-item">
                <span class="prt-badge" data-kind="commit">{!! $oct('commit') !!}</span>
                <div class="prt-body">
                    <p class="prt-event"><b>{{ $say('Sara', 'سارا') }}</b> {{ $say('pushed 3 commits', '۳ کامیت پوش کرد') }} <time>· {{ $say('yesterday', 'دیروز') }}</time></p>
                    <div class="prt-comment" style="margin-block-end: .5rem">
                        <header><b>feature/pdf</b><time>{{ $say('2 days ago', '۲ روز پیش') }}</time></header>
                        <p><code style="font: 500 .8125rem ui-monospace, monospace">a1b2c3d</code> — {{ $say('pick the PDF renderer', 'انتخاب موتور رندر PDF') }}</p>
                    </div>
                    <div class="prt-comment" x-show="open" x-cloak style="margin-block-end: .5rem">
                        <header><b>feature/pdf</b><time>{{ $say('yesterday', 'دیروز') }}</time></header>
                        <p><code style="font: 500 .8125rem ui-monospace, monospace">e4f5a6b</code> — {{ $say('stream large reports instead of buffering', 'به‌جای بافر، گزارش‌های بزرگ را استریم کن') }}</p>
                    </div>
                    <div class="prt-comment" x-show="open" x-cloak>
                        <header><b>feature/pdf</b><time>{{ $say('yesterday', 'دیروز') }}</time></header>
                        <p><code style="font: 500 .8125rem ui-monospace, monospace">9c8d7e6</code> — {{ $say('wire the download button to the job queue', 'اتصال دکمهٔ دانلود به صف کارها') }}</p>
                    </div>
                    <button type="button" class="prt-condense" x-on:click="open = !open" x-text="open ? (rtl ? 'بستن ۲ کامیت' : 'fold 2 commits') : (rtl ? '…و ۲ کامیت دیگر' : '…and 2 more commits')"></button>
                </div>
            </div>

            <div class="prt-item">
                <span class="prt-badge" data-kind="approve">{!! $oct('check') !!}</span>
                <div class="prt-body">
                    <p class="prt-event"><b>{{ $say('Milad', 'میلاد') }}</b> {{ $say('approved these changes', 'این تغییرها را تأیید کرد') }} <time>· {{ $say('6 hours ago', '۶ ساعت پیش') }}</time></p>
                    <div class="prt-comment">
                        <header><b>{{ $say('Milad', 'میلاد') }}</b> <time>{{ $say('6 hours ago', '۶ ساعت پیش') }}</time><span class="prt-ref">{{ $say('approved', 'تأیید') }}</span></header>
                        <p>{{ $say('The queue wiring looks right — one naming nit inline, the rest is ready to ship.', 'اتصال به صف درست است — یک نکتهٔ نام‌گذاری درون‌خطی هست، بقیه آمادهٔ انتشار است.') }}</p>
                    </div>
                </div>
            </div>

            <div class="prt-item">
                <span class="prt-badge" data-kind="push">{!! $oct('push') !!}</span>
                <div class="prt-body">
                    <p class="prt-event"><b>{{ $say('Sara', 'سارا') }}</b> {{ $say('force-pushed the branch', 'شاخه را force-push کرد') }} <time>· {{ $say('3 hours ago', '۳ ساعت پیش') }}</time></p>
                </div>
            </div>

            <template x-for="(c, i) in comments" :key="i">
                <div class="prt-item" :data-last="i === comments.length - 1 && draft.trim() === '' ? 'true' : 'false'">
                    <span class="prt-badge">{!! $oct('comment') !!}</span>
                    <div class="prt-body">
                        <div class="prt-comment">
                            <header><b x-text="c.who"></b> <time x-text="c.when"></time><span class="prt-ref">{{ $say('comment', 'کامنت') }}</span></header>
                            <p x-text="c.body"></p>
                        </div>
                    </div>
                </div>
            </template>

            <div class="prt-item" data-last="true">
                <span class="prt-badge">{!! $oct('comment') !!}</span>
                <div class="prt-body prt-composer">
                    <textarea x-model="draft" aria-label="{{ $say('Write a comment', 'نوشتن کامنت') }}" placeholder="{{ $say('Leave a comment — it joins the rail above', 'کامنتی بنویسید — به ریل بالا می‌پیوندد') }}"></textarea>
                    <div class="prt-composer-actions">
                        <p class="prt-hint">{{ $say('Enter nothing, nothing happens; the button knows.', 'خالی بفرستید، هیچ‌ اتفاقی نمی‌افتد؛ دکمه خودش می‌داند.') }}</p>
                        <button type="button" class="prt-btn" :disabled="!draft.trim()" x-on:click="send()">{{ $say('Comment', 'ثبت کامنت') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The bubble palette', 'پالت حباب‌ها') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Six event kinds, six tinted circles — the rail reads at a glance because the colours never change jobs.', 'شش نوع رویداد، شش دایرهٔ رنگی — ریل در یک نگاه خوانده می‌شود چون رنگ‌ها هیچ‌وقت شغلشان را عوض نمی‌کنند.') }}
        </p>
    </div>
    <div class="prt-root prt-palette">
        <div class="prt-chip"><span class="prt-badge" data-kind="commit">{!! $oct('commit') !!}</span><small>{{ $say('commit', 'کامیت') }}</small></div>
        <div class="prt-chip"><span class="prt-badge">{!! $oct('comment') !!}</span><small>{{ $say('comment', 'کامنت') }}</small></div>
        <div class="prt-chip"><span class="prt-badge" data-kind="approve">{!! $oct('check') !!}</span><small>{{ $say('approved', 'تأیید') }}</small></div>
        <div class="prt-chip"><span class="prt-badge" data-kind="request">{!! $oct('x') !!}</span><small>{{ $say('changes requested', 'درخواست تغییر') }}</small></div>
        <div class="prt-chip"><span class="prt-badge" data-kind="push">{!! $oct('push') !!}</span><small>{{ $say('force-pushed', 'فورس‌پوش') }}</small></div>
        <div class="prt-chip"><span class="prt-badge" data-kind="merged">{!! $oct('merge') !!}</span><small>{{ $say('merged', 'ادغام') }}</small></div>
    </div>
</section>
