import {
  type CSSProperties,
  type KeyboardEvent,
  type PointerEvent,
  type ReactNode,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { areaPath, bands, barPath, donutSegments, linePath, linearScale, nearestIndex, niceTicks, reveal } from '@nabuxai/ui-core';
import { cx, useBehavior, useWidth } from '../internal/hooks';
import { useLocale, useT } from '../internal/provider';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const seriesColor = (i: number) => `var(--nx-chart-${(i % 7) + 1})`;

export interface Series {
  name: string;
  values: number[];
}

interface ChartFrameProps {
  title?: ReactNode;
  subtitle?: ReactNode;
  /** Visible heading, and the caption of the data table assistive tech reads. */
  labels: string[];
  series: Series[];
  format?: Intl.NumberFormatOptions;
  locale?: string;
  height?: number;
  className?: string;
}

function useFormat(locale: string | undefined, format?: Intl.NumberFormatOptions) {
  const language = useLocale();
  const intl = locale ?? INTL[language];
  return useMemo(() => {
    const value = new Intl.NumberFormat(intl, format);
    const tick = new Intl.NumberFormat(intl, { notation: 'compact', maximumFractionDigits: 1, ...(format?.style === 'currency' ? { style: 'currency', currency: format.currency } : null) });
    const percent = new Intl.NumberFormat(intl, { style: 'percent', maximumFractionDigits: 0 });
    return { value: (n: number) => value.format(n), tick: (n: number) => tick.format(n), percent: (n: number) => percent.format(n) };
  }, [intl, format]);
}

/** Legend (only for two or more series — a single series is named by the title). */
function Legend({ series, shape }: { series: Series[]; shape: 'rect' | 'line' }) {
  if (series.length < 2) return null;
  return (
    <ul className="nx-chart-legend">
      {series.map((s, i) => (
        <li key={s.name} className="nx-legend-item">
          <span className="nx-legend-swatch" data-shape={shape} style={{ '--nx-series': seriesColor(i) } as CSSProperties} />
          {s.name}
        </li>
      ))}
    </ul>
  );
}

/** The same numbers as a table, for screen readers and anyone who cannot use the chart. */
function DataTable({ caption, labels, series, format }: { caption?: ReactNode; labels: string[]; series: Series[]; format: (n: number) => string }) {
  return (
    <table className="nx-visually-hidden">
      {caption && <caption>{caption}</caption>}
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
          <tr key={label}>
            <th scope="row">{label}</th>
            {series.map((s) => (
              <td key={s.name}>{format(s.values[row] ?? 0)}</td>
            ))}
          </tr>
        ))}
      </tbody>
    </table>
  );
}

function Tooltip({ open, x, y, width, title, rows }: { open: boolean; x: number; y: number; width: number; title: string; rows: Array<{ name: string; value: string; color: string }> }) {
  // Keep the bubble inside the plot: clamp around the pointer's x.
  const clampedX = Math.min(Math.max(x, 70), Math.max(70, width - 70));
  return (
    <div
      className="nx-chart-tooltip"
      data-open={open ? '' : undefined}
      aria-hidden="true"
      style={{ '--nx-tx': `calc(${clampedX}px - 50%)`, '--nx-ty': `calc(${y}px - 100% - 12px)` } as CSSProperties}
    >
      <p className="nx-chart-tooltip-title">{title}</p>
      {rows.map((row) => (
        <div key={row.name} className="nx-chart-tooltip-row">
          <span className="nx-chart-tooltip-key" style={{ '--nx-series': row.color } as CSSProperties} />
          <strong>{row.value}</strong>
          <span>{row.name}</span>
        </div>
      ))}
    </div>
  );
}

/** Keyboard reading of a chart: arrows move between categories, Escape leaves. */
function useChartKeys(count: number, active: number | null, setActive: (i: number | null) => void) {
  return (event: KeyboardEvent) => {
    const rtl = false; // the plot is always drawn left to right
    if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
      event.preventDefault();
      const step = (event.key === 'ArrowRight') !== rtl ? 1 : -1;
      setActive(active === null ? 0 : Math.min(Math.max(active + step, 0), count - 1));
    } else if (event.key === 'Home') setActive(0);
    else if (event.key === 'End') setActive(count - 1);
    else if (event.key === 'Escape') setActive(null);
  };
}

const PAD = { top: 16, right: 12, bottom: 28, left: 44 };

/* ---- Bar chart ------------------------------------------------------------------- */

export interface BarChartProps extends Partial<Pick<ChartFrameProps, 'labels' | 'series'>>, Omit<ChartFrameProps, 'labels' | 'series'> {
  /** One series: [{ label, value }]. */
  data?: Array<{ label: string; value: number }>;
}

export function BarChart({ data, labels: labelsProp, series: seriesProp, title, subtitle, format, locale, height = 240, className }: BarChartProps) {
  const t = useT();
  const id = useId();
  const figure = useRef<HTMLElement>(null);
  const plot = useRef<HTMLDivElement>(null);
  const width = useWidth(plot, 560);
  const [active, setActive] = useState<number | null>(null);
  const fmt = useFormat(locale, format);
  useBehavior(figure, reveal, { once: true });

  const labels = labelsProp ?? data?.map((d) => d.label) ?? [];
  const series: Series[] = seriesProp ?? [{ name: typeof title === 'string' ? title : 'Value', values: data?.map((d) => d.value) ?? [] }];

  const all = series.flatMap((s) => s.values);
  const ticks = niceTicks(Math.min(0, ...all), Math.max(0, ...all), 4);
  const innerW = Math.max(0, width - PAD.left - PAD.right);
  const innerH = height - PAD.top - PAD.bottom;
  const y = linearScale([ticks[0]!, ticks[ticks.length - 1]!], [PAD.top + innerH, PAD.top]);
  const groups = bands(labels.length, innerW, { maxBar: 24 * series.length + 2 * (series.length - 1), fill: 0.7 });
  const barWidth = (group: { width: number }) => (group.width - 2 * (series.length - 1)) / series.length;

  const onMove = (event: PointerEvent<HTMLDivElement>) => {
    const rect = event.currentTarget.getBoundingClientRect();
    const x = event.clientX - rect.left - PAD.left;
    const index = Math.floor(x / (innerW / Math.max(1, labels.length)));
    setActive(index >= 0 && index < labels.length ? index : null);
  };

  const tooltipY = active === null ? 0 : y(Math.max(...series.map((s) => s.values[active] ?? 0)));

  return (
    <figure ref={figure} className={cx('nx-chart', className)} data-type="bar" data-nx-reveal="" data-active={active !== null ? '' : undefined} aria-labelledby={title ? `${id}-title` : undefined}>
      {(title || series.length > 1) && (
        <div className="nx-chart-head">
          <div>
            {title && <p className="nx-chart-title" id={`${id}-title`}>{title}</p>}
            {subtitle && <p className="nx-chart-subtitle">{subtitle}</p>}
          </div>
          <Legend series={series} shape="rect" />
        </div>
      )}
      <div
        ref={plot}
        className="nx-chart-plot"
        tabIndex={0}
        aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${t('chartHint')}`}
        onPointerMove={onMove}
        onPointerLeave={() => setActive(null)}
        onKeyDown={useChartKeys(labels.length, active, setActive)}
        onBlur={() => setActive(null)}
      >
        <svg className="nx-chart-svg" width={width} height={height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true">
          {ticks.map((tick) => (
            <g key={tick}>
              <line className={tick === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1={PAD.left} x2={width - PAD.right} y1={y(tick)} y2={y(tick)} />
              <text className="nx-chart-tick" x={PAD.left - 8} y={y(tick)} dy="0.32em" textAnchor="end">
                {fmt.tick(tick)}
              </text>
            </g>
          ))}
          {groups.map((group, index) => (
            <g key={labels[index]} transform={`translate(${PAD.left},0)`}>
              {series.map((s, si) => {
                const w = barWidth(group);
                const x = group.x + si * (w + 2);
                return (
                  <path
                    key={s.name}
                    className="nx-chart-bar"
                    d={barPath(x, w, y(s.values[index] ?? 0), y(0), 4)}
                    data-active={active === index ? '' : undefined}
                    style={{ '--nx-series': seriesColor(si), '--nx-i': index } as CSSProperties}
                  />
                );
              })}
              <text className="nx-chart-tick" x={group.center} y={height - 8} textAnchor="middle">
                {labels[index]}
              </text>
            </g>
          ))}
        </svg>
        <Tooltip
          open={active !== null}
          x={active === null ? 0 : PAD.left + groups[active]!.center}
          y={tooltipY}
          width={width}
          title={active === null ? '' : labels[active]!}
          rows={active === null ? [] : series.map((s, i) => ({ name: s.name, value: fmt.value(s.values[active] ?? 0), color: seriesColor(i) }))}
        />
      </div>
      <DataTable caption={title} labels={labels} series={series} format={fmt.value} />
    </figure>
  );
}

/* ---- Area / line chart ---------------------------------------------------------- */

export interface AreaChartProps extends ChartFrameProps {
  /** A wash under each line (area) or the lines alone. */
  variant?: 'area' | 'line';
  smooth?: boolean;
}

export function AreaChart({ labels, series, title, subtitle, format, locale, height = 260, variant = 'area', smooth = true, className }: AreaChartProps) {
  const t = useT();
  const id = useId().replace(/:/g, '');
  const figure = useRef<HTMLElement>(null);
  const plot = useRef<HTMLDivElement>(null);
  const width = useWidth(plot, 560);
  const [active, setActive] = useState<number | null>(null);
  const fmt = useFormat(locale, format);
  useBehavior(figure, reveal, { once: true });

  const all = series.flatMap((s) => s.values);
  const ticks = niceTicks(Math.min(0, ...all), Math.max(...all), 4);
  const innerH = height - PAD.top - PAD.bottom;
  const x = linearScale([0, Math.max(1, labels.length - 1)], [PAD.left, width - PAD.right]);
  const y = linearScale([ticks[0]!, ticks[ticks.length - 1]!], [PAD.top + innerH, PAD.top]);
  const xs = labels.map((_, i) => x(i));
  const lines = series.map((s) => s.values.map((v, i) => [x(i), y(v)] as const));

  const onMove = (event: PointerEvent<HTMLDivElement>) => {
    const rect = event.currentTarget.getBoundingClientRect();
    setActive(nearestIndex(xs, event.clientX - rect.left));
  };

  // Label only a few ticks along x so they never collide.
  const every = Math.max(1, Math.ceil(labels.length / Math.max(2, Math.floor(width / 90))));

  return (
    <figure ref={figure} className={cx('nx-chart', className)} data-type={variant} data-nx-reveal="" aria-labelledby={title ? `${id}-title` : undefined}>
      {(title || series.length > 1) && (
        <div className="nx-chart-head">
          <div>
            {title && <p className="nx-chart-title" id={`${id}-title`}>{title}</p>}
            {subtitle && <p className="nx-chart-subtitle">{subtitle}</p>}
          </div>
          <Legend series={series} shape={variant === 'area' ? 'rect' : 'line'} />
        </div>
      )}
      <div
        ref={plot}
        className="nx-chart-plot"
        tabIndex={0}
        aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${t('chartHint')}`}
        onPointerMove={onMove}
        onPointerLeave={() => setActive(null)}
        onKeyDown={useChartKeys(labels.length, active, setActive)}
        onBlur={() => setActive(null)}
      >
        <svg className="nx-chart-svg" width={width} height={height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true">
          <defs>
            {series.map((s, i) => (
              <linearGradient key={s.name} id={`${id}-fill-${i}`} x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stopColor={seriesColor(i)} stopOpacity={0.22} />
                <stop offset="100%" stopColor={seriesColor(i)} stopOpacity={0.01} />
              </linearGradient>
            ))}
          </defs>
          {ticks.map((tick) => (
            <g key={tick}>
              <line className={tick === ticks[0] ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1={PAD.left} x2={width - PAD.right} y1={y(tick)} y2={y(tick)} />
              <text className="nx-chart-tick" x={PAD.left - 8} y={y(tick)} dy="0.32em" textAnchor="end">
                {fmt.tick(tick)}
              </text>
            </g>
          ))}
          {labels.map((label, i) =>
            i % every === 0 || i === labels.length - 1 ? (
              <text key={label} className="nx-chart-tick" x={xs[i]} y={height - 8} textAnchor={i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle'}>
                {label}
              </text>
            ) : null,
          )}
          {variant === 'area' &&
            lines.map((points, i) => <path key={`a${i}`} className="nx-chart-area" d={areaPath(points, y(ticks[0]!), smooth)} fill={`url(#${id}-fill-${i})`} style={{ '--nx-series': seriesColor(i) } as CSSProperties} />)}
          {lines.map((points, i) => (
            <path key={`l${i}`} className="nx-chart-line" d={linePath(points, smooth)} pathLength={1} style={{ '--nx-series': seriesColor(i), '--nx-i': i } as CSSProperties} />
          ))}
          <line className="nx-chart-crosshair" data-active={active !== null ? '' : undefined} x1={active === null ? 0 : xs[active]} x2={active === null ? 0 : xs[active]} y1={PAD.top} y2={PAD.top + innerH} />
          {lines.map((points, i) =>
            points.map(([px, py], pi) => (
              <circle
                key={`p${i}-${pi}`}
                className="nx-chart-point"
                cx={px}
                cy={py}
                r={4}
                data-active={active === pi ? '' : undefined}
                data-end={pi === points.length - 1 ? '' : undefined}
                style={{ '--nx-series': seriesColor(i) } as CSSProperties}
              />
            )),
          )}
        </svg>
        <Tooltip
          open={active !== null}
          x={active === null ? 0 : xs[active]!}
          y={active === null ? 0 : Math.min(...lines.map((points) => points[active]?.[1] ?? height))}
          width={width}
          title={active === null ? '' : labels[active]!}
          rows={active === null ? [] : series.map((s, i) => ({ name: s.name, value: fmt.value(s.values[active] ?? 0), color: seriesColor(i) }))}
        />
      </div>
      <DataTable caption={title} labels={labels} series={series} format={fmt.value} />
    </figure>
  );
}

/* ---- Donut ------------------------------------------------------------------------ */

export interface DonutChartProps {
  data: Array<{ label: string; value: number }>;
  title?: ReactNode;
  /** Shown in the centre; the total by default. */
  centerLabel?: ReactNode;
  format?: Intl.NumberFormatOptions;
  locale?: string;
  size?: string;
  thickness?: number;
  className?: string;
}

export function DonutChart({ data, title, centerLabel, format, locale, size, thickness = 14, className }: DonutChartProps) {
  const figure = useRef<HTMLElement>(null);
  const [active, setActive] = useState<number | null>(null);
  const fmt = useFormat(locale, format);
  const segments = useMemo(() => donutSegments(data.map((d) => d.value)), [data]);
  const total = data.reduce((sum, d) => sum + d.value, 0);
  const radius = 50 - thickness / 2 - 4;
  useBehavior(figure, reveal, { once: true });

  const shown = active === null ? { value: fmt.value(total), label: centerLabel ?? title } : { value: fmt.value(data[active]!.value), label: data[active]!.label };
  const series = data.map((d) => ({ name: d.label, values: [d.value] }));

  return (
    <figure ref={figure} className={cx('nx-chart', className)} data-type="donut" data-nx-reveal="">
      <div className="nx-donut" style={{ ...(size ? { '--nx-donut-size': size } : null), '--nx-donut-thickness': thickness } as CSSProperties}>
        <svg className="nx-chart-svg" viewBox="0 0 100 100" aria-hidden="true" onPointerLeave={() => setActive(null)}>
          {segments.map((segment, i) => (
            <circle
              key={data[i]!.label}
              className="nx-donut-segment"
              cx="50"
              cy="50"
              r={radius}
              pathLength={100}
              data-active={active === i ? '' : undefined}
              onPointerEnter={() => setActive(i)}
              style={{ '--nx-series': seriesColor(i), '--nx-len': segment.length, '--nx-offset': segment.offset, '--nx-i': i } as CSSProperties}
            />
          ))}
        </svg>
        <div className="nx-donut-center" aria-hidden="true">
          <span className="nx-donut-value">{shown.value}</span>
          {shown.label && <span className="nx-donut-label">{shown.label}</span>}
        </div>
      </div>
      <ul className="nx-chart-legend" style={{ justifyContent: 'center' }}>
        {data.map((d, i) => (
          <li key={d.label} className="nx-legend-item" onPointerEnter={() => setActive(i)} onPointerLeave={() => setActive(null)}>
            <span className="nx-legend-swatch" style={{ '--nx-series': seriesColor(i) } as CSSProperties} />
            {d.label} · {fmt.percent(segments[i]!.share)}
          </li>
        ))}
      </ul>
      <DataTable caption={title} labels={['']} series={series} format={fmt.value} />
    </figure>
  );
}

/* ---- Sparkline --------------------------------------------------------------------- */

export interface SparklineProps {
  data: number[];
  /** Colours the line: rising (good) or falling (bad). */
  trend?: 'up' | 'down';
  area?: boolean;
  className?: string;
}

export function Sparkline({ data, trend, area = true, className }: SparklineProps) {
  const ref = useRef<SVGSVGElement>(null);
  const width = 120;
  const height = 40;
  const min = Math.min(...data);
  const max = Math.max(...data);
  const x = linearScale([0, Math.max(1, data.length - 1)], [2, width - 4]);
  const y = linearScale([min, max === min ? min + 1 : max], [height - 3, 3]);
  const points = data.map((v, i) => [x(i), y(v)] as const);
  const last = points[points.length - 1];
  useBehavior(ref, reveal, { once: true });

  return (
    <svg ref={ref} className={cx('nx-sparkline', 'nx-chart', className)} data-nx-reveal="" data-trend={trend} viewBox={`0 0 ${width} ${height}`} preserveAspectRatio="none" aria-hidden="true">
      {area && <path className="nx-chart-area" d={areaPath(points, height, true)} />}
      <path className="nx-chart-line" d={linePath(points, true)} pathLength={1} />
      {last && <circle className="nx-chart-point" cx={last[0]} cy={last[1]} r={3} data-end="" />}
    </svg>
  );
}

/* ---- Progress ring -------------------------------------------------------------- */

export interface ProgressRingProps {
  /** 0–100. */
  value: number;
  label?: string;
  /** Text in the middle; the rounded percentage by default. */
  children?: ReactNode;
  size?: string;
  thickness?: string;
  tone?: 'accent' | 'success' | 'warning' | 'danger';
  className?: string;
}

export function ProgressRing({ value, label, children, size, thickness, tone, className }: ProgressRingProps) {
  const ref = useRef<HTMLDivElement>(null);
  const clamped = Math.min(100, Math.max(0, value));
  useBehavior(ref, reveal, { once: true });

  return (
    <div
      ref={ref}
      className={cx('nx-ring', className)}
      data-nx-reveal=""
      style={{ ...(size ? { '--nx-ring-size': size } : null), ...(thickness ? { '--nx-ring-thickness': thickness } : null) } as CSSProperties}
    >
      <progress className="nx-ring-progress" value={clamped} max={100} aria-label={label} data-tone={tone === 'accent' ? undefined : tone} style={{ '--nx-value': clamped } as CSSProperties} />
      <span className="nx-ring-label" aria-hidden="true">
        {children ?? `${Math.round(clamped)}%`}
      </span>
    </div>
  );
}
