{{--
    The floating pill navbar on a simulated marketing page: the stage scrolls
    for real, the indicator pill springs across the links as they are clicked,
    and past 24px of scroll the pill condenses — tighter padding, deeper
    shadow, the wordmark folding away. A specimen row compares the two states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap');
    .fpn-root {
        --fpn-ink: #0f172a; --fpn-muted: #64748b; --fpn-surface: #ffffff;
        --fpn-border: #e2e8f0; --fpn-accent: #4f46e5; --fpn-soft: #eef2ff;
        --fpn-page: #f8fafc; --fpn-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        font-family: var(--fpn-font);
        color: var(--fpn-ink);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .fpn-root {
        --fpn-ink: #e2e8f0; --fpn-muted: #94a3b8; --fpn-surface: #101a30;
        --fpn-border: #24324f; --fpn-accent: #818cf8; --fpn-soft: #1c2748;
        --fpn-page: #0b1120;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .fpn-root {
            --fpn-ink: #e2e8f0; --fpn-muted: #94a3b8; --fpn-surface: #101a30;
            --fpn-border: #24324f; --fpn-accent: #818cf8; --fpn-soft: #1c2748;
            --fpn-page: #0b1120;
        }
    }
    .fpn-root, .fpn-root *, .fpn-root *::before, .fpn-root *::after { box-sizing: border-box; }
    .fpn-root :focus-visible { outline: 2px solid var(--fpn-accent); outline-offset: 2px; }

    /* ——— the stage: a simulated page with real inner scrolling ——— */
    .fpn-stage { position: relative; inline-size: min(100%, 46rem); margin-inline: auto; block-size: 30rem;
                 overflow-y: auto; border: 1px solid var(--fpn-border); border-radius: 1rem;
                 background: var(--fpn-page);
                 background-image: radial-gradient(60rem 18rem at 50% -6rem, color-mix(in srgb, var(--fpn-accent) 14%, transparent), transparent 70%);
                 scrollbar-width: thin; }

    /* ——— the pill ——— */
    .fpn-nav { position: sticky; inset-block-start: .75rem; z-index: 5; display: flex; align-items: center;
               gap: .375rem; margin-inline: auto; inline-size: fit-content; max-inline-size: calc(100% - 1.5rem);
               padding: .5rem .5rem .5rem .875rem; border-radius: 999px; border: 1px solid var(--fpn-border);
               background: color-mix(in srgb, var(--fpn-surface) 88%, transparent);
               backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(15, 23, 42, .12);
               transition: padding .25s ease, box-shadow .25s ease, background-color .25s ease; }
    .fpn-nav[data-shrunk='true'] { padding-block: .25rem; padding-inline: .375rem .375rem; gap: .25rem;
               background: color-mix(in srgb, var(--fpn-surface) 96%, transparent);
               box-shadow: 0 6px 16px rgba(15, 23, 42, .22); }
    .fpn-brand { display: flex; align-items: center; gap: .45rem; font-weight: 700; font-size: .9rem; }
    .fpn-brand i { flex: none; inline-size: 1.2rem; block-size: 1.2rem; border-radius: 50%;
                   background: conic-gradient(from 210deg, #6366f1, #d946ef, #06b6d4, #6366f1); }
    .fpn-word { max-inline-size: 6rem; white-space: nowrap; overflow: clip; transition: max-inline-size .3s ease, opacity .3s ease; }
    .fpn-nav[data-shrunk='true'] .fpn-word { max-inline-size: 0; opacity: 0; }

    .fpn-links { position: relative; display: flex; gap: .125rem; }
    /* Physical left on purpose: it pairs with the JS offsetLeft measurement,
       so it lands correctly in both directions. */
    .fpn-ind { position: absolute; inset-block: .1875rem; left: var(--fpn-x, 0); inline-size: var(--fpn-w, 0);
               border-radius: 999px; background: var(--fpn-soft);
               transition: left .26s cubic-bezier(.2, 0, 0, 1), inline-size .26s cubic-bezier(.2, 0, 0, 1); }
    .fpn-links button { position: relative; padding: .4rem .8rem; border: 0; background: none; color: var(--fpn-muted);
                        font: 500 .82rem/1 var(--fpn-font); cursor: pointer; white-space: nowrap;
                        transition: padding .25s ease, color .15s ease; }
    .fpn-links button:hover { color: var(--fpn-ink); }
    .fpn-links button[aria-current='page'] { color: var(--fpn-ink); font-weight: 600; }
    .fpn-nav[data-shrunk='true'] .fpn-links button { padding-block: .3rem; font-size: .76rem; }
    .fpn-cta { display: inline-flex; align-items: center; gap: .35rem; padding: .5rem 1rem; border-radius: 999px;
               background: var(--fpn-accent); color: #fff; font: 600 .8rem/1 var(--fpn-font); text-decoration: none;
               white-space: nowrap; transition: padding .25s ease, filter .15s ease; }
    .fpn-cta:hover { filter: brightness(1.08); }
    .fpn-cta .nx-icon { inline-size: .95em; block-size: .95em; }
    .fpn-nav[data-shrunk='true'] .fpn-cta { padding: .38rem .8rem; font-size: .76rem; }

    /* ——— the fake page under the pill ——— */
    .fpn-hero { padding: 8.5rem 1.5rem 3rem; text-align: center; }
    .fpn-hero h4 { margin: 0 0 .5rem; font-size: clamp(1.3rem, 3.4vw, 1.9rem); font-weight: 800; letter-spacing: -.02em; }
    .fpn-hero p { margin: 0 auto; max-inline-size: 44ch; color: var(--fpn-muted); font-size: .92rem; }
    .fpn-stats { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem 1.75rem; margin-block: 1.25rem; }
    .fpn-stats div { display: grid; gap: .15rem; }
    .fpn-stats b { font-size: 1.05rem; font-weight: 700; }
    .fpn-stats span { color: var(--fpn-muted); font-size: .74rem; }
    .fpn-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(13rem, 100%), 1fr));
                gap: .75rem; padding: 0 1.25rem 1.25rem; }
    .fpn-card { display: grid; gap: .35rem; padding: 1rem; border: 1px solid var(--fpn-border); border-radius: .85rem;
                background: var(--fpn-surface); text-align: start; }
    .fpn-card b { display: flex; align-items: center; gap: .45rem; font-size: .88rem; }
    .fpn-card .nx-icon { inline-size: 1.05rem; block-size: 1.05rem; color: var(--fpn-accent); }
    .fpn-card span { color: var(--fpn-muted); font-size: .78rem; line-height: 1.6; }
    .fpn-foot { padding: 1rem 1.5rem 2.25rem; text-align: center; color: var(--fpn-muted); font-size: .74rem; }

    /* ——— specimen row: the two states side by side ——— */
    .fpn-spec { display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; align-items: flex-start; }
    .fpn-spec-cell { display: grid; gap: .5rem; justify-items: center; }
    .fpn-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .fpn-spec .fpn-nav { position: static; }
    .fpn-spec .fpn-nav[data-shrunk='true'] { box-shadow: 0 6px 16px rgba(15, 23, 42, .22); }
    :where(.nx-js) .pg:has(.fpn-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .fpn-links button:not([aria-current='page']) { display: none; }
        .fpn-stage { block-size: 26rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .fpn-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<div class="fpn-root"
    x-data="{
        tab: 'product',
        shrunk: false,
        place(el) {
            this.$refs.links.style.setProperty('--fpn-x', el.offsetLeft + 'px');
            this.$refs.links.style.setProperty('--fpn-w', el.offsetWidth + 'px');
        },
    }">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A pill that floats, slides and folds', 'قرصی که شنا می‌کند، می‌لغزد و جمع می‌شود') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('Scroll the stage: past 24px the pill condenses — tighter padding, deeper shadow, the wordmark folding away — while the indicator keeps springing across the links. Sticky, never fixed.', 'استیج را اسکرول کنید: بعد از ۲۴ پیکسل قرص جمع می‌شود — پدینگ تنگ‌تر، سایهٔ عمیق‌تر، وردمارک تاشو — و نشانگر همچنان با فنر بین لینک‌ها می‌لغزد. چسبنده، نه ثابت.') }}
            </p>
        </div>

        <div class="fpn-stage"
            x-on:scroll.passive="shrunk = $event.target.scrollTop > 24"
            x-on:resize.window="place($refs.links.querySelector('[aria-current]'))">
            <nav class="fpn-nav" x-bind:data-shrunk="shrunk ? 'true' : 'false'" aria-label="{{ $say('Primary', 'اصلی') }}">
                <b class="fpn-brand"><i aria-hidden="true"></i><span class="fpn-word">{{ $say('Meridian', 'مریدین') }}</span></b>
                <div class="fpn-links" x-ref="links"
                    x-init="$nextTick(() => place($refs.links.querySelector('[aria-current]')))">
                    <span class="fpn-ind" aria-hidden="true"></span>
                    <button type="button" x-bind:aria-current="tab === 'product' ? 'page' : null"
                        x-on:click="tab = 'product'; place($el)">{{ $say('Product', 'محصول') }}</button>
                    <button type="button" x-bind:aria-current="tab === 'solutions' ? 'page' : null"
                        x-on:click="tab = 'solutions'; place($el)">{{ $say('Solutions', 'راه‌حل‌ها') }}</button>
                    <button type="button" x-bind:aria-current="tab === 'pricing' ? 'page' : null"
                        x-on:click="tab = 'pricing'; place($el)">{{ $say('Pricing', 'قیمت') }}</button>
                    <button type="button" x-bind:aria-current="tab === 'docs' ? 'page' : null"
                        x-on:click="tab = 'docs'; place($el)">{{ $say('Docs', 'مستندات') }}</button>
                </div>
                <a class="fpn-cta" href="#start">{{ $say('Start free', 'شروع رایگان') }}<x-nx::icon name="arrow-right" /></a>
            </nav>

            <header class="fpn-hero">
                <h4>{{ $say('Release intelligence for global product teams', 'هوش انتشار برای تیم‌های محصول جهانی') }}</h4>
                <p>{{ $say('Traces, releases and alerts in one workspace — from the first commit in Frankfurt to the last rollout in Singapore.', 'ردیابی، انتشار و هشدارها در یک ورک‌اسپیس — از اولین کامیت در فرانکفورت تا آخرین استقرار در سنگاپور.') }}</p>
                <div class="fpn-stats">
                    <div><b>{{ $num('99.98') }}%</b><span>{{ $say('API uptime', 'پایداری API') }}</span></div>
                    <div><b>{{ $num('214') }}ms</b><span>{{ $say('p95 worldwide', 'p95 جهانی') }}</span></div>
                    <div><b>{{ $num('12') }}</b><span>{{ $say('regions live', 'ناحیهٔ فعال') }}</span></div>
                </div>
            </header>

            <div class="fpn-grid">
                <div class="fpn-card"><b><x-nx::icon name="zap" />{{ $say('Real-time traces', 'ردیابی زنده') }}</b><span>{{ $say('Every request across Dublin, Toronto and Osaka — sampled, yet complete.', 'هر درخواست در دوبلین، تورنتو و اوساکا — نمونه‌گیری‌شده اما کامل.') }}</span></div>
                <div class="fpn-card"><b><x-nx::icon name="trend-up" />{{ $say('Release analytics', 'تحلیل انتشار') }}</b><span>{{ $say('Regression alerts land before the rollout reaches the last region.', 'هشدار رگرسیون قبل از رسیدن استقرار به آخرین ناحیه می‌رسد.') }}</span></div>
                <div class="fpn-card"><b><x-nx::icon name="bell" />{{ $say('Alert routing', 'مسیریابی هشدار') }}</b><span>{{ $say('Follow-the-sun on-call for Marco, Priya and the whole rotation.', 'آن‌کالِ خورشیدگرد برای مارکو، پریا و کل چرخه.') }}</span></div>
            </div>
            <p class="fpn-foot">{{ $say('Meridian — SOC 2 Type II · GDPR · deployed in 12 regions', 'مریدین — SOC 2 نوع ۲ · GDPR · مستقر در ۱۲ ناحیه') }}</p>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Resting versus condensed', 'حالت آرام در برابر جمع‌شده') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('The same pill at both ends of the scroll — hover the condensed one and it still works at full size.', 'همان قرص در دو سر اسکرول — روی حالت جمع‌شده هاور کنید؛ همچنان با اندازهٔ کامل کار می‌کند.') }}
            </p>
        </div>
        <div class="fpn-spec">
            <div class="fpn-spec-cell">
                <span class="fpn-nav" aria-hidden="true">
                    <b class="fpn-brand"><i></i><span class="fpn-word">{{ $say('Meridian', 'مریدین') }}</span></b>
                    <span class="fpn-links"><span class="fpn-ind"></span><button type="button" aria-current="page" tabindex="-1" style="color: var(--fpn-ink); font-weight: 600">{{ $say('Product', 'محصول') }}</button></span>
                    <span class="fpn-cta" style="cursor: default">{{ $say('Start free', 'شروع رایگان') }}</span>
                </span>
                <small>resting · scrollTop = {{ $num('0') }}</small>
            </div>
            <div class="fpn-spec-cell">
                <span class="fpn-nav" data-shrunk="true" aria-hidden="true">
                    <b class="fpn-brand"><i></i><span class="fpn-word">{{ $say('Meridian', 'مریدین') }}</span></b>
                    <span class="fpn-links"><span class="fpn-ind"></span><button type="button" aria-current="page" tabindex="-1" style="color: var(--fpn-ink); font-weight: 600">{{ $say('Product', 'محصول') }}</button></span>
                    <span class="fpn-cta" style="cursor: default">{{ $say('Start free', 'شروع رایگان') }}</span>
                </span>
                <small>condensed · scrollTop &gt; {{ $num('24') }}px</small>
            </div>
        </div>
    </section>
</div>
