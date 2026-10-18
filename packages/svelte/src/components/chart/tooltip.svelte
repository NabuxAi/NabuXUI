<script lang="ts">
  /**
   * The chart tooltip bubble — private to the chart family. Clamped inside the
   * plot around the pointer's x, exactly like ui-react's chart Tooltip.
   */
  interface Row {
    name: string;
    value: string;
    color: string;
  }

  interface Props {
    open: boolean;
    x: number;
    y: number;
    width: number;
    title: string;
    rows: Row[];
  }

  let { open, x, y, width, title, rows }: Props = $props();

  // Keep the bubble inside the plot: clamp around the pointer's x.
  const clampedX = $derived(Math.min(Math.max(x, 70), Math.max(70, width - 70)));
</script>

<div
  class="nx-chart-tooltip"
  data-open={open ? '' : undefined}
  aria-hidden="true"
  style:--nx-tx={`calc(${clampedX}px - 50%)`}
  style:--nx-ty={`calc(${y}px - 100% - 12px)`}
>
  <p class="nx-chart-tooltip-title">{title}</p>
  {#each rows as row (row.name)}
    <div class="nx-chart-tooltip-row">
      <span class="nx-chart-tooltip-key" style:--nx-series={row.color}></span>
      <strong>{row.value}</strong>
      <span>{row.name}</span>
    </div>
  {/each}
</div>
