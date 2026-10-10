<?php

/**
 * Demo manifest of the "lightning" group — Salesforce Lightning Design System
 * rebuilt as faithful, interactive skins: the sales Path with its marketing
 * colours and Mark-Stage-Complete CTA, the Record Home header with its
 * Highlights panel, Related Lists, the Docked Utility Bar with in-place
 * panels, the Welcome Mat onboarding checklist, Scoped Tabs, the App
 * Launcher waffle, the Chatter feed and the Dueling Picklist. Scenarios live
 * at resources/views/demos/components/lightning/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'سیلزفورس — لایتنینگ', 'en' => 'Salesforce Lightning'],

    'path' => [
        'title' => ['fa' => 'مسیر فروش (Path)', 'en' => 'Path'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'نوار افقی مراحل فروش با رنگ‌های مارکتینگ و دکمهٔ «تکمیل مرحله»؛ رکورد را از دید معامله‌گر سلس‌فورس لو می‌دهد.',
            'en' => 'The horizontal stage bar with marketing-colored chevrons and a Mark-Stage-Complete CTA — instantly reads as an opportunity on the move.',
        ],
        'js' => true,
        'docs' => 'https://www.lightningdesignsystem.com/components/path/',
        'props' => [
            ['name' => 'stage-status', 'type' => 'enum', 'default' => "'current'", 'note' => [
                'fa' => 'complete با تیک، current سفید روی رنگ مرحله، incomplete خاکستری و lost قرمز پالت مارکتینگ.',
                'en' => 'complete shows a tick, current sits white on the stage colour, incomplete is grey and lost wears the palette’s red.',
            ]],
            ['name' => 'stage-color', 'type' => 'color', 'default' => "'#0176D3'", 'note' => [
                'fa' => 'رنگ هر مرحله از پالت مارکتینگ سلس‌فورس می‌آید: آبی، سبزآبی، بنفش، نارنجی، صورتی، زرد، قرمز و سبز.',
                'en' => 'Each stage takes a Marketing Cloud colour: blue, teal, purple, orange, pink, yellow, red and green.',
            ]],
            ['name' => 'mark-complete', 'type' => 'event', 'default' => "'click'", 'note' => [
                'fa' => 'دکمهٔ همیشه‌نمایان «تکمیل مرحله» پایین نوار، مرحلهٔ جاری را کامل و نوار را یک قدم جلو می‌برد.',
                'en' => 'The always-visible Mark-Stage-Complete button closes the current stage and nudges the path one step forward.',
            ]],
            ['name' => 'coaching', 'type' => 'field', 'default' => 'null', 'note' => [
                'fa' => 'زیر مرحلهٔ جاری، فیلد راهنمای معامله («گام بعدی») می‌نشیند؛ همان coaching path معروف.',
                'en' => 'A coaching field — the next step — sits under the current stage: the famous coaching path.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <div class="sflx-path" data-current="4">
                <ol>
                    <li class="sflx-step" data-status="complete" style="--stage: #0176D3">کشف فرصت</li>
                    <li class="sflx-step" data-status="complete" style="--stage: #0B827C">ارزیابی صلاحیت</li>
                    <li class="sflx-step" data-status="current" style="--stage: #6739B7">تحلیل نیاز</li>
                    <li class="sflx-step" data-status="incomplete" style="--stage: #FE9339">ارزش پیشنهادی</li>
                </ol>
                <button class="sflx-mark">تکمیل مرحله</button>
            </div>

            <style>
            .sflx-path ol { display: flex; }
            .sflx-step { flex: 1; clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%,
                        calc(100% - 12px) 100%, 0 100%, 12px 50%); padding: .5rem 1.5rem;
                        background: #E8E8E8; }
            .sflx-step[data-status='complete'] { background: var(--stage); color: #fff; }
            .sflx-step[data-status='current'] { background: var(--stage); color: #fff; font-weight: 700; }
            .sflx-mark { margin: .5rem 0 0 auto; background: var(--stage); color: #fff; border-radius: .25rem; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="sflx-path" data-current="4">
                <ol>
                    <li class="sflx-step" data-status="complete" style="--stage: #0176D3">Prospecting</li>
                    <li class="sflx-step" data-status="complete" style="--stage: #0B827C">Qualification</li>
                    <li class="sflx-step" data-status="current" style="--stage: #6739B7">Needs Analysis</li>
                    <li class="sflx-step" data-status="incomplete" style="--stage: #FE9339">Value Proposition</li>
                </ol>
                <button class="sflx-mark">Mark Stage as Complete</button>
            </div>

            <style>
            .sflx-path ol { display: flex; }
            .sflx-step { flex: 1; clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%,
                        calc(100% - 12px) 100%, 0 100%, 12px 50%); padding: .5rem 1.5rem;
                        background: #E8E8E8; }
            .sflx-step[data-status='complete'] { background: var(--stage); color: #fff; }
            .sflx-step[data-status='current'] { background: var(--stage); color: #fff; font-weight: 700; }
            .sflx-mark { margin: .5rem 0 0 auto; background: var(--stage); color: #fff; border-radius: .25rem; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Opportunities/Show.tsx — an Inertia page (React flavor).
            // Inertia resolves this file for the /opportunities/{record} route and hands
            // the record down as page props; the UI itself is the SalesPath component
            // from the React snippet — no rewrite, just routing.
            import SalesPath from '@/components/SalesPath';

            export default function OpportunityShow() {
              // A real page would read its data: const { opportunity } = usePage().props;
              return <SalesPath />;
            }
            TSX,
            'react' => <<<'TSX'
            // SalesPath.tsx — the Lightning Path: marketing-coloured chevrons, the
            // coaching "next step" field and the Mark-Stage-as-Complete CTA. The markup
            // mirrors the playground partial (slp-* classes); styles ship with it.
            import { type CSSProperties, useState } from 'react';
            import '@nabuxai/ui-react/css';

            const STAGES = [
              { label: 'Prospecting', color: '#0176D3' },
              { label: 'Qualification', color: '#0B827C' },
              { label: 'Needs Analysis', color: '#6739B7' },
              { label: 'Value Proposition', color: '#FE9339' },
              { label: 'Id. Decision Makers', color: '#B32D69' },
              { label: 'Proposal/Price Quote', color: '#FFB75D' },
              { label: 'Negotiation/Review', color: '#BA0517' },
              { label: 'Closed Won', color: '#04844B' },
            ];

            const tint = (color: string) => ({ '--slp-stage': color }) as CSSProperties;

            export default function SalesPath() {
              // cur is the current stage; lost drops the whole deal to Closed Lost.
              const [cur, setCur] = useState(5);
              const [lost, setLost] = useState(false);
              const status = (i: number) =>
                lost ? (i < cur ? 'complete' : 'lost')
                  : i < cur ? 'complete' : i === cur ? 'current' : 'incomplete';

              return (
                <div className="slp-root">
                  <div className="slp-card">
                    <div className="slp-what">
                      <small>Opportunity · Bosporus Logistics (Istanbul)</small>
                      <b>Nabu annual license — renewal</b>
                    </div>

                    <ol className="slp-rail">
                      {STAGES.map((s, i) => (
                        <li key={s.label}>
                          <button type="button" className="slp-step" style={tint(s.color)}
                            data-status={status(i)}
                            aria-current={i === cur && !lost ? 'step' : undefined}
                            onClick={() => { if (i < cur && !lost) setCur(i); }}>
                            {s.label}
                          </button>
                        </li>
                      ))}
                    </ol>

                    {!lost && (
                      <div className="slp-coach" style={tint(STAGES[cur].color)}>
                        <label htmlFor="slp-next">Next step</label>
                        <input id="slp-next" type="text" placeholder="e.g. send the revised quote" />
                      </div>
                    )}

                    <div className="slp-foot">
                      {!lost && cur < STAGES.length - 1 && (
                        <button type="button" className="slp-btn" data-variant="brand"
                          onClick={() => setCur(cur + 1)}>Mark Stage as Complete</button>
                      )}
                      {cur === STAGES.length - 1 && !lost && <span className="slp-won">✓ Won — time to invoice</span>}
                      {lost ? (
                        <span className="slp-flip">Reopen the deal:
                          <button type="button" className="slp-btn"
                            onClick={() => { setLost(false); setCur(5); }}>Back to Proposal</button>
                        </span>
                      ) : (
                        <span className="slp-flip">Deal fell through?
                          <button type="button" className="slp-btn" data-variant="lost"
                            onClick={() => { setLost(true); setCur(STAGES.length - 2); }}>Mark as Lost</button>
                        </span>
                      )}
                    </div>
                    <p className="slp-hint">Stage {cur + 1} of {STAGES.length} — {STAGES[cur].label}</p>
                  </div>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .slp-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slp-border: #DDDBDA; --slp-card: #FFFFFF; --slp-step-bg: #E8E8E8; --slp-step-ink: #3E3E3C;
              --slp-blue: #0176D3; --slp-red: #BA0517; --slp-green: #04844B; --slp-muted: #706E6B; }
            @media (prefers-color-scheme: dark) { .slp-root { color: #F3F3F3;
              --slp-border: #474747; --slp-card: #232323; --slp-step-bg: #3B3B3B; --slp-step-ink: #D5D5D5;
              --slp-blue: #0D9DDA; --slp-red: #FE5C4C; --slp-green: #0E9E5B; --slp-muted: #A5A5A5; } }
            .slp-card { max-inline-size: 46rem; margin-inline: auto; padding: 1rem; border: 1px solid var(--slp-border);
              border-radius: .25rem; background: var(--slp-card); display: grid; gap: 1rem; }
            .slp-what { display: grid; gap: .15rem; }
            .slp-what small { font-size: .78rem; color: var(--slp-muted); }
            .slp-what b { font-size: 1.1rem; }
            .slp-rail { display: flex; margin: 0; padding: 0; list-style: none; overflow-x: auto; }
            .slp-rail li { display: contents; }
            .slp-step { flex: 1; min-inline-size: 8.5rem; block-size: 2.6rem; padding-inline: 1.55rem; border: 0;
              cursor: pointer; text-align: start; font-size: .78rem; font-weight: 600; line-height: 1.15;
              background: var(--slp-step-bg); color: var(--slp-step-ink);
              clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%, 12px 50%); }
            .slp-step:first-child { clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%); }
            .slp-step[data-status='complete'], .slp-step[data-status='current'] { background: var(--slp-stage); color: #FFFFFF; }
            .slp-step[data-status='current'] { font-weight: 700; }
            .slp-step[data-status='lost'] { background: var(--slp-red); color: #FFFFFF; }
            .slp-coach { display: grid; gap: .35rem; padding: .75rem; border: 1px solid var(--slp-border);
              border-block-start: 2px solid var(--slp-stage); }
            .slp-coach label { font-size: .72rem; font-weight: 700; color: var(--slp-muted); }
            .slp-coach input { padding: .4rem .55rem; border: 1px solid var(--slp-border); border-radius: .25rem;
              background: var(--slp-card); color: inherit; font: inherit; font-size: .85rem; }
            .slp-foot { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
            .slp-btn { padding: .45rem 1rem; border: 1px solid var(--slp-border); border-radius: .25rem;
              background: var(--slp-card); color: inherit; font: inherit; font-size: .85rem; cursor: pointer; }
            .slp-btn[data-variant='brand'] { background: var(--slp-blue); border-color: var(--slp-blue); color: #FFFFFF; font-weight: 600; }
            .slp-btn[data-variant='lost'] { color: var(--slp-red); }
            .slp-flip { display: inline-flex; flex-wrap: wrap; align-items: center; gap: .4rem; font-size: .8rem; color: var(--slp-muted); }
            .slp-won { display: flex; align-items: center; padding: .55rem .75rem; border-radius: .25rem;
              background: color-mix(in srgb, var(--slp-green) 12%, var(--slp-card)); color: var(--slp-green);
              font-size: .85rem; font-weight: 600; }
            .slp-hint { margin: 0; font-size: .78rem; color: var(--slp-muted); }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- SalesPath.vue — the Lightning Path: marketing-coloured chevrons, the
                 coaching "next step" field and the Mark-Stage-as-Complete CTA. Markup
                 mirrors the playground partial (slp-* classes). -->
            <script setup lang="ts">
            import { ref } from 'vue';

            const stages = [
              { label: 'Prospecting', color: '#0176D3' },
              { label: 'Qualification', color: '#0B827C' },
              { label: 'Needs Analysis', color: '#6739B7' },
              { label: 'Value Proposition', color: '#FE9339' },
              { label: 'Id. Decision Makers', color: '#B32D69' },
              { label: 'Proposal/Price Quote', color: '#FFB75D' },
              { label: 'Negotiation/Review', color: '#BA0517' },
              { label: 'Closed Won', color: '#04844B' },
            ];

            // cur is the current stage; lost drops the whole deal to Closed Lost.
            const cur = ref(5);
            const lost = ref(false);

            const status = (i: number) =>
              lost.value ? (i < cur.value ? 'complete' : 'lost')
                : i < cur.value ? 'complete' : i === cur.value ? 'current' : 'incomplete';
            const advance = () => { if (cur.value < stages.length - 1) cur.value++; };
            const lose = () => { lost.value = true; cur.value = stages.length - 2; };
            const revive = () => { lost.value = false; cur.value = 5; };
            </script>

            <template>
              <div class="slp-root">
                <div class="slp-card">
                  <div class="slp-what">
                    <small>Opportunity · Bosporus Logistics (Istanbul)</small>
                    <b>Nabu annual license — renewal</b>
                  </div>

                  <ol class="slp-rail">
                    <li v-for="(s, i) in stages" :key="s.label">
                      <button type="button" class="slp-step"
                        :style="{ '--slp-stage': s.color }"
                        :data-status="status(i)"
                        :aria-current="i === cur && !lost ? 'step' : undefined"
                        @click="i < cur && !lost && (cur = i)">
                        {{ s.label }}
                      </button>
                    </li>
                  </ol>

                  <div v-if="!lost" class="slp-coach" :style="{ '--slp-stage': stages[cur].color }">
                    <label for="slp-next">Next step</label>
                    <input id="slp-next" type="text" placeholder="e.g. send the revised quote" />
                  </div>

                  <div class="slp-foot">
                    <button v-if="!lost && cur < stages.length - 1" type="button" class="slp-btn"
                      data-variant="brand" @click="advance">Mark Stage as Complete</button>
                    <span v-if="cur === stages.length - 1 && !lost" class="slp-won">✓ Won — time to invoice</span>
                    <span v-if="!lost && cur < stages.length - 1" class="slp-flip">Deal fell through?
                      <button type="button" class="slp-btn" data-variant="lost" @click="lose">Mark as Lost</button>
                    </span>
                    <span v-if="lost" class="slp-flip">Reopen the deal:
                      <button type="button" class="slp-btn" @click="revive">Back to Proposal</button>
                    </span>
                  </div>
                  <p class="slp-hint">Stage {{ cur + 1 }} of {{ stages.length }} — {{ stages[cur].label }}</p>
                </div>
              </div>
            </template>

            <style scoped>
            .slp-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slp-border: #DDDBDA; --slp-card: #FFFFFF; --slp-step-bg: #E8E8E8; --slp-step-ink: #3E3E3C;
              --slp-blue: #0176D3; --slp-red: #BA0517; --slp-green: #04844B; --slp-muted: #706E6B; }
            @media (prefers-color-scheme: dark) { .slp-root { color: #F3F3F3;
              --slp-border: #474747; --slp-card: #232323; --slp-step-bg: #3B3B3B; --slp-step-ink: #D5D5D5;
              --slp-blue: #0D9DDA; --slp-red: #FE5C4C; --slp-green: #0E9E5B; --slp-muted: #A5A5A5; } }
            .slp-card { max-inline-size: 46rem; margin-inline: auto; padding: 1rem; border: 1px solid var(--slp-border);
              border-radius: .25rem; background: var(--slp-card); display: grid; gap: 1rem; }
            .slp-what { display: grid; gap: .15rem; }
            .slp-what small { font-size: .78rem; color: var(--slp-muted); }
            .slp-what b { font-size: 1.1rem; }
            .slp-rail { display: flex; margin: 0; padding: 0; list-style: none; overflow-x: auto; }
            .slp-rail li { display: contents; }
            .slp-step { flex: 1; min-inline-size: 8.5rem; block-size: 2.6rem; padding-inline: 1.55rem; border: 0;
              cursor: pointer; text-align: start; font-size: .78rem; font-weight: 600; line-height: 1.15;
              background: var(--slp-step-bg); color: var(--slp-step-ink);
              clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%, 12px 50%); }
            .slp-step:first-child { clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%); }
            .slp-step[data-status='complete'], .slp-step[data-status='current'] { background: var(--slp-stage); color: #FFFFFF; }
            .slp-step[data-status='current'] { font-weight: 700; }
            .slp-step[data-status='lost'] { background: var(--slp-red); color: #FFFFFF; }
            .slp-coach { display: grid; gap: .35rem; padding: .75rem; border: 1px solid var(--slp-border);
              border-block-start: 2px solid var(--slp-stage); }
            .slp-coach label { font-size: .72rem; font-weight: 700; color: var(--slp-muted); }
            .slp-coach input { padding: .4rem .55rem; border: 1px solid var(--slp-border); border-radius: .25rem;
              background: var(--slp-card); color: inherit; font: inherit; font-size: .85rem; }
            .slp-foot { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
            .slp-btn { padding: .45rem 1rem; border: 1px solid var(--slp-border); border-radius: .25rem;
              background: var(--slp-card); color: inherit; font: inherit; font-size: .85rem; cursor: pointer; }
            .slp-btn[data-variant='brand'] { background: var(--slp-blue); border-color: var(--slp-blue); color: #FFFFFF; font-weight: 600; }
            .slp-btn[data-variant='lost'] { color: var(--slp-red); }
            .slp-flip { display: inline-flex; flex-wrap: wrap; align-items: center; gap: .4rem; font-size: .8rem; color: var(--slp-muted); }
            .slp-won { display: flex; align-items: center; padding: .55rem .75rem; border-radius: .25rem;
              background: color-mix(in srgb, var(--slp-green) 12%, var(--slp-card)); color: var(--slp-green);
              font-size: .85rem; font-weight: 600; }
            .slp-hint { margin: 0; font-size: .78rem; color: var(--slp-muted); }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- SalesPath.svelte — the Lightning Path: marketing-coloured chevrons, the
                 coaching "next step" field and the Mark-Stage-as-Complete CTA. Markup
                 mirrors the playground partial (slp-* classes). -->
            <script lang="ts">
              const stages = [
                { label: 'Prospecting', color: '#0176D3' },
                { label: 'Qualification', color: '#0B827C' },
                { label: 'Needs Analysis', color: '#6739B7' },
                { label: 'Value Proposition', color: '#FE9339' },
                { label: 'Id. Decision Makers', color: '#B32D69' },
                { label: 'Proposal/Price Quote', color: '#FFB75D' },
                { label: 'Negotiation/Review', color: '#BA0517' },
                { label: 'Closed Won', color: '#04844B' },
              ];

              // cur is the current stage; lost drops the whole deal to Closed Lost.
              let cur = $state(5);
              let lost = $state(false);

              const status = (i: number) =>
                lost ? (i < cur ? 'complete' : 'lost')
                  : i < cur ? 'complete' : i === cur ? 'current' : 'incomplete';
              const advance = () => { if (cur < stages.length - 1) cur++; };
              const lose = () => { lost = true; cur = stages.length - 2; };
              const revive = () => { lost = false; cur = 5; };
            </script>

            <div class="slp-root">
              <div class="slp-card">
                <div class="slp-what">
                  <small>Opportunity · Bosporus Logistics (Istanbul)</small>
                  <b>Nabu annual license — renewal</b>
                </div>

                <ol class="slp-rail">
                  {#each stages as s, i (s.label)}
                    <li>
                      <button
                        type="button"
                        class="slp-step"
                        style:--slp-stage={s.color}
                        data-status={status(i)}
                        aria-current={i === cur && !lost ? 'step' : undefined}
                        onclick={() => { if (i < cur && !lost) cur = i; }}
                      >
                        {s.label}
                      </button>
                    </li>
                  {/each}
                </ol>

                {#if !lost}
                  <div class="slp-coach" style:--slp-stage={stages[cur].color}>
                    <label for="slp-next">Next step</label>
                    <input id="slp-next" type="text" placeholder="e.g. send the revised quote" />
                  </div>
                {/if}

                <div class="slp-foot">
                  {#if !lost && cur < stages.length - 1}
                    <button type="button" class="slp-btn" data-variant="brand" onclick={advance}>
                      Mark Stage as Complete
                    </button>
                  {/if}
                  {#if cur === stages.length - 1 && !lost}
                    <span class="slp-won">✓ Won — time to invoice</span>
                  {/if}
                  {#if lost}
                    <span class="slp-flip">Reopen the deal:
                      <button type="button" class="slp-btn" onclick={revive}>Back to Proposal</button>
                    </span>
                  {:else}
                    <span class="slp-flip">Deal fell through?
                      <button type="button" class="slp-btn" data-variant="lost" onclick={lose}>Mark as Lost</button>
                    </span>
                  {/if}
                </div>
                <p class="slp-hint">Stage {cur + 1} of {stages.length} — {stages[cur].label}</p>
              </div>
            </div>

            <style>
            .slp-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slp-border: #DDDBDA; --slp-card: #FFFFFF; --slp-step-bg: #E8E8E8; --slp-step-ink: #3E3E3C;
              --slp-blue: #0176D3; --slp-red: #BA0517; --slp-green: #04844B; --slp-muted: #706E6B; }
            @media (prefers-color-scheme: dark) { .slp-root { color: #F3F3F3;
              --slp-border: #474747; --slp-card: #232323; --slp-step-bg: #3B3B3B; --slp-step-ink: #D5D5D5;
              --slp-blue: #0D9DDA; --slp-red: #FE5C4C; --slp-green: #0E9E5B; --slp-muted: #A5A5A5; } }
            .slp-card { max-inline-size: 46rem; margin-inline: auto; padding: 1rem; border: 1px solid var(--slp-border);
              border-radius: .25rem; background: var(--slp-card); display: grid; gap: 1rem; }
            .slp-what { display: grid; gap: .15rem; }
            .slp-what small { font-size: .78rem; color: var(--slp-muted); }
            .slp-what b { font-size: 1.1rem; }
            .slp-rail { display: flex; margin: 0; padding: 0; list-style: none; overflow-x: auto; }
            .slp-rail li { display: contents; }
            .slp-step { flex: 1; min-inline-size: 8.5rem; block-size: 2.6rem; padding-inline: 1.55rem; border: 0;
              cursor: pointer; text-align: start; font-size: .78rem; font-weight: 600; line-height: 1.15;
              background: var(--slp-step-bg); color: var(--slp-step-ink);
              clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%, 12px 50%); }
            .slp-step:first-child { clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%); }
            .slp-step[data-status='complete'], .slp-step[data-status='current'] { background: var(--slp-stage); color: #FFFFFF; }
            .slp-step[data-status='current'] { font-weight: 700; }
            .slp-step[data-status='lost'] { background: var(--slp-red); color: #FFFFFF; }
            .slp-coach { display: grid; gap: .35rem; padding: .75rem; border: 1px solid var(--slp-border);
              border-block-start: 2px solid var(--slp-stage); }
            .slp-coach label { font-size: .72rem; font-weight: 700; color: var(--slp-muted); }
            .slp-coach input { padding: .4rem .55rem; border: 1px solid var(--slp-border); border-radius: .25rem;
              background: var(--slp-card); color: inherit; font: inherit; font-size: .85rem; }
            .slp-foot { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
            .slp-btn { padding: .45rem 1rem; border: 1px solid var(--slp-border); border-radius: .25rem;
              background: var(--slp-card); color: inherit; font: inherit; font-size: .85rem; cursor: pointer; }
            .slp-btn[data-variant='brand'] { background: var(--slp-blue); border-color: var(--slp-blue); color: #FFFFFF; font-weight: 600; }
            .slp-btn[data-variant='lost'] { color: var(--slp-red); }
            .slp-flip { display: inline-flex; flex-wrap: wrap; align-items: center; gap: .4rem; font-size: .8rem; color: var(--slp-muted); }
            .slp-won { display: flex; align-items: center; padding: .55rem .75rem; border-radius: .25rem;
              background: color-mix(in srgb, var(--slp-green) 12%, var(--slp-card)); color: var(--slp-green);
              font-size: .85rem; font-weight: 600; }
            .slp-hint { margin: 0; font-size: .78rem; color: var(--slp-muted); }
            </style>
            SVELTE,
        ],
    ],

    'record-home' => [
        'title' => ['fa' => 'خانهٔ رکورد (Record Home)', 'en' => 'Record Home'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'سربرگ رکورد با آیکن تکی، نوار اکشن‌ها و پنل هایلایت؛ قاب استانداردی که هر آبجکت لایتنینگ در آن جا می‌گیرد.',
            'en' => 'Icon tile, action rail and the Highlights panel in one header — the frame every Lightning record lives inside.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/page-headers/',
        'props' => [
            ['name' => 'icon-tile', 'type' => 'slot', 'default' => "'letter'", 'note' => [
                'fa' => 'کاشی آیکن با پس‌زمینهٔ رنگ آبجکت و حرف اول رکورد؛ اندازهٔ بزرگ در سربرگ.',
                'en' => 'An icon tile in the object colour with the record’s initial; oversized in the header.',
            ]],
            ['name' => 'highlights', 'type' => 'panel', 'default' => "'open'", 'note' => [
                'fa' => 'پنل هایلایت زیر عنوان، فیلدهای کلیدی (مبلغ، تاریخ، مرحله) را با برچسب کوچک ردیف می‌کند.',
                'en' => 'The Highlights panel lays the key fields — amount, date, stage — under the title with small labels.',
            ]],
            ['name' => 'action-rail', 'type' => 'buttons', 'default' => "'edit · …'", 'note' => [
                'fa' => 'اکشن اصلی دکمهٔ پررنگ برند است و بقیه به منوی سرریز با آیکن … می‌روند.',
                'en' => 'The primary action is the brand button; the rest fold into the ⋯ overflow menu.',
            ]],
            ['name' => 'follow', 'type' => 'toggle', 'default' => 'false', 'note' => [
                'fa' => 'ستارهٔ کنار عنوان رکورد را دنبال می‌کند و پر شدنش خبر می‌دهد.',
                'en' => 'The star beside the title follows the record; filling it announces the change.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <header class="sflx-record">
                <span class="sflx-tile" data-object="opportunity">ف</span>
                <div>
                    <p class="sflx-crumbs">فرصت‌ها / قرارداد سالانهٔ نابو</p>
                    <h1>قرارداد سالانهٔ نابو
                        <button class="sflx-follow" aria-pressed="false" aria-label="دنبال کردن">★</button>
                    </h1>
                </div>
                <div class="sflx-actions">
                    <button class="sflx-btn" data-variant="brand">ویرایش</button>
                    <button class="sflx-btn" data-variant="neutral">حذف</button>
                </div>
            </header>
            <dl class="sflx-highlights">
                <div><dt>مبلغ</dt><dd>۲٬۴۰۰٬۰۰۰٬۰۰۰ ریال</dd></div>
                <div><dt>مرحله</dt><dd>مذاکره</dd></div>
            </dl>

            <style>
            .sflx-record { display: flex; gap: 1rem; align-items: center; padding: 1rem; background: #fff; }
            .sflx-tile { display: grid; place-items: center; inline-size: 3rem; aspect-ratio: 1;
                         border-radius: .25rem; background: #0B5CAB; color: #fff; font-weight: 700; }
            .sflx-highlights { display: flex; gap: 2rem; border-block-start: 1px solid #DDDBDA; padding: .75rem 1rem; }
            .sflx-highlights dt { font-size: .75rem; color: #706E6B; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <header class="sflx-record">
                <span class="sflx-tile" data-object="opportunity">O</span>
                <div>
                    <p class="sflx-crumbs">Opportunities / Acme annual license</p>
                    <h1>Acme annual license
                        <button class="sflx-follow" aria-pressed="false" aria-label="Follow record">★</button>
                    </h1>
                </div>
                <div class="sflx-actions">
                    <button class="sflx-btn" data-variant="brand">Edit</button>
                    <button class="sflx-btn" data-variant="neutral">Delete</button>
                </div>
            </header>
            <dl class="sflx-highlights">
                <div><dt>Amount</dt><dd>€240,000,000</dd></div>
                <div><dt>Stage</dt><dd>Negotiation/Review</dd></div>
            </dl>

            <style>
            .sflx-record { display: flex; gap: 1rem; align-items: center; padding: 1rem; background: #fff; }
            .sflx-tile { display: grid; place-items: center; inline-size: 3rem; aspect-ratio: 1;
                         border-radius: .25rem; background: #0B5CAB; color: #fff; font-weight: 700; }
            .sflx-highlights { display: flex; gap: 2rem; border-block-start: 1px solid #DDDBDA; padding: .75rem 1rem; }
            .sflx-highlights dt { font-size: .75rem; color: #706E6B; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Opportunities/Show.tsx — an Inertia page (React flavor).
            // Inertia resolves this file for the /opportunities/{record} route; the
            // RecordHome component from the React snippet renders it, with the record
            // passed down as props.
            import RecordHome from '@/components/RecordHome';

            export default function OpportunityShow() {
              // A real page would read its data: const { record } = usePage().props;
              return <RecordHome />;
            }
            TSX,
            'react' => <<<'TSX'
            // RecordHome.tsx — the Lightning Record Home header: object tile, follow
            // star, action rail with ⋯ overflow menu, and the foldable Highlights panel.
            // The markup mirrors the playground partial (slr-* classes).
            import { type CSSProperties, useEffect, useRef, useState } from 'react';
            import '@nabuxai/ui-react/css';

            const STAR = 'M12 3.5l2.6 5.3 5.9.9-4.25 4.1 1 5.85L12 16.9l-5.25 2.75 1-5.85L3.5 9.7l5.9-.9z';
            const CHEVRON = 'M6 9l6 6 6-6';

            export default function RecordHome() {
              const [follow, setFollow] = useState(false);
              const [menu, setMenu] = useState(false);
              const [expanded, setExpanded] = useState(false);
              const [toast, setToast] = useState('');
              const card = useRef<HTMLDivElement>(null);

              // Close the ⋯ menu on Escape or an outside click (Alpine did this before).
              useEffect(() => {
                const click = (e: MouseEvent) => { if (!card.current?.contains(e.target as Node)) setMenu(false); };
                const esc = (e: KeyboardEvent) => { if (e.key === 'Escape') setMenu(false); };
                document.addEventListener('click', click);
                document.addEventListener('keydown', esc);
                return () => { document.removeEventListener('click', click); document.removeEventListener('keydown', esc); };
              }, []);

              const toggleFollow = () => {
                const next = !follow;
                setFollow(next);
                setToast(next ? 'Following "Nabu annual license"' : 'Unfollowed');
                window.setTimeout(() => setToast(''), 2400);
              };

              return (
                <div className="slr-root" style={{ '--slr-tint': '#04844B' } as CSSProperties}>
                  <div className="slr-card" ref={card}>
                    <div className="slr-head">
                      <span className="slr-tile" aria-hidden="true">O</span>
                      <div className="slr-who">
                        <small>Opportunities / Industrial customers</small>
                        <h2>
                          Nabu annual license
                          <button type="button" className="slr-star" aria-pressed={follow}
                            aria-label={follow ? 'Following — unfollow' : 'Follow record'} onClick={toggleFollow}>
                            <svg className="nx-icon" viewBox="0 0 24 24" fill={follow ? 'currentColor' : 'none'}
                              stroke="currentColor" strokeLinejoin="round" aria-hidden="true"><path d={STAR} /></svg>
                          </button>
                        </h2>
                      </div>
                      <div className="slr-rail">
                        <button type="button" className="slr-btn" data-variant="brand">Edit</button>
                        <button type="button" className="slr-btn">Clone</button>
                        <button type="button" className="slr-btn slr-more" aria-haspopup="menu" aria-expanded={menu}
                          aria-label="More actions" onClick={() => setMenu(!menu)}>⋯</button>
                      </div>
                      {menu && (
                        <nav className="slr-menu" role="menu">
                          {['Delete', 'Attach file', 'Send email'].map((item) => (
                            <button key={item} type="button" role="menuitem" onClick={() => setMenu(false)}>{item}</button>
                          ))}
                        </nav>
                      )}
                    </div>

                    <div className="slr-highlights">
                      <div className="slr-hl-head">
                        <h3>Highlights</h3>
                        <button type="button" className="slr-hl-toggle" aria-expanded={expanded}
                          onClick={() => setExpanded(!expanded)}>
                          {expanded ? 'Show less' : 'Show more'}
                          <svg className="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d={CHEVRON} /></svg>
                        </button>
                      </div>
                      <dl className="slr-fields">
                        <div className="slr-field"><dt>Amount</dt><dd>€2,400,000</dd></div>
                        <div className="slr-field"><dt>Stage</dt><dd><span className="slr-badge">Negotiation/Review</span></dd></div>
                        <div className="slr-field"><dt>Close date</dt><dd>Feb 28</dd></div>
                      </dl>
                      <dl className={'slr-fields' + (expanded ? '' : ' slr-hide')}>
                        <div className="slr-field"><dt>Owner</dt><dd>Elif Demir</dd></div>
                        <div className="slr-field"><dt>Probability</dt><dd>75%</dd></div>
                        <div className="slr-field"><dt>Next step</dt><dd>Send the revised quote</dd></div>
                      </dl>
                    </div>

                    {toast && <p className="slr-toast">✓ {toast}</p>}
                  </div>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .slr-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slr-border: #DDDBDA; --slr-card: #FFFFFF; --slr-bg: #F3F3F3; --slr-blue: #0176D3;
              --slr-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --slr-muted: #706E6B; }
            @media (prefers-color-scheme: dark) { .slr-root { color: #F3F3F3;
              --slr-border: #474747; --slr-card: #232323; --slr-bg: #181818; --slr-blue: #0D9DDA;
              --slr-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --slr-muted: #A5A5A5; } }
            .slr-card { position: relative; max-inline-size: 46rem; margin-inline: auto;
              border: 1px solid var(--slr-border); border-radius: .25rem; background: var(--slr-card); }
            .slr-head { display: flex; flex-wrap: wrap; align-items: center; gap: .9rem; padding: 1rem; }
            .slr-tile { display: grid; place-items: center; inline-size: 3rem; block-size: 3rem;
              border-radius: .25rem; background: var(--slr-tint); color: #FFFFFF; font-size: 1.35rem; font-weight: 700; }
            .slr-who { min-inline-size: 0; flex: 1 1 12rem; }
            .slr-who small { display: block; font-size: .75rem; color: var(--slr-muted); }
            .slr-who h2 { margin: .1rem 0 0; font-size: 1.15rem; font-weight: 700; display: flex; align-items: center; gap: .4rem; }
            .slr-star { border: 0; padding: .15rem; background: transparent; cursor: pointer; color: var(--slr-muted);
              display: grid; place-items: center; border-radius: .25rem; }
            .slr-star[aria-pressed='true'] { color: #FFB75D; }
            .slr-rail { display: flex; flex-wrap: wrap; gap: .4rem; }
            .slr-btn { padding: .45rem .95rem; border: 1px solid var(--slr-border); border-radius: .25rem;
              background: var(--slr-card); color: inherit; font: inherit; font-size: .82rem; cursor: pointer; }
            .slr-btn[data-variant='brand'] { background: var(--slr-blue); border-color: var(--slr-blue); color: #FFFFFF; font-weight: 600; }
            .slr-more { padding-inline: .55rem; font-weight: 700; letter-spacing: .1em; }
            .slr-menu { position: absolute; z-index: 5; inset-inline-end: 1rem; inset-block-start: 3.4rem;
              min-inline-size: 10rem; padding: .25rem 0; border: 1px solid var(--slr-border); border-radius: .25rem;
              background: var(--slr-card); box-shadow: 0 2px 6px rgba(0, 0, 0, .14); }
            .slr-menu button { display: flex; inline-size: 100%; align-items: center; padding: .45rem .85rem;
              border: 0; background: transparent; color: inherit; font: inherit; font-size: .82rem; cursor: pointer; text-align: start; }
            .slr-menu button:hover { background: var(--slr-blue-soft); }
            .slr-highlights { border-block-start: 2px solid var(--slr-tint); padding: .85rem 1rem 1rem; }
            .slr-hl-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
            .slr-hl-head h3 { margin: 0; font-size: .95rem; font-weight: 700; }
            .slr-hl-toggle { border: 0; background: transparent; color: var(--slr-blue); font: inherit;
              font-size: .78rem; cursor: pointer; display: inline-flex; align-items: center; gap: .2rem; }
            .slr-hl-toggle[aria-expanded='true'] .nx-icon { rotate: 180deg; }
            .slr-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(9rem, 1fr));
              gap: .8rem 1.25rem; margin: .8rem 0 0; }
            .slr-field dt { font-size: .72rem; color: var(--slr-muted); }
            .slr-field dd { margin: .1rem 0 0; font-size: .88rem; font-weight: 700; }
            .slr-badge { display: inline-flex; align-items: center; padding: .1rem .55rem; border-radius: .9rem;
              background: color-mix(in srgb, var(--slr-tint) 16%, var(--slr-card)); color: var(--slr-tint);
              font-size: .78rem; font-weight: 700; }
            .slr-hide { display: none; }
            .slr-toast { display: flex; align-items: center; gap: .5rem; margin: .85rem 1rem 1rem;
              padding: .55rem .75rem; border-radius: .25rem;
              background: color-mix(in srgb, var(--slr-tint) 12%, var(--slr-card)); color: var(--slr-tint);
              font-size: .8rem; font-weight: 600; }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- RecordHome.vue — the Lightning Record Home header: object tile, follow
                 star, action rail with ⋯ overflow menu, foldable Highlights panel.
                 Markup mirrors the playground partial (slr-* classes). -->
            <script setup lang="ts">
            import { onBeforeUnmount, onMounted, ref } from 'vue';

            const follow = ref(false);
            const menu = ref(false);
            const expanded = ref(false);
            const toast = ref('');
            const card = ref<HTMLElement | null>(null);
            let timer: number | undefined;

            // Close the ⋯ menu on Escape or an outside click (Alpine did this before).
            const onClick = (e: MouseEvent) => { if (!card.value?.contains(e.target as Node)) menu.value = false; };
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') menu.value = false; };
            onMounted(() => {
              document.addEventListener('click', onClick);
              document.addEventListener('keydown', onKey);
            });
            onBeforeUnmount(() => {
              document.removeEventListener('click', onClick);
              document.removeEventListener('keydown', onKey);
              window.clearTimeout(timer);
            });

            const menuItems = ['Delete', 'Attach file', 'Send email'];
            const toggleFollow = () => {
              follow.value = !follow.value;
              toast.value = follow.value ? 'Following "Nabu annual license"' : 'Unfollowed';
              window.clearTimeout(timer);
              timer = window.setTimeout(() => (toast.value = ''), 2400);
            };
            </script>

            <template>
              <div class="slr-root" style="--slr-tint: #04844B">
                <div ref="card" class="slr-card">
                  <div class="slr-head">
                    <span class="slr-tile" aria-hidden="true">O</span>
                    <div class="slr-who">
                      <small>Opportunities / Industrial customers</small>
                      <h2>
                        Nabu annual license
                        <button type="button" class="slr-star" :aria-pressed="follow"
                          :aria-label="follow ? 'Following — unfollow' : 'Follow record'" @click="toggleFollow">★</button>
                      </h2>
                    </div>
                    <div class="slr-rail">
                      <button type="button" class="slr-btn" data-variant="brand">Edit</button>
                      <button type="button" class="slr-btn">Clone</button>
                      <button type="button" class="slr-btn slr-more" aria-haspopup="menu" :aria-expanded="menu"
                        aria-label="More actions" @click="menu = !menu">⋯</button>
                    </div>
                    <nav v-if="menu" class="slr-menu" role="menu">
                      <button v-for="item in menuItems" :key="item" type="button" role="menuitem" @click="menu = false">
                        {{ item }}
                      </button>
                    </nav>
                  </div>

                  <div class="slr-highlights">
                    <div class="slr-hl-head">
                      <h3>Highlights</h3>
                      <button type="button" class="slr-hl-toggle" :aria-expanded="expanded" @click="expanded = !expanded">
                        {{ expanded ? 'Show less' : 'Show more' }} ▾
                      </button>
                    </div>
                    <dl class="slr-fields">
                      <div class="slr-field"><dt>Amount</dt><dd>€2,400,000</dd></div>
                      <div class="slr-field"><dt>Stage</dt><dd><span class="slr-badge">Negotiation/Review</span></dd></div>
                      <div class="slr-field"><dt>Close date</dt><dd>Feb 28</dd></div>
                    </dl>
                    <dl class="slr-fields" :class="expanded ? '' : 'slr-hide'">
                      <div class="slr-field"><dt>Owner</dt><dd>Elif Demir</dd></div>
                      <div class="slr-field"><dt>Probability</dt><dd>75%</dd></div>
                      <div class="slr-field"><dt>Next step</dt><dd>Send the revised quote</dd></div>
                    </dl>
                  </div>

                  <p v-if="toast" class="slr-toast">✓ {{ toast }}</p>
                </div>
              </div>
            </template>

            <style scoped>
            .slr-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slr-border: #DDDBDA; --slr-card: #FFFFFF; --slr-bg: #F3F3F3; --slr-blue: #0176D3;
              --slr-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --slr-muted: #706E6B; }
            @media (prefers-color-scheme: dark) { .slr-root { color: #F3F3F3;
              --slr-border: #474747; --slr-card: #232323; --slr-bg: #181818; --slr-blue: #0D9DDA;
              --slr-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --slr-muted: #A5A5A5; } }
            .slr-card { position: relative; max-inline-size: 46rem; margin-inline: auto;
              border: 1px solid var(--slr-border); border-radius: .25rem; background: var(--slr-card); }
            .slr-head { display: flex; flex-wrap: wrap; align-items: center; gap: .9rem; padding: 1rem; }
            .slr-tile { display: grid; place-items: center; inline-size: 3rem; block-size: 3rem;
              border-radius: .25rem; background: var(--slr-tint); color: #FFFFFF; font-size: 1.35rem; font-weight: 700; }
            .slr-who { min-inline-size: 0; flex: 1 1 12rem; }
            .slr-who small { display: block; font-size: .75rem; color: var(--slr-muted); }
            .slr-who h2 { margin: .1rem 0 0; font-size: 1.15rem; font-weight: 700; display: flex; align-items: center; gap: .4rem; }
            .slr-star { border: 0; padding: .15rem; background: transparent; cursor: pointer; color: var(--slr-muted); }
            .slr-star[aria-pressed='true'] { color: #FFB75D; }
            .slr-rail { display: flex; flex-wrap: wrap; gap: .4rem; }
            .slr-btn { padding: .45rem .95rem; border: 1px solid var(--slr-border); border-radius: .25rem;
              background: var(--slr-card); color: inherit; font: inherit; font-size: .82rem; cursor: pointer; }
            .slr-btn[data-variant='brand'] { background: var(--slr-blue); border-color: var(--slr-blue); color: #FFFFFF; font-weight: 600; }
            .slr-more { padding-inline: .55rem; font-weight: 700; letter-spacing: .1em; }
            .slr-menu { position: absolute; z-index: 5; inset-inline-end: 1rem; inset-block-start: 3.4rem;
              min-inline-size: 10rem; padding: .25rem 0; border: 1px solid var(--slr-border); border-radius: .25rem;
              background: var(--slr-card); box-shadow: 0 2px 6px rgba(0, 0, 0, .14); }
            .slr-menu button { display: flex; inline-size: 100%; padding: .45rem .85rem; border: 0;
              background: transparent; color: inherit; font: inherit; font-size: .82rem; cursor: pointer; text-align: start; }
            .slr-menu button:hover { background: var(--slr-blue-soft); }
            .slr-highlights { border-block-start: 2px solid var(--slr-tint); padding: .85rem 1rem 1rem; }
            .slr-hl-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
            .slr-hl-head h3 { margin: 0; font-size: .95rem; font-weight: 700; }
            .slr-hl-toggle { border: 0; background: transparent; color: var(--slr-blue); font: inherit;
              font-size: .78rem; cursor: pointer; }
            .slr-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(9rem, 1fr));
              gap: .8rem 1.25rem; margin: .8rem 0 0; }
            .slr-field dt { font-size: .72rem; color: var(--slr-muted); }
            .slr-field dd { margin: .1rem 0 0; font-size: .88rem; font-weight: 700; }
            .slr-badge { display: inline-flex; align-items: center; padding: .1rem .55rem; border-radius: .9rem;
              background: color-mix(in srgb, var(--slr-tint) 16%, var(--slr-card)); color: var(--slr-tint);
              font-size: .78rem; font-weight: 700; }
            .slr-hide { display: none; }
            .slr-toast { display: flex; align-items: center; gap: .5rem; margin: .85rem 1rem 1rem;
              padding: .55rem .75rem; border-radius: .25rem;
              background: color-mix(in srgb, var(--slr-tint) 12%, var(--slr-card)); color: var(--slr-tint);
              font-size: .8rem; font-weight: 600; }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- RecordHome.svelte — the Lightning Record Home header: object tile,
                 follow star, action rail with ⋯ overflow menu, foldable Highlights
                 panel. Markup mirrors the playground partial (slr-* classes). -->
            <script lang="ts">
              let follow = $state(false);
              let menu = $state(false);
              let expanded = $state(false);
              let toast = $state('');
              let card: HTMLElement;
              let timer: ReturnType<typeof setTimeout>;

              const menuItems = ['Delete', 'Attach file', 'Send email'];
              const toggleFollow = () => {
                follow = !follow;
                toast = follow ? 'Following "Nabu annual license"' : 'Unfollowed';
                clearTimeout(timer);
                timer = setTimeout(() => (toast = ''), 2400);
              };
              const dismiss = (e: MouseEvent) => { if (!card.contains(e.target as Node)) menu = false; };
            </script>

            <svelte:document onclick={dismiss} onkeydown={(e) => e.key === 'Escape' && (menu = false)} />

            <div class="slr-root" style:--slr-tint="#04844B">
              <div bind:this={card} class="slr-card">
                <div class="slr-head">
                  <span class="slr-tile" aria-hidden="true">O</span>
                  <div class="slr-who">
                    <small>Opportunities / Industrial customers</small>
                    <h2>
                      Nabu annual license
                      <button type="button" class="slr-star" aria-pressed={follow}
                        aria-label={follow ? 'Following — unfollow' : 'Follow record'} onclick={toggleFollow}>★</button>
                    </h2>
                  </div>
                  <div class="slr-rail">
                    <button type="button" class="slr-btn" data-variant="brand">Edit</button>
                    <button type="button" class="slr-btn">Clone</button>
                    <button type="button" class="slr-btn slr-more" aria-haspopup="menu" aria-expanded={menu}
                      aria-label="More actions" onclick={() => (menu = !menu)}>⋯</button>
                  </div>
                  {#if menu}
                    <nav class="slr-menu" role="menu">
                      {#each menuItems as item (item)}
                        <button type="button" role="menuitem" onclick={() => (menu = false)}>{item}</button>
                      {/each}
                    </nav>
                  {/if}
                </div>

                <div class="slr-highlights">
                  <div class="slr-hl-head">
                    <h3>Highlights</h3>
                    <button type="button" class="slr-hl-toggle" aria-expanded={expanded}
                      onclick={() => (expanded = !expanded)}>
                      {expanded ? 'Show less' : 'Show more'} ▾
                    </button>
                  </div>
                  <dl class="slr-fields">
                    <div class="slr-field"><dt>Amount</dt><dd>€2,400,000</dd></div>
                    <div class="slr-field"><dt>Stage</dt><dd><span class="slr-badge">Negotiation/Review</span></dd></div>
                    <div class="slr-field"><dt>Close date</dt><dd>Feb 28</dd></div>
                  </dl>
                  <dl class="slr-fields" class:slr-hide={!expanded}>
                    <div class="slr-field"><dt>Owner</dt><dd>Elif Demir</dd></div>
                    <div class="slr-field"><dt>Probability</dt><dd>75%</dd></div>
                    <div class="slr-field"><dt>Next step</dt><dd>Send the revised quote</dd></div>
                  </dl>
                </div>

                {#if toast}<p class="slr-toast">✓ {toast}</p>{/if}
              </div>
            </div>

            <style>
            .slr-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slr-border: #DDDBDA; --slr-card: #FFFFFF; --slr-bg: #F3F3F3; --slr-blue: #0176D3;
              --slr-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --slr-muted: #706E6B; }
            @media (prefers-color-scheme: dark) { .slr-root { color: #F3F3F3;
              --slr-border: #474747; --slr-card: #232323; --slr-bg: #181818; --slr-blue: #0D9DDA;
              --slr-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --slr-muted: #A5A5A5; } }
            .slr-card { position: relative; max-inline-size: 46rem; margin-inline: auto;
              border: 1px solid var(--slr-border); border-radius: .25rem; background: var(--slr-card); }
            .slr-head { display: flex; flex-wrap: wrap; align-items: center; gap: .9rem; padding: 1rem; }
            .slr-tile { display: grid; place-items: center; inline-size: 3rem; block-size: 3rem;
              border-radius: .25rem; background: var(--slr-tint); color: #FFFFFF; font-size: 1.35rem; font-weight: 700; }
            .slr-who { min-inline-size: 0; flex: 1 1 12rem; }
            .slr-who small { display: block; font-size: .75rem; color: var(--slr-muted); }
            .slr-who h2 { margin: .1rem 0 0; font-size: 1.15rem; font-weight: 700; display: flex; align-items: center; gap: .4rem; }
            .slr-star { border: 0; padding: .15rem; background: transparent; cursor: pointer; color: var(--slr-muted); }
            .slr-star[aria-pressed='true'] { color: #FFB75D; }
            .slr-rail { display: flex; flex-wrap: wrap; gap: .4rem; }
            .slr-btn { padding: .45rem .95rem; border: 1px solid var(--slr-border); border-radius: .25rem;
              background: var(--slr-card); color: inherit; font: inherit; font-size: .82rem; cursor: pointer; }
            .slr-btn[data-variant='brand'] { background: var(--slr-blue); border-color: var(--slr-blue); color: #FFFFFF; font-weight: 600; }
            .slr-more { padding-inline: .55rem; font-weight: 700; letter-spacing: .1em; }
            .slr-menu { position: absolute; z-index: 5; inset-inline-end: 1rem; inset-block-start: 3.4rem;
              min-inline-size: 10rem; padding: .25rem 0; border: 1px solid var(--slr-border); border-radius: .25rem;
              background: var(--slr-card); box-shadow: 0 2px 6px rgba(0, 0, 0, .14); }
            .slr-menu button { display: flex; inline-size: 100%; padding: .45rem .85rem; border: 0;
              background: transparent; color: inherit; font: inherit; font-size: .82rem; cursor: pointer; text-align: start; }
            .slr-menu button:hover { background: var(--slr-blue-soft); }
            .slr-highlights { border-block-start: 2px solid var(--slr-tint); padding: .85rem 1rem 1rem; }
            .slr-hl-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem; }
            .slr-hl-head h3 { margin: 0; font-size: .95rem; font-weight: 700; }
            .slr-hl-toggle { border: 0; background: transparent; color: var(--slr-blue); font: inherit;
              font-size: .78rem; cursor: pointer; }
            .slr-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(9rem, 1fr));
              gap: .8rem 1.25rem; margin: .8rem 0 0; }
            .slr-field dt { font-size: .72rem; color: var(--slr-muted); }
            .slr-field dd { margin: .1rem 0 0; font-size: .88rem; font-weight: 700; }
            .slr-badge { display: inline-flex; align-items: center; padding: .1rem .55rem; border-radius: .9rem;
              background: color-mix(in srgb, var(--slr-tint) 16%, var(--slr-card)); color: var(--slr-tint);
              font-size: .78rem; font-weight: 700; }
            .slr-hide { display: none; }
            .slr-toast { display: flex; align-items: center; gap: .5rem; margin: .85rem 1rem 1rem;
              padding: .55rem .75rem; border-radius: .25rem;
              background: color-mix(in srgb, var(--slr-tint) 12%, var(--slr-card)); color: var(--slr-tint);
              font-size: .8rem; font-weight: 600; }
            </style>
            SVELTE,
        ],
    ],

    'related-list' => [
        'title' => ['fa' => 'لیست مرتبط (Related List)', 'en' => 'Related List'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'کارت‌های «موارد مرتبط» با سربرگ آیکن‌دار و دکمهٔ View All؛ رابطهٔ آبجکت‌ها را بی‌هیچ توضیحی لو می‌دهد.',
            'en' => 'Icon-headed cards of child records with a View All footer — object relationships at a glance.',
        ],
        'js' => true,
        'docs' => 'https://www.lightningdesignsystem.com/components/cards/',
        'props' => [
            ['name' => 'icon-header', 'type' => 'slot', 'default' => "'object'", 'note' => [
                'fa' => 'آیکن کوچک آبجکت کنار عنوان کارت می‌نشیند و آبجکتِ مرتبط را در نگاه اول می‌گوید.',
                'en' => 'The object’s small icon sits beside the card title and names the related object at a glance.',
            ]],
            ['name' => 'count', 'type' => 'number', 'default' => "'title'", 'note' => [
                'fa' => 'شمار رکوردها داخل پرانتز کنار عنوان: «مخاطب‌ها (۵)».',
                'en' => 'The record count rides in parentheses on the title: «Contacts (5)».',
            ]],
            ['name' => 'view-all', 'type' => 'footer', 'default' => "'link'", 'note' => [
                'fa' => 'پانویس کارت با لینک View All به نمای کامل لیست می‌رود.',
                'en' => 'The card footer’s View All link opens the list’s full view.',
            ]],
            ['name' => 'row-actions', 'type' => 'menu', 'default' => "'⋯'", 'note' => [
                'fa' => 'هر ردیف منوی سرریز خودش را دارد: ویرایش، حذف، بازکردن.',
                'en' => 'Every row carries its own overflow menu: edit, delete, open.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <article class="sflx-related">
                <header>
                    <span class="sflx-rl-icon" data-object="contact">م</span>
                    <h2>مخاطب‌ها (۳)</h2>
                    <button class="sflx-btn" data-variant="neutral">جدید</button>
                </header>
                <ul>
                    <li><b>سارا احمدی</b><span>sara@acme.example</span></li>
                    <li><b>رضا کاظمی</b><span>reza@acme.example</span></li>
                </ul>
                <footer><a href="#">مشاهدهٔ همه</a></footer>
            </article>

            <style>
            .sflx-related { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
            .sflx-related header { display: flex; align-items: center; gap: .5rem; padding: .75rem 1rem; }
            .sflx-rl-icon { inline-size: 1.5rem; aspect-ratio: 1; display: grid; place-items: center;
                            border-radius: .25rem; background: #0B5CAB; color: #fff; font-size: .7rem; }
            .sflx-related ul li { display: grid; padding: .5rem 1rem; border-block-start: 1px solid #DDDBDA; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <article class="sflx-related">
                <header>
                    <span class="sflx-rl-icon" data-object="contact">C</span>
                    <h2>Contacts (3)</h2>
                    <button class="sflx-btn" data-variant="neutral">New</button>
                </header>
                <ul>
                    <li><b>Lena Hoffmann</b><span>lena@acme.example</span></li>
                    <li><b>Emre Yilmaz</b><span>emre@acme.example</span></li>
                </ul>
                <footer><a href="#">View all</a></footer>
            </article>

            <style>
            .sflx-related { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
            .sflx-related header { display: flex; align-items: center; gap: .5rem; padding: .75rem 1rem; }
            .sflx-rl-icon { inline-size: 1.5rem; aspect-ratio: 1; display: grid; place-items: center;
                            border-radius: .25rem; background: #0B5CAB; color: #fff; font-size: .7rem; }
            .sflx-related ul li { display: grid; padding: .5rem 1rem; border-block-start: 1px solid #DDDBDA; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Opportunities/Related.tsx — an Inertia page (React
            // flavor). The child records arrive from the backend as props; rendering is
            // the RelatedList component from the React snippet.
            import RelatedList from '@/components/RelatedList';

            export default function OpportunityRelated() {
              // A real page would read its data: const { contacts, files } = usePage().props;
              return <RelatedList />;
            }
            TSX,
            'react' => <<<'TSX'
            // RelatedList.tsx — two icon-headed related-list cards (Contacts, Files)
            // with honest counts, a New action, row overflow menus and View All footers.
            // The markup mirrors the playground partial (sll-* classes).
            import { type CSSProperties, useState } from 'react';
            import '@nabuxai/ui-react/css';

            type Person = { name: string; role: string; mail: string };
            const POOL: Person[] = [
              { name: 'Elif Demir', role: 'Head of purchasing', mail: 'elif@acme.example' },
              { name: 'Lena Fischer', role: 'Technical reviewer', mail: 'lena@acme.example' },
              { name: 'Marco Benzi', role: 'Finance manager', mail: 'marco@acme.example' },
            ];
            const FILES = [
              { name: 'Quote-2026.pdf', meta: '248 KB · yesterday' },
              { name: 'Contract-Bosporus.docx', meta: '1.1 MB · 3 days ago' },
            ];

            const PATHS = {
              externalLink: 'M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5',
              edit: 'M4 20h4L19 9l-4-4L4 16zM13.5 6.5l4 4',
              trash: 'M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3',
              file: 'M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8zM14 3v5h5',
              chevronLeft: 'M15 6l-6 6 6 6',
            };

            const I = ({ d }: { d: string }) => (
              <svg className="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                strokeLinecap="round" strokeLinejoin="round" aria-hidden="true"><path d={d} /></svg>
            );

            export default function RelatedList() {
              const [people, setPeople] = useState<Person[]>(POOL.slice(0, 2));
              const [next, setNext] = useState(2);
              const [menuFor, setMenuFor] = useState<number | null>(null);

              const add = () => { setPeople([...people, POOL[next % POOL.length]]); setNext(next + 1); };
              const remove = (i: number) => { setPeople(people.filter((_, j) => j !== i)); setMenuFor(null); };

              return (
                <div className="sll-root">
                  <div className="sll-row">
                    <article className="sll-card" style={{ '--sll-tint': '#6739B7' } as CSSProperties}>
                      <header className="sll-head">
                        <span className="sll-tile" aria-hidden="true">C</span>
                        <h3>Contacts ({people.length})</h3>
                        <button type="button" className="sll-btn sll-new" onClick={add}>New</button>
                      </header>
                      <ul className="sll-list">
                        {people.map((p, i) => (
                          <li key={p.mail} className="sll-item">
                            <div><b>{p.name}</b><span>{p.role} · {p.mail}</span></div>
                            <span className="sll-rowmenu">
                              <button type="button" className="sll-dots" aria-haspopup="menu"
                                aria-expanded={menuFor === i} aria-label={'Actions for ' + p.name}
                                onClick={() => setMenuFor(menuFor === i ? null : i)}>⋯</button>
                              {menuFor === i && (
                                <nav className="sll-menu" role="menu">
                                  <button type="button" role="menuitem" onClick={() => setMenuFor(null)}><I d={PATHS.externalLink} /> Open</button>
                                  <button type="button" role="menuitem" onClick={() => setMenuFor(null)}><I d={PATHS.edit} /> Edit</button>
                                  <button type="button" role="menuitem" data-tone="danger" onClick={() => remove(i)}><I d={PATHS.trash} /> Remove</button>
                                </nav>
                              )}
                            </span>
                          </li>
                        ))}
                      </ul>
                      <footer className="sll-foot"><a href="#contacts">View all <I d={PATHS.chevronLeft} /></a></footer>
                    </article>

                    <article className="sll-card" style={{ '--sll-tint': '#FE9339' } as CSSProperties}>
                      <header className="sll-head">
                        <span className="sll-tile" aria-hidden="true">F</span>
                        <h3>Files ({FILES.length})</h3>
                      </header>
                      <ul className="sll-list">
                        {FILES.map((f) => (
                          <li key={f.name} className="sll-item">
                            <span className="sll-file">
                              <I d={PATHS.file} />
                              <div><b>{f.name}</b><span>{f.meta}</span></div>
                            </span>
                          </li>
                        ))}
                      </ul>
                      <footer className="sll-foot"><a href="#files">View all <I d={PATHS.chevronLeft} /></a></footer>
                    </article>
                  </div>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .sll-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sll-border: #DDDBDA; --sll-card: #FFFFFF; --sll-bg: #F3F3F3; --sll-blue: #0176D3;
              --sll-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sll-muted: #706E6B; --sll-red: #BA0517; }
            @media (prefers-color-scheme: dark) { .sll-root { color: #F3F3F3;
              --sll-border: #474747; --sll-card: #232323; --sll-bg: #181818; --sll-blue: #0D9DDA;
              --sll-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sll-muted: #A5A5A5; --sll-red: #FE5C4C; } }
            .sll-row { display: flex; flex-wrap: wrap; gap: 1rem; }
            .sll-card { flex: 1 1 17rem; min-inline-size: 0; border: 1px solid var(--sll-border);
              border-radius: .25rem; background: var(--sll-card); display: grid; }
            .sll-head { display: flex; align-items: center; gap: .5rem; padding: .7rem .9rem; }
            .sll-tile { display: grid; place-items: center; inline-size: 1.5rem; block-size: 1.5rem;
              border-radius: .25rem; background: var(--sll-tint); color: #FFFFFF; font-size: .72rem; font-weight: 700; }
            .sll-head h3 { margin: 0; font-size: .95rem; font-weight: 700; }
            .sll-new { margin-inline-start: auto; }
            .sll-list { margin: 0; padding: 0; list-style: none; }
            .sll-item { display: flex; align-items: center; gap: .6rem; padding: .55rem .9rem; }
            .sll-item + .sll-item { border-block-start: 1px solid var(--sll-border); }
            .sll-item > div { min-inline-size: 0; flex: 1; }
            .sll-item b { display: block; font-size: .85rem; font-weight: 600; }
            .sll-item span { display: block; font-size: .76rem; color: var(--sll-muted); }
            .sll-rowmenu { position: relative; flex: none; }
            .sll-dots { border: 0; background: transparent; color: var(--sll-muted); cursor: pointer;
              padding: .1rem .35rem; border-radius: .25rem; font-weight: 700; }
            .sll-dots:hover { background: var(--sll-blue-soft); color: var(--sll-blue); }
            .sll-menu { position: absolute; z-index: 5; inset-inline-end: 0; inset-block-start: 1.5rem;
              min-inline-size: 8.5rem; padding: .2rem 0; border: 1px solid var(--sll-border); border-radius: .25rem;
              background: var(--sll-card); box-shadow: 0 2px 6px rgba(0, 0, 0, .14); }
            .sll-menu button { display: flex; inline-size: 100%; align-items: center; gap: .45rem; padding: .4rem .75rem;
              border: 0; background: transparent; color: inherit; font: inherit; font-size: .8rem; cursor: pointer; text-align: start; }
            .sll-menu button:hover { background: var(--sll-blue-soft); }
            .sll-menu [data-tone='danger'] { color: var(--sll-red); }
            .sll-foot { border-block-start: 1px solid var(--sll-border); padding: .5rem .9rem; }
            .sll-foot a { color: var(--sll-blue); font-size: .8rem; font-weight: 600; text-decoration: none;
              display: inline-flex; align-items: center; gap: .25rem; }
            .sll-file { display: flex; align-items: center; gap: .6rem; }
            .sll-file .nx-icon { color: var(--sll-muted); }
            .sll-btn { padding: .35rem .8rem; border: 1px solid var(--sll-border); border-radius: .25rem;
              background: var(--sll-card); color: var(--sll-blue); font: inherit; font-size: .78rem;
              font-weight: 600; cursor: pointer; }
            .sll-btn:hover { background: var(--sll-blue-soft); }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- RelatedList.vue — two icon-headed related-list cards (Contacts, Files)
                 with honest counts, a New action, row overflow menus and View All
                 footers. Markup mirrors the playground partial (sll-* classes). -->
            <script setup lang="ts">
            import { ref } from 'vue';

            interface Person { name: string; role: string; mail: string }
            const pool: Person[] = [
              { name: 'Elif Demir', role: 'Head of purchasing', mail: 'elif@acme.example' },
              { name: 'Lena Fischer', role: 'Technical reviewer', mail: 'lena@acme.example' },
              { name: 'Marco Benzi', role: 'Finance manager', mail: 'marco@acme.example' },
            ];
            const files = [
              { name: 'Quote-2026.pdf', meta: '248 KB · yesterday' },
              { name: 'Contract-Bosporus.docx', meta: '1.1 MB · 3 days ago' },
            ];

            const people = ref<Person[]>(pool.slice(0, 2));
            const next = ref(2);
            const menuFor = ref<number | null>(null);

            const add = () => { people.value = [...people.value, pool[next.value % pool.length]]; next.value++; };
            const remove = (i: number) => { people.value = people.value.filter((_, j) => j !== i); menuFor.value = null; };
            </script>

            <template>
              <div class="sll-root">
                <div class="sll-row">
                  <article class="sll-card" style="--sll-tint: #6739B7">
                    <header class="sll-head">
                      <span class="sll-tile" aria-hidden="true">C</span>
                      <h3>Contacts ({{ people.length }})</h3>
                      <button type="button" class="sll-btn sll-new" @click="add">New</button>
                    </header>
                    <ul class="sll-list">
                      <li v-for="(p, i) in people" :key="p.mail" class="sll-item">
                        <div><b>{{ p.name }}</b><span>{{ p.role }} · {{ p.mail }}</span></div>
                        <span class="sll-rowmenu">
                          <button type="button" class="sll-dots" aria-haspopup="menu"
                            :aria-expanded="menuFor === i" :aria-label="'Actions for ' + p.name"
                            @click="menuFor = menuFor === i ? null : i">⋯</button>
                          <nav v-if="menuFor === i" class="sll-menu" role="menu">
                            <button type="button" role="menuitem" @click="menuFor = null">Open</button>
                            <button type="button" role="menuitem" @click="menuFor = null">Edit</button>
                            <button type="button" role="menuitem" data-tone="danger" @click="remove(i)">Remove</button>
                          </nav>
                        </span>
                      </li>
                    </ul>
                    <footer class="sll-foot"><a href="#contacts">View all ›</a></footer>
                  </article>

                  <article class="sll-card" style="--sll-tint: #FE9339">
                    <header class="sll-head">
                      <span class="sll-tile" aria-hidden="true">F</span>
                      <h3>Files ({{ files.length }})</h3>
                    </header>
                    <ul class="sll-list">
                      <li v-for="f in files" :key="f.name" class="sll-item">
                        <span class="sll-file"><div><b>{{ f.name }}</b><span>{{ f.meta }}</span></div></span>
                      </li>
                    </ul>
                    <footer class="sll-foot"><a href="#files">View all ›</a></footer>
                  </article>
                </div>
              </div>
            </template>

            <style scoped>
            .sll-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sll-border: #DDDBDA; --sll-card: #FFFFFF; --sll-bg: #F3F3F3; --sll-blue: #0176D3;
              --sll-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sll-muted: #706E6B; --sll-red: #BA0517; }
            @media (prefers-color-scheme: dark) { .sll-root { color: #F3F3F3;
              --sll-border: #474747; --sll-card: #232323; --sll-bg: #181818; --sll-blue: #0D9DDA;
              --sll-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sll-muted: #A5A5A5; --sll-red: #FE5C4C; } }
            .sll-row { display: flex; flex-wrap: wrap; gap: 1rem; }
            .sll-card { flex: 1 1 17rem; min-inline-size: 0; border: 1px solid var(--sll-border);
              border-radius: .25rem; background: var(--sll-card); display: grid; }
            .sll-head { display: flex; align-items: center; gap: .5rem; padding: .7rem .9rem; }
            .sll-tile { display: grid; place-items: center; inline-size: 1.5rem; block-size: 1.5rem;
              border-radius: .25rem; background: var(--sll-tint); color: #FFFFFF; font-size: .72rem; font-weight: 700; }
            .sll-head h3 { margin: 0; font-size: .95rem; font-weight: 700; }
            .sll-new { margin-inline-start: auto; }
            .sll-list { margin: 0; padding: 0; list-style: none; }
            .sll-item { display: flex; align-items: center; gap: .6rem; padding: .55rem .9rem; }
            .sll-item + .sll-item { border-block-start: 1px solid var(--sll-border); }
            .sll-item > div { min-inline-size: 0; flex: 1; }
            .sll-item b { display: block; font-size: .85rem; font-weight: 600; }
            .sll-item span { display: block; font-size: .76rem; color: var(--sll-muted); }
            .sll-rowmenu { position: relative; flex: none; }
            .sll-dots { border: 0; background: transparent; color: var(--sll-muted); cursor: pointer;
              padding: .1rem .35rem; border-radius: .25rem; font-weight: 700; }
            .sll-dots:hover { background: var(--sll-blue-soft); color: var(--sll-blue); }
            .sll-menu { position: absolute; z-index: 5; inset-inline-end: 0; inset-block-start: 1.5rem;
              min-inline-size: 8.5rem; padding: .2rem 0; border: 1px solid var(--sll-border); border-radius: .25rem;
              background: var(--sll-card); box-shadow: 0 2px 6px rgba(0, 0, 0, .14); }
            .sll-menu button { display: flex; inline-size: 100%; padding: .4rem .75rem; border: 0;
              background: transparent; color: inherit; font: inherit; font-size: .8rem; cursor: pointer; text-align: start; }
            .sll-menu button:hover { background: var(--sll-blue-soft); }
            .sll-menu [data-tone='danger'] { color: var(--sll-red); }
            .sll-foot { border-block-start: 1px solid var(--sll-border); padding: .5rem .9rem; }
            .sll-foot a { color: var(--sll-blue); font-size: .8rem; font-weight: 600; text-decoration: none; }
            .sll-file { display: flex; align-items: center; gap: .6rem; }
            .sll-file div { min-inline-size: 0; }
            .sll-btn { padding: .35rem .8rem; border: 1px solid var(--sll-border); border-radius: .25rem;
              background: var(--sll-card); color: var(--sll-blue); font: inherit; font-size: .78rem;
              font-weight: 600; cursor: pointer; }
            .sll-btn:hover { background: var(--sll-blue-soft); }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- RelatedList.svelte — two icon-headed related-list cards (Contacts,
                 Files) with honest counts, a New action, row overflow menus and View All
                 footers. Markup mirrors the playground partial (sll-* classes). -->
            <script lang="ts">
              interface Person { name: string; role: string; mail: string }
              const pool: Person[] = [
                { name: 'Elif Demir', role: 'Head of purchasing', mail: 'elif@acme.example' },
                { name: 'Lena Fischer', role: 'Technical reviewer', mail: 'lena@acme.example' },
                { name: 'Marco Benzi', role: 'Finance manager', mail: 'marco@acme.example' },
              ];
              const files = [
                { name: 'Quote-2026.pdf', meta: '248 KB · yesterday' },
                { name: 'Contract-Bosporus.docx', meta: '1.1 MB · 3 days ago' },
              ];

              let people = $state<Person[]>(pool.slice(0, 2));
              let next = $state(2);
              let menuFor = $state<number | null>(null);

              const add = () => { people = [...people, pool[next % pool.length]]; next++; };
              const remove = (i: number) => { people = people.filter((_, j) => j !== i); menuFor = null; };
            </script>

            <div class="sll-root">
              <div class="sll-row">
                <article class="sll-card" style:--sll-tint="#6739B7">
                  <header class="sll-head">
                    <span class="sll-tile" aria-hidden="true">C</span>
                    <h3>Contacts ({people.length})</h3>
                    <button type="button" class="sll-btn sll-new" onclick={add}>New</button>
                  </header>
                  <ul class="sll-list">
                    {#each people as p, i (p.mail)}
                      <li class="sll-item">
                        <div><b>{p.name}</b><span>{p.role} · {p.mail}</span></div>
                        <span class="sll-rowmenu">
                          <button type="button" class="sll-dots" aria-haspopup="menu"
                            aria-expanded={menuFor === i} aria-label={'Actions for ' + p.name}
                            onclick={() => (menuFor = menuFor === i ? null : i)}>⋯</button>
                          {#if menuFor === i}
                            <nav class="sll-menu" role="menu">
                              <button type="button" role="menuitem" onclick={() => (menuFor = null)}>Open</button>
                              <button type="button" role="menuitem" onclick={() => (menuFor = null)}>Edit</button>
                              <button type="button" role="menuitem" data-tone="danger" onclick={() => remove(i)}>Remove</button>
                            </nav>
                          {/if}
                        </span>
                      </li>
                    {/each}
                  </ul>
                  <footer class="sll-foot"><a href="#contacts">View all ›</a></footer>
                </article>

                <article class="sll-card" style:--sll-tint="#FE9339">
                  <header class="sll-head">
                    <span class="sll-tile" aria-hidden="true">F</span>
                    <h3>Files ({files.length})</h3>
                  </header>
                  <ul class="sll-list">
                    {#each files as f (f.name)}
                      <li class="sll-item">
                        <span class="sll-file"><div><b>{f.name}</b><span>{f.meta}</span></div></span>
                      </li>
                    {/each}
                  </ul>
                  <footer class="sll-foot"><a href="#files">View all ›</a></footer>
                </article>
              </div>
            </div>

            <style>
            .sll-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sll-border: #DDDBDA; --sll-card: #FFFFFF; --sll-bg: #F3F3F3; --sll-blue: #0176D3;
              --sll-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sll-muted: #706E6B; --sll-red: #BA0517; }
            @media (prefers-color-scheme: dark) { .sll-root { color: #F3F3F3;
              --sll-border: #474747; --sll-card: #232323; --sll-bg: #181818; --sll-blue: #0D9DDA;
              --sll-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sll-muted: #A5A5A5; --sll-red: #FE5C4C; } }
            .sll-row { display: flex; flex-wrap: wrap; gap: 1rem; }
            .sll-card { flex: 1 1 17rem; min-inline-size: 0; border: 1px solid var(--sll-border);
              border-radius: .25rem; background: var(--sll-card); display: grid; }
            .sll-head { display: flex; align-items: center; gap: .5rem; padding: .7rem .9rem; }
            .sll-tile { display: grid; place-items: center; inline-size: 1.5rem; block-size: 1.5rem;
              border-radius: .25rem; background: var(--sll-tint); color: #FFFFFF; font-size: .72rem; font-weight: 700; }
            .sll-head h3 { margin: 0; font-size: .95rem; font-weight: 700; }
            .sll-new { margin-inline-start: auto; }
            .sll-list { margin: 0; padding: 0; list-style: none; }
            .sll-item { display: flex; align-items: center; gap: .6rem; padding: .55rem .9rem; }
            .sll-item + .sll-item { border-block-start: 1px solid var(--sll-border); }
            .sll-item > div { min-inline-size: 0; flex: 1; }
            .sll-item b { display: block; font-size: .85rem; font-weight: 600; }
            .sll-item span { display: block; font-size: .76rem; color: var(--sll-muted); }
            .sll-rowmenu { position: relative; flex: none; }
            .sll-dots { border: 0; background: transparent; color: var(--sll-muted); cursor: pointer;
              padding: .1rem .35rem; border-radius: .25rem; font-weight: 700; }
            .sll-dots:hover { background: var(--sll-blue-soft); color: var(--sll-blue); }
            .sll-menu { position: absolute; z-index: 5; inset-inline-end: 0; inset-block-start: 1.5rem;
              min-inline-size: 8.5rem; padding: .2rem 0; border: 1px solid var(--sll-border); border-radius: .25rem;
              background: var(--sll-card); box-shadow: 0 2px 6px rgba(0, 0, 0, .14); }
            .sll-menu button { display: flex; inline-size: 100%; padding: .4rem .75rem; border: 0;
              background: transparent; color: inherit; font: inherit; font-size: .8rem; cursor: pointer; text-align: start; }
            .sll-menu button:hover { background: var(--sll-blue-soft); }
            .sll-menu [data-tone='danger'] { color: var(--sll-red); }
            .sll-foot { border-block-start: 1px solid var(--sll-border); padding: .5rem .9rem; }
            .sll-foot a { color: var(--sll-blue); font-size: .8rem; font-weight: 600; text-decoration: none; }
            .sll-file { display: flex; align-items: center; gap: .6rem; }
            .sll-file div { min-inline-size: 0; }
            .sll-btn { padding: .35rem .8rem; border: 1px solid var(--sll-border); border-radius: .25rem;
              background: var(--sll-card); color: var(--sll-blue); font: inherit; font-size: .78rem;
              font-weight: 600; cursor: pointer; }
            .sll-btn:hover { background: var(--sll-blue-soft); }
            </style>
            SVELTE,
        ],
    ],

    'docked-utility-bar' => [
        'title' => ['fa' => 'نوار ابزار چسبیده (Docked Utility Bar)', 'en' => 'Docked Utility Bar'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'نوار آیکنی چسبیده به پایین صفحه که با هر کلیک پنل کاربردی باز می‌کند؛ ابزار همیشگی کارشناس در لایتنینگ.',
            'en' => 'A footer of utility icons that pop open panels in place — the agent’s always-on toolbox docked to the page bottom.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/docked-utility-bar/',
        'props' => [
            ['name' => 'panel', 'type' => 'popover', 'default' => "'above'", 'note' => [
                'fa' => 'پنل بالای آیکن باز می‌شود، فقط یکی در هر لحظه و لمس بیرون یا همان آیکن می‌بنددش.',
                'en' => 'The panel opens above its icon, one at a time; an outside tap or the same icon closes it.',
            ]],
            ['name' => 'badge', 'type' => 'count', 'default' => 'null', 'note' => [
                'fa' => 'آیکن‌ها نشان شمار می‌گیرند؛ اعداد بالای ۹ فشرده به ۹+.',
                'en' => 'Icons carry count badges; anything past 9 clamps to 9+.',
            ]],
            ['name' => 'active', 'type' => 'state', 'default' => "'panel-open'", 'note' => [
                'fa' => 'آیکنِ پنل باز، نوار آبی برند زیرش می‌گیرد و تا بسته شدن روشن می‌ماند.',
                'en' => 'The open panel’s icon gains a brand-blue bar beneath and stays lit until closed.',
            ]],
            ['name' => 'items', 'type' => 'count', 'default' => "'4–8'", 'note' => [
                'fa' => 'چهار تا هشت ابزار؛ متجاوز در منوی بیشتر می‌نشیند.',
                'en' => 'Four to eight utilities; the overflow folds into a More menu.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <div class="sflx-util">
                <div class="sflx-util-panel" role="dialog" aria-label="گفت‌وگو">…</div>
                <nav class="sflx-util-bar">
                    <button aria-expanded="true" aria-label="گفت‌وگو"><x-nx::icon name="message" /><b>۳</b></button>
                    <button aria-expanded="false" aria-label="اعلان‌ها"><x-nx::icon name="bell" /><b>۹+</b></button>
                </nav>
            </div>

            <style>
            .sflx-util { position: relative; }
            .sflx-util-bar { display: flex; background: #0B5CAB; color: #fff; }
            .sflx-util-bar button { inline-size: 3rem; block-size: 2.75rem; position: relative; }
            .sflx-util-bar button[aria-expanded='true'] { box-shadow: inset 0 -3px 0 #fff; }
            .sflx-util-panel { position: absolute; inset-inline: 0; inset-block-end: 2.75rem;
                               block-size: 16rem; background: #fff; border: 1px solid #DDDBDA; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="sflx-util">
                <div class="sflx-util-panel" role="dialog" aria-label="Chat">…</div>
                <nav class="sflx-util-bar">
                    <button aria-expanded="true" aria-label="Chat"><x-nx::icon name="message" /><b>3</b></button>
                    <button aria-expanded="false" aria-label="Notifications"><x-nx::icon name="bell" /><b>9+</b></button>
                </nav>
            </div>

            <style>
            .sflx-util { position: relative; }
            .sflx-util-bar { display: flex; background: #0B5CAB; color: #fff; }
            .sflx-util-bar button { inline-size: 3rem; block-size: 2.75rem; position: relative; }
            .sflx-util-bar button[aria-expanded='true'] { box-shadow: inset 0 -3px 0 #fff; }
            .sflx-util-panel { position: absolute; inset-inline: 0; inset-block-end: 2.75rem;
                               block-size: 16rem; background: #fff; border: 1px solid #DDDBDA; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Console.tsx — an Inertia page (React flavor). Inertia
            // mounts this component for /console; the docked frame itself is the
            // UtilityBar component from the React snippet. Register the route with
            // Route::inertia('/console', 'Console') on the Laravel side.
            import UtilityBar from '@/components/UtilityBar';

            export default function Console() {
              return <UtilityBar />;
            }
            TSX,
            'react' => <<<'TSX'
            // UtilityBar.tsx — a Lightning console frame with the Docked Utility Bar on
            // its bottom edge: five utilities, one panel open at a time, chat sends.
            // The markup mirrors the playground partial (sld-* classes).
            import { type CSSProperties, useEffect, useRef, useState } from 'react';
            import '@nabuxai/ui-react/css';

            const PATHS = {
              grid: 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z',
              message: 'M4 5h16v11H9l-5 4z',
              bell: 'M6 16v-5a6 6 0 1 1 12 0v5l1.5 2h-15zM10 21h4',
              users: 'M9 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM2.5 20a6.5 6.5 0 0 1 13 0M16 4.2a3.5 3.5 0 0 1 0 6.6M18 14a6.5 6.5 0 0 1 3.5 6',
              folder: 'M3.5 7.5a2 2 0 0 1 2-2h3.8l2 2h7.2a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2z',
              mic: 'M12 15a3 3 0 0 0 3-3V6a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3zM19 11a7 7 0 0 1-14 0M12 18v3',
            };

            const I = ({ d }: { d: string }) => (
              <svg className="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                strokeLinecap="round" strokeLinejoin="round" aria-hidden="true"><path d={d} /></svg>
            );

            const CREW = [
              { name: 'Elif', initials: 'ED', tint: '#0176D3', online: true },
              { name: 'Lena', initials: 'LF', tint: '#6739B7', online: true },
              { name: 'Marco', initials: 'MB', tint: '#04844B', online: false },
            ];

            // Panel id -> [icon path, aria-label, panel heading].
            const PANELS: Record<string, [string, string, string]> = {
              chat: [PATHS.message, 'Chat with the deal team', 'Deal team chat'],
              bell: [PATHS.bell, 'Notifications', '9 unread'],
              users: [PATHS.users, 'Who is online', 'Deal team — 2 online'],
              files: [PATHS.folder, 'Files drawer', 'Recent files'],
              mic: [PATHS.mic, 'Voice note', 'Record a voice note'],
            };

            export default function UtilityBar() {
              const [open, setOpen] = useState<string | null>(null);
              const [sent, setSent] = useState<string[]>([]);
              const frame = useRef<HTMLDivElement>(null);
              const input = useRef<HTMLInputElement>(null);

              // One panel at a time; Escape or an outside click closes (was Alpine).
              useEffect(() => {
                const click = (e: MouseEvent) => { if (!frame.current?.contains(e.target as Node)) setOpen(null); };
                const esc = (e: KeyboardEvent) => { if (e.key === 'Escape') setOpen(null); };
                document.addEventListener('click', click);
                document.addEventListener('keydown', esc);
                return () => { document.removeEventListener('click', click); document.removeEventListener('keydown', esc); };
              }, []);

              const send = () => {
                const value = input.current?.value.trim();
                if (!value) return;
                setSent([...sent, value]);
                input.current!.value = '';
              };

              return (
                <div className="sld-root">
                  <div className="sld-frame" ref={frame}>
                    <div className="sld-top"><I d={PATHS.grid} /><b>Sales Console</b><span style={{ opacity: .8 }}>My open opportunities</span></div>
                    <div className="sld-body">
                      <div className="sld-rec"><div><b>Nabu annual license</b><small>Negotiation · €2.4M</small></div><span className="sld-badge-card">Hot</span></div>
                      <div className="sld-rec"><div><b>Anatolia plant rollout</b><small>Proposal · €980K</small></div><span className="sld-badge-card">On track</span></div>
                      <div className="sld-rec"><div><b>Aegean retail renewal</b><small>Qualification · €450K</small></div><span className="sld-badge-card">At risk</span></div>
                    </div>

                    {open && (
                      <div className="sld-panelzone">
                        <div className="sld-panel" role="dialog" aria-label={PANELS[open][1]}>
                          <div className="sld-panel-head">
                            <I d={PANELS[open][0]} />
                            {PANELS[open][2]}
                            <button type="button" onClick={() => setOpen(null)} aria-label="Close">✕</button>
                          </div>
                          <div className="sld-panel-body">
                            {open === 'chat' && (
                              <>
                                <p className="sld-note"><b>Elif</b><span>Spree asked for the revised quote today.</span></p>
                                <p className="sld-note"><b>Lena</b><span>Pricing sheet is with legal now.</span></p>
                                {sent.map((m, i) => <p key={i} className="sld-note"><b>You</b><span>{m}</span></p>)}
                                <div className="sld-typing">
                                  <input ref={input} type="text" placeholder="Message the team…"
                                    onKeyDown={(e) => e.key === 'Enter' && send()} />
                                  <button type="button" onClick={send}>Send</button>
                                </div>
                              </>
                            )}
                            {open === 'bell' && (
                              <>
                                <p className="sld-note"><b>Approval requested</b><span>€2.4M discount needs a manager sign-off.</span></p>
                                <p className="sld-note"><b>Spree replied</b><span>New comment on the quote — 2 min ago.</span></p>
                              </>
                            )}
                            {open === 'users' && CREW.map((p) => (
                              <div key={p.name} className="sld-crew">
                                <span className="sld-ava" style={{ '--sld-tint': p.tint } as CSSProperties}>{p.initials}</span>
                                <span>{p.name}</span>
                                <span className="sld-dot" style={!p.online ? { background: 'var(--sld-muted)' } : undefined} />
                              </div>
                            ))}
                            {open === 'files' && (
                              <>
                                <p className="sld-note"><b>Quote-2026.pdf</b><span>248 KB · shared with Spree</span></p>
                                <p className="sld-note"><b>Pricing sheet Q4.xlsx</b><span>92 KB · edited by Lena</span></p>
                              </>
                            )}
                            {open === 'mic' && (
                              <p style={{ margin: 0, color: 'var(--sld-muted)' }}>Notes attach straight to "Nabu annual license" and land in the Chatter feed.</p>
                            )}
                          </div>
                        </div>
                      </div>
                    )}

                    <nav className="sld-bar" aria-label="Utility bar">
                      {([
                        ['chat', PATHS.message, 'Chat, 3 unread', '3'],
                        ['bell', PATHS.bell, 'Notifications, 9 unread', '9'],
                        ['users', PATHS.users, 'Who is online', null],
                        ['files', PATHS.folder, 'Files', null],
                        ['mic', PATHS.mic, 'Voice note', null],
                      ] as const).map(([id, d, label, badge]) => (
                        <button key={id} type="button" className="sld-util"
                          aria-expanded={open === id} aria-label={label}
                          onClick={() => setOpen(open === id ? null : id)}>
                          <I d={d} />
                          {badge && <span className="sld-count">{badge}</span>}
                        </button>
                      ))}
                    </nav>
                  </div>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .sld-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sld-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sld-navy: #0B5CAB; --sld-green: #04844B;
              --sld-muted: #706E6B; --sld-border: #DDDBDA; --sld-bg: #F3F3F3; --sld-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sld-root { color: #F3F3F3;
              --sld-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sld-navy: #0B5CAB; --sld-green: #0E9E5B;
              --sld-muted: #A5A5A5; --sld-border: #474747; --sld-bg: #181818; --sld-card: #232323; } }
            .sld-frame { position: relative; max-inline-size: 46rem; margin-inline: auto; block-size: 27rem;
              display: flex; flex-direction: column; overflow: clip; border: 1px solid var(--sld-border);
              border-radius: .5rem; background: var(--sld-bg); }
            .sld-top { display: flex; align-items: center; gap: .6rem; padding: .5rem .8rem;
              background: var(--sld-navy); color: #FFFFFF; font-size: .82rem; }
            .sld-body { flex: 1; overflow-y: auto; padding: .8rem; display: grid; gap: .5rem; align-content: start; }
            .sld-rec { display: flex; align-items: center; gap: .6rem; padding: .55rem .7rem; border: 1px solid var(--sld-border);
              border-radius: .25rem; background: var(--sld-card); }
            .sld-rec small { display: block; color: var(--sld-muted); font-size: .74rem; }
            .sld-badge-card { margin-inline-start: auto; font-size: .74rem; font-weight: 700; color: var(--sld-green); }
            .sld-panelzone { position: absolute; inset-inline: 0; inset-block-end: 2.75rem; z-index: 5;
              display: flex; justify-content: center; padding: 0 .75rem; }
            .sld-panel { inline-size: min(100%, 24rem); max-block-size: 17rem; display: flex; flex-direction: column;
              border: 1px solid var(--sld-border); border-radius: .25rem .25rem 0 0; border-block-end: 0;
              background: var(--sld-card); box-shadow: 0 -4px 14px rgba(0, 0, 0, .12); overflow: hidden; }
            .sld-panel-head { display: flex; align-items: center; gap: .5rem; padding: .55rem .8rem;
              border-block-end: 1px solid var(--sld-border); background: var(--sld-blue-soft);
              font-size: .82rem; font-weight: 700; }
            .sld-panel-head button { margin-inline-start: auto; border: 0; background: transparent;
              color: var(--sld-muted); cursor: pointer; font-weight: 700; }
            .sld-panel-body { overflow-y: auto; padding: .6rem .8rem; font-size: .82rem; display: grid; gap: .55rem; align-content: start; }
            .sld-note { display: grid; gap: .1rem; margin: 0; padding-block-end: .55rem; border-block-end: 1px solid var(--sld-border); }
            .sld-note:last-of-type { border-block-end: 0; padding-block-end: 0; }
            .sld-note b { font-size: .8rem; }
            .sld-note span { color: var(--sld-muted); font-size: .76rem; }
            .sld-crew { display: flex; align-items: center; gap: .55rem; }
            .sld-ava { display: grid; place-items: center; inline-size: 1.75rem; block-size: 1.75rem; border-radius: 50%;
              background: var(--sld-tint, var(--sld-navy)); color: #FFFFFF; font-size: .64rem; font-weight: 700; }
            .sld-dot { margin-inline-start: auto; inline-size: .5rem; block-size: .5rem; border-radius: 50%; background: var(--sld-green); }
            .sld-typing { display: flex; gap: .4rem; }
            .sld-typing input { flex: 1; min-inline-size: 0; padding: .4rem .55rem; border: 1px solid var(--sld-border);
              border-radius: .25rem; background: var(--sld-card); color: inherit; font: inherit; font-size: .8rem; }
            .sld-typing button { border: 0; border-radius: .25rem; background: var(--sld-navy); color: #FFFFFF;
              cursor: pointer; padding-inline: .7rem; font: inherit; font-size: .8rem; font-weight: 600; }
            .sld-bar { display: flex; justify-content: center; gap: .15rem; block-size: 2.75rem;
              background: var(--sld-navy); color: #FFFFFF; }
            .sld-util { position: relative; display: grid; place-items: center; inline-size: 3.4rem; border: 0;
              background: transparent; color: #FFFFFF; cursor: pointer; }
            .sld-util::after { content: ''; position: absolute; inset-inline: 15%; inset-block-end: 0; block-size: 3px;
              background: transparent; }
            .sld-util[aria-expanded='true'] { background: color-mix(in srgb, #FFFFFF 10%, transparent); }
            .sld-util[aria-expanded='true']::after { background: #FFFFFF; }
            .sld-count { position: absolute; inset-block-start: .3rem; inset-inline-end: .45rem; min-inline-size: 1rem;
              padding-inline: .22rem; block-size: 1rem; display: grid; place-items: center; border-radius: .5rem;
              background: #EA001E; color: #FFFFFF; font-size: .6rem; font-weight: 700; box-shadow: 0 0 0 2px var(--sld-navy); }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- UtilityBar.vue — a Lightning console frame with the Docked Utility Bar
                 on its bottom edge: five utilities, one panel open at a time, chat
                 sends. Markup mirrors the playground partial (sld-* classes). -->
            <script setup lang="ts">
            import { onBeforeUnmount, onMounted, ref } from 'vue';

            const crew = [
              { name: 'Elif', initials: 'ED', tint: '#0176D3', online: true },
              { name: 'Lena', initials: 'LF', tint: '#6739B7', online: true },
              { name: 'Marco', initials: 'MB', tint: '#04844B', online: false },
            ];
            // Icon paths by utility id (the partial renders these via <x-nx::icon />).
            const icons: Record<string, string> = {
              grid: 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z',
              chat: 'M4 5h16v11H9l-5 4z',
              bell: 'M6 16v-5a6 6 0 1 1 12 0v5l1.5 2h-15zM10 21h4',
              users: 'M9 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM2.5 20a6.5 6.5 0 0 1 13 0M16 4.2a3.5 3.5 0 0 1 0 6.6M18 14a6.5 6.5 0 0 1 3.5 6',
              files: 'M3.5 7.5a2 2 0 0 1 2-2h3.8l2 2h7.2a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2z',
              mic: 'M12 15a3 3 0 0 0 3-3V6a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3zM19 11a7 7 0 0 1-14 0M12 18v3',
            };
            const records = [
              { name: 'Nabu annual license', meta: 'Negotiation · €2.4M', badge: 'Hot' },
              { name: 'Anatolia plant rollout', meta: 'Proposal · €980K', badge: 'On track' },
              { name: 'Aegean retail renewal', meta: 'Qualification · €450K', badge: 'At risk' },
            ];

            const open = ref<string | null>(null);
            const sent = ref<string[]>([]);
            const draft = ref('');
            const frame = ref<HTMLElement | null>(null);

            // One panel at a time; Escape or an outside click closes (was Alpine).
            const onClick = (e: MouseEvent) => { if (!frame.value?.contains(e.target as Node)) open.value = null; };
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') open.value = null; };
            onMounted(() => {
              document.addEventListener('click', onClick);
              document.addEventListener('keydown', onKey);
            });
            onBeforeUnmount(() => {
              document.removeEventListener('click', onClick);
              document.removeEventListener('keydown', onKey);
            });

            const send = () => {
              const value = draft.value.trim();
              if (!value) return;
              sent.value = [...sent.value, value];
              draft.value = '';
            };
            </script>

            <template>
              <div class="sld-root">
                  <div ref="frame" class="sld-frame">
                    <div class="sld-top">
                      <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true"><path :d="icons.grid" /></svg>
                      <b>Sales Console</b><span style="opacity: .8">My open opportunities</span>
                    </div>
                  <div class="sld-body">
                    <div v-for="r in records" :key="r.name" class="sld-rec">
                      <div><b>{{ r.name }}</b><small>{{ r.meta }}</small></div>
                      <span class="sld-badge-card">{{ r.badge }}</span>
                    </div>
                  </div>

                  <div v-if="open" class="sld-panelzone">
                    <div class="sld-panel" role="dialog" aria-label="Utility panel">
                      <div class="sld-panel-head">
                        {{ open === 'chat' ? 'Deal team chat' : open === 'bell' ? '9 unread'
                          : open === 'users' ? 'Deal team — 2 online'
                          : open === 'files' ? 'Recent files' : 'Record a voice note' }}
                        <button type="button" aria-label="Close" @click="open = null">✕</button>
                      </div>
                      <div class="sld-panel-body">
                        <template v-if="open === 'chat'">
                          <p class="sld-note"><b>Elif</b><span>Spree asked for the revised quote today.</span></p>
                          <p class="sld-note"><b>Lena</b><span>Pricing sheet is with legal now.</span></p>
                          <p v-for="(m, i) in sent" :key="i" class="sld-note"><b>You</b><span>{{ m }}</span></p>
                          <div class="sld-typing">
                            <input v-model="draft" type="text" placeholder="Message the team…" @keydown.enter="send" />
                            <button type="button" @click="send">Send</button>
                          </div>
                        </template>
                        <template v-else-if="open === 'bell'">
                          <p class="sld-note"><b>Approval requested</b><span>€2.4M discount needs a manager sign-off.</span></p>
                          <p class="sld-note"><b>Spree replied</b><span>New comment on the quote — 2 min ago.</span></p>
                        </template>
                        <template v-else-if="open === 'users'">
                          <div v-for="p in crew" :key="p.name" class="sld-crew">
                            <span class="sld-ava" :style="{ '--sld-tint': p.tint }">{{ p.initials }}</span>
                            <span>{{ p.name }}</span>
                            <span class="sld-dot" :style="p.online ? undefined : { background: 'var(--sld-muted)' }" />
                          </div>
                        </template>
                        <template v-else-if="open === 'files'">
                          <p class="sld-note"><b>Quote-2026.pdf</b><span>248 KB · shared with Spree</span></p>
                          <p class="sld-note"><b>Pricing sheet Q4.xlsx</b><span>92 KB · edited by Lena</span></p>
                        </template>
                        <p v-else style="margin: 0; color: var(--sld-muted)">
                          Notes attach straight to "Nabu annual license" and land in the Chatter feed.
                        </p>
                      </div>
                    </div>
                  </div>

                  <nav class="sld-bar" aria-label="Utility bar">
                    <button v-for="u in [
                      { id: 'chat', label: 'Chat, 3 unread', badge: '3' },
                      { id: 'bell', label: 'Notifications, 9 unread', badge: '9' },
                      { id: 'users', label: 'Who is online', badge: null },
                      { id: 'files', label: 'Files', badge: null },
                      { id: 'mic', label: 'Voice note', badge: null },
                    ]" :key="u.id" type="button" class="sld-util"
                      :aria-expanded="open === u.id" :aria-label="u.label"
                      @click="open = open === u.id ? null : u.id">
                      <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true"><path :d="icons[u.id]" /></svg>
                      <span v-if="u.badge" class="sld-count">{{ u.badge }}</span>
                    </button>
                  </nav>
                </div>
              </div>
            </template>

            <style scoped>
            .sld-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sld-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sld-navy: #0B5CAB; --sld-green: #04844B;
              --sld-muted: #706E6B; --sld-border: #DDDBDA; --sld-bg: #F3F3F3; --sld-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sld-root { color: #F3F3F3;
              --sld-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sld-navy: #0B5CAB; --sld-green: #0E9E5B;
              --sld-muted: #A5A5A5; --sld-border: #474747; --sld-bg: #181818; --sld-card: #232323; } }
            .sld-frame { position: relative; max-inline-size: 46rem; margin-inline: auto; block-size: 27rem;
              display: flex; flex-direction: column; overflow: clip; border: 1px solid var(--sld-border);
              border-radius: .5rem; background: var(--sld-bg); }
            .sld-top { display: flex; align-items: center; gap: .6rem; padding: .5rem .8rem;
              background: var(--sld-navy); color: #FFFFFF; font-size: .82rem; }
            .sld-top b { font-weight: 700; }
            .sld-body { flex: 1; overflow-y: auto; padding: .8rem; display: grid; gap: .5rem; align-content: start; }
            .sld-rec { display: flex; align-items: center; gap: .6rem; padding: .55rem .7rem;
              border: 1px solid var(--sld-border); border-radius: .25rem; background: var(--sld-card); }
            .sld-rec small { display: block; color: var(--sld-muted); font-size: .74rem; }
            .sld-badge-card { margin-inline-start: auto; font-size: .74rem; font-weight: 700; color: var(--sld-green); }
            .sld-panelzone { position: absolute; inset-inline: 0; inset-block-end: 2.75rem; z-index: 5;
              display: flex; justify-content: center; padding: 0 .75rem; }
            .sld-panel { inline-size: min(100%, 24rem); max-block-size: 17rem; display: flex; flex-direction: column;
              border: 1px solid var(--sld-border); border-radius: .25rem .25rem 0 0; border-block-end: 0;
              background: var(--sld-card); box-shadow: 0 -4px 14px rgba(0, 0, 0, .12); overflow: hidden; }
            .sld-panel-head { display: flex; align-items: center; gap: .5rem; padding: .55rem .8rem;
              border-block-end: 1px solid var(--sld-border); background: var(--sld-blue-soft);
              font-size: .82rem; font-weight: 700; }
            .sld-panel-head button { margin-inline-start: auto; border: 0; background: transparent;
              color: var(--sld-muted); cursor: pointer; font-weight: 700; }
            .sld-panel-body { overflow-y: auto; padding: .6rem .8rem; font-size: .82rem; display: grid; gap: .55rem; align-content: start; }
            .sld-note { display: grid; gap: .1rem; margin: 0; padding-block-end: .55rem; border-block-end: 1px solid var(--sld-border); }
            .sld-note:last-of-type { border-block-end: 0; padding-block-end: 0; }
            .sld-note b { font-size: .8rem; }
            .sld-note span { color: var(--sld-muted); font-size: .76rem; }
            .sld-crew { display: flex; align-items: center; gap: .55rem; }
            .sld-ava { display: grid; place-items: center; inline-size: 1.75rem; block-size: 1.75rem; border-radius: 50%;
              background: var(--sld-tint, var(--sld-navy)); color: #FFFFFF; font-size: .64rem; font-weight: 700; flex: none; }
            .sld-dot { margin-inline-start: auto; inline-size: .5rem; block-size: .5rem; border-radius: 50%; background: var(--sld-green); }
            .sld-typing { display: flex; gap: .4rem; }
            .sld-typing input { flex: 1; min-inline-size: 0; padding: .4rem .55rem; border: 1px solid var(--sld-border);
              border-radius: .25rem; background: var(--sld-card); color: inherit; font: inherit; font-size: .8rem; }
            .sld-typing button { border: 0; border-radius: .25rem; background: var(--sld-navy); color: #FFFFFF;
              cursor: pointer; padding-inline: .7rem; font: inherit; font-size: .8rem; font-weight: 600; }
            .sld-bar { display: flex; justify-content: center; gap: .15rem; block-size: 2.75rem;
              background: var(--sld-navy); color: #FFFFFF; }
            .sld-util { position: relative; display: grid; place-items: center; inline-size: 3.4rem; border: 0;
              background: transparent; color: #FFFFFF; cursor: pointer; font-size: 1.05rem; }
            .sld-util::after { content: ''; position: absolute; inset-inline: 15%; inset-block-end: 0; block-size: 3px;
              background: transparent; }
            .sld-util[aria-expanded='true'] { background: color-mix(in srgb, #FFFFFF 10%, transparent); }
            .sld-util[aria-expanded='true']::after { background: #FFFFFF; }
            .sld-count { position: absolute; inset-block-start: .3rem; inset-inline-end: .45rem; min-inline-size: 1rem;
              padding-inline: .22rem; block-size: 1rem; display: grid; place-items: center; border-radius: .5rem;
              background: #EA001E; color: #FFFFFF; font-size: .6rem; font-weight: 700; box-shadow: 0 0 0 2px var(--sld-navy); }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- UtilityBar.svelte — a Lightning console frame with the Docked Utility
                 Bar on its bottom edge: five utilities, one panel open at a time, chat
                 sends. Markup mirrors the playground partial (sld-* classes). -->
            <script lang="ts">
              const crew = [
                { name: 'Elif', initials: 'ED', tint: '#0176D3', online: true },
                { name: 'Lena', initials: 'LF', tint: '#6739B7', online: true },
                { name: 'Marco', initials: 'MB', tint: '#04844B', online: false },
              ];
              const records = [
                { name: 'Nabu annual license', meta: 'Negotiation · €2.4M', badge: 'Hot' },
                { name: 'Anatolia plant rollout', meta: 'Proposal · €980K', badge: 'On track' },
                { name: 'Aegean retail renewal', meta: 'Qualification · €450K', badge: 'At risk' },
              ];
              // id -> [icon path, panel heading]
              const utilities = [
                { id: 'chat', label: 'Chat, 3 unread', badge: '3', d: 'M4 5h16v11H9l-5 4z', heading: 'Deal team chat' },
                { id: 'bell', label: 'Notifications, 9 unread', badge: '9', d: 'M6 16v-5a6 6 0 1 1 12 0v5l1.5 2h-15zM10 21h4', heading: '9 unread' },
                { id: 'users', label: 'Who is online', badge: null, d: 'M9 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM2.5 20a6.5 6.5 0 0 1 13 0M16 4.2a3.5 3.5 0 0 1 0 6.6M18 14a6.5 6.5 0 0 1 3.5 6', heading: 'Deal team — 2 online' },
                { id: 'files', label: 'Files', badge: null, d: 'M3.5 7.5a2 2 0 0 1 2-2h3.8l2 2h7.2a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2z', heading: 'Recent files' },
                { id: 'mic', label: 'Voice note', badge: null, d: 'M12 15a3 3 0 0 0 3-3V6a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3zM19 11a7 7 0 0 1-14 0M12 18v3', heading: 'Record a voice note' },
              ];

              let open = $state<string | null>(null);
              let sent = $state<string[]>([]);
              let draft = $state('');
              let frame: HTMLElement;

              const send = () => {
                const value = draft.trim();
                if (!value) return;
                sent = [...sent, value];
                draft = '';
              };
              const dismiss = (e: MouseEvent) => { if (!frame.contains(e.target as Node)) open = null; };
            </script>

            <svelte:document onclick={dismiss} onkeydown={(e) => e.key === 'Escape' && (open = null)} />

            <div class="sld-root">
              <div bind:this={frame} class="sld-frame">
                <div class="sld-top"><b>Sales Console</b><span style="opacity: .8">My open opportunities</span></div>
                <div class="sld-body">
                  {#each records as r (r.name)}
                    <div class="sld-rec">
                      <div><b>{r.name}</b><small>{r.meta}</small></div>
                      <span class="sld-badge-card">{r.badge}</span>
                    </div>
                  {/each}
                </div>

                {#if open}
                  <div class="sld-panelzone">
                    <div class="sld-panel" role="dialog" aria-label="Utility panel">
                      <div class="sld-panel-head">
                        {utilities.find((u) => u.id === open)?.heading}
                        <button type="button" aria-label="Close" onclick={() => (open = null)}>✕</button>
                      </div>
                      <div class="sld-panel-body">
                        {#if open === 'chat'}
                          <p class="sld-note"><b>Elif</b><span>Spree asked for the revised quote today.</span></p>
                          <p class="sld-note"><b>Lena</b><span>Pricing sheet is with legal now.</span></p>
                          {#each sent as m, i (i)}
                            <p class="sld-note"><b>You</b><span>{m}</span></p>
                          {/each}
                          <div class="sld-typing">
                            <input bind:value={draft} type="text" placeholder="Message the team…"
                              onkeydown={(e) => e.key === 'Enter' && send()} />
                            <button type="button" onclick={send}>Send</button>
                          </div>
                        {:else if open === 'bell'}
                          <p class="sld-note"><b>Approval requested</b><span>€2.4M discount needs a manager sign-off.</span></p>
                          <p class="sld-note"><b>Spree replied</b><span>New comment on the quote — 2 min ago.</span></p>
                        {:else if open === 'users'}
                          {#each crew as p (p.name)}
                            <div class="sld-crew">
                              <span class="sld-ava" style:--sld-tint={p.tint}>{p.initials}</span>
                              <span>{p.name}</span>
                              <span class="sld-dot" style:background={p.online ? undefined : 'var(--sld-muted)'} />
                            </div>
                          {/each}
                        {:else if open === 'files'}
                          <p class="sld-note"><b>Quote-2026.pdf</b><span>248 KB · shared with Spree</span></p>
                          <p class="sld-note"><b>Pricing sheet Q4.xlsx</b><span>92 KB · edited by Lena</span></p>
                        {:else}
                          <p style="margin: 0; color: var(--sld-muted)">
                            Notes attach straight to "Nabu annual license" and land in the Chatter feed.
                          </p>
                        {/if}
                      </div>
                    </div>
                  </div>
                {/if}

                <nav class="sld-bar" aria-label="Utility bar">
                  {#each utilities as u (u.id)}
                    <button type="button" class="sld-util" aria-expanded={open === u.id} aria-label={u.label}
                      onclick={() => (open = open === u.id ? null : u.id)}>
                      <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true"><path d={u.d} /></svg>
                      {#if u.badge}<span class="sld-count">{u.badge}</span>{/if}
                    </button>
                  {/each}
                </nav>
              </div>
            </div>

            <style>
            .sld-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sld-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sld-navy: #0B5CAB; --sld-green: #04844B;
              --sld-muted: #706E6B; --sld-border: #DDDBDA; --sld-bg: #F3F3F3; --sld-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sld-root { color: #F3F3F3;
              --sld-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sld-navy: #0B5CAB; --sld-green: #0E9E5B;
              --sld-muted: #A5A5A5; --sld-border: #474747; --sld-bg: #181818; --sld-card: #232323; } }
            .sld-frame { position: relative; max-inline-size: 46rem; margin-inline: auto; block-size: 27rem;
              display: flex; flex-direction: column; overflow: clip; border: 1px solid var(--sld-border);
              border-radius: .5rem; background: var(--sld-bg); }
            .sld-top { display: flex; align-items: center; gap: .6rem; padding: .5rem .8rem;
              background: var(--sld-navy); color: #FFFFFF; font-size: .82rem; }
            .sld-body { flex: 1; overflow-y: auto; padding: .8rem; display: grid; gap: .5rem; align-content: start; }
            .sld-rec { display: flex; align-items: center; gap: .6rem; padding: .55rem .7rem;
              border: 1px solid var(--sld-border); border-radius: .25rem; background: var(--sld-card); }
            .sld-rec small { display: block; color: var(--sld-muted); font-size: .74rem; }
            .sld-badge-card { margin-inline-start: auto; font-size: .74rem; font-weight: 700; color: var(--sld-green); }
            .sld-panelzone { position: absolute; inset-inline: 0; inset-block-end: 2.75rem; z-index: 5;
              display: flex; justify-content: center; padding: 0 .75rem; }
            .sld-panel { inline-size: min(100%, 24rem); max-block-size: 17rem; display: flex; flex-direction: column;
              border: 1px solid var(--sld-border); border-radius: .25rem .25rem 0 0; border-block-end: 0;
              background: var(--sld-card); box-shadow: 0 -4px 14px rgba(0, 0, 0, .12); overflow: hidden; }
            .sld-panel-head { display: flex; align-items: center; gap: .5rem; padding: .55rem .8rem;
              border-block-end: 1px solid var(--sld-border); background: var(--sld-blue-soft);
              font-size: .82rem; font-weight: 700; }
            .sld-panel-head button { margin-inline-start: auto; border: 0; background: transparent;
              color: var(--sld-muted); cursor: pointer; font-weight: 700; }
            .sld-panel-body { overflow-y: auto; padding: .6rem .8rem; font-size: .82rem; display: grid; gap: .55rem; align-content: start; }
            .sld-note { display: grid; gap: .1rem; margin: 0; padding-block-end: .55rem; border-block-end: 1px solid var(--sld-border); }
            .sld-note b { font-size: .8rem; }
            .sld-note span { color: var(--sld-muted); font-size: .76rem; }
            .sld-crew { display: flex; align-items: center; gap: .55rem; }
            .sld-ava { display: grid; place-items: center; inline-size: 1.75rem; block-size: 1.75rem; border-radius: 50%;
              background: var(--sld-tint, var(--sld-navy)); color: #FFFFFF; font-size: .64rem; font-weight: 700; flex: none; }
            .sld-dot { margin-inline-start: auto; inline-size: .5rem; block-size: .5rem; border-radius: 50%; background: var(--sld-green); }
            .sld-typing { display: flex; gap: .4rem; }
            .sld-typing input { flex: 1; min-inline-size: 0; padding: .4rem .55rem; border: 1px solid var(--sld-border);
              border-radius: .25rem; background: var(--sld-card); color: inherit; font: inherit; font-size: .8rem; }
            .sld-typing button { border: 0; border-radius: .25rem; background: var(--sld-navy); color: #FFFFFF;
              cursor: pointer; padding-inline: .7rem; font: inherit; font-size: .8rem; font-weight: 600; }
            .sld-bar { display: flex; justify-content: center; gap: .15rem; block-size: 2.75rem;
              background: var(--sld-navy); color: #FFFFFF; }
            .sld-util { position: relative; display: grid; place-items: center; inline-size: 3.4rem; border: 0;
              background: transparent; color: #FFFFFF; cursor: pointer; }
            .sld-util::after { content: ''; position: absolute; inset-inline: 15%; inset-block-end: 0; block-size: 3px;
              background: transparent; }
            .sld-util[aria-expanded='true'] { background: color-mix(in srgb, #FFFFFF 10%, transparent); }
            .sld-util[aria-expanded='true']::after { background: #FFFFFF; }
            .sld-count { position: absolute; inset-block-start: .3rem; inset-inline-end: .45rem; min-inline-size: 1rem;
              padding-inline: .22rem; block-size: 1rem; display: grid; place-items: center; border-radius: .5rem;
              background: #EA001E; color: #FFFFFF; font-size: .6rem; font-weight: 700; box-shadow: 0 0 0 2px var(--sld-navy); }
            </style>
            SVELTE,
        ],
    ],

    'welcome-mat' => [
        'title' => ['fa' => 'مات خوش‌آمد (Welcome Mat)', 'en' => 'Welcome Mat'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'آنبوردینگ چک‌لیستی با تصویر راهنما و تیک‌های پیشرفت؛ اولین برخورد کاربر با یک اپ تازه‌نصب لایتنینگ.',
            'en' => 'A checklist-onboarding card with an illustration and progress ticks — the first hello of every new Lightning app.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/welcome-mat/',
        'props' => [
            ['name' => 'split', 'type' => 'layout', 'default' => "'info · steps'", 'note' => [
                'fa' => 'ستون تصویر و معرفی یک‌سو، ستون چک‌لیست وظایف سوی دیگر؛ در موبایل زیر هم.',
                'en' => 'An intro/illustration column beside a column of checklist steps; stacked on mobile.',
            ]],
            ['name' => 'progress', 'type' => 'counter', 'default' => "'n of m'", 'note' => [
                'fa' => 'تیک هر وظیفه شمارنده را جلو می‌برد: «۲ از ۴ تکمیل شد».',
                'en' => 'Each tick advances the counter: «2 of 4 completed».',
            ]],
            ['name' => 'complete', 'type' => 'state', 'default' => "'done'", 'note' => [
                'fa' => 'با تکمیل همه، مات حالت سبز «همه‌چیز آماده» می‌گیرد.',
                'en' => 'Complete every step and the mat flips to the green all-set state.',
            ]],
            ['name' => 'dismiss', 'type' => 'event', 'default' => "'x'", 'note' => [
                'fa' => 'دکمهٔ ضربدر بالای کارت مات را برای همیشه می‌بندد.',
                'en' => 'The ✕ on the card dismisses the mat for good.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <section class="sflx-mat">
                <button class="sflx-mat-x" aria-label="بستن">✕</button>
                <figure class="sflx-mat-info">
                    <span class="sflx-mat-art"></span>
                    <figcaption>به «میز فروش نابو» خوش آمدید</figcaption>
                </figure>
                <ol class="sflx-mat-steps">
                    <li><button aria-pressed="true">✓ پروفایل را کامل کنید</button></li>
                    <li><button aria-pressed="false">اولین فرصت را بسازید</button></li>
                </ol>
            </section>

            <style>
            .sflx-mat { position: relative; display: grid; grid-template-columns: 1fr 1fr;
                        border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
            .sflx-mat-info { background: linear-gradient(135deg, #0B5CAB, #0176D3); color: #fff; }
            .sflx-mat-steps li button { inline-size: 100%; text-align: start; padding: .75rem 1rem; }
            .sflx-mat-steps li[aria-pressed='true'] button { color: #04844B; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <section class="sflx-mat">
                <button class="sflx-mat-x" aria-label="Dismiss">✕</button>
                <figure class="sflx-mat-info">
                    <span class="sflx-mat-art"></span>
                    <figcaption>Welcome to Nabu Sales</figcaption>
                </figure>
                <ol class="sflx-mat-steps">
                    <li><button aria-pressed="true">✓ Complete your profile</button></li>
                    <li><button aria-pressed="false">Create your first opportunity</button></li>
                </ol>
            </section>

            <style>
            .sflx-mat { position: relative; display: grid; grid-template-columns: 1fr 1fr;
                        border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
            .sflx-mat-info { background: linear-gradient(135deg, #0B5CAB, #0176D3); color: #fff; }
            .sflx-mat-steps li button { inline-size: 100%; text-align: start; padding: .75rem 1rem; }
            .sflx-mat-steps li[aria-pressed='true'] button { color: #04844B; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Onboarding.tsx — an Inertia page (React flavor).
            // Inertia mounts this component for /onboarding (e.g. after registration);
            // the mat itself is the WelcomeMat component from the React snippet. Register
            // the route with Route::inertia('/onboarding', 'Onboarding') on the backend.
            import WelcomeMat from '@/components/WelcomeMat';

            export default function Onboarding() {
              return <WelcomeMat />;
            }
            TSX,
            'react' => <<<'TSX'
            // WelcomeMat.tsx — the Lightning Welcome Mat: an illustrated info split
            // beside a four-step checklist with progress meter, the green all-set state
            // and the dismissable card. The markup mirrors the playground partial.
            import { type CSSProperties, useState } from 'react';
            import '@nabuxai/ui-react/css';

            const STEPS = [
              { label: 'Complete your company profile', hint: 'Logo, size and industry' },
              { label: 'Invite your teammates', hint: 'At least two sales folks' },
              { label: 'Create your first opportunity', hint: 'e.g. a customer annual license' },
              { label: 'Try the mobile app', hint: 'Same data, your pocket' },
            ];

            const CHECK = 'M4.5 12.5l5 5L19.5 6.5';
            const PLAY = 'M7 5l12 7-12 7z';

            export default function WelcomeMat() {
              const [gone, setGone] = useState(false);
              const [done, setDone] = useState([true, true, false, false]);
              const count = done.filter(Boolean).length;
              const tick = (i: number) => setDone(done.map((d, j) => (j === i ? !d : d)));

              if (gone) {
                return (
                  <div className="slw-root">
                    <div className="slw-gone">
                      <span>The mat is dismissed for good.</span>
                      <button type="button" onClick={() => setGone(false)}>Show it again</button>
                    </div>
                    <style>{css}</style>
                  </div>
                );
              }

              return (
                <div className="slw-root">
                  <section className="slw-card" aria-label="Welcome mat">
                    <button type="button" className="slw-close" aria-label="Dismiss the welcome mat"
                      onClick={() => setGone(true)}>✕</button>
                    <figure className="slw-info">
                      <span className="slw-art" aria-hidden="true"><i /><i /><i /></span>
                      <h3>Welcome to Nabu Sales</h3>
                      <p>Four small steps and your team is selling — the tour takes about three minutes.</p>
                      <button type="button" className="slw-play">
                        <svg className="nx-icon" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true"><path d={PLAY} /></svg>
                        Watch the tour
                      </button>
                    </figure>
                    <div>
                      <ol className="slw-steps">
                        {STEPS.map((s, i) => (
                          <li key={s.label}>
                            <button type="button" className="slw-check" aria-pressed={done[i]}
                              aria-label={(done[i] ? 'Done: ' : 'To do: ') + s.label} onClick={() => tick(i)}>
                              <svg className="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                strokeLinecap="round" strokeLinejoin="round" aria-hidden="true"><path d={CHECK} /></svg>
                            </button>
                            <button type="button" className="slw-step-text" onClick={() => tick(i)}>
                              <span>{s.label}</span>
                              <small>{s.hint}</small>
                            </button>
                            <span className="slw-step-num" aria-hidden="true">{i + 1}</span>
                          </li>
                        ))}
                      </ol>
                      <div className="slw-progress">
                        <div className="slw-meter" role="progressbar" aria-valuenow={count} aria-valuemin={0}
                          aria-valuemax={4} style={{ '--slw-done': count } as CSSProperties}><i /></div>
                        <b>{count} of 4 completed</b>
                      </div>
                      {count === STEPS.length && <p className="slw-set">✓ All set — happy selling!</p>}
                    </div>
                  </section>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .slw-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slw-green: #04844B; --slw-muted: #706E6B; --slw-border: #DDDBDA; --slw-card: #FFFFFF; --slw-blue: #0176D3; }
            @media (prefers-color-scheme: dark) { .slw-root { color: #F3F3F3;
              --slw-green: #0E9E5B; --slw-muted: #A5A5A5; --slw-border: #474747; --slw-card: #232323; --slw-blue: #0D9DDA; } }
            .slw-card { position: relative; max-inline-size: 46rem; margin-inline: auto; display: grid;
              grid-template-columns: minmax(11rem, 1fr) minmax(0, 1.2fr); border: 1px solid var(--slw-border);
              border-radius: .25rem; background: var(--slw-card); overflow: clip; }
            @media (max-width: 540px) { .slw-card { grid-template-columns: 1fr; } }
            .slw-close { position: absolute; inset-block-start: .5rem; inset-inline-end: .5rem; z-index: 2;
              inline-size: 1.75rem; aspect-ratio: 1; display: grid; place-items: center; border: 0; border-radius: .25rem;
              background: transparent; color: var(--slw-muted); cursor: pointer; font-size: .95rem; }
            .slw-info { position: relative; display: grid; align-content: end; gap: .4rem; min-block-size: 15rem;
              margin: 0; padding: 1.25rem 1.1rem;
              background: linear-gradient(160deg, #0B5CAB, #0176D3 62%, #0E9E5B 130%); color: #FFFFFF; }
            .slw-art { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%; pointer-events: none; }
            .slw-art i { position: absolute; border-radius: 50%; background: color-mix(in srgb, #FFFFFF 16%, transparent); }
            .slw-art i:nth-child(1) { inline-size: 5.5rem; aspect-ratio: 1; inset-block-start: 14%; inset-inline-start: 12%; }
            .slw-art i:nth-child(2) { inline-size: 2.2rem; aspect-ratio: 1; inset-block-start: 48%; inset-inline-start: 58%; opacity: .7; }
            .slw-art i:nth-child(3) { inline-size: 3.4rem; aspect-ratio: 1; inset-block-start: 8%; inset-inline-start: 66%; opacity: .5; }
            .slw-info h3 { position: relative; margin: 0; font-size: 1.15rem; font-weight: 700; }
            .slw-info p { position: relative; margin: 0; font-size: .84rem; opacity: .92; }
            .slw-play { position: relative; justify-self: start; display: inline-flex; align-items: center; gap: .45rem;
              margin-block-start: .5rem; padding: .45rem .95rem; border: 1px solid color-mix(in srgb, #FFFFFF 55%, transparent);
              border-radius: .25rem; background: color-mix(in srgb, #FFFFFF 16%, transparent); color: #FFFFFF;
              font: inherit; font-size: .82rem; font-weight: 600; cursor: pointer; }
            .slw-steps { display: grid; gap: 0; align-content: start; margin: 0; padding: .35rem .9rem .9rem; list-style: none; }
            .slw-steps li { display: flex; align-items: center; gap: .75rem; padding: .15rem 0; }
            .slw-check { position: relative; flex: none; inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50%;
              border: 1px solid var(--slw-border); background: var(--slw-card); color: transparent;
              display: grid; place-items: center; cursor: pointer; }
            .slw-check .nx-icon { inline-size: .8em; block-size: .8em; }
            .slw-check[aria-pressed='true'] { background: var(--slw-green); border-color: var(--slw-green); color: #FFFFFF; }
            .slw-step-text { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: inherit;
              font: inherit; font-size: .85rem; font-weight: 600; cursor: pointer; text-align: start; padding: .35rem 0; }
            .slw-step-text small { display: block; font-weight: 400; font-size: .74rem; color: var(--slw-muted); }
            .slw-step-num { flex: none; font-size: .72rem; font-weight: 700; color: var(--slw-muted); }
            .slw-progress { display: flex; align-items: center; gap: .6rem; padding: .6rem .9rem .8rem; }
            .slw-meter { flex: 1; block-size: 4px; border-radius: 999px; background: var(--slw-border); overflow: clip; }
            .slw-meter i { display: block; block-size: 100%; border-radius: inherit; background: var(--slw-green);
              inline-size: calc(var(--slw-done, 0) / 4 * 100%); transition: inline-size .25s ease; }
            .slw-progress b { font-size: .78rem; color: var(--slw-muted); white-space: nowrap; }
            .slw-set { display: flex; align-items: center; gap: .5rem; margin: 0 .9rem .9rem; padding: .55rem .75rem;
              border-radius: .25rem; background: color-mix(in srgb, var(--slw-green) 12%, var(--slw-card));
              color: var(--slw-green); font-size: .8rem; font-weight: 700; }
            .slw-gone { display: grid; place-items: center; gap: .5rem; max-inline-size: 46rem; margin-inline: auto;
              min-block-size: 9rem; border: 1px dashed var(--slw-border); border-radius: .25rem;
              color: var(--slw-muted); font-size: .85rem; text-align: center; padding: 1rem; }
            .slw-gone button { border: 0; background: transparent; color: var(--slw-blue); font: inherit;
              font-size: .82rem; font-weight: 600; cursor: pointer; text-decoration: underline; }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- WelcomeMat.vue — the Lightning Welcome Mat: an illustrated info split
                 beside a four-step checklist with progress meter, the green all-set
                 state and the dismissable card. Mirrors the playground partial. -->
            <script setup lang="ts">
            import { computed, ref } from 'vue';

            const steps = [
              { label: 'Complete your company profile', hint: 'Logo, size and industry' },
              { label: 'Invite your teammates', hint: 'At least two sales folks' },
              { label: 'Create your first opportunity', hint: 'e.g. a customer annual license' },
              { label: 'Try the mobile app', hint: 'Same data, your pocket' },
            ];

            const gone = ref(false);
            const done = ref([true, true, false, false]);
            const count = computed(() => done.value.filter(Boolean).length);
            const tick = (i: number) => { done.value = done.value.map((d, j) => (j === i ? !d : d)); };
            </script>

            <template>
              <div class="slw-root">
                <section v-if="!gone" class="slw-card" aria-label="Welcome mat">
                  <button type="button" class="slw-close" aria-label="Dismiss the welcome mat" @click="gone = true">✕</button>
                  <figure class="slw-info">
                    <span class="slw-art" aria-hidden="true"><i /><i /><i /></span>
                    <h3>Welcome to Nabu Sales</h3>
                    <p>Four small steps and your team is selling — the tour takes about three minutes.</p>
                    <button type="button" class="slw-play">Watch the tour ▸</button>
                  </figure>
                  <div>
                    <ol class="slw-steps">
                      <li v-for="(s, i) in steps" :key="s.label">
                        <button type="button" class="slw-check" :aria-pressed="done[i]"
                          :aria-label="(done[i] ? 'Done: ' : 'To do: ') + s.label" @click="tick(i)">✓</button>
                        <button type="button" class="slw-step-text" @click="tick(i)">
                          <span>{{ s.label }}</span>
                          <small>{{ s.hint }}</small>
                        </button>
                        <span class="slw-step-num" aria-hidden="true">{{ i + 1 }}</span>
                      </li>
                    </ol>
                    <div class="slw-progress">
                      <div class="slw-meter" role="progressbar" :aria-valuenow="count" aria-valuemin="0" aria-valuemax="4"
                        :style="{ '--slw-done': count }"><i /></div>
                      <b>{{ count }} of 4 completed</b>
                    </div>
                    <p v-if="count === steps.length" class="slw-set">✓ All set — happy selling!</p>
                  </div>
                </section>

                <div v-else class="slw-gone">
                  <span>The mat is dismissed for good.</span>
                  <button type="button" @click="gone = false">Show it again</button>
                </div>
              </div>
            </template>

            <style scoped>
            .slw-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slw-green: #04844B; --slw-muted: #706E6B; --slw-border: #DDDBDA; --slw-card: #FFFFFF; --slw-blue: #0176D3; }
            @media (prefers-color-scheme: dark) { .slw-root { color: #F3F3F3;
              --slw-green: #0E9E5B; --slw-muted: #A5A5A5; --slw-border: #474747; --slw-card: #232323; --slw-blue: #0D9DDA; } }
            .slw-card { position: relative; max-inline-size: 46rem; margin-inline: auto; display: grid;
              grid-template-columns: minmax(11rem, 1fr) minmax(0, 1.2fr); border: 1px solid var(--slw-border);
              border-radius: .25rem; background: var(--slw-card); overflow: clip; }
            @media (max-width: 540px) { .slw-card { grid-template-columns: 1fr; } }
            .slw-close { position: absolute; inset-block-start: .5rem; inset-inline-end: .5rem; z-index: 2;
              inline-size: 1.75rem; aspect-ratio: 1; display: grid; place-items: center; border: 0; border-radius: .25rem;
              background: transparent; color: var(--slw-muted); cursor: pointer; font-size: .95rem; }
            .slw-info { position: relative; display: grid; align-content: end; gap: .4rem; min-block-size: 15rem;
              margin: 0; padding: 1.25rem 1.1rem;
              background: linear-gradient(160deg, #0B5CAB, #0176D3 62%, #0E9E5B 130%); color: #FFFFFF; }
            .slw-art { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%; pointer-events: none; }
            .slw-art i { position: absolute; border-radius: 50%; background: color-mix(in srgb, #FFFFFF 16%, transparent); }
            .slw-art i:nth-child(1) { inline-size: 5.5rem; aspect-ratio: 1; inset-block-start: 14%; inset-inline-start: 12%; }
            .slw-art i:nth-child(2) { inline-size: 2.2rem; aspect-ratio: 1; inset-block-start: 48%; inset-inline-start: 58%; opacity: .7; }
            .slw-art i:nth-child(3) { inline-size: 3.4rem; aspect-ratio: 1; inset-block-start: 8%; inset-inline-start: 66%; opacity: .5; }
            .slw-info h3 { position: relative; margin: 0; font-size: 1.15rem; font-weight: 700; }
            .slw-info p { position: relative; margin: 0; font-size: .84rem; opacity: .92; }
            .slw-play { position: relative; justify-self: start; margin-block-start: .5rem; padding: .45rem .95rem;
              border: 1px solid color-mix(in srgb, #FFFFFF 55%, transparent); border-radius: .25rem;
              background: color-mix(in srgb, #FFFFFF 16%, transparent); color: #FFFFFF;
              font: inherit; font-size: .82rem; font-weight: 600; cursor: pointer; }
            .slw-steps { display: grid; align-content: start; margin: 0; padding: .35rem .9rem .9rem; list-style: none; }
            .slw-steps li { display: flex; align-items: center; gap: .75rem; padding: .15rem 0; }
            .slw-check { flex: none; inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50%;
              border: 1px solid var(--slw-border); background: var(--slw-card); color: transparent;
              display: grid; place-items: center; cursor: pointer; }
            .slw-check[aria-pressed='true'] { background: var(--slw-green); border-color: var(--slw-green); color: #FFFFFF; }
            .slw-step-text { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: inherit;
              font: inherit; font-size: .85rem; font-weight: 600; cursor: pointer; text-align: start; padding: .35rem 0; }
            .slw-step-text small { display: block; font-weight: 400; font-size: .74rem; color: var(--slw-muted); }
            .slw-step-num { flex: none; font-size: .72rem; font-weight: 700; color: var(--slw-muted); }
            .slw-progress { display: flex; align-items: center; gap: .6rem; padding: .6rem .9rem .8rem; }
            .slw-meter { flex: 1; block-size: 4px; border-radius: 999px; background: var(--slw-border); overflow: clip; }
            .slw-meter i { display: block; block-size: 100%; border-radius: inherit; background: var(--slw-green);
              inline-size: calc(var(--slw-done, 0) / 4 * 100%); transition: inline-size .25s ease; }
            .slw-progress b { font-size: .78rem; color: var(--slw-muted); white-space: nowrap; }
            .slw-set { display: flex; align-items: center; gap: .5rem; margin: 0 .9rem .9rem; padding: .55rem .75rem;
              border-radius: .25rem; background: color-mix(in srgb, var(--slw-green) 12%, var(--slw-card));
              color: var(--slw-green); font-size: .8rem; font-weight: 700; }
            .slw-gone { display: grid; place-items: center; gap: .5rem; max-inline-size: 46rem; margin-inline: auto;
              min-block-size: 9rem; border: 1px dashed var(--slw-border); border-radius: .25rem;
              color: var(--slw-muted); font-size: .85rem; text-align: center; padding: 1rem; }
            .slw-gone button { border: 0; background: transparent; color: var(--slw-blue); font: inherit;
              font-size: .82rem; font-weight: 600; cursor: pointer; text-decoration: underline; }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- WelcomeMat.svelte — the Lightning Welcome Mat: an illustrated info
                 split beside a four-step checklist with progress meter, the green
                 all-set state and the dismissable card. Mirrors the playground partial. -->
            <script lang="ts">
              const steps = [
                { label: 'Complete your company profile', hint: 'Logo, size and industry' },
                { label: 'Invite your teammates', hint: 'At least two sales folks' },
                { label: 'Create your first opportunity', hint: 'e.g. a customer annual license' },
                { label: 'Try the mobile app', hint: 'Same data, your pocket' },
              ];

              let gone = $state(false);
              let done = $state([true, true, false, false]);

              const count = $derived(done.filter(Boolean).length);
              const tick = (i: number) => { done = done.map((d, j) => (j === i ? !d : d)); };
            </script>

            <div class="slw-root">
              {#if !gone}
                <section class="slw-card" aria-label="Welcome mat">
                  <button type="button" class="slw-close" aria-label="Dismiss the welcome mat"
                    onclick={() => (gone = true)}>✕</button>
                  <figure class="slw-info">
                    <span class="slw-art" aria-hidden="true"><i></i><i></i><i></i></span>
                    <h3>Welcome to Nabu Sales</h3>
                    <p>Four small steps and your team is selling — the tour takes about three minutes.</p>
                    <button type="button" class="slw-play">Watch the tour ▸</button>
                  </figure>
                  <div>
                    <ol class="slw-steps">
                      {#each steps as s, i (s.label)}
                        <li>
                          <button type="button" class="slw-check" aria-pressed={done[i]}
                            aria-label={(done[i] ? 'Done: ' : 'To do: ') + s.label} onclick={() => tick(i)}>✓</button>
                          <button type="button" class="slw-step-text" onclick={() => tick(i)}>
                            <span>{s.label}</span>
                            <small>{s.hint}</small>
                          </button>
                          <span class="slw-step-num" aria-hidden="true">{i + 1}</span>
                        </li>
                      {/each}
                    </ol>
                    <div class="slw-progress">
                      <div class="slw-meter" role="progressbar" aria-valuenow={count} aria-valuemin="0" aria-valuemax="4"
                        style:--slw-done={count}><i></i></div>
                      <b>{count} of 4 completed</b>
                    </div>
                    {#if count === steps.length}
                      <p class="slw-set">✓ All set — happy selling!</p>
                    {/if}
                  </div>
                </section>
              {:else}
                <div class="slw-gone">
                  <span>The mat is dismissed for good.</span>
                  <button type="button" onclick={() => (gone = false)}>Show it again</button>
                </div>
              {/if}
            </div>

            <style>
            .slw-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slw-green: #04844B; --slw-muted: #706E6B; --slw-border: #DDDBDA; --slw-card: #FFFFFF; --slw-blue: #0176D3; }
            @media (prefers-color-scheme: dark) { .slw-root { color: #F3F3F3;
              --slw-green: #0E9E5B; --slw-muted: #A5A5A5; --slw-border: #474747; --slw-card: #232323; --slw-blue: #0D9DDA; } }
            .slw-card { position: relative; max-inline-size: 46rem; margin-inline: auto; display: grid;
              grid-template-columns: minmax(11rem, 1fr) minmax(0, 1.2fr); border: 1px solid var(--slw-border);
              border-radius: .25rem; background: var(--slw-card); overflow: clip; }
            @media (max-width: 540px) { .slw-card { grid-template-columns: 1fr; } }
            .slw-close { position: absolute; inset-block-start: .5rem; inset-inline-end: .5rem; z-index: 2;
              inline-size: 1.75rem; aspect-ratio: 1; display: grid; place-items: center; border: 0; border-radius: .25rem;
              background: transparent; color: var(--slw-muted); cursor: pointer; font-size: .95rem; }
            .slw-info { position: relative; display: grid; align-content: end; gap: .4rem; min-block-size: 15rem;
              margin: 0; padding: 1.25rem 1.1rem;
              background: linear-gradient(160deg, #0B5CAB, #0176D3 62%, #0E9E5B 130%); color: #FFFFFF; }
            .slw-art { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%; pointer-events: none; }
            .slw-art i { position: absolute; border-radius: 50%; background: color-mix(in srgb, #FFFFFF 16%, transparent); }
            .slw-art i:nth-child(1) { inline-size: 5.5rem; aspect-ratio: 1; inset-block-start: 14%; inset-inline-start: 12%; }
            .slw-art i:nth-child(2) { inline-size: 2.2rem; aspect-ratio: 1; inset-block-start: 48%; inset-inline-start: 58%; opacity: .7; }
            .slw-art i:nth-child(3) { inline-size: 3.4rem; aspect-ratio: 1; inset-block-start: 8%; inset-inline-start: 66%; opacity: .5; }
            .slw-info h3 { position: relative; margin: 0; font-size: 1.15rem; font-weight: 700; }
            .slw-info p { position: relative; margin: 0; font-size: .84rem; opacity: .92; }
            .slw-play { position: relative; justify-self: start; margin-block-start: .5rem; padding: .45rem .95rem;
              border: 1px solid color-mix(in srgb, #FFFFFF 55%, transparent); border-radius: .25rem;
              background: color-mix(in srgb, #FFFFFF 16%, transparent); color: #FFFFFF;
              font: inherit; font-size: .82rem; font-weight: 600; cursor: pointer; }
            .slw-steps { display: grid; align-content: start; margin: 0; padding: .35rem .9rem .9rem; list-style: none; }
            .slw-steps li { display: flex; align-items: center; gap: .75rem; padding: .15rem 0; }
            .slw-check { flex: none; inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50%;
              border: 1px solid var(--slw-border); background: var(--slw-card); color: transparent;
              display: grid; place-items: center; cursor: pointer; }
            .slw-check[aria-pressed='true'] { background: var(--slw-green); border-color: var(--slw-green); color: #FFFFFF; }
            .slw-step-text { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: inherit;
              font: inherit; font-size: .85rem; font-weight: 600; cursor: pointer; text-align: start; padding: .35rem 0; }
            .slw-step-text small { display: block; font-weight: 400; font-size: .74rem; color: var(--slw-muted); }
            .slw-step-num { flex: none; font-size: .72rem; font-weight: 700; color: var(--slw-muted); }
            .slw-progress { display: flex; align-items: center; gap: .6rem; padding: .6rem .9rem .8rem; }
            .slw-meter { flex: 1; block-size: 4px; border-radius: 999px; background: var(--slw-border); overflow: clip; }
            .slw-meter i { display: block; block-size: 100%; border-radius: inherit; background: var(--slw-green);
              inline-size: calc(var(--slw-done, 0) / 4 * 100%); transition: inline-size .25s ease; }
            .slw-progress b { font-size: .78rem; color: var(--slw-muted); white-space: nowrap; }
            .slw-set { display: flex; align-items: center; gap: .5rem; margin: 0 .9rem .9rem; padding: .55rem .75rem;
              border-radius: .25rem; background: color-mix(in srgb, var(--slw-green) 12%, var(--slw-card));
              color: var(--slw-green); font-size: .8rem; font-weight: 700; }
            .slw-gone { display: grid; place-items: center; gap: .5rem; max-inline-size: 46rem; margin-inline: auto;
              min-block-size: 9rem; border: 1px dashed var(--slw-border); border-radius: .25rem;
              color: var(--slw-muted); font-size: .85rem; text-align: center; padding: 1rem; }
            .slw-gone button { border: 0; background: transparent; color: var(--slw-blue); font: inherit;
              font-size: .82rem; font-weight: 600; cursor: pointer; text-decoration: underline; }
            </style>
            SVELTE,
        ],
    ],

    'scoped-tabs' => [
        'title' => ['fa' => 'تب‌های اسکوپ‌دار (Scoped Tabs)', 'en' => 'Scoped Tabs'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'تب‌های هم‌رنگ برند که محتوای زیر خود را قاب می‌کنند؛ نسخهٔ رکوردیِ تب که ناوبری لایتنینگ را از هر تب دیگری جدا می‌کند.',
            'en' => 'Brand-colored tabs that frame their own content area — navigation that only a record app wears.',
        ],
        'js' => true,
        'docs' => 'https://www.lightningdesignsystem.com/components/tabs/',
        'props' => [
            ['name' => 'scope', 'type' => 'color', 'default' => "'per-tab'", 'note' => [
                'fa' => 'هر تب رنگ خودش را از پالت مارکتینگ می‌گیرد و با انتخاب، نوارِ تب‌ها به همان رنگ درمی‌آید.',
                'en' => 'Each tab owns a Marketing Cloud colour; selecting one repaints the whole tab bar with it.',
            ]],
            ['name' => 'frame', 'type' => 'border', 'default' => "'1px'", 'note' => [
                'fa' => 'قاب یک‌پیکسلی دور ناحیهٔ محتوا، رنگش همان رنگ تب فعال است.',
                'en' => 'A 1px frame wraps the content area, painted in the active tab’s colour.',
            ]],
            ['name' => 'panel', 'type' => 'slot', 'default' => "'record'", 'note' => [
                'fa' => 'هر پنل محتوای رکوردی خودش را دارد: جزئیات، مرتبط‌ها، فعالیت.',
                'en' => 'Every panel carries its own record content: details, related, activity.',
            ]],
            ['name' => 'overflow', 'type' => 'behaviour', 'default' => "'scroll'", 'note' => [
                'fa' => 'تب‌های بیش از عرض، افقی داخل خودِ نوار اسکرول می‌شوند نه کل صفحه.',
                'en' => 'Tabs beyond the width scroll horizontally inside the bar, never the page.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <div class="sflx-stabs" style="--scope: #0176D3" data-active="details">
                <div role="tablist">
                    <button role="tab" aria-selected="true">جزئیات</button>
                    <button role="tab" aria-selected="false" style="--scope: #04844B">مرتبط</button>
                </div>
                <div role="tabpanel">…محتوای جزئیات…</div>
            </div>

            <style>
            .sflx-stabs { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
            .sflx-stabs [role='tablist'] { display: flex; background: var(--scope); padding: 0 .25rem; }
            .sflx-stabs [role='tab'] { padding: .75rem 1.25rem; color: #181818; }
            .sflx-stabs [role='tab'][aria-selected='true'] { background: #fff; font-weight: 700;
                box-shadow: inset 0 3px 0 var(--scope); }
            .sflx-stabs [role='tabpanel'] { padding: 1rem; border-block-start: 1px solid var(--scope); }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="sflx-stabs" style="--scope: #0176D3" data-active="details">
                <div role="tablist">
                    <button role="tab" aria-selected="true">Details</button>
                    <button role="tab" aria-selected="false" style="--scope: #04844B">Related</button>
                </div>
                <div role="tabpanel">…Details content…</div>
            </div>

            <style>
            .sflx-stabs { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
            .sflx-stabs [role='tablist'] { display: flex; background: var(--scope); padding: 0 .25rem; }
            .sflx-stabs [role='tab'] { padding: .75rem 1.25rem; color: #181818; }
            .sflx-stabs [role='tab'][aria-selected='true'] { background: #fff; font-weight: 700;
                box-shadow: inset 0 3px 0 var(--scope); }
            .sflx-stabs [role='tabpanel'] { padding: 1rem; border-block-start: 1px solid var(--scope); }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Opportunities/Show.tsx — an Inertia page (React flavor).
            // Inertia resolves this file for the /opportunities/{record} route; the
            // section switching stays client-side inside ScopedTabs, so no Inertia
            // partial loads are involved. Component from the React snippet.
            import ScopedTabs from '@/components/ScopedTabs';

            export default function OpportunityShow() {
              return <ScopedTabs />;
            }
            TSX,
            'react' => <<<'TSX'
            // ScopedTabs.tsx — Scoped Tabs framing a record page: each section owns a
            // marketing colour that repaints the whole bar, and the active tab carries
            // its colour bar on top. The markup mirrors the playground partial.
            import { type CSSProperties, useState } from 'react';
            import '@nabuxai/ui-react/css';

            const TABS = [
              { label: 'Details', tint: '#0176D3' },
              { label: 'Related', tint: '#04844B' },
              { label: 'News', tint: '#FE9339' },
              { label: 'Activity', tint: '#6739B7' },
            ];

            export default function ScopedTabs() {
              const [cur, setCur] = useState(0);

              return (
                <div className="sly-root">
                  <div className="sly-card" style={{ '--sly-tint': TABS[cur].tint } as CSSProperties}>
                    <div className="sly-bar" role="tablist" aria-label="Record sections">
                      {TABS.map((t, i) => (
                        <button key={t.label} type="button" className="sly-tab" role="tab"
                          aria-selected={cur === i} onClick={() => setCur(i)}>
                          {t.label}
                        </button>
                      ))}
                    </div>

                    {/* key remounts the panel so the fade replays on every switch */}
                    <div className="sly-panel" role="tabpanel" key={cur}>
                      {cur === 0 && (
                        <>
                          <p className="sly-legend">Opportunity: <b>Details</b></p>
                          <dl className="sly-fields">
                            <div><dt>Account</dt><dd>Bosporus Logistics</dd></div>
                            <div><dt>Amount</dt><dd>€2,400,000</dd></div>
                            <div><dt>Owner</dt><dd>Elif Demir</dd></div>
                            <div><dt>Close date</dt><dd>Feb 28</dd></div>
                          </dl>
                        </>
                      )}
                      {cur === 1 && (
                        <>
                          <p className="sly-legend">Related records</p>
                          <ul className="sly-mini">
                            <li>Contact · Lena Fischer <small>Technical reviewer</small></li>
                            <li>Quote · 2026/Q-18 <small>Sent yesterday</small></li>
                            <li>Task · Follow up on pricing <small>Due Sunday</small></li>
                          </ul>
                        </>
                      )}
                      {cur === 2 && (
                        <>
                          <p className="sly-legend">News about <b>Bosporus Logistics</b></p>
                          <ul className="sly-log">
                            <li>Bosporus opened a second depot in Berlin — expansion budget approved.<time>2 days ago</time></li>
                            <li>Q3 earnings call: logistics automation up 18%.<time>1 week ago</time></li>
                          </ul>
                        </>
                      )}
                      {cur === 3 && (
                        <>
                          <p className="sly-legend">Recent activity</p>
                          <ul className="sly-log">
                            <li>Elif sent the revised quote.<time>2 hours ago</time></li>
                            <li>Stage moved to Negotiation/Review.<time>yesterday</time></li>
                            <li>Lena attached the pricing sheet.<time>3 days ago</time></li>
                          </ul>
                        </>
                      )}
                    </div>
                  </div>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .sly-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sly-muted: #706E6B; --sly-border: #DDDBDA; --sly-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sly-root { color: #F3F3F3;
              --sly-muted: #A5A5A5; --sly-border: #474747; --sly-card: #232323; } }
            .sly-card { max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sly-tint, #0176D3);
              border-radius: .25rem; background: var(--sly-card); overflow: clip; }
            .sly-bar { display: flex; background: var(--sly-tint, #0176D3); padding-inline: .3rem;
              overflow-x: auto; transition: background .2s ease; }
            .sly-tab { flex: none; position: relative; padding: .7rem 1.15rem; border: 0; background: transparent;
              color: color-mix(in srgb, #FFFFFF 88%, transparent); font: inherit; font-size: .85rem; font-weight: 600;
              cursor: pointer; white-space: nowrap; }
            .sly-tab:hover { color: #FFFFFF; }
            .sly-tab[aria-selected='true'] { background: var(--sly-card); color: var(--sly-text, #181818); }
            .sly-tab[aria-selected='true']::after { content: ''; position: absolute; inset-inline: 0;
              inset-block-start: 0; block-size: 3px; background: var(--sly-tint, #0176D3); }
            .sly-panel { padding: 1rem; min-block-size: 13rem; border-block-start: 1px solid var(--sly-tint, #0176D3);
              animation: sly-fade .2s ease-out; }
            @keyframes sly-fade { from { opacity: 0; translate: 0 3px; } to { opacity: 1; translate: 0 0; } }
            .sly-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));
              gap: .8rem 1.25rem; margin: 0; }
            .sly-fields dt { font-size: .72rem; color: var(--sly-muted); }
            .sly-fields dd { margin: .1rem 0 0; font-size: .88rem; font-weight: 700; }
            .sly-mini { margin: 0; padding: 0; list-style: none; display: grid; gap: .5rem; }
            .sly-mini li { display: flex; align-items: center; gap: .6rem; padding: .5rem .65rem;
              border: 1px solid var(--sly-border); border-radius: .25rem; font-size: .82rem; }
            .sly-mini li small { margin-inline-start: auto; color: var(--sly-muted); }
            .sly-log { margin: 0; padding: 0; list-style: none; display: grid; gap: .75rem; }
            .sly-log li { position: relative; padding-inline-start: 1.25rem; font-size: .84rem; }
            .sly-log li::before { content: ''; position: absolute; inset-block-start: .3rem; inset-inline-start: 0;
              inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: var(--sly-tint, #0176D3); }
            .sly-log li time { display: block; font-size: .72rem; color: var(--sly-muted); }
            .sly-legend { display: flex; align-items: center; gap: .4rem; margin: 0 0 .75rem; font-size: .78rem; color: var(--sly-muted); }
            .sly-legend b { padding: .08rem .5rem; border-radius: .9rem; color: #FFFFFF; font-size: .72rem;
              background: var(--sly-tint, #0176D3); font-weight: 700; }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- ScopedTabs.vue — Scoped Tabs framing a record page: each section owns a
                 marketing colour that repaints the whole bar, the active tab carries its
                 colour bar on top. Markup mirrors the playground partial. -->
            <script setup lang="ts">
            import { computed, ref } from 'vue';

            const tabs = [
              { label: 'Details', tint: '#0176D3' },
              { label: 'Related', tint: '#04844B' },
              { label: 'News', tint: '#FE9339' },
              { label: 'Activity', tint: '#6739B7' },
            ];

            const cur = ref(0);
            const tint = computed(() => ({ '--sly-tint': tabs[cur.value].tint }));
            </script>

            <template>
              <div class="sly-root">
                <div class="sly-card" :style="tint">
                  <div class="sly-bar" role="tablist" aria-label="Record sections">
                    <button v-for="(t, i) in tabs" :key="t.label" type="button" class="sly-tab" role="tab"
                      :aria-selected="cur === i" @click="cur = i">
                      {{ t.label }}
                    </button>
                  </div>

                  <!-- :key remounts the panel so the fade replays on every switch -->
                  <div :key="cur" class="sly-panel" role="tabpanel">
                    <template v-if="cur === 0">
                      <p class="sly-legend">Opportunity: <b>Details</b></p>
                      <dl class="sly-fields">
                        <div><dt>Account</dt><dd>Bosporus Logistics</dd></div>
                        <div><dt>Amount</dt><dd>€2,400,000</dd></div>
                        <div><dt>Owner</dt><dd>Elif Demir</dd></div>
                        <div><dt>Close date</dt><dd>Feb 28</dd></div>
                      </dl>
                    </template>
                    <template v-else-if="cur === 1">
                      <p class="sly-legend">Related records</p>
                      <ul class="sly-mini">
                        <li>Contact · Lena Fischer <small>Technical reviewer</small></li>
                        <li>Quote · 2026/Q-18 <small>Sent yesterday</small></li>
                        <li>Task · Follow up on pricing <small>Due Sunday</small></li>
                      </ul>
                    </template>
                    <template v-else-if="cur === 2">
                      <p class="sly-legend">News about <b>Bosporus Logistics</b></p>
                      <ul class="sly-log">
                        <li>Bosporus opened a second depot in Berlin — expansion budget approved.<time>2 days ago</time></li>
                        <li>Q3 earnings call: logistics automation up 18%.<time>1 week ago</time></li>
                      </ul>
                    </template>
                    <template v-else>
                      <p class="sly-legend">Recent activity</p>
                      <ul class="sly-log">
                        <li>Elif sent the revised quote.<time>2 hours ago</time></li>
                        <li>Stage moved to Negotiation/Review.<time>yesterday</time></li>
                        <li>Lena attached the pricing sheet.<time>3 days ago</time></li>
                      </ul>
                    </template>
                  </div>
                </div>
              </div>
            </template>

            <style scoped>
            .sly-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sly-muted: #706E6B; --sly-border: #DDDBDA; --sly-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sly-root { color: #F3F3F3;
              --sly-muted: #A5A5A5; --sly-border: #474747; --sly-card: #232323; } }
            .sly-card { max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sly-tint, #0176D3);
              border-radius: .25rem; background: var(--sly-card); overflow: clip; }
            .sly-bar { display: flex; background: var(--sly-tint, #0176D3); padding-inline: .3rem;
              overflow-x: auto; transition: background .2s ease; }
            .sly-tab { flex: none; position: relative; padding: .7rem 1.15rem; border: 0; background: transparent;
              color: color-mix(in srgb, #FFFFFF 88%, transparent); font: inherit; font-size: .85rem; font-weight: 600;
              cursor: pointer; white-space: nowrap; }
            .sly-tab:hover { color: #FFFFFF; }
            .sly-tab[aria-selected='true'] { background: var(--sly-card); color: inherit; }
            .sly-tab[aria-selected='true']::after { content: ''; position: absolute; inset-inline: 0;
              inset-block-start: 0; block-size: 3px; background: var(--sly-tint, #0176D3); }
            .sly-panel { padding: 1rem; min-block-size: 13rem; border-block-start: 1px solid var(--sly-tint, #0176D3);
              animation: sly-fade .2s ease-out; }
            @keyframes sly-fade { from { opacity: 0; translate: 0 3px; } to { opacity: 1; translate: 0 0; } }
            .sly-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));
              gap: .8rem 1.25rem; margin: 0; }
            .sly-fields dt { font-size: .72rem; color: var(--sly-muted); }
            .sly-fields dd { margin: .1rem 0 0; font-size: .88rem; font-weight: 700; }
            .sly-mini { margin: 0; padding: 0; list-style: none; display: grid; gap: .5rem; }
            .sly-mini li { display: flex; align-items: center; gap: .6rem; padding: .5rem .65rem;
              border: 1px solid var(--sly-border); border-radius: .25rem; font-size: .82rem; }
            .sly-mini li small { margin-inline-start: auto; color: var(--sly-muted); }
            .sly-log { margin: 0; padding: 0; list-style: none; display: grid; gap: .75rem; }
            .sly-log li { position: relative; padding-inline-start: 1.25rem; font-size: .84rem; }
            .sly-log li::before { content: ''; position: absolute; inset-block-start: .3rem; inset-inline-start: 0;
              inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: var(--sly-tint, #0176D3); }
            .sly-log li time { display: block; font-size: .72rem; color: var(--sly-muted); }
            .sly-legend { display: flex; align-items: center; gap: .4rem; margin: 0 0 .75rem; font-size: .78rem; color: var(--sly-muted); }
            .sly-legend b { padding: .08rem .5rem; border-radius: .9rem; color: #FFFFFF; font-size: .72rem;
              background: var(--sly-tint, #0176D3); font-weight: 700; }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- ScopedTabs.svelte — Scoped Tabs framing a record page: each section owns
                 a marketing colour that repaints the whole bar, the active tab carries
                 its colour bar on top. Markup mirrors the playground partial. -->
            <script lang="ts">
              const tabs = [
                { label: 'Details', tint: '#0176D3' },
                { label: 'Related', tint: '#04844B' },
                { label: 'News', tint: '#FE9339' },
                { label: 'Activity', tint: '#6739B7' },
              ];

              let cur = $state(0);
              const tint = $derived(tabs[cur].tint);
            </script>

            <div class="sly-root">
              <div class="sly-card" style:--sly-tint={tint}>
                <div class="sly-bar" role="tablist" aria-label="Record sections">
                  {#each tabs as t, i (t.label)}
                    <button type="button" class="sly-tab" role="tab" aria-selected={cur === i}
                      onclick={() => (cur = i)}>
                      {t.label}
                    </button>
                  {/each}
                </div>

                <!-- key block remounts the panel so the fade replays on every switch -->
                {#key cur}
                  <div class="sly-panel" role="tabpanel">
                    {#if cur === 0}
                      <p class="sly-legend">Opportunity: <b>Details</b></p>
                      <dl class="sly-fields">
                        <div><dt>Account</dt><dd>Bosporus Logistics</dd></div>
                        <div><dt>Amount</dt><dd>€2,400,000</dd></div>
                        <div><dt>Owner</dt><dd>Elif Demir</dd></div>
                        <div><dt>Close date</dt><dd>Feb 28</dd></div>
                      </dl>
                    {:else if cur === 1}
                      <p class="sly-legend">Related records</p>
                      <ul class="sly-mini">
                        <li>Contact · Lena Fischer <small>Technical reviewer</small></li>
                        <li>Quote · 2026/Q-18 <small>Sent yesterday</small></li>
                        <li>Task · Follow up on pricing <small>Due Sunday</small></li>
                      </ul>
                    {:else if cur === 2}
                      <p class="sly-legend">News about <b>Bosporus Logistics</b></p>
                      <ul class="sly-log">
                        <li>Bosporus opened a second depot in Berlin — expansion budget approved.<time>2 days ago</time></li>
                        <li>Q3 earnings call: logistics automation up 18%.<time>1 week ago</time></li>
                      </ul>
                    {:else}
                      <p class="sly-legend">Recent activity</p>
                      <ul class="sly-log">
                        <li>Elif sent the revised quote.<time>2 hours ago</time></li>
                        <li>Stage moved to Negotiation/Review.<time>yesterday</time></li>
                        <li>Lena attached the pricing sheet.<time>3 days ago</time></li>
                      </ul>
                    {/if}
                  </div>
                {/key}
              </div>
            </div>

            <style>
            .sly-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sly-muted: #706E6B; --sly-border: #DDDBDA; --sly-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sly-root { color: #F3F3F3;
              --sly-muted: #A5A5A5; --sly-border: #474747; --sly-card: #232323; } }
            .sly-card { max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sly-tint, #0176D3);
              border-radius: .25rem; background: var(--sly-card); overflow: clip; }
            .sly-bar { display: flex; background: var(--sly-tint, #0176D3); padding-inline: .3rem;
              overflow-x: auto; transition: background .2s ease; }
            .sly-tab { flex: none; position: relative; padding: .7rem 1.15rem; border: 0; background: transparent;
              color: color-mix(in srgb, #FFFFFF 88%, transparent); font: inherit; font-size: .85rem; font-weight: 600;
              cursor: pointer; white-space: nowrap; }
            .sly-tab:hover { color: #FFFFFF; }
            .sly-tab[aria-selected='true'] { background: var(--sly-card); color: inherit; }
            .sly-tab[aria-selected='true']::after { content: ''; position: absolute; inset-inline: 0;
              inset-block-start: 0; block-size: 3px; background: var(--sly-tint, #0176D3); }
            .sly-panel { padding: 1rem; min-block-size: 13rem; border-block-start: 1px solid var(--sly-tint, #0176D3);
              animation: sly-fade .2s ease-out; }
            @keyframes sly-fade { from { opacity: 0; translate: 0 3px; } to { opacity: 1; translate: 0 0; } }
            .sly-fields { display: grid; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr));
              gap: .8rem 1.25rem; margin: 0; }
            .sly-fields dt { font-size: .72rem; color: var(--sly-muted); }
            .sly-fields dd { margin: .1rem 0 0; font-size: .88rem; font-weight: 700; }
            .sly-mini { margin: 0; padding: 0; list-style: none; display: grid; gap: .5rem; }
            .sly-mini li { display: flex; align-items: center; gap: .6rem; padding: .5rem .65rem;
              border: 1px solid var(--sly-border); border-radius: .25rem; font-size: .82rem; }
            .sly-mini li small { margin-inline-start: auto; color: var(--sly-muted); }
            .sly-log { margin: 0; padding: 0; list-style: none; display: grid; gap: .75rem; }
            .sly-log li { position: relative; padding-inline-start: 1.25rem; font-size: .84rem; }
            .sly-log li::before { content: ''; position: absolute; inset-block-start: .3rem; inset-inline-start: 0;
              inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: var(--sly-tint, #0176D3); }
            .sly-log li time { display: block; font-size: .72rem; color: var(--sly-muted); }
            .sly-legend { display: flex; align-items: center; gap: .4rem; margin: 0 0 .75rem; font-size: .78rem; color: var(--sly-muted); }
            .sly-legend b { padding: .08rem .5rem; border-radius: .9rem; color: #FFFFFF; font-size: .72rem;
              background: var(--sly-tint, #0176D3); font-weight: 700; }
            </style>
            SVELTE,
        ],
    ],

    'app-launcher' => [
        'title' => ['fa' => 'لانچر اپ‌ها (App Launcher)', 'en' => 'App Launcher'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'دکمهٔ وافل معروف که شبکهٔ کاشی‌های رنگی «همهٔ اپ‌ها» را باز می‌کند؛ امضای ناوبری سلس‌فورس از همان نگاه اول.',
            'en' => 'The famous waffle button opening a tile grid of all apps — the switcher every Salesforce screen starts with.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/app-launcher/',
        'props' => [
            ['name' => 'waffle', 'type' => 'button', 'default' => "'3×3'", 'note' => [
                'fa' => 'دکمهٔ ۹نقطه‌ای سربرگ؛ با کلیک پنل اپ‌ها باز می‌شود و Escape می‌بندد.',
                'en' => 'The header’s 3×3 dot button; it springs the app panel open and Escape closes it.',
            ]],
            ['name' => 'search', 'type' => 'input', 'default' => "'live'", 'note' => [
                'fa' => 'جست‌وجوی بالای پنل، همان لحظه کاشی‌ها را فیلتر می‌کند.',
                'en' => 'The search on top of the panel live-filters the tiles as you type.',
            ]],
            ['name' => 'tile', 'type' => 'grid', 'default' => "'3-col'", 'note' => [
                'fa' => 'کاشی‌های رنگی با حرف اول اپ؛ هر بخش اپ‌های خودش را دارد.',
                'en' => 'Colour tiles with the app’s initial; sections group their own apps.',
            ]],
            ['name' => 'all-apps', 'type' => 'link', 'default' => "'footer'", 'note' => [
                'fa' => 'پانویس با لینک «همهٔ اپ‌ها» به نمای تمام‌صفحه می‌رود.',
                'en' => 'The footer’s «All Apps» link opens the full-page view.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <div class="sflx-launcher">
                <button class="sflx-waffle" aria-expanded="false" aria-label="همهٔ اپ‌ها">
                    <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
                </button>
                <div class="sflx-apps" role="dialog" aria-label="اپ‌ها">
                    <input type="search" placeholder="جست‌وجوی اپ‌ها…">
                    <section>
                        <h3>فروش</h3>
                        <a class="sflx-tile" style="--tile: #0B5CAB" href="#"><b>ف</b>فروش</a>
                    </section>
                </div>
            </div>

            <style>
            .sflx-launcher { position: relative; }
            .sflx-waffle { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; padding: .4rem; }
            .sflx-waffle i { inline-size: 3px; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
            .sflx-apps { position: absolute; inset-block-start: 100%; inline-size: 20rem;
                         background: #fff; border: 1px solid #DDDBDA; border-radius: .25rem; }
            .sflx-tile b { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                           border-radius: .25rem; background: var(--tile); color: #fff; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="sflx-launcher">
                <button class="sflx-waffle" aria-expanded="false" aria-label="All apps">
                    <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
                </button>
                <div class="sflx-apps" role="dialog" aria-label="Apps">
                    <input type="search" placeholder="Search apps…">
                    <section>
                        <h3>Sales</h3>
                        <a class="sflx-tile" style="--tile: #0B5CAB" href="#"><b>S</b>Sales</a>
                    </section>
                </div>
            </div>

            <style>
            .sflx-launcher { position: relative; }
            .sflx-waffle { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; padding: .4rem; }
            .sflx-waffle i { inline-size: 3px; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
            .sflx-apps { position: absolute; inset-block-start: 100%; inline-size: 20rem;
                         background: #fff; border: 1px solid #DDDBDA; border-radius: .25rem; }
            .sflx-tile b { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                           border-radius: .25rem; background: var(--tile); color: #fff; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Dashboard.tsx — an Inertia page (React flavor). The
            // launcher lives in the app header; the waffle panel itself is the
            // AppLauncher component from the React snippet.
            import AppLauncher from '@/components/AppLauncher';

            export default function Dashboard() {
              return <AppLauncher />;
            }
            TSX,
            'react' => <<<'TSX'
            // AppLauncher.tsx — the famous 3×3 waffle in a Lightning header opening the
            // app panel: live search, colour tiles with initials, honest counts, Escape
            // to close. The markup mirrors the playground partial (sla-* classes).
            import { type CSSProperties, useState } from 'react';
            import '@nabuxai/ui-react/css';

            const APPS = [
              { label: 'Sales', initial: 'S', tint: '#0B5CAB' },
              { label: 'Service', initial: 'S', tint: '#0176D3' },
              { label: 'Marketing', initial: 'M', tint: '#B32D69' },
              { label: 'Chatter', initial: 'C', tint: '#04844B' },
              { label: 'Reports', initial: 'R', tint: '#FE9339' },
              { label: 'Dashboards', initial: 'D', tint: '#6739B7' },
              { label: 'Tasks', initial: 'T', tint: '#0B827C' },
              { label: 'Notes', initial: 'N', tint: '#BA0517' },
            ];

            export default function AppLauncher() {
              const [open, setOpen] = useState(true);
              const [q, setQ] = useState('');
              const matches = APPS.filter((a) => a.label.toLowerCase().includes(q.trim().toLowerCase()));

              return (
                <div className="sla-root" onKeyDown={(e) => e.key === 'Escape' && setOpen(false)}>
                  <div>
                    <div className="sla-header">
                      <button type="button" className="sla-waffle" aria-expanded={open}
                        aria-label="Open the app launcher" onClick={() => setOpen(!open)}>
                        {Array.from({ length: 9 }, (_, i) => <i key={i} />)}
                      </button>
                      <span className="sla-breadcrumb">Sales / Opportunity: Nabu annual license</span>
                      <span className="sla-search-head">Search…</span>
                    </div>

                    {open && (
                      <div className="sla-panel" role="dialog" aria-label="App launcher">
                        <div className="sla-search">
                          <svg className="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            strokeLinecap="round" aria-hidden="true"><path d="M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14zM20 20l-4-4" /></svg>
                          <input type="search" value={q} onChange={(e) => setQ(e.target.value)}
                            placeholder="Find an app…" aria-label="Search apps" />
                        </div>
                        <div className="sla-body">
                          <section className="sla-section">
                            <h3>All items · {matches.length}</h3>
                            <div className="sla-grid">
                              {matches.map((a) => (
                                <button key={a.label} type="button" className="sla-app"
                                  style={{ '--sla-tint': a.tint } as CSSProperties}>
                                  <span className="sla-tile" aria-hidden="true">{a.initial}</span>
                                  <span>{a.label}</span>
                                </button>
                              ))}
                            </div>
                            {matches.length === 0 && <p className="sla-empty">No app matches — try "Sales".</p>}
                          </section>
                        </div>
                        <div className="sla-foot"><button type="button">All apps →</button></div>
                      </div>
                    )}
                  </div>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .sla-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sla-blue: #0176D3; --sla-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sla-navy: #0B5CAB;
              --sla-muted: #706E6B; --sla-border: #DDDBDA; --sla-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sla-root { color: #F3F3F3;
              --sla-blue: #0D9DDA; --sla-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sla-navy: #0B5CAB;
              --sla-muted: #A5A5A5; --sla-border: #474747; --sla-card: #232323; } }
            .sla-header { display: flex; align-items: center; gap: .75rem; padding: .55rem .75rem;
              max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sla-border);
              border-radius: .25rem .25rem 0 0; border-block-end: 0; background: var(--sla-navy); color: #FFFFFF; }
            .sla-waffle { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5px; padding: .45rem;
              border: 0; border-radius: .25rem; background: transparent; color: #FFFFFF; cursor: pointer; }
            .sla-waffle[aria-expanded='true'] { background: color-mix(in srgb, #FFFFFF 22%, transparent); }
            .sla-waffle i { inline-size: 3.5px; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
            .sla-breadcrumb { font-size: .85rem; opacity: .92; }
            .sla-search-head { margin-inline-start: auto; font-size: .8rem; opacity: .9; }
            .sla-panel { max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sla-border);
              border-radius: 0 0 .25rem .25rem; background: var(--sla-card); overflow: clip; }
            .sla-search { display: flex; align-items: center; gap: .5rem; padding: .65rem .9rem;
              border-block-end: 1px solid var(--sla-border); }
            .sla-search .nx-icon { color: var(--sla-muted); }
            .sla-search input { flex: 1; min-inline-size: 0; padding: .45rem .6rem; border: 1px solid var(--sla-border);
              border-radius: .25rem; background: var(--sla-card); color: inherit; font: inherit; font-size: .85rem; }
            .sla-body { max-block-size: 21rem; overflow-y: auto; padding: .35rem .9rem 0; }
            .sla-section h3 { margin: .6rem 0 .4rem; font-size: .8rem; font-weight: 700; color: var(--sla-muted);
              display: flex; align-items: center; gap: .5rem; }
            .sla-section h3::after { content: ''; flex: 1; block-size: 1px; background: var(--sla-border); }
            .sla-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.5rem, 1fr));
              gap: .4rem; padding-block-end: .6rem; }
            .sla-app { display: grid; justify-items: center; gap: .35rem; padding: .6rem .35rem; border: 0;
              border-radius: .25rem; background: transparent; color: inherit; font: inherit; cursor: pointer; }
            .sla-app:hover { background: var(--sla-blue-soft); }
            .sla-tile { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem; border-radius: .25rem;
              background: var(--sla-tint, var(--sla-navy)); color: #FFFFFF; font-size: 1.15rem; font-weight: 700; }
            .sla-app span { max-inline-size: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
              font-size: .76rem; font-weight: 600; }
            .sla-empty { padding: 1.4rem 0 1.6rem; text-align: center; color: var(--sla-muted); font-size: .85rem; }
            .sla-foot { display: flex; justify-content: center; border-block-start: 1px solid var(--sla-border); padding: .55rem; }
            .sla-foot button { border: 0; background: transparent; color: var(--sla-blue); font: inherit;
              font-size: .8rem; font-weight: 600; cursor: pointer; }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- AppLauncher.vue — the famous 3×3 waffle in a Lightning header opening
                 the app panel: live search, colour tiles with initials, honest counts,
                 Escape to close. Markup mirrors the playground partial (sla-* classes). -->
            <script setup lang="ts">
            import { computed, ref } from 'vue';

            const apps = [
              { label: 'Sales', initial: 'S', tint: '#0B5CAB' },
              { label: 'Service', initial: 'S', tint: '#0176D3' },
              { label: 'Marketing', initial: 'M', tint: '#B32D69' },
              { label: 'Chatter', initial: 'C', tint: '#04844B' },
              { label: 'Reports', initial: 'R', tint: '#FE9339' },
              { label: 'Dashboards', initial: 'D', tint: '#6739B7' },
              { label: 'Tasks', initial: 'T', tint: '#0B827C' },
              { label: 'Notes', initial: 'N', tint: '#BA0517' },
            ];

            const open = ref(true);
            const q = ref('');
            const matches = computed(() =>
              apps.filter((a) => a.label.toLowerCase().includes(q.value.trim().toLowerCase())));
            </script>

            <template>
              <div class="sla-root" @keydown.escape="open = false">
                <div>
                  <div class="sla-header">
                    <button type="button" class="sla-waffle" :aria-expanded="open"
                      aria-label="Open the app launcher" @click="open = !open">
                      <i v-for="dot in 9" :key="dot" />
                    </button>
                    <span class="sla-breadcrumb">Sales / Opportunity: Nabu annual license</span>
                    <span class="sla-search-head">Search…</span>
                  </div>

                  <div v-if="open" class="sla-panel" role="dialog" aria-label="App launcher">
                    <div class="sla-search">
                      <input v-model="q" type="search" placeholder="Find an app…" aria-label="Search apps" />
                    </div>
                    <div class="sla-body">
                      <section class="sla-section">
                        <h3>All items · {{ matches.length }}</h3>
                        <div class="sla-grid">
                          <button v-for="a in matches" :key="a.label" type="button" class="sla-app"
                            :style="{ '--sla-tint': a.tint }">
                            <span class="sla-tile" aria-hidden="true">{{ a.initial }}</span>
                            <span>{{ a.label }}</span>
                          </button>
                        </div>
                        <p v-if="matches.length === 0" class="sla-empty">No app matches — try "Sales".</p>
                      </section>
                    </div>
                    <div class="sla-foot"><button type="button">All apps →</button></div>
                  </div>
                </div>
              </div>
            </template>

            <style scoped>
            .sla-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sla-blue: #0176D3; --sla-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sla-navy: #0B5CAB;
              --sla-muted: #706E6B; --sla-border: #DDDBDA; --sla-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sla-root { color: #F3F3F3;
              --sla-blue: #0D9DDA; --sla-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sla-navy: #0B5CAB;
              --sla-muted: #A5A5A5; --sla-border: #474747; --sla-card: #232323; } }
            .sla-header { display: flex; align-items: center; gap: .75rem; padding: .55rem .75rem;
              max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sla-border);
              border-radius: .25rem .25rem 0 0; border-block-end: 0; background: var(--sla-navy); color: #FFFFFF; }
            .sla-waffle { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5px; padding: .45rem;
              border: 0; border-radius: .25rem; background: transparent; color: #FFFFFF; cursor: pointer; }
            .sla-waffle[aria-expanded='true'] { background: color-mix(in srgb, #FFFFFF 22%, transparent); }
            .sla-waffle i { inline-size: 3.5px; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
            .sla-breadcrumb { font-size: .85rem; opacity: .92; }
            .sla-search-head { margin-inline-start: auto; font-size: .8rem; opacity: .9; }
            .sla-panel { max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sla-border);
              border-radius: 0 0 .25rem .25rem; background: var(--sla-card); overflow: clip; }
            .sla-search { display: flex; align-items: center; gap: .5rem; padding: .65rem .9rem;
              border-block-end: 1px solid var(--sla-border); }
            .sla-search input { flex: 1; min-inline-size: 0; padding: .45rem .6rem; border: 1px solid var(--sla-border);
              border-radius: .25rem; background: var(--sla-card); color: inherit; font: inherit; font-size: .85rem; }
            .sla-body { max-block-size: 21rem; overflow-y: auto; padding: .35rem .9rem 0; }
            .sla-section h3 { margin: .6rem 0 .4rem; font-size: .8rem; font-weight: 700; color: var(--sla-muted);
              display: flex; align-items: center; gap: .5rem; }
            .sla-section h3::after { content: ''; flex: 1; block-size: 1px; background: var(--sla-border); }
            .sla-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.5rem, 1fr));
              gap: .4rem; padding-block-end: .6rem; }
            .sla-app { display: grid; justify-items: center; gap: .35rem; padding: .6rem .35rem; border: 0;
              border-radius: .25rem; background: transparent; color: inherit; font: inherit; cursor: pointer; }
            .sla-app:hover { background: var(--sla-blue-soft); }
            .sla-tile { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem; border-radius: .25rem;
              background: var(--sla-tint, var(--sla-navy)); color: #FFFFFF; font-size: 1.15rem; font-weight: 700; }
            .sla-app span { max-inline-size: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
              font-size: .76rem; font-weight: 600; }
            .sla-empty { padding: 1.4rem 0 1.6rem; text-align: center; color: var(--sla-muted); font-size: .85rem; }
            .sla-foot { display: flex; justify-content: center; border-block-start: 1px solid var(--sla-border); padding: .55rem; }
            .sla-foot button { border: 0; background: transparent; color: var(--sla-blue); font: inherit;
              font-size: .8rem; font-weight: 600; cursor: pointer; }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- AppLauncher.svelte — the famous 3×3 waffle in a Lightning header opening
                 the app panel: live search, colour tiles with initials, honest counts,
                 Escape to close. Markup mirrors the playground partial (sla-* classes). -->
            <script lang="ts">
              const apps = [
                { label: 'Sales', initial: 'S', tint: '#0B5CAB' },
                { label: 'Service', initial: 'S', tint: '#0176D3' },
                { label: 'Marketing', initial: 'M', tint: '#B32D69' },
                { label: 'Chatter', initial: 'C', tint: '#04844B' },
                { label: 'Reports', initial: 'R', tint: '#FE9339' },
                { label: 'Dashboards', initial: 'D', tint: '#6739B7' },
                { label: 'Tasks', initial: 'T', tint: '#0B827C' },
                { label: 'Notes', initial: 'N', tint: '#BA0517' },
              ];

              let open = $state(true);
              let q = $state('');
              const matches = $derived(apps.filter((a) => a.label.toLowerCase().includes(q.trim().toLowerCase())));
            </script>

            <svelte:window onkeydown={(e) => e.key === 'Escape' && (open = false)} />

            <div class="sla-root">
              <div>
                <div class="sla-header">
                  <button type="button" class="sla-waffle" aria-expanded={open}
                    aria-label="Open the app launcher" onclick={() => (open = !open)}>
                    {#each { length: 9 } as _, i (i)}<i></i>{/each}
                  </button>
                  <span class="sla-breadcrumb">Sales / Opportunity: Nabu annual license</span>
                  <span class="sla-search-head">Search…</span>
                </div>

                {#if open}
                  <div class="sla-panel" role="dialog" aria-label="App launcher">
                    <div class="sla-search">
                      <input bind:value={q} type="search" placeholder="Find an app…" aria-label="Search apps" />
                    </div>
                    <div class="sla-body">
                      <section class="sla-section">
                        <h3>All items · {matches.length}</h3>
                        <div class="sla-grid">
                          {#each matches as a (a.label)}
                            <button type="button" class="sla-app" style:--sla-tint={a.tint}>
                              <span class="sla-tile" aria-hidden="true">{a.initial}</span>
                              <span>{a.label}</span>
                            </button>
                          {/each}
                        </div>
                        {#if matches.length === 0}
                          <p class="sla-empty">No app matches — try "Sales".</p>
                        {/if}
                      </section>
                    </div>
                    <div class="sla-foot"><button type="button">All apps →</button></div>
                  </div>
                {/if}
              </div>
            </div>

            <style>
            .sla-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --sla-blue: #0176D3; --sla-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF); --sla-navy: #0B5CAB;
              --sla-muted: #706E6B; --sla-border: #DDDBDA; --sla-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .sla-root { color: #F3F3F3;
              --sla-blue: #0D9DDA; --sla-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323); --sla-navy: #0B5CAB;
              --sla-muted: #A5A5A5; --sla-border: #474747; --sla-card: #232323; } }
            .sla-header { display: flex; align-items: center; gap: .75rem; padding: .55rem .75rem;
              max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sla-border);
              border-radius: .25rem .25rem 0 0; border-block-end: 0; background: var(--sla-navy); color: #FFFFFF; }
            .sla-waffle { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5px; padding: .45rem;
              border: 0; border-radius: .25rem; background: transparent; color: #FFFFFF; cursor: pointer; }
            .sla-waffle[aria-expanded='true'] { background: color-mix(in srgb, #FFFFFF 22%, transparent); }
            .sla-waffle i { inline-size: 3.5px; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
            .sla-breadcrumb { font-size: .85rem; opacity: .92; }
            .sla-search-head { margin-inline-start: auto; font-size: .8rem; opacity: .9; }
            .sla-panel { max-inline-size: 46rem; margin-inline: auto; border: 1px solid var(--sla-border);
              border-radius: 0 0 .25rem .25rem; background: var(--sla-card); overflow: clip; }
            .sla-search { display: flex; align-items: center; gap: .5rem; padding: .65rem .9rem;
              border-block-end: 1px solid var(--sla-border); }
            .sla-search input { flex: 1; min-inline-size: 0; padding: .45rem .6rem; border: 1px solid var(--sla-border);
              border-radius: .25rem; background: var(--sla-card); color: inherit; font: inherit; font-size: .85rem; }
            .sla-body { max-block-size: 21rem; overflow-y: auto; padding: .35rem .9rem 0; }
            .sla-section h3 { margin: .6rem 0 .4rem; font-size: .8rem; font-weight: 700; color: var(--sla-muted);
              display: flex; align-items: center; gap: .5rem; }
            .sla-section h3::after { content: ''; flex: 1; block-size: 1px; background: var(--sla-border); }
            .sla-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.5rem, 1fr));
              gap: .4rem; padding-block-end: .6rem; }
            .sla-app { display: grid; justify-items: center; gap: .35rem; padding: .6rem .35rem; border: 0;
              border-radius: .25rem; background: transparent; color: inherit; font: inherit; cursor: pointer; }
            .sla-app:hover { background: var(--sla-blue-soft); }
            .sla-tile { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem; border-radius: .25rem;
              background: var(--sla-tint, var(--sla-navy)); color: #FFFFFF; font-size: 1.15rem; font-weight: 700; }
            .sla-app span { max-inline-size: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
              font-size: .76rem; font-weight: 600; }
            .sla-empty { padding: 1.4rem 0 1.6rem; text-align: center; color: var(--sla-muted); font-size: .85rem; }
            .sla-foot { display: flex; justify-content: center; border-block-start: 1px solid var(--sla-border); padding: .55rem; }
            .sla-foot button { border: 0; background: transparent; color: var(--sla-blue); font: inherit;
              font-size: .8rem; font-weight: 600; cursor: pointer; }
            </style>
            SVELTE,
        ],
    ],

    'chatter-feed' => [
        'title' => ['fa' => 'فید چَتِر (Chatter Feed)', 'en' => 'Chatter Feed'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'جعبهٔ «اشتراک به‌روزرسانی» بالای فیدی از پست، لایک و کامنت؛ لایهٔ اجتماعیِ همکاری که امضای سلس‌فورس است.',
            'en' => 'A share-an-update publisher above posts, likes and threaded comments — social collaboration that screams Salesforce.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/feeds/',
        'props' => [
            ['name' => 'publisher', 'type' => 'composer', 'default' => "'collapsed'", 'note' => [
                'fa' => 'جعبهٔ اشتراک با «اشتراک به‌روزرسانی…» بسته است؛ تمرکز، ابزار پیوست و دکمهٔ اشتراک را باز می‌کند.',
                'en' => 'The publisher rests collapsed on «Share an update…»; focus opens the attach tools and the Share button.',
            ]],
            ['name' => 'like', 'type' => 'toggle', 'default' => "'count'", 'note' => [
                'fa' => 'قلبِ لایک شمارنده دارد و لمس دوباره برمی‌گرداند؛ اعداد فارسی.',
                'en' => 'The like heart carries a counter and untoggles on a second tap.',
            ]],
            ['name' => 'comment', 'type' => 'thread', 'default' => "'nested'", 'note' => [
                'fa' => 'کامنت‌ها زیر پست با ورودی «نظر بنویسید…» رشته می‌شوند.',
                'en' => 'Comments thread beneath the post above a «Write a comment…» input.',
            ]],
            ['name' => 'avatar', 'type' => 'initials', 'default' => "'2-letter'", 'note' => [
                'fa' => 'آواتار دایره‌ای با دو حرف اول نام کاربر؛ هر کاربر رنگ خودش.',
                'en' => 'A circular avatar of the user’s two initials; every user owns a colour.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <div class="sflx-feed">
                <form class="sflx-publisher">
                    <span class="sflx-avatar" data-user="me">من</span>
                    <input type="text" placeholder="اشتراک به‌روزرسانی…">
                    <button type="submit" class="sflx-btn" data-variant="brand">اشتراک</button>
                </form>
                <article class="sflx-post">
                    <header><span class="sflx-avatar" data-user="sara">سا</span><b>سارا احمدی</b><time>۲ ساعت پیش</time></header>
                    <p>پیش‌فاکتور را برای اکیوم فرستادم ✅</p>
                    <footer>
                        <button class="sflx-like" aria-pressed="false">♥ لایک ۳</button>
                        <button>نظر ۲</button>
                    </footer>
                </article>
            </div>

            <style>
            .sflx-publisher { display: flex; gap: .5rem; padding: .75rem 1rem; background: #fff;
                              border: 1px solid #DDDBDA; border-radius: .25rem; }
            .sflx-avatar { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                           border-radius: 50%; background: #0B5CAB; color: #fff; font-size: .7rem; }
            .sflx-post { margin-block-start: .75rem; padding: .75rem 1rem; background: #fff;
                         border: 1px solid #DDDBDA; border-radius: .25rem; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="sflx-feed">
                <form class="sflx-publisher">
                    <span class="sflx-avatar" data-user="me">YO</span>
                    <input type="text" placeholder="Share an update…">
                    <button type="submit" class="sflx-btn" data-variant="brand">Share</button>
                </form>
                <article class="sflx-post">
                    <header><span class="sflx-avatar" data-user="lena">LH</span><b>Lena Hoffmann</b><time>2 hours ago</time></header>
                    <p>Sent the revised quote to Acme ✅</p>
                    <footer>
                        <button class="sflx-like" aria-pressed="false">♥ Like 3</button>
                        <button>Comment 2</button>
                    </footer>
                </article>
            </div>

            <style>
            .sflx-publisher { display: flex; gap: .5rem; padding: .75rem 1rem; background: #fff;
                              border: 1px solid #DDDBDA; border-radius: .25rem; }
            .sflx-avatar { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                           border-radius: 50%; background: #0B5CAB; color: #fff; font-size: .7rem; }
            .sflx-post { margin-block-start: .75rem; padding: .75rem 1rem; background: #fff;
                         border: 1px solid #DDDBDA; border-radius: .25rem; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Opportunities/Feed.tsx — an Inertia page (React
            // flavor). Posts arrive from the backend as props; the feed itself is the
            // ChatterFeed component from the React snippet. For pagination use
            // router.visit(url, { preserveState: true }) or Inertia's usePrefetch.
            import ChatterFeed from '@/components/ChatterFeed';

            export default function OpportunityFeed() {
              // A real page would read its data: const { posts } = usePage().props;
              return <ChatterFeed />;
            }
            TSX,
            'react' => <<<'TSX'
            // ChatterFeed.tsx — the Chatter feed: a share-an-update publisher, posts
            // with initials avatars, like counters that untoggle, and comment threads
            // that grow through the inline composer. Mirrors the playground partial.
            import { type CSSProperties, useState } from 'react';
            import '@nabuxai/ui-react/css';

            type Comment = { who: string; initials: string; tint: string; time: string; body: string };
            type Post = Comment & { likes: number; liked: boolean; comments: Comment[] };

            const INITIAL: Post[] = [
              {
                who: 'Elif Demir', initials: 'ED', tint: '#0176D3', time: '2 hours ago',
                body: 'Sent the revised quote to Bosporus — waiting on their purchasing team now.',
                likes: 3, liked: false,
                comments: [
                  { who: 'Lena Fischer', initials: 'LF', tint: '#6739B7', time: '1 hour ago', body: 'Attached the final pricing sheet; the legal copy lands tomorrow.' },
                ],
              },
              {
                who: 'Marco Benzi', initials: 'MB', tint: '#04844B', time: 'yesterday',
                body: 'Got the discount approved by the sales director — we are clear up to 5%.',
                likes: 7, liked: true, comments: [],
              },
            ];

            const PATHS = {
              heart: 'M12 20s-7.5-4.6-7.5-10.2A4.3 4.3 0 0 1 12 7.1a4.3 4.3 0 0 1 7.5 2.7C19.5 15.4 12 20 12 20z',
              message: 'M4 5h16v11H9l-5 4z',
              paperclip: 'M20 11.5l-7.8 7.8a5 5 0 0 1-7.1-7.1l8-8a3.3 3.3 0 0 1 4.7 4.7l-8 8a1.7 1.7 0 0 1-2.4-2.4l7.3-7.3',
              image: 'M5.5 4h13A1.5 1.5 0 0 1 20 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-13A1.5 1.5 0 0 1 5.5 4zM9 10.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM20 15l-4.5-4.5L6 20',
            };

            const I = ({ d }: { d: string }) => (
              <svg className="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                strokeLinecap="round" strokeLinejoin="round" aria-hidden="true"><path d={d} /></svg>
            );

            export default function ChatterFeed() {
              const [draft, setDraft] = useState('');
              const [posts, setPosts] = useState(INITIAL);
              const [openComments, setOpenComments] = useState([true, false]);

              const share = () => {
                const body = draft.trim();
                if (!body) return;
                setPosts([{ who: 'You', initials: 'YO', tint: '#FE9339', time: 'just now', body, likes: 0, liked: false, comments: [] }, ...posts]);
                setOpenComments([false, ...openComments]);
                setDraft('');
              };
              const like = (i: number) => setPosts(posts.map((p, j) =>
                j === i ? { ...p, liked: !p.liked, likes: p.likes + (p.liked ? -1 : 1) } : p));
              const addComment = (i: number, input: HTMLInputElement) => {
                const body = input.value.trim();
                if (!body) return;
                setPosts(posts.map((p, j) => j === i
                  ? { ...p, comments: [...p.comments, { who: 'You', initials: 'YO', tint: '#FE9339', time: 'just now', body }] }
                  : p));
                input.value = '';
              };

              return (
                <div className="slf-root">
                  <div className="slf-feed">
                    <form className="slf-publisher" onSubmit={(e) => { e.preventDefault(); share(); }}>
                      <div className="slf-pub-row">
                        <span className="slf-ava" style={{ '--slf-tint': '#FE9339' } as CSSProperties} aria-hidden="true">YO</span>
                        <input type="text" value={draft} onChange={(e) => setDraft(e.target.value)}
                          placeholder="Share an update…" aria-label="Share an update" />
                      </div>
                      <div className="slf-tools">
                        <button type="button" className="slf-tool" aria-label="Attach a file"><I d={PATHS.paperclip} /></button>
                        <button type="button" className="slf-tool" aria-label="Add an image"><I d={PATHS.image} /></button>
                        <button type="submit" className="slf-share" disabled={!draft.trim()}>Share</button>
                      </div>
                    </form>

                    {posts.map((p, i) => (
                      <article key={p.who + i} className="slf-post">
                        <header className="slf-post-head">
                          <span className="slf-ava" style={{ '--slf-tint': p.tint } as CSSProperties} aria-hidden="true">{p.initials}</span>
                          <b>{p.who}</b>
                          <time>{p.time}</time>
                          <small>on the opportunity</small>
                        </header>
                        <p>{p.body}</p>
                        <div className="slf-acts">
                          <button type="button" className="slf-act" aria-pressed={p.liked} onClick={() => like(i)}>
                            <svg className="nx-icon" viewBox="0 0 24 24" fill={p.liked ? 'currentColor' : 'none'}
                              stroke="currentColor" strokeLinejoin="round" aria-hidden="true"><path d={PATHS.heart} /></svg>
                            <span>{p.likes > 0 ? p.likes : ''}</span>
                            Like
                          </button>
                          <button type="button" className="slf-act" aria-expanded={openComments[i]}
                            onClick={() => setOpenComments(openComments.map((o, j) => (j === i ? !o : o)))}>
                            <svg className="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                              strokeLinejoin="round" aria-hidden="true"><path d={PATHS.message} /></svg>
                            <span>{p.comments.length > 0 ? p.comments.length : ''}</span>
                            Comment
                          </button>
                        </div>
                        {openComments[i] && (
                          <div className="slf-comments">
                            {p.comments.map((c, j) => (
                              <div key={j} className="slf-comment">
                                <span className="slf-ava" style={{ '--slf-tint': c.tint } as CSSProperties} aria-hidden="true">{c.initials}</span>
                                <div className="slf-comment-body">
                                  <b>{c.who}</b><time>{c.time}</time>
                                  <p>{c.body}</p>
                                </div>
                              </div>
                            ))}
                            <div className="slf-compose">
                              <span className="slf-ava" aria-hidden="true">YO</span>
                              <input type="text" placeholder="Write a comment…" aria-label="Write a comment"
                                onKeyDown={(e) => e.key === 'Enter' && addComment(i, e.currentTarget)} />
                            </div>
                          </div>
                        )}
                      </article>
                    ))}

                    <p className="slf-empty">Older posts live in the full feed.</p>
                  </div>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .slf-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slf-blue: #0176D3; --slf-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
              --slf-muted: #706E6B; --slf-border: #DDDBDA; --slf-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .slf-root { color: #F3F3F3;
              --slf-blue: #0D9DDA; --slf-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
              --slf-muted: #A5A5A5; --slf-border: #474747; --slf-card: #232323; } }
            .slf-feed { max-inline-size: 40rem; margin-inline: auto; display: grid; gap: .75rem; }
            .slf-publisher { border: 1px solid var(--slf-border); border-radius: .25rem; background: var(--slf-card);
              padding: .75rem .85rem; display: grid; gap: .6rem; }
            .slf-pub-row { display: flex; align-items: center; gap: .6rem; }
            .slf-ava { display: grid; place-items: center; inline-size: 2rem; block-size: 2rem; border-radius: 50%;
              background: var(--slf-tint, var(--slf-blue)); color: #FFFFFF; font-size: .68rem; font-weight: 700; flex: none; }
            .slf-pub-row input { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: inherit;
              font: inherit; font-size: .88rem; }
            .slf-tools { display: flex; align-items: center; gap: .25rem; }
            .slf-tool { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border: 0;
              border-radius: .25rem; background: transparent; cursor: pointer; }
            .slf-share { margin-inline-start: auto; padding: .4rem 1.1rem; border: 1px solid var(--slf-blue);
              border-radius: .25rem; background: var(--slf-blue); color: #FFFFFF; font: inherit; font-size: .8rem;
              font-weight: 700; cursor: pointer; }
            .slf-share:disabled { opacity: .45; cursor: not-allowed; }
            .slf-post { border: 1px solid var(--slf-border); border-radius: .25rem; background: var(--slf-card);
              padding: .75rem .85rem; display: grid; gap: .55rem; }
            .slf-post-head { display: flex; align-items: center; gap: .6rem; }
            .slf-post-head b { font-size: .85rem; }
            .slf-post-head time, .slf-post-head small { font-size: .74rem; color: var(--slf-muted); }
            .slf-post-head small { margin-inline-start: auto; }
            .slf-post p { margin: 0; font-size: .88rem; line-height: 1.6; }
            .slf-acts { display: flex; gap: .4rem; }
            .slf-act { display: inline-flex; align-items: center; gap: .35rem; padding: .3rem .7rem; border: 0;
              border-radius: .25rem; background: transparent; color: var(--slf-muted); font: inherit; font-size: .78rem;
              font-weight: 600; cursor: pointer; }
            .slf-act:hover { background: var(--slf-blue-soft); color: var(--slf-blue); }
            .slf-act[aria-pressed='true'] { color: var(--slf-blue); }
            .slf-act .nx-icon { inline-size: 1em; block-size: 1em; }
            .slf-comments { border-block-start: 1px solid var(--slf-border); padding-block-start: .6rem;
              display: grid; gap: .55rem; }
            .slf-comment { display: flex; gap: .55rem; }
            .slf-comment .slf-ava { inline-size: 1.6rem; block-size: 1.6rem; font-size: .6rem; }
            .slf-comment-body { min-inline-size: 0; flex: 1; }
            .slf-comment-body b { font-size: .8rem; }
            .slf-comment-body time { font-size: .7rem; color: var(--slf-muted); margin-inline-start: .4rem; }
            .slf-comment-body p { margin: .1rem 0 0; font-size: .82rem; line-height: 1.55; }
            .slf-compose { display: flex; gap: .55rem; align-items: center; }
            .slf-compose .slf-ava { background: #6739B7; }
            .slf-compose input { flex: 1; min-inline-size: 0; padding: .45rem .6rem; border: 1px solid var(--slf-border);
              border-radius: .25rem; background: var(--slf-card); color: inherit; font: inherit; font-size: .8rem; }
            .slf-empty { text-align: center; color: var(--slf-muted); font-size: .82rem; padding: .4rem 0 0; }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- ChatterFeed.vue — the Chatter feed: a share-an-update publisher, posts
                 with initials avatars, like counters that untoggle, and comment threads
                 that grow through the inline composer. Mirrors the playground partial. -->
            <script setup lang="ts">
            import { ref } from 'vue';

            interface Comment { who: string; initials: string; tint: string; time: string; body: string }
            interface Post extends Comment { likes: number; liked: boolean; comments: Comment[] }

            const posts = ref<Post[]>([
              {
                who: 'Elif Demir', initials: 'ED', tint: '#0176D3', time: '2 hours ago',
                body: 'Sent the revised quote to Bosporus — waiting on their purchasing team now.',
                likes: 3, liked: false,
                comments: [
                  { who: 'Lena Fischer', initials: 'LF', tint: '#6739B7', time: '1 hour ago', body: 'Attached the final pricing sheet; the legal copy lands tomorrow.' },
                ],
              },
              {
                who: 'Marco Benzi', initials: 'MB', tint: '#04844B', time: 'yesterday',
                body: 'Got the discount approved by the sales director — we are clear up to 5%.',
                likes: 7, liked: true, comments: [],
              },
            ]);

            const draft = ref('');
            const openComments = ref([true, false]);

            const share = () => {
              const body = draft.value.trim();
              if (!body) return;
              posts.value = [{ who: 'You', initials: 'YO', tint: '#FE9339', time: 'just now', body, likes: 0, liked: false, comments: [] }, ...posts.value];
              openComments.value = [false, ...openComments.value];
              draft.value = '';
            };
            const like = (i: number) => {
              const p = posts.value[i];
              posts.value[i] = { ...p, liked: !p.liked, likes: p.likes + (p.liked ? -1 : 1) };
            };
            const addComment = (i: number, input: HTMLInputElement) => {
              const body = input.value.trim();
              if (!body) return;
              posts.value[i].comments.push({ who: 'You', initials: 'YO', tint: '#FE9339', time: 'just now', body });
              input.value = '';
            };
            </script>

            <template>
              <div class="slf-root">
                <div class="slf-feed">
                  <form class="slf-publisher" @submit.prevent="share">
                    <div class="slf-pub-row">
                      <span class="slf-ava" style="--slf-tint: #FE9339" aria-hidden="true">YO</span>
                      <input v-model="draft" type="text" placeholder="Share an update…" aria-label="Share an update" />
                    </div>
                    <div class="slf-tools">
                      <button type="submit" class="slf-share" :disabled="!draft.trim()">Share</button>
                    </div>
                  </form>

                  <article v-for="(p, i) in posts" :key="p.who + i" class="slf-post">
                    <header class="slf-post-head">
                      <span class="slf-ava" :style="{ '--slf-tint': p.tint }" aria-hidden="true">{{ p.initials }}</span>
                      <b>{{ p.who }}</b>
                      <time>{{ p.time }}</time>
                      <small>on the opportunity</small>
                    </header>
                    <p>{{ p.body }}</p>
                    <div class="slf-acts">
                      <button type="button" class="slf-act" :aria-pressed="p.liked" @click="like(i)">♥ {{ p.likes > 0 ? p.likes : '' }} Like</button>
                      <button type="button" class="slf-act" :aria-expanded="openComments[i]"
                        @click="openComments[i] = !openComments[i]">
                        ✉ {{ p.comments.length > 0 ? p.comments.length : '' }} Comment
                      </button>
                    </div>
                    <div v-if="openComments[i]" class="slf-comments">
                      <div v-for="(c, j) in p.comments" :key="j" class="slf-comment">
                        <span class="slf-ava" :style="{ '--slf-tint': c.tint }" aria-hidden="true">{{ c.initials }}</span>
                        <div class="slf-comment-body">
                          <b>{{ c.who }}</b><time>{{ c.time }}</time>
                          <p>{{ c.body }}</p>
                        </div>
                      </div>
                      <div class="slf-compose">
                        <span class="slf-ava" aria-hidden="true">YO</span>
                        <input type="text" placeholder="Write a comment…" aria-label="Write a comment"
                          @keydown.enter="addComment(i, $event.target as HTMLInputElement)" />
                      </div>
                    </div>
                  </article>

                  <p class="slf-empty">Older posts live in the full feed.</p>
                </div>
              </div>
            </template>

            <style scoped>
            .slf-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slf-blue: #0176D3; --slf-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
              --slf-muted: #706E6B; --slf-border: #DDDBDA; --slf-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .slf-root { color: #F3F3F3;
              --slf-blue: #0D9DDA; --slf-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
              --slf-muted: #A5A5A5; --slf-border: #474747; --slf-card: #232323; } }
            .slf-feed { max-inline-size: 40rem; margin-inline: auto; display: grid; gap: .75rem; }
            .slf-publisher { border: 1px solid var(--slf-border); border-radius: .25rem; background: var(--slf-card);
              padding: .75rem .85rem; display: grid; gap: .6rem; }
            .slf-pub-row { display: flex; align-items: center; gap: .6rem; }
            .slf-ava { display: grid; place-items: center; inline-size: 2rem; block-size: 2rem; border-radius: 50%;
              background: var(--slf-tint, var(--slf-blue)); color: #FFFFFF; font-size: .68rem; font-weight: 700; flex: none; }
            .slf-pub-row input { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: inherit;
              font: inherit; font-size: .88rem; }
            .slf-tools { display: flex; align-items: center; }
            .slf-share { margin-inline-start: auto; padding: .4rem 1.1rem; border: 1px solid var(--slf-blue);
              border-radius: .25rem; background: var(--slf-blue); color: #FFFFFF; font: inherit; font-size: .8rem;
              font-weight: 700; cursor: pointer; }
            .slf-share:disabled { opacity: .45; cursor: not-allowed; }
            .slf-post { border: 1px solid var(--slf-border); border-radius: .25rem; background: var(--slf-card);
              padding: .75rem .85rem; display: grid; gap: .55rem; }
            .slf-post-head { display: flex; align-items: center; gap: .6rem; }
            .slf-post-head b { font-size: .85rem; }
            .slf-post-head time, .slf-post-head small { font-size: .74rem; color: var(--slf-muted); }
            .slf-post-head small { margin-inline-start: auto; }
            .slf-post p { margin: 0; font-size: .88rem; line-height: 1.6; }
            .slf-acts { display: flex; gap: .4rem; }
            .slf-act { padding: .3rem .7rem; border: 0; border-radius: .25rem; background: transparent;
              color: var(--slf-muted); font: inherit; font-size: .78rem; font-weight: 600; cursor: pointer; }
            .slf-act:hover { background: var(--slf-blue-soft); color: var(--slf-blue); }
            .slf-act[aria-pressed='true'] { color: var(--slf-blue); }
            .slf-comments { border-block-start: 1px solid var(--slf-border); padding-block-start: .6rem;
              display: grid; gap: .55rem; }
            .slf-comment { display: flex; gap: .55rem; }
            .slf-comment .slf-ava { inline-size: 1.6rem; block-size: 1.6rem; font-size: .6rem; }
            .slf-comment-body { min-inline-size: 0; flex: 1; }
            .slf-comment-body b { font-size: .8rem; }
            .slf-comment-body time { font-size: .7rem; color: var(--slf-muted); margin-inline-start: .4rem; }
            .slf-comment-body p { margin: .1rem 0 0; font-size: .82rem; line-height: 1.55; }
            .slf-compose { display: flex; gap: .55rem; align-items: center; }
            .slf-compose .slf-ava { background: #6739B7; }
            .slf-compose input { flex: 1; min-inline-size: 0; padding: .45rem .6rem; border: 1px solid var(--slf-border);
              border-radius: .25rem; background: var(--slf-card); color: inherit; font: inherit; font-size: .8rem; }
            .slf-empty { text-align: center; color: var(--slf-muted); font-size: .82rem; padding: .4rem 0 0; }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- ChatterFeed.svelte — the Chatter feed: a share-an-update publisher,
                 posts with initials avatars, like counters that untoggle, and comment
                 threads that grow through the inline composer. Mirrors the partial. -->
            <script lang="ts">
              interface Comment { who: string; initials: string; tint: string; time: string; body: string }
              interface Post extends Comment { likes: number; liked: boolean; comments: Comment[] }

              let posts = $state<Post[]>([
                {
                  who: 'Elif Demir', initials: 'ED', tint: '#0176D3', time: '2 hours ago',
                  body: 'Sent the revised quote to Bosporus — waiting on their purchasing team now.',
                  likes: 3, liked: false,
                  comments: [
                    { who: 'Lena Fischer', initials: 'LF', tint: '#6739B7', time: '1 hour ago', body: 'Attached the final pricing sheet; the legal copy lands tomorrow.' },
                  ],
                },
                {
                  who: 'Marco Benzi', initials: 'MB', tint: '#04844B', time: 'yesterday',
                  body: 'Got the discount approved by the sales director — we are clear up to 5%.',
                  likes: 7, liked: true, comments: [],
                },
              ]);

              let draft = $state('');
              let openComments = $state([true, false]);

              const share = () => {
                const body = draft.trim();
                if (!body) return;
                posts = [{ who: 'You', initials: 'YO', tint: '#FE9339', time: 'just now', body, likes: 0, liked: false, comments: [] }, ...posts];
                openComments = [false, ...openComments];
                draft = '';
              };
              const like = (i: number) => {
                const p = posts[i];
                posts[i] = { ...p, liked: !p.liked, likes: p.likes + (p.liked ? -1 : 1) };
              };
              const addComment = (i: number, input: HTMLInputElement) => {
                const body = input.value.trim();
                if (!body) return;
                posts[i].comments.push({ who: 'You', initials: 'YO', tint: '#FE9339', time: 'just now', body });
                input.value = '';
              };
            </script>

            <div class="slf-root">
              <div class="slf-feed">
                <form class="slf-publisher" onsubmit={(e) => { e.preventDefault(); share(); }}>
                  <div class="slf-pub-row">
                    <span class="slf-ava" style:--slf-tint="#FE9339" aria-hidden="true">YO</span>
                    <input bind:value={draft} type="text" placeholder="Share an update…" aria-label="Share an update" />
                  </div>
                  <div class="slf-tools">
                    <button type="submit" class="slf-share" disabled={!draft.trim()}>Share</button>
                  </div>
                </form>

                {#each posts as p, i (p.who + i)}
                  <article class="slf-post">
                    <header class="slf-post-head">
                      <span class="slf-ava" style:--slf-tint={p.tint} aria-hidden="true">{p.initials}</span>
                      <b>{p.who}</b>
                      <time>{p.time}</time>
                      <small>on the opportunity</small>
                    </header>
                    <p>{p.body}</p>
                    <div class="slf-acts">
                      <button type="button" class="slf-act" aria-pressed={p.liked} onclick={() => like(i)}>
                        ♥ {p.likes > 0 ? p.likes : ''} Like
                      </button>
                      <button type="button" class="slf-act" aria-expanded={openComments[i]}
                        onclick={() => (openComments[i] = !openComments[i])}>
                        ✉ {p.comments.length > 0 ? p.comments.length : ''} Comment
                      </button>
                    </div>
                    {#if openComments[i]}
                      <div class="slf-comments">
                        {#each p.comments as c, j (j)}
                          <div class="slf-comment">
                            <span class="slf-ava" style:--slf-tint={c.tint} aria-hidden="true">{c.initials}</span>
                            <div class="slf-comment-body">
                              <b>{c.who}</b><time>{c.time}</time>
                              <p>{c.body}</p>
                            </div>
                          </div>
                        {/each}
                        <div class="slf-compose">
                          <span class="slf-ava" aria-hidden="true">YO</span>
                          <input type="text" placeholder="Write a comment…" aria-label="Write a comment"
                            onkeydown={(e) => e.key === 'Enter' && addComment(i, e.currentTarget)} />
                        </div>
                      </div>
                    {/if}
                  </article>
                {/each}

                <p class="slf-empty">Older posts live in the full feed.</p>
              </div>
            </div>

            <style>
            .slf-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slf-blue: #0176D3; --slf-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
              --slf-muted: #706E6B; --slf-border: #DDDBDA; --slf-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .slf-root { color: #F3F3F3;
              --slf-blue: #0D9DDA; --slf-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
              --slf-muted: #A5A5A5; --slf-border: #474747; --slf-card: #232323; } }
            .slf-feed { max-inline-size: 40rem; margin-inline: auto; display: grid; gap: .75rem; }
            .slf-publisher { border: 1px solid var(--slf-border); border-radius: .25rem; background: var(--slf-card);
              padding: .75rem .85rem; display: grid; gap: .6rem; }
            .slf-pub-row { display: flex; align-items: center; gap: .6rem; }
            .slf-ava { display: grid; place-items: center; inline-size: 2rem; block-size: 2rem; border-radius: 50%;
              background: var(--slf-tint, var(--slf-blue)); color: #FFFFFF; font-size: .68rem; font-weight: 700; flex: none; }
            .slf-pub-row input { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: inherit;
              font: inherit; font-size: .88rem; }
            .slf-tools { display: flex; align-items: center; }
            .slf-share { margin-inline-start: auto; padding: .4rem 1.1rem; border: 1px solid var(--slf-blue);
              border-radius: .25rem; background: var(--slf-blue); color: #FFFFFF; font: inherit; font-size: .8rem;
              font-weight: 700; cursor: pointer; }
            .slf-share:disabled { opacity: .45; cursor: not-allowed; }
            .slf-post { border: 1px solid var(--slf-border); border-radius: .25rem; background: var(--slf-card);
              padding: .75rem .85rem; display: grid; gap: .55rem; }
            .slf-post-head { display: flex; align-items: center; gap: .6rem; }
            .slf-post-head b { font-size: .85rem; }
            .slf-post-head time, .slf-post-head small { font-size: .74rem; color: var(--slf-muted); }
            .slf-post-head small { margin-inline-start: auto; }
            .slf-post p { margin: 0; font-size: .88rem; line-height: 1.6; }
            .slf-acts { display: flex; gap: .4rem; }
            .slf-act { padding: .3rem .7rem; border: 0; border-radius: .25rem; background: transparent;
              color: var(--slf-muted); font: inherit; font-size: .78rem; font-weight: 600; cursor: pointer; }
            .slf-act:hover { background: var(--slf-blue-soft); color: var(--slf-blue); }
            .slf-act[aria-pressed='true'] { color: var(--slf-blue); }
            .slf-comments { border-block-start: 1px solid var(--slf-border); padding-block-start: .6rem;
              display: grid; gap: .55rem; }
            .slf-comment { display: flex; gap: .55rem; }
            .slf-comment .slf-ava { inline-size: 1.6rem; block-size: 1.6rem; font-size: .6rem; }
            .slf-comment-body { min-inline-size: 0; flex: 1; }
            .slf-comment-body b { font-size: .8rem; }
            .slf-comment-body time { font-size: .7rem; color: var(--slf-muted); margin-inline-start: .4rem; }
            .slf-comment-body p { margin: .1rem 0 0; font-size: .82rem; line-height: 1.55; }
            .slf-compose { display: flex; gap: .55rem; align-items: center; }
            .slf-compose .slf-ava { background: #6739B7; }
            .slf-compose input { flex: 1; min-inline-size: 0; padding: .45rem .6rem; border: 1px solid var(--slf-border);
              border-radius: .25rem; background: var(--slf-card); color: inherit; font: inherit; font-size: .8rem; }
            .slf-empty { text-align: center; color: var(--slf-muted); font-size: .82rem; padding: .4rem 0 0; }
            </style>
            SVELTE,
        ],
    ],

    'dueling-picklist' => [
        'title' => ['fa' => 'دوگانه‌انتخاب (Dueling Picklist)', 'en' => 'Dueling Picklist'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'دو لیست «در دسترس» و «انتخاب‌شده» با فلش‌های انتقال؛ کلاسیک صفحه‌های پیکربندی ادمین سلس‌فورس.',
            'en' => 'Two listboxes with move arrows between them — the admin-configuration classic, straight from Lightning setup screens.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/dueling-picklist/',
        'props' => [
            ['name' => 'move', 'type' => 'buttons', 'default' => "'‹ ›'", 'note' => [
                'fa' => 'ستون فلش‌ها بین دو لیست: بردن به انتخاب‌شده، برگرداندن، و نسخهٔ دوتایی برای همه.',
                'en' => 'An arrow rail between the lists: move to chosen, move back, plus double arrows for all.',
            ]],
            ['name' => 'multi-select', 'type' => 'behaviour', 'default' => "'click'", 'note' => [
                'fa' => 'کلیک با Ctrl/⌘ چند گزینه را انتخاب نگه می‌دارد؛ فلش‌ها تا انتخاب غیرفعال‌اند.',
                'en' => 'Click with Ctrl/⌘ keeps several options selected; arrows stay disabled until then.',
            ]],
            ['name' => 'reorder', 'type' => 'buttons', 'default' => "'↑ ↓'", 'note' => [
                'fa' => 'فلش‌های بالا/پایین کنار لیست انتخاب‌شده، ترتیب را می‌چینند.',
                'en' => 'Up/down arrows beside the chosen list set the order.',
            ]],
            ['name' => 'option-meta', 'type' => 'label', 'default' => "'secondary'", 'note' => [
                'fa' => 'زیر هر گزینه، توضیح دوم خط با رنگ کم‌رنگ می‌آید.',
                'en' => 'A muted second line of meta rides under each option.',
            ]],
        ],
        'code' => [
            'livewire' => [
            'fa' => <<<'BLADE'
            <div class="sflx-duel">
                <fieldset>
                    <legend>در دسترس (۳)</legend>
                    <ul role="listbox" aria-multiselectable="true">
                        <li role="option" aria-selected="false">فرصت‌ها</li>
                        <li role="option" aria-selected="true">مخاطب‌ها</li>
                    </ul>
                </fieldset>
                <div class="sflx-duel-rail">
                    <button aria-label="بردن به انتخاب‌شده">›</button>
                    <button aria-label="برگرداندن به در دسترس">‹</button>
                </div>
                <fieldset>
                    <legend>انتخاب‌شده (۱)</legend>
                    <ul role="listbox"></ul>
                </fieldset>
            </div>

            <style>
            .sflx-duel { display: grid; grid-template-columns: 1fr auto 1fr; gap: 1rem; }
            .sflx-duel fieldset { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
            .sflx-duel li[aria-selected='true'] { background: #EAF5FE; box-shadow: inset 2px 0 0 #0176D3; }
            .sflx-duel-rail { display: grid; align-content: center; gap: .25rem; }
            .sflx-duel-rail button { inline-size: 2rem; block-size: 2rem; border: 1px solid #DDDBDA;
                                     border-radius: .25rem; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="sflx-duel">
                <fieldset>
                    <legend>Available (3)</legend>
                    <ul role="listbox" aria-multiselectable="true">
                        <li role="option" aria-selected="false">Opportunity name</li>
                        <li role="option" aria-selected="true">Contacts</li>
                    </ul>
                </fieldset>
                <div class="sflx-duel-rail">
                    <button aria-label="Move to Chosen">›</button>
                    <button aria-label="Move back to Available">‹</button>
                </div>
                <fieldset>
                    <legend>Chosen (1)</legend>
                    <ul role="listbox"></ul>
                </fieldset>
            </div>

            <style>
            .sflx-duel { display: grid; grid-template-columns: 1fr auto 1fr; gap: 1rem; }
            .sflx-duel fieldset { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
            .sflx-duel li[aria-selected='true'] { background: #EAF5FE; box-shadow: inset 2px 0 0 #0176D3; }
            .sflx-duel-rail { display: grid; align-content: center; gap: .25rem; }
            .sflx-duel-rail button { inline-size: 2rem; block-size: 2rem; border: 1px solid #DDDBDA;
                                     border-radius: .25rem; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'TSX'
            // resources/js/pages/Reports/Columns.tsx — an Inertia page (React flavor).
            // The field list arrives from the backend as props; saving posts through
            // Inertia: router.post('/reports/columns', { fields: chosen }). The picker
            // itself is the DuelingPicklist component from the React snippet.
            import DuelingPicklist from '@/components/DuelingPicklist';

            export default function ReportColumns() {
              // A real page would read its data: const { fields } = usePage().props;
              return <DuelingPicklist />;
            }
            TSX,
            'react' => <<<'TSX'
            // DuelingPicklist.tsx — «Available» and «Chosen» listboxes with multi-select,
            // the move rail, double arrows for everything, and reorder arrows inside the
            // chosen list. Mirrors the playground partial (slk-* classes).
            import { useState } from 'react';
            import '@nabuxai/ui-react/css';

            type Field = { name: string; meta: string };
            const ALL: Field[] = [
              { name: 'Opportunity name', meta: 'text' },
              { name: 'Stage', meta: 'picklist' },
              { name: 'Amount', meta: 'number' },
              { name: 'Close date', meta: 'date' },
              { name: 'Owner name', meta: 'lookup' },
              { name: 'Probability', meta: 'percent' },
              { name: 'Account', meta: 'lookup' },
              { name: 'Next step', meta: 'text' },
            ];

            export default function DuelingPicklist() {
              const [avail, setAvail] = useState<Field[]>(ALL.slice(4));
              const [chosen, setChosen] = useState<Field[]>(ALL.slice(0, 4));
              const [selA, setSelA] = useState<number[]>([]);
              const [selC, setSelC] = useState<number[]>([]);
              const [saved, setSaved] = useState('');

              const toggle = (which: 'a' | 'c', i: number) => {
                const sel = which === 'a' ? selA : selC;
                const setSel = which === 'a' ? setSelA : setSelC;
                setSel(sel.includes(i) ? sel.filter((j) => j !== i) : [...sel, i]);
                setSaved('');
              };
              // Move the selection (or everything) between the lists; splice from the
              // bottom up so the remaining indices stay valid.
              const take = (all: boolean) => {
                const picks = all ? avail.map((_, i) => i) : [...selA].sort((a, b) => b - a);
                setChosen([...chosen, ...picks.map((i) => avail[i])]);
                setAvail(avail.filter((_, i) => !picks.includes(i)));
                setSelA([]);
                setSaved('');
              };
              const give = (all: boolean) => {
                const picks = all ? chosen.map((_, i) => i) : [...selC].sort((a, b) => b - a);
                setAvail([...avail, ...picks.map((i) => chosen[i])]);
                setChosen(chosen.filter((_, i) => !picks.includes(i)));
                setSelC([]);
                setSaved('');
              };
              const shift = (dir: 'up' | 'down') => {
                const next = [...chosen];
                const order = [...selC].sort((a, b) => (dir === 'up' ? b - a : a - b));
                for (const i of order) {
                  const to = dir === 'up' ? i - 1 : i + 1;
                  if (to < 0 || to >= next.length) continue;
                  [next[i], next[to]] = [next[to], next[i]];
                }
                setChosen(next);
                setSaved('');
              };

              // One JSX row per option — a plain helper, not an inline component.
              const option = (f: Field, i: number, sel: boolean, which: 'a' | 'c') => (
                <li role="option" aria-selected={sel}>
                  <button type="button" className="slk-opt" onClick={() => toggle(which, i)}>
                    <b>{which === 'c' ? (i + 1) + '. ' + f.name : f.name}</b>
                    <small>{f.meta}</small>
                  </button>
                </li>
              );

              return (
                <div className="slk-root">
                  <form className="slk-duel" onSubmit={(e) => { e.preventDefault(); setSaved(chosen.length + ' columns saved to "Deals report"'); }}>
                    <fieldset className="slk-listbox">
                      <legend>Available <small>({avail.length})</small></legend>
                      <ul className="slk-options" role="listbox" aria-multiselectable="true" aria-label="Available fields">
                        {avail.map((f, i) => option(f, i, selA.includes(i), 'a'))}
                        {avail.length === 0 && <li className="slk-opt" style={{ color: 'var(--slk-muted)', cursor: 'default' }}>Everything is chosen</li>}
                      </ul>
                    </fieldset>

                    <div className="slk-rail" role="group" aria-label="Move fields">
                      <button type="button" className="slk-move" disabled={!selA.length}
                        aria-label="Move selection to Chosen" onClick={() => take(false)}>›</button>
                      <button type="button" className="slk-move" disabled={!avail.length}
                        aria-label="Move all to Chosen" onClick={() => take(true)}>»</button>
                      <button type="button" className="slk-move" disabled={!selC.length}
                        aria-label="Move selection back to Available" onClick={() => give(false)}>‹</button>
                      <button type="button" className="slk-move" disabled={!chosen.length}
                        aria-label="Move all back to Available" onClick={() => give(true)}>«</button>
                    </div>

                    <fieldset className="slk-listbox">
                      <legend>Chosen <small>({chosen.length})</small></legend>
                      <div className="slk-chosen">
                        <ul className="slk-options" role="listbox" aria-multiselectable="true" aria-label="Chosen fields">
                          {chosen.map((f, i) => option(f, i, selC.includes(i), 'c'))}
                          {chosen.length === 0 && <li className="slk-opt" style={{ color: 'var(--slk-muted)', cursor: 'default' }}>Nothing chosen yet</li>}
                        </ul>
                        <div className="slk-order" role="group" aria-label="Reorder chosen fields">
                          <button type="button" className="slk-move" disabled={!selC.length}
                            aria-label="Move up" onClick={() => shift('up')}>↑</button>
                          <button type="button" className="slk-move" disabled={!selC.length}
                            aria-label="Move down" onClick={() => shift('down')}>↓</button>
                        </div>
                      </div>
                    </fieldset>

                    <div className="slk-save">
                      <button type="button" className="slk-btn" onClick={() => give(true)}>Reset</button>
                      <button type="submit" className="slk-btn" data-variant="brand">Save layout</button>
                      {saved && <p className="slk-note"><b>✓</b> {saved}</p>}
                    </div>
                  </form>

                  <style>{css}</style>
                </div>
              );
            }

            const css = `
            .slk-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slk-blue: #0176D3; --slk-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
              --slk-green: #04844B; --slk-muted: #706E6B; --slk-border: #DDDBDA; --slk-bg: #F3F3F3;
              --slk-card: #FFFFFF; --slk-text: #181818; }
            @media (prefers-color-scheme: dark) { .slk-root { color: #F3F3F3; --slk-text: #F3F3F3;
              --slk-blue: #0D9DDA; --slk-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
              --slk-green: #0E9E5B; --slk-muted: #A5A5A5; --slk-border: #474747; --slk-bg: #181818; --slk-card: #232323; } }
            .slk-duel { max-inline-size: 46rem; margin-inline: auto; display: grid;
              grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr); gap: .75rem; align-items: start; }
            @media (max-width: 560px) { .slk-duel { grid-template-columns: 1fr; } }
            .slk-listbox { border: 1px solid var(--slk-border); border-radius: .25rem; background: var(--slk-card);
              padding: 0; margin: 0; }
            .slk-listbox legend { padding: .55rem .75rem .4rem; font-size: .82rem; font-weight: 700; display: flex; gap: .35rem; }
            .slk-listbox legend small { font-weight: 400; color: var(--slk-muted); }
            .slk-options { margin: 0; padding: 0 .25rem .35rem; list-style: none; max-block-size: 15rem; overflow-y: auto; }
            .slk-opt { border: 0; inline-size: 100%; background: transparent; color: inherit; font: inherit;
              cursor: pointer; padding: .45rem .6rem; border-radius: .25rem; text-align: start; }
            .slk-opt b { display: block; font-size: .84rem; font-weight: 600; }
            .slk-opt small { display: block; font-size: .72rem; color: var(--slk-muted); }
            .slk-opt:hover { background: var(--slk-bg); }
            .slk-chosen { display: flex; gap: .3rem; padding: 0 .4rem; }
            .slk-chosen .slk-options { flex: 1; }
            .slk-rail { display: grid; gap: .3rem; padding-block-start: 2.4rem; }
            .slk-move { display: grid; place-items: center; inline-size: 2.25rem; block-size: 2rem;
              border: 1px solid var(--slk-border); border-radius: .25rem; background: var(--slk-card);
              color: var(--slk-blue); cursor: pointer; font-size: 1rem; }
            .slk-move:hover:not(:disabled) { background: var(--slk-blue-soft); }
            .slk-move:disabled { color: color-mix(in srgb, var(--slk-text) 35%, transparent); cursor: not-allowed; }
            .slk-order { display: grid; gap: .3rem; padding-block-start: 2.4rem; }
            .slk-save { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; justify-content: center;
              grid-column: 1 / -1; margin-block-start: .35rem; }
            .slk-btn { padding: .45rem 1.1rem; border: 1px solid var(--slk-border); border-radius: .25rem;
              background: var(--slk-card); color: var(--slk-text); font: inherit; font-size: .84rem; cursor: pointer; }
            .slk-btn[data-variant='brand'] { background: var(--slk-blue); border-color: var(--slk-blue); color: #FFFFFF; font-weight: 600; }
            .slk-note { margin: 0; font-size: .78rem; color: var(--slk-muted); }
            .slk-note b { color: var(--slk-green); }
            `;
            TSX,
            'vue' => <<<'VUE'
            <!-- DuelingPicklist.vue — «Available» and «Chosen» listboxes with
                 multi-select, the move rail, double arrows for everything, and reorder
                 arrows inside the chosen list. Mirrors the playground partial. -->
            <script setup lang="ts">
            import { ref } from 'vue';

            interface Field { name: string; meta: string }
            const all: Field[] = [
              { name: 'Opportunity name', meta: 'text' },
              { name: 'Stage', meta: 'picklist' },
              { name: 'Amount', meta: 'number' },
              { name: 'Close date', meta: 'date' },
              { name: 'Owner name', meta: 'lookup' },
              { name: 'Probability', meta: 'percent' },
              { name: 'Account', meta: 'lookup' },
              { name: 'Next step', meta: 'text' },
            ];

            const avail = ref<Field[]>(all.slice(4));
            const chosen = ref<Field[]>(all.slice(0, 4));
            const selA = ref<number[]>([]);
            const selC = ref<number[]>([]);
            const saved = ref('');

            const toggle = (which: 'a' | 'c', i: number) => {
              const sel = which === 'a' ? selA : selC;
              sel.value = sel.value.includes(i) ? sel.value.filter((j) => j !== i) : [...sel.value, i];
              saved.value = '';
            };
            // Move the selection (or everything); splice from the bottom up so the
            // remaining indices stay valid.
            const take = (allItems: boolean) => {
              const picks = allItems ? avail.value.map((_, i) => i) : [...selA.value].sort((a, b) => b - a);
              chosen.value = [...chosen.value, ...picks.map((i) => avail.value[i])];
              avail.value = avail.value.filter((_, i) => !picks.includes(i));
              selA.value = [];
              saved.value = '';
            };
            const give = (allItems: boolean) => {
              const picks = allItems ? chosen.value.map((_, i) => i) : [...selC.value].sort((a, b) => b - a);
              avail.value = [...avail.value, ...picks.map((i) => chosen.value[i])];
              chosen.value = chosen.value.filter((_, i) => !picks.includes(i));
              selC.value = [];
              saved.value = '';
            };
            const shift = (dir: 'up' | 'down') => {
              const next = [...chosen.value];
              const order = [...selC.value].sort((a, b) => (dir === 'up' ? b - a : a - b));
              for (const i of order) {
                const to = dir === 'up' ? i - 1 : i + 1;
                if (to < 0 || to >= next.length) continue;
                [next[i], next[to]] = [next[to], next[i]];
              }
              chosen.value = next;
              saved.value = '';
            };
            const save = () => { saved.value = `${chosen.value.length} columns saved to "Deals report"`; };
            </script>

            <template>
              <div class="slk-root">
                <form class="slk-duel" @submit.prevent="save">
                  <fieldset class="slk-listbox">
                    <legend>Available <small>({{ avail.length }})</small></legend>
                    <ul class="slk-options" role="listbox" aria-multiselectable="true" aria-label="Available fields">
                      <li v-for="(f, i) in avail" :key="f.name" role="option" :aria-selected="selA.includes(i)">
                        <button type="button" class="slk-opt" @click="toggle('a', i)">
                          <b>{{ f.name }}</b>
                          <small>{{ f.meta }}</small>
                        </button>
                      </li>
                      <li v-if="avail.length === 0" class="slk-opt" style="color: var(--slk-muted); cursor: default">
                        Everything is chosen
                      </li>
                    </ul>
                  </fieldset>

                  <div class="slk-rail" role="group" aria-label="Move fields">
                    <button type="button" class="slk-move" :disabled="!selA.length" aria-label="Move selection to Chosen" @click="take(false)">›</button>
                    <button type="button" class="slk-move" :disabled="!avail.length" aria-label="Move all to Chosen" @click="take(true)">»</button>
                    <button type="button" class="slk-move" :disabled="!selC.length" aria-label="Move selection back to Available" @click="give(false)">‹</button>
                    <button type="button" class="slk-move" :disabled="!chosen.length" aria-label="Move all back to Available" @click="give(true)">«</button>
                  </div>

                  <fieldset class="slk-listbox">
                    <legend>Chosen <small>({{ chosen.length }})</small></legend>
                    <div class="slk-chosen">
                      <ul class="slk-options" role="listbox" aria-multiselectable="true" aria-label="Chosen fields">
                        <li v-for="(f, i) in chosen" :key="f.name" role="option" :aria-selected="selC.includes(i)">
                          <button type="button" class="slk-opt" @click="toggle('c', i)">
                            <b>{{ i + 1 }}. {{ f.name }}</b>
                            <small>{{ f.meta }}</small>
                          </button>
                        </li>
                        <li v-if="chosen.length === 0" class="slk-opt" style="color: var(--slk-muted); cursor: default">
                          Nothing chosen yet
                        </li>
                      </ul>
                      <div class="slk-order" role="group" aria-label="Reorder chosen fields">
                        <button type="button" class="slk-move" :disabled="!selC.length" aria-label="Move up" @click="shift('up')">↑</button>
                        <button type="button" class="slk-move" :disabled="!selC.length" aria-label="Move down" @click="shift('down')">↓</button>
                      </div>
                    </div>
                  </fieldset>

                  <div class="slk-save">
                    <button type="button" class="slk-btn" @click="give(true)">Reset</button>
                    <button type="submit" class="slk-btn" data-variant="brand">Save layout</button>
                    <p v-if="saved" class="slk-note"><b>✓</b> {{ saved }}</p>
                  </div>
                </form>
              </div>
            </template>

            <style scoped>
            .slk-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slk-blue: #0176D3; --slk-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
              --slk-green: #04844B; --slk-muted: #706E6B; --slk-border: #DDDBDA; --slk-bg: #F3F3F3;
              --slk-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .slk-root { color: #F3F3F3;
              --slk-blue: #0D9DDA; --slk-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
              --slk-green: #0E9E5B; --slk-muted: #A5A5A5; --slk-border: #474747; --slk-bg: #181818; --slk-card: #232323; } }
            .slk-duel { max-inline-size: 46rem; margin-inline: auto; display: grid;
              grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr); gap: .75rem; align-items: start; }
            @media (max-width: 560px) { .slk-duel { grid-template-columns: 1fr; } }
            .slk-listbox { border: 1px solid var(--slk-border); border-radius: .25rem; background: var(--slk-card);
              padding: 0; margin: 0; }
            .slk-listbox legend { padding: .55rem .75rem .4rem; font-size: .82rem; font-weight: 700; display: flex; gap: .35rem; }
            .slk-listbox legend small { font-weight: 400; color: var(--slk-muted); }
            .slk-options { margin: 0; padding: 0 .25rem .35rem; list-style: none; max-block-size: 15rem; overflow-y: auto; }
            .slk-opt { border: 0; inline-size: 100%; background: transparent; color: inherit; font: inherit;
              cursor: pointer; padding: .45rem .6rem; border-radius: .25rem; text-align: start; }
            .slk-opt b { display: block; font-size: .84rem; font-weight: 600; }
            .slk-opt small { display: block; font-size: .72rem; color: var(--slk-muted); }
            .slk-opt:hover { background: var(--slk-bg); }
            li[role='option'][aria-selected='true'] { background: var(--slk-blue-soft); }
            .slk-chosen { display: flex; gap: .3rem; padding: 0 .4rem; }
            .slk-chosen .slk-options { flex: 1; }
            .slk-rail { display: grid; gap: .3rem; padding-block-start: 2.4rem; }
            .slk-move { display: grid; place-items: center; inline-size: 2.25rem; block-size: 2rem;
              border: 1px solid var(--slk-border); border-radius: .25rem; background: var(--slk-card);
              color: var(--slk-blue); cursor: pointer; font-size: 1rem; }
            .slk-move:hover:not(:disabled) { background: var(--slk-blue-soft); }
            .slk-move:disabled { color: color-mix(in srgb, var(--slk-text, #181818) 35%, transparent); cursor: not-allowed; }
            .slk-order { display: grid; gap: .3rem; padding-block-start: 2.4rem; }
            .slk-save { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; justify-content: center;
              grid-column: 1 / -1; margin-block-start: .35rem; }
            .slk-btn { padding: .45rem 1.1rem; border: 1px solid var(--slk-border); border-radius: .25rem;
              background: var(--slk-card); color: inherit; font: inherit; font-size: .84rem; cursor: pointer; }
            .slk-btn[data-variant='brand'] { background: var(--slk-blue); border-color: var(--slk-blue); color: #FFFFFF; font-weight: 600; }
            .slk-note { margin: 0; font-size: .78rem; color: var(--slk-muted); }
            .slk-note b { color: var(--slk-green); }
            </style>
            VUE,
            'svelte' => <<<'SVELTE'
            <!-- DuelingPicklist.svelte — «Available» and «Chosen» listboxes with
                 multi-select, the move rail, double arrows for everything, and reorder
                 arrows inside the chosen list. Mirrors the playground partial. -->
            <script lang="ts">
              interface Field { name: string; meta: string }
              const all: Field[] = [
                { name: 'Opportunity name', meta: 'text' },
                { name: 'Stage', meta: 'picklist' },
                { name: 'Amount', meta: 'number' },
                { name: 'Close date', meta: 'date' },
                { name: 'Owner name', meta: 'lookup' },
                { name: 'Probability', meta: 'percent' },
                { name: 'Account', meta: 'lookup' },
                { name: 'Next step', meta: 'text' },
              ];

              let avail = $state<Field[]>(all.slice(4));
              let chosen = $state<Field[]>(all.slice(0, 4));
              let selA = $state<number[]>([]);
              let selC = $state<number[]>([]);
              let saved = $state('');

              const toggle = (which: 'a' | 'c', i: number) => {
                const sel = which === 'a' ? selA : selC;
                const next = sel.includes(i) ? sel.filter((j) => j !== i) : [...sel, i];
                if (which === 'a') selA = next; else selC = next;
                saved = '';
              };
              // Move the selection (or everything); splice from the bottom up so the
              // remaining indices stay valid.
              const take = (everything: boolean) => {
                const picks = everything ? avail.map((_, i) => i) : [...selA].sort((a, b) => b - a);
                chosen = [...chosen, ...picks.map((i) => avail[i])];
                avail = avail.filter((_, i) => !picks.includes(i));
                selA = [];
                saved = '';
              };
              const give = (everything: boolean) => {
                const picks = everything ? chosen.map((_, i) => i) : [...selC].sort((a, b) => b - a);
                avail = [...avail, ...picks.map((i) => chosen[i])];
                chosen = chosen.filter((_, i) => !picks.includes(i));
                selC = [];
                saved = '';
              };
              const shift = (dir: 'up' | 'down') => {
                const next = [...chosen];
                const order = [...selC].sort((a, b) => (dir === 'up' ? b - a : a - b));
                for (const i of order) {
                  const to = dir === 'up' ? i - 1 : i + 1;
                  if (to < 0 || to >= next.length) continue;
                  [next[i], next[to]] = [next[to], next[i]];
                }
                chosen = next;
                saved = '';
              };
              const save = () => { saved = `${chosen.length} columns saved to "Deals report"`; };
            </script>

            <form class="slk-duel" onsubmit={(e) => { e.preventDefault(); save(); }}>
              <fieldset class="slk-listbox">
                <legend>Available <small>({avail.length})</small></legend>
                <ul class="slk-options" role="listbox" aria-multiselectable="true" aria-label="Available fields">
                  {#each avail as f, i (f.name)}
                    <li role="option" aria-selected={selA.includes(i)}>
                      <button type="button" class="slk-opt" onclick={() => toggle('a', i)}>
                        <b>{f.name}</b>
                        <small>{f.meta}</small>
                      </button>
                    </li>
                  {/each}
                  {#if avail.length === 0}
                    <li class="slk-opt" style="color: var(--slk-muted); cursor: default">Everything is chosen</li>
                  {/if}
                </ul>
              </fieldset>

              <div class="slk-rail" role="group" aria-label="Move fields">
                <button type="button" class="slk-move" disabled={!selA.length} aria-label="Move selection to Chosen" onclick={() => take(false)}>›</button>
                <button type="button" class="slk-move" disabled={!avail.length} aria-label="Move all to Chosen" onclick={() => take(true)}>»</button>
                <button type="button" class="slk-move" disabled={!selC.length} aria-label="Move selection back to Available" onclick={() => give(false)}>‹</button>
                <button type="button" class="slk-move" disabled={!chosen.length} aria-label="Move all back to Available" onclick={() => give(true)}>«</button>
              </div>

              <fieldset class="slk-listbox">
                <legend>Chosen <small>({chosen.length})</small></legend>
                <div class="slk-chosen">
                  <ul class="slk-options" role="listbox" aria-multiselectable="true" aria-label="Chosen fields">
                    {#each chosen as f, i (f.name)}
                      <li role="option" aria-selected={selC.includes(i)}>
                        <button type="button" class="slk-opt" onclick={() => toggle('c', i)}>
                          <b>{i + 1}. {f.name}</b>
                          <small>{f.meta}</small>
                        </button>
                      </li>
                    {/each}
                    {#if chosen.length === 0}
                      <li class="slk-opt" style="color: var(--slk-muted); cursor: default">Nothing chosen yet</li>
                    {/if}
                  </ul>
                  <div class="slk-order" role="group" aria-label="Reorder chosen fields">
                    <button type="button" class="slk-move" disabled={!selC.length} aria-label="Move up" onclick={() => shift('up')}>↑</button>
                    <button type="button" class="slk-move" disabled={!selC.length} aria-label="Move down" onclick={() => shift('down')}>↓</button>
                  </div>
                </div>
              </fieldset>

              <div class="slk-save">
                <button type="button" class="slk-btn" onclick={() => give(true)}>Reset</button>
                <button type="submit" class="slk-btn" data-variant="brand">Save layout</button>
                {#if saved}<p class="slk-note"><b>✓</b> {saved}</p>{/if}
              </div>
            </form>

            <style>
            .slk-root { font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif; color: #181818;
              --slk-blue: #0176D3; --slk-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
              --slk-green: #04844B; --slk-muted: #706E6B; --slk-border: #DDDBDA; --slk-bg: #F3F3F3;
              --slk-card: #FFFFFF; }
            @media (prefers-color-scheme: dark) { .slk-root { color: #F3F3F3;
              --slk-blue: #0D9DDA; --slk-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
              --slk-green: #0E9E5B; --slk-muted: #A5A5A5; --slk-border: #474747; --slk-bg: #181818; --slk-card: #232323; } }
            .slk-duel { max-inline-size: 46rem; margin-inline: auto; display: grid;
              grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr); gap: .75rem; align-items: start; }
            @media (max-width: 560px) { .slk-duel { grid-template-columns: 1fr; } }
            .slk-listbox { border: 1px solid var(--slk-border); border-radius: .25rem; background: var(--slk-card);
              padding: 0; margin: 0; }
            .slk-listbox legend { padding: .55rem .75rem .4rem; font-size: .82rem; font-weight: 700; display: flex; gap: .35rem; }
            .slk-listbox legend small { font-weight: 400; color: var(--slk-muted); }
            .slk-options { margin: 0; padding: 0 .25rem .35rem; list-style: none; max-block-size: 15rem; overflow-y: auto; }
            .slk-opt { border: 0; inline-size: 100%; background: transparent; color: inherit; font: inherit;
              cursor: pointer; padding: .45rem .6rem; border-radius: .25rem; text-align: start; }
            .slk-opt b { display: block; font-size: .84rem; font-weight: 600; }
            .slk-opt small { display: block; font-size: .72rem; color: var(--slk-muted); }
            .slk-opt:hover { background: var(--slk-bg); }
            li[role='option'][aria-selected='true'] { background: var(--slk-blue-soft); }
            .slk-chosen { display: flex; gap: .3rem; padding: 0 .4rem; }
            .slk-chosen .slk-options { flex: 1; }
            .slk-rail { display: grid; gap: .3rem; padding-block-start: 2.4rem; }
            .slk-move { display: grid; place-items: center; inline-size: 2.25rem; block-size: 2rem;
              border: 1px solid var(--slk-border); border-radius: .25rem; background: var(--slk-card);
              color: var(--slk-blue); cursor: pointer; font-size: 1rem; }
            .slk-move:hover:not(:disabled) { background: var(--slk-blue-soft); }
            .slk-move:disabled { color: color-mix(in srgb, #181818 35%, transparent); cursor: not-allowed; }
            .slk-order { display: grid; gap: .3rem; padding-block-start: 2.4rem; }
            .slk-save { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; justify-content: center;
              grid-column: 1 / -1; margin-block-start: .35rem; }
            .slk-btn { padding: .45rem 1.1rem; border: 1px solid var(--slk-border); border-radius: .25rem;
              background: var(--slk-card); color: inherit; font: inherit; font-size: .84rem; cursor: pointer; }
            .slk-btn[data-variant='brand'] { background: var(--slk-blue); border-color: var(--slk-blue); color: #FFFFFF; font-weight: 600; }
            .slk-note { margin: 0; font-size: .78rem; color: var(--slk-muted); }
            .slk-note b { color: var(--slk-green); }
            </style>
            SVELTE,
        ],
    ],
];
