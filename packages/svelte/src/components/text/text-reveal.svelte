<script lang="ts">
  /**
   * TextReveal — همتای Svelte برای TextReveal در ui-react/components/text.tsx:
   * متن را واژه‌به‌واژه یا نویسه‌به‌نویسه آشکار می‌کند.
   */
  import { reveal, splitText, textDirection, type SplitBy } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import { REVEAL_ONCE } from './reveal.svelte';
  import type { HTMLAttributes } from 'svelte/elements';

  interface Props extends HTMLAttributes<HTMLElement> {
    /** متن — همتای children رشته‌ای در React. */
    text: string;
    /** Split into words or characters (Persian and Arabic always split by word). */
    by?: SplitBy;
    /** Play once when it enters the view, or brighten word by word as it scrolls. */
    trigger?: 'view' | 'scroll';
    /** Delay before the first piece, ms. */
    delay?: number;
    /** The element to render — React's polymorphic `as`. */
    as?: string;
    class?: string;
    style?: string;
  }

  let { text, by = 'word', trigger = 'view', delay, as = 'span', class: className, style, ...rest }: Props = $props();

  const pieces = $derived(splitText(text, by));
  const dir = $derived(textDirection(text));
  const vars = $derived([delay ? `--nx-delay: ${delay}ms` : '', style].filter(Boolean).join('; ') || undefined);
</script>

<svelte:element
  this={as}
  class={cx('nx-text-reveal', className)}
  data-by={by}
  data-trigger={trigger}
  data-nx-reveal={trigger === 'view' ? '' : undefined}
  style={vars}
  use:nxBehavior={{ behavior: reveal, options: REVEAL_ONCE, enabled: trigger === 'view' }}
  {...rest}
>
  <span class="nx-visually-hidden">{text}</span>
  <!-- قطعه‌ها جعبه‌اند و در جهت ظرف چیده می‌شوند: جهت متنِ خودش را می‌گیرند. -->
  <span class="nx-text-pieces" aria-hidden="true" dir={dir}>
    {#each pieces as piece, i}
      <span class="nx-text-piece" style="--nx-i: {i}">{piece}</span>
    {/each}
  </span>
</svelte:element>
