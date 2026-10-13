/**
 * Interactive motion (React): the spotlight grid, the cursor halo and the
 * marquee wall. Thin over css/blocks/motion-fx.css and the core behaviours in
 * js/blocks/motion-fx.ts — the components render the markup and own the state.
 */
import {
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useRef,
  useState,
} from 'react';
import { cursorHalo, marqueeWall, pointerSpot } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';
import { Marquee } from '../components/display';

/* ---- Spotlight grid ------------------------------------------------------------------ */

export interface SpotlightGridItem {
  id: string;
  /** The card's heading; a link when `href` is set. */
  title: ReactNode;
  /** A line of body copy under the title. */
  note?: ReactNode;
  /** A small fact chip (mono, tabular). */
  meta?: ReactNode;
  /** Where the card's title goes. */
  href?: string;
}

export interface SpotlightGridProps extends HTMLAttributes<HTMLDivElement> {
  items: SpotlightGridItem[];
  /** Radius of the halo that sweeps the whole wall, px (default 260). */
  radius?: number;
}

/**
 * A wall of cards under one follow-spot: the halo tracks the pointer across
 * the whole grid while the card under the hand lights its own edge from
 * inside. Keyboard focus paints the same glow, so nothing is pointer-only.
 */
export const SpotlightGrid = forwardRef<HTMLDivElement, SpotlightGridProps>(function SpotlightGrid(
  { items, radius = 260, className, ...rest },
  ref,
) {
  const own = useRef<HTMLDivElement>(null);
  useBehavior(own, pointerSpot, { radius });

  return (
    <div ref={mergeRefs(ref, own)} className={cx('nx-spotlight-grid', className)} {...rest}>
      <ul className="nx-spotlight-list">
        {items.map((item) => (
          <li key={item.id}>
            <article className="nx-spotlight-card" data-nx-spot="">
              <h3 className="nx-spotlight-title">
                {item.href ? <SmartLink href={item.href}>{item.title}</SmartLink> : item.title}
              </h3>
              {item.note !== undefined && <p className="nx-spotlight-note">{item.note}</p>}
              {item.meta !== undefined && <span className="nx-spotlight-meta">{item.meta}</span>}
            </article>
          </li>
        ))}
      </ul>
    </div>
  );
});

/* ---- Cursor halo ---------------------------------------------------------------------- */

export interface CursorHaloProps extends HTMLAttributes<HTMLElement> {
  /**
   * Hide the native cursor inside the area while the halo pair is live
   * (default true). Fields keep the platform cursor either way.
   */
  hideCursor?: boolean;
  as?: 'div' | 'section';
}

/**
 * A ring that trails the pointer and a dot that rides it — inside this
 * container only, never `position: fixed`. Children ask for a cursor state
 * with `data-cursor="link" | "text" | "play"` (unstyled names keep the plain
 * ring) and can name the state with `data-cursor-label`, which the disc shows.
 * Touch input and reduced motion keep the platform cursor.
 */
export const CursorHalo = forwardRef<HTMLElement, CursorHaloProps>(function CursorHalo(
  { hideCursor = true, as = 'div', className, children, ...rest },
  ref,
) {
  const own = useRef<HTMLElement>(null);
  useBehavior(own, cursorHalo, {});
  const Tag = as as 'div';

  return (
    <Tag
      ref={mergeRefs(ref, own) as never}
      className={cx('nx-cursor-halo', className)}
      data-hide-cursor={hideCursor ? '' : undefined}
      {...rest}
    >
      {children}
      <span className="nx-cursor-halo-ring" aria-hidden="true">
        <span className="nx-cursor-halo-face" />
      </span>
      <span className="nx-cursor-halo-dot" aria-hidden="true" />
    </Tag>
  );
});

/* ---- Marquee wall ---------------------------------------------------------------------- */

export interface MarqueeRowsItem {
  id: string;
  /** The line that scrolls by. */
  quote: ReactNode;
  /** Who said it, or where it is from. */
  caption?: ReactNode;
  /** Initials (or any short mark) for the avatar disc; decorative. */
  avatar?: ReactNode;
}

export interface MarqueeRowsRow {
  id?: string;
  items: MarqueeRowsItem[];
  /** Seconds for one lap of this row (default 34). */
  duration?: number;
  /** This row runs against the wall's base direction. */
  reverse?: boolean;
}

export interface MarqueeRowsProps extends HTMLAttributes<HTMLDivElement> {
  rows: MarqueeRowsRow[];
  /** Flip every row's direction at once (controlled). */
  reversed?: boolean;
  defaultReversed?: boolean;
  onReversedChange?: (reversed: boolean) => void;
  /** Show the flip-directions button (default true). */
  flip?: boolean;
  /** Label — and accessible name — of the flip button. */
  flipLabel?: string;
  /** Show the stop/start button (default true: moving content must be stoppable). */
  park?: boolean;
  /** Park rows while they are outside the viewport (default true). */
  pauseOffscreen?: boolean;
}

/**
 * A wall of marquee rows at unsynchronised speeds and directions. Hover or
 * focus freezes a row, the flip button turns every row around on the spot, the
 * stop button parks the whole wall, and off-screen rows pause by themselves.
 * Under reduced motion the rows become a plain scroll strip.
 */
export function MarqueeRows({
  rows,
  reversed,
  defaultReversed = false,
  onReversedChange,
  flip = true,
  flipLabel = 'Flip directions',
  park = true,
  pauseOffscreen = true,
  className,
  ...rest
}: MarqueeRowsProps) {
  const t = useT();
  const [current, setCurrent] = useControllable(reversed, defaultReversed, onReversedChange);
  const [parked, setParked] = useState(false);
  const root = useRef<HTMLDivElement>(null);
  useBehavior(root, marqueeWall, { pauseOffscreen });

  return (
    <div ref={root} className={cx('nx-marquee-wall', className)} data-reversed={current ? '' : undefined} data-paused={parked ? '' : undefined} {...rest}>
      {(flip || park) && (
        <div className="nx-marquee-wall-toolbar">
          {flip && (
            <button type="button" className="nx-marquee-flip" aria-pressed={current} onClick={() => setCurrent(!current)}>
              <Icon name="arrow-right" />
              <span>{flipLabel}</span>
            </button>
          )}
          {park && (
            <button type="button" className="nx-marquee-park" aria-pressed={parked} onClick={() => setParked(!parked)}>
              <Icon name={parked ? 'play' : 'pause'} />
              <span>{parked ? t('play') : t('pause')}</span>
            </button>
          )}
        </div>
      )}
      <div className="nx-marquee-wall-rows">
        {rows.map((row, index) => (
          <Marquee
            key={row.id ?? index}
            className="nx-marquee-wall-row"
            duration={row.duration ?? 34}
            reverse={row.reverse !== current}
            controls={false}
            items={row.items.map((item) => (
              <div key={item.id} className="nx-marquee-card">
                {item.avatar !== undefined && (
                  <span className="nx-marquee-avatar" aria-hidden="true">
                    {item.avatar}
                  </span>
                )}
                <div className="nx-marquee-quote">
                  <blockquote>{item.quote}</blockquote>
                  {item.caption !== undefined && <span className="nx-marquee-caption">{item.caption}</span>}
                </div>
              </div>
            ))}
          />
        ))}
      </div>
    </div>
  );
}
