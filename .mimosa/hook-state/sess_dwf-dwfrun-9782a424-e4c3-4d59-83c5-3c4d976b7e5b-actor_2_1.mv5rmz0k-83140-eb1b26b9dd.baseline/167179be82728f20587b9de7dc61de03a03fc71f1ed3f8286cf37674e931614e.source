/**
 * Effects & layout motion (React): border beam, goo stack, glitch text,
 * typewriter, resizable panels, scroll progress, auto height, dynamic island,
 * spotlight tour and image reveal. Thin over css/blocks/effects.css and the
 * core behaviours in js/blocks/effects.ts.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  type RefObject,
  Children,
  forwardRef,
  useEffect,
  useId,
  useRef,
  useState,
} from 'react';
import {
  type IconName,
  type Side,
  type SpringName,
  type TypewriterUnit,
  autoHeight,
  borderBeam,
  dynamicIsland,
  imageReveal,
  resizablePanels,
  scrollProgress,
  scrollToStart,
  spotlightTour,
  textDirection,
  tourStepInfo,
  typewriter,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useEvent, useInert, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { Button } from '../components/button';

/* ---- Border beam ------------------------------------------------------------------ */

export type BorderBeamTone = 'accent' | 'gold' | 'violet' | 'success';

export interface BorderBeamProps extends HTMLAttributes<HTMLDivElement> {
  /** Length of the beam in degrees of the lap (default 70). */
  size?: number;
  /** Seconds per lap (default 6). */
  duration?: number;
  /** Ring thickness in px (default 1.5). */
  width?: number;
  tone?: BorderBeamTone;
  /** Custom tail and head colours (any CSS colour, ideally a token). */
  colorFrom?: string;
  colorTo?: string;
  /** Run the other way round. */
  reverse?: boolean;
  /** Start offset in seconds, for several beams on one page. */
  delay?: number;
  /** Corner radius of the wrapper (the beam follows it). */
  radius?: string;
  /** Keep running off screen (default: paused there). */
  alwaysRun?: boolean;
}

/** A glowing light segment that travels around its border. Wraps any element. */
export const BorderBeam = forwardRef<HTMLDivElement, BorderBeamProps>(function BorderBeam(
  { size, duration, width, tone, colorFrom, colorTo, reverse, delay, radius, alwaysRun, className, style, children, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, borderBeam, { pauseOffscreen: !alwaysRun });
  const vars = {
    ...(size !== undefined ? { '--nx-beam-size': `${size}deg` } : null),
    ...(duration !== undefined ? { '--nx-beam-duration': `${duration}s` } : null),
    ...(width !== undefined ? { '--nx-beam-width': `${width}px` } : null),
    ...(delay !== undefined ? { '--nx-beam-delay': `${-Math.abs(delay)}s` } : null),
    ...(colorFrom ? { '--nx-beam-from': colorFrom } : null),
    ...(colorTo ? { '--nx-beam-to': colorTo } : null),
    ...(radius ? { borderRadius: radius } : null),
  } as CSSProperties;
  return (
    <div
      ref={mergeRefs(ref, forwarded)}
      className={cx('nx-beam', className)}
      data-tone={tone && tone !== 'accent' ? tone : undefined}
      data-reverse={reverse ? '' : undefined}
      style={{ ...vars, ...style }}
      {...rest}
    >
      {children}
    </div>
  );
});

/* ---- Goo stack ------------------------------------------------------------------------ */

export interface GooStackItem {
  id: string;
  label: ReactNode;
  icon?: IconName;
}

export interface GooStackProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  items: GooStackItem[];
  /** Called with the id of the pill whose dismiss button was pressed. */
  onDismiss?: (id: string) => void;
  /** 'auto' (default) melts the stack at rest and splits it on hover or focus. */
  state?: 'auto' | 'merged' | 'split';
  /** Blur radius of the goo, px (default 8): more melts further apart. */
  strength?: number;
  /** Accessible name of the list. */
  label?: string;
  /** Accessible name of each dismiss button. */
  dismissLabel?: string;
}

type GooRow = { item: GooStackItem; state: 'enter' | 'idle' | 'leave' };

const GOO_LEAVE_MS = 230;

/** Merge the rows on screen with the new items: new ids enter, missing ids stay a moment to leave. */
function gooRows(previous: GooRow[], items: GooStackItem[], mounted: boolean): GooRow[] {
  const known = new Map(previous.map((row) => [row.item.id, row]));
  const next: GooRow[] = items.map((item) => {
    const before = known.get(item.id);
    return { item, state: before && before.state !== 'leave' ? before.state : mounted ? 'enter' : 'idle' };
  });
  const ids = new Set(items.map((item) => item.id));
  previous.forEach((row, index) => {
    if (!ids.has(row.item.id)) next.splice(Math.min(index, next.length), 0, { item: row.item, state: 'leave' });
  });
  return next;
}

/**
 * A stack of pills that melt together like liquid at rest and split apart on
 * hover or focus: an SVG goo filter on a back layer of solid shapes, the real
 * content crisp on a front layer.
 */
export const GooStack = forwardRef<HTMLDivElement, GooStackProps>(function GooStack(
  { items, onDismiss, state = 'auto', strength = 8, label, dismissLabel = 'Dismiss', className, style, ...rest },
  ref,
) {
  const id = `nx-goo-${useId().replace(/[^a-zA-Z0-9_-]/g, '')}`;
  const mounted = useRef(false);
  const [rows, setRows] = useState<GooRow[]>(() => gooRows([], items, false));

  useEffect(() => {
    if (!mounted.current) {
      mounted.current = true;
      return;
    }
    setRows((previous) => gooRows(previous, items, true));
    const timer = window.setTimeout(() => setRows((previous) => previous.filter((row) => row.state !== 'leave')), GOO_LEAVE_MS);
    return () => window.clearTimeout(timer);
  }, [items]);

  const pill = (row: GooRow, front: boolean) => (
    <li key={row.item.id} className="nx-goo-pill" data-state={row.state === 'idle' ? undefined : row.state}>
      {row.item.icon && (
        <span className="nx-goo-icon" aria-hidden="true">
          <Icon name={row.item.icon} />
        </span>
      )}
      <span className="nx-goo-label">{row.item.label}</span>
      {front ? (
        <button type="button" className="nx-goo-dismiss" aria-label={dismissLabel} disabled={row.state === 'leave'} onClick={() => onDismiss?.(row.item.id)}>
          <Icon name="x" />
        </button>
      ) : (
        <span className="nx-goo-dismiss" />
      )}
    </li>
  );

  return (
    <div ref={ref} className={cx('nx-goo', className)} data-state={state === 'auto' ? undefined : state} style={{ '--_goo': `url(#${id})`, ...style } as CSSProperties} {...rest}>
      <svg className="nx-goo-filter" aria-hidden="true" focusable="false">
        <filter id={id}>
          <feGaussianBlur in="SourceGraphic" stdDeviation={strength} result="blur" />
          <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -9" result="goo" />
          <feComposite in="SourceGraphic" in2="goo" operator="atop" />
        </filter>
      </svg>
      <ul className="nx-goo-layer nx-goo-blobs" aria-hidden="true">
        {rows.map((row) => pill(row, false))}
      </ul>
      <ul className="nx-goo-layer nx-goo-items" aria-label={label} aria-live="polite">
        {rows.map((row) => pill(row, true))}
      </ul>
    </div>
  );
});

/* ---- Glitch text ------------------------------------------------------------------------ */

export interface GlitchTextProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'children'> {
  /** The text; it stays whole (never split into letters), so Persian keeps its joins. */
  text: string;
  /** hover (default; also when a surrounding link or button is hovered), loop or always. */
  trigger?: 'hover' | 'loop' | 'always';
}

/** Text that splits into chromatic, sliced copies — on hover, in short bursts, or all the time. */
export const GlitchText = forwardRef<HTMLSpanElement, GlitchTextProps>(function GlitchText({ text, trigger = 'hover', className, ...rest }, ref) {
  return (
    <span ref={ref} className={cx('nx-glitch', className)} data-trigger={trigger} dir={textDirection(text)} {...rest}>
      <span className="nx-glitch-text">{text}</span>
      <span className="nx-glitch-layer" data-layer="a" aria-hidden="true">
        {text}
      </span>
      <span className="nx-glitch-layer" data-layer="b" aria-hidden="true">
        {text}
      </span>
    </span>
  );
});

/* ---- Typewriter --------------------------------------------------------------------------- */

export interface TypewriterProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'children'> {
  phrases: string[];
  /** Type by grapheme (default) or by whole word. */
  by?: TypewriterUnit;
  /** ms per typed piece (default 55). */
  typeSpeed?: number;
  /** ms per deleted piece (default 28). */
  deleteSpeed?: number;
  /** ms a finished phrase stays (default 1800). */
  hold?: number;
  /** Cycle through the phrases forever (default) or stop on the last. */
  loop?: boolean;
  startDelay?: number;
  caret?: 'bar' | 'block' | 'none';
  /** Called when a phrase is fully typed. */
  onPhrase?: (index: number) => void;
}

/**
 * Types phrases a character at a time (a grapheme: Persian letters with their
 * marks and joiners stay whole), deletes back to what the next phrase shares
 * and types on. Screen readers get the phrases once, as plain text.
 */
export const Typewriter = forwardRef<HTMLSpanElement, TypewriterProps>(function Typewriter(
  { phrases, by = 'grapheme', typeSpeed, deleteSpeed, hold, loop = true, startDelay, caret = 'bar', onPhrase, className, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLSpanElement>(null);
  const handlePhrase = useEvent(onPhrase);
  useBehavior(ref, typewriter, { phrases, by, typeSpeed, deleteSpeed, hold, loop, startDelay, onPhrase: handlePhrase });
  const first = phrases[0] ?? '';
  return (
    <span ref={mergeRefs(ref, forwarded)} className={cx('nx-typewriter', className)} data-caret={caret === 'bar' ? undefined : caret} {...rest}>
      <span className="nx-visually-hidden">{loop ? phrases.join(' · ') : phrases[phrases.length - 1]}</span>
      <span className="nx-typewriter-line" aria-hidden="true" dir={textDirection(first)}>
        <span className="nx-typewriter-text">{first}</span>
        <span className="nx-typewriter-caret" />
      </span>
    </span>
  );
});

/* ---- Resizable panels ----------------------------------------------------------------------- */

export interface ResizablePanelsProps extends HTMLAttributes<HTMLDivElement> {
  orientation?: 'horizontal' | 'vertical';
  /** Remember the sizes in localStorage under this key. */
  storageKey?: string;
  /** Keyboard step in percent (Shift: four times). */
  step?: number;
  /** Called with the sizes (percent) after every change. */
  onSizesChange?: (sizes: number[]) => void;
}

/**
 * Split panes with draggable separators: children are ResizablePane and
 * ResizableHandle in turn. Nest a group inside a pane for a grid of panes.
 */
export const ResizablePanels = forwardRef<HTMLDivElement, ResizablePanelsProps>(function ResizablePanels(
  { orientation = 'horizontal', storageKey, step, onSizesChange, className, children, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLDivElement>(null);
  const onResize = useEvent(onSizesChange);
  const count = Children.count(children);
  useEffect(() => {
    if (!ref.current) return;
    return resizablePanels(ref.current, { storageKey, step, onResize: (sizes) => onResize(sizes) });
  }, [storageKey, step, count, orientation, onResize]);
  return (
    <div ref={mergeRefs(ref, forwarded)} className={cx('nx-resizable', className)} data-orientation={orientation} {...rest}>
      {children}
    </div>
  );
});

export interface ResizablePaneProps extends HTMLAttributes<HTMLDivElement> {
  /** Starting size in percent; panes without one share what is left. */
  defaultSize?: number;
  minSize?: number;
  maxSize?: number;
  /** May fold away (dragged under half its minimum, double-click or Enter on its handle). */
  collapsible?: boolean;
  collapsedSize?: number;
}

export const ResizablePane = forwardRef<HTMLDivElement, ResizablePaneProps>(function ResizablePane(
  { defaultSize, minSize, maxSize, collapsible, collapsedSize, className, style, ...rest },
  ref,
) {
  return (
    <div
      ref={ref}
      className={cx('nx-resizable-pane', className)}
      data-size={defaultSize}
      data-min={minSize}
      data-max={maxSize}
      data-collapsible={collapsible ? '' : undefined}
      data-collapsed-size={collapsedSize}
      style={defaultSize !== undefined ? { flex: `${defaultSize} 1 0px`, ...style } : style}
      {...rest}
    />
  );
});

export interface ResizableHandleProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  /** Accessible name of the separator. */
  label?: string;
}

/** The separator between two panes: drag it, or focus it and use the arrows, Home, End and Enter. */
export const ResizableHandle = forwardRef<HTMLDivElement, ResizableHandleProps>(function ResizableHandle({ label = 'Resize', className, ...rest }, ref) {
  return <div ref={ref} role="separator" tabIndex={0} aria-label={label} className={cx('nx-resizable-handle', className)} {...rest} />;
});

/* ---- Scroll progress -------------------------------------------------------------------------- */

export interface ScrollProgressProps extends Omit<HTMLAttributes<HTMLElement>, 'onProgress'> {
  /** A hairline bar (default) or a back-to-top button with a filling ring. */
  variant?: 'bar' | 'circle';
  /** Measure reading progress through this element (a CSS selector) instead of the whole page. */
  target?: string;
  /** The scrolling box (a CSS selector); the page when absent. */
  container?: string;
  /** fixed (default) pins to the viewport; sticky / static keep it in flow, for a scrolling box. */
  position?: 'fixed' | 'sticky' | 'static';
  /** Accessible name of the bar, or of the circle's button. */
  label?: string;
  onProgress?: (progress: number) => void;
}

/** Reading progress as a top bar or a ring, driven by CSS scroll timelines where possible. */
export function ScrollProgress({ variant = 'bar', target, container, position = 'fixed', label, onProgress, className, onClick, ...rest }: ScrollProgressProps) {
  const ref = useRef<HTMLElement>(null);
  const handle = useEvent(onProgress);
  useEffect(() => {
    if (!ref.current) return;
    return scrollProgress(ref.current, { target, container, onProgress: (p) => handle(p) });
  }, [target, container, handle]);
  const common = {
    className: cx('nx-scroll-progress', className),
    'data-variant': variant,
    'data-position': position === 'fixed' ? undefined : position,
    'data-source': target || container ? 'element' : undefined,
  };

  if (variant === 'circle') {
    return (
      <button
        ref={ref as RefObject<HTMLButtonElement>}
        type="button"
        aria-label={label ?? 'Back to top'}
        {...common}
        onClick={(event) => {
          onClick?.(event);
          if (!event.defaultPrevented) scrollToStart(container);
        }}
        {...(rest as HTMLAttributes<HTMLButtonElement>)}
      >
        <svg className="nx-scroll-progress-ring" viewBox="0 0 48 48" aria-hidden="true" focusable="false">
          <circle className="nx-scroll-progress-ring-track" cx="24" cy="24" r="21" />
          <circle className="nx-scroll-progress-ring-fill" cx="24" cy="24" r="21" pathLength={100} />
        </svg>
        <Icon name="arrow-up" className="nx-scroll-progress-icon" />
      </button>
    );
  }
  return (
    <div
      ref={ref as RefObject<HTMLDivElement>}
      role="progressbar"
      aria-label={label ?? 'Reading progress'}
      aria-valuemin={0}
      aria-valuemax={100}
      aria-valuenow={0}
      {...common}
      {...(rest as HTMLAttributes<HTMLDivElement>)}
    >
      <span className="nx-scroll-progress-fill" />
    </div>
  );
}

/* ---- Auto height ------------------------------------------------------------------------------- */

export interface AutoHeightProps extends HTMLAttributes<HTMLDivElement> {
  /** The spring the height follows (default gentle). */
  spring?: SpringName;
  /** Fold to zero height (animated natively where interpolate-size exists). */
  collapsed?: boolean;
}

/** A container whose height springs to fit its content whenever the content changes. */
export const AutoHeight = forwardRef<HTMLDivElement, AutoHeightProps>(function AutoHeight({ spring = 'gentle', collapsed = false, className, children, ...rest }, forwarded) {
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, autoHeight, { spring });
  useInert(ref, collapsed);
  return (
    <div ref={mergeRefs(ref, forwarded)} className={cx('nx-auto-height', className)} data-collapsed={collapsed ? '' : undefined} aria-hidden={collapsed || undefined} {...rest}>
      <div>{children}</div>
    </div>
  );
});

/* ---- Dynamic island ------------------------------------------------------------------------------ */

export interface DynamicIslandProps extends HTMLAttributes<HTMLDivElement> {
  /** The name of the IslandView to show; the pill morphs to its size. */
  view: string;
  /** Accessible name of the island region. */
  label?: string;
  /** Text announced politely whenever it changes ("Timer started"). */
  announcement?: string;
}

/**
 * A dark pill that morphs between views — compact, expanded, anything —
 * springing its width, height and radius while the content cross-fades.
 * Children are IslandView elements; state lives with you (`view`).
 */
export const DynamicIsland = forwardRef<HTMLDivElement, DynamicIslandProps>(function DynamicIsland({ view, label = 'Live activity', announcement, className, children, ...rest }, forwarded) {
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, dynamicIsland, {});
  return (
    <div role="region" aria-label={label} {...rest} className={cx('nx-island-wrap', className)}>
      <div ref={mergeRefs(ref, forwarded)} className="nx-island" data-active={view}>
        {children}
      </div>
      <span className="nx-visually-hidden" aria-live="polite">
        {announcement}
      </span>
    </div>
  );
});

export interface IslandViewProps extends HTMLAttributes<HTMLDivElement> {
  name: string;
  /** Expanded views get more padding and a minimum width. */
  size?: 'compact' | 'expanded';
}

export function IslandView({ name, size = 'compact', className, ...rest }: IslandViewProps) {
  return <div className={cx('nx-island-view', className)} data-view={name} data-size={size} {...rest} />;
}

/* ---- Spotlight tour -------------------------------------------------------------------------------- */

export interface SpotlightTourStep {
  /** CSS selector of the element to spotlight; none centres the step on the dimmed page. */
  target?: string;
  title: ReactNode;
  body?: ReactNode;
  side?: Side;
}

export interface SpotlightTourLabels {
  next?: string;
  back?: string;
  skip?: string;
  done?: string;
  /** "Step 2 of 5". */
  step?: (index: number, count: number) => string;
}

export interface SpotlightTourProps {
  steps: SpotlightTourStep[];
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  step?: number;
  defaultStep?: number;
  onStepChange?: (step: number) => void;
  /** Called when the last step's button is pressed (before closing). */
  onFinish?: () => void;
  /** Space around the target, px. */
  padding?: number;
  labels?: SpotlightTourLabels;
  className?: string;
}

const tourLabels: Required<SpotlightTourLabels> = {
  next: 'Next',
  back: 'Back',
  skip: 'Skip tour',
  done: 'Done',
  step: (index, count) => `Step ${index + 1} of ${count}`,
};

/**
 * Onboarding coachmarks: dims the page with a cut-out around each step's
 * target, scrolls it into view and shows the step beside it. Arrows move
 * between steps (in reading direction), Escape skips, focus stays in the
 * step and returns where it was when the tour ends.
 */
export function SpotlightTour({
  steps,
  open: openProp,
  defaultOpen = false,
  onOpenChange,
  step: stepProp,
  defaultStep = 0,
  onStepChange,
  onFinish,
  padding = 8,
  labels,
  className,
}: SpotlightTourProps) {
  const [open, setOpen] = useControllable(openProp, defaultOpen, onOpenChange);
  const [index, setIndex] = useControllable(stepProp, defaultStep, onStepChange);
  const ref = useRef<HTMLDivElement>(null);
  const returnTo = useRef<HTMLElement | null>(null);
  const titleId = useId();
  const bodyId = useId();
  const text = { ...tourLabels, ...labels };
  const info = tourStepInfo(index, steps.length);
  const current = steps[info.index];

  const close = useEvent(() => setOpen(false));
  const next = useEvent(() => {
    if (info.last) {
      onFinish?.();
      setOpen(false);
    } else setIndex(info.index + 1);
  });
  const back = useEvent(() => {
    if (!info.first) setIndex(info.index - 1);
  });

  useIsoLayoutEffect(() => {
    const el = ref.current;
    if (!open || !el) return;
    returnTo.current = document.activeElement instanceof HTMLElement ? document.activeElement : null;
    try {
      if (typeof el.showPopover === 'function' && !el.matches(':popover-open')) el.showPopover();
    } catch {
      /* not connected yet, or no popover support */
    }
    return () => {
      try {
        if (typeof el.hidePopover === 'function' && el.matches(':popover-open')) el.hidePopover();
      } catch {
        /* already hidden */
      }
      returnTo.current?.focus({ preventScroll: true });
    };
  }, [open]);

  useEffect(() => {
    const el = ref.current;
    if (!open || !el || !current) return;
    return spotlightTour(el, { target: current.target ?? null, padding, side: current.side ?? 'bottom', onNext: () => next(), onBack: () => back(), onClose: () => close() });
  }, [open, info.index, current, padding, next, back, close]);

  if (!open || !current) return null;

  return (
    <div ref={ref} className={cx('nx-tour', className)} popover="manual">
      <div className="nx-tour-catcher" aria-hidden="true" />
      <div className="nx-tour-hole" aria-hidden="true" />
      <div key={info.index} className="nx-tour-pop" role="dialog" aria-modal="true" aria-labelledby={titleId} aria-describedby={current.body ? bodyId : undefined} tabIndex={-1}>
        <p className="nx-tour-step">{text.step(info.index, info.count)}</p>
        <h2 id={titleId} className="nx-tour-title">
          {current.title}
        </h2>
        {current.body && (
          <div id={bodyId} className="nx-tour-body">
            {current.body}
          </div>
        )}
        <div className="nx-tour-foot">
          <span className="nx-tour-dots" aria-hidden="true">
            {steps.map((_, i) => (
              <i key={i} data-current={i === info.index ? '' : undefined} />
            ))}
          </span>
          <div className="nx-tour-actions">
            {info.first ? (
              <Button variant="ghost" size="sm" onClick={close}>
                {text.skip}
              </Button>
            ) : (
              <Button variant="ghost" size="sm" onClick={back}>
                {text.back}
              </Button>
            )}
            <Button variant="primary" size="sm" onClick={next} iconEnd={info.last ? 'check' : 'arrow-right'}>
              {info.last ? text.done : text.next}
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ---- Image reveal ------------------------------------------------------------------------------------ */

export interface ImageRevealProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  /** The image; leave empty while it is still being generated. */
  src?: string;
  alt: string;
  /** A tiny blurred preview (LQIP); the aurora gradient when absent. */
  placeholder?: string;
  /** 0–100 while generating or uploading; the image stays hidden until it reaches 100. */
  progress?: number;
  /** CSS aspect ratio of the frame (default "4 / 3"). */
  ratio?: string;
  /** Status text while waiting (default "Loading"). */
  label?: string;
  /** Status text when the image fails (default "Could not load the image"). */
  errorLabel?: string;
}

/** An image placeholder that resolves out of pixel noise into the loaded image, with an optional progress. */
export const ImageReveal = forwardRef<HTMLDivElement, ImageRevealProps>(function ImageReveal(
  { src, alt, placeholder, progress, ratio, label = 'Loading', errorLabel = 'Could not load the image', className, style, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, imageReveal, {});
  const pending = progress !== undefined && progress < 100;
  const value = progress === undefined ? undefined : Math.max(0, Math.min(100, Math.round(progress)));
  return (
    <div
      ref={mergeRefs(ref, forwarded)}
      className={cx('nx-image-reveal', className)}
      data-pending={pending || !src ? '' : undefined}
      data-progress={value}
      style={{ ...(ratio ? { '--_ratio': ratio } : null), ...(value !== undefined ? { '--_p': value / 100 } : null), ...style } as CSSProperties}
      {...rest}
    >
      {placeholder ? <img className="nx-image-reveal-placeholder" src={placeholder} alt="" aria-hidden="true" /> : <div className="nx-image-reveal-placeholder" aria-hidden="true" />}
      <canvas className="nx-image-reveal-noise" aria-hidden="true" />
      <img className="nx-image-reveal-img" src={src || undefined} alt={alt} decoding="async" />
      {value !== undefined ? (
        <div className="nx-image-reveal-status" role="progressbar" aria-label={label} aria-valuemin={0} aria-valuemax={100} aria-valuenow={value}>
          <span className="nx-image-reveal-status-row">
            <span className="nx-image-reveal-label">{label}</span>
            <span className="nx-image-reveal-error">{errorLabel}</span>
            <span>{value}%</span>
          </span>
          <span className="nx-image-reveal-bar">
            <i />
          </span>
        </div>
      ) : (
        <div className="nx-image-reveal-status" role="status">
          <span className="nx-image-reveal-label">{label}</span>
          <span className="nx-image-reveal-error">{errorLabel}</span>
        </div>
      )}
    </div>
  );
});
