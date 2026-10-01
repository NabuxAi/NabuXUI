<script lang="ts">
  /**
   * The kanban board — the hand-written Svelte twin of the `kanban` block
   * (nx-kanban): native HTML5 drag & drop with the breathing placeholder pill
   * at the drop point, the FLIP glide after every reorder (core `snapshotRows`
   * + `playRowFlip`), the per-column quick-add composer and each card's
   * three-dot menu (KanbanCard). The page owns the columns; reorders, additions
   * and menu picks come back up through the callbacks.
   */
  import { onMount, tick } from 'svelte';
  import { type RowSnapshot, playRowFlip, reveal, snapshotRows, translate } from '@nabuxai/ui-core';
  import { app, numberFmt } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';
  import KanbanCard, { type CardAction, type CardColumn } from './KanbanCard.svelte';

  export type KanbanTone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';

  export interface KanbanCardData {
    id: string;
    title: string;
    meta?: string;
    tone?: KanbanTone;
    assignee?: string;
  }

  export interface KanbanColumn {
    id: string;
    title: string;
    tone?: KanbanTone;
    cards: KanbanCardData[];
  }

  export interface KanbanMove {
    card: string;
    from: string;
    to: string;
    index: number;
  }

  let {
    columns,
    label = undefined,
    height = undefined,
    addPlaceholder = undefined,
    actions = [],
    onchange,
    onmove,
    onadd,
    onaction,
  }: {
    columns: KanbanColumn[];
    label?: string;
    height?: string;
    addPlaceholder?: string;
    actions?: CardAction[];
    onchange?: (columns: KanbanColumn[]) => void;
    onmove?: (move: KanbanMove) => void;
    onadd?: (payload: { column: string; title: string }) => void;
    onaction?: (payload: { id: string; cardId: string }) => void;
  } = $props();

  let board: HTMLElement;
  let dragging = $state<string | null>(null);
  let over = $state<{ column: string; index: number } | null>(null);
  let adding = $state<string | null>(null);
  let announced = $state('');
  let flight: RowSnapshot | null = null;

  const cardsOf = () => board?.querySelectorAll<HTMLElement>('.nx-kanban-card[data-key]') ?? [];

  onMount(() => reveal(board, { once: true }));

  // After the page hands the new columns back, the cards glide to their places.
  $effect(() => {
    void columns;
    if (!flight) return;
    const before = flight;
    flight = null;
    requestAnimationFrame(() => playRowFlip(cardsOf(), before));
  });

  /** Where a pointer's Y falls in `list`: before which card (its index, or the end). */
  function dropIndex(list: HTMLElement, y: number): number {
    const source = dragging ? (board?.querySelector<HTMLElement>(`.nx-kanban-card[data-key="${CSS.escape(dragging)}"]`) ?? null) : null;
    const cards = Array.from(list.querySelectorAll<HTMLElement>('.nx-kanban-card')).filter((card) => card !== source);
    for (let i = 0; i < cards.length; i++) {
      const rect = cards[i]!.getBoundingClientRect();
      if (y < rect.top + rect.height / 2) return i;
    }
    return cards.length;
  }

  // The interleaved render list: the placeholder sits among the column's *other*
  // cards, so it shows where the dragged card will land once it leaves its slot.
  interface Slot {
    key: string;
    card?: KanbanCardData;
    index: number;
    placeholder?: boolean;
  }

  const slotsOf = (column: KanbanColumn): Slot[] => {
    const here = over?.column === column.id ? over.index : null;
    const items: Slot[] = [];
    let landing = 0;
    for (const card of column.cards) {
      if (card.id !== dragging) {
        if (here === landing) items.push({ key: `ph-${column.id}-${landing}`, index: landing, placeholder: true });
        landing += 1;
      }
      items.push({ key: card.id, card, index: landing });
    }
    if (here !== null && here >= landing) items.push({ key: `ph-${column.id}-end`, index: landing, placeholder: true });
    return items;
  };

  const commit = (move: KanbanMove) => {
    const next = columns.map((col) => ({ ...col, cards: [...col.cards] }));
    const from = next.find((col) => col.id === move.from);
    const to = next.find((col) => col.id === move.to);
    const card = from?.cards.find((c) => c.id === move.card);
    if (!from || !to || !card) return;
    flight = snapshotRows(cardsOf());
    from.cards = from.cards.filter((c) => c.id !== move.card);
    to.cards.splice(Math.max(0, Math.min(move.index, to.cards.length)), 0, card);
    onchange?.(next);
    onmove?.(move);
    announced = translate(app.lang, 'kanbanMovedTo', { card: card.title, column: to.title });
  };

  /* ---- Native drag & drop ----------------------------------------------------------------- */

  const onDragStart = (event: DragEvent, card: string) => {
    event.dataTransfer?.setData('text/plain', card);
    if (event.dataTransfer) event.dataTransfer.effectAllowed = 'move';
    dragging = card;
  };

  const onDragOver = (event: DragEvent, column: string) => {
    if (!dragging) return;
    event.preventDefault();
    if (event.dataTransfer) event.dataTransfer.dropEffect = 'move';
    const index = dropIndex(event.currentTarget as HTMLElement, event.clientY);
    if (over?.column === column && over.index === index) return;
    over = { column, index };
  };

  const onDrop = (event: DragEvent, column: string) => {
    event.preventDefault();
    const card = dragging ?? event.dataTransfer?.getData('text/plain') ?? null;
    const from = columns.find((col) => col.cards.some((c) => c.id === card))?.id;
    dragging = null;
    over = null;
    if (!card || !from) return;
    commit({ card, from, to: column, index: dropIndex(event.currentTarget as HTMLElement, event.clientY) });
  };

  const onDragLeave = (event: DragEvent, column: string) => {
    if ((event.currentTarget as HTMLElement).contains(event.relatedTarget as Node | null)) return;
    if (over?.column === column) over = null;
  };

  /* ---- Card menu picks ------------------------------------------------------------------- */

  const onCardMove = (cardId: string, from: string, to: string) => {
    commit({ card: cardId, from, to, index: columns.find((col) => col.id === to)?.cards.length ?? 0 });
  };

  const onCardAction = (id: string, cardId: string) => onaction?.({ id, cardId });

  const othersOf = (cardId: string): CardColumn[] => {
    const from = columns.find((col) => col.cards.some((c) => c.id === cardId))?.id;
    return columns.filter((col) => col.id !== from).map((col) => ({ id: col.id, title: col.title }));
  };

  /* ---- Quick add -------------------------------------------------------------------------- */

  const submitAdd = async (event: SubmitEvent, column: string) => {
    event.preventDefault();
    const form = event.currentTarget as HTMLFormElement;
    const input = form.elements.namedItem('title') as HTMLInputElement | null;
    const title = input?.value.trim();
    if (!title) {
      input?.focus();
      return;
    }
    flight = snapshotRows(cardsOf());
    const next = columns.map((col) => (col.id === column ? { ...col, cards: [...col.cards, { id: `card-${Date.now().toString(36)}`, title }] } : col));
    onchange?.(next);
    onadd?.({ column, title });
    await tick();
    if (input) input.value = '';
    input?.focus();
  };

  const word = (key: 'kanbanBoard' | 'kanbanAddCard' | 'kanbanAdd' | 'kanbanEmpty' | 'kanbanCards', params: Record<string, string | number> = {}) =>
    translate(app.lang, key, params);

  const boardLabel = $derived(label ?? word('kanbanBoard'));
  const placeholder = $derived(addPlaceholder ?? word('kanbanAddCard'));
</script>

<section bind:this={board} class="nx-kanban" aria-label={boardLabel} style:--nx-kanban-height={height}>
  <div class="nx-kanban-board">
    {#each columns as column (column.id)}
      <div class="nx-kanban-column" data-column={column.id} data-tone={column.tone} data-dropping={over?.column === column.id ? '' : undefined}>
        <header class="nx-kanban-column-head">
          <span class="nx-kanban-column-dot" aria-hidden="true"></span>
          <h3 class="nx-kanban-column-title" id={`nx-kanban-col-${column.id}`}>{column.title}</h3>
          <span class="nx-kanban-column-count" aria-hidden="true">{numberFmt().format(column.cards.length)}</span>
          <span class="nx-visually-hidden">{word('kanbanCards', { count: numberFmt().format(column.cards.length) })}</span>
        </header>
        <ul
          class="nx-kanban-list"
          aria-labelledby={`nx-kanban-col-${column.id}`}
          ondragover={(event) => onDragOver(event, column.id)}
          ondrop={(event) => onDrop(event, column.id)}
          ondragleave={(event) => onDragLeave(event, column.id)}
        >
          {#each slotsOf(column) as slot (slot.key)}
            {#if slot.placeholder}
              <li class="nx-kanban-placeholder" aria-hidden="true">
                <span class="nx-kanban-placeholder-dot"></span>
                <span class="nx-kanban-placeholder-bar"></span>
              </li>
            {:else if slot.card}
              <KanbanCard
                card={slot.card}
                columnId={column.id}
                index={slot.index}
                dragging={dragging === slot.card.id}
                {actions}
                others={othersOf(slot.card.id)}
                ondragstart={onDragStart}
                ondragend={() => {
                  dragging = null;
                  over = null;
                }}
                onmove={onCardMove}
                onaction={onCardAction}
              />
            {/if}
          {/each}
          {#if column.cards.length === 0 && over?.column !== column.id}
            <li class="nx-kanban-empty">{word('kanbanEmpty')}</li>
          {/if}
        </ul>
        <div class="nx-kanban-add">
          <button
            type="button"
            class="nx-kanban-add-open"
            aria-expanded={adding === column.id}
            aria-controls={`nx-kanban-add-${column.id}`}
            onclick={() => (adding = adding === column.id ? null : column.id)}
          >
            <NxIcon name="plus" />
            <span>{word('kanbanAddCard')}</span>
          </button>
          <div class="nx-kanban-add-form" id={`nx-kanban-add-${column.id}`} data-open={adding === column.id ? '' : undefined}>
            <form class="nx-kanban-add-body" onsubmit={(event) => submitAdd(event, column.id)}>
              <input class="nx-kanban-add-input" name="title" type="text" autocomplete="off" {placeholder} aria-label={word('kanbanAddCard')} />
              <button type="submit" class="nx-kanban-add-submit">{word('kanbanAdd')}</button>
            </form>
          </div>
        </div>
      </div>
    {/each}
  </div>
  <!-- The move, spoken once it lands. -->
  <p class="nx-visually-hidden" role="status">{announced}</p>
</section>
