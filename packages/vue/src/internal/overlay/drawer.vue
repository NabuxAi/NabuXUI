<script lang="ts">
/**
 * NxDrawer — the Vue counterpart of ui-react's `Drawer`: the dialog fixed
 * to one edge. Everything but `variant` passes straight through.
 */
</script>

<script setup lang="ts">
import NxDialog from './dialog.vue';

defineOptions({ inheritAttrs: false });
</script>

<template>
  <NxDialog v-bind="$attrs" variant="drawer">
    <template v-if="$slots.trigger" #trigger><slot name="trigger" /></template>
    <template v-if="$slots.title" #title><slot name="title" /></template>
    <template v-if="$slots.description" #description><slot name="description" /></template>
    <template v-if="$slots.footer" #footer><slot name="footer" /></template>
    <slot />
  </NxDialog>
</template>
