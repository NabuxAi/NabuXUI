/**
 * Entrances (React): the ways a page's content can arrive — the curtain
 * overture of a launch page, a list that waves in item by item, and a Solari
 * split-flap board that re-spells itself through its leaves. Thin over
 * css/blocks/entrances.css and the core behaviours in js/blocks/entrances.ts.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useEffect,
  useRef,
} from 'react';
import { type IconName, curtainReveal, icons, setSplitFlapValue, splitFlap, staggerList } from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useEvent } from '../internal/hooks';
import { Icon } from '../internal/icon';

/** A name from the icon set, or any node you brought yourself. */
const renderIcon = (icon: IconName | ReactNode) => (typeof icon === 'string' && icon in icons ? <Icon name={icon as IconName} /> : icon);

/* The aperture mark that rides the curtains (decorative; the label beside it is real text). */
const APERTURE = 'M12 3v6M19.8 7.5l-5.2 3M19.8 16.5l-5.2-3M12 21v-6M4.2 16.5l5.2-3M4.2 7.5l5.2 3';

/* ---- Curtain reveal ------------------------------------------------------------------ */

export interface CurtainRevealProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  /** The page's blocks, wrapped one by one and waved in once the curtains part. */
  items: ReactNode[];
  /** The wordmark the curtains carry (rendered uppercase, decorative). */
  label?: string;
  /** How long the eased 0–100 count takes, in milliseconds (default 2400). */
  duration?: number;
  /** Show the replay button (default true). */
  replay?: boolean;
  /** Label — and accessible name — of the replay button. */
  replayLabel?: string;
  /** Called once the count completes and the curtains part. */
  onOpen?: () => void;
  /** An accessible name for the whole stage, when the items are not enough. */
  stageLabel?: string;
}

/**
 * The overture of a launch page: two curtain panels carry a wordmark and an
 * eased 0–100 counter; at one hundred the panels part to either side and the
 * content beneath enters item by item on a stepped delay. The count only runs
 * while the stage is on screen, parks when it leaves, and under reduced
 * motion the house opens at once. Replay closes the curtains and runs it all
 * again.
 */
export const CurtainReveal = forwardRef<HTMLElement, CurtainRevealProps>(function CurtainReveal(
  { items, label = 'NABU', duration = 2400, replay = true, replayLabel = 'Replay the overture', onOpen, stageLabel, className, ...rest },
  ref,
) {
  const own = useRef<HTMLElement>(null);
  const opened = useEvent(onOpen);
  useBehavior(own, curtainReveal, { duration, onOpen: opened });

  const replayNow = useEvent(() => {
    own.current?.dispatchEvent(new CustomEvent('nx-curtain-replay'));
  });

  return (
    <figure ref={mergeRefs(ref, own)} className={cx('nx-curtain', className)} {...rest}>
      {replay && (
        <button type="button" className="nx-curtain-replay" data-nx-curtain-replay="" onClick={replayNow}>
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 12a9 9 0 1 0 3-6.7L3 8" />
            <path d="M3 3v5h5" />
          </svg>
          <span>{replayLabel}</span>
        </button>
      )}

      <div className="nx-curtain-stage" role={stageLabel ? 'img' : undefined} aria-label={stageLabel}>
        {items.map((item, index) => (
          <div key={index} className="nx-curtain-item" style={{ '--nx-i': index } as CSSProperties}>
            {item}
          </div>
        ))}

        <div className="nx-curtain-mark" aria-hidden="true">
          <svg className="nx-curtain-aperture" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.6} strokeLinecap="round">
            <circle cx="12" cy="12" r="9" />
            <path d={APERTURE} />
          </svg>
          <span className="nx-curtain-word">{label}</span>
          <span className="nx-curtain-count">
            <span className="nx-curtain-pct">0</span>
            <small>%</small>
          </span>
        </div>
        <div className="nx-curtain-panel nx-curtain-panel-start" aria-hidden="true" />
        <div className="nx-curtain-panel nx-curtain-panel-end" aria-hidden="true" />
      </div>
    </figure>
  );
});

/* ---- Stagger list ---------------------------------------------------------------------- */

export interface StaggerListItem {
  /** Key for the item (the index is used when absent). */
  id?: string;
  /** The item's heading. */
  title: ReactNode;
  /** A line of body copy under the title. */
  note?: ReactNode;
  /** A small mark pinned to the heading's row (a version tag, a date…). */
  tag?: ReactNode;
  /** An icon for the tile's disc — a name from the set, or a node of your own. */
  icon?: IconName | ReactNode;
}

export interface StaggerListProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  items: StaggerListItem[];
  /** Milliseconds between one item and the next (default 80). */
  step?: number;
  /** Show the replay button (default true). */
  replay?: boolean;
  /** Label — and accessible name — of the replay button. */
  replayLabel?: string;
}

/**
 * Rows that arrive one after another the moment the list scrolls into view —
 * each rises, sheds a soft blur and a hair of skew on the soft spring, a step
 * after the one before it. The stagger lives on the item, so joined Persian
 * never breaks; replay pulls the wave back and sends it again.
 */
export const StaggerList = forwardRef<HTMLDivElement, StaggerListProps>(function StaggerList(
  { items, step = 80, replay = true, replayLabel = 'Send the wave again', className, ...rest },
  ref,
) {
  const own = useRef<HTMLDivElement>(null);
  useBehavior(own, staggerList, { step });

  const replayNow = useEvent(() => {
    own.current?.dispatchEvent(new CustomEvent('nx-stagger-replay'));
  });

  return (
    <div ref={mergeRefs(ref, own)} className={cx('nx-stagger', className)} {...rest}>
      <ul className="nx-stagger-list">
        {items.map((item, index) => (
          <li key={item.id ?? index} className="nx-stagger-item">
            {item.icon !== undefined && <span className="nx-stagger-item-icon" aria-hidden="true">{renderIcon(item.icon)}</span>}
            <h3 className="nx-stagger-item-title">
              {item.title}
              {item.tag !== undefined && <span className="nx-stagger-item-tag">{item.tag}</span>}
            </h3>
            {item.note !== undefined && <p className="nx-stagger-item-note">{item.note}</p>}
          </li>
        ))}
      </ul>
      {replay && (
        <button type="button" className="nx-stagger-replay" data-nx-stagger-replay="" onClick={replayNow}>
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 12a9 9 0 1 0 3-6.7L3 8" />
            <path d="M3 3v5h5" />
          </svg>
          <span>{replayLabel}</span>
        </button>
      )}
    </div>
  );
});

/* ---- Split flap -------------------------------------------------------------------------- */

export interface SplitFlapProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  /** What the board spells — Latin caps, digits and : . - · +; anything else blanks. */
  value: string;
  /** How many cells the board has (default 10; the value is padded or cut to it). Fixed for the board's life. */
  cells?: number;
  /** The brand on the board's top rail. */
  brand?: string;
  /** The small tag on the top rail's end (mono, tabular). */
  tag?: string;
  /** What the board is, for assistive tech (the value is read after it). */
  label?: string;
  /** Milliseconds between one cell and the next when re-spelling (default 55). */
  step?: number;
}

/** One mechanical cell: two halves plus the two leaves that fall between glyphs. */
function FlapCell({ ch }: { ch: string }) {
  const glyph = ch === ' ' ? '\u00a0' : ch;
  return (
    <span className="nx-split-flap-cell" data-ch={ch}>
      <span className="nx-split-flap-half nx-split-flap-top-half">
        <span className="nx-split-flap-g">{glyph}</span>
      </span>
      <span className="nx-split-flap-half nx-split-flap-bot-half">
        <span className="nx-split-flap-g">{glyph}</span>
      </span>
      <span className="nx-split-flap-leaf nx-split-flap-leaf-t" aria-hidden="true">
        <span className="nx-split-flap-g" />
      </span>
      <span className="nx-split-flap-leaf nx-split-flap-leaf-b" aria-hidden="true">
        <span className="nx-split-flap-g" />
      </span>
    </span>
  );
}

/**
 * A Solari departure board: every character is a cell with a top and a bottom
 * half plus two falling leaves — the top leaf swings down on its hinge and
 * the bottom drops flat after it, 130 + 105 milliseconds of pure mechanics.
 * The first value is painted without flipping; changing `value` re-spells
 * the board in a cascade that flips only the cells whose glyph really
 * changed. The row is decorative — the real text lives in the board's
 * accessible name — and the board is hardware: Latin, ltr, dark in every
 * theme, so Persian copy stays word-level around it and never letter-splits.
 */
export const SplitFlap = forwardRef<HTMLDivElement, SplitFlapProps>(function SplitFlap(
  { value, cells = 10, brand = 'ATLAS RAIL', tag = 'SPLIT-FLAP', label = 'Split-flap board', step = 55, className, ...rest },
  ref,
) {
  const own = useRef<HTMLDivElement>(null);
  // The board's DOM is painted once with the first value; the behaviour owns
  // every glyph after that, so React never stomps a leaf mid-flip.
  const painted = useRef(value);
  useBehavior(own, splitFlap, { value: painted.current, step });

  useEffect(() => {
    if (own.current && value !== painted.current) {
      painted.current = value;
      setSplitFlapValue(own.current, value);
    }
  }, [value]);

  const board = painted.current
    .toUpperCase()
    .replace(/[^A-Z0-9 :.\-·+]/g, ' ')
    .slice(0, cells)
    .padEnd(cells, ' ');

  return (
    <div
      ref={mergeRefs(ref, own)}
      className={cx('nx-split-flap', className)}
      dir="ltr"
      role="img"
      aria-label={`${label}: ${value.trim() || '—'}`}
      {...rest}
    >
      <div className="nx-split-flap-bar">
        <span className="nx-split-flap-brand">{brand}</span>
        <span className="nx-split-flap-rule" aria-hidden="true" />
        <span className="nx-split-flap-tag">{tag}</span>
      </div>
      <div className="nx-split-flap-row" aria-hidden="true">
        {Array.from(board).map((ch, index) => (
          <FlapCell key={index} ch={ch} />
        ))}
      </div>
    </div>
  );
});
