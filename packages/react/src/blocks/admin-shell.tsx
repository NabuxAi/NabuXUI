/**
 * Admin shell (React): AdminShell + AdminSidebar + AdminTopbar — the frame a
 * whole admin page lives in. Thin over css/blocks/admin-shell.css: the sidebar
 * collapses with a spring on the frame's grid column, the current item's
 * highlight springs after it (core `indicator`), and on small screens the
 * sidebar comes back as a drawer on a native popover. The topbar offers the
 * search/command seat, an actions slot (theme toggle, language menu, activity…)
 * and an avatar menu; everything comes in through props.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent,
  type ReactNode,
  createContext,
  useContext,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { type IconName, dataWord, place, roveFocus } from '@nabuxai/ui-core';
import { cx, useControllable, useIndicator, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { Avatar } from '../components/display';

const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;
function useBaseId(prefix: string) {
  return `${prefix}${useId().replace(/:/g, '')}`;
}

/* ---- The shell's shared state ------------------------------------------------------------------ */

interface AdminShellState {
  /** The current nav item's id, shared by the sidebar and its drawer copy. */
  activeId: string | null;
  setActiveId: (id: string) => void;
  collapsed: boolean;
  setCollapsed: (collapsed: boolean) => void;
  drawerId: string;
  drawerOpen: boolean;
  setDrawerOpen: (open: boolean) => void;
  /** True inside the drawer's copy of the sidebar (it never collapses to a rail). */
  inDrawer: boolean;
}

const AdminShellContext = createContext<AdminShellState | null>(null);

/* ==========================================================================================
 * Admin sidebar
 * ======================================================================================== */

export interface AdminItem {
  id: string;
  label: ReactNode;
  icon?: IconName;
  href?: string;
  /** A count beside the label (a number is formatted for the reader's language). */
  badge?: number | ReactNode;
  disabled?: boolean;
  onSelect?: () => void;
}

export interface AdminGroup {
  id?: string;
  label?: ReactNode;
  items: AdminItem[];
}

export interface AdminSidebarProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  groups: AdminGroup[];
  /** The current item's id. */
  value?: string;
  defaultValue?: string;
  onValueChange?: (id: string) => void;
  collapsed?: boolean;
  defaultCollapsed?: boolean;
  onCollapsedChange?: (collapsed: boolean) => void;
  /** The product name beside the mark. */
  brand?: ReactNode;
  /** The square mark (the brand's first character by default). */
  brandMark?: ReactNode;
  /** Bottom of the sidebar, above the collapse toggle. */
  footer?: ReactNode;
  /** Accessible name of the navigation. */
  navLabel?: string;
}

export function AdminSidebar({
  groups,
  value,
  defaultValue,
  onValueChange,
  collapsed,
  defaultCollapsed = false,
  onCollapsedChange,
  brand,
  brandMark,
  footer,
  navLabel,
  className,
  ...rest
}: AdminSidebarProps) {
  const language = useLocale();
  const shell = useContext(AdminShellContext);
  const nav = useRef<HTMLElement>(null);
  const ind = useIndicator(nav);
  const first = groups.flatMap((group) => group.items)[0]?.id ?? '';
  const [ownActive, setOwnActive] = useControllable(value, defaultValue ?? first, onValueChange);
  const [ownCollapsed, setOwnCollapsed] = useControllable(collapsed, defaultCollapsed, onCollapsedChange);

  // Inside the shell the shared state wins (so the sidebar and its drawer copy
  // agree); standalone the sidebar owns its state.
  const current = value !== undefined ? value : (shell?.activeId ?? ownActive);
  const isCollapsed = collapsed !== undefined ? collapsed : (shell?.collapsed ?? ownCollapsed);

  const mark = brandMark ?? (typeof brand === 'string' ? Array.from(brand)[0] : 'N');
  const number = useMemo(() => new Intl.NumberFormat(language), [language]);

  // The highlight springs to the current item; the core indicator's own
  // ResizeObserver re-measures it while the collapse animation runs. The
  // drawer copy re-measures when its popover opens (it had no box until then).
  const drawerOpen = shell?.drawerOpen;
  useIsoLayoutEffect(() => {
    ind.current?.update(current ? (nav.current?.querySelector(`[data-value="${CSS.escape(current)}"]`) ?? null) : null);
  }, [current, groups, ind, drawerOpen]);

  const pick = (item: AdminItem) => {
    if (item.disabled) return;
    if (shell && value === undefined) shell.setActiveId(item.id);
    else setOwnActive(item.id);
    item.onSelect?.();
  };

  const toggleCollapsed = () => {
    if (shell && collapsed === undefined) shell.setCollapsed(!isCollapsed);
    else setOwnCollapsed(!isCollapsed);
  };

  return (
    <aside className={cx('nx-admin-sidebar', className)} data-collapsed={isCollapsed || undefined} {...rest}>
      <div className="nx-admin-brand">
        <span className="nx-admin-mark" aria-hidden={brandMark ? undefined : true}>
          {mark}
        </span>
        {brand && <span className="nx-admin-brand-text">{brand}</span>}
      </div>
      <nav ref={nav} className="nx-admin-nav" aria-label={navLabel}>
        <span className="nx-indicator" aria-hidden="true" />
        {groups.map((group, i) => (
          <section key={group.id ?? i} className="nx-admin-group">
            {group.label && <h3 className="nx-admin-group-label">{group.label}</h3>}
            <ul>
              {group.items.map((item) => {
                const on = item.id === current;
                const inner = (
                  <>
                    <Icon name={item.icon ?? 'grid'} />
                    <span className="nx-admin-label">{item.label}</span>
                    {item.badge !== undefined && (
                      <span className="nx-admin-badge">{typeof item.badge === 'number' ? number.format(item.badge) : item.badge}</span>
                    )}
                  </>
                );
                const common = {
                  className: 'nx-admin-item',
                  'data-value': item.id,
                  'data-label': typeof item.label === 'string' ? item.label : undefined,
                  'aria-current': on ? ('page' as const) : undefined,
                  'aria-disabled': item.disabled || undefined,
                  onClick: () => pick(item),
                };
                return (
                  <li key={item.id}>
                    {item.href ? (
                      <SmartLink href={item.href} {...common}>
                        {inner}
                      </SmartLink>
                    ) : (
                      <button type="button" disabled={item.disabled} {...common}>
                        {inner}
                      </button>
                    )}
                  </li>
                );
              })}
            </ul>
          </section>
        ))}
      </nav>
      <div className="nx-admin-sidebar-foot">
        {footer}
        <button
          type="button"
          className="nx-admin-item nx-admin-toggle"
          aria-expanded={!isCollapsed}
          data-label={isCollapsed ? dataWord(language, 'expand') : dataWord(language, 'collapse')}
          onClick={toggleCollapsed}
        >
          <Icon name="chevron-left" />
          <span className="nx-admin-label">{isCollapsed ? dataWord(language, 'expand') : dataWord(language, 'collapse')}</span>
        </button>
      </div>
    </aside>
  );
}

/* ==========================================================================================
 * Admin topbar
 * ======================================================================================== */

export interface AdminUserAction {
  label: ReactNode;
  icon?: IconName;
  href?: string;
  onClick?: () => void;
  /** A rule above this entry instead of the entry itself. */
  divider?: boolean;
}

export interface AdminUser {
  name: string;
  /** The line under the name ("مدیر سیستم"). */
  role?: ReactNode;
  avatar?: string;
  /** The popover's entries; without them the avatar is only a display. */
  menu?: AdminUserAction[];
  /** Accessible name of the menu button (defaults to the user's name). */
  menuLabel?: string;
}

export interface AdminTopbarProps extends Omit<HTMLAttributes<HTMLDivElement>, 'title'> {
  title?: ReactNode;
  subtitle?: ReactNode;
  /** Your own composer for the search seat; `null` removes the seat. */
  search?: ReactNode;
  searchPlaceholder?: string;
  /** The kbd hint on the built-in search button ("⌘K"). */
  searchHint?: ReactNode;
  /** Called when the built-in search button is pressed (an `nx-search` event also bubbles). */
  onSearchActivate?: () => void;
  /** The end of the bar: theme toggle, language menu, activity dropdown… */
  actions?: ReactNode;
  /** The signed-in user; with `menu` (or the `userMenu` slot) their avatar becomes a menu. */
  user?: AdminUser;
  /** Your own markup for the user menu panel (wrap the menuitems in your own `role="menu"`). */
  userMenu?: ReactNode;
}

export function AdminTopbar({
  title,
  subtitle,
  search,
  searchPlaceholder,
  searchHint,
  onSearchActivate,
  actions,
  user,
  userMenu,
  className,
  ...rest
}: AdminTopbarProps) {
  const t = useT();
  const language = useLocale();
  const shell = useContext(AdminShellContext);
  const menu = useRef<HTMLButtonElement>(null);
  const userTrigger = useRef<HTMLButtonElement>(null);
  const userPanel = useRef<HTMLDivElement>(null);
  const menuId = useBaseId('nx-admin-user');
  const [userOpen, setUserOpen] = useState(false);
  const hasMenu = !!user && (!!userMenu || !!user.menu?.length);

  // The drawer's invoker is declarative where the browser can do it.
  const drawerId = shell?.drawerId;
  const inDrawer = shell?.inDrawer ?? false;
  useEffect(() => {
    if (supportsPopover() && drawerId && !inDrawer) menu.current?.setAttribute('popovertarget', drawerId);
  }, [drawerId, inDrawer]);

  // So is the avatar's — with the Popover API the panel only opens through its
  // declarative invoker; the click handler below is the no-popover fallback.
  useEffect(() => {
    if (supportsPopover() && hasMenu) userTrigger.current?.setAttribute('popovertarget', menuId);
  }, [hasMenu, menuId]);

  // The user menu panel: toggled along with the state, placed against its trigger.
  useIsoLayoutEffect(() => {
    const el = userPanel.current;
    if (!el) return;
    const onToggle = (event: Event) => setUserOpen((event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  }, [hasMenu]);

  useIsoLayoutEffect(() => {
    const el = userPanel.current;
    if (!el || !hasMenu) return;
    if (!supportsPopover()) {
      el.toggleAttribute('data-open', userOpen);
      return;
    }
    const shown = el.matches(':popover-open');
    try {
      if (userOpen && !shown) el.showPopover();
      if (!userOpen && shown) el.hidePopover();
    } catch {
      /* not connected yet */
    }
  }, [userOpen, hasMenu]);

  useIsoLayoutEffect(() => {
    if (!userOpen || !userTrigger.current || !userPanel.current) return;
    return place(userTrigger.current, userPanel.current, { side: 'bottom', align: 'end', offset: 8 });
  }, [userOpen]);

  // Closing the menu from inside returns focus to the avatar.
  useEffect(() => {
    if (userOpen || !userPanel.current) return;
    if (userPanel.current.contains(document.activeElement)) userTrigger.current?.focus();
  }, [userOpen]);

  const onMenuKey = (event: KeyboardEvent<HTMLDivElement>) => {
    if (userPanel.current) roveFocus(event.nativeEvent, userPanel.current, '.nx-admin-user-item', { orientation: 'vertical' });
  };

  return (
    <div className={cx('nx-admin-topbar-start', className)} {...rest}>
      {shell && !shell.inDrawer && (
        <button
          ref={menu}
          type="button"
          className="nx-admin-menu"
          aria-controls={shell.drawerId}
          aria-expanded={shell.drawerOpen}
          onClick={() => !supportsPopover() && shell.setDrawerOpen(!shell.drawerOpen)}
        >
          <Icon name="menu" />
          <span className="nx-visually-hidden">{t('menu')}</span>
        </button>
      )}
      <div className="nx-admin-heading">
        {title && <p className="nx-admin-title">{title}</p>}
        {subtitle && <p className="nx-admin-subtitle">{subtitle}</p>}
      </div>
      {search !== null && (
        <div className="nx-admin-search">
          {search ?? (
            <button
              type="button"
              className="nx-admin-search-btn"
              onClick={(event) => {
                onSearchActivate?.();
                event.currentTarget.dispatchEvent(new CustomEvent('nx-search', { bubbles: true }));
              }}
            >
              <Icon name="search" />
              <span>{searchPlaceholder ?? t('searchPlaceholder')}</span>
              {searchHint && <kbd className="nx-kbd">{searchHint}</kbd>}
            </button>
          )}
        </div>
      )}
      <div className="nx-admin-actions">
        {actions}
        {user && (
          <>
            <button
              ref={userTrigger}
              type="button"
              className="nx-admin-user"
              aria-haspopup="menu"
              aria-expanded={hasMenu ? userOpen : undefined}
              aria-controls={hasMenu ? menuId : undefined}
              aria-label={user.menuLabel ?? user.name}
              onClick={() => !supportsPopover() && hasMenu && setUserOpen(!userOpen)}
            >
              <Avatar name={user.name} src={user.avatar} />
              <span className="nx-admin-user-text">
                <span className="nx-admin-user-name">{user.name}</span>
                {user.role && <span className="nx-admin-user-role">{user.role}</span>}
              </span>
              {hasMenu && <Icon name="chevron-down" />}
            </button>
            {hasMenu && (
              <div ref={userPanel} id={menuId} className="nx-admin-user-menu" {...{ popover: 'auto' }} onKeyDown={onMenuKey}>
                {userMenu ?? (
                  <>
                    {/* The head is panel chrome, not a menu child — a menu may only
                        hold menuitems and separators, so the items get their own wrapper. */}
                    <div className="nx-admin-user-head">
                      <p className="nx-admin-user-name">{user.name}</p>
                      {user.role && <p className="nx-admin-user-role">{user.role}</p>}
                    </div>
                    <div role="menu" aria-label={user.menuLabel ?? user.name}>
                      {user.menu?.map((entry, i) =>
                        entry.divider ? (
                          <hr key={i} className="nx-admin-user-divider" />
                        ) : entry.href ? (
                          <SmartLink key={i} className="nx-admin-user-item" role="menuitem" href={entry.href}>
                            {entry.icon && <Icon name={entry.icon} />}
                            <span>{entry.label}</span>
                          </SmartLink>
                        ) : (
                          <button
                            key={i}
                            type="button"
                            className="nx-admin-user-item"
                            role="menuitem"
                            onClick={() => {
                              entry.onClick?.();
                              setUserOpen(false);
                            }}
                          >
                            {entry.icon && <Icon name={entry.icon} />}
                            <span>{entry.label}</span>
                          </button>
                        ),
                      )}
                    </div>
                  </>
                )}
              </div>
            )}
          </>
        )}
      </div>
    </div>
  );
}

/* ==========================================================================================
 * Admin shell
 * ======================================================================================== */

export interface AdminShellProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'title' | 'defaultValue'> {
  /** The sidebar — the same node again inside the drawer, so pass it once. */
  sidebar: ReactNode;
  /** The topbar row; defaults to <AdminTopbar> built from the props below. */
  topbar?: ReactNode;
  children?: ReactNode;
  /** The current nav item's id, shared with the sidebar. */
  active?: string;
  defaultActive?: string;
  onActiveChange?: (id: string) => void;
  collapsed?: boolean;
  defaultCollapsed?: boolean;
  onCollapsedChange?: (collapsed: boolean) => void;
  title?: ReactNode;
  subtitle?: ReactNode;
  search?: ReactNode;
  searchPlaceholder?: string;
  searchHint?: ReactNode;
  onSearchActivate?: () => void;
  actions?: ReactNode;
  user?: AdminUser;
  userMenu?: ReactNode;
  /** Height of the frame (100dvh by default: it frames the whole panel page). */
  height?: string;
  minHeight?: string;
  radius?: string;
  border?: string;
}

export function AdminShell({
  sidebar,
  topbar,
  children,
  active,
  defaultActive,
  onActiveChange,
  collapsed,
  defaultCollapsed = false,
  onCollapsedChange,
  title,
  subtitle,
  search,
  searchPlaceholder,
  searchHint,
  onSearchActivate,
  actions,
  user,
  userMenu,
  height,
  minHeight,
  radius,
  border,
  className,
  style,
  ...rest
}: AdminShellProps) {
  const t = useT();
  const root = useRef<HTMLDivElement>(null);
  const drawer = useRef<HTMLDivElement>(null);
  const drawerId = useBaseId('nx-admin-drawer');
  const [activeId, setActiveId] = useControllable<string | null>(active ?? null, defaultActive ?? null, onActiveChange as (id: string | null) => void);
  const [isCollapsed, setCollapsed] = useControllable(collapsed, defaultCollapsed, onCollapsedChange);
  const [drawerOpen, setDrawerOpen] = useState(false);

  const shell = useMemo<AdminShellState>(
    () => ({ activeId, setActiveId, collapsed: isCollapsed, setCollapsed, drawerId, drawerOpen, setDrawerOpen, inDrawer: false }),
    [activeId, setActiveId, isCollapsed, setCollapsed, drawerId, drawerOpen],
  );
  // The drawer's copy of the sidebar stays expanded whatever the desktop rail does.
  const inDrawer = useMemo<AdminShellState>(() => ({ ...shell, collapsed: false, inDrawer: true }), [shell]);

  // The drawer is a native popover: the toggle event is the one source of truth.
  useIsoLayoutEffect(() => {
    const el = drawer.current;
    if (!el) return;
    const onToggle = (event: Event) => {
      const open = (event as ToggleEvent).newState === 'open';
      setDrawerOpen(open);
      if (!open && el.contains(document.activeElement)) root.current?.querySelector<HTMLElement>('.nx-admin-menu')?.focus();
    };
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  }, []);

  // Without popover support the state drives the panel's data-open fallback.
  useIsoLayoutEffect(() => {
    const el = drawer.current;
    if (!el || supportsPopover()) return;
    el.toggleAttribute('data-open', drawerOpen);
  }, [drawerOpen]);

  return (
    <AdminShellContext.Provider value={shell}>
      <div
        ref={root}
        className={cx('nx-admin', className)}
        style={{
          ...vars({
            '--nx-admin-height': height,
            '--nx-admin-min-height': minHeight,
            '--nx-admin-radius': radius,
            '--nx-admin-border': border,
          }),
          ...style,
        }}
        {...rest}
      >
        <div className="nx-admin-frame" data-collapsed={isCollapsed || undefined}>
          {sidebar}
          <div className="nx-admin-main">
            <header className="nx-admin-topbar">
              {topbar ?? (
                <AdminTopbar
                  title={title}
                  subtitle={subtitle}
                  search={search}
                  searchPlaceholder={searchPlaceholder}
                  searchHint={searchHint}
                  onSearchActivate={onSearchActivate}
                  actions={actions}
                  user={user}
                  userMenu={userMenu}
                />
              )}
            </header>
            <div className="nx-admin-content">{children}</div>
          </div>
        </div>
        <div ref={drawer} id={drawerId} className="nx-admin-drawer" aria-label={t('menu')} {...{ popover: 'auto' }}>
          <button
            type="button"
            className="nx-admin-drawer-close"
            onClick={() => {
              // The drawer is a native popover where supported: hiding it fires the
              // toggle listener that brings the state along; state alone cannot close it.
              if (!supportsPopover()) setDrawerOpen(false);
              else if (drawer.current?.matches(':popover-open')) drawer.current.hidePopover();
            }}
          >
            <Icon name="x" />
            <span className="nx-visually-hidden">{t('close')}</span>
          </button>
          <AdminShellContext.Provider value={inDrawer}>{sidebar}</AdminShellContext.Provider>
        </div>
      </div>
    </AdminShellContext.Provider>
  );
}
