<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface StackCardsProps {
    items: Snippet[];
    /** How far each card overlaps the previous one (a negative margin, e.g. "-55%"). */
    overlap?: string;
    class?: string;
    'aria-label'?: string;
  }
</script>

<script lang="ts">
  /**
   * StackCards — the Svelte counterpart of ui-react's card family's StackCards:
   * overlapping cards that fan out on hover.
   */
  import { cx } from '../../internal/cx';

  let { items, overlap, class: className, 'aria-label': label }: StackCardsProps = $props();
</script>

<ul class={cx('nx-stack', className)} aria-label={label} style:--nx-count={items.length} style:--nx-stack-overlap={overlap || undefined}>
  {#each items as item, i (i)}
    <li class="nx-stack-item" style:--i={i}>
      {@render item()}
    </li>
  {/each}
</ul>
