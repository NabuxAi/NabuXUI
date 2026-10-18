<script module lang="ts">
  /**
   * The same numbers as a table, for screen readers and anyone who cannot use
   * the chart — private to the chart family, shared by bar / area / donut.
   */
  import type { Snippet } from 'svelte';

  export interface Series {
    name: string;
    values: number[];
  }

  export interface ChartTableProps {
    caption?: string | Snippet;
    labels: string[];
    series: Series[];
    format: (n: number) => string;
  }
</script>

<script lang="ts">
  let { caption, labels, series, format }: ChartTableProps = $props();
</script>

<table class="nx-visually-hidden">
  {#if caption}
    <caption>{#if typeof caption === 'string'}{caption}{:else}{@render caption()}{/if}</caption>
  {/if}
  <thead>
    <tr>
      <td></td>
      {#each series as s (s.name)}
        <th scope="col">{s.name}</th>
      {/each}
    </tr>
  </thead>
  <tbody>
    {#each labels as label, row (label + row)}
      <tr>
        <th scope="row">{label}</th>
        {#each series as s (s.name)}
          <td>{format(s.values[row] ?? 0)}</td>
        {/each}
      </tr>
    {/each}
  </tbody>
</table>
