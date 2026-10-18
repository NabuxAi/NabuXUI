<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { IconName } from '@nabuxai/ui-core';

  export interface DockItem {
    label: string;
    icon: IconName | Snippet;
    href?: string;
    onClick?: () => void;
    current?: boolean;
  }

  export interface DockProps {
    items: DockItem[];
    variant?: 'glass' | 'metal';
    /** Resting icon size. */
    size?: string;
    'aria-label'?: string;
    class?: string;
  }
</script>

<script lang="ts">
  import { dock as dockBehavior } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import SmartLink from '../../internal/smart-link.svelte';
  import { nxB } from '../../internal/bind';

  let { items, variant = 'glass', size, 'aria-label': label, class: className }: DockProps = $props();

  // The dock behaviour as a plain action — the React seat's useBehavior(ref, dock, {}).
  const dockAction = nxB(dockBehavior, {});
</script>

<nav
  class={cx('nx-dock', className)}
  data-variant={variant === 'metal' ? 'metal' : undefined}
  aria-label={label}
  style={size ? `--nx-dock-size: ${size}` : undefined}
  use:dockAction
>
  {#each items as item (item.label)}
    {#if item.href}
      <SmartLink
        class="nx-dock-item"
        href={item.href}
        aria-label={item.label}
        aria-current={item.current ? 'page' : undefined}
      >
        {#if typeof item.icon === 'string'}
          <Icon name={item.icon} />
        {:else}
          {@render item.icon()}
        {/if}
        <span class="nx-dock-label" aria-hidden="true">{item.label}</span>
      </SmartLink>
    {:else}
      <button type="button" class="nx-dock-item" aria-label={item.label} onclick={item.onClick}>
        {#if typeof item.icon === 'string'}
          <Icon name={item.icon} />
        {:else}
          {@render item.icon()}
        {/if}
        <span class="nx-dock-label" aria-hidden="true">{item.label}</span>
      </button>
    {/if}
  {/each}
</nav>
