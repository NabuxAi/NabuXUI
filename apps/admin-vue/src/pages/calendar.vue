<script setup lang="ts">
/**
 * Calendar — the month grid with a controlled selection and view: picking a
 * day opens the block's own agenda panel, the "today" button brings both the
 * month and the selection home, and the "new event" dialog really appends to
 * the state (the chip lands on its day at once). Beside the grid sits the
 * upcoming list — the next six events as day chips in the locale's calendar.
 * Events are seeded on fixed offsets from the shared NOW, so the page looks
 * the same on every load; fa gets the Persian week (starting Saturday) and
 * the Jalali month names straight from the NxCalendar block.
 */
import { computed, nextTick, ref, watch } from 'vue';
import { toast } from '@nabuxai/ui-core';
import { NOW } from '../data';
import { intlLocale, s, tr } from '../store';
import NxCalendar, { type CalendarEvent, type CalendarTone } from '../components/NxCalendar.vue';
import NxIcon from '../components/NxIcon.vue';

const c = computed(() => s.value.pages.calendar);

/** A local-day ISO `offset` days from the shared NOW — how the block reads days too. */
function isoFromOffset(offset: number) {
  const day = new Date(NOW);
  day.setDate(day.getDate() + offset);
  return `${day.getFullYear()}-${String(day.getMonth() + 1).padStart(2, '0')}-${String(day.getDate()).padStart(2, '0')}`;
}

/** An event as state: both languages kept, so the page survives a language switch. */
interface PanelEvent {
  id: string;
  date: string;
  fa: string;
  en: string;
  timeFa?: string;
  timeEn?: string;
  tone?: CalendarTone;
}

const SEED: PanelEvent[] = [
  { id: 'e1', date: isoFromOffset(-3), fa: 'پشتیبان‌گیری ماهانه', en: 'The monthly backup', tone: 'success' },
  { id: 'e2', date: isoFromOffset(-1), fa: 'بازگشت از مرخصی سارا', en: 'Sara is back from leave', tone: 'success' },
  { id: 'e3', date: isoFromOffset(0), fa: 'جلسهٔ تیم فروش', en: 'The sales team meets', timeFa: '۱۰:۰۰', timeEn: '10:00', tone: 'accent' },
  { id: 'e4', date: isoFromOffset(0), fa: 'ارسال خبرنامه', en: 'Send the newsletter', timeFa: '۱۶:۳۰', timeEn: '16:30' },
  { id: 'e5', date: isoFromOffset(1), fa: 'مهلت پرداخت فاکتور ۱۲۴۸', en: 'Invoice 1248 falls due', tone: 'danger' },
  { id: 'e6', date: isoFromOffset(3), fa: 'جلسهٔ طراحی صفحهٔ محصول', en: 'Product page design review', timeFa: '۱۴:۰۰', timeEn: '14:00', tone: 'info' },
  { id: 'e7', date: isoFromOffset(5), fa: 'مصاحبهٔ پشتیبانی — نامزد دوم', en: 'Support interview — second candidate', timeFa: '۱۱:۰۰', timeEn: '11:00', tone: 'accent' },
  { id: 'e8', date: isoFromOffset(8), fa: 'تحویل عکسهای محصول', en: 'Product photos delivered', tone: 'warning' },
  { id: 'e9', date: isoFromOffset(12), fa: 'گزارش مالی ماهانه', en: 'The monthly financial report', tone: 'gold' },
  { id: 'e10', date: isoFromOffset(16), fa: 'کارگاه تجربهٔ مشتری', en: 'The customer experience workshop', timeFa: '۱۳:۰۰', timeEn: '13:00', tone: 'info' },
  { id: 'e11', date: isoFromOffset(21), fa: 'بازبینی طرحهای نوروز', en: 'Review the Nowruz designs', tone: 'accent' },
];

const events = ref<PanelEvent[]>(SEED);
const today = isoFromOffset(0);
const selected = ref<string | null>(today);
const viewing = ref(today.slice(0, 7));
const composing = ref(false);
const draft = ref({ label: '', date: today, time: '' });
const dialog = ref<HTMLDialogElement | null>(null);

watch(composing, (open) => void nextTick(() => (open ? dialog.value?.showModal() : dialog.value?.close())));
const closeCompose = () => (composing.value = false);

const calendarEvents = computed<CalendarEvent[]>(() =>
  events.value.map((event) => ({
    date: event.date,
    label: tr(event.fa, event.en),
    time: event.timeFa ? tr(event.timeFa, event.timeEn ?? event.timeFa) : undefined,
    tone: event.tone,
  })),
);

const upcoming = computed(() =>
  events.value
    .filter((event) => event.date >= today)
    .sort((a, b) => (a.date < b.date ? -1 : a.date > b.date ? 1 : 0))
    .slice(0, 6),
);

const weekday = computed(() => new Intl.DateTimeFormat(intlLocale.value, { weekday: 'short', day: 'numeric', timeZone: 'UTC' }));
const dayChip = (iso: string) => weekday.value.format(new Date(`${iso}T00:00:00Z`));

const goToday = () => {
  selected.value = today;
  viewing.value = today.slice(0, 7);
};

const canAdd = computed(() => draft.value.label.trim().length > 0 && /^\d{4}-\d{2}-\d{2}$/.test(draft.value.date));

const addEvent = () => {
  const label = draft.value.label.trim();
  if (!label || !canAdd.value) return;
  const time = draft.value.time.trim();
  events.value = [...events.value, { id: `e-${Date.now().toString(36)}`, date: draft.value.date, fa: label, en: label, timeFa: time || undefined, timeEn: time || undefined, tone: 'accent' }];
  selected.value = draft.value.date;
  viewing.value = draft.value.date.slice(0, 7);
  composing.value = false;
  draft.value = { label: '', date: draft.value.date, time: '' };
  toast(c.value.added);
};
</script>

<template>
  <div class="adm-dashboard">
    <div class="adm-calendar-main">
      <div class="adm-welcome-actions">
        <button type="button" class="nx-button" data-variant="primary" @click="composing = true">
          <span class="nx-button-label">
            <NxIcon name="plus" />
            <span class="nx-button-text">{{ c.action }}</span>
          </span>
        </button>
        <button type="button" class="nx-button" data-variant="secondary" @click="goToday">
          <span class="nx-button-label">
            <NxIcon name="grid" />
            <span class="nx-button-text">{{ c.today }}</span>
          </span>
        </button>
      </div>
      <NxCalendar v-model="selected" v-model:month="viewing" :events="calendarEvents" :label="c.title" :empty-text="c.noEvents" />
    </div>

    <div class="adm-side">
      <section class="nx-card">
        <div class="nx-card-header">
          <span class="nx-card-icon"><NxIcon name="bell" /></span>
          <h2 class="nx-card-title">{{ c.upcoming }}</h2>
        </div>
        <div class="nx-card-body">
          <ul v-if="upcoming.length > 0" class="adm-upcoming">
            <li v-for="event in upcoming" :key="event.id" class="adm-upcoming-row">
              <span class="nx-badge" :data-tone="event.tone">{{ dayChip(event.date) }}</span>
              <span>{{ tr(event.fa, event.en) }}</span>
              <span v-if="event.timeFa" dir="ltr" class="adm-upcoming-time">{{ tr(event.timeFa, event.timeEn ?? event.timeFa) }}</span>
            </li>
          </ul>
          <p v-else class="adm-upcoming-empty">{{ c.noEvents }}</p>
        </div>
      </section>
    </div>

    <dialog ref="dialog" class="nx-dialog" data-size="sm" @close="closeCompose" @click.self="closeCompose">
      <header class="nx-dialog-header">
        <h2 class="nx-dialog-title">{{ c.action }}</h2>
      </header>
      <div class="nx-dialog-body adm-form">
        <div class="nx-field">
          <label class="nx-label" for="event-title">{{ c.fieldTitle }}</label>
          <input id="event-title" v-model="draft.label" class="nx-input" autocomplete="off" required />
        </div>
        <div class="nx-field">
          <label class="nx-label" for="event-day">{{ c.fieldDay }}</label>
          <input id="event-day" v-model="draft.date" class="nx-input" type="date" dir="ltr" required />
        </div>
        <div class="nx-field">
          <label class="nx-label" for="event-time">{{ c.fieldTime }}</label>
          <input id="event-time" v-model="draft.time" class="nx-input" type="time" dir="ltr" />
        </div>
      </div>
      <footer class="nx-dialog-footer">
        <button type="button" class="nx-button" data-variant="secondary" @click="closeCompose">
          <span class="nx-button-label"><span class="nx-button-text">{{ s.common.cancel }}</span></span>
        </button>
        <button type="button" class="nx-button" data-variant="primary" :disabled="!canAdd" @click="addEvent">
          <span class="nx-button-label">
            <NxIcon name="plus" />
            <span class="nx-button-text">{{ s.common.add }}</span>
          </span>
        </button>
      </footer>
      <button type="button" class="nx-dialog-close" :aria-label="s.common.close" @click="closeCompose">
        <NxIcon name="x" />
      </button>
    </dialog>
  </div>
</template>
