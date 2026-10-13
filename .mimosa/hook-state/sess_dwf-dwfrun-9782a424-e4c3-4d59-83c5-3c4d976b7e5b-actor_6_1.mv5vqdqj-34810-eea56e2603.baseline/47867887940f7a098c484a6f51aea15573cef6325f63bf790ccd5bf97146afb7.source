{{--
    mask-lines's real scenario: the hero of Fathom 2.0, a deep-work calendar —
    every headline line lives in its own overflow-clip mask and rises from
    112% below on the gentle spring, line after line at 130ms; the eyebrow,
    sub and buttons follow with a blur-and-rise of their own. Replay runs
    the whole entrance again; hovering a line replays just that line.
    Persian lines are whole phrases — never split into letters.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Each line: an array of [text, isAccent] segments.
    $lines = [
        ['en' => [['We make '], ['quiet', true], [' software']], 'fa' => [['نرم‌افزارِ '], ['آرام', true]]],
        ['en' => [['for loud teams.']], 'fa' => [['برای تیم‌های '], ['پرصدا', true], ['.']]],
    ];
@endphp

<style>
    .enml-wrap {
        --enml-fg: #18181b; --enml-muted: #71717a; --enml-border: #e4e4e7;
        --enml-bg: #fafafa; --enml-accent: #4338ca; --enml-btn: #18181b; --enml-btn-ink: #fafafa;
        display: grid; gap: 1rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--enml-fg);
    }
    html[data-theme="dark"] .enml-wrap {
        --enml-fg: #fafafa; --enml-muted: #a1a1aa; --enml-border: #27272a;
        --enml-bg: #131316; --enml-accent: #a5b4fc; --enml-btn: #fafafa; --enml-btn-ink: #18181b;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .enml-wrap {
            --enml-fg: #fafafa; --enml-muted: #a1a1aa; --enml-border: #27272a;
            --enml-bg: #131316; --enml-accent: #a5b4fc; --enml-btn: #fafafa; --enml-btn-ink: #18181b;
        }
    }
    .enml-hero { display: grid; gap: 1.15rem; justify-items: start;
                 padding: clamp(1.4rem, 5%, 3rem); border-radius: 1.25rem;
                 border: 1px solid var(--enml-border); background: var(--enml-bg); }

    .enml-chip { display: inline-flex; align-items: center; gap: .45rem;
                 padding: .3rem .8rem; border-radius: 999px; font-size: .72rem; font-weight: 700;
                 border: 1px solid var(--enml-border); color: var(--enml-muted); }
    .enml-chip i { inline-size: .5rem; aspect-ratio: 1; border-radius: 50%;
                   background: var(--enml-accent); }

    .enml-h1 { margin: 0; font-size: clamp(1.9rem, 6.5vw, 3.4rem); line-height: 1.14;
               font-weight: 800; letter-spacing: -.015em; }
    .enml-mask { display: block; overflow: clip; padding-block-end: .14em; margin-block-end: -.14em; }
    .enml-line { display: inline-block; translate: 0 112%; cursor: default;
                 transition: translate .85s var(--nx-spring-gentle);
                 transition-delay: calc(120ms + var(--l, 0) * 130ms); }
    .enml-line[data-up="true"] { translate: 0 0; }
    .enml-line:not([data-up="true"]) { transition: none; }
    .enml-seg { font-style: normal; }
    .enml-seg[data-accent="true"] { font-style: italic; font-family: Georgia, 'Times New Roman', serif;
                                    font-weight: 600; color: var(--enml-accent); letter-spacing: 0; }
    .enml-h1:lang(fa) .enml-seg[data-accent="true"] { font-style: normal; font-family: inherit;
                                                       font-weight: 900; }

    .enml-sub { margin: 0; max-inline-size: 44ch; font-size: .92rem; line-height: 1.85;
                color: var(--enml-muted); }
    .enml-cta { display: flex; flex-wrap: wrap; gap: .6rem; }
    .enml-btn { padding: .62rem 1.25rem; border-radius: 999px; font: 700 .84rem/1 inherit;
                cursor: pointer; border: 1px solid transparent; }
    .enml-btn[data-kind="solid"] { background: var(--enml-btn); color: var(--enml-btn-ink); }
    .enml-btn[data-kind="ghost"] { border-color: var(--enml-border); color: inherit; background: transparent; }

    .enml-follow { opacity: 0; translate: 0 14px; filter: blur(5px);
        transition: opacity .6s var(--nx-spring-gentle), translate .6s var(--nx-spring-gentle),
                    filter .6s var(--nx-spring-gentle);
        transition-delay: calc(520ms + var(--f, 0) * 120ms); }
    .enml-hero[data-live="true"] .enml-follow { opacity: 1; translate: 0 0; filter: blur(0); }
    .enml-hero:not([data-live="true"]) .enml-follow { transition: none; }

    .enml-ctl { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1.4rem; }
    .enml-again { display: inline-flex; align-items: center; gap: .45rem; padding: .55rem 1.1rem;
        border-radius: 999px; border: 1px solid var(--enml-border); background: var(--enml-bg);
        color: inherit; font: 700 .8rem/1 inherit; cursor: pointer;
        transition: border-color .15s ease, color .15s ease; }
    .enml-again:hover { border-color: var(--enml-accent); color: var(--enml-accent); }
    .enml-again:focus-visible { outline: 2px solid var(--enml-accent); outline-offset: 2px; }
    .enml-again svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .enml-hint { margin: 0; font-size: .74rem; color: var(--enml-muted); }
    @media (prefers-reduced-motion: reduce) {
        .enml-line { transition: none; translate: 0 0; }
        .enml-follow { transition: none; opacity: 1; translate: 0 0; filter: none; }
        .enml-again { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        live: false, io: null,
        init() {
            this.io = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) { this.play(); this.io.disconnect(); }
            }, { threshold: 0.3 });
            this.io.observe(this.$refs.hero);
        },
        destroy() { if (this.io) this.io.disconnect(); },
        play() {
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.live = true;
                window.dispatchEvent(new CustomEvent('enml-go'));
                return;
            }
            this.live = false;
            window.dispatchEvent(new CustomEvent('enml-reset'));
            requestAnimationFrame(() => requestAnimationFrame(() => {
                window.dispatchEvent(new CustomEvent('enml-go'));
                this.live = true;
            }));
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Headlines that rise out of their masks', 'تیترهایی که از ماسک خودشان بالا می‌آیند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Fathom 2.0’s hero: each headline line sits in its own overflow-clip mask and rises from 112% below on the gentle spring — the next line 130ms after the last — then the eyebrow, sub and buttons follow with a softer blur-rise. Replay the entrance, or hover a single line to replay just that one.', 'هیروی فذم ۲٫۰: هر خط تیتر در ماسک overflow-clip خودش نشسته و از ۱۱۲٪ پایین با فنر gentle بالا می‌آید — خط بعد ۱۳۰ms بعد از خط قبل — بعد چیک، زیرتیتر و دکمه‌ها با محو-بلور نرم‌تر دنبال می‌کنند. ورود را دوباره اجرا کنید، یا روی یک خط هاور کنید تا فقط همان بازپخش شود.') }}
        </p>
    </div>

    <div class="enml-wrap">
        <div class="enml-hero" x-ref="hero" x-bind:data-live="live ? 'true' : 'false'">
            <p class="enml-chip enml-follow" style="--f: 0; margin: 0">
                <i aria-hidden="true"></i>
                {{ $say('Fathom 2.0 — now shipping', 'فذم ۲٫۰ — منتشر شد') }}
            </p>
            <h2 class="enml-h1">
                @foreach ($lines as $i => $line)
                    <span class="enml-mask">
                        <span class="enml-line"
                            x-data="{
                                up: false,
                                again() {
                                    if (!this.up) return;
                                    this.up = false;
                                    requestAnimationFrame(() => requestAnimationFrame(() => (this.up = true)));
                                },
                            }"
                            x-bind:data-up="up ? 'true' : 'false'"
                            @enml-go.window="up = true"
                            @enml-reset.window="up = false"
                            @pointerenter="again()"
                            style="--l: {{ $i }}">
                            @foreach ($fa ? $line['fa'] : $line['en'] as $seg)
                                <span class="enml-seg" data-accent="{{ ! empty($seg[1]) ? 'true' : 'false' }}">{{ $seg[0] }}</span>
                            @endforeach
                        </span>
                    </span>
                @endforeach
            </h2>
            <p class="enml-sub enml-follow" style="--f: 1">
                {{ $say('A calendar that understands meetings and defends focus — built for teams whose calendar is louder than their to-do list.', 'تقویمی که جلسات را می‌فهمد و تمرکز را محافظت می‌کند — برای تیم‌هایی که تقویمشان پرصدا‌تر از فهرست کارهایشان است.') }}
            </p>
            <div class="enml-cta enml-follow" style="--f: 2">
                <button type="button" class="enml-btn" data-kind="solid">{{ $say('Book a demo', 'دمو رزرو کن') }}</button>
                <button type="button" class="enml-btn" data-kind="ghost">{{ $say('Read the manifesto', 'مانیفست را بخوان') }}</button>
            </div>
        </div>

        <div class="enml-ctl">
            <button type="button" class="enml-again" x-on:click="play()">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                {{ $say('Rise again', 'دوباره بالا بیا') }}
            </button>
            <p class="enml-hint">{{ $say('each mask: overflow clip · line: 112% → 0 on --nx-spring-gentle · 130ms per line · hover a line for a solo', 'هر ماسک: overflow clip · خط: ۱۱۲٪ → ۰ با --nx-spring-gentle · ۱۳۰ms بین خطوط · هاور روی هر خط برای بازپخش انفرادی') }}</p>
        </div>
    </div>
</section>
