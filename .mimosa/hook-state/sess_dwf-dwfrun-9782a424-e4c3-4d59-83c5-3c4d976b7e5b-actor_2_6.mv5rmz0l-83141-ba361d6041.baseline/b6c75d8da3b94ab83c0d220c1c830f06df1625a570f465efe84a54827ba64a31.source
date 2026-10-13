/**
 * Icon motion (React): a set of line icons that ink themselves in, a toggle
 * button whose icon morphs between its two states, and looping status icons
 * for buttons and cards. Thin over css/blocks/icon-motion.css and the core
 * behaviours in js/blocks/icon-motion.ts.
 */
import {
  type ButtonHTMLAttributes,
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useRef,
} from 'react';
import { burst, drawIn, formatNumber, icons, pauseOffscreen, redrawIn } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useEvent } from '../internal/hooks';
import { useLocale, useT } from '../internal/provider';

/* ---- Icon draw ------------------------------------------------------------------ */

export interface IconDrawIcon {
  /** Key for the list (the index is used when absent). */
  id?: string;
  /** The caption under the glyph. */
  name: ReactNode;
  /** 24×24 path data; each string is one stroke of the pen. */
  paths: string[];
}

export interface IconDrawProps extends Omit<HTMLAttributes<HTMLUListElement>, 'children'> {
  icons: IconDrawIcon[];
  /** Milliseconds between one stroke and the next within a tile (default 130). */
  stagger?: number;
  /** Milliseconds by which each tile starts after the one before it (default 60). */
  itemStagger?: number;
  /** Re-ink a tile when the pointer rests on it (fine pointers only). */
  replayOnHover?: boolean;
}

function DrawTile({ icon, index, stagger, itemStagger, replay }: { icon: IconDrawIcon; index: number; stagger: number; itemStagger: number; replay: boolean }) {
  const ref = useRef<HTMLLIElement>(null);
  useBehavior(ref, drawIn, { stagger, delay: index * itemStagger });
  const again = useEvent(() => {
    if (ref.current) redrawIn(ref.current);
  });

  return (
    <li ref={ref} className="nx-icon-draw-tile" onPointerEnter={replay ? again : undefined}>
      <svg viewBox="0 0 24 24" aria-hidden="true">
        {icon.paths.map((d, i) => (
          <path key={i} className="nx-icon-draw-ink" pathLength={1} d={d} />
        ))}
      </svg>
      <span className="nx-icon-draw-name">{icon.name}</span>
    </li>
  );
}

/** A grid of line icons that enter blank and draw themselves stroke by stroke as they scroll into view. */
export const IconDraw = forwardRef<HTMLUListElement, IconDrawProps>(function IconDraw(
  { icons: set, stagger = 130, itemStagger = 60, replayOnHover, className, ...rest },
  ref,
) {
  return (
    <ul ref={ref} className={cx('nx-icon-draw', className)} {...rest}>
      {set.map((icon, i) => (
        <DrawTile key={icon.id ?? i} icon={icon} index={i} stagger={stagger} itemStagger={itemStagger} replay={!!replayOnHover} />
      ))}
    </ul>
  );
});

/* ---- Icon toggle ---------------------------------------------------------------- */

export type IconToggleVariant = 'heart' | 'bell' | 'bookmark' | 'play';

export interface IconToggleStateProps extends Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'children'> {
  /** Which morph the icon performs. */
  variant: IconToggleVariant;
  pressed?: boolean;
  defaultPressed?: boolean;
  onPressedChange?: (pressed: boolean) => void;
  /** Idle label (defaults per variant: Like, Notifications, Save, Play). */
  label?: string;
  /** Label once pressed (defaults to the idle label; play flips to Pause). */
  pressedLabel?: string;
  /** A running count — the bell wears it as a badge that pops in. */
  count?: number;
  /** No press scale, where the motion would distract. */
  static?: boolean;
}

/** One toggle's own glyph pair (play keeps its halves in the sliding window). */
const TOGGLE_SHAPES: Record<IconToggleVariant, { line: string[]; fill?: string[] }> = {
  heart: { line: [icons.heart.d], fill: [icons.heart.d] },
  bell: { line: ['M6.5 16.5V11a5.5 5.5 0 0 1 11 0v5.5l1.6 2.2H4.9z', 'M10 22.2a2.1 2.1 0 0 0 4 0'] },
  bookmark: { line: ['M7 3.5h10a1 1 0 0 1 1 1V20l-6-3.8L6 20V4.5a1 1 0 0 1 1-1z'], fill: ['M7 3.5h10a1 1 0 0 1 1 1V20l-6-3.8L6 20V4.5a1 1 0 0 1 1-1z'] },
  play: { line: [] },
};

/**
 * A button whose icon morphs between its two states: the heart and the
 * bookmark fill over their outline and scatter a burst, the bell swings while
 * its badge counts, play and pause glide through a sliding window. The state
 * lives in `aria-pressed` (controlled or not) and always reaches colour and
 * label too, never motion alone.
 */
export const IconToggleState = forwardRef<HTMLButtonElement, IconToggleStateProps>(function IconToggleState(
  { variant, pressed, defaultPressed, onPressedChange, label, pressedLabel, count, static: still, className, ...rest },
  ref,
) {
  const t = useT();
  const locale = useLocale();
  const [on, setOn] = useControllable(pressed, defaultPressed ?? false, onPressedChange);
  const own = useRef<HTMLButtonElement>(null);

  const idle =
    label ??
    (variant === 'heart' ? t('like') : variant === 'bell' ? t('notifications') : variant === 'play' ? t('play') : 'Save');
  const active = pressedLabel ?? (variant === 'play' ? t('pause') : idle);

  const toggle = useEvent(() => {
    const next = !on;
    setOn(next);
    // The burst belongs to arriving, never to leaving.
    if (next && (variant === 'heart' || variant === 'bookmark') && own.current) burst(own.current);
  });

  const shapes = TOGGLE_SHAPES[variant];

  return (
    <button
      ref={mergeRefs(ref, own)}
      type="button"
      className={cx('nx-icon-toggle', className)}
      data-variant={variant}
      data-static={still ? '' : undefined}
      aria-pressed={on}
      {...rest}
      onClick={toggle}
    >
      {variant === 'play' ? (
        <span className="nx-icon-toggle-window">
          <span className="nx-icon-toggle-track">
            <svg className="nx-icon-toggle-half" viewBox="0 0 24 24" aria-hidden="true">
              <path className="nx-icon-toggle-line" d={icons.play.d} />
            </svg>
            <svg className="nx-icon-toggle-half" viewBox="0 0 24 24" aria-hidden="true">
              <path className="nx-icon-toggle-line" d={icons.pause.d} />
            </svg>
          </span>
        </span>
      ) : (
        <span className="nx-icon-toggle-icon">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            {variant === 'bell' ? (
              <>
                <g className="nx-icon-toggle-swing">
                  <path className="nx-icon-toggle-line" d={shapes.line[0]} />
                </g>
                <path className="nx-icon-toggle-line" d={shapes.line[1]} />
              </>
            ) : (
              <>
                {shapes.line.map((d, i) => (
                  <path key={i} className="nx-icon-toggle-line" d={d} />
                ))}
                {shapes.fill?.map((d, i) => (
                  <path key={i} className="nx-icon-toggle-fill" d={d} />
                ))}
              </>
            )}
          </svg>
          {variant === 'bell' && on && count !== undefined && <span className="nx-icon-toggle-badge">{formatNumber(count, locale)}</span>}
        </span>
      )}
      <span className="nx-icon-toggle-label">
        <span>{idle}</span>
        <span data-off>{active}</span>
      </span>
    </button>
  );
});

/* ---- Icon loop ------------------------------------------------------------------ */

export type IconLoopKind = 'dots' | 'arc' | 'sync' | 'download' | 'upload' | 'search';

export interface IconLoopProps extends HTMLAttributes<HTMLElement> {
  /** Which loop the icon runs. */
  kind: IconLoopKind;
  /** The status the loop stands for — the glyph itself is decorative. */
  label: string;
  /** 0–100 for kind="upload": how much of the arrow is filled. */
  progress?: number;
  /** kind="search": the magnifier pulses while true. */
  busy?: boolean;
  /** Just the moving glyph, for inside buttons and inputs. */
  bare?: boolean;
}

/**
 * A looping status icon: dots or an arc while something runs, counter-rotating
 * sync arrows, a download bouncing into its tray, an upload arrow whose fill is
 * the progress, a magnifier that pulses while a search is busy. The loops park
 * while off screen and stop under reduced motion — the text beside them always
 * carries the status alone.
 */
export const IconLoop = forwardRef<HTMLElement, IconLoopProps>(function IconLoop(
  { kind, label, progress, busy, bare, className, style, ...rest },
  ref,
) {
  const own = useRef<HTMLElement>(null);
  const locale = useLocale();
  useBehavior(own, pauseOffscreen, {});

  const vars = {
    ...(progress !== undefined ? { '--_p': `${Math.round(Math.min(100, Math.max(0, progress)))}%` } : null),
    ...style,
  } as CSSProperties;

  const glyph =
    kind === 'dots' ? (
      <span className="nx-icon-loop-dots">
        <i />
        <i />
        <i />
      </span>
    ) : kind === 'arc' ? (
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <circle className="nx-icon-loop-arc" cx={12} cy={12} r={9} pathLength={100} />
      </svg>
    ) : kind === 'sync' ? (
      <svg className="nx-icon-loop-sync" viewBox="0 0 24 24" aria-hidden="true">
        <g className="nx-icon-loop-cw">
          <path className="nx-icon-loop-line" d="M20 13a8 8 0 0 1-14.2 3.2" />
          <path className="nx-icon-loop-line" d="M5 13.5V17h3.5" />
        </g>
        <g className="nx-icon-loop-ccw">
          <path className="nx-icon-loop-line" d="M4 11a8 8 0 0 1 14.2-3.2" />
          <path className="nx-icon-loop-line" d="M19 10.5V7h-3.5" />
        </g>
      </svg>
    ) : kind === 'download' ? (
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <g className="nx-icon-loop-bounce">
          <path className="nx-icon-loop-line" d="M12 4.5V15" />
          <path className="nx-icon-loop-line" d="M6.5 10 12 15.5 17.5 10" />
        </g>
        <path className="nx-icon-loop-line" d="M5 20.5h14" />
      </svg>
    ) : kind === 'upload' ? (
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path className="nx-icon-loop-line" d="M12 15.5V4.5" />
        <path className="nx-icon-loop-line" d="M6.5 10 12 4.5 17.5 10" />
        <path className="nx-icon-loop-line" d="M5 20h14" />
        <g className="nx-icon-loop-upfill">
          <path d="M11 6h2v9.5h-2z" />
          <path d="M6.5 10.5 12 5l5.5 5.5z" />
        </g>
      </svg>
    ) : (
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path className="nx-icon-loop-line" d="M17.5 10.5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" />
        <path className="nx-icon-loop-line" d="M16.5 16.5 21 21" />
      </svg>
    );

  return (
    <span
      ref={mergeRefs(ref, own)}
      className={cx('nx-icon-loop', className)}
      role="img"
      aria-label={label}
      data-kind={kind}
      data-bare={bare ? '' : undefined}
      data-busy={busy ? '' : undefined}
      style={vars}
      {...rest}
    >
      {glyph}
      {kind === 'upload' && progress !== undefined && (
        <span className="nx-icon-loop-pct" aria-hidden="true">{`${formatNumber(Math.round(Math.min(100, Math.max(0, progress))), locale)}%`}</span>
      )}
    </span>
  );
});
