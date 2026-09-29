/**
 * Sort pill — a quick single-choice sort/filter control (React).
 *
 * Thin over css/blocks/sort-pill.css: the round pill's trigger, the stacked
 * label roll and the springy check are pure CSS; the native popover, its
 * placement against the trigger and light dismissal come from
 * '@nabuxai/ui-core'.
 */
import { useEffect, useId, useRef, useState } from 'react';
import { type Align, type IconName, lightDismiss, place, roveFocus } from '@nabuxai/ui-core';
import { cx, useControllable, useEvent, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';

const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

function useBaseId(prefix: string) {
  return `${prefix}${useId().replace(/:/g, '')}`;
}

interface PopoverWiring {
  id: string;
  open: boolean;
  setOpen: (open: boolean) => void;
  trigger: RefObjectLike<HTMLButtonElement>;
  panel: RefObjectLike<HTMLDivElement>;
  align?: Align;
  offset?: number;
}

/** The slice of React's RefObject this file needs (kept local, like menus.tsx's popover wiring). */
interface RefObjectLike<T> {
  current: T | null;
}

/**
 * A native [popover] panel driven by React state: the trigger is its declarative
 * invoker (light dismiss, Escape and focus return come from the browser), core
 * `place` keeps it against the trigger and scaling out of it.
 */
function usePopover({ id, open, setOpen, trigger, panel, align = 'start', offset = 8 }: PopoverWiring) {
  const state = useRef(open);
  state.current = open;
  const change = useEvent((next: boolean) => {
    if (next !== state.current) setOpen(next);
  });

  useEffect(() => {
    if (supportsPopover()) trigger.current?.setAttribute('popovertarget', id);
  }, [id, trigger]);

  useEffect(() => {
    const el = panel.current;
    if (!el) return;
    const onToggle = (event: Event) => change((event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  }, [panel, change]);

  useIsoLayoutEffect(() => {
    const el = panel.current;
    if (!el) return;
    if (!supportsPopover()) {
      el.toggleAttribute('data-open', open);
      return;
    }
    const shown = el.matches(':popover-open');
    try {
      if (open && !shown) el.showPopover();
      if (!open && shown) el.hidePopover();
    } catch {
      /* not connected yet */
    }
  }, [open, panel]);

  useIsoLayoutEffect(() => {
    if (!open || !trigger.current || !panel.current) return;
    return place(trigger.current, panel.current, { side: 'bottom', align, offset });
  }, [open, align, offset]);

  // Without popover support: close on Escape or a press outside.
  useEffect(() => {
    if (!open || supportsPopover() || !panel.current) return;
    return lightDismiss(panel.current, () => change(false), { inside: [trigger.current] });
  }, [open, change]);

  return {
    /** The trigger's click when the browser cannot toggle the popover itself. */
    onTriggerClick: () => {
      if (!supportsPopover()) change(!state.current);
    },
  };
}

export interface SortPillOption {
  value: string;
  label: string;
  icon?: IconName;
}

export interface SortPillProps {
  /** The choices, in order; the first one is the default. */
  options: SortPillOption[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  /** The control's accessible name, read before the chosen label (defaults to "Sort"). */
  label?: string;
  /** Which edge of the trigger the panel lines up with. */
  align?: Align;
  className?: string;
}

/** A quick sort pill ("Featured ∨"): the chosen label rolls into the trigger, the panel is a listbox. */
export function SortPill({ options, value, defaultValue, onValueChange, label, align = 'start', className }: SortPillProps) {
  const id = useBaseId('nx-sort');
  const [selected, setSelected] = useControllable(value, defaultValue ?? options[0]?.value ?? '', onValueChange);
  const [open, setOpen] = useState(false);
  const trigger = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLDivElement>(null);
  const { onTriggerClick } = usePopover({ id, open, setOpen, trigger, panel, align, offset: 8 });

  const current = options.find((option) => option.value === selected) ?? options[0] ?? null;

  // Let the label finish rolling before the panel folds away.
  const choose = (next: string) => {
    setSelected(next);
    window.setTimeout(() => {
      const el = panel.current;
      if (!el) return;
      if (supportsPopover()) {
        if (el.matches(':popover-open')) el.hidePopover();
      } else setOpen(false);
    }, 300);
  };

  return (
    <>
      <button
        ref={trigger}
        type="button"
        className={cx('nx-sort-pill-trigger', className)}
        aria-haspopup="listbox"
        aria-expanded={open}
        aria-controls={id}
        aria-label={`${label ?? 'Sort'}: ${current?.label ?? ''}`}
        onClick={onTriggerClick}
      >
        <span className="nx-sort-pill-labels" aria-hidden="true">
          {options.map((option) => (
            <span key={option.value} className="nx-sort-pill-label" data-current={option.value === selected ? '' : undefined}>
              {option.label}
            </span>
          ))}
        </span>
        <span className="nx-sort-pill-chevron" aria-hidden="true">
          <Icon name="chevron-down" />
        </span>
      </button>
      <div
        ref={panel}
        id={id}
        className="nx-sort-pill"
        role="listbox"
        aria-label={label ?? 'Sort'}
        {...{ popover: 'auto' }}
        onKeyDown={(event) => roveFocus(event.nativeEvent, event.currentTarget, '.nx-sort-pill-choice', { orientation: 'vertical' })}
      >
        <ul className="nx-sort-pill-list">
          {options.map((option) => {
            const picked = option.value === selected;
            return (
              <li key={option.value} className="nx-sort-pill-option" data-selected={picked ? '' : undefined}>
                <button type="button" className="nx-sort-pill-choice" role="option" aria-selected={picked} onClick={() => choose(option.value)}>
                  {option.icon && <Icon name={option.icon} />}
                  <span>{option.label}</span>
                  <span className="nx-sort-pill-mark" aria-hidden="true">
                    <Icon name="check" />
                  </span>
                </button>
              </li>
            );
          })}
        </ul>
      </div>
    </>
  );
}
