<script lang="ts">
  /**
   * Calendar — the month grid with a real selection and view: picking a day
   * opens the block's own agenda panel, the "today" button brings both the
   * month and the selection home, and the "new event" dialog really appends to
   * the state (the chip lands on its day at once). Beside the grid sits the
   * upcoming list — the next six events as day chips in the locale's calendar.
   * Events are seeded on fixed offsets from the shared NOW, so the page looks
   * the same on every load; fa gets the Persian week (starting Saturday).
   */
  import { toast } from '@nabuxai/ui-core';
  import { app, intlLocale, strings, tr } from '../store.svelte';
  import { NOW } from '../data';
  import Calendar, { type CalendarEvent } from '../lib/Calendar.svelte';
  import NxIcon from '../lib/NxIcon.svelte';

  type Tone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';

  /** A local-day ISO `offset` days from the shared NOW. */
  function isoFromOffset(offset: number) {
    const day = new Date(NOW);
    day.setDate(day.getDate() + offset);
    return `${day.getFullYear()}-${String(day.getMonth() + 1).padStart(2, '0')}-${String(day.getDate()).padStart(2, '0')}`;
  }

  /** A seeded event: both languages kept, so the page survives a language switch. */
  type PanelEvent = { id: string; date: string; fa: string; en: string; timeFa?: string; timeEn?: string; tone?: Tone };

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

  const s = $derived(strings());
  const c = $derived(s.pages.calendar);
  const today = isoFromOffset(0);

  let events = $state<PanelEvent[]>(SEED);
  let selected = $state<string | null>(today);
  let viewing = $state(today.slice(0, 7));
  let composing = $state(false);
  let draft = $state({ label: '', date: today, time: '' });
  let dialog: HTMLDialogElement;

  const calendarEvents = $derived<CalendarEvent[]>(
    events.map((event) => ({
      date: event.date,
      label: tr(event.fa, event.en),
      time: event.timeFa ? tr(event.timeFa, event.timeEn ?? event.timeFa) : undefined,
      tone: event.tone,
    })),
  );

  const upcoming = $derived(events.filter((event) => event.date >= today).sort((a, b) => (a.date < b.date ? -1 : a.date > b.date ? 1 : 0)).slice(0, 6));

  // "شنبه ۱۵" — the locale's weekday and day, pinned to UTC so the chip is the day we wrote.
  const weekday = $derived(new Intl.DateTimeFormat(intlLocale(), { weekday: 'short', day: 'numeric', timeZone: 'UTC' }));
  const dayChip = (iso: string) => weekday.format(new Date(`${iso}T00:00:00Z`));

  const goToday = () => {
    selected = today;
    viewing = today.slice(0, 7);
  };

  $effect(() => {
    if (composing) dialog?.showModal();
    else dialog?.close();
  });

  const canAdd = $derived(draft.label.trim().length > 0 && /^\d{4}-\d{2}-\d{2}$/.test(draft.date));

  const addEvent = () => {
    const label = draft.label.trim();
    if (!label || !canAdd) return;
    const time = draft.time.trim();
    events = [...events, { id: `e-${Date.now().toString(36)}`, date: draft.date, fa: label, en: label, timeFa: time || undefined, timeEn: time || undefined, tone: 'accent' }];
    selected = draft.date;
    viewing = draft.date.slice(0, 7);
    composing = false;
    draft = { label: '', date: draft.date, time: '' };
    toast(c.added);
  };

  // Clicks on the backdrop (the dialog element itself) close; clicks inside flow on.
  const backdropClose = (event: MouseEvent) => {
    if (event.target === event.currentTarget) composing = false;
  };
</script>

<div class="adm-dashboard">
  <div class="adm-calendar-main">
    <div class="adm-welcome-actions">
      <button type="button" class="nx-button" data-variant="primary" onclick={() => (composing = true)}>
        <span class="nx-button-label">
          <NxIcon name="plus" />
          <span class="nx-button-text">{c.action}</span>
        </span>
      </button>
      <button type="button" class="nx-button" data-variant="secondary" onclick={goToday}>
        <span class="nx-button-label">
          <NxIcon name="grid" />
          <span class="nx-button-text">{c.today}</span>
        </span>
      </button>
    </div>
    <Calendar events={calendarEvents} bind:value={selected} bind:month={viewing} weekStart={app.lang === 'fa' ? 6 : 0} label={c.title} emptyText={c.noEvents} />
  </div>

  <div class="adm-side">
    <section class="nx-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="bell" /></span>
        <h2 class="nx-card-title">{c.upcoming}</h2>
      </div>
      <div class="nx-card-body">
        {#if upcoming.length > 0}
          <ul class="adm-upcoming">
            {#each upcoming as event (event.id)}
              <li class="adm-upcoming-row">
                <span class="nx-badge" data-tone={event.tone}>{dayChip(event.date)}</span>
                <span>{tr(event.fa, event.en)}</span>
                {#if event.timeFa}
                  <span dir="ltr" class="adm-upcoming-time">{tr(event.timeFa, event.timeEn ?? event.timeFa)}</span>
                {/if}
              </li>
            {/each}
          </ul>
        {:else}
          <p class="adm-upcoming-empty">{c.noEvents}</p>
        {/if}
      </div>
    </section>
  </div>

  <dialog bind:this={dialog} class="nx-dialog" data-size="sm" onclick={backdropClose} onclose={() => (composing = false)}>
    <header class="nx-dialog-header">
      <h2 class="nx-dialog-title">{c.action}</h2>
    </header>
    <div class="nx-dialog-body adm-form">
      <div class="nx-field">
        <label class="nx-label" for="event-title">{c.fieldTitle}</label>
        <input id="event-title" class="nx-input" autocomplete="off" bind:value={draft.label} />
      </div>
      <div class="nx-field">
        <label class="nx-label" for="event-day">{c.fieldDay}</label>
        <input id="event-day" class="nx-input" type="date" dir="ltr" bind:value={draft.date} />
      </div>
      <div class="nx-field">
        <label class="nx-label" for="event-time">{c.fieldTime}</label>
        <input id="event-time" class="nx-input" type="time" dir="ltr" bind:value={draft.time} />
      </div>
    </div>
    <footer class="nx-dialog-footer">
      <button type="button" class="nx-button" data-variant="secondary" onclick={() => (composing = false)}>
        <span class="nx-button-label"><span class="nx-button-text">{s.common.cancel}</span></span>
      </button>
      <button type="button" class="nx-button" data-variant="primary" disabled={!canAdd} onclick={addEvent}>
        <span class="nx-button-label">
          <NxIcon name="plus" />
          <span class="nx-button-text">{s.common.add}</span>
        </span>
      </button>
    </footer>
    <button type="button" class="nx-dialog-close" aria-label={s.common.close} onclick={() => (composing = false)}>
      <NxIcon name="x" />
    </button>
  </dialog>
</div>

<style>
  /* The grid and its toolbar on one side, the upcoming card beside it. */
  @media (min-width: 72rem) {
    .adm-calendar-main {
      grid-column: 1;
    }

    .adm-side {
      grid-column: 2;
      grid-row: 1 / span 2;
      align-self: start;
    }
  }

  .adm-upcoming {
    display: grid;
    gap: var(--nx-space-2);
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .adm-upcoming-row {
    display: flex;
    align-items: center;
    gap: var(--nx-space-3);
    font-size: var(--nx-text-sm);
  }

  .adm-upcoming-time {
    margin-inline-start: auto;
    color: var(--nx-text-subtle);
    font-family: var(--nx-font-mono);
    font-size: var(--nx-text-xs);
  }

  .adm-upcoming-empty {
    margin: 0;
    color: var(--nx-text-muted);
  }
</style>
