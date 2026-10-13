/**
 * Alpine part for the email block. The Blade component renders the folder
 * rail, the message rows and every reading pane server-side (unread bars,
 * stars and badges in the page's digits); this holds the live state around
 * them — which folder is open, which message is being read (opening it marks
 * it read), the stars and the reply composer. There is no backend: nx-folder /
 * nx-open / nx-star / nx-reply events bubble for the page, and $wire gets the
 * reply when `reply-action` is set.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installEmailBlocks } from './alpine/blocks/email';
 *   installEmailBlocks(Alpine);   // x-data="nxEmail(config)"
 */
import { reveal, roveFocus } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

/** The bits of Livewire's $wire these parts use (absent outside Livewire). */
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };

export interface EmailRowJson {
  id: string;
  folder: string;
  unread: boolean;
  starred: boolean;
  from: string;
  subject: string;
}

export interface EmailLabels {
  messages: string;
  unread: string;
  folderUnread: string;
  star: string;
  unstar: string;
  replyTo: string;
  replySent: string;
}

export interface EmailConfig {
  rows: EmailRowJson[];
  folder: string;
  open: string | null;
  /** A Livewire method to call with (text, messageId) after the composer clears. */
  action?: string | null;
  locale?: string;
  labels: EmailLabels;
}

/** `:count` / `:subject` / `:name` → the value, so the server's words fill in. */
const withParams = (template: string, params: Record<string, string | number>) =>
  template.replace(/:(\w+)/g, (_, name: string) => String(params[name] ?? `:${name}`));

export function installEmailBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxEmail', (config: EmailConfig) => {
    const number = (value: number) => {
      try {
        return new Intl.NumberFormat(config.locale).format(value);
      } catch {
        return String(value);
      }
    };

    interface EmailSelf {
      rows: EmailRowJson[];
      folder: string;
      open: string | null;
      model: unknown;
      draft: string;
      announce: string;
      openMsg: (id: string) => void;
      rowOf: (id: string) => EmailRowJson | undefined;
    }

    return {
      // One live row per message; the markup re-derives everything from these.
      rows: config.rows.map((row) => ({ ...row })) as EmailRowJson[],
      folder: config.folder,
      open: config.open,
      // x-modelable="open": wire:model on the root binds the message being read.
      model: config.open as unknown,
      draft: '',
      announce: '',

      init(this: Self<EmailSelf>) {
        reveal(this.$root, { once: true });
        const self = this;
        this.$watch('model', (next: unknown) => {
          if (typeof next === 'string' && next && next !== self.open) self.openMsg(next);
        });
      },

      rowOf(this: EmailSelf, id: string) {
        return this.rows.find((row: EmailRowJson) => row.id === id);
      },

      /** 'starred' is virtual: it gathers starred messages wherever they sit. */
      inFolder(this: Self<EmailSelf>, id: string) {
        const row = this.rowOf(id);
        if (!row) return false;
        return this.folder === 'starred' ? row.starred : row.folder === this.folder;
      },

      anyVisible(this: Self<EmailSelf & { inFolder: (id: string) => boolean }>) {
        return this.rows.some((row: EmailRowJson) => this.inFolder(row.id));
      },

      isUnread(this: Self<EmailSelf>, id: string) {
        return !!this.rowOf(id)?.unread;
      },

      isStarred(this: Self<EmailSelf>, id: string) {
        return !!this.rowOf(id)?.starred;
      },

      /* ---- Folder badges, in the page's digits ------------------------------------------- */

      /** A folder's badge: unread inside it, starred for the virtual 'starred'. */
      unreadCount(this: Self<EmailSelf>, folder: string) {
        if (folder === 'starred') return this.rows.filter((row: EmailRowJson) => row.starred).length;
        return this.rows.filter((row: EmailRowJson) => row.folder === folder && row.unread).length;
      },

      countOf(this: Self<EmailSelf & { unreadCount: (folder: string) => number }>, folder: string) {
        return number(this.unreadCount(folder));
      },

      /** “{count} unread”, bound to the folder's hidden span. */
      unreadText(this: Self<EmailSelf & { unreadCount: (folder: string) => number }>, folder: string) {
        return withParams(config.labels.folderUnread, { count: number(this.unreadCount(folder)) });
      },

      /* ---- Folders and the open message ----------------------------------------------------- */

      pick(this: Self<EmailSelf>, folder: string) {
        if (folder === this.folder) return;
        // Leaving the open message behind closes it; the empty reader takes over.
        const openRow = this.open !== null ? this.rowOf(this.open) : undefined;
        const staying = !!openRow && (folder === 'starred' ? openRow.starred : openRow.folder === folder);
        this.folder = folder;
        if (!staying) this.open = null;
        this.$dispatch('nx-folder', folder);
      },

      /** Opening a message also marks it read — the bar folds away, the type settles. */
      openMsg(this: Self<EmailSelf>, id: string) {
        const row = this.rowOf(id);
        if (!row) return;
        this.open = row.id;
        this.model = row.id;
        row.unread = false;
        this.$dispatch('nx-open', row.id);
      },

      star(this: Self<EmailSelf>, id: string) {
        const row = this.rowOf(id);
        if (!row) return;
        row.starred = !row.starred;
        this.$dispatch('nx-star', { message: row.id, starred: row.starred });
      },

      starLabel(this: Self<EmailSelf>, id: string) {
        const row = this.rowOf(id);
        if (!row) return config.labels.star;
        return withParams(row.starred ? config.labels.unstar : config.labels.star, { subject: row.subject });
      },

      /* ---- The composer ------------------------------------------------------------------------- */

      /** “Reply to {name}” for the open message. */
      replyLabel(this: Self<EmailSelf>) {
        const row = this.open !== null ? this.rowOf(this.open) : undefined;
        return row ? withParams(config.labels.replyTo, { name: row.from }) : config.labels.messages;
      },

      /** Enter sends, Shift+Enter breaks the line; composing text (Persian IME) never sends. */
      key(this: Self<{ send: () => void }>, event: KeyboardEvent) {
        if (event.key !== 'Enter' || event.shiftKey || event.isComposing) return;
        event.preventDefault();
        this.send();
      },

      send(this: Self<EmailSelf>) {
        const text = String(this.draft ?? '').trim();
        const id = this.open;
        if (!text || !id) return;
        const row = this.rowOf(id);
        this.draft = '';
        // The autogrow listens for input events; tell it the field emptied.
        (this.$refs.input as HTMLTextAreaElement | undefined)?.dispatchEvent(new Event('input'));
        this.announce = withParams(config.labels.replySent, { name: row?.from ?? '' });
        this.$dispatch('nx-reply', { message: id, text });
        const wire = (this as unknown as { $wire?: Wire }).$wire;
        if (config.action && wire) wire.call(config.action, text, id);
      },

      /** Arrow keys walk the rows (Home/End too), the same as the React twin. */
      listKey(this: Self<object>, event: KeyboardEvent) {
        const list = this.$root.querySelector('.nx-email-list');
        if (list) roveFocus(event, list, '.nx-email-row', { orientation: 'vertical' });
      },
    };
  });
}
