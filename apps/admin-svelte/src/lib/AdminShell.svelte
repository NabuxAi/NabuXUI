<script lang="ts">
  /**
   * The admin shell frame — the markup `AdminShell` renders in React: the frame
   * grid (sidebar + main), the topbar row, the scroll area, and the mobile
   * drawer as a native popover holding a second, always-expanded copy of the
   * sidebar.
   */
  import { translate } from '@nabuxai/ui-core';
  import { app, strings } from '../store.svelte';
  import { router } from '../router.svelte';
  import AdminSidebar, { type SidebarGroup } from './AdminSidebar.svelte';
  import AdminTopbar, { type TopbarUser } from './AdminTopbar.svelte';
  import NxIcon from './NxIcon.svelte';

  let {
    active,
    title,
    subtitle = undefined,
    groups,
    user,
    onsearch,
    onmenu,
    actions,
    children,
  }: {
    active: string;
    title: string;
    subtitle?: string;
    groups: SidebarGroup[];
    user: TopbarUser;
    onsearch?: () => void;
    onmenu?: (id: string) => void;
    actions?: import('svelte').Snippet;
    children?: import('svelte').Snippet;
  } = $props();

  const drawerId = 'nx-admin-drawer';

  let collapsed = $state(false);
  let drawerOpen = $state(false);
  let root: HTMLElement;
  let content: HTMLElement;
  let drawer: HTMLElement;

  function onToggle(event: Event) {
    drawerOpen = (event as ToggleEvent).newState === 'open';
    if (!drawerOpen && drawer.contains(document.activeElement)) {
      root?.querySelector<HTMLElement>('.nx-admin-menu')?.focus();
    }
  }

  const closeDrawer = () => {
    if (drawer?.matches(':popover-open')) drawer.hidePopover();
  };

  // A new page starts from the top of the shell's scroll area.
  $effect(() => {
    void router.route;
    content?.scrollTo({ top: 0 });
  });

  const s = $derived(strings());
  const menuWord = $derived(translate(app.lang, 'menu'));
  const closeWord = $derived(translate(app.lang, 'close'));
</script>

<div bind:this={root} class="nx-admin">
  <div class="nx-admin-frame" data-collapsed={collapsed || undefined}>
    <AdminSidebar
      {groups}
      {active}
      brand={s.app.brand}
      navLabel={s.app.navLabel}
      footerText={s.app.tagline}
      {collapsed}
      ontoggle={() => (collapsed = !collapsed)}
    />
    <div class="nx-admin-main">
      <header class="nx-admin-topbar">
        <AdminTopbar {title} {subtitle} searchPlaceholder={s.app.searchPlaceholder} searchHint={s.app.searchHint} {user} {drawerId} {drawerOpen} {onsearch} {onmenu}>
          {#if actions}{@render actions()}{/if}
        </AdminTopbar>
      </header>
      <div bind:this={content} class="nx-admin-content">
        {@render children?.()}
      </div>
    </div>
  </div>
  <div bind:this={drawer} id={drawerId} class="nx-admin-drawer" popover="auto" aria-label={menuWord} ontoggle={onToggle}>
    <button type="button" class="nx-admin-drawer-close" onclick={closeDrawer}>
      <NxIcon name="x" />
      <span class="nx-visually-hidden">{closeWord}</span>
    </button>
    <AdminSidebar
      {groups}
      {active}
      brand={s.app.brand}
      navLabel={s.app.navLabel}
      footerText={s.app.tagline}
      collapsed={false}
      measure={drawerOpen ? 1 : 0}
    />
  </div>
</div>
