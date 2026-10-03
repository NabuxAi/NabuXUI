/**
 * Charts plus (React): stacked area, stacked / percent / grouped bars,
 * composed bars + line on two axes, a brush-zoomed line, forecast with a
 * confidence band, radar, radial bars and the loading skeleton
 * (css/blocks/charts-plus.css).
 *
 * The geometry is the core's (js/blocks/charts-plus.ts), shared with the
 * Alpine charts so both draw identical marks. Every chart ships its data as a
 * visually-hidden table, reads with the arrow keys, and wears the frosted
 * tooltip. Legend toggles restack with a tween (interruptible, instant under
 * reduced motion).
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent,
  type PointerEvent,
  type ReactNode,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import {
  type ChartCurve,
  type ChartFill,
  type ChartRange,
  type ChartStack,
  type ChartsPlusWord,
  type StackMode,
  brushClamp,
  brushEdge,
  brushWindow,
  chartBrush,
  chartColor,
  chartExtremes,
  chartFillDefs,
  chartFillPaint,
  chartPad,
  chartStack,
  chartTween,
  chartsPlusWord,
  composedModel,
  forecastModel,
  isRtlLocale,
  lerpChartStack,
  lineModel,
  nearestIndex,
  radarModel,
  radialBarModel,
  reveal,
  stackedAreaModel,
  stackedBarModel,
} from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useWidth } from '../internal/hooks';
import { useLocale, useT } from '../internal/provider';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

export interface PlusSeries {
  name: string;
  values: number[];
}

type FigureProps = Omit<HTMLAttributes<HTMLElement>, 'title' | 'children' | 'defaultValue' | 'onChange'>;

interface ChromeProps {
  title?: ReactNode;
  subtitle?: ReactNode;
  /** Intl.NumberFormat options for values. */
  format?: Intl.NumberFormatOptions;
  /** Digits and words (the provider's language by default). */
  locale?: string;
  /** Override any built-in word. */
  words?: Partial<Record<ChartsPlusWord, string>>;
}

/* ---- Shared plumbing ------------------------------------------------------------------------ */

function useChartTools(locale: string | undefined, format: Intl.NumberFormatOptions | undefined, words?: Partial<Record<ChartsPlusWord, string>>) {
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const key = JSON.stringify(format ?? null);
  return useMemo(() => {
    const value = new Intl.NumberFormat(intl, format);
    const tick = new Intl.NumberFormat(intl, { notation: 'compact', maximumFractionDigits: 1, ...(format?.style === 'currency' ? { style: 'currency', currency: format.currency } : null) });
    const pct = new Intl.NumberFormat(intl, { style: 'percent', maximumFractionDigits: 0 });
    return {
      intl,
      rtl: isRtlLocale(intl),
      value: (n: number) => value.format(n),
      tick: (n: number) => tick.format(n),
      percent: (n: number) => pct.format(n / 100),
      word: (k: ChartsPlusWord, params?: Record<string, string | number>) => words?.[k] ?? chartsPlusWord(intl, k, params),
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [intl, key, JSON.stringify(words ?? null)]);
}

function useChartId() {
  return `nxc${useId().replace(/[^a-zA-Z0-9]/g, '')}`;
}

function Head({ id, title, subtitle, children }: { id: string; title?: ReactNode; subtitle?: ReactNode; children?: ReactNode }) {
  if (!title && !subtitle && !children) return null;
  return (
    <div className="nx-chart-head">
      <div>
        {title && (
          <p className="nx-chart-title" id={`${id}-title`}>
            {title}
          </p>
        )}
        {subtitle && <p className="nx-chart-subtitle">{subtitle}</p>}
      </div>
      {children}
    </div>
  );
}

function Toggles({
  names,
  hidden,
  onToggle,
  label,
  fill,
  shape = 'rect',
}: {
  names: string[];
  hidden: number[];
  onToggle: (i: number) => void;
  label: string;
  fill?: ChartFill;
  shape?: 'rect' | 'line';
}) {
  if (names.length < 2) return null;
  return (
    <ul className="nx-chart-legend" aria-label={label}>
      {names.map((name, i) => (
        <li key={`${name}-${i}`}>
          <button type="button" className="nx-chart-toggle" aria-pressed={!hidden.includes(i)} onClick={() => onToggle(i)}>
            <span className="nx-legend-swatch" data-shape={shape} data-fill={fill === 'hatched' || fill === 'duotone' ? fill : undefined} style={vars({ '--nx-series': chartColor(i) })} />
            {name}
          </button>
        </li>
      ))}
    </ul>
  );
}

/** Hidden series by index; never lets the last visible one go. */
function useHidden(names: string[], hidden?: string[], defaultHidden?: string[], onHiddenChange?: (names: string[]) => void) {
  const [value, setValue] = useControllable(hidden, defaultHidden ?? [], onHiddenChange);
  const indices = names.map((n, i) => (value.includes(n) ? i : -1)).filter((i) => i >= 0);
  const toggle = (i: number) => {
    const name = names[i]!;
    const off = value.includes(name);
    if (!off && indices.length >= names.length - 1) return;
    setValue(off ? value.filter((n) => n !== name) : [...value, name]);
  };
  return [indices, toggle] as const;
}

interface TipRow {
  name: string;
  value: string;
  color?: string;
  shape?: 'line' | 'rect' | 'dash';
  total?: boolean;
}

function Tip({ open, x, y, width, title, rows, below = false }: { open: boolean; x: number; y: number; width: number; title: string; rows: TipRow[]; below?: boolean }) {
  const clampedX = Math.min(Math.max(x, 76), Math.max(76, width - 76));
  return (
    <div
      className="nx-chart-tooltip"
      data-frost=""
      data-open={open ? '' : undefined}
      aria-hidden="true"
      style={vars({ '--nx-tx': `calc(${clampedX}px - 50%)`, '--nx-ty': below ? `${y + 12}px` : `calc(${y}px - 100% - 12px)` })}
    >
      <p className="nx-chart-tooltip-title">{title}</p>
      {rows.map((row, i) => (
        <div key={`${row.name}-${i}`} className="nx-chart-tooltip-row" data-total={row.total ? '' : undefined}>
          <span className="nx-chart-tooltip-key" data-shape={row.shape} style={vars({ '--nx-series': row.color })} />
          <strong>{row.value}</strong>
          <span>{row.name}</span>
        </div>
      ))}
    </div>
  );
}

function DataTable({ caption, head, rows }: { caption?: ReactNode; head: string[]; rows: Array<[string, ...string[]]> }) {
  return (
    <table className="nx-visually-hidden">
      {caption && <caption>{caption}</caption>}
      <thead>
        <tr>
          <td />
          {head.map((h, i) => (
            <th key={`${h}-${i}`} scope="col">
              {h}
            </th>
          ))}
        </tr>
      </thead>
      <tbody>
        {rows.map(([label, ...cells], r) => (
          <tr key={`${label}-${r}`}>
            <th scope="row">{label}</th>
            {cells.map((c, i) => (
              <td key={i}>{c}</td>
            ))}
          </tr>
        ))}
      </tbody>
    </table>
  );
}

/** Arrow keys walk the categories (mirrored plots walk the other way), Escape leaves. */
function chartKeys(count: number, active: number | null, setActive: (i: number | null) => void, mirrored = false) {
  return (event: KeyboardEvent) => {
    if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
      event.preventDefault();
      const step = (event.key === 'ArrowRight') !== mirrored ? 1 : -1;
      setActive(active === null ? (step > 0 ? 0 : count - 1) : Math.min(Math.max(active + step, 0), count - 1));
    } else if (event.key === 'Home') setActive(0);
    else if (event.key === 'End') setActive(count - 1);
    else if (event.key === 'Escape') setActive(null);
  };
}

/** A stack that tweens toward its target (interruptible; instant under reduced motion). */
function useTweenedStack(target: ChartStack): ChartStack {
  const [shown, setShown] = useState(target);
  const current = useRef(target);
  const key = JSON.stringify([target.lower, target.upper, target.domain]);
  const last = useRef(key);
  useEffect(() => {
    if (last.current === key) return;
    last.current = key;
    const from = current.current;
    if (from.upper.length !== target.upper.length || (from.upper[0]?.length ?? 0) !== (target.upper[0]?.length ?? 0)) {
      current.current = target;
      setShown(target);
      return;
    }
    return chartTween(560, (t) => {
      const next = lerpChartStack(from, target, t);
      current.current = next;
      setShown(next);
    });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [key]);
  return shown;
}

function XLabels({ labels, xs, y, every }: { labels: string[]; xs: number[]; y: number; every: number }) {
  return (
    <>
      {labels.map((label, i) =>
        (i % every === 0 && labels.length - 1 - i >= every * 0.6) || i === labels.length - 1 ? (
          <text key={`${label}-${i}`} className="nx-chart-tick" x={xs[i]} y={y} textAnchor={i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle'}>
            {label}
          </text>
        ) : null,
      )}
    </>
  );
}

function Grid({ ticks, x0, x1, label, baseline }: { ticks: Array<{ value: number; at: number }>; x0: number; x1: number; label: (v: number) => string; baseline?: number }) {
  return (
    <>
      {ticks.map((tick) => (
        <g key={tick.value}>
          <line className={tick.value === (baseline ?? ticks[0]?.value) ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1={x0} x2={x1} y1={tick.at} y2={tick.at} />
          <text className="nx-chart-tick" x={x0 - 8} y={tick.at} dy="0.32em" textAnchor="end">
            {label(tick.value)}
          </text>
        </g>
      ))}
    </>
  );
}

function Ping({ x, y, color }: { x: number; y: number; color: string }) {
  return (
    <g style={vars({ '--nx-series': color })}>
      <circle className="nx-chart-ping-ring" cx={x} cy={y} r={4.5} />
      <circle className="nx-chart-ping" cx={x} cy={y} r={4.5} />
    </g>
  );
}

/** A max / min marker: a ringed dot and a pill naming the value, kept inside the plot. */
function Marker({ x, y, kind, value, color, x0, x1, below }: { x: number; y: number; kind: string; value: string; color: string; x0: number; x1: number; below?: boolean }) {
  const width = Math.round((kind.length + value.length) * 6.2 + 18);
  const left = Math.min(Math.max(x - width / 2, x0), x1 - width);
  const top = below ? y + 9 : y - 27;
  return (
    <g className="nx-chart-marker" style={vars({ '--nx-series': color })} aria-hidden="true">
      <circle cx={x} cy={y} r={4} />
      <rect x={left} y={top} width={width} height={18} rx={9} />
      <text x={left + width / 2} y={top + 12.5} textAnchor="middle">
        <tspan className="nx-chart-marker-kind">{kind}</tspan> {value}
      </text>
    </g>
  );
}

function Defs({ id, count, fill, area }: { id: string; count: number; fill: ChartFill; area: boolean }) {
  const html = useMemo(() => chartFillDefs(id, count, fill, area), [id, count, fill, area]);
  return <defs dangerouslySetInnerHTML={{ __html: html }} />;
}

/* ---- Stacked area ------------------------------------------------------------------------------ */

export interface StackedAreaChartProps extends ChromeProps, FigureProps {
  labels: string[];
  series: PlusSeries[];
  /** stacked (absolute) · percent (each column = 100%) · expanded (a streamgraph centred on zero). */
  mode?: StackMode;
  curve?: ChartCurve;
  fill?: ChartFill;
  height?: number;
  /** Series names switched off (controlled) / initially (uncontrolled). */
  hiddenSeries?: string[];
  defaultHiddenSeries?: string[];
  onHiddenSeriesChange?: (hidden: string[]) => void;
  /** A ping on the latest value of the top layer. */
  ping?: boolean;
  /** Max / min markers on the column totals. */
  markers?: boolean;
}

export function StackedAreaChart({
  labels,
  series,
  mode = 'stacked',
  curve = 'smooth',
  fill = 'gradient',
  height = 280,
  hiddenSeries,
  defaultHiddenSeries,
  onHiddenSeriesChange,
  ping = true,
  markers = false,
  title,
  subtitle,
  format,
  locale,
  words,
  className,
  ...rest
}: StackedAreaChartProps) {
  const t = useT();
  const id = useChartId();
  const figure = useRef<HTMLElement>(null);
  const plot = useRef<HTMLDivElement>(null);
  const width = useWidth(plot, 560);
  const [active, setActive] = useState<number | null>(null);
  const tools = useChartTools(locale, format, words);
  const names = series.map((s) => s.name);
  const [off, toggle] = useHidden(names, hiddenSeries, defaultHiddenSeries, onHiddenSeriesChange);
  useBehavior(figure, reveal, { once: true });

  const valuesKey = JSON.stringify(series.map((s) => s.values));
  const target = useMemo(
    () => chartStack(series.map((s) => s.values), mode, series.map((_, i) => (off.includes(i) ? 0 : 1))),
    // eslint-disable-next-line react-hooks/exhaustive-deps
    [valuesKey, mode, off.join(',')],
  );
  const stack = useTweenedStack(target);
  const m = stackedAreaModel(stack, labels.length, width, height, curve);
  const visible = series.map((_, i) => i).filter((i) => !off.includes(i));
  const topLayer = visible[visible.length - 1] ?? 0;
  const tickLabel = (v: number) => (mode === 'percent' ? tools.percent(v) : tools.tick(Math.abs(v)));
  const { max, min } = chartExtremes(target.totals);

  const onMove = (event: PointerEvent<HTMLDivElement>) => {
    const rect = event.currentTarget.getBoundingClientRect();
    setActive(nearestIndex(m.xs, event.clientX - rect.left));
  };

  const rows: TipRow[] =
    active === null
      ? []
      : [
          ...visible
            .slice()
            .reverse()
            .map((i) => ({
              name: series[i]!.name,
              value: mode === 'percent' ? `${tools.percent((stack.upper[i]![active] ?? 0) - (stack.lower[i]![active] ?? 0))} · ${tools.value(series[i]!.values[active] ?? 0)}` : tools.value(series[i]!.values[active] ?? 0),
              color: chartColor(i),
              shape: 'rect' as const,
            })),
          { name: tools.word('total'), value: tools.value(visible.reduce((sum, i) => sum + Math.max(0, series[i]!.values[active] ?? 0), 0)), total: true },
        ];

  const mark = (index: number, kind: 'max' | 'min') => {
    const y = stackedAreaModel(target, labels.length, width, height, curve).tops[index]!;
    return (
      <Marker
        key={kind}
        x={m.xs[index]!}
        y={y}
        kind={tools.word(kind)}
        value={tools.value(target.totals[index] ?? 0)}
        color={chartColor(topLayer)}
        x0={m.plot.x0}
        x1={m.plot.x1}
        below={kind === 'min' && y < m.plot.y0 + 30}
      />
    );
  };

  return (
    <figure
      ref={figure}
      className={cx('nx-chart nx-stacked-area', className)}
      data-type="area"
      data-mode={mode}
      data-nx-reveal=""
      aria-labelledby={title ? `${id}-title` : undefined}
      {...rest}
    >
      <Head id={id} title={title} subtitle={subtitle}>
        <Toggles names={names} hidden={off} onToggle={toggle} label={tools.word('legend')} fill={fill} />
      </Head>
      <div
        ref={plot}
        className="nx-chart-plot"
        tabIndex={0}
        aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${t('chartHint')}`}
        onPointerMove={onMove}
        onPointerLeave={() => setActive(null)}
        onKeyDown={chartKeys(labels.length, active, setActive)}
        onBlur={() => setActive(null)}
      >
        <svg className="nx-chart-svg" width={width} height={height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true">
          <Defs id={id} count={series.length} fill={fill} area />
          <Grid ticks={m.ticks} x0={m.plot.x0} x1={m.plot.x1} label={tickLabel} baseline={mode === 'expanded' ? 0 : undefined} />
          <XLabels labels={labels} xs={m.xs} y={height - 8} every={m.every} />
          {m.layers.map((layer, i) => (
            <path key={`a${i}`} className="nx-chart-layer" data-fill={fill} d={layer.area} fill={chartFillPaint(id, i, fill)} style={vars({ '--nx-i': i })} />
          ))}
          {m.layers.map((layer, i) =>
            off.includes(i) && stack.upper[i]!.every((v, k) => Math.abs(v - (stack.lower[i]![k] ?? 0)) < 0.01) ? null : (
              <path key={`l${i}`} className="nx-chart-line" d={layer.line} pathLength={1} style={vars({ '--nx-series': chartColor(i), '--nx-i': i })} />
            ),
          )}
          <line className="nx-chart-crosshair" data-active={active !== null ? '' : undefined} x1={active === null ? 0 : m.xs[active]} x2={active === null ? 0 : m.xs[active]} y1={m.plot.y0} y2={m.plot.y1} />
          {active !== null &&
            visible.map((i) => (
              <circle key={`p${i}`} className="nx-chart-point" data-active="" cx={m.xs[active]} cy={m.layers[i]!.top[active]![1]} r={4} style={vars({ '--nx-series': chartColor(i) })} />
            ))}
          {markers && max && mark(max.index, 'max')}
          {markers && min && min.index !== max?.index && mark(min.index, 'min')}
          {ping && labels.length > 0 && <Ping x={m.xs[labels.length - 1]!} y={m.layers[topLayer]!.top[labels.length - 1]![1]} color={chartColor(topLayer)} />}
        </svg>
        <Tip open={active !== null} x={active === null ? 0 : m.xs[active]!} y={active === null ? 0 : m.tops[active]!} width={width} title={active === null ? '' : labels[active]!} rows={rows} />
      </div>
      <DataTable caption={title} head={[...names, tools.word('total')]} rows={labels.map((label, r) => [label, ...series.map((s) => tools.value(s.values[r] ?? 0)), tools.value(series.reduce((sum, s) => sum + Math.max(0, s.values[r] ?? 0), 0))] as [string, ...string[]])} />
    </figure>
  );
}

/* ---- Stacked / percent / grouped bars ------------------------------------------------------------ */

export interface StackedBarChartProps extends ChromeProps, FigureProps {
  labels: string[];
  series: PlusSeries[];
  /** stacked · percent (each bar = 100%) · grouped (side by side). */
  mode?: 'stacked' | 'percent' | 'grouped';
  orientation?: 'vertical' | 'horizontal';
  fill?: ChartFill;
  height?: number;
  /** The total at the end of each stack. */
  totals?: boolean;
  hiddenSeries?: string[];
  defaultHiddenSeries?: string[];
  onHiddenSeriesChange?: (hidden: string[]) => void;
}

export function StackedBarChart({
  labels,
  series,
  mode = 'stacked',
  orientation = 'vertical',
  fill = 'solid',
  height: heightProp,
  totals = false,
  hiddenSeries,
  defaultHiddenSeries,
  onHiddenSeriesChange,
  title,
  subtitle,
  format,
  locale,
  words,
  className,
  ...rest
}: StackedBarChartProps) {
  const t = useT();
  const id = useChartId();
  const figure = useRef<HTMLElement>(null);
  const plot = useRef<HTMLDivElement>(null);
  const width = useWidth(plot, 560);
  const [active, setActive] = useState<number | null>(null);
  const tools = useChartTools(locale, format, words);
  const names = series.map((s) => s.name);
  const [off, toggle] = useHidden(names, hiddenSeries, defaultHiddenSeries, onHiddenSeriesChange);
  useBehavior(figure, reveal, { once: true });

  const horizontal = orientation === 'horizontal';
  const height = heightProp ?? (horizontal ? Math.max(160, labels.length * 40 + 40) : 260);
  const values = series.map((s) => s.values);
  const weights = series.map((_, i) => (off.includes(i) ? 0 : 1));
  const valuesKey = JSON.stringify(values);
  const target = useMemo(
    () => chartStack(values, mode === 'percent' ? 'percent' : 'stacked', weights),
    // eslint-disable-next-line react-hooks/exhaustive-deps
    [valuesKey, mode, off.join(',')],
  );
  const stack = useTweenedStack(target);
  const m = stackedBarModel({ stack: mode === 'grouped' ? null : stack, values, weights, count: labels.length, width, height, orientation, rtl: tools.rtl && horizontal });
  const tickLabel = (v: number) => (mode === 'percent' ? tools.percent(v) : tools.tick(v));
  const visible = series.map((_, i) => i).filter((i) => !off.includes(i));
  const total = (index: number) => visible.reduce((sum, i) => sum + Math.max(0, series[i]!.values[index] ?? 0), 0);

  const onMove = (event: PointerEvent<HTMLDivElement>) => {
    const rect = event.currentTarget.getBoundingClientRect();
    setActive(nearestIndex(m.centers, horizontal ? event.clientY - rect.top : event.clientX - rect.left));
  };
  const onKeys = (event: KeyboardEvent) => {
    if (horizontal && (event.key === 'ArrowDown' || event.key === 'ArrowUp')) {
      event.preventDefault();
      const step = event.key === 'ArrowDown' ? 1 : -1;
      setActive(active === null ? 0 : Math.min(Math.max(active + step, 0), labels.length - 1));
      return;
    }
    chartKeys(labels.length, active, setActive)(event);
  };

  const rows: TipRow[] =
    active === null
      ? []
      : [
          ...visible
            .slice()
            .reverse()
            .map((i) => {
              const raw = series[i]!.values[active] ?? 0;
              const share = total(active) > 0 ? (Math.max(0, raw) / total(active)) * 100 : 0;
              return { name: series[i]!.name, value: mode === 'percent' ? `${tools.percent(share)} · ${tools.value(raw)}` : tools.value(raw), color: chartColor(i), shape: 'rect' as const };
            }),
          ...(mode === 'grouped' ? [] : [{ name: tools.word('total'), value: tools.value(total(active)), total: true }]),
        ];

  const hit = active === null ? null : m.hits[active]!;
  const rtl = tools.rtl && horizontal;

  return (
    <figure
      ref={figure}
      className={cx('nx-chart nx-stacked-bar', className)}
      data-type="bar"
      data-mode={mode}
      data-orientation={orientation}
      data-rtl={rtl ? '' : undefined}
      data-active={active !== null ? '' : undefined}
      data-nx-reveal=""
      aria-labelledby={title ? `${id}-title` : undefined}
      {...rest}
    >
      <Head id={id} title={title} subtitle={subtitle}>
        <Toggles names={names} hidden={off} onToggle={toggle} label={tools.word('legend')} fill={fill} />
      </Head>
      <div
        ref={plot}
        className="nx-chart-plot"
        tabIndex={0}
        aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${t('chartHint')}`}
        onPointerMove={onMove}
        onPointerLeave={() => setActive(null)}
        onKeyDown={onKeys}
        onBlur={() => setActive(null)}
      >
        <svg className="nx-chart-svg" width={width} height={height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true">
          <Defs id={id} count={series.length} fill={fill} area={false} />
          {horizontal
            ? m.ticks.map((tick, i) => (
                <g key={tick.value}>
                  <line className={i === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1={tick.at} x2={tick.at} y1={m.plot.y0} y2={m.plot.y1} />
                  <text className="nx-chart-tick" x={tick.at} y={height - 8} textAnchor="middle">
                    {tickLabel(tick.value)}
                  </text>
                </g>
              ))
            : <Grid ticks={m.ticks} x0={m.plot.x0} x1={m.plot.x1} label={tickLabel} />}
          {m.hits.map((h, i) => (
            <rect key={`h${i}`} className="nx-chart-hover" data-active={active === i ? '' : undefined} x={h.x} y={h.y} width={h.w} height={h.h} rx={6} />
          ))}
          {labels.map((label, index) => (
            <g key={`c${index}`} className="nx-chart-col" data-active={active === index ? '' : undefined} style={vars({ '--nx-i': index })}>
              {m.bars
                .filter((b) => b.index === index)
                .map((b) => (
                  <path key={b.series} className="nx-chart-seg" d={b.d} fill={chartFillPaint(id, b.series, fill)} />
                ))}
              {totals && mode !== 'grouped' && (
                <text
                  className="nx-chart-seg-label"
                  x={horizontal ? m.hits[index]!.tipX + (rtl ? -6 : 6) : m.centers[index]}
                  y={horizontal ? m.centers[index] : m.hits[index]!.tipY - 6}
                  dy={horizontal ? '0.32em' : undefined}
                  textAnchor={horizontal ? (rtl ? 'end' : 'start') : 'middle'}
                >
                  {mode === 'percent' ? tools.value(total(index)) : tools.tick(total(index))}
                </text>
              )}
            </g>
          ))}
          {labels.map((label, i) =>
            horizontal ? (
              <text key={`n${i}`} className="nx-chart-cat" x={rtl ? m.plot.x1 + 10 : m.plot.x0 - 10} y={m.centers[i]} dy="0.32em" textAnchor={rtl ? 'start' : 'end'}>
                {label}
              </text>
            ) : (
              <text key={`n${i}`} className="nx-chart-tick" x={m.centers[i]} y={height - 8} textAnchor="middle">
                {label}
              </text>
            ),
          )}
        </svg>
        <Tip open={hit !== null} x={hit ? hit.tipX : 0} y={hit ? (horizontal ? hit.tipY - 14 : hit.tipY) : 0} width={width} title={active === null ? '' : labels[active]!} rows={rows} />
      </div>
      <DataTable
        caption={title}
        head={[...names, ...(mode === 'grouped' ? [] : [tools.word('total')])]}
        rows={labels.map((label, r) => [label, ...series.map((s) => tools.value(s.values[r] ?? 0)), ...(mode === 'grouped' ? [] : [tools.value(series.reduce((sum, s) => sum + Math.max(0, s.values[r] ?? 0), 0))])] as [string, ...string[]])}
      />
    </figure>
  );
}

/* ---- Composed: bars + line, two axes ------------------------------------------------------------- */

export interface ComposedChartProps extends ChromeProps, FigureProps {
  labels: string[];
  /** Bars, on the primary (inline-start) axis. */
  bars: PlusSeries[];
  /** Lines, on the secondary (inline-end) axis; colours continue after the bars. */
  lines: PlusSeries[];
  /** Number format of the line axis (the bars use `format`). */
  lineFormat?: Intl.NumberFormatOptions;
  /** Axis titles. */
  barAxis?: string;
  lineAxis?: string;
  curve?: ChartCurve;
  height?: number;
  /** Max / min markers on the first line. */
  markers?: boolean;
  ping?: boolean;
  /** Force a direction; the locale's by default (RTL puts the primary axis on the right). */
  dir?: 'ltr' | 'rtl';
}

export function ComposedChart({
  labels,
  bars,
  lines,
  lineFormat,
  barAxis,
  lineAxis,
  curve = 'smooth',
  height = 280,
  markers = false,
  ping = true,
  dir,
  title,
  subtitle,
  format,
  locale,
  words,
  className,
  ...rest
}: ComposedChartProps) {
  const t = useT();
  const id = useChartId();
  const figure = useRef<HTMLElement>(null);
  const plot = useRef<HTMLDivElement>(null);
  const width = useWidth(plot, 560);
  const [active, setActive] = useState<number | null>(null);
  const tools = useChartTools(locale, format, words);
  const lineTools = useChartTools(locale, lineFormat ?? format, words);
  useBehavior(figure, reveal, { once: true });

  const rtl = dir ? dir === 'rtl' : tools.rtl;
  const m = composedModel({ bars: bars.map((s) => s.values), lines: lines.map((s) => s.values), count: labels.length, width, height, rtl, curve });
  const lineColor = (i: number) => chartColor(bars.length + i);
  const first = lines[0];
  const { max, min } = chartExtremes(first?.values ?? []);

  const onMove = (event: PointerEvent<HTMLDivElement>) => {
    const rect = event.currentTarget.getBoundingClientRect();
    setActive(nearestIndex(m.centers, event.clientX - rect.left));
  };

  const rows: TipRow[] =
    active === null
      ? []
      : [
          ...bars.map((s, i) => ({ name: s.name, value: tools.value(s.values[active] ?? 0), color: chartColor(i), shape: 'rect' as const })),
          ...lines.map((s, i) => ({ name: s.name, value: lineTools.value(s.values[active] ?? 0), color: lineColor(i), shape: 'line' as const })),
        ];

  const axis = (side: typeof m.primary, label: (v: number) => string, key: string) =>
    side.ticks.map((tick) => (
      <text key={`${key}${tick.value}`} className="nx-chart-tick" x={side.x} y={tick.at} dy="0.32em" textAnchor={side.anchor}>
        {label(tick.value)}
      </text>
    ));

  return (
    <figure
      ref={figure}
      className={cx('nx-chart nx-composed-chart', className)}
      data-type="composed"
      data-active={active !== null ? '' : undefined}
      data-nx-reveal=""
      aria-labelledby={title ? `${id}-title` : undefined}
      {...rest}
    >
      <Head id={id} title={title} subtitle={subtitle}>
        <ul className="nx-chart-legend">
          {bars.map((s, i) => (
            <li key={`b${i}`} className="nx-legend-item">
              <span className="nx-legend-swatch" style={vars({ '--nx-series': chartColor(i) })} />
              {s.name}
            </li>
          ))}
          {lines.map((s, i) => (
            <li key={`l${i}`} className="nx-legend-item">
              <span className="nx-legend-swatch" data-shape="line" style={vars({ '--nx-series': lineColor(i) })} />
              {s.name}
            </li>
          ))}
        </ul>
      </Head>
      <div
        ref={plot}
        className="nx-chart-plot"
        tabIndex={0}
        aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${t('chartHint')}`}
        onPointerMove={onMove}
        onPointerLeave={() => setActive(null)}
        onKeyDown={chartKeys(labels.length, active, setActive, rtl)}
        onBlur={() => setActive(null)}
      >
        <svg className="nx-chart-svg" width={width} height={height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true">
          {m.primary.ticks.map((tick, i) => (
            <line key={`g${tick.value}`} className={i === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1={m.plot.x0} x2={m.plot.x1} y1={tick.at} y2={tick.at} />
          ))}
          {axis(m.primary, tools.tick, 'p')}
          {axis(m.secondary, lineTools.tick, 's')}
          {barAxis && (
            <text className="nx-chart-axis-title" x={m.primary.x} y={m.plot.y0 - 6} textAnchor={m.primary.anchor}>
              {barAxis}
            </text>
          )}
          {lineAxis && (
            <text className="nx-chart-axis-title" x={m.secondary.x} y={m.plot.y0 - 6} textAnchor={m.secondary.anchor}>
              {lineAxis}
            </text>
          )}
          {m.hits.map((h, i) => (
            <rect key={`h${i}`} className="nx-chart-hover" data-active={active === i ? '' : undefined} x={h.x} y={m.plot.y0} width={h.w} height={m.plot.y1 - m.plot.y0} rx={6} />
          ))}
          {labels.map((label, index) => (
            <g key={`c${index}`} className="nx-chart-col" data-active={active === index ? '' : undefined} style={vars({ '--nx-i': index })}>
              {m.bars
                .filter((b) => b.index === index)
                .map((b) => (
                  <path key={b.series} className="nx-chart-seg" d={b.d} fill={chartColor(b.series)} />
                ))}
            </g>
          ))}
          {labels.map((label, i) => (
            <text key={`n${i}`} className="nx-chart-tick" x={m.centers[i]} y={height - 8} textAnchor="middle">
              {label}
            </text>
          ))}
          {m.lines.map((line, i) => (
            <path key={`l${i}`} className="nx-chart-line" d={line.d} pathLength={1} style={vars({ '--nx-series': lineColor(i), '--nx-i': i + 2 })} />
          ))}
          {m.lines.map((line, i) =>
            line.points.map(([x, y], k) => <circle key={`d${i}-${k}`} className="nx-chart-dot" cx={x} cy={y} r={3} data-active={active === k ? '' : undefined} style={vars({ '--nx-series': lineColor(i) })} />),
          )}
          {markers && first && max && (
            <Marker x={m.lines[0]!.points[max.index]![0]} y={m.lines[0]!.points[max.index]![1]} kind={tools.word('max')} value={lineTools.value(max.value)} color={lineColor(0)} x0={m.plot.x0} x1={m.plot.x1} />
          )}
          {markers && first && min && min.index !== max?.index && (
            <Marker x={m.lines[0]!.points[min.index]![0]} y={m.lines[0]!.points[min.index]![1]} kind={tools.word('min')} value={lineTools.value(min.value)} color={lineColor(0)} x0={m.plot.x0} x1={m.plot.x1} below />
          )}
          {ping && first && labels.length > 0 && <Ping x={m.lines[0]!.points[labels.length - 1]![0]} y={m.lines[0]!.points[labels.length - 1]![1]} color={lineColor(0)} />}
        </svg>
        <Tip open={active !== null} x={active === null ? 0 : m.centers[active]!} y={active === null ? 0 : m.tops[active]!} width={width} title={active === null ? '' : labels[active]!} rows={rows} />
      </div>
      <DataTable
        caption={title}
        head={[...bars.map((s) => s.name), ...lines.map((s) => s.name)]}
        rows={labels.map((label, r) => [label, ...bars.map((s) => tools.value(s.values[r] ?? 0)), ...lines.map((s) => lineTools.value(s.values[r] ?? 0))] as [string, ...string[]])}
      />
    </figure>
  );
}

/* ---- Brush: a line / area zoomed by a range selector ------------------------------------------------ */

export interface BrushChartProps extends ChromeProps, FigureProps {
  labels: string[];
  series: PlusSeries[];
  variant?: 'area' | 'line';
  curve?: ChartCurve;
  /** The selected index range [start, end], inclusive. */
  value?: ChartRange;
  defaultValue?: ChartRange;
  onValueChange?: (range: ChartRange) => void;
  /** The narrowest window, in steps. */
  minSpan?: number;
  height?: number;
  /** The brush strip's height (px). */
  brushHeight?: number;
  markers?: boolean;
}

export function BrushChart({
  labels,
  series,
  variant = 'area',
  curve = 'smooth',
  value,
  defaultValue,
  onValueChange,
  minSpan = 2,
  height = 240,
  brushHeight = 56,
  markers = true,
  title,
  subtitle,
  format,
  locale,
  words,
  className,
  ...rest
}: BrushChartProps) {
  const t = useT();
  const id = useChartId();
  const figure = useRef<HTMLElement>(null);
  const plot = useRef<HTMLDivElement>(null);
  const handle = useRef<HTMLButtonElement>(null);
  const width = useWidth(plot, 560);
  const [active, setActive] = useState<number | null>(null);
  const tools = useChartTools(locale, format, words);
  const count = labels.length;
  const fallback: ChartRange = defaultValue ?? [Math.max(0, count - Math.max(minSpan + 1, Math.ceil(count / 3))), count - 1];
  const [rawRange, setRange] = useControllable(value, fallback, onValueChange);
  const range = brushClamp(rawRange, count, minSpan);
  const rangeRef = useRef(range);
  rangeRef.current = range;
  useBehavior(figure, reveal, { once: true });

  useEffect(() => {
    const el = handle.current;
    if (!el) return;
    return chartBrush(el, { count, get: () => rangeRef.current, onChange: (next) => setRange(next) });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [count]);

  const [a, b] = range;
  const slice = series.map((s) => s.values.slice(a, b + 1));
  const sliceLabels = labels.slice(a, b + 1);
  const m = lineModel(slice, width, height, { curve });
  const track = Math.max(0, width - chartPad.left - chartPad.right);
  const mini = lineModel(
    series.map((s) => s.values),
    track,
    brushHeight,
    { pad: { top: 6, bottom: 4, left: 0, right: 0 }, curve },
  );
  const win = brushWindow(range, count, track);
  const every = Math.max(1, Math.ceil(sliceLabels.length / Math.max(2, Math.floor(width / 90))));
  const ext = chartExtremes(slice[0] ?? []);

  const onMove = (event: PointerEvent<HTMLDivElement>) => {
    const rect = event.currentTarget.getBoundingClientRect();
    setActive(nearestIndex(m.xs, event.clientX - rect.left));
  };

  const fromTo = tools.word('rangeValue', { from: labels[a] ?? '', to: labels[b] ?? '' });

  return (
    <figure
      ref={figure}
      className={cx('nx-chart nx-brush-chart', className)}
      data-type={variant}
      data-nx-reveal=""
      aria-labelledby={title ? `${id}-title` : undefined}
      {...rest}
    >
      <Head id={id} title={title} subtitle={subtitle}>
        {series.length > 1 && (
          <ul className="nx-chart-legend">
            {series.map((s, i) => (
              <li key={`${s.name}-${i}`} className="nx-legend-item">
                <span className="nx-legend-swatch" data-shape={variant === 'line' ? 'line' : 'rect'} style={vars({ '--nx-series': chartColor(i) })} />
                {s.name}
              </li>
            ))}
          </ul>
        )}
      </Head>
      <div
        ref={plot}
        className="nx-chart-plot"
        tabIndex={0}
        aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${fromTo}. ${t('chartHint')}`}
        onPointerMove={onMove}
        onPointerLeave={() => setActive(null)}
        onKeyDown={chartKeys(sliceLabels.length, active, setActive)}
        onBlur={() => setActive(null)}
      >
        <svg className="nx-chart-svg" width={width} height={height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true">
          <Defs id={id} count={series.length} fill="gradient" area />
          <Grid ticks={m.ticks} x0={m.plot.x0} x1={m.plot.x1} label={tools.tick} />
          <XLabels labels={sliceLabels} xs={m.xs} y={height - 8} every={every} />
          {variant === 'area' && m.series.map((s, i) => <path key={`a${i}`} className="nx-chart-area" d={s.area} style={{ fill: chartFillPaint(id, i, 'gradient'), fillOpacity: 1 }} />)}
          {m.series.map((s, i) => (
            <path key={`l${i}`} className="nx-chart-line" d={s.line} pathLength={1} style={vars({ '--nx-series': chartColor(i), '--nx-i': i })} />
          ))}
          <line className="nx-chart-crosshair" data-active={active !== null ? '' : undefined} x1={active === null ? 0 : m.xs[active]} x2={active === null ? 0 : m.xs[active]} y1={m.plot.y0} y2={m.plot.y1} />
          {active !== null && m.series.map((s, i) => <circle key={`p${i}`} className="nx-chart-point" data-active="" cx={s.points[active]?.[0]} cy={s.points[active]?.[1]} r={4} style={vars({ '--nx-series': chartColor(i) })} />)}
          {markers && ext.max && (
            <Marker x={m.series[0]!.points[ext.max.index]![0]} y={m.series[0]!.points[ext.max.index]![1]} kind={tools.word('max')} value={tools.value(ext.max.value)} color={chartColor(0)} x0={m.plot.x0} x1={m.plot.x1} />
          )}
          {markers && ext.min && ext.min.index !== ext.max?.index && (
            <Marker x={m.series[0]!.points[ext.min.index]![0]} y={m.series[0]!.points[ext.min.index]![1]} kind={tools.word('min')} value={tools.value(ext.min.value)} color={chartColor(0)} x0={m.plot.x0} x1={m.plot.x1} below />
          )}
        </svg>
        <Tip
          open={active !== null}
          x={active === null ? 0 : m.xs[active]!}
          y={active === null ? 0 : Math.min(...m.series.map((s) => s.points[active]?.[1] ?? height))}
          width={width}
          title={active === null ? '' : sliceLabels[active]!}
          rows={active === null ? [] : series.map((s, i) => ({ name: s.name, value: tools.value(slice[i]![active] ?? 0), color: chartColor(i) }))}
        />
      </div>
      <div className="nx-brush" data-brush-track="" style={vars({ '--nx-brush-height': `${brushHeight}px` })}>
        <svg className="nx-brush-svg" viewBox={`0 0 ${Math.max(1, track)} ${brushHeight}`} preserveAspectRatio="none" aria-hidden="true">
          {mini.series.map((s, i) => (
            <g key={i} style={vars({ '--nx-series': chartColor(i) })}>
              <path className="nx-chart-area" d={s.area} />
              <path className="nx-chart-line" d={s.line} />
            </g>
          ))}
        </svg>
        <span className="nx-brush-shade" data-edge="start" style={{ width: `${win.start}px` }} />
        <span className="nx-brush-shade" data-edge="end" style={{ width: `${Math.max(0, track - win.start - win.size)}px` }} />
        <button ref={handle} type="button" className="nx-brush-window" aria-label={`${tools.word('moveRange')}: ${fromTo}`} style={{ left: `${win.start}px`, width: `${win.size}px` }} />
        <input
          className="nx-brush-input"
          type="range"
          min={0}
          max={Math.max(0, count - 1)}
          step={1}
          value={a}
          aria-label={tools.word('rangeStart')}
          aria-valuetext={labels[a]}
          onChange={(event) => setRange(brushEdge(range, 'start', Number(event.currentTarget.value), count, minSpan))}
        />
        <input
          className="nx-brush-input"
          type="range"
          min={0}
          max={Math.max(0, count - 1)}
          step={1}
          value={b}
          aria-label={tools.word('rangeEnd')}
          aria-valuetext={labels[b]}
          onChange={(event) => setRange(brushEdge(range, 'end', Number(event.currentTarget.value), count, minSpan))}
        />
      </div>
      <p className="nx-brush-readout">
        <span>{tools.word('range')}</span>
        <output aria-live="polite">{fromTo}</output>
      </p>
      <DataTable caption={title} head={series.map((s) => s.name)} rows={labels.map((label, r) => [label, ...series.map((s) => tools.value(s.values[r] ?? 0))] as [string, ...string[]])} />
    </figure>
  );
}

/* ---- Forecast: actual vs projected, with a confidence band ------------------------------------------- */

export interface ForecastChartProps extends ChromeProps, FigureProps {
  /** All labels: the actual ones, then the forecast ones. */
  labels: string[];
  actual: number[];
  /** Values after the last actual one. */
  forecast: number[];
  /** The confidence band, aligned with `forecast`. */
  lower?: number[];
  upper?: number[];
  /** Marching dashes on the forecast line. */
  animated?: boolean;
  curve?: ChartCurve;
  height?: number;
  markers?: boolean;
  ping?: boolean;
  /** The pill on the "today" line; null hides it. */
  todayLabel?: ReactNode;
}

export function ForecastChart({
  labels,
  actual,
  forecast,
  lower,
  upper,
  animated = false,
  curve = 'smooth',
  height = 280,
  markers = true,
  ping = true,
  todayLabel,
  title,
  subtitle,
  format,
  locale,
  words,
  className,
  ...rest
}: ForecastChartProps) {
  const t = useT();
  const id = useChartId();
  const figure = useRef<HTMLElement>(null);
  const plot = useRef<HTMLDivElement>(null);
  const width = useWidth(plot, 560);
  const [active, setActive] = useState<number | null>(null);
  const tools = useChartTools(locale, format, words);
  useBehavior(figure, reveal, { once: true });

  const m = forecastModel({ actual, forecast, lower, upper, width, height, curve });
  const k = actual.length;
  const ext = chartExtremes(actual);
  const every = Math.max(1, Math.ceil(labels.length / Math.max(2, Math.floor(width / 90))));
  const today = todayLabel === undefined ? tools.word('today') : todayLabel;
  const todayText = typeof today === 'string' ? today : '';
  const pillW = Math.round(todayText.length * 6.4 + 16);
  const color = chartColor(0);

  const onMove = (event: PointerEvent<HTMLDivElement>) => {
    const rect = event.currentTarget.getBoundingClientRect();
    setActive(nearestIndex(m.xs, event.clientX - rect.left));
  };

  const rowsAt = (i: number): TipRow[] => {
    if (i < k) return [{ name: tools.word('actual'), value: tools.value(actual[i] ?? 0), color, shape: 'line' }];
    const j = i - k;
    const out: TipRow[] = [{ name: tools.word('forecast'), value: tools.value(forecast[j] ?? 0), color, shape: 'dash' }];
    if (lower && upper) out.push({ name: `${tools.word('low')} – ${tools.word('high')}`, value: `${tools.value(lower[j] ?? 0)} – ${tools.value(upper[j] ?? 0)}`, total: true });
    return out;
  };
  const point = (i: number) => (i < k ? m.actual.points[i] : m.forecast.points[i - k + 1]);

  return (
    <figure
      ref={figure}
      className={cx('nx-chart nx-forecast', className)}
      data-type="line"
      data-nx-reveal=""
      aria-labelledby={title ? `${id}-title` : undefined}
      {...rest}
    >
      <Head id={id} title={title} subtitle={subtitle}>
        <ul className="nx-chart-legend">
          <li className="nx-legend-item">
            <span className="nx-legend-swatch" data-shape="line" style={vars({ '--nx-series': color })} />
            {tools.word('actual')}
          </li>
          <li className="nx-legend-item">
            <span className="nx-legend-swatch" data-shape="dash" style={vars({ '--nx-series': color })} />
            {tools.word('forecast')}
          </li>
        </ul>
      </Head>
      <div
        ref={plot}
        className="nx-chart-plot"
        tabIndex={0}
        aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${t('chartHint')}`}
        onPointerMove={onMove}
        onPointerLeave={() => setActive(null)}
        onKeyDown={chartKeys(labels.length, active, setActive)}
        onBlur={() => setActive(null)}
      >
        <svg className="nx-chart-svg" width={width} height={height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true" style={vars({ '--nx-series': color })}>
          <Defs id={id} count={1} fill="gradient" area />
          <rect className="nx-chart-future" x={m.today} y={m.plot.y0} width={Math.max(0, m.plot.x1 - m.today)} height={m.plot.y1 - m.plot.y0} rx={4} />
          <Grid ticks={m.ticks} x0={m.plot.x0} x1={m.plot.x1} label={tools.tick} />
          <XLabels labels={labels} xs={m.xs} y={height - 8} every={every} />
          {m.band && <path className="nx-chart-band" d={m.band} />}
          <path className="nx-chart-area" d={m.actual.area} style={{ fill: chartFillPaint(id, 0, 'gradient'), fillOpacity: 1 }} />
          <path className="nx-chart-line" d={m.actual.line} pathLength={1} />
          <path className="nx-chart-forecast" d={m.forecast.line} data-animate={animated ? '' : undefined} />
          <g className="nx-chart-today">
            <line x1={m.today} x2={m.today} y1={m.plot.y0} y2={m.plot.y1} />
            {todayText && (
              <>
                <rect x={m.today - pillW / 2} y={m.plot.y0 - 14} width={pillW} height={17} rx={8.5} />
                <text x={m.today} y={m.plot.y0 - 2} textAnchor="middle">
                  {todayText}
                </text>
              </>
            )}
          </g>
          <line className="nx-chart-crosshair" data-active={active !== null ? '' : undefined} x1={active === null ? 0 : m.xs[active]} x2={active === null ? 0 : m.xs[active]} y1={m.plot.y0} y2={m.plot.y1} />
          {active !== null && point(active) && <circle className="nx-chart-point" data-active="" cx={point(active)![0]} cy={point(active)![1]} r={4} />}
          {markers && ext.max && <Marker x={m.actual.points[ext.max.index]![0]} y={m.actual.points[ext.max.index]![1]} kind={tools.word('max')} value={tools.value(ext.max.value)} color={color} x0={m.plot.x0} x1={m.plot.x1} />}
          {markers && ext.min && ext.min.index !== ext.max?.index && (
            <Marker x={m.actual.points[ext.min.index]![0]} y={m.actual.points[ext.min.index]![1]} kind={tools.word('min')} value={tools.value(ext.min.value)} color={color} x0={m.plot.x0} x1={m.plot.x1} below />
          )}
          {ping && k > 0 && <Ping x={m.actual.points[k - 1]![0]} y={m.actual.points[k - 1]![1]} color={color} />}
        </svg>
        <Tip open={active !== null} x={active === null ? 0 : m.xs[active]!} y={active === null ? 0 : m.tops[active]!} width={width} title={active === null ? '' : labels[active]!} rows={active === null ? [] : rowsAt(active)} />
      </div>
      <DataTable
        caption={title}
        head={[tools.word('actual'), tools.word('forecast'), ...(lower && upper ? [tools.word('low'), tools.word('high')] : [])]}
        rows={labels.map((label, r) => {
          const j = r - k;
          return [label, r < k ? tools.value(actual[r] ?? 0) : '', r >= k ? tools.value(forecast[j] ?? 0) : '', ...(lower && upper ? [r >= k ? tools.value(lower[j] ?? 0) : '', r >= k ? tools.value(upper[j] ?? 0) : ''] : [])] as [string, ...string[]];
        })}
      />
    </figure>
  );
}

/* ---- Radar ------------------------------------------------------------------------------------------ */

export interface RadarChartProps extends ChromeProps, FigureProps {
  /** One name per spoke. */
  axes: string[];
  series: PlusSeries[];
  /** The rim's value (a nice round number above the data by default). */
  max?: number;
  rings?: number;
  /** Drawing size in px (the chart scales down with its container). */
  size?: number;
  hiddenSeries?: string[];
  defaultHiddenSeries?: string[];
  onHiddenSeriesChange?: (hidden: string[]) => void;
  dir?: 'ltr' | 'rtl';
}

export function RadarChart({
  axes,
  series,
  max,
  rings = 4,
  size = 340,
  hiddenSeries,
  defaultHiddenSeries,
  onHiddenSeriesChange,
  dir,
  title,
  subtitle,
  format,
  locale,
  words,
  className,
  ...rest
}: RadarChartProps) {
  const t = useT();
  const id = useChartId();
  const figure = useRef<HTMLElement>(null);
  const [active, setActive] = useState<number | null>(null);
  const tools = useChartTools(locale, format, words);
  const names = series.map((s) => s.name);
  const [off, toggle] = useHidden(names, hiddenSeries, defaultHiddenSeries, onHiddenSeriesChange);
  const plot = useRef<HTMLDivElement>(null);
  const svg = useRef<SVGSVGElement>(null);
  const plotWidth = useWidth(plot, size);
  const svgWidth = useWidth(svg, size);
  useBehavior(figure, reveal, { once: true });

  const clockwise = !(dir ? dir === 'rtl' : tools.rtl);
  const m = useMemo(() => radarModel(series.map((s) => s.values), axes.length, size, { max, rings, clockwise }), [series, axes.length, size, max, rings, clockwise]);

  const onMove = (event: PointerEvent<SVGSVGElement>) => {
    const rect = event.currentTarget.getBoundingClientRect();
    const scale = size / (rect.width || size);
    const dx = (event.clientX - rect.left) * scale - m.cx;
    const dy = (event.clientY - rect.top) * scale - m.cy;
    if (Math.hypot(dx, dy) < 8) return;
    let angle = (Math.atan2(dx, -dy) * 180) / Math.PI;
    if (!clockwise) angle = -angle;
    const n = axes.length;
    setActive(((Math.round(((angle + 360) % 360) / (360 / n)) % n) + n) % n);
  };

  const visible = series.map((_, i) => i).filter((i) => !off.includes(i));
  // The tooltip lives in plot pixels: map the active spoke's tip through the svg's rendered scale.
  const scale = svgWidth / size;
  const offset = (plotWidth - svgWidth) / 2;
  const tip = active === null ? null : m.spokes[active]!;
  const tipBelow = tip ? tip[3] < m.cy : false;

  return (
    <figure ref={figure} className={cx('nx-chart nx-radar', className)} data-type="radar" data-nx-reveal="" aria-labelledby={title ? `${id}-title` : undefined} {...rest}>
      <Head id={id} title={title} subtitle={subtitle}>
        <Toggles names={names} hidden={off} onToggle={toggle} label={tools.word('legend')} />
      </Head>
      <div
        ref={plot}
        className="nx-chart-plot"
        tabIndex={0}
        aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${t('chartHint')}`}
        onKeyDown={(event) => {
          const n = axes.length;
          if (['ArrowRight', 'ArrowDown', 'ArrowLeft', 'ArrowUp'].includes(event.key)) {
            event.preventDefault();
            const step = event.key === 'ArrowRight' || event.key === 'ArrowDown' ? 1 : -1;
            setActive(active === null ? 0 : (active + step + n) % n);
          } else if (event.key === 'Escape') setActive(null);
        }}
        onBlur={() => setActive(null)}
      >
        <svg ref={svg} className="nx-chart-svg" viewBox={`0 0 ${size} ${size}`} aria-hidden="true" onPointerMove={onMove} onPointerLeave={() => setActive(null)}>
          <g>
            {m.rings.map((d, i) => (
              <path key={`r${i}`} className="nx-radar-ring" d={d} />
            ))}
          </g>
          {m.spokes.map(([x1, y1, x2, y2], i) => (
            <line key={`s${i}`} className="nx-radar-spoke" data-active={active === i ? '' : undefined} x1={x1} y1={y1} x2={x2} y2={y2} />
          ))}
          {m.labels.map((l, i) => (
            <text key={`t${i}`} className="nx-radar-label" data-active={active === i ? '' : undefined} x={l.x} y={l.y} dy="0.32em" textAnchor={l.anchor}>
              {axes[i]}
            </text>
          ))}
          {m.shapes.map((shape, i) => (
            <path key={`p${i}`} className="nx-radar-shape" d={shape.d} data-off={off.includes(i) ? '' : undefined} style={vars({ '--nx-series': chartColor(i), '--nx-i': i })} />
          ))}
          {active !== null &&
            visible.map((i) => (
              <circle key={`d${i}`} className="nx-radar-dot" data-active="" cx={m.shapes[i]!.points[active]![0]} cy={m.shapes[i]!.points[active]![1]} r={4} style={vars({ '--nx-series': chartColor(i) })} />
            ))}
        </svg>
        <Tip
          open={tip !== null}
          x={tip ? offset + tip[2] * scale : 0}
          y={tip ? tip[3] * scale : 0}
          width={plotWidth}
          title={active === null ? '' : axes[active]!}
          rows={active === null ? [] : visible.map((i) => ({ name: series[i]!.name, value: tools.value(series[i]!.values[active] ?? 0), color: chartColor(i), shape: 'rect' as const }))}
          below={tipBelow}
        />
      </div>
      <DataTable caption={title} head={names} rows={axes.map((axis, r) => [axis, ...series.map((s) => tools.value(s.values[r] ?? 0))] as [string, ...string[]])} />
    </figure>
  );
}

/* ---- Radial bars --------------------------------------------------------------------------------------- */

export interface RadialBarDatum {
  label: string;
  value: number;
  /** This ring's full scale (the chart's `max` by default). */
  max?: number;
  /** A small note under the label. */
  hint?: string;
}

export interface RadialBarChartProps extends ChromeProps, FigureProps {
  data: RadialBarDatum[];
  max?: number;
  /** Degrees of arc each ring sweeps (270 leaves a gap at the bottom; 360 closes it). */
  sweep?: number;
  /** The centre's reading when nothing is hovered (the average share by default). */
  centerValue?: ReactNode;
  centerLabel?: ReactNode;
  /** The dial's width (CSS length). */
  size?: string;
  dir?: 'ltr' | 'rtl';
}

export function RadialBarChart({ data, max = 100, sweep = 270, centerValue, centerLabel, size, dir, title, subtitle, format, locale, words, className, style, ...rest }: RadialBarChartProps) {
  const id = useChartId();
  const figure = useRef<HTMLElement>(null);
  const [active, setActive] = useState<number | null>(null);
  const tools = useChartTools(locale, format, words);
  useBehavior(figure, reveal, { once: true });

  const clockwise = !(dir ? dir === 'rtl' : tools.rtl);
  const maxes = data.map((d) => d.max ?? max);
  const m = radialBarModel(
    data.map((d) => d.value),
    maxes,
    { sweep, clockwise },
  );
  const average = m.rings.reduce((sum, r) => sum + r.share, 0) / Math.max(1, m.rings.length);
  const shown =
    active === null
      ? { value: centerValue ?? tools.percent(average), label: centerLabel ?? tools.word('share') }
      : { value: tools.percent(m.rings[active]!.share), label: data[active]!.label };

  return (
    <figure
      ref={figure}
      className={cx('nx-chart nx-radial-bar', className)}
      data-type="radial"
      data-active={active !== null ? '' : undefined}
      data-nx-reveal=""
      aria-labelledby={title ? `${id}-title` : undefined}
      style={{ ...style, ...(size ? vars({ '--nx-radial-size': size }) : null) }}
      {...rest}
    >
      <Head id={id} title={title} subtitle={subtitle} />
      <div className="nx-radial-bar-body">
        <div className="nx-radial-bar-dial" onPointerLeave={() => setActive(null)}>
          <svg className="nx-chart-svg" viewBox="0 0 100 100" aria-hidden="true" style={vars({ '--_t': m.thickness })}>
            {m.rings.map((ring, i) => (
              <path key={`t${i}`} className="nx-radial-track" d={ring.track} />
            ))}
            {m.rings.map((ring, i) => (
              <path
                key={`v${i}`}
                className="nx-radial-value"
                d={ring.track}
                pathLength={100}
                data-empty={ring.share <= 0 ? '' : undefined}
                data-active={active === i ? '' : undefined}
                onPointerEnter={() => setActive(i)}
                style={vars({ '--nx-series': chartColor(i), '--_v': ring.share, '--nx-i': i })}
              />
            ))}
          </svg>
          <div className="nx-radial-bar-center" aria-hidden="true">
            <span className="nx-radial-bar-value">{shown.value}</span>
            {shown.label && <span className="nx-radial-bar-label">{shown.label}</span>}
          </div>
        </div>
        <ul className="nx-radial-bar-list">
          {data.map((d, i) => (
            <li key={`${d.label}-${i}`} className="nx-radial-bar-item" data-active={active === i ? '' : undefined} onPointerEnter={() => setActive(i)} onPointerLeave={() => setActive(null)}>
              <span className="nx-legend-swatch" style={vars({ '--nx-series': chartColor(i) })} />
              <span>{d.label}</span>
              <strong>{tools.word('of', { value: tools.value(d.value), max: tools.value(maxes[i]!) })}</strong>
              {d.hint && <small>{d.hint}</small>}
            </li>
          ))}
        </ul>
      </div>
      <DataTable
        caption={title}
        head={[tools.word('value'), tools.word('share')]}
        rows={data.map((d, i) => [d.label, tools.word('of', { value: tools.value(d.value), max: tools.value(maxes[i]!) }), tools.percent(m.rings[i]!.share)] as [string, ...string[]])}
      />
    </figure>
  );
}

/* ---- Loading skeleton --------------------------------------------------------------------------------- */

export interface ChartLoadingProps extends Omit<HTMLAttributes<HTMLDivElement>, 'title'> {
  variant?: 'line' | 'area' | 'bar' | 'donut';
  /** Plot height (CSS length). */
  height?: string;
  /** Read by screen readers instead of the built-in "Loading chart". */
  label?: string;
  /** Show the real title while the data loads (a bone otherwise). */
  title?: ReactNode;
  /** Number of legend bones. */
  legend?: number;
  locale?: string;
}

const LOADING_BARS = [46, 68, 38, 82, 58, 74, 52, 90, 64];

export function ChartLoading({ variant = 'line', height, label, title, legend = 2, locale, className, style, ...rest }: ChartLoadingProps) {
  const language = useLocale();
  const text = label ?? chartsPlusWord(locale ?? INTL[language], 'loading');
  return (
    <div
      role="status"
      aria-busy="true"
      aria-live="polite"
      className={cx('nx-chart-loading', className)}
      data-variant={variant}
      style={{ ...style, ...(height ? vars({ '--nx-chart-loading-height': height }) : null) }}
      {...rest}
    >
      <span className="nx-visually-hidden">{text}</span>
      <div className="nx-chart-loading-head" aria-hidden="true">
        <div className="nx-chart-loading-lines">
          {title ? <p className="nx-chart-title">{title}</p> : <span className="nx-chart-loading-bone" data-size="lg" style={vars({ '--_w': '9rem' })} />}
          <span className="nx-chart-loading-bone" style={vars({ '--_w': '6rem' })} />
        </div>
        {variant !== 'donut' && legend > 0 && (
          <div className="nx-chart-loading-legend">
            {Array.from({ length: legend }, (_, i) => (
              <span key={i} className="nx-chart-loading-bone" style={vars({ '--_w': '3.5rem' })} />
            ))}
          </div>
        )}
      </div>
      <div className="nx-chart-loading-plot" aria-hidden="true">
        {variant === 'donut' ? (
          <svg viewBox="0 0 100 100">
            <circle className="nx-chart-loading-ring" cx="50" cy="50" r="38" />
          </svg>
        ) : (
          <svg viewBox="0 0 300 120" preserveAspectRatio="none">
            {[20, 50, 80, 110].map((y) => (
              <line key={y} className="nx-chart-loading-grid" x1="0" x2="300" y1={y} y2={y} />
            ))}
            {variant === 'bar' ? (
              LOADING_BARS.map((h, i) => <rect key={i} className="nx-chart-loading-shape" x={12 + i * 32} y={110 - h} width="18" height={h} rx="3" />)
            ) : (
              <>
                {variant === 'area' && <path className="nx-chart-loading-wash" d="M0,84 C40,70 60,92 100,66 S160,40 200,52 S260,30 300,24 L300,120 L0,120 Z" />}
                <path className="nx-chart-loading-line" d="M0,84 C40,70 60,92 100,66 S160,40 200,52 S260,30 300,24" />
              </>
            )}
          </svg>
        )}
      </div>
    </div>
  );
}
