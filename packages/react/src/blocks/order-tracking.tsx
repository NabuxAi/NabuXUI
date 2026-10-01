/**
 * Order tracking (React): where an order is, and how it got there — a
 * horizontal route of steps whose fill draws itself up to the current step
 * (a pinging marker while it travels), an event log beneath it stitched by
 * tone, and the live status on a StatusBadge
 * (css/blocks/order-tracking.css). Sections rise one after another, event
 * rows slide in from the reading side, and relative times drift while the
 * block is on screen. The Blade twin renders the same markup.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { type IconName, type MessageKey, activityTime, timeOf } from '@nabuxai/ui-core';
import { cx } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale, useT } from '../internal/provider';
import { useReveal } from '../components/text';
import { StatusBadge, type JobStatus } from './data';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** The machine-readable moment behind a shown time; undefined when `time` is a plain label. */
const isoOf = (time: Date | number | string): string | undefined => {
  const at = timeOf(time);
  return Number.isNaN(at) ? undefined : new Date(at).toISOString();
};

/** The words the block says itself; override any of them with `labels`. */
type OrderWord = 'tracking' | 'number' | 'carrier' | 'eta' | 'events' | 'noEvents';

/** Where each of them lives in the core i18n table. */
const WORD_KEYS: Record<OrderWord, MessageKey> = {
  tracking: 'orderTracking',
  number: 'orderNumber',
  carrier: 'orderCarrier',
  eta: 'orderEta',
  events: 'orderEvents',
  noEvents: 'orderNoEvents',
};

const NODE_ICON: Record<OrderEventTone, IconName> = {
  neutral: 'zap',
  success: 'check-circle',
  warning: 'alert-triangle',
  info: 'info',
  danger: 'alert-circle',
};

/* ---- Types ---------------------------------------------------------------------------- */

export type OrderStepState = 'complete' | 'current' | 'upcoming';
export type OrderEventTone = 'neutral' | 'success' | 'warning' | 'info' | 'danger';

export interface OrderStep {
  /** Keeps its DOM node across re-renders; the index by default. */
  id?: string;
  title: ReactNode;
  description?: ReactNode;
  /** Overrides the marker's default content (a check once complete, the index otherwise). */
  icon?: IconName;
  /** Wins over the position derived from `current`. */
  state?: OrderStepState;
  /** A Date, timestamp or ISO string (shown relative), or a label as it is. */
  time?: Date | number | string;
}

export interface OrderEvent {
  id?: string;
  title: ReactNode;
  description?: ReactNode;
  /** Where it happened ("تهران، مرکز توزیع"). */
  place?: ReactNode;
  time: Date | number | string;
  tone?: OrderEventTone;
  /** Overrides the tone's default node icon. */
  icon?: IconName;
}

export interface OrderTrackingProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  /** The order's number, shown large ("۱۴۰۴-۰۸۲۱۵"). */
  number?: ReactNode;
  /** The live status, on a StatusBadge: running | success | failed | queued | canceled. */
  status?: JobStatus;
  statusLabel?: string;
  /** Announce status changes to screen readers (default). */
  live?: boolean;
  carrier?: ReactNode;
  /** The promised delivery ("سه‌شنبه، ۱۰ مهر"). */
  eta?: ReactNode;
  steps: OrderStep[];
  /** The id or index of the current step (default: the first). */
  current?: number | string;
  /** The event log, newest first. */
  events?: OrderEvent[];
  /** The block's accessible name. */
  label?: string;
  labels?: Partial<Record<OrderWord, string>>;
  locale?: string;
  children?: undefined;
}

export function OrderTracking({
  number,
  status,
  statusLabel,
  live = true,
  carrier,
  eta,
  steps,
  current,
  events = [],
  label,
  labels,
  locale,
  className,
  ...rest
}: OrderTrackingProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  // `labels` may override any word; the rest come from the core table.
  const word = (key: OrderWord) => labels?.[key] ?? t(WORD_KEYS[key]);
  const id = useId();
  const root = useRef<HTMLElement>(null);
  const log = useRef<HTMLOListElement>(null);
  const route = useRef<HTMLOListElement>(null);
  const [now, setNow] = useState(() => Date.now());
  // The sections rise one after another; the event rows one at a time.
  useReveal(root, { stagger: true });
  useReveal(log, { stagger: true });

  // Relative times drift while the block is on screen ("۳ دقیقه پیش" → "۴ دقیقه پیش").
  useEffect(() => {
    const timer = setInterval(() => setNow(Date.now()), 30_000);
    return () => clearInterval(timer);
  }, []);

  // A long route scrolls; bring the current step into the frame.
  useEffect(() => {
    const row = route.current;
    const here = row?.querySelector<HTMLElement>('.nx-order-tracking-step[data-state="current"] .nx-order-tracking-marker');
    if (!row || !here || row.scrollWidth <= row.clientWidth + 1) return;
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    here.scrollIntoView({ block: 'nearest', inline: 'center', behavior: reduce ? 'auto' : 'smooth' });
  }, []);

  /** Where the route stands: an explicit state wins; otherwise everything
   *  before `current` is complete, everything after it upcoming. `current`
   *  names a step by id, or by its index ("2" after no id matches). */
  const currentIndex = useMemo(() => {
    if (typeof current === 'number') return Math.max(0, current);
    if (typeof current === 'string') {
      const byId = steps.findIndex((step) => step.id === current);
      if (byId >= 0) return byId;
      if (/^\d+$/.test(current)) return Math.max(0, Number(current));
    }
    return 0;
  }, [current, steps]);

  const digits = useMemo(() => new Intl.NumberFormat(intl), [intl]);
  const when = (time: Date | number | string) => activityTime(time, now, intl);

  return (
    <section
      ref={root}
      className={cx('nx-order-tracking', className)}
      data-nx-reveal="group"
      aria-label={label ?? word('tracking')}
      {...rest}
    >
      <header className="nx-order-tracking-head">
        <div className="nx-order-tracking-order">
          <span className="nx-order-tracking-kicker">{word('tracking')}</span>
          {number !== undefined && number !== null && number !== '' && (
            <p className="nx-order-tracking-number">
              <span className="nx-visually-hidden">{word('number')}: </span>
              {number}
            </p>
          )}
        </div>
        {(carrier || eta) && (
          <dl className="nx-order-tracking-facts">
            {carrier && (
              <div className="nx-order-tracking-fact">
                <dt>{word('carrier')}</dt>
                <dd>{carrier}</dd>
              </div>
            )}
            {eta && (
              <div className="nx-order-tracking-fact">
                <dt>{word('eta')}</dt>
                <dd>{eta}</dd>
              </div>
            )}
          </dl>
        )}
        {status && (
          <span className="nx-order-tracking-status">
            <StatusBadge status={status} label={statusLabel} live={live} />
          </span>
        )}
      </header>

      <ol ref={route} className="nx-order-tracking-steps">
        {steps.map((step, i) => {
          const state = step.state ?? (i < currentIndex ? 'complete' : i === currentIndex ? 'current' : 'upcoming');
          return (
            <li
              key={step.id ?? i}
              className="nx-order-tracking-step"
              data-state={state}
              style={vars({ '--nx-i': i })}
              aria-current={state === 'current' ? 'step' : undefined}
            >
              <span className="nx-order-tracking-marker" aria-hidden="true">
                {state === 'complete' && !step.icon ? (
                  <Icon name="check" />
                ) : step.icon ? (
                  <Icon name={step.icon} />
                ) : (
                  digits.format(i + 1)
                )}
              </span>
              <div className="nx-order-tracking-step-text">
                <p className="nx-order-tracking-step-title">{step.title}</p>
                {step.description && <p className="nx-order-tracking-step-desc">{step.description}</p>}
                {step.time !== undefined && step.time !== null && step.time !== '' && (
                  <time className="nx-order-tracking-step-time" dateTime={isoOf(step.time)}>
                    {when(step.time)}
                  </time>
                )}
              </div>
            </li>
          );
        })}
      </ol>

      <div className="nx-order-tracking-events">
        <h3 className="nx-order-tracking-events-title" id={`${id}-events`}>
          {word('events')}
        </h3>
        <ol ref={log} className="nx-order-tracking-events-list" data-nx-reveal="group" aria-labelledby={`${id}-events`}>
          {events.map((event, i) => {
            const tone = event.tone ?? 'neutral';
            return (
              <li
                key={event.id ?? i}
                className="nx-order-tracking-event"
                data-tone={tone === 'neutral' ? undefined : tone}
                style={vars({ '--nx-i': i })}
              >
                <span className="nx-order-tracking-event-node" aria-hidden="true">
                  <Icon name={event.icon ?? NODE_ICON[tone]} />
                </span>
                <div className="nx-order-tracking-event-body">
                  <p className="nx-order-tracking-event-title">{event.title}</p>
                  {event.description && <p className="nx-order-tracking-event-desc">{event.description}</p>}
                  {(event.time !== '' || event.place) && (
                    <p className="nx-order-tracking-event-meta">
                      <time className="nx-order-tracking-event-time" dateTime={isoOf(event.time)}>
                        {when(event.time)}
                      </time>
                      {event.place && (
                        <>
                          <span aria-hidden="true">·</span>
                          <span className="nx-order-tracking-event-place">{event.place}</span>
                        </>
                      )}
                    </p>
                  )}
                </div>
              </li>
            );
          })}
          {events.length === 0 && <li className="nx-order-tracking-empty">{word('noEvents')}</li>}
        </ol>
      </div>
    </section>
  );
}
