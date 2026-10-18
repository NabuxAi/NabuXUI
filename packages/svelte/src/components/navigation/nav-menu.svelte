<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface NavLinkItem {
    href: string;
    label: string | Snippet;
    current?: boolean;
  }

  export interface NavMenuProps {
    items: NavLinkItem[];
    'aria-label'?: string;
    class?: string;
  }
</script>

<script lang="ts">
  import { type IndicatorController, indicator } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import SmartLink from '../../internal/smart-link.svelte';
  import Content from '../../internal/content.svelte';

  let { items, 'aria-label': label, class: className }: NavMenuProps = $props();

  let nav = $state<HTMLElement>();
  let ind: IndicatorController | undefined;

  $effect(() => {
    ind = indicator(nav!);
    return () => {
      ind?.destroy();
      ind = undefined;
    };
  });

  const home = () => nav?.querySelector('[aria-current="page"]') ?? null;

  $effect(() => {
    items;
    ind?.update(home());
  });

  const follow = (event: Event) => {
    const link = (event.target as Element).closest('.nx-navmenu-link');
    if (link) ind?.update(link);
  };
</script>

<nav
  class={cx('nx-navmenu', className)}
  aria-label={label}
  bind:this={nav}
  onpointerover={follow}
  onfocusin={follow}
  onpointerleave={() => ind?.update(home())}
  onfocusout={() => ind?.update(home())}
>
  <ul>
    {#each items as item (item.href)}
      <li>
        <SmartLink class="nx-navmenu-link" href={item.href} aria-current={item.current ? 'page' : undefined}>
          <Content of={item.label} />
        </SmartLink>
      </li>
    {/each}
  </ul>
  <span class="nx-indicator" aria-hidden="true"></span>
</nav>
