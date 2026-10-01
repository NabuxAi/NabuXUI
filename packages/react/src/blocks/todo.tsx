/**
 * Todo — a task list: grouped tasks, a spring tick, drag-to-reorder, a rolling
 * counter with a progress bar, and a quick-add composer (React).
 *
 * Thin over css/blocks/todo.css. Tasks are reordered by dragging (or, fully
 * keyboard-reachable, by Alt+↑/Alt+↓ on a task) and glide with the core FLIP
 * helpers; the drop point shows as a breathing placeholder pill, the done/total
 * counter and the bar's percent roll as digits (numberParts/localeDigits) and
 * the quick-add composer puts new tasks into the group you pick. State is the
 * groups you pass: controlled (`groups` + `onGroupsChange`) or uncontrolled
 * (`defaultGroups`), with `onCheck` / `onRemove` / `onAdd` / `onMove` detail
 * callbacks.
 */
import {
  type CSSProperties,
  type DragEvent as ReactDragEvent,
  type FormEvent,
  type HTMLAttributes,
  type KeyboardEvent as ReactKeyboardEvent,
  type ReactNode,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { playRowFlip, reveal, snapshotRows } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useEvent, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale, useT } from '../internal/provider';
import { NumberTicker } from '../components/text';

/** The Intl locale: the prop, or the provider's language. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

const PERCENT = { style: 'percent', maximumFractionDigits: 0 } as const;

/* ---- Types ---------------------------------------------------------------------------- */

export type TodoTone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';

export interface TodoTask {
  id: string;
  title: string;
  done?: boolean;
  tone?: TodoTone;
}

export interface TodoGroup {
  id: string;
  title: string;
  tone?: TodoTone;
  tasks: TodoTask[];
}

export interface TodoMove {
  task: string;
  from: string;
  to: string;
  index: number;
}

export interface TodoProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  groups?: TodoGroup[];
  defaultGroups?: TodoGroup[];
  onGroupsChange?: (groups: TodoGroup[]) => void;
  /** Fired whenever a task's tick flips, whichever way it started. */
  onCheck?: (task: string, done: boolean, groups: TodoGroup[]) => void;
  onRemove?: (task: string, groups: TodoGroup[]) => void;
  /** Fired when the quick-add composer adds a task. */
  onAdd?: (group: string, title: string, groups: TodoGroup[]) => void;
  /** Fired for every reorder, whichever way it started (drag or Alt+Arrow). */
  onMove?: (move: TodoMove, groups: TodoGroup[]) => void;
  /** The visible list title; the accessible name comes from `label`. */
  heading?: ReactNode;
  /** Show the quick-add composer (default). */
  quickAdd?: boolean;
  addPlaceholder?: string;
  /** Show the done/total counter and the progress bar (default). */
  progress?: boolean;
  /** The list's accessible name. */
  label?: string;
  locale?: string;
}

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** Where a pointer's Y falls in `list`: before which task (its index, or the end). */
function dropIndex(list: HTMLElement, dragging: HTMLElement | null, y: number): number {
  const items = Array.from(list.querySelectorAll<HTMLElement>('.nx-todo-item')).filter((item) => item !== dragging);
  for (let i = 0; i < items.length; i++) {
    const rect = items[i]!.getBoundingClientRect();
    if (y < rect.top + rect.height / 2) return i;
  }
  return items.length;
}

export function Todo({
  groups: controlled,
  defaultGroups,
  onGroupsChange,
  onCheck,
  onRemove,
  onAdd,
  onMove,
  heading,
  quickAdd = true,
  addPlaceholder,
  progress = true,
  label,
  locale,
  className,
  ...rest
}: TodoProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const number = useMemo(() => new Intl.NumberFormat(intl), [intl]);
  const base = `nx-todo${useId().replace(/:/g, '')}`;
  const [groups, setGroups] = useControllable(controlled, defaultGroups ?? [], onGroupsChange);
  const [dragging, setDragging] = useState<string | null>(null);
  const [over, setOver] = useState<{ group: string; index: number } | null>(null);
  const [target, setTarget] = useState<string | null>(null);
  const [announced, setAnnounced] = useState('');
  const root = useRef<HTMLElement>(null);
  const flight = useRef<ReturnType<typeof snapshotRows> | null>(null);
  const refocus = useRef<string | null>(null);
  useBehavior(root, reveal, { once: true });

  const itemsOf = () => root.current?.querySelectorAll<HTMLElement>('.nx-todo-item[data-key]') ?? [];

  const totals = useMemo(() => {
    const tasks = groups.flatMap((group) => group.tasks);
    const done = tasks.filter((task) => task.done).length;
    return { done, total: tasks.length, ratio: tasks.length ? done / tasks.length : 0 };
  }, [groups]);

  /** The group quick-add drops into: the picked one, or the first. */
  const addTo = target && groups.some((group) => group.id === target) ? target : (groups[0]?.id ?? null);

  // Reorders arrive from drops, Alt+Arrow nudges and removals alike; each sets
  // up a FLIP so the survivors glide, then announces the result.
  const commit = useEvent((move: TodoMove, glide = true) => {
    const next = groups.map((group) => ({ ...group, tasks: [...group.tasks] }));
    const from = next.find((group) => group.id === move.from);
    const to = next.find((group) => group.id === move.to);
    const task = from?.tasks.find((item) => item.id === move.task);
    if (!from || !to || !task) return;
    from.tasks = from.tasks.filter((item) => item.id !== move.task);
    to.tasks.splice(Math.max(0, Math.min(move.index, to.tasks.length)), 0, task);
    if (glide) flight.current = snapshotRows(itemsOf());
    setGroups(next);
    onMove?.(move, next);
    setAnnounced(t('todoMoved', { name: task.title }));
  });

  useIsoLayoutEffect(() => {
    if (flight.current) {
      const before = flight.current;
      flight.current = null;
      playRowFlip(itemsOf(), before);
    }
    // A keyboard nudge keeps the task focused after it lands.
    if (refocus.current) {
      const key = refocus.current;
      refocus.current = null;
      root.current?.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(key)}"] .nx-todo-check`)?.focus();
    }
  }, [groups]);

  /* ---- Drag & drop ------------------------------------------------------------------------- */

  const onDragStart = (event: ReactDragEvent<HTMLLIElement>, task: string) => {
    event.dataTransfer.setData('text/plain', task);
    event.dataTransfer.effectAllowed = 'move';
    setDragging(task);
  };

  const onDragOver = (event: ReactDragEvent<HTMLUListElement>, group: string) => {
    if (!dragging) return;
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
    const source = root.current?.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(dragging)}"]`) ?? null;
    const index = dropIndex(event.currentTarget, source, event.clientY);
    setOver((current) => (current?.group === group && current.index === index ? current : { group, index }));
  };

  const onDrop = (event: ReactDragEvent<HTMLUListElement>, group: string) => {
    event.preventDefault();
    const task = dragging ?? event.dataTransfer.getData('text/plain');
    const from = groups.find((groupItem) => groupItem.tasks.some((item) => item.id === task))?.id;
    setDragging(null);
    setOver(null);
    if (!from) return;
    const source = root.current?.querySelector<HTMLElement>(`.nx-todo-item[data-key="${CSS.escape(task)}"]`) ?? null;
    commit({ task, from, to: group, index: dropIndex(event.currentTarget, source, event.clientY) });
  };

  const onDragLeave = (event: ReactDragEvent<HTMLUListElement>, group: string) => {
    if (event.currentTarget.contains(event.relatedTarget as Node | null)) return;
    setOver((current) => (current?.group === group ? null : current));
  };

  /* ---- Keyboard reorder: Alt+↑/↓ walks a task through the list ------------------------------ */

  const nudge = (event: ReactKeyboardEvent<HTMLLIElement>, task: string) => {
    if (!event.altKey || (event.key !== 'ArrowUp' && event.key !== 'ArrowDown')) return;
    event.preventDefault();
    const down = event.key === 'ArrowDown';
    for (let g = 0; g < groups.length; g++) {
      const group = groups[g]!;
      const index = group.tasks.findIndex((item) => item.id === task);
      if (index === -1) continue;
      if (down) {
        if (index < group.tasks.length - 1) commit({ task, from: group.id, to: group.id, index: index + 1 }, false);
        else if (groups[g + 1]) commit({ task, from: group.id, to: groups[g + 1]!.id, index: 0 }, false);
      } else {
        if (index > 0) commit({ task, from: group.id, to: group.id, index: index - 1 }, false);
        else if (groups[g - 1]) commit({ task, from: group.id, to: groups[g - 1]!.id, index: groups[g - 1]!.tasks.length }, false);
      }
      // Keyboard navigation is a many-times-a-day action: no glide, just the new slot.
      refocus.current = task;
      return;
    }
  };

  /* ---- Tick and remove ------------------------------------------------------------------------ */

  const toggle = (task: string) => {
    let done = false;
    let title = '';
    const next = groups.map((group) => ({
      ...group,
      tasks: group.tasks.map((item) => {
        if (item.id !== task) return item;
        done = !item.done;
        title = item.title;
        return { ...item, done };
      }),
    }));
    setGroups(next);
    onCheck?.(task, done, next);
    setAnnounced(t(done ? 'todoChecked' : 'todoUnchecked', { name: title }));
  };

  const remove = (task: string) => {
    const gone = groups.flatMap((group) => group.tasks).find((item) => item.id === task);
    // Groups stay even when a removal empties them — the empty plate shows, as
    // it does after a drag empties a group.
    const next = groups.map((group) => ({ ...group, tasks: group.tasks.filter((item) => item.id !== task) }));
    flight.current = snapshotRows(itemsOf());
    setGroups(next);
    onRemove?.(task, next);
    setAnnounced(t('todoRemove', { name: gone?.title ?? task }));
  };

  /* ---- Quick add ------------------------------------------------------------------------------ */

  const submit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    const form = event.currentTarget;
    const input = form.elements.namedItem('title') as HTMLInputElement | null;
    const title = input?.value.trim();
    if (!title || !addTo) {
      input?.focus();
      return;
    }
    const id = `task-${Date.now().toString(36)}-${Math.random().toString(36).slice(2, 6)}`;
    const next = groups.map((group) => (group.id === addTo ? { ...group, tasks: [...group.tasks, { id, title }] } : group));
    flight.current = snapshotRows(itemsOf());
    setGroups(next);
    onAdd?.(addTo, title, next);
    if (input) input.value = '';
    input?.focus();
  };

  const progressText = t('todoProgress', { done: number.format(totals.done), total: number.format(totals.total) });

  return (
    <section
      ref={root}
      className={cx('nx-todo', className)}
      aria-label={label ?? t('todoList')}
      data-complete={totals.total > 0 && totals.done === totals.total ? '' : undefined}
      {...rest}
    >
      {(heading || progress) && (
        <header className="nx-todo-head">
          {heading && <h3 className="nx-todo-title">{heading}</h3>}
          {progress && (
            <>
              <span className="nx-todo-count" aria-hidden="true">
                <NumberTicker value={totals.done} reveal={false} locale={locale} />
                <span className="nx-todo-count-sep">/</span>
                <NumberTicker value={totals.total} reveal={false} locale={locale} />
              </span>
              {/* The spoken total is rendered so it reads without the visual bar. */}
              <span className="nx-visually-hidden">{progressText}</span>
            </>
          )}
        </header>
      )}
      {progress && (
        <div
          className="nx-todo-progress"
          role="progressbar"
          aria-valuemin={0}
          aria-valuemax={100}
          aria-valuenow={Math.round(totals.ratio * 100)}
          aria-valuetext={progressText}
        >
          <span className="nx-todo-progress-track">
            <span className="nx-todo-progress-fill" style={vars({ '--nx-todo-p': totals.ratio * 100 })} />
          </span>
          <span className="nx-todo-progress-label" aria-hidden="true">
            <NumberTicker value={totals.ratio} format={PERCENT} reveal={false} locale={locale} />
          </span>
        </div>
      )}
      {quickAdd && (
        <form className="nx-todo-add" onSubmit={submit}>
          <input
            className="nx-todo-add-input"
            name="title"
            type="text"
            autoComplete="off"
            placeholder={addPlaceholder ?? t('todoAddTask')}
            aria-label={t('todoAddTask')}
          />
          {groups.length > 1 && (
            <select
              className="nx-todo-add-select"
              value={addTo ?? undefined}
              aria-label={t('todoGroup')}
              onChange={(event) => setTarget(event.target.value)}
            >
              {groups.map((group) => (
                <option key={group.id} value={group.id}>
                  {group.title}
                </option>
              ))}
            </select>
          )}
          <button type="submit" className="nx-todo-add-submit">
            <Icon name="plus" />
            <span>{t('todoAdd')}</span>
          </button>
        </form>
      )}
      <ul className="nx-todo-groups">
        {groups.map((group) => {
          const headingId = `${base}-group-${group.id}`;
          const overHere = over?.group === group.id ? over.index : null;
          // The placeholder sits among the group's *other* tasks, so it shows
          // where the dragged task will land once it leaves its old slot.
          const items: ReactNode[] = [];
          let landing = 0;
          let index = 0;
          group.tasks.forEach((task) => {
            if (task.id !== dragging) {
              if (overHere === landing) items.push(<Placeholder key={`ph-${group.id}`} />);
              landing += 1;
            }
            items.push(
              <Task
                key={task.id}
                task={task}
                checkId={`${base}-check-${task.id}`}
                index={index++}
                dragging={dragging === task.id}
                removeLabel={t('todoRemove', { name: task.title })}
                onDragStart={onDragStart}
                onDragEnd={() => {
                  setDragging(null);
                  setOver(null);
                }}
                onNudge={nudge}
                onCheck={toggle}
                onRemove={remove}
              />,
            );
          });
          if (overHere !== null && overHere >= landing) items.push(<Placeholder key={`ph-${group.id}-end`} />);
          return (
            <li
              key={group.id}
              className="nx-todo-group"
              data-group={group.id}
              data-tone={group.tone}
              data-dropping={overHere !== null ? '' : undefined}
            >
              <header className="nx-todo-group-head">
                <span className="nx-todo-group-dot" aria-hidden="true" />
                <h4 className="nx-todo-group-title" id={headingId}>
                  {group.title}
                </h4>
                <span className="nx-todo-group-count" aria-hidden="true">
                  <NumberTicker value={group.tasks.length} reveal={false} locale={locale} />
                </span>
                <span className="nx-visually-hidden">{t('todoTasks', { count: number.format(group.tasks.length) })}</span>
              </header>
              <ul
                className="nx-todo-list"
                aria-labelledby={headingId}
                onDragOver={(event) => onDragOver(event, group.id)}
                onDrop={(event) => onDrop(event, group.id)}
                onDragLeave={(event) => onDragLeave(event, group.id)}
              >
                {items}
                {group.tasks.length === 0 && overHere === null && <li className="nx-todo-empty">{t('todoEmpty')}</li>}
              </ul>
            </li>
          );
        })}
      </ul>
      {/* The tick or the move, spoken once it lands. */}
      <p className="nx-visually-hidden" role="status">
        {announced}
      </p>
    </section>
  );
}

/* ---- The drop placeholder pill ----------------------------------------------------------- */

function Placeholder() {
  return (
    <li className="nx-todo-placeholder" aria-hidden="true">
      <span className="nx-todo-placeholder-dot" />
      <span className="nx-todo-placeholder-bar" />
    </li>
  );
}

/* ---- One task ------------------------------------------------------------------------------- */

interface TaskProps {
  task: TodoTask;
  checkId: string;
  index: number;
  dragging: boolean;
  removeLabel: string;
  onDragStart: (event: ReactDragEvent<HTMLLIElement>, task: string) => void;
  onDragEnd: () => void;
  onNudge: (event: ReactKeyboardEvent<HTMLLIElement>, task: string) => void;
  onCheck: (task: string) => void;
  onRemove: (task: string) => void;
}

function Task({ task, checkId, index, dragging, removeLabel, onDragStart, onDragEnd, onNudge, onCheck, onRemove }: TaskProps) {
  return (
    <li
      className="nx-todo-item"
      data-key={task.id}
      data-done={task.done ? '' : undefined}
      data-dragging={dragging ? '' : undefined}
      style={vars({ '--nx-i': index })}
      draggable
      onDragStart={(event) => onDragStart(event, task.id)}
      onDragEnd={onDragEnd}
      onKeyDown={(event) => onNudge(event, task.id)}
    >
      <span className="nx-todo-grip" aria-hidden="true" />
      <input
        id={checkId}
        type="checkbox"
        className="nx-todo-check"
        checked={Boolean(task.done)}
        onChange={() => onCheck(task.id)}
      />
      <label className="nx-todo-label" htmlFor={checkId}>
        {task.title}
      </label>
      <button type="button" className="nx-todo-remove" aria-label={removeLabel} onClick={() => onRemove(task.id)}>
        <Icon name="x" />
      </button>
    </li>
  );
}
