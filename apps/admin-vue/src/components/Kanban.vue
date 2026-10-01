<script setup lang="ts">
/**
 * The kanban board — the hand-written Vue twin of the `kanban` block
 * (nx-kanban): native HTML5 drag & drop with the breathing placeholder pill at
 * the drop point, the FLIP glide after every reorder (core `snapshotRows` +
 * `playRowFlip`), the per-column quick-add composer and each card's three-dot
 * menu as a native popover (core `place` + `roveFocus`) holding the page's
 * actions and the "move to" items. The page owns the columns; reorders,
 * additions and menu picks come back up as events.
 */
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import {
  type Cleanup,
  type IconName,
  type RowSnapshot,
  place,
  playRowFlip,
  reveal,
  roveFocus,
  snapshotRows,
  translate,
} from '@nabuxai/ui-core';
import { lang, numberFmt } from '../store';
import NxIcon from './NxIcon.vue';

export type KanbanTone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';

export interface KanbanCardAction {
  id: string;
  label: string;
  icon?: IconName;
  danger?: boolean;
}

export interface KanbanCard {
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
  cards: KanbanCard[];
}

export interface KanbanMove {
  card: string;
  from: string;
  to: string;
  index: number;
}

const props = withDefaults(
  defineProps<{
    columns: KanbanColumn[];
    label?: string;
    height?: string;
    addPlaceholder?: string;
    actions?: KanbanCardAction[];
  }>(),
  { label: undefined, height: undefined, addPlaceholder: undefined, actions: undefined },
);

const emit = defineEmits<{
  change: [columns: KanbanColumn[]];
  move: [move: KanbanMove];
  add: [payload: { column: string; title: string }];
  action: [payload: { id: string; cardId: string }];
}>();

const board = ref<HTMLElement | null>(null);
const dragging = ref<string | null>(null);
const over = ref<{ column: string; index: number } | null>(null);
const adding = ref<string | null>(null);
const announced = ref('');
let flight: RowSnapshot | null = null;
let stopReveal: (() => void) | null = null;

const cardsOf = () => board.value?.querySelectorAll<HTMLElement>('.nx-kanban-card[data-key]') ?? [];

onMounted(() => {
  if (board.value) stopReveal = reveal(board.value, { once: true });
});
onBeforeUnmount(() => stopReveal?.());

// After the page hands the new columns back, the cards glide to their places.
watch(
  () => props.columns,
  () => {
    if (!flight) return;
    const before = flight;
    flight = null;
    void nextTick(() => playRowFlip(cardsOf(), before));
  },
);

/** Where a pointer's Y falls in `list`: before which card (its index, or the end). */
function dropIndex(list: HTMLElement, y: number): number {
  const source = dragging.value ? board.value?.querySelector<HTMLElement>(`.nx-kanban-card[data-key="${CSS.escape(dragging.value)}"]`) ?? null : null;
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
  card?: KanbanCard;
  index: number;
  placeholder?: boolean;
}

const slotsOf = (column: KanbanColumn): Slot[] => {
  const here = over.value?.column === column.id ? over.value.index : null;
  const items: Slot[] = [];
  let landing = 0;
  column.cards.forEach((card) => {
    if (card.id !== dragging.value) {
      if (here === landing) items.push({ key: `ph-${column.id}-${landing}`, index: landing, placeholder: true });
      landing += 1;
    }
    items.push({ key: card.id, card, index: landing });
  });
  if (here !== null && here >= landing) items.push({ key: `ph-${column.id}-end`, index: landing, placeholder: true });
  return items;
};

const commit = (move: KanbanMove) => {
  const next = props.columns.map((col) => ({ ...col, cards: [...col.cards] }));
  const from = next.find((col) => col.id === move.from);
  const to = next.find((col) => col.id === move.to);
  const card = from?.cards.find((c) => c.id === move.card);
  if (!from || !to || !card) return;
  flight = snapshotRows(cardsOf());
  from.cards = from.cards.filter((c) => c.id !== move.card);
  to.cards.splice(Math.max(0, Math.min(move.index, to.cards.length)), 0, card);
  emit('change', next);
  emit('move', move);
  const word = (key: 'kanbanMovedTo', params: Record<string, string>) => translate(lang.value, key, params);
  announced.value = word('kanbanMovedTo', { card: card.title, column: to.title });
};

/* ---- Native drag & drop ----------------------------------------------------------------- */

const onDragStart = (event: DragEvent, card: string) => {
  event.dataTransfer?.setData('text/plain', card);
  if (event.dataTransfer) event.dataTransfer.effectAllowed = 'move';
  dragging.value = card;
};

const onDragOver = (event: DragEvent, column: string) => {
  if (!dragging.value) return;
  event.preventDefault();
  if (event.dataTransfer) event.dataTransfer.dropEffect = 'move';
  const list = event.currentTarget as HTMLElement;
  const index = dropIndex(list, event.clientY);
  if (over.value?.column === column && over.value.index === index) return;
  over.value = { column, index };
};

const onDrop = (event: DragEvent, column: string) => {
  event.preventDefault();
  const card = dragging.value ?? event.dataTransfer?.getData('text/plain') ?? null;
  const from = props.columns.find((col) => col.cards.some((c) => c.id === card))?.id;
  dragging.value = null;
  over.value = null;
  if (!card || !from) return;
  const list = event.currentTarget as HTMLElement;
  commit({ card, from, to: column, index: dropIndex(list, event.clientY) });
};

const onDragLeave = (event: DragEvent, column: string) => {
  if ((event.currentTarget as HTMLElement).contains(event.relatedTarget as Node | null)) return;
  if (over.value?.column === column) over.value = null;
};

/* ---- Card menu: a native popover placed against its trigger ---------------------------- */

const openMenu = ref<string | null>(null);
const menuTriggers = new Map<string, HTMLButtonElement>();
const menuPanels = new Map<string, HTMLElement>();
const unplaces = new Map<string, Cleanup>();

function setMenuTrigger(cardId: string, el: unknown) {
  if (el) menuTriggers.set(cardId, el as HTMLButtonElement);
  else menuTriggers.delete(cardId);
}

function setMenuPanel(cardId: string, el: unknown) {
  if (el) menuPanels.set(cardId, el as HTMLElement);
  else menuPanels.delete(cardId);
}

onBeforeUnmount(() => {
  for (const stop of unplaces.values()) stop();
  unplaces.clear();
});

// The panel's own toggle event is the one source of truth (bound in the template).
function onMenuToggle(cardId: string, target: HTMLElement) {
  const shown = target.matches(':popover-open');
  openMenu.value = shown ? cardId : null;
  unplaces.get(cardId)?.();
  unplaces.delete(cardId);
  if (shown) {
    const trigger = menuTriggers.get(cardId);
    if (trigger) unplaces.set(cardId, place(trigger, target, { side: 'bottom', align: 'end', offset: 6 }));
  }
}

// An exit is softer and faster than the enter: fold away quickly.
const closeMenu = (cardId: string) =>
  window.setTimeout(() => {
    const panel = menuPanels.get(cardId);
    if (panel?.matches(':popover-open')) panel.hidePopover();
  }, 150);

const chooseAction = (action: KanbanCardAction, cardId: string) => {
  emit('action', { id: action.id, cardId });
  closeMenu(cardId);
};

const moveTo = (cardId: string, from: string, to: string) => {
  commit({ card: cardId, from, to, index: props.columns.find((col) => col.id === to)?.cards.length ?? 0 });
  closeMenu(cardId);
};

const othersOf = (cardId: string) => {
  const from = props.columns.find((col) => col.cards.some((c) => c.id === cardId))?.id;
  return props.columns.filter((col) => col.id !== from);
};

const onMenuKey = (event: KeyboardEvent) => {
  roveFocus(event, event.currentTarget as HTMLElement, '.nx-kanban-menu-choice', { orientation: 'vertical' });
};

/* ---- Quick add -------------------------------------------------------------------------- */

const submitAdd = (event: Event, column: string) => {
  const form = event.target as HTMLFormElement;
  const input = form.elements.namedItem('title') as HTMLInputElement | null;
  const title = input?.value.trim();
  if (!title) {
    input?.focus();
    return;
  }
  flight = snapshotRows(cardsOf());
  const next = props.columns.map((col) => (col.id === column ? { ...col, cards: [...col.cards, { id: `card-${Date.now().toString(36)}`, title }] } : col));
  emit('change', next);
  emit('add', { column, title });
  if (input) input.value = '';
  input?.focus();
};

const word = (key: 'kanbanBoard' | 'kanbanAddCard' | 'kanbanAdd' | 'kanbanEmpty' | 'kanbanCardMenu' | 'kanbanMoveTo' | 'kanbanCards', params: Record<string, string | number> = {}) =>
  translate(lang.value, key, params);

const hasMenu = (cardId: string) => (props.actions?.length ?? 0) > 0 || othersOf(cardId).length > 0;
</script>

<template>
  <section ref="board" class="nx-kanban" :aria-label="label ?? word('kanbanBoard')" :style="height ? { '--nx-kanban-height': height } : undefined">
    <div class="nx-kanban-board">
      <div
        v-for="column in columns"
        :key="column.id"
        class="nx-kanban-column"
        :data-column="column.id"
        :data-tone="column.tone"
        :data-dropping="over?.column === column.id ? '' : undefined"
      >
        <header class="nx-kanban-column-head">
          <span class="nx-kanban-column-dot" aria-hidden="true" />
          <h3 class="nx-kanban-column-title" :id="`nx-kanban-col-${column.id}`">{{ column.title }}</h3>
          <span class="nx-kanban-column-count" aria-hidden="true">{{ numberFmt.format(column.cards.length) }}</span>
          <span class="nx-visually-hidden">{{ word('kanbanCards', { count: numberFmt.format(column.cards.length) }) }}</span>
        </header>
        <ul
          class="nx-kanban-list"
          :aria-labelledby="`nx-kanban-col-${column.id}`"
          @dragover="(event) => onDragOver(event, column.id)"
          @drop="(event) => onDrop(event, column.id)"
          @dragleave="(event) => onDragLeave(event, column.id)"
        >
          <template v-for="slot in slotsOf(column)" :key="slot.key">
            <li v-if="slot.placeholder" class="nx-kanban-placeholder" aria-hidden="true">
              <span class="nx-kanban-placeholder-dot" />
              <span class="nx-kanban-placeholder-bar" />
            </li>
            <li
              v-else-if="slot.card"
              class="nx-kanban-card"
              :data-key="slot.card.id"
              :data-tone="slot.card.tone"
              :data-dragging="dragging === slot.card.id ? '' : undefined"
              :style="{ '--nx-i': slot.index }"
              draggable="true"
              @dragstart="(event) => onDragStart(event, slot.card!.id)"
              @dragend="dragging = null; over = null"
            >
              <span v-if="slot.card.tone" class="nx-kanban-card-dot" aria-hidden="true" />
              <div class="nx-kanban-card-main">
                <p class="nx-kanban-card-title">{{ slot.card.title }}</p>
                <p v-if="slot.card.meta" class="nx-kanban-card-meta">{{ slot.card.meta }}</p>
              </div>
              <span v-if="slot.card.assignee" class="nx-avatar" data-size="sm" role="img" :aria-label="slot.card.assignee">
                <span aria-hidden="true">{{ slot.card.assignee.split(/\s+/).slice(0, 2).map((p) => Array.from(p)[0]).join('').toLocaleUpperCase() }}</span>
              </span>
              <template v-if="hasMenu(slot.card.id)">
                <button
                  :ref="(el) => setMenuTrigger(slot.card!.id, el)"
                  type="button"
                  class="nx-kanban-card-menu"
                  aria-haspopup="menu"
                  :aria-expanded="openMenu === slot.card.id"
                  :aria-controls="`nx-kanban-menu-${slot.card.id}`"
                  :aria-label="word('kanbanCardMenu', { name: slot.card.title })"
                  :popovertarget="`nx-kanban-menu-${slot.card.id}`"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" aria-hidden="true">
                    <path d="M12 5.25h.01M12 12h.01M12 18.75h.01" />
                  </svg>
                </button>
                <div
                  :id="`nx-kanban-menu-${slot.card.id}`"
                  :ref="(el) => setMenuPanel(slot.card!.id, el)"
                  class="nx-kanban-menu"
                  role="menu"
                  :aria-label="word('kanbanCardMenu', { name: slot.card.title })"
                  popover="auto"
                  @toggle="onMenuToggle(slot.card!.id, $event.currentTarget as HTMLElement)"
                  @keydown="onMenuKey"
                >
                  <button
                    v-for="action in actions"
                    :key="action.id"
                    type="button"
                    class="nx-kanban-menu-choice"
                    role="menuitem"
                    :data-danger="action.danger ? '' : undefined"
                    @click="chooseAction(action, slot.card!.id)"
                  >
                    <NxIcon v-if="action.icon" :name="action.icon" />
                    <span>{{ action.label }}</span>
                  </button>
                  <hr v-if="actions && othersOf(slot.card.id).length > 0" class="nx-kanban-menu-sep" role="separator" />
                  <button
                    v-for="other in othersOf(slot.card.id)"
                    :key="other.id"
                    type="button"
                    class="nx-kanban-menu-choice"
                    role="menuitem"
                    @click="moveTo(slot.card!.id, column.id, other.id)"
                  >
                    <NxIcon name="chevron-right" />
                    <span>{{ word('kanbanMoveTo', { column: other.title }) }}</span>
                  </button>
                </div>
              </template>
            </li>
          </template>
          <li v-if="column.cards.length === 0 && over?.column !== column.id" class="nx-kanban-empty">{{ word('kanbanEmpty') }}</li>
        </ul>
        <div class="nx-kanban-add">
          <button
            type="button"
            class="nx-kanban-add-open"
            :aria-expanded="adding === column.id"
            :aria-controls="`nx-kanban-add-${column.id}`"
            @click="adding = adding === column.id ? null : column.id"
          >
            <NxIcon name="plus" />
            <span>{{ word('kanbanAddCard') }}</span>
          </button>
          <div class="nx-kanban-add-form" :id="`nx-kanban-add-${column.id}`" :data-open="adding === column.id ? '' : undefined">
            <form class="nx-kanban-add-body" @submit.prevent="(event) => submitAdd(event, column.id)">
              <input
                class="nx-kanban-add-input"
                name="title"
                type="text"
                autocomplete="off"
                :placeholder="addPlaceholder ?? word('kanbanAddCard')"
                :aria-label="word('kanbanAddCard')"
              />
              <button type="submit" class="nx-kanban-add-submit">{{ word('kanbanAdd') }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- The move, spoken once it lands. -->
    <p class="nx-visually-hidden" role="status">{{ announced }}</p>
  </section>
</template>
