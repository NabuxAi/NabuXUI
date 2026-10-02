<script lang="ts" module>
  export interface CalendarEvent {
    /** Which day the chip sits on: "YYYY-MM-DD". */
    date: string;
    label: string;
    tone?: 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';
    /** A free-form time shown in the day panel ("09:30", "۹:۳۰ صبح"). */
    time?: string;
  }

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

  /** "YYYY-MM" a step away (steps may cross the year). */
  export function shiftMonth(ym: string, step: number): string {
    const [y, m] = ym.split('-').map(Number);
    const at = Date.UTC(y!, (m! - 1) + step, 1);
    return `${new Date(at).getUTCFullYear()}-${String(new Date(at).getUTCMonth() + 1).padStart(2, '0')}`;
  }

  /** The six-ish week rows of a month: every cell's ISO day, leading and trailing days included. */
  export function monthGrid(ym: string, weekStart: number): string[][] {
    const first = new Date(`${ym}-01T00:00:00Z`);
    const offset = (first.getUTCDay() - weekStart + 7) % 7;
    const days = new Date(Date.UTC(first.getUTCFullYear(), first.getUTCMonth() + 1, 0)).getUTCDate();
    const rows = Math.ceil((offset + days) / 7);
    const start = first.getTime() - offset * DAY;
    return Array.from({ length: rows }, (_, row) => Array.from({ length: 7 }, (_, col) => isoDay(start + (row * 7 + col) * DAY)));
  }
</script>

<script lang="ts">
  /**
   * The calendar — the hand-written Svelte shape of the `calendar` block
   * (nx-calendar): a month grid whose switch slides in from the side it comes
   * (mirrored by --nx-dir in RTL, collapsed to a fade by --nx-motion), event
   * chips in the cells with a "+N" tally, the selected day's agenda panel, and
   * a visually-hidden table of the same data. Arrow keys walk the grid with a
   * roving tab stop; names and digits follow the locale ("شهریور" vs
   * "September", ۱۵ vs 15).
   */
  import { onMount } from 'svelte';
  import { type Cleanup, reveal, translate } from '@nabuxai/ui-core';
  import { app, intlLocale } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  let {
    events = [],
    value = $bindable(null),
    month = $bindable(undefined),
    weekStart = undefined,
    maxPerCell = 3,
    label = undefined,
    emptyText = undefined,
  }: {
    events?: CalendarEvent[];
    value?: string | null;
    month?: string;
    /** 0 Sunday, 1 Monday, 6 Saturday (the Persian week). Defaults to the language's week. */
    weekStart?: 0 | 1 | 6;
    maxPerCell?: number;
    label?: string;
    emptyText?: string;
  } = $props();

  const today = todayISO();
  let enter = $state<'next' | 'prev' | null>(null);
  // Inside a keyed block (one remount per month), so the binding writes again.
  let grid = $state<HTMLDivElement | undefined>(undefined);
  let root: HTMLElement;
  let stopReveal: Cleanup | null = null;

  const intl = $derived(intlLocale());
  // The week starts on Saturday in the Persian calendar, Sunday elsewhere.
  const week = $derived(weekStart ?? (app.lang === 'fa' ? 6 : 0));

  const formatters = $derived.by(() => ({
    title: new Intl.DateTimeFormat(intl, { month: 'long', year: 'numeric', timeZone: 'UTC' }),
    weekday: new Intl.DateTimeFormat(intl, { weekday: 'short', timeZone: 'UTC' }),
    day: new Intl.DateTimeFormat(intl, { day: 'numeric', timeZone: 'UTC' }),
    long: new Intl.DateTimeFormat(intl, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' }),
    number: new Intl.NumberFormat(intl),
  }));

  const dayOf = (iso: string) => formatters.day.format(new Date(`${iso}T00:00:00Z`));
  const longOf = (iso: string) => formatters.long.format(new Date(`${iso}T00:00:00Z`));

  const weekdays = $derived(Array.from({ length: 7 }, (_, i) => formatters.weekday.format(REFERENCE_SUNDAY + ((week + i) % 7) * DAY)));

  const byDay = $derived.by(() => {
    const map = new Map<string, CalendarEvent[]>();
    for (const event of events) {
      const key = event.date.slice(0, 10);
      map.set(key, [...(map.get(key) ?? []), event]);
    }
    return map;
  });

  const viewing = $derived(month ?? monthOf(value ?? today));
  const weeks = $derived(monthGrid(viewing, week));
  const dayEvents = $derived(value ? byDay.get(value) ?? [] : []);
  const titleText = $derived(formatters.title.format(new Date(`${viewing}-15T00:00:00Z`)));

  const word = (key: 'calendarPrevMonth' | 'calendarNextMonth' | 'calendar' | 'calendarEventsCount' | 'calendarEmptyDay', params: Record<string, string | number> = {}) =>
    translate(app.lang, key, params);

  const go = (step: 1 | -1) => {
    enter = step > 0 ? 'next' : 'prev';
    month = shiftMonth(viewing, step);
  };

  const pick = (iso: string) => {
    value = iso === value ? null : iso;
    // A day picked from outside its month brings the view along.
    if (month === undefined && value && monthOf(value) !== viewing) {
      enter = monthOf(value) > viewing ? 'next' : 'prev';
      month = monthOf(value);
    }
  };

  // Arrow keys walk the days with a roving tab stop; Home/End run to the week's edges.
  function onGridKey(event: KeyboardEvent) {
    const cell = (event.target as HTMLElement).closest<HTMLElement>('.nx-calendar-day');
    if (!cell) return;
    const days = Array.from(grid?.querySelectorAll<HTMLElement>('.nx-calendar-day') ?? []);
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
  }

  // The roving anchor: the selected day when visible, else today, else the first cell.
  const anchor = $derived(weeks.flat().find((iso) => iso === value) ?? weeks.flat().find((iso) => iso === today) ?? weeks[0]?.[0]);

  onMount(() => {
    stopReveal = reveal(root, { once: true });
    return () => stopReveal?.();
  });
</script>

<section bind:this={root} class="nx-calendar" data-nx-reveal="" aria-labelledby="nx-calendar-title">
  <header class="nx-calendar-head">
    <h3 class="nx-calendar-title" id="nx-calendar-title" aria-live="polite">
      {titleText}
    </h3>
    <nav class="nx-calendar-nav" aria-label={label ?? word('calendar')}>
      <button type="button" class="nx-calendar-nav-btn" aria-label={word('calendarPrevMonth')} onclick={() => go(-1)}>
        <NxIcon name="chevron-left" />
      </button>
      <button type="button" class="nx-calendar-nav-btn" aria-label={word('calendarNextMonth')} onclick={() => go(1)}>
        <NxIcon name="chevron-right" />
      </button>
    </nav>
  </header>

  <div class="nx-calendar-weekdays" aria-hidden="true">
    {#each weekdays as name (name)}
      <span class="nx-calendar-weekday">{name}</span>
    {/each}
  </div>

  {#key viewing}
    <!-- svelte-ignore a11y_no_noninteractive_tabindex -->
    <div bind:this={grid} class="nx-calendar-grid" role="grid" tabindex="-1" aria-labelledby="nx-calendar-title" data-enter={enter ?? undefined} onkeydown={onGridKey}>
      {#each weeks as weekRow, row (row)}
        <div class="nx-calendar-week" role="row">
          {#each weekRow as iso (iso)}
            {@const list = byDay.get(iso) ?? []}
            {@const shown = list.slice(0, maxPerCell)}
            {@const more = list.length - shown.length}
            <div
              class="nx-calendar-cell"
              role="gridcell"
              data-out={monthOf(iso) !== viewing ? '' : undefined}
              data-today={iso === today ? '' : undefined}
              aria-selected={iso === value}
            >
              <button
                type="button"
                class="nx-calendar-day"
                data-date={iso}
                data-count={list.length > 0 ? '' : undefined}
                tabindex={iso === anchor ? 0 : -1}
                aria-current={iso === today ? 'date' : undefined}
                aria-label={`${longOf(iso)}${list.length ? `, ${word('calendarEventsCount', { count: formatters.number.format(list.length) })}` : ''}`}
                onclick={() => pick(iso)}
              >
                <span class="nx-calendar-daynum">{dayOf(iso)}</span>
                {#if iso === today}<span class="nx-calendar-today-dot" aria-hidden="true"></span>{/if}
                {#if shown.length > 0 || more > 0}
                  <span class="nx-calendar-day-events" aria-hidden="true">
                    {#each shown as event, i (i)}
                      <span class="nx-calendar-chip" data-tone={event.tone}></span>
                    {/each}
                    {#if more > 0}
                      <span class="nx-calendar-more">+{formatters.number.format(more)}</span>
                    {/if}
                  </span>
                {/if}
              </button>
            </div>
          {/each}
        </div>
      {/each}
    </div>
  {/key}

  <div class="nx-calendar-panel" data-open={value ? '' : undefined} aria-live="polite">
    {#if value}
      <header class="nx-calendar-panel-head">
        <h4 class="nx-calendar-panel-title">{longOf(value)}</h4>
        <span class="nx-calendar-panel-count">{word('calendarEventsCount', { count: formatters.number.format(dayEvents.length) })}</span>
      </header>
      {#if dayEvents.length > 0}
        {#key value}
          <ul class="nx-calendar-panel-list">
            {#each dayEvents as event, i (i)}
              <li style:--nx-i={i}>
                <div class="nx-calendar-panel-item">
                  <span class="nx-calendar-panel-time">{event.time ?? ''}</span>
                  <span class="nx-calendar-panel-dot" data-tone={event.tone} aria-hidden="true"></span>
                  <span class="nx-calendar-panel-label">{event.label}</span>
                </div>
              </li>
            {/each}
          </ul>
        {/key}
      {:else}
        <p class="nx-calendar-panel-empty">{emptyText ?? word('calendarEmptyDay')}</p>
      {/if}
    {/if}
  </div>

  <!-- The same data as text: what a screen reader reads instead of chips. -->
  <table class="nx-visually-hidden">
    <caption>{titleText}</caption>
    <tbody>
      {#each [...byDay.entries()].filter(([iso]) => monthOf(iso) === viewing).sort(([a], [b]) => (a < b ? -1 : 1)) as [iso, list] (iso)}
        {#each list as event, i (`${iso}-${i}`)}
          <tr>
            <th scope="row">{longOf(iso)}</th>
            <td>{event.time ? `${event.time} — ` : ''}{event.label}</td>
          </tr>
        {/each}
      {/each}
    </tbody>
  </table>
</section>
