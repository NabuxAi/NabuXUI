{{--
    Atlassian's page header as the top of a Jira project page: breadcrumb,
    title with an inprogress lozenge, avatar stack, quiet icon actions and the
    blue primary, then the underline tabs that really switch the page's
    summary line. A second box shows the Confluence hero and the compact
    variant side by side.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $tabs = [
        'overview' => [
            'en' => '3 in progress, 2 in review and 7 backlog issues in this sprint. Mean time to resolve is down to 1.8 days.',
            'fa' => '۳ کار در جریان، ۲ کار در بازبینی و ۷ کار عقب‌افتاده در این اسپرینت. میانگین زمان حل مساله‌ها به ۱٫۸ روز رسیده است.',
        ],
        'work' => [
            'en' => 'Sprint 24 board — “Advanced search” enters review today while “Instalment checkout” waits on legal sign-off.',
            'fa' => 'تختهٔ اسپرینت ۲۴ — «جست‌وجوی پیشرفته» امروز وارد بازبینی می‌شود و «پرداخت اقساط» منتظر داوری حقوقی است.',
        ],
        'plan' => [
            'en' => 'Phase two closes on Oct 20; 84% of committed work is done and 32 hours of capacity remain.',
            'fa' => 'فاز دوم تا ۲۸ مهر بسته می‌شود؛ ۸۴٪ کارهای تعهدشده تکمیل شده و ظرفیت باقی‌مانده ۳۲ ساعت است.',
        ],
        'files' => [
            'en' => '14 files — latest: “User behaviour analysis.pdf” and “Atlassian palette.fig”, uploaded yesterday.',
            'fa' => '۱۴ فایل — آخرین موارد: «سند تحلیل رفتار کاربر.pdf» و «طرح رنگ اطلسیان.fig» دیروز بارگذاری شده‌اند.',
        ],
    ];
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    .atph-root {
        --atph-blue: #0052CC; --atph-blue-hover: #0747A6; --atph-blue-tint: #E9F2FF;
        --atph-ink: #172B4D; --atph-ink-strong: #091E42; --atph-subtle: #44546F;
        --atph-muted: #626F86; --atph-border: #DFE1E6; --atph-hover: #F1F2F4;
        --atph-surface: #FFFFFF; --atph-page: #F7F8F9;
        font-family: 'Inter', 'Vazirmatn', sans-serif; color: var(--atph-ink);
    }
    html[data-theme="dark"] .atph-root {
        --atph-blue: #388BFF; --atph-blue-hover: #579DFF; --atph-blue-tint: #17263B;
        --atph-ink: #C7D1DB; --atph-ink-strong: #E4EAF0; --atph-subtle: #A9B8C4;
        --atph-muted: #8590A2; --atph-border: #2C3136; --atph-hover: #1D2125;
        --atph-surface: #161A1D; --atph-page: #101214;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atph-root {
            --atph-blue: #388BFF; --atph-blue-hover: #579DFF; --atph-blue-tint: #17263B;
            --atph-ink: #C7D1DB; --atph-ink-strong: #E4EAF0; --atph-subtle: #A9B8C4;
            --atph-muted: #8590A2; --atph-border: #2C3136; --atph-hover: #1D2125;
            --atph-surface: #161A1D; --atph-page: #101214;
        }
    }
    .atph-root :focus-visible { outline: 2px solid var(--atph-blue); outline-offset: 2px; }

    .atph-page { inline-size: min(100%, 46rem); margin-inline: auto; border: 1px solid var(--atph-border);
                  border-radius: 6px; background: var(--atph-page); padding: 1.25rem; display: grid; gap: .75rem; }
    .atph-sheet { border: 1px solid var(--atph-border); border-radius: 6px; background: var(--atph-surface);
                  padding: 1rem 1.25rem 0; display: grid; gap: .5rem; }
    .atph-crumbs { display: flex; flex-wrap: wrap; align-items: center; gap: .35rem; font: 400 .78rem/1.4 Inter, Vazirmatn, system-ui; color: var(--atph-muted); }
    .atph-crumbs a { color: var(--atph-blue); text-decoration: none; }
    .atph-crumbs a:hover { text-decoration: underline; }
    .atph-row { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem; }
    .atph-title { margin: 0; font: 500 1.4rem/1.25 "Charlie Display", Inter, Vazirmatn, system-ui; color: var(--atph-ink-strong); display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; min-inline-size: 0; }
    .atph-mini { display: inline-block; padding: 2px 8px; border-radius: 3px; font: 700 .7rem/1.6 Inter, Vazirmatn, system-ui; background: var(--atph-blue-tint); color: var(--atph-blue); white-space: nowrap; }
    .atph-people { display: flex; align-items: center; padding-inline-start: 8px; }
    .atph-face { display: grid; place-items: center; inline-size: 28px; aspect-ratio: 1; border-radius: 50%;
                 border: 2px solid var(--atph-surface); font: 600 .68rem Inter, Vazirmatn, system-ui; color: #fff; }
    .atph-face + .atph-face, .atph-more { margin-inline-start: -9px; }
    .atph-more { display: grid; place-items: center; inline-size: 28px; aspect-ratio: 1; border-radius: 50%;
                 border: 2px solid var(--atph-surface); background: var(--atph-hover); color: var(--atph-muted); font: 600 .68rem Inter, Vazirmatn, system-ui; }
    .atph-actions { margin-inline-start: auto; display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; }
    .atph-iconbtn { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border: none; border-radius: 3px;
                    background: none; color: var(--atph-muted); cursor: pointer; transition: background .15s ease, color .15s ease; }
    .atph-iconbtn:hover { background: var(--atph-hover); color: var(--atph-blue); }
    .atph-iconbtn svg { fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .atph-iconbtn[aria-pressed='true'] { color: var(--atph-blue); }
    .atph-iconbtn[aria-pressed='true'] svg { fill: color-mix(in srgb, currentColor 22%, transparent); }
    .atph-btn { block-size: 2rem; padding-inline: .75rem; border: none; border-radius: 3px; cursor: pointer;
                font: 500 .82rem/1 Inter, Vazirmatn, system-ui; color: var(--atph-ink-strong); background: var(--atph-hover); transition: background .15s ease; }
    .atph-btn:hover { background: color-mix(in srgb, var(--atph-hover) 70%, var(--atph-muted)); }
    .atph-btn[data-primary] { background: var(--atph-blue); color: #fff; }
    .atph-btn[data-primary]:hover { background: var(--atph-blue-hover); }
    .atph-meta { display: flex; flex-wrap: wrap; gap: 1rem; font: 400 .75rem/1.4 Inter, Vazirmatn, system-ui; color: var(--atph-muted); }
    .atph-meta b { font-weight: 500; color: var(--atph-subtle); }
    .atph-tabs { display: flex; gap: .25rem; overflow-x: auto; scrollbar-width: none; border-block-end: 2px solid color-mix(in srgb, var(--atph-border) 55%, transparent); }
    .atph-tabs button { flex: none; padding: .55rem .75rem; border: none; border-block-end: 2px solid transparent; margin-block-end: -2px;
                        background: none; cursor: pointer; font: 500 .82rem/1 Inter, Vazirmatn, system-ui; color: var(--atph-subtle); transition: color .15s ease, border-color .15s ease; }
    .atph-tabs button:hover { color: var(--atph-blue); }
    .atph-tabs button[aria-current='page'] { color: var(--atph-blue); border-block-end-color: var(--atph-blue); }
    .atph-pane { margin-block: .25rem 1rem; font: 400 .85rem/1.65 Inter, Vazirmatn, system-ui; color: var(--atph-subtle); }
    .atph-pane b { color: var(--atph-ink); font-weight: 600; }
    .atph-variants { display: grid; gap: 1rem; justify-items: center; }
    .atph-vgrid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 18rem), 1fr)); inline-size: 100%; max-inline-size: 44rem; }
    .atph-vcard { border: 1px solid var(--atph-border); border-radius: 6px; background: var(--atph-surface); padding: 1rem 1.25rem; display: grid; gap: .5rem; align-content: start; }
    .atph-vcard > small { font: 500 .7rem/1 Inter, Vazirmatn, system-ui; letter-spacing: .4px; color: var(--atph-muted); }
    .atph-hero-title { margin: 0; font: 500 1.6rem/1.2 "Charlie Display", Inter, Vazirmatn, system-ui; color: var(--atph-ink-strong); }
    .atph-hero-desc { margin: 0; font: 400 .82rem/1.6 Inter, Vazirmatn, system-ui; color: var(--atph-muted); }
    @media (prefers-reduced-motion: reduce) {
        .atph-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }

    /* The «Important props» table the demo page renders after this partial
       reveals its rows only when a scroll observer marks the table; full-page
       captures never scroll, so pin the rows visible. Ships with this partial
       only, so it stays page-scoped. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="atph-root"
    x-data="{
        tab: 'overview',
        star: false,
        watchOn: @js($say('Watching', 'دنبال می‌کنید')),
        watchOff: @js($say('Watch', 'دنبال کردن')),
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The frame every Jira page opens with', 'قابی که هر صفحهٔ جیرا با آن باز می‌شود') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('Breadcrumb, title, people and actions on one line, then the underline tabs — switch tabs to see the page summary change underneath.', 'مسیر، تیتر، آدم‌ها و اکشن‌ها در یک سطر، و بعد تب‌های زیرخط‌دار — تب‌ها را عوض کنید تا خلاصهٔ صفحه زیرشان عوض شود.') }}
            </p>
        </div>

        <div class="atph-page">
            <div class="atph-sheet">
                <nav class="atph-crumbs" aria-label="{{ $say('Breadcrumb', 'مسیر صفحه') }}">
                    <a href="#">{{ $say('Projects', 'پروژه‌ها') }}</a><span aria-hidden="true">/</span><b>{{ $say('Nabu platform', 'نابو پلتفرم') }}</b>
                </nav>
                <div class="atph-row">
                    <h4 class="atph-title">
                        {{ $say('Nabu UI redesign', 'طراحی رابط نابو ۲') }}
                        <span class="atph-mini">{{ $say('In progress', 'در جریان') }}</span>
                    </h4>
                    <div class="atph-actions">
                        <div class="atph-people" aria-label="{{ $say('5 people on this project', '۵ نفر روی این پروژه') }}">
                            <span class="atph-face" style="background:#0052CC" title="{{ $say('M. Rezaei', 'م. رضایی') }}">{{ $say('MR', 'م‌ر') }}</span>
                            <span class="atph-face" style="background:#1F845A" title="{{ $say('A. Kazemi', 'ع. کاظمی') }}">{{ $say('AK', 'ع‌ک') }}</span>
                            <span class="atph-face" style="background:#6E5DC6" title="{{ $say('S. Moradi', 'س. مرادی') }}">{{ $say('SM', 'س‌م') }}</span>
                            <span class="atph-more">+{{ $fa ? '۲' : '2' }}</span>
                        </div>
                        <button type="button" class="atph-iconbtn" :aria-pressed="star ? 'true' : 'false'" x-on:click="star = !star" :title="star ? watchOn : watchOff" :aria-label="star ? watchOn : watchOff">
                            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l2.7 5.6 6.3.9-4.5 4.3 1 6.2-5.5-2.9-5.5 2.9 1-6.2L3 9.5l6.3-.9z"/></svg>
                        </button>
                        <button type="button" class="atph-btn">{{ $say('Invite', 'دعوت') }}</button>
                        <button type="button" class="atph-btn" data-primary>{{ $say('Create issue', 'ایجاد مساله') }}</button>
                    </div>
                </div>
                <nav class="atph-tabs" role="tablist" aria-label="{{ $say('Project views', 'نمای‌های پروژه') }}">
                    @foreach (['overview' => ['Overview', 'نمای کلی'], 'work' => ['Work items', 'کارها'], 'plan' => ['Timeline', 'زمان‌بندی'], 'files' => ['Files', 'فایل‌ها']] as $key => $label)
                        <button type="button" role="tab" x-on:click="tab = '{{ $key }}'" :aria-current="tab === '{{ $key }}' ? 'page' : null" :aria-selected="tab === '{{ $key }}' ? 'true' : 'false'">{{ $say($label[0], $label[1]) }}</button>
                    @endforeach
                </nav>
                <p class="atph-pane" role="tabpanel">
                    <template x-if="tab === 'overview'"><span><b>{{ $say('This sprint:', 'این اسپرینت:') }}</b> {{ $say($tabs['overview']['en'], $tabs['overview']['fa']) }}</span></template>
                    <template x-if="tab === 'work'"><span><b>{{ $say('Board:', 'تخته:') }}</b> {{ $say($tabs['work']['en'], $tabs['work']['fa']) }}</span></template>
                    <template x-if="tab === 'plan'"><span><b>{{ $say('Timeline:', 'زمان‌بندی:') }}</b> {{ $say($tabs['plan']['en'], $tabs['plan']['fa']) }}</span></template>
                    <template x-if="tab === 'files'"><span><b>{{ $say('Files:', 'فایل‌ها:') }}</b> {{ $say($tabs['files']['en'], $tabs['files']['fa']) }}</span></template>
                </p>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Hero and compact heads', 'سرصفحهٔ قهرمان و جمع‌وجور') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Confluence opens pages with the big display title and a quiet description; Jira lists squeeze the same frame into a single compact row.', 'کانفلوئنس صفحه را با تیتر نمایشی بزرگ و توضیح آرام باز می‌کند؛ جیرا همان قاب را در یک ردیف جمع‌وجور می‌فشارد.') }}
        </p>
    </div>
    <div class="atph-root" style="inline-size: 100%">
        <div class="atph-variants">
            <div class="atph-vgrid">
                <div class="atph-vcard">
                    <small>{{ $say('CONFLUENCE · HERO', 'کانفلوئنس · قهرمان') }}</small>
                    <h4 class="atph-hero-title">{{ $say('Design system guidelines', 'رهنمودهای سیستم طراحی') }}</h4>
                    <p class="atph-hero-desc">{{ $say('Everything the Nabu team agreed on tokens, spacing and voice — kept in one living page.', 'همهٔ قراردادهای تیم نابو دربارهٔ توکن، فاصله و لحن — در یک صفحهٔ زنده.') }}</p>
                    <div class="atph-row">
                        <button type="button" class="atph-btn" data-primary>{{ $say('Edit', 'ویرایش') }}</button>
                        <button type="button" class="atph-btn">{{ $say('Share', 'هم‌رسانی') }}</button>
                    </div>
                </div>
                <div class="atph-vcard">
                    <small>{{ $say('JIRA · COMPACT', 'جیرا · جمع‌وجور') }}</small>
                    <div class="atph-row" style="margin-block-start: .25rem">
                        <h4 class="atph-title" style="font-size: 1.05rem">{{ $say('Payment webhook', 'وب‌هوک پرداخت') }}</h4>
                        <span class="atph-mini" style="background: #DCFFF1; color: #1F845A; margin-inline-start: .5rem">{{ $say('Done', 'انجام شد') }}</span>
                        <div class="atph-actions">
                            <button type="button" class="atph-iconbtn" aria-label="{{ $say('Copy link', 'رونوشت لینک') }}"><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M10 14L20 4M14 4h6v6M20 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5"/></svg></button>
                            <button type="button" class="atph-btn">{{ $say('Open', 'باز کردن') }}</button>
                        </div>
                    </div>
                    <p class="atph-hero-desc" style="margin-block-start: .35rem">{{ $say('NABU-241 · updated 2h ago · assigned to you', 'نابو-۲۴۱ · به‌روزرسانی ۲ ساعت پیش · واگذارشده به شما') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
