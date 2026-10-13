/** Interaction blocks (React): confirmations, in-place editing, rolling numbers, undo, reordering, viewing, comparing, swiping, passwords. */
import {
  type ButtonHTMLAttributes,
  type CSSProperties,
  type HTMLAttributes,
  type InputHTMLAttributes,
  type KeyboardEvent,
  type MouseEvent,
  type ReactNode,
  forwardRef,
  useCallback,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import {
  type HoldState,
  type IconName,
  type LightboxStageController,
  type PasswordRuleId,
  type SlideState,
  type SortMessages,
  type SwipeSide,
  closeLightbox,
  compareSlider,
  createLightboxStage,
  createOdometer,
  direction,
  holdToConfirm,
  icons,
  morphWidth,
  moveItem,
  numberParts,
  openLightbox,
  passwordRuleIds,
  passwordScoreLabels,
  passwordStrength,
  slideToConfirm,
  snackbarTimer,
  sortable,
  swipeActions,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useControllable, useEvent, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale, useT } from '../internal/provider';

/** A built-in icon by name, or any node as it is. */
const renderIcon = (icon: IconName | ReactNode) => (typeof icon === 'string' && icon in icons ? <Icon name={icon as IconName} /> : icon);
const sizeAttr = (size: string | undefined) => (size && size !== 'md' ? size : undefined);
const textOf = (node: ReactNode) => (typeof node === 'string' || typeof node === 'number' ? String(node) : undefined);
const escapeHtml = (text: string) => text.replace(/[&<>"']/g, (c) => `&#${c.charCodeAt(0)};`);

/* Glyphs the shared icon set does not carry, drawn in the same 24-unit, 1.75-stroke style. */
const stroke = { viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: 1.75, strokeLinecap: 'round' as const, strokeLinejoin: 'round' as const, 'aria-hidden': true };
const UndoGlyph = () => (
  <svg {...stroke}>
    <path d="M9 14L4 9l5-5" />
    <path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11" />
  </svg>
);
const GripGlyph = () => (
  <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
    {[6, 12, 18].map((y) => (
      <g key={y}>
        <circle cx="9" cy={y} r="1.6" />
        <circle cx="15" cy={y} r="1.6" />
      </g>
    ))}
  </svg>
);
const EyeGlyph = () => (
  <svg {...stroke}>
    <path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12z" />
    <circle cx="12" cy="12" r="3" />
  </svg>
);
const EyeOffGlyph = () => (
  <svg {...stroke}>
    <path d="M10.6 5.6A10 10 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17 17 0 0 1-2.8 3.6M6.6 6.6A17 17 0 0 0 2.5 12S6 18.5 12 18.5a9.6 9.6 0 0 0 5.4-1.6" />
    <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2M3 3l18 18" />
  </svg>
);
const DotGlyph = () => (
  <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
    <circle cx="12" cy="12" r="3" />
  </svg>
);

/* ---- Hold to confirm --------------------------------------------------------- */

export interface HoldToConfirmProps extends Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'children'> {
  /** What the button does ("Hold to delete"). */
  label: ReactNode;
  /** Shown (and announced) once confirmed. */
  doneLabel?: ReactNode;
  /** Explains the gesture to assistive tech. */
  hint?: string;
  /** Hold time in ms. */
  duration?: number;
  /** Back to idle after this many ms; 0 stays confirmed. */
  resetAfter?: number;
  variant?: 'bar' | 'ring';
  tone?: 'danger' | 'accent' | 'warning';
  icon?: IconName | ReactNode;
  doneIcon?: IconName | ReactNode;
  size?: 'sm' | 'md' | 'lg';
  static?: boolean;
  onConfirm?: () => void;
}

/** A destructive button that confirms only after it has been held: pointer or Space/Enter. */
export const HoldToConfirm = forwardRef<HTMLButtonElement, HoldToConfirmProps>(function HoldToConfirm(
  { label, doneLabel = 'Done', hint = 'Press and hold to confirm', duration = 1200, resetAfter = 2400, variant = 'bar', tone = 'danger', icon = 'trash', doneIcon = 'check', size, static: still, onConfirm, className, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLButtonElement>(null);
  const hintId = useId();
  const [state, setState] = useState<HoldState>('idle');
  const confirm = useEvent(onConfirm);

  useEffect(() => {
    if (!ref.current) return;
    return holdToConfirm(ref.current, { duration, resetAfter, onConfirm: confirm, onStateChange: setState });
  }, [duration, resetAfter, confirm]);

  return (
    <button
      ref={mergeRefs(ref, forwarded)}
      type="button"
      className={cx('nx-hold', className)}
      data-variant={variant}
      data-tone={tone === 'danger' ? undefined : tone}
      data-size={sizeAttr(size)}
      data-static={still ? '' : undefined}
      aria-describedby={hintId}
      {...rest}
    >
      <span className="nx-hold-fill" aria-hidden="true" />
      <span className="nx-hold-icon" aria-hidden="true">
        <svg className="nx-hold-ring" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10.5" />
          <circle className="nx-hold-ring-bar" cx="12" cy="12" r="10.5" pathLength={1} />
        </svg>
        <span>{renderIcon(icon)}</span>
        <span data-off="">{renderIcon(doneIcon)}</span>
      </span>
      <span className="nx-hold-label">
        <span>{label}</span>
        <span data-off="" aria-hidden="true">
          {doneLabel}
        </span>
      </span>
      <span className="nx-visually-hidden" id={hintId}>
        {hint}
      </span>
      <span className="nx-visually-hidden" aria-live="polite">
        {state === 'done' ? doneLabel : ''}
      </span>
    </button>
  );
});

/* ---- Slide to confirm -------------------------------------------------------- */

export interface SlideToConfirmProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  label: ReactNode;
  doneLabel?: ReactNode;
  /** The thumb's accessible name (defaults to the label when it is text). */
  thumbLabel?: string;
  hint?: string;
  /** Fraction of the track past which a release confirms. */
  threshold?: number;
  resetAfter?: number;
  tone?: 'accent' | 'danger' | 'success';
  icon?: IconName | ReactNode;
  doneIcon?: IconName | ReactNode;
  disabled?: boolean;
  onConfirm?: () => void;
  onStateChange?: (state: SlideState) => void;
}

/** Drag the thumb along the track to confirm (arrow keys step, End confirms). */
export const SlideToConfirm = forwardRef<HTMLDivElement, SlideToConfirmProps>(function SlideToConfirm(
  { label, doneLabel = 'Done', thumbLabel, hint = 'Drag to the end, or press End, to confirm', threshold = 0.9, resetAfter = 0, tone = 'accent', icon = 'arrow-right', doneIcon = 'check', disabled, onConfirm, onStateChange, className, ...rest },
  forwarded,
) {
  const ref = useRef<HTMLDivElement>(null);
  const hintId = useId();
  const [state, setState] = useState<SlideState>('idle');
  const confirm = useEvent(onConfirm);
  const change = useEvent((next: SlideState) => {
    setState(next);
    onStateChange?.(next);
  });

  useEffect(() => {
    if (!ref.current) return;
    return slideToConfirm(ref.current, { threshold, resetAfter, onConfirm: confirm, onStateChange: change });
  }, [threshold, resetAfter, confirm, change]);

  return (
    <div ref={mergeRefs(ref, forwarded)} className={cx('nx-slide-confirm', className)} data-tone={tone === 'accent' ? undefined : tone} aria-disabled={disabled || undefined} {...rest}>
      <span className="nx-slide-confirm-fill" aria-hidden="true" />
      <span className="nx-slide-confirm-label" aria-hidden="true">
        <span>{label}</span>
        <span data-off="">{doneLabel}</span>
      </span>
      <span
        className="nx-slide-confirm-thumb"
        role="slider"
        tabIndex={disabled ? -1 : 0}
        aria-label={thumbLabel ?? textOf(label)}
        aria-valuemin={0}
        aria-valuemax={100}
        aria-valuenow={0}
        aria-describedby={hintId}
        aria-disabled={disabled || undefined}
      >
        <span className="nx-slide-confirm-icon" aria-hidden="true">
          <span>{renderIcon(icon)}</span>
          <span data-off="">{renderIcon(doneIcon)}</span>
        </span>
      </span>
      <span className="nx-visually-hidden" id={hintId}>
        {hint}
      </span>
      <span className="nx-visually-hidden" aria-live="polite">
        {state === 'done' ? doneLabel : ''}
      </span>
    </div>
  );
});

/* ---- Inline edit -------------------------------------------------------------- */

export type InlineEditState = 'idle' | 'editing' | 'saving' | 'error';

export interface InlineEditProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  /**
   * Persist the new value. Return (or resolve) `false` or an error message to
   * keep the field open in its error state; throwing does the same.
   */
  onSave?: (value: string) => void | boolean | string | Promise<void | boolean | string>;
  /** The field's name for assistive tech ("Project name"). */
  label: string;
  placeholder?: string;
  /** The display button's name: "Edit {label}". */
  editLabel?: string;
  savingLabel?: string;
  errorLabel?: string;
  requiredLabel?: string;
  required?: boolean;
  maxLength?: number;
  size?: 'md' | 'lg';
  disabled?: boolean;
  /** Blur saves (default) or cancels. */
  blur?: 'save' | 'cancel';
  /** Refuse a value before saving: return an error message. */
  validate?: (value: string) => string | null | undefined | false;
}

/** Text that turns into an input in place: Enter saves, Escape cancels, the width morphs between the two. */
export const InlineEdit = forwardRef<HTMLDivElement, InlineEditProps>(function InlineEdit(
  { value: valueProp, defaultValue = '', onValueChange, onSave, label, placeholder, editLabel = 'Edit {label}', savingLabel = 'Saving…', errorLabel = 'Could not save', requiredLabel = 'Required', required, maxLength, size, disabled, blur = 'save', validate, className, ...rest },
  forwarded,
) {
  const [value, setValue] = useControllable(valueProp, defaultValue, onValueChange);
  const [state, setState] = useState<InlineEditState>('idle');
  const [draft, setDraft] = useState(value);
  const [message, setMessage] = useState('');
  const root = useRef<HTMLDivElement>(null);
  const input = useRef<HTMLInputElement>(null);
  const display = useRef<HTMLButtonElement>(null);
  const from = useRef<number | null>(null);
  const focusTo = useRef<'input' | 'display' | null>(null);
  const errorId = useId();

  /** Change mode, morphing the width from what it was. */
  const go = (next: InlineEditState, focus: 'input' | 'display' | null = null) => {
    if (root.current) from.current = root.current.offsetWidth;
    focusTo.current = focus;
    setState(next);
  };

  useIsoLayoutEffect(() => {
    const el = root.current;
    if (el && from.current !== null) morphWidth(el, undefined, { from: from.current, spring: 'snappy' });
    from.current = null;
    if (focusTo.current === 'input') {
      input.current?.focus();
      input.current?.select();
    } else if (focusTo.current === 'display') display.current?.focus();
    focusTo.current = null;
  }, [state]);

  const start = () => {
    if (disabled) return;
    setDraft(value);
    setMessage('');
    go('editing', 'input');
  };

  const cancel = (focus = true) => {
    setDraft(value);
    setMessage('');
    go('idle', focus ? 'display' : null);
  };

  const commit = async (focus = true) => {
    const next = draft.trim() === '' ? '' : draft;
    if (next === value) return cancel(focus);
    if (required && next.trim() === '') {
      setMessage(requiredLabel);
      setState('error');
      return;
    }
    const refusal = validate?.(next);
    if (refusal) {
      setMessage(refusal);
      setState('error');
      return;
    }
    if (onSave) {
      setState('saving');
      setMessage(savingLabel);
      try {
        const result = await onSave(next);
        if (result === false || typeof result === 'string') {
          setMessage(typeof result === 'string' ? result : errorLabel);
          setState('error');
          input.current?.focus();
          return;
        }
      } catch (error) {
        setMessage(error instanceof Error && error.message ? error.message : errorLabel);
        setState('error');
        input.current?.focus();
        return;
      }
    }
    setValue(next);
    setMessage('');
    go('idle', focus ? 'display' : null);
  };

  const onKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
    if (event.key === 'Enter') {
      event.preventDefault();
      void commit();
    } else if (event.key === 'Escape') {
      event.preventDefault();
      event.stopPropagation();
      cancel();
    }
  };

  const onBlur = () => {
    if (state !== 'editing') return;
    if (blur === 'cancel') cancel(false);
    else void commit(false);
  };

  const editing = state !== 'idle';
  const shown = value || placeholder || '';

  return (
    <div ref={mergeRefs(root, forwarded)} className={cx('nx-inline-edit', className)} data-state={state} data-size={sizeAttr(size)} {...rest}>
      {!editing && (
        <button ref={display} type="button" className="nx-inline-edit-display" aria-label={`${editLabel.replace('{label}', label)}: ${value || placeholder || ''}`} disabled={disabled} onClick={start}>
          <span className="nx-inline-edit-text" data-placeholder={value ? undefined : ''}>
            {shown}
          </span>
          <span className="nx-inline-edit-pen" aria-hidden="true">
            <Icon name="edit" />
          </span>
        </button>
      )}
      {editing && (
        <span className="nx-inline-edit-field">
          <span className="nx-inline-edit-sizer" aria-hidden="true">
            {draft || placeholder || ' '}
          </span>
          <input
            ref={input}
            className="nx-inline-edit-input"
            value={draft}
            placeholder={placeholder}
            aria-label={label}
            aria-invalid={state === 'error' || undefined}
            aria-describedby={message ? errorId : undefined}
            readOnly={state === 'saving'}
            maxLength={maxLength}
            onChange={(event) => {
              setDraft(event.target.value);
              if (state === 'error') setState('editing');
            }}
            onKeyDown={onKeyDown}
            onBlur={onBlur}
          />
        </span>
      )}
      <span className="nx-inline-edit-status" id={errorId} aria-live="polite">
        {state === 'saving' && <span className="nx-spinner" aria-hidden="true" />}
        {editing ? message : ''}
      </span>
    </div>
  );
});

/* ---- Odometer ----------------------------------------------------------------- */

export interface OdometerProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'children'> {
  value: number;
  /** Where the first roll starts when `reveal` is on. */
  from?: number;
  /** Defaults to the provider's locale (Persian digits under `fa`). */
  locale?: string;
  format?: Intl.NumberFormatOptions;
  /** Roll in when scrolled into view. */
  reveal?: boolean;
}

/** A number whose digits roll, column by column, up or down the wheel. */
export const Odometer = forwardRef<HTMLSpanElement, OdometerProps>(function Odometer({ value, from = 0, locale: localeProp, format, reveal = true, className, ...rest }, forwarded) {
  const providerLocale = useLocale();
  const locale = localeProp ?? providerLocale;
  const ref = useRef<HTMLSpanElement>(null);
  const controller = useRef<ReturnType<typeof createOdometer> | null>(null);
  const formatKey = JSON.stringify(format ?? null);

  // The server markup: the final value for assistive tech, the start value in the roll. Frozen, so React never re-renders what the roll owns.
  const [initial] = useState(() => {
    const text = (n: number) => numberParts(n, locale, format).map((p) => p.char).join('');
    const cells = numberParts(reveal ? from : value, locale, format)
      .map((p) => `<span class="nx-odometer-col" data-kind="${p.kind}"><span class="nx-odometer-cell">${escapeHtml(p.char)}</span></span>`)
      .join('');
    return { sr: escapeHtml(text(value)), roll: cells };
  });

  useEffect(() => {
    if (!ref.current) return;
    controller.current = createOdometer(ref.current, { value, from, locale, format, reveal });
    return () => {
      controller.current?.destroy();
      controller.current = null;
    };
    // value is followed by the effect below; a new locale or format rebuilds.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [locale, formatKey, reveal]);

  useEffect(() => {
    controller.current?.update(value);
  }, [value]);

  return (
    <span ref={mergeRefs(ref, forwarded)} className={cx('nx-odometer', className)} {...rest}>
      <span className="nx-odometer-sr nx-visually-hidden" dangerouslySetInnerHTML={{ __html: initial.sr }} />
      <span className="nx-odometer-roll" aria-hidden="true" dangerouslySetInnerHTML={{ __html: initial.roll }} />
    </span>
  );
});

/* ---- Undo snackbar ------------------------------------------------------------ */

export interface UndoSnackbarItem {
  id: string | number;
  message: ReactNode;
  /** Leave out for a plain notice without Undo. */
  undoable?: boolean;
  /** ms before it goes on its own; 0 stays until dismissed. */
  duration?: number;
  icon?: IconName | ReactNode;
}

export type SnackbarCloseReason = 'timeout' | 'close' | 'undo';

export interface UndoSnackbarsProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  items: UndoSnackbarItem[];
  onUndo?: (id: UndoSnackbarItem['id']) => void;
  /** The item has left (after its exit): drop it from your list. */
  onDismiss?: (id: UndoSnackbarItem['id'], reason: SnackbarCloseReason) => void;
  position?: 'bottom-center' | 'bottom-start' | 'bottom-end' | 'inline';
  undoLabel?: string;
  /** The region's name for assistive tech. */
  label?: string;
  duration?: number;
}

function SnackbarRow({ item, duration, undoLabel, dismissLabel, onClose, onUndo }: { item: UndoSnackbarItem; duration: number; undoLabel: string; dismissLabel: string; onClose: (reason: SnackbarCloseReason) => void; onUndo?: () => void }) {
  const ref = useRef<HTMLLIElement>(null);
  const [closing, setClosing] = useState(false);
  const close = useEvent((reason: SnackbarCloseReason) => {
    if (closing) return;
    setClosing(true);
    setTimeout(() => onClose(reason), 160);
  });
  const time = item.duration ?? duration;

  useEffect(() => {
    if (!ref.current || closing) return;
    return snackbarTimer(ref.current, { duration: time, onExpire: () => close('timeout') });
  }, [time, closing, close]);

  return (
    <li ref={ref} className="nx-snackbar" data-state={closing ? 'closing' : 'open'}>
      {item.icon && <span className="nx-snackbar-icon">{renderIcon(item.icon)}</span>}
      <p className="nx-snackbar-message">{item.message}</p>
      {item.undoable !== false && (
        <button
          type="button"
          className="nx-snackbar-undo"
          onClick={() => {
            onUndo?.();
            close('undo');
          }}
        >
          <UndoGlyph />
          {undoLabel}
        </button>
      )}
      <button type="button" className="nx-snackbar-close" aria-label={dismissLabel} onClick={() => close('close')}>
        <Icon name="x" />
      </button>
      {time > 0 && (
        <span className="nx-snackbar-timer" aria-hidden="true">
          <i />
        </span>
      )}
    </li>
  );
}

/** A stack of "Done · Undo" snackbars with a draining timer that pauses on hover and focus. */
export function UndoSnackbars({ items, onUndo, onDismiss, position = 'bottom-center', undoLabel = 'Undo', label, duration = 6000, className, ...rest }: UndoSnackbarsProps) {
  const t = useT();
  const ref = useRef<HTMLElement>(null);
  // The top layer, so a transformed or clipping ancestor never traps the fixed stack.
  useEffect(() => {
    const el = ref.current;
    if (!el || position === 'inline' || typeof el.showPopover !== 'function') return;
    el.setAttribute('popover', 'manual');
    try {
      el.showPopover();
    } catch {
      /* already shown */
    }
    return () => {
      try {
        el.hidePopover();
      } catch {
        /* not shown */
      }
      el.removeAttribute('popover');
    };
  }, [position]);
  return (
    <section ref={ref} className={cx('nx-snackbars', className)} data-position={position} aria-label={label ?? t('notifications')} {...rest}>
      <ol className="nx-snackbar-list" aria-live="polite">
        {items.map((item) => (
          <SnackbarRow key={item.id} item={item} duration={duration} undoLabel={undoLabel} dismissLabel={t('dismiss')} onUndo={onUndo ? () => onUndo(item.id) : undefined} onClose={(reason) => onDismiss?.(item.id, reason)} />
        ))}
      </ol>
    </section>
  );
}

let snackSeq = 0;

/** State for <UndoSnackbars>: `push()` a message, spread `bind` on the stack. */
export function useUndoSnackbar() {
  const [items, setItems] = useState<UndoSnackbarItem[]>([]);
  const push = useCallback((item: Omit<UndoSnackbarItem, 'id'> & { id?: UndoSnackbarItem['id'] }) => {
    const id = item.id ?? `nx-snack-${++snackSeq}`;
    setItems((list) => [...list.filter((existing) => existing.id !== id), { ...item, id }]);
    return id;
  }, []);
  const dismiss = useCallback((id: UndoSnackbarItem['id']) => setItems((list) => list.filter((item) => item.id !== id)), []);
  return { items, push, dismiss, bind: { items, onDismiss: dismiss } };
}

/* ---- Sortable list ------------------------------------------------------------ */

export interface SortableListProps<T> extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'onChange'> {
  items: T[];
  getKey: (item: T) => string;
  renderItem: (item: T, index: number) => ReactNode;
  onReorder: (items: T[]) => void;
  /** The item's name in announcements and on its handle. */
  getLabel?: (item: T) => string;
  /** The handle's name: "Reorder {name}". */
  handleLabel?: string;
  /** How to reorder from the keyboard (read with the handle). */
  instructions?: string;
  messages?: Partial<SortMessages>;
  /** The list's own name. */
  label?: string;
}

/** A list reordered by dragging its handles, or from the keyboard with live announcements. */
export function SortableList<T>({ items, getKey, renderItem, onReorder, getLabel, handleLabel = 'Reorder {name}', instructions = 'Press Space to pick up, the arrow keys to move, Space again to drop, Escape to cancel.', messages, label, className, ...rest }: SortableListProps<T>) {
  const ref = useRef<HTMLDivElement>(null);
  const hintId = useId();
  const latest = useRef(items);
  latest.current = items;
  const locale = useLocale();
  const reorder = useEvent(onReorder);
  const messageKey = JSON.stringify(messages ?? null);

  useEffect(() => {
    if (!ref.current) return;
    return sortable(ref.current, {
      onMove: (from, to) => reorder(moveItem(latest.current, from, to)),
      messages,
      locale,
    });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [reorder, messageKey, locale]);

  return (
    <div ref={ref} className={cx('nx-sortable', className)} {...rest}>
      <ol className="nx-sortable-list" aria-label={label}>
        {items.map((item, index) => {
          const key = getKey(item);
          const name = getLabel?.(item) ?? key;
          return (
            <li key={key} className="nx-sortable-item" data-nx-sort-key={key} data-nx-sort-label={name}>
              <button type="button" className="nx-sortable-handle" data-nx-sort-handle="" aria-pressed="false" aria-label={handleLabel.replace('{name}', name)} aria-describedby={hintId}>
                <GripGlyph />
              </button>
              <div className="nx-sortable-body">{renderItem(item, index)}</div>
            </li>
          );
        })}
      </ol>
      <p className="nx-visually-hidden" id={hintId}>
        {instructions}
      </p>
      <span className="nx-visually-hidden" data-nx-sort-live="" aria-live="assertive" />
    </div>
  );
}

/* ---- Lightbox ----------------------------------------------------------------- */

export interface LightboxImage {
  src: string;
  /** A smaller file for the grid (defaults to `src`). */
  thumb?: string;
  alt: string;
  caption?: ReactNode;
}

export interface LightboxProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  images: LightboxImage[];
  columns?: number;
  /** The thumbnails' aspect ratio. */
  ratio?: string;
  /** The viewer's name. */
  label?: string;
  zoomInLabel?: string;
  zoomOutLabel?: string;
  /** The thumbnail buttons' names: "{alt}, image {index} of {total}". */
  openLabel?: string;
  onIndexChange?: (index: number) => void;
}

/** A thumbnail grid whose images grow into a full-screen viewer with zoom, pan and swipe. */
export const Lightbox = forwardRef<HTMLDivElement, LightboxProps>(function Lightbox(
  { images, columns = 3, ratio = '4 / 3', label = 'Gallery', zoomInLabel = 'Zoom in', zoomOutLabel = 'Zoom out', openLabel = '{alt}, image {index} of {total}', onIndexChange, className, style, ...rest },
  forwarded,
) {
  const t = useT();
  const locale = useLocale();
  const [index, setIndex] = useState(0);
  const [nav, setNav] = useState<{ dir: 'next' | 'prev' | null; key: number }>({ dir: null, key: 0 });
  const [zoomed, setZoomed] = useState(false);
  const dialog = useRef<HTMLDialogElement>(null);
  const stage = useRef<HTMLDivElement>(null);
  const hero = useRef<HTMLImageElement>(null);
  const thumbs = useRef<Array<HTMLButtonElement | null>>([]);
  const controller = useRef<LightboxStageController | null>(null);
  const opening = useRef<HTMLElement | null>(null);
  const [openTick, setOpenTick] = useState(0);
  const number = useMemo(() => new Intl.NumberFormat(locale), [locale]);
  const total = images.length;
  const current = images[index];
  const changed = useEvent(onIndexChange);

  const go = useCallback(
    (next: number, dir: 'next' | 'prev') => {
      if (total < 2) return;
      const target = (next + total) % total;
      setIndex(target);
      setNav((n) => ({ dir, key: n.key + 1 }));
      changed(target);
    },
    [total, changed],
  );

  const close = useEvent(async () => {
    const box = dialog.current;
    if (!box?.open) return;
    const thumb = thumbs.current[index];
    await closeLightbox(box, thumb?.querySelector('img') ?? null, () => hero.current);
    controller.current?.reset();
    thumb?.focus();
  });

  const next = useEvent(() => go(index + 1, 'next'));
  const prev = useEvent(() => go(index - 1, 'prev'));

  useEffect(() => {
    if (!stage.current) return;
    controller.current = createLightboxStage(stage.current, { onNext: next, onPrev: prev, onClose: close, onZoomChange: (z) => setZoomed(z > 1.01) });
    return () => {
      controller.current?.destroy();
      controller.current = null;
    };
  }, [next, prev, close]);

  useEffect(() => {
    controller.current?.reset();
  }, [index]);

  // Open after the dialog already shows the right image, so the morph snapshots the real thing.
  useEffect(() => {
    if (!openTick || !dialog.current) return;
    void openLightbox(dialog.current, opening.current, () => hero.current);
  }, [openTick]);

  const open = (i: number) => {
    opening.current = thumbs.current[i]?.querySelector('img') ?? null;
    setIndex(i);
    setNav((n) => ({ dir: null, key: n.key + 1 }));
    setOpenTick((tick) => tick + 1);
    changed(i);
  };

  const onKeyDown = (event: KeyboardEvent<HTMLDialogElement>) => {
    const dir = dialog.current ? direction(dialog.current) : 1;
    const forward = dir === 1 ? 'ArrowRight' : 'ArrowLeft';
    const back = dir === 1 ? 'ArrowLeft' : 'ArrowRight';
    if (event.key === forward) next();
    else if (event.key === back) prev();
    else if (event.key === 'Home') go(0, 'prev');
    else if (event.key === 'End') go(total - 1, 'next');
    else if (event.key === '+' || event.key === '=') controller.current?.zoomBy(1.5);
    else if (event.key === '-') controller.current?.zoomBy(1 / 1.5);
    else if (event.key === '0') controller.current?.reset();
    else return;
    event.preventDefault();
  };

  return (
    <div ref={forwarded} className={cx('nx-lightbox-gallery', className)} style={style} {...rest}>
      <ul className="nx-lightbox-grid" style={{ '--_cols': columns } as CSSProperties}>
        {images.map((image, i) => (
          <li key={`${image.src}-${i}`}>
            <button
              ref={(node) => {
                thumbs.current[i] = node;
              }}
              type="button"
              className="nx-lightbox-thumb"
              style={{ '--_ratio': ratio } as CSSProperties}
              aria-haspopup="dialog"
              aria-label={openLabel.replace('{alt}', image.alt).replace('{index}', number.format(i + 1)).replace('{total}', number.format(total))}
              onClick={() => open(i)}
            >
              <img src={image.thumb ?? image.src} alt="" loading="lazy" decoding="async" draggable={false} />
            </button>
          </li>
        ))}
      </ul>

      <dialog
        ref={dialog}
        className="nx-lightbox"
        aria-label={label}
        onCancel={(event) => {
          event.preventDefault();
          void close();
        }}
        onKeyDown={onKeyDown}
      >
        <div className="nx-lightbox-bar">
          <span className="nx-lightbox-counter">
            {number.format(index + 1)} / {number.format(total)}
          </span>
          <div className="nx-lightbox-tools">
            <button type="button" className="nx-lightbox-tool" aria-label={zoomOutLabel} disabled={!zoomed} onClick={() => controller.current?.zoomBy(1 / 1.5)}>
              <Icon name="minus" />
            </button>
            <button type="button" className="nx-lightbox-tool" aria-label={zoomInLabel} onClick={() => controller.current?.zoomBy(1.5)}>
              <Icon name="plus" />
            </button>
            <button type="button" className="nx-lightbox-tool" aria-label={t('close')} onClick={() => void close()}>
              <Icon name="x" />
            </button>
          </div>
        </div>
        <div ref={stage} className="nx-lightbox-stage" data-nav={nav.dir ?? undefined}>
          {current && <img ref={hero} key={nav.key} className="nx-lightbox-image" src={current.src} alt={current.alt} draggable={false} />}
        </div>
        {total > 1 && (
          <>
            <button type="button" className="nx-lightbox-nav" data-dir="prev" aria-label={t('previous')} onClick={prev}>
              <Icon name="chevron-left" />
            </button>
            <button type="button" className="nx-lightbox-nav" data-dir="next" aria-label={t('next')} onClick={next}>
              <Icon name="chevron-right" />
            </button>
          </>
        )}
        <p className="nx-lightbox-caption" aria-live="polite">
          {current?.caption ?? ''}
        </p>
      </dialog>
    </div>
  );
});

/* ---- Compare slider ----------------------------------------------------------- */

export interface CompareImage {
  src: string;
  alt: string;
}

export interface CompareSliderProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  before: CompareImage;
  after: CompareImage;
  beforeLabel?: string;
  afterLabel?: string;
  /** Where the divider sits, 0–100 from the inline start (or the top). */
  value?: number;
  defaultValue?: number;
  onValueChange?: (value: number) => void;
  orientation?: 'horizontal' | 'vertical';
  /** CSS aspect ratio of the frame. */
  ratio?: string;
  /** The range's name. */
  label?: string;
}

/** Before/after images split by a divider you drag (a native range underneath). */
export const CompareSlider = forwardRef<HTMLDivElement, CompareSliderProps>(function CompareSlider(
  { before, after, beforeLabel = 'Before', afterLabel = 'After', value: valueProp, defaultValue = 50, onValueChange, orientation = 'horizontal', ratio, label = 'Divider position', className, style, ...rest },
  forwarded,
) {
  const [value, setValue] = useControllable(valueProp, defaultValue, onValueChange);
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => (ref.current ? compareSlider(ref.current) : undefined), []);

  return (
    <div
      ref={mergeRefs(ref, forwarded)}
      className={cx('nx-compare', className)}
      data-orientation={orientation}
      style={{ ...style, '--_pos': `${value}%`, ...(ratio ? { '--_ratio': ratio } : {}) } as CSSProperties}
      {...rest}
    >
      <img className="nx-compare-img" data-side="after" src={after.src} alt={after.alt} draggable={false} />
      <img className="nx-compare-img" data-side="before" src={before.src} alt={before.alt} draggable={false} />
      <span className="nx-compare-tag" data-side="before" aria-hidden="true">
        {beforeLabel}
      </span>
      <span className="nx-compare-tag" data-side="after" aria-hidden="true">
        {afterLabel}
      </span>
      <span className="nx-compare-divider" aria-hidden="true">
        <span className="nx-compare-knob">
          <Icon name="chevron-left" />
          <Icon name="chevron-right" />
        </span>
      </span>
      <input
        type="range"
        className="nx-compare-range"
        min={0}
        max={100}
        step={0.5}
        value={value}
        aria-label={label}
        aria-orientation={orientation}
        aria-valuetext={`${beforeLabel} ${Math.round(value)}%`}
        onChange={(event) => setValue(Number(event.target.value))}
      />
    </div>
  );
});

/* ---- Swipe actions ------------------------------------------------------------ */

export interface SwipeAction {
  id: string;
  label: string;
  icon?: IconName | ReactNode;
  tone?: 'neutral' | 'accent' | 'danger' | 'success' | 'warning' | 'gold';
  /** Fired by a full swipe on its side. */
  primary?: boolean;
  onSelect?: () => void;
}

export interface SwipeActionsProps extends HTMLAttributes<HTMLDivElement> {
  /** Revealed by swiping toward the inline end. */
  start?: SwipeAction[];
  /** Revealed by swiping toward the inline start. */
  end?: SwipeAction[];
  /** A swipe past this fraction of the row fires the side's primary action; false turns it off. */
  fullSwipe?: number | false;
  onOpenChange?: (side: SwipeSide | null) => void;
}

/** A row that slides aside to reveal actions; a long swipe fires the primary one. */
export const SwipeActions = forwardRef<HTMLDivElement, SwipeActionsProps>(function SwipeActions({ start = [], end = [], fullSwipe = 0.62, onOpenChange, className, children, ...rest }, forwarded) {
  const ref = useRef<HTMLDivElement>(null);
  const openChange = useEvent(onOpenChange);

  useEffect(() => {
    if (!ref.current) return;
    return swipeActions(ref.current, { fullSwipe, onOpen: (side) => openChange(side), onClose: () => openChange(null) });
  }, [fullSwipe, openChange]);

  const side = (list: SwipeAction[], which: SwipeSide) =>
    list.length > 0 && (
      <div className="nx-swipe-actions" data-side={which}>
        {list.map((action) => (
          <button
            key={action.id}
            type="button"
            className="nx-swipe-action"
            data-tone={action.tone && action.tone !== 'neutral' ? action.tone : undefined}
            data-primary={action.primary ? '' : undefined}
            onClick={(event: MouseEvent<HTMLButtonElement>) => {
              event.stopPropagation();
              action.onSelect?.();
            }}
          >
            {action.icon && renderIcon(action.icon)}
            <span>{action.label}</span>
          </button>
        ))}
      </div>
    );

  return (
    <div ref={mergeRefs(ref, forwarded)} className={cx('nx-swipe-row', className)} {...rest}>
      {side(start, 'start')}
      {side(end, 'end')}
      <div className="nx-swipe-content">{children}</div>
    </div>
  );
});

/* ---- Password strength -------------------------------------------------------- */

export interface PasswordStrengthLabels {
  /** Words for scores 0–4. */
  scores?: readonly string[];
  rules?: Partial<Record<PasswordRuleId, string>>;
  strength?: string;
  show?: string;
  hide?: string;
  met?: string;
  unmet?: string;
}

const defaultRuleLabels: Record<PasswordRuleId, string> = {
  length: 'At least {min} characters',
  lower: 'A lowercase letter',
  upper: 'An uppercase letter',
  number: 'A number',
  symbol: 'A symbol',
};

export interface PasswordStrengthProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type' | 'value' | 'defaultValue' | 'size' | 'minLength'> {
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  onScoreChange?: (score: number) => void;
  label?: ReactNode;
  hint?: ReactNode;
  error?: ReactNode;
  minLength?: number;
  /** The rules the checklist shows (all score anyway). */
  rules?: PasswordRuleId[];
  /** Name, email…: words that make a password guessable. */
  userInputs?: string[];
  labels?: PasswordStrengthLabels;
}

/** A password field with a segmented strength meter, a rule checklist and a show/hide toggle. */
export const PasswordStrength = forwardRef<HTMLInputElement, PasswordStrengthProps>(function PasswordStrength(
  { value: valueProp, defaultValue = '', onValueChange, onScoreChange, label = 'Password', hint, error, minLength = 8, rules = passwordRuleIds, userInputs, labels = {}, className, style, id: idProp, onChange, ...rest },
  forwarded,
) {
  const [value, setValue] = useControllable(valueProp, defaultValue, onValueChange);
  const [visible, setVisible] = useState(false);
  const auto = useId();
  const id = idProp ?? auto;
  const result = useMemo(() => passwordStrength(value, { minLength, userInputs }), [value, minLength, userInputs]);
  const scoreChange = useEvent(onScoreChange);
  useEffect(() => {
    scoreChange(result.score);
  }, [result.score, scoreChange]);

  const scores = labels.scores ?? passwordScoreLabels;
  const ruleText = { ...defaultRuleLabels, ...labels.rules };
  const empty = value.length === 0;
  const word = empty ? '' : (scores[result.score] ?? '');

  return (
    <div className={cx('nx-password nx-field', className)} style={style} data-score={result.score} data-empty={empty ? '' : undefined} data-invalid={error ? '' : undefined}>
      <label className="nx-label" htmlFor={id}>
        {label}
      </label>
      {hint && (
        <p className="nx-hint" id={`${id}-hint`}>
          {hint}
        </p>
      )}
      <div className="nx-input-group">
        <input
          ref={forwarded}
          id={id}
          className="nx-input"
          type={visible ? 'text' : 'password'}
          autoComplete="new-password"
          spellCheck={false}
          value={value}
          aria-invalid={error ? true : undefined}
          aria-describedby={[hint && `${id}-hint`, `${id}-meter`, `${id}-rules`, error && `${id}-error`].filter(Boolean).join(' ')}
          onChange={(event) => {
            setValue(event.target.value);
            onChange?.(event);
          }}
          {...rest}
        />
        <button type="button" className="nx-password-toggle" aria-pressed={visible} aria-label={visible ? (labels.hide ?? 'Hide password') : (labels.show ?? 'Show password')} aria-controls={id} onClick={() => setVisible((v) => !v)}>
          <span>
            <EyeGlyph />
          </span>
          <span data-off="">
            <EyeOffGlyph />
          </span>
        </button>
      </div>
      <div className="nx-password-meter" id={`${id}-meter`}>
        <span className="nx-password-bars" aria-hidden="true">
          <i />
          <i />
          <i />
          <i />
        </span>
        <span className="nx-password-label" aria-live="polite">
          {word && <span className="nx-visually-hidden">{labels.strength ?? 'Strength'}: </span>}
          {word}
        </span>
      </div>
      <ul className="nx-password-rules" id={`${id}-rules`}>
        {result.rules
          .filter((rule) => rules.includes(rule.id))
          .map((rule) => (
            <li key={rule.id} data-met={rule.met ? '' : undefined}>
              <span className="nx-password-tick" aria-hidden="true">
                <span>
                  <DotGlyph />
                </span>
                <span data-off="">
                  <Icon name="check" />
                </span>
              </span>
              {ruleText[rule.id].replace('{min}', String(minLength))}
              <span className="nx-visually-hidden">, {rule.met ? (labels.met ?? 'done') : (labels.unmet ?? 'not yet')}</span>
            </li>
          ))}
      </ul>
      {error && (
        <p className="nx-error" id={`${id}-error`}>
          {error}
        </p>
      )}
    </div>
  );
});
