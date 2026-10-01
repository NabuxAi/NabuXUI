<script setup lang="ts">
/**
 * The month calendar — the hand-written Vue shape of the `calendar` block
 * (nx-calendar), on the same plain Date math the React/Alpine block uses: a
 * month is "YYYY-MM", a day is "YYYY-MM-DD", the grid always holds whole
 * weeks. Switching months remounts the grid entering from the side it comes
 * from (mirrored by --nx-dir in RTL); arrow keys walk the days with a roving
 * tab stop; the selected day's agenda sits below; a visually-hidden table
 * carries the same events. Names and digits follow the reader's locale
 * ("شهریور" vs "September", ۱۵ vs 15) through Intl — fa-IR is the Jalali
 * calendar, so no date library is needed.
 */
import { computed, onBeforeUnmount, onMounted, ref, useId, watch } from 'vue';
import { type IconName, reveal, translate } from '@nabuxai/ui-core';
import { intlLocale, lang, numberFmt } from '../store';
import NxIcon from './NxIcon.vue';

export type CalendarTone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';

export interface CalendarEvent {
  /** Which day the chip sits on: "YYYY-MM-DD" (an ISO datetime's day part works too). */
  date: string;
  label: string;
  tone?: CalendarTone;
  /** A free-form time shown in the day view ("09:30", "۹:۳۰ صبح"). */
  time?: string;
}

const props = withDefaults(
  defineProps<{
    events?: CalendarEvent[];
    /** The selected day, "YYYY-MM-DD"; null for none. */
    modelValue?: string | null;
    /** The month on show, "YYYY-MM". */
    month: string;
    /** First day of the week: 0 Sunday, 1 Monday, 6 Saturday (the Persian week). */
    weekStart?: 0 | 1 | 6;
    /** Event chips each cell shows before the "+N" tally. */
    maxPerCell?: number;
    /** The calendar's accessible name. */
    label?: string;
    emptyText?: string;
  }>(),
  { events: () => [], modelValue: null, weekStart: undefined, maxPerCell: 3, label: undefined, emptyText: undefined },
);

const emit = defineEmits<{ 'update:modelValue': [date: string | null]; 'update:month': [month: string] }>();

const uid = useId();
const root = ref<HTMLElement | null>(null);
const grid = ref<HTMLElement | null>(null);
const enter = ref<'next' | 'prev' | null>(null);
let stopReveal: (() => void) | null = null;

onMounted(() => {
  if (root.value) stopReveal = reveal(root.value, { once: true });
});
onBeforeUnmount(() => stopReveal?.());

/* ---- Date math (UTC days, no libraries) -------------------------------------------------- */

const DAY = 86_400_000;
/** A Sunday, so weekday names can be picked by offset whatever the week starts on. */
const REFERENCE_SUNDAY = Date.UTC(2026, 0, 4);

const isoDay = (time: number) => new Date(time).toISOString().slice(0, 10);
const monthOf = (date: string) => date.slice(0, 7);
const todayISO = () => {
  const now = new Date();
  return isoDay(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));
};
const today = todayISO();

/** "YYYY-MM" a step away (steps may cross the year). */
const shiftMonth = (ym: string, step: number) => {
  const [y, m] = ym.split('-').map(Number);
  const at = Date.UTC(y!, m! - 1 + step, 1);
  return `${new Date(at).getUTCFullYear()}-${String(new Date(at).getUTCMonth() + 1).padStart(2, '0')}`;
};

/** The six-ish week rows of a month: every cell's ISO day, leading and trailing days included. */
function monthGrid(ym: string, weekStart: number): string[][] {
  const first = new Date(`${ym}-01T00:00:00Z`);
  const offset = (first.getUTCDay() - weekStart + 7) % 7;
  const days = new Date(Date.UTC(first.getUTCFullYear(), first.getUTCMonth() + 1, 0)).getUTCDate();
  const rows = Math.ceil((offset + days) / 7);
  const start = first.getTime() - offset * DAY;
  return Array.from({ length: rows }, (_, row) => Array.from({ length: 7 }, (_, col) => isoDay(start + (row * 7 + col) * DAY)));
}

const week = computed(() => props.weekStart ?? (lang.value === 'fa' ? 6 : 0));

const word = (key: 'calendar' | 'calendarPrevMonth' | 'calendarNextMonth' | 'calendarEventsCount' | 'calendarEmptyDay', params: Record<string, string | number> = {}) =>
  translate(lang.value, key, params);

/* ---- Formatters (fa-IR gives the Persian calendar) ---------------------------------------- */

const formatters = computed(() => ({
  title: new Intl.DateTimeFormat(intlLocale.value, { month: 'long', year: 'numeric', timeZone: 'UTC' }),
  weekday: new Intl.DateTimeFormat(intlLocale.value, { weekday: 'short', timeZone: 'UTC' }),
  day: new Intl.DateTimeFormat(intlLocale.value, { day: 'numeric', timeZone: 'UTC' }),
  long: new Intl.DateTimeFormat(intlLocale.value, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' }),
}));

const dayOf = (iso: string) => formatters.value.day.format(new Date(`${iso}T00:00:00Z`));
const longOf = (iso: string) => formatters.value.long.format(new Date(`${iso}T00:00:00Z`));

const weekdays = computed(() =>
  Array.from({ length: 7 }, (_, i) => formatters.value.weekday.format(REFERENCE_SUNDAY + ((week.value + i) % 7) * DAY)),
);

/* ---- The grid and the selected day --------------------------------------------------------- */

const byDay = computed(() => {
  const map = new Map<string, CalendarEvent[]>();
  for (const event of props.events) {
    const key = event.date.slice(0, 10);
    map.set(key, [...(map.get(key) ?? []), event]);
  }
  return map;
});

const weeks = computed(() => monthGrid(props.month, week.value));
const dayEvents = computed(() => (props.modelValue ? byDay.value.get(props.modelValue) ?? [] : []));
const titleText = computed(() => formatters.value.title.format(new Date(`${props.month}-15T00:00:00Z`)));

const go = (step: 1 | -1) => {
  enter.value = step > 0 ? 'next' : 'prev';
  emit('update:month', shiftMonth(props.month, step));
};

const pick = (iso: string) => emit('update:modelValue', iso === props.modelValue ? null : iso);

// A day picked from outside its month brings the view along.
watch(
  () => props.modelValue,
  (selected) => {
    if (selected && monthOf(selected) !== props.month) {
      enter.value = monthOf(selected) > props.month ? 'next' : 'prev';
      emit('update:month', monthOf(selected));
    }
  },
);

/** Arrow keys walk the days with a roving tab stop; Home/End run to the week's edges. */
const onGridKey = (event: KeyboardEvent) => {
  const cell = (event.target as HTMLElement).closest<HTMLElement>('.nx-calendar-day');
  if (!cell) return;
  const days = Array.from(grid.value?.querySelectorAll<HTMLElement>('.nx-calendar-day') ?? []);
  const at = days.indexOf(cell);
  if (at < 0) return;
  const rtl = getComputedStyle(event.currentTarget as HTMLElement).direction === 'rtl';
  const side = rtl ? -1 : 1;
  const moves: Record<string, number> = { ArrowRight: side, ArrowLeft: -side, ArrowDown: 7, ArrowUp: -7, Home: 0, End: 0 };
  const move = moves[event.key];
  if (move === undefined) return;
  event.preventDefault();
  let next: number;
  if (event.key === 'Home') next = at - (at % 7);
  else if (event.key === 'End') next = at - (at % 7) + 6;
  else next = Math.min(days.length - 1, Math.max(0, at + move));
  days[next]?.focus();
};

// The roving anchor: the selected day when visible, else today, else the first cell.
const anchor = computed(
  () => weeks.value.flat().find((iso) => iso === props.modelValue) ?? weeks.value.flat().find((iso) => iso === today) ?? weeks.value[0]?.[0],
);

const navIcon = (direction: 'prev' | 'next'): IconName => (direction === 'prev' ? 'chevron-left' : 'chevron-right');
</script>

<template>
  <section ref="root" class="nx-calendar" :aria-labelledby="`${uid}-title`">
    <header class="nx-calendar-head">
      <h3 class="nx-calendar-title" :id="`${uid}-title`" aria-live="polite">{{ titleText }}</h3>
      <nav class="nx-calendar-nav" :aria-label="label ?? word('calendar')">
        <button type="button" class="nx-calendar-nav-btn" :aria-label="word('calendarPrevMonth')" @click="go(-1)">
          <NxIcon :name="navIcon('prev')" />
        </button>
        <button type="button" class="nx-calendar-nav-btn" :aria-label="word('calendarNextMonth')" @click="go(1)">
          <NxIcon :name="navIcon('next')" />
        </button>
      </nav>
    </header>

    <div class="nx-calendar-weekdays" aria-hidden="true">
      <span v-for="name in weekdays" :key="name" class="nx-calendar-weekday">{{ name }}</span>
    </div>

    <div
      ref="grid"
      :key="month"
      class="nx-calendar-grid"
      role="grid"
      :aria-labelledby="`${uid}-title`"
      :data-enter="enter ?? undefined"
      @keydown="onGridKey"
    >
      <div v-for="(weekRow, row) in weeks" :key="row" class="nx-calendar-week" role="row">
        <div
          v-for="iso in weekRow"
          :key="iso"
          class="nx-calendar-cell"
          role="gridcell"
          :data-out="monthOf(iso) !== month ? '' : undefined"
          :data-today="iso === today ? '' : undefined"
          :aria-selected="iso === modelValue ? 'true' : undefined"
        >
          <button
            type="button"
            class="nx-calendar-day"
            :data-date="iso"
            :data-count="(byDay.get(iso) ?? []).length > 0 ? '' : undefined"
            :tabindex="iso === anchor ? 0 : -1"
            :aria-current="iso === today ? 'date' : undefined"
            :aria-label="`${longOf(iso)}${(byDay.get(iso) ?? []).length ? `, ${word('calendarEventsCount', { count: numberFmt.format((byDay.get(iso) ?? []).length) })}` : ''}`"
            @click="pick(iso)"
          >
            <span class="nx-calendar-daynum">{{ dayOf(iso) }}</span>
            <span v-if="iso === today" class="nx-calendar-today-dot" aria-hidden="true" />
            <span
              v-if="(byDay.get(iso) ?? []).slice(0, maxPerCell).length > 0 || (byDay.get(iso) ?? []).length > maxPerCell"
              class="nx-calendar-day-events"
              aria-hidden="true"
            >
              <span v-for="(event, i) in (byDay.get(iso) ?? []).slice(0, maxPerCell)" :key="i" class="nx-calendar-chip" :data-tone="event.tone" />
              <span v-if="(byDay.get(iso) ?? []).length > maxPerCell" class="nx-calendar-more">+{{ numberFmt.format((byDay.get(iso) ?? []).length - maxPerCell) }}</span>
            </span>
          </button>
        </div>
      </div>
    </div>

    <div class="nx-calendar-panel" :data-open="modelValue ? '' : undefined" aria-live="polite">
      <template v-if="modelValue">
        <header class="nx-calendar-panel-head">
          <h4 class="nx-calendar-panel-title">{{ longOf(modelValue) }}</h4>
          <span class="nx-calendar-panel-count">{{ word('calendarEventsCount', { count: numberFmt.format(dayEvents.length) }) }}</span>
        </header>
        <ul v-if="dayEvents.length > 0" :key="modelValue" class="nx-calendar-panel-list">
          <li v-for="(event, i) in dayEvents" :key="i" :style="{ '--nx-i': i }">
            <div class="nx-calendar-panel-item">
              <span class="nx-calendar-panel-time">{{ event.time ?? '' }}</span>
              <span class="nx-calendar-panel-dot" :data-tone="event.tone" aria-hidden="true" />
              <span class="nx-calendar-panel-label">{{ event.label }}</span>
            </div>
          </li>
        </ul>
        <p v-else class="nx-calendar-panel-empty">{{ emptyText ?? word('calendarEmptyDay') }}</p>
      </template>
    </div>

    <!-- The same data as text: what a screen reader reads instead of chips. -->
    <table class="nx-visually-hidden">
      <caption>{{ titleText }}</caption>
      <tbody>
        <template v-for="[iso, list] in [...byDay.entries()].filter(([iso]) => monthOf(iso) === month).sort(([a], [b]) => (a < b ? -1 : 1))" :key="iso">
          <tr v-for="(event, i) in list" :key="`${iso}-${i}`">
            <th scope="row">{{ longOf(iso) }}</th>
            <td>{{ event.time ? `${event.time} — ` : '' }}{{ event.label }}</td>
          </tr>
        </template>
      </tbody>
    </table>
  </section>
</template>
