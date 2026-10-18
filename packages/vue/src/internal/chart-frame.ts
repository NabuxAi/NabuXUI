/**
 * The frame every chart family shares — legend, screen-reader data table,
 * tooltip bubble, tick formatting and the keyboard reading — mirroring the
 * helpers at the top of ui-react's components/chart.tsx, same DOM.
 */
import { type FunctionalComponent, computed, h, type MaybeRefOrGetter, type PropType, type Ref, toValue } from 'vue';
import { INTL_LOCALES, type VueNode } from './utils';
import type { NxLang } from '../i18n';

export interface Series {
  name: string;
  values: number[];
}

export const PAD = { top: 16, right: 12, bottom: 28, left: 44 } as const;

export const seriesColor = (i: number) => `var(--nx-chart-${(i % 7) + 1})`;

/** The three Intl formats a chart needs, off the prop locale or the page language. */
export function useChartFormat(locale: MaybeRefOrGetter<string | undefined>, format: MaybeRefOrGetter<Intl.NumberFormatOptions | undefined>, lang: MaybeRefOrGetter<NxLang>) {
  return computed(() => {
    const intl = toValue(locale) ?? INTL_LOCALES[toValue(lang)];
    const options = toValue(format);
    const value = new Intl.NumberFormat(intl, options);
    const tick = new Intl.NumberFormat(intl, {
      notation: 'compact',
      maximumFractionDigits: 1,
      ...(options?.style === 'currency' ? { style: 'currency', currency: options.currency } : null),
    });
    const percent = new Intl.NumberFormat(intl, { style: 'percent', maximumFractionDigits: 0 });
    return {
      value: (n: number) => value.format(n),
      tick: (n: number) => tick.format(n),
      percent: (n: number) => percent.format(n),
    };
  });
}

/** Legend (only for two or more series — a single series is named by the title). */
export const NxChartLegend: FunctionalComponent<{ series: Series[]; shape: 'rect' | 'line' }> = ({ series, shape }) => {
  if (series.length < 2) return null;
  return h('ul', { class: 'nx-chart-legend' }, series.map((s, i) =>
    h('li', { key: s.name, class: 'nx-legend-item' }, [
      h('span', { class: 'nx-legend-swatch', 'data-shape': shape, style: { '--nx-series': seriesColor(i) } }),
      s.name,
    ]),
  ));
};
NxChartLegend.props = { series: { type: Array as PropType<Series[]>, required: true }, shape: { type: String as PropType<'rect' | 'line'>, default: 'rect' } };

/** The same numbers as a table, for screen readers and anyone who cannot use the chart. */
export const NxChartTable: FunctionalComponent<{ caption?: VueNode; labels: string[]; series: Series[]; format: (n: number) => string }> = ({ caption, labels, series, format }) =>
  h('table', { class: 'nx-visually-hidden' }, [
    caption ? h('caption', null, [caption]) : null,
    h('thead', null, [
      h('tr', null, [
        h('td'),
        ...series.map((s) => h('th', { key: s.name, scope: 'col' }, [s.name])),
      ]),
    ]),
    h('tbody', null, labels.map((label, row) =>
      h('tr', { key: label }, [
        h('th', { scope: 'row' }, [label]),
        ...series.map((s) => h('td', { key: s.name }, [format(s.values[row] ?? 0)])),
      ]),
    )),
  ]);
NxChartTable.props = {
  caption: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
  labels: { type: Array as PropType<string[]>, required: true },
  series: { type: Array as PropType<Series[]>, required: true },
  format: { type: Function as PropType<(n: number) => string>, required: true },
};

export interface ChartTooltipRow {
  name: string;
  value: string;
  color: string;
}

export const NxChartTooltip: FunctionalComponent<{ open: boolean; x: number; y: number; width: number; title: string; rows: ChartTooltipRow[] }> = ({ open, x, y, width, title, rows }) => {
  // Keep the bubble inside the plot: clamp around the pointer's x.
  const clampedX = Math.min(Math.max(x, 70), Math.max(70, width - 70));
  return h('div', {
    class: 'nx-chart-tooltip',
    'data-open': open ? '' : undefined,
    'aria-hidden': 'true',
    style: { '--nx-tx': `calc(${clampedX}px - 50%)`, '--nx-ty': `calc(${y}px - 100% - 12px)` },
  }, [
    h('p', { class: 'nx-chart-tooltip-title' }, [title]),
    ...rows.map((row) =>
      h('div', { key: row.name, class: 'nx-chart-tooltip-row' }, [
        h('span', { class: 'nx-chart-tooltip-key', style: { '--nx-series': row.color } }),
        h('strong', null, [row.value]),
        h('span', null, [row.name]),
      ]),
    ),
  ]);
};
NxChartTooltip.props = {
  open: { type: Boolean, default: false },
  x: { type: Number, default: 0 },
  y: { type: Number, default: 0 },
  width: { type: Number, default: 0 },
  title: { type: String, default: '' },
  rows: { type: Array as PropType<ChartTooltipRow[]>, required: true },
};

/** Keyboard reading of a chart: arrows move between categories, Escape leaves. */
export function useChartKeys(count: () => number, active: Ref<number | null>, setActive: (next: number | null) => void) {
  return (event: KeyboardEvent) => {
    const rtl = false; // the plot is always drawn left to right
    if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
      event.preventDefault();
      const step = (event.key === 'ArrowRight') !== rtl ? 1 : -1;
      setActive(active.value === null ? 0 : Math.min(Math.max(active.value + step, 0), count() - 1));
    } else if (event.key === 'Home') setActive(0);
    else if (event.key === 'End') setActive(count() - 1);
    else if (event.key === 'Escape') setActive(null);
  };
}
