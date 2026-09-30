/**
 * Alpine part for the calendar block. The Blade component renders the opening
 * month (and the agenda of the day selected server-side); this takes over from
 * there — switching months client-side with the directional slide, walking the
 * grid with a roving tab stop and rebuilding the selected day's agenda. The
 * same Date math and Intl names the React component uses, over the same CSS.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installCalendarBlocks } from './alpine/blocks/calendar';
 *   installCalendarBlocks(Alpine);   // x-data="nxCalendar(config)"
 */
import { reveal } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

export interface CalendarEventJson {
  date: string;
  label: string;
  time?: string | null;
  tone?: string | null;
  href?: string | null;
}

export interface CalendarConfig {
  events: Array<{ date: string; events: CalendarEventJson[] }>;
  labels: { calendar: string; prevMonth: string; nextMonth: string; eventsCount: string; emptyDay: string };
  locale?: string;
  weekStart?: number;
  maxPerCell?: number;
  month?: string;
  selected?: string | null;
  today?: string;
}

const DAY = 86_400_000;
/** A Sunday, so weekday names can be picked by offset whatever the week starts on. */
const REFERENCE_SUNDAY = Date.UTC(2026, 0, 4);

const isoDay = (time: number) => new Date(time).toISOString().slice(0, 10);
const monthOf = (date: string) => date.slice(0, 7);
const atNoon = (iso: string) => new Date(`${iso}T00:00:00Z`);

/** "YYYY-MM" a step away (steps may cross the year). */
function shiftMonth(ym: string, step: number): string {
  const at = new Date(`${ym}-01T00:00:00Z`);
  at.setUTCMonth(at.getUTCMonth() + step);
  return `${at.getUTCFullYear()}-${String(at.getUTCMonth() + 1).padStart(2, '0')}`;
}

/** The week rows of a month: every cell's ISO day, leading and trailing days included. */
function monthGrid(ym: string, weekStart: number): string[][] {
  const first = new Date(`${ym}-01T00:00:00Z`);
  const offset = (first.getUTCDay() - weekStart + 7) % 7;
  const days = new Date(Date.UTC(first.getUTCFullYear(), first.getUTCMonth() + 1, 0)).getUTCDate();
  const rows = Math.ceil((offset + days) / 7);
  const start = first.getTime() - offset * DAY;
  return Array.from({ length: rows }, (_, row) => Array.from({ length: 7 }, (_, col) => isoDay(start + (row * 7 + col) * DAY)));
}

function element(tag: string, className?: string, text?: string): HTMLElement {
  const node = document.createElement(tag);
  if (className) node.className = className;
  // Agenda and labels are data: written as text, never parsed as markup.
  if (text !== undefined) node.textContent = text;
  return node;
}

export function installCalendarBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxCalendar', (config: CalendarConfig) => {
    const weekStart = config.weekStart ?? 0;
    const maxPerCell = Math.max(1, config.maxPerCell ?? 3);
    const byDay = new Map(config.events.map((day) => [day.date, day.events]));
    const fmt = {
      title: new Intl.DateTimeFormat(config.locale, { month: 'long', year: 'numeric', timeZone: 'UTC' }),
      weekday: new Intl.DateTimeFormat(config.locale, { weekday: 'short', timeZone: 'UTC' }),
      day: new Intl.DateTimeFormat(config.locale, { day: 'numeric', timeZone: 'UTC' }),
      long: new Intl.DateTimeFormat(config.locale, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' }),
      number: new Intl.NumberFormat(config.locale),
    };
    const withParams = (template: string, params: Record<string, string | number>) => template.replace(/:(\w+)/g, (_, name: string) => String(params[name] ?? `:${name}`));

    interface CalendarSelf {
      month: string;
      enter: 'next' | 'prev' | null;
      selected: string | null;
      model: unknown;
      $root: HTMLElement;
      paint: () => void;
      paintGrid: () => void;
      paintPanel: () => void;
      dayLabel: (iso: string) => string;
      pick: (iso: string) => void;
    }

    return {
      month: config.month ?? monthOf(config.today ?? isoDay(Date.now())),
      enter: null as 'next' | 'prev' | null,
      selected: config.selected ?? null,
      /** wire:model on the root binds the selected day (x-modelable). */
      model: (config.selected ?? null) as unknown,

      init(this: Self<CalendarSelf>) {
        reveal(this.$root, { once: true });
        this.$watch('model', (next: unknown) => {
          const iso = typeof next === 'string' && next !== '' ? next.slice(0, 10) : null;
          if (iso === this.selected) return;
          this.selected = iso;
          if (iso && monthOf(iso) !== this.month) {
            this.enter = monthOf(iso) > this.month ? 'next' : 'prev';
            this.month = monthOf(iso);
          }
        });
        this.$watch('selected', () => {
          this.model = this.selected;
          this.$dispatch('nx-select', this.selected);
          this.paint();
        });
        this.$watch('month', () => this.paint());
        this.paint();
      },

      /** The month on the header, in the page's calendar and digits. */
      get titleText(): string {
        const month = (this as unknown as { month: string }).month;
        return fmt.title.format(atNoon(`${month}-15`));
      },

      /** One day's long date plus its event count, for the day button's label. */
      dayLabel(this: Self<CalendarSelf>, iso: string): string {
        const count = byDay.get(iso)?.length ?? 0;
        const date = fmt.long.format(atNoon(iso));
        return count ? `${date}, ${withParams(config.labels.eventsCount, { count: fmt.number.format(count) })}` : date;
      },

      go(this: Self<CalendarSelf>, step: 1 | -1) {
        this.enter = step > 0 ? 'next' : 'prev';
        this.month = shiftMonth(this.month, step);
        this.$dispatch('nx-month', this.month);
      },

      /** Picking the selected day clears it. */
      pick(this: Self<CalendarSelf>, iso: string) {
        this.selected = iso === this.selected ? null : iso;
      },

      /** Arrow keys walk the days with a roving tab stop; Home/End run to the week's edges. */
      gridKey(this: Self<CalendarSelf>, event: KeyboardEvent) {
        const grid = this.$refs.grid;
        const days = Array.from(grid.querySelectorAll<HTMLButtonElement>('.nx-calendar-day'));
        const at = days.indexOf((event.target as HTMLElement).closest('.nx-calendar-day') as HTMLButtonElement);
        if (at < 0) return;
        const rtl = getComputedStyle(grid).direction === 'rtl';
        const side = rtl ? -1 : 1;
        const moves: Record<string, number> = { ArrowRight: side, ArrowLeft: -side, ArrowDown: 7, ArrowUp: -7 };
        let next: number | undefined;
        if (event.key === 'Home') next = at - (at % 7);
        else if (event.key === 'End') next = at - (at % 7) + 6;
        else if (event.key in moves) next = Math.min(days.length - 1, Math.max(0, at + moves[event.key]!));
        if (next === undefined) return;
        event.preventDefault();
        days[next]?.focus();
      },

      /** Rebuild the grid (if the month changed) and the agenda (if the day changed). */
      paint(this: Self<CalendarSelf>) {
        this.paintGrid();
        this.paintPanel();
      },

      paintGrid(this: Self<CalendarSelf>) {
        const grid = this.$refs.grid;
        if (!grid) return;
        const focusWas = grid.contains(document.activeElement) ? (document.activeElement as HTMLElement).closest<HTMLElement>('.nx-calendar-day')?.dataset.date : undefined;
        const weeks = monthGrid(this.month, weekStart);
        const today = config.today ?? isoDay(Date.now());
        const anchor = weeks.flat().find((iso) => iso === this.selected) ?? weeks.flat().find((iso) => iso === today) ?? weeks[0]?.[0];

        const rows = weeks.map((week) => {
          const row = element('div', 'nx-calendar-week');
          row.setAttribute('role', 'row');
          for (const iso of week) {
            const list = byDay.get(iso) ?? [];
            const cell = element('div', 'nx-calendar-cell');
            cell.setAttribute('role', 'gridcell');
            cell.dataset.date = iso;
            if (monthOf(iso) !== this.month) cell.setAttribute('data-out', '');
            if (iso === today) cell.setAttribute('data-today', '');
            if (iso === this.selected) cell.setAttribute('aria-selected', 'true');

            const day = document.createElement('button');
            day.type = 'button';
            day.className = 'nx-calendar-day';
            day.dataset.date = iso;
            if (list.length) day.setAttribute('data-count', '');
            day.tabIndex = iso === anchor ? 0 : -1;
            if (iso === today) day.setAttribute('aria-current', 'date');
            day.setAttribute('aria-label', this.dayLabel(iso));
            day.addEventListener('click', () => this.pick(iso));

            const num = element('span', 'nx-calendar-daynum', fmt.day.format(atNoon(iso)));
            day.append(num);
            if (iso === today) day.append(element('span', 'nx-calendar-today-dot'));
            const shown = list.slice(0, maxPerCell);
            const more = list.length - shown.length;
            if (shown.length || more > 0) {
              const chips = element('span', 'nx-calendar-day-events');
              for (const event of shown) {
                const chip = element('span', 'nx-calendar-chip');
                if (event.tone) chip.dataset.tone = event.tone;
                chips.append(chip);
              }
              if (more > 0) chips.append(element('span', 'nx-calendar-more', `+${fmt.number.format(more)}`));
              chips.setAttribute('aria-hidden', 'true');
              day.append(chips);
            }
            cell.append(day);
            row.append(cell);
          }
          return row;
        });

        // Replay the directional enter: drop the state, measure, set it again.
        grid.removeAttribute('data-enter');
        grid.replaceChildren(...rows);
        if (this.enter) {
          void grid.offsetWidth;
          grid.setAttribute('data-enter', this.enter);
        }
        // Only a rebuild that interrupted the grid's own focus steals it back.
        if (focusWas) grid.querySelector<HTMLButtonElement>(`.nx-calendar-day[data-date="${CSS.escape(focusWas)}"]`)?.focus();
        this.enter = null;
      },

      /** The selected day's agenda, rebuilt as text-only nodes and staggered by --nx-i. */
      paintPanel(this: Self<CalendarSelf>) {
        const panel = this.$refs.panel;
        if (!panel) return;
        const iso = this.selected;
        panel.toggleAttribute('data-open', !!iso);
        if (!iso) {
          panel.replaceChildren();
          return;
        }
        const list = byDay.get(iso) ?? [];
        const head = element('header', 'nx-calendar-panel-head');
        const title = element('h4', 'nx-calendar-panel-title', fmt.long.format(atNoon(iso)));
        const count = element('span', 'nx-calendar-panel-count', withParams(config.labels.eventsCount, { count: fmt.number.format(list.length) }));
        head.append(title, count);

        if (!list.length) {
          panel.replaceChildren(head, element('p', 'nx-calendar-panel-empty', config.labels.emptyDay));
          return;
        }
        const items = element('ul', 'nx-calendar-panel-list');
        list.forEach((event, i) => {
          const li = element('li');
          li.style.setProperty('--nx-i', String(i));
          const row = element(event.href ? 'a' : 'div', 'nx-calendar-panel-item');
          if (event.href) (row as HTMLAnchorElement).href = event.href;
          row.append(
            element('span', 'nx-calendar-panel-time', event.time ?? ''),
            (() => {
              const dot = element('span', 'nx-calendar-panel-dot');
              if (event.tone) dot.dataset.tone = event.tone;
              dot.setAttribute('aria-hidden', 'true');
              return dot;
            })(),
            element('span', 'nx-calendar-panel-label', event.label),
          );
          li.append(row);
          items.append(li);
        });
        panel.replaceChildren(head, items);
      },
    };
  });
}
