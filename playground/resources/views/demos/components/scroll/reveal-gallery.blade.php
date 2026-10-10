{{--
    Clip reveal gallery: four Meridian customer stories on an asymmetric
    12-column grid. One rAF-throttled scroll pass (measured against each
    frame's own position, cleaned up on destroy — the effects/scroll-progress
    pattern) writes every frame's reveal value; the clip-path masks and the
    rising captions read it, so the reveal runs equally forwards and backwards.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .rgv-root {
        --rgv-text: #1d1b2e; --rgv-muted: #5c5a75; --rgv-border: #e4e4ef;
        --rgv-surface: #ffffff; --rgv-stage: #f4f4fa;
        font-family: var(--nx-font-display, system-ui);
        color: var(--rgv-text);
    }
    html[data-theme="dark"] .rgv-root {
        --rgv-text: #f0eefc; --rgv-muted: #a9a6c6; --rgv-border: #34314e;
        --rgv-surface: #1b1a2c; --rgv-stage: #12111f;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .rgv-root {
            --rgv-text: #f0eefc; --rgv-muted: #a9a6c6; --rgv-border: #34314e;
            --rgv-surface: #1b1a2c; --rgv-stage: #12111f;
        }
    }
    .rgv-stage { position: relative; display: grid; border-radius: 1.25rem;
                 border: 1px solid var(--rgv-border); background: var(--rgv-stage);
                 padding: clamp(1.25rem, 4vw, 3rem); min-block-size: 260vh;
                 align-content: start; overflow: clip; }
    .rgv-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 2.75rem 1.25rem; }
    .rgv-item { --rgv-r: 0; margin: 0; display: grid; gap: .9rem; }
    .rgv-i1 { grid-column: 1 / span 7; }
    .rgv-i2 { grid-column: 6 / span 7; margin-block-start: 4.5rem; }
    .rgv-i3 { grid-column: 2 / span 7; }
    .rgv-i4 { grid-column: 7 / span 6; margin-block-start: 3.5rem; }
    .rgv-frame { position: relative; border-radius: 1.25rem; overflow: clip;
                 border: 1px solid var(--rgv-border); background: var(--rgv-surface); }
    .rgv-frame svg { display: block; inline-size: 100%; block-size: auto; aspect-ratio: 16 / 10; }
    /* Four signatures: symmetric inset, off-centre circle, diagonal wipe, vertical curtain */
    .rgv-i1 .rgv-frame { clip-path: inset(calc((1 - var(--rgv-r)) * 46%) calc((1 - var(--rgv-r)) * 44%) round 1.25rem); }
    .rgv-i2 .rgv-frame { clip-path: circle(calc(6% + var(--rgv-r) * 72%) at 32% 42%); }
    .rgv-i3 .rgv-frame { clip-path: polygon(0% 0%, calc(14% + var(--rgv-r) * 86%) 0%,
                          calc(8% + var(--rgv-r) * 92%) calc(14% + var(--rgv-r) * 86%),
                          0% calc(26% + var(--rgv-r) * 74%)); }
    .rgv-i4 .rgv-frame { clip-path: inset(calc((1 - var(--rgv-r)) * 48%) 7% round 1.25rem); }
    .rgv-cap { display: grid; gap: .3rem;
               opacity: var(--rgv-r); translate: 0 calc((1 - var(--rgv-r)) * 1.25rem); }
    .rgv-cap small { font-size: .76rem; font-weight: 700; letter-spacing: .09em;
                     text-transform: uppercase; color: var(--rgv-muted); }
    .rgv-cap b { font-size: 1.2rem; font-weight: 800; }
    .rgv-cap p { margin: 0; font-size: .95rem; color: var(--rgv-muted); }
    .rgv-cap p strong { color: #2f9e6e; font-weight: 700; }
    :where(.nx-js) .pg:has(.rgv-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 640px) {
        .rgv-grid { gap: 2.25rem; }
        .rgv-i1, .rgv-i2, .rgv-i3, .rgv-i4 { grid-column: 1 / -1; margin-block-start: 0; }
    }
    @media (prefers-reduced-motion: reduce) {
        .rgv-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="rgv-root"
    x-data="{
        stop: () => {},
        init() {
            const items = [...this.$root.querySelectorAll('.rgv-item')];
            let frame = 0;
            const measure = () => {
                frame = 0;
                items.forEach((item) => {
                    const top = item.getBoundingClientRect().top;
                    const r = Math.min(1, Math.max(0, (window.innerHeight * .88 - top) / (window.innerHeight * .5)));
                    item.style.setProperty('--rgv-r', r.toFixed(3));
                });
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
            {{ $say('Scroll back up and the curtains close again — every reveal is measured against its own frame, so the four masks run forwards and backwards with the wheel: an inset shutter, an off-centre iris, a diagonal wipe, a vertical curtain.', 'به بالا برگردید تا پرده‌ها دوباره بسته شوند — هر آشکارسازی نسبت به قاب خودش سنجیده می‌شود، پس هر چهار ماسک همراه چرخ، پیش و پس می‌روند: شاترِ inset، عنبیهٔ نامرکز، برش مورب، پردهٔ عمودی.') }}
        </p>

        <div class="rgv-stage">
            <div class="rgv-grid">
                <figure class="rgv-item rgv-i1">
                    <div class="rgv-frame">
                        <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Northwind routes over the Baltic', 'مسیرهای نورث‌ویند روی بالتیک') }}">
                            <defs><linearGradient id="rgv-g1" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0" stop-color="#e8f4ef" /><stop offset="1" stop-color="#d3e9e0" />
                            </linearGradient></defs>
                            <rect width="320" height="200" fill="url(#rgv-g1)" />
                            <g fill="none" stroke="#2f9e6e" stroke-width="3" stroke-dasharray="1 7" stroke-linecap="round">
                                <path d="M52 148 C 110 92 150 150 208 96" />
                                <path d="M92 158 C 150 110 196 158 258 108" />
                            </g>
                            <g fill="#173f30">
                                <circle cx="52" cy="148" r="9" /><circle cx="208" cy="96" r="9" />
                                <circle cx="258" cy="108" r="9" />
                            </g>
                            <rect x="120" y="118" width="26" height="18" rx="5" fill="#2f9e6e" opacity=".85" />
                            <rect x="152" y="112" width="26" height="18" rx="5" fill="#2f9e6e" opacity=".5" />
                        </svg>
                    </div>
                    <figcaption class="rgv-cap">
                        <small>{{ $say('Copenhagen · logistics', 'کپنهاک · لجستیک') }}</small>
                        <b>Northwind Logistics</b>
                        <p>{{ $say('<strong>+38%</strong> deploy frequency across 4 fleets', 'فراوانی انتشار <strong>۳۸٪+</strong> در ۴ ناوگان') }}</p>
                    </figcaption>
                </figure>

                <figure class="rgv-item rgv-i2">
                    <div class="rgv-frame">
                        <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Kite Payments card streams', 'جریان‌های کارتی کایت پی‌منت') }}">
                            <defs><linearGradient id="rgv-g2" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#eceafd" /><stop offset="1" stop-color="#dbd7fb" />
                            </linearGradient></defs>
                            <rect width="320" height="200" fill="url(#rgv-g2)" />
                            <rect x="70" y="66" width="150" height="92" rx="14" fill="#5647e6" />
                            <rect x="86" y="84" width="60" height="10" rx="5" fill="#ffffff" opacity=".85" />
                            <rect x="86" y="104" width="96" height="8" rx="4" fill="#ffffff" opacity=".45" />
                            <rect x="86" y="124" width="34" height="18" rx="6" fill="#ffffff" opacity=".9" />
                            <g fill="#2f9e6e">
                                <circle cx="246" cy="70" r="8" /><circle cx="264" cy="102" r="8" /><circle cx="252" cy="136" r="8" />
                            </g>
                            <path d="M224 84 C 244 92 244 116 232 128" fill="none" stroke="#2f9e6e" stroke-width="3" stroke-dasharray="1 6" stroke-linecap="round" />
                        </svg>
                    </div>
                    <figcaption class="rgv-cap">
                        <small>{{ $say('Singapore · payments', 'سنگاپور · پرداخت') }}</small>
                        <b>Kite Payments</b>
                        <p>{{ $say('Canary in <strong>9 regions</strong>, zero downtime', 'کاناری در <strong>۹ ناحیه</strong>، بدون قطعی') }}</p>
                    </figcaption>
                </figure>

                <figure class="rgv-item rgv-i3">
                    <div class="rgv-frame">
                        <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Aster Health pulse', 'نبض استر هلث') }}">
                            <defs><linearGradient id="rgv-g3" x1="0" y1="0" x2="1" y2="0">
                                <stop offset="0" stop-color="#fdeee0" /><stop offset="1" stop-color="#fbddc1" />
                            </linearGradient></defs>
                            <rect width="320" height="200" fill="url(#rgv-g3)" />
                            <path d="M20 110 H84 L100 74 L120 146 L140 92 L154 110 H300"
                                  fill="none" stroke="#c2530f" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
                            <g fill="#c2530f">
                                <rect x="146" y="30" width="28" height="86" rx="8" />
                                <rect x="117" y="59" width="86" height="28" rx="8" />
                            </g>
                            <circle cx="272" cy="52" r="14" fill="#c2530f" opacity=".2" />
                            <circle cx="42" cy="150" r="10" fill="#c2530f" opacity=".2" />
                        </svg>
                    </div>
                    <figcaption class="rgv-cap">
                        <small>{{ $say('Toronto · health tech', 'تورنتو · سلامت دیجیتال') }}</small>
                        <b>Aster Health</b>
                        <p>{{ $say('Rollback from <strong>4 hours to 12 minutes</strong>', 'واگرد از <strong>۴ ساعت به ۱۲ دقیقه</strong>') }}</p>
                    </figcaption>
                </figure>

                <figure class="rgv-item rgv-i4">
                    <div class="rgv-frame">
                        <svg viewBox="0 0 320 200" role="img" aria-label="{{ $say('Vela Media broadcast waves', 'امواج پخش ولا مدیا') }}">
                            <defs><linearGradient id="rgv-g4" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#e0f5f7" /><stop offset="1" stop-color="#c8ebee" />
                            </linearGradient></defs>
                            <rect width="320" height="200" fill="url(#rgv-g4)" />
                            <rect x="154" y="52" width="12" height="96" rx="6" fill="#0e7490" />
                            <circle cx="160" cy="46" r="12" fill="#0e7490" />
                            <g fill="none" stroke="#0e7490" stroke-width="4" stroke-linecap="round">
                                <path d="M132 62 A 40 40 0 0 0 132 102" opacity=".85" />
                                <path d="M188 62 A 40 40 0 0 1 188 102" opacity=".85" />
                                <path d="M114 48 A 62 62 0 0 0 114 116" opacity=".45" />
                                <path d="M206 48 A 62 62 0 0 1 206 116" opacity=".45" />
                                <path d="M96 34 A 86 86 0 0 0 96 130" opacity=".22" />
                                <path d="M224 34 A 86 86 0 0 1 224 130" opacity=".22" />
                            </g>
                            <circle cx="66" cy="160" r="9" fill="#2f9e6e" />
                            <circle cx="256" cy="166" r="9" fill="#c2530f" />
                        </svg>
                    </div>
                    <figcaption class="rgv-cap">
                        <small>{{ $say('São Paulo · media', 'سائوپائولو · رسانه') }}</small>
                        <b>Vela Media</b>
                        <p>{{ $say('<strong>24 regions</strong>, one release stream', '<strong>۲۴ ناحیه</strong>، یک جریان انتشار') }}</p>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>
</div>
