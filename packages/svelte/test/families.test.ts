// The four family seats this run owns — card, chart, display, feedback —
// compiled through the same vite-plugin-svelte pipeline the build uses, then
// mounted and asserted. DOM/classes mirror packages/react/src/components;
// snippet-taking components are exercised through test/families-app.svelte.
import { mount, unmount } from 'svelte';
import { describe, expect, it } from 'vitest';
import App from './families-app.svelte';
import { Card, StatCard } from '../src/components/card.svelte';
import { AreaChart, BarChart, DonutChart, ProgressRing, Sparkline } from '../src/components/chart.svelte';
import { Alert, DotsLoader, Progress, Skeleton, Spinner, Toaster } from '../src/components/feedback.svelte';
import { Avatar, AvatarGroup, Marquee, Rating, Steps } from '../src/components/display.svelte';
import { SwipeStackFor } from '../src/components/card.svelte';

function render(Component: unknown, props: Record<string, unknown> = {}): string {
  const el = document.createElement('div');
  document.body.appendChild(el);
  const instance = mount(Component as Parameters<typeof mount>[0], { target: el, props });
  const html = el.innerHTML;
  unmount(instance);
  el.remove();
  return html;
}

describe('the snippet-taking components, through the real app', () => {
  it('renders Card children, FlipCard faces, StackCards items, SwipeStack cards', () => {
    const el = document.createElement('div');
    document.body.appendChild(el);
    const instance = mount(App, { target: el });
    const html = el.innerHTML;
    unmount(instance);
    el.remove();

    expect(html).toContain('the body');
    expect(html).toContain('href="https://example.com"');
    expect(html).toContain('front face');
    expect(html).toContain('back face');
    expect(html).toContain('nx-flip');
    expect(html).toContain('stack-one');
    expect(html).toContain('--nx-count: 2');
    expect(html).toContain('card of Ann');
    expect(html).toContain('nx-swipe-card');
    expect(html).toContain('nx-swipe-actions');
    expect(html).toContain('Gold badge');
    expect(html).toContain('nx-kbd');
    expect(html).toContain('--nx-grid-min: 12rem');
    expect(html).toContain('--nx-cols: 4');
    expect(html).toContain('nx-bento-item');
    // Tooltip wiring: the trigger carries aria-describedby of the tip.
    expect(html).toMatch(/class="trigger"[^>]*aria-describedby|aria-describedby[^>]*class="trigger"/);
    expect(html).toContain('role="tooltip"');
  });
});

describe('card family', () => {
  it('Card renders the header, variant and footer without children', () => {
    const html = render(Card, { icon: 'star', title: 'Hi', description: 'There', footer: 'Bye', variant: 'glass' });
    expect(html).toContain('nx-card');
    expect(html).toContain('data-variant="glass"');
    expect(html).toContain('nx-card-icon');
    expect(html).toContain('nx-card-title');
    expect(html).toContain('nx-card-footer');
    expect(html).not.toContain('nx-card-body');
  });

  it('StatCard rolls the number and marks the delta trend', () => {
    const html = render(StatCard, { label: 'Revenue', value: 1284, delta: 12.5, trend: [1, 2, 3, 4] });
    expect(html).toContain('nx-stat');
    expect(html).toContain('nx-delta');
    expect(html).toContain('data-trend="up"');
    expect(html).toContain('nx-digit');
    expect(html).toContain('nx-sparkline');
  });

  it('SwipeStackFor hands back a typed component', () => {
    const Stack = SwipeStackFor<{ id: number }>();
    expect(typeof Stack).toBe('function');
  });
});

describe('chart family', () => {
  it('BarChart draws bars, gridlines and the screen-reader table', () => {
    const html = render(BarChart, { data: [{ label: 'Mon', value: 4 }, { label: 'Tue', value: 8 }], title: 'Sales' });
    expect(html).toContain('data-type="bar"');
    expect(html).toContain('nx-chart-bar');
    expect(html).toContain('nx-chart-plot');
    expect(html).toContain('nx-visually-hidden');
  });

  it('AreaChart draws the wash, the line and the points', () => {
    const html = render(AreaChart, {
      labels: ['a', 'b', 'c'],
      series: [{ name: 'One', values: [1, 2, 3] }],
    });
    expect(html).toContain('data-type="area"');
    expect(html).toContain('nx-chart-area');
    expect(html).toContain('nx-chart-line');
    expect(html).toContain('nx-chart-point');
  });

  it('DonutChart renders segments, centre and legend', () => {
    const html = render(DonutChart, { data: [{ label: 'A', value: 2 }, { label: 'B', value: 3 }], title: 'Split' });
    expect(html).toContain('data-type="donut"');
    expect(html).toContain('nx-donut-segment');
    expect(html).toContain('nx-donut-center');
    expect(html).toContain('nx-legend-item');
  });

  it('Sparkline and ProgressRing draw their marks', () => {
    expect(render(Sparkline, { data: [1, 3, 2, 5] })).toContain('nx-sparkline');
    const ring = render(ProgressRing, { value: 41 });
    expect(ring).toContain('nx-ring-progress');
    expect(ring).toContain('41%');
  });
});

describe('feedback family', () => {
  it('Alert carries tone, icon, description and a dismiss button', () => {
    const html = render(Alert, { tone: 'success', title: 'Saved', dismissible: true });
    expect(html).toContain('data-tone="success"');
    expect(html).toContain('nx-alert-icon');
    expect(html).toContain('nx-alert-close');
  });

  it('Progress, Skeleton, Spinner, DotsLoader render their marks', () => {
    expect(render(Progress, { value: 50 })).toContain('nx-progress');
    expect(render(Skeleton, { lines: 3 })).toContain('nx-skeleton');
    expect(render(Spinner, {})).toContain('nx-spinner');
    expect(render(DotsLoader, {})).toContain('nx-dots');
  });

  it('Toaster renders the popover section with an empty queue', () => {
    const html = render(Toaster, {});
    expect(html).toContain('nx-toaster');
    expect(html).toContain('popover');
    expect(html).toContain('nx-toast-list');
  });
});

describe('display family', () => {
  it('Avatar falls back to initials', () => {
    const html = render(Avatar, { name: 'Nabu Kade' });
    expect(html).toContain('nx-avatar');
    expect(html).toContain('NK');
  });

  it('AvatarGroup truncates with +N and wraps each in a Tooltip', () => {
    const html = render(AvatarGroup, {
      people: [{ name: 'Ann A' }, { name: 'Bob B' }, { name: 'Cat C' }],
      max: 2,
    });
    expect(html).toContain('nx-avatar-group');
    expect(html).toContain('nx-avatar-more');
    expect(html).toContain('+1');
    expect(html).toContain('role="tooltip"');
  });

  it('Rating, Steps, Marquee render their marks', () => {
    expect(render(Rating, { defaultValue: 3 })).toContain('nx-rating-input');
    expect(render(Steps, { steps: [{ title: 'One' }, { title: 'Two' }], current: 0 })).toContain('data-status="current"');
    expect(render(Marquee, { items: ['a', 'b'], controls: true })).toContain('nx-marquee-toggle');
  });
});
