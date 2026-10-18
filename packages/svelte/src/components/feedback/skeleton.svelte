<script module lang="ts">
  export interface SkeletonProps {
    shape?: 'text' | 'circle' | 'rect' | 'block';
    /** For text: how many lines (the last one shorter). */
    lines?: number;
    width?: string;
    height?: string;
    class?: string;
    style?: string;
  }
</script>

<script lang="ts">
  /**
   * Skeleton — the Svelte counterpart of ui-react's feedback family's
   * Skeleton: same DOM, same nx-* classes, same props.
   */
  import { cx } from '../../internal/cx';

  let { shape = 'text', lines = 1, width, height, class: className, style }: SkeletonProps = $props();

  const itemStyle = $derived(
    [
      width ? `inline-size: ${width}` : '',
      height ? `block-size: ${height}` : '',
      shape === 'circle' && width ? `--nx-skeleton-size: ${width}` : '',
      style,
    ]
      .filter(Boolean)
      .join('; ') || undefined,
  );
</script>

{#snippet item()}
  <span class={cx('nx-skeleton', className)} data-shape={shape === 'block' ? undefined : shape} aria-hidden="true" style={itemStyle}></span>
{/snippet}

{#if shape !== 'text' || lines <= 1}
  {@render item()}
{:else}
  <span style="display: grid; gap: 0.6em">
    {#each Array.from({ length: lines }) as _, i (i)}
      {@render item()}
    {/each}
  </span>
{/if}
