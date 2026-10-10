<?php

/**
 * Demo manifest of the "cta" group — mid-page call-to-action banners for a
 * landing page, each with its own personality: the gradient banner with its
 * passing light sweep and noise grain, the pricing card with its animated
 * monthly/yearly price, the image-backed banner with a hand-drawn scenic SVG
 * and pointer parallax, and the developer terminal with a typed install
 * command, a ticking copy button and a star counter. Scenarios live at
 * resources/views/demos/components/cta/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'دعوت به کنش', 'en' => 'CTA'],

    'gradient-cta' => [
        'title' => ['fa' => 'بنر گرادیانی', 'en' => 'Gradient CTA banner'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'بنر تمام‌عرض میانی لندینگ: گرادیان بنفش به سرخابی، جاروی نوری که آرام از رویش می‌گذرد و دانهٔ نویز ظریفی که تازگی رنگ را می‌گیرد؛ تیتر درشت، دو دکمه و بج «بدون کارت بانکی» — هاور دکمه‌ها را از سطح بالا می‌آورد.',
            'en' => 'A full-width mid-page landing banner: violet-to-magenta gradient, a light sweep drifting across it and a fine noise grain that keeps the colour from feeling plastic; a big headline, two buttons and a “no credit card” badge — hover lifts the buttons off the surface.',
        ],
        'js' => false,
        'docs' => 'https://cta.gallery',
        'props' => [
            ['name' => 'sweep', 'type' => 'animation', 'default' => "'6.5s ease-in-out infinite'", 'note' => [
                'fa' => 'جاروی نور مورب که کل عرض بنر را در هر دور می‌جارو‌د و بین دورها مکث می‌کند؛ با prefers-reduced-motion کاملاً متوقف می‌شود.',
                'en' => 'The skewed light sweep crossing the whole banner each cycle with a pause between rounds; fully stopped under prefers-reduced-motion.',
            ]],
            ['name' => '--ctgr-angle', 'type' => 'angle', 'default' => "'115deg'", 'note' => [
                'fa' => 'جهت گرادیان؛ با تغییرش شخصیت بنر از آرام به پوی عوض می‌شود.',
                'en' => 'The gradient direction; turning it swaps the banner’s mood from calm to kinetic.',
            ]],
            ['name' => 'noise', 'type' => 'opacity', 'default' => "'5%'", 'note' => [
                'fa' => 'دانهٔ نویز SVG (feTurbulence) روی رنگ؛ نبودش گرادیان را پلاستیکی می‌کند.',
                'en' => 'An SVG feTurbulence grain over the colour; without it the gradient reads plastic.',
            ]],
            ['name' => 'data-size', 'type' => 'string', 'default' => "'hero · slim'", 'note' => [
                'fa' => 'hero برای بین بخش‌های اصلی صفحه؛ slim نسخهٔ یک‌خطی برای انتهای هر پست بلاگ.',
                'en' => 'hero for between the page’s main sections; slim is the one-line variant for the end of each blog post.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="ctgr-banner">
            <span class="ctgr-noise" aria-hidden="true"></span>
            <span class="ctgr-sweep" aria-hidden="true"></span>
            <div class="ctgr-body">
                <span class="ctgr-chip">بدون کارت بانکی · ۱۴ روز آزمایشی</span>
                <h3>هر انتشار، با عدد.</h3>
                <p>از فرانکفورت تا سنگاپور، ببین کدام فیچر واقعاً استفاده می‌شود.</p>
                <div class="ctgr-actions">
                    <a class="ctgr-btn" href="/signup">شروع رایگان</a>
                    <a class="ctgr-btn ctgr-ghost" href="mailto:sales@meridian.dev">گفتگو با فروش</a>
                </div>
            </div>
        </section>

        <style>
        .ctgr-banner { position: relative; overflow: clip; border-radius: 24px; color: #fff;
                       background: linear-gradient(115deg, #312e81, #4f46e5 38%, #7c3aed 62%, #c026d3); }
        .ctgr-sweep { position: absolute; inset-block: -20%; inline-size: 34%; inset-inline-start: 0;
                      background: linear-gradient(105deg, transparent, #ffffff47, transparent);
                      transform: skewX(-18deg); animation: ctgr-sweep 6.5s ease-in-out infinite; }
        @keyframes ctgr-sweep { 0%, 12% { translate: -180% 0; } 62%, 100% { translate: 480% 0; } }
        .ctgr-btn { display: inline-flex; block-size: 3rem; align-items: center; padding-inline: 1.5rem;
                    border-radius: 999px; background: #fff; color: #312e81;
                    transition: translate .18s, box-shadow .18s; }
        .ctgr-btn:hover { translate: 0 -2px; box-shadow: 0 10px 24px #00000038; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="ctgr-banner">
        <span class="ctgr-noise" aria-hidden="true"></span>
        <span class="ctgr-sweep" aria-hidden="true"></span>
        <div class="ctgr-body">
        <span class="ctgr-chip">No credit card · 14-day trial</span>
        <h3>Every release, measured.</h3>
        <p>From Frankfurt to Singapore, see which features actually get used.</p>
        <div class="ctgr-actions">
        <a class="ctgr-btn" href="/signup">Start free</a>
        <a class="ctgr-btn ctgr-ghost" href="mailto:sales@meridian.dev">Talk to sales</a>
        </div>
        </div>
        </section>

        <style>
        .ctgr-banner { position: relative; overflow: clip; border-radius: 24px; color: #fff;
        background: linear-gradient(115deg, #312e81, #4f46e5 38%, #7c3aed 62%, #c026d3); }
        .ctgr-sweep { position: absolute; inset-block: -20%; inline-size: 34%; inset-inline-start: 0;
        background: linear-gradient(105deg, transparent, #ffffff47, transparent);
        transform: skewX(-18deg); animation: ctgr-sweep 6.5s ease-in-out infinite; }
        @keyframes ctgr-sweep { 0%, 12% { translate: -180% 0; } 62%, 100% { translate: 480% 0; } }
        .ctgr-btn { display: inline-flex; block-size: 3rem; align-items: center; padding-inline: 1.5rem;
        border-radius: 999px; background: #fff; color: #312e81;
        transition: translate .18s, box-shadow .18s; }
        .ctgr-btn:hover { translate: 0 -2px; box-shadow: 0 10px 24px #00000038; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Home.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import GradientBanner from '@/components/GradientBanner';

        export default function Home() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <GradientBanner />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // GradientBanner.jsx — the mid-page CTA: gradient, light sweep, grain
        import '@nabuxai/ui-core/css';

        export default function GradientBanner() {
          return (
            <>
              <style>{`
                .ctgr-banner { position: relative; overflow: clip; border-radius: 1.5rem; color: #fff; text-align: center;
                               background: linear-gradient(115deg, #312e81, #4f46e5 38%, #7c3aed 62%, #c026d3); }
                .ctgr-noise { position: absolute; inset: 0; opacity: .05; mix-blend-mode: overlay; pointer-events: none;
                              background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)'/%3E%3C/svg%3E"); }
                .ctgr-sweep { position: absolute; inset-block: -20%; inline-size: 34%; inset-inline-start: 0; pointer-events: none;
                              background: linear-gradient(105deg, transparent, #ffffff47, transparent);
                              transform: skewX(-18deg); animation: ctgr-sweep 6.5s ease-in-out infinite; }
                @keyframes ctgr-sweep { 0%, 12% { translate: -180% 0; } 62%, 100% { translate: 480% 0; } }
                .ctgr-body { position: relative; display: grid; gap: 1rem; justify-items: center;
                             padding: clamp(2.5rem, 6vw, 4.5rem) clamp(1.25rem, 5vw, 3rem); }
                .ctgr-chip { display: inline-flex; align-items: center; gap: .5rem; padding: .4rem .9rem; border-radius: 999px;
                             background: #ffffff26; backdrop-filter: blur(8px); font-size: .8rem; }
                .ctgr-title { margin: 0; max-inline-size: 24ch; font: 800 clamp(1.9rem, 4.5vw, 3rem)/1.15
                              'Bricolage Grotesque', Inter, sans-serif; letter-spacing: -.02em; }
                .ctgr-sub { margin: 0; max-inline-size: 44ch; color: #ffffffcc; }
                .ctgr-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; margin-block-start: .5rem; }
                .ctgr-btn { display: inline-flex; align-items: center; gap: .5rem; block-size: 3rem; padding-inline: 1.5rem;
                            border-radius: 999px; background: #fff; color: #312e81; font-weight: 700; text-decoration: none;
                            transition: translate .18s ease, box-shadow .18s ease; }
                .ctgr-btn:hover { translate: 0 -2px; box-shadow: 0 10px 24px #00000038; }
                .ctgr-ghost { background: #ffffff1f; color: #fff; box-shadow: inset 0 0 0 1px #ffffff59; }
                @media (prefers-reduced-motion: reduce) {
                  .ctgr-sweep { animation: none; }
                  .ctgr-btn { transition: none; }
                }
              `}</style>
              <section class="ctgr-banner">
                <span className="ctgr-noise" aria-hidden="true" />
                <span className="ctgr-sweep" aria-hidden="true" />
                <div className="ctgr-body">
                  <span className="ctgr-chip">No credit card · 14-day trial</span>
                  <h3 className="ctgr-title">Every release, measured.</h3>
                  <p className="ctgr-sub">From Frankfurt to Singapore, see which features actually get used.</p>
                  <div className="ctgr-actions">
                    <a className="ctgr-btn" href="/signup">Start free</a>
                    <a className="ctgr-btn ctgr-ghost" href="mailto:sales@meridian.dev">Talk to sales</a>
                  </div>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- GradientBanner.vue — the mid-page CTA: gradient, light sweep, grain -->
        <script setup lang="ts">
        import '@nabuxai/ui-core/css';
        </script>

        <template>
          <section class="ctgr-banner">
            <span class="ctgr-noise" aria-hidden="true"></span>
            <span class="ctgr-sweep" aria-hidden="true"></span>
            <div class="ctgr-body">
              <span class="ctgr-chip">No credit card · 14-day trial</span>
              <h3 class="ctgr-title">Every release, measured.</h3>
              <p class="ctgr-sub">From Frankfurt to Singapore, see which features actually get used.</p>
              <div class="ctgr-actions">
                <a class="ctgr-btn" href="/signup">Start free</a>
                <a class="ctgr-btn ctgr-ghost" href="mailto:sales@meridian.dev">Talk to sales</a>
              </div>
            </div>
          </section>
        </template>

        <style scoped>
        .ctgr-banner { position: relative; overflow: clip; border-radius: 1.5rem; color: #fff; text-align: center;
                       background: linear-gradient(115deg, #312e81, #4f46e5 38%, #7c3aed 62%, #c026d3); }
        .ctgr-noise { position: absolute; inset: 0; opacity: .05; mix-blend-mode: overlay; pointer-events: none;
                      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)'/%3E%3C/svg%3E"); }
        .ctgr-sweep { position: absolute; inset-block: -20%; inline-size: 34%; inset-inline-start: 0; pointer-events: none;
                      background: linear-gradient(105deg, transparent, #ffffff47, transparent);
                      transform: skewX(-18deg); animation: ctgr-sweep 6.5s ease-in-out infinite; }
        @keyframes ctgr-sweep { 0%, 12% { translate: -180% 0; } 62%, 100% { translate: 480% 0; } }
        .ctgr-body { position: relative; display: grid; gap: 1rem; justify-items: center;
                     padding: clamp(2.5rem, 6vw, 4.5rem) clamp(1.25rem, 5vw, 3rem); }
        .ctgr-chip { display: inline-flex; align-items: center; gap: .5rem; padding: .4rem .9rem; border-radius: 999px;
                     background: #ffffff26; backdrop-filter: blur(8px); font-size: .8rem; }
        .ctgr-title { margin: 0; max-inline-size: 24ch; font: 800 clamp(1.9rem, 4.5vw, 3rem)/1.15
                      'Bricolage Grotesque', Inter, sans-serif; letter-spacing: -.02em; }
        .ctgr-sub { margin: 0; max-inline-size: 44ch; color: #ffffffcc; }
        .ctgr-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; margin-block-start: .5rem; }
        .ctgr-btn { display: inline-flex; align-items: center; gap: .5rem; block-size: 3rem; padding-inline: 1.5rem;
                    border-radius: 999px; background: #fff; color: #312e81; font-weight: 700; text-decoration: none;
                    transition: translate .18s ease, box-shadow .18s ease; }
        .ctgr-btn:hover { translate: 0 -2px; box-shadow: 0 10px 24px #00000038; }
        .ctgr-ghost { background: #ffffff1f; color: #fff; box-shadow: inset 0 0 0 1px #ffffff59; }
        @media (prefers-reduced-motion: reduce) {
          .ctgr-sweep { animation: none; }
          .ctgr-btn { transition: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- GradientBanner.svelte — the mid-page CTA: gradient, light sweep, grain -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';
        </script>

        <section class="ctgr-banner">
          <span class="ctgr-noise" aria-hidden="true"></span>
          <span class="ctgr-sweep" aria-hidden="true"></span>
          <div class="ctgr-body">
            <span class="ctgr-chip">No credit card · 14-day trial</span>
            <h3 class="ctgr-title">Every release, measured.</h3>
            <p class="ctgr-sub">From Frankfurt to Singapore, see which features actually get used.</p>
            <div class="ctgr-actions">
              <a class="ctgr-btn" href="/signup">Start free</a>
              <a class="ctgr-btn ctgr-ghost" href="mailto:sales@meridian.dev">Talk to sales</a>
            </div>
          </div>
        </section>

        <style>
        .ctgr-banner { position: relative; overflow: clip; border-radius: 1.5rem; color: #fff; text-align: center;
                       background: linear-gradient(115deg, #312e81, #4f46e5 38%, #7c3aed 62%, #c026d3); }
        .ctgr-noise { position: absolute; inset: 0; opacity: .05; mix-blend-mode: overlay; pointer-events: none;
                      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)'/%3E%3C/svg%3E"); }
        .ctgr-sweep { position: absolute; inset-block: -20%; inline-size: 34%; inset-inline-start: 0; pointer-events: none;
                      background: linear-gradient(105deg, transparent, #ffffff47, transparent);
                      transform: skewX(-18deg); animation: ctgr-sweep 6.5s ease-in-out infinite; }
        @keyframes ctgr-sweep { 0%, 12% { translate: -180% 0; } 62%, 100% { translate: 480% 0; } }
        .ctgr-body { position: relative; display: grid; gap: 1rem; justify-items: center;
                     padding: clamp(2.5rem, 6vw, 4.5rem) clamp(1.25rem, 5vw, 3rem); }
        .ctgr-chip { display: inline-flex; align-items: center; gap: .5rem; padding: .4rem .9rem; border-radius: 999px;
                     background: #ffffff26; backdrop-filter: blur(8px); font-size: .8rem; }
        .ctgr-title { margin: 0; max-inline-size: 24ch; font: 800 clamp(1.9rem, 4.5vw, 3rem)/1.15
                      'Bricolage Grotesque', Inter, sans-serif; letter-spacing: -.02em; }
        .ctgr-sub { margin: 0; max-inline-size: 44ch; color: #ffffffcc; }
        .ctgr-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; margin-block-start: .5rem; }
        .ctgr-btn { display: inline-flex; align-items: center; gap: .5rem; block-size: 3rem; padding-inline: 1.5rem;
                    border-radius: 999px; background: #fff; color: #312e81; font-weight: 700; text-decoration: none;
                    transition: translate .18s ease, box-shadow .18s ease; }
        .ctgr-btn:hover { translate: 0 -2px; box-shadow: 0 10px 24px #00000038; }
        .ctgr-ghost { background: #ffffff1f; color: #fff; box-shadow: inset 0 0 0 1px #ffffff59; }
        @media (prefers-reduced-motion: reduce) {
          .ctgr-sweep { animation: none; }
          .ctgr-btn { transition: none; }
        }
        </style>
        SVELTE,
        ],
    ],

    'pricing-cta' => [
        'title' => ['fa' => 'CTA قیمتی', 'en' => 'Pricing CTA card'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'کارت دعوت به کنش با تاگل ماهانه/سالانه: عدد قیمت با غلتش محو بالا می‌رود و قیمت تازه از پایین می‌آید، صرفه‌جویی سالانه با بج سبز روشن می‌شود و پای کارت، دکمهٔ اصلی کنار سطر تضمین بازگشت وجه می‌نشیند.',
            'en' => 'A call-to-action card with a monthly/yearly toggle: the price rolls up and out while the new one rolls in from below, the yearly savings light up as a green badge, and the primary button sits above a money-back guarantee line.',
        ],
        'js' => true,
        'docs' => 'https://cta.gallery',
        'props' => [
            ['name' => 'billing', 'type' => 'state', 'default' => "'monthly'", 'note' => [
                'fa' => 'تاگل ماهانه/سالانه؛ کل کارت — قیمت، زیرنویس صورتحساب و بج صرفه‌جویی — همان لحظه دنبالش می‌رود.',
                'en' => 'The monthly/yearly toggle; the whole card — price, billing note and savings badge — follows it instantly.',
            ]],
            ['name' => 'price', 'type' => 'number', 'default' => "'19 · 15'", 'note' => [
                'fa' => 'دلار به‌ازای هر صندلی در ماه؛ با عوض‌شدن دوره، عدد قدیمی به بالا غلت می‌خورد و تازه از پایین می‌آید.',
                'en' => 'USD per seat per month; when the period flips the old figure rolls up and out as the new one rolls in from below.',
            ]],
            ['name' => 'save-badge', 'type' => 'string', 'default' => "'Save $96 a year'", 'note' => [
                'fa' => 'بج صرفه‌جویی که فقط روی حالت سالانه پدیدار می‌شود؛ عددش اختلاف واقعی دو دوره است.',
                'en' => 'The savings badge that appears only in yearly mode; its number is the real difference between the two periods.',
            ]],
            ['name' => 'guarantee', 'type' => 'string', 'default' => "'30-day money-back'", 'note' => [
                'fa' => 'سطر تضمین زیر دکمهٔ اصلی؛ ترس خرید را می‌گیرد و با آیکون سپر می‌نشیند.',
                'en' => 'The guarantee line under the primary button; it lifts the purchase anxiety and carries a shield icon.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="ctpr-card" dir="rtl">
            <div class="ctpr-toggle" role="radiogroup" aria-label="دورهٔ صورتحساب">
                <label><input type="radio" name="ctpr-b" checked><span>ماهانه</span></label>
                <label><input type="radio" name="ctpr-b" id="ctpr-y"><span>سالانه</span></label>
            </div>
            <div class="ctpr-figure">
                <span class="ctpr-num" data-m>۱۹</span>
                <span class="ctpr-num" data-y>۱۵</span>
                <small>دلار / ماه / هر صندلی</small>
            </div>
            <span class="ctpr-save">۹۶ دلار در سال صرفه‌جویی می‌کنید</span>
            <button class="ctpr-go">ارتقای پلن Pro</button>
            <small class="ctpr-guarantee">۳۰ روز ضمانت بازگشت وجه</small>
        </div>

        <style>
        .ctpr-card { display: grid; gap: 1rem; justify-items: center; inline-size: 22rem; padding: 2rem;
                     border: 1px solid #e5e7eb; border-radius: 20px; }
        .ctpr-num { grid-area: 1 / 1; font-size: 3rem; font-weight: 800;
                    transition: opacity .22s, translate .22s; }
        .ctpr-num[data-y] { opacity: 0; translate: 0 .4em; }
        .ctpr-card:has(#ctpr-y:checked) .ctpr-num[data-m] { opacity: 0; translate: 0 -.4em; }
        .ctpr-card:has(#ctpr-y:checked) .ctpr-num[data-y] { opacity: 1; translate: 0 0; }
        .ctpr-save { opacity: 0; font-size: .8rem; color: #059669; transition: opacity .2s; }
        .ctpr-card:has(#ctpr-y:checked) .ctpr-save { opacity: 1; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="ctpr-card">
        <div class="ctpr-toggle" role="radiogroup" aria-label="Billing period">
        <label><input type="radio" name="ctpr-b" checked><span>Monthly</span></label>
        <label><input type="radio" name="ctpr-b" id="ctpr-y"><span>Yearly</span></label>
        </div>
        <div class="ctpr-figure">
        <span class="ctpr-num" data-m>19</span>
        <span class="ctpr-num" data-y>15</span>
        <small>$ per seat / month</small>
        </div>
        <span class="ctpr-save">You save $96 a year</span>
        <button class="ctpr-go">Upgrade to Pro</button>
        <small class="ctpr-guarantee">30-day money-back guarantee</small>
        </div>

        <style>
        .ctpr-card { display: grid; gap: 1rem; justify-items: center; inline-size: 22rem; padding: 2rem;
        border: 1px solid #e5e7eb; border-radius: 20px; }
        .ctpr-num { grid-area: 1 / 1; font-size: 3rem; font-weight: 800;
        transition: opacity .22s, translate .22s; }
        .ctpr-num[data-y] { opacity: 0; translate: 0 .4em; }
        .ctpr-card:has(#ctpr-y:checked) .ctpr-num[data-m] { opacity: 0; translate: 0 -.4em; }
        .ctpr-card:has(#ctpr-y:checked) .ctpr-num[data-y] { opacity: 1; translate: 0 0; }
        .ctpr-save { opacity: 0; font-size: .8rem; color: #059669; transition: opacity .2s; }
        .ctpr-card:has(#ctpr-y:checked) .ctpr-save { opacity: 1; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Pricing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import PricingCta from '@/components/PricingCta';

        export default function Pricing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <PricingCta />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // PricingCta.jsx — monthly/yearly toggle with a rolling price
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function PricingCta() {
          const [yearly, setYearly] = useState(false);
          const price = yearly ? 15 : 19;

          return (
            <>
              <style>{`
                .ctpr-card { display: grid; gap: 1.1rem; justify-items: center; inline-size: min(100%, 24rem);
                             padding: 2rem 1.75rem; border: 1px solid #e5e7eb; border-radius: 1.25rem;
                             background: #fff; box-shadow: 0 18px 40px #0a0c1714;
                             font-family: Inter, system-ui, sans-serif; }
                .ctpr-top { display: grid; gap: .35rem; justify-items: center; text-align: center; }
                .ctpr-plan { font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #5647e6; }
                .ctpr-toggle { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; background: #eeeff4; }
                .ctpr-seg { block-size: 2.25rem; padding-inline: 1.1rem; border: none; border-radius: 999px;
                            background: transparent; font: 600 .875rem/1 Inter, sans-serif; color: #555a70; cursor: pointer;
                            transition: background-color .18s, color .18s; }
                .ctpr-seg[aria-pressed='true'] { background: #fff; color: #1e1a4a; box-shadow: 0 2px 8px #0a0c1721; }
                .ctpr-figure { display: flex; align-items: baseline; gap: .4rem; }
                .ctpr-nums { display: grid; }
                .ctpr-num { grid-area: 1 / 1; font: 800 3.25rem/1 Inter, sans-serif; letter-spacing: -.03em; color: #141726;
                            animation: ctpr-in .26s ease both; }
                @keyframes ctpr-in { from { opacity: 0; translate: 0 .4em; } }
                .ctpr-per { font-size: .8rem; color: #6c7088; }
                .ctpr-save { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .8rem;
                             border-radius: 999px; background: #10b9811f; color: #047857; font-size: .78rem; font-weight: 600; }
                .ctpr-go { display: flex; justify-content: center; align-items: center; gap: .5rem; inline-size: 100%;
                           block-size: 3rem; border: none; border-radius: 999px; background: #5647e6; color: #fff;
                           font: 700 .9375rem/1 Inter, sans-serif; cursor: pointer;
                           transition: translate .18s, box-shadow .18s, background-color .18s; }
                .ctpr-go:hover { translate: 0 -2px; background: #4739ca; box-shadow: 0 12px 26px #5647e638; }
                .ctpr-guarantee { font-size: .78rem; color: #6c7088; }
                @media (prefers-reduced-motion: reduce) {
                  .ctpr-num { animation: none; }
                  .ctpr-go, .ctpr-seg { transition: none; }
                }
              `}</style>
              <div className="ctpr-card">
                <div className="ctpr-top">
                  <span className="ctpr-plan">Pro</span>
                  <div className="ctpr-toggle" role="group" aria-label="Billing period">
                    <button type="button" className="ctpr-seg" aria-pressed={!yearly} onClick={() => setYearly(false)}>Monthly</button>
                    <button type="button" className="ctpr-seg" aria-pressed={yearly} onClick={() => setYearly(true)}>Yearly −20%</button>
                  </div>
                </div>
                {/* The active figure mounts with the roll-in animation. */}
                <div className="ctpr-figure">
                  <span className="ctpr-nums">
                    <span className="ctpr-num" key={price}>${price}</span>
                  </span>
                  <span className="ctpr-per">per seat / month</span>
                </div>
                {yearly && (
                  <span className="ctpr-save">✓ You save $96 a year — billed once</span>
                )}
                <button type="button" className="ctpr-go">Upgrade to Pro</button>
                <small className="ctpr-guarantee">30-day money-back guarantee · cancel anytime</small>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- PricingCta.vue — monthly/yearly toggle with a rolling price -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const yearly = ref(false);
        const price = computed(() => (yearly.value ? 15 : 19));
        </script>

        <template>
          <div class="ctpr-card">
            <div class="ctpr-top">
              <span class="ctpr-plan">Pro</span>
              <div class="ctpr-toggle" role="group" aria-label="Billing period">
                <button type="button" class="ctpr-seg" :aria-pressed="!yearly" @click="yearly = false">Monthly</button>
                <button type="button" class="ctpr-seg" :aria-pressed="yearly" @click="yearly = true">Yearly −20%</button>
              </div>
            </div>
            <!-- The active figure mounts with the roll-in animation. -->
            <div class="ctpr-figure">
              <span class="ctpr-nums">
                <span :key="price" class="ctpr-num">${{ price }}</span>
              </span>
              <span class="ctpr-per">per seat / month</span>
            </div>
            <span v-if="yearly" class="ctpr-save">✓ You save $96 a year — billed once</span>
            <button type="button" class="ctpr-go">Upgrade to Pro</button>
            <small class="ctpr-guarantee">30-day money-back guarantee · cancel anytime</small>
          </div>
        </template>

        <style scoped>
        .ctpr-card { display: grid; gap: 1.1rem; justify-items: center; inline-size: min(100%, 24rem);
                     padding: 2rem 1.75rem; border: 1px solid #e5e7eb; border-radius: 1.25rem;
                     background: #fff; box-shadow: 0 18px 40px #0a0c1714;
                     font-family: Inter, system-ui, sans-serif; }
        .ctpr-top { display: grid; gap: .35rem; justify-items: center; text-align: center; }
        .ctpr-plan { font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #5647e6; }
        .ctpr-toggle { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; background: #eeeff4; }
        .ctpr-seg { block-size: 2.25rem; padding-inline: 1.1rem; border: none; border-radius: 999px;
                    background: transparent; font: 600 .875rem/1 Inter, sans-serif; color: #555a70; cursor: pointer;
                    transition: background-color .18s, color .18s; }
        .ctpr-seg[aria-pressed='true'] { background: #fff; color: #1e1a4a; box-shadow: 0 2px 8px #0a0c1721; }
        .ctpr-figure { display: flex; align-items: baseline; gap: .4rem; }
        .ctpr-nums { display: grid; }
        .ctpr-num { grid-area: 1 / 1; font: 800 3.25rem/1 Inter, sans-serif; letter-spacing: -.03em; color: #141726;
                    animation: ctpr-in .26s ease both; }
        @keyframes ctpr-in { from { opacity: 0; translate: 0 .4em; } }
        .ctpr-per { font-size: .8rem; color: #6c7088; }
        .ctpr-save { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .8rem;
                     border-radius: 999px; background: #10b9811f; color: #047857; font-size: .78rem; font-weight: 600; }
        .ctpr-go { display: flex; justify-content: center; align-items: center; gap: .5rem; inline-size: 100%;
                   block-size: 3rem; border: none; border-radius: 999px; background: #5647e6; color: #fff;
                   font: 700 .9375rem/1 Inter, sans-serif; cursor: pointer;
                   transition: translate .18s, box-shadow .18s, background-color .18s; }
        .ctpr-go:hover { translate: 0 -2px; background: #4739ca; box-shadow: 0 12px 26px #5647e638; }
        .ctpr-guarantee { font-size: .78rem; color: #6c7088; }
        @media (prefers-reduced-motion: reduce) {
          .ctpr-num { animation: none; }
          .ctpr-go, .ctpr-seg { transition: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- PricingCta.svelte — monthly/yearly toggle with a rolling price -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          let yearly = $state(false);
          const price = $derived(yearly ? 15 : 19);
        </script>

        <div class="ctpr-card">
          <div class="ctpr-top">
            <span class="ctpr-plan">Pro</span>
            <div class="ctpr-toggle" role="group" aria-label="Billing period">
              <button type="button" class="ctpr-seg" aria-pressed={!yearly} onclick={() => (yearly = false)}>Monthly</button>
              <button type="button" class="ctpr-seg" aria-pressed={yearly} onclick={() => (yearly = true)}>Yearly −20%</button>
            </div>
          </div>
          <!-- The active figure mounts with the roll-in animation. -->
          <div class="ctpr-figure">
            <span class="ctpr-nums">
              <span class="ctpr-num">{#key price}<span class="ctpr-num-inner">${price}</span>{/key}</span>
            </span>
            <span class="ctpr-per">per seat / month</span>
          </div>
          {#if yearly}
            <span class="ctpr-save">✓ You save $96 a year — billed once</span>
          {/if}
          <button type="button" class="ctpr-go">Upgrade to Pro</button>
          <small class="ctpr-guarantee">30-day money-back guarantee · cancel anytime</small>
        </div>

        <style>
        .ctpr-card { display: grid; gap: 1.1rem; justify-items: center; inline-size: min(100%, 24rem);
                     padding: 2rem 1.75rem; border: 1px solid #e5e7eb; border-radius: 1.25rem;
                     background: #fff; box-shadow: 0 18px 40px #0a0c1714;
                     font-family: Inter, system-ui, sans-serif; }
        .ctpr-top { display: grid; gap: .35rem; justify-items: center; text-align: center; }
        .ctpr-plan { font-size: .8rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #5647e6; }
        .ctpr-toggle { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; background: #eeeff4; }
        .ctpr-seg { block-size: 2.25rem; padding-inline: 1.1rem; border: none; border-radius: 999px;
                    background: transparent; font: 600 .875rem/1 Inter, sans-serif; color: #555a70; cursor: pointer;
                    transition: background-color .18s, color .18s; }
        .ctpr-seg[aria-pressed='true'] { background: #fff; color: #1e1a4a; box-shadow: 0 2px 8px #0a0c1721; }
        .ctpr-figure { display: flex; align-items: baseline; gap: .4rem; }
        .ctpr-nums { display: grid; }
        .ctpr-num { grid-area: 1 / 1; }
        .ctpr-num-inner { display: inline-block; font: 800 3.25rem/1 Inter, sans-serif; letter-spacing: -.03em; color: #141726;
                          animation: ctpr-in .26s ease both; }
        @keyframes ctpr-in { from { opacity: 0; translate: 0 .4em; } }
        .ctpr-per { font-size: .8rem; color: #6c7088; }
        .ctpr-save { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .8rem;
                     border-radius: 999px; background: #10b9811f; color: #047857; font-size: .78rem; font-weight: 600; }
        .ctpr-go { display: flex; justify-content: center; align-items: center; gap: .5rem; inline-size: 100%;
                   block-size: 3rem; border: none; border-radius: 999px; background: #5647e6; color: #fff;
                   font: 700 .9375rem/1 Inter, sans-serif; cursor: pointer;
                   transition: translate .18s, box-shadow .18s, background-color .18s; }
        .ctpr-go:hover { translate: 0 -2px; background: #4739ca; box-shadow: 0 12px 26px #5647e638; }
        .ctpr-guarantee { font-size: .78rem; color: #6c7088; }
        @media (prefers-reduced-motion: reduce) {
          .ctpr-num-inner { animation: none; }
          .ctpr-go, .ctpr-seg { transition: none; }
        }
        </style>
        SVELTE,
        ],
    ],

    'image-cta' => [
        'title' => ['fa' => 'CTA تصویری', 'en' => 'Image-backed CTA'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'بنر دعوت به کنش با پس‌زمینهٔ صحنه‌ای SVG دست‌ساز — خورشیدِ در حال غروب، تپه‌های لایه‌لایه و شبکهٔ همگرا — زیر اورلی تیره؛ تیتر روشن، سه آمار کوچک و دکمهٔ شیشه‌ای، با پارالاکس ظریفی که لایه‌ها را با موس جابه‌جا می‌کند.',
            'en' => 'A CTA banner over a hand-drawn scenic SVG — setting sun, layered hills and a converging grid — under a dark overlay; a bright headline, three small stats and a glass button, with a subtle parallax drifting the layers under your pointer.',
        ],
        'js' => true,
        'docs' => 'https://cta.gallery',
        'props' => [
            ['name' => 'parallax', 'type' => 'px', 'default' => "'±4 … ±22'", 'note' => [
                'fa' => 'هر لایه ضریب خودش را دارد — ستاره‌ها ۴، خورشید ۱۰، تپه‌ها ۱۶ و ۲۲ پیکسل — و با حرکت موس مخالف هم می‌روند تا عمود دیده شود.',
                'en' => 'Each layer carries its own factor — stars 4, sun 10, hills 16 and 22px — and they counter-move with the pointer so the scene reads as depth.',
            ]],
            ['name' => 'overlay', 'type' => 'color', 'default' => "'#0a0c17 · 46–78%'", 'note' => [
                'fa' => 'اورلی تیرهٔ گرادیانی که پایینش پررنگ‌تر است؛ کنتراست متن روشن را تضمین می‌کند.',
                'en' => 'The gradient dark overlay, heavier at the bottom; it guarantees the bright copy’s contrast.',
            ]],
            ['name' => 'stats', 'type' => 'count', 'default' => "'3'", 'note' => [
                'fa' => 'سه آمار کوچک زیر تیتر — عدد درشت و برچسب کم‌رنگ — که اعتماد را قبل از کلیک می‌سازد.',
                'en' => 'Three small stats under the headline — big figure, muted label — building trust before the click.',
            ]],
            ['name' => 'glass', 'type' => 'background', 'default' => "'blur(14px) · 18% white'", 'note' => [
                'fa' => 'دکمهٔ شیشه‌ای روی صحنه؛ بلور پس‌زمینه را از داخل دکمه رد می‌کند و با هاور کمی روشن‌تر می‌شود.',
                'en' => 'The glass button over the scene; the blur lets the backdrop through and it brightens a touch on hover.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="ctim" x-data="{ x: 0, y: 0 }"
                 x-on:pointermove="const r = $el.getBoundingClientRect();
                     x = ((($event.clientX - r.left) / r.width) * 2 - 1).toFixed(3);
                     y = ((($event.clientY - r.top) / r.height) * 2 - 1).toFixed(3)"
                 x-on:pointerleave="x = 0; y = 0"
                 :style="'--ctim-x:' + x + '; --ctim-y:' + y">
            <svg class="ctim-art" viewBox="0 0 960 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <defs>
                    <linearGradient id="ctim-sky" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#1e1a4a"/><stop offset=".55" stop-color="#7c3aed"/><stop offset="1" stop-color="#f472b6"/>
                    </linearGradient>
                </defs>
                <rect width="960" height="400" fill="url(#ctim-sky)"/>
                <g class="ctim-sun"><circle cx="600" cy="190" r="58" fill="#ffd68a"/></g>
                <path class="ctim-hill-b" d="M0 292 C160 232 300 268 460 260 C640 250 760 286 960 268 V400 H0 Z" fill="#3b31a3"/>
                <path class="ctim-hill-f" d="M0 340 C200 296 380 330 560 322 C740 314 840 336 960 326 V400 H0 Z" fill="#1e1a4a"/>
            </svg>
            <div class="ctim-body">
                <h3>منتشر کن. تماشا کن. یاد بگیر.</h3>
                <div class="ctim-stats">
                    <div><b>۹۹٫۹۹٪</b><small>آپ‌تایم شبکهٔ رله</small></div>
                </div>
                <a class="ctim-glass" href="/signup">شروع رایگان</a>
            </div>
        </section>

        <style>
        .ctim { position: relative; overflow: clip; border-radius: 24px; color: #fff; }
        .ctim::after { content: ''; position: absolute; inset: 0;
                       background: linear-gradient(to top, #0a0c17c7, #0a0c1775 55%, #0a0c1740); }
        .ctim-body { position: relative; z-index: 1; display: grid; gap: 1.25rem; justify-items: center;
                     padding: 4rem 1.5rem; text-align: center; }
        .ctim-sun { transform: translate(calc(var(--ctim-x, 0) * 10px), calc(var(--ctim-y, 0) * 6px));
                    transition: transform .3s ease-out; }
        .ctim-hill-f { transform: translate(calc(var(--ctim-x, 0) * -22px), 0); transition: transform .3s ease-out; }
        .ctim-glass { padding: .9rem 1.75rem; border-radius: 999px; background: #ffffff2e;
                      backdrop-filter: blur(14px); box-shadow: inset 0 0 0 1px #ffffff59; color: #fff; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="ctim" x-data="{ x: 0, y: 0 }"
        x-on:pointermove="const r = $el.getBoundingClientRect();
            x = ((($event.clientX - r.left) / r.width) * 2 - 1).toFixed(3);
            y = ((($event.clientY - r.top) / r.height) * 2 - 1).toFixed(3)"
        x-on:pointerleave="x = 0; y = 0"
        :style="'--ctim-x:' + x + '; --ctim-y:' + y">
        <svg class="ctim-art" viewBox="0 0 960 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <defs>
        <linearGradient id="ctim-sky" x1="0" y1="0" x2="0" y2="1">
        <stop offset="0" stop-color="#1e1a4a"/><stop offset=".55" stop-color="#7c3aed"/><stop offset="1" stop-color="#f472b6"/>
        </linearGradient>
        </defs>
        <rect width="960" height="400" fill="url(#ctim-sky)"/>
        <g class="ctim-sun"><circle cx="600" cy="190" r="58" fill="#ffd68a"/></g>
        <path class="ctim-hill-b" d="M0 292 C160 232 300 268 460 260 C640 250 760 286 960 268 V400 H0 Z" fill="#3b31a3"/>
        <path class="ctim-hill-f" d="M0 340 C200 296 380 330 560 322 C740 314 840 336 960 326 V400 H0 Z" fill="#1e1a4a"/>
        </svg>
        <div class="ctim-body">
        <h3>Launch. Watch. Learn.</h3>
        <div class="ctim-stats">
        <div><b>99.99%</b><small>relay uptime</small></div>
        </div>
        <a class="ctim-glass" href="/signup">Start free</a>
        </div>
        </section>

        <style>
        .ctim { position: relative; overflow: clip; border-radius: 24px; color: #fff; }
        .ctim::after { content: ''; position: absolute; inset: 0;
                       background: linear-gradient(to top, #0a0c17c7, #0a0c1775 55%, #0a0c1740); }
        .ctim-body { position: relative; z-index: 1; display: grid; gap: 1.25rem; justify-items: center;
                     padding: 4rem 1.5rem; text-align: center; }
        .ctim-sun { transform: translate(calc(var(--ctim-x, 0) * 10px), calc(var(--ctim-y, 0) * 6px));
                    transition: transform .3s ease-out; }
        .ctim-hill-f { transform: translate(calc(var(--ctim-x, 0) * -22px), 0); transition: transform .3s ease-out; }
        .ctim-glass { padding: .9rem 1.75rem; border-radius: 999px; background: #ffffff2e;
                      backdrop-filter: blur(14px); box-shadow: inset 0 0 0 1px #ffffff59; color: #fff; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Launch.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ImageCta from '@/components/ImageCta';

        export default function Launch() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ImageCta />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ImageCta.jsx — scenic SVG backdrop, dark overlay, pointer parallax
        import '@nabuxai/ui-core/css';

        export default function ImageCta() {
          // Pointer position becomes two CSS vars; each layer reads them
          // with its own factor, so the scene gains depth for free.
          const track = (e) => {
            const el = e.currentTarget;
            const r = el.getBoundingClientRect();
            el.style.setProperty('--ctim-x', (((e.clientX - r.left) / r.width) * 2 - 1).toFixed(3));
            el.style.setProperty('--ctim-y', (((e.clientY - r.top) / r.height) * 2 - 1).toFixed(3));
          };
          const reset = (e) => {
            e.currentTarget.style.setProperty('--ctim-x', 0);
            e.currentTarget.style.setProperty('--ctim-y', 0);
          };

          return (
            <>
              <style>{`
                .ctim { position: relative; overflow: clip; border-radius: 1.5rem; color: #fff; }
                .ctim::after { content: ''; position: absolute; inset: 0; pointer-events: none;
                               background: linear-gradient(to top, #0a0c17c7, #0a0c1775 55%, #0a0c1740); }
                .ctim-art { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
                .ctim .ctim-stars, .ctim .ctim-sun, .ctim .ctim-hill-b, .ctim .ctim-hill-f { transition: transform .3s ease-out; }
                .ctim .ctim-stars { transform: translate(calc(var(--ctim-x, 0) * 4px), calc(var(--ctim-y, 0) * 3px)); }
                .ctim .ctim-sun { transform: translate(calc(var(--ctim-x, 0) * 10px), calc(var(--ctim-y, 0) * 6px)); }
                .ctim .ctim-hill-b { transform: translate(calc(var(--ctim-x, 0) * -16px), 0); }
                .ctim .ctim-hill-f { transform: translate(calc(var(--ctim-x, 0) * -22px), 0); }
                .ctim-body { position: relative; z-index: 1; display: grid; gap: 1.25rem; justify-items: center;
                             padding: clamp(3.5rem, 9vw, 5.5rem) 1.5rem; text-align: center; }
                .ctim-title { margin: 0; max-inline-size: 20ch; font: 800 clamp(1.8rem, 4.2vw, 2.8rem)/1.15
                              'Bricolage Grotesque', Inter, sans-serif; letter-spacing: -.02em; text-wrap: balance; }
                .ctim-stats { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem 2.5rem; }
                .ctim-stats div { display: grid; gap: .2rem; }
                .ctim-stats b { font-size: 1.5rem; letter-spacing: -.02em; }
                .ctim-stats small { color: #ffffffb3; }
                .ctim-glass { display: inline-flex; align-items: center; gap: .5rem; block-size: 3rem; padding-inline: 1.6rem;
                              border-radius: 999px; background: #ffffff2e; backdrop-filter: blur(14px);
                              -webkit-backdrop-filter: blur(14px); box-shadow: inset 0 0 0 1px #ffffff59; color: #fff;
                              font-weight: 700; text-decoration: none; transition: background-color .18s, translate .18s; }
                .ctim-glass:hover { background: #ffffff40; translate: 0 -2px; }
                @media (prefers-reduced-motion: reduce) {
                  .ctim .ctim-stars, .ctim .ctim-sun, .ctim .ctim-hill-b, .ctim .ctim-hill-f { transition: none; transform: none; }
                  .ctim-glass { transition: none; }
                }
              `}</style>
              <section class="ctim" onPointerMove={track} onPointerLeave={reset}>
                <svg class="ctim-art" viewBox="0 0 960 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                  <defs>
                    <linearGradient id="ctim-sky" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0" stopColor="#1e1a4a" /><stop offset=".55" stopColor="#7c3aed" /><stop offset="1" stopColor="#f472b6" />
                    </linearGradient>
                  </defs>
                  <rect width="960" height="400" fill="url(#ctim-sky)" />
                  <g class="ctim-stars" fill="#fff">
                    <circle cx="120" cy="60" r="2" opacity=".8" /><circle cx="320" cy="110" r="1.5" opacity=".6" />
                    <circle cx="820" cy="52" r="2" opacity=".7" /><circle cx="700" cy="130" r="1.5" opacity=".5" />
                  </g>
                  <g class="ctim-sun">
                    <circle cx="600" cy="190" r="96" fill="#ffd68a" opacity=".22" />
                    <circle cx="600" cy="190" r="58" fill="#ffd68a" />
                  </g>
                  <path class="ctim-hill-b" d="M0 292 C160 232 300 268 460 260 C640 250 760 286 960 268 V400 H0 Z" fill="#3b31a3" />
                  <path class="ctim-hill-f" d="M0 340 C200 296 380 330 560 322 C740 314 840 336 960 326 V400 H0 Z" fill="#1e1a4a" />
                </svg>
                <div class="ctim-body">
                  <h3 class="ctim-title">Launch. Watch. Learn.</h3>
                  <div class="ctim-stats">
                    <div><b>99.99%</b><small>relay uptime</small></div>
                    <div><b>38ms</b><small>median latency · Frankfurt</small></div>
                    <div><b>4,200</b><small>active teams</small></div>
                  </div>
                  <a class="ctim-glass" href="/signup">Start free →</a>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ImageCta.vue — scenic SVG backdrop, dark overlay, pointer parallax -->
        <script setup lang="ts">
        import '@nabuxai/ui-core/css';

        // Pointer position becomes two CSS vars; each layer reads them
        // with its own factor, so the scene gains depth for free.
        const track = (e: PointerEvent) => {
          const el = e.currentTarget as HTMLElement;
          const r = el.getBoundingClientRect();
          el.style.setProperty('--ctim-x', (((e.clientX - r.left) / r.width) * 2 - 1).toFixed(3));
          el.style.setProperty('--ctim-y', (((e.clientY - r.top) / r.height) * 2 - 1).toFixed(3));
        };
        const reset = (e: PointerEvent) => {
          const el = e.currentTarget as HTMLElement;
          el.style.setProperty('--ctim-x', '0');
          el.style.setProperty('--ctim-y', '0');
        };
        </script>

        <template>
          <section class="ctim" @pointermove="track" @pointerleave="reset">
            <svg class="ctim-art" viewBox="0 0 960 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
              <defs>
                <linearGradient id="ctim-sky" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stop-color="#1e1a4a" /><stop offset=".55" stop-color="#7c3aed" /><stop offset="1" stop-color="#f472b6" />
                </linearGradient>
              </defs>
              <rect width="960" height="400" fill="url(#ctim-sky)" />
              <g class="ctim-stars" fill="#fff">
                <circle cx="120" cy="60" r="2" opacity=".8" /><circle cx="320" cy="110" r="1.5" opacity=".6" />
                <circle cx="820" cy="52" r="2" opacity=".7" /><circle cx="700" cy="130" r="1.5" opacity=".5" />
              </g>
              <g class="ctim-sun">
                <circle cx="600" cy="190" r="96" fill="#ffd68a" opacity=".22" />
                <circle cx="600" cy="190" r="58" fill="#ffd68a" />
              </g>
              <path class="ctim-hill-b" d="M0 292 C160 232 300 268 460 260 C640 250 760 286 960 268 V400 H0 Z" fill="#3b31a3" />
              <path class="ctim-hill-f" d="M0 340 C200 296 380 330 560 322 C740 314 840 336 960 326 V400 H0 Z" fill="#1e1a4a" />
            </svg>
            <div class="ctim-body">
              <h3 class="ctim-title">Launch. Watch. Learn.</h3>
              <div class="ctim-stats">
                <div><b>99.99%</b><small>relay uptime</small></div>
                <div><b>38ms</b><small>median latency · Frankfurt</small></div>
                <div><b>4,200</b><small>active teams</small></div>
              </div>
              <a class="ctim-glass" href="/signup">Start free →</a>
            </div>
          </section>
        </template>

        <style scoped>
        .ctim { position: relative; overflow: clip; border-radius: 1.5rem; color: #fff; }
        .ctim::after { content: ''; position: absolute; inset: 0; pointer-events: none;
                       background: linear-gradient(to top, #0a0c17c7, #0a0c1775 55%, #0a0c1740); }
        .ctim-art { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ctim :deep(.ctim-stars), .ctim :deep(.ctim-sun), .ctim :deep(.ctim-hill-b), .ctim :deep(.ctim-hill-f) { transition: transform .3s ease-out; }
        .ctim :deep(.ctim-stars) { transform: translate(calc(var(--ctim-x, 0) * 4px), calc(var(--ctim-y, 0) * 3px)); }
        .ctim :deep(.ctim-sun) { transform: translate(calc(var(--ctim-x, 0) * 10px), calc(var(--ctim-y, 0) * 6px)); }
        .ctim :deep(.ctim-hill-b) { transform: translate(calc(var(--ctim-x, 0) * -16px), 0); }
        .ctim :deep(.ctim-hill-f) { transform: translate(calc(var(--ctim-x, 0) * -22px), 0); }
        .ctim-body { position: relative; z-index: 1; display: grid; gap: 1.25rem; justify-items: center;
                     padding: clamp(3.5rem, 9vw, 5.5rem) 1.5rem; text-align: center; }
        .ctim-title { margin: 0; max-inline-size: 20ch; font: 800 clamp(1.8rem, 4.2vw, 2.8rem)/1.15
                      'Bricolage Grotesque', Inter, sans-serif; letter-spacing: -.02em; text-wrap: balance; }
        .ctim-stats { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem 2.5rem; }
        .ctim-stats div { display: grid; gap: .2rem; }
        .ctim-stats b { font-size: 1.5rem; letter-spacing: -.02em; }
        .ctim-stats small { color: #ffffffb3; }
        .ctim-glass { display: inline-flex; align-items: center; gap: .5rem; block-size: 3rem; padding-inline: 1.6rem;
                      border-radius: 999px; background: #ffffff2e; backdrop-filter: blur(14px);
                      -webkit-backdrop-filter: blur(14px); box-shadow: inset 0 0 0 1px #ffffff59; color: #fff;
                      font-weight: 700; text-decoration: none; transition: background-color .18s, translate .18s; }
        .ctim-glass:hover { background: #ffffff40; translate: 0 -2px; }
        @media (prefers-reduced-motion: reduce) {
          .ctim :deep(.ctim-stars), .ctim :deep(.ctim-sun), .ctim :deep(.ctim-hill-b), .ctim :deep(.ctim-hill-f) { transition: none; transform: none; }
          .ctim-glass { transition: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ImageCta.svelte — scenic SVG backdrop, dark overlay, pointer parallax -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          // Pointer position becomes two CSS vars; each layer reads them
          // with its own factor, so the scene gains depth for free.
          const track = (e: PointerEvent) => {
            const el = e.currentTarget as HTMLElement;
            const r = el.getBoundingClientRect();
            el.style.setProperty('--ctim-x', (((e.clientX - r.left) / r.width) * 2 - 1).toFixed(3));
            el.style.setProperty('--ctim-y', (((e.clientY - r.top) / r.height) * 2 - 1).toFixed(3));
          };
          const reset = (e: PointerEvent) => {
            const el = e.currentTarget as HTMLElement;
            el.style.setProperty('--ctim-x', '0');
            el.style.setProperty('--ctim-y', '0');
          };
        </script>

        <section class="ctim" onpointermove={track} onpointerleave={reset}>
          <svg class="ctim-art" viewBox="0 0 960 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <defs>
              <linearGradient id="ctim-sky" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#1e1a4a" /><stop offset=".55" stop-color="#7c3aed" /><stop offset="1" stop-color="#f472b6" />
              </linearGradient>
            </defs>
            <rect width="960" height="400" fill="url(#ctim-sky)" />
            <g class="ctim-stars" fill="#fff">
              <circle cx="120" cy="60" r="2" opacity=".8" /><circle cx="320" cy="110" r="1.5" opacity=".6" />
              <circle cx="820" cy="52" r="2" opacity=".7" /><circle cx="700" cy="130" r="1.5" opacity=".5" />
            </g>
            <g class="ctim-sun">
              <circle cx="600" cy="190" r="96" fill="#ffd68a" opacity=".22" />
              <circle cx="600" cy="190" r="58" fill="#ffd68a" />
            </g>
            <path class="ctim-hill-b" d="M0 292 C160 232 300 268 460 260 C640 250 760 286 960 268 V400 H0 Z" fill="#3b31a3" />
            <path class="ctim-hill-f" d="M0 340 C200 296 380 330 560 322 C740 314 840 336 960 326 V400 H0 Z" fill="#1e1a4a" />
          </svg>
          <div class="ctim-body">
            <h3 class="ctim-title">Launch. Watch. Learn.</h3>
            <div class="ctim-stats">
              <div><b>99.99%</b><small>relay uptime</small></div>
              <div><b>38ms</b><small>median latency · Frankfurt</small></div>
              <div><b>4,200</b><small>active teams</small></div>
            </div>
            <a class="ctim-glass" href="/signup">Start free →</a>
          </div>
        </section>

        <style>
        .ctim { position: relative; overflow: clip; border-radius: 1.5rem; color: #fff; }
        .ctim::after { content: ''; position: absolute; inset: 0; pointer-events: none;
                       background: linear-gradient(to top, #0a0c17c7, #0a0c1775 55%, #0a0c1740); }
        .ctim-art { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ctim .ctim-stars, .ctim .ctim-sun, .ctim .ctim-hill-b, .ctim .ctim-hill-f { transition: transform .3s ease-out; }
        .ctim .ctim-stars { transform: translate(calc(var(--ctim-x, 0) * 4px), calc(var(--ctim-y, 0) * 3px)); }
        .ctim .ctim-sun { transform: translate(calc(var(--ctim-x, 0) * 10px), calc(var(--ctim-y, 0) * 6px)); }
        .ctim .ctim-hill-b { transform: translate(calc(var(--ctim-x, 0) * -16px), 0); }
        .ctim .ctim-hill-f { transform: translate(calc(var(--ctim-x, 0) * -22px), 0); }
        .ctim-body { position: relative; z-index: 1; display: grid; gap: 1.25rem; justify-items: center;
                     padding: clamp(3.5rem, 9vw, 5.5rem) 1.5rem; text-align: center; }
        .ctim-title { margin: 0; max-inline-size: 20ch; font: 800 clamp(1.8rem, 4.2vw, 2.8rem)/1.15
                      'Bricolage Grotesque', Inter, sans-serif; letter-spacing: -.02em; text-wrap: balance; }
        .ctim-stats { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem 2.5rem; }
        .ctim-stats div { display: grid; gap: .2rem; }
        .ctim-stats b { font-size: 1.5rem; letter-spacing: -.02em; }
        .ctim-stats small { color: #ffffffb3; }
        .ctim-glass { display: inline-flex; align-items: center; gap: .5rem; block-size: 3rem; padding-inline: 1.6rem;
                      border-radius: 999px; background: #ffffff2e; backdrop-filter: blur(14px);
                      -webkit-backdrop-filter: blur(14px); box-shadow: inset 0 0 0 1px #ffffff59; color: #fff;
                      font-weight: 700; text-decoration: none; transition: background-color .18s, translate .18s; }
        .ctim-glass:hover { background: #ffffff40; translate: 0 -2px; }
        @media (prefers-reduced-motion: reduce) {
          .ctim .ctim-stars, .ctim .ctim-sun, .ctim .ctim-hill-b, .ctim .ctim-hill-f { transition: none; transform: none; }
          .ctim-glass { transition: none; }
        }
        </style>
        SVELTE,
        ],
    ],

    'terminal-cta' => [
        'title' => ['fa' => 'CTA ترمینالی', 'en' => 'Terminal CTA'],
        'icon' => 'command',
        'oneLiner' => [
            'fa' => 'دعوت به کنش دولوپری: ترمینالی که دستور نصب را جلوی چشم تایپ می‌کند و دکمهٔ کپی‌اش تیک می‌زند؛ کنارش تیتر «از ترمینال تا دِپلوی»، دکمهٔ ستاره در گیت‌هاب با شمارنده‌ای که واقعاً بالا می‌رود و لینک مستندات با آیکون.',
            'en' => 'The developer call-to-action: a terminal that types the install command in front of you with a copy button that ticks, beside a “terminal to deploy” headline, a GitHub star button whose counter really climbs, and a docs link with an icon.',
        ],
        'js' => true,
        'docs' => 'https://cta.gallery',
        'props' => [
            ['name' => 'command', 'type' => 'string', 'default' => "'npm i -g @meridian/cli'", 'note' => [
                'fa' => 'دستوری که حرف‌به‌حرف تایپ می‌شود، مکث می‌کند و بعد از چند ثانیه دوباره از نو؛ خروجی‌اش با تیک سبز می‌آید.',
                'en' => 'The command typed character by character, held for a beat, then replayed from the top; its output lands with a green check.',
            ]],
            ['name' => 'type-speed', 'type' => 'ms', 'default' => "'55'", 'note' => [
                'fa' => 'سرعت تایپ به‌ازای هر نویسه؛ با prefers-reduced-motion کل دستور یک‌جا و بی‌انیمیشن می‌نشیند.',
                'en' => 'Per-character typing speed; under prefers-reduced-motion the whole command lands at once, unanimated.',
            ]],
            ['name' => 'copy', 'type' => 'state', 'default' => "'idle → done'", 'note' => [
                'fa' => 'دکمهٔ کپی دستور را در کلیپ‌بورد می‌نویسد و آیکونش تا ۱٫۸ ثانیه تیک می‌زند.',
                'en' => 'The copy button writes the command to the clipboard and swaps its icon to a tick for 1.8 seconds.',
            ]],
            ['name' => 'stars', 'type' => 'number', 'default' => "'12,840'", 'note' => [
                'fa' => 'شمارندهٔ ستاره؛ هر کلیک یک ستاره اضافه می‌کند و دوباره کلیک همان را برمی‌دارد.',
                'en' => 'The star counter; each click adds a star and clicking again takes it right back.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cttr" x-data="{
                cmd: 'npm i -g @meridian/cli', typed: '', done: false, copied: false,
                init() {
                    if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        this.typed = this.cmd; this.done = true; return;
                    }
                    let i = 0;
                    const t = setInterval(() => {
                        this.typed = this.cmd.slice(0, ++i);
                        if (i >= this.cmd.length) { clearInterval(t); setTimeout(() => { this.done = true }, 650); }
                    }, 55);
                },
                copy() { navigator.clipboard?.writeText(this.cmd).catch(() => {});
                         this.copied = true; setTimeout(() => this.copied = false, 1800); },
            }" x-init="init()">
            <div class="cttr-term" dir="ltr">
                <div class="cttr-bar">
                    <span class="cttr-dot" data-r></span><span class="cttr-dot" data-y></span><span class="cttr-dot" data-g></span>
                    <span class="cttr-tab">meridian — zsh</span>
                    <button class="cttr-copy" x-on:click="copy()" x-text="copied ? '✓ کپی شد' : 'کپی'"></button>
                </div>
                <div class="cttr-screen">
                    <p><span class="cttr-p">$</span> <span x-text="typed"></span><span class="cttr-caret"></span></p>
                    <p class="cttr-out" x-bind:class="{ 'cttr-on': done }">✓ meridian 2.4.1 — linked to the Frankfurt edge</p>
                </div>
            </div>
            <a class="cttr-docs" href="https://cta.gallery" target="_blank" rel="noopener">مستندات CLI ↗</a>
        </div>

        <style>
        .cttr-term { border-radius: 14px; background: #0a0c17; color: #f7f7fa; overflow: clip;
                     box-shadow: 0 24px 48px #0a0c173d; }
        .cttr-bar { display: flex; align-items: center; gap: .5rem; padding: .6rem .9rem;
                    background: #141726; border-block-end: 1px solid #2a2d40; }
        .cttr-dot { inline-size: .7rem; aspect-ratio: 1; border-radius: 50%; }
        .cttr-dot[data-r] { background: #ff7a7a; } .cttr-dot[data-y] { background: #fbbf24; } .cttr-dot[data-g] { background: #10b981; }
        .cttr-screen { padding: 1.1rem 1.25rem; font: 500 .85rem/1.9 ui-monospace, monospace; }
        .cttr-p { color: #10b981; }
        .cttr-caret { display: inline-block; inline-size: .55em; block-size: 1.05em; background: #7df3ff;
                      vertical-align: text-bottom; animation: cttr-blink 1.1s steps(2) infinite; }
        @keyframes cttr-blink { 50% { opacity: 0; } }
        .cttr-out { margin: 0; color: #9a9db3; opacity: 0; translate: 0 4px; transition: opacity .3s, translate .3s; }
        .cttr-out.cttr-on { opacity: 1; translate: 0 0; }
        @media (prefers-reduced-motion: reduce) { .cttr-caret { animation: none; } .cttr-out { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cttr" x-data="{
        cmd: 'npm i -g @meridian/cli', typed: '', done: false, copied: false,
        init() {
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
                this.typed = this.cmd; this.done = true; return;
            }
            let i = 0;
            const t = setInterval(() => {
                this.typed = this.cmd.slice(0, ++i);
                if (i >= this.cmd.length) { clearInterval(t); setTimeout(() => { this.done = true }, 650); }
            }, 55);
        },
        copy() { navigator.clipboard?.writeText(this.cmd).catch(() => {});
                 this.copied = true; setTimeout(() => this.copied = false, 1800); },
        }" x-init="init()">
        <div class="cttr-term" dir="ltr">
        <div class="cttr-bar">
        <span class="cttr-dot" data-r></span><span class="cttr-dot" data-y></span><span class="cttr-dot" data-g></span>
        <span class="cttr-tab">meridian — zsh</span>
        <button class="cttr-copy" x-on:click="copy()" x-text="copied ? '✓ copied' : 'copy'"></button>
        </div>
        <div class="cttr-screen">
        <p><span class="cttr-p">$</span> <span x-text="typed"></span><span class="cttr-caret"></span></p>
        <p class="cttr-out" x-bind:class="{ 'cttr-on': done }">✓ meridian 2.4.1 — linked to the Frankfurt edge</p>
        </div>
        </div>
        <a class="cttr-docs" href="https://cta.gallery" target="_blank" rel="noopener">CLI docs ↗</a>
        </div>

        <style>
        .cttr-term { border-radius: 14px; background: #0a0c17; color: #f7f7fa; overflow: clip;
                     box-shadow: 0 24px 48px #0a0c173d; }
        .cttr-bar { display: flex; align-items: center; gap: .5rem; padding: .6rem .9rem;
                    background: #141726; border-block-end: 1px solid #2a2d40; }
        .cttr-dot { inline-size: .7rem; aspect-ratio: 1; border-radius: 50%; }
        .cttr-dot[data-r] { background: #ff7a7a; } .cttr-dot[data-y] { background: #fbbf24; } .cttr-dot[data-g] { background: #10b981; }
        .cttr-screen { padding: 1.1rem 1.25rem; font: 500 .85rem/1.9 ui-monospace, monospace; }
        .cttr-p { color: #10b981; }
        .cttr-caret { display: inline-block; inline-size: .55em; block-size: 1.05em; background: #7df3ff;
                      vertical-align: text-bottom; animation: cttr-blink 1.1s steps(2) infinite; }
        @keyframes cttr-blink { 50% { opacity: 0; } }
        .cttr-out { margin: 0; color: #9a9db3; opacity: 0; translate: 0 4px; transition: opacity .3s, translate .3s; }
        .cttr-out.cttr-on { opacity: 1; translate: 0 0; }
        @media (prefers-reduced-motion: reduce) { .cttr-caret { animation: none; } .cttr-out { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Developers.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import TerminalCta from '@/components/TerminalCta';

        export default function Developers() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <TerminalCta />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // TerminalCta.jsx — typed install command, ticking copy, star counter
        import { useEffect, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const CMD = 'npm i -g @meridian/cli';

        export default function TerminalCta() {
          const [typed, setTyped] = useState('');
          const [done, setDone] = useState(false);
          const [copied, setCopied] = useState(false);
          const [stars, setStars] = useState(12840);
          const [starred, setStarred] = useState(false);

          // Types the command once; reduced motion lands it whole.
          useEffect(() => {
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
              setTyped(CMD); setDone(true); return;
            }
            let i = 0;
            const t = setInterval(() => {
              i += 1; setTyped(CMD.slice(0, i));
              if (i >= CMD.length) { clearInterval(t); setTimeout(() => setDone(true), 650); }
            }, 55);
            return () => clearInterval(t);
          }, []);

          const copy = async () => {
            try { await navigator.clipboard.writeText(CMD); } catch { /* demo: tick anyway */ }
            setCopied(true); setTimeout(() => setCopied(false), 1800);
          };
          const star = () => { setStarred(!starred); setStars(stars + (starred ? -1 : 1)); };

          return (
            <>
              <style>{`
                .cttr-wrap { display: grid; gap: 2rem; align-items: center; justify-items: center;
                             grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr)); max-inline-size: 56rem;
                             font-family: Inter, system-ui, sans-serif; }
                .cttr-copy h2 { margin: 0 0 .5rem; font-size: 2rem; letter-spacing: -.02em; color: #141726; }
                .cttr-copy p { margin: 0 0 1.25rem; color: #6c7088; }
                .cttr-row { display: flex; flex-wrap: wrap; gap: .75rem; }
                .cttr-star { display: inline-flex; align-items: center; gap: .5rem; block-size: 2.75rem; padding-inline: 1.1rem;
                             border-radius: .75rem; border: 1px solid #dfe0e8; background: #fff; color: #141726;
                             font: 600 .875rem/1 Inter, sans-serif; cursor: pointer;
                             transition: border-color .18s, translate .18s, background-color .18s; }
                .cttr-star:hover { translate: 0 -2px; border-color: #d98a1c; }
                .cttr-star[data-on='true'] { border-color: #f4a93c; background: #fff7ea; }
                .cttr-star[data-on='true'] svg { fill: #f4a93c; }
                .cttr-docs { display: inline-flex; align-items: center; gap: .45rem; block-size: 2.75rem; color: #5647e6;
                             font: 600 .875rem/1 Inter, sans-serif; text-decoration: none; }
                .cttr-term { inline-size: 100%; border-radius: .875rem; background: #0a0c17; color: #f7f7fa;
                             overflow: clip; box-shadow: 0 24px 48px #0a0c173d; }
                .cttr-bar { display: flex; align-items: center; gap: .5rem; padding: .6rem .9rem; background: #141726;
                            border-block-end: 1px solid #2a2d40; }
                .cttr-dot { inline-size: .7rem; aspect-ratio: 1; border-radius: 50%; }
                .cttr-dot[data-r] { background: #ff7a7a; } .cttr-dot[data-y] { background: #fbbf24; }
                .cttr-dot[data-g] { background: #10b981; }
                .cttr-tab { margin-inline-start: .35rem; font: 500 .72rem/1 ui-monospace, monospace; color: #6c7088; }
                .cttr-copybtn { margin-inline-start: auto; border: none; background: transparent; cursor: pointer;
                                font: 600 .72rem/1 ui-monospace, monospace; color: #9a9db3; padding: .35rem .6rem;
                                border-radius: .5rem; transition: color .18s, background-color .18s; }
                .cttr-copybtn:hover { color: #f7f7fa; background: #1d2031; }
                .cttr-copybtn[data-done='true'] { color: #3ddc97; }
                .cttr-screen { min-block-size: 8.75rem; padding: 1.1rem 1.25rem;
                               font: 500 .85rem/1.9 ui-monospace, 'JetBrains Mono', monospace; }
                .cttr-p { color: #10b981; }
                .cttr-caret { display: inline-block; inline-size: .55em; block-size: 1.05em; background: #7df3ff;
                              vertical-align: text-bottom; animation: cttr-blink 1.1s steps(2) infinite; }
                @keyframes cttr-blink { 50% { opacity: 0; } }
                .cttr-out { margin: 0; opacity: 0; translate: 0 4px; transition: opacity .3s, translate .3s; }
                .cttr-out[data-on='true'] { opacity: 1; translate: 0 0; }
                .cttr-out b { color: #3ddc97; font-weight: 500; }
                .cttr-out span { color: #7df3ff; }
                @media (prefers-reduced-motion: reduce) {
                  .cttr-caret { animation: none; }
                  .cttr-out, .cttr-star { transition: none; }
                }
              `}</style>
              <div className="cttr-wrap">
                <div className="cttr-copy">
                  <h2>Terminal to deploy, thirty seconds.</h2>
                  <p>The CLI settles in with one command — your first rollout lands before you write a line of config.</p>
                  <div className="cttr-row">
                    <button type="button" className="cttr-star" data-on={starred} aria-pressed={starred} onClick={star}>
                      <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2">
                        <path d="M12 2.7l2.9 5.9 6.5.9-4.7 4.6 1.1 6.4L12 17.4 6.2 20.5l1.1-6.4L2.6 9.5l6.5-.9z" />
                      </svg>
                      {stars.toLocaleString('en-US')}
                    </button>
                    <a className="cttr-docs" href="https://cta.gallery" target="_blank" rel="noopener">
                      CLI docs <span aria-hidden="true">↗</span>
                    </a>
                  </div>
                </div>
                <div className="cttr-term" dir="ltr">
                  <div className="cttr-bar">
                    <span className="cttr-dot" data-r /><span className="cttr-dot" data-y /><span className="cttr-dot" data-g />
                    <span className="cttr-tab">meridian — zsh</span>
                    <button type="button" className="cttr-copybtn" data-done={copied} onClick={copy}>
                      {copied ? '✓ copied' : 'copy'}
                    </button>
                  </div>
                  <div className="cttr-screen">
                    <p><span className="cttr-p">$</span> {typed}<span className="cttr-caret" aria-hidden="true" /></p>
                    <p className="cttr-out" data-on={done}><b>✓</b> meridian 2.4.1 — linked to the Frankfurt edge</p>
                    <p className="cttr-out" data-on={done}><span>›</span> run “meridian deploy” to ship your first release</p>
                  </div>
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- TerminalCta.vue — typed install command, ticking copy, star counter -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const CMD = 'npm i -g @meridian/cli';
        const typed = ref('');
        const done = ref(false);
        const copied = ref(false);
        const stars = ref(12840);
        const starred = ref(false);
        let timer: number | undefined;

        onMounted(() => {
          // Types the command once; reduced motion lands it whole.
          if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
            typed.value = CMD; done.value = true; return;
          }
          let i = 0;
          timer = window.setInterval(() => {
            i += 1; typed.value = CMD.slice(0, i);
            if (i >= CMD.length) { clearInterval(timer); setTimeout(() => (done.value = true), 650); }
          }, 55);
        });
        onBeforeUnmount(() => clearInterval(timer));

        const copy = async () => {
          try { await navigator.clipboard.writeText(CMD); } catch { /* demo: tick anyway */ }
          copied.value = true; setTimeout(() => (copied.value = false), 1800);
        };
        const star = () => { starred.value = !starred.value; stars.value += starred.value ? 1 : -1; };
        </script>

        <template>
          <div class="cttr-wrap">
            <div class="cttr-copy">
              <h2>Terminal to deploy, thirty seconds.</h2>
              <p>The CLI settles in with one command — your first rollout lands before you write a line of config.</p>
              <div class="cttr-row">
                <button type="button" class="cttr-star" :data-on="starred" :aria-pressed="starred" @click="star">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2.7l2.9 5.9 6.5.9-4.7 4.6 1.1 6.4L12 17.4 6.2 20.5l1.1-6.4L2.6 9.5l6.5-.9z" />
                  </svg>
                  {{ stars.toLocaleString('en-US') }}
                </button>
                <a class="cttr-docs" href="https://cta.gallery" target="_blank" rel="noopener">
                  CLI docs <span aria-hidden="true">↗</span>
                </a>
              </div>
            </div>
            <div class="cttr-term" dir="ltr">
              <div class="cttr-bar">
                <span class="cttr-dot" data-r></span><span class="cttr-dot" data-y></span><span class="cttr-dot" data-g></span>
                <span class="cttr-tab">meridian — zsh</span>
                <button type="button" class="cttr-copybtn" :data-done="copied" @click="copy">
                  {{ copied ? '✓ copied' : 'copy' }}
                </button>
              </div>
              <div class="cttr-screen">
                <p><span class="cttr-p">$</span> {{ typed }}<span class="cttr-caret" aria-hidden="true"></span></p>
                <p class="cttr-out" :data-on="done"><b>✓</b> meridian 2.4.1 — linked to the Frankfurt edge</p>
                <p class="cttr-out" :data-on="done"><span>›</span> run “meridian deploy” to ship your first release</p>
              </div>
            </div>
          </div>
        </template>

        <style scoped>
        .cttr-wrap { display: grid; gap: 2rem; align-items: center; justify-items: center;
                     grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr)); max-inline-size: 56rem;
                     font-family: Inter, system-ui, sans-serif; }
        .cttr-copy h2 { margin: 0 0 .5rem; font-size: 2rem; letter-spacing: -.02em; color: #141726; }
        .cttr-copy p { margin: 0 0 1.25rem; color: #6c7088; }
        .cttr-row { display: flex; flex-wrap: wrap; gap: .75rem; }
        .cttr-star { display: inline-flex; align-items: center; gap: .5rem; block-size: 2.75rem; padding-inline: 1.1rem;
                     border-radius: .75rem; border: 1px solid #dfe0e8; background: #fff; color: #141726;
                     font: 600 .875rem/1 Inter, sans-serif; cursor: pointer;
                     transition: border-color .18s, translate .18s, background-color .18s; }
        .cttr-star:hover { translate: 0 -2px; border-color: #d98a1c; }
        .cttr-star[data-on='true'] { border-color: #f4a93c; background: #fff7ea; }
        .cttr-star[data-on='true'] svg { fill: #f4a93c; }
        .cttr-docs { display: inline-flex; align-items: center; gap: .45rem; block-size: 2.75rem; color: #5647e6;
                     font: 600 .875rem/1 Inter, sans-serif; text-decoration: none; }
        .cttr-term { inline-size: 100%; border-radius: .875rem; background: #0a0c17; color: #f7f7fa;
                     overflow: clip; box-shadow: 0 24px 48px #0a0c173d; }
        .cttr-bar { display: flex; align-items: center; gap: .5rem; padding: .6rem .9rem; background: #141726;
                    border-block-end: 1px solid #2a2d40; }
        .cttr-dot { inline-size: .7rem; aspect-ratio: 1; border-radius: 50%; }
        .cttr-dot[data-r] { background: #ff7a7a; } .cttr-dot[data-y] { background: #fbbf24; }
        .cttr-dot[data-g] { background: #10b981; }
        .cttr-tab { margin-inline-start: .35rem; font: 500 .72rem/1 ui-monospace, monospace; color: #6c7088; }
        .cttr-copybtn { margin-inline-start: auto; border: none; background: transparent; cursor: pointer;
                        font: 600 .72rem/1 ui-monospace, monospace; color: #9a9db3; padding: .35rem .6rem;
                        border-radius: .5rem; transition: color .18s, background-color .18s; }
        .cttr-copybtn:hover { color: #f7f7fa; background: #1d2031; }
        .cttr-copybtn[data-done='true'] { color: #3ddc97; }
        .cttr-screen { min-block-size: 8.75rem; padding: 1.1rem 1.25rem;
                       font: 500 .85rem/1.9 ui-monospace, 'JetBrains Mono', monospace; }
        .cttr-p { color: #10b981; }
        .cttr-caret { display: inline-block; inline-size: .55em; block-size: 1.05em; background: #7df3ff;
                      vertical-align: text-bottom; animation: cttr-blink 1.1s steps(2) infinite; }
        @keyframes cttr-blink { 50% { opacity: 0; } }
        .cttr-out { margin: 0; opacity: 0; translate: 0 4px; transition: opacity .3s, translate .3s; }
        .cttr-out[data-on='true'] { opacity: 1; translate: 0 0; }
        .cttr-out b { color: #3ddc97; font-weight: 500; }
        .cttr-out span { color: #7df3ff; }
        @media (prefers-reduced-motion: reduce) {
          .cttr-caret { animation: none; }
          .cttr-out, .cttr-star { transition: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- TerminalCta.svelte — typed install command, ticking copy, star counter -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const CMD = 'npm i -g @meridian/cli';
          let typed = $state('');
          let done = $state(false);
          let copied = $state(false);
          let stars = $state(12840);
          let starred = $state(false);
          let timer: ReturnType<typeof setInterval> | undefined;

          onMount(() => {
            // Types the command once; reduced motion lands it whole.
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
              typed = CMD; done = true; return;
            }
            let i = 0;
            timer = setInterval(() => {
              i += 1; typed = CMD.slice(0, i);
              if (i >= CMD.length) { clearInterval(timer); setTimeout(() => (done = true), 650); }
            }, 55);
            return () => clearInterval(timer);
          });

          const copy = async () => {
            try { await navigator.clipboard.writeText(CMD); } catch { /* demo: tick anyway */ }
            copied = true; setTimeout(() => (copied = false), 1800);
          };
          const star = () => { starred = !starred; stars += starred ? 1 : -1; };
        </script>

        <div class="cttr-wrap">
          <div class="cttr-copy">
            <h2>Terminal to deploy, thirty seconds.</h2>
            <p>The CLI settles in with one command — your first rollout lands before you write a line of config.</p>
            <div class="cttr-row">
              <button type="button" class="cttr-star" data-on={starred} aria-pressed={starred} onclick={star}>
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 2.7l2.9 5.9 6.5.9-4.7 4.6 1.1 6.4L12 17.4 6.2 20.5l1.1-6.4L2.6 9.5l6.5-.9z" />
                </svg>
                {stars.toLocaleString('en-US')}
              </button>
              <a class="cttr-docs" href="https://cta.gallery" target="_blank" rel="noopener">
                CLI docs <span aria-hidden="true">↗</span>
              </a>
            </div>
          </div>
          <div class="cttr-term" dir="ltr">
            <div class="cttr-bar">
              <span class="cttr-dot" data-r></span><span class="cttr-dot" data-y></span><span class="cttr-dot" data-g></span>
              <span class="cttr-tab">meridian — zsh</span>
              <button type="button" class="cttr-copybtn" data-done={copied} onclick={copy}>
                {copied ? '✓ copied' : 'copy'}
              </button>
            </div>
            <div class="cttr-screen">
              <p><span class="cttr-p">$</span> {typed}<span class="cttr-caret" aria-hidden="true"></span></p>
              <p class="cttr-out" data-on={done}><b>✓</b> meridian 2.4.1 — linked to the Frankfurt edge</p>
              <p class="cttr-out" data-on={done}><span>›</span> run “meridian deploy” to ship your first release</p>
            </div>
          </div>
        </div>

        <style>
        .cttr-wrap { display: grid; gap: 2rem; align-items: center; justify-items: center;
                     grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr)); max-inline-size: 56rem;
                     font-family: Inter, system-ui, sans-serif; }
        .cttr-copy h2 { margin: 0 0 .5rem; font-size: 2rem; letter-spacing: -.02em; color: #141726; }
        .cttr-copy p { margin: 0 0 1.25rem; color: #6c7088; }
        .cttr-row { display: flex; flex-wrap: wrap; gap: .75rem; }
        .cttr-star { display: inline-flex; align-items: center; gap: .5rem; block-size: 2.75rem; padding-inline: 1.1rem;
                     border-radius: .75rem; border: 1px solid #dfe0e8; background: #fff; color: #141726;
                     font: 600 .875rem/1 Inter, sans-serif; cursor: pointer;
                     transition: border-color .18s, translate .18s, background-color .18s; }
        .cttr-star:hover { translate: 0 -2px; border-color: #d98a1c; }
        .cttr-star[data-on='true'] { border-color: #f4a93c; background: #fff7ea; }
        .cttr-star[data-on='true'] svg { fill: #f4a93c; }
        .cttr-docs { display: inline-flex; align-items: center; gap: .45rem; block-size: 2.75rem; color: #5647e6;
                     font: 600 .875rem/1 Inter, sans-serif; text-decoration: none; }
        .cttr-term { inline-size: 100%; border-radius: .875rem; background: #0a0c17; color: #f7f7fa;
                     overflow: clip; box-shadow: 0 24px 48px #0a0c173d; }
        .cttr-bar { display: flex; align-items: center; gap: .5rem; padding: .6rem .9rem; background: #141726;
                    border-block-end: 1px solid #2a2d40; }
        .cttr-dot { inline-size: .7rem; aspect-ratio: 1; border-radius: 50%; }
        .cttr-dot[data-r] { background: #ff7a7a; } .cttr-dot[data-y] { background: #fbbf24; }
        .cttr-dot[data-g] { background: #10b981; }
        .cttr-tab { margin-inline-start: .35rem; font: 500 .72rem/1 ui-monospace, monospace; color: #6c7088; }
        .cttr-copybtn { margin-inline-start: auto; border: none; background: transparent; cursor: pointer;
                        font: 600 .72rem/1 ui-monospace, monospace; color: #9a9db3; padding: .35rem .6rem;
                        border-radius: .5rem; transition: color .18s, background-color .18s; }
        .cttr-copybtn:hover { color: #f7f7fa; background: #1d2031; }
        .cttr-copybtn[data-done='true'] { color: #3ddc97; }
        .cttr-screen { min-block-size: 8.75rem; padding: 1.1rem 1.25rem;
                       font: 500 .85rem/1.9 ui-monospace, 'JetBrains Mono', monospace; }
        .cttr-p { color: #10b981; }
        .cttr-caret { display: inline-block; inline-size: .55em; block-size: 1.05em; background: #7df3ff;
                      vertical-align: text-bottom; animation: cttr-blink 1.1s steps(2) infinite; }
        @keyframes cttr-blink { 50% { opacity: 0; } }
        .cttr-out { margin: 0; opacity: 0; translate: 0 4px; transition: opacity .3s, translate .3s; }
        .cttr-out[data-on='true'] { opacity: 1; translate: 0 0; }
        .cttr-out b { color: #3ddc97; font-weight: 500; }
        .cttr-out span { color: #7df3ff; }
        @media (prefers-reduced-motion: reduce) {
          .cttr-caret { animation: none; }
          .cttr-out, .cttr-star { transition: none; }
        }
        </style>
        SVELTE,
        ],
    ],
];
