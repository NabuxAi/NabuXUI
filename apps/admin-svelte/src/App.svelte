<script lang="ts">
  /**
   * The panel root — the shape of apps/admin/src/App.tsx: one page registry
   * drives the sidebar, the router and the document title; the admin shell wraps
   * every panel page and the auth view stands outside it. Language is a rune
   * store the whole tree reads; the document follows it (lang/dir/title).
   */
  import type { Component } from 'svelte';
  import { type IconName, toast } from '@nabuxai/ui-core';
  import { app, chat, strings } from './store.svelte';
  import { go, href, isAuthRoute, router, type PanelId } from './router.svelte';
  import AdminShell from './lib/AdminShell.svelte';
  import type { SidebarGroup } from './lib/AdminSidebar.svelte';
  import type { TopbarUser } from './lib/AdminTopbar.svelte';
  import ThemeToggle from './lib/ThemeToggle.svelte';
  import LangMenu from './lib/LangMenu.svelte';
  import Toaster from './lib/Toaster.svelte';
  import DashboardPage from './pages/Dashboard.svelte';
  import AnalyticsPage from './pages/Analytics.svelte';
  import UsersPage from './pages/Users.svelte';
  import KanbanPage from './pages/KanbanPage.svelte';
  import CalendarPage from './pages/Calendar.svelte';
  import ChatPage from './pages/Chat.svelte';
  import InvoicesPage from './pages/Invoices.svelte';
  import ProfilePage from './pages/Profile.svelte';
  import SettingsPage from './pages/Settings.svelte';
  import AuthPage from './pages/Auth.svelte';

  /** The page registry: one list drives the sidebar and the router. */
  interface PanelPage {
    id: PanelId;
    group: 'main' | 'work' | 'account';
    icon: IconName;
    Component: Component;
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

  const s = $derived(strings());

  /** The chat page's live unread total — opening a conversation clears its share. */
  const chatUnread = $derived(Object.values(chat.unread).reduce((sum, count) => sum + count, 0));

  const page = $derived(PANEL.find((entry) => entry.id === router.route));
  const Page = $derived(page?.Component);
  const pageTitle = $derived(page ? s.pages[page.id].title : s.pages.auth.loginTitle);
  const pageSubtitle = $derived(page ? s.pages[page.id].subtitle : undefined);

  // The document follows the language, exactly like the showcase app.
  $effect(() => {
    document.documentElement.lang = app.lang;
    document.documentElement.dir = app.lang === 'fa' ? 'rtl' : 'ltr';
    document.title = `${pageTitle} · ${s.app.name}`;
  });

  const groups = $derived<SidebarGroup[]>(
    (['main', 'work', 'account'] as const).map((group) => ({
      id: group,
      label: s.nav[group],
      items: PANEL.filter((entry) => entry.group === group).map((entry) => ({
        id: entry.id,
        label: s.pages[entry.id].title,
        icon: entry.icon,
        href: href(entry.id),
        // A live unread count on the chat page — cleared by reading, not by hand.
        badge: entry.id === 'chat' && chatUnread > 0 ? chatUnread : undefined,
      })),
    })),
  );

  const user = $derived<TopbarUser>({
    name: s.app.user.name,
    role: s.app.user.role,
    menuLabel: s.app.user.menuLabel,
    menu: [
      { id: 'profile', label: s.pages.profile.title, icon: 'user' },
      { id: 'settings', label: s.pages.settings.title, icon: 'settings' },
      { id: 'logout', label: s.app.logout, icon: 'lock' },
    ],
  });

  const languageOptions = $derived([
    { id: 'fa' as const, name: 'فارسی', short: 'FA' },
    { id: 'en' as const, name: 'English', short: 'EN' },
  ]);

  const signOut = () => {
    go('login');
    toast(s.app.signedOut);
  };

  // The search seat jumps to the users page, where the real search box lives.
  // ⌘K / Ctrl+K do the same from anywhere in the panel.
  const search = () => go('users');

  const onMenu = (id: string) => {
    if (id === 'profile') go('profile');
    else if (id === 'settings') go('settings');
    else if (id === 'logout') signOut();
  };

  $effect(() => {
    const onKeydown = (event: KeyboardEvent) => {
      if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
        event.preventDefault();
        search();
      }
    };
    window.addEventListener('keydown', onKeydown);
    return () => window.removeEventListener('keydown', onKeydown);
  });
</script>

{#if isAuthRoute(router.route)}
  <AuthPage />
{:else if page && Page}
  <AdminShell active={page.id} title={pageTitle} subtitle={pageSubtitle} {groups} {user} onsearch={search} onmenu={onMenu}>
    {#snippet actions()}
      <ThemeToggle />
      <LangMenu label={s.app.navLabel} options={languageOptions} />
    {/snippet}
    {#key page.id}
      <Page />
    {/key}
  </AdminShell>
{/if}
<Toaster />
