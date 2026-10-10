{{--
    The animated beams' real scenario: the architecture page of a platform —
    an SVG canvas where the orchestrator hub wires five services with faint
    copper lines, and beam gradients pulse along them via animated stroke-dash.
    Hover (or focus, or the chips below on touch) lights just the wiring of
    the node you are on.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $nodes = [
        ['id' => 'web',    'fa' => 'رابط وب',     'en' => 'Web app',  'cityFa' => 'دوبلین',    'cityEn' => 'Dublin',    'x' => 110, 'y' => 72,  'r' => 30, 'lat' => '12'],
        ['id' => 'auth',   'fa' => 'احراز هویت',  'en' => 'Auth',     'cityFa' => 'فرانکفورت', 'cityEn' => 'Frankfurt', 'x' => 320, 'y' => 48,  'r' => 28, 'lat' => '8'],
        ['id' => 'search', 'fa' => 'جست‌وجو',      'en' => 'Search',   'cityFa' => 'سنگاپور',   'cityEn' => 'Singapore', 'x' => 530, 'y' => 72,  'r' => 30, 'lat' => '21'],
        ['id' => 'media',  'fa' => 'رسانه',       'en' => 'Media',    'cityFa' => 'اوساکا',     'cityEn' => 'Osaka',     'x' => 530, 'y' => 236, 'r' => 30, 'lat' => '26'],
        ['id' => 'queue',  'fa' => 'صف کارها',    'en' => 'Queue',    'cityFa' => 'تورنتو',     'cityEn' => 'Toronto',   'x' => 110, 'y' => 236, 'r' => 30, 'lat' => '17'],
    ];
    $hub = ['x' => 320, 'y' => 150, 'r' => 36];
    $beam = fn (array $a, array $b, int $lift = 56): string =>
        sprintf('M%d %d Q %d %d %d %d', $a['x'], $a['y'], ($a['x'] + $b['x']) / 2, ($a['y'] + $b['y']) / 2 - $lift, $b['x'], $b['y']);
@endphp

<style>
    .mfxab-root {
        --mfxab-beam: #6366f1; --mfxab-beam-2: #22d3ee;
        --mfxab-wire: #c9cfdd; --mfxab-ink: #16181d; --mfxab-ink-soft: #5c6270;
        --mfxab-node: #ffffff; --mfxab-floor: #f7f8fa; --mfxab-line: #d9dde4;
        display: grid; gap: 1.25rem; justify-items: center;
        font-family: var(--nx-font-display, system-ui);
    }
    html[data-theme="dark"] .mfxab-root {
        --mfxab-beam: #8b83ff; --mfxab-beam-2: #22d3ee;
        --mfxab-wire: #3a4054; --mfxab-ink: #f2f4f8; --mfxab-ink-soft: #9aa1b0;
        --mfxab-node: #1e2130; --mfxab-floor: #12141d; --mfxab-line: #2e3243;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mfxab-root {
            --mfxab-beam: #8b83ff; --mfxab-beam-2: #22d3ee;
            --mfxab-wire: #3a4054; --mfxab-ink: #f2f4f8; --mfxab-ink-soft: #9aa1b0;
            --mfxab-node: #1e2130; --mfxab-floor: #12141d; --mfxab-line: #2e3243;
        }
    }
    .mfxab-head { display: grid; gap: .4rem; text-align: center; justify-items: center; }
    .mfxab-head p { margin: 0; max-inline-size: 56ch; color: var(--nx-text-muted); }
    .mfxab-canvas {
        inline-size: 100%; border: 1px solid var(--mfxab-line); border-radius: 1.25rem;
        background:
            radial-gradient(420px 240px at 50% 42%, rgba(99, 102, 241, .07), transparent 70%),
            var(--mfxab-floor);
        padding: clamp(.75rem, 2.5vw, 1.5rem); overflow: clip;
    }
    .mfxab-map { display: block; inline-size: 100%; block-size: auto; }
    .mfxab-wire { fill: none; stroke: var(--mfxab-wire); stroke-width: 1.5; }
    .mfxab-beam {
        fill: none; stroke-width: 2; stroke-linecap: round;
        stroke-dasharray: 16 214;
        animation: mfxab-run 3.2s linear infinite;
        opacity: .7; transition: opacity .2s ease, stroke-width .2s ease;
    }
    .mfxab-beam:nth-of-type(2n) { animation-delay: -1.1s; }
    .mfxab-beam:nth-of-type(3n) { animation-delay: -2.2s; animation-duration: 3.8s; }
    .mfxab-beam[data-hot] { opacity: 1; stroke-width: 3.5; animation-duration: 1.5s; }
    .mfxab-node circle { fill: var(--mfxab-node); stroke: var(--mfxab-wire); stroke-width: 1.5; transition: stroke .2s ease, stroke-width .2s ease; }
    .mfxab-node[data-hot] circle { stroke: var(--mfxab-beam); stroke-width: 3; }
    .mfxab-name { text-anchor: middle; font: 700 15px var(--nx-font-display, system-ui); fill: var(--mfxab-ink); }
    .mfxab-node[data-hot] .mfxab-name { fill: var(--mfxab-beam); }
    .mfxab-city { text-anchor: middle; font: 500 10.5px var(--nx-font-display, system-ui); fill: var(--mfxab-ink-soft); }
    .mfxab-node { cursor: pointer; }
    .mfxab-node:focus { outline: none; }
    .mfxab-node:focus-visible circle { stroke: var(--mfxab-beam-2); stroke-width: 3; }
    .mfxab-chips { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; }
    .mfxab-chip {
        display: inline-flex; align-items: center; gap: .45rem; border: 1px solid var(--mfxab-line);
        border-radius: 999px; padding: .45rem .95rem; background: var(--mfxab-node);
        color: var(--mfxab-ink); font: 600 .78rem / 1 var(--nx-font-display, system-ui); cursor: pointer;
        transition: border-color .2s ease, color .2s ease;
    }
    .mfxab-chip i { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: var(--mfxab-wire); transition: background-color .2s ease; }
    .mfxab-chip[aria-pressed="true"] { border-color: var(--mfxab-beam); color: var(--mfxab-beam); }
    .mfxab-chip[aria-pressed="true"] i { background: var(--mfxab-beam); }
    .mfxab-chip:focus-visible { outline: 2px solid var(--mfxab-beam-2); outline-offset: 2px; }
    .mfxab-note { margin: 0; text-align: center; font-size: .78rem; color: var(--mfxab-ink-soft); }
    @media (prefers-reduced-motion: reduce) {
        .mfxab-beam { animation: none; opacity: .45; }
        .mfxab-beam[data-hot] { opacity: 1; }
    }
</style>

<div class="mfxab-root" x-data="{ hot: '', lit(id) { return this.hot === id || this.hot === 'hub' } }">
    <section class="pg-box" style="padding: 0; overflow: clip; gap: 1rem">
        <div class="mfxab-head" style="padding: 1.5rem 1.5rem 0">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('How Lumen Platform talks to itself', 'پلتفرم لومن چطور با خودش حرف می‌زند') }}</h3>
            <p>{{ $say('Five services around one orchestrator. The faint lines are the wiring; the moving light is traffic. Rest on a node and only its beams brighten and quicken.', 'پنج سرویس دور یک ارکستراتور. خطوط کم‌رنگ سیم‌کشی‌اند و نور متحرک، ترافیک. روی یک گره بایستید تا فقط پرتوهای خودش روشن‌تر و تندتر شوند.') }}</p>
        </div>

        <div class="mfxab-canvas">
            <svg class="mfxab-map" viewBox="0 0 640 310" role="img" aria-label="{{ $say('Lumen service map', 'نقشهٔ سرویس‌های لومن') }}">
                <defs>
                    <linearGradient id="mfxab-glow" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0" stop-color="#6366f1" stop-opacity="0" />
                        <stop offset=".7" stop-color="#6366f1" stop-opacity=".85" />
                        <stop offset="1" stop-color="#22d3ee" />
                    </linearGradient>
                </defs>

                @foreach ($nodes as $n)
                    <path class="mfxab-wire" d="{{ $beam($hub, $n) }}" />
                    <path class="mfxab-beam" stroke="url(#mfxab-glow)" d="{{ $beam($hub, $n) }}"
                        x-bind:data-hot="lit('{{ $n['id'] }}') ? '' : null" />
                @endforeach

                <g class="mfxab-node" role="button" tabindex="0" aria-label="{{ $say('Orchestrator hub', 'هاب ارکستراتور') }}"
                    x-bind:data-hot="hot === 'hub' ? '' : null"
                    x-on:pointerenter="hot = 'hub'" x-on:pointerleave="hot = ''"
                    x-on:focus="hot = 'hub'" x-on:blur="hot = ''" x-on:click="hot = hot === 'hub' ? '' : 'hub'">
                    <circle cx="{{ $hub['x'] }}" cy="{{ $hub['y'] }}" r="{{ $hub['r'] }}" />
                    <text class="mfxab-name" x="{{ $hub['x'] }}" y="{{ $hub['y'] - 2 }}">{{ $say('Hub', 'هاب') }}</text>
                    <text class="mfxab-city" x="{{ $hub['x'] }}" y="{{ $hub['y'] + 16 }}">{{ $say('Orchestrator', 'ارکستراتور') }}</text>
                </g>

                @foreach ($nodes as $n)
                    <g class="mfxab-node" role="button" tabindex="0"
                        aria-label="{{ $say($n['en'] . ' — ' . $n['cityEn'], $n['fa'] . ' — ' . $n['cityFa']) }}"
                        x-bind:data-hot="hot === '{{ $n['id'] }}' ? '' : null"
                        x-on:pointerenter="hot = '{{ $n['id'] }}'" x-on:pointerleave="hot = ''"
                        x-on:focus="hot = '{{ $n['id'] }}'" x-on:blur="hot = ''"
                        x-on:click="hot = hot === '{{ $n['id'] }}' ? '' : '{{ $n['id'] }}'">
                        <circle cx="{{ $n['x'] }}" cy="{{ $n['y'] }}" r="{{ $n['r'] }}" />
                        <text class="mfxab-name" x="{{ $n['x'] }}" y="{{ $n['y'] - 1 }}">{{ $say($n['en'], $n['fa']) }}</text>
                        <text class="mfxab-city" x="{{ $n['x'] }}" y="{{ $n['y'] + 15 }}">{{ $say($n['cityEn'], $n['cityFa']) }} · {{ $num($n['lat']) }}ms</text>
                    </g>
                @endforeach
            </svg>
        </div>

        <div class="mfxab-chips" style="padding: 0 1.5rem 1.5rem">
            <button type="button" class="mfxab-chip" x-bind:aria-pressed="hot === 'hub' ? 'true' : 'false'"
                x-on:click="hot = hot === 'hub' ? '' : 'hub'" x-on:pointerenter="hot = 'hub'" x-on:pointerleave="hot = ''">
                <i aria-hidden="true"></i>{{ $say('Orchestrator', 'ارکستراتور') }}
            </button>
            @foreach ($nodes as $n)
                <button type="button" class="mfxab-chip" x-bind:aria-pressed="hot === '{{ $n['id'] }}' ? 'true' : 'false'"
                    x-on:click="hot = hot === '{{ $n['id'] }}' ? '' : '{{ $n['id'] }}'"
                    x-on:pointerenter="hot = '{{ $n['id'] }}'" x-on:pointerleave="hot = ''">
                    <i aria-hidden="true"></i>{{ $say($n['en'], $n['fa']) }}
                </button>
            @endforeach
        </div>

        <p class="mfxab-note" style="padding: 0 1.5rem 1.5rem">
            {{ $say('Chips do the same job for touch and keyboard — every node is focusable and announces itself.', 'چیپ‌ها همان کار را برای لمس و کیبورد می‌کنند — هر گره فوکوس‌پذیر است و خودش را معرفی می‌کند.') }}
        </p>
    </section>
</div>
