/**
 * Text & hero motion (React): the text hero with stickers, the scroll text
 * reveal, hover-reveal links, the scroll scramble, roll text, the pulse button
 * and the dither backdrop. Thin over the core CSS and behaviours.
 */
import {
  type AnchorHTMLAttributes,
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  Fragment,
  forwardRef,
  useMemo,
  useRef,
} from 'react';
import {
  type DitherOptions,
  type GlyphPiece,
  type GlyphSplit,
  type IconName,
  type StickerPosition,
  type StickerShape,
  type StickerTone,
  dither,
  glyphs,
  hoverReveal,
  magnetic,
  reveal,
  scatterGlyphs,
  scrollScramble,
  splitGlyphs,
  stickerArt,
  stickyProgress,
} from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink } from '../internal/provider';

export type { StickerPosition, StickerShape, StickerTone };

type Heading = 'h1' | 'h2' | 'h3' | 'p';

/**
 * Split letters, a word at a time: a word never breaks across lines and a word
 * written the other way keeps its own direction (see the core's splitGlyphs).
 */
function Glyphs({ split, render }: { split: GlyphSplit; render: (piece: GlyphPiece) => ReactNode }) {
  return (
    <>
      {split.words.map((word, w) => (
        <Fragment key={w}>
          {word.pieces.length > 0 && (
            <span className="nx-glyph-word" dir={word.dir ?? undefined} data-loose={word.loose ? '' : undefined}>
              {word.pieces.map((piece) => (
                <Fragment key={piece.index}>{render(piece)}</Fragment>
              ))}
            </span>
          )}
          {word.space}
        </Fragment>
      ))}
    </>
  );
}

/* ---- Text hero ---------------------------------------------------------------- */

export interface TextHeroSticker {
  shape: StickerShape;
  position: StickerPosition;
  /** Each shape has its own colour unless you pick one. */
  tone?: StickerTone;
}

export interface TextHeroProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** The headline: its letters spring up one by one (Persian and Arabic: word by word). */
  title: string;
  /** Part of the title painted with the brand gradient (whole words read best). */
  highlight?: string;
  subtitle?: ReactNode;
  actions?: ReactNode;
  /** Decorative stickers that pop in around the title, then float. */
  stickers?: TextHeroSticker[];
  align?: 'center' | 'start';
  /** The heading element (h1 unless the hero sits lower on the page). */
  as?: 'h1' | 'h2';
}

function StickerMark({ shape, position, tone, index }: TextHeroSticker & { index: number }) {
  const art = stickerArt[shape];
  return (
    <span
      className="nx-text-hero-sticker"
      data-shape={shape}
      data-position={position}
      data-tone={tone ?? art.tone}
      style={{ '--nx-i': index } as CSSProperties}
      aria-hidden="true"
    >
      <svg viewBox="0 0 64 64" focusable="false">
        {art.line ? (
          <>
            <path data-part="outline" d={art.body} />
            <path data-part="line" d={art.body} />
          </>
        ) : (
          <path data-part="body" d={art.body} />
        )}
        {art.ink && <path data-part="ink" d={art.ink} />}
        {art.shine && <path data-part="shine" d={art.shine} />}
      </svg>
    </span>
  );
}

/** A bold hero headline whose letters spring up one by one, with stickers popping in around it. */
export function TextHero({ title, highlight, subtitle, actions, stickers = [], align = 'center', as: Tag = 'h1', className, style, children, ...rest }: TextHeroProps) {
  const ref = useRef<HTMLElement>(null);
  const split = useMemo(() => splitGlyphs(title, highlight), [title, highlight]);
  useBehavior(ref, reveal, { once: true, threshold: 0.2 });

  return (
    <section
      ref={ref}
      className={cx('nx-hero nx-text-hero', className)}
      data-align={align === 'start' ? 'start' : undefined}
      data-shaped={split.shaped ? '' : undefined}
      data-nx-reveal=""
      style={{ '--_n': split.count, '--_m': split.marked, ...style } as CSSProperties}
      {...rest}
    >
      <div className="nx-hero-content">
        <div className="nx-text-hero-heading">
          <Tag className="nx-hero-title nx-text-hero-title">
            <span className="nx-visually-hidden">{title}</span>
            <span aria-hidden="true" dir={split.dir}>
              <Glyphs
                split={split}
                render={(piece) => (
                  <span
                    className="nx-text-hero-char"
                    data-mark={piece.mark >= 0 ? '' : undefined}
                    style={{ '--nx-i': piece.index, ...(piece.mark >= 0 ? { '--_k': piece.mark } : null) } as CSSProperties}
                  >
                    {piece.text}
                  </span>
                )}
              />
            </span>
          </Tag>
          {stickers.map((sticker, i) => (
            <StickerMark key={i} {...sticker} index={i} />
          ))}
        </div>
        {subtitle && <p className="nx-hero-subtitle nx-text-hero-subtitle">{subtitle}</p>}
        {actions && <div className="nx-hero-actions nx-text-hero-actions">{actions}</div>}
        {children}
      </div>
    </section>
  );
}

/* ---- Scroll text reveal ---------------------------------------------------------- */

export interface ScrollTextRevealProps extends HTMLAttributes<HTMLElement> {
  text: string;
  /** A small line above the text. */
  eyebrow?: ReactNode;
  /** How far the section scrolls; its stage stays pinned for all but one screen of it. */
  height?: string;
  /** Another number scatters the letters another way. */
  seed?: number;
  as?: Heading;
}

/**
 * Oversized text on a pinned stage: scattered letters gather as you scroll and
 * the filled copy wipes in over the outline. CSS scroll-driven animations drive
 * --nx-progress; stickyProgress() writes it where they are missing.
 */
export function ScrollTextReveal({ text, eyebrow, height = '240vh', seed = 1, as: Tag = 'h2', className, style, ...rest }: ScrollTextRevealProps) {
  const ref = useRef<HTMLElement>(null);
  const split = useMemo(() => splitGlyphs(text), [text]);
  const offsets = useMemo(() => scatterGlyphs(split.count, seed), [split.count, seed]);
  useBehavior(ref, stickyProgress, {});

  const layer = (name: 'outline' | 'fill') => (
    <span className="nx-scroll-reveal-layer" data-layer={name} dir={split.dir}>
      <Glyphs
        split={split}
        render={(piece) => {
          const at = offsets[piece.index]!;
          return (
            <span className="nx-scroll-reveal-char" style={{ '--nx-i': piece.index, '--_x': at.x, '--_y': at.y, '--_r': at.r } as CSSProperties}>
              {piece.text}
            </span>
          );
        }}
      />
    </span>
  );

  return (
    <section ref={ref} className={cx('nx-scroll-reveal', className)} style={{ '--nx-scroll-height': height, ...style } as CSSProperties} {...rest}>
      <div className="nx-scroll-reveal-stage">
        {eyebrow && <p className="nx-scroll-reveal-eyebrow">{eyebrow}</p>}
        <Tag className="nx-scroll-reveal-title" data-shaped={split.shaped ? '' : undefined}>
          <span className="nx-visually-hidden">{text}</span>
          <span className="nx-scroll-reveal-art" aria-hidden="true" style={{ '--_n': split.count } as CSSProperties}>
            {layer('outline')}
            {layer('fill')}
          </span>
        </Tag>
      </div>
    </section>
  );
}

/* ---- Hover reveal ----------------------------------------------------------------- */

export interface HoverRevealItem {
  label: string;
  href: string;
  description?: ReactNode;
}

export interface HoverRevealProps extends HTMLAttributes<HTMLUListElement> {
  items: HoverRevealItem[];
  /** No press or hover scaling. */
  static?: boolean;
}

/** Large links: the hovered one glows where the pointer is and its arrow turns; the others fade and drift away. */
export function HoverReveal({ items, static: isStatic, className, ...rest }: HoverRevealProps) {
  const ref = useRef<HTMLUListElement>(null);
  useBehavior(ref, hoverReveal, {});

  return (
    <ul ref={ref} className={cx('nx-hover-reveal', className)} role="list" data-static={isStatic ? '' : undefined} {...rest}>
      {items.map((item, i) => (
        <li key={`${item.href}-${i}`} className="nx-hover-reveal-item">
          <SmartLink className="nx-hover-reveal-link" href={item.href}>
            <span className="nx-hover-reveal-label" dir="auto">
              {item.label}
            </span>
            {item.description && <span className="nx-hover-reveal-description">{item.description}</span>}
            <span className="nx-hover-reveal-icon" aria-hidden="true">
              <Icon name="trend-up" className="nx-hover-reveal-rest" />
              <Icon name="arrow-right" className="nx-hover-reveal-active" />
            </span>
          </SmartLink>
        </li>
      ))}
    </ul>
  );
}

/* ---- Scroll scramble ---------------------------------------------------------------- */

export interface ScrollScrambleProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** The headline that decodes as the section scrolls. */
  title: string;
  /** Chip labels that fly from a pile into a tidy row. */
  items?: string[];
  /** How far the section scrolls; its stage stays pinned for all but one screen of it. */
  height?: string;
  /** Glyphs to cycle: latin, persian, cuneiform or your own string (from the title's script by default). */
  charset?: keyof typeof glyphs | string;
  as?: Heading;
}

/** A pinned stage whose headline decodes in step with the scroll while chips fly out of a pile into place. */
export function ScrollScramble({ title, items = [], height = '220vh', charset, as: Tag = 'h2', className, style, ...rest }: ScrollScrambleProps) {
  const ref = useRef<HTMLElement>(null);
  const shaped = useMemo(() => splitGlyphs(title).shaped, [title]);
  useBehavior(ref, scrollScramble, { text: title, charset });

  return (
    <section ref={ref} className={cx('nx-scroll-scramble', className)} style={{ '--nx-scroll-height': height, ...style } as CSSProperties} {...rest}>
      <div className="nx-scroll-scramble-stage">
        <Tag className="nx-scroll-scramble-title" dir="auto" data-shaped={shaped ? '' : undefined}>
          <span className="nx-visually-hidden">{title}</span>
          <span className="nx-scroll-scramble-sizer" aria-hidden="true">
            {title}
          </span>
          <span className="nx-scroll-scramble-text" aria-hidden="true">
            {title}
          </span>
        </Tag>
        {items.length > 0 && (
          <ul className="nx-scroll-scramble-chips" role="list" style={{ '--_n': items.length } as CSSProperties}>
            {items.map((item, i) => (
              <li key={`${item}-${i}`} className="nx-scroll-scramble-chip" dir="auto" style={{ '--nx-i': i } as CSSProperties}>
                {item}
              </li>
            ))}
          </ul>
        )}
      </div>
    </section>
  );
}

/* ---- Roll text ------------------------------------------------------------------------- */

export interface RollTextProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  /** The label, as plain text. */
  children: string;
  /** Render a link. */
  href?: string;
  /** Without href: a button, or a span that rolls when its parent link or button is hovered. */
  as?: 'span' | 'button';
  type?: 'button' | 'submit' | 'reset';
  disabled?: boolean;
  target?: string;
  rel?: string;
  /** No press scaling. */
  static?: boolean;
}

/** Menu text: on hover each letter rolls up and out while its copy rolls in from below, in reading order. */
export const RollText = forwardRef<HTMLElement, RollTextProps>(function RollText({ children, href, as = 'span', type, disabled, static: isStatic, className, ...rest }, ref) {
  const split = useMemo(() => splitGlyphs(children), [children]);
  const content = (
    <>
      <span className="nx-visually-hidden">{children}</span>
      <span className="nx-roll-text-pieces" aria-hidden="true" dir={split.dir}>
        <Glyphs
          split={split}
          render={(piece) => (
            <span className="nx-roll-text-piece" style={{ '--nx-i': piece.index } as CSSProperties}>
              <span>{piece.text}</span>
              <span>{piece.text}</span>
            </span>
          )}
        />
      </span>
    </>
  );
  const shared = { className: cx('nx-roll-text', className), 'data-static': isStatic ? '' : undefined };

  if (href) {
    return (
      <SmartLink ref={ref as never} href={href} {...shared} {...(rest as AnchorHTMLAttributes<HTMLAnchorElement>)}>
        {content}
      </SmartLink>
    );
  }
  if (as === 'button') {
    return (
      <button ref={ref as never} type={type ?? 'button'} disabled={disabled} {...shared} {...rest}>
        {content}
      </button>
    );
  }
  return (
    <span ref={ref as never} {...shared} {...rest}>
      {content}
    </span>
  );
});

/* ---- Pulse button ------------------------------------------------------------------------ */

export interface PulseButtonProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  /** The icon in the disc. */
  icon?: IconName;
  /** A label under the disc. Without one, give the button an aria-label. */
  children?: ReactNode;
  href?: string;
  size?: 'sm' | 'md' | 'lg';
  tone?: 'accent' | 'gold' | 'inverse';
  type?: 'button' | 'submit' | 'reset';
  disabled?: boolean;
  target?: string;
  rel?: string;
  /** No magnetic pull, hover or press scaling. */
  static?: boolean;
}

/** A round call to action with rings pulsing out of it, pulled toward the pointer. */
export const PulseButton = forwardRef<HTMLElement, PulseButtonProps>(function PulseButton(
  { icon = 'arrow-right', children, href, size = 'md', tone = 'accent', type, disabled, static: isStatic, className, ...rest },
  ref,
) {
  const disc = useRef<HTMLSpanElement>(null);
  useBehavior(disc, magnetic, { strength: 0.3 }, !isStatic && !disabled);

  const content = (
    <>
      <span ref={disc} className="nx-pulse-button-disc">
        <span className="nx-pulse-button-rings" aria-hidden="true">
          <i />
          <i />
          <i />
        </span>
        <span className="nx-pulse-button-face">
          <Icon name={icon} />
        </span>
      </span>
      {children != null && children !== false && <span className="nx-pulse-button-label">{children}</span>}
    </>
  );
  const shared = {
    className: cx('nx-pulse-button', className),
    'data-size': size === 'md' ? undefined : size,
    'data-tone': tone === 'accent' ? undefined : tone,
    'data-static': isStatic ? '' : undefined,
  };

  if (href) {
    return (
      <SmartLink ref={ref as never} href={href} aria-disabled={disabled || undefined} {...shared} {...(rest as AnchorHTMLAttributes<HTMLAnchorElement>)}>
        {content}
      </SmartLink>
    );
  }
  return (
    <button ref={ref as never} type={type ?? 'button'} disabled={disabled} {...shared} {...rest}>
      {content}
    </button>
  );
});

/* ---- Dither backdrop ------------------------------------------------------------------------ */

export interface DitherBackdropProps extends DitherOptions {
  className?: string;
  style?: CSSProperties;
}

/**
 * An animated ordered-dither field in the theme's colours, drawn on a canvas.
 * Put it first inside a positioned section, like the other backdrops.
 */
export function DitherBackdrop({ cell, matrix, speed, fps, className, style }: DitherBackdropProps) {
  const canvas = useRef<HTMLCanvasElement>(null);
  useBehavior(canvas, dither, { cell, matrix, speed, fps });

  return (
    <div className={cx('nx-backdrop', className)} data-variant="dither" aria-hidden="true" style={style}>
      <canvas ref={canvas} className="nx-backdrop-canvas" />
    </div>
  );
}
