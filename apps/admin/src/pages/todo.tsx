/**
 * #/todo — the task list, live: the nx-todo block (springy ticks, drag to
 * reorder, Alt+↑/↓, the rolling progress) over seeded bilingual tasks grouped
 * into Today / Upcoming / Someday. The all/open/done chip filter narrows the
 * list without losing state — invisible tasks survive every move, and the
 * block's changes (tick, move, quick-add, remove) fold back into the page's
 * own state through one merge. "Clear completed" really empties the ticks and
 * toasts; the donut beside the list counts tasks per list, done ones apart.
 * Due words (today / tomorrow / later) prefix the tasks they belong to.
 * Deterministic: no Math.random, no fetch.
 */
import { useState } from 'react';
import { Button, ChipFilter, DonutRing, EmptyState, Todo, toast, type TodoGroup, type TodoTone } from '@nabuxai/ui-react';
import { useLang, useStrings } from '../lang';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

type ListId = 'today' | 'upcoming' | 'someday';
type Due = 'today' | 'tomorrow' | 'later';
type Filter = 'all' | 'open' | 'done';

/** A task as state: both languages kept, so the list survives a language switch. */
type Task = {
  id: string;
  fa: string;
  en: string;
  done?: boolean;
  tone?: TodoTone;
  due?: Due;
};

type List = { id: ListId; tasks: Task[] };

const SEED: List[] = [
  {
    id: 'today',
    tasks: [
      { id: 't1', fa: 'پاسخ به نظرهای دیروز', en: 'Answer yesterday’s reviews', done: true },
      { id: 't2', fa: 'بستن گزارش مالی مهر', en: 'Close the Mehr financial report', tone: 'danger' },
      { id: 't3', fa: 'تماس با گالری رنگین', en: 'Call the Rangin Gallery' },
      { id: 't4', fa: 'مرتب‌کردن کمد اسناد', en: 'Tidy the document drawer' },
    ],
  },
  {
    id: 'upcoming',
    tasks: [
      { id: 't5', fa: 'ارسال فایلهای چاپ به گالری', en: 'Send the print files to the gallery', due: 'today' },
      { id: 't6', fa: 'جلسهٔ بازبینی طراحی — ساعت ۱۰', en: 'Design review — 10 o’clock', due: 'tomorrow' },
      { id: 't7', fa: 'به‌روزرسانی عکسهای کیف چرمی', en: 'Refresh the leather bag photos', tone: 'gold', due: 'later' },
      { id: 't8', fa: 'خرید مواد بسته‌بندی', en: 'Buy packaging supplies', due: 'later' },
    ],
  },
  {
    id: 'someday',
    tasks: [
      { id: 't9', fa: 'ایدهٔ بستهٔ هدیهٔ نوروزی', en: 'Nowruz gift-box idea' },
      { id: 't10', fa: 'بازنویسی راهنمای اندازه‌ها', en: 'Rewrite the size guide' },
      { id: 't11', fa: 'مصاحبه با نامزد پشتیبانی', en: 'Interview the support candidate', done: true },
    ],
  },
];

const LIST_TONE: Record<ListId, TodoTone | undefined> = { today: 'accent', upcoming: 'info', someday: undefined };

export function TodoPage() {
  const lang = useLang();
  const s = useStrings();
  const k = s.pages.todo;
  const number = new Intl.NumberFormat(INTL[lang]);

  const [lists, setLists] = useState<List[]>(SEED);
  const [filter, setFilter] = useState<Filter>('all');

  const titles: Record<ListId, string> = { today: k.listToday, upcoming: k.listUpcoming, someday: k.listSomeday };
  const dueWord: Record<Due, string> = { today: k.dueToday, tomorrow: k.dueTomorrow, later: k.dueLater };

  const nameOf = (task: Task) => (task.due ? `${dueWord[task.due]}: ${lang === 'fa' ? task.fa : task.en}` : lang === 'fa' ? task.fa : task.en);
  const matches = (task: Task) => filter === 'all' || (filter === 'open' ? !task.done : task.done);

  // What the block sees: the raw lists, narrowed by the filter, in its shape.
  const display: TodoGroup[] = lists.map((list) => ({
    id: list.id,
    title: titles[list.id],
    tone: LIST_TONE[list.id],
    tasks: list.tasks.filter(matches).map((task) => ({ id: task.id, title: nameOf(task), done: task.done, tone: task.tone })),
  }));

  // …and back: every change the block reports lands here. Visible tasks keep
  // their identity by id (cross-list moves included); tasks the filter hides
  // were never on screen, so they simply stay; unknown ids are the quick-add's.
  const sync = (next: TodoGroup[]) => {
    const union = new Set(next.flatMap((group) => group.tasks.map((task) => task.id)));
    setLists((prev) => {
      const known = new Map(prev.flatMap((list) => list.tasks.map((task) => [task.id, task])));
      return prev.map((list) => {
        const target = next.find((group) => group.id === list.id);
        if (!target) return list;
        const ordered = target.tasks.map((shown) => {
          const task = known.get(shown.id);
          return task ? { ...task, done: shown.done ?? false } : { id: shown.id, fa: shown.title, en: shown.title, tone: shown.tone };
        });
        const hidden = list.tasks.filter((task) => !union.has(task.id) && !matches(task));
        return { ...list, tasks: [...ordered, ...hidden] };
      });
    });
  };

  const all = lists.flatMap((list) => list.tasks);
  const open = all.filter((task) => !task.done);
  const done = all.filter((task) => task.done);

  const clearDone = () => {
    setLists((prev) => prev.map((list) => ({ ...list, tasks: list.tasks.filter((task) => !task.done) })));
    toast(k.cleared);
  };

  return (
    <div className="adm-dashboard">
      <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-3)', alignItems: 'center', justifyContent: 'space-between' }}>
        <ChipFilter
          aria-label={k.title}
          value={filter}
          onValueChange={(next) => setFilter(next as Filter)}
          items={[
            { value: 'all', label: k.filterAll, count: all.length },
            { value: 'open', label: k.filterOpen, count: open.length },
            { value: 'done', label: k.filterDone, count: done.length },
          ]}
        />
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <span className="nx-badge" data-tone="accent" data-dot="">
            {number.format(open.length)} {k.remaining}
          </span>
          <Button variant="secondary" icon="trash" disabled={done.length === 0} onClick={clearDone}>
            {k.clearDone}
          </Button>
        </div>
      </div>

      {all.length > 0 ? (
        <Todo
          className="adm-wide"
          groups={display}
          onGroupsChange={sync}
          label={k.title}
          addPlaceholder={k.placeholder}
          onAdd={() => toast(k.added)}
        />
      ) : (
        <div className="adm-wide">
          <EmptyState icon="check-circle" title={k.emptyList} size="sm" />
        </div>
      )}

      <div className="adm-side">
        <DonutRing
          title={k.title}
          centerLabel=""
          data={[
            ...lists.map((list) => ({ label: titles[list.id], value: list.tasks.filter((task) => !task.done).length })),
            { label: k.listDone, value: done.length },
          ].filter((slice) => slice.value > 0)}
        />
      </div>
    </div>
  );
}
