import {
  type CSSProperties,
  type KeyboardEvent,
  type ReactNode,
  useEffect,
  useId,
  useRef,
  useState,
} from 'react';
import { type IconName, headerScroll, indicator, theme, themeScript } from '@nabuxai/ui-core';
import { cx, useBehavior, useInert, useIsoLayoutEffect, useMounted } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';
import { Drawer } from './overlay';

/* ---- Shared navigation model ---------------------------------------------------- */

export interface NavChild {
  label: ReactNode;
  href: string;
  description?: ReactNode;
  icon?: IconName;
}

export interface NavItem {
  label: ReactNode;
  /** A plain link… */
  href?: string;
  current?: boolean;
  /** …or a panel of links (mega menu on desktop, a sub-level on mobile). */
  children?: NavChild[];
  /** Columns for the panel's link grid. */
  columns?: number;
  /** Extra content in the panel beside the links (a featured card, say). */
  aside?: ReactNode;
}

/* ---- Mega menu --------------------------------------------------------------------- */

export interface MegaMenuProps {
  items: NavItem[];
  'aria-label'?: string;
  className?: string;
}

type Motion = 'from-start' | 'from-end' | 'to-start' | 'to-end' | undefined;

export function MegaMenu({ items, 'aria-label': label, className }: MegaMenuProps) {
  const base = `nx-mega${useId().replace(/:/g, '')}`;
  const nav = useRef<HTMLElement>(null);
  const viewport = useRef<HTMLDivElement>(null);
  const ind = useRef<ReturnType<typeof indicator> | null>(null);
  const closeTimer = useRef<ReturnType<typeof setTimeout> | undefined>(undefined);
  const [active, setActive] = useState<number | null>(null);
  const [leaving, setLeaving] = useState<{ index: number; motion: Motion } | null>(null);
  const [motion, setMotion] = useState<Motion>(undefined);
  const [fresh, setFresh] = useState(true);

  const open = (index: number) => {
    clearTimeout(closeTimer.current);
    if (index === active) return;
    if (active === null) {
      setFresh(true);
      setMotion(undefined);
      setLeaving(null);
    } else {
      // Slide from the side the pointer came from, in reading order.
      const forward = index > active;
      setFresh(false);
      setMotion(forward ? 'from-end' : 'from-start');
      setLeaving({ index: active, motion: forward ? 'to-start' : 'to-end' });
    }
    setActive(index);
  };

  const close = (delay = 0) => {
    clearTimeout(closeTimer.current);
    closeTimer.current = setTimeout(() => {
      setActive(null);
      setLeaving(null);
    }, delay);
  };

  useEffect(() => () => clearTimeout(closeTimer.current), []);

  useIsoLayoutEffect(() => {
    if (!nav.current) return;
    ind.current = indicator(nav.current);
    return () => ind.current?.destroy();
  }, []);

  const home = () => nav.current?.querySelector('.nx-mega-list [aria-current="page"]') ?? null;

  // Pill follows the open trigger, or goes home to the current page.
  useIsoLayoutEffect(() => {
    const trigger = active === null ? home() : nav.current?.querySelector(`#${base}-trigger-${active}`);
    ind.current?.update(trigger ?? null);
  }, [active]);

  // Size and place the viewport around the active panel.
  useIsoLayoutEffect(() => {
    const view = viewport.current;
    const root = nav.current;
    if (active === null || !view || !root) return;
    const panel = view.querySelector<HTMLElement>(`#${base}-panel-${active}`);
    const trigger = root.querySelector<HTMLElement>(`#${base}-trigger-${active}`);
    if (!panel || !trigger) return;
    const w = panel.offsetWidth;
    const h = panel.offsetHeight;
    const navRect = root.getBoundingClientRect();
    const t = trigger.getBoundingClientRect();
    const ideal = t.left + t.width / 2 - navRect.left - w / 2;
    const min = -navRect.left + 12;
    const max = document.documentElement.clientWidth - navRect.left - w - 12;
    view.style.setProperty('--nx-mega-w', `${w}px`);
    view.style.setProperty('--nx-mega-h', `${h}px`);
    view.style.setProperty('--nx-mega-x', `${Math.round(Math.min(Math.max(ideal, min), Math.max(min, max)))}px`);
  }, [active]);

  const onTriggerKey = (event: KeyboardEvent<HTMLButtonElement>, index: number) => {
    if (event.key === 'ArrowDown' || ((event.key === 'Enter' || event.key === ' ') && active !== index)) {
      event.preventDefault();
      open(index);
      requestAnimationFrame(() => viewport.current?.querySelector<HTMLElement>(`#${base}-panel-${index} a`)?.focus());
    }
  };

  const onPanelKey = (event: KeyboardEvent<HTMLDivElement>) => {
    if (event.key !== 'Escape' || active === null) return;
    const index = active;
    close();
    nav.current?.querySelector<HTMLElement>(`#${base}-trigger-${index}`)?.focus();
  };

  return (
    <nav
      ref={nav}
      className={cx('nx-mega', className)}
      aria-label={label}
      onPointerLeave={() => close(220)}
      onPointerEnter={() => clearTimeout(closeTimer.current)}
      onBlur={(event) => !event.currentTarget.contains(event.relatedTarget as Node) && close()}
    >
      <ul className="nx-mega-list">
        {items.map((item, i) =>
          item.children ? (
            <li key={i}>
              <button
                type="button"
                id={`${base}-trigger-${i}`}
                className="nx-mega-trigger"
                aria-expanded={active === i}
                aria-controls={`${base}-panel-${i}`}
                onPointerEnter={(event) => event.pointerType === 'mouse' && open(i)}
                onClick={() => (active === i ? close() : open(i))}
                onKeyDown={(event) => onTriggerKey(event, i)}
              >
                {item.label}
                <Icon name="chevron-down" />
              </button>
            </li>
          ) : (
            <li key={i}>
              <SmartLink
                className="nx-mega-link"
                href={item.href ?? '#'}
                aria-current={item.current ? 'page' : undefined}
                onPointerEnter={() => {
                  close(120);
                  ind.current?.update(nav.current?.querySelector(`[data-mega-link="${i}"]`) ?? null);
                }}
                data-mega-link={i}
              >
                {item.label}
              </SmartLink>
            </li>
          ),
        )}
      </ul>
      <span className="nx-indicator" aria-hidden="true" />
      <div ref={viewport} className="nx-mega-viewport" data-state={active === null ? 'closed' : 'open'} data-fresh={fresh ? '' : undefined} onKeyDown={onPanelKey}>
        {items.map((item, i) =>
          item.children ? (
            <div
              key={i}
              id={`${base}-panel-${i}`}
              className="nx-mega-panel"
              hidden={i !== active && leaving?.index !== i}
              data-motion={i === active ? motion : leaving?.index === i ? leaving.motion : undefined}
              onAnimationEnd={() => leaving?.index === i && setLeaving(null)}
            >
              <div style={{ display: 'flex', gap: 'var(--nx-space-4)' }}>
                <ul className="nx-mega-grid" style={{ '--nx-mega-cols': item.columns ?? 2 } as CSSProperties}>
                  {item.children.map((child, c) => (
                    <li key={c}>
                      <SmartLink className="nx-mega-item" href={child.href} onClick={() => close()}>
                        <span className="nx-mega-item-icon" aria-hidden="true">
                          <Icon name={child.icon ?? 'arrow-right'} />
                        </span>
                        <span className="nx-mega-item-title">{child.label}</span>
                        {child.description && <span className="nx-mega-item-description">{child.description}</span>}
                      </SmartLink>
                    </li>
                  ))}
                </ul>
                {item.aside}
              </div>
            </div>
          ) : null,
        )}
      </div>
    </nav>
  );
}

/* ---- Mobile drill-down menu ------------------------------------------------------ */

export interface MobileMenuProps {
  items: NavItem[];
  /** Called after a link is chosen (close the drawer here). */
  onNavigate?: () => void;
  footer?: ReactNode;
}

export function MobileMenu({ items, onNavigate, footer }: MobileMenuProps) {
  const t = useT();
  const [sub, setSub] = useState<number | null>(null);
  const rootPanel = useRef<HTMLDivElement>(null);
  const subPanel = useRef<HTMLDivElement>(null);
  const opener = useRef<HTMLButtonElement | null>(null);
  useInert(rootPanel, sub !== null);
  useInert(subPanel, sub === null);

  useEffect(() => {
    if (sub !== null) subPanel.current?.querySelector<HTMLElement>('.nx-drilldown-back')?.focus();
    else opener.current?.focus();
  }, [sub]);

  const current = sub === null ? null : items[sub];

  return (
    <div className="nx-drilldown">
      <div className="nx-drilldown-track" style={{ '--nx-level': sub === null ? 0 : 1 } as CSSProperties}>
        <div ref={rootPanel} className="nx-drilldown-panel">
          {items.map((item, i) =>
            item.children ? (
              <button
                key={i}
                type="button"
                className="nx-drilldown-item"
                style={{ '--nx-i': i } as CSSProperties}
                aria-expanded={sub === i}
                onClick={(event) => {
                  opener.current = event.currentTarget;
                  setSub(i);
                }}
              >
                <span>{item.label}</span>
                <Icon name="chevron-right" />
              </button>
            ) : (
              <SmartLink key={i} className="nx-drilldown-item" href={item.href ?? '#'} aria-current={item.current ? 'page' : undefined} style={{ '--nx-i': i } as CSSProperties} onClick={onNavigate}>
                <span>{item.label}</span>
              </SmartLink>
            ),
          )}
          {footer && <div style={{ marginBlockStart: 'var(--nx-space-4)', display: 'grid', gap: 'var(--nx-space-2)' }}>{footer}</div>}
        </div>
        <div ref={subPanel} className="nx-drilldown-panel">
          {current?.children && (
            <>
              <button type="button" className="nx-drilldown-back" onClick={() => setSub(null)}>
                <Icon name="chevron-left" />
                {t('back')}
              </button>
              {current.children.map((child, c) => (
                <SmartLink key={c} className="nx-drilldown-item" href={child.href} style={{ '--nx-i': c } as CSSProperties} onClick={onNavigate}>
                  <span>{child.label}</span>
                  {child.icon && <Icon name={child.icon} />}
                </SmartLink>
              ))}
            </>
          )}
        </div>
      </div>
    </div>
  );
}

/* ---- Header ------------------------------------------------------------------------- */

export interface HeaderProps {
  brand: ReactNode;
  brandHref?: string;
  items?: NavItem[];
  /** Buttons at the end of the bar. Mark desktop-only ones with data-desktop. */
  actions?: ReactNode;
  /** Shown at the bottom of the mobile menu. */
  mobileActions?: ReactNode;
  variant?: 'bar' | 'floating';
  /** Slide away while scrolling down, back on the way up. */
  hideOnScroll?: boolean;
  'aria-label'?: string;
  className?: string;
}

export function Header({ brand, brandHref = '/', items = [], actions, mobileActions, variant = 'bar', hideOnScroll, 'aria-label': label, className }: HeaderProps) {
  const t = useT();
  const ref = useRef<HTMLElement>(null);
  const [menuOpen, setMenuOpen] = useState(false);
  useBehavior(ref, headerScroll, { hide: !!hideOnScroll });

  return (
    <header ref={ref} className={cx('nx-header', className)} data-variant={variant === 'floating' ? 'floating' : undefined}>
      <div className="nx-header-inner">
        <SmartLink className="nx-header-brand" href={brandHref}>
          {brand}
        </SmartLink>
        {items.length > 0 && (
          <div className="nx-header-nav">
            <MegaMenu items={items} aria-label={label} />
          </div>
        )}
        {actions && <div className="nx-header-actions">{actions}</div>}
        {items.length > 0 && (
          <button type="button" className="nx-header-toggle" aria-expanded={menuOpen} aria-label={t('menu')} onClick={() => setMenuOpen(true)}>
            <span className="nx-burger" aria-hidden="true">
              <i />
              <i />
            </span>
          </button>
        )}
      </div>
      {items.length > 0 && (
        <Drawer open={menuOpen} onOpenChange={setMenuOpen} title={t('menu')}>
          <MobileMenu items={items} onNavigate={() => setMenuOpen(false)} footer={mobileActions} />
        </Drawer>
      )}
    </header>
  );
}

/* ---- Route progress ------------------------------------------------------------------ */

export interface RouteProgressProps {
  /** loading while a page is on its way, done when it arrived, idle otherwise. */
  state: 'idle' | 'loading' | 'done';
  /** 0–1 when the real progress is known (uploads); a trickle otherwise. */
  value?: number;
}

export function RouteProgress({ state, value }: RouteProgressProps) {
  return (
    <div
      className="nx-route-progress"
      data-state={state}
      data-determinate={value !== undefined ? '' : undefined}
      aria-hidden="true"
      style={value !== undefined ? ({ '--nx-value': value } as CSSProperties) : undefined}
    />
  );
}

/* ---- Theme ----------------------------------------------------------------------------- */

/** Render inside <head> so the page paints in the right theme from the first frame. */
export function ThemeScript({ nonce }: { nonce?: string }) {
  return <script nonce={nonce} dangerouslySetInnerHTML={{ __html: themeScript }} />;
}

export function ThemeToggle({ className }: { className?: string }) {
  const t = useT();
  const mounted = useMounted();
  const [dark, setDark] = useState(false);

  useEffect(() => {
    setDark(theme.resolved() === 'dark');
    return theme.watch((scheme) => setDark(scheme === 'dark'));
  }, []);

  return (
    <button
      type="button"
      className={cx('nx-button nx-theme-toggle', className)}
      data-variant="ghost"
      data-icon-only=""
      aria-pressed={mounted ? dark : undefined}
      aria-label={t('darkMode')}
      title={dark ? t('toLight') : t('toDark')}
      onClick={() => setDark(theme.toggle() === 'dark')}
    >
      <span className="nx-button-label">
        <span className="nx-theme-icons" aria-hidden="true">
          <Icon name="sun" className="nx-theme-sun" />
          <Icon name="moon" className="nx-theme-moon" />
        </span>
      </span>
    </button>
  );
}
