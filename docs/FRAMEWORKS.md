# NabuXUI در هر حالت — Livewire، Inertia، React، Vue و Svelte

NabuXUI یک سیستم طراحی با **یک هستهٔ مشترک** است: همهٔ رنگ‌ها، حرکت‌ها و رفتارها
یک‌بار نوشته می‌شوند و هر فریم‌ورک فقط یک لایهٔ بومی روی همان هسته است. این سند
می‌گوید هر حالت چطور نصب و استفاده می‌شود. برای ساختن بلوک تازه به
[docs/BLOCKS.md](./BLOCKS.md) بروید.

## یک هسته، پنج حالت

هسته (`@nabuxai/ui-core`) دو نیمه دارد:

- **CSS** (`packages/core/src/css/index.css`): توکن‌ها (`tokens.css`)، پایه و
  جهت (`base.css`)، زبان حرکت (`motion.css`) و استایل همهٔ کامپوننت‌ها و بلوک‌ها
  — همه در لایه‌های `nx.tokens, nx.base, nx.components, nx.utilities`، پس هر
  قانون لایه‌نشدهٔ خودتان برنده می‌شود.
- **JS رفتاری** (`packages/core/src/js/`): توابع framework-agnostic به شکل
  `(el, options) => cleanup` (`reveal`, `indicator`, `place`, `lightDismiss`,
  `roveFocus`, `dither`, `glassPane`, `theme`, `toast`…)؛ هوک‌های React و
  دایرکتیوهای Alpine هر دو همین توابع را صدا می‌زنند.

| حالت | بسته | کامپوننت‌ها | رفتارها | وضعیت |
|---|---|---|---|---|
| **Livewire / Blade** | `nabuxai/nabuxui` (composer) | تگ‌های `<x-nx::…>` + دیتای Alpine | خودکار با `@nabuxuiScripts` | رسمی (`packages/livewire`) |
| **React — Vite یا Next.js** | `@nabuxai/ui-react` (npm) | کامپوننت‌های React | از داخل هوک‌های خودشان | رسمی (`packages/react`) |
| **Inertia (React)** | همان `@nabuxai/ui-react` | همان کامپوننت‌ها، با `linkComponent={Link}` اینرسی | همان | رسمی — بستهٔ جداگانه‌ای وجود ندارد (`packages/inertia` فعلاً پوشهٔ خالی است) |
| **Vue** | `@nabuxai/ui-core` | هیچ wrapper رسمی‌ای نیست — مارک‌آپ را با کلاس‌های `nx-*` خودتان می‌نویسید | خودتان از `@nabuxai/ui-core` صدا می‌زنید | CSS + رفتارهای هسته؛ کامپوننت Vue نیست |
| **Svelte** | `@nabuxai/ui-core` | مثل Vue — مارک‌آپ با کلاس‌های `nx-*` | مثل Vue | CSS + رفتارهای هسته؛ کامپوننت Svelte نیست |

قرارداد کلاس‌ها در هر پنج حالت یکی است: `nx-<block>` و `nx-<block>-<part>`؛ یک
کلاسِ بلوک در React و Blade و مارک‌آپ دستی Vue/Svelte به یک CSS می‌رسد. به همین
دلیل «استفاده در Vue و Svelte» یعنی همان مارک‌آپی که Blade رندر می‌کند را با
state فریم‌ورک خودتان بسازید — نه یک پوستهٔ دوباره‌طراحی‌شده.

## نصب

### Livewire (Blade + Alpine)

```bash
composer require nabuxai/nabuxui
```

سرویس‌پروایدر با کشف خودکار Laravel ثبت می‌شود (`composer.json` →
`extra.laravel.providers`). بعد سه دایرکتیو را در layout بگذارید — به همین
ترتیب، مثل `playground/resources/views/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta name="color-scheme" content="light dark">
    @nabuxuiHead          {{-- themeScript درون‌خطی + data-nx-transition --}}
    {{-- فونت‌ها (Inter، Vazirmatn…) --}}
    @nabuxuiStyles        {{-- nabuxui.css --}}
    @nabuxuiScripts       {{-- nabuxui.js — قبل از اسکریپت Livewire/Alpine --}}
</head>
```

- `@nabuxuiHead` اسکریپت تم را درون‌خطی می‌کند تا صفحه هیچ‌وقت در تم اشتباه
  رنگ نیاید و `data-nx-transition` را از کانفیگ می‌نویسد
  (`NabuXUI::head()` در `packages/livewire/src/NabuXUI.php`).
- فایل‌ها از مسیر `nabuxui/…` با روتِ داخل پکیج سرو می‌شوند؛ بعد از
  `vendor:publish --tag=nabuxui-assets` کانفیگ `serve_assets` را خاموش کنید تا
  وب‌سرور خودتان تحویل بدهد (`config/nabuxui.php`).
- پیش‌فرض ترنزیشن صفحه `fade` است؛ با کانفیگ `nabuxui.transition` روی
  `rise` / `slide` / `zoom` تغییر می‌کند و `.nx-page` را روی محتوای صفحه بگذارید
  (`motion.css` بخش Page transitions).
- تگ‌ها در فضای‌نام `x-nx::` هستند: `<x-nx::button>`، `<x-nx::chip-filter>`،
  `<x-nx::stat-strip>`…

### React — Vite یا Next.js

```bash
npm install @nabuxai/ui-react
```

دو خط در نقطهٔ ورود، به همان ترتیبِ اپ نمایشی
(`apps/showcase/src/main.tsx`): اول CSS هسته، بعد CSS خودتان.

```tsx
import '@nabuxai/ui-react/css';
import './app.css';
```

بعد کل درخت را در Provider بپیچید (`packages/react/src/internal/provider.tsx`):

```tsx
import { NabuXUIProvider } from '@nabuxai/ui-react';

<NabuXUIProvider locale="fa">
  <App />
</NabuXUIProvider>
```

- `locale` زبانِ «کلمه‌های خودِ کامپوننت‌ها» را تعیین می‌کند (بستن، صفحهٔ خالی،
  «مرتب‌سازی»…)؛ پیش‌فرض انگلیسی است.
- `linkComponent` هر `href` داخلی را از لینکِ روتر شما رد می‌کند؛ لینک بیرونی،
  `mailto:` و `#` خودکار `<a>` ساده می‌مانند (`SmartLink`).
- برای Next.js (App Router) آماده است: خروجی build با `"use client"` شروع
  می‌شود (`packages/react/scripts/build.mjs`)، پس همان‌طور که استفاده می‌کنید
  کار می‌کند؛ CSS را در root layout ایمپورت کنید.
- نسخهٔ پشتیبانی‌شده: React 18.2+ و 19 (`peerDependencies`).

### Inertia

بستهٔ جداگانه‌ای وجود ندارد — همان `@nabuxai/ui-react` با لینکِ اینرسی. پوشهٔ
`packages/inertia` فعلاً خالی است، پس تنها مسیر رسمی همین است:

```tsx
import { Link } from '@inertiajs/react';
import { NabuXUIProvider } from '@nabuxai/ui-react';

<NabuXUIProvider locale="fa" linkComponent={Link}>
  {/* صفحات Inertia */}
</NabuXUIProvider>
```

`type LinkComponent` در provider دقیقاً برای next/link و Inertia's Link و لینک
React Router نوشته شده. برای ترنزیشن صفحه، به‌روزرسانی DOM را با `transition()`
هسته داخل View Transition بیندازید (از `@nabuxai/ui-react` هم re-export شده) و
پریست را با `data-nx-transition` روی `<html>` انتخاب کنید (`fade | rise | slide
| zoom`).

### Vue و Svelte

اینجا صادقانه: **wrapper رسمی وجود ندارد.** چیزی که پشتیبانی می‌شود، همان سه
چیز است که هسته به هر زبانی می‌دهد — CSS، رفتارها و قرارداد مارک‌آپ:

```bash
npm install @nabuxai/ui-core
```

```js
import '@nabuxai/ui-core/css';       // کل استایل (نسخهٔ min هم هست: nabuxui.min.css)
// یا فقط توکن‌ها: import '@nabuxai/ui-core/tokens.css';
```

و بعد:

1. **مارک‌آپ را خودتان می‌نویسید** با همان کلاس‌ها و data-attributeهایی که
   کامپوننت React یا Blade رندر می‌کند (در کاتالوگ پایین، نام کامپوننت React هر
   بلوک را دنبال کنید و کلاس‌ها را از `packages/react/src/blocks/*.tsx` بردارید).
2. **رفتارها را خودتان وصل می‌کنید** — همان توابعی که React و Alpine مصرف
   می‌کنند، از `'@nabuxai/ui-core'`:

   ```js
   import {
     reveal, indicator, place, lightDismiss, roveFocus,   // ناوبری و popover
     magnetic, tilt, spotlight, ripple,                    // رفتارهای pointer
     numberParts, localeDigits,                            // اعداد رولینگ
     theme, themeScript,                                   // تم
     toast,
   } from '@nabuxai/ui-core';
   ```

   هر رفتار cleanup برمی‌گرداند؛ در `onBeforeUnmount` (Vue) و cleanup خود
   `onMount` (Svelte) صدا بزنید.
3. **اسکریپت تم را درون‌خطی کنید** تا اولین فریم در تم درست باشد — بخش تم پایین.
4. ویژگی‌های بومی (`popover="auto"`, `popovertarget`, `dir`) در قالب Vue و
   Svelte همان‌طور که هستند رندر می‌شوند؛ چیزی برای شبیه‌سازی لازم نیست.

## سه بلوک، پنج حالت

نمونه‌های React و Blade زیر از کد واقعی مخزن آمده‌اند (دموی نمایشی
`apps/showcase/src/blocks/nav-extras.tsx` و کامپوننت‌های
`packages/livewire/resources/views/components/`). نمونه‌های Vue و Svelte برای
همین سند نوشته شده‌اند — با همان کلاس‌ها و همان رفتارهای هسته؛ در این مخزن
ابزار Vue/Svelte نیست، پس این دو کامپایل نشده‌اند (بخش «مرزهای این سند»).

### فیلتر چیپی — chip-filter

یک ردیف انتخاب‌تکی از چیپ‌ها؛ اَکنِنت زیر چیپِ تیک‌خورده با فنر حرکت می‌کند
(هستهٔ `indicator`) و ردیف لبه‌هایش محو می‌شود.

**React:**

```tsx
import { ChipFilter } from '@nabuxai/ui-react';

<ChipFilter
  aria-label="فیلتر تگ‌ها"
  value={tag}
  onValueChange={setTag}
  items={[
    { value: 'all', label: 'همه' },
    { value: 'landing', label: 'لندینگ', icon: 'globe', count: 320 },
    { value: 'dashboard', label: 'داشبورد', icon: 'grid', count: 214 },
    { value: 'shop', label: 'فروشگاه', icon: 'layers', count: 96 },
  ]}
/>
```

کنترل‌شده/کنترل‌نشده: `value` / `defaultValue` / `onValueChange`.

**Blade:**

```blade
<x-nx::chip-filter
    :options="[
        'all' => 'همه',
        'landing' => ['label' => 'لندینگ', 'icon' => 'globe'],
        'dashboard' => ['label' => 'داشبورد', 'icon' => 'grid'],
    ]"
    :counts="['landing' => 320, 'dashboard' => 214]"
    wire:model.live="category"
/>
```

`wire:model` مستقیم روی radioها می‌نشیند؛ بعد از morph هم فنر زیر چیپ درست
می‌ماند (`nxChipFilter` در `alpine/blocks/chip-filter.ts`).

**Vue** — `ChipFilter.vue`:

```vue
<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { indicator } from '@nabuxai/ui-core';

interface Item { value: string; label: string; count?: number }
const props = defineProps<{ items: Item[]; modelValue?: string }>();
const emit = defineEmits<{ 'update:modelValue': [value: string] }>();

const row = ref<HTMLElement | null>(null);
let ind: ReturnType<typeof indicator> | null = null;
const move = () => ind?.update(row.value?.querySelector('.nx-chip-filter-chip:has(:checked)') ?? null);

onMounted(() => {
  if (!row.value) return;
  ind = indicator(row.value);
  move();
});
onBeforeUnmount(() => ind?.destroy());
</script>

<template>
  <div class="nx-chip-filter" role="group" aria-label="فیلتر" @change="move">
    <div ref="row" class="nx-chip-filter-row">
      <span class="nx-indicator nx-chip-filter-thumb" aria-hidden="true"></span>
      <label v-for="item in props.items" :key="item.value" class="nx-chip-filter-chip">
        <input
          class="nx-chip-filter-input" type="radio" name="tag"
          :value="item.value"
          :checked="item.value === props.modelValue"
          @change="emit('update:modelValue', item.value)"
        />
        <span>{{ item.label }}</span>
        <span v-if="item.count !== undefined" class="nx-chip-filter-count">{{ item.count }}</span>
      </label>
    </div>
  </div>
</template>
```

**Svelte** — `ChipFilter.svelte`:

```svelte
<script lang="ts">
  import { onMount } from 'svelte';
  import { indicator } from '@nabuxai/ui-core';

  interface Item { value: string; label: string; count?: number }
  let { items, value = $bindable('all') }: { items: Item[]; value?: string } = $props();

  let row: HTMLElement;
  let ind: ReturnType<typeof indicator> | null = null;
  const move = () => ind?.update(row.querySelector('.nx-chip-filter-chip:has(:checked)'));
  const pick = (item: Item) => { value = item.value; move(); };

  onMount(() => {
    ind = indicator(row);
    move();
    return () => ind?.destroy();
  });
</script>

<div class="nx-chip-filter" role="group" aria-label="فیلتر" onchange={move}>
  <div bind:this={row} class="nx-chip-filter-row">
    <span class="nx-indicator nx-chip-filter-thumb" aria-hidden="true"></span>
    {#each items as item (item.value)}
      <label class="nx-chip-filter-chip">
        <input
          class="nx-chip-filter-input" type="radio" name="tag"
          value={item.value} checked={item.value === value}
          onchange={() => pick(item)}
        />
        <span>{item.label}</span>
        {#if item.count !== undefined}<span class="nx-chip-filter-count">{item.count}</span>{/if}
      </label>
    {/each}
  </div>
</div>
```

### نوار آمار — stat-strip

نوار افقی آمار؛ هر عدد تا رسیدن به دید صفر می‌ماند و یک‌بار با ارقام زبانِ
مخاطب می‌غلتد (`reveal` + ستون‌های رقم `nx-number`).

**React:**

```tsx
import { StatStrip } from '@nabuxai/ui-react';

<StatStrip
  aria-label="آمار پلتفرم"
  stats={[
    { label: 'طرح‌ها', value: 2400, icon: 'layers', caption: '+۱۲۰ این ماه' },
    { label: 'طراحان', value: 1400, icon: 'users' },
    { label: 'دسته‌ها', value: 40, icon: 'grid' },
    { label: 'پلتفرم‌ها', value: 5, icon: 'globe' },
  ]}
/>
```

**Blade** — ارقام سمت سرور با زبان فعلی رندر می‌شوند؛ بعد از morph هم مقدار
تازه می‌غلتد:

```blade
<x-nx::stat-strip :stats="[
    ['label' => 'طرح‌ها', 'value' => 2400, 'icon' => 'layers', 'caption' => '+۱۲۰ این ماه'],
    ['label' => 'طراحان', 'value' => 1400, 'icon' => 'users'],
    ['label' => 'دسته‌ها', 'value' => 40, 'icon' => 'grid'],
    ['label' => 'پلتفرم‌ها', 'value' => 5, 'icon' => 'globe'],
]" />
```

**Vue** — `StatStrip.vue` (ستون‌های رقم را با `numberParts` می‌سازد):

```vue
<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { localeDigits, numberParts, reveal } from '@nabuxai/ui-core';

interface Stat { label: string; value: number; caption?: string }
const props = defineProps<{ stats: Stat[] }>();

const root = ref<HTMLElement | null>(null);
const digits = localeDigits('fa');
const rows = computed(() =>
  props.stats.map((s) => ({ ...s, parts: numberParts(s.value, 'fa') })),
);
const cleanups: Array<() => void> = [];

onMounted(() => {
  if (!root.value) return;
  cleanups.push(reveal(root.value, { once: true, stagger: true }));
  for (const el of root.value.querySelectorAll<HTMLElement>('.nx-number[data-nx-reveal]')) {
    cleanups.push(reveal(el, { once: true }));
  }
});
onBeforeUnmount(() => cleanups.forEach((stop) => stop()));
</script>

<template>
  <dl ref="root" class="nx-stat-strip" data-nx-reveal="group" aria-label="آمار">
    <div v-for="(s, i) in rows" :key="s.label" class="nx-stat-strip-item" :style="{ '--nx-i': i }">
      <div class="nx-stat-strip-what">
        <dt class="nx-stat-strip-label">{{ s.label }}</dt>
        <dd class="nx-stat-strip-value">
          <span class="nx-number" data-nx-reveal>
            <span class="nx-visually-hidden">{{ s.value.toLocaleString('fa') }}</span>
            <span class="nx-number-roll" aria-hidden="true">
              <template v-for="(p, j) in s.parts" :key="j">
                <span
                  v-if="p.kind === 'digit'"
                  class="nx-digit"
                  :style="{ '--d': p.value, '--nx-p': s.parts.length - 1 - j }"
                >
                  <span class="nx-digit-track"><span v-for="d in digits" :key="d">{{ d }}</span></span>
                </span>
                <span v-else class="nx-number-sep">{{ p.char }}</span>
              </template>
            </span>
          </span>
          <span v-if="s.caption" class="nx-stat-strip-caption">{{ s.caption }}</span>
        </dd>
      </div>
    </div>
  </dl>
</template>
```

**Svelte** — `StatStrip.svelte`:

```svelte
<script lang="ts">
  import { onMount } from 'svelte';
  import { type Cleanup, localeDigits, numberParts, reveal } from '@nabuxai/ui-core';

  interface Stat { label: string; value: number; caption?: string }
  let { stats }: { stats: Stat[] } = $props();

  let root: HTMLElement;
  const digits = localeDigits('fa');
  const rows = $derived(stats.map((s) => ({ ...s, parts: numberParts(s.value, 'fa') })));

  onMount(() => {
    const cleanups: Cleanup[] = [];
    cleanups.push(reveal(root, { once: true, stagger: true }));
    for (const el of root.querySelectorAll<HTMLElement>('.nx-number[data-nx-reveal]')) {
      cleanups.push(reveal(el, { once: true }));
    }
    return () => cleanups.forEach((stop) => stop());
  });
</script>

<dl bind:this={root} class="nx-stat-strip" data-nx-reveal="group" aria-label="آمار">
  {#each rows as s, i (s.label)}
    <div class="nx-stat-strip-item" style:--nx-i={i}>
      <div class="nx-stat-strip-what">
        <dt class="nx-stat-strip-label">{s.label}</dt>
        <dd class="nx-stat-strip-value">
          <span class="nx-number" data-nx-reveal>
            <span class="nx-visually-hidden">{s.value.toLocaleString('fa')}</span>
            <span class="nx-number-roll" aria-hidden="true">
              {#each s.parts as p, j (j)}
                {#if p.kind === 'digit'}
                  <span class="nx-digit" style:--d={p.value} style:--nx-p={s.parts.length - 1 - j}>
                    <span class="nx-digit-track">{#each digits as d}{d}{/each}</span>
                  </span>
                {:else}
                  <span class="nx-number-sep">{p.char}</span>
                {/if}
              {/each}
            </span>
          </span>
          {#if s.caption}<span class="nx-stat-strip-caption">{s.caption}</span>{/if}
        </dd>
      </div>
    </div>
  {/each}
</dl>
```

### قرص مرتب‌سازی — sort-pill

قرصی که برچسبِ گزینهٔ انتخاب‌شده داخل خودش می‌چرخد و پنلش یک popover بومی است؛
جاگذاری با `place` و بستن با Escape/کلیک بیرون از هسته می‌آید.

**React:**

```tsx
import { SortPill } from '@nabuxai/ui-react';

<SortPill
  label="مرتب‌سازی"
  value={sort}
  onValueChange={setSort}
  options={[
    { value: 'featured', label: 'منتخب', icon: 'star' },
    { value: 'recent', label: 'تازه‌ها', icon: 'sparkles' },
    { value: 'top', label: 'بالاترین امتیاز', icon: 'trend-up' },
  ]}
/>
```

**Blade:**

```blade
<x-nx::sort-pill
    :options="['featured' => ['label' => 'منتخب', 'icon' => 'star'], 'recent' => 'تازه‌ها']"
    wire:model.live="sort"
/>
```

**Vue** — `SortPill.vue` (popover بومی + `place` هسته؛ همان wiring که
`usePopover` در React می‌کند):

```vue
<script setup lang="ts">
import { ref } from 'vue';
import { type Cleanup, iconSvg, place, roveFocus } from '@nabuxai/ui-core';

interface Option { value: string; label: string }
const props = withDefaults(defineProps<{ options: Option[]; modelValue?: string; label?: string }>(), {
  modelValue: undefined, label: 'مرتب‌سازی',
});
const emit = defineEmits<{ 'update:modelValue': [value: string] }>();

const id = 'nx-sort';
const open = ref(false);
const trigger = ref<HTMLButtonElement | null>(null);
const panel = ref<HTMLElement | null>(null);
let unplace: Cleanup | null = null;

const current = () => props.options.find((o) => o.value === props.modelValue) ?? props.options[0];

function onToggle(event: Event) {
  open.value = (event as ToggleEvent).newState === 'open';
  unplace?.();
  unplace = null;
  if (open.value && trigger.value && panel.value) {
    unplace = place(trigger.value, panel.value, { side: 'bottom', align: 'start', offset: 8 });
  }
}
function choose(value: string) {
  emit('update:modelValue', value);
  window.setTimeout(() => panel.value?.matches(':popover-open') && panel.value?.hidePopover(), 300);
}
function onKey(event: KeyboardEvent) {
  roveFocus(event, event.currentTarget as HTMLElement, '.nx-sort-pill-choice', { orientation: 'vertical' });
}
</script>

<template>
  <button
    ref="trigger" type="button" :popovertarget="id"
    class="nx-sort-pill-trigger"
    aria-haspopup="listbox" :aria-expanded="open" :aria-controls="id"
    :aria-label="`${props.label}: ${current()?.label ?? ''}`"
  >
    <span class="nx-sort-pill-labels" aria-hidden="true">
      <span
        v-for="o in props.options" :key="o.value" class="nx-sort-pill-label"
        :data-current="o.value === props.modelValue ? '' : undefined"
      >{{ o.label }}</span>
    </span>
    <span class="nx-sort-pill-chevron" aria-hidden="true" v-html="iconSvg('chevron-down')"></span>
  </button>
  <div :id="id" ref="panel" class="nx-sort-pill" popover="auto" @toggle="onToggle" @keydown="onKey">
    <ul class="nx-sort-pill-list" role="listbox" :aria-label="props.label">
      <li
        v-for="o in props.options" :key="o.value"
        class="nx-sort-pill-option" role="presentation"
        :data-selected="o.value === props.modelValue ? '' : undefined"
      >
        <button type="button" class="nx-sort-pill-choice" role="option"
          :aria-selected="o.value === props.modelValue" @click="choose(o.value)">
          <span>{{ o.label }}</span>
          <span class="nx-sort-pill-mark" aria-hidden="true" v-html="iconSvg('check')"></span>
        </button>
      </li>
    </ul>
  </div>
</template>
```

**Svelte** — `SortPill.svelte`:

```svelte
<script lang="ts">
  import { iconSvg, place, roveFocus } from '@nabuxai/ui-core';

  interface Option { value: string; label: string }
  let { options, value = $bindable(), label = 'مرتب‌سازی' }: { options: Option[]; value?: string; label?: string } = $props();

  const id = 'nx-sort';
  let open = $state(false);
  let trigger: HTMLButtonElement;
  let panel: HTMLElement;
  let unplace: (() => void) | null = null;

  const current = $derived(options.find((o) => o.value === value) ?? options[0]);

  function onToggle(event: Event) {
    open = (event as ToggleEvent).newState === 'open';
    unplace?.();
    unplace = null;
    if (open) unplace = place(trigger, panel, { side: 'bottom', align: 'start', offset: 8 });
  }
  const choose = (next: string) => {
    value = next;
    setTimeout(() => { if (panel.matches(':popover-open')) panel.hidePopover(); }, 300);
  };
  const onKey = (event: KeyboardEvent) => {
    roveFocus(event, event.currentTarget as HTMLElement, '.nx-sort-pill-choice', { orientation: 'vertical' });
  };
</script>

<button
  bind:this={trigger} type="button" popovertarget={id}
  class="nx-sort-pill-trigger"
  aria-haspopup="listbox" aria-expanded={open} aria-controls={id}
  aria-label="{label}: {current?.label ?? ''}"
>
  <span class="nx-sort-pill-labels" aria-hidden="true">
    {#each options as o (o.value)}
      <span class="nx-sort-pill-label" data-current={o.value === value ? '' : undefined}>{o.label}</span>
    {/each}
  </span>
  <span class="nx-sort-pill-chevron" aria-hidden="true">{@html iconSvg('chevron-down')}</span>
</button>

<div {id} bind:this={panel} class="nx-sort-pill" popover="auto" {onToggle} {onKey}>
  <ul class="nx-sort-pill-list" role="listbox" aria-label={label}>
    {#each options as o (o.value)}
      <li class="nx-sort-pill-option" role="presentation" data-selected={o.value === value ? '' : undefined}>
        <button type="button" class="nx-sort-pill-choice" role="option" aria-selected={o.value === value} onclick={() => choose(o.value)}>
          <span>{o.label}</span>
          <span class="nx-sort-pill-mark" aria-hidden="true">{@html iconSvg('check')}</span>
        </button>
      </li>
    {/each}
  </ul>
</div>
```

## تم روشن و تیره

تم از سه جا هم‌زمان خوانده می‌شود که هیچ مصرف‌کننده‌ای جا نماند
(`packages/core/src/js/theme.ts`): `data-theme` روی `<html>` (توکن‌های
NabuXUI)، کلاس `dark` (برای `dark:` تیلویند)، و `color-scheme` (کنترل‌های بومی
مرورگر). ترجیح در `localStorage` زیر کلید **`nabu.theme`** ذخیره می‌شود؛ مقدار
`light` / `dark` / حذفِ کلید (یعنی «سیستم»).

**اسکریپت تم باید درون‌خطی در `<head>` باشد** — قبل از هر استایلی — تا اولین
فریم در تم درست رنگ بگیرد. تابع `themeScript` هسته دقیقاً همین را می‌دهد
(ES5 خالص، `nx-js` را هم روی `<html>` می‌گذارد تا محتوای reveal فقط وقتی
اسکریپت آمد پنهان شود):

- **Livewire:** کاری نکنید — `@nabuxuiHead` همین را درون‌خطی می‌کند.
- **React / Next / Inertia:** خروجی `themeScript` را در `<head>` بگذارید:

  ```tsx
  import { themeScript } from '@nabuxai/ui-core';

  <head>
    <script dangerouslySetInnerHTML={{ __html: themeScript }} />
    {/* در Next: <script dangerouslySetInnerHTML…> داخل root layout */}
  </head>
  ```

- **Vue / Svelte:** در قالب صفحهٔ اصلی:

  ```js
  import { themeScript } from '@nabuxai/ui-core';
  // Vue/Svelte: تزریق یک <script> با innerHTML = themeScript، قبل از CSS
  ```

بعد از آن، هر فریم‌ورک از همان API مشترک استفاده می‌کند:

```js
import { theme } from '@nabuxai/ui-core';

theme.set('dark');        // یا 'light' | 'system' — ذخیره و اعمال
theme.toggle();           // اگر با سیستم یکی شد، کلید حذف می‌شود (دنبال سیستم می‌ماند)
theme.resolved();         // 'light' | 'dark'
theme.watch((scheme) => …); // تغییر سیستم و تب‌های دیگر؛ خروجی: unsubscribe
```

رویداد `nx-theme-change` هم روی `window` پخش می‌شود (`THEME_EVENT`).

- **کامپوننت آماده:** React `ThemeSwitch` (سه‌حالته روشن/سیستم/تیره) و
  `ThemeToggle` (هدر)؛ Blade `<x-nx::theme-switch>` و `<x-nx::theme-toggle>` —
  هر دو روی همین مخزن تم می‌نویسند.
- **توکن‌ها:** هر دو تم در `packages/core/src/css/tokens.css` تعریف شده‌اند —
  روشن در `:root`، تیره در `[data-theme="dark"]` / `.dark` (و مسیر
  `prefers-color-scheme` برای وقتی صفحه چیزی pin نکرده). هیچ‌چیز حق ندارد به
  پس‌زمینهٔ روشن یا تیره «گمان» کند؛ مثل توکن‌های سطح را بنویسید
  (`--nx-surface`, `--nx-text`, `--nx-border`, `--nx-accent`…). فهرست کامل
  توکن‌ها در [docs/BLOCKS.md](./BLOCKS.md).
- **حرکت کم:** در `prefers-reduced-motion: reduce` توکن `--nx-motion` صفر
  می‌شود و همهٔ جابه‌جایی‌ها به fade ساده تبدیل می‌شوند — بدون هیچ کدی از
  سمت شما.

## راست‌به‌چپ

- جهت را با `dir="rtl"` روی `<html>` بگذارید (مثل layout پلی‌گراوند). همهٔ
  چیدمان‌ها با propertyهای منطقی نوشته شده‌اند (`inset-inline-start`,
  `margin-inline-end`)، پس آینه‌شدن رایگان است.
- **متغیر `--nx-dir`** (`packages/core/src/css/base.css`): `1` در LTR و `-1` در
  RTL. هر حرکتی که به سمت ابتدا/انتهای خط سفر می‌کند در آن ضرب شده — همان
  کلاس‌ها در RTL خودبه‌خود به سمت دیگر می‌روند:

  ```css
  :where(html)      { --nx-dir: 1; }
  :where([dir="rtl"]) { --nx-dir: -1; }
  /* نمونه: reveal از سمت انتها (motion.css) */
  :where(.nx-js) [data-nx-reveal="end"]:not([data-nx-revealed]) {
    translate: calc(24px * var(--nx-dir) * var(--nx-motion)) 0;
  }
  ```

- هر مسافت حرکتی در `--nx-motion` هم ضرب می‌شود؛ حاصل‌ضرب دوم یعنی در RTL و
  با reduced-motion هم‌زمان درست است.
- آیکون‌های جهت‌دار (فلش‌ها و chevronها) با `data-directional` علامت خورده‌اند
  و در RTL خودشان flip می‌شوند (`base.css`).
- **فارسی/عربی:** فونت‌ها با `:lang(fa)` به Vazirmatn می‌روند،
  `line-height` روی 1.8 می‌آید و همهٔ توکن‌های `--nx-tracking-*` صفر می‌شوند
  (حروف چسبان نباید از هم باز شوند — `base.css`).
- **ارقام:** React `numberParts(value, 'fa')` / `localeDigits('fa')` و Blade
  `NabuXUI::formatNumber($v, 0, 'fa')` ستون‌های رقم را با ۰–۹ فارسی
  می‌سازند؛ اعداد در هر زبانی می‌غلتند.

## کاتالوگ بلوک‌ها

هر سطر: نام بلوک، تگ یک‌خطی، نام کامپوننت React، تگ Blade، و اینکه رفتار JS
لازم دارد یا خالص CSS است (ستون JS از خودِ کد آمده: وجود `x-data`/دایرکتیو
`x-nx-*` در Blade و رفتار core در React — نشانه‌های «خالص CSS» هم از
کامنت‌های خود کامپوننت‌ها). ستون Blade برای دو بلوکِ `hover-nav` و
`file-upload` خالی است: آن دو فعلاً فقط در React و هسته پیاده شده‌اند.

### متن

| بلوک | تگ یک‌خطی | React | Blade | JS رفتاری |
|---|---|---|---|---|
| text-hero | حرف‌به‌حرف بالا می‌پرد (فارسی: کلمه‌به‌کلمه) و هایلایت گرادیان برند | `TextHero` | `<x-nx::text-hero>` | `reveal` |
| scroll-text-reveal | سکشن پین‌شده؛ حروف پراکنده با اسکرول جمع می‌شوند | `ScrollTextReveal` | `<x-nx::scroll-text-reveal>` | `stickyProgress` |
| hover-reveal | ردیف لینک‌ها؛ برچسب با جهتِ ورودِ موس جابه‌جا می‌شود | `HoverReveal` | `<x-nx::hover-reveal>` | `hoverReveal` |
| scroll-scramble | تیتر با اسکرول «دیکود» می‌شود؛ چیپ‌ها از پهله به ردیف می‌آیند | `ScrollScramble` | `<x-nx::scroll-scramble>` | `scrollScramble` |
| dither-backdrop | زمینهٔ دیتر متحرک با رنگ‌های تم، روی canvas | `DitherBackdrop` | `<x-nx::dither-backdrop>` | `dither` |
| pulse-button | CTA گرد با حلقه‌های پالس، به سمت موس کشیده می‌شود | `PulseButton` | `<x-nx::pulse-button>` | `magnetic` |
| roll-text | hover: هر حرف بالا می‌رود و کپی‌اش از پایین می‌آید | `RollText` | `<x-nx::roll-text>` | — |
| backdrop | زمینهٔ تزئینی خالص CSS: aurora، grid، stars، beams، dots | `Backdrop` | `<x-nx::backdrop>` | — |

### کنش

| بلوک | تگ یک‌خطی | React | Blade | JS رفتاری |
|---|---|---|---|---|
| border-button | قاب نقطه‌چینِ در حال رژه؛ زیر لود می‌چرخد | `BorderButton` | `<x-nx::border-button>` | `marchingBorder` |
| fill-button | hover: برچسب دوم جای اولی را با فنر پر می‌کند | `FillButton` | `<x-nx::fill-button>` | `morphLabel` |
| metal-button | کلید فلزی مایع با rim رنگین‌کمانی در حالت فعال | `MetalButton` | `<x-nx::metal-button>` | بله |
| flip-button | hover/فوکوس: هر پاره‌ای مثل مکعب یک‌چهارم دور می‌خورد | `FlipButton` | `<x-nx::flip-button>` | — |
| blob-button | قرص تیره با blobهای لاجورد/بنفش/طلایی دنبال‌کنندهٔ موس | `BlobButton` | `<x-nx::blob-button>` | `spotlight` |
| transaction-button | دکمهٔ تراکنش با حالت‌های loading/success/error | `TransactionButton` | `<x-nx::transaction-button>` | بله |
| file-upload | دراپ‌زون گرادیانی با گزارش پذیرش/رد و sheen نامعین | `FileUpload` | — (هنوز Blade ندارد) | `dropzone` |
| file-drop | رهاکردن فایل به input و نمایش پیشرفت | `FileDrop` | `<x-nx::file-drop>` | `dropzone` |
| interests-picker | انتخاب علایق به‌صورت چیپ‌های چندانتخابی چندردیفه | `InterestsPicker` | `<x-nx::interests-picker>` | بله |
| label-creator | چیپ‌در-فیلد به سبک Notion با ساخت برچسب و پیکر رنگ | `LabelCreator` | `<x-nx::label-creator>` | بله |
| pixel-loader | لودر پیکسلی موجی — کاملاً CSS | `PixelLoader` | `<x-nx::pixel-loader>` | — |
| parametric-loader | مسیر پارامتری (rose/spiro/lissajous) با سرِ درخشان | `ParametricLoader` | `<x-nx::parametric-loader>` | — |

### کارت

| بلوک | تگ یک‌خطی | React | Blade | JS رفتاری |
|---|---|---|---|---|
| stacked-scroll-cards | کارت‌ها با اسکرول روی هم سنجاق می‌شوند | `StackedScrollCards` | `<x-nx::stacked-scroll-cards>` | `stackedScroll` |
| ring-carousel | کاروسل حلقه‌ای با شمارندهٔ دوار | `RingCarousel` | `<x-nx::ring-carousel>` | `ringCarousel` |
| expandable-stack | دستهٔ کارت؛ انتخاب یکی آن را باز می‌کند | `ExpandableStack` | `<x-nx::expandable-stack>` | بله |
| orbit-showcase | آیتم‌ها در مدار دور مرکز می‌چرخند | `OrbitShowcase` | `<x-nx::orbit-showcase>` | `orbitShowcase` |
| team-cards | کارت‌های تیم؛ hover رنگ از پایین بالا می‌آید | `TeamCards` | `<x-nx::team-cards>` | — |
| depth-carousel | کاروسل با عمق و آیتم‌های محو کنار | `DepthCarousel` | `<x-nx::depth-carousel>` | بله |
| cycle-stack | کارت جلو با کلید «بعدی» می‌چرخد | `CycleStack` / `CycleCard` | `<x-nx::cycle-stack>` | بله |
| elastic-grid | گرید کشسان با پارالاکس ستون‌ها | `ElasticGrid` | `<x-nx::elastic-grid>` | `elasticGrid` |
| workflow-card | کارت وضعیت مرحله‌ای با اتصال‌ها | `WorkflowCard` | `<x-nx::workflow-card>` | — |
| feature-card | کارت ویژگی با تصویرسازی متحرک CSS | `FeatureCard` | `<x-nx::feature-card>` | — |
| pit-slider | ردیف میله‌ها روی یک رِنج بومی؛ زیر thumb هنگام کشیدن در گودال فرو می‌روند | `PitSlider` | `<x-nx::pit-slider>` | `pitSlider` |
| precision-slider | اسلایدر دقیق با عدد رولینگ و تیک‌های پرشونده | `PrecisionSlider` | `<x-nx::precision-slider>` | بله |
| infinite-grid | زمینهٔ نقطه‌ای بی‌پایان با spot موس | `InfiniteGrid` | `<x-nx::infinite-grid>` | `spotlight` |
| invoice | سند فاکتور قابل‌چاپ؛ ردیف‌ها reveal و جمع‌ها رول می‌شوند | `Invoice` | `<x-nx::invoice>` | `reveal` |

### داده

| بلوک | تگ یک‌خطی | React | Blade | JS رفتاری |
|---|---|---|---|---|
| multi-select | چندانتخاب جست‌وجوپذیر با توضیح هر گزینه | `MultiSelect` | `<x-nx::multi-select>` | بله |
| heatmap | تقویم مشارکت (یا هر ماتریس عددی) | `Heatmap` | `<x-nx::heatmap>` | بله |
| status-badge | نشان وضعیت زنده؛ عرض با برچسب می‌ایستد | `StatusBadge` | `<x-nx::status-badge>` | `fitStatusBadge` |
| data-table | جدول با مرتب‌سازی و FLIP ردیف‌ها | `DataTable` | `<x-nx::data-table>` | `sortRows` + `playRowFlip` |
| metric-chart | نمودار متریک با تب دوره‌ها | `MetricChart` | `<x-nx::metric-chart>` | بله |
| currency-converter | تبدیل ارز با نرخ‌های prop — هیچ fetchی نیست | `CurrencyConverter` | `<x-nx::currency-converter>` | بله |
| workspace-shell | پوستهٔ ورک‌اسپیس با ناوبری و پنل‌ها | `WorkspaceShell` | `<x-nx::workspace-shell>` | بله |
| support-agent-card | کارت ایجنت پشتیبانی با متریک‌ها و ترند | `SupportAgentCard` | `<x-nx::support-agent-card>` | `reveal` |
| analytics-card | کارت آنالیتیکس با انتخاب دوره | `AnalyticsCard` | `<x-nx::analytics-card>` | بله |
| dot-matrix-chart | نمودار نقطه‌ماتریسی چندسری | `DotMatrixChart` | `<x-nx::dot-matrix-chart>` | بله |
| branch-connector | اتصال منبع به هدف‌ها با مسیر منحنی | `BranchConnector` | `<x-nx::branch-connector>` | `connect` |
| curved-timeline | تایم‌لاین مارپیچ با نقاط تاریخ‌دار | `CurvedTimeline` | `<x-nx::curved-timeline>` | `curvedTimeline` |
| usage-card | کارت مصرف با سهم دسته‌ها و CTA ارتقا | `UsageCard` | `<x-nx::usage-card>` | `reveal` |
| comparison-table | جدول مقایسهٔ پلن‌ها با ستون پیشنهادی | `ComparisonTable` | `<x-nx::comparison-table>` | `reveal` |
| calendar | تقویم ماه؛ تغییر ماه با اسلاید جهت‌دار و پیمایش کیبوردی روزها | `Calendar` | `<x-nx::calendar>` | بله |
| kanban | برد ستونی با درگ‌انددراپ بومی و لغزش FLIP | `Kanban` | `<x-nx::kanban>` | `snapshotRows` + `playRowFlip` |
| timeline-feed | جریان فعالیت عمودی با اتصال گرادیانی و زمان نسبی | `TimelineFeed` | `<x-nx::timeline-feed>` | `reveal` |
| empty-state | «هنوز چیزی نیست»: بشقاب شناور، مدار خط‌چین و هالهٔ نرم | `EmptyState` | `<x-nx::empty-state>` | `reveal` |

### منو

| بلوک | تگ یک‌خطی | React | Blade | JS رفتاری |
|---|---|---|---|---|
| admin-shell | پوستهٔ پنل: سایدبار گروهی با نشان فنری، تاپ‌بار، دراور موبایل | `AdminShell` + `AdminSidebar` + `AdminTopbar` | `<x-nx::admin-shell>` + `<x-nx::admin-sidebar>` + `<x-nx::admin-topbar>` | `indicator` + `place` + `lightDismiss` |
| fold-menu | هر بخش یک تایِ تاشو؛ سه‌چهار بخش بهتر خوانده می‌شود | `FoldMenu` | `<x-nx::fold-menu>` | بله |
| dock-panels | داک که خودش به پنل آیتم فعال بزرگ می‌شود | `DockPanels` | `<x-nx::dock-panels>` | بله |
| morph-menu | دکمهٔ فیلتری که ظرفِ خودش منو می‌شود | `MorphMenu` | `<x-nx::morph-menu>` | `morphShell` |
| stacked-accordion | آکاردئونی که کارت‌ها مثل دسته ورق باز می‌شوند | `StackedAccordion` | `<x-nx::stacked-accordion>` | بله |
| stack-menu | منوی چندسطحی با push/pop و جست‌وجوی همهٔ لایه‌ها | `StackMenu` | `<x-nx::stack-menu>` | بله |
| morph-tabs | تب‌های آیکونی؛ فعال‌ شده باز می‌شود و pill دنبالش می‌آید | `MorphTabs` | `<x-nx::morph-tabs>` | `morphTabs` |
| audio-room | اتاق صوتی با میزبان‌ها و نوارهای گفتار | `AudioRoom` | `<x-nx::audio-room>` | بله |
| chat | گفتگوی دوستونه: جست‌وجوی رشته‌ها، اکوی محلی پیام، حالت تایپ | `Chat` | `<x-nx::chat>` | بله |
| activity-dropdown | دراپ‌داون فعالیت با زمان نسبی | `ActivityDropdown` | `<x-nx::activity-dropdown>` | بله |
| member-selector | چندانتخاب عضو با جست‌وجو و نقش‌ها | `MemberSelector` | `<x-nx::member-selector>` | بله |
| registration-card | فرم ثبت‌نام چندمرحله‌ای با بلیت‌ها | `RegistrationCard` | `<x-nx::registration-card>` | بله |
| auth-card | فرم سه‌پنهای ورود/ثبت‌نام/فراموشی؛ پن‌ها می‌لغزند و ارتفاع مورف می‌شود | `AuthCard` | `<x-nx::auth-card>` | `morphShell` |
| voice-recorder | میکروفونی که قرص ضبط و پخش می‌شود — بدون دسترسی میکروفن | `VoiceRecorder` | `<x-nx::voice-recorder>` | بله |
| mega-menu | منوی بزرگ هدر با ستون‌ها و توضیح | `MegaMenu` | `<x-nx::mega-menu>` | بله |
| hover-nav | ناوبری که پنل از سمتِ ورودِ موس باز می‌شود | `HoverNav` | — (هنوز Blade ندارد) | `hoverNav` |
| chain-selector | انتخاب شبکه؛ گلیف تریگر به زنجیرهٔ انتخابی morph می‌شود | `MultiChainSelector` | `<x-nx::chain-selector>` | بله |
| language-menu | سوییچ زبان؛ کد زبان در تریگر می‌چرخد | `LanguageMenu` | `<x-nx::language-menu>` | بله |
| theme-switch | سه‌حالته روشن/سیستم/تیره با thumb فنری | `ThemeSwitch` | `<x-nx::theme-switch>` | بله |
| promo-bar | نوار اطلاعیه با نشان چرخیده و بستنِ به‌یادماندنی | `PromoBar` | `<x-nx::promo-bar>` | بله |
| chip-filter | ردیف چیپ فیلتر با فنرِ زیر انتخاب | `ChipFilter` | `<x-nx::chip-filter>` | `indicator` |
| stat-strip | نوار آمار با اعدادی که از صفر می‌غلتند | `StatStrip` | `<x-nx::stat-strip>` | `reveal` |
| sort-pill | قرص مرتب‌سازی؛ برچسب داخل تریگر می‌چرخد | `SortPill` | `<x-nx::sort-pill>` | `place` + `lightDismiss` |

### شیشه (glass)

| بلوک | تگ یک‌خطی | React | Blade | JS رفتاری |
|---|---|---|---|---|
| glass-panel | سطح شیشه با tint و rim نورگیر | `GlassPanel` | `<x-nx::glass-panel>` | `glassPane` |
| glass-button | دکمهٔ شیشه‌ای با shimmer اختیاری | `GlassButton` | `<x-nx::glass-button>` | `glassPane` |
| glass-segmented | سگمنتِ شیشه‌ای که انتخابش یک عدسی قابل‌درگ است | `GlassSegmented` | `<x-nx::glass-segmented>` | `glassSegmented` |
| glass-dock | داک شیشه‌ای با ذره‌بینِ لغزان | `GlassDock` | `<x-nx::glass-dock>` | `glassDock` |
| glass-tab-bar | تب‌بار شیشه‌ای شناور | `GlassTabBar` | `<x-nx::glass-tab-bar>` | `glassTabBar` |
| glass-switch | سوییچ شیشه‌ای روی checkbox بومی | `GlassSwitch` | `<x-nx::glass-switch>` | `glassSwitch` |
| glass-slider | رِنج بومی که thumbش هنگام کشیدن ذره‌بین می‌شود | `GlassSlider` | `<x-nx::glass-slider>` | `glassSlider` |
| reading-glass | عدسی مطالعه روی محتوا | `ReadingGlass` | `<x-nx::reading-glass>` | `readingGlass` |
| liquid-ripple | هر کلیک موجی در محتوای زنده می‌فرستد | `LiquidRipple` | `<x-nx::liquid-ripple>` | بله |

> کامپوننت‌های پایه (Button، Card، Input، Dialog، Header، Tabs، Toaster و…)
> هم در React (`packages/react/src/components/*`) و هم در Blade
> (`<x-nx::…>`) با همان کلاس‌ها هستند؛ جدول بالا فقط بلوک‌های حرکتی را فهرست
> کرده.

## مرزهای این سند

- Vue و Svelte wrapper رسمی ندارند؛ نمونه‌های SFC بالا برای همین سند نوشته
  شده‌اند و در این مخزن کامپایل نشده‌اند (ابزار Vue/Svelte در مخزن نیست).
- `packages/inertia` فعلاً پوشهٔ خالی است؛ مسیر رسمی Inertia همان
  `@nabuxai/ui-react` با `linkComponent={Link}` است.
- بلوک‌های `hover-nav` و `file-upload` هنوز معادل Blade ندارند (در React و
  هسته پیاده شده‌اند).
- ستون JS جدول کاتالوگ از presence خواندنِ `x-data`/`x-nx-*` در Blade و
  رفتارهای core در React استخراج شده؛ «بله» یعنی رفتار لازم است حتی اگر نامش
  اینجا ذکر نشده.
- نام‌های بسته‌ها (`nabuxai/nabuxui`، `@nabuxai/ui-react`، `@nabuxai/ui-core`)
  از فایل‌های package/composer همین مخزن است؛ وضعیت انتشار عمومی آن‌ها را این
  مخزن تعیین نمی‌کند.

## بعدش چه

برای ساختن بلوک تازه — ساختار فایل‌ها، قواعد حرکت (فنرها، `--nx-motion`،
`--nx-dir`)، قواعد پولیش و دسترسی‌پذیری، و چک‌های قبل از تحویل —
[docs/BLOCKS.md](./BLOCKS.md).
