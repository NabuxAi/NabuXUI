<script lang="ts">
/**
 * NxBackdrop — the Vue counterpart of ui-react's hero `Backdrop`: a
 * decorative, pure-CSS layer. Place it first inside a positioned section.
 */
export type NxBackdropVariant = 'aurora' | 'grid' | 'stars' | 'beams' | 'dots' | 'mesh' | 'stripes';

export interface NxBackdropProps {
  variant?: NxBackdropVariant;
}
</script>

<script setup lang="ts">
withDefaults(defineProps<NxBackdropProps>(), { variant: 'aurora' });
</script>

<template>
  <div class="nx-backdrop" :data-variant="variant" aria-hidden="true">
    <i />
    <i />
    <i />
  </div>
</template>
