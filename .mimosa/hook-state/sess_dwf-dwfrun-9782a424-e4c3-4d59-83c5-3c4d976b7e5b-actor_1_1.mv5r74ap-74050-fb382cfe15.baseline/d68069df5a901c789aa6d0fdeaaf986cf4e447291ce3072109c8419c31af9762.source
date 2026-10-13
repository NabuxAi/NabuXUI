import { useEffect, useState, type ReactNode } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import {
  Header,
  LanguageMenu,
  NabuXUIProvider,
  ThemeToggle,
  Toaster,
  type NavItem,
} from '@nabuxai/ui-react';
import { LangContext, STRINGS, readLang, writeLang, type Lang } from './lang';

/** The three Inertia pages, in header order; `path` is the route the integrator wires. */
const NAV: ReadonlyArray<{ id: 'dashboard' | 'gallery' | 'visits'; path: string }> = [
  { id: 'dashboard', path: '/inertia' },
  { id: 'gallery', path: '/inertia/gallery' },
  { id: 'visits', path: '/inertia/visits' },
];

/**
 * The chrome every Inertia page shares: the language layer, the NabuXUI
 * provider with Inertia's `Link` wired in (the FRAMEWORKS.md contract — no
 * separate package, the React components plus `linkComponent`), the header,
 * and the toaster.
 *
 * The shell lives inside each page, i.e. inside Inertia's `App` context, so
 * `usePage` sees the current URL and the shared `locale` prop.
 */
export function Shell({ title, children }: { title: string; children: ReactNode }) {
  const { url, props } = usePage();
  const shared = (props.locale as string | undefined) ?? 'fa';
  const [lang, setLang] = useState<Lang>(() => readLang(shared === 'en' ? 'en' : 'fa'));
  const s = STRINGS[lang];
  const fa = lang === 'fa';

  // The document follows the language, exactly like the sister apps.
  useEffect(() => {
    document.documentElement.lang = lang;
    document.documentElement.dir = fa ? 'rtl' : 'ltr';
    writeLang(lang);
  }, [lang, fa]);

  const items: NavItem[] = NAV.map((entry) => ({
    label: s.nav[entry.id],
    href: entry.path,
    // Exact match (or a query string after it); the dashboard path is a prefix
    // of the others, so a plain startsWith would keep it current everywhere.
    current:
      url === entry.path ||
      url.startsWith(`${entry.path}?`) ||
      (entry.id !== 'dashboard' && url.startsWith(`${entry.path}/`)),
  }));

  return (
    <LangContext.Provider value={lang}>
      <NabuXUIProvider locale={lang} linkComponent={Link}>
        <Head title={title} />
        {/* The base document title is what the very first paint shows; the
            <Head> above takes over once the client is up. */}
        <Header
          brand={
            <span className="inr-brand">
              <span className="inr-brand-mark" aria-hidden="true">
                N
              </span>
              <span className="inr-brand-text">{s.app.brand}</span>
            </span>
          }
          brandHref="/inertia"
          items={items}
          aria-label={s.nav.ariaLabel}
          actions={
            <>
              <ThemeToggle />
              <LanguageMenu
                languages={[
                  { id: 'fa', name: 'فارسی', short: 'FA' },
                  { id: 'en', name: 'English', short: 'EN' },
                ]}
                value={lang}
                onValueChange={(value) => setLang(value === 'en' ? 'en' : 'fa')}
              />
              {/* A plain anchor on purpose: leaving the Inertia app for the
                  Livewire flavor must be a full document visit. */}
              <a className="inr-leave" href="/" title={s.app.homeTitle}>
                {s.app.home}
              </a>
            </>
          }
        />
        <main className="inr-page nx-page">
          <header className="inr-head">
            <h1>{title}</h1>
          </header>
          {children}
        </main>
        <footer className="inr-foot">
          <span>{s.footer}</span>
          <a href="/">{s.app.home} · Livewire</a>
        </footer>
        <Toaster />
      </NabuXUIProvider>
    </LangContext.Provider>
  );
}
