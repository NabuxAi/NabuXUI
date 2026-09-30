/**
 * Calendar — a month grid with a directional month switch (React).
 *
 * Thin over css/blocks/calendar.css. Plain Date math (no date library): a
 * month is "YYYY-MM", a day is "YYYY-MM-DD". Switching months remounts the
 * grid entering from the side it comes from — mirrored by --nx-dir in
 * right-to-left pages, collapsed to a fade by --nx-motion. Events arrive as
 * data: coloured chips in the cells (a "+N" tally when they do not fit), the
 * selected day's agenda below, and a visually-hidden table of the same data.
 * Arrow keys walk the grid with a roving tab stop; names and digits follow the
 * locale ("شهریور" vs "September", ۱۵ vs 15).
 */
import { type CSSProperties, type HTMLAttributes, type KeyboardEvent, type ReactNode, useEffect, useId, useMemo, useRef, useState } from 'react';
import { reveal } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale } from '../internal/provider';

/* ---- The block's own words (kept local until they graduate into the core table) --------- */

const words = {
  en: { calendar: 'Calendar', prevMonth: 'Previous month', nextMonth: 'Next month', eventsCount: '{count} events', emptyDay: 'No events' },
  fa: { calendar: 'تقویم', prevMonth: 'ماه قبل', nextMonth: 'ماه بعد', eventsCount: '{count} رویداد', emptyDay: 'رویدادی نیست' },
  ar: { calendar: 'التقويم', prevMonth: 'الشهر السابق', nextMonth: 'الشهر التالي', eventsCount: '{count} أحداث', emptyDay: 'لا أحداث' },
} as const;

type WordKey = keyof (typeof words)['en'];
type Language = keyof typeof words;

function useWord(): (key: WordKey, params?: Record<string, string | number>) => string {
  const locale = useLocale();
  const language: Language = locale in words ? (locale as Language) : 'en';
  return (key, params = {}) => {
    const text = words[language][key] ?? words.en[key];
    return text.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`));
  };
}

/* ---- Types ---------------------------------------------------------------------------- */

export type CalendarTone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';

export interface CalendarEvent {
  /** Which day the chip sits on: "YYYY-MM-DD" (an ISO datetime's day part works too). */
  date: string;
  label: string;
  tone?: CalendarTone;
  /** A free-form time shown in the day view ("09:30", "۹:۳۰ صبح"). */
  time?: string;
  href?: string;
}

export interface CalendarProps extends Omit<HTMLAttributes<HTMLElement>, 'children' | 'defaultValue'> {
  events?: CalendarEvent[];
  /** The selected day, "YYYY-MM-DD"; null for none. */
  value?: string | null;
  defaultValue?: string | null;
  onValueChange?: (date: string | null) => void;
  /** The month on show, "YYYY-MM" (defaults to the selected day's, else this month's). */
  month?: string;
  defaultMonth?: string;
  onMonthChange?: (month: string) => void;
  /** First day of the week: 0 Sunday, 1 Monday, 6 Saturday (the Persian week). Defaults to the locale's week — Saturday for fa/ar, Sunday elsewhere. */
  weekStart?: 0 | 1 | 6;
  /** Event chips each cell shows before the "+N" tally (3 by default). */
  maxPerCell?: number;
  /** The calendar's accessible name. */
  label?: string;
  emptyText?: ReactNode;
  locale?: string;
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

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

export function Calendar({
  events = [],
  value,
  defaultValue = null,
  onValueChange,
  month,
  defaultMonth,
  onMonthChange,
  weekStart,
  maxPerCell = 3,
  label,
  emptyText,
  locale,
  className,
  ...rest
}: CalendarProps) {
  const word = useWord();
  const language = useLocale();
  // The week starts on Saturday in the Persian and Arabic calendars, Sunday elsewhere.
  const week = weekStart ?? (language === 'fa' || language === 'ar' ? 6 : 0);
  const intl = locale ?? (language === 'fa' ? 'fa-IR' : language === 'ar' ? 'ar' : 'en-US');
  const id = useId();
  const root = useRef<HTMLElement>(null);
  const today = useMemo(todayISO, []);
  const [selected, setSelected] = useControllable<string | null>(value, defaultValue, onValueChange);
  const [viewing, setViewing] = useControllable(month, defaultMonth ?? monthOf(selected ?? today), onMonthChange);
  const [enter, setEnter] = useState<'next' | 'prev' | null>(null);
  const grid = useRef<HTMLDivElement>(null);
  useBehavior(root, reveal, { once: true });

  // A day picked from outside its month brings the view along (when the app does not pin the month).
  useEffect(() => {
    if (month === undefined && selected && monthOf(selected) !== viewing) {
      setEnter(monthOf(selected) > viewing ? 'next' : 'prev');
      setViewing(monthOf(selected));
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [selected]);

  const formatters = useMemo(
    () => ({
      title: new Intl.DateTimeFormat(intl, { month: 'long', year: 'numeric', timeZone: 'UTC' }),
      weekday: new Intl.DateTimeFormat(intl, { weekday: 'short', timeZone: 'UTC' }),
      day: new Intl.DateTimeFormat(intl, { day: 'numeric', timeZone: 'UTC' }),
      long: new Intl.DateTimeFormat(intl, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' }),
      number: new Intl.NumberFormat(intl),
    }),
    [intl],
  );

  const dayOf = (iso: string) => formatters.day.format(new Date(`${iso}T00:00:00Z`));

  const weekdays = useMemo(
    () => Array.from({ length: 7 }, (_, i) => formatters.weekday.format(REFERENCE_SUNDAY + ((week + i) % 7) * DAY)),
    [formatters, week],
  );

  const byDay = useMemo(() => {
    const map = new Map<string, CalendarEvent[]>();
    for (const event of events) {
      const key = event.date.slice(0, 10);
      map.set(key, [...(map.get(key) ?? []), event]);
    }
    return map;
  }, [events]);

  const weeks = useMemo(() => monthGrid(viewing, week), [viewing, week]);
  const dayEvents = selected ? byDay.get(selected) ?? [] : [];

  const go = (step: 1 | -1) => {
    setEnter(step > 0 ? 'next' : 'prev');
    setViewing(shiftMonth(viewing, step));
  };

  const pick = (iso: string) => setSelected(iso === selected ? null : iso);

  // Arrow keys walk the days with a roving tab stop; Home/End run to the week's edges.
  const onGridKey = (event: KeyboardEvent<HTMLDivElement>) => {
    const cell = (event.target as HTMLElement).closest<HTMLElement>('.nx-calendar-day');
    if (!cell) return;
    const days = Array.from(grid.current?.querySelectorAll<HTMLElement>('.nx-calendar-day') ?? []);
    const at = days.indexOf(cell);
    if (at < 0) return;
    const rtl = getComputedStyle(event.currentTarget).direction === 'rtl';
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
  const anchor = useMemo(() => weeks.flat().find((iso) => iso === selected) ?? weeks.flat().find((iso) => iso === today) ?? weeks[0]?.[0], [weeks, selected, today]);
  const titleText = formatters.title.format(new Date(`${viewing}-15T00:00:00Z`));

  return (
    <section ref={root} className={cx('nx-calendar', className)} aria-labelledby={`${id}-title`} {...rest}>
      <header className="nx-calendar-head">
        <h3 className="nx-calendar-title" id={`${id}-title`} aria-live="polite">
          {titleText}
        </h3>
        <nav className="nx-calendar-nav" aria-label={label ?? word('calendar')}>
          <button type="button" className="nx-calendar-nav-btn" aria-label={word('prevMonth')} onClick={() => go(-1)}>
            <Icon name="chevron-left" />
          </button>
          <button type="button" className="nx-calendar-nav-btn" aria-label={word('nextMonth')} onClick={() => go(1)}>
            <Icon name="chevron-right" />
          </button>
        </nav>
      </header>

      <div className="nx-calendar-weekdays" aria-hidden="true">
        {weekdays.map((name) => (
          <span key={name} className="nx-calendar-weekday">
            {name}
          </span>
        ))}
      </div>

      <div
        ref={grid}
        className="nx-calendar-grid"
        role="grid"
        aria-labelledby={`${id}-title`}
        key={viewing}
        data-enter={enter ?? undefined}
        onKeyDown={onGridKey}
      >
        {weeks.map((week, row) => (
          <div className="nx-calendar-week" role="row" key={row}>
            {week.map((iso) => {
              const list = byDay.get(iso) ?? [];
              const shown = list.slice(0, maxPerCell);
              const more = list.length - shown.length;
              const isToday = iso === today;
              const isSelected = iso === selected;
              return (
                <div
                  className="nx-calendar-cell"
                  role="gridcell"
                  key={iso}
                  data-out={monthOf(iso) !== viewing ? '' : undefined}
                  data-today={isToday ? '' : undefined}
                  aria-selected={isSelected}
                >
                  <button
                    type="button"
                    className="nx-calendar-day"
                    data-date={iso}
                    data-count={list.length > 0 ? '' : undefined}
                    tabIndex={iso === anchor ? 0 : -1}
                    aria-current={isToday ? 'date' : undefined}
                    aria-label={`${formatters.long.format(new Date(`${iso}T00:00:00Z`))}${list.length ? `, ${word('eventsCount', { count: formatters.number.format(list.length) })}` : ''}`}
                    onClick={() => pick(iso)}
                  >
                    <span className="nx-calendar-daynum">{dayOf(iso)}</span>
                    {isToday && <span className="nx-calendar-today-dot" aria-hidden="true" />}
                    {(shown.length > 0 || more > 0) && (
                      <span className="nx-calendar-day-events" aria-hidden="true">
                        {shown.map((event, i) => (
                          <span key={i} className="nx-calendar-chip" data-tone={event.tone} />
                        ))}
                        {more > 0 && (
                          <span className="nx-calendar-more">
                            {'+'}
                            {formatters.number.format(more)}
                          </span>
                        )}
                      </span>
                    )}
                  </button>
                </div>
              );
            })}
          </div>
        ))}
      </div>

      <div className="nx-calendar-panel" data-open={selected ? '' : undefined} aria-live="polite">
        {selected && (
          <>
            <header className="nx-calendar-panel-head">
              <h4 className="nx-calendar-panel-title">{formatters.long.format(new Date(`${selected}T00:00:00Z`))}</h4>
              <span className="nx-calendar-panel-count">{word('eventsCount', { count: formatters.number.format(dayEvents.length) })}</span>
            </header>
            {dayEvents.length > 0 ? (
              <ul className="nx-calendar-panel-list" key={selected}>
                {dayEvents.map((event, i) => {
                  const row = (
                    <>
                      <span className="nx-calendar-panel-time">{event.time ?? ''}</span>
                      <span className="nx-calendar-panel-dot" data-tone={event.tone} aria-hidden="true" />
                      <span className="nx-calendar-panel-label">{event.label}</span>
                    </>
                  );
                  return (
                    <li style={vars({ '--nx-i': i })} key={i}>
                      {event.href ? (
                        <SmartLink href={event.href} className="nx-calendar-panel-item">
                          {row}
                        </SmartLink>
                      ) : (
                        <div className="nx-calendar-panel-item">{row}</div>
                      )}
                    </li>
                  );
                })}
              </ul>
            ) : (
              <p className="nx-calendar-panel-empty">{emptyText ?? word('emptyDay')}</p>
            )}
          </>
        )}
      </div>

      {/* The same data as text: what a screen reader reads instead of chips. */}
      <table className="nx-visually-hidden">
        <caption>{titleText}</caption>
        <tbody>
          {[...byDay.entries()]
            .filter(([iso]) => monthOf(iso) === viewing)
            .sort(([a], [b]) => (a < b ? -1 : 1))
            .flatMap(([iso, list]) =>
              list.map((event, i) => (
                <tr key={`${iso}-${i}`}>
                  <th scope="row">{formatters.long.format(new Date(`${iso}T00:00:00Z`))}</th>
                  <td>
                    {event.time ? `${event.time} — ` : ''}
                    {event.label}
                  </td>
                </tr>
              )),
            )}
        </tbody>
      </table>
    </section>
  );
}
