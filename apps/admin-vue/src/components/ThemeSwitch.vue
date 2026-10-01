<script setup lang="ts">
/**
 * The three-state theme switch — the markup of the `theme-switch` block CSS
 * contract in ui-core (nx-theme-switch): light / system / dark radios with the
 * spring thumb (core `indicator`) between them, writing the shared theme store.
 */
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { type Cleanup, type ThemePreference, indicator, theme } from '@nabuxai/ui-core';
import { s } from '../store';
import NxIcon from './NxIcon.vue';

const props = defineProps<{ label: string }>();

const root = ref<HTMLElement | null>(null);
const value = ref<ThemePreference>('system');
let ind: ReturnType<typeof indicator> | null = null;
let stop: (() => void) | null = null;

const move = () => {
  ind?.update(root.value?.querySelector(`.nx-theme-switch-option:has(:checked)`) ?? null);
};

onMounted(() => {
  value.value = theme.preference();
  stop = theme.watch(() => {
    value.value = theme.preference();
    void nextTick(move);
  });
  if (!root.value) return;
  ind = indicator(root.value);
  void nextTick(move);
});

onBeforeUnmount(() => {
  ind?.destroy();
  stop?.();
});

watch(value, () => void nextTick(move));

const choose = (next: ThemePreference) => {
  value.value = next;
  theme.set(next);
};

const options = () => [
  { id: 'light' as const, label: s.value.pages.settings.themeLight },
  { id: 'system' as const, label: s.value.pages.settings.themeSystem },
  { id: 'dark' as const, label: s.value.pages.settings.themeDark },
];
</script>

<template>
  <div ref="root" class="nx-theme-switch" role="radiogroup" :aria-label="label" data-nx-indicator="ready">
    <span class="nx-indicator" aria-hidden="true" />
    <template v-for="option in options()" :key="option.id">
      <label v-if="option.id === 'system'" class="nx-theme-switch-option" :title="option.label">
        <input
          class="nx-theme-switch-input"
          type="radio"
          name="nx-theme-switch"
          value="system"
          :checked="value === 'system'"
          @change="choose('system')"
        />
        <span class="nx-theme-switch-half" aria-hidden="true" />
        <span class="nx-visually-hidden">{{ option.label }}</span>
      </label>
      <label v-else class="nx-theme-switch-option" :title="option.label">
        <input
          class="nx-theme-switch-input"
          type="radio"
          name="nx-theme-switch"
          :value="option.id"
          :checked="value === option.id"
          @change="choose(option.id)"
        />
        <NxIcon :name="option.id === 'light' ? 'sun' : 'moon'" />
        <span class="nx-visually-hidden">{{ option.label }}</span>
      </label>
    </template>
  </div>
</template>
