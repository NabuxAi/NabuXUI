<script lang="ts" module>
  export type ChatPresence = 'online' | 'busy' | 'away' | 'offline';

  export interface ChatMessage {
    id: string;
    /** Incoming (them) or outgoing (me); sides mirror themselves in RTL. */
    side: 'in' | 'out';
    text: string;
    /** A timestamp; any other string is shown as it is. */
    time?: Date | number | string;
  }

  export interface ChatConversation {
    id: string;
    name: string;
    status?: ChatPresence;
    /** Who they are, under the name in the thread header. */
    role?: string;
    /** The last line, shown in the conversations column. */
    preview?: string;
    time?: Date | number | string;
    unread?: number;
    /** Show the three-dot indicator in this thread. */
    typing?: boolean;
    messages?: ChatMessage[];
  }
</script>

<script lang="ts">
  /**
   * The chat block — the hand-written Svelte shape of `nx-chat`: a
   * conversations column (live search + unread, core `foldSearchText` and
   * `roveFocus`) beside one message thread — bubbles that pop in, day
   * separators, a growing composer (core `autogrow`) and the three-dot typing
   * indicator. Sending echoes the message into the thread locally; there is no
   * backend to talk to. The words the block says come from the core i18n table.
   */
  import { onMount, tick } from 'svelte';
  import { activityTime, autogrow, type Cleanup, foldSearchText, prefersReducedMotion, reveal, roveFocus, timeOf, translate } from '@nabuxai/ui-core';
  import { app, intlLocale } from '../store.svelte';
  import Avatar from './Avatar.svelte';
  import NxIcon from './NxIcon.svelte';

  let {
    conversations,
    value = $bindable(undefined),
    onsend = undefined,
    searchPlaceholder = undefined,
    placeholder = undefined,
    emptyText = undefined,
    height = '34rem',
    ariaLabel = undefined,
  }: {
    conversations: ChatConversation[];
    value?: string;
    onsend?: (text: string, conversationId: string) => void;
    searchPlaceholder?: string;
    placeholder?: string;
    emptyText?: string;
    height?: string;
    ariaLabel?: string;
  } = $props();

  const intl = $derived(intlLocale());
  const word = (key: 'search' | 'send' | 'noResults' | 'chatConversations' | 'chatMessages' | 'chatMessagePlaceholder' | 'chatTyping' | 'chatUnread' | 'chatEmpty' | 'chatNoMessages', params: Record<string, string | number> = {}) =>
    translate(app.lang, key, params);

  // The open thread starts at the (bound or) first conversation; afterwards the list owns it.
  // svelte-ignore state_referenced_locally
  let current = $state(value ?? conversations[0]?.id ?? '');
  let query = $state('');
  let draft = $state('');
  let read = $state<Record<string, true>>({});
  let now = $state(Date.now());
  /** Messages sent from this page, echoed per conversation (the seeds come from props). */
  let sent = $state<Record<string, ChatMessage[]>>({});
  let sequence = 0;

  // Bound inside an {#if}, so the binding writes again as threads come and go.
  let thread = $state<HTMLDivElement | undefined>(undefined);
  let area: HTMLTextAreaElement;
  let stopGrow: Cleanup | null = null;
  let stopReveal: Cleanup | null = null;
  let timer: ReturnType<typeof setInterval> | undefined;
  let previousConversation: string | null = null;

  const filtered = $derived.by(() => {
    const q = foldSearchText(query.trim());
    return q ? conversations.filter((conversation) => foldSearchText(`${conversation.name} ${conversation.preview ?? ''}`).includes(q)) : conversations;
  });

  const active = $derived(conversations.find((conversation) => conversation.id === current) ?? conversations[0]);
  /** The thread: the seeded messages plus everything sent here, in order. */
  const messages = $derived([...(active?.messages ?? []), ...(sent[active?.id ?? ''] ?? [])]);
  const typingWord = $derived(active?.typing ? word('chatTyping', { name: active.name }) : null);
  const numberFmt = $derived(new Intl.NumberFormat(intl));

  // The relative times in the column stay honest while the page is open; the
  // thread's opening messages rise one after another (a one-shot group reveal).
  onMount(() => {
    stopGrow = autogrow(area);
    if (thread) stopReveal = reveal(thread, { stagger: true, once: true });
    timer = setInterval(() => (now = Date.now()), 30_000);
    return () => {
      stopGrow?.();
      stopReveal?.();
      clearInterval(timer);
    };
  });

  // New messages and the typing indicator keep the thread glued to its end.
  const lastMessage = $derived(messages[messages.length - 1]);
  $effect(() => {
    void current;
    void lastMessage?.id;
    void active?.typing;
    if (!thread) return;
    void tick().then(() => {
      // Switching threads lands at the end at once; new messages glide there.
      const behavior = previousConversation !== current || prefersReducedMotion() ? 'auto' : 'smooth';
      previousConversation = current;
      thread?.scrollTo({ top: thread.scrollHeight, behavior });
    });
  });

  const select = (id: string) => {
    if (id === current) return;
    current = id;
    value = id;
    read = { ...read, [id]: true };
    query = '';
  };

  const send = () => {
    const text = draft.trim();
    if (!text || !active) return;
    sequence += 1;
    const id = `sent-${sequence}`;
    sent = { ...sent, [active.id]: [...(sent[active.id] ?? []), { id, side: 'out', text, time: Date.now() }] };
    draft = '';
    // The autogrow listens for input events; tell it the field emptied.
    area?.dispatchEvent(new Event('input', { bubbles: false }));
    onsend?.(text, active.id);
  };

  const onComposerKey = (event: KeyboardEvent) => {
    if (event.key === 'Enter' && !event.shiftKey && !event.isComposing) {
      event.preventDefault();
      send();
    }
  };

  const onListKey = (event: KeyboardEvent) => roveFocus(event, event.currentTarget as HTMLElement, '.nx-chat-conversation', { orientation: 'vertical' });

  const unreadOf = (conversation: ChatConversation) => (read[conversation.id] || conversation.id === current ? 0 : conversation.unread ?? 0);

  /** "10:24" for anything that parses as a moment; anything else shows as it is. */
  function clockOf(time: Date | number | string | undefined): string | null {
    if (time === undefined) return null;
    const at = timeOf(time);
    return Number.isNaN(at) ? (typeof time === 'string' ? time : null) : new Intl.DateTimeFormat(intl, { hour: 'numeric', minute: '2-digit' }).format(at);
  }

  /** The day a message belongs to, in the reader's calendar. */
  function dayOf(time: Date | number | string | undefined): string | null {
    if (time === undefined) return null;
    const at = timeOf(time);
    return Number.isNaN(at) ? null : new Intl.DateTimeFormat(intl, { dateStyle: 'medium' }).format(at);
  }

  const isoOf = (time: Date | number | string | undefined) => {
    if (time === undefined) return undefined;
    const at = timeOf(time);
    return Number.isNaN(at) ? undefined : new Date(at).toISOString();
  };
</script>

<section class="nx-chat" style:--nx-chat-height={height} aria-label={ariaLabel}>
  <div class="nx-chat-frame">
    <aside class="nx-chat-side">
      <div class="nx-chat-search">
        <NxIcon name="search" />
        <input
          class="nx-chat-search-input"
          type="search"
          aria-label={searchPlaceholder ?? word('search')}
          placeholder={searchPlaceholder ?? word('search')}
          autocomplete="off"
          bind:value={query}
        />
      </div>
      {#if conversations.length === 0}
        <p class="nx-chat-empty">{emptyText ?? word('chatEmpty')}</p>
      {:else}
        <!-- svelte-ignore a11y_no_noninteractive_element_interactions -->
        <ul class="nx-chat-list" role="listbox" aria-label={word('chatConversations')} onkeydown={onListKey}>
          {#each filtered as conversation (conversation.id)}
            {@const unread = unreadOf(conversation)}
            {@const time = conversation.time !== undefined ? activityTime(conversation.time, now, intl) : null}
            <li role="presentation">
              <button
                type="button"
                class="nx-chat-conversation"
                role="option"
                aria-selected={conversation.id === active?.id}
                aria-label={unread ? `${conversation.name} (${numberFmt.format(unread)} ${word('chatUnread')})` : conversation.name}
                onclick={() => select(conversation.id)}
              >
                <Avatar name={conversation.name} status={conversation.status} />
                <span class="nx-chat-cell">
                  <span class="nx-chat-row">
                    <span class="nx-chat-name">{conversation.name}</span>
                    {#if time}<time class="nx-chat-time" datetime={isoOf(conversation.time)}>{time}</time>{/if}
                  </span>
                  <span class="nx-chat-row">
                    <span class="nx-chat-preview">{conversation.preview}</span>
                    {#if unread > 0}<span class="nx-chat-unread" aria-hidden="true">{numberFmt.format(unread)}</span>{/if}
                  </span>
                </span>
              </button>
            </li>
          {/each}
          {#if filtered.length === 0}
            <li role="presentation">
              <p class="nx-chat-empty">{word('noResults')}</p>
            </li>
          {/if}
        </ul>
      {/if}
    </aside>

    <div class="nx-chat-main">
      {#if active}
        <div class="nx-chat-panel" data-conversation={active.id}>
          <header class="nx-chat-head">
            <Avatar name={active.name} status={active.status} />
            <div class="nx-chat-head-meta">
              <span class="nx-chat-head-name">{active.name}</span>
              {#if active.role}<span class="nx-chat-head-role">{active.role}</span>{/if}
            </div>
          </header>
          <div bind:this={thread} class="nx-chat-thread" role="log" aria-live="polite" aria-label={word('chatMessages')} data-nx-reveal="group">
            {#each messages as message, i (message.id)}
              {@const previous = messages[i - 1]}
              {@const day = dayOf(message.time)}
              {@const previousDay = previous ? dayOf(previous.time) : null}
              {@const clock = clockOf(message.time)}
              {@const grouped = !!previous && previous.side === message.side && day === previousDay}
              {#if day !== previousDay}
                <div class="nx-chat-day">
                  <span>{day}</span>
                </div>
              {/if}
              <div class="nx-chat-message" data-side={message.side} data-grouped={grouped ? '' : undefined} data-fresh={message.id.startsWith('sent-') ? '' : undefined}>
                <div class="nx-chat-bubble">
                  {message.text}
                  {#if clock}<time class="nx-chat-message-time" datetime={isoOf(message.time)}>{clock}</time>{/if}
                </div>
              </div>
            {/each}
            {#if typingWord}
              <p class="nx-chat-typing" data-open="">
                <span class="nx-chat-typing-dots" aria-hidden="true">
                  <i></i>
                  <i></i>
                  <i></i>
                </span>
                {typingWord}
              </p>
            {/if}
            {#if messages.length === 0 && !typingWord}
              <p class="nx-chat-empty">{word('chatNoMessages')}</p>
            {/if}
          </div>
        </div>
      {:else}
        <div class="nx-chat-panel">
          <p class="nx-chat-empty">{emptyText ?? word('chatEmpty')}</p>
        </div>
      {/if}

      <form
        class="nx-chat-composer"
        onsubmit={(event) => {
          event.preventDefault();
          send();
        }}
      >
        <textarea
          bind:this={area}
          class="nx-chat-input"
          rows="1"
          aria-label={placeholder ?? word('chatMessagePlaceholder')}
          placeholder={placeholder ?? word('chatMessagePlaceholder')}
          disabled={!active}
          bind:value={draft}
          onkeydown={onComposerKey}
        ></textarea>
        <button type="submit" class="nx-chat-send" data-ready={draft.trim() ? '' : undefined} disabled={!draft.trim() || !active} aria-label={word('send')}>
          <NxIcon name="arrow-up" />
        </button>
      </form>
    </div>
  </div>
</section>
