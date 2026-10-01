<script lang="ts">
  /**
   * Users — the member table, with real state all the way down: live search, a
   * status chip filter, sorting (the table's own core helpers), row selection
   * with a confirm-before-delete dialog, an invite dialog that appends a
   * genuine row and a CSV export of exactly what is on show. The seed of the
   * React panel's UsersPage, on the hand-written nx-data-table markup; cells
   * are per-column snippets.
   */
  import { toast } from '@nabuxai/ui-core';
  import { app, intlLocale, numberFmt, strings, tr } from '../store.svelte';
  import { AGO, NOW } from '../data';
  import DataTable, { type Column } from '../lib/DataTable.svelte';
  import ChipFilter, { type ChipItem } from '../lib/ChipFilter.svelte';
  import NxIcon from '../lib/NxIcon.svelte';

  type Role = 'admin' | 'manager' | 'support' | 'user';
  type Status = 'active' | 'invited' | 'inactive';

  /** A type (not interface) so the table's open row type accepts it. */
  type Member = {
    id: string;
    fa: string;
    en: string;
    email: string;
    role: Role;
    status: Status;
    /** An ms timestamp: rendered as a relative word, sorted as a number. */
    lastSeen: number;
  };

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

  const s = $derived(strings());
  const u = $derived(s.pages.users);

  let rows = $state<Member[]>(SEED);
  let query = $state('');
  let status = $state('all');
  let selected = $state(new Set<string>());
  let confirming = $state(false);
  let inviting = $state(false);
  let invite = $state<{ name: string; email: string; role: Role }>({ name: '', email: '', role: 'user' });
  let someChecked = $state(false);

  let confirmDialog: HTMLDialogElement;
  let inviteDialog: HTMLDialogElement;

  const needle = $derived(query.trim().toLowerCase());
  const filtered = $derived(
    rows.filter(
      (row) =>
        (status === 'all' || row.status === status) &&
        (!needle || row.fa.toLowerCase().includes(needle) || row.en.toLowerCase().includes(needle) || row.email.toLowerCase().includes(needle)),
    ),
  );
  const chosen = $derived(filtered.filter((row) => selected.has(row.id)));

  const nameOf = (row: Member) => (app.lang === 'fa' ? row.fa : row.en);
  const roleLabel = $derived<Record<Role, string>>({ admin: u.roleAdmin, manager: u.roleManager, support: u.roleSupport, user: u.roleUser });
  const statusLabel = $derived<Record<Status, string>>({ active: u.active, invited: u.invited, inactive: u.inactive });

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
    const n = (value: number) => new Intl.NumberFormat(intlLocale()).format(value);
    const minutes = Math.max(0, Math.round((NOW - at) / 60_000));
    if (minutes < 1) return u.justNow;
    if (minutes < 60) return tr(`${n(minutes)} دقیقه پیش`, `${n(minutes)}m ago`);
    const hours = Math.round(minutes / 60);
    if (hours < 24) return tr(`${n(hours)} ساعت پیش`, `${n(hours)}h ago`);
    const days = Math.round(hours / 24);
    return tr(`${n(days)} روز پیش`, `${n(days)}d ago`);
  }

  const allChecked = $derived(filtered.length > 0 && chosen.length === filtered.length);

  // The select-all checkbox's indeterminate state is a property, not an attribute.
  $effect(() => {
    someChecked = chosen.length > 0 && chosen.length < filtered.length;
  });

  const toggle = (id: string) => {
    const next = new Set(selected);
    if (next.has(id)) next.delete(id);
    else next.add(id);
    selected = next;
  };

  const toggleAll = () => {
    selected = allChecked ? new Set() : new Set(filtered.map((row) => row.id));
  };

  const removeChosen = () => {
    rows = rows.filter((row) => !selected.has(row.id));
    selected = new Set();
    confirming = false;
    toast(u.removedSelected);
  };

  const canInvite = $derived(invite.name.trim().length > 0 && invite.email.trim().includes('@'));

  const sendInvite = () => {
    const name = invite.name.trim();
    const email = invite.email.trim().toLowerCase();
    if (!name || !email.includes('@')) return;
    rows = [...rows, { id: `u-${Date.now().toString(36)}`, fa: name, en: name, email, role: invite.role, status: 'invited', lastSeen: NOW }];
    inviting = false;
    invite = { name: '', email: '', role: 'user' };
    toast(tr(`دعوت‌نامه برای ${email} ارسال شد`, `An invitation went to ${email}`));
  };

  /** A real download of what is on show (BOM first, so Persian opens right in Excel). */
  const exportCsv = () => {
    const head = [u.colName, u.colEmail, u.colRole, u.colStatus, u.colLastSeen];
    const body = filtered.map((row) => [nameOf(row), row.email, roleLabel[row.role], statusLabel[row.status], lastSeenWord(row.lastSeen)]);
    const csv = `\uFEFF${[head, ...body].map((cells) => cells.map((cell) => `"${cell.replaceAll('"', '""')}"`).join(',')).join('\n')}`;
    const url = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8' }));
    const link = document.createElement('a');
    link.href = url;
    link.download = 'nabu-users.csv';
    link.click();
    URL.revokeObjectURL(url);
    toast(u.csvReady);
  };

  const counts = $derived({
    all: rows.length,
    active: rows.filter((row) => row.status === 'active').length,
    invited: rows.filter((row) => row.status === 'invited').length,
    inactive: rows.filter((row) => row.status === 'inactive').length,
  });

  const filterItems = $derived<ChipItem[]>([
    { value: 'all', label: u.filterAll, count: counts.all },
    { value: 'active', label: u.active, count: counts.active },
    { value: 'invited', label: u.invited, count: counts.invited },
    { value: 'inactive', label: u.inactive, count: counts.inactive },
  ]);

  // The dialogs are native <dialog> elements behind the nx-dialog styles. The
  // `onclose` below keeps the state honest when the browser closes the dialog
  // itself (Escape) — otherwise the stale `true` would block the next showModal.
  $effect(() => {
    if (confirming) confirmDialog?.showModal();
    else confirmDialog?.close();
  });
  $effect(() => {
    if (inviting) inviteDialog?.showModal();
    else inviteDialog?.close();
  });

  // Clicks on the backdrop (the dialog element itself) close; clicks inside flow on.
  const backdropClose = (event: MouseEvent, close: () => void) => {
    if (event.target === event.currentTarget) close();
  };
</script>

<div class="adm-dashboard">
  <div class="adm-wide" style="display: flex; flex-wrap: wrap; gap: var(--nx-space-3); align-items: center; justify-content: space-between">
    <div style="display: flex; flex-wrap: wrap; gap: var(--nx-space-2); align-items: center">
      <div class="nx-input-group" style="inline-size: min(18rem, 60vw)">
        <span class="nx-input-addon"><NxIcon name="search" /></span>
        <input class="nx-input" type="search" placeholder={u.search} aria-label={s.common.search} bind:value={query} />
      </div>
      <ChipFilter items={filterItems} bind:value={status} label={u.colStatus} />
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: var(--nx-space-2); align-items: center">
      <button type="button" class="nx-button" data-variant="secondary" onclick={exportCsv}>
        <span class="nx-button-label">
          <NxIcon name="file" />
          <span class="nx-button-text">{u.exportCsv}</span>
        </span>
      </button>
      <button type="button" class="nx-button" data-variant="primary" onclick={() => (inviting = true)}>
        <span class="nx-button-label">
          <NxIcon name="plus" />
          <span class="nx-button-text">{u.addUser}</span>
        </span>
      </button>
    </div>
  </div>

  {#if chosen.length > 0}
    <div class="adm-wide" style="display: flex; flex-wrap: wrap; gap: var(--nx-space-2); align-items: center">
      <span class="nx-badge" data-tone="accent" data-dot="">{numberFmt().format(chosen.length)} {u.selectedCount}</span>
      <button type="button" class="nx-button" data-variant="danger" data-size="sm" onclick={() => (confirming = true)}>
        <span class="nx-button-label">
          <NxIcon name="trash" />
          <span class="nx-button-text">{u.deleteTitle}</span>
        </span>
      </button>
    </div>
  {/if}

  {#snippet selectHead()}
    <input class="nx-checkbox" type="checkbox" checked={allChecked} bind:indeterminate={someChecked} aria-label={u.selectAll} onchange={toggleAll} />
  {/snippet}

  {#snippet selectCell(row: Member)}
    <input class="nx-checkbox" type="checkbox" checked={selected.has(row.id)} aria-label={`${u.select} ${nameOf(row)}`} onchange={() => toggle(row.id)} />
  {/snippet}

  {#snippet nameCell(row: Member)}
    <span style="display: inline-flex; align-items: center; gap: var(--nx-space-2)">
      <span class="nx-avatar" data-size="sm" role="img" aria-label={nameOf(row)}>
        <span aria-hidden="true">{initials(nameOf(row))}</span>
      </span>
      <span style="font-weight: 600">{nameOf(row)}</span>
    </span>
  {/snippet}

  {#snippet emailCell(row: Member)}
    <span dir="ltr" style="color: var(--nx-text-muted)">{row.email}</span>
  {/snippet}

  {#snippet roleCell(row: Member)}
    {#if row.role === 'admin'}
      <span class="nx-badge" data-tone="accent">{roleLabel[row.role]}</span>
    {:else}
      <span style="color: var(--nx-text-muted)">{roleLabel[row.role]}</span>
    {/if}
  {/snippet}

  {#snippet statusCell(row: Member)}
    <span class="nx-badge" data-tone={STATUS_TONE[row.status]} data-dot={row.status !== 'inactive' ? '' : undefined}>{statusLabel[row.status]}</span>
  {/snippet}

  {#snippet lastSeenCell(row: Member)}
    {lastSeenWord(row.lastSeen)}
  {/snippet}

  <DataTable
    class="adm-wide"
    rows={filtered}
    columns={[
      { key: 'select', label: '', width: '2.5rem', head: selectHead, cell: selectCell },
      { key: 'name', label: u.colName, sortable: true, sortValue: (row: Member) => nameOf(row), cell: nameCell },
      { key: 'email', label: u.colEmail, sortable: true, cell: emailCell },
      { key: 'role', label: u.colRole, sortable: true, sortValue: (row: Member) => ROLE_RANK[row.role], cell: roleCell },
      { key: 'status', label: u.colStatus, sortable: true, sortValue: (row: Member) => STATUS_RANK[row.status], cell: statusCell },
      { key: 'lastSeen', label: u.colLastSeen, sortable: true, sortValue: (row: Member) => row.lastSeen, cell: lastSeenCell },
    ]}
    caption={u.title}
    emptyText={needle || status !== 'all' ? u.noMatch : u.colName}
    defaultSort={{ key: 'lastSeen', direction: 'descending' }}
    maxHeight="32rem"
    rowKey={(row: Member) => row.id}
  />

  <dialog bind:this={confirmDialog} class="nx-dialog" data-size="sm" onclick={(event) => backdropClose(event, () => (confirming = false))} onclose={() => (confirming = false)}>
    <header class="nx-dialog-header">
      <h2 class="nx-dialog-title">{u.deleteTitle}</h2>
      <p class="nx-dialog-description">{u.deleteBody}</p>
    </header>
    <footer class="nx-dialog-footer">
      <button type="button" class="nx-button" data-variant="secondary" onclick={() => (confirming = false)}>
        <span class="nx-button-label"><span class="nx-button-text">{s.common.cancel}</span></span>
      </button>
      <button type="button" class="nx-button" data-variant="danger" onclick={removeChosen}>
        <span class="nx-button-label">
          <NxIcon name="trash" />
          <span class="nx-button-text">{s.common.delete}</span>
        </span>
      </button>
    </footer>
    <button type="button" class="nx-dialog-close" aria-label={s.common.close} onclick={() => (confirming = false)}>
      <NxIcon name="x" />
    </button>
  </dialog>

  <dialog bind:this={inviteDialog} class="nx-dialog" data-size="sm" onclick={(event) => backdropClose(event, () => (inviting = false))} onclose={() => (inviting = false)}>
    <header class="nx-dialog-header">
      <h2 class="nx-dialog-title">{u.addUser}</h2>
    </header>
    <div class="nx-dialog-body adm-form">
      <div class="nx-field">
        <label class="nx-label" for="invite-name">{u.colName}</label>
        <input id="invite-name" class="nx-input" bind:value={invite.name} autocomplete="off" required />
      </div>
      <div class="nx-field">
        <label class="nx-label" for="invite-email">{u.colEmail}</label>
        <input id="invite-email" class="nx-input" type="email" dir="ltr" bind:value={invite.email} autocomplete="off" required />
      </div>
      <div class="nx-field">
        <label class="nx-label" for="invite-role">{u.colRole}</label>
        <select id="invite-role" class="nx-select" bind:value={invite.role}>
          <option value="user">{u.roleUser}</option>
          <option value="support">{u.roleSupport}</option>
          <option value="manager">{u.roleManager}</option>
          <option value="admin">{u.roleAdmin}</option>
        </select>
      </div>
    </div>
    <footer class="nx-dialog-footer">
      <button type="button" class="nx-button" data-variant="secondary" onclick={() => (inviting = false)}>
        <span class="nx-button-label"><span class="nx-button-text">{s.common.cancel}</span></span>
      </button>
      <button type="button" class="nx-button" data-variant="primary" disabled={!canInvite} onclick={sendInvite}>
        <span class="nx-button-label">
          <NxIcon name="mail" />
          <span class="nx-button-text">{u.addUser}</span>
        </span>
      </button>
    </footer>
    <button type="button" class="nx-dialog-close" aria-label={s.common.close} onclick={() => (inviting = false)}>
      <NxIcon name="x" />
    </button>
  </dialog>
</div>

<style>
  /* The users toolbar spans the dashboard grid like the React panel's .adm-wide. */
  @media (min-width: 72rem) {
    .adm-wide {
      grid-column: 1 / -1;
    }
  }
</style>
