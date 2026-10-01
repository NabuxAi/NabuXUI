<script lang="ts">
  /**
   * The admin sidebar — the same markup `AdminSidebar` renders in React
   * (nx-admin-sidebar): brand, grouped nav with the spring indicator under the
   * current item (core `indicator`), and the collapse toggle. Items are plain
   * `href="#/…"` links, so the hash router carries navigation. Mounted twice by
   * the shell: the frame copy follows `collapsed`, the drawer copy stays open.
   */
  import { onMount } from 'svelte';
  import { type IconName, type Cleanup, indicator, translate } from '@nabuxai/ui-core';
  import { app, numberFmt } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  export interface SidebarItem {
    id: string;
    label: string;
    icon?: IconName;
    href: string;
    badge?: number;
  }

  export interface SidebarGroup {
    id?: string;
    label: string;
    items: SidebarItem[];
  }

  let {
    groups,
    active,
    brand,
    navLabel,
    footerText = undefined,
    collapsed,
    measure = 0,
    ontoggle,
  }: {
    groups: SidebarGroup[];
    active: string;
    brand: string;
    navLabel: string;
    footerText?: string;
    collapsed: boolean;
    /** Bumping this re-measures the indicator (the drawer had no box until it opened). */
    measure?: number;
    ontoggle?: () => void;
  } = $props();

  let nav: HTMLElement;

  const mark = $derived(Array.from(brand)[0] ?? 'N');

  let ind: ReturnType<typeof indicator> | null = null;

  const move = () => {
    ind?.update(nav?.querySelector(`[data-value="${CSS.escape(active)}"]`) ?? null);
  };

  onMount(() => {
    ind = indicator(nav);
    move();
    return () => ind?.destroy();
  });

  // The current item, a language switch (labels resize) and the rail collapse
  // (the indicator's own ResizeObserver re-measures mid-flight) all re-aim here.
  $effect(() => {
    void active;
    void groups;
    void measure;
    requestAnimationFrame(move);
  });

  const word = (key: 'collapseSidebar' | 'expandSidebar') => translate(app.lang, key);
</script>

<aside class="nx-admin-sidebar" data-collapsed={collapsed || undefined}>
  <div class="nx-admin-brand">
    <span class="nx-admin-mark" aria-hidden="true">{mark}</span>
    <span class="nx-admin-brand-text">{brand}</span>
  </div>
  <nav bind:this={nav} class="nx-admin-nav" aria-label={navLabel}>
    <span class="nx-indicator" aria-hidden="true"></span>
    {#each groups as group (group.id ?? group.label)}
      <section class="nx-admin-group">
        <h3 class="nx-admin-group-label">{group.label}</h3>
        <ul>
          {#each group.items as item (item.id)}
            <li>
              <a class="nx-admin-item" href={item.href} data-value={item.id} data-label={item.label} aria-current={item.id === active ? 'page' : undefined}>
                <NxIcon name={item.icon ?? 'grid'} />
                <span class="nx-admin-label">{item.label}</span>
                {#if item.badge !== undefined}<span class="nx-admin-badge">{numberFmt().format(item.badge)}</span>{/if}
              </a>
            </li>
          {/each}
        </ul>
      </section>
    {/each}
  </nav>
  <div class="nx-admin-sidebar-foot">
    {#if footerText}<span class="nx-admin-brand-text">{footerText}</span>{/if}
    <button
      type="button"
      class="nx-admin-item nx-admin-toggle"
      aria-expanded={!collapsed}
      data-label={collapsed ? word('expandSidebar') : word('collapseSidebar')}
      onclick={() => ontoggle?.()}
    >
      <NxIcon name="chevron-left" />
      <span class="nx-admin-label">{collapsed ? word('expandSidebar') : word('collapseSidebar')}</span>
    </button>
  </div>
</aside>
