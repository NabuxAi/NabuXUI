{{--
    Horizontal scroll strip: a 320vh stage whose sticky viewport carries a
    horizontal track of project cards. One rAF-throttled scroll pass measures
    the stage against its own position and writes a single progress variable;
    the travel distance is re-measured on resize so there is never any
    overflow, and the direction sign flips for RTL.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .hst-root {
        --hst-p: 0; --hst-shift: 0px;
        --hst-text: #1d1b2e; --hst-muted: #5c5a75; --hst-border: #e4e4ef;
        --hst-surface: #ffffff; --hst-stage: #f4f4fa; --hst-chip: #eeeef7;
        font-family: var(--nx-font-display, system-ui);
        color: var(--hst-text);
    }
    html[data-theme="dark"] .hst-root {
        --hst-text: #f0eefc; --hst-muted: #a9a6c6; --hst-border: #34314e;
        --hst-surface: #1b1a2c; --hst-stage: #12111f; --hst-chip: #262440;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hst-root {
            --hst-text: #f0eefc; --hst-muted: #a9a6c6; --hst-border: #34314e;
            --hst-surface: #1b1a2c; --hst-stage: #12111f; --hst-chip: #262440;
        }
    }
    .hst-stage { position: relative; display: grid; border-radius: 1.25rem;
                 border: 1px solid var(--hst-border); background: var(--hst-stage); overflow: clip; }
    .hst-view { position: sticky; inset-block-start: 4.5rem; block-size: min(84vh, 44rem);
                display: grid; grid-template-rows: auto 1fr auto; gap: 1rem;
                padding: clamp(1rem, 3vw, 1.75rem); }
    .hst-head { display: grid; gap: .3rem; text-align: center; }
    .hst-head h3 { margin: 0; font-size: clamp(1.2rem, 3.4vw, 1.6rem); font-weight: 700; }
    .hst-head p { margin: 0; color: var(--hst-muted); font-size: .92rem; }
    .hst-lane { position: relative; overflow: clip; border-radius: 1.25rem; }
    .hst-track {
        --hst-dir: -1;
        display: flex; align-items: center; gap: clamp(.85rem, 2.5vw, 1.4rem);
        padding-inline: clamp(.5rem, 2vw, 1.5rem); inline-size: max-content;
        block-size: 100%;
        translate: calc(var(--hst-p) * var(--hst-shift) * var(--hst-dir)) 0;
    }
    html[dir="rtl"] .hst-track { --hst-dir: 1; }
    .hst-card {
        inline-size: min(76vw, 24rem); display: grid; grid-template-rows: auto 1fr;
        border-radius: 1.25rem; border: 1px solid var(--hst-border);
        background: var(--hst-surface); overflow: clip; margin: 0;
        box-shadow: 0 14px 34px rgba(24, 20, 52, .10);
    }
    .hst-card svg { display: block; inline-size: 100%; block-size: auto; aspect-ratio: 16 / 10; }
    .hst-cap { display: grid; gap: .45rem; padding: .9rem 1.1rem 1.05rem; }
    .hst-cap h4 { margin: 0; font-size: 1.05rem; font-weight: 700; }
    .hst-meta { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem;
                color: var(--hst-muted); font-size: .82rem; }
    .hst-state { display: inline-flex; align-items: center; gap: .4rem; padding: .22rem .7rem;
                 border-radius: 999px; background: var(--hst-chip); font-weight: 600; }
    .hst-state i { inline-size: .5rem; aspect-ratio: 1; border-radius: 50%; background: var(--hst-live, #2f9e6e); }
    .hst-state[data-state="canary"] { --hst-live: #c2530f; }
    .hst-state[data-state="scheduled"] { --hst-live: #8f8cb0; }
    .hst-meter { display: grid; gap: .45rem; }
    .hst-bar { block-size: 4px; border-radius: 999px; background: var(--hst-chip); overflow: clip; }
    .hst-bar span { display: block; block-size: 100%; border-radius: 999px;
                    background: #5647e6; inline-size: calc(var(--hst-p) * 100%); }
    html[data-theme="dark"] .hst-bar span { background: #8f83ff; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hst-bar span { background: #8f83ff; }
    }
    .hst-readout { margin: 0; text-align: center; font-size: .82rem; color: var(--hst-muted);
                   font-variant-numeric: tabular-nums; }
    :where(.nx-js) .pg:has(.hst-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .hst-view { inset-block-start: 3.25rem; block-size: 88vh; }
        .hst-card { inline-size: 80vw; }
    }
    @media (prefers-reduced-motion: reduce) {
        .hst-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="hst-root"
    x-data="{
        p: 0,
        fa: @json($fa),
        num(s) { return String(s).replace(/[0-9]/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]); },
        stop: () => {},
        init() {
            const root = this.$root, stage = root.querySelector('.hst-stage'),
                  lane = root.querySelector('.hst-lane'), track = root.querySelector('.hst-track');
            let frame = 0, last = -1;
            const measure = () => {
                frame = 0;
                const rect = stage.getBoundingClientRect();
                const span = Math.max(rect.height - window.innerHeight, 1);
                const next = Math.min(1, Math.max(0, -rect.top / span));
                root.style.setProperty('--hst-shift', Math.max(track.scrollWidth - lane.clientWidth, 0) + 'px');
                const v = Math.round(next * 1000) / 1000;
                if (v !== last) { last = v; this.p = v; root.style.setProperty('--hst-p', v); }
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
            {{ $say('Keep scrolling down — the wheel never waits for a sideways drag. The five rollout projects of the quarter slide by on a sticky track, and the meter under the frame mirrors the same single progress variable.', 'به پایین اسکرول کنید — چرخ، هیچ‌وقت برای کشیدن به پهلو منتظر نمی‌ماند. پنج پروژهٔ انتشار فصل روی یک track چسبان رد می‌شوند و نشانگر زیر قاب همان یک متغیر پیشرفت را باز می‌گوید.') }}
        </p>

        <div class="hst-stage">
            <div class="hst-view">
                <div class="hst-head">
                    <h3>{{ $say('Rollout strip — Q4', 'نوار انتشار — فصل چهارم') }}</h3>
                    <p>{{ $say('Meridian pipelines across five regions', 'خطوط انتشار مریدین در پنج ناحیه') }}</p>
                </div>
                <div class="hst-lane">
                    <div class="hst-track">
                        <figure class="hst-card">
                            <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Atlas pipeline graph', 'نمودار خط‌لولهٔ اطلس') }}" preserveAspectRatio="xMidYMid slice">
                                <defs><linearGradient id="hst-g1" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0" stop-color="#eceafd" /><stop offset="1" stop-color="#dcd8fb" />
                                </linearGradient></defs>
                                <rect width="320" height="200" fill="url(#hst-g1)" />
                                <path d="M30 152 C 84 152 84 64 146 64 S 226 152 290 120" stroke="#b9b3f5" stroke-width="3" fill="none" stroke-dasharray="1 8" stroke-linecap="round" />
                                <circle cx="30" cy="152" r="13" fill="#5647e6" /><circle cx="30" cy="152" r="5" fill="#fff" />
                                <circle cx="146" cy="64" r="13" fill="#2f9e6e" /><circle cx="146" cy="64" r="5" fill="#fff" />
                                <circle cx="290" cy="120" r="13" fill="#c2530f" /><circle cx="290" cy="120" r="5" fill="#fff" />
                                <rect x="120" y="138" width="22" height="22" rx="6" fill="#5647e6" opacity=".25" />
                                <rect x="196" y="52" width="22" height="22" rx="6" fill="#2f9e6e" opacity=".25" />
                            </svg>
                            <figcaption class="hst-cap">
                                <h4>{{ $say('Atlas pipeline', 'خط‌لولهٔ اطلس') }}</h4>
                                <div class="hst-meta">
                                    <span class="hst-state"><i aria-hidden="true"></i>{{ $say('Live', 'زنده') }}</span>
                                    <span>{{ $say('Frankfurt · eu-de', 'فرانکفورت · eu-de') }}</span>
                                </div>
                            </figcaption>
                        </figure>
                        <figure class="hst-card">
                            <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Aurora deploy waves', 'امواج استقرار شفق') }}" preserveAspectRatio="xMidYMid slice">
                                <defs><linearGradient id="hst-g2" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0" stop-color="#101c33" /><stop offset="1" stop-color="#20344f" />
                                </linearGradient></defs>
                                <rect width="320" height="200" fill="url(#hst-g2)" />
                                <path d="M-10 120 C 60 60 120 160 190 110 S 300 60 340 100" stroke="#4fd1c5" stroke-width="14" fill="none" opacity=".22" stroke-linecap="round" />
                                <path d="M-10 145 C 70 90 130 185 200 135 S 310 85 340 125" stroke="#7be3d3" stroke-width="9" fill="none" opacity=".38" stroke-linecap="round" />
                                <path d="M-10 168 C 80 120 140 205 210 158 S 320 110 340 150" stroke="#c9f5ec" stroke-width="4" fill="none" opacity=".8" stroke-linecap="round" />
                                <circle cx="250" cy="46" r="16" fill="#e8fffa" opacity=".9" />
                            </svg>
                            <figcaption class="hst-cap">
                                <h4>{{ $say('Aurora deploy', 'استقرار شفق') }}</h4>
                                <div class="hst-meta">
                                    <span class="hst-state" data-state="canary"><i aria-hidden="true"></i>{{ $say('Canary', 'کاناری') }}</span>
                                    <span>{{ $say('Stockholm · eu-se', 'استکهلم · eu-se') }}</span>
                                </div>
                            </figcaption>
                        </figure>
                        <figure class="hst-card">
                            <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Harbor release crates', 'صنادیق انتشار بندر') }}" preserveAspectRatio="xMidYMid slice">
                                <defs><linearGradient id="hst-g3" x1="0" y1="0" x2="1" y2="0">
                                    <stop offset="0" stop-color="#e4eefc" /><stop offset="1" stop-color="#cfe0fa" />
                                </linearGradient></defs>
                                <rect width="320" height="200" fill="url(#hst-g3)" />
                                <path d="M160 12 L160 60" stroke="#0f62fe" stroke-width="4" stroke-linecap="round" />
                                <path d="M160 12 L196 26 L160 40" fill="none" stroke="#0f62fe" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                <g fill="#0f62fe">
                                    <rect x="52" y="112" width="56" height="34" rx="8" />
                                    <rect x="116" y="112" width="56" height="34" rx="8" opacity=".55" />
                                    <rect x="180" y="112" width="56" height="34" rx="8" opacity=".3" />
                                    <rect x="84" y="68" width="56" height="34" rx="8" opacity=".75" />
                                    <rect x="148" y="68" width="56" height="34" rx="8" opacity=".4" />
                                </g>
                                <rect x="42" y="152" width="236" height="6" rx="3" fill="#0f62fe" opacity=".2" />
                            </svg>
                            <figcaption class="hst-cap">
                                <h4>{{ $say('Harbor release', 'انتشار بندر') }}</h4>
                                <div class="hst-meta">
                                    <span class="hst-state"><i aria-hidden="true"></i>{{ $say('Live', 'زنده') }}</span>
                                    <span>{{ $say('Singapore · ap-se', 'سنگاپور · ap-se') }}</span>
                                </div>
                            </figcaption>
                        </figure>
                        <figure class="hst-card">
                            <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Cascade flags steps', 'پله‌های فلگ‌های آبشار') }}" preserveAspectRatio="xMidYMid slice">
                                <defs><linearGradient id="hst-g4" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0" stop-color="#fdeee0" /><stop offset="1" stop-color="#fbddc3" />
                                </linearGradient></defs>
                                <rect width="320" height="200" fill="url(#hst-g4)" />
                                <g fill="#c2530f">
                                    <rect x="30" y="46" width="60" height="30" rx="8" opacity=".3" />
                                    <rect x="80" y="84" width="60" height="30" rx="8" opacity=".45" />
                                    <rect x="130" y="122" width="60" height="30" rx="8" opacity=".65" />
                                    <rect x="180" y="160" width="60" height="30" rx="8" />
                                </g>
                                <path d="M240 168 L240 132 L286 142 L240 152" fill="#2f9e6e" />
                                <circle cx="30" cy="61" r="7" fill="#c2530f" />
                                <circle cx="110" cy="99" r="7" fill="#c2530f" opacity=".7" />
                                <circle cx="190" cy="137" r="7" fill="#c2530f" opacity=".5" />
                            </svg>
                            <figcaption class="hst-cap">
                                <h4>{{ $say('Cascade flags', 'فلگ‌های آبشار') }}</h4>
                                <div class="hst-meta">
                                    <span class="hst-state" data-state="scheduled"><i aria-hidden="true"></i>{{ $say('Scheduled', 'برنامه‌ریزی') }}</span>
                                    <span>{{ $say('Vancouver · na-ca', 'ونکوور · na-ca') }}</span>
                                </div>
                            </figcaption>
                        </figure>
                        <figure class="hst-card">
                            <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Polar rollout radar', 'رادار انتشار قطبی') }}" preserveAspectRatio="xMidYMid slice">
                                <defs><linearGradient id="hst-g5" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0" stop-color="#e0f5f7" /><stop offset="1" stop-color="#c5eaee" />
                                </linearGradient></defs>
                                <rect width="320" height="200" fill="url(#hst-g5)" />
                                <g fill="none" stroke="#0e7490" stroke-width="3">
                                    <circle cx="160" cy="112" r="72" opacity=".25" stroke-dasharray="2 8" />
                                    <circle cx="160" cy="112" r="48" opacity=".45" stroke-dasharray="2 8" />
                                    <circle cx="160" cy="112" r="24" opacity=".8" />
                                </g>
                                <path d="M160 112 L160 40 A 72 72 0 0 1 224 76 Z" fill="#0e7490" opacity=".18" />
                                <circle cx="160" cy="112" r="8" fill="#0e7490" />
                                <circle cx="214" cy="66" r="7" fill="#2f9e6e" />
                                <circle cx="112" cy="146" r="7" fill="#c2530f" />
                            </svg>
                            <figcaption class="hst-cap">
                                <h4>{{ $say('Polar rollout', 'انتشار قطبی') }}</h4>
                                <div class="hst-meta">
                                    <span class="hst-state"><i aria-hidden="true"></i>{{ $say('Live', 'زنده') }}</span>
                                    <span>{{ $say('Oslo · eu-no', 'اسلو · eu-no') }}</span>
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                </div>
                <div class="hst-meter">
                    <div class="hst-bar"><span></span></div>
                    <p class="hst-readout">
                        {{ $say('Strip progress', 'پیشرفت نوار') }}:
                        <span x-text="fa ? num(Math.round(p * 100)) : Math.round(p * 100)">0</span>{{ $say('%', '٪') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
