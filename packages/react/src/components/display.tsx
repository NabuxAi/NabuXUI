import { type CSSProperties, type HTMLAttributes, type ReactNode, useId, useRef, useState } from 'react';
import { reveal, spotlight as spotlightBehavior } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useInert } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';
import { Tooltip } from './feedback';

/* ---- Avatar ------------------------------------------------------------------ */

export interface AvatarProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'children'> {
  name: string;
  src?: string;
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
  status?: 'online' | 'busy' | 'away' | 'offline';
  shape?: 'circle' | 'square';
}

const initials = (name: string) =>
  name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => Array.from(part)[0])
    .join('')
    .toLocaleUpperCase();

export function Avatar({ name, src, size, status, shape, className, ...rest }: AvatarProps) {
  const [failed, setFailed] = useState(false);
  return (
    <span
      className={cx('nx-avatar', className)}
      data-size={size === 'md' ? undefined : size}
      data-status={status}
      data-shape={shape === 'square' ? 'square' : undefined}
      role="img"
      aria-label={status ? `${name} (${status})` : name}
      {...rest}
    >
      {src && !failed ? <img className="nx-avatar-image" src={src} alt="" onError={() => setFailed(true)} /> : <span aria-hidden="true">{initials(name)}</span>}
    </span>
  );
}

export interface AvatarGroupProps {
  people: Array<{ name: string; src?: string }>;
  /** Show this many; the rest become "+N". */
  max?: number;
  size?: AvatarProps['size'];
  label?: string;
  className?: string;
}

export function AvatarGroup({ people, max = 5, size, label, className }: AvatarGroupProps) {
  const t = useT();
  const shown = people.slice(0, max);
  const rest = people.length - shown.length;
  return (
    <div className={cx('nx-avatar-group', className)} role="group" aria-label={label}>
      {shown.map((person) => (
        <Tooltip key={person.name} content={person.name}>
          <Avatar name={person.name} src={person.src} size={size} tabIndex={0} />
        </Tooltip>
      ))}
      {rest > 0 && (
        <span className="nx-avatar nx-avatar-more" data-size={size === 'md' ? undefined : size} role="img" aria-label={t('more', { count: rest })}>
          <span aria-hidden="true">+{rest}</span>
        </span>
      )}
    </div>
  );
}

/* ---- Kbd ----------------------------------------------------------------------- */

export function Kbd({ className, ...rest }: HTMLAttributes<HTMLElement>) {
  return <kbd className={cx('nx-kbd', className)} {...rest} />;
}

/* ---- Rating: native radios, painted and previewed by CSS ------------------------ */

export interface RatingProps {
  name?: string;
  value?: number;
  defaultValue?: number;
  onValueChange?: (value: number) => void;
  max?: number;
  label?: string;
  disabled?: boolean;
  size?: string;
  className?: string;
}

export function Rating({ name, value, defaultValue = 0, onValueChange, max = 5, label, disabled, size, className }: RatingProps) {
  const t = useT();
  const generated = useId();
  const [current, setCurrent] = useControllable(value, defaultValue, onValueChange);
  const group = name ?? `nx-rating${generated.replace(/:/g, '')}`;

  return (
    <fieldset className={cx('nx-rating', className)} disabled={disabled} style={size ? ({ '--nx-rating-size': size } as CSSProperties) : undefined}>
      <legend className="nx-visually-hidden">{label ?? t('rating')}</legend>
      {Array.from({ length: max }, (_, i) => i + 1).map((star) => (
        <label key={star} className="nx-rating-star">
          <input
            className="nx-rating-input"
            type="radio"
            name={group}
            value={star}
            checked={current === star}
            onChange={() => setCurrent(star)}
            aria-label={t('stars', { count: star, total: max })}
          />
          <Icon name="star" />
        </label>
      ))}
    </fieldset>
  );
}

/* ---- Steps ---------------------------------------------------------------------- */

export interface StepsProps {
  steps: Array<{ title: ReactNode; description?: ReactNode }>;
  /** Index of the step in progress; earlier ones are complete. */
  current: number;
  orientation?: 'horizontal' | 'vertical';
  className?: string;
  'aria-label'?: string;
}

export function Steps({ steps, current, orientation = 'horizontal', className, 'aria-label': label }: StepsProps) {
  return (
    <ol className={cx('nx-steps', className)} data-orientation={orientation === 'vertical' ? 'vertical' : undefined} aria-label={label}>
      {steps.map((step, i) => {
        const status = i < current ? 'complete' : i === current ? 'current' : 'upcoming';
        return (
          <li key={i} className="nx-step" data-status={status} aria-current={status === 'current' ? 'step' : undefined}>
            <span className="nx-step-marker">{status === 'complete' ? <Icon name="check" /> : null}</span>
            <span className="nx-step-text">
              <span className="nx-step-title">{step.title}</span>
              {step.description && <span className="nx-step-description">{step.description}</span>}
            </span>
          </li>
        );
      })}
    </ol>
  );
}

/* ---- Marquee -------------------------------------------------------------------- */

export interface MarqueeProps {
  items: ReactNode[];
  /** Seconds for one full loop. */
  duration?: number;
  reverse?: boolean;
  gap?: string;
  /** A pause button (on by default: moving content must be stoppable). */
  controls?: boolean;
  'aria-label'?: string;
  className?: string;
}

export function Marquee({ items, duration = 40, reverse, gap, controls = true, 'aria-label': label, className }: MarqueeProps) {
  const t = useT();
  const [paused, setPaused] = useState(false);
  const copy = useRef<HTMLUListElement>(null);
  // The second copy only exists for the seamless loop: nobody should tab into it.
  useInert(copy, true);
  const group = (hidden: boolean) => (
    <ul ref={hidden ? copy : undefined} className="nx-marquee-group" aria-hidden={hidden || undefined}>
      {items.map((item, i) => (
        <li key={i}>{item}</li>
      ))}
    </ul>
  );

  return (
    <section
      className={cx('nx-marquee', className)}
      aria-label={label}
      data-direction={reverse ? 'reverse' : undefined}
      data-paused={paused ? '' : undefined}
      style={{ '--nx-duration': `${duration}s`, ...(gap ? { '--nx-marquee-gap': gap } : null) } as CSSProperties}
    >
      <div className="nx-marquee-track">
        {group(false)}
        {group(true)}
      </div>
      {controls && (
        <button type="button" className="nx-marquee-toggle" aria-pressed={paused} aria-label={paused ? t('play') : t('pause')} onClick={() => setPaused(!paused)}>
          <Icon name={paused ? 'play' : 'pause'} />
        </button>
      )}
    </section>
  );
}

/* ---- Grid & bento ----------------------------------------------------------------- */

export interface GridProps extends HTMLAttributes<HTMLDivElement> {
  /** Narrowest a column may get before the grid wraps. */
  min?: string;
  gap?: string;
  /** Children rise in one after another when the grid scrolls into view. */
  stagger?: boolean;
}

export function Grid({ min, gap, stagger = true, className, style, children, ...rest }: GridProps) {
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, reveal, { stagger: true, once: true }, stagger);
  return (
    <div
      ref={ref}
      className={cx('nx-grid', className)}
      data-nx-reveal={stagger ? 'group' : undefined}
      style={{ ...(min ? { '--nx-grid-min': min } : null), ...(gap ? { '--nx-grid-gap': gap } : null), ...style } as CSSProperties}
      {...rest}
    >
      {children}
    </div>
  );
}

export interface BentoProps extends HTMLAttributes<HTMLDivElement> {
  columns?: number;
  /** Minimum row height. */
  rowHeight?: string;
}

export function Bento({ columns = 3, rowHeight, className, style, children, ...rest }: BentoProps) {
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, reveal, { stagger: true, once: true });
  return (
    <div
      ref={ref}
      className={cx('nx-bento', className)}
      data-nx-reveal="group"
      style={{ '--nx-cols': columns, ...(rowHeight ? { '--nx-row': rowHeight } : null), ...style } as CSSProperties}
      {...rest}
    >
      {children}
    </div>
  );
}

export interface BentoItemProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  colSpan?: number;
  rowSpan?: number;
  title?: ReactNode;
  description?: ReactNode;
  /** Artwork at the top of the tile (image, chart, illustration). */
  media?: ReactNode;
  href?: string;
  spotlight?: boolean;
}

export function BentoItem({ colSpan = 1, rowSpan = 1, title, description, media, href, spotlight = true, className, style, children, ...rest }: BentoItemProps) {
  const ref = useRef<HTMLElement>(null);
  useBehavior(ref, spotlightBehavior, undefined as never, spotlight);
  const shared = {
    ref: ref as never,
    className: cx('nx-bento-item', spotlight && 'nx-spotlight', className),
    style: { '--nx-col-span': colSpan, '--nx-row-span': rowSpan, ...style } as CSSProperties,
  };
  const content = (
    <>
      {media && <div className="nx-bento-media">{media}</div>}
      {title && <h3 className="nx-bento-title">{title}</h3>}
      {description && <p className="nx-bento-description">{description}</p>}
      {children}
    </>
  );

  return href ? (
    <SmartLink href={href} {...shared} {...(rest as HTMLAttributes<HTMLAnchorElement>)}>
      {content}
    </SmartLink>
  ) : (
    <article {...shared} {...rest}>
      {content}
    </article>
  );
}

export function Divider({ children, className }: { children?: ReactNode; className?: string }) {
  return (
    <div className={cx('nx-divider', className)} role="separator">
      {children}
    </div>
  );
}
