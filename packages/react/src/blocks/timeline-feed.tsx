/**
 * Timeline feed (React): a vertical activity stream. Rows of icon + text +
 * relative time reveal one after another (core `reveal` group stagger), stitched
 * together by a gradient connector whose colour follows each row's tone. Thin
 * over css/blocks/timeline-feed.css — the Blade twin renders the same markup.
 */
import { type CSSProperties, type HTMLAttributes, type ReactNode, useEffect, useMemo, useRef, useState } from 'react';
import { type IconName, activityTime, reveal, timeOf } from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale } from '../internal/provider';

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** The machine-readable moment behind a shown time; undefined when `time` is a plain label. */
const isoOf = (time: Date | number | string): string | undefined => {
  const at = timeOf(time);
  return Number.isNaN(at) ? undefined : new Date(at).toISOString();
};

export type TimelineTone = 'neutral' | 'success' | 'warning' | 'info' | 'danger';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

const NODE_ICON: Record<TimelineTone, IconName> = {
  neutral: 'zap',
  success: 'check-circle',
  warning: 'alert-triangle',
  info: 'info',
  danger: 'alert-circle',
};

export interface TimelineEntry {
  id: string;
  /** What happened, between the actor and the target. */
  text: ReactNode;
  /** Who did it, shown in bold first ("مریم رضایی"). */
  actor?: ReactNode;
  /** What it happened to, shown in bold after the text. */
  target?: ReactNode;
  /** A link for the target. */
  href?: string;
  /** A Date, timestamp or ISO string (shown relative), or a label as it is. */
  time: Date | number | string;
  tone?: TimelineTone;
  /** Overrides the tone's default node icon. */
  icon?: IconName;
}

export interface TimelineFeedProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  entries: TimelineEntry[];
  /** Accessible name of the stream ("جریان فعالیت"). */
  label?: string;
  /** An Intl locale for the relative times (defaults to the provider's language). */
  locale?: string;
  children?: undefined;
}

export function TimelineFeed({ entries, label, locale, className, ...rest }: TimelineFeedProps) {
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const list = useRef<HTMLOListElement>(null);
  const [now, setNow] = useState(() => Date.now());
  useBehavior(list, reveal, { once: true, stagger: true });

  // Relative times drift while the feed is on screen ("۳ دقیقه پیش" → "۴ دقیقه پیش").
  useEffect(() => {
    const timer = setInterval(() => setNow(Date.now()), 30_000);
    return () => clearInterval(timer);
  }, []);

  const rows = useMemo(() => entries.map((entry, i) => ({ entry, i, when: activityTime(entry.time, now, intl) })), [entries, now, intl]);

  return (
    <div className={cx('nx-timeline-feed', className)} {...rest}>
      <ol ref={list} className="nx-timeline-feed-list" data-nx-reveal="group" aria-label={label}>
        {rows.map(({ entry, i, when }) => {
          const tone = entry.tone ?? 'neutral';
          const target =
            entry.target === undefined ? null : entry.href ? (
              <SmartLink href={entry.href}>
                <strong>{entry.target}</strong>
              </SmartLink>
            ) : (
              <strong>{entry.target}</strong>
            );
          return (
            <li key={entry.id} className="nx-timeline-feed-item" data-tone={tone === 'neutral' ? undefined : tone} style={vars({ '--nx-i': i })}>
              <span className="nx-timeline-feed-node" aria-hidden="true">
                <Icon name={entry.icon ?? NODE_ICON[tone]} />
              </span>
              <div className="nx-timeline-feed-body">
                <p className="nx-timeline-feed-text">
                  {entry.actor && <strong>{entry.actor}</strong>} {entry.text} {target}
                </p>
                <time className="nx-timeline-feed-time" dateTime={isoOf(entry.time)}>{when}</time>
              </div>
            </li>
          );
        })}
      </ol>
    </div>
  );
}
