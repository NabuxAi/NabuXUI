/**
 * Kanban — a column board with native HTML5 drag & drop (React).
 *
 * Thin over css/blocks/kanban.css. Cards are reordered by dragging (or by the
 * "Move to …" items in each card's three-dot menu, which keeps reordering
 * keyboard reachable) and glide with the core FLIP helpers; the drop point is
 * shown by a breathing placeholder pill, column counts roll as digits
 * (numberParts/localeDigits) and the quick-add composer grows in place. State
 * is the columns you pass: controlled (`columns` + `onColumnsChange`) or
 * uncontrolled (`defaultColumns`), with `onMove` / `onAdd` detail callbacks.
 */
import {
  type CSSProperties,
  type DragEvent as ReactDragEvent,
  type FormEvent,
  type HTMLAttributes,
  type ReactNode,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import type { IconName } from '@nabuxai/ui-core';
import { place, playRowFlip, reveal, roveFocus, snapshotRows } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useEvent, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale } from '../internal/provider';
import { Avatar } from '../components/display';
import { NumberTicker } from '../components/text';

/* ---- The block's own words (kept local until they graduate into the core table) --------- */

const words = {
  en: {
    board: 'Board',
    addCard: 'Add a card',
    add: 'Add',
    cardMenu: 'Actions for {name}',
    empty: 'No cards yet',
    moveTo: 'Move to {column}',
    movedTo: '{card} moved to {column}',
    cards: '{count} cards',
  },
  fa: {
    board: 'برد',
    addCard: 'افزودن کارت',
    add: 'افزودن',
    cardMenu: 'کنش‌های «{name}»',
    empty: 'هنوز کاری نیست',
    moveTo: 'انتقال به {column}',
    movedTo: '«{card}» به {column} منتقل شد',
    cards: '{count} کارت',
  },
  ar: {
    board: 'اللوحة',
    addCard: 'إضافة بطاقة',
    add: 'إضافة',
    cancel: 'إلغاء',
    cardMenu: 'إجراءات {name}',
    empty: 'لا بطاقات بعد',
    moveTo: 'نقل إلى {column}',
    movedTo: 'نُقلت {card} إلى {column}',
    cards: '{count} بطاقات',
  },
} as const;

type WordKey = keyof (typeof words)['en'];
type Language = keyof typeof words;

function useWord(): (key: WordKey, params?: Record<string, string | number>) => string {
  const locale = useLocale();
  const language: Language = locale in words ? (locale as Language) : 'en';
  return (key, params = {}) => {
    const text = words[language][key] ?? words.en[key];
    return text.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`));
  };
}

/** The Intl locale: the prop, or the provider's language. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

/* ---- Types ---------------------------------------------------------------------------- */

export type KanbanTone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';

export interface KanbanCardAction {
  label: ReactNode;
  icon?: IconName;
  /** Destructive actions read in the danger colour. */
  danger?: boolean;
  href?: string;
  onSelect?: () => void;
}

export interface KanbanCard {
  id: string;
  title: string;
  description?: string;
  tone?: KanbanTone;
  /** A small line under the title — free text ("Due Friday · 2 comments"). */
  meta?: string;
  assignee?: { name: string; src?: string };
  /** The three-dot menu items; the menu appears when a card has any. */
  actions?: KanbanCardAction[];
}

export interface KanbanColumn {
  id: string;
  title: string;
  tone?: KanbanTone;
  cards: KanbanCard[];
}

export interface KanbanMove {
  card: string;
  from: string;
  to: string;
  index: number;
}

export interface KanbanProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  columns?: KanbanColumn[];
  defaultColumns?: KanbanColumn[];
  onColumnsChange?: (columns: KanbanColumn[]) => void;
  /** Fired for every reorder, whichever way it started (drag or menu). */
  onMove?: (move: KanbanMove, columns: KanbanColumn[]) => void;
  /** Fired when the quick-add composer adds a card. */
  onAdd?: (column: string, title: string, columns: KanbanColumn[]) => void;
  /** Show the per-column quick-add composer (default). */
  quickAdd?: boolean;
  addPlaceholder?: string;
  /** The board's accessible name. */
  label?: string;
  /** Overall board height; each column scrolls inside ("36rem"). */
  height?: string;
  locale?: string;
}

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

const DOTS = 'M12 5.25h.01M12 12h.01M12 18.75h.01';

/** Where a pointer's Y falls in `list`: before which card (its index, or the end). */
function dropIndex(list: HTMLElement, dragging: HTMLElement | null, y: number): number {
  const cards = Array.from(list.querySelectorAll<HTMLElement>('.nx-kanban-card')).filter((card) => card !== dragging);
  for (let i = 0; i < cards.length; i++) {
    const rect = cards[i]!.getBoundingClientRect();
    if (y < rect.top + rect.height / 2) return i;
  }
  return cards.length;
}

export function Kanban({
  columns: controlled,
  defaultColumns,
  onColumnsChange,
  onMove,
  onAdd,
  quickAdd = true,
  addPlaceholder,
  label,
  height,
  locale,
  className,
  style,
  ...rest
}: KanbanProps) {
  const word = useWord();
  const language = useLocale();
  const number = useMemo(() => new Intl.NumberFormat(locale ?? INTL[language]), [locale, language]);
  const base = `nx-kanban${useId().replace(/:/g, '')}`;
  const [columns, setColumns] = useControllable(controlled, defaultColumns ?? [], onColumnsChange);
  const [dragging, setDragging] = useState<string | null>(null);
  const [over, setOver] = useState<{ column: string; index: number } | null>(null);
  const [adding, setAdding] = useState<string | null>(null);
  const [announced, setAnnounced] = useState('');
  const board = useRef<HTMLElement>(null);
  const flight = useRef<ReturnType<typeof snapshotRows> | null>(null);
  useBehavior(board, reveal, { once: true });

  const cardsOf = () => board.current?.querySelectorAll<HTMLElement>('.nx-kanban-card[data-key]') ?? [];

  // Reorders arrive from drops and from card menus alike; both carry the same FLIP.
  const commit = useEvent((move: KanbanMove) => {
    const next = columns.map((col) => ({ ...col, cards: [...col.cards] }));
    const from = next.find((col) => col.id === move.from);
    const to = next.find((col) => col.id === move.to);
    const card = from?.cards.find((c) => c.id === move.card);
    if (!from || !to || !card) return;
    from.cards = from.cards.filter((c) => c.id !== move.card);
    to.cards.splice(Math.max(0, Math.min(move.index, to.cards.length)), 0, card);
    flight.current = snapshotRows(cardsOf());
    setColumns(next);
    onMove?.(move, next);
    setAnnounced(word('movedTo', { card: card.title, column: to.title }));
  });

  useIsoLayoutEffect(() => {
    if (!flight.current) return;
    const before = flight.current;
    flight.current = null;
    playRowFlip(cardsOf(), before);
  }, [columns]);

  /* ---- Native drag & drop ----------------------------------------------------------------- */

  const onDragStart = (event: ReactDragEvent<HTMLLIElement>, card: string) => {
    event.dataTransfer.setData('text/plain', card);
    event.dataTransfer.effectAllowed = 'move';
    setDragging(card);
  };

  const onDragOver = (event: ReactDragEvent<HTMLUListElement>, column: string) => {
    if (!dragging) return;
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
    const source = board.current?.querySelector<HTMLElement>(`.nx-kanban-card[data-key="${CSS.escape(dragging)}"]`) ?? null;
    const index = dropIndex(event.currentTarget, source, event.clientY);
    setOver((current) => (current?.column === column && current.index === index ? current : { column, index }));
  };

  const onDrop = (event: ReactDragEvent<HTMLUListElement>, column: string) => {
    event.preventDefault();
    const card = dragging ?? event.dataTransfer.getData('text/plain');
    const from = columns.find((col) => col.cards.some((c) => c.id === card))?.id;
    setDragging(null);
    setOver(null);
    if (!from) return;
    const source = board.current?.querySelector<HTMLElement>(`.nx-kanban-card[data-key="${CSS.escape(card)}"]`) ?? null;
    commit({ card, from, to: column, index: dropIndex(event.currentTarget, source, event.clientY) });
  };

  const onDragLeave = (event: ReactDragEvent<HTMLUListElement>, column: string) => {
    if (event.currentTarget.contains(event.relatedTarget as Node | null)) return;
    setOver((current) => (current?.column === column ? null : current));
  };

  /* ---- Quick add -------------------------------------------------------------------------- */

  const submit = (event: FormEvent<HTMLFormElement>, column: string) => {
    event.preventDefault();
    const input = event.currentTarget.elements.namedItem('title') as HTMLInputElement | null;
    const title = input?.value.trim();
    if (!title) {
      input?.focus();
      return;
    }
    const id = `card-${Date.now().toString(36)}-${Math.random().toString(36).slice(2, 6)}`;
    const next = columns.map((col) => (col.id === column ? { ...col, cards: [...col.cards, { id, title }] } : col));
    flight.current = snapshotRows(cardsOf());
    setColumns(next);
    onAdd?.(column, title, next);
    if (input) input.value = '';
    input?.focus();
  };

  return (
    <section
      ref={board}
      className={cx('nx-kanban', className)}
      aria-label={label ?? word('board')}
      style={{ ...(height ? vars({ '--nx-kanban-height': height }) : null), ...style }}
      {...rest}
    >
      <div className="nx-kanban-board">
        {columns.map((column) => {
          const heading = `${base}-col-${column.id}`;
          const overHere = over?.column === column.id ? over.index : null;
          // The placeholder sits among the column's *other* cards, so it shows
          // where the dragged card will land once it leaves its old slot.
          const items: ReactNode[] = [];
          let landing = 0;
          column.cards.forEach((card) => {
            if (card.id !== dragging) {
              if (overHere === landing) items.push(<Placeholder key={`ph-${column.id}`} />);
              landing += 1;
            }
            items.push(
              <Card
                key={card.id}
                card={card}
                index={landing}
                dragging={dragging === card.id}
                menuId={`${base}-menu-${card.id}`}
                columns={columns}
                onDragStart={onDragStart}
                onDragEnd={() => {
                  setDragging(null);
                  setOver(null);
                }}
                onMove={commit}
                menuLabel={word('cardMenu', { name: card.title })}
                moveTo={(name: string) => word('moveTo', { column: name })}
              />,
            );
          });
          if (overHere !== null && overHere >= landing) items.push(<Placeholder key={`ph-${column.id}-end`} />);
          return (
            <div
              key={column.id}
              className="nx-kanban-column"
              data-column={column.id}
              data-tone={column.tone}
              data-dropping={overHere !== null ? '' : undefined}
            >
              <header className="nx-kanban-column-head">
                <span className="nx-kanban-column-dot" aria-hidden="true" />
                <h3 className="nx-kanban-column-title" id={heading}>
                  {column.title}
                </h3>
                <span className="nx-kanban-column-count" aria-hidden="true">
                  <NumberTicker value={column.cards.length} reveal={false} locale={locale} />
                </span>
                <span className="nx-visually-hidden">{word('cards', { count: number.format(column.cards.length) })}</span>
              </header>
              <ul
                className="nx-kanban-list"
                aria-labelledby={heading}
                onDragOver={(event) => onDragOver(event, column.id)}
                onDrop={(event) => onDrop(event, column.id)}
                onDragLeave={(event) => onDragLeave(event, column.id)}
              >
                {items}
                {column.cards.length === 0 && overHere === null && <li className="nx-kanban-empty">{word('empty')}</li>}
              </ul>
              {quickAdd && (
                <div className="nx-kanban-add">
                  <button
                    type="button"
                    className="nx-kanban-add-open"
                    aria-expanded={adding === column.id}
                    aria-controls={`${base}-add-${column.id}`}
                    onClick={() => setAdding(adding === column.id ? null : column.id)}
                  >
                    <Icon name="plus" />
                    <span>{word('addCard')}</span>
                  </button>
                  <div className="nx-kanban-add-form" id={`${base}-add-${column.id}`} data-open={adding === column.id ? '' : undefined}>
                    <form className="nx-kanban-add-body" onSubmit={(event) => submit(event, column.id)}>
                      <input
                        className="nx-kanban-add-input"
                        name="title"
                        type="text"
                        autoComplete="off"
                        placeholder={addPlaceholder ?? word('addCard')}
                        aria-label={word('addCard')}
                      />
                      <button type="submit" className="nx-kanban-add-submit">
                        {word('add')}
                      </button>
                    </form>
                  </div>
                </div>
              )}
            </div>
          );
        })}
      </div>
      {/* The move, spoken once it lands. */}
      <p className="nx-visually-hidden" role="status">
        {announced}
      </p>
    </section>
  );
}

/* ---- The placeholder pill ---------------------------------------------------------------- */

function Placeholder() {
  return (
    <li className="nx-kanban-placeholder" aria-hidden="true">
      <span className="nx-kanban-placeholder-dot" />
      <span className="nx-kanban-placeholder-bar" />
    </li>
  );
}

/* ---- One card, with its three-dot menu ----------------------------------------------------- */

interface CardProps {
  card: KanbanCard;
  index: number;
  dragging: boolean;
  menuId: string;
  columns: KanbanColumn[];
  onDragStart: (event: ReactDragEvent<HTMLLIElement>, card: string) => void;
  onDragEnd: () => void;
  onMove: (move: KanbanMove) => void;
  menuLabel: string;
  moveTo: (column: string) => string;
}

function Card({ card, index, dragging, menuId, columns, onDragStart, onDragEnd, onMove, menuLabel, moveTo }: CardProps) {
  const [open, setOpen] = useState(false);
  const trigger = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLDivElement>(null);
  const from = columns.find((col) => col.cards.some((c) => c.id === card.id))?.id;
  const others = useMemo(() => columns.filter((col) => col.id !== from), [columns, from]);
  const hasMenu = (card.actions?.length ?? 0) > 0 || others.length > 0;

  // The menu is a native popover placed against its trigger (core place).
  useEffect(() => {
    const button = trigger.current;
    if (button && 'popover' in HTMLElement.prototype) button.setAttribute('popovertarget', menuId);
  }, [menuId]);

  useEffect(() => {
    const el = panel.current;
    if (!el) return;
    const onToggle = (event: Event) => setOpen((event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  }, []);

  useIsoLayoutEffect(() => {
    if (!open || !trigger.current || !panel.current) return;
    return place(trigger.current, panel.current, { side: 'bottom', align: 'end', offset: 6 });
  }, [open]);

  // An exit is softer and faster than the enter: fold away quickly.
  const close = () => window.setTimeout(() => panel.current?.matches(':popover-open') && panel.current?.hidePopover(), 150);

  const choose = (action: KanbanCardAction) => {
    action.onSelect?.();
    close();
  };

  const move = (to: string) => {
    onMove({ card: card.id, from: from ?? '', to, index: columns.find((col) => col.id === to)?.cards.length ?? 0 });
    close();
  };

  return (
    <li
      className="nx-kanban-card"
      data-key={card.id}
      data-tone={card.tone}
      data-dragging={dragging ? '' : undefined}
      style={vars({ '--nx-i': index })}
      draggable
      onDragStart={(event) => onDragStart(event, card.id)}
      onDragEnd={onDragEnd}
    >
      {card.tone && <span className="nx-kanban-card-dot" aria-hidden="true" />}
      <div className="nx-kanban-card-main">
        <p className="nx-kanban-card-title">{card.title}</p>
        {card.meta && <p className="nx-kanban-card-meta">{card.meta}</p>}
      </div>
      {card.assignee && <Avatar name={card.assignee.name} src={card.assignee.src} size="sm" />}
      {hasMenu && (
        <>
          <button
            ref={trigger}
            type="button"
            className="nx-kanban-card-menu"
            aria-haspopup="menu"
            aria-expanded={open}
            aria-controls={menuId}
            aria-label={menuLabel}
            onClick={() => {
              if (!('popover' in HTMLElement.prototype)) setOpen(!open);
            }}
          >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2.75} strokeLinecap="round" aria-hidden="true">
              <path d={DOTS} />
            </svg>
          </button>
          <div
            ref={panel}
            id={menuId}
            className="nx-kanban-menu"
            role="menu"
            aria-label={menuLabel}
            {...{ popover: 'auto' }}
            onKeyDown={(event) => roveFocus(event.nativeEvent, event.currentTarget, '.nx-kanban-menu-choice', { orientation: 'vertical' })}
          >
            {card.actions?.map((action, i) => (
              <MenuItem key={i} action={action} onChoose={() => choose(action)} />
            ))}
            {card.actions && others.length > 0 && <hr className="nx-kanban-menu-sep" role="separator" />}
            {others.map((column) => (
              <button key={column.id} type="button" className="nx-kanban-menu-choice" role="menuitem" onClick={() => move(column.id)}>
                <Icon name="chevron-right" />
                <span>{moveTo(column.title)}</span>
              </button>
            ))}
          </div>
        </>
      )}
    </li>
  );
}

function MenuItem({ action, onChoose }: { action: KanbanCardAction; onChoose: () => void }) {
  const inner = (
    <>
      {action.icon && <Icon name={action.icon} />}
      <span>{action.label}</span>
    </>
  );
  return action.href ? (
    <SmartLink href={action.href} className="nx-kanban-menu-choice" role="menuitem" data-danger={action.danger ? '' : undefined} onClick={onChoose}>
      {inner}
    </SmartLink>
  ) : (
    <button type="button" className="nx-kanban-menu-choice" role="menuitem" data-danger={action.danger ? '' : undefined} onClick={onChoose}>
      {inner}
    </button>
  );
}
