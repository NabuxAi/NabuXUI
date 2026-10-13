/**
 * 3D icons (React): hand-built inline-SVG icons — a soft body gradient, a
 * cooler accent gradient, a white edge highlight and an elliptical ground
 * shadow — plus the feature row that carries them. Every icon draws its own
 * gradients under ids from useId, so any number of them can share a page, and
 * one data-tone re-skins a whole set through the custom properties
 * css/blocks/icons.css sets. The optional tilt is the core tilt() behaviour;
 * this family needs no behaviour of its own.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useId,
  useRef,
} from 'react';
import { tilt } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink } from '../internal/provider';

/** The icons the set can draw, on a shared 64×64 grid. */
export type Icon3dName =
  | 'rocket'
  | 'chart'
  | 'shield'
  | 'cloud'
  | 'message'
  | 'gear'
  | 'lock'
  | 'camera'
  | 'battery'
  | 'search'
  | 'folder'
  | 'star'
  | 'bolt';

/** One palette for the body gradient; the accent stays cyan across tones. */
export type Icon3dTone = 'lapis' | 'violet' | 'gold';

export type Icon3dSize = 'sm' | 'md' | 'lg' | 'display';

/* The gradient ids one icon fills its paints with. */
interface Paint {
  body: string;
  accent: string;
  gloss: string;
  shadow: string;
}

/* Material highlights are plain white at varying strength — the same licence
   the image outline rule takes with pure black/white; everything thematic
   comes through the custom properties the CSS sets. */
const hi = (opacity: number) => `rgb(255 255 255 / ${opacity})`;

/**
 * The geometry, drawn from scratch per name. Every icon speaks one language:
 * body gradient for the mass, accent gradient for the working parts, a white
 * highlight where the light catches, and an elliptical shadow on the ground.
 */
const art: Record<Icon3dName, (p: Paint) => ReactNode> = {
  rocket: ({ body, accent, gloss, shadow }) => (
    <>
      <ellipse cx={32} cy={55} rx={14} ry={4} fill={`url(#${shadow})`} />
      <path d="M27.5 44.5h9c.5 3.6-1.5 6.7-4.5 9.7-3-3-5-6.1-4.5-9.7Z" fill={`url(#${accent})`} />
      <path d="M23.5 33c-5.4 3-8.4 8.3-8.7 14.7l8.7-5.7Z" fill={`url(#${accent})`} />
      <path d="M40.5 33c5.4 3 8.4 8.3 8.7 14.7l-8.7-5.7Z" fill={`url(#${accent})`} />
      <path
        d="M32 7.5c6.2 4.4 9.2 11.8 9.2 19.6v12.6a4.3 4.3 0 0 1-4.3 4.3h-9.8a4.3 4.3 0 0 1-4.3-4.3V27.1c0-7.8 3-15.2 9.2-19.6Z"
        fill={`url(#${body})`}
      />
      <circle cx={32} cy={26} r={5.4} fill={`url(#${accent})`} />
      <circle cx={30.5} cy={24.5} r={1.4} fill={hi(0.8)} />
      <ellipse cx={27.8} cy={20.5} rx={2} ry={5.5} transform="rotate(16 27.8 20.5)" fill={`url(#${gloss})`} />
    </>
  ),
  chart: ({ body, accent, shadow }) => (
    <>
      <ellipse cx={33} cy={55} rx={16} ry={4} fill={`url(#${shadow})`} />
      <polygon points="22,38 26,34 26,48 22,52" fill={`url(#${accent})`} />
      <polygon points="12,38 16,34 26,34 22,38" fill={hi(0.5)} />
      <rect x={12} y={38} width={10} height={14} rx={2} fill={`url(#${body})`} />
      <polygon points="36,28 40,24 40,48 36,52" fill={`url(#${accent})`} />
      <polygon points="26,28 30,24 40,24 36,28" fill={hi(0.5)} />
      <rect x={26} y={28} width={10} height={24} rx={2} fill={`url(#${body})`} />
      <polygon points="50,18 54,14 54,48 50,52" fill={`url(#${accent})`} />
      <polygon points="40,18 44,14 54,14 50,18" fill={hi(0.5)} />
      <rect x={40} y={18} width={10} height={34} rx={2} fill={`url(#${body})`} />
    </>
  ),
  shield: ({ body, shadow }) => (
    <>
      <ellipse cx={32} cy={54} rx={15} ry={4} fill={`url(#${shadow})`} />
      <path
        d="M32 8.5l17.5 6.3v13.9c0 11-7.1 19.3-17.5 24.5-10.4-5.2-17.5-13.5-17.5-24.5V14.8Z"
        fill={`url(#${body})`}
      />
      <path d="M25.5 29l5 5 9-9.5" fill="none" stroke={hi(0.9)} strokeWidth={4.3} strokeLinecap="round" strokeLinejoin="round" />
      <path d="M25 13l7-2.6 7 2.6" fill="none" stroke={hi(0.5)} strokeWidth={2.4} strokeLinecap="round" />
    </>
  ),
  cloud: ({ body, accent, shadow }) => (
    <>
      <ellipse cx={31} cy={52.5} rx={14} ry={3.8} fill={`url(#${shadow})`} />
      <path
        d="M18 43a7.2 7.2 0 0 1-1.4-14.2 12 12 0 0 1 23.3-3.3A8.7 8.7 0 0 1 43.5 43Z"
        fill={`url(#${body})`}
      />
      <path d="M25.5 27a9.2 9.2 0 0 1 8.7-4.3" fill="none" stroke={hi(0.55)} strokeWidth={2.8} strokeLinecap="round" />
      <path d="M21.5 43.2h21" fill="none" stroke={`url(#${accent})`} strokeWidth={3} strokeLinecap="round" opacity={0.4} />
    </>
  ),
  message: ({ body, accent, gloss, shadow }) => (
    <>
      <ellipse cx={32} cy={54.5} rx={15} ry={4} fill={`url(#${shadow})`} />
      <path d="M20.5 41.5 15 53l13.5-9.5Z" fill={`url(#${accent})`} />
      <rect x={12.5} y={12} width={39} height={31} rx={9.5} fill={`url(#${body})`} />
      <circle cx={24} cy={28} r={3} fill={`url(#${accent})`} />
      <circle cx={32} cy={28} r={3} fill={`url(#${accent})`} />
      <circle cx={40} cy={28} r={3} fill={`url(#${accent})`} />
      <circle cx={23.1} cy={27.1} r={0.9} fill={hi(0.8)} />
      <circle cx={31.1} cy={27.1} r={0.9} fill={hi(0.8)} />
      <rect x={16.5} y={16} width={20} height={6} rx={3} fill={`url(#${gloss})`} opacity={0.7} />
    </>
  ),
  gear: ({ body, accent, shadow }) => (
    <>
      <ellipse cx={32} cy={55} rx={14} ry={4} fill={`url(#${shadow})`} />
      <g fill={`url(#${body})`}>
        <rect x={29.2} y={9.5} width={5.6} height={11.5} rx={2.8} />
        <rect x={29.2} y={9.5} width={5.6} height={11.5} rx={2.8} transform="rotate(45 32 32)" />
        <rect x={29.2} y={9.5} width={5.6} height={11.5} rx={2.8} transform="rotate(90 32 32)" />
        <rect x={29.2} y={9.5} width={5.6} height={11.5} rx={2.8} transform="rotate(135 32 32)" />
        <rect x={29.2} y={9.5} width={5.6} height={11.5} rx={2.8} transform="rotate(180 32 32)" />
        <rect x={29.2} y={9.5} width={5.6} height={11.5} rx={2.8} transform="rotate(225 32 32)" />
        <rect x={29.2} y={9.5} width={5.6} height={11.5} rx={2.8} transform="rotate(270 32 32)" />
        <rect x={29.2} y={9.5} width={5.6} height={11.5} rx={2.8} transform="rotate(315 32 32)" />
      </g>
      <circle cx={32} cy={32} r={13.5} fill={`url(#${body})`} />
      <circle cx={32} cy={32} r={5.2} fill={`url(#${accent})`} />
      <circle cx={30.4} cy={30.4} r={1.5} fill={hi(0.85)} />
      <path d="M22.5 24.5a12.8 12.8 0 0 1 6.8-6.2" fill="none" stroke={hi(0.5)} strokeWidth={2.8} strokeLinecap="round" />
    </>
  ),
  lock: ({ body, accent, gloss, shadow }) => (
    <>
      <ellipse cx={32} cy={53.5} rx={14} ry={3.8} fill={`url(#${shadow})`} />
      <path d="M23.5 28v-5.5a8.5 8.5 0 0 1 17 0V28" fill="none" stroke={`url(#${accent})`} strokeWidth={6.2} strokeLinecap="round" />
      <rect x={16.5} y={27.5} width={31} height={22} rx={7.5} fill={`url(#${body})`} />
      <circle cx={32} cy={37.5} r={4.4} fill={`url(#${accent})`} />
      <rect x={30} y={39.5} width={4} height={6.5} rx={2} fill={`url(#${accent})`} />
      <rect x={20.5} y={31} width={13} height={5.5} rx={2.75} fill={`url(#${gloss})`} opacity={0.65} />
    </>
  ),
  camera: ({ body, accent, gloss, shadow }) => (
    <>
      <ellipse cx={32} cy={53.5} rx={16} ry={3.8} fill={`url(#${shadow})`} />
      <path d="M24 20.5l2.8-5.5h10.4l2.8 5.5Z" fill={`url(#${accent})`} />
      <rect x={10.5} y={20.5} width={43} height={28.5} rx={7.5} fill={`url(#${body})`} />
      <circle cx={32} cy={34.5} r={10.2} fill={`url(#${accent})`} />
      <circle cx={32} cy={34.5} r={6.8} fill={`url(#${gloss})`} opacity={0.25} />
      <circle cx={29.2} cy={32} r={2} fill={hi(0.85)} />
      <circle cx={45} cy={26.5} r={1.9} fill={`url(#${accent})`} />
      <rect x={14.5} y={24} width={15} height={5.5} rx={2.75} fill={`url(#${gloss})`} opacity={0.65} />
    </>
  ),
  battery: ({ body, accent, gloss, shadow }) => (
    <>
      <ellipse cx={33} cy={53.5} rx={15} ry={3.8} fill={`url(#${shadow})`} />
      <rect x={48} y={27.5} width={4.8} height={9.5} rx={2.4} fill={`url(#${accent})`} />
      <rect x={11.5} y={22.5} width={36.5} height={21.5} rx={5.5} fill={`url(#${body})`} />
      <rect x={16.5} y={27} width={6.2} height={11.5} rx={2.4} fill={`url(#${accent})`} />
      <rect x={25} y={27} width={6.2} height={11.5} rx={2.4} fill={`url(#${accent})`} />
      <rect x={33.5} y={27} width={6.2} height={11.5} rx={2.4} fill={`url(#${accent})`} />
      <rect x={15.5} y={25.5} width={12.5} height={5} rx={2.5} fill={`url(#${gloss})`} opacity={0.65} />
    </>
  ),
  search: ({ body, accent, gloss, shadow }) => (
    <>
      <ellipse cx={31} cy={54.5} rx={13} ry={3.8} fill={`url(#${shadow})`} />
      <path d="M39.8 39.8 51.5 51.5" fill="none" stroke={`url(#${accent})`} strokeWidth={7.2} strokeLinecap="round" />
      <circle cx={27.5} cy={27.5} r={16} fill={`url(#${body})`} />
      <circle cx={27.5} cy={27.5} r={9.2} fill={`url(#${accent})`} />
      <circle cx={27.5} cy={27.5} r={9.2} fill={`url(#${gloss})`} opacity={0.28} />
      <path d="M16.5 20a14.2 14.2 0 0 1 9-6.1" fill="none" stroke={hi(0.55)} strokeWidth={2.8} strokeLinecap="round" />
    </>
  ),
  folder: ({ body, accent, gloss, shadow }) => (
    <>
      <ellipse cx={32} cy={53.5} rx={16} ry={3.8} fill={`url(#${shadow})`} />
      <path
        d="M12.5 25.5a4 4 0 0 1 4-4H27l5 4.8h12.5a4 4 0 0 1 4 4V44a4 4 0 0 1-4 4h-28a4 4 0 0 1-4-4Z"
        fill={`url(#${accent})`}
      />
      <rect x={12.5} y={31} width={39} height={3.8} rx={1.9} fill={hi(0.4)} />
      <path d="M12.5 35h39v9a4 4 0 0 1-4 4h-31a4 4 0 0 1-4-4Z" fill={`url(#${body})`} />
      <rect x={15.5} y={37.5} width={12.5} height={3.6} rx={1.8} fill={`url(#${gloss})`} opacity={0.6} />
    </>
  ),
  star: ({ body, accent, shadow }) => (
    <>
      <ellipse cx={32} cy={53.5} rx={13} ry={3.8} fill={`url(#${shadow})`} />
      <path
        d="M32 9.5l6 12.2 13.4 1.9-9.7 9.4 2.3 13.3L32 40.1l-12 6.2 2.3-13.3-9.7-9.4 13.4-1.9Z"
        fill={`url(#${body})`}
      />
      <path d="M32 9.5l6 12.2 4 .6" fill="none" stroke={hi(0.6)} strokeWidth={2.4} strokeLinecap="round" />
      <path d="M47.5 11.5l1.6 3.7 3.7 1.6-3.7 1.6-1.6 3.7-1.6-3.7-3.7-1.6 3.7-1.6Z" fill={`url(#${accent})`} />
      <circle cx={16.5} cy={45} r={2} fill={`url(#${accent})`} />
    </>
  ),
  bolt: ({ body, accent, shadow }) => (
    <>
      <ellipse cx={32} cy={54} rx={13} ry={3.8} fill={`url(#${shadow})`} />
      <path d="M36 6 16.5 36.5H28L23.5 58 43.5 27.5H32L36 6Z" fill={`url(#${body})`} />
      <path d="M36 6 16.5 36.5" fill="none" stroke={hi(0.55)} strokeWidth={2.2} strokeLinecap="round" />
      <circle cx={47.5} cy={13.5} r={2.3} fill={`url(#${accent})`} />
    </>
  ),
};

export interface Icon3dProps extends HTMLAttributes<HTMLSpanElement> {
  /** The icon to draw. */
  name: Icon3dName;
  /** A preset box size, or an exact pixel size (default 'md', 48px). */
  size?: Icon3dSize | number;
  tone?: Icon3dTone;
  /** Tilt with the pointer: true for the default 8°, or the largest rotation. Fine pointers only. */
  tilt?: boolean | number;
  /** Accessible name; without it the icon is decorative. */
  label?: string;
}

/** One 3D icon: gradients of its own (useId), tone-recolourable, optionally tilting with the pointer. */
export const Icon3d = forwardRef<HTMLSpanElement, Icon3dProps>(function Icon3d(
  { name, size = 'md', tone, tilt: tiltWith, label, className, style, ...rest },
  ref,
) {
  const uid = useId().replace(/[^a-zA-Z0-9_-]/g, '');
  const paint: Paint = {
    body: `nx-i3d-${uid}-body`,
    accent: `nx-i3d-${uid}-accent`,
    gloss: `nx-i3d-${uid}-gloss`,
    shadow: `nx-i3d-${uid}-shadow`,
  };

  const own = useRef<HTMLSpanElement>(null);
  const max = typeof tiltWith === 'number' ? tiltWith : 8;
  useBehavior(own, tilt, { max }, tiltWith !== undefined && tiltWith !== false);

  const sizing = typeof size === 'number' ? ({ '--_size': `${size}px` } as CSSProperties) : undefined;
  return (
    <span
      ref={mergeRefs(ref, own)}
      className={cx('nx-icon3d', className)}
      data-tone={tone && tone !== 'lapis' ? tone : undefined}
      data-size={typeof size === 'string' && size !== 'md' ? size : undefined}
      style={{ ...sizing, ...style }}
      {...(label ? ({ role: 'img', 'aria-label': label } as HTMLAttributes<HTMLSpanElement>) : { 'aria-hidden': true })}
      {...rest}
    >
      <svg viewBox="0 0 64 64" focusable="false">
        <defs>
          <linearGradient id={paint.body} x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" style={{ stopColor: 'var(--_c1)' }} />
            <stop offset="1" style={{ stopColor: 'var(--_c2)' }} />
          </linearGradient>
          <linearGradient id={paint.accent} x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" style={{ stopColor: 'var(--_c3)' }} />
            <stop offset="1" style={{ stopColor: 'var(--_c4)' }} />
          </linearGradient>
          <linearGradient id={paint.gloss} x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" style={{ stopColor: 'rgb(255 255 255 / 85%)' }} />
            <stop offset="1" style={{ stopColor: 'rgb(255 255 255 / 0%)' }} />
          </linearGradient>
          <radialGradient id={paint.shadow}>
            <stop offset="0" style={{ stopColor: 'color-mix(in oklab, var(--nx-ink-950) 26%, transparent)' }} />
            <stop offset="0.65" style={{ stopColor: 'color-mix(in oklab, var(--nx-ink-950) 12%, transparent)' }} />
            <stop offset="1" style={{ stopColor: 'rgb(0 0 0 / 0%)' }} />
          </radialGradient>
        </defs>
        {art[name](paint)}
      </svg>
    </span>
  );
});

/* ---- Feature row ---------------------------------------------------------------- */

export interface Icon3dRowItem {
  icon: Icon3dName;
  title: ReactNode;
  text?: ReactNode;
  tone?: Icon3dTone;
  /** Where the optional "more" link goes (a SmartLink). */
  href?: string;
  /** That link's visible label; give it with href. */
  link?: ReactNode;
}

export interface Icon3dRowProps extends HTMLAttributes<HTMLUListElement> {
  items: Icon3dRowItem[];
  /** The icon size inside each card (default 'lg', 64px). */
  iconSize?: Icon3dSize | number;
  /** Tilt each card with the pointer (default true; fine pointers only). */
  tilt?: boolean;
}

/** One feature card: its own ref so the core tilt can follow the pointer over it. */
const Icon3dRowCard = forwardRef<HTMLLIElement, { item: Icon3dRowItem; iconSize: Icon3dSize | number; tilt: boolean }>(
  function Icon3dRowCard({ item, iconSize, tilt: tiltWith }, ref) {
    const own = useRef<HTMLLIElement>(null);
    useBehavior(own, tilt, { max: 4 }, tiltWith);
    return (
      <li ref={mergeRefs(ref, own)} className="nx-icon3d-item">
        <Icon3d name={item.icon} size={iconSize} tone={item.tone} />
        <p className="nx-icon3d-title">{item.title}</p>
        {item.text && <p className="nx-icon3d-text">{item.text}</p>}
        {item.href && item.link !== undefined && (
          <SmartLink href={item.href} className="nx-icon3d-link">
            {item.link}
            <Icon name="arrow-right" />
          </SmartLink>
        )}
      </li>
    );
  },
);

/** A feature row: 3D icons as the backbone of a card each, tilting toward the pointer on hover. */
export const Icon3dRow = forwardRef<HTMLUListElement, Icon3dRowProps>(function Icon3dRow(
  { items, iconSize = 'lg', tilt: tiltWith = true, className, ...rest },
  ref,
) {
  return (
    <ul ref={ref} className={cx('nx-icon3d-row', className)} {...rest}>
      {items.map((item, index) => (
        <Icon3dRowCard key={index} item={item} iconSize={iconSize} tilt={tiltWith} />
      ))}
    </ul>
  );
});
