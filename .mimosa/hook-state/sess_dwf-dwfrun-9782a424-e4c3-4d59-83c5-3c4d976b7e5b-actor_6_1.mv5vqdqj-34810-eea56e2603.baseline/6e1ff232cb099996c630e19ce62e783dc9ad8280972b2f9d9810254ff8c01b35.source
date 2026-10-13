/**
 * The shared shell of a not-yet-filled page: the new empty-state block with
 * the page’s own title/description from the language file and one honest way
 * out — back to the dashboard. Each stub page file wraps this; the agents that
 * fill a page replace that file and consume keys from ../lang.
 */
import { EmptyState, type IconName } from '@nabuxai/ui-react';
import { useStrings } from '../lang';
import { href } from '../router';

export type StubId =
  | 'analytics'
  | 'products'
  | 'orders'
  | 'users'
  | 'roles'
  | 'kanban'
  | 'calendar'
  | 'chat'
  | 'email'
  | 'files'
  | 'todo'
  | 'invoices'
  | 'profile'
  | 'settings';

export function StubPage({ id, icon }: { id: StubId; icon: IconName }) {
  const s = useStrings();
  const page = s.pages[id];
  return (
    <EmptyState
      icon={icon}
      title={page.emptyTitle}
      description={page.emptyBody}
      action={{ label: s.pages.dash.title, icon: 'grid', href: href('dash') }}
    />
  );
}
