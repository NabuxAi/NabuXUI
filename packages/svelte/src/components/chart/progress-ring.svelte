<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface ProgressRingProps {
    /** 0–100. */
    value: number;
    label?: string;
    /** Text in the middle (children); the rounded percentage by default. */
    children?: Snippet;
    size?: string;
    thickness?: string;
    tone?: 'accent' | 'success' | 'warning' | 'danger';
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * ProgressRing — the Svelte counterpart of ui-react's chart family's
   * ProgressRing: same DOM, same nx-* classes, same props.
   */
  import { reveal } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';

  let { value, label, children, size, thickness, tone, class: className }: ProgressRingProps = $props();

  const clamped = $derived(Math.min(100, Math.max(0, value)));
</script>

<div
  class={cx('nx-ring', className)}
  data-nx-reveal=""
  style:--nx-ring-size={size || undefined}
  style:--nx-ring-thickness={thickness || undefined}
  use:nxBehavior={{ behavior: reveal, options: { once: true }, enabled: true }}
>
  <progress class="nx-ring-progress" value={clamped} max="100" aria-label={label} data-tone={tone === 'accent' ? undefined : tone} style:--nx-value={clamped}></progress>
  <span class="nx-ring-label" aria-hidden="true">
    {#if children}{@render children()}{:else}{Math.round(clamped)}%{/if}
  </span>
</div>
