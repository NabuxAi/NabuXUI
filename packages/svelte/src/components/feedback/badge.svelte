<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface BadgeProps {
    tone?: 'neutral' | 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';
    variant?: 'soft' | 'solid' | 'outline';
    size?: 'md' | 'lg';
    /** A leading dot; `pulse` makes it breathe (for live states). */
    dot?: boolean;
    pulse?: boolean;
    class?: string;
    children?: Snippet;
  }
</script>

<script lang="ts">
  /**
   * Badge — the Svelte counterpart of ui-react's feedback family's Badge:
   * same DOM, same nx-* classes, same props.
   */
  import { cx } from '../../internal/cx';

  let { tone = 'neutral', variant, size, dot, pulse, class: className, children }: BadgeProps = $props();
</script>

<span
  class={cx('nx-badge', className)}
  data-tone={tone === 'neutral' ? undefined : tone}
  data-variant={variant === 'soft' ? undefined : variant}
  data-size={size === 'lg' ? 'lg' : undefined}
  data-dot={dot || pulse ? '' : undefined}
  data-pulse={pulse ? '' : undefined}
>
  {#if children}{@render children()}{/if}
</span>
