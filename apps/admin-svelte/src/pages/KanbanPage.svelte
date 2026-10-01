<script lang="ts">
  /**
   * Kanban — the team board, live: columns sit in real state, drag & drop and the
   * per-column quick-add flow back through the `onchange` callback, and every
   * card's three-dot menu (edit / archive / delete) really mutates the board —
   * edit opens a rename dialog, the other two remove the card with a toast.
   * Tasks are seeded bilingual, so switching fa/en re-titles the whole board
   * without losing a drag. The FLIP glide, the landing placeholder and the
   * spoken "moved to" announcement live in the Kanban component.
   */
  import { toast } from '@nabuxai/ui-core';
  import { strings, tr } from '../store.svelte';
  import Kanban, { type KanbanColumn, type KanbanTone } from '../lib/Kanban.svelte';
  import NxIcon from '../lib/NxIcon.svelte';

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

  const SEED: Array<{ id: ColumnId; cards: Task[] }> = [
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

  const s = $derived(strings());
  const k = $derived(s.pages.kanban);

  let board = $state(SEED);
  let renaming = $state<{ id: string; value: string } | null>(null);
  let renameDialog: HTMLDialogElement;

  const titles = $derived<Record<ColumnId, string>>({ backlog: k.backlog, todo: k.todo, doing: k.doing, review: k.review, done: k.done });
  const tones: Record<ColumnId, KanbanTone | undefined> = { backlog: undefined, todo: 'info', doing: 'accent', review: 'warning', done: 'success' };

  /** Display columns carry per-card menu actions that mutate the board state. */
  const columns = $derived<KanbanColumn[]>(
    board.map((column) => ({
      id: column.id,
      title: titles[column.id],
      tone: tones[column.id],
      cards: column.cards.map((task) => ({
        id: task.id,
        title: tr(task.fa, task.en),
        tone: task.tone,
        meta: task.metaFa ? tr(task.metaFa, task.metaEn ?? task.metaFa) : undefined,
        assignee: task.who ? tr(task.who.fa, task.who.en) : undefined,
      })),
    })),
  );

  const actions = $derived([
    { id: 'edit', label: k.menuEdit, icon: 'edit' as const },
    { id: 'archive', label: k.menuArchive, icon: 'folder' as const },
    { id: 'delete', label: k.menuDelete, icon: 'trash' as const, danger: true },
  ]);

  const findTask = (id: string) => board.flatMap((column) => column.cards).find((task) => task.id === id);
  const removeTask = (id: string) => (board = board.map((column) => ({ ...column, cards: column.cards.filter((task) => task.id !== id) })));
  const renameTask = (id: string, title: string) =>
    (board = board.map((column) => ({ ...column, cards: column.cards.map((task) => (task.id === id ? { ...task, fa: title, en: title } : task)) })));

  // The component hands back display-shaped columns; the bilingual task is looked
  // up by id and kept, and a card the component just added becomes a new task.
  const sync = (next: KanbanColumn[]) => {
    const known = new Map(board.flatMap((column) => column.cards.map((task) => [task.id, task])));
    board = next.map((column) => ({
      id: column.id as ColumnId,
      cards: column.cards.map((card) => known.get(card.id) ?? { id: card.id, fa: card.title, en: card.title }),
    }));
  };

  const onAction = ({ id, cardId }: { id: string; cardId: string }) => {
    const task = findTask(cardId);
    const title = task ? tr(task.fa, task.en) : '';
    if (id === 'edit') {
      renaming = { id: cardId, value: title };
      return;
    }
    if (id === 'archive') {
      removeTask(cardId);
      toast(tr(`«${title}» بایگانی شد`, `“${title}” archived`));
      return;
    }
    removeTask(cardId);
    toast(tr(`«${title}» حذف شد`, `“${title}” deleted`));
  };

  $effect(() => {
    if (renaming) renameDialog?.showModal();
    else renameDialog?.close();
  });

  const saveRename = () => {
    if (!renaming) return;
    const title = renaming.value.trim();
    if (!title) return;
    renameTask(renaming.id, title);
    renaming = null;
    toast(tr(`«${title}» به‌روزرسانی شد`, `“${title}” was renamed`));
  };

  const backdropClose = (event: MouseEvent) => {
    if (event.target === event.currentTarget) renaming = null;
  };
</script>

<div style="display: grid; gap: var(--nx-space-5)">
  <Kanban {columns} label={k.title} height="44rem" addPlaceholder={k.newCardPlaceholder} {actions} onchange={sync} onaction={onAction} />

  <dialog bind:this={renameDialog} class="nx-dialog" data-size="sm" onclick={backdropClose} onclose={() => (renaming = null)}>
    <header class="nx-dialog-header">
      <h2 class="nx-dialog-title">{k.menuEdit}</h2>
      <p class="nx-dialog-description">{k.newCardPlaceholder}</p>
    </header>
    <div class="nx-dialog-body">
      <div class="nx-field">
        <label class="nx-label" for="kanban-rename">{k.addCard}</label>
        <input
          id="kanban-rename"
          class="nx-input"
          value={renaming?.value ?? ''}
          autocomplete="off"
          oninput={(event) => renaming && (renaming = { ...renaming, value: (event.target as HTMLInputElement).value })}
          onkeydown={(event) => event.key === 'Enter' && saveRename()}
        />
      </div>
    </div>
    <footer class="nx-dialog-footer">
      <button type="button" class="nx-button" data-variant="secondary" onclick={() => (renaming = null)}>
        <span class="nx-button-label"><span class="nx-button-text">{s.common.cancel}</span></span>
      </button>
      <button type="button" class="nx-button" data-variant="primary" disabled={!renaming?.value.trim()} onclick={saveRename}>
        <span class="nx-button-label">
          <NxIcon name="check" />
          <span class="nx-button-text">{s.common.save}</span>
        </span>
      </button>
    </footer>
    <button type="button" class="nx-dialog-close" aria-label={s.common.close} onclick={() => (renaming = null)}>
      <NxIcon name="x" />
    </button>
  </dialog>
</div>
