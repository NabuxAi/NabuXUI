import { useEffect, useState } from 'react';

/**
 * A deliberately tiny hash router — no dependency, no history API. The panel’s
 * pages are static ids: `#/users`, `#/kanban`… The auth views (`#/login`,
 * `#/register`, `#/forgot`) render outside the admin shell.
 */

export type RouteId =
  | 'dash'
  | 'analytics'
  | 'users'
  | 'kanban'
  | 'calendar'
  | 'chat'
  | 'invoices'
  | 'profile'
  | 'settings'
  | 'login'
  | 'register'
  | 'forgot';

export const ROUTES: readonly RouteId[] = [
  'dash',
  'analytics',
  'users',
  'kanban',
  'calendar',
  'chat',
  'invoices',
  'profile',
  'settings',
  'login',
  'register',
  'forgot',
];

const AUTH_ROUTES: ReadonlySet<string> = new Set(['login', 'register', 'forgot']);

export const isAuthRoute = (id: RouteId) => AUTH_ROUTES.has(id);

export const href = (id: RouteId) => `#/${id}`;

function parse(hash: string): RouteId {
  const clean = hash.replace(/^#\/?/, '').split(/[?#]/)[0]!.toLowerCase();
  return (ROUTES as readonly string[]).includes(clean) ? (clean as RouteId) : 'dash';
}

function current(): RouteId {
  return typeof window === 'undefined' ? 'dash' : parse(window.location.hash);
}

export function useRoute(): [RouteId, (id: RouteId) => void] {
  const [route, setRoute] = useState<RouteId>(current);

  // Manual hash edits, back/forward, and the sidebar’s plain `href="#/…"` links
  // all land here.
  useEffect(() => {
    const onHash = () => setRoute(current());
    window.addEventListener('hashchange', onHash);
    return () => window.removeEventListener('hashchange', onHash);
  }, []);

  const go = (id: RouteId) => {
    setRoute(id);
    if (current() !== id) window.location.hash = href(id);
  };

  return [route, go];
}
