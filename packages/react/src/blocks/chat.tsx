/**
 * The chat block (React): a conversations column (search + unread) beside one
 * message thread — bubbles that pop in, day separators, a growing composer and
 * a three-dot typing indicator (css/blocks/chat.css). State is local/props:
 * sending echoes the message into the thread and tells `onSend`; there is no
 * backend to talk to.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent,
  type ReactNode,
  Fragment,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { activityTime, autogrow, foldSearchText, prefersReducedMotion, roveFocus, timeOf } from '@nabuxai/ui-core';
import { cx, useControllable, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale, useT } from '../internal/provider';
import { Avatar } from '../components/display';
import { useReveal } from '../components/text';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** The words the chat says itself; override any of them with the `labels` prop. */
type ChatWord = 'conversations' | 'messages' | 'messagePlaceholder' | 'typing' | 'unread' | 'empty' | 'noMessages';

const WORDS: Record<ChatWord, Record<'en' | 'fa' | 'ar', string>> = {
  conversations: { en: 'Conversations', fa: 'گفتگوها', ar: 'المحادثات' },
  messages: { en: 'Messages', fa: 'پیام‌ها', ar: 'الرسائل' },
  messagePlaceholder: { en: 'Write a message…', fa: 'پیام بنویسید…', ar: 'اكتب رسالة…' },
  typing: { en: '{name} is typing…', fa: '{name} در حال نوشتن…', ar: '{name} يكتب…' },
  unread: { en: 'unread', fa: 'خوانده‌نشده', ar: 'غير مقروء' },
  empty: { en: 'No conversations yet', fa: 'هنوز گفتگویی نیست', ar: 'لا محادثات بعد' },
  noMessages: { en: 'No messages yet', fa: 'هنوز پیامی نیست', ar: 'لا رسائل بعد' },
};

export type ChatPresence = 'online' | 'busy' | 'away' | 'offline';

export interface ChatMessage {
  id: string;
  /** Incoming (them) or outgoing (me); sides mirror themselves in RTL. */
  side: 'in' | 'out';
  text: string;
  /** A Date, timestamp or ISO string; any other string is shown as it is. */
  time?: Date | number | string;
  /** The separator label; derived from `time` in the reader's language when omitted. */
  day?: string;
}

export interface ChatConversation {
  id: string;
  name: string;
  avatar?: string;
  status?: ChatPresence;
  /** Who they are, under the name in the thread header. */
  role?: ReactNode;
  /** The last line, shown in the conversations column. */
  preview?: ReactNode;
  time?: Date | number | string;
  unread?: number;
  /** Show the three-dot indicator in this thread. */
  typing?: boolean;
  messages?: ChatMessage[];
}

export interface ChatProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  conversations: ChatConversation[];
  /** The active conversation's id. */
  value?: string;
  defaultValue?: string;
  onValueChange?: (id: string) => void;
  /** The sent text; the bubble appears locally whatever you do with it. */
  onSend?: (text: string, conversationId: string) => void;
  placeholder?: string;
  searchPlaceholder?: string;
  emptyText?: ReactNode;
  labels?: Partial<Record<ChatWord, string>>;
  /** Height of the whole frame (34rem by default). */
  height?: string;
  locale?: string;
  disabled?: boolean;
}

/** "10:24" for anything that parses as a moment; anything else is shown as it is. */
function clockOf(time: Date | number | string | undefined, locale: string): string | null {
  if (time === undefined) return null;
  const at = timeOf(time);
  return Number.isNaN(at) ? (typeof time === 'string' ? time : null) : new Intl.DateTimeFormat(locale, { hour: 'numeric', minute: '2-digit' }).format(at);
}

/** The day a message belongs to, in the reader's calendar. */
function dayOf(time: Date | number | string | undefined, locale: string): string | null {
  if (time === undefined) return null;
  const at = timeOf(time);
  return Number.isNaN(at) ? null : new Intl.DateTimeFormat(locale, { dateStyle: 'medium' }).format(at);
}

export function Chat({
  conversations,
  value,
  defaultValue,
  onValueChange,
  onSend,
  placeholder,
  searchPlaceholder,
  emptyText,
  labels,
  height,
  locale,
  disabled,
  className,
  style,
  ...rest
}: ChatProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const word = (key: ChatWord, params: Record<string, string | number> = {}) =>
    (labels?.[key] ?? WORDS[key][language]).replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? ''));

  const base = `nx-chat${useId().replace(/:/g, '')}`;
  const [current, setCurrent] = useControllable(value, defaultValue ?? conversations[0]?.id ?? '', onValueChange);
  const [query, setQuery] = useState('');
  const [draft, setDraft] = useState('');
  const [read, setRead] = useState<Record<string, true>>({});
  const [now, setNow] = useState(() => Date.now());
  const thread = useRef<HTMLDivElement>(null);
  const area = useRef<HTMLTextAreaElement>(null);
  const sequence = useRef(0);
  useReveal(thread, { stagger: true });

  // The thread state starts from (and follows) the conversations prop; messages
  // sent here echo into it. A conversation is keyed by id, its messages by id.
  const signature = useMemo(
    () => conversations.map((conversation) => `${conversation.id}\u0001${conversation.messages?.map((m) => m.id).join(',') ?? ''}`).join('\u0000'),
    [conversations],
  );
  const [threads, setThreads] = useState<Record<string, ChatMessage[]>>(() => Object.fromEntries(conversations.map((conversation) => [conversation.id, [...(conversation.messages ?? [])]])));
  useEffect(() => {
    setThreads(Object.fromEntries(conversations.map((conversation) => [conversation.id, [...(conversation.messages ?? [])]])));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [signature]);

  // Messages that were not in the first paint pop in; the opening ones belong
  // to the thread's group reveal instead.
  const painted = useRef<Set<string> | null>(null);
  if (painted.current === null) painted.current = new Set(Object.values(threads).flat().map((message) => message.id));
  const isFresh = (message: ChatMessage) => !painted.current!.has(message.id);

  // The relative times in the column stay honest while the page is open.
  useEffect(() => {
    const timer = setInterval(() => setNow(Date.now()), 30_000);
    return () => clearInterval(timer);
  }, []);

  useEffect(() => (area.current ? autogrow(area.current) : undefined), []);

  const filtered = useMemo(() => {
    const q = foldSearchText(query.trim());
    return q ? conversations.filter((conversation) => foldSearchText(`${conversation.name} ${typeof conversation.preview === 'string' ? conversation.preview : ''}`).includes(q)) : conversations;
  }, [conversations, query]);

  const active = conversations.find((conversation) => conversation.id === current) ?? conversations[0];
  const messages = (active && threads[active.id]) || [];
  const typing = active?.typing ? word('typing', { name: active.name }) : null;

  // New messages and the typing indicator keep the thread glued to its end.
  const lastMessage = messages[messages.length - 1];
  const pinned = [current, lastMessage?.id, active?.typing].join('\u0000');
  const previousConversation = useRef<string | null>(null);
  useIsoLayoutEffect(() => {
    const el = thread.current;
    if (!el) return;
    // Switching threads lands at the end at once; new messages glide there.
    const behavior = previousConversation.current !== current || prefersReducedMotion() ? 'auto' : 'smooth';
    previousConversation.current = current;
    el.scrollTo({ top: el.scrollHeight, behavior });
  }, [pinned, thread]);

  const select = (id: string) => {
    if (id === current) return;
    setCurrent(id);
    setRead((state) => ({ ...state, [id]: true }));
    // The unread badge of the thread we just left stays; this one does not.
    setQuery('');
  };

  const send = () => {
    const text = draft.trim();
    if (!text || disabled || !active) return;
    const id = `${base}-sent-${(sequence.current += 1)}`;
    setThreads((state) => ({ ...state, [active.id]: [...(state[active.id] ?? []), { id, side: 'out', text, time: Date.now() }] }));
    setDraft('');
    // The autogrow listens for input events; tell it the field emptied.
    area.current?.dispatchEvent(new Event('input', { bubbles: false }));
    onSend?.(text, active.id);
  };

  const onListKey = (event: KeyboardEvent<HTMLUListElement>) => {
    roveFocus(event.nativeEvent, event.currentTarget, '.nx-chat-conversation', { orientation: 'vertical' });
  };

  const onComposerKey = (event: KeyboardEvent<HTMLTextAreaElement>) => {
    if (event.key === 'Enter' && !event.shiftKey && !event.nativeEvent.isComposing) {
      event.preventDefault();
      send();
    }
  };

  const unreadOf = (conversation: ChatConversation) => (read[conversation.id] || conversation.id === current ? 0 : conversation.unread ?? 0);
  const number = useMemo(() => new Intl.NumberFormat(intl), [intl]);

  return (
    <section className={cx('nx-chat', className)} style={{ ...(height ? vars({ '--nx-chat-height': height }) : null), ...style }} {...rest}>
      <div className="nx-chat-frame">
        <aside className="nx-chat-side">
          <div className="nx-chat-search">
            <Icon name="search" />
            <input
              className="nx-chat-search-input"
              type="search"
              aria-label={searchPlaceholder ?? t('search')}
              placeholder={searchPlaceholder ?? t('search')}
              autoComplete="off"
              value={query}
              onChange={(event) => setQuery(event.target.value)}
            />
          </div>
          {conversations.length === 0 ? (
            <p className="nx-chat-empty">{emptyText ?? word('empty')}</p>
          ) : (
            <ul className="nx-chat-list" role="listbox" aria-label={word('conversations')} onKeyDown={onListKey}>
              {filtered.map((conversation) => {
                const unread = unreadOf(conversation);
                const time = conversation.time !== undefined ? activityTime(conversation.time, now, intl) : null;
                return (
                  <li key={conversation.id} role="presentation">
                    <button
                      type="button"
                      className="nx-chat-conversation"
                      role="option"
                      aria-selected={conversation.id === active?.id}
                      aria-label={unread ? `${conversation.name} (${number.format(unread)} ${word('unread')})` : conversation.name}
                      onClick={() => select(conversation.id)}
                    >
                      <Avatar name={conversation.name} src={conversation.avatar} status={conversation.status} />
                      <span className="nx-chat-cell">
                        <span className="nx-chat-row">
                          <span className="nx-chat-name">{conversation.name}</span>
                          {time && <time className="nx-chat-time">{time}</time>}
                        </span>
                        <span className="nx-chat-row">
                          <span className="nx-chat-preview">{conversation.preview}</span>
                          {unread > 0 && <span className="nx-chat-unread" aria-hidden="true">{number.format(unread)}</span>}
                        </span>
                      </span>
                    </button>
                  </li>
                );
              })}
              {filtered.length === 0 && (
                <li role="presentation">
                  <p className="nx-chat-empty">{t('noResults')}</p>
                </li>
              )}
            </ul>
          )}
        </aside>

        <div className="nx-chat-main">
          {active ? (
            <div className="nx-chat-panel" data-conversation={active.id}>
              <header className="nx-chat-head">
                <Avatar name={active.name} src={active.avatar} status={active.status} />
                <div className="nx-chat-head-meta">
                  <span className="nx-chat-head-name">{active.name}</span>
                  {active.role && <span className="nx-chat-head-role">{active.role}</span>}
                </div>
              </header>
              <div ref={thread} className="nx-chat-thread" role="log" aria-live="polite" aria-label={word('messages')} data-nx-reveal="group">
                {messages.map((message, i) => {
                  const previous = messages[i - 1];
                  const day = message.day ?? dayOf(message.time, intl);
                  const previousDay = previous ? previous.day ?? dayOf(previous.time, intl) : null;
                  const clock = clockOf(message.time, intl);
                  const grouped = !!previous && previous.side === message.side && day === previousDay;
                  return (
                    <Fragment key={message.id}>
                      {day !== previousDay && (
                        <div className="nx-chat-day">
                          <span>{day}</span>
                        </div>
                      )}
                      <div className="nx-chat-message" data-side={message.side} data-grouped={grouped ? '' : undefined} data-fresh={isFresh(message) ? '' : undefined}>
                        <div className="nx-chat-bubble">
                          {message.text}
                          {clock && <time className="nx-chat-message-time">{clock}</time>}
                        </div>
                      </div>
                    </Fragment>
                  );
                })}
                {typing && (
                  <p className="nx-chat-typing" data-open="">
                    <span className="nx-chat-typing-dots" aria-hidden="true">
                      <i />
                      <i />
                      <i />
                    </span>
                    {typing}
                  </p>
                )}
                {messages.length === 0 && !typing && <p className="nx-chat-empty">{word('noMessages')}</p>}
              </div>
            </div>
          ) : (
            <div className="nx-chat-panel">
              <p className="nx-chat-empty">{emptyText ?? word('empty')}</p>
            </div>
          )}

          <form
            className="nx-chat-composer"
            onSubmit={(event) => {
              event.preventDefault();
              send();
            }}
          >
            <textarea
              ref={area}
              className="nx-chat-input"
              rows={1}
              aria-label={placeholder ?? word('messagePlaceholder')}
              placeholder={placeholder ?? word('messagePlaceholder')}
              disabled={disabled || !active}
              value={draft}
              onChange={(event) => setDraft(event.target.value)}
              onKeyDown={onComposerKey}
            />
            <button type="submit" className="nx-chat-send" data-ready={draft.trim() ? '' : undefined} disabled={disabled || !draft.trim() || !active} aria-label={t('send')}>
              <Icon name="arrow-up" />
            </button>
          </form>
        </div>
      </div>
    </section>
  );
}
