{{--
    GitHub's signature sub-navigation: underlined tabs with live CounterLabels
    that actually swap the panel beneath them — plus the CounterLabel and
    density variants.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int|string $n): string => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;
    $oct = function (string $name): string {
        $p = [
            'comment' => '<path fill-rule="evenodd" d="M1 2.75C1 1.784 1.784 1 2.75 1h10.5c.966 0 1.75.784 1.75 1.75v7.5A1.75 1.75 0 0 1 13.25 12H9.06l-2.573 2.573A1.458 1.458 0 0 1 4 13.543V12H2.75A1.75 1.75 0 0 1 1 10.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h2a.75.75 0 0 1 .75.75v2.19l2.72-2.72a.749.749 0 0 1 .53-.22h4.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"/>',
            'commit' => '<path fill-rule="evenodd" d="M11.93 8.5a4.002 4.002 0 0 1-7.86 0H.75a.75.75 0 0 1 0-1.5h3.32a4.002 4.002 0 0 1 7.86 0h3.32a.75.75 0 0 1 0 1.5Zm-1.43-.75a2.5 2.5 0 1 0-5 0 2.5 2.5 0 0 0 5 0Z"/>',
            'shield' => '<path fill-rule="evenodd" d="M7.467.133a1.748 1.748 0 0 1 1.066 0l5.25 1.68A1.75 1.75 0 0 1 15 3.48V7c0 1.566-.32 3.182-1.303 4.682-.983 1.498-2.585 2.813-5.032 3.855a1.697 1.697 0 0 1-1.33 0c-2.447-1.042-4.049-2.357-5.032-3.855C1.32 10.182 1 8.566 1 7V3.48a1.75 1.75 0 0 1 1.217-1.667Zm.61 1.429a.25.25 0 0 0-.153 0l-5.25 1.68a.25.25 0 0 0-.174.238V7c0 1.358.275 2.666 1.057 3.86.784 1.194 2.121 2.34 4.366 3.297a.196.196 0 0 0 .154 0c2.245-.956 3.582-2.104 4.366-3.298C13.225 9.666 13.5 8.36 13.5 7V3.48a.251.251 0 0 0-.174-.237l-5.25-1.68ZM8.75 4.75v1.5h1.5a.75.75 0 0 1 0 1.5h-1.5v1.5a.75.75 0 0 1-1.5 0v-1.5h-1.5a.75.75 0 0 1 0-1.5h1.5v-1.5a.75.75 0 0 1 1.5 0Z"/>',
            'file' => '<path fill-rule="evenodd" d="M2 1.75C2 .784 2.784 0 3.75 0h6.586c.464 0 .909.184 1.237.513l2.914 2.914c.329.328.513.773.513 1.237v9.586A1.75 1.75 0 0 1 13.25 16h-9.5A1.75 1.75 0 0 1 2 14.25Zm1.75-.25a.25.25 0 0 0-.25.25v12.5c0 .138.112.25.25.25h9.5a.25.25 0 0 0 .25-.25V6h-2.75A1.75 1.75 0 0 1 9 4.25V1.5Zm6.75.062V4.25c0 .138.112.25.25.25h2.688l-.011-.013-2.914-2.914-.013-.011Z"/>',
            'merge' => '<path fill-rule="evenodd" d="M5.45 5.154A4.25 4.25 0 0 0 9.25 7.5h1.378a2.251 2.251 0 1 1 0 1.5H9.25A5.734 5.734 0 0 1 5 7.123v3.505a2.25 2.25 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.95-.218ZM4.25 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm8.5-4.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM5 3.25a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0Z"/>',
            'check' => '<path d="M13.78 4.22a.75.75 0 0 1 0 1.06l-7.25 7.25a.75.75 0 0 1-1.06 0L2.22 9.28a.751.751 0 0 1 .018-1.042.751.751 0 0 1 1.042-.018L6 10.94l6.72-6.72a.75.75 0 0 1 1.06 0Z"/>',
            'x' => '<path d="M3.72 3.72a.75.75 0 0 1 1.06 0L8 6.94l3.22-3.22a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L9.06 8l3.22 3.22a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215L8 9.06l-3.22 3.22a.751.751 0 0 1-1.042-.018.751.751 0 0 1-.018-1.042L6.94 8 3.72 4.78a.75.75 0 0 1 0-1.06Z"/>',
        ];
        return '<svg aria-hidden="true" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display:block">' . ($p[$name] ?? '') . '</svg>';
    };
@endphp
<style>
    .prn-root {
        --prn-canvas: #ffffff; --prn-subtle: #f6f8fa; --prn-fg: #1f2328; --prn-muted: #59636e;
        --prn-border: #d1d9e0; --prn-accent: #0969da;
        --prn-success: #1a7f37; --prn-done: #8250df; --prn-danger: #cf222e; --prn-count: #eff2f5;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif;
        color: var(--prn-fg);
    }
    html[data-theme="dark"] .prn-root {
        --prn-canvas: #0d1117; --prn-subtle: #151b23; --prn-fg: #f0f6fc; --prn-muted: #9198a1;
        --prn-border: #3d444d; --prn-accent: #4493f8;
        --prn-success: #3fb950; --prn-done: #ab7df8; --prn-danger: #f85149; --prn-count: #262c36;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .prn-root {
            --prn-canvas: #0d1117; --prn-subtle: #151b23; --prn-fg: #f0f6fc; --prn-muted: #9198a1;
            --prn-border: #3d444d; --prn-accent: #4493f8;
            --prn-success: #3fb950; --prn-done: #ab7df8; --prn-danger: #f85149; --prn-count: #262c36;
        }
    }
    .prn-root :focus-visible { outline: 2px solid var(--prn-accent); outline-offset: -2px; border-radius: 6px; }

    .prn-page { inline-size: min(100%, 44rem); margin-inline: auto; }
    .prn-nav { display: flex; gap: .25rem; padding-inline: .5rem; border-block-end: 1px solid var(--prn-border); overflow-x: auto; scrollbar-width: none; }
    .prn-nav::-webkit-scrollbar { display: none; }
    .prn-tab { position: relative; display: inline-flex; flex: none; align-items: center; gap: .45rem; padding: .55rem .75rem; border: 0; border-block-end: 2px solid transparent; margin-block-end: -1px; background: none; color: var(--prn-muted); font: 500 .875rem/1.2 inherit; cursor: pointer; white-space: nowrap; border-radius: 6px 6px 0 0; }
    .prn-tab:hover { color: var(--prn-fg); background: color-mix(in srgb, var(--prn-border) 30%, transparent); }
    .prn-tab[aria-current="page"] { color: var(--prn-fg); border-block-end-color: #fd8c73; }
    .prn-tab svg { flex: none; }
    .prn-count { display: inline-grid; place-items: center; min-inline-size: 1.35rem; padding-inline: .45rem; block-size: 1.25rem; border-radius: 2em; background: var(--prn-count); font: 500 .75rem/1 inherit; }
    .prn-tab[aria-current="page"] .prn-count { background: color-mix(in srgb, var(--prn-accent) 15%, var(--prn-canvas)); color: var(--prn-accent); }
    .prn-panel { border: 1px solid var(--prn-border); border-block-start: 0; border-radius: 0 0 6px 6px; padding: 1rem; min-block-size: 10.5rem; background: var(--prn-canvas); }
    .prn-mini { display: grid; gap: .55rem; }
    .prn-mini-row { display: flex; flex-wrap: wrap; gap: .35rem .6rem; align-items: baseline; font: 400 .8125rem/1.5 inherit; }
    .prn-mini-row svg { align-self: center; flex: none; }
    .prn-mini-row[data-tone="success"] svg { color: var(--prn-success); }
    .prn-mini-row[data-tone="danger"] svg { color: var(--prn-danger); }
    .prn-mini-row[data-tone="done"] svg { color: var(--prn-done); }
    .prn-mini-row b { font-weight: 600; }
    .prn-mini-row code { font: 500 .75rem/1 ui-monospace, SFMono-Regular, Menlo, monospace; color: var(--prn-muted); }
    .prn-mini-row time, .prn-mini-row small { color: var(--prn-muted); font-size: .71875rem; }
    .prn-fileline { display: flex; align-items: baseline; gap: .6rem; }
    .prn-fileline .prn-diff { margin-inline-start: auto; font: 500 .75rem/1 ui-monospace, monospace; white-space: nowrap; }
    .prn-plus { color: var(--prn-success); }
    .prn-minus { color: var(--prn-danger); }
    .prn-merged { display: inline-flex; align-items: center; gap: .45rem; block-size: 1.5rem; padding-inline: .65rem; border-radius: 2em; background: var(--prn-done); color: #fff; font: 500 .75rem/1 inherit; }
    .prn-strip { display: flex; flex-wrap: wrap; gap: 1.25rem 2.5rem; align-items: center; justify-content: center; }
    .prn-spec { display: grid; gap: .5rem; justify-items: center; }
    .prn-spec small { font: 400 .6875rem/1.4 inherit; color: var(--nx-text-muted); }
    .prn-sm .prn-tab { padding: .4rem .55rem; font-size: .78rem; }
    .prn-sm .prn-count { block-size: 1.1rem; font-size: .6875rem; min-inline-size: 1.1rem; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.prn-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .prn-panel { padding: .75rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .prn-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="prn-root" x-data="{ tab: 'conversation', rtl: document.documentElement.dir === 'rtl' }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Tabs, the GitHub way', 'تب‌ها، به روش گیت‌هاب') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('Four tabs, four CounterLabels, one burnt-orange underline. Hover a tab for the gray window, click it and the panel beneath obeys.', 'چهار تب، چهار شمارنده، یک زیرخط نارنجیِ سوخته. روی تب هاور کنید تا پنجرهٔ خاکستری ببینید، کلیک کنید و پنل زیرش اطاعت می‌کند.') }}
            </p>
        </div>

        <div class="prn-page">
            <nav class="prn-nav" role="tablist" aria-label="{{ $say('Pull request sections', 'بخش‌های پول‌ریکوئست') }}">
                <button type="button" class="prn-tab" role="tab" x-on:click="tab = 'conversation'" :aria-current="tab === 'conversation' ? 'page' : null">
                    {!! $oct('comment') !!} {{ $say('Conversation', 'گفت‌وگو') }} <span class="prn-count">{{ $num(4) }}</span>
                </button>
                <button type="button" class="prn-tab" role="tab" x-on:click="tab = 'commits'" :aria-current="tab === 'commits' ? 'page' : null">
                    {!! $oct('commit') !!} {{ $say('Commits', 'کامیت‌ها') }} <span class="prn-count">{{ $num(7) }}</span>
                </button>
                <button type="button" class="prn-tab" role="tab" x-on:click="tab = 'checks'" :aria-current="tab === 'checks' ? 'page' : null">
                    {!! $oct('shield') !!} {{ $say('Checks', 'بررسی‌ها') }} <span class="prn-count">{{ $num(3) }}</span>
                </button>
                <button type="button" class="prn-tab" role="tab" x-on:click="tab = 'files'" :aria-current="tab === 'files' ? 'page' : null">
                    {!! $oct('file') !!} {{ $say('Files changed', 'فایل‌های تغییر یافته') }} <span class="prn-count">{{ $num(12) }}</span>
                </button>
            </nav>

            <div class="prn-panel" role="tabpanel">
                <div class="prn-mini" x-show="tab === 'conversation'">
                    <div class="prn-mini-row" data-tone="done">
                        <span class="prn-merged">{!! $oct('merge') !!} {{ $say('Merged', 'ادغام شد') }}</span>
                        <b>{{ $say('Milad', 'میلاد') }}</b>
                        {{ $say('merged 3 commits into', '۳ کامیت را در') }} <b>main</b>
                        <time>· {{ $say('2 days ago', '۲ روز پیش') }}</time>
                    </div>
                    <p style="margin: 0; font: 400 .8125rem/1.6 inherit; color: var(--prn-muted)">
                        {{ $say('The conversation keeps 4 comments, 1 approval and the squash decision — scroll the rail above in the PR Timeline demo.', 'گفت‌وگو ۴ کامنت، ۱ تأیید و تصمیم squash را نگه می‌دارد — ریلش را در دموی تایم‌لاین ببینید.') }}
                    </p>
                </div>

                <div class="prn-mini" x-show="tab === 'commits'" x-cloak>
                    <div class="prn-mini-row"><code>a1b2c3d</code> <span>{{ $say('pick the PDF renderer', 'انتخاب موتور رندر PDF') }}</span><time>· {{ $say('2 days ago', '۲ روز پیش') }}</time></div>
                    <div class="prn-mini-row"><code>e4f5a6b</code> <span>{{ $say('stream large reports instead of buffering', 'استریم گزارش‌های بزرگ به‌جای بافر') }}</span><time>· {{ $say('yesterday', 'دیروز') }}</time></div>
                    <div class="prn-mini-row"><code>9c8d7e6</code> <span>{{ $say('wire the download button to the queue', 'اتصال دکمهٔ دانلود به صف') }}</span><time>· {{ $say('yesterday', 'دیروز') }}</time></div>
                </div>

                <div class="prn-mini" x-show="tab === 'checks'" x-cloak>
                    <div class="prn-mini-row" data-tone="success">{!! $oct('check') !!} <b>build و تست</b> <small>#{{ $num(812) }} · {{ $say('3 دقیقه', '3 min') }}</small></div>
                    <div class="prn-mini-row" data-tone="success">{!! $oct('check') !!} <b>lint</b> <small>#{{ $num(812) }} · {{ $fa ? '۴۱ ثانیه' : '41s' }}</small></div>
                    <div class="prn-mini-row" data-tone="danger">{!! $oct('x') !!} <b>e2e / گزارش‌ها</b> <small>#{{ $num(812) }} · {{ $fa ? '۵ دقیقه' : '5 min' }} · {{ $say('flaky, retried', 'بی‌ثبات، دوباره اجرا شد') }}</small></div>
                </div>

                <div class="prn-mini" x-show="tab === 'files'" x-cloak>
                    <div class="prn-mini-row prn-fileline">{!! $oct('file') !!} <span>app/Jobs/RenderReportPdf.php</span><span class="prn-diff"><span class="prn-plus">{{ $fa ? '+۱۲۴' : '+124' }}</span> <span class="prn-minus">{{ $fa ? '−۰' : '−0' }}</span></span></div>
                    <div class="prn-mini-row prn-fileline">{!! $oct('file') !!} <span>resources/views/reports/show.blade.php</span><span class="prn-diff"><span class="prn-plus">{{ $fa ? '+۱۸' : '+18' }}</span> <span class="prn-minus">{{ $fa ? '−۴' : '−4' }}</span></span></div>
                    <div class="prn-mini-row prn-fileline">{!! $oct('file') !!} <span>tests/Feature/ReportPdfTest.php</span><span class="prn-diff"><span class="prn-plus">{{ $fa ? '+۴۶' : '+46' }}</span> <span class="prn-minus">{{ $fa ? '−۲' : '−2' }}</span></span></div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('CounterLabel postures', 'حالت‌وشکل‌های CounterLabel') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Neutral gray at rest, accent when its tab owns the underline, and the compact density for toolbars — one component, three voices.', 'در حالت عادی خاکستریِ خنثی، وقتی تبش زیرخط را دارد آبی، و چگالی فشرده برای تولبارها — یک کامپوننت، سه صدا.') }}
        </p>
    </div>
    <div class="prn-root prn-strip">
        <div class="prn-spec">
            <nav class="prn-nav" style="border-block-end: 0; padding-inline: 0" aria-label="{{ $say('Neutral demo', 'نمونهٔ خنثی') }}">
                <button type="button" class="prn-tab">{!! $oct('comment') !!} {{ $say('Notes', 'یادداشت‌ها') }} <span class="prn-count">{{ $num(9) }}</span></button>
            </nav>
            <small>neutral</small>
        </div>
        <div class="prn-spec">
            <nav class="prn-nav" style="border-block-end: 0; padding-inline: 0" aria-label="{{ $say('Selected demo', 'نمونهٔ انتخاب‌شده') }}">
                <button type="button" class="prn-tab" aria-current="page">{!! $oct('commit') !!} {{ $say('Commits', 'کامیت‌ها') }} <span class="prn-count">{{ $num(7) }}</span></button>
            </nav>
            <small>aria-current="page"</small>
        </div>
        <div class="prn-spec">
            <nav class="prn-nav prn-sm" style="border-block-end: 0; padding-inline: 0" aria-label="{{ $say('Compact demo', 'نمونهٔ فشرده') }}">
                <button type="button" class="prn-tab" aria-current="page">{!! $oct('file') !!} {{ $say('Files', 'فایل‌ها') }} <span class="prn-count">{{ $num(12) }}</span></button>
            </nav>
            <small>compact</small>
        </div>
    </div>
</section>
