/**
 * Users — the member table, with real state all the way down: live search, a
 * status chip filter, sorting (the table block's own), row selection with a
 * confirm-before-delete dialog, an invite dialog that appends a genuine row,
 * and a CSV export of exactly what is on show. Names are seeded bilingual;
 * "last seen" hangs off the shared NOW of ../data and reads as a relative
 * word in the locale's digits.
 */
import { useState } from 'react';
import { Avatar, Button, ChipFilter, DataTable, Dialog, Field, Input, Select, toast, type DataTableColumn } from '@nabuxai/ui-react';
import { useLang, useStrings, useTr, type Lang } from '../lang';
import { AGO, NOW } from '../data';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

type Role = 'admin' | 'manager' | 'support' | 'user';
type Status = 'active' | 'invited' | 'inactive';

/** A type (not interface) so the table's `Row` constraint accepts it. */
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

/** "۱۸ دقیقه پیش" / "18m ago" — the locale's digits, measured from the shared NOW. */
function lastSeenWord(at: number, lang: Lang, justNow: string) {
  const tr = (fa: string, en: string) => (lang === 'fa' ? fa : en);
  const n = (value: number) => new Intl.NumberFormat(INTL[lang]).format(value);
  const minutes = Math.max(0, Math.round((NOW - at) / 60_000));
  if (minutes < 1) return justNow;
  if (minutes < 60) return tr(`${n(minutes)} دقیقه پیش`, `${n(minutes)}m ago`);
  const hours = Math.round(minutes / 60);
  if (hours < 24) return tr(`${n(hours)} ساعت پیش`, `${n(hours)}h ago`);
  const days = Math.round(hours / 24);
  return tr(`${n(days)} روز پیش`, `${n(days)}d ago`);
}

export function UsersPage() {
  const lang = useLang();
  const tr = useTr();
  const s = useStrings();
  const u = s.pages.users;
  const number = new Intl.NumberFormat(INTL[lang]);

  const [rows, setRows] = useState<Member[]>(SEED);
  const [query, setQuery] = useState('');
  const [status, setStatus] = useState('all');
  const [selected, setSelected] = useState<ReadonlySet<string>>(() => new Set());
  const [confirming, setConfirming] = useState(false);
  const [inviting, setInviting] = useState(false);
  const [invite, setInvite] = useState({ name: '', email: '', role: 'user' as Role });

  const needle = query.trim().toLowerCase();
  const filtered = rows.filter(
    (row) =>
      (status === 'all' || row.status === status) &&
      (!needle || row.fa.toLowerCase().includes(needle) || row.en.toLowerCase().includes(needle) || row.email.toLowerCase().includes(needle)),
  );
  const chosen = filtered.filter((row) => selected.has(row.id));

  const nameOf = (row: Member) => (lang === 'fa' ? row.fa : row.en);
  const roleLabel: Record<Role, string> = { admin: u.roleAdmin, manager: u.roleManager, support: u.roleSupport, user: u.roleUser };
  const statusLabel: Record<Status, string> = { active: u.active, invited: u.invited, inactive: u.inactive };

  const toggle = (id: string) =>
    setSelected((prev) => {
      const next = new Set(prev);
      if (next.has(id)) next.delete(id);
      else next.add(id);
      return next;
    });

  const removeChosen = () => {
    setRows((prev) => prev.filter((row) => !selected.has(row.id)));
    setSelected(new Set());
    setConfirming(false);
    toast(u.removedSelected);
  };

  const sendInvite = () => {
    const name = invite.name.trim();
    const email = invite.email.trim().toLowerCase();
    if (!name || !email.includes('@')) return;
    setRows((prev) => [...prev, { id: `u-${Date.now().toString(36)}`, fa: name, en: name, email, role: invite.role, status: 'invited', lastSeen: NOW }]);
    setInviting(false);
    setInvite({ name: '', email: '', role: 'user' });
    toast(tr(`دعوت‌نامه برای ${email} ارسال شد`, `An invitation went to ${email}`));
  };

  /** A real download of what is on show (BOM first, so Persian opens right in Excel). */
  const exportCsv = () => {
    const head = [u.colName, u.colEmail, u.colRole, u.colStatus, u.colLastSeen];
    const body = filtered.map((row) => [nameOf(row), row.email, roleLabel[row.role], statusLabel[row.status], lastSeenWord(row.lastSeen, lang, u.justNow)]);
    const csv = `\uFEFF${[head, ...body].map((cells) => cells.map((cell) => `"${cell.replaceAll('"', '""')}"`).join(',')).join('\n')}`;
    const url = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8' }));
    const link = document.createElement('a');
    link.href = url;
    link.download = 'nabu-users.csv';
    link.click();
    URL.revokeObjectURL(url);
    toast(u.csvReady);
  };

  const columns: DataTableColumn<Member>[] = [
    {
      key: 'select',
      label: (
        <input
          type="checkbox"
          checked={filtered.length > 0 && chosen.length === filtered.length}
          ref={(el) => {
            if (el) el.indeterminate = chosen.length > 0 && chosen.length < filtered.length;
          }}
          onChange={() => setSelected(chosen.length === filtered.length ? new Set() : new Set(filtered.map((row) => row.id)))}
          aria-label={u.selectAll}
        />
      ),
      width: '2.5rem',
      format: (_value, row) => (
        <input type="checkbox" checked={selected.has(row.id)} onChange={() => toggle(row.id)} aria-label={`${u.select} ${nameOf(row)}`} />
      ),
    },
    {
      key: 'name',
      label: u.colName,
      sortable: true,
      sortValue: (row) => nameOf(row),
      format: (_value, row) => (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 'var(--nx-space-2)' }}>
          <Avatar name={nameOf(row)} size="sm" status={row.status === 'active' ? 'online' : 'offline'} />
          <span style={{ fontWeight: 600 }}>{nameOf(row)}</span>
        </span>
      ),
    },
    {
      key: 'email',
      label: u.colEmail,
      sortable: true,
      format: (value) => (
        <span dir="ltr" style={{ color: 'var(--nx-text-muted)' }}>
          {String(value)}
        </span>
      ),
    },
    {
      key: 'role',
      label: u.colRole,
      sortable: true,
      sortValue: (row) => ROLE_RANK[row.role],
      format: (_value, row) =>
        row.role === 'admin' ? (
          <span className="nx-badge" data-tone="accent">
            {roleLabel[row.role]}
          </span>
        ) : (
          <span style={{ color: 'var(--nx-text-muted)' }}>{roleLabel[row.role]}</span>
        ),
    },
    {
      key: 'status',
      label: u.colStatus,
      sortable: true,
      sortValue: (row) => STATUS_RANK[row.status],
      format: (_value, row) => (
        <span className="nx-badge" data-tone={STATUS_TONE[row.status]} data-dot={row.status !== 'inactive' ? '' : undefined}>
          {statusLabel[row.status]}
        </span>
      ),
    },
    { key: 'lastSeen', label: u.colLastSeen, sortable: true, sortValue: (row) => row.lastSeen, format: (_value, row) => lastSeenWord(row.lastSeen, lang, u.justNow) },
  ];

  const counts = {
    active: rows.filter((row) => row.status === 'active').length,
    invited: rows.filter((row) => row.status === 'invited').length,
    inactive: rows.filter((row) => row.status === 'inactive').length,
  };
  const canInvite = invite.name.trim().length > 0 && invite.email.trim().includes('@');

  return (
    <div className="adm-dashboard">
      <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-3)', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <Input
            type="search"
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            placeholder={u.search}
            aria-label={s.common.search}
            startAddon="search"
            style={{ inlineSize: 'min(18rem, 60vw)' }}
          />
          <ChipFilter
            aria-label={u.colStatus}
            value={status}
            onValueChange={setStatus}
            items={[
              { value: 'all', label: u.filterAll, count: rows.length },
              { value: 'active', label: u.active, count: counts.active },
              { value: 'invited', label: u.invited, count: counts.invited },
              { value: 'inactive', label: u.inactive, count: counts.inactive },
            ]}
          />
        </div>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <Button variant="secondary" icon="file" onClick={exportCsv}>
            {u.exportCsv}
          </Button>
          <Button variant="primary" icon="plus" onClick={() => setInviting(true)}>
            {u.addUser}
          </Button>
        </div>
      </div>

      {chosen.length > 0 && (
        <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <span className="nx-badge" data-tone="accent" data-dot="">
            {number.format(chosen.length)} {u.selectedCount}
          </span>
          <Button size="sm" variant="danger" icon="trash" onClick={() => setConfirming(true)}>
            {u.deleteTitle}
          </Button>
        </div>
      )}

      <DataTable
        className="adm-wide"
        caption={u.title}
        captionHidden
        maxHeight="32rem"
        rows={filtered}
        columns={columns}
        defaultSort={{ key: 'lastSeen', direction: 'descending' }}
        emptyText={needle || status !== 'all' ? u.noMatch : u.emptyTitle}
      />

      <Dialog
        open={confirming}
        onOpenChange={setConfirming}
        size="sm"
        title={u.deleteTitle}
        description={u.deleteBody}
        footer={
          <>
            <Button variant="secondary" onClick={() => setConfirming(false)}>
              {s.common.cancel}
            </Button>
            <Button variant="danger" icon="trash" onClick={removeChosen}>
              {s.common.delete}
            </Button>
          </>
        }
      />

      <Dialog
        open={inviting}
        onOpenChange={setInviting}
        size="sm"
        title={u.secondary}
        footer={
          <>
            <Button variant="secondary" onClick={() => setInviting(false)}>
              {s.common.cancel}
            </Button>
            <Button variant="primary" icon="mail" disabled={!canInvite} onClick={sendInvite}>
              {u.secondary}
            </Button>
          </>
        }
      >
        <div style={{ display: 'grid', gap: 'var(--nx-space-3)' }}>
          <Field label={u.colName} required>
            <Input value={invite.name} onChange={(event) => setInvite({ ...invite, name: event.target.value })} autoComplete="off" />
          </Field>
          <Field label={u.colEmail} required>
            <Input type="email" dir="ltr" value={invite.email} onChange={(event) => setInvite({ ...invite, email: event.target.value })} autoComplete="off" />
          </Field>
          <Field label={u.colRole}>
            <Select
              value={invite.role}
              onChange={(event) => setInvite({ ...invite, role: event.target.value as Role })}
              options={[
                { value: 'user', label: u.roleUser },
                { value: 'support', label: u.roleSupport },
                { value: 'manager', label: u.roleManager },
                { value: 'admin', label: u.roleAdmin },
              ]}
            />
          </Field>
        </div>
      </Dialog>
    </div>
  );
}
