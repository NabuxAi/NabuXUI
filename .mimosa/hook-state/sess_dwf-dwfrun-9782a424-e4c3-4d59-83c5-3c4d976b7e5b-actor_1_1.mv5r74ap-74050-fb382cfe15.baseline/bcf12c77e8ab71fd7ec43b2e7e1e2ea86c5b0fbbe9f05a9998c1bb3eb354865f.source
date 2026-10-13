{{--
    rotate-cycle-3d's real scenario: the hero line of a global design-ops
    product — the sentence stays put while its keyword flips on the X axis
    through a four-word loop (rotateX out at +90°, swap under the fold, back
    in from −90°). A hidden same-font span measures each incoming word so
    the slot's width eases instead of jolting the line. Hover holds the
    timer, the replay button restarts the loop, the dots jump straight to a
    word, an IntersectionObserver rests the cycle off screen and reduced
    motion trades the flip for an instant swap.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $words = $fa
        ? ['سریع‌تر', 'باهم', 'همه‌جا', 'ساده‌تر']
        : ['faster', 'together', 'anywhere', 'wiser'];
    $lead = $say('Ship your design system', 'سیستم طراحی‌تان را بسازید');
    $tail = $say('than ever.', 'از همیشه.');
    $kicker = $say('NABUXUI · DESIGNOPS', 'نبوکس‌یوآی · دیزاین‌اپس');
@endphp

<style>
    .knrc-root {
        --knrc-bg: #ffffff; --knrc-ink: #1c1917; --knrc-muted: #78716c;
        --knrc-border: #e7e5e4; --knrc-card: #fafaf9; --knrc-accent: #7c3aed;
        --knrc-accent-soft: color-mix(in oklab, #7c3aed 12%, #ffffff);
        display: grid; gap: 1.25rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--knrc-ink);
    }
    html[data-theme="dark"] .knrc-root {
        --knrc-bg: #171514; --knrc-ink: #fafaf9; --knrc-muted: #a8a29e;
        --knrc-border: #292524; --knrc-card: #1c1917; --knrc-accent: #a78bfa;
        --knrc-accent-soft: color-mix(in oklab, #a78bfa 16%, #171514);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .knrc-root {
            --knrc-bg: #171514; --knrc-ink: #fafaf9; --knrc-muted: #a8a29e;
            --knrc-border: #292524; --knrc-card: #1c1917; --knrc-accent: #a78bfa;
            --knrc-accent-soft: color-mix(in oklab, #a78bfa 16%, #171514);
        }
    }
    .knrc-hero {
        position: relative; display: grid; gap: 1.1rem; justify-items: center;
        text-align: center; padding: clamp(1.5rem, 4vw, 2.6rem) 1.25rem;
        border: 1px solid var(--knrc-border); border-radius: 1.25rem;
        background:
            radial-gradient(120% 100% at 50% 0%, var(--knrc-accent-soft), transparent 60%),
            var(--knrc-bg);
        overflow: clip; cursor: default;
    }
    .knrc-kicker { margin: 0; font-size: .74rem; font-weight: 700;
                   letter-spacing: .14em; color: var(--knrc-muted); }
    .knrc-line {
        margin: 0; max-inline-size: 22ch; font-weight: 900; line-height: 1.6;
        font-size: clamp(1.7rem, 7vw, 3.1rem); letter-spacing: .01em;
        text-wrap: balance;
    }
    .knrc-stage {
        display: inline-block; perspective: 600px; white-space: nowrap;
        vertical-align: bottom; text-align: start;
        inline-size: 4ch; /* fitted by JS */
        transition: inline-size .42s cubic-bezier(.3, .7, .2, 1);
    }
    .knrc-word {
        display: inline-block; transform-origin: 50% 100%;
        color: var(--knrc-accent);
        transition: transform .26s ease, opacity .26s ease;
        backface-visibility: hidden;
    }
    .knrc-word[data-state='out'] { transform: rotateX(90deg); opacity: 0; }
    .knrc-word[data-state='in'] { transform: rotateX(-90deg); opacity: 0; transition: none; }
    .knrc-ghost { position: absolute; visibility: hidden; white-space: nowrap;
                  pointer-events: none; font: inherit; font-weight: 900; }
    .knrc-hint { margin: 0; font-size: .76rem; color: var(--knrc-muted); }
    .knrc-foot { display: flex; flex-wrap: wrap; align-items: center; gap: .9rem 1.4rem;
                 justify-content: space-between; }
    .knrc-again {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .55rem 1.1rem; border-radius: 999px; cursor: pointer;
        border: 1px solid var(--knrc-border); background: var(--knrc-card);
        color: var(--knrc-ink); font: 600 .82rem/1.4 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        transition: border-color .15s ease, color .15s ease;
    }
    .knrc-again:hover { border-color: var(--knrc-accent); color: var(--knrc-accent); }
    .knrc-again:focus-visible { outline: 2px solid var(--knrc-accent); outline-offset: 2px; }
    .knrc-again svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .knrc-dots { display: inline-flex; gap: .45rem; }
    .knrc-dot {
        inline-size: .5rem; aspect-ratio: 1; border-radius: 50%; padding: 0;
        border: 1px solid var(--knrc-muted); background: transparent; cursor: pointer;
        transition: background-color .2s ease, border-color .2s ease, scale .2s ease;
    }
    .knrc-dot[aria-current='true'] { background: var(--knrc-accent);
                                     border-color: var(--knrc-accent); scale: 1.25; }
    .knrc-dot:focus-visible { outline: 2px solid var(--knrc-accent); outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) {
        .knrc-stage { transition: none; }
        .knrc-word { transition: none; }
        .knrc-word[data-state='out'], .knrc-word[data-state='in'] { transform: none; opacity: 1; }
        .knrc-dot { transition: none; }
        .knrc-again { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        words: {{ json_encode($words, JSON_UNESCAPED_UNICODE) }},
        i: 0, state: 'show', t: null, t2: null, io: null,
        calm: false,
        get current() { return this.words[this.i]; },
        fit() {
            this.$refs.ghost.textContent = this.words[this.i];
            const w = Math.ceil(this.$refs.ghost.getBoundingClientRect().width);
            this.$refs.slot.style.inlineSize = w + 'px';
        },
        show(n) {
            this.state = 'in';
            this.i = ((n % this.words.length) + this.words.length) % this.words.length;
            this.fit();
            if (this.calm) { this.state = 'show'; return; }
            requestAnimationFrame(() => requestAnimationFrame(() => (this.state = 'show')));
        },
        swap() {
            if (this.calm) { this.show(this.i + 1); return; }
            this.state = 'out';
            clearTimeout(this.t2);
            this.t2 = setTimeout(() => this.show(this.i + 1), 260);
        },
        start() {
            this.stop();
            if (this.calm) return;
            this.t = setInterval(() => this.swap(), 2400);
        },
        stop() { clearInterval(this.t); clearTimeout(this.t2); },
        replay() { this.stop(); this.show(0); this.start(); },
        goto(n) { this.stop(); this.show(n); this.start(); },
        init() {
            this.calm = matchMedia('(prefers-reduced-motion: reduce)').matches;
            this.fit();
            this.io = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) this.start(); else this.stop();
            }, { threshold: 0.25 });
            this.io.observe(this.$refs.hero);
        },
        destroy() { this.stop(); if (this.io) this.io.disconnect(); },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The headline word that never stands still', 'واژهٔ تیتر که هرگز نمی‌ایستد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The keyword flips out at rotateX(90°), swaps under the fold and swings back in from −90°, while a hidden same-font span measures the incoming word so the slot width eases to it. Hover holds the loop, the dots jump straight to a word, and off screen the cycle rests.', 'واژهٔ کلیدی با rotateX(90) بیرون می‌چرخد، زیر تاختن عوض می‌شود و از −90 درجه برمی‌گردد؛ هم‌زمان یک span پنهانِ هم‌قلم واژهٔ تازه را اندازه می‌گیرد تا عرض جایگاه نرم به آن برسد. هاور چرخه را نگه می‌دارد، نقطه‌ها مستقیم به واژه می‌پرند و بیرون از دید چرخه می‌خوابد.') }}
        </p>
    </div>

    <div class="knrc-hero" x-ref="hero" x-on:pointerenter="stop()" x-on:pointerleave="start()">
        <p class="knrc-kicker">{{ $kicker }}</p>
        <h4 class="knrc-line">
            {{ $lead }}
            <span class="knrc-stage" x-ref="slot"><span class="knrc-word" x-ref="word" x-bind:data-state="state" x-text="current" aria-live="polite"></span></span>{{ $say(' — ', ' — ') }}{{ $tail }}
        </h4>
        <span class="knrc-ghost" x-ref="ghost" aria-hidden="true"></span>
        <p class="knrc-hint">{{ $say('Hover to hold · 2.4s per word', 'هاور برای نگه‌داشتن · ۲٫۴ ثانیه هر واژه') }}</p>
    </div>

    <div class="knrc-foot">
        <div class="knrc-dots" role="group" aria-label="{{ $say('Word', 'واژه') }}">
            <template x-for="(w, n) in words" :key="n">
                <button type="button" class="knrc-dot"
                    x-bind:aria-current="i === n ? 'true' : 'false'"
                    x-bind:aria-label="w"
                    x-on:click="goto(n)"></button>
            </template>
        </div>
        <button type="button" class="knrc-again" x-on:click="replay()">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
            {{ $say('Replay the cycle', 'چرخه را از نو بچرخان') }}
        </button>
    </div>
</section>
