/**
 * Kanban — the team board, live: columns sit in real React state, drag & drop
 * and the per-column quick-add flow back through `onColumnsChange`, and every
 * card's three-dot menu (edit / archive / delete) really mutates the board —
 * edit opens a rename dialog, the other two remove the card with a toast.
 * Tasks are seeded bilingual, so switching fa/en re-titles the whole board
 * without losing a drag; the block itself keeps the FLIP glide, the landing
 * placeholder and the spoken "moved to" announcement.
 */
import { useState } from 'react';
import { Button, Dialog, Field, Input, Kanban, toast, type KanbanColumn, type KanbanTone } from '@nabuxai/ui-react';
import { useStrings, useTr } from '../lang';

type ColumnId = 'backlog' | 'todo' | 'doing' | 'review' | 'done';

/** A task as state: both languages kept, so the board survives a language switch. */
type Task = {
  id: string;
  fa: string;
  en: string;
  metaFa?: string;
  metaEn?: string;
  tone?: KanbanTone;
  who?: { fa: string; en: string };
};

type Column = { id: ColumnId; cards: Task[] };

const SEED: Column[] = [
  {
    id: 'backlog',
    cards: [
      { id: 't1', fa: 'بررسی درخواست مرجوعی ۹۲۱', en: 'Review refund request 921', metaFa: 'اولویت پایین', metaEn: 'Low priority', tone: 'info' },
      { id: 't2', fa: 'ایدهٔ بستهٔ هدیهٔ نوروزی', en: 'Nowruz gift-box idea' },
      { id: 't3', fa: 'بازنویسی متن «دربارهٔ ما»', en: 'Rewrite the “About us” copy' },
    ],
  },
  {
    id: 'todo',
    cards: [
      { id: 't4', fa: 'بازنویسی متن صفحهٔ پرداخت', en: 'Rewrite the checkout copy', metaFa: 'مهلت: پنجشنبه', metaEn: 'Due Thursday', who: { fa: 'سارا احمدی', en: 'Sara Ahmadi' } },
      { id: 't5', fa: 'به‌روزرسانی راهنمای اندازه‌ها', en: 'Refresh the size guide', who: { fa: 'علی نیک‌پور', en: 'Ali Nikpour' } },
    ],
  },
  {
    id: 'doing',
    cards: [
      { id: 't6', fa: 'اصلاح باگ درگاه پرداخت', en: 'Fix the payment gateway bug', metaFa: 'فوری', metaEn: 'Urgent', tone: 'danger', who: { fa: 'مریم رضایی', en: 'Maryam Rezaei' } },
      { id: 't7', fa: 'مصاحبه با دو نامزد پشتیبانی', en: 'Interview two support candidates', who: { fa: 'حسین مرادی', en: 'Hossein Moradi' } },
    ],
  },
  {
    id: 'review',
    cards: [
      { id: 't8', fa: 'کیف چرمی نابو — عکسهای تازه', en: 'Nabu leather bag — new photos', metaFa: '۲ نظر', metaEn: '2 comments', tone: 'gold', who: { fa: 'سارا احمدی', en: 'Sara Ahmadi' } },
      { id: 't9', fa: 'گزارش مالی ماهانه', en: 'The monthly financial report', metaFa: 'نیازمند تأیید', metaEn: 'Needs sign-off', who: { fa: 'حسین مرادی', en: 'Hossein Moradi' } },
    ],
  },
  {
    id: 'done',
    cards: [
      { id: 't10', fa: 'راه‌اندازی کارت هدیه', en: 'Gift cards shipped', metaFa: 'آزاد شد', metaEn: 'Released', tone: 'success' },
      { id: 't11', fa: 'پاسخ به نظرهای هفته', en: 'Answered the week’s reviews', tone: 'success' },
    ],
  },
];

export function KanbanPage() {
  const s = useStrings();
  const k = s.pages.kanban;
  const tr = useTr();

  const [board, setBoard] = useState<Column[]>(SEED);
  const [renaming, setRenaming] = useState<{ id: string; value: string } | null>(null);

  const titles: Record<ColumnId, string> = { backlog: k.backlog, todo: k.todo, doing: k.doing, review: k.review, done: k.done };
  const tones: Record<ColumnId, KanbanTone | undefined> = { backlog: undefined, todo: 'info', doing: 'accent', review: 'warning', done: 'success' };

  const findTask = (columns: Column[], id: string) => columns.flatMap((column) => column.cards).find((task) => task.id === id);
  const removeTask = (id: string) => setBoard((prev) => prev.map((column) => ({ ...column, cards: column.cards.filter((task) => task.id !== id) })));
  const renameTask = (id: string, title: string) =>
    setBoard((prev) => prev.map((column) => ({ ...column, cards: column.cards.map((task) => (task.id === id ? { ...task, fa: title, en: title } : task)) })));

  // The block hands back display-shaped columns; the bilingual task is looked
  // up by id and kept, and a card the block just added becomes a new task.
  const sync = (next: KanbanColumn[]) =>
    setBoard((prev) => {
      const known = new Map(prev.flatMap((column) => column.cards.map((task) => [task.id, task])));
      return next.map((column) => ({
        id: column.id as ColumnId,
        cards: column.cards.map((card) => {
          const task = known.get(card.id);
          return task ?? { id: card.id, fa: card.title, en: card.title };
        }),
      }));
    });

  // Display columns carry per-card menu actions that mutate the board state.
  const columns: KanbanColumn[] = board.map((column) => ({
    id: column.id,
    title: titles[column.id],
    tone: tones[column.id],
    cards: column.cards.map((task) => {
      const title = tr(task.fa, task.en);
      return {
        id: task.id,
        title,
        tone: task.tone,
        meta: task.metaFa ? tr(task.metaFa, task.metaEn ?? task.metaFa) : undefined,
        assignee: task.who ? { name: tr(task.who.fa, task.who.en) } : undefined,
        actions: [
          { label: k.menuEdit, icon: 'edit' as const, onSelect: () => setRenaming({ id: task.id, value: title }) },
          {
            label: k.menuArchive,
            icon: 'folder' as const,
            onSelect: () => {
              removeTask(task.id);
              toast(tr(`«${title}» بایگانی شد`, `“${title}” archived`));
            },
          },
          {
            label: k.menuDelete,
            icon: 'trash' as const,
            danger: true,
            onSelect: () => {
              removeTask(task.id);
              toast(tr(`«${title}» حذف شد`, `“${title}” deleted`));
            },
          },
        ],
      };
    }),
  }));

  const saveRename = () => {
    if (!renaming) return;
    const title = renaming.value.trim();
    if (!title) return;
    renameTask(renaming.id, title);
    setRenaming(null);
    toast(tr(`«${title}» به‌روزرسانی شد`, `“${title}” was renamed`));
  };

  const renamingTask = renaming ? findTask(board, renaming.id) : undefined;

  return (
    <div className="adm-dashboard">
      <Kanban
        className="adm-wide"
        label={k.title}
        height="44rem"
        columns={columns}
        onColumnsChange={sync}
        addPlaceholder={k.newCardPlaceholder}
      />

      <Dialog
        open={!!renaming}
        onOpenChange={(open) => !open && setRenaming(null)}
        size="sm"
        title={k.menuEdit}
        description={renamingTask ? tr(renamingTask.fa, renamingTask.en) : undefined}
        footer={
          <>
            <Button variant="secondary" onClick={() => setRenaming(null)}>
              {s.common.cancel}
            </Button>
            <Button variant="primary" icon="check" disabled={!renaming?.value.trim()} onClick={saveRename}>
              {s.common.save}
            </Button>
          </>
        }
      >
        <Field label={k.addCard} hint={k.newCardPlaceholder}>
          <Input
            value={renaming?.value ?? ''}
            onChange={(event) => setRenaming((current) => (current ? { ...current, value: event.target.value } : current))}
            onKeyDown={(event) => event.key === 'Enter' && saveRename()}
            autoComplete="off"
          />
        </Field>
      </Dialog>
    </div>
  );
}
