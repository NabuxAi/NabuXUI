<script module lang="ts">
  import type { HTMLAttributes } from 'svelte/elements';
  import type { Snippet } from 'svelte';

  export interface KbdProps extends HTMLAttributes<HTMLElement> {
    children?: Snippet;
  }
</script>

<script lang="ts">
  /**
   * Kbd — the Svelte counterpart of ui-react's display family's Kbd.
   */
  import { cx } from '../../internal/cx';

  let { class: className, children, ...rest }: KbdProps = $props();
</script>

<kbd class={cx('nx-kbd', className)} {...rest}>
  {#if children}{@render children()}{/if}
</kbd>
