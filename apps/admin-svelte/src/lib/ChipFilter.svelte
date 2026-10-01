<script lang="ts">
  /**
   * The chip filter — the Svelte shape of the `chip-filter` block from
   * docs/FRAMEWORKS.md: a single-select chip row whose accent springs under the
   * checked chip (core `indicator`).
   */
  import { onMount } from 'svelte';
  import { indicator } from '@nabuxai/ui-core';

  export interface ChipItem {
    value: string;
    label: string;
    count?: number;
  }

  let { items, value = $bindable(), label = undefined }: { items: ChipItem[]; value?: string; label?: string } = $props();

  let row: HTMLElement;
  let ind: ReturnType<typeof indicator> | null = null;

  const move = () => ind?.update(row?.querySelector('.nx-chip-filter-chip:has(:checked)') ?? null);

  onMount(() => {
    ind = indicator(row);
    move();
    return () => ind?.destroy();
  });

  const pick = (item: ChipItem) => {
    value = item.value;
    requestAnimationFrame(move);
  };
</script>

<div class="nx-chip-filter" role="group" aria-label={label}>
  <div bind:this={row} class="nx-chip-filter-row">
    <span class="nx-indicator nx-chip-filter-thumb" aria-hidden="true"></span>
    {#each items as item (item.value)}
      <label class="nx-chip-filter-chip">
        <input class="nx-chip-filter-input" type="radio" name="nx-chip-filter" value={item.value} checked={item.value === value} onchange={() => pick(item)} />
        <span>{item.label}</span>
        {#if item.count !== undefined}<span class="nx-chip-filter-count">{item.count}</span>{/if}
      </label>
    {/each}
  </div>
</div>
