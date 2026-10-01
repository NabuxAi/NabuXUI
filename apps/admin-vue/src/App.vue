<script setup lang="ts">
/**
 * The panel root — the shape of apps/admin/src/App.tsx: one page registry
 * drives the sidebar, the router and the document title; the admin shell wraps
 * every panel page and the auth view stands outside it. Language is a reactive
 * store the whole tree reads; the document follows it (lang/dir/title).
 */
import { computed, watchEffect, type Component } from 'vue';
import { type IconName, toast } from '@nabuxai/ui-core';
import { fa, lang, s } from './store';
import { go, href, isAuthRoute, route, type PanelId } from './router';
import AdminShell from './components/AdminShell.vue';
import type { SidebarGroup } from './components/AdminSidebar.vue';
import ThemeToggle from './components/ThemeToggle.vue';
import LangMenu from './components/LangMenu.vue';
import Toaster from './components/Toaster.vue';
import DashboardPage from './pages/dashboard.vue';
import UsersPage from './pages/users.vue';
import KanbanPage from './pages/kanban.vue';
import SettingsPage from './pages/settings.vue';
import AuthPage from './pages/auth.vue';
import AnalyticsPage from './pages/analytics.vue';
import CalendarPage from './pages/calendar.vue';
import ChatPage from './pages/chat.vue';
import InvoicesPage from './pages/invoices.vue';
import ProfilePage from './pages/profile.vue';

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

const page = computed(() => PANEL.find((entry) => entry.id === route.value));

const pageTitle = computed(() => (page.value ? s.value.pages[page.value.id].title : s.value.pages.auth.loginTitle));
const pageSubtitle = computed(() => (page.value ? s.value.pages[page.value.id].subtitle : undefined));

// The document follows the language, exactly like the showcase app.
watchEffect(() => {
  document.documentElement.lang = lang.value;
  document.documentElement.dir = fa.value ? 'rtl' : 'ltr';
  document.title = `${pageTitle.value} · ${s.value.app.name}`;
});

const groups = computed<SidebarGroup[]>((): SidebarGroup[] =>
  (['main', 'work', 'account'] as const).map((group) => ({
    id: group,
    label: s.value.nav[group],
    items: PANEL.filter((entry) => entry.group === group).map((entry) => ({
      id: entry.id,
      label: s.value.pages[entry.id].title,
      icon: entry.icon,
      href: href(entry.id),
      // A live count on the one page that keeps one.
      badge: entry.id === 'chat' ? 3 : undefined,
    })),
  })),
);

const userMenu = computed(() => [
  { id: 'profile', label: s.value.pages.profile.title, icon: 'user' as const },
  { id: 'settings', label: s.value.pages.settings.title, icon: 'settings' as const },
  { id: 'logout', label: s.value.app.logout, icon: 'lock' as const },
]);

const user = computed(() => ({ name: s.value.app.user.name, role: s.value.app.user.role, menuLabel: s.value.app.user.menuLabel, menu: userMenu.value }));

const languageOptions = computed(() => [
  { id: 'fa' as const, name: 'فارسی', short: 'FA' },
  { id: 'en' as const, name: 'English', short: 'EN' },
]);

const signOut = () => {
  go('login');
  toast(s.value.app.signedOut);
};

// The search seat jumps to the users page, where the real search box lives.
// ⌘K / Ctrl+K do the same from anywhere in the panel.
const search = () => go('users');

const onMenu = (id: string) => {
  if (id === 'profile') go('profile');
  else if (id === 'settings') go('settings');
  else if (id === 'logout') signOut();
};

window.addEventListener('keydown', (event) => {
  if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
    event.preventDefault();
    search();
  }
});
</script>

<template>
  <template v-if="isAuthRoute(route)">
    <AuthPage />
  </template>
  <AdminShell
    v-else-if="page"
    :active="page.id"
    :title="pageTitle"
    :subtitle="pageSubtitle"
    :groups="groups"
    :user="user"
    @search="search"
    @menu="onMenu"
  >
    <template #actions>
      <ThemeToggle />
      <LangMenu :label="s.app.navLabel" :options="languageOptions" />
    </template>
    <div :key="page.id">
      <component :is="page.Component" />
    </div>
  </AdminShell>
  <Toaster />
</template>
