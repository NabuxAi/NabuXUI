<script lang="ts">
  /**
   * One kanban card, with its three-dot menu — the markup of one `nx-kanban-card`
   * from the kanban block. The menu is a native popover placed against its
   * trigger (core `place`), roved with the arrow keys (core `roveFocus`), and
   * holds the page's actions plus the "move to" items for the other columns.
   */
  import { type IconName, type Cleanup, place, roveFocus, translate } from '@nabuxai/ui-core';
  import { app } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  export interface CardAction {
    id: string;
    label: string;
    icon?: IconName;
    danger?: boolean;
  }

  export interface CardColumn {
    id: string;
    title: string;
  }

  let {
    card,
    columnId,
    index,
    dragging,
    actions,
    others,
    ondragstart,
    ondragend,
    onmove,
    onaction,
  }: {
    card: { id: string; title: string; meta?: string; tone?: string; assignee?: string };
    columnId: string;
    index: number;
    dragging: boolean;
    actions: CardAction[];
    others: CardColumn[];
    ondragstart: (event: DragEvent, cardId: string) => void;
    ondragend: () => void;
    onmove: (cardId: string, from: string, to: string) => void;
    onaction: (actionId: string, cardId: string) => void;
  } = $props();

  const menuId = $derived(`nx-kanban-menu-${card.id}`);

  let open = $state(false);
  let trigger: HTMLButtonElement;
  let panel: HTMLElement;
  let unplace: Cleanup | null = null;

  const initials = $derived(
    card.assignee
      ? card.assignee
          .split(/\s+/)
          .slice(0, 2)
          .map((part) => Array.from(part)[0])
          .join('')
          .toLocaleUpperCase()
      : '',
  );

  function onToggle() {
    open = panel.matches(':popover-open');
    unplace?.();
    unplace = null;
    if (open) unplace = place(trigger, panel, { side: 'bottom', align: 'end', offset: 6 });
  }

  // An exit is softer and faster than the enter: fold away quickly.
  const closeMenu = () =>
    setTimeout(() => {
      if (panel?.matches(':popover-open')) panel.hidePopover();
    }, 150);

  const choose = (action: CardAction) => {
    onaction(action.id, card.id);
    closeMenu();
  };

  const move = (to: CardColumn) => {
    onmove(card.id, columnId, to.id);
    closeMenu();
  };

  const onKey = (event: KeyboardEvent) => {
    roveFocus(event, event.currentTarget as HTMLElement, '.nx-kanban-menu-choice', { orientation: 'vertical' });
  };

  const menuLabel = $derived(translate(app.lang, 'kanbanCardMenu', { name: card.title }));
  const moveTo = (column: string) => translate(app.lang, 'kanbanMoveTo', { column });
</script>

<li
  class="nx-kanban-card"
  data-key={card.id}
  data-tone={card.tone}
  data-dragging={dragging ? '' : undefined}
  style:--nx-i={index}
  draggable="true"
  ondragstart={(event) => ondragstart(event, card.id)}
  ondragend={ondragend}
>
  {#if card.tone}<span class="nx-kanban-card-dot" aria-hidden="true"></span>{/if}
  <div class="nx-kanban-card-main">
    <p class="nx-kanban-card-title">{card.title}</p>
    {#if card.meta}<p class="nx-kanban-card-meta">{card.meta}</p>{/if}
  </div>
  {#if card.assignee}
    <span class="nx-avatar" data-size="sm" role="img" aria-label={card.assignee}><span aria-hidden="true">{initials}</span></span>
  {/if}
  <button
    bind:this={trigger}
    type="button"
    class="nx-kanban-card-menu"
    aria-haspopup="menu"
    aria-expanded={open}
    aria-controls={menuId}
    aria-label={menuLabel}
    popovertarget={menuId}
  >
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" aria-hidden="true">
      <path d="M12 5.25h.01M12 12h.01M12 18.75h.01" />
    </svg>
  </button>
  <div bind:this={panel} id={menuId} class="nx-kanban-menu" role="menu" tabindex="-1" aria-label={menuLabel} popover="auto" ontoggle={onToggle} onkeydown={onKey}>
    {#each actions as action (action.id)}
      <button type="button" class="nx-kanban-menu-choice" role="menuitem" data-danger={action.danger ? '' : undefined} onclick={() => choose(action)}>
        {#if action.icon}<NxIcon name={action.icon} />{/if}
        <span>{action.label}</span>
      </button>
    {/each}
    {#if actions.length > 0 && others.length > 0}<hr class="nx-kanban-menu-sep" />{/if}
    {#each others as other (other.id)}
      <button type="button" class="nx-kanban-menu-choice" role="menuitem" onclick={() => move(other)}>
        <NxIcon name="chevron-right" />
        <span>{moveTo(other.title)}</span>
      </button>
    {/each}
  </div>
</li>
