/**
 * Alpine part for the chat block. The Blade component renders every thread
 * server-side (bubbles, day separators, unread badges in the page's digits);
 * this holds the live state around them — the search filter, which conversation
 * is open, the composer, and the local echo of a sent message. There is no
 * backend: `send` appends the bubble, pins the thread to its end and tells the
 * page (nx-send event, and $wire.sendAction(text, id) when `send-action` is set).
 */
import { foldSearchText, prefersReducedMotion, roveFocus } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };

export interface ChatConfig {
  /** Folded "name preview" strings, one per conversation, in order. */
  texts: string[];
  /** The active conversation's id. */
  active: string;
  /** Unread counts by conversation id. */
  unread: Record<string, number>;
  /** Which threads show the typing indicator right now. */
  typing: Record<string, boolean>;
  /** A Livewire method to call with (text, conversationId) after the echo. */
  action?: string | null;
  locale?: string;
}

export function installChatBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxChat', (config: ChatConfig) => {
    const clock = () => {
      try {
        return new Intl.DateTimeFormat(config.locale, { hour: 'numeric', minute: '2-digit' }).format(Date.now());
      } catch {
        return '';
      }
    };

    return {
      active: config.active,
      // x-modelable="active": wire:model on the root binds the open conversation.
      model: config.active as unknown,
      query: '',
      draft: '',
      read: {} as Record<string, boolean>,
      typing: { ...config.typing } as Record<string, boolean>,

      init(this: Self<{ active: string; model: unknown; pin: (smooth: boolean) => void }>) {
        const self = this;
        this.$nextTick(() => this.pin(false));
        this.$watch('active', () => this.$nextTick(() => this.pin(false)));
        this.$watch('model', (next: unknown) => {
          if (typeof next === 'string' && next && next !== self.active) self.active = next;
        });
      },

      get total(): number {
        return config.texts.length;
      },

      get count(): number {
        const self = this as unknown as { matches: (i: number) => boolean };
        return config.texts.filter((_, i) => self.matches(i)).length;
      },

      matches(this: { query: string }, i: number) {
        const q = foldSearchText(this.query.trim());
        return !q || config.texts[i]!.includes(q);
      },

      /** Arrow keys walk the conversations (Home/End too), the same as the React twin. */
      listKey(this: Self<object>, event: KeyboardEvent) {
        const list = this.$root.querySelector('.nx-chat-list');
        if (list) roveFocus(event, list, '.nx-chat-conversation', { orientation: 'vertical' });
      },

      /** The unread count of a conversation, in the page's digits. */
      countOf(this: Self<{ unreadOf: (id: string) => number }>, id: string) {
        try {
          return new Intl.NumberFormat(config.locale).format(this.unreadOf(id));
        } catch {
          return String(this.unreadOf(id));
        }
      },

      unreadOf(this: Self<{ active: string; read: Record<string, boolean> }>, id: string) {
        return this.read[id] || id === this.active ? 0 : (config.unread[id] ?? 0);
      },

      select(this: Self<{ active: string; read: Record<string, boolean>; model: unknown }>, id: string) {
        this.active = id;
        this.read[id] = true;
        this.model = id;
        this.$dispatch('nx-select', id);
      },

      /** The app (or Livewire) can raise and lower the typing indicator. */
      setTyping(this: Self<{ typing: Record<string, boolean>; pin: (smooth: boolean) => void }>, id: string, on = true) {
        this.typing = { ...this.typing, [id]: on };
        this.$nextTick(() => this.pin(true));
      },

      /** Enter sends, Shift+Enter breaks the line; composing text (Persian IME) never sends. */
      key(this: Self<{ send: () => void }>, event: KeyboardEvent) {
        if (event.key !== 'Enter' || event.shiftKey || event.isComposing) return;
        event.preventDefault();
        this.send();
      },

      send(this: Self<{ active: string; draft: string; echo: (id: string, text: string) => void; pin: (smooth: boolean) => void; model: unknown }>) {
        const text = String(this.draft ?? '').trim();
        const id = this.active;
        if (!text || !id) return;
        this.echo(id, text);
        this.draft = '';
        // The autogrow listens for input events; tell it the field emptied.
        (this.$refs.input as HTMLTextAreaElement | undefined)?.dispatchEvent(new Event('input'));
        this.$dispatch('nx-send', { conversation: id, text });
        const wire = (this as unknown as { $wire?: Wire }).$wire;
        if (config.action && wire) wire.call(config.action, text, id);
      },

      /** A sent bubble, appended with the same markup the server renders. */
      echo(this: Self<{ active: string; pin: (smooth: boolean) => void }>, id: string, text: string) {
        const panel = this.$root.querySelector(`.nx-chat-panel[data-conversation="${CSS.escape(id)}"]`);
        const thread = panel?.querySelector('.nx-chat-thread');
        if (!thread) return;
        // Text is data, never markup: every piece is written as textContent.
        const message = document.createElement('div');
        message.className = 'nx-chat-message';
        message.dataset.side = 'out';
        message.dataset.fresh = '';
        const bubble = document.createElement('div');
        bubble.className = 'nx-chat-bubble';
        bubble.textContent = text;
        const time = document.createElement('time');
        time.className = 'nx-chat-message-time';
        time.textContent = clock();
        bubble.append(time);
        message.append(bubble);
        const typing = thread.querySelector(':scope > .nx-chat-typing');
        if (typing) typing.before(message);
        else thread.append(message);
        this.pin(true);
      },

      /** Keep the open thread at its end — instantly on a switch, smoothly on new messages. */
      pin(this: Self<{ active: string }>, smooth: boolean) {
        const panel = this.$root.querySelector(`.nx-chat-panel[data-conversation="${CSS.escape(this.active)}"]`);
        const thread = panel?.querySelector<HTMLElement>('.nx-chat-thread');
        if (!thread) return;
        thread.scrollTo({ top: thread.scrollHeight, behavior: smooth && !prefersReducedMotion() ? 'smooth' : 'auto' });
      },
    };
  });
}
