/**
 * #/roles — the team's permission matrix. Rows are permissions grouped by
 * area (store, products, orders…, the group cell spanning its rows), columns
 * are roles, and every crossing is a live switch: flipping one wakes the
 * save button, which lands the matrix with a toast. The admin role is locked
 * on — its switches stay disabled and the hint under the table says why —
 * and "New role" appends a genuine column (its typed name, zero members, no
 * grants yet). Member counts mirror the users page's seed; every number
 * rolls with the locale's digits. Words come from `s.pages.roles` in ../lang
 * (plus one-off `useTr` pairs), never edited here.
 */
import { useState, type CSSProperties } from 'react';
import { Button, Dialog, Field, Icon, Input, Switch, toast, type ButtonStatus } from '@nabuxai/ui-react';
import { useLang, useStrings, useTr } from '../lang';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

type PermId = 'view' | 'create' | 'edit' | 'delete' | 'approve';
type RoleWord = 'roleAdmin' | 'roleManager' | 'roleSupport' | 'roleUser';
type GroupWord = 'gStore' | 'gProducts' | 'gOrders' | 'gUsers' | 'gReports' | 'gSystem';
type PermWord = 'pView' | 'pCreate' | 'pEdit' | 'pDelete' | 'pApprove';

/** A role column; built-ins take their name from the language layer, added ones carry their own. */
interface RoleColumn {
  id: string;
  word?: RoleWord;
  name?: string;
  members: number;
  /** Locked columns cannot be edited — the admin role always has everything. */
  locked?: boolean;
}

interface PermGroup {
  id: string;
  word: GroupWord;
  perms: PermId[];
}

/** The matrix's shape; the words themselves resolve from s.pages.roles at render time. */
const GROUPS: PermGroup[] = [
  { id: 'store', word: 'gStore', perms: ['view', 'edit'] },
  { id: 'products', word: 'gProducts', perms: ['view', 'create', 'edit', 'delete'] },
  { id: 'orders', word: 'gOrders', perms: ['view', 'edit', 'approve'] },
  { id: 'users', word: 'gUsers', perms: ['view', 'create', 'edit', 'delete'] },
  { id: 'reports', word: 'gReports', perms: ['view'] },
  { id: 'system', word: 'gSystem', perms: ['edit', 'delete'] },
];

const PERM_WORDS: Record<PermId, PermWord> = { view: 'pView', create: 'pCreate', edit: 'pEdit', delete: 'pDelete', approve: 'pApprove' };

/** 1 admin, 2 managers, 3 support, 4 users — exactly the users page's seed. */
const BASE_ROLES: RoleColumn[] = [
  { id: 'admin', word: 'roleAdmin', members: 1, locked: true },
  { id: 'manager', word: 'roleManager', members: 2 },
  { id: 'support', word: 'roleSupport', members: 3 },
  { id: 'user', word: 'roleUser', members: 4 },
];

const grantKey = (groupId: string, perm: PermId, roleId: string) => `${groupId}.${perm}.${roleId}`;

/** The seed matrix — deterministic, no randomness; the admin column is all-on. */
function seedGrants(): Record<string, boolean> {
  const grants: Record<string, boolean> = {};
  const allow = (groupId: string, perm: PermId, roleIds: string[]) => {
    for (const roleId of roleIds) grants[grantKey(groupId, perm, roleId)] = true;
  };
  for (const group of GROUPS) for (const perm of group.perms) allow(group.id, perm, ['admin']);
  allow('store', 'view', ['manager', 'support']);
  allow('store', 'edit', ['manager']);
  allow('products', 'view', ['manager', 'support']);
  allow('products', 'create', ['manager']);
  allow('products', 'edit', ['manager']);
  allow('orders', 'view', ['manager', 'support', 'user']);
  allow('orders', 'edit', ['manager', 'support']);
  allow('orders', 'approve', ['manager']);
  allow('users', 'view', ['manager']);
  allow('reports', 'view', ['manager']);
  return grants;
}

export function RolesPage() {
  const lang = useLang();
  const tr = useTr();
  const s = useStrings();
  const r = s.pages.roles;
  const number = new Intl.NumberFormat(INTL[lang]);

  const [roles, setRoles] = useState<RoleColumn[]>(BASE_ROLES);
  const [grants, setGrants] = useState<Record<string, boolean>>(seedGrants);
  const [dirty, setDirty] = useState(false);
  const [saveState, setSaveState] = useState<ButtonStatus>('idle');
  const [adding, setAdding] = useState(false);
  const [draft, setDraft] = useState('');

  const roleName = (role: RoleColumn) => (role.word ? r[role.word] : role.name ?? '');
  const permName = (perm: PermId) => r[PERM_WORDS[perm]];

  const flip = (group: PermGroup, perm: PermId, role: RoleColumn) => (checked: boolean) => {
    setGrants((prev) => ({ ...prev, [grantKey(group.id, perm, role.id)]: checked }));
    setDirty(true);
  };

  const save = () => {
    if (saveState !== 'idle' || !dirty) return;
    setSaveState('loading');
    window.setTimeout(() => {
      setSaveState('success');
      setDirty(false);
      toast.success(r.matrixSaved);
      window.setTimeout(() => setSaveState('idle'), 1400);
    }, 700);
  };

  const addRole = () => {
    const name = draft.trim();
    if (!name) return;
    setRoles((prev) => [...prev, { id: `role-${Date.now().toString(36)}`, name, members: 0 }]);
    setAdding(false);
    setDraft('');
    setDirty(true);
    toast(tr(`نقش «${name}» ساخته شد — همهٔ دسترسی‌ها خاموش`, `The role “${name}” was created — every access off`));
  };

  return (
    <div className="adm-dashboard">
      <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-3)', alignItems: 'center', justifyContent: 'space-between' }}>
        {/* A quiet key to the switches — decorative, so it stays out of the tab order. */}
        <span aria-hidden="true" style={{ display: 'inline-flex', flexWrap: 'wrap', gap: 'var(--nx-space-3)', alignItems: 'center', pointerEvents: 'none' }}>
          <Switch size="sm" checked tabIndex={-1} label={r.allow} />
          <Switch size="sm" tabIndex={-1} label={r.deny} />
        </span>
        <span style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <Button variant="secondary" icon="plus" onClick={() => setAdding(true)}>
            {r.addRole}
          </Button>
          <Button variant="primary" icon="check" status={saveState} disabled={!dirty && saveState === 'idle'} onClick={save}>
            {r.saveMatrix}
          </Button>
        </span>
      </div>

      <div className="nx-data-table adm-wide" style={{ '--nx-table-max': 'calc(100dvh - 26rem)' } as CSSProperties}>
        <table style={{ minWidth: '38rem' }}>
          <caption className="nx-visually-hidden">{r.title}</caption>
          <thead>
            <tr>
              <th scope="col">{r.colPermission}</th>
              {roles.map((role) => (
                <th key={role.id} scope="col" data-align="center" style={{ inlineSize: '9rem' }}>
                  <span style={{ display: 'grid', justifyItems: 'center', gap: '0.125rem' }}>
                    <span style={{ display: 'inline-flex', alignItems: 'center', gap: '0.3rem', color: 'var(--nx-text)' }}>
                      {roleName(role)}
                      {role.locked && <Icon name="lock" width="0.85rem" height="0.85rem" />}
                    </span>
                    <span style={{ color: 'var(--nx-text-subtle)', fontWeight: 500, whiteSpace: 'nowrap' }}>
                      {number.format(role.members)} {r.members}
                    </span>
                  </span>
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {GROUPS.map((group) =>
              group.perms.map((perm, index) => (
                <tr key={`${group.id}.${perm}`}>
                  {index === 0 && (
                    <th
                      scope="rowgroup"
                      rowSpan={group.perms.length}
                      style={{ borderInlineEnd: '1px solid var(--nx-border)', background: 'var(--nx-surface-2)', color: 'var(--nx-text)', fontWeight: 600 }}
                    >
                      {r[group.word]}
                    </th>
                  )}
                  <th scope="row" style={{ color: 'var(--nx-text)', fontWeight: 500 }}>
                    {permName(perm)}
                  </th>
                  {roles.map((role) => (
                    <td key={role.id} data-align="center">
                      <Switch
                        size="sm"
                        checked={role.locked ? true : !!grants[grantKey(group.id, perm, role.id)]}
                        disabled={role.locked}
                        onCheckedChange={flip(group, perm, role)}
                        aria-label={`${r[group.word]} · ${permName(perm)} · ${roleName(role)}`}
                      />
                    </td>
                  ))}
                </tr>
              )),
            )}
          </tbody>
        </table>
      </div>

      <p className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 'var(--nx-space-2)', margin: 0, color: 'var(--nx-text-subtle)', fontSize: 'var(--nx-text-sm)' }}>
        <Icon name="lock" width="0.9rem" height="0.9rem" />
        {r.adminLocked}
      </p>

      <Dialog
        open={adding}
        onOpenChange={setAdding}
        size="sm"
        title={r.addRole}
        footer={
          <>
            <Button variant="secondary" onClick={() => setAdding(false)}>
              {s.common.cancel}
            </Button>
            <Button variant="primary" icon="plus" disabled={!draft.trim()} onClick={addRole}>
              {r.addRole}
            </Button>
          </>
        }
      >
        <Field label={tr('نام نقش', 'Role name')} required>
          <Input value={draft} onChange={(event) => setDraft(event.target.value)} autoComplete="off" />
        </Field>
      </Dialog>
    </div>
  );
}
