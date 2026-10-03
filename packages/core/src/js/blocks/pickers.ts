/**
 * Pickers & inputs blocks: framework-agnostic behaviour.
 *
 * combobox · tag-input · context-menu · hover-card · bottom-sheet ·
 * date-range-picker · time-picker · number-field
 *
 * The pure functions (filtering, tag drafts, menu geometry, sheet snapping,
 * calendar months in any Intl calendar, clock values, stepping) are exported
 * for tests and for the React and Alpine layers, which share them. The DOM
 * behaviours follow the core convention: `(el, options) => cleanup`, writing
 * state as attributes and custom properties and leaving the motion to CSS.
 */
import { type Cleanup, direction, isBrowser } from '../env';
import { foldSearchText } from './menus';

/* ==== Words ================================================================================= */

/** The words the picker blocks say themselves; everything else arrives through props. */
export const pickerWords = {
  en: {
    menuHint: 'Right-click, long-press or press Shift+F10 for actions',
    actions: 'Actions',
    noMatches: 'No matches',
    loading: 'Searching…',
    clear: 'Clear',
    showOptions: 'Show options',
    remove: 'Remove {name}',
    tags: 'Tags',
    addTag: 'Add a tag…',
    duplicate: '{name} is already added',
    limit: 'Up to {max}',
    added: '{name} added',
    removed: '{name} removed',
    handle: 'Resize sheet',
    hour: 'Hour',
    minute: 'Minute',
    period: 'AM or PM',
    increase: 'Increase',
    decrease: 'Decrease',
    startDate: 'Start date',
    endDate: 'End date',
    pickRange: 'Pick a date range',
    presets: 'Presets',
    today: 'Today',
    yesterday: 'Yesterday',
    last7: 'Last 7 days',
    last30: 'Last 30 days',
    thisMonth: 'This month',
    lastMonth: 'Last month',
    nights: '{count} days',
    selectEnd: 'Now pick the end date',
  },
  fa: {
    menuHint: 'برای کارها راست‌کلیک کنید، انگشت را نگه دارید یا Shift+F10 بزنید',
    actions: 'کارها',
    noMatches: 'موردی پیدا نشد',
    loading: 'در حال جست‌وجو…',
    clear: 'پاک کردن',
    showOptions: 'نمایش گزینه‌ها',
    remove: 'حذف {name}',
    tags: 'برچسب‌ها',
    addTag: 'یک برچسب بنویسید…',
    duplicate: '«{name}» از قبل اضافه شده',
    limit: 'حداکثر {max}',
    added: '«{name}» اضافه شد',
    removed: '«{name}» حذف شد',
    handle: 'تغییر اندازهٔ برگه',
    hour: 'ساعت',
    minute: 'دقیقه',
    period: 'قبل یا بعد از ظهر',
    increase: 'افزایش',
    decrease: 'کاهش',
    startDate: 'تاریخ شروع',
    endDate: 'تاریخ پایان',
    pickRange: 'بازهٔ تاریخ را انتخاب کنید',
    presets: 'بازه‌های آماده',
    today: 'امروز',
    yesterday: 'دیروز',
    last7: '۷ روز گذشته',
    last30: '۳۰ روز گذشته',
    thisMonth: 'این ماه',
    lastMonth: 'ماه گذشته',
    nights: '{count} روز',
    selectEnd: 'حالا تاریخ پایان را انتخاب کنید',
  },
  ar: {
    menuHint: 'انقر بالزر الأيمن أو اضغط مطولًا أو Shift+F10 للإجراءات',
    actions: 'الإجراءات',
    noMatches: 'لا نتائج',
    loading: 'جارٍ البحث…',
    clear: 'مسح',
    showOptions: 'عرض الخيارات',
    remove: 'إزالة {name}',
    tags: 'الوسوم',
    addTag: 'أضف وسمًا…',
    duplicate: '«{name}» مضاف بالفعل',
    limit: 'حتى {max}',
    added: 'أُضيف «{name}»',
    removed: 'أُزيل «{name}»',
    handle: 'تغيير حجم اللوحة',
    hour: 'الساعة',
    minute: 'الدقيقة',
    period: 'صباحًا أو مساءً',
    increase: 'زيادة',
    decrease: 'إنقاص',
    startDate: 'تاريخ البدء',
    endDate: 'تاريخ الانتهاء',
    pickRange: 'اختر نطاقًا زمنيًا',
    presets: 'نطاقات جاهزة',
    today: 'اليوم',
    yesterday: 'أمس',
    last7: 'آخر 7 أيام',
    last30: 'آخر 30 يومًا',
    thisMonth: 'هذا الشهر',
    lastMonth: 'الشهر الماضي',
    nights: '{count} أيام',
    selectEnd: 'اختر الآن تاريخ الانتهاء',
  },
} as const;

export type PickerWord = keyof (typeof pickerWords)['en'];

/** A picker word in `locale` ("fa-IR" → fa), with {params} filled in. */
export function pickerWord(locale: string | undefined, key: PickerWord, params: Record<string, string | number> = {}): string {
  const lang = (locale ?? 'en').slice(0, 2).toLowerCase();
  const table = (pickerWords as Record<string, Record<PickerWord, string>>)[lang] ?? pickerWords.en;
  return (table[key] ?? pickerWords.en[key]).replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`));
}

/* ==== Combobox =============================================================================== */

export interface PickerOption {
  value: string;
  label: string;
  description?: string;
  /** Extra words that should find this option ("NYC" for New York). */
  keywords?: readonly string[];
  disabled?: boolean;
}

/** Indexes of the options that match `query` (all of them when the query is empty). */
export function comboMatches(options: readonly PickerOption[], query: string): number[] {
  const q = foldSearchText(query.trim());
  const all = options.map((_, i) => i);
  if (!q) return all;
  const words = q.split(/\s+/);
  const scored: Array<[number, number]> = [];
  options.forEach((option, i) => {
    const label = foldSearchText(option.label);
    const hay = `${label} ${foldSearchText(option.description ?? '')} ${foldSearchText((option.keywords ?? []).join(' '))} ${foldSearchText(option.value)}`;
    if (!words.every((w) => hay.includes(w))) return;
    // Labels that start with the query come first, then a word start, then anything else.
    const rank = label.startsWith(q) ? 0 : new RegExp(`(^|\\s)${escapeRegExp(words[0]!)}`).test(label) ? 1 : 2;
    scored.push([i, rank]);
  });
  return scored.sort((a, b) => a[1] - b[1] || a[0] - b[0]).map(([i]) => i);
}

const escapeRegExp = (s: string) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

/** Length-preserving fold for highlighting: lower case and the Arabic/Persian letter variants. */
function foldChar(char: string): string {
  if (char === 'ي' || char === 'ى') return 'ی';
  if (char === 'ك') return 'ک';
  const lower = char.toLocaleLowerCase();
  return lower.length === char.length ? lower : char;
}

export interface TextSegment {
  text: string;
  match: boolean;
}

/**
 * Split `text` into runs that do and do not match `query`, for the bold match
 * in a listbox. Every occurrence of every query word is marked.
 */
export function comboSegments(text: string, query: string): TextSegment[] {
  const words = query.trim().split(/\s+/).filter(Boolean).map((w) => Array.from(w).map(foldChar).join(''));
  if (!words.length) return [{ text, match: false }];
  const chars = Array.from(text);
  const folded = chars.map(foldChar);
  const marks = new Array<boolean>(chars.length).fill(false);
  for (const word of words) {
    const w = Array.from(word);
    for (let i = 0; i + w.length <= folded.length; i++) {
      let hit = true;
      for (let j = 0; j < w.length; j++) {
        if (folded[i + j] !== w[j]) {
          hit = false;
          break;
        }
      }
      if (hit) for (let j = 0; j < w.length; j++) marks[i + j] = true;
    }
  }
  const out: TextSegment[] = [];
  chars.forEach((char, i) => {
    const last = out[out.length - 1];
    if (last && last.match === marks[i]) last.text += char;
    else out.push({ text: char, match: marks[i]! });
  });
  return out;
}

/** The next enabled index in `visible` from `active`, one step either way, wrapping. */
export function comboStep(visible: readonly number[], active: number, step: 1 | -1, disabled: (i: number) => boolean = () => false): number {
  if (!visible.length) return -1;
  let at = visible.indexOf(active);
  if (at < 0) at = step > 0 ? -1 : visible.length;
  for (let n = 0; n < visible.length; n++) {
    at = (at + step + visible.length) % visible.length;
    if (!disabled(visible[at]!)) return visible[at]!;
  }
  return -1;
}

/** The first (or last) enabled index of `visible`. */
export function comboEdge(visible: readonly number[], edge: 'first' | 'last', disabled: (i: number) => boolean = () => false): number {
  const list = edge === 'first' ? visible : [...visible].reverse();
  return list.find((i) => !disabled(i)) ?? -1;
}

/* ==== Tag input ============================================================================== */

/** How tags compare: case- and letter-variant-insensitive, trimmed. */
export function tagKey(tag: string): string {
  return foldSearchText(tag.trim().replace(/\s+/g, ' '));
}

export interface TagDraftOptions {
  /** Separators that commit a tag while typing or pasting (comma, Persian comma, newline by default). */
  separators?: readonly string[];
  max?: number | null;
  /** Longest tag kept, in characters. */
  maxLength?: number;
}

export interface TagDraftResult {
  /** New tags to append, in order. */
  added: string[];
  /** Text that stays in the input (after the last separator). */
  rest: string;
  /** Tags that were already there (or typed twice). */
  duplicates: string[];
  /** Tags dropped because the limit was reached. */
  overflow: string[];
}

/**
 * Turn typed (or pasted) text into tags. With `commit` the trailing text is a
 * tag too (Enter); without it only text before a separator is (typing a comma).
 */
export function tagDraft(text: string, existing: readonly string[], commit = false, { separators = [',', '،', '\n', ';'], max = null, maxLength = 64 }: TagDraftOptions = {}): TagDraftResult {
  const pattern = new RegExp(`[${separators.map(escapeRegExp).join('')}]`);
  const pieces = text.split(pattern);
  const rest = commit ? '' : pieces.pop() ?? '';
  const seen = new Set(existing.map(tagKey));
  const added: string[] = [];
  const duplicates: string[] = [];
  const overflow: string[] = [];
  for (const raw of pieces) {
    const tag = raw.trim().replace(/\s+/g, ' ').slice(0, maxLength);
    if (!tag) continue;
    const key = tagKey(tag);
    if (seen.has(key)) {
      duplicates.push(tag);
      continue;
    }
    if (max != null && existing.length + added.length >= max) {
      overflow.push(tag);
      continue;
    }
    seen.add(key);
    added.push(tag);
  }
  return { added, rest, duplicates, overflow };
}

/** The index of `tag` among `tags` by tagKey, or -1. */
export function tagIndex(tags: readonly string[], tag: string): number {
  const key = tagKey(tag);
  return tags.findIndex((t) => tagKey(t) === key);
}

/* ==== Context menu =========================================================================== */

export interface MenuPoint {
  x: number;
  y: number;
  /** transform-origin inside the menu, so it scales out of the pointer. */
  originX: number;
  originY: number;
}

/**
 * Where a menu of `size` opens for a pointer at `point`: its reading-start
 * corner at the pointer, flipped to the other side of the pointer when it
 * would leave the viewport, and finally clamped inside it.
 */
export function ctxMenuPoint(
  point: { x: number; y: number },
  size: { width: number; height: number },
  viewport: { width: number; height: number },
  padding = 8,
  dir: 1 | -1 = 1,
): MenuPoint {
  let x = dir === 1 ? point.x : point.x - size.width;
  if (dir === 1 && x + size.width > viewport.width - padding) x = point.x - size.width;
  if (dir === -1 && x < padding) x = point.x;
  let y = point.y;
  if (y + size.height > viewport.height - padding) y = point.y - size.height;
  x = Math.min(Math.max(x, padding), Math.max(padding, viewport.width - size.width - padding));
  y = Math.min(Math.max(y, padding), Math.max(padding, viewport.height - size.height - padding));
  return {
    x: Math.round(x),
    y: Math.round(y),
    originX: Math.round(Math.min(Math.max(point.x - x, 0), size.width)),
    originY: Math.round(Math.min(Math.max(point.y - y, 0), size.height)),
  };
}

/** Open the menu `menu` (position: fixed) at a client point: writes left/top and --nx-origin. */
export function placeAtPoint(menu: HTMLElement, point: { x: number; y: number }, padding = 8): MenuPoint {
  const size = { width: menu.offsetWidth, height: menu.offsetHeight };
  const viewport = { width: document.documentElement.clientWidth, height: window.innerHeight };
  const at = ctxMenuPoint(point, size, viewport, padding, direction(menu));
  menu.style.position = 'fixed';
  menu.style.left = `${at.x}px`;
  menu.style.top = `${at.y}px`;
  menu.style.right = 'auto';
  menu.style.bottom = 'auto';
  menu.style.setProperty('--nx-origin', `${at.originX}px ${at.originY}px`);
  return at;
}

export interface ContextTriggerOptions {
  /** Called with the client point the menu should open at. */
  onOpen: (point: { x: number; y: number }, event: Event) => void;
  /** Long-press time on touch, ms. */
  delay?: number;
  /** How far a finger may wander before it is a scroll, px. */
  tolerance?: number;
}

/**
 * The ways a context menu is asked for on `area`: right click, a long press
 * on touch, and Shift+F10 / the ContextMenu key (opening at the focused
 * element's corner). The area should be focusable (tabindex="0") so the
 * keyboard can reach it.
 */
export function ctxMenuTrigger(area: HTMLElement, { onOpen, delay = 500, tolerance = 10 }: ContextTriggerOptions): Cleanup {
  if (!isBrowser) return () => {};
  let timer: ReturnType<typeof setTimeout> | undefined;
  let start: { x: number; y: number; id: number } | null = null;
  let pressed = false;

  const onContext = (event: MouseEvent) => {
    if (pressed) {
      event.preventDefault();
      return;
    }
    event.preventDefault();
    // A keyboard-raised contextmenu event has no pointer position.
    if (event.clientX === 0 && event.clientY === 0 && event.detail === 0) onOpen(cornerOf(event.target as Element, area), event);
    else onOpen({ x: event.clientX, y: event.clientY }, event);
  };

  const cancel = () => {
    clearTimeout(timer);
    timer = undefined;
    start = null;
    area.removeAttribute('data-pressing');
  };

  const onDown = (event: PointerEvent) => {
    if (event.pointerType !== 'touch' || start) return;
    pressed = false;
    start = { x: event.clientX, y: event.clientY, id: event.pointerId };
    area.setAttribute('data-pressing', '');
    timer = setTimeout(() => {
      if (!start) return;
      pressed = true;
      const point = { x: start.x, y: start.y };
      cancel();
      navigator.vibrate?.(10);
      onOpen(point, event);
    }, delay);
  };

  const onMove = (event: PointerEvent) => {
    if (!start || event.pointerId !== start.id) return;
    if (Math.hypot(event.clientX - start.x, event.clientY - start.y) > tolerance) cancel();
  };

  const onUp = (event: PointerEvent) => {
    if (start && event.pointerId === start.id) cancel();
  };

  const onKey = (event: KeyboardEvent) => {
    if ((event.key === 'F10' && event.shiftKey) || event.key === 'ContextMenu') {
      event.preventDefault();
      onOpen(cornerOf(event.target as Element, area), event);
    }
  };

  // A long press must not also select text or fire a click.
  const onClick = (event: MouseEvent) => {
    if (pressed) {
      event.preventDefault();
      event.stopPropagation();
      pressed = false;
    }
  };

  area.addEventListener('contextmenu', onContext);
  area.addEventListener('pointerdown', onDown);
  area.addEventListener('pointermove', onMove);
  area.addEventListener('pointerup', onUp);
  area.addEventListener('pointercancel', onUp);
  area.addEventListener('keydown', onKey);
  area.addEventListener('click', onClick, true);
  return () => {
    cancel();
    area.removeEventListener('contextmenu', onContext);
    area.removeEventListener('pointerdown', onDown);
    area.removeEventListener('pointermove', onMove);
    area.removeEventListener('pointerup', onUp);
    area.removeEventListener('pointercancel', onUp);
    area.removeEventListener('keydown', onKey);
    area.removeEventListener('click', onClick, true);
  };
}

/** The reading-start bottom corner of the focused element (or the area), for keyboard opening. */
function cornerOf(target: Element | null, area: HTMLElement): { x: number; y: number } {
  const el = target && area.contains(target) ? target : area;
  const r = el.getBoundingClientRect();
  const rtl = direction(area) === -1;
  return { x: Math.round(rtl ? r.right - 8 : r.left + 8), y: Math.round(Math.min(r.bottom, r.top + 32)) };
}

/* ==== Hover card ============================================================================= */

export interface HoverCardOptions {
  openDelay?: number;
  closeDelay?: number;
  onOpenChange: (open: boolean) => void;
}

export interface HoverCardController {
  /** Open or close now (cancels any pending delay). */
  set: (open: boolean) => void;
  destroy: Cleanup;
}

/**
 * Open-intent for a hover card: opens after `openDelay` while the pointer
 * rests on `trigger` (or it has keyboard focus), and stays open while the
 * pointer is anywhere inside `card` — the gap between them is bridged by
 * `closeDelay`. Touch never opens it (the trigger is usually a link). Escape
 * closes it.
 */
export function hoverCard(trigger: HTMLElement, card: HTMLElement, { openDelay = 500, closeDelay = 250, onOpenChange }: HoverCardOptions): HoverCardController {
  if (!isBrowser) return { set: () => {}, destroy: () => {} };
  let timer: ReturnType<typeof setTimeout> | undefined;
  let open = false;

  const set = (next: boolean) => {
    clearTimeout(timer);
    timer = undefined;
    if (next === open) return;
    open = next;
    onOpenChange(next);
  };
  const later = (next: boolean, ms: number) => {
    clearTimeout(timer);
    if (next === open) return;
    timer = setTimeout(() => set(next), ms);
  };

  const enter = (event: PointerEvent) => {
    if (event.pointerType === 'touch') return;
    later(true, open ? 0 : openDelay);
  };
  const leave = (event: PointerEvent) => {
    if (event.pointerType === 'touch') return;
    const to = event.relatedTarget as Node | null;
    if (to && (trigger.contains(to) || card.contains(to))) return;
    if (card.contains(document.activeElement) || trigger.matches(':focus-visible')) return;
    later(false, closeDelay);
  };
  const cardEnter = () => {
    if (open) clearTimeout(timer);
  };
  const focusIn = () => {
    if (trigger.matches(':focus-visible') || trigger.querySelector(':focus-visible')) later(true, Math.min(openDelay, 300));
  };
  const focusOut = (event: FocusEvent) => {
    const to = event.relatedTarget as Node | null;
    if (to && (trigger.contains(to) || card.contains(to))) return;
    later(false, closeDelay);
  };
  const onKey = (event: KeyboardEvent) => {
    if (event.key === 'Escape' && open) {
      event.stopPropagation();
      set(false);
      if (card.contains(document.activeElement)) (trigger.querySelector<HTMLElement>('a, button, [tabindex]') ?? trigger).focus();
    }
  };

  trigger.addEventListener('pointerenter', enter);
  trigger.addEventListener('pointerleave', leave);
  card.addEventListener('pointerenter', cardEnter);
  card.addEventListener('pointerleave', leave);
  trigger.addEventListener('focusin', focusIn);
  trigger.addEventListener('focusout', focusOut);
  card.addEventListener('focusout', focusOut);
  document.addEventListener('keydown', onKey);

  return {
    set,
    destroy() {
      clearTimeout(timer);
      trigger.removeEventListener('pointerenter', enter);
      trigger.removeEventListener('pointerleave', leave);
      card.removeEventListener('pointerenter', cardEnter);
      card.removeEventListener('pointerleave', leave);
      trigger.removeEventListener('focusin', focusIn);
      trigger.removeEventListener('focusout', focusOut);
      card.removeEventListener('focusout', focusOut);
      document.removeEventListener('keydown', onKey);
    },
  };
}

/* ==== Bottom sheet =========================================================================== */

/**
 * Rubber-band damping: past a bound, travel slows the further you pull
 * (iOS-style). `overshoot` px of pull becomes at most `limit` px of travel.
 */
export function sheetRubber(overshoot: number, limit: number, constant = 0.55): number {
  if (overshoot <= 0 || limit <= 0) return 0;
  return (1 - 1 / ((overshoot * constant) / limit + 1)) * limit;
}

export interface SheetReleaseInput {
  /** The sheet's visible height right now, px. */
  height: number;
  /** Snap heights, px, ascending. */
  snaps: readonly number[];
  /** Release velocity, px/ms, positive = moving down (closing). */
  velocity: number;
  /** Allow closing by dragging below the lowest snap. */
  dismissible?: boolean;
  /** Flick speed that always moves one snap (or dismisses), px/ms. */
  flick?: number;
}

/**
 * Where a released sheet goes: the snap index, or -1 to dismiss. A flick
 * (faster than `flick`) moves one snap in its direction even when the drag
 * was short; a slow release settles on the nearest snap, projected a little
 * along the motion so it feels thrown.
 */
export function sheetRelease({ height, snaps, velocity, dismissible = true, flick = 0.11 }: SheetReleaseInput): number {
  if (!snaps.length) return -1;
  const nearest = (h: number) => snaps.reduce((best, s, i) => (Math.abs(s - h) < Math.abs(snaps[best]! - h) ? i : best), 0);
  if (Math.abs(velocity) >= flick) {
    if (velocity > 0) {
      // Downward flick: the next snap below the current height, or dismiss.
      for (let i = snaps.length - 1; i >= 0; i--) if (snaps[i]! < height - 1) return i;
      return dismissible ? -1 : 0;
    }
    for (let i = 0; i < snaps.length; i++) if (snaps[i]! > height + 1) return i;
    return snaps.length - 1;
  }
  const projected = height - velocity * 120;
  if (dismissible && projected < snaps[0]! * 0.5) return -1;
  return nearest(projected);
}

export interface BottomSheetOptions {
  /** Snap points as fractions of the viewport height (0–1), ascending. */
  snaps?: readonly number[];
  /** Index of the snap the sheet opens at. */
  initial?: number;
  dismissible?: boolean;
  /** Where a drag may start; defaults to the handle and the header. */
  handle?: string;
  onSnap?: (index: number) => void;
  onDismiss?: () => void;
}

export interface BottomSheetController {
  snapTo: (index: number) => void;
  /** Measure again (after the viewport changed). */
  refresh: () => void;
  readonly index: number;
  destroy: Cleanup;
}

/**
 * Drag, snap and flick for a bottom sheet (`sheet` is the panel, usually a
 * native <dialog>). The sheet is as tall as its highest snap; the visible
 * height is a translate written to --nx-sheet-y (px), with [data-dragging]
 * while a finger holds it (CSS drops the transition then) and data-snap with
 * the resting index. Past the highest snap the pull is rubber-banded; below
 * the lowest it follows the finger, and a release there — or a downward flick
 * faster than 0.11 px/ms — calls `onDismiss`.
 */
export function bottomSheet(sheet: HTMLElement, { snaps = [0.5, 0.9], initial = 0, dismissible = true, handle = '[data-sheet-handle], .nx-bsheet-head', onSnap, onDismiss }: BottomSheetOptions = {}): BottomSheetController {
  const sorted = [...snaps].sort((a, b) => a - b);
  let index = Math.max(0, Math.min(initial, sorted.length - 1));
  if (!isBrowser) return { snapTo: () => {}, refresh: () => {}, index, destroy: () => {} };

  let viewport = window.innerHeight;
  const heights = () => sorted.map((f) => Math.round(f * viewport));
  const full = () => heights()[heights().length - 1]!;

  const write = (visible: number) => {
    sheet.style.setProperty('--nx-sheet-full', `${full()}px`);
    sheet.style.setProperty('--nx-sheet-y', `${Math.round(full() - visible)}px`);
  };

  const snapTo = (next: number) => {
    index = Math.max(0, Math.min(next, sorted.length - 1));
    sheet.setAttribute('data-snap', String(index));
    write(heights()[index]!);
    onSnap?.(index);
  };

  let drag: { id: number; y: number; start: number; samples: Array<[number, number]> } | null = null;
  let current = 0;

  const onDown = (event: PointerEvent) => {
    if (drag || event.button !== 0 || !(event.target as Element).closest(handle)) return;
    if ((event.target as Element).closest('button, a, input, textarea, select')) return;
    drag = { id: event.pointerId, y: event.clientY, start: heights()[index]!, samples: [[performance.now(), event.clientY]] };
    current = drag.start;
    (event.target as Element).setPointerCapture?.(event.pointerId);
    sheet.setAttribute('data-dragging', '');
  };

  const onMove = (event: PointerEvent) => {
    if (!drag || event.pointerId !== drag.id) return;
    event.preventDefault();
    const raw = drag.start + (drag.y - event.clientY);
    const top = full();
    const bottom = dismissible ? 0 : heights()[0]!;
    if (raw > top) current = top + sheetRubber(raw - top, 48);
    else if (raw < bottom) current = bottom - sheetRubber(bottom - raw, 48);
    else current = raw;
    // Over the top the sheet grows past its height: let it by translating negative.
    write(current);
    drag.samples.push([performance.now(), event.clientY]);
    if (drag.samples.length > 6) drag.samples.shift();
  };

  const onUp = (event: PointerEvent) => {
    if (!drag || event.pointerId !== drag.id) return;
    const [t0, y0] = drag.samples[0]!;
    const [t1, y1] = drag.samples[drag.samples.length - 1]!;
    const velocity = (y1 - y0) / Math.max(1, t1 - t0);
    drag = null;
    sheet.removeAttribute('data-dragging');
    const target = sheetRelease({ height: current, snaps: heights(), velocity, dismissible });
    if (target < 0) {
      onDismiss?.();
      return;
    }
    snapTo(target);
  };

  const onResize = () => {
    viewport = window.innerHeight;
    if (!drag) write(heights()[index]!);
  };

  sheet.addEventListener('pointerdown', onDown);
  sheet.addEventListener('pointermove', onMove);
  sheet.addEventListener('pointerup', onUp);
  sheet.addEventListener('pointercancel', onUp);
  window.addEventListener('resize', onResize);
  sheet.setAttribute('data-snap', String(index));
  write(heights()[index]!);

  return {
    snapTo,
    refresh: onResize,
    get index() {
      return index;
    },
    destroy() {
      sheet.removeEventListener('pointerdown', onDown);
      sheet.removeEventListener('pointermove', onMove);
      sheet.removeEventListener('pointerup', onUp);
      sheet.removeEventListener('pointercancel', onUp);
      window.removeEventListener('resize', onResize);
      sheet.removeAttribute('data-dragging');
    },
  };
}

/* ==== Dates (any Intl calendar) ============================================================== */

/** The calendars the range picker draws: the Gregorian and the Solar Hijri (Jalali). */
export type PickerCalendar = 'gregory' | 'persian';

const DAY_MS = 86_400_000;
export interface DateRange {
  start: string | null;
  end: string | null;
}

/** "YYYY-MM-DD" of a UTC timestamp. */
export const isoOfTime = (time: number) => new Date(time).toISOString().slice(0, 10);
const timeOfIso = (iso: string) => Date.parse(`${iso}T00:00:00Z`);

/** Today's local date as "YYYY-MM-DD". */
export function isoToday(now: Date = new Date()): string {
  return isoOfTime(Date.UTC(now.getFullYear(), now.getMonth(), now.getDate()));
}

export function isoAddDays(iso: string, days: number): string {
  return isoOfTime(timeOfIso(iso) + days * DAY_MS);
}

/** Whole days from `a` to `b` (negative when b is earlier). */
export function isoDaysBetween(a: string, b: string): number {
  return Math.round((timeOfIso(b) - timeOfIso(a)) / DAY_MS);
}

/** The calendar a locale reads dates in by default: Persian for fa, Gregorian elsewhere. */
export function defaultCalendar(locale?: string): PickerCalendar {
  return (locale ?? '').toLowerCase().startsWith('fa') ? 'persian' : 'gregory';
}

/** The first day of the week a locale expects: Saturday for fa/ar, Monday for most of Europe, else Sunday. */
export function defaultWeekStart(locale?: string): 0 | 1 | 6 {
  const lang = (locale ?? '').slice(0, 2).toLowerCase();
  if (lang === 'fa' || lang === 'ar') return 6;
  if (['de', 'fr', 'es', 'it', 'nl', 'pt', 'ru', 'tr', 'pl', 'sv'].includes(lang)) return 1;
  return 0;
}

const partsCache = new Map<string, Intl.DateTimeFormat>();

/** Year, month (1-based) and day of `iso` in `calendar`. */
export function calendarParts(iso: string, calendar: PickerCalendar = 'gregory'): { year: number; month: number; day: number } {
  if (calendar === 'gregory') {
    const [y, m, d] = iso.split('-').map(Number);
    return { year: y!, month: m!, day: d! };
  }
  let format = partsCache.get(calendar);
  if (!format) {
    format = new Intl.DateTimeFormat(`en-u-ca-${calendar}-nu-latn`, { year: 'numeric', month: 'numeric', day: 'numeric', timeZone: 'UTC' });
    partsCache.set(calendar, format);
  }
  const parts = format.formatToParts(new Date(timeOfIso(iso)));
  const pick = (type: string) => Number(parts.find((p) => p.type === type)?.value ?? Number.NaN);
  return { year: pick('year'), month: pick('month'), day: pick('day') };
}

/** The ISO day the calendar month containing `iso` starts on. */
export function monthStartOf(iso: string, calendar: PickerCalendar = 'gregory'): string {
  return isoAddDays(iso, 1 - calendarParts(iso, calendar).day);
}

/** How many days the calendar month starting at `start` has. */
export function calendarMonthLength(start: string, calendar: PickerCalendar = 'gregory'): number {
  for (let n = 28; n <= 32; n++) if (calendarParts(isoAddDays(start, n), calendar).day === 1) return n;
  return 30;
}

/** The first day of the month `step` months away from the one containing `iso`. */
export function shiftCalendarMonth(iso: string, step: number, calendar: PickerCalendar = 'gregory'): string {
  let start = monthStartOf(iso, calendar);
  for (let i = 0; i < Math.abs(step); i++) {
    start = step > 0 ? isoAddDays(start, calendarMonthLength(start, calendar)) : monthStartOf(isoAddDays(start, -1), calendar);
  }
  return start;
}

/**
 * The weeks of the calendar month containing `iso`, as ISO days, with null
 * for the cells before the first and after the last day (a range picker shows
 * two months side by side, so neither repeats the other's days).
 */
export function rangeMonthWeeks(iso: string, calendar: PickerCalendar = 'gregory', weekStart = 0): Array<Array<string | null>> {
  const start = monthStartOf(iso, calendar);
  const length = calendarMonthLength(start, calendar);
  const offset = (new Date(timeOfIso(start)).getUTCDay() - weekStart + 7) % 7;
  const cells: Array<string | null> = Array.from({ length: offset }, () => null);
  for (let i = 0; i < length; i++) cells.push(isoAddDays(start, i));
  while (cells.length % 7) cells.push(null);
  const weeks: Array<Array<string | null>> = [];
  for (let i = 0; i < cells.length; i += 7) weeks.push(cells.slice(i, i + 7));
  return weeks;
}

/** Weekday names (short) starting at `weekStart`, in `locale`. */
export function pickerWeekdays(locale?: string, weekStart = 0, style: 'narrow' | 'short' | 'long' = 'short'): string[] {
  const format = new Intl.DateTimeFormat(locale, { weekday: style, timeZone: 'UTC' });
  const sunday = Date.UTC(2026, 0, 4);
  return Array.from({ length: 7 }, (_, i) => format.format(new Date(sunday + ((weekStart + i) % 7) * DAY_MS)));
}

/** A formatter for `iso` days in a locale and calendar ("شهریور ۱۴۰۵", "Sep 3"). */
export function dayFormatter(locale: string | undefined, calendar: PickerCalendar, options: Intl.DateTimeFormatOptions): (iso: string) => string {
  const format = new Intl.DateTimeFormat(locale, { ...options, calendar, timeZone: 'UTC' });
  return (iso: string) => format.format(new Date(timeOfIso(iso)));
}

/** The range with its ends in order. */
export function orderRange(a: string, b: string): [string, string] {
  return a <= b ? [a, b] : [b, a];
}

export type RangeMark = 'start' | 'end' | 'single' | 'middle' | null;

/** Where `iso` sits in the range `[start, end]` (end may be the hovered preview day). */
export function rangeMark(iso: string, start: string | null, end: string | null): RangeMark {
  if (!start) return null;
  if (!end || end === start) return iso === start ? 'single' : null;
  const [a, b] = orderRange(start, end);
  if (iso === a) return 'start';
  if (iso === b) return 'end';
  return iso > a && iso < b ? 'middle' : null;
}

/**
 * The next state of a range after clicking `iso`: the first click starts a
 * new range, the second closes it (in either order); a third starts over.
 */
export function rangeClick(range: DateRange, iso: string): DateRange {
  if (!range.start || range.end) return { start: iso, end: null };
  const [start, end] = orderRange(range.start, iso);
  return { start, end };
}

export type RangePresetId = 'today' | 'yesterday' | 'last7' | 'last30' | 'thisMonth' | 'lastMonth';

/** The range a preset stands for, relative to `today`, in `calendar`'s months. */
export function rangePreset(id: RangePresetId, today: string, calendar: PickerCalendar = 'gregory'): DateRange {
  switch (id) {
    case 'today': return { start: today, end: today };
    case 'yesterday': { const y = isoAddDays(today, -1); return { start: y, end: y }; }
    case 'last7': return { start: isoAddDays(today, -6), end: today };
    case 'last30': return { start: isoAddDays(today, -29), end: today };
    case 'thisMonth': return { start: monthStartOf(today, calendar), end: today };
    case 'lastMonth': {
      const start = shiftCalendarMonth(today, -1, calendar);
      return { start, end: isoAddDays(start, calendarMonthLength(start, calendar) - 1) };
    }
  }
}

/* ==== Time =================================================================================== */

export interface ClockValue {
  hour: number;
  minute: number;
}

/** "HH:MM" (24-hour) → hour and minute, or null. Accepts locale digits. */
export function parseClock(text: string | null | undefined): ClockValue | null {
  const s = String(text ?? '')
    .replace(/[۰-۹]/g, (d) => String(d.charCodeAt(0) - 0x06f0))
    .replace(/[٠-٩]/g, (d) => String(d.charCodeAt(0) - 0x0660));
  const m = /^\s*(\d{1,2})\s*[:٫.]\s*(\d{1,2})/.exec(s);
  if (!m) return null;
  const hour = Number(m[1]);
  const minute = Number(m[2]);
  if (hour > 23 || minute > 59) return null;
  return { hour, minute };
}

/** hour and minute → "HH:MM". */
export function clockText({ hour, minute }: ClockValue): string {
  return `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
}

/** 24-hour → 12-hour face: hour 1–12 and the period. */
export function to12h(hour: number): { hour: number; pm: boolean } {
  return { hour: hour % 12 === 0 ? 12 : hour % 12, pm: hour >= 12 };
}

/** 12-hour face → 24-hour. */
export function from12h(hour: number, pm: boolean): number {
  return (hour % 12) + (pm ? 12 : 0);
}

/** The minutes a wheel offers for a step (5 → 0, 5, … 55). */
export function minuteStops(step = 1): number[] {
  const s = Math.max(1, Math.min(30, Math.round(step)));
  return Array.from({ length: Math.ceil(60 / s) }, (_, i) => i * s);
}

/** The minute stop nearest to `minute`. */
export function nearestMinute(minute: number, step = 1): number {
  const stops = minuteStops(step);
  return stops.reduce((best, s) => (Math.abs(s - minute) < Math.abs(best - minute) ? s : best), stops[0]!);
}

/** The locale's names for the two halves of the day ("AM"/"PM", "ق.ظ."/"ب.ظ."). */
export function dayPeriods(locale?: string): [string, string] {
  const format = new Intl.DateTimeFormat(locale, { hour: 'numeric', hour12: true, timeZone: 'UTC' });
  const pick = (h: number) => format.formatToParts(new Date(Date.UTC(2026, 0, 1, h))).find((p) => p.type === 'dayPeriod')?.value ?? (h < 12 ? 'AM' : 'PM');
  return [pick(9), pick(15)];
}

/** Whether a locale reads the clock in 12 hours by default. */
export function prefers12h(locale?: string): boolean {
  return new Intl.DateTimeFormat(locale, { hour: 'numeric' }).resolvedOptions().hour12 === true;
}

export interface WheelOptions {
  /** Called with the index that settled in the middle after a scroll. */
  onSelect: (index: number) => void;
}

/**
 * A scroll-snap wheel: `list` scrolls vertically with one option per row
 * (all the same height); when scrolling settles the centred row is reported.
 * Items carry their distance from the centre as --nx-wheel-d (for the
 * curved fade), updated per frame while scrolling.
 */
export function snapWheel(list: HTMLElement, { onSelect }: WheelOptions): Cleanup {
  if (!isBrowser) return () => {};
  let frame = 0;
  let settle: ReturnType<typeof setTimeout> | undefined;
  const rowHeight = () => (list.firstElementChild as HTMLElement | null)?.offsetHeight || 36;

  const paint = () => {
    frame = 0;
    const h = rowHeight();
    const centre = list.scrollTop / h;
    Array.from(list.children).forEach((child, i) => (child as HTMLElement).style.setProperty('--nx-wheel-d', Math.min(3, Math.abs(i - centre)).toFixed(2)));
  };
  const report = () => {
    const i = Math.round(list.scrollTop / rowHeight());
    onSelect(Math.max(0, Math.min(i, list.children.length - 1)));
  };
  const onScroll = () => {
    if (!frame) frame = requestAnimationFrame(paint);
    clearTimeout(settle);
    settle = setTimeout(report, 120);
  };
  list.addEventListener('scroll', onScroll, { passive: true });
  paint();
  return () => {
    cancelAnimationFrame(frame);
    clearTimeout(settle);
    list.removeEventListener('scroll', onScroll);
  };
}

/** Scroll a wheel so row `index` is centred. */
export function wheelTo(list: HTMLElement, index: number, smooth = true): void {
  const row = list.children[index] as HTMLElement | undefined;
  if (!row) return;
  const top = index * row.offsetHeight;
  if (Math.abs(list.scrollTop - top) < 1) return;
  list.scrollTo({ top, behavior: smooth ? 'smooth' : 'instant' as ScrollBehavior });
}

/* ==== Numbers ================================================================================ */

/** Decimal places a step implies (0.25 → 2). */
export function stepDecimals(step: number): number {
  const s = String(step);
  if (s.includes('e-')) return Number(s.split('e-')[1]);
  return s.includes('.') ? s.split('.')[1]!.length : 0;
}

/**
 * Clamp `value` into [min, max] and onto the step grid that starts at `min`
 * (or 0), rounding away float noise.
 */
export function snapNumber(value: number, { min = -Infinity, max = Infinity, step = 1 }: { min?: number; max?: number; step?: number } = {}): number {
  if (!Number.isFinite(value)) value = Number.isFinite(min) ? min : 0;
  const base = Number.isFinite(min) ? min : 0;
  const decimals = Math.max(stepDecimals(step), stepDecimals(base));
  let v = step > 0 ? base + Math.round((value - base) / step) * step : value;
  v = Math.min(Math.max(v, min), max);
  return Number(v.toFixed(Math.min(decimals, 12)));
}

export interface RepeatOptions {
  /** Called once on press, then repeatedly while held. */
  onStep: () => void;
  /** Wait before repeating, ms. */
  delay?: number;
  /** First repeat interval, ms (it speeds up to a quarter of it). */
  interval?: number;
}

/**
 * Press-and-hold repeat on a button: one step on press, then after `delay`
 * steps that speed up while held. Keyboard Enter/Space still click normally
 * (the click that follows a pointer press is swallowed so it counts once).
 */
export function pressRepeat(button: HTMLElement, { onStep, delay = 400, interval = 120 }: RepeatOptions): Cleanup {
  if (!isBrowser) return () => {};
  let timer: ReturnType<typeof setTimeout> | undefined;
  let pointer = -1;
  let fromPointer = false;

  const stop = () => {
    clearTimeout(timer);
    timer = undefined;
    pointer = -1;
    button.removeAttribute('data-holding');
  };
  const tick = (wait: number) => {
    timer = setTimeout(() => {
      if ((button as HTMLButtonElement).disabled) return stop();
      onStep();
      tick(Math.max(interval / 4, wait * 0.82));
    }, wait);
  };
  const onDown = (event: PointerEvent) => {
    if (event.button !== 0 || pointer !== -1 || (button as HTMLButtonElement).disabled) return;
    pointer = event.pointerId;
    fromPointer = true;
    button.setPointerCapture?.(event.pointerId);
    button.setAttribute('data-holding', '');
    onStep();
    timer = setTimeout(() => tick(interval), delay - interval);
  };
  const onUp = (event: PointerEvent) => {
    if (event.pointerId === pointer) stop();
  };
  const onClick = (event: MouseEvent) => {
    if (fromPointer) {
      fromPointer = false;
      event.preventDefault();
      return;
    }
    onStep();
  };

  button.addEventListener('pointerdown', onDown);
  button.addEventListener('pointerup', onUp);
  button.addEventListener('pointercancel', onUp);
  button.addEventListener('lostpointercapture', stop);
  button.addEventListener('click', onClick);
  return () => {
    stop();
    button.removeEventListener('pointerdown', onDown);
    button.removeEventListener('pointerup', onUp);
    button.removeEventListener('pointercancel', onUp);
    button.removeEventListener('lostpointercapture', stop);
    button.removeEventListener('click', onClick);
  };
}

export interface ScrubOptions {
  /** Called with whole steps moved since the last call (+ toward the reading end). */
  onSteps: (steps: number) => void;
  /** Pointer travel per step, px. */
  pixels?: number;
  onStart?: () => void;
  onEnd?: () => void;
}

/**
 * Drag-to-scrub on a label: dragging horizontally changes the value by one
 * step every `pixels` px, toward the reading end increasing. Hold Shift for
 * ten times the steps, Alt for a tenth of the speed.
 */
export function scrubber(label: HTMLElement, { onSteps, pixels = 6, onStart, onEnd }: ScrubOptions): Cleanup {
  if (!isBrowser) return () => {};
  let drag: { id: number; x: number; carry: number; moved: boolean } | null = null;
  const onDown = (event: PointerEvent) => {
    if (event.button !== 0 || drag) return;
    drag = { id: event.pointerId, x: event.clientX, carry: 0, moved: false };
    label.setPointerCapture?.(event.pointerId);
  };
  const onMove = (event: PointerEvent) => {
    if (!drag || event.pointerId !== drag.id) return;
    const dx = (event.clientX - drag.x) * direction(label);
    if (!drag.moved) {
      if (Math.abs(dx) < 3) return;
      drag.moved = true;
      label.setAttribute('data-scrubbing', '');
      onStart?.();
    }
    event.preventDefault();
    drag.x = event.clientX;
    drag.carry += dx / (event.altKey ? pixels * 10 : pixels);
    const whole = Math.trunc(drag.carry);
    if (whole) {
      drag.carry -= whole;
      onSteps(whole * (event.shiftKey ? 10 : 1));
    }
  };
  const onUp = (event: PointerEvent) => {
    if (!drag || event.pointerId !== drag.id) return;
    const moved = drag.moved;
    drag = null;
    label.removeAttribute('data-scrubbing');
    if (moved) {
      label.setAttribute('data-scrubbed', '');
      onEnd?.();
    }
  };
  // A scrub must not also click the label (which would focus the input).
  const onClick = (event: MouseEvent) => {
    if (label.hasAttribute('data-scrubbed')) {
      event.preventDefault();
      label.removeAttribute('data-scrubbed');
    }
  };
  label.addEventListener('pointerdown', onDown);
  label.addEventListener('pointermove', onMove);
  label.addEventListener('pointerup', onUp);
  label.addEventListener('pointercancel', onUp);
  label.addEventListener('click', onClick);
  return () => {
    label.removeEventListener('pointerdown', onDown);
    label.removeEventListener('pointermove', onMove);
    label.removeEventListener('pointerup', onUp);
    label.removeEventListener('pointercancel', onUp);
    label.removeEventListener('click', onClick);
  };
}
