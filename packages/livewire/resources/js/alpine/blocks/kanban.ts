/**
 * Alpine part for the kanban block. The Blade component renders the columns,
 * cards, menus and the quick-add composer server-side; this adds the native
 * drag & drop (with the core FLIP glide and the breathing placeholder), the
 * popover menus, the rolling counts and the wire sync — the same behaviours
 * the React component implements over the same CSS.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installKanbanBlocks } from './alpine/blocks/kanban';
 *   installKanbanBlocks(Alpine);   // x-data="nxKanban(config)"
 */
import { type Cleanup, place, playRowFlip, reveal, roveFocus, snapshotRows } from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

/** The bits of Livewire's $wire these parts use (absent outside Livewire). */
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };
type LivewireGlobal = { hook?: (name: string, callback: (payload: { el: Element }) => void) => (() => void) | void };

export interface KanbanCardJson {
  id: string;
  title: string;
  meta?: string | null;
  tone?: string | null;
  assignee?: { name: string; src?: string | null } | null;
  actions?: Array<{ label: string; icon?: string | null; href?: string | null; danger?: boolean }>;
}

export interface KanbanColumnJson {
  id: string;
  title: string;
  tone?: string | null;
  cards: KanbanCardJson[];
}

export interface KanbanLabels {
  board: string;
  addCard: string;
  add: string;
  empty: string;
  moveTo: string;
  menuFor: string;
  cards: string;
  movedTo: string;
}

export interface KanbanConfig {
  columns: KanbanColumnJson[];
  labels: KanbanLabels;
  locale?: string;
  moveAction?: string | null;
  addAction?: string | null;
  quickAdd?: boolean;
}

const supportsPopover = () => 'popover' in HTMLElement.prototype;

/** `:count` → the number, in the page's digits. */
const withParams = (template: string, params: Record<string, string | number>) => template.replace(/:(\w+)/g, (_, name: string) => String(params[name] ?? `:${name}`));

/** Where a pointer's Y falls in `list`: before which card (its index, or the end). */
function dropIndex(list: HTMLElement, dragging: HTMLElement | null, y: number): number {
  const cards = Array.from(list.querySelectorAll<HTMLElement>('.nx-kanban-card')).filter((card) => card !== dragging);
  for (let i = 0; i < cards.length; i++) {
    const rect = cards[i]!.getBoundingClientRect();
    if (y < rect.top + rect.height / 2) return i;
  }
  return cards.length;
}

/** The menu panel a trigger opens, from its aria-controls (ids are server-made). */
function panelFor(root: HTMLElement, card: string): HTMLElement | null {
  const trigger = root.querySelector<HTMLElement>(`.nx-kanban-card-menu[data-menu-for="${CSS.escape(card)}"]`);
  const id = trigger?.getAttribute('aria-controls');
  return id ? root.querySelector<HTMLElement>(`#${CSS.escape(id)}`) : null;
}

/** Fold a menu away: an exit softer and faster than the enter. */
function hidePanel(panel: HTMLElement | null): void {
  if (!panel) return;
  window.setTimeout(() => {
    if (panel.matches(':popover-open')) panel.hidePopover();
    else panel.removeAttribute('data-open');
  }, 150);
}

export function installKanbanBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxKanban', (config: KanbanConfig) => {
    const titles = new Map(config.columns.map((column) => [column.id, column.title]));
    let unplace: Cleanup = () => {};
    let unhook: Cleanup = () => {};

    interface KanbanSelf {
      dragging: string | null;
      dropping: string | null;
      menu: string | null;
      adding: string | null;
      announce: string;
      $root: HTMLElement;
      paintCounts: () => void;
      placePlaceholder: (list: HTMLElement, index: number) => void;
      move: (card: string, to: string, index: number) => Promise<void>;
    }

    return {
      dragging: null as string | null,
      /** The column the pointer is over; its placeholder shows at the drop index. */
      dropping: null as string | null,
      menu: null as string | null,
      adding: null as string | null,
      announce: '',

      init(this: Self<KanbanSelf>) {
        reveal(this.$root, { once: true });
        // A Livewire re-render re-renders counts server-side; keep them rolling anyway.
        const livewire = (window as unknown as { Livewire?: LivewireGlobal }).Livewire;
        const root = this.$root;
        if (livewire?.hook) {
          unhook =
            livewire.hook('morphed', ({ el }) => {
              if (el.contains(root)) requestAnimationFrame(() => this.paintCounts());
            }) ?? (() => {});
        }
      },

      destroy(this: Self<KanbanSelf>) {
        unplace();
        unhook();
      },

      /* ---- Drag & drop (delegated from the board; cards need no handlers of their own) ---- */

      dragStart(this: Self<KanbanSelf>, event: DragEvent) {
        const card = (event.target as HTMLElement).closest<HTMLElement>('.nx-kanban-card');
        if (!card) return;
        this.dragging = card.dataset.key ?? null;
        if (!this.dragging) return;
        event.dataTransfer?.setData('text/plain', this.dragging);
        if (event.dataTransfer) event.dataTransfer.effectAllowed = 'move';
      },

      dragEnd(this: Self<KanbanSelf>) {
        this.dragging = null;
        this.dropping = null;
      },

      dragOver(this: Self<KanbanSelf>, event: DragEvent) {
        if (!this.dragging) return;
        const list = (event.target as HTMLElement).closest<HTMLElement>('.nx-kanban-list');
        if (!list) return;
        event.preventDefault();
        if (event.dataTransfer) event.dataTransfer.dropEffect = 'move';
        const column = list.closest<HTMLElement>('.nx-kanban-column')?.dataset.column;
        if (!column) return;
        const source = this.$root.querySelector<HTMLElement>(`.nx-kanban-card[data-key="${CSS.escape(this.dragging)}"]`);
        this.placePlaceholder(list, dropIndex(list, source, event.clientY));
        this.dropping = column;
      },

      dragLeave(this: Self<KanbanSelf>, event: DragEvent) {
        const list = event.currentTarget as HTMLElement;
        if (list.contains(event.relatedTarget as Node | null)) return;
        if (list.closest<HTMLElement>('.nx-kanban-column')?.dataset.column === this.dropping) this.dropping = null;
      },

      drop(this: Self<KanbanSelf>, event: DragEvent) {
        const list = event.currentTarget as HTMLElement;
        const column = list.closest<HTMLElement>('.nx-kanban-column')?.dataset.column ?? null;
        const card = this.dragging ?? event.dataTransfer?.getData('text/plain') ?? null;
        this.dragging = null;
        this.dropping = null;
        if (!card || !column) return;
        const source = this.$root.querySelector<HTMLElement>(`.nx-kanban-card[data-key="${CSS.escape(card)}"]`);
        void this.move(card, column, dropIndex(list, source, event.clientY));
      },

      /** Park the column's placeholder before card `index`; each hop replays its pop.
       *  The slot is counted among the column's *other* cards, so it shows where
       *  the dragged card will land once it leaves its old slot. */
      placePlaceholder(this: Self<KanbanSelf>, list: HTMLElement, index: number) {
        const placeholder = list.querySelector<HTMLElement>('.nx-kanban-placeholder');
        if (!placeholder) return;
        const dragged = this.dragging ? this.$root.querySelector<HTMLElement>(`.nx-kanban-card[data-key="${CSS.escape(this.dragging)}"]`) : null;
        const cards = Array.from(list.querySelectorAll<HTMLElement>('.nx-kanban-card')).filter((el) => el !== dragged);
        const before = cards[index] ?? null;
        if (placeholder.nextSibling !== before) list.insertBefore(placeholder, before);
        placeholder.hidden = false;
      },

      /* ---- Reordering ------------------------------------------------------------------------ */

      /** One move, from a drop or a menu item: FLIP, counts, events, then the wire action. */
      async move(this: Self<KanbanSelf>, card: string, to: string, index: number) {
        const root = this.$root;
        const cardEl = root.querySelector<HTMLElement>(`.nx-kanban-card[data-key="${CSS.escape(card)}"]`);
        const list = root.querySelector<HTMLElement>(`.nx-kanban-column[data-column="${CSS.escape(to)}"] .nx-kanban-list`);
        if (!cardEl || !list) return;

        const before = snapshotRows(root.querySelectorAll<HTMLElement>('.nx-kanban-card[data-key]'));
        const cards = Array.from(list.querySelectorAll<HTMLElement>('.nx-kanban-card')).filter((el) => el !== cardEl);
        const at = Math.max(0, Math.min(index, cards.length));
        cards[at] ? list.insertBefore(cardEl, cards[at]) : list.append(cardEl);
        playRowFlip(root.querySelectorAll<HTMLElement>('.nx-kanban-card[data-key]'), before);
        this.paintCounts();
        const title = cardEl.querySelector('.nx-kanban-card-title')?.textContent ?? card;
        this.announce = withParams(config.labels.movedTo, { card: title, column: titles.get(to) ?? to });
        this.$dispatch('nx-move', { card, from: cardEl.closest<HTMLElement>('.nx-kanban-column')?.dataset.column, to, index: at });

        if (config.moveAction) {
          const wire = (this as unknown as { $wire?: Wire }).$wire;
          if (wire) await wire.call(config.moveAction, card, cardEl.closest<HTMLElement>('.nx-kanban-column')?.dataset.column, to, at);
        }
      },

      /** Keep every column's rolling count and its spoken total true after any reorder. */
      paintCounts(this: Self<KanbanSelf>) {
        const fmt = new Intl.NumberFormat(config.locale);
        for (const column of this.$root.querySelectorAll<HTMLElement>('.nx-kanban-column')) {
          const total = column.querySelectorAll('.nx-kanban-card').length;
          const count = column.querySelector<HTMLElement>('.nx-kanban-column-count .nx-number');
          if (count) renderNumber(count, total, config.locale);
          const spoken = column.querySelector<HTMLElement>('.nx-kanban-column-head > .nx-visually-hidden');
          if (spoken) spoken.textContent = withParams(config.labels.cards, { count: fmt.format(total) });
        }
      },

      /** "{count} cards", bound to the column header's hidden span. */
      countText(this: Self<KanbanSelf>, columnId: string) {
        const column = this.$root.querySelector<HTMLElement>(`.nx-kanban-column[data-column="${CSS.escape(columnId)}"]`);
        const total = column ? column.querySelectorAll('.nx-kanban-card').length : 0;
        return withParams(config.labels.cards, { count: new Intl.NumberFormat(config.locale).format(total) });
      },

      /* ---- Card menus -------------------------------------------------------------------------- */

      menuToggle(this: Self<KanbanSelf>, card: string, event: Event) {
        const open = (event as ToggleEvent).newState === 'open';
        this.menu = open ? card : null;
        unplace();
        unplace = () => {};
        if (!open) return;
        const trigger = this.$root.querySelector<HTMLElement>(`.nx-kanban-card-menu[data-menu-for="${CSS.escape(card)}"]`);
        const panel = panelFor(this.$root, card);
        if (trigger && panel) unplace = place(trigger, panel, { side: 'bottom', align: 'end', offset: 6 });
      },

      /** Without the Popover API the trigger toggles the panel itself. */
      menuClick(this: Self<KanbanSelf>, card: string) {
        if (supportsPopover()) return;
        const panel = panelFor(this.$root, card);
        if (!panel) return;
        const open = panel.hasAttribute('data-open');
        if (open) panel.removeAttribute('data-open');
        else panel.setAttribute('data-open', '');
        this.menu = open ? null : card;
      },

      menuKey(this: Self<KanbanSelf>, event: KeyboardEvent) {
        roveFocus(event, event.currentTarget as HTMLElement, '.nx-kanban-menu-choice', { orientation: 'vertical' });
      },

      cardAction(this: Self<KanbanSelf>, card: string, action: string, event: Event) {
        this.$dispatch('nx-card-action', { card, action });
        hidePanel((event.currentTarget as HTMLElement).closest<HTMLElement>('.nx-kanban-menu'));
      },

      menuMove(this: Self<KanbanSelf>, card: string, from: string, to: string, event: Event) {
        hidePanel((event.currentTarget as HTMLElement).closest<HTMLElement>('.nx-kanban-menu'));
        void this.move(card, to, Number.MAX_SAFE_INTEGER);
        this.$dispatch('nx-card-action', { card, action: 'move', from, to });
      },

      /* ---- Quick add ------------------------------------------------------------------------------ */

      toggleAdd(this: Self<KanbanSelf>, column: string) {
        this.adding = this.adding === column ? null : column;
      },

      async submitAdd(this: Self<KanbanSelf>, event: SubmitEvent, column: string) {
        const form = event.target as HTMLFormElement;
        const input = form.elements.namedItem('title') as HTMLInputElement | null;
        const title = input?.value.trim() ?? '';
        if (!title) {
          input?.focus();
          return;
        }

        if (config.addAction) {
          // The server owns the cards: it re-renders and the new card arrives with the morph.
          const wire = (this as unknown as { $wire?: Wire }).$wire;
          if (wire) await wire.call(config.addAction, column, title);
          if (input) input.value = '';
          input?.focus();
          return;
        }

        // No action: a local card, built from text only (never parsed as markup).
        const list = this.$root.querySelector<HTMLElement>(`.nx-kanban-column[data-column="${CSS.escape(column)}"] .nx-kanban-list`);
        if (!list) return;
        const card = document.createElement('li');
        card.className = 'nx-kanban-card';
        card.draggable = true;
        card.dataset.key = `card-${Date.now().toString(36)}`;
        const tone = list.closest<HTMLElement>('.nx-kanban-column')?.dataset.tone;
        if (tone) card.dataset.tone = tone;
        const main = document.createElement('div');
        main.className = 'nx-kanban-card-main';
        const heading = document.createElement('p');
        heading.className = 'nx-kanban-card-title';
        heading.textContent = title;
        main.append(heading);
        card.append(main);
        const empty = list.querySelector('.nx-kanban-empty');
        if (empty) list.insertBefore(card, empty);
        else list.append(card);
        this.paintCounts();
        this.$dispatch('nx-add', { column, title });
        if (input) input.value = '';
        input?.focus();
      },
    };
  });
}
