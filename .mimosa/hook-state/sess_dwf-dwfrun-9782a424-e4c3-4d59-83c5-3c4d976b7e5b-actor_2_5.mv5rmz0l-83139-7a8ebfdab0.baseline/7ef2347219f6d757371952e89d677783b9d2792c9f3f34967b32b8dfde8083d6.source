{{--
    outline-fill's real scenarios: the region poster of a cloud platform whose
    big outline word fills from the bottom on hover, then the three tier cards
    (speed, scale, trust) that fill themselves once as they scroll into view.
    A two-dot switch flips the fill body between a gradient and a checkered
    pattern; an IntersectionObserver with $cleanup drives the scroll trigger.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .otl-root {
        --otl-bg: #ffffff; --otl-ink: #1c1917; --otl-muted: #78716c;
        --otl-border: #e7e5e4; --otl-card: #fafaf9; --otl-stroke: #d6d3d1;
        --otl-c1: #7c3aed; --otl-c2: #22d3ee; --otl-check: #ede9fe;
        display: grid; gap: 2rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--otl-ink);
    }
    html[data-theme="dark"] .otl-root {
        --otl-bg: #171514; --otl-ink: #fafaf9; --otl-muted: #a8a29e;
        --otl-border: #292524; --otl-card: #1c1917; --otl-stroke: #44403c;
        --otl-c1: #a78bfa; --otl-c2: #67e8f9; --otl-check: #2e1065;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .otl-root {
            --otl-bg: #171514; --otl-ink: #fafaf9; --otl-muted: #a8a29e;
            --otl-border: #292524; --otl-card: #1c1917; --otl-stroke: #44403c;
            --otl-c1: #a78bfa; --otl-c2: #67e8f9; --otl-check: #2e1065;
        }
    }
    .otl-switch {
        display: inline-flex; gap: .3rem; padding: .3rem;
        border: 1px solid var(--otl-border); border-radius: 999px; background: var(--otl-card);
    }
    .otl-switch button {
        display: inline-flex; align-items: center; gap: .5rem; padding: .38rem 1rem;
        border: none; border-radius: 999px; background: transparent; color: var(--otl-muted);
        font: 600 .78rem/1.4 inherit; cursor: pointer; transition: color .2s ease, background-color .2s ease;
    }
    .otl-switch button:focus-visible { outline: 2px solid var(--otl-c1); outline-offset: 2px; }
    .otl-switch button[aria-pressed='true'] { color: var(--otl-ink); background: color-mix(in oklab, var(--otl-ink) 8%, transparent); }
    .otl-switch i { display: inline-block; inline-size: .8rem; aspect-ratio: 1; border-radius: 50%; }
    .otl-switch .otl-dot-g { background: linear-gradient(135deg, var(--otl-c1), var(--otl-c2)); }
    .otl-switch .otl-dot-p { border-radius: .2rem; background: repeating-conic-gradient(var(--otl-c1) 0 25%, var(--otl-check) 0 50%) 0 0 / 8px 8px; }
    .otl-poster {
        display: grid; gap: 1rem; justify-items: center; text-align: center;
        padding: clamp(1.5rem, 4vw, 3rem) 1.25rem;
        border: 1px solid var(--otl-border); border-radius: 1.25rem; background: var(--otl-bg);
        overflow: clip;
    }
    .otl-kicker { margin: 0; font-size: .76rem; font-weight: 700; letter-spacing: .12em; color: var(--otl-muted); }
    .otl-word {
        position: relative; display: inline-block; margin: 0;
        font-weight: 900; letter-spacing: .02em; line-height: 1.18;
        font-size: clamp(2.3rem, 10vw, 4.6rem); overflow-wrap: anywhere; cursor: default;
    }
    .otl-ghost { color: transparent; -webkit-text-stroke: 1.5px var(--otl-stroke); }
    .otl-fill {
        position: absolute; inset-block-start: 0; inset-inline-start: 0;
        background-clip: text; -webkit-background-clip: text; color: transparent;
        clip-path: inset(100% 0 0 0);
        transition: clip-path .7s cubic-bezier(.4, 0, .2, 1);
        pointer-events: none;
    }
    .otl[data-fill='gradient'] .otl-fill { background: linear-gradient(15deg, var(--otl-c1), var(--otl-c2)); }
    .otl[data-fill='pattern'] .otl-fill { background: repeating-conic-gradient(var(--otl-c1) 0 25%, var(--otl-check) 0 50%) 0 0 / 14px 14px; }
    .otl-word:hover .otl-fill, .otl-word[data-in='true'] .otl-fill { clip-path: inset(0); }
    .otl-hint { margin: 0; font-size: .76rem; color: var(--otl-muted); }
    .otl-grid {
        display: grid; gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(min(15rem, 100%), 1fr));
    }
    .otl-card {
        display: grid; gap: .8rem; justify-items: center; text-align: center;
        padding: 1.4rem 1rem 1.2rem; border: 1px solid var(--otl-border);
        border-radius: 1.15rem; background: var(--otl-card);
    }
    .otl-card .otl-word { font-size: clamp(1.9rem, 7vw, 2.8rem); }
    .otl-card p { margin: 0; font-size: .8rem; color: var(--otl-muted); }
    .otl-card small {
        font-size: .7rem; font-weight: 700; color: var(--otl-ink);
        padding: .18rem .7rem; border-radius: 999px;
        background: color-mix(in oklab, var(--otl-ink) 7%, transparent);
    }
    @media (prefers-reduced-motion: reduce) {
        .otl-fill { transition: none; }
        .otl-switch button { transition: none; }
    }
</style>

<div class="otl-root">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Words that earn their ink', 'واژه‌هایی که جوهرشان را به‌دست می‌آورند') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The outline is the resting state — text-stroke over transparent ink. Hover fills a word from the bottom through a clip-path wipe, and the tier cards below fill themselves once as they scroll into view. The two-dot switch trades the gradient body for a checkered one.', 'دورق حالتِ خواب است — text-stroke روی جوهر شفاف. هاور واژه را از پایین با گذرِ clip-path پُر می‌کند و کارت‌های پایین هم با ورود به دید، یک‌بار خودشان را پُر می‌کنند. سوییچ دوتمی مایهٔ گرادیان را با الگوی شطرنجی عوض می‌کند.') }}
            </p>
        </div>

        <div class="otl"
            x-data="{
                fill: 'gradient',
                io: null,
                init() {
                    this.io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                        if (entry.isIntersecting) { entry.target.dataset.in = 'true'; this.io.unobserve(entry.target); }
                    }), { threshold: 0.35 });
                    this.$el.querySelectorAll('.otl-word').forEach((w) => this.io.observe(w));
                },
                destroy() { if (this.io) this.io.disconnect(); },
            }"
            x-bind:data-fill="fill">

            <div style="display: grid; gap: 1.25rem; justify-items: center">
                <div class="otl-switch" role="group" aria-label="{{ $say('Fill body', 'مایهٔ پُرشدن') }}">
                    <button type="button" x-bind:aria-pressed="fill === 'gradient' ? 'true' : 'false'" x-on:click="fill = 'gradient'">
                        <i class="otl-dot-g" aria-hidden="true"></i>{{ $say('Gradient', 'گرادیان') }}
                    </button>
                    <button type="button" x-bind:aria-pressed="fill === 'pattern' ? 'true' : 'false'" x-on:click="fill = 'pattern'">
                        <i class="otl-dot-p" aria-hidden="true"></i>{{ $say('Pattern', 'الگو') }}
                    </button>
                </div>

                <div class="otl-poster">
                    <p class="otl-kicker">{{ $say('FJORD CLOUD · NOW LIVE', 'فیورد کلاد · از همین حالا') }}</p>
                    <span class="otl-word"><span class="otl-ghost">{{ $say('FRANKFURT', 'فرانکفورت') }}</span><span class="otl-fill" aria-hidden="true">{{ $say('FRANKFURT', 'فرانکفورت') }}</span></span>
                    <p class="otl-hint">{{ $say('Hover the word — the wipe climbs from the baseline.', 'نشانگر را روی واژه ببرید — پرشدن از خط کف بالا می‌آید.') }}</p>
                </div>
            </div>

            <div class="otl-grid">
                @foreach ([
                    ['word' => ['SPEED', 'سرعت'], 'copy' => ['Sub-50ms responses across the EU edge', 'پاسخ زیر ۵۰ میلی‌ثانیه در لبهٔ اروپا'], 'meta' => ['99.99% uptime', 'پایداری ۹۹٫۹۹٪']],
                    ['word' => ['SCALE', 'مقیاس'], 'copy' => ['Up to 248 cores a region, no ticket needed', 'تا ۲۴۸ هسته در هر ناحیه، بدون تیکت'], 'meta' => ['Dublin · Singapore', 'دوبلین · سنگاپور']],
                    ['word' => ['TRUST', 'اعتماد'], 'copy' => ['Encryption at rest and in transit, on by default', 'رمزگذاری در سکون و عبور، به‌صورت پیش‌فرض'], 'meta' => ['SOC 2 Type II', 'SOC 2 نوع دوم']],
                ] as $tier)
                    <div class="otl-card">
                        <span class="otl-word">
                            <span class="otl-ghost">{{ $say($tier['word'][0], $tier['word'][1]) }}</span>
                            <span class="otl-fill" aria-hidden="true">{{ $say($tier['word'][0], $tier['word'][1]) }}</span>
                        </span>
                        <p>{{ $say($tier['copy'][0], $tier['copy'][1]) }}</p>
                        <small>{{ $say($tier['meta'][0], $tier['meta'][1]) }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
