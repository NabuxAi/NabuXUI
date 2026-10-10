<?php

/**
 * Demo manifest of the "scroll" group — scroll-driven storytelling patterns
 * from the scrolltide.co gallery, rebuilt as living product scenes on a tall
 * stage inside pg-box: sticky cards that stack under the next one, a vertical
 * scroll that drives a horizontal project strip, multi-speed parallax depth
 * with a short narrative, a zooming roadmap on a filling progress line, and a
 * clip-path reveal gallery. Every scene listens to scroll relative to its own
 * position (rAF-throttled, cleaned up on destroy) so Livewire morphs stay
 * happy. Scenarios live at
 * resources/views/demos/components/scroll/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'اسکرول', 'en' => 'Scroll'],

    'stack-cards' => [
        'title' => ['fa' => 'کارت‌های انبارشونده', 'en' => 'Sticky stack cards'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'پلن‌های «مریدین» با اسکرول روی هم انبار می‌شوند: هر کارت چسبان با ورود کارت بعدی کمی کوچک و محو می‌شود و لبه‌اش زیر کارت تازه پیدا می‌ماند — قیمت‌گذاری که خودش روایت می‌شود، بدون حتی یک خط جاوااسکریپت برای چسبیدن.',
            'en' => 'Meridian’s plans stack up as you scroll: each sticky card shrinks a touch and dims under the next one while its rim stays visible — pricing that narrates itself, with the sticking done purely by CSS.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'zone', 'type' => 'length', 'default' => "'88vh'", 'note' => [
                'fa' => 'ارتفاع ناحیهٔ هر کارت؛ کل مسیر انبارش از همین عدد ساخته می‌شود (۴ ناحیه ≈ ۳۵۰vh).',
                'en' => 'Each card’s zone height; the whole stacking runway is built from it (4 zones ≈ 350vh).',
            ]],
            ['name' => 'sticky-top', 'type' => 'calc', 'default' => "'5.5rem + i × .875rem'", 'note' => [
                'fa' => 'هر کارت کمی پایین‌تر از قبلی می‌چسبد تا لبهٔ انبارشونده دیده بماند.',
                'en' => 'Every card sticks slightly lower than the one before, so the stacked rims stay in sight.',
            ]],
            ['name' => '--scs-b', 'type' => '0 → 1', 'default' => "'per card'", 'note' => [
                'fa' => 'درصد پوشیده‌شدن هر کارت توسط کارت بعدی؛ از همان، scale تا ۰٫۹۴ و محوی تا ۰٫۵۵ محاسبه می‌شود.',
                'en' => 'How far the next card has covered this one; scale down to 0.94 and dimming to 0.55 derive from it.',
            ]],
            ['name' => 'scale-floor', 'type' => 'scale', 'default' => "'0.94'", 'note' => [
                'fa' => 'کف کوچک‌شدن کارت مدفون؛ عمقِ انبار را همین فاصلهٔ ناچیز می‌سازد.',
                'en' => 'The buried card’s scale floor; this tiny gap is what makes the stack read as depth.',
            ]],
            ['name' => 'fade-floor', 'type' => 'opacity', 'default' => "'0.45'", 'note' => [
                'fa' => 'کف محوی کارت زیرین؛ کارت هنوز دیده می‌شود اما حرف اول را کارت تازه می‌زند.',
                'en' => 'The lower card’s dimming floor; still visible, but the fresh card owns the scene.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- کارت‌های انبارشونده: اسکرول عمودی پلن‌ها را روی هم می‌انبارد --}}
        <div class="scs" x-data="{
                stop: () => {},
                init() {
                    const cards = [...this.$root.querySelectorAll('.scs-card')];
                    let frame = 0;
                    const measure = () => {
                        frame = 0;
                        cards.forEach((card, i) => {
                            const next = cards[i + 1];
                            if (!next) return;
                            const top = card.getBoundingClientRect().top;
                            const nt = next.getBoundingClientRect().top;
                            // پوشش: از لحظهٔ ورود کارت بعدی تا نشستنش کنار کارت فعلی
                            const covered = Math.min(1, Math.max(0, (innerHeight - nt) / (innerHeight - top)));
                            card.style.setProperty('--scs-b', covered.toFixed(3));
                        });
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <div class="scs-zone"><article class="scs-card" style="--i: 0">
                <h3>آغازگر</h3><b>۰ دلار در ماه</b>
                <ul><li>۲ خط انتشار</li><li>۳ ناحیه</li><li>گزارش هفتگی</li></ul>
            </article></div>
            <div class="scs-zone"><article class="scs-card" style="--i: 1">
                <h3>رشد</h3><b>۴۹ دلار در ماه</b>
                <ul><li>۱۰ خط انتشار</li><li>۱۲ ناحیه</li><li>کاناری و واگرد خودکار</li></ul>
            </article></div>
            <div class="scs-zone"><article class="scs-card" style="--i: 2">
                <h3>مقیاس</h3><b>۱۹۹ دلار در ماه</b>
                <ul><li>خط انتشار نامحدود</li><li>۲۴ ناحیه</li><li>تحویل تدریجی + SLA</li></ul>
            </article></div>
            <div class="scs-zone"><article class="scs-card" style="--i: 3">
                <h3>سازمانی</h3><b>سفارشی</b>
                <ul><li>ابر اختصاصی</li><li>SOC 2</li><li>مهندس اختصاصی</li></ul>
            </article></div>
        </div>

        <style>
        .scs-zone { block-size: 88vh; }
        .scs-card { position: sticky; inset-block-start: calc(5.5rem + var(--i) * .875rem);
                    inline-size: min(100%, 34rem); margin-inline: auto; padding: 1.5rem;
                    border-radius: 1.5rem; background: #fff; border: 1px solid #e4e4ef;
                    scale: calc(1 - var(--scs-b, 0) * .06);
                    opacity: calc(1 - var(--scs-b, 0) * .55); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Sticky stack cards: vertical scroll piles the plans one under the next --}}
        <div class="scs" x-data="{
                stop: () => {},
                init() {
                    const cards = [...this.$root.querySelectorAll('.scs-card')];
                    let frame = 0;
                    const measure = () => {
                        frame = 0;
                        cards.forEach((card, i) => {
                            const next = cards[i + 1];
                            if (!next) return;
                            const top = card.getBoundingClientRect().top;
                            const nt = next.getBoundingClientRect().top;
                            // coverage: from the next card entering until it settles beside this one
                            const covered = Math.min(1, Math.max(0, (innerHeight - nt) / (innerHeight - top)));
                            card.style.setProperty('--scs-b', covered.toFixed(3));
                        });
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <div class="scs-zone"><article class="scs-card" style="--i: 0">
                <h3>Starter</h3><b>$0 / month</b>
                <ul><li>2 rollout pipelines</li><li>3 regions</li><li>Weekly report</li></ul>
            </article></div>
            <div class="scs-zone"><article class="scs-card" style="--i: 1">
                <h3>Growth</h3><b>$49 / month</b>
                <ul><li>10 pipelines</li><li>12 regions</li><li>Canary + auto-rollback</li></ul>
            </article></div>
            <div class="scs-zone"><article class="scs-card" style="--i: 2">
                <h3>Scale</h3><b>$199 / month</b>
                <ul><li>Unlimited pipelines</li><li>24 regions</li><li>Progressive delivery + SLA</li></ul>
            </article></div>
            <div class="scs-zone"><article class="scs-card" style="--i: 3">
                <h3>Enterprise</h3><b>Custom</b>
                <ul><li>Private cloud</li><li>SOC 2</li><li>Dedicated engineer</li></ul>
            </article></div>
        </div>

        <style>
        .scs-zone { block-size: 88vh; }
        .scs-card { position: sticky; inset-block-start: calc(5.5rem + var(--i) * .875rem);
                    inline-size: min(100%, 34rem); margin-inline: auto; padding: 1.5rem;
                    border-radius: 1.5rem; background: #fff; border: 1px solid #e4e4ef;
                    scale: calc(1 - var(--scs-b, 0) * .06);
                    opacity: calc(1 - var(--scs-b, 0) * .55); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Pricing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import PlanStack from '@/components/PlanStack';

        export default function Pricing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <PlanStack />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // PlanStack.jsx — sticky stack cards: scroll piles the plans one under the next
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        const PLANS = [
          { id: 'starter', name: 'Starter', price: '$0 / month', perks: ['2 rollout pipelines', '3 regions', 'Weekly report'] },
          { id: 'growth', name: 'Growth', price: '$49 / month', perks: ['10 pipelines', '12 regions', 'Canary + auto-rollback'] },
          { id: 'scale', name: 'Scale', price: '$199 / month', perks: ['Unlimited pipelines', '24 regions', 'Progressive delivery + SLA'] },
          { id: 'enterprise', name: 'Enterprise', price: 'Custom', perks: ['Private cloud', 'SOC 2', 'Dedicated engineer'] },
        ];

        export default function PlanStack() {
          const cardRefs = useRef([]);

          // One rAF-throttled scroll pass: each card learns how far the next
          // one has covered it (--scs-b), CSS does the scale + dim. Cleanup
          // on unmount, so Livewire/Inertia navigation never leaks listeners.
          useEffect(() => {
            let frame = 0;
            const measure = () => {
              frame = 0;
              const cards = cardRefs.current;
              cards.forEach((card, i) => {
                const next = cards[i + 1];
                if (!card || !next) return;
                const top = card.getBoundingClientRect().top;
                const nt = next.getBoundingClientRect().top;
                const covered = Math.min(1, Math.max(0, (innerHeight - nt) / (innerHeight - top)));
                card.style.setProperty('--scs-b', covered.toFixed(3));
              });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          }, []);

          return (
            <>
              <style>{`
                .scs-zone { block-size: 88vh; }
                .scs-card { position: sticky; inset-block-start: calc(5.5rem + var(--i) * .875rem);
                            inline-size: min(100%, 34rem); margin-inline: auto; padding: 1.5rem;
                            border-radius: 1.5rem; background: #fff; border: 1px solid #e4e4ef;
                            box-shadow: 0 12px 32px rgba(23, 20, 48, .08);
                            scale: calc(1 - var(--scs-b, 0) * .06);
                            opacity: calc(1 - var(--scs-b, 0) * .55); }
                .scs-card h3 { margin: 0 0 .25rem; font-size: 1.125rem; }
                .scs-card b { display: block; margin-block-end: 1rem; font-size: 1.375rem; }
                .scs-card ul { margin: 0; padding-inline-start: 1.125rem; color: #55536b; line-height: 1.9; }
              `}</style>
              {PLANS.map((plan, i) => (
                <div className="scs-zone" key={plan.id}>
                  <article className="scs-card" style={{ '--i': i }} ref={(el) => { cardRefs.current[i] = el; }}>
                    <h3>{plan.name}</h3>
                    <b>{plan.price}</b>
                    <ul>{plan.perks.map((perk) => <li key={perk}>{perk}</li>)}</ul>
                  </article>
                </div>
              ))}
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- PlanStack.vue — sticky stack cards: scroll piles the plans one under the next -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const plans = [
          { id: 'starter', name: 'Starter', price: '$0 / month', perks: ['2 rollout pipelines', '3 regions', 'Weekly report'] },
          { id: 'growth', name: 'Growth', price: '$49 / month', perks: ['10 pipelines', '12 regions', 'Canary + auto-rollback'] },
          { id: 'scale', name: 'Scale', price: '$199 / month', perks: ['Unlimited pipelines', '24 regions', 'Progressive delivery + SLA'] },
          { id: 'enterprise', name: 'Enterprise', price: 'Custom', perks: ['Private cloud', 'SOC 2', 'Dedicated engineer'] },
        ];
        const cards = ref<HTMLElement[]>([]);
        const setCard = (i: number) => (el: unknown) => { cards.value[i] = el as HTMLElement; };

        // One rAF-throttled scroll pass sets each card's --scs-b (how far the
        // next card covers it); CSS does the scale + dim. Torn down on unmount.
        let frame = 0;
        const measure = () => {
          frame = 0;
          cards.value.forEach((card, i) => {
            const next = cards.value[i + 1];
            if (!card || !next) return;
            const top = card.getBoundingClientRect().top;
            const nt = next.getBoundingClientRect().top;
            const covered = Math.min(1, Math.max(0, (innerHeight - nt) / (innerHeight - top)));
            card.style.setProperty('--scs-b', covered.toFixed(3));
          });
        };
        const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };

        onMounted(() => {
          window.addEventListener('scroll', schedule, { passive: true });
          window.addEventListener('resize', schedule, { passive: true });
          measure();
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(frame);
          window.removeEventListener('scroll', schedule);
          window.removeEventListener('resize', schedule);
        });
        </script>

        <template>
          <div v-for="(plan, i) in plans" :key="plan.id" class="scs-zone">
            <article :ref="setCard(i)" class="scs-card" :style="{ '--i': i }">
              <h3>{{ plan.name }}</h3>
              <b>{{ plan.price }}</b>
              <ul><li v-for="perk in plan.perks" :key="perk">{{ perk }}</li></ul>
            </article>
          </div>
        </template>

        <style scoped>
        .scs-zone { block-size: 88vh; }
        .scs-card { position: sticky; inset-block-start: calc(5.5rem + var(--i) * .875rem);
                    inline-size: min(100%, 34rem); margin-inline: auto; padding: 1.5rem;
                    border-radius: 1.5rem; background: #fff; border: 1px solid #e4e4ef;
                    box-shadow: 0 12px 32px rgba(23, 20, 48, .08);
                    scale: calc(1 - var(--scs-b, 0) * .06);
                    opacity: calc(1 - var(--scs-b, 0) * .55); }
        .scs-card h3 { margin: 0 0 .25rem; font-size: 1.125rem; }
        .scs-card b { display: block; margin-block-end: 1rem; font-size: 1.375rem; }
        .scs-card ul { margin: 0; padding-inline-start: 1.125rem; color: #55536b; line-height: 1.9; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- PlanStack.svelte — sticky stack cards: scroll piles the plans one under the next -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const plans = [
            { id: 'starter', name: 'Starter', price: '$0 / month', perks: ['2 rollout pipelines', '3 regions', 'Weekly report'] },
            { id: 'growth', name: 'Growth', price: '$49 / month', perks: ['10 pipelines', '12 regions', 'Canary + auto-rollback'] },
            { id: 'scale', name: 'Scale', price: '$199 / month', perks: ['Unlimited pipelines', '24 regions', 'Progressive delivery + SLA'] },
            { id: 'enterprise', name: 'Enterprise', price: 'Custom', perks: ['Private cloud', 'SOC 2', 'Dedicated engineer'] },
          ];
          let cards: HTMLElement[] = $state([]);

          // One rAF-throttled scroll pass sets each card's --scs-b (how far the
          // next card covers it); CSS does the scale + dim. $effect cleans up.
          $effect(() => {
            let frame = 0;
            const measure = () => {
              frame = 0;
              cards.forEach((card, i) => {
                const next = cards[i + 1];
                if (!next) return;
                const top = card.getBoundingClientRect().top;
                const nt = next.getBoundingClientRect().top;
                const covered = Math.min(1, Math.max(0, (innerHeight - nt) / (innerHeight - top)));
                card.style.setProperty('--scs-b', covered.toFixed(3));
              });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          });
        </script>

        {#each plans as plan, i (plan.id)}
          <div class="scs-zone">
            <article bind:this={cards[i]} class="scs-card" style="--i: {i}">
              <h3>{plan.name}</h3>
              <b>{plan.price}</b>
              <ul>{#each plan.perks as perk (perk)}<li>{perk}</li>{/each}</ul>
            </article>
          </div>
        {/each}

        <style>
        .scs-zone { block-size: 88vh; }
        .scs-card { position: sticky; inset-block-start: calc(5.5rem + var(--i) * .875rem);
                    inline-size: min(100%, 34rem); margin-inline: auto; padding: 1.5rem;
                    border-radius: 1.5rem; background: #fff; border: 1px solid #e4e4ef;
                    box-shadow: 0 12px 32px rgba(23, 20, 48, .08);
                    scale: calc(1 - var(--scs-b, 0) * .06);
                    opacity: calc(1 - var(--scs-b, 0) * .55); }
        .scs-card h3 { margin: 0 0 .25rem; font-size: 1.125rem; }
        .scs-card b { display: block; margin-block-end: 1rem; font-size: 1.375rem; }
        .scs-card ul { margin: 0; padding-inline-start: 1.125rem; color: #55536b; line-height: 1.9; }
        </style>
        SVELTE,
        ],
    ],

    'horizontal-strip' => [
        'title' => ['fa' => 'نوار افقی اسکرولی', 'en' => 'Horizontal scroll strip'],
        'icon' => 'arrow-right',
        'oneLiner' => [
            'fa' => 'اسکرول عمودی، گالری افقی پروژه‌های «مریدین» را می‌راند: چرخ‌دنده‌های اسکرول به translate یک track چسبان بدل می‌شود و پنج کارت پروژه با تصویر SVG دست‌ساز یکی‌یکی رد می‌شوند — بدون حتی یک لحظه انتظار برای چرخ ماوس.',
            'en' => 'Vertical scroll drives Meridian’s horizontal project gallery: the scroll wheel becomes translate on a sticky track, sweeping five project cards with hand-drawn SVG scenes past the frame — never a moment spent waiting on a wheel.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'stage', 'type' => 'length', 'default' => "'320vh'", 'note' => [
                'fa' => 'ارتفاع استیج؛ فاصلهٔ اسکرولی که کل طول track در آن خرج می‌شود.',
                'en' => 'The stage height; the scroll distance spent on the track’s full length.',
            ]],
            ['name' => '--hst-p', 'type' => '0 → 1', 'default' => "'stage progress'", 'note' => [
                'fa' => 'پیشرفت استیج نسبت به موقعیت خودش؛ یک متغیر که کل حرکت از آن ساخته می‌شود.',
                'en' => 'The stage’s progress measured against its own position; one variable every motion derives from.',
            ]],
            ['name' => '--hst-shift', 'type' => 'px', 'default' => "'track − viewport'", 'note' => [
                'fa' => 'مسیر خالص جابه‌جایی؛ در هر resize دوباره اندازه‌گیری می‌شود تا سرریز صفر بماند.',
                'en' => 'The net travel distance; re-measured on every resize so overflow stays at zero.',
            ]],
            ['name' => '--hst-dir', 'type' => '±1', 'default' => "'dir'", 'note' => [
                'fa' => 'جهت حرکت؛ در RTL برعکس می‌شود تا track همیشه به سمت آیندهٔ داستان باز شود.',
                'en' => 'The travel sign; flips in RTL so the track always opens toward the story’s future.',
            ]],
            ['name' => 'progress', 'type' => 'readout', 'default' => "'%'", 'note' => [
                'fa' => 'خوانش درصدی زیر قاب؛ همان متغیر پیشرفت، بدون محاسبهٔ دوم.',
                'en' => 'The percentage readout under the frame; the same progress variable, no second math.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- نوار افقی اسکرولی: اسکرول عمودی، track افقی را می‌راند --}}
        <div class="hst" x-data="{
                p: 0,
                stop: () => {},
                init() {
                    const root = this.$root, stage = root.querySelector('.hst-stage'),
                          viewport = root.querySelector('.hst-view'), track = root.querySelector('.hst-track');
                    let frame = 0, last = -1;
                    const measure = () => {
                        frame = 0;
                        const r = stage.getBoundingClientRect();
                        const span = Math.max(r.height - window.innerHeight, 1);
                        const p = Math.min(1, Math.max(0, -r.top / span));
                        const shift = Math.max(track.scrollWidth - viewport.clientWidth, 0);
                        root.style.setProperty('--hst-shift', shift + 'px');
                        const v = Math.round(p * 1000) / 1000;
                        if (v !== last) { last = v; this.p = v; root.style.setProperty('--hst-p', v); }
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <div class="hst-stage">
                <div class="hst-view">
                    <div class="hst-track">
                        <article class="hst-card"><svg viewBox="0 0 320 200">…</svg>
                            <h3>خط‌لولهٔ اطلس</h3><small>فرانکفورت · زنده</small></article>
                        <article class="hst-card"><svg viewBox="0 0 320 200">…</svg>
                            <h3>استقرار شفق</h3><small>استکهلم · کاناری</small></article>
                        <article class="hst-card"><svg viewBox="0 0 320 200">…</svg>
                            <h3>انتشار بندر</h3><small>سنگاپور · زنده</small></article>
                    </div>
                </div>
            </div>
            <p class="hst-readout">پیشرفت: <span x-text="Math.round(p * 100)">۰</span>٪</p>
        </div>

        <style>
        .hst-stage { block-size: 320vh; }
        .hst-view { position: sticky; inset-block-start: 10vh; block-size: 80vh;
                    overflow: clip; border-radius: 1.5rem; }
        .hst-track { --hst-p: 0; --hst-shift: 0px; --hst-dir: -1;
                     display: flex; gap: 1.25rem; padding-inline: 1.5rem; inline-size: max-content;
                     translate: calc(var(--hst-p) * var(--hst-shift) * var(--hst-dir)) 0; }
        [dir="rtl"] .hst-track { --hst-dir: 1; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Horizontal scroll strip: vertical scroll drives the horizontal track --}}
        <div class="hst" x-data="{
                p: 0,
                stop: () => {},
                init() {
                    const root = this.$root, stage = root.querySelector('.hst-stage'),
                          viewport = root.querySelector('.hst-view'), track = root.querySelector('.hst-track');
                    let frame = 0, last = -1;
                    const measure = () => {
                        frame = 0;
                        const r = stage.getBoundingClientRect();
                        const span = Math.max(r.height - window.innerHeight, 1);
                        const p = Math.min(1, Math.max(0, -r.top / span));
                        const shift = Math.max(track.scrollWidth - viewport.clientWidth, 0);
                        root.style.setProperty('--hst-shift', shift + 'px');
                        const v = Math.round(p * 1000) / 1000;
                        if (v !== last) { last = v; this.p = v; root.style.setProperty('--hst-p', v); }
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <div class="hst-stage">
                <div class="hst-view">
                    <div class="hst-track">
                        <article class="hst-card"><svg viewBox="0 0 320 200">…</svg>
                            <h3>Atlas pipeline</h3><small>Frankfurt · live</small></article>
                        <article class="hst-card"><svg viewBox="0 0 320 200">…</svg>
                            <h3>Aurora deploy</h3><small>Stockholm · canary</small></article>
                        <article class="hst-card"><svg viewBox="0 0 320 200">…</svg>
                            <h3>Harbor release</h3><small>Singapore · live</small></article>
                    </div>
                </div>
            </div>
            <p class="hst-readout">Progress: <span x-text="Math.round(p * 100)">0</span>%</p>
        </div>

        <style>
        .hst-stage { block-size: 320vh; }
        .hst-view { position: sticky; inset-block-start: 10vh; block-size: 80vh;
                    overflow: clip; border-radius: 1.5rem; }
        .hst-track { --hst-p: 0; --hst-shift: 0px; --hst-dir: -1;
                     display: flex; gap: 1.25rem; padding-inline: 1.5rem; inline-size: max-content;
                     translate: calc(var(--hst-p) * var(--hst-shift) * var(--hst-dir)) 0; }
        [dir="rtl"] .hst-track { --hst-dir: 1; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Projects.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ProjectStrip from '@/components/ProjectStrip';

        export default function Projects() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ProjectStrip />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ProjectStrip.jsx — vertical scroll drives a horizontal project track
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PROJECTS = [
          { id: 'atlas', name: 'Atlas pipeline', region: 'Frankfurt', state: 'live' },
          { id: 'aurora', name: 'Aurora deploy', region: 'Stockholm', state: 'canary' },
          { id: 'harbor', name: 'Harbor release', region: 'Singapore', state: 'live' },
          { id: 'cascade', name: 'Cascade flags', region: 'Vancouver', state: 'scheduled' },
          { id: 'polar', name: 'Polar rollout', region: 'Oslo', state: 'live' },
        ];

        export default function ProjectStrip() {
          const rootRef = useRef(null);
          const [pct, setPct] = useState(0);

          // Progress measured against the stage's own rect; travel re-measured
          // on resize so overflow never appears. Everything else is one CSS var.
          useEffect(() => {
            const root = rootRef.current;
            const stage = root.querySelector('.hst-stage');
            const viewport = root.querySelector('.hst-view');
            const track = root.querySelector('.hst-track');
            let frame = 0;
            const measure = () => {
              frame = 0;
              const r = stage.getBoundingClientRect();
              const span = Math.max(r.height - window.innerHeight, 1);
              const p = Math.min(1, Math.max(0, -r.top / span));
              root.style.setProperty('--hst-shift', Math.max(track.scrollWidth - viewport.clientWidth, 0) + 'px');
              root.style.setProperty('--hst-p', p.toFixed(3));
              setPct(Math.round(p * 100));
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          }, []);

          return (
            <div className="hst" ref={rootRef}>
              <style>{`
                .hst-stage { block-size: 320vh; }
                .hst-view { position: sticky; inset-block-start: 10vh; block-size: 80vh; overflow: clip;
                            border-radius: 1.5rem; }
                .hst-track { --hst-p: 0; --hst-shift: 0px; --hst-dir: -1; display: flex; gap: 1.25rem;
                             padding-inline: 1.5rem; inline-size: max-content; align-items: center;
                             translate: calc(var(--hst-p) * var(--hst-shift) * var(--hst-dir)) 0; }
                [dir='rtl'] .hst-track { --hst-dir: 1; }
                .hst-card { inline-size: min(78vw, 26rem); border-radius: 1.25rem; background: #fff;
                            border: 1px solid #e4e4ef; overflow: clip; }
                .hst-card svg { display: block; inline-size: 100%; }
                .hst-card figcaption { padding: 1rem 1.25rem; }
                .hst-readout { font: 600 .9rem system-ui; }
              `}</style>
              <div className="hst-stage">
                <div className="hst-view">
                  <div className="hst-track">
                    {PROJECTS.map((project) => (
                      <figure className="hst-card" key={project.id}>
                        <svg viewBox="0 0 320 200" role="img" aria-label={project.name}>
                          <rect width="320" height="200" fill="#eef0fb" />
                          <circle cx="60" cy="120" r="16" fill="#5647e6" />
                          <circle cx="160" cy="70" r="16" fill="#2f9e6e" />
                          <circle cx="260" cy="130" r="16" fill="#c2530f" />
                          <path d="M74 113 L148 77 M174 77 L248 123" stroke="#5647e6" strokeWidth="4" fill="none" />
                        </svg>
                        <figcaption>
                          <h3 style={{ margin: 0 }}>{project.name}</h3>
                          <small>{project.region} · {project.state}</small>
                        </figcaption>
                      </figure>
                    ))}
                  </div>
                </div>
              </div>
              <p className="hst-readout">Progress: {pct}%</p>
            </div>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ProjectStrip.vue — vertical scroll drives a horizontal project track -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const projects = [
          { id: 'atlas', name: 'Atlas pipeline', region: 'Frankfurt', state: 'live' },
          { id: 'aurora', name: 'Aurora deploy', region: 'Stockholm', state: 'canary' },
          { id: 'harbor', name: 'Harbor release', region: 'Singapore', state: 'live' },
          { id: 'cascade', name: 'Cascade flags', region: 'Vancouver', state: 'scheduled' },
          { id: 'polar', name: 'Polar rollout', region: 'Oslo', state: 'live' },
        ];
        const root = ref<HTMLElement | null>(null);
        const pct = ref(0);

        // Progress measured against the stage's own rect; travel re-measured on
        // resize so overflow never appears. Everything else is one CSS var.
        let frame = 0;
        const measure = () => {
          frame = 0;
          const el = root.value;
          if (!el) return;
          const stage = el.querySelector('.hst-stage') as HTMLElement;
          const viewport = el.querySelector('.hst-view') as HTMLElement;
          const track = el.querySelector('.hst-track') as HTMLElement;
          const r = stage.getBoundingClientRect();
          const span = Math.max(r.height - window.innerHeight, 1);
          const p = Math.min(1, Math.max(0, -r.top / span));
          el.style.setProperty('--hst-shift', Math.max(track.scrollWidth - viewport.clientWidth, 0) + 'px');
          el.style.setProperty('--hst-p', p.toFixed(3));
          pct.value = Math.round(p * 100);
        };
        const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };

        onMounted(() => {
          window.addEventListener('scroll', schedule, { passive: true });
          window.addEventListener('resize', schedule, { passive: true });
          measure();
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(frame);
          window.removeEventListener('scroll', schedule);
          window.removeEventListener('resize', schedule);
        });
        </script>

        <template>
          <div ref="root" class="hst">
            <div class="hst-stage">
              <div class="hst-view">
                <div class="hst-track">
                  <figure v-for="project in projects" :key="project.id" class="hst-card">
                    <svg viewBox="0 0 320 200" role="img" :aria-label="project.name">
                      <rect width="320" height="200" fill="#eef0fb" />
                      <circle cx="60" cy="120" r="16" fill="#5647e6" />
                      <circle cx="160" cy="70" r="16" fill="#2f9e6e" />
                      <circle cx="260" cy="130" r="16" fill="#c2530f" />
                      <path d="M74 113 L148 77 M174 77 L248 123" stroke="#5647e6" stroke-width="4" fill="none" />
                    </svg>
                    <figcaption>
                      <h3>{{ project.name }}</h3>
                      <small>{{ project.region }} · {{ project.state }}</small>
                    </figcaption>
                  </figure>
                </div>
              </div>
            </div>
            <p class="hst-readout">Progress: {{ pct }}%</p>
          </div>
        </template>

        <style scoped>
        .hst-stage { block-size: 320vh; }
        .hst-view { position: sticky; inset-block-start: 10vh; block-size: 80vh; overflow: clip;
                    border-radius: 1.5rem; }
        .hst-track { --hst-p: 0; --hst-shift: 0px; --hst-dir: -1; display: flex; gap: 1.25rem;
                     padding-inline: 1.5rem; inline-size: max-content; align-items: center;
                     translate: calc(var(--hst-p) * var(--hst-shift) * var(--hst-dir)) 0; }
        :global([dir='rtl']) .hst-track { --hst-dir: 1; }
        .hst-card { inline-size: min(78vw, 26rem); border-radius: 1.25rem; background: #fff;
                    border: 1px solid #e4e4ef; overflow: clip; margin: 0; }
        .hst-card svg { display: block; inline-size: 100%; }
        .hst-card figcaption { padding: 1rem 1.25rem; }
        .hst-card h3 { margin: 0; }
        .hst-readout { font: 600 .9rem system-ui; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ProjectStrip.svelte — vertical scroll drives a horizontal project track -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const projects = [
            { id: 'atlas', name: 'Atlas pipeline', region: 'Frankfurt', state: 'live' },
            { id: 'aurora', name: 'Aurora deploy', region: 'Stockholm', state: 'canary' },
            { id: 'harbor', name: 'Harbor release', region: 'Singapore', state: 'live' },
            { id: 'cascade', name: 'Cascade flags', region: 'Vancouver', state: 'scheduled' },
            { id: 'polar', name: 'Polar rollout', region: 'Oslo', state: 'live' },
          ];
          let root: HTMLElement;
          let pct = $state(0);

          // Progress measured against the stage's own rect; travel re-measured on
          // resize so overflow never appears. Everything else is one CSS var.
          $effect(() => {
            let frame = 0;
            const measure = () => {
              frame = 0;
              const stage = root.querySelector('.hst-stage') as HTMLElement;
              const viewport = root.querySelector('.hst-view') as HTMLElement;
              const track = root.querySelector('.hst-track') as HTMLElement;
              const r = stage.getBoundingClientRect();
              const span = Math.max(r.height - window.innerHeight, 1);
              const p = Math.min(1, Math.max(0, -r.top / span));
              root.style.setProperty('--hst-shift', Math.max(track.scrollWidth - viewport.clientWidth, 0) + 'px');
              root.style.setProperty('--hst-p', p.toFixed(3));
              pct = Math.round(p * 100);
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          });
        </script>

        <div bind:this={root} class="hst">
          <div class="hst-stage">
            <div class="hst-view">
              <div class="hst-track">
                {#each projects as project (project.id)}
                  <figure class="hst-card">
                    <svg viewBox="0 0 320 200" role="img" aria-label={project.name}>
                      <rect width="320" height="200" fill="#eef0fb" />
                      <circle cx="60" cy="120" r="16" fill="#5647e6" />
                      <circle cx="160" cy="70" r="16" fill="#2f9e6e" />
                      <circle cx="260" cy="130" r="16" fill="#c2530f" />
                      <path d="M74 113 L148 77 M174 77 L248 123" stroke="#5647e6" stroke-width="4" fill="none" />
                    </svg>
                    <figcaption>
                      <h3>{project.name}</h3>
                      <small>{project.region} · {project.state}</small>
                    </figcaption>
                  </figure>
                {/each}
              </div>
            </div>
          </div>
          <p class="hst-readout">Progress: {pct}%</p>
        </div>

        <style>
        .hst-stage { block-size: 320vh; }
        .hst-view { position: sticky; inset-block-start: 10vh; block-size: 80vh; overflow: clip;
                    border-radius: 1.5rem; }
        .hst-track { --hst-p: 0; --hst-shift: 0px; --hst-dir: -1; display: flex; gap: 1.25rem;
                     padding-inline: 1.5rem; inline-size: max-content; align-items: center;
                     translate: calc(var(--hst-p) * var(--hst-shift) * var(--hst-dir)) 0; }
        [dir='rtl'] .hst-track { --hst-dir: 1; }
        .hst-card { inline-size: min(78vw, 26rem); border-radius: 1.25rem; background: #fff;
                    border: 1px solid #e4e4ef; overflow: clip; margin: 0; }
        .hst-card svg { display: block; inline-size: 100%; }
        .hst-card figcaption { padding: 1rem 1.25rem; }
        .hst-card h3 { margin: 0; }
        .hst-readout { font: 600 .9rem system-ui; }
        </style>
        SVELTE,
        ],
    ],

    'parallax-depth' => [
        'title' => ['fa' => 'پارالاکس چندلایه', 'en' => 'Parallax depth'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'یک روزِ انتشار مریدین در سه لایه با سرعت‌های متفاوت: اعداد شبح‌وارِ کند در پس‌زمینه، روایت میانی از دوبلین تا سائوپائولو، و تراشه‌های پیش‌رو که تندتر رد می‌شوند — عمق، از یک متغیر پیشرفت و چند calc ساخته می‌شود.',
            'en' => 'One Meridian rollout day in three speeds: slow ghost numerals in the back, the Dublin-to-São-Paulo narrative mid-frame, and foreground chips racing past — depth built from a single progress variable and a handful of calcs.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'stage', 'type' => 'length', 'default' => "'340vh'", 'note' => [
                'fa' => 'ارتفاع استیج؛ صحنهٔ چسبان در تمام طول آن روی صفحه می‌ماند.',
                'en' => 'The stage height; the sticky scene stays on screen through all of it.',
            ]],
            ['name' => 'bg · fg speed', 'type' => 'vh', 'default' => "'8 · 30'", 'note' => [
                'fa' => 'جابه‌جایی پس‌زمینه و پیش‌زمینه از همان یک پیشرفت؛ لایهٔ میانی (روایت) ثابت می‌ماند و فقط تعویض می‌شود.',
                'en' => 'Back and front travel derived from that one progress value; the middle layer (the story) stays put and only crossfades.',
            ]],
            ['name' => 'beats', 'type' => 'count', 'default' => "'3'", 'note' => [
                'fa' => 'سه ضرب روایت؛ هر ضرب در ±۱۲٪ پیشرفت کامل روشن و تا ±۳۰٪ محو می‌شود.',
                'en' => 'Three narrative beats; each fully lit within ±12% of its centre and gone by ±30%.',
            ]],
            ['name' => '--pdz-on', 'type' => '0 → 1', 'default' => "'per beat'", 'note' => [
                'fa' => 'روشنایی هر ضرب که در همان حلقهٔ اسکرول نوشته می‌شود؛ به‌همراه یک بلندشدن ظریف هنگام ظهور.',
                'en' => 'Each beat’s brightness, written in the same scroll pass; paired with a slight rise as it appears.',
            ]],
            ['name' => 'layers', 'type' => 'pointer-events', 'default' => "'none'", 'note' => [
                'fa' => 'لایه‌های تزئینی روی روایت کلیک نمی‌گیرند؛ فقط لایهٔ میجان تعامل دارد.',
                'en' => 'Decorative layers swallow no clicks; only the story layer is interactive.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- پارالاکس چندلایه: سه سرعت، یک متغیر پیشرفت --}}
        <div class="pdz" x-data="{
                stop: () => {},
                init() {
                    const root = this.$root, stage = root.querySelector('.pdz-stage'),
                          beats = [...root.querySelectorAll('.pdz-beat')];
                    let frame = 0, last = -1;
                    const centers = beats.map((_, i) => (i + .5) / beats.length);
                    const measure = () => {
                        frame = 0;
                        const r = stage.getBoundingClientRect();
                        const span = Math.max(r.height - window.innerHeight, 1);
                        const p = Math.min(1, Math.max(0, -r.top / span));
                        beats.forEach((beat, i) => {
                            const on = Math.min(1, Math.max(0, (.3 - Math.abs(p - centers[i])) / .18));
                            beat.style.setProperty('--pdz-on', on.toFixed(3));
                        });
                        const v = Math.round(p * 1000) / 1000;
                        if (v !== last) { last = v; root.style.setProperty('--pdz-p', v); }
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <div class="pdz-stage">
                <div class="pdz-view">
                    <div class="pdz-bg"><span>۰۱</span><span>۰۲</span><span>۰۳</span></div>
                    <div class="pdz-mid">
                        <p class="pdz-beat"><small>۰۹:۱۵ · دوبلین</small><b>یک کامیت، شروع داستان</b></p>
                        <p class="pdz-beat"><small>۱۱:۴۰ · سنگاپور</small><b>کاناری روی ۵٪ ترافیک</b></p>
                        <p class="pdz-beat"><small>۱۶:۲۰ · سائوپائولو</small><b>انتشار کامل، بدون شب‌بیداری</b></p>
                    </div>
                    <div class="pdz-fg">
                        <span class="pdz-chip">+۲۳٪ فراوانی انتشار</span>
                        <span class="pdz-chip">۹۹٫۹۸٪ آپ‌تایم</span>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .pdz-stage { block-size: 340vh; }
        .pdz-view { position: sticky; inset-block-start: 0; block-size: 100svh; overflow: clip;
                    display: grid; place-items: center; }
        .pdz-bg, .pdz-fg { position: absolute; inset: 0; pointer-events: none; }
        .pdz-bg { --pdz-p: 0; font-weight: 800; font-size: 34vmin; color: #1d1b2e0d;
                  display: flex; justify-content: space-around; align-items: center;
                  translate: 0 calc(var(--pdz-p) * -8vh); }
        .pdz-fg { --pdz-p: 0; translate: 0 calc(var(--pdz-p) * -30vh); }
        .pdz-beat { --pdz-on: 0; grid-area: 1 / 1; text-align: center;
                    opacity: var(--pdz-on); translate: 0 calc((1 - var(--pdz-on)) * 1.5rem); }
        .pdz-chip { position: absolute; padding: .5rem 1rem; border-radius: 999px;
                    background: #fff; font-weight: 600; font-size: .85rem; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Multi-layer parallax: three speeds, one progress variable --}}
        <div class="pdz" x-data="{
                stop: () => {},
                init() {
                    const root = this.$root, stage = root.querySelector('.pdz-stage'),
                          beats = [...this.$root.querySelectorAll('.pdz-beat')];
                    let frame = 0, last = -1;
                    const centers = beats.map((_, i) => (i + .5) / beats.length);
                    const measure = () => {
                        frame = 0;
                        const r = stage.getBoundingClientRect();
                        const span = Math.max(r.height - window.innerHeight, 1);
                        const p = Math.min(1, Math.max(0, -r.top / span));
                        beats.forEach((beat, i) => {
                            const on = Math.min(1, Math.max(0, (.3 - Math.abs(p - centers[i])) / .18));
                            beat.style.setProperty('--pdz-on', on.toFixed(3));
                        });
                        const v = Math.round(p * 1000) / 1000;
                        if (v !== last) { last = v; root.style.setProperty('--pdz-p', v); }
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <div class="pdz-stage">
                <div class="pdz-view">
                    <div class="pdz-bg"><span>01</span><span>02</span><span>03</span></div>
                    <div class="pdz-mid">
                        <p class="pdz-beat"><small>09:15 · Dublin</small><b>One commit starts the story</b></p>
                        <p class="pdz-beat"><small>11:40 · Singapore</small><b>Canary on 5% of traffic</b></p>
                        <p class="pdz-beat"><small>16:20 · São Paulo</small><b>Full rollout, nobody on call</b></p>
                    </div>
                    <div class="pdz-fg">
                        <span class="pdz-chip">+23% deploy frequency</span>
                        <span class="pdz-chip">99.98% uptime</span>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .pdz-stage { block-size: 340vh; }
        .pdz-view { position: sticky; inset-block-start: 0; block-size: 100svh; overflow: clip;
                    display: grid; place-items: center; }
        .pdz-bg, .pdz-fg { position: absolute; inset: 0; pointer-events: none; }
        .pdz-bg { --pdz-p: 0; font-weight: 800; font-size: 34vmin; color: #1d1b2e0d;
                  display: flex; justify-content: space-around; align-items: center;
                  translate: 0 calc(var(--pdz-p) * -8vh); }
        .pdz-fg { --pdz-p: 0; translate: 0 calc(var(--pdz-p) * -30vh); }
        .pdz-beat { --pdz-on: 0; grid-area: 1 / 1; text-align: center;
                    opacity: var(--pdz-on); translate: 0 calc((1 - var(--pdz-on)) * 1.5rem); }
        .pdz-chip { position: absolute; padding: .5rem 1rem; border-radius: 999px;
                    background: #fff; font-weight: 600; font-size: .85rem; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/RolloutDay.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import RolloutDay from '@/components/RolloutDay';

        export default function Page() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <RolloutDay />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // RolloutDay.jsx — multi-layer parallax: three speeds, one progress variable
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        const BEATS = [
          { at: '09:15', city: 'Dublin', line: 'One commit starts the story' },
          { at: '11:40', city: 'Singapore', line: 'Canary on 5% of traffic' },
          { at: '16:20', city: 'São Paulo', line: 'Full rollout, nobody on call' },
        ];

        export default function RolloutDay() {
          const rootRef = useRef(null);

          // One scroll pass: stage progress against its own rect drives the
          // two drifting layers; each beat's brightness is written per element.
          useEffect(() => {
            const root = rootRef.current;
            const stage = root.querySelector('.pdz-stage');
            const beats = [...root.querySelectorAll('.pdz-beat')];
            const centers = beats.map((_, i) => (i + 0.5) / beats.length);
            let frame = 0;
            const measure = () => {
              frame = 0;
              const r = stage.getBoundingClientRect();
              const span = Math.max(r.height - window.innerHeight, 1);
              const p = Math.min(1, Math.max(0, -r.top / span));
              root.style.setProperty('--pdz-p', p.toFixed(3));
              beats.forEach((beat, i) => {
                const on = Math.min(1, Math.max(0, (0.3 - Math.abs(p - centers[i])) / 0.18));
                beat.style.setProperty('--pdz-on', on.toFixed(3));
              });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          }, []);

          return (
            <div className="pdz" ref={rootRef}>
              <style>{`
                .pdz-stage { block-size: 340vh; }
                .pdz-view { position: sticky; inset-block-start: 0; block-size: 100svh; overflow: clip;
                            display: grid; place-items: center; background: #f4f4fa; }
                .pdz-bg, .pdz-fg { position: absolute; inset: 0; pointer-events: none; }
                .pdz-bg { --pdz-p: 0; font-weight: 800; font-size: 34vmin; color: rgba(29, 27, 46, .05);
                          display: flex; justify-content: space-around; align-items: center;
                          translate: 0 calc(var(--pdz-p) * -8vh); }
                .pdz-mid { display: grid; }
                .pdz-beat { --pdz-on: 0; grid-area: 1 / 1; margin: 0; text-align: center;
                            opacity: var(--pdz-on); translate: 0 calc((1 - var(--pdz-on)) * 1.5rem); }
                .pdz-beat small { display: block; font-size: .85rem; letter-spacing: .08em; color: #5c5a75; }
                .pdz-beat b { font-size: clamp(1.5rem, 4.5vw, 2.6rem); }
                .pdz-fg { --pdz-p: 0; translate: 0 calc(var(--pdz-p) * -30vh); }
                .pdz-chip { position: absolute; padding: .5rem 1rem; border-radius: 999px; background: #fff;
                            font: 600 .85rem system-ui; box-shadow: 0 8px 22px rgba(24, 20, 52, .12); }
                .pdz-chip:nth-child(1) { inset-inline-start: 12%; inset-block-start: 24%; }
                .pdz-chip:nth-child(2) { inset-inline-end: 10%; inset-block-end: 28%; }
              `}</style>
              <div className="pdz-stage">
                <div className="pdz-view">
                  <div className="pdz-bg" aria-hidden="true">
                    <span>01</span><span>02</span><span>03</span>
                  </div>
                  <div className="pdz-mid">
                    {BEATS.map((beat) => (
                      <p className="pdz-beat" key={beat.city}>
                        <small>{beat.at} · {beat.city}</small>
                        <b>{beat.line}</b>
                      </p>
                    ))}
                  </div>
                  <div className="pdz-fg" aria-hidden="true">
                    <span className="pdz-chip">+23% deploy frequency</span>
                    <span className="pdz-chip">99.98% uptime</span>
                  </div>
                </div>
              </div>
            </div>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- RolloutDay.vue — multi-layer parallax: three speeds, one progress variable -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const beats = [
          { at: '09:15', city: 'Dublin', line: 'One commit starts the story' },
          { at: '11:40', city: 'Singapore', line: 'Canary on 5% of traffic' },
          { at: '16:20', city: 'São Paulo', line: 'Full rollout, nobody on call' },
        ];
        const root = ref<HTMLElement | null>(null);

        // One scroll pass: stage progress against its own rect drives the two
        // drifting layers; each beat's brightness is written per element.
        let frame = 0;
        const measure = () => {
          frame = 0;
          const el = root.value;
          if (!el) return;
          const stage = el.querySelector('.pdz-stage') as HTMLElement;
          const nodes = [...el.querySelectorAll('.pdz-beat')] as HTMLElement[];
          const centers = nodes.map((_, i) => (i + 0.5) / nodes.length);
          const r = stage.getBoundingClientRect();
          const span = Math.max(r.height - window.innerHeight, 1);
          const p = Math.min(1, Math.max(0, -r.top / span));
          el.style.setProperty('--pdz-p', p.toFixed(3));
          nodes.forEach((beat, i) => {
            const on = Math.min(1, Math.max(0, (0.3 - Math.abs(p - centers[i])) / 0.18));
            beat.style.setProperty('--pdz-on', on.toFixed(3));
          });
        };
        const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };

        onMounted(() => {
          window.addEventListener('scroll', schedule, { passive: true });
          window.addEventListener('resize', schedule, { passive: true });
          measure();
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(frame);
          window.removeEventListener('scroll', schedule);
          window.removeEventListener('resize', schedule);
        });
        </script>

        <template>
          <div ref="root" class="pdz">
            <div class="pdz-stage">
              <div class="pdz-view">
                <div class="pdz-bg" aria-hidden="true"><span>01</span><span>02</span><span>03</span></div>
                <div class="pdz-mid">
                  <p v-for="beat in beats" :key="beat.city" class="pdz-beat">
                    <small>{{ beat.at }} · {{ beat.city }}</small>
                    <b>{{ beat.line }}</b>
                  </p>
                </div>
                <div class="pdz-fg" aria-hidden="true">
                  <span class="pdz-chip">+23% deploy frequency</span>
                  <span class="pdz-chip">99.98% uptime</span>
                </div>
              </div>
            </div>
          </div>
        </template>

        <style scoped>
        .pdz-stage { block-size: 340vh; }
        .pdz-view { position: sticky; inset-block-start: 0; block-size: 100svh; overflow: clip;
                    display: grid; place-items: center; background: #f4f4fa; }
        .pdz-bg, .pdz-fg { position: absolute; inset: 0; pointer-events: none; }
        .pdz-bg { --pdz-p: 0; font-weight: 800; font-size: 34vmin; color: rgba(29, 27, 46, .05);
                  display: flex; justify-content: space-around; align-items: center;
                  translate: 0 calc(var(--pdz-p) * -8vh); }
        .pdz-mid { display: grid; }
        .pdz-beat { --pdz-on: 0; grid-area: 1 / 1; margin: 0; text-align: center;
                    opacity: var(--pdz-on); translate: 0 calc((1 - var(--pdz-on)) * 1.5rem); }
        .pdz-beat small { display: block; font-size: .85rem; letter-spacing: .08em; color: #5c5a75; }
        .pdz-beat b { font-size: clamp(1.5rem, 4.5vw, 2.6rem); }
        .pdz-fg { --pdz-p: 0; translate: 0 calc(var(--pdz-p) * -30vh); }
        .pdz-chip { position: absolute; padding: .5rem 1rem; border-radius: 999px; background: #fff;
                    font: 600 .85rem system-ui; box-shadow: 0 8px 22px rgba(24, 20, 52, .12); }
        .pdz-chip:nth-child(1) { inset-inline-start: 12%; inset-block-start: 24%; }
        .pdz-chip:nth-child(2) { inset-inline-end: 10%; inset-block-end: 28%; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- RolloutDay.svelte — multi-layer parallax: three speeds, one progress variable -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const beats = [
            { at: '09:15', city: 'Dublin', line: 'One commit starts the story' },
            { at: '11:40', city: 'Singapore', line: 'Canary on 5% of traffic' },
            { at: '16:20', city: 'São Paulo', line: 'Full rollout, nobody on call' },
          ];
          let root: HTMLElement;

          // One scroll pass: stage progress against its own rect drives the two
          // drifting layers; each beat's brightness is written per element.
          $effect(() => {
            let frame = 0;
            const measure = () => {
              frame = 0;
              const stage = root.querySelector('.pdz-stage') as HTMLElement;
              const nodes = [...root.querySelectorAll('.pdz-beat')] as HTMLElement[];
              const centers = nodes.map((_, i) => (i + 0.5) / nodes.length);
              const r = stage.getBoundingClientRect();
              const span = Math.max(r.height - window.innerHeight, 1);
              const p = Math.min(1, Math.max(0, -r.top / span));
              root.style.setProperty('--pdz-p', p.toFixed(3));
              nodes.forEach((beat, i) => {
                const on = Math.min(1, Math.max(0, (0.3 - Math.abs(p - centers[i])) / 0.18));
                beat.style.setProperty('--pdz-on', on.toFixed(3));
              });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          });
        </script>

        <div bind:this={root} class="pdz">
          <div class="pdz-stage">
            <div class="pdz-view">
              <div class="pdz-bg" aria-hidden="true"><span>01</span><span>02</span><span>03</span></div>
              <div class="pdz-mid">
                {#each beats as beat (beat.city)}
                  <p class="pdz-beat">
                    <small>{beat.at} · {beat.city}</small>
                    <b>{beat.line}</b>
                  </p>
                {/each}
              </div>
              <div class="pdz-fg" aria-hidden="true">
                <span class="pdz-chip">+23% deploy frequency</span>
                <span class="pdz-chip">99.98% uptime</span>
              </div>
            </div>
          </div>
        </div>

        <style>
        .pdz-stage { block-size: 340vh; }
        .pdz-view { position: sticky; inset-block-start: 0; block-size: 100svh; overflow: clip;
                    display: grid; place-items: center; background: #f4f4fa; }
        .pdz-bg, .pdz-fg { position: absolute; inset: 0; pointer-events: none; }
        .pdz-bg { --pdz-p: 0; font-weight: 800; font-size: 34vmin; color: rgba(29, 27, 46, .05);
                  display: flex; justify-content: space-around; align-items: center;
                  translate: 0 calc(var(--pdz-p) * -8vh); }
        .pdz-mid { display: grid; }
        .pdz-beat { --pdz-on: 0; grid-area: 1 / 1; margin: 0; text-align: center;
                    opacity: var(--pdz-on); translate: 0 calc((1 - var(--pdz-on)) * 1.5rem); }
        .pdz-beat small { display: block; font-size: .85rem; letter-spacing: .08em; color: #5c5a75; }
        .pdz-beat b { font-size: clamp(1.5rem, 4.5vw, 2.6rem); }
        .pdz-fg { --pdz-p: 0; translate: 0 calc(var(--pdz-p) * -30vh); }
        .pdz-chip { position: absolute; padding: .5rem 1rem; border-radius: 999px; background: #fff;
                    font: 600 .85rem system-ui; box-shadow: 0 8px 22px rgba(24, 20, 52, .12); }
        .pdz-chip:nth-child(1) { inset-inline-start: 12%; inset-block-start: 24%; }
        .pdz-chip:nth-child(2) { inset-inline-end: 10%; inset-block-end: 28%; }
        </style>
        SVELTE,
        ],
    ],

    'zoom-roadmap' => [
        'title' => ['fa' => 'نقشهٔ راه بزرگ‌نما', 'en' => 'Zoom roadmap'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'خط پیشرفت عمودی که با اسکرول پُر می‌شود و ایستگاه‌های ۲۰۲۷ مریدین را یکی‌یکی روشن می‌کند؛ هر ایستگاه از محویی و تارِ کوچک با zoom به اندازهٔ کامل می‌رسد و آخرین ایستگاه خودش کارت CTA است — دعوت، همان‌جا که سفر تمام می‌شود.',
            'en' => 'A vertical progress line that fills as you scroll, lighting Meridian’s 2027 stations one by one; each arrives from a small, blurred offset with a zoom to full size, and the last station is the CTA card itself — the invitation lands right where the journey ends.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'stage', 'type' => 'length', 'default' => "'300vh'", 'note' => [
                'fa' => 'ارتفاع استیج؛ تختهٔ نقشهٔ راه در تمام طول آن چسبان می‌ماند.',
                'en' => 'The stage height; the roadmap board stays sticky through all of it.',
            ]],
            ['name' => 'fill', 'type' => '%', 'default' => "'--zrm-p × 100%'", 'note' => [
                'fa' => 'ارتفاع پرشدن خط پیشرفت؛ همان یک متغیر پیشرفت، بدون اندازه‌گیری دوم.',
                'en' => 'The progress line’s fill height; the same single progress variable, no second measurement.',
            ]],
            ['name' => 'zoom-in', 'type' => 'transform', 'default' => "'scale .74 → 1'", 'note' => [
                'fa' => 'هر ایستگاه با بزرگ‌شدن از ۷۴٪ همراه با محوشدن بلور ۶ پیکسلی می‌نشیند.',
                'en' => 'Each station settles by growing from 74% while a 6px blur dissolves.',
            ]],
            ['name' => 'thresholds', 'type' => 'progress', 'default' => "'−.09 · .29 · .57 · .86'", 'note' => [
                'fa' => 'آستانهٔ روشن‌شدن هر ایستگاه؛ ایستگاه نخست از همان ابتدا روشن است و CTA کمی پیش از پایان.',
                'en' => 'Where each station lights; the first is lit from the very start, the CTA slightly before the end.',
            ]],
            ['name' => 'cta', 'type' => 'station', 'default' => "'last'", 'note' => [
                'fa' => 'آخرین ایستگاه کارت دعوت است با دکمهٔ زندهٔ Alpine — نه صرفاً یک برچسب.',
                'en' => 'The last station is the invitation card with a live Alpine button — not just a label.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- نقشهٔ راه بزرگ‌نما: خط پیشرفت پُر می‌شود، ایستگاه‌ها zoom می‌گیرند --}}
        <div class="zrm" x-data="{
                p: 0,
                stop: () => {},
                init() {
                    const root = this.$root, stage = root.querySelector('.zrm-stage'),
                          stops = [...root.querySelectorAll('.zrm-stop')];
                    const gate = (i) => i === 0 ? -0.09 : (i / (stops.length - 1)) * 0.86;
                    let frame = 0, last = -1;
                    const measure = () => {
                        frame = 0;
                        const r = stage.getBoundingClientRect();
                        const span = Math.max(r.height - window.innerHeight, 1);
                        const p = Math.min(1, Math.max(0, -r.top / span));
                        stops.forEach((stop, i) => {
                            const on = Math.min(1, Math.max(0, (p - gate(i)) / 0.09));
                            stop.style.setProperty('--zrm-on', on.toFixed(3));
                        });
                        const v = Math.round(p * 1000) / 1000;
                        if (v !== last) { last = v; this.p = v; root.style.setProperty('--zrm-p', v); }
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <div class="zrm-stage">
                <div class="zrm-board">
                    <span class="zrm-line" aria-hidden="true"><span class="zrm-fill"></span></span>
                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card"><b>موتور فلگ‌ها نسخهٔ ۲</b><small>دوبلین · منتشرشده</small></article>
                    </div>
                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card"><b>ایجنت‌های لبه، ۲۴ ناحیه</b><small>سنگاپور · در حال اجرا</small></article>
                    </div>
                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card"><b>نگهبان انتشار هوشمند</b><small>سئول · در ساخت</small></article>
                    </div>
                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card zrm-cta"><b>راه‌انداز رایگان</b>
                            <button type="button">شروع انتشار</button></article>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .zrm-stage { block-size: 300vh; }
        .zrm-board { position: sticky; inset-block-start: 10vh; display: grid; gap: 1.25rem; }
        .zrm-line { position: absolute; inset-block: 1rem; inset-inline-start: 1.06rem;
                    inline-size: 3px; border-radius: 999px; background: #e4e4ef; }
        .zrm-fill { --zrm-p: 0; display: block; block-size: calc(var(--zrm-p) * 100%);
                    border-radius: 999px; background: #5647e6; }
        .zrm-stop { --zrm-on: 0; position: relative; padding-inline-start: 2.75rem; }
        .zrm-node { position: absolute; inset-block-start: .4rem; inset-inline-start: .55rem;
                    inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: #c9c9dd; scale: var(--zrm-on); }
        .zrm-card { scale: calc(.74 + .26 * var(--zrm-on)); opacity: var(--zrm-on);
                    filter: blur(calc((1 - var(--zrm-on)) * 6px)); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Zoom roadmap: the line fills, the stations zoom in --}}
        <div class="zrm" x-data="{
                p: 0,
                stop: () => {},
                init() {
                    const root = this.$root, stage = root.querySelector('.zrm-stage'),
                          stops = [...root.querySelectorAll('.zrm-stop')];
                    const gate = (i) => i === 0 ? -0.09 : (i / (stops.length - 1)) * 0.86;
                    let frame = 0, last = -1;
                    const measure = () => {
                        frame = 0;
                        const r = stage.getBoundingClientRect();
                        const span = Math.max(r.height - window.innerHeight, 1);
                        const p = Math.min(1, Math.max(0, -r.top / span));
                        stops.forEach((stop, i) => {
                            const on = Math.min(1, Math.max(0, (p - gate(i)) / 0.09));
                            stop.style.setProperty('--zrm-on', on.toFixed(3));
                        });
                        const v = Math.round(p * 1000) / 1000;
                        if (v !== last) { last = v; this.p = v; root.style.setProperty('--zrm-p', v); }
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <div class="zrm-stage">
                <div class="zrm-board">
                    <span class="zrm-line" aria-hidden="true"><span class="zrm-fill"></span></span>
                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card"><b>Flags engine v2</b><small>Dublin · shipped</small></article>
                    </div>
                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card"><b>Edge agents, 24 regions</b><small>Singapore · building</small></article>
                    </div>
                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card"><b>Smart rollout guard</b><small>Seoul · in design</small></article>
                    </div>
                    <div class="zrm-stop">
                        <span class="zrm-node" aria-hidden="true"></span>
                        <article class="zrm-card zrm-cta"><b>Start free</b>
                            <button type="button">Roll out now</button></article>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .zrm-stage { block-size: 300vh; }
        .zrm-board { position: sticky; inset-block-start: 10vh; display: grid; gap: 1.25rem; }
        .zrm-line { position: absolute; inset-block: 1rem; inset-inline-start: 1.06rem;
                    inline-size: 3px; border-radius: 999px; background: #e4e4ef; }
        .zrm-fill { --zrm-p: 0; display: block; block-size: calc(var(--zrm-p) * 100%);
                    border-radius: 999px; background: #5647e6; }
        .zrm-stop { --zrm-on: 0; position: relative; padding-inline-start: 2.75rem; }
        .zrm-node { position: absolute; inset-block-start: .4rem; inset-inline-start: .55rem;
                    inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: #c9c9dd; scale: var(--zrm-on); }
        .zrm-card { scale: calc(.74 + .26 * var(--zrm-on)); opacity: var(--zrm-on);
                    filter: blur(calc((1 - var(--zrm-on)) * 6px)); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Roadmap.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import Roadmap2027 from '@/components/Roadmap2027';

        export default function Roadmap() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <Roadmap2027 />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // Roadmap2027.jsx — the line fills, the stations zoom in
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const STOPS = [
          { id: 'flags', title: 'Flags engine v2', meta: 'Dublin · shipped' },
          { id: 'edge', title: 'Edge agents, 24 regions', meta: 'Singapore · building' },
          { id: 'guard', title: 'Smart rollout guard', meta: 'Seoul · in design' },
        ];

        export default function Roadmap2027() {
          const rootRef = useRef(null);
          const [pct, setPct] = useState(0);

          // One scroll pass: stage progress fills the line; each stop's own
          // gate decides when it zooms in. Cleaned up on unmount.
          useEffect(() => {
            const root = rootRef.current;
            const stage = root.querySelector('.zrm-stage');
            const stops = [...root.querySelectorAll('.zrm-stop')];
            const gate = (i) => (i === 0 ? -0.09 : (i / (stops.length - 1)) * 0.86);
            let frame = 0;
            const measure = () => {
              frame = 0;
              const r = stage.getBoundingClientRect();
              const span = Math.max(r.height - window.innerHeight, 1);
              const p = Math.min(1, Math.max(0, -r.top / span));
              root.style.setProperty('--zrm-p', p.toFixed(3));
              setPct(Math.round(p * 100));
              stops.forEach((stop, i) => {
                const on = Math.min(1, Math.max(0, (p - gate(i)) / 0.09));
                stop.style.setProperty('--zrm-on', on.toFixed(3));
              });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          }, []);

          return (
            <div className="zrm" ref={rootRef}>
              <style>{`
                .zrm-stage { block-size: 300vh; }
                .zrm-board { position: sticky; inset-block-start: 10vh; display: grid; gap: 1.25rem;
                             max-inline-size: 34rem; }
                .zrm-line { position: absolute; inset-block: 1rem; inset-inline-start: 1.06rem; inline-size: 3px;
                            border-radius: 999px; background: #e4e4ef; }
                .zrm-fill { display: block; block-size: calc(var(--zrm-p, 0) * 100%);
                            border-radius: 999px; background: #5647e6; }
                .zrm-stop { --zrm-on: 0; position: relative; padding-inline-start: 2.75rem; }
                .zrm-node { position: absolute; inset-block-start: .4rem; inset-inline-start: .55rem; inline-size: .55rem;
                            aspect-ratio: 1; border-radius: 50%; background: #c9c9dd; scale: var(--zrm-on); }
                .zrm-stop[style*='--zrm-on: 1'] .zrm-node { background: #2f9e6e; }
                .zrm-card { padding: 1rem 1.25rem; border-radius: 1.25rem; background: #fff;
                            border: 1px solid #e4e4ef; scale: calc(.74 + .26 * var(--zrm-on, 0));
                            opacity: var(--zrm-on, 0); filter: blur(calc((1 - var(--zrm-on, 0)) * 6px)); }
                .zrm-cta { background: #5647e6; color: #fff; border-color: transparent; }
              `}</style>
              <div className="zrm-stage">
                <div className="zrm-board">
                  <span className="zrm-line" aria-hidden="true"><span className="zrm-fill" /></span>
                  {STOPS.map((stop) => (
                    <div className="zrm-stop" key={stop.id}>
                      <span className="zrm-node" aria-hidden="true" />
                      <article className="zrm-card"><b>{stop.title}</b><small>{stop.meta}</small></article>
                    </div>
                  ))}
                  <div className="zrm-stop">
                    <span className="zrm-node" aria-hidden="true" />
                    <article className="zrm-card zrm-cta">
                      <b>Start free</b>
                      <button type="button">Roll out now — {pct}% of the road behind you</button>
                    </article>
                  </div>
                </div>
              </div>
            </div>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- Roadmap2027.vue — the line fills, the stations zoom in -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const stops = [
          { id: 'flags', title: 'Flags engine v2', meta: 'Dublin · shipped' },
          { id: 'edge', title: 'Edge agents, 24 regions', meta: 'Singapore · building' },
          { id: 'guard', title: 'Smart rollout guard', meta: 'Seoul · in design' },
        ];
        const root = ref<HTMLElement | null>(null);
        const pct = ref(0);

        // One scroll pass: stage progress fills the line; each stop's own gate
        // decides when it zooms in. Cleaned up on unmount.
        let frame = 0;
        const measure = () => {
          frame = 0;
          const el = root.value;
          if (!el) return;
          const stage = el.querySelector('.zrm-stage') as HTMLElement;
          const nodes = [...el.querySelectorAll('.zrm-stop')] as HTMLElement[];
          const gate = (i: number) => (i === 0 ? -0.09 : (i / (nodes.length - 1)) * 0.86);
          const r = stage.getBoundingClientRect();
          const span = Math.max(r.height - window.innerHeight, 1);
          const p = Math.min(1, Math.max(0, -r.top / span));
          el.style.setProperty('--zrm-p', p.toFixed(3));
          pct.value = Math.round(p * 100);
          nodes.forEach((stop, i) => {
            const on = Math.min(1, Math.max(0, (p - gate(i)) / 0.09));
            stop.style.setProperty('--zrm-on', on.toFixed(3));
          });
        };
        const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };

        onMounted(() => {
          window.addEventListener('scroll', schedule, { passive: true });
          window.addEventListener('resize', schedule, { passive: true });
          measure();
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(frame);
          window.removeEventListener('scroll', schedule);
          window.removeEventListener('resize', schedule);
        });
        </script>

        <template>
          <div ref="root" class="zrm">
            <div class="zrm-stage">
              <div class="zrm-board">
                <span class="zrm-line" aria-hidden="true"><span class="zrm-fill"></span></span>
                <div v-for="stop in stops" :key="stop.id" class="zrm-stop">
                  <span class="zrm-node" aria-hidden="true"></span>
                  <article class="zrm-card"><b>{{ stop.title }}</b><small>{{ stop.meta }}</small></article>
                </div>
                <div class="zrm-stop">
                  <span class="zrm-node" aria-hidden="true"></span>
                  <article class="zrm-card zrm-cta">
                    <b>Start free</b>
                    <button type="button">Roll out now — {{ pct }}% of the road behind you</button>
                  </article>
                </div>
              </div>
            </div>
          </div>
        </template>

        <style scoped>
        .zrm-stage { block-size: 300vh; }
        .zrm-board { position: sticky; inset-block-start: 10vh; display: grid; gap: 1.25rem; max-inline-size: 34rem; }
        .zrm-line { position: absolute; inset-block: 1rem; inset-inline-start: 1.06rem; inline-size: 3px;
                    border-radius: 999px; background: #e4e4ef; }
        .zrm-fill { display: block; block-size: calc(var(--zrm-p, 0) * 100%); border-radius: 999px; background: #5647e6; }
        .zrm-stop { --zrm-on: 0; position: relative; padding-inline-start: 2.75rem; }
        .zrm-node { position: absolute; inset-block-start: .4rem; inset-inline-start: .55rem; inline-size: .55rem;
                    aspect-ratio: 1; border-radius: 50%; background: #c9c9dd; scale: var(--zrm-on); }
        .zrm-card { padding: 1rem 1.25rem; border-radius: 1.25rem; background: #fff; border: 1px solid #e4e4ef;
                    scale: calc(.74 + .26 * var(--zrm-on, 0)); opacity: var(--zrm-on, 0);
                    filter: blur(calc((1 - var(--zrm-on, 0)) * 6px)); }
        .zrm-cta { background: #5647e6; color: #fff; border-color: transparent; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- Roadmap2027.svelte — the line fills, the stations zoom in -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const stops = [
            { id: 'flags', title: 'Flags engine v2', meta: 'Dublin · shipped' },
            { id: 'edge', title: 'Edge agents, 24 regions', meta: 'Singapore · building' },
            { id: 'guard', title: 'Smart rollout guard', meta: 'Seoul · in design' },
          ];
          let root: HTMLElement;
          let pct = $state(0);

          // One scroll pass: stage progress fills the line; each stop's own
          // gate decides when it zooms in. $effect cleans up.
          $effect(() => {
            let frame = 0;
            const measure = () => {
              frame = 0;
              const stage = root.querySelector('.zrm-stage') as HTMLElement;
              const nodes = [...root.querySelectorAll('.zrm-stop')] as HTMLElement[];
              const gate = (i: number) => (i === 0 ? -0.09 : (i / (nodes.length - 1)) * 0.86);
              const r = stage.getBoundingClientRect();
              const span = Math.max(r.height - window.innerHeight, 1);
              const p = Math.min(1, Math.max(0, -r.top / span));
              root.style.setProperty('--zrm-p', p.toFixed(3));
              pct = Math.round(p * 100);
              nodes.forEach((stop, i) => {
                const on = Math.min(1, Math.max(0, (p - gate(i)) / 0.09));
                stop.style.setProperty('--zrm-on', on.toFixed(3));
              });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          });
        </script>

        <div bind:this={root} class="zrm">
          <div class="zrm-stage">
            <div class="zrm-board">
              <span class="zrm-line" aria-hidden="true"><span class="zrm-fill"></span></span>
              {#each stops as stop (stop.id)}
                <div class="zrm-stop">
                  <span class="zrm-node" aria-hidden="true"></span>
                  <article class="zrm-card"><b>{stop.title}</b><small>{stop.meta}</small></article>
                </div>
              {/each}
              <div class="zrm-stop">
                <span class="zrm-node" aria-hidden="true"></span>
                <article class="zrm-card zrm-cta">
                  <b>Start free</b>
                  <button type="button">Roll out now — {pct}% of the road behind you</button>
                </article>
              </div>
            </div>
          </div>
        </div>

        <style>
        .zrm-stage { block-size: 300vh; }
        .zrm-board { position: sticky; inset-block-start: 10vh; display: grid; gap: 1.25rem; max-inline-size: 34rem; }
        .zrm-line { position: absolute; inset-block: 1rem; inset-inline-start: 1.06rem; inline-size: 3px;
                    border-radius: 999px; background: #e4e4ef; }
        .zrm-fill { display: block; block-size: calc(var(--zrm-p, 0) * 100%); border-radius: 999px; background: #5647e6; }
        .zrm-stop { --zrm-on: 0; position: relative; padding-inline-start: 2.75rem; }
        .zrm-node { position: absolute; inset-block-start: .4rem; inset-inline-start: .55rem; inline-size: .55rem;
                    aspect-ratio: 1; border-radius: 50%; background: #c9c9dd; scale: var(--zrm-on); }
        .zrm-card { padding: 1rem 1.25rem; border-radius: 1.25rem; background: #fff; border: 1px solid #e4e4ef;
                    scale: calc(.74 + .26 * var(--zrm-on, 0)); opacity: var(--zrm-on, 0);
                    filter: blur(calc((1 - var(--zrm-on, 0)) * 6px)); }
        .zrm-cta { background: #5647e6; color: #fff; border-color: transparent; }
        </style>
        SVELTE,
        ],
    ],

    'reveal-gallery' => [
        'title' => ['fa' => 'گالری پرده‌بردار', 'en' => 'Clip reveal gallery'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'چهار ماجرای مشتریان مریدین در چیدمانی نامتقارن که هر تصویر SVG با ماسک متفاوتی باز می‌شود — inset، دایره، برشِ مورب، پردهٔ عمودی — و زیرنویس هر کادر از پایین سر می‌آید بالا؛ باز شدن، نسبت به موقعیت خودِ هر کادر سنجیده می‌شود.',
            'en' => 'Four Meridian customer stories in an asymmetric layout, each SVG scene opening through its own mask — inset, circle, a diagonal wipe, a vertical curtain — while the caption rises from below; every reveal measured against its own frame’s position.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'trigger', 'type' => 'viewport', 'default' => "'88vh'", 'note' => [
                'fa' => 'باز شدن از جایی شروع می‌شود که بالای کادر به ۸۸٪ ارتفاع دید برسد.',
                'en' => 'Opening starts once the frame’s top crosses 88% of the viewport height.',
            ]],
            ['name' => 'travel', 'type' => 'length', 'default' => "'50vh'", 'note' => [
                'fa' => 'فاصلهٔ اسکرول تا بازشدن کامل؛ باز و بسته‌شدن کاملاً برگشت‌پذیر است.',
                'en' => 'The scroll distance to a full open; the reveal runs equally backwards.',
            ]],
            ['name' => 'mask', 'type' => 'clip-path', 'default' => "'4 shapes'", 'note' => [
                'fa' => 'inset متقارن، دایره از نقطه‌ای نامرکز، برش موربِ polygon و پردهٔ عمودی — هر کادر امضای خودش را دارد.',
                'en' => 'A symmetric inset, an off-centre circle, a diagonal polygon wipe and a vertical curtain — every frame keeps its own signature.',
            ]],
            ['name' => 'caption', 'type' => 'translate', 'default' => "'1.25rem'", 'note' => [
                'fa' => 'زیرنویس با همان پیشرفت از پایین بلند می‌شود و محو می‌گردد.',
                'en' => 'The caption rises and fades on the very same progress value.',
            ]],
            ['name' => 'layout', 'type' => 'grid', 'default' => "'12-col asymmetric'", 'note' => [
                'fa' => 'ستون‌های روی‌هم‌افتاده با فاصلهٔ عمودی؛ در موبایل به یک ستونِ صاف فرو می‌ریزد.',
                'en' => 'Overlapping columns with vertical offsets; collapses to a clean single column on mobile.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- گالری پرده‌بردار: هر کادر با ماسک خودش باز می‌شود --}}
        <div class="rgv" x-data="{
                stop: () => {},
                init() {
                    const items = [...this.$root.querySelectorAll('.rgv-item')];
                    let frame = 0;
                    const measure = () => {
                        frame = 0;
                        items.forEach((item) => {
                            const top = item.getBoundingClientRect().top;
                            const r = Math.min(1, Math.max(0, (innerHeight * .88 - top) / (innerHeight * .5)));
                            item.style.setProperty('--rgv-r', r.toFixed(3));
                        });
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <figure class="rgv-item rgv-a">
                <svg viewBox="0 0 320 200">…</svg>
                <figcaption><b>نورث‌ویند لجستیک</b><small>کپنهاگ · ۳۸٪ فراوانی انتشار بیشتر</small></figcaption>
            </figure>
            <figure class="rgv-item rgv-b">
                <svg viewBox="0 0 320 200">…</svg>
                <figcaption><b>کایت پی‌منت</b><small>سنگاپور · کاناری در ۹ ناحیه بدون قطعی</small></figcaption>
            </figure>
        </div>

        <style>
        .rgv-item { --rgv-r: 0; }
        .rgv-item svg { display: block; inline-size: 100%; }
        .rgv-a svg { clip-path: inset(calc((1 - var(--rgv-r)) * 46%) calc((1 - var(--rgv-r)) * 44%) round 1.25rem); }
        .rgv-b svg { clip-path: circle(calc(6% + var(--rgv-r) * 72%) at 32% 42%); }
        .rgv-item figcaption { opacity: var(--rgv-r); translate: 0 calc((1 - var(--rgv-r)) * 1.25rem); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Clip reveal gallery: every frame opens through its own mask --}}
        <div class="rgv" x-data="{
                stop: () => {},
                init() {
                    const items = [...this.$root.querySelectorAll('.rgv-item')];
                    let frame = 0;
                    const measure = () => {
                        frame = 0;
                        items.forEach((item) => {
                            const top = item.getBoundingClientRect().top;
                            const r = Math.min(1, Math.max(0, (innerHeight * .88 - top) / (innerHeight * .5)));
                            item.style.setProperty('--rgv-r', r.toFixed(3));
                        });
                    };
                    const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
                    addEventListener('scroll', schedule, { passive: true });
                    addEventListener('resize', schedule, { passive: true });
                    this.stop = () => {
                        cancelAnimationFrame(frame);
                        removeEventListener('scroll', schedule);
                        removeEventListener('resize', schedule);
                    };
                    measure();
                },
                destroy() { this.stop(); },
            }">
            <figure class="rgv-item rgv-a">
                <svg viewBox="0 0 320 200">…</svg>
                <figcaption><b>Northwind Logistics</b><small>Copenhagen · 38% more deploy frequency</small></figcaption>
            </figure>
            <figure class="rgv-item rgv-b">
                <svg viewBox="0 0 320 200">…</svg>
                <figcaption><b>Kite Payments</b><small>Singapore · canary across 9 regions, zero downtime</small></figcaption>
            </figure>
        </div>

        <style>
        .rgv-item { --rgv-r: 0; }
        .rgv-item svg { display: block; inline-size: 100%; }
        .rgv-a svg { clip-path: inset(calc((1 - var(--rgv-r)) * 46%) calc((1 - var(--rgv-r)) * 44%) round 1.25rem); }
        .rgv-b svg { clip-path: circle(calc(6% + var(--rgv-r) * 72%) at 32% 42%); }
        .rgv-item figcaption { opacity: var(--rgv-r); translate: 0 calc((1 - var(--rgv-r)) * 1.25rem); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Customers.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import StoryGallery from '@/components/StoryGallery';

        export default function Customers() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <StoryGallery />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // StoryGallery.jsx — every frame opens through its own clip-path mask
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        const STORIES = [
          { id: 'northwind', name: 'Northwind Logistics', meta: 'Copenhagen · 38% more deploy frequency', mask: 'a' },
          { id: 'kite', name: 'Kite Payments', meta: 'Singapore · canary across 9 regions, zero downtime', mask: 'b' },
          { id: 'aster', name: 'Aster Health', meta: 'Toronto · rollback from 4 hours to 12 minutes', mask: 'c' },
          { id: 'vela', name: 'Vela Media', meta: 'São Paulo · 24 regions, one release stream', mask: 'd' },
        ];

        export default function StoryGallery() {
          const itemRefs = useRef([]);

          // One rAF-throttled pass writes each frame's own reveal value,
          // measured against its own rect — reversible, cleaned up on unmount.
          useEffect(() => {
            let frame = 0;
            const measure = () => {
              frame = 0;
              itemRefs.current.forEach((item) => {
                if (!item) return;
                const top = item.getBoundingClientRect().top;
                const r = Math.min(1, Math.max(0, (innerHeight * 0.88 - top) / (innerHeight * 0.5)));
                item.style.setProperty('--rgv-r', r.toFixed(3));
              });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          }, []);

          return (
            <>
              <style>{`
                .rgv-item { --rgv-r: 0; margin: 0; }
                .rgv-item svg { display: block; inline-size: 100%; background: #eef0fb; }
                .rgv-a svg { clip-path: inset(calc((1 - var(--rgv-r)) * 46%) calc((1 - var(--rgv-r)) * 44%) round 1.25rem); }
                .rgv-b svg { clip-path: circle(calc(6% + var(--rgv-r) * 72%) at 32% 42%); }
                .rgv-c svg { clip-path: polygon(0% 0%, calc(14% + var(--rgv-r) * 86%) 0%,
                              calc(8% + var(--rgv-r) * 92%) calc(14% + var(--rgv-r) * 86%),
                              0% calc(26% + var(--rgv-r) * 74%)); }
                .rgv-d svg { clip-path: inset(calc((1 - var(--rgv-r)) * 48%) 8% round 1.25rem); }
                .rgv-item figcaption { display: grid; gap: .25rem; padding-block-start: .8rem;
                                       opacity: var(--rgv-r); translate: 0 calc((1 - var(--rgv-r)) * 1.25rem); }
                .rgv-item figcaption small { color: #5c5a75; }
              `}</style>
              {STORIES.map((story, i) => (
                <figure className={`rgv-item rgv-${story.mask}`} key={story.id}
                  ref={(el) => { itemRefs.current[i] = el; }}>
                  <svg viewBox="0 0 320 200" role="img" aria-label={story.name}>
                    <rect width="320" height="200" fill="#eef0fb" />
                    <circle cx="80" cy="120" r="18" fill="#5647e6" opacity=".8" />
                    <circle cx="220" cy="80" r="18" fill="#2f9e6e" opacity=".8" />
                    <path d="M96 112 C 140 60 170 130 204 88" stroke="#c2530f" stroke-width="5" fill="none" />
                  </svg>
                  <figcaption>
                    <b>{story.name}</b>
                    <small>{story.meta}</small>
                  </figcaption>
                </figure>
              ))}
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- StoryGallery.vue — every frame opens through its own clip-path mask -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const stories = [
          { id: 'northwind', name: 'Northwind Logistics', meta: 'Copenhagen · 38% more deploy frequency', mask: 'a' },
          { id: 'kite', name: 'Kite Payments', meta: 'Singapore · canary across 9 regions, zero downtime', mask: 'b' },
          { id: 'aster', name: 'Aster Health', meta: 'Toronto · rollback from 4 hours to 12 minutes', mask: 'c' },
          { id: 'vela', name: 'Vela Media', meta: 'São Paulo · 24 regions, one release stream', mask: 'd' },
        ];
        const items = ref<HTMLElement[]>([]);
        const setItem = (i: number) => (el: unknown) => { items.value[i] = el as HTMLElement; };

        // One rAF-throttled pass writes each frame's own reveal value,
        // measured against its own rect — reversible, cleaned up on unmount.
        let frame = 0;
        const measure = () => {
          frame = 0;
          items.value.forEach((item) => {
            if (!item) return;
            const top = item.getBoundingClientRect().top;
            const r = Math.min(1, Math.max(0, (innerHeight * 0.88 - top) / (innerHeight * 0.5)));
            item.style.setProperty('--rgv-r', r.toFixed(3));
          });
        };
        const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };

        onMounted(() => {
          window.addEventListener('scroll', schedule, { passive: true });
          window.addEventListener('resize', schedule, { passive: true });
          measure();
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(frame);
          window.removeEventListener('scroll', schedule);
          window.removeEventListener('resize', schedule);
        });
        </script>

        <template>
          <figure v-for="(story, i) in stories" :key="story.id" :ref="setItem(i)" class="rgv-item" :class="'rgv-' + story.mask">
            <svg viewBox="0 0 320 200" role="img" :aria-label="story.name">
              <rect width="320" height="200" fill="#eef0fb" />
              <circle cx="80" cy="120" r="18" fill="#5647e6" opacity=".8" />
              <circle cx="220" cy="80" r="18" fill="#2f9e6e" opacity=".8" />
              <path d="M96 112 C 140 60 170 130 204 88" stroke="#c2530f" stroke-width="5" fill="none" />
            </svg>
            <figcaption>
              <b>{{ story.name }}</b>
              <small>{{ story.meta }}</small>
            </figcaption>
          </figure>
        </template>

        <style scoped>
        .rgv-item { --rgv-r: 0; margin: 0; }
        .rgv-item svg { display: block; inline-size: 100%; background: #eef0fb; }
        .rgv-a svg { clip-path: inset(calc((1 - var(--rgv-r)) * 46%) calc((1 - var(--rgv-r)) * 44%) round 1.25rem); }
        .rgv-b svg { clip-path: circle(calc(6% + var(--rgv-r) * 72%) at 32% 42%); }
        .rgv-c svg { clip-path: polygon(0% 0%, calc(14% + var(--rgv-r) * 86%) 0%,
                       calc(8% + var(--rgv-r) * 92%) calc(14% + var(--rgv-r) * 86%),
                       0% calc(26% + var(--rgv-r) * 74%)); }
        .rgv-d svg { clip-path: inset(calc((1 - var(--rgv-r)) * 48%) 8% round 1.25rem); }
        .rgv-item figcaption { display: grid; gap: .25rem; padding-block-start: .8rem;
                               opacity: var(--rgv-r); translate: 0 calc((1 - var(--rgv-r)) * 1.25rem); }
        .rgv-item figcaption small { color: #5c5a75; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- StoryGallery.svelte — every frame opens through its own clip-path mask -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const stories = [
            { id: 'northwind', name: 'Northwind Logistics', meta: 'Copenhagen · 38% more deploy frequency', mask: 'a' },
            { id: 'kite', name: 'Kite Payments', meta: 'Singapore · canary across 9 regions, zero downtime', mask: 'b' },
            { id: 'aster', name: 'Aster Health', meta: 'Toronto · rollback from 4 hours to 12 minutes', mask: 'c' },
            { id: 'vela', name: 'Vela Media', meta: 'São Paulo · 24 regions, one release stream', mask: 'd' },
          ];
          let items: HTMLElement[] = $state([]);

          // One rAF-throttled pass writes each frame's own reveal value,
          // measured against its own rect — reversible. $effect cleans up.
          $effect(() => {
            let frame = 0;
            const measure = () => {
              frame = 0;
              items.forEach((item) => {
                const top = item.getBoundingClientRect().top;
                const r = Math.min(1, Math.max(0, (innerHeight * 0.88 - top) / (innerHeight * 0.5)));
                item.style.setProperty('--rgv-r', r.toFixed(3));
              });
            };
            const schedule = () => { if (!frame) frame = requestAnimationFrame(measure); };
            window.addEventListener('scroll', schedule, { passive: true });
            window.addEventListener('resize', schedule, { passive: true });
            measure();
            return () => {
              cancelAnimationFrame(frame);
              window.removeEventListener('scroll', schedule);
              window.removeEventListener('resize', schedule);
            };
          });
        </script>

        {#each stories as story, i (story.id)}
          <figure bind:this={items[i]} class="rgv-item rgv-{story.mask}">
            <svg viewBox="0 0 320 200" role="img" aria-label={story.name}>
              <rect width="320" height="200" fill="#eef0fb" />
              <circle cx="80" cy="120" r="18" fill="#5647e6" opacity=".8" />
              <circle cx="220" cy="80" r="18" fill="#2f9e6e" opacity=".8" />
              <path d="M96 112 C 140 60 170 130 204 88" stroke="#c2530f" stroke-width="5" fill="none" />
            </svg>
            <figcaption>
              <b>{story.name}</b>
              <small>{story.meta}</small>
            </figcaption>
          </figure>
        {/each}

        <style>
        .rgv-item { --rgv-r: 0; margin: 0; }
        .rgv-item svg { display: block; inline-size: 100%; background: #eef0fb; }
        .rgv-a svg { clip-path: inset(calc((1 - var(--rgv-r)) * 46%) calc((1 - var(--rgv-r)) * 44%) round 1.25rem); }
        .rgv-b svg { clip-path: circle(calc(6% + var(--rgv-r) * 72%) at 32% 42%); }
        .rgv-c svg { clip-path: polygon(0% 0%, calc(14% + var(--rgv-r) * 86%) 0%,
                       calc(8% + var(--rgv-r) * 92%) calc(14% + var(--rgv-r) * 86%),
                       0% calc(26% + var(--rgv-r) * 74%)); }
        .rgv-d svg { clip-path: inset(calc((1 - var(--rgv-r)) * 48%) 8% round 1.25rem); }
        .rgv-item figcaption { display: grid; gap: .25rem; padding-block-start: .8rem;
                               opacity: var(--rgv-r); translate: 0 calc((1 - var(--rgv-r)) * 1.25rem); }
        .rgv-item figcaption small { color: #5c5a75; }
        </style>
        SVELTE,
        ],
    ],
];
