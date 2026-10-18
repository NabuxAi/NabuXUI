<script lang="ts">
  /**
   * ShimmerText — همتای Svelte برای ShimmerText در ui-react/components/text.tsx:
   * نوار نور (sweep) یا موج نویسه‌ها (wave).
   */
  import { splitText, textDirection } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import type { HTMLAttributes } from 'svelte/elements';

  interface Props extends HTMLAttributes<HTMLElement> {
    /** متن — همتای children رشته‌ای در React. */
    text: string;
    /** A band of light passing over (sweep) or letters rising in a wave. */
    variant?: 'sweep' | 'wave';
    /** Seconds per pass. */
    duration?: number;
    /** The element to render — React's polymorphic `as`. */
    as?: string;
    class?: string;
    style?: string;
  }

  let { text, variant = 'sweep', duration, as = 'span', class: className, style, ...rest }: Props = $props();

  const pieces = $derived(variant === 'wave' ? splitText(text, 'char') : []);
  const dir = $derived(textDirection(text));
  const vars = $derived([duration ? `--nx-shimmer-duration: ${duration}s` : '', style].filter(Boolean).join('; ') || undefined);
</script>

{#if variant === 'sweep'}
  <svelte:element this={as} class={cx('nx-shimmer', className)} style={vars} {...rest}>
    {text}
  </svelte:element>
{:else}
  <svelte:element this={as} class={cx('nx-shimmer', className)} data-variant="wave" style={vars} {...rest}>
    <span class="nx-visually-hidden">{text}</span>
    <span aria-hidden="true" dir={dir}>
      {#each pieces as piece, i}
        <span class="nx-text-piece" style="--nx-i: {i}">{piece}</span>
      {/each}
    </span>
  </svelte:element>
{/if}
