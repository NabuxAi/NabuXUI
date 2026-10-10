{{--
    The timeline scene's real scenario: the launch card of Orbit 3.0 — one
    play button runs the whole choreography (eyebrow, spring-settled title,
    staggered items, a self-drawing line, the CTA pop), the labelled rail
    underneath mirrors each step as it lands, and replay resets to zero,
    waits one frame for styles to settle, then plays it all again.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $items = [
        ['fa' => $num('42') . '٪ سریع‌تر', 'en' => $num('42') . '% faster'],
        ['fa' => 'همگام‌سازی آفلاین', 'en' => 'Offline sync'],
        ['fa' => $num('4') . ' زبان تازه', 'en' => $num('4') . ' new languages'],
    ];
    $rail = [
        ['fa' => 'تیتر', 'en' => 'Title'],
        ['fa' => 'آیتم‌ها', 'en' => 'Items'],
        ['fa' => 'خط', 'en' => 'Line'],
        ['fa' => 'دکمه', 'en' => 'Button'],
    ];
@endphp

<style>
    .mfxs-root {
        --mfxs-ink: #16181d; --mfxs-ink-soft: #5c6270;
        --mfxs-line: #d9dde4; --mfxs-surface: #ffffff; --mfxs-floor: #f7f8fa;
        --mfxs-accent: #4f46e5; --mfxs-accent-2: #22d3ee;
        display: grid; gap: 1.25rem; justify-items: center;
        font-family: var(--nx-font-display, system-ui);
    }
    html[data-theme="dark"] .mfxs-root {
        --mfxs-ink: #f2f4f8; --mfxs-ink-soft: #9aa1b0;
        --mfxs-line: #2e3243; --mfxs-surface: #1a1d2b; --mfxs-floor: #12141d;
        --mfxs-accent: #8b83ff; --mfxs-accent-2: #22d3ee;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mfxs-root {
            --mfxs-ink: #f2f4f8; --mfxs-ink-soft: #9aa1b0;
            --mfxs-line: #2e3243; --mfxs-surface: #1a1d2b; --mfxs-floor: #12141d;
            --mfxs-accent: #8b83ff; --mfxs-accent-2: #22d3ee;
        }
    }
    .mfxs-head { display: grid; gap: .4rem; text-align: center; justify-items: center; }
    .mfxs-head p { margin: 0; max-inline-size: 56ch; color: var(--nx-text-muted); }
    .mfxs-stage {
        display: grid; gap: 1rem; justify-items: center; text-align: center;
        inline-size: min(100%, 36rem); padding: clamp(1.75rem, 5vw, 2.75rem) clamp(1.25rem, 4vw, 2rem);
        border: 1px solid var(--mfxs-line); border-radius: 1.25rem;
        background:
            radial-gradient(130% 100% at 50% 0%, rgba(99, 102, 241, .08), transparent 55%),
            var(--mfxs-floor);
        overflow: clip;
    }
    .mfxs-piece {
        opacity: 0; translate: 0 20px; scale: .9;
        transition: opacity .55s ease, translate .6s cubic-bezier(.34, 1.56, .64, 1), scale .6s cubic-bezier(.34, 1.56, .64, 1);
    }
    .mfxs-stage:is([data-phase="run"], [data-phase="done"]) .mfxs-piece { opacity: 1; translate: none; scale: 1; }
    .mfxs-eyebrow {
        margin: 0; font: 600 .72rem / 1 var(--nx-font-display, system-ui);
        letter-spacing: .16em; text-transform: uppercase; color: var(--mfxs-ink-soft);
    }
    .mfxs-stage:is([data-phase="run"], [data-phase="done"]) .mfxs-eyebrow { transition-delay: .05s; }
    .mfxs-title {
        margin: 0; font: 800 clamp(2.1rem, 7vw, 3.4rem) / 1.1 var(--nx-font-display, system-ui);
        color: var(--mfxs-ink); text-wrap: balance;
    }
    .mfxs-stage:is([data-phase="run"], [data-phase="done"]) .mfxs-title { transition-delay: .18s; }
    .mfxs-svg { inline-size: min(18rem, 72vw); block-size: auto; }
    .mfxs-svg path {
        fill: none; stroke: var(--mfxs-accent); stroke-width: 2.5; stroke-linecap: round;
        stroke-dasharray: 100; stroke-dashoffset: 100;
        transition: stroke-dashoffset .9s ease-out .8s;
    }
    .mfxs-stage:is([data-phase="run"], [data-phase="done"]) .mfxs-svg path { stroke-dashoffset: 0; }
    .mfxs-items { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; margin: 0; padding: 0; list-style: none; }
    .mfxs-items li {
        padding: .5rem .95rem; border: 1px solid var(--mfxs-line); border-radius: 999px;
        background: var(--mfxs-surface); font: 600 .82rem / 1 var(--nx-font-display, system-ui); color: var(--mfxs-ink);
    }
    .mfxs-stage:is([data-phase="run"], [data-phase="done"]) .mfxs-items li:nth-child(1) { transition-delay: .42s; }
    .mfxs-stage:is([data-phase="run"], [data-phase="done"]) .mfxs-items li:nth-child(2) { transition-delay: .54s; }
    .mfxs-stage:is([data-phase="run"], [data-phase="done"]) .mfxs-items li:nth-child(3) { transition-delay: .66s; }
    .mfxs-cta {
        margin-block-start: .35rem; border: 0; border-radius: 999px; padding: .9rem 1.7rem;
        background: var(--mfxs-ink); color: var(--mfxs-floor);
        font: 700 .92rem / 1 var(--nx-font-display, system-ui); cursor: pointer;
    }
    .mfxs-cta:focus-visible { outline: 2px solid var(--mfxs-accent-2); outline-offset: 3px; }
    .mfxs-stage:is([data-phase="run"], [data-phase="done"]) .mfxs-cta { transition-delay: 1.05s; }
    .mfxs-note { margin: 0; font-size: .76rem; color: var(--mfxs-ink-soft); min-block-size: 1.2em; }
    .mfxs-rail { display: flex; flex-wrap: wrap; justify-content: center; gap: 1.1rem; margin-block-start: .35rem; }
    .mfxs-step { display: inline-flex; align-items: center; gap: .45rem; font-size: .74rem; color: var(--mfxs-ink-soft); }
    .mfxs-step i {
        inline-size: .6rem; aspect-ratio: 1; border-radius: 50%; background: var(--mfxs-line);
        transition: background-color .2s ease, scale .35s cubic-bezier(.34, 1.56, .64, 1);
    }
    .mfxs-step[data-on] { color: var(--mfxs-ink); }
    .mfxs-step[data-on] i { background: var(--mfxs-accent); scale: 1.4; }
    .mfxs-controls { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: .75rem 1.25rem; }
    .mfxs-play {
        display: inline-flex; align-items: center; gap: .5rem; border: 1px solid var(--mfxs-accent);
        border-radius: 999px; padding: .65rem 1.45rem; background: var(--mfxs-surface);
        color: var(--mfxs-accent); font: 700 .85rem / 1 var(--nx-font-display, system-ui); cursor: pointer;
        transition: background-color .2s ease, color .2s ease;
    }
    .mfxs-play:hover { background: var(--mfxs-accent); color: #fff; }
    .mfxs-play:focus-visible { outline: 2px solid var(--mfxs-accent-2); outline-offset: 2px; }
    .mfxs-status { font-size: .78rem; color: var(--mfxs-ink-soft); }
    .mfxs-root [x-cloak] { display: none !important; }
    @media (prefers-reduced-motion: reduce) {
        .mfxs-piece, .mfxs-svg path, .mfxs-step i { transition: none; }
        .mfxs-piece { opacity: 1; translate: none; scale: 1; }
        .mfxs-svg path { stroke-dashoffset: 0; }
    }
</style>

<div class="mfxs-root"
    x-data="{
        phase: 'idle', step: 0, launched: false, timers: [],
        clear() { this.timers.forEach(clearTimeout); this.timers = [] },
        play() {
            const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches
            this.clear(); this.launched = false
            if (reduced) { this.step = 4; this.phase = 'done'; return }
            this.step = 0; this.phase = 'idle'
            requestAnimationFrame(() => requestAnimationFrame(() => {
                this.phase = 'run'
                const at = [180, 420, 800, 1050]
                at.forEach((ms, i) => this.timers.push(setTimeout(() => this.step = i + 1, ms)))
                this.timers.push(setTimeout(() => this.phase = 'done', 1900))
            }))
        },
        init() { this.playBound = () => this.play() },
        destroy() { this.clear() },
    }">
    <section class="pg-box" style="padding: 0; overflow: clip; gap: 1.25rem">
        <div class="mfxs-head" style="padding: 1.5rem 1.5rem 0">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The launch card that introduces itself', 'کارت رونمایی که خودش را معرفی می‌کند') }}</h3>
            <p>{{ $say('Press play and the timeline runs once, end to end: title, items, line, button — each on its own beat with a spring that overshoots and settles. The rail under the card lights each step as it lands.', 'پخش را بزنید تا تایم‌لاین یک‌بار کامل اجرا شود: تیتر، آیتم‌ها، خط، دکمه — هرکدام در ضربِ خودش با فنری که کمی می‌گذرد و می‌نشیند. ریل زیر کارت هر پله را همان‌جا که فرود می‌آید روشن می‌کند.') }}</p>
        </div>

        <div class="mfxs-stage" x-bind:data-phase="phase" style="margin-inline: 1.5rem">
            <p class="mfxs-piece mfxs-eyebrow">{{ $say('Release notes · 3.0', 'یادداشت انتشار · ۳٫۰') }}</p>
            <h4 class="mfxs-piece mfxs-title">{{ $say('Orbit 3.0', 'Orbit ۳٫۰') }}</h4>
            <svg class="mfxs-svg" viewBox="0 0 320 24" aria-hidden="true">
                <path pathLength="100" d="M4 16 C 80 4 240 4 316 14" />
            </svg>
            <ul class="mfxs-items">
                @foreach ($items as $item)
                    <li class="mfxs-piece">{{ $say($item['en'], $item['fa']) }}</li>
                @endforeach
            </ul>
            <button type="button" class="mfxs-piece mfxs-cta" x-on:click="launched = true">
                {{ $say('Start Orbit 3.0', 'شروع Orbit ۳٫۰') }}
            </button>
            <p class="mfxs-note" x-show="launched" x-cloak>
                {{ $say('The real launch would open now — this one is only the scene.', 'رونمایی واقعی همین حالا باز می‌شد — اینجا فقط صحنه است.') }}
            </p>
            <div class="mfxs-rail" aria-hidden="true">
                @foreach ($rail as $i => $label)
                    <span class="mfxs-step" x-bind:data-on="step > {{ $i }} ? '' : null"><i></i>{{ $say($label['en'], $label['fa']) }}</span>
                @endforeach
            </div>
        </div>

        <div class="mfxs-controls" style="padding: 0 1.5rem 1.5rem">
            <button type="button" class="mfxs-play" x-on:click="play()">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="inline-size: .85rem; block-size: .85rem">
                    <path d="M7 4.5v15l13-7.5z" />
                </svg>
                <span x-text="phase === 'idle' ? @js($say('Play', 'پخش')) : @js($say('Replay', 'ری‌پلی'))"></span>
            </button>
            <span class="mfxs-status" x-text="
                phase === 'idle' ? @js($say('Ready — four steps are waiting.', 'آماده — چهار پله در انتظارند.'))
                : phase === 'run' ? @js($say('Running the timeline…', 'در حال اجرای تایم‌لاین…'))
                : @js($say('Done — replay any time.', 'تمام — هر وقت خواستید دوباره.'))"></span>
        </div>
    </section>
</div>
