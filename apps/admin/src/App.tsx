import { useEffect, useMemo, useState, type ComponentType } from 'react';
import {
  ActivityDropdown,
  AdminShell,
  AdminSidebar,
  CommandPalette,
  LanguageMenu,
  NabuXUIProvider,
  ThemeToggle,
  Toaster,
  toast,
  type ActivityItem,
  type AdminGroup,
  type CommandGroup,
  type IconName,
} from '@nabuxai/ui-react';
import { LangContext, STRINGS, readLang, type Lang } from './lang';
import { href, isAuthRoute, useRoute, type RouteId } from './router';
import { AGO } from './data';
import { DashboardPage } from './pages/dashboard';
import { AnalyticsPage } from './pages/analytics';
import { UsersPage } from './pages/users';
import { KanbanPage } from './pages/kanban';
import { CalendarPage } from './pages/calendar';
import { ChatPage } from './pages/chat';
import { InvoicesPage } from './pages/invoices';
import { ProfilePage } from './pages/profile';
import { SettingsPage } from './pages/settings';
import { AuthPage } from './pages/auth';

type PanelRoute = Exclude<RouteId, 'login' | 'register' | 'forgot'>;

/** The page registry: one list drives the sidebar, the router and the palette. */
interface PanelPage {
  id: PanelRoute;
  group: 'main' | 'work' | 'account';
  icon: IconName;
  Component: ComponentType;
}

const PANEL: readonly PanelPage[] = [
  { id: 'dash', group: 'main', icon: 'grid', Component: DashboardPage },
  { id: 'analytics', group: 'main', icon: 'chart', Component: AnalyticsPage },
  { id: 'users', group: 'main', icon: 'users', Component: UsersPage },
  { id: 'kanban', group: 'work', icon: 'layers', Component: KanbanPage },
  { id: 'calendar', group: 'work', icon: 'grid', Component: CalendarPage },
  { id: 'chat', group: 'work', icon: 'message', Component: ChatPage },
  { id: 'invoices', group: 'work', icon: 'file', Component: InvoicesPage },
  { id: 'profile', group: 'account', icon: 'user', Component: ProfilePage },
  { id: 'settings', group: 'account', icon: 'settings', Component: SettingsPage },
];

export function App() {
  const [lang, setLang] = useState<Lang>(readLang);
  const [route, go] = useRoute();
  const [palette, setPalette] = useState(false);
  const fa = lang === 'fa';
  // App itself sits above the LangContext provider, so it reads the dictionary
  // straight from the state instead of the context (the default of which is fa).
  const s = STRINGS[lang];
  const tr = (a: string, b: string) => (fa ? a : b);

  const page = PANEL.find((entry) => entry.id === route);
  const authTitle = route === 'register' ? s.pages.auth.registerTitle : route === 'forgot' ? s.pages.auth.forgotTitle : s.pages.auth.loginTitle;
  const title = page ? s.pages[page.id].title : authTitle;

  // The document follows the language, exactly like the showcase app.
  useEffect(() => {
    document.documentElement.lang = lang;
    document.documentElement.dir = fa ? 'rtl' : 'ltr';
    document.title = `${title} · ${s.app.name}`;
    try {
      localStorage.setItem('nabuxai.admin.lang', lang);
    } catch {
      /* not persisted */
    }
  }, [lang, fa, title, s.app.name]);

  // A new page starts from the top of the shell’s scroll area.
  useEffect(() => {
    document.querySelector('.nx-admin-content')?.scrollTo({ top: 0 });
  }, [route]);

  const signOut = () => {
    go('login');
    toast(s.app.signedOut);
  };

  const activity: ActivityItem[] = [
    { id: 'a1', actor: { name: tr('مریم رضایی', 'Maryam Rezaei') }, text: tr('سفارش را تأیید کرد', 'confirmed order'), target: '#1248', time: AGO.minutes18.at, unread: true },
    { id: 'a2', actor: { name: tr('علی نیک‌پور', 'Ali Nikpour') }, text: tr('کامنت گذاشت روی', 'commented on'), target: tr('صفحهٔ پرداخت', 'Checkout page'), time: AGO.hour1.at, unread: true },
    { id: 'a3', actor: { name: tr('سارا احمدی', 'Sara Ahmadi') }, text: tr('گزارش مالی را بست', 'closed the financial report'), time: AGO.day1.at },
  ];

  const groups: AdminGroup[] = (['main', 'work', 'account'] as const).map((group) => ({
    label: s.nav[group],
    items: PANEL.filter((entry) => entry.group === group).map((entry) => ({
      id: entry.id,
      label: s.pages[entry.id].title,
      icon: entry.icon,
      href: href(entry.id),
      // A live count on the one page that keeps one.
      badge: entry.id === 'chat' ? 3 : undefined,
    })),
  }));

  const commands: CommandGroup[] = [
    {
      label: s.command.pages,
      items: PANEL.map((entry) => ({
        id: entry.id,
        label: s.pages[entry.id].title,
        icon: entry.icon,
        keywords: [entry.id],
        onSelect: () => go(entry.id),
      })),
    },
    {
      label: s.command.actions,
      items: [
        { id: 'dashboard', label: s.command.goDashboard, icon: 'grid', onSelect: () => go('dash') },
        { id: 'lang', label: s.command.switchLang, icon: 'globe', onSelect: () => setLang(fa ? 'en' : 'fa') },
        { id: 'signout', label: s.command.signOut, icon: 'lock', onSelect: signOut },
      ],
    },
  ];

  const sidebar = (
    <AdminSidebar brand={s.app.brand} navLabel={s.app.navLabel} groups={groups} footer={<span className="nx-admin-brand-text">{s.app.tagline}</span>} />
  );

  if (isAuthRoute(route)) {
    return (
      <LangContext.Provider value={lang}>
        <NabuXUIProvider locale={lang}>
          <AuthPage />
          <Toaster />
        </NabuXUIProvider>
      </LangContext.Provider>
    );
  }

  return (
    <LangContext.Provider value={lang}>
      <NabuXUIProvider locale={lang}>
        <AdminShell
          active={route}
          onActiveChange={(id) => go(id as PanelRoute)}
          title={title}
          subtitle={page ? s.pages[page.id].subtitle : undefined}
          searchPlaceholder={s.app.searchPlaceholder}
          searchHint={s.app.searchHint}
          onSearchActivate={() => setPalette(true)}
          sidebar={sidebar}
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
              <ActivityDropdown items={activity} title={s.app.notifications} />
            </>
          }
          user={{
            name: s.app.user.name,
            role: s.app.user.role,
            menuLabel: s.app.user.menuLabel,
            menu: [
              { label: s.pages.profile.title, icon: 'user', onClick: () => go('profile') },
              { label: s.pages.settings.title, icon: 'settings', onClick: () => go('settings') },
              { label: '', divider: true },
              { label: s.app.logout, icon: 'lock', onClick: signOut },
            ],
          }}
        >
          {page && (
            <div key={route}>
              <page.Component />
            </div>
          )}
        </AdminShell>
        <CommandPalette open={palette} onOpenChange={setPalette} groups={commands} />
        <Toaster />
      </NabuXUIProvider>
    </LangContext.Provider>
  );
}
