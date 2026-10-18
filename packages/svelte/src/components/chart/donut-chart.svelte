<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface DonutChartProps {
    data: Array<{ label: string; value: number }>;
    title?: string | Snippet;
    /** Shown in the centre; the total by default. */
    centerLabel?: string | Snippet;
    format?: Intl.NumberFormatOptions;
    locale?: string;
    size?: string;
    thickness?: number;
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * DonutChart — the Svelte counterpart of ui-react's chart family's
   * DonutChart: same DOM, same nx-* classes, same props.
   */
  import { donutSegments, reveal } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import { nxIntlLocale } from '../../internal/locale';
  import ChartTable from './data-table.svelte';

  const seriesColor = (i: number) => `var(--nx-chart-${(i % 7) + 1})`;

  let { data, title, centerLabel, format, locale, size, thickness = 14, class: className }: DonutChartProps = $props();

  let active = $state<number | null>(null);

  const fmt = $derived.by(() => {
    const intl = locale ?? nxIntlLocale();
    const value = new Intl.NumberFormat(intl, format);
    const percent = new Intl.NumberFormat(intl, { style: 'percent', maximumFractionDigits: 0 });
    return {
      value: (n: number) => value.format(n),
      percent: (n: number) => percent.format(n),
    };
  });

  const segments = $derived(donutSegments(data.map((d) => d.value)));
  const total = $derived(data.reduce((sum, d) => sum + d.value, 0));
  const radius = $derived(50 - thickness / 2 - 4);

  const shown = $derived(
    active === null
      ? { value: fmt.value(total), label: centerLabel ?? title }
      : { value: fmt.value(data[active]?.value ?? 0), label: data[active]?.label },
  );
  const series = $derived(data.map((d) => ({ name: d.label, values: [d.value] })));
</script>

<figure class={cx('nx-chart', className)} data-type="donut" data-nx-reveal="" use:nxBehavior={{ behavior: reveal, options: { once: true }, enabled: true }}>
  <div class="nx-donut" style:--nx-donut-size={size || undefined} style:--nx-donut-thickness={thickness}>
    <svg class="nx-chart-svg" viewBox="0 0 100 100" aria-hidden="true" onpointerleave={() => (active = null)}>
      {#each segments as segment, i (data[i]?.label ?? i)}
        <circle
          class="nx-donut-segment"
          cx="50"
          cy="50"
          r={radius}
          pathLength={100}
          data-active={active === i ? '' : undefined}
          onpointerenter={() => (active = i)}
          style:--nx-series={seriesColor(i)}
          style:--nx-len={segment.length}
          style:--nx-offset={segment.offset}
          style:--nx-i={i}
        />
      {/each}
    </svg>
    <div class="nx-donut-center" aria-hidden="true">
      <span class="nx-donut-value">{shown.value}</span>
      {#if shown.label}
        <span class="nx-donut-label">{#if typeof shown.label === 'string'}{shown.label}{:else}{@render shown.label()}{/if}</span>
      {/if}
    </div>
  </div>
  <ul class="nx-chart-legend" style:justify-content="center">
    {#each data as d, i (d.label)}
      <li class="nx-legend-item" onpointerenter={() => (active = i)} onpointerleave={() => (active = null)}>
        <span class="nx-legend-swatch" style:--nx-series={seriesColor(i)}></span>
        {d.label} · {fmt.percent(segments[i]?.share ?? 0)}
      </li>
    {/each}
  </ul>
  <ChartTable caption={title} labels={['']} {series} format={fmt.value} />
</figure>
