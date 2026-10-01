<script lang="ts">
  /**
   * The admin topbar — the markup `AdminTopbar` renders in React: the drawer
   * invoker, the page heading, the search seat (this flavour jumps to the users
   * page, where the real search box lives), the actions children (theme toggle,
   * language menu) and the avatar's popover menu (native popover + core `place`
   * and `roveFocus`).
   */
  import { type IconName, type Cleanup, place, roveFocus, translate } from '@nabuxai/ui-core';
  import { app } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  export interface UserEntry {
    id: string;
    label: string;
    icon?: IconName;
  }

  export interface TopbarUser {
    name: string;
    role: string;
    menuLabel: string;
    menu: UserEntry[];
  }

  let {
    title,
    subtitle = undefined,
    searchPlaceholder,
    searchHint,
    user,
    drawerId,
    drawerOpen,
    onsearch,
    onmenu,
    children,
  }: {
    title: string;
    subtitle?: string;
    searchPlaceholder: string;
    searchHint: string;
    user: TopbarUser;
    drawerId: string;
    drawerOpen: boolean;
    onsearch?: () => void;
    onmenu?: (id: string) => void;
    children?: import('svelte').Snippet;
  } = $props();

  const menuId = 'nx-admin-user-menu';

  let userTrigger: HTMLButtonElement;
  let userPanel: HTMLElement;
  let userOpen = $state(false);
  let unplace: Cleanup | null = null;

  const initials = $derived(
    user.name
      .split(/\s+/)
      .filter(Boolean)
      .slice(0, 2)
      .map((part) => Array.from(part)[0])
      .join('')
      .toLocaleUpperCase(),
  );

  // The panel's own toggle event is the one source of truth; opening it also
  // places it against the trigger.
  function onToggle() {
    userOpen = userPanel.matches(':popover-open');
    unplace?.();
    unplace = null;
    if (userOpen) unplace = place(userTrigger, userPanel, { side: 'bottom', align: 'end', offset: 8 });
  }

  // Closing the menu from inside returns focus to the avatar.
  $effect(() => {
    if (userOpen) return;
    if (userPanel?.contains(document.activeElement)) userTrigger?.focus();
  });

  const choose = (entry: UserEntry) => {
    userPanel?.hidePopover?.();
    onmenu?.(entry.id);
  };

  const onMenuKey = (event: KeyboardEvent) => {
    roveFocus(event, event.currentTarget as HTMLElement, '.nx-admin-user-item', { orientation: 'vertical' });
  };

  const word = (key: 'menu' | 'close') => translate(app.lang, key);
</script>

<div class="nx-admin-topbar-start">
  <button type="button" class="nx-admin-menu" popovertarget={drawerId} aria-controls={drawerId} aria-expanded={drawerOpen}>
    <NxIcon name="menu" />
    <span class="nx-visually-hidden">{word('menu')}</span>
  </button>
  <div class="nx-admin-heading">
    <p class="nx-admin-title">{title}</p>
    {#if subtitle}<p class="nx-admin-subtitle">{subtitle}</p>{/if}
  </div>
  <div class="nx-admin-search">
    <button type="button" class="nx-admin-search-btn" onclick={() => onsearch?.()}>
      <NxIcon name="search" />
      <span>{searchPlaceholder}</span>
      <kbd class="nx-kbd">{searchHint}</kbd>
    </button>
  </div>
  <div class="nx-admin-actions">
    {@render children?.()}
    <button
      bind:this={userTrigger}
      type="button"
      class="nx-admin-user"
      aria-haspopup="menu"
      aria-expanded={userOpen}
      aria-controls={menuId}
      popovertarget={menuId}
      aria-label={user.menuLabel}
    >
      <span class="nx-avatar" role="img" aria-label={user.name}><span aria-hidden="true">{initials}</span></span>
      <span class="nx-admin-user-text">
        <span class="nx-admin-user-name">{user.name}</span>
        <span class="nx-admin-user-role">{user.role}</span>
      </span>
      <NxIcon name="chevron-down" />
    </button>
    <div bind:this={userPanel} id={menuId} class="nx-admin-user-menu" popover="auto" ontoggle={onToggle}>
      <div class="nx-admin-user-head">
        <p class="nx-admin-user-name">{user.name}</p>
        <p class="nx-admin-user-role">{user.role}</p>
      </div>
      <div role="menu" tabindex="-1" aria-label={user.menuLabel} onkeydown={onMenuKey}>
        {#each user.menu as entry (entry.id)}
          <button type="button" class="nx-admin-user-item" role="menuitem" onclick={() => choose(entry)}>
            {#if entry.icon}<NxIcon name={entry.icon} />{/if}
            <span>{entry.label}</span>
          </button>
        {/each}
      </div>
    </div>
  </div>
</div>
