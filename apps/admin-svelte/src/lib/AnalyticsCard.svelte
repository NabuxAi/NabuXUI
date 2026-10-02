<script lang="ts" module>
  // One counter for every card on a page, so each radio group owns its name.
  let nextId = 1;
</script>

<script lang="ts">
  /**
   * The analytics KPI card — the hand-written Svelte shape of the
   * `analytics-card` block (nx-analytics-card): a period segmented control
   * (native radios under the core `indicator` thumb), a rolling KPI
   * (RollNumber), a delta pill, and bars that grow per period (`--nx-v` slots
   * that fold away beyond the current period). Pointer or arrow keys walk the
   * bars with the block's tooltip (core `placeChartTip`), and a
   * visually-hidden table carries the same data.
   */
  import { onMount } from 'svelte';
  import { type Cleanup, indicator, placeChartTip, reveal, translate } from '@nabuxai/ui-core';
  import { app, intlLocale } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';
  import RollNumber from './RollNumber.svelte';

  export interface AnalyticsPeriod {
    id: string;
    label: string;
    values: number[];
    /** The headline figure (the sum, a rate…) — not necessarily a value of `values`. */
    value: number;
    /** Change against the previous period, in percent. */
    delta?: number;
    labels?: string[];
    caption?: string;
  }

  let {
    title,
    periods,
    format = undefined,
    invertDelta = false,
  }: { title: string; periods: AnalyticsPeriod[]; format?: Intl.NumberFormatOptions; invertDelta?: boolean } = $props();

  let root: HTMLElement;
  let frame: HTMLElement;
  let tip: HTMLElement;
  let control: HTMLElement;
  const uid = `nx-analytics-${nextId++}`;

  // The open period starts at the first; afterwards the segmented control owns it.
  // svelte-ignore state_referenced_locally
  let current = $state(periods[0]?.id ?? '');
  let active = $state<number | null>(null);

  let ind: ReturnType<typeof indicator> | null = null;
  let stopReveal: Cleanup | null = null;

  const period = $derived(periods.find((p) => p.id === current) ?? periods[0]);
  const slots = $derived(Math.max(0, ...periods.map((p) => p.values.length)));
  const count = $derived(period?.values.length ?? 0);
  const max = $derived(Math.max(0, ...(period?.values ?? [])) || 1);
  const nameOf = (i: number) => period?.labels?.[i] ?? String(i + 1);
  const fmt = $derived(new Intl.NumberFormat(intlLocale(), format));
  const percentFmt = $derived(new Intl.NumberFormat(intlLocale(), { style: 'percent', maximumFractionDigits: 1 }));

  const trend = $derived.by(() => {
    if (period?.delta === undefined) return undefined;
    const up = period.delta >= 0;
    return (invertDelta ? !up : up) ? 'up' : 'down';
  });

  const moveThumb = () => ind?.update(control?.querySelector('.nx-segment:has(:checked)') ?? null);

  const pick = (id: string) => {
    current = id;
    active = null;
    requestAnimationFrame(moveThumb);
  };

  onMount(() => {
    stopReveal = reveal(root, { once: true });
    ind = indicator(control);
    return () => {
      ind?.destroy();
      stopReveal?.();
    };
  });

  // The tooltip rides the active bar (after the DOM has caught up).
  $effect(() => {
    if (active === null || !frame || !tip) return;
    const bar = frame.querySelector(`.nx-analytics-card-slot[data-index="${active}"] .nx-analytics-card-bar`);
    if (bar) placeChartTip(frame, bar, tip);
  });

  const onOver = (event: PointerEvent) => {
    const slot = (event.target as HTMLElement).closest<HTMLElement>('[data-index]');
    const at = slot ? Number(slot.dataset.index) : -1;
    active = at >= 0 && at < count ? at : null;
  };

  /** Arrow keys along the bars: the next index, null to leave, undefined when not handled. */
  function stepIndex(event: KeyboardEvent, at: number | null, total: number): number | null | undefined {
    if (!total) return undefined;
    const i = at ?? -1;
    switch (event.key) {
      case 'ArrowRight':
        return Math.min(total - 1, i + 1);
      case 'ArrowLeft':
        return Math.min(total - 1, Math.max(0, i === -1 ? 0 : i - 1));
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

  const onBarsKey = (event: KeyboardEvent) => {
    const step = stepIndex(event, active, count);
    if (step === undefined) return;
    event.preventDefault();
    active = step;
  };
</script>

<article bind:this={root} class="nx-analytics-card" data-nx-reveal="">
  <header class="nx-analytics-card-head">
    <p class="nx-analytics-card-title">{title}</p>
    <div bind:this={control} class="nx-segmented" data-size="sm" role="radiogroup" aria-label={title}>
      <span class="nx-indicator" aria-hidden="true"></span>
      {#each periods as p (p.id)}
        <label class="nx-segment">
          <input class="nx-segment-input" type="radio" name={uid} value={p.id} checked={p.id === current} onchange={() => pick(p.id)} />
          <span>{p.label}</span>
        </label>
      {/each}
    </div>
  </header>

  <div class="nx-analytics-card-kpi">
    <span class="nx-analytics-card-value">
      <RollNumber value={period?.value ?? 0} {format} />
    </span>
    {#if period?.delta !== undefined}
      <span class="nx-delta" data-trend={trend}>
        <NxIcon name={trend === 'down' ? 'trend-down' : 'trend-up'} />
        {percentFmt.format(Math.abs(period.delta) / 100)}
      </span>
    {/if}
  </div>
  {#if period?.caption}<p class="nx-analytics-card-caption">{period.caption}</p>{/if}

  <div bind:this={frame} class="nx-analytics-card-frame">
    <!-- svelte-ignore a11y_no_noninteractive_tabindex, a11y_no_noninteractive_element_interactions -->
    <div
      class="nx-analytics-card-bars"
      tabindex="0"
      role="img"
      aria-label={`${title}. ${translate(app.lang, 'chartHint')}`}
      onpointerover={onOver}
      onpointerleave={() => (active = null)}
      onkeydown={onBarsKey}
      onblur={() => (active = null)}
    >
      {#each Array.from({ length: slots }, (_, i) => i) as i (i)}
        <span
          class="nx-analytics-card-slot"
          data-index={i}
          data-state={i < count ? 'on' : 'off'}
          data-current={i === count - 1 ? '' : undefined}
          data-active={i === active ? '' : undefined}
          style:--nx-i={i}
          style:--nx-v={i < count ? Math.max(0.02, (period?.values[i] ?? 0) / max) : 0}
        >
          <span class="nx-analytics-card-bar">
            <span class="nx-analytics-card-fill"></span>
          </span>
        </span>
      {/each}
    </div>
    <div bind:this={tip} class="nx-chart-tooltip" data-open={active !== null ? '' : undefined} aria-hidden="true">
      <p class="nx-chart-tooltip-title">{active !== null ? nameOf(active) : ''}</p>
      <div class="nx-chart-tooltip-row">
        <strong>{active !== null ? fmt.format(period?.values[active] ?? 0) : ''}</strong>
        <span>{title}</span>
      </div>
    </div>
  </div>

  {#if count > 1}
    <div class="nx-analytics-card-axis" aria-hidden="true">
      <span>{nameOf(0)}</span>
      <span>{nameOf(count - 1)}</span>
    </div>
  {/if}

  <table class="nx-visually-hidden">
    <caption>{title} · {period?.label}</caption>
    <tbody>
      {#each period?.values ?? [] as value, i (i)}
        <tr>
          <th scope="row">{nameOf(i)}</th>
          <td>{fmt.format(value)}</td>
        </tr>
      {/each}
    </tbody>
  </table>
</article>
