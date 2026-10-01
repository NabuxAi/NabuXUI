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

/* ---- The dashboard's store-health chart: twelve months, three series ------------------------- */

/** Millions of tomans (the chart multiplies by a million). */
export const REVENUE_BY_MONTH = [812, 880, 914, 902, 1005, 1080, 1042, 1130, 1210, 1180, 1244, 1315];
export const ORDERS_BY_MONTH = [610, 702, 748, 690, 806, 884, 842, 936, 1010, 962, 1030, 1118];
export const VISITS_BY_MONTH = [18400, 19200, 20800, 20100, 22600, 24100, 23000, 25400, 26900, 25800, 27200, 29100];

export const REVENUE_DELTA = 8.4;
export const ORDERS_DELTA = 6.1;
export const VISITS_DELTA = 12.7;

/** Twelve short month names in the reader's calendar ("شهریور" / "Sep"). */
export function monthLabels(locale: string): string[] {
  const month = new Intl.DateTimeFormat(locale, { month: 'short' });
  return Array.from({ length: 12 }, (_, i) => month.format(new Date(2026, i, 15)));
}
