import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useCallback,
  useEffect,
  useRef,
  useState,
} from 'react';
import { type IconName, leave, spotlight as spotlightBehavior, swipe, tilt as tiltBehavior } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useInert } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { Button } from './button';
import { Sparkline } from './chart';
import { NumberTicker } from './text';

export interface CardProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  variant?: 'default' | 'glass' | 'outline' | 'gradient' | 'inverse';
  size?: 'sm' | 'md' | 'lg';
  /** Lifts on hover (links and buttons always do). */
  interactive?: boolean;
  /** A light and a lit edge that follow the pointer. */
  spotlight?: boolean;
  /** Tilts in 3D toward the pointer. */
  tilt?: boolean;
  href?: string;
  icon?: IconName | ReactNode;
  title?: ReactNode;
  description?: ReactNode;
  footer?: ReactNode;
  /** Heading level of the title (h3 by default). */
  titleAs?: 'h2' | 'h3' | 'h4';
}

export const Card = forwardRef<HTMLElement, CardProps>(function Card(
  { variant, size, interactive, spotlight, tilt, href, icon, title, description, footer, titleAs: Title = 'h3', className, children, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLElement>(null);
  useBehavior(ref, spotlightBehavior, undefined as never, !!spotlight);
  useBehavior(ref, tiltBehavior, {}, !!tilt);

  const content = (
    <>
      {(icon || title || description) && (
        <div className="nx-card-header">
          {icon && <span className="nx-card-icon">{typeof icon === 'string' ? <Icon name={icon as IconName} /> : icon}</span>}
          {title && <Title className="nx-card-title">{title}</Title>}
          {description && <p className="nx-card-description">{description}</p>}
        </div>
      )}
      {children !== undefined && <div className="nx-card-body">{children}</div>}
      {footer && <div className="nx-card-footer">{footer}</div>}
    </>
  );

  const shared = {
    ref: mergeRefs(ref, forwarded),
    className: cx('nx-card', className),
    'data-variant': variant && variant !== 'default' ? variant : undefined,
    'data-size': size && size !== 'md' ? size : undefined,
    'data-interactive': interactive ? '' : undefined,
    'data-spotlight': spotlight ? '' : undefined,
  };

  if (href) {
    return (
      <SmartLink href={href} {...shared} {...(rest as HTMLAttributes<HTMLAnchorElement>)}>
        {content}
      </SmartLink>
    );
  }

  return (
    <article {...shared} {...rest}>
      {content}
    </article>
  );
});

/* ---- Flip ---------------------------------------------------------------------- */

export interface FlipCardProps {
  front: ReactNode;
  back: ReactNode;
  /** Turn on hover (and keyboard focus), or on click / the corner button. */
  trigger?: 'hover' | 'click';
  flipped?: boolean;
  defaultFlipped?: boolean;
  onFlippedChange?: (flipped: boolean) => void;
  /** Accessible name of the flip button. */
  flipLabel?: string;
  className?: string;
  style?: CSSProperties;
}

export function FlipCard({ front, back, trigger = 'hover', flipped, defaultFlipped = false, onFlippedChange, flipLabel = 'Flip card', className, style }: FlipCardProps) {
  const [on, setOn] = useControllable(flipped, defaultFlipped, onFlippedChange);
  const click = trigger === 'click';
  const frontRef = useRef<HTMLDivElement>(null);
  const backRef = useRef<HTMLDivElement>(null);
  // The face turned away cannot be reached by keyboard or read out.
  useInert(frontRef, click && on);
  useInert(backRef, click && !on);

  return (
    <div
      className={cx('nx-flip', className)}
      data-trigger={trigger}
      data-flipped={click && on ? '' : undefined}
      style={style}
      onClick={click ? (event) => !(event.target as Element).closest('a, button, input, select, textarea') && setOn(!on) : undefined}
    >
      <div className="nx-flip-inner">
        <div ref={frontRef} className="nx-flip-face nx-flip-front">
          {front}
        </div>
        <div ref={backRef} className="nx-flip-face nx-flip-back">
          {back}
        </div>
      </div>
      {click && (
        <Button
          variant="secondary"
          size="sm"
          shape="pill"
          iconOnly
          icon="sliders"
          aria-pressed={on}
          aria-label={flipLabel}
          style={{ position: 'absolute', insetBlockStart: 'var(--nx-space-3)', insetInlineEnd: 'var(--nx-space-3)', zIndex: 2 }}
          onClick={() => setOn(!on)}
        />
      )}
    </div>
  );
}

/* ---- Stack: overlapping cards that fan out on hover ------------------------------ */

export interface StackCardsProps {
  items: ReactNode[];
  /** How far each card overlaps the previous one (a negative margin, e.g. "-55%"). */
  overlap?: string;
  className?: string;
  'aria-label'?: string;
}

export function StackCards({ items, overlap, className, 'aria-label': label }: StackCardsProps) {
  return (
    <ul className={cx('nx-stack', className)} aria-label={label} style={{ '--nx-count': items.length, ...(overlap ? { '--nx-stack-overlap': overlap } : null) } as CSSProperties}>
      {items.map((item, i) => (
        <li key={i} className="nx-stack-item" style={{ '--i': i } as CSSProperties}>
          {item}
        </li>
      ))}
    </ul>
  );
}

/* ---- Swipe stack ----------------------------------------------------------------- */

export type SwipeVerdict = 'accept' | 'reject';

export interface SwipeStackProps<T> {
  items: T[];
  renderItem: (item: T) => ReactNode;
  getKey?: (item: T, index: number) => string | number;
  onSwipe?: (item: T, verdict: SwipeVerdict) => void;
  /** Swiped cards go to the back of the deck instead of leaving. */
  loop?: boolean;
  /** Show accept / reject buttons (the keyboard path). */
  actions?: boolean;
  acceptLabel?: string;
  rejectLabel?: string;
  className?: string;
}

export function SwipeStack<T>({ items, renderItem, getKey = (_, i) => i, onSwipe, loop = true, actions = true, acceptLabel, rejectLabel, className }: SwipeStackProps<T>) {
  const t = useT();
  const [order, setOrder] = useState(() => items.map((_, i) => i));
  const [leaving, setLeaving] = useState<number | null>(null);
  const topRef = useRef<HTMLElement | null>(null);

  useEffect(() => setOrder(items.map((_, i) => i)), [items]);

  const decide = useCallback(
    (verdict: SwipeVerdict) => {
      const top = order[0];
      if (top === undefined || leaving !== null || !topRef.current) return;
      const el = topRef.current;
      el.style.setProperty('--nx-exit-x', verdict === 'accept' ? '1' : '-1');
      setLeaving(top);
      onSwipe?.(items[top]!, verdict);
      leave(el, { value: 'leaving', timeout: 700 }).then(() => {
        el.style.removeProperty('--nx-dx');
        el.style.removeProperty('--nx-dy');
        el.style.removeProperty('--nx-drag-rotate');
        el.style.removeProperty('--nx-drag-progress');
        setLeaving(null);
        setOrder((current) => (loop ? [...current.slice(1), current[0]!] : current.slice(1)));
      });
    },
    [order, leaving, items, loop, onSwipe],
  );

  useEffect(() => {
    const el = topRef.current;
    if (!el) return;
    return swipe(el, { axis: 'x', threshold: 110, rotate: 9, onSwipe: (direction) => decide(direction === 'right' ? 'accept' : 'reject') });
  }, [order, decide]);

  return (
    <div className={className}>
      <div className="nx-swipe-stack">
        {order.slice(0, 4).map((itemIndex, depth) => {
          const item = items[itemIndex]!;
          const isTop = depth === 0;
          return (
            <article
              key={getKey(item, itemIndex)}
              ref={isTop ? (el) => void (topRef.current = el) : undefined}
              className="nx-swipe-card"
              data-state={leaving === itemIndex ? 'leaving' : isTop ? 'top' : undefined}
              aria-hidden={isTop ? undefined : true}
              style={{ '--i': depth } as CSSProperties}
            >
              {renderItem(item)}
              {isTop && (
                <>
                  <span className="nx-swipe-hint" data-side="accept" aria-hidden="true">
                    {acceptLabel ?? t('accept')}
                  </span>
                  <span className="nx-swipe-hint" data-side="reject" aria-hidden="true">
                    {rejectLabel ?? t('reject')}
                  </span>
                </>
              )}
            </article>
          );
        })}
      </div>
      {actions && order.length > 0 && (
        <div className="nx-swipe-actions">
          <Button variant="secondary" shape="pill" size="lg" iconOnly icon="x" aria-label={rejectLabel ?? t('reject')} onClick={() => decide('reject')} />
          <Button variant="primary" shape="pill" size="lg" iconOnly icon="heart" aria-label={acceptLabel ?? t('accept')} onClick={() => decide('accept')} />
        </div>
      )}
    </div>
  );
}

/* ---- Stat ------------------------------------------------------------------------- */

export interface StatCardProps {
  label: ReactNode;
  value: number;
  /** Intl number format for the value (currency, compact notation…). */
  format?: Intl.NumberFormatOptions;
  /** Change in percent against the previous period. */
  delta?: number;
  /** When a rise is bad news (costs, churn), colour it as such. */
  invertDelta?: boolean;
  /** Recent values for the sparkline. */
  trend?: number[];
  caption?: ReactNode;
  locale?: string;
  className?: string;
}

export function StatCard({ label, value, format, delta, invertDelta, trend, caption, locale, className }: StatCardProps) {
  const language = useLocale();
  const intl = locale ?? ({ en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const)[language];
  const good = delta === undefined ? undefined : invertDelta ? delta <= 0 : delta >= 0;

  return (
    <article className={cx('nx-stat', className)}>
      <div className="nx-stat-head">
        <span className="nx-stat-label">{label}</span>
        {delta !== undefined && (
          <span className="nx-delta" data-trend={good ? 'up' : 'down'}>
            <Icon name={delta >= 0 ? 'trend-up' : 'trend-down'} />
            {new Intl.NumberFormat(intl, { style: 'percent', maximumFractionDigits: 1, signDisplay: 'exceptZero' }).format(delta / 100)}
          </span>
        )}
      </div>
      <div className="nx-stat-value">
        <NumberTicker value={value} format={format} locale={intl} />
      </div>
      {trend && trend.length > 1 && <Sparkline data={trend} trend={good === undefined ? undefined : good ? 'up' : 'down'} />}
      {caption && <p className="nx-stat-caption">{caption}</p>}
    </article>
  );
}
