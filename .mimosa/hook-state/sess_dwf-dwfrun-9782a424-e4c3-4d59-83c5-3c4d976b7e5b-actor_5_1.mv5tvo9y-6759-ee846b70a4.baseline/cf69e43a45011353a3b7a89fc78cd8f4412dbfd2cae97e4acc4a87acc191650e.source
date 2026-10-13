{{--
    state-morph's real scenario: four product buttons whose icons morph
    between two states on click — the heart fills with a particle burst, the
    bell swings with a springy shake while its badge counts, play/pause
    glide through a sliding window, the bookmark fills and scatters. Every
    state is mirrored into aria-pressed and spelled out beside the button.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ims-grid {
        --ims-fg: #1c1917; --ims-muted: #78716c; --ims-border: #e7e5e4;
        --ims-bg: #ffffff; --ims-accent: #4f46e5; --ims-pink: #ec4899;
        display: grid;
        gap: .9rem;
        grid-template-columns: repeat(auto-fit, minmax(12.5rem, 1fr));
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--ims-fg);
    }
    html[data-theme="dark"] .ims-grid {
        --ims-fg: #fafaf9; --ims-muted: #a8a29e; --ims-border: #292524;
        --ims-bg: #1c1917; --ims-accent: #a5b4fc; --ims-pink: #f472b6;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ims-grid {
            --ims-fg: #fafaf9; --ims-muted: #a8a29e; --ims-border: #292524;
            --ims-bg: #1c1917; --ims-accent: #a5b4fc; --ims-pink: #f472b6;
        }
    }
    .ims-card {
        display: grid;
        gap: .9rem;
        justify-items: center;
        padding: 1.4rem 1rem 1.2rem;
        border: 1px solid var(--ims-border);
        border-radius: 1rem;
        background: var(--ims-bg);
        text-align: center;
    }
    .ims-btn {
        display: inline-grid;
        justify-items: center;
        gap: .55rem;
        padding: .8rem 1.4rem;
        border: 1px solid var(--ims-border);
        border-radius: 1rem;
        background: transparent;
        color: inherit;
        font: 600 .82rem/1.2 inherit;
        cursor: pointer;
        transition: border-color .2s ease, background-color .2s ease;
    }
    .ims-btn:hover { border-color: var(--ims-accent); }
    .ims-btn:focus-visible { outline: 2px solid var(--ims-accent); outline-offset: 2px; }
    .ims-card[data-on="true"] .ims-btn { background: color-mix(in oklab, var(--ims-accent) 8%, transparent); border-color: var(--ims-accent); }
    .ims-card[data-on="true"] .ims-heart { color: var(--ims-pink); border-color: var(--ims-pink); }
    .ims-card[data-on="true"] .ims-mark { color: var(--ims-accent); }
    .ims-ico { position: relative; display: grid; place-items: center; inline-size: 2rem; block-size: 2rem; }
    .ims-ico > svg { inline-size: 1.7rem; block-size: 1.7rem; overflow: visible; }
    .ims-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .ims-fill { fill: currentColor; stroke: none; scale: 0; transform-box: fill-box; transform-origin: center; }
    .ims-card[data-on="true"] .ims-fill { scale: 1; animation: ims-pop .5s cubic-bezier(.2, .9, .3, 1.35); }
    .ims-bellswing { transform-box: fill-box; transform-origin: 50% 8%; }
    .ims-card[data-on="true"] .ims-bellswing { animation: ims-ring .8s cubic-bezier(.36, .07, .19, .97); }
    .ims-badge {
        position: absolute; inset-block-start: -.35rem; inset-inline-end: -.55rem;
        min-inline-size: 1rem; padding: .1rem .3rem;
        border-radius: 999px; background: var(--ims-accent); color: #fff;
        font-size: .62rem; font-weight: 700; line-height: 1.5;
    }
    .ims-card[data-on="true"] .ims-badge { animation: ims-pop .4s cubic-bezier(.2, .9, .3, 1.35); }
    .ims-window { inline-size: 1.7rem; block-size: 1.7rem; overflow: clip; }
    .ims-track { display: flex; inline-size: 3.4rem; block-size: 1.7rem; transition: translate .35s cubic-bezier(.3, .7, .2, 1); }
    .ims-track[data-on="true"] { translate: -1.7rem 0; }
    .ims-half { flex: none; inline-size: 1.7rem; block-size: 1.7rem; }
    .ims-dot {
        position: absolute; inline-size: 4px; block-size: 4px; border-radius: 50%;
        background: currentColor; pointer-events: none;
        animation: ims-burst .65s ease-out forwards;
    }
    .ims-title { margin: 0; font-size: .82rem; font-weight: 700; }
    .ims-meta { margin: 0; font-size: .74rem; color: var(--ims-muted); }
    .ims-state {
        display: inline-block; padding: .18rem .7rem; border-radius: 999px;
        font-size: .68rem; font-weight: 700; letter-spacing: .02em;
        background: color-mix(in oklab, var(--ims-fg) 7%, transparent); color: var(--ims-muted);
    }
    .ims-state[data-on="true"] { background: color-mix(in oklab, var(--ims-accent) 15%, transparent); color: var(--ims-accent); }
    @keyframes ims-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
    @keyframes ims-burst { from { opacity: 1; translate: 0 0; scale: 1; } to { opacity: 0; translate: var(--dx) var(--dy); scale: .2; } }
    @keyframes ims-ring {
        0%, 100% { rotate: 0deg; }
        18% { rotate: 15deg; } 38% { rotate: -12deg; } 58% { rotate: 8deg; } 78% { rotate: -5deg; }
    }
    @media (prefers-reduced-motion: reduce) {
        .ims-fill, .ims-badge { animation: none; }
        .ims-card[data-on="true"] .ims-bellswing { animation: none; }
        .ims-track { transition: none; }
        .ims-dot { display: none; }
        .ims-btn { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Icons that earn their click', 'آیکون‌هایی که کلیکشان را می‌ارزند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Lordicon-style state morphs on real buttons: the heart bursts into particles as it fills, the bell swings a springy shake while its badge counts, play and pause glide through a sliding window, the bookmark fills and scatters. Every state is mirrored into aria-pressed.', 'مورف‌های حالت به سبک لردیکون روی دکمه‌های واقعی: قلب با انفجار ذره‌ها پر می‌شود، زنگ با تکان فنری می‌لرزد و بجش می‌شمارد، پخش و توقف در پنجرهٔ لغزان در هم می‌لغزند، بوکمارک پر می‌شود و ذره می‌پاشد. هر حالت در aria-pressed آینه می‌شود.') }}
        </p>
    </div>

    <div class="ims-grid"
        x-data="{
            fa: @js($fa),
            fmt(n) { const s = String(n); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
            dots: { heart: [], mark: [] },
            timers: [],
            burst(key) {
                const nonce = Date.now();
                this.dots[key] = Array.from({ length: 8 }, (_, i) => {
                    const a = (Math.PI * 2 * i) / 8 + Math.random() * .6;
                    const r = 24 + Math.random() * 12;
                    return { k: nonce + '-' + i, dx: Math.round(Math.cos(a) * r), dy: Math.round(Math.sin(a) * r) };
                });
                this.timers.push(setTimeout(() => (this.dots[key] = []), 700));
            },
            destroy() { this.timers.forEach((t) => clearTimeout(t)); },
        }">

        {{-- heart --}}
        <article class="ims-card"
            x-data="{ on: false, count: 2481, toggle() { this.on = !this.on; if (this.on) { this.count++; burst('heart'); } } }"
            x-bind:data-on="on ? 'true' : 'false'">
            <button type="button" class="ims-btn ims-heart" x-bind:aria-pressed="on ? 'true' : 'false'" x-on:click="toggle()">
                <span class="ims-ico">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="ims-line" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        <path class="ims-fill" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                    <template x-for="dot in dots.heart" :key="dot.k">
                        <span class="ims-dot" :style="`--dx: ${dot.dx}px; --dy: ${dot.dy}px`"></span>
                    </template>
                </span>
                <span class="ims-label" x-text="on ? (fa ? 'پسندیدی' : 'Liked') : (fa ? 'پسند' : 'Like')"></span>
            </button>
            <h4 class="ims-title">{{ $say('Signal · launch post', 'سیگنال · پست رونمایی') }}</h4>
            <p class="ims-meta"><span x-text="fmt(count)"></span> {{ $say('people liked this', 'نفر این را پسندیدند') }}</p>
            <span class="ims-state" x-bind:data-on="on ? 'true' : 'false'"
                  x-text="on ? (fa ? 'فعال' : 'active') : (fa ? 'خاموش' : 'idle')"></span>
        </article>

        {{-- bell --}}
        <article class="ims-card"
            x-data="{ on: false, count: 2, toggle() { this.on = !this.on; if (this.on) this.count++; } }"
            x-bind:data-on="on ? 'true' : 'false'">
            <button type="button" class="ims-btn" x-bind:aria-pressed="on ? 'true' : 'false'" x-on:click="toggle()">
                <span class="ims-ico">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <g class="ims-bellswing">
                            <path class="ims-line" d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        </g>
                        <path class="ims-line" d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    <span class="ims-badge" x-show="on" x-text="fmt(count)"></span>
                </span>
                <span class="ims-label" x-text="on ? (fa ? 'هشدار روشن است' : 'Alerts on') : (fa ? 'هشدار قیمت' : 'Price alert')"></span>
            </button>
            <h4 class="ims-title">{{ $say('Meridian · EUR/USD watch', 'مریدین · زیر نظر داشتن EUR/USD') }}</h4>
            <p class="ims-meta"><span x-text="fmt(count)"></span> {{ $say('alerts today', 'هشدار امروز') }}</p>
            <span class="ims-state" x-bind:data-on="on ? 'true' : 'false'"
                  x-text="on ? (fa ? 'فعال' : 'active') : (fa ? 'خاموش' : 'idle')"></span>
        </article>

        {{-- play / pause --}}
        <article class="ims-card" x-data="{ on: false }" x-bind:data-on="on ? 'true' : 'false'">
            <button type="button" class="ims-btn" x-bind:aria-pressed="on ? 'true' : 'false'" x-on:click="on = !on">
                <span class="ims-window" dir="ltr">
                    <span class="ims-track" x-bind:data-on="on ? 'true' : 'false'">
                        <svg class="ims-half" viewBox="0 0 24 24" aria-hidden="true"><path class="ims-line" d="m7 4 13 8-13 8Z"/></svg>
                        <svg class="ims-half" viewBox="0 0 24 24" aria-hidden="true"><path class="ims-line" d="M7 4h3v16H7zM14 4h3v16h-3z"/></svg>
                    </span>
                </span>
                <span class="ims-label" x-text="on ? (fa ? 'در حال پخش' : 'Playing') : (fa ? 'پخش ویدیو' : 'Play video')"></span>
            </button>
            <h4 class="ims-title">{{ $say('Onboarding · chapter 1', 'آموزش · فصل ۱') }}</h4>
            <p class="ims-meta">{{ $say('4:12 · product tour', '۴:۱۲ · تور محصول') }}</p>
            <span class="ims-state" x-bind:data-on="on ? 'true' : 'false'"
                  x-text="on ? (fa ? 'فعال' : 'active') : (fa ? 'خاموش' : 'idle')"></span>
        </article>

        {{-- bookmark --}}
        <article class="ims-card"
            x-data="{ on: false, toggle() { this.on = !this.on; if (this.on) burst('mark'); } }"
            x-bind:data-on="on ? 'true' : 'false'">
            <button type="button" class="ims-btn ims-mark" x-bind:aria-pressed="on ? 'true' : 'false'" x-on:click="toggle()">
                <span class="ims-ico">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="ims-line" d="M6 3h12a1 1 0 0 1 1 1v17l-7-4.5L5 21V4a1 1 0 0 1 1-1Z"/>
                        <path class="ims-fill" d="M6 3h12a1 1 0 0 1 1 1v17l-7-4.5L5 21V4a1 1 0 0 1 1-1Z"/>
                    </svg>
                    <template x-for="dot in dots.mark" :key="dot.k">
                        <span class="ims-dot" :style="`--dx: ${dot.dx}px; --dy: ${dot.dy}px`"></span>
                    </template>
                </span>
                <span class="ims-label" x-text="on ? (fa ? 'ذخیره شد' : 'Saved') : (fa ? 'ذخیره برای بعد' : 'Read later')"></span>
            </button>
            <h4 class="ims-title">{{ $say('Field notes · Q4 report', 'یادداشت‌های میدانی · گزارش Q4') }}</h4>
            <p class="ims-meta">{{ $say('Goes to your reading list', 'به فهرست خواندنت می‌رود') }}</p>
            <span class="ims-state" x-bind:data-on="on ? 'true' : 'false'"
                  x-text="on ? (fa ? 'فعال' : 'active') : (fa ? 'خاموش' : 'idle')"></span>
        </article>
    </div>
</section>
