<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface BreadcrumbItem {
    label: string | Snippet;
    href?: string;
  }

  export interface BreadcrumbsProps {
    items: BreadcrumbItem[];
    class?: string;
  }
</script>

<script lang="ts">
  import type { MessageKey } from '@nabuxai/ui-core';
  import { nxTranslate } from '../../i18n';
  import { nxLang } from '../../internal/locale';
  import { cx } from '../../internal/cx';
  import SmartLink from '../../internal/smart-link.svelte';
  import Content from '../../internal/content.svelte';

  let { items, class: className }: BreadcrumbsProps = $props();

  /** The component's own words, in the page's language. */
  const t = (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(nxLang(), key, params);
</script>

<nav class={cx('nx-breadcrumbs', className)} aria-label={t('breadcrumb')}>
  <ol>
    {#each items as item, i}
      {@const last = i === items.length - 1}
      <li>
        {#if item.href && !last}
          <SmartLink href={item.href}>
            <Content of={item.label} />
          </SmartLink>
        {:else}
          <span
            aria-current={last ? 'page' : undefined}
            style={last ? 'color: var(--nx-text); font-weight: 600' : undefined}
          >
            <Content of={item.label} />
          </span>
        {/if}
      </li>
    {/each}
  </ol>
</nav>
