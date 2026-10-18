<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { Series } from './data-table.svelte';

  export interface BarChartProps {
    /** One series: [{ label, value }]. */
    data?: Array<{ label: string; value: number }>;
    labels?: string[];
    series?: Series[];
    title?: string | Snippet;
    subtitle?: string | Snippet;
    format?: Intl.NumberFormatOptions;
    locale?: string;
    height?: number;
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * BarChart — the Svelte counterpart of ui-react's chart family's BarChart:
   * same DOM, same nx-* classes, same props (camelCase), events as callbacks.
   */
  import { bands, barPath, linearScale, niceTicks, reveal } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import { nxIntlLocale, nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import ChartLegend from './legend.svelte';
  import ChartTable from './data-table.svelte';
  import ChartTooltip from './tooltip.svelte';

  const seriesColor = (i: number) => `var(--nx-chart-${(i % 7) + 1})`;
  const PAD = { top: 16, right: 12, bottom: 28, left: 44 };

  let { data, labels: labelsProp, series: seriesProp, title, subtitle, format, locale, height = 240, class: className }: BarChartProps = $props();

  let plot = $state<HTMLDivElement>();
  let width = $state(560);
  let active = $state<number | null>(null);
  const id = $props.id();

  // The element's content-box width, tracked as it resizes (0 until measured).
  $effect(() => {
    const el = plot;
    if (!el) return;
    width = el.getBoundingClientRect().width || 560;
    if (typeof ResizeObserver === 'undefined') return;
    const observer = new ResizeObserver(([entry]) => entry && (width = entry.contentRect.width));
    observer.observe(el);
    return () => observer.disconnect();
  });

  const fmt = $derived.by(() => {
    const intl = locale ?? nxIntlLocale();
    const value = new Intl.NumberFormat(intl, format);
    const tick = new Intl.NumberFormat(intl, { notation: 'compact', maximumFractionDigits: 1, ...(format?.style === 'currency' ? { style: 'currency', currency: format.currency } : null) });
    return {
      value: (n: number) => value.format(n),
      tick: (n: number) => tick.format(n),
    };
  });

  const labels = $derived(labelsProp ?? data?.map((d) => d.label) ?? []);
  const series = $derived(
    seriesProp ?? [{ name: typeof title === 'string' ? title : 'Value', values: data?.map((d) => d.value) ?? [] }],
  );

  const ticks = $derived.by(() => {
    const all = series.flatMap((s) => s.values);
    return niceTicks(Math.min(0, ...all), Math.max(0, ...all), 4);
  });
  const innerW = $derived(Math.max(0, width - PAD.left - PAD.right));
  const innerH = $derived(height - PAD.top - PAD.bottom);
  const y = $derived(linearScale([ticks[0] ?? 0, ticks[ticks.length - 1] ?? 0], [PAD.top + innerH, PAD.top]));
  const groups = $derived(
    bands(labels.length, innerW, { maxBar: 24 * series.length + 2 * (series.length - 1), fill: 0.7 }),
  );

  function onMove(event: PointerEvent) {
    const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
    const x = event.clientX - rect.left - PAD.left;
    const index = Math.floor(x / (innerW / Math.max(1, labels.length)));
    active = index >= 0 && index < labels.length ? index : null;
  }

  // Keyboard reading of a chart: arrows move between categories, Escape leaves.
  function onKey(event: KeyboardEvent) {
    if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
      event.preventDefault();
      const step = event.key === 'ArrowRight' ? 1 : -1;
      active = active === null ? 0 : Math.min(Math.max(active + step, 0), labels.length - 1);
    } else if (event.key === 'Home') active = 0;
    else if (event.key === 'End') active = labels.length - 1;
    else if (event.key === 'Escape') active = null;
  }

  const tooltipY = $derived.by(() => {
    if (active === null) return 0;
    const i = active;
    return y(Math.max(...series.map((s) => s.values[i] ?? 0)));
  });
  const tooltipX = $derived(active === null ? 0 : PAD.left + (groups[active]?.center ?? 0));
  const tooltipTitle = $derived(active === null ? '' : labels[active] ?? '');
  const tooltipRows = $derived.by(() => {
    if (active === null) return [];
    const i = active;
    return series.map((s, si) => ({ name: s.name, value: fmt.value(s.values[i] ?? 0), color: seriesColor(si) }));
  });
</script>

<figure
  class={cx('nx-chart', className)}
  data-type="bar"
  data-nx-reveal=""
  data-active={active !== null ? '' : undefined}
  aria-labelledby={title ? `${id}-title` : undefined}
  use:nxBehavior={{ behavior: reveal, options: { once: true }, enabled: true }}
>
  {#if title || series.length > 1}
    <div class="nx-chart-head">
      <div>
        {#if title}
          <p class="nx-chart-title" id={`${id}-title`}>{#if typeof title === 'string'}{title}{:else}{@render title()}{/if}</p>
        {/if}
        {#if subtitle}
          <p class="nx-chart-subtitle">{#if typeof subtitle === 'string'}{subtitle}{:else}{@render subtitle()}{/if}</p>
        {/if}
      </div>
      <ChartLegend {series} shape="rect" color={seriesColor} />
    </div>
  {/if}
  <div
    bind:this={plot}
    class="nx-chart-plot"
    tabindex="0"
    aria-label={`${typeof title === 'string' ? `${title}. ` : ''}${nxTranslate(nxLang(), 'chartHint')}`}
    onpointermove={onMove}
    onpointerleave={() => (active = null)}
    onkeydown={onKey}
    onblur={() => (active = null)}
  >
    <svg class="nx-chart-svg" {width} {height} viewBox={`0 0 ${width} ${height}`} aria-hidden="true">
      {#each ticks as tick (tick)}
        <g>
          <line class={tick === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1={PAD.left} x2={width - PAD.right} y1={y(tick)} y2={y(tick)} />
          <text class="nx-chart-tick" x={PAD.left - 8} y={y(tick)} dy="0.32em" text-anchor="end">{fmt.tick(tick)}</text>
        </g>
      {/each}
      {#each groups as group, index (index)}
        <g transform={`translate(${PAD.left},0)`}>
          {#each series as s, si (s.name)}
            {@const w = (group.width - 2 * (series.length - 1)) / series.length}
            {@const x = group.x + si * (w + 2)}
            <path
              class="nx-chart-bar"
              d={barPath(x, w, y(s.values[index] ?? 0), y(0), 4)}
              data-active={active === index ? '' : undefined}
              style:--nx-series={seriesColor(si)}
              style:--nx-i={index}
            />
          {/each}
          <text class="nx-chart-tick" x={group.center} y={height - 8} text-anchor="middle">{labels[index]}</text>
        </g>
      {/each}
    </svg>
    <ChartTooltip
      open={active !== null}
      x={tooltipX}
      y={tooltipY}
      {width}
      title={tooltipTitle}
      rows={tooltipRows}
    />
  </div>
  <ChartTable caption={title} {labels} {series} format={fmt.value} />
</figure>
