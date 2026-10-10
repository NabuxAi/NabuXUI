{{--
    Gradient CTA in its real habitat: the mid-page banner of a landing page
    (gradient + passing light sweep + fine noise grain, two depth-lifting
    buttons, “no credit card” badge), then the same banner collapsed into its
    slim one-line variant for the end of a blog post.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .ctgr-root { display: grid; gap: 2.5rem; font-family: var(--nx-font-sans); }
    .ctgr-banner {
        --ctgr-angle: 115deg;
        --ctgr-deep: #312e81; --ctgr-mid: #4f46e5; --ctgr-hot: #7c3aed; --ctgr-edge: #c026d3;
        position: relative; overflow: clip; isolation: isolate; text-align: center; color: #fff;
        border-radius: var(--nx-radius-xl);
        background: linear-gradient(var(--ctgr-angle), var(--ctgr-deep), var(--ctgr-mid) 38%, var(--ctgr-hot) 62%, var(--ctgr-edge));
    }
    .ctgr-glow { position: absolute; inset: 0; z-index: -1; pointer-events: none;
                 background:
                     radial-gradient(52rem 18rem at 18% -12%, #ffffff2e, transparent 60%),
                     radial-gradient(40rem 16rem at 88% 112%, #0a0c1752, transparent 62%); }
    .ctgr-noise { position: absolute; inset: 0; z-index: 1; pointer-events: none; opacity: .05; mix-blend-mode: overlay;
                  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)'/%3E%3C/svg%3E"); }
    .ctgr-sweep { position: absolute; inset-block: -25%; inset-inline-start: 0; z-index: 1; inline-size: 34%; pointer-events: none;
                  background: linear-gradient(105deg, transparent, #ffffff4d, transparent);
                  transform: skewX(-18deg); animation: ctgr-sweep 6.5s ease-in-out infinite; }
    @keyframes ctgr-sweep { 0%, 12% { translate: -180% 0; } 62%, 100% { translate: 480% 0; } }
    .ctgr-body { position: relative; z-index: 2; display: grid; gap: 1rem; justify-items: center;
                 padding: clamp(2.75rem, 7vw, 4.75rem) clamp(1.25rem, 5vw, 4rem); }
    .ctgr-chip { display: inline-flex; align-items: center; gap: .5rem; padding: .45rem .95rem;
                 border-radius: 999px; background: #ffffff26; color: #fff; font-size: .8rem; font-weight: 500;
                 box-shadow: inset 0 0 0 1px #ffffff2e; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
    .ctgr-chip svg { inline-size: 1rem; block-size: 1rem; color: #7df3ff; }
    .ctgr-title { margin: 0; max-inline-size: 22ch; font: 800 clamp(1.85rem, 4.6vw, 3.1rem) / 1.15 var(--nx-font-display);
                  letter-spacing: var(--nx-tracking-tight); text-wrap: balance; }
    .ctgr-sub { margin: 0; max-inline-size: 46ch; color: #ffffffcc; font-size: var(--nx-text-md); text-wrap: pretty; }
    .ctgr-actions { display: flex; flex-wrap: wrap; justify-content: center; gap: .75rem; margin-block-start: .75rem; }
    .ctgr-btn { display: inline-flex; align-items: center; gap: .5rem; block-size: 3rem; padding-inline: 1.6rem;
                border-radius: 999px; background: #fff; color: #312e81; font: 700 var(--nx-text-sm) / 1 var(--nx-font-sans);
                text-decoration: none; cursor: pointer; box-shadow: 0 1px 0 #ffffff59 inset;
                transition: translate .18s ease, box-shadow .18s ease, background-color .18s ease; }
    .ctgr-btn svg { inline-size: 1.05rem; block-size: 1.05rem; }
    .ctgr-btn:hover { translate: 0 -2px; box-shadow: 0 12px 26px #00000040; }
    .ctgr-btn:active { translate: 0 0; box-shadow: 0 4px 12px #00000038; }
    .ctgr-btn:focus-visible { outline: 2px solid #7df3ff; outline-offset: 3px; }
    html[dir="rtl"] .ctgr-btn svg { transform: scaleX(-1); }
    .ctgr-ghost { background: #ffffff1f; color: #fff; box-shadow: inset 0 0 0 1px #ffffff59; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); }
    .ctgr-ghost:hover { background: #ffffff33; }
    html[data-theme="dark"] .ctgr-banner {
        --ctgr-deep: #1e1a4a; --ctgr-mid: #4739ca; --ctgr-hot: #7c3aed; --ctgr-edge: #9d3ace;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ctgr-banner {
            --ctgr-deep: #1e1a4a; --ctgr-mid: #4739ca; --ctgr-hot: #7c3aed; --ctgr-edge: #9d3ace;
        }
    }
    .ctgr-slim { border-radius: var(--nx-radius-lg); text-align: start; }
    .ctgr-slim .ctgr-body { grid-auto-flow: column; grid-auto-columns: auto; justify-content: space-between; align-items: center;
                            gap: 1rem; padding: 1.1rem 1.5rem; }
    .ctgr-slim .ctgr-slim-text { display: grid; gap: .2rem; }
    .ctgr-slim .ctgr-title { font-size: clamp(1.05rem, 2.6vw, 1.35rem); max-inline-size: none; }
    .ctgr-slim .ctgr-sub { font-size: var(--nx-text-sm); }
    .ctgr-slim .ctgr-btn { block-size: 2.6rem; padding-inline: 1.2rem; }
    @media (max-width: 560px) {
        .ctgr-slim .ctgr-body { grid-auto-flow: row; justify-items: start; }
        .ctgr-slim .ctgr-actions { align-self: stretch; justify-content: flex-start; }
    }
    @media (prefers-reduced-motion: reduce) {
        .ctgr-sweep { animation: none; }
        .ctgr-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="ctgr-root">
    <section aria-label="{{ $say('Mid-page CTA banner', 'بنر دعوت به کنش میانی صفحه') }}">
        <div class="ctgr-banner">
            <span class="ctgr-glow" aria-hidden="true"></span>
            <span class="ctgr-noise" aria-hidden="true"></span>
            <span class="ctgr-sweep" aria-hidden="true"></span>
            <div class="ctgr-body">
                <span class="ctgr-chip">
                    {{ \NabuXUI\NabuXUI::icon('shield') }}
                    {{ $say('No credit card · 14-day trial', 'بدون کارت بانکی · ' . $num('14') . ' روز آزمایشی') }}
                </span>
                <h3 class="ctgr-title">{{ $say('Every release, measured.', 'هر انتشار، با عدد.') }}</h3>
                <p class="ctgr-sub">
                    {{ $say('Meridian traces adoption from the first deploy — from Frankfurt to Singapore, see which features actually get used.', 'مریدین پذیرش فیچرها را از همان اولین استقرار دنبال می‌کند — از فرانکفورت تا سنگاپور، ببین کدام فیچر واقعاً استفاده می‌شود.') }}
                </p>
                <div class="ctgr-actions">
                    <a class="ctgr-btn" href="/components">
                        {{ $say('Start free', 'شروع رایگان') }}{{ \NabuXUI\NabuXUI::icon('arrow-right') }}
                    </a>
                    <a class="ctgr-btn ctgr-ghost" href="mailto:sales@meridian.dev">{{ $say('Talk to sales', 'گفتگو با فروش') }}</a>
                </div>
            </div>
        </div>
    </section>

    <section aria-label="{{ $say('Slim variant for the end of a blog post', 'نسخهٔ یک‌خطی برای انتهای پست بلاگ') }}">
        <div class="ctgr-banner ctgr-slim">
            <span class="ctgr-noise" aria-hidden="true"></span>
            <span class="ctgr-sweep" aria-hidden="true"></span>
            <div class="ctgr-body">
                <div class="ctgr-slim-text">
                    <h4 class="ctgr-title">{{ $say('Still reading? Your metrics are waiting.', 'هنوز این‌هایی؟ معیارهایت منتظرند.') }}</h4>
                    <p class="ctgr-sub">{{ $say('Set up Meridian before your next release.', 'مریدین را قبل از انتشار بعدی راه بیندازید.') }}</p>
                </div>
                <div class="ctgr-actions">
                    <a class="ctgr-btn" href="/components">
                        {{ $say('Start free', 'شروع رایگان') }}{{ \NabuXUI\NabuXUI::icon('arrow-right') }}
                    </a>
                </div>
            </div>
        </div>
        <p style="margin: .75rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
            {{ $say('data-size="slim" — the same gradient, sweep and grain folded into one line for the gap between blog sections.', '‏data-size="slim" — همان گرادیان، جارو و دانه، تا شده در یک خط برای فاصلهٔ بین بخش‌های بلاگ.') }}
        </p>
    </section>
</div>
