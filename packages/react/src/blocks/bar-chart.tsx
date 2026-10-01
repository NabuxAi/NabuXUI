/**
 * BarColumns — the CSS bar chart block (React).
 *
 * Thin over css/blocks/bar-chart.css: heights ride custom properties, so the
 * bars grow from the baseline in steps when the chart is revealed — one step
 * per category, a finer one per series inside it; a stack grows as one column.
 * Series stand grouped side by side or stacked. The tooltip opens above each
 * group's tallest bar on hover and on keyboard focus (each group is a tab stop
 * labelled with its readout), the value axis is a nice-ticks ladder in the
 * reader's digits, and the same data ships as a visually-hidden table.
 *
 * The SVG chart primitive (`BarChart` in components/chart) stays the tool for
 * dense/interactive plots; this block is the CSS one, mirrored by the Blade
 * `<x-nx::bar-chart>` component.
 */
import { type CSSProperties, type HTMLAttributes, type ReactNode, useId, useMemo, useRef } from 'react';
import { niceTicks, reveal } from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { useLocale, useT } from '../internal/provider';

/** The Intl locale: the prop, or the provider's language. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/* ---- Types ---------------------------------------------------------------------------- */

export interface BarColumnsSeries {
  /** Shown in the legend, the tooltip and the hidden table's header. */
  name?: string;
  /** One value per label, in order; negatives clamp to the baseline. */
  values: number[];
}

export interface BarColumnsProps extends Omit<HTMLAttributes<HTMLElement>, 'children' | 'title'> {
  /** `{ Monday: 42, Tuesday: 58 }` — shorthand for one unnamed series. */
  data?: Record<string, number>;
  /** One name per column ("Mon", "Tue"). Falls back to 1…n. */
  labels?: string[];
  /** The series; several stand grouped side by side, or stacked with `stacked`. */
  series?: BarColumnsSeries[];
  /** Stack the series instead of grouping them; tooltips then add a total row. */
  stacked?: boolean;
  title?: ReactNode;
  subtitle?: ReactNode;
  /** Plot height in px, or any CSS length ("18rem"). */
  height?: number | string;
  locale?: string;
  /** Number-format options for the values (decimals default to the data's own). */
  format?: Intl.NumberFormatOptions;
}

interface BarColumnGroup {
  label: string;
  values: number[];
  total: number;
  /** Where the group's tooltip parks: its tallest bar, in % of the plot. */
  top: number;
}

export function BarColumns({
  data,
  labels: labelsProp,
  series: seriesProp,
  stacked = false,
  title,
  subtitle,
  height = 240,
  locale,
  format,
  className,
  style,
  ...rest
}: BarColumnsProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const figure = useRef<HTMLElement>(null);
  const heading = `nx-bar-chart${useId().replace(/:/g, '')}`;
  useBehavior(figure, reveal, { once: true });

  const model = useMemo(() => {
    let labelsList = labelsProp ?? [];
    let list = seriesProp ?? [];
    if (data) {
      const entries = Object.entries(data);
      labelsList = entries.map(([label]) => label);
      list = [{ values: entries.map(([, value]) => value) }];
    }
    const series = list.map((s) => ({ name: s.name, values: s.values.map((v) => Math.max(0, v)) }));
    if (!labelsList.length) {
      const longest = series.reduce((n, s) => Math.max(n, s.values.length), 0);
      labelsList = Array.from({ length: longest }, (_, i) => String(i + 1));
    }
    const decimals = series.some((s) => s.values.some((v) => !Number.isInteger(v))) ? 1 : 0;
    const value = new Intl.NumberFormat(intl, { maximumFractionDigits: decimals, ...format });
    const totals = labelsList.map((_, i) => series.reduce((sum, s) => sum + (s.values[i] ?? 0), 0));
    const maxValue = stacked ? Math.max(0, ...totals) : Math.max(0, ...series.flatMap((s) => s.values), 0);
    // The same ladder the core's niceTicks builds for the SVG charts.
    const ticks = niceTicks(0, maxValue, 4);
    const top = ticks[ticks.length - 1] ?? 1;
    const percent = (v: number) => (top > 0 ? Math.round((v / top) * 10000) / 100 : 0);
    const groups: BarColumnGroup[] = labelsList.map((label, i) => {
      const values = series.map((s) => s.values[i] ?? 0);
      return { label, values, total: totals[i] ?? 0, top: percent(stacked ? totals[i] ?? 0 : Math.max(0, ...values)) };
    });
    return {
      series,
      groups,
      ticks,
      percent,
      value,
      tickValue: new Intl.NumberFormat(intl, { maximumFractionDigits: ticks.some((tick) => !Number.isInteger(tick)) ? 1 : 0 }),
    };
  }, [data, labelsProp, seriesProp, stacked, intl, format]);

  const nameOf = (series: { name?: string }) => series.name ?? t('barChartSeries');

  return (
    <figure
      ref={figure}
      className={cx('nx-chart', 'nx-bar-chart', className)}
      aria-labelledby={title ? `${heading}-title` : undefined}
      style={{ ...vars({ '--nx-bar-chart-height': typeof height === 'number' ? `${height}px` : height }), ...style }}
      {...rest}
    >
      {(title || subtitle || model.series.length > 1) && (
        <div className="nx-chart-head">
          <div>
            {title && (
              <p className="nx-chart-title" id={`${heading}-title`}>
                {title}
              </p>
            )}
            {subtitle && <p className="nx-chart-subtitle">{subtitle}</p>}
          </div>
          {model.series.length > 1 && (
            <ul className="nx-chart-legend">
              {model.series.map((series, j) => (
                <li key={j} className="nx-legend-item">
                  <span className="nx-legend-swatch" style={vars({ '--nx-series': `var(--nx-chart-${(j % 7) + 1})` })} />
                  {nameOf(series)}
                </li>
              ))}
            </ul>
          )}
        </div>
      )}
      <div className="nx-bar-chart-frame">
        <div className="nx-bar-chart-plot">
          {model.ticks.map((tick, i) => (
            <span key={`line-${i}`} className="nx-bar-chart-gridline" data-zero={tick === 0 ? '' : undefined} style={vars({ '--_p': model.percent(tick) })} aria-hidden="true" />
          ))}
          {model.ticks.map((tick, i) => (
            <span key={`tick-${i}`} className="nx-bar-chart-tick" style={vars({ '--_p': model.percent(tick) })} aria-hidden="true">
              {model.tickValue.format(tick)}
            </span>
          ))}
          <div className="nx-bar-chart-row">
            {model.groups.map((group, i) => {
              // "{label}: 12" for one unnamed series, "{label}: Web 12 · Mobile 8" otherwise.
              const spoken =
                model.series.length === 1 && !model.series[0]!.name
                  ? model.value.format(group.values[0] ?? 0)
                  : model.series.map((series, j) => `${nameOf(series)} ${model.value.format(group.values[j] ?? 0)}`).join(' · ');
              return (
                <div
                  key={`${group.label}-${i}`}
                  className="nx-bar-chart-group"
                  tabIndex={0}
                  role="img"
                  aria-label={`${group.label}: ${spoken}`}
                  style={vars({ '--nx-i': i, '--_top': group.top })}
                >
                  <div className="nx-bar-chart-bars" data-stacked={stacked ? '' : undefined} aria-hidden="true">
                    {group.values.map((value, j) => (
                      <span
                        key={j}
                        className="nx-bar-chart-bar"
                        data-cap={!stacked || j === group.values.length - 1 ? '' : undefined}
                        style={vars({ '--_v': model.percent(value), '--nx-j': j, '--nx-series': `var(--nx-chart-${(j % 7) + 1})` })}
                      />
                    ))}
                  </div>
                  <div className="nx-chart-tooltip nx-bar-chart-tip" aria-hidden="true">
                    <p className="nx-chart-tooltip-title">{group.label}</p>
                    {group.values.map((value, j) => (
                      <div key={j} className="nx-chart-tooltip-row">
                        <span className="nx-chart-tooltip-key" style={vars({ '--nx-series': `var(--nx-chart-${(j % 7) + 1})` })} />
                        <strong>{model.value.format(value)}</strong>
                        <span>{nameOf(model.series[j]!)}</span>
                      </div>
                    ))}
                    {stacked && model.series.length > 1 && (
                      <div className="nx-chart-tooltip-row">
                        <strong>{model.value.format(group.total)}</strong>
                        <span>{t('barChartTotal')}</span>
                      </div>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        </div>
        <div className="nx-bar-chart-names" aria-hidden="true">
          {model.groups.map((group, i) => (
            <span key={`${group.label}-${i}`} className="nx-bar-chart-name">
              {group.label}
            </span>
          ))}
        </div>
      </div>
      <table className="nx-visually-hidden">
        {title && <caption>{title}</caption>}
        <thead>
          <tr>
            <td />
            {model.series.map((series, j) => (
              <th key={j} scope="col">
                {nameOf(series)}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {model.groups.map((group, i) => (
            <tr key={`${group.label}-${i}`}>
              <th scope="row">{group.label}</th>
              {group.values.map((value, j) => (
                <td key={j}>{model.value.format(value)}</td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </figure>
  );
}
