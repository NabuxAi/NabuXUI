{{--
    Atlassian's empty state as the first visit to a project's Files tab: the
    friendly line illustration, one sentence and a single blue CTA that
    really seeds the list — and a reset that empties it again. Compact
    variants (no results, no access) sit below.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $files = [
        ['en' => 'User behaviour analysis.pdf', 'fa' => 'سند تحلیل رفتار کاربر.pdf', 'size' => ['2.4 MB', '۲٫۴ مگابایت'], 'when' => ['yesterday', 'دیروز'], 'tile' => '#B40000', 'd' => 'M7 3h7l5 5v13H7z M14 3v5h5'],
        ['en' => 'Atlassian palette.fig', 'fa' => 'طرح رنگ اطلسیان.fig', 'size' => ['812 KB', '۸۱۲ کیلوبایت'], 'when' => ['yesterday', 'دیروز'], 'tile' => '#6E5DC6', 'd' => 'M7 3h7l5 5v13H7z M14 3v5h5'],
        ['en' => 'sprint-24-metrics.csv', 'fa' => 'سنجه‌های اسپرینت-۲۴.csv', 'size' => ['44 KB', '۴۴ کیلوبایت'], 'when' => ['2 days ago', '۲ روز پیش'], 'tile' => '#1F845A', 'd' => 'M7 3h7l5 5v13H7z M14 3v5h5'],
    ];
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    .ates-root {
        --ates-blue: #0052CC; --ates-ink: #172B4D; --ates-ink-strong: #091E42; --ates-muted: #626F86;
        --ates-subtle: #44546F; --ates-border: #DFE1E6; --ates-hover: #F1F2F4; --ates-surface: #FFFFFF; --ates-page: #F7F8F9;
        --ates-art: #8590A2; --ates-art-soft: #DFE1E6;
        font-family: 'Inter', 'Vazirmatn', sans-serif; color: var(--ates-ink);
    }
    html[data-theme="dark"] .ates-root {
        --ates-blue: #388BFF; --ates-ink: #C7D1DB; --ates-ink-strong: #E4EAF0; --ates-muted: #8590A2;
        --ates-subtle: #A9B8C4; --ates-border: #2C3136; --ates-hover: #1D2125; --ates-surface: #161A1D; --ates-page: #101214;
        --ates-art: #738496; --ates-art-soft: #2C3136;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ates-root {
            --ates-blue: #388BFF; --ates-ink: #C7D1DB; --ates-ink-strong: #E4EAF0; --ates-muted: #8590A2;
            --ates-subtle: #A9B8C4; --ates-border: #2C3136; --ates-hover: #1D2125; --ates-surface: #161A1D; --ates-page: #101214;
            --ates-art: #738496; --ates-art-soft: #2C3136;
        }
    }
    .ates-root :focus-visible { outline: 2px solid var(--ates-blue); outline-offset: 2px; }

    .ates-stage { inline-size: min(100%, 34rem); margin-inline: auto; border: 1px solid var(--ates-border); border-radius: 6px;
                  background: var(--ates-surface); padding: 1rem; }
    .ates-crumb { font: 400 .74rem/1.4 Inter, Vazirmatn, system-ui; color: var(--ates-muted); padding: .25rem .5rem .75rem; }
    .ates-crumb b { color: var(--ates-subtle); font-weight: 600; }
    .ates-empty { display: grid; justify-items: center; gap: .55rem; padding: 1.5rem 1.25rem 2rem; text-align: center;
                  animation: ates-in .3s ease; }
    @keyframes ates-in { from { opacity: 0; translate: 0 6px; } to { opacity: 1; translate: 0 0; } }
    .ates-empty svg.art { inline-size: 9.5rem; block-size: auto; }
    .ates-empty h4 { margin: .35rem 0 0; font: 500 1.25rem/1.3 "Charlie Display", Inter, Vazirmatn, system-ui; color: var(--ates-ink-strong); }
    .ates-empty p { margin: 0; max-inline-size: 40ch; font: 400 .84rem/1.6 Inter, Vazirmatn, system-ui; color: var(--ates-muted); }
    .ates-cta { margin-block-start: .75rem; block-size: 2rem; padding-inline: .9rem; border: none; border-radius: 3px; cursor: pointer;
                background: var(--ates-blue); color: #fff; font: 500 .8rem/1 Inter, Vazirmatn, system-ui; transition: background .15s ease; }
    .ates-cta:hover { background: color-mix(in srgb, var(--ates-blue) 88%, black); }
    .ates-quiet { margin-block-start: .35rem; border: none; background: none; padding: .2rem .3rem; border-radius: 3px; cursor: pointer;
                  font: 500 .78rem Inter, Vazirmatn, system-ui; color: var(--ates-blue); }
    .ates-quiet:hover { text-decoration: underline; }

    .ates-list { display: grid; gap: .1rem; animation: ates-in .3s ease; }
    .ates-file { display: flex; align-items: center; gap: .65rem; padding: .55rem .5rem; border-radius: 4px; text-align: start;
                 border: none; background: none; inline-size: 100%; cursor: pointer; font: inherit; }
    .ates-file:hover { background: var(--ates-hover); }
    .ates-file i { flex: none; display: grid; place-items: center; inline-size: 1.9rem; aspect-ratio: 1; border-radius: 4px; color: #fff; font-style: normal; }
    .ates-file i svg { inline-size: 1rem; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .ates-file b { display: block; font: 500 .8rem/1.4 Inter, Vazirmatn, system-ui; color: var(--ates-ink-strong); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ates-file small { display: block; font: 400 .7rem/1.4 Inter, Vazirmatn, system-ui; color: var(--ates-muted); }
    .ates-file > span { min-inline-size: 0; flex: 1 1 auto; }
    .ates-meta { display: flex; justify-content: space-between; align-items: center; padding: .55rem .5rem .25rem;
                 font: 500 .74rem Inter, Vazirmatn, system-ui; color: var(--ates-muted); }
    .ates-meta button { border: none; background: none; padding: .2rem .3rem; border-radius: 3px; cursor: pointer; color: var(--ates-blue); font: 500 .74rem Inter, Vazirmatn, system-ui; }
    .ates-meta button:hover { text-decoration: underline; }

    .ates-variants { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 16rem), 1fr)); inline-size: 100%; max-inline-size: 40rem; }
    .ates-mini { border: 1px solid var(--ates-border); border-radius: 6px; background: var(--ates-surface); padding: 1.25rem 1rem;
                 display: grid; justify-items: center; gap: .45rem; text-align: center; }
    .ates-mini svg { inline-size: 4.6rem; }
    .ates-mini h5 { margin: 0; font: 500 .92rem/1.3 "Charlie Display", Inter, Vazirmatn, system-ui; color: var(--ates-ink-strong); }
    .ates-mini p { margin: 0; font: 400 .76rem/1.55 Inter, Vazirmatn, system-ui; color: var(--ates-muted); }
    .ates-mini small { font: 500 .7rem/1.4 Inter, Vazirmatn, system-ui; color: var(--ates-muted); }
    @media (prefers-reduced-motion: reduce) {
        .ates-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }

    /* Pin the page's «Important props» rows for full-page captures — ships
       with this partial only, so it stays scoped to this demo page. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="ates-root" x-data="{ filled: false }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The invitation, drawn in lines', 'دعوت‌نامه، خط‌خطی‌شده') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('An empty Files tab with its one CTA — press it and the list seeds itself with the first three uploads of the team; the quiet link below puts the emptiness back.', 'تب «فایل‌های» خالی با تنها CTAاش — دکمه را بزنید تا فهرست با سه بارگذاری اول تیم پر شود؛ لینک آرام پایین، خالی‌بودن را برمی‌گرداند.') }}
            </p>
        </div>

        <div class="ates-stage">
            <p class="ates-crumb">{{ $say('Nabu platform', 'نابو پلتفرم') }} &nbsp;/&nbsp; <b>{{ $say('Files', 'فایل‌ها') }}</b></p>

            <template x-if="!filled">
                <div class="ates-empty">
                    <svg class="art" viewBox="0 0 160 120" fill="none" aria-hidden="true">
                        <rect x="34" y="16" width="92" height="76" rx="8" stroke="var(--ates-art)" stroke-width="1.5" fill="var(--ates-surface)"/>
                        <path d="M46 34h30" stroke="var(--ates-art)" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="118" cy="34" r="10" stroke="#388BFF" stroke-width="1.5"/>
                        <path d="M113.5 34l3 3 5.5-6" stroke="#388BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="46" y="50" width="68" height="9" rx="4.5" fill="var(--ates-art-soft)"/>
                        <rect x="46" y="66" width="50" height="9" rx="4.5" fill="var(--ates-art-soft)"/>
                        <path d="M120 96l4-9 4 9-4-2.4z" stroke="var(--ates-art)" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M20 46l2.2-5 2.2 5 5 2.2-5 2.2-2.2 5-2.2-5-5-2.2z" fill="#388BFF" opacity=".85"/>
                        <path d="M140 66l1.6-3.6 1.6 3.6 3.6 1.6-3.6 1.6-1.6 3.6-1.6-3.6-3.6-1.6z" fill="#388BFF" opacity=".45"/>
                    </svg>
                    <h4>{{ $say('Nothing lives here yet', 'اینجا هنوز چیزی نیست') }}</h4>
                    <p>{{ $say('Files your team uploads to this project land here — specs, mockups, exports. The first one starts the pile.', 'فایل‌هایی که تیم به این پروژه می‌آورد همین‌جا می‌نشینند — سند، طرح، خروجی. اولی، تپه را شروع می‌کند.') }}</p>
                    <button type="button" class="ates-cta" x-on:click="filled = true">{{ $say('Upload the first file', 'بارگذاری اولین فایل') }}</button>
                    <button type="button" class="ates-quiet" x-on:click.prevent>{{ $say('What can be uploaded?', 'چه چیزی قابل بارگذاری است؟') }}</button>
                </div>
            </template>

            <template x-if="filled">
                <div class="ates-list">
                    <div class="ates-meta">
                        <span>{{ $fa ? '۳ فایل · ۳٫۲ مگابایت' : '3 files · 3.2 MB' }}</span>
                        <button type="button" x-on:click="filled = false">{{ $say('Back to empty', 'بازگشت به حالت خالی') }}</button>
                    </div>
                    @foreach ($files as $file)
                        <button type="button" class="ates-file">
                            <i style="background: {{ $file['tile'] }}" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="{{ $file['d'] }}"/></svg></i>
                            <span>
                                <b>{{ $say($file['en'], $file['fa']) }}</b>
                                <small>{{ $say($file['size'][0], $file['size'][1]) }} · {{ $say($file['when'][0], $file['when'][1]) }} · {{ $fa ? 'م. رضایی' : 'M. Rezaei' }}</small>
                            </span>
                        </button>
                    @endforeach
                </div>
            </template>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Compact cousins', 'پسرعموهای جمع‌وجور') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Inside panels the same recipe shrinks: smaller art, one line of copy and one link — no results and no access.', 'داخل پنل‌ها همین دستور کوچک می‌شود: تصویر کوچک‌تر، یک خط متن و یک لینک — بدون نتیجه و بدون دسترسی.') }}
        </p>
    </div>
    <div class="ates-root" style="inline-size: 100%">
        <div class="ates-variants">
            <div class="ates-mini">
                <svg viewBox="0 0 80 56" fill="none" aria-hidden="true">
                    <circle cx="34" cy="24" r="14" stroke="var(--ates-art)" stroke-width="1.5"/>
                    <path d="M45 35l12 12" stroke="var(--ates-art)" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M28 24h12M34 18v12" stroke="#388BFF" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <h5>{{ $say('No results for “growth”', 'نتیجه‌ای برای «رشد» نیست') }}</h5>
                <p>{{ $say('Try a shorter word, or clear two of the four filters.', 'واژهٔ کوتاه‌تری امتحان کنید، یا دو تا از چهار فیلتر را پاک کنید.') }}</p>
                <button type="button" class="ates-quiet" style="margin-block-start: .2rem" x-on:click.prevent>{{ $say('Clear all filters', 'پاک کردن همهٔ فیلترها') }}</button>
                <small>empty state · {{ $fa ? 'جست‌وجو' : 'search' }}</small>
            </div>
            <div class="ates-mini">
                <svg viewBox="0 0 80 56" fill="none" aria-hidden="true">
                    <rect x="22" y="20" width="36" height="26" rx="4" stroke="var(--ates-art)" stroke-width="1.5"/>
                    <path d="M28 20v-5a12 12 0 0 1 24 0v5" stroke="var(--ates-art)" stroke-width="1.5"/>
                    <circle cx="40" cy="32" r="4" stroke="#B40000" stroke-width="1.5"/>
                    <path d="M40 36v4" stroke="#B40000" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <h5>{{ $say('This project is private', 'این پروژه خصوصی است') }}</h5>
                <p>{{ $say('Ask its admin for access and the board appears right here.', 'از مدیرش دسترسی بخواهید تا تخته همین‌جا ظاهر شود.') }}</p>
                <button type="button" class="ates-quiet" style="margin-block-start: .2rem" x-on:click.prevent>{{ $say('Request access', 'درخواست دسترسی') }}</button>
                <small>empty state · {{ $fa ? 'دسترسی' : 'permissions' }}</small>
            </div>
        </div>
    </div>
</section>
