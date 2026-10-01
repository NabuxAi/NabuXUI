/**
 * Deterministic demo data for the panel — the seed of apps/admin/src/data.ts,
 * trimmed to what this flavour shows: relative moments (no clock drift between
 * the table and the feed) and the storage split. No `Math.random`, no fetch.
 */

const HOUR = 3_600_000;
const DAY = 24 * HOUR;

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

/** The storage card: gigabytes per category and the plan limit. */
export const STORAGE = { docs: 3.2, media: 2.1, voice: 1.4, embeddings: 0.7, limit: 10 };

/** The chart's twelve short month names in the reader's calendar (فارسی: جلالی). */
export function monthLabels(locale: string): string[] {
  const month = new Intl.DateTimeFormat(locale, { month: 'short' });
  return Array.from({ length: 12 }, (_, i) => month.format(new Date(2026, i, 15)));
}
