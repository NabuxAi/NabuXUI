<script module lang="ts">
  /** The hand-drawn mark paths, keyed by highlight variant — same curves the react seat draws. */
  export const HIGHLIGHT_MARKS = {
    underline: 'M3 13 C 45 6, 90 5, 130 8 S 185 13, 197 9',
    circle: 'M104 3 C 44 2, 3 7, 4 12 C 5 17, 58 19, 108 18 C 162 17, 197 14, 196 9 C 195 4, 150 1, 88 5',
  } as const;
</script>

<script lang="ts">
  /**
   * Highlight — همتای Svelte برای Highlight در ui-react/components/text.tsx:
   * زیرخط/دایره/ماژیکی که با reveal خودش را می‌کشد.
   */
  import { prefersReducedMotion, reveal } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import { REVEAL_ONCE } from './reveal.svelte';
  import type { Snippet } from 'svelte';
  import type { HTMLAttributes } from 'svelte/elements';

  interface Props extends HTMLAttributes<HTMLSpanElement> {
    variant?: 'underline' | 'circle' | 'marker';
    /** Any CSS colour; the brand gold by default. */
    color?: string;
    delay?: number;
    children?: Snippet;
    class?: string;
    style?: string;
  }

  let { variant = 'underline', color, delay, children, class: className, style, ...rest }: Props = $props();

  // با کاهش حرکت، هیچ رفتاری وصل نمی‌شود (همان شرط ری‌اکت)؛ نشانه می‌ماند.
  const revealable = $derived(!prefersReducedMotion());
  const vars = $derived(
    [color ? `--nx-highlight-color: ${color}` : '', delay ? `--nx-delay: ${delay}ms` : '', style].filter(Boolean).join('; ') || undefined,
  );
</script>

<span
  class={cx('nx-highlight', className)}
  data-variant={variant}
  data-nx-reveal=""
  style={vars}
  use:nxBehavior={{ behavior: reveal, options: REVEAL_ONCE, enabled: revealable }}
  {...rest}
>
  {@render children?.()}
  {#if variant !== 'marker'}
    <svg class="nx-highlight-mark" viewBox="0 0 200 20" preserveAspectRatio="none" aria-hidden="true">
      <path d={HIGHLIGHT_MARKS[variant]} pathLength={1} />
    </svg>
  {/if}
</span>
