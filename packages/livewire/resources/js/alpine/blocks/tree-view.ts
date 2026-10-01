/**
 * Alpine part for the tree-view block. The Blade component renders the whole
 * tree server-side (rows, counts, ARIA state); this adds the ARIA treeview
 * keyboard pattern over it — one roving tab stop, ↑/↓ walking the visible
 * items, →/← expanding and collapsing (swapped in RTL), Home/End jumping to
 * the ends, Enter selecting and Space folding a parent — plus the fold state
 * and the selection, the same behaviours the React component implements over
 * the same CSS.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installTreeViewBlocks } from './alpine/blocks/tree-view';
 *   installTreeViewBlocks(Alpine);   // x-data="nxTreeView(config)"
 */
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

export interface TreeNodeJson {
  id: string;
  label: string;
  meta?: string | null;
  icon?: string | null;
  tone?: string | null;
  children: TreeNodeJson[];
}

export interface TreeLabels {
  tree: string;
  children: string;
  selected: string;
  empty: string;
}

export interface TreeConfig {
  nodes: TreeNodeJson[];
  labels: TreeLabels;
  locale?: string;
  defaultExpanded?: string[];
  selected?: string | null;
  selectable?: boolean;
}

/** `:count` → the number, in the page's digits. */
const withParams = (template: string, params: Record<string, string | number>) =>
  template.replace(/:(\w+)/g, (_, name: string) => String(params[name] ?? `:${name}`));

/** The items a keyboard user can reach: closed groups keep theirs out of view. */
function visibleItems(root: Element): HTMLElement[] {
  return Array.from(root.querySelectorAll<HTMLElement>('li.nx-tree-view-item')).filter(
    (el) => !el.parentElement?.closest('li.nx-tree-view-item[aria-expanded="false"]'),
  );
}

export function installTreeViewBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxTreeView', (config: TreeConfig) => {
    const firstId = config.nodes[0]?.id ?? null;

    interface TreeSelf {
      expanded: string[];
      selected: string | null;
      /** wire:model / x-model on the component binds the selected node id. */
      model: unknown;
      active: string | null;
      announce: string;
      $root: HTMLElement;
      isOpen: (id: string) => boolean;
      toggle: (id: string, open?: boolean) => void;
      pick: (id: string) => void;
      rowClick: (id: string, event: Event) => void;
      key: (event: KeyboardEvent) => void;
      focusIn: (event: Event) => void;
    }

    return {
      /** The ids of the open parents. */
      expanded: [...(config.defaultExpanded ?? [])] as string[],
      selected: (config.selected ?? null) as string | null,
      model: (config.selected ?? null) as unknown,
      /** The roving tab stop follows the focused row. */
      active: (config.selected ?? firstId) as string | null,
      announce: '',

      init(this: Self<TreeSelf>) {
        // wire:model (or x-model, via x-modelable) drives the selection.
        this.$watch('model', (next) => {
          const id = typeof next === 'string' && next !== '' ? next : null;
          if (id !== this.selected) this.selected = id;
        });
        this.$watch('selected', () => {
          this.model = this.selected;
          this.$dispatch('nx-select', this.selected);
        });
      },

      isOpen(this: Self<TreeSelf>, id: string) {
        return this.expanded.includes(id);
      },

      /** Fold a group open or shut; every change also tells the page. */
      toggle(this: Self<TreeSelf>, id: string, open?: boolean) {
        const willOpen = open ?? !this.isOpen(id);
        this.expanded = willOpen
          ? Array.from(new Set(this.expanded.concat(id)))
          : this.expanded.filter((kept) => kept !== id);
        this.$dispatch('nx-expand', { id, open: willOpen });
      },

      /** Select a row: the aria-selected state, the announcement, the event. */
      pick(this: Self<TreeSelf>, id: string) {
        if (config.selectable === false) return;
        this.selected = id;
        const row = this.$root.querySelector<HTMLElement>(
          `.nx-tree-view-item[data-id="${CSS.escape(id)}"] > .nx-tree-view-row`,
        );
        const name = row?.querySelector('.nx-tree-view-label')?.textContent ?? id;
        this.announce = withParams(config.labels.selected, { name });
      },

      /** The twist zone folds; the rest of the row selects. */
      rowClick(this: Self<TreeSelf>, id: string, event: Event) {
        this.active = id;
        const item = this.$root.querySelector<HTMLElement>(`.nx-tree-view-item[data-id="${CSS.escape(id)}"]`);
        const hasChildren = !!item?.querySelector(':scope > .nx-tree-view-group');
        if (hasChildren && (event.target as HTMLElement).closest('.nx-tree-view-twist')) {
          this.toggle(id);
          return;
        }
        this.pick(id);
      },

      /* The ARIA treeview pattern, delegated from the root. */
      key(this: Self<TreeSelf>, event: KeyboardEvent) {
        const tree = this.$root.querySelector('.nx-tree-view');
        const item = (event.target as HTMLElement).closest<HTMLElement>('.nx-tree-view-item');
        if (!tree || !item?.dataset.id) return;
        const id = item.dataset.id;
        const items = visibleItems(tree);
        const index = items.indexOf(item);
        if (index < 0) return;
        const rtl = getComputedStyle(tree).direction === 'rtl';
        const hasChildren = !!item.querySelector(':scope > .nx-tree-view-group');
        const open = this.isOpen(id);
        const focus = (target?: HTMLElement) => {
          if (!target) return;
          event.preventDefault();
          this.active = target.dataset.id ?? null;
          target.focus();
        };

        switch (event.key) {
          case 'ArrowDown':
            return focus(items[index + 1]);
          case 'ArrowUp':
            return focus(items[index - 1]);
          case 'Home':
            return focus(items[0]);
          case 'End':
            return focus(items[items.length - 1]);
          case 'ArrowRight':
          case 'ArrowLeft': {
            // In RTL the keys swap: "inline" is the way the subtree opens toward.
            const inline = rtl ? event.key === 'ArrowLeft' : event.key === 'ArrowRight';
            event.preventDefault();
            if (!hasChildren) return;
            if (inline) {
              if (!open) this.toggle(id, true); // expand; the focus stays put
              else focus(items[index + 1]); // open already: in comes the first child
            } else if (open) {
              this.toggle(id, false); // collapse; the focus stays put
            } else {
              focus(item.parentElement?.closest<HTMLElement>('.nx-tree-view-item') ?? undefined); // up to the parent
            }
            return;
          }
          case 'Enter':
            event.preventDefault();
            return this.pick(id);
          case ' ':
            event.preventDefault();
            return hasChildren ? this.toggle(id) : this.pick(id);
        }
      },

      /** Tab and clicks both move the roving tab stop to the row in focus. */
      focusIn(this: Self<TreeSelf>, event: Event) {
        const item = (event.target as HTMLElement).closest<HTMLElement>('.nx-tree-view-item');
        if (item?.dataset.id) this.active = item.dataset.id;
      },
    };
  });
}
