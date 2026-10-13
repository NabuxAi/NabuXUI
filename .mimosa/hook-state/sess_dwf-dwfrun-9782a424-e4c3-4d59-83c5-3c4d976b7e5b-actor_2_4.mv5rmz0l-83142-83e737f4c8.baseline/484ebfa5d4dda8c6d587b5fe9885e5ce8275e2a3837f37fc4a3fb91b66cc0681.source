{{--
    The Liquid Glass material of iOS 26 on its own: a vivid animated colour
    field with drifting blurred blobs (var(--nx-motion) aware), the regular
    and clear recipes side by side as floating panels, and a rim + interior
    gleam that follows the pointer so you can judge each variant on its own.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ioglass-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6);
        --ioglass-reg-bg: rgba(255, 255, 255, .7); --ioglass-clear-bg: rgba(255, 255, 255, .2);
        --ioglass-strip-bg: rgba(255, 255, 255, .62);
        --ioglass-clear-recipe: var(--ios-label-2); --ioglass-clear-recipe-shadow: none;
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .ioglass-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6);
        --ioglass-reg-bg: rgba(30, 30, 30, .55); --ioglass-clear-bg: rgba(255, 255, 255, .08);
        --ioglass-strip-bg: rgba(30, 30, 30, .5);
        --ioglass-clear-recipe: rgba(235, 235, 245, .95);
        --ioglass-clear-recipe-shadow: 0 1px 6px rgba(10, 12, 23, .7), 0 0 3px rgba(10, 12, 23, .55);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ioglass-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6);
            --ioglass-reg-bg: rgba(30, 30, 30, .55); --ioglass-clear-bg: rgba(255, 255, 255, .08);
            --ioglass-strip-bg: rgba(30, 30, 30, .5);
            --ioglass-clear-recipe: rgba(235, 235, 245, .95);
            --ioglass-clear-recipe-shadow: 0 1px 6px rgba(10, 12, 23, .7), 0 0 3px rgba(10, 12, 23, .55);
        }
    }
    .ioglass-root :focus-visible { outline: 2px solid #fff; outline-offset: 2px; }

    .ioglass-stage { position: relative; overflow: clip; inline-size: min(100%, 30rem); margin-inline: auto; padding: 1.6rem 1.2rem;
                     border-radius: 24px; background: linear-gradient(130deg, #101031, #1c0f38 55%, #2a0f22);
                     box-shadow: 0 24px 60px rgba(0, 0, 0, .35), inset 0 0 0 1px rgba(255, 255, 255, .08); }
    .ioglass-field { position: absolute; inset: -10%; z-index: 0; filter: blur(46px) saturate(1.5); pointer-events: none; }
    .ioglass-field i { position: absolute; inline-size: 52%; aspect-ratio: 1; border-radius: 50%; opacity: .95;
                       animation: ioglass-drift 18s ease-in-out infinite alternate; }
    .ioglass-field i:nth-child(1) { inset-block-start: -6%; inset-inline-start: -4%; background: radial-gradient(circle at 35% 35%, #FFD60A, #FF9500 45%, #FF3B30 80%); }
    .ioglass-field i:nth-child(2) { inset-block-end: -10%; inset-inline-end: -6%; background: radial-gradient(circle at 40% 40%, #64D2FF, #5856D6 55%, #5E5CE6); animation-delay: -7s; animation-duration: 24s; }
    .ioglass-field i:nth-child(3) { inset-block-start: 32%; inset-inline-start: 34%; inline-size: 38%; background: radial-gradient(circle at 45% 45%, #30D158, #007AFF); animation-delay: -13s; animation-duration: 21s; }
    @keyframes ioglass-drift { to { translate: calc(9% * var(--nx-motion)) calc(-11% * var(--nx-motion)); scale: calc(1 + .12 * var(--nx-motion)); } }

    .ioglass-panels { position: relative; z-index: 1; display: flex; flex-wrap: wrap; justify-content: center; gap: 1.1rem; }
    .ioglass-panel { position: relative; overflow: clip; inline-size: min(12.5rem, 100%); padding: 1rem 1.1rem 1.1rem; border-radius: 24px;
                     background: var(--ioglass-reg-bg); backdrop-filter: blur(24px) saturate(1.8); -webkit-backdrop-filter: blur(24px) saturate(1.8);
                     box-shadow: 0 10px 34px rgba(0, 0, 0, .18), inset 0 0 0 .5px rgba(255, 255, 255, .8); }
    .ioglass-panel[data-kind="clear"] { background: var(--ioglass-clear-bg);
                     backdrop-filter: blur(6px) saturate(1.4); -webkit-backdrop-filter: blur(6px) saturate(1.4);
                     box-shadow: 0 10px 34px rgba(0, 0, 0, .24), inset 0 0 0 .5px rgba(255, 255, 255, .45); }
    .ioglass-panel::before { content: ''; position: absolute; inset: 0; pointer-events: none;
                     background: radial-gradient(9rem circle at var(--gx, 50%) var(--gy, 28%), rgba(255, 255, 255, .55), transparent 55%); }
    .ioglass-panel[data-kind="clear"]::before { background: radial-gradient(9rem circle at var(--gx, 50%) var(--gy, 28%), rgba(255, 255, 255, .7), transparent 60%); }
    .ioglass-panel::after { content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px; pointer-events: none;
                     background: radial-gradient(8rem circle at var(--gx, 50%) var(--gy, 25%), rgba(255, 255, 255, .95), rgba(255, 255, 255, .12) 60%);
                     -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                     -webkit-mask-composite: xor; mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); mask-composite: exclude; }
    .ioglass-panel > * { position: relative; z-index: 1; }
    .ioglass-panel b { display: block; font-size: 1.05rem; font-weight: 700; }
    .ioglass-panel .recipe { display: block; margin-block: .15rem .8rem; font-size: .72rem; color: var(--ios-label-2); }
    /* در تم تیره خط نازک دستورِ پنلِ clear در هالهٔ روشنِ میدانِ رنگی (و درخشش
       اشاره‌گر) غرق می‌شود؛ با تُنِ تقریباً کامل و هالهٔ تاریکِ اطراف حروف
       خوانا می‌ماند — بدون دست‌زدن به شفافیتِ خودِ متریال. */
    .ioglass-panel[data-kind="clear"] .recipe { color: var(--ioglass-clear-recipe); text-shadow: var(--ioglass-clear-recipe-shadow); }
    .ioglass-panel p { margin: 0 0 .9rem; font-size: .86rem; line-height: 1.55; }
    .ioglass-chip { display: inline-flex; align-items: center; gap: .4rem; padding: .42rem .95rem; border-radius: 999px;
                    background: var(--ioglass-strip-bg); font-size: .8rem; font-weight: 600;
                    box-shadow: inset 0 0 0 .5px rgba(255, 255, 255, .6); }
    .ioglass-chip i { inline-size: 8px; aspect-ratio: 1; border-radius: 50%; }

    .ioglass-strip { position: relative; z-index: 1; display: flex; align-items: center; gap: .6rem; margin-block-start: 1.1rem;
                     padding: .6rem 1rem; border-radius: 16px; color: #fff; font-size: .78rem;
                     background: var(--ioglass-strip-bg); backdrop-filter: blur(6px) saturate(1.4); -webkit-backdrop-filter: blur(6px) saturate(1.4);
                     box-shadow: inset 0 0 0 .5px rgba(255, 255, 255, .35), 0 8px 24px rgba(0, 0, 0, .2); }

    @media (prefers-reduced-motion: reduce) {
        .ioglass-field i { animation: none; }
    }

    /* جدول «پراپ‌های مهم» که صفحهٔ دمو زیر این پارشال رندر می‌کند سطرهایش را
       فقط وقتیِ observer اسکرول نشان می‌دهد fade-in می‌کند و روی صفحهٔ لایوایر
       failsafeِ ۲/۵ ثانیه‌ای CSS هم خاموش است (.nx-live). در کپچرهای تمام‌صفحه
       observerِ زیرِ فولد هرگز آتش نمی‌زد و بدنهٔ جدول خالی می‌افتاد. این قانون
       فقط داخل همین پارشال است، پس اسکوپش همین صفحه است: سطرها همیشه دیده
       شوند. (الگوی همین‌طورِ ios/alert) */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The material itself: regular vs clear', 'خودِ متریال: regular در برابر clear') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Two recipes of Liquid Glass float over a live colour field: regular — 24px blur plus a 70% tint, legible on anything — and clear — barely-there 6px blur for big lively surfaces. Move the pointer across them: the interior gleam and the lit rim follow it, so you can judge each variant on its own.', 'دو دستور شیشهٔ مایع روی میدان رنگی زنده شناورند: regular — بلور ۲۴ پیکسلی با رنگ‌مایهٔ ۷۰٪، روی هرچیزی خوانا — و clear — بلور ۶ پیکسلیِ تقریباً نامحسوس برای سطوح بزرگ و پرتحرک. اشاره‌گر را رویشان بچرخانید: درخشش درونی و لبهٔ نورانی دنبالش می‌روند تا هر نسخه را تنها قضاوت کنید.') }}
        </p>
    </div>

    <div class="ioglass-root" x-data="{
            g(e) { const el = e.currentTarget; const r = el.getBoundingClientRect();
                   el.style.setProperty('--gx', ((e.clientX - r.left) / r.width * 100).toFixed(1) + '%');
                   el.style.setProperty('--gy', ((e.clientY - r.top) / r.height * 100).toFixed(1) + '%') },
            rest(e) { const el = e.currentTarget; el.style.setProperty('--gx', '50%'); el.style.setProperty('--gy', '28%') },
        }">
        <div class="ioglass-stage">
            <div class="ioglass-field" aria-hidden="true"><i></i><i></i><i></i></div>

            <div class="ioglass-panels">
                <div class="ioglass-panel" data-kind="regular" x-on:pointermove="g($event)" x-on:pointerleave="rest($event)">
                    <b>Regular</b>
                    <span class="recipe">{{ $say('blur 24px · saturate 1.8 · tint 70%', 'بلور ۲۴ · اشباع ۱٫۸ · رنگ‌مایه ۷۰٪') }}</span>
                    <p>{{ $say('The default material: buttons, toolbars, sheets — text stays readable over any colour beneath.', 'متریال پیش‌فرض: دکمه‌ها، نوارها، شیت‌ها — متن روی هر رنگی که زیرش باشد خوانا می‌ماند.') }}</p>
                    <span class="ioglass-chip"><i style="background: var(--ios-green)" aria-hidden="true"></i>{{ $say('Legible', 'خوانا') }}</span>
                </div>

                <div class="ioglass-panel" data-kind="clear" x-on:pointermove="g($event)" x-on:pointerleave="rest($event)">
                    <b>Clear</b>
                    <span class="recipe">{{ $say('blur 6px · saturate 1.4 · tint 20%', 'بلور ۶ · اشباع ۱٫۴ · رنگ‌مایه ۲۰٪') }}</span>
                    <p>{{ $say('Nearly invisible: the colour field pours through. Reserve it for large, playful surfaces — not for body text.', 'تقریباً نامرئی: میدان رنگ از میانش سرازیر می‌شود. برای سطوح بزرگ و شاد نگه‌اش دارید — نه برای متنِ بدنه.') }}</p>
                    <span class="ioglass-chip"><i style="background: var(--ios-teal)" aria-hidden="true"></i>{{ $say('Lively', 'پرتحرک') }}</span>
                </div>
            </div>

            <p class="ioglass-strip">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M8 2.2v11.6M8 2.2 4.6 5.6M8 2.2l3.4 3.4M2.6 13.8h10.8"/></svg>
                {{ $say('Sweep the pointer over the panels — the rim catches the light’s direction.', 'اشاره‌گر را روی پنل‌ها بچرخانید — لبه، جهت نور را می‌گیرد.') }}
            </p>
        </div>
    </div>
</section>
