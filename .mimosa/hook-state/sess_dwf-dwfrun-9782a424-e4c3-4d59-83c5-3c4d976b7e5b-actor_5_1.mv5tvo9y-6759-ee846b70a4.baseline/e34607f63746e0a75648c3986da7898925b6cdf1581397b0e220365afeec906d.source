/**
 * Pickers & inputs (React): Combobox, TagInput, ContextMenu, HoverCard,
 * BottomSheet, DateRangePicker, TimePicker, NumberField.
 *
 * Thin over css/blocks/pickers.css and core blocks/pickers.ts — the same
 * filtering, tag drafts, menu geometry, sheet snapping, calendar months (in
 * the Gregorian or the Solar Hijri calendar, through Intl) and stepping the
 * Alpine side uses. Every floating part is a native [popover] (or a <dialog>
 * for the sheet) placed with core `place`; values are controlled
 * (`value` + `onValueChange`) or uncontrolled (`defaultValue`), and a `name`
 * posts them in a plain form through hidden inputs.
 */
import {
  type CSSProperties,
  type FocusEvent as ReactFocusEvent,
  type HTMLAttributes,
  type KeyboardEvent as ReactKeyboardEvent,
  type ReactNode,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import {
  type Align,
  type DateRange,
  type IconName,
  type PickerCalendar,
  type PickerOption,
  type PickerWord,
  type RangePresetId,
  type Side,
  bottomSheet,
  calendarParts,
  clockText,
  comboEdge,
  comboMatches,
  comboSegments,
  comboStep,
  createTypeahead,
  ctxMenuTrigger,
  dayFormatter,
  dayPeriods,
  defaultCalendar,
  defaultWeekStart,
  focusableItems,
  from12h,
  hoverCard,
  isoAddDays,
  isoDaysBetween,
  isoToday,
  minuteStops,
  nearestMinute,
  orderRange,
  parseAmount,
  parseClock,
  pickerWeekdays,
  pickerWord,
  place,
  placeAtPoint,
  prefers12h,
  pressRepeat,
  rangeClick,
  rangeMark,
  rangeMonthWeeks,
  rangePreset,
  scrubber,
  shiftCalendarMonth,
  monthStartOf,
  snapNumber,
  snapWheel,
  tagDraft,
  tagIndex,
  tagKey,
  to12h,
  translate,
  wheelTo,
} from '@nabuxai/ui-core';
import { cx, useControllable, useEvent, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale } from '../internal/provider';
import { NumberTicker } from '../components/text';

export type { DateRange, PickerCalendar, RangePresetId };

/* ---- Shared ---------------------------------------------------------------------------- */

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

/** The Intl locale: the prop, or the provider's language. */
function useIntlLocale(locale?: string): string {
  const language = useLocale();
  return locale ?? INTL[language] ?? 'en-US';
}

/** A picker word, overridable per instance. */
function useWords<K extends PickerWord>(locale: string, overrides?: Partial<Record<K, string>>) {
  return (key: K, params: Record<string, string | number> = {}) => {
    const own = overrides?.[key];
    return own ? own.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`)) : pickerWord(locale, key, params);
  };
}

function showPop(el: HTMLElement | null, open: boolean) {
  if (!el) return;
  if (!supportsPopover()) {
    el.toggleAttribute('data-open', open);
    return;
  }
  try {
    const shown = el.matches(':popover-open');
    if (open && !shown) el.showPopover();
    if (!open && shown) el.hidePopover();
  } catch {
    /* not connected */
  }
}

/** A polite live region's text that re-announces even when the words repeat. */
function useAnnounce(): [string, (text: string) => void] {
  const [text, setText] = useState('');
  return [text, (next: string) => setText((prev) => (prev === next ? `${next}​` : next))];
}

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/* ======================================================================================== */
/* ---- Combobox -------------------------------------------------------------------------- */
/* ======================================================================================== */

export interface ComboboxOption extends PickerOption {
  icon?: IconName;
}

export interface ComboboxProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  options: ComboboxOption[];
  value?: string | null;
  defaultValue?: string | null;
  onValueChange?: (value: string | null) => void;
  /** Called as the user types (debounce on your side); pair with `filter={false}` and `loading` for server search. */
  onQueryChange?: (query: string) => void;
  /** Filter the options locally by the typed text (default). Turn off when the options already are the results. */
  filter?: boolean;
  loading?: boolean;
  placeholder?: string;
  /** The input's accessible name (use a <label htmlFor={id}> instead when there is a visible one). */
  label?: string;
  inputId?: string;
  name?: string;
  disabled?: boolean;
  clearable?: boolean;
  emptyText?: ReactNode;
  locale?: string;
  labels?: Partial<Record<'noMatches' | 'loading' | 'clear' | 'showOptions', string>>;
}

/** A typeahead single-select: type to filter, arrows to move, Enter to pick. */
export function Combobox({
  options, value, defaultValue = null, onValueChange, onQueryChange, filter = true, loading = false, placeholder, label, inputId,
  name, disabled = false, clearable = true, emptyText, locale, labels, className, ...rest
}: ComboboxProps) {
  const intl = useIntlLocale(locale);
  const say = useWords(intl, labels);
  const base = useId().replace(/:/g, '');
  const listId = `nx-cb-${base}-list`;
  const [selected, setSelected] = useControllable<string | null>(value, defaultValue, onValueChange);
  const current = options.find((o) => o.value === selected) ?? null;
  const [query, setQuery] = useState(current?.label ?? '');
  const [typed, setTyped] = useState(false);
  const [open, setOpen] = useState(false);
  const [active, setActive] = useState(-1);
  const field = useRef<HTMLDivElement>(null);
  const input = useRef<HTMLInputElement>(null);
  const pop = useRef<HTMLDivElement>(null);
  const list = useRef<HTMLUListElement>(null);

  // The input follows the selection when nobody is typing.
  useEffect(() => {
    if (!typed) setQuery(current?.label ?? '');
  }, [current?.label, typed]);

  const visible = useMemo(() => (filter && typed ? comboMatches(options, query) : options.map((_, i) => i)), [filter, typed, options, query]);
  const off = (i: number) => !!options[i]?.disabled;

  useIsoLayoutEffect(() => {
    showPop(pop.current, open);
    if (!open || !field.current || !pop.current) return;
    return place(field.current, pop.current, { side: 'bottom', align: 'start', offset: 6, matchWidth: true });
  }, [open]);

  // Keep the active option in view as it moves.
  useEffect(() => {
    if (open && active >= 0) list.current?.querySelector(`[data-index="${active}"]`)?.scrollIntoView({ block: 'nearest' });
  }, [open, active]);

  // When the results change, the best match becomes active.
  useEffect(() => {
    if (!open) return;
    const at = visible.indexOf(options.findIndex((o) => o.value === selected));
    setActive(typed || at < 0 ? comboEdge(visible, 'first', off) : visible[at]!);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [visible, open]);

  const close = (restore = true) => {
    setOpen(false);
    setActive(-1);
    if (restore) {
      setTyped(false);
      setQuery(current?.label ?? '');
    }
  };

  const choose = (i: number) => {
    const option = options[i];
    if (!option || option.disabled) return;
    setSelected(option.value);
    setTyped(false);
    setQuery(option.label);
    setOpen(false);
    setActive(-1);
  };

  const onInput = (text: string) => {
    setQuery(text);
    setTyped(true);
    setOpen(true);
    onQueryChange?.(text);
    if (text === '' && clearable) setSelected(null);
  };

  const onKeyDown = (event: ReactKeyboardEvent<HTMLInputElement>) => {
    const k = event.key;
    if (k === 'ArrowDown' || k === 'ArrowUp') {
      event.preventDefault();
      if (!open) {
        setOpen(true);
        return;
      }
      setActive(comboStep(visible, active, k === 'ArrowDown' ? 1 : -1, off));
    } else if ((k === 'Home' || k === 'End') && open) {
      event.preventDefault();
      setActive(comboEdge(visible, k === 'Home' ? 'first' : 'last', off));
    } else if (k === 'Enter' && open) {
      if (active >= 0) {
        event.preventDefault();
        choose(active);
      }
    } else if (k === 'Escape') {
      if (open) {
        event.preventDefault();
        close();
      } else if (clearable && query) {
        event.preventDefault();
        onInput('');
      }
    } else if (k === 'Tab' && open) close();
  };

  const onBlur = (event: ReactFocusEvent) => {
    const to = event.relatedTarget as Node | null;
    if (to && (field.current?.contains(to) || pop.current?.contains(to))) return;
    if (open) close();
    else if (typed) {
      setTyped(false);
      setQuery(current?.label ?? '');
    }
  };

  const showEmpty = open && !loading && visible.length === 0;

  return (
    <div className={cx('nx-combobox', className)} data-open={open ? '' : undefined} data-disabled={disabled ? '' : undefined} {...rest}>
      <div className="nx-combobox-field" ref={field} onClick={() => !disabled && input.current?.focus()}>
        <input
          ref={input}
          id={inputId}
          className="nx-combobox-input"
          type="text"
          role="combobox"
          autoComplete="off"
          spellCheck={false}
          aria-label={label}
          aria-expanded={open}
          aria-controls={listId}
          aria-autocomplete="list"
          aria-activedescendant={open && active >= 0 ? `${listId}-${active}` : undefined}
          aria-busy={loading || undefined}
          placeholder={placeholder}
          disabled={disabled}
          value={query}
          onChange={(e) => onInput(e.target.value)}
          onKeyDown={onKeyDown}
          onBlur={onBlur}
          onClick={() => !open && setOpen(true)}
        />
        {clearable && (
          <button
            type="button"
            className="nx-combobox-clear"
            aria-label={say('clear')}
            data-hidden={query ? undefined : ''}
            tabIndex={query ? 0 : -1}
            disabled={disabled}
            onClick={(e) => {
              e.stopPropagation();
              onInput('');
              input.current?.focus();
            }}
          >
            <Icon name="x" />
          </button>
        )}
        <button
          type="button"
          className="nx-combobox-toggle"
          tabIndex={-1}
          aria-label={say('showOptions')}
          aria-expanded={open}
          aria-controls={listId}
          disabled={disabled}
          onMouseDown={(e) => e.preventDefault()}
          onClick={(e) => {
            e.stopPropagation();
            if (open) close();
            else setOpen(true);
            input.current?.focus();
          }}
        >
          <Icon name="chevron-down" />
        </button>
      </div>
      {name && <input type="hidden" name={name} value={selected ?? ''} />}
      <div ref={pop} className="nx-picker-pop nx-combobox-popover" popover="manual" onMouseDown={(e) => e.preventDefault()}>
        <ul ref={list} id={listId} className="nx-combobox-list" role="listbox" aria-label={label ?? placeholder}>
          {open &&
            visible.map((i, n) => {
              const option = options[i]!;
              return (
                <li
                  key={option.value}
                  id={`${listId}-${i}`}
                  data-index={i}
                  className="nx-combobox-option"
                  role="option"
                  aria-selected={option.value === selected}
                  aria-disabled={option.disabled || undefined}
                  data-active={i === active ? '' : undefined}
                  style={vars({ '--nx-i': n })}
                  onPointerMove={() => !option.disabled && i !== active && setActive(i)}
                  onClick={() => choose(i)}
                >
                  {option.icon && <Icon name={option.icon} />}
                  <span className="nx-combobox-option-text">
                    <span className="nx-combobox-option-label">
                      {comboSegments(option.label, typed ? query : '').map((seg, j) => (seg.match ? <mark key={j}>{seg.text}</mark> : <span key={j}>{seg.text}</span>))}
                    </span>
                    {option.description && <span className="nx-combobox-option-desc">{option.description}</span>}
                  </span>
                  <span className="nx-combobox-check" aria-hidden="true">
                    <Icon name="check" />
                  </span>
                </li>
              );
            })}
        </ul>
        {open && loading && (
          <p className="nx-combobox-empty" role="status">
            <span className="nx-combobox-spinner" aria-hidden="true" />
            {say('loading')}
          </p>
        )}
        {showEmpty && (
          <p className="nx-combobox-empty" role="status">
            {emptyText ?? say('noMatches')}
          </p>
        )}
      </div>
    </div>
  );
}

/* ======================================================================================== */
/* ---- Tag input ------------------------------------------------------------------------- */
/* ======================================================================================== */

export interface TagInputProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  value?: string[];
  defaultValue?: string[];
  onValueChange?: (tags: string[]) => void;
  /** Offered while typing (the ones already added are left out). */
  suggestions?: string[];
  max?: number;
  placeholder?: string;
  /** The tag list's accessible name. */
  label?: string;
  inputId?: string;
  /** Posts each tag as name[] in a plain form. */
  name?: string;
  disabled?: boolean;
  separators?: string[];
  /** Show "3 / 5" under the field when there is a max. */
  showCount?: boolean;
  locale?: string;
  labels?: Partial<Record<'remove' | 'tags' | 'addTag' | 'duplicate' | 'limit' | 'added' | 'removed', string>>;
}

type ChipState = { tag: string; key: string; state: 'idle' | 'exit' };

/** A tokenizer: Enter or a comma turns the text into a chip; Backspace on empty removes the last one. */
export function TagInput({
  value, defaultValue = [], onValueChange, suggestions = [], max, placeholder, label, inputId, name, disabled = false,
  separators, showCount = true, locale, labels, className, ...rest
}: TagInputProps) {
  const intl = useIntlLocale(locale);
  const say = useWords(intl, labels);
  const base = useId().replace(/:/g, '');
  const listId = `nx-tag-${base}-list`;
  const [tags, setTags] = useControllable<string[]>(value, defaultValue, onValueChange);
  const [draft, setDraft] = useState('');
  const [chips, setChips] = useState<ChipState[]>(() => tags.map((tag) => ({ tag, key: tagKey(tag), state: 'idle' })));
  const [flash, setFlash] = useState<string | null>(null);
  const [active, setActive] = useState(-1);
  const [focused, setFocused] = useState(false);
  const [announce, setAnnounce] = useAnnounce();
  const field = useRef<HTMLDivElement>(null);
  const input = useRef<HTMLInputElement>(null);
  const pop = useRef<HTMLDivElement>(null);
  const full = max != null && tags.length >= max;

  // Removed chips stay a moment to fade out where they were.
  useEffect(() => {
    setChips((prev) => {
      const wanted = new Set(tags.map(tagKey));
      const kept = prev.map((c) => (wanted.has(c.key) ? { ...c, state: 'idle' as const } : { ...c, state: 'exit' as const }));
      const known = new Set(prev.map((c) => c.key));
      for (const tag of tags) if (!known.has(tagKey(tag))) kept.push({ tag, key: tagKey(tag), state: 'idle' });
      return kept;
    });
    const timer = setTimeout(() => setChips((prev) => prev.filter((c) => c.state !== 'exit')), 220);
    return () => clearTimeout(timer);
  }, [tags]);

  useEffect(() => {
    if (!flash) return;
    const timer = setTimeout(() => setFlash(null), 420);
    return () => clearTimeout(timer);
  }, [flash]);

  const offered = useMemo(() => {
    const q = draft.trim();
    if (!q || !suggestions.length) return [];
    const opts = suggestions.filter((s) => tagIndex(tags, s) < 0).map((s) => ({ value: s, label: s }));
    return comboMatches(opts, q).slice(0, 8).map((i) => opts[i]!.label);
  }, [draft, suggestions, tags]);
  const open = focused && offered.length > 0 && !full;

  useIsoLayoutEffect(() => {
    showPop(pop.current, open);
    if (!open || !field.current || !pop.current) return;
    return place(field.current, pop.current, { side: 'bottom', align: 'start', offset: 6, matchWidth: true });
  }, [open]);

  useEffect(() => setActive(-1), [draft]);

  const add = (text: string, commit: boolean) => {
    const result = tagDraft(text, tags, commit, { separators, max: max ?? null });
    if (result.added.length) {
      setTags([...tags, ...result.added]);
      setAnnounce(say('added', { name: result.added.join('، ') }));
    }
    if (result.duplicates.length) {
      const existing = tags[tagIndex(tags, result.duplicates[0]!)];
      if (existing) setFlash(tagKey(existing));
      setAnnounce(say('duplicate', { name: result.duplicates[0]! }));
    }
    if (result.overflow.length && max != null) setAnnounce(say('limit', { max: new Intl.NumberFormat(intl).format(max) }));
    setDraft(result.rest);
  };

  const remove = (tag: string, focusNext = false) => {
    const at = tagIndex(tags, tag);
    if (at < 0) return;
    const next = tags.filter((_, i) => i !== at);
    setTags(next);
    setAnnounce(say('removed', { name: tag }));
    if (focusNext) {
      requestAnimationFrame(() => {
        const buttons = Array.from(field.current?.querySelectorAll<HTMLButtonElement>('.nx-taginput-chip:not([data-state="exit"]) .nx-taginput-chip-remove') ?? []);
        (buttons[at] ?? buttons[at - 1] ?? input.current)?.focus();
      });
    }
  };

  const onKeyDown = (event: ReactKeyboardEvent<HTMLInputElement>) => {
    const k = event.key;
    if (open && (k === 'ArrowDown' || k === 'ArrowUp')) {
      event.preventDefault();
      const all = offered.map((_, i) => i);
      setActive(comboStep(all, active, k === 'ArrowDown' ? 1 : -1));
    } else if (k === 'Enter') {
      if (open && active >= 0) {
        event.preventDefault();
        add(offered[active]!, true);
      } else if (draft.trim()) {
        event.preventDefault();
        add(draft, true);
      }
    } else if (k === 'Backspace' && draft === '' && tags.length) {
      event.preventDefault();
      remove(tags[tags.length - 1]!);
    } else if (k === 'Escape' && open) {
      event.preventDefault();
      setDraft('');
    }
  };

  const countText = max != null ? `${new Intl.NumberFormat(intl).format(tags.length)} / ${new Intl.NumberFormat(intl).format(max)}` : null;

  return (
    <div className={cx('nx-taginput', className)} data-full={full ? '' : undefined} {...rest}>
      <div className="nx-taginput-field" ref={field} onClick={(e) => e.target === e.currentTarget && input.current?.focus()}>
        <ul className="nx-taginput-chips" aria-label={label ?? say('tags')}>
          {chips.map((chip) => (
            <li key={chip.key} className="nx-taginput-chip" data-state={chip.state} data-flash={flash === chip.key ? '' : undefined}>
              <span>{chip.tag}</span>
              {!disabled && (
                <button
                  type="button"
                  className="nx-taginput-chip-remove"
                  aria-label={say('remove', { name: chip.tag })}
                  tabIndex={chip.state === 'exit' ? -1 : undefined}
                  onClick={() => remove(chip.tag, true)}
                >
                  <Icon name="x" />
                </button>
              )}
            </li>
          ))}
        </ul>
        <input
          ref={input}
          id={inputId}
          className="nx-taginput-input"
          type="text"
          autoComplete="off"
          enterKeyHint="enter"
          aria-label={label ?? say('tags')}
          role={suggestions.length ? 'combobox' : undefined}
          aria-expanded={suggestions.length ? open : undefined}
          aria-controls={suggestions.length ? listId : undefined}
          aria-autocomplete={suggestions.length ? 'list' : undefined}
          aria-activedescendant={open && active >= 0 ? `${listId}-${active}` : undefined}
          placeholder={full ? undefined : (placeholder ?? say('addTag'))}
          disabled={disabled}
          readOnly={full}
          value={draft}
          onChange={(e) => {
            const text = e.target.value;
            if (separators?.some((s) => text.includes(s)) || /[,،;\n]/.test(text)) add(text, false);
            else setDraft(text);
          }}
          onPaste={(e) => {
            const text = e.clipboardData.getData('text');
            if (/[,،;\n]/.test(text)) {
              e.preventDefault();
              add(draft + text, true);
            }
          }}
          onKeyDown={onKeyDown}
          onFocus={() => setFocused(true)}
          onBlur={(e) => {
            if (pop.current?.contains(e.relatedTarget as Node)) return;
            setFocused(false);
            if (draft.trim()) add(draft, true);
          }}
        />
      </div>
      {name && tags.map((tag) => <input key={tagKey(tag)} type="hidden" name={`${name}[]`} value={tag} />)}
      {showCount && countText && (
        <p className="nx-taginput-meta">
          <span>{full ? say('limit', { max: new Intl.NumberFormat(intl).format(max!) }) : ''}</span>
          <span className="nx-taginput-count">{countText}</span>
        </p>
      )}
      <p className="nx-visually-hidden" role="status" aria-live="polite">
        {announce}
      </p>
      {suggestions.length > 0 && (
        <div ref={pop} className="nx-picker-pop nx-taginput-popover" popover="manual" onMouseDown={(e) => e.preventDefault()}>
          <ul id={listId} className="nx-combobox-list" role="listbox" aria-label={label ?? say('tags')}>
            {offered.map((s, i) => (
              <li
                key={s}
                id={`${listId}-${i}`}
                className="nx-combobox-option"
                role="option"
                aria-selected={false}
                data-active={i === active ? '' : undefined}
                style={vars({ '--nx-i': i })}
                onPointerMove={() => setActive(i)}
                onClick={() => {
                  add(s, true);
                  input.current?.focus();
                }}
              >
                <Icon name="plus" />
                <span className="nx-combobox-option-label">
                  {comboSegments(s, draft).map((seg, j) => (seg.match ? <mark key={j}>{seg.text}</mark> : <span key={j}>{seg.text}</span>))}
                </span>
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
}

/* ======================================================================================== */
/* ---- Context menu ---------------------------------------------------------------------- */
/* ======================================================================================== */

export interface ContextMenuItem {
  /** Reported to onSelect; separators and headings need none. */
  id?: string;
  label?: string;
  icon?: IconName;
  /** Shown at the end ("⌘C"); purely a hint. */
  shortcut?: string;
  disabled?: boolean;
  tone?: 'danger';
  separator?: boolean;
  /** A small group title instead of an item. */
  heading?: boolean;
  /** One level of submenu. */
  items?: ContextMenuItem[];
}

export interface ContextMenuProps extends Omit<HTMLAttributes<HTMLDivElement>, 'onSelect'> {
  items: ContextMenuItem[];
  onSelect?: (id: string) => void;
  /** The menu's accessible name. */
  label?: string;
  /** The visually hidden hint telling keyboard users how to open it. */
  hint?: string;
  /** Long-press time on touch, ms. */
  pressDelay?: number;
  locale?: string;
  children: ReactNode;
}

const ITEM = ':scope > .nx-ctxmenu-item';

function MenuItems({ items, onPick, depth }: { items: ContextMenuItem[]; onPick: (id: string) => void; depth: number }) {
  return (
    <>
      {items.map((item, i) => {
        if (item.separator) return <hr key={`s${i}`} className="nx-ctxmenu-sep" />;
        if (item.heading) return <p key={`h${i}`} className="nx-ctxmenu-heading" role="presentation">{item.label}</p>;
        if (item.items?.length) return <SubMenu key={item.id ?? i} item={item} onPick={onPick} depth={depth} />;
        return (
          <button
            key={item.id ?? i}
            type="button"
            role="menuitem"
            tabIndex={-1}
            className="nx-ctxmenu-item"
            data-tone={item.tone}
            aria-disabled={item.disabled || undefined}
            onClick={() => !item.disabled && item.id && onPick(item.id)}
          >
            {item.icon && <Icon name={item.icon} />}
            <span className="nx-ctxmenu-label">{item.label}</span>
            {item.shortcut && <kbd className="nx-ctxmenu-shortcut" dir="ltr">{item.shortcut}</kbd>}
          </button>
        );
      })}
    </>
  );
}

function SubMenu({ item, onPick, depth }: { item: ContextMenuItem; onPick: (id: string) => void; depth: number }) {
  const [open, setOpen] = useState(false);
  const button = useRef<HTMLButtonElement>(null);
  const menu = useRef<HTMLDivElement>(null);
  const hover = useRef<ReturnType<typeof setTimeout>>(undefined);

  useIsoLayoutEffect(() => {
    showPop(menu.current, open);
    if (!open || !button.current || !menu.current) return;
    return place(button.current, menu.current, { side: 'inline-end', align: 'start', offset: 2 });
  }, [open]);

  useEffect(() => {
    const el = menu.current;
    if (!el) return;
    const onToggle = (event: Event) => setOpen((event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => {
      el.removeEventListener('toggle', onToggle);
      clearTimeout(hover.current);
    };
  }, []);

  const openSub = (focusFirst: boolean) => {
    setOpen(true);
    if (focusFirst) requestAnimationFrame(() => menu.current && focusableItems(menu.current, ITEM)[0]?.focus());
  };

  return (
    <>
      <button
        ref={button}
        type="button"
        role="menuitem"
        tabIndex={-1}
        className="nx-ctxmenu-item"
        aria-haspopup="menu"
        aria-expanded={open}
        aria-disabled={item.disabled || undefined}
        data-open={open ? '' : undefined}
        onClick={() => !item.disabled && openSub(true)}
        onPointerEnter={() => {
          clearTimeout(hover.current);
          if (!item.disabled) hover.current = setTimeout(() => openSub(false), 120);
        }}
        onPointerLeave={() => clearTimeout(hover.current)}
        onKeyDown={(e) => {
          const forward = getComputedStyle(e.currentTarget).direction === 'rtl' ? 'ArrowLeft' : 'ArrowRight';
          if (e.key === forward && !item.disabled) {
            e.preventDefault();
            e.stopPropagation();
            openSub(true);
          }
        }}
      >
        {item.icon && <Icon name={item.icon} />}
        <span className="nx-ctxmenu-label">{item.label}</span>
        <Icon name="chevron-right" className="nx-ctxmenu-sub-arrow" data-directional="" />
      </button>
      <div
        ref={menu}
        className="nx-picker-pop nx-ctxmenu-menu"
        data-sub=""
        popover="auto"
        role="menu"
        aria-label={item.label}
        onKeyDown={(e) => {
          const back = getComputedStyle(e.currentTarget).direction === 'rtl' ? 'ArrowRight' : 'ArrowLeft';
          if (e.key === back || e.key === 'Escape') {
            e.preventDefault();
            e.stopPropagation();
            setOpen(false);
            button.current?.focus();
          }
        }}
      >
        <MenuItems items={item.items ?? []} onPick={onPick} depth={depth + 1} />
      </div>
    </>
  );
}

/** A right-click (long-press, Shift+F10) menu for whatever it wraps, opening at the pointer. */
export function ContextMenu({ items, onSelect, label, hint, pressDelay = 500, locale, className, children, ...rest }: ContextMenuProps) {
  const intl = useIntlLocale(locale);
  const hintId = `nx-ctx-${useId().replace(/:/g, '')}-hint`;
  const area = useRef<HTMLDivElement>(null);
  const menu = useRef<HTMLDivElement>(null);
  const restore = useRef<HTMLElement | null>(null);
  const typeahead = useMemo(() => createTypeahead(), []);
  const select = useEvent(onSelect);

  useEffect(() => {
    const el = area.current;
    const m = menu.current;
    if (!el || !m) return;
    const onToggle = (event: Event) => {
      if ((event as ToggleEvent).newState === 'closed' && restore.current && (m.contains(document.activeElement) || document.activeElement === document.body)) {
        restore.current.focus();
      }
    };
    m.addEventListener('toggle', onToggle);
    const off = ctxMenuTrigger(el, {
      delay: pressDelay,
      onOpen: (point, event) => {
        restore.current = (document.activeElement as HTMLElement | null) && el.contains(document.activeElement) ? (document.activeElement as HTMLElement) : el;
        showPop(m, false);
        showPop(m, true);
        placeAtPoint(m, point);
        const keyboard = event.type === 'keydown';
        requestAnimationFrame(() => (keyboard ? focusableItems(m, ITEM)[0]?.focus() : m.focus({ preventScroll: true })));
      },
    });
    return () => {
      off();
      m.removeEventListener('toggle', onToggle);
    };
  }, [pressDelay]);

  const pick = (id: string) => {
    showPop(menu.current, false);
    select(id);
  };

  const onMenuKey = (event: ReactKeyboardEvent<HTMLDivElement>) => {
    const host = (event.target as HTMLElement).closest('.nx-ctxmenu-menu') as HTMLElement | null;
    if (!host) return;
    const rows = focusableItems(host, ITEM);
    if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Home' || event.key === 'End') {
      event.preventDefault();
      const at = rows.indexOf(document.activeElement as HTMLElement);
      let next: number;
      if (event.key === 'Home') next = 0;
      else if (event.key === 'End') next = rows.length - 1;
      else if (at < 0) next = event.key === 'ArrowDown' ? 0 : rows.length - 1;
      else next = (at + (event.key === 'ArrowDown' ? 1 : -1) + rows.length) % rows.length;
      rows[next]?.focus();
      return;
    }
    if (event.key === 'Tab') {
      event.preventDefault();
      showPop(menu.current, false);
      return;
    }
    const at = rows.indexOf(document.activeElement as HTMLElement);
    const hit = typeahead(event.key, rows, at);
    if (hit) hit.focus();
  };

  return (
    <div ref={area} className={cx('nx-ctxmenu', className)} tabIndex={0} aria-describedby={hintId} {...rest}>
      {children}
      <span id={hintId} className="nx-visually-hidden">
        {hint ?? pickerWord(intl, 'menuHint')}
      </span>
      <div
        ref={menu}
        className="nx-picker-pop nx-ctxmenu-menu"
        popover="auto"
        role="menu"
        tabIndex={-1}
        aria-label={label ?? pickerWord(intl, 'actions')}
        onKeyDown={onMenuKey}
        onContextMenu={(e) => e.preventDefault()}
      >
        <MenuItems items={items} onPick={pick} depth={0} />
      </div>
    </div>
  );
}

/* ======================================================================================== */
/* ---- Hover card ------------------------------------------------------------------------ */
/* ======================================================================================== */

export interface HoverCardProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'content'> {
  /** What you hover: usually a link or an avatar. */
  trigger: ReactNode;
  /** The card's content. */
  children: ReactNode;
  openDelay?: number;
  closeDelay?: number;
  side?: Side;
  align?: Align;
  cardClassName?: string;
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
}

/** A rich preview on hover or keyboard focus, staying open while the pointer is inside it. */
export function HoverCard({ trigger, children, openDelay = 500, closeDelay = 250, side = 'bottom', align = 'center', cardClassName, open, defaultOpen = false, onOpenChange, className, ...rest }: HoverCardProps) {
  const [shown, setShown] = useControllable(open, defaultOpen, onOpenChange);
  const id = `nx-hc-${useId().replace(/:/g, '')}`;
  const anchor = useRef<HTMLSpanElement>(null);
  const card = useRef<HTMLSpanElement>(null);
  const change = useEvent(setShown);

  useEffect(() => {
    if (!anchor.current || !card.current) return;
    const ctrl = hoverCard(anchor.current, card.current, { openDelay, closeDelay, onOpenChange: (next) => change(next) });
    return ctrl.destroy;
  }, [openDelay, closeDelay, change]);

  useIsoLayoutEffect(() => {
    showPop(card.current, shown);
    if (!shown || !anchor.current || !card.current) return;
    return place(anchor.current, card.current, { side, align, offset: 8 });
  }, [shown, side, align]);

  return (
    <span className={cx('nx-hovercard', className)} {...rest}>
      <span ref={anchor} className="nx-hovercard-trigger" aria-describedby={id}>
        {trigger}
      </span>
      <span ref={card} id={id} className={cx('nx-picker-pop nx-hovercard-card', cardClassName)} popover="manual">
        {children}
      </span>
    </span>
  );
}

/* ======================================================================================== */
/* ---- Bottom sheet ---------------------------------------------------------------------- */
/* ======================================================================================== */

export interface BottomSheetProps extends Omit<HTMLAttributes<HTMLDialogElement>, 'title'> {
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  title?: ReactNode;
  /** Snap heights as fractions of the viewport (0–1). */
  snaps?: number[];
  /** Which snap it opens at. */
  initialSnap?: number;
  onSnapChange?: (index: number) => void;
  /** Allow closing by dragging down, a flick, the backdrop or Escape (default). */
  dismissible?: boolean;
  closeLabel?: string;
  locale?: string;
  children?: ReactNode;
}

/** A draggable sheet with snap points, flick-to-dismiss and a rubber band, on a native <dialog>. */
export function BottomSheet({ open, defaultOpen = false, onOpenChange, title, snaps = [0.5, 0.9], initialSnap = 0, onSnapChange, dismissible = true, closeLabel, locale, className, children, ...rest }: BottomSheetProps) {
  const intl = useIntlLocale(locale);
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const [snap, setSnap] = useState(initialSnap);
  const dialog = useRef<HTMLDialogElement>(null);
  const ctrl = useRef<ReturnType<typeof bottomSheet> | null>(null);
  const titleId = `nx-bs-${useId().replace(/:/g, '')}`;
  const snapKey = snaps.join(',');
  const notifySnap = useEvent(onSnapChange);

  useIsoLayoutEffect(() => {
    const el = dialog.current;
    if (!el) return;
    if (isOpen && !el.open) {
      ctrl.current = bottomSheet(el, {
        snaps,
        initial: initialSnap,
        dismissible,
        onSnap: (i) => {
          setSnap(i);
          notifySnap(i);
        },
        onDismiss: () => setOpen(false),
      });
      setSnap(initialSnap);
      el.showModal();
    }
    if (!isOpen && el.open) el.close();
    return () => {
      if (!isOpen) return;
      ctrl.current?.destroy();
      ctrl.current = null;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isOpen, snapKey, dismissible]);

  const percent = Math.round((snaps[snap] ?? 0) * 100);

  return (
    <dialog
      ref={dialog}
      className={cx('nx-bsheet', className)}
      aria-labelledby={title ? titleId : undefined}
      onCancel={(e) => {
        e.preventDefault();
        if (dismissible) setOpen(false);
      }}
      onClose={() => isOpen && setOpen(false)}
      onClick={(e) => {
        // A click on the backdrop lands on the dialog itself, outside its box.
        if (e.target === e.currentTarget && dismissible && e.clientY < e.currentTarget.getBoundingClientRect().top) setOpen(false);
      }}
      {...rest}
    >
      <header className="nx-bsheet-head">
        <button
          type="button"
          className="nx-bsheet-handle"
          data-sheet-handle=""
          aria-label={pickerWord(intl, 'handle')}
          aria-description={`${new Intl.NumberFormat(intl, { style: 'percent' }).format(percent / 100)}`}
          onClick={() => ctrl.current?.snapTo((snap + 1) % snaps.length)}
          onKeyDown={(e) => {
            if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
              e.preventDefault();
              ctrl.current?.snapTo(snap + (e.key === 'ArrowUp' ? 1 : -1));
            }
          }}
        />
        {(title || dismissible) && (
          <div className="nx-bsheet-titles">
            {title ? <h2 id={titleId} className="nx-bsheet-title">{title}</h2> : <span />}
            {dismissible && (
              <button type="button" className="nx-bsheet-close" aria-label={closeLabel ?? translate(intl.slice(0, 2), 'close')} onClick={() => setOpen(false)}>
                <Icon name="x" />
              </button>
            )}
          </div>
        )}
      </header>
      <div className="nx-bsheet-body">{children}</div>
    </dialog>
  );
}

/* ======================================================================================== */
/* ---- Date-range picker ----------------------------------------------------------------- */
/* ======================================================================================== */

export interface DateRangePreset {
  id: string;
  label: string;
  range: DateRange;
}

export interface DateRangePickerProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  value?: DateRange;
  defaultValue?: DateRange;
  onValueChange?: (range: DateRange) => void;
  /** 'persian' (Jalali) by default for fa, 'gregory' elsewhere. */
  calendar?: PickerCalendar;
  /** 0 Sunday, 1 Monday, 6 Saturday; follows the locale by default. */
  weekStart?: 0 | 1 | 6;
  /** Built-in preset ids or your own ranges; [] hides the list. */
  presets?: Array<RangePresetId | DateRangePreset>;
  /** Earliest and latest pickable days, "YYYY-MM-DD". */
  min?: string;
  max?: string;
  /** How many months show side by side (1 or 2). */
  months?: 1 | 2;
  /** Posts name[start] and name[end]. */
  name?: string;
  placeholder?: string;
  label?: string;
  /** "Today" for the presets and the dot; defaults to the device's date. */
  today?: string;
  locale?: string;
  labels?: Partial<Record<PickerWord, string>>;
}

const DEFAULT_PRESETS: RangePresetId[] = ['today', 'yesterday', 'last7', 'last30', 'thisMonth', 'lastMonth'];

/** Two months of days: click a start and an end (the range previews as you hover), or pick a preset. */
export function DateRangePicker({
  value, defaultValue = { start: null, end: null }, onValueChange, calendar, weekStart, presets = DEFAULT_PRESETS, min, max, months = 2,
  name, placeholder, label, today: todayProp, locale, labels, className, ...rest
}: DateRangePickerProps) {
  const intl = useIntlLocale(locale);
  const say = useWords(intl, labels);
  const cal = calendar ?? defaultCalendar(intl);
  const week = weekStart ?? defaultWeekStart(intl);
  const [range, setRange] = useControllable<DateRange>(value, defaultValue, onValueChange);
  const [today, setToday] = useState(todayProp ?? '');
  useEffect(() => setToday(todayProp ?? isoToday()), [todayProp]);
  const [open, setOpen] = useState(false);
  const [draft, setDraft] = useState<DateRange>(range);
  const [hover, setHover] = useState<string | null>(null);
  const [view, setView] = useState<string>(() => monthStartOf(range.start ?? todayProp ?? '2026-01-01', cal));
  const [focusDay, setFocusDay] = useState<string>(range.start ?? todayProp ?? '');
  const [enter, setEnter] = useState<'prev' | 'next' | null>(null);
  const base = `nx-dr-${useId().replace(/:/g, '')}`;
  const trigger = useRef<HTMLButtonElement>(null);
  const pop = useRef<HTMLDivElement>(null);
  const monthsRef = useRef<HTMLDivElement>(null);

  const fmt = useMemo(() => ({
    title: dayFormatter(intl, cal, { month: 'long', year: 'numeric' }),
    day: dayFormatter(intl, cal, { day: 'numeric' }),
    long: dayFormatter(intl, cal, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }),
    short: dayFormatter(intl, cal, { day: 'numeric', month: 'short', year: 'numeric' }),
    number: new Intl.NumberFormat(intl),
  }), [intl, cal]);
  const weekdays = useMemo(() => pickerWeekdays(intl, week, 'narrow'), [intl, week]);
  const longWeekdays = useMemo(() => pickerWeekdays(intl, week, 'long'), [intl, week]);

  const presetList: DateRangePreset[] = useMemo(() => {
    if (!today) return [];
    return presets.map((p) => (typeof p === 'string' ? { id: p, label: say(p), range: rangePreset(p, today, cal) } : p));
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [presets, today, cal, intl, labels]);

  useEffect(() => {
    if (supportsPopover()) trigger.current?.setAttribute('popovertarget', `${base}-pop`);
  }, [base]);

  useEffect(() => {
    const el = pop.current;
    if (!el) return;
    const onToggle = (event: Event) => setOpen((event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  }, []);

  // Opening: start from the committed range, show its month, focus a day.
  useIsoLayoutEffect(() => {
    if (!open || !trigger.current || !pop.current) return;
    setDraft(range);
    setHover(null);
    const anchor = range.start ?? today;
    if (anchor) {
      setView(monthStartOf(anchor, cal));
      setFocusDay(anchor);
    }
    requestAnimationFrame(() => pop.current?.querySelector<HTMLButtonElement>('.nx-daterange-day[tabindex="0"]')?.focus());
    return place(trigger.current, pop.current, { side: 'bottom', align: 'start', offset: 8 });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [open]);

  useEffect(() => {
    if (!enter) return;
    const timer = setTimeout(() => setEnter(null), 400);
    return () => clearTimeout(timer);
  }, [enter, view]);

  const shown = useMemo(() => Array.from({ length: months }, (_, i) => shiftCalendarMonth(view, i, cal)), [view, months, cal]);
  const blocked = (iso: string) => (min != null && iso < min) || (max != null && iso > max);

  const go = (step: number) => {
    setEnter(step > 0 ? 'next' : 'prev');
    setView(shiftCalendarMonth(view, step, cal));
  };

  const close = () => {
    showPop(pop.current, false);
    trigger.current?.focus();
  };

  const commit = (next: DateRange) => {
    setRange(next);
    setTimeout(close, 220);
  };

  const click = (iso: string) => {
    if (blocked(iso)) return;
    const next = rangeClick(draft, iso);
    setDraft(next);
    setFocusDay(iso);
    if (next.end) commit(next);
  };

  // Keep the focused day in view and focused.
  const moveFocus = (iso: string) => {
    setFocusDay(iso);
    if (draft.start && !draft.end) setHover(iso);
    const first = shown[0]!;
    const lastStart = shown[shown.length - 1]!;
    if (iso < first) go(-1);
    else if (iso >= shiftCalendarMonth(lastStart, 1, cal)) go(1);
    requestAnimationFrame(() => pop.current?.querySelector<HTMLButtonElement>(`.nx-daterange-day[data-date="${iso}"]`)?.focus());
  };

  const onGridKey = (event: ReactKeyboardEvent) => {
    const rtl = getComputedStyle(event.currentTarget).direction === 'rtl';
    const side = rtl ? -1 : 1;
    let next: string | null = null;
    switch (event.key) {
      case 'ArrowRight': next = isoAddDays(focusDay, side); break;
      case 'ArrowLeft': next = isoAddDays(focusDay, -side); break;
      case 'ArrowDown': next = isoAddDays(focusDay, 7); break;
      case 'ArrowUp': next = isoAddDays(focusDay, -7); break;
      case 'PageDown': next = isoAddDays(shiftCalendarMonth(focusDay, 1, cal), calendarParts(focusDay, cal).day - 1); break;
      case 'PageUp': next = isoAddDays(shiftCalendarMonth(focusDay, -1, cal), calendarParts(focusDay, cal).day - 1); break;
      case 'Home': next = isoAddDays(focusDay, -((new Date(`${focusDay}T00:00:00Z`).getUTCDay() - week + 7) % 7)); break;
      case 'End': next = isoAddDays(focusDay, 6 - ((new Date(`${focusDay}T00:00:00Z`).getUTCDay() - week + 7) % 7)); break;
      default: return;
    }
    event.preventDefault();
    moveFocus(next);
  };

  const preview = draft.start && !draft.end ? hover : null;
  const markEnd = draft.end ?? preview;
  const valueText = range.start && range.end
    ? range.start === range.end ? fmt.short(range.start) : `${fmt.short(range.start)} – ${fmt.short(range.end)}`
    : null;
  const draftDays = draft.start && (draft.end ?? preview) ? Math.abs(isoDaysBetween(draft.start, (draft.end ?? preview)!)) + 1 : 0;
  const summary = draft.start
    ? draft.end || preview
      ? (() => {
          const [a, b] = orderRange(draft.start!, (draft.end ?? preview)!);
          return `${fmt.short(a)} – ${fmt.short(b)} · ${say('nights', { count: fmt.number.format(draftDays) })}`;
        })()
      : `${fmt.short(draft.start)} · ${say('selectEnd')}`
    : say('pickRange');
  const activePreset = presetList.find((p) => p.range.start === range.start && p.range.end === range.end)?.id;

  return (
    <div className={cx('nx-daterange', className)} {...rest}>
      <button
        ref={trigger}
        type="button"
        className="nx-daterange-trigger"
        aria-haspopup="dialog"
        aria-expanded={open}
        aria-controls={`${base}-pop`}
        aria-label={label ? `${label}: ${valueText ?? placeholder ?? say('pickRange')}` : undefined}
        onClick={() => !supportsPopover() && showPop(pop.current, !open)}
      >
        <Icon name="grid" />
        <span className="nx-daterange-value" data-empty={valueText ? undefined : ''}>
          {valueText ?? placeholder ?? say('pickRange')}
        </span>
        <Icon name="chevron-down" />
      </button>
      {name && (
        <>
          <input type="hidden" name={`${name}[start]`} value={range.start ?? ''} />
          <input type="hidden" name={`${name}[end]`} value={range.end ?? ''} />
        </>
      )}
      <div
        ref={pop}
        id={`${base}-pop`}
        className="nx-picker-pop nx-daterange-popover"
        popover="auto"
        role="dialog"
        aria-label={label ?? say('pickRange')}
        style={presetList.length ? undefined : { gridTemplateColumns: '1fr' }}
      >
        {presetList.length > 0 && (
          <ul className="nx-daterange-presets" aria-label={say('presets')}>
            {presetList.map((p) => (
              <li key={p.id}>
                <button
                  type="button"
                  className="nx-daterange-preset"
                  aria-pressed={p.id === activePreset}
                  onClick={() => {
                    setDraft(p.range);
                    if (p.range.start) setView(monthStartOf(p.range.start, cal));
                    commit(p.range);
                  }}
                >
                  {p.label}
                </button>
              </li>
            ))}
          </ul>
        )}
        <div className="nx-daterange-main">
          <div ref={monthsRef} className="nx-daterange-months" data-enter={enter ?? undefined} style={vars({ '--nx-daterange-months': months })}>
            {open &&
              shown.map((start, m) => {
                const weeks = rangeMonthWeeks(start, cal, week);
                const titleId = `${base}-m${m}`;
                return (
                  <section key={start} className="nx-daterange-month">
                    <header className="nx-daterange-head">
                      <button type="button" className="nx-daterange-nav" data-at="prev" aria-label={translate(intl.slice(0, 2), 'calendarPrevMonth')} onClick={() => go(-1)}>
                        <Icon name="chevron-left" data-directional="" />
                      </button>
                      <h3 id={titleId} aria-live="polite">{fmt.title(isoAddDays(start, 14))}</h3>
                      <button type="button" className="nx-daterange-nav" data-at="next" aria-label={translate(intl.slice(0, 2), 'calendarNextMonth')} onClick={() => go(1)}>
                        <Icon name="chevron-right" data-directional="" />
                      </button>
                    </header>
                    <div className="nx-daterange-grid" role="grid" aria-labelledby={titleId} onKeyDown={onGridKey}>
                      <div className="nx-daterange-row" role="row">
                        {weekdays.map((w, i) => (
                          <span key={i} className="nx-daterange-weekday" role="columnheader" aria-label={longWeekdays[i]}>
                            {w}
                          </span>
                        ))}
                      </div>
                      {weeks.map((row, r) => (
                        <div key={r} className="nx-daterange-row" role="row">
                          {row.map((iso, c) => {
                            if (!iso) return <span key={c} className="nx-daterange-cell" role="gridcell" />;
                            const mark = rangeMark(iso, draft.start, markEnd);
                            const isPreview = !!preview && !!mark && mark !== 'single';
                            return (
                              <span
                                key={c}
                                className="nx-daterange-cell"
                                role="gridcell"
                                data-mark={mark ?? undefined}
                                data-preview={isPreview ? '' : undefined}
                                aria-selected={mark ? true : undefined}
                              >
                                <button
                                  type="button"
                                  className="nx-daterange-day"
                                  data-date={iso}
                                  data-today={iso === today ? '' : undefined}
                                  aria-current={iso === today ? 'date' : undefined}
                                  aria-label={fmt.long(iso)}
                                  tabIndex={iso === focusDay ? 0 : -1}
                                  disabled={blocked(iso)}
                                  onClick={() => click(iso)}
                                  onPointerEnter={() => draft.start && !draft.end && setHover(iso)}
                                  onFocus={() => draft.start && !draft.end && setHover(iso)}
                                >
                                  {fmt.day(iso)}
                                </button>
                              </span>
                            );
                          })}
                        </div>
                      ))}
                    </div>
                  </section>
                );
              })}
          </div>
          <footer className="nx-daterange-foot">
            <p className="nx-daterange-summary" role="status" aria-live="polite">{summary}</p>
            <div className="nx-daterange-actions">
              <button
                type="button"
                className="nx-daterange-preset"
                onClick={() => {
                  setDraft({ start: null, end: null });
                  setRange({ start: null, end: null });
                }}
              >
                {say('clear')}
              </button>
            </div>
          </footer>
        </div>
      </div>
    </div>
  );
}

/* ======================================================================================== */
/* ---- Time picker ----------------------------------------------------------------------- */
/* ======================================================================================== */

export interface TimePickerProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  /** "HH:MM", 24-hour, whatever the face shows. */
  value?: string | null;
  defaultValue?: string | null;
  onValueChange?: (value: string) => void;
  /** 12 or 24 hours on the face; follows the locale by default. */
  hourCycle?: 12 | 24;
  /** Minutes between stops on the minute wheel (1, 5, 15…). */
  minuteStep?: number;
  name?: string;
  label?: string;
  locale?: string;
  labels?: Partial<Record<'hour' | 'minute' | 'period', string>>;
}

interface WheelProps {
  items: string[];
  index: number;
  onIndex: (index: number) => void;
  label: string;
  id: string;
  period?: boolean;
}

function Wheel({ items, index, onIndex, label, id, period }: WheelProps) {
  const list = useRef<HTMLUListElement>(null);
  const settled = useRef(index);
  const pick = useEvent(onIndex);

  useEffect(() => {
    if (!list.current) return;
    return snapWheel(list.current, {
      onSelect: (i) => {
        if (i === settled.current) return;
        settled.current = i;
        pick(i);
      },
    });
  }, [pick]);

  // The value moved from outside (or a key): bring the wheel to it.
  useEffect(() => {
    if (!list.current) return;
    settled.current = index;
    wheelTo(list.current, index, true);
  }, [index]);

  useIsoLayoutEffect(() => {
    if (list.current) wheelTo(list.current, index, false);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [items.length]);

  const onKey = (event: ReactKeyboardEvent) => {
    const n = items.length;
    let next: number | null = null;
    if (event.key === 'ArrowDown') next = (index + 1) % n;
    else if (event.key === 'ArrowUp') next = (index - 1 + n) % n;
    else if (event.key === 'PageDown') next = Math.min(n - 1, index + 5);
    else if (event.key === 'PageUp') next = Math.max(0, index - 5);
    else if (event.key === 'Home') next = 0;
    else if (event.key === 'End') next = n - 1;
    if (next === null) return;
    event.preventDefault();
    pick(next);
  };

  return (
    <ul
      ref={list}
      className="nx-timepicker-wheel"
      role="listbox"
      tabIndex={0}
      aria-label={label}
      aria-activedescendant={`${id}-${index}`}
      data-period={period ? '' : undefined}
      onKeyDown={onKey}
    >
      {items.map((text, i) => (
        <li key={i} id={`${id}-${i}`} className="nx-timepicker-option" role="option" aria-selected={i === index} onClick={() => pick(i)}>
          {text}
        </li>
      ))}
    </ul>
  );
}

/** Hour and minute drums (scroll-snap), 12- or 24-hour, in the locale's digits. */
export function TimePicker({ value, defaultValue = null, onValueChange, hourCycle, minuteStep = 1, name, label, locale, labels, className, ...rest }: TimePickerProps) {
  const intl = useIntlLocale(locale);
  const say = useWords(intl, labels);
  const id = `nx-tp-${useId().replace(/:/g, '')}`;
  const [raw, setRaw] = useControllable<string | null>(value, defaultValue, onValueChange as (v: string | null) => void);
  const [twelve, setTwelve] = useState(hourCycle === 12);
  useEffect(() => setTwelve(hourCycle ? hourCycle === 12 : prefers12h(intl)), [hourCycle, intl]);
  const clock = parseClock(raw) ?? { hour: 9, minute: 0 };
  const minutes = useMemo(() => minuteStops(minuteStep), [minuteStep]);
  const pad = useMemo(() => new Intl.NumberFormat(intl, { minimumIntegerDigits: 2 }), [intl]);
  const plain = useMemo(() => new Intl.NumberFormat(intl), [intl]);
  const periods = useMemo(() => dayPeriods(intl), [intl]);
  const face = to12h(clock.hour);
  const hours = twelve ? Array.from({ length: 12 }, (_, i) => plain.format(i + 1)) : Array.from({ length: 24 }, (_, i) => pad.format(i));
  const hourIndex = twelve ? face.hour - 1 : clock.hour;
  const minuteIndex = Math.max(0, minutes.indexOf(nearestMinute(clock.minute, minuteStep)));

  const set = (hour: number, minute: number) => setRaw(clockText({ hour, minute }));
  const timeText = new Intl.DateTimeFormat(intl, { hour: 'numeric', minute: '2-digit', hour12: twelve, timeZone: 'UTC' }).format(new Date(Date.UTC(2026, 0, 1, clock.hour, clock.minute)));

  return (
    <div className={cx('nx-timepicker', className)} role="group" aria-label={label} {...rest}>
      <div className="nx-timepicker-wheels">
        <span className="nx-timepicker-band" aria-hidden="true" />
        <Wheel id={`${id}-h`} label={say('hour')} items={hours} index={hourIndex} onIndex={(i) => set(twelve ? from12h(i + 1, face.pm) : i, clock.minute)} />
        <span className="nx-timepicker-sep" aria-hidden="true">:</span>
        <Wheel id={`${id}-m`} label={say('minute')} items={minutes.map((m) => pad.format(m))} index={minuteIndex} onIndex={(i) => set(clock.hour, minutes[i]!)} />
        {twelve && <Wheel id={`${id}-p`} label={say('period')} period items={periods} index={face.pm ? 1 : 0} onIndex={(i) => set(from12h(face.hour, i === 1), clock.minute)} />}
      </div>
      <div className="nx-timepicker-foot">
        <p className="nx-timepicker-value" aria-live="polite">{raw ? timeText : '—'}</p>
      </div>
      {name && <input type="hidden" name={name} value={raw ?? ''} />}
    </div>
  );
}

/* ======================================================================================== */
/* ---- Number field ---------------------------------------------------------------------- */
/* ======================================================================================== */

export interface NumberFieldProps extends Omit<HTMLAttributes<HTMLDivElement>, 'defaultValue' | 'onChange'> {
  value?: number | null;
  defaultValue?: number | null;
  onValueChange?: (value: number | null) => void;
  min?: number;
  max?: number;
  step?: number;
  /** PageUp / PageDown and Shift-scrub step. */
  largeStep?: number;
  /** Intl number format: { style: 'currency', currency: 'USD' }, { style: 'unit', unit: 'kilogram' }, … */
  format?: Intl.NumberFormatOptions;
  label?: ReactNode;
  hint?: ReactNode;
  inputId?: string;
  name?: string;
  disabled?: boolean;
  /** Drag the label sideways to change the value (default). */
  scrub?: boolean;
  locale?: string;
  labels?: Partial<Record<'increase' | 'decrease', string>>;
}

/** A number input with stepper buttons (press and hold repeats), drag-to-scrub on its label and rolling digits. */
export function NumberField({
  value, defaultValue = null, onValueChange, min, max, step = 1, largeStep, format, label, hint, inputId, name, disabled = false, scrub = true,
  locale, labels, className, ...rest
}: NumberFieldProps) {
  const intl = useIntlLocale(locale);
  const say = useWords(intl, labels);
  const autoId = `nx-nf-${useId().replace(/:/g, '')}`;
  const id = inputId ?? autoId;
  const [num, setNum] = useControllable<number | null>(value, defaultValue, onValueChange);
  const [draft, setDraft] = useState<string | null>(null);
  const latest = useRef(num);
  latest.current = num;
  const labelRef = useRef<HTMLLabelElement>(null);
  const down = useRef<HTMLButtonElement>(null);
  const up = useRef<HTMLButtonElement>(null);
  const bounds = { min: min ?? -Infinity, max: max ?? Infinity, step };
  const formatter = useMemo(() => new Intl.NumberFormat(intl, { maximumFractionDigits: 20, ...format }), [intl, format]);
  const text = num == null ? '' : formatter.format(num);

  const nudge = useEvent((steps: number) => {
    const from = latest.current ?? (min ?? 0);
    const next = snapNumber(from + steps * step, bounds);
    latest.current = next;
    setNum(next);
  });

  useEffect(() => {
    if (!down.current || !up.current) return;
    const a = pressRepeat(down.current, { onStep: () => nudge(-1) });
    const b = pressRepeat(up.current, { onStep: () => nudge(1) });
    return () => {
      a();
      b();
    };
  }, [nudge]);

  useEffect(() => {
    if (!scrub || disabled || !labelRef.current) return;
    return scrubber(labelRef.current, { onSteps: (n) => nudge(n) });
  }, [scrub, disabled, nudge]);

  const commit = () => {
    if (draft === null) return;
    const parsed = parseAmount(draft, intl);
    setNum(draft.trim() === '' ? null : snapNumber(Number.isNaN(parsed) ? (num ?? 0) : parsed, bounds));
    setDraft(null);
  };

  const onKey = (event: ReactKeyboardEvent<HTMLInputElement>) => {
    const big = largeStep ?? step * 10;
    const k = event.key;
    if (k === 'ArrowUp' || k === 'ArrowDown') {
      event.preventDefault();
      commit();
      nudge((k === 'ArrowUp' ? 1 : -1) * (event.shiftKey ? big / step : 1));
    } else if (k === 'PageUp' || k === 'PageDown') {
      event.preventDefault();
      nudge((k === 'PageUp' ? 1 : -1) * (big / step));
    } else if (k === 'Home' && min != null) {
      event.preventDefault();
      setNum(min);
    } else if (k === 'End' && max != null) {
      event.preventDefault();
      setNum(max);
    } else if (k === 'Enter') commit();
  };

  return (
    <div className={cx('nx-numfield', className)} data-disabled={disabled ? '' : undefined} {...rest}>
      {label && (
        <label ref={labelRef} className="nx-numfield-label" htmlFor={id}>
          {label}
        </label>
      )}
      <div className="nx-numfield-group">
        <button ref={down} type="button" className="nx-numfield-step" data-dir="down" aria-label={say('decrease')} tabIndex={-1} disabled={disabled || (min != null && num != null && num <= min)}>
          <Icon name="minus" />
        </button>
        <span className="nx-numfield-box">
          <input
            id={id}
            className="nx-numfield-input"
            type="text"
            inputMode={step % 1 === 0 && (min ?? 0) >= 0 ? 'numeric' : 'decimal'}
            role="spinbutton"
            autoComplete="off"
            aria-valuenow={num ?? undefined}
            aria-valuemin={min}
            aria-valuemax={max}
            aria-valuetext={text || undefined}
            disabled={disabled}
            value={draft ?? text}
            onChange={(e) => setDraft(e.target.value)}
            onFocus={(e) => e.currentTarget.select()}
            onBlur={commit}
            onKeyDown={onKey}
          />
          {num != null && <NumberTicker className="nx-numfield-display" value={num} locale={intl} format={format} reveal={false} aria-hidden="true" />}
        </span>
        <button ref={up} type="button" className="nx-numfield-step" data-dir="up" aria-label={say('increase')} tabIndex={-1} disabled={disabled || (max != null && num != null && num >= max)}>
          <Icon name="plus" />
        </button>
      </div>
      {hint && <p className="nx-numfield-hint">{hint}</p>}
      {name && <input type="hidden" name={name} value={num ?? ''} />}
    </div>
  );
}
