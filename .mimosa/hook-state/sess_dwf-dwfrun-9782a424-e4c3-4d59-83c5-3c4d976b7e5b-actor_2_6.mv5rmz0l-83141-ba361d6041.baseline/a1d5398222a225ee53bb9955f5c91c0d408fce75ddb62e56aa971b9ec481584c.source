/**
 * Dashboards & data (React): multi-select, heatmap, data table, status badge,
 * metric chart, currency converter, workspace shell, support agent card,
 * analytics card, dot matrix chart, branch connector, curved timeline, usage
 * card and comparison table. Thin over the core CSS and helpers, so the Blade
 * components render the same markup.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent,
  type PointerEvent,
  type ReactNode,
  Fragment,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import {
  type CurrencyRates,
  type DataWord,
  type HeatmapDatum,
  type IconName,
  type RowSnapshot,
  type SortState,
  connect,
  convertCurrency,
  crossSwap,
  curvedTimeline,
  dataWord,
  dotStack,
  dotUnit,
  exchangeRate,
  fitStatusBadge,
  formatCell,
  heatLevel,
  heatmapWeeks,
  indicator,
  metricGeometry,
  morphPath,
  nearestIndex,
  nextSort,
  parseAmount,
  place,
  placeChartTip,
  playRowFlip,
  reveal,
  snapshotRows,
  sortRows,
  statusRank,
} from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useIsoLayoutEffect, useWidth } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { Button } from '../components/button';
import { Sparkline } from '../components/chart';
import { Avatar } from '../components/display';
import { PromptInput } from '../components/form';
import { SegmentedControl } from '../components/navigation';
import { NumberTicker } from '../components/text';

/* ---- Shared ------------------------------------------------------------------------ */

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

/** Series colours in fixed order; up to seven series, never cycled. */
const seriesColor = (i: number) => `var(--nx-chart-${Math.min(Math.max(i, 0), 6) + 1})`;

/** The Intl locale: the prop, or the provider's language. */
function useIntl(locale?: string): string {
  const language = useLocale();
  return locale ?? INTL[language];
}

/** The blocks' own words in the provider's language. */
function useWord() {
  const language = useLocale();
  return useMemo(() => (key: DataWord, params?: Record<string, string | number>) => dataWord(language, key, params), [language]);
}

const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** Fold case, accents and the Persian/Arabic letter variants so a search matches either spelling. */
const fold = (text: string) =>
  text
    .toLocaleLowerCase()
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[يى]/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[\u064b-\u0670\u065f\u200c]/g, '');

/** Keep the moving highlight (core `indicator`) inside `container`. */
function useIndicatorIn(container: React.RefObject<HTMLElement | null>) {
  const ctrl = useRef<ReturnType<typeof indicator> | null>(null);
  useIsoLayoutEffect(() => {
    if (!container.current) return;
    ctrl.current = indicator(container.current);
    return () => {
      ctrl.current?.destroy();
      ctrl.current = null;
    };
  }, [container]);
  return ctrl;
}

interface TipRow {
  name: string;
  value: string;
  color?: string;
}

/** The chart tooltip: values lead, names follow, line keys carry identity. */
function ChartTip({ tip, open, title, rows }: { tip: React.RefObject<HTMLDivElement | null>; open: boolean; title: string; rows: TipRow[] }) {
  return (
    <div ref={tip} className="nx-chart-tooltip" data-open={open ? '' : undefined} aria-hidden="true">
      <p className="nx-chart-tooltip-title">{title}</p>
      {rows.map((row) => (
        <div key={row.name} className="nx-chart-tooltip-row">
          {row.color && <span className="nx-chart-tooltip-key" style={vars({ '--nx-series': row.color })} />}
          <strong>{row.value}</strong>
          <span>{row.name}</span>
        </div>
      ))}
    </div>
  );
}

/** Arrow keys along one axis of a plot: the next index, null to leave, undefined when not handled. */
function stepIndex(event: KeyboardEvent, active: number | null, count: number, dir: 1 | -1 = 1): number | null | undefined {
  if (!count) return undefined;
  const at = active ?? -1;
  switch (event.key) {
    case 'ArrowRight':
      return Math.min(count - 1, Math.max(0, at + dir));
    case 'ArrowLeft':
      return Math.min(count - 1, Math.max(0, at === -1 ? 0 : at - dir));
    case 'Home':
      return 0;
    case 'End':
      return count - 1;
    case 'Escape':
      return null;
    default:
      return undefined;
  }
}

function Delta({ delta, invert, locale }: { delta: number; invert?: boolean; locale: string }) {
  const good = invert ? delta <= 0 : delta >= 0;
  return (
    <span className="nx-delta" data-trend={good ? 'up' : 'down'}>
      <Icon name={delta >= 0 ? 'trend-up' : 'trend-down'} />
      {new Intl.NumberFormat(locale, { style: 'percent', maximumFractionDigits: 1, signDisplay: 'exceptZero' }).format(delta / 100)}
    </span>
  );
}

/** "{used} of {limit} used" with React nodes in the slots, in whatever order the language puts them. */
function fillTemplate(template: string, slots: Record<string, ReactNode>): ReactNode[] {
  return template.split(/(\{\w+\})/g).map((part, i) => {
    const name = /^\{(\w+)\}$/.exec(part)?.[1];
    return <Fragment key={i}>{name && name in slots ? slots[name] : part}</Fragment>;
  });
}

interface Action {
  label: ReactNode;
  href?: string;
  onClick?: () => void;
  icon?: IconName;
}

/* ==========================================================================================
 * Multi-select
 * ======================================================================================== */

export interface MultiSelectOption {
  value: string;
  label: string;
  /** A built-in icon name or any node (a network logo, a flag). */
  icon?: IconName | ReactNode;
  description?: string;
  disabled?: boolean;
}

export interface MultiSelectProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  options: MultiSelectOption[];
  value?: string[];
  defaultValue?: string[];
  onValueChange?: (value: string[]) => void;
  placeholder?: string;
  searchPlaceholder?: string;
  emptyText?: ReactNode;
  /** The most values that can be picked. */
  max?: number;
  /** Posts every value as `name[]` in a plain form. */
  name?: string;
  disabled?: boolean;
  /** Accessible name of the control (the placeholder by default). */
  label?: string;
}

type ChipState = 'idle' | 'enter' | 'exit';

/** Keys in and out of a list with a moment to animate: new ones enter, removed ones exit, then go. */
function usePresence(keys: string[]) {
  const mounted = useRef(false);
  const [items, setItems] = useState<Array<{ key: string; state: ChipState }>>(() => keys.map((key) => ({ key, state: 'idle' })));
  const signature = keys.join('\u0000');

  useIsoLayoutEffect(() => {
    setItems((prev) => {
      const wanted = new Set(keys);
      const known = new Set(prev.map((item) => item.key));
      const next = prev.map((item) =>
        wanted.has(item.key) ? (item.state === 'exit' ? { key: item.key, state: 'enter' as const } : item) : item.state === 'exit' ? item : { key: item.key, state: 'exit' as const },
      );
      for (const key of keys) if (!known.has(key)) next.push({ key, state: mounted.current ? 'enter' : 'idle' });
      const same = next.length === prev.length && next.every((item, i) => item.key === prev[i]!.key && item.state === prev[i]!.state);
      return same ? prev : next;
    });
    mounted.current = true;
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [signature]);

  const settle = (key: string) => setItems((prev) => prev.filter((item) => !(item.key === key && item.state === 'exit')));

  // A leaving chip goes when its collapse ends, or shortly after if no transition runs.
  const exiting = items.filter((item) => item.state === 'exit').map((item) => item.key).join('\u0000');
  useEffect(() => {
    if (!exiting) return;
    const timer = setTimeout(() => setItems((prev) => prev.filter((item) => item.state !== 'exit')), 450);
    return () => clearTimeout(timer);
  }, [exiting]);

  return [items, settle] as const;
}

export function MultiSelect({
  options,
  value,
  defaultValue,
  onValueChange,
  placeholder,
  searchPlaceholder,
  emptyText,
  max,
  name,
  disabled,
  label,
  className,
  ...rest
}: MultiSelectProps) {
  const t = useT();
  const word = useWord();
  const base = `nx-ms${useId().replace(/:/g, '')}`;
  const [selected, setSelected] = useControllable(value, defaultValue ?? [], onValueChange);
  const [open, setOpen] = useState(false);
  const [query, setQuery] = useState('');
  const [active, setActive] = useState(-1);
  const [chips, settle] = usePresence(selected);
  const field = useRef<HTMLDivElement>(null);
  const trigger = useRef<HTMLButtonElement>(null);
  const popover = useRef<HTMLDivElement>(null);
  const input = useRef<HTMLInputElement>(null);
  const list = useRef<HTMLUListElement>(null);

  const byValue = useMemo(() => new Map(options.map((option) => [option.value, option])), [options]);
  const full = max !== undefined && selected.length >= max;
  const filtered = useMemo(() => {
    const q = fold(query.trim());
    return options.filter((option) => !q || fold(`${option.label} ${option.description ?? ''} ${option.value}`).includes(q));
  }, [options, query]);
  const blocked = (option: MultiSelectOption) => !!option.disabled || (full && !selected.includes(option.value));

  // Declarative invoker: the browser toggles the list and keeps the button out of light dismiss.
  useEffect(() => {
    if (supportsPopover()) trigger.current?.setAttribute('popovertarget', `${base}-list`);
  }, [base]);

  useEffect(() => {
    const el = popover.current;
    if (!el) return;
    const onToggle = (event: Event) => setOpen((event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  }, []);

  useEffect(() => {
    if (!open || !field.current || !popover.current) return;
    const stop = place(field.current, popover.current, { side: 'bottom', align: 'start', offset: 6, matchWidth: true });
    requestAnimationFrame(() => input.current?.focus());
    return () => {
      stop();
      setQuery('');
      setActive(-1);
      // Closing from inside the list brings focus back to the field.
      if (popover.current?.contains(document.activeElement) || document.activeElement === document.body) trigger.current?.focus();
    };
  }, [open]);

  // The first choosable option is active as the list opens or the query changes.
  useEffect(() => {
    if (open) setActive(filtered.findIndex((option) => !blocked(option)));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [open, query]);

  useEffect(() => {
    if (active < 0 || !list.current) return;
    list.current.querySelector<HTMLElement>(`#${base}-opt-${active}`)?.scrollIntoView({ block: 'nearest' });
  }, [active, base]);

  const show = () => {
    const el = popover.current;
    if (!el || disabled) return;
    if (supportsPopover()) {
      try {
        el.showPopover();
      } catch {
        /* already open */
      }
    } else {
      el.setAttribute('data-open', '');
      setOpen(true);
    }
  };

  const hide = () => {
    const el = popover.current;
    if (!el) return;
    el.removeAttribute('data-open');
    try {
      if (el.matches(':popover-open')) el.hidePopover();
    } catch {
      /* no popover support */
    }
    if (!supportsPopover()) setOpen(false);
  };

  const toggle = (option: MultiSelectOption | undefined) => {
    if (!option || blocked(option)) return;
    setSelected(selected.includes(option.value) ? selected.filter((v) => v !== option.value) : [...selected, option.value]);
  };

  const remove = (key: string, from?: HTMLElement | null) => {
    // Keep focus on a neighbouring chip (or the field) as this one leaves.
    if (from) {
      const buttons = Array.from(field.current?.querySelectorAll<HTMLButtonElement>('.nx-multiselect-chip:not([data-state="exit"]) .nx-multiselect-chip-remove') ?? []);
      const at = buttons.indexOf(from as HTMLButtonElement);
      (buttons[at + 1] ?? buttons[at - 1] ?? trigger.current)?.focus();
    }
    setSelected(selected.filter((v) => v !== key));
  };

  const move = (step: 1 | -1) => {
    const count = filtered.length;
    if (!count) return;
    // Nothing active yet: down starts at the top, up at the bottom.
    let next = active < 0 ? (step > 0 ? -1 : 0) : active;
    for (let i = 0; i < count; i++) {
      next = (next + step + count) % count;
      if (!blocked(filtered[next]!)) break;
    }
    setActive(next);
  };

  const onInputKey = (event: KeyboardEvent<HTMLInputElement>) => {
    if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
      event.preventDefault();
      move(event.key === 'ArrowDown' ? 1 : -1);
    } else if (event.key === 'Enter' || (event.key === ' ' && !query)) {
      event.preventDefault();
      toggle(filtered[active]);
    } else if (event.key === 'Backspace' && !query && selected.length) {
      remove(selected[selected.length - 1]!);
    } else if (event.key === 'Tab') {
      hide();
    } else if (event.key === 'Escape') {
      event.preventDefault();
      hide();
    }
  };

  const onTriggerKey = (event: KeyboardEvent<HTMLButtonElement>) => {
    if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
      event.preventDefault();
      show();
    } else if (event.key === 'Backspace' && selected.length && !disabled) {
      remove(selected[selected.length - 1]!);
    }
  };

  const accessibleName = label ?? placeholder;

  return (
    <div className={cx('nx-multiselect', className)} data-open={open ? '' : undefined} data-disabled={disabled ? '' : undefined} {...rest}>
      <div
        ref={field}
        className="nx-multiselect-field"
        onClick={(event) => {
          // A click on the field's empty space opens the list like the button does.
          if (!open && (event.target === event.currentTarget || (event.target as Element).matches('.nx-multiselect-chips'))) trigger.current?.click();
        }}
      >
        <ul className="nx-multiselect-chips" aria-label={word('selected')}>
          {chips.map(({ key, state }) => {
            const option = byValue.get(key);
            const text = option?.label ?? key;
            return (
              <li
                key={key}
                className="nx-multiselect-chip"
                data-state={state}
                onTransitionEnd={(event) => {
                  if (state === 'exit' && event.propertyName === 'grid-template-columns' && event.target === event.currentTarget) settle(key);
                }}
              >
                <span className="nx-multiselect-chip-body">
                  <span className="nx-multiselect-chip-pill">
                    {option?.icon ? (typeof option.icon === 'string' ? <Icon name={option.icon as IconName} /> : option.icon) : null}
                    <span>{text}</span>
                    {!disabled && (
                      <button
                        type="button"
                        className="nx-multiselect-chip-remove"
                        aria-label={t('remove', { name: text })}
                        tabIndex={state === 'exit' ? -1 : undefined}
                        onClick={(event) => {
                          event.stopPropagation();
                          remove(key, event.currentTarget);
                        }}
                      >
                        <Icon name="x" />
                      </button>
                    )}
                  </span>
                </span>
              </li>
            );
          })}
        </ul>
        <button
          ref={trigger}
          type="button"
          className="nx-multiselect-trigger"
          aria-haspopup="listbox"
          aria-expanded={open}
          aria-controls={`${base}-list`}
          aria-label={accessibleName ? `${accessibleName}${selected.length ? ` (${selected.length})` : ''}` : undefined}
          disabled={disabled}
          onKeyDown={onTriggerKey}
          onClick={() => {
            if (!supportsPopover()) (open ? hide() : show());
          }}
        >
          <span className="nx-multiselect-placeholder">{selected.length ? '' : placeholder}</span>
          <Icon name="chevron-down" className="nx-multiselect-chevron" />
        </button>
      </div>

      <div ref={popover} id={`${base}-list`} className="nx-multiselect-popover" {...{ popover: 'auto' }}>
        <div className="nx-multiselect-search">
          <Icon name="search" />
          <input
            ref={input}
            className="nx-multiselect-input"
            role="combobox"
            aria-expanded={open}
            aria-controls={`${base}-listbox`}
            aria-autocomplete="list"
            aria-activedescendant={active >= 0 && filtered[active] ? `${base}-opt-${active}` : undefined}
            aria-label={searchPlaceholder ?? t('search')}
            placeholder={searchPlaceholder ?? t('search')}
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            onKeyDown={onInputKey}
          />
        </div>
        <ul ref={list} id={`${base}-listbox`} className="nx-multiselect-list" role="listbox" aria-multiselectable="true" aria-label={accessibleName}>
          {filtered.map((option, i) => {
            const isSelected = selected.includes(option.value);
            return (
              <li
                key={option.value}
                id={`${base}-opt-${i}`}
                className="nx-multiselect-option"
                role="option"
                aria-selected={isSelected}
                aria-disabled={blocked(option) || undefined}
                data-active={i === active ? '' : undefined}
                style={vars({ '--nx-i': i })}
                onPointerMove={() => i !== active && !blocked(option) && setActive(i)}
                onMouseDown={(event) => event.preventDefault()}
                onClick={() => toggle(option)}
              >
                <span className="nx-multiselect-check" aria-hidden="true">
                  <svg viewBox="0 0 24 24">
                    <path d="M4.5 12.5l5 5L19.5 6.5" pathLength={1} />
                  </svg>
                </span>
                {option.icon && <span className="nx-multiselect-option-icon">{typeof option.icon === 'string' ? <Icon name={option.icon as IconName} /> : option.icon}</span>}
                <span className="nx-multiselect-option-text">
                  <span className="nx-multiselect-option-label">{option.label}</span>
                  {option.description && <span className="nx-multiselect-option-description">{option.description}</span>}
                </span>
              </li>
            );
          })}
        </ul>
        {filtered.length === 0 && <p className="nx-multiselect-empty">{emptyText ?? t('noResults')}</p>}
      </div>

      {name && selected.map((v) => <input key={v} type="hidden" name={`${name}[]`} value={v} />)}
    </div>
  );
}

/* ==========================================================================================
 * Heatmap
 * ======================================================================================== */

export interface HeatmapProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** Days ([{ date: 'YYYY-MM-DD', value }]) for a contribution calendar, or rows of numbers for any matrix. */
  data: HeatmapDatum[] | number[][];
  /** Row and column names for a matrix. */
  labels?: { rows?: string[]; columns?: string[] };
  title?: ReactNode;
  subtitle?: ReactNode;
  /** Calendar: how many weeks to show, ending with the last day (or `end`). */
  weeks?: number;
  /** Calendar: the first day of the week (0 Sunday, 1 Monday, 6 Saturday). */
  weekStart?: 0 | 1 | 6;
  end?: string;
  /** Word after the value in the tooltip ("contributions"). */
  unit?: string;
  format?: Intl.NumberFormatOptions;
  locale?: string;
  /** A line under the grid, beside the legend ("1,284 contributions in 2026"). */
  summary?: ReactNode;
}

interface HeatCell {
  col: number;
  row: number;
  value: number;
  level: number;
  title: string;
}

const REFERENCE_SUNDAY = Date.UTC(2026, 0, 4);

export function Heatmap({ data, labels, title, subtitle, weeks, weekStart = 0, end, unit, format, locale, summary, className, ...rest }: HeatmapProps) {
  const t = useT();
  const word = useWord();
  const intl = useIntl(locale);
  const id = useId();
  const figure = useRef<HTMLElement>(null);
  const frame = useRef<HTMLDivElement>(null);
  const tip = useRef<HTMLDivElement>(null);
  const [active, setActive] = useState<{ col: number; row: number } | null>(null);
  useBehavior(figure, reveal, { once: true });

  const matrix = Array.isArray(data[0]);
  const model = useMemo(() => {
    const value = new Intl.NumberFormat(intl, format);
    if (matrix) {
      const rows = data as number[][];
      const cols = Math.max(0, ...rows.map((r) => r.length));
      const max = Math.max(0, ...rows.flat());
      const rowNames = labels?.rows ?? rows.map((_, i) => String(i + 1));
      const colNames = labels?.columns ?? Array.from({ length: cols }, (_, i) => String(i + 1));
      const cells: HeatCell[] = rows.flatMap((r, row) => r.map((v, col) => ({ col, row, value: v, level: heatLevel(v, max), title: `${rowNames[row] ?? ''} · ${colNames[col] ?? ''}` })));
      return {
        cols,
        rows: rows.length,
        cells,
        columnLabels: colNames.map((text, col) => ({ col, text })),
        rowLabels: rowNames.map((text, row) => ({ row, text })),
        table: { head: colNames, body: rows.map((r, i) => ({ label: rowNames[i] ?? '', cells: Array.from({ length: cols }, (_, c) => (r[c] === undefined ? '' : value.format(r[c]!))) })) },
        format: value,
      };
    }
    // Month labels follow the reader's calendar (a Persian reader's months start mid-way through Gregorian ones).
    const dayOfMonth = new Intl.DateTimeFormat(intl, { day: 'numeric', timeZone: 'UTC', numberingSystem: 'latn' });
    const layout = heatmapWeeks(data as HeatmapDatum[], { weekStart, weeks, end, monthStart: (date) => dayOfMonth.format(new Date(`${date}T00:00:00Z`)) === '1' });
    const day = new Intl.DateTimeFormat(intl, { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric', timeZone: 'UTC' });
    const month = new Intl.DateTimeFormat(intl, { month: 'short', timeZone: 'UTC' });
    const weekday = new Intl.DateTimeFormat(intl, { weekday: 'short', timeZone: 'UTC' });
    const short = new Intl.DateTimeFormat(intl, { month: 'short', day: 'numeric', timeZone: 'UTC' });
    const dayNames = Array.from({ length: 7 }, (_, row) => weekday.format(REFERENCE_SUNDAY + ((weekStart + row) % 7) * 86_400_000));
    const cells: HeatCell[] = layout.weeks.flatMap((week) =>
      week.flatMap((cell) => (cell ? [{ col: cell.col, row: cell.row, value: cell.value, level: cell.level, title: day.format(new Date(`${cell.date}T00:00:00Z`)) }] : [])),
    );
    return {
      cols: layout.weeks.length,
      rows: 7,
      cells,
      columnLabels: layout.months.map((m) => ({ col: m.col, text: month.format(new Date(`${m.date}T00:00:00Z`)) })),
      rowLabels: [1, 3, 5].map((row) => ({ row, text: dayNames[row]! })),
      table: {
        head: dayNames,
        body: layout.weeks.map((week) => {
          const first = week.find(Boolean);
          return { label: first ? word('week', { date: short.format(new Date(`${first.date}T00:00:00Z`)) }) : '', cells: week.map((cell) => (cell ? value.format(cell.value) : '')) };
        }),
      },
      format: value,
    };
  }, [data, matrix, labels, weekStart, weeks, end, intl, format, word]);

  const at = useMemo(() => new Map(model.cells.map((cell) => [`${cell.col}:${cell.row}`, cell])), [model.cells]);
  const current = active ? at.get(`${active.col}:${active.row}`) : undefined;

  useIsoLayoutEffect(() => {
    if (!active || !frame.current || !tip.current) return;
    const cell = frame.current.querySelector(`[data-col="${active.col}"][data-row="${active.row}"]`);
    if (cell) placeChartTip(frame.current, cell, tip.current);
  }, [active]);

  const onKey = (event: KeyboardEvent<HTMLDivElement>) => {
    const rtl = getComputedStyle(event.currentTarget).direction === 'rtl';
    const start = active ?? { col: Math.max(0, model.cols - 1), row: 0 };
    const moves: Record<string, [number, number]> = { ArrowRight: [rtl ? -1 : 1, 0], ArrowLeft: [rtl ? 1 : -1, 0], ArrowDown: [0, 1], ArrowUp: [0, -1] };
    if (event.key === 'Escape') return setActive(null);
    if (event.key === 'Home' || event.key === 'End') {
      event.preventDefault();
      const edge = event.key === 'Home' ? 0 : model.cols - 1;
      const cell = model.cells.find((c) => c.col === edge && c.row === start.row) ?? model.cells.find((c) => c.col === edge);
      if (cell) setActive({ col: cell.col, row: cell.row });
      return;
    }
    const move = moves[event.key];
    if (!move) return;
    event.preventDefault();
    if (!active) return setActive({ col: start.col, row: start.row });
    // Step over days outside the range.
    for (let col = start.col + move[0], row = start.row + move[1]; col >= 0 && col < model.cols && row >= 0 && row < model.rows; col += move[0], row += move[1]) {
      if (at.has(`${col}:${row}`)) return setActive({ col, row });
    }
  };

  const pick = (event: PointerEvent<HTMLDivElement>) => {
    const cell = (event.target as HTMLElement).closest<HTMLElement>('[data-col]');
    if (cell) setActive({ col: Number(cell.dataset.col), row: Number(cell.dataset.row) });
  };

  const caption = typeof title === 'string' ? title : undefined;

  return (
    <figure ref={figure} className={cx('nx-chart', 'nx-heatmap', className)} data-nx-reveal="" aria-labelledby={title ? `${id}-title` : undefined} {...rest}>
      {(title || subtitle) && (
        <div className="nx-chart-head">
          <div>
            {title && (
              <p className="nx-chart-title" id={`${id}-title`}>
                {title}
              </p>
            )}
            {subtitle && <p className="nx-chart-subtitle">{subtitle}</p>}
          </div>
        </div>
      )}
      <div ref={frame} className="nx-heatmap-frame">
        <div className="nx-heatmap-scroll">
          <div
            className="nx-heatmap-plot"
            tabIndex={0}
            aria-label={`${caption ? `${caption}. ` : ''}${t('chartHint')}`}
            style={vars({ '--nx-cols': model.cols, '--nx-rows': model.rows, '--_label': matrix ? '4.5rem' : undefined })}
            onKeyDown={onKey}
            onBlur={() => setActive(null)}
          >
            <div className="nx-heatmap-columns" aria-hidden="true">
              {model.columnLabels.map((label) => (
                <span key={`${label.col}-${label.text}`} className="nx-heatmap-column" style={{ gridColumn: `${label.col + 1} / span ${matrix ? 1 : 3}` }}>
                  {label.text}
                </span>
              ))}
            </div>
            <div className="nx-heatmap-rows" aria-hidden="true">
              {model.rowLabels.map((label) => (
                <span key={label.row} className="nx-heatmap-row" style={{ gridRow: label.row + 1 }}>
                  {label.text}
                </span>
              ))}
            </div>
            <div className="nx-heatmap-grid" aria-hidden="true" onPointerOver={pick} onPointerLeave={() => setActive(null)}>
              {model.cells.map((cell) => (
                <span
                  key={`${cell.col}:${cell.row}`}
                  className="nx-heatmap-cell"
                  data-level={cell.level}
                  data-col={cell.col}
                  data-row={cell.row}
                  data-active={active?.col === cell.col && active.row === cell.row ? '' : undefined}
                  style={vars({ '--nx-d': cell.col + cell.row, gridColumn: cell.col + 1, gridRow: cell.row + 1 })}
                />
              ))}
            </div>
          </div>
        </div>
        <ChartTip tip={tip} open={!!current} title={current?.title ?? ''} rows={current ? [{ name: unit ?? '', value: model.format.format(current.value) }] : []} />
      </div>
      <div className="nx-heatmap-foot">
        {summary && <span>{summary}</span>}
        <div className="nx-heatmap-legend" aria-hidden="true">
          <span>{word('less')}</span>
          {[0, 1, 2, 3, 4].map((level) => (
            <span key={level} className="nx-heatmap-cell" data-level={level} />
          ))}
          <span>{word('more')}</span>
        </div>
      </div>
      <table className="nx-visually-hidden">
        {title && <caption>{title}</caption>}
        <thead>
          <tr>
            <td />
            {model.table.head.map((head, i) => (
              <th key={i} scope="col">
                {head}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {model.table.body.map((row, i) => (
            <tr key={i}>
              <th scope="row">{row.label}</th>
              {row.cells.map((cell, c) => (
                <td key={c}>{cell}</td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </figure>
  );
}

/* ==========================================================================================
 * Status badge
 * ======================================================================================== */

export type JobStatus = 'running' | 'success' | 'failed' | 'queued' | 'canceled';

const STATUSES: JobStatus[] = ['running', 'success', 'failed', 'queued', 'canceled'];

export interface StatusBadgeProps extends HTMLAttributes<HTMLSpanElement> {
  status: JobStatus;
  /** Text for the current status; the built-in word by default. */
  label?: string;
  /** Text per status. */
  labels?: Partial<Record<JobStatus, string>>;
  size?: 'sm' | 'md' | 'lg';
  /** Announce changes to screen readers (role="status"). */
  live?: boolean;
}

function StatusGlyph({ status }: { status: JobStatus }) {
  switch (status) {
    case 'running':
      return (
        <span className="nx-status-badge-icon">
          <span className="nx-status-badge-ring" />
          <span className="nx-status-badge-dot" />
        </span>
      );
    case 'queued':
      return (
        <span className="nx-status-badge-icon">
          <span className="nx-status-badge-dots">
            <i />
            <i />
            <i />
          </span>
        </span>
      );
    case 'canceled':
      return (
        <span className="nx-status-badge-icon">
          <svg viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="6.25" />
            <path className="nx-status-badge-mark" d="M3.8 12.2L12.2 3.8" pathLength={1} />
          </svg>
        </span>
      );
    default:
      return (
        <span className="nx-status-badge-icon">
          <svg viewBox="0 0 16 16">
            <circle className="nx-status-badge-disc" cx="8" cy="8" r="7.5" />
            <path className="nx-status-badge-mark" d={status === 'success' ? 'M4.9 8.3l2.1 2.1 4.1-4.4' : 'M5.7 5.7l4.6 4.6M10.3 5.7l-4.6 4.6'} pathLength={1} />
          </svg>
        </span>
      );
  }
}

export function StatusBadge({ status, label, labels, size, live, className, ...rest }: StatusBadgeProps) {
  const word = useWord();
  const ref = useRef<HTMLSpanElement>(null);
  const previous = useRef<JobStatus | null>(null);
  const text = (s: JobStatus) => (s === status && label) || labels?.[s] || word(s);

  useIsoLayoutEffect(() => {
    const badge = ref.current;
    if (!badge) return;
    if (previous.current && previous.current !== status) badge.setAttribute('data-changed', '');
    previous.current = status;
    fitStatusBadge(badge);
  }, [status, label, labels, word]);

  useEffect(() => {
    let cancelled = false;
    document.fonts?.ready.then(() => !cancelled && fitStatusBadge(ref.current)).catch(() => {});
    return () => {
      cancelled = true;
    };
  }, []);

  return (
    <span ref={ref} className={cx('nx-status-badge', className)} data-status={status} data-size={size && size !== 'md' ? size : undefined} role={live ? 'status' : undefined} {...rest}>
      <span className="nx-visually-hidden">{text(status)}</span>
      <span className="nx-status-badge-layers" aria-hidden="true">
        {STATUSES.map((s) => (
          <span key={s} className="nx-status-badge-layer" data-for={s}>
            <StatusGlyph status={s} />
            <span className="nx-status-badge-label">{text(s)}</span>
          </span>
        ))}
      </span>
    </span>
  );
}

/* ==========================================================================================
 * Data table
 * ======================================================================================== */

type Row = Record<string, unknown>;

export interface DataTableColumn<R extends Row = Row> {
  key: string;
  label: ReactNode;
  align?: 'start' | 'center' | 'end';
  sortable?: boolean;
  /**
   * A preset — number · compact · percent (0.42) · currency:EUR · date · datetime ·
   * status (renders a StatusBadge) — or your own renderer.
   */
  format?: string | ((value: unknown, row: R) => ReactNode);
  /** What sorting compares for this column (the raw value; statuses by urgency). */
  sortValue?: (row: R) => unknown;
  width?: string;
}

export interface DataTableProps<R extends Row = Row> extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  columns: DataTableColumn<R>[];
  rows: R[];
  /** A field name or a function; `id` (then the index) by default. Rows keep their DOM node across sorts. */
  rowKey?: string | ((row: R, index: number) => string);
  sort?: SortState | null;
  defaultSort?: SortState | null;
  onSortChange?: (sort: SortState) => void;
  caption?: ReactNode;
  /** Keep the caption for screen readers only. */
  captionHidden?: boolean;
  /** Scroll inside the table past this height, with the header stuck on top. */
  maxHeight?: string;
  density?: 'comfortable' | 'compact';
  emptyText?: ReactNode;
  locale?: string;
}

const NUMERIC = /^(number|compact|percent|currency)/;

export function DataTable<R extends Row = Row>({
  columns,
  rows,
  rowKey = 'id',
  sort,
  defaultSort = null,
  onSortChange,
  caption,
  captionHidden,
  maxHeight,
  density,
  emptyText,
  locale,
  className,
  style,
  ...rest
}: DataTableProps<R>) {
  const t = useT();
  const intl = useIntl(locale);
  const scroller = useRef<HTMLDivElement>(null);
  const body = useRef<HTMLTableSectionElement>(null);
  const snapshot = useRef<RowSnapshot | null>(null);
  const [current, setCurrent] = useControllable<SortState | null>(sort, defaultSort, onSortChange as (s: SortState | null) => void);
  const [scrollable, setScrollable] = useState(false);
  useBehavior(scroller, reveal, { once: true });

  const order = useMemo(() => new Map(rows.map((row, index) => [row, index])), [rows]);
  const keyOf = (row: R) => {
    const index = order.get(row) ?? 0;
    return typeof rowKey === 'function' ? rowKey(row, index) : String(row[rowKey] ?? index);
  };

  const sorted = useMemo(() => {
    if (!current) return rows;
    const column = columns.find((c) => c.key === current.key);
    const value = column?.sortValue
      ? (row: R) => column.sortValue!(row)
      : column?.format === 'status'
        ? (row: R) => statusRank(row[current.key])
        : undefined;
    return sortRows(rows, current.key, current.direction, { locale: intl, value });
  }, [rows, columns, current, intl]);

  // Rows glide to their new places after a sort (FLIP).
  useIsoLayoutEffect(() => {
    if (!snapshot.current || !body.current) return;
    playRowFlip(Array.from(body.current.rows), snapshot.current);
    snapshot.current = null;
  }, [current?.key, current?.direction]);

  // A table wider or taller than its box can be scrolled from the keyboard.
  useEffect(() => {
    const el = scroller.current;
    if (!el || typeof ResizeObserver === 'undefined') return;
    const check = () => setScrollable(el.scrollWidth > el.clientWidth + 1 || el.scrollHeight > el.clientHeight + 1);
    check();
    const observer = new ResizeObserver(check);
    observer.observe(el);
    return () => observer.disconnect();
  }, []);

  const press = (key: string) => {
    if (body.current) snapshot.current = snapshotRows(Array.from(body.current.rows));
    setCurrent(nextSort(current, key));
    // A controlled table whose parent keeps the old sort never re-renders: drop the snapshot.
    requestAnimationFrame(() => (snapshot.current = null));
  };

  const cell = (column: DataTableColumn<R>, row: R) => {
    const value = row[column.key];
    if (typeof column.format === 'function') return column.format(value, row);
    if (column.format === 'status') return <StatusBadge status={value as JobStatus} size="sm" />;
    return formatCell(value, column.format, intl);
  };

  const alignOf = (column: DataTableColumn<R>) => column.align ?? (typeof column.format === 'string' && NUMERIC.test(column.format) ? 'end' : 'start');

  return (
    <div
      ref={scroller}
      className={cx('nx-data-table', className)}
      data-nx-reveal=""
      data-density={density === 'compact' ? 'compact' : undefined}
      tabIndex={scrollable ? 0 : undefined}
      role={scrollable ? 'region' : undefined}
      aria-label={scrollable && typeof caption === 'string' ? caption : undefined}
      style={{ ...(maxHeight ? vars({ '--nx-table-max': maxHeight }) : null), ...style }}
      {...rest}
    >
      <table>
        {caption && <caption className={captionHidden ? 'nx-visually-hidden' : undefined}>{caption}</caption>}
        <thead>
          <tr>
            {columns.map((column) => {
              const direction = current?.key === column.key ? current.direction : undefined;
              const align = alignOf(column);
              return (
                <th key={column.key} scope="col" aria-sort={direction} data-align={align === 'start' ? undefined : align} style={column.width ? { inlineSize: column.width } : undefined}>
                  {column.sortable ? (
                    <button type="button" className="nx-data-table-sort" data-direction={direction} onClick={() => press(column.key)}>
                      <span>{column.label}</span>
                      <svg className="nx-data-table-sort-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 19V5M6 11l6-6 6 6" />
                      </svg>
                    </button>
                  ) : (
                    column.label
                  )}
                </th>
              );
            })}
          </tr>
        </thead>
        <tbody ref={body}>
          {sorted.length === 0 && (
            <tr>
              <td className="nx-data-table-empty" colSpan={columns.length}>
                {emptyText ?? t('noResults')}
              </td>
            </tr>
          )}
          {sorted.map((row, index) => {
            const key = keyOf(row);
            return (
              <tr key={key} data-key={key} style={vars({ '--nx-i': index })}>
                {columns.map((column) => {
                  const align = alignOf(column);
                  return (
                    <td key={column.key} data-align={align === 'start' ? undefined : align} data-numeric={typeof column.format === 'string' && NUMERIC.test(column.format) ? '' : undefined}>
                      {cell(column, row)}
                    </td>
                  );
                })}
              </tr>
            );
          })}
        </tbody>
      </table>
    </div>
  );
}

/* ==========================================================================================
 * Metric chart
 * ======================================================================================== */

export interface MetricChartMetric {
  id: string;
  label: string;
  values: number[];
  format?: Intl.NumberFormatOptions;
  /** The headline number; the last value by default. */
  value?: number;
  /** Change against the previous period, in percent. */
  delta?: number;
  /** When a rise is bad news (latency, costs). */
  invertDelta?: boolean;
}

export interface MetricChartProps extends Omit<HTMLAttributes<HTMLElement>, 'title' | 'defaultValue'> {
  labels: string[];
  metrics: MetricChartMetric[];
  /** The id of the metric on show. */
  value?: string;
  defaultValue?: string;
  onValueChange?: (id: string) => void;
  title?: ReactNode;
  subtitle?: ReactNode;
  /** Beside the delta ("vs last month"). */
  caption?: ReactNode;
  /** Plot height in px. */
  height?: number;
  locale?: string;
}

export function MetricChart({ labels, metrics, value, defaultValue, onValueChange, title, subtitle, caption, height = 200, locale, className, style, ...rest }: MetricChartProps) {
  const t = useT();
  const intl = useIntl(locale);
  const base = `nx-metric${useId().replace(/:/g, '')}`;
  const root = useRef<HTMLElement>(null);
  const tabs = useRef<HTMLDivElement>(null);
  const plot = useRef<HTMLDivElement>(null);
  const tip = useRef<HTMLDivElement>(null);
  const line = useRef<SVGPathElement>(null);
  const area = useRef<SVGPathElement>(null);
  const width = useWidth(plot, 560);
  const [currentId, setCurrentId] = useControllable(value, defaultValue ?? metrics[0]?.id ?? '', onValueChange);
  const [active, setActive] = useState<number | null>(null);
  const ind = useIndicatorIn(tabs);
  useBehavior(root, reveal, { once: true });

  const index = Math.max(0, metrics.findIndex((m) => m.id === currentId));
  const metric = metrics[index]!;
  const fmt = useMemo(() => new Intl.NumberFormat(intl, metric?.format), [intl, metric?.format]);
  const tick = useMemo(() => new Intl.NumberFormat(intl, { notation: 'compact', maximumFractionDigits: 1, ...(metric?.format?.style === 'currency' ? { style: 'currency', currency: metric.format.currency } : null) }), [intl, metric?.format]);
  const geo = useMemo(() => metricGeometry(metric?.values ?? [], width, height), [metric?.values, width, height]);
  const drawn = useRef<{ id: string; line: string; area: string } | null>(null);

  useIsoLayoutEffect(() => {
    ind.current?.update(tabs.current?.querySelector(`[data-value="${CSS.escape(metric?.id ?? '')}"]`) ?? null);
  }, [metric?.id, metrics]);

  // Switching metrics morphs the line and its wash into the new shape.
  useIsoLayoutEffect(() => {
    const previous = drawn.current;
    drawn.current = { id: metric?.id ?? '', line: geo.line, area: geo.area };
    if (!previous || previous.id === metric?.id || !line.current || !area.current) return;
    morphPath(line.current, geo.line, { from: previous.line });
    morphPath(area.current, geo.area, { from: previous.area, fallback: 'fade' });
  }, [metric?.id, geo.line, geo.area]);

  useIsoLayoutEffect(() => {
    if (active === null || !plot.current || !tip.current) return;
    const point = plot.current.querySelector('.nx-metric-chart-point[data-active]');
    if (point) placeChartTip(plot.current, point, tip.current);
  }, [active, metric?.id]);

  if (!metric) return null;
  const headline = metric.value ?? metric.values[metric.values.length - 1] ?? 0;
  const xs = geo.points.map((p) => p[0]);
  const every = Math.max(1, Math.ceil(labels.length / Math.max(2, Math.floor(width / 84))));

  const onTabKey = (event: KeyboardEvent<HTMLDivElement>) => {
    const dir = getComputedStyle(event.currentTarget).direction === 'rtl' ? -1 : 1;
    const next = stepIndex(event, index, metrics.length, dir);
    if (next === undefined || next === null) return;
    event.preventDefault();
    setCurrentId(metrics[next]!.id);
    tabs.current?.querySelector<HTMLElement>(`[data-value="${CSS.escape(metrics[next]!.id)}"]`)?.focus();
  };

  return (
    <section ref={root} className={cx('nx-metric-chart', className)} data-nx-reveal="" aria-labelledby={title ? `${base}-title` : undefined} style={{ ...vars({ '--nx-series': seriesColor(index) }), ...style }} {...rest}>
      <header className="nx-metric-chart-head">
        {(title || subtitle) && (
          <div>
            {title && (
              <p className="nx-metric-chart-title" id={`${base}-title`}>
                {title}
              </p>
            )}
            {subtitle && <p className="nx-metric-chart-subtitle">{subtitle}</p>}
          </div>
        )}
        <div ref={tabs} className="nx-metric-chart-tabs" role="tablist" aria-label={typeof title === 'string' ? title : undefined} onKeyDown={onTabKey}>
          <span className="nx-indicator" aria-hidden="true" />
          {metrics.map((m, i) => (
            <button
              key={m.id}
              type="button"
              role="tab"
              id={`${base}-tab-${i}`}
              className="nx-metric-chart-tab"
              data-value={m.id}
              aria-selected={i === index}
              aria-controls={`${base}-panel`}
              tabIndex={i === index ? 0 : -1}
              style={vars({ '--nx-series': seriesColor(i) })}
              onClick={() => setCurrentId(m.id)}
            >
              <span className="nx-metric-chart-key" aria-hidden="true" />
              {m.label}
            </button>
          ))}
        </div>
      </header>

      <div className="nx-metric-chart-panel" role="tabpanel" id={`${base}-panel`} aria-labelledby={`${base}-tab-${index}`}>
        <div className="nx-metric-chart-summary">
          <span className="nx-metric-chart-value">
            <NumberTicker value={headline} format={metric.format} locale={intl} />
          </span>
          {metric.delta !== undefined && <Delta delta={metric.delta} invert={metric.invertDelta} locale={intl} />}
          {caption && <span className="nx-metric-chart-caption">{caption}</span>}
        </div>
        <div
          ref={plot}
          className="nx-metric-chart-plot"
          tabIndex={0}
          aria-label={`${metric.label}. ${t('chartHint')}`}
          onPointerMove={(event) => setActive(nearestIndex(xs, event.clientX - event.currentTarget.getBoundingClientRect().left))}
          onPointerLeave={() => setActive(null)}
          onKeyDown={(event) => {
            const next = stepIndex(event, active, labels.length);
            if (next === undefined) return;
            event.preventDefault();
            setActive(next);
          }}
          onBlur={() => setActive(null)}
        >
          <svg className="nx-chart-svg" width={width} height={height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true">
            <defs>
              <linearGradient id={`${base}-fill`} x1="0" x2="0" y1="0" y2="1">
                <stop className="nx-metric-chart-stop" offset="0%" stopOpacity={0.24} />
                <stop className="nx-metric-chart-stop" offset="100%" stopOpacity={0.01} />
              </linearGradient>
            </defs>
            <g key={metric.id} className="nx-metric-chart-ticks">
              {geo.ticks.map((tk, i) => (
                <g key={tk.value}>
                  <line className={i === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1={48} x2={width - 12} y1={tk.y} y2={tk.y} />
                  <text className="nx-chart-tick" x={40} y={tk.y} dy="0.32em" textAnchor="end">
                    {tick.format(tk.value)}
                  </text>
                </g>
              ))}
            </g>
            {labels.map((label, i) =>
              i % every === 0 || i === labels.length - 1 ? (
                <text key={`${label}-${i}`} className="nx-chart-tick" x={xs[i]} y={height - 6} textAnchor={i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle'}>
                  {label}
                </text>
              ) : null,
            )}
            <path ref={area} className="nx-metric-chart-area" d={geo.area} fill={`url(#${base}-fill)`} />
            <path ref={line} className="nx-metric-chart-line" d={geo.line} pathLength={1} />
            <line className="nx-chart-crosshair" data-active={active !== null ? '' : undefined} x1={active === null ? 0 : xs[active]} x2={active === null ? 0 : xs[active]} y1={12} y2={geo.baseline} />
            {geo.points.map(([px, py], i) =>
              i === active || i === geo.points.length - 1 ? (
                <circle key={i} className="nx-metric-chart-point" cx={px} cy={py} r={4} data-active={i === active ? '' : undefined} data-end={i === geo.points.length - 1 ? '' : undefined} />
              ) : null,
            )}
          </svg>
          <ChartTip tip={tip} open={active !== null} title={active === null ? '' : labels[active] ?? ''} rows={active === null ? [] : [{ name: metric.label, value: fmt.format(metric.values[active] ?? 0), color: seriesColor(index) }]} />
        </div>
      </div>

      <table className="nx-visually-hidden">
        {title && <caption>{title}</caption>}
        <thead>
          <tr>
            <td />
            {metrics.map((m) => (
              <th key={m.id} scope="col">
                {m.label}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {labels.map((label, row) => (
            <tr key={`${label}-${row}`}>
              <th scope="row">{label}</th>
              {metrics.map((m) => (
                <td key={m.id}>{new Intl.NumberFormat(intl, m.format).format(m.values[row] ?? 0)}</td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  );
}

/* ==========================================================================================
 * Currency converter
 * ======================================================================================== */

export interface CurrencyValue {
  amount: number;
  from: string;
  to: string;
}

export interface CurrencyConverterProps extends Omit<HTMLAttributes<HTMLElement>, 'title' | 'defaultValue'> {
  /** Rates against any common base, e.g. { USD: 1, EUR: 0.92, JPY: 149.5 }. No network: you pass them in. */
  rates: CurrencyRates;
  /** Which currencies to offer, in order (all of `rates` by default). */
  currencies?: string[];
  value?: CurrencyValue;
  defaultValue?: CurrencyValue;
  onValueChange?: (value: CurrencyValue) => void;
  title?: ReactNode;
  /** Beside the rate ("Mid-market · 2 min ago"). */
  note?: ReactNode;
  labels?: { from?: string; to?: string; swap?: string };
  locale?: string;
}

const SWAP_PATH = 'M7 4v16M3.5 7.5 7 4l3.5 3.5M17 20V4m3.5 12.5L17 20l-3.5-3.5';

export function CurrencyConverter({ rates, currencies, value, defaultValue, onValueChange, title, note, labels, locale, className, ...rest }: CurrencyConverterProps) {
  const word = useWord();
  const intl = useIntl(locale);
  const base = `nx-fx${useId().replace(/:/g, '')}`;
  const codes = currencies ?? Object.keys(rates);
  const [state, setState] = useControllable<CurrencyValue>(value, defaultValue ?? { amount: 1000, from: codes[0] ?? 'USD', to: codes[1] ?? codes[0] ?? 'EUR' }, onValueChange);
  const [text, setText] = useState(() => new Intl.NumberFormat(intl, { maximumFractionDigits: 2 }).format(state.amount));
  const [turns, setTurns] = useState(0);
  const fromRow = useRef<HTMLDivElement>(null);
  const toRow = useRef<HTMLDivElement>(null);
  const swapped = useRef(false);
  const root = useRef<HTMLElement>(null);
  useBehavior(root, reveal, { once: true });

  // A new amount from outside (controlled) replaces what is typed, unless it already says that.
  useEffect(() => {
    const typed = parseAmount(text, intl);
    if (!(Number.isNaN(typed) ? state.amount === 0 : typed === state.amount)) setText(new Intl.NumberFormat(intl, { maximumFractionDigits: 2 }).format(state.amount));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [state.amount, intl]);

  useIsoLayoutEffect(() => {
    if (!swapped.current || !fromRow.current || !toRow.current) return;
    swapped.current = false;
    crossSwap(fromRow.current, toRow.current);
  }, [turns]);

  const result = convertCurrency(state.amount, state.from, state.to, rates);
  const rate = exchangeRate(state.from, state.to, rates);
  const names = useMemo(() => {
    try {
      return new Intl.DisplayNames(intl, { type: 'currency' });
    } catch {
      return null;
    }
  }, [intl]);

  const swap = () => {
    const amount = Number.isFinite(result) ? Math.round(result * 100) / 100 : 0;
    swapped.current = true;
    setState({ amount, from: state.to, to: state.from });
    setText(new Intl.NumberFormat(intl, { maximumFractionDigits: 2 }).format(amount));
    setTurns((n) => n + 1);
  };

  const fromLabel = labels?.from ?? word('from');
  const toLabel = labels?.to ?? word('to');
  const select = (which: 'from' | 'to', label: string) => (
    <select
      className="nx-select nx-currency-converter-select"
      data-size="sm"
      aria-label={`${label} · ${word('currency')}`}
      value={state[which]}
      onChange={(event) => setState({ ...state, [which]: event.target.value })}
    >
      {codes.map((code) => (
        <option key={code} value={code} title={names?.of(code) ?? code}>
          {code}
        </option>
      ))}
    </select>
  );

  return (
    <section ref={root} className={cx('nx-currency-converter', className)} data-nx-reveal="" aria-labelledby={title ? `${base}-title` : undefined} {...rest}>
      {title && (
        <header className="nx-currency-converter-head">
          <p className="nx-currency-converter-title" id={`${base}-title`}>
            {title}
          </p>
        </header>
      )}
      <div className="nx-currency-converter-rows">
        <div ref={fromRow} className="nx-currency-converter-row" data-row="from">
          <label className="nx-currency-converter-label" htmlFor={`${base}-amount`}>
            {fromLabel}
          </label>
          <input
            id={`${base}-amount`}
            className="nx-currency-converter-amount"
            inputMode="decimal"
            autoComplete="off"
            spellCheck={false}
            value={text}
            onChange={(event) => {
              setText(event.target.value);
              const amount = parseAmount(event.target.value, intl);
              setState({ ...state, amount: Number.isNaN(amount) ? 0 : amount });
            }}
          />
          {select('from', fromLabel)}
        </div>
        <button type="button" className="nx-currency-converter-swap" aria-label={labels?.swap ?? word('swap')} style={vars({ '--_turns': turns })} onClick={swap}>
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d={SWAP_PATH} />
          </svg>
        </button>
        <div ref={toRow} className="nx-currency-converter-row" data-row="to">
          <span className="nx-currency-converter-label" id={`${base}-to`}>
            {toLabel}
          </span>
          <output className="nx-currency-converter-result" htmlFor={`${base}-amount`} aria-labelledby={`${base}-to`}>
            <NumberTicker value={Number.isFinite(result) ? result : 0} locale={intl} format={{ style: 'currency', currency: state.to }} />
          </output>
          {select('to', toLabel)}
        </div>
      </div>
      <p className="nx-currency-converter-rate">
        <span>
          <strong>
            {word('rate', { from: state.from, rate: Number.isFinite(rate) ? new Intl.NumberFormat(intl, { maximumSignificantDigits: 6 }).format(rate) : '—', to: state.to })}
          </strong>
        </span>
        {note && <span>{note}</span>}
      </p>
    </section>
  );
}

/* ==========================================================================================
 * Workspace shell
 * ======================================================================================== */

export interface WorkspaceItem {
  id: string;
  label: string;
  icon: IconName;
  href?: string;
  badge?: ReactNode;
  onSelect?: () => void;
}

export interface WorkspaceShellProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue'> {
  items: WorkspaceItem[];
  /** The id of the current item. */
  value?: string;
  defaultValue?: string;
  onValueChange?: (id: string) => void;
  collapsed?: boolean;
  defaultCollapsed?: boolean;
  onCollapsedChange?: (collapsed: boolean) => void;
  /** The product name beside the mark. */
  brand?: ReactNode;
  /** The square mark (a letter by default). */
  brandMark?: ReactNode;
  header?: ReactNode;
  /** Bottom of the sidebar (the signed-in user, say). */
  footer?: ReactNode;
  /** Your own composer; or give `promptPlaceholder` / `onPromptSubmit` for the built-in one. */
  prompt?: ReactNode;
  promptPlaceholder?: string;
  onPromptSubmit?: (text: string) => void;
  /** Height of the frame (36rem by default). */
  height?: string;
  'aria-label'?: string;
  children?: ReactNode;
}

export function WorkspaceShell({
  items,
  value,
  defaultValue,
  onValueChange,
  collapsed,
  defaultCollapsed = false,
  onCollapsedChange,
  brand,
  brandMark,
  header,
  footer,
  prompt,
  promptPlaceholder,
  onPromptSubmit,
  height,
  'aria-label': label,
  className,
  style,
  children,
  ...rest
}: WorkspaceShellProps) {
  const word = useWord();
  const sidebar = `nx-ws${useId().replace(/:/g, '')}`;
  const nav = useRef<HTMLElement>(null);
  const [current, setCurrent] = useControllable(value, defaultValue ?? items[0]?.id ?? '', onValueChange);
  const [isCollapsed, setCollapsed] = useControllable(collapsed, defaultCollapsed, onCollapsedChange);
  const ind = useIndicatorIn(nav);
  const [draft, setDraft] = useState('');

  useIsoLayoutEffect(() => {
    ind.current?.update(nav.current?.querySelector(`[data-value="${CSS.escape(current)}"]`) ?? null);
  }, [current, items]);

  const composer =
    prompt ??
    (promptPlaceholder || onPromptSubmit ? (
      <PromptInput
        placeholder={promptPlaceholder}
        value={draft}
        onValueChange={setDraft}
        onSubmit={(text) => {
          onPromptSubmit?.(text);
          setDraft('');
        }}
      />
    ) : null);

  return (
    <div
      className={cx('nx-workspace', className)}
      data-collapsed={isCollapsed ? '' : undefined}
      style={{ ...(height ? vars({ '--nx-workspace-height': height }) : null), ...style }}
      {...rest}
    >
      <div className="nx-workspace-frame">
        <aside id={sidebar} className="nx-workspace-sidebar">
          <div className="nx-workspace-brand">
            <span className="nx-workspace-mark" aria-hidden={brandMark ? undefined : true}>
              {brandMark ?? (typeof brand === 'string' ? Array.from(brand)[0] : 'N')}
            </span>
            {brand && <span className="nx-workspace-brand-text">{brand}</span>}
          </div>
          <nav ref={nav} className="nx-workspace-nav" aria-label={label}>
            <span className="nx-indicator" aria-hidden="true" />
            <ul>
              {items.map((item) => {
                const on = item.id === current;
                const inner = (
                  <>
                    <Icon name={item.icon} />
                    <span className="nx-workspace-label">{item.label}</span>
                    {item.badge !== undefined && <span className="nx-workspace-badge">{item.badge}</span>}
                  </>
                );
                const common = {
                  className: 'nx-workspace-item',
                  'data-value': item.id,
                  'data-label': item.label,
                  'aria-current': on ? ('page' as const) : undefined,
                  onClick: () => {
                    setCurrent(item.id);
                    item.onSelect?.();
                  },
                };
                return (
                  <li key={item.id}>
                    {item.href ? (
                      <SmartLink href={item.href} {...common}>
                        {inner}
                      </SmartLink>
                    ) : (
                      <button type="button" {...common}>
                        {inner}
                      </button>
                    )}
                  </li>
                );
              })}
            </ul>
          </nav>
          <div className="nx-workspace-sidebar-foot">
            {footer}
            <button
              type="button"
              className="nx-workspace-item nx-workspace-toggle"
              aria-controls={sidebar}
              aria-expanded={!isCollapsed}
              data-label={isCollapsed ? word('expand') : word('collapse')}
              onClick={() => setCollapsed(!isCollapsed)}
            >
              <Icon name="chevron-left" />
              <span className="nx-workspace-label">{isCollapsed ? word('expand') : word('collapse')}</span>
            </button>
          </div>
        </aside>
        <div className="nx-workspace-main">
          <header className="nx-workspace-header">{header}</header>
          <div className="nx-workspace-content">{children}</div>
          {composer && <div className="nx-workspace-composer">{composer}</div>}
        </div>
      </div>
    </div>
  );
}

/* ==========================================================================================
 * Support agent card
 * ======================================================================================== */

export interface AgentMetric {
  label: ReactNode;
  value: number;
  /** The bar is value / max (100 by default). */
  max?: number;
  /** What to print; the formatted value by default. */
  display?: ReactNode;
  tone?: 'accent' | 'success' | 'warning' | 'danger' | 'info';
}

export type AgentPresence = 'online' | 'away' | 'busy' | 'offline';

export interface SupportAgentCardProps extends Omit<HTMLAttributes<HTMLElement>, 'role'> {
  name: string;
  /** The agent's job and place ("Billing · Lagos"); the article keeps its implicit ARIA role. */
  role?: ReactNode;
  avatar?: string;
  status?: AgentPresence;
  statusLabel?: string;
  metrics?: AgentMetric[];
  /** Recent values for the sparkline. */
  trend?: number[];
  trendLabel?: ReactNode;
  trendValue?: ReactNode;
  action?: Action;
  locale?: string;
}

export function SupportAgentCard({ name, role, avatar, status = 'online', statusLabel, metrics = [], trend, trendLabel, trendValue, action, locale, className, ...rest }: SupportAgentCardProps) {
  const word = useWord();
  const intl = useIntl(locale);
  const ref = useRef<HTMLElement>(null);
  useBehavior(ref, reveal, { once: true });
  const number = new Intl.NumberFormat(intl);

  return (
    <article ref={ref} className={cx('nx-agent-card', className)} data-nx-reveal="" {...rest}>
      <header className="nx-agent-card-head">
        <Avatar name={name} src={avatar} size="lg" status={status} />
        <div className="nx-agent-card-identity">
          <h3 className="nx-agent-card-name">{name}</h3>
          {role && <p className="nx-agent-card-role">{role}</p>}
        </div>
        <span className="nx-agent-card-status" data-status={status}>
          <i aria-hidden="true" />
          {statusLabel ?? word(status)}
        </span>
      </header>
      {metrics.length > 0 && (
        <ul className="nx-agent-card-metrics">
          {metrics.map((metric, i) => (
            <li key={i} className="nx-agent-card-metric" data-tone={metric.tone && metric.tone !== 'accent' ? metric.tone : undefined} style={vars({ '--nx-i': i, '--nx-value': Math.min(1, Math.max(0, metric.value / (metric.max ?? 100))) })}>
              <div className="nx-agent-card-metric-head">
                <span>{metric.label}</span>
                <strong>{metric.display ?? number.format(metric.value)}</strong>
              </div>
              <span className="nx-agent-card-bar" aria-hidden="true">
                <span className="nx-agent-card-fill" />
              </span>
            </li>
          ))}
        </ul>
      )}
      {trend && trend.length > 1 && (
        <div className="nx-agent-card-trend">
          <span className="nx-agent-card-trend-label">
            {trendLabel}
            {trendValue !== undefined && <strong>{trendValue}</strong>}
          </span>
          <Sparkline data={trend} trend={trend[trend.length - 1]! >= trend[0]! ? 'up' : 'down'} />
          <span className="nx-visually-hidden">{trend.map((v) => number.format(v)).join(', ')}</span>
        </div>
      )}
      {action && (
        <footer className="nx-agent-card-action">
          <Button variant="primary" block icon={action.icon} href={action.href} onClick={action.onClick}>
            {action.label}
          </Button>
        </footer>
      )}
    </article>
  );
}

/* ==========================================================================================
 * Analytics card
 * ======================================================================================== */

export interface AnalyticsPeriod {
  id: string;
  /** The switch's label ("7d"). */
  label: string;
  /** The headline for this period. */
  value: number;
  /** Change against the previous period, in percent. */
  delta?: number;
  /** One bar per value, oldest first. */
  values: number[];
  /** Names for the bars (days, weeks), for the tooltip and the table. */
  labels?: string[];
  caption?: ReactNode;
}

export interface AnalyticsCardProps extends Omit<HTMLAttributes<HTMLElement>, 'title' | 'defaultValue'> {
  title: ReactNode;
  periods: AnalyticsPeriod[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (id: string) => void;
  format?: Intl.NumberFormatOptions;
  invertDelta?: boolean;
  locale?: string;
}

export function AnalyticsCard({ title, periods, value, defaultValue, onValueChange, format, invertDelta, locale, className, ...rest }: AnalyticsCardProps) {
  const t = useT();
  const intl = useIntl(locale);
  const root = useRef<HTMLElement>(null);
  const frame = useRef<HTMLDivElement>(null);
  const tip = useRef<HTMLDivElement>(null);
  const [current, setCurrent] = useControllable(value, defaultValue ?? periods[0]?.id ?? '', onValueChange);
  const [active, setActive] = useState<number | null>(null);
  useBehavior(root, reveal, { once: true });

  const period = periods.find((p) => p.id === current) ?? periods[0];
  const fmt = useMemo(() => new Intl.NumberFormat(intl, format), [intl, format]);
  const slots = Math.max(0, ...periods.map((p) => p.values.length));

  useIsoLayoutEffect(() => {
    if (active === null || !frame.current || !tip.current) return;
    const bar = frame.current.querySelector(`.nx-analytics-card-slot[data-index="${active}"] .nx-analytics-card-bar`);
    if (bar) placeChartTip(frame.current, bar, tip.current);
  }, [active, current]);

  useEffect(() => setActive(null), [current]);

  if (!period) return null;
  const count = period.values.length;
  const max = Math.max(0, ...period.values) || 1;
  const nameOf = (i: number) => period.labels?.[i] ?? String(i + 1);
  const heading = typeof title === 'string' ? title : undefined;

  return (
    <article ref={root} className={cx('nx-analytics-card', className)} data-nx-reveal="" {...rest}>
      <header className="nx-analytics-card-head">
        <p className="nx-analytics-card-title">{title}</p>
        <SegmentedControl size="sm" aria-label={heading} value={period.id} onValueChange={setCurrent} options={periods.map((p) => ({ value: p.id, label: p.label }))} />
      </header>
      <div className="nx-analytics-card-kpi">
        <span className="nx-analytics-card-value">
          <NumberTicker value={period.value} format={format} locale={intl} />
        </span>
        {period.delta !== undefined && <Delta delta={period.delta} invert={invertDelta} locale={intl} />}
      </div>
      {period.caption && <p className="nx-analytics-card-caption">{period.caption}</p>}
      <div ref={frame} className="nx-analytics-card-frame">
        <div
          className="nx-analytics-card-bars"
          tabIndex={0}
          aria-label={`${heading ? `${heading}. ` : ''}${t('chartHint')}`}
          onPointerOver={(event) => {
            const slot = (event.target as HTMLElement).closest<HTMLElement>('[data-index]');
            if (slot && Number(slot.dataset.index) < count) setActive(Number(slot.dataset.index));
          }}
          onPointerLeave={() => setActive(null)}
          onKeyDown={(event) => {
            const next = stepIndex(event, active, count);
            if (next === undefined) return;
            event.preventDefault();
            setActive(next);
          }}
          onBlur={() => setActive(null)}
        >
          {Array.from({ length: slots }, (_, i) => (
            <span
              key={i}
              className="nx-analytics-card-slot"
              data-index={i}
              data-state={i < count ? 'on' : 'off'}
              data-current={i === count - 1 ? '' : undefined}
              data-active={i === active ? '' : undefined}
              style={vars({ '--nx-i': i, '--nx-v': i < count ? Math.max(0.02, (period.values[i] ?? 0) / max) : 0 })}
            >
              <span className="nx-analytics-card-bar">
                <span className="nx-analytics-card-fill" />
              </span>
            </span>
          ))}
        </div>
        <ChartTip tip={tip} open={active !== null} title={active === null ? '' : nameOf(active)} rows={active === null ? [] : [{ name: heading ?? '', value: fmt.format(period.values[active] ?? 0) }]} />
      </div>
      {count > 1 && (
        <div className="nx-analytics-card-axis" aria-hidden="true">
          <span>{nameOf(0)}</span>
          <span>{nameOf(count - 1)}</span>
        </div>
      )}
      <table className="nx-visually-hidden">
        <caption>
          {heading} · {period.label}
        </caption>
        <tbody>
          {period.values.map((v, i) => (
            <tr key={i}>
              <th scope="row">{nameOf(i)}</th>
              <td>{fmt.format(v)}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </article>
  );
}

/* ==========================================================================================
 * Dot matrix chart
 * ======================================================================================== */

export interface DotMatrixChartProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** Column names (with `series`). */
  labels?: string[];
  series?: Array<{ name: string; values: number[] }>;
  /** One series: [{ label, value }]. */
  data?: Array<{ label: string; value: number }>;
  /** Dots per column (10 by default). */
  rows?: number;
  /** Value of one dot; a round number that fits the tallest column by default. */
  unit?: number;
  title?: ReactNode;
  subtitle?: ReactNode;
  format?: Intl.NumberFormatOptions;
  locale?: string;
}

export function DotMatrixChart({ labels: labelsProp, series: seriesProp, data, rows = 10, unit, title, subtitle, format, locale, className, style, ...rest }: DotMatrixChartProps) {
  const t = useT();
  const word = useWord();
  const intl = useIntl(locale);
  const id = useId();
  const figure = useRef<HTMLElement>(null);
  const frame = useRef<HTMLDivElement>(null);
  const tip = useRef<HTMLDivElement>(null);
  const [active, setActive] = useState<number | null>(null);
  useBehavior(figure, reveal, { once: true });

  const labels = labelsProp ?? data?.map((d) => d.label) ?? [];
  const series = seriesProp ?? [{ name: typeof title === 'string' ? title : 'Value', values: data?.map((d) => d.value) ?? [] }];
  const totals = labels.map((_, c) => series.reduce((sum, s) => sum + Math.max(0, s.values[c] ?? 0), 0));
  const perDot = unit ?? dotUnit(Math.max(0, ...totals), rows);
  const fmt = useMemo(() => new Intl.NumberFormat(intl, format), [intl, format]);
  const every = Math.max(1, Math.ceil(labels.length / 12));

  useIsoLayoutEffect(() => {
    if (active === null || !frame.current || !tip.current) return;
    const col = frame.current.querySelector(`.nx-dot-matrix-col[data-index="${active}"] .nx-dot-matrix-dots`);
    if (col) placeChartTip(frame.current, col, tip.current);
  }, [active]);

  const caption = typeof title === 'string' ? title : undefined;

  return (
    <figure ref={figure} className={cx('nx-chart', 'nx-dot-matrix', className)} data-nx-reveal="" aria-labelledby={title ? `${id}-title` : undefined} style={{ ...vars({ '--nx-rows': rows }), ...style }} {...rest}>
      {(title || subtitle || series.length > 1) && (
        <div className="nx-chart-head">
          <div>
            {title && (
              <p className="nx-chart-title" id={`${id}-title`}>
                {title}
              </p>
            )}
            {subtitle && <p className="nx-chart-subtitle">{subtitle}</p>}
          </div>
          {series.length > 1 && (
            <ul className="nx-chart-legend">
              {series.map((s, i) => (
                <li key={s.name} className="nx-legend-item">
                  <span className="nx-legend-swatch" style={vars({ '--nx-series': seriesColor(i), borderRadius: '50%' })} />
                  {s.name}
                </li>
              ))}
            </ul>
          )}
        </div>
      )}
      <div ref={frame} className="nx-dot-matrix-frame">
        <div
          className="nx-dot-matrix-plot"
          tabIndex={0}
          aria-label={`${caption ? `${caption}. ` : ''}${t('chartHint')}`}
          onPointerOver={(event) => {
            const col = (event.target as HTMLElement).closest<HTMLElement>('[data-index]');
            if (col) setActive(Number(col.dataset.index));
          }}
          onPointerLeave={() => setActive(null)}
          onKeyDown={(event) => {
            const next = stepIndex(event, active, labels.length);
            if (next === undefined) return;
            event.preventDefault();
            setActive(next);
          }}
          onBlur={() => setActive(null)}
        >
          {labels.map((label, c) => {
            const dots = dotStack(series.map((s) => s.values[c] ?? 0), perDot, rows);
            return (
              <div key={`${label}-${c}`} className="nx-dot-matrix-col" data-index={c} data-active={active === c ? '' : undefined} style={vars({ '--nx-c': c })}>
                <span className="nx-dot-matrix-dots" aria-hidden="true">
                  {dots.map((s, r) => (
                    <i key={r} className="nx-dot-matrix-dot" data-series={s ?? undefined} style={vars({ '--nx-r': r, '--nx-series': s === null ? undefined : seriesColor(s) })} />
                  ))}
                </span>
                <span className="nx-dot-matrix-label" aria-hidden="true">
                  {c % every === 0 || c === labels.length - 1 ? label : ''}
                </span>
              </div>
            );
          })}
        </div>
        <ChartTip
          tip={tip}
          open={active !== null}
          title={active === null ? '' : labels[active] ?? ''}
          rows={active === null ? [] : series.map((s, i) => ({ name: s.name, value: fmt.format(s.values[active] ?? 0), color: seriesColor(i) }))}
        />
      </div>
      <p className="nx-dot-matrix-note">{word('perDot', { value: fmt.format(perDot) })}</p>
      <table className="nx-visually-hidden">
        {title && <caption>{title}</caption>}
        <thead>
          <tr>
            <td />
            {series.map((s) => (
              <th key={s.name} scope="col">
                {s.name}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {labels.map((label, row) => (
            <tr key={`${label}-${row}`}>
              <th scope="row">{label}</th>
              {series.map((s) => (
                <td key={s.name}>{fmt.format(s.values[row] ?? 0)}</td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </figure>
  );
}

/* ==========================================================================================
 * Branch connector
 * ======================================================================================== */

export interface BranchNode {
  label: ReactNode;
  description?: ReactNode;
  icon?: IconName | ReactNode;
  /** Idle targets get a quiet link without pulses. */
  state?: 'active' | 'idle';
}

export interface BranchConnectorProps extends HTMLAttributes<HTMLDivElement> {
  source: BranchNode;
  targets: BranchNode[];
  /** Name of the list of targets ("Connected to" by default). */
  targetsLabel?: string;
}

function BranchContent({ node }: { node: BranchNode }) {
  return (
    <>
      {node.icon && <span className="nx-branch-icon">{typeof node.icon === 'string' ? <Icon name={node.icon as IconName} /> : node.icon}</span>}
      <span className="nx-branch-text">
        <span className="nx-branch-label">{node.label}</span>
        {node.description && <span className="nx-branch-description">{node.description}</span>}
      </span>
    </>
  );
}

export function BranchConnector({ source, targets, targetsLabel, className, ...rest }: BranchConnectorProps) {
  const word = useWord();
  const root = useRef<HTMLDivElement>(null);
  useBehavior(root, reveal, { once: true });

  useEffect(() => (root.current ? connect(root.current) : undefined), [targets.length]);

  return (
    <div ref={root} className={cx('nx-branch', className)} data-nx-reveal="" {...rest}>
      <div className="nx-branch-layout">
        <svg className="nx-branch-links" data-nx-links="" aria-hidden="true">
          {targets.map((target, i) => (
            <g key={i} className="nx-branch-link" data-nx-link="" data-state={target.state === 'idle' ? 'idle' : undefined} style={vars({ '--nx-i': i })}>
              <path className="nx-branch-track" pathLength={1} />
              <path className="nx-branch-pulse" pathLength={1} />
            </g>
          ))}
        </svg>
        <div className="nx-branch-node nx-branch-source" data-nx-node="source">
          <BranchContent node={source} />
        </div>
        <ul className="nx-branch-targets" aria-label={targetsLabel ?? word('connectedTo')}>
          {targets.map((target, i) => (
            <li key={i} className="nx-branch-node" data-nx-node="target" data-state={target.state === 'idle' ? 'idle' : 'active'} style={vars({ '--nx-i': i + 1 })}>
              <BranchContent node={target} />
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

/* ==========================================================================================
 * Curved timeline
 * ======================================================================================== */

export interface TimelineMilestone {
  title: ReactNode;
  description?: ReactNode;
  /** A short line above the title ("Q1 2026"). */
  date?: ReactNode;
}

export interface CurvedTimelineProps extends HTMLAttributes<HTMLDivElement> {
  items: TimelineMilestone[];
  /** How far the line swings between milestones, px. */
  amplitude?: number;
  'aria-label'?: string;
}

export function CurvedTimeline({ items, amplitude, 'aria-label': label, className, ...rest }: CurvedTimelineProps) {
  const root = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const el = root.current;
    if (!el) return;
    const stops = [curvedTimeline(el, { amplitude }), ...Array.from(el.querySelectorAll('[data-nx-curve-item]'), (item) => reveal(item, { once: true }))];
    return () => stops.forEach((stop) => stop());
  }, [items.length, amplitude]);

  return (
    <div ref={root} className={cx('nx-curved-timeline', className)} {...rest}>
      <svg className="nx-curved-timeline-svg" data-nx-curve-svg="" aria-hidden="true">
        <path className="nx-curved-timeline-track" data-nx-curve-path="" />
        <path className="nx-curved-timeline-draw" data-nx-curve-path="" pathLength={1} />
      </svg>
      <ol className="nx-curved-timeline-list" aria-label={label}>
        {items.map((item, i) => (
          <li key={i} className="nx-curved-timeline-item" data-nx-curve-item="" data-nx-reveal="" data-side={i % 2 === 0 ? 'start' : 'end'} style={vars({ '--nx-i': i })}>
            <span className="nx-curved-timeline-dot" data-nx-curve-dot="" aria-hidden="true" />
            <div className="nx-curved-timeline-card">
              {item.date && <p className="nx-curved-timeline-date">{item.date}</p>}
              <h3 className="nx-curved-timeline-title">{item.title}</h3>
              {item.description && <p className="nx-curved-timeline-text">{item.description}</p>}
            </div>
          </li>
        ))}
      </ol>
    </div>
  );
}

/* ==========================================================================================
 * Usage card
 * ======================================================================================== */

export interface UsageCategory {
  label: string;
  value: number;
}

export interface UsageCardProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  title?: ReactNode;
  /** The plan's name, shown as a badge. */
  plan?: ReactNode;
  limit: number;
  /** Everything used; the categories' sum by default. */
  used?: number;
  categories: UsageCategory[];
  /** How values read: { style: 'unit', unit: 'gigabyte', maximumFractionDigits: 1 }, say. */
  format?: Intl.NumberFormatOptions;
  /** A line beside the action; turns warning / danger past 75% / 90%. */
  note?: ReactNode;
  action?: Partial<Action>;
  locale?: string;
}

export function UsageCard({ title, plan, limit, used: usedProp, categories, format, note, action, locale, className, ...rest }: UsageCardProps) {
  const word = useWord();
  const intl = useIntl(locale);
  const ref = useRef<HTMLElement>(null);
  useBehavior(ref, reveal, { once: true });

  const used = usedProp ?? categories.reduce((sum, c) => sum + Math.max(0, c.value), 0);
  const ratio = limit > 0 ? used / limit : 0;
  const level = ratio >= 0.9 ? 'danger' : ratio >= 0.75 ? 'warning' : undefined;
  const fmt = new Intl.NumberFormat(intl, format);
  const percent = new Intl.NumberFormat(intl, { style: 'percent', maximumFractionDigits: 0 });

  return (
    <article ref={ref} className={cx('nx-usage-card', className)} data-nx-reveal="" {...rest}>
      {(title || plan) && (
        <header className="nx-usage-card-head">
          {title && <p className="nx-usage-card-title">{title}</p>}
          {plan && (
            <span className="nx-badge" data-tone="accent">
              {plan}
            </span>
          )}
        </header>
      )}
      <p className="nx-usage-card-count">
        <span>{fillTemplate(word('usedOf'), { used: <NumberTicker key="used" value={used} format={format} locale={intl} />, limit: fmt.format(limit) })}</span>
        <span className="nx-usage-card-percent" data-level={level}>
          {percent.format(Math.min(ratio, 9.99))}
        </span>
      </p>
      <div className="nx-usage-card-bar" aria-hidden="true">
        {categories.map((c, i) => (
          <span key={c.label} className="nx-usage-card-segment" style={vars({ '--nx-share': limit > 0 ? Math.max(0, c.value) / limit : 0, '--nx-series': seriesColor(i), '--nx-i': i })} />
        ))}
        {ratio < 1 && <span className="nx-usage-card-free" style={vars({ '--nx-share': 1 - ratio })} />}
      </div>
      <ul className="nx-usage-card-legend">
        {categories.map((c, i) => (
          <li key={c.label} className="nx-usage-card-row" style={vars({ '--nx-i': i })}>
            <span className="nx-usage-card-swatch" style={vars({ '--nx-series': seriesColor(i) })} aria-hidden="true" />
            <span className="nx-usage-card-label">{c.label}</span>
            <span className="nx-usage-card-value">{fmt.format(c.value)}</span>
            <span className="nx-usage-card-share">{percent.format(limit > 0 ? c.value / limit : 0)}</span>
          </li>
        ))}
      </ul>
      {(note || action) && (
        <footer className="nx-usage-card-foot">
          {note && (
            <p className="nx-usage-card-note" data-level={level}>
              {note}
            </p>
          )}
          {action && (
            <Button variant={level ? 'primary' : 'secondary'} size="sm" icon={action.icon ?? 'zap'} href={action.href} onClick={action.onClick} effect={level ? 'shine' : undefined}>
              {action.label ?? word('upgrade')}
            </Button>
          )}
        </footer>
      )}
    </article>
  );
}

/* ==========================================================================================
 * Comparison table
 * ======================================================================================== */

export interface ComparisonPlan {
  id: string;
  name: ReactNode;
  price?: ReactNode;
  /** After the price ("/mo"). */
  period?: ReactNode;
  description?: ReactNode;
  action?: Action;
}

export interface ComparisonFeature {
  label: ReactNode;
  hint?: ReactNode;
  /** Rows sharing a group sit under its heading. */
  group?: string;
  /** Per plan id: true / false for a mark, or any text ("10 GB"). */
  values: Record<string, boolean | ReactNode>;
}

export interface ComparisonTableProps extends HTMLAttributes<HTMLDivElement> {
  plans: ComparisonPlan[];
  features: ComparisonFeature[];
  /** The plan id to light up. */
  recommended?: string;
  caption?: string;
  /** Heading over the feature column. */
  featureLabel?: ReactNode;
  recommendedLabel?: ReactNode;
  includedLabel?: string;
  excludedLabel?: string;
}

export function ComparisonTable({ plans, features, recommended, caption, featureLabel, recommendedLabel, includedLabel, excludedLabel, className, ...rest }: ComparisonTableProps) {
  const word = useWord();
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, reveal, { once: true });
  let lastGroup: string | undefined;

  return (
    <div ref={ref} className={cx('nx-comparison', className)} data-nx-reveal="" {...rest}>
      <div className="nx-comparison-scroll" role={caption ? 'region' : undefined} tabIndex={0} aria-label={caption}>
        <table className="nx-comparison-table">
          {caption && <caption className="nx-visually-hidden">{caption}</caption>}
          <thead>
            <tr>
              <th scope="col" className="nx-comparison-corner">
                {featureLabel ?? word('features')}
              </th>
              {plans.map((plan, j) => (
                <th key={plan.id} scope="col" className="nx-comparison-plan" data-featured={plan.id === recommended ? '' : undefined} style={vars({ '--nx-j': j })}>
                  <span className="nx-comparison-plan-inner">
                    {plan.id === recommended && <span className="nx-comparison-flag">{recommendedLabel ?? word('recommended')}</span>}
                    <span className="nx-comparison-plan-name">{plan.name}</span>
                    {plan.price !== undefined && (
                      <span className="nx-comparison-price">
                        {plan.price}
                        {plan.period && <small>{plan.period}</small>}
                      </span>
                    )}
                    {plan.description && <span className="nx-comparison-plan-description">{plan.description}</span>}
                    {plan.action && (
                      <Button size="sm" variant={plan.id === recommended ? 'primary' : 'secondary'} href={plan.action.href} onClick={plan.action.onClick} icon={plan.action.icon}>
                        {plan.action.label}
                      </Button>
                    )}
                  </span>
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {features.map((feature, i) => {
              const heading = feature.group && feature.group !== lastGroup ? feature.group : null;
              lastGroup = feature.group ?? lastGroup;
              return (
                <Fragment key={i}>
                  {heading && (
                    <tr className="nx-comparison-group">
                      <th scope="colgroup" colSpan={plans.length + 1}>
                        {heading}
                      </th>
                    </tr>
                  )}
                  <tr style={vars({ '--nx-i': i })}>
                    <th scope="row" className="nx-comparison-feature">
                      {feature.label}
                      {feature.hint && <span className="nx-comparison-feature-hint">{feature.hint}</span>}
                    </th>
                    {plans.map((plan, j) => {
                      const v = feature.values[plan.id];
                      return (
                        <td key={plan.id} data-featured={plan.id === recommended ? '' : undefined} style={vars({ '--nx-j': j })}>
                          {typeof v === 'boolean' || v === undefined || v === null ? (
                            <>
                              <span className="nx-comparison-mark" data-value={v ? 'yes' : 'no'} aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                  <path d={v ? 'M5 12.5l4.5 4.5L19 7.5' : 'M6 12h12'} />
                                </svg>
                              </span>
                              <span className="nx-visually-hidden">{v ? includedLabel ?? word('included') : excludedLabel ?? word('excluded')}</span>
                            </>
                          ) : (
                            <span className="nx-comparison-text">{v}</span>
                          )}
                        </td>
                      );
                    })}
                  </tr>
                </Fragment>
              );
            })}
          </tbody>
        </table>
      </div>
    </div>
  );
}
