<script lang="ts">
  /**
   * GradientText — همتای Svelte برای GradientText در ui-react/components/text.tsx.
   */
  import { cx } from '../../internal/cx';
  import type { Snippet } from 'svelte';
  import type { HTMLAttributes } from 'svelte/elements';

  interface Props extends HTMLAttributes<HTMLElement> {
    variant?: 'text' | 'brand' | 'gold';
    /** Let the gradient drift slowly. */
    animate?: boolean;
    /** The element to render — React's polymorphic `as`. */
    as?: string;
    children?: Snippet;
    class?: string;
  }

  let { variant = 'text', animate, as = 'span', children, class: className, ...rest }: Props = $props();
</script>

<svelte:element
  this={as}
  class={cx('nx-gradient-text', className)}
  data-variant={variant === 'text' ? undefined : variant}
  data-animate={animate ? '' : undefined}
  {...rest}
>
  {@render children?.()}
</svelte:element>
