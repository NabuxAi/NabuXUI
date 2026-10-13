{{--
    letter-wave's real scenario: the studio title of Oceanline — a marine
    research platform. The title's units ride a travelling sine: translateY
    plus a slight rotation, phase-shifted per unit so the motion reads as a
    wave. The pointer drops a gaussian boost on the timeline (σ ≈ 70px) that
    makes the nearest units leap; it decays smoothly after the pointer
    leaves. Latin splits per letter, Persian per whole word — letters are
    never broken. One rAF loop, parked off screen (IntersectionObserver) and
    stilled under reduced motion; the slider scales the swell 0–2×.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Latin: letter-level units. Persian: word-level — letters stay joined.
    $units = $fa
        ? ['تایپوگرافی', 'موج', 'می‌زند']
        : str_split('MAKING WAVES');
    $plainTitle = $fa ? 'تایپوگرافی موج می‌زند' : 'MAKING WAVES';
    $kicker = $say('OCEANLINE · MARINE RESEARCH PLATFORM', 'اوشن‌لاین · سکوی پژوهش دریایی');
@endphp

<style>
    .knlw-root {
        --knlw-bg: #062a3a; --knlw-ink: #e8f6ff; --knlw-muted: #8fc0d6;
        --knlw-border: rgba(143, 192, 214, .28); --knlw-accent: #38e0d0;
        display: grid; gap: 1.25rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--knlw-ink);
    }
    html[data-theme="dark"] .knlw-root {
        --knlw-bg: #041a26; --knlw-ink: #dff4ff; --knlw-muted: #7faeC4;
        --knlw-border: rgba(127, 174, 196, .24); --knlw-accent: #2fd4c4;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .knlw-root {
            --knlw-bg: #041a26; --knlw-ink: #dff4ff; --knlw-muted: #7faec4;
            --knlw-border: rgba(127, 174, 196, .24); --knlw-accent: #2fd4c4;
        }
    }
    .knlw-stage {
        position: relative; display: grid; gap: 1.6rem; justify-items: center;
        padding: clamp(1.6rem, 5vw, 3rem) 1.25rem calc(1.4rem + 2.2rem);
        border-radius: 1.25rem; overflow: clip;
        background:
            radial-gradient(130% 90% at 50% -10%, rgba(56, 224, 208, .16), transparent 55%),
            linear-gradient(180deg, var(--knlw-bg), color-mix(in oklab, var(--knlw-bg) 78%, #000));
        cursor: default;
    }
    .knlw-kicker { margin: 0; z-index: 1; font-size: .72rem; font-weight: 700;
                   letter-spacing: .16em; color: var(--knlw-muted); }
    .knlw-title {
        margin: 0; z-index: 1; display: flex; flex-wrap: wrap; justify-content: center;
        gap: 0 .06em; font-weight: 900; line-height: 1.35;
        font-size: clamp(2.1rem, 9vw, 4.3rem); letter-spacing: .02em;
        perspective: 500px; max-inline-size: 100%;
    }
    [dir="rtl"] .knlw-title { gap: 0 .35em; }
    .knlw-u { display: inline-block; white-space: pre; will-change: transform; }
    .knlw-wave { position: absolute; inset-inline: 0; inset-block-end: 0; block-size: 2.6rem;
                 opacity: .5; pointer-events: none;
                 background: repeating-radial-gradient(circle at 0 100%, transparent 0 26px, rgba(56, 224, 208, .12) 26px 27px); }
    .knlw-dock {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: .6rem 1.1rem; margin-block-start: -1.2rem; z-index: 2;
        padding: .55rem 1rem; border-radius: 999px;
        background: color-mix(in oklab, var(--knlw-bg) 72%, transparent);
        border: 1px solid var(--knlw-border);
        backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        inline-size: fit-content; margin-inline: auto;
    }
    .knlw-amp { display: inline-flex; align-items: center; gap: .6rem;
                font: 600 .8rem/1.4 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; color: var(--knlw-ink); }
    .knlw-amp input {
        inline-size: 9.5rem; accent-color: var(--knlw-accent); cursor: pointer;
    }
    .knlw-amp input:focus-visible { outline: 2px solid var(--knlw-accent); outline-offset: 3px; }
    .knlw-amp b { font-variant-numeric: tabular-nums; min-inline-size: 3.2ch; text-align: center;
                  color: var(--knlw-accent); direction: ltr; }
    .knlw-hint { margin: 0; font-size: .74rem; color: var(--knlw-muted); text-align: center;
                 font-family: ui-monospace, monospace; }
    @media (prefers-reduced-motion: reduce) {
        .knlw-u { transform: none !important; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A title that swims under the pointer', 'تیتری که زیر نشانگر شنا می‌کند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Each unit rides a travelling sine — translateY with a phase-shifted tilt — and the pointer drops a gaussian boost (σ ≈ 70px) so the nearest units leap higher. Latin units are letters; Persian units are whole words, never broken letters. The slider scales the swell from dead calm to double storm.', 'هر واحد روی یک سینوس متحرک می‌راند — جابه‌جایی عمودی با چرخش فازدار — و موس یک تقویت گاوسی (σ ≈ ۷۰ پیکسل) می‌اندازد تا نزدیک‌ترین واحدها بلندتر بجهند. واحد لاتین حرف است؛ واحد فارسی واژهٔ کامل، بدون شکستن حروف. اسلایدر موج را از آرامِ مرده تا توفانِ دوبرابر می‌کشد.') }}
        </p>
    </div>

    <div class="knlw-root">
        <div class="knlw-stage"
            x-data="{
                units: [], raf: 0, t0: 0, io: null, mq: null, onMq: null, onResize: null,
                mx: 0, boost: 0, over: false, amp: 1, visible: true,
                fa: @js($fa),
                get ampLabel() {
                    const s = (Math.round(this.amp * 100) / 100).toFixed(2);
                    return this.fa ? s.replace(/\\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]).replace('.', '٫') + '×' : s + '×';
                },
                measure() {
                    this.units.forEach((u) => {
                        const r = u.el.getBoundingClientRect();
                        u.x = r.x + r.width / 2;
                    });
                },
                frame(now) {
                    const t = (now - this.t0) / 1000;
                    this.boost += ((this.over ? 1 : 0) - this.boost) * 0.12;
                    this.units.forEach((u, i) => {
                        const near = Math.exp(-((u.x - this.mx) ** 2) / 9800) * this.boost;
                        const y = Math.sin(t * 2.2 + i * 0.55) * 10 * this.amp * (1 + 2 * near);
                        const r = Math.sin(t * 2.2 + i * 0.55 + 0.9) * 3 * this.amp * (1 + near);
                        u.el.style.transform = 'translateY(' + y.toFixed(2) + 'px) rotate(' + r.toFixed(2) + 'deg)';
                    });
                    this.raf = requestAnimationFrame((n) => this.frame(n));
                },
                run() {
                    cancelAnimationFrame(this.raf);
                    if (this.mq.matches || !this.visible) return;
                    this.raf = requestAnimationFrame((n) => this.frame(n));
                },
                init() {
                    this.t0 = performance.now();
                    this.units = [...this.$refs.title.querySelectorAll('.knlw-u')].map((el) => ({ el, x: 0 }));
                    this.measure();
                    this.onResize = () => this.measure();
                    window.addEventListener('resize', this.onResize);
                    this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                    this.onMq = () => {
                        if (this.mq.matches) this.units.forEach((u) => (u.el.style.transform = ''));
                        this.run();
                    };
                    this.mq.addEventListener('change', this.onMq);
                    this.io = new IntersectionObserver((entries) => {
                        this.visible = entries[0].isIntersecting;
                        this.run();
                    }, { threshold: 0.15 });
                    this.io.observe(this.$refs.stage);
                    this.run();
                },
                destroy() {
                    cancelAnimationFrame(this.raf);
                    if (this.io) this.io.disconnect();
                    if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                    if (this.onResize) window.removeEventListener('resize', this.onResize);
                },
            }"
            x-ref="stage"
            x-on:pointermove="mx = $event.clientX; over = true"
            x-on:pointerleave="over = false">
            <p class="knlw-kicker">{{ $kicker }}</p>
            <h4 class="knlw-title" x-ref="title" aria-label="{{ $plainTitle }}">
                @foreach ($units as $u)
                    <span class="knlw-u" aria-hidden="true">{{ $u }}</span>
                @endforeach
            </h4>
            <i class="knlw-wave" aria-hidden="true"></i>
        </div>

        <div class="knlw-dock">
            <label class="knlw-amp">
                {{ $say('Swell', 'شدت') }}
                <input type="range" min="0" max="2" step="0.05" value="1" x-model.number="amp">
                <b x-text="ampLabel"></b>
            </label>
        </div>

        <p class="knlw-hint">
            {{ $say('sin(t · 2.2 + i · 0.55) · 10px · gaussian pointer boost · rAF parked off screen · still under reduced motion', 'sin(t · ۲٫۲ + i · ۰٫۵۵) · ۱۰px · تقویت گاوسی موس · rAF پارک‌شده بیرون دید · ساکن در حرکت کاهش‌یافته') }}
        </p>
    </div>
</section>
