{{--
    The GitHub Issues page as one component: the Open/Closed counter tabs
    filtering a real list, rows with circular state octicons, bold titles,
    gray meta, label chips and assignee initials — plus a variant strip of
    the three issue states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int|string $n): string => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;
    $oct = function (string $name): string {
        $p = [
            'open' => '<path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Z"/>',
            'done' => '<path fill-rule="evenodd" d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16Zm3.78-9.72a.75.75 0 0 0-1.06-1.06L6.75 9.19 5.28 7.72a.75.75 0 0 0-1.06 1.06l2 2a.75.75 0 0 0 1.06 0l4.5-4.5Z"/>',
            'skip' => '<path fill-rule="evenodd" d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Z"/><path d="M4.25 8.75h7.5v-1.5h-7.5Z"/>',
            'comment' => '<path fill-rule="evenodd" d="M1 2.75C1 1.784 1.784 1 2.75 1h10.5c.966 0 1.75.784 1.75 1.75v7.5A1.75 1.75 0 0 1 13.25 12H9.06l-2.573 2.573A1.458 1.458 0 0 1 4 13.543V12H2.75A1.75 1.75 0 0 1 1 10.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h2a.75.75 0 0 1 .75.75v2.19l2.72-2.72a.749.749 0 0 1 .53-.22h4.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"/>',
        ];
        return '<svg aria-hidden="true" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display:block">' . ($p[$name] ?? '') . '</svg>';
    };
    $rows = [
        ['state' => 'open', 'title' => $say('Stat cards overflow at 375px on the analytics page', 'کارت‌های آمار در صفحهٔ تحلیل‌ها روی ۳۷۵ پیکسل سرریز می‌کنند'), 'meta' => $say('#412 opened 3 days ago by Sara', '#۴۱۲ سه روز پیش توسط سارا باز شد'), 'labels' => [['bug', 'باگ', '#d73a4a'], ['priority: high', 'اولویت: بالا', '#d93f0b']], 'who' => [$say('S', 'س'), $say('M', 'م')], 'talk' => 4],
        ['state' => 'open', 'title' => $say('Dark theme: divider lines disappear on invoices', 'تم تیره: خطوط جداکننده در فاکتورها دیده نمی‌شوند'), 'meta' => $say('#407 opened 6 days ago by Milad', '#۴۰۷ شش روز پیش توسط میلاد باز شد'), 'labels' => [['design', 'طراحی', '#0e8a16'], ['dark-theme', 'تم تیره', '#5319e7']], 'who' => [$say('M', 'م')], 'talk' => 9],
        ['state' => 'open', 'title' => $say('Add Persian date picker to the reports filter', 'افزودن تاریخ‌گیر شمسی به فیلتر گزارش‌ها'), 'meta' => $say('#391 opened last week by Parisa', '#۳۹۱ هفتهٔ پیش توسط پریسا باز شد'), 'labels' => [['enhancement', 'بهبود', '#a2eeef'], ['good first issue', 'شروع خوب', '#7057ff']], 'who' => [$say('P', 'پ'), $say('N', 'ن'), $say('K', 'ک')], 'talk' => 2],
        ['state' => 'closed', 'title' => $say('Email digest sends twice on Sundays', 'خلاصهٔ ایمیل یکشنبه‌ها دوبار ارسال می‌شود'), 'meta' => $say('#388 closed 2 days ago by Milad', '#۳۸۸ دو روز پیش توسط میلاد بسته شد'), 'labels' => [['bug', 'باگ', '#d73a4a']], 'who' => [$say('M', 'م')], 'talk' => 12],
        ['state' => 'closed', 'title' => $say('Rewrite the CSV export streaming', 'بازنویسی استریم خروجی CSV'), 'meta' => $say('#362 closed last week by Nima', '#۳۶۲ هفتهٔ پیش توسط نیما بسته شد'), 'labels' => [['tech-debt', 'بدهی فنی', '#bfd4f2']], 'who' => [$say('N', 'ن')], 'talk' => 5],
        ['state' => 'closed', 'title' => $say('Keyboard focus lost after closing the modal', 'پس از بستن مودال فوکوس کیبورد گم می‌شود'), 'meta' => $say('#349 closed 3 weeks ago by Sara', '#۳۴۹ سه هفته پیش توسط سارا بسته شد'), 'labels' => [['accessibility', 'دسترس‌پذیری', '#008672']], 'who' => [$say('S', 'س'), $say('A', 'آ')], 'talk' => 7],
    ];
    $openCount = count(array_filter($rows, fn ($r) => $r['state'] === 'open'));
    $closedCount = count($rows) - $openCount;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');
    .pri-root {
        --pri-canvas: #ffffff; --pri-subtle: #f6f8fa; --pri-fg: #1f2328; --pri-muted: #59636e;
        --pri-border: #d1d9e0; --pri-accent: #0969da; --pri-success: #1a7f37; --pri-done: #8250df;
        --pri-neutral: #59636e;
        font-family: 'Figtree', 'Inter', 'Vazirmatn', sans-serif;
        color: var(--pri-fg);
    }
    html[data-theme="dark"] .pri-root {
        --pri-canvas: #0d1117; --pri-subtle: #151b23; --pri-fg: #f0f6fc; --pri-muted: #9198a1;
        --pri-border: #3d444d; --pri-accent: #4493f8; --pri-success: #3fb950; --pri-done: #ab7df8;
        --pri-neutral: #9198a1;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .pri-root {
            --pri-canvas: #0d1117; --pri-subtle: #151b23; --pri-fg: #f0f6fc; --pri-muted: #9198a1;
            --pri-border: #3d444d; --pri-accent: #4493f8; --pri-success: #3fb950; --pri-done: #ab7df8;
            --pri-neutral: #9198a1;
        }
    }
    .pri-root :focus-visible { outline: 2px solid var(--pri-accent); outline-offset: 2px; border-radius: 6px; }

    .pri-list { inline-size: min(100%, 46rem); margin-inline: auto; border: 1px solid var(--pri-border); border-radius: 6px; background: var(--pri-canvas); }
    .pri-tabs { display: flex; flex-wrap: wrap; gap: .25rem 1.25rem; padding: .625rem 1rem; border-block-end: 1px solid var(--pri-border); background: var(--pri-subtle); border-start-start-radius: 6px; border-start-end-radius: 6px; }
    .pri-tab { display: inline-flex; align-items: center; gap: .4rem; border: 0; background: none; padding: .25rem 0; cursor: pointer; font: 500 .875rem/1.2 inherit; color: var(--pri-muted); }
    .pri-tab b { font-weight: 600; color: inherit; }
    .pri-tab[aria-pressed="true"] { color: var(--pri-fg); }
    .pri-tab[data-tone="open"][aria-pressed="true"] { color: var(--pri-success); }
    .pri-tab[data-tone="closed"][aria-pressed="true"] { color: var(--pri-done); }
    .pri-row { display: flex; flex-wrap: wrap; gap: .5rem .75rem; align-items: flex-start; padding: .625rem 1rem; border-block-end: 1px solid var(--pri-border); text-decoration: none; }
    .pri-row:last-child { border-block-end: 0; border-end-end-radius: 6px; border-end-start-radius: 6px; }
    .pri-row:hover { background: var(--pri-subtle); }
    .pri-ico { flex: none; margin-block-start: 1px; }
    .pri-ico[data-state="open"] { color: var(--pri-success); }
    .pri-ico[data-state="closed"] { color: var(--pri-done); }
    .pri-main { flex: 1 1 14rem; min-inline-size: 0; display: grid; gap: .2rem; }
    .pri-title { font: 400 1rem/1.4 inherit; color: var(--pri-fg); overflow-wrap: anywhere; }
    .pri-row:hover .pri-title { color: var(--pri-accent); }
    .pri-meta { font: 400 .75rem/1.5 inherit; color: var(--pri-muted); }
    .pri-side { display: flex; flex-wrap: wrap; gap: .35rem; align-items: center; justify-content: flex-end; }
    .pri-label { display: inline-flex; align-items: center; block-size: 1.25rem; padding-inline: .45rem; border-radius: 2em; font: 500 .75rem/1 inherit; color: color-mix(in srgb, var(--hue) 72%, black); border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent); white-space: nowrap; }
    html[data-theme="dark"] .pri-label { color: color-mix(in srgb, var(--hue) 82%, white); }
    .pri-faces { display: flex; }
    .pri-face { inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50%; display: grid; place-items: center; background: color-mix(in srgb, var(--pri-accent) 18%, var(--pri-canvas)); color: var(--pri-accent); font: 600 .65rem/1 inherit; border: 2px solid var(--pri-canvas); }
    .pri-face + .pri-face { margin-inline-start: -.4rem; }
    .pri-talk { display: inline-flex; align-items: center; gap: .25rem; font: 400 .75rem/1 inherit; color: var(--pri-muted); }
    .pri-hero { display: grid; gap: 1.5rem; justify-items: center; }
    .pri-strip { display: grid; gap: 0; inline-size: min(100%, 40rem); border: 1px solid var(--pri-border); border-radius: 6px; overflow: clip; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.pri-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .pri-side { justify-content: flex-start; }
        .pri-title { font-size: .9rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .pri-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="pri-root" x-data="{ tab: 'open' }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The Issues page, distilled', 'صفحهٔ ایشوها، تقطیرشده') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('Switch between Open and Closed — the rows and their green/purple state icons follow. Hover a title to see it turn GitHub blue.', 'بین Open و Closed جابه‌جا شوید — ردیف‌ها و آیکن‌های سبز/بنفش وضعیت دنبال می‌کنند. روی عنوان هاور کنید تا آبی گیت‌هاب شود.') }}
            </p>
        </div>

        <div class="pri-list" role="group" aria-label="{{ $say('nabux/dashboard issues', 'ایشوهای nabux/dashboard') }}">
            <div class="pri-tabs" role="tablist" aria-label="{{ $say('Filter by state', 'فیلتر با وضعیت') }}">
                <button type="button" class="pri-tab" data-tone="open" x-on:click="tab = 'open'" :aria-pressed="tab === 'open'">
                    {!! $oct('open') !!}<b>{{ $num($openCount) }}</b> {{ $say('Open', 'باز') }}
                </button>
                <button type="button" class="pri-tab" data-tone="closed" x-on:click="tab = 'closed'" :aria-pressed="tab === 'closed'">
                    {!! $oct('done') !!}<b>{{ $num($closedCount) }}</b> {{ $say('Closed', 'بسته') }}
                </button>
            </div>

            @foreach ($rows as $row)
                <a class="pri-row" href="#" x-show="tab === '{{ $row['state'] }}'" x-on:click.prevent>
                    <span class="pri-ico" data-state="{{ $row['state'] }}">{!! $oct($row['state'] === 'open' ? 'open' : 'done') !!}</span>
                    <span class="pri-main">
                        <span class="pri-title">{{ $row['title'] }}</span>
                        <span class="pri-meta">{{ $row['meta'] }}</span>
                    </span>
                    <span class="pri-side">
                        @foreach ($row['labels'] as [$en, $faText, $hue])
                            <span class="pri-label" style="--hue: {{ $hue }}">{{ $say($en, $faText) }}</span>
                        @endforeach
                        <span class="pri-faces" aria-hidden="true">
                            @foreach ($row['who'] as $initial)
                                <span class="pri-face">{{ $initial }}</span>
                            @endforeach
                        </span>
                        <span class="pri-talk">{!! $oct('comment') !!} {{ $num($row['talk']) }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Every state an issue can wear', 'هر حالتی که یک ایشو می‌پوشد') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Open in Primer green, completed in done purple, and not-planned in neutral gray — the same row anatomy, three verdicts.', 'باز با سبز پرایمر، تکمیل‌شده با بنفشِ done و برنامه‌ریزی‌نشده با خاکستریِ خنثی — همان کالبد ردیف، سه حکم.') }}
        </p>
    </div>
    <div class="pri-root pri-strip" style="margin-inline: auto">
        <div class="pri-row">
            <span class="pri-ico" data-state="open">{!! $oct('open') !!}</span>
            <span class="pri-main">
                <span class="pri-title">{{ $say('Slack notifications arrive out of order', 'اطلاع‌رسانی اسلک به‌هم‌ریخته می‌رسد') }}</span>
                <span class="pri-meta">{{ $say('#415 opened 4 hours ago', '#۴۱۵ چهار ساعت پیش باز شد') }}</span>
            </span>
            <span class="pri-side"><span class="pri-label" style="--hue: #d73a4a">{{ $say('bug', 'باگ') }}</span></span>
        </div>
        <div class="pri-row">
            <span class="pri-ico" data-state="closed">{!! $oct('done') !!}</span>
            <span class="pri-main">
                <span class="pri-title">{{ $say('Slack notifications arrive out of order', 'اطلاع‌رسانی اسلک به‌هم‌ریخته می‌رسد') }}</span>
                <span class="pri-meta">{{ $say('#415 closed as completed 2 hours ago', '#۴۱۵ به‌عنوان تکمیل‌شده دو ساعت پیش بسته شد') }}</span>
            </span>
            <span class="pri-side"><span class="pri-label" style="--hue: #d73a4a">{{ $say('bug', 'باگ') }}</span></span>
        </div>
        <div class="pri-row">
            <span class="pri-ico" style="color: var(--pri-neutral)">{!! $oct('skip') !!}</span>
            <span class="pri-main">
                <span class="pri-title">{{ $say('Support MySQL 5.7 alongside MySQL 8', 'پشتیبانی از MySQL 5.7 در کنار MySQL 8') }}</span>
                <span class="pri-meta">{{ $say('#356 closed as not planned last month', '#۳۵۶ به‌عنوان برنامه‌ریزی‌نشده ماه پیش بسته شد') }}</span>
            </span>
            <span class="pri-side"><span class="pri-label" style="--hue: #ffffff">{{ $say('wontfix', 'اصلاح نمی‌شود') }}</span></span>
        </div>
    </div>
</section>
