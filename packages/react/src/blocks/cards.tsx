/** Cards, sliders & carousels (React). */
import {
  type CSSProperties,
  type HTMLAttributes,
  type InputHTMLAttributes,
  type KeyboardEvent as ReactKeyboardEvent,
  type ReactNode,
  isValidElement,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import {
  type IconName,
  type OrbitShowcaseController,
  type RingCarouselController,
  carouselOffset,
  direction,
  elasticGrid,
  flip,
  orbitShowcase,
  pitSlider,
  ringCarousel,
  stackedScroll,
  swipe,
  textDirection,
} from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useEvent, useIsoLayoutEffect, useWidth } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { Button } from '../components/button';
import { Avatar, AvatarGroup } from '../components/display';
import { Badge, type BadgeProps, Tooltip } from '../components/feedback';
import { Slider, type SliderProps } from '../components/form';
import { NumberTicker } from '../components/text';

/* ---- Shared ------------------------------------------------------------------------ */

/** lapis | violet | cyan | gold, or any CSS colour. */
export type CardTone = 'lapis' | 'violet' | 'cyan' | 'gold' | (string & {});

const NAMED_TONES = ['lapis', 'violet', 'cyan', 'gold'];

/** A named tone becomes data-tone; any other colour is set as --nx-tone. */
function tone(value?: CardTone): { 'data-tone'?: string; style?: CSSProperties } {
  if (!value) return {};
  return NAMED_TONES.includes(value) ? { 'data-tone': value } : { style: { '--nx-tone': value } as CSSProperties };
}

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

/** Numbers in the provider's numbering system ("۰۳ / ۰۸" in Persian). */
function useNumberFormat(options?: Intl.NumberFormatOptions): (value: number) => string {
  const locale = INTL[useLocale()];
  const key = JSON.stringify(options ?? null);
  return useMemo(() => {
    const format = new Intl.NumberFormat(locale, options);
    return (value: number) => format.format(value);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [locale, key]);
}

const wrap = (value: number, count: number) => (count > 0 ? ((value % count) + count) % count : 0);

/** A ref that keeps an element inert (unfocusable, hidden from assistive tech) while `on`. */
const inertWhile = (on: boolean) => (el: HTMLElement | null) => {
  el?.toggleAttribute('inert', on);
};

/** Extra fingers after the first never restart a drag. */
function primaryPointerOnly(el: HTMLElement): () => void {
  const guard = (event: PointerEvent) => {
    if (!event.isPrimary) event.stopImmediatePropagation();
  };
  el.addEventListener('pointerdown', guard, true);
  return () => el.removeEventListener('pointerdown', guard, true);
}

/** Artwork: a node, else an image, over an optional CSS background (a span inside buttons). */
function Art({ as: Tag = 'div', className, cover, image, alt, media }: { as?: 'div' | 'span'; className: string; cover?: string; image?: string; alt?: string; media?: ReactNode }) {
  return (
    <Tag className={className} style={cover ? { background: cover } : undefined}>
      {media ?? (image ? <img src={image} alt={alt ?? ''} draggable={false} loading="lazy" /> : null)}
    </Tag>
  );
}

/* ---- Stacked scroll cards ------------------------------------------------------------ */

export interface StackedScrollCardItem {
  title: ReactNode;
  description?: ReactNode;
  /** Artwork beside the text: any node. */
  media?: ReactNode;
  /** A CSS background for the artwork panel (a gradient, an image url()). */
  cover?: string;
  /** Small label over the title; the card's number by default. */
  eyebrow?: ReactNode;
  tone?: CardTone;
  key?: string | number;
}

export interface StackedScrollCardsProps extends HTMLAttributes<HTMLElement> {
  items: StackedScrollCardItem[];
  /** Where the first card sticks (below a sticky header, say). */
  top?: string;
  /** How much lower each next card sticks. */
  offset?: string;
  /** Height of every card. */
  height?: string;
  titleAs?: 'h2' | 'h3' | 'h4';
}

/** Big cards that stick over one another as the page scrolls; the covered ones shrink, lean back and dim. */
export function StackedScrollCards({ items, top, offset, height, titleAs: Title = 'h3', className, style, ...rest }: StackedScrollCardsProps) {
  const ref = useRef<HTMLElement>(null);
  const number = useNumberFormat({ minimumIntegerDigits: 2 });
  useBehavior(ref, stackedScroll, {});

  return (
    <section
      ref={ref}
      className={cx('nx-stacked', className)}
      style={{
        '--nx-count': items.length,
        ...(top ? { '--nx-stacked-top': top } : null),
        ...(offset ? { '--nx-stacked-offset': offset } : null),
        ...(height ? { '--nx-stacked-height': height } : null),
        ...style,
      } as CSSProperties}
      {...rest}
    >
      <ol className="nx-stacked-list">
        {items.map((item, i) => {
          const t = tone(item.tone);
          return (
            <li key={item.key ?? i} className="nx-stacked-item" style={{ '--nx-i': i } as CSSProperties}>
              <article className="nx-stacked-card" data-tone={t['data-tone']} style={t.style}>
                <div className="nx-stacked-body">
                  <div>
                    <span className="nx-stacked-eyebrow">{item.eyebrow ?? number(i + 1)}</span>
                    <Title className="nx-stacked-title">{item.title}</Title>
                  </div>
                  {item.description && <p className="nx-stacked-description">{item.description}</p>}
                </div>
                <Art className="nx-stacked-media" cover={item.cover} media={item.media} />
              </article>
            </li>
          );
        })}
      </ol>
    </section>
  );
}

/* ---- Ring carousel ------------------------------------------------------------------------ */

export interface RingCarouselItem {
  /** Also the morphing label under the ring. */
  title: string;
  subtitle?: string;
  /** A CSS background for the card art. */
  cover?: string;
  image?: string;
  alt?: string;
  media?: ReactNode;
  href?: string;
  tone?: CardTone;
  key?: string | number;
}

export interface RingCarouselProps extends Omit<HTMLAttributes<HTMLElement>, 'onChange'> {
  items: RingCarouselItem[];
  index?: number;
  defaultIndex?: number;
  onIndexChange?: (index: number) => void;
  cardWidth?: string;
  cardHeight?: string;
}

/** Two layers for a morphing label: new text arrives on whichever layer is hidden. */
function useMorph(text: string) {
  const [state, setState] = useState<{ layers: [string, string]; front: 0 | 1; morphing: boolean }>({ layers: [text, ''], front: 0, morphing: false });

  useEffect(() => {
    setState((current) => {
      if (current.layers[current.front] === text) return current;
      const front = current.front === 0 ? 1 : 0;
      const layers: [string, string] = [current.layers[0], current.layers[1]];
      layers[front] = text;
      return { layers, front, morphing: true };
    });
    const timer = setTimeout(() => setState((current) => (current.morphing ? { ...current, morphing: false } : current)), 460);
    return () => clearTimeout(timer);
  }, [text]);

  return state;
}

/**
 * Cards around a 3D ring. Drag it (with inertia; it snaps to the nearest card),
 * scroll it sideways, or use the arrow keys and the buttons. The front card is
 * lit and its title morphs into the next one through a goo filter.
 */
export function RingCarousel({ items, index: indexProp, defaultIndex = 0, onIndexChange, cardWidth, cardHeight, className, style, 'aria-label': label, ...rest }: RingCarouselProps) {
  const t = useT();
  const number = useNumberFormat();
  const count = items.length;
  const [raw, setIndex] = useControllable(indexProp, defaultIndex, onIndexChange);
  const index = wrap(raw, count);
  const ref = useRef<HTMLElement>(null);
  const ring = useRef<RingCarouselController | null>(null);
  const start = useRef(index);
  start.current = index;
  // The last index the ring itself reported: only other changes should turn it.
  const reported = useRef(index);
  const change = useEvent((i: number) => {
    reported.current = i;
    setIndex(i);
  });
  const goo = `nx-goo${useId().replace(/[^\w-]/g, '')}`;
  const current = items[index];
  const morph = useMorph(current?.title ?? '');

  useEffect(() => {
    const el = ref.current;
    if (!el || count < 1) return;
    const controller = ringCarousel(el, { count, index: start.current, onChange: (i) => change(i) });
    ring.current = controller;
    return () => {
      controller.destroy();
      ring.current = null;
    };
  }, [count, change]);

  // A new index from outside (controlled) turns the ring there.
  useEffect(() => {
    if (!ring.current || index === reported.current) return;
    reported.current = index;
    ring.current.go(index);
  }, [index]);

  return (
    <section
      ref={ref}
      className={cx('nx-ring', className)}
      aria-roledescription="carousel"
      aria-label={label}
      style={{
        '--nx-count': Math.max(count, 1),
        ...(cardWidth ? { '--nx-ring-card-width': cardWidth } : null),
        ...(cardHeight ? { '--nx-ring-card-height': cardHeight } : null),
        ...style,
      } as CSSProperties}
      {...rest}
    >
      <div className="nx-ring-stage">
        <div className="nx-ring-track">
          {items.map((item, i) => {
            const active = i === index;
            const tn = tone(item.tone);
            const face = (
              <>
                <Art className="nx-ring-cover" cover={item.cover} image={item.image} alt={item.alt} media={item.media} />
                <span className="nx-ring-caption">
                  <span>{item.title}</span>
                  {item.subtitle && <small>{item.subtitle}</small>}
                </span>
              </>
            );
            return (
              <div
                key={item.key ?? i}
                className="nx-ring-card"
                style={{ '--i': i } as CSSProperties}
                data-active={active ? '' : undefined}
                role="group"
                aria-roledescription="slide"
                aria-label={`${number(i + 1)} / ${number(count)}`}
                aria-hidden={active ? undefined : true}
                onClick={active ? undefined : () => ring.current?.go(i)}
              >
                {item.href ? (
                  <SmartLink href={item.href} className="nx-ring-face" data-tone={tn['data-tone']} style={tn.style} draggable={false} tabIndex={active ? undefined : -1}>
                    {face}
                  </SmartLink>
                ) : (
                  <div className="nx-ring-face" data-tone={tn['data-tone']} style={tn.style}>
                    {face}
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </div>

      <div className="nx-ring-label" data-morphing={morph.morphing ? '' : undefined} style={{ '--_goo': `url(#${goo})` } as CSSProperties} aria-hidden="true">
        <svg className="nx-ring-goo" focusable="false" aria-hidden="true">
          <filter id={goo} colorInterpolationFilters="sRGB">
            <feColorMatrix in="SourceGraphic" type="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -8" />
          </filter>
        </svg>
        <div className="nx-ring-titles">
          {morph.layers.map((text, layer) => (
            <span key={layer} className="nx-ring-title" data-state={layer === morph.front ? 'in' : 'out'} dir={textDirection(text)}>
              {text}
            </span>
          ))}
        </div>
        {current?.subtitle && <p className="nx-ring-subtitle">{current.subtitle}</p>}
      </div>
      <p className="nx-visually-hidden" aria-live="polite">
        {current ? `${current.title}${current.subtitle ? ` — ${current.subtitle}` : ''}` : ''}
      </p>

      <div className="nx-ring-controls">
        <Button variant="secondary" shape="pill" iconOnly icon="chevron-left" aria-label={t('previous')} onClick={() => ring.current?.prev()}>
          {t('previous')}
        </Button>
        <span className="nx-ring-count" aria-hidden="true">
          {number(index + 1)} / {number(count)}
        </span>
        <Button variant="secondary" shape="pill" iconOnly icon="chevron-right" aria-label={t('next')} onClick={() => ring.current?.next()}>
          {t('next')}
        </Button>
      </div>
    </section>
  );
}

/* ---- Expandable stack --------------------------------------------------------------------- */

export interface ExpandableStackItem {
  title: ReactNode;
  description?: ReactNode;
  media?: ReactNode;
  cover?: string;
  image?: string;
  alt?: string;
  tone?: CardTone;
  key?: string | number;
}

export interface ExpandableStackProps extends HTMLAttributes<HTMLDivElement> {
  items: ExpandableStackItem[];
  expanded?: boolean;
  defaultExpanded?: boolean;
  onExpandedChange?: (expanded: boolean) => void;
  /** The toggle's label while stacked. */
  expandLabel?: ReactNode;
  /** The toggle's label while spread out. */
  collapseLabel?: ReactNode;
  titleAs?: 'h3' | 'h4';
  cardWidth?: string;
  cardHeight?: string;
}

/** A fanned pile of cards that flies out into a grid (FLIP on the gentle spring) and back. */
export function ExpandableStack({
  items,
  expanded,
  defaultExpanded = false,
  onExpandedChange,
  expandLabel = 'Show all',
  collapseLabel = 'Stack them',
  titleAs: Title = 'h3',
  cardWidth,
  cardHeight,
  className,
  style,
  ...rest
}: ExpandableStackProps) {
  const [open, setOpen] = useControllable(expanded, defaultExpanded, onExpandedChange);
  // The layout on screen; it follows `open` inside a FLIP.
  const [shown, setShown] = useState(open);
  const list = useRef<HTMLUListElement>(null);
  const committed = useRef<(() => void) | null>(null);
  const listId = useId();

  useIsoLayoutEffect(() => {
    const done = committed.current;
    committed.current = null;
    done?.();
  }, [shown]);

  useEffect(() => {
    if (open === shown) return;
    const el = list.current;
    if (!el) {
      setShown(open);
      return;
    }
    void flip(el.children, () => new Promise<void>((resolve) => {
      committed.current = resolve;
      setShown(open);
    }), { stagger: 30, container: el });
  }, [open, shown]);

  return (
    <div
      className={cx('nx-xstack', className)}
      data-expanded={shown ? '' : undefined}
      style={{
        '--nx-count': items.length,
        ...(cardWidth ? { '--nx-xstack-card-width': cardWidth } : null),
        ...(cardHeight ? { '--nx-xstack-card-height': cardHeight } : null),
        ...style,
      } as CSSProperties}
      {...rest}
    >
      <ul ref={list} id={listId} className="nx-xstack-list" onClick={shown ? undefined : () => setOpen(true)}>
        {items.map((item, i) => {
          const tn = tone(item.tone);
          const tucked = !shown && i > 0;
          return (
            <li key={item.key ?? i} ref={inertWhile(tucked)} className="nx-xstack-item" style={{ '--nx-i': i } as CSSProperties} aria-hidden={tucked || undefined}>
              <article className="nx-xstack-card" data-tone={tn['data-tone']} style={tn.style}>
                <Art className="nx-xstack-media" cover={item.cover} image={item.image} alt={item.alt} media={item.media} />
                <div className="nx-xstack-text">
                  <Title className="nx-xstack-title">{item.title}</Title>
                  {item.description && <p className="nx-xstack-description">{item.description}</p>}
                </div>
              </article>
            </li>
          );
        })}
      </ul>
      <Button className="nx-xstack-toggle" variant="secondary" shape="pill" iconEnd="chevron-down" aria-expanded={open} aria-controls={listId} onClick={() => setOpen(!open)}>
        {open ? collapseLabel : expandLabel}
      </Button>
    </div>
  );
}

/* ---- Orbit showcase ------------------------------------------------------------------------ */

export interface OrbitShowcaseItem {
  title: string;
  subtitle?: string;
  cover?: string;
  image?: string;
  alt?: string;
  media?: ReactNode;
  href?: string;
  tone?: CardTone;
  key?: string | number;
}

export interface OrbitShowcaseProps extends HTMLAttributes<HTMLElement> {
  items: OrbitShowcaseItem[];
  /** What sits in the middle; a glowing orb by default. */
  center?: ReactNode;
  /** Seconds for one revolution. */
  period?: number;
  /** A pause button (on by default: moving content must be stoppable). */
  controls?: boolean;
  height?: string;
  radius?: string;
  onFrontChange?: (index: number) => void;
}

/** Project screens orbiting a centre and always facing you; hover pauses, focus or a click brings one forward. */
export function OrbitShowcase({ items, center, period = 40, controls = true, height, radius, onFrontChange, className, style, ...rest }: OrbitShowcaseProps) {
  const t = useT();
  const count = items.length;
  const ref = useRef<HTMLElement>(null);
  const orbit = useRef<OrbitShowcaseController | null>(null);
  const [front, setFront] = useState(0);
  const [paused, setPaused] = useState(false);
  const pausedRef = useRef(paused);
  pausedRef.current = paused;
  const notify = useEvent(onFrontChange);

  useEffect(() => {
    const el = ref.current;
    if (!el || count < 1) return;
    const controller = orbitShowcase(el, {
      count,
      period,
      paused: pausedRef.current,
      onFront: (i) => {
        setFront(i);
        notify(i);
      },
    });
    orbit.current = controller;
    return () => {
      controller.destroy();
      orbit.current = null;
    };
  }, [count, period, notify]);

  const toggle = () => {
    if (paused) orbit.current?.play();
    else orbit.current?.pause();
    setPaused(!paused);
  };

  const shown = items[front];

  return (
    <section
      ref={ref}
      className={cx('nx-orbit', className)}
      style={{ '--nx-count': Math.max(count, 1), ...(height ? { '--nx-orbit-height': height } : null), ...(radius ? { '--nx-orbit-radius': radius } : null), ...style } as CSSProperties}
      {...rest}
    >
      <div className="nx-orbit-stage">
        <div className="nx-orbit-scene">
          <span className="nx-orbit-path" aria-hidden="true" />
          <div className="nx-orbit-center">{center ?? <span className="nx-orbit-orb" aria-hidden="true" />}</div>
          {items.map((item, i) => {
            const tn = tone(item.tone);
            const inner = (
              <>
                <span className="nx-orbit-bar" aria-hidden="true">
                  <i />
                  <i />
                  <i />
                  <span>{item.title}</span>
                </span>
                <Art as="span" className="nx-orbit-cover" cover={item.cover} image={item.image} alt={item.alt} media={item.media} />
                <span className="nx-visually-hidden">{item.subtitle ? `${item.title} — ${item.subtitle}` : item.title}</span>
              </>
            );
            return (
              <div key={item.key ?? i} className="nx-orbit-item" style={{ '--i': i } as CSSProperties}>
                {item.href ? (
                  <SmartLink href={item.href} className="nx-orbit-screen" data-tone={tn['data-tone']} style={tn.style} draggable={false}>
                    {inner}
                  </SmartLink>
                ) : (
                  <button type="button" className="nx-orbit-screen" data-tone={tn['data-tone']} style={tn.style}>
                    {inner}
                  </button>
                )}
              </div>
            );
          })}
        </div>
        {controls && count > 1 && (
          <Button className="nx-orbit-toggle" variant="secondary" size="sm" shape="pill" iconOnly icon={paused ? 'play' : 'pause'} aria-label={paused ? t('play') : t('pause')} aria-pressed={paused} onClick={toggle}>
            {paused ? t('play') : t('pause')}
          </Button>
        )}
      </div>
      <div className="nx-orbit-caption" aria-hidden="true">
        {shown && <strong>{shown.title}</strong>}
        {shown?.subtitle && <span>{shown.subtitle}</span>}
      </div>
    </section>
  );
}

/* ---- Team cards ----------------------------------------------------------------------------- */

export interface TeamMember {
  name: string;
  role?: ReactNode;
  /** Photo URL; initials otherwise. */
  avatar?: string;
  /** The glow: lapis | violet | cyan | gold, or any CSS colour. */
  color?: CardTone;
  href?: string;
  key?: string | number;
}

export interface TeamCardsProps extends HTMLAttributes<HTMLDivElement> {
  members: TeamMember[];
  nameAs?: 'h3' | 'h4' | 'p';
  cardWidth?: string;
}

/** Tall dark cards side by side; the hovered, focused or tapped one rises and its colour climbs. */
export function TeamCards({ members, nameAs: Name = 'h3', cardWidth, className, style, 'aria-label': label, ...rest }: TeamCardsProps) {
  const base = useId();

  return (
    <div
      className={cx('nx-team', className)}
      style={{ '--nx-count': Math.max(members.length, 1), ...(cardWidth ? { '--nx-team-card-width': cardWidth } : null), ...style } as CSSProperties}
      {...rest}
    >
      <ul className="nx-team-list" aria-label={label}>
        {members.map((member, i) => {
          const id = `${base}-${i}`;
          const tn = tone(member.color);
          const content = (
            <>
              <div className="nx-team-text">
                <Name className="nx-team-name" id={id}>
                  {member.name}
                </Name>
                {member.role && <p className="nx-team-role">{member.role}</p>}
              </div>
              <Avatar name={member.name} src={member.avatar} size="lg" aria-hidden="true" />
            </>
          );
          const shared = { className: 'nx-team-card', 'data-theme': 'dark', 'data-tone': tn['data-tone'], style: tn.style };
          return (
            <li key={member.key ?? member.name} className="nx-team-item">
              {member.href ? (
                <SmartLink href={member.href} {...shared}>
                  {content}
                </SmartLink>
              ) : (
                // Focusable so a keyboard or a tap can raise it like a hover does.
                <article {...shared} tabIndex={0} aria-labelledby={id} onPointerDown={(event) => event.pointerType !== 'mouse' && event.currentTarget.focus()}>
                  {content}
                </article>
              )}
            </li>
          );
        })}
      </ul>
    </div>
  );
}

/* ---- Depth carousel ---------------------------------------------------------------------------- */

export interface DepthCarouselSlide {
  title?: ReactNode;
  description?: ReactNode;
  cover?: string;
  image?: string;
  alt?: string;
  href?: string;
  tone?: CardTone;
  key?: string | number;
}

export interface DepthCarouselProps extends Omit<HTMLAttributes<HTMLElement>, 'onChange'> {
  /** Slides: built-in ones from objects, or any nodes. */
  items: Array<DepthCarouselSlide | ReactNode>;
  index?: number;
  defaultIndex?: number;
  onIndexChange?: (index: number) => void;
  /** Past the last card comes the first. */
  loop?: boolean;
  dots?: boolean;
  cardWidth?: string;
  cardHeight?: string;
}

const isSlide = (item: unknown): item is DepthCarouselSlide => !!item && typeof item === 'object' && !isValidElement(item) && !Array.isArray(item);

function DepthSlide({ title, description, cover, image, alt, href }: DepthCarouselSlide) {
  const content = (
    <>
      <Art className="nx-depth-media" cover={cover} image={image} alt={alt} />
      {title && <h3 className="nx-depth-title">{title}</h3>}
      {description && <p className="nx-depth-description">{description}</p>}
    </>
  );
  return href ? (
    <SmartLink href={href} className="nx-depth-slide" draggable={false}>
      {content}
    </SmartLink>
  ) : (
    <div className="nx-depth-slide">{content}</div>
  );
}

/** The active card centred and full size, its neighbours smaller, pushed back and softened at both sides. */
export function DepthCarousel({
  items,
  index: indexProp,
  defaultIndex = 0,
  onIndexChange,
  loop = true,
  dots = true,
  cardWidth,
  cardHeight,
  className,
  style,
  onKeyDown,
  'aria-label': label,
  ...rest
}: DepthCarouselProps) {
  const t = useT();
  const number = useNumberFormat();
  const count = items.length;
  const [raw, setIndex] = useControllable(indexProp, defaultIndex, onIndexChange);
  const index = loop ? wrap(raw, count) : Math.min(Math.max(raw, 0), Math.max(count - 1, 0));
  const stage = useRef<HTMLDivElement>(null);

  const go = useEvent((to: number) => {
    if (!count) return;
    const next = loop ? wrap(to, count) : Math.min(Math.max(to, 0), count - 1);
    if (next !== index) setIndex(next);
  });
  const step = useEvent((by: number) => go(index + by));

  useEffect(() => {
    const el = stage.current;
    if (!el) return;
    let dragged = false;
    const release = () => setTimeout(() => (dragged = false));
    // The release that ends a drag is not a click on the card under it.
    const swallow = (event: MouseEvent) => {
      if (!dragged) return;
      dragged = false;
      event.preventDefault();
      event.stopPropagation();
    };
    const guard = primaryPointerOnly(el);
    el.addEventListener('click', swallow, true);
    const stop = swipe(el, {
      axis: 'x',
      threshold: 56,
      velocity: 0.11,
      ignore: 'input, textarea, select, [data-nx-no-swipe]',
      onStart: () => (dragged = true),
      onCancel: release,
      onSwipe: (swiped) => {
        release();
        el.style.setProperty('--nx-dx', '0px');
        // "Next" sits toward inline-end: dragging toward inline-start brings it in.
        step((swiped === 'left') === (direction(el) === 1) ? 1 : -1);
      },
    });
    return () => {
      stop();
      guard();
      el.removeEventListener('click', swallow, true);
    };
  }, [step]);

  const onKey = (event: ReactKeyboardEvent<HTMLElement>) => {
    onKeyDown?.(event);
    if (event.defaultPrevented || (event.target as Element).closest('input, textarea, select, [contenteditable]')) return;
    const dir = stage.current ? direction(stage.current) : 1;
    if (event.key === 'ArrowRight') step(dir);
    else if (event.key === 'ArrowLeft') step(-dir);
    else if (event.key === 'Home') go(0);
    else if (event.key === 'End') go(count - 1);
    else return;
    event.preventDefault();
  };

  return (
    <section
      className={cx('nx-depth', className)}
      aria-roledescription="carousel"
      aria-label={label}
      onKeyDown={onKey}
      style={{ ...(cardWidth ? { '--nx-depth-card-width': cardWidth } : null), ...(cardHeight ? { '--nx-depth-card-height': cardHeight } : null), ...style } as CSSProperties}
      {...rest}
    >
      <div ref={stage} className="nx-depth-stage" data-at-start={!loop && index === 0 ? '' : undefined} data-at-end={!loop && index === count - 1 ? '' : undefined}>
        {items.map((item, i) => {
          const offset = carouselOffset(i, index, count, loop);
          const active = offset === 0;
          const slide = isSlide(item) ? item : null;
          const tn = tone(slide?.tone);
          return (
            <div
              key={slide?.key ?? i}
              className="nx-depth-card"
              data-active={active ? '' : undefined}
              data-tone={tn['data-tone']}
              style={{ '--_o': offset, '--_d': Math.abs(offset), ...tn.style } as CSSProperties}
              role="group"
              aria-roledescription="slide"
              aria-label={`${number(i + 1)} / ${number(count)}`}
              aria-hidden={active ? undefined : true}
              onClick={active ? undefined : () => go(i)}
            >
              <div ref={inertWhile(!active)} className="nx-depth-content">
                {slide ? <DepthSlide {...slide} /> : (item as ReactNode)}
              </div>
            </div>
          );
        })}
      </div>
      <div className="nx-depth-controls">
        <Button variant="secondary" shape="pill" iconOnly icon="chevron-left" aria-label={t('previous')} disabled={!loop && index === 0} onClick={() => step(-1)}>
          {t('previous')}
        </Button>
        {dots && (
          <div className="nx-depth-dots" role="group" aria-label={t('pagination')}>
            {items.map((_, i) => (
              <button key={i} type="button" className="nx-depth-dot" aria-label={t('page', { page: number(i + 1) })} aria-current={i === index ? 'true' : undefined} onClick={() => go(i)} />
            ))}
          </div>
        )}
        <Button variant="secondary" shape="pill" iconOnly icon="chevron-right" aria-label={t('next')} disabled={!loop && index === count - 1} onClick={() => step(1)}>
          {t('next')}
        </Button>
      </div>
      <p className="nx-visually-hidden" aria-live="polite">
        {count ? `${number(index + 1)} / ${number(count)}` : ''}
      </p>
    </section>
  );
}

/* ---- Cycle stack --------------------------------------------------------------------------------- */

export interface CycleStackProps extends HTMLAttributes<HTMLDivElement> {
  /** The cards, front first. */
  items: ReactNode[];
  /** The card in front. */
  index?: number;
  defaultIndex?: number;
  onIndexChange?: (index: number) => void;
  /** Label of the cycle button ("Next" by default). */
  nextLabel?: string;
}

export interface CycleCardProps extends Omit<HTMLAttributes<HTMLDivElement>, 'title'> {
  icon?: IconName | ReactNode;
  title: ReactNode;
  description?: ReactNode;
  meta?: ReactNode;
  tone?: CardTone;
}

/** A ready-made face for a CycleStack card. */
export function CycleCard({ icon, title, description, meta, tone: toneValue, className, style, ...rest }: CycleCardProps) {
  const tn = tone(toneValue);
  return (
    <div className={cx('nx-cycle-face', className)} data-tone={tn['data-tone']} style={{ ...tn.style, ...style }} {...rest}>
      {icon && <span className="nx-cycle-icon">{typeof icon === 'string' ? <Icon name={icon as IconName} /> : icon}</span>}
      <h3 className="nx-cycle-title">{title}</h3>
      {description && <p className="nx-cycle-description">{description}</p>}
      {meta && <span className="nx-cycle-meta">{meta}</span>}
    </div>
  );
}

/** A vertical deck: drag the top card down (or click it, or press Next) and it tucks in behind the others. */
export function CycleStack({ items, index: indexProp, defaultIndex = 0, onIndexChange, nextLabel, className, ...rest }: CycleStackProps) {
  const t = useT();
  const number = useNumberFormat();
  const count = items.length;
  const [raw, setIndex] = useControllable(indexProp, defaultIndex, onIndexChange);
  const front = wrap(raw, count);
  const [tucking, setTucking] = useState<number | null>(null);
  const top = useRef<HTMLElement | null>(null);
  const timer = useRef<ReturnType<typeof setTimeout> | undefined>(undefined);

  useEffect(() => () => clearTimeout(timer.current), []);

  const cycle = useEvent(() => {
    if (tucking !== null || count < 2) return;
    top.current?.style.setProperty('--nx-dy', '0px');
    setTucking(front);
    // It drops first; then it rises behind the others as they step up.
    timer.current = setTimeout(() => {
      setTucking(null);
      setIndex(wrap(front + 1, count));
    }, 230);
  });

  useEffect(() => {
    const el = top.current;
    if (!el || tucking !== null || count < 2) return;
    let dragged = false;
    const release = () => setTimeout(() => (dragged = false));
    const onClick = (event: MouseEvent) => {
      if (dragged) {
        dragged = false;
        event.preventDefault();
        event.stopPropagation();
        return;
      }
      if (!(event.target as Element).closest('a, button, input, textarea, select, label, [data-nx-no-swipe]')) cycle();
    };
    const guard = primaryPointerOnly(el);
    el.addEventListener('click', onClick);
    const stop = swipe(el, {
      axis: 'y',
      threshold: 64,
      velocity: 0.11,
      onStart: () => (dragged = true),
      onCancel: release,
      onSwipe: (swiped) => {
        release();
        if (swiped === 'down') cycle();
        else el.style.setProperty('--nx-dy', '0px');
      },
    });
    return () => {
      stop();
      guard();
      el.removeEventListener('click', onClick);
      for (const name of ['--nx-dx', '--nx-dy', '--nx-drag-progress', '--nx-exit-x']) el.style.removeProperty(name);
    };
  }, [front, tucking, count, cycle]);

  return (
    <div className={cx('nx-cycle', className)} {...rest}>
      <div className="nx-cycle-deck">
        {items.map((item, i) => {
          // While the top card drops, the rest already step up.
          const depth = tucking === i ? 0 : wrap(i - front - (tucking !== null ? 1 : 0), count);
          const isTop = tucking === null && depth === 0;
          return (
            <article
              key={i}
              ref={(el) => {
                el?.toggleAttribute('inert', !isTop);
                if (isTop) top.current = el;
              }}
              className="nx-cycle-card"
              data-state={tucking === i ? 'tucking' : isTop ? 'top' : undefined}
              style={{ '--_d': depth } as CSSProperties}
              aria-hidden={isTop ? undefined : true}
            >
              {item}
            </article>
          );
        })}
      </div>
      <div className="nx-cycle-controls">
        <span className="nx-cycle-count" aria-live="polite">
          {number(front + 1)} / {number(count)}
        </span>
        <Button variant="secondary" shape="pill" icon="layers" disabled={count < 2} onClick={() => cycle()}>
          {nextLabel ?? t('next')}
        </Button>
      </div>
    </div>
  );
}

/* ---- Elastic grid ---------------------------------------------------------------------------------- */

export interface ElasticGridItem {
  cover?: string;
  image?: string;
  alt?: string;
  title?: ReactNode;
  caption?: ReactNode;
  /** Aspect ratio of the art ("4 / 5"); the column rhythm picks one otherwise. */
  ratio?: string;
  href?: string;
  key?: string | number;
}

export interface ElasticGridProps extends HTMLAttributes<HTMLDivElement> {
  /** Cells: built-in ones from objects, or any nodes. */
  items: Array<ElasticGridItem | ReactNode>;
  /** Columns on a wide screen (two on a narrow one). */
  columns?: number;
  /** Largest distance a column may trail, px. */
  lag?: number;
  /** Parallax travel between columns, px. */
  parallax?: number;
}

const isElasticItem = (item: unknown): item is ElasticGridItem => !!item && typeof item === 'object' && !isValidElement(item) && !Array.isArray(item);

/** A grid whose columns follow the scroll on springs of their own: they trail, stretch and settle. */
export function ElasticGrid({ items, columns = 3, lag = 90, parallax = 40, className, style, ...rest }: ElasticGridProps) {
  const ref = useRef<HTMLDivElement>(null);
  const width = useWidth(ref);
  const cols = Math.max(1, width && width < 560 ? Math.min(2, columns) : columns);
  const groups = useMemo(() => {
    const out: Array<Array<{ item: ElasticGridItem | ReactNode; i: number }>> = Array.from({ length: cols }, () => []);
    items.forEach((item, i) => out[i % cols]!.push({ item, i }));
    return out;
  }, [items, cols]);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    return elasticGrid(el, { max: lag, parallax });
  }, [cols, lag, parallax, items.length]);

  return (
    <div ref={ref} className={cx('nx-elastic', className)} style={{ '--nx-columns': cols, ...style } as CSSProperties} {...rest}>
      {groups.map((group, c) => (
        <div key={c} className="nx-elastic-col">
          {group.map(({ item, i }) => {
            if (!isElasticItem(item)) {
              return (
                <div key={i} className="nx-elastic-cell">
                  {item as ReactNode}
                </div>
              );
            }
            const art = (
              <>
                <div className="nx-elastic-cover" style={{ ...(item.cover ? { background: item.cover } : null), ...(item.ratio ? { '--nx-ratio': item.ratio } : null) } as CSSProperties}>
                  {item.image ? <img src={item.image} alt={item.alt ?? ''} loading="lazy" /> : null}
                </div>
                {(item.title || item.caption) && (
                  <figcaption className="nx-elastic-caption">
                    {item.title && <span>{item.title}</span>}
                    {item.caption && <small>{item.caption}</small>}
                  </figcaption>
                )}
              </>
            );
            return (
              <figure key={item.key ?? i} className="nx-elastic-cell">
                {item.href ? (
                  <SmartLink href={item.href} style={{ display: 'contents' }}>
                    {art}
                  </SmartLink>
                ) : (
                  art
                )}
              </figure>
            );
          })}
        </div>
      ))}
    </div>
  );
}

/* ---- Workflow card --------------------------------------------------------------------------------- */

export interface WorkflowAction {
  icon: IconName;
  label: string;
  onClick?: () => void;
  href?: string;
}

export interface WorkflowCardProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  title: ReactNode;
  description?: ReactNode;
  icon?: IconName | ReactNode;
  /** A status badge ("Running", "Paused"…). */
  status?: ReactNode;
  statusTone?: BadgeProps['tone'];
  /** The status dot breathes (for something running now). */
  live?: boolean;
  members?: Array<{ name: string; src?: string }>;
  membersLabel?: string;
  /** Detail rows revealed when the card opens. */
  meta?: Array<{ label: ReactNode; value: ReactNode }>;
  actions?: WorkflowAction[];
  /** Keep it open, not only on hover and focus. */
  expanded?: boolean;
  titleAs?: 'h3' | 'h4';
}

/** A workflow summary that opens on hover or focus: details grow in, avatars, and actions sliding in one by one. */
export function WorkflowCard({
  title,
  description,
  icon = 'zap',
  status,
  statusTone = 'success',
  live,
  members,
  membersLabel,
  meta,
  actions,
  expanded,
  titleAs: Title = 'h3',
  className,
  ...rest
}: WorkflowCardProps) {
  const id = useId();

  return (
    <article className={cx('nx-workflow', className)} tabIndex={0} aria-labelledby={id} data-expanded={expanded ? '' : undefined} {...rest}>
      <div className="nx-workflow-head">
        <span className="nx-workflow-icon">{typeof icon === 'string' ? <Icon name={icon as IconName} /> : icon}</span>
        <div>
          <Title className="nx-workflow-title" id={id}>
            {title}
          </Title>
          {description && <p className="nx-workflow-description">{description}</p>}
        </div>
        {status && (
          <Badge tone={statusTone} dot pulse={live}>
            {status}
          </Badge>
        )}
      </div>
      {meta && meta.length > 0 && (
        <div className="nx-workflow-details">
          <div>
            <dl className="nx-workflow-meta">
              {meta.map((row, i) => (
                <div key={i} className="nx-workflow-row">
                  <dt>{row.label}</dt>
                  <dd>{row.value}</dd>
                </div>
              ))}
            </dl>
          </div>
        </div>
      )}
      {((members && members.length > 0) || (actions && actions.length > 0)) && (
        <div className="nx-workflow-foot">
          {members && members.length > 0 && <AvatarGroup people={members} size="sm" max={4} label={membersLabel} />}
          {actions && actions.length > 0 && (
            <div className="nx-workflow-actions">
              {actions.map((action) => (
                <span key={action.label}>
                  <Tooltip content={action.label}>
                    <Button variant="ghost" size="sm" shape="pill" iconOnly icon={action.icon} aria-label={action.label} href={action.href} onClick={action.onClick}>
                      {action.label}
                    </Button>
                  </Tooltip>
                </span>
              ))}
            </div>
          )}
        </div>
      )}
    </article>
  );
}

/* ---- Feature cards ------------------------------------------------------------------------------- */

export type FeatureVisual = 'inbox' | 'summary' | 'processing' | 'team';

export interface FeatureCardProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** The animated illustration on top. */
  visual: FeatureVisual;
  title: ReactNode;
  description?: ReactNode;
  href?: string;
  tone?: CardTone;
  titleAs?: 'h2' | 'h3' | 'h4';
}

const four = [0, 1, 2, 3];
const three = [0, 1, 2];

/** The illustrations: decorative CSS loops that rest under reduced motion. */
export function FeatureVisualArt({ visual }: { visual: FeatureVisual }) {
  let art: ReactNode = null;
  if (visual === 'inbox') {
    art = (
      <div className="nx-fv-inbox">
        {four.map((i) => (
          <span key={i} className="nx-fv-row" style={{ '--nx-i': i } as CSSProperties}>
            <b />
            <span>
              <i className="nx-fv-line" />
              <i className="nx-fv-line" />
            </span>
          </span>
        ))}
      </div>
    );
  } else if (visual === 'summary') {
    art = (
      <div className="nx-fv-summary">
        {four.map((i) => (
          <i key={i} className="nx-fv-line" style={{ '--nx-i': i } as CSSProperties} />
        ))}
        <span className="nx-fv-pill">
          <Icon name="sparkles" />
          <span>
            <i className="nx-fv-line" />
            <i className="nx-fv-line" />
          </span>
        </span>
      </div>
    );
  } else if (visual === 'processing') {
    art = (
      <div className="nx-fv-processing">
        <span className="nx-fv-core">
          {three.map((i) => (
            <span key={i} className="nx-fv-ring" style={{ '--nx-i': i } as CSSProperties} />
          ))}
          <span className="nx-fv-bolt">
            <Icon name="zap" />
          </span>
        </span>
        <span className="nx-fv-bar">
          <i />
        </span>
      </div>
    );
  } else if (visual === 'team') {
    art = (
      <div className="nx-fv-team">
        {three.map((i) => (
          <span key={`slot-${i}`} className="nx-fv-slot" style={{ '--nx-i': i } as CSSProperties} />
        ))}
        {three.map((i) => (
          <span key={`person-${i}`} className="nx-fv-person" style={{ '--nx-i': i } as CSSProperties}>
            <Icon name="user" />
          </span>
        ))}
      </div>
    );
  }
  return (
    <div className="nx-feature-visual" data-visual={visual} aria-hidden="true">
      {art}
    </div>
  );
}

/** A card with a small animated illustration: inbox, summary, processing or team. */
export function FeatureCard({ visual, title, description, href, tone: toneValue, titleAs: Title = 'h3', className, style, children, ...rest }: FeatureCardProps) {
  const tn = tone(toneValue);
  const content = (
    <>
      <FeatureVisualArt visual={visual} />
      <div className="nx-feature-body">
        <Title className="nx-feature-title">{title}</Title>
        {description && <p className="nx-feature-description">{description}</p>}
        {children}
      </div>
    </>
  );
  const shared = { className: cx('nx-feature', className), 'data-tone': tn['data-tone'], style: { ...tn.style, ...style } };

  if (href) {
    return (
      <SmartLink href={href} {...shared} {...(rest as HTMLAttributes<HTMLAnchorElement>)}>
        {content}
      </SmartLink>
    );
  }
  return (
    <article {...shared} {...rest}>
      {content}
    </article>
  );
}

/* ---- Pit slider ----------------------------------------------------------------------------------- */

export interface PitSliderProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type' | 'value' | 'defaultValue' | 'onChange' | 'min' | 'max' | 'step'> {
  min?: number;
  max?: number;
  step?: number;
  value?: number;
  defaultValue?: number;
  onValueChange?: (value: number) => void;
  label?: ReactNode;
  /** How many sticks. */
  bars?: number;
  formatValue?: (value: number) => string;
  /** A dark panel whatever the page theme (default), or follow the page. */
  dark?: boolean;
}

/** A row of sticks over a native range: they grow on hover and dip into a pit under the thumb while you drag. */
export function PitSlider({
  min = 0,
  max = 100,
  step = 1,
  value,
  defaultValue,
  onValueChange,
  label,
  bars = 36,
  formatValue,
  dark = true,
  className,
  style,
  id: idProp,
  ...rest
}: PitSliderProps) {
  const generated = useId();
  const id = idProp ?? generated;
  const ref = useRef<HTMLDivElement>(null);
  const [current, setCurrent] = useControllable(value, defaultValue ?? min, onValueChange);
  const fraction = Math.min(1, Math.max(0, (current - min) / (max - min || 1)));
  const shown = formatValue ? formatValue(current) : String(current);
  const count = Math.max(2, Math.round(bars));
  useBehavior(ref, pitSlider, {});

  return (
    <div
      ref={ref}
      className={cx('nx-pit', className)}
      data-theme={dark ? 'dark' : undefined}
      style={{ '--nx-frac': fraction, '--nx-pct': `${fraction * 100}%`, ...style } as CSSProperties}
    >
      {label && (
        <div className="nx-pit-head">
          <label className="nx-pit-label" htmlFor={id}>
            {label}
          </label>
          <span className="nx-pit-readout" aria-hidden="true">
            {shown}
          </span>
        </div>
      )}
      <div className="nx-pit-track">
        <div className="nx-pit-bars" aria-hidden="true">
          {Array.from({ length: count }, (_, i) => (
            <span key={i} className="nx-pit-bar" style={{ '--p': i / (count - 1) } as CSSProperties} />
          ))}
        </div>
        <span className="nx-pit-thumb" aria-hidden="true" />
        <output className="nx-pit-value" aria-hidden="true">
          {shown}
        </output>
        <input
          id={id}
          type="range"
          className="nx-pit-input"
          min={min}
          max={max}
          step={step}
          value={current}
          aria-valuetext={formatValue ? shown : undefined}
          onChange={(event) => setCurrent(Number(event.target.value))}
          {...rest}
        />
      </div>
    </div>
  );
}

/* ---- Precision slider ------------------------------------------------------------------------------- */

export interface PrecisionSliderProps extends Omit<SliderProps, 'showValue' | 'formatValue' | 'startLabel' | 'endLabel' | 'prefix'> {
  label?: ReactNode;
  /** After the number: "%", "ms", "°C"… */
  unit?: ReactNode;
  /** Before the number: "$"… */
  prefix?: ReactNode;
  /** Intl options for the number (fraction digits, grouping…). */
  format?: Intl.NumberFormatOptions;
  locale?: string;
  /** How many tick intervals (from the step by default). */
  ticks?: number;
  /** Every n-th tick is long. */
  majorEvery?: number;
}

/** The slider with a large rolling number, fine ticks that fill with the value, and its range's ends. */
export function PrecisionSlider({
  min = 0,
  max = 100,
  step = 1,
  value,
  defaultValue,
  onValueChange,
  label,
  unit,
  prefix,
  format,
  locale,
  ticks,
  majorEvery,
  className,
  style,
  id: idProp,
  ...rest
}: PrecisionSliderProps) {
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const generated = useId();
  const id = idProp ?? generated;
  const [current, setCurrent] = useControllable(value, defaultValue ?? min, onValueChange);
  const fraction = Math.min(1, Math.max(0, (current - min) / (max - min || 1)));
  const formatter = useMemo(() => new Intl.NumberFormat(intl, format), [intl, format]);
  const steps = Math.round((max - min) / (step || 1));
  const intervals = Math.max(2, Math.round(ticks ?? (steps > 0 && steps <= 60 ? steps : 40)));
  const major = Math.max(1, majorEvery ?? (intervals % 10 === 0 ? intervals / 10 : intervals % 5 === 0 ? 5 : 1));
  const text = (n: number) => {
    const affix = (part: ReactNode) => (typeof part === 'string' || typeof part === 'number' ? String(part) : '');
    return `${affix(prefix)}${formatter.format(n)}${unit !== undefined ? ` ${affix(unit)}` : ''}`.trim();
  };

  return (
    <div className={cx('nx-precision', className)} style={{ '--nx-frac': fraction, ...style } as CSSProperties}>
      <div className="nx-precision-head">
        {label && (
          <label className="nx-precision-label" htmlFor={id}>
            {label}
          </label>
        )}
        <div className="nx-precision-readout" aria-hidden="true">
          {prefix && <span className="nx-precision-affix">{prefix}</span>}
          <NumberTicker value={current} format={format} locale={intl} reveal={false} />
          {unit && <span className="nx-precision-affix">{unit}</span>}
        </div>
      </div>
      <div className="nx-precision-ticks" aria-hidden="true">
        {Array.from({ length: intervals + 1 }, (_, i) => (
          <i key={i} style={{ '--p': i / intervals } as CSSProperties} data-major={i % major === 0 ? '' : undefined} />
        ))}
      </div>
      <Slider id={id} min={min} max={max} step={step} value={current} onValueChange={setCurrent} formatValue={text} {...rest} />
      <div className="nx-precision-scale" aria-hidden="true">
        <span>{text(min)}</span>
        <span>{text(max)}</span>
      </div>
    </div>
  );
}
