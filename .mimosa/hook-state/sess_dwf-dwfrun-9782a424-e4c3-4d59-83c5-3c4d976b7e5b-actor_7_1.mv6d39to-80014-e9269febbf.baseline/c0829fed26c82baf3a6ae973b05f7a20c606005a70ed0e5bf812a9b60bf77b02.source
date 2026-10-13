/**
 * Gantt — rows of draggable bars over a day grid (React).
 *
 * Thin over css/blocks/gantt.css. Bars are buttons: drag one with the pointer
 * to move it, drag its edge handles to resize, or use the arrow keys (Shift
 * steps a week, Alt resizes) — Escape cancels a drag in flight. Everything on
 * the chart — grid, bars, dependency elbows, today line — is day units ×
 * --nx-gantt-unit, so the day/week/month zoom is one attribute and the whole
 * chart reflows in a spring. The same data ships as a visually-hidden table.
 *
 * State is the rows you pass: controlled (`rows` + `onRowsChange`) or
 * uncontrolled (`defaultRows`), with an `onChange` detail callback; `zoom` is
 * controlled the same way. Dates are plain 'YYYY-MM-DD' days.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent as ReactKeyboardEvent,
  type PointerEvent as ReactPointerEvent,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { reveal } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useEvent } from '../internal/hooks';
import { useLocale, useT } from '../internal/provider';

/** The Intl locale: the prop, or the provider's language. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

const DAY = 86_400_000;

/** 'YYYY-MM-DD' → a UTC day number (epoch days); null when unparseable. */
function dayIndex(iso: string): number | null {
  const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso);
  return m ? Date.UTC(Number(m[1]), Number(m[2]) - 1, Number(m[3])) / DAY : null;
}

/** A UTC day number → 'YYYY-MM-DD'. */
function isoOf(day: number): string {
  return new Date(day * DAY).toISOString().slice(0, 10);
}

/** 0 = Sunday … 6 = Saturday, without touching the local timezone. */
const weekdayOf = (day: number) => (day + 4) % 7;

const clamp = (value: number, min: number, max: number) => Math.max(min, Math.min(value, max));

/** The zoom pills' words, keyed the way `t()` wants them. */
const ZOOM_LABEL = { day: 'ganttZoomDay', week: 'ganttZoomWeek', month: 'ganttZoomMonth' } as const;

/* ---- Types ---------------------------------------------------------------------------- */

export type GanttTone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';
export type GanttZoom = 'day' | 'week' | 'month';

export interface GanttTask {
  id: string;
  title: string;
  /** 'YYYY-MM-DD'; the end day is included. */
  start: string;
  end: string;
  tone?: GanttTone;
  /** 0–100; draws the inner fill. */
  progress?: number;
  /** The id of the task this one waits for (any row); draws the elbow + dot. */
  dependsOn?: string;
}

export interface GanttRow {
  id: string;
  title: string;
  tone?: GanttTone;
  tasks: GanttTask[];
}

/** What a drag or an arrow-key nudge changed. */
export interface GanttChange {
  task: string;
  start: string;
  end: string;
  kind: 'move' | 'resize';
}

export interface GanttProps extends Omit<HTMLAttributes<HTMLElement>, 'children' | 'onChange'> {
  rows?: GanttRow[];
  defaultRows?: GanttRow[];
  onRowsChange?: (rows: GanttRow[]) => void;
  /** Fired for every committed move or resize, whichever way it started. */
  onChange?: (change: GanttChange, rows: GanttRow[]) => void;
  zoom?: GanttZoom;
  defaultZoom?: GanttZoom;
  onZoomChange?: (zoom: GanttZoom) => void;
  /** The chart's accessible name. */
  label?: string;
  /** Overall chart height; the grid scrolls inside ("32rem"). */
  height?: string;
  locale?: string;
}

/* ---- The model: one pass over the rows ------------------------------------------------- */

interface PlacedTask {
  task: GanttTask;
  row: number;
  lane: number;
  s: number;
  n: number;
}

interface Segment {
  x: number;
  y: number;
  w: number;
}

interface VSegment {
  x: number;
  y: number;
  h: number;
}

interface LinkModel {
  id: string;
  tone?: GanttTone;
  h1: Segment | null;
  v: VSegment | null;
  h2: Segment | null;
  from: { x: number; y: number };
  to: { x: number; y: number };
}

interface GanttModel {
  rows: Array<{ id: string; title: string; tone?: GanttTone; lanes: number; tasks: PlacedTask[] }>;
  origin: number;
  totalDays: number;
  months: Array<{ s: number; n: number; label: string }>;
  weeks: Array<{ d: number; label: string }>;
  days: Array<{ d: number; label: string; weekend: boolean; weekStart: boolean; monthStart: boolean }>;
  links: LinkModel[];
  hasTasks: boolean;
}

interface Formatters {
  day: Intl.DateTimeFormat;
  short: Intl.DateTimeFormat;
  month: Intl.DateTimeFormat;
  long: Intl.DateTimeFormat;
  number: Intl.NumberFormat;
  percentSign: string;
  weekStarts: number;
  isWeekend: (weekday: number) => boolean;
}

/** Greedy lane packing: the first lane whose last bar ends before this one starts. */
function packLanes(tasks: PlacedTask[]): number {
  const ends: number[] = [];
  let lanes = 1;
  for (const bar of [...tasks].sort((a, b) => a.s - b.s || b.n - a.n)) {
    let lane = ends.findIndex((end) => end <= bar.s);
    if (lane === -1) {
      lane = ends.length;
      ends.push(0);
    }
    ends[lane] = bar.s + bar.n;
    bar.lane = lane;
    lanes = Math.max(lanes, lane + 1);
  }
  return lanes;
}

function buildModel(rows: GanttRow[], fmt: Formatters, today: number): GanttModel {
  const placed: PlacedTask[] = [];
  const modelRows: GanttModel['rows'] = [];
  rows.forEach((row, rowIndex) => {
    const tasks: PlacedTask[] = [];
    for (const task of row.tasks ?? []) {
      const start = dayIndex(String(task.start ?? ''));
      const end = dayIndex(String(task.end ?? ''));
      if (start === null || end === null) continue;
      const s0 = Math.min(start, end);
      const n = Math.abs(end - start) + 1;
      tasks.push({ task, row: rowIndex, lane: 0, s: s0, n });
    }
    const lanes = packLanes(tasks);
    modelRows.push({ id: row.id, title: row.title, tone: row.tone, lanes, tasks });
    placed.push(...tasks);
  });

  const hasTasks = placed.length > 0;
  const firstDay = today - 7;
  const lastDay = today + 21;
  const origin = hasTasks ? Math.min(...placed.map((bar) => bar.s)) - 3 : firstDay;
  const horizon = hasTasks ? Math.max(...placed.map((bar) => bar.s + bar.n)) + 3 : lastDay;
  const totalDays = horizon - origin;

  // Offsets from the origin, once — everything below speaks in these.
  for (const bar of placed) bar.s -= origin;

  const months: GanttModel['months'] = [];
  const weeks: GanttModel['weeks'] = [];
  const days: GanttModel['days'] = [];
  for (let d = 0; d < totalDays; d++) {
    const date = new Date((origin + d) * DAY);
    const weekday = weekdayOf(origin + d);
    days.push({
      d,
      label: fmt.day.format(date),
      weekend: fmt.isWeekend(weekday),
      weekStart: weekday === fmt.weekStarts,
      monthStart: date.getUTCDate() === 1,
    });
    if (weekday === fmt.weekStarts) weeks.push({ d, label: fmt.day.format(date) });
    if (date.getUTCDate() === 1 || d === 0) {
      const y = date.getUTCFullYear();
      const m = date.getUTCMonth();
      const until = Math.min((Date.UTC(m === 11 ? y + 1 : y, m === 11 ? 0 : m + 1, 1) / DAY) - origin, totalDays);
      months.push({ s: d, n: until - d, label: fmt.month.format(date) });
    }
  }

  // Dependency elbows: from the predecessor's end to the successor's start.
  const at = new Map(placed.map((bar) => [bar.task.id, bar]));
  const rowOffset: number[] = [];
  let acc = 0;
  for (const row of modelRows) {
    rowOffset.push(acc);
    acc += row.lanes;
  }
  const links: LinkModel[] = [];
  for (const bar of placed) {
    const after = bar.task.dependsOn ? at.get(bar.task.dependsOn) : undefined;
    if (!after || after === bar) continue;
    const x1 = after.s + after.n;
    const y1 = rowOffset[after.row]! + after.lane + 0.5;
    const x2 = bar.s;
    const y2 = rowOffset[bar.row]! + bar.lane + 0.5;
    const elbow = Math.max(x1, x2 - 0.6);
    links.push({
      id: `${after.task.id}-${bar.task.id}`,
      tone: bar.task.tone,
      h1: elbow - x1 > 0.01 ? { x: x1, y: y1, w: elbow - x1 } : null,
      v: Math.abs(y2 - y1) > 0.01 ? { x: elbow, y: Math.min(y1, y2), h: Math.abs(y2 - y1) } : null,
      h2: x2 - elbow > 0.01 ? { x: elbow, y: y2, w: x2 - elbow } : null,
      from: { x: x1, y: y1 },
      to: { x: x2, y: y2 },
    });
  }

  return { rows: modelRows, origin, totalDays, months, weeks, days, links, hasTasks };
}

/* ---- The drag -------------------------------------------------------------------------- */

interface DragState {
  id: string;
  mode: 'move' | 'start' | 'end';
  title: string;
  pointer: number;
  startX: number;
  origS: number;
  origN: number;
  el: HTMLElement;
  unit: number;
  rtl: boolean;
}

/** One day, in px, from the live --nx-gantt-unit the zoom sets. */
function unitOf(root: HTMLElement): number {
  const value = getComputedStyle(root).getPropertyValue('--nx-gantt-unit').trim();
  const n = parseFloat(value);
  return value.endsWith('rem') ? n * parseFloat(getComputedStyle(document.documentElement).fontSize) : n || 24;
}

const isRtl = (el: Element) => getComputedStyle(el).direction === 'rtl';

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/* ---- The component ---------------------------------------------------------------------- */

export function Gantt({
  rows: controlled,
  defaultRows,
  onRowsChange,
  onChange,
  zoom: controlledZoom,
  defaultZoom = 'day',
  onZoomChange,
  label,
  height,
  locale,
  className,
  style,
  ...rest
}: GanttProps) {
  const t = useT();
  const language = useLocale();
  const base = `nx-gantt${useId().replace(/:/g, '')}`;
  const [rows, setRows] = useControllable(controlled, defaultRows ?? [], onRowsChange);
  const [zoom, setZoom] = useControllable<GanttZoom>(controlledZoom, defaultZoom, onZoomChange);
  const [announced, setAnnounced] = useState('');
  const root = useRef<HTMLElement>(null);
  const frame = useRef<HTMLDivElement>(null);
  const drag = useRef<DragState | null>(null);
  useBehavior(root, reveal, { once: true });

  const fmt = useMemo<Formatters>(() => {
    const intl = locale ?? INTL[language];
    const lang = intl.slice(0, 2);
    // Persian dates stay Gregorian (like the PHP side): the month bands are
    // Gregorian months, so their labels and day numbers must be too.
    const dates = lang === 'fa' ? `${intl}-u-ca-gregory` : intl;
    return {
      day: new Intl.DateTimeFormat(dates, { day: 'numeric', timeZone: 'UTC' }),
      short: new Intl.DateTimeFormat(dates, { day: 'numeric', month: 'short', timeZone: 'UTC' }),
      month: new Intl.DateTimeFormat(dates, { month: 'long', year: 'numeric', timeZone: 'UTC' }),
      long: new Intl.DateTimeFormat(dates, { day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' }),
      number: new Intl.NumberFormat(intl),
      percentSign: lang === 'fa' || lang === 'ar' ? '٪' : '%',
      weekStarts: lang === 'fa' || lang === 'ar' ? 6 : 0,
      isWeekend: lang === 'fa' || lang === 'ar' ? (weekday) => weekday === 5 : (weekday) => weekday === 6 || weekday === 0,
    };
  }, [locale, language]);

  const today = useMemo(() => Math.floor(Date.now() / DAY), []);
  const model = useMemo(() => buildModel(rows, fmt, today), [rows, fmt, today]);
  const todayOffset = today >= model.origin && today < model.origin + model.totalDays ? today - model.origin : null;
  const hintId = `${base}-hint`;

  const barLabel = (bar: PlacedTask) => {
    const range = `${fmt.short.format(new Date((model.origin + bar.s) * DAY))} – ${fmt.short.format(new Date((model.origin + bar.s + bar.n - 1) * DAY))}`;
    const progress = bar.task.progress != null ? `, ${fmt.number.format(Math.round(bar.task.progress))}${fmt.percentSign}` : '';
    return `${bar.task.title}, ${range}${progress}`;
  };

  /** Where a drag or a nudge lands, clamped to the timeline; null when nothing moved. */
  const landed = (state: { origS: number; origN: number; mode: 'move' | 'start' | 'end' }, deltaDays: number, totalDays: number) => {
    const d = Math.round(deltaDays);
    if (state.mode === 'end') {
      const n = clamp(state.origN + d, 1, totalDays - state.origS);
      return n === state.origN ? null : { s: state.origS, n };
    }
    if (state.mode === 'start') {
      const s = clamp(state.origS + d, 0, state.origS + state.origN - 1);
      return s === state.origS ? null : { s, n: state.origS + state.origN - s };
    }
    const s = clamp(state.origS + d, 0, totalDays - state.origN);
    return s === state.origS ? null : { s, n: state.origN };
  };

  /** Commit a new span for a task: state, callback, announcement. */
  const commit = useEvent((state: { id: string; title: string; mode: 'move' | 'start' | 'end' }, next: { s: number; n: number }) => {
    const start = isoOf(model.origin + next.s);
    const end = isoOf(model.origin + next.s + next.n - 1);
    const changed = rows.map((row) => ({
      ...row,
      tasks: row.tasks.map((task) => (task.id === state.id ? { ...task, start, end } : task)),
    }));
    setRows(changed);
    const kind = state.mode === 'end' ? 'resize' : 'move';
    onChange?.({ task: state.id, start, end, kind }, changed);
    setAnnounced(
      kind === 'resize'
        ? t('ganttResized', { task: state.title, date: fmt.short.format(new Date((model.origin + next.s + next.n - 1) * DAY)) })
        : t('ganttMoved', { task: state.title, date: fmt.short.format(new Date((model.origin + next.s) * DAY)) }),
    );
  });

  /* ---- Pointer drag ------------------------------------------------------------------------ */

  const barDown = (event: ReactPointerEvent<HTMLButtonElement>, bar: PlacedTask) => {
    if (event.button !== 0 || !event.isPrimary || !root.current) return;
    const handle = (event.target as HTMLElement).closest<HTMLElement>('[data-handle]');
    const el = event.currentTarget;
    el.setPointerCapture(event.pointerId);
    drag.current = {
      id: bar.task.id,
      mode: handle?.dataset.handle === 'start' || handle?.dataset.handle === 'end' ? handle.dataset.handle : 'move',
      title: bar.task.title,
      pointer: event.pointerId,
      startX: event.clientX,
      origS: bar.s,
      origN: bar.n,
      el,
      unit: unitOf(root.current),
      rtl: isRtl(el),
    };
    el.dataset.dragging = '';
  };

  const barMove = (event: ReactPointerEvent<HTMLButtonElement>) => {
    const state = drag.current;
    if (!state || event.pointerId !== state.pointer) return;
    // One drag, one finger: extra touches after the first are ignored.
    if (event.pointerType === 'touch' && !event.isPrimary) return;
    const days = ((event.clientX - state.startX) / state.unit) * (state.rtl ? -1 : 1);
    const raw = state.mode === 'end' ? state.origS + state.origN + days : state.origS + days;
    const clamped =
      state.mode === 'end'
        ? clamp(raw, state.origS + 1, model.totalDays - state.origS)
        : state.mode === 'start'
          ? clamp(raw, 0, state.origS + state.origN - 1)
          : clamp(raw, 0, model.totalDays - state.origN);
    // Rubber-band past the edges: the overshoot follows at a third of its weight.
    state.el.style.setProperty('--over', String((raw - clamped) * 0.35));
    const snapped = Math.round(clamped);
    if (state.mode === 'end') state.el.style.setProperty('--dn', String(snapped - (state.origS + state.origN)));
    else state.el.style.setProperty('--ds', String(snapped - state.origS));
  };

  const barUp = (event: ReactPointerEvent<HTMLButtonElement>) => {
    const state = drag.current;
    if (!state || event.pointerId !== state.pointer) return;
    const days = ((event.clientX - state.startX) / state.unit) * (state.rtl ? -1 : 1);
    const next = landed(state, days, model.totalDays);
    barReset(state.el);
    if (next) commit(state, next);
  };

  const barCancel = (event: ReactPointerEvent<HTMLButtonElement>) => {
    const state = drag.current;
    if (!state || event.pointerId !== state.pointer) return;
    barReset(state.el);
  };

  const barReset = (el: HTMLElement) => {
    drag.current = null;
    delete el.dataset.dragging;
    el.style.removeProperty('--ds');
    el.style.removeProperty('--dn');
    el.style.removeProperty('--over');
  };

  /* ---- Keyboard ------------------------------------------------------------------------------ */

  const barKey = (event: ReactKeyboardEvent<HTMLButtonElement>, bar: PlacedTask) => {
    if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight' && event.key !== 'Escape') return;
    // Escape lets a pointer drag in flight go — the bar springs home untouched.
    if (event.key === 'Escape') {
      if (drag.current?.el === event.currentTarget) barReset(event.currentTarget);
      return;
    }
    if (drag.current) return;
    const rtl = isRtl(event.currentTarget);
    const step = (event.key === 'ArrowRight' ? 1 : -1) * (rtl ? -1 : 1) * (event.shiftKey ? 7 : 1);
    const mode = event.altKey ? 'end' : 'move';
    const next = landed({ origS: bar.s, origN: bar.n, mode }, step, model.totalDays);
    if (next) commit({ id: bar.task.id, title: bar.task.title, mode }, next);
    event.preventDefault();
  };

  /* ---- The today jump -------------------------------------------------------------------------- */

  const goToday = useEvent(() => {
    const el = frame.current;
    const rootEl = root.current;
    if (!el || !rootEl || todayOffset === null) return;
    const max = el.scrollWidth - el.clientWidth;
    const pos = clamp(todayOffset * unitOf(rootEl) - el.clientWidth / 3, 0, max);
    const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    el.scrollTo({ left: isRtl(el) ? -pos : pos, behavior: calm ? 'auto' : 'smooth' });
  });

  // The bars' rise-in stagger needs a global index; keep it across rows.
  let seen = 0;

  return (
    <section
      ref={root}
      className={cx('nx-gantt', className)}
      data-zoom={zoom}
      aria-label={label ?? t('ganttChart')}
      style={{ ...(height ? vars({ '--nx-gantt-height': height }) : null), ...style }}
      {...rest}
    >
      <div className="nx-gantt-toolbar">
        {todayOffset !== null && (
          <button type="button" className="nx-gantt-today-button" onClick={goToday}>
            {t('ganttToday')}
          </button>
        )}
        <fieldset className="nx-gantt-zoom">
          <legend className="nx-visually-hidden">{t('ganttZoom')}</legend>
          {(['day', 'week', 'month'] as const).map((level) => (
            <label key={level} className="nx-gantt-zoom-choice">
              <input
                type="radio"
                className="nx-gantt-zoom-input"
                name={`${base}-zoom`}
                value={level}
                checked={zoom === level}
                onChange={() => setZoom(level)}
              />
              <span className="nx-gantt-zoom-label">{t(ZOOM_LABEL[level])}</span>
            </label>
          ))}
        </fieldset>
      </div>
      <div className="nx-gantt-frame" ref={frame}>
        {model.hasTasks ? (
          <div className="nx-gantt-inner" style={vars({ '--nx-gantt-days': model.totalDays })}>
            <div className="nx-gantt-corner" aria-hidden="true" />
            <div className="nx-gantt-head">
              <div className="nx-gantt-months">
                {model.months.map((month, i) => (
                  <span key={i} className="nx-gantt-month" style={vars({ '--s': month.s, '--n': month.n })}>
                    {month.label}
                  </span>
                ))}
                {todayOffset !== null && (
                  <span className="nx-gantt-today-flag" style={vars({ '--d': todayOffset })}>
                    {t('ganttToday')}
                  </span>
                )}
              </div>
              <div className="nx-gantt-days">
                {model.days.map((day) => (
                  <span key={day.d} className="nx-gantt-day" data-weekend={day.weekend ? '' : undefined} style={vars({ '--d': day.d })}>
                    {day.label}
                  </span>
                ))}
              </div>
              <div className="nx-gantt-weeks">
                {model.weeks.map((week) => (
                  <span key={week.d} className="nx-gantt-week" style={vars({ '--d': week.d })}>
                    {week.label}
                  </span>
                ))}
              </div>
            </div>
            <div className="nx-gantt-side">
              {model.rows.map((row) => (
                <div key={row.id} className="nx-gantt-label" style={vars({ '--lanes': row.lanes })}>
                  {row.tone && <span className="nx-gantt-label-dot" data-tone={row.tone} aria-hidden="true" />}
                  <span>{row.title}</span>
                </div>
              ))}
            </div>
            <div className="nx-gantt-lanes">
              <div className="nx-gantt-grid" aria-hidden="true">
                {model.days.map((day) => (
                  <span
                    key={day.d}
                    className="nx-gantt-tick"
                    data-week={day.weekStart ? '' : undefined}
                    data-month={day.monthStart ? '' : undefined}
                    style={vars({ '--d': day.d })}
                  />
                ))}
                {model.days.map((day) =>
                  day.weekend ? (
                    <span key={`we-${day.d}`} className="nx-gantt-weekend" style={vars({ '--d': day.d })} />
                  ) : null,
                )}
              </div>
              {model.links.map((link) => (
                <div key={link.id} className="nx-gantt-link" data-tone={link.tone} aria-hidden="true">
                  {link.h1 && <span className="nx-gantt-link-h" style={vars({ '--x': link.h1.x, '--y': link.h1.y, '--w': link.h1.w })} />}
                  {link.v && <span className="nx-gantt-link-v" style={vars({ '--x': link.v.x, '--y': link.v.y, '--h': link.v.h })} />}
                  {link.h2 && <span className="nx-gantt-link-h" style={vars({ '--x': link.h2.x, '--y': link.h2.y, '--w': link.h2.w })} />}
                  <span className="nx-gantt-link-dot" style={vars({ '--x': link.from.x, '--y': link.from.y })} />
                  <span className="nx-gantt-link-dot" style={vars({ '--x': link.to.x, '--y': link.to.y })} />
                </div>
              ))}
              {todayOffset !== null && (
                <div className="nx-gantt-today" style={vars({ '--d': todayOffset })} aria-hidden="true">
                  <span className="nx-gantt-today-dot" />
                </div>
              )}
              {model.rows.map((row) => (
                <div key={row.id} className="nx-gantt-track" style={vars({ '--lanes': row.lanes })}>
                  {row.tasks.map((bar) => {
                    const index = seen++;
                    return (
                      <button
                        key={bar.task.id}
                        type="button"
                        className="nx-gantt-bar"
                        data-tone={bar.task.tone}
                        draggable={false}
                        aria-label={barLabel(bar)}
                        aria-describedby={hintId}
                        style={vars({
                          '--s': bar.s,
                          '--n': bar.n,
                          '--l': bar.lane,
                          ...(bar.task.progress != null ? { '--p': Math.round(bar.task.progress) } : null),
                          '--nx-i': index,
                        })}
                        onPointerDown={(event) => barDown(event, bar)}
                        onPointerMove={barMove}
                        onPointerUp={barUp}
                        onPointerCancel={barCancel}
                        onKeyDown={(event) => barKey(event, bar)}
                      >
                        <span className="nx-gantt-bar-fill" aria-hidden="true" />
                        <span className="nx-gantt-bar-title">{bar.task.title}</span>
                        <span className="nx-gantt-bar-handle" data-handle="start" aria-hidden="true" />
                        <span className="nx-gantt-bar-handle" data-handle="end" aria-hidden="true" />
                      </button>
                    );
                  })}
                </div>
              ))}
            </div>
          </div>
        ) : (
          <p className="nx-gantt-empty">{t('ganttEmpty')}</p>
        )}
      </div>
      {/* What the bars say aloud, once they land. */}
      <p className="nx-visually-hidden" id={hintId}>
        {t('ganttHint')}
      </p>
      <p className="nx-visually-hidden" role="status">
        {announced}
      </p>
      {model.hasTasks && (
        <table className="nx-visually-hidden">
          <caption>{label ?? t('ganttChart')}</caption>
          <thead>
            <tr>
              <th scope="col">{t('ganttTask')}</th>
              <th scope="col">{t('ganttStart')}</th>
              <th scope="col">{t('ganttEnd')}</th>
              <th scope="col">{t('ganttDuration')}</th>
              <th scope="col">{t('ganttProgress')}</th>
              <th scope="col">{t('ganttDepends')}</th>
            </tr>
          </thead>
          <tbody>
            {model.rows.map((row) =>
              row.tasks.map((bar) => (
                <tr key={bar.task.id}>
                  <th scope="row">{`${row.title} — ${bar.task.title}`}</th>
                  <td>{fmt.long.format(new Date((model.origin + bar.s) * DAY))}</td>
                  <td>{fmt.long.format(new Date((model.origin + bar.s + bar.n - 1) * DAY))}</td>
                  <td>{t('ganttDays', { count: fmt.number.format(bar.n) })}</td>
                  <td>
                    {bar.task.progress != null
                      ? `${fmt.number.format(Math.round(bar.task.progress))}${fmt.percentSign}`
                      : '—'}
                  </td>
                  <td>
                    {bar.task.dependsOn
                      ? t('ganttDependsOn', { name: model.rows.flatMap((r) => r.tasks).find((b) => b.task.id === bar.task.dependsOn)?.task.title ?? '' })
                      : ''}
                  </td>
                </tr>
              )),
            )}
          </tbody>
        </table>
      )}
    </section>
  );
}
