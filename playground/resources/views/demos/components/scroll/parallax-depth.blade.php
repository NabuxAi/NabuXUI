{{--
    Parallax depth on a 340vh stage: one sticky scene, three speeds. The ghost
    numerals and the dot grid drift slowly behind, the narrative beats hold
    centre and crossfade, the chips and shapes race past in front. A single
    rAF-throttled scroll pass (measured against the stage's own rect, cleaned
    up on destroy — the effects/scroll-progress pattern) writes the progress
    variable and each beat's brightness.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .pdz-root {
        --pdz-p: 0;
        --pdz-text: #1d1b2e; --pdz-muted: #5c5a75; --pdz-border: #e4e4ef;
        --pdz-surface: #ffffff; --pdz-stage: #f4f4fa; --pdz-ghost: rgba(29, 27, 46, .055);
        font-family: var(--nx-font-display, system-ui);
        color: var(--pdz-text);
    }
    html[data-theme="dark"] .pdz-root {
        --pdz-text: #f0eefc; --pdz-muted: #a9a6c6; --pdz-border: #34314e;
        --pdz-surface: #1b1a2c; --pdz-stage: #12111f; --pdz-ghost: rgba(240, 238, 252, .06);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .pdz-root {
            --pdz-text: #f0eefc; --pdz-muted: #a9a6c6; --pdz-border: #34314e;
            --pdz-surface: #1b1a2c; --pdz-stage: #12111f; --pdz-ghost: rgba(240, 238, 252, .06);
        }
    }
    .pdz-stage { position: relative; display: grid; border-radius: 1.25rem;
                 border: 1px solid var(--pdz-border); background: var(--pdz-stage); overflow: clip; }
    .pdz-view { position: sticky; inset-block-start: 0; block-size: 100svh;
                display: grid; place-items: center; overflow: clip; }
    .pdz-bg, .pdz-fg { position: absolute; inset: 0; pointer-events: none; }
    .pdz-bg {
        background-image: radial-gradient(color-mix(in oklab, var(--pdz-text) 14%, transparent) 1px, transparent 1.5px);
        background-size: 26px 26px;
        display: flex; justify-content: space-around; align-items: center;
        font-weight: 800; font-size: 36vmin; line-height: 1; color: var(--pdz-ghost);
        translate: 0 calc(var(--pdz-p) * -8vh + 4vh);
    }
    .pdz-mid { position: relative; display: grid; inline-size: min(92%, 40rem); text-align: center; }
    .pdz-beat { --pdz-on: 0; grid-area: 1 / 1; margin: 0; display: grid; gap: .6rem; justify-items: center;
                opacity: var(--pdz-on); translate: 0 calc((1 - var(--pdz-on)) * 1.5rem); }
    .pdz-when { display: inline-flex; align-items: center; gap: .5rem; padding: .35rem .95rem;
                border-radius: 999px; border: 1px solid var(--pdz-border); background: var(--pdz-surface);
                font-size: .8rem; font-weight: 600; letter-spacing: .06em; color: var(--pdz-muted); }
    .pdz-when i { inline-size: .5rem; aspect-ratio: 1; border-radius: 50%; background: #2f9e6e; }
    .pdz-beat b { font-size: clamp(1.4rem, 5vw, 2.5rem); line-height: 1.25; font-weight: 800; }
    .pdz-beat small { font-size: .95rem; color: var(--pdz-muted); }
    .pdz-dots { position: absolute; inset-block-end: clamp(1rem, 4vh, 2rem); display: flex; gap: .5rem; }
    .pdz-dot { --pdz-on: 0; inline-size: .55rem; aspect-ratio: 1; border-radius: 999px;
               background: var(--pdz-text); opacity: calc(.25 + var(--pdz-on) * .75);
               scale: calc(.8 + var(--pdz-on) * .35); }
    .pdz-fg { translate: 0 calc(var(--pdz-p) * -30vh + 12vh); }
    .pdz-chip { position: absolute; padding: .55rem 1.05rem; border-radius: 999px;
                background: var(--pdz-surface); border: 1px solid var(--pdz-border);
                font-size: .85rem; font-weight: 700; box-shadow: 0 10px 26px rgba(24, 20, 52, .12);
                white-space: nowrap; }
    .pdz-chip b { color: #2f9e6e; }
    .pdz-c1 { inset-inline-start: 8%; inset-block-start: 22%; }
    .pdz-c2 { inset-inline-end: 7%; inset-block-start: 30%; }
    .pdz-c3 { inset-inline-start: 14%; inset-block-end: 20%; }
    .pdz-shape { position: absolute; opacity: .8; }
    .pdz-s1 { inset-inline-end: 20%; inset-block-start: 16%; }
    .pdz-s2 { inset-inline-start: 22%; inset-block-end: 30%; }
    .pdz-s3 { inset-inline-end: 30%; inset-block-end: 24%; }
    :where(.nx-js) .pg:has(.pdz-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .pdz-bg { font-size: 30vmin; }
        .pdz-chip { font-size: .78rem; padding: .45rem .8rem; }
        .pdz-c2 { inset-block-start: 36%; }
        .pdz-c3 { inset-block-end: 26%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .pdz-bg, .pdz-fg, .pdz-s1, .pdz-s2, .pdz-s3 { translate: none !important; }
        .pdz-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="pdz-root"
    x-data="{
        stop: () => {},
        init() {
            const root = this.$root, stage = root.querySelector('.pdz-stage'),
                  beats = [...root.querySelectorAll('.pdz-beat')],
                  dots = [...root.querySelectorAll('.pdz-dot')];
            const centers = beats.map((_, i) => (i + .5) / beats.length);
            let frame = 0, last = -1;
            const measure = () => {
                frame = 0;
                const rect = stage.getBoundingClientRect();
                const span = Math.max(rect.height - window.innerHeight, 1);
                const p = Math.min(1, Math.max(0, -rect.top / span));
                beats.forEach((beat, i) => {
                    const on = Math.min(1, Math.max(0, (.3 - Math.abs(p - centers[i])) / .18));
                    beat.style.setProperty('--pdz-on', on.toFixed(3));
                    if (dots[i]) dots[i].style.setProperty('--pdz-on', on.toFixed(3));
                });
                const v = Math.round(p * 1000) / 1000;
                if (v !== last) { last = v; root.style.setProperty('--pdz-p', v); }
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            this.stop = () => {
                cancelAnimationFrame(frame);
                window.removeEventListener('scroll', schedule);
                window.removeEventListener('resize', schedule);
            };
            measure();
        },
        destroy() { this.stop(); },
    }">
    <section class="pg-box" style="gap: 1.25rem">
        <p style="margin: 0; color: var(--nx-text-muted); text-align: center; max-inline-size: 56ch; justify-self: center">
            {{ $say('Scroll slowly: the numerals crawl, the story holds, the chips fly. Three speeds over one scroll pass — a day of Meridian releases told in depth, from the first commit in Dublin to the full rollout in São Paulo.', 'آرام اسکرول کنید: اعداد می‌خزند، روایت می‌ایستد، تراشه‌ها می‌پرند. سه سرعت روی یک گذر اسکرول — یک روز انتشار مریدین که با عمق روایت می‌شود، از نخستین کامیت در دوبلین تا انتشار کامل در سائوپائولو.') }}
        </p>

        <div class="pdz-stage">
            <div class="pdz-view">
                <div class="pdz-bg" aria-hidden="true">
                    <span>{{ $say('01', '۰۱') }}</span>
                    <span>{{ $say('02', '۰۲') }}</span>
                    <span>{{ $say('03', '۰۳') }}</span>
                </div>

                <div class="pdz-mid">
                    <p class="pdz-beat">
                        <span class="pdz-when"><i aria-hidden="true"></i>{{ $say('09:15 · Dublin', '۰۹:۱۵ · دوبلین') }}</span>
                        <b>{{ $say('One commit starts the story', 'یک کامیت، شروع داستان') }}</b>
                        <small>{{ $say('2 reviewed files · 1 green build', '۲ فایل بازبینی‌شده · ۱ بیلد سبز') }}</small>
                    </p>
                    <p class="pdz-beat">
                        <span class="pdz-when"><i aria-hidden="true"></i>{{ $say('11:40 · Singapore', '۱۱:۴۰ · سنگاپور') }}</span>
                        <b>{{ $say('Canary on 5% of traffic', 'کاناری روی ۵٪ ترافیک') }}</b>
                        <small>{{ $say('Error rate flat · 9 regions watching', 'نرخ خطا پایدار · ۹ ناحیه در حال دیدن') }}</small>
                    </p>
                    <p class="pdz-beat">
                        <span class="pdz-when"><i aria-hidden="true"></i>{{ $say('16:20 · São Paulo', '۱۶:۲۰ · سائوپائولو') }}</span>
                        <b>{{ $say('Full rollout, nobody on call', 'انتشار کامل، بدون شب‌بیداری') }}</b>
                        <small>{{ $say('24 regions · 12-minute rollback in reach', '۲۴ ناحیه · واگرد ۱۲ دقیقه‌ای در دسترس') }}</small>
                    </p>
                    <div class="pdz-dots" aria-hidden="true">
                        <span class="pdz-dot"></span>
                        <span class="pdz-dot"></span>
                        <span class="pdz-dot"></span>
                    </div>
                </div>

                <div class="pdz-fg" aria-hidden="true">
                    <span class="pdz-chip pdz-c1">{{ $say('<b>+23%</b> deploy frequency', '<b>۲۳٪+</b> فراوانی انتشار') }}</span>
                    <span class="pdz-chip pdz-c2">{{ $say('<b>99.98%</b> uptime', '<b>۹۹٫۹۸٪</b> آپ‌تایم') }}</span>
                    <span class="pdz-chip pdz-c3">{{ $say('<b>8</b> engineers · <b>3</b> continents', '<b>۸</b> مهندس · <b>۳</b> قاره') }}</span>
                    <svg class="pdz-shape pdz-s1" width="54" height="54" viewBox="0 0 54 54">
                        <circle cx="27" cy="27" r="20" fill="none" stroke="#5647e6" stroke-width="5" opacity=".5" />
                    </svg>
                    <svg class="pdz-shape pdz-s2" width="44" height="44" viewBox="0 0 44 44">
                        <path d="M22 6 V38 M6 22 H38" stroke="#2f9e6e" stroke-width="6" stroke-linecap="round" opacity=".45" />
                    </svg>
                    <svg class="pdz-shape pdz-s3" width="36" height="36" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="10" fill="#c2530f" opacity=".3" />
                    </svg>
                </div>
            </div>
        </div>
    </section>
</div>
