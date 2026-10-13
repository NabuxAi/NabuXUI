/**
 * Hero blocks (React): the spotlight hero, the split hero and the marquee
 * hero — full first screens distilled from the playground demos. Thin over
 * css/blocks/heroes.css and the core pointer behaviours: spotlight() drives
 * the ink stage's halo, tilt() the mockup and its chips, and the marquee
 * belt is pure CSS, so the family ships no behaviour of its own.
 *
 * The heroes say nothing themselves: every word (title, subtitle, button
 * labels, chip and trust copy, logo names) arrives through props, so the
 * app translates them. `<em>` inside a title picks up the hero's gradient.
 */
import { type CSSProperties, type HTMLAttributes, type ReactNode, useRef } from 'react';
import { type IconName, spotlight, tilt } from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { Button, type ButtonVariant } from '../components/button';
import { Badge } from '../components/feedback';

/* ---- Shared: the call to action -------------------------------------------------- */

export interface HeroCta {
  /** The label; translate it in the app — the heroes say nothing themselves. */
  label: ReactNode;
  /** Render a link (through the app's router for internal paths). */
  href?: string;
  icon?: IconName;
  iconEnd?: IconName;
  /** Button variant; each hero picks one that suits its surface by default. */
  variant?: ButtonVariant;
  onClick?: () => void;
}

function CtaButton({ cta, variant }: { cta: HeroCta; variant: ButtonVariant }) {
  return (
    <Button variant={cta.variant ?? variant} href={cta.href} icon={cta.icon} iconEnd={cta.iconEnd} size="lg" shape="pill" onClick={cta.onClick}>
      {cta.label}
    </Button>
  );
}

/** The badge pill that opens a hero, with its quiet leading dot. */
function HeroBadge({ children }: { children: ReactNode }) {
  return (
    <Badge tone="neutral" dot>
      {children}
    </Badge>
  );
}

/* ---- Spotlight hero ---------------------------------------------------------------- */

export interface HeroSpotlightTrustItem {
  icon?: IconName;
  label: ReactNode;
}

export interface HeroSpotlightProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** The headline; `<em>` picks up the light gradient. */
  title: ReactNode;
  subtitle?: ReactNode;
  badge?: ReactNode;
  primary?: HeroCta;
  secondary?: HeroCta;
  /** The quiet proof row under the buttons. */
  trust?: HeroSpotlightTrustItem[];
  /** The heading element: h1 at the top of a page, h2 further down. */
  as?: 'h1' | 'h2';
}

/**
 * An ink-dark stage whose halo of light travels with the pointer over a calm
 * mesh of drifting blobs: badge, display title, two CTAs and a trust row. The
 * light is direct manipulation — it follows the hand (or finger) live, and
 * only the mesh behind it freezes under reduced motion.
 */
export function HeroSpotlight({ title, subtitle, badge, primary, secondary, trust = [], as: Tag = 'h1', className, ...rest }: HeroSpotlightProps) {
  const ref = useRef<HTMLElement>(null);
  useBehavior(ref, spotlight, {});

  return (
    <section ref={ref} className={cx('nx-hero-spotlight', className)} {...rest}>
      <div className="nx-hero-spotlight-blobs" aria-hidden="true">
        <i />
        <i />
        <i />
      </div>
      <div className="nx-hero-spotlight-light" aria-hidden="true" />
      <div className="nx-hero-spotlight-copy">
        {badge && <p className="nx-hero-spotlight-badge">{badge}</p>}
        <Tag className="nx-hero-spotlight-title">{title}</Tag>
        {subtitle && <p className="nx-hero-spotlight-subtitle">{subtitle}</p>}
        {(primary || secondary) && (
          <div className="nx-hero-spotlight-actions">
            {primary && <CtaButton cta={primary} variant="primary" />}
            {secondary && <CtaButton cta={secondary} variant="inverse" />}
          </div>
        )}
        {trust.length > 0 && (
          <ul className="nx-hero-spotlight-trust">
            {trust.map((item, i) => (
              <li key={i}>
                {item.icon && <Icon name={item.icon} />}
                {item.label}
              </li>
            ))}
          </ul>
        )}
      </div>
    </section>
  );
}

/* ---- Split hero ----------------------------------------------------------------------- */

export interface HeroSplitChip {
  icon?: IconName;
  label: ReactNode;
}

export interface HeroSplitProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  title: ReactNode;
  subtitle?: ReactNode;
  badge?: ReactNode;
  primary?: HeroCta;
  secondary?: HeroCta;
  /** The quiet line under the buttons (trial terms, pricing…). */
  note?: ReactNode;
  /** Capability chips floating over the mockup. */
  chips?: HeroSplitChip[];
  /** Replace the built-in decorative mockup with your own product shot. */
  mock?: ReactNode;
  as?: 'h1' | 'h2';
}

/**
 * The wordless console sketch the hero ships with: status dots, skeleton
 * lines and a throughput chart — a product without saying anything in any
 * language. Purely decorative, so it stays aria-hidden.
 */
function HeroSplitMock() {
  const bars = [38, 62, 47, 78, 56, 88, 70, 94, 64, 82];
  return (
    <>
      <span className="nx-hero-split-bar">
        <i />
        <i />
        <i />
        <b />
      </span>
      <div className="nx-hero-split-body">
        <div className="nx-hero-split-nav">
          <i />
          <i />
          <i />
          <i />
          <i />
        </div>
        <div className="nx-hero-split-main">
          {[false, true, false].map((run, i) => (
            <div className="nx-hero-split-row" key={i}>
              <i className="nx-hero-split-dot" data-run={run ? '' : undefined} />
              <i className="nx-hero-split-line" />
              <i className="nx-hero-split-state" data-run={run ? '' : undefined} />
            </div>
          ))}
          <div className="nx-hero-split-bars">
            {bars.map((height, i) => (
              <i key={i} style={{ blockSize: `${height}%`, '--nx-i': i } as CSSProperties} />
            ))}
          </div>
        </div>
      </div>
    </>
  );
}

/**
 * Copy on the start side, and on the other a tilted product mockup wrapped in
 * a soft halo with capability chips floating over it: the product hands you
 * its angle before you ever scroll. The mockup and chips turn with the
 * pointer (on devices that can hover) and stand still everywhere else.
 */
export function HeroSplit({ title, subtitle, badge, primary, secondary, note, chips = [], mock, as: Tag = 'h1', className, ...rest }: HeroSplitProps) {
  const stage = useRef<HTMLDivElement>(null);
  useBehavior(stage, tilt, { max: 8 });

  return (
    <section className={cx('nx-hero-split', className)} {...rest}>
      <div className="nx-hero-split-copy">
        {badge && (
          <p className="nx-hero-split-badge">
            <HeroBadge>{badge}</HeroBadge>
          </p>
        )}
        <Tag className="nx-hero-split-title">{title}</Tag>
        {subtitle && <p className="nx-hero-split-subtitle">{subtitle}</p>}
        {(primary || secondary) && (
          <div className="nx-hero-split-actions">
            {primary && <CtaButton cta={primary} variant="primary" />}
            {secondary && <CtaButton cta={secondary} variant="secondary" />}
          </div>
        )}
        {note && <p className="nx-hero-split-note">{note}</p>}
      </div>
      <div ref={stage} className="nx-hero-split-stage">
        <i className="nx-hero-split-halo" aria-hidden="true" />
        <div className="nx-hero-split-mock" aria-hidden={mock ? undefined : true}>
          {mock ?? <HeroSplitMock />}
        </div>
        {chips.length > 0 && (
          <ul className="nx-hero-split-chips">
            {chips.map((chip, i) => (
              <li key={i} className="nx-hero-split-chip">
                {chip.icon && <Icon name={chip.icon} />}
                {chip.label}
              </li>
            ))}
          </ul>
        )}
      </div>
    </section>
  );
}

/* ---- Marquee hero ------------------------------------------------------------------------ */

export interface HeroMarqueeLogo {
  /** The wordmark, as plain text. */
  name: string;
  /** A 24×24 stroke path for the mark; a small geometric set fills in when missing. */
  path?: string;
}

/** Simple geometric marks so plain names still read as logos. */
const LOGO_MARKS = [
  'M12 3l8 9-8 9-8-9 8-9Z',
  'M4 19 12 5l8 14H4Z',
  'M2 14c3-5 5 5 8 0s5 5 8 0',
  'M12 2l9 5v10l-9 5-9-5V7l9-5Z',
  'M20 12a8 8 0 1 1-6-7.7',
  'M5 19 12 6l7 13M8.5 13.5h7',
  'M12 3v18M3 12h18',
  'M7 12a3.5 3.5 0 1 0 7 0 3.5 3.5 0 0 0-7 0Zm6 0a3.5 3.5 0 1 0 7 0 3.5 3.5 0 0 0-7 0Z',
] as const;

export interface HeroMarqueeProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  title: ReactNode;
  subtitle?: ReactNode;
  badge?: ReactNode;
  primary?: HeroCta;
  secondary?: HeroCta;
  note?: ReactNode;
  /** Caption over the belt ("Trusted by…"). */
  logoLabel?: ReactNode;
  /** The marks that ride the belt; pass six or more so the loop never shows a seam. */
  logos: HeroMarqueeLogo[];
  /** Seconds per lap (default 32). */
  speed?: number;
  as?: 'h1' | 'h2';
}

/**
 * A centered title over an endless logo belt: the belt pauses under the
 * pointer, dissolves into a mask at both ends, and stands still under reduced
 * motion. It stays LTR whatever the page direction — the marks are geometric
 * and the wordmarks Latin, so the loop's seam stays seamless.
 */
export function HeroMarquee({
  title,
  subtitle,
  badge,
  primary,
  secondary,
  note,
  logoLabel,
  logos,
  speed = 32,
  as: Tag = 'h1',
  className,
  ...rest
}: HeroMarqueeProps) {
  return (
    <section className={cx('nx-hero-marquee', className)} {...rest}>
      <div className="nx-hero-marquee-copy">
        {badge && (
          <p className="nx-hero-marquee-badge">
            <HeroBadge>{badge}</HeroBadge>
          </p>
        )}
        <Tag className="nx-hero-marquee-title">{title}</Tag>
        {subtitle && <p className="nx-hero-marquee-subtitle">{subtitle}</p>}
        {(primary || secondary) && (
          <div className="nx-hero-marquee-actions">
            {primary && <CtaButton cta={primary} variant="primary" />}
            {secondary && <CtaButton cta={secondary} variant="ghost" />}
          </div>
        )}
        {note && <p className="nx-hero-marquee-note">{note}</p>}
      </div>
      {logoLabel && <p className="nx-hero-marquee-label">{logoLabel}</p>}
      <div className="nx-hero-marquee-belt">
        <div className="nx-hero-marquee-track" style={{ '--_speed': `${speed}s` } as CSSProperties}>
          {[false, true].map((mirror) => (
            <div key={String(mirror)} className="nx-hero-marquee-group" aria-hidden={mirror || undefined}>
              {logos.map((logo, i) => (
                <span key={logo.name} className="nx-hero-marquee-logo">
                  <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                    <path d={logo.path ?? LOGO_MARKS[i % LOGO_MARKS.length]!} />
                  </svg>
                  <span>{logo.name}</span>
                </span>
              ))}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
