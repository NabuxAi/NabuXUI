<script lang="ts" module>
  import type { Snippet } from 'svelte';
  import type { SortState } from '@nabuxai/ui-core';

  export interface Column<R> {
    key: string;
    label: string;
    sortable?: boolean;
    sortValue?: (row: R) => unknown;
    align?: 'start' | 'end';
    width?: string;
    /** The page's own cell renderer for this column. */
    cell?: Snippet<[row: R, index: number]>;
    /** The page's own header renderer (the select-all checkbox). */
    head?: Snippet<[column: Column<R>]>;
  }

  export type { SortState };
</script>

<script lang="ts">
  /**
   * The data table — the hand-written Svelte twin of the `data-table` block
   * (nx-data-table): sorting through the core `sortRows`/`nextSort` pair and
   * the FLIP glide (`snapshotRows` + `playRowFlip`) after each sort, the group
   * reveal on mount, and a keyboard-scrollable box when the table overflows.
   * Cells come in as per-column snippets on the column definitions. The row
   * type stays open (`unknown`) — the page instantiates `Column<ItsRow>`.
   */
  import { onMount, tick } from 'svelte';
  import { nextSort, playRowFlip, reveal, snapshotRows, sortRows, type RowSnapshot } from '@nabuxai/ui-core';
  import { intlLocale } from '../store.svelte';

  /* eslint-disable @typescript-eslint/no-explicit-any -- Svelte components have no generics; the page types its own rows. */
  let {
    rows,
    columns,
    class: className = undefined,
    rowKey = undefined,
    caption,
    emptyText = undefined,
    defaultSort = null,
    maxHeight = undefined,
  }: {
    rows: any[];
    columns: Column<any>[];
    class?: string;
    rowKey?: (row: any, index: number) => string;
    caption: string;
    emptyText?: string;
    defaultSort?: SortState | null;
    maxHeight?: string;
  } = $props();

  let scroller: HTMLDivElement;
  let body: HTMLTableSectionElement;
  // The initial sort comes from the page; afterwards the header presses own it.
  // svelte-ignore state_referenced_locally
  let sort = $state<SortState | null>(defaultSort);
  let scrollable = $state(false);
  let flight: RowSnapshot | null = null;

  const keyOf = (row: any, index: number) => (rowKey ? rowKey(row, index) : String(index));

  const sorted = $derived.by(() => {
    if (!sort) return rows;
    const current = sort;
    const column = columns.find((c) => c.key === current.key);
    const value = column?.sortValue ? (row: any) => column.sortValue!(row) : undefined;
    return sortRows(rows, current.key, current.direction, { locale: intlLocale(), value });
  });

  onMount(() => {
    const stop = reveal(scroller, { once: true });
    checkScroll();
    return stop;
  });

  // A table wider or taller than its box can be scrolled from the keyboard.
  function checkScroll() {
    if (!scroller) return;
    scrollable = scroller.scrollWidth > scroller.clientWidth + 1 || scroller.scrollHeight > scroller.clientHeight + 1;
  }

  const press = async (key: string) => {
    if (body) flight = snapshotRows(Array.from(body.rows));
    sort = nextSort(sort, key);
    await tick();
    flip();
    requestAnimationFrame(() => (flight = null));
  };

  function flip() {
    if (!flight || !body) return;
    const before = flight;
    flight = null;
    playRowFlip(Array.from(body.rows), before);
  }

  const directionOf = (key: string) => (sort?.key === key ? sort.direction : undefined);
</script>

<!-- svelte-ignore a11y_no_noninteractive_tabindex, a11y_no_static_element_interactions -->
<div
  bind:this={scroller}
  class={className ? `nx-data-table ${className}` : 'nx-data-table'}
  data-nx-reveal=""
  tabindex={scrollable ? 0 : undefined}
  role={scrollable ? 'region' : undefined}
  aria-label={scrollable ? caption : undefined}
  style:--nx-table-max={maxHeight}
>
  <table>
    <caption class="nx-visually-hidden">{caption}</caption>
    <thead>
      <tr>
        {#each columns as column (column.key)}
          <th
            scope="col"
            aria-sort={directionOf(column.key)}
            data-align={column.align && column.align !== 'start' ? column.align : undefined}
            style={column.width ? `inline-size: ${column.width}` : undefined}
          >
            {#if column.head}
              {@render column.head(column)}
            {:else if column.sortable}
              <button type="button" class="nx-data-table-sort" data-direction={directionOf(column.key)} onclick={() => press(column.key)}>
                <span>{column.label}</span>
                <svg class="nx-data-table-sort-icon" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12 19V5M6 11l6-6 6 6" />
                </svg>
              </button>
            {:else}
              {column.label}
            {/if}
          </th>
        {/each}
      </tr>
    </thead>
    <tbody bind:this={body}>
      {#if sorted.length === 0}
        <tr><td class="nx-data-table-empty" colspan={columns.length}>{emptyText}</td></tr>
      {/if}
      {#each sorted as row, index (keyOf(row, index))}
        <tr data-key={keyOf(row, index)} style:--nx-i={index}>
          {#each columns as column (column.key)}
            <td data-align={column.align && column.align !== 'start' ? column.align : undefined}>
              {#if column.cell}{@render column.cell(row, index)}{:else}{row[column.key]}{/if}
            </td>
          {/each}
        </tr>
      {/each}
    </tbody>
  </table>
</div>
