/**
 * Alpine part for the todo block. The Blade component renders the groups,
 * tasks, progress bar and quick-add composer server-side; this adds the native
 * drag & drop (with the core FLIP glide and the breathing placeholder), the
 * Alt+↑/↓ keyboard reorder, the spring tick bookkeeping, the rolling counters
 * and bar, and the wire sync — the same behaviours the React component
 * implements over the same CSS.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installTodoBlocks } from './alpine/blocks/todo';
 *   installTodoBlocks(Alpine);   // x-data="nxTodo(config)"
 */
import { iconSvg, playRowFlip, reveal, snapshotRows } from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

/** The bits of Livewire's $wire these parts use (absent outside Livewire). */
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };
type LivewireGlobal = { hook?: (name: string, callback: (payload: { el: Element }) => void) => (() => void) | void };

export interface TodoTaskJson {
  id: string;
  title: string;
  done?: boolean;
}

export interface TodoGroupJson {
  id: string;
  title: string;
  tone?: string | null;
  tasks: TodoTaskJson[];
}

export interface TodoLabels {
  list: string;
  addTask: string;
  add: string;
  empty: string;
  group: string;
  tasks: string;
  progress: string;
  remove: string;
  checked: string;
  unchecked: string;
  moved: string;
}

export interface TodoConfig {
  groups: TodoGroupJson[];
  labels: TodoLabels;
  locale?: string;
  quickAdd?: boolean;
  progress?: boolean;
  toggleAction?: string | null;
  removeAction?: string | null;
  addAction?: string | null;
  moveAction?: string | null;
}

const PERCENT: Intl.NumberFormatOptions = { style: 'percent', maximumFractionDigits: 0 };

/** `:count` → the number, in the page's digits. */
const withParams = (template: string, params: Record<string, string | number>) => template.replace(/:(\w+)/g, (_, name: string) => String(params[name] ?? `:${name}`));

/** Where a pointer's Y falls in `list`: before which task (its index, or the end). */
function dropIndex(list: HTMLElement, dragging: HTMLElement | null, y: number): number {
  const items = Array.from(list.querySelectorAll<HTMLElement>('.nx-todo-item')).filter((item) => item !== dragging);
  for (let i = 0; i < items.length; i++) {
    const rect = items[i]!.getBoundingClientRect();
    if (y < rect.top + rect.height / 2) return i;
  }
  return items.length;
}

export function installTodoBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxTodo', (config: TodoConfig) => {
    let unhook: () => void = () => {};

    interface TodoSelf {
      dragging: string | null;
      dropping: string | null;
      announce: string;
      target: string | null;
      readonly progressText: () => string;
      $root: HTMLElement;
      paint: () => void;
      countText: (groupId: string) => string;
      syncEmpty: (list: HTMLElement) => void;
      placePlaceholder: (list: HTMLElement, index: number) => void;
      move: (task: string, to: string, index: number, glide?: boolean) => Promise<void>;
      nudge: (event: KeyboardEvent, task: string) => void;
      toggle: (task: string, event: Event) => void;
      done: (task: string) => boolean;
      remove: (task: string) => Promise<void>;
      submitAdd: (event: SubmitEvent) => Promise<void>;
    }

    return {
      dragging: null as string | null,
      /** The group the pointer is over; its placeholder shows at the drop index. */
      dropping: null as string | null,
      announce: '',
      /** The group quick-add drops into; the select's x-model. */
      target: (config.groups[0]?.id ?? null) as string | null,

      /** "{done} of {total} done", bound to the head's hidden span. */
      progressText(this: Self<TodoSelf>): string {
        const fmt = new Intl.NumberFormat(config.locale);
        const root = this.$root;
        const items = root.querySelectorAll('.nx-todo-item');
        const done = root.querySelectorAll('.nx-todo-item .nx-todo-check:checked').length;
        return withParams(config.labels.progress, { done: fmt.format(done), total: fmt.format(items.length) });
      },

      init(this: Self<TodoSelf>) {
        reveal(this.$root, { once: true });
        // A Livewire re-render re-renders counts server-side; keep them rolling anyway.
        const livewire = (window as unknown as { Livewire?: LivewireGlobal }).Livewire;
        const root = this.$root;
        if (livewire?.hook) {
          unhook =
            livewire.hook('morphed', ({ el }) => {
              if (el.contains(root)) requestAnimationFrame(() => this.paint());
            }) ?? (() => {});
        }
      },

      destroy(this: Self<TodoSelf>) {
        unhook();
      },

      /* ---- Drag & drop (delegated from the board; tasks need no handlers of their own) ---- */

      dragStart(this: Self<TodoSelf>, event: DragEvent) {
        const item = (event.target as HTMLElement).closest<HTMLElement>('.nx-todo-item');
        if (!item) return;
        this.dragging = item.dataset.key ?? null;
        if (!this.dragging) return;
        event.dataTransfer?.setData('text/plain', this.dragging);
        if (event.dataTransfer) event.dataTransfer.effectAllowed = 'move';
      },

      dragEnd(this: Self<TodoSelf>) {
        this.dragging = null;
        this.dropping = null;
        // An emptied group gets its empty plate back once the drag is over.
        for (const list of this.$root.querySelectorAll<HTMLElement>('.nx-todo-list')) this.syncEmpty(list);
      },

      dragOver(this: Self<TodoSelf>, event: DragEvent) {
        if (!this.dragging) return;
        const list = (event.target as HTMLElement).closest<HTMLElement>('.nx-todo-list');
        if (!list) return;
        event.preventDefault();
        if (event.dataTransfer) event.dataTransfer.dropEffect = 'move';
        const group = list.closest<HTMLElement>('.nx-todo-group')?.dataset.group;
        if (!group) return;
        const source = this.$root.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(this.dragging)}"]`);
        this.placePlaceholder(list, dropIndex(list, source, event.clientY));
        this.dropping = group;
      },

      dragLeave(this: Self<TodoSelf>, event: DragEvent) {
        const list = event.currentTarget as HTMLElement;
        if (list.contains(event.relatedTarget as Node | null)) return;
        if (list.closest<HTMLElement>('.nx-todo-group')?.dataset.group === this.dropping) this.dropping = null;
      },

      drop(this: Self<TodoSelf>, event: DragEvent) {
        const list = event.currentTarget as HTMLElement;
        const group = list.closest<HTMLElement>('.nx-todo-group')?.dataset.group ?? null;
        const task = this.dragging ?? event.dataTransfer?.getData('text/plain') ?? null;
        this.dragging = null;
        this.dropping = null;
        if (!task || !group) return;
        const source = this.$root.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(task)}"]`);
        void this.move(task, group, dropIndex(list, source, event.clientY));
      },

      /** Park the group's placeholder before task `index`; each hop replays its pop.
       *  The slot is counted among the group's *other* tasks, so it shows where
       *  the dragged task will land once it leaves its old slot. */
      placePlaceholder(this: Self<TodoSelf>, list: HTMLElement, index: number) {
        const placeholder = list.querySelector<HTMLElement>('.nx-todo-placeholder');
        if (!placeholder) return;
        const dragged = this.dragging ? this.$root.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(this.dragging)}"]`) : null;
        const items = Array.from(list.querySelectorAll<HTMLElement>('.nx-todo-item')).filter((el) => el !== dragged);
        const before = items[index] ?? null;
        if (placeholder.nextSibling !== before) list.insertBefore(placeholder, before);
        placeholder.hidden = false;
        // While the pill shows, an emptied group hides its empty plate.
        const empty = list.querySelector<HTMLElement>('.nx-todo-empty');
        if (empty) empty.hidden = true;
      },

      /* ---- Reordering ------------------------------------------------------------------------ */

      /** One move, from a drop, a nudge or a removal ripple: FLIP, counts, events, then the wire action. */
      async move(this: Self<TodoSelf>, task: string, to: string, index: number, glide = true) {
        const root = this.$root;
        const item = root.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(task)}"]`);
        const list = root.querySelector<HTMLElement>(`.nx-todo-group[data-group="${CSS.escape(to)}"] .nx-todo-list`);
        if (!item || !list) return;

        const from = item.closest<HTMLElement>('.nx-todo-group')?.dataset.group ?? to;
        const source = item.closest<HTMLElement>('.nx-todo-list');
        const before = glide ? snapshotRows(root.querySelectorAll<HTMLElement>('.nx-todo-item[data-key]')) : null;
        const others = Array.from(list.querySelectorAll<HTMLElement>('.nx-todo-item')).filter((el) => el !== item);
        const at = Math.max(0, Math.min(index, others.length));
        others[at] ? list.insertBefore(item, others[at]!) : list.append(item);
        if (before) playRowFlip(root.querySelectorAll<HTMLElement>('.nx-todo-item[data-key]'), before);
        if (source) this.syncEmpty(source);
        this.syncEmpty(list);
        this.paint();
        const title = item.querySelector('.nx-todo-label')?.textContent ?? task;
        this.announce = withParams(config.labels.moved, { name: title });
        this.$dispatch('nx-move', { task, from, to, index: at });

        if (config.moveAction) {
          const wire = (this as unknown as { $wire?: Wire }).$wire;
          if (wire) await wire.call(config.moveAction, task, from, to, at);
        }
      },

      /** Alt+↑/↓ walks a task through the list — the keyboard path of the drag.
       *  Keyboard navigation is a many-times-a-day action: no glide, just the new slot. */
      nudge(this: Self<TodoSelf>, event: KeyboardEvent, task: string) {
        if (!event.altKey || (event.key !== 'ArrowUp' && event.key !== 'ArrowDown')) return;
        event.preventDefault();
        const root = this.$root;
        const item = root.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(task)}"]`);
        if (!item) return;
        const lists = Array.from(root.querySelectorAll<HTMLElement>('.nx-todo-list'));
        const list = item.closest<HTMLElement>('.nx-todo-list');
        if (!list) return;
        const group = list.closest<HTMLElement>('.nx-todo-group')?.dataset.group;
        const pos = lists.indexOf(list);
        const siblings = Array.from(list.querySelectorAll<HTMLElement>('.nx-todo-item'));
        const at = siblings.indexOf(item);
        const id = (el: HTMLElement | undefined) => el?.closest<HTMLElement>('.nx-todo-group')?.dataset.group ?? '';

        if (event.key === 'ArrowDown') {
          if (at >= 0 && at < siblings.length - 1) void this.move(task, group ?? '', at + 1, false);
          else if (lists[pos + 1]) void this.move(task, id(lists[pos + 1]!), 0, false);
        } else {
          if (at > 0) void this.move(task, group ?? '', at - 1, false);
          else if (lists[pos - 1]) void this.move(task, id(lists[pos - 1]!), lists[pos - 1]!.querySelectorAll('.nx-todo-item').length, false);
        }
        item.querySelector<HTMLElement>('.nx-todo-check')?.focus();
      },

      /* ---- The tick --------------------------------------------------------------------------- */

      /** The checkbox is native: read its own state, mirror it on the row, repaint. */
      toggle(this: Self<TodoSelf>, task: string, event: Event) {
        const input = event.target as HTMLInputElement;
        const item = input.closest<HTMLElement>('.nx-todo-item');
        if (!item) return;
        const done = input.checked;
        item.toggleAttribute('data-done', done);
        this.paint();
        const title = item.querySelector('.nx-todo-label')?.textContent ?? task;
        this.announce = withParams(done ? config.labels.checked : config.labels.unchecked, { name: title });
        this.$dispatch('nx-toggle', { task, done });
        this.syncEmpty(item.closest<HTMLElement>('.nx-todo-list') ?? this.$root);

        if (config.toggleAction) {
          const wire = (this as unknown as { $wire?: Wire }).$wire;
          if (wire) void wire.call(config.toggleAction, task, done);
        }
      },

      /** Is a task done, by its own checkbox? (The DOM is the truth after any move.) */
      done(this: Self<TodoSelf>, task: string): boolean {
        const item = this.$root.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(task)}"]`);
        return (item?.querySelector('.nx-todo-check') as HTMLInputElement | null)?.checked ?? false;
      },

      async remove(this: Self<TodoSelf>, task: string) {
        const root = this.$root;
        const item = root.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(task)}"]`);
        if (!item) return;
        const title = item.querySelector('.nx-todo-label')?.textContent ?? task;
        const list = item.closest<HTMLElement>('.nx-todo-list');
        const before = snapshotRows(root.querySelectorAll<HTMLElement>('.nx-todo-item[data-key]'));
        item.remove();
        playRowFlip(root.querySelectorAll<HTMLElement>('.nx-todo-item[data-key]'), before);
        if (list) this.syncEmpty(list);
        this.paint();
        this.announce = withParams(config.labels.remove, { name: title });
        this.$dispatch('nx-remove', { task });

        if (config.removeAction) {
          const wire = (this as unknown as { $wire?: Wire }).$wire;
          if (wire) await wire.call(config.removeAction, task);
        }
      },

      /* ---- Quick add ---------------------------------------------------------------------------- */

      async submitAdd(this: Self<TodoSelf>, event: SubmitEvent) {
        const form = event.target as HTMLFormElement;
        const input = form.elements.namedItem('title') as HTMLInputElement | null;
        const title = input?.value.trim() ?? '';
        const group = this.target ?? config.groups[0]?.id ?? null;
        if (!title || !group) {
          input?.focus();
          return;
        }

        if (config.addAction) {
          // The server owns the tasks: it re-renders and the new task arrives with the morph.
          const wire = (this as unknown as { $wire?: Wire }).$wire;
          if (wire) await wire.call(config.addAction, group, title);
          if (input) input.value = '';
          input?.focus();
          return;
        }

        // No action: a local task, built from text only (never parsed as markup).
        const list = this.$root.querySelector<HTMLElement>(`.nx-todo-group[data-group="${CSS.escape(group)}"] .nx-todo-list`);
        if (!list) return;
        const id = `task-${Date.now().toString(36)}`;
        const row = document.createElement('li');
        row.className = 'nx-todo-item';
        row.draggable = true;
        row.dataset.key = id;
        const grip = document.createElement('span');
        grip.className = 'nx-todo-grip';
        grip.setAttribute('aria-hidden', 'true');
        const check = document.createElement('input');
        check.type = 'checkbox';
        check.id = `${id}-check`;
        check.className = 'nx-todo-check';
        check.setAttribute('aria-label', title);
        const label = document.createElement('label');
        label.className = 'nx-todo-label';
        label.htmlFor = `${id}-check`;
        label.textContent = title;
        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'nx-todo-remove';
        remove.setAttribute('aria-label', withParams(config.labels.remove, { name: title }));
        remove.innerHTML = iconSvg('x');
        row.append(grip, check, label, remove);

        const self: Self<TodoSelf> = this;
        check.addEventListener('change', (e) => self.toggle(id, e));
        remove.addEventListener('click', () => self.remove(id));
        row.addEventListener('keydown', (e) => self.nudge(e as KeyboardEvent, id));

        const before = snapshotRows(this.$root.querySelectorAll<HTMLElement>('.nx-todo-item[data-key]'));
        const empty = list.querySelector('.nx-todo-empty');
        if (empty) list.insertBefore(row, empty);
        else list.append(row);
        playRowFlip(this.$root.querySelectorAll<HTMLElement>('.nx-todo-item[data-key]'), before);
        this.syncEmpty(list);
        this.paint();
        this.$dispatch('nx-add', { group, title });
        if (input) input.value = '';
        input?.focus();
      },

      /* ---- Counters and the bar ------------------------------------------------------------------- */

      /** Keep every rolling number, the fill, and the spoken totals true after any change. */
      paint(this: Self<TodoSelf>) {
        const root = this.$root;
        const fmt = new Intl.NumberFormat(config.locale);
        const items = root.querySelectorAll('.nx-todo-item');
        const total = items.length;
        const done = root.querySelectorAll('.nx-todo-item .nx-todo-check:checked').length;
        const ratio = total > 0 ? done / total : 0;

        const count = root.querySelector<HTMLElement>('.nx-todo-count');
        if (count) {
          const numbers = count.querySelectorAll<HTMLElement>('.nx-number');
          if (numbers[0]) renderNumber(numbers[0]!, done, config.locale);
          if (numbers[1]) renderNumber(numbers[1]!, total, config.locale);
        }
        const spoken = root.querySelector<HTMLElement>('.nx-todo-head > .nx-visually-hidden');
        if (spoken) spoken.textContent = withParams(config.labels.progress, { done: fmt.format(done), total: fmt.format(total) });

        const fill = root.querySelector<HTMLElement>('.nx-todo-progress-fill');
        if (fill) fill.style.setProperty('--nx-todo-p', String(Math.round(ratio * 1000) / 10));
        const bar = root.querySelector<HTMLElement>('.nx-todo-progress');
        if (bar) {
          bar.setAttribute('aria-valuenow', String(Math.round(ratio * 100)));
          bar.setAttribute('aria-valuetext', withParams(config.labels.progress, { done: fmt.format(done), total: fmt.format(total) }));
        }
        const percent = root.querySelector<HTMLElement>('.nx-todo-progress-label .nx-number');
        if (percent) renderNumber(percent, ratio, config.locale, PERCENT);
        root.toggleAttribute('data-complete', total > 0 && done === total);

        for (const group of root.querySelectorAll<HTMLElement>('.nx-todo-group')) {
          const inGroup = group.querySelectorAll('.nx-todo-item').length;
          const number = group.querySelector<HTMLElement>('.nx-todo-group-count .nx-number');
          if (number) renderNumber(number, inGroup, config.locale);
          const groupSpoken = group.querySelector<HTMLElement>('.nx-todo-group-head > .nx-visually-hidden');
          if (groupSpoken) groupSpoken.textContent = withParams(config.labels.tasks, { count: fmt.format(inGroup) });
        }
      },

      /** "{count} tasks", bound to the group header's hidden span. */
      countText(this: Self<TodoSelf>, groupId: string) {
        const group = this.$root.querySelector<HTMLElement>(`.nx-todo-group[data-group="${CSS.escape(groupId)}"]`);
        const total = group ? group.querySelectorAll('.nx-todo-item').length : 0;
        return withParams(config.labels.tasks, { count: new Intl.NumberFormat(config.locale).format(total) });
      },

      /** A list with no tasks shows the empty plate — unless the drop pill is showing in it. */
      syncEmpty(this: Self<TodoSelf>, list: HTMLElement) {
        const group = list.closest<HTMLElement>('.nx-todo-group')?.dataset.group ?? null;
        const hovering = this.dropping !== null && this.dropping === group;
        let empty = list.querySelector<HTMLElement>('.nx-todo-empty');
        if (list.querySelectorAll('.nx-todo-item').length === 0) {
          if (!empty) {
            empty = document.createElement('li');
            empty.className = 'nx-todo-empty';
            empty.textContent = config.labels.empty;
            list.append(empty);
          }
          empty.hidden = hovering;
        } else {
          empty?.remove();
        }
      },
    };
  });
}
