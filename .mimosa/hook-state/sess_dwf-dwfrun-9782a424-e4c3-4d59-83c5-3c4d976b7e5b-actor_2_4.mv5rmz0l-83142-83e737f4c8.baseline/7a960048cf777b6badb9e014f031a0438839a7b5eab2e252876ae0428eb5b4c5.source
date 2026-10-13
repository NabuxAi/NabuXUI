{{--
    word-fill-scroll's real scenario: the release notes of Lumen Studio —
    a global design platform. The story paragraph lives in its own reading
    pane; every word carries a --lit share that the scroll position feeds,
    so colour climbs the text exactly as fast as the reader does. A sticky
    percentage marker and a hairline rail frame the progress; under reduced
    motion the interpolation stays but the transition is dropped so lighting
    is strictly position-mapped. Persian keeps every word whole.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $story = $say(
        'Lumen Studio tells its product story like this: the idea was sketched in Copenhagen, the first prototype was printed in Osaka, and feedback from forty thousand users — Toronto to São Paulo — polished every edge. The shared library landed first, then the motion tokens, and yesterday the public release went out to every team. Keep scrolling to read on; every word lights up exactly as you reach it, then the closing line hands you the launch date.',
        'استودیو لومن داستان محصولش را این‌طور روایت می‌کند: ایده در کپنهاگ روی دستمال کاغذی طرح شد، نمونهٔ اول در اوساکا چاپ شد و بازخورد چهل هزار کاربر — از تورنتو تا سائوپائولو — هر لبه‌اش را صیقل داد. کتابخانهٔ اشتراکی اول رسید، بعد توکن‌های حرکت و دیروز نسخهٔ عمومی به دست همهٔ تیم‌ها رسید. برای خواندن ادامه اسکرول کنید؛ هر واژه دقیقاً وقتی روشن می‌شود که به آن می‌رسید و جملهٔ پایانی تاریخ رونمایی را می‌گوید.',
    );
    $words = preg_split('/\s+/u', trim($story)) ?: [];
@endphp

<style>
    .knwf-root {
        --knwf-bg: #ffffff; --knwf-ink: #1c1917; --knwf-dim: #d6d3d1;
        --knwf-muted: #78716c; --knwf-border: #e7e5e4; --knwf-card: #fafaf9;
        --knwf-accent: #7c3aed;
        display: grid; gap: 1rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--knwf-ink);
    }
    html[data-theme="dark"] .knwf-root {
        --knwf-bg: #171514; --knwf-ink: #fafaf9; --knwf-dim: #44403c;
        --knwf-muted: #a8a29e; --knwf-border: #292524; --knwf-card: #1c1917;
        --knwf-accent: #a78bfa;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .knwf-root {
            --knwf-bg: #171514; --knwf-ink: #fafaf9; --knwf-dim: #44403c;
            --knwf-muted: #a8a29e; --knwf-border: #292524; --knwf-card: #1c1917;
            --knwf-accent: #a78bfa;
        }
    }
    .knwf-layout {
        display: grid; grid-template-columns: auto 1fr; gap: 1rem;
        align-items: start;
        padding: 1.15rem; border: 1px solid var(--knwf-border);
        border-radius: 1.15rem; background: var(--knwf-bg);
    }
    @media (max-width: 480px) { .knwf-layout { grid-template-columns: 1fr; } .knwf-rail { grid-row: 2; } }
    .knwf-rail {
        position: sticky; inset-block-start: .75rem;
        display: grid; gap: .55rem; justify-items: center;
        inline-size: 3.1rem; padding-block: .2rem;
    }
    @media (max-width: 480px) { .knwf-rail { position: static; grid-auto-flow: column; justify-content: start; align-items: center; } }
    .knwf-pct {
        font-size: .92rem; font-weight: 800; font-variant-numeric: tabular-nums;
        color: var(--knwf-ink); direction: ltr;
    }
    .knwf-track {
        display: block; inline-size: 3px; block-size: 9rem; border-radius: 999px;
        background: var(--knwf-border); overflow: clip;
    }
    @media (max-width: 480px) { .knwf-track { block-size: 3px; inline-size: 6rem; } }
    .knwf-fill {
        display: block; inline-size: 100%; border-radius: inherit;
        background: linear-gradient(180deg, var(--knwf-accent), color-mix(in oklab, var(--knwf-accent) 55%, var(--knwf-ink)));
        transition: block-size .15s linear;
    }
    @media (max-width: 480px) { .knwf-fill { transition: inline-size .15s linear; } }
    .knwf-page {
        block-size: 17.5rem; overflow-y: auto; overscroll-behavior: contain;
        padding: 1.25rem 1.35rem; border: 1px solid var(--knwf-border);
        border-radius: .9rem; background: var(--knwf-card);
        scrollbar-width: thin; scrollbar-color: var(--knwf-dim) transparent;
    }
    .knwf-text {
        margin: 0; font-size: .98rem; font-weight: 500; line-height: 2.15;
        max-inline-size: 58ch;
    }
    .knwf-w {
        color: color-mix(in oklab, var(--knwf-ink) calc(var(--lit, 0) * 100%), var(--knwf-dim));
        transition: color .22s linear;
    }
    .knwf-page::-webkit-scrollbar { inline-size: 6px; }
    .knwf-page::-webkit-scrollbar-thumb { border-radius: 999px; background: var(--knwf-dim); }
    .knwf-foot {
        display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1.25rem;
        justify-content: space-between;
    }
    .knwf-again {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .55rem 1.1rem; border-radius: 999px; cursor: pointer;
        border: 1px solid var(--knwf-border); background: var(--knwf-card);
        color: var(--knwf-ink); font: 600 .82rem/1.4 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        transition: border-color .15s ease, color .15s ease;
    }
    .knwf-again:hover { border-color: var(--knwf-accent); color: var(--knwf-accent); }
    .knwf-again:focus-visible { outline: 2px solid var(--knwf-accent); outline-offset: 2px; }
    .knwf-again svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .knwf-hint { margin: 0; font-size: .74rem; color: var(--knwf-muted);
                 font-family: ui-monospace, monospace; }
    @media (prefers-reduced-motion: reduce) {
        .knwf-w, .knwf-fill { transition: none; }
        .knwf-again { transition: none; }
        .knwf-page { scroll-behavior: auto; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        pct: 0, raf: 0,
        fa: @js($fa),
        faDigits(s) { return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        get pctLabel() { return this.faDigits(String(this.pct)) + (this.fa ? '٪' : '%'); },
        measure() {
            const el = this.$refs.page;
            const max = el.scrollHeight - el.clientHeight;
            const p = max > 0 ? el.scrollTop / max : 1;
            this.pct = Math.round(p * 100);
            const spans = this.$refs.text.querySelectorAll('.knwf-w');
            const lit = p * spans.length;
            spans.forEach((w, i) => {
                const v = Math.max(0, Math.min(1, lit - i));
                w.style.setProperty('--lit', v.toFixed(3));
            });
        },
        onScroll() {
            cancelAnimationFrame(this.raf);
            this.raf = requestAnimationFrame(() => this.measure());
        },
        again() {
            const calm = !matchMedia('(prefers-reduced-motion: reduce)').matches;
            this.$refs.page.scrollTo({ top: 0, behavior: calm ? 'smooth' : 'auto' });
        },
        init() { this.measure(); },
        destroy() { cancelAnimationFrame(this.raf); },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Reading that lights its own way', 'خواندنی که راهِ خودش را روشن می‌کند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Every word carries a share of the reading progress: as the pane scrolls, colour climbs the paragraph exactly as fast as the reader — dim grey ahead of them, full ink behind. The sticky marker keeps the percentage and the rail honest, and one rAF-throttled handler does all the work.', 'هر واژه سهمی از پیشرفت خواندن دارد: با اسکرولِ قاب، رنگ دقیقاً به همان سرعت خواننده بالا می‌آید — خاکستری خاموش جلوتر از او، جوهر کامل پشت سرش. نشانگر چسبان درصد و ریل را صادق نگه می‌دارد و یک گردانندهٔ rAF-throttled همهٔ کار را می‌کند.') }}
        </p>
    </div>

    <div class="knwf-layout">
        <aside class="knwf-rail" aria-hidden="true">
            <b class="knwf-pct" x-text="pctLabel"></b>
            <i class="knwf-track"><b class="knwf-fill" x-bind:style="`block-size:${pct}%`"></b></i>
        </aside>
        <div class="knwf-page" x-ref="page" x-on:scroll.passive="onScroll">
            <p class="knwf-text" x-ref="text" lang="{{ $fa ? 'fa' : 'en' }}">
                @foreach ($words as $w)
                    <span class="knwf-w">{{ $w }}</span>{{ ' ' }}
                @endforeach
            </p>
        </div>
    </div>
    <p class="knwf-hint">
        {{ $say('--lit per word · scrollTop ÷ (scrollHeight − clientHeight) · rAF-throttled · instant under reduced motion', '--lit برای هر واژه · scrollTop ÷ (scrollHeight − clientHeight) · با throttle فریمی · بی‌ترنزیشن در حرکت کاهش‌یافته') }}
    </p>

    <div class="knwf-foot">
        <button type="button" class="knwf-again" x-on:click="again()">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
            {{ $say('Read it again', 'از نو بخوان') }}
        </button>
    </div>
</section>
