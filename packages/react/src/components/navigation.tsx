import {
  Children,
  type CSSProperties,
  type KeyboardEvent,
  type ReactElement,
  type ReactNode,
  cloneElement,
  isValidElement,
  useEffect,
  useId,
  useRef,
  useState,
} from 'react';
import {
  type IconName,
  createTypeahead,
  dock as dockBehavior,
  focusableItems,
  place,
  roveFocus,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useIndicator, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';
import { Kbd } from './display';


const idSafe = (value: string) => value.replace(/[^\w-]/g, '_');

/* ---- Tabs --------------------------------------------------------------------- */

export interface TabItem {
  value: string;
  label: ReactNode;
  content: ReactNode;
  badge?: ReactNode;
  icon?: IconName;
  disabled?: boolean;
}

export interface TabsProps {
  items: TabItem[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  variant?: 'pill' | 'underline' | 'enclosed';
  'aria-label'?: string;
  className?: string;
}

export function Tabs({ items, value, defaultValue, onValueChange, variant = 'pill', 'aria-label': label, className }: TabsProps) {
  const base = `nx-tabs${useId().replace(/:/g, '')}`;
  const [current, setCurrent] = useControllable(value, defaultValue ?? items[0]?.value ?? '', onValueChange);
  const list = useRef<HTMLDivElement>(null);
  const ind = useIndicator(list);

  useIsoLayoutEffect(() => {
    ind.current?.update(list.current?.querySelector(`[data-value="${CSS.escape(current)}"]`) ?? null);
  }, [current, items]);

  const onKeyDown = (event: KeyboardEvent<HTMLDivElement>) => {
    const next = roveFocus(event.nativeEvent, event.currentTarget, '[role="tab"]');
    // Automatic activation: moving focus selects the tab.
    if (next?.dataset.value) setCurrent(next.dataset.value);
  };

  return (
    <div className={cx('nx-tabs', className)} data-variant={variant === 'pill' ? undefined : variant}>
      <div ref={list} className="nx-tab-list" role="tablist" aria-label={label} onKeyDown={onKeyDown}>
        <span className="nx-indicator" aria-hidden="true" />
        {items.map((item) => {
          const selected = item.value === current;
          return (
            <button
              key={item.value}
              type="button"
              role="tab"
              className="nx-tab"
              id={`${base}-tab-${idSafe(item.value)}`}
              data-value={item.value}
              aria-selected={selected}
              aria-controls={`${base}-panel-${idSafe(item.value)}`}
              tabIndex={selected ? 0 : -1}
              disabled={item.disabled}
              onClick={() => setCurrent(item.value)}
            >
              {item.icon && <Icon name={item.icon} />}
              {item.label}
              {item.badge}
            </button>
          );
        })}
      </div>
      {items.map((item) => (
        <div
          key={item.value}
          className="nx-tab-panel"
          role="tabpanel"
          id={`${base}-panel-${idSafe(item.value)}`}
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

/* ---- Segmented control --------------------------------------------------------- */

export interface SegmentedControlProps {
  options: Array<{ value: string; label: ReactNode; icon?: IconName; disabled?: boolean }>;
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  name?: string;
  size?: 'sm' | 'md';
  tone?: 'neutral' | 'accent';
  'aria-label'?: string;
  className?: string;
}

export function SegmentedControl({ options, value, defaultValue, onValueChange, name, size, tone, 'aria-label': label, className }: SegmentedControlProps) {
  const generated = useId();
  const group = name ?? `nx-seg${generated.replace(/:/g, '')}`;
  const [current, setCurrent] = useControllable(value, defaultValue ?? options[0]?.value ?? '', onValueChange);
  const root = useRef<HTMLDivElement>(null);
  const ind = useIndicator(root);

  useIsoLayoutEffect(() => {
    ind.current?.update(root.current?.querySelector('.nx-segment:has(:checked)') ?? null);
  }, [current, options]);

  return (
    <div ref={root} className={cx('nx-segmented', className)} role="radiogroup" aria-label={label} data-size={size === 'sm' ? 'sm' : undefined} data-tone={tone === 'accent' ? 'accent' : undefined}>
      <span className="nx-indicator" aria-hidden="true" />
      {options.map((option) => (
        <label key={option.value} className="nx-segment">
          <input className="nx-segment-input" type="radio" name={group} value={option.value} checked={current === option.value} disabled={option.disabled} onChange={() => setCurrent(option.value)} />
          {option.icon && <Icon name={option.icon} />}
          {option.label}
        </label>
      ))}
    </div>
  );
}

/* ---- Nav menu: a pill that follows the pointer ------------------------------------ */

export interface NavLinkItem {
  href: string;
  label: ReactNode;
  current?: boolean;
}

export interface NavMenuProps {
  items: NavLinkItem[];
  'aria-label'?: string;
  className?: string;
}

export function NavMenu({ items, 'aria-label': label, className }: NavMenuProps) {
  const nav = useRef<HTMLElement>(null);
  const ind = useIndicator(nav);
  const home = () => nav.current?.querySelector('[aria-current="page"]') ?? null;

  useIsoLayoutEffect(() => {
    ind.current?.update(home());
  }, [items]);

  const follow = (event: { target: EventTarget }) => {
    const link = (event.target as Element).closest('.nx-navmenu-link');
    if (link) ind.current?.update(link);
  };

  return (
    <nav ref={nav} className={cx('nx-navmenu', className)} aria-label={label} onPointerOver={follow} onFocus={follow} onPointerLeave={() => ind.current?.update(home())} onBlur={() => ind.current?.update(home())}>
      <ul>
        {items.map((item) => (
          <li key={item.href}>
            <SmartLink className="nx-navmenu-link" href={item.href} aria-current={item.current ? 'page' : undefined}>
              {item.label}
            </SmartLink>
          </li>
        ))}
      </ul>
      <span className="nx-indicator" aria-hidden="true" />
    </nav>
  );
}

/* ---- Dock --------------------------------------------------------------------------- */

export interface DockItem {
  label: string;
  icon: IconName | ReactNode;
  href?: string;
  onClick?: () => void;
  current?: boolean;
}

export interface DockProps {
  items: DockItem[];
  variant?: 'glass' | 'metal';
  /** Resting icon size. */
  size?: string;
  'aria-label'?: string;
  className?: string;
}

export function Dock({ items, variant = 'glass', size, 'aria-label': label, className }: DockProps) {
  const ref = useRef<HTMLElement>(null);
  useBehavior(ref, dockBehavior, {});
  return (
    <nav ref={ref} className={cx('nx-dock', className)} data-variant={variant === 'metal' ? 'metal' : undefined} aria-label={label} style={size ? ({ '--nx-dock-size': size } as CSSProperties) : undefined}>
      {items.map((item) => {
        const inner = (
          <>
            {typeof item.icon === 'string' ? <Icon name={item.icon as IconName} /> : item.icon}
            <span className="nx-dock-label" aria-hidden="true">
              {item.label}
            </span>
          </>
        );
        return item.href ? (
          <SmartLink key={item.label} className="nx-dock-item" href={item.href} aria-label={item.label} aria-current={item.current ? 'page' : undefined}>
            {inner}
          </SmartLink>
        ) : (
          <button key={item.label} type="button" className="nx-dock-item" aria-label={item.label} onClick={item.onClick}>
            {inner}
          </button>
        );
      })}
    </nav>
  );
}

/* ---- Breadcrumbs -------------------------------------------------------------------- */

export function Breadcrumbs({ items, className }: { items: Array<{ label: ReactNode; href?: string }>; className?: string }) {
  const t = useT();
  return (
    <nav className={cx('nx-breadcrumbs', className)} aria-label={t('breadcrumb')}>
      <ol>
        {items.map((item, i) => {
          const last = i === items.length - 1;
          return (
            <li key={i}>
              {item.href && !last ? (
                <SmartLink href={item.href}>{item.label}</SmartLink>
              ) : (
                <span aria-current={last ? 'page' : undefined} style={last ? { color: 'var(--nx-text)', fontWeight: 600 } : undefined}>
                  {item.label}
                </span>
              )}
            </li>
          );
        })}
      </ol>
    </nav>
  );
}

/* ---- Pagination ----------------------------------------------------------------------- */

/** Page numbers to show, with null for the gaps: 1 … 4 5 6 … 20. */
export function pageRange(page: number, count: number, siblings = 1): Array<number | null> {
  const pages = new Set([1, count, ...Array.from({ length: siblings * 2 + 1 }, (_, i) => page - siblings + i)].filter((p) => p >= 1 && p <= count));
  const sorted = [...pages].sort((a, b) => a - b);
  const out: Array<number | null> = [];
  sorted.forEach((p, i) => {
    if (i > 0 && p - sorted[i - 1]! > 1) out.push(p - sorted[i - 1]! === 2 ? p - 1 : null);
    out.push(p);
  });
  return out;
}

export interface PaginationProps {
  page: number;
  pageCount: number;
  /** Buttons that call back… */
  onPageChange?: (page: number) => void;
  /** …or links, for server-rendered pages. */
  hrefFor?: (page: number) => string;
  siblings?: number;
  className?: string;
}

export function Pagination({ page, pageCount, onPageChange, hrefFor, siblings = 1, className }: PaginationProps) {
  const t = useT();
  const nav = useRef<HTMLElement>(null);
  const ind = useIndicator(nav);

  useIsoLayoutEffect(() => {
    ind.current?.update(nav.current?.querySelector('[aria-current="page"]') ?? null);
  }, [page, pageCount]);

  const link = (target: number, children: ReactNode, extra: Record<string, unknown> = {}) => {
    const disabled = target < 1 || target > pageCount;
    const props = { className: 'nx-page-link', 'aria-disabled': disabled || undefined, ...extra };
    return hrefFor && !disabled ? (
      <SmartLink href={hrefFor(target)} {...props}>
        {children}
      </SmartLink>
    ) : (
      <button type="button" disabled={disabled} onClick={() => onPageChange?.(target)} {...props}>
        {children}
      </button>
    );
  };

  return (
    <nav ref={nav} className={cx('nx-pagination', className)} aria-label={t('pagination')}>
      <span className="nx-indicator" aria-hidden="true" />
      {link(page - 1, <Icon name="chevron-left" />, { 'aria-label': t('previous') })}
      {pageRange(page, pageCount, siblings).map((p, i) =>
        p === null ? (
          <span key={`gap-${i}`} className="nx-page-link" data-gap="" aria-hidden="true">
            …
          </span>
        ) : (
          <span key={p} style={{ display: 'contents' }}>
            {link(p, p, { 'aria-current': p === page ? 'page' : undefined, 'aria-label': t('page', { page: p }) })}
          </span>
        ),
      )}
      {link(page + 1, <Icon name="chevron-right" />, { 'aria-label': t('next') })}
    </nav>
  );
}

/* ---- Accordion ------------------------------------------------------------------------- */

export interface AccordionProps {
  items: Array<{ id?: string; title: ReactNode; content: ReactNode }>;
  /** Only one open at a time (native exclusive <details name>). */
  single?: boolean;
  /** Ids (or indexes) open at first. */
  defaultOpen?: Array<string | number>;
  variant?: 'joined' | 'separated';
  className?: string;
}

export function Accordion({ items, single = true, defaultOpen = [], variant = 'joined', className }: AccordionProps) {
  const group = `nx-acc${useId().replace(/:/g, '')}`;
  return (
    <div className={cx('nx-accordion', className)} data-variant={variant === 'separated' ? 'separated' : undefined}>
      {items.map((item, i) => (
        <details key={item.id ?? i} className="nx-accordion-item" name={single ? group : undefined} open={defaultOpen.includes(item.id ?? i) || undefined}>
          <summary className="nx-accordion-trigger">{item.title}</summary>
          <div className="nx-accordion-content">{item.content}</div>
        </details>
      ))}
    </div>
  );
}

/* ---- Menu (dropdown) ----------------------------------------------------------------- */

export type MenuEntry =
  | { type?: 'item'; label: ReactNode; icon?: IconName; shortcut?: string; onSelect?: () => void; href?: string; tone?: 'danger'; disabled?: boolean; checked?: boolean }
  | { type: 'separator' }
  | { type: 'label'; label: ReactNode };

export interface MenuProps {
  /** A <button> (e.g. <Button>) that opens the menu. */
  trigger: ReactElement;
  items: MenuEntry[];
  side?: 'bottom' | 'top';
  align?: 'start' | 'center' | 'end';
  'aria-label'?: string;
  className?: string;
}

const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

export function Menu({ trigger, items, side = 'bottom', align = 'start', 'aria-label': label, className }: MenuProps) {
  const id = `nx-menu${useId().replace(/:/g, '')}`;
  const menu = useRef<HTMLDivElement>(null);
  const button = useRef<HTMLElement | null>(null);
  const [open, setOpen] = useState(false);
  const focusOnOpen = useRef<'first' | 'last'>('first');
  const typeahead = useRef(createTypeahead());

  // Declarative invoker: the browser toggles the menu and exempts the button
  // from light dismiss. Set on the DOM because React 18 and 19 spell it differently.
  useEffect(() => {
    if (supportsPopover()) button.current?.setAttribute('popovertarget', id);
  }, [id]);

  // The popover opens and light-dismisses natively; keep React's view in step.
  useEffect(() => {
    const el = menu.current;
    if (!el) return;
    const onToggle = (event: Event) => setOpen((event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  }, []);

  useEffect(() => {
    const el = menu.current;
    if (!open || !el || !button.current) return;
    const stop = place(button.current, el, { side, align, offset: 6 });
    const items = focusableItems(el, '[role^="menuitem"]');
    (focusOnOpen.current === 'last' ? items[items.length - 1] : items[0])?.focus();
    focusOnOpen.current = 'first';
    return stop;
  }, [open, side, align]);

  const show = (where: 'first' | 'last') => {
    focusOnOpen.current = where;
    const el = menu.current;
    if (!el) return;
    if (supportsPopover()) el.showPopover();
    else {
      el.setAttribute('data-open', '');
      setOpen(true);
    }
  };

  const hide = (returnFocus = true) => {
    const el = menu.current;
    if (!el) return;
    if (supportsPopover()) {
      try {
        el.hidePopover();
      } catch {
        /* already hidden */
      }
    } else {
      el.removeAttribute('data-open');
      setOpen(false);
    }
    if (returnFocus) button.current?.focus();
  };

  const onMenuKey = (event: KeyboardEvent<HTMLDivElement>) => {
    const el = event.currentTarget;
    if (roveFocus(event.nativeEvent, el, '[role^="menuitem"]', { orientation: 'vertical' })) return;
    if (event.key === 'Tab') hide(false);
    if (event.key === 'Escape') {
      event.preventDefault();
      hide();
    }
    const list = focusableItems(el, '[role^="menuitem"]');
    const match = typeahead.current(event.key, list, list.indexOf(document.activeElement as HTMLElement));
    match?.focus();
  };

  const child = Children.only(trigger);
  if (!isValidElement(child)) return null;
  const childProps = child.props as Record<string, unknown>;

  return (
    <>
      {cloneElement(child as ReactElement<Record<string, unknown>>, {
        ref: mergeRefs((childProps.ref as React.Ref<HTMLElement>) ?? ((child as unknown as { ref?: React.Ref<HTMLElement> }).ref || undefined), button),
        'aria-haspopup': 'menu',
        'aria-expanded': open,
        'aria-controls': id,
        onClick: (event: React.MouseEvent) => {
          (childProps.onClick as ((e: React.MouseEvent) => void) | undefined)?.(event);
          if (!supportsPopover()) (open ? hide(false) : show('first'));
        },
        onKeyDown: (event: KeyboardEvent) => {
          (childProps.onKeyDown as ((e: KeyboardEvent) => void) | undefined)?.(event);
          if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            show(event.key === 'ArrowUp' ? 'last' : 'first');
          }
        },
      })}
      <div ref={menu} id={id} className={cx('nx-menu', className)} role="menu" aria-label={label} {...{ popover: 'auto' }} onKeyDown={onMenuKey}>
        {items.map((entry, i) => {
          if (entry.type === 'separator') return <div key={i} className="nx-menu-separator" role="separator" />;
          if (entry.type === 'label') return <div key={i} className="nx-menu-label" role="presentation">{entry.label}</div>;
          const common = {
            className: 'nx-menu-item',
            role: entry.checked === undefined ? 'menuitem' : 'menuitemcheckbox',
            'aria-checked': entry.checked,
            'aria-disabled': entry.disabled || undefined,
            'data-tone': entry.tone,
            tabIndex: -1,
            style: { '--nx-i': i } as CSSProperties,
          };
          const body = (
            <>
              {entry.icon && <Icon name={entry.icon} />}
              <span>{entry.label}</span>
              {entry.shortcut && <Kbd>{entry.shortcut}</Kbd>}
            </>
          );
          return entry.href && !entry.disabled ? (
            <SmartLink key={i} href={entry.href} {...common} onClick={() => hide(false)}>
              {body}
            </SmartLink>
          ) : (
            <button
              key={i}
              type="button"
              {...common}
              onClick={() => {
                if (entry.disabled) return;
                entry.onSelect?.();
                hide();
              }}
            >
              {body}
            </button>
          );
        })}
      </div>
    </>
  );
}
