/**
 * Empty states (React): the friendly "nothing here yet" for panel pages.
 *
 * Thin over css/blocks/empty-state.css — a floating plate with the page's
 * icon, a dashed orbit with two sparks and a breathing halo, all pure CSS
 * from the tokens, resting under prefers-reduced-motion. The words and the
 * actions come in through props; only the fallback title is the block's own.
 */
import { type HTMLAttributes, type ReactNode, useRef } from 'react';
import { type IconName, reveal } from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useT } from '../internal/provider';
import { Button } from '../components/button';

export interface EmptyStateAction {
  label: ReactNode;
  /** Render the action as a link (through the app's router for internal paths). */
  href?: string;
  onClick?: () => void;
  icon?: IconName;
}

export interface EmptyStateProps extends Omit<HTMLAttributes<HTMLDivElement>, 'title'> {
  /** A built-in icon name for the plate (see core icons). */
  icon?: IconName;
  title?: ReactNode;
  description?: ReactNode;
  /** The primary way out ("Create project"). */
  action?: EmptyStateAction;
  /** A quieter way out ("Import"). */
  secondaryAction?: EmptyStateAction;
  /** Your own actions row, replacing `action` / `secondaryAction`. */
  actions?: ReactNode;
  size?: 'sm' | 'md' | 'lg';
}

export function EmptyState({ icon = 'folder', title, description, action, secondaryAction, actions, size, className, ...rest }: EmptyStateProps) {
  const t = useT();
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, reveal, { once: true });

  const buttons = actions ?? (
    <>
      {action && (
        <Button variant="primary" size={size === 'sm' ? 'sm' : 'md'} icon={action.icon} href={action.href} onClick={action.onClick}>
          {action.label}
        </Button>
      )}
      {secondaryAction && (
        <Button variant="secondary" size={size === 'sm' ? 'sm' : 'md'} icon={secondaryAction.icon} href={secondaryAction.href} onClick={secondaryAction.onClick}>
          {secondaryAction.label}
        </Button>
      )}
    </>
  );
  const hasActions = actions != null || !!action || !!secondaryAction;

  return (
    <div ref={ref} className={cx('nx-empty-state', className)} data-size={size && size !== 'md' ? size : undefined} data-nx-reveal="" {...rest}>
      <span className="nx-empty-state-art" aria-hidden="true">
        <span className="nx-empty-state-halo" />
        <span className="nx-empty-state-orbit">
          <i className="nx-empty-state-spark" />
          <i className="nx-empty-state-spark" />
        </span>
        <span className="nx-empty-state-plate">
          <Icon name={icon} />
        </span>
      </span>
      <p className="nx-empty-state-title">{title ?? t('noResults')}</p>
      {description && <p className="nx-empty-state-description">{description}</p>}
      {hasActions && <div className="nx-empty-state-actions">{buttons}</div>}
    </div>
  );
}
