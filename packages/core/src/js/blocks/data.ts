/**
 * Dashboards & data blocks: the framework-agnostic half.
 *
 * Pure helpers (sorting, heatmap weeks, dot stacks, currency maths, snake and
 * branch paths, cell formats, the blocks' own words) are shared by the React
 * components and the Alpine ones, so both render the same thing. The DOM
 * behaviours (FLIP rows, branch connectors, the curved timeline, path morphs,
 * the cross-swap, tooltip placement) take elements and return a cleanup or the
 * animations they started.
 */
import { type Point, areaPath, linePath, linearScale, niceTicks } from '../charts';
import { type Cleanup, direction, isBrowser, prefersReducedMotion } from '../env';
import { type SpringName, springEasing, springs } from '../spring';

const round = (n: number, digits = 2) => {
  const factor = 10 ** digits;
  return Math.round(n * factor) / factor;
};

const clamp01 = (n: number) => Math.min(1, Math.max(0, n));

/* ---- Springs for the Web Animations API ------------------------------------ */

const timings: Partial<Record<SpringName, { easing: string; duration: number }>> = {};
let linearEasing: boolean | null = null;

/** A spring as a WAAPI timing: `linear()` where the browser has it, a close cubic otherwise. */
function springTiming(name: SpringName): { easing: string; duration: number } {
  if (!timings[name]) {
    const spring = springEasing(springs[name]);
    linearEasing ??= isBrowser && typeof CSS !== 'undefined' && CSS.supports('animation-timing-function', 'linear(0, 1)');
    timings[name] = linearEasing ? spring : { easing: 'cubic-bezier(0.16, 1, 0.3, 1)', duration: Math.round(spring.duration * 0.8) };
  }
  return timings[name]!;
}

/* ---- The blocks' own words ---------------------------------------------------- */

/**
 * Words the data blocks say themselves, in the languages the core i18n table
 * ships. Everything else comes in through props.
 */
export const dataWords = {
  en: {
    less: 'Less',
    more: 'More',
    running: 'Running',
    success: 'Succeeded',
    failed: 'Failed',
    queued: 'Queued',
    canceled: 'Canceled',
    included: 'Included',
    excluded: 'Not included',
    recommended: 'Recommended',
    features: 'Features',
    swap: 'Swap currencies',
    from: 'From',
    to: 'To',
    amount: 'Amount',
    rate: '1 {from} = {rate} {to}',
    collapse: 'Collapse sidebar',
    expand: 'Expand sidebar',
    selected: 'Selected',
    usedOf: '{used} of {limit} used',
    upgrade: 'Upgrade plan',
    perDot: 'Each dot is {value}',
    week: 'Week of {date}',
    currency: 'Currency',
    online: 'Online',
    away: 'Away',
    busy: 'Busy',
    offline: 'Offline',
    connectedTo: 'Connected to',
  },
  fa: {
    less: 'کمتر',
    more: 'بیشتر',
    running: 'در حال اجرا',
    success: 'موفق',
    failed: 'ناموفق',
    queued: 'در صف',
    canceled: 'لغو شد',
    included: 'دارد',
    excluded: 'ندارد',
    recommended: 'پیشنهادی',
    features: 'ویژگی‌ها',
    swap: 'جابه‌جایی ارزها',
    from: 'از',
    to: 'به',
    amount: 'مبلغ',
    rate: '۱ {from} = {rate} {to}',
    collapse: 'بستن نوار کناری',
    expand: 'باز کردن نوار کناری',
    selected: 'انتخاب‌شده‌ها',
    usedOf: '{used} از {limit} مصرف شده',
    upgrade: 'ارتقای طرح',
    perDot: 'هر نقطه برابر {value} است',
    week: 'هفتهٔ {date}',
    currency: 'ارز',
    online: 'آنلاین',
    away: 'دور از میز',
    busy: 'مشغول',
    offline: 'آفلاین',
    connectedTo: 'متصل به',
  },
  ar: {
    less: 'أقل',
    more: 'أكثر',
    running: 'قيد التشغيل',
    success: 'نجح',
    failed: 'فشل',
    queued: 'في الانتظار',
    canceled: 'أُلغي',
    included: 'مشمول',
    excluded: 'غير مشمول',
    recommended: 'موصى به',
    features: 'الميزات',
    swap: 'تبديل العملات',
    from: 'من',
    to: 'إلى',
    amount: 'المبلغ',
    rate: '١ {from} = {rate} {to}',
    collapse: 'طي الشريط الجانبي',
    expand: 'توسيع الشريط الجانبي',
    selected: 'العناصر المحددة',
    usedOf: 'تم استخدام {used} من {limit}',
    upgrade: 'ترقية الخطة',
    perDot: 'كل نقطة تساوي {value}',
    week: 'أسبوع {date}',
    currency: 'العملة',
    online: 'متصل',
    away: 'بعيد',
    busy: 'مشغول',
    offline: 'غير متصل',
    connectedTo: 'متصل بـ',
  },
} as const;

export type DataWord = keyof (typeof dataWords)['en'];

/** `dataWord('fa', 'less')`, `dataWord('en', 'usedOf', { used: 3, limit: 5 })`. Unknown languages fall back to English. */
export function dataWord(locale: string | undefined, key: DataWord, params: Record<string, string | number> = {}): string {
  const language = (locale ?? 'en').slice(0, 2).toLowerCase();
  const table = (dataWords as Record<string, Record<string, string>>)[language] ?? dataWords.en;
  const text = table[key] ?? dataWords.en[key];
  return text.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`));
}

/* ---- Sorting (data table) ---------------------------------------------------------- */

export type SortDirection = 'ascending' | 'descending';

export interface SortState {
  key: string;
  direction: SortDirection;
}

/** Status values sort by urgency, not alphabet: problems first. */
const STATUS_ORDER = ['failed', 'running', 'queued', 'success', 'canceled'];

export function statusRank(status: unknown): number {
  const index = STATUS_ORDER.indexOf(String(status));
  return index === -1 ? STATUS_ORDER.length : index;
}

const asNumber = (value: unknown): number | null => {
  if (typeof value === 'number') return Number.isFinite(value) ? value : null;
  if (value instanceof Date) return value.getTime();
  if (typeof value === 'string' && value.trim() !== '' && Number.isFinite(Number(value))) return Number(value);
  return null;
};

/** Numbers numerically, everything else by the locale's collation ("item 2" before "item 10"). */
export function compareValues(a: unknown, b: unknown, collator: Intl.Collator = new Intl.Collator(undefined, { numeric: true, sensitivity: 'base' })): number {
  const x = asNumber(a);
  const y = asNumber(b);
  if (x !== null && y !== null) return x - y;
  return collator.compare(String(a), String(b));
}

const blank = (value: unknown) => value === null || value === undefined || value === '' || (typeof value === 'number' && Number.isNaN(value));

/**
 * A sorted copy of `rows`. Stable (equal rows keep their order) and empty
 * values always go last, whichever the direction.
 */
export function sortRows<T>(
  rows: readonly T[],
  key: string,
  direction: SortDirection,
  { locale, value }: { locale?: string; value?: (row: T, key: string) => unknown } = {},
): T[] {
  const collator = new Intl.Collator(locale, { numeric: true, sensitivity: 'base' });
  const read = value ?? ((row: T, k: string) => (row as Record<string, unknown> | null)?.[k]);
  const sign = direction === 'descending' ? -1 : 1;
  return rows
    .map((row, index) => ({ row, index, v: read(row, key) }))
    .sort((x, y) => {
      const bx = blank(x.v);
      const by = blank(y.v);
      if (bx || by) return bx === by ? x.index - y.index : bx ? 1 : -1;
      return compareValues(x.v, y.v, collator) * sign || x.index - y.index;
    })
    .map((entry) => entry.row);
}

/** What pressing a column header does: a new column sorts ascending, the same one flips. */
export function nextSort(current: SortState | null | undefined, key: string): SortState {
  if (current?.key === key) return { key, direction: current.direction === 'ascending' ? 'descending' : 'ascending' };
  return { key, direction: 'ascending' };
}

/**
 * Cell text for the preset formats a column can name:
 * number · compact · percent (0.42 → 42%) · currency:EUR · date · datetime · text.
 */
export function formatCell(value: unknown, format: string | undefined, locale?: string): string {
  if (blank(value)) return '—';
  const [kind, arg] = (format ?? 'text').split(':');
  const n = asNumber(value);
  switch (kind) {
    case 'number':
      return n === null ? String(value) : new Intl.NumberFormat(locale).format(n);
    case 'compact':
      return n === null ? String(value) : new Intl.NumberFormat(locale, { notation: 'compact', maximumFractionDigits: 1 }).format(n);
    case 'percent':
      return n === null ? String(value) : new Intl.NumberFormat(locale, { style: 'percent', maximumFractionDigits: 1 }).format(n);
    case 'currency':
      return n === null ? String(value) : new Intl.NumberFormat(locale, { style: 'currency', currency: (arg || 'USD').toUpperCase() }).format(n);
    case 'date':
    case 'datetime': {
      const text = String(value);
      const date = value instanceof Date ? value : new Date(typeof value === 'number' ? value : text);
      if (Number.isNaN(date.getTime())) return text;
      // A bare day ("2026-09-01") is midnight UTC: keep it on that day in every timezone.
      const dayOnly = typeof value === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(text);
      return new Intl.DateTimeFormat(locale, kind === 'date' ? { dateStyle: 'medium', ...(dayOnly ? { timeZone: 'UTC' } : null) } : { dateStyle: 'medium', timeStyle: 'short' }).format(date);
    }
    default:
      return String(value);
  }
}

/* ---- FLIP: rows glide to their new places ------------------------------------------- */

export interface FlipOptions {
  /** The spring the rows travel on (gentle by default). */
  spring?: SpringName;
  /** Delay between successive moving rows, ms. */
  stagger?: number;
}

export type RowSnapshot = Map<Element | string, DOMRect>;

/** Rows are matched by data-key when they carry one (a server morph may replace the node), else by identity. */
const flipKey = (el: Element): Element | string => (el as HTMLElement).dataset?.key ?? el;
const flights = new WeakMap<Element, Animation>();

/** Where each element is now. Take it before the DOM changes and hand it to `playRowFlip` after. */
export function snapshotRows(elements: Iterable<Element>): RowSnapshot {
  const snapshot: RowSnapshot = new Map();
  for (const el of elements) snapshot.set(flipKey(el), el.getBoundingClientRect());
  return snapshot;
}

/**
 * Animate each element from where `snapshotRows` saw it to where it is now.
 * The snapshot includes any flight still in the air, so a second sort mid-way
 * picks the rows up where they are instead of snapping them back.
 */
export function playRowFlip(elements: Iterable<Element>, before: RowSnapshot, { spring = 'gentle', stagger = 0 }: FlipOptions = {}): Animation[] {
  if (!isBrowser || prefersReducedMotion()) return [];
  const { easing, duration } = springTiming(spring);
  const started: Animation[] = [];
  let moved = 0;

  for (const el of elements) {
    if (typeof (el as HTMLElement).animate !== 'function') continue;
    flights.get(el)?.cancel();
    const was = before.get(flipKey(el));
    let flight: Animation;
    if (!was) {
      // A row that was not there before arrives with a short fade.
      flight = el.animate([{ opacity: 0 }, { opacity: 1 }], { duration: 200, easing: 'ease-out' });
    } else {
      const now = el.getBoundingClientRect();
      const dx = was.left - now.left;
      const dy = was.top - now.top;
      if (Math.abs(dx) < 0.5 && Math.abs(dy) < 0.5) continue;
      flight = el.animate([{ translate: `${round(dx)}px ${round(dy)}px` }, { translate: '0px 0px' }], { duration, easing, delay: moved * stagger, fill: 'backwards' });
      moved += 1;
    }
    flights.set(el, flight);
    started.push(flight);
  }
  return started;
}

/**
 * Reorder `container`'s rows inside `mutate` (move the nodes, or let a framework
 * re-render) and they glide from their old places to the new ones.
 *
 *   flipRows(tbody, () => tbody.append(...sorted));
 */
export async function flipRows(container: Element, mutate: () => void | Promise<void>, options: FlipOptions & { selector?: string } = {}): Promise<Animation[]> {
  const rows = () => (options.selector ? Array.from(container.querySelectorAll(options.selector)) : Array.from(container.children));
  const before = snapshotRows(rows());
  await mutate();
  return playRowFlip(rows(), before, options);
}

/* ---- Heatmap -------------------------------------------------------------------------- */

export interface HeatmapDatum {
  /** A day, "YYYY-MM-DD". */
  date: string;
  value: number;
}

export interface HeatmapCell {
  date: string;
  value: number;
  /** 0 (nothing) to 4 (the busiest days). */
  level: number;
  /** Week column and weekday row. */
  col: number;
  row: number;
}

export interface HeatmapLayout {
  /** Week columns of seven days; days outside the range are null. */
  weeks: Array<Array<HeatmapCell | null>>;
  /** The first column holding each month's 1st day (for the labels above). */
  months: Array<{ col: number; date: string }>;
  max: number;
  total: number;
}

const DAY = 86_400_000;

const parseDay = (iso: string) => {
  const [y, m, d] = iso.slice(0, 10).split('-').map(Number);
  return Date.UTC(y!, (m || 1) - 1, d || 1);
};

const isoDay = (time: number) => new Date(time).toISOString().slice(0, 10);

/** The level of a value on a sequential scale of `steps` (0 is kept for "nothing"). */
export function heatLevel(value: number, max: number, steps = 4): number {
  if (!(value > 0) || !(max > 0)) return 0;
  return Math.min(steps, Math.max(1, Math.ceil((value / max) * steps)));
}

export interface HeatmapWeeksOptions {
  /** 0 for Sunday, 1 for Monday, 6 for Saturday (the Persian week). */
  weekStart?: number;
  /** Exactly this many week columns, ending with `end` (or the last day with data). */
  weeks?: number;
  end?: string;
  /**
   * Whether a day opens a month, for the labels above. Gregorian by default;
   * pass one from `Intl` so the months follow the reader's calendar.
   */
  monthStart?: (date: string) => boolean;
}

/** Lay daily values out as a contribution calendar: one column per week, one row per weekday. */
export function heatmapWeeks(data: readonly HeatmapDatum[], { weekStart = 0, weeks, end, monthStart }: HeatmapWeeksOptions = {}): HeatmapLayout {
  const values = new Map<number, number>();
  for (const datum of data) {
    const day = parseDay(datum.date);
    if (Number.isFinite(day)) values.set(day, (values.get(day) ?? 0) + (Number(datum.value) || 0));
  }
  const days = [...values.keys()].sort((a, b) => a - b);
  const today = new Date();
  const last = end ? parseDay(end) : days[days.length - 1] ?? Date.UTC(today.getUTCFullYear(), today.getUTCMonth(), today.getUTCDate());
  const weekday = (time: number) => (new Date(time).getUTCDay() - weekStart + 7) % 7;
  const lastWeek = last - weekday(last) * DAY;
  const first = weeks ? lastWeek - (weeks - 1) * 7 * DAY : Math.min(days[0] ?? last, last);
  const firstWeek = first - weekday(first) * DAY;
  const columns = Math.round((lastWeek - firstWeek) / (7 * DAY)) + 1;

  const inRange: number[] = [];
  for (let day = first; day <= last; day += DAY) inRange.push(values.get(day) ?? 0);
  const max = Math.max(0, ...inRange);
  const total = inRange.reduce((sum, v) => sum + v, 0);

  const grid: Array<Array<HeatmapCell | null>> = [];
  const months: Array<{ col: number; date: string }> = [];
  for (let col = 0; col < columns; col++) {
    const week: Array<HeatmapCell | null> = [];
    for (let row = 0; row < 7; row++) {
      const day = firstWeek + (col * 7 + row) * DAY;
      if (day < first || day > last) {
        week.push(null);
        continue;
      }
      const value = values.get(day) ?? 0;
      const date = isoDay(day);
      week.push({ date, value, level: heatLevel(value, max), col, row });
      if (monthStart ? monthStart(date) : new Date(day).getUTCDate() === 1) months.push({ col, date });
    }
    grid.push(week);
  }

  // The partial month the range opens with gets a label too, when there is room before the next one.
  const opening = grid[0]?.find(Boolean);
  if (opening && !months.some((m) => m.col === 0) && (months[0]?.col ?? Infinity) >= 3) months.unshift({ col: 0, date: opening.date });

  return { weeks: grid, months, max, total };
}

/* ---- Dot matrix --------------------------------------------------------------------------- */

/** A round value per dot (1, 2, 2.5, 5 × 10ⁿ) so the tallest column fits in `rows` dots. */
export function dotUnit(maxTotal: number, rows: number): number {
  if (!(maxTotal > 0) || rows < 1) return 1;
  const rough = maxTotal / rows;
  const magnitude = 10 ** Math.floor(Math.log10(rough));
  return [1, 2, 2.5, 5, 10].map((m) => m * magnitude).find((step) => step >= rough - 1e-9) ?? 10 * magnitude;
}

/**
 * Which series lights each dot of one column, bottom to top (null = unlit).
 * Series keep their fixed order; largest-remainder rounding keeps the lit
 * total true to the column's total.
 */
export function dotStack(values: readonly number[], unit: number, rows: number): Array<number | null> {
  const exact = values.map((v) => Math.max(0, Number(v) || 0) / (unit || 1));
  const total = Math.min(rows, Math.round(exact.reduce((sum, v) => sum + v, 0)));
  const counts = exact.map((v) => Math.floor(v));
  let spare = total - counts.reduce((sum, v) => sum + v, 0);
  for (let i = counts.length - 1; i >= 0 && spare < 0; i--) {
    const cut = Math.min(counts[i], -spare);
    counts[i] -= cut;
    spare += cut;
  }
  const order = exact.map((v, i) => ({ i, rest: v - Math.floor(v) })).sort((a, b) => b.rest - a.rest || a.i - b.i);
  for (const { i } of order) {
    if (spare <= 0) break;
    counts[i] += 1;
    spare -= 1;
  }
  const dots: Array<number | null> = [];
  counts.forEach((count, series) => {
    for (let k = 0; k < count && dots.length < rows; k++) dots.push(series);
  });
  while (dots.length < rows) dots.push(null);
  return dots;
}

/* ---- Currency --------------------------------------------------------------------------------- */

/** Rates quoted against any common base: { USD: 1, EUR: 0.92, JPY: 149.3 }. */
export type CurrencyRates = Readonly<Record<string, number>>;

/** Units of `to` for one unit of `from`. */
export function exchangeRate(from: string, to: string, rates: CurrencyRates): number {
  const a = Number(rates[from]);
  const b = Number(rates[to]);
  if (!(a > 0) || !(b > 0)) return Number.NaN;
  return b / a;
}

export function convertCurrency(amount: number, from: string, to: string, rates: CurrencyRates): number {
  return amount * exchangeRate(from, to, rates);
}

const decimalOf = (locale?: string) => new Intl.NumberFormat(locale).formatToParts(1.5).find((p) => p.type === 'decimal')?.value ?? '.';

/**
 * Read an amount as people type it, in any numbering system and either
 * separator convention: "1,234.5", "1.234,5", "۱۲٬۳۴۵٫۶", "1,5". A lone
 * separator followed by exactly three digits groups; otherwise it is the
 * decimal point. NaN when there is no number.
 */
export function parseAmount(text: string, locale?: string): number {
  let s = String(text ?? '')
    .trim()
    .replace(/[۰-۹]/g, (d) => String(d.charCodeAt(0) - 0x06f0))
    .replace(/[٠-٩]/g, (d) => String(d.charCodeAt(0) - 0x0660))
    .replace(/٫/g, '.')
    .replace(/[٬\s  '’]/g, '')
    .replace(/[−–]/g, '-');
  s = s.replace(/[^\d.,-]/g, '');
  if (!/\d/.test(s)) return Number.NaN;
  const negative = s.startsWith('-');
  s = s.replace(/-/g, '');

  const lastDot = s.lastIndexOf('.');
  const lastComma = s.lastIndexOf(',');
  let decimal: string | null = null;
  if (lastDot !== -1 && lastComma !== -1) decimal = lastDot > lastComma ? '.' : ',';
  else if (lastDot !== -1 || lastComma !== -1) {
    const separator = lastDot !== -1 ? '.' : ',';
    const count = s.split(separator).length - 1;
    const tail = s.length - s.lastIndexOf(separator) - 1;
    decimal = count > 1 ? null : separator === decimalOf(locale) || tail !== 3 ? separator : null;
  }

  let normalised: string;
  if (decimal) {
    const at = s.lastIndexOf(decimal);
    normalised = `${s.slice(0, at).replace(/[.,]/g, '')}.${s.slice(at + 1).replace(/[.,]/g, '')}`;
  } else normalised = s.replace(/[.,]/g, '');
  const value = Number(normalised);
  return Number.isFinite(value) ? (negative ? -value : value) : Number.NaN;
}

/* ---- Paths ------------------------------------------------------------------------------------- */

/** The command letters of a path: two paths morph smoothly only when these match. */
export function pathShape(d: string): string {
  return d.replace(/[^A-Za-z]/g, '').toUpperCase();
}

/**
 * A curved link between two points. Horizontal links leave and enter level
 * (a flow chart's branches); vertical ones leave downward.
 */
export function branchPath(from: Point, to: Point, orientation: 'horizontal' | 'vertical' = 'horizontal', curvature = 0.55): string {
  const [x0, y0] = from;
  const [x1, y1] = to;
  if (orientation === 'vertical') {
    const dy = (y1 - y0) * curvature;
    return `M${round(x0)},${round(y0)}C${round(x0)},${round(y0 + dy)} ${round(x1)},${round(y1 - dy)} ${round(x1)},${round(y1)}`;
  }
  const dx = (x1 - x0) * curvature;
  return `M${round(x0)},${round(y0)}C${round(x0 + dx)},${round(y0)} ${round(x1 - dx)},${round(y1)} ${round(x1)},${round(y1)}`;
}

const cubicLength = (p0: Point, p1: Point, p2: Point, p3: Point, steps = 24) => {
  let length = 0;
  let [px, py] = p0;
  for (let i = 1; i <= steps; i++) {
    const t = i / steps;
    const u = 1 - t;
    const x = u * u * u * p0[0] + 3 * u * u * t * p1[0] + 3 * u * t * t * p2[0] + t * t * t * p3[0];
    const y = u * u * u * p0[1] + 3 * u * u * t * p1[1] + 3 * u * t * t * p2[1] + t * t * t * p3[1];
    length += Math.hypot(x - px, y - py);
    px = x;
    py = y;
  }
  return length;
};

/**
 * A path that snakes through the points top to bottom, swinging to alternate
 * sides between them. Each swing is proportional to its segment's height, so
 * the tangent is the same at every point and the curve never kinks. `stops`
 * is how far along the path (0–1) each point sits: the draw progress at which
 * the drawn line reaches it.
 */
export function snakePath(points: readonly Point[], { amplitude = 32, dir = 1 }: { amplitude?: number; dir?: 1 | -1 } = {}): { d: string; stops: number[] } {
  if (points.length === 0) return { d: '', stops: [] };
  const [fx, fy] = points[0]!;
  let d = `M${round(fx)},${round(fy)}`;
  if (points.length === 1) return { d, stops: [0] };

  const tallest = Math.max(...points.slice(1).map((p, i) => Math.abs(p[1] - points[i]![1]))) || 1;
  const lengths = [0];
  for (let i = 0; i < points.length - 1; i++) {
    const p0 = points[i]!;
    const p3 = points[i + 1]!;
    const h = p3[1] - p0[1];
    const swing = (i % 2 === 0 ? 1 : -1) * dir * amplitude * (Math.abs(h) / tallest);
    const p1: Point = [p0[0] + swing, p0[1] + h * 0.3];
    const p2: Point = [p3[0] + swing, p3[1] - h * 0.3];
    d += `C${round(p1[0])},${round(p1[1])} ${round(p2[0])},${round(p2[1])} ${round(p3[0])},${round(p3[1])}`;
    lengths.push(lengths[i]! + cubicLength(p0, p1, p2, p3));
  }
  const total = lengths[lengths.length - 1] || 1;
  return { d, stops: lengths.map((length) => round(length / total, 4)) };
}

let pathMorph: boolean | null = null;

/** Whether the browser interpolates the CSS `d` property (so a path can morph with WAAPI). */
export function supportsPathMorph(): boolean {
  if (!isBrowser) return false;
  pathMorph ??= typeof CSS !== 'undefined' && CSS.supports('d', 'path("M0 0")');
  return pathMorph;
}

export interface MorphOptions {
  /** The shape to start from; the path's current (possibly mid-flight) shape by default. */
  from?: string;
  spring?: SpringName;
  /** Where `d` cannot be animated: replay the line's draw, fade the shape in, or just swap. */
  fallback?: 'draw' | 'fade' | 'none';
}

const morphs = new WeakMap<Element, Animation>();

/**
 * Move a path to a new shape. Paths with the same commands (the same point
 * count) morph through Web Animations on `d`; elsewhere the new line draws
 * itself in (needs pathLength="1" and a dash of 1) or fades in. Interrupting
 * a morph starts the next one from wherever the line is.
 */
export function morphPath(path: SVGPathElement, to: string, { from, spring = 'gentle', fallback = 'draw' }: MorphOptions = {}): Animation | null {
  let start = from ?? path.getAttribute('d') ?? '';
  const flying = morphs.get(path);
  if (flying && flying.playState === 'running' && supportsPathMorph()) {
    const current = getComputedStyle(path).getPropertyValue('d');
    const match = /^path\(["'](.*)["']\)$/.exec(current.trim());
    if (match?.[1]) start = match[1];
  }
  flying?.cancel();
  if (path.getAttribute('d') !== to) path.setAttribute('d', to);
  if (!isBrowser || !start || start === to || prefersReducedMotion() || typeof path.animate !== 'function') return null;

  let animation: Animation | null = null;
  if (supportsPathMorph() && pathShape(start) === pathShape(to)) {
    const { easing, duration } = springTiming(spring);
    try {
      animation = path.animate([{ d: `path("${start}")` }, { d: `path("${to}")` }], { duration, easing });
    } catch {
      animation = null;
    }
  }
  if (!animation && fallback === 'draw') animation = path.animate([{ strokeDashoffset: 1 }, { strokeDashoffset: 0 }], { duration: 900, easing: 'cubic-bezier(0.16, 1, 0.3, 1)' });
  if (!animation && fallback === 'fade') animation = path.animate([{ opacity: 0 }, { opacity: 1 }], { duration: 400, easing: 'ease-out' });
  if (animation) morphs.set(path, animation);
  return animation;
}

/**
 * Call right after two slots traded their contents (a currency swap): each
 * slot's new content travels in from where the other one sits, so the two
 * rows appear to cross.
 */
export function crossSwap(a: HTMLElement, b: HTMLElement, { spring = 'gentle' }: { spring?: SpringName } = {}): Animation[] {
  if (!isBrowser || prefersReducedMotion() || typeof a.animate !== 'function') return [];
  const ra = a.getBoundingClientRect();
  const rb = b.getBoundingClientRect();
  const dx = round(rb.left - ra.left);
  const dy = round(rb.top - ra.top);
  const { easing, duration } = springTiming(spring);
  return [
    a.animate([{ translate: `${dx}px ${dy}px`, scale: '0.97' }, { translate: '0px 0px', scale: '1' }], { duration, easing }),
    b.animate([{ translate: `${-dx}px ${-dy}px`, scale: '1.02' }, { translate: '0px 0px', scale: '1' }], { duration, easing }),
  ];
}

/* ---- Metric chart geometry ------------------------------------------------------------------- */

export interface MetricGeometry {
  ticks: Array<{ value: number; y: number }>;
  /** x and y of every point, in the plot's pixels. */
  points: Point[];
  line: string;
  area: string;
  baseline: number;
}

/** Padding around a metric chart's plot: room for the y ticks on the left and the x labels below. */
export const METRIC_PAD = { top: 12, right: 12, bottom: 26, left: 48 } as const;

/**
 * One series drawn into a `width` × `height` plot: round ticks, a monotone
 * line and its area. Every metric with the same number of values yields the
 * same path commands, so switching metrics can morph one line into the next.
 */
export function metricGeometry(values: readonly number[], width: number, height: number, pad: { top: number; right: number; bottom: number; left: number } = METRIC_PAD): MetricGeometry {
  const clean = values.map((v) => (Number.isFinite(v) ? v : 0));
  const ticks = niceTicks(Math.min(0, ...clean), Math.max(0, ...clean), 4);
  const bottom = height - pad.bottom;
  const y = linearScale([ticks[0]!, ticks[ticks.length - 1]!], [bottom, pad.top]);
  const x = linearScale([0, Math.max(1, clean.length - 1)], [pad.left, Math.max(pad.left + 1, width - pad.right)]);
  const points = clean.map((v, i) => [round(x(i)), round(y(v))] as Point);
  const baseline = y(ticks[0]!);
  return {
    ticks: ticks.map((value) => ({ value, y: round(y(value)) })),
    points,
    line: linePath(points, true),
    area: areaPath(points, baseline, true),
    baseline: round(baseline),
  };
}

/* ---- Status badge ---------------------------------------------------------------------------- */

/**
 * Size a status badge (.nx-status-badge) to its active layer by writing --_w,
 * so a status change morphs the pill's width instead of snapping it. Call it
 * once on mount (to pin the current width) and after every status change.
 */
export function fitStatusBadge(badge: HTMLElement | null): void {
  const layer = badge?.querySelector<HTMLElement>(`.nx-status-badge-layer[data-for="${badge.dataset.status ?? ''}"]`);
  if (!badge || !layer) return;
  const style = getComputedStyle(badge);
  const extra = [style.paddingInlineStart, style.paddingInlineEnd, style.borderInlineStartWidth, style.borderInlineEndWidth].reduce((sum, v) => sum + (parseFloat(v) || 0), 0);
  badge.style.setProperty('--_w', `${Math.ceil(layer.offsetWidth + extra)}px`);
}

/* ---- Tooltips over HTML plots ------------------------------------------------------------------ */

/**
 * Put a chart tooltip (.nx-chart-tooltip, positioned from --nx-tx / --nx-ty)
 * above `target`, kept inside `container`; below it when there is no room.
 */
export function placeChartTip(container: Element, target: Element, tip: HTMLElement, gap = 10): void {
  const c = container.getBoundingClientRect();
  const t = target.getBoundingClientRect();
  const width = tip.offsetWidth;
  const height = tip.offsetHeight;
  const x = Math.min(Math.max(t.left + t.width / 2 - c.left - width / 2, 0), Math.max(0, c.width - width));
  let y = t.top - c.top - height - gap;
  if (t.top - height - gap < 4) y = t.bottom - c.top + gap;
  tip.style.setProperty('--nx-tx', `${round(x)}px`);
  tip.style.setProperty('--nx-ty', `${round(y)}px`);
}

/* ---- Branch connector ----------------------------------------------------------------------------- */

export interface ConnectOptions {
  /** 0–1: how far each link's handles reach toward the other end. */
  curvature?: number;
}

type Box = { left: number; top: number; right: number; bottom: number; width: number; height: number };

const relative = (rect: DOMRect, origin: DOMRect): Box => ({
  left: rect.left - origin.left,
  top: rect.top - origin.top,
  right: rect.right - origin.left,
  bottom: rect.bottom - origin.top,
  width: rect.width,
  height: rect.height,
});

function linkBetween(s: Box, t: Box, curvature: number): string {
  const beside = t.left >= s.right - 1 || t.right <= s.left + 1;
  if (beside) {
    const after = t.left >= s.right - 1;
    return branchPath([after ? s.right : s.left, s.top + s.height / 2], [after ? t.left : t.right, t.top + t.height / 2], 'horizontal', curvature);
  }
  const below = t.top >= s.top;
  return branchPath([s.left + s.width / 2, below ? s.bottom : s.top], [t.left + t.width / 2, below ? t.top : t.bottom], 'vertical', curvature);
}

/**
 * Draw curved links from a source node to each target node, measured from the DOM:
 *
 *   <div>                                      (the container)
 *     <svg data-nx-links> <g data-nx-link><path/><path/></g> … </svg>
 *     <div data-nx-node="source">…</div>
 *     <div data-nx-node="target">…</div> …
 *
 * The i-th [data-nx-link] joins the source to the i-th target (every path in it
 * gets the same `d`: a track and a pulse, say). Where each target sits — after,
 * before or below the source — picks the edges its link leaves and enters by,
 * so a right-to-left page and a stacked phone layout both draw correctly. It
 * re-measures whenever anything resizes and marks the container
 * [data-nx-connected] once the links exist.
 */
export function connect(container: HTMLElement, { curvature = 0.55 }: ConnectOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  let frame = 0;

  const draw = () => {
    frame = 0;
    const svg = container.querySelector<SVGSVGElement>('[data-nx-links]');
    const source = container.querySelector<HTMLElement>('[data-nx-node="source"]');
    if (!svg || !source) return;
    const box = svg.getBoundingClientRect();
    if (!box.width || !box.height) return;
    svg.setAttribute('viewBox', `0 0 ${round(box.width)} ${round(box.height)}`);
    const s = relative(source.getBoundingClientRect(), box);
    const links = container.querySelectorAll<SVGElement>('[data-nx-link]');
    container.querySelectorAll<HTMLElement>('[data-nx-node="target"]').forEach((target, i) => {
      const link = links[i];
      if (!link) return;
      const d = linkBetween(s, relative(target.getBoundingClientRect(), box), curvature);
      const paths = link.tagName.toLowerCase() === 'path' ? [link] : Array.from(link.querySelectorAll('path'));
      for (const path of paths) path.setAttribute('d', d);
    });
    container.setAttribute('data-nx-connected', '');
  };

  const schedule = () => {
    if (!frame) frame = requestAnimationFrame(draw);
  };

  draw();
  const observer = 'ResizeObserver' in window ? new ResizeObserver(schedule) : null;
  observer?.observe(container);
  container.querySelectorAll('[data-nx-node]').forEach((node) => observer?.observe(node));
  window.addEventListener('resize', schedule);
  document.fonts?.ready.then(schedule).catch(() => {});

  return () => {
    cancelAnimationFrame(frame);
    observer?.disconnect();
    window.removeEventListener('resize', schedule);
  };
}

/* ---- Curved timeline ----------------------------------------------------------------------------- */

let viewTimelines: boolean | null = null;
const nativeScrollTimelines = () => {
  viewTimelines ??= isBrowser && typeof CSS !== 'undefined' && CSS.supports('(animation-timeline: view()) and (animation-range: entry)');
  return viewTimelines;
};

export interface CurvedTimelineOptions {
  /** How far the path swings to each side between milestones, px. */
  amplitude?: number;
}

/**
 * The curved timeline: a path through every milestone's dot that draws itself
 * as the list scrolls past 60% of the viewport, each milestone lighting up as
 * the line reaches it.
 *
 *   <div>                                  (el)
 *     <svg data-nx-curve-svg> <path data-nx-curve-path/> … </svg>
 *     <li data-nx-curve-item> … <span data-nx-curve-dot></span> … </li> …
 *
 * This measures the dots, writes the path's `d` and each item's --nx-at (how
 * far along the line it sits). Where CSS scroll-driven animations exist the
 * stylesheet does the drawing; elsewhere this writes the progress itself:
 * --nx-draw on `el` and --nx-reach (0 | 1) on each item. Reduced motion shows
 * the whole line at once.
 */
export function curvedTimeline(el: HTMLElement, { amplitude = 32 }: CurvedTimelineOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const native = nativeScrollTimelines();
  const LINE = 0.6;
  let stops: number[] = [];
  let list: HTMLElement[] = [];
  let layoutFrame = 0;
  let scrollFrame = 0;

  const progress = () => {
    scrollFrame = 0;
    if (native && !prefersReducedMotion()) return;
    const rect = el.getBoundingClientRect();
    const p = prefersReducedMotion() ? 1 : clamp01((window.innerHeight * LINE - rect.top) / (rect.height || 1));
    el.style.setProperty('--nx-draw', String(round(p, 4)));
    list.forEach((item, i) => item.style.setProperty('--nx-reach', p + 0.001 >= (stops[i] ?? 0) ? '1' : '0'));
  };

  const layout = () => {
    layoutFrame = 0;
    const svg = el.querySelector<SVGSVGElement>('[data-nx-curve-svg]');
    if (!svg) return;
    const box = svg.getBoundingClientRect();
    if (!box.width || !box.height) return;
    svg.setAttribute('viewBox', `0 0 ${round(box.width)} ${round(box.height)}`);
    list = Array.from(el.querySelectorAll<HTMLElement>('[data-nx-curve-item]'));
    const points = list.map((item) => {
      const r = (item.querySelector('[data-nx-curve-dot]') ?? item).getBoundingClientRect();
      return [r.left + r.width / 2 - box.left, r.top + r.height / 2 - box.top] as Point;
    });
    // Never swing further than the room beside the dots (a phone layout keeps them near the edge).
    const room = Math.min(...points.map(([x]) => Math.min(x, box.width - x))) - 4;
    const path = snakePath(points, { amplitude: Math.max(0, Math.min(amplitude, room)), dir: direction(el) });
    stops = path.stops;
    el.querySelectorAll('[data-nx-curve-path]').forEach((p) => p.setAttribute('d', path.d));
    list.forEach((item, i) => item.style.setProperty('--nx-at', String(stops[i] ?? 0)));
    el.setAttribute('data-nx-curved', '');
    progress();
  };

  const onLayout = () => {
    if (!layoutFrame) layoutFrame = requestAnimationFrame(layout);
  };
  const onScroll = () => {
    if (!scrollFrame) scrollFrame = requestAnimationFrame(progress);
  };

  layout();
  const observer = 'ResizeObserver' in window ? new ResizeObserver(onLayout) : null;
  observer?.observe(el);
  document.fonts?.ready.then(onLayout).catch(() => {});
  window.addEventListener('resize', onLayout);
  document.addEventListener('scroll', onScroll, { capture: true, passive: true });

  return () => {
    cancelAnimationFrame(layoutFrame);
    cancelAnimationFrame(scrollFrame);
    observer?.disconnect();
    window.removeEventListener('resize', onLayout);
    document.removeEventListener('scroll', onScroll, { capture: true });
  };
}
