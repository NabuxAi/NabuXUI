<script module lang="ts">
  /** Page numbers to show, with null for the gaps: 1 … 4 5 6 … 20. */
  export function pageRange(page: number, count: number, siblings = 1): Array<number | null> {
    const pages = new Set(
      [1, count, ...Array.from({ length: siblings * 2 + 1 }, (_, i) => page - siblings + i)].filter((p) => p >= 1 && p <= count),
    );
    const sorted = [...pages].sort((a, b) => a - b);
    const out: Array<number | null> = [];
    sorted.forEach((p, i) => {
      if (i > 0 && p - sorted[i - 1]! > 1) out.push(p - sorted[i - 1]! === 2 ? p - 1 : null);
      out.push(p);
    });
    return out;
  }

  export interface PaginationProps {
    page: number;
    pageCount: number;
    /** Buttons that call back… */
    onPageChange?: (page: number) => void;
    /** …or links, for server-rendered pages. */
    hrefFor?: (page: number) => string;
    siblings?: number;
    class?: string;
  }
</script>

<script lang="ts">
  import type { MessageKey } from '@nabuxai/ui-core';
  import { type IndicatorController, indicator } from '@nabuxai/ui-core';
  import { nxTranslate } from '../../i18n';
  import { nxLang } from '../../internal/locale';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import SmartLink from '../../internal/smart-link.svelte';

  let { page, pageCount, onPageChange, hrefFor, siblings = 1, class: className }: PaginationProps = $props();

  /** The component's own words, in the page's language. */
  const t = (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(nxLang(), key, params);

  let nav = $state<HTMLElement>();
  let ind: IndicatorController | undefined;

  $effect(() => {
    ind = indicator(nav!);
    return () => {
      ind?.destroy();
      ind = undefined;
    };
  });

  $effect(() => {
    // Re-home when the page or the count changes, like the React seat's
    // [page, pageCount] layout effect.
    page;
    pageCount;
    ind?.update(nav?.querySelector('[aria-current="page"]') ?? null);
  });
</script>

<nav class={cx('nx-pagination', className)} aria-label={t('pagination')} bind:this={nav}>
  <span class="nx-indicator" aria-hidden="true"></span>

  {#snippet pageLink(target: number, extra: Record<string, string | undefined>, kind: 'prev' | 'next' | 'page', p?: number)}
    {@const disabled = target < 1 || target > pageCount}
    {#if hrefFor && !disabled}
      <SmartLink class="nx-page-link" href={hrefFor(target)} aria-disabled={disabled || undefined} {...extra}>
        {#if kind === 'page'}
          {p}
        {:else}
          <Icon name={kind === 'prev' ? 'chevron-left' : 'chevron-right'} />
        {/if}
      </SmartLink>
    {:else}
      <button
        type="button"
        class="nx-page-link"
        disabled={disabled}
        onclick={() => onPageChange?.(target)}
        aria-disabled={disabled || undefined}
        {...extra}
      >
        {#if kind === 'page'}
          {p}
        {:else}
          <Icon name={kind === 'prev' ? 'chevron-left' : 'chevron-right'} />
        {/if}
      </button>
    {/if}
  {/snippet}

  {@render pageLink(page - 1, { 'aria-label': t('previous') }, 'prev')}

  {#each pageRange(page, pageCount, siblings) as p, i}
    {#if p === null}
      <span class="nx-page-link" data-gap="" aria-hidden="true">…</span>
    {:else if p === page}
      {@render pageLink(p, { 'aria-current': 'page', 'aria-label': t('page', { page: p }) }, 'page', p)}
    {:else}
      {@render pageLink(p, { 'aria-label': t('page', { page: p }) }, 'page', p)}
    {/if}
  {/each}

  {@render pageLink(page + 1, { 'aria-label': t('next') }, 'next')}
</nav>
