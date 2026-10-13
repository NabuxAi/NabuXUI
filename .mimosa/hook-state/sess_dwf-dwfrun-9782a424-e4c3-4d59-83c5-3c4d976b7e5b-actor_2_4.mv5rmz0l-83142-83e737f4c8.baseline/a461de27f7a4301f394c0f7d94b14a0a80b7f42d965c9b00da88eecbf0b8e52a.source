/**
 * Chip filter (React).
 *
 * A single-select row of filter pills — the trending-tags / category-pills
 * pattern. Thin over css/blocks/chip-filter.css: the row scrolls and fades at
 * its edges, and the accent thumb that springs under the checked chip is the
 * core `indicator`, the same mechanism as the segmented control.
 */
import { type HTMLAttributes, type ReactNode, useId, useRef } from 'react';
import { type IconName, menuWord } from '@nabuxai/ui-core';
import { cx, useControllable, useIndicator, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale } from '../internal/provider';

export interface ChipFilterItem {
  value: string;
  label: ReactNode;
  /** A small round count badge next to the label. */
  count?: number;
  icon?: IconName;
  disabled?: boolean;
}

export interface ChipFilterProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'defaultValue' | 'onChange'> {
  /** The filter choices; the first one is usually the "All" chip. */
  items: ChipFilterItem[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  /** Form field name the radios post as (radios share a generated one by default). */
  name?: string;
  /** Accessible name of the group (defaults to "Filter" in the provider's language). */
  'aria-label'?: string;
  className?: string;
}

const reducedMotion = () => typeof window !== 'undefined' && !!window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

export function ChipFilter({ items, value, defaultValue, onValueChange, name, 'aria-label': label, className, ...rest }: ChipFilterProps) {
  const locale = useLocale();
  const generated = useId();
  // Without a shared name the radios are separate groups and the arrow keys die.
  const group = name ?? `nx-chip${generated.replace(/:/g, '')}`;
  const [current, setCurrent] = useControllable(value, defaultValue ?? items[0]?.value ?? '', onValueChange);
  const row = useRef<HTMLDivElement>(null);
  const ind = useIndicator(row);
  const mounted = useRef(false);

  useIsoLayoutEffect(() => {
    const chip = row.current?.querySelector<HTMLElement>('.nx-chip-filter-chip:has(:checked)') ?? null;
    ind.current?.update(chip);
    // Once live, keep the picked chip on screen (keyboard and programmatic picks too).
    if (!mounted.current) mounted.current = true;
    else chip?.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: reducedMotion() ? 'auto' : 'smooth' });
  }, [current, items, ind]);

  return (
    <div className={cx('nx-chip-filter', className)} role="group" aria-label={label ?? menuWord(locale, 'filter')} {...rest}>
      <div ref={row} className="nx-chip-filter-row">
        <span className="nx-indicator nx-chip-filter-thumb" aria-hidden="true" />
        {items.map((item) => (
          <label key={item.value} className="nx-chip-filter-chip" data-value={item.value}>
            <input
              className="nx-chip-filter-input"
              type="radio"
              name={group}
              value={item.value}
              checked={current === item.value}
              disabled={item.disabled}
              onChange={() => setCurrent(item.value)}
            />
            {item.icon && <Icon name={item.icon} />}
            <span>{item.label}</span>
            {item.count !== undefined && <span className="nx-chip-filter-count">{item.count}</span>}
          </label>
        ))}
      </div>
    </div>
  );
}
