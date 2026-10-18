<script lang="ts">
  /**
   * NumberTicker — همتای Svelte برای NumberTicker در ui-react/components/text.tsx:
   * ارقامِ محلی‌شده در ستون‌های غلتان.
   */
  import { localeDigits, numberParts, reveal } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import { nxIntlLocale } from '../../internal/locale';
  import { REVEAL_ONCE } from './reveal.svelte';
  import type { HTMLAttributes } from 'svelte/elements';

  interface Props extends HTMLAttributes<HTMLSpanElement> {
    value: number;
    /** An Intl locale; defaults to the page's language. */
    locale?: string;
    format?: Intl.NumberFormatOptions;
    /** Roll up from zero when it scrolls into view (default) or show the value at once. */
    reveal?: boolean;
    /** Roll duration, ms. */
    duration?: number;
    class?: string;
    style?: string;
  }

  let { value, locale, format, reveal: rollIn = true, duration, class: className, style, ...rest }: Props = $props();

  const intlLocale = $derived(locale ?? nxIntlLocale());
  const parts = $derived(numberParts(value, intlLocale, format));
  const digits = $derived(localeDigits(intlLocale));
  const text = $derived(parts.map((p) => p.char).join(''));
  const vars = $derived([duration ? `--nx-number-duration: ${duration}ms` : '', style].filter(Boolean).join('; ') || undefined);
</script>

<span
  class={cx('nx-number', className)}
  data-nx-reveal={rollIn ? '' : undefined}
  style={vars}
  use:nxBehavior={{ behavior: reveal, options: REVEAL_ONCE, enabled: rollIn }}
  {...rest}
>
  <span class="nx-visually-hidden">{text}</span>
  <span class="nx-number-roll" aria-hidden="true">
    {#each parts as part, i (parts.length - i)}
      {#if part.kind === 'digit'}
        <!-- کلید از راست تا ستون یکان با بزرگ‌شدن عدد، یکان بماند. -->
        <span class="nx-digit" style="--d: {part.value}; --nx-p: {parts.length - 1 - i}">
          <span class="nx-digit-track">
            {#each digits as digit, d (d)}
              <span>{digit}</span>
            {/each}
          </span>
        </span>
      {:else}
        <span class="nx-number-sep">{part.char}</span>
      {/if}
    {/each}
  </span>
</span>
