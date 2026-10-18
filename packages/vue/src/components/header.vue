<script lang="ts">
/**
 * NxHeader — the header seat of @nabuxai/ui-vue: the bar itself (the
 * default export), the mega menu, the mobile drill-down, route progress
 * and the theme pair — the Vue counterparts of ui-react's components/header.tsx.
 */
import { defineComponent, h } from 'vue';
import { themeScript } from '@nabuxai/ui-core';
import NxHeader from '../internal/header/header.vue';

// The seat's face for a default import; every part is a named export below.
export default NxHeader;

export { default as NxHeader, type NxHeaderProps } from '../internal/header/header.vue';
export { default as NxMegaMenu, type NxMegaMenuProps, type NxMegaMotion } from '../internal/header/megamenu.vue';
export type { NxNavChild, NxNavItem } from '../internal/header/megamenu.vue';
export { default as NxMobileMenu, type NxMobileMenuProps } from '../internal/header/mobile-menu.vue';
export { default as NxRouteProgress, type NxRouteProgressProps } from '../internal/header/route-progress.vue';
export { default as NxThemeToggle } from '../internal/header/theme-toggle.vue';

/** Render inside <head> so the page paints in the right theme from the first frame. */
export const NxThemeScript = defineComponent({
  name: 'NxThemeScript',
  props: { nonce: { type: String, required: false } },
  setup(props) {
    return () => h('script', { nonce: props.nonce, innerHTML: themeScript });
  },
});
</script>
