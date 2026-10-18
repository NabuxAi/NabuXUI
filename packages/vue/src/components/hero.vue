<script lang="ts">
/**
 * NxHero — the hero seat of @nabuxai/ui-vue: the banner itself (the
 * default export) and its decorative backdrop — the Vue counterparts of
 * ui-react's components/hero.tsx.
 */
import NxHero from '../internal/hero/hero.vue';

// The seat's face for a default import; every part is a named export below.
export default NxHero;

export { default as NxHero, type NxHeroEyebrow, type NxHeroProps } from '../internal/hero/hero.vue';
export { default as NxBackdrop, type NxBackdropProps, type NxBackdropVariant } from '../internal/hero/backdrop.vue';
</script>
