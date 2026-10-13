/**
 * Call-to-action blocks (React): the gradient banner with its passing light
 * sweep, the developer terminal that types the install command, and the scenic
 * dusk banner with pointer parallax. Thin over the core CSS — the typing loop
 * and the parallax are local state (timers and two custom properties), so this
 * family ships no core JS behaviour of its own.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type MouseEventHandler,
  type ReactNode,
  forwardRef,
  useEffect,
  useId,
  useRef,
  useState,
} from 'react';
import { type IconName, copyText } from '@nabuxai/ui-core';
import { cx, mergeRefs } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';

const clamp1 = (value: number) => Math.min(1, Math.max(-1, value));
const reducedMotion = () => typeof window !== 'undefined' && !!window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

/* ---- Gradient banner --------------------------------------------------------- */

export interface CtaGradientProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  title: ReactNode;
  subtitle?: ReactNode;
  /** The small pill above the title — a promise, like “No credit card · 14-day trial”. */
  badge?: ReactNode;
  /** The icon that leads the badge. */
  badgeIcon?: IconName | null;
  primaryLabel?: ReactNode;
  primaryHref?: string;
  onPrimaryClick?: MouseEventHandler;
  /** The icon that trails the primary label (the reading-direction arrow). */
  primaryIcon?: IconName | null;
  secondaryLabel?: ReactNode;
  secondaryHref?: string;
  onSecondaryClick?: MouseEventHandler;
  /** Fold the banner into one line, for the end of an article. */
  size?: 'default' | 'slim';
}

/** A gradient banner with a passing light sweep, fine grain and two depth-lifting buttons. */
export const CtaGradient = forwardRef<HTMLElement, CtaGradientProps>(function CtaGradient(
  {
    title,
    subtitle,
    badge,
    badgeIcon = 'shield',
    primaryLabel,
    primaryHref,
    onPrimaryClick,
    primaryIcon = 'arrow-right',
    secondaryLabel,
    secondaryHref,
    onSecondaryClick,
    size,
    className,
    ...rest
  },
  ref,
) {
  const slim = size === 'slim';
  const button = (label: ReactNode, href: string | undefined, click: MouseEventHandler | undefined, icon: IconName | null, ghost: boolean) =>
    href ? (
      <SmartLink className="nx-cta-gradient-button" data-variant={ghost ? 'ghost' : undefined} href={href} onClick={click}>
        {label}
        {icon && <Icon name={icon} />}
      </SmartLink>
    ) : (
      <button type="button" className="nx-cta-gradient-button" data-variant={ghost ? 'ghost' : undefined} onClick={click}>
        {label}
        {icon && <Icon name={icon} />}
      </button>
    );

  return (
    <section ref={ref} className={cx('nx-cta-gradient', className)} data-size={slim ? 'slim' : undefined} {...rest}>
      <span className="nx-cta-gradient-sweep" aria-hidden="true" />
      <div className="nx-cta-gradient-body">
        {badge != null && badge !== false && (
          <span className="nx-cta-gradient-chip">
            {badgeIcon && <Icon name={badgeIcon} />}
            {badge}
          </span>
        )}
        {slim ? (
          <div className="nx-cta-gradient-slim-text">
            <h3 className="nx-cta-gradient-title">{title}</h3>
            {subtitle && <p className="nx-cta-gradient-subtitle">{subtitle}</p>}
          </div>
        ) : (
          <>
            <h3 className="nx-cta-gradient-title">{title}</h3>
            {subtitle && <p className="nx-cta-gradient-subtitle">{subtitle}</p>}
          </>
        )}
        {(primaryLabel || secondaryLabel) && (
          <div className="nx-cta-gradient-actions">
            {primaryLabel && button(primaryLabel, primaryHref, onPrimaryClick, primaryIcon, false)}
            {secondaryLabel && button(secondaryLabel, secondaryHref, onSecondaryClick, null, true)}
          </div>
        )}
      </div>
    </section>
  );
});

/* ---- Terminal ----------------------------------------------------------------- */

/** A leading “✓ ” or “› ” carries the tone; the rest of the line stays muted. */
function TerminalLine({ line }: { line: string }) {
  if (line.startsWith('✓')) {
    return (
      <>
        <b aria-hidden="true">✓</b>
        {line.slice(1)}
      </>
    );
  }
  if (line.startsWith('›')) {
    return (
      <>
        <i aria-hidden="true">›</i>
        {line.slice(1)}
      </>
    );
  }
  return <>{line}</>;
}

export interface CtaTerminalProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  title: ReactNode;
  subtitle?: ReactNode;
  badge?: ReactNode;
  badgeIcon?: IconName | null;
  /** The command the terminal types — and what the copy button copies. */
  command?: string;
  /** Lines that appear once the command lands; a leading “✓ ” or “› ” is coloured. */
  output?: string[];
  /** The window's tab label. */
  terminalLabel?: string;
  /** Anything under the pitch: a star button, a docs link. */
  actions?: ReactNode;
  /** Type the command (default) or land it whole. */
  typing?: boolean;
  /** Clear the screen and type again, forever (default). */
  replay?: boolean;
  /** ms per typed character. */
  typeSpeed?: number;
  /** ms the finished command and its output stay before a replay. */
  hold?: number;
}

/** A developer CTA: the pitch on one side, a terminal that types the install command on the other. */
export const CtaTerminal = forwardRef<HTMLElement, CtaTerminalProps>(function CtaTerminal(
  {
    title,
    subtitle,
    badge,
    badgeIcon = 'command',
    command = 'npm i @nabuxai/ui-react',
    output = [],
    terminalLabel = 'terminal — zsh',
    actions,
    typing = true,
    replay = true,
    typeSpeed = 55,
    hold = 6000,
    className,
    ...rest
  },
  ref,
) {
  const t = useT();
  const [typed, setTyped] = useState('');
  const [done, setDone] = useState(false);
  const [copied, setCopied] = useState(false);

  // The typing loop: one timer chain, all of it cleared on unmount or re-run.
  useEffect(() => {
    if (!typing || reducedMotion() || command.length === 0) {
      setTyped(command);
      setDone(true);
      return;
    }
    let cancelled = false;
    const timers: number[] = [];
    const schedule = (fn: () => void, ms: number) => {
      timers.push(
        window.setTimeout(() => {
          if (!cancelled) fn();
        }, ms),
      );
    };
    const play = () => {
      setTyped('');
      setDone(false);
      let i = 0;
      const step = () => {
        i += 1;
        setTyped(command.slice(0, i));
        if (i >= command.length) {
          // The output settles shortly after the command lands.
          schedule(() => {
            setDone(true);
            if (replay) schedule(play, hold);
          }, 650);
        } else {
          schedule(step, typeSpeed);
        }
      };
      schedule(step, 350);
    };
    play();
    return () => {
      cancelled = true;
      for (const id of timers) window.clearTimeout(id);
    };
  }, [command, typing, replay, typeSpeed, hold]);

  useEffect(() => {
    if (!copied) return;
    const id = window.setTimeout(() => setCopied(false), 1800);
    return () => window.clearTimeout(id);
  }, [copied]);

  return (
    <section ref={ref} className={cx('nx-cta-terminal', className)} {...rest}>
      <div className="nx-cta-terminal-pitch">
        {badge != null && badge !== false && (
          <span className="nx-cta-terminal-badge">
            {badgeIcon && <Icon name={badgeIcon} />}
            {badge}
          </span>
        )}
        <h3 className="nx-cta-terminal-title">{title}</h3>
        {subtitle && <p className="nx-cta-terminal-subtitle">{subtitle}</p>}
        {actions && <div className="nx-cta-terminal-actions">{actions}</div>}
      </div>

      {/* The command is Latin; the window keeps its own direction whatever the page speaks. */}
      <div className="nx-cta-terminal-window" dir="ltr" lang="en">
        <div className="nx-cta-terminal-bar">
          <span className="nx-cta-terminal-dot" data-r="" aria-hidden="true" />
          <span className="nx-cta-terminal-dot" data-y="" aria-hidden="true" />
          <span className="nx-cta-terminal-dot" data-g="" aria-hidden="true" />
          <span className="nx-cta-terminal-tab">{terminalLabel}</span>
          <button
            type="button"
            className="nx-cta-terminal-copy"
            data-copied={copied ? '' : undefined}
            onClick={() => void copyText(command).then((ok) => ok && setCopied(true))}
          >
            <span className="nx-cta-terminal-copy-icons" aria-hidden="true">
              <span>
                <Icon name="copy" />
              </span>
              <span data-off="">
                <Icon name="check" />
              </span>
            </span>
            <span className="nx-cta-terminal-copy-text">
              <span>{t('copy')}</span>
              <span data-off="" aria-hidden="true">
                {t('copied')}
              </span>
            </span>
          </button>
        </div>
        <div className="nx-cta-terminal-screen">
          <p>
            {/* Screen readers get the whole command at once; only the visuals type. */}
            <span className="nx-visually-hidden">{command}</span>
            <span aria-hidden="true">
              <span className="nx-cta-terminal-prompt">$</span>
              <span className="nx-cta-terminal-command">{typed}</span>
              <span className="nx-cta-terminal-caret" />
            </span>
          </p>
          {output.map((line, i) => (
            <p key={`${line}-${i}`} className="nx-cta-terminal-output" data-on={done ? '' : undefined} style={{ '--nx-i': i } as CSSProperties}>
              <TerminalLine line={line} />
            </p>
          ))}
        </div>
      </div>
    </section>
  );
});

/* ---- Scenic image banner -------------------------------------------------------- */

export interface CtaImageStat {
  value: ReactNode;
  label: ReactNode;
}

export interface CtaImageProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  title: ReactNode;
  subtitle?: ReactNode;
  stats?: CtaImageStat[];
  actionLabel?: ReactNode;
  actionHref?: string;
  onActionClick?: MouseEventHandler;
  /** The icon that trails the action label (the reading-direction arrow). */
  actionIcon?: IconName | null;
  /** Let the scene's layers drift against the pointer (default; off under reduced motion). */
  parallax?: boolean;
}

/** A dusk scene under a veil: the layers carry their own parallax, three stats sit under the headline. */
export const CtaImage = forwardRef<HTMLElement, CtaImageProps>(function CtaImage(
  { title, subtitle, stats = [], actionLabel, actionHref, onActionClick, actionIcon = 'arrow-right', parallax = true, className, ...rest },
  ref,
) {
  const own = useRef<HTMLElement>(null);
  const skyId = `nx-cta-image-sky-${useId().replace(/[^a-zA-Z0-9-]/g, '')}`;

  // Pointer parallax: -1…1 fractions of the banner's box, written as custom
  // properties on the root; the layers below inherit them and the CSS gives
  // each one its own factor.
  useEffect(() => {
    const el = own.current;
    if (!el || !parallax || reducedMotion()) return;
    const move = (event: PointerEvent) => {
      const rect = el.getBoundingClientRect();
      if (rect.width === 0 || rect.height === 0) return;
      const x = clamp1(((event.clientX - rect.left) / rect.width) * 2 - 1);
      const y = clamp1(((event.clientY - rect.top) / rect.height) * 2 - 1);
      el.style.setProperty('--nx-tx', x.toFixed(3));
      el.style.setProperty('--nx-ty', y.toFixed(3));
    };
    const leave = () => {
      el.style.setProperty('--nx-tx', '0');
      el.style.setProperty('--nx-ty', '0');
    };
    el.addEventListener('pointermove', move);
    el.addEventListener('pointerleave', leave);
    return () => {
      el.removeEventListener('pointermove', move);
      el.removeEventListener('pointerleave', leave);
    };
  }, [parallax]);

  const action =
    actionLabel == null || actionLabel === false ? null : actionHref ? (
      <SmartLink className="nx-cta-image-button" href={actionHref} onClick={onActionClick}>
        {actionLabel}
        {actionIcon && <Icon name={actionIcon} />}
      </SmartLink>
    ) : (
      <button type="button" className="nx-cta-image-button" onClick={onActionClick}>
        {actionLabel}
        {actionIcon && <Icon name={actionIcon} />}
      </button>
    );

  return (
    <section ref={mergeRefs(ref, own)} className={cx('nx-cta-image', className)} {...rest}>
      <svg className="nx-cta-image-art" viewBox="0 0 960 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
        <defs>
          <linearGradient id={skyId} x1="0" y1="0" x2="0" y2="1">
            <stop className="nx-cta-image-sky-top" offset="0" />
            <stop className="nx-cta-image-sky-mid" offset="0.55" />
            <stop className="nx-cta-image-sky-horizon" offset="1" />
          </linearGradient>
        </defs>
        <rect width="960" height="400" fill={`url(#${skyId})`} />
        <g className="nx-cta-image-stars">
          <circle cx="112" cy="58" r="2" opacity="0.8" />
          <circle cx="318" cy="112" r="1.5" opacity="0.6" />
          <circle cx="508" cy="52" r="1.2" opacity="0.5" />
          <circle cx="702" cy="132" r="1.5" opacity="0.5" />
          <circle cx="836" cy="48" r="2" opacity="0.7" />
          <circle cx="898" cy="150" r="1.2" opacity="0.45" />
        </g>
        <g className="nx-cta-image-sun">
          <circle className="nx-cta-image-sun-halo" cx="600" cy="192" r="98" />
          <circle className="nx-cta-image-sun-core" cx="600" cy="192" r="58" />
        </g>
        <path className="nx-cta-image-hills-far" d="M0 300 C180 240 340 276 520 264 S820 292 960 272 V400 H0 Z" />
        <g className="nx-cta-image-hills-near">
          <path className="nx-cta-image-hills-near-ridge" d="M0 348 C220 300 400 336 580 326 S840 344 960 332 V400 H0 Z" />
          <g className="nx-cta-image-grid" fill="none" strokeWidth="1.5">
            <path d="M480 268 L-80 400" />
            <path d="M480 268 L160 400" />
            <path d="M480 268 L400 400" />
            <path d="M480 268 L560 400" />
            <path d="M480 268 L800 400" />
            <path d="M480 268 L1040 400" />
            <path d="M0 356 H960" />
            <path d="M0 378 H960" />
            <path d="M0 396 H960" />
          </g>
        </g>
      </svg>
      <span className="nx-cta-image-veil" aria-hidden="true" />
      <div className="nx-cta-image-body">
        <h3 className="nx-cta-image-title">{title}</h3>
        {subtitle && <p className="nx-cta-image-subtitle">{subtitle}</p>}
        {stats.length > 0 && (
          <div className="nx-cta-image-stats">
            {stats.map((stat, i) => (
              <div key={`${i}-${String(stat.value)}`} className="nx-cta-image-stat">
                <b className="nx-cta-image-stat-value">{stat.value}</b>
                <small className="nx-cta-image-stat-label">{stat.label}</small>
              </div>
            ))}
          </div>
        )}
        {action}
      </div>
    </section>
  );
});
