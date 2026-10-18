import { createApp, h, nextTick } from 'vue';
import { describe, expect, it } from 'vitest';
import {
  NxAccordion,
  NxBackdrop,
  NxBreadcrumbs,
  NxCommandPalette,
  NxDialog,
  NxDock,
  NxHeader,
  NxHero,
  NxMegaMenu,
  NxMenu,
  NxMobileMenu,
  NxNavMenu,
  NxPagination,
  NxPricingTable,
  NxPopover,
  NxRouteProgress,
  NxSegmentedControl,
  NxSheet,
  NxTabs,
  pageRange,
} from '../src/index';

/** Mount a component with props and slots; unmount returns the host. */
function seat(Component: Parameters<typeof createApp>[0], props: Record<string, unknown> = {}, slots: Record<string, unknown> = {}) {
  const host = document.createElement('div');
  document.body.appendChild(host);
  const app = createApp(h(Component as never, props as never, slots as never));
  app.mount(host);
  return {
    host,
    unmount: () => {
      app.unmount();
      host.remove();
    },
  };
}

describe('the navigation seat', () => {
  it('tabs switch panels and wire aria up', async () => {
    const { host, unmount } = seat(NxTabs, {
      items: [
        { value: 'a', label: 'A', content: 'alpha' },
        { value: 'b', label: 'B', content: 'beta' },
      ],
    });
    await nextTick();
    expect(host.querySelector('.nx-tabs')).toBeTruthy();
    expect(host.querySelector('.nx-tab[aria-selected="true"]')?.textContent).toContain('A');
    const panels = [...host.querySelectorAll('.nx-tab-panel')];
    expect(panels[0]!.hidden).toBe(false);
    expect(panels[1]!.hidden).toBe(true);

    (host.querySelectorAll('.nx-tab')[1] as HTMLElement).click();
    await nextTick();
    expect(panels[0]!.hidden).toBe(true);
    expect(panels[1]!.hidden).toBe(false);
    unmount();
  });

  it('a segmented control moves its radios', async () => {
    const { host, unmount } = seat(NxSegmentedControl, {
      options: [
        { value: 'one', label: 'One' },
        { value: 'two', label: 'Two' },
      ],
    });
    await nextTick();
    const radios = [...host.querySelectorAll('.nx-segment-input')] as HTMLInputElement[];
    expect(radios[0]!.checked).toBe(true);
    radios[1]!.click();
    await nextTick();
    expect(radios[1]!.checked).toBe(true);
    unmount();
  });

  it('the nav menu, dock, breadcrumbs, pagination and accordion paint their marks', async () => {
    const nav = seat(NxNavMenu, { items: [{ href: '/a', label: 'A', current: true }, { href: '/b', label: 'B' }] });
    await nextTick();
    expect(nav.host.querySelector('.nx-navmenu-link[aria-current="page"]')?.textContent).toContain('A');
    nav.unmount();

    const dock = seat(NxDock, { items: [{ label: 'Home', icon: 'home', href: '/' }, { label: 'Ping', icon: 'zap', onClick: () => {} }] });
    await nextTick();
    expect(dock.host.querySelectorAll('.nx-dock-item')).toHaveLength(2);
    dock.unmount();

    const crumbs = seat(NxBreadcrumbs, { items: [{ label: 'Root', href: '/' }, { label: 'Here' }] });
    await nextTick();
    expect(crumbs.host.querySelector('.nx-breadcrumbs [aria-current="page"]')?.textContent).toContain('Here');
    crumbs.unmount();

    const pages = seat(NxPagination, { page: 2, pageCount: 3 });
    await nextTick();
    expect(pages.host.querySelector('.nx-page-link[aria-current="page"]')?.textContent?.trim()).toBe('2');
    pages.unmount();

    const accordion = seat(NxAccordion, { items: [{ title: 'Q', content: 'A' }], defaultOpen: [0] });
    await nextTick();
    expect(accordion.host.querySelector('.nx-accordion-item')!.hasAttribute('open')).toBe(true);
    accordion.unmount();
  });

  it('pagination computes honest gaps', () => {
    expect(pageRange(1, 1)).toEqual([1]);
    expect(pageRange(4, 20, 1)).toEqual([1, 2, 3, 4, 5, null, 20]);
  });

  it('the menu augments its trigger and follows the popover signal', async () => {
    const { host, unmount } = seat(
      NxMenu,
      {
        items: [
          { label: 'Cut', shortcut: '⌘X', onSelect: () => {} },
          { type: 'separator' },
          { type: 'label', label: 'More' },
          { label: 'Delete', tone: 'danger' },
        ],
      },
      { trigger: () => h('button', { type: 'button' }, 'Actions') },
    );
    await nextTick();
    const trigger = host.querySelector('button:not(.nx-menu-item)') as HTMLElement;
    const menu = host.querySelector('.nx-menu') as HTMLElement;
    expect(trigger.getAttribute('aria-haspopup')).toBe('menu');
    expect(trigger.getAttribute('aria-expanded')).toBe('false');
    // The declarative invoker the browser toggles the menu with.
    expect(trigger.getAttribute('popovertarget')).toBe(menu.id);
    expect(menu.querySelectorAll('[role="menuitem"]')).toHaveLength(2);
    expect(menu.querySelector('.nx-menu-separator')).toBeTruthy();
    expect(menu.querySelector('.nx-menu-label')?.textContent).toContain('More');

    // happy-dom ships the Popover API surface but never fires `toggle`; speak
    // the browser's signal by hand.
    const signal = (state: string) => {
      const event = new Event('toggle') as Event & { newState?: string };
      event.newState = state;
      menu.dispatchEvent(event);
    };
    signal('open');
    await nextTick();
    expect(trigger.getAttribute('aria-expanded')).toBe('true');
    signal('closed');
    await nextTick();
    expect(trigger.getAttribute('aria-expanded')).toBe('false');
    unmount();
  });
});

describe('the overlay seat', () => {
  it('the dialog opens through its model and closes on the close button', async () => {
    const { host, unmount } = seat(NxDialog, { open: false, title: 'Hello' }, { default: () => h('p', {}, 'Body') });
    await nextTick();
    const dialog = host.querySelector('dialog.nx-dialog') as HTMLDialogElement;
    expect(dialog.open).toBe(false);
    unmount();

    const open = seat(NxDialog, { open: true, title: 'Hello' }, { default: () => h('p', {}, 'Body') });
    await nextTick();
    const el = open.host.querySelector('dialog.nx-dialog') as HTMLDialogElement;
    expect(el.querySelector('.nx-dialog-title')?.textContent).toContain('Hello');
    (el.querySelector('.nx-dialog-close') as HTMLElement).click();
    await nextTick();
    expect(el.open).toBe(false);
    open.unmount();
  });

  it('the sheet carries its variant and the palette filters its groups', async () => {
    const sheet = seat(NxSheet, { open: true }, { default: () => h('p', {}, 'Sheet body') });
    await nextTick();
    expect((sheet.host.querySelector('dialog.nx-dialog') as HTMLDialogElement).getAttribute('data-variant')).toBe('sheet');
    sheet.unmount();

    const palette = seat(NxCommandPalette, {
      open: true,
      groups: [{ label: 'Pages', items: [{ id: 'home', label: 'Home', icon: 'home' }, { id: 'set', label: 'Settings' }] }],
    });
    await nextTick();
    expect(palette.host.querySelector('.nx-command-input')).toBeTruthy();
    const input = palette.host.querySelector('.nx-command-input') as HTMLInputElement;
    input.value = 'sett';
    input.dispatchEvent(new Event('input', { bubbles: true }));
    await nextTick();
    expect(palette.host.querySelectorAll('.nx-command-item')).toHaveLength(1);
    expect(palette.host.querySelector('.nx-command-item span')?.textContent).toContain('Settings');
    palette.unmount();
  });

  it('the popover wires its trigger and follows the popover signal', async () => {
    const { host, unmount } = seat(NxPopover, { label: 'Details' }, { trigger: () => h('button', { type: 'button' }, 'More'), default: () => h('p', {}, 'Panel') });
    await nextTick();
    const trigger = host.querySelector('button') as HTMLElement;
    const panel = host.querySelector('.nx-popover') as HTMLElement;
    expect(trigger.getAttribute('aria-controls')).toBe(panel.id);
    expect(panel.getAttribute('role')).toBe('dialog');
    expect(panel.getAttribute('popover')).toBe('auto');
    // The declarative invoker the browser toggles the panel with.
    expect(trigger.getAttribute('popovertarget')).toBe(panel.id);

    // happy-dom ships the Popover API surface but never fires `toggle`; speak
    // the browser's signal by hand.
    const signal = (state: string) => {
      const event = new Event('toggle') as Event & { newState?: string };
      event.newState = state;
      panel.dispatchEvent(event);
    };
    signal('open');
    await nextTick();
    expect(trigger.getAttribute('aria-expanded')).toBe('true');
    signal('closed');
    await nextTick();
    expect(trigger.getAttribute('aria-expanded')).toBe('false');
    unmount();
  });
});

describe('the header and hero seats', () => {
  it('the header paints brand, menu, actions and the mobile drawer', async () => {
    const { host, unmount } = seat(
      NxHeader,
      {
        brand: 'Nabu',
        items: [
          { label: 'Docs', href: '/docs' },
          { label: 'Product', children: [{ label: 'Overview', href: '/p' }] },
        ],
      },
      { actions: () => h('button', { type: 'button' }, 'Sign in') },
    );
    await nextTick();
    expect(host.querySelector('.nx-header-brand')?.textContent).toContain('Nabu');
    expect(host.querySelector('.nx-mega')).toBeTruthy();
    expect(host.querySelector('.nx-mega-trigger')?.getAttribute('aria-expanded')).toBe('false');
    expect(host.querySelector('.nx-header-actions button')?.textContent).toContain('Sign in');

    (host.querySelector('.nx-header-toggle') as HTMLElement).click();
    await nextTick();
    const drawer = host.querySelector('dialog.nx-dialog[data-variant="drawer"]') as HTMLDialogElement;
    expect(drawer.querySelector('.nx-drilldown')).toBeTruthy();
    expect(drawer.querySelector('.nx-drilldown-item')?.textContent).toContain('Docs');
    unmount();
  });

  it('the mega menu opens a panel on click and the mobile menu drills down', async () => {
    const mega = seat(NxMegaMenu, {
      items: [{ label: 'Product', children: [{ label: 'Overview', href: '/p' }] }],
    });
    await nextTick();
    (mega.host.querySelector('.nx-mega-trigger') as HTMLElement).click();
    await nextTick();
    expect(mega.host.querySelector('.nx-mega-trigger')?.getAttribute('aria-expanded')).toBe('true');
    expect(mega.host.querySelector('.nx-mega-item')?.textContent).toContain('Overview');
    mega.unmount();

    const mobile = seat(NxMobileMenu, { items: [{ label: 'Product', children: [{ label: 'Overview', href: '/p' }] }] });
    await nextTick();
    (mobile.host.querySelector('.nx-drilldown-item') as HTMLElement).click();
    await nextTick();
    expect(mobile.host.querySelector('.nx-drilldown-back')?.textContent).toContain('Back');
    expect(mobile.host.querySelectorAll('.nx-drilldown-panel')[1]!.querySelector('.nx-drilldown-item')?.textContent).toContain('Overview');
    mobile.unmount();
  });

  it('route progress carries its state and the hero paints backdrop and title', async () => {
    const bar = seat(NxRouteProgress, { state: 'loading' });
    await nextTick();
    expect(bar.host.querySelector('.nx-route-progress')?.getAttribute('data-state')).toBe('loading');
    bar.unmount();

    const hero = seat(NxHero, { title: 'Build anything', eyebrow: { label: 'New', badge: 'v2' } });
    await nextTick();
    expect(hero.host.querySelector('.nx-backdrop')?.getAttribute('data-variant')).toBe('aurora');
    expect(hero.host.querySelector('.nx-hero-title .nx-text-piece')?.textContent).toContain('Build');
    expect(hero.host.querySelector('.nx-hero-eyebrow .nx-badge')?.textContent).toContain('v2');
    hero.unmount();

    const bare = seat(NxHero, { backdrop: false });
    await nextTick();
    expect(bare.host.querySelector('.nx-backdrop')).toBeNull();
    bare.unmount();

    const layer = seat(NxBackdrop, { variant: 'stars' });
    await nextTick();
    expect(layer.host.querySelector('.nx-backdrop')?.getAttribute('data-variant')).toBe('stars');
    layer.unmount();
  });
});

describe('the pricing seat', () => {
  it('switches billing periods and marks the featured plan', async () => {
    const plans = [
      {
        id: 'free',
        name: 'Free',
        price: { monthly: 0, yearly: 0 },
        features: [{ label: 'One project' }],
        cta: { label: 'Start', onClick: () => {} },
      },
      {
        id: 'pro',
        name: 'Pro',
        price: { monthly: 9, yearly: 90 },
        featured: true,
        flag: 'Most popular',
        features: [{ label: 'Everything', included: true }, { label: 'Support', included: false }],
        cta: { label: 'Go Pro', href: '/pro' },
        period: { monthly: '/mo' },
      },
    ];
    const { host, unmount } = seat(NxPricingTable, { plans, yearlyNote: '-20%' });
    await nextTick();
    expect(host.querySelectorAll('.nx-plan')).toHaveLength(2);
    expect(host.querySelector('.nx-plan[data-featured] .nx-plan-flag')?.textContent).toContain('Most popular');
    expect(host.querySelector('.nx-plan[data-featured] .nx-plan-action a.nx-button')?.getAttribute('href')).toBe('/pro');
    expect(host.querySelectorAll('.nx-plan-features li[data-excluded]')).toHaveLength(1);

    const yearly = [...host.querySelectorAll('.nx-segment-input')] as HTMLInputElement[];
    yearly[1]!.click();
    await nextTick();
    expect(host.querySelector('.nx-plan[data-featured] .nx-number')?.textContent).toContain('90');
    unmount();
  });
});
