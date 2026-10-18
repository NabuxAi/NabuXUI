<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { Series } from './data-table.svelte';

  export interface AreaChartProps {
    labels: string[];
    series: Series[];
    title?: string | Snippet;
    subtitle?: string | Snippet;
    format?: Intl.NumberFormatOptions;
    locale?: string;
    height?: number;
    /** A wash under each line (area) or the lines alone. */
    variant?: 'area' | 'line';
    smooth?: boolean;
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * AreaChart — the Svelte counterpart of ui-react's chart family's
   * AreaChart / line chart: same DOM, same nx-* classes, same props.
   */
  import { areaPath, linePath, linearScale, nearestIndex, niceTicks, reveal } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import { nxIntlLocale, nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import ChartLegend from './legend.svelte';
  import ChartTable from './data-table.svelte';
  import ChartTooltip from './tooltip.svelte';

  const seriesColor = (i: number) => `var(--nx-chart-${(i % 7) + 1})`;
  const PAD = { top: 16, right: 12, bottom: 28, left: 44 };

  let {
    labels,
    series,
    title,
    subtitle,
    format,
    locale,
    height = 260,
    variant = 'area',
    smooth = true,
    class: className,
  }: AreaChartProps = $props();

  let plot = $state<HTMLDivElement>();
  let width = $state(560);
  let active = $state<number | null>(null);
  const id = $props.id();

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

  const ticks = $derived.by(() => {
    const all = series.flatMap((s) => s.values);
    return niceTicks(Math.min(0, ...all), Math.max(0, ...all), 4);
  });
  const innerH = $derived(height - PAD.top - PAD.bottom);
  const x = $derived(linearScale([0, Math.max(1, labels.length - 1)], [PAD.left, width - PAD.right]));
  const y = $derived(linearScale([ticks[0] ?? 0, ticks[ticks.length - 1] ?? 0], [PAD.top + innerH, PAD.top]));
  const xs = $derived(labels.map((_, i) => x(i)));
  const lines = $derived(series.map((s) => s.values.map((v, i) => [x(i), y(v)] as const)));

  function onMove(event: PointerEvent) {
    const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
    active = nearestIndex(xs, event.clientX - rect.left);
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

  // Label only a few ticks along x so they never collide.
  const every = $derived(Math.max(1, Math.ceil(labels.length / Math.max(2, Math.floor(width / 90)))));

  const tooltipX = $derived(active === null ? 0 : xs[active] ?? 0);
  const tooltipTitle = $derived(active === null ? '' : labels[active] ?? '');
  const tooltipY = $derived.by(() => {
    if (active === null) return 0;
    const i = active;
    return Math.min(...lines.map((points) => points[i]?.[1] ?? height));
  });
  const tooltipRows = $derived.by(() => {
    if (active === null) return [];
    const i = active;
    return series.map((s, si) => ({ name: s.name, value: fmt.value(s.values[i] ?? 0), color: seriesColor(si) }));
  });
</script>

<figure
  class={cx('nx-chart', className)}
  data-type={variant}
  data-nx-reveal=""
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
      <ChartLegend {series} shape={variant === 'area' ? 'rect' : 'line'} color={seriesColor} />
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
      <defs>
        {#each series as s, i (s.name)}
          <linearGradient id={`${id}-fill-${i}`} x1="0" x2="0" y1="0" y2="1">
            <stop offset="0%" stop-color={seriesColor(i)} stop-opacity="0.22" />
            <stop offset="100%" stop-color={seriesColor(i)} stop-opacity="0.01" />
          </linearGradient>
        {/each}
      </defs>
      {#each ticks as tick (tick)}
        <g>
          <line class={tick === ticks[0] ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1={PAD.left} x2={width - PAD.right} y1={y(tick)} y2={y(tick)} />
          <text class="nx-chart-tick" x={PAD.left - 8} y={y(tick)} dy="0.32em" text-anchor="end">{fmt.tick(tick)}</text>
        </g>
      {/each}
      {#each labels as label, i (label + i)}
        {#if i % every === 0 || i === labels.length - 1}
          <text
            class="nx-chart-tick"
            x={xs[i]}
            y={height - 8}
            text-anchor={i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle'}
          >{label}</text>
        {/if}
      {/each}
      {#if variant === 'area'}
        {#each lines as points, i (i)}
          <path class="nx-chart-area" d={areaPath(points, y(ticks[0] ?? 0), smooth)} fill={`url(#${id}-fill-${i})`} style:--nx-series={seriesColor(i)} />
        {/each}
      {/if}
      {#each lines as points, i (i)}
        <path class="nx-chart-line" d={linePath(points, smooth)} pathLength={1} style:--nx-series={seriesColor(i)} style:--nx-i={i} />
      {/each}
      <line
        class="nx-chart-crosshair"
        data-active={active !== null ? '' : undefined}
        x1={tooltipX}
        x2={tooltipX}
        y1={PAD.top}
        y2={PAD.top + innerH}
      />
      {#each lines as points, i (i)}
        {#each points as [px, py], pi (`p${i}-${pi}`)}
          <circle
            class="nx-chart-point"
            cx={px}
            cy={py}
            r="4"
            data-active={active === pi ? '' : undefined}
            data-end={pi === points.length - 1 ? '' : undefined}
            style:--nx-series={seriesColor(i)}
          />
        {/each}
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
