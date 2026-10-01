/**
 * The email block (React): a mail client — a folder rail, a message list
 * (unread bar + star, keyboard walked) and a reading pane with a reply
 * composer that is soft on Arabic-script diacritics (roomy lines, no
 * tracking, wrap-anywhere — css/blocks/email.css). State is local/props:
 * opening a message marks it read, stars toggle, sending a reply tells
 * `onReply`; there is no backend to talk to.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent,
  type ReactNode,
  useEffect,
  useMemo,
  useRef,
  useState,
} from 'react';
import { type IconName, type MessageKey, autogrow, roveFocus, timeOf } from '@nabuxai/ui-core';
import { cx, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale, useT } from '../internal/provider';
import { Avatar } from '../components/display';
import { useReveal } from '../components/text';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** The words the mail client says itself; override any of them with the `labels` prop. */
type EmailWord =
  | 'mail' | 'folders' | 'inbox' | 'starred' | 'sent' | 'drafts' | 'archive' | 'trash'
  | 'messages' | 'empty' | 'noMessage' | 'unread' | 'folderUnread' | 'star' | 'unstar'
  | 'from' | 'to' | 'replyTo' | 'placeholder' | 'replySent';

/** Where each of them lives in the core i18n table. */
const WORD_KEYS: Record<EmailWord, MessageKey> = {
  mail: 'emailMail',
  folders: 'emailFolders',
  inbox: 'emailInbox',
  starred: 'emailStarred',
  sent: 'emailSent',
  drafts: 'emailDrafts',
  archive: 'emailArchive',
  trash: 'emailTrash',
  messages: 'emailMessages',
  empty: 'emailEmpty',
  noMessage: 'emailNoMessage',
  unread: 'emailUnread',
  folderUnread: 'emailFolderUnread',
  star: 'emailStar',
  unstar: 'emailUnstar',
  from: 'emailFrom',
  to: 'emailTo',
  replyTo: 'emailReplyTo',
  placeholder: 'emailPlaceholder',
  replySent: 'emailReplySent',
};

/** The built-in folder rail; `folders` replaces it wholesale. */
const DEFAULT_FOLDERS: Array<{ id: string; icon: IconName; key: MessageKey }> = [
  { id: 'inbox', icon: 'mail', key: 'emailInbox' },
  { id: 'starred', icon: 'star', key: 'emailStarred' },
  { id: 'sent', icon: 'arrow-right', key: 'emailSent' },
  { id: 'drafts', icon: 'edit', key: 'emailDrafts' },
  { id: 'archive', icon: 'folder', key: 'emailArchive' },
  { id: 'trash', icon: 'trash', key: 'emailTrash' },
];

export interface EmailAddress {
  name: string;
  email?: string;
  /** The sender's picture; their initial otherwise. */
  avatar?: string;
}

export interface EmailMessage {
  id: string;
  from: EmailAddress;
  subject: string;
  /** Plain text; lines become paragraphs, the first is the list snippet. */
  body?: string;
  /** A Date, timestamp or ISO string; any other string is shown as it is. */
  time?: Date | number | string;
  unread?: boolean;
  starred?: boolean;
  /** Which folder it sits in ('inbox' by default). 'starred' is virtual: `starred`. */
  folder?: string;
  /** Who it went to, free text ("me, سارا"); the To line appears when set. */
  to?: string;
}

export interface EmailFolder {
  id: string;
  /** Falls back to the built-in word for the six known ids, else the id. */
  label?: string;
  icon?: IconName;
}

export interface EmailProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  messages: EmailMessage[];
  /** Custom folders replace the built-in six. */
  folders?: EmailFolder[];
  /** The open folder's id ('inbox' by default). */
  folder?: string;
  defaultFolder?: string;
  onFolderChange?: (id: string) => void;
  /** The open (reading) message's id; null for none. */
  open?: string | null;
  defaultOpen?: string | null;
  onOpenChange?: (id: string | null) => void;
  /** Fired when a star toggles, with its new state. */
  onStarredChange?: (id: string, starred: boolean) => void;
  /** Fired when a reply is sent; the draft clears whatever you do with it. */
  onReply?: (text: string, message: EmailMessage) => void;
  placeholder?: string;
  emptyText?: ReactNode;
  labels?: Partial<Record<EmailWord, string>>;
  /** The frame's accessible name. */
  label?: string;
  /** Height of the whole frame (36rem by default). */
  height?: string;
  locale?: string;
  disabled?: boolean;
}

const startOfDay = (at: number) => new Date(at).setHours(0, 0, 0, 0);

/** "10:24" today, a medium date any older day; a plain string is shown as it is. */
function whenInList(time: Date | number | string | undefined, locale: string, now: number): { label: string; iso?: string } | null {
  if (time === undefined) return null;
  const at = timeOf(time);
  if (Number.isNaN(at)) return typeof time === 'string' && time ? { label: time } : null;
  const label =
    startOfDay(at) === startOfDay(now)
      ? new Intl.DateTimeFormat(locale, { hour: 'numeric', minute: '2-digit' }).format(at)
      : new Intl.DateTimeFormat(locale, { dateStyle: 'medium' }).format(at);
  return { label, iso: new Date(at).toISOString() };
}

/** The full moment for the reading pane; undefined for a plain label. */
function whenFull(time: Date | number | string | undefined, locale: string): { label: string; iso?: string } | null {
  if (time === undefined) return null;
  const at = timeOf(time);
  if (Number.isNaN(at)) return typeof time === 'string' && time ? { label: time } : null;
  return { label: new Intl.DateTimeFormat(locale, { dateStyle: 'medium', timeStyle: 'short' }).format(at), iso: new Date(at).toISOString() };
}

/** The first non-blank body line, as the list snippet. */
const snippetOf = (message: EmailMessage): string | null => {
  const line = message.body?.split('\n').find((part) => part.trim());
  return line ? line.trim() : null;
};

export function Email({
  messages,
  folders: customFolders,
  folder: controlledFolder,
  defaultFolder = 'inbox',
  onFolderChange,
  open: controlledOpen,
  defaultOpen,
  onOpenChange,
  onStarredChange,
  onReply,
  placeholder,
  emptyText,
  labels,
  label,
  height,
  locale,
  disabled,
  className,
  style,
  ...rest
}: EmailProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  // `labels` may override any word; the rest come from the core table, params filling `{name}` slots.
  const word = (key: EmailWord, params: Record<string, string | number> = {}) => {
    const override = labels?.[key];
    return override === undefined ? t(WORD_KEYS[key], params) : override.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? ''));
  };

  const root = useRef<HTMLElement>(null);
  const list = useRef<HTMLUListElement>(null);
  const area = useRef<HTMLTextAreaElement>(null);
  const [draft, setDraft] = useState('');
  const [announced, setAnnounced] = useState('');
  const [now, setNow] = useState(() => Date.now());
  useReveal(root);
  useReveal(list, { stagger: true });

  // The list state starts from (and follows) the messages prop; read/star
  // changes made here stay until the ids change again.
  const signature = useMemo(() => messages.map((message) => message.id).join('\u0000'), [messages]);
  const [items, setItems] = useState<EmailMessage[]>(() => messages.map((message) => ({ ...message })));
  useEffect(() => {
    setItems(messages.map((message) => ({ ...message })));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [signature]);

  const folders = useMemo(() => {
    const given: EmailFolder[] = customFolders ?? DEFAULT_FOLDERS.map((entry) => ({ id: entry.id, icon: entry.icon }));
    return given.map((folder) => {
      const known = DEFAULT_FOLDERS.find((entry) => entry.id === folder.id);
      return { ...folder, label: folder.label ?? (known ? t(known.key) : folder.id), icon: folder.icon ?? ('folder' as IconName) };
    });
  }, [customFolders, t]);

  const inFolder = (message: EmailMessage, id: string) => (id === 'starred' ? !!message.starred : (message.folder ?? 'inbox') === id);

  const [folder, setFolder] = useControllable(
    controlledFolder,
    folders.some((entry) => entry.id === defaultFolder) ? defaultFolder : (folders[0]?.id ?? 'inbox'),
    onFolderChange,
  );
  const [open, setOpen] = useControllable<string | null>(
    controlledOpen,
    defaultOpen ?? items.find((message) => inFolder(message, defaultFolder))?.id ?? null,
    onOpenChange,
  );

  // The relative "today" of the list stays honest while the page is open.
  useEffect(() => {
    const timer = setInterval(() => setNow(Date.now()), 60_000);
    return () => clearInterval(timer);
  }, []);

  useEffect(() => (area.current ? autogrow(area.current) : undefined), []);

  const visible = useMemo(() => items.filter((message) => inFolder(message, folder)), [items, folder]);
  const reading = items.find((message) => message.id === open) ?? null;
  const number = useMemo(() => new Intl.NumberFormat(intl), [intl]);

  /** A folder's badge: unread inside it, starred for the virtual 'starred'. */
  const countOf = (id: string) =>
    id === 'starred' ? items.filter((message) => message.starred).length : items.filter((message) => (message.folder ?? 'inbox') === id && message.unread).length;

  const pick = (id: string) => {
    if (id === folder) return;
    // Leaving the open message behind closes it; the empty reader takes over.
    const staying = open !== null && items.some((message) => message.id === open && inFolder(message, id));
    setFolder(id);
    if (!staying) setOpen(null);
  };

  const read = (id: string) => {
    if (id !== open) setOpen(id);
    setItems((prev) => (prev.some((message) => message.id === id && message.unread) ? prev.map((message) => (message.id === id ? { ...message, unread: false } : message)) : prev));
  };

  const toggleStar = (id: string) => {
    const starred = items.find((message) => message.id === id)?.starred ?? false;
    setItems((prev) => prev.map((message) => (message.id === id ? { ...message, starred: !message.starred } : message)));
    onStarredChange?.(id, !starred);
  };

  const send = () => {
    const text = draft.trim();
    if (!text || disabled || !reading) return;
    setDraft('');
    // The autogrow listens for input events; tell it the field emptied.
    area.current?.dispatchEvent(new Event('input', { bubbles: false }));
    onReply?.(text, reading);
    setAnnounced(word('replySent', { name: reading.from.name }));
  };

  const onListKey = (event: KeyboardEvent<HTMLUListElement>) => {
    roveFocus(event.nativeEvent, event.currentTarget, '.nx-email-row', { orientation: 'vertical' });
  };

  const onComposerKey = (event: KeyboardEvent<HTMLTextAreaElement>) => {
    if (event.key === 'Enter' && !event.shiftKey && !event.nativeEvent.isComposing) {
      event.preventDefault();
      send();
    }
  };

  return (
    <section
      ref={root}
      className={cx('nx-email', className)}
      aria-label={label ?? word('mail')}
      style={{ ...(height ? vars({ '--nx-email-height': height }) : null), ...style }}
      data-nx-reveal=""
      {...rest}
    >
      <div className="nx-email-frame">
        <nav className="nx-email-folders" aria-label={word('folders')}>
          <ul className="nx-email-folder-list">
            {folders.map((entry) => {
              const count = countOf(entry.id);
              return (
                <li key={entry.id}>
                  <button type="button" className="nx-email-folder" aria-current={entry.id === folder ? 'true' : undefined} onClick={() => pick(entry.id)}>
                    <Icon name={entry.icon ?? 'folder'} />
                    <span className="nx-email-folder-label">{entry.label}</span>
                    {count > 0 && (
                      <>
                        <span className="nx-email-folder-count" aria-hidden="true">
                          {number.format(count)}
                        </span>
                        <span className="nx-visually-hidden">{word('folderUnread', { count: number.format(count) })}</span>
                      </>
                    )}
                  </button>
                </li>
              );
            })}
          </ul>
        </nav>

        <div className="nx-email-listcol">
          <ul className="nx-email-list" role="listbox" aria-label={word('messages')} data-nx-reveal="group" onKeyDown={onListKey} ref={list}>
            {visible.map((message, i) => {
              const when = whenInList(message.time, intl, now);
              const snippet = snippetOf(message);
              return (
                <li key={message.id} className="nx-email-item" role="presentation" style={vars({ '--nx-i': i })}>
                  <button
                    type="button"
                    className="nx-email-row"
                    role="option"
                    aria-selected={message.id === open}
                    data-unread={message.unread ? '' : undefined}
                    onClick={() => read(message.id)}
                  >
                    <Avatar name={message.from.name} src={message.from.avatar} />
                    <span className="nx-email-cell">
                      <span className="nx-email-line">
                        <span className="nx-email-from">{message.from.name}</span>
                        {when && (
                          <time className="nx-email-when" dateTime={when.iso}>
                            {when.label}
                          </time>
                        )}
                      </span>
                      <span className="nx-email-line">
                        <span className="nx-email-subject">{message.subject}</span>
                      </span>
                      {snippet && <span className="nx-email-snippet">{snippet}</span>}
                    </span>
                    {message.unread && <span className="nx-visually-hidden">{word('unread')}</span>}
                  </button>
                  <button
                    type="button"
                    className="nx-email-star"
                    aria-pressed={message.starred ? 'true' : 'false'}
                    aria-label={(message.starred ? word('unstar') : word('star')).replace('{subject}', message.subject)}
                    onClick={() => toggleStar(message.id)}
                  >
                    <Icon name="star" />
                  </button>
                </li>
              );
            })}
            {visible.length === 0 && (
              <li className="nx-email-empty" role="presentation">
                <p>{emptyText ?? word('empty')}</p>
              </li>
            )}
          </ul>
        </div>

        <div className="nx-email-main">
          {reading ? (
            <div className="nx-email-reading" key={reading.id}>
              <header className="nx-email-head">
                <div className="nx-email-head-main">
                  <h3 className="nx-email-subject-full">{reading.subject}</h3>
                  <div className="nx-email-parties">
                    <Avatar name={reading.from.name} src={reading.from.avatar} size="sm" />
                    <div className="nx-email-party-group">
                      <span className="nx-email-party">
                        <span className="nx-email-role">{word('from')}</span>
                        <span className="nx-email-address">
                          {reading.from.name}
                          {reading.from.email ? ` · ${reading.from.email}` : ''}
                        </span>
                      </span>
                      {reading.to && (
                        <span className="nx-email-party">
                          <span className="nx-email-role">{word('to')}</span>
                          <span className="nx-email-address">{reading.to}</span>
                        </span>
                      )}
                      {(() => {
                        const full = whenFull(reading.time, intl);
                        return full ? (
                          <time className="nx-email-when-full" dateTime={full.iso}>
                            {full.label}
                          </time>
                        ) : null;
                      })()}
                    </div>
                  </div>
                </div>
                <button
                  type="button"
                  className="nx-email-star"
                  aria-pressed={reading.starred ? 'true' : 'false'}
                  aria-label={(reading.starred ? word('unstar') : word('star')).replace('{subject}', reading.subject)}
                  onClick={() => toggleStar(reading.id)}
                >
                  <Icon name="star" />
                </button>
              </header>
              <div className="nx-email-body">
                {(reading.body?.split('\n').filter((line) => line.trim()) ?? []).map((line, i) => (
                  <p key={i}>{line}</p>
                ))}
              </div>
            </div>
          ) : (
            <div className="nx-email-noselect">
              <p>{word('noMessage')}</p>
            </div>
          )}

          <form
            className="nx-email-composer"
            onSubmit={(event) => {
              event.preventDefault();
              send();
            }}
          >
            <span className="nx-email-reply">
              <Icon name="message" />
              {reading ? word('replyTo', { name: reading.from.name }) : word('messages')}
            </span>
            <div className="nx-email-compose">
              <textarea
                ref={area}
                className="nx-email-input"
                rows={1}
                aria-label={placeholder ?? word('placeholder')}
                placeholder={placeholder ?? word('placeholder')}
                disabled={disabled || !reading}
                value={draft}
                onChange={(event) => setDraft(event.target.value)}
                onKeyDown={onComposerKey}
              />
              <button
                type="submit"
                className="nx-email-send"
                data-ready={draft.trim() && reading ? '' : undefined}
                disabled={disabled || !draft.trim() || !reading}
                aria-label={t('send')}
              >
                <Icon name="arrow-right" />
              </button>
            </div>
          </form>
        </div>
      </div>
      {/* The reply, spoken once it leaves. */}
      <p className="nx-visually-hidden" role="status">
        {announced}
      </p>
    </section>
  );
}
