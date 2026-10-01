<script setup lang="ts">
/**
 * Kanban — the team board, live: columns sit in real state, drag & drop and the
 * per-column quick-add flow back through the `change` event, and every card's
 * three-dot menu (edit / archive / delete) really mutates the board — edit
 * opens a rename dialog, the other two remove the card with a toast. Tasks are
 * seeded bilingual, so switching fa/en re-titles the whole board without losing
 * a drag. The FLIP glide, the landing placeholder and the spoken "moved to"
 * announcement live in the Kanban component.
 */
import { computed, nextTick, ref, watch } from 'vue';
import { toast } from '@nabuxai/ui-core';
import { s, tr } from '../store';
import Kanban, { type KanbanColumn, type KanbanTone } from '../components/Kanban.vue';
import NxIcon from '../components/NxIcon.vue';

type ColumnId = 'backlog' | 'todo' | 'doing' | 'review' | 'done';

/** A task as state: both languages kept, so the board survives a language switch. */
interface Task {
  id: string;
  fa: string;
  en: string;
  metaFa?: string;
  metaEn?: string;
  tone?: KanbanTone;
  who?: { fa: string; en: string };
}

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

const k = computed(() => s.value.pages.kanban);

const board = ref(SEED);
const renaming = ref<{ id: string; value: string } | null>(null);
const renameDialog = ref<HTMLDialogElement | null>(null);

const titles = computed<Record<ColumnId, string>>(() => ({ backlog: k.value.backlog, todo: k.value.todo, doing: k.value.doing, review: k.value.review, done: k.value.done }));
const tones: Record<ColumnId, KanbanTone | undefined> = { backlog: undefined, todo: 'info', doing: 'accent', review: 'warning', done: 'success' };

/** Display columns carry per-card menu actions that mutate the board state. */
const columns = computed<KanbanColumn[]>(() =>
  board.value.map((column) => ({
    id: column.id,
    title: titles.value[column.id],
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

const actions = computed(() => [
  { id: 'edit', label: k.value.menuEdit, icon: 'edit' as const },
  { id: 'archive', label: k.value.menuArchive, icon: 'folder' as const },
  { id: 'delete', label: k.value.menuDelete, icon: 'trash' as const, danger: true },
]);

const findTask = (id: string) => board.value.flatMap((column) => column.cards).find((task) => task.id === id);
const removeTask = (id: string) => (board.value = board.value.map((column) => ({ ...column, cards: column.cards.filter((task) => task.id !== id) })));
const renameTask = (id: string, title: string) =>
  (board.value = board.value.map((column) => ({ ...column, cards: column.cards.map((task) => (task.id === id ? { ...task, fa: title, en: title } : task)) })));

// The component hands back display-shaped columns; the bilingual task is looked
// up by id and kept, and a card the component just added becomes a new task.
const sync = (next: KanbanColumn[]) => {
  const known = new Map(board.value.flatMap((column) => column.cards.map((task) => [task.id, task])));
  board.value = next.map((column) => ({
    id: column.id as ColumnId,
    cards: column.cards.map((card) => known.get(card.id) ?? { id: card.id, fa: card.title, en: card.title }),
  }));
};

const onAction = ({ id, cardId }: { id: string; cardId: string }) => {
  const task = findTask(cardId);
  const title = task ? tr(task.fa, task.en) : '';
  if (id === 'edit') {
    renaming.value = { id: cardId, value: title };
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

watch(renaming, (open) => void nextTick(() => (open ? renameDialog.value?.showModal() : renameDialog.value?.close())));

const saveRename = () => {
  if (!renaming.value) return;
  const title = renaming.value.value.trim();
  if (!title) return;
  renameTask(renaming.value.id, title);
  renaming.value = null;
  toast(tr(`«${title}» به‌روزرسانی شد`, `“${title}” was renamed`));
};

const closeRename = () => (renaming.value = null);
</script>

<template>
  <div class="adm-kanban" style="display: grid; gap: var(--nx-space-5)">
    <Kanban
      :columns="columns"
      :label="k.title"
      height="44rem"
      :add-placeholder="k.newCardPlaceholder"
      :actions="actions"
      @change="sync"
      @action="onAction"
    />

    <dialog ref="renameDialog" class="nx-dialog" data-size="sm" @close="closeRename" @click.self="closeRename">
      <header class="nx-dialog-header">
        <h2 class="nx-dialog-title">{{ k.menuEdit }}</h2>
        <p class="nx-dialog-description">{{ k.newCardPlaceholder }}</p>
      </header>
      <div class="nx-dialog-body">
        <div class="nx-field">
          <label class="nx-label" for="kanban-rename">{{ k.addCard }}</label>
          <input
            id="kanban-rename"
            class="nx-input"
            :value="renaming?.value ?? ''"
            autocomplete="off"
            @input="renaming = renaming ? { ...renaming, value: ($event.target as HTMLInputElement).value } : renaming"
            @keydown.enter="saveRename"
          />
        </div>
      </div>
      <footer class="nx-dialog-footer">
        <button type="button" class="nx-button" data-variant="secondary" @click="closeRename">
          <span class="nx-button-label"><span class="nx-button-text">{{ s.common.cancel }}</span></span>
        </button>
        <button type="button" class="nx-button" data-variant="primary" :disabled="!renaming?.value.trim()" @click="saveRename">
          <span class="nx-button-label">
            <NxIcon name="check" />
            <span class="nx-button-text">{{ s.common.save }}</span>
          </span>
        </button>
      </footer>
      <button type="button" class="nx-dialog-close" :aria-label="s.common.close" @click="closeRename">
        <NxIcon name="x" />
      </button>
    </dialog>
  </div>
</template>
