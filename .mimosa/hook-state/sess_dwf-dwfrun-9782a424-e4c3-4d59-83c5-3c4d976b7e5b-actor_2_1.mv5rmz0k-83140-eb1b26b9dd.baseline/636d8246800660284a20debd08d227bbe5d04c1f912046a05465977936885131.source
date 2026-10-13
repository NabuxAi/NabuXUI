/**
 * Scroll blocks (React): ScrollStack, ScrollHorizontal and ScrollRoadmap —
 * sticky scenes driven by their stage's own scroll run. Thin over
 * css/blocks/scroll.css and the core scrollStage() behaviour: the page scrolls
 * one tall stage, everything the reader sees is pinned inside it.
 */
import { type CSSProperties, type HTMLAttributes, type ReactNode, forwardRef, useRef, useState } from 'react';
import { type IconName, type ScrollStageMode, formatNumber, scrollStage } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useEvent } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale } from '../internal/provider';

/** The rail and node accents a card or a station can carry. */
export type ScrollAccent = 'accent' | 'lapis' | 'violet' | 'cyan' | 'gold' | 'success';

const ACCENTS: Record<ScrollAccent, string> = {
  accent: 'var(--nx-accent)',
  lapis: 'var(--nx-lapis-500)',
  violet: 'var(--nx-violet-500)',
  cyan: 'var(--nx-cyan-500)',
  gold: 'var(--nx-gold-500)',
  success: 'var(--nx-success)',
};

/** The status pill a strip panel or a roadmap station can carry. */
export type ScrollState = 'live' | 'canary' | 'scheduled' | 'building' | 'design';

function StatePill({ state, label }: { state: ScrollState; label: ReactNode }) {
  return (
    <span className="nx-scroll-state" data-state={state}>
      <i aria-hidden="true" />
      {label}
    </span>
  );
}

/** The integer percent as a figure in the page's own numbering system, plus the raw number for aria. */
function usePercent(onProgress: ((progress: number) => void) | undefined): { readout: (progress: number) => void; percent: number; figure: string } {
  const locale = useLocale();
  const [percent, setPercent] = useState(0);
  const handle = useEvent(onProgress);
  const readout = (progress: number) => {
    const next = Math.round(progress * 100);
    setPercent((current) => (current === next ? current : next));
    handle?.(progress);
  };
  return { readout, percent, figure: formatNumber(percent, locale) };
}

/* ---- Sticky stack ------------------------------------------------------------------ */

export interface ScrollStackItem {
  /** Stable key; the index is used when absent. */
  key?: string;
  /** Small label over the title (a plan's name, a chapter number…). */
  kicker?: ReactNode;
  title: ReactNode;
  /** Body copy under the title. */
  text?: ReactNode;
  /** The card's accent rail (default accent). */
  accent?: ScrollAccent;
  icon?: IconName;
  /** Live footer content — actions, a chosen chip — pinned inside the sticky card. */
  foot?: ReactNode;
}

export interface ScrollStackProps extends Omit<HTMLAttributes<HTMLElement>, 'title' | 'onProgress'> {
  items: ScrollStackItem[];
  /** Where the first card sticks from the viewport top (a CSS length; default 4.5rem). */
  inset?: string;
  /** Every card's runway step (a CSS length; default 88vh). */
  zone?: string;
  /** Max inline size of a card (a CSS length; default 34rem). */
  cardWidth?: string;
  /** The scrolling box that drives the stack (a CSS selector); the page when absent. */
  container?: string;
  /** Called with the stack's progress (0–1) as it scrolls. */
  onProgress?: (progress: number) => void;
  /** Accessible name of the card sequence. */
  label?: string;
}

/**
 * A runway of cards that each stick a little lower than the one before: the
 * card above stays visible as a rim while it shrinks six percent and dims, so
 * the pile reads as depth. Footers stay live inside the pinned card.
 */
export const ScrollStack = forwardRef<HTMLElement, ScrollStackProps>(function ScrollStack(
  { items, inset, zone, cardWidth, container, onProgress, label = 'Stacked cards', className, style, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLElement>(null);
  const handle = useEvent(onProgress);
  useBehavior(ref, scrollStage, { mode: 'sticky' as ScrollStageMode, container, onProgress: handle });
  const vars = {
    ...(inset ? { '--_inset': inset } : null),
    ...(zone ? { '--_zone': zone } : null),
    ...(cardWidth ? { '--_card': cardWidth } : null),
  } as CSSProperties;
  return (
    <section ref={mergeRefs(ref, forwarded)} className={cx('nx-scroll-stage', className)} data-mode="sticky" role="group" aria-label={label} style={{ ...vars, ...style }} {...rest}>
      {items.map((item, i) => (
        <div className="nx-scroll-zone" key={item.key ?? i}>
          <article className="nx-scroll-card" style={{ '--nx-i': i, '--_accent': ACCENTS[item.accent ?? 'accent'] } as CSSProperties}>
            {item.kicker && <p className="nx-scroll-card-kicker">{item.kicker}</p>}
            <h3 className="nx-scroll-card-title">
              {item.icon && (
                <span className="nx-scroll-card-icon" aria-hidden="true">
                  <Icon name={item.icon} />
                </span>
              )}
              {item.title}
            </h3>
            {item.text && <div className="nx-scroll-card-text">{item.text}</div>}
            {item.foot && <div className="nx-scroll-card-foot">{item.foot}</div>}
          </article>
        </div>
      ))}
    </section>
  );
});

/* ---- Horizontal strip ---------------------------------------------------------------- */

export interface ScrollHorizontalItem {
  key?: string;
  title: ReactNode;
  meta?: ReactNode;
  /** A status pill beside the meta (its label must be given too). */
  state?: ScrollState;
  stateLabel?: ReactNode;
  /** The artwork: an <img>, an inline SVG scene, anything. */
  media?: ReactNode;
  /** A CSS background behind the artwork panel (a gradient, an image url()). */
  cover?: string;
}

export interface ScrollHorizontalProps extends Omit<HTMLAttributes<HTMLElement>, 'title' | 'onProgress'> {
  items: ScrollHorizontalItem[];
  title?: ReactNode;
  subtitle?: ReactNode;
  /** Viewport-heights of travel after the strip fits (default 2.2). */
  pages?: number;
  inset?: string;
  container?: string;
  onProgress?: (progress: number) => void;
  /** Accessible name of the strip. */
  label?: string;
  /** The readout under the strip (default "Strip progress"). */
  progressLabel?: ReactNode;
}

/**
 * A horizontal gallery the vertical wheel drives: the stage scrolls, the
 * pinned viewport slides its track sideways, and the meter under the frame
 * mirrors the same single progress. Under reduced motion the lane becomes a
 * plain sideways scroller.
 */
export const ScrollHorizontal = forwardRef<HTMLElement, ScrollHorizontalProps>(function ScrollHorizontal(
  { items, title, subtitle, pages, inset, container, onProgress, label = 'Gallery strip', progressLabel = 'Strip progress', className, style, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLElement>(null);
  const { readout, percent, figure } = usePercent(onProgress);
  useBehavior(ref, scrollStage, { mode: 'horizontal' as ScrollStageMode, container, onProgress: readout });
  const vars = {
    ...(pages !== undefined ? { '--_pages': pages } : null),
    ...(inset ? { '--_inset': inset } : null),
  } as CSSProperties;
  return (
    <section ref={mergeRefs(ref, forwarded)} className={cx('nx-scroll-stage', className)} data-mode="horizontal" aria-label={label} style={{ ...vars, ...style }} {...rest}>
      <div className="nx-scroll-view">
        {(title || subtitle) && (
          <div className="nx-scroll-head">
            {title && <h3 className="nx-scroll-title">{title}</h3>}
            {subtitle && <p className="nx-scroll-subtitle">{subtitle}</p>}
          </div>
        )}
        <div className="nx-scroll-lane">
          <ul className="nx-scroll-track" aria-label={label}>
            {items.map((item, i) => (
              <li key={item.key ?? i} className="nx-scroll-panel">
                {item.media !== undefined || item.cover ? (
                  <div className="nx-scroll-panel-media" style={item.cover ? { background: item.cover } : undefined}>
                    {item.media}
                  </div>
                ) : null}
                <div className="nx-scroll-panel-cap">
                  <h4 className="nx-scroll-panel-title">{item.title}</h4>
                  {(item.state || item.meta) && (
                    <div className="nx-scroll-panel-meta">
                      {item.state && item.stateLabel && <StatePill state={item.state} label={item.stateLabel} />}
                      {item.meta}
                    </div>
                  )}
                </div>
              </li>
            ))}
          </ul>
        </div>
        <div className="nx-scroll-meter-group">
          <span className="nx-scroll-bar" role="progressbar" aria-label={typeof progressLabel === 'string' ? progressLabel : undefined} aria-valuemin={0} aria-valuemax={100} aria-valuenow={percent}>
            <span className="nx-scroll-bar-fill" />
          </span>
          <p className="nx-scroll-readout">
            {progressLabel}: {figure}%
          </p>
        </div>
      </div>
    </section>
  );
});

/* ---- Roadmap --------------------------------------------------------------------------- */

export interface ScrollRoadmapStop {
  key?: string;
  /** Small label over the title ("Q1 · shipped"). */
  kicker?: ReactNode;
  title: ReactNode;
  text?: ReactNode;
  meta?: ReactNode;
  state?: ScrollState;
  stateLabel?: ReactNode;
  /** The last station is not a milestone but the door: an accent slab. */
  cta?: boolean;
  /** Live content inside the station (the CTA's button and its result). */
  foot?: ReactNode;
}

export interface ScrollRoadmapProps extends Omit<HTMLAttributes<HTMLElement>, 'title' | 'onProgress'> {
  stops: ScrollRoadmapStop[];
  title?: ReactNode;
  subtitle?: ReactNode;
  /** Viewport-heights of travel after the board fits (default 2). */
  pages?: number;
  inset?: string;
  container?: string;
  onProgress?: (progress: number) => void;
  /** Accessible name of the roadmap. */
  label?: string;
  /** The meter beside the title (default "% shipped"). */
  meterLabel?: ReactNode;
}

/**
 * A pinned board whose progress line drinks the scroll while the stations
 * wake in order — each card zooms out of a soft blur the moment its gate
 * opens, and the node beside it lights in the same breath.
 */
export const ScrollRoadmap = forwardRef<HTMLElement, ScrollRoadmapProps>(function ScrollRoadmap(
  { stops, title, subtitle, pages, inset, container, onProgress, label = 'Roadmap', meterLabel = '% shipped', className, style, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLElement>(null);
  const { readout, percent, figure } = usePercent(onProgress);
  useBehavior(ref, scrollStage, { mode: 'zoom' as ScrollStageMode, container, onProgress: readout });
  const vars = {
    ...(pages !== undefined ? { '--_pages': pages } : null),
    ...(inset ? { '--_inset': inset } : null),
  } as CSSProperties;
  return (
    <section ref={mergeRefs(ref, forwarded)} className={cx('nx-scroll-stage', className)} data-mode="zoom" aria-label={label} style={{ ...vars, ...style }} {...rest}>
      <div className="nx-scroll-view">
        {(title || subtitle) && (
          <div className="nx-scroll-head" data-roadmap="">
            <div>
              {title && <h3 className="nx-scroll-title">{title}</h3>}
              {subtitle && <p className="nx-scroll-subtitle">{subtitle}</p>}
            </div>
            <span className="nx-scroll-meter" role="progressbar" aria-label={typeof meterLabel === 'string' ? meterLabel : undefined} aria-valuemin={0} aria-valuemax={100} aria-valuenow={percent}>
              <i aria-hidden="true" />
              {figure}
              {meterLabel}
            </span>
          </div>
        )}
        <ol className="nx-scroll-rail" aria-label={label}>
          <span className="nx-scroll-line" aria-hidden="true">
            <span className="nx-scroll-fill" />
          </span>
          {stops.map((stop, i) => (
            <li key={stop.key ?? i} className="nx-scroll-stop">
              <span className="nx-scroll-node" aria-hidden="true" />
              <article className="nx-scroll-station" data-cta={stop.cta ? '' : undefined}>
                {stop.kicker && <span className="nx-scroll-station-kicker">{stop.kicker}</span>}
                <h4 className="nx-scroll-station-title">{stop.title}</h4>
                {stop.text && <p className="nx-scroll-station-text">{stop.text}</p>}
                {(stop.state || stop.meta) && (
                  <div className="nx-scroll-station-meta">
                    {stop.state && stop.stateLabel && <StatePill state={stop.state} label={stop.stateLabel} />}
                    {stop.meta}
                  </div>
                )}
                {stop.foot && <div className="nx-scroll-station-foot">{stop.foot}</div>}
              </article>
            </li>
          ))}
        </ol>
      </div>
    </section>
  );
});
