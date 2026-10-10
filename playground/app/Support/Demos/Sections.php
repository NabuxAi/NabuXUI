<?php

/**
 * Demo manifest of the "sections" group — ready-made landing page sections in
 * the shadcnblocks spirit, each with its own identity: the full pricing table
 * with an animated monthly/yearly toggle, the breathing minimal feature grid,
 * the warm testimonial carousel, the trusty logo band, the single-open FAQ
 * and the dark stats band that counts in view. Scenarios live at
 * resources/views/demos/components/sections/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'بخش‌های لندینگ', 'en' => 'Landing sections'],

    'pricing-section' => [
        'title' => ['fa' => 'بخش قیمت‌گذاری', 'en' => 'Pricing section'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'جدول قیمت کامل: سه پلن با تاگل ماهانه/سالانه که قیمت‌ها را با یک تکان انیمیت می‌کند و بج «۲ ماه رایگان» را نشان می‌دهد، ردیف مقایسهٔ ویژگی‌ها زیر کارت‌ها و پانوشت ضمانت ۳۰ روزه — پلن میانی با کارت تیره از همه برجسته‌تر است.',
            'en' => 'The full pricing table: three plans under a monthly/yearly toggle that bumps the prices alive and flashes the “2 months free” badge, a feature-comparison grid beneath the cards and a 30-day guarantee footer — the middle plan stands out on a dark card.',
        ],
        'js' => true,
        'docs' => 'https://shadcnblocks.com',
        'props' => [
            ['name' => 'interval', 'type' => "'monthly' | 'yearly'", 'default' => "'yearly'", 'note' => [
                'fa' => 'تاگل بخش‌بندی‌شده با قرص لغزنده؛ قیمت هر سه کارت با انیمیشن bump جابه‌جا می‌شود و بج ذخیره فقط در حالت سالانه می‌آید.',
                'en' => 'The segmented toggle with a sliding pill; all three prices bump to their new value and the savings badge only shows on yearly.',
            ]],
            ['name' => 'highlight', 'type' => 'string', 'default' => "'growth'", 'note' => [
                'fa' => 'پلن میانی برجسته است: کارت تیره، بج «محبوب‌ترین» و بلندتر ایستادن از دو کارت کناری.',
                'en' => 'The middle plan is featured: a dark card, the “Most popular” badge, standing taller than its neighbours.',
            ]],
            ['name' => 'comparison', 'type' => 'boolean', 'default' => 'true', 'note' => [
                'fa' => 'جدول مقایسهٔ ویژگی‌ها زیر کارت‌ها — تیک برای دارا بودن، خط برای نداشتن، مقدار برای شمارش‌ها.',
                'en' => 'The feature comparison grid under the cards — ticks for included, dashes for not, values for counts.',
            ]],
            ['name' => 'guarantee', 'type' => 'slot', 'default' => "'30-day'", 'note' => [
                'fa' => 'پانوشت ضمانت با آیکون سپر: بازگشت وجه ۳۰ روزه بدون پرسش، به‌همراه لینک گفت‌وگو با فروش.',
                'en' => 'The guarantee footer with a shield icon: 30-day no-questions refunds plus a talk-to-sales link.',
            ]],
            ['name' => '--secpr-accent', 'type' => 'color', 'default' => "'#4F46E5'", 'note' => [
                'fa' => 'رنگ برند بخش؛ قرص تاگل، بج ذخیره و دکمهٔ اصلی از آن می‌آیند و در تم تیره روشن‌تر می‌شود.',
                'en' => 'The section brand colour; the toggle pill, the savings badge and the primary button draw from it, brightening in dark mode.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="secpr" x-data="{ yearly: true, fa: true,
                fmt(n) { return this.fa ? String(n).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) } }">
            <header class="secpr-head">
                <h2>قیمت‌گذاری بی‌پیچیدگی</h2>
                <div class="secpr-toggle" :data-yearly="yearly" role="group" aria-label="دورهٔ پرداخت">
                    <button type="button" :class="!yearly && 'secpr-on'" x-on:click="yearly = false">ماهانه</button>
                    <button type="button" :class="yearly && 'secpr-on'" x-on:click="yearly = true">
                        سالانه <em class="secpr-save" x-show="yearly" x-cloak>۲ ماه رایگان</em>
                    </button>
                </div>
            </header>

            <div class="secpr-cards">
                <!-- کارت میانی تیره و برجسته است -->
                <article class="secpr-card" data-featured="true">
                    <span class="secpr-pop">محبوب‌ترین</span>
                    <h3>رشد</h3>
                    <p class="secpr-price"><span class="secpr-amount" x-text="fmt(yearly ? 23 : 29)"></span><small>دلار در ماه</small></p>
                    <ul><li>۱۰ عضو تیم</li><li>۲۵ پروژه</li><li>پشتیبانی اولویت‌دار</li></ul>
                    <button type="button" class="secpr-cta">شروع دورهٔ آزمایشی</button>
                </article>
            </div>

            <p class="secpr-guarantee"><svg aria-hidden="true">…سپر…</svg>ضمانت بازگشت وجه ۳۰ روزه — بدون پرسش</p>
        </section>

        <style>
        .secpr-toggle { position: relative; display: inline-flex; border: 1px solid #E7E5E4; border-radius: 999px; }
        .secpr-toggle::before { content: ''; position: absolute; inset-block: 3px; inset-inline-start: 3px;
                                 inline-size: calc(50% - 3px); background: #4F46E5; border-radius: 999px;
                                 transition: translate .25s cubic-bezier(.2,0,0,1); }
        .secpr-toggle[data-yearly='true']::before { translate: 100% 0; }
        .secpr-amount { display: inline-block; font: 800 2.75rem/1 'Inter', 'Vazirmatn', sans-serif; }
        .secpr-amount.secpr-bump { animation: secpr-bump .35s cubic-bezier(.2,0,0,1); }
        @keyframes secpr-bump { 0% { opacity: 0; translate: 0 .35em; } 100% { opacity: 1; translate: 0 0; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="secpr" x-data="{ yearly: true }">
            <header class="secpr-head">
                <h2>Pricing without the fine print</h2>
                <div class="secpr-toggle" :data-yearly="yearly" role="group" aria-label="Billing interval">
                    <button type="button" :class="!yearly && 'secpr-on'" x-on:click="yearly = false">Monthly</button>
                    <button type="button" :class="yearly && 'secpr-on'" x-on:click="yearly = true">
                        Yearly <em class="secpr-save" x-show="yearly" x-cloak>2 months free</em>
                    </button>
                </div>
            </header>

            <div class="secpr-cards">
                <!-- the middle card is the dark, featured one -->
                <article class="secpr-card" data-featured="true">
                    <span class="secpr-pop">Most popular</span>
                    <h3>Growth</h3>
                    <p class="secpr-price"><span class="secpr-amount" x-text="yearly ? 23 : 29"></span><small>$ per month</small></p>
                    <ul><li>10 seats</li><li>25 projects</li><li>Priority support</li></ul>
                    <button type="button" class="secpr-cta">Start the trial</button>
                </article>
            </div>

            <p class="secpr-guarantee"><svg aria-hidden="true">…shield…</svg>30-day money-back guarantee — no questions asked</p>
        </section>

        <style>
        .secpr-toggle { position: relative; display: inline-flex; border: 1px solid #E7E5E4; border-radius: 999px; }
        .secpr-toggle::before { content: ''; position: absolute; inset-block: 3px; inset-inline-start: 3px;
                                 inline-size: calc(50% - 3px); background: #4F46E5; border-radius: 999px;
                                 transition: translate .25s cubic-bezier(.2,0,0,1); }
        .secpr-toggle[data-yearly='true']::before { translate: 100% 0; }
        .secpr-amount { display: inline-block; font: 800 2.75rem/1 'Inter', 'Vazirmatn', sans-serif; }
        .secpr-amount.secpr-bump { animation: secpr-bump .35s cubic-bezier(.2,0,0,1); }
        @keyframes secpr-bump { 0% { opacity: 0; translate: 0 .35em; } 100% { opacity: 1; translate: 0 0; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Pricing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import PricingSection from '@/components/PricingSection';

        export default function Pricing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <PricingSection />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // PricingSection.jsx — animated monthly/yearly toggle, featured middle plan
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PLANS = [
          { id: 'starter', name: 'Starter', tagline: 'For side projects', monthly: 12, yearly: 9,
            perks: ['3 seats', '5 projects', 'Community support'] },
          { id: 'growth', name: 'Growth', tagline: 'For shipping teams', monthly: 29, yearly: 23, featured: true,
            perks: ['10 seats', '25 projects', 'Priority support', 'Usage alerts'] },
          { id: 'scale', name: 'Scale', tagline: 'For platforms', monthly: 79, yearly: 63,
            perks: ['Unlimited seats', 'SSO / SAML', 'Audit log', 'Dedicated engineer'] },
        ];

        export default function PricingSection() {
          const [yearly, setYearly] = useState(true);

          return (
            <>
              <style>{`
                .scp { display: grid; gap: 2rem; justify-items: center; padding: 3rem 1.5rem;
                       font: 400 .9375rem/1.5 'Inter', sans-serif; background: #FAFAF9; color: #1C1917; }
                .scp-toggle { position: relative; display: inline-flex; border: 1px solid #E7E5E4; border-radius: 999px; background: #fff; }
                .scp-toggle::before { content: ''; position: absolute; inset-block: 3px; inset-inline-start: 3px;
                                      inline-size: calc(50% - 3px); background: #4F46E5; border-radius: 999px;
                                      transition: translate .25s cubic-bezier(.2, 0, 0, 1); }
                .scp-toggle[data-yearly='true']::before { translate: 100% 0; }
                .scp-toggle button { position: relative; z-index: 1; padding: .55rem 1.25rem; border: none; background: transparent;
                                     font: 600 .875rem/1.2 'Inter', sans-serif; color: #78716C; cursor: pointer; transition: color .25s; }
                .scp-toggle button[aria-pressed='true'] { color: #fff; }
                .scp-cards { display: grid; gap: 1.25rem; grid-template-columns: repeat(3, minmax(13rem, 1fr)); }
                .scp-card { display: grid; gap: .9rem; align-content: start; padding: 1.75rem 1.5rem;
                            border: 1px solid #E7E5E4; border-radius: 1.25rem; background: #fff; }
                .scp-card[data-featured='true'] { background: #171512; color: #FAFAF9; border-color: #171512;
                                                  translate: 0 -1rem; box-shadow: 0 24px 48px -24px rgba(28, 25, 23, .5); }
                .scp-amount { display: inline-block; font: 800 2.75rem/1 'Inter', sans-serif; letter-spacing: -.03em; }
                .scp-amount.scp-bump { animation: scp-bump .35s cubic-bezier(.2, 0, 0, 1); }
                @keyframes scp-bump { from { opacity: 0; translate: 0 .35em; } to { opacity: 1; translate: 0 0; } }
                @media (max-width: 640px) { .scp-cards { grid-template-columns: 1fr; } .scp-card[data-featured='true'] { translate: none; } }
              `}</style>
              <section className="scp">
                <div className="scp-toggle" data-yearly={yearly} role="group" aria-label="Billing interval">
                  <button type="button" aria-pressed={!yearly} onClick={() => setYearly(false)}>Monthly</button>
                  <button type="button" aria-pressed={yearly} onClick={() => setYearly(true)}>Yearly · 2 months free</button>
                </div>
                <div className="scp-cards">
                  {PLANS.map((p) => (
                    <article className="scp-card" data-featured={!!p.featured} key={p.id}>
                      <h3>{p.name} <small>{p.tagline}</small></h3>
                      {/* keying the span on the interval remounts it, replaying the bump */}
                      <p><span key={String(yearly)} className="scp-amount scp-bump">{yearly ? p.yearly : p.monthly}</span> $/mo</p>
                      <ul>{p.perks.map((perk) => <li key={perk}>{perk}</li>)}</ul>
                      <button type="button">{p.featured ? 'Start the trial' : `Choose ${p.name}`}</button>
                    </article>
                  ))}
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- PricingSection.vue — animated monthly/yearly toggle, featured middle plan -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const plans = [
          { id: 'starter', name: 'Starter', tagline: 'For side projects', monthly: 12, yearly: 9,
            perks: ['3 seats', '5 projects', 'Community support'] },
          { id: 'growth', name: 'Growth', tagline: 'For shipping teams', monthly: 29, yearly: 23, featured: true,
            perks: ['10 seats', '25 projects', 'Priority support', 'Usage alerts'] },
          { id: 'scale', name: 'Scale', tagline: 'For platforms', monthly: 79, yearly: 63,
            perks: ['Unlimited seats', 'SSO / SAML', 'Audit log', 'Dedicated engineer'] },
        ];
        const yearly = ref(true);
        </script>

        <template>
          <section class="scp">
            <div class="scp-toggle" :data-yearly="yearly" role="group" aria-label="Billing interval">
              <button type="button" :aria-pressed="!yearly" @click="yearly = false">Monthly</button>
              <button type="button" :aria-pressed="yearly" @click="yearly = true">Yearly · 2 months free</button>
            </div>
            <div class="scp-cards">
              <article v-for="p in plans" :key="p.id" class="scp-card" :data-featured="!!p.featured">
                <h3>{{ p.name }} <small>{{ p.tagline }}</small></h3>
                <!-- keying the span on the interval remounts it, replaying the bump -->
                <p><span :key="String(yearly)" class="scp-amount scp-bump">{{ yearly ? p.yearly : p.monthly }}</span> $/mo</p>
                <ul><li v-for="perk in p.perks" :key="perk">{{ perk }}</li></ul>
                <button type="button">{{ p.featured ? 'Start the trial' : `Choose ${p.name}` }}</button>
              </article>
            </div>
          </section>
        </template>

        <style scoped>
        .scp { display: grid; gap: 2rem; justify-items: center; padding: 3rem 1.5rem;
               font: 400 .9375rem/1.5 'Inter', sans-serif; background: #FAFAF9; color: #1C1917; }
        .scp-toggle { position: relative; display: inline-flex; border: 1px solid #E7E5E4; border-radius: 999px; background: #fff; }
        .scp-toggle::before { content: ''; position: absolute; inset-block: 3px; inset-inline-start: 3px;
                              inline-size: calc(50% - 3px); background: #4F46E5; border-radius: 999px;
                              transition: translate .25s cubic-bezier(.2, 0, 0, 1); }
        .scp-toggle[data-yearly='true']::before { translate: 100% 0; }
        .scp-toggle button { position: relative; z-index: 1; padding: .55rem 1.25rem; border: none; background: transparent;
                             font: 600 .875rem/1.2 'Inter', sans-serif; color: #78716C; cursor: pointer; transition: color .25s; }
        .scp-toggle button[aria-pressed='true'] { color: #fff; }
        .scp-cards { display: grid; gap: 1.25rem; grid-template-columns: repeat(3, minmax(13rem, 1fr)); }
        .scp-card { display: grid; gap: .9rem; align-content: start; padding: 1.75rem 1.5rem;
                    border: 1px solid #E7E5E4; border-radius: 1.25rem; background: #fff; }
        .scp-card[data-featured='true'] { background: #171512; color: #FAFAF9; border-color: #171512;
                                          translate: 0 -1rem; box-shadow: 0 24px 48px -24px rgba(28, 25, 23, .5); }
        .scp-amount { display: inline-block; font: 800 2.75rem/1 'Inter', sans-serif; letter-spacing: -.03em; }
        .scp-amount.scp-bump { animation: scp-bump .35s cubic-bezier(.2, 0, 0, 1); }
        @keyframes scp-bump { from { opacity: 0; translate: 0 .35em; } to { opacity: 1; translate: 0 0; } }
        @media (max-width: 640px) { .scp-cards { grid-template-columns: 1fr; } .scp-card[data-featured='true'] { translate: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- PricingSection.svelte — animated monthly/yearly toggle, featured middle plan -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const plans = [
            { id: 'starter', name: 'Starter', tagline: 'For side projects', monthly: 12, yearly: 9,
              perks: ['3 seats', '5 projects', 'Community support'] },
            { id: 'growth', name: 'Growth', tagline: 'For shipping teams', monthly: 29, yearly: 23, featured: true,
              perks: ['10 seats', '25 projects', 'Priority support', 'Usage alerts'] },
            { id: 'scale', name: 'Scale', tagline: 'For platforms', monthly: 79, yearly: 63,
              perks: ['Unlimited seats', 'SSO / SAML', 'Audit log', 'Dedicated engineer'] },
          ];
          let yearly = $state(true);
        </script>

        <section class="scp">
          <div class="scp-toggle" data-yearly={yearly} role="group" aria-label="Billing interval">
            <button type="button" aria-pressed={!yearly} onclick={() => (yearly = false)}>Monthly</button>
            <button type="button" aria-pressed={yearly} onclick={() => (yearly = true)}>Yearly · 2 months free</button>
          </div>
          <div class="scp-cards">
            {#each plans as p (p.id)}
              <article class="scp-card" data-featured={!!p.featured}>
                <h3>{p.name} <small>{p.tagline}</small></h3>
                <!-- keying the span on the interval remounts it, replaying the bump -->
                {#key yearly}
                  <p><span class="scp-amount scp-bump">{yearly ? p.yearly : p.monthly}</span> $/mo</p>
                {/key}
                <ul>{#each p.perks as perk (perk)}<li>{perk}</li>{/each}</ul>
                <button type="button">{p.featured ? 'Start the trial' : `Choose ${p.name}`}</button>
              </article>
            {/each}
          </div>
        </section>

        <style>
        .scp { display: grid; gap: 2rem; justify-items: center; padding: 3rem 1.5rem;
               font: 400 .9375rem/1.5 'Inter', sans-serif; background: #FAFAF9; color: #1C1917; }
        .scp-toggle { position: relative; display: inline-flex; border: 1px solid #E7E5E4; border-radius: 999px; background: #fff; }
        .scp-toggle::before { content: ''; position: absolute; inset-block: 3px; inset-inline-start: 3px;
                              inline-size: calc(50% - 3px); background: #4F46E5; border-radius: 999px;
                              transition: translate .25s cubic-bezier(.2, 0, 0, 1); }
        .scp-toggle[data-yearly='true']::before { translate: 100% 0; }
        .scp-toggle button { position: relative; z-index: 1; padding: .55rem 1.25rem; border: none; background: transparent;
                             font: 600 .875rem/1.2 'Inter', sans-serif; color: #78716C; cursor: pointer; transition: color .25s; }
        .scp-toggle button[aria-pressed='true'] { color: #fff; }
        .scp-cards { display: grid; gap: 1.25rem; grid-template-columns: repeat(3, minmax(13rem, 1fr)); }
        .scp-card { display: grid; gap: .9rem; align-content: start; padding: 1.75rem 1.5rem;
                    border: 1px solid #E7E5E4; border-radius: 1.25rem; background: #fff; }
        .scp-card[data-featured='true'] { background: #171512; color: #FAFAF9; border-color: #171512;
                                          translate: 0 -1rem; box-shadow: 0 24px 48px -24px rgba(28, 25, 23, .5); }
        .scp-amount { display: inline-block; font: 800 2.75rem/1 'Inter', sans-serif; letter-spacing: -.03em; }
        .scp-amount.scp-bump { animation: scp-bump .35s cubic-bezier(.2, 0, 0, 1); }
        @keyframes scp-bump { from { opacity: 0; translate: 0 .35em; } to { opacity: 1; translate: 0 0; } }
        @media (max-width: 640px) { .scp-cards { grid-template-columns: 1fr; } .scp-card[data-featured='true'] { translate: none; } }
        </style>
        SVELTE,
        ],
    ],

    'feature-grid' => [
        'title' => ['fa' => 'گرید قابلیت‌ها', 'en' => 'Feature grid'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'شش قابلیت با آیکون خطی و توضیح کوتاه در گرید بنتوی نفس‌دار؛ دو خونهٔ بزرگ‌تر با تصویرسازی SVG خودشان داستان را تعریف می‌کنند و هاور ظریف فقط یک نگاه به آیکون می‌اندازد — مینیمال، سفید، بی‌سروصدا.',
            'en' => 'Six features with line icons and one-line proof on a breathing bento grid; two larger cells carry SVG illustrations that tell the story themselves, and the subtle hover only glances at the icon — minimal, white, quiet.',
        ],
        'js' => false,
        'docs' => 'https://shadcnblocks.com',
        'props' => [
            ['name' => 'columns', 'type' => 'number', 'default' => "'4'", 'note' => [
                'fa' => 'گرید بنتو در چهار ستون؛ هر قابلیت می‌تواند با span دو ستون بگیرد و خونه‌های بزرگ تصویرسازی دارند.',
                'en' => 'The bento grid runs four columns; a feature may span two of them, and the wide cells carry the illustrations.',
            ]],
            ['name' => 'icon', 'type' => 'svg', 'default' => "'1.75rem'", 'note' => [
                'fa' => 'آیکون خطی ۲ پیکسلی که با هاور رنگ برند می‌گیرد؛ ضخامت خط با آیکون‌های تصویرسازی یکی است.',
                'en' => 'A 2px line icon that takes the brand colour on hover; its stroke weight matches the illustrations.',
            ]],
            ['name' => 'illustration', 'type' => 'slot', 'default' => "'2 of 6'", 'note' => [
                'fa' => 'دو خونهٔ بزرگ تصویرسازی SVG دارند — گراف معنایی و نقشهٔ لبه — با شناسه‌های پیشونددار تا با دموی دیگر تداخل نکنند.',
                'en' => 'Two wide cells carry SVG illustrations — the semantic graph and the edge map — with prefixed ids so they never clash with another demo.',
            ]],
            ['name' => '--secfg-accent', 'type' => 'color', 'default' => "'#0F766E'", 'note' => [
                'fa' => 'رنگ اکسنت روی سفید خالص؛ فقط آیکون هاورشده، شماره‌ها و دو خط گراف آن را می‌بینند.',
                'en' => 'The accent on pure white; only the hovered icon, the indices and two graph lines ever show it.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="secfg">
            <header class="secfg-head">
                <h2>ابزارهایی که تیم را سریع نگه می‌دارند</h2>
                <p>اتلس دانش تیم را جست‌وجوپذیر، زنده و امن می‌کند.</p>
            </header>
            <div class="secfg-grid">
                <!-- خونه‌های بزرگ دو ستون می‌گیرند -->
                <article class="secfg-cell" data-span="2">
                    <svg class="secfg-art" viewBox="0 0 220 120" aria-hidden="true">…گراف معنایی…</svg>
                    <h3>جست‌وجوی معنایی</h3>
                    <p>پاسخ را پیدا می‌کند، نه فقط کلیدواژه را.</p>
                </article>
                <article class="secfg-cell">
                    <svg class="secfg-icon" aria-hidden="true">…آیکون خطی…</svg>
                    <h3>هم‌نویسی زنده</h3>
                    <p>مکان‌نماها با نام هم‌تیمی‌ها.</p>
                </article>
            </div>
        </section>

        <style>
        .secfg { padding: 3rem 1.5rem; background: #FFFFFF; color: #1C1917;
                 font-family: 'Inter', 'Vazirmatn', sans-serif; }
        .secfg-grid { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .secfg-cell { display: grid; gap: .6rem; align-content: start; padding: 1.5rem;
                      border: 1px solid #E7E5E4; border-radius: 1.25rem; transition: border-color .25s ease; }
        .secfg-cell:hover { border-color: #0F766E; }
        .secfg-cell[data-span='2'] { grid-column: span 2; }
        .secfg-icon { inline-size: 1.75rem; stroke-width: 2; stroke: #78716C;
                      transition: stroke .25s ease; fill: none; }
        .secfg-cell:hover .secfg-icon { stroke: #0F766E; }
        @media (max-width: 640px) { .secfg-grid { grid-template-columns: 1fr; } .secfg-cell[data-span='2'] { grid-column: auto; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="secfg">
            <header class="secfg-head">
                <h2>The tools that keep teams fast</h2>
                <p>Atlas keeps team knowledge searchable, alive and safe.</p>
            </header>
            <div class="secfg-grid">
                <!-- wide cells take two columns -->
                <article class="secfg-cell" data-span="2">
                    <svg class="secfg-art" viewBox="0 0 220 120" aria-hidden="true">…semantic graph…</svg>
                    <h3>Semantic search</h3>
                    <p>Finds the answer, not just the keyword.</p>
                </article>
                <article class="secfg-cell">
                    <svg class="secfg-icon" aria-hidden="true">…line icon…</svg>
                    <h3>Live co-editing</h3>
                    <p>Cursors carry your teammates’ names.</p>
                </article>
            </div>
        </section>

        <style>
        .secfg { padding: 3rem 1.5rem; background: #FFFFFF; color: #1C1917;
                 font-family: 'Inter', 'Vazirmatn', sans-serif; }
        .secfg-grid { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .secfg-cell { display: grid; gap: .6rem; align-content: start; padding: 1.5rem;
                      border: 1px solid #E7E5E4; border-radius: 1.25rem; transition: border-color .25s ease; }
        .secfg-cell:hover { border-color: #0F766E; }
        .secfg-cell[data-span='2'] { grid-column: span 2; }
        .secfg-icon { inline-size: 1.75rem; stroke-width: 2; stroke: #78716C;
                      transition: stroke .25s ease; fill: none; }
        .secfg-cell:hover .secfg-icon { stroke: #0F766E; }
        @media (max-width: 640px) { .secfg-grid { grid-template-columns: 1fr; } .secfg-cell[data-span='2'] { grid-column: auto; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Features.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import FeatureGrid from '@/components/FeatureGrid';

        export default function Features() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <FeatureGrid />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // FeatureGrid.jsx — minimal bento grid; wide cells span two columns
        import '@nabuxai/ui-core/css';

        const FEATURES = [
          { id: 'search', title: 'Semantic search', copy: 'Finds the answer, not just the keyword.', span: 2 },
          { id: 'live', title: 'Live co-editing', copy: 'Cursors carry your teammates’ names.' },
          { id: 'history', title: 'Version history', copy: 'Every page, every change, forever.' },
          { id: 'edge', title: 'Edge-fast everywhere', copy: 'Pages served from 34 regions — Frankfurt to Singapore.', span: 2 },
          { id: 'integrations', title: 'Integrations', copy: 'Slack, GitHub and Figma, two clicks each.' },
          { id: 'permissions', title: 'Granular permissions', copy: 'SSO, roles and space-level locks.' },
        ];

        export default function FeatureGrid() {
          return (
            <>
              <style>{`
                .scg { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr));
                       padding: 3rem 1.5rem; background: #fff; color: #1C1917;
                       font: 400 .9375rem/1.5 'Inter', sans-serif; }
                .scg-cell { display: grid; gap: .6rem; align-content: start; padding: 1.5rem;
                            border: 1px solid #E7E5E4; border-radius: 1.25rem; transition: border-color .25s ease; }
                .scg-cell:hover { border-color: #0F766E; }
                .scg-cell[data-span='2'] { grid-column: span 2; }
                .scg-index { font: 700 .7rem/1 'JetBrains Mono', monospace; color: #A8A29E; }
                .scg-cell h3 { margin: 0; font-size: 1rem; font-weight: 650; letter-spacing: -.01em; }
                .scg-cell p { margin: 0; font-size: .84rem; color: #78716C; line-height: 1.6; }
                .scg-icon { inline-size: 1.75rem; block-size: 1.75rem; stroke-width: 2; stroke: #78716C;
                            fill: none; stroke-linecap: round; stroke-linejoin: round; transition: stroke .25s ease; }
                .scg-cell:hover .scg-icon { stroke: #0F766E; }
                @media (max-width: 640px) { .scg { grid-template-columns: 1fr; } .scg-cell[data-span='2'] { grid-column: auto; } }
              `}</style>
              <section className="scg">
                {FEATURES.map((f, i) => (
                  // data-span carries the bento width; everything else is CSS.
                  <article className="scg-cell" data-span={f.span ? String(f.span) : undefined} key={f.id}>
                    <span className="scg-index">{String(i + 1).padStart(2, '0')}</span>
                    <svg className="scg-icon" viewBox="0 0 24 24" aria-hidden="true">
                      {f.id === 'search' && <><circle cx="11" cy="11" r="7" /><path d="M16.5 16.5L21 21" /></>}
                      {f.id === 'live' && <><circle cx="9" cy="8" r="3.2" /><path d="M3.5 19c.8-3 3-4.5 5.5-4.5s4.7 1.5 5.5 4.5" /><path d="M16 5.5a3 3 0 0 1 0 5" /></>}
                      {f.id === 'history' && <><path d="M4 7v5h5" /><path d="M4.5 12a8 8 0 1 0 2-5.3" /><path d="M12 8v4.5l3 1.8" /></>}
                      {f.id === 'edge' && <><circle cx="12" cy="12" r="8.5" /><path d="M3.5 12h17M12 3.5c2.6 2.4 4 5.3 4 8.5s-1.4 6.1-4 8.5c-2.6-2.4-4-5.3-4-8.5s1.4-6.1 4-8.5z" /></>}
                      {f.id === 'integrations' && <><rect x="3.5" y="3.5" width="7" height="7" rx="1.5" /><rect x="13.5" y="13.5" width="7" height="7" rx="1.5" /><path d="M10.5 7h6a3 3 0 0 1 3 3v3.5" /><path d="M7 10.5V14a3 3 0 0 0 3 3h4" /></>}
                      {f.id === 'permissions' && <><rect x="5" y="10.5" width="14" height="9.5" rx="2" /><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5" /><circle cx="12" cy="15.2" r="1.3" /></>}
                    </svg>
                    <h3>{f.title}</h3>
                    <p>{f.copy}</p>
                  </article>
                ))}
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- FeatureGrid.vue — minimal bento grid; wide cells span two columns -->
        <script setup lang="ts">
        import '@nabuxai/ui-core/css';

        const features = [
          { id: 'search', title: 'Semantic search', copy: 'Finds the answer, not just the keyword.', span: 2 },
          { id: 'live', title: 'Live co-editing', copy: 'Cursors carry your teammates’ names.' },
          { id: 'history', title: 'Version history', copy: 'Every page, every change, forever.' },
          { id: 'edge', title: 'Edge-fast everywhere', copy: 'Pages served from 34 regions — Frankfurt to Singapore.', span: 2 },
          { id: 'integrations', title: 'Integrations', copy: 'Slack, GitHub and Figma, two clicks each.' },
          { id: 'permissions', title: 'Granular permissions', copy: 'SSO, roles and space-level locks.' },
        ];
        </script>

        <template>
          <section class="scg">
            <!-- data-span carries the bento width; everything else is CSS. -->
            <article v-for="(f, i) in features" :key="f.id" class="scg-cell" :data-span="f.span ? String(f.span) : undefined">
              <span class="scg-index">{{ String(i + 1).padStart(2, '0') }}</span>
              <svg class="scg-icon" viewBox="0 0 24 24" aria-hidden="true">
                <template v-if="f.id === 'search'"><circle cx="11" cy="11" r="7" /><path d="M16.5 16.5L21 21" /></template>
                <template v-else-if="f.id === 'live'"><circle cx="9" cy="8" r="3.2" /><path d="M3.5 19c.8-3 3-4.5 5.5-4.5s4.7 1.5 5.5 4.5" /><path d="M16 5.5a3 3 0 0 1 0 5" /></template>
                <template v-else-if="f.id === 'history'"><path d="M4 7v5h5" /><path d="M4.5 12a8 8 0 1 0 2-5.3" /><path d="M12 8v4.5l3 1.8" /></template>
                <template v-else-if="f.id === 'edge'"><circle cx="12" cy="12" r="8.5" /><path d="M3.5 12h17" /><path d="M12 3.5c2.6 2.4 4 5.3 4 8.5s-1.4 6.1-4 8.5c-2.6-2.4-4-5.3-4-8.5s1.4-6.1 4-8.5z" /></template>
                <template v-else-if="f.id === 'integrations'"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5" /><rect x="13.5" y="13.5" width="7" height="7" rx="1.5" /><path d="M10.5 7h6a3 3 0 0 1 3 3v3.5" /><path d="M7 10.5V14a3 3 0 0 0 3 3h4" /></template>
                <template v-else><rect x="5" y="10.5" width="14" height="9.5" rx="2" /><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5" /><circle cx="12" cy="15.2" r="1.3" /></template>
              </svg>
              <h3>{{ f.title }}</h3>
              <p>{{ f.copy }}</p>
            </article>
          </section>
        </template>

        <style scoped>
        .scg { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr));
               padding: 3rem 1.5rem; background: #fff; color: #1C1917;
               font: 400 .9375rem/1.5 'Inter', sans-serif; }
        .scg-cell { display: grid; gap: .6rem; align-content: start; padding: 1.5rem;
                    border: 1px solid #E7E5E4; border-radius: 1.25rem; transition: border-color .25s ease; }
        .scg-cell:hover { border-color: #0F766E; }
        .scg-cell[data-span='2'] { grid-column: span 2; }
        .scg-index { font: 700 .7rem/1 'JetBrains Mono', monospace; color: #A8A29E; }
        .scg-cell h3 { margin: 0; font-size: 1rem; font-weight: 650; letter-spacing: -.01em; }
        .scg-cell p { margin: 0; font-size: .84rem; color: #78716C; line-height: 1.6; }
        .scg-icon { inline-size: 1.75rem; block-size: 1.75rem; stroke-width: 2; stroke: #78716C;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; transition: stroke .25s ease; }
        .scg-cell:hover .scg-icon { stroke: #0F766E; }
        @media (max-width: 640px) { .scg { grid-template-columns: 1fr; } .scg-cell[data-span='2'] { grid-column: auto; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- FeatureGrid.svelte — minimal bento grid; wide cells span two columns -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const features = [
            { id: 'search', title: 'Semantic search', copy: 'Finds the answer, not just the keyword.', span: 2 },
            { id: 'live', title: 'Live co-editing', copy: 'Cursors carry your teammates’ names.' },
            { id: 'history', title: 'Version history', copy: 'Every page, every change, forever.' },
            { id: 'edge', title: 'Edge-fast everywhere', copy: 'Pages served from 34 regions — Frankfurt to Singapore.', span: 2 },
            { id: 'integrations', title: 'Integrations', copy: 'Slack, GitHub and Figma, two clicks each.' },
            { id: 'permissions', title: 'Granular permissions', copy: 'SSO, roles and space-level locks.' },
          ];
        </script>

        <section class="scg">
          <!-- data-span carries the bento width; everything else is CSS. -->
          {#each features as f, i (f.id)}
            <article class="scg-cell" data-span={f.span ? String(f.span) : undefined}>
              <span class="scg-index">{String(i + 1).padStart(2, '0')}</span>
              <svg class="scg-icon" viewBox="0 0 24 24" aria-hidden="true">
                {#if f.id === 'search'}<circle cx="11" cy="11" r="7" /><path d="M16.5 16.5L21 21" />
                {:else if f.id === 'live'}<circle cx="9" cy="8" r="3.2" /><path d="M3.5 19c.8-3 3-4.5 5.5-4.5s4.7 1.5 5.5 4.5" /><path d="M16 5.5a3 3 0 0 1 0 5" />
                {:else if f.id === 'history'}<path d="M4 7v5h5" /><path d="M4.5 12a8 8 0 1 0 2-5.3" /><path d="M12 8v4.5l3 1.8" />
                {:else if f.id === 'edge'}<circle cx="12" cy="12" r="8.5" /><path d="M3.5 12h17" /><path d="M12 3.5c2.6 2.4 4 5.3 4 8.5s-1.4 6.1-4 8.5c-2.6-2.4-4-5.3-4-8.5s1.4-6.1 4-8.5z" />
                {:else if f.id === 'integrations'}<rect x="3.5" y="3.5" width="7" height="7" rx="1.5" /><rect x="13.5" y="13.5" width="7" height="7" rx="1.5" /><path d="M10.5 7h6a3 3 0 0 1 3 3v3.5" /><path d="M7 10.5V14a3 3 0 0 0 3 3h4" />
                {:else}<rect x="5" y="10.5" width="14" height="9.5" rx="2" /><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5" /><circle cx="12" cy="15.2" r="1.3" />
                {/if}
              </svg>
              <h3>{f.title}</h3>
              <p>{f.copy}</p>
            </article>
          {/each}
        </section>

        <style>
        .scg { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr));
               padding: 3rem 1.5rem; background: #fff; color: #1C1917;
               font: 400 .9375rem/1.5 'Inter', sans-serif; }
        .scg-cell { display: grid; gap: .6rem; align-content: start; padding: 1.5rem;
                    border: 1px solid #E7E5E4; border-radius: 1.25rem; transition: border-color .25s ease; }
        .scg-cell:hover { border-color: #0F766E; }
        .scg-cell[data-span='2'] { grid-column: span 2; }
        .scg-index { font: 700 .7rem/1 'JetBrains Mono', monospace; color: #A8A29E; }
        .scg-cell h3 { margin: 0; font-size: 1rem; font-weight: 650; letter-spacing: -.01em; }
        .scg-cell p { margin: 0; font-size: .84rem; color: #78716C; line-height: 1.6; }
        .scg-icon { inline-size: 1.75rem; block-size: 1.75rem; stroke-width: 2; stroke: #78716C;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; transition: stroke .25s ease; }
        .scg-cell:hover .scg-icon { stroke: #0F766E; }
        @media (max-width: 640px) { .scg { grid-template-columns: 1fr; } .scg-cell[data-span='2'] { grid-column: auto; } }
        </style>
        SVELTE,
        ],
    ],

    'testimonials' => [
        'title' => ['fa' => 'بخش نقل‌قول‌ها', 'en' => 'Testimonials'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'کاروسل نقل‌قول درشت با آواتار و شرکت و نام، زیرش گرید نقل‌قول‌های کوچک ستاره‌دار؛ ناوبری هم با دکمه، هم با نقطه‌ها و هم با کلیدهای جهت — همه روی پالت گرم کِرِم و کهربایی که اعتماد می‌سازد.',
            'en' => 'A big-quote carousel with avatars, companies and names, over a grid of small star-rated quotes; navigate by button, by dots or with the arrow keys — all on a warm cream-and-amber palette that builds trust.',
        ],
        'js' => true,
        'docs' => 'https://shadcnblocks.com',
        'props' => [
            ['name' => 'index', 'type' => 'number', 'default' => "'0'", 'note' => [
                'fa' => 'اسلاید فعال؛ اسلایدها در یک ناحیهٔ گرید روی هم می‌نشینند و با محو + سُرِشظریف جابه‌جا می‌شوند، پس ارتفاع هرگز نمی‌پرد.',
                'en' => 'The active slide; slides stack in one grid area and swap with a soft fade-and-slide, so the height never jumps.',
            ]],
            ['name' => 'controls', 'type' => "'buttons · dots · keys'", 'default' => "'all three'", 'note' => [
                'fa' => 'دکمه‌های قبلی/بعدی، نقطه‌های کلیک‌پذیر و کلیدهای جهت صفحه — کاروسل با هر سه راه جابه‌جا می‌شود.',
                'en' => 'Prev/next buttons, clickable dots and the keyboard arrows — the carousel moves all three ways.',
            ]],
            ['name' => 'rating', 'type' => '0–5', 'default' => "'5'", 'note' => [
                'fa' => 'کارت‌های کوچک ستاره دارند؛ ستاره‌های SVG با پرشدگی میزان امتیاز را می‌گویند.',
                'en' => 'The small cards carry stars; filled SVG stars spell the score out.',
            ]],
            ['name' => '--sectm-accent', 'type' => 'color', 'default' => "'#B45309'", 'note' => [
                'fa' => 'کهربای گرم بخش؛ ستاره‌ها و نقل‌قول بزرگ از آن رنگ می‌گیرند و در تم تیره به طلایی روشن می‌رسند.',
                'en' => 'The section’s warm amber; stars and the big quote take it, brightening to gold in dark mode.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="sectm" x-data="{ i: 0, items: [ 'نقل‌قول ۱', 'نقل‌قول ۲', 'نقل‌قول ۳' ] }"
                 x-on:keydown.window.arrow-right.prevent="i = (i + 1) % 3"
                 x-on:keydown.window.arrow-left.prevent="i = (i - 1 + 3) % 3">
            <!-- اسلایدها در یک ناحیهٔ گرید روی هم می‌نشینند -->
            <div class="sectm-stage">
                <blockquote class="sectm-quote" x-show="i === 0" x-transition:enter.duration.400ms>
                    <p>«هفته‌ای که بیکِن را آوردیم، سه ابزار را بازنشسته کردیم.»</p>
                    <footer><span class="sectm-avatar">AL</span><b>املی لوران</b><small>نورت‌ویند · اسلو</small></footer>
                </blockquote>
            </div>
            <div class="sectm-nav">
                <button type="button" x-on:click="i = (i - 1 + 3) % 3" aria-label="قبلی">→</button>
                <button type="button" class="sectm-dot" :class="i === 0 && 'sectm-on'" x-on:click="i = 0" aria-label="نقل‌قال ۱"></button>
                <button type="button" x-on:click="i = (i + 1) % 3" aria-label="بعدی">←</button>
            </div>
        </section>

        <style>
        .sectm { padding: 3rem 1.5rem; background: #FBF7F0; color: #292524; font-family: 'Vazirmatn', sans-serif; }
        .sectm-stage { display: grid; } .sectm-quote { grid-area: 1 / 1; margin: 0; }
        .sectm-quote p { margin: 0 0 1.25rem; font-size: 1.375rem; font-weight: 700; line-height: 1.75; }
        .sectm-avatar { display: inline-grid; place-items: center; inline-size: 2.75rem; aspect-ratio: 1;
                        border-radius: 999px; background: #B45309; color: #fff; font-weight: 700; }
        .sectm-dot { inline-size: .5rem; aspect-ratio: 1; border-radius: 999px; border: none;
                     background: #E7D8C3; cursor: pointer; }
        .sectm-dot.sectm-on { background: #B45309; inline-size: 1.25rem; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="sectm" x-data="{ i: 0 }"
                 x-on:keydown.window.arrow-right.prevent="i = (i + 1) % 3"
                 x-on:keydown.window.arrow-left.prevent="i = (i - 1 + 3) % 3">
            <!-- slides stack in one grid area, so the height never jumps -->
            <div class="sectm-stage">
                <blockquote class="sectm-quote" x-show="i === 0" x-transition:enter.duration.400ms>
                    <p>“The week we adopted Beacon we retired three tools.”</p>
                    <footer><span class="sectm-avatar">AL</span><b>Amelie Laurent</b><small>Northwind · Oslo</small></footer>
                </blockquote>
            </div>
            <div class="sectm-nav">
                <button type="button" x-on:click="i = (i - 1 + 3) % 3" aria-label="Previous">←</button>
                <button type="button" class="sectm-dot" :class="i === 0 && 'sectm-on'" x-on:click="i = 0" aria-label="Quote 1"></button>
                <button type="button" x-on:click="i = (i + 1) % 3" aria-label="Next">→</button>
            </div>
        </section>

        <style>
        .sectm { padding: 3rem 1.5rem; background: #FBF7F0; color: #292524; font-family: 'Inter', sans-serif; }
        .sectm-stage { display: grid; } .sectm-quote { grid-area: 1 / 1; margin: 0; }
        .sectm-quote p { margin: 0 0 1.25rem; font-size: 1.375rem; font-weight: 650; line-height: 1.65; }
        .sectm-avatar { display: inline-grid; place-items: center; inline-size: 2.75rem; aspect-ratio: 1;
                        border-radius: 999px; background: #B45309; color: #fff; font-weight: 700; }
        .sectm-dot { inline-size: .5rem; aspect-ratio: 1; border-radius: 999px; border: none;
                     background: #E7D8C3; cursor: pointer; }
        .sectm-dot.sectm-on { background: #B45309; inline-size: 1.25rem; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Love.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import Testimonials from '@/components/Testimonials';

        export default function Love() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <Testimonials />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // Testimonials.jsx — big-quote carousel + star grid, keys included
        import { useEffect, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const QUOTES = [
          { id: 'northwind', text: 'The week we adopted Beacon we retired three tools. The billing page nobody dared to touch finally has an owner.',
            name: 'Amelie Laurent', role: 'VP Engineering · Northwind, Oslo', initials: 'AL' },
          { id: 'helios', text: 'The audit log paid for itself in one incident — we traced a broken deploy to a single env var in four minutes.',
            name: 'Diego Ferreira', role: 'SRE Lead · Helios Labs, Lisbon', initials: 'DF' },
          { id: 'quantia', text: 'Onboarding new engineers dropped from two weeks to three days. They just read the runbooks.',
            name: 'Mei Tanaka', role: 'CTO · Quantia, Singapore', initials: 'MT' },
        ];
        const SHORTS = [
          { id: 's1', text: 'Support answers in minutes, not days.', name: 'Priya Sharma', co: 'Fabrik, Toronto', stars: 5 },
          { id: 's2', text: 'The changelog writes itself now.', name: 'Jonas Weber', co: 'Kanso, Berlin', stars: 5 },
          { id: 's3', text: 'A dashboard the whole team actually opens.', name: 'Sofia Rossi', co: 'Vantage, Milan', stars: 4 },
        ];

        export default function Testimonials() {
          const [i, setI] = useState(0);
          const step = (d) => setI((v) => (v + d + QUOTES.length) % QUOTES.length);

          // Arrow keys drive the carousel from anywhere on the page.
          useEffect(() => {
            const onKey = (e) => {
              if (e.key === 'ArrowRight') step(1);
              if (e.key === 'ArrowLeft') step(-1);
            };
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          }, []);

          return (
            <>
              <style>{`
                .sct { display: grid; gap: 1.5rem; padding: 3rem 1.5rem; background: #FBF7F0; color: #292524;
                       font: 400 .9375rem/1.5 'Inter', sans-serif; }
                .sct-stage { display: grid; max-inline-size: 44rem; margin-inline: auto; }
                .sct-quote { grid-area: 1 / 1; margin: 0; display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
                .sct-quote p { margin: 0; font: 650 clamp(1.15rem, 2.6vw, 1.6rem)/1.6 'Inter', sans-serif; letter-spacing: -.01em; }
                .sct-quote footer { display: inline-flex; align-items: center; gap: .75rem; }
                .sct-avatar { display: inline-grid; place-items: center; inline-size: 2.75rem; aspect-ratio: 1;
                              border-radius: 999px; background: #B45309; color: #fff; font-weight: 700; }
                .sct-on { opacity: 1; } .sct-off { opacity: 0; pointer-events: none; }
                .sct-nav { display: flex; align-items: center; justify-content: center; gap: .75rem; }
                .sct-nav button { border: none; cursor: pointer; background: transparent; }
                .sct-arrow { inline-size: 2.25rem; aspect-ratio: 1; border-radius: 999px; background: #F3E8D7 !important; }
                .sct-dot { inline-size: .5rem; aspect-ratio: 1; border-radius: 999px; background: #E7D8C3 !important; }
                .sct-dot[data-on='true'] { background: #B45309 !important; inline-size: 1.25rem; }
                .sct-shorts { display: grid; gap: 1rem; grid-template-columns: repeat(3, 1fr); max-inline-size: 62rem; margin-inline: auto; }
                .sct-short { padding: 1.25rem; border: 1px solid #EADFCB; border-radius: 1.1rem; background: #FFFCF6; }
                .sct-stars { color: #F59E0B; letter-spacing: .15em; }
                @media (max-width: 640px) { .sct-shorts { grid-template-columns: 1fr; } }
              `}</style>
              <section className="sct">
                <div className="sct-stage">
                  {QUOTES.map((q, n) => (
                    <blockquote className={`sct-quote ${n === i ? 'sct-on' : 'sct-off'}`} key={q.id} aria-hidden={n !== i}>
                      <p>“{q.text}”</p>
                      <footer>
                        <span className="sct-avatar" aria-hidden="true">{q.initials}</span>
                        <span><b>{q.name}</b><br /><small>{q.role}</small></span>
                      </footer>
                    </blockquote>
                  ))}
                </div>
                <div className="sct-nav">
                  <button type="button" className="sct-arrow" aria-label="Previous quote" onClick={() => step(-1)}>←</button>
                  {QUOTES.map((q, n) => (
                    <button type="button" className="sct-dot" data-on={n === i} key={q.id}
                      aria-label={`Quote ${n + 1}`} onClick={() => setI(n)} />
                  ))}
                  <button type="button" className="sct-arrow" aria-label="Next quote" onClick={() => step(1)}>→</button>
                </div>
                <div className="sct-shorts">
                  {SHORTS.map((s) => (
                    <figure className="sct-short" key={s.id}>
                      <div className="sct-stars" aria-label={`${s.stars} out of 5 stars`}>
                        {'★'.repeat(s.stars)}{'☆'.repeat(5 - s.stars)}
                      </div>
                      <blockquote>{s.text}</blockquote>
                      <figcaption><b>{s.name}</b> · {s.co}</figcaption>
                    </figure>
                  ))}
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- Testimonials.vue — big-quote carousel + star grid, keys included -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const quotes = [
          { id: 'northwind', text: 'The week we adopted Beacon we retired three tools. The billing page nobody dared to touch finally has an owner.',
            name: 'Amelie Laurent', role: 'VP Engineering · Northwind, Oslo', initials: 'AL' },
          { id: 'helios', text: 'The audit log paid for itself in one incident — we traced a broken deploy to a single env var in four minutes.',
            name: 'Diego Ferreira', role: 'SRE Lead · Helios Labs, Lisbon', initials: 'DF' },
          { id: 'quantia', text: 'Onboarding new engineers dropped from two weeks to three days. They just read the runbooks.',
            name: 'Mei Tanaka', role: 'CTO · Quantia, Singapore', initials: 'MT' },
        ];
        const shorts = [
          { id: 's1', text: 'Support answers in minutes, not days.', name: 'Priya Sharma', co: 'Fabrik, Toronto', stars: 5 },
          { id: 's2', text: 'The changelog writes itself now.', name: 'Jonas Weber', co: 'Kanso, Berlin', stars: 5 },
          { id: 's3', text: 'A dashboard the whole team actually opens.', name: 'Sofia Rossi', co: 'Vantage, Milan', stars: 4 },
        ];
        const i = ref(0);
        const step = (d: number) => { i.value = (i.value + d + quotes.length) % quotes.length; };
        const onKey = (e: KeyboardEvent) => {
          if (e.key === 'ArrowRight') step(1);
          if (e.key === 'ArrowLeft') step(-1);
        };
        onMounted(() => window.addEventListener('keydown', onKey));
        onBeforeUnmount(() => window.removeEventListener('keydown', onKey));
        </script>

        <template>
          <section class="sct">
            <div class="sct-stage">
              <!-- slides stack in one grid area, so the height never jumps -->
              <blockquote v-for="(q, n) in quotes" :key="q.id" class="sct-quote" :class="n === i ? 'sct-on' : 'sct-off'" :aria-hidden="n !== i">
                <p>“{{ q.text }}”</p>
                <footer>
                  <span class="sct-avatar" aria-hidden="true">{{ q.initials }}</span>
                  <span><b>{{ q.name }}</b><br /><small>{{ q.role }}</small></span>
                </footer>
              </blockquote>
            </div>
            <div class="sct-nav">
              <button type="button" class="sct-arrow" aria-label="Previous quote" @click="step(-1)">←</button>
              <button v-for="(q, n) in quotes" :key="q.id" type="button" class="sct-dot" :data-on="n === i"
                :aria-label="`Quote ${n + 1}`" @click="i = n" />
              <button type="button" class="sct-arrow" aria-label="Next quote" @click="step(1)">→</button>
            </div>
            <div class="sct-shorts">
              <figure v-for="s in shorts" :key="s.id" class="sct-short">
                <div class="sct-stars" :aria-label="`${s.stars} out of 5 stars`">★★★★★</div>
                <blockquote>{{ s.text }}</blockquote>
                <figcaption><b>{{ s.name }}</b> · {{ s.co }}</figcaption>
              </figure>
            </div>
          </section>
        </template>

        <style scoped>
        .sct { display: grid; gap: 1.5rem; padding: 3rem 1.5rem; background: #FBF7F0; color: #292524;
               font: 400 .9375rem/1.5 'Inter', sans-serif; }
        .sct-stage { display: grid; max-inline-size: 44rem; margin-inline: auto; }
        .sct-quote { grid-area: 1 / 1; margin: 0; display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .sct-quote p { margin: 0; font: 650 clamp(1.15rem, 2.6vw, 1.6rem)/1.6 'Inter', sans-serif; letter-spacing: -.01em; }
        .sct-quote footer { display: inline-flex; align-items: center; gap: .75rem; }
        .sct-avatar { display: inline-grid; place-items: center; inline-size: 2.75rem; aspect-ratio: 1;
                      border-radius: 999px; background: #B45309; color: #fff; font-weight: 700; }
        .sct-on { opacity: 1; } .sct-off { opacity: 0; pointer-events: none; }
        .sct-nav { display: flex; align-items: center; justify-content: center; gap: .75rem; }
        .sct-nav button { border: none; cursor: pointer; background: transparent; }
        .sct-arrow { inline-size: 2.25rem; aspect-ratio: 1; border-radius: 999px; background: #F3E8D7; }
        .sct-dot { inline-size: .5rem; aspect-ratio: 1; border-radius: 999px; background: #E7D8C3; }
        .sct-dot[data-on='true'] { background: #B45309; inline-size: 1.25rem; }
        .sct-shorts { display: grid; gap: 1rem; grid-template-columns: repeat(3, 1fr); max-inline-size: 62rem; margin-inline: auto; }
        .sct-short { margin: 0; padding: 1.25rem; border: 1px solid #EADFCB; border-radius: 1.1rem; background: #FFFCF6; }
        .sct-short blockquote { margin: .5rem 0; }
        .sct-stars { color: #F59E0B; letter-spacing: .15em; }
        @media (max-width: 640px) { .sct-shorts { grid-template-columns: 1fr; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- Testimonials.svelte — big-quote carousel + star grid, keys included -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const quotes = [
            { id: 'northwind', text: 'The week we adopted Beacon we retired three tools. The billing page nobody dared to touch finally has an owner.',
              name: 'Amelie Laurent', role: 'VP Engineering · Northwind, Oslo', initials: 'AL' },
            { id: 'helios', text: 'The audit log paid for itself in one incident — we traced a broken deploy to a single env var in four minutes.',
              name: 'Diego Ferreira', role: 'SRE Lead · Helios Labs, Lisbon', initials: 'DF' },
            { id: 'quantia', text: 'Onboarding new engineers dropped from two weeks to three days. They just read the runbooks.',
              name: 'Mei Tanaka', role: 'CTO · Quantia, Singapore', initials: 'MT' },
          ];
          const shorts = [
            { id: 's1', text: 'Support answers in minutes, not days.', name: 'Priya Sharma', co: 'Fabrik, Toronto', stars: 5 },
            { id: 's2', text: 'The changelog writes itself now.', name: 'Jonas Weber', co: 'Kanso, Berlin', stars: 5 },
            { id: 's3', text: 'A dashboard the whole team actually opens.', name: 'Sofia Rossi', co: 'Vantage, Milan', stars: 4 },
          ];
          let i = $state(0);
          const step = (d: number) => { i = (i + d + quotes.length) % quotes.length; };

          // Arrow keys drive the carousel from anywhere on the page.
          onMount(() => {
            const onKey = (e: KeyboardEvent) => {
              if (e.key === 'ArrowRight') step(1);
              if (e.key === 'ArrowLeft') step(-1);
            };
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          });
        </script>

        <section class="sct">
          <div class="sct-stage">
            <!-- slides stack in one grid area, so the height never jumps -->
            {#each quotes as q, n (q.id)}
              <blockquote class="sct-quote" class:on={n === i} class:off={n !== i} aria-hidden={n !== i}>
                <p>“{q.text}”</p>
                <footer>
                  <span class="sct-avatar" aria-hidden="true">{q.initials}</span>
                  <span><b>{q.name}</b><br /><small>{q.role}</small></span>
                </footer>
              </blockquote>
            {/each}
          </div>
          <div class="sct-nav">
            <button type="button" class="sct-arrow" aria-label="Previous quote" onclick={() => step(-1)}>←</button>
            {#each quotes as q, n (q.id)}
              <button type="button" class="sct-dot" class:on={n === i} aria-label="Quote {n + 1}" onclick={() => (i = n)} />
            {/each}
            <button type="button" class="sct-arrow" aria-label="Next quote" onclick={() => step(1)}>→</button>
          </div>
          <div class="sct-shorts">
            {#each shorts as s (s.id)}
              <figure class="sct-short">
                <div class="sct-stars" aria-label="{s.stars} out of 5 stars">★★★★★</div>
                <blockquote>{s.text}</blockquote>
                <figcaption><b>{s.name}</b> · {s.co}</figcaption>
              </figure>
            {/each}
          </div>
        </section>

        <style>
        .sct { display: grid; gap: 1.5rem; padding: 3rem 1.5rem; background: #FBF7F0; color: #292524;
               font: 400 .9375rem/1.5 'Inter', sans-serif; }
        .sct-stage { display: grid; max-inline-size: 44rem; margin-inline: auto; }
        .sct-quote { grid-area: 1 / 1; margin: 0; display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .sct-quote p { margin: 0; font: 650 clamp(1.15rem, 2.6vw, 1.6rem)/1.6 'Inter', sans-serif; letter-spacing: -.01em; }
        .sct-quote footer { display: inline-flex; align-items: center; gap: .75rem; }
        .sct-avatar { display: inline-grid; place-items: center; inline-size: 2.75rem; aspect-ratio: 1;
                      border-radius: 999px; background: #B45309; color: #fff; font-weight: 700; }
        .sct-quote.on { opacity: 1; } .sct-quote.off { opacity: 0; pointer-events: none; }
        .sct-nav { display: flex; align-items: center; justify-content: center; gap: .75rem; }
        .sct-nav button { border: none; cursor: pointer; background: transparent; }
        .sct-arrow { inline-size: 2.25rem; aspect-ratio: 1; border-radius: 999px; background: #F3E8D7; }
        .sct-dot { inline-size: .5rem; aspect-ratio: 1; border-radius: 999px; background: #E7D8C3; }
        .sct-dot.on { background: #B45309; inline-size: 1.25rem; }
        .sct-shorts { display: grid; gap: 1rem; grid-template-columns: repeat(3, 1fr); max-inline-size: 62rem; margin-inline: auto; }
        .sct-short { margin: 0; padding: 1.25rem; border: 1px solid #EADFCB; border-radius: 1.1rem; background: #FFFCF6; }
        .sct-short blockquote { margin: .5rem 0; }
        .sct-stars { color: #F59E0B; letter-spacing: .15em; }
        @media (max-width: 640px) { .sct-shorts { grid-template-columns: 1fr; } }
        </style>
        SVELTE,
        ],
    ],

    'logos-section' => [
        'title' => ['fa' => 'نوار لوگوها', 'en' => 'Logos section'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'بخش اعتماد: گرید لوگوهای خاکستری که با هاور رنگ برندشان را پس می‌گیرند و زیرشان آمار ریز اعتماد می‌نشیند؛ با یک سوییچ، همان لوگوها به نوار اسکرول آرام و بی‌درز تبدیل می‌شوند که با هاور می‌ایستد.',
            'en' => 'The trust section: a grid of grayscale logos that reclaim their brand colours on hover with fine trust stats beneath; flip one switch and the same logos become a seamless slow marquee that pauses on hover.',
        ],
        'js' => true,
        'docs' => 'https://shadcnblocks.com',
        'props' => [
            ['name' => 'mode', 'type' => "'grid' | 'scroll'", 'default' => "'grid'", 'note' => [
                'fa' => 'گرید ساکن یا نوار متحرک؛ سوییچ بالا همان هشت لوگو را بی‌هیچ پرش_divی جابه‌جا می‌کند.',
                'en' => 'Static grid or moving band; the switch above swaps the same eight logos without a single jump.',
            ]],
            ['name' => 'grayscale', 'type' => 'filter', 'default' => "'1 → 0'", 'note' => [
                'fa' => 'لوگوها با grayscale کامل و ۶۰٪ شفافیت می‌خوابند و با هاور در ۳۰۰ms رنگ و طرح کامل را برمی‌گردانند.',
                'en' => 'Logos rest at full grayscale and 60% opacity, returning to full colour in 300ms on hover.',
            ]],
            ['name' => 'speed', 'type' => 'duration', 'default' => "'32s'", 'note' => [
                'fa' => 'سرعت نوار متحرک؛ مسیر دوبار پشت‌سرهم چیده شده تا حلقه بی‌درز باشد و هاور آن را نگه می‌دارد.',
                'en' => 'The marquee speed; the track is laid out twice for a seamless loop and hovering holds it in place.',
            ]],
            ['name' => 'stats', 'type' => 'array', 'default' => "'4'", 'note' => [
                'fa' => 'چهار آمار ریز زیر لوگوها — تیم‌ها، کشورها، آپتایم و امتیاز — با اعداد فارسی در حالت فارسی.',
                'en' => 'Four fine stats under the logos — teams, countries, uptime and score — Persian digits in Persian.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="seclg" x-data="{ scroll: false }">
            <div class="seclg-switch" role="group" aria-label="حالت نمایش لوگوها">
                <button type="button" :class="!scroll && 'seclg-on'" x-on:click="scroll = false">گرید</button>
                <button type="button" :class="scroll && 'seclg-on'" x-on:click="scroll = true">اسکرول آرام</button>
            </div>

            <!-- گرید ساکن: لوگوهای خاکستری که با هاور رنگ می‌گیرند -->
            <ul class="seclg-grid" x-show="!scroll">
                <li class="seclg-logo" style="--seclg-brand: #0EA5E9"><svg>…</svg>لومینا</li>
                <li class="seclg-logo" style="--seclg-brand: #8B5CF6"><svg>…</svg>اوربیتال</li>
            </ul>

            <!-- نوار متحرک: مسیر دوبارچیده برای حلقهٔ بی‌درز -->
            <div class="seclg-marquee" x-show="scroll" x-cloak dir="ltr">
                <ul class="seclg-track">
                    <li class="seclg-logo">…لومینا…</li><li class="seclg-logo">…اوربیتال…</li>
                    <li class="seclg-logo" aria-hidden="true">…لومینا…</li><li class="seclg-logo" aria-hidden="true">…اوربیتال…</li>
                </ul>
            </div>
        </section>

        <style>
        .seclg { display: grid; gap: 1.5rem; justify-items: center; padding: 2.5rem 1.5rem; }
        .seclg-logo { display: inline-flex; align-items: center; gap: .5rem; color: var(--seclg-brand);
                      filter: grayscale(1); opacity: .6; transition: filter .3s ease, opacity .3s ease; }
        .seclg-logo:hover { filter: grayscale(0); opacity: 1; }
        .seclg-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem 2.5rem; margin: 0; padding: 0; list-style: none; }
        .seclg-marquee { inline-size: 100%; overflow: clip; }
        .seclg-track { display: flex; gap: 3rem; inline-size: max-content; margin: 0; padding: 0; list-style: none;
                       animation: seclg-run 32s linear infinite; }
        .seclg-marquee:hover .seclg-track { animation-play-state: paused; }
        @keyframes seclg-run { to { transform: translateX(-50%); } }
        @media (prefers-reduced-motion: reduce) { .seclg-track { animation: none; flex-wrap: wrap; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="seclg" x-data="{ scroll: false }">
            <div class="seclg-switch" role="group" aria-label="Logo display mode">
                <button type="button" :class="!scroll && 'seclg-on'" x-on:click="scroll = false">Grid</button>
                <button type="button" :class="scroll && 'seclg-on'" x-on:click="scroll = true">Slow scroll</button>
            </div>

            <!-- the static grid: grayscale logos that colour on hover -->
            <ul class="seclg-grid" x-show="!scroll">
                <li class="seclg-logo" style="--seclg-brand: #0EA5E9"><svg>…</svg>Lumina</li>
                <li class="seclg-logo" style="--seclg-brand: #8B5CF6"><svg>…</svg>Orbital</li>
            </ul>

            <!-- the marquee: the track is laid out twice for a seamless loop -->
            <div class="seclg-marquee" x-show="scroll" x-cloak dir="ltr">
                <ul class="seclg-track">
                    <li class="seclg-logo">…Lumina…</li><li class="seclg-logo">…Orbital…</li>
                    <li class="seclg-logo" aria-hidden="true">…Lumina…</li><li class="seclg-logo" aria-hidden="true">…Orbital…</li>
                </ul>
            </div>
        </section>

        <style>
        .seclg { display: grid; gap: 1.5rem; justify-items: center; padding: 2.5rem 1.5rem; }
        .seclg-logo { display: inline-flex; align-items: center; gap: .5rem; color: var(--seclg-brand);
                      filter: grayscale(1); opacity: .6; transition: filter .3s ease, opacity .3s ease; }
        .seclg-logo:hover { filter: grayscale(0); opacity: 1; }
        .seclg-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem 2.5rem; margin: 0; padding: 0; list-style: none; }
        .seclg-marquee { inline-size: 100%; overflow: clip; }
        .seclg-track { display: flex; gap: 3rem; inline-size: max-content; margin: 0; padding: 0; list-style: none;
                       animation: seclg-run 32s linear infinite; }
        .seclg-marquee:hover .seclg-track { animation-play-state: paused; }
        @keyframes seclg-run { to { transform: translateX(-50%); } }
        @media (prefers-reduced-motion: reduce) { .seclg-track { animation: none; flex-wrap: wrap; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Trust.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import LogoBand from '@/components/LogoBand';

        export default function Trust() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <LogoBand />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // LogoBand.jsx — grayscale grid ↔ seamless marquee, one switch
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const LOGOS = [
          { id: 'lumina', name: 'Lumina', color: '#0EA5E9', mark: 'ring' },
          { id: 'orbital', name: 'Orbital', color: '#8B5CF6', mark: 'orbit' },
          { id: 'kanso', name: 'Kanso', color: '#57534E', mark: 'enso' },
          { id: 'vantage', name: 'Vantage', color: '#2563EB', mark: 'peak' },
          { id: 'helios', name: 'Helios', color: '#F59E0B', mark: 'sun' },
          { id: 'northwind', name: 'Northwind', color: '#475569', mark: 'north' },
          { id: 'quantia', name: 'Quantia', color: '#DB2777', mark: 'wave' },
          { id: 'fabrik', name: 'Fabrik', color: '#EA580C', mark: 'grid' },
        ];

        export default function LogoBand() {
          const [scroll, setScroll] = useState(false);
          const logo = (l, ghost = false) => (
            <li className="scl-logo" key={ghost ? `g-${l.id}` : l.id} aria-hidden={ghost || undefined}
                style={{ '--scl-brand': l.color }}>
              <svg viewBox="0 0 24 24" aria-hidden="true">
                {l.mark === 'ring' && <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" strokeWidth="2.4" />}
                {l.mark === 'orbit' && <><circle cx="12" cy="12" r="4" fill="currentColor" /><ellipse cx="12" cy="12" rx="10" ry="4.5" fill="none" stroke="currentColor" strokeWidth="1.8" /></>}
                {l.mark === 'enso' && <path d="M17.5 7A8 8 0 1 0 19 13.5" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" />}
                {l.mark === 'peak' && <path d="M3 19L10 5l4 7 3-4 4 11z" fill="currentColor" />}
                {l.mark === 'sun' && <><circle cx="12" cy="12" r="4.5" fill="currentColor" /><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3" stroke="currentColor" strokeWidth="2" strokeLinecap="round" /></>}
                {l.mark === 'north' && <path d="M12 3l7 18-7-4-7 4z" fill="currentColor" />}
                {l.mark === 'wave' && <path d="M2 14c2.5-6 5-6 7.5 0s5 6 7.5 0 5-6 5-6" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" />}
                {l.mark === 'grid' && <><rect x="3" y="3" width="8" height="8" fill="currentColor" /><rect x="13" y="13" width="8" height="8" fill="currentColor" /></>}
              </svg>
              {l.name}
            </li>
          );

          return (
            <>
              <style>{`
                .scl { display: grid; gap: 1.75rem; justify-items: center; padding: 2.5rem 1.5rem;
                       font: 500 .9375rem/1.5 'Inter', sans-serif; }
                .scl-switch { display: inline-flex; border: 1px solid #E7E5E4; border-radius: 999px; overflow: clip; }
                .scl-switch button { padding: .45rem 1.1rem; border: none; background: transparent; cursor: pointer;
                                     font: 600 .8rem/1 'Inter', sans-serif; color: #78716C; }
                .scl-switch button[aria-pressed='true'] { background: #292524; color: #fff; }
                .scl-logo { display: inline-flex; align-items: center; gap: .5rem; color: var(--scl-brand);
                            filter: grayscale(1); opacity: .6; transition: filter .3s ease, opacity .3s ease;
                            font-weight: 700; letter-spacing: -.01em; white-space: nowrap; }
                .scl-logo:hover { filter: grayscale(0); opacity: 1; }
                .scl-logo svg { inline-size: 1.5rem; block-size: 1.5rem; }
                .scl-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem 2.5rem; margin: 0; padding: 0; list-style: none; }
                .scl-marquee { inline-size: 100%; overflow: clip; }
                .scl-track { display: flex; gap: 3rem; inline-size: max-content; margin: 0; padding: 0; list-style: none;
                             animation: scl-run 32s linear infinite; }
                .scl-marquee:hover .scl-track { animation-play-state: paused; }
                @keyframes scl-run { to { transform: translateX(-50%); } }
                @media (max-width: 560px) { .scl-grid { grid-template-columns: repeat(2, 1fr); } }
                @media (prefers-reduced-motion: reduce) { .scl-track { animation: none; flex-wrap: wrap; } }
              `}</style>
              <section className="scl">
                <div className="scl-switch" role="group" aria-label="Logo display mode">
                  <button type="button" aria-pressed={!scroll} onClick={() => setScroll(false)}>Grid</button>
                  <button type="button" aria-pressed={scroll} onClick={() => setScroll(true)}>Slow scroll</button>
                </div>
                {scroll ? (
                  // the doubled track makes the loop seamless
                  <div className="scl-marquee" dir="ltr">
                    <ul className="scl-track">
                      {LOGOS.map((l) => logo(l))}
                      {LOGOS.map((l) => logo(l, true))}
                    </ul>
                  </div>
                ) : (
                  <ul className="scl-grid">{LOGOS.map((l) => logo(l))}</ul>
                )}
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- LogoBand.vue — grayscale grid ↔ seamless marquee, one switch -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const logos = [
          { id: 'lumina', name: 'Lumina', color: '#0EA5E9', mark: 'ring' },
          { id: 'orbital', name: 'Orbital', color: '#8B5CF6', mark: 'orbit' },
          { id: 'kanso', name: 'Kanso', color: '#57534E', mark: 'enso' },
          { id: 'vantage', name: 'Vantage', color: '#2563EB', mark: 'peak' },
          { id: 'helios', name: 'Helios', color: '#F59E0B', mark: 'sun' },
          { id: 'northwind', name: 'Northwind', color: '#475569', mark: 'north' },
          { id: 'quantia', name: 'Quantia', color: '#DB2777', mark: 'wave' },
          { id: 'fabrik', name: 'Fabrik', color: '#EA580C', mark: 'grid' },
        ];
        const scroll = ref(false);
        // the track carries every logo twice — the ghost copy closes the loop.
        const trackLogos = [...logos, ...logos.map((l) => ({ ...l, ghost: true }))];
        </script>

        <template>
          <section class="scl">
            <div class="scl-switch" role="group" aria-label="Logo display mode">
              <button type="button" :aria-pressed="!scroll" @click="scroll = false">Grid</button>
              <button type="button" :aria-pressed="scroll" @click="scroll = true">Slow scroll</button>
            </div>
            <div v-if="scroll" class="scl-marquee" dir="ltr">
              <ul class="scl-track">
                <li v-for="l in trackLogos" :key="(l.ghost ? 'g-' : '') + l.id" class="scl-logo"
                    :aria-hidden="l.ghost || undefined" :style="{ '--scl-brand': l.color }">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <template v-if="l.mark === 'ring'"><circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="2.4" /></template>
                    <template v-else-if="l.mark === 'orbit'"><circle cx="12" cy="12" r="4" fill="currentColor" /><ellipse cx="12" cy="12" rx="10" ry="4.5" fill="none" stroke="currentColor" stroke-width="1.8" /></template>
                    <template v-else-if="l.mark === 'enso'"><path d="M17.5 7A8 8 0 1 0 19 13.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" /></template>
                    <template v-else-if="l.mark === 'peak'"><path d="M3 19L10 5l4 7 3-4 4 11z" fill="currentColor" /></template>
                    <template v-else-if="l.mark === 'sun'"><circle cx="12" cy="12" r="4.5" fill="currentColor" /><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></template>
                    <template v-else-if="l.mark === 'north'"><path d="M12 3l7 18-7-4-7 4z" fill="currentColor" /></template>
                    <template v-else-if="l.mark === 'wave'"><path d="M2 14c2.5-6 5-6 7.5 0s5 6 7.5 0 5-6 5-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" /></template>
                    <template v-else><rect x="3" y="3" width="8" height="8" fill="currentColor" /><rect x="13" y="13" width="8" height="8" fill="currentColor" /></template>
                  </svg>
                  {{ l.name }}
                </li>
              </ul>
            </div>
            <ul v-else class="scl-grid">
              <li v-for="l in logos" :key="l.id" class="scl-logo" :style="{ '--scl-brand': l.color }">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="2.4" />
                </svg>
                {{ l.name }}
              </li>
            </ul>
          </section>
        </template>

        <style scoped>
        .scl { display: grid; gap: 1.75rem; justify-items: center; padding: 2.5rem 1.5rem;
               font: 500 .9375rem/1.5 'Inter', sans-serif; }
        .scl-switch { display: inline-flex; border: 1px solid #E7E5E4; border-radius: 999px; overflow: clip; }
        .scl-switch button { padding: .45rem 1.1rem; border: none; background: transparent; cursor: pointer;
                             font: 600 .8rem/1 'Inter', sans-serif; color: #78716C; }
        .scl-switch button[aria-pressed='true'] { background: #292524; color: #fff; }
        .scl-logo { display: inline-flex; align-items: center; gap: .5rem; color: var(--scl-brand);
                    filter: grayscale(1); opacity: .6; transition: filter .3s ease, opacity .3s ease;
                    font-weight: 700; letter-spacing: -.01em; white-space: nowrap; }
        .scl-logo:hover { filter: grayscale(0); opacity: 1; }
        .scl-logo :deep(svg) { inline-size: 1.5rem; block-size: 1.5rem; }
        .scl-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem 2.5rem; margin: 0; padding: 0; list-style: none; }
        .scl-marquee { inline-size: 100%; overflow: clip; }
        .scl-track { display: flex; gap: 3rem; inline-size: max-content; margin: 0; padding: 0; list-style: none;
                     animation: scl-run 32s linear infinite; }
        .scl-marquee:hover .scl-track { animation-play-state: paused; }
        @keyframes scl-run { to { transform: translateX(-50%); } }
        @media (max-width: 560px) { .scl-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (prefers-reduced-motion: reduce) { .scl-track { animation: none; flex-wrap: wrap; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- LogoBand.svelte — grayscale grid ↔ seamless marquee, one switch -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const logos = [
            { id: 'lumina', name: 'Lumina', color: '#0EA5E9', mark: 'ring' },
            { id: 'orbital', name: 'Orbital', color: '#8B5CF6', mark: 'orbit' },
            { id: 'kanso', name: 'Kanso', color: '#57534E', mark: 'enso' },
            { id: 'vantage', name: 'Vantage', color: '#2563EB', mark: 'peak' },
            { id: 'helios', name: 'Helios', color: '#F59E0B', mark: 'sun' },
            { id: 'northwind', name: 'Northwind', color: '#475569', mark: 'north' },
            { id: 'quantia', name: 'Quantia', color: '#DB2777', mark: 'wave' },
            { id: 'fabrik', name: 'Fabrik', color: '#EA580C', mark: 'grid' },
          ];
          let scroll = $state(false);
        </script>

        <section class="scl">
          <div class="scl-switch" role="group" aria-label="Logo display mode">
            <button type="button" aria-pressed={!scroll} onclick={() => (scroll = false)}>Grid</button>
            <button type="button" aria-pressed={scroll} onclick={() => (scroll = true)}>Slow scroll</button>
          </div>
          <!-- the doubled track makes the loop seamless -->
          {#if scroll}
            <div class="scl-marquee" dir="ltr">
              <ul class="scl-track">
                {#each logos as l (l.id)}
                  <li class="scl-logo" style="--scl-brand: {l.color}">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      {#if l.mark === 'ring'}<circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="2.4" />
                      {:else if l.mark === 'orbit'}<circle cx="12" cy="12" r="4" fill="currentColor" /><ellipse cx="12" cy="12" rx="10" ry="4.5" fill="none" stroke="currentColor" stroke-width="1.8" />
                      {:else if l.mark === 'enso'}<path d="M17.5 7A8 8 0 1 0 19 13.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
                      {:else if l.mark === 'peak'}<path d="M3 19L10 5l4 7 3-4 4 11z" fill="currentColor" />
                      {:else if l.mark === 'sun'}<circle cx="12" cy="12" r="4.5" fill="currentColor" /><path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                      {:else if l.mark === 'north'}<path d="M12 3l7 18-7-4-7 4z" fill="currentColor" />
                      {:else if l.mark === 'wave'}<path d="M2 14c2.5-6 5-6 7.5 0s5 6 7.5 0 5-6 5-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
                      {:else}<rect x="3" y="3" width="8" height="8" fill="currentColor" /><rect x="13" y="13" width="8" height="8" fill="currentColor" />
                      {/if}
                    </svg>
                    {l.name}
                  </li>
                {/each}
                {#each logos as l ('g-' + l.id)}
                  <li class="scl-logo" aria-hidden="true" style="--scl-brand: {l.color}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8" /></svg>
                    {l.name}
                  </li>
                {/each}
              </ul>
            </div>
          {:else}
            <ul class="scl-grid">
              {#each logos as l (l.id)}
                <li class="scl-logo" style="--scl-brand: {l.color}">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8" /></svg>
                  {l.name}
                </li>
              {/each}
            </ul>
          {/if}
        </section>

        <style>
        .scl { display: grid; gap: 1.75rem; justify-items: center; padding: 2.5rem 1.5rem;
               font: 500 .9375rem/1.5 'Inter', sans-serif; }
        .scl-switch { display: inline-flex; border: 1px solid #E7E5E4; border-radius: 999px; overflow: clip; }
        .scl-switch button { padding: .45rem 1.1rem; border: none; background: transparent; cursor: pointer;
                             font: 600 .8rem/1 'Inter', sans-serif; color: #78716C; }
        .scl-switch button[aria-pressed='true'] { background: #292524; color: #fff; }
        .scl-logo { display: inline-flex; align-items: center; gap: .5rem; color: var(--scl-brand);
                    filter: grayscale(1); opacity: .6; transition: filter .3s ease, opacity .3s ease;
                    font-weight: 700; letter-spacing: -.01em; white-space: nowrap; }
        .scl-logo:hover { filter: grayscale(0); opacity: 1; }
        .scl-logo svg { inline-size: 1.5rem; block-size: 1.5rem; }
        .scl-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem 2.5rem; margin: 0; padding: 0; list-style: none; }
        .scl-marquee { inline-size: 100%; overflow: clip; }
        .scl-track { display: flex; gap: 3rem; inline-size: max-content; margin: 0; padding: 0; list-style: none;
                     animation: scl-run 32s linear infinite; }
        .scl-marquee:hover .scl-track { animation-play-state: paused; }
        @keyframes scl-run { to { transform: translateX(-50%); } }
        @media (max-width: 560px) { .scl-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (prefers-reduced-motion: reduce) { .scl-track { animation: none; flex-wrap: wrap; } }
        </style>
        SVELTE,
        ],
    ],

    'faq-section' => [
        'title' => ['fa' => 'بخش پرسش‌ها', 'en' => 'FAQ section'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'پرسش‌های پرتکرار در آکاردئون تک‌بازشونده: باز کردن یکی بقیه را می‌بندد و ارتفاع با ردیف گرید 0fr→1fr بی‌هیچ پرشی نرم باز می‌شود؛ سرستون کناری CTA تماس با فروش دارد و لینک «همهٔ پرسش‌ها» پایین کار را می‌بندد.',
            'en' => 'The frequently asked questions in a single-open accordion: opening one closes the rest, and the height unfurls softly through a 0fr→1fr grid row — no jumps; the side column carries the talk-to-sales CTA and the “all questions” link closes the page.',
        ],
        'js' => true,
        'docs' => 'https://shadcnblocks.com',
        'props' => [
            ['name' => 'open', 'type' => 'index | null', 'default' => 'null', 'note' => [
                'fa' => 'شناسهٔ پرسش باز؛ با کلیک روی همان ردیف بسته می‌شود و باز کردن ردیفی دیگر فقط همان یکی را باز نگه می‌دارد.',
                'en' => 'The open question’s id; clicking its own row closes it, and opening another keeps only that one open.',
            ]],
            ['name' => 'transition', 'type' => 'grid-rows', 'default' => "'0fr → 1fr · 300ms'", 'note' => [
                'fa' => 'بدنهٔ هر آیتم در ردیف گرید 0fr نشسته و با باز شدن به 1fr می‌رسد — انیمیشن ارتفاع بدون اندازه‌گیری جاوااسکریپت.',
                'en' => 'Each item’s body sits in a 0fr grid row and grows to 1fr when opened — height animation with zero JavaScript measuring.',
            ]],
            ['name' => 'icon', 'type' => 'rotate', 'default' => "'0 → 45°'", 'note' => [
                'fa' => 'آیکون + با باز شدن ۴۵ درجه می‌چرخد و × می‌شود؛ چرخش هم‌زمان با باز شدن ارتفاع است.',
                'en' => 'The + icon rotates 45° into an × as it opens; the spin rides the same curve as the height.',
            ]],
            ['name' => 'aside', 'type' => 'slot', 'default' => "'heading + CTA'", 'note' => [
                'fa' => 'ستون کناری: سرستون، توضیح کوتاه، دکمهٔ تماس با فروش با آیکون نامه و لینک «همهٔ پرسش‌ها» با آیکون پیکان.',
                'en' => 'The side column: the heading, a short line, the talk-to-sales button with a mail icon and the “all questions” link with an arrow.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="secfq" x-data="{ open: null }">
            <div class="secfq-side">
                <p class="secfq-eyebrow">پرسش‌های پرتکرار</p>
                <h2>چیزی که تیم‌ها پیش از شروع می‌پرسند</h2>
                <button type="button" class="secfq-cta">✉ با تیم فروش حرف بزنید</button>
                <a class="secfq-all" href="#">همهٔ پرسش‌ها ←</a>
            </div>

            <!-- آکاردئون تک‌بازشونده: باز کردن یکی، بقیه را می‌بندد -->
            <div class="secfq-list">
                <div class="secfq-item" :data-open="String(open === 0)">
                    <button type="button" class="secfq-q" :aria-expanded="String(open === 0)"
                        x-on:click="open = open === 0 ? null : 0">
                        <span>آیا دورهٔ آزمایشی نیاز به کارت بانکی دارد؟</span><i class="secfq-plus" aria-hidden="true"></i>
                    </button>
                    <div class="secfq-a"><div class="secfq-pad"><p>نه؛ ۱۴ روز کامل، بدون کارت. هیچ چیزی خودکار شارژ نمی‌شود.</p></div></div>
                </div>
            </div>
        </section>

        <style>
        .secfq { display: grid; grid-template-columns: 20rem 1fr; gap: 3rem; padding: 3rem 1.5rem; }
        .secfq-item { display: grid; grid-template-rows: auto 0fr;
                      transition: grid-template-rows .3s cubic-bezier(.2, 0, 0, 1); }
        .secfq-item[data-open='true'] { grid-template-rows: auto 1fr; }
        .secfq-a { overflow: hidden; }
        .secfq-plus { position: relative; inline-size: 1rem; aspect-ratio: 1; transition: rotate .3s; }
        .secfq-plus::before, .secfq-plus::after { content: ''; position: absolute; inset-block-start: 50%;
            inset-inline-start: 0; inline-size: 100%; block-size: 2px; background: currentColor; }
        .secfq-plus::after { rotate: 90deg; }
        .secfq-item[data-open='true'] .secfq-plus { rotate: 45deg; }
        @media (max-width: 720px) { .secfq { grid-template-columns: 1fr; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="secfq" x-data="{ open: null }">
            <div class="secfq-side">
                <p class="secfq-eyebrow">Frequently asked</p>
                <h2>What teams ask before they start</h2>
                <button type="button" class="secfq-cta">✉ Talk to sales</button>
                <a class="secfq-all" href="#">All questions →</a>
            </div>

            <!-- single-open accordion: opening one closes the rest -->
            <div class="secfq-list">
                <div class="secfq-item" :data-open="String(open === 0)">
                    <button type="button" class="secfq-q" :aria-expanded="String(open === 0)"
                        x-on:click="open = open === 0 ? null : 0">
                        <span>Does the trial need a credit card?</span><i class="secfq-plus" aria-hidden="true"></i>
                    </button>
                    <div class="secfq-a"><div class="secfq-pad"><p>No card, 14 full days. Nothing ever auto-charges.</p></div></div>
                </div>
            </div>
        </section>

        <style>
        .secfq { display: grid; grid-template-columns: 20rem 1fr; gap: 3rem; padding: 3rem 1.5rem; }
        .secfq-item { display: grid; grid-template-rows: auto 0fr;
                      transition: grid-template-rows .3s cubic-bezier(.2, 0, 0, 1); }
        .secfq-item[data-open='true'] { grid-template-rows: auto 1fr; }
        .secfq-a { overflow: hidden; }
        .secfq-plus { position: relative; inline-size: 1rem; aspect-ratio: 1; transition: rotate .3s; }
        .secfq-plus::before, .secfq-plus::after { content: ''; position: absolute; inset-block-start: 50%;
            inset-inline-start: 0; inline-size: 100%; block-size: 2px; background: currentColor; }
        .secfq-plus::after { rotate: 90deg; }
        .secfq-item[data-open='true'] .secfq-plus { rotate: 45deg; }
        @media (max-width: 720px) { .secfq { grid-template-columns: 1fr; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Faq.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import FaqSection from '@/components/FaqSection';

        export default function Faq() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <FaqSection />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // FaqSection.jsx — single-open accordion, 0fr→1fr height animation
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const FAQS = [
          { id: 'trial', q: 'Does the trial need a credit card?',
            a: 'No card, 14 full days. You get one reminder email on day 12 and nothing ever auto-charges.' },
          { id: 'data', q: 'Where does our data live?',
            a: 'In the region you pick — Frankfurt, Dublin or Singapore. Encrypted at rest with AES-256 and in flight with TLS 1.3.' },
          { id: 'plan', q: 'Can we switch plans mid-month?',
            a: 'Yes. Changes are prorated to the minute and only the difference shows up on the next invoice.' },
          { id: 'exit', q: 'What does leaving look like?',
            a: 'One click: a full JSON and CSV export of every workspace, then hard deletion within 30 days.' },
        ];

        export default function FaqSection() {
          const [open, setOpen] = useState('trial');
          const toggle = (id) => setOpen((v) => (v === id ? null : id));

          return (
            <>
              <style>{`
                .scf { display: grid; grid-template-columns: 19rem 1fr; gap: 3rem; padding: 3rem 1.5rem;
                       font: 400 .9375rem/1.6 'Inter', sans-serif; color: #1C1917; }
                .scf-eyebrow { margin: 0 0 .5rem; font-size: .72rem; font-weight: 700; letter-spacing: .14em;
                               text-transform: uppercase; color: #78716C; }
                .scf-side h2 { margin: 0 0 1rem; font-size: 1.6rem; font-weight: 800; letter-spacing: -.02em; }
                .scf-cta { display: inline-flex; align-items: center; gap: .5rem; padding: .65rem 1.1rem;
                           border: none; border-radius: .8rem; background: #4F46E5; color: #fff;
                           font: 600 .85rem/1 inherit; cursor: pointer; }
                .scf-all { display: inline-block; margin-block-start: 1rem; color: #4F46E5; font-weight: 600; }
                .scf-item { display: grid; grid-template-rows: auto 0fr;
                            transition: grid-template-rows .3s cubic-bezier(.2, 0, 0, 1); }
                .scf-item[data-open='true'] { grid-template-rows: auto 1fr; }
                .scf-q { display: flex; align-items: center; gap: 1rem; inline-size: 100%; padding: 1.1rem .25rem;
                         border: none; border-block-end: 1px solid #E7E5E4; background: transparent;
                         font: 650 .95rem/1.4 'Inter', sans-serif; color: inherit; cursor: pointer; text-align: start; }
                .scf-a { overflow: hidden; }
                .scf-a > div { padding: 0 .25rem 1.1rem; color: #57534E; }
                .scf-plus { flex: none; position: relative; inline-size: 1rem; aspect-ratio: 1;
                            margin-inline-start: auto; transition: rotate .3s cubic-bezier(.2, 0, 0, 1); }
                .scf-plus::before, .scf-plus::after { content: ''; position: absolute; inset-block-start: calc(50% - 1px);
                           inset-inline-start: 0; inline-size: 100%; block-size: 2px; background: currentColor; }
                .scf-plus::after { rotate: 90deg; }
                .scf-item[data-open='true'] .scf-plus { rotate: 45deg; }
                @media (max-width: 720px) { .scf { grid-template-columns: 1fr; gap: 1.5rem; } }
              `}</style>
              <section className="scf">
                <div className="scf-side">
                  <p className="scf-eyebrow">Frequently asked</p>
                  <h2>What teams ask before they start</h2>
                  <button type="button" className="scf-cta">✉ Talk to sales</button>
                  <a className="scf-all" href="#">All questions →</a>
                </div>
                <div>
                  {FAQS.map((f) => (
                    // data-open drives the 0fr → 1fr row transition; no height measuring.
                    <div className="scf-item" data-open={open === f.id} key={f.id}>
                      <button type="button" className="scf-q" aria-expanded={open === f.id} onClick={() => toggle(f.id)}>
                        <span>{f.q}</span>
                        <i className="scf-plus" aria-hidden="true" />
                      </button>
                      <div className="scf-a"><div>{f.a}</div></div>
                    </div>
                  ))}
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- FaqSection.vue — single-open accordion, 0fr→1fr height animation -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const faqs = [
          { id: 'trial', q: 'Does the trial need a credit card?',
            a: 'No card, 14 full days. You get one reminder email on day 12 and nothing ever auto-charges.' },
          { id: 'data', q: 'Where does our data live?',
            a: 'In the region you pick — Frankfurt, Dublin or Singapore. Encrypted at rest with AES-256 and in flight with TLS 1.3.' },
          { id: 'plan', q: 'Can we switch plans mid-month?',
            a: 'Yes. Changes are prorated to the minute and only the difference shows up on the next invoice.' },
          { id: 'exit', q: 'What does leaving look like?',
            a: 'One click: a full JSON and CSV export of every workspace, then hard deletion within 30 days.' },
        ];
        const open = ref('trial');
        const toggle = (id: string) => { open.value = open.value === id ? null : id; };
        </script>

        <template>
          <section class="scf">
            <div class="scf-side">
              <p class="scf-eyebrow">Frequently asked</p>
              <h2>What teams ask before they start</h2>
              <button type="button" class="scf-cta">✉ Talk to sales</button>
              <a class="scf-all" href="#">All questions →</a>
            </div>
            <div>
              <!-- data-open drives the 0fr → 1fr row transition; no height measuring. -->
              <div v-for="f in faqs" :key="f.id" class="scf-item" :data-open="open === f.id">
                <button type="button" class="scf-q" :aria-expanded="open === f.id" @click="toggle(f.id)">
                  <span>{{ f.q }}</span>
                  <i class="scf-plus" aria-hidden="true"></i>
                </button>
                <div class="scf-a"><div>{{ f.a }}</div></div>
              </div>
            </div>
          </section>
        </template>

        <style scoped>
        .scf { display: grid; grid-template-columns: 19rem 1fr; gap: 3rem; padding: 3rem 1.5rem;
               font: 400 .9375rem/1.6 'Inter', sans-serif; color: #1C1917; }
        .scf-eyebrow { margin: 0 0 .5rem; font-size: .72rem; font-weight: 700; letter-spacing: .14em;
                       text-transform: uppercase; color: #78716C; }
        .scf-side h2 { margin: 0 0 1rem; font-size: 1.6rem; font-weight: 800; letter-spacing: -.02em; }
        .scf-cta { display: inline-flex; align-items: center; gap: .5rem; padding: .65rem 1.1rem;
                   border: none; border-radius: .8rem; background: #4F46E5; color: #fff;
                   font: 600 .85rem/1 inherit; cursor: pointer; }
        .scf-all { display: inline-block; margin-block-start: 1rem; color: #4F46E5; font-weight: 600; }
        .scf-item { display: grid; grid-template-rows: auto 0fr;
                    transition: grid-template-rows .3s cubic-bezier(.2, 0, 0, 1); }
        .scf-item[data-open='true'] { grid-template-rows: auto 1fr; }
        .scf-q { display: flex; align-items: center; gap: 1rem; inline-size: 100%; padding: 1.1rem .25rem;
                 border: none; border-block-end: 1px solid #E7E5E4; background: transparent;
                 font: 650 .95rem/1.4 'Inter', sans-serif; color: inherit; cursor: pointer; text-align: start; }
        .scf-a { overflow: hidden; }
        .scf-a > div { padding: 0 .25rem 1.1rem; color: #57534E; }
        .scf-plus { flex: none; position: relative; inline-size: 1rem; aspect-ratio: 1;
                    margin-inline-start: auto; transition: rotate .3s cubic-bezier(.2, 0, 0, 1); }
        .scf-plus::before, .scf-plus::after { content: ''; position: absolute; inset-block-start: calc(50% - 1px);
                   inset-inline-start: 0; inline-size: 100%; block-size: 2px; background: currentColor; }
        .scf-plus::after { rotate: 90deg; }
        .scf-item[data-open='true'] .scf-plus { rotate: 45deg; }
        @media (max-width: 720px) { .scf { grid-template-columns: 1fr; gap: 1.5rem; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- FaqSection.svelte — single-open accordion, 0fr→1fr height animation -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const faqs = [
            { id: 'trial', q: 'Does the trial need a credit card?',
              a: 'No card, 14 full days. You get one reminder email on day 12 and nothing ever auto-charges.' },
            { id: 'data', q: 'Where does our data live?',
              a: 'In the region you pick — Frankfurt, Dublin or Singapore. Encrypted at rest with AES-256 and in flight with TLS 1.3.' },
            { id: 'plan', q: 'Can we switch plans mid-month?',
              a: 'Yes. Changes are prorated to the minute and only the difference shows up on the next invoice.' },
            { id: 'exit', q: 'What does leaving look like?',
              a: 'One click: a full JSON and CSV export of every workspace, then hard deletion within 30 days.' },
          ];
          let open = $state('trial');
          const toggle = (id: string) => { open = open === id ? null : id; };
        </script>

        <section class="scf">
          <div class="scf-side">
            <p class="scf-eyebrow">Frequently asked</p>
            <h2>What teams ask before they start</h2>
            <button type="button" class="scf-cta">✉ Talk to sales</button>
            <a class="scf-all" href="#">All questions →</a>
          </div>
          <div>
            <!-- data-open drives the 0fr → 1fr row transition; no height measuring. -->
            {#each faqs as f (f.id)}
              <div class="scf-item" data-open={open === f.id}>
                <button type="button" class="scf-q" aria-expanded={open === f.id} onclick={() => toggle(f.id)}>
                  <span>{f.q}</span>
                  <i class="scf-plus" aria-hidden="true"></i>
                </button>
                <div class="scf-a"><div>{f.a}</div></div>
              </div>
            {/each}
          </div>
        </section>

        <style>
        .scf { display: grid; grid-template-columns: 19rem 1fr; gap: 3rem; padding: 3rem 1.5rem;
               font: 400 .9375rem/1.6 'Inter', sans-serif; color: #1C1917; }
        .scf-eyebrow { margin: 0 0 .5rem; font-size: .72rem; font-weight: 700; letter-spacing: .14em;
                       text-transform: uppercase; color: #78716C; }
        .scf-side h2 { margin: 0 0 1rem; font-size: 1.6rem; font-weight: 800; letter-spacing: -.02em; }
        .scf-cta { display: inline-flex; align-items: center; gap: .5rem; padding: .65rem 1.1rem;
                   border: none; border-radius: .8rem; background: #4F46E5; color: #fff;
                   font: 600 .85rem/1 inherit; cursor: pointer; }
        .scf-all { display: inline-block; margin-block-start: 1rem; color: #4F46E5; font-weight: 600; }
        .scf-item { display: grid; grid-template-rows: auto 0fr;
                    transition: grid-template-rows .3s cubic-bezier(.2, 0, 0, 1); }
        .scf-item[data-open='true'] { grid-template-rows: auto 1fr; }
        .scf-q { display: flex; align-items: center; gap: 1rem; inline-size: 100%; padding: 1.1rem .25rem;
                 border: none; border-block-end: 1px solid #E7E5E4; background: transparent;
                 font: 650 .95rem/1.4 'Inter', sans-serif; color: inherit; cursor: pointer; text-align: start; }
        .scf-a { overflow: hidden; }
        .scf-a > div { padding: 0 .25rem 1.1rem; color: #57534E; }
        .scf-plus { flex: none; position: relative; inline-size: 1rem; aspect-ratio: 1;
                    margin-inline-start: auto; transition: rotate .3s cubic-bezier(.2, 0, 0, 1); }
        .scf-plus::before, .scf-plus::after { content: ''; position: absolute; inset-block-start: calc(50% - 1px);
                   inset-inline-start: 0; inline-size: 100%; block-size: 2px; background: currentColor; }
        .scf-plus::after { rotate: 90deg; }
        .scf-item[data-open='true'] .scf-plus { rotate: 45deg; }
        @media (max-width: 720px) { .scf { grid-template-columns: 1fr; gap: 1.5rem; } }
        </style>
        SVELTE,
        ],
    ],

    'stats-band' => [
        'title' => ['fa' => 'نوار آماری', 'en' => 'Stats band'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'نوار تمام‌عرض تیره با بافت نقطه‌ای ظریف و چهار شمارندهٔ بزرگ که همین که وارد دید می‌شوند از صفر تا مقدار می‌شمارند — هر یک با آیکون خطی و برچسب ریز؛ با حرکت کاهشی مستقیم روی عدد نهایی می‌نشیند.',
            'en' => 'A full-width dark band with a fine dot texture and four big counters that count from zero the moment they enter view — each with a line icon and a small label; reduced motion settles straight on the final number.',
        ],
        'js' => true,
        'docs' => 'https://shadcnblocks.com',
        'props' => [
            ['name' => 'trigger', 'type' => 'IntersectionObserver', 'default' => "'threshold 0.25 · once'", 'note' => [
                'fa' => 'شمارش فقط بار اولِ دیده‌شدن شروع می‌شود؛ رصدگر بعد از آن قطع می‌شود و شمارنده دیگر از نو نمی‌شمارد.',
                'en' => 'Counting starts the first time the band is seen; the observer disconnects after and never restarts.',
            ]],
            ['name' => 'easing', 'type' => 'curve', 'default' => "'easeOutCubic · 1400ms'", 'note' => [
                'fa' => 'شمارش با کند شدن نرم به مقدار می‌رسد؛ اعداد اعشاری (۹۹٫۹۹ و ۳٫۴) هم‌زمان با اعشارشان می‌شمارند.',
                'en' => 'The count eases out to its value; the decimals (99.99, 3.4) count along with their fraction digits.',
            ]],
            ['name' => 'decimals · suffix', 'type' => 'format', 'default' => "'2 · %'", 'note' => [
                'fa' => 'هر عدد تعداد رقم اعشار و پسوند خودش را دارد؛ در فارسی ارقام فارسی، ممیز «٫» و جداکنندهٔ هزارگان «٬» می‌شود.',
                'en' => 'Each number carries its own decimals and suffix; in Persian the digits, the decimal mark «٫» and the thousands separator «٬» all localise.',
            ]],
            ['name' => 'texture', 'type' => 'background', 'default' => "'dots · 22px'", 'note' => [
                'fa' => 'بافت نقطه‌ای با radial-gradient یک‌پیکسلی روی سرمهٔ تیره؛ نوار در هر دو تم تیره می‌ماند چون هویتش تیره است.',
                'en' => 'The one-pixel radial-gradient dot grid over deep navy; the band stays dark in both themes — dark is its identity.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'final value'", 'note' => [
                'fa' => 'با prefers-reduced-motion شمارش حذف و عدد نهایی بی‌درنگ نشان داده می‌شود.',
                'en' => 'Under prefers-reduced-motion the count is skipped and the final value shows instantly.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="secst" x-data="{
                isFa: true, vals: [0, 0, 0, 0],
                fmt(v, dec) { let s = dec ? v.toFixed(dec) : Math.round(v).toLocaleString('en-US');
                    if (this.isFa) s = s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]).replace('.', '٫').replace(',', '٬');
                    return s; },
                snap() { this.vals = [99.99, 3.4, 142, 8200]; },
                start() { const T0 = performance.now(), DUR = 1400, TO = [99.99, 3.4, 142, 8200];
                    const tick = (t) => { const p = Math.min(1, (t - T0) / DUR), e = 1 - Math.pow(1 - p, 3);
                        this.vals = TO.map(x => x * e); if (p < 1) requestAnimationFrame(tick); };
                    requestAnimationFrame(tick); },
            }"
            x-init="if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { snap(); }
                    else { const io = new IntersectionObserver((es) => { es.forEach((en) => {
                        if (en.isIntersecting) { start(); io.disconnect(); } }); }, { threshold: .25 });
                        io.observe($el); }">
            <!-- شمارنده‌ها اولین بار که دیده شوند می‌شمارند -->
            <div class="secst-grid">
                <div class="secst-stat">
                    <svg aria-hidden="true">…سپر…</svg>
                    <p class="secst-value"><span x-text="fmt(vals[0], 2)"></span><small>٪</small></p>
                    <small>آپتایم اندازه‌گیری‌شده</small>
                </div>
            </div>
        </section>

        <style>
        .secst { padding: 3rem 1.5rem; border-radius: 1.4rem; color: #F8FAFC;
                 background: #0B1220 radial-gradient(rgba(148,163,184,.16) 1px, transparent 1.5px);
                 background-size: auto, 22px 22px; }
        .secst-grid { display: grid; grid-template-columns: repeat(4, 1fr); }
        .secst-value { margin: 0; font: 800 clamp(2rem, 4.5vw, 3.25rem)/1 'Inter', 'Vazirmatn', sans-serif;
                       letter-spacing: -.03em; font-variant-numeric: tabular-nums; }
        .secst-stat:not(:first-child) { border-inline-start: 1px solid #1E293B; }
        @media (max-width: 640px) { .secst-grid { grid-template-columns: repeat(2, 1fr); } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="secst" x-data="{
                vals: [0, 0, 0, 0],
                fmt(v, dec) { return dec ? v.toFixed(dec) : Math.round(v).toLocaleString('en-US'); },
                snap() { this.vals = [99.99, 3.4, 142, 8200]; },
                start() { const T0 = performance.now(), DUR = 1400, TO = [99.99, 3.4, 142, 8200];
                    const tick = (t) => { const p = Math.min(1, (t - T0) / DUR), e = 1 - Math.pow(1 - p, 3);
                        this.vals = TO.map(x => x * e); if (p < 1) requestAnimationFrame(tick); };
                    requestAnimationFrame(tick); },
            }"
            x-init="if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { snap(); }
                    else { const io = new IntersectionObserver((es) => { es.forEach((en) => {
                        if (en.isIntersecting) { start(); io.disconnect(); } }); }, { threshold: .25 });
                        io.observe($el); }">
            <!-- the counters run the first time the band enters the view -->
            <div class="secst-grid">
                <div class="secst-stat">
                    <svg aria-hidden="true">…shield…</svg>
                    <p class="secst-value"><span x-text="fmt(vals[0], 2)"></span><small>%</small></p>
                    <small>measured uptime</small>
                </div>
            </div>
        </section>

        <style>
        .secst { padding: 3rem 1.5rem; border-radius: 1.4rem; color: #F8FAFC;
                 background: #0B1220 radial-gradient(rgba(148,163,184,.16) 1px, transparent 1.5px);
                 background-size: auto, 22px 22px; }
        .secst-grid { display: grid; grid-template-columns: repeat(4, 1fr); }
        .secst-value { margin: 0; font: 800 clamp(2rem, 4.5vw, 3.25rem)/1 'Inter', sans-serif;
                       letter-spacing: -.03em; font-variant-numeric: tabular-nums; }
        .secst-stat:not(:first-child) { border-inline-start: 1px solid #1E293B; }
        @media (max-width: 640px) { .secst-grid { grid-template-columns: repeat(2, 1fr); } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Scale.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import StatsBand from '@/components/StatsBand';

        export default function Scale() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <StatsBand />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // StatsBand.jsx — counters that run once, the moment they scroll into view
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const STATS = [
          { id: 'uptime', to: 99.99, dec: 2, suffix: '%', label: 'measured uptime' },
          { id: 'events', to: 3.4, dec: 1, suffix: 'B', label: 'events a day' },
          { id: 'countries', to: 142, dec: 0, suffix: '', label: 'countries served' },
          { id: 'teams', to: 8200, dec: 0, suffix: '+', label: 'teams on board' },
        ];
        const fmt = (v, dec) => (dec ? v.toFixed(dec) : Math.round(v).toLocaleString('en-US'));

        export default function StatsBand() {
          const [vals, setVals] = useState(STATS.map(() => 0));
          const rootRef = useRef(null);
          const started = useRef(false);

          const run = () => {
            const t0 = performance.now(), DUR = 1400;
            const tick = (t) => {
              const p = Math.min(1, (t - t0) / DUR);
              const e = 1 - Math.pow(1 - p, 3); // easeOutCubic
              setVals(STATS.map((s) => s.to * e));
              if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
          };

          useEffect(() => {
            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduce) { setVals(STATS.map((s) => s.to)); return; }
            const io = new IntersectionObserver((entries) => {
              entries.forEach((en) => {
                if (en.isIntersecting && !started.current) { started.current = true; run(); io.disconnect(); }
              });
            }, { threshold: 0.25 });
            io.observe(rootRef.current);
            return () => io.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .scs { display: grid; grid-template-columns: repeat(4, 1fr); padding: 3rem 1.5rem;
                       border-radius: 1.4rem; color: #F8FAFC; font: 400 .9375rem/1.5 'Inter', sans-serif;
                       background: #0B1220 radial-gradient(rgba(148, 163, 184, .16) 1px, transparent 1.5px);
                       background-size: auto, 22px 22px; }
                .scs-stat { display: grid; gap: .5rem; justify-items: center; text-align: center; padding: .5rem 1rem; }
                .scs-stat:not(:first-child) { border-inline-start: 1px solid #1E293B; }
                .scs-stat svg { inline-size: 1.5rem; block-size: 1.5rem; stroke: #7DD3FC; stroke-width: 1.75;
                                fill: none; stroke-linecap: round; stroke-linejoin: round; }
                .scs-value { margin: 0; font: 800 clamp(2rem, 4.5vw, 3.25rem)/1 'Inter', sans-serif;
                             letter-spacing: -.03em; font-variant-numeric: tabular-nums; }
                .scs-value small { font-size: .45em; font-weight: 700; color: #7DD3FC; }
                .scs-stat > small { color: #94A3B8; }
                @media (max-width: 640px) { .scs { grid-template-columns: repeat(2, 1fr); row-gap: 2rem; }
                  .scs-stat:nth-child(3) { border-inline-start: none; } }
              `}</style>
              <section className="scs" ref={rootRef}>
                {STATS.map((s, i) => (
                  <div className="scs-stat" key={s.id}>
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      {s.id === 'uptime' && <><path d="M12 3l7 3v5c0 4.4-3 8.4-7 9.5C8 19.4 5 15.4 5 11V6l7-3z" /><path d="M9 11.5l2 2 4-4.5" /></>}
                      {s.id === 'events' && <path d="M13 2.5L5 13.5h5.5L11 21.5l8-11h-5.5l-.5-8z" />}
                      {s.id === 'countries' && <><circle cx="12" cy="12" r="8.5" /><path d="M3.5 12h17M12 3.5c2.6 2.4 4 5.3 4 8.5s-1.4 6.1-4 8.5c-2.6-2.4-4-5.3-4-8.5s1.4-6.1 4-8.5z" /></>}
                      {s.id === 'teams' && <><circle cx="9" cy="8.5" r="3.5" /><path d="M3 19.5c.9-3.3 3.3-5 6-5s5.1 1.7 6 5" /><path d="M15.5 5.5a3.5 3.5 0 0 1 0 6.4" /></>}
                    </svg>
                    <p className="scs-value">{fmt(vals[i], s.dec)}{s.suffix && <small> {s.suffix}</small>}</p>
                    <small>{s.label}</small>
                  </div>
                ))}
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- StatsBand.vue — counters that run once, the moment they scroll into view -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const stats = [
          { id: 'uptime', to: 99.99, dec: 2, suffix: '%', label: 'measured uptime' },
          { id: 'events', to: 3.4, dec: 1, suffix: 'B', label: 'events a day' },
          { id: 'countries', to: 142, dec: 0, suffix: '', label: 'countries served' },
          { id: 'teams', to: 8200, dec: 0, suffix: '+', label: 'teams on board' },
        ];
        const vals = ref(stats.map(() => 0));
        const root = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;
        const fmt = (v: number, dec: number) => (dec ? v.toFixed(dec) : Math.round(v).toLocaleString('en-US'));

        const run = () => {
          const t0 = performance.now(), DUR = 1400;
          const tick = (t: number) => {
            const p = Math.min(1, (t - t0) / DUR);
            const e = 1 - Math.pow(1 - p, 3); // easeOutCubic
            vals.value = stats.map((s) => s.to * e);
            if (p < 1) requestAnimationFrame(tick);
          };
          requestAnimationFrame(tick);
        };

        onMounted(() => {
          if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            vals.value = stats.map((s) => s.to);
            return;
          }
          io = new IntersectionObserver((entries) => {
            entries.forEach((en) => {
              if (en.isIntersecting && io) { run(); io.disconnect(); io = null; }
            });
          }, { threshold: 0.25 });
          if (root.value) io.observe(root.value);
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <section ref="root" class="scs">
            <div v-for="s, i in stats" :key="s.id" class="scs-stat">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <template v-if="s.id === 'uptime'"><path d="M12 3l7 3v5c0 4.4-3 8.4-7 9.5C8 19.4 5 15.4 5 11V6l7-3z" /><path d="M9 11.5l2 2 4-4.5" /></template>
                <template v-else-if="s.id === 'events'"><path d="M13 2.5L5 13.5h5.5L11 21.5l8-11h-5.5l-.5-8z" /></template>
                <template v-else-if="s.id === 'countries'"><circle cx="12" cy="12" r="8.5" /><path d="M3.5 12h17M12 3.5c2.6 2.4 4 5.3 4 8.5s-1.4 6.1-4 8.5c-2.6-2.4-4-5.3-4-8.5s1.4-6.1 4-8.5z" /></template>
                <template v-else><circle cx="9" cy="8.5" r="3.5" /><path d="M3 19.5c.9-3.3 3.3-5 6-5s5.1 1.7 6 5" /><path d="M15.5 5.5a3.5 3.5 0 0 1 0 6.4" /></template>
              </svg>
              <p class="scs-value">{{ fmt(vals[i], s.dec) }}<small v-if="s.suffix"> {{ s.suffix }}</small></p>
              <small>{{ s.label }}</small>
            </div>
          </section>
        </template>

        <style scoped>
        .scs { display: grid; grid-template-columns: repeat(4, 1fr); padding: 3rem 1.5rem;
               border-radius: 1.4rem; color: #F8FAFC; font: 400 .9375rem/1.5 'Inter', sans-serif;
               background: #0B1220 radial-gradient(rgba(148, 163, 184, .16) 1px, transparent 1.5px);
               background-size: auto, 22px 22px; }
        .scs-stat { display: grid; gap: .5rem; justify-items: center; text-align: center; padding: .5rem 1rem; }
        .scs-stat:not(:first-child) { border-inline-start: 1px solid #1E293B; }
        .scs-stat svg { inline-size: 1.5rem; block-size: 1.5rem; stroke: #7DD3FC; stroke-width: 1.75;
                        fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .scs-value { margin: 0; font: 800 clamp(2rem, 4.5vw, 3.25rem)/1 'Inter', sans-serif;
                     letter-spacing: -.03em; font-variant-numeric: tabular-nums; }
        .scs-value small { font-size: .45em; font-weight: 700; color: #7DD3FC; }
        .scs-stat > small { color: #94A3B8; }
        @media (max-width: 640px) { .scs { grid-template-columns: repeat(2, 1fr); row-gap: 2rem; }
          .scs-stat:nth-child(3) { border-inline-start: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- StatsBand.svelte — counters that run once, the moment they scroll into view -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const stats = [
            { id: 'uptime', to: 99.99, dec: 2, suffix: '%', label: 'measured uptime' },
            { id: 'events', to: 3.4, dec: 1, suffix: 'B', label: 'events a day' },
            { id: 'countries', to: 142, dec: 0, suffix: '', label: 'countries served' },
            { id: 'teams', to: 8200, dec: 0, suffix: '+', label: 'teams on board' },
          ];
          let vals = $state(stats.map(() => 0));
          let root: HTMLElement;
          const fmt = (v: number, dec: number) => (dec ? v.toFixed(dec) : Math.round(v).toLocaleString('en-US'));

          const run = () => {
            const t0 = performance.now(), DUR = 1400;
            const tick = (t: number) => {
              const p = Math.min(1, (t - t0) / DUR);
              const e = 1 - Math.pow(1 - p, 3); // easeOutCubic
              vals = stats.map((s) => s.to * e);
              if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
          };

          onMount(() => {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
              vals = stats.map((s) => s.to);
              return;
            }
            const io = new IntersectionObserver((entries) => {
              entries.forEach((en) => {
                if (en.isIntersecting) { run(); io.disconnect(); }
              });
            }, { threshold: 0.25 });
            io.observe(root);
            return () => io.disconnect();
          });
        </script>

        <section class="scs" bind:this={root}>
          {#each stats as s, i (s.id)}
            <div class="scs-stat">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                {#if s.id === 'uptime'}<path d="M12 3l7 3v5c0 4.4-3 8.4-7 9.5C8 19.4 5 15.4 5 11V6l7-3z" /><path d="M9 11.5l2 2 4-4.5" />
                {:else if s.id === 'events'}<path d="M13 2.5L5 13.5h5.5L11 21.5l8-11h-5.5l-.5-8z" />
                {:else if s.id === 'countries'}<circle cx="12" cy="12" r="8.5" /><path d="M3.5 12h17M12 3.5c2.6 2.4 4 5.3 4 8.5s-1.4 6.1-4 8.5c-2.6-2.4-4-5.3-4-8.5s1.4-6.1 4-8.5z" />
                {:else}<circle cx="9" cy="8.5" r="3.5" /><path d="M3 19.5c.9-3.3 3.3-5 6-5s5.1 1.7 6 5" /><path d="M15.5 5.5a3.5 3.5 0 0 1 0 6.4" />
                {/if}
              </svg>
              <p class="scs-value">{fmt(vals[i], s.dec)}{#if s.suffix}<small> {s.suffix}</small>{/if}</p>
              <small>{s.label}</small>
            </div>
          {/each}
        </section>

        <style>
        .scs { display: grid; grid-template-columns: repeat(4, 1fr); padding: 3rem 1.5rem;
               border-radius: 1.4rem; color: #F8FAFC; font: 400 .9375rem/1.5 'Inter', sans-serif;
               background: #0B1220 radial-gradient(rgba(148, 163, 184, .16) 1px, transparent 1.5px);
               background-size: auto, 22px 22px; }
        .scs-stat { display: grid; gap: .5rem; justify-items: center; text-align: center; padding: .5rem 1rem; }
        .scs-stat:not(:first-child) { border-inline-start: 1px solid #1E293B; }
        .scs-stat svg { inline-size: 1.5rem; block-size: 1.5rem; stroke: #7DD3FC; stroke-width: 1.75;
                        fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .scs-value { margin: 0; font: 800 clamp(2rem, 4.5vw, 3.25rem)/1 'Inter', sans-serif;
                     letter-spacing: -.03em; font-variant-numeric: tabular-nums; }
        .scs-value small { font-size: .45em; font-weight: 700; color: #7DD3FC; }
        .scs-stat > small { color: #94A3B8; }
        @media (max-width: 640px) { .scs { grid-template-columns: repeat(2, 1fr); row-gap: 2rem; }
          .scs-stat:nth-child(3) { border-inline-start: none; } }
        </style>
        SVELTE,
        ],
    ],
];
