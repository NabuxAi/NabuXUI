/**
 * Framework-agnostic behaviours for the menus, navigation & morphing panels
 * blocks: the size morph every "shell grows into a panel" block shares, light
 * dismissal, the morphing tab strip, the avatar-stack FLIP and the voice
 * recorder's level meter — plus the pure helpers behind them.
 *
 * Like every core behaviour they only measure, write custom properties and
 * data attributes, or start short Web Animations; the look and the springs
 * live in css/blocks/menus.css, so React and Alpine get the same motion.
 */
import { type Cleanup, isBrowser, prefersReducedMotion } from '../env';
import { indicator } from '../indicator';
import { type SpringEasing, type SpringName, springEasing, springs } from '../spring';

const clamp = (value: number, min: number, max: number) => Math.min(max, Math.max(min, value));
const FALLBACK_EASE = 'cubic-bezier(0.16, 1, 0.3, 1)';

/* ---- The blocks' own words ------------------------------------------------------ */

/**
 * Words these blocks say themselves, in the languages the core i18n table
 * ships (the core table already has close, back, search, menu, play, pause…).
 * Every other string comes in through props.
 */
export const menuWords = {
  en: {
    filter: 'Filter',
    clear: 'Clear',
    selected: '{count} selected',
    live: 'Live',
    listening: 'listening',
    speakers: 'Speakers',
    speaking: 'Speaking',
    muted: 'Muted',
    mute: 'Mute',
    unmute: 'Unmute',
    raiseHand: 'Raise hand',
    lowerHand: 'Lower hand',
    leave: 'Leave',
    openRoom: 'Open the room',
    minimize: 'Minimize',
    markAllRead: 'Mark all as read',
    caughtUp: 'You’re all caught up',
    unread: '{count} unread',
    members: 'Members',
    searchPeople: 'Search people…',
    addPeople: 'Add people',
    roleFor: 'Role for {name}',
    viewer: 'Viewer',
    editor: 'Editor',
    admin: 'Admin',
    details: 'Details',
    tickets: 'Tickets',
    confirm: 'Confirm',
    continue: 'Continue',
    register: 'Register',
    fullName: 'Full name',
    email: 'Email',
    total: 'Total',
    free: 'Free',
    addOne: 'Add one {name}',
    removeOne: 'Remove one {name}',
    chooseTicket: 'Choose at least one ticket',
    registered: 'You’re in!',
    registeredText: 'Your tickets are on their way to {email}.',
    stepOf: 'Step {step} of {total}',
    record: 'Record a voice message',
    recording: 'Recording',
    paused: 'Paused',
    resume: 'Resume',
    stopRecording: 'Stop recording',
    cancel: 'Cancel',
    seek: 'Seek',
    discard: 'Delete recording',
    voiceMessage: 'Voice message',
    dock: 'Dock',
  },
  fa: {
    filter: 'فیلتر',
    clear: 'پاک کردن',
    selected: '{count} مورد انتخاب شد',
    live: 'زنده',
    listening: 'شنونده',
    speakers: 'سخنران‌ها',
    speaking: 'در حال صحبت',
    muted: 'بی‌صدا',
    mute: 'بی‌صدا کردن',
    unmute: 'باصدا کردن',
    raiseHand: 'بالا بردن دست',
    lowerHand: 'پایین آوردن دست',
    leave: 'خروج',
    openRoom: 'باز کردن اتاق',
    minimize: 'کوچک کردن',
    markAllRead: 'همه خوانده شد',
    caughtUp: 'چیز تازه‌ای نمانده',
    unread: '{count} خوانده‌نشده',
    members: 'اعضا',
    searchPeople: 'جست‌وجوی افراد…',
    addPeople: 'افزودن افراد',
    roleFor: 'نقش {name}',
    viewer: 'بیننده',
    editor: 'ویرایشگر',
    admin: 'مدیر',
    details: 'مشخصات',
    tickets: 'بلیت‌ها',
    confirm: 'تأیید',
    continue: 'ادامه',
    register: 'ثبت‌نام',
    fullName: 'نام و نام خانوادگی',
    email: 'ایمیل',
    total: 'جمع',
    free: 'رایگان',
    addOne: 'افزودن یک {name}',
    removeOne: 'کم کردن یک {name}',
    chooseTicket: 'دست‌کم یک بلیت انتخاب کنید',
    registered: 'ثبت‌نام شد!',
    registeredText: 'بلیت‌ها به {email} فرستاده شد.',
    stepOf: 'مرحلهٔ {step} از {total}',
    record: 'ضبط پیام صوتی',
    recording: 'در حال ضبط',
    paused: 'متوقف',
    resume: 'ادامهٔ ضبط',
    stopRecording: 'پایان ضبط',
    cancel: 'لغو',
    seek: 'جابه‌جایی در صدا',
    discard: 'حذف صدا',
    voiceMessage: 'پیام صوتی',
    dock: 'داک',
  },
  ar: {
    filter: 'تصفية',
    clear: 'مسح',
    selected: 'تم تحديد {count}',
    live: 'مباشر',
    listening: 'يستمعون',
    speakers: 'المتحدثون',
    speaking: 'يتحدث',
    muted: 'مكتوم',
    mute: 'كتم الصوت',
    unmute: 'إلغاء الكتم',
    raiseHand: 'رفع اليد',
    lowerHand: 'خفض اليد',
    leave: 'مغادرة',
    openRoom: 'فتح الغرفة',
    minimize: 'تصغير',
    markAllRead: 'تعليم الكل كمقروء',
    caughtUp: 'لا جديد لديك',
    unread: '{count} غير مقروءة',
    members: 'الأعضاء',
    searchPeople: 'ابحث عن أشخاص…',
    addPeople: 'إضافة أشخاص',
    roleFor: 'دور {name}',
    viewer: 'مشاهد',
    editor: 'محرر',
    admin: 'مسؤول',
    details: 'البيانات',
    tickets: 'التذاكر',
    confirm: 'تأكيد',
    continue: 'متابعة',
    register: 'تسجيل',
    fullName: 'الاسم الكامل',
    email: 'البريد الإلكتروني',
    total: 'الإجمالي',
    free: 'مجاني',
    addOne: 'إضافة {name} واحدة',
    removeOne: 'إزالة {name} واحدة',
    chooseTicket: 'اختر تذكرة واحدة على الأقل',
    registered: 'تم تسجيلك!',
    registeredText: 'أُرسلت تذاكرك إلى {email}.',
    stepOf: 'الخطوة {step} من {total}',
    record: 'تسجيل رسالة صوتية',
    recording: 'جارٍ التسجيل',
    paused: 'متوقف مؤقتًا',
    resume: 'استئناف',
    stopRecording: 'إيقاف التسجيل',
    cancel: 'إلغاء',
    seek: 'التنقل في الصوت',
    discard: 'حذف التسجيل',
    voiceMessage: 'رسالة صوتية',
    dock: 'الشريط',
  },
} as const;

export type MenuWord = keyof (typeof menuWords)['en'];

/** `menuWord('fa', 'leave')`, `menuWord('en', 'unread', { count: 3 })`. Unknown languages fall back to English. */
export function menuWord(locale: string | undefined, key: MenuWord, params: Record<string, string | number> = {}): string {
  const language = (locale ?? 'en').slice(0, 2).toLowerCase();
  const table = (menuWords as Record<string, Record<string, string>>)[language] ?? menuWords.en;
  const text = table[key] ?? menuWords.en[key];
  return text.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`));
}

/**
 * A few glyphs these blocks draw beyond the core icon set (24×24, stroked at
 * 1.75 like the core icons). The Blade components carry the same paths.
 */
export const menuGlyphs = {
  'mic-off': 'M12 15a3 3 0 0 0 3-3V6a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3zM19 11a7 7 0 0 1-14 0M12 18v3M4 4l16 16',
  hand: 'M7 12.5V7.25a1.25 1.25 0 0 1 2.5 0v4.25M9.5 11.5V5.25a1.25 1.25 0 0 1 2.5 0v6.25M12 11.5V5.75a1.25 1.25 0 0 1 2.5 0v5.75M14.5 11.5V7.75a1.25 1.25 0 0 1 2.5 0V14a6 6 0 0 1-6 6h-.6a5 5 0 0 1-3.8-1.8l-2.7-3.3a1.3 1.3 0 0 1 2-1.6L7 14.5v-2',
  'hand-raised': 'M7 12.5V7.25a1.25 1.25 0 0 1 2.5 0v4.25M9.5 11.5V5.25a1.25 1.25 0 0 1 2.5 0v6.25M12 11.5V5.75a1.25 1.25 0 0 1 2.5 0v5.75M14.5 11.5V7.75a1.25 1.25 0 0 1 2.5 0V14a6 6 0 0 1-6 6h-.6a5 5 0 0 1-3.8-1.8l-2.7-3.3a1.3 1.3 0 0 1 2-1.6L7 14.5v-2M3.6 8.2l1.6.5M4 4.6l1.3 1.1M20.4 8.2l-1.6.5M20 4.6l-1.3 1.1',
  leave: 'M10 4H6.5A1.5 1.5 0 0 0 5 5.5v13A1.5 1.5 0 0 0 6.5 20H10M14.5 8l4 4-4 4M18.5 12H9.5',
  calendar: 'M5 6.5A1.5 1.5 0 0 1 6.5 5h11A1.5 1.5 0 0 1 19 6.5v11a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 5 17.5zM5 10h14M9 3v4M15 3v4',
  pin: 'M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11zM12 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z',
} as const;

export type MenuGlyph = keyof typeof menuGlyphs;

/** Glyphs that point along the reading direction (mirrored in right-to-left pages). */
export const directionalGlyphs: readonly MenuGlyph[] = ['leave'];

/* ---- Pure helpers (shared by React and Alpine) --------------------------------- */

/** Toggle `value` in a selection: a set of any size, or at most one when `multiple` is false. */
export function toggleSelection(list: readonly string[], value: string, multiple = true): string[] {
  const has = list.includes(value);
  if (multiple) return has ? list.filter((item) => item !== value) : [...list, value];
  return has ? [] : [value];
}

/** "425ms" or "0.4s" in milliseconds (NaN when it is neither). */
export function parseCssTime(value: string): number {
  const match = /^\s*(-?[\d.]+)\s*(ms|s)?\s*$/.exec(value);
  if (!match) return Number.NaN;
  const amount = Number(match[1]);
  return match[2] === 's' ? amount * 1000 : amount;
}

/** Seconds as a timer shows them: 00:07, 01:23, 12:05 (minutes keep growing past 99). */
export function formatClock(seconds: number): string {
  const total = Math.max(0, Math.floor(Number.isFinite(seconds) ? seconds : 0));
  const minutes = Math.floor(total / 60);
  return `${String(minutes).padStart(2, '0')}:${String(total % 60).padStart(2, '0')}`;
}

const RELATIVE_UNITS: Array<[Intl.RelativeTimeFormatUnit, number]> = [
  ['second', 60],
  ['minute', 3600],
  ['hour', 86400],
  ['day', 604800],
  ['week', 2629800],
  ['month', 31557600],
  ['year', Number.POSITIVE_INFINITY],
];
const UNIT_SECONDS: Record<string, number> = { second: 1, minute: 60, hour: 3600, day: 86400, week: 604800, month: 2629800, year: 31557600 };

/** A moment as a number of milliseconds, or NaN when it is a label ("Yesterday"). */
export function timeOf(time: Date | number | string): number {
  if (time instanceof Date) return time.getTime();
  if (typeof time === 'number') return time;
  return /\d{4}-\d{2}-\d{2}/.test(time) ? Date.parse(time) : Number.NaN;
}

/**
 * "now", "3 minutes ago", "yesterday", "in 2 hours" — in the reader's language.
 * A string that is not a date is shown as it is (a label the app already wrote).
 */
export function activityTime(time: Date | number | string, now: Date | number = Date.now(), locale?: string, style: Intl.RelativeTimeFormatStyle = 'long'): string {
  const then = timeOf(time);
  if (Number.isNaN(then)) return String(time);
  const diff = (then - (now instanceof Date ? now.getTime() : now)) / 1000;
  const abs = Math.abs(diff);
  const format = new Intl.RelativeTimeFormat(locale, { numeric: 'auto', style });
  if (abs < 45) return format.format(0, 'second');
  const [unit] = RELATIVE_UNITS.find(([, limit]) => abs < limit) ?? RELATIVE_UNITS[RELATIVE_UNITS.length - 1]!;
  return format.format(Math.round(diff / UNIT_SECONDS[unit]!), unit);
}

/** Fold the letter variants of Persian and Arabic together and drop diacritics, so either spelling matches. */
export function foldSearchText(text: string): string {
  return text
    .toLocaleLowerCase()
    .normalize('NFKD')
    .replace(/[̀-ͯ]/g, '')
    .replace(/[يى]/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[ً-ٰۖ-ۭ‌]/g, '')
    .normalize('NFKC');
}

export interface MenuTreeItem {
  id: string;
  label: string;
  keywords?: readonly string[];
  children?: readonly MenuTreeItem[];
}

export interface MenuTreeMatch<T extends MenuTreeItem> {
  item: T;
  /** The item's ancestors, outermost first (what a breadcrumb shows). */
  trail: T[];
}

/** The chain of items that `path` (a list of ids, outermost first) walks through. */
export function menuPath<T extends MenuTreeItem>(items: readonly T[], path: readonly string[]): T[] {
  const chain: T[] = [];
  let level: readonly T[] = items;
  for (const id of path) {
    const next = level.find((item) => item.id === id);
    if (!next) break;
    chain.push(next);
    level = (next.children ?? []) as readonly T[];
  }
  return chain;
}

/** Every item at any depth whose label or keywords contain `query`, in reading order, with its trail. */
export function searchMenuTree<T extends MenuTreeItem>(items: readonly T[], query: string, limit = 60): Array<MenuTreeMatch<T>> {
  const q = foldSearchText(query.trim());
  const out: Array<MenuTreeMatch<T>> = [];
  if (!q) return out;
  const walk = (level: readonly T[], trail: T[]) => {
    for (const item of level) {
      if (out.length >= limit) return;
      const haystack = foldSearchText([item.label, ...(item.keywords ?? [])].join(' '));
      if (haystack.includes(q)) out.push({ item, trail });
      if (item.children?.length) walk(item.children as readonly T[], [...trail, item]);
    }
  };
  walk(items, []);
  return out;
}

/** The sum of quantity × price over the ticket types. */
export function ticketTotal(tickets: ReadonlyArray<{ id: string; price: number }>, quantities: Readonly<Record<string, number>>): number {
  return tickets.reduce((sum, ticket) => sum + (Number(quantities[ticket.id]) || 0) * ticket.price, 0);
}

/** How loud a block of time-domain samples (0–255, 128 = silence) sounds, 0–1, eased so speech fills the meter. */
export function rmsLevel(samples: ArrayLike<number>): number {
  if (!samples.length) return 0;
  let sum = 0;
  for (let i = 0; i < samples.length; i++) {
    const v = (samples[i]! - 128) / 128;
    sum += v * v;
  }
  const rms = Math.sqrt(sum / samples.length);
  return clamp(Math.pow(Math.min(1, rms * 3.2), 0.8), 0, 1);
}

const hash = (n: number) => {
  const s = Math.sin(n * 127.1 + 311.7) * 43758.5453;
  return s - Math.floor(s);
};

/**
 * A believable speaking level at `t` seconds, 0–1: words with syllables,
 * the odd pause between them and a little breath noise. Deterministic, so a
 * simulated recording plays back the same way it was drawn.
 */
export function voiceLevel(t: number, seed = 0): number {
  const time = Math.max(0, t) * 2.1 + seed;
  const word = Math.floor(time);
  const inWord = time - word;
  const noise = hash(Math.floor(t * 28) + seed * 13.7) * 0.12;
  if (hash(word + seed) < 0.16) return clamp(0.04 + noise * 0.5, 0, 1);
  const envelope = Math.pow(Math.sin(Math.PI * Math.min(1, inWord / 0.9)), 0.7);
  const syllable = 0.55 + 0.45 * Math.abs(Math.sin(t * 15.5 + hash(word) * 6.28));
  const loudness = 0.35 + 0.65 * hash(word * 1.37 + 0.5);
  return clamp(0.04 + loudness * envelope * syllable * 0.92 + noise, 0, 1);
}

/** `count` bars summarising `values` (each bar is the loudest moment of its slice). */
export function downsampleLevels(values: readonly number[], count: number): number[] {
  const n = Math.max(0, Math.floor(count));
  if (!values.length) return Array.from({ length: n }, () => 0);
  return Array.from({ length: n }, (_, i) => {
    const start = Math.floor((i * values.length) / n);
    const end = Math.max(start + 1, Math.floor(((i + 1) * values.length) / n));
    let peak = 0;
    for (let j = start; j < end && j < values.length; j++) peak = Math.max(peak, values[j]!);
    return peak;
  });
}

/* ---- Springs from the page's own tokens --------------------------------------------- */

/**
 * A spring as the page's CSS defines it right now — reduced motion already
 * swaps the tokens for a short ease — so a Web Animation started from script
 * moves exactly like the CSS transitions around it.
 */
export function cssSpring(el: Element, name: SpringName = 'gentle'): SpringEasing {
  const fallback = springEasing(springs[name]);
  if (!isBrowser) return fallback;
  const style = getComputedStyle(el);
  const easing = style.getPropertyValue(`--nx-spring-${name}`).trim();
  const duration = parseCssTime(style.getPropertyValue(`--nx-spring-${name}-duration`));
  return easing && Number.isFinite(duration) ? { easing, duration } : fallback;
}

/** Element.animate that falls back to a plain ease where the browser rejects linear() easings. */
function animateSafely(el: Element, keyframes: Keyframe[], options: KeyframeAnimationOptions): Animation | null {
  if (typeof (el as HTMLElement).animate !== 'function') return null;
  try {
    return el.animate(keyframes, options);
  } catch {
    try {
      return el.animate(keyframes, { ...options, easing: FALLBACK_EASE });
    } catch {
      return null;
    }
  }
}

/* ---- Morph shell --------------------------------------------------------------------- */

export interface MorphShellOptions {
  /** The element the shell follows (default: its first element child). */
  content?: Element | null;
  /** Follow both axes (default), only the height ('block') or only the width ('inline'). */
  axis?: 'both' | 'block' | 'inline';
}

/**
 * Size a shell to its content and let CSS spring between sizes: whenever the
 * content's border box changes, --nx-morph-w / --nx-morph-h (px) are written
 * on the shell, which transitions its inline-size / block-size to them.
 *
 * The first measurement lands without a transition (data-nx-morph="init",
 * then "ready"). Content that is leaving should be taken out of flow
 * (position: absolute) so the shell follows the content that stays.
 */
export function morphShell(shell: HTMLElement, { content, axis = 'both' }: MorphShellOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const target = (content ?? shell.firstElementChild) as HTMLElement | null;
  if (!target) return () => {};

  const write = (width: number, height: number) => {
    // Round up to the next hundredth so sub-pixel text is never clipped.
    if (axis !== 'block') shell.style.setProperty('--nx-morph-w', `${Math.ceil(width * 100) / 100}px`);
    if (axis !== 'inline') shell.style.setProperty('--nx-morph-h', `${Math.ceil(height * 100) / 100}px`);
  };

  // Land the next measurement in place, then spring from there on.
  let frame = 0;
  const settle = () => {
    cancelAnimationFrame(frame);
    shell.setAttribute('data-nx-morph', 'init');
    frame = requestAnimationFrame(() => {
      frame = requestAnimationFrame(() => shell.setAttribute('data-nx-morph', 'ready'));
    });
  };

  // Content that is not rendered (a closed popover, display: none) measures
  // 0 × 0: skip it, and let the size it has when shown again land without growing.
  let hidden = false;
  const measure = (width: number, height: number) => {
    if (width === 0 && height === 0) {
      hidden = true;
      return;
    }
    if (hidden) {
      hidden = false;
      settle();
    }
    write(width, height);
  };

  if (shell.getAttribute('data-nx-morph') !== 'ready') settle();
  measure(target.offsetWidth, target.offsetHeight);

  if (typeof ResizeObserver === 'undefined') return () => cancelAnimationFrame(frame);
  const observer = new ResizeObserver(([entry]) => {
    const box = entry?.borderBoxSize?.[0];
    if (box) measure(box.inlineSize, box.blockSize);
    else measure(target.offsetWidth, target.offsetHeight);
  });
  observer.observe(target);

  return () => {
    cancelAnimationFrame(frame);
    observer.disconnect();
  };
}

/* ---- Light dismiss ---------------------------------------------------------------------- */

export type DismissReason = 'escape' | 'outside' | 'focus';

export interface LightDismissOptions {
  /** Escape pressed while focus is inside (default true). */
  escape?: boolean;
  /** A press anywhere outside (default true). */
  outside?: boolean;
  /** Focus moving to something outside (default false). */
  focusOut?: boolean;
  /** Other elements that count as inside (a trigger that lives elsewhere, a popover in the top layer). */
  inside?: ReadonlyArray<Element | null | undefined>;
}

/**
 * Call `onDismiss` when the reader presses Escape inside `el`, presses outside
 * it, or (optionally) moves focus out of it — the light dismissal of an open
 * panel that is not a native popover. Attach it while open; the cleanup detaches.
 */
export function lightDismiss(el: HTMLElement, onDismiss: (reason: DismissReason) => void, { escape = true, outside = true, focusOut = false, inside = [] }: LightDismissOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const contains = (node: EventTarget | null) =>
    node instanceof Node && (el.contains(node) || inside.some((other) => other?.contains(node)));

  const onKey = (event: KeyboardEvent) => {
    if (event.key !== 'Escape' || event.defaultPrevented) return;
    event.preventDefault();
    event.stopPropagation();
    onDismiss('escape');
  };
  const onDown = (event: PointerEvent) => {
    const path = typeof event.composedPath === 'function' ? event.composedPath() : [event.target];
    if (!path.some((node) => contains(node as EventTarget))) onDismiss('outside');
  };
  const onFocusOut = (event: FocusEvent) => {
    // A null target means the window lost focus: not a reason to close.
    if (event.relatedTarget && !contains(event.relatedTarget)) onDismiss('focus');
  };

  if (escape) el.addEventListener('keydown', onKey);
  if (outside) document.addEventListener('pointerdown', onDown, true);
  if (focusOut) el.addEventListener('focusout', onFocusOut);
  return () => {
    el.removeEventListener('keydown', onKey);
    document.removeEventListener('pointerdown', onDown, true);
    el.removeEventListener('focusout', onFocusOut);
  };
}

/* ---- Morphing tabs ------------------------------------------------------------------------- */

export interface MorphTabsOptions {
  /** Each tab's collapsible label wrapper; its first child holds the text at its natural width. */
  label?: string;
}

export interface MorphTabsController {
  /** Call once the newly selected tab is marked selected in the DOM (aria-selected). */
  select(tab: HTMLElement | null): void;
  destroy: Cleanup;
}

/**
 * The icon tab strip whose active tab opens to show its label.
 *
 * Labels change width at once in CSS (0 or auto), so the layout is final the
 * moment a tab is selected: the shared pill (core `indicator`, on `rail`)
 * measures exactly where the tab will end up, and each label that changed is
 * then sprung from the width it had to its new one with the snappy spring —
 * the same spring the pill moves on, so both land together. Interrupted
 * morphs restart from the width on screen.
 */
export function morphTabs(rail: HTMLElement, { label = '[data-nx-tab-label]' }: MorphTabsOptions = {}): MorphTabsController {
  const pill = indicator(rail);
  const running = new Map<Element, Animation>();
  let current: HTMLElement | null = null;
  let first = true;

  // Layout widths (offsetWidth), not boxes: a pressed tab's scale must not skew the measure.
  const natural = (el: HTMLElement) => (el.firstElementChild as HTMLElement | null)?.offsetWidth ?? 0;

  return {
    select(tab) {
      if (!isBrowser) return;
      const previous = current;
      current = tab;
      const labels = Array.from(rail.querySelectorAll<HTMLElement>(label));

      // 1. Where each label is now: mid-morph widths count, otherwise the resting width it had.
      const from = labels.map((el) => {
        const animation = running.get(el);
        if (animation && animation.playState === 'running') return el.offsetWidth;
        const owner = el.closest('[role="tab"]');
        return owner && owner === previous ? natural(el) : 0;
      });

      // 2. Settle: without the animations the layout shows the final state…
      for (const animation of running.values()) animation.cancel();
      running.clear();

      // 3. …which the pill measures and springs to.
      pill.update(tab);

      if (first || prefersReducedMotion()) {
        first = false;
        return;
      }

      // 4. Each label that changed grows or shrinks from where it was.
      const { easing, duration } = cssSpring(rail, 'snappy');
      labels.forEach((el, i) => {
        const to = el.offsetWidth;
        if (Math.abs(to - from[i]!) < 0.5) return;
        const animation = animateSafely(el, [{ width: `${from[i]}px` }, { width: `${to}px` }], { duration, easing });
        if (!animation) return;
        running.set(el, animation);
        animation.addEventListener('finish', () => running.delete(el));
      });
    },
    destroy() {
      for (const animation of running.values()) animation.cancel();
      running.clear();
      pill.destroy();
    },
  };
}

/* ---- Avatar stack FLIP ---------------------------------------------------------------------- */

export interface FlipStackOptions {
  /** The items that move (each carries a stable key). */
  selector: string;
  /** Attribute holding each item's key (default data-key). */
  key?: string;
  spring?: SpringName;
}

export interface FlipStackController {
  /** Call after the items changed in the DOM: those that moved slide from where they were. */
  update(): void;
  destroy: Cleanup;
}

/**
 * Keeps the last resting position of each keyed item; after a change, items
 * that moved glide from the old position to the new one (a composited
 * translate, so hover lifts and entrances still apply on top).
 */
export function flipStack(container: HTMLElement, { selector, key = 'data-key', spring = 'gentle' }: FlipStackOptions): FlipStackController {
  const last = new Map<string, { x: number; y: number }>();
  const read = () =>
    Array.from(container.querySelectorAll<HTMLElement>(selector)).map((el) => ({ el, id: el.getAttribute(key) ?? '', x: el.offsetLeft, y: el.offsetTop }));

  if (isBrowser) for (const row of read()) last.set(row.id, row);

  return {
    update() {
      if (!isBrowser) return;
      const rows = read();
      if (!prefersReducedMotion()) {
        const { easing, duration } = cssSpring(container, spring);
        for (const { el, id, x, y } of rows) {
          const before = last.get(id);
          if (!before || (before.x === x && before.y === y)) continue;
          animateSafely(el, [{ translate: `${before.x - x}px ${before.y - y}px` }, { translate: '0px 0px' }], { duration, easing, composite: 'add' });
        }
      }
      last.clear();
      for (const row of rows) last.set(row.id, row);
    },
    destroy() {
      last.clear();
    },
  };
}

/* ---- Level meter (voice recorder) ------------------------------------------------------------ */

export interface LevelMeterOptions {
  /** The current input level, 0–1, read once per tick. */
  level: () => number;
  /** Ticks per second (default 18; 5 under reduced motion). */
  rate?: number;
  /** Every new sample, for keeping the whole take. */
  onSample?: (value: number) => void;
}

export interface LevelMeterController {
  start(): void;
  stop(): void;
  /** Show these levels (oldest first) without sampling: a still picture of a take. */
  show(values: readonly number[]): void;
  destroy: Cleanup;
}

/**
 * Drive a row of bars (the children of `bars`) as a scrolling level meter:
 * each tick samples `level()`, the newest sample enters at the inline end and
 * the rest move one bar toward the start. Each bar only gets --_v (0–1); its
 * height and easing are CSS.
 */
export function levelMeter(bars: HTMLElement, { level, rate, onSample }: LevelMeterOptions): LevelMeterController {
  let values: number[] = [];
  let frame = 0;
  let lastTick = 0;

  const paint = () => {
    const nodes = Array.from(bars.children) as HTMLElement[];
    if (values.length !== nodes.length) values = [...Array.from({ length: Math.max(0, nodes.length - values.length) }, () => 0), ...values].slice(-nodes.length);
    nodes.forEach((node, i) => node.style.setProperty('--_v', (values[i] ?? 0).toFixed(3)));
  };

  const tick = (now: number) => {
    frame = requestAnimationFrame(tick);
    const perSecond = rate ?? (prefersReducedMotion() ? 5 : 18);
    if (now - lastTick < 1000 / perSecond) return;
    lastTick = now;
    const value = clamp(Number(level()) || 0, 0, 1);
    onSample?.(value);
    values.push(value);
    values.shift();
    paint();
  };

  return {
    start() {
      if (!isBrowser || frame) return;
      if (!values.length) {
        values = Array.from({ length: bars.children.length }, () => 0);
        paint();
      }
      frame = requestAnimationFrame(tick);
    },
    stop() {
      cancelAnimationFrame(frame);
      frame = 0;
    },
    show(next) {
      values = [...next];
      paint();
    },
    destroy() {
      cancelAnimationFrame(frame);
      frame = 0;
    },
  };
}
