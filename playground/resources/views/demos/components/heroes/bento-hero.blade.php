{{--
    The bento hero as a real first screen: display title, sub and CTAs on the
    start side, an asymmetric bento grid on the other — the line-chart tile
    that re-rolls its data, the tall mergers tile with avatars and its
    "load more", the tickable publishers tile and the stat tile whose number
    counts up. Every tile carries exactly one live micro-interaction.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;
@endphp
<style>
    .hrb-hero {
        --hrb-bg: linear-gradient(180deg, #fbfbfe 0%, #f1f2fa 100%);
        --hrb-ink: #12152b; --hrb-muted: #575d85;
        --hrb-panel: #ffffff; --hrb-panel-2: #f6f7fd; --hrb-line: #e3e6f5;
        --hrb-accent: #5b5bd6; --hrb-accent-2: #22d3ee; --hrb-ok: #178a50;
        padding: clamp(3rem, 7vw, 5.5rem) clamp(1.25rem, 4vw, 3rem);
        background: var(--hrb-bg); color: var(--hrb-ink);
        display: grid; grid-template-columns: minmax(0, .9fr) minmax(0, 1.35fr);
        gap: clamp(2rem, 5vw, 4rem); align-items: center;
    }
    html[data-theme="dark"] .hrb-hero {
        --hrb-bg: linear-gradient(180deg, #12152b 0%, #0e1023 100%);
        --hrb-ink: #eef1ff; --hrb-muted: #aab3e8;
        --hrb-panel: #181c3a; --hrb-panel-2: #141833; --hrb-line: #2b3160;
        --hrb-accent: #8a94ff; --hrb-accent-2: #67e8f9; --hrb-ok: #3ecf8e;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hrb-hero {
            --hrb-bg: linear-gradient(180deg, #12152b 0%, #0e1023 100%);
            --hrb-ink: #eef1ff; --hrb-muted: #aab3e8;
            --hrb-panel: #181c3a; --hrb-panel-2: #141833; --hrb-line: #2b3160;
            --hrb-accent: #8a94ff; --hrb-accent-2: #67e8f9; --hrb-ok: #3ecf8e;
        }
    }
    .hrb-copy { display: grid; gap: 1.25rem; justify-items: start; }
    .hrb-title { margin: 0; font: 800 clamp(2.2rem, 5vw, 3.9rem) / 1.08 var(--nx-font-display);
                 letter-spacing: var(--nx-tracking-tight); text-wrap: balance; }
    .hrb-sub { margin: 0; max-inline-size: 40ch; font-size: var(--nx-text-base);
               color: var(--hrb-muted); text-wrap: pretty; }
    .hrb-ctas { display: flex; flex-wrap: wrap; gap: .75rem; }

    .hrb-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr));
                grid-template-rows: auto auto; gap: .875rem; min-inline-size: 0; }
    .hrb-tile { margin: 0; padding: 1rem 1.1rem; border-radius: 1.25rem;
                background: var(--hrb-panel); border: 1px solid var(--hrb-line);
                box-shadow: 0 .65rem 1.9rem color-mix(in oklab, #0b1020 9%, transparent);
                display: grid; gap: .65rem; align-content: start; min-inline-size: 0;
                transition: translate .25s ease, box-shadow .25s ease; }
    .hrb-tile:hover { translate: 0 -.2rem;
                       box-shadow: 0 1.1rem 2.6rem color-mix(in oklab, #0b1020 14%, transparent); }
    .hrb-tile h3 { margin: 0; font: 650 .8rem/1.3 var(--nx-font-display); color: var(--hrb-muted);
                   display: flex; align-items: center; gap: .4rem; }
    .hrb-tile h3 svg { inline-size: .9rem; aspect-ratio: 1; color: var(--hrb-accent); }
    .hrb-chart { grid-column: 1 / 3; }
    .hrb-merges { grid-column: 3; grid-row: 1 / 3; }
    .hrb-chart svg { inline-size: 100%; block-size: auto; }
    .hrb-area { fill: url(#hrb-fill); opacity: .9; }
    .hrb-line { fill: none; stroke: var(--hrb-accent); stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
                stroke-dasharray: 320; stroke-dashoffset: 0;
                animation: hrb-draw 1.3s cubic-bezier(.4, 0, .2, 1) both; }
    .hrb-hero[data-roll] .hrb-line { animation: hrb-draw .55s cubic-bezier(.4, 0, .2, 1) both; }
    @keyframes hrb-draw { from { stroke-dashoffset: 320; } to { stroke-dashoffset: 0; } }
    .hrb-live { fill: var(--hrb-accent-2); animation: hrb-blink 1.8s ease-in-out infinite; }
    @keyframes hrb-blink { 50% { opacity: .25; } }
    .hrb-mini { justify-self: start; border: 1px solid var(--hrb-line); background: var(--hrb-panel-2);
                border-radius: 999px; padding: .35rem .8rem; font: 600 .72rem/1 inherit;
                color: var(--hrb-muted); cursor: pointer; transition: color .2s, border-color .2s; }
    .hrb-mini:hover { color: var(--hrb-ink); border-color: color-mix(in oklab, var(--hrb-accent) 45%, transparent); }
    .hrb-merges ul { display: grid; gap: .55rem; margin: 0; padding: 0; list-style: none; }
    .hrb-merge { display: flex; align-items: center; gap: .6rem; font-size: .8rem;
                 color: var(--hrb-muted); min-inline-size: 0; }
    .hrb-merge b { color: var(--hrb-ink); font-weight: 600; }
    .hrb-merge span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .hrb-ava { flex: none; inline-size: 1.7rem; aspect-ratio: 1; border-radius: 50%;
               display: inline-grid; place-items: center; font: 700 .62rem/1 system-ui;
               color: #fff; background: var(--hrb-c, var(--hrb-accent)); }
    .hrb-merge i { margin-inline-start: auto; flex: none; color: var(--hrb-ok); }
    .hrb-merge i svg { inline-size: .95rem; aspect-ratio: 1; }
    .hrb-pub { margin: 0; padding: 0; list-style: none; display: grid; gap: .5rem; }
    .hrb-pub button { display: flex; align-items: center; gap: .55rem; inline-size: 100%;
                      border: 1px solid var(--hrb-line); background: var(--hrb-panel-2);
                      border-radius: .8rem; padding: .5rem .7rem; cursor: pointer;
                      font: 600 .8rem/1 inherit; color: var(--hrb-muted);
                      transition: color .2s, border-color .2s, background-color .2s; }
    .hrb-pub button:hover { border-color: color-mix(in oklab, var(--hrb-accent) 40%, transparent); }
    .hrb-tick { flex: none; inline-size: 1.1rem; aspect-ratio: 1; border-radius: .35rem;
                border: 1.5px solid var(--hrb-line); display: inline-grid; place-items: center;
                color: transparent; transition: all .2s ease; }
    .hrb-tick svg { inline-size: .7rem; aspect-ratio: 1; }
    .hrb-pub button[aria-pressed="true"] { color: var(--hrb-ink); }
    .hrb-pub button[aria-pressed="true"] .hrb-tick { background: var(--hrb-ok); border-color: var(--hrb-ok); color: #fff; }
    .hrb-pub-count { margin-inline-start: auto; font: 600 .68rem/1 var(--nx-font-mono); color: var(--hrb-ok); }
    .hrb-stat { align-content: center; justify-items: start; cursor: pointer; }
    .hrb-stat b { font: 800 clamp(1.9rem, 4vw, 2.6rem)/1 var(--nx-font-display);
                  font-variant-numeric: tabular-nums; letter-spacing: -.02em; }
    .hrb-stat b small { font-size: .5em; font-weight: 700; color: var(--hrb-muted); }
    .hrb-trend { display: inline-flex; align-items: center; gap: .3rem; font: 600 .75rem/1 inherit;
                 color: var(--hrb-ok); }
    .hrb-trend svg { inline-size: .85rem; aspect-ratio: 1; }
    @media (max-width: 56.25rem) {
        .hrb-hero { grid-template-columns: 1fr; }
        .hrb-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .hrb-chart { grid-column: 1 / 3; }
        .hrb-merges { grid-column: 1 / 3; grid-row: auto; }
    }
    @media (max-width: 30rem) {
        .hrb-grid { grid-template-columns: 1fr; }
        .hrb-chart { grid-column: auto; }
    }
    @media (prefers-reduced-motion: reduce) {
        .hrb-line, .hrb-hero[data-roll] .hrb-line { animation-duration: .01ms; }
        .hrb-live { animation: none; }
        .hrb-tile { transition: none; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="hrb-hero"
        x-data="{
            points: [22, 40, 30, 55, 48, 70, 62, 84],
            merged: 3,
            pubs: [
                { name: 'npm', ok: true },
                { name: 'Docker Hub', ok: true },
                { name: 'Helm chart', ok: false },
            ],
            p95: 0,
            roll: false,
            fa: {{ $fa ? 'true' : 'false' }},
            reroll() {
                this.points = this.points.map(() => 15 + Math.round(Math.random() * 75));
                this.roll = true; setTimeout(() => this.roll = false, 600);
            },
            countP95() {
                this.p95 = 0;
                const t0 = performance.now();
                const step = (t) => {
                    this.p95 = Math.round(42 * Math.min(1, (t - t0) / 1100));
                    if (t - t0 < 1100) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            },
        }"
        x-init="countP95()"
        :data-roll="roll ? '' : null">
        <div class="hrb-copy">
            <x-nx::badge tone="neutral" dot>{{ $say('Live from the release plane', 'زنده از صفحهٔ انتشار') }}</x-nx::badge>
            <h2 class="hrb-title">
                {{ $say('One release plane, every signal', 'یک صفحهٔ انتشار، همهٔ سیگنال‌ها') }}
            </h2>
            <p class="hrb-sub">
                {{ $say('Charts, merges, publishers and latency in one glance — Asterly tiles the whole rollout so nothing hides behind a tab.', 'نمودارها، ادغام‌ها، پخش‌کننده‌ها و تأخیر در یک نگاه — استرلی کل انتشار را کاشی‌کاشی می‌چیند تا چیزی پشت یک تب پنهان نماند.') }}
            </p>
            <div class="hrb-ctas">
                <x-nx::button variant="primary" shape="pill" size="lg" icon-end="arrow-right">
                    {{ $say('Start free', 'شروع رایگان') }}
                </x-nx::button>
                <x-nx::button variant="secondary" shape="pill" size="lg" icon="chart">
                    {{ $say('Explore the tour', 'گشت‌وگذار در تور') }}
                </x-nx::button>
            </div>
        </div>

        <div class="hrb-grid">
            <figure class="hrb-tile hrb-chart" dir="ltr">
                <h3>{!! \NabuXUI\NabuXUI::icon('chart') !!}{{ $say('Deploy latency · 8 steps', 'تأخیر استقرار · ۸ پله') }}</h3>
                <svg viewBox="0 0 200 84" role="img" :aria-label="{{ \Illuminate\Support\Js::from($say('Latency line over the last eight steps', 'خط تأخیر روی هشت پلهٔ اخیر'))->toHtml() }}">
                    <defs>
                        <linearGradient id="hrb-fill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="var(--hrb-accent)" stop-opacity=".28" />
                            <stop offset="100%" stop-color="var(--hrb-accent)" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    <polygon class="hrb-area" :points="'0,84 ' + points.map((p, i) => `${i * 200 / 7},${80 - p * .8}`).join(' ') + ' 200,84'" fill="url(#hrb-fill)"></polygon>
                    <polyline class="hrb-line" :points="points.map((p, i) => `${i * 200 / 7},${80 - p * .8}`).join(' ')"></polyline>
                    <circle class="hrb-live" :cx="200" :cy="80 - points[points.length - 1] * .8" r="4"></circle>
                </svg>
                <button type="button" class="hrb-mini" x-on:click="reroll()">
                    {{ $say('Fresh data', 'دادهٔ تازه') }}
                </button>
            </figure>

            <div class="hrb-tile hrb-merges">
                <h3>{!! \NabuXUI\NabuXUI::icon('check-circle') !!}{{ $say('Merged today', 'ادغام‌شده‌های امروز') }}</h3>
                <ul>
                    <template x-for="m in [
                        { ini: 'LH', name: 'lena', note: 'feat: canary weights', c: '#6d7cff' },
                        { ini: 'MK', name: 'marco', note: 'fix: fifo queue', c: '#22d3ee' },
                        { ini: 'PR', name: 'priya', note: 'chore: deps bump', c: '#f59e0b' },
                        { ini: 'TA', name: 'tomás', note: 'feat: dr detector', c: '#a855f7' },
                        { ini: 'YS', name: 'yuki', note: 'docs: runbook', c: '#34d399' },
                    ].slice(0, merged)" :key="m.ini">
                        <li class="hrb-merge">
                            <span class="hrb-ava" :style="`--hrb-c: ${m.c}`" x-text="m.ini"></span>
                            <span><b x-text="m.name"></b> · <span x-text="m.note"></span></span>
                            <i aria-hidden="true">{!! \NabuXUI\NabuXUI::icon('check') !!}</i>
                        </li>
                    </template>
                </ul>
                <button type="button" class="hrb-mini" x-show="merged < 5" x-on:click="merged = Math.min(5, merged + 1)">
                    {{ $say('Load one more', 'یکی بیشتر') }}
                </button>
            </div>

            <div class="hrb-tile">
                <h3>
                    {!! \NabuXUI\NabuXUI::icon('upload') !!}{{ $say('Publishers', 'پخش‌کننده‌ها') }}
                    <span class="hrb-pub-count" x-text="pubs.filter(p => p.ok).length + '/' + pubs.length"></span>
                </h3>
                <ul class="hrb-pub">
                    <template x-for="(p, k) in pubs" :key="p.name">
                        <li>
                            <button type="button" :aria-pressed="p.ok ? 'true' : 'false'" x-on:click="p.ok = !p.ok">
                                <span class="hrb-tick" aria-hidden="true">{!! \NabuXUI\NabuXUI::icon('check') !!}</span>
                                <span x-text="p.name"></span>
                            </button>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="hrb-tile hrb-stat" x-on:click="countP95()" role="button" tabindex="0"
                x-on:keydown.enter.prevent="countP95()" x-on:keydown.space.prevent="countP95()"
                :title="{{ \Illuminate\Support\Js::from($say('Count it again', 'دوباره بشمار'))->toHtml() }}">
                <h3>{!! \NabuXUI\NabuXUI::icon('zap') !!}{{ $say('Edge latency', 'تأخیر لبه') }}</h3>
                <b><span x-text="fa ? String(p95).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]) : p95"></span><small>ms</small></b>
                <span class="hrb-trend">{!! \NabuXUI\NabuXUI::icon('trend-up') !!}{{ $say('+18% this week', 'این هفته +۱۸٪') }}</span>
            </div>
        </div>
    </div>
</section>
