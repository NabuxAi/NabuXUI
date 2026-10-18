<script module lang="ts">
  /**
   * Legend (only for two or more series — a single series is named by the
   * title) — private to the chart family.
   */
  import type { Series } from './data-table.svelte';

  export interface ChartLegendProps {
    series: Series[];
    shape: 'rect' | 'line';
    color: (i: number) => string;
  }
</script>

<script lang="ts">
  let { series, shape, color }: ChartLegendProps = $props();
</script>

{#if series.length >= 2}
  <ul class="nx-chart-legend">
    {#each series as s, i (s.name)}
      <li class="nx-legend-item">
        <span class="nx-legend-swatch" data-shape={shape} style:--nx-series={color(i)}></span>
        {s.name}
      </li>
    {/each}
  </ul>
{/if}
