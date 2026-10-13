/** Showcase section: the framework switcher's home — three blocks in all five modes. */
import { useState } from 'react';
import { ChipFilter, SortPill, StatStrip } from '@nabuxai/ui-react';
import { Demo, Section, Snippet } from '../Section';
import { useTr } from '../lang';

/* The real React usage of the block (packages/react/src/blocks/chip-filter.tsx). */
const CHIP_REACT = `import { ChipFilter } from '@nabuxai/ui-react';
import { useState } from 'react';

const [tag, setTag] = useState('all');

<ChipFilter
  aria-label="فیلتر دسته‌ها"
  value={tag}
  onValueChange={setTag}
  items={[
    { value: 'all', label: 'همه' },
    { value: 'guide', label: 'راهنما', icon: 'edit', count: 12 },
    { value: 'component', label: 'کامپوننت', icon: 'grid', count: 48 },
    { value: 'block', label: 'بلوک', icon: 'layers', count: 27 },
  ]}
/>`;

/* The real Blade component's invocation (packages/livewire/.../chip-filter.blade.php). */
const CHIP_BLADE = `<x-nx::chip-filter
    :options="['all' => 'همه', 'guide' => ['label' => 'راهنما', 'icon' => 'edit'], 'component' => ['label' => 'کامپوننت', 'icon' => 'grid'], 'block' => ['label' => 'بلوک', 'icon' => 'layers']]"
    :counts="['guide' => 12, 'component' => 48, 'block' => 27]"
    wire:model.live="tag"
/>`;

/* Vue SFC: the Blade markup without the Blade/Alpine directives, state in a ref,
   the accent thumb driven by the core indicator behaviour. */
const CHIP_VUE = `<script setup>
import { indicator } from '@nabuxai/ui-core';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const items = [
  { value: 'all', label: 'همه' },
  { value: 'guide', label: 'راهنما', count: 12 },
  { value: 'component', label: 'کامپوننت', count: 48 },
  { value: 'block', label: 'بلوک', count: 27 },
];
const tag = ref('all');
const row = ref(null);
let thumb;

// The accent thumb under the checked chip is the core indicator behaviour.
function moveThumb() {
  thumb?.update(row.value?.querySelector('.nx-chip-filter-chip:has(:checked)') ?? null);
}

onMounted(() => {
  thumb = indicator(row.value);
  moveThumb();
});
onBeforeUnmount(() => thumb?.destroy());
</script>

<template>
  <div class="nx-chip-filter" role="group" aria-label="فیلتر دسته‌ها">
    <div ref="row" class="nx-chip-filter-row">
      <span class="nx-indicator nx-chip-filter-thumb" aria-hidden="true"></span>
      <label v-for="item in items" :key="item.value" class="nx-chip-filter-chip">
        <input v-model="tag" class="nx-chip-filter-input" type="radio" name="tag" :value="item.value" @change="moveThumb" />
        <span>{{ item.label }}</span>
        <span v-if="item.count" class="nx-chip-filter-count">{{ item.count }}</span>
      </label>
    </div>
  </div>
</template>

<style>
@import '@nabuxai/ui-core/css';
</style>`;

/* Svelte 5 runes: same markup and core behaviour, state in $state. */
const CHIP_SVELTE = `<script>
  import { indicator } from '@nabuxai/ui-core';
  import { onMount } from 'svelte';

  const items = [
    { value: 'all', label: 'همه' },
    { value: 'guide', label: 'راهنما', count: 12 },
    { value: 'component', label: 'کامپوننت', count: 48 },
    { value: 'block', label: 'بلوک', count: 27 },
  ];

  let tag = $state('all');
  let row = $state();
  let thumb;

  // The accent thumb under the checked chip is the core indicator behaviour.
  function moveThumb() {
    thumb?.update(row?.querySelector('.nx-chip-filter-chip:has(:checked)') ?? null);
  }

  onMount(() => {
    thumb = indicator(row);
    moveThumb();
    return () => thumb?.destroy();
  });
</script>

<div class="nx-chip-filter" role="group" aria-label="فیلتر دسته‌ها">
  <div bind:this={row} class="nx-chip-filter-row">
    <span class="nx-indicator nx-chip-filter-thumb" aria-hidden="true"></span>
    {#each items as item (item.value)}
      <label class="nx-chip-filter-chip">
        <input bind:group={tag} class="nx-chip-filter-input" type="radio" name="tag" value={item.value} onchange={moveThumb} />
        <span>{item.label}</span>
        {#if item.count}<span class="nx-chip-filter-count">{item.count}</span>{/if}
      </label>
    {/each}
  </div>
</div>

<style>
  @import '@nabuxai/ui-core/css';
</style>`;

/* The real React usage of the block (packages/react/src/blocks/stat-strip.tsx). */
const STAT_REACT = `import { StatStrip } from '@nabuxai/ui-react';

<StatStrip
  aria-label="آمار مستندات"
  stats={[
    { label: 'کامپوننت‌ها', value: 48, icon: 'grid' },
    { label: 'بلوک‌ها', value: 14, icon: 'layers', caption: '+۲ این ماه' },
    { label: 'فریم‌ورک‌ها', value: 5, icon: 'globe' },
    { label: 'تم‌ها', value: 2, icon: 'moon' },
  ]}
/>`;

/* The real Blade component's invocation (packages/livewire/.../stat-strip.blade.php). */
const STAT_BLADE = `<x-nx::stat-strip :stats="[
    ['label' => 'کامپوننت‌ها', 'value' => 48, 'icon' => 'grid'],
    ['label' => 'بلوک‌ها', 'value' => 14, 'icon' => 'layers', 'caption' => '+۲ این ماه'],
    ['label' => 'فریم‌ورک‌ها', 'value' => 5, 'icon' => 'globe'],
    ['label' => 'تم‌ها', 'value' => 2, 'icon' => 'moon'],
]" />`;

/* Vue SFC: the Blade markup without the Blade/Alpine directives; the digit
   roll is the same nx-number structure, revealed by the core reveal behaviour. */
const STAT_VUE = `<script setup>
import { formatNumber, localeDigits, numberParts, reveal } from '@nabuxai/ui-core';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const locale = 'fa-IR';
const digits = localeDigits(locale);
const stats = [
  { label: 'کامپوننت‌ها', value: 48 },
  { label: 'بلوک‌ها', value: 14, caption: '+۲ این ماه' },
  { label: 'فریم‌ورک‌ها', value: 5 },
  { label: 'تم‌ها', value: 2 },
];

// Digit columns for the roll: the digit's value 0–9 and its position from the right.
function roll(value) {
  const parts = numberParts(value, locale);
  return parts.map((part, i) => ({ ...part, depth: parts.length - 1 - i }));
}

const root = ref(null);
let stops = [];

onMounted(() => {
  // The group reveal raises the items; each figure reveals its own digits,
  // otherwise the CSS holds every track at zero.
  stops.push(reveal(root.value, { once: true, stagger: true }));
  for (const el of root.value.querySelectorAll('.nx-number[data-nx-reveal]')) stops.push(reveal(el, { once: true }));
});
onBeforeUnmount(() => stops.forEach((stop) => stop()));
</script>

<template>
  <dl ref="root" class="nx-stat-strip" aria-label="آمار مستندات" data-nx-reveal="group">
    <div v-for="(stat, i) in stats" :key="stat.label" class="nx-stat-strip-item" :style="{ '--nx-i': i }">
      <div class="nx-stat-strip-what">
        <dt class="nx-stat-strip-label">{{ stat.label }}</dt>
        <dd class="nx-stat-strip-value">
          <span class="nx-number" data-nx-reveal :data-value="stat.value">
            <span class="nx-visually-hidden">{{ formatNumber(stat.value, locale) }}</span>
            <span class="nx-number-roll" aria-hidden="true">
              <template v-for="(part, j) in roll(stat.value)" :key="j">
                <span v-if="part.kind === 'digit'" class="nx-digit" :style="{ '--d': part.value, '--nx-p': part.depth }">
                  <span class="nx-digit-track"><span v-for="d in digits" :key="d">{{ d }}</span></span>
                </span>
                <span v-else class="nx-number-sep">{{ part.char }}</span>
              </template>
            </span>
          </span>
          <span v-if="stat.caption" class="nx-stat-strip-caption">{{ stat.caption }}</span>
        </dd>
      </div>
    </div>
  </dl>
</template>

<style>
@import '@nabuxai/ui-core/css';
</style>`;

/* Svelte 5 runes: same markup and core behaviours, state in $state. */
const STAT_SVELTE = `<script>
  import { formatNumber, localeDigits, numberParts, reveal } from '@nabuxai/ui-core';
  import { onMount } from 'svelte';

  const locale = 'fa-IR';
  const digits = localeDigits(locale);
  const stats = [
    { label: 'کامپوننت‌ها', value: 48 },
    { label: 'بلوک‌ها', value: 14, caption: '+۲ این ماه' },
    { label: 'فریم‌ورک‌ها', value: 5 },
    { label: 'تم‌ها', value: 2 },
  ];

  // Digit columns for the roll: the digit's value 0–9 and its position from the right.
  const roll = (value) => {
    const parts = numberParts(value, locale);
    return parts.map((part, i) => ({ ...part, depth: parts.length - 1 - i }));
  };

  let root = $state();

  onMount(() => {
    // The group reveal raises the items; each figure reveals its own digits,
    // otherwise the CSS holds every track at zero.
    const stops = [reveal(root, { once: true, stagger: true })];
    for (const el of root.querySelectorAll('.nx-number[data-nx-reveal]')) stops.push(reveal(el, { once: true }));
    return () => stops.forEach((stop) => stop());
  });
</script>

<dl bind:this={root} class="nx-stat-strip" aria-label="آمار مستندات" data-nx-reveal="group">
  {#each stats as stat, i (stat.label)}
    <div class="nx-stat-strip-item" style="--nx-i: {i}">
      <div class="nx-stat-strip-what">
        <dt class="nx-stat-strip-label">{stat.label}</dt>
        <dd class="nx-stat-strip-value">
          <span class="nx-number" data-nx-reveal data-value={stat.value}>
            <span class="nx-visually-hidden">{formatNumber(stat.value, locale)}</span>
            <span class="nx-number-roll" aria-hidden="true">
              {#each roll(stat.value) as part, j}
                {#if part.kind === 'digit'}
                  <span class="nx-digit" style="--d: {part.value}; --nx-p: {part.depth}">
                    <span class="nx-digit-track">{#each digits as d (d)}<span>{d}</span>{/each}</span>
                  </span>
                {:else}
                  <span class="nx-number-sep">{part.char}</span>
                {/if}
              {/each}
            </span>
          </span>
          {#if stat.caption}<span class="nx-stat-strip-caption">{stat.caption}</span>{/if}
        </dd>
      </div>
    </div>
  {/each}
</dl>

<style>
  @import '@nabuxai/ui-core/css';
</style>`;

/* The real React usage of the block (packages/react/src/blocks/sort-pill.tsx). */
const SORT_REACT = `import { SortPill } from '@nabuxai/ui-react';
import { useState } from 'react';

const [sort, setSort] = useState('recent');

<SortPill
  label="مرتب‌سازی"
  value={sort}
  onValueChange={setSort}
  options={[
    { value: 'recent', label: 'تازه‌ها', icon: 'sparkles' },
    { value: 'popular', label: 'محبوب‌ها', icon: 'heart' },
    { value: 'top', label: 'بالاترین امتیاز', icon: 'trend-up' },
  ]}
/>`;

/* The real Blade component's invocation (packages/livewire/.../sort-pill.blade.php). */
const SORT_BLADE = `<x-nx::sort-pill
    :options="['recent' => ['label' => 'تازه‌ها', 'icon' => 'sparkles'], 'popular' => ['label' => 'محبوب‌ها', 'icon' => 'heart'], 'top' => ['label' => 'بالاترین امتیاز', 'icon' => 'trend-up']]"
    wire:model.live="sort"
/>`;

/* Vue SFC: the Blade markup without the Blade/Alpine directives; the panel is a
   native popover, positioned against the trigger by the core place behaviour. */
const SORT_VUE = `<script setup>
import { place, roveFocus } from '@nabuxai/ui-core';
import { ref } from 'vue';

const options = [
  { value: 'recent', label: 'تازه‌ها' },
  { value: 'popular', label: 'محبوب‌ها' },
  { value: 'top', label: 'بالاترین امتیاز' },
];
const sort = ref('recent');
const open = ref(false);
const trigger = ref(null);
const panel = ref(null);

// The native popover opens the panel; core place() pins it under the trigger.
function onToggle(event) {
  open.value = event.newState === 'open';
  if (open.value) place(trigger.value, panel.value, { side: 'bottom', offset: 8 });
}

function choose(value) {
  sort.value = value;
  // Let the label finish rolling before the panel folds away.
  setTimeout(() => {
    if (panel.value?.matches(':popover-open')) panel.value.hidePopover();
  }, 300);
}
</script>

<template>
  <button
    ref="trigger"
    type="button"
    class="nx-sort-pill-trigger"
    popovertarget="sort-pill"
    aria-controls="sort-pill"
    aria-haspopup="listbox"
    :aria-expanded="open ? 'true' : 'false'"
    :aria-label="'مرتب‌سازی: ' + (options.find((o) => o.value === sort)?.label ?? '')"
  >
    <span class="nx-sort-pill-labels" aria-hidden="true">
      <span v-for="option in options" :key="option.value" class="nx-sort-pill-label" :data-current="sort === option.value ? '' : null">
        {{ option.label }}
      </span>
    </span>
    <span class="nx-sort-pill-chevron" aria-hidden="true">
      <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6" /></svg>
    </span>
  </button>
  <div
    id="sort-pill"
    ref="panel"
    class="nx-sort-pill"
    popover="auto"
    @toggle="onToggle"
    @keydown="roveFocus($event, $event.currentTarget, '.nx-sort-pill-choice', { orientation: 'vertical' })"
  >
    <ul class="nx-sort-pill-list" role="listbox" aria-label="مرتب‌سازی">
      <li v-for="option in options" :key="option.value" class="nx-sort-pill-option" role="presentation" :data-selected="sort === option.value ? '' : null">
        <button type="button" class="nx-sort-pill-choice" role="option" :aria-selected="sort === option.value ? 'true' : 'false'" @click="choose(option.value)">
          <span>{{ option.label }}</span>
          <span class="nx-sort-pill-mark" aria-hidden="true">
            <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 12.5l5 5L19.5 6.5" /></svg>
          </span>
        </button>
      </li>
    </ul>
  </div>
</template>

<style>
@import '@nabuxai/ui-core/css';
</style>`;

/* Svelte 5 runes: same markup and core behaviours, state in $state. */
const SORT_SVELTE = `<script>
  import { place, roveFocus } from '@nabuxai/ui-core';

  const options = [
    { value: 'recent', label: 'تازه‌ها' },
    { value: 'popular', label: 'محبوب‌ها' },
    { value: 'top', label: 'بالاترین امتیاز' },
  ];

  let sort = $state('recent');
  let open = $state(false);
  let trigger = $state();
  let panel = $state();

  // The native popover opens the panel; core place() pins it under the trigger.
  function onToggle(event) {
    open = event.newState === 'open';
    if (open) place(trigger, panel, { side: 'bottom', offset: 8 });
  }

  function choose(value) {
    sort = value;
    // Let the label finish rolling before the panel folds away.
    setTimeout(() => {
      if (panel?.matches(':popover-open')) panel.hidePopover();
    }, 300);
  }
</script>

<button
  bind:this={trigger}
  type="button"
  class="nx-sort-pill-trigger"
  popovertarget="sort-pill"
  aria-controls="sort-pill"
  aria-haspopup="listbox"
  aria-expanded={open ? 'true' : 'false'}
  aria-label={'مرتب‌سازی: ' + (options.find((o) => o.value === sort)?.label ?? '')}
>
  <span class="nx-sort-pill-labels" aria-hidden="true">
    {#each options as option (option.value)}
      <span class="nx-sort-pill-label" data-current={sort === option.value ? '' : null}>{option.label}</span>
    {/each}
  </span>
  <span class="nx-sort-pill-chevron" aria-hidden="true">
    <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6" /></svg>
  </span>
</button>
<div
  bind:this={panel}
  id="sort-pill"
  class="nx-sort-pill"
  popover="auto"
  ontoggle={onToggle}
  onkeydown={(event) => roveFocus(event, event.currentTarget, '.nx-sort-pill-choice', { orientation: 'vertical' })}
>
  <ul class="nx-sort-pill-list" role="listbox" aria-label="مرتب‌سازی">
    {#each options as option (option.value)}
      <li class="nx-sort-pill-option" role="presentation" data-selected={sort === option.value ? '' : null}>
        <button type="button" class="nx-sort-pill-choice" role="option" aria-selected={sort === option.value ? 'true' : 'false'} onclick={() => choose(option.value)}>
          <span>{option.label}</span>
          <span class="nx-sort-pill-mark" aria-hidden="true">
            <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 12.5l5 5L19.5 6.5" /></svg>
          </span>
        </button>
      </li>
    {/each}
  </ul>
</div>

<style>
  @import '@nabuxai/ui-core/css';
</style>`;

export function FrameworksSection() {
  const tr = useTr();
  const [tag, setTag] = useState('all');
  const [sort, setSort] = useState('recent');

  return (
    <Section
      id="frameworks"
      eyebrow={tr('فریم‌ورک‌ها', 'Frameworks')}
      title={tr('یک هسته، پنج فریم‌ورک', 'One core, five frameworks')}
      description={tr(
        'سوییچ بالای صفحه، نمونه‌کد همهٔ سکشن‌ها را عوض می‌کند. اینجا سه بلوک را در هر پنج حالت می‌بینید: Vue و Svelte همان هستهٔ CSS را مصرف می‌کنند و markup پایه‌شان همین خروجی Blade است.',
        'The switcher in the header swaps the snippets of every section. Here are three blocks in all five modes: Vue and Svelte consume the same core CSS, and their base markup is this Blade output.',
      )}
    >
      <div className="sc-demos">
        <Demo title={tr('فیلتر چیپی — دسته‌های مستندات', 'Chip filter — the docs categories')}>
          <ChipFilter
            aria-label={tr('فیلتر دسته‌ها', 'Category filter')}
            value={tag}
            onValueChange={setTag}
            items={[
              { value: 'all', label: tr('همه', 'All') },
              { value: 'guide', label: tr('راهنما', 'Guide'), icon: 'edit', count: 12 },
              { value: 'component', label: tr('کامپوننت', 'Component'), icon: 'grid', count: 48 },
              { value: 'block', label: tr('بلوک', 'Block'), icon: 'layers', count: 27 },
            ]}
          />
          <Snippet react={CHIP_REACT} blade={CHIP_BLADE} vue={CHIP_VUE} svelte={CHIP_SVELTE} />
        </Demo>

        <Demo title={tr('نوار آمار — مستندات در یک نگاه', 'Stat strip — the docs at a glance')}>
          <StatStrip
            aria-label={tr('آمار مستندات', 'Docs stats')}
            stats={[
              { label: tr('کامپوننت‌ها', 'Components'), value: 48, icon: 'grid' },
              { label: tr('بلوک‌ها', 'Blocks'), value: 14, icon: 'layers', caption: tr('+۲ این ماه', '+2 this month') },
              { label: tr('فریم‌ورک‌ها', 'Frameworks'), value: 5, icon: 'globe' },
              { label: tr('تم‌ها', 'Themes'), value: 2, icon: 'moon' },
            ]}
          />
          <Snippet react={STAT_REACT} blade={STAT_BLADE} vue={STAT_VUE} svelte={STAT_SVELTE} />
        </Demo>

        <Demo title={tr('قرص مرتب‌سازی — فهرست مطالب', 'Sort pill — the docs list')}>
          <SortPill
            label={tr('مرتب‌سازی', 'Sort')}
            value={sort}
            onValueChange={setSort}
            options={[
              { value: 'recent', label: tr('تازه‌ها', 'Recent'), icon: 'sparkles' },
              { value: 'popular', label: tr('محبوب‌ها', 'Popular'), icon: 'heart' },
              { value: 'top', label: tr('بالاترین امتیاز', 'Top rated'), icon: 'trend-up' },
            ]}
          />
          <Snippet react={SORT_REACT} blade={SORT_BLADE} vue={SORT_VUE} svelte={SORT_SVELTE} />
        </Demo>
      </div>
    </Section>
  );
}
