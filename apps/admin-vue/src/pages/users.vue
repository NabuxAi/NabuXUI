<script setup lang="ts">
/**
 * Users — the member table, with real state all the way down: live search, a
 * status chip filter, sorting (the table's own core helpers), row selection
 * with a confirm-before-delete dialog, an invite dialog that appends a genuine
 * row and a CSV export of exactly what is on show. The seed of the React
 * panel's UsersPage, on the hand-written nx-data-table markup.
 */
import { computed, nextTick, ref, watch } from 'vue';
import { type SortState, toast } from '@nabuxai/ui-core';
import { intlLocale, lang, numberFmt, s, tr } from '../store';
import { AGO, NOW } from '../data';
import DataTable, { type Column } from '../components/DataTable.vue';
import ChipFilter from '../components/ChipFilter.vue';
import NxIcon from '../components/NxIcon.vue';

type Role = 'admin' | 'manager' | 'support' | 'user';
type Status = 'active' | 'invited' | 'inactive';

interface Member {
  id: string;
  fa: string;
  en: string;
  email: string;
  role: Role;
  status: Status;
  /** An ms timestamp: rendered as a relative word, sorted as a number. */
  lastSeen: number;
}

const SEED: Member[] = [
  { id: 'u1', fa: 'حسین مرادی', en: 'Hossein Moradi', email: 'hossein@nabu.shop', role: 'admin', status: 'active', lastSeen: NOW },
  { id: 'u2', fa: 'مریم رضایی', en: 'Maryam Rezaei', email: 'maryam@nabu.shop', role: 'manager', status: 'active', lastSeen: AGO.minutes18.at },
  { id: 'u3', fa: 'علی نیک‌پور', en: 'Ali Nikpour', email: 'ali@nabu.shop', role: 'support', status: 'active', lastSeen: AGO.hour1.at },
  { id: 'u4', fa: 'سارا احمدی', en: 'Sara Ahmadi', email: 'sara@nabu.shop', role: 'support', status: 'active', lastSeen: AGO.hours2.at },
  { id: 'u5', fa: 'نگار موسوی', en: 'Negar Mousavi', email: 'negar@nabu.shop', role: 'manager', status: 'active', lastSeen: AGO.hours5.at },
  { id: 'u6', fa: 'الهام صادقی', en: 'Elham Sadeghi', email: 'elham@nabu.shop', role: 'support', status: 'active', lastSeen: AGO.minutes3.at },
  { id: 'u7', fa: 'مینا کریمی', en: 'Mina Karimi', email: 'mina@nabu.shop', role: 'user', status: 'invited', lastSeen: AGO.day1.at },
  { id: 'u8', fa: 'بهرام رستمی', en: 'Bahram Rostami', email: 'bahram@nabu.shop', role: 'user', status: 'invited', lastSeen: AGO.day1.at },
  { id: 'u9', fa: 'رضا قاسمی', en: 'Reza Ghasemi', email: 'reza@nabu.shop', role: 'user', status: 'inactive', lastSeen: AGO.days2.at },
  { id: 'u10', fa: 'کاوه تهامی', en: 'Kaveh Tahami', email: 'kaveh@nabu.shop', role: 'user', status: 'inactive', lastSeen: AGO.days2.at },
];

const ROLE_RANK: Record<Role, number> = { admin: 0, manager: 1, support: 2, user: 3 };
const STATUS_RANK: Record<Status, number> = { active: 0, invited: 1, inactive: 2 };
const STATUS_TONE: Record<Status, 'success' | 'info' | undefined> = { active: 'success', invited: 'info', inactive: undefined };

const u = computed(() => s.value.pages.users);

const rows = ref<Member[]>(SEED);
const query = ref('');
const status = ref('all');
const selected = ref(new Set<string>());
const confirming = ref(false);
const inviting = ref(false);
const invite = ref({ name: '', email: '', role: 'user' as Role });

const needle = computed(() => query.value.trim().toLowerCase());
const filtered = computed(() =>
  rows.value.filter(
    (row) =>
      (status.value === 'all' || row.status === status.value) &&
      (!needle.value || row.fa.toLowerCase().includes(needle.value) || row.en.toLowerCase().includes(needle.value) || row.email.toLowerCase().includes(needle.value)),
  ),
);
const chosen = computed(() => filtered.value.filter((row) => selected.value.has(row.id)));

const nameOf = (row: Member) => (lang.value === 'fa' ? row.fa : row.en);
const roleLabel = computed<Record<Role, string>>(() => ({ admin: u.value.roleAdmin, manager: u.value.roleManager, support: u.value.roleSupport, user: u.value.roleUser }));
const statusLabel = computed<Record<Status, string>>(() => ({ active: u.value.active, invited: u.value.invited, inactive: u.value.inactive }));

const initials = (name: string) =>
  name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => Array.from(part)[0])
    .join('')
    .toLocaleUpperCase();

/** "۱۸ دقیقه پیش" / "18m ago" — the locale's digits, measured from the shared NOW. */
function lastSeenWord(at: number): string {
  const n = (value: number) => new Intl.NumberFormat(intlLocale.value).format(value);
  const minutes = Math.max(0, Math.round((NOW - at) / 60_000));
  if (minutes < 1) return u.value.justNow;
  if (minutes < 60) return tr(`${n(minutes)} دقیقه پیش`, `${n(minutes)}m ago`);
  const hours = Math.round(minutes / 60);
  if (hours < 24) return tr(`${n(hours)} ساعت پیش`, `${n(hours)}h ago`);
  const days = Math.round(hours / 24);
  return tr(`${n(days)} روز پیش`, `${n(days)}d ago`);
}

const allChecked = computed(() => filtered.value.length > 0 && chosen.value.length === filtered.value.length);
const someChecked = computed(() => chosen.value.length > 0 && chosen.value.length < filtered.value.length);

const toggle = (id: string) => {
  const next = new Set(selected.value);
  if (next.has(id)) next.delete(id);
  else next.add(id);
  selected.value = next;
};

const toggleAll = () => {
  selected.value = allChecked.value ? new Set() : new Set(filtered.value.map((row) => row.id));
};

const removeChosen = () => {
  rows.value = rows.value.filter((row) => !selected.value.has(row.id));
  selected.value = new Set();
  confirming.value = false;
  toast(u.value.removedSelected);
};

const canInvite = computed(() => invite.value.name.trim().length > 0 && invite.value.email.trim().includes('@'));

const sendInvite = () => {
  const name = invite.value.name.trim();
  const email = invite.value.email.trim().toLowerCase();
  if (!name || !email.includes('@')) return;
  rows.value = [...rows.value, { id: `u-${Date.now().toString(36)}`, fa: name, en: name, email, role: invite.value.role, status: 'invited', lastSeen: NOW }];
  inviting.value = false;
  invite.value = { name: '', email: '', role: 'user' };
  toast(tr(`دعوت‌نامه برای ${email} ارسال شد`, `An invitation went to ${email}`));
};

/** A real download of what is on show (BOM first, so Persian opens right in Excel). */
const exportCsv = () => {
  const head = [u.value.colName, u.value.colEmail, u.value.colRole, u.value.colStatus, u.value.colLastSeen];
  const body = filtered.value.map((row) => [nameOf(row), row.email, roleLabel.value[row.role], statusLabel.value[row.status], lastSeenWord(row.lastSeen)]);
  const csv = `\uFEFF${[head, ...body].map((cells) => cells.map((cell) => `"${cell.replaceAll('"', '""')}"`).join(',')).join('\n')}`;
  const url = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8' }));
  const link = document.createElement('a');
  link.href = url;
  link.download = 'nabu-users.csv';
  link.click();
  URL.revokeObjectURL(url);
  toast(u.value.csvReady);
};

const columns = computed<Column<Member>[]>(() => [
  { key: 'select', label: '', width: '2.5rem' },
  { key: 'name', label: u.value.colName, sortable: true, sortValue: (row) => nameOf(row) },
  { key: 'email', label: u.value.colEmail, sortable: true },
  { key: 'role', label: u.value.colRole, sortable: true, sortValue: (row) => ROLE_RANK[row.role] },
  { key: 'status', label: u.value.colStatus, sortable: true, sortValue: (row) => STATUS_RANK[row.status] },
  { key: 'lastSeen', label: u.value.colLastSeen, sortable: true, sortValue: (row) => row.lastSeen },
]);

const defaultSort: SortState = { key: 'lastSeen', direction: 'descending' };

const counts = computed(() => ({
  all: rows.value.length,
  active: rows.value.filter((row) => row.status === 'active').length,
  invited: rows.value.filter((row) => row.status === 'invited').length,
  inactive: rows.value.filter((row) => row.status === 'inactive').length,
}));

const filterItems = computed(() => [
  { value: 'all', label: u.value.filterAll, count: counts.value.all },
  { value: 'active', label: u.value.active, count: counts.value.active },
  { value: 'invited', label: u.value.invited, count: counts.value.invited },
  { value: 'inactive', label: u.value.inactive, count: counts.value.inactive },
]);

/* ---- Dialogs: the native <dialog> behind nx-dialog ---------------------------------- */

const confirmDialog = ref<HTMLDialogElement | null>(null);
const inviteDialog = ref<HTMLDialogElement | null>(null);

watch(confirming, (open) => void nextTick(() => (open ? confirmDialog.value?.showModal() : confirmDialog.value?.close())));
watch(inviting, (open) => void nextTick(() => (open ? inviteDialog.value?.showModal() : inviteDialog.value?.close())));

const closeConfirm = () => (confirming.value = false);
const closeInvite = () => (inviting.value = false);

const selectAllRef = ref<HTMLInputElement | null>(null);
watch(someChecked, (indeterminate) => {
  if (selectAllRef.value) selectAllRef.value.indeterminate = indeterminate;
});
</script>

<template>
  <div class="adm-dashboard">
    <div class="adm-wide" style="display: flex; flex-wrap: wrap; gap: var(--nx-space-3); align-items: center; justify-content: space-between">
      <div style="display: flex; flex-wrap: wrap; gap: var(--nx-space-2); align-items: center">
        <div class="nx-input-group" style="inline-size: min(18rem, 60vw)">
          <span class="nx-input-addon"><NxIcon name="search" /></span>
          <input v-model="query" class="nx-input" type="search" :placeholder="u.search" :aria-label="s.common.search" />
        </div>
        <ChipFilter v-model="status" :items="filterItems" :label="u.colStatus" />
      </div>
      <div style="display: flex; flex-wrap: wrap; gap: var(--nx-space-2); align-items: center">
        <button type="button" class="nx-button" data-variant="secondary" @click="exportCsv">
          <span class="nx-button-label">
            <NxIcon name="file" />
            <span class="nx-button-text">{{ u.exportCsv }}</span>
          </span>
        </button>
        <button type="button" class="nx-button" data-variant="primary" @click="inviting = true">
          <span class="nx-button-label">
            <NxIcon name="plus" />
            <span class="nx-button-text">{{ u.addUser }}</span>
          </span>
        </button>
      </div>
    </div>

    <div v-if="chosen.length > 0" class="adm-wide" style="display: flex; flex-wrap: wrap; gap: var(--nx-space-2); align-items: center">
      <span class="nx-badge" data-tone="accent" data-dot="">{{ numberFmt.format(chosen.length) }} {{ u.selectedCount }}</span>
      <button type="button" class="nx-button" data-variant="danger" data-size="sm" @click="confirming = true">
        <span class="nx-button-label">
          <NxIcon name="trash" />
          <span class="nx-button-text">{{ u.deleteTitle }}</span>
        </span>
      </button>
    </div>

    <DataTable
      class="adm-wide"
      :rows="filtered"
      :columns="columns"
      :caption="u.title"
      :empty-text="needle || status !== 'all' ? u.noMatch : u.colName"
      :default-sort="defaultSort"
      max-height="32rem"
      :row-key="(row: Member) => row.id"
    >
      <template #head-select>
        <input
          ref="selectAllRef"
          type="checkbox"
          class="nx-checkbox"
          :checked="allChecked"
          :aria-label="u.selectAll"
          @change="toggleAll"
        />
      </template>
      <template #cell-select="{ row }">
        <input type="checkbox" class="nx-checkbox" :checked="selected.has(row.id)" :aria-label="`${u.select} ${nameOf(row)}`" @change="toggle(row.id)" />
      </template>
      <template #cell-name="{ row }">
        <span style="display: inline-flex; align-items: center; gap: var(--nx-space-2)">
          <span class="nx-avatar" data-size="sm" role="img" :aria-label="nameOf(row)">
            <span aria-hidden="true">{{ initials(nameOf(row)) }}</span>
          </span>
          <span style="font-weight: 600">{{ nameOf(row) }}</span>
        </span>
      </template>
      <template #cell-email="{ row }">
        <span dir="ltr" style="color: var(--nx-text-muted)">{{ row.email }}</span>
      </template>
      <template #cell-role="{ row }">
        <span v-if="row.role === 'admin'" class="nx-badge" data-tone="accent">{{ roleLabel[row.role] }}</span>
        <span v-else style="color: var(--nx-text-muted)">{{ roleLabel[row.role] }}</span>
      </template>
      <template #cell-status="{ row }">
        <span class="nx-badge" :data-tone="STATUS_TONE[row.status]" :data-dot="row.status !== 'inactive' ? '' : undefined">{{ statusLabel[row.status] }}</span>
      </template>
      <template #cell-lastSeen="{ row }">{{ lastSeenWord(row.lastSeen) }}</template>
    </DataTable>

    <dialog ref="confirmDialog" class="nx-dialog" data-size="sm" @close="closeConfirm" @click.self="closeConfirm">
      <header class="nx-dialog-header">
        <h2 class="nx-dialog-title">{{ u.deleteTitle }}</h2>
        <p class="nx-dialog-description">{{ u.deleteBody }}</p>
      </header>
      <footer class="nx-dialog-footer">
        <button type="button" class="nx-button" data-variant="secondary" @click="closeConfirm">
          <span class="nx-button-label"><span class="nx-button-text">{{ s.common.cancel }}</span></span>
        </button>
        <button type="button" class="nx-button" data-variant="danger" @click="removeChosen">
          <span class="nx-button-label">
            <NxIcon name="trash" />
            <span class="nx-button-text">{{ s.common.delete }}</span>
          </span>
        </button>
      </footer>
      <button type="button" class="nx-dialog-close" :aria-label="s.common.close" @click="closeConfirm">
        <NxIcon name="x" />
      </button>
    </dialog>

    <dialog ref="inviteDialog" class="nx-dialog" data-size="sm" @close="closeInvite" @click.self="closeInvite">
      <header class="nx-dialog-header">
        <h2 class="nx-dialog-title">{{ u.addUser }}</h2>
      </header>
      <div class="nx-dialog-body adm-form">
        <div class="nx-field">
          <label class="nx-label" for="invite-name">{{ u.colName }}</label>
          <input id="invite-name" v-model="invite.name" class="nx-input" autocomplete="off" required />
        </div>
        <div class="nx-field">
          <label class="nx-label" for="invite-email">{{ u.colEmail }}</label>
          <input id="invite-email" v-model="invite.email" class="nx-input" type="email" dir="ltr" autocomplete="off" required />
        </div>
        <div class="nx-field">
          <label class="nx-label" for="invite-role">{{ u.colRole }}</label>
          <select id="invite-role" v-model="invite.role" class="nx-select">
            <option value="user">{{ u.roleUser }}</option>
            <option value="support">{{ u.roleSupport }}</option>
            <option value="manager">{{ u.roleManager }}</option>
            <option value="admin">{{ u.roleAdmin }}</option>
          </select>
        </div>
      </div>
      <footer class="nx-dialog-footer">
        <button type="button" class="nx-button" data-variant="secondary" @click="closeInvite">
          <span class="nx-button-label"><span class="nx-button-text">{{ s.common.cancel }}</span></span>
        </button>
        <button type="button" class="nx-button" data-variant="primary" :disabled="!canInvite" @click="sendInvite">
          <span class="nx-button-label">
            <NxIcon name="mail" />
            <span class="nx-button-text">{{ u.addUser }}</span>
          </span>
        </button>
      </footer>
      <button type="button" class="nx-dialog-close" :aria-label="s.common.close" @click="closeInvite">
        <NxIcon name="x" />
      </button>
    </dialog>
  </div>
</template>

<style scoped>
/* The users toolbar spans the dashboard grid like the React panel's .adm-wide. */
@media (min-width: 72rem) {
  .adm-wide {
    grid-column: 1 / -1;
  }
}
</style>
