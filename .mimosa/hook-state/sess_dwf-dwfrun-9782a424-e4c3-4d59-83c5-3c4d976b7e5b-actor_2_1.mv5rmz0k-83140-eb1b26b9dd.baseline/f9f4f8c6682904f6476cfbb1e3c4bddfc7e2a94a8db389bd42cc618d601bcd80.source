/**
 * Landing-page sections (React): the pricing section with its interval toggle
 * and comparison grid, the single-open FAQ accordion, the in-view stats band,
 * the grayscale-to-colour logos row and the testimonial wall. Thin over the
 * core CSS; the state (interval, open item, marquee mode, quote index) lives
 * here and the counters roll in through NumberTicker once the band's reveal
 * group fires.
 *
 * The pricing block is `PricingSection` (and its classes `nx-pricing-section`)
 * because the catalogue already ships a compact `PricingTable` component over
 * the `nx-pricing` / `nx-plan` classes — this is its landing-page sibling.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type MouseEventHandler,
  type ReactNode,
  forwardRef,
  useId,
  useRef,
} from 'react';
import { type IconName, reveal } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { NumberTicker } from '../components/text';

/** The provider's language as an Intl locale, for the few numbers formatted here. */
const INTL_LOCALES = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

/* ---- Pricing section -------------------------------------------------------------- */

export type PricingInterval = 'monthly' | 'yearly';

export interface PricingSectionPlan {
  id: string;
  name: ReactNode;
  /** Who the plan is for, under the name. */
  tag?: ReactNode;
  /** The price for one month, billed monthly. */
  monthly: number;
  /** The price for one month, billed yearly. */
  yearly: number;
  /** The lines under the price; a leading tick icon is drawn for you. */
  perks?: ReactNode[];
  /** Stand the card out on ink with the accent action. */
  featured?: boolean;
  ctaLabel?: ReactNode;
  ctaHref?: string;
  onCtaClick?: MouseEventHandler;
}

/** A comparison cell: `true`/`false` draw the tick or the dash, a string prints itself. */
export type PricingValue = string | boolean;

export interface PricingComparisonRow {
  label: ReactNode;
  /** One value per plan, in the plans' order. */
  values: PricingValue[];
}

export interface PricingSectionProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  plans: PricingSectionPlan[];
  comparison?: PricingComparisonRow[];
  eyebrow?: ReactNode;
  title: ReactNode;
  subtitle?: ReactNode;
  interval?: PricingInterval;
  defaultInterval?: PricingInterval;
  onIntervalChange?: (interval: PricingInterval) => void;
  monthlyLabel?: ReactNode;
  yearlyLabel?: ReactNode;
  /** The note that rides inside the yearly half of the toggle. */
  savingsLabel?: ReactNode;
  /** The flag over the featured card. */
  featuredLabel?: ReactNode;
  /** Name of the billing-interval toggle for assistive tech. */
  toggleLabel?: string;
  /** Name of the comparison grid's scrollable region for assistive tech. */
  compareLabel?: string;
  /** The currency sign before each amount. */
  currency?: string;
  /** The quiet words after each amount, like “/ seat / mo”. */
  priceSuffix?: ReactNode;
  /** What a tick in the comparison grid means to a screen reader. */
  includedLabel?: ReactNode;
  /** What a dash in the comparison grid means to a screen reader. */
  notIncludedLabel?: ReactNode;
  compareTitle?: ReactNode;
  note?: ReactNode;
}

/** Three plans under a monthly/yearly toggle: the prices bump when the interval flips and a grid compares what each plan includes. */
export const PricingSection = forwardRef<HTMLElement, PricingSectionProps>(function PricingSection(
  {
    plans,
    comparison,
    eyebrow,
    title,
    subtitle,
    interval,
    defaultInterval = 'yearly',
    onIntervalChange,
    monthlyLabel = 'Monthly',
    yearlyLabel = 'Yearly',
    savingsLabel,
    featuredLabel = 'Most popular',
    currency = '$',
    priceSuffix,
    includedLabel = 'Included',
    notIncludedLabel = 'Not included',
    compareTitle,
    note,
    toggleLabel,
    compareLabel,
    className,
    ...rest
  },
  ref,
) {
  const [current, setCurrent] = useControllable(interval, defaultInterval, onIntervalChange);
  const yearly = current === 'yearly';
  const priceLocale = INTL_LOCALES[useLocale()] ?? 'en-US';

  return (
    <section ref={ref} className={cx('nx-pricing-section', className)} {...rest}>
      <header className="nx-pricing-section-head">
        {eyebrow != null && eyebrow !== false && <p className="nx-pricing-section-eyebrow">{eyebrow}</p>}
        <h3 className="nx-pricing-section-title">{title}</h3>
        {subtitle && <p className="nx-pricing-section-subtitle">{subtitle}</p>}
      </header>

      <div className="nx-pricing-section-toggle" data-interval={current} role="group" aria-label={toggleLabel}>
        <span className="nx-pricing-section-toggle-thumb" aria-hidden="true" />
        <button type="button" className="nx-pricing-section-toggle-button" aria-pressed={!yearly} onClick={() => setCurrent('monthly')}>
          {monthlyLabel}
        </button>
        <button type="button" className="nx-pricing-section-toggle-button" aria-pressed={yearly} onClick={() => setCurrent('yearly')}>
          {yearlyLabel}
          {savingsLabel && (
            <em className="nx-pricing-section-save" aria-hidden="true">
              {savingsLabel}
            </em>
          )}
        </button>
      </div>

      <div className="nx-pricing-section-cards">
        {plans.map((plan) => {
          const amount = yearly ? plan.yearly : plan.monthly;
          const cta =
            plan.ctaLabel == null || plan.ctaLabel === false ? null : plan.ctaHref ? (
              <SmartLink className="nx-pricing-section-cta" href={plan.ctaHref} onClick={plan.onCtaClick}>
                {plan.ctaLabel}
              </SmartLink>
            ) : (
              <button type="button" className="nx-pricing-section-cta" onClick={plan.onCtaClick}>
                {plan.ctaLabel}
              </button>
            );
          return (
            <article key={plan.id} className="nx-pricing-section-card" data-featured={plan.featured ? '' : undefined}>
              {plan.featured && featuredLabel != null && <span className="nx-pricing-section-badge">{featuredLabel}</span>}
              <h4 className="nx-pricing-section-name">
                {plan.name}
                {plan.tag && <span className="nx-pricing-section-tag">{plan.tag}</span>}
              </h4>
              <p className="nx-pricing-section-price">
                {/* Keyed by the interval: the new amount enters through its starting style. */}
                <span key={current} className="nx-pricing-section-amount">
                  {currency}
                  {amount.toLocaleString(priceLocale)}
                </span>
                {priceSuffix != null && priceSuffix !== false && <small className="nx-pricing-section-suffix">{priceSuffix}</small>}
              </p>
              {plan.perks && plan.perks.length > 0 && (
                <ul className="nx-pricing-section-perks">
                  {plan.perks.map((perk, i) => (
                    <li key={`${plan.id}-${i}`}>
                      <Icon name="check" />
                      <span>{perk}</span>
                    </li>
                  ))}
                </ul>
              )}
              {cta}
            </article>
          );
        })}
      </div>

      {comparison && comparison.length > 0 && plans.length > 0 && (
        <div className="nx-pricing-section-compare">
          {compareTitle && <h4 className="nx-pricing-section-compare-title">{compareTitle}</h4>}
          <div className="nx-pricing-section-table-wrap" tabIndex={0} role="group" aria-label={compareLabel}>
            <table className="nx-pricing-section-table">
              <thead>
                <tr>
                  <th scope="col" />
                  {plans.map((plan) => (
                    <th key={plan.id} scope="col" data-featured={plan.featured ? '' : undefined}>
                      {plan.name}
                    </th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {comparison.map((row, i) => (
                  <tr key={`${i}-${String(row.label)}`}>
                    <th scope="row">{row.label}</th>
                    {plans.map((plan, column) => {
                      const value = row.values[column];
                      return (
                        <td key={plan.id} data-featured={plan.featured ? '' : undefined}>
                          {value === true ? (
                            <>
                              <Icon name="check" data-included="" />
                              <span className="nx-visually-hidden">{includedLabel}</span>
                            </>
                          ) : value === false ? (
                            <>
                              <Icon name="minus" data-missing="" />
                              <span className="nx-visually-hidden">{notIncludedLabel}</span>
                            </>
                          ) : (
                            value
                          )}
                        </td>
                      );
                    })}
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          {note && <p className="nx-pricing-section-note">{note}</p>}
        </div>
      )}
    </section>
  );
});

/* ---- FAQ section ------------------------------------------------------------------ */

export interface FaqItem {
  id: string;
  question: ReactNode;
  answer: ReactNode;
}

export interface FaqSectionProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  items: FaqItem[];
  eyebrow?: ReactNode;
  title: ReactNode;
  intro?: ReactNode;
  /** The open item's id, or null when all are closed. */
  open?: string | null;
  defaultOpen?: string | null;
  onOpenChange?: (open: string | null) => void;
  ctaLabel?: ReactNode;
  ctaHref?: string;
  onCtaClick?: MouseEventHandler;
  moreLabel?: ReactNode;
  moreHref?: string;
  note?: ReactNode;
}

/** A side column with the heading and the talk-to-us actions, beside a single-open accordion whose answers unfurl through a 0fr→1fr grid row. */
export const FaqSection = forwardRef<HTMLElement, FaqSectionProps>(function FaqSection(
  { items, eyebrow, title, intro, open, defaultOpen = null, onOpenChange, ctaLabel, ctaHref, onCtaClick, moreLabel, moreHref, note, className, ...rest },
  ref,
) {
  const [current, setCurrent] = useControllable(open, defaultOpen, onOpenChange);
  const base = useId().replace(/:/g, '');

  const cta =
    ctaLabel == null || ctaLabel === false ? null : ctaHref ? (
      <SmartLink className="nx-faq-cta" href={ctaHref} onClick={onCtaClick}>
        <Icon name="mail" />
        {ctaLabel}
      </SmartLink>
    ) : (
      <button type="button" className="nx-faq-cta" onClick={onCtaClick}>
        <Icon name="mail" />
        {ctaLabel}
      </button>
    );

  return (
    <section ref={ref} className={cx('nx-faq', className)} {...rest}>
      <div className="nx-faq-side">
        {eyebrow != null && eyebrow !== false && <p className="nx-faq-eyebrow">{eyebrow}</p>}
        <h3 className="nx-faq-title">{title}</h3>
        {intro && <p className="nx-faq-intro">{intro}</p>}
        {cta}
        {moreLabel && moreHref && (
          <SmartLink className="nx-faq-more" href={moreHref}>
            {moreLabel}
            <Icon name="arrow-right" />
          </SmartLink>
        )}
      </div>

      <div className="nx-faq-list">
        {items.map((item) => {
          const isOpen = current === item.id;
          return (
            <div key={item.id} className="nx-faq-item" data-open={isOpen ? '' : undefined}>
              <h4 className="nx-faq-heading">
                <button
                  type="button"
                  id={`nx-faq-q-${base}-${item.id}`}
                  className="nx-faq-q"
                  aria-expanded={isOpen}
                  aria-controls={`nx-faq-a-${base}-${item.id}`}
                  onClick={() => setCurrent(isOpen ? null : item.id)}
                >
                  <span>{item.question}</span>
                  <i className="nx-faq-plus" aria-hidden="true" />
                </button>
              </h4>
              <div className="nx-faq-a" id={`nx-faq-a-${base}-${item.id}`} role="region" aria-labelledby={`nx-faq-q-${base}-${item.id}`}>
                <div>{item.answer}</div>
              </div>
            </div>
          );
        })}
        {note && <p className="nx-faq-note">{note}</p>}
      </div>
    </section>
  );
});

/* ---- Stats band --------------------------------------------------------------------- */

export interface StatsBandStat {
  /** The figure; it rolls up from zero when the band scrolls into view. */
  value: number;
  label: ReactNode;
  icon?: IconName;
  /** The small mark after the figure: %, +, a unit… */
  unit?: ReactNode;
  /** Intl options, e.g. `{ minimumFractionDigits: 2 }` for an uptime figure. */
  format?: Intl.NumberFormatOptions;
}

export interface StatsBandProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  stats: StatsBandStat[];
  eyebrow?: ReactNode;
  title: ReactNode;
  note?: ReactNode;
}

/** A dark band where the counters roll in — in the provider language's digits — the first time the band scrolls into view. */
export const StatsBand = forwardRef<HTMLElement, StatsBandProps>(function StatsBand(
  { stats, eyebrow, title, note, className, ...rest },
  ref,
) {
  const root = useRef<HTMLElement>(null);
  // The band reveals as a group: each stat rises one after the other, and every
  // NumberTicker inside rolls its own figure at the same time.
  useBehavior(root, reveal, { stagger: true, once: true, threshold: 0.25 });

  return (
    <section ref={mergeRefs(ref, root)} className={cx('nx-stats-band', className)} data-nx-reveal="group" {...rest}>
      <header className="nx-stats-band-head">
        {eyebrow != null && eyebrow !== false && <p className="nx-stats-band-eyebrow">{eyebrow}</p>}
        <h3 className="nx-stats-band-title">{title}</h3>
      </header>

      <dl className="nx-stats-band-grid">
        {stats.map((stat, i) => (
          <div key={`${i}-${String(stat.value)}`} className="nx-stats-band-stat" style={{ '--nx-i': i } as CSSProperties}>
            {stat.icon && (
              <span className="nx-stats-band-icon" aria-hidden="true">
                <Icon name={stat.icon} />
              </span>
            )}
            <dd className="nx-stats-band-value">
              <NumberTicker value={stat.value} format={stat.format} />
              {stat.unit != null && stat.unit !== false && (
                <small className="nx-stats-band-unit" aria-hidden="true">
                  {stat.unit}
                </small>
              )}
            </dd>
            <dt className="nx-stats-band-label">{stat.label}</dt>
          </div>
        ))}
      </dl>

      {note && <p className="nx-stats-band-note">{note}</p>}
    </section>
  );
});

/* ---- Logos trust ---------------------------------------------------------------------- */

export type LogoMark = 'ring' | 'orbit' | 'enso' | 'peak' | 'sun' | 'north' | 'wave' | 'blocks';
export type LogoTone = 'lapis' | 'violet' | 'cyan' | 'gold' | 'ink' | 'rose' | 'green';

export interface TrustLogo {
  id: string;
  /** The brand's name — it sets the mark's accessible label when the logo links somewhere. */
  name: string;
  mark?: LogoMark;
  tone?: LogoTone;
  href?: string;
}

const LOGO_BRAND: Record<LogoTone, string> = {
  lapis: 'var(--nx-lapis-600)',
  violet: 'var(--nx-violet-500)',
  cyan: 'var(--nx-cyan-500)',
  gold: 'var(--nx-gold-500)',
  ink: 'var(--nx-text)',
  rose: 'var(--nx-chart-2)',
  green: 'var(--nx-chart-7)',
};

/** The geometric marks, drawn from scratch — one glyph per fictional brand. */
function LogoGlyph({ mark }: { mark: LogoMark }) {
  switch (mark) {
    case 'ring':
      return <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" strokeWidth="2.4" />;
    case 'orbit':
      return (
        <>
          <circle cx="12" cy="12" r="4" fill="currentColor" />
          <ellipse cx="12" cy="12" rx="10" ry="4.5" fill="none" stroke="currentColor" strokeWidth="1.8" />
        </>
      );
    case 'enso':
      return <path d="M17.5 7A8 8 0 1 0 19 13.5" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" />;
    case 'peak':
      return <path d="M3 19L10 5l4 7 3-4 4 11z" fill="currentColor" />;
    case 'sun':
      return (
        <>
          <circle cx="12" cy="12" r="4.5" fill="currentColor" />
          <path d="M12 2.5v3M12 18.5v3M2.5 12h3M18.5 12h3" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
        </>
      );
    case 'north':
      return <path d="M12 3l7 18-7-4-7 4z" fill="currentColor" />;
    case 'wave':
      return <path d="M2 14c2.5-6 5-6 7.5 0s5 6 7.5 0 5-6 5-6" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" />;
    case 'blocks':
      return (
        <>
          <rect x="3" y="3" width="8" height="8" fill="currentColor" />
          <rect x="13" y="13" width="8" height="8" fill="currentColor" />
        </>
      );
  }
}

export interface LogosTrustProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  logos: TrustLogo[];
  eyebrow?: ReactNode;
  title: ReactNode;
  mode?: 'grid' | 'marquee';
  defaultMode?: 'grid' | 'marquee';
  onModeChange?: (mode: 'grid' | 'marquee') => void;
  gridLabel?: ReactNode;
  marqueeLabel?: ReactNode;
  /** Name of the display-mode switch for assistive tech. */
  switchLabel?: string;
  footnote?: ReactNode;
}

/** Brand marks resting in grayscale that take their colour back on hover; the same row can run as a slow marquee that holds under the pointer. */
export const LogosTrust = forwardRef<HTMLElement, LogosTrustProps>(function LogosTrust(
  {
    logos,
    eyebrow,
    title,
    mode,
    defaultMode = 'grid',
    onModeChange,
    gridLabel = 'Grid',
    marqueeLabel = 'Slow scroll',
    switchLabel,
    footnote,
    className,
    ...rest
  },
  ref,
) {
  const [current, setCurrent] = useControllable(mode, defaultMode, onModeChange);
  const marquee = current === 'marquee';

  const item = (logo: TrustLogo, ghost: boolean) => {
    const mark = logo.mark ?? 'ring';
    const glyph = (
      <>
        <svg className="nx-logos-mark" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <LogoGlyph mark={mark} />
        </svg>
        <span dir="ltr">{logo.name}</span>
      </>
    );
    const style = { '--_brand': LOGO_BRAND[logo.tone ?? 'ink'] } as CSSProperties;
    return logo.href && !ghost ? (
      <li key={`${logo.id}-${ghost}`}>
        <SmartLink className="nx-logos-logo" href={logo.href} style={style} aria-label={logo.name}>
          {glyph}
        </SmartLink>
      </li>
    ) : (
      <li key={`${logo.id}-${ghost}`} className="nx-logos-logo" style={style} aria-hidden={ghost || undefined}>
        {glyph}
      </li>
    );
  };

  return (
    <section ref={ref} className={cx('nx-logos', className)} {...rest}>
      <header className="nx-logos-head">
        {eyebrow != null && eyebrow !== false && <p className="nx-logos-eyebrow">{eyebrow}</p>}
        <h3 className="nx-logos-title">{title}</h3>
      </header>

      <div className="nx-logos-switch" role="group" aria-label={switchLabel}>
        <button type="button" className="nx-logos-switch-button" aria-pressed={!marquee} onClick={() => setCurrent('grid')}>
          {gridLabel}
        </button>
        <button type="button" className="nx-logos-switch-button" aria-pressed={marquee} onClick={() => setCurrent('marquee')}>
          {marqueeLabel}
        </button>
      </div>

      {marquee ? (
        /* The row runs left-to-right whatever the page reads; the ghosts repeat it so the loop never shows a seam. */
        <div className="nx-logos-marquee" dir="ltr">
          <ul className="nx-logos-track">
            {logos.map((logo) => item(logo, false))}
            {logos.map((logo) => item(logo, true))}
          </ul>
        </div>
      ) : (
        <ul className="nx-logos-grid">{logos.map((logo) => item(logo, false))}</ul>
      )}

      {footnote && <p className="nx-logos-footnote">{footnote}</p>}
    </section>
  );
});

/* ---- Testimonial wall -------------------------------------------------------------------- */

export type AvatarTone = 'gold' | 'lapis' | 'violet' | 'cyan' | 'moss' | 'clay';

const AVATAR_TONE: Record<AvatarTone, string> = {
  gold: 'var(--nx-gold-600)',
  lapis: 'var(--nx-lapis-600)',
  violet: 'var(--nx-violet-600)',
  cyan: 'var(--nx-cyan-600)',
  moss: 'var(--nx-success)',
  clay: 'var(--nx-warning)',
};

export interface TestimonialQuote {
  id: string;
  text: ReactNode;
  name: ReactNode;
  role?: ReactNode;
  /** The initials in the avatar circle; the first letters of the name when omitted. */
  initials?: string;
  tone?: AvatarTone;
}

export interface TestimonialShort {
  text: ReactNode;
  name: ReactNode;
  co?: ReactNode;
  /** 1–5 stars over the quote. */
  stars?: number;
  initials?: string;
  tone?: AvatarTone;
}

export interface TestimonialWallProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  quotes: TestimonialQuote[];
  shorts?: TestimonialShort[];
  /** The review-score chip above the heading, like “4.9 / 5 across 2,400+ reviews”. */
  score?: ReactNode;
  title: ReactNode;
  subtitle?: ReactNode;
  index?: number;
  defaultIndex?: number;
  onIndexChange?: (index: number) => void;
}

const initialsOf = (name: ReactNode, explicit?: string) =>
  explicit ?? (typeof name === 'string' ? name.split(/\s+/).slice(0, 2).map((word) => word.charAt(0)).join('') : undefined);

/** One big quote at a time — buttons, dots or the arrow keys — over a grid of short star-rated quotes. */
export const TestimonialWall = forwardRef<HTMLElement, TestimonialWallProps>(function TestimonialWall(
  { quotes, shorts = [], score, title, subtitle, index, defaultIndex = 0, onIndexChange, className, onKeyDown, ...rest },
  ref,
) {
  const t = useT();
  const [current, setCurrent] = useControllable(index, defaultIndex, onIndexChange);
  const count = quotes.length;
  const step = (delta: number) => {
    if (count > 0) setCurrent((current + delta + count) % count);
  };

  return (
    <section
      ref={ref}
      className={cx('nx-testimonials', className)}
      onKeyDown={(event) => {
        onKeyDown?.(event);
        if (event.key === 'ArrowRight') {
          event.preventDefault();
          step(1);
        } else if (event.key === 'ArrowLeft') {
          event.preventDefault();
          step(-1);
        }
      }}
      {...rest}
    >
      <header className="nx-testimonials-head">
        {score != null && score !== false && (
          <span className="nx-testimonials-score">
            <Icon name="star" />
            {score}
          </span>
        )}
        <h3 className="nx-testimonials-title">{title}</h3>
        {subtitle && <p className="nx-testimonials-subtitle">{subtitle}</p>}
      </header>

      <div className="nx-testimonials-stage" role="group" aria-live="polite">
        {quotes.map((quote, i) => (
          <blockquote key={quote.id} className="nx-testimonials-quote" data-off={i !== current ? '' : undefined} aria-hidden={i !== current || undefined}>
            <svg className="nx-testimonials-mark" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M9.5 6C6.5 7.5 5 9.8 5 13v5h5v-6H7.6c.2-1.8 1.1-3.1 2.7-4L9.5 6zm9 0c-3 1.5-4.5 3.8-4.5 7v5h5v-6h-2.4c.2-1.8 1.1-3.1 2.7-4L18.5 6z" />
            </svg>
            <p className="nx-testimonials-text">{quote.text}</p>
            <footer className="nx-testimonials-who">
              <span className="nx-testimonials-avatar" aria-hidden="true" style={{ '--_avatar': AVATAR_TONE[quote.tone ?? 'lapis'] } as CSSProperties}>
                {initialsOf(quote.name, quote.initials)}
              </span>
              <span>
                <span className="nx-testimonials-name">{quote.name}</span>
                {quote.role && <span className="nx-testimonials-role">{quote.role}</span>}
              </span>
            </footer>
          </blockquote>
        ))}
      </div>

      {count > 1 && (
        <div className="nx-testimonials-nav">
          <button type="button" className="nx-testimonials-arrow" aria-label={t('previous')} onClick={() => step(-1)}>
            <Icon name="chevron-left" />
          </button>
          <div className="nx-testimonials-dots">
            {quotes.map((quote, i) => (
              <button
                key={quote.id}
                type="button"
                className="nx-testimonials-dot"
                aria-current={i === current ? 'true' : undefined}
                aria-label={typeof quote.name === 'string' ? quote.name : `${i + 1}`}
                onClick={() => setCurrent(i)}
              />
            ))}
          </div>
          <button type="button" className="nx-testimonials-arrow" aria-label={t('next')} onClick={() => step(1)}>
            <Icon name="chevron-right" />
          </button>
        </div>
      )}

      {shorts.length > 0 && (
        <div className="nx-testimonials-shorts">
          {shorts.map((short, i) => {
            const stars = Math.max(0, Math.min(5, Math.round(short.stars ?? 5)));
            return (
              <figure key={`${i}-${String(short.name)}`} className="nx-testimonials-short">
                <span className="nx-testimonials-stars" role="img" aria-label={t('stars', { count: stars, total: 5 })}>
                  {Array.from({ length: 5 }, (_, star) => (
                    <Icon key={star} name="star" data-on={star < stars ? '' : undefined} />
                  ))}
                </span>
                <blockquote>{short.text}</blockquote>
                <figcaption>
                  <span className="nx-testimonials-mini" aria-hidden="true" style={{ '--_avatar': AVATAR_TONE[short.tone ?? 'lapis'] } as CSSProperties}>
                    {initialsOf(short.name, short.initials)}
                  </span>
                  <span>
                    <b className="nx-testimonials-name">{short.name}</b>
                    {short.co && <> · {short.co}</>}
                  </span>
                </figcaption>
              </figure>
            );
          })}
        </div>
      )}
    </section>
  );
});
