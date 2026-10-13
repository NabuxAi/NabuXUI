<?php

/**
 * Demo manifest of the "flows" group — real multi-step journeys in the Mobbin
 * spirit, rebuilt as faithful, interactive skins with Alpine state only (no
 * wire:): the four-step onboarding wizard, the checkout with a validated
 * address form, a formatted card and a sticky order summary, the auth flow
 * with its six-digit code boxes, resend timer and forgot-password branch, and
 * the product search with live suggestions, a filter rail, the empty state
 * and a slide-in details drawer. Scenarios live at
 * resources/views/demos/components/flows/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'جریان‌های واقعی', 'en' => 'Real flows'],

    'onboarding-flow' => [
        'title' => ['fa' => 'جریان آنبوردینگ', 'en' => 'Onboarding flow'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'چهار قدم واقعی از خوش‌آمد تا ورک‌اسپیس آماده: انتخاب نقش، چیپ‌های شخصی‌سازی و تأیید با خلاصه — نوار پیشرفت سهم هر قدم را پر می‌کند، برگشت همیشه هست و قدم‌ها با اسلایدی می‌آیند که در فارسی جهتش آینه می‌شود.',
            'en' => 'Four real steps from welcome to a ready workspace: role pick, personalization chips and a summary confirm — the progress bar fills per step, back is always there, and steps slide in from a side that mirrors itself in Persian.',
        ],
        'js' => true,
        'docs' => 'https://mobbin.com',
        'props' => [
            ['name' => 'steps', 'type' => 'count', 'default' => "'4'", 'note' => [
                'fa' => 'خوش‌آمد → نقش → شخصی‌سازی → تأیید؛ نوار بالای قاب چهار سگمنت دارد و سهم قدم جاری را همان لحظه پر می‌کند.',
                'en' => 'welcome → role → personalization → confirm; the frame’s top bar has four segments and fills the current step’s share instantly.',
            ]],
            ['name' => 'slide', 'type' => 'translate', 'default' => "'28px · 340ms'", 'note' => [
                'fa' => 'قدم بعدی از جلو و قدم قبلی از عقب خارج می‌شود؛ در RTL جهت آمدن و رفتن برعکس می‌شود تا سفر همیشه به سمت جلو حس شود.',
                'en' => 'The next step enters from ahead while the previous exits behind; RTL mirrors both sides so the journey always feels forward.',
            ]],
            ['name' => 'min-picks', 'type' => 'number', 'default' => "'1'", 'note' => [
                'fa' => 'قدم شخصی‌سازی تا دست‌کم یک چیپ انتخاب نشود دکمهٔ ادامه را قفل نگه می‌دارد.',
                'en' => 'The personalization step keeps Continue locked until at least one chip is picked.',
            ]],
            ['name' => 'summary', 'type' => 'slot', 'default' => "'role · picks'", 'note' => [
                'fa' => 'قدم تأیید نقش و انتخاب‌ها را کنار هم می‌آورد و پیام پایان با تیک بزرگ جای کل قاب را می‌گیرد.',
                'en' => 'The confirm step gathers the role and the picks side by side; the finish message with its big check then takes over the frame.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="flwb" x-data="{ step: 0, role: null, picks: [] }">
            <header class="flwb-top">
                <div class="flwb-bar">
                    <i data-on></i><i :data-on="step >= 1"></i><i :data-on="step >= 2"></i><i :data-on="step >= 3"></i>
                </div>
                <small><span x-text="step + 1"></span> از ۴</small>
            </header>

            <div class="flwb-stage">
                <section class="flwb-step" :data-pos="step === 0 ? 'now' : 'before'">
                    <h3>به مریدین خوش آمدید</h3>
                    <p>در دو دقیقه ورک‌اسپیس تیم‌تان آماده می‌شود.</p>
                </section>
                <section class="flwb-step" :data-pos="step === 1 ? 'now' : (step < 1 ? 'after' : 'before')">
                    <button class="flwb-role" :data-on="role === 'designer'" @click="role = 'designer'">طراح محصول</button>
                </section>
                <section class="flwb-step" :data-pos="step === 2 ? 'now' : (step < 2 ? 'after' : 'before')">
                    <button class="flwb-chip" :data-on="picks.includes('motion')" @click="picks.push('motion')">موشن</button>
                </section>
            </div>

            <footer class="flwb-nav">
                <button @click="step--" :disabled="step === 0">برگشت</button>
                <button :disabled="step === 1 && !role" @click="step++">ادامه</button>
            </footer>
        </div>

        <style>
        .flwb-stage { display: grid; } .flwb-step { grid-area: 1 / 1; transition: translate .34s, opacity .34s; }
        .flwb-step[data-pos='before'] { translate: -28px 0; opacity: 0; visibility: hidden; }
        .flwb-step[data-pos='after']  { translate: 28px 0;  opacity: 0; visibility: hidden; }
        .flwb-bar { display: flex; gap: 4px; } .flwb-bar i { flex: 1; block-size: 4px; border-radius: 999px; background: #e4e6ef; }
        .flwb-bar i[data-on] { background: #4f46e5; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="flwb" x-data="{ step: 0, role: null, picks: [] }">
            <header class="flwb-top">
                <div class="flwb-bar">
                    <i data-on></i><i :data-on="step >= 1"></i><i :data-on="step >= 2"></i><i :data-on="step >= 3"></i>
                </div>
                <small>Step <span x-text="step + 1"></span> of 4</small>
            </header>

            <div class="flwb-stage">
                <section class="flwb-step" :data-pos="step === 0 ? 'now' : 'before'">
                    <h3>Welcome to Meridian</h3>
                    <p>Your team workspace is ready in two minutes.</p>
                </section>
                <section class="flwb-step" :data-pos="step === 1 ? 'now' : (step < 1 ? 'after' : 'before')">
                    <button class="flwb-role" :data-on="role === 'designer'" @click="role = 'designer'">Product designer</button>
                </section>
                <section class="flwb-step" :data-pos="step === 2 ? 'now' : (step < 2 ? 'after' : 'before')">
                    <button class="flwb-chip" :data-on="picks.includes('motion')" @click="picks.push('motion')">Motion</button>
                </section>
            </div>

            <footer class="flwb-nav">
                <button @click="step--" :disabled="step === 0">Back</button>
                <button :disabled="step === 1 && !role" @click="step++">Continue</button>
            </footer>
        </div>

        <style>
        .flwb-stage { display: grid; } .flwb-step { grid-area: 1 / 1; transition: translate .34s, opacity .34s; }
        .flwb-step[data-pos='before'] { translate: -28px 0; opacity: 0; visibility: hidden; }
        .flwb-step[data-pos='after']  { translate: 28px 0;  opacity: 0; visibility: hidden; }
        .flwb-bar { display: flex; gap: 4px; } .flwb-bar i { flex: 1; block-size: 4px; border-radius: 999px; background: #e4e6ef; }
        .flwb-bar i[data-on] { background: #4f46e5; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Onboarding.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import OnboardingWizard from '@/components/OnboardingWizard';

        export default function Onboarding() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <OnboardingWizard />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // OnboardingWizard.jsx — the four-step onboarding: welcome → role → chips → confirm
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ROLES = [
          { id: 'designer', name: 'Product designer', note: 'Shapes the interface' },
          { id: 'engineer', name: 'Engineer', note: 'Ships the code' },
          { id: 'manager', name: 'Product manager', note: 'Owns the roadmap' },
          { id: 'marketer', name: 'Marketer', note: 'Tells the story' },
        ];
        const TOPICS = ['Design systems', 'Motion', 'Accessibility', 'Performance', 'Tokens', 'Handoff'];

        export default function OnboardingWizard() {
          const [step, setStep] = useState(0);
          const [role, setRole] = useState(null);
          const [topics, setTopics] = useState([]);
          const [done, setDone] = useState(false);
          const flip = (t) => setTopics((s) => (s.includes(t) ? s.filter((x) => x !== t) : [...s, t]));
          const pos = (i) => (i === step ? 'now' : i < step ? 'before' : 'after');
          const canNext = step === 1 ? !!role : step === 2 ? topics.length > 0 : true;

          if (done) return (
            <>
              <style>{FLWB_CSS}</style>
              <div className="flwb flwb-done">
                <span className="flwb-check" aria-hidden="true">✓</span>
                <h3>Your workspace is ready</h3>
                <p>Meridian tuned itself for {ROLES.find((r) => r.id === role).name}s.</p>
                <button type="button" className="flwb-go"
                  onClick={() => { setStep(0); setRole(null); setTopics([]); setDone(false); }}>
                  Enter the dashboard
                </button>
              </div>
            </>
          );

          return (
            <>
              <style>{FLWB_CSS}</style>
              <div className="flwb">
                <header className="flwb-top">
                  <div className="flwb-bar">
                    {[0, 1, 2, 3].map((i) => <i data-on={i <= step ? '' : undefined} key={i} />)}
                  </div>
                  <small>Step {step + 1} of 4</small>
                </header>

                <div className="flwb-stage">
                  <section className="flwb-step" data-pos={pos(0)}>
                    <h3>Welcome to Meridian</h3>
                    <p>Two minutes, four steps — and the workspace fits your team.</p>
                    <ul className="flwb-list">
                      <li>Role-aware dashboards</li><li>Hand-picked starter kits</li>
                    </ul>
                  </section>
                  <section className="flwb-step" data-pos={pos(1)}>
                    <h3>What do you do?</h3>
                    <div className="flwb-roles" role="radiogroup" aria-label="Your role">
                      {ROLES.map((r) => (
                        <button type="button" className="flwb-role" data-on={role === r.id || undefined}
                          key={r.id} onClick={() => setRole(r.id)}>
                          <b>{r.name}</b><small>{r.note}</small><i aria-hidden="true">✓</i>
                        </button>
                      ))}
                    </div>
                  </section>
                  <section className="flwb-step" data-pos={pos(2)}>
                    <h3>Tune your feed</h3>
                    <div className="flwb-chips">
                      {TOPICS.map((t) => (
                        <button type="button" className="flwb-chip" data-on={topics.includes(t) || undefined}
                          key={t} onClick={() => flip(t)}>{t}</button>
                      ))}
                    </div>
                  </section>
                  <section className="flwb-step" data-pos={pos(3)}>
                    <h3>All set?</h3>
                    <dl className="flwb-sum">
                      <div><dt>Role</dt><dd>{ROLES.find((r) => r.id === role)?.name}</dd></div>
                      <div><dt>Interests</dt><dd>{topics.join(' · ')}</dd></div>
                    </dl>
                  </section>
                </div>

                <footer className="flwb-nav">
                  <button type="button" onClick={() => setStep((s) => s - 1)} disabled={step === 0}>Back</button>
                  {step < 3
                    ? <button type="button" className="flwb-go" disabled={!canNext} onClick={() => setStep((s) => s + 1)}>Continue</button>
                    : <button type="button" className="flwb-go" onClick={() => setDone(true)}>Create workspace</button>}
                </footer>
              </div>
            </>
          );
        }

        const FLWB_CSS = `
          .flwb { display: grid; gap: 1.25rem; max-inline-size: 26rem; padding: 1.5rem; border: 1px solid #e4e6ef;
                  border-radius: 1.25rem; background: #fff; color: #191b2a; }
          .flwb-bar { display: flex; gap: .25rem; }
          .flwb-bar i { flex: 1; block-size: 4px; border-radius: 999px; background: #e4e6ef; }
          .flwb-bar i[data-on] { background: #4f46e5; }
          .flwb-stage { display: grid; }
          .flwb-step { grid-area: 1 / 1; transition: translate .34s cubic-bezier(.2, 0, 0, 1), opacity .34s; }
          .flwb-step[data-pos='before'] { translate: -28px 0; opacity: 0; visibility: hidden; }
          .flwb-step[data-pos='after'] { translate: 28px 0; opacity: 0; visibility: hidden; }
          .flwb-roles { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
          .flwb-role { position: relative; display: grid; gap: .2rem; padding: .8rem; text-align: start;
                       border: 1px solid #e4e6ef; border-radius: .9rem; background: #fff; cursor: pointer; }
          .flwb-role[data-on] { border-color: #4f46e5; box-shadow: inset 0 0 0 1px #4f46e5; }
          .flwb-role i { position: absolute; inset-block-start: .4rem; inset-inline-end: .4rem; display: none;
                         color: #4f46e5; font-style: normal; }
          .flwb-role[data-on] i { display: block; }
          .flwb-chips { display: flex; flex-wrap: wrap; gap: .5rem; }
          .flwb-chip { padding: .45rem .9rem; border: 1px solid #e4e6ef; border-radius: 999px; background: #fff; cursor: pointer; }
          .flwb-chip[data-on] { background: #4f46e5; border-color: #4f46e5; color: #fff; }
          .flwb-sum { display: grid; margin: 0; }
          .flwb-sum div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .5rem;
                          border-block-start: 1px solid #e4e6ef; }
          .flwb-nav { display: flex; justify-content: space-between; gap: .75rem; }
          .flwb-go { padding: .65rem 1.2rem; border: none; border-radius: 999px; background: #4f46e5; color: #fff; cursor: pointer; }
          .flwb-go:disabled { opacity: .45; cursor: not-allowed; }
          .flwb-done { justify-items: center; text-align: center; }
          .flwb-check { display: grid; place-items: center; inline-size: 3.5rem; aspect-ratio: 1; border-radius: 50%;
                        background: #e7f6ec; color: #0f8a43; font-size: 1.5rem; }
        `;
        REACT,
            'vue' => <<<'VUE'
        <!-- OnboardingWizard.vue — the four-step onboarding: welcome → role → chips → confirm -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const roles = [
          { id: 'designer', name: 'Product designer', note: 'Shapes the interface' },
          { id: 'engineer', name: 'Engineer', note: 'Ships the code' },
          { id: 'manager', name: 'Product manager', note: 'Owns the roadmap' },
          { id: 'marketer', name: 'Marketer', note: 'Tells the story' },
        ];
        const topics = ['Design systems', 'Motion', 'Accessibility', 'Performance', 'Tokens', 'Handoff'];
        const step = ref(0);
        const role = ref<string | null>(null);
        const picks = ref<string[]>([]);
        const done = ref(false);
        const flip = (t: string) => {
          picks.value = picks.value.includes(t) ? picks.value.filter((x) => x !== t) : [...picks.value, t];
        };
        const pos = (i: number) => (i === step.value ? 'now' : i < step.value ? 'before' : 'after');
        const roleName = computed(() => roles.find((r) => r.id === role.value)?.name);
        const canNext = computed(() =>
          step.value === 1 ? !!role.value : step.value === 2 ? picks.value.length > 0 : true);
        const next = () => (step.value < 3 ? step.value++ : (done.value = true));
        </script>

        <template>
          <div class="flwb" v-if="!done">
            <header class="flwb-top">
              <div class="flwb-bar"><i v-for="i in 4" :key="i" :data-on="i - 1 <= step || undefined"></i></div>
              <small>Step {{ step + 1 }} of 4</small>
            </header>

            <div class="flwb-stage">
              <section class="flwb-step" :data-pos="pos(0)">
                <h3>Welcome to Meridian</h3>
                <p>Two minutes, four steps — and the workspace fits your team.</p>
              </section>
              <section class="flwb-step" :data-pos="pos(1)">
                <h3>What do you do?</h3>
                <div class="flwb-roles" role="radiogroup" aria-label="Your role">
                  <button v-for="r in roles" :key="r.id" type="button" class="flwb-role"
                    :data-on="role === r.id || undefined" @click="role = r.id">
                    <b>{{ r.name }}</b><small>{{ r.note }}</small><i aria-hidden="true">✓</i>
                  </button>
                </div>
              </section>
              <section class="flwb-step" :data-pos="pos(2)">
                <h3>Tune your feed</h3>
                <div class="flwb-chips">
                  <button v-for="t in topics" :key="t" type="button" class="flwb-chip"
                    :data-on="picks.includes(t) || undefined" @click="flip(t)">{{ t }}</button>
                </div>
              </section>
              <section class="flwb-step" :data-pos="pos(3)">
                <h3>All set?</h3>
                <dl class="flwb-sum">
                  <div><dt>Role</dt><dd>{{ roleName }}</dd></div>
                  <div><dt>Interests</dt><dd>{{ picks.join(' · ') }}</dd></div>
                </dl>
              </section>
            </div>

            <footer class="flwb-nav">
              <button type="button" :disabled="step === 0" @click="step--">Back</button>
              <button type="button" class="flwb-go" :disabled="!canNext" @click="next()">
                {{ step < 3 ? 'Continue' : 'Create workspace' }}
              </button>
            </footer>
          </div>

          <div class="flwb flwb-done" v-else>
            <span class="flwb-check" aria-hidden="true">✓</span>
            <h3>Your workspace is ready</h3>
            <p>Meridian tuned itself for {{ roleName }}s.</p>
            <button type="button" class="flwb-go"
              @click="step = 0; role = null; picks = []; done = false">Enter the dashboard</button>
          </div>
        </template>

        <style scoped>
        .flwb { display: grid; gap: 1.25rem; max-inline-size: 26rem; padding: 1.5rem; border: 1px solid #e4e6ef;
                border-radius: 1.25rem; background: #fff; color: #191b2a; }
        .flwb-bar { display: flex; gap: .25rem; }
        .flwb-bar i { flex: 1; block-size: 4px; border-radius: 999px; background: #e4e6ef; }
        .flwb-bar i[data-on] { background: #4f46e5; }
        .flwb-stage { display: grid; }
        .flwb-step { grid-area: 1 / 1; transition: translate .34s cubic-bezier(.2, 0, 0, 1), opacity .34s; }
        .flwb-step[data-pos='before'] { translate: -28px 0; opacity: 0; visibility: hidden; }
        .flwb-step[data-pos='after'] { translate: 28px 0; opacity: 0; visibility: hidden; }
        .flwb-roles { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
        .flwb-role { position: relative; display: grid; gap: .2rem; padding: .8rem; text-align: start;
                     border: 1px solid #e4e6ef; border-radius: .9rem; background: #fff; cursor: pointer; }
        .flwb-role[data-on] { border-color: #4f46e5; box-shadow: inset 0 0 0 1px #4f46e5; }
        .flwb-role i { position: absolute; inset-block-start: .4rem; inset-inline-end: .4rem; display: none;
                       color: #4f46e5; font-style: normal; }
        .flwb-role[data-on] i { display: block; }
        .flwb-chips { display: flex; flex-wrap: wrap; gap: .5rem; }
        .flwb-chip { padding: .45rem .9rem; border: 1px solid #e4e6ef; border-radius: 999px; background: #fff; cursor: pointer; }
        .flwb-chip[data-on] { background: #4f46e5; border-color: #4f46e5; color: #fff; }
        .flwb-sum { display: grid; margin: 0; }
        .flwb-sum div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .5rem;
                        border-block-start: 1px solid #e4e6ef; }
        .flwb-nav { display: flex; justify-content: space-between; gap: .75rem; }
        .flwb-go { padding: .65rem 1.2rem; border: none; border-radius: 999px; background: #4f46e5; color: #fff; cursor: pointer; }
        .flwb-go:disabled { opacity: .45; cursor: not-allowed; }
        .flwb-done { justify-items: center; text-align: center; }
        .flwb-check { display: grid; place-items: center; inline-size: 3.5rem; aspect-ratio: 1; border-radius: 50%;
                      background: #e7f6ec; color: #0f8a43; font-size: 1.5rem; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- OnboardingWizard.svelte — the four-step onboarding: welcome → role → chips → confirm -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const roles = [
            { id: 'designer', name: 'Product designer', note: 'Shapes the interface' },
            { id: 'engineer', name: 'Engineer', note: 'Ships the code' },
            { id: 'manager', name: 'Product manager', note: 'Owns the roadmap' },
            { id: 'marketer', name: 'Marketer', note: 'Tells the story' },
          ];
          const topics = ['Design systems', 'Motion', 'Accessibility', 'Performance', 'Tokens', 'Handoff'];
          let step = $state(0);
          let role = $state<string | null>(null);
          let picks = $state<string[]>([]);
          let done = $state(false);

          const flip = (t: string) => {
            picks = picks.includes(t) ? picks.filter((x) => x !== t) : [...picks, t];
          };
          const pos = (i: number) => (i === step ? 'now' : i < step ? 'before' : 'after');
          const roleName = $derived(roles.find((r) => r.id === role)?.name);
          const canNext = $derived(
            step === 1 ? !!role : step === 2 ? picks.length > 0 : true);
          const next = () => (step < 3 ? step++ : (done = true));
        </script>

        {#if !done}
          <div class="flwb">
            <header class="flwb-top">
              <div class="flwb-bar">
                {#each [0, 1, 2, 3] as i (i)}
                  <i data-on={i <= step || undefined}></i>
                {/each}
              </div>
              <small>Step {step + 1} of 4</small>
            </header>

            <div class="flwb-stage">
              <section class="flwb-step" data-pos={pos(0)}>
                <h3>Welcome to Meridian</h3>
                <p>Two minutes, four steps — and the workspace fits your team.</p>
              </section>
              <section class="flwb-step" data-pos={pos(1)}>
                <h3>What do you do?</h3>
                <div class="flwb-roles" role="radiogroup" aria-label="Your role">
                  {#each roles as r (r.id)}
                    <button type="button" class="flwb-role" data-on={role === r.id || undefined}
                      onclick={() => (role = r.id)}>
                      <b>{r.name}</b><small>{r.note}</small><i aria-hidden="true">✓</i>
                    </button>
                  {/each}
                </div>
              </section>
              <section class="flwb-step" data-pos={pos(2)}>
                <h3>Tune your feed</h3>
                <div class="flwb-chips">
                  {#each topics as t (t)}
                    <button type="button" class="flwb-chip" data-on={picks.includes(t) || undefined}
                      onclick={() => flip(t)}>{t}</button>
                  {/each}
                </div>
              </section>
              <section class="flwb-step" data-pos={pos(3)}>
                <h3>All set?</h3>
                <dl class="flwb-sum">
                  <div><dt>Role</dt><dd>{roleName}</dd></div>
                  <div><dt>Interests</dt><dd>{picks.join(' · ')}</dd></div>
                </dl>
              </section>
            </div>

            <footer class="flwb-nav">
              <button type="button" disabled={step === 0} onclick={() => step--}>Back</button>
              <button type="button" class="flwb-go" disabled={!canNext} onclick={next}>
                {step < 3 ? 'Continue' : 'Create workspace'}
              </button>
            </footer>
          </div>
        {:else}
          <div class="flwb flwb-done">
            <span class="flwb-check" aria-hidden="true">✓</span>
            <h3>Your workspace is ready</h3>
            <p>Meridian tuned itself for {roleName}s.</p>
            <button type="button" class="flwb-go"
              onclick={() => { step = 0; role = null; picks = []; done = false; }}>Enter the dashboard</button>
          </div>
        {/if}

        <style>
          .flwb { display: grid; gap: 1.25rem; max-inline-size: 26rem; padding: 1.5rem; border: 1px solid #e4e6ef;
                  border-radius: 1.25rem; background: #fff; color: #191b2a; }
          .flwb-bar { display: flex; gap: .25rem; }
          .flwb-bar i { flex: 1; block-size: 4px; border-radius: 999px; background: #e4e6ef; }
          .flwb-bar i[data-on] { background: #4f46e5; }
          .flwb-stage { display: grid; }
          .flwb-step { grid-area: 1 / 1; transition: translate .34s cubic-bezier(.2, 0, 0, 1), opacity .34s; }
          .flwb-step[data-pos='before'] { translate: -28px 0; opacity: 0; visibility: hidden; }
          .flwb-step[data-pos='after'] { translate: 28px 0; opacity: 0; visibility: hidden; }
          .flwb-roles { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
          .flwb-role { position: relative; display: grid; gap: .2rem; padding: .8rem; text-align: start;
                       border: 1px solid #e4e6ef; border-radius: .9rem; background: #fff; cursor: pointer; }
          .flwb-role[data-on] { border-color: #4f46e5; box-shadow: inset 0 0 0 1px #4f46e5; }
          .flwb-role i { position: absolute; inset-block-start: .4rem; inset-inline-end: .4rem; display: none;
                         color: #4f46e5; font-style: normal; }
          .flwb-role[data-on] i { display: block; }
          .flwb-chips { display: flex; flex-wrap: wrap; gap: .5rem; }
          .flwb-chip { padding: .45rem .9rem; border: 1px solid #e4e6ef; border-radius: 999px; background: #fff; cursor: pointer; }
          .flwb-chip[data-on] { background: #4f46e5; border-color: #4f46e5; color: #fff; }
          .flwb-sum { display: grid; margin: 0; }
          .flwb-sum div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .5rem;
                          border-block-start: 1px solid #e4e6ef; }
          .flwb-nav { display: flex; justify-content: space-between; gap: .75rem; }
          .flwb-go { padding: .65rem 1.2rem; border: none; border-radius: 999px; background: #4f46e5; color: #fff; cursor: pointer; }
          .flwb-go:disabled { opacity: .45; cursor: not-allowed; }
          .flwb-done { justify-items: center; text-align: center; }
          .flwb-check { display: grid; place-items: center; inline-size: 3.5rem; aspect-ratio: 1; border-radius: 50%;
                        background: #e7f6ec; color: #0f8a43; font-size: 1.5rem; }
        </style>
        SVELTE,
        ],
    ],

    'checkout-flow' => [
        'title' => ['fa' => 'جریان پرداخت', 'en' => 'Checkout flow'],
        'icon' => 'lock',
        'oneLiner' => [
            'fa' => 'از سبد تا شمارهٔ سفارش در سه قدم صادق: فرم آدرس که واقعاً اعتبارسنجی می‌کند، کارت که هم‌حین تایپ چهارتایی گروه می‌شود و تاريخش را خودش اسلش می‌زند، و خلاصهٔ سفارشی که چسبیده کنار دستتان می‌ماند — با حالت واقعی کارتِ ردشده و انیمیشن پردازش.',
            'en' => 'From cart to order number in three honest steps: an address form that really validates, a card that groups in fours and slashes its own expiry while you type, and an order summary that stays sticky at your side — plus a real declined-card state and a processing spin.',
        ],
        'js' => true,
        'docs' => 'https://mobbin.com',
        'props' => [
            ['name' => 'steps', 'type' => 'count', 'default' => "'3 + done'", 'note' => [
                'fa' => 'سبد → آدرس → پرداخت → تأیید؛ دکمهٔ ادامهٔ هر قدم فقط وقتی قدم روراست باشد باز می‌شود.',
                'en' => 'cart → address → payment → done; each step’s Continue unlocks only when the step is honest.',
            ]],
            ['name' => 'validation', 'type' => 'behaviour', 'default' => "'on-continue'", 'note' => [
                'fa' => 'نام، خیابان، شهر و کدپستی هنگام ادامه بررسی می‌شوند؛ خطای هر فیلد زیر خودش با حاشیهٔ قرمز می‌نشیند و قدم عوض نمی‌شود.',
                'en' => 'Name, street, city and postcode are checked on Continue; each field’s error sits under it with a red rim and the step refuses to move.',
            ]],
            ['name' => 'card-format', 'type' => 'mask', 'default' => "'#### #### #### ####'", 'note' => [
                'fa' => 'شمارهٔ کارت حین تایپ چهاررقمی گروه می‌شود، تاریخ به‌تنهایی «ماه/سال» می‌شود و پیش‌نمایش کارت زیرش زنده به‌روز می‌شود.',
                'en' => 'The card number groups in fours as you type, the expiry becomes month/year on its own, and the card preview below updates live.',
            ]],
            ['name' => 'declined', 'type' => 'state', 'default' => "'4000 0000 0000 0002'", 'note' => [
                'fa' => 'کارت آزمایشی معروف ردشدن؛ با آن پرداخت کنید تا بنر خطا با دکمهٔ تلاش دوباره بیاید — بقیهٔ شماره‌های ۱۶رقمی قبول می‌شوند.',
                'en' => 'The classic decline test card; pay with it and the error banner with its retry button arrives — any other 16-digit number goes through.',
            ]],
            ['name' => 'summary', 'type' => 'position', 'default' => "'sticky'", 'note' => [
                'fa' => 'خلاصهٔ سفارش در ستون کناری می‌چسبد و با هر تغییر تعداد، ارسال رایگانِ سقف ۱۵۰ یورو و مالیات را همان لحظه می‌شمارد.',
                'en' => 'The order summary sticks in the side column and recounts free shipping over €150 and VAT the moment a quantity changes.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="flck" x-data="{
            step: 0, declined: false, processing: false,
            form: { name: '', street: '', city: '', postcode: '' }, errors: {},
            card: { number: '', exp: '', cvc: '' },
            formatCard() {
                const d = this.card.number.replace(/\D/g, '').slice(0, 16);
                this.card.number = d.replace(/(.{4})/g, '$1 ').trim();
            },
            formatExp() {
                const d = this.card.exp.replace(/\D/g, '').slice(0, 4);
                this.card.exp = d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
            },
            validate() {
                this.errors = {};
                if (this.form.name.trim().length < 2) this.errors.name = 'نام کامل لازم است';
                if (this.form.street.trim().length < 3) this.errors.street = 'نشانی خیابان کامل نیست';
                return Object.keys(this.errors).length === 0;
            },
        }">
            <input x-model="card.number" @input="formatCard" dir="ltr" placeholder="4000 0000 0000 0002">
            <p class="flck-error" x-show="errors.name" x-cloak x-text="errors.name"></p>
            <button @click="step === 1 && !validate() ? null : step++">ادامه</button>
        </div>

        <style>
        .flck-error { margin: .25rem 0 0; color: #c62a2f; font-size: .75rem; }
        input[data-invalid] { border-color: #c62a2f; box-shadow: inset 0 0 0 1px #c62a2f; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="flck" x-data="{
            step: 0, declined: false, processing: false,
            form: { name: '', street: '', city: '', postcode: '' }, errors: {},
            card: { number: '', exp: '', cvc: '' },
            formatCard() {
                const d = this.card.number.replace(/\D/g, '').slice(0, 16);
                this.card.number = d.replace(/(.{4})/g, '$1 ').trim();
            },
            formatExp() {
                const d = this.card.exp.replace(/\D/g, '').slice(0, 4);
                this.card.exp = d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
            },
            validate() {
                this.errors = {};
                if (this.form.name.trim().length < 2) this.errors.name = 'Full name is required';
                if (this.form.street.trim().length < 3) this.errors.street = 'Street address is incomplete';
                return Object.keys(this.errors).length === 0;
            },
        }">
            <input x-model="card.number" @input="formatCard" dir="ltr" placeholder="4000 0000 0000 0002">
            <p class="flck-error" x-show="errors.name" x-cloak x-text="errors.name"></p>
            <button @click="step === 1 && !validate() ? null : step++">Continue</button>
        </div>

        <style>
        .flck-error { margin: .25rem 0 0; color: #c62a2f; font-size: .75rem; }
        input[data-invalid] { border-color: #c62a2f; box-shadow: inset 0 0 0 1px #c62a2f; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Checkout.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import CheckoutFlow from '@/components/CheckoutFlow';

        export default function Checkout() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <CheckoutFlow />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // CheckoutFlow.jsx — cart → validated address → formatted card → order number
        import { useMemo, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ITEMS = [
          { id: 'lamp', name: 'Aster desk lamp', price: 89, qty: 1 },
          { id: 'keys', name: 'Nimbus Keys K2 keyboard', price: 149, qty: 1 },
          { id: 'stand', name: 'Vela laptop stand', price: 59, qty: 2 },
        ];
        const DECLINED = '4000000000000002';

        export default function CheckoutFlow() {
          const [step, setStep] = useState(0);
          const [items, setItems] = useState(ITEMS);
          const [form, setForm] = useState({ name: '', street: '', city: '', postcode: '' });
          const [errors, setErrors] = useState({});
          const [card, setCard] = useState({ number: '', exp: '', cvc: '' });
          const [declined, setDeclined] = useState(false);
          const [order, setOrder] = useState(null);

          const subtotal = useMemo(() => items.reduce((s, i) => s + i.price * i.qty, 0), [items]);
          const shipping = subtotal >= 150 ? 0 : 12;
          const vat = Math.round(subtotal * 0.19);
          const total = subtotal + shipping + vat;

          // Masks that run on every keystroke: fours for the number, MM/YY for the expiry.
          const maskNumber = (v) => v.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
          const maskExp = (v) => {
            const d = v.replace(/\D/g, '').slice(0, 4);
            return d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
          };

          const validate = () => {
            const e = {};
            if (form.name.trim().length < 2) e.name = 'Full name is required';
            if (form.street.trim().length < 3) e.street = 'Street address is incomplete';
            if (form.city.trim().length < 2) e.city = 'City is required';
            if (!/^[a-z0-9-]{3,10}$/i.test(form.postcode.trim())) e.postcode = 'Postcode looks off';
            setErrors(e);
            return Object.keys(e).length === 0;
          };

          const pay = () => {
            if (card.number.replace(/\s/g, '') === DECLINED) {
              setDeclined(true); // the decline banner with its retry
            } else {
              setOrder('NBX-' + Math.floor(10000 + Math.random() * 90000));
              setStep(3);
            }
          };

          return (
            <>
              <style>{FLCK_CSS}</style>
              <div className="flck">
                <div className="flck-main">
                  {step === 0 && (
                    <section className="flck-pane">
                      <h3>Your cart</h3>
                      {items.map((i) => (
                        <div className="flck-row" key={i.id}>
                          <b>{i.name}</b>
                          <span className="flck-qty">
                            <button type="button" onClick={() => setItems(items.map((x) =>
                              x.id === i.id ? { ...x, qty: Math.max(1, x.qty - 1) } : x))}>−</button>
                            {i.qty}
                            <button type="button" onClick={() => setItems(items.map((x) =>
                              x.id === i.id ? { ...x, qty: x.qty + 1 } : x))}>+</button>
                          </span>
                          <span>€{i.price * i.qty}</span>
                        </div>
                      ))}
                      <button type="button" className="flck-go" onClick={() => setStep(1)}>Continue to address</button>
                    </section>
                  )}
                  {step === 1 && (
                    <section className="flck-pane">
                      <h3>Shipping address</h3>
                      {['name', 'street', 'city', 'postcode'].map((f) => (
                        <label className="flck-field" key={f}>
                          <span>{f[0].toUpperCase() + f.slice(1)}</span>
                          <input value={form[f]} data-invalid={errors[f] || undefined}
                            onChange={(e) => setForm({ ...form, [f]: e.target.value })} />
                          {errors[f] && <small className="flck-error">{errors[f]}</small>}
                        </label>
                      ))}
                      <div className="flck-nav">
                        <button type="button" onClick={() => setStep(0)}>Back</button>
                        <button type="button" className="flck-go" onClick={() => validate() && setStep(2)}>
                          Continue to payment
                        </button>
                      </div>
                    </section>
                  )}
                  {step === 2 && (
                    <section className="flck-pane">
                      <h3>Payment</h3>
                      <label className="flck-field"><span>Card number</span>
                        <input dir="ltr" inputMode="numeric" placeholder="4000 0000 0000 0002"
                          value={card.number} onChange={(e) => setCard({ ...card, number: maskNumber(e.target.value) })} />
                      </label>
                      <div className="flck-duo">
                        <label className="flck-field"><span>Expiry</span>
                          <input dir="ltr" inputMode="numeric" placeholder="MM/YY"
                            value={card.exp} onChange={(e) => setCard({ ...card, exp: maskExp(e.target.value) })} />
                        </label>
                        <label className="flck-field"><span>CVC</span>
                          <input dir="ltr" inputMode="numeric" placeholder="123"
                            value={card.cvc} onChange={(e) => setCard({ ...card, cvc: e.target.value.replace(/\D/g, '').slice(0, 4) })} />
                        </label>
                      </div>
                      {declined && <p className="flck-declined" role="alert">
                        Card declined — try another card or contact your bank.</p>}
                      <div className="flck-nav">
                        <button type="button" onClick={() => setStep(1)}>Back</button>
                        <button type="button" className="flck-go" onClick={pay}>
                          {declined ? 'Try again' : `Pay €${total}`}
                        </button>
                      </div>
                    </section>
                  )}
                  {step === 3 && (
                    <section className="flck-pane flck-done">
                      <h3>Order confirmed</h3>
                      <p>Order <b>{order}</b> ships to {form.city} — arriving in about 5 days.</p>
                    </section>
                  )}
                </div>

                <aside className="flck-aside" aria-label="Order summary">
                  {items.map((i) => <div key={i.id}><span>{i.qty} × {i.name}</span><span>€{i.price * i.qty}</span></div>)}
                  <div><span>Shipping</span><span>{shipping === 0 ? 'Free' : `€${shipping}`}</span></div>
                  <div><span>VAT (19%)</span><span>€{vat}</span></div>
                  <div className="flck-total"><span>Total</span><span>€{total}</span></div>
                </aside>
              </div>
            </>
          );
        }

        const FLCK_CSS = `
          .flck { display: grid; grid-template-columns: minmax(0, 1fr) 15rem; gap: 1.5rem; align-items: start;
                  max-inline-size: 46rem; color: #191b2a; }
          .flck-pane { display: grid; gap: 1rem; padding: 1.5rem; border: 1px solid #e4e6ef;
                       border-radius: 1.25rem; background: #fff; }
          .flck-aside { position: sticky; inset-block-start: 1rem; display: grid; gap: .6rem; padding: 1.25rem;
                        border: 1px solid #e4e6ef; border-radius: 1.25rem; background: #f5f6fb; font-size: .85rem; }
          .flck-aside div { display: flex; justify-content: space-between; gap: 1rem; }
          .flck-total { border-block-start: 2px solid #4f46e5; padding-block-start: .6rem; font-weight: 700; }
          .flck-field { display: grid; gap: .3rem; font-size: .8rem; }
          .flck-field input { padding: .6rem .75rem; border: 1px solid #e4e6ef; border-radius: .6rem; font: inherit; }
          .flck-field input[data-invalid] { border-color: #c62a2f; box-shadow: inset 0 0 0 1px #c62a2f; }
          .flck-error { color: #c62a2f; font-size: .75rem; }
          .flck-declined { margin: 0; padding: .7rem .9rem; border-radius: .7rem; background: #fdecec;
                           border: 1px solid #f5b9bb; color: #a1191e; font-size: .82rem; }
          .flck-nav { display: flex; justify-content: space-between; gap: .75rem; }
          .flck-go { margin-inline-start: auto; padding: .65rem 1.2rem; border: none; border-radius: 999px;
                     background: #4f46e5; color: #fff; cursor: pointer; }
        `;
        REACT,
            'vue' => <<<'VUE'
        <!-- CheckoutFlow.vue — cart → validated address → formatted card → order number -->
        <script setup lang="ts">
        import { computed, reactive, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const initial = () => ([
          { id: 'lamp', name: 'Aster desk lamp', price: 89, qty: 1 },
          { id: 'keys', name: 'Nimbus Keys K2 keyboard', price: 149, qty: 1 },
          { id: 'stand', name: 'Vela laptop stand', price: 59, qty: 2 },
        ]);
        const DECLINED = '4000000000000002';
        let items = ref(initial());
        const step = ref(0);
        const form = reactive({ name: '', street: '', city: '', postcode: '' });
        const errors = reactive<Record<string, string>>({});
        const card = reactive({ number: '', exp: '', cvc: '' });
        const declined = ref(false);
        const order = ref<string | null>(null);

        const subtotal = computed(() => items.value.reduce((s, i) => s + i.price * i.qty, 0));
        const shipping = computed(() => (subtotal.value >= 150 ? 0 : 12));
        const vat = computed(() => Math.round(subtotal.value * 0.19));
        const total = computed(() => subtotal.value + shipping.value + vat.value);

        // Masks that run on every keystroke: fours for the number, MM/YY for the expiry.
        const maskNumber = (v: string) => v.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
        const maskExp = (v: string) => {
          const d = v.replace(/\D/g, '').slice(0, 4);
          return d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
        };
        const bump = (id: string, d: number) => {
          items.value = items.value.map((x) => (x.id === id ? { ...x, qty: Math.max(1, x.qty + d) } : x));
        };

        const validate = () => {
          Object.keys(errors).forEach((k) => delete errors[k]);
          if (form.name.trim().length < 2) errors.name = 'Full name is required';
          if (form.street.trim().length < 3) errors.street = 'Street address is incomplete';
          if (form.city.trim().length < 2) errors.city = 'City is required';
          if (!/^[a-z0-9-]{3,10}$/i.test(form.postcode.trim())) errors.postcode = 'Postcode looks off';
          return Object.keys(errors).length === 0;
        };

        const pay = () => {
          if (card.number.replace(/\s/g, '') === DECLINED) {
            declined.value = true; // the decline banner with its retry
          } else {
            order.value = 'NBX-' + Math.floor(10000 + Math.random() * 90000);
            step.value = 3;
          }
        };
        </script>

        <template>
          <div class="flck">
            <div class="flck-main">
              <section v-if="step === 0" class="flck-pane">
                <h3>Your cart</h3>
                <div v-for="i in items" :key="i.id" class="flck-row">
                  <b>{{ i.name }}</b>
                  <span class="flck-qty">
                    <button type="button" @click="bump(i.id, -1)">−</button>{{ i.qty }}<button type="button" @click="bump(i.id, 1)">+</button>
                  </span>
                  <span>€{{ i.price * i.qty }}</span>
                </div>
                <button type="button" class="flck-go" @click="step = 1">Continue to address</button>
              </section>

              <section v-else-if="step === 1" class="flck-pane">
                <h3>Shipping address</h3>
                <label v-for="f in ['name', 'street', 'city', 'postcode']" :key="f" class="flck-field">
                  <span>{{ f[0].toUpperCase() + f.slice(1) }}</span>
                  <input v-model="form[f]" :data-invalid="errors[f] || undefined" />
                  <small v-if="errors[f]" class="flck-error">{{ errors[f] }}</small>
                </label>
                <div class="flck-nav">
                  <button type="button" @click="step = 0">Back</button>
                  <button type="button" class="flck-go" @click="validate() && (step = 2)">Continue to payment</button>
                </div>
              </section>

              <section v-else-if="step === 2" class="flck-pane">
                <h3>Payment</h3>
                <label class="flck-field"><span>Card number</span>
                  <input v-model="card.number" dir="ltr" inputmode="numeric" placeholder="4000 0000 0000 0002"
                    @input="card.number = maskNumber(card.number)" />
                </label>
                <div class="flck-duo">
                  <label class="flck-field"><span>Expiry</span>
                    <input v-model="card.exp" dir="ltr" inputmode="numeric" placeholder="MM/YY"
                      @input="card.exp = maskExp(card.exp)" />
                  </label>
                  <label class="flck-field"><span>CVC</span>
                    <input v-model="card.cvc" dir="ltr" inputmode="numeric" placeholder="123"
                      @input="card.cvc = card.cvc.replace(/\D/g, '').slice(0, 4)" />
                  </label>
                </div>
                <p v-if="declined" class="flck-declined" role="alert">
                  Card declined — try another card or contact your bank.</p>
                <div class="flck-nav">
                  <button type="button" @click="step = 1">Back</button>
                  <button type="button" class="flck-go" @click="pay">
                    {{ declined ? 'Try again' : `Pay €${total}` }}
                  </button>
                </div>
              </section>

              <section v-else class="flck-pane flck-done">
                <h3>Order confirmed</h3>
                <p>Order <b>{{ order }}</b> ships to {{ form.city }} — arriving in about 5 days.</p>
              </section>
            </div>

            <aside class="flck-aside" aria-label="Order summary">
              <div v-for="i in items" :key="i.id"><span>{{ i.qty }} × {{ i.name }}</span><span>€{{ i.price * i.qty }}</span></div>
              <div><span>Shipping</span><span>{{ shipping === 0 ? 'Free' : `€${shipping}` }}</span></div>
              <div><span>VAT (19%)</span><span>€{{ vat }}</span></div>
              <div class="flck-total"><span>Total</span><span>€{{ total }}</span></div>
            </aside>
          </div>
        </template>

        <style scoped>
        .flck { display: grid; grid-template-columns: minmax(0, 1fr) 15rem; gap: 1.5rem; align-items: start;
                max-inline-size: 46rem; color: #191b2a; }
        .flck-pane { display: grid; gap: 1rem; padding: 1.5rem; border: 1px solid #e4e6ef;
                     border-radius: 1.25rem; background: #fff; }
        .flck-aside { position: sticky; inset-block-start: 1rem; display: grid; gap: .6rem; padding: 1.25rem;
                      border: 1px solid #e4e6ef; border-radius: 1.25rem; background: #f5f6fb; font-size: .85rem; }
        .flck-aside div { display: flex; justify-content: space-between; gap: 1rem; }
        .flck-total { border-block-start: 2px solid #4f46e5; padding-block-start: .6rem; font-weight: 700; }
        .flck-field { display: grid; gap: .3rem; font-size: .8rem; }
        .flck-field input { padding: .6rem .75rem; border: 1px solid #e4e6ef; border-radius: .6rem; font: inherit; }
        .flck-field input[data-invalid] { border-color: #c62a2f; box-shadow: inset 0 0 0 1px #c62a2f; }
        .flck-error { color: #c62a2f; font-size: .75rem; }
        .flck-declined { margin: 0; padding: .7rem .9rem; border-radius: .7rem; background: #fdecec;
                         border: 1px solid #f5b9bb; color: #a1191e; font-size: .82rem; }
        .flck-nav { display: flex; justify-content: space-between; gap: .75rem; }
        .flck-go { margin-inline-start: auto; padding: .65rem 1.2rem; border: none; border-radius: 999px;
                   background: #4f46e5; color: #fff; cursor: pointer; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- CheckoutFlow.svelte — cart → validated address → formatted card → order number -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          type Item = { id: string; name: string; price: number; qty: number };
          const initial = (): Item[] => [
            { id: 'lamp', name: 'Aster desk lamp', price: 89, qty: 1 },
            { id: 'keys', name: 'Nimbus Keys K2 keyboard', price: 149, qty: 1 },
            { id: 'stand', name: 'Vela laptop stand', price: 59, qty: 2 },
          ];
          const DECLINED = '4000000000000002';
          let items = $state<Item[]>(initial());
          let step = $state(0);
          let form = $state({ name: '', street: '', city: '', postcode: '' });
          let errors = $state<Record<string, string>>({});
          let card = $state({ number: '', exp: '', cvc: '' });
          let declined = $state(false);
          let order = $state<string | null>(null);

          const subtotal = $derived(items.reduce((s, i) => s + i.price * i.qty, 0));
          const shipping = $derived(subtotal >= 150 ? 0 : 12);
          const vat = $derived(Math.round(subtotal * 0.19));
          const total = $derived(subtotal + shipping + vat);

          // Masks that run on every keystroke: fours for the number, MM/YY for the expiry.
          const maskNumber = (v: string) => v.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
          const maskExp = (v: string) => {
            const d = v.replace(/\D/g, '').slice(0, 4);
            return d.length > 2 ? d.slice(0, 2) + '/' + d.slice(2) : d;
          };
          const bump = (id: string, d: number) => {
            items = items.map((x) => (x.id === id ? { ...x, qty: Math.max(1, x.qty + d) } : x));
          };

          const validate = () => {
            errors = {};
            if (form.name.trim().length < 2) errors.name = 'Full name is required';
            if (form.street.trim().length < 3) errors.street = 'Street address is incomplete';
            if (form.city.trim().length < 2) errors.city = 'City is required';
            if (!/^[a-z0-9-]{3,10}$/i.test(form.postcode.trim())) errors.postcode = 'Postcode looks off';
            return Object.keys(errors).length === 0;
          };

          const pay = () => {
            if (card.number.replace(/\s/g, '') === DECLINED) {
              declined = true; // the decline banner with its retry
            } else {
              order = 'NBX-' + Math.floor(10000 + Math.random() * 90000);
              step = 3;
            }
          };
        </script>

        <div class="flck">
          <div class="flck-main">
            {#if step === 0}
              <section class="flck-pane">
                <h3>Your cart</h3>
                {#each items as i (i.id)}
                  <div class="flck-row">
                    <b>{i.name}</b>
                    <span class="flck-qty">
                      <button type="button" onclick={() => bump(i.id, -1)}>−</button>{i.qty}<button type="button" onclick={() => bump(i.id, 1)}>+</button>
                    </span>
                    <span>€{i.price * i.qty}</span>
                  </div>
                {/each}
                <button type="button" class="flck-go" onclick={() => (step = 1)}>Continue to address</button>
              </section>
            {:else if step === 1}
              <section class="flck-pane">
                <h3>Shipping address</h3>
                {#each ['name', 'street', 'city', 'postcode'] as f (f)}
                  <label class="flck-field">
                    <span>{f[0].toUpperCase() + f.slice(1)}</span>
                    <input bind:value={form[f]} data-invalid={errors[f] || undefined} />
                    {#if errors[f]}<small class="flck-error">{errors[f]}</small>{/if}
                  </label>
                {/each}
                <div class="flck-nav">
                  <button type="button" onclick={() => (step = 0)}>Back</button>
                  <button type="button" class="flck-go" onclick={() => validate() && (step = 2)}>Continue to payment</button>
                </div>
              </section>
            {:else if step === 2}
              <section class="flck-pane">
                <h3>Payment</h3>
                <label class="flck-field"><span>Card number</span>
                  <input dir="ltr" inputmode="numeric" placeholder="4000 0000 0000 0002" bind:value={card.number}
                    oninput={() => (card.number = maskNumber(card.number))} />
                </label>
                <div class="flck-duo">
                  <label class="flck-field"><span>Expiry</span>
                    <input dir="ltr" inputmode="numeric" placeholder="MM/YY" bind:value={card.exp}
                      oninput={() => (card.exp = maskExp(card.exp))} />
                  </label>
                  <label class="flck-field"><span>CVC</span>
                    <input dir="ltr" inputmode="numeric" placeholder="123" bind:value={card.cvc}
                      oninput={() => (card.cvc = card.cvc.replace(/\D/g, '').slice(0, 4))} />
                  </label>
                </div>
                {#if declined}
                  <p class="flck-declined" role="alert">Card declined — try another card or contact your bank.</p>
                {/if}
                <div class="flck-nav">
                  <button type="button" onclick={() => (step = 1)}>Back</button>
                  <button type="button" class="flck-go" onclick={pay}>
                    {declined ? 'Try again' : `Pay €${total}`}
                  </button>
                </div>
              </section>
            {:else}
              <section class="flck-pane flck-done">
                <h3>Order confirmed</h3>
                <p>Order <b>{order}</b> ships to {form.city} — arriving in about 5 days.</p>
              </section>
            {/if}
          </div>

          <aside class="flck-aside" aria-label="Order summary">
            {#each items as i (i.id)}
              <div><span>{i.qty} × {i.name}</span><span>€{i.price * i.qty}</span></div>
            {/each}
            <div><span>Shipping</span><span>{shipping === 0 ? 'Free' : `€${shipping}`}</span></div>
            <div><span>VAT (19%)</span><span>€{vat}</span></div>
            <div class="flck-total"><span>Total</span><span>€{total}</span></div>
          </aside>
        </div>

        <style>
          .flck { display: grid; grid-template-columns: minmax(0, 1fr) 15rem; gap: 1.5rem; align-items: start;
                  max-inline-size: 46rem; color: #191b2a; }
          .flck-pane { display: grid; gap: 1rem; padding: 1.5rem; border: 1px solid #e4e6ef;
                       border-radius: 1.25rem; background: #fff; }
          .flck-aside { position: sticky; inset-block-start: 1rem; display: grid; gap: .6rem; padding: 1.25rem;
                        border: 1px solid #e4e6ef; border-radius: 1.25rem; background: #f5f6fb; font-size: .85rem; }
          .flck-aside div { display: flex; justify-content: space-between; gap: 1rem; }
          .flck-total { border-block-start: 2px solid #4f46e5; padding-block-start: .6rem; font-weight: 700; }
          .flck-field { display: grid; gap: .3rem; font-size: .8rem; }
          .flck-field input { padding: .6rem .75rem; border: 1px solid #e4e6ef; border-radius: .6rem; font: inherit; }
          .flck-field input[data-invalid] { border-color: #c62a2f; box-shadow: inset 0 0 0 1px #c62a2f; }
          .flck-error { color: #c62a2f; font-size: .75rem; }
          .flck-declined { margin: 0; padding: .7rem .9rem; border-radius: .7rem; background: #fdecec;
                           border: 1px solid #f5b9bb; color: #a1191e; font-size: .82rem; }
          .flck-nav { display: flex; justify-content: space-between; gap: .75rem; }
          .flck-go { margin-inline-start: auto; padding: .65rem 1.2rem; border: none; border-radius: 999px;
                     background: #4f46e5; color: #fff; cursor: pointer; }
        </style>
        SVELTE,
        ],
    ],

    'auth-flow' => [
        'title' => ['fa' => 'جریان ورود', 'en' => 'Auth flow'],
        'icon' => 'shield',
        'oneLiner' => [
            'fa' => 'ورود دو مرحله‌ای واقعی: ایمیل و رمز، بعد کد ۶رقمی که خانه‌هایش خودشان فوکوس می‌شوند، چسباندن را یک‌جا می‌پذیرند و پاک‌کردن با Backspace به خانهٔ قبل برمی‌گردد؛ تایمر ارسال دوباره می‌شمارد، کد نادرست قاب را می‌لرزاند و مسیر فراموشی رمز دو قدمی کنارش است.',
            'en' => 'A real two-step sign-in: email and password, then a six-digit code whose boxes focus themselves, accept a whole paste at once and give Backspace back to the previous box; the resend timer counts, a wrong code shakes the frame, and the two-step forgot-password branch sits beside it.',
        ],
        'js' => true,
        'docs' => 'https://mobbin.com',
        'props' => [
            ['name' => 'steps', 'type' => 'count', 'default' => "'2'", 'note' => [
                'fa' => 'ورود → کد ۶رقمی → خوش‌آمد؛ لینک «رمز را فراموش کرده‌ام» مسیر دو قدمی خودش (ایمیل → ارسال‌شد) را باز می‌کند.',
                'en' => 'sign-in → six-digit code → welcome; the “forgot password” link opens its own two-step path (email → sent).',
            ]],
            ['name' => 'boxes', 'type' => 'behaviour', 'default' => "'auto-advance'", 'note' => [
                'fa' => 'هر خانه با یک رقم پر می‌شود و فوکوس می‌رود بعدی؛ چسباندن کد کامل همهٔ خانه‌ها را یک‌جا پر می‌کند و Backscore روی خانهٔ خالی به قبلی برمی‌گردد.',
                'en' => 'Each box fills with one digit and focuses the next; pasting the whole code fills every box at once, and Backspace on an empty box steps back.',
            ]],
            ['name' => 'resend', 'type' => 'timer', 'default' => "'30s'", 'note' => [
                'fa' => 'شمارندهٔ معکوس ارسال دوباره؛ تا صفر نشده دکمه خواب است و تایمر با تخریب کامپوننت پاک می‌شود.',
                'en' => 'The resend countdown; the button sleeps until zero and the timer clears itself when the component dies.',
            ]],
            ['name' => 'error', 'type' => 'animation', 'default' => "'shake · 380ms'", 'note' => [
                'fa' => 'کد نادرست قاب کد را می‌لرزاند، خانه‌ها را خالی و قرمز می‌کند و پیام زیرش می‌نشیند — با prefers-reduced-motion بی‌لرزش.',
                'en' => 'A wrong code shakes the code frame, empties and reddens the boxes and parks a message beneath — no shake under prefers-reduced-motion.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="flau" x-data="{
            stage: 'login', code: ['', '', '', '', '', ''], error: '', timer: 30, tick: null,
            digits() { return this.code.join('') },
            type(i, e) {
                const d = e.target.value.replace(/\D/g, '').slice(-1);
                this.code[i] = d; e.target.value = d;
                if (d && i < 5) this.$refs.box[i + 1].focus();
            },
            back(i, e) { if (!e.target.value && i > 0) this.$refs.box[i - 1].focus(); },
            paste(e) {
                const d = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
                this.code = d.padEnd(6, '').split('').slice(0, 6);
            },
            verify() {
                if (this.digits() !== '284913') {
                    this.error = 'کد درست نیست — دوباره بزنید'; this.code = ['', '', '', '', '', ''];
                    this.$refs.box[0].focus(); return;
                }
                this.stage = 'ok';
            },
        }">
            <template x-if="stage === 'login'">
                <form @submit.prevent="stage = 'code'">
                    <input type="email" placeholder="maya@nordvana.io" required>
                    <input type="password" placeholder="••••••••" required>
                    <button>ورود با کد تأیید</button>
                    <a href="#" @click.prevent="stage = 'forgot'">رمز را فراموش کرده‌ام</a>
                </form>
            </template>

            <div class="flau-boxes" x-show="stage === 'code'" x-cloak :class="error && 'flau-shake'">
                <template x-for="(d, i) in code" :key="i">
                    <input maxlength="1" inputmode="numeric" dir="ltr" :value="d"
                        @input="type(i, $event)" @keydown.backspace="back(i, $event)" @paste="paste">
                </template>
            </div>
        </div>

        <style>
        .flau-boxes { display: flex; gap: .5rem; }
        .flau-boxes input { inline-size: 2.75rem; block-size: 3.25rem; text-align: center;
                            border: 1px solid #e4e6ef; border-radius: .8rem; font-size: 1.25rem; }
        .flau-boxes input:focus { outline: 2px solid #4f46e5; outline-offset: 1px; }
        .flau-shake { animation: flau-shake .38s; }
        @keyframes flau-shake { 20%, 60% { translate: -7px 0; } 40%, 80% { translate: 7px 0; } }
        @media (prefers-reduced-motion: reduce) { .flau-shake { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="flau" x-data="{
            stage: 'login', code: ['', '', '', '', '', ''], error: '', timer: 30, tick: null,
            digits() { return this.code.join('') },
            type(i, e) {
                const d = e.target.value.replace(/\D/g, '').slice(-1);
                this.code[i] = d; e.target.value = d;
                if (d && i < 5) this.$refs.box[i + 1].focus();
            },
            back(i, e) { if (!e.target.value && i > 0) this.$refs.box[i - 1].focus(); },
            paste(e) {
                const d = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
                this.code = d.padEnd(6, '').split('').slice(0, 6);
            },
            verify() {
                if (this.digits() !== '284913') {
                    this.error = 'That code is not right — try again'; this.code = ['', '', '', '', '', ''];
                    this.$refs.box[0].focus(); return;
                }
                this.stage = 'ok';
            },
        }">
            <template x-if="stage === 'login'">
                <form @submit.prevent="stage = 'code'">
                    <input type="email" placeholder="maya@nordvana.io" required>
                    <input type="password" placeholder="••••••••" required>
                    <button>Continue with code</button>
                    <a href="#" @click.prevent="stage = 'forgot'">Forgot password?</a>
                </form>
            </template>

            <div class="flau-boxes" x-show="stage === 'code'" x-cloak :class="error && 'flau-shake'">
                <template x-for="(d, i) in code" :key="i">
                    <input maxlength="1" inputmode="numeric" dir="ltr" :value="d"
                        @input="type(i, $event)" @keydown.backspace="back(i, $event)" @paste="paste">
                </template>
            </div>
        </div>

        <style>
        .flau-boxes { display: flex; gap: .5rem; }
        .flau-boxes input { inline-size: 2.75rem; block-size: 3.25rem; text-align: center;
                            border: 1px solid #e4e6ef; border-radius: .8rem; font-size: 1.25rem; }
        .flau-boxes input:focus { outline: 2px solid #4f46e5; outline-offset: 1px; }
        .flau-shake { animation: flau-shake .38s; }
        @keyframes flau-shake { 20%, 60% { translate: -7px 0; } 40%, 80% { translate: 7px 0; } }
        @media (prefers-reduced-motion: reduce) { .flau-shake { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/SignIn.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import AuthFlow from '@/components/AuthFlow';

        export default function SignIn() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <AuthFlow />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // AuthFlow.jsx — sign-in → six-digit code → welcome, plus the forgot branch
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const CODE = '284913';

        export default function AuthFlow() {
          const [stage, setStage] = useState('login'); // login | code | ok | forgot-email | forgot-sent
          const [code, setCode] = useState(Array(6).fill(''));
          const [error, setError] = useState('');
          const [timer, setTimer] = useState(30);
          const boxes = useRef([]);

          // The resend countdown: one interval, cleaned on unmount (and on resend).
          useEffect(() => {
            if (stage !== 'code' || timer <= 0) return;
            const t = setTimeout(() => setTimer((s) => s - 1), 1000);
            return () => clearTimeout(t);
          }, [stage, timer]);

          // Auto-focus walks with the digits; pasting the whole code fills every box.
          const type = (i, e) => {
            const d = e.target.value.replace(/\D/g, '').slice(-1);
            setCode(code.map((c, j) => (j === i ? d : c)));
            if (d && i < 5) boxes.current[i + 1]?.focus();
          };
          const back = (i, e) => { if (!e.target.value && i > 0) boxes.current[i - 1]?.focus(); };
          const paste = (e) => {
            const d = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
            if (!d) return;
            e.preventDefault();
            setCode(d.padEnd(6, '').slice(0, 6).split(''));
            boxes.current[Math.min(5, d.length)]?.focus();
          };

          const verify = () => {
            if (code.join('') !== CODE) {
              setError('That code is not right — try again');
              setCode(Array(6).fill(''));
              boxes.current[0]?.focus();
              return;
            }
            setError('');
            setStage('ok');
          };

          return (
            <>
              <style>{FLAU_CSS}</style>
              <div className="flau">
                {stage === 'login' && (
                  <form className="flau-pane" onSubmit={(e) => { e.preventDefault(); setStage('code'); }}>
                    <h3>Sign in to Northwind</h3>
                    <label className="flau-field"><span>Email</span>
                      <input type="email" placeholder="maya@nordvana.io" required /></label>
                    <label className="flau-field"><span>Password</span>
                      <input type="password" placeholder="••••••••" required /></label>
                    <button type="submit" className="flau-go">Continue with code</button>
                    <button type="button" className="flau-link"
                      onClick={() => setStage('forgot-email')}>Forgot password?</button>
                  </form>
                )}

                {stage === 'code' && (
                  <section className="flau-pane">
                    <h3>Check your inbox</h3>
                    <p>We sent a six-digit code to your email. Demo code: <b>{CODE}</b>.</p>
                    <div className={'flau-boxes' + (error ? ' flau-shake' : '')}>
                      {code.map((d, i) => (
                        <input key={i} ref={(el) => (boxes.current[i] = el)} maxLength={1} inputMode="numeric"
                          dir="ltr" value={d} onChange={(e) => type(i, e)}
                          onKeyDown={(e) => e.key === 'Backspace' && back(i, e)} onPaste={paste} />
                      ))}
                    </div>
                    {error && <p className="flau-error" role="alert">{error}</p>}
                    <button type="button" className="flau-go" onClick={verify}>Verify</button>
                    <button type="button" className="flau-link" disabled={timer > 0}
                      onClick={() => { setTimer(30); setError(''); }}>
                      {timer > 0 ? `Resend in ${timer}s` : 'Resend code'}
                    </button>
                  </section>
                )}

                {stage === 'ok' && (
                  <section className="flau-pane flau-ok">
                    <span className="flau-check" aria-hidden="true">✓</span>
                    <h3>Welcome back, Maya</h3>
                    <button type="button" className="flau-go"
                      onClick={() => { setStage('login'); setCode(Array(6).fill('')); setTimer(30); }}>
                      Sign out
                    </button>
                  </section>
                )}

                {(stage === 'forgot-email' || stage === 'forgot-sent') && (
                  <section className="flau-pane">
                    <h3>Reset your password</h3>
                    {stage === 'forgot-email' ? (
                      <>
                        <p>Step 1 of 2 — tell us the account email.</p>
                        <form onSubmit={(e) => { e.preventDefault(); setStage('forgot-sent'); }}>
                          <label className="flau-field"><span>Email</span>
                            <input type="email" placeholder="maya@nordvana.io" required /></label>
                          <button type="submit" className="flau-go">Send reset link</button>
                        </form>
                      </>
                    ) : (
                      <>
                        <p>Step 2 of 2 — the reset link is on its way. It lives for 20 minutes.</p>
                        <button type="button" className="flau-go" onClick={() => setStage('login')}>
                          Back to sign in
                        </button>
                      </>
                    )}
                  </section>
                )}
              </div>
            </>
          );
        }

        const FLAU_CSS = `
          .flau { display: grid; justify-items: center; }
          .flau-pane { display: grid; gap: .9rem; inline-size: min(100%, 22rem); padding: 1.5rem;
                       border: 1px solid #e4e6ef; border-radius: 1.25rem; background: #fff; color: #191b2a; }
          .flau-field { display: grid; gap: .3rem; font-size: .8rem; }
          .flau-field input { min-block-size: 2.6rem; padding-inline: .8rem; border: 1px solid #e4e6ef;
                              border-radius: .7rem; font: inherit; }
          .flau-boxes { display: flex; gap: .5rem; justify-content: center; }
          .flau-boxes input { inline-size: 2.75rem; block-size: 3.25rem; text-align: center;
                              border: 1px solid #e4e6ef; border-radius: .8rem; font-size: 1.25rem; }
          .flau-boxes input:focus { outline: 2px solid #4f46e5; outline-offset: 1px; }
          .flau-error { margin: 0; color: #c62a2f; font-size: .8rem; text-align: center; }
          .flau-shake { animation: flau-shake .38s; }
          @keyframes flau-shake { 20%, 60% { translate: -7px 0; } 40%, 80% { translate: 7px 0; } }
          .flau-go { min-block-size: 2.7rem; border: none; border-radius: 999px; background: #4f46e5;
                     color: #fff; font: 600 .88rem/1 inherit; cursor: pointer; }
          .flau-link { border: none; background: none; color: #4f46e5; cursor: pointer; font-size: .82rem; }
          .flau-link:disabled { color: #8a8fa3; cursor: default; }
          .flau-ok { justify-items: center; text-align: center; }
          .flau-check { display: grid; place-items: center; inline-size: 3.5rem; aspect-ratio: 1; border-radius: 50%;
                        background: #e7f6ec; color: #0f8a43; font-size: 1.5rem; }
          @media (prefers-reduced-motion: reduce) { .flau-shake { animation: none; } }
        `;
        REACT,
            'vue' => <<<'VUE'
        <!-- AuthFlow.vue — sign-in → six-digit code → welcome, plus the forgot branch -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const CODE = '284913';
        const stage = ref<'login' | 'code' | 'ok' | 'forgot-email' | 'forgot-sent'>('login');
        const code = ref<string[]>(Array(6).fill(''));
        const error = ref('');
        const timer = ref(30);
        let tick: number | undefined;
        const boxes = ref<HTMLInputElement[]>([]);

        // The resend countdown: one interval, cleared on unmount and on resend.
        onMounted(() => {
          tick = window.setInterval(() => {
            if (stage.value === 'code' && timer.value > 0) timer.value--;
          }, 1000);
        });
        onBeforeUnmount(() => window.clearInterval(tick));

        const type = (i: number, e: Event) => {
          const el = e.target as HTMLInputElement;
          const d = el.value.replace(/\D/g, '').slice(-1);
          code.value = code.value.map((c, j) => (j === i ? d : c));
          el.value = d;
          if (d && i < 5) boxes.value[i + 1]?.focus();
        };
        const back = (i: number, e: KeyboardEvent) => {
          if (!(e.target as HTMLInputElement).value && i > 0) boxes.value[i - 1]?.focus();
        };
        const paste = (e: ClipboardEvent) => {
          const d = (e.clipboardData?.getData('text') || '').replace(/\D/g, '').slice(0, 6);
          if (!d) return;
          e.preventDefault();
          code.value = d.padEnd(6, '').slice(0, 6).split('');
          boxes.value[Math.min(5, d.length)]?.focus();
        };

        const verify = () => {
          if (code.value.join('') !== CODE) {
            error.value = 'That code is not right — try again';
            code.value = Array(6).fill('');
            boxes.value[0]?.focus();
            return;
          }
          error.value = '';
          stage.value = 'ok';
        };
        const resend = () => { timer.value = 30; error.value = ''; };
        </script>

        <template>
          <div class="flau">
            <form v-if="stage === 'login'" class="flau-pane" @submit.prevent="stage = 'code'">
              <h3>Sign in to Northwind</h3>
              <label class="flau-field"><span>Email</span>
                <input type="email" placeholder="maya@nordvana.io" required /></label>
              <label class="flau-field"><span>Password</span>
                <input type="password" placeholder="••••••••" required /></label>
              <button type="submit" class="flau-go">Continue with code</button>
              <button type="button" class="flau-link" @click="stage = 'forgot-email'">Forgot password?</button>
            </form>

            <section v-else-if="stage === 'code'" class="flau-pane">
              <h3>Check your inbox</h3>
              <p>We sent a six-digit code to your email. Demo code: <b>{{ CODE }}</b>.</p>
              <!-- Auto-focus walks with the digits; pasting fills every box at once. -->
              <div class="flau-boxes" :class="{ 'flau-shake': error }">
                <input v-for="(d, i) in code" :key="i" :ref="(el) => (boxes[i] = el as HTMLInputElement)"
                  maxlength="1" inputmode="numeric" dir="ltr" :value="d"
                  @input="type(i, $event)" @keydown.backspace="back(i, $event)" @paste="paste" />
              </div>
              <p v-if="error" class="flau-error" role="alert">{{ error }}</p>
              <button type="button" class="flau-go" @click="verify">Verify</button>
              <button type="button" class="flau-link" :disabled="timer > 0" @click="resend">
                {{ timer > 0 ? `Resend in ${timer}s` : 'Resend code' }}
              </button>
            </section>

            <section v-else-if="stage === 'ok'" class="flau-pane flau-ok">
              <span class="flau-check" aria-hidden="true">✓</span>
              <h3>Welcome back, Maya</h3>
              <button type="button" class="flau-go"
                @click="stage = 'login'; code = Array(6).fill(''); timer = 30">Sign out</button>
            </section>

            <section v-else class="flau-pane">
              <h3>Reset your password</h3>
              <template v-if="stage === 'forgot-email'">
                <p>Step 1 of 2 — tell us the account email.</p>
                <form @submit.prevent="stage = 'forgot-sent'">
                  <label class="flau-field"><span>Email</span>
                    <input type="email" placeholder="maya@nordvana.io" required /></label>
                  <button type="submit" class="flau-go">Send reset link</button>
                </form>
              </template>
              <template v-else>
                <p>Step 2 of 2 — the reset link is on its way. It lives for 20 minutes.</p>
                <button type="button" class="flau-go" @click="stage = 'login'">Back to sign in</button>
              </template>
            </section>
          </div>
        </template>

        <style scoped>
        .flau { display: grid; justify-items: center; }
        .flau-pane { display: grid; gap: .9rem; inline-size: min(100%, 22rem); padding: 1.5rem;
                     border: 1px solid #e4e6ef; border-radius: 1.25rem; background: #fff; color: #191b2a; }
        .flau-field { display: grid; gap: .3rem; font-size: .8rem; }
        .flau-field input { min-block-size: 2.6rem; padding-inline: .8rem; border: 1px solid #e4e6ef;
                            border-radius: .7rem; font: inherit; }
        .flau-boxes { display: flex; gap: .5rem; justify-content: center; }
        .flau-boxes input { inline-size: 2.75rem; block-size: 3.25rem; text-align: center;
                            border: 1px solid #e4e6ef; border-radius: .8rem; font-size: 1.25rem; }
        .flau-boxes input:focus { outline: 2px solid #4f46e5; outline-offset: 1px; }
        .flau-error { margin: 0; color: #c62a2f; font-size: .8rem; text-align: center; }
        .flau-shake { animation: flau-shake .38s; }
        @keyframes flau-shake { 20%, 60% { translate: -7px 0; } 40%, 80% { translate: 7px 0; } }
        .flau-go { min-block-size: 2.7rem; border: none; border-radius: 999px; background: #4f46e5;
                   color: #fff; font: 600 .88rem/1 inherit; cursor: pointer; }
        .flau-link { border: none; background: none; color: #4f46e5; cursor: pointer; font-size: .82rem; }
        .flau-link:disabled { color: #8a8fa3; cursor: default; }
        .flau-ok { justify-items: center; text-align: center; }
        .flau-check { display: grid; place-items: center; inline-size: 3.5rem; aspect-ratio: 1; border-radius: 50%;
                      background: #e7f6ec; color: #0f8a43; font-size: 1.5rem; }
        @media (prefers-reduced-motion: reduce) { .flau-shake { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- AuthFlow.svelte — sign-in → six-digit code → welcome, plus the forgot branch -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const CODE = '284913';
          let stage = $state<'login' | 'code' | 'ok' | 'forgot-email' | 'forgot-sent'>('login');
          let code = $state<string[]>(Array(6).fill(''));
          let error = $state('');
          let timer = $state(30);
          let boxes: HTMLInputElement[] = $state([]);

          // The resend countdown: one interval, cleared on unmount and on resend.
          onMount(() => {
            const tick = window.setInterval(() => {
              if (stage === 'code' && timer > 0) timer--;
            }, 1000);
            return () => window.clearInterval(tick);
          });

          // Auto-focus walks with the digits; pasting the whole code fills every box.
          const type = (i: number, e: Event) => {
            const el = e.target as HTMLInputElement;
            const d = el.value.replace(/\D/g, '').slice(-1);
            code = code.map((c, j) => (j === i ? d : c));
            el.value = d;
            if (d && i < 5) boxes[i + 1]?.focus();
          };
          const back = (i: number, e: KeyboardEvent) => {
            if (!(e.target as HTMLInputElement).value && i > 0) boxes[i - 1]?.focus();
          };
          const paste = (e: ClipboardEvent) => {
            const d = (e.clipboardData?.getData('text') || '').replace(/\D/g, '').slice(0, 6);
            if (!d) return;
            e.preventDefault();
            code = d.padEnd(6, '').slice(0, 6).split('');
            boxes[Math.min(5, d.length)]?.focus();
          };

          const verify = () => {
            if (code.join('') !== CODE) {
              error = 'That code is not right — try again';
              code = Array(6).fill('');
              boxes[0]?.focus();
              return;
            }
            error = '';
            stage = 'ok';
          };
          const resend = () => { timer = 30; error = ''; };
        </script>

        <div class="flau">
          {#if stage === 'login'}
            <form class="flau-pane" onsubmit={(e) => { e.preventDefault(); stage = 'code'; }}>
              <h3>Sign in to Northwind</h3>
              <label class="flau-field"><span>Email</span>
                <input type="email" placeholder="maya@nordvana.io" required /></label>
              <label class="flau-field"><span>Password</span>
                <input type="password" placeholder="••••••••" required /></label>
              <button type="submit" class="flau-go">Continue with code</button>
              <button type="button" class="flau-link" onclick={() => (stage = 'forgot-email')}>Forgot password?</button>
            </form>
          {:else if stage === 'code'}
            <section class="flau-pane">
              <h3>Check your inbox</h3>
              <p>We sent a six-digit code to your email. Demo code: <b>{CODE}</b>.</p>
              <div class="flau-boxes" class:flau-shake={!!error}>
                {#each code as d, i (i)}
                  <input bind:this={boxes[i]} maxlength={1} inputmode="numeric" dir="ltr" value={d}
                    oninput={(e) => type(i, e)} onkeydown={(e) => e.key === 'Backspace' && back(i, e)}
                    onpaste={paste} />
                {/each}
              </div>
              {#if error}<p class="flau-error" role="alert">{error}</p>{/if}
              <button type="button" class="flau-go" onclick={verify}>Verify</button>
              <button type="button" class="flau-link" disabled={timer > 0} onclick={resend}>
                {timer > 0 ? `Resend in ${timer}s` : 'Resend code'}
              </button>
            </section>
          {:else if stage === 'ok'}
            <section class="flau-pane flau-ok">
              <span class="flau-check" aria-hidden="true">✓</span>
              <h3>Welcome back, Maya</h3>
              <button type="button" class="flau-go"
                onclick={() => { stage = 'login'; code = Array(6).fill(''); timer = 30; }}>Sign out</button>
            </section>
          {:else}
            <section class="flau-pane">
              <h3>Reset your password</h3>
              {#if stage === 'forgot-email'}
                <p>Step 1 of 2 — tell us the account email.</p>
                <form onsubmit={(e) => { e.preventDefault(); stage = 'forgot-sent'; }}>
                  <label class="flau-field"><span>Email</span>
                    <input type="email" placeholder="maya@nordvana.io" required /></label>
                  <button type="submit" class="flau-go">Send reset link</button>
                </form>
              {:else}
                <p>Step 2 of 2 — the reset link is on its way. It lives for 20 minutes.</p>
                <button type="button" class="flau-go" onclick={() => (stage = 'login')}>Back to sign in</button>
              {/if}
            </section>
          {/if}
        </div>

        <style>
          .flau { display: grid; justify-items: center; }
          .flau-pane { display: grid; gap: .9rem; inline-size: min(100%, 22rem); padding: 1.5rem;
                       border: 1px solid #e4e6ef; border-radius: 1.25rem; background: #fff; color: #191b2a; }
          .flau-field { display: grid; gap: .3rem; font-size: .8rem; }
          .flau-field input { min-block-size: 2.6rem; padding-inline: .8rem; border: 1px solid #e4e6ef;
                              border-radius: .7rem; font: inherit; }
          .flau-boxes { display: flex; gap: .5rem; justify-content: center; }
          .flau-boxes input { inline-size: 2.75rem; block-size: 3.25rem; text-align: center;
                              border: 1px solid #e4e6ef; border-radius: .8rem; font-size: 1.25rem; }
          .flau-boxes input:focus { outline: 2px solid #4f46e5; outline-offset: 1px; }
          .flau-error { margin: 0; color: #c62a2f; font-size: .8rem; text-align: center; }
          .flau-shake { animation: flau-shake .38s; }
          @keyframes flau-shake { 20%, 60% { translate: -7px 0; } 40%, 80% { translate: 7px 0; } }
          .flau-go { min-block-size: 2.7rem; border: none; border-radius: 999px; background: #4f46e5;
                     color: #fff; font: 600 .88rem/1 inherit; cursor: pointer; }
          .flau-link { border: none; background: none; color: #4f46e5; cursor: pointer; font-size: .82rem; }
          .flau-link:disabled { color: #8a8fa3; cursor: default; }
          .flau-ok { justify-items: center; text-align: center; }
          .flau-check { display: grid; place-items: center; inline-size: 3.5rem; aspect-ratio: 1; border-radius: 50%;
                        background: #e7f6ec; color: #0f8a43; font-size: 1.5rem; }
          @media (prefers-reduced-motion: reduce) { .flau-shake { animation: none; } }
        </style>
        SVELTE,
        ],
    ],

    'search-flow' => [
        'title' => ['fa' => 'جریان جست‌وجو', 'en' => 'Search flow'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'جست‌وجوی محصول از اولین حرف: پیشنهادها زیر خانهٔ تایپ زنده می‌جوشند، نتایج با ریل فیلتر چپی (دسته، سقف قیمت، مرتب‌سازی) همان لحظه صافی می‌شوند، جایی که هیچ‌چیز پیدا نمی‌شود حالت هیچ‌نتیجه با پیشنهاد خودش را نشان می‌دهد، و کلیک روی هر آیتم کشوی جزئیاتش را از کنار داخل می‌آورد.',
            'en' => 'Product search from the first keystroke: suggestions boil under the box while you type, results re-filter the instant the left rail (category, price cap, sort) moves, the nothing-found state offers its own way out, and clicking an item slides its details drawer in from the side.',
        ],
        'js' => true,
        'docs' => 'https://mobbin.com',
        'props' => [
            ['name' => 'suggestions', 'type' => 'behaviour', 'default' => "'live · 5'", 'note' => [
                'fa' => 'از حرف دوم به بعد، بیشینهٔ پنج پیشنهاد از نام و توضیح محصولات زیر خانه می‌آیند؛ خالی بودن فهرست، پرطرفدارها را نشان می‌دهد.',
                'en' => 'From the second character on, up to five suggestions surface from product names and meta; an empty query shows what’s trending.',
            ]],
            ['name' => 'filters', 'type' => 'rail', 'default' => "'category · price · sort'", 'note' => [
                'fa' => 'ریل چپی با چک‌باکس دسته‌ها، سقف قیمت اسلایدر و مرتب‌سازی؛ هر تغییری فوراً همان صفحهٔ نتایج را صافی می‌کند — بدون دکمهٔ اعمال.',
                'en' => 'The left rail with category checkboxes, a slider price cap and sort; every change filters the same results page instantly — no Apply button.',
            ]],
            ['name' => 'empty', 'type' => 'state', 'default' => "'no-results'", 'note' => [
                'fa' => 'وقتی فیلتر و پرسش هیچ کالا را نگه نمی‌دارند، حالت هیچ‌نتیجه با سه پیشنهاد واقعی می‌آید تا مسیر ادامه داشته باشد.',
                'en' => 'When the query and filters hold nothing back, the empty state arrives with three real suggestions so the journey keeps going.',
            ]],
            ['name' => 'detail', 'type' => 'drawer', 'default' => "'slide-in'", 'note' => [
                'fa' => 'کلیک روی آیتم، کشوی جزئیات را از لبهٔ درون‌خطی باز می‌کند — در فارسی از چپ؛ پردهٔ پشت، دکمهٔ بستن و Escape می‌بندند و افزودن به سبد جواب خودش را می‌گیرد.',
                'en' => 'Clicking an item opens the details drawer from the inline edge — mirrored in Persian; the scrim, the close button and Escape close it, and add-to-cart answers back.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="flsq" x-data="{
            q: '', view: 'home', detail: null,
            cats: { audio: true, desk: true, acc: true }, max: 200, sort: 'relevance',
            items: [
                { name: 'هدفون Aurora X2', cat: 'audio', price: 189, meta: 'ANC · ۳۰ ساعت' },
                { name: 'کیبورد Nimbus K2', cat: 'desk', price: 149, meta: 'تکتیکال · بی‌سیم' },
                { name: 'ماوس بی‌سیم Orbit', cat: 'acc', price: 45, meta: 'بی‌صدا · فشرده' },
            ],
            get suggestions() {
                const q = this.q.trim().toLowerCase();
                return q ? this.items.filter((i) => (i.name + ' ' + i.meta).toLowerCase().includes(q)).slice(0, 5) : [];
            },
            get results() {
                let r = this.items.filter((i) => this.cats[i.cat] && i.price <= this.max);
                const q = this.q.trim().toLowerCase();
                if (q) r = r.filter((i) => (i.name + ' ' + i.meta).toLowerCase().includes(q));
                return r;
            },
        }">
            <div class="flsq-box">
                <input x-model="q" @input="view === 'home' || (view = 'results')" placeholder="دنبال چی می‌گردید؟">
                <ul class="flsq-sugg" x-show="view === 'home' && suggestions.length" x-cloak>
                    <template x-for="s in suggestions" :key="s.name">
                        <li @click="q = s.name; view = 'results'"><b x-text="s.name"></b></li>
                    </template>
                </ul>
            </div>

            <template x-if="view === 'results' && !results.length">
                <p class="flsq-empty">چیزی پیدا نشد — «هدفون Aurora» را امتحان کنید.</p>
            </template>
        </div>

        <style>
        .flsq-box { position: relative; }
        .flsq-sugg { position: absolute; inset-inline: 0; inset-block-start: 100%; margin: .25rem 0 0; padding: .25rem;
                     list-style: none; background: #fff; border: 1px solid #e4e6ef; border-radius: .9rem; }
        .flsq-empty { padding: 1.5rem; text-align: center; color: #666b83; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="flsq" x-data="{
            q: '', view: 'home', detail: null,
            cats: { audio: true, desk: true, acc: true }, max: 200, sort: 'relevance',
            items: [
                { name: 'Aurora X2 headphones', cat: 'audio', price: 189, meta: 'ANC · 30h' },
                { name: 'Nimbus K2 keyboard', cat: 'desk', price: 149, meta: 'Tactile · wireless' },
                { name: 'Orbit wireless mouse', cat: 'acc', price: 45, meta: 'Silent · compact' },
            ],
            get suggestions() {
                const q = this.q.trim().toLowerCase();
                return q ? this.items.filter((i) => (i.name + ' ' + i.meta).toLowerCase().includes(q)).slice(0, 5) : [];
            },
            get results() {
                let r = this.items.filter((i) => this.cats[i.cat] && i.price <= this.max);
                const q = this.q.trim().toLowerCase();
                if (q) r = r.filter((i) => (i.name + ' ' + i.meta).toLowerCase().includes(q));
                return r;
            },
        }">
            <div class="flsq-box">
                <input x-model="q" @input="view === 'home' || (view = 'results')" placeholder="What are you looking for?">
                <ul class="flsq-sugg" x-show="view === 'home' && suggestions.length" x-cloak>
                    <template x-for="s in suggestions" :key="s.name">
                        <li @click="q = s.name; view = 'results'"><b x-text="s.name"></b></li>
                    </template>
                </ul>
            </div>

            <template x-if="view === 'results' && !results.length">
                <p class="flsq-empty">Nothing found — try “Aurora headphones”.</p>
            </template>
        </div>

        <style>
        .flsq-box { position: relative; }
        .flsq-sugg { position: absolute; inset-inline: 0; inset-block-start: 100%; margin: .25rem 0 0; padding: .25rem;
                     list-style: none; background: #fff; border: 1px solid #e4e6ef; border-radius: .9rem; }
        .flsq-empty { padding: 1.5rem; text-align: center; color: #666b83; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Search.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SearchFlow from '@/components/SearchFlow';

        export default function Search() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <SearchFlow />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SearchFlow.jsx — live suggestions → filtered results → empty state → drawer
        import { useEffect, useMemo, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ITEMS = [
          { id: 'aurora', name: 'Aurora X2 headphones', cat: 'audio', price: 189, rating: 4.8, meta: 'ANC · 30h' },
          { id: 'pulse', name: 'Pulse earbuds', cat: 'audio', price: 99, rating: 4.5, meta: 'Waterproof · 24h case' },
          { id: 'halo', name: 'Halo desk speaker', cat: 'audio', price: 129, rating: 4.6, meta: 'Stereo pair · USB-C' },
          { id: 'nimbus', name: 'Nimbus K2 keyboard', cat: 'desk', price: 149, rating: 4.7, meta: 'Tactile · wireless' },
          { id: 'vela', name: 'Vela laptop stand', cat: 'desk', price: 59, rating: 4.4, meta: 'Aluminium · folding' },
          { id: 'orbit', name: 'Orbit wireless mouse', cat: 'acc', price: 45, rating: 4.3, meta: 'Silent · compact' },
          { id: 'atlas', name: 'Atlas monitor light', cat: 'acc', price: 79, rating: 4.6, meta: 'Auto-dim · bar' },
          { id: 'drift', name: 'Drift HD webcam', cat: 'acc', price: 95, rating: 4.2, meta: '4K · privacy shutter' },
        ];
        const CATS = { audio: 'Audio', desk: 'Desk', acc: 'Accessories' };
        const matches = (i, q) => !q || (i.name + ' ' + i.meta).toLowerCase().includes(q);

        export default function SearchFlow() {
          const [q, setQ] = useState('');
          const [searched, setSearched] = useState(false);
          const [cats, setCats] = useState({ audio: true, desk: true, acc: true });
          const [max, setMax] = useState(200);
          const [detail, setDetail] = useState(null);
          const boxRef = useRef(null);

          // Escape closes the drawer — the scrim and the X close it too.
          useEffect(() => {
            const onKey = (e) => e.key === 'Escape' && setDetail(null);
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          }, []);

          const query = q.trim().toLowerCase();
          const suggestions = useMemo(
            () => (query ? ITEMS.filter((i) => matches(i, query)).slice(0, 5) : []), [query]);
          const results = useMemo(() => {
            let r = ITEMS.filter((i) => cats[i.cat] && i.price <= max && matches(i, query));
            return r;
          }, [cats, max, query]);

          return (
            <>
              <style>{FLSQ_CSS}</style>
              <div className="flsq">
                <div className="flsq-box" ref={boxRef}>
                  <input value={q} placeholder="What are you looking for?"
                    onChange={(e) => { setQ(e.target.value); setSearched(true); }}
                    onKeyDown={(e) => e.key === 'Enter' && setSearched(true)} />
                  {/* Live suggestions boil under the box while typing. */}
                  {!searched && suggestions.length > 0 && (
                    <ul className="flsq-sugg">
                      {suggestions.map((s) => (
                        <li key={s.id} onClick={() => { setQ(s.name); setSearched(true); }}>
                          <b>{s.name}</b><small>{CATS[s.cat]} · €{s.price}</small>
                        </li>
                      ))}
                    </ul>
                  )}
                </div>

                {searched && (
                  <div className="flsq-body">
                    <aside className="flsq-rail">
                      {Object.entries(CATS).map(([id, label]) => (
                        <label key={id}>
                          <input type="checkbox" checked={cats[id]}
                            onChange={() => setCats({ ...cats, [id]: !cats[id] })} /> {label}
                        </label>
                      ))}
                      <label className="flsq-price">Up to €{max}
                        <input type="range" min="40" max="200" step="5" value={max}
                          onChange={(e) => setMax(+e.target.value)} />
                      </label>
                    </aside>

                    <div className="flsq-results">
                      {results.length === 0 ? (
                        <div className="flsq-empty">
                          <p>Nothing matched “{q}” within €{max}.</p>
                          <div>
                            {['Aurora', 'keyboard', 'light'].map((s) => (
                              <button type="button" key={s} onClick={() => setQ(s)}>{s}</button>
                            ))}
                          </div>
                        </div>
                      ) : results.map((i) => (
                        <button type="button" className="flsq-item" key={i.id} onClick={() => setDetail(i)}>
                          <b>{i.name}</b>
                          <small>★ {i.rating} · {i.meta}</small>
                          <span>€{i.price}</span>
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* The drawer slides in from the inline edge; the scrim dims behind it. */}
                <div className="flsq-stage" data-open={detail ? '' : undefined}>
                  {detail && (
                    <>
                      <div className="flsq-scrim" onClick={() => setDetail(null)} />
                      <aside className="flsq-drawer" role="dialog" aria-label={detail.name}>
                        <button type="button" className="flsq-x" onClick={() => setDetail(null)}>×</button>
                        <h3>{detail.name}</h3>
                        <p>{detail.meta}</p>
                        <b>€{detail.price}</b>
                      </aside>
                    </>
                  )}
                </div>
              </div>
            </>
          );
        }

        const FLSQ_CSS = `
          .flsq { display: grid; gap: 1rem; max-inline-size: 44rem; color: #191b2a; }
          .flsq-box { position: relative; }
          .flsq-box input { inline-size: 100%; min-block-size: 2.9rem; padding-inline: 3rem;
                            border: 1px solid #e4e6ef; border-radius: 999px; font: inherit; }
          .flsq-sugg { position: absolute; inset-inline: 0; inset-block-start: calc(100% + .35rem); z-index: 3; margin: 0;
                       padding: .3rem; list-style: none; background: #fff; border: 1px solid #e4e6ef; border-radius: 1rem; }
          .flsq-sugg li { display: grid; padding: .55rem .75rem; border-radius: .6rem; cursor: pointer; }
          .flsq-sugg li:hover { background: #f5f6fb; }
          .flsq-sugg small { color: #666b83; }
          .flsq-body { display: grid; grid-template-columns: 9rem minmax(0, 1fr); gap: 1rem; align-items: start; }
          .flsq-rail { display: grid; gap: .5rem; padding: 1rem; border: 1px solid #e4e6ef;
                       border-radius: 1rem; font-size: .84rem; }
          .flsq-price input { inline-size: 100%; accent-color: #4f46e5; }
          .flsq-results { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .75rem; }
          .flsq-item { display: grid; gap: .25rem; padding: .9rem; text-align: start; border: 1px solid #e4e6ef;
                       border-radius: 1rem; background: #fff; cursor: pointer; font: inherit; }
          .flsq-item:hover { border-color: #4f46e5; }
          .flsq-empty { grid-column: 1 / -1; display: grid; justify-items: center; gap: .75rem; padding: 2rem 1rem;
                        color: #666b83; text-align: center; }
          .flsq-empty div { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; }
          .flsq-empty button { padding: .4rem .9rem; border: 1px solid #e4e6ef; border-radius: 999px;
                               background: #fff; cursor: pointer; }
          .flsq-stage { position: relative; }
          .flsq-scrim { position: absolute; inset: 0; background: rgb(15 16 32 / .35); border-radius: 1rem; }
          .flsq-drawer { position: absolute; inset-block: 0; inset-inline-end: 0; inline-size: min(100%, 18rem);
                         padding: 1.25rem; background: #fff; border-radius: 1rem; box-shadow: -12px 0 30px rgb(20 22 48 / .18);
                         translate: 105% 0; transition: translate .3s cubic-bezier(.2, 0, 0, 1); }
          .flsq-stage[data-open] .flsq-drawer { translate: 0 0; }
          .flsq-x { position: absolute; inset-block-start: .7rem; inset-inline-end: .7rem; inline-size: 1.8rem;
                    aspect-ratio: 1; border: none; border-radius: 50%; background: #f5f6fb; cursor: pointer; }
        `;
        REACT,
            'vue' => <<<'VUE'
        <!-- SearchFlow.vue — live suggestions → filtered results → empty state → drawer -->
        <script setup lang="ts">
        import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const items = [
          { id: 'aurora', name: 'Aurora X2 headphones', cat: 'audio', price: 189, rating: 4.8, meta: 'ANC · 30h' },
          { id: 'pulse', name: 'Pulse earbuds', cat: 'audio', price: 99, rating: 4.5, meta: 'Waterproof · 24h case' },
          { id: 'halo', name: 'Halo desk speaker', cat: 'audio', price: 129, rating: 4.6, meta: 'Stereo pair · USB-C' },
          { id: 'nimbus', name: 'Nimbus K2 keyboard', cat: 'desk', price: 149, rating: 4.7, meta: 'Tactile · wireless' },
          { id: 'vela', name: 'Vela laptop stand', cat: 'desk', price: 59, rating: 4.4, meta: 'Aluminium · folding' },
          { id: 'orbit', name: 'Orbit wireless mouse', cat: 'acc', price: 45, rating: 4.3, meta: 'Silent · compact' },
          { id: 'atlas', name: 'Atlas monitor light', cat: 'acc', price: 79, rating: 4.6, meta: 'Auto-dim · bar' },
          { id: 'drift', name: 'Drift HD webcam', cat: 'acc', price: 95, rating: 4.2, meta: '4K · privacy shutter' },
        ];
        const cats = reactive({ audio: true, desk: true, acc: true });
        const catLabels: Record<string, string> = { audio: 'Audio', desk: 'Desk', acc: 'Accessories' };
        const q = ref('');
        const searched = ref(false);
        const max = ref(200);
        const detail = ref(null);

        // Escape closes the drawer — the scrim and the X close it too.
        const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') detail.value = null; };
        onMounted(() => window.addEventListener('keydown', onKey));
        onBeforeUnmount(() => window.removeEventListener('keydown', onKey));

        const matches = (i: typeof items[number], query: string) =>
          !query || (i.name + ' ' + i.meta).toLowerCase().includes(query);
        const suggestions = computed(() => {
          const query = q.value.trim().toLowerCase();
          return query ? items.filter((i) => matches(i, query)).slice(0, 5) : [];
        });
        const results = computed(() => {
          const query = q.value.trim().toLowerCase();
          return items.filter((i) => cats[i.cat as 'audio'] && i.price <= max.value && matches(i, query));
        });
        </script>

        <template>
          <div class="flsq">
            <div class="flsq-box">
              <input v-model="q" placeholder="What are you looking for?"
                @input="searched = true" @keydown.enter="searched = true" />
              <!-- Live suggestions boil under the box while typing. -->
              <ul v-if="!searched && suggestions.length" class="flsq-sugg">
                <li v-for="s in suggestions" :key="s.id" @click="q = s.name; searched = true">
                  <b>{{ s.name }}</b><small>{{ catLabels[s.cat] }} · €{{ s.price }}</small>
                </li>
              </ul>
            </div>

            <div v-if="searched" class="flsq-body">
              <aside class="flsq-rail">
                <label v-for="(label, id) in catLabels" :key="id">
                  <input v-model="cats[id]" type="checkbox" /> {{ label }}
                </label>
                <label class="flsq-price">Up to €{{ max }}
                  <input v-model.number="max" type="range" min="40" max="200" step="5" />
                </label>
              </aside>

              <div class="flsq-results">
                <div v-if="!results.length" class="flsq-empty">
                  <p>Nothing matched “{{ q }}” within €{{ max }}.</p>
                  <div>
                    <button v-for="s in ['Aurora', 'keyboard', 'light']" :key="s" type="button" @click="q = s">{{ s }}</button>
                  </div>
                </div>
                <button v-for="i in results" :key="i.id" type="button" class="flsq-item" @click="detail = i">
                  <b>{{ i.name }}</b>
                  <small>★ {{ i.rating }} · {{ i.meta }}</small>
                  <span>€{{ i.price }}</span>
                </button>
              </div>
            </div>

            <!-- The drawer slides in from the inline edge; the scrim dims behind it. -->
            <div class="flsq-stage" :data-open="detail || undefined">
              <template v-if="detail">
                <div class="flsq-scrim" @click="detail = null"></div>
                <aside class="flsq-drawer" role="dialog" aria-label="detail?.name">
                  <button type="button" class="flsq-x" @click="detail = null">×</button>
                  <h3>{{ detail.name }}</h3>
                  <p>{{ detail.meta }}</p>
                  <b>€{{ detail.price }}</b>
                </aside>
              </template>
            </div>
          </div>
        </template>

        <style scoped>
        .flsq { display: grid; gap: 1rem; max-inline-size: 44rem; color: #191b2a; }
        .flsq-box { position: relative; }
        .flsq-box input { inline-size: 100%; min-block-size: 2.9rem; padding-inline: 3rem;
                          border: 1px solid #e4e6ef; border-radius: 999px; font: inherit; }
        .flsq-sugg { position: absolute; inset-inline: 0; inset-block-start: calc(100% + .35rem); z-index: 3; margin: 0;
                     padding: .3rem; list-style: none; background: #fff; border: 1px solid #e4e6ef; border-radius: 1rem; }
        .flsq-sugg li { display: grid; padding: .55rem .75rem; border-radius: .6rem; cursor: pointer; }
        .flsq-sugg li:hover { background: #f5f6fb; }
        .flsq-sugg small { color: #666b83; }
        .flsq-body { display: grid; grid-template-columns: 9rem minmax(0, 1fr); gap: 1rem; align-items: start; }
        .flsq-rail { display: grid; gap: .5rem; padding: 1rem; border: 1px solid #e4e6ef;
                     border-radius: 1rem; font-size: .84rem; }
        .flsq-price input { inline-size: 100%; accent-color: #4f46e5; }
        .flsq-results { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .75rem; }
        .flsq-item { display: grid; gap: .25rem; padding: .9rem; text-align: start; border: 1px solid #e4e6ef;
                     border-radius: 1rem; background: #fff; cursor: pointer; font: inherit; }
        .flsq-item:hover { border-color: #4f46e5; }
        .flsq-empty { grid-column: 1 / -1; display: grid; justify-items: center; gap: .75rem; padding: 2rem 1rem;
                      color: #666b83; text-align: center; }
        .flsq-empty div { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; }
        .flsq-empty button { padding: .4rem .9rem; border: 1px solid #e4e6ef; border-radius: 999px;
                             background: #fff; cursor: pointer; }
        .flsq-stage { position: relative; }
        .flsq-scrim { position: absolute; inset: 0; background: rgb(15 16 32 / .35); border-radius: 1rem; }
        .flsq-drawer { position: absolute; inset-block: 0; inset-inline-end: 0; inline-size: min(100%, 18rem);
                       padding: 1.25rem; background: #fff; border-radius: 1rem; box-shadow: -12px 0 30px rgb(20 22 48 / .18);
                       translate: 105% 0; transition: translate .3s cubic-bezier(.2, 0, 0, 1); }
        .flsq-stage[data-open] .flsq-drawer { translate: 0 0; }
        .flsq-x { position: absolute; inset-block-start: .7rem; inset-inline-end: .7rem; inline-size: 1.8rem;
                  aspect-ratio: 1; border: none; border-radius: 50%; background: #f5f6fb; cursor: pointer; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SearchFlow.svelte — live suggestions → filtered results → empty state → drawer -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const items = [
            { id: 'aurora', name: 'Aurora X2 headphones', cat: 'audio', price: 189, rating: 4.8, meta: 'ANC · 30h' },
            { id: 'pulse', name: 'Pulse earbuds', cat: 'audio', price: 99, rating: 4.5, meta: 'Waterproof · 24h case' },
            { id: 'halo', name: 'Halo desk speaker', cat: 'audio', price: 129, rating: 4.6, meta: 'Stereo pair · USB-C' },
            { id: 'nimbus', name: 'Nimbus K2 keyboard', cat: 'desk', price: 149, rating: 4.7, meta: 'Tactile · wireless' },
            { id: 'vela', name: 'Vela laptop stand', cat: 'desk', price: 59, rating: 4.4, meta: 'Aluminium · folding' },
            { id: 'orbit', name: 'Orbit wireless mouse', cat: 'acc', price: 45, rating: 4.3, meta: 'Silent · compact' },
            { id: 'atlas', name: 'Atlas monitor light', cat: 'acc', price: 79, rating: 4.6, meta: 'Auto-dim · bar' },
            { id: 'drift', name: 'Drift HD webcam', cat: 'acc', price: 95, rating: 4.2, meta: '4K · privacy shutter' },
          ];
          const catLabels: Record<string, string> = { audio: 'Audio', desk: 'Desk', acc: 'Accessories' };
          let q = $state('');
          let searched = $state(false);
          let cats = $state({ audio: true, desk: true, acc: true });
          let max = $state(200);
          let detail = $state<(typeof items)[number] | null>(null);

          // Escape closes the drawer — the scrim and the X close it too.
          onMount(() => {
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') detail = null; };
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          });

          const matches = (i: (typeof items)[number], query: string) =>
            !query || (i.name + ' ' + i.meta).toLowerCase().includes(query);
          const suggestions = $derived(
            q.trim() ? items.filter((i) => matches(i, q.trim().toLowerCase())).slice(0, 5) : []);
          const results = $derived(
            items.filter((i) => cats[i.cat as 'audio'] && i.price <= max && matches(i, q.trim().toLowerCase())));
        </script>

        <div class="flsq">
          <div class="flsq-box">
            <input placeholder="What are you looking for?" bind:value onkeydown={(e) =>
              e.key === 'Enter' && (searched = true)} oninput={() => (searched = true)} />
            <!-- Live suggestions boil under the box while typing. -->
            {#if !searched && suggestions.length}
              <ul class="flsq-sugg">
                {#each suggestions as s (s.id)}
                  <li onclick={() => { q = s.name; searched = true; }}>
                    <b>{s.name}</b><small>{catLabels[s.cat]} · €{s.price}</small>
                  </li>
                {/each}
              </ul>
            {/if}
          </div>

          {#if searched}
            <div class="flsq-body">
              <aside class="flsq-rail">
                {#each Object.entries(catLabels) as [id, label] (id)}
                  <label><input type="checkbox" bind:checked={cats[id]} /> {label}</label>
                {/each}
                <label class="flsq-price">Up to €{max}
                  <input type="range" min="40" max="200" step="5" bind:value={max} />
                </label>
              </aside>

              <div class="flsq-results">
                {#if !results.length}
                  <div class="flsq-empty">
                    <p>Nothing matched “{q}” within €{max}.</p>
                    <div>
                      {#each ['Aurora', 'keyboard', 'light'] as s (s)}
                        <button type="button" onclick={() => (q = s)}>{s}</button>
                      {/each}
                    </div>
                  </div>
                {:else}
                  {#each results as i (i.id)}
                    <button type="button" class="flsq-item" onclick={() => (detail = i)}>
                      <b>{i.name}</b>
                      <small>★ {i.rating} · {i.meta}</small>
                      <span>€{i.price}</span>
                    </button>
                  {/each}
                {/if}
              </div>
            </div>
          {/if}

          <!-- The drawer slides in from the inline edge; the scrim dims behind it. -->
          <div class="flsq-stage" data-open={detail || undefined}>
            {#if detail}
              <div class="flsq-scrim" onclick={() => (detail = null)}></div>
              <aside class="flsq-drawer" role="dialog" aria-label={detail.name}>
                <button type="button" class="flsq-x" onclick={() => (detail = null)}>×</button>
                <h3>{detail.name}</h3>
                <p>{detail.meta}</p>
                <b>€{detail.price}</b>
              </aside>
            {/if}
          </div>
        </div>

        <style>
          .flsq { display: grid; gap: 1rem; max-inline-size: 44rem; color: #191b2a; }
          .flsq-box { position: relative; }
          .flsq-box input { inline-size: 100%; min-block-size: 2.9rem; padding-inline: 3rem;
                            border: 1px solid #e4e6ef; border-radius: 999px; font: inherit; }
          .flsq-sugg { position: absolute; inset-inline: 0; inset-block-start: calc(100% + .35rem); z-index: 3; margin: 0;
                       padding: .3rem; list-style: none; background: #fff; border: 1px solid #e4e6ef; border-radius: 1rem; }
          .flsq-sugg li { display: grid; padding: .55rem .75rem; border-radius: .6rem; cursor: pointer; }
          .flsq-sugg li:hover { background: #f5f6fb; }
          .flsq-sugg small { color: #666b83; }
          .flsq-body { display: grid; grid-template-columns: 9rem minmax(0, 1fr); gap: 1rem; align-items: start; }
          .flsq-rail { display: grid; gap: .5rem; padding: 1rem; border: 1px solid #e4e6ef;
                       border-radius: 1rem; font-size: .84rem; }
          .flsq-price input { inline-size: 100%; accent-color: #4f46e5; }
          .flsq-results { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .75rem; }
          .flsq-item { display: grid; gap: .25rem; padding: .9rem; text-align: start; border: 1px solid #e4e6ef;
                       border-radius: 1rem; background: #fff; cursor: pointer; font: inherit; }
          .flsq-item:hover { border-color: #4f46e5; }
          .flsq-empty { grid-column: 1 / -1; display: grid; justify-items: center; gap: .75rem; padding: 2rem 1rem;
                        color: #666b83; text-align: center; }
          .flsq-empty div { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; }
          .flsq-empty button { padding: .4rem .9rem; border: 1px solid #e4e6ef; border-radius: 999px;
                               background: #fff; cursor: pointer; }
          .flsq-stage { position: relative; }
          .flsq-scrim { position: absolute; inset: 0; background: rgb(15 16 32 / .35); border-radius: 1rem; }
          .flsq-drawer { position: absolute; inset-block: 0; inset-inline-end: 0; inline-size: min(100%, 18rem);
                         padding: 1.25rem; background: #fff; border-radius: 1rem; box-shadow: -12px 0 30px rgb(20 22 48 / .18);
                         translate: 105% 0; transition: translate .3s cubic-bezier(.2, 0, 0, 1); }
          .flsq-stage[data-open] .flsq-drawer { translate: 0 0; }
          .flsq-x { position: absolute; inset-block-start: .7rem; inset-inline-end: .7rem; inline-size: 1.8rem;
                    aspect-ratio: 1; border: none; border-radius: 50%; background: #f5f6fb; cursor: pointer; }
        </style>
        SVELTE,
        ],
    ],
];
