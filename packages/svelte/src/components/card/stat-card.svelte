<script module lang="ts">
  import { reveal, type RevealOptions } from '@nabuxai/ui-core';

  import type { Snippet } from 'svelte';

  export interface StatCardProps {
    label: string | Snippet;
    value: number;
    /** Intl number format for the value (currency, compact notation…). */
    format?: Intl.NumberFormatOptions;
    /** Change in percent against the previous period. */
    delta?: number;
    /** When a rise is bad news (costs, churn), colour it as such. */
    invertDelta?: boolean;
    /** Recent values for the sparkline. */
    trend?: number[];
    caption?: string | Snippet;
    locale?: string;
    class?: string;
  }

  /** گزینهٔ پایدار — تا اکشن فقط با تغییر وابستگی‌هایش دوباره وصل شود. */
  const REVEAL_ONCE: RevealOptions = { once: true };
</script>

<script lang="ts">
  /**
   * StatCard — the Svelte counterpart of ui-react's card family's StatCard.
   * The value rolls up from zero when it scrolls into view — React reaches for
   * the text family's NumberTicker; here the same nx-number roll renders
   * inline so the card seat stays independent of the text seat's shape.
   */
  import { localeDigits, numberParts, type NumberPart } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import { nxIntlLocale } from '../../internal/locale';
  import Icon from '../../internal/icon.svelte';
  import Sparkline from '../chart/sparkline.svelte';

  let { label, value, format, delta, invertDelta, trend, caption, locale, class: className }: StatCardProps = $props();

  const intlLocale = $derived(locale ?? nxIntlLocale());
  const good = $derived(delta === undefined ? undefined : invertDelta ? delta <= 0 : delta >= 0);

  const parts = $derived(numberParts(value, intlLocale, format));
  const digits = $derived(localeDigits(intlLocale));
  const text = $derived(parts.map((p) => p.char).join(''));
</script>

{#snippet digitColumn(part: NumberPart, i: number)}
  {#if part.kind === 'digit'}
    <!-- Keyed from the right so the ones column stays the ones column as the number grows. -->
    <span class="nx-digit" style:--d={part.value} style:--nx-p={parts.length - 1 - i}>
      <span class="nx-digit-track">
        {#each digits as digit (digit)}
          <span>{digit}</span>
        {/each}
      </span>
    </span>
  {:else}
    <span class="nx-number-sep">{part.char}</span>
  {/if}
{/snippet}

<article class={cx('nx-stat', className)}>
  <div class="nx-stat-head">
    <span class="nx-stat-label">{#if typeof label === 'string'}{label}{:else}{@render label()}{/if}</span>
    {#if delta !== undefined}
      <span class="nx-delta" data-trend={good ? 'up' : 'down'}>
        <Icon name={delta >= 0 ? 'trend-up' : 'trend-down'} />
        {new Intl.NumberFormat(intlLocale, { style: 'percent', maximumFractionDigits: 1, signDisplay: 'exceptZero' }).format(delta / 100)}
      </span>
    {/if}
  </div>
  <div class="nx-stat-value">
    <span
      class="nx-number"
      data-nx-reveal=""
      use:nxBehavior={{ behavior: reveal, options: REVEAL_ONCE, enabled: true }}
    >
      <span class="nx-visually-hidden">{text}</span>
      <span class="nx-number-roll" aria-hidden="true">
        {#each parts as part, i (parts.length - i)}
          {@render digitColumn(part, i)}
        {/each}
      </span>
    </span>
  </div>
  {#if trend && trend.length > 1}
    <Sparkline data={trend} trend={good === undefined ? undefined : good ? 'up' : 'down'} />
  {/if}
  {#if caption}
    <p class="nx-stat-caption">{#if typeof caption === 'string'}{caption}{:else}{@render caption()}{/if}</p>
  {/if}
</article>
