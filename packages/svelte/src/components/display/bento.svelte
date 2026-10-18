<script module lang="ts">
  import { reveal, type RevealOptions } from '@nabuxai/ui-core';
  import { nxB } from '../../internal/bind';

  import type { HTMLAttributes } from 'svelte/elements';
  import type { Snippet } from 'svelte';

  export interface BentoProps extends HTMLAttributes<HTMLDivElement> {
    columns?: number;
    /** Minimum row height. */
    rowHeight?: string;
    children?: Snippet;
  }

  /** گزینهٔ پایدار — تا اکشن فقط با تغییر وابستگی‌هایش دوباره وصل شود. */
  const REVEAL_STAGGER: RevealOptions = { stagger: true, once: true };
  const nxBentoReveal = nxB(reveal, REVEAL_STAGGER);
</script>

<script lang="ts">
  /**
   * Bento — the Svelte counterpart of ui-react's display family's Bento:
   * same DOM, same nx-* classes, same props.
   */
  import { cx } from '../../internal/cx';

  let { columns = 3, rowHeight, class: className, style, children, ...rest }: BentoProps = $props();

  const vars = $derived([`--nx-cols: ${columns}`, rowHeight ? `--nx-row: ${rowHeight}` : '', style].filter(Boolean).join('; ') || undefined);
</script>

<div class={cx('nx-bento', className)} data-nx-reveal="group" style={vars} use:nxBentoReveal {...rest}>
  {#if children}{@render children()}{/if}
</div>
