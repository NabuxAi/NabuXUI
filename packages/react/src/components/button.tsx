import {
  type AnchorHTMLAttributes,
  type ButtonHTMLAttributes,
  type MouseEvent,
  type ReactNode,
  forwardRef,
  isValidElement,
  useEffect,
  useRef,
  useState,
} from 'react';
import { type IconName, burst, copyText, magnetic as magneticBehavior, ripple as rippleBehavior } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';
import { NumberTicker } from './text';

export type ButtonVariant = 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'gold' | 'glow' | 'inverse' | 'link';
export type ButtonSize = 'xs' | 'sm' | 'md' | 'lg' | 'xl';
export type ButtonStatus = 'idle' | 'loading' | 'success' | 'error';

interface ButtonOwnProps {
  variant?: ButtonVariant;
  size?: ButtonSize;
  shape?: 'rounded' | 'pill' | 'square';
  /** Morphs the label into a spinner, a check or a cross without changing the width. */
  status?: ButtonStatus;
  /** Shorthand for status="loading". */
  loading?: boolean;
  /** A sheen on hover, or the label sliding out for its copy. */
  effect?: 'shine' | 'slide';
  /** Lean toward the pointer. */
  magnetic?: boolean;
  /** A ripple from the press point. */
  ripple?: boolean;
  /** Leading icon: a built-in icon name or any node. */
  icon?: IconName | ReactNode;
  iconEnd?: IconName | ReactNode;
  /** Square button holding only an icon; give it an aria-label. */
  iconOnly?: boolean;
  block?: boolean;
  /** Render as a link (through the app's router link for internal paths). */
  href?: string;
  children?: ReactNode;
}

export type ButtonProps = ButtonOwnProps &
  Omit<ButtonHTMLAttributes<HTMLButtonElement>, keyof ButtonOwnProps> &
  Pick<AnchorHTMLAttributes<HTMLAnchorElement>, 'target' | 'rel' | 'download'>;

const renderIcon = (icon: IconName | ReactNode) => (typeof icon === 'string' ? <Icon name={icon as IconName} /> : isValidElement(icon) ? icon : icon);

/** The status layer: all three marks are present, CSS shows the current one. */
export function ButtonStatusMarks() {
  return (
    <span className="nx-button-status" aria-hidden="true">
      <span className="nx-spinner" data-size="sm" />
      <svg className="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
        <path d="M5 12.5l4.5 4.5L19 7.5" pathLength={1} />
      </svg>
      <svg className="nx-icon nx-status-cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
        <path d="M7 7l10 10M17 7L7 17" pathLength={1} />
      </svg>
    </span>
  );
}

export const Button = forwardRef<HTMLButtonElement | HTMLAnchorElement, ButtonProps>(function Button(
  {
    variant = 'secondary',
    size = 'md',
    shape,
    status,
    loading,
    effect,
    magnetic,
    ripple,
    icon,
    iconEnd,
    iconOnly,
    block,
    href,
    className,
    children,
    type,
    onClick,
    disabled,
    ...rest
  },
  forwarded,
) {
  const ref = useRef<HTMLElement>(null);
  const resolved: ButtonStatus = loading ? 'loading' : status ?? 'idle';
  const busy = resolved === 'loading';
  useBehavior(ref, magneticBehavior, {}, !!magnetic);
  useBehavior(ref, rippleBehavior, undefined as never, !!ripple);

  const text = typeof children === 'string' ? children : undefined;
  const label = (
    <span className="nx-button-label" data-text={effect === 'slide' ? text : undefined}>
      {icon ? renderIcon(icon) : null}
      {children !== undefined && children !== null && (iconOnly ? <span className="nx-visually-hidden">{children}</span> : <span className="nx-button-text">{children}</span>)}
      {iconEnd ? renderIcon(iconEnd) : null}
    </span>
  );

  const shared = {
    ref: mergeRefs(ref, forwarded as never),
    className: cx('nx-button', className),
    'data-variant': variant,
    'data-size': size === 'md' ? undefined : size,
    'data-shape': shape && shape !== 'rounded' ? shape : undefined,
    'data-status': resolved === 'idle' ? undefined : resolved,
    'data-effect': effect,
    'data-icon-only': iconOnly ? '' : undefined,
    'data-block': block ? '' : undefined,
    'aria-busy': busy || undefined,
  };

  if (href) {
    return (
      <SmartLink href={href} {...shared} {...(rest as AnchorHTMLAttributes<HTMLAnchorElement>)} aria-disabled={disabled || undefined} onClick={onClick as never}>
        {label}
        <ButtonStatusMarks />
      </SmartLink>
    );
  }

  return (
    <button
      {...shared}
      type={type ?? 'button'}
      disabled={disabled}
      // Busy buttons stay focusable (disabled would drop focus) but ignore presses.
      aria-disabled={busy || undefined}
      onClick={(event: MouseEvent<HTMLButtonElement>) => {
        if (busy) {
          event.preventDefault();
          return;
        }
        onClick?.(event);
      }}
      {...rest}
    >
      {label}
      <ButtonStatusMarks />
    </button>
  );
});

export type IconButtonProps = Omit<ButtonProps, 'iconOnly' | 'icon' | 'children'> & {
  icon: IconName | ReactNode;
  /** The accessible name — required, since there is no visible text. */
  label: string;
};

export const IconButton = forwardRef<HTMLButtonElement | HTMLAnchorElement, IconButtonProps>(function IconButton({ icon, label, variant = 'ghost', ...rest }, ref) {
  return <Button ref={ref} variant={variant} icon={icon} iconOnly aria-label={label} title={label} {...rest} />;
});

/* ---- Copy ------------------------------------------------------------------- */

export interface CopyButtonProps extends Omit<ButtonProps, 'onClick' | 'icon' | 'status'> {
  /** The text that goes to the clipboard. */
  value: string;
  /** How long the check stays, ms. */
  timeout?: number;
  onCopied?: () => void;
}

export function CopyButton({ value, timeout = 1600, onCopied, children, variant = 'ghost', className, ...rest }: CopyButtonProps) {
  const t = useT();
  const [copied, setCopied] = useState(false);

  useEffect(() => {
    if (!copied) return;
    const id = setTimeout(() => setCopied(false), timeout);
    return () => clearTimeout(id);
  }, [copied, timeout]);

  return (
    <Button
      variant={variant}
      className={cx('nx-copy', className)}
      data-copied={copied ? '' : undefined}
      iconOnly={!children}
      aria-label={children ? undefined : t('copy')}
      icon={
        <span className="nx-copy-icons" aria-hidden="true">
          <Icon name="copy" className="nx-copy-idle" />
          <Icon name="check" className="nx-copy-done" />
        </span>
      }
      onClick={async () => {
        if (await copyText(value)) {
          setCopied(true);
          onCopied?.();
        }
      }}
      {...rest}
    >
      {children}
      <span className="nx-visually-hidden" aria-live="polite">
        {copied ? t('copied') : ''}
      </span>
    </Button>
  );
}

/* ---- Like ----------------------------------------------------------------------- */

export interface LikeButtonProps extends Omit<ButtonProps, 'onClick' | 'icon' | 'children'> {
  liked?: boolean;
  defaultLiked?: boolean;
  onLikedChange?: (liked: boolean) => void;
  /** Count shown beside the heart (without this press). */
  count?: number;
  label?: string;
}

export function LikeButton({ liked, defaultLiked = false, onLikedChange, count, label, variant = 'ghost', className, ...rest }: LikeButtonProps) {
  const t = useT();
  const ref = useRef<HTMLButtonElement>(null);
  const [on, setOn] = useControllable(liked, defaultLiked, onLikedChange);

  return (
    <Button
      ref={ref}
      variant={variant}
      className={cx('nx-like', className)}
      aria-pressed={on}
      aria-label={label ?? t('like')}
      icon={<Icon name="heart" className="nx-like-heart" />}
      onClick={() => {
        const next = !on;
        setOn(next);
        if (next && ref.current) burst(ref.current);
      }}
      {...rest}
    >
      {count !== undefined ? <NumberTicker value={count + (on ? 1 : 0)} reveal={false} /> : undefined}
    </Button>
  );
}
