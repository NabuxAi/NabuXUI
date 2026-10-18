<script module lang="ts">
  /**
   * NxNavigation — همتای Svelte برای components/navigation.tsx در @nabuxai/ui-react.
   * سولت تک‌کامپوننتی است، پس این صندلیِ خانواده است: کامپوننت‌ها را از
   * ./navigation/ با همان نام‌هایی که همتای React صادر می‌کند بیرون می‌دهد.
   */
  export { default as Tabs, type TabItem, type TabsProps } from './navigation/tabs.svelte';
  export { default as SegmentedControl, type SegmentedControlProps } from './navigation/segmented-control.svelte';
  export { default as NavMenu, type NavLinkItem, type NavMenuProps } from './navigation/nav-menu.svelte';
  export { default as Dock, type DockItem, type DockProps } from './navigation/dock.svelte';
  export { default as Breadcrumbs, type BreadcrumbItem, type BreadcrumbsProps } from './navigation/breadcrumbs.svelte';
  export { default as Pagination, pageRange, type PaginationProps } from './navigation/pagination.svelte';
  export { default as Accordion, type AccordionItem, type AccordionProps } from './navigation/accordion.svelte';
  export { default as Menu, type MenuEntry, type MenuProps } from './navigation/menu.svelte';
</script>
