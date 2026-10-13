import {
  type CSSProperties,
  type ElementType,
  type HTMLAttributes,
  type ReactNode,
  useEffect,
  useMemo,
  useRef,
  useState,
} from 'react';
import { type SplitBy, glyphs, localeDigits, numberParts, prefersReducedMotion, reveal, scramble, splitText, textDirection } from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { useLocale } from '../internal/provider';

type Polymorphic<P> = P & Omit<HTMLAttributes<HTMLElement>, keyof P> & { as?: ElementType };

/* ---- Reveal ----------------------------------------------------------------- */

export type RevealVariant = 'rise' | 'fade' | 'scale' | 'blur' | 'start' | 'end';

export interface RevealOptions {
  /** How it arrives. */
  variant?: RevealVariant;
  /** Stagger direct children (each gets --nx-i). */
  stagger?: boolean;
  /** Extra delay in ms. */
  delay?: number;
  /** Reveal again each time it re-enters the view. */
  repeat?: boolean;
}

/** Reveal the element when it scrolls into view. */
export function useReveal(ref: React.RefObject<Element | null>, { stagger, repeat }: Pick<RevealOptions, 'stagger' | 'repeat'> = {}, enabled = true) {
  useBehavior(ref, reveal, { stagger, once: !repeat }, enabled);
}

export function Reveal({ as: Tag = 'div', variant = 'rise', stagger, delay, repeat, className, style, children, ...rest }: Polymorphic<RevealOptions & { children?: ReactNode }>) {
  const ref = useRef<HTMLElement>(null);
  useReveal(ref, { stagger, repeat });
  return (
    <Tag
      ref={ref}
      className={className}
      data-nx-reveal={stagger ? 'group' : variant}
      style={{ ...(delay ? { '--nx-delay': `${delay}ms` } : null), ...style } as CSSProperties}
      {...rest}
    >
      {children}
    </Tag>
  );
}

/* ---- Gradient & shimmer ----------------------------------------------------- */

export interface GradientTextProps {
  variant?: 'text' | 'brand' | 'gold';
  /** Let the gradient drift slowly. */
  animate?: boolean;
  children: ReactNode;
}

export function GradientText({ as: Tag = 'span', variant = 'text', animate, className, children, ...rest }: Polymorphic<GradientTextProps>) {
  return (
    <Tag className={cx('nx-gradient-text', className)} data-variant={variant === 'text' ? undefined : variant} data-animate={animate ? '' : undefined} {...rest}>
      {children}
    </Tag>
  );
}

export interface ShimmerTextProps {
  children: string;
  /** A band of light passing over (sweep) or letters rising in a wave. */
  variant?: 'sweep' | 'wave';
  /** Seconds per pass. */
  duration?: number;
}

export function ShimmerText({ as: Tag = 'span', variant = 'sweep', duration, className, style, children, ...rest }: Polymorphic<ShimmerTextProps>) {
  const pieces = useMemo(() => (variant === 'wave' ? splitText(children, 'char') : []), [children, variant]);
  const vars = { ...(duration ? { '--nx-shimmer-duration': `${duration}s` } : null), ...style } as CSSProperties;

  if (variant === 'sweep') {
    return (
      <Tag className={cx('nx-shimmer', className)} style={vars} {...rest}>
        {children}
      </Tag>
    );
  }

  return (
    <Tag className={cx('nx-shimmer', className)} data-variant="wave" style={vars} {...rest}>
      <span className="nx-visually-hidden">{children}</span>
      <span aria-hidden="true" dir={textDirection(children)}>
        {pieces.map((piece, i) => (
          <span key={i} className="nx-text-piece" style={{ '--nx-i': i } as CSSProperties}>
            {piece}
          </span>
        ))}
      </span>
    </Tag>
  );
}

/* ---- Text reveal ---------------------------------------------------------- */

export interface TextRevealProps {
  children: string;
  /** Split into words or characters (Persian and Arabic always split by word). */
  by?: SplitBy;
  /** Play once when it enters the view, or brighten word by word as it scrolls. */
  trigger?: 'view' | 'scroll';
  /** Delay before the first piece, ms. */
  delay?: number;
}

export function TextReveal({ as: Tag = 'span', by = 'word', trigger = 'view', delay, className, style, children, ...rest }: Polymorphic<TextRevealProps>) {
  const ref = useRef<HTMLElement>(null);
  const pieces = useMemo(() => splitText(children, by), [children, by]);
  useBehavior(ref, reveal, { once: true }, trigger === 'view');

  return (
    <Tag
      ref={ref}
      className={cx('nx-text-reveal', className)}
      data-by={by}
      data-trigger={trigger}
      data-nx-reveal={trigger === 'view' ? '' : undefined}
      style={{ ...(delay ? { '--nx-delay': `${delay}ms` } : null), ...style } as CSSProperties}
      {...rest}
    >
      <span className="nx-visually-hidden">{children}</span>
      {/* Pieces are boxes, laid out in their container's direction: give it the text's own. */}
      <span className="nx-text-pieces" aria-hidden="true" dir={textDirection(children)}>
        {pieces.map((piece, i) => (
          <span key={i} className="nx-text-piece" style={{ '--nx-i': i } as CSSProperties}>
            {piece}
          </span>
        ))}
      </span>
    </Tag>
  );
}

/* ---- Scramble -------------------------------------------------------------- */

export interface ScrambleTextProps {
  children: string;
  /** When to decode: on mount, when scrolled into view, or on hover. */
  trigger?: 'mount' | 'view' | 'hover';
  duration?: number;
  /** Glyphs to cycle: latin, persian, cuneiform, or your own string. */
  charset?: keyof typeof glyphs | string;
}

export function ScrambleText({ as: Tag = 'span', trigger = 'view', duration, charset, className, children, onPointerEnter, ...rest }: Polymorphic<ScrambleTextProps>) {
  const host = useRef<HTMLElement>(null);
  const text = useRef<HTMLSpanElement>(null);
  const stop = useRef<() => void>(() => {});

  const run = () => {
    if (!text.current) return;
    stop.current();
    stop.current = scramble(text.current, children, { duration, charset });
  };

  useEffect(() => {
    if (trigger === 'mount') run();
    if (trigger !== 'view' || !host.current) return () => stop.current();
    const observer = new IntersectionObserver(([entry]) => {
      if (entry?.isIntersecting) {
        run();
        observer.disconnect();
      }
    }, { threshold: 0.4 });
    observer.observe(host.current);
    return () => {
      observer.disconnect();
      stop.current();
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [children, trigger]);

  return (
    <Tag
      ref={host}
      className={cx('nx-scramble', className)}
      onPointerEnter={(event: React.PointerEvent<HTMLElement>) => {
        if (trigger === 'hover') run();
        onPointerEnter?.(event);
      }}
      {...rest}
    >
      <span className="nx-visually-hidden">{children}</span>
      <span ref={text} aria-hidden="true">
        {children}
      </span>
    </Tag>
  );
}

/* ---- Rotating words ------------------------------------------------------------ */

export interface WordRotateProps {
  words: string[];
  /** Time each word stays, ms. */
  interval?: number;
  className?: string;
  style?: CSSProperties;
}

export function WordRotate({ words, interval = 2600, className, style }: WordRotateProps) {
  const [index, setIndex] = useState(0);
  const [previous, setPrevious] = useState<number | null>(null);
  const [width, setWidth] = useState<number | null>(null);
  const [paused, setPaused] = useState(false);
  const current = useRef<HTMLSpanElement>(null);

  useEffect(() => {
    if (words.length < 2 || paused) return;
    const id = setInterval(() => {
      if (document.hidden) return;
      setIndex((i) => {
        setPrevious(i);
        return (i + 1) % words.length;
      });
    }, interval);
    return () => clearInterval(id);
  }, [words.length, interval, paused]);

  useEffect(() => {
    if (current.current) setWidth(current.current.scrollWidth);
  }, [index, words]);

  return (
    <span
      className={cx('nx-word-rotate', className)}
      style={{ ...(width ? { '--nx-word-width': `${width}px` } : null), ...style } as CSSProperties}
      onPointerEnter={() => setPaused(true)}
      onPointerLeave={() => setPaused(false)}
    >
      <span className="nx-visually-hidden">{words.join('، ')}</span>
      {previous !== null && previous !== index && (
        <span key={`out-${previous}-${index}`} className="nx-word-rotate-word" data-state="exit" aria-hidden="true" onAnimationEnd={() => setPrevious(null)}>
          {words[previous]}
        </span>
      )}
      <span key={`in-${index}`} ref={current} className="nx-word-rotate-word" data-state={previous === null && index === 0 ? undefined : 'enter'} aria-hidden="true">
        {words[index]}
      </span>
    </span>
  );
}

/* ---- Rolling number ---------------------------------------------------------- */

const LOCALES = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

export interface NumberTickerProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'children'> {
  value: number;
  /** An Intl locale; defaults to the provider's language. */
  locale?: string;
  format?: Intl.NumberFormatOptions;
  /** Roll up from zero when it scrolls into view (default) or show the value at once. */
  reveal?: boolean;
  /** Roll duration, ms. */
  duration?: number;
}

export function NumberTicker({ value, locale, format, reveal: rollIn = true, duration, className, style, ...rest }: NumberTickerProps) {
  const language = useLocale();
  const intlLocale = locale ?? LOCALES[language];
  const ref = useRef<HTMLSpanElement>(null);
  const parts = useMemo(() => numberParts(value, intlLocale, format), [value, intlLocale, format]);
  const digits = useMemo(() => localeDigits(intlLocale), [intlLocale]);
  const text = useMemo(() => parts.map((p) => p.char).join(''), [parts]);
  useBehavior(ref, reveal, { once: true }, rollIn);

  return (
    <span
      ref={ref}
      className={cx('nx-number', className)}
      data-nx-reveal={rollIn ? '' : undefined}
      style={{ ...(duration ? { '--nx-number-duration': `${duration}ms` } : null), ...style } as CSSProperties}
      {...rest}
    >
      <span className="nx-visually-hidden">{text}</span>
      <span className="nx-number-roll" aria-hidden="true">
        {parts.map((part, i) => {
          // Keyed from the right so the ones column stays the ones column as the number grows.
          const key = parts.length - i;
          return part.kind === 'digit' ? (
            <span key={key} className="nx-digit" style={{ '--d': part.value, '--nx-p': parts.length - 1 - i } as CSSProperties}>
              <span className="nx-digit-track">
                {digits.map((digit) => (
                  <span key={digit}>{digit}</span>
                ))}
              </span>
            </span>
          ) : (
            <span key={key} className="nx-number-sep">
              {part.char}
            </span>
          );
        })}
      </span>
    </span>
  );
}

/* ---- Highlight ----------------------------------------------------------------- */

const MARKS = {
  underline: 'M3 13 C 45 6, 90 5, 130 8 S 185 13, 197 9',
  circle: 'M104 3 C 44 2, 3 7, 4 12 C 5 17, 58 19, 108 18 C 162 17, 197 14, 196 9 C 195 4, 150 1, 88 5',
} as const;

export interface HighlightProps extends HTMLAttributes<HTMLSpanElement> {
  variant?: 'underline' | 'circle' | 'marker';
  /** Any CSS colour; the brand gold by default. */
  color?: string;
  delay?: number;
}

/** A hand-drawn underline, circle or marker that draws itself when revealed. */
export function Highlight({ variant = 'underline', color, delay, className, style, children, ...rest }: HighlightProps) {
  const ref = useRef<HTMLSpanElement>(null);
  useBehavior(ref, reveal, { once: true }, !prefersReducedMotionSafe());

  return (
    <span
      ref={ref}
      className={cx('nx-highlight', className)}
      data-variant={variant}
      data-nx-reveal=""
      style={{ ...(color ? { '--nx-highlight-color': color } : null), ...(delay ? { '--nx-delay': `${delay}ms` } : null), ...style } as CSSProperties}
      {...rest}
    >
      {children}
      {variant !== 'marker' && (
        <svg className="nx-highlight-mark" viewBox="0 0 200 20" preserveAspectRatio="none" aria-hidden="true">
          <path d={MARKS[variant]} pathLength={1} />
        </svg>
      )}
    </span>
  );
}

function prefersReducedMotionSafe() {
  return typeof window !== 'undefined' && prefersReducedMotion();
}
