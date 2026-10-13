/**
 * Hover effects (React): the image trail, the eight link-underline variants,
 * the glare card, the magnetic set and the image accordion. Thin over
 * css/blocks/hover-fx.css and the core behaviours in js/blocks/hover-fx.ts —
 * the components render the markup and own the state.
 */
import {
  type AnchorHTMLAttributes,
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent,
  type ReactNode,
  type Ref,
  forwardRef,
  useRef,
} from 'react';
import { direction, glareCard, imageTrail, magneticField, nextIndex, splitText } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink } from '../internal/provider';

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/* ---- Image trail ------------------------------------------------------------------ */

export interface ImageTrailItem {
  id: string;
  /** Where the row goes. */
  href: string;
  /** The project's name — the row's own text. */
  title: ReactNode;
  /** The small discipline note after the title. */
  kind?: ReactNode;
}

export interface ImageTrailScene {
  /** The artwork as an image URL. */
  src?: string;
  /** The artwork as markup (an svg, a canvas) instead of an image. */
  art?: ReactNode;
}

export interface ImageTrailProps extends HTMLAttributes<HTMLDivElement> {
  /** The rows the chain flies over; each row is a link that gathers the chain on focus. */
  items: ImageTrailItem[];
  /** The artworks the chain cards carry, cycled along the chain. */
  images: ImageTrailScene[];
  /** How many cards ride the chain (default 9). */
  count?: number;
  /** The second column — hotspots, a mark, anything the stage should hold. */
  aside?: ReactNode;
}

/**
 * A stage whose image cards chase the pointer: card 0 follows the hand and
 * every card after it lerps toward its predecessor, so the fan opens with
 * movement and fades card by card once the hand rests. Focusing a row
 * gathers the chain around it — one static card beside it under reduced
 * motion — so the effect is never pointer-only.
 */
export const ImageTrail = forwardRef<HTMLDivElement, ImageTrailProps>(function ImageTrail(
  { items, images, count = 9, aside, className, ...rest },
  ref,
) {
  const own = useRef<HTMLDivElement>(null);
  useBehavior(own, imageTrail, {});
  const total = images.length > 0 ? Math.max(1, count) : 0;

  return (
    <div ref={mergeRefs(ref, own)} className={cx('nx-image-trail', className)} {...rest}>
      <ul className="nx-image-trail-list">
        {items.map((item, index) => (
          <li key={item.id} className="nx-image-trail-row">
            <span className="nx-image-trail-no">{String(index + 1).padStart(2, '0')}</span>
            <SmartLink href={item.href} className="nx-image-trail-link" data-trail-pin="">
              {item.title}
              {item.kind !== undefined && <small className="nx-image-trail-kind">{item.kind}</small>}
            </SmartLink>
          </li>
        ))}
      </ul>
      {aside !== undefined && <div className="nx-image-trail-aside">{aside}</div>}
      {Array.from({ length: total }, (_, index) => {
        const scene = images[index % images.length]!;
        return (
          <div key={index} className="nx-image-trail-card" aria-hidden="true">
            {scene.art ?? (scene.src !== undefined ? <img src={scene.src} alt="" loading="lazy" /> : null)}
          </div>
        );
      })}
    </div>
  );
});

/* ---- Link underline ---------------------------------------------------------------- */

export type LinkUnderlineVariant = 'center' | 'brackets' | 'arrow' | 'swap' | 'marker' | 'circle' | 'wave' | 'icon';

export interface LinkUnderlineProps extends AnchorHTMLAttributes<HTMLAnchorElement> {
  /** The hover language; see css/blocks/hover-fx.css (default 'center'). */
  variant?: LinkUnderlineVariant;
  href: string;
  /** The link's text; `swap` and `wave` split it, so a plain string works best. */
  children: ReactNode;
}

/** Split into words of units for the wave: letters for Latin, whole words for joined scripts. */
function waveWords(text: string): string[][] {
  const words: string[][] = [];
  let current: string[] = [];
  for (const piece of splitText(text, 'char')) {
    const unit = piece.trim();
    if (!unit) {
      if (current.length) words.push(current);
      current = [];
      continue;
    }
    current.push(unit);
    if (piece !== piece.trimEnd()) {
      words.push(current);
      current = [];
    }
  }
  if (current.length) words.push(current);
  return words;
}

/**
 * One link, eight hover languages: a centre-out underline, drawn brackets, a
 * sliding arrow that makes room, a two-layer text roll, a marker highlight, a
 * bouncing circle behind the text, a per-unit wave and an icon that shifts
 * through its clipped window. Everything is CSS; every effect answers
 * :focus-visible exactly like :hover and collapses to its colour change
 * under reduced motion.
 */
export const LinkUnderline = forwardRef<HTMLAnchorElement, LinkUnderlineProps>(function LinkUnderline(
  { variant = 'center', href, children, className, ...rest },
  ref,
) {
  const text = typeof children === 'string' ? children : '';

  let inner: ReactNode = children;
  if (variant === 'arrow') {
    inner = (
      <>
        {children}
        <Icon name="arrow-right" />
      </>
    );
  } else if (variant === 'swap' && text) {
    inner = (
      <span className="nx-link-underline-swap" data-text={text}>
        <span className="nx-link-underline-swap-a">{children}</span>
      </span>
    );
  } else if (variant === 'wave' && text) {
    let index = 0;
    const nodes: ReactNode[] = [];
    waveWords(text).forEach((units, word) => {
      if (word > 0) nodes.push(' ');
      nodes.push(
        <span key={word} className="nx-link-underline-wave-word">
          {units.map((unit) => {
            const i = index++;
            return (
              <span key={i} className="nx-link-underline-wave-unit" style={vars({ '--nx-i': i })}>
                {unit}
              </span>
            );
          })}
        </span>,
      );
    });
    inner = nodes;
  } else if (variant === 'icon') {
    inner = (
      <>
        {children}
        <span className="nx-link-underline-window" aria-hidden="true">
          <span className="nx-link-underline-strip">
            <Icon name="arrow-right" />
            <Icon name="arrow-right" />
          </span>
        </span>
      </>
    );
  }

  return (
    <SmartLink
      ref={ref}
      href={href}
      className={cx('nx-link-underline', className)}
      data-variant={variant}
      {...rest}
    >
      {inner}
    </SmartLink>
  );
});

/* ---- Glare card -------------------------------------------------------------------- */

export interface GlareCardProps extends HTMLAttributes<HTMLDivElement> {
  /** The printed scene behind everything (an image or an svg); decorative. */
  art?: ReactNode;
  /** Rides the deepest parallax layer — the freeze button, the call to act. */
  actions?: ReactNode;
  /** The slab's accessible name; giving one also makes it focusable (default none). */
  label?: string;
  /** Largest tilt in degrees (default 10). */
  maxTilt?: number;
  /** Switch the glare and parallax off where they would distract. */
  static?: boolean;
  /** The body: the brand block, the embossed line, the holder row. */
  children?: ReactNode;
}

/**
 * A slab that catches the light: the glare halo rotates toward the pointer
 * (atan2 of its angle around the centre) while the scene, the body and the
 * actions each drift a different distance toward the hand. The lerp loop
 * only runs while the pointer is over the card or the card is settling
 * back, and parks while the card is off screen. Focus poses the card, a tap
 * sweeps the glare across once, and under reduced motion a fixed soft sheen
 * replaces the chase.
 */
export const GlareCard = forwardRef<HTMLDivElement, GlareCardProps>(function GlareCard(
  { art, actions, label, maxTilt = 10, static: still = false, children, className, ...rest },
  ref,
) {
  const own = useRef<HTMLDivElement>(null);
  useBehavior(own, glareCard, { maxTilt }, !still);

  return (
    <div
      ref={mergeRefs(ref, own)}
      className={cx('nx-glare-card', className)}
      role={label ? 'group' : undefined}
      aria-label={label}
      tabIndex={label ? 0 : undefined}
      data-static={still ? '' : undefined}
      {...rest}
    >
      {art !== undefined && (
        <div className="nx-glare-art" aria-hidden="true">
          {art}
        </div>
      )}
      <div className="nx-glare-sheen" aria-hidden="true" />
      {children !== undefined && <div className="nx-glare-body">{children}</div>}
      {actions !== undefined && <div className="nx-glare-actions">{actions}</div>}
    </div>
  );
});

/* ---- Magnetic set ------------------------------------------------------------------ */

export interface MagneticSetProps extends HTMLAttributes<HTMLDivElement> {
  /** Pull strength, 0–1 (default 0.5). Tunable live: the field reads it every frame. */
  strength?: number;
  /** Field radius in px (default 130). Tunable live, like the strength. */
  radius?: number;
  /** The controls of the field — wrap each one in a MagneticItem. */
  children?: ReactNode;
}

/**
 * A field of controls that lean toward the hand: while the pointer is inside
 * the radius every item is drawn toward it with distance falloff, and when
 * the hand leaves, a stiffness/damping spring carries each one home with a
 * little overshoot. Keyboard focus poses the focused item; the loop parks
 * off screen; under reduced motion the field is simply off.
 */
export const MagneticSet = forwardRef<HTMLDivElement, MagneticSetProps>(function MagneticSet(
  { strength = 0.5, radius = 130, style, className, children, ...rest },
  ref,
) {
  const own = useRef<HTMLDivElement>(null);
  useBehavior(own, magneticField, {});

  return (
    <div
      ref={mergeRefs(ref, own)}
      className={cx('nx-magnetic-set', className)}
      style={{ ...vars({ '--nx-field-r': `${radius}px`, '--nx-field-k': strength }), ...style }}
      {...rest}
    >
      {children}
    </div>
  );
});

export interface MagneticItemProps extends HTMLAttributes<HTMLElement> {
  /** The largest pull this item may feel, px (default 24). */
  pull?: number;
  /** Where the item poses while it holds keyboard focus, as "x y" px (default none). */
  pose?: [number, number];
  as?: 'span' | 'div';
  children?: ReactNode;
}

/** One magnetic control: wraps anything pressable and carries the field's translate. */
export const MagneticItem = forwardRef<HTMLElement, MagneticItemProps>(function MagneticItem(
  { pull, pose, as = 'span', className, children, ...rest },
  ref,
) {
  const Tag = as as 'span';
  return (
    <Tag
      ref={ref as Ref<HTMLSpanElement>}
      className={cx('nx-magnetic-item', className)}
      data-pull={pull !== undefined ? String(pull) : undefined}
      data-pose={pose ? pose.join(' ') : undefined}
      {...rest}
    >
      {children}
    </Tag>
  );
});

/* ---- Image accordion ---------------------------------------------------------------- */

export interface ImageAccordionItem {
  id: string;
  /** The panel's title; rises in from the fold when the panel opens. */
  title: ReactNode;
  /** The line under the title. */
  caption?: ReactNode;
  /** The scene's image; decorative — the title carries the content. */
  image?: string;
  /** A scene of your own (an svg, a canvas) instead of an image. */
  art?: ReactNode;
}

export interface ImageAccordionProps extends HTMLAttributes<HTMLDivElement> {
  items: ImageAccordionItem[];
  /** The open panel's index (controlled). */
  value?: number;
  defaultValue?: number;
  onValueChange?: (index: number) => void;
}

/**
 * A strip of scene panels sharing one width: the hovered, focused or tapped
 * panel grows to its open share on a flex-grow curve while the others fold
 * away — the open panel's number and title rise in from the fold and the
 * collapsed ones keep a vertical running number. Arrow keys walk the strip
 * (flipped for RTL), Home/End jump to the ends, and under reduced motion the
 * share change is instant. No RAF loop — flex-grow transitions only.
 */
export const ImageAccordion = forwardRef<HTMLDivElement, ImageAccordionProps>(function ImageAccordion(
  { items, value, defaultValue = 0, onValueChange, className, ...rest },
  ref,
) {
  const [current, setCurrent] = useControllable(value, defaultValue, onValueChange);
  const root = useRef<HTMLDivElement>(null);
  const panels = useRef<Array<HTMLButtonElement | null>>([]);

  const open = (index: number) => setCurrent(Math.min(Math.max(0, index), items.length - 1));

  const walk = (event: KeyboardEvent<HTMLButtonElement>, index: number) => {
    const container = root.current;
    if (!container) return;
    const target = nextIndex(event.key, index, items.length, { orientation: 'horizontal', loop: false }, direction(container));
    if (target === null || target === index) return;
    event.preventDefault();
    open(target);
    panels.current[target]?.focus();
  };

  return (
    <div
      ref={mergeRefs(ref, root)}
      className={cx('nx-image-accordion', className)}
      role="group"
      {...rest}
    >
      {items.map((item, index) => {
        const isOpen = index === current;
        return (
          <button
            key={item.id}
            ref={(el) => {
              panels.current[index] = el;
            }}
            type="button"
            className="nx-image-accordion-panel"
            data-open={isOpen ? '' : undefined}
            aria-expanded={isOpen}
            onPointerEnter={() => open(index)}
            onClick={() => open(index)}
            onFocus={() => open(index)}
            onKeyDown={(event) => walk(event, index)}
          >
            <span className="nx-image-accordion-scene" aria-hidden="true">
              {item.art ?? (item.image !== undefined ? <img src={item.image} alt="" loading="lazy" /> : null)}
            </span>
            <span className="nx-image-accordion-veil" aria-hidden="true" />
            <span className="nx-image-accordion-runner" aria-hidden="true">
              {index + 1}
            </span>
            <span className="nx-image-accordion-fold">
              <span className="nx-image-accordion-title-wrap">
                <span className="nx-image-accordion-no">{String(index + 1).padStart(2, '0')}</span>
                <span className="nx-image-accordion-title">{item.title}</span>
                {item.caption !== undefined && <span className="nx-image-accordion-caption">{item.caption}</span>}
              </span>
            </span>
          </button>
        );
      })}
    </div>
  );
});
