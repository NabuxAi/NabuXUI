{{--
    Atlassian flags as Jira release feedback: publish a version and the
    success flag slides into the stage corner with a countdown hairline and a
    working Undo; cutting the connection raises the error flag whose retry
    turns the story around. The four appearances line up underneath.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    .atfl-root {
        --atfl-blue: #0052CC; --atfl-ink: #172B4D; --atfl-ink-strong: #091E42; --atfl-muted: #626F86;
        --atfl-subtle: #44546F; --atfl-border: #DFE1E6; --atfl-hover: #F1F2F4; --atfl-surface: #FFFFFF; --atfl-page: #F7F8F9;
        --atfl-info-solid: #0052CC; --atfl-ok-solid: #1F845A; --atfl-warn-solid: #B38600; --atfl-err-solid: #B40000;
        --atfl-err-ink: #AE2A19;
        font-family: 'Inter', 'Vazirmatn', sans-serif; color: var(--atfl-ink);
    }
    html[data-theme="dark"] .atfl-root {
        --atfl-blue: #388BFF; --atfl-ink: #C7D1DB; --atfl-ink-strong: #E4EAF0; --atfl-muted: #8590A2;
        --atfl-subtle: #A9B8C4; --atfl-border: #2C3136; --atfl-hover: #1D2125; --atfl-surface: #161A1D; --atfl-page: #101214;
        --atfl-info-solid: #1D7AFC; --atfl-ok-solid: #1F845A; --atfl-warn-solid: #B38600; --atfl-err-solid: #C9372C;
        --atfl-err-ink: #F87168;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atfl-root {
            --atfl-blue: #388BFF; --atfl-ink: #C7D1DB; --atfl-ink-strong: #E4EAF0; --atfl-muted: #8590A2;
            --atfl-subtle: #A9B8C4; --atfl-border: #2C3136; --atfl-hover: #1D2125; --atfl-surface: #161A1D; --atfl-page: #101214;
            --atfl-info-solid: #1D7AFC; --atfl-ok-solid: #1F845A; --atfl-warn-solid: #B38600; --atfl-err-solid: #C9372C;
            --atfl-err-ink: #F87168;
        }
    }
    .atfl-root :focus-visible { outline: 2px solid var(--atfl-blue); outline-offset: 2px; }

    .atfl-stage { position: relative; isolation: isolate; overflow: clip; inline-size: min(100%, 36rem); margin-inline: auto;
                  min-block-size: 26rem; border: 1px solid var(--atfl-border); border-radius: 6px;
                  background: var(--atfl-page); padding: 1.25rem; display: grid; gap: .9rem; align-content: start; }
    .atfl-issue { border: 1px solid var(--atfl-border); border-radius: 6px; background: var(--atfl-surface);
                  padding: 1rem 1.1rem; display: grid; gap: .75rem; }
    .atfl-issue h4 { margin: 0; font: 500 1.05rem/1.3 "Charlie Display", Inter, Vazirmatn, system-ui; color: var(--atfl-ink-strong); }
    .atfl-issue h4 small { font: 600 .72rem Inter, Vazirmatn, system-ui; color: var(--atfl-blue); margin-inline-end: .4rem; }
    .atfl-line { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; font: 400 .8rem/1.5 Inter, Vazirmatn, system-ui; color: var(--atfl-subtle); }
    .atfl-toggle { position: relative; inline-size: 34px; block-size: 18px; border-radius: 999px; background: var(--atfl-hover);
                   border: 1px solid var(--atfl-border); cursor: pointer; padding: 0; transition: background .2s ease, border-color .2s ease; }
    .atfl-toggle::after { content: ''; position: absolute; inset-block-start: 1px; inset-inline-start: 1px; inline-size: 14px; aspect-ratio: 1;
                          border-radius: 50%; background: var(--atfl-surface); border: 1px solid var(--atfl-border); box-sizing: border-box;
                          transition: translate .2s ease; }
    .atfl-toggle[aria-checked='true'] { background: var(--atfl-blue); border-color: var(--atfl-blue); }
    .atfl-toggle[aria-checked='true']::after { translate: 14px 0; }
    [dir='rtl'] .atfl-toggle[aria-checked='true']::after { translate: -14px 0; }
    .atfl-btns { display: flex; flex-wrap: wrap; gap: .5rem; }
    .atfl-btn { block-size: 2rem; padding-inline: .8rem; border: none; border-radius: 3px; cursor: pointer;
                font: 500 .8rem/1 Inter, Vazirmatn, system-ui; color: var(--atfl-ink-strong); background: var(--atfl-hover); transition: background .15s ease; }
    .atfl-btn:hover { background: color-mix(in srgb, var(--atfl-hover) 70%, var(--atfl-muted)); }
    .atfl-btn[data-primary] { background: var(--atfl-blue); color: #fff; }
    .atfl-btn[data-primary]:hover { background: color-mix(in srgb, var(--atfl-blue) 88%, black); }

    .atfl-toasts { position: absolute; inset-inline: 1rem; inset-block-end: 1rem; z-index: 3; display: grid; gap: .6rem; pointer-events: none; }
    .atfl { pointer-events: auto; position: relative; overflow: clip; display: flex; gap: .75rem; inline-size: min(100%, 24rem); justify-self: end;
            padding: .8rem .9rem .9rem; border-radius: 4px; background: var(--atfl-surface); text-align: start;
            box-shadow: 0 8px 12px rgba(9, 30, 66, .16), 0 0 1px rgba(9, 30, 66, .25);
            animation: atfl-in .3s cubic-bezier(.2, 0, 0, 1); }
    @keyframes atfl-in { from { opacity: 0; translate: 0 12px; } to { opacity: 1; translate: 0 0; } }
    .atfl-ic { flex: none; display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1; border-radius: 2px; margin-block-start: .1rem; color: #fff; }
    .atfl-ic svg { inline-size: .85rem; fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; stroke-linejoin: round; }
    .atfl[data-tone='info'] .atfl-ic { background: var(--atfl-info-solid); }
    .atfl[data-tone='success'] .atfl-ic { background: var(--atfl-ok-solid); }
    .atfl[data-tone='warning'] .atfl-ic { background: var(--atfl-warn-solid); }
    .atfl[data-tone='error'] .atfl-ic { background: var(--atfl-err-solid); }
    .atfl-body { flex: 1 1 auto; min-inline-size: 0; }
    .atfl-body h5 { margin: 0; font: 600 .82rem/1.4 Inter, Vazirmatn, system-ui; color: var(--atfl-ink-strong); }
    .atfl-body p { margin: .15rem 0 .35rem; font: 400 .76rem/1.55 Inter, Vazirmatn, system-ui; color: var(--atfl-subtle); }
    .atfl-body button { border: none; background: none; padding: 0; cursor: pointer; font: 600 .76rem Inter, Vazirmatn, system-ui; color: var(--atfl-blue); }
    .atfl-body button:hover { text-decoration: underline; }
    .atfl[data-tone='error'] .atfl-body button { color: var(--atfl-err-ink); }
    .atfl-x { flex: none; display: grid; place-items: center; inline-size: 1.4rem; aspect-ratio: 1;
              margin-block-start: -.2rem; margin-inline-end: -.2rem;
              border: none; background: none; border-radius: 3px; cursor: pointer; color: var(--atfl-muted); }
    .atfl-x:hover { background: var(--atfl-hover); color: var(--atfl-ink-strong); }
    .atfl-timer { position: absolute; inset-inline: 0; inset-block-end: 0; block-size: 3px; background: var(--atfl-blue);
                  transform-origin: left; animation: atfl-count 6s linear forwards; }
    [dir='rtl'] .atfl-timer { transform-origin: right; }
    .atfl[data-tone='error'] .atfl-timer { display: none; }
    @keyframes atfl-count { from { scale: 1 1; } to { scale: 0 1; } }

    .atfl-family { display: grid; gap: .8rem 1.25rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); inline-size: 100%; max-inline-size: 40rem; }
    .atfl-family .atfl { inline-size: 100%; justify-self: stretch; }
    .atfl-cell { display: grid; gap: .3rem; }
    .atfl-cell > small { font: 500 .7rem/1.4 Inter, Vazirmatn, system-ui; color: var(--atfl-muted); letter-spacing: .3px; }
    @media (prefers-reduced-motion: reduce) {
        .atfl-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }

    /* Pin the page's «Important props» rows for full-page captures — ships
       with this partial only, so it stays scoped to this demo page. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="atfl-root"
    x-data="{
        on: false,
        prevOn: false,
        flag: null,
        timer: null,
        texts: {
            publish: { title: @js($say('Version 2.4 shipped', 'نسخهٔ ۲٫۴ منتشر شد')), body: @js($say('Live for every teammate on the web and mobile apps.', 'برای همهٔ همکاران در وب و اپ موبایل فعال شد.')), action: @js($say('Undo', 'واگرد')) },
            fail:    { title: @js($say('Release failed — you are offline', 'انتشار ناموفق — اتصال قطع است')), body: @js($say('The build is safe; we will not half-publish it.', 'بیلد سالم است؛ نیمه‌منتشرش نمی‌کنیم.')), action: @js($say('Retry', 'تلاش دوباره')) },
            fixed:   { title: @js($say('Connection restored, 2.4 shipped', 'اتصال برگشت، ۲٫۴ منتشر شد')), body: @js($say('It took one retry — the queue did the rest.', 'فقط یک تلاش دوباره برد — صف، بقیه‌اش را انجام داد.')), action: @js($say('Undo', 'واگرد')) },
        },
        show(kind) {
            clearTimeout(this.timer);
            this.flag = kind;
            if (kind !== 'fail') this.timer = setTimeout(() => { this.flag = null }, 6000);
        },
        publish() { this.prevOn = this.on; this.on = true; this.show('publish'); },
        fail() { this.prevOn = this.on; this.on = true; this.show('fail'); },
        undo() { this.on = this.prevOn; this.flag = null; },
        retry() { this.show('fixed'); },
    }"
    x-on:keydown.escape.window="flag = null">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The toast Jira taught the world', 'توستی که جیرا به دنیا یاد داد') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('Publish the release for a success flag with a six-second countdown and a working Undo — or cut the connection and meet the error flag that waits.', 'نسخه را منتشر کنید تا فلگ موفق با شمارش ۶ ثانیه‌ای و واگردِ واقعی بیاید — یا اتصال را قطع کنید تا فلگ خطا را ببینید که منتظر می‌ماند.') }}
            </p>
        </div>

        <div class="atfl-stage">
            <div class="atfl-issue">
                <h4><small>NABU-241</small>{{ $say('Payment webhook', 'وب‌هوک پرداخت') }}</h4>
                <div class="atfl-line">
                    <button type="button" class="atfl-toggle" role="switch" :aria-checked="on ? 'true' : 'false'" x-on:click="on = !on"
                            aria-label="{{ $say('Ready to ship', 'آمادهٔ انتشار') }}"></button>
                    <span>{{ $say('Ready to ship', 'آمادهٔ انتشار') }}</span>
                    <span aria-hidden="true">·</span>
                    <span>{{ $fa ? '۲ واکنش' : '2 reactions' }}</span>
                </div>
                <div class="atfl-btns">
                    <button type="button" class="atfl-btn" data-primary x-on:click="publish()">{{ $say('Release 2.4', 'انتشار نسخهٔ ۲٫۴') }}</button>
                    <button type="button" class="atfl-btn" x-on:click="fail()">{{ $say('Release (offline)', 'انتشار (آفلاین)') }}</button>
                </div>
            </div>
            <p style="margin: 0; font: 400 .75rem/1.6 Inter, Vazirmatn, system-ui; color: var(--nx-text-muted)">
                {{ $say('The flag lands in the stage corner — inside the app frame, never over the whole page. Escape dismisses.', 'فلگ در گوشهٔ قاب می‌نشیند — داخل قاب اپ، نه روی کل صفحه. با Escape بسته می‌شود.') }}
            </p>

            <div class="atfl-toasts" aria-live="polite">
                <template x-if="flag === 'publish' || flag === 'fixed'">
                    <div class="atfl" data-tone="success" role="status">
                        <span class="atfl-ic" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg></span>
                        <div class="atfl-body">
                            <h5 x-text="texts[flag].title"></h5>
                            <p x-text="texts[flag].body"></p>
                            <button type="button" x-on:click="undo()" x-text="texts[flag].action"></button>
                        </div>
                        <button type="button" class="atfl-x" x-on:click="flag = null" aria-label="{{ $say('Dismiss flag', 'بستن فلگ') }}">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                        <span class="atfl-timer" aria-hidden="true"></span>
                    </div>
                </template>
                <template x-if="flag === 'fail'">
                    <div class="atfl" data-tone="error" role="alert">
                        <span class="atfl-ic" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg></span>
                        <div class="atfl-body">
                            <h5 x-text="texts.fail.title"></h5>
                            <p x-text="texts.fail.body"></p>
                            <button type="button" x-on:click="retry()" x-text="texts.fail.action"></button>
                        </div>
                        <button type="button" class="atfl-x" x-on:click="flag = null" aria-label="{{ $say('Dismiss flag', 'بستن فلگ') }}">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The four appearances', 'چهار ظاهر رسمی') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The square icon carries the severity: blue informs, green celebrates, yellow cautions, red stops you.', 'آیکن مربعی خودِ شدت است: آبی خبر می‌دهد، سبز جشن می‌گیرد، زرد هشدار می‌دهد و قرمز جلوی شما را می‌گیرد.') }}
        </p>
    </div>
    <div class="atfl-root" style="inline-size: 100%">
        <div class="atfl-family">
            @foreach ([['info', 'Information', 'اطلاع', 'Two teammates joined the project today.', 'امروز دو همکار به پروژه پیوستند.'], ['success', 'Success', 'موفق', 'Sprint 24 closed with 41 of 44 items done.', 'اسپرینت ۲۴ با ۴۱ کار از ۴۴ بسته شد.'], ['warning', 'Warning', 'هشدار', 'Capacity for next sprint is over by 12 hours.', 'ظرفیت اسپرینت بعد ۱۲ ساعت بیشتر از حد است.'], ['error', 'Error', 'خطا', 'Two builds failed on the release branch.', 'دو بیلد روی شاخهٔ انتشار شکست خورد.']] as [$tone, $en, $faT, $enBody, $faBody])
                <div class="atfl-cell">
                    <div class="atfl" data-tone="{{ $tone }}">
                        <span class="atfl-ic" aria-hidden="true">
                            @if ($tone === 'info')
                                <svg viewBox="0 0 24 24"><path d="M12 11v5M12 8v.01"/></svg>
                            @elseif ($tone === 'success')
                                <svg viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            @elseif ($tone === 'warning')
                                <svg viewBox="0 0 24 24"><path d="M12 8v5M12 16.2v.01"/></svg>
                            @else
                                <svg viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                            @endif
                        </span>
                        <div class="atfl-body">
                            <h5>{{ $say($en, $faT) }}</h5>
                            <p>{{ $say($enBody, $faBody) }}</p>
                        </div>
                    </div>
                    <small>{{ $tone }}</small>
                </div>
            @endforeach
        </div>
    </div>
</section>
