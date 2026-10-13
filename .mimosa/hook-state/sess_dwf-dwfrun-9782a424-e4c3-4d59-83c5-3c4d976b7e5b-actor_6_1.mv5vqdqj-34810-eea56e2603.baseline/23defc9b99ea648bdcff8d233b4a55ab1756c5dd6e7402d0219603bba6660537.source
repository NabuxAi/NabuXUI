{{--
    curtain-reveal's real scenario: the launch page of Northlight Studio —
    two curtain panels carry the wordmark and a percentage counter; when the
    count completes the panels part to the sides and the page beneath enters
    item by item on a stagger. Replay and a load-speed slider included; the
    loop parks off screen and reduced motion opens the house instantly.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .encr-wrap {
        --encr-fg: #1c1917; --encr-muted: #78716c; --encr-border: #e7e5e4;
        --encr-bg: #fffdf9; --encr-ink: #f5f0e8; --encr-accent: #b45309;
        --encr-panel-a: #1c1917; --encr-panel-b: #3b2f2a; --encr-chip: #f5f0eb;
        display: grid; gap: 1rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--encr-fg);
    }
    html[data-theme="dark"] .encr-wrap {
        --encr-fg: #fafaf9; --encr-muted: #a8a29e; --encr-border: #292524;
        --encr-bg: #171412; --encr-ink: #f5f0e8; --encr-accent: #f59e0b;
        --encr-panel-a: #0c0a09; --encr-panel-b: #292524; --encr-chip: #231e1a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .encr-wrap {
            --encr-fg: #fafaf9; --encr-muted: #a8a29e; --encr-border: #292524;
            --encr-bg: #171412; --encr-ink: #f5f0e8; --encr-accent: #f59e0b;
            --encr-panel-a: #0c0a09; --encr-panel-b: #292524; --encr-chip: #231e1a;
        }
    }
    .encr-stage {
        position: relative; overflow: clip; border-radius: 1.25rem;
        border: 1px solid var(--encr-border);
        background: var(--encr-bg);
        min-block-size: 24rem; aspect-ratio: 16 / 11;
        display: grid; align-content: center; gap: 1.1rem;
        padding: clamp(1rem, 4%, 2.25rem);
    }
    @media (max-width: 560px) { .encr-stage { aspect-ratio: auto; min-block-size: 26rem; } }
    .encr-nav { display: flex; align-items: center; gap: .9rem; }
    .encr-logo { display: inline-flex; align-items: center; gap: .45rem;
                 font-weight: 800; letter-spacing: .08em; font-size: .82rem; }
    .encr-logo svg { inline-size: 1.15rem; block-size: 1.15rem; stroke: var(--encr-accent); fill: none;
                     stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .encr-links { display: flex; gap: 1rem; margin-inline-start: auto; font-size: .8rem;
                  color: var(--encr-muted); font-weight: 600; }
    @media (max-width: 560px) { .encr-links { display: none; } }
    .encr-eyebrow { font-size: .74rem; font-weight: 700; letter-spacing: .12em;
                    text-transform: uppercase; color: var(--encr-accent); }
    .encr-h1 { margin: 0; font-size: clamp(1.5rem, 4.5vw, 2.4rem); line-height: 1.25;
               font-weight: 800; letter-spacing: -.01em; max-inline-size: 24ch; }
    .encr-sub { margin: 0; color: var(--encr-muted); font-size: .92rem;
                line-height: 1.8; max-inline-size: 42ch; }
    .encr-cta { display: flex; flex-wrap: wrap; gap: .6rem; }
    .encr-btn { padding: .6rem 1.15rem; border-radius: 999px; font: 700 .82rem/1 inherit;
                cursor: pointer; border: 1px solid transparent; }
    .encr-btn[data-kind="solid"] { background: var(--encr-panel-a); color: var(--encr-ink); }
    .encr-btn[data-kind="ghost"] { border-color: var(--encr-border); color: inherit;
                                   background: transparent; }
    .encr-stats { display: flex; flex-wrap: wrap; gap: .5rem; }
    .encr-chip { display: inline-flex; align-items: baseline; gap: .4rem; padding: .45rem .85rem;
                 border-radius: .75rem; background: var(--encr-chip);
                 border: 1px solid var(--encr-border); font-size: .78rem; }
    .encr-chip b { font-size: .95rem; font-variant-numeric: tabular-nums; }
    .encr-chip span { color: var(--encr-muted); }

    .encr-in { opacity: 0; translate: 0 18px; filter: blur(7px);
        transition: opacity .55s var(--nx-spring-gentle), translate .55s var(--nx-spring-gentle),
                    filter .55s var(--nx-spring-gentle); }
    .encr-stage[data-open="true"] .encr-in { opacity: 1; translate: 0 0; filter: blur(0);
        transition-delay: calc(360ms + var(--i, 0) * 95ms); }

    .encr-panel { position: absolute; inset-block: 0; inline-size: 50.5%; z-index: 2;
        background: linear-gradient(180deg, var(--encr-panel-a), var(--encr-panel-b));
        translate: 0 0;
        transition: translate .9s var(--nx-spring-gentle); }
    .encr-panel::after { content: ''; position: absolute; inset: 0;
        background: repeating-linear-gradient(90deg, transparent 0 2.2rem, rgba(255, 255, 255, .035) 2.2rem 2.35rem); }
    .encr-left { inset-inline-start: 0; }
    .encr-right { inset-inline-end: 0; }
    .encr-stage[data-open="true"] .encr-left { translate: -102% 0; }
    .encr-stage[data-open="true"] .encr-right { translate: 102% 0; }

    .encr-mark { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 3;
        display: grid; justify-items: center; align-content: center; gap: .8rem;
        block-size: 100%; color: var(--encr-ink); pointer-events: none;
        transition: opacity .4s ease, scale .4s ease; }
    .encr-stage[data-open="true"] .encr-mark { opacity: 0; scale: .92; }
    .encr-aperture { inline-size: 3.4rem; block-size: 3.4rem; stroke: var(--encr-accent); fill: none;
                     stroke-width: 1.6; stroke-linecap: round; }
    .encr-word { font-size: .78rem; font-weight: 800; letter-spacing: .35em; }
    .encr-count { font-size: clamp(2rem, 6vw, 3rem); font-weight: 800; line-height: 1;
                  font-variant-numeric: tabular-nums; }
    .encr-count small { font-size: .45em; font-weight: 700; color: var(--encr-accent); }

    .encr-ctl { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1.4rem; }
    .encr-replay { display: inline-flex; align-items: center; gap: .45rem; padding: .55rem 1.1rem;
        border-radius: 999px; border: 1px solid var(--encr-border); background: var(--encr-bg);
        color: inherit; font: 700 .8rem/1 inherit; cursor: pointer;
        transition: border-color .15s ease, color .15s ease; }
    .encr-replay:hover { border-color: var(--encr-accent); color: var(--encr-accent); }
    .encr-replay:focus-visible { outline: 2px solid var(--encr-accent); outline-offset: 2px; }
    .encr-replay svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                       fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .encr-speed { display: inline-flex; align-items: center; gap: .55rem; font-size: .78rem;
                  font-weight: 700; color: var(--encr-muted); }
    .encr-speed input { inline-size: 8rem; accent-color: var(--encr-accent); }
    .encr-speed output { min-inline-size: 2.8em; font-family: ui-monospace, monospace;
                         color: var(--encr-fg); text-align: end; }
    .encr-hint { margin: 0; font-size: .74rem; color: var(--encr-muted); }
    @media (prefers-reduced-motion: reduce) {
        .encr-panel, .encr-mark, .encr-in, .encr-replay { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        open: false, pct: 0, speed: 2.4, visible: false,
        io: null, raf: 0, t0: 0, acc: 0,
        fa: @js($fa),
        init() {
            this.io = new IntersectionObserver((entries) => {
                this.visible = entries[0].isIntersecting;
                this.tick();
            }, { threshold: 0.25 });
            this.io.observe(this.$refs.stage);
        },
        destroy() {
            if (this.io) this.io.disconnect();
            cancelAnimationFrame(this.raf);
        },
        tick() {
            cancelAnimationFrame(this.raf);
            if (this.open || !this.visible) return;
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) { this.finish(); return; }
            this.acc = Math.min(1, this.pct / 100);
            this.t0 = performance.now();
            const dur = this.speed * 1000;
            const loop = (now) => {
                const p = Math.min(1, this.acc + (now - this.t0) / dur);
                this.pct = Math.round((1 - Math.pow(1 - p, 3)) * 100);
                if (p >= 1) { this.finish(); return; }
                this.raf = requestAnimationFrame(loop);
            };
            this.raf = requestAnimationFrame(loop);
        },
        finish() { cancelAnimationFrame(this.raf); this.pct = 100; this.open = true; },
        replay() {
            this.open = false; this.pct = 0;
            requestAnimationFrame(() => requestAnimationFrame(() => this.tick()));
        },
        get pctLabel() {
            const s = String(this.pct);
            return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s;
        },
        get speedLabel() {
            const s = this.speed.toFixed(1);
            return (this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]).replace('.', '٫') : s) + 's';
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The house opens, the page enters', 'پرده بالا می‌رود، صفحه وارد می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Two curtain panels carry the wordmark and an eased percentage counter; at one hundred the panels part to the sides and every block of the launch page beneath arrives on a stagger. Drag the speed slider or replay the whole overture.', 'دو پنل پرده، نشان برند و شمارندهٔ درصد را حمل می‌کنند؛ در صد، پرده‌ها به دو طرف باز می‌شوند و تک‌تک بخش‌های صفحهٔ معرفی با تأخیر پله‌ای می‌رسند. اسلایدر سرعت را بکشید یا کل افتتاحیه را دوباره اجرا کنید.') }}
        </p>
    </div>

    <div class="encr-wrap">
        <div class="encr-stage" x-ref="stage" x-bind:data-open="open ? 'true' : 'false'"
            role="img"
            x-bind:aria-label="@js($say('Launch page behind a two-panel curtain', 'صفحهٔ معرفی پشت پردهٔ دوپنلی'))">
            <nav class="encr-nav encr-in" style="--i: 0">
                <span class="encr-logo">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 3v6M19.8 7.5l-5.2 3M19.8 16.5l-5.2-3M12 21v-6M4.2 16.5l5.2-3M4.2 7.5l5.2 3"/></svg>
                    NORTHLIGHT
                </span>
                <span class="encr-links">
                    <span>{{ $say('Work', 'کارها') }}</span>
                    <span>{{ $say('Studio', 'استودیو') }}</span>
                    <span>{{ $say('Journal', 'بلاگ') }}</span>
                </span>
            </nav>
            <p class="encr-eyebrow encr-in" style="--i: 1; margin: 0">{{ $say('Northlight 3.0 — public beta', 'نورث‌لایت ۳٫۰ — بتای عمومی') }}</p>
            <h2 class="encr-h1 encr-in" style="--i: 2">{{ $say('The curtain rises on your next interface', 'پرده برای رابط بعدی شما بالا می‌رود') }}</h2>
            <p class="encr-sub encr-in" style="--i: 3">{{ $say('Light-and-shadow design tools for product teams — from first idea to a working demo in one sitting.', 'ابزارهای طراحی نور و سایه برای تیم‌های محصول — از ایدهٔ اول تا دموی کارا، در یک نشست.') }}</p>
            <div class="encr-cta encr-in" style="--i: 4">
                <button type="button" class="encr-btn" data-kind="solid">{{ $say('Start building', 'شروع ساخت') }}</button>
                <button type="button" class="encr-btn" data-kind="ghost">{{ $say('Watch the reel', 'تماشای ریل') }}</button>
            </div>
            <div class="encr-stats encr-in" style="--i: 5">
                <span class="encr-chip"><b>{{ $say('120+', '۱۲۰+') }}</b><span>{{ $say('ready templates', 'قالب آماده') }}</span></span>
                <span class="encr-chip"><b>{{ $say('40ms', '۴۰ms') }}</b><span>{{ $say('interactions', 'پاسخ‌دهی تعامل') }}</span></span>
                <span class="encr-chip"><b>{{ $say('4.9/5', '۴٫۹/۵') }}</b><span>{{ $say('team rating', 'امتیاز تیم‌ها') }}</span></span>
            </div>

            <div class="encr-mark" aria-hidden="true">
                <svg class="encr-aperture" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 3v6M19.8 7.5l-5.2 3M19.8 16.5l-5.2-3M12 21v-6M4.2 16.5l5.2-3M4.2 7.5l5.2 3"/>
                </svg>
                <span class="encr-word">NORTHLIGHT</span>
                <span class="encr-count"><span x-text="pctLabel"></span><small>%</small></span>
            </div>
            <div class="encr-panel encr-left" aria-hidden="true"></div>
            <div class="encr-panel encr-right" aria-hidden="true"></div>
        </div>

        <div class="encr-ctl">
            <button type="button" class="encr-replay" x-on:click="replay()">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                {{ $say('Replay the overture', 'دوباره اجرا کن') }}
            </button>
            <label class="encr-speed">
                <span>{{ $say('Load speed', 'سرعت بارگذاری') }}</span>
                <input type="range" min="0.8" max="5" step="0.1" x-model.number="speed" x-on:input="if (!open) tick()">
                <output x-text="speedLabel"></output>
            </label>
            <p class="encr-hint">{{ $say('eased count → panels part on --nx-spring-gentle → items stagger at 95ms', 'شمارش نرم → بازشدن پرده با --nx-spring-gentle → ورود آیتم‌ها با گام ۹۵ms') }}</p>
        </div>
    </div>
</section>
