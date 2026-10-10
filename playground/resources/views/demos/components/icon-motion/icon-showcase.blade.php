{{--
    icon-showcase's real scenario: the launch section of Vertex Studio — the
    whole icon-motion vocabulary in one page. A self-drawing line
    illustration breathes behind the headline, the feature row inks its
    icons in on entry, the CTAs carry stateful heart/bookmark toggles, and
    the stat cards run a working spinner and a bouncing trend arrow.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Drawn in order behind the headline: two orbits, three signal hops.
    $bgPaths = [
        'M60 220a120 120 0 1 1 240 0',                    // outer orbit — right half
        'M300 220a120 120 0 1 1 -240 0',                  // outer orbit — left half
        'M100 220a80 80 0 1 1 160 0',                     // inner orbit — right half
        'M260 220a80 80 0 1 1 -160 0',                    // inner orbit — left half
        'M180 220h40',                                    // first hop
        'm224 204 24-24',                                 // second hop
        'm256 168 28 8',                                  // third hop
        'M288 128a56 56 0 0 1 56 56',                     // signal arc
    ];

    // The feature row, in draw order.
    $features = [
        ['fa' => 'انتشار فلگ‌دار', 'en' => 'Guarded rollouts', 'desc_fa' => 'هر ویژگی پشت فلگ، برای ۱٪ یا ۱۰۰٪.', 'desc_en' => 'Every feature behind a flag — for 1% or 100%.', 'paths' => ['M12 22s8-3.5 8-9.5V5l-8-3-8 3v7.5C4 18.5 12 22 12 22Z', 'm9 12 2 2 4-4']],
        ['fa' => 'همگام‌سازی زنده', 'en' => 'Live sync', 'desc_fa' => '۲۱۴ فایل در کمتر از یک دقیقه.', 'desc_en' => '214 files in under a minute.', 'paths' => ['M19.5 13.5A8 8 0 0 1 5.6 16.4', 'M4.5 13.5v3.8h3.8', 'M4.5 10.5A8 8 0 0 1 18.4 7.6', 'M19.5 10.5V6.7h-3.8']],
        ['fa' => 'مشاهده‌پذیری کامل', 'en' => 'Full observability', 'desc_fa' => 'ردیابی که تا خودِ کلید کاربر می‌رود.', 'desc_en' => 'Traces that reach the keypress itself.', 'paths' => ['M3 3v18h18', 'M7 15v3M12 10v8M17 6v12']],
        ['fa' => 'کنترل از راه دور', 'en' => 'Remote control', 'desc_fa' => 'از فرانکفورت تا سنگاپور، یک کلیک.', 'desc_en' => 'Frankfurt to Singapore in one click.', 'paths' => ['M5 12a7 7 0 1 1 2 4.9', 'M5 3v5h5', 'M13 8l4 4-4 4', 'M17 12H8']],
    ];
@endphp

<style>
    .imc-root {
        --imc-fg: #1c1917; --imc-muted: #78716c; --imc-border: #e7e5e4;
        --imc-bg: #ffffff; --imc-accent: #4f46e5; --imc-soft: #eef2ff; --imc-pink: #ec4899;
        display: grid;
        gap: 2.2rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--imc-fg);
    }
    html[data-theme="dark"] .imc-root {
        --imc-fg: #fafaf9; --imc-muted: #a8a29e; --imc-border: #292524;
        --imc-bg: #171512; --imc-accent: #a5b4fc; --imc-soft: #26264a; --imc-pink: #f472b6;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .imc-root {
            --imc-fg: #fafaf9; --imc-muted: #a8a29e; --imc-border: #292524;
            --imc-bg: #171512; --imc-accent: #a5b4fc; --imc-soft: #26264a; --imc-pink: #f472b6;
        }
    }
    .imc-hero {
        position: relative;
        display: grid;
        justify-items: center;
        gap: 1.15rem;
        padding: clamp(2rem, 5vw, 3.5rem) 1.25rem;
        border-radius: 1.4rem;
        background: var(--imc-bg);
        border: 1px solid var(--imc-border);
        overflow: clip;
        text-align: center;
    }
    .imc-bgart {
        position: absolute;
        inset: 0;
        inline-size: 100%;
        block-size: 100%;
        color: var(--imc-accent);
        opacity: .35;
        pointer-events: none;
    }
    .imc-bgart path {
        fill: none; stroke: currentColor; stroke-width: 1.6; stroke-linecap: round;
        stroke-dasharray: 1; stroke-dashoffset: 1;
        transition: stroke-dashoffset 1s ease var(--d, 0s);
    }
    .imc-hero[data-seen="true"] .imc-bgart path { stroke-dashoffset: 0; }
    .imc-hero > *:not(.imc-bgart) { position: relative; }
    .imc-badge {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .32rem .85rem; border-radius: 999px;
        border: 1px solid var(--imc-border);
        background: var(--imc-soft);
        color: var(--imc-accent);
        font-size: .72rem; font-weight: 700;
    }
    .imc-badge svg { inline-size: .8rem; block-size: .8rem; fill: none; stroke: currentColor;
                     stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .imc-title {
        margin: 0;
        max-inline-size: 22ch;
        font-size: clamp(1.6rem, 4.2vw, 2.5rem);
        font-weight: 800;
        line-height: 1.22;
        letter-spacing: -.02em;
        text-wrap: balance;
    }
    .imc-sub { margin: 0; max-inline-size: 46ch; font-size: .88rem; line-height: 1.8; color: var(--imc-muted); }
    .imc-ctas { display: flex; flex-wrap: wrap; gap: .6rem; justify-content: center; }
    .imc-primary {
        display: inline-flex; align-items: center; gap: .5rem;
        block-size: 2.7rem; padding-inline: 1.5rem;
        border: 1px solid var(--imc-accent); border-radius: .85rem;
        background: var(--imc-accent); color: #fff;
        font: 700 .86rem/1 inherit; text-decoration: none; cursor: pointer;
        transition: translate .15s ease, box-shadow .15s ease;
    }
    .imc-primary:hover { translate: 0 -2px; box-shadow: 0 10px 22px -10px var(--imc-accent); }
    .imc-primary:focus-visible { outline: 2px solid var(--imc-accent); outline-offset: 2px; }
    .imc-iconbtn {
        display: inline-grid; place-items: center;
        block-size: 2.7rem; aspect-ratio: 1;
        border: 1px solid var(--imc-border); border-radius: .85rem;
        background: var(--imc-bg); color: var(--imc-muted); cursor: pointer;
        transition: border-color .2s ease, color .2s ease;
    }
    .imc-iconbtn:hover { border-color: var(--imc-accent); color: var(--imc-fg); }
    .imc-iconbtn:focus-visible { outline: 2px solid var(--imc-accent); outline-offset: 2px; }
    .imc-iconbtn svg { inline-size: 1.2rem; block-size: 1.2rem; }
    .imc-stroke { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .imc-fill { fill: currentColor; stroke: none; scale: 0; transform-box: fill-box; transform-origin: center; }
    .imc-iconbtn[aria-pressed="true"] { color: var(--imc-pink); border-color: var(--imc-pink); }
    .imc-iconbtn[aria-pressed="true"] .imc-fill { scale: 1; animation: imc-pop .45s cubic-bezier(.2, .9, .3, 1.35); }

    .imc-stats { display: grid; gap: .8rem; grid-template-columns: repeat(auto-fit, minmax(11rem, 1fr)); }
    .imc-stat {
        display: grid; grid-template-columns: auto 1fr; align-items: center; gap: .8rem;
        padding: 1rem 1.1rem;
        border: 1px solid var(--imc-border); border-radius: 1rem;
        background: var(--imc-bg);
    }
    .imc-ico { display: grid; place-items: center; inline-size: 2.3rem; block-size: 2.3rem;
               border-radius: .7rem; background: var(--imc-soft); color: var(--imc-accent); }
    .imc-ico svg { inline-size: 1.2rem; block-size: 1.2rem; overflow: visible; }
    .imc-dots { display: flex; gap: .22rem; }
    .imc-dots i { inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor;
                  animation: imc-dot .9s ease-in-out infinite; }
    .imc-dots i:nth-child(2) { animation-delay: .15s; }
    .imc-dots i:nth-child(3) { animation-delay: .3s; }
    .imc-pulse { position: relative; inline-size: .55rem; block-size: .55rem; border-radius: 50%;
                 background: #10b981; color: #10b981; }
    .imc-pulse::after { content: ''; position: absolute; inset: 0; border-radius: 50%;
                        border: 2px solid currentColor; animation: imc-halo 1.3s ease-out infinite; }
    .imc-bounce { animation: imc-bounce 1.5s ease-in-out infinite; }
    .imc-num { font-size: 1.15rem; font-weight: 800; line-height: 1.2; }
    .imc-cap { margin: .1rem 0 0; font-size: .7rem; color: var(--imc-muted); }

    .imc-feats { display: grid; gap: .8rem; grid-template-columns: repeat(auto-fit, minmax(12.5rem, 1fr)); }
    .imc-feat {
        display: grid; gap: .6rem; justify-items: start;
        padding: 1.15rem 1.2rem;
        border: 1px solid var(--imc-border); border-radius: 1rem;
        background: var(--imc-bg);
    }
    .imc-feat .imc-fico { color: var(--imc-accent); }
    .imc-feat svg { inline-size: 1.7rem; block-size: 1.7rem; }
    .imc-feat path { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
                     stroke-dasharray: 1; stroke-dashoffset: 1;
                     transition: stroke-dashoffset .55s ease var(--d, 0s); }
    .imc-root[data-seen="true"] .imc-feat path { stroke-dashoffset: 0; }
    .imc-feat h4 { margin: .25rem 0 0; font-size: .84rem; font-weight: 700; }
    .imc-feat p { margin: 0; font-size: .74rem; line-height: 1.65; color: var(--imc-muted); }

    @keyframes imc-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
    @keyframes imc-dot { 0%, 100% { translate: 0 0; opacity: .45; } 40% { translate: 0 -.28rem; opacity: 1; } }
    @keyframes imc-halo { from { opacity: .8; scale: .7; } to { opacity: 0; scale: 1.7; } }
    @keyframes imc-bounce { 0%, 100% { translate: 0 0; } 42% { translate: 0 2.5px; } 58% { translate: 0 -3px; } 76% { translate: 0 1px; } }
    @media (prefers-reduced-motion: reduce) {
        .imc-bgart path, .imc-feat path { transition: none; stroke-dashoffset: 0; }
        .imc-fill { animation: none; }
        .imc-iconbtn[aria-pressed='true'] .imc-fill { scale: 1; }
        .imc-dots i, .imc-pulse::after, .imc-bounce, .imc-primary:hover { animation: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The whole vocabulary, one launch', 'کل واژگان، یک لانچ') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Vertex Studio’s launch section speaks the entire icon-motion language at once: a self-drawing line illustration breathes behind the headline, the feature row inks its icons in as it enters view, the CTAs carry stateful heart and bookmark toggles, and the stat cards run a working dot spinner, a pulsing uptime dot and a bouncing latency arrow.', 'بخش رونمایی ورتکس استودیو همهٔ زبان آیکون‌های متحرک را با هم حرف می‌زند: تصویرسازی خطیِ خودترسیم پشت سرتیتر نفس می‌کشد، ردیف قابلیت‌ها آیکون‌هایش را با ورود به دید می‌کشد، دکمه‌های CTA تاگل‌های حالت‌دار قلب و بوکمارک دارند و کارت‌های آمار اسپینر نقطه‌ایِ در حال کار، نقطهٔ آپ‌تایم نبض‌دار و فلش تأخیر جهنده را می‌چرخانند.') }}
        </p>
    </div>

    <div class="imc-root"
        x-data="{
            fa: @js($fa),
            seen: false,
            liked: false,
            saved: false,
            io: null,
            init() {
                this.io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                    if (entry.isIntersecting) { this.seen = true; this.io.disconnect(); }
                }), { threshold: 0.25 });
                this.io.observe(this.$el);
            },
            destroy() { if (this.io) this.io.disconnect(); },
        }"
        x-bind:data-seen="seen ? 'true' : 'false'">

        {{-- hero: self-drawing background + stateful CTAs --}}
        <div class="imc-hero" x-bind:data-seen="seen ? 'true' : 'false'">
            <svg class="imc-bgart" viewBox="0 0 360 440" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                @foreach ($bgPaths as $i => $d)
                    <path pathLength="1" d="{{ $d }}" style="--d: {{ $i * 140 }}ms" />
                @endforeach
            </svg>

            <span class="imc-badge">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l1.9 5.7a2 2 0 0 0 1.3 1.3L21 12l-5.8 2a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.7a2 2 0 0 0-1.3-1.3L3 12l5.8-2a2 2 0 0 0 1.3-1.3L12 3Z"/></svg>
                {{ $say('Vertex Studio 2026.10 is live', 'ورتکس استودیو ۲۰۲۶٫۱۰ منتشر شد') }}
            </span>

            <h4 class="imc-title">{{ $say('Ship the work. Watch it move.', 'کار را منتشر کن. جنبشش را ببین.') }}</h4>
            <p class="imc-sub">
                {{ $say('Rollouts behind flags, live sync across three regions and traces down to the keypress — the launch desk your team actually keeps open.', 'انتشار پشت فلگ‌ها، همگام‌سازی زنده در سه قاره و ردیابی تا خودِ کلید کاربر — میز لانچی که تیمت واقعاً باز نگه می‌دارد.') }}
            </p>

            <div class="imc-ctas">
                <a class="imc-primary" href="#start">
                    {{ $say('Start free', 'شروع رایگان') }}
                    <svg viewBox="0 0 24 24" aria-hidden="true" style="inline-size: .95rem; block-size: .95rem; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </a>
                <button type="button" class="imc-iconbtn" x-bind:aria-pressed="liked ? 'true' : 'false'"
                        x-on:click="liked = !liked"
                        :aria-label="liked ? (fa ? 'حذف پسند' : 'Remove like') : (fa ? 'پسندن صفحه' : 'Like this page')">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="imc-stroke" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        <path class="imc-fill" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </button>
                <button type="button" class="imc-iconbtn" x-bind:aria-pressed="saved ? 'true' : 'false'"
                        x-on:click="saved = !saved"
                        :aria-label="saved ? (fa ? 'حذف از ذخیره‌شده‌ها' : 'Remove bookmark') : (fa ? 'ذخیرهٔ صفحه' : 'Bookmark this page')">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="imc-stroke" d="M6 3h12a1 1 0 0 1 1 1v17l-7-4.5L5 21V4a1 1 0 0 1 1-1Z"/>
                        <path class="imc-fill" d="M6 3h12a1 1 0 0 1 1 1v17l-7-4.5L5 21V4a1 1 0 0 1 1-1Z"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- stats: working icons inside live numbers --}}
        <div class="imc-stats">
            <div class="imc-stat">
                <span class="imc-ico" role="img" :aria-label="fa ? 'در حال کار' : 'working'">
                    <span class="imc-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                </span>
                <div>
                    <div class="imc-num">{{ $say('128', '۱۲۸') }}</div>
                    <p class="imc-cap">{{ $say('projects shipping this week', 'پروژهٔ در حال انتشار این هفته') }}</p>
                </div>
            </div>
            <div class="imc-stat">
                <span class="imc-ico" role="img" :aria-label="fa ? 'پایدار' : 'healthy'">
                    <span class="imc-pulse" aria-hidden="true"></span>
                </span>
                <div>
                    <div class="imc-num">{{ $say('99.98%', '۹۹٫۹۸٪') }}</div>
                    <p class="imc-cap">{{ $say('uptime across 3 regions', 'آپ‌تایم در ۳ قاره') }}</p>
                </div>
            </div>
            <div class="imc-stat">
                <span class="imc-ico" role="img" :aria-label="fa ? 'رو به رشد' : 'trending'">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <g class="imc-bounce">
                            <path class="imc-stroke" d="M3 17l6-6 4 4 8-8"/>
                            <path class="imc-stroke" d="M15 7h6v6"/>
                        </g>
                    </svg>
                </span>
                <div>
                    <div class="imc-num">{{ $say('42ms', '۴۲ms') }}</div>
                    <p class="imc-cap">{{ $say('median rollout latency', 'میانهٔ تأخیر انتشار') }}</p>
                </div>
            </div>
        </div>

        {{-- feature row: icons inking themselves in --}}
        <div class="imc-feats">
            @foreach ($features as $fi => $feature)
                <div class="imc-feat">
                    <span class="imc-fico" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            @foreach ($feature['paths'] as $p => $d)
                                <path pathLength="1" d="{{ $d }}" style="--d: {{ $fi * 160 + $p * 130 }}ms" />
                            @endforeach
                        </svg>
                    </span>
                    <h4>{{ $say($feature['en'], $feature['fa']) }}</h4>
                    <p>{{ $say($feature['desc_en'], $feature['desc_fa']) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
