<script module lang="ts">
  import { reveal, type RevealOptions } from '@nabuxai/ui-core';

  import type { HTMLAttributes } from 'svelte/elements';
  import type { Snippet } from 'svelte';

  export interface GridProps extends HTMLAttributes<HTMLDivElement> {
    /** Narrowest a column may get before the grid wraps. */
    min?: string;
    gap?: string;
    /** Children rise in one after another when the grid scrolls into view. */
    stagger?: boolean;
    children?: Snippet;
  }

  /** گزینهٔ پایدار — تا اکشن فقط با تغییر وابستگی‌هایش دوباره وصل شود. */
  const REVEAL_STAGGER: RevealOptions = { stagger: true, once: true };
</script>

<script lang="ts">
  /**
   * Grid — the Svelte counterpart of ui-react's display family's Grid:
   * children rise in one after another when the grid scrolls into view.
   */
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';

  let { min, gap, stagger = true, class: className, style, children, ...rest }: GridProps = $props();

  const vars = $derived([min ? `--nx-grid-min: ${min}` : '', gap ? `--nx-grid-gap: ${gap}` : '', style].filter(Boolean).join('; ') || undefined);
</script>

<div
  class={cx('nx-grid', className)}
  data-nx-reveal={stagger ? 'group' : undefined}
  style={vars}
  use:nxBehavior={{ behavior: reveal, options: REVEAL_STAGGER, enabled: stagger }}
  {...rest}
>
  {#if children}{@render children()}{/if}
</div>
