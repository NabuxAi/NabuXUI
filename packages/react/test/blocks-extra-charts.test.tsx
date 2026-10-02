// @vitest-environment happy-dom
/**
 * The CSS chart blocks: BarColumns (grouped/stacked bars with their hidden
 * data table), DonutRing (segments, legend, rolling centre) and Gauge (zones,
 * needle angle, spoken reading) — in several locales plus an unknown one.
 */
import { afterEach, describe, expect, it } from 'vitest';
import { cleanup, render, screen, within } from '@testing-library/react';
import type { Locale } from '@nabuxai/ui-core';
import { BarColumns } from '../src/blocks/bar-chart';
import { DonutRing } from '../src/blocks/donut-chart';
import { Gauge } from '../src/blocks/gauge';
import { NabuXUIProvider } from '../src/internal/provider';

afterEach(cleanup);

/* ---- BarColumns --------------------------------------------------------------------------- */

describe('BarColumns', () => {
  it('renders the data shorthand as focusable groups over a nice-ticks ladder', () => {
    const { container } = render(<BarColumns data={{ Mon: 4, Tue: 8 }} />);
    expect(screen.getByRole('img', { name: 'Mon: 4' })).toBeTruthy();
    expect(screen.getByRole('img', { name: 'Tue: 8' })).toBeTruthy();

    // niceTicks(0, 8, 4) → 0, 2, 4, 6, 8.
    const ticks = Array.from(container.querySelectorAll('.nx-bar-chart-tick')).map((tick) => tick.textContent);
    expect(ticks).toEqual(['0', '2', '4', '6', '8']);

    // Bar heights ride custom properties: 4/8 → 50%, 8/8 → 100%.
    const bars = container.querySelectorAll('.nx-bar-chart-bar');
    expect(bars[0]?.getAttribute('style')).toMatch(/--_v:\s*50/);
    expect(bars[1]?.getAttribute('style')).toMatch(/--_v:\s*100/);

    // The same data ships as a visually-hidden table.
    const table = screen.getByRole('table');
    expect(within(table).getByText('Value')).toBeTruthy(); // i18n.en.barChartSeries for an unnamed series
    expect(within(table).getAllByRole('row')).toHaveLength(3); // header + Mon + Tue
    expect(within(table).getByRole('row', { name: /Mon/ }).textContent).toContain('4');
    expect(within(table).getByRole('row', { name: /Tue/ }).textContent).toContain('8');
  });

  it('names its series, stacks them, and adds the total row to the tooltip', () => {
    const { container } = render(
      <BarColumns
        labels={['Mon', 'Tue']}
        series={[
          { name: 'Web', values: [4, 2] },
          { name: 'Mobile', values: [8, 6] },
        ]}
        stacked
      />,
    );
    expect(container.querySelector('.nx-chart-legend')?.textContent).toContain('Web'); // the legend
    expect(container.querySelector('.nx-chart-legend')?.textContent).toContain('Mobile');
    expect(screen.getByRole('img', { name: 'Mon: Web 4 · Mobile 8' })).toBeTruthy();
    const tooltip = container.querySelector('.nx-bar-chart-tip');
    expect(tooltip?.textContent).toContain('Total'); // i18n.en.barChartTotal
    expect(tooltip?.textContent).toContain('12'); // Mon's stack: 4 + 8
  });

  it('speaks Persian through the provider, digits in the hidden table', () => {
    render(
      <NabuXUIProvider locale="fa">
        <BarColumns data={{ Mon: 4, Tue: 8 }} />
      </NabuXUIProvider>,
    );
    const table = screen.getByRole('table');
    expect(within(table).getByText('مقدار')).toBeTruthy(); // i18n.fa.barChartSeries
    expect(within(table).getByRole('row', { name: /Mon/ }).textContent).toContain('۴');
    expect(within(table).getByRole('row', { name: /Tue/ }).textContent).toContain('۸');
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <BarColumns data={{ Mon: 4 }} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(within(screen.getByRole('table')).getByText('Value')).toBeTruthy(); // the English table
  });
});

/* ---- DonutRing ---------------------------------------------------------------------------- */

describe('DonutRing', () => {
  const data = [
    { label: 'Web', value: 12 },
    { label: 'Mobile', value: 6 },
    { label: 'API', value: 2 },
  ];

  it('renders the ring, the rolling centre, the legend, and the hidden table', () => {
    const { container } = render(<DonutRing data={data} />);
    expect(container.querySelectorAll('.nx-donut-chart-segment')).toHaveLength(3);
    const center = container.querySelector('.nx-donut-chart-center')!;
    expect(center.querySelector('.nx-visually-hidden')?.textContent).toBe('20'); // 12 + 6 + 2
    expect(center.querySelector('.nx-donut-chart-caption')?.textContent).toBe('Total'); // i18n.en.donutChartTotal

    const rows = container.querySelectorAll('.nx-donut-chart-row');
    expect(rows[0]?.querySelector('.nx-donut-chart-row-label')?.textContent).toBe('Web');
    expect(rows[0]?.querySelector('.nx-donut-chart-row-value')?.textContent).toBe('12');
    expect(rows[0]?.querySelector('.nx-donut-chart-row-share .nx-visually-hidden')?.textContent).toBe('60%'); // 12/20

    const table = screen.getByRole('table');
    expect(within(table).getByText('Value')).toBeTruthy(); // i18n.en.donutChartValue
    expect(within(table).getByText('Share')).toBeTruthy(); // i18n.en.donutChartShare
    const webRow = within(table).getByRole('row', { name: /Web/ });
    expect(webRow.textContent).toContain('12');
    expect(webRow.textContent).toContain('60%');
  });

  it('takes its centre from props and hides the caption on an empty string', () => {
    const { container } = render(<DonutRing data={data} centerValue={99} centerLabel="Sessions" />);
    const center = container.querySelector('.nx-donut-chart-center')!;
    expect(center.querySelector('.nx-visually-hidden')?.textContent).toBe('99');
    expect(center.querySelector('.nx-donut-chart-caption')?.textContent).toBe('Sessions');
    cleanup();

    const bare = render(<DonutRing data={data} centerLabel="" />);
    expect(bare.container.querySelector('.nx-donut-chart-caption')).toBeNull(); // '' drops it
  });

  it('speaks Persian through the provider', () => {
    const { container } = render(
      <NabuXUIProvider locale="fa">
        <DonutRing data={data} />
      </NabuXUIProvider>,
    );
    expect(container.querySelector('.nx-donut-chart-caption')?.textContent).toBe('جمع'); // i18n.fa.donutChartTotal
    const table = screen.getByRole('table');
    expect(within(table).getByText('مقدار')).toBeTruthy(); // i18n.fa.donutChartValue
    expect(within(table).getByText('سهم')).toBeTruthy(); // i18n.fa.donutChartShare
    expect(within(table).getByRole('row', { name: /Web/ }).textContent).toContain('۱۲');
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <DonutRing data={data} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(screen.getByText('Total')).toBeTruthy(); // the English table's caption
  });
});

/* ---- Gauge --------------------------------------------------------------------------------- */

describe('Gauge', () => {
  it('lands the needle in the matching zone and speaks the reading', () => {
    const { container } = render(<Gauge value={62} unit="%" />);
    const figure = container.querySelector('figure.nx-gauge')!;
    expect(figure.getAttribute('data-tone')).toBe('warning'); // 62 sits in the middle third
    expect(figure.getAttribute('style')).toMatch(/--_sweep:\s*62/); // 62% of the arc
    expect(figure.getAttribute('style')).toMatch(/--_angle:\s*21\.6/); // 0.62 × 180° − 90°
    expect(container.querySelector('.nx-gauge-figure .nx-visually-hidden')?.textContent).toBe('62');
    expect(container.querySelector('.nx-gauge-unit')?.textContent).toBe('%');
    expect(container.querySelector('.nx-gauge-zone-label')?.textContent).toBe('Caution'); // i18n.en.gaugeZoneCaution

    const table = screen.getByRole('table');
    const rows = within(table).getAllByRole('row');
    expect(rows.map((row) => row.textContent)).toEqual(
      expect.arrayContaining(['ZoneRange', 'Safe0 – 60', 'Caution60 – 85', 'Critical85 – 100', 'Value62 of 100']),
    );
  });

  it('maps the edges to their zones and clamps beyond the dial', () => {
    const low = render(<Gauge value={30} />);
    expect(low.container.querySelector('figure.nx-gauge')?.getAttribute('data-tone')).toBe('success');
    expect(low.container.querySelector('.nx-gauge-zone-label')?.textContent).toBe('Safe');
    cleanup();

    const high = render(<Gauge value={95} />);
    expect(high.container.querySelector('figure.nx-gauge')?.getAttribute('data-tone')).toBe('danger');
    expect(high.container.querySelector('.nx-gauge-zone-label')?.textContent).toBe('Critical');
    cleanup();

    const beyond = render(<Gauge value={150} />);
    expect(beyond.container.querySelector('figure.nx-gauge')?.getAttribute('style')).toMatch(/--_sweep:\s*100/); // clamped to max
  });

  it('names untoneable zones by number and honours a custom caption', () => {
    const { container } = render(
      <Gauge value={70} zones={[{ upTo: 40, tone: 'info' }, { upTo: 80, tone: 'info' }]} zoneLabel="Sweet spot" />,
    );
    expect(container.querySelector('.nx-gauge-zone-label')?.textContent).toBe('Sweet spot');
    const table = screen.getByRole('table');
    expect(within(table).getByText('Zone 1')).toBeTruthy(); // info zones have no word of their own
    expect(within(table).getByText('Zone 2')).toBeTruthy();
  });

  it('keeps a decimal place when the value has one, on every figure', () => {
    render(<Gauge value={62.5} />);
    const table = screen.getByRole('table');
    // The data's own decimals apply to the zones and the reading alike.
    expect(within(table).getByText('62.5 of 100.0')).toBeTruthy(); // i18n.en.gaugeReading
    expect(within(table).getByText('0.0 – 60.0')).toBeTruthy();
  });

  it('speaks Persian through the provider', () => {
    const { container } = render(
      <NabuXUIProvider locale="fa">
        <Gauge value={62} />
      </NabuXUIProvider>,
    );
    expect(container.querySelector('.nx-gauge-zone-label')?.textContent).toBe('احتیاط'); // i18n.fa.gaugeZoneCaution
    expect(screen.getByText('۶۲ از ۱۰۰')).toBeTruthy(); // i18n.fa.gaugeReading
    expect(within(screen.getByRole('table')).getByText('ایمن')).toBeTruthy(); // i18n.fa.gaugeZoneSafe
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <Gauge value={62} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(screen.getByText('62 of 100')).toBeTruthy(); // the English table
  });
});
