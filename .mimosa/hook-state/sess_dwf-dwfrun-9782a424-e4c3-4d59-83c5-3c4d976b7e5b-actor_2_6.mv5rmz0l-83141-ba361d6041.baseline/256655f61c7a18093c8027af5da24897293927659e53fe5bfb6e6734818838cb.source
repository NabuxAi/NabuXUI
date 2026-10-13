{{--
    Gradient border button's real scenarios: the deploy console of an edge
    network — a glass pill whose conic ring circles it, regions picked with
    the same ringed buttons (the chosen one earning a thicker ring), a dial
    for the spin speed — then the shapes and the link cut for RTL.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp

<style>
    .gbb-root {
        --gbb-speed: 4s;
        --gbb-ink: #0f172a;
        --gbb-glass: rgba(255, 255, 255, .78);
        --gbb-panel: linear-gradient(160deg, #f8fafc, #eef2ff);
        --gbb-dial-ink: #475569;
        display: grid;
        gap: 2rem;
        justify-items: center;
        inline-size: 100%;
    }
    html[data-theme="dark"] .gbb-root {
        --gbb-ink: #e2e8f0;
        --gbb-glass: rgba(15, 23, 42, .55);
        --gbb-panel: linear-gradient(160deg, #111827, #1e1b33);
        --gbb-dial-ink: #94a3b8;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .gbb-root {
            --gbb-ink: #e2e8f0;
            --gbb-glass: rgba(15, 23, 42, .55);
            --gbb-panel: linear-gradient(160deg, #111827, #1e1b33);
            --gbb-dial-ink: #94a3b8;
        }
    }
    .gbb-btn {
        position: relative; overflow: hidden; padding: 1.5px; border: 0; border-radius: 999px;
        background: transparent; cursor: pointer; font: inherit;
    }
    .gbb-btn::before {
        content: ''; position: absolute; inset: -60%;
        background: conic-gradient(from 0deg, #f472b6, #a78bfa, #38bdf8, #34d399, #fbbf24, #f472b6);
        animation: gbb-spin var(--gbb-speed) linear infinite;
    }
    .gbb-inner {
        position: relative; display: inline-flex; align-items: center; gap: .5rem;
        padding: .65rem 1.4rem; border-radius: inherit; background: var(--gbb-glass);
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        color: var(--gbb-ink); font-weight: 600; font-size: .92rem; line-height: 1.4;
        transition: translate .25s ease;
    }
    .gbb-inner small { font-weight: 400; opacity: .65; }
    .gbb-btn:hover::before, .gbb-btn:focus-visible::before { animation-duration: 1s; }
    .gbb-btn:hover .gbb-inner { translate: 0 -1px; }
    .gbb-btn:active .gbb-inner { translate: 0 0; }
    .gbb-btn:focus-visible { outline: none; }
    .gbb-btn:focus-visible .gbb-inner { outline: 2px solid #a78bfa; outline-offset: 2px; }
    .gbb-btn[data-size="lg"] .gbb-inner { padding: .9rem 1.9rem; font-size: 1.05rem; }
    .gbb-btn[data-shape="rounded"] { border-radius: 14px; }
    .gbb-btn[aria-pressed="true"] { padding: 2.5px; }
    .gbb-regions { display: flex; flex-wrap: wrap; gap: .75rem 1rem; justify-content: center; }
    .gbb-dial {
        display: flex; flex-wrap: wrap; align-items: center; gap: .5rem .75rem;
        font-size: var(--nx-text-sm); color: var(--gbb-dial-ink);
    }
    .gbb-dial input[type="range"] { accent-color: #a78bfa; inline-size: min(11rem, 56vw); }
    .gbb-status { margin: 0; min-block-size: 1.4em; font-size: var(--nx-text-sm); color: var(--gbb-dial-ink); text-align: center; }
    @keyframes gbb-spin { to { rotate: 360deg; } }
    @media (prefers-reduced-motion: reduce) {
        .gbb-btn::before { animation: none; }
        .gbb-inner { transition: none; }
    }
</style>

<div class="gbb-root"
    x-data="{ speed: 4, region: 'eu-de-2', queued: null, locale: '{{ $fa ? 'fa-IR' : 'en-US' }}' }"
    x-bind:style="'--gbb-speed:' + speed + 's'">
    <section class="pg-box" style="gap: 1.25rem; justify-items: center; background: var(--gbb-panel)">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Deploy to the edge', 'استقرار روی لبه') }}</h3>
            <p style="margin: 0; max-inline-size: 52ch; color: var(--nx-text-muted)">
                {{ $say('The conic ring never stops circling — hover a button and it spins four times faster. The inner face is glass, the picked region thickens its ring, and the dial sets the pace for the whole board.', 'حلقهٔ conic هیچ‌وقت از چرخیدن نمی‌ایستد — هاور هر دکمه را چهار برابر تندتر می‌چرخاند. سطح داخلی شیشه است، ناحیهٔ انتخاب‌شده حلقه‌اش را ضخیم می‌کند و دستگیره، آهنگ کل تخته را می‌چیند.') }}
            </p>
        </div>

        <button type="button" class="gbb-btn" data-size="lg" x-on:click="queued = region">
            <span class="gbb-inner">{{ $say('Deploy the edge app', 'انتشار اپ لبه') }}</span>
        </button>

        <div class="gbb-dial">
            <label for="gbb-speed-dial">{{ $say('Ring speed', 'سرعت حلقه') }}</label>
            <input id="gbb-speed-dial" type="range" min="1" max="6" step="0.5" x-model.number="speed">
            <output x-text="speed.toLocaleString(locale) + '{{ $say(' s', ' ثانیه') }}'"></output>
        </div>

        <div class="gbb-regions" role="group" aria-label="{{ $say('Deploy region', 'ناحیهٔ استقرار') }}">
            @foreach ([
                ['eu-de-2', 'Frankfurt', 'فرانکفورت'],
                ['eu-ie-1', 'Dublin', 'دوبلین'],
                ['ap-se-1', 'Singapore', 'سنگاپور'],
            ] as [$zone, $cityEn, $cityFa])
                <button type="button" class="gbb-btn"
                    x-bind:aria-pressed="region === '{{ $zone }}' ? 'true' : 'false'"
                    x-on:click="region = '{{ $zone }}'; queued = null">
                    <span class="gbb-inner">{{ $say($cityEn, $cityFa) }} <small>· {{ $zone }}</small></span>
                </button>
            @endforeach
        </div>

        <p class="gbb-status" role="status" x-show="queued" x-cloak>
            {{ $say('Queued in', 'در صف') }}
            <span x-text="queued"></span>
            — {{ $say('live in about '. $num('90') . ' seconds.', 'حدود ' . $num('90') . ' ثانیه دیگر زنده است.') }}
        </p>
    </section>

    <div class="pg-grid">
        <section class="pg-box" style="gap: 1rem; background: var(--gbb-panel)">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pill or rounded', 'قرصی یا گرد') }}</h3>
            <p style="margin: 0; color: var(--nx-text-muted)">
                {{ $say('The ring is the border itself — 1.5px of glass padding on pill, the same rhythm squared off at 14px for the changelog cut.', 'خود حلقه همان حاشیه است — ۱٫۵ پیکسل پدینگ شیشه‌ای در حالت قرصی، و همان ریتم با گوشهٔ ۱۴ پیکسلی برای حالت گرد.') }}
            </p>
            <div class="gbb-regions">
                <button type="button" class="gbb-btn">
                    <span class="gbb-inner">{{ $say('Join the beta', 'عضویت در بتا') }}</span>
                </button>
                <button type="button" class="gbb-btn" data-shape="rounded">
                    <span class="gbb-inner">{{ $say('Read the changelog', 'خواندن تغییرات') }}</span>
                </button>
            </div>
        </section>

        <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem; background: var(--gbb-panel)">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">لینک با حلقهٔ چرخان</h3>
            <p style="margin: 0; color: var(--nx-text-muted)">
                چرخش حاشیه به جهت کار ندارد؛ لینک هم همان دکمه است و حلقه دورش می‌گردد.
            </p>
            <div class="gbb-regions">
                <a class="gbb-btn" data-size="lg" href="#">
                    <span class="gbb-inner">رزرو جلسهٔ دمو</span>
                </a>
                <a class="gbb-btn" href="#">
                    <span class="gbb-inner">مشاهدهٔ مستندات</span>
                </a>
            </div>
            <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted); text-align: center">
                دموی {{ $num('30') }} دقیقه‌ای — ساعت کبری و ساعت سنتی
            </p>
        </section>
    </div>
</div>
