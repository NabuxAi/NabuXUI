/**
 * A deliberately tiny hash router — the panel's pages are static ids (`#/users`,
 * `#/kanban`…) and the auth view (`#/login`) renders outside the admin shell.
 * The same ids the React panel uses, minus the register/forgot panes this
 * flavour does not ship. A `.svelte.ts` module so the route is a rune.
 */

export type PanelId = 'dash' | 'analytics' | 'users' | 'kanban' | 'calendar' | 'chat' | 'invoices' | 'profile' | 'settings';
export type RouteId = PanelId | 'login';

export const ROUTES: readonly RouteId[] = ['dash', 'analytics', 'users', 'kanban', 'calendar', 'chat', 'invoices', 'profile', 'settings', 'login'];

export const isAuthRoute = (id: RouteId) => id === 'login';

export const href = (id: RouteId) => `#/${id}`;

function parse(hash: string): RouteId {
  const clean = hash.replace(/^#\/?/, '').split(/[?#]/)[0]!.toLowerCase();
  return (ROUTES as readonly string[]).includes(clean) ? (clean as RouteId) : 'dash';
}

function current(): RouteId {
  return typeof window === 'undefined' ? 'dash' : parse(window.location.hash);
}

/** The route, reactive. Manual hash edits, back/forward and the sidebar's
 *  plain `href="#/…"` links all land here through the hashchange listener. */
export const router = $state({ route: current() });

if (typeof window !== 'undefined') {
  window.addEventListener('hashchange', () => {
    router.route = current();
  });
}

export function go(id: RouteId): void {
  router.route = id;
  if (current() !== id) window.location.hash = href(id);
}
