// The five seats this run owns — navigation, overlay, header, hero, pricing —
// compiled through the same vite-plugin-svelte pipeline the build uses, then
// mounted and asserted. DOM/classes mirror packages/react/src/components;
// snippet-taking components are exercised through test/chrome-app.svelte.
import { mount, unmount } from 'svelte';
import { describe, expect, it } from 'vitest';
import App from './chrome-app.svelte';
import {
  Accordion,
  Breadcrumbs,
  NavMenu,
  Pagination,
  pageRange,
  SegmentedControl,
  Tabs,
} from '../src/components/navigation.svelte';
import { CommandPalette, Dialog, Drawer, Sheet } from '../src/components/overlay.svelte';
import { Header, MegaMenu, MobileMenu, RouteProgress, ThemeToggle } from '../src/components/header.svelte';
import { Hero, HeroBackdrop } from '../src/components/hero.svelte';
import { PricingTable, type PricingPlan } from '../src/components/pricing.svelte';

function render(Component: unknown, props: Record<string, unknown> = {}): string {
  const el = document.createElement('div');
  document.body.appendChild(el);
  const instance = mount(Component as Parameters<typeof mount>[0], { target: el, props });
  const html = el.innerHTML;
  unmount(instance);
  el.remove();
  return html;
}

describe('navigation family', () => {
  it('Tabs mirrors the React seat: pill list, aria wiring, one open panel', () => {
    const html = render(Tabs, {
      items: [
        { value: 'a b', label: 'First', content: 'Alpha body' },
        { value: 'two', label: 'Second', content: 'Beta body' },
      ],
      'aria-label': 'Sections',
    });
    // Pill is the default variant: no data-variant attribute at all.
    expect(html).not.toContain('data-variant');
    expect(html).toContain('role="tablist"');
    expect(html).toContain('aria-label="Sections"');
    expect(html).toContain('data-value="a b"');
    expect(html).toContain('id="nx-tabs-');
    // The value is id-sanitised into the aria wiring; the first tab is selected.
    expect(html).toMatch(/aria-selected="true"[^>]*aria-controls="[^"]*panel-a_b"/);
    expect(html).toContain('Alpha body');
    // The unselected panel is hidden.
    expect(html).toMatch(/role="tabpanel"[^>]*hidden[^>]*>|hidden[^>]*role="tabpanel"/);
    expect(html).toContain('Beta body');
  });

  it('SegmentedControl renders a radiogroup with the first option checked', () => {
    const html = render(SegmentedControl, {
      options: [
        { value: 'm', label: 'Monthly' },
        { value: 'y', label: 'Yearly' },
      ],
    });
    expect(html).toContain('role="radiogroup"');
    expect(html).toContain('type="radio"');
    expect(html).toMatch(/checked[^>]*\/?>|value="m"/);
    expect(html).toContain('Monthly');
  });

  it('NavMenu marks the current page and Breadcrumbs labels itself', () => {
    const nav = render(NavMenu, {
      items: [
        { href: '/', label: 'Home' },
        { href: '/docs', label: 'Docs', current: true },
      ],
    });
    expect(nav).toContain('class="nx-navmenu-link"');
    expect(nav).toMatch(/href="\/docs"[^>]*aria-current="page"|aria-current="page"[^>]*href="\/docs"/);

    const crumbs = render(Breadcrumbs, { items: [{ label: 'Home', href: '/' }, { label: 'Docs' }] });
    expect(crumbs).toContain('aria-label="Breadcrumb"');
    expect(crumbs).toMatch(/aria-current="page"/);
  });

  it('pageRange folds the gaps the React way', () => {
    expect(pageRange(5, 20)).toEqual([1, null, 4, 5, 6, null, 20]);
    expect(pageRange(1, 1)).toEqual([1]);
    expect(pageRange(2, 3)).toEqual([1, 2, 3]);
  });

  it('Pagination wires the current page and prev/next labels', () => {
    const html = render(Pagination, { page: 5, pageCount: 20 });
    expect(html).toContain('aria-label="Pagination"');
    expect(html).toMatch(/aria-current="page"/);
    expect(html).toContain('aria-label="Previous"');
    expect(html).toContain('aria-label="Next"');
    expect(html).toContain('data-gap');
  });

  it('Accordion opens the seeded item and names its exclusive group', () => {
    const html = render(Accordion, {
      items: [
        { title: 'One', content: 'First answer' },
        { title: 'Two', content: 'Second answer' },
      ],
      defaultOpen: [0],
    });
    expect(html).toContain('<details class="nx-accordion-item" name="nx-acc-');
    expect(html).toMatch(/open/);
    expect(html).toContain('First answer');
  });
});

describe('overlay family', () => {
  it('Dialog renders the closed element with header wiring and a close button', () => {
    const html = render(Dialog, { title: 'Settings', description: 'Change things' });
    expect(html).toContain('class="nx-dialog"');
    expect(html).toMatch(/aria-labelledby="nx-dialog-[^"]*title"/);
    expect(html).toMatch(/aria-describedby="nx-dialog-[^"]*description"/);
    expect(html).toContain('class="nx-dialog-title"');
    expect(html).toContain('aria-label="Close"');
  });

  it('Drawer and Sheet pin their variants', () => {
    expect(render(Drawer, {})).toContain('data-variant="drawer"');
    expect(render(Sheet, {})).toContain('data-variant="sheet"');
  });

  it('CommandPalette mounts closed with the search combobox inside', () => {
    const html = render(CommandPalette, {
      groups: [{ label: 'Pages', items: [{ id: 'home', label: 'Home' }] }],
    });
    expect(html).toContain('nx-command');
    expect(html).toContain('role="combobox"');
    expect(html).toContain('aria-label="Search"');
  });
});

describe('header family', () => {
  it('MegaMenu renders triggers for panel items and links for plain ones', () => {
    const html = render(MegaMenu, {
      items: [
        {
          label: 'Product',
          children: [{ label: 'Overview', href: '/p' }],
        },
        { label: 'Pricing', href: '/pricing', current: true },
      ],
    });
    expect(html).toContain('class="nx-mega-trigger"');
    expect(html).toContain('aria-expanded="false"');
    expect(html).toContain('class="nx-mega-link"');
    expect(html).toContain('aria-current="page"');
    expect(html).toContain('class="nx-mega-viewport" data-state="closed"');
  });

  it('MobileMenu lists the items with their staggered indexes', () => {
    const html = render(MobileMenu, {
      items: [
        { label: 'A', children: [{ label: 'A1', href: '/a1' }] },
        { label: 'B', href: '/b' },
      ],
    });
    expect(html).toContain('nx-drilldown');
    expect(html).toContain('--nx-i: 0');
    expect(html).toContain('href="/b"');
  });

  it('Header carries brand, mega menu, toggle and the mobile drawer', () => {
    const html = render(Header, {
      brand: 'Nabu',
      items: [{ label: 'Docs', href: '/docs' }],
    });
    expect(html).toContain('class="nx-header"');
    expect(html).toContain('class="nx-header-brand"');
    expect(html).toContain('class="nx-mega"');
    expect(html).toContain('aria-label="Menu"');
    expect(html).toContain('data-variant="drawer"');
  });

  it('RouteProgress and ThemeToggle render their states', () => {
    expect(render(RouteProgress, { state: 'loading' })).toContain('data-state="loading"');
    const toggle = render(ThemeToggle, {});
    expect(toggle).toContain('class="nx-button nx-theme-toggle"');
    expect(toggle).toContain('aria-label="Dark mode"');
    expect(toggle).toContain('nx-theme-sun');
  });
});

describe('hero family', () => {
  it('HeroBackdrop is a hidden, variant-carrying decoration', () => {
    const html = render(HeroBackdrop, { variant: 'stars' });
    expect(html).toContain('class="nx-backdrop"');
    expect(html).toContain('data-variant="stars"');
    expect(html).toContain('aria-hidden="true"');
    expect((html.match(/<i><\/i>/g) ?? []).length).toBe(3);
  });

  it('Hero strings become word-by-word reveals; the backdrop sits first', () => {
    const html = render(Hero, { title: 'Build anything', backdrop: 'grid' });
    expect(html).toMatch(/<h1 class="nx-text-reveal nx-hero-title"/);
    expect(html).toContain('data-nx-reveal="group"');
    expect(html.indexOf('nx-backdrop')).toBeLessThan(html.indexOf('nx-hero-content'));
  });
});

describe('pricing family', () => {
  const plans: PricingPlan[] = [
    {
      id: 'free',
      name: 'Free',
      price: { monthly: 0, yearly: 0 },
      features: [{ label: 'One project' }, { label: 'Support', included: false }],
      cta: { label: 'Start' },
    },
    {
      id: 'pro',
      name: 'Pro',
      price: { monthly: 12, yearly: 120 },
      featured: true,
      flag: 'Most popular',
      currency: '$',
      features: [{ label: 'Unlimited' }],
      cta: { label: 'Upgrade', href: '/upgrade' },
    },
  ];

  it('PricingTable renders plans, the toggle and the featured ribbon', () => {
    const html = render(PricingTable, { plans });
    expect(html).toContain('class="nx-pricing"');
    expect(html).toContain('class="nx-pricing-toggle"');
    expect(html).toContain('role="radiogroup"');
    expect((html.match(/class="nx-plan"/g) ?? []).length).toBe(2);
    expect(html).toContain('data-featured');
    expect(html).toContain('class="nx-plan-flag"');
    expect(html).toContain('Most popular');
    expect(html).toContain('class="nx-plan-currency"');
    expect(html).toContain('data-excluded');
    expect(html).toMatch(/class="nx-button"[^>]*data-variant="primary"|data-variant="primary"[^>]*class="nx-button"/);
  });
});

describe('the snippet-taking components, through the real app', () => {
  it('hands the popover and aria wiring to the trigger via snippet attrs', () => {
    const el = document.createElement('div');
    document.body.appendChild(el);
    const instance = mount(App, { target: el });
    const html = el.innerHTML;

    // Menu: the app's button carries the aria state and the popover invoker.
    const trigger = el.querySelector<HTMLButtonElement>('button.trigger');
    expect(trigger?.getAttribute('aria-haspopup')).toBe('menu');
    expect(trigger?.getAttribute('aria-controls')).toMatch(/^nx-menu-/);
    expect(trigger?.getAttribute('popovertarget')).toMatch(/^nx-menu-/);
    expect(el.querySelector('[data-nx-menu-trigger]')).toBe(trigger);

    // The menu itself renders with its items.
    expect(html).toContain('role="menu"');
    expect(html).toContain('class="nx-menu-separator" role="separator"');
    expect(html).toContain('class="nx-menu-label"');
    expect(html).toContain('data-tone="danger"');

    // Popover: same contract on its own trigger.
    const popTrigger = el.querySelector<HTMLButtonElement>('button.pop-trigger');
    expect(popTrigger?.getAttribute('aria-controls')).toMatch(/^nx-pop-/);
    expect(el.querySelector('.nx-popover')?.getAttribute('popover')).toBe('auto');

    unmount(instance);
    el.remove();
  });
});
