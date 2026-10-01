<script setup lang="ts">
/**
 * The empty state — the markup of the `empty-state` block: floating plate,
 * dashed orbit and soft halo, revealed once. The stub pages wrap this with
 * their own title/description and one honest way out.
 */
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { type IconName, reveal } from '@nabuxai/ui-core';
import NxIcon from './NxIcon.vue';

defineProps<{
  icon?: IconName;
  title: string;
  description?: string;
  action?: { label: string; href: string; icon?: IconName };
}>();

const root = ref<HTMLElement | null>(null);
let stop: (() => void) | null = null;

onMounted(() => {
  if (root.value) stop = reveal(root.value, { once: true });
});
onBeforeUnmount(() => stop?.());
</script>

<template>
  <div ref="root" class="nx-empty-state" data-nx-reveal="">
    <span class="nx-empty-state-art" aria-hidden="true">
      <span class="nx-empty-state-halo" />
      <span class="nx-empty-state-orbit">
        <i class="nx-empty-state-spark" />
        <i class="nx-empty-state-spark" />
      </span>
      <span class="nx-empty-state-plate">
        <NxIcon :name="icon ?? 'folder'" />
      </span>
    </span>
    <p class="nx-empty-state-title">{{ title }}</p>
    <p v-if="description" class="nx-empty-state-description">{{ description }}</p>
    <div v-if="action" class="nx-empty-state-actions">
      <a class="nx-button" data-variant="primary" :href="action.href">
        <span class="nx-button-label">
          <NxIcon v-if="action.icon" :name="action.icon" />
          <span class="nx-button-text">{{ action.label }}</span>
        </span>
      </a>
    </div>
  </div>
</template>
