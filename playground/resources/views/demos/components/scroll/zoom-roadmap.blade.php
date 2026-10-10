{{--
    Zoom roadmap on a 300vh stage: the board sticks while a vertical line fills
    with the stage's own scroll progress, and every station zooms in from a
    small blurred offset as its gate opens. The last station is the CTA card,
    with a live Alpine button. One rAF-throttled pass, cleaned up on destroy —
    the effects/scroll-progress pattern.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .zrm-root {
        --zrm-p: 0;
        --zrm-text: #1d1b2e; --zrm-muted: #5c5a75; --zrm-border: #e4e4ef;
        --zrm-surface: #ffffff; --zrm-stage: #f4f4fa; --zrm-chip: #eeeef7;
        --zrm-accent: #5647e6; --zrm-line: #dcd9ee;
        font-family: var(--nx-font-display, system-ui);
        color: var(--zrm-text);
    }
    html[data-theme="dark"] .zrm-root {
        --zrm-text: #f0eefc; --zrm-muted: #a9a6c6; --zrm-border: #34314e;
        --zrm-surface: #1b1a2c; --zrm-stage: #12111f; --zrm-chip: #262440;
        --zrm-accent: #8f83ff; --zrm-line: #312e4c;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .zrm-root {
            --zrm-text: #f0eefc; --zrm-muted: #a9a6c6; --zrm-border: #34314e;
            --zrm-surface: #1b1a2c; --zrm-stage: #12111f; --zrm-chip: #262440;
            --zrm-accent: #8f83ff; --zrm-line: #312e4c;
        }
    }
    .zrm-stage { position: relative; display: grid; border-radius: 1.25rem;
                 border: 1px solid var(--zrm-border); background: var(--zrm-stage); overflow: clip;
                 min-block-size: 300vh; }
    .zrm-board { position: sticky; inset-block-start: 3.25rem; inline-size: min(100%, 36rem);
                 margin-inline: auto; padding: clamp(1rem, 3vw, 1.5rem); display: grid; gap: 1rem; }
    .zrm-head { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem; }
    .zrm-head h3 { margin: 0; font-size: clamp(1.15rem, 3.2vw, 1.5rem); font-weight: 800; }
    .zrm-meter { margin-inline-start: auto; display: inline-flex; align-items: center; gap: .5rem;
                 padding: .4rem .95rem; border-radius: 999px; background: var(--zrm-surface);
                 border: 1px solid var(--zrm-border); font-size: .85rem; font-weight: 700;
                 font-variant-numeric: tabular-nums; }
    .zrm-meter i { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: var(--zrm-accent); }
    .zrm-rail { position: relative; display: grid; gap: 1.1rem; padding-block-start: .35rem; }
    .zrm-line { position: absolute; inset-block: .3rem 3.2rem; inset-inline-start: 1.02rem;
                inline-size: 3px; border-radius: 999px; background: var(--zrm-line); }
    .zrm-fill { display: block; inline-size: 100%; block-size: calc(var(--zrm-p) * 100%);
                border-radius: 999px; background: var(--zrm-accent); }
    .zrm-stop { --zrm-on: 0; position: relative; padding-inline-start: 2.9rem; }
    .zrm-node { position: absolute; inset-block-start: .95rem; inset-inline-start: .47rem;
                inline-size: 1.1rem; aspect-ratio: 1; border-radius: 50%;
                background: color-mix(in oklab, #2f9e6e calc(var(--zrm-on) * 100%), #c9c9dd);
                box-shadow: 0 0 0 calc(var(--zrm-on) * 4px) color-mix(in oklab, #2f9e6e 22%, transparent);
                scale: calc(.6 + var(--zrm-on) * .4); }
    .zrm-card { display: grid; gap: .3rem; padding: .95rem 1.2rem; border-radius: 1.15rem;
                background: var(--zrm-surface); border: 1px solid var(--zrm-border);
                border-inline-start: 3px solid color-mix(in oklab, var(--zrm-accent) calc(var(--zrm-on) * 100%), var(--zrm-border));
                scale: calc(.74 + .26 * var(--zrm-on)); opacity: var(--zrm-on);
                filter: blur(calc((1 - var(--zrm-on)) * 6px)); }
    .zrm-q { font-size: .72rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
             color: var(--zrm-muted); }
    .zrm-card b { font-size: 1.05rem; font-weight: 700; }
    .zrm-meta { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem;
                font-size: .82rem; color: var(--zrm-muted); }
    .zrm-pill { display: inline-flex; align-items: center; gap: .4rem; padding: .18rem .65rem;
                border-radius: 999px; background: var(--zrm-chip); font-weight: 600; }
    .zrm-pill i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: var(--zrm-live, #2f9e6e); }
    .zrm-pill[data-state="building"] { --zrm-live: #c2530f; }
    .zrm-pill[data-state="design"] { --zrm-live: #5647e6; }
    .zrm-cta { border-inline-start-width: 3px; background:
               linear-gradient(135deg, color-mix(in oklab, var(--zrm-accent) 92%, #101020), color-mix(in oklab, var(--zrm-accent) 55%, #101020));
               color: #fff; gap: .55rem; }
    .zrm-cta .zrm-q { color: rgba(255, 255, 255, .75); }
    .zrm-cta p { margin: 0; font-size: .9rem; color: rgba(255, 255, 255, .85); }
    .zrm-go { justify-self: start; block-size: 2.6rem; padding-inline: 1.4rem; border: none; border-radius: 999px;
              background: #fff; color: #2b2350; font: 700 .95rem var(--nx-font-display, system-ui);
              cursor: pointer; transition: translate .15s ease, box-shadow .15s ease; }
    .zrm-go:hover { translate: 0 -2px; box-shadow: 0 10px 22px rgba(10, 8, 30, .35); }
    .zrm-go:focus-visible { outline: 3px solid #fff; outline-offset: 2px; }
    .zrm-done { margin: 0; font-size: .8rem; color: #d6ffe9; }
    :where(.nx-js) .pg:has(.zrm-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .zrm-board { inset-block-start: 2.5rem; gap: .8rem; }
        .zrm-card { padding: .8rem 1rem; }
        .zrm-rail { gap: .85rem; }
        .zrm-cta .zrm-go { inline-size: 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .zrm-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="zrm-root"
    x-data="{
        p: 0,
        joined: false,
        fa: @json($fa),
        num(s) { return String(s).replace(/[0-9]/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]); },
        stop: () => {},
        init() {
            const root = this.$root, stage = root.querySelector('.zrm-stage'),
                  stops = [...root.querySelectorAll('.zrm-stop')];
            const gate = (i) => i === 0 ? -0.09 : (i / (stops.length - 1)) * 0.86;
            let frame = 0, last = -1;
            const measure = () => {
                frame = 0;
                const rect = stage.getBoundingClientRect();
                const span = Math.max(rect.height - window.innerHeight, 1);
                const next = Math.min(1, Math.max(0, -rect.top / span));
                stops.forEach((stop, i) => {
                    const on = Math.min(1, Math.max(0, (next - gate(i)) / 0.09));
                    stop.style.setProperty('--zrm-on', on.toFixed(3));
                });
                const v = Math.round(next * 1000) / 1000;
                if (v !== last) { last = v; this.p = v; root.style.setProperty('--zrm-p', v); }
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
            {{ $say('The line drinks the scroll and the stations wake in order — each card zooms out of a soft blur the moment its gate opens, and the fourth station is not a milestone but the door: your workspace, two minutes, no card.', 'خط پیشرفت اسکرول را می‌نوشد و ایستگاه‌ها به‌ترتیب بیدار می‌شوند — هر کارت لحظهٔ بازشدن دروازه‌اش از یک بلور نرم بیرون می‌آید و ایستگاه چهارم نشانِ یک راه نیست، خودِ در است: ورک‌اسپیس شما، دو دقیقه، بدون کارت.') }}
        </p>

        <div class="zrm-stage">
            <div class="zrm-board">
                <div class="zrm-head">
                    <h3>{{ $say('Meridian — 2027 roadmap', 'مریدین — نقشهٔ راه ۲۰۲۷') }}</h3>
                    <span class="zrm-meter">
                        <i aria-hidden="true"></i>
                        <span x-text="fa ? num(Math.round(p * 100)) : Math.round(p * 100)">0</span>{{ $say('% shipped', '٪ طی‌شده') }}
                    </span>
                </div>
                <div class="zrm-rail">
                    <span class="zrm-line" aria-hidden="true"><span class="zrm-fill"></span></span>

                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card">
                            <span class="zrm-q">{{ $say('Q1 · shipped', 'فصل ۱ · منتشرشده') }}</span>
                            <b>{{ $say('Flags engine v2', 'موتور فلگ‌ها نسخهٔ ۲') }}</b>
                            <div class="zrm-meta">
                                <span class="zrm-pill"><i aria-hidden="true"></i>{{ $say('Live', 'زنده') }}</span>
                                <span>{{ $say('Dublin lab · flag evaluation under 8ms', 'آزمایشگاه دوبلین · ارزیابی فلگ زیر ۸ms') }}</span>
                            </div>
                        </article>
                    </div>

                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card">
                            <span class="zrm-q">{{ $say('Q2 · in flight', 'فصل ۲ · در جریان') }}</span>
                            <b>{{ $say('Edge agents in 24 regions', 'ایجنت‌های لبه در ۲۴ ناحیه') }}</b>
                            <div class="zrm-meta">
                                <span class="zrm-pill" data-state="building"><i aria-hidden="true"></i>{{ $say('Building', 'در حال اجرا') }}</span>
                                <span>{{ $say('Singapore first · rollback at the edge', 'ابتدا سنگاپور · واگرد روی لبه') }}</span>
                            </div>
                        </article>
                    </div>

                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card">
                            <span class="zrm-q">{{ $say('Q3 · in design', 'فصل ۳ · در طراحی') }}</span>
                            <b>{{ $say('Smart rollout guard', 'نگهبان انتشار هوشمند') }}</b>
                            <div class="zrm-meta">
                                <span class="zrm-pill" data-state="design"><i aria-hidden="true"></i>{{ $say('Design', 'در طراحی') }}</span>
                                <span>{{ $say('Seoul lab · anomaly-gated deploys', 'آزمایشگاه سئول · استقرارِ دروازهٔ ناهنجاری') }}</span>
                            </div>
                        </article>
                    </div>

                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card zrm-cta">
                            <span class="zrm-q">{{ $say('Q4 · you', 'فصل ۴ · شما') }}</span>
                            <b>{{ $say('Start rolling out', 'شروع انتشار') }}</b>
                            <p>{{ $say('Free tier, two minutes, no card — the roadmap ends at your first release.', 'پلن رایگان، دو دقیقه، بدون کارت — نقشهٔ راه به نخستین انتشارِ شما ختم می‌شود.') }}</p>
                            <button type="button" class="zrm-go" x-on:click="joined = true">
                                {{ $say('Create workspace', 'ساخت ورک‌اسپیس') }}
                            </button>
                            <p class="zrm-done" role="status" x-show="joined" x-cloak>
                                {{ $say('Workspace “aurora-42” is ready — first pipeline preloaded.', 'ورک‌اسپیس «aurora-42» آماده شد — نخستین خط انتشار از پیش بار شده است.') }}
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
