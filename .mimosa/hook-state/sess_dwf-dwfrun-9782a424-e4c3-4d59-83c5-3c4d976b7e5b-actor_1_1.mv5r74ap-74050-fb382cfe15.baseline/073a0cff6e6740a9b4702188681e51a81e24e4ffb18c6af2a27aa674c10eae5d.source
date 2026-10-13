{{--
    metaballs' real scenario: the hero of "Meld", a workspace app — five gooey
    balls wander behind the headline on one SVG group: feGaussianBlur smears
    them and an feColorMatrix alpha threshold snaps the smear back into
    liquid, so blobs merge and split as their paths cross. The animation is
    pure transform keyframes (one period per ball), parked off screen with an
    IntersectionObserver and frozen under prefers-reduced-motion. The
    segmented toggle flips the section between its two shipped versions:
    paper-light with indigo ink and night-dark with luminous colour.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ambm-root {
        --ambm-ink: #1c1917; --ambm-muted: #78716c;
        --ambm-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: 1rem; font-family: var(--ambm-font); color: var(--ambm-ink);
    }
    html[data-theme="dark"] .ambm-root { --ambm-ink: #fafaf9; --ambm-muted: #a8a29e; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ambm-root { --ambm-ink: #fafaf9; --ambm-muted: #a8a29e; }
    }
    .ambm-stage {
        --ambm-bg: #f6f7fb; --ambm-ink2: #1e1b4b; --ambm-sub: #4c4a6e;
        --ambm-chip: rgba(30, 27, 75, .07); --ambm-chip-border: rgba(30, 27, 75, .18);
        --ambm-cta-bg: #4f46e5; --ambm-cta-ink: #ffffff;
        --ambm-b1: #6366f1; --ambm-b2: #8b5cf6; --ambm-b3: #a855f7;
        --ambm-b4: #4f46e5; --ambm-b5: #7c3aed;
        position: relative; overflow: clip; border-radius: 1.25rem;
        min-block-size: 24rem; display: grid; place-content: center; justify-items: center;
        gap: 1rem; padding: 3rem 1.25rem; text-align: center;
        background: var(--ambm-bg); color: var(--ambm-ink2);
        transition: background-color .35s ease, color .35s ease;
    }
    .ambm-stage[data-variant="dark"] {
        --ambm-bg: #0b1020; --ambm-ink2: #e0e7ff; --ambm-sub: #a5b4fc;
        --ambm-chip: rgba(224, 231, 255, .09); --ambm-chip-border: rgba(224, 231, 255, .22);
        --ambm-cta-bg: #e0e7ff; --ambm-cta-ink: #0b1020;
        --ambm-b1: #22d3ee; --ambm-b2: #818cf8; --ambm-b3: #e879f9;
        --ambm-b4: #38bdf8; --ambm-b5: #a78bfa;
    }
    .ambm-svg { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; display: block; }
    .ambm-blobs { mix-blend-mode: normal; }
    .ambm-stage[data-variant="dark"] .ambm-blobs { mix-blend-mode: screen; }
    .ambm-ball { transform-box: fill-box; transform-origin: center; will-change: transform; }
    .ambm-stage[data-running="false"] .ambm-ball { animation-play-state: paused; }
    .ambm-ball.b1 { fill: var(--ambm-b1); animation: ambm-w1 24s ease-in-out infinite alternate; }
    .ambm-ball.b2 { fill: var(--ambm-b2); animation: ambm-w2 19s ease-in-out infinite alternate; }
    .ambm-ball.b3 { fill: var(--ambm-b3); animation: ambm-w3 28s ease-in-out infinite alternate; }
    .ambm-ball.b4 { fill: var(--ambm-b4); animation: ambm-w4 22s ease-in-out infinite alternate; }
    .ambm-ball.b5 { fill: var(--ambm-b5); animation: ambm-w5 31s ease-in-out infinite alternate; }
    @keyframes ambm-w1 {
        from { translate: -120px 30px; scale: 1; }
        to { translate: 210px -60px; scale: 1.3; }
    }
    @keyframes ambm-w2 {
        from { translate: 160px -40px; scale: 1.15; }
        to { translate: -230px 70px; scale: .9; }
    }
    @keyframes ambm-w3 {
        from { translate: -90px -70px; scale: .95; }
        to { translate: 140px 90px; scale: 1.25; }
    }
    @keyframes ambm-w4 {
        from { translate: 130px 60px; scale: 1.05; }
        to { translate: -160px -80px; scale: 1.2; }
    }
    @keyframes ambm-w5 {
        from { translate: -60px 80px; scale: 1.2; }
        to { translate: 190px -50px; scale: .9; }
    }
    .ambm-copy { position: relative; z-index: 1; display: grid; justify-items: center; gap: 1rem; }
    .ambm-eyebrow {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .35rem .85rem; border-radius: 999px;
        background: var(--ambm-chip); border: 1px solid var(--ambm-chip-border);
        font-size: .76rem; font-weight: 600; color: var(--ambm-sub);
        backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    }
    .ambm-eyebrow svg { inline-size: .85rem; block-size: .85rem; stroke: currentColor; stroke-width: 2;
                        fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ambm-h { max-inline-size: 32rem; margin: 0; font-size: clamp(1.6rem, 4.5vw, 2.4rem);
              font-weight: 700; line-height: 1.25; letter-spacing: -.01em; }
    .ambm-p { max-inline-size: 28rem; margin: 0; color: var(--ambm-sub); font-size: .95rem; line-height: 1.7; }
    .ambm-cta-row { display: flex; flex-wrap: wrap; justify-content: center; gap: .6rem; margin-block-start: .4rem; }
    .ambm-cta {
        display: inline-flex; align-items: center; gap: .45rem; padding: .65rem 1.3rem;
        border-radius: 999px; border: 1px solid transparent; cursor: pointer;
        font: 600 .85rem var(--ambm-font); text-decoration: none;
        transition: translate .15s ease, background-color .35s ease;
    }
    .ambm-cta:hover { translate: 0 -1px; }
    .ambm-cta:focus-visible { outline: 2px solid var(--ambm-sub); outline-offset: 2px; }
    .ambm-cta-primary { background: var(--ambm-cta-bg); color: var(--ambm-cta-ink); }
    .ambm-cta-ghost { background: var(--ambm-chip); color: var(--ambm-ink2); border-color: var(--ambm-chip-border); }
    .ambm-cta svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ambm-variant {
        display: inline-flex; align-items: center; gap: .3rem; margin-block-start: .5rem;
        padding: .3rem; border-radius: 999px;
        background: var(--ambm-chip); border: 1px solid var(--ambm-chip-border);
    }
    .ambm-vbtn {
        display: inline-flex; align-items: center; gap: .4rem; padding: .45rem .95rem;
        border-radius: 999px; border: 0; cursor: pointer;
        background: none; color: var(--ambm-sub); font: 600 .78rem var(--ambm-font);
        transition: background-color .2s ease, color .2s ease;
    }
    .ambm-vbtn:hover { color: var(--ambm-ink2); }
    .ambm-vbtn:focus-visible { outline: 2px solid var(--ambm-sub); outline-offset: 2px; }
    .ambm-vbtn[aria-pressed="true"] { background: var(--ambm-cta-bg); color: var(--ambm-cta-ink); }
    .ambm-vbtn svg { inline-size: .85rem; block-size: .85rem; stroke: currentColor; stroke-width: 2;
                     fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ambm-hint { margin: 0; font: 400 .74rem/1.6 ui-monospace, monospace; color: var(--ambm-muted); text-align: center; }
    @media (prefers-reduced-motion: reduce) {
        .ambm-ball { animation: none; }
        .ambm-cta, .ambm-vbtn { transition: none; }
        .ambm-cta:hover { translate: 0 0; }
        .ambm-stage { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        io: null,
        running: true,
        dark: false,
        init() {
            this.io = new IntersectionObserver((entries) => {
                this.running = entries[0].isIntersecting;
            }, { threshold: 0.15 });
            this.io.observe(this.$refs.stage);
        },
        destroy() { if (this.io) this.io.disconnect(); },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A background that pools and splits', 'پس‌زمینه‌ای که جمع و پخش می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Five balls run pure transform keyframes while one SVG group smears them with feGaussianBlur and snaps the smear back into liquid with an feColorMatrix alpha threshold — neighbours melt into each other and pull apart as their paths cross, and no pixel is re-laid. The toggle ships both section versions: paper-light with indigo ink, night-dark with luminous colour. The goo parks off screen and freezes to its final shape under reduced motion.', 'پنج گوی با keyframeهای خالصِ transform حرکت می‌کنند و یک گروه SVG آن‌ها را با feGaussianBlur محو و با آستانهٔ آلفای feColorMatrix دوباره به مایع برمی‌گرداند — همسایه‌ها در هم ذوب می‌شوند و با عبور مسیرها از هم می‌کشند، بدون بازچینش پیکسل. کلید، هر دو نسخهٔ بخش را تحویل می‌دهد: کاغذیِ روشن با جوهر نیلی و شبانه با رنگ نورانی. گو بیرون از دید پارک و در حرکت کاهش‌یافته به شکل نهایی‌اش جمود می‌کند.') }}
        </p>
    </div>

    <div class="ambm-stage" x-ref="stage"
        x-bind:data-variant="dark ? 'dark' : 'light'"
        x-bind:data-running="running ? 'true' : 'false'">
        <svg class="ambm-svg" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <defs>
                <filter id="ambm-goo" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="16" result="ambm-blur" />
                    <feColorMatrix in="ambm-blur"
                        values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 24 -12" />
                </filter>
            </defs>
            <g class="ambm-blobs" filter="url(#ambm-goo)">
                <circle class="ambm-ball b1" cx="330" cy="300" r="86" />
                <circle class="ambm-ball b2" cx="620" cy="190" r="104" />
                <circle class="ambm-ball b3" cx="900" cy="300" r="92" />
                <circle class="ambm-ball b4" cx="480" cy="150" r="72" />
                <circle class="ambm-ball b5" cx="760" cy="350" r="78" />
            </g>
        </svg>
        <div class="ambm-copy">
            <span class="ambm-eyebrow">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v4"/><path d="M12 17v4"/><path d="M4.9 4.9l2.8 2.8"/><path d="M16.3 16.3l2.8 2.8"/><path d="M3 12h4"/><path d="M17 12h4"/><path d="M4.9 19.1l2.8-2.8"/><path d="M16.3 7.7l2.8-2.8"/></svg>
                {{ $say('Meld · the living workspace', 'Meld · فضای کاری زنده') }}
            </span>
            <h4 class="ambm-h">{{ $say('Where work melds together', 'جایی که کارها در هم می‌آمیزند') }}</h4>
            <p class="ambm-p">{{ $say('Meld pools your docs, threads and tasks into one living surface — search once and everything your team ever wrote surfaces with it.', 'Meld سندها، گفتگوها و کارها را در یک سطح زنده جمع می‌کند — یک بار جست‌وجو کنید و هرچه تیم‌تان نوشته همراهش بالا می‌آید.') }}</p>
            <div class="ambm-cta-row">
                <a class="ambm-cta ambm-cta-primary" href="#">{{ $say('Start a workspace', 'یک فضای کاری بسازید') }}</a>
                <a class="ambm-cta ambm-cta-ghost" href="#">
                    {{ $say('Watch the 2-min tour', 'تور ۲ دقیقه‌ای را ببینید') }}
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m8 5 11 7-11 7Z"/></svg>
                </a>
            </div>
            <div class="ambm-variant" role="group" aria-label="{{ $say('Section version', 'نسخهٔ بخش') }}">
                <button type="button" class="ambm-vbtn" x-bind:aria-pressed="dark ? 'false' : 'true'" x-on:click="dark = false">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.9 4.9 1.4 1.4"/><path d="m17.7 17.7 1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m4.9 19.1 1.4-1.4"/><path d="m17.7 6.3 1.4-1.4"/></svg>
                    {{ $say('Light', 'روشن') }}
                </button>
                <button type="button" class="ambm-vbtn" x-bind:aria-pressed="dark ? 'true' : 'false'" x-on:click="dark = true">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                    {{ $say('Dark', 'تیره') }}
                </button>
            </div>
        </div>
    </div>

    <p class="ambm-hint">
        {{ $say('5 balls · feGaussianBlur σ=16 + feColorMatrix α×24−12 · transform-only wander · light & dark section versions · parks off screen', '۵ گوی · feGaussianBlur σ=۱۶ + feColorMatrix α×۲۴−۱۲ · سرگردانی فقط با transform · دو نسخهٔ روشن و تیرهٔ بخش · پارک بیرون از دید') }}
    </p>
</section>
