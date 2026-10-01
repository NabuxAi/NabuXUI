/**
 * DonutRing — the CSS donut chart block (React).
 *
 * Thin over css/blocks/donut-chart.css: every segment is a full-size circle
 * painted with a conic-gradient from its start angle as far as its share of the
 * turn, masked to the ring, and scaled by the registered --nx-donut-sweep
 * property — which transitions when the chart is revealed, so the donut fills
 * clockwise one segment after another. The centre rolls the main number (the
 * total, or your own `centerValue`), each legend row rolls its share of the
 * whole in the reader's digits, and the same data ships as a visually-hidden
 * table.
 *
 * The SVG chart primitive (`DonutChart` in components/chart) stays the tool for
 * dense/interactive plots; this block is the CSS one, mirrored by the Blade
 * `<x-nx::donut-chart>` component.
 */
import { type CSSProperties, type HTMLAttributes, type ReactNode, useId, useMemo, useRef } from 'react';
import { donutSegments, reveal } from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { useLocale, useT } from '../internal/provider';
import { NumberTicker } from '../components/text';

/** The Intl locale: the prop, or the provider's language. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/* ---- Types ---------------------------------------------------------------------------- */

export interface DonutRingDatum {
  /** Shown in the legend and the hidden table's row header. */
  label: string;
  /** The slice's size; negatives clamp to zero. */
  value: number;
}

export interface DonutRingProps extends Omit<HTMLAttributes<HTMLElement>, 'children' | 'title'> {
  /** `{ Web: 42, Mobile: 26 }` — or the explicit list, in order. */
  data: Record<string, number> | DonutRingDatum[];
  title?: ReactNode;
  subtitle?: ReactNode;
  /** The number in the centre; the total by default. */
  centerValue?: number;
  /** Under it; "Total" by default, `''` hides it. */
  centerLabel?: ReactNode;
  /** Ring size, px or any CSS length ("10rem"). */
  size?: number | string;
  /** Ring thickness: a share of the radius (14), or any CSS length ("2rem"). */
  thickness?: number | string;
  locale?: string;
  /** Number-format options for the values (decimals default to the data's own). */
  format?: Intl.NumberFormatOptions;
}

export function DonutRing({
  data,
  title,
  subtitle,
  centerValue,
  centerLabel,
  size,
  thickness,
  locale,
  format,
  className,
  style,
  ...rest
}: DonutRingProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const figure = useRef<HTMLElement>(null);
  const heading = `nx-donut-chart${useId().replace(/:/g, '')}`;
  useBehavior(figure, reveal, { once: true });

  const model = useMemo(() => {
    const items = Array.isArray(data)
      ? data.map((d) => ({ label: d.label, value: Math.max(0, d.value) }))
      : Object.entries(data).map(([label, value]) => ({ label, value: Math.max(0, value) }));
    // The same 0–100 turn the core's donutSegments cuts for the SVG donut.
    const segments = donutSegments(items.map((d) => d.value));
    const total = items.reduce((sum, d) => sum + d.value, 0);
    const decimals = items.some((d) => !Number.isInteger(d.value)) ? 1 : 0;
    return {
      items,
      segments,
      total,
      decimals,
      center: centerValue ?? total,
      value: new Intl.NumberFormat(intl, { maximumFractionDigits: decimals, ...format }),
      share: new Intl.NumberFormat(intl, { style: 'percent', maximumFractionDigits: 0 }),
    };
  }, [data, centerValue, intl, format]);

  const caption = centerLabel === undefined ? t('donutChartTotal') : centerLabel;

  return (
    <figure
      ref={figure}
      className={cx('nx-chart', 'nx-donut-chart', className)}
      aria-labelledby={title ? `${heading}-title` : undefined}
      data-nx-reveal=""
      style={{
        ...vars({
          '--nx-donut-chart-size': size === undefined ? undefined : typeof size === 'number' ? `${size}px` : size,
          '--nx-donut-chart-thickness': thickness === undefined ? undefined : typeof thickness === 'number' ? `${thickness}%` : thickness,
        }),
        ...style,
      }}
      {...rest}
    >
      {(title || subtitle) && (
        <div className="nx-chart-head">
          <div>
            {title && (
              <p className="nx-chart-title" id={`${heading}-title`}>
                {title}
              </p>
            )}
            {subtitle && <p className="nx-chart-subtitle">{subtitle}</p>}
          </div>
        </div>
      )}
      <div className="nx-donut-chart-frame">
        <div className="nx-donut-chart-ring" aria-hidden="true">
          <span className="nx-donut-chart-track" />
          {model.segments.map((segment, i) => (
            <span
              key={`${model.items[i]?.label}-${i}`}
              className="nx-donut-chart-segment"
              style={vars({
                '--_start': -segment.offset,
                '--_len': segment.length,
                '--nx-i': i,
                '--nx-series': `var(--nx-chart-${(i % 7) + 1})`,
              })}
            />
          ))}
          <div className="nx-donut-chart-center">
            <NumberTicker value={model.center} locale={intl} format={{ maximumFractionDigits: model.decimals, ...format }} className="nx-donut-chart-value" />
            {caption !== '' && <span className="nx-donut-chart-caption">{caption}</span>}
          </div>
        </div>
        <ul className="nx-chart-legend nx-donut-chart-legend">
          {model.items.map((item, i) => (
            <li key={`${item.label}-${i}`} className="nx-legend-item nx-donut-chart-row">
              <span className="nx-legend-swatch" style={vars({ '--nx-series': `var(--nx-chart-${(i % 7) + 1})` })} />
              <span className="nx-donut-chart-row-label">{item.label}</span>
              <span className="nx-donut-chart-row-value">{model.value.format(item.value)}</span>
              <NumberTicker value={model.segments[i]?.share ?? 0} locale={intl} format={{ style: 'percent', maximumFractionDigits: 0 }} className="nx-donut-chart-row-share" />
            </li>
          ))}
        </ul>
      </div>
      <table className="nx-visually-hidden">
        {title && <caption>{title}</caption>}
        <thead>
          <tr>
            <td />
            <th scope="col">{t('donutChartValue')}</th>
            <th scope="col">{t('donutChartShare')}</th>
          </tr>
        </thead>
        <tbody>
          {model.items.map((item, i) => (
            <tr key={`${item.label}-${i}`}>
              <th scope="row">{item.label}</th>
              <td>{model.value.format(item.value)}</td>
              <td>{model.share.format(model.segments[i]?.share ?? 0)}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </figure>
  );
}
