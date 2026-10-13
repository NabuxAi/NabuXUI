/**
 * The profile card block (React): a profile over a gradient cover — the
 * avatar rides the cover/body seam (with an optional presence dot), the
 * stats roll their digits, the follow button morphs between “follow” and
 * “following” (icon swap + label crossfade + accent→success recolour;
 * css/blocks/profile-card.css), the message button is a link or a plain
 * callback, and the small tabs pass a springing indicator over the chosen
 * section. `following` and `tab` are controlled (`value` + `onChange`) or
 * uncontrolled (`defaultValue`), with detail callbacks for each side.
 */
import {
  type KeyboardEvent,
  type HTMLAttributes,
  type ReactNode,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import type { MessageKey } from '@nabuxai/ui-core';
import { roveFocus } from '@nabuxai/ui-core';
import { cx, useControllable, useEvent, useIndicator, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { NumberTicker, useReveal } from '../components/text';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const idSafe = (value: string) => value.replace(/[^\w-]/g, '_');
const initials = (name: string) =>
  name
    .trim()
    .split(/\s+/)
    .slice(0, 2)
    .map((part) => Array.from(part)[0] ?? '')
    .join('')
    .toUpperCase();

export type ProfileStatus = 'online' | 'busy' | 'away';

/** The words the card says itself; override any of them with `labels`. */
type ProfileWord =
  | 'follow' | 'following' | 'nowFollowing' | 'unfollowed'
  | 'message' | 'verified' | 'sections';

/** Where each of them lives in the core i18n table. */
const WORD_KEYS: Record<ProfileWord, MessageKey> = {
  follow: 'profileFollow',
  following: 'profileFollowing',
  nowFollowing: 'profileNowFollowing',
  unfollowed: 'profileUnfollowed',
  message: 'profileMessage',
  verified: 'profileVerified',
  sections: 'profileSections',
};

export interface ProfileCardStat {
  /** What the figure counts — “Followers”, “Projects”… */
  label: ReactNode;
  value: number;
}

export interface ProfileCardTab {
  id: string;
  label: ReactNode;
  /** The open section's body; each tab owns one panel. */
  content?: ReactNode;
}

export interface ProfileCardProps extends Omit<HTMLAttributes<HTMLElement>, 'children' | 'role'> {
  name: ReactNode;
  /** The name becomes a link (through the app's router for internal paths). */
  href?: string;
  role?: ReactNode;
  /** The @handle, shown beside the role in the mono face. */
  handle?: string;
  avatar?: { src: string; alt?: string };
  /** Presence on the avatar's rim; absent hides the dot. */
  status?: ProfileStatus;
  /** The gold seal beside the name. */
  verified?: boolean;
  /** A photo over the gradient cover (the gradient is always there). */
  cover?: { src: string; alt?: string };
  stats?: ProfileCardStat[];
  /** The index into `stats` of the follower count; it rolls ±1 with the button. */
  followersStat?: number;
  following?: boolean;
  defaultFollowing?: boolean;
  onFollowingChange?: (following: boolean) => void;
  onFollow?: () => void;
  onUnfollow?: () => void;
  /** The message action becomes a link (through the app's router). */
  messageHref?: string;
  onMessage?: () => void;
  tabs?: ProfileCardTab[];
  tab?: string;
  defaultTab?: string;
  onTabChange?: (tab: string) => void;
  labels?: Partial<Record<ProfileWord, string>>;
  locale?: string;
  /** No motion feedback (press scale, hover lift) where it would distract. */
  static?: boolean;
}

export function ProfileCard({
  name,
  href,
  role,
  handle,
  avatar,
  status,
  verified,
  cover,
  stats = [],
  followersStat,
  following: controlledFollowing,
  defaultFollowing = false,
  onFollowingChange,
  onFollow,
  onUnfollow,
  messageHref,
  onMessage,
  tabs = [],
  tab: controlledTab,
  defaultTab,
  onTabChange,
  labels,
  locale,
  static: still,
  className,
  ...rest
}: ProfileCardProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  // `labels` may override any word; the rest come from the core table.
  const say = (key: ProfileWord, params: Record<string, string | number> = {}) => {
    const text = labels?.[key] ?? t(WORD_KEYS[key]);
    return text.replace(/\{(\w+)\}/g, (_, param: string) => String(params[param] ?? `{${param}}`));
  };
  const nameText = typeof name === 'string' ? name : undefined;
  const id = useId().replace(/:/g, '');
  const root = useRef<HTMLElement>(null);
  // The sections rise one after another (the cover, then the body).
  useReveal(root, { stagger: true });

  const [following, setFollowing] = useControllable(controlledFollowing, defaultFollowing, onFollowingChange);
  const [tab, setTab] = useControllable(controlledTab, defaultTab ?? tabs[0]?.id ?? '', onTabChange);
  const [failed, setFailed] = useState(false);
  const [announced, setAnnounced] = useState('');
  /** The mount-time following state — the follower count rolls relative to it. */
  const baseline = useRef(following);

  /* The small tabs: one springing indicator, roving keyboard focus. */
  const list = useRef<HTMLDivElement>(null);
  const ind = useIndicator(list);
  useIsoLayoutEffect(() => {
    ind.current?.update(list.current?.querySelector(`[data-value="${CSS.escape(tab)}"]`) ?? null);
  }, [tab, tabs, ind]);

  const onTabKey = useEvent((event: KeyboardEvent<HTMLDivElement>) => {
    const next = roveFocus(event.nativeEvent, event.currentTarget, '[role="tab"]');
    // Automatic activation: moving focus selects the tab.
    if (next?.dataset.value) setTab(next.dataset.value);
  });

  const format = useMemo(() => ({ maximumFractionDigits: 0 }) as Intl.NumberFormatOptions, []);
  /** The followers stat rides the button (±1 from its mount-time value) until the app's own number lands. */
  const followerDelta = following === baseline.current ? 0 : following ? 1 : -1;
  const valueOf = (stat: ProfileCardStat, i: number) =>
    i === followersStat ? stat.value + followerDelta : stat.value;

  // One press flips the follow state; both sides also report through callbacks.
  const toggle = useEvent(() => {
    const next = !following;
    setFollowing(next);
    if (next) onFollow?.();
    else onUnfollow?.();
    setAnnounced(next ? say('nowFollowing', { name: nameText ?? '' }) : say('unfollowed', { name: nameText ?? '' }));
  });

  const message = useEvent(() => onMessage?.());
  const open = (tabbed: ProfileCardTab) => tabbed.id === tab;

  return (
    <article
      ref={root}
      className={cx('nx-profile-card', className)}
      data-covered={cover ? '' : undefined}
      data-nx-reveal="group"
      aria-labelledby={`${id}-name`}
      {...rest}
    >
      <div className="nx-profile-card-cover">
        {cover && <img className="nx-profile-card-cover-img" src={cover.src} alt={cover.alt ?? ''} loading="lazy" />}
      </div>

      <div className="nx-profile-card-body">
        <div className="nx-profile-card-id">
          <span className="nx-profile-card-avatar" role="img" aria-label={nameText} data-status={status}>
            {avatar && !failed ? (
              <img className="nx-profile-card-avatar-img" src={avatar.src} alt="" onError={() => setFailed(true)} />
            ) : (
              <span aria-hidden="true">{nameText ? initials(nameText) : ''}</span>
            )}
          </span>
          <div className="nx-profile-card-id-text">
            <h3 className="nx-profile-card-name" id={`${id}-name`}>
              {href ? (
                <SmartLink href={href} className="nx-profile-card-link">
                  {name}
                </SmartLink>
              ) : (
                name
              )}
              {verified && (
                <span className="nx-profile-card-verified">
                  <Icon name="check-circle" />
                  <span className="nx-visually-hidden">{say('verified')}</span>
                </span>
              )}
            </h3>
            {(role || handle) && (
              <p className="nx-profile-card-role">
                {role && <span>{role}</span>}
                {role && handle && (
                  <span aria-hidden="true">
                    {' · '}
                  </span>
                )}
                {handle && <span className="nx-profile-card-handle">{handle}</span>}
              </p>
            )}
          </div>
        </div>

        <div className="nx-profile-card-actions">
          <button
            type="button"
            className="nx-profile-card-follow"
            data-following={following ? '' : undefined}
            data-static={still ? '' : undefined}
            onClick={toggle}
          >
            <span className="nx-profile-card-follow-icon" aria-hidden="true">
              <span data-part="idle">
                <Icon name="plus" />
              </span>
              <span data-part="done">
                <Icon name="check" />
              </span>
            </span>
            {/* Both labels live on one grid cell; the resting one is opacity-0,
                so it must also leave the accessibility tree. */}
            <span className="nx-profile-card-follow-label">
              <span data-part="idle" aria-hidden={following || undefined}>
                {say('follow')}
              </span>
              <span data-part="done" aria-hidden={!following || undefined}>
                {say('following')}
              </span>
            </span>
          </button>
          {messageHref ? (
            <SmartLink href={messageHref} className="nx-profile-card-message">
              <Icon name="message" />
              <span>{say('message')}</span>
            </SmartLink>
          ) : (
            <button type="button" className="nx-profile-card-message" data-static={still ? '' : undefined} onClick={message}>
              <Icon name="message" />
              <span>{say('message')}</span>
            </button>
          )}
        </div>

        {stats.length > 0 && (
          <dl className="nx-profile-card-stats">
            {stats.map((stat, i) => (
              <div key={i} className="nx-profile-card-stat" data-stat={i}>
                <dt>{stat.label}</dt>
                <dd>
                  <NumberTicker value={valueOf(stat, i)} locale={intl} format={format} />
                </dd>
              </div>
            ))}
          </dl>
        )}

        {tabs.length > 0 && (
          <>
            <div ref={list} className="nx-profile-card-tabs" role="tablist" aria-label={say('sections')} onKeyDown={onTabKey}>
              <span className="nx-indicator" aria-hidden="true" />
              {tabs.map((item) => {
                const selected = open(item);
                return (
                  <button
                    key={item.id}
                    type="button"
                    role="tab"
                    className="nx-profile-card-tab"
                    id={`${id}-tab-${idSafe(item.id)}`}
                    data-value={item.id}
                    data-static={still ? '' : undefined}
                    aria-selected={selected}
                    aria-controls={`${id}-panel-${idSafe(item.id)}`}
                    tabIndex={selected ? 0 : -1}
                    onClick={() => setTab(item.id)}
                  >
                    {item.label}
                  </button>
                );
              })}
            </div>
            {tabs.map((item) => (
              <div
                key={item.id}
                className="nx-profile-card-panel"
                role="tabpanel"
                id={`${id}-panel-${idSafe(item.id)}`}
                aria-labelledby={`${id}-tab-${idSafe(item.id)}`}
                tabIndex={0}
                hidden={!open(item)}
              >
                {item.content}
              </div>
            ))}
          </>
        )}
      </div>
      {/* The follow change, spoken once it lands. */}
      <p className="nx-visually-hidden" role="status">
        {announced}
      </p>
    </article>
  );
}
