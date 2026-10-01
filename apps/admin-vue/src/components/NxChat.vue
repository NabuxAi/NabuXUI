<script setup lang="ts">
/**
 * The conversation block — the hand-written Vue shape of the `chat` block
 * (nx-chat): a searchable conversations column (core `foldSearchText`,
 * `activityTime` for the relative stamps, `roveFocus` for the listbox) beside
 * one thread that reveals its opening messages as a staggered group
 * (data-nx-reveal="group" + core `reveal`), day separators in the reader's
 * calendar, and a composer that autogrows (core `autogrow`). Sending echoes
 * the message into the thread locally (data-fresh pops it in) — there is no
 * backend to talk to. The block's own words come from the core i18n table;
 * the page overrides any of them through `labels`.
 */
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import {
  type Cleanup,
  type MessageKey,
  activityTime,
  autogrow,
  foldSearchText,
  prefersReducedMotion,
  reveal,
  roveFocus,
  timeOf,
  translate,
} from '@nabuxai/ui-core';
import { intlLocale, lang, numberFmt } from '../store';
import NxIcon from './NxIcon.vue';

export type ChatPresence = 'online' | 'busy' | 'away' | 'offline';

export interface ChatMessage {
  id: string;
  /** Incoming (them) or outgoing (me); sides mirror themselves in RTL. */
  side: 'in' | 'out';
  text: string;
  /** A timestamp; any other string is shown as it is. */
  time?: number | string;
}

export interface ChatConversation {
  id: string;
  name: string;
  avatar?: string;
  status?: ChatPresence;
  /** Who they are, under the name in the thread header. */
  role?: string;
  /** The last line, shown in the conversations column. */
  preview?: string;
  time?: number;
  unread?: number;
  /** Show the three-dot indicator in this thread. */
  typing?: boolean;
  messages?: ChatMessage[];
}

/** The words the chat says itself; override any of them with `labels`. */
type ChatWord = 'conversations' | 'messages' | 'messagePlaceholder' | 'typing' | 'unread' | 'empty' | 'noMessages' | 'search' | 'send' | 'noResults';

const WORD_KEYS: Record<ChatWord, MessageKey> = {
  conversations: 'chatConversations',
  messages: 'chatMessages',
  messagePlaceholder: 'chatMessagePlaceholder',
  typing: 'chatTyping',
  unread: 'chatUnread',
  empty: 'chatEmpty',
  noMessages: 'chatNoMessages',
  search: 'search',
  send: 'send',
  noResults: 'noResults',
};

const props = withDefaults(
  defineProps<{
    conversations: ChatConversation[];
    /** The active conversation's id (the first one by default). */
    modelValue?: string;
    placeholder?: string;
    searchPlaceholder?: string;
    emptyText?: string;
    labels?: Partial<Record<ChatWord, string>>;
    /** Height of the whole frame (34rem by default). */
    height?: string;
  }>(),
  {
    modelValue: undefined,
    placeholder: undefined,
    searchPlaceholder: undefined,
    emptyText: undefined,
    labels: undefined,
    height: undefined,
  },
);

const emit = defineEmits<{ 'update:modelValue': [id: string]; send: [payload: { text: string; conversation: string }] }>();

const current = ref(props.modelValue ?? props.conversations[0]?.id ?? '');
const query = ref('');
const draft = ref('');
const read = ref<Record<string, true>>({});
const now = ref(Date.now());
const thread = ref<HTMLElement | null>(null);
const area = ref<HTMLTextAreaElement | null>(null);
let stopReveal: (() => void) | null = null;
let stopGrow: Cleanup | null = null;
let ticker = 0;

// `labels` may override any word; the rest come from the core table, params filling `{name}` slots.
const word = (key: ChatWord, params: Record<string, string | number> = {}) => {
  const override = props.labels?.[key];
  return override === undefined ? translate(lang.value, WORD_KEYS[key], params) : override.replaceAll(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? ''));
};

// The thread state starts from (and follows) the conversations prop; messages sent here echo into it.
const threads = ref<Record<string, ChatMessage[]>>(Object.fromEntries(props.conversations.map((c) => [c.id, [...(c.messages ?? [])]])));
watch(
  () => props.conversations.map((c) => `${c.id}:${c.messages?.map((m) => m.id).join(',')}`).join('|'),
  () => {
    threads.value = Object.fromEntries(props.conversations.map((c) => [c.id, [...(c.messages ?? [])]]));
  },
);

// Messages that were not in the first paint pop in; the opening ones belong to the thread's group reveal instead.
const painted = new Set(Object.values(threads.value).flat().map((message) => message.id));
const isFresh = (message: ChatMessage) => !painted.has(message.id);

// The relative times in the column stay honest while the page is open.
onMounted(() => {
  if (thread.value) stopReveal = reveal(thread.value, { once: true, stagger: true });
  if (area.value) stopGrow = autogrow(area.value);
  ticker = window.setInterval(() => (now.value = Date.now()), 30_000);
});
onBeforeUnmount(() => {
  window.clearInterval(ticker);
  stopReveal?.();
  stopGrow?.();
});

const filtered = computed(() => {
  const q = foldSearchText(query.value.trim());
  return q ? props.conversations.filter((c) => foldSearchText(`${c.name} ${c.preview ?? ''}`).includes(q)) : props.conversations;
});

const active = computed(() => props.conversations.find((c) => c.id === current.value) ?? props.conversations[0]);
const messages = computed(() => (active.value ? threads.value[active.value.id] ?? [] : []));
const typing = computed(() => (active.value?.typing ? word('typing', { name: active.value.name }) : null));

const unreadOf = (conversation: ChatConversation) => (read.value[conversation.id] || conversation.id === current.value ? 0 : conversation.unread ?? 0);

const clockOf = (time: ChatMessage['time']) =>
  time === undefined
    ? null
    : Number.isNaN(timeOf(time))
      ? (typeof time === 'string' ? time : null)
      : new Intl.DateTimeFormat(intlLocale.value, { hour: 'numeric', minute: '2-digit' }).format(timeOf(time));

const dayOf = (time: ChatMessage['time']) =>
  time === undefined || Number.isNaN(timeOf(time)) ? null : new Intl.DateTimeFormat(intlLocale.value, { dateStyle: 'medium' }).format(timeOf(time));

const isoOf = (time: ChatMessage['time']) => (time === undefined || Number.isNaN(timeOf(time)) ? undefined : new Date(timeOf(time)).toISOString());

const initials = (name: string) =>
  name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => Array.from(part)[0])
    .join('')
    .toLocaleUpperCase();

const select = (id: string) => {
  if (id === current.value) return;
  current.value = id;
  read.value = { ...read.value, [id]: true };
  query.value = '';
  emit('update:modelValue', id);
};

// New messages and the typing indicator keep the thread glued to its end.
let previousConversation: string | null = null;
watch(
  () => [current.value, messages.value[messages.value.length - 1]?.id, active.value?.typing] as const,
  () => {
    const el = thread.value;
    if (!el) return;
    // Switching threads lands at the end at once; new messages glide there.
    const behavior = previousConversation !== current.value || prefersReducedMotion() ? 'auto' : 'smooth';
    previousConversation = current.value;
    el.scrollTo({ top: el.scrollHeight, behavior });
  },
  { flush: 'post' },
);

const send = () => {
  const text = draft.value.trim();
  if (!text || !active.value) return;
  threads.value = { ...threads.value, [active.value.id]: [...(threads.value[active.value.id] ?? []), { id: `sent-${Date.now().toString(36)}`, side: 'out', text, time: Date.now() }] };
  draft.value = '';
  // The autogrow listens for input events; tell it the field emptied.
  area.value?.dispatchEvent(new Event('input', { bubbles: false }));
  emit('send', { text, conversation: active.value.id });
};

const onComposerKey = (event: KeyboardEvent) => {
  if (event.key === 'Enter' && !event.shiftKey && !event.isComposing) {
    event.preventDefault();
    send();
  }
};

const onListKey = (event: KeyboardEvent) => roveFocus(event, event.currentTarget as HTMLElement, '.nx-chat-conversation', { orientation: 'vertical' });

const relative = (time: number | undefined) => (time === undefined ? null : activityTime(time, now.value, intlLocale.value));
</script>

<template>
  <section class="nx-chat" :style="height ? { '--nx-chat-height': height } : undefined">
    <div class="nx-chat-frame">
      <aside class="nx-chat-side">
        <div class="nx-chat-search">
          <NxIcon name="search" />
          <input
            v-model="query"
            class="nx-chat-search-input"
            type="search"
            :aria-label="searchPlaceholder ?? word('search')"
            :placeholder="searchPlaceholder ?? word('search')"
            autocomplete="off"
          />
        </div>
        <p v-if="conversations.length === 0" class="nx-chat-empty">{{ emptyText ?? word('empty') }}</p>
        <ul v-else class="nx-chat-list" role="listbox" :aria-label="word('conversations')" @keydown="onListKey">
          <li v-for="conversation in filtered" :key="conversation.id" role="presentation">
            <button
              type="button"
              class="nx-chat-conversation"
              role="option"
              :aria-selected="conversation.id === active?.id"
              :aria-label="unreadOf(conversation) ? `${conversation.name} (${numberFmt.format(unreadOf(conversation))} ${word('unread')})` : conversation.name"
              @click="select(conversation.id)"
            >
              <span class="nx-avatar" :data-status="conversation.status" role="img" :aria-label="conversation.name">
                <span aria-hidden="true">{{ initials(conversation.name) }}</span>
              </span>
              <span class="nx-chat-cell">
                <span class="nx-chat-row">
                  <span class="nx-chat-name">{{ conversation.name }}</span>
                  <time v-if="relative(conversation.time)" class="nx-chat-time" :datetime="isoOf(conversation.time)">{{ relative(conversation.time) }}</time>
                </span>
                <span class="nx-chat-row">
                  <span class="nx-chat-preview">{{ conversation.preview }}</span>
                  <span v-if="unreadOf(conversation) > 0" class="nx-chat-unread" aria-hidden="true">{{ numberFmt.format(unreadOf(conversation)) }}</span>
                </span>
              </span>
            </button>
          </li>
          <li v-if="filtered.length === 0" role="presentation">
            <p class="nx-chat-empty">{{ word('noResults') }}</p>
          </li>
        </ul>
      </aside>

      <div class="nx-chat-main">
        <div v-if="active" class="nx-chat-panel" :data-conversation="active.id">
          <header class="nx-chat-head">
            <span class="nx-avatar" :data-status="active.status" role="img" :aria-label="active.name">
              <span aria-hidden="true">{{ initials(active.name) }}</span>
            </span>
            <div class="nx-chat-head-meta">
              <span class="nx-chat-head-name">{{ active.name }}</span>
              <span v-if="active.role" class="nx-chat-head-role">{{ active.role }}</span>
            </div>
          </header>
          <div ref="thread" class="nx-chat-thread" role="log" aria-live="polite" :aria-label="word('messages')" data-nx-reveal="group">
            <template v-for="(message, i) in messages" :key="message.id">
              <div v-if="dayOf(message.time) !== dayOf(messages[i - 1]?.time)" class="nx-chat-day">
                <span>{{ dayOf(message.time) }}</span>
              </div>
              <div
                class="nx-chat-message"
                :data-side="message.side"
                :data-grouped="i > 0 && messages[i - 1]!.side === message.side && dayOf(message.time) === dayOf(messages[i - 1]?.time) ? '' : undefined"
                :data-fresh="isFresh(message) ? '' : undefined"
              >
                <div class="nx-chat-bubble">
                  {{ message.text }}
                  <time v-if="clockOf(message.time)" class="nx-chat-message-time" :datetime="isoOf(message.time)">{{ clockOf(message.time) }}</time>
                </div>
              </div>
            </template>
            <p v-if="typing" class="nx-chat-typing" data-open="">
              <span class="nx-chat-typing-dots" aria-hidden="true"><i /><i /><i /></span>
              {{ typing }}
            </p>
            <p v-if="messages.length === 0 && !typing" class="nx-chat-empty">{{ word('noMessages') }}</p>
          </div>
        </div>
        <div v-else class="nx-chat-panel">
          <p class="nx-chat-empty">{{ emptyText ?? word('empty') }}</p>
        </div>

        <form class="nx-chat-composer" @submit.prevent="send">
          <textarea
            ref="area"
            v-model="draft"
            class="nx-chat-input"
            rows="1"
            :aria-label="placeholder ?? word('messagePlaceholder')"
            :placeholder="placeholder ?? word('messagePlaceholder')"
            :disabled="!active"
            @keydown="onComposerKey"
          />
          <button type="submit" class="nx-chat-send" :data-ready="draft.trim() ? '' : undefined" :disabled="!draft.trim() || !active" :aria-label="word('send')">
            <NxIcon name="arrow-up" />
          </button>
        </form>
      </div>
    </div>
  </section>
</template>
