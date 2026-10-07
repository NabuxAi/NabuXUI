import {
  Children,
  type CSSProperties,
  type HTMLAttributes,
  type ReactElement,
  type ReactNode,
  type Ref,
  cloneElement,
  isValidElement,
  useEffect,
  useId,
  useRef,
  useState,
  useSyncExternalStore,
} from 'react';
import {
  type IconName,
  type Toast,
  type ToastStore,
  type ToastTone,
  leave,
  place,
  stackToasts,
  swipe,
  toasts as defaultStore,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';

export { toast } from '@nabuxai/ui-core';

const TONE_ICON: Record<ToastTone, IconName> = {
  neutral: 'bell',
  accent: 'sparkles',
  success: 'check-circle',
  warning: 'alert-triangle',
  danger: 'alert-circle',
  info: 'info',
};

/* ---- Toaster ------------------------------------------------------------------- */

export type ToasterPosition = 'bottom-end' | 'bottom-start' | 'bottom-center' | 'top-end' | 'top-start' | 'top-center';

export interface ToasterProps {
  position?: ToasterPosition;
  /** Another store, if the page runs more than one (rare). */
  store?: ToastStore;
  /** `ripple`: a ring spreads from each toast as it lands. */
  effect?: 'ripple';
  className?: string;
}

/** Render once near the root; then call `toast(...)` from anywhere. */
export function Toaster({ position = 'bottom-end', store = defaultStore, effect, className }: ToasterProps) {
  const t = useT();
  const items = useSyncExternalStore(store.subscribe, store.getSnapshot, store.getSnapshot);
  const section = useRef<HTMLElement>(null);
  const list = useRef<HTMLOListElement>(null);

  // In the top layer, so toasts stay visible above open dialogs.
  useEffect(() => {
    const el = section.current;
    try {
      if (typeof el?.showPopover === 'function') el.showPopover();
    } catch {
      /* already open */
    }
  }, []);

  // A dialog opened after the toaster would cover it: step back on top when a toast arrives.
  useEffect(() => {
    const el = section.current;
    if (typeof el?.showPopover !== 'function' || !document.querySelector('dialog:modal')) return;
    try {
      el.hidePopover();
      el.showPopover();
    } catch {
      /* not supported */
    }
  }, [items.length]);

  useIsoLayoutEffect(() => {
    if (list.current) stackToasts(list.current);
  });

  useEffect(() => {
    if (!list.current || typeof ResizeObserver === 'undefined') return;
    const observer = new ResizeObserver(() => list.current && stackToasts(list.current));
    for (const child of Array.from(list.current.children)) observer.observe(child);
    return () => observer.disconnect();
  }, [items]);

  return (
    <section
      ref={section}
      className={cx('nx-toaster', className)}
      data-position={position}
      data-effect={effect}
      aria-label={t('notifications')}
      {...{ popover: 'manual' }}
      onPointerEnter={store.pause}
      onPointerLeave={store.resume}
      onFocus={store.pause}
      onBlur={(event) => !event.currentTarget.contains(event.relatedTarget as Node) && store.resume()}
    >
      <ol ref={list} className="nx-toast-list" aria-live="polite">
        {items.map((item) => (
          <ToastItem key={item.id} toast={item} store={store} />
        ))}
      </ol>
    </section>
  );
}

function ToastItem({ toast, store }: { toast: Toast; store: ToastStore }) {
  const t = useT();
  const ref = useRef<HTMLLIElement>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    return swipe(el, { axis: 'x', threshold: 80, onSwipe: () => store.dismiss(toast.id) });
  }, [toast.id, store]);

  useEffect(() => {
    if (toast.state !== 'closing' || !ref.current) return;
    let alive = true;
    leave(ref.current).then(() => alive && store.remove(toast.id));
    return () => {
      alive = false;
    };
  }, [toast.state, toast.id, store]);

  return (
    <li ref={ref} className="nx-toast" data-tone={toast.tone} data-state={toast.state} role={toast.tone === 'danger' ? 'alert' : undefined}>
      <span className="nx-toast-icon">
        <Icon name={TONE_ICON[toast.tone]} />
      </span>
      <div className="nx-toast-body">
        <p className="nx-toast-title">{toast.title}</p>
        {toast.description && <p className="nx-toast-description">{toast.description}</p>}
      </div>
      {toast.action &&
        (toast.action.href ? (
          <SmartLink className="nx-button nx-toast-action" data-variant="secondary" data-size="xs" href={toast.action.href}>
            <span className="nx-button-label">{toast.action.label}</span>
          </SmartLink>
        ) : (
          <button
            type="button"
            className="nx-button nx-toast-action"
            data-variant="secondary"
            data-size="xs"
            onClick={() => {
              toast.action?.onClick?.();
              store.dismiss(toast.id);
            }}
          >
            <span className="nx-button-label">{toast.action.label}</span>
          </button>
        ))}
      <button type="button" className="nx-toast-close" aria-label={t('dismiss')} onClick={() => store.dismiss(toast.id)}>
        <Icon name="x" />
      </button>
      {toast.duration > 0 && toast.state === 'open' && <span className="nx-toast-timer" aria-hidden="true" style={{ '--nx-duration': `${toast.duration}ms` } as CSSProperties} />}
    </li>
  );
}

/* ---- Alert --------------------------------------------------------------------- */

export interface AlertProps extends Omit<HTMLAttributes<HTMLDivElement>, 'title'> {
  tone?: 'neutral' | 'accent' | 'success' | 'warning' | 'danger' | 'info';
  variant?: 'soft' | 'outline' | 'accent-bar';
  title?: ReactNode;
  /** A built-in icon, any node, or false for none. Defaults to the tone's icon. */
  icon?: IconName | ReactNode | false;
  actions?: ReactNode;
  /** Show a close button; the alert collapses away when pressed. */
  dismissible?: boolean;
  onDismiss?: () => void;
}

export function Alert({ tone = 'info', variant, title, icon, actions, dismissible, onDismiss, className, children, ...rest }: AlertProps) {
  const t = useT();
  const ref = useRef<HTMLDivElement>(null);
  const [gone, setGone] = useState(false);
  if (gone) return null;

  const shownIcon = icon === false ? null : icon === undefined ? <Icon name={TONE_ICON[tone]} /> : typeof icon === 'string' ? <Icon name={icon as IconName} /> : icon;

  return (
    <div ref={ref} className={cx('nx-alert', className)} data-tone={tone} data-variant={variant === 'soft' ? undefined : variant} {...rest}>
      {shownIcon ? <span className="nx-alert-icon">{shownIcon}</span> : <span />}
      <div className="nx-alert-content">
        {title && <p className="nx-alert-title">{title}</p>}
        {children && <div className="nx-alert-description">{children}</div>}
        {actions && <div className="nx-alert-actions">{actions}</div>}
      </div>
      {dismissible && (
        <button
          type="button"
          className="nx-alert-close"
          aria-label={t('dismiss')}
          onClick={() => {
            if (!ref.current) return;
            leave(ref.current).then(() => {
              setGone(true);
              onDismiss?.();
            });
          }}
        >
          <Icon name="x" />
        </button>
      )}
    </div>
  );
}

/* ---- Badge --------------------------------------------------------------------- */

export interface BadgeProps extends HTMLAttributes<HTMLSpanElement> {
  tone?: 'neutral' | 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';
  variant?: 'soft' | 'solid' | 'outline';
  size?: 'md' | 'lg';
  /** A leading dot; `pulse` makes it breathe (for live states). */
  dot?: boolean;
  pulse?: boolean;
}

export function Badge({ tone = 'neutral', variant, size, dot, pulse, className, children, ...rest }: BadgeProps) {
  return (
    <span
      className={cx('nx-badge', className)}
      data-tone={tone === 'neutral' ? undefined : tone}
      data-variant={variant === 'soft' ? undefined : variant}
      data-size={size === 'lg' ? 'lg' : undefined}
      data-dot={dot || pulse ? '' : undefined}
      data-pulse={pulse ? '' : undefined}
      {...rest}
    >
      {children}
    </span>
  );
}

/* ---- Tooltip -------------------------------------------------------------------- */

export interface TooltipProps {
  content: ReactNode;
  /** One focusable element: the tooltip describes it. */
  children: ReactElement;
  side?: 'top' | 'bottom' | 'start' | 'end';
  /** Hover delay before showing, ms (focus shows at once). */
  delay?: number;
  disabled?: boolean;
}


function showTop(el: HTMLElement) {
  if (typeof el.showPopover === 'function') {
    try {
      el.showPopover();
      return;
    } catch {
      /* already shown */
    }
  }
  el.setAttribute('data-open', '');
}

function hideTop(el: HTMLElement) {
  el.removeAttribute('data-open');
  try {
    if (el.matches(':popover-open')) el.hidePopover();
  } catch {
    /* no popover support */
  }
}

const SIDES = { top: 'top', bottom: 'bottom', start: 'inline-start', end: 'inline-end' } as const;

function childRef(child: ReactElement): Ref<HTMLElement> | undefined {
  // React 19 keeps the ref in props; React 18 on the element.
  return ((child.props as { ref?: Ref<HTMLElement> }).ref ?? (child as unknown as { ref?: Ref<HTMLElement> }).ref) || undefined;
}

export function Tooltip({ content, children, side = 'top', delay = 350, disabled }: TooltipProps) {
  const id = useId();
  const tip = useRef<HTMLDivElement>(null);
  const trigger = useRef<HTMLElement | null>(null);
  const timer = useRef<ReturnType<typeof setTimeout> | undefined>(undefined);
  const [open, setOpen] = useState(false);

  const schedule = (next: boolean, wait: number) => {
    clearTimeout(timer.current);
    timer.current = setTimeout(() => setOpen(next), wait);
  };

  useEffect(() => () => clearTimeout(timer.current), []);

  useEffect(() => {
    const el = tip.current;
    const anchor = trigger.current;
    if (!el || !anchor) return;
    if (!open || disabled) {
      hideTop(el);
      return;
    }
    showTop(el);
    const stop = place(anchor, el, { side: SIDES[side], align: 'center', offset: 8 });
    const onKey = (event: KeyboardEvent) => event.key === 'Escape' && setOpen(false);
    document.addEventListener('keydown', onKey);
    return () => {
      stop();
      document.removeEventListener('keydown', onKey);
    };
  }, [open, disabled, side]);

  const child = Children.only(children);
  if (!isValidElement(child)) return child;
  const props = child.props as Record<string, (event: never) => void> & { 'aria-describedby'?: string };
  const chain = (name: string, fn: () => void) => (event: never) => {
    props[name]?.(event);
    fn();
  };

  return (
    <>
      {cloneElement(child as ReactElement<Record<string, unknown>>, {
        ref: mergeRefs(childRef(child), trigger),
        'aria-describedby': cx(props['aria-describedby'], disabled ? undefined : id) || undefined,
        onPointerEnter: chain('onPointerEnter', () => schedule(true, delay)),
        onPointerLeave: chain('onPointerLeave', () => schedule(false, 100)),
        onFocus: chain('onFocus', () => schedule(true, 0)),
        onBlur: chain('onBlur', () => schedule(false, 0)),
      })}
      <div
        ref={tip}
        id={id}
        role="tooltip"
        className="nx-tooltip"
        {...{ popover: 'manual' }}
        onPointerEnter={() => clearTimeout(timer.current)}
        onPointerLeave={() => schedule(false, 100)}
      >
        {content}
      </div>
    </>
  );
}

/* ---- Progress, skeleton, loaders ------------------------------------------------ */

export interface ProgressProps extends Omit<HTMLAttributes<HTMLProgressElement>, 'children'> {
  /** Omit for an indeterminate bar. */
  value?: number;
  max?: number;
  tone?: 'accent' | 'brand' | 'success' | 'warning' | 'danger';
  label?: string;
}

export function Progress({ value, max = 100, tone, label, className, style, ...rest }: ProgressProps) {
  const pct = value === undefined ? undefined : Math.min(100, Math.max(0, (value / max) * 100));
  return (
    <progress
      className={cx('nx-progress', className)}
      value={value}
      max={max}
      aria-label={label}
      data-tone={tone === 'accent' ? undefined : tone}
      style={{ ...(pct !== undefined ? { '--nx-value': pct } : null), ...style } as CSSProperties}
      {...rest}
    />
  );
}

export interface SkeletonProps extends HTMLAttributes<HTMLSpanElement> {
  shape?: 'text' | 'circle' | 'rect' | 'block';
  /** For text: how many lines (the last one shorter). */
  lines?: number;
  width?: string;
  height?: string;
}

export function Skeleton({ shape = 'text', lines = 1, width, height, className, style, ...rest }: SkeletonProps) {
  const item = (key?: number) => (
    <span
      key={key}
      className={cx('nx-skeleton', className)}
      data-shape={shape === 'block' ? undefined : shape}
      aria-hidden="true"
      style={{ inlineSize: width, ...(height ? { blockSize: height } : null), ...(shape === 'circle' && width ? { '--nx-skeleton-size': width } : null), ...style } as CSSProperties}
      {...rest}
    />
  );
  if (shape !== 'text' || lines <= 1) return item();
  return <span style={{ display: 'grid', gap: '0.6em' }}>{Array.from({ length: lines }, (_, i) => item(i))}</span>;
}

export interface LoaderProps {
  /** What is loading, for screen readers. Defaults to "Loading". */
  label?: string;
  size?: 'sm' | 'md' | 'lg';
  className?: string;
}

export function Spinner({ label, size, className, tone }: LoaderProps & { tone?: 'accent' }) {
  const t = useT();
  return <progress className={cx('nx-spinner', className)} aria-label={label ?? t('loading')} data-size={size === 'md' ? undefined : size} data-tone={tone} />;
}

const LOADER_SIZE = { sm: '2.25rem', md: '3.5rem', lg: '5rem' } as const;

export function OrbitLoader({ label, size = 'md', className }: LoaderProps) {
  const t = useT();
  const rings = [
    { r: '0deg', c: 'var(--nx-lapis-500)', d: '1.6s' },
    { r: '60deg', c: 'var(--nx-cyan-400)', d: '2.1s' },
    { r: '120deg', c: 'var(--nx-violet-500)', d: '2.6s' },
  ];
  return (
    <span className={cx('nx-orbit', className)} role="status" style={{ '--nx-loader-size': LOADER_SIZE[size] } as CSSProperties}>
      <svg viewBox="0 0 64 64" aria-hidden="true">
        {rings.map((ring) => (
          <g key={ring.r} className="nx-orbit-ring" style={{ '--r': ring.r, '--c': ring.c, '--d': ring.d } as CSSProperties}>
            <ellipse className="nx-orbit-path" cx="32" cy="32" rx="26" ry="10" />
            <ellipse className="nx-orbit-comet" cx="32" cy="32" rx="26" ry="10" pathLength={100} />
          </g>
        ))}
        <circle className="nx-orbit-core" cx="32" cy="32" r="4.5" />
      </svg>
      <span className="nx-visually-hidden">{label ?? t('loading')}</span>
    </span>
  );
}

export function DotsLoader({ label, className }: LoaderProps) {
  const t = useT();
  return (
    <span className={cx('nx-dots', className)} role="status">
      <i />
      <i />
      <i />
      <span className="nx-visually-hidden">{label ?? t('loading')}</span>
    </span>
  );
}

/** Wedges pressed into clay one after another — the Nabu scribe at work. */
export const WEDGES = [
  { transform: 'translate(2 7)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
  { transform: 'translate(24 1) rotate(90 5 5)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
  { transform: 'translate(34 7)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
  { transform: 'translate(52 5)', d: 'M10 0L0 7L10 14L6.5 7Z' },
] as const;

export function CuneiformLoader({ label, size = 'md', className }: LoaderProps) {
  const t = useT();
  return (
    <span className={cx('nx-cuneiform', className)} role="status" style={{ '--nx-loader-size': { sm: '3rem', md: '4.5rem', lg: '6.5rem' }[size] } as CSSProperties}>
      <svg viewBox="0 0 64 24" aria-hidden="true">
        {WEDGES.map((wedge, i) => (
          <g key={i} transform={wedge.transform}>
            <path className="nx-wedge" d={wedge.d} style={{ '--i': i } as CSSProperties} />
          </g>
        ))}
      </svg>
      <span className="nx-visually-hidden">{label ?? t('loading')}</span>
    </span>
  );
}
