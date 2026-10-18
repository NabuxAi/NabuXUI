<script module lang="ts">
  /**
   * NxHeader — همتای Svelte برای components/header.tsx در @nabuxai/ui-react.
   * سولت تک‌کامپوننتی است، پس این صندلیِ خانواده است: کامپوننت‌ها را از
   * ./header/ با همان نام‌هایی که همتای React صادر می‌کند بیرون می‌دهد.
   */
  export { default as MegaMenu, type MegaMenuProps } from './header/mega-menu.svelte';
  export { default as MobileMenu, type MobileMenuProps } from './header/mobile-menu.svelte';
  export { default as Header, type HeaderProps } from './header/header.svelte';
  export { default as RouteProgress, type RouteProgressProps } from './header/route-progress.svelte';
  export { default as ThemeScript, type ThemeScriptProps } from './header/theme-script.svelte';
  export { default as ThemeToggle, type ThemeToggleProps } from './header/theme-toggle.svelte';
  export type { NavChild, NavItem } from './header/nav';
</script>
