<script setup lang="ts">
/**
 * One of NabuXUI's built-in icons, drawn from the same paths the React/Blade
 * components use (`icons` in ui-core). Decorative unless `label` is given.
 */
import { computed } from 'vue';
import { icons, type IconDefinition, type IconName } from '@nabuxai/ui-core';

const props = defineProps<{ name: IconName; label?: string }>();

const def = computed<IconDefinition>(() => icons[props.name]);
</script>

<template>
  <svg
    class="nx-icon"
    viewBox="0 0 24 24"
    :fill="def.filled ? 'currentColor' : 'none'"
    :stroke="def.filled ? 'none' : 'currentColor'"
    stroke-linecap="round"
    stroke-linejoin="round"
    :data-directional="def.directional ? '' : undefined"
    :role="label ? 'img' : undefined"
    :aria-label="label"
    :aria-hidden="label ? undefined : 'true'"
  >
    <path :d="def.d" />
  </svg>
</template>
