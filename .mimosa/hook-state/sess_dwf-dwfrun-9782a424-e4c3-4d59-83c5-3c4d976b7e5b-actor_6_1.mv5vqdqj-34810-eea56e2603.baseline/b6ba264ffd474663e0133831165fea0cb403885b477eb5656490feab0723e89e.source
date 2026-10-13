{{--
    The stats band: a full-width dark strip with a fine dot texture where
    four big counters run once — the first time the band scrolls into view —
    on an easeOutCubic curve. Reduced motion skips the count and lands on
    the final numbers straight away.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .secst-root {
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        display: grid;
    }
    .secst {
        --secst-bg: #0B1220; --secst-line: #1E293B; --secst-text: #F8FAFC;
        --secst-muted: #94A3B8; --secst-ink: #7DD3FC;
        inline-size: 100%; border-radius: 1.4rem; border: 1px solid var(--secst-line);
        padding: clamp(2rem, 5vw, 3.5rem) clamp(1rem, 3.5vw, 2.5rem);
        color: var(--secst-text);
        background:
            radial-gradient(60rem 18rem at 85% -20%, rgba(125, 211, 252, .14), transparent 60%),
            radial-gradient(rgba(148, 163, 184, .15) 1px, transparent 1.5px) 0 0 / 22px 22px,
            var(--secst-bg);
        display: grid; gap: 2rem; justify-items: center; overflow: clip;
    }
    html[data-theme="dark"] .secst { --secst-bg: #0A0F1C; --secst-line: #26324A; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .secst { --secst-bg: #0A0F1C; --secst-line: #26324A; }
    }
    .secst-head { display: grid; gap: .5rem; justify-items: center; text-align: center; }
    .secst-head p { margin: 0; font-size: .72rem; font-weight: 700; letter-spacing: .16em;
                    text-transform: uppercase; color: var(--secst-ink); }
    .secst-head h3 { margin: 0; font-size: clamp(1.15rem, 2.4vw, 1.5rem); font-weight: 800;
                     letter-spacing: -.015em; }
    .secst-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));
                  inline-size: 100%; max-inline-size: 62rem; }
    .secst-stat { display: grid; gap: .55rem; justify-items: center; text-align: center;
                  padding: .75rem .9rem; }
    .secst-stat + .secst-stat { border-inline-start: 1px solid var(--secst-line); }
    .secst-stat svg { inline-size: 1.5rem; block-size: 1.5rem; stroke: var(--secst-ink);
                      stroke-width: 1.75; fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .secst-value { margin: 0; display: flex; align-items: baseline; gap: .3rem; justify-content: center;
                   font-size: clamp(1.9rem, 4.6vw, 3.2rem); font-weight: 800; line-height: 1;
                   letter-spacing: -.03em; font-variant-numeric: tabular-nums; }
    .secst-value small { font-size: .42em; font-weight: 700; color: var(--secst-ink); }
    .secst-stat > small { color: var(--secst-muted); font-size: .78rem; }
    .secst-note { margin: 0; font-size: .7rem; color: var(--secst-muted); }
    @media (max-width: 640px) {
        .secst-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); row-gap: 1.75rem; }
        .secst-stat + .secst-stat { border-inline-start: none; }
        .secst-stat:nth-child(even) { border-inline-start: 1px solid var(--secst-line); }
    }
    @media (prefers-reduced-motion: reduce) {
        .secst-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="secst-root"
    x-data="{
        isFa: @js($fa),
        vals: [0, 0, 0, 0],
        targets: [99.99, 3.4, 142, 8200],
        fmt(v, dec) {
            let s = dec ? v.toFixed(dec) : Math.round(v).toLocaleString('en-US');
            if (this.isFa) {
                s = s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]).replace(/,/g, '٬').replace(/\./g, '٫');
            }
            return s;
        },
        snap() { this.vals = [...this.targets]; },
        start() {
            const T0 = performance.now(), DUR = 1400;
            const tick = (t) => {
                const p = Math.min(1, (t - T0) / DUR);
                const e = 1 - Math.pow(1 - p, 3);
                this.vals = this.targets.map(x => x * e);
                if (p < 1) { requestAnimationFrame(tick); }
            };
            requestAnimationFrame(tick);
        },
    }"
    x-init="
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            snap();
        } else {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) { start(); io.disconnect(); }
                });
            }, { threshold: 0.25 });
            io.observe($el);
        }
    ">
    <section class="secst" aria-label="{{ $say('Beacon in numbers', 'بیکِن در اعداد') }}">
        <header class="secst-head">
            <p>{{ $say('Beacon in numbers', 'بیکِن در اعداد') }}</p>
            <h3>{{ $say('The scale your mornings rely on', 'مقیاسی که صبح‌های شما به آن تکیه می‌کند') }}</h3>
        </header>

        <div class="secst-grid">
            <div class="secst-stat">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7 3v5c0 4.4-3 8.4-7 9.5C8 19.4 5 15.4 5 11V6l7-3z"/><path d="M9 11.5l2 2 4-4.5"/></svg>
                <p class="secst-value"><span x-text="fmt(vals[0], 2)"></span><small>{{ $say('%', '٪') }}</small></p>
                <small>{{ $say('measured uptime', 'آپتایم اندازه‌گیری‌شده') }}</small>
            </div>
            <div class="secst-stat">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13 2.5L5 13.5h5.5L11 21.5l8-11h-5.5l-.5-8z"/></svg>
                <p class="secst-value"><span x-text="fmt(vals[1], 1)"></span><small>{{ $say('B', 'میلیارد') }}</small></p>
                <small>{{ $say('events a day', 'رویداد در روز') }}</small>
            </div>
            <div class="secst-stat">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.6 2.4 4 5.3 4 8.5s-1.4 6.1-4 8.5c-2.6-2.4-4-5.3-4-8.5s1.4-6.1 4-8.5z"/></svg>
                <p class="secst-value"><span x-text="fmt(vals[2], 0)"></span></p>
                <small>{{ $say('countries served', 'کشور تحت پوشش') }}</small>
            </div>
            <div class="secst-stat">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8.5" r="3.5"/><path d="M3 19.5c.9-3.3 3.3-5 6-5s5.1 1.7 6 5"/><path d="M15.5 5.5a3.5 3.5 0 0 1 0 6.4"/></svg>
                <p class="secst-value"><span x-text="fmt(vals[3], 0)"></span><small>+</small></p>
                <small>{{ $say('teams on board', 'تیم روی برد') }}</small>
            </div>
        </div>

        <p class="secst-note">{{ $say('Rolling 90-day average across 34 regions · audited October 2026', 'میانگین غلتانِ ۹۰ روزه در ۳۴ ناحیه · بازرسی‌شده اکتبر ۲۰۲۶') }}</p>
    </section>
</div>
