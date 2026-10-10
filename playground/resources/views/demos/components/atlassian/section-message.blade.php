{{--
    Atlassian's section message over a real settings panel — the API-token
    card of a fictional workspace. A severity switcher swaps the message with
    a fade, the message can be dismissed and restored, and the second box
    lays out all five official appearances as a variants row.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    // appearance => [icon name, en title, fa title, en body, fa body, en action, fa action]
    $messages = [
        'info' => [
            'info', 'Your token works with the v2 API', 'نشانهٔ شما با API نسخهٔ ۲ کار می‌کند',
            'Calls made with this token count against 600 requests per hour; the counter resets on the hour.',
            'تماس‌های این نشانه در سقف ۶۰۰ درخواست در ساعت می‌شمارند؛ شمارنده سرِ هر ساعت صفر می‌شود.',
            'Read the rate limits', 'سقف نرخ را بخوانید',
        ],
        'warning' => [
            'warning', 'This token expires in 7 days', 'این نشانه تا ۷ روز دیگر منقضی می‌شود',
            'Anything still using it will start failing on the 18th. Rotate it now and update your CI secrets.',
            'هرچه از آن استفاده می‌کند از تاریخ ۱۸ شروع به خطا می‌کند. همین حالا عوضش کنید و رمزهای CI را به‌روز کنید.',
            'Rotate the token', 'نشانه را عوض کنید',
        ],
        'error' => [
            'error', 'The last call was rejected', 'آخرین فراخوانی رد شد',
            'The webhook returned 401 at 14:32 — the token in production no longer matches this workspace.',
            'وب‌هوک ساعت ۱۴:۳۲ خطای ۴۰۱ داد — نشانهٔ محیط عملیاتی دیگر با این ورک‌اسپیس نمی‌خواند.',
            'See the failed calls', 'فراخوانی‌های ردشده را ببینید',
        ],
        'success' => [
            'success', 'Token rotated successfully', 'نشانه با موفقیت عوض شد',
            'The new secret was copied to your clipboard and the old one stays valid for the next 24 hours.',
            'رمز تازه در کلیپ‌بورد شما رونوشت شد و رمز قبلی تا ۲۴ ساعت دیگر هم اعتبار دارد.',
            'View audit log', 'گزارش حسابرسی',
        ],
    ];
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    .atsm-root {
        --atsm-blue: #0052CC; --atsm-ink: #172B4D; --atsm-ink-strong: #091E42; --atsm-muted: #626F86;
        --atsm-subtle: #44546F; --atsm-border: #DFE1E6; --atsm-hover: #F1F2F4; --atsm-surface: #FFFFFF;
        --atsm-info-bg: #E9F2FF; --atsm-info-ink: #0055CC;
        --atsm-warn-bg: #FFFAE6; --atsm-warn-ink: #A54800; --atsm-warn-solid: #B38600;
        --atsm-error-bg: #FFEDEB; --atsm-error-ink: #AE2A19; --atsm-error-solid: #B40000;
        --atsm-ok-bg: #DCFFF1; --atsm-ok-ink: #1F845A; --atsm-ok-solid: #1F845A;
        --atsm-disc-bg: #EAE6FF; --atsm-disc-ink: #5E4DB2; --atsm-disc-solid: #6E5DC6;
        font-family: 'Inter', 'Vazirmatn', sans-serif; color: var(--atsm-ink);
    }
    html[data-theme="dark"] .atsm-root {
        --atsm-blue: #388BFF; --atsm-ink: #C7D1DB; --atsm-ink-strong: #E4EAF0; --atsm-muted: #8590A2;
        --atsm-subtle: #A9B8C4; --atsm-border: #2C3136; --atsm-hover: #1D2125; --atsm-surface: #161A1D;
        --atsm-info-bg: #17263B; --atsm-info-ink: #579DFF;
        --atsm-warn-bg: #2E2A16; --atsm-warn-ink: #F5CD47; --atsm-warn-solid: #B38600;
        --atsm-error-bg: #3B2222; --atsm-error-ink: #F87168; --atsm-error-solid: #C9372C;
        --atsm-ok-bg: #1C322A; --atsm-ok-ink: #4BCE97; --atsm-ok-solid: #1F845A;
        --atsm-disc-bg: #2A2745; --atsm-disc-ink: #9F8FEF; --atsm-disc-solid: #6E5DC6;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atsm-root {
            --atsm-blue: #388BFF; --atsm-ink: #C7D1DB; --atsm-ink-strong: #E4EAF0; --atsm-muted: #8590A2;
            --atsm-subtle: #A9B8C4; --atsm-border: #2C3136; --atsm-hover: #1D2125; --atsm-surface: #161A1D;
            --atsm-info-bg: #17263B; --atsm-info-ink: #579DFF;
            --atsm-warn-bg: #2E2A16; --atsm-warn-ink: #F5CD47; --atsm-warn-solid: #B38600;
            --atsm-error-bg: #3B2222; --atsm-error-ink: #F87168; --atsm-error-solid: #C9372C;
            --atsm-ok-bg: #1C322A; --atsm-ok-ink: #4BCE97; --atsm-ok-solid: #1F845A;
            --atsm-disc-bg: #2A2745; --atsm-disc-ink: #9F8FEF; --atsm-disc-solid: #6E5DC6;
        }
    }
    .atsm-root :focus-visible { outline: 2px solid var(--atsm-blue); outline-offset: 2px; }

    .atsm-panel { inline-size: min(100%, 34rem); margin-inline: auto; border: 1px solid var(--atsm-border);
                  border-radius: 6px; background: var(--atsm-surface); padding: 1.25rem; display: grid; gap: 1rem; }
    .atsm-panel > h4 { margin: 0; font: 500 1.15rem/1.3 "Charlie Display", Inter, Vazirmatn, system-ui; color: var(--atsm-ink-strong); }
    .atsm-switch { display: flex; flex-wrap: wrap; gap: .4rem; }
    .atsm-seg { border: 1px solid var(--atsm-border); background: none; border-radius: 3px; block-size: 1.75rem;
                padding-inline: .65rem; font: 500 .76rem/1 Inter, Vazirmatn, system-ui; color: var(--atsm-subtle); cursor: pointer;
                transition: background .15s ease, color .15s ease, border-color .15s ease; }
    .atsm-seg:hover { background: var(--atsm-hover); }
    .atsm-seg[aria-pressed='true'] { background: var(--atsm-blue); border-color: var(--atsm-blue); color: #fff; }

    .atsm-msg { display: flex; gap: .75rem; padding: 1rem; border-radius: 3px; align-items: flex-start;
                background: var(--atsm-msg-bg); animation: atsm-in .25s ease; }
    @keyframes atsm-in { from { opacity: 0; translate: 0 4px; } to { opacity: 1; translate: 0 0; } }
    .atsm-msg[data-tone='info']     { --atsm-msg-bg: var(--atsm-info-bg);  --atsm-msg-ink: var(--atsm-info-ink); }
    .atsm-msg[data-tone='warning']  { --atsm-msg-bg: var(--atsm-warn-bg);  --atsm-msg-ink: var(--atsm-warn-ink); }
    .atsm-msg[data-tone='error']    { --atsm-msg-bg: var(--atsm-error-bg); --atsm-msg-ink: var(--atsm-error-ink); }
    .atsm-msg[data-tone='success']  { --atsm-msg-bg: var(--atsm-ok-bg);    --atsm-msg-ink: var(--atsm-ok-ink); }
    .atsm-msg[data-tone='discovery']{ --atsm-msg-bg: var(--atsm-disc-bg);  --atsm-msg-ink: var(--atsm-disc-ink); }
    .atsm-ic { flex: none; display: grid; place-items: center; inline-size: 1.5rem; aspect-ratio: 1; color: var(--atsm-msg-ink); }
    .atsm-ic svg { inline-size: 1.35rem; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .atsm-body { flex: 1 1 auto; min-inline-size: 0; }
    .atsm-body h5 { margin: 0 0 .2rem; font: 600 .85rem/1.4 Inter, Vazirmatn, system-ui; color: var(--atsm-ink-strong); }
    .atsm-body p { margin: 0 0 .5rem; font: 400 .8rem/1.6 Inter, Vazirmatn, system-ui; color: var(--atsm-ink); }
    .atsm-body a { color: var(--atsm-blue); font: 500 .8rem Inter, Vazirmatn, system-ui; text-decoration: none; }
    .atsm-body a:hover { text-decoration: underline; }
    .atsm-x { flex: none; display: grid; place-items: center; inline-size: 1.5rem; aspect-ratio: 1;
              margin-block-start: -.25rem; margin-inline-end: -.25rem;
              border: none; background: none; border-radius: 3px; cursor: pointer; color: var(--atsm-muted); }
    .atsm-x:hover { background: color-mix(in srgb, var(--atsm-msg-ink) 14%, transparent); color: var(--atsm-msg-ink); }
    .atsm-restore { justify-self: start; border: none; background: none; padding: .25rem 0; cursor: pointer;
                    color: var(--atsm-blue); font: 500 .78rem Inter, Vazirmatn, system-ui; }
    .atsm-restore:hover { text-decoration: underline; }

    .atsm-field label { display: block; margin-block-end: .3rem; font: 500 .78rem Inter, Vazirmatn, system-ui; color: var(--atsm-subtle); }
    .atsm-field code { display: block; inline-size: 100%; box-sizing: border-box; padding: .55rem .7rem; border: 1px solid var(--atsm-border);
                       border-radius: 4px; background: var(--atsm-hover); font: 500 .8rem/1.5 "JetBrains Mono", ui-monospace, monospace;
                       color: var(--atsm-ink-strong); overflow-x: auto; white-space: nowrap; }
    .atsm-row { display: flex; flex-wrap: wrap; gap: .5rem; }

    .atsm-family { display: grid; gap: .75rem; inline-size: 100%; max-inline-size: 36rem; justify-items: center; }
    .atsm-family .atsm-msg { inline-size: 100%; box-sizing: border-box; }
    .atsm-family .atsm-msg[data-tone] { background: var(--atsm-msg-bg); }
    @media (prefers-reduced-motion: reduce) {
        .atsm-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }

    /* Pin the page's «Important props» rows for full-page captures — ships
       with this partial only, so it stays scoped to this demo page. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="atsm-root"
    x-data="{
        tone: 'warning',
        killed: false,
        msgs: @js(array_map(static fn (array $m): array => ['title' => $say($m[1], $m[2]), 'body' => $say($m[3], $m[4]), 'action' => $say($m[5], $m[6])], $messages)),
        pick(t) { this.tone = t; this.killed = false; },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Warning without stopping the flow', 'هشدار، بدون قطع کردن جریان') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('The API-token panel of a workspace — pick a severity and the message swaps in place; dismiss it and bring it back.', 'پنل نشانهٔ API یک ورک‌اسپیس — شدت را انتخاب کنید تا پیام همان‌جا عوض شود؛ ببندیدش و برگردانیدش.') }}
            </p>
        </div>

        <div class="atsm-panel">
            <h4>{{ $say('API token', 'نشانهٔ API') }}</h4>
            <div class="atsm-switch" role="group" aria-label="{{ $say('Message severity', 'شدت پیام') }}">
                @foreach ([['info', 'Info', 'اطلاع'], ['warning', 'Warning', 'هشدار'], ['error', 'Error', 'خطا'], ['success', 'Success', 'موفق']] as [$tone, $en, $faT])
                    <button type="button" class="atsm-seg" x-on:click="pick('{{ $tone }}')" :aria-pressed="tone === '{{ $tone }}' ? 'true' : 'false'">{{ $say($en, $faT) }}</button>
                @endforeach
            </div>

            <template x-if="!killed">
                <section class="atsm-msg" :data-tone="tone" role="status">
                    <span class="atsm-ic" aria-hidden="true">
                        <template x-if="tone === 'info'"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8v.01"/></svg></template>
                        <template x-if="tone === 'warning'"><svg viewBox="0 0 24 24"><path d="M12 3L2.5 20h19z" stroke-linejoin="round"/><path d="M12 10v4M12 17.2v.01"/></svg></template>
                        <template x-if="tone === 'error'"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg></template>
                        <template x-if="tone === 'success'"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.4 2.4 4.6-5.3"/></svg></template>
                    </span>
                    <div class="atsm-body">
                        <h5 x-text="msgs[tone].title"></h5>
                        <p x-text="msgs[tone].body"></p>
                        <a href="#" x-on:click.prevent x-text="msgs[tone].action"></a>
                    </div>
                    <button type="button" class="atsm-x" x-on:click="killed = true" aria-label="{{ $say('Dismiss message', 'بستن پیام') }}">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                </section>
            </template>
            <button type="button" class="atsm-restore" x-show="killed" x-on:click="killed = false" x-cloak>{{ $say('Bring the message back', 'پیام را برگردان') }}</button>

            <div class="atsm-field">
                <label for="atsm-token">{{ $say('Personal access token', 'نشانهٔ دسترسی شخصی') }}</label>
                <code id="atsm-token">nabu_live_9f2c····················4e81</code>
            </div>
            <div class="atsm-row">
                <button type="button" class="atsm-seg" style="border: none; background: var(--atsm-blue); color: #fff">{{ $say('Copy', 'رونوشت') }}</button>
                <button type="button" class="atsm-seg">{{ $say('Revoke', 'باطل کردن') }}</button>
                <span style="margin-inline-start: auto; font: 400 .72rem/1.4 Inter, Vazirmatn, system-ui; color: var(--atsm-muted)">{{ $say('Last used today, 14:02', 'آخرین استفاده امروز ۱۴:۰۲') }}</span>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('All five appearances', 'هر پنج ظاهر رسمی') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Information, warning, error, success — and discovery, the purple one that points at brand-new features.', 'اطلاع، هشدار، خطا، موفق — و کشفِ بنفش که ویژگی‌های تازه را نشانه می‌گیرد.') }}
        </p>
    </div>
    <div class="atsm-root" style="inline-size: 100%">
        <div class="atsm-family">
            @foreach ($messages as $tone => $m)
                <section class="atsm-msg" data-tone="{{ $tone }}">
                    <span class="atsm-ic" aria-hidden="true">
                        @if ($tone === 'info')
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8v.01"/></svg>
                        @elseif ($tone === 'warning')
                            <svg viewBox="0 0 24 24"><path d="M12 3L2.5 20h19z" stroke-linejoin="round"/><path d="M12 10v4M12 17.2v.01"/></svg>
                        @elseif ($tone === 'error')
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                        @else
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.4 2.4 4.6-5.3"/></svg>
                        @endif
                    </span>
                    <div class="atsm-body">
                        <h5>{{ $say($m[1], $m[2]) }}</h5>
                        <p>{{ $say($m[3], $m[4]) }}</p>
                        <a href="#" x-on:click.prevent>{{ $say($m[5], $m[6]) }}</a>
                    </div>
                </section>
            @endforeach
            <section class="atsm-msg" data-tone="discovery">
                <span class="atsm-ic" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/><circle cx="12" cy="12" r="3.4"/></svg>
                </span>
                <div class="atsm-body">
                    <h5>{{ $say('Try the new board filters', 'فیلترهای تازهٔ تخته را امتحان کنید') }}</h5>
                    <p>{{ $say('Saved filters are now shared with the whole team — a first for this workspace.', 'فیلترهای ذخیره‌شده حالا با همهٔ تیم به‌اشتراک می‌افتند — اولین بار برای این ورک‌اسپیس.') }}</p>
                    <a href="#" x-on:click.prevent>{{ $say('Take a look', 'یکی ببینید') }}</a>
                </div>
            </section>
        </div>
    </div>
</section>
