{{--
    The custom cursor's real scenario: a launch stage for an autumn collection —
    the ring trails the pointer, the dot rides it, and every test element asks
    for its own cursor state: the big link grows it, the copy narrows it to a
    reading bar, the trailer button labels it "play".
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .mfxcs-root {
        --mfxcs-ink: #16181d; --mfxcs-ink-soft: #5c6270;
        --mfxcs-line: #d9dde4; --mfxcs-surface: #f7f8fa; --mfxcs-surface-2: #ffffff;
        --mfxcs-accent: #4f46e5; --mfxcs-ring-fill: rgba(22, 24, 29, .08);
        --mfxcs-ring-ink: #ffffff;
        font-family: var(--nx-font-display, system-ui);
        display: grid;
        gap: 1.25rem;
    }
    html[data-theme="dark"] .mfxcs-root {
        --mfxcs-ink: #f2f4f8; --mfxcs-ink-soft: #9aa1b0;
        --mfxcs-line: #33384a; --mfxcs-surface: #171923; --mfxcs-surface-2: #1e2130;
        --mfxcs-accent: #8b83ff; --mfxcs-ring-fill: rgba(242, 244, 248, .12);
        --mfxcs-ring-ink: #171923;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mfxcs-root {
            --mfxcs-ink: #f2f4f8; --mfxcs-ink-soft: #9aa1b0;
            --mfxcs-line: #33384a; --mfxcs-surface: #171923; --mfxcs-surface-2: #1e2130;
            --mfxcs-accent: #8b83ff; --mfxcs-ring-fill: rgba(242, 244, 248, .12);
            --mfxcs-ring-ink: #171923;
        }
    }
    .mfxcs-stage {
        position: relative; overflow: clip; border: 1px solid var(--mfxcs-line);
        border-radius: 1.25rem; background: var(--mfxcs-surface);
        display: grid; gap: 1.5rem; padding: clamp(1.25rem, 4vw, 2.5rem);
    }
    @media (pointer: fine) {
        .mfxcs-stage, .mfxcs-stage :is(a, button, input, textarea) { cursor: none; }
    }
    .mfxcs-head { display: grid; gap: .5rem; text-align: center; justify-items: center; }
    .mfxcs-eyebrow {
        font-size: .72rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase;
        color: var(--mfxcs-ink-soft);
    }
    .mfxcs-link {
        font: 800 clamp(1.75rem, 6vw, 3.25rem) / 1.15 var(--nx-font-display, system-ui);
        color: var(--mfxcs-ink); text-decoration: none; text-wrap: balance; padding-inline: .25rem;
    }
    .mfxcs-link:focus-visible { outline: 2px solid var(--mfxcs-accent); outline-offset: 4px; border-radius: .5rem; }
    .mfxcs-body {
        display: grid; gap: 1rem; grid-template-columns: 1.2fr .8fr;
        align-items: stretch; min-inline-size: 0;
    }
    .mfxcs-copy {
        margin: 0; padding: 1.25rem; border: 1px solid var(--mfxcs-line);
        border-radius: 1rem; background: var(--mfxcs-surface-2);
        color: var(--mfxcs-ink); line-height: 1.95; text-wrap: pretty;
    }
    .mfxcs-trailer {
        display: grid; gap: 1rem; align-content: center; justify-items: center;
        padding: 1.25rem; border: 1px solid var(--mfxcs-line); border-radius: 1rem;
        background:
            radial-gradient(120% 90% at 20% 0%, rgba(79, 70, 229, .14), transparent 60%),
            var(--mfxcs-surface-2);
    }
    .mfxcs-btn {
        display: inline-flex; align-items: center; gap: .5rem; border: 0; border-radius: 999px;
        padding: .8rem 1.5rem; background: var(--mfxcs-ink); color: var(--mfxcs-surface);
        font: 600 .9rem / 1 var(--nx-font-display, system-ui);
    }
    .mfxcs-btn:focus-visible { outline: 2px solid var(--mfxcs-accent); outline-offset: 3px; }
    .mfxcs-btn small { font-weight: 500; opacity: .72; }
    .mfxcs-hint { margin: 0; text-align: center; font-size: .78rem; color: var(--mfxcs-ink-soft); }
    /* The pointer pair: a lagging ring and an instant dot, both absolutely
       anchored to the stage's inline-start edge; coordinates stay physical. */
    .mfxcs-pos {
        position: absolute; inset-block-start: 0; inset-inline-start: 0;
        pointer-events: none; opacity: 0; transition: opacity .25s ease; z-index: 3;
    }
    .mfxcs-stage[data-on="yes"] .mfxcs-pos { opacity: 1; }
    .mfxcs-ring {
        display: grid; place-items: center; translate: -50% -50%;
        inline-size: 20px; aspect-ratio: 1; border: 1.5px solid var(--mfxcs-ink);
        border-radius: 999px; color: transparent;
        font: 600 .68rem / 1 var(--nx-font-display, system-ui); white-space: nowrap;
        transition: inline-size .18s ease, block-size .18s ease, background-color .18s ease,
                    color .18s ease, border-color .18s ease;
    }
    .mfxcs-dotpos { z-index: 4; }
    .mfxcs-dot {
        display: block; translate: -50% -50%; inline-size: 5px; aspect-ratio: 1;
        border-radius: 50%; background: var(--mfxcs-ink);
    }
    .mfxcs-stage[data-mode="link"] .mfxcs-ring {
        inline-size: clamp(64px, 18vw, 96px); aspect-ratio: auto; block-size: 3rem;
        background: var(--mfxcs-ring-fill); border-color: var(--mfxcs-accent);
    }
    .mfxcs-stage[data-mode="text"] .mfxcs-ring {
        inline-size: 4px; aspect-ratio: auto; block-size: 30px;
        border-radius: 2px; background: var(--mfxcs-accent); border-color: transparent;
    }
    .mfxcs-stage[data-mode="play"] .mfxcs-ring {
        inline-size: 72px; aspect-ratio: auto; block-size: 72px;
        background: var(--mfxcs-ink); border-color: var(--mfxcs-ink); color: var(--mfxcs-ring-ink);
    }
    @media (pointer: coarse) { .mfxcs-pos { display: none; } }
    @media (max-width: 640px) { .mfxcs-body { grid-template-columns: 1fr; } }
    @media (prefers-reduced-motion: reduce) {
        .mfxcs-pos { transition: opacity .01ms; }
        .mfxcs-ring { transition-duration: .01ms; }
    }
</style>

<section class="pg-box" style="padding: 0; overflow: clip">
    <div class="mfxcs-root"
        x-data="{
            mode: '', label: '', on: false, first: true,
            raf: 0, tx: 0, ty: 0, rx: 0, ry: 0, rtl: false, reduced: false,
            place(el, x, y) { el.style.translate = (this.rtl ? -x : x) + 'px ' + y + 'px' },
            tick() {
                const k = this.reduced ? 1 : 0.18,
                ring = this.$refs.pos, dot = this.$refs.dotpos
                this.rx += (this.tx - this.rx) * k
                this.ry += (this.ty - this.ry) * k
                this.place(ring, this.rx, this.ry)
                this.place(dot, this.tx, this.ty)
                this.raf = requestAnimationFrame(() => this.tick())
            },
            onMove(event) {
                const stage = this.$refs.stage, rect = stage.getBoundingClientRect(),
                px = event.clientX - rect.left, py = event.clientY - rect.top
                this.rtl = getComputedStyle(stage).direction === 'rtl'
                this.tx = this.rtl ? rect.width - px : px
                this.ty = py
                if (this.first) { this.rx = this.tx; this.ry = this.ty; this.first = false }
                const hit = event.target.closest('[data-cursor]')
                this.mode = hit ? hit.getAttribute('data-cursor') : ''
                this.label = hit ? (hit.getAttribute('data-label') || '') : ''
                this.on = true
            },
            onLeave() { this.on = false },
            init() {
                this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches
                const stage = this.$refs.stage
                this.move = (e) => this.onMove(e)
                this.over = (e) => this.onMove(e)
                stage.addEventListener('pointermove', this.move)
                stage.addEventListener('pointerover', this.over)
                stage.addEventListener('pointerleave', this.leave = () => this.onLeave())
                this.tick()
            },
            destroy() {
                cancelAnimationFrame(this.raf)
                const stage = this.$refs.stage
                stage.removeEventListener('pointermove', this.move)
                stage.removeEventListener('pointerover', this.over)
                stage.removeEventListener('pointerleave', this.leave)
            },
        }">
        <div class="mfxcs-stage" x-ref="stage" x-bind:data-mode="mode" x-bind:data-on="on ? 'yes' : 'no'">
            <div class="mfxcs-head">
                <p class="mfxcs-eyebrow">{{ $say('Autumn drop · 04 · Kyoto', 'مجموعهٔ پاییز · ۰۴ · کیوتو') }}</p>
                <a class="mfxcs-link" href="#mfxcs-drop" data-cursor="link">
                    {{ $say('Wander the whole collection', 'در کل مجموعه بچرخ') }}
                </a>
            </div>

            <div class="mfxcs-body">
                <p class="mfxcs-copy" data-cursor="text">
                    {{ $say('Twelve pieces cut in Kyoto and Copenhagen — a coat that packs into its own pocket, wool knits dyed with tea, and boots that break in on the first walk. Move your pointer over this paragraph and the cursor thins into a reading bar.', 'دوازده قطعه که در کیوتو و کپنهاگ بریده شده‌اند — کاپشنی که در جیب خودش جا می‌شود، بافت‌های پشمی با رنگ چای و بوت‌هایی که در همان پیاده‌روی اول نرم می‌شوند. اشاره‌گر را روی این بند ببرید تا کرسر به میلهٔ خواندن باریک شود.') }}
                </p>
                <div class="mfxcs-trailer">
                    <button type="button" class="mfxcs-btn" data-cursor="play" data-label="{{ $say('Play', 'پخش') }}">
                        <span>{{ $say('Play the trailer', 'پخش تریلر') }}</span>
                    </button>
                    <small style="color: var(--mfxcs-ink-soft)">{{ $say('2:14 · shot on location', '۲:۱۴ · فیلم‌برداری‌شده در محل') }}</small>
                </div>
            </div>

            <p class="mfxcs-hint">
                {{ $say('Ring over the title, bar over the copy, label over the button — leave the stage and it all fades; on touch the native cursor stays.', 'روی تیتر حلقه، روی متن میله، روی دکمه برچسب — از صحنه بیرون بروید همه محو می‌شود؛ روی لمس، کرسر بومی سرِ جای خودش می‌ماند.') }}
            </p>

            <span class="mfxcs-pos" x-ref="pos" aria-hidden="true"><span class="mfxcs-ring" x-text="label"></span></span>
            <span class="mfxcs-pos mfxcs-dotpos" x-ref="dotpos" aria-hidden="true"><i class="mfxcs-dot"></i></span>
        </div>
    </div>
</section>
