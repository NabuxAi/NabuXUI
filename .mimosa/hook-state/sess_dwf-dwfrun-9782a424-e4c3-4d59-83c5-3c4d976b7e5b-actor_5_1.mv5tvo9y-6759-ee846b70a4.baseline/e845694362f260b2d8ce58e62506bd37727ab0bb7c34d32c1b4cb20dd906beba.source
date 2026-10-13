/**
 * Micro-interactions (React): the confetti button, the ripple surface, the
 * drag spring, the reaction bar and the live ticker. Thin over
 * css/blocks/micro-fx.css and the core behaviours in js/blocks/micro-fx.ts —
 * the components render the markup and own the state; the physics, the canvas
 * and the cleanup stay in the core.
 */
import {
  type ButtonHTMLAttributes,
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  type RefObject,
  forwardRef,
  useEffect,
  useRef,
  useState,
} from 'react';
import {
  type ConfettiController,
  type ConfettiOptions,
  type DragSpringController,
  type DragSpringOptions,
  confetti,
  dragSpring,
  rippleSurface,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useEvent, useIsoLayoutEffect } from '../internal/hooks';
import { type IconName, Icon } from '../internal/icon';
import { SmartLink } from '../internal/provider';
import { NumberTicker } from '../components/text';

/* ---- Controller hooks (the indicator pattern: create once, live forever) -------------- */

/** A confetti cannon mounted on `ref`'s element for as long as it is in the tree. */
function useConfetti(ref: RefObject<HTMLElement | null>, options: ConfettiOptions = {}): RefObject<ConfettiController | null> {
  const cannon = useRef<ConfettiController | null>(null);
  useIsoLayoutEffect(() => {
    const el = ref.current;
    if (!el) return;
    cannon.current = confetti(el, options);
    return () => {
      cannon.current?.destroy();
      cannon.current = null;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [ref]);
  return cannon;
}

/** A drag spring on `ref`'s element; callbacks stay live across renders. */
function useDragSpring(ref: RefObject<HTMLElement | null>, options: DragSpringOptions = {}): RefObject<DragSpringController | null> {
  const controller = useRef<DragSpringController | null>(null);
  const latest = useRef(options);
  useIsoLayoutEffect(() => {
    latest.current = options;
  });
  useIsoLayoutEffect(() => {
    const el = ref.current;
    if (!el) return;
    controller.current = dragSpring(el, {
      ...latest.current,
      target: () => latest.current.target?.() ?? null,
      onGrab: () => latest.current.onGrab?.(),
      onDrop: () => latest.current.onDrop?.(),
      onCancel: () => latest.current.onCancel?.(),
    });
    return () => {
      controller.current?.destroy();
      controller.current = null;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [ref]);
  return controller;
}

/** Origin fractions of `host` for a point just above an element's centre-top. */
function originAbove(host: HTMLElement, el: HTMLElement): { x: number; y: number } {
  const h = host.getBoundingClientRect();
  const r = el.getBoundingClientRect();
  return {
    x: (r.left + r.width / 2 - h.left) / Math.max(1, h.width),
    y: (r.top + r.height * 0.25 - h.top) / Math.max(1, h.height),
  };
}

/* ---- Confetti button ------------------------------------------------------------------- */

export interface ConfettiButtonProps extends Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'children'> {
  /** The idle label. */
  label: ReactNode;
  /** The label once the win has landed (defaults to the idle label). */
  doneLabel?: ReactNode;
  /** The idle icon (default "mail" — a thing you send). */
  icon?: IconName;
  /** The icon once done (default "check-circle"). */
  doneIcon?: IconName;
  /** Pieces per burst (default 120). */
  count?: number;
  /** Palette override; the theme's chart tokens by default. */
  colors?: string[];
  /** Whether the action has succeeded; controls itself when omitted. */
  done?: boolean;
  defaultDone?: boolean;
  onDoneChange?: (done: boolean) => void;
}

/**
 * A success button whose win the whole screen can hear: pressing it (or
 * flipping `done` on) throws a confetti burst from the button's top edge —
 * gravity, drag, spin, the theme's own palette — while the label and the icon
 * crossfade to the done state, so the colour says it even where the party is
 * parked. Pressing a done button resets it, ready for the next win. Under
 * reduced motion the flip still happens; the cannon stays quiet.
 */
export const ConfettiButton = forwardRef<HTMLButtonElement, ConfettiButtonProps>(function ConfettiButton(
  { label, doneLabel, icon = 'mail', doneIcon = 'check-circle', count, colors, done, defaultDone = false, onDoneChange, className, type = 'button', onClick, ...rest },
  ref,
) {
  const host = useRef<HTMLSpanElement>(null);
  const button = useRef<HTMLButtonElement>(null);
  const cannon = useConfetti(host, { count, colors });
  const [current, setCurrent] = useControllable(done, defaultDone, onDoneChange);

  // An outside flip to done fires too — success may arrive from the server.
  const firedFor = useRef(false);
  useEffect(() => {
    if (current && !firedFor.current) {
      firedFor.current = true;
      const h = host.current;
      const b = button.current;
      if (h && b) cannon.current?.fire({ ...originAbove(h, b), count, colors });
    }
    if (!current) firedFor.current = false;
  }, [current, count, colors, cannon]);

  const press = (event: React.MouseEvent<HTMLButtonElement>) => {
    onClick?.(event);
    const next = !current;
    setCurrent(next);
    if (!next) return;
    // Claim the flip here so the done-transition effect does not fire twice.
    firedFor.current = true;
    const h = host.current;
    const b = button.current;
    if (h && b) cannon.current?.fire({ ...originAbove(h, b), count, colors });
  };

  return (
    <span ref={host} className="nx-confetti-button-host">
      <button
        ref={mergeRefs(ref, button)}
        type={type}
        className={cx('nx-confetti-button', className)}
        data-state={current ? 'done' : 'idle'}
        onClick={press}
        {...rest}
      >
        <span className="nx-confetti-button-icons" aria-hidden="true">
          <Icon name={icon} />
          <Icon name={doneIcon} />
        </span>
        <span className="nx-confetti-button-label">{current ? (doneLabel ?? label) : label}</span>
      </button>
    </span>
  );
});

/* ---- Ripple surface ---------------------------------------------------------------------- */

type SurfaceTag = 'button' | 'div' | 'span';

export interface RippleSurfaceProps extends HTMLAttributes<HTMLElement> {
  /** What to render (default "button"; an `href` renders an anchor either way). */
  as?: SurfaceTag;
  /** filled (the accent pill), outline (the ghost pill) or plain (just the wave). */
  variant?: 'filled' | 'outline' | 'plain';
  /** Turns the surface into a link through the app's link component. */
  href?: string;
  type?: 'button' | 'submit' | 'reset';
  children?: ReactNode;
}

/**
 * Any surface — a button, a card, a tab row — that ripples from the exact
 * point of the press. The ink is currentColor, so a filled surface ripples
 * light and a ghost one in its own colour; Enter and Space drop the wave from
 * the centre. Children marked `data-nx-ripple` ripple too (delegation), so one
 * plain card can dress every button and tab inside it. Under reduced motion
 * nothing spawns — the surface's own state change is the cue.
 */
export const RippleSurface = forwardRef<HTMLElement, RippleSurfaceProps>(function RippleSurface(
  { as, variant, href, type = 'button', className, children, ...rest },
  ref,
) {
  const own = useRef<HTMLElement>(null);
  useBehavior(own, rippleSurface, {});
  const shape = variant ?? (as === 'div' || as === 'span' ? 'plain' : 'filled');
  const classes = cx('nx-ripple-surface', className);

  if (href) {
    return (
      <SmartLink ref={mergeRefs(ref, own) as never} href={href} className={classes} data-nx-ripple="" data-variant={shape} {...rest}>
        {children}
      </SmartLink>
    );
  }

  const Tag = (as ?? 'button') as 'button';
  return (
    <Tag ref={mergeRefs(ref, own) as never} type={as === 'div' || as === 'span' ? undefined : type} className={classes} data-nx-ripple="" data-variant={shape} {...rest}>
      {children}
    </Tag>
  );
});

/* ---- Drag spring --------------------------------------------------------------------------- */

export interface DragSpringProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  /** The draggable card's content (its label, usually). */
  children: ReactNode;
  /** An icon that rides along on the card (default "grid" — a thing you move). */
  chipIcon?: IconName;
  /** The tray's label — where the card lives. */
  homeLabel?: ReactNode;
  /** The drop target's label — where it goes. */
  targetLabel?: ReactNode;
  /** Quiet copy under the target that says what a drop means. */
  targetNote?: ReactNode;
  /** Whether the card is on the target; controls itself when omitted. */
  held?: boolean;
  defaultHeld?: boolean;
  onHeldChange?: (held: boolean) => void;
  /** How close the centres must be for the target to grip, px (default 72). */
  radius?: number;
  /** Status line while the card is home (aria-live, so it narrates too). */
  hint?: ReactNode;
  /** Status line once the card is held. */
  heldNote?: ReactNode;
}

/**
 * A card you drag with your wrist: one spring chases the pointer while you
 * hold (the card trails like a sticky bead), and on release it either wobbles
 * onto the drop target — which arms inside its capture radius, grips and
 * confirms with a check — or back into its tray. Enter and Space assign and
 * release with no pointer at all; a controlled `held` flies the same flight
 * (that is the reset button's path). Reduced motion teleports.
 */
export function DragSpring({
  children,
  chipIcon = 'grid',
  homeLabel,
  targetLabel,
  targetNote,
  held,
  defaultHeld = false,
  onHeldChange,
  radius,
  hint,
  heldNote,
  className,
  ...rest
}: DragSpringProps) {
  const chip = useRef<HTMLButtonElement>(null);
  const slot = useRef<HTMLDivElement>(null);
  const [current, setCurrent] = useControllable(held, defaultHeld, onHeldChange);
  const controller = useDragSpring(chip, {
    target: () => slot.current,
    radius,
    onDrop: () => setCurrent(true),
    onCancel: () => setCurrent(false),
  });

  // An outside flip (the reset button, the server) flies the same flight.
  const seen = useRef(defaultHeld);
  useEffect(() => {
    if (seen.current === current) return;
    seen.current = current;
    if (current) controller.current?.assign();
    else controller.current?.release();
  }, [current, controller]);

  return (
    <div className={cx('nx-drag-wrap', className)} {...rest}>
      <div className="nx-drag-board">
        <div className="nx-drag-pad nx-drag-home">
          {homeLabel !== undefined && <span className="nx-drag-label">{homeLabel}</span>}
          <button
            ref={chip}
            type="button"
            className="nx-drag-chip"
            aria-pressed={current}
          >
            {chipIcon && <Icon name={chipIcon} />}
            {children}
          </button>
        </div>
        <div ref={slot} className="nx-drag-pad nx-drag-slot">
          {targetLabel !== undefined && <span className="nx-drag-label">{targetLabel}</span>}
          {targetNote !== undefined && <span className="nx-drag-note">{targetNote}</span>}
          {current && (
            <span className="nx-drag-check">
              <Icon name="check" />
            </span>
          )}
        </div>
      </div>
      {(hint !== undefined || heldNote !== undefined) && (
        <p className="nx-drag-status" role="status">
          {current ? heldNote : hint}
        </p>
      )}
    </div>
  );
}

/* ---- Reaction bar ---------------------------------------------------------------------------- */

export interface ReactionBarItem {
  id: string;
  /** The reaction's name — read out, and shown as a tooltip when it is a string. */
  label: ReactNode;
  /** One of the built-in icons ("heart", "star", "sparkles", "zap"…). */
  icon?: IconName;
  count: number;
  /** The reaction's colour, any CSS colour (default the accent). */
  tint?: string;
}

export interface ReactionBarProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue'> {
  items: ReactionBarItem[];
  /** The picked reaction; `null` is none. Controls itself when omitted. */
  value?: string | null;
  defaultValue?: string | null;
  onValueChange?: (id: string | null) => void;
  /** Pieces per pick; 0 turns the burst off (default 14). */
  particles?: number;
}

/**
 * Reactions that land: one pick at a time, toggleable off, every pick throwing
 * a small burst of the reaction's own colour from the top of its button. The
 * counts roll on the shared odometer, so 128 visibly becomes 129; switching
 * slots moves the reaction without a second party. Under reduced motion only
 * the pressed state and the number change — which is the whole news anyway.
 */
export function ReactionBar({ items, value, defaultValue = null, onValueChange, particles = 14, className, ...rest }: ReactionBarProps) {
  const host = useRef<HTMLDivElement>(null);
  const cannon = useConfetti(host, {});
  const [current, setCurrent] = useControllable(value, defaultValue, onValueChange);
  const [counts, setCounts] = useState(() => Object.fromEntries(items.map((item) => [item.id, item.count])));

  // Fresh props win: the bar mirrors whatever the app says the counts are.
  const signature = items.map((item) => `${item.id}:${item.count}`).join('|');
  useEffect(() => {
    setCounts(Object.fromEntries(items.map((item) => [item.id, item.count])));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [signature]);

  const pick = (id: string, el: HTMLElement) => {
    const next = current === id ? null : id;
    setCounts((counts) => {
      const moved: Record<string, number> = { ...counts };
      if (current && moved[current] !== undefined) moved[current] = Math.max(0, moved[current] - 1);
      if (next && moved[next] !== undefined) moved[next] = moved[next] + 1;
      return moved;
    });
    setCurrent(next);
    if (next && particles > 0) {
      const item = items.find((entry) => entry.id === next);
      const tint = item?.tint ?? 'var(--nx-accent)';
      const h = host.current;
      if (h) cannon.current?.fire({ ...originAbove(h, el), count: particles, colors: [tint], speed: [180, 440] });
    }
  };

  return (
    <div ref={host} className={cx('nx-reaction-bar', className)} role="group" {...rest}>
      {items.map((item) => {
        const count = counts[item.id] ?? item.count;
        const pressed = current === item.id;
        return (
          <button
            key={item.id}
            type="button"
            className="nx-reaction-btn"
            style={{ '--_tint': item.tint ?? 'var(--nx-accent)' } as CSSProperties}
            aria-pressed={pressed}
            title={typeof item.label === 'string' ? item.label : undefined}
            onPointerDown={(event) => {
              if (event.button === 0) pick(item.id, event.currentTarget);
            }}
            onKeyDown={(event) => {
              if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                pick(item.id, event.currentTarget);
              }
            }}
          >
            <span className="nx-visually-hidden">{item.label}</span>
            {item.icon && <Icon name={item.icon} />}
            <NumberTicker className="nx-reaction-count" value={count} reveal={false} />
          </button>
        );
      })}
    </div>
  );
}

/* ---- Live ticker -------------------------------------------------------------------------------- */

export interface LiveTickerQuote {
  id: string;
  /** The symbol code, bold ("AUR"). */
  symbol: ReactNode;
  /** A muted name beside the code ("Aurum"). */
  name?: ReactNode;
  value: number;
  /** Digits after the decimal point (default 2). */
  precision?: number;
  /** Derive the next value; the default is a small random walk. */
  next?: (quote: LiveTickerQuote) => number;
}

export interface LiveTickerProps extends HTMLAttributes<HTMLDivElement> {
  quotes: LiveTickerQuote[];
  /** Milliseconds between steps (default 1600). */
  interval?: number;
  /** Half-width of the random walk as a share of the price (default 0.003). */
  step?: number;
  /** Hold the flow while the pointer rests on the strip (default true). */
  hold?: boolean;
  /** Called with every step, for apps that want to log or steer the flow. */
  onTick?: (quotes: LiveTickerQuote[]) => void;
}

/**
 * Numbers that move like numbers: a watchlist strip where every price rolls on
 * per-digit columns (the shared odometer, locale-aware digits included), a
 * coloured arrow and border carry the session sign, and the quotes are
 * bidi-isolated so they read correctly in RTL and LTR alike. The flow steps
 * only while the strip is on screen and the tab is visible, and rests while
 * the pointer is over it. Under reduced motion the digits still change — they
 * just snap instead of rolling.
 */
export function LiveTicker({ quotes, interval = 1600, step = 0.003, hold = true, onTick, className, ...rest }: LiveTickerProps) {
  const root = useRef<HTMLDivElement>(null);
  const [live, setLive] = useState(() => quotes.map((quote) => ({ ...quote, open: quote.value })));
  const [visible, setVisible] = useState(true);
  const [paused, setPaused] = useState(false);

  const signature = quotes.map((quote) => `${quote.id}:${quote.value}`).join('|');
  useEffect(() => {
    setLive(quotes.map((quote) => ({ ...quote, open: quote.value })));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [signature]);

  useEffect(() => {
    if (!('IntersectionObserver' in window)) return;
    const observer = new IntersectionObserver((entries) => setVisible(entries[0]?.isIntersecting ?? true), { rootMargin: '64px' });
    if (root.current) observer.observe(root.current);
    const onVisibility = () => setVisible(!document.hidden);
    document.addEventListener('visibilitychange', onVisibility);
    return () => {
      observer.disconnect();
      document.removeEventListener('visibilitychange', onVisibility);
    };
  }, []);

  const tick = useEvent(() => {
    setLive((rows) =>
      rows.map((row) => {
        const value = row.next ? row.next(row) : Math.max(0.01, row.value * (1 + (Math.random() - 0.5) * 2 * step));
        return { ...row, value };
      }),
    );
    onTick?.(live.map(({ open, ...quote }) => ({ ...quote })));
  });

  useEffect(() => {
    if (!visible || paused) return;
    const timer = window.setInterval(tick, interval);
    return () => window.clearInterval(timer);
  }, [visible, paused, interval, tick]);

  return (
    <div
      ref={root}
      className={cx('nx-live-ticker', className)}
      role="group"
      onPointerEnter={hold ? () => setPaused(true) : undefined}
      onPointerLeave={hold ? () => setPaused(false) : undefined}
      {...rest}
    >
      {live.map((row) => {
        const precision = row.precision ?? 2;
        const pct = (row.value / row.open - 1) * 100;
        const up = pct >= 0;
        return (
          <div key={row.id} className="nx-ticker-cell" data-dir={up ? 'up' : 'down'}>
            <span className="nx-ticker-sym">
              <b>{row.symbol}</b>
              {row.name !== undefined && <span>{row.name}</span>}
            </span>
            <span className="nx-ticker-quote">
              <NumberTicker
                className="nx-ticker-price"
                value={row.value}
                reveal={false}
                format={{ minimumFractionDigits: precision, maximumFractionDigits: precision }}
              />
              <span className="nx-ticker-chg">
                <Icon name={up ? 'trend-up' : 'trend-down'} />
                {up ? '+' : '−'}
                <NumberTicker value={Math.abs(pct)} reveal={false} format={{ minimumFractionDigits: 2, maximumFractionDigits: 2 }} />
                %
              </span>
            </span>
          </div>
        );
      })}
    </div>
  );
}
