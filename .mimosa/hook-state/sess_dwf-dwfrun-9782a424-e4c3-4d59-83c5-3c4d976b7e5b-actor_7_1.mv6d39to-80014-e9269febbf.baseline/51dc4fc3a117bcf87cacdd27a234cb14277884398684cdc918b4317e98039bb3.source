/**
 * Ambient backgrounds (React): sections whose sky is the effect. Thin over
 * css/blocks/ambient.css and the core behaviours in js/blocks/ambient.ts —
 * the components render the markup and own the state.
 *
 *   AuroraBackdrop      four blurred, screen-blended blobs breathing behind
 *                       the copy; `colors` retunes the whole sky live
 *   PerspectiveGrid     the retro floor: rotateX plane, streaming cells, fog;
 *                       `speed` is seconds per cell, live after mount
 *   StarfieldBackdrop   the canvas star field with real depth; the ref can
 *                       `warp()` — triple the approach for a beat
 *   ContourLines        the survey map: phased sine bands, summit dots that
 *                       ride them, hover/focus retunes the peak's band
 *   Metaballs           the goo: SVG blur + alpha threshold, transform-only
 *                       wander; `variant` pins the palette
 *
 * Every stage parks its loops off screen (ambientStage) and the styles freeze
 * them under reduced motion; the copy is yours, through children.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useId,
  useImperativeHandle,
  useRef,
} from 'react';
import { ambientStage, contourLines, prefersReducedMotion, starfield } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useEvent, useIsoLayoutEffect } from '../internal/hooks';

/* ---- Aurora backdrop ---------------------------------------------------------------------- */

/** The three colours the sky breathes in (any CSS colour; tokens welcome). */
export type AuroraColors = [string, string, string];

const AURORA_DEFAULT: AuroraColors = ['var(--nx-cyan-400)', 'var(--nx-chart-7)', 'var(--nx-violet-500)'];

export interface AuroraBackdropProps extends HTMLAttributes<HTMLElement> {
  /** The sky's three blob colours, live after mount (default: cyan, green, violet). */
  colors?: AuroraColors;
  children?: ReactNode;
}

/** A night sky that breathes under the headline: drifting, blurred colour — transform only, parked off screen. */
export const AuroraBackdrop = forwardRef<HTMLElement, AuroraBackdropProps>(function AuroraBackdrop(
  { colors = AURORA_DEFAULT, className, style, children, ...rest },
  forwarded,
) {
  const own = useRef<HTMLElement>(null);
  useBehavior(own, ambientStage, {});

  return (
    <section
      ref={mergeRefs(forwarded, own)}
      className={cx('nx-aurora', className)}
      style={{
        '--nx-aurora-c1': colors[0],
        '--nx-aurora-c2': colors[1],
        '--nx-aurora-c3': colors[2],
        ...style,
      } as CSSProperties}
      {...rest}
    >
      <div className="nx-aurora-sky" aria-hidden="true">
        <i className="nx-aurora-blob" />
        <i className="nx-aurora-blob" />
        <i className="nx-aurora-blob" />
        <i className="nx-aurora-blob" />
      </div>
      {children}
    </section>
  );
});

/* ---- Perspective grid ---------------------------------------------------------------------- */

export interface PerspectiveGridProps extends HTMLAttributes<HTMLElement> {
  /** Seconds one grid cell takes to stream toward the viewer (default 1.6). */
  speed?: number;
  children?: ReactNode;
}

/** A floor that rushes at the viewer without ever re-laying pixels: one rotateX plane, one seamless cell loop, fog at the horizon. */
export const PerspectiveGrid = forwardRef<HTMLElement, PerspectiveGridProps>(function PerspectiveGrid(
  { speed = 1.6, className, style, children, ...rest },
  forwarded,
) {
  const own = useRef<HTMLElement>(null);
  useBehavior(own, ambientStage, {});

  return (
    <section
      ref={mergeRefs(forwarded, own)}
      className={cx('nx-perspective-grid', className)}
      style={{ '--nx-perspective-duration': `${speed}s`, ...style } as CSSProperties}
      {...rest}
    >
      <div className="nx-perspective-sun" aria-hidden="true" />
      <div className="nx-perspective-floor" aria-hidden="true">
        <i className="nx-perspective-lines" />
      </div>
      <div className="nx-perspective-horizon" aria-hidden="true" />
      {children}
    </section>
  );
});

/* ---- Starfield ----------------------------------------------------------------------------- */

/** What a StarfieldBackdrop ref can do: give the sky a push. */
export interface StarfieldHandle {
  /** Triple the approach for a beat (1.6s); a no-op under reduced motion. */
  warp(): void;
}

export interface StarfieldBackdropProps extends HTMLAttributes<HTMLElement> {
  /** Star count at a 900×500 stage; scales with the stage's area (default 180). */
  density?: number;
  /** The base approach speed (default 0.9); the warp triples it on top. */
  speed?: number;
  /** How far the pointer eases the vanishing point, px (default 26). */
  parallax?: number;
  /** Called with the star count after every reseed. */
  onCount?: (count: number) => void;
  children?: ReactNode;
}

/** Stars that fly past the headline: every star owns a z, and the pointer bends the vanishing point. */
export const StarfieldBackdrop = forwardRef<StarfieldHandle, StarfieldBackdropProps>(function StarfieldBackdrop(
  { density = 180, speed = 0.9, parallax = 26, onCount, className, children, ...rest },
  forwarded,
) {
  const own = useRef<HTMLElement>(null);
  const report = useEvent(onCount);
  // Through a ref: speed changes retune the flow; the warp bumps it for a beat.
  const flow = useRef({ speed, warpUntil: 0 });
  useIsoLayoutEffect(() => {
    flow.current.speed = speed;
  });
  useBehavior(own, starfield, {
    density,
    parallax,
    speed: () => flow.current.speed * (performance.now() < flow.current.warpUntil ? 3 : 1),
    onCount: report,
  });
  useImperativeHandle(
    forwarded,
    () => ({
      warp: () => {
        if (prefersReducedMotion()) return;
        flow.current.warpUntil = performance.now() + 1600;
      },
    }),
    [],
  );

  return (
    <section ref={own} className={cx('nx-starfield', className)} {...rest}>
      <canvas className="nx-starfield-canvas" aria-hidden="true" />
      {children}
    </section>
  );
});

/* ---- Contour lines ------------------------------------------------------------------------- */

/** One summit on the survey map: a dot that rides its contour band. */
export interface ContourPeak {
  id: string;
  /** Which band the summit rides, counting from the front (default 0). */
  band?: number;
  /** Where it sits along the map, in viewBox units (0–1200). */
  x: number;
  /** The dot's accessible name — it is one image to assistive tech. */
  label: string;
  /** Its elevation colour: any CSS colour, tokens welcome (default: the accent). */
  color?: string;
}

export interface ContourLinesProps extends HTMLAttributes<HTMLElement> {
  /** How many contour bands the survey draws (default 8). */
  bands?: number;
  /** Time multiplier, live after mount (default 1). */
  speed?: number;
  /** The summits that ride the bands. */
  peaks?: ContourPeak[];
  /** Called when the hot peak changes: its id, or null when none. */
  onPeakChange?: (id: string | null) => void;
  children?: ReactNode;
}

/** A map that never stops surveying: phased contour bands, summits riding them, hover or focus retunes the peak's band. */
export const ContourLines = forwardRef<HTMLElement, ContourLinesProps>(function ContourLines(
  { bands = 8, speed = 1, peaks = [], onPeakChange, className, children, ...rest },
  forwarded,
) {
  const own = useRef<HTMLElement>(null);
  const report = useEvent(onPeakChange);
  const flow = useRef(speed);
  useIsoLayoutEffect(() => {
    flow.current = speed;
  });
  useBehavior(own, contourLines, { bands, speed: () => flow.current, onPeakChange: report });

  return (
    <section ref={mergeRefs(forwarded, own)} className={cx('nx-contour', className)} {...rest}>
      <svg className="nx-contour-svg" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice">
        <g className="nx-contour-lines" aria-hidden="true">
          {Array.from({ length: bands }, (_, index) => (
            <path key={index} className="nx-contour-line" />
          ))}
        </g>
        {peaks.map((peak) => (
          <g
            key={peak.id}
            className="nx-contour-peak"
            data-contour-peak={peak.id}
            data-band={peak.band ?? 0}
            data-x={peak.x}
            data-color={peak.color}
          >
            <circle className="nx-contour-halo" r={13} />
            <circle className="nx-contour-dot" r={5} tabIndex={0} role="img" aria-label={peak.label} />
          </g>
        ))}
      </svg>
      {children}
    </section>
  );
});

/* ---- Metaballs ----------------------------------------------------------------------------- */

/** Where each ball starts, in viewBox units (1200×480); the wander keyframes carry them from there. */
const GOO_BALLS = [
  { cx: 330, cy: 300, r: 86 },
  { cx: 620, cy: 190, r: 104 },
  { cx: 900, cy: 300, r: 92 },
  { cx: 480, cy: 150, r: 72 },
  { cx: 760, cy: 350, r: 78 },
  { cx: 210, cy: 210, r: 64 },
  { cx: 1010, cy: 180, r: 68 },
] as const;

export interface MetaballsProps extends HTMLAttributes<HTMLElement> {
  /** How many balls wander, 3–7 (default 5). */
  count?: number;
  /** Pin the palette instead of following the theme. */
  variant?: 'light' | 'dark';
  children?: ReactNode;
}

/** A background that pools and splits: blurred balls snapped back into liquid by an alpha threshold — neighbours melt and pull apart. */
export const Metaballs = forwardRef<HTMLElement, MetaballsProps>(function Metaballs(
  { count = 5, variant, className, children, ...rest },
  forwarded,
) {
  const own = useRef<HTMLElement>(null);
  useBehavior(own, ambientStage, {});
  // One filter per instance, so two goos on a page never share a threshold.
  const goo = `nx-goo-${useId().replace(/:/g, '')}`;
  const balls = GOO_BALLS.slice(0, Math.max(3, Math.min(7, Math.round(count))));

  return (
    <section
      ref={mergeRefs(forwarded, own)}
      className={cx('nx-metaballs', className)}
      data-variant={variant}
      {...rest}
    >
      <svg className="nx-metaballs-svg" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <defs>
          <filter id={goo} x="-20%" y="-20%" width="140%" height="140%">
            <feGaussianBlur in="SourceGraphic" stdDeviation={16} />
            <feColorMatrix values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 24 -12" />
          </filter>
        </defs>
        <g className="nx-metaballs-goo" filter={`url(#${goo})`}>
          {balls.map((ball) => (
            <circle key={`${ball.cx}-${ball.cy}`} className="nx-metaballs-ball" cx={ball.cx} cy={ball.cy} r={ball.r} />
          ))}
        </g>
      </svg>
      {children}
    </section>
  );
});
