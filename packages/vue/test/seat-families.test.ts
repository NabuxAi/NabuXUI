import { createApp, h, nextTick } from 'vue';
import { describe, expect, it } from 'vitest';
import {
  NxAlert,
  NxAreaChart,
  NxAvatar,
  NxAvatarGroup,
  NxBadge,
  NxBarChart,
  NxBento,
  NxBentoItem,
  NxCard,
  NxCuneiformLoader,
  NxDivider,
  NxDotsLoader,
  NxDonutChart,
  NxFlipCard,
  NxGrid,
  NxKbd,
  NxMarquee,
  NxOrbitLoader,
  NxProgress,
  NxProgressRing,
  NxRating,
  NxSkeleton,
  NxSparkline,
  NxSpinner,
  NxStackCards,
  NxStatCard,
  NxSteps,
  NxSwipeStack,
  NxToaster,
  NxTooltip,
} from '../src/index';

/** Mount a component with props and slots; unmount and return the host. */
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

describe('card family seat', () => {
  it('renders the article with variant, size and header seats', () => {
    const view = seat(NxCard, { variant: 'glass', size: 'sm', interactive: true, title: 'Studio', description: 'The quiet deck' }, { footer: () => 'footer seat' });
    const el = view.host.querySelector('article')!;
    expect(el.className).toBe('nx-card');
    expect(el.getAttribute('data-variant')).toBe('glass');
    expect(el.getAttribute('data-size')).toBe('sm');
    expect(el.getAttribute('data-interactive')).toBe('');
    expect(el.querySelector('.nx-card-title')?.textContent).toBe('Studio');
    expect(el.querySelector('.nx-card-description')?.textContent).toBe('The quiet deck');
    expect(el.querySelector('.nx-card-footer')?.textContent).toBe('footer seat');
    view.unmount();
  });

  it('renders a link card through the same classes', () => {
    const view = seat(NxCard, { href: '/pricing', spotlight: true }, { default: () => h('p', 'body') });
    const link = view.host.querySelector('a.nx-card')!;
    expect(link.getAttribute('href')).toBe('/pricing');
    expect(link.getAttribute('data-spotlight')).toBe('');
    expect(link.querySelector('.nx-card-body p')?.textContent).toBe('body');
    view.unmount();
  });

  it('flip card turns on click and keeps the corner button', async () => {
    const view = seat(NxFlipCard, { trigger: 'click' }, { front: () => 'F', back: () => 'B' });
    const root = view.host.firstElementChild as HTMLElement;
    expect(root.className).toBe('nx-flip');
    expect(root.getAttribute('data-trigger')).toBe('click');
    const button = root.querySelector<HTMLButtonElement>('button.nx-button')!;
    expect(button.getAttribute('aria-pressed')).toBe('false');
    button.click();
    await nextTick();
    expect(root.getAttribute('data-flipped')).toBe('');
    view.unmount();
  });

  it('stack cards count their items', () => {
    const view = seat(NxStackCards, { items: ['a', 'b', 'c'], overlap: '-55%' });
    const list = view.host.querySelector('ul.nx-stack')!;
    expect(list.style.getPropertyValue('--nx-count')).toBe('3');
    expect(list.style.getPropertyValue('--nx-stack-overlap')).toBe('-55%');
    expect(list.querySelectorAll('li.nx-stack-item')).toHaveLength(3);
    view.unmount();
  });

  it('swipe stack renders the top card and the two verdict buttons', () => {
    const view = seat(NxSwipeStack, { items: [1, 2], renderItem: (n: number) => `card ${n}` });
    const cards = view.host.querySelectorAll('article.nx-swipe-card');
    expect(cards.length).toBeGreaterThan(0);
    expect(cards[0]!.getAttribute('data-state')).toBe('top');
    expect(cards[0]!.textContent).toContain('card 1');
    const actions = view.host.querySelectorAll('.nx-swipe-actions button.nx-button');
    expect(actions).toHaveLength(2);
    view.unmount();
  });

  it('stat card paints the delta and the ticking value', () => {
    const view = seat(NxStatCard, { label: 'Revenue', value: 1284, delta: 12.5, trend: [1, 2, 3, 4] });
    const el = view.host.querySelector('article.nx-stat')!;
    expect(el.querySelector('.nx-stat-label')?.textContent).toBe('Revenue');
    expect(el.querySelector('.nx-delta')?.getAttribute('data-trend')).toBe('up');
    expect(el.querySelector('.nx-number')).toBeTruthy();
    expect(el.querySelector('svg.nx-sparkline')).toBeTruthy();
    view.unmount();
  });
});

describe('chart family seat', () => {
  it('bar chart draws bars, gridlines and the screen-reader table', () => {
    const view = seat(NxBarChart, { title: 'Sales', data: [
      { label: 'Q1', value: 4 },
      { label: 'Q2', value: 9 },
    ] });
    const figure = view.host.querySelector('figure.nx-chart')!;
    expect(figure.getAttribute('data-type')).toBe('bar');
    expect(figure.getAttribute('data-nx-reveal')).toBe('');
    expect(figure.querySelectorAll('path.nx-chart-bar')).toHaveLength(2);
    expect(figure.querySelector('.nx-chart-title')?.textContent).toBe('Sales');
    expect(figure.querySelector('table.nx-visually-hidden th[scope="row"]')?.textContent).toBe('Q1');
    view.unmount();
  });

  it('area chart draws lines, area wash and points', () => {
    const view = seat(NxAreaChart, { labels: ['a', 'b', 'c'], series: [{ name: 'one', values: [1, 2, 3] }] });
    const svg = view.host.querySelector('svg.nx-chart-svg')!;
    expect(view.host.querySelector('figure.nx-chart')!.getAttribute('data-type')).toBe('area');
    expect(svg.querySelectorAll('path.nx-chart-line')).toHaveLength(1);
    expect(svg.querySelectorAll('path.nx-chart-area')).toHaveLength(1);
    expect(svg.querySelectorAll('circle.nx-chart-point')).toHaveLength(3);
    view.unmount();
  });

  it('donut chart draws a segment per datum with the legend shares', () => {
    const view = seat(NxDonutChart, { data: [
      { label: 'Lapis', value: 2 },
      { label: 'Gold', value: 6 },
    ] });
    expect(view.host.querySelectorAll('circle.nx-donut-segment')).toHaveLength(2);
    const legend = view.host.querySelectorAll('li.nx-legend-item');
    expect(legend[1]!.textContent).toContain('Gold');
    expect(legend[1]!.textContent).toContain('75%');
    view.unmount();
  });

  it('sparkline and ring keep their marks', () => {
    const spark = seat(NxSparkline, { data: [1, 3, 2, 5] });
    expect(spark.host.querySelector('path.nx-chart-line')?.getAttribute('d')?.length).toBeGreaterThan(4);
    spark.unmount();

    const ring = seat(NxProgressRing, { value: 41, label: 'Uploading' });
    const progress = ring.host.querySelector('progress.nx-ring-progress')!;
    expect(progress.getAttribute('value')).toBe('41');
    expect(progress.getAttribute('aria-label')).toBe('Uploading');
    expect(ring.host.querySelector('.nx-ring-label')?.textContent).toBe('41%');
    ring.unmount();
  });
});

describe('display family seat', () => {
  it('avatar shows initials and status', () => {
    const view = seat(NxAvatar, { name: 'Seta Blackwood', status: 'online' });
    const el = view.host.querySelector('span.nx-avatar')!;
    expect(el.getAttribute('role')).toBe('img');
    expect(el.getAttribute('aria-label')).toBe('Seta Blackwood (online)');
    expect(el.getAttribute('data-status')).toBe('online');
    expect(el.textContent).toBe('SB');
    view.unmount();
  });

  it('avatar group folds the overflow into a +N seat with tooltips', () => {
    const view = seat(NxAvatarGroup, {
      people: [{ name: 'Ada' }, { name: 'Bey' }, { name: 'Cy' }],
      max: 2,
    });
    expect(view.host.querySelectorAll('.nx-avatar-group .nx-avatar:not(.nx-avatar-more)')).toHaveLength(2);
    const more = view.host.querySelector('.nx-avatar-more')!;
    expect(more.getAttribute('aria-label')).toBe('1 more');
    expect(view.host.querySelectorAll('.nx-tooltip')).toHaveLength(2);
    view.unmount();
  });

  it('kbd, steps, divider and grid/bento keep their seats', () => {
    const kbd = seat(NxKbd, {}, { default: () => '⌘K' });
    expect(kbd.host.querySelector('kbd.nx-kbd')?.textContent).toBe('⌘K');
    kbd.unmount();

    const steps = seat(NxSteps, { steps: [{ title: 'Cart' }, { title: 'Pay' }, { title: 'Done' }], current: 1 });
    const items = steps.host.querySelectorAll('li.nx-step');
    expect(items[0]!.getAttribute('data-status')).toBe('complete');
    expect(items[1]!.getAttribute('data-status')).toBe('current');
    expect(items[1]!.getAttribute('aria-current')).toBe('step');
    expect(items[2]!.getAttribute('data-status')).toBe('upcoming');
    steps.unmount();

    const grid = seat(NxGrid, { min: '16rem' }, { default: () => h('div') });
    expect(grid.host.querySelector('div.nx-grid')!.getAttribute('data-nx-reveal')).toBe('group');
    grid.unmount();

    const bento = seat(NxBento, { columns: 4 }, { default: () => h(NxBentoItem, { title: 'Tile', colSpan: 2 }) });
    expect(bento.host.querySelector('.nx-bento')!.style.getPropertyValue('--nx-cols')).toBe('4');
    const tile = bento.host.querySelector('.nx-bento-item')!;
    expect(tile.className).toContain('nx-spotlight');
    expect(tile.querySelector('.nx-bento-title')?.textContent).toBe('Tile');
    bento.unmount();

    const divider = seat(NxDivider, {}, { default: () => 'or' });
    expect(divider.host.querySelector('.nx-divider')?.getAttribute('role')).toBe('separator');
    divider.unmount();
  });

  it('rating is a radio group bound by v-model', async () => {
    let chosen = 0;
    const view = seat({
      setup: () => () => h(NxRating, { max: 5, 'onUpdate:value': (v: number) => (chosen = v) }),
    });
    const inputs = view.host.querySelectorAll('input.nx-rating-input');
    expect(inputs).toHaveLength(5);
    expect(inputs[0]!.getAttribute('name')).toBeTruthy();
    inputs[2]!.click();
    await nextTick();
    expect(chosen).toBe(3);
    expect(inputs[2]!.checked).toBe(true);
    view.unmount();
  });

  it('marquee loops a second inert copy and can pause', async () => {
    const view = seat(NxMarquee, { items: ['one', 'two'] });
    const groups = view.host.querySelectorAll('ul.nx-marquee-group');
    expect(groups).toHaveLength(2);
    expect(groups[1]!.getAttribute('aria-hidden')).toBe('true');
    const toggle = view.host.querySelector<HTMLButtonElement>('button.nx-marquee-toggle')!;
    expect(toggle.getAttribute('aria-pressed')).toBe('false');
    toggle.click();
    await nextTick();
    expect(view.host.querySelector('.nx-marquee')!.getAttribute('data-paused')).toBe('');
    view.unmount();
  });
});

describe('feedback family seat', () => {
  it('badge carries tone, dot and pulse', () => {
    const view = seat(NxBadge, { tone: 'success', variant: 'solid', dot: true, pulse: true }, { default: () => 'live' });
    const el = view.host.querySelector('span.nx-badge')!;
    expect(el.getAttribute('data-tone')).toBe('success');
    expect(el.getAttribute('data-variant')).toBe('solid');
    expect(el.getAttribute('data-dot')).toBe('');
    expect(el.getAttribute('data-pulse')).toBe('');
    view.unmount();
  });

  it('alert shows the tone icon and a dismiss path', () => {
    const view = seat(NxAlert, { tone: 'warning', title: 'Heads up', dismissible: true }, { default: () => 'Check the log' });
    const el = view.host.querySelector('div.nx-alert')!;
    expect(el.getAttribute('data-tone')).toBe('warning');
    expect(el.querySelector('.nx-alert-icon svg.nx-icon')).toBeTruthy();
    expect(el.querySelector('.nx-alert-close')).toBeTruthy();
    view.unmount();
  });

  it('progress is indeterminate without a value, toned otherwise', () => {
    const idle = seat(NxProgress, {});
    expect(idle.host.querySelector('progress.nx-progress')!.hasAttribute('value')).toBe(false);
    idle.unmount();

    const toned = seat(NxProgress, { value: 30, max: 60, tone: 'danger', label: 'Upload' });
    const el = toned.host.querySelector('progress.nx-progress')!;
    expect(el.getAttribute('value')).toBe('30');
    expect(el.getAttribute('data-tone')).toBe('danger');
    expect(el.getAttribute('aria-label')).toBe('Upload');
    toned.unmount();
  });

  it('skeleton repeats its lines and the loaders keep their marks', () => {
    const lines = seat(NxSkeleton, { lines: 3 });
    expect(lines.host.querySelectorAll('span.nx-skeleton')).toHaveLength(3);
    lines.unmount();

    const orbit = seat(NxOrbitLoader);
    expect(orbit.host.querySelectorAll('g.nx-orbit-ring')).toHaveLength(3);
    expect(orbit.host.querySelector('.nx-orbit')!.getAttribute('role')).toBe('status');
    orbit.unmount();

    const dots = seat(NxDotsLoader);
    expect(dots.host.querySelectorAll('.nx-dots i')).toHaveLength(3);
    dots.unmount();

    const wedges = seat(NxCuneiformLoader, { size: 'lg' });
    expect(wedges.host.querySelectorAll('path.nx-wedge')).toHaveLength(4);
    wedges.unmount();
  });

  it('spinner labels itself for screen readers', () => {
    const view = seat(NxSpinner, {});
    const el = view.host.querySelector('progress.nx-spinner')!;
    expect(el.getAttribute('aria-label')).toBe('Loading');
    view.unmount();
  });

  it('tooltip describes its single child without a wrapper', () => {
    const view = seat(NxTooltip, { content: 'Save the draft' }, { default: () => h('button', { type: 'button' }, 'Save') });
    const trigger = view.host.querySelector('button')!;
    expect(trigger.getAttribute('aria-describedby')).toBeTruthy();
    const tip = view.host.querySelector('.nx-tooltip')!;
    expect(tip.getAttribute('role')).toBe('tooltip');
    expect(tip.textContent).toBe('Save the draft');
    view.unmount();
  });

  it('toaster renders the live region over the shared store', () => {
    const view = seat(NxToaster, { position: 'top-start' });
    const section = view.host.querySelector('section.nx-toaster')!;
    expect(section.getAttribute('data-position')).toBe('top-start');
    expect(section.querySelector('ol.nx-toast-list')!.getAttribute('aria-live')).toBe('polite');
    view.unmount();
  });
});
