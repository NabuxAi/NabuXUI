<script lang="ts">
/**
 * NxNavigation — the navigation seat of @nabuxai/ui-vue: tabs, segmented
 * controls, nav menus, the dock, breadcrumbs, pagination, accordions and
 * dropdown menus — the Vue counterparts of ui-react's components/navigation.tsx.
 */
import NxTabs from '../internal/nav/tabs.vue';

// The seat's face for a default import; every part is a named export below.
export default NxTabs;

export { default as NxTabs, type NxTabItem, type NxTabsProps } from '../internal/nav/tabs.vue';
export { default as NxSegmentedControl, type NxSegmentedControlProps, type NxSegmentOption } from '../internal/nav/segmented.vue';
export { default as NxNavMenu, type NxNavMenuProps, type NxNavLinkItem } from '../internal/nav/navmenu.vue';
export { default as NxDock, type NxDockItem, type NxDockProps } from '../internal/nav/dock.vue';
export { default as NxBreadcrumbs, type NxBreadcrumbItem, type NxBreadcrumbsProps } from '../internal/nav/breadcrumbs.vue';
export { default as NxPagination, pageRange, type NxPaginationProps } from '../internal/nav/pagination.vue';
export { default as NxAccordion, type NxAccordionItem, type NxAccordionProps } from '../internal/nav/accordion.vue';
export { default as NxMenu, type NxMenuEntry, type NxMenuProps } from '../internal/nav/menu.vue';
</script>
