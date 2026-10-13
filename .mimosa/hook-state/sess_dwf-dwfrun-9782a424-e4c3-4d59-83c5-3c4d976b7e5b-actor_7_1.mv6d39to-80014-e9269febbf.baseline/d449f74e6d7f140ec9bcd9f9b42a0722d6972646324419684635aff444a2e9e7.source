/**
 * Text effects (React): a gradient that flows through the glyphs, outline type
 * that fills from the baseline, and type extruded by a stacked shadow that
 * tilts under the pointer. Thin over css/blocks/text-fx.css; the tilt reuses
 * the core tilt() behaviour and the in-view fill reuses reveal(). The text is
 * never split into letters, so Persian and Arabic keep their joins.
 */
import {
  type CSSProperties,
  type KeyboardEvent as ReactKeyboardEvent,
  type HTMLAttributes,
  forwardRef,
  useMemo,
  useRef,
} from 'react';
import { reveal, textDirection, tilt } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior } from '../internal/hooks';

type TextElement = 'span' | 'p' | 'div' | 'h1' | 'h2' | 'h3' | 'h4';

/* ---- Gradient flow ------------------------------------------------------------------ */

export type TextFlowPalette = 'aurora' | 'ocean' | 'dusk';

export interface TextGradientFlowProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  /** The text, kept whole so every script keeps its joins. */
  children: string;
  /** The colour set (default aurora). */
  palette?: TextFlowPalette;
  /** Flow speed: 1 is one lap every 8s (default 1). */
  speed?: number;
  as?: TextElement;
}

/** A headline whose ink is a gradient that keeps flowing through the glyphs, at a speed you set. */
export const TextGradientFlow = forwardRef<HTMLElement, TextGradientFlowProps>(function TextGradientFlow(
  { children, palette, speed = 1, as = 'span', className, style, ...rest },
  ref,
) {
  const Tag = as as 'span';
  const lap = `${(8 / Math.min(Math.max(speed, 0.1), 16)).toFixed(3)}s`;
  return (
    <Tag
      ref={ref as never}
      dir={textDirection(children)}
      className={cx('nx-text-flow', className)}
      data-palette={palette && palette !== 'aurora' ? palette : undefined}
      style={{ '--nx-flow-duration': lap, ...style } as CSSProperties}
      {...rest}
    >
      {children}
    </Tag>
  );
});

/* ---- Outline fill -------------------------------------------------------------------- */

export type TextOutlineFillBody = 'gradient' | 'solid' | 'pattern';
export type TextOutlineFillTrigger = 'hover' | 'inview' | 'always';

export interface TextOutlineFillProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'children'> {
  /** The word or line, kept whole. */
  children: string;
  /** What fills the outline: a gradient (default), a solid colour or a woven pattern. */
  body?: TextOutlineFillBody;
  /** hover fills it under the pointer (default), inview fills it once it scrolls into view, always starts filled. */
  trigger?: TextOutlineFillTrigger;
  as?: 'span' | 'p' | 'h1' | 'h2' | 'h3' | 'h4';
}

/** Outline type that earns its ink: the stroke is the resting state, the fill wipes up from the baseline. */
export const TextOutlineFill = forwardRef<HTMLSpanElement, TextOutlineFillProps>(function TextOutlineFill(
  { body, trigger = 'hover', as = 'span', className, children, ...rest },
  ref,
) {
  const own = useRef<HTMLSpanElement>(null);
  useBehavior(own, reveal, { once: true, threshold: 0.35 }, trigger === 'inview');
  const Tag = as as 'span';

  return (
    <Tag
      ref={mergeRefs(ref, own) as never}
      dir={textDirection(children)}
      className={cx('nx-outline-fill', className)}
      data-body={body && body !== 'gradient' ? body : undefined}
      data-trigger={trigger === 'hover' ? undefined : trigger}
      data-nx-reveal={trigger === 'inview' ? '' : undefined}
      {...rest}
    >
      <span className="nx-visually-hidden">{children}</span>
      <span className="nx-outline-fill-ghost" aria-hidden="true">
        {children}
      </span>
      <span className="nx-outline-fill-fill" aria-hidden="true">
        {children}
      </span>
    </Tag>
  );
});

/* ---- Extrude ------------------------------------------------------------------------- */

export type TextExtrudeTone = 'accent' | 'gold' | 'ink';

export interface TextExtrudeProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  /** The text, kept whole — the extrusion only paints behind it. */
  children: string;
  /** Extrusion steps, 3–12 (default 6); each sits one pixel further out. */
  depth?: number;
  /** Largest tilt in degrees (default 10). */
  max?: number;
  tone?: TextExtrudeTone;
  /** No tilt, and no focusable stage. */
  static?: boolean;
  as?: TextElement;
}

/** Type that stands off the page: a stacked shadow extrudes it and it leans toward the pointer (or the arrow keys). */
export const TextExtrude = forwardRef<HTMLElement, TextExtrudeProps>(function TextExtrude(
  { children, depth = 6, max = 10, tone, static: still, as = 'span', className, onKeyDown, ...rest },
  forwarded,
) {
  const own = useRef<HTMLElement>(null);
  useBehavior(own, tilt, { max }, !still);

  // One shadow per step, each a pixel further out toward the inline end (the
  // calc flips in RTL), then a soft glow — every colour stays a CSS variable,
  // so each theme repaints the stack.
  const shadow = useMemo(() => {
    const steps = Math.min(12, Math.max(1, Math.round(depth)));
    const layers: string[] = [];
    for (let i = 1; i <= steps; i++) layers.push(`calc(${i}px * var(--nx-dir, 1)) ${i}px 0 var(--_shade)`);
    layers.push(`calc(${steps}px * var(--nx-dir, 1)) ${steps}px 1rem var(--_glow)`);
    return layers.join(', ');
  }, [depth]);

  /** The arrow keys nudge the tilt (Shift: wider steps); Escape or Home levels it. */
  const press = (event: ReactKeyboardEvent<HTMLElement>) => {
    onKeyDown?.(event);
    const el = own.current;
    if (still || !el || event.defaultPrevented || event.ctrlKey || event.metaKey || event.altKey) return;
    if (typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const read = (name: string) => parseFloat(el.style.getPropertyValue(name)) || 0;
    const clamp = (value: number) => Math.min(24, Math.max(-24, value));
    const step = event.shiftKey ? 8 : 4;
    let rx = read('--nx-rx');
    let ry = read('--nx-ry');
    if (event.key === 'ArrowUp') rx = clamp(rx + step);
    else if (event.key === 'ArrowDown') rx = clamp(rx - step);
    else if (event.key === 'ArrowRight') ry = clamp(ry + step);
    else if (event.key === 'ArrowLeft') ry = clamp(ry - step);
    else if (event.key === 'Escape' || event.key === 'Home') {
      rx = 0;
      ry = 0;
    } else return;
    event.preventDefault();
    el.style.setProperty('--nx-rx', `${rx}deg`);
    el.style.setProperty('--nx-ry', `${ry}deg`);
  };

  const Tag = as as 'span';
  return (
    <Tag
      ref={mergeRefs(forwarded, own) as never}
      dir={textDirection(children)}
      className={cx('nx-text-extrude', className)}
      data-tone={tone && tone !== 'accent' ? tone : undefined}
      data-static={still ? '' : undefined}
      tabIndex={still ? undefined : 0}
      onKeyDown={press}
      {...rest}
    >
      <span className="nx-text-extrude-word" style={{ textShadow: shadow } as CSSProperties}>
        {children}
      </span>
    </Tag>
  );
});
