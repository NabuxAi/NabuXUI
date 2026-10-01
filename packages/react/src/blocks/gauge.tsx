/**
 * The gauge block (React): a semicircle dial whose needle swings in on a
 * spring and whose reading rolls its digits (css/blocks/gauge.css). The zones
 * are a dim backdrop of arc segments on the same arc (the core's donut
 * segment maths); the sweep, the hub dot and the caption take the colour of
 * the zone the value sits in, so no state is carried by motion alone. The
 * same data ships as a visually-hidden table.
 *
 * There is no JavaScript of its own: the sweep and the needle ride custom
 * properties (--_sweep, --_angle) with CSS transitions, so prop updates
 * continue from wherever the previous animation left off.
 */
import { type CSSProperties, type HTMLAttributes, type ReactNode, useId, useMemo, useRef } from 'react';
import { type MessageKey, donutSegments, reveal } from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { useLocale, useT } from '../internal/provider';
import { NumberTicker } from '../components/text';

/** The arc the track, the zones and the sweep are drawn on (pathLength 100 = min → max). */
const ARC = 'M8 50 A42 42 0 0 1 92 50';
/** The hand, pointing up (12 o'clock) so its angle is −90…90° from vertical. */
const HAND = 'M50 17 L46.6 47.6 L50 50.8 L53.4 47.6 Z';

/** Eleven ticks around the arc, every fifth one major. */
const TICKS = Array.from({ length: 11 }, (_, i) => {
  const rad = (((i / 10) * 180 - 90) * Math.PI) / 180;
  const inner = i % 5 === 0 ? 30.5 : 33.5;
  const at = (r: number) => [Number((50 + r * Math.sin(rad)).toFixed(2)), Number((50 - r * Math.cos(rad)).toFixed(2))] as const;
  const [x1, y1] = at(inner);
  const [x2, y2] = at(36);
  return { x1, y1, x2, y2, major: i % 5 === 0 };
});

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

export type GaugeTone = 'success' | 'warning' | 'danger' | 'info' | 'accent';

/** The words the gauge says itself; override any of them with `labels`. */
type GaugeWord = 'value' | 'zone' | 'range' | 'safe' | 'caution' | 'critical';

const WORD_KEYS: Record<GaugeWord, MessageKey> = {
  value: 'gaugeValue',
  zone: 'gaugeZone',
  range: 'gaugeRange',
  safe: 'gaugeZoneSafe',
  caution: 'gaugeZoneCaution',
  critical: 'gaugeZoneCritical',
};

/** Which word names a zone of each tone (info/accent zones fall back to "Zone n"). */
const ZONE_WORD: Partial<Record<GaugeTone, GaugeWord>> = { success: 'safe', warning: 'caution', danger: 'critical' };

export interface GaugeZone {
  /** Where the zone ends — the last zone runs to `max` when omitted. */
  upTo?: number;
  label?: ReactNode;
  tone?: GaugeTone;
}

export interface GaugeProps extends Omit<HTMLAttributes<HTMLElement>, 'children' | 'title'> {
  value: number;
  min?: number;
  max?: number;
  /** Shown beside the reading: "%", "km/h", "تومان"… */
  unit?: ReactNode;
  title?: ReactNode;
  subtitle?: ReactNode;
  /** The coloured bands; the dial's thirds by default (safe / caution / critical). */
  zones?: GaugeZone[];
  /** Fraction digits — the data's own by default (1 when anything has any). */
  decimals?: number;
  /** The dial's width: a number is px, anything else a CSS length ("18rem"). */
  size?: number | string;
  /** Replace the caption under the reading, or null to drop it. */
  zoneLabel?: ReactNode;
  locale?: string;
  labels?: Partial<Record<GaugeWord, string>>;
}

export function Gauge({
  value,
  min = 0,
  max = 100,
  unit,
  title,
  subtitle,
  zones: zonesProp,
  decimals,
  size,
  zoneLabel,
  locale,
  labels,
  className,
  style,
  ...rest
}: GaugeProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const word = (key: GaugeWord) => labels?.[key] ?? t(WORD_KEYS[key]);
  const heading = `nx-gauge${useId().replace(/:/g, '')}`;
  const figure = useRef<HTMLElement>(null);
  useBehavior(figure, reveal, { once: true });

  const model = useMemo(() => {
    const lo = Math.min(min, max);
    const hi = Math.max(min, max);
    const span = hi - lo || 1;
    const shown = Math.min(hi, Math.max(lo, value));
    const fraction = (shown - lo) / span;

    // Zones: the dial's thirds by default, each with its own tone.
    const raw = zonesProp ?? [
      { upTo: lo + span * 0.6, tone: 'success' as const },
      { upTo: lo + span * 0.85, tone: 'warning' as const },
      { tone: 'danger' as const },
    ];
    let edge = lo;
    const zones = raw
      .map((zone, i) => {
        const from = edge;
        const to = Math.min(hi, Math.max(from, zone.upTo ?? (i === raw.length - 1 ? hi : from)));
        edge = to;
        return { from, to, tone: zone.tone ?? 'accent' };
      })
      .filter((zone) => zone.to > zone.from);
    // The zones are dashes on the same arc — the core's donut segment maths.
    const segments = donutSegments(zones.map((zone) => zone.to - zone.from), 1.6);

    const active = Math.max(0, zones.findIndex((zone) => shown <= zone.to));
    return {
      lo,
      hi,
      shown,
      zones,
      segments,
      active: zones.length > 0 ? Math.min(active, zones.length - 1) : -1,
      angle: Number((fraction * 180 - 90).toFixed(2)),
      sweep: Number((fraction * 100).toFixed(2)),
    };
  }, [min, max, value, zonesProp]);

  const digits = decimals ?? (Number.isInteger(model.shown) && Number.isInteger(model.lo) && Number.isInteger(model.hi) ? 0 : 1);
  const format = useMemo(
    () => ({ minimumFractionDigits: digits, maximumFractionDigits: digits }) as Intl.NumberFormatOptions,
    [digits],
  );
  const fmt = useMemo(() => new Intl.NumberFormat(intl, format), [intl, format]);

  const active = model.active >= 0 ? model.zones[model.active] : undefined;
  const tone = active?.tone ?? 'accent';
  // A zone's name: the prop, else the tone's word, else "Zone n".
  const nameOf = (zone: { tone: GaugeTone }, i: number) => {
    const key = ZONE_WORD[zone.tone];
    return key ? word(key) : `${word('zone')} ${new Intl.NumberFormat(intl).format(i + 1)}`;
  };
  const reading = t('gaugeReading', { value: fmt.format(model.shown), max: fmt.format(model.hi) });
  const caption = zoneLabel !== undefined && zoneLabel !== null && zoneLabel !== '' ? zoneLabel : active ? nameOf(active, model.active) : null;

  return (
    <figure
      ref={figure}
      className={cx('nx-gauge', className)}
      data-tone={tone}
      data-nx-reveal=""
      aria-labelledby={title ? `${heading}-title` : undefined}
      style={{ ...vars({ '--nx-gauge-size': typeof size === 'number' ? `${size}px` : size, '--_angle': model.angle, '--_sweep': model.sweep }), ...style }}
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

      <div className="nx-gauge-dial">
        <div className="nx-gauge-face">
          <svg className="nx-gauge-svg" viewBox="0 0 100 58" aria-hidden="true">
            <path className="nx-gauge-track" d={ARC} pathLength={100} />
            {model.zones.map((zone, i) => (
              <path
                key={i}
                className="nx-gauge-zone"
                d={ARC}
                pathLength={100}
                data-tone={zone.tone}
                style={vars({ '--_len': model.segments[i]!.length, '--_off': model.segments[i]!.offset, '--nx-i': i })}
              />
            ))}
            <g className="nx-gauge-ticks">
              {TICKS.map((tick, i) => (
                <line
                  key={i}
                  className="nx-gauge-tick"
                  data-major={tick.major ? '' : undefined}
                  x1={tick.x1}
                  y1={tick.y1}
                  x2={tick.x2}
                  y2={tick.y2}
                />
              ))}
            </g>
            <path className="nx-gauge-value" d={ARC} pathLength={100} />
            <g className="nx-gauge-needle">
              <path className="nx-gauge-hand" d={HAND} />
            </g>
            <circle className="nx-gauge-hub" cx="50" cy="50" r="5.2" />
            <circle className="nx-gauge-hub-dot" cx="50" cy="50" r="2.1" />
          </svg>
          <span className="nx-gauge-end" data-end="min" aria-hidden="true">
            {fmt.format(model.lo)}
          </span>
          <span className="nx-gauge-end" data-end="max" aria-hidden="true">
            {fmt.format(model.hi)}
          </span>
        </div>

        <p className="nx-gauge-readout">
          <span className="nx-gauge-figure">
            <NumberTicker value={model.shown} locale={intl} format={format} />
            {unit && <span className="nx-gauge-unit">{unit}</span>}
          </span>
          {caption && <span className="nx-gauge-zone-label">{caption}</span>}
        </p>
      </div>

      <table className="nx-visually-hidden">
        {title && <caption>{title}</caption>}
        <thead>
          <tr>
            <th scope="col">{word('zone')}</th>
            <th scope="col">{word('range')}</th>
          </tr>
        </thead>
        <tbody>
          {model.zones.map((zone, i) => (
            <tr key={i}>
              <th scope="row">{nameOf(zone, i)}</th>
              <td>
                {fmt.format(zone.from)} – {fmt.format(zone.to)}
              </td>
            </tr>
          ))}
          <tr>
            <th scope="row">{word('value')}</th>
            <td>{reading}</td>
          </tr>
        </tbody>
      </table>
    </figure>
  );
}
