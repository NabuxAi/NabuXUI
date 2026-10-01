/**
 * Alpine part for the file-manager block. The Blade component renders every
 * entry (the current folder's visible, the rest hidden), the breadcrumb, the
 * toolbar and an empty details drawer server-side; this adds the folder
 * navigation, the search filter, the rolling count, the details drawer and the
 * upload picker — the same behaviours the React component implements over the
 * same CSS.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installFileManagerBlocks } from './alpine/blocks/file-manager';
 *   installFileManagerBlocks(Alpine);   // x-data="nxFileManager(config)"
 */
import { reveal } from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

type LivewireGlobal = { hook?: (name: string, callback: (payload: { el: Element }) => void) => (() => void) | void };

export type FileManagerKind = 'folder' | 'image' | 'audio' | 'video' | 'file';
export type FileManagerView = 'grid' | 'list';

export interface FileManagerEntryJson {
  id: string;
  name: string;
  kind: FileManagerKind;
  parent?: string | null;
  size?: number | null;
  items?: number | null;
  /** Already formatted by the server, in the page's locale. */
  modified?: string | null;
  href?: string | null;
  preview?: string | null;
  details?: Array<{ label: string; value: string }> | null;
}

export interface FileManagerLabels {
  board: string;
  items: string;
  empty: string;
  results: string;
  entered: string;
  detailsFor: string;
  type: string;
  size: string;
  modified: string;
  contains: string;
  kinds: Record<FileManagerKind, string>;
}

export interface FileManagerConfig {
  items: FileManagerEntryJson[];
  labels: FileManagerLabels;
  locale?: string;
  view?: FileManagerView;
  current?: string | null;
  /** A wire:model on the picker: Livewire keeps the files, a plain picker resets. */
  wireUpload?: boolean;
}

/** Fold case, accents and the Persian/Arabic letter variants so a search matches either spelling. */
const fold = (text: string) =>
  text
    .toLocaleLowerCase()
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[يى]/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[\u064b-\u0670\u065f\u200c]/g, '');

/** `:count` → the number, in the page's digits. */
const withParams = (template: string, params: Record<string, string | number>) => template.replace(/:(\w+)/g, (_, name: string) => String(params[name] ?? `:${name}`));

const UNITS = ['B', 'KB', 'MB', 'GB', 'TB'];

/** "۲٫۴ MB" — locale digits, a thin space, a unit symbol either language reads. */
function formatBytes(bytes: number, locale?: string): string {
  let value = bytes;
  let unit = 0;
  while (value >= 1024 && unit < UNITS.length - 1) {
    value /= 1024;
    unit += 1;
  }
  const number = new Intl.NumberFormat(locale, { maximumFractionDigits: unit === 0 || value >= 100 ? 0 : 1 }).format(value);
  return `${number}\u2009${UNITS[unit]}`;
}

export function installFileManagerBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxFileManager', (config: FileManagerConfig) => {
    const items = config.items ?? [];
    const byId = new Map(items.map((entry) => [entry.id, entry]));
    const format = new Intl.NumberFormat(config.locale);
    let unhook: () => void = () => {};

    interface FileManagerSelf {
      view: FileManagerView;
      current: string | null;
      query: string;
      selected: string | null;
      announce: string;
      $root: HTMLElement;
      $refs: Record<string, HTMLElement>;
      apply: () => void;
      paintCrumbs: () => void;
      countText: () => string;
      selectedKind: () => FileManagerKind;
      entryName: (id: string) => string;
      entryKindLabel: (id: string) => string;
      navigate: (folder: string | null) => void;
      activate: (id: string) => void;
      select: (id: string) => void;
      close: () => void;
      fillDrawer: (entry: FileManagerEntryJson) => void;
      clearSearch: () => void;
      viewChange: (event: Event) => void;
      pick: () => void;
      picked: (event: Event) => void;
    }

    return {
      view: (config.view ?? 'grid') as FileManagerView,
      current: config.current ?? null,
      query: '',
      selected: null,
      announce: '',

      init(this: Self<FileManagerSelf>) {
        reveal(this.$root, { once: true });
        // A Livewire re-render re-renders the folders' hidden state server-side; keep it honest anyway.
        const livewire = (window as unknown as { Livewire?: LivewireGlobal }).Livewire;
        const root = this.$root;
        if (livewire?.hook) {
          unhook =
            livewire.hook('morphed', ({ el }) => {
              if (el.contains(root)) requestAnimationFrame(() => this.apply());
            }) ?? (() => {});
        }
      },

      destroy(this: Self<FileManagerSelf>) {
        unhook();
      },

      /** One pass: which entries show, the count, the crumbs and the empty states. */
      apply(this: Self<FileManagerSelf>) {
        const q = fold(this.query.trim());
        let shown = 0;
        for (const item of this.$root.querySelectorAll<HTMLElement>('.nx-fm-item')) {
          const on = (item.dataset.parent ?? '') === (this.current ?? '') && (!q || fold(item.dataset.name ?? '').includes(q));
          item.hidden = !on;
          if (on) shown += 1;
        }
        const count = this.$root.querySelector<HTMLElement>('.nx-fm-count .nx-number');
        if (count) renderNumber(count, shown, config.locale);
        const spoken = this.$root.querySelector<HTMLElement>('.nx-fm-countwrap > .nx-visually-hidden');
        if (spoken) spoken.textContent = withParams(config.labels.items, { count: format.format(shown) });
        this.paintCrumbs();
        const emptyFolder = this.$root.querySelector<HTMLElement>('.nx-fm-empty[data-variant="folder"]');
        const emptySearch = this.$root.querySelector<HTMLElement>('.nx-fm-empty[data-variant="results"]');
        if (emptyFolder) emptyFolder.hidden = !(shown === 0 && !q);
        if (emptySearch) emptySearch.hidden = !(shown === 0 && q !== '');
        const list = this.$root.querySelector<HTMLElement>('.nx-fm-items');
        const here = this.current ? byId.get(this.current) : undefined;
        if (list) list.setAttribute('aria-label', here ? here.name : config.labels.board);
      },

      /** The trail of crumbs above the current folder, in walk order, the last one current. */
      paintCrumbs(this: Self<FileManagerSelf>) {
        const nav = this.$root.querySelector<HTMLElement>('.nx-fm-crumbs');
        if (!nav) return;
        const trail: string[] = [];
        const seen = new Set<string>();
        let at = this.current;
        while (at && !seen.has(at)) {
          seen.add(at);
          const entry = byId.get(at);
          if (!entry) break;
          trail.unshift(at);
          at = entry.parent ?? null;
        }
        const order = ['', ...trail];
        for (const id of order) {
          const crumb = nav.querySelector<HTMLElement>(`.nx-fm-crumb[data-folder="${CSS.escape(id)}"]`);
          // Appending in walk order puts each crumb after the one above it.
          if (crumb) nav.append(crumb);
        }
        for (const crumb of Array.from(nav.querySelectorAll<HTMLElement>('.nx-fm-crumb'))) {
          const id = crumb.dataset.folder ?? '';
          const on = order.includes(id);
          crumb.hidden = !on;
          if (on && id === order[order.length - 1]) crumb.setAttribute('aria-current', 'page');
          else crumb.removeAttribute('aria-current');
        }
      },

      /** "{count} items", bound to the toolbar's hidden span. */
      countText(this: Self<FileManagerSelf>) {
        const shown = this.$root.querySelectorAll('.nx-fm-item:not([hidden])').length;
        return withParams(config.labels.items, { count: format.format(shown) });
      },

      /** The kind of the entry whose drawer is open, for the drawer's icon tile. */
      selectedKind(this: Self<FileManagerSelf>): FileManagerKind {
        return (this.selected ? byId.get(this.selected)?.kind : undefined) ?? 'file';
      },

      /** The selected entry's name, bound to the drawer title. */
      entryName(this: Self<FileManagerSelf>, id: string) {
        const entry = byId.get(id);
        return entry ? entry.name : '';
      },

      /** The selected entry's kind in words, bound under the drawer title. */
      entryKindLabel(this: Self<FileManagerSelf>, id: string) {
        const entry = byId.get(id);
        return entry ? config.labels.kinds[entry.kind] : '';
      },

      /** Step into a folder (or out to the root with null). */
      navigate(this: Self<FileManagerSelf>, folder: string | null) {
        if ((this.current ?? null) === (folder ?? null)) return;
        this.current = folder ?? null;
        this.selected = null;
        const entry = folder ? byId.get(folder) : undefined;
        this.announce = entry ? withParams(config.labels.entered, { name: entry.name }) : '';
        this.apply();
        this.$dispatch('nx-navigate', { folder: this.current });
      },

      /** A card: folders navigate, files toggle their details drawer. */
      activate(this: Self<FileManagerSelf>, id: string) {
        const entry = byId.get(id);
        if (!entry) return;
        if (entry.kind === 'folder') {
          this.navigate(id);
          return;
        }
        this.select(id);
      },

      select(this: Self<FileManagerSelf>, id: string) {
        if (this.selected === id) {
          this.close();
          return;
        }
        const entry = byId.get(id);
        if (!entry) return;
        this.selected = id;
        this.fillDrawer(entry);
        this.announce = withParams(config.labels.detailsFor, { name: entry.name });
        this.$dispatch('nx-open', { entry: id });
      },

      close(this: Self<FileManagerSelf>) {
        this.selected = null;
        this.announce = '';
      },

      /** The drawer's content, built from the entry — text nodes only, never markup. */
      fillDrawer(this: Self<FileManagerSelf>, entry: FileManagerEntryJson) {
        const drawer = this.$root.querySelector<HTMLElement>('.nx-fm-drawer');
        if (!drawer) return;
        const icon = drawer.querySelector<HTMLElement>('.nx-fm-drawer-icon');
        if (icon) {
          // The card already drew this kind's icon; borrow it (the tile's kind,
          // title and kind label ride their own x-bind / x-text bindings).
          const source = this.$root.querySelector<HTMLElement>(`.nx-fm-item[data-id="${CSS.escape(entry.id)}"] .nx-fm-icon`);
          icon.replaceChildren(...Array.from(source?.children ?? []).map((child) => child.cloneNode(true)));
        }
        const rows = drawer.querySelector<HTMLElement>('.nx-fm-drawer-rows');
        if (rows) {
          rows.replaceChildren();
          const add = (label: string, value: string) => {
            const row = document.createElement('div');
            const dt = document.createElement('dt');
            dt.textContent = label;
            const dd = document.createElement('dd');
            dd.textContent = value;
            row.append(dt, dd);
            rows.append(row);
          };
          add(config.labels.type, config.labels.kinds[entry.kind]);
          if (entry.size != null && entry.kind !== 'folder') add(config.labels.size, formatBytes(entry.size, config.locale));
          if (entry.modified) add(config.labels.modified, entry.modified);
          if (entry.kind === 'folder' && entry.items != null) add(config.labels.contains, withParams(config.labels.items, { count: format.format(entry.items) }));
          for (const detail of entry.details ?? []) add(detail.label, detail.value);
        }
        const link = drawer.querySelector<HTMLAnchorElement>('.nx-fm-drawer-open');
        if (link) {
          if (entry.href) {
            link.hidden = false;
            link.setAttribute('href', entry.href);
          } else {
            link.hidden = true;
          }
        }
        const preview = drawer.querySelector<HTMLElement>('.nx-fm-drawer-preview');
        const image = preview?.querySelector<HTMLImageElement>('img');
        if (preview) {
          if (entry.preview && image) {
            image.src = entry.preview;
            preview.hidden = false;
          } else {
            preview.hidden = true;
          }
        }
      },

      clearSearch(this: Self<FileManagerSelf>) {
        this.query = '';
        (this.$refs.search as HTMLInputElement | undefined)?.focus();
      },

      /** The stock segmented control's change, bubbling from its radio. */
      viewChange(this: Self<FileManagerSelf>, event: Event) {
        const value = (event.target as HTMLInputElement).value;
        if (value !== 'grid' && value !== 'list') return;
        if (this.view === value) return;
        this.view = value;
        this.$dispatch('nx-view', { view: value });
      },

      pick(this: Self<FileManagerSelf>) {
        (this.$refs.picker as HTMLInputElement | undefined)?.click();
      },

      picked(this: Self<FileManagerSelf>, event: Event) {
        const input = event.target as HTMLInputElement;
        const files = Array.from(input.files ?? []);
        if (files.length > 0) this.$dispatch('nx-upload', { files });
        // A bound wire:model keeps its files for Livewire's upload; a plain picker resets.
        if (!config.wireUpload) input.value = '';
      },
    };
  });
}
