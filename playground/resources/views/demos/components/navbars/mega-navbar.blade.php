{{--
    The mega menu navbar on a simulated product page: the panel swings open on
    hover with a 140ms close-grace (so sweeping across the bar-panel gap never
    dismisses it), click toggles it, ArrowDown walks the keyboard in, Escape
    snaps it shut — over two link columns with icon rows and a gradient promo.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap');
    .mgn-root {
        --mgn-ink: #0f172a; --mgn-muted: #64748b; --mgn-surface: #ffffff;
        --mgn-border: #e2e8f0; --mgn-accent: #4f46e5; --mgn-soft: #eef2ff; --mgn-page: #f8fafc;
        --mgn-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        font-family: var(--mgn-font);
        color: var(--mgn-ink);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .mgn-root {
        --mgn-ink: #e2e8f0; --mgn-muted: #94a3b8; --mgn-surface: #101a30;
        --mgn-border: #24324f; --mgn-accent: #818cf8; --mgn-soft: #1c2748; --mgn-page: #0b1120;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mgn-root {
            --mgn-ink: #e2e8f0; --mgn-muted: #94a3b8; --mgn-surface: #101a30;
            --mgn-border: #24324f; --mgn-accent: #818cf8; --mgn-soft: #1c2748; --mgn-page: #0b1120;
        }
    }
    .mgn-root, .mgn-root *, .mgn-root *::before, .mgn-root *::after { box-sizing: border-box; }
    .mgn-root :focus-visible { outline: 2px solid var(--mgn-accent); outline-offset: 2px; }
    [x-cloak] { display: none !important; }

    /* ——— the stage: a simulated page, the bar floats at its top ——— */
    .mgn-stage { position: relative; inline-size: min(100%, 48rem); margin-inline: auto; min-block-size: 26.25rem;
                 overflow: clip; border: 1px solid var(--mgn-border); border-radius: 1rem; background: var(--mgn-page);
                 background-image: radial-gradient(color-mix(in srgb, var(--mgn-ink) 8%, transparent) 1px, transparent 1px);
                 background-size: 1.4rem 1.4rem; }

    .mgn-head { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 10;
                padding: .75rem 1.25rem 0; }
    .mgn-bar { display: flex; align-items: center; gap: .75rem 1.25rem; flex-wrap: wrap;
               padding: .6rem .8rem; border-radius: 999px; border: 1px solid var(--mgn-border);
               background: color-mix(in srgb, var(--mgn-surface) 90%, transparent); backdrop-filter: blur(10px);
               box-shadow: 0 8px 24px rgba(15, 23, 42, .08); }
    .mgn-brand { display: inline-flex; align-items: center; gap: .45rem; font-weight: 700; font-size: .95rem; }
    .mgn-brand i { inline-size: 1.15rem; block-size: 1.15rem; border-radius: .35rem;
                   background: conic-gradient(from 210deg, #6366f1, #d946ef, #06b6d4, #6366f1); }
    .mgn-bar nav { display: flex; align-items: center; gap: .125rem; flex-wrap: wrap; }
    .mgn-trigger { display: inline-flex; align-items: center; gap: .35rem; padding: .5rem .8rem; border: 0;
                   border-radius: 999px; background: none; color: var(--mgn-ink); font: 500 .85rem/1 var(--mgn-font);
                   cursor: pointer; transition: background-color .15s ease; }
    .mgn-trigger:hover { background: var(--mgn-soft); }
    .mgn-trigger[aria-expanded='true'] { background: var(--mgn-soft); font-weight: 600; }
    .mgn-trigger .nx-icon { inline-size: .9em; block-size: .9em; transition: rotate .2s ease; }
    .mgn-trigger[aria-expanded='true'] .nx-icon { rotate: 180deg; }
    .mgn-bar nav > a { padding: .5rem .8rem; border-radius: 999px; color: var(--mgn-muted);
                       font: 500 .85rem/1 var(--mgn-font); text-decoration: none; }
    .mgn-bar nav > a:hover { color: var(--mgn-ink); background: var(--mgn-soft); }
    .mgn-end { margin-inline-start: auto; display: inline-flex; align-items: center; gap: .6rem; }
    .mgn-signin { color: var(--mgn-muted); font: 500 .82rem/1 var(--mgn-font); text-decoration: none; }
    .mgn-signin:hover { color: var(--mgn-ink); }
    .mgn-cta { display: inline-flex; align-items: center; gap: .35rem; padding: .55rem 1.05rem; border-radius: 999px;
               background: var(--mgn-accent); color: #fff; font: 600 .8rem/1 var(--mgn-font); text-decoration: none; }
    .mgn-cta:hover { filter: brightness(1.08); }
    .mgn-cta .nx-icon { inline-size: .9em; block-size: .9em; }

    /* ——— the mega panel ——— */
    .mgn-panel { position: absolute; inset-block-start: calc(100% + .5rem); inset-inline: 1.25rem; z-index: 9;
                 display: grid; grid-template-columns: minmax(0, 2.1fr) minmax(12.5rem, 1fr); gap: 1.25rem;
                 padding: 1.25rem; border: 1px solid var(--mgn-border); border-radius: 1rem;
                 background: var(--mgn-surface); box-shadow: 0 24px 60px rgba(15, 23, 42, .18);
                 animation: mgn-pop .18s ease-out; }
    @keyframes mgn-pop { from { opacity: 0; translate: 0 -6px; } to { opacity: 1; translate: 0 0; } }
    .mgn-cols { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem 1.5rem; }
    .mgn-cols section { display: grid; gap: .25rem; align-content: start; }
    .mgn-cols h5 { margin: 0 0 .25rem; font-size: .68rem; font-weight: 600; letter-spacing: .09em;
                   text-transform: uppercase; color: var(--mgn-muted); }
    .mgn-item { display: grid; grid-template-columns: auto minmax(0, 1fr); column-gap: .6rem; align-items: center;
                padding: .5rem .6rem; border-radius: .6rem; color: var(--mgn-ink); text-decoration: none;
                transition: background-color .13s ease; }
    .mgn-item:hover { background: var(--mgn-soft); }
    .mgn-item i { grid-row: span 2; display: grid; place-items: center; inline-size: 2rem; block-size: 2rem;
                  border-radius: .55rem; background: var(--mgn-soft); color: var(--mgn-accent); }
    .mgn-item:hover i { background: color-mix(in srgb, var(--mgn-accent) 16%, transparent); }
    .mgn-item i .nx-icon { inline-size: 1.05rem; block-size: 1.05rem; }
    .mgn-item b { font-size: .85rem; font-weight: 600; }
    .mgn-item span { grid-column: 2; color: var(--mgn-muted); font-size: .74rem; }

    .mgn-promo { position: relative; display: grid; gap: .4rem; align-content: start; padding: 1.1rem;
                 border-radius: .9rem; overflow: clip; color: #fff;
                 background: linear-gradient(160deg, #4f46e5 0%, #9333ea 55%, #d946ef 100%); }
    .mgn-promo::after { content: ''; position: absolute; inset-block-start: -40%; inset-inline-end: -30%;
                         block-size: 10rem; inline-size: 10rem; border-radius: 50%;
                         background: rgba(255, 255, 255, .18); filter: blur(6px); }
    .mgn-promo b { display: inline-flex; align-items: center; gap: .4rem; font-size: .95rem; }
    .mgn-promo b .nx-icon { inline-size: 1rem; block-size: 1rem; }
    .mgn-promo p { margin: 0; font-size: .78rem; line-height: 1.6; opacity: .92; }
    .mgn-promo a { position: relative; justify-self: start; margin-block-start: .35rem; padding: .45rem .9rem;
                   border-radius: 999px; background: #fff; color: #4f46e5; font: 600 .76rem/1 var(--mgn-font);
                   text-decoration: none; }
    .mgn-promo a:hover { filter: brightness(.97); }

    /* ——— the fake page under the bar ——— */
    .mgn-page { padding: 9.5rem 1.5rem 2.5rem; display: grid; gap: 1rem; }
    .mgn-page h4 { margin: 0; text-align: center; font-size: clamp(1.25rem, 3.2vw, 1.75rem); font-weight: 800; letter-spacing: -.02em; }
    .mgn-page > p { margin: 0 auto; max-inline-size: 46ch; text-align: center; color: var(--mgn-muted); font-size: .9rem; }
    .mgn-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(11rem, 100%), 1fr)); gap: .75rem; }
    .mgn-card { padding: .9rem 1rem; border: 1px solid var(--mgn-border); border-radius: .8rem; background: var(--mgn-surface); }
    .mgn-card b { display: flex; align-items: center; gap: .4rem; font-size: .84rem; }
    .mgn-card b .nx-icon { inline-size: 1rem; block-size: 1rem; color: var(--mgn-accent); }
    .mgn-card span { display: block; margin-block-start: .25rem; color: var(--mgn-muted); font-size: .76rem; line-height: 1.6; }

    .mgn-spec { display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; align-items: flex-start; }
    .mgn-spec-cell { display: grid; gap: .5rem; justify-items: center; }
    .mgn-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    .mgn-spec .mgn-bar { position: static; }
    :where(.nx-js) .pg:has(.mgn-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 620px) {
        .mgn-panel { grid-template-columns: minmax(0, 1fr); }
        .mgn-cols { grid-template-columns: minmax(0, 1fr); }
    }
    @media (max-width: 480px) {
        .mgn-bar nav > a, .mgn-signin { display: none; }
        .mgn-page { padding-block-start: 8rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .mgn-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<div class="mgn-root"
    x-data="{
        mega: false,
        closer: null,
        open() { clearTimeout(this.closer); this.mega = true },
        later() { clearTimeout(this.closer); this.closer = setTimeout(() => this.mega = false, 140) },
    }"
    x-on:keydown.escape.window="if (mega) { mega = false; $refs.trigger.focus() }">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Hover it, click it, tab into it', 'هاورش کن، کلیکش کن، با تب واردش شو') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The panel opens on hover with a 140ms grace before closing, the trigger toggles it on click, ArrowDown walks the keyboard in and Escape snaps it shut — two link columns and a gradient promo card inside.', 'پنل با هاور باز می‌شود و ۱۴۰ میلی‌ثانیه مهلت قبل از بستن دارد، دکمه با کلیک وضعیت را برمی‌گرداند، فلش پایین کیبورد را وارد می‌کند و Escape می‌بندد — دو ستون لینک و کارت تخفیف گرادیانی داخلش.') }}
            </p>
        </div>

        <div class="mgn-stage">
            <div class="mgn-head" x-on:mouseenter="open()" x-on:mouseleave="later()">
                <header class="mgn-bar">
                    <b class="mgn-brand"><i aria-hidden="true"></i>{{ $say('Meridian', 'مریدین') }}</b>
                    <nav aria-label="{{ $say('Primary', 'اصلی') }}">
                        <button type="button" class="mgn-trigger" x-ref="trigger"
                            x-bind:aria-expanded="mega ? 'true' : 'false'" aria-haspopup="true"
                            x-on:click="mega = ! mega"
                            x-on:keydown.arrow-down.prevent="open(); $nextTick(() => $refs.panel.querySelector('a').focus())">
                            {{ $say('Product', 'محصول') }}<x-nx::icon name="chevron-down" />
                        </button>
                        <a href="#pricing">{{ $say('Pricing', 'قیمت') }}</a>
                        <a href="#docs">{{ $say('Docs', 'مستندات') }}</a>
                        <a href="#changelog">{{ $say('Changelog', 'تغییرات') }}</a>
                    </nav>
                    <span class="mgn-end">
                        <a class="mgn-signin" href="#signin">{{ $say('Sign in', 'ورود') }}</a>
                        <a class="mgn-cta" href="#demo">{{ $say('Book a demo', 'رزرو دمو') }}<x-nx::icon name="arrow-right" /></a>
                    </span>
                </header>

                <div class="mgn-panel" x-ref="panel" x-show="mega" x-cloak role="region" aria-label="{{ $say('Product menu', 'منوی محصول') }}">
                    <div class="mgn-cols">
                        <section>
                            <h5>{{ $say('Platform', 'پلتفرم') }}</h5>
                            <a class="mgn-item" href="#dashboards"><i><x-nx::icon name="chart" /></i><b>{{ $say('Dashboards', 'داشبوردها') }}</b><span>{{ $say('Live architecture, zero config', 'معماری زنده، بدون تنظیم') }}</span></a>
                            <a class="mgn-item" href="#alerts"><i><x-nx::icon name="bell" /></i><b>{{ $say('Alert routing', 'مسیریابی هشدار') }}</b><span>{{ $say('Follow-the-sun on-call', 'آن‌کال خورشیدگرد') }}</span></a>
                            <a class="mgn-item" href="#integrations"><i><x-nx::icon name="layers" /></i><b>{{ $say('Integrations', 'یکپارچه‌سازی') }}</b><span>{{ $num('120') }}+ {{ $say('connectors', 'اتصال‌دهنده') }}</span></a>
                            <a class="mgn-item" href="#api"><i><x-nx::icon name="command" /></i><b>GraphQL API</b><span>{{ $say('Typed clients in 6 languages', 'کلاینت تایپ‌دار در ۶ زبان') }}</span></a>
                        </section>
                        <section>
                            <h5>{{ $say('For teams', 'برای تیم‌ها') }}</h5>
                            <a class="mgn-item" href="#analytics"><i><x-nx::icon name="trend-up" /></i><b>{{ $say('Product analytics', 'تحلیل محصول') }}</b><span>{{ $say('Events without funnels', 'رویدادها بدون قیف') }}</span></a>
                            <a class="mgn-item" href="#sso"><i><x-nx::icon name="shield" /></i><b>SSO {{ $say('and', 'و') }} SCIM</b><span>{{ $say('Enterprise ready', 'آمادهٔ سازمان') }}</span></a>
                            <a class="mgn-item" href="#quotas"><i><x-nx::icon name="sliders" /></i><b>{{ $say('Usage quotas', 'سهمیهٔ مصرف') }}</b><span>{{ $say('Per team, per region', 'به‌ازای تیم و ناحیه') }}</span></a>
                            <a class="mgn-item" href="#notes"><i><x-nx::icon name="file" /></i><b>{{ $say('Release notes', 'یادداشت انتشار') }}</b><span>{{ $say('Auto-drafted per rollout', 'خودنویس برای هر استقرار') }}</span></a>
                        </section>
                    </div>
                    <aside class="mgn-promo">
                        <b><x-nx::icon name="sparkles" />{{ $say('Meridian Pro', 'مریدین پرو') }}</b>
                        <p>{{ $say('30% off annual plans until Oct 31 — includes the new Singapore and Toronto regions.', '۳۰٪ تخفیف پلن‌های سالانه تا ۳۱ اکتبر — با ناحیه‌های تازهٔ سنگاپور و تورنتو.') }}</p>
                        <a href="#claim">{{ $say('Claim credit', 'دریافت اعتبار') }}</a>
                    </aside>
                </div>
            </div>

            <div class="mgn-page">
                <h4>{{ $say('One workspace, every rollout', 'یک ورک‌اسپیس، همهٔ انتشارها') }}</h4>
                <p>{{ $say('Hover the Product trigger above — the mega panel carries the whole map of the platform, and the promo card never lets a launch go unnoticed.', 'نشانگر محصول بالا را هاور کنید — پنل مگا کل نقشهٔ پلتفرم را حمل می‌کند و کارت تخفیف هیچ انتشار را بی‌سروصدا رد نمی‌کند.') }}</p>
                <div class="mgn-cards">
                    <div class="mgn-card"><b><x-nx::icon name="zap" />{{ $say('Ingest', 'دریافت') }}</b><span>{{ $say('3.2M spans/s at p99 under 40ms.', '۳٫۲ میلیون اسپن بر ثانیه با p99 زیر ۴۰ms.') }}</span></div>
                    <div class="mgn-card"><b><x-nx::icon name="globe" />{{ $say('Regions', 'ناحیه‌ها') }}</b><span>{{ $say('Frankfurt, Dublin, Singapore, Toronto.', 'فرانکفورت، دوبلین، سنگاپور، تورنتو.') }}</span></div>
                    <div class="mgn-card"><b><x-nx::icon name="lock" />{{ $say('Compliance', 'انطباق') }}</b><span>{{ $say('SOC 2 Type II, GDPR, HIPAA.', 'SOC 2 نوع ۲، GDPR، HIPAA.') }}</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Trigger and promo states', 'حالت‌های دکمه و کارت تخفیف') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('The trigger lights its pill when expanded and the chevron flips; the promo card keeps its glow in both themes.', 'دکمه با بازشدن پیل روشنش را می‌گیرد و شورون برمی‌گردد؛ کارت تخفیف در هر دو تم درخشان می‌ماند.') }}
            </p>
        </div>
        <div class="mgn-spec">
            <div class="mgn-spec-cell">
                <span class="mgn-bar" aria-hidden="true">
                    <nav>
                        <span class="mgn-trigger" aria-expanded="true" style="cursor: default">{{ $say('Product', 'محصول') }}<x-nx::icon name="chevron-down" /></span>
                    </nav>
                </span>
                <small>aria-expanded = true</small>
            </div>
            <div class="mgn-spec-cell">
                <span class="mgn-bar" aria-hidden="true">
                    <nav>
                        <span class="mgn-trigger" style="cursor: default">{{ $say('Product', 'محصول') }}<x-nx::icon name="chevron-down" /></span>
                    </nav>
                </span>
                <small>aria-expanded = false</small>
            </div>
            <div class="mgn-spec-cell">
                <span class="mgn-promo" style="inline-size: 15rem" aria-hidden="true">
                    <b><x-nx::icon name="sparkles" />{{ $say('Meridian Pro', 'مریدین پرو') }}</b>
                    <p>{{ $say('30% off annual until Oct 31.', '۳۰٪ تخفیف سالانه تا ۳۱ اکتبر.') }}</p>
                    <a style="cursor: default">{{ $say('Claim credit', 'دریافت اعتبار') }}</a>
                </span>
                <small>promo-card</small>
            </div>
        </div>
    </section>
</div>
