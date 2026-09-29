/**
 * The stat strip block (React).
 *
 * A compact horizontal band of stats — small-caps terms over big display
 * figures, hairlines between (css/blocks/stat-strip.css). The count-up is
 * NumberTicker's: every figure holds its digits at zero until it scrolls
 * into view, then rolls once, in the provider language's digits.
 */
import { type CSSProperties, type HTMLAttributes, type ReactNode, useRef } from 'react';
import type { IconName } from '@nabuxai/ui-core';
import { cx } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { NumberTicker, useReveal } from '../components/text';

/* ============================================================================
 * StatStrip — "Designs 2.4k | Designers 1.4k | Categories 40 | Platforms 5".
 * ========================================================================== */

export interface StatStripStat {
  /** The small-caps term ("Designs"). */
  label: ReactNode;
  /** The figure; it counts up from zero when it scrolls into view. */
  value: number;
  /** An optional glyph beside the label. */
  icon?: IconName;
  /** A quiet line under the number ("+120 this week"). */
  caption?: ReactNode;
}

export interface StatStripProps extends Omit<HTMLAttributes<HTMLDListElement>, 'children'> {
  /** Two to four stats read best in one row; it wraps to two rows when narrow. */
  stats: StatStripStat[];
  /** Name of the group for assistive tech. */
  'aria-label'?: string;
}

export function StatStrip({ stats, 'aria-label': label, className, ...rest }: StatStripProps) {
  const root = useRef<HTMLDListElement>(null);
  // The items rise one after another; each NumberTicker rolls its own figure.
  useReveal(root, { stagger: true });

  return (
    <dl ref={root} className={cx('nx-stat-strip', className)} data-nx-reveal="group" aria-label={label} {...rest}>
      {stats.map((stat, i) => (
        <div key={i} className="nx-stat-strip-item" style={{ '--nx-i': i } as CSSProperties}>
          {stat.icon && (
            <span className="nx-stat-strip-icon" aria-hidden="true">
              <Icon name={stat.icon} />
            </span>
          )}
          <div className="nx-stat-strip-what">
            <dt className="nx-stat-strip-label">{stat.label}</dt>
            <dd className="nx-stat-strip-value">
              <NumberTicker value={stat.value} />
              {stat.caption && <span className="nx-stat-strip-caption">{stat.caption}</span>}
            </dd>
          </div>
        </div>
      ))}
    </dl>
  );
}
