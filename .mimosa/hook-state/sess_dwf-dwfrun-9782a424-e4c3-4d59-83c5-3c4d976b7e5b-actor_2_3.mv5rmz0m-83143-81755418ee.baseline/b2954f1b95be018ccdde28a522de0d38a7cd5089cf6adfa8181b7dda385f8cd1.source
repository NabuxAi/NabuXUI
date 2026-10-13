/**
 * Menus, navigation & morphing panels (React).
 *
 * Thin over css/blocks/menus.css and the core behaviours: the size morph
 * (morphShell), light dismissal, the morphing tab strip, the avatar-stack
 * FLIP and the recorder's level meter all come from '@nabuxai/ui-core'.
 */
import {
  Children,
  type CSSProperties,
  type FormEvent,
  type FormHTMLAttributes,
  type HTMLAttributes,
  type KeyboardEvent,
  type ReactElement,
  type ReactNode,
  type RefObject,
  cloneElement,
  isValidElement,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import {
  type Align,
  type IconName,
  type MenuGlyph,
  type MenuWord,
  type MorphTabsController,
  type Side,
  activityTime,
  burst,
  direction,
  directionalGlyphs,
  downsampleLevels,
  flipStack,
  focusableItems,
  foldSearchText,
  formatClock,
  hoverNav,
  icons,
  levelMeter,
  lightDismiss,
  localeDigits,
  menuGlyphs,
  menuPath,
  menuWord,
  morphShell,
  morphTabs,
  place,
  rmsLevel,
  roveFocus,
  searchMenuTree,
  theme,
  ticketTotal,
  timeOf,
  toggleSelection,
  voiceLevel,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useEvent, useIndicator, useIsoLayoutEffect, useMounted } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { Button } from '../components/button';
import { Avatar, Kbd } from '../components/display';
import { Field, Input } from '../components/form';
import { NumberTicker } from '../components/text';

/* ---- Shared internals ------------------------------------------------------------ */

const INTL_LOCALES: Record<string, string> = { en: 'en-US', fa: 'fa-IR', ar: 'ar' };
const idSafe = (value: string) => value.replace(/[^\w-]/g, '_');
const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

type Words = Partial<Record<MenuWord, string>>;

/** The block's own words in the provider's language, overridable per instance. */
function useWords(labels?: Words) {
  const locale = useLocale();
  return (key: MenuWord, params?: Record<string, string | number>) => labels?.[key] ?? menuWord(locale, key, params);
}

function useBaseId(prefix: string) {
  return `${prefix}${useId().replace(/:/g, '')}`;
}

/** A glyph beyond the core icon set (mic-off, hand, leave, calendar, pin). */
function Glyph({ name, className }: { name: MenuGlyph; className?: string }) {
  return (
    <svg
      className={cx('nx-icon', className)}
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeLinecap="round"
      strokeLinejoin="round"
      aria-hidden="true"
      {...(directionalGlyphs.includes(name) ? { 'data-directional': '' } : {})}
    >
      <path d={menuGlyphs[name]} />
    </svg>
  );
}

/** Keep `shell` sized to `content` (core morphShell); the CSS springs between sizes. */
function useMorphShell(shell: RefObject<HTMLElement | null>, content: RefObject<HTMLElement | null>, axis: 'both' | 'block' | 'inline' = 'both') {
  useIsoLayoutEffect(() => {
    if (!shell.current || !content.current) return;
    return morphShell(shell.current, { content: content.current, axis });
  }, [shell, content, axis]);
}

interface PopoverWiring {
  id: string;
  open: boolean;
  setOpen: (open: boolean) => void;
  trigger: RefObject<HTMLElement | null>;
  panel: RefObject<HTMLElement | null>;
  side?: Side;
  align?: Align;
  offset?: number;
}

/**
 * A native [popover] panel driven by React state: the trigger is its declarative
 * invoker (light dismiss, Escape and focus return come from the browser), core
 * `place` keeps it against the trigger and scaling out of it.
 */
function usePopover({ id, open, setOpen, trigger, panel, side = 'bottom', align = 'start', offset = 8 }: PopoverWiring) {
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
    return place(trigger.current, panel.current, { side, align, offset });
  }, [open, side, align, offset]);

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

function childRef(child: ReactElement): React.Ref<HTMLElement> | undefined {
  // React 19 keeps the ref in props; React 18 on the element.
  return ((child.props as { ref?: React.Ref<HTMLElement> }).ref ?? (child as unknown as { ref?: React.Ref<HTMLElement> }).ref) || undefined;
}

/** Characters that roll digit by digit (a clock, a count), in the locale's digits. */
function RollingText({ text, locale, className }: { text: string; locale?: string; className?: string }) {
  const digits = useMemo(() => localeDigits(locale), [locale]);
  const chars = Array.from(text);
  return (
    <span className={cx('nx-number', className)}>
      <span className="nx-visually-hidden">{chars.map((c) => (c >= '0' && c <= '9' ? digits[Number(c)] : c)).join('')}</span>
      <span className="nx-number-roll" aria-hidden="true">
        {chars.map((char, i) => {
          const key = chars.length - i;
          return char >= '0' && char <= '9' ? (
            <span key={key} className="nx-digit" style={{ '--d': Number(char), '--nx-p': 0 } as CSSProperties}>
              <span className="nx-digit-track">
                {digits.map((digit) => (
                  <span key={digit}>{digit}</span>
                ))}
              </span>
            </span>
          ) : (
            <span key={key} className="nx-number-sep">
              {char}
            </span>
          );
        })}
      </span>
    </span>
  );
}

/* ============================================================================
 * FoldMenu — a menu panel that unfolds like folded paper.
 * ========================================================================== */

export interface FoldMenuLink {
  label: ReactNode;
  href?: string;
  onSelect?: () => void;
  icon?: IconName;
  description?: ReactNode;
  current?: boolean;
}

export interface FoldMenuSection {
  title?: ReactNode;
  links: FoldMenuLink[];
}

export interface FoldMenuProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  /** One fold per section (three or four read best). */
  sections: FoldMenuSection[];
  /** An extra last fold: a call to action, a language switch… */
  footer?: ReactNode;
  /** Trigger text (defaults to "Menu" in the provider's language). */
  label?: ReactNode;
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  /** Which edge of the trigger the panel lines up with. */
  align?: 'start' | 'end';
  /** Name of the navigation landmark. */
  navLabel?: string;
}

export function FoldMenu({ sections, footer, label, open, defaultOpen = false, onOpenChange, align = 'start', navLabel, className, ...rest }: FoldMenuProps) {
  const t = useT();
  const id = useBaseId('nx-fold');
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const trigger = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLElement>(null);
  const focusFirst = useRef(false);
  const { onTriggerClick } = usePopover({ id, open: isOpen, setOpen, trigger, panel, side: 'bottom', align, offset: 10 });

  useEffect(() => {
    if (!isOpen || !focusFirst.current) return;
    focusFirst.current = false;
    panel.current?.querySelector<HTMLElement>('.nx-fold-menu-link')?.focus({ preventScroll: true });
  }, [isOpen]);

  const folds: Array<{ title?: ReactNode; links?: FoldMenuLink[]; extra?: ReactNode }> = [...sections, ...(footer ? [{ extra: footer }] : [])];

  const close = (focusTrigger: boolean) => {
    setOpen(false);
    if (focusTrigger) trigger.current?.focus();
  };

  const renderLink = (link: FoldMenuLink, j: number) => {
    const body = (
      <>
        {link.icon && (
          <span className="nx-fold-menu-icon">
            <Icon name={link.icon} />
          </span>
        )}
        <span className="nx-fold-menu-text">
          <span>{link.label}</span>
          {link.description && <span className="nx-fold-menu-description">{link.description}</span>}
        </span>
      </>
    );
    return (
      <li key={j} style={{ '--nx-j': j } as CSSProperties}>
        {link.href ? (
          <SmartLink
            href={link.href}
            className="nx-fold-menu-link"
            aria-current={link.current ? 'page' : undefined}
            onClick={() => {
              link.onSelect?.();
              close(false);
            }}
          >
            {body}
          </SmartLink>
        ) : (
          <button
            type="button"
            className="nx-fold-menu-link"
            onClick={() => {
              link.onSelect?.();
              close(true);
            }}
          >
            {body}
          </button>
        )}
      </li>
    );
  };

  // Each fold hangs from the bottom edge of the one before it.
  const renderFold = (index: number): ReactNode => {
    const fold = folds[index];
    if (!fold) return null;
    return (
      <div className="nx-fold-menu-fold" style={{ '--nx-i': index } as CSSProperties} data-first={index === 0 ? '' : undefined} data-last={index === folds.length - 1 ? '' : undefined}>
        <div className="nx-fold-menu-face">
          {fold.title && <p className="nx-fold-menu-title">{fold.title}</p>}
          {fold.links && <ul className="nx-fold-menu-links">{fold.links.map(renderLink)}</ul>}
          {fold.extra}
        </div>
        {renderFold(index + 1)}
      </div>
    );
  };

  return (
    <div className={cx('nx-fold-menu', className)} {...rest}>
      <button
        ref={trigger}
        type="button"
        className="nx-fold-menu-trigger"
        aria-expanded={isOpen}
        aria-controls={id}
        onClick={onTriggerClick}
        onKeyDown={(event) => {
          if (event.key === 'ArrowDown' && !isOpen) {
            event.preventDefault();
            focusFirst.current = true;
            setOpen(true);
          }
        }}
      >
        <span>{label ?? t('menu')}</span>
        <span className="nx-fold-menu-burger" aria-hidden="true">
          <i />
          <i />
        </span>
      </button>
      <nav
        ref={panel}
        id={id}
        className="nx-fold-menu-panel"
        aria-label={navLabel ?? (typeof label === 'string' ? label : t('menu'))}
        {...{ popover: 'auto' }}
        style={{ '--_n': folds.length } as CSSProperties}
        onKeyDown={(event: KeyboardEvent<HTMLElement>) => roveFocus(event.nativeEvent, event.currentTarget, '.nx-fold-menu-link', { orientation: 'vertical' })}
      >
        {renderFold(0)}
      </nav>
    </div>
  );
}

/* ============================================================================
 * DockPanels — a floating dock whose container morphs into each item's panel.
 * ========================================================================== */

export interface DockPanelItem {
  id: string;
  label: string;
  icon: IconName | ReactNode;
  content: ReactNode;
}

export interface DockPanelsProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'defaultValue'> {
  items: DockPanelItem[];
  /** The open item's id, or null when the dock is closed. */
  value?: string | null;
  defaultValue?: string | null;
  onValueChange?: (id: string | null) => void;
}

export function DockPanels({ items, value, defaultValue = null, onValueChange, 'aria-label': label, className, style, ...rest }: DockPanelsProps) {
  const base = useBaseId('nx-dockp');
  const word = useWords();
  const [active, setActive] = useControllable<string | null>(value, defaultValue, onValueChange);
  const [stop, setStop] = useState(0);
  const root = useRef<HTMLDivElement>(null);
  const shell = useRef<HTMLDivElement>(null);
  const measure = useRef<HTMLDivElement>(null);
  useMorphShell(shell, measure);

  const open = active !== null && items.some((item) => item.id === active);
  const activeIndex = items.findIndex((item) => item.id === active);
  const tabStop = activeIndex >= 0 ? activeIndex : Math.min(stop, Math.max(0, items.length - 1));

  useEffect(() => {
    if (!open || !root.current || active === null) return;
    const current = active;
    return lightDismiss(root.current, (reason) => {
      setActive(null);
      if (reason === 'escape') root.current?.querySelector<HTMLElement>(`#${base}-item-${idSafe(current)}`)?.focus();
    });
  }, [open, active, base, setActive]);

  return (
    <div ref={root} className={cx('nx-dock-panels', className)} data-state={open ? 'open' : 'closed'} style={{ '--_n': items.length, ...style } as CSSProperties} {...rest}>
      <div
        className="nx-dock-panels-bar"
        role="toolbar"
        aria-label={label ?? word('dock')}
        onKeyDown={(event) => {
          const next = roveFocus(event.nativeEvent, event.currentTarget, '.nx-dock-panels-item');
          if (next) setStop(Number(next.dataset.index ?? 0));
        }}
      >
        {items.map((item, i) => {
          const pressed = item.id === active;
          return (
            <button
              key={item.id}
              type="button"
              id={`${base}-item-${idSafe(item.id)}`}
              className="nx-dock-panels-item"
              data-index={i}
              aria-label={item.label}
              aria-expanded={pressed}
              aria-controls={`${base}-panel-${idSafe(item.id)}`}
              tabIndex={i === tabStop ? 0 : -1}
              onFocus={() => setStop(i)}
              onClick={() => setActive(pressed ? null : item.id)}
            >
              {typeof item.icon === 'string' ? <Icon name={item.icon as IconName} /> : item.icon}
              <span className="nx-dock-panels-tip" aria-hidden="true">
                {item.label}
              </span>
            </button>
          );
        })}
      </div>
      <div ref={shell} className="nx-dock-panels-shell">
        <div ref={measure} className="nx-dock-panels-measure">
          <div className="nx-dock-panels-view">
            {items.map((item) => {
              const shown = item.id === active;
              return (
                <section
                  key={item.id}
                  id={`${base}-panel-${idSafe(item.id)}`}
                  className="nx-dock-panels-content"
                  role="region"
                  aria-labelledby={`${base}-item-${idSafe(item.id)}`}
                  aria-hidden={shown ? undefined : true}
                  data-active={shown ? '' : undefined}
                >
                  {item.content}
                </section>
              );
            })}
          </div>
          <div className="nx-dock-panels-spacer" />
        </div>
      </div>
    </div>
  );
}

/* ============================================================================
 * MorphMenu — a filter button whose own container becomes the menu.
 * ========================================================================== */

export interface MorphMenuOption {
  value: string;
  label: string;
  icon?: IconName;
  disabled?: boolean;
}

export interface MorphMenuProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'defaultValue' | 'onChange'> {
  options: MorphMenuOption[];
  value?: string[];
  defaultValue?: string[];
  onValueChange?: (value: string[]) => void;
  /** Trigger text (defaults to "Filter"). */
  label?: string;
  icon?: IconName;
  /** Form field name: each checked option posts as `name[]`. */
  name?: string;
  /** Grow downward (default) or upward from the trigger. */
  side?: 'bottom' | 'top';
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  labels?: Words;
}

export function MorphMenu({
  options,
  value,
  defaultValue = [],
  onValueChange,
  label,
  icon = 'sliders',
  name,
  side = 'bottom',
  open,
  defaultOpen = false,
  onOpenChange,
  labels,
  className,
  ...rest
}: MorphMenuProps) {
  const base = useBaseId('nx-morph');
  const word = useWords(labels);
  const locale = useLocale();
  const [selected, setSelected] = useControllable(value, defaultValue, onValueChange);
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const root = useRef<HTMLDivElement>(null);
  const shell = useRef<HTMLDivElement>(null);
  const measure = useRef<HTMLDivElement>(null);
  const trigger = useRef<HTMLButtonElement>(null);
  const list = useRef<HTMLDivElement>(null);
  const focusFirst = useRef(false);
  // The root keeps the trigger's footprint; the shell follows everything inside.
  useMorphShell(root, trigger);
  useMorphShell(shell, measure);

  const focusOption = (where: 'first' | 'last') => {
    const inputs = list.current ? focusableItems(list.current, '.nx-morph-menu-input') : [];
    (where === 'first' ? inputs[0] : inputs[inputs.length - 1])?.focus({ preventScroll: true });
  };

  useEffect(() => {
    if (!isOpen || !root.current) return;
    if (focusFirst.current) {
      focusFirst.current = false;
      // The list becomes visible with this render; focus once it is.
      requestAnimationFrame(() => focusOption(side === 'top' ? 'last' : 'first'));
    }
    return lightDismiss(
      root.current,
      (reason) => {
        setOpen(false);
        if (reason === 'escape') trigger.current?.focus();
      },
      { focusOut: true },
    );
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isOpen]);

  const count = selected.length;
  const text = label ?? word('filter');

  const onListKey = (event: KeyboardEvent<HTMLDivElement>) => {
    const target = event.target as HTMLElement;
    const inputs = focusableItems(event.currentTarget, '.nx-morph-menu-input');
    const index = inputs.indexOf(target);
    const towardTrigger = side === 'top' ? 'ArrowDown' : 'ArrowUp';
    if (event.key === towardTrigger && index === (side === 'top' ? inputs.length - 1 : 0)) {
      event.preventDefault();
      trigger.current?.focus();
      return;
    }
    if (event.key === 'Enter' && target.matches('.nx-morph-menu-input')) {
      event.preventDefault();
      target.click();
      return;
    }
    roveFocus(event.nativeEvent, event.currentTarget, '.nx-morph-menu-input', { orientation: 'vertical', loop: false });
  };

  return (
    <div ref={root} className={cx('nx-morph-menu', className)} data-state={isOpen ? 'open' : 'closed'} data-side={side === 'top' ? 'top' : undefined} {...rest}>
      <div ref={shell} className="nx-morph-menu-shell">
        <div ref={measure} className="nx-morph-menu-measure">
          <button
            ref={trigger}
            type="button"
            className="nx-morph-menu-trigger"
            aria-expanded={isOpen}
            aria-controls={`${base}-list`}
            onClick={() => setOpen(!isOpen)}
            onKeyDown={(event) => {
              const into = side === 'top' ? 'ArrowUp' : 'ArrowDown';
              if (event.key !== into) return;
              event.preventDefault();
              if (isOpen) focusOption(side === 'top' ? 'last' : 'first');
              else {
                focusFirst.current = true;
                setOpen(true);
              }
            }}
          >
            <Icon name={icon} />
            <span>{text}</span>
            <span className="nx-morph-menu-count" data-active={count ? '' : undefined} aria-hidden={count ? undefined : true}>
              <span>
                <span className="nx-morph-menu-badge">
                  <NumberTicker value={count} reveal={false} locale={INTL_LOCALES[locale]} />
                </span>
              </span>
            </span>
            <span className="nx-morph-menu-chevron" aria-hidden="true">
              <Icon name="chevron-down" />
            </span>
          </button>
          <div ref={list} id={`${base}-list`} className="nx-morph-menu-list" role="group" aria-label={text} onKeyDown={onListKey}>
            {options.map((option, i) => (
              <label key={option.value} className="nx-morph-menu-option" style={{ '--nx-i': i } as CSSProperties}>
                <input
                  className="nx-morph-menu-input"
                  type="checkbox"
                  name={name ? `${name}[]` : undefined}
                  value={option.value}
                  checked={selected.includes(option.value)}
                  disabled={option.disabled}
                  onChange={() => setSelected(toggleSelection(selected, option.value))}
                />
                {option.icon && <Icon name={option.icon} />}
                <span>{option.label}</span>
                <span className="nx-morph-menu-check" aria-hidden="true">
                  <svg viewBox="0 0 24 24">
                    <path d="M5 12.5l4.5 4.5L19 7.5" pathLength={1} />
                  </svg>
                </span>
              </label>
            ))}
          </div>
          <div className="nx-morph-menu-footer">
            <span aria-live="polite">{count ? word('selected', { count: new Intl.NumberFormat(INTL_LOCALES[locale]).format(count) }) : ''}</span>
            <button type="button" className="nx-morph-menu-clear" disabled={!count} onClick={() => setSelected([])}>
              {word('clear')}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ============================================================================
 * StackedAccordion — accordion items as a deck of stacked cards.
 * ========================================================================== */

export interface StackedAccordionItem {
  id: string;
  title: ReactNode;
  subtitle?: ReactNode;
  icon?: IconName;
  content: ReactNode;
  disabled?: boolean;
}

export interface StackedAccordionProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'defaultValue'> {
  items: StackedAccordionItem[];
  /** One card open at a time, or any number. */
  type?: 'single' | 'multiple';
  /** Ids of the open cards. */
  value?: string[];
  defaultValue?: string[];
  onValueChange?: (value: string[]) => void;
  /** In single mode, whether the open card can be closed again (default true). */
  collapsible?: boolean;
  headingLevel?: 2 | 3 | 4 | 5 | 6;
}

export function StackedAccordion({
  items,
  type = 'single',
  value,
  defaultValue = [],
  onValueChange,
  collapsible = true,
  headingLevel = 3,
  className,
  style,
  ...rest
}: StackedAccordionProps) {
  const base = useBaseId('nx-sacc');
  const [open, setOpen] = useControllable(value, defaultValue, onValueChange);
  const Heading = `h${headingLevel}` as 'h3';

  const toggle = (id: string) => {
    if (type === 'multiple') setOpen(toggleSelection(open, id));
    else if (open.includes(id)) {
      if (collapsible) setOpen([]);
    } else setOpen([id]);
  };

  return (
    <div
      className={cx('nx-stacked-accordion', className)}
      data-open={open.length ? '' : undefined}
      style={{ '--_n': items.length, ...style } as CSSProperties}
      onKeyDown={(event) => {
        if ((event.target as HTMLElement).matches('.nx-stacked-accordion-trigger')) roveFocus(event.nativeEvent, event.currentTarget, '.nx-stacked-accordion-trigger', { orientation: 'vertical', loop: false });
      }}
      {...rest}
    >
      {items.map((item, i) => {
        const expanded = open.includes(item.id);
        const key = idSafe(item.id);
        return (
          <div key={item.id} className="nx-stacked-accordion-item" data-state={expanded ? 'open' : 'closed'} style={{ '--_i': i } as CSSProperties}>
            <Heading className="nx-stacked-accordion-heading">
              <button
                type="button"
                id={`${base}-trigger-${key}`}
                className="nx-stacked-accordion-trigger"
                aria-expanded={expanded}
                aria-controls={`${base}-panel-${key}`}
                disabled={item.disabled}
                onClick={() => toggle(item.id)}
              >
                {item.icon && (
                  <span className="nx-stacked-accordion-icon" aria-hidden="true">
                    <Icon name={item.icon} />
                  </span>
                )}
                <span className="nx-stacked-accordion-text">
                  <span>{item.title}</span>
                  {item.subtitle && <span className="nx-stacked-accordion-subtitle">{item.subtitle}</span>}
                </span>
                <span className="nx-stacked-accordion-chevron" aria-hidden="true">
                  <Icon name="chevron-down" />
                </span>
              </button>
            </Heading>
            <div id={`${base}-panel-${key}`} className="nx-stacked-accordion-panel" role="region" aria-labelledby={`${base}-trigger-${key}`}>
              <div className="nx-stacked-accordion-inner">
                <div className="nx-stacked-accordion-body">{item.content}</div>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}

/* ============================================================================
 * StackMenu — nested levels pushed and popped in a popover, searchable.
 * ========================================================================== */

export interface StackMenuItem {
  id: string;
  label: string;
  icon?: IconName;
  shortcut?: string;
  description?: string;
  keywords?: string[];
  tone?: 'danger';
  disabled?: boolean;
  href?: string;
  onSelect?: () => void;
  children?: StackMenuItem[];
}

export interface StackMenuProps {
  items: StackMenuItem[];
  /** A <button> (e.g. <Button>) that opens the menu. */
  trigger: ReactElement;
  /** Title of the first level (defaults to "Menu"). */
  title?: string;
  placeholder?: string;
  emptyText?: ReactNode;
  side?: 'bottom' | 'top';
  align?: Align;
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  className?: string;
  'aria-label'?: string;
}

type Level = { key: string; title: string; items: StackMenuItem[]; depth: number; state: 'active' | 'behind' | 'leaving' };

export function StackMenu({ items, trigger, title, placeholder, emptyText, side = 'bottom', align = 'start', open, defaultOpen = false, onOpenChange, className, 'aria-label': label }: StackMenuProps) {
  const t = useT();
  const id = useBaseId('nx-stack');
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const [path, setPath] = useState<string[]>([]);
  const [leaving, setLeaving] = useState<string[] | null>(null);
  const [query, setQuery] = useState('');
  const panel = useRef<HTMLDivElement>(null);
  const button = useRef<HTMLElement | null>(null);
  const viewport = useRef<HTMLDivElement>(null);
  const measure = useRef<HTMLDivElement>(null);
  const input = useRef<HTMLInputElement>(null);
  const focusTarget = useRef<string | null>(null);
  const { onTriggerClick } = usePopover({ id, open: isOpen, setOpen, trigger: button, panel, side, align, offset: 8 });
  useMorphShell(viewport, measure, 'block');

  const rootTitle = title ?? t('menu');
  const searching = query.trim() !== '';
  const results = useMemo(() => searchMenuTree(items, query), [items, query]);

  // Every opening starts at the top level, searching from the field.
  useIsoLayoutEffect(() => {
    if (!isOpen) return;
    setPath([]);
    setLeaving(null);
    setQuery('');
    requestAnimationFrame(() => input.current?.focus({ preventScroll: true }));
  }, [isOpen]);

  useEffect(() => {
    if (!leaving) return;
    const timer = setTimeout(() => setLeaving(null), 450);
    return () => clearTimeout(timer);
  }, [leaving]);

  // Move focus once the level it belongs to is on screen.
  useEffect(() => {
    const target = focusTarget.current;
    const view = panel.current?.querySelector<HTMLElement>('[data-view]');
    if (!target || !view) return;
    focusTarget.current = null;
    const el = target === 'first' ? focusableItems(view, '[role="menuitem"]')[0] : view.querySelector<HTMLElement>(`[data-item="${CSS.escape(target)}"]`);
    el?.focus({ preventScroll: true });
  });

  const chain = menuPath(items, path);
  const levels: Level[] = [
    { key: '__root', title: rootTitle, items, depth: 0, state: path.length ? 'behind' : 'active' },
    ...chain.map((node, i): Level => ({ key: node.id, title: node.label, items: node.children ?? [], depth: i + 1, state: i === chain.length - 1 ? 'active' : 'behind' })),
  ];
  if (leaving) {
    const gone = menuPath(items, leaving);
    const node = gone[gone.length - 1];
    if (node && !levels.some((level) => level.key === node.id)) levels.push({ key: node.id, title: node.label, items: node.children ?? [], depth: leaving.length, state: 'leaving' });
  }

  const push = (next: string[]) => {
    if (leaving && leaving.join('/') === next.join('/')) setLeaving(null);
    setPath(next);
    focusTarget.current = 'first';
  };

  const pop = () => {
    if (!path.length) return;
    setLeaving(path);
    setPath(path.slice(0, -1));
    focusTarget.current = path[path.length - 1]!;
  };

  const close = () => {
    setOpen(false);
    button.current?.focus();
  };

  const select = (item: StackMenuItem, trail: StackMenuItem[] = chain) => {
    if (item.disabled) return;
    if (item.children?.length) {
      setQuery('');
      push([...trail.map((node) => node.id), item.id]);
      return;
    }
    item.onSelect?.();
    close();
  };

  const onKey = (event: KeyboardEvent<HTMLDivElement>) => {
    const target = event.target as HTMLElement;
    const view = event.currentTarget.querySelector<HTMLElement>('[data-view]');
    const rtl = direction(event.currentTarget) === -1;
    const forward = rtl ? 'ArrowLeft' : 'ArrowRight';
    const backward = rtl ? 'ArrowRight' : 'ArrowLeft';

    if (event.key === 'Escape') {
      // Clear the search, then climb back up; only the top level lets the popover close.
      if (searching || path.length) {
        event.preventDefault();
        event.stopPropagation();
        if (searching) {
          setQuery('');
          input.current?.focus();
        } else pop();
      }
      return;
    }

    if (target === input.current) {
      if (event.key === 'ArrowDown' && view) {
        event.preventDefault();
        focusableItems(view, '[role="menuitem"]')[0]?.focus();
      } else if (event.key === 'Enter' && searching && results[0]) {
        event.preventDefault();
        select(results[0].item, results[0].trail);
      }
      return;
    }

    if (!view) return;
    if (event.key === backward && path.length && !searching) {
      event.preventDefault();
      pop();
      return;
    }
    if (event.key === forward && target.getAttribute('aria-haspopup') === 'menu') {
      event.preventDefault();
      target.click();
      return;
    }
    if (event.key === 'ArrowUp' && focusableItems(view, '[role="menuitem"]')[0] === target) {
      event.preventDefault();
      input.current?.focus();
      return;
    }
    roveFocus(event.nativeEvent, view, '[role="menuitem"]', { orientation: 'vertical', loop: false });
  };

  const renderItem = (item: StackMenuItem, index: number, live: boolean, trail?: StackMenuItem[]) => {
    const branch = !!item.children?.length;
    const body = (
      <>
        {item.icon && <Icon name={item.icon} />}
        <span className="nx-stack-menu-label">
          <span>{item.label}</span>
          {trail?.length ? (
            <span className="nx-stack-menu-trail">
              {trail.map((node) => (
                <span key={node.id}>{node.label}</span>
              ))}
            </span>
          ) : item.description ? (
            <span className="nx-stack-menu-hint">{item.description}</span>
          ) : null}
        </span>
        {item.shortcut && <Kbd>{item.shortcut}</Kbd>}
        {branch && (
          <span className="nx-stack-menu-more" aria-hidden="true">
            <Icon name="chevron-right" />
          </span>
        )}
      </>
    );
    const common = {
      role: 'menuitem',
      className: 'nx-stack-menu-item',
      'data-item': item.id,
      'data-tone': item.tone,
      'aria-haspopup': branch ? ('menu' as const) : undefined,
      'aria-disabled': item.disabled || undefined,
      tabIndex: live && index === 0 ? 0 : -1,
    };
    return item.href && !branch && !item.disabled ? (
      <SmartLink
        key={item.id}
        href={item.href}
        {...common}
        onClick={() => {
          item.onSelect?.();
          setOpen(false);
        }}
      >
        {body}
      </SmartLink>
    ) : (
      <button key={item.id} type="button" {...common} onClick={() => select(item, trail)}>
        {body}
      </button>
    );
  };

  const child = Children.only(trigger);
  if (!isValidElement(child)) return null;
  const childProps = child.props as Record<string, unknown>;

  return (
    <>
      {cloneElement(child as ReactElement<Record<string, unknown>>, {
        ref: mergeRefs(childRef(child), button),
        'aria-haspopup': 'dialog',
        'aria-expanded': isOpen,
        'aria-controls': id,
        onClick: (event: React.MouseEvent) => {
          (childProps.onClick as ((e: React.MouseEvent) => void) | undefined)?.(event);
          onTriggerClick();
        },
      })}
      <div ref={panel} id={id} className={cx('nx-stack-menu', className)} role="dialog" aria-label={label ?? rootTitle} {...{ popover: 'auto' }} onKeyDown={onKey}>
        <div className="nx-stack-menu-search">
          <Icon name="search" />
          <input
            ref={input}
            type="search"
            className="nx-stack-menu-input"
            placeholder={placeholder ?? `${t('search')}…`}
            aria-label={t('search')}
            value={query}
            onChange={(event) => setQuery(event.target.value)}
          />
        </div>
        <div ref={viewport} className="nx-stack-menu-viewport">
          <div ref={measure} className="nx-stack-menu-measure">
            {searching ? (
              <div className="nx-stack-menu-results" role="menu" aria-label={t('search')} data-view="">
                {results.length ? results.map(({ item, trail }, i) => renderItem(item, i, true, trail)) : <p className="nx-stack-menu-empty">{emptyText ?? t('noResults')}</p>}
              </div>
            ) : (
              levels.map((level) => {
                const live = level.state === 'active';
                return (
                  <section
                    key={level.key}
                    className="nx-stack-menu-level"
                    data-state={level.state}
                    data-root={level.depth === 0 ? '' : undefined}
                    data-view={live ? '' : undefined}
                    aria-hidden={live ? undefined : true}
                    style={{ '--_depth': level.depth } as CSSProperties}
                    onTransitionEnd={
                      level.state === 'leaving'
                        ? (event) => {
                            if (event.target === event.currentTarget && event.propertyName === 'translate') setLeaving(null);
                          }
                        : undefined
                    }
                  >
                    {level.depth > 0 && (
                      <button type="button" className="nx-stack-menu-back" tabIndex={live ? 0 : -1} aria-label={`${t('back')}, ${level.title}`} onClick={pop}>
                        <Icon name="chevron-left" />
                        <span>{level.title}</span>
                      </button>
                    )}
                    <div className="nx-stack-menu-list" role="menu" aria-label={level.title}>
                      {level.items.map((item, i) => renderItem(item, i, live))}
                    </div>
                  </section>
                );
              })
            )}
          </div>
        </div>
      </div>
    </>
  );
}

/* ============================================================================
 * MorphTabs — icon tabs; the active one opens to show its label.
 * ========================================================================== */

export interface MorphTabItem {
  value: string;
  label: string;
  icon: IconName | ReactNode;
  /** Optional panel content. */
  content?: ReactNode;
  disabled?: boolean;
}

export interface MorphTabsProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'defaultValue'> {
  items: MorphTabItem[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  /** Where the strip sits in its row. */
  justify?: 'start' | 'center' | 'end';
}

export function MorphTabs({ items, value, defaultValue, onValueChange, justify = 'start', 'aria-label': label, className, style, ...rest }: MorphTabsProps) {
  const base = useBaseId('nx-mtabs');
  const [current, setCurrent] = useControllable(value, defaultValue ?? items[0]?.value ?? '', onValueChange);
  const rail = useRef<HTMLDivElement>(null);
  const ctrl = useRef<MorphTabsController | null>(null);

  useIsoLayoutEffect(() => {
    if (!rail.current) return;
    ctrl.current = morphTabs(rail.current);
    return () => {
      ctrl.current?.destroy();
      ctrl.current = null;
    };
  }, []);

  useIsoLayoutEffect(() => {
    ctrl.current?.select(rail.current?.querySelector<HTMLElement>(`[data-value="${CSS.escape(current)}"]`) ?? null);
  }, [current, items]);

  const panels = items.some((item) => item.content !== undefined);

  return (
    <div className={cx('nx-morph-tabs', className)} style={{ '--nx-morph-tabs-justify': justify === 'start' ? 'flex-start' : justify === 'end' ? 'flex-end' : 'center', ...style } as CSSProperties} {...rest}>
      <div ref={rail} className="nx-morph-tabs-rail">
        <span className="nx-indicator nx-morph-tabs-pill" aria-hidden="true" />
        <div
          className="nx-morph-tabs-list"
          role="tablist"
          aria-label={label}
          onKeyDown={(event) => {
            const next = roveFocus(event.nativeEvent, event.currentTarget, '[role="tab"]');
            // Automatic activation: moving focus selects the tab.
            if (next?.dataset.value) setCurrent(next.dataset.value);
          }}
        >
          {items.map((item) => {
            const selected = item.value === current;
            return (
              <button
                key={item.value}
                type="button"
                role="tab"
                id={`${base}-tab-${idSafe(item.value)}`}
                className="nx-morph-tab"
                data-value={item.value}
                aria-selected={selected}
                aria-controls={panels ? `${base}-panel-${idSafe(item.value)}` : undefined}
                tabIndex={selected ? 0 : -1}
                disabled={item.disabled}
                onClick={() => setCurrent(item.value)}
              >
                {typeof item.icon === 'string' ? <Icon name={item.icon as IconName} /> : item.icon}
                <span className="nx-morph-tab-label" data-nx-tab-label="">
                  <span>{item.label}</span>
                </span>
                <span className="nx-morph-tab-tip" aria-hidden="true">
                  {item.label}
                </span>
              </button>
            );
          })}
        </div>
      </div>
      {panels &&
        items.map((item) => (
          <div
            key={item.value}
            id={`${base}-panel-${idSafe(item.value)}`}
            className="nx-morph-tabs-panel"
            role="tabpanel"
            aria-labelledby={`${base}-tab-${idSafe(item.value)}`}
            tabIndex={0}
            hidden={item.value !== current}
          >
            {item.content}
          </div>
        ))}
    </div>
  );
}

/* ============================================================================
 * AudioRoom — a live pill that opens like a dynamic island.
 * ========================================================================== */

export interface AudioRoomMember {
  name: string;
  avatar?: string;
  speaking?: boolean;
  muted?: boolean;
  /** A small caption under the name ("Host"). */
  role?: string;
}

export interface AudioRoomProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'title'> {
  title: string;
  /** The people on stage. */
  members: AudioRoomMember[];
  /** How many are listening. */
  listeners?: number;
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  muted?: boolean;
  defaultMuted?: boolean;
  onMutedChange?: (muted: boolean) => void;
  handRaised?: boolean;
  defaultHandRaised?: boolean;
  onHandRaisedChange?: (raised: boolean) => void;
  onLeave?: () => void;
  /** An Intl locale for the listener count (defaults to the provider's language). */
  locale?: string;
  labels?: Words;
}

export function AudioRoom({
  title,
  members,
  listeners = 0,
  open,
  defaultOpen = false,
  onOpenChange,
  muted,
  defaultMuted = false,
  onMutedChange,
  handRaised,
  defaultHandRaised = false,
  onHandRaisedChange,
  onLeave,
  locale,
  labels,
  className,
  ...rest
}: AudioRoomProps) {
  const base = useBaseId('nx-room');
  const word = useWords(labels);
  const language = useLocale();
  const intl = locale ?? INTL_LOCALES[language];
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const [isMuted, setMuted] = useControllable(muted, defaultMuted, onMutedChange);
  const [raised, setRaised] = useControllable(handRaised, defaultHandRaised, onHandRaisedChange);
  const root = useRef<HTMLDivElement>(null);
  const shell = useRef<HTMLDivElement>(null);
  const measure = useRef<HTMLDivElement>(null);
  const pill = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLElement>(null);
  useMorphShell(root, pill);
  useMorphShell(shell, measure);

  const speaking = members.some((member) => member.speaking);

  useEffect(() => {
    if (!isOpen || !root.current) return;
    requestAnimationFrame(() => panel.current?.querySelector<HTMLElement>('.nx-audio-room-close')?.focus({ preventScroll: true }));
    return lightDismiss(root.current, (reason) => {
      setOpen(false);
      if (reason !== 'outside') pill.current?.focus();
    });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isOpen]);

  const collapse = () => {
    setOpen(false);
    pill.current?.focus();
  };

  return (
    <div ref={root} className={cx('nx-audio-room', className)} data-state={isOpen ? 'open' : 'closed'} {...rest}>
      <div ref={shell} className="nx-audio-room-shell">
        <div ref={measure} className="nx-audio-room-measure">
          <button ref={pill} type="button" className="nx-audio-room-pill" aria-expanded={isOpen} aria-controls={`${base}-panel`} tabIndex={isOpen ? -1 : 0} onClick={() => setOpen(true)}>
            <span className="nx-audio-room-live" aria-hidden="true" />
            <span className="nx-visually-hidden">{word('live')}:</span>
            <span className="nx-audio-room-title">{title}</span>
            <span className="nx-audio-room-faces" aria-hidden="true">
              {members.slice(0, 3).map((member) => (
                <Avatar key={member.name} name={member.name} src={member.avatar} size="xs" />
              ))}
            </span>
            <span className="nx-audio-room-eq" data-active={speaking ? '' : undefined} aria-hidden="true">
              <i />
              <i />
              <i />
              <i />
            </span>
          </button>
          <section ref={panel} id={`${base}-panel`} className="nx-audio-room-panel" role="region" aria-label={title} aria-hidden={isOpen ? undefined : true}>
            <div className="nx-audio-room-head">
              <div className="nx-audio-room-heading">
                <p className="nx-audio-room-name">{title}</p>
                <div className="nx-audio-room-meta">
                  <span className="nx-audio-room-badge">
                    <span className="nx-audio-room-live" aria-hidden="true" />
                    {word('live')}
                  </span>
                  <span className="nx-audio-room-count">
                    <NumberTicker value={listeners} reveal={false} locale={intl} />
                    <span>{word('listening')}</span>
                  </span>
                </div>
              </div>
              <button type="button" className="nx-audio-room-close" aria-label={word('minimize')} tabIndex={isOpen ? 0 : -1} onClick={collapse}>
                <Icon name="chevron-up" />
              </button>
            </div>
            <ul className="nx-audio-room-speakers" aria-label={word('speakers')}>
              {members.map((member, i) => (
                <li key={member.name} className="nx-audio-room-speaker" data-speaking={member.speaking ? '' : undefined} style={{ '--nx-i': i } as CSSProperties}>
                  <span className="nx-audio-room-avatar">
                    <Avatar name={member.name} src={member.avatar} />
                    {member.muted && (
                      <span className="nx-audio-room-mic" role="img" aria-label={word('muted')}>
                        <Glyph name="mic-off" />
                      </span>
                    )}
                  </span>
                  <span className="nx-audio-room-person">{member.name}</span>
                  {member.role && <span className="nx-audio-room-role">{member.role}</span>}
                  {member.speaking && <span className="nx-visually-hidden">{word('speaking')}</span>}
                </li>
              ))}
            </ul>
            <div className="nx-audio-room-controls">
              <button type="button" className="nx-audio-room-control" aria-pressed={isMuted} tabIndex={isOpen ? 0 : -1} onClick={() => setMuted(!isMuted)}>
                <span className="nx-audio-room-swap" aria-hidden="true">
                  <Icon name="mic" />
                  <Glyph name="mic-off" />
                </span>
                <span>{isMuted ? word('unmute') : word('mute')}</span>
              </button>
              <button type="button" className="nx-audio-room-control" aria-pressed={raised} tabIndex={isOpen ? 0 : -1} onClick={() => setRaised(!raised)}>
                <span className="nx-audio-room-swap" aria-hidden="true">
                  <Glyph name="hand" />
                  <Glyph name="hand-raised" />
                </span>
                <span>{raised ? word('lowerHand') : word('raiseHand')}</span>
              </button>
              <button type="button" className="nx-audio-room-control" data-tone="danger" tabIndex={isOpen ? 0 : -1} onClick={() => onLeave?.()}>
                <Glyph name="leave" />
                <span>{word('leave')}</span>
              </button>
            </div>
          </section>
        </div>
      </div>
    </div>
  );
}

/* ============================================================================
 * ActivityDropdown — a bell, an unread count and a popover of activity.
 * ========================================================================== */

export interface ActivityItem {
  id: string;
  actor: { name: string; avatar?: string };
  /** What happened: "commented on", or any rich node. */
  text: ReactNode;
  /** What it happened to, shown in bold after the text. */
  target?: ReactNode;
  /** A Date, timestamp or ISO string (shown relative), or a label as it is. */
  time: Date | number | string;
  unread?: boolean;
  href?: string;
}

export interface ActivityDropdownProps {
  items: ActivityItem[];
  /** Panel title (defaults to "Notifications"). */
  title?: string;
  onMarkAllRead?: () => void;
  onItemSelect?: (item: ActivityItem) => void;
  emptyText?: ReactNode;
  /** An Intl locale for times and counts (defaults to the provider's language). */
  locale?: string;
  side?: 'bottom' | 'top';
  align?: Align;
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  labels?: Words;
  className?: string;
}

export function ActivityDropdown({
  items,
  title,
  onMarkAllRead,
  onItemSelect,
  emptyText,
  locale,
  side = 'bottom',
  align = 'end',
  open,
  defaultOpen = false,
  onOpenChange,
  labels,
  className,
}: ActivityDropdownProps) {
  const t = useT();
  const word = useWords(labels);
  const language = useLocale();
  const intl = locale ?? INTL_LOCALES[language];
  const id = useBaseId('nx-activity');
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const [read, setRead] = useState<ReadonlySet<string>>(() => new Set());
  const [now, setNow] = useState(() => Date.now());
  const trigger = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLDivElement>(null);
  const badge = useRef<HTMLSpanElement>(null);
  const previous = useRef<number | null>(null);
  const { onTriggerClick } = usePopover({ id, open: isOpen, setOpen, trigger, panel, side, align, offset: 10 });

  const unread = items.filter((item) => item.unread && !read.has(item.id)).length;
  const heading = title ?? t('notifications');

  // A new arrival pops the badge and rings the bell (one-shot keyframes, restarted).
  useEffect(() => {
    const before = previous.current;
    previous.current = unread;
    if (before === null || unread <= before) return;
    for (const [el, attr] of [
      [badge.current, 'data-pop'],
      [trigger.current, 'data-ring'],
    ] as const) {
      if (!el) continue;
      el.removeAttribute(attr);
      void el.offsetWidth;
      el.setAttribute(attr, '');
    }
  }, [unread]);

  // Relative times stay fresh while the panel is open.
  useEffect(() => {
    if (!isOpen) return;
    setNow(Date.now());
    const timer = setInterval(() => setNow(Date.now()), 30000);
    return () => clearInterval(timer);
  }, [isOpen]);

  const markAll = () => {
    setRead(new Set(items.map((item) => item.id)));
    onMarkAllRead?.();
  };

  return (
    <>
      <button
        ref={trigger}
        type="button"
        className={cx('nx-activity-dropdown-trigger', className)}
        aria-expanded={isOpen}
        aria-controls={id}
        aria-label={unread ? `${heading}, ${word('unread', { count: new Intl.NumberFormat(intl).format(unread) })}` : heading}
        onClick={onTriggerClick}
        onAnimationEnd={(event) => {
          if (event.animationName === 'nx-activity-dropdown-ring') event.currentTarget.removeAttribute('data-ring');
        }}
      >
        <Icon name="bell" />
        <span ref={badge} className="nx-activity-dropdown-badge" data-empty={unread ? undefined : ''} aria-hidden="true" onAnimationEnd={(event) => event.animationName === 'nx-pop' && event.currentTarget.removeAttribute('data-pop')}>
          <NumberTicker value={unread} reveal={false} locale={intl} />
        </span>
      </button>
      <div ref={panel} id={id} className="nx-activity-dropdown" role="dialog" aria-labelledby={`${id}-title`} {...{ popover: 'auto' }}>
        <header className="nx-activity-dropdown-head">
          <div>
            <h2 id={`${id}-title`} className="nx-activity-dropdown-title">
              {heading}
            </h2>
            {unread > 0 && <span className="nx-activity-dropdown-unread">{word('unread', { count: new Intl.NumberFormat(intl).format(unread) })}</span>}
          </div>
          <button type="button" className="nx-activity-dropdown-mark" disabled={!unread} onClick={markAll}>
            {word('markAllRead')}
          </button>
        </header>
        {items.length ? (
          <ul className="nx-activity-dropdown-list">
            {items.map((item, i) => {
              const isUnread = !!item.unread && !read.has(item.id);
              const moment = timeOf(item.time);
              const body = (
                <>
                  <Avatar name={item.actor.name} src={item.actor.avatar} />
                  <p className="nx-activity-dropdown-text">
                    <span>
                      <strong>{item.actor.name}</strong> {item.text}
                      {item.target !== undefined && (
                        <>
                          {' '}
                          <strong>{item.target}</strong>
                        </>
                      )}
                    </span>
                    <time className="nx-activity-dropdown-time" dateTime={Number.isNaN(moment) ? undefined : new Date(moment).toISOString()} suppressHydrationWarning>
                      {activityTime(item.time, now, intl)}
                    </time>
                  </p>
                  <span className="nx-activity-dropdown-dot" aria-hidden="true" />
                  {isUnread && <span className="nx-visually-hidden">{word('unread', { count: '' }).trim()}</span>}
                </>
              );
              const select = () => {
                setRead((ids) => new Set(ids).add(item.id));
                onItemSelect?.(item);
              };
              return (
                <li key={item.id} className="nx-activity-dropdown-item" data-unread={isUnread ? '' : undefined} style={{ '--nx-i': i } as CSSProperties}>
                  {item.href ? (
                    <SmartLink href={item.href} className="nx-activity-dropdown-row" onClick={select}>
                      {body}
                    </SmartLink>
                  ) : onItemSelect ? (
                    <button type="button" className="nx-activity-dropdown-row" onClick={select}>
                      {body}
                    </button>
                  ) : (
                    <div className="nx-activity-dropdown-row">{body}</div>
                  )}
                </li>
              );
            })}
          </ul>
        ) : (
          <p className="nx-activity-dropdown-empty">
            <Icon name="check-circle" />
            {emptyText ?? word('caughtUp')}
          </p>
        )}
      </div>
    </>
  );
}

/* ============================================================================
 * MemberSelector — an avatar stack and a searchable, role-aware member list.
 * ========================================================================== */

export interface Member {
  id: string;
  name: string;
  email?: string;
  avatar?: string;
}

export interface MemberRoleOption {
  value: string;
  label: string;
}

export interface MemberSelectorProps {
  members: Member[];
  /** Selected member ids, in the order they were added. */
  value?: string[];
  defaultValue?: string[];
  onValueChange?: (value: string[]) => void;
  /** Role per selected id. */
  roles?: Record<string, string>;
  defaultRoles?: Record<string, string>;
  onRolesChange?: (roles: Record<string, string>) => void;
  roleOptions?: MemberRoleOption[];
  /** The role a newly added member starts with (the first option by default). */
  defaultRole?: string;
  /** Avatars shown in the stack before "+N". */
  max?: number;
  /** Form names: `name[]` for the ids, `name_roles[id]` for the roles. */
  name?: string;
  label?: string;
  placeholder?: string;
  emptyText?: ReactNode;
  side?: 'bottom' | 'top';
  align?: Align;
  labels?: Words;
  className?: string;
}

export function MemberSelector({
  members,
  value,
  defaultValue = [],
  onValueChange,
  roles,
  defaultRoles = {},
  onRolesChange,
  roleOptions,
  defaultRole,
  max = 4,
  name,
  label,
  placeholder,
  emptyText,
  side = 'bottom',
  align = 'start',
  labels,
  className,
}: MemberSelectorProps) {
  const t = useT();
  const word = useWords(labels);
  const language = useLocale();
  const id = useBaseId('nx-members');
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useControllable(value, defaultValue, onValueChange);
  const [roleMap, setRoleMap] = useControllable(roles, defaultRoles, onRolesChange);
  const [query, setQuery] = useState('');
  const [fresh, setFresh] = useState<string | null>(null);
  const trigger = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLDivElement>(null);
  const stack = useRef<HTMLSpanElement>(null);
  const flip = useRef<ReturnType<typeof flipStack> | null>(null);
  const { onTriggerClick } = usePopover({ id, open, setOpen, trigger, panel, side, align, offset: 8 });

  const options = roleOptions ?? [
    { value: 'viewer', label: word('viewer') },
    { value: 'editor', label: word('editor') },
    { value: 'admin', label: word('admin') },
  ];
  const startRole = defaultRole ?? options[0]?.value ?? 'viewer';
  const byId = useMemo(() => new Map(members.map((member) => [member.id, member])), [members]);
  const chosen = selected.map((key) => byId.get(key)).filter((member): member is Member => !!member);
  const shown = chosen.length > max ? chosen.slice(0, max - 1) : chosen;
  const rest = chosen.length - shown.length;
  const q = foldSearchText(query.trim());

  // Those already in the stack glide over when one leaves or the overflow changes.
  useIsoLayoutEffect(() => {
    if (!stack.current) return;
    flip.current = flipStack(stack.current, { selector: '[data-key]' });
    return () => flip.current?.destroy();
  }, []);
  useIsoLayoutEffect(() => flip.current?.update(), [selected.join('|'), max]);

  useEffect(() => {
    if (open) requestAnimationFrame(() => panel.current?.querySelector<HTMLElement>('.nx-member-selector-input')?.focus({ preventScroll: true }));
    else setQuery('');
  }, [open]);

  const toggle = (member: Member) => {
    const adding = !selected.includes(member.id);
    setSelected(toggleSelection(selected, member.id));
    if (adding) {
      setFresh(member.id);
      if (!roleMap[member.id]) setRoleMap({ ...roleMap, [member.id]: startRole });
    }
  };

  const names = chosen.map((member) => member.name);
  const summary = names.length ? `${label ?? word('members')}: ${names.slice(0, 3).join(', ')}${names.length > 3 ? `, ${t('more', { count: names.length - 3 })}` : ''}` : word('addPeople');

  return (
    <>
      <button ref={trigger} type="button" className={cx('nx-member-selector-trigger', className)} aria-expanded={open} aria-controls={id} aria-label={summary} onClick={onTriggerClick}>
        <span ref={stack} className="nx-member-selector-stack" aria-hidden="true">
          {shown.map((member) => (
            <Avatar
              key={member.id}
              data-key={member.id}
              data-new={member.id === fresh ? '' : undefined}
              name={member.name}
              src={member.avatar}
              aria-hidden
              role={undefined}
              onAnimationEnd={() => setFresh((current) => (current === member.id ? null : current))}
            />
          ))}
          {rest > 0 && (
            // Keyed by the count: each change pops the chip in again.
            <span key={`more-${rest}`} className="nx-avatar nx-avatar-more" data-key="__more" data-new="">
              <span>
                +<RollingText text={String(rest)} locale={INTL_LOCALES[language]} />
              </span>
            </span>
          )}
          <span className="nx-member-selector-add" data-key="__add">
            <Icon name="plus" />
          </span>
        </span>
      </button>
      <div ref={panel} id={id} className="nx-member-selector" role="dialog" aria-label={label ?? word('members')} {...{ popover: 'auto' }}>
        <div className="nx-member-selector-search">
          <Icon name="search" />
          <input
            type="search"
            className="nx-member-selector-input"
            placeholder={placeholder ?? word('searchPeople')}
            aria-label={t('search')}
            aria-controls={`${id}-list`}
            value={query}
            onChange={(event) => setQuery(event.target.value)}
          />
        </div>
        <ul id={`${id}-list`} className="nx-member-selector-list" aria-label={label ?? word('members')}>
          {members.map((member) => {
            const checked = selected.includes(member.id);
            const visible = !q || foldSearchText(`${member.name} ${member.email ?? ''}`).includes(q);
            return (
              <li key={member.id} className="nx-member-selector-row" data-selected={checked ? '' : undefined} hidden={!visible}>
                <label className="nx-member-selector-choice">
                  <input type="checkbox" className="nx-checkbox" name={name ? `${name}[]` : undefined} value={member.id} checked={checked} onChange={() => toggle(member)} />
                  <Avatar name={member.name} src={member.avatar} aria-hidden role={undefined} />
                  <span className="nx-member-selector-who">
                    <span className="nx-member-selector-name">{member.name}</span>
                    {member.email && <span className="nx-member-selector-email">{member.email}</span>}
                  </span>
                </label>
                <select
                  className="nx-select nx-member-selector-role"
                  data-size="sm"
                  aria-label={word('roleFor', { name: member.name })}
                  name={name ? `${name}_roles[${member.id}]` : undefined}
                  disabled={!checked}
                  tabIndex={checked ? 0 : -1}
                  value={roleMap[member.id] ?? startRole}
                  onChange={(event) => setRoleMap({ ...roleMap, [member.id]: event.target.value })}
                >
                  {options.map((option) => (
                    <option key={option.value} value={option.value}>
                      {option.label}
                    </option>
                  ))}
                </select>
              </li>
            );
          })}
        </ul>
        {q && !members.some((member) => foldSearchText(`${member.name} ${member.email ?? ''}`).includes(q)) && <p className="nx-member-selector-empty">{emptyText ?? t('noResults')}</p>}
      </div>
    </>
  );
}

/* ============================================================================
 * RegistrationCard — details → tickets → confirm, then a celebration.
 * ========================================================================== */

export interface RegistrationTicket {
  id: string;
  label: string;
  description?: ReactNode;
  price: number;
  /** Most of this kind one person can take (default 10). */
  max?: number;
}

export interface RegistrationDetails {
  name: string;
  email: string;
  /** Quantity per ticket id. */
  tickets: Record<string, number>;
}

export interface RegistrationCardProps extends Omit<FormHTMLAttributes<HTMLFormElement>, 'onSubmit' | 'title'> {
  event: { title: ReactNode; date?: ReactNode; location?: ReactNode; badge?: ReactNode };
  tickets: RegistrationTicket[];
  /** ISO 4217 code for prices (default USD). */
  currency?: string;
  /** An Intl locale for prices and counts (defaults to the provider's language). */
  locale?: string;
  defaultValues?: Partial<RegistrationDetails>;
  /** Called on the last step; a returned promise keeps the button busy until it settles. */
  onSubmit?: (details: RegistrationDetails) => unknown;
  labels?: Words;
}

export function RegistrationCard({ event, tickets, currency = 'USD', locale, defaultValues, onSubmit, labels, className, ...rest }: RegistrationCardProps) {
  const t = useT();
  const word = useWords(labels);
  const language = useLocale();
  const intl = locale ?? INTL_LOCALES[language];
  const base = useBaseId('nx-reg');
  const [step, setStep] = useState(0);
  const [name, setName] = useState(defaultValues?.name ?? '');
  const [email, setEmail] = useState(defaultValues?.email ?? '');
  const [quantities, setQuantities] = useState<Record<string, number>>(defaultValues?.tickets ?? {});
  const [status, setStatus] = useState<'idle' | 'loading' | 'error'>('idle');
  const [needTicket, setNeedTicket] = useState(false);
  const form = useRef<HTMLFormElement>(null);
  const viewport = useRef<HTMLDivElement>(null);
  const measure = useRef<HTMLDivElement>(null);
  const check = useRef<HTMLSpanElement>(null);
  const moved = useRef(false);
  useMorphShell(viewport, measure, 'block');

  const titles = [word('details'), word('tickets'), word('confirm')];
  const done = step === titles.length;
  const count = Object.values(quantities).reduce((sum, qty) => sum + (Number(qty) || 0), 0);
  const total = ticketTotal(tickets, quantities);
  const money = (amount: number) => (amount === 0 ? word('free') : new Intl.NumberFormat(intl, { style: 'currency', currency }).format(amount));
  const digits = localeDigits(intl);

  // Focus follows the step: its first field, or the confirmation.
  useEffect(() => {
    if (!moved.current) return;
    moved.current = false;
    const current = form.current?.querySelector<HTMLElement>('[data-pos="current"]');
    const target = done ? current?.querySelector<HTMLElement>('[tabindex="-1"]') : current?.querySelector<HTMLElement>('input:not([type="hidden"]), button:not(:disabled), select');
    target?.focus({ preventScroll: true });
  }, [step, done]);

  // The check draws itself, then the confetti.
  useEffect(() => {
    if (!done) return;
    const timer = setTimeout(() => check.current && burst(check.current, { count: 16, colors: ['var(--nx-success)', 'var(--nx-gold)', 'var(--nx-accent)', 'var(--nx-glow)'] }), 420);
    return () => clearTimeout(timer);
  }, [done]);

  const go = (next: number) => {
    moved.current = true;
    setStep(next);
  };

  const valid = () => {
    const fields = form.current ? Array.from(form.current.querySelectorAll<HTMLInputElement>(`[data-step="${step}"] input:not([type="hidden"])`)) : [];
    for (const field of fields) {
      if (!field.checkValidity()) {
        field.reportValidity();
        field.focus();
        return false;
      }
    }
    if (step === 1 && count === 0) {
      setNeedTicket(true);
      return false;
    }
    return true;
  };

  const submit = async (formEvent: FormEvent<HTMLFormElement>) => {
    formEvent.preventDefault();
    if (done || status === 'loading') return;
    if (!valid()) return;
    if (step < titles.length - 1) {
      go(step + 1);
      return;
    }
    setStatus('loading');
    try {
      await onSubmit?.({ name, email, tickets: quantities });
      setStatus('idle');
      go(titles.length);
    } catch {
      setStatus('error');
    }
  };

  const setQty = (ticket: RegistrationTicket, next: number) => {
    setNeedTicket(false);
    setQuantities({ ...quantities, [ticket.id]: Math.max(0, Math.min(ticket.max ?? 10, next)) });
  };

  const pos = (index: number) => (index < step ? 'before' : index === step ? 'current' : 'after');

  return (
    <form ref={form} className={cx('nx-registration-card', className)} data-step={step} data-done={done ? '' : undefined} noValidate aria-labelledby={`${base}-title`} onSubmit={submit} {...rest}>
      <header className="nx-registration-card-head">
        <div className="nx-registration-card-eyebrow">
          {event.badge}
          {event.date && (
            <span>
              <Glyph name="calendar" />
              {event.date}
            </span>
          )}
          {event.location && (
            <span>
              <Glyph name="pin" />
              {event.location}
            </span>
          )}
        </div>
        <h2 id={`${base}-title`} className="nx-registration-card-title">
          {event.title}
        </h2>
      </header>
      <ol className="nx-registration-card-steps" style={{ '--_progress': Math.min(step, titles.length - 1) / (titles.length - 1), '--_count': titles.length } as CSSProperties}>
        {titles.map((stepTitle, i) => {
          const state = done || i < step ? 'complete' : i === step ? 'current' : 'upcoming';
          return (
            <li key={stepTitle} className="nx-registration-card-stepmark" data-status={state} aria-current={state === 'current' ? 'step' : undefined}>
              <span className="nx-registration-card-marker" aria-hidden="true">
                <span>{digits[i + 1] ?? i + 1}</span>
                <Icon name="check" />
              </span>
              <span>{stepTitle}</span>
            </li>
          );
        })}
      </ol>
      <p className="nx-visually-hidden" aria-live="polite">
        {done ? word('registered') : `${word('stepOf', { step: step + 1, total: titles.length })}: ${titles[step]}`}
      </p>
      <div ref={viewport} className="nx-registration-card-viewport">
        <div ref={measure} className="nx-registration-card-measure">
          <fieldset className="nx-registration-card-step" data-step={0} data-pos={pos(0)} disabled={step !== 0}>
            <legend className="nx-registration-card-legend">{titles[0]}</legend>
            <Field label={word('fullName')} required>
              <Input name="name" autoComplete="name" required value={name} onChange={(e) => setName(e.target.value)} />
            </Field>
            <Field label={word('email')} required>
              <Input name="email" type="email" autoComplete="email" dir="ltr" required value={email} onChange={(e) => setEmail(e.target.value)} />
            </Field>
          </fieldset>
          <fieldset className="nx-registration-card-step" data-step={1} data-pos={pos(1)} disabled={step !== 1}>
            <legend className="nx-registration-card-legend">{titles[1]}</legend>
            <ul className="nx-registration-card-tickets">
              {tickets.map((ticket) => {
                const qty = quantities[ticket.id] ?? 0;
                return (
                  <li key={ticket.id} className="nx-registration-card-ticket" data-picked={qty ? '' : undefined}>
                    <span className="nx-registration-card-ticket-text">
                      <span className="nx-registration-card-ticket-name">{ticket.label}</span>
                      {ticket.description && <span className="nx-registration-card-ticket-note">{ticket.description}</span>}
                      <span className="nx-registration-card-price">{money(ticket.price)}</span>
                    </span>
                    <span className="nx-registration-card-stepper">
                      <button type="button" className="nx-registration-card-stepbtn" aria-label={word('removeOne', { name: ticket.label })} disabled={qty === 0} onClick={() => setQty(ticket, qty - 1)}>
                        <Icon name="minus" />
                      </button>
                      <output className="nx-registration-card-qty" aria-live="polite" aria-label={ticket.label}>
                        <NumberTicker value={qty} reveal={false} locale={intl} />
                      </output>
                      <button type="button" className="nx-registration-card-stepbtn" aria-label={word('addOne', { name: ticket.label })} disabled={qty >= (ticket.max ?? 10)} onClick={() => setQty(ticket, qty + 1)}>
                        <Icon name="plus" />
                      </button>
                    </span>
                    <input type="hidden" name={`tickets[${ticket.id}]`} value={qty} />
                  </li>
                );
              })}
            </ul>
            {needTicket && (
              <p className="nx-error" role="alert">
                <Icon name="alert-circle" />
                {word('chooseTicket')}
              </p>
            )}
          </fieldset>
          <fieldset className="nx-registration-card-step" data-step={2} data-pos={pos(2)} disabled={step !== 2}>
            <legend className="nx-registration-card-legend">{titles[2]}</legend>
            <dl className="nx-registration-card-summary">
              <div>
                <dt>{word('fullName')}</dt>
                <dd>{name}</dd>
              </div>
              <div>
                <dt>{word('email')}</dt>
                <dd dir="ltr">{email}</dd>
              </div>
              {tickets
                .filter((ticket) => (quantities[ticket.id] ?? 0) > 0)
                .map((ticket) => (
                  <div key={ticket.id}>
                    <dt>
                      {new Intl.NumberFormat(intl).format(quantities[ticket.id] ?? 0)} × {ticket.label}
                    </dt>
                    <dd>{money((quantities[ticket.id] ?? 0) * ticket.price)}</dd>
                  </div>
                ))}
              <div className="nx-registration-card-total">
                <dt>{word('total')}</dt>
                <dd>
                  <NumberTicker value={total} reveal={false} locale={intl} format={{ style: 'currency', currency }} />
                </dd>
              </div>
            </dl>
            {status === 'error' && (
              <p className="nx-error" role="alert">
                <Icon name="alert-circle" />
                {t('failed')}
              </p>
            )}
          </fieldset>
          <div className="nx-registration-card-success" data-pos={done ? 'current' : 'after'}>
            <span ref={check} className="nx-registration-card-check" aria-hidden="true">
              <svg viewBox="0 0 24 24">
                <path d="M5 12.5l4.5 4.5L19 7.5" pathLength={1} />
              </svg>
            </span>
            <h3 className="nx-registration-card-success-title" tabIndex={-1}>
              {word('registered')}
            </h3>
            <p>{word('registeredText', { email })}</p>
          </div>
        </div>
      </div>
      <footer className="nx-registration-card-foot">
        {step > 0 && !done && (
          <Button variant="ghost" icon="arrow-left" onClick={() => go(step - 1)} disabled={status === 'loading'}>
            {t('back')}
          </Button>
        )}
        <Button type="submit" variant="primary" iconEnd={step < titles.length - 1 ? 'arrow-right' : 'check'} status={status === 'loading' ? 'loading' : undefined}>
          {step < titles.length - 1 ? word('continue') : word('register')}
        </Button>
      </footer>
    </form>
  );
}

/* ============================================================================
 * VoiceRecorder — mic → recording pill → playback chip.
 * ========================================================================== */

export interface VoiceRecording {
  /** Seconds recorded. */
  duration: number;
  /** Every level sampled (0–1), for drawing the take. */
  levels: number[];
}

export interface VoiceRecorderProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  /** The live input level, 0–1 (drives the bars while recording). */
  level?: number;
  /** Or an AnalyserNode the bars read from. Without either, a believable simulation plays. */
  analyser?: AnalyserNode | null;
  /** The recorded audio, once the app has it: the chip plays it. */
  src?: string;
  /** Stop by itself after this many seconds. */
  maxDuration?: number;
  /** Grow from the inline start (default) or the inline end. */
  align?: 'start' | 'end';
  onStart?: () => void;
  onPause?: () => void;
  onResume?: () => void;
  onStop?: (recording: VoiceRecording) => void;
  onCancel?: () => void;
  onDiscard?: () => void;
  locale?: string;
  labels?: Words;
}

const LIVE_BARS = 26;
const TAKE_BARS = 30;

export function VoiceRecorder({
  level,
  analyser,
  src,
  maxDuration,
  align = 'start',
  onStart,
  onPause,
  onResume,
  onStop,
  onCancel,
  onDiscard,
  locale,
  labels,
  className,
  ...rest
}: VoiceRecorderProps) {
  const t = useT();
  const word = useWords(labels);
  const language = useLocale();
  const intl = locale ?? INTL_LOCALES[language];
  const [state, setState] = useState<'idle' | 'recording' | 'paused' | 'stopped'>('idle');
  const [elapsed, setElapsed] = useState(0);
  const [duration, setDuration] = useState(0);
  const [playing, setPlaying] = useState(false);
  const [position, setPosition] = useState(0);
  const [take, setTake] = useState<number[]>([]);
  const root = useRef<HTMLDivElement>(null);
  const measure = useRef<HTMLDivElement>(null);
  const wave = useRef<HTMLSpanElement>(null);
  const audio = useRef<HTMLAudioElement>(null);
  const samples = useRef<number[]>([]);
  const clock = useRef({ start: 0, before: 0 });
  const levelRef = useRef(level);
  levelRef.current = level;
  const analyserRef = useRef(analyser);
  analyserRef.current = analyser;
  const buffer = useRef<Uint8Array<ArrayBuffer> | null>(null);
  const seed = useRef(Math.random() * 100);
  const focusNext = useRef<string | null>(null);
  const playhead = useRef(0);
  useMorphShell(root, measure);

  const moveTo = (next: number) => {
    playhead.current = next;
    setPosition(next);
  };

  const seconds = () => (clock.current.before + (clock.current.start ? performance.now() - clock.current.start : 0)) / 1000;

  // The bars: sampled from the level prop, the analyser, or the simulation.
  const meter = useRef<ReturnType<typeof levelMeter> | null>(null);
  useIsoLayoutEffect(() => {
    if (!wave.current) return;
    meter.current = levelMeter(wave.current, {
      level: () => {
        if (typeof levelRef.current === 'number') return levelRef.current;
        const node = analyserRef.current;
        if (node) {
          if (!buffer.current || buffer.current.length !== node.fftSize) buffer.current = new Uint8Array(node.fftSize);
          node.getByteTimeDomainData(buffer.current);
          return rmsLevel(buffer.current);
        }
        return voiceLevel(seconds(), seed.current);
      },
      onSample: (value) => samples.current.push(value),
    });
    return () => meter.current?.destroy();
  }, []);

  // The clock: re-render only when the shown second changes.
  useEffect(() => {
    if (state !== 'recording') return;
    const timer = setInterval(() => {
      const now = seconds();
      setElapsed(Math.floor(now));
      if (maxDuration && now >= maxDuration) stop();
    }, 200);
    return () => clearInterval(timer);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [state, maxDuration]);

  useEffect(() => {
    const name = focusNext.current;
    if (!name) return;
    focusNext.current = null;
    root.current?.querySelector<HTMLElement>(`[data-focus="${name}"]`)?.focus({ preventScroll: true });
  }, [state]);

  // Playback without audio: move the playhead through the take in real time.
  useEffect(() => {
    if (!playing || src) return;
    let frame = 0;
    let last = performance.now();
    const tick = (now: number) => {
      const next = Math.min(duration, playhead.current + (now - last) / 1000);
      last = now;
      moveTo(next);
      if (next >= duration) setPlaying(false);
      else frame = requestAnimationFrame(tick);
    };
    frame = requestAnimationFrame(tick);
    return () => cancelAnimationFrame(frame);
  }, [playing, src, duration]);

  const start = () => {
    samples.current = [];
    clock.current = { start: performance.now(), before: 0 };
    setElapsed(0);
    setState('recording');
    meter.current?.show(Array.from({ length: LIVE_BARS }, () => 0));
    meter.current?.start();
    focusNext.current = 'stop';
    onStart?.();
  };

  const togglePause = () => {
    if (state === 'recording') {
      clock.current = { start: 0, before: clock.current.before + performance.now() - clock.current.start };
      meter.current?.stop();
      setState('paused');
      onPause?.();
    } else if (state === 'paused') {
      clock.current = { start: performance.now(), before: clock.current.before };
      meter.current?.start();
      setState('recording');
      onResume?.();
    }
  };

  function stop() {
    const total = seconds();
    meter.current?.stop();
    clock.current = { start: 0, before: 0 };
    setDuration(total);
    setElapsed(Math.floor(total));
    moveTo(0);
    setPlaying(false);
    setTake(downsampleLevels(samples.current, TAKE_BARS));
    setState('stopped');
    focusNext.current = 'play';
    onStop?.({ duration: total, levels: [...samples.current] });
  }

  const cancel = () => {
    meter.current?.stop();
    clock.current = { start: 0, before: 0 };
    setState('idle');
    focusNext.current = 'mic';
    onCancel?.();
  };

  const discard = () => {
    audio.current?.pause();
    setPlaying(false);
    setState('idle');
    focusNext.current = 'mic';
    onDiscard?.();
  };

  const togglePlay = () => {
    const el = audio.current;
    if (playing) {
      el?.pause();
      setPlaying(false);
      return;
    }
    if (playhead.current >= duration - 0.05) moveTo(0);
    if (el && src) void el.play().catch(() => setPlaying(false));
    setPlaying(true);
  };

  const seek = (next: number) => {
    moveTo(next);
    if (audio.current && src) audio.current.currentTime = next;
  };

  const live = state === 'recording' || state === 'paused';
  const shownTime = state === 'stopped' ? (playing || position > 0 ? position : duration) : elapsed;

  return (
    <div ref={root} className={cx('nx-voice-recorder', className)} data-state={state} data-align={align === 'end' ? 'end' : undefined} {...rest}>
      <div ref={measure} className="nx-voice-recorder-measure">
        <div className="nx-voice-recorder-face" data-face="idle" data-active={state === 'idle' ? '' : undefined}>
          <button type="button" className="nx-voice-recorder-mic" data-focus="mic" aria-label={word('record')} tabIndex={state === 'idle' ? 0 : -1} onClick={start}>
            <Icon name="mic" />
          </button>
        </div>
        <div className="nx-voice-recorder-face" data-face="live" data-active={live ? '' : undefined} role="group" aria-label={state === 'paused' ? word('paused') : word('recording')}>
          <button type="button" className="nx-voice-recorder-btn" data-tone="ghost" aria-label={word('cancel')} tabIndex={live ? 0 : -1} onClick={cancel}>
            <Icon name="x" />
          </button>
          <span className="nx-voice-recorder-dot" aria-hidden="true" />
          <span ref={wave} className="nx-voice-recorder-wave" aria-hidden="true">
            {Array.from({ length: LIVE_BARS }, (_, i) => (
              <i key={i} />
            ))}
          </span>
          <span className="nx-voice-recorder-time" role="timer">
            <RollingText text={formatClock(live ? elapsed : 0)} locale={intl} />
          </span>
          <button type="button" className="nx-voice-recorder-btn" data-swapped={state === 'paused' ? '' : undefined} aria-label={state === 'paused' ? word('resume') : t('pause')} tabIndex={live ? 0 : -1} onClick={togglePause}>
            <span className="nx-voice-recorder-swap" aria-hidden="true">
              <Icon name="pause" />
              <Icon name="mic" />
            </span>
          </button>
          <button type="button" className="nx-voice-recorder-btn" data-tone="danger" data-focus="stop" aria-label={word('stopRecording')} tabIndex={live ? 0 : -1} onClick={stop}>
            <Icon name="stop" />
          </button>
        </div>
        <div className="nx-voice-recorder-face" data-face="playback" data-active={state === 'stopped' ? '' : undefined} role="group" aria-label={word('voiceMessage')}>
          <button
            type="button"
            className="nx-voice-recorder-btn"
            data-tone="accent"
            data-focus="play"
            data-swapped={playing ? '' : undefined}
            aria-label={playing ? t('pause') : t('play')}
            tabIndex={state === 'stopped' ? 0 : -1}
            onClick={togglePlay}
          >
            <span className="nx-voice-recorder-swap" aria-hidden="true">
              <Icon name="play" />
              <Icon name="pause" />
            </span>
          </button>
          <span className="nx-voice-recorder-take" style={{ '--_p': duration ? position / duration : 0, '--_bars': TAKE_BARS } as CSSProperties}>
            {Array.from({ length: TAKE_BARS }, (_, i) => (
              <i key={i} style={{ '--_v': take[i] ?? 0, '--_i': i } as CSSProperties} />
            ))}
            <input
              type="range"
              className="nx-voice-recorder-seek"
              min={0}
              max={Math.max(duration, 0.1)}
              step={0.1}
              value={Math.min(position, duration)}
              aria-label={word('seek')}
              aria-valuetext={`${formatClock(position)} / ${formatClock(duration)}`}
              tabIndex={state === 'stopped' ? 0 : -1}
              onChange={(event) => seek(Number(event.target.value))}
            />
          </span>
          <span className="nx-voice-recorder-time">
            <RollingText text={formatClock(shownTime)} locale={intl} />
          </span>
          <button type="button" className="nx-voice-recorder-btn" data-tone="ghost" aria-label={word('discard')} tabIndex={state === 'stopped' ? 0 : -1} onClick={discard}>
            <Icon name="trash" />
          </button>
        </div>
      </div>
      {src && (
        <audio
          ref={audio}
          src={src}
          preload="metadata"
          onTimeUpdate={(event) => moveTo(event.currentTarget.currentTime)}
          onEnded={() => setPlaying(false)}
          onLoadedMetadata={(event) => Number.isFinite(event.currentTarget.duration) && setDuration(event.currentTarget.duration)}
        />
      )}
    </div>
  );
}

/* ---- Chain selector ----------------------------------------------------------------------------- */

export interface ChainOption {
  id: string;
  name: string;
  symbol?: string;
  tag?: string;
  icon?: IconName;
  tone?: 'lapis' | 'violet' | 'cyan' | 'gold';
}

interface MultiChainSelectorProps {
  chains: ChainOption[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  name?: string;
  label?: string;
  placeholder?: string;
  emptyText?: string;
  side?: Side;
  align?: Align;
  className?: string;
}

/** A network picker whose trigger glyph morphs into the chosen chain. */
export function MultiChainSelector({
  chains,
  value,
  defaultValue = chains[0]?.id ?? '',
  onValueChange,
  name,
  label = 'Network',
  placeholder = 'Search networks…',
  emptyText = 'No networks match',
  side = 'bottom',
  align = 'start',
  className,
}: MultiChainSelectorProps) {
  const id = useBaseId('nx-chains');
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useControllable(value, defaultValue, onValueChange);
  const [query, setQuery] = useState('');
  const trigger = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLDivElement>(null);
  const { onTriggerClick } = usePopover({ id, open, setOpen, trigger, panel, side, align, offset: 8 });

  const current = chains.find((chain) => chain.id === selected) ?? chains[0] ?? null;
  const q = foldSearchText(query.trim());

  // Let the morph play before the panel folds away.
  const choose = (chain: ChainOption) => {
    setSelected(chain.id);
    onValueChange?.(chain.id);
    window.setTimeout(() => panel.current?.hidePopover?.(), 240);
  };

  return (
    <>
      <button
        ref={trigger}
        type="button"
        className={cx('nx-chain-selector-trigger', className)}
        aria-haspopup="listbox"
        aria-expanded={open}
        aria-controls={id}
        aria-label={`${label}: ${current?.name ?? ''}`}
        onClick={onTriggerClick}
      >
        <span className="nx-chain-selector-glyph" data-tone={current?.tone ?? 'lapis'} aria-hidden>
          {chains.map((chain) => (
            <span key={chain.id} className="nx-chain-selector-morph" data-current={chain.id === selected ? '' : undefined}>
              <Icon name={chain.icon ?? 'globe'} />
            </span>
          ))}
        </span>
        <span className="nx-chain-selector-who">
          <span className="nx-chain-selector-name">{current?.name ?? '—'}</span>
          <span className="nx-chain-selector-tag">{[current?.tag, current?.symbol].filter(Boolean).join(' · ')}</span>
        </span>
      </button>
      <div ref={panel} id={id} className="nx-chain-selector" role="listbox" aria-label={label} {...{ popover: 'auto' }}>
        <div className="nx-chain-selector-search">
          <Icon name="search" />
          <input
            type="search"
            className="nx-chain-selector-input"
            placeholder={placeholder}
            aria-label={placeholder}
            aria-controls={`${id}-list`}
            value={query}
            onChange={(event) => setQuery(event.target.value)}
          />
        </div>
        <ul id={`${id}-list`} className="nx-chain-selector-list" aria-label={label}>
          {chains.map((chain) => {
            const selectedNow = chain.id === selected;
            const visible = !q || foldSearchText(`${chain.name} ${chain.symbol ?? ''} ${chain.tag ?? ''}`).includes(q);
            return (
              <li key={chain.id} className="nx-chain-selector-row" data-selected={selectedNow ? '' : undefined} hidden={!visible}>
                <button type="button" className="nx-chain-selector-choice" role="option" aria-selected={selectedNow} onClick={() => choose(chain)}>
                  <span className="nx-chain-selector-glyph" data-tone={chain.tone ?? 'lapis'} aria-hidden>
                    <span className="nx-chain-selector-morph" data-current>
                      <Icon name={chain.icon ?? 'globe'} />
                    </span>
                  </span>
                  <span className="nx-chain-selector-what">
                    <span className="nx-chain-selector-name">{chain.name}</span>
                    {(chain.tag || chain.symbol) && (
                      <span className="nx-chain-selector-tag">{[chain.tag, chain.symbol].filter(Boolean).join(' · ')}</span>
                    )}
                  </span>
                  <span className="nx-chain-selector-mark">
                    <Icon name="check" />
                  </span>
                </button>
              </li>
            );
          })}
        </ul>
        <p className="nx-chain-selector-empty" hidden={!chains.every((chain) => q && !foldSearchText(`${chain.name} ${chain.symbol ?? ''} ${chain.tag ?? ''}`).includes(q))}>
          {emptyText}
        </p>
        {name && <input type="hidden" name={name} value={selected ?? ''} />}
      </div>
    </>
  );
}

/* ---- Language menu --------------------------------------------------------------------------- */

export interface LanguageOption {
  id: string;
  /** The language's own name, as the list shows it (English, فارسی, العربية…). */
  name: string;
  /** The short code the trigger shows (EN, FA…); defaults to the id uppercased. */
  short?: string;
}

interface LanguageMenuProps {
  languages: LanguageOption[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  name?: string;
  label?: string;
  side?: Side;
  align?: Align;
  className?: string;
}

/** A locale switcher whose trigger code rolls into the chosen language, wedoflow-style. */
export function LanguageMenu({
  languages,
  value,
  defaultValue,
  onValueChange,
  name,
  label,
  side = 'bottom',
  align = 'end',
  className,
}: LanguageMenuProps) {
  const w = useWords();
  const id = useBaseId('nx-language');
  const [open, setOpen] = useState(false);
  const [selected, setSelected] = useControllable(value, defaultValue ?? languages[0]?.id ?? '', onValueChange);
  const trigger = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLDivElement>(null);
  const { onTriggerClick } = usePopover({ id, open, setOpen, trigger, panel, side, align, offset: 8 });

  const current = languages.find((language) => language.id === selected) ?? languages[0] ?? null;

  // Let the code finish rolling before the panel folds away.
  const choose = (language: LanguageOption) => {
    setSelected(language.id);
    onValueChange?.(language.id);
    window.setTimeout(() => panel.current?.hidePopover?.(), 200);
  };

  return (
    <>
      <button
        ref={trigger}
        type="button"
        className={cx('nx-language-trigger', className)}
        aria-haspopup="listbox"
        aria-expanded={open}
        aria-controls={id}
        aria-label={`${label ?? w('language')}: ${current?.name ?? ''}`}
        onClick={onTriggerClick}
      >
        <span className="nx-language-globe" aria-hidden="true">
          <Icon name="globe" />
        </span>
        <span className="nx-language-codes" aria-hidden="true">
          {languages.map((language) => (
            <span key={language.id} className="nx-language-code" data-current={language.id === selected ? '' : undefined}>
              {language.short ?? language.id.toUpperCase()}
            </span>
          ))}
        </span>
        <span className="nx-language-chevron" aria-hidden="true">
          <Icon name="chevron-down" />
        </span>
      </button>
      <div ref={panel} id={id} className="nx-language" role="listbox" aria-label={label ?? w('language')} {...{ popover: 'auto' }}>
        <ul className="nx-language-list">
          {languages.map((language) => {
            const selectedNow = language.id === selected;
            return (
              <li key={language.id} className="nx-language-row" data-selected={selectedNow ? '' : undefined}>
                <button type="button" className="nx-language-choice" role="option" aria-selected={selectedNow} onClick={() => choose(language)}>
                  {language.name}
                  <span className="nx-language-short">{language.short ?? language.id.toUpperCase()}</span>
                  <span className="nx-language-mark">
                    <Icon name="check" />
                  </span>
                </button>
              </li>
            );
          })}
        </ul>
        {name && <input type="hidden" name={name} value={selected ?? ''} />}
      </div>
    </>
  );
}

/* ---- Theme switch ------------------------------------------------------------------------------ */

export type ThemeSwitchValue = 'light' | 'system' | 'dark';

interface ThemeSwitchProps {
  value?: ThemeSwitchValue;
  defaultValue?: ThemeSwitchValue;
  onValueChange?: (value: ThemeSwitchValue) => void;
  name?: string;
  label?: string;
  className?: string;
}

const THEME_CHOICES: ThemeSwitchValue[] = ['light', 'system', 'dark'];

/** Light / system / dark triad with a spring-loaded thumb; writes the core theme preference. */
export function ThemeSwitch({ value, defaultValue, onValueChange, name, label, className }: ThemeSwitchProps) {
  const w = useWords();
  const mounted = useMounted();
  const group = name ?? `nx-theme${useId().replace(/:/g, '')}`;
  const [pref, setPref] = useControllable(value, defaultValue ?? 'system', onValueChange);
  const root = useRef<HTMLDivElement>(null);
  const ind = useIndicator(root);
  const controlled = useRef(value !== undefined);
  controlled.current = value !== undefined;
  // Until mounted, render the system choice so server and client agree.
  const shown = mounted ? pref : 'system';

  useEffect(() => {
    if (!controlled.current) setPref(theme.preference());
    return theme.watch(() => {
      if (!controlled.current) setPref(theme.preference());
    });
  }, [setPref]);

  useIsoLayoutEffect(() => {
    ind.current?.update(root.current?.querySelector('.nx-theme-switch-option:has(:checked)') ?? null);
  }, [ind, shown]);

  return (
    <div ref={root} className={cx('nx-theme-switch', className)} role="radiogroup" aria-label={label ?? w('theme')}>
      <span className="nx-indicator" aria-hidden="true" />
      {THEME_CHOICES.map((choice) => (
        <label key={choice} className="nx-theme-switch-option" aria-label={w(choice)}>
          <input
            className="nx-theme-switch-input"
            type="radio"
            name={group}
            value={choice}
            checked={shown === choice}
            onChange={() => {
              theme.set(choice);
              setPref(choice);
            }}
          />
          {choice === 'light' && <Icon name="sun" />}
          {choice === 'system' && <span className="nx-theme-switch-half" aria-hidden="true" />}
          {choice === 'dark' && <Icon name="moon" />}
        </label>
      ))}
    </div>
  );
}

/* ---- Promo bar --------------------------------------------------------------------------------- */

interface PromoBarProps {
  /** Remembers the dismissal under this key for the browsing session. */
  id?: string;
  children?: ReactNode;
  href?: string;
  linkLabel?: ReactNode;
  badge?: ReactNode;
  /** Persist the dismissal in sessionStorage (default true). */
  persist?: boolean;
  onDismiss?: () => void;
  className?: string;
}

/** An announcement strip that collapses away when dismissed, wedoflow-style. */
export function PromoBar({ id = 'promo', children, href, linkLabel, badge, persist = true, onDismiss, className }: PromoBarProps) {
  const t = useT();
  const w = useWords();
  const [state, setState] = useState<'open' | 'closing' | 'gone'>('open');

  useIsoLayoutEffect(() => {
    if (!persist) return;
    try {
      if (sessionStorage.getItem(`nabuxui.promo.${id}`)) setState('gone');
    } catch {
      /* storage refused: show the bar */
    }
  }, [persist, id]);

  if (state === 'gone') return null;

  const dismiss = () => {
    onDismiss?.();
    if (persist) {
      try {
        sessionStorage.setItem(`nabuxui.promo.${id}`, '1');
      } catch {
        /* not persisted, still dismissed */
      }
    }
    setState('closing');
  };

  return (
    <aside
      className={cx('nx-promo-bar', className)}
      role="region"
      aria-label={w('announcement')}
      data-closing={state === 'closing' ? '' : undefined}
      onTransitionEnd={(event) => {
        if (state === 'closing' && event.target === event.currentTarget) setState('gone');
      }}
    >
      <div className="nx-promo-bar-inner">
        <p className="nx-promo-bar-text">
          {children}
          {href && (
            <SmartLink className="nx-promo-bar-link" href={href}>
              {linkLabel} <Icon name="arrow-right" />
            </SmartLink>
          )}
        </p>
        {badge != null && <span className="nx-promo-bar-badge">{badge}</span>}
      </div>
      <button type="button" className="nx-promo-bar-close" aria-label={t('dismiss')} onClick={dismiss}>
        <Icon name="x" />
      </button>
    </aside>
  );
}

/* ---- Directional hover nav --------------------------------------------------------------------- */

export interface HoverNavLink {
  label: ReactNode;
  href?: string;
  icon?: IconName | ReactNode;
  description?: ReactNode;
  onSelect?: () => void;
}

export interface HoverNavItem {
  id: string;
  label: ReactNode;
  href?: string;
  /** When present the item owns a directional dropdown. */
  links?: HoverNavLink[];
}

export interface HoverNavProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  /** The wordmark at the inline start. */
  brand?: ReactNode;
  brandHref?: string;
  /** The bar's accessible name ("Main", "Website"…). */
  label?: string;
  items: HoverNavItem[];
}

/** A built-in icon by name, or any node as it is. */
const navIcon = (icon: IconName | ReactNode | undefined, fallback: IconName) =>
  icon === undefined ? <Icon name={fallback} /> : typeof icon === 'string' && icon in icons ? <Icon name={icon as IconName} /> : icon;

/**
 * A header navigation whose dropdowns open and close with the pointer's
 * direction: the panel springs in from the side the pointer crossed, hops
 * between items aim the previous panel's exit at the new one, and a short
 * grace keeps the panel open along the diagonal path to it. The pointer work
 * is the core `hoverNav` behaviour; clicks toggle for touch and keyboard, the
 * panel is a native popover (Escape and outside presses come from the
 * browser), and focus leaving the bar closes whatever is open.
 */
export function HoverNav({ brand = 'Nabu', brandHref = '/', label, items, className, ...rest }: HoverNavProps) {
  const idBase = useBaseId('nx-hover-nav');
  const [openId, setOpenId] = useState<string | null>(null);
  const bar = useRef<HTMLElement>(null);
  const triggers = useRef(new Map<string, HTMLElement>());
  const panels = useRef(new Map<string, HTMLElement>());

  const remember = (store: typeof triggers, id: string) => (el: HTMLElement | null) => {
    if (el) store.current.set(id, el);
    else store.current.delete(id);
  };

  const change = useEvent((item: HTMLElement | null) => setOpenId(item?.getAttribute('data-id') ?? null));
  useBehavior(bar, hoverNav, { item: '[data-nx-hover-item]', grace: 140, onOpen: change, onClose: () => change(null) });

  // Reflect open state onto the native popovers (or data-open without them).
  useIsoLayoutEffect(() => {
    for (const [id, panel] of panels.current) {
      const wanted = id === openId;
      if (!supportsPopover()) {
        panel.toggleAttribute('data-open', wanted);
        continue;
      }
      const shown = panel.matches(':popover-open');
      try {
        if (wanted && !shown) panel.showPopover();
        if (!wanted && shown) panel.hidePopover();
      } catch {
        /* not connected yet */
      }
    }
  }, [openId]);

  // The browser can close a popover on its own (Escape, outside press, another
  // popover opening): fold those back into state so aria stays truthful.
  useEffect(() => {
    const onToggle = (event: Event) => {
      const id = (event.currentTarget as HTMLElement).getAttribute('data-id');
      if (!id) return;
      if ((event as ToggleEvent).newState === 'open') setOpenId(id);
      else setOpenId((current) => (current === id ? null : current));
    };
    for (const panel of panels.current.values()) panel.addEventListener('toggle', onToggle);
    return () => {
      for (const panel of panels.current.values()) panel.removeEventListener('toggle', onToggle);
    };
  }, [items]);

  useIsoLayoutEffect(() => {
    if (!openId) return;
    const trigger = triggers.current.get(openId);
    const panel = panels.current.get(openId);
    if (!trigger || !panel) return;
    return place(trigger, panel, { side: 'bottom', align: 'start', offset: 10 });
  }, [openId]);

  return (
    <header className={cx('nx-hover-nav', className)} {...rest}>
      <nav
        className="nx-hover-nav-bar"
        aria-label={label}
        onKeyDown={(event) => {
          if (event.key === 'Escape') setOpenId(null);
        }}
      >
        <SmartLink className="nx-hover-nav-brand" href={brandHref}>
          {brand}
        </SmartLink>
        <ul className="nx-hover-nav-list">
          {items.map((item) => {
            const droppable = Boolean(item.links?.length);
            const open = droppable && openId === item.id;
            const panelId = `${idBase}-${idSafe(item.id)}`;
            return (
              <li key={item.id} className="nx-hover-nav-item" data-nx-hover-item="" data-id={item.id} data-open={open ? '' : undefined}>
                {droppable ? (
                  <button
                    type="button"
                    ref={remember(triggers, item.id)}
                    className="nx-hover-nav-link"
                    aria-haspopup="true"
                    aria-expanded={Boolean(open)}
                    aria-controls={panelId}
                    onClick={() => setOpenId((current) => (current === item.id ? null : item.id))}
                  >
                    {item.label}
                    <span className="nx-hover-nav-caret" aria-hidden="true">
                      <Icon name="chevron-down" />
                    </span>
                  </button>
                ) : (
                  <SmartLink ref={remember(triggers, item.id)} className="nx-hover-nav-link" href={item.href ?? '#'}>
                    {item.label}
                  </SmartLink>
                )}
                {droppable && (
                  <div ref={remember(panels, item.id)} id={panelId} className="nx-hover-nav-panel" data-id={item.id} {...{ popover: 'auto' }}>
                    <ul className="nx-hover-nav-menu">
                      {item.links!.map((link, index) => (
                        <li key={index}>
                          <SmartLink
                            className="nx-hover-nav-choice"
                            href={link.href ?? '#'}
                            style={{ '--nx-j': index } as CSSProperties}
                            onClick={link.onSelect}
                          >
                            <span className="nx-hover-nav-choice-icon" aria-hidden="true">
                              {navIcon(link.icon, 'sparkles')}
                            </span>
                            <span className="nx-hover-nav-choice-text">
                              <span className="nx-hover-nav-choice-title">{link.label}</span>
                              {link.description != null && <span className="nx-hover-nav-choice-desc">{link.description}</span>}
                            </span>
                          </SmartLink>
                        </li>
                      ))}
                    </ul>
                  </div>
                )}
              </li>
            );
          })}
        </ul>
      </nav>
    </header>
  );
}
