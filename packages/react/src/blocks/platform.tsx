/**
 * Platform app window (React): AppWindow — one app frame that wears four
 * platform shells. The same DOM sits under `data-platform="windows | macos |
 * android | ios"` on the root: the desktop platforms get the sidebar and the
 * caption cluster (Windows keeps its snap layouts behind a hold on maximize),
 * the mobile ones get the bottom tab bar and — on Android — a FAB. Thin over
 * css/blocks/platform.css and the core `platformWindow` behaviour, which owns
 * the window state machine (minimize squish, maximize toggle with the
 * maximize/restore label swap, hold-to-snap flyout, close event) and reflects
 * `data-platform` changes as `nx-platform-change` events.
 *
 * The behaviour's `autoRestore` defaults to true because the common use is a
 * demo/showcase window: a "closed" window comes back after 0.8s and a
 * minimized one after 1.1s. Real consumers that want the window to stay gone
 * pass `autoRestore={false}` and act on `onClose` (or the `nx-window-close`
 * event on the root).
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  useEffect,
  useMemo,
  useRef,
  useState,
} from 'react';
import { type IconName, type Platform, platformWindow } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useEvent, useIndicator, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** The window states the core behaviour moves the root between. */
export type AppWindowState = 'rest' | 'minimized' | 'maximized';

/** The snap shapes a zone pick flashes the window into (cleared after a beat). */
export type AppWindowShape = 'half' | 'third' | 'big' | 'quarter' | 'wide' | 'mid' | 'full';

/** One sidebar / tab-bar destination, like the admin sidebar's items. */
export interface AppNavItem {
  id: string;
  label: ReactNode;
  icon?: IconName;
  /** A count beside the label (a number is formatted for the reader's language). */
  badge?: number | ReactNode;
  href?: string;
  onSelect?: () => void;
}

/** One figure of the metrics strip above the content. */
export interface AppMetric {
  value: ReactNode;
  label: ReactNode;
  /** The movement beside the value; `trend` picks its colour. */
  delta?: ReactNode;
  trend?: 'up' | 'down';
}

interface SnapCell {
  shape: AppWindowShape;
  en: string;
  fa: string;
}

/** The six Windows snap layouts, in flyout order — the shapes match the values the root's `data-shape` takes. */
const SNAP_LAYOUTS: ReadonlyArray<{ zones: string; cells: ReadonlyArray<SnapCell> }> = [
  {
    zones: 'halves',
    cells: [
      { shape: 'half', en: 'Start half', fa: 'نیمهٔ ابتدا' },
      { shape: 'half', en: 'End half', fa: 'نیمهٔ انتها' },
    ],
  },
  {
    zones: 'thirds',
    cells: [
      { shape: 'third', en: 'First third', fa: 'یک‌سوم اول' },
      { shape: 'third', en: 'Middle third', fa: 'یک‌سوم میانی' },
      { shape: 'third', en: 'Last third', fa: 'یک‌سوم آخر' },
    ],
  },
  {
    zones: 'big-start',
    cells: [
      { shape: 'big', en: 'Large start', fa: 'بزرگ ابتدا' },
      { shape: 'quarter', en: 'Top end quarter', fa: 'ربع بالای انتها' },
      { shape: 'quarter', en: 'Bottom end quarter', fa: 'ربع پایین انتها' },
    ],
  },
  {
    zones: 'quads',
    cells: [
      { shape: 'quarter', en: 'Top start quadrant', fa: 'ربع بالای ابتدا' },
      { shape: 'quarter', en: 'Top end quadrant', fa: 'ربع بالای انتها' },
      { shape: 'quarter', en: 'Bottom start quadrant', fa: 'ربع پایین ابتدا' },
      { shape: 'quarter', en: 'Bottom end quadrant', fa: 'ربع پایین انتها' },
    ],
  },
  {
    zones: 'big-top',
    cells: [
      { shape: 'wide', en: 'Large top', fa: 'بزرگ بالا' },
      { shape: 'quarter', en: 'Bottom start', fa: 'پایین ابتدا' },
      { shape: 'quarter', en: 'Bottom end', fa: 'پایین انتها' },
    ],
  },
  {
    zones: 'mixed',
    cells: [
      { shape: 'mid', en: 'Start half, tall', fa: 'نیمهٔ ابتدا، بلند' },
      { shape: 'quarter', en: 'Top end', fa: 'بالای انتها' },
      { shape: 'quarter', en: 'Bottom end', fa: 'پایین انتها' },
    ],
  },
];

export interface AppWindowProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'title'> {
  /** The shell the window wears right now (controlled). */
  platform?: Platform;
  defaultPlatform?: Platform;
  onPlatformChange?: (platform: Platform) => void;
  title?: ReactNode;
  subtitle?: ReactNode;
  /** The little glyph before the title. */
  icon?: IconName;
  /** The sidebar's destinations; on android/ios they become the tab bar (`tabs` defaults to this). */
  nav?: AppNavItem[];
  /** The current nav item's id. */
  active?: string;
  defaultActive?: string;
  onActiveChange?: (id: string) => void;
  /** The product name beside the mark (desktop shells only). */
  brand?: ReactNode;
  /** The square mark (the brand's first character by default). */
  brandMark?: ReactNode;
  /** The figures above the content. */
  metrics?: AppMetric[];
  /** The tab bar's destinations on android/ios; `null` removes the tab bar (defaults to `nav`). */
  tabs?: AppNavItem[] | null;
  /** Your own titlebar interior, in place of the built-in icon + heading + toolbar + caption. */
  titlebar?: ReactNode;
  /** The end of the titlebar, before the caption cluster. */
  toolbar?: ReactNode;
  /** Your own sidebar interior (inside `.nx-app-sidebar`, desktop shells only). */
  sidebar?: ReactNode;
  /** The bottom of the built-in sidebar. */
  sidebarFoot?: ReactNode;
  /** The status bar under the content. */
  statusbar?: ReactNode;
  /** The FAB's content (Android only); `true` shows the default plus glyph. */
  fab?: ReactNode;
  /** The FAB's accessible name when its content is icon-only. */
  fabLabel?: string;
  /** Accessible name of the sidebar navigation and the tab bar. */
  navLabel?: string;
  /** Height of the frame (the CSS's default when omitted). */
  height?: string;
  /** Corner radius of the frame (each shell's own default when omitted). */
  radius?: string;
  /** The state the window starts in; afterwards the core behaviour owns `data-state`. */
  defaultState?: AppWindowState;
  /** Show the Windows snap layouts flyout (default). */
  snap?: boolean;
  /** How long the maximize button must be held before the flyout arms (core default 500ms). */
  holdMs?: number;
  /** Bring a minimized window back after 1.1s and a closed one after 0.8s (default) — see the docstring. */
  autoRestore?: boolean;
  onMinimize?: () => void;
  onMaximize?: () => void;
  onRestore?: () => void;
  onClose?: () => void;
  children?: ReactNode;
}

export function AppWindow({
  platform,
  defaultPlatform = 'windows',
  onPlatformChange,
  title,
  subtitle,
  icon,
  nav = [],
  active,
  defaultActive,
  onActiveChange,
  brand,
  brandMark,
  metrics,
  tabs,
  titlebar,
  toolbar,
  sidebar,
  sidebarFoot,
  statusbar,
  fab,
  fabLabel,
  navLabel,
  height,
  radius,
  defaultState = 'rest',
  snap = true,
  holdMs,
  autoRestore,
  onMinimize,
  onMaximize,
  onRestore,
  onClose,
  className,
  style,
  children,
  ...rest
}: AppWindowProps) {
  const t = useT();
  const language = useLocale();
  const root = useRef<HTMLDivElement>(null);
  const navEl = useRef<HTMLElement>(null);
  const ind = useIndicator(navEl);
  const number = useMemo(() => new Intl.NumberFormat(language), [language]);

  const [currentPlatform, setPlatform] = useControllable(platform, defaultPlatform, onPlatformChange);
  // Like every sibling block: the raw prop goes in, so an absent `active` stays
  // uncontrolled (a `?? null` here would read as "controlled with null").
  const [activeId, setActiveId] = useControllable(active, defaultActive ?? nav[0]?.id ?? '', onActiveChange);
  // The behaviour flips `data-state` on the root directly; the mirror keeps
  // React's next render from writing the stale attribute back over it (the
  // maximize button's label reads this state too).
  const [state, setState] = useState<AppWindowState>(defaultState);

  const tabsList = tabs === undefined ? nav : tabs;
  const desktop = currentPlatform === 'windows' || currentPlatform === 'macos';
  const fa = language.startsWith('fa');
  const mark = brandMark ?? (typeof brand === 'string' ? Array.from(brand)[0] : 'N');

  // One instance of the core behaviour for the mounted window. The callbacks
  // ride useEvent so the captured options never go stale without re-attaching.
  const notifyMinimize = useEvent(onMinimize);
  const notifyMaximize = useEvent(onMaximize);
  const notifyRestore = useEvent(onRestore);
  const notifyClose = useEvent(onClose);
  useBehavior(root, platformWindow, {
    snap,
    holdMs,
    autoRestore,
    onMinimize: onMinimize ? notifyMinimize : undefined,
    onMaximize: onMaximize ? notifyMaximize : undefined,
    onRestore: onRestore ? notifyRestore : undefined,
    onClose: onClose ? notifyClose : undefined,
  });

  // data-state: the behaviour owns the attribute after mount; we follow it.
  useIsoLayoutEffect(() => {
    const el = root.current;
    if (!el) return;
    const observer = new MutationObserver(() => {
      const next = el.getAttribute('data-state') as AppWindowState | null;
      setState((current) => (current === next ? current : (next ?? 'rest')));
    });
    observer.observe(el, { attributes: true, attributeFilter: ['data-state'] });
    return () => observer.disconnect();
  }, []);

  // data-platform: our own renders echo through the behaviour's observer as
  // `nx-platform-change`; only flips that did not come from us are adopted
  // (an uncontrolled window follows its DOM, a controlled one just reports).
  const renderedPlatform = useRef(currentPlatform);
  useIsoLayoutEffect(() => {
    renderedPlatform.current = currentPlatform;
  });
  useEffect(() => {
    const el = root.current;
    if (!el) return;
    const onExternal = (event: Event) => {
      const next = (event as CustomEvent<{ platform: Platform }>).detail?.platform;
      if (!next || next === renderedPlatform.current) return;
      setPlatform(next);
    };
    el.addEventListener('nx-platform-change', onExternal);
    return () => el.removeEventListener('nx-platform-change', onExternal);
  }, [setPlatform]);

  // The active item's highlight springs after it (the sidebar re-mounts with
  // the shell, so the platform belongs to the dependencies too).
  useIsoLayoutEffect(() => {
    ind.current?.update(activeId ? (navEl.current?.querySelector(`[data-value="${CSS.escape(activeId)}"]`) ?? null) : null);
  }, [activeId, nav, ind, sidebar, currentPlatform]);

  const pick = (item: AppNavItem) => {
    setActiveId(item.id);
    item.onSelect?.();
  };

  const sidebarInner = (item: AppNavItem) => (
    <>
      <Icon name={item.icon ?? 'grid'} />
      <span className="nx-app-label">{item.label}</span>
      {item.badge !== undefined && <span className="nx-app-badge">{typeof item.badge === 'number' ? number.format(item.badge) : item.badge}</span>}
    </>
  );
  const tabInner = (item: AppNavItem) => (
    <>
      {item.icon && <Icon name={item.icon} />}
      <span className="nx-app-tab-label">{item.label}</span>
    </>
  );
  const navItem = (item: AppNavItem, className: string, asTab = false) =>
    item.href ? (
      <SmartLink
        href={item.href}
        className={className}
        data-value={item.id}
        data-label={typeof item.label === 'string' ? item.label : undefined}
        aria-current={item.id === activeId ? ('page' as const) : undefined}
        onClick={() => pick(item)}
      >
        {asTab ? tabInner(item) : sidebarInner(item)}
      </SmartLink>
    ) : (
      <button
        type="button"
        className={className}
        data-value={item.id}
        data-label={typeof item.label === 'string' ? item.label : undefined}
        aria-current={item.id === activeId ? ('page' as const) : undefined}
        onClick={() => pick(item)}
      >
        {asTab ? tabInner(item) : sidebarInner(item)}
      </button>
    );

  const maximizeLabel = t(state === 'maximized' ? 'restore' : 'maximize');

  return (
    <div
      ref={root}
      className={cx('nx-app-window', className)}
      data-platform={currentPlatform}
      data-state={state}
      style={{
        ...vars({ '--nx-app-height': height, '--nx-app-radius': radius }),
        ...style,
      }}
      {...rest}
    >
      {!desktop && (
        <div className="nx-app-status" aria-hidden="true">
          <span className="nx-app-status-time">9:41</span>
          <span className="nx-app-status-icons">
            <span className="nx-app-status-signal" />
            <span className="nx-app-status-battery" />
          </span>
        </div>
      )}
      <div className="nx-app-frame">
        {desktop && (
          <aside className="nx-app-sidebar">
            {sidebar ?? (
              <>
                <div className="nx-app-brand">
                  <span className="nx-app-mark" aria-hidden={brandMark ? undefined : true}>
                    {mark}
                  </span>
                  {brand && <span className="nx-app-brand-text">{brand}</span>}
                </div>
                <nav ref={navEl} className="nx-app-nav" aria-label={navLabel ?? t('menu')}>
                  <span className="nx-indicator" aria-hidden="true" />
                  {nav.map((item) => navItem(item, 'nx-app-item'))}
                </nav>
                <div className="nx-app-sidebar-foot">{sidebarFoot}</div>
              </>
            )}
          </aside>
        )}
        <div className="nx-app-main">
          <header className="nx-app-titlebar">
            {titlebar ?? (
              <>
                {icon && (
                  <span className="nx-app-title-icon">
                    <Icon name={icon} />
                  </span>
                )}
                <div className="nx-app-heading">
                  {title && <h2 className="nx-app-title">{title}</h2>}
                  {subtitle && <p className="nx-app-subtitle">{subtitle}</p>}
                </div>
                {toolbar && <div className="nx-app-toolbar">{toolbar}</div>}
                {desktop && (
                  <div className="nx-app-caption">
                    <button type="button" className="nx-app-caption-btn" data-kind="minimize" aria-label={t('minimize')}>
                      <Icon name="minus" />
                    </button>
                    {currentPlatform === 'windows' ? (
                      <div className="nx-app-snap">
                        <button type="button" className="nx-app-caption-btn" data-kind="maximize" aria-label={maximizeLabel} aria-haspopup="menu">
                          <Icon name="stop" />
                        </button>
                        {snap && (
                          <div className="nx-app-snap-menu" role="menu" aria-label={t('snapLayouts')} {...{ popover: 'auto' }}>
                            {SNAP_LAYOUTS.map((layout) => (
                              <div key={layout.zones} className="nx-app-snap-layout" data-zones={layout.zones}>
                                {layout.cells.map((cell, i) => (
                                  <button key={i} type="button" role="menuitem" data-shape={cell.shape} aria-label={fa ? cell.fa : cell.en} />
                                ))}
                              </div>
                            ))}
                          </div>
                        )}
                      </div>
                    ) : (
                      <button type="button" className="nx-app-caption-btn" data-kind="maximize" aria-label={maximizeLabel}>
                        <Icon name="stop" />
                      </button>
                    )}
                    <button type="button" className="nx-app-caption-btn" data-kind="close" aria-label={t('close')}>
                      <Icon name="x" />
                    </button>
                  </div>
                )}
              </>
            )}
          </header>
          <div className="nx-app-content">
            {metrics && metrics.length > 0 && (
              <dl className="nx-app-metrics">
                {metrics.map((metric, i) => (
                  <div key={i} className="nx-app-metric">
                    <dt className="nx-app-metric-label">{metric.label}</dt>
                    <dd className="nx-app-metric-value">{metric.value}</dd>
                    {metric.delta !== undefined && (
                      <dd className="nx-app-metric-delta" data-trend={metric.trend}>
                        {metric.delta}
                      </dd>
                    )}
                  </div>
                ))}
              </dl>
            )}
            {children}
          </div>
          {statusbar !== undefined && statusbar !== null && <footer className="nx-app-statusbar">{statusbar}</footer>}
        </div>
      </div>
      {!desktop && tabsList && tabsList.length > 0 && (
        <nav className="nx-app-tabbar" aria-label={navLabel ?? t('menu')}>
          {tabsList.map((item) => navItem(item, 'nx-app-tab', true))}
        </nav>
      )}
      {!desktop && <span className="nx-app-homebar" aria-hidden="true" />}
      {currentPlatform === 'android' && fab !== undefined && fab !== null && fab !== false && (
        <button type="button" className="nx-app-fab" aria-label={fabLabel}>
          {fab === true ? <Icon name="plus" /> : fab}
        </button>
      )}
    </div>
  );
}
