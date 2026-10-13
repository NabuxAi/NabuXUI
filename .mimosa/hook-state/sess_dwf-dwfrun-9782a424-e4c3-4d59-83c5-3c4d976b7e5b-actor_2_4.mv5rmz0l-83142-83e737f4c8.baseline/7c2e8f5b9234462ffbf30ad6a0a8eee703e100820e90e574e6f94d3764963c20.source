/**
 * #/err404 · #/err500 · #/maintenance — the standalone, minimal error views,
 * outside the admin shell (`.adm-error` in admin.css centres them, like the
 * auth view). The numbered flavours carry their HTTP status code big and
 * gradient-clipped in the locale's digits (۴۰۴ / 404) above the empty-state —
 * the same shape the Livewire flavour draws — while maintenance leans on its
 * mark alone. The words come from `s.pages.<err…>` in ../lang; 500 offers an
 * honest reload as its way out, the other two lead back to the panel.
 */
import { EmptyState, type IconName } from '@nabuxai/ui-react';
import { useLang, useStrings } from '../lang';
import { href, useRoute } from '../router';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

export function ErrorPage() {
  const s = useStrings();
  const lang = useLang();
  const [route] = useRoute();
  const e = route === 'err500' ? s.pages.err500 : route === 'maintenance' ? s.pages.maintenance : s.pages.err404;
  const icon: IconName = route === 'err500' ? 'alert-triangle' : route === 'maintenance' ? 'settings' : 'search';
  // The numbered flavours carry a status; maintenance is a pause, not a status.
  const code = route === 'err500' ? 500 : route === 'maintenance' ? null : 404;

  return (
    <div className="adm-error" style={{ rowGap: 'var(--nx-space-4)' }}>
      {code !== null && (
        <p
          aria-hidden="true"
          style={{
            margin: 0,
            font: '800 var(--nx-text-display) / 1 var(--nx-font-display)',
            letterSpacing: 'var(--nx-tracking-tight)',
            background: 'var(--nx-gradient-text)',
            WebkitBackgroundClip: 'text',
            backgroundClip: 'text',
            color: 'transparent',
          }}
        >
          {new Intl.NumberFormat(INTL[lang]).format(code)}
        </p>
      )}
      <EmptyState
        icon={icon}
        title={e.title}
        description={e.emptyBody}
        action={route === 'err500' ? { label: e.action, icon: 'zap', onClick: () => window.location.reload() } : { label: e.action, icon: 'grid', href: href('dash') }}
      />
    </div>
  );
}
