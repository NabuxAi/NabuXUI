<script module lang="ts">
  /** Reveal options kept stable so the action only re-attaches when its reactive deps change. */
  export const REVEAL_ONCE = { once: true } as const;
</script>

<script lang="ts">
  /**
   * Reveal — همتای Svelte برای Reveal در ui-react/components/text.tsx:
   * عنصر را با ورود به دید آشکار می‌کند.
   */
  import { reveal } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import type { Snippet } from 'svelte';
  import type { HTMLAttributes } from 'svelte/elements';

  export type RevealVariant = 'rise' | 'fade' | 'scale' | 'blur' | 'start' | 'end';

  interface Props extends HTMLAttributes<HTMLElement> {
    /** How it arrives. */
    variant?: RevealVariant;
    /** Stagger direct children (each gets --nx-i). */
    stagger?: boolean;
    /** Extra delay in ms. */
    delay?: number;
    /** Reveal again each time it re-enters the view. */
    repeat?: boolean;
    /** The element to render — React's polymorphic `as`. */
    as?: string;
    children?: Snippet;
    class?: string;
    style?: string;
  }

  let {
    variant = 'rise',
    stagger,
    delay,
    repeat,
    as = 'div',
    children,
    class: className,
    style,
    ...rest
  }: Props = $props();

  const options = $derived({ stagger, once: !repeat });
  const vars = $derived([delay ? `--nx-delay: ${delay}ms` : '', style].filter(Boolean).join('; ') || undefined);
</script>

<svelte:element
  this={as}
  class={className}
  data-nx-reveal={stagger ? 'group' : variant}
  style={vars}
  use:nxBehavior={{ behavior: reveal, options, enabled: true }}
  {...rest}
>
  {@render children?.()}
</svelte:element>
