<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface DividerProps {
    children?: Snippet;
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * Divider — the Svelte counterpart of ui-react's display family's Divider.
   */
  import { cx } from '../../internal/cx';

  let { children, class: className }: DividerProps = $props();
</script>

<div class={cx('nx-divider', className)} role="separator">
  {#if children}{@render children()}{/if}
</div>
