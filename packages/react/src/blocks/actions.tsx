/** Buttons, loaders & micro-interactions (React). */
import {
  type AnchorHTMLAttributes,
  type ButtonHTMLAttributes,
  type CSSProperties,
  type ChangeEvent,
  type FieldsetHTMLAttributes,
  type HTMLAttributes,
  type KeyboardEvent,
  type MouseEvent,
  type ReactNode,
  forwardRef,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import {
  type Cleanup,
  type IconName,
  type ParametricKind,
  type ParametricOptions,
  dragScroll,
  emojiBurst,
  icons,
  leave,
  marchingBorder,
  morphWidth,
  parametricPath,
  pixelLoaderCells,
  pointerEntry,
  prefersReducedMotion,
  shakeAndBurst,
  splitText,
  spotlight,
  textDirection,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { Button } from '../components/button';

/** A built-in icon by name, or any node as it is. */
const renderIcon = (icon: IconName | ReactNode) => (typeof icon === 'string' && icon in icons ? <Icon name={icon as IconName} /> : icon);

type AnchorBits = Pick<AnchorHTMLAttributes<HTMLAnchorElement>, 'target' | 'rel' | 'download'>;
type Size = 'sm' | 'md' | 'lg';
const sizeAttr = (size: string | undefined) => (size && size !== 'md' ? size : undefined);

/** Busy buttons stay focusable (disabled would drop focus) but ignore presses. */
function guard<E extends HTMLElement>(busy: boolean, onClick?: (event: MouseEvent<E>) => void) {
  return (event: MouseEvent<E>) => {
    if (busy) {
      event.preventDefault();
      return;
    }
    onClick?.(event);
  };
}

/** The status words nobody has translated yet fall back to English. */
const DONE = 'Done';

export type ActionStatus = 'idle' | 'loading' | 'success' | 'error';

/* ---- Flip button: the perspective reveal ------------------------------------ */

export interface FlipButtonProps extends Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'children'>, AnchorBits {
  /** The front label; also the button's accessible name. */
  label: ReactNode;
  /** What rolls into view on hover or keyboard focus (defaults to `label`). */
  hoverLabel?: ReactNode;
  icon?: IconName | ReactNode;
  hoverIcon?: IconName | ReactNode;
  /** Render as a link (through the app's router link for internal paths). */
  href?: string;
  variant?: 'primary' | 'secondary' | 'inverse';
  size?: Size;
  /** No press scale. */
  static?: boolean;
}

export const FlipButton = forwardRef<HTMLButtonElement | HTMLAnchorElement, FlipButtonProps>(function FlipButton(
  { label, hoverLabel, icon = 'arrow-right', hoverIcon = 'sparkles', href, variant = 'primary', size, static: still, className, type, disabled, onClick, ...rest },
  ref,
) {
  const parts = (
    <>
      <span className="nx-flip-button-part" data-part="label">
        <span className="nx-flip-button-cube">
          <span className="nx-flip-button-face">{label}</span>
          <span className="nx-flip-button-face" data-face="back" aria-hidden="true">
            {hoverLabel ?? label}
          </span>
        </span>
      </span>
      <span className="nx-flip-button-part" data-part="icon" aria-hidden="true">
        <span className="nx-flip-button-cube">
          <span className="nx-flip-button-face">{renderIcon(icon)}</span>
          <span className="nx-flip-button-face" data-face="back">
            {renderIcon(hoverIcon)}
          </span>
        </span>
      </span>
    </>
  );

  const shared = {
    className: cx('nx-flip-button', className),
    'data-variant': variant,
    'data-size': sizeAttr(size),
    'data-static': still ? '' : undefined,
  };

  if (href) {
    return (
      <SmartLink ref={ref as never} href={href} {...shared} {...(rest as AnchorHTMLAttributes<HTMLAnchorElement>)} aria-disabled={disabled || undefined} onClick={onClick as never}>
        {parts}
      </SmartLink>
    );
  }

  return (
    <button ref={ref as never} type={type ?? 'button'} disabled={disabled} onClick={onClick} {...shared} {...rest}>
      {parts}
    </button>
  );
});

/* ---- Blob button: the animated gradient button -------------------------------- */

export interface BlobButtonProps extends ButtonHTMLAttributes<HTMLButtonElement>, AnchorBits {
  href?: string;
  size?: Size;
  icon?: IconName | ReactNode;
  iconEnd?: IconName | ReactNode;
  static?: boolean;
}

export const BlobButton = forwardRef<HTMLButtonElement | HTMLAnchorElement, BlobButtonProps>(function BlobButton(
  { href, size, icon, iconEnd, static: still, className, children, type, disabled, onClick, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLElement>(null);
  // Writes --nx-px / --nx-py: the blobs gather where the pointer is.
  useBehavior(ref, spotlight, undefined as never);

  const content = (
    <>
      <span className="nx-blob-button-blobs" aria-hidden="true">
        <i />
        <i />
        <i />
      </span>
      <span className="nx-blob-button-label">
        {icon ? renderIcon(icon) : null}
        {children}
        {iconEnd ? renderIcon(iconEnd) : null}
      </span>
    </>
  );

  const shared = {
    ref: mergeRefs(ref, forwarded as never),
    className: cx('nx-blob-button', className),
    'data-size': sizeAttr(size),
    'data-static': still ? '' : undefined,
  };

  if (href) {
    return (
      <SmartLink href={href} {...shared} {...(rest as AnchorHTMLAttributes<HTMLAnchorElement>)} aria-disabled={disabled || undefined} onClick={onClick as never}>
        {content}
      </SmartLink>
    );
  }

  return (
    <button type={type ?? 'button'} disabled={disabled} onClick={onClick} {...shared} {...rest}>
      {content}
    </button>
  );
});

/* ---- Border button: the animated border ------------------------------------------ */

export interface BorderButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  /** loading spins the dashes; success and error close them into a ring of their colour. */
  status?: ActionStatus;
  /** Shown (and announced) on success. */
  successLabel?: ReactNode;
  /** Shown (and announced) on error. */
  errorLabel?: ReactNode;
  icon?: IconName | ReactNode;
  size?: Size;
  static?: boolean;
}

/** The frame: a faint track, the marching dashes, and the trace that spins and closes. */
function BorderFrame() {
  return (
    <svg className="nx-border-button-frame" aria-hidden="true" focusable="false">
      <rect className="nx-border-button-track" x="0.75" y="0.75" width="100%" height="100%" rx="12" />
      <rect className="nx-border-button-march" x="0.75" y="0.75" width="100%" height="100%" rx="12" />
      <rect className="nx-border-button-trace" x="0.75" y="0.75" width="100%" height="100%" rx="12" pathLength={100} />
    </svg>
  );
}

const markProps = { className: 'nx-icon', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: 2.25, strokeLinecap: 'round', strokeLinejoin: 'round' } as const;

export const BorderButton = forwardRef<HTMLButtonElement, BorderButtonProps>(function BorderButton(
  { status = 'idle', successLabel, errorLabel, icon, size, static: still, className, children, type, onClick, ...rest },
  forwarded,
) {
  const t = useT();
  const ref = useRef<HTMLButtonElement>(null);
  // Hover and focus speed the march up and back down without a jump.
  useBehavior(ref, marchingBorder, {});
  const busy = status === 'loading';
  const done = successLabel ?? DONE;
  const failed = errorLabel ?? t('failed');

  return (
    <button
      ref={mergeRefs(ref, forwarded)}
      type={type ?? 'button'}
      className={cx('nx-border-button', className)}
      data-status={status === 'idle' ? undefined : status}
      data-size={sizeAttr(size)}
      data-static={still ? '' : undefined}
      aria-busy={busy || undefined}
      aria-disabled={busy || undefined}
      onClick={guard(busy, onClick)}
      {...rest}
    >
      <BorderFrame />
      <span className="nx-border-button-label" data-slot="idle" aria-hidden="true">
        {icon ? renderIcon(icon) : null}
        <span>{children}</span>
      </span>
      <span className="nx-border-button-label" data-slot="success" aria-hidden="true">
        <svg {...markProps}>
          <path className="nx-border-button-mark" d="M5 12.5l4.5 4.5L19 7.5" pathLength={1} />
        </svg>
        <span>{done}</span>
      </span>
      <span className="nx-border-button-label" data-slot="error" aria-hidden="true">
        <svg {...markProps}>
          <path className="nx-border-button-mark" d="M7 7l10 10M17 7L7 17" pathLength={1} />
        </svg>
        <span>{failed}</span>
      </span>
      <span className="nx-visually-hidden" aria-live="polite">
        {status === 'success' ? done : status === 'error' ? failed : children}
      </span>
    </button>
  );
});

/* ---- Transaction button: the whole flow in one button ------------------------------ */

export interface TransactionLabels {
  idle: string;
  loading?: string;
  success?: string;
  error?: string;
}

export interface TransactionButtonProps extends Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'children'> {
  status?: ActionStatus;
  /** What the button says in each state; the width springs to fit each one. */
  labels: TransactionLabels;
  size?: Size;
  static?: boolean;
}

const LOCK = (
  <svg className="nx-tx-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
    <path className="nx-tx-shackle" d="M8 11V8a4 4 0 0 1 8 0v3" />
    <rect x="5" y="11" width="14" height="10" rx="2.5" />
    <path d="M12 15.25v2" />
  </svg>
);

const CHECK = (
  <svg className="nx-tx-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
    <circle cx="12" cy="12" r="9" />
    <path className="nx-tx-mark" d="M8.5 12.5l2.5 2.5 4.5-5" pathLength={1} />
  </svg>
);

const CROSS = (
  <svg className="nx-tx-cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round">
    <circle cx="12" cy="12" r="9" />
    <path className="nx-tx-mark" d="M9.5 9.5l5 5M14.5 9.5l-5 5" pathLength={1} />
  </svg>
);

/** Words of a label, each an inline-block with its index for the stagger. */
function Words({ text, state, onAnimationEnd }: { text: string; state?: 'enter' | 'leave'; onAnimationEnd?: () => void }) {
  return (
    <span
      className="nx-tx-button-text"
      data-state={state}
      dir={textDirection(text)}
      aria-hidden="true"
      onAnimationEnd={onAnimationEnd ? (event) => event.target === event.currentTarget && onAnimationEnd() : undefined}
    >
      {splitText(text).map((piece, i) => (
        <span key={i} style={{ '--nx-i': i } as CSSProperties}>
          {piece}
        </span>
      ))}
    </span>
  );
}

export const TransactionButton = forwardRef<HTMLButtonElement, TransactionButtonProps>(function TransactionButton(
  { status = 'idle', labels, size, static: still, className, type, onClick, ...rest },
  forwarded,
) {
  const t = useT();
  const ref = useRef<HTMLButtonElement>(null);
  // What is on screen trails `status` by one layout pass: long enough to measure the old width.
  const [shown, setShown] = useState<ActionStatus>(status);
  const [swap, setSwap] = useState<{ n: number; leaving: string | null }>({ n: 0, leaving: null });
  const from = useRef<number | null>(null);

  const say = (state: ActionStatus) =>
    state === 'loading' ? labels.loading ?? `${t('loading')}…` : state === 'success' ? labels.success ?? DONE : state === 'error' ? labels.error ?? t('failed') : labels.idle;
  const text = say(shown);

  // 1. The status changed: note the current width, then swap the label (all before paint).
  useIsoLayoutEffect(() => {
    if (status === shown) return;
    from.current = ref.current?.offsetWidth ?? null;
    setSwap((current) => ({ n: current.n + 1, leaving: text }));
    setShown(status);
  }, [status, shown]);

  // 2. The new label is in: spring the width over from the old one.
  useIsoLayoutEffect(() => {
    if (from.current === null || !ref.current) return;
    morphWidth(ref.current, undefined, { from: from.current });
    from.current = null;
  }, [shown]);

  // The leaving copy goes even when its animation never reports (a background tab).
  useEffect(() => {
    if (!swap.leaving) return;
    const id = setTimeout(() => setSwap((current) => ({ ...current, leaving: null })), 450);
    return () => clearTimeout(id);
  }, [swap]);

  const busy = shown === 'loading';

  return (
    <button
      ref={mergeRefs(ref, forwarded)}
      type={type ?? 'button'}
      className={cx('nx-tx-button', className)}
      data-status={shown === 'idle' ? undefined : shown}
      data-size={sizeAttr(size)}
      data-static={still ? '' : undefined}
      aria-busy={busy || undefined}
      aria-disabled={busy || undefined}
      onClick={guard(busy, onClick)}
      {...rest}
    >
      <span className="nx-tx-button-waves" aria-hidden="true" />
      <span className="nx-tx-button-icon" aria-hidden="true">
        {LOCK}
        {CHECK}
        {CROSS}
      </span>
      <span className="nx-tx-button-label">
        {swap.leaving !== null && <Words key={`leave-${swap.n}`} text={swap.leaving} state="leave" onAnimationEnd={() => setSwap((current) => ({ ...current, leaving: null }))} />}
        <Words key={`enter-${swap.n}`} text={text} state={swap.n > 0 ? 'enter' : undefined} />
      </span>
      <span className="nx-visually-hidden" aria-live="polite">
        {text}
      </span>
    </button>
  );
});

/* ---- Fill button: the animated hover button ----------------------------------------- */

export interface FillButtonProps extends ButtonHTMLAttributes<HTMLButtonElement>, AnchorBits {
  href?: string;
  /** The colour that fills the button. */
  variant?: 'accent' | 'inverse' | 'gold';
  /** The copy that slides in over the fill (defaults to the label). */
  hoverLabel?: ReactNode;
  icon?: IconName | ReactNode;
  iconEnd?: IconName | ReactNode;
  size?: Size;
  shape?: 'pill' | 'rounded';
  static?: boolean;
}

export const FillButton = forwardRef<HTMLButtonElement | HTMLAnchorElement, FillButtonProps>(function FillButton(
  { href, variant = 'accent', hoverLabel, icon, iconEnd, size, shape, static: still, className, children, type, disabled, onClick, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLElement>(null);
  // Where the pointer came in and went out: the circle grows from one and retreats to the other.
  useBehavior(ref, pointerEntry, undefined as never);
  const start = icon ? renderIcon(icon) : null;
  const end = iconEnd ? renderIcon(iconEnd) : null;

  const content = (
    <>
      <span className="nx-fill-button-fill" aria-hidden="true" />
      <span className="nx-fill-button-label">
        <span>
          {start}
          {children}
          {end}
        </span>
        <span data-copy="" aria-hidden="true">
          {start}
          {hoverLabel ?? children}
          {end}
        </span>
      </span>
    </>
  );

  const shared = {
    ref: mergeRefs(ref, forwarded as never),
    className: cx('nx-fill-button', className),
    'data-variant': variant === 'accent' ? undefined : variant,
    'data-size': sizeAttr(size),
    'data-shape': shape === 'rounded' ? 'rounded' : undefined,
    'data-static': still ? '' : undefined,
  };

  if (href) {
    return (
      <SmartLink href={href} {...shared} {...(rest as AnchorHTMLAttributes<HTMLAnchorElement>)} aria-disabled={disabled || undefined} onClick={onClick as never}>
        {content}
      </SmartLink>
    );
  }

  return (
    <button type={type ?? 'button'} disabled={disabled} onClick={onClick} {...shared} {...rest}>
      {content}
    </button>
  );
});

/* ---- Metal button: the metallic dock toggle ----------------------------------------- */

export interface MetalButtonProps extends Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'children'> {
  /** The accessible name (shown beside the icon when shape="pill"). */
  label: string;
  icon?: IconName | ReactNode;
  /** Shown while pressed; defaults to live wave bars. */
  activeIcon?: IconName | ReactNode;
  pressed?: boolean;
  defaultPressed?: boolean;
  onPressedChange?: (pressed: boolean) => void;
  size?: Size;
  shape?: 'round' | 'pill';
  static?: boolean;
}

/** Five bars that bounce like a voice level while the toggle is on. */
function MetalBars() {
  return (
    <span className="nx-metal-bars">
      <i />
      <i />
      <i />
      <i />
      <i />
    </span>
  );
}

export const MetalButton = forwardRef<HTMLButtonElement, MetalButtonProps>(function MetalButton(
  { label, icon = 'mic', activeIcon, pressed, defaultPressed = false, onPressedChange, size, shape = 'round', static: still, className, type, onClick, ...rest },
  ref,
) {
  const [on, setOn] = useControllable(pressed, defaultPressed, onPressedChange);
  const pill = shape === 'pill';

  return (
    <button
      ref={ref}
      type={type ?? 'button'}
      className={cx('nx-metal-button', className)}
      data-size={sizeAttr(size)}
      data-shape={pill ? 'pill' : undefined}
      data-static={still ? '' : undefined}
      aria-pressed={on}
      aria-label={pill ? undefined : label}
      title={pill ? undefined : label}
      onClick={(event) => {
        onClick?.(event);
        if (!event.defaultPrevented) setOn(!on);
      }}
      {...rest}
    >
      <span className="nx-metal-button-rim" aria-hidden="true" />
      <span className="nx-metal-button-face" aria-hidden="true" />
      <span className="nx-metal-button-icons" aria-hidden="true">
        <span data-when="off">{renderIcon(icon)}</span>
        <span data-when="on">{activeIcon ? renderIcon(activeIcon) : <MetalBars />}</span>
      </span>
      {pill && <span className="nx-metal-button-label">{label}</span>}
    </button>
  );
});

/* ---- Interests picker ---------------------------------------------------------------- */

export interface InterestOption {
  value: string;
  label: string;
  emoji: string;
  disabled?: boolean;
}

export interface InterestsPickerProps extends Omit<FieldsetHTMLAttributes<HTMLFieldSetElement>, 'defaultValue' | 'onChange'> {
  options: InterestOption[];
  value?: string[];
  defaultValue?: string[];
  onValueChange?: (value: string[]) => void;
  /** The checkboxes' name, for form posts ("interests[]"). */
  name?: string;
  /** The group's legend. */
  label?: ReactNode;
  /** How many draggable rows the options are dealt into. */
  rows?: number;
  clearLabel?: string;
}

export function InterestsPicker({ options, value, defaultValue = [], onValueChange, name, label, rows = 3, clearLabel = 'Clear', className, disabled, ...rest }: InterestsPickerProps) {
  const locale = useLocale();
  const [selected, setSelected] = useControllable(value, defaultValue, onValueChange);
  const root = useRef<HTMLFieldSetElement>(null);
  const clearing = useRef<Cleanup>(() => {});
  const perRow = Math.max(1, Math.ceil(options.length / Math.max(1, rows)));
  const lines = useMemo(() => Array.from({ length: Math.ceil(options.length / perRow) }, (_, i) => options.slice(i * perRow, (i + 1) * perRow)), [options, perRow]);

  useEffect(() => {
    const el = root.current;
    if (!el) return;
    const stops = Array.from(el.querySelectorAll<HTMLElement>('.nx-interests-row')).map((row) => dragScroll(row));
    return () => stops.forEach((stop) => stop());
  }, [lines.length]);

  useEffect(() => () => clearing.current(), []);

  const toggle = (option: InterestOption, event: ChangeEvent<HTMLInputElement>) => {
    const checked = event.target.checked;
    const others = selected.filter((item) => item !== option.value);
    setSelected(checked ? [...others, option.value] : others);
    if (checked) emojiBurst(event.target.closest('.nx-interest') ?? event.target, option.emoji);
  };

  const clear = () => {
    const el = root.current;
    if (!el || selected.length === 0) return;
    const chips = Array.from(el.querySelectorAll<HTMLElement>('.nx-interest')).filter((chip) => chip.querySelector<HTMLInputElement>('.nx-interest-input')?.checked);
    clearing.current();
    clearing.current = shakeAndBurst(chips, { emoji: (chip) => chip.dataset.emoji, onBurst: () => setSelected([]) });
  };

  return (
    <fieldset ref={root} className={cx('nx-interests', className)} disabled={disabled} {...rest}>
      {label && <legend className="nx-interests-legend">{label}</legend>}
      <div className="nx-interests-rows">
        {lines.map((line, r) => (
          <div key={r} className="nx-interests-row">
            <div className="nx-interests-track">
              {line.map((option) => (
                <label key={option.value} className="nx-interest" data-emoji={option.emoji}>
                  <input
                    type="checkbox"
                    className="nx-interest-input"
                    name={name}
                    value={option.value}
                    checked={selected.includes(option.value)}
                    disabled={option.disabled}
                    onChange={(event) => toggle(option, event)}
                  />
                  <span className="nx-interest-emoji" aria-hidden="true">
                    {option.emoji}
                  </span>
                  <span className="nx-interest-label">{option.label}</span>
                  <span className="nx-interest-mark" aria-hidden="true">
                    <Icon name="plus" className="nx-interest-off" />
                    <Icon name="check" className="nx-interest-on" />
                  </span>
                </label>
              ))}
            </div>
          </div>
        ))}
      </div>
      <div className="nx-interests-footer">
        <Button size="sm" variant="ghost" icon="x" onClick={clear} aria-disabled={selected.length === 0 || undefined}>
          {clearLabel}
          {selected.length > 0 && (
            <span className="nx-badge" data-tone="accent">
              {new Intl.NumberFormat(locale).format(selected.length)}
            </span>
          )}
        </Button>
      </div>
    </fieldset>
  );
}

/* ---- Label creator (Notion-style) ---------------------------------------------------- */

export interface LabelColor {
  value: string;
  /** Accessible name of the swatch. */
  label: string;
  /** Any CSS colour. */
  color: string;
}

export interface LabelItem {
  name: string;
  /** A LabelColor value (or any CSS colour). */
  color: string;
}

/** The chart palette, in its fixed order. */
export const LABEL_COLORS: LabelColor[] = [
  { value: 'indigo', label: 'Indigo', color: 'var(--nx-chart-1)' },
  { value: 'pink', label: 'Pink', color: 'var(--nx-chart-2)' },
  { value: 'ochre', label: 'Ochre', color: 'var(--nx-chart-3)' },
  { value: 'teal', label: 'Teal', color: 'var(--nx-chart-4)' },
  { value: 'orange', label: 'Orange', color: 'var(--nx-chart-5)' },
  { value: 'violet', label: 'Violet', color: 'var(--nx-chart-6)' },
  { value: 'green', label: 'Green', color: 'var(--nx-chart-7)' },
];

export interface LabelCreatorProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  labels?: LabelItem[];
  defaultLabels?: LabelItem[];
  onLabelsChange?: (labels: LabelItem[]) => void;
  /** Called with the new label; if it returns a promise, the pixel loader plays until it settles (a rejection cancels). */
  onCreate?: (label: LabelItem) => unknown;
  colors?: LabelColor[];
  placeholder?: string;
  /** The create row; {name} is replaced by what was typed. */
  createText?: string;
  /** Accessible name of the field and its list of labels. */
  label?: string;
  /** Legend of the colour swatches. */
  colorLabel?: string;
  /** Also post the labels as JSON under this name. */
  name?: string;
  disabled?: boolean;
}

function PixelGrid({ rows, cols }: { rows: number; cols: number }) {
  const cells = useMemo(() => pixelLoaderCells(rows, cols), [rows, cols]);
  return (
    <span className="nx-pixel-loader-grid" aria-hidden="true">
      {cells.map((cell, i) => (
        <i key={i} style={{ '--_pl-x': cell.x, '--_pl-y': cell.y, '--_pl-d': cell.d, '--_pl-r': cell.r, '--_pl-s': cell.s } as CSSProperties} />
      ))}
    </span>
  );
}

export function LabelCreator({
  labels,
  defaultLabels = [],
  onLabelsChange,
  onCreate,
  colors = LABEL_COLORS,
  placeholder = 'Add a label…',
  createText = 'Create “{name}”',
  label = 'Labels',
  colorLabel = 'Colour',
  name,
  disabled,
  className,
  ...rest
}: LabelCreatorProps) {
  const t = useT();
  const id = useId();
  const [items, setItems] = useControllable(labels, defaultLabels, onLabelsChange);
  const [query, setQuery] = useState('');
  const [picked, setPicked] = useState<string | null>(null);
  const [creating, setCreating] = useState(false);
  const [fresh, setFresh] = useState<string | null>(null);
  const [flash, setFlash] = useState<string | null>(null);
  const input = useRef<HTMLInputElement>(null);
  const latest = useRef(items);
  useIsoLayoutEffect(() => {
    latest.current = items;
  });

  const typed = query.trim();
  const match = items.find((item) => item.name.toLocaleLowerCase() === typed.toLocaleLowerCase());
  const open = (typed !== '' && !match) || creating;
  // The first colour no label wears yet; once all are taken, round they go.
  const next = colors.find((swatch) => !items.some((item) => item.color === swatch.value))?.value ?? colors[items.length % Math.max(1, colors.length)]?.value ?? '';
  const color = picked ?? next;
  const colorOf = (value: string) => colors.find((c) => c.value === value)?.color ?? value;
  const [before, after = ''] = createText.split('{name}');

  useEffect(() => {
    if (!fresh) return;
    const timer = setTimeout(() => setFresh(null), 1200);
    return () => clearTimeout(timer);
  }, [fresh]);

  useEffect(() => {
    if (!flash) return;
    const timer = setTimeout(() => setFlash(null), 400);
    return () => clearTimeout(timer);
  }, [flash]);

  const create = async () => {
    if (creating || disabled || !typed) return;
    if (match) {
      setFlash(match.name);
      return;
    }
    const item: LabelItem = { name: typed, color };
    setCreating(true);
    // Long enough for the loader to read as work, never a stall.
    const beat = new Promise((resolve) => setTimeout(resolve, prefersReducedMotion() ? 150 : 650));
    try {
      await Promise.all([beat, onCreate?.(item)]);
    } catch {
      setCreating(false);
      return;
    }
    setItems([...latest.current, item]);
    setFresh(item.name);
    setQuery('');
    setPicked(null);
    setCreating(false);
    input.current?.focus();
  };

  const remove = (item: LabelItem, chip: HTMLElement | null) => {
    const drop = () => {
      setItems(latest.current.filter((other) => other.name !== item.name));
      input.current?.focus();
    };
    if (!chip) return drop();
    chip.style.setProperty('--_w', `${chip.offsetWidth}px`);
    leave(chip, { attribute: 'data-leaving', value: '', timeout: 400 }).then(drop);
  };

  const onKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
    if (event.nativeEvent.isComposing) return;
    if (event.key === 'Enter') {
      event.preventDefault();
      create();
    } else if (event.key === 'Escape' && query) {
      event.preventDefault();
      setQuery('');
    }
  };

  return (
    <div className={cx('nx-label-creator', className)} {...rest}>
      <div
        className="nx-label-creator-field"
        onClick={(event) => {
          if (event.target === event.currentTarget) input.current?.focus();
        }}
      >
        {items.length > 0 && (
          <ul className="nx-label-creator-chips" aria-label={label}>
            {items.map((item) => (
              <li
                key={item.name}
                className="nx-label-chip"
                style={{ '--_c': colorOf(item.color) } as CSSProperties}
                data-state={fresh === item.name ? 'enter' : undefined}
                data-flash={flash === item.name ? '' : undefined}
              >
                <span className="nx-label-chip-dot" aria-hidden="true" />
                <span className="nx-label-chip-name">{item.name}</span>
                <button
                  type="button"
                  className="nx-label-chip-remove"
                  aria-label={t('remove', { name: item.name })}
                  disabled={disabled}
                  onClick={(event) => remove(item, event.currentTarget.closest('li'))}
                >
                  <Icon name="x" />
                </button>
              </li>
            ))}
          </ul>
        )}
        <input
          ref={input}
          className="nx-label-creator-input"
          type="text"
          value={query}
          placeholder={placeholder}
          aria-label={label}
          autoComplete="off"
          disabled={disabled}
          onChange={(event) => setQuery(event.target.value)}
          onKeyDown={onKeyDown}
        />
      </div>
      {name && <input type="hidden" name={name} value={JSON.stringify(items)} />}
      <div className="nx-label-creator-panel" data-open={open ? '' : undefined} style={{ '--_c': colorOf(color) } as CSSProperties}>
        <button type="button" className="nx-label-creator-create" data-state={creating ? 'creating' : undefined} aria-busy={creating || undefined} onClick={create}>
          <span className="nx-label-creator-create-icon" aria-hidden="true">
            <Icon name="plus" />
            <span className="nx-pixel-loader" data-variant="wave" data-size="xs" style={{ '--nx-rows': 3, '--nx-cols': 3 } as CSSProperties}>
              <PixelGrid rows={3} cols={3} />
            </span>
          </span>
          <span>
            {before}
            <strong>{typed}</strong>
            {after}
          </span>
        </button>
        <fieldset className="nx-label-creator-colors">
          <legend className="nx-label-creator-colors-legend">{colorLabel}</legend>
          {colors.map((swatch) => (
            <label key={swatch.value} className="nx-label-color" style={{ '--_c': swatch.color } as CSSProperties}>
              <input
                type="radio"
                className="nx-label-color-input"
                name={`${id}-color`}
                value={swatch.value}
                checked={color === swatch.value}
                aria-label={swatch.label}
                onChange={() => setPicked(swatch.value)}
              />
              <span className="nx-label-color-dot" aria-hidden="true" />
            </label>
          ))}
        </fieldset>
      </div>
    </div>
  );
}

/* ---- Pixel loader ----------------------------------------------------------------- */

export interface PixelLoaderProps extends HTMLAttributes<HTMLSpanElement> {
  rows?: number;
  cols?: number;
  /** wave: a diagonal cascade · center: a ripple from the middle · chaos: a random flicker (table rows). */
  variant?: 'wave' | 'center' | 'chaos';
  size?: 'xs' | 'sm' | 'md' | 'lg';
  /** What is loading, for screen readers. Defaults to "Loading". */
  label?: string;
}

export function PixelLoader({ rows = 5, cols = 5, variant = 'wave', size, label, className, style, ...rest }: PixelLoaderProps) {
  const t = useT();
  return (
    <span
      className={cx('nx-pixel-loader', className)}
      role="status"
      data-variant={variant}
      data-size={sizeAttr(size)}
      style={{ '--nx-rows': rows, '--nx-cols': cols, ...style } as CSSProperties}
      {...rest}
    >
      <PixelGrid rows={rows} cols={cols} />
      <span className="nx-visually-hidden">{label ?? t('loading')}</span>
    </span>
  );
}

/* ---- Parametric loader ------------------------------------------------------------- */

export interface ParametricLoaderProps extends HTMLAttributes<HTMLSpanElement> {
  /** rose, spiro (a hypotrochoid) or lissajous. */
  kind?: ParametricKind;
  /** The curve's parameters (see parametricPath). */
  options?: Omit<ParametricOptions, 'size'>;
  size?: Size;
  /** One lap of the glowing head, ms. */
  duration?: number;
  /** What is loading, for screen readers. Defaults to "Loading". */
  label?: string;
}

const LOADER_SIZE = { sm: '2.25rem', md: '3.5rem', lg: '5rem' } as const;

/** Trail copies, tail first: longer and fainter behind, short and bright at the head. */
const TRAIL = [
  { len: 0.34, alpha: 0.12 },
  { len: 0.22, alpha: 0.24 },
  { len: 0.12, alpha: 0.5 },
  { len: 0.05, alpha: 1 },
] as const;

export function ParametricLoader({ kind = 'rose', options, size = 'md', duration, label, className, style, ...rest }: ParametricLoaderProps) {
  const t = useT();
  const key = JSON.stringify(options ?? null);
  // eslint-disable-next-line react-hooks/exhaustive-deps
  const d = useMemo(() => parametricPath(kind, { ...options, size: 100 }), [kind, key]);

  return (
    <span
      className={cx('nx-parametric', className)}
      role="status"
      data-kind={kind}
      style={{ '--nx-loader-size': LOADER_SIZE[size], ...(duration ? { '--_dur': `${duration}ms` } : null), ...style } as CSSProperties}
      {...rest}
    >
      <svg viewBox="0 0 100 100" aria-hidden="true" focusable="false">
        <path className="nx-parametric-track" d={d} />
        {TRAIL.map((layer, i) => (
          <path
            key={layer.len}
            className="nx-parametric-trail"
            d={d}
            pathLength={1}
            data-head={i === TRAIL.length - 1 ? '' : undefined}
            style={{ '--_len': layer.len, '--_alpha': layer.alpha } as CSSProperties}
          />
        ))}
      </svg>
      <span className="nx-visually-hidden">{label ?? t('loading')}</span>
    </span>
  );
}
