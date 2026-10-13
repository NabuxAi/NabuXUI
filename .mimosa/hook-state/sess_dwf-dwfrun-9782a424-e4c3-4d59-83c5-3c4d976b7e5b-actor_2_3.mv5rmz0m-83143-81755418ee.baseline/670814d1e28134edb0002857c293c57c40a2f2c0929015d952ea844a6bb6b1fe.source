/**
 * Deterministic demo data for the panel — seeded, no `Math.random`, no fetch:
 * the same numbers on every load, so screenshots and checks stay reproducible.
 * Anything the reader sees as a word (labels, names of months, captions) lives
 * in lang.tsx; only numbers, dates and proper nouns live here.
 */

const HOUR = 3_600_000;
const DAY = 24 * HOUR;

/** A tiny seeded PRNG (mulberry32) so the heatmap is stable across reloads. */
function rng(seed: number) {
  let state = seed;
  return () => {
    state = (state + 0x6d2b79f5) | 0;
    let t = Math.imul(state ^ (state >>> 15), 1 | state);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

/* ---- Metric chart: the last 12 months, three switchable metrics ---------------- */

/** Revenue in thousands; the chart formats it compact (812K → ۱٬۳۱۵K). */
export const REVENUE_BY_MONTH = [812, 880, 914, 902, 1005, 1080, 1042, 1130, 1210, 1180, 1244, 1315];
export const ORDERS_BY_MONTH = [610, 702, 748, 690, 806, 884, 842, 936, 1010, 962, 1030, 1118];
export const VISITS_BY_MONTH = [18400, 19200, 20800, 20100, 22600, 24100, 23000, 25400, 26900, 25800, 27200, 29100];

export const REVENUE_DELTA = 8.4;
export const ORDERS_DELTA = 6.1;
export const VISITS_DELTA = 12.7;

/* ---- Heatmap: 26 weeks of support conversations ------------------------------- */

export interface HeatDay {
  date: string;
  value: number;
}

export const HEAT_DAYS: HeatDay[] = (() => {
  const random = rng(7);
  const end = new Date();
  end.setHours(0, 0, 0, 0);
  const total = 26 * 7;
  return Array.from({ length: total }, (_, i) => {
    const day = new Date(end.getTime() - (total - 1 - i) * DAY);
    // Thursdays and Fridays (the Persian weekend edge) stay quiet.
    const quiet = day.getDay() === 4 || day.getDay() === 5;
    const value = quiet ? Math.round(random() * 3) : Math.round(random() * 14);
    return { date: day.toISOString().slice(0, 10), value };
  });
})();

export const HEAT_TOTAL = HEAT_DAYS.reduce((sum, day) => sum + day.value, 0);

/* ---- Usage card: storage in gigabytes ------------------------------------------ */

export const STORAGE = { docs: 3.2, media: 2.1, voice: 1.4, embeddings: 0.7, limit: 10 };

/* ---- Relative moments the feeds and the topbar bell hang from ------------------ */

export const NOW = Date.now();

export interface Moment {
  ms: number;
  at: number;
}

export const AGO: Record<string, Moment> = {
  minutes3: { ms: 3 * 60_000, at: NOW - 3 * 60_000 },
  minutes18: { ms: 18 * 60_000, at: NOW - 18 * 60_000 },
  hour1: { ms: HOUR, at: NOW - HOUR },
  hours2: { ms: 2 * HOUR, at: NOW - 2 * HOUR },
  hours5: { ms: 5 * HOUR, at: NOW - 5 * HOUR },
  day1: { ms: DAY, at: NOW - DAY },
  days2: { ms: 2 * DAY, at: NOW - 2 * DAY },
};

/** The month labels for the metric chart, in the reader’s calendar. */
export function monthLabels(locale: string): string[] {
  const month = new Intl.DateTimeFormat(locale, { month: 'short' });
  return Array.from({ length: 12 }, (_, i) => month.format(new Date(2026, i, 15)));
}
