<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { NavItem } from './nav';

  export interface MobileMenuProps {
    items: NavItem[];
    /** Called after a link is chosen (close the drawer here). */
    onNavigate?: () => void;
    footer?: string | Snippet;
  }
</script>

<script lang="ts">
  import Icon from '../../internal/icon.svelte';
  import SmartLink from '../../internal/smart-link.svelte';
  import Content from '../../internal/content.svelte';
  import { nxTranslate } from '../../i18n';
  import { nxLang } from '../../internal/locale';
  import type { MessageKey } from '@nabuxai/ui-core';

  let { items, onNavigate, footer }: MobileMenuProps = $props();

  /** The component's own words, in the page's language. */
  const t = (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(nxLang(), key, params);

  let sub = $state<number | null>(null);
  let rootPanel = $state<HTMLDivElement>();
  let subPanel = $state<HTMLDivElement>();
  let opener = $state<HTMLButtonElement | null>(null);

  // The panel that is scrolled away gets `inert`, the same attribute dance the
  // React seat does through useInert.
  $effect(() => {
    rootPanel?.toggleAttribute('inert', sub !== null);
    subPanel?.toggleAttribute('inert', sub === null);
  });

  $effect(() => {
    if (sub !== null) subPanel?.querySelector<HTMLElement>('.nx-drilldown-back')?.focus();
    else opener?.focus();
  });

  const current = $derived(sub === null ? null : items[sub]);
</script>

<div class="nx-drilldown">
  <div class="nx-drilldown-track" style={`--nx-level: ${sub === null ? 0 : 1}`}>
    <div class="nx-drilldown-panel" bind:this={rootPanel}>
      {#each items as item, i}
        {#if item.children}
          <button
            type="button"
            class="nx-drilldown-item"
            style={`--nx-i: ${i}`}
            aria-expanded={sub === i}
            onclick={(event) => {
              opener = event.currentTarget;
              sub = i;
            }}
          >
            <span>
              <Content of={item.label} />
            </span>
            <Icon name="chevron-right" />
          </button>
        {:else}
          <SmartLink
            class="nx-drilldown-item"
            href={item.href ?? '#'}
            aria-current={item.current ? 'page' : undefined}
            style={`--nx-i: ${i}`}
            onclick={onNavigate}
          >
            <span>
              <Content of={item.label} />
            </span>
          </SmartLink>
        {/if}
      {/each}
      {#if footer}
        <div style="margin-block-start: var(--nx-space-4); display: grid; gap: var(--nx-space-2)">
          <Content of={footer} />
        </div>
      {/if}
    </div>
    <div class="nx-drilldown-panel" bind:this={subPanel}>
      {#if current?.children}
        <button type="button" class="nx-drilldown-back" onclick={() => (sub = null)}>
          <Icon name="chevron-left" />
          {t('back')}
        </button>
        {#each current.children as child, c}
          <SmartLink class="nx-drilldown-item" href={child.href} style={`--nx-i: ${c}`} onclick={onNavigate}>
            <span>
              <Content of={child.label} />
            </span>
            {#if child.icon}
              <Icon name={child.icon} />
            {/if}
          </SmartLink>
        {/each}
      {/if}
    </div>
  </div>
</div>
