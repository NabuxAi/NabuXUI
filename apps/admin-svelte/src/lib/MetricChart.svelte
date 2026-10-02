<script lang="ts" module>
  // One counter for every chart on a page, so each one owns its ids and tab list.
  let nextId = 1;
</script>

<script lang="ts">
  /**
   * The metric chart — the hand-written Svelte shape of the `metric-chart`
   * block (nx-metric-chart): one line series at a time behind metric tabs (the
   * core `indicator` thumb + roving focus), the geometry and the line-to-line
   * morph straight from the core (`metricGeometry` + `morphPath` — the same
   * marks the React chart draws), a pointer/keyboard crosshair with the block's
   * tooltip (`nearestIndex` + `placeChartTip`), and a visually-hidden table of
   * every series for screen readers.
   */
  import { onMount } from 'svelte';
  import {
    type Cleanup,
    indicator,
    metricGeometry,
    morphPath,
    nearestIndex,
    placeChartTip,
    reveal,
    translate,
  } from '@nabuxai/ui-core';
  import { app, intlLocale } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  export interface MetricChartMetric {
    id: string;
    label: string;
    values: number[];
    format?: Intl.NumberFormatOptions;
    /** The headline number; the last value by default. */
    value?: number;
    /** Change against the previous period, in percent. */
    delta?: number;
    /** When a rise is bad news (latency, costs). */
    invertDelta?: boolean;
  }

  let {
    labels,
    metrics,
    title = undefined,
    caption = undefined,
    height = 200,
  }: { labels: string[]; metrics: MetricChartMetric[]; title?: string; caption?: string; height?: number } = $props();

  let root: HTMLElement;
  let tabs: HTMLElement;
  let plot: HTMLElement;
  let tip: HTMLElement;
  let line: SVGPathElement;
  let area: SVGPathElement;

  // The open series starts at the first; afterwards the tabs own it.
  // svelte-ignore state_referenced_locally
  let currentId = $state(metrics[0]?.id ?? '');
  let active = $state<number | null>(null);
  let width = $state(560);

  const uid = `nx-metric-${nextId++}`;
  let ind: ReturnType<typeof indicator> | null = null;
  let stopReveal: Cleanup | null = null;
  let observer: ResizeObserver | null = null;

  const index = $derived(Math.max(0, metrics.findIndex((m) => m.id === currentId)));
  const metric = $derived(metrics[index]);
  const series = (i: number) => `var(--nx-chart-${Math.min(Math.max(i, 0), 6) + 1})`;

  const fmt = $derived(new Intl.NumberFormat(intlLocale(), metric?.format));
  const tickFmt = $derived(
    new Intl.NumberFormat(intlLocale(), {
      notation: 'compact',
      maximumFractionDigits: 1,
      ...(metric?.format?.style === 'currency' ? { style: 'currency', currency: metric.format.currency } : null),
    }),
  );
  const percentFmt = $derived(new Intl.NumberFormat(intlLocale(), { style: 'percent', maximumFractionDigits: 1 }));

  const geo = $derived(metricGeometry(metric?.values ?? [], width, height));
  const headline = $derived.by(() => {
    const values = metric?.values ?? [];
    return metric?.value ?? (values.length ? values[values.length - 1]! : 0);
  });
  const xs = $derived(geo.points.map((p) => p[0]));
  const every = $derived(Math.max(1, Math.ceil(labels.length / Math.max(2, Math.floor(width / 84)))));

  const trend = $derived.by(() => {
    if (metric?.delta === undefined) return undefined;
    const up = metric.delta >= 0;
    return (metric?.invertDelta ? !up : up) ? 'up' : 'down';
  });

  const moveThumb = () => ind?.update(tabs?.querySelector(`[data-value="${CSS.escape(currentId)}"]`) ?? null);
  const pick = (id: string) => {
    currentId = id;
    requestAnimationFrame(moveThumb);
  };

  // The plot follows its box; the geometry (and the morph) follow the width.
  onMount(() => {
    stopReveal = reveal(root, { once: true });
    ind = indicator(tabs);
    if ('ResizeObserver' in window) {
      observer = new ResizeObserver(() => {
        const next = Math.round(plot?.getBoundingClientRect().width ?? 0);
        if (next > 0) width = next;
      });
      observer.observe(plot);
    }
    return () => {
      observer?.disconnect();
      ind?.destroy();
      stopReveal?.();
    };
  });

  // Switching metrics morphs the line and its wash into the new shape — the
  // same core morphPath call the React chart makes, starting from the old one.
  let drawn: { id: string; line: string; area: string } | null = null;
  $effect(() => {
    const previous = drawn;
    drawn = { id: metric?.id ?? '', line: geo.line, area: geo.area };
    if (!previous || previous.id === drawn.id || !line || !area) return;
    morphPath(line, geo.line, { from: previous.line });
    morphPath(area, geo.area, { from: previous.area, fallback: 'fade' });
  });

  // The tooltip rides the active point.
  $effect(() => {
    if (active === null || !plot || !tip) return;
    const point = plot.querySelector('.nx-metric-chart-point[data-active]');
    if (point) placeChartTip(plot, point, tip);
  });

  const onPlotMove = (event: PointerEvent) => {
    const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
    active = nearestIndex(xs, event.clientX - rect.left);
  };

  /** Arrow keys along one axis (a plot's points, a tablist's tabs): the next
   *  index, null to leave, undefined when the key is not ours. `dir` flips for
   *  right-to-left rows. */
  function stepIndex(event: KeyboardEvent, at: number | null, total: number, dir = 1): number | null | undefined {
    if (!total) return undefined;
    const i = at ?? -1;
    switch (event.key) {
      case 'ArrowRight':
        return Math.min(total - 1, Math.max(0, i + dir));
      case 'ArrowLeft':
        return Math.min(total - 1, Math.max(0, i === -1 ? 0 : i - dir));
      case 'Home':
        return 0;
      case 'End':
        return total - 1;
      case 'Escape':
        return null;
      default:
        return undefined;
    }
  }

  const onPlotKey = (event: KeyboardEvent) => {
    const step = stepIndex(event, active, labels.length);
    if (step === undefined) return;
    event.preventDefault();
    active = step;
  };

  const onTabsKey = (event: KeyboardEvent) => {
    const dir = getComputedStyle(event.currentTarget as HTMLElement).direction === 'rtl' ? -1 : 1;
    const step = stepIndex(event, index, metrics.length, dir);
    if (step === undefined || step === null) return;
    event.preventDefault();
    currentId = metrics[step]!.id;
    requestAnimationFrame(() => {
      moveThumb();
      tabs?.querySelector<HTMLElement>(`[data-value="${CSS.escape(currentId)}"]`)?.focus();
    });
  };
</script>

<section bind:this={root} class="nx-metric-chart" data-nx-reveal="" aria-labelledby={title ? `${uid}-title` : undefined} style:--nx-series={series(index)}>
  <header class="nx-metric-chart-head">
    {#if title}
      <div>
        <p class="nx-metric-chart-title" id={`${uid}-title`}>{title}</p>
      </div>
    {/if}
    <div bind:this={tabs} class="nx-metric-chart-tabs" role="tablist" tabindex="-1" aria-label={title} onkeydown={onTabsKey}>
      <span class="nx-indicator" aria-hidden="true"></span>
      {#each metrics as m, i (m.id)}
        <button
          type="button"
          role="tab"
          id={`${uid}-tab-${i}`}
          class="nx-metric-chart-tab"
          data-value={m.id}
          aria-selected={i === index}
          aria-controls={`${uid}-panel`}
          tabindex={i === index ? 0 : -1}
          style:--nx-series={series(i)}
          onclick={() => pick(m.id)}
        >
          <span class="nx-metric-chart-key" aria-hidden="true"></span>
          {m.label}
        </button>
      {/each}
    </div>
  </header>

  <div class="nx-metric-chart-panel" role="tabpanel" id={`${uid}-panel`} aria-labelledby={`${uid}-tab-${index}`}>
    <div class="nx-metric-chart-summary">
      <span class="nx-metric-chart-value">{fmt.format(headline)}</span>
      {#if metric?.delta !== undefined}
        <span class="nx-delta" data-trend={trend}>
          <NxIcon name={trend === 'down' ? 'trend-down' : 'trend-up'} />
          {percentFmt.format(Math.abs(metric.delta) / 100)}
        </span>
      {/if}
      {#if caption}<span class="nx-metric-chart-caption">{caption}</span>{/if}
    </div>
    <!-- svelte-ignore a11y_no_noninteractive_tabindex, a11y_no_noninteractive_element_interactions -->
    <div
      bind:this={plot}
      class="nx-metric-chart-plot"
      tabindex="0"
      role="img"
      aria-label={`${metric?.label}. ${translate(app.lang, 'chartHint')}`}
      onpointermove={onPlotMove}
      onpointerleave={() => (active = null)}
      onkeydown={onPlotKey}
      onblur={() => (active = null)}
    >
      <svg class="nx-chart-svg" {width} {height} viewBox="0 0 {width} {height}" aria-hidden="true">
        <defs>
          <linearGradient id={`${uid}-fill`} x1="0" x2="0" y1="0" y2="1">
            <stop class="nx-metric-chart-stop" offset="0%" stop-opacity="0.24"></stop>
            <stop class="nx-metric-chart-stop" offset="100%" stop-opacity="0.01"></stop>
          </linearGradient>
        </defs>
        {#key metric?.id}
          <g class="nx-metric-chart-ticks">
            {#each geo.ticks as tick, i (tick.value)}
              <g>
                <line class={i === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'} x1="48" x2={width - 12} y1={tick.y} y2={tick.y}></line>
                <text class="nx-chart-tick" x="40" y={tick.y} dy="0.32em" text-anchor="end">{tickFmt.format(tick.value)}</text>
              </g>
            {/each}
          </g>
        {/key}
        {#each labels as label, i (`${label}-${i}`)}
          {#if i % every === 0 || i === labels.length - 1}
            <text
              class="nx-chart-tick"
              x={xs[i]}
              y={height - 6}
              text-anchor={i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle'}
            >
              {label}
            </text>
          {/if}
        {/each}
        <path bind:this={area} class="nx-metric-chart-area" d={geo.area} fill={`url(#${uid}-fill)`}></path>
        <path bind:this={line} class="nx-metric-chart-line" d={geo.line} pathLength="1"></path>
        <line
          class="nx-chart-crosshair"
          data-active={active !== null ? '' : undefined}
          x1={active === null ? 0 : xs[active]}
          x2={active === null ? 0 : xs[active]}
          y1="12"
          y2={geo.baseline}
        ></line>
        {#each geo.points as p, i (i)}
          {#if i === active || i === geo.points.length - 1}
            <circle class="nx-metric-chart-point" cx={p[0]} cy={p[1]} r="4" data-active={i === active ? '' : undefined} data-end={i === geo.points.length - 1 ? '' : undefined}></circle>
          {/if}
        {/each}
      </svg>
      <div bind:this={tip} class="nx-chart-tooltip" data-open={active !== null ? '' : undefined} aria-hidden="true">
        <p class="nx-chart-tooltip-title">{active !== null ? labels[active] : ''}</p>
        <div class="nx-chart-tooltip-row">
          <span class="nx-chart-tooltip-key" style:--nx-series={series(index)}></span>
          <strong>{active !== null ? fmt.format(metric?.values[active] ?? 0) : ''}</strong>
          <span>{metric?.label}</span>
        </div>
      </div>
    </div>
  </div>

  <table class="nx-visually-hidden">
    {#if title}<caption>{title}</caption>{/if}
    <thead>
      <tr>
        <td></td>
        {#each metrics as m (m.id)}
          <th scope="col">{m.label}</th>
        {/each}
      </tr>
    </thead>
    <tbody>
      {#each labels as label, row (`${label}-${row}`)}
        <tr>
          <th scope="row">{label}</th>
          {#each metrics as m (m.id)}
            <td>{new Intl.NumberFormat(intlLocale(), m.format).format(m.values[row] ?? 0)}</td>
          {/each}
        </tr>
      {/each}
    </tbody>
  </table>
</section>
