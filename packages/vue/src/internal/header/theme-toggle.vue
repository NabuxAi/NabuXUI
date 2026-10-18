<script lang="ts">
/**
 * NxThemeToggle — the Vue counterpart of ui-react's `ThemeToggle`: the
 * sun/moon button that flips the core theme, in step with the system and
 * other tabs while mounted.
 */
</script>

<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { theme } from '@nabuxai/ui-core';
import { useNxT } from '../kit';
import NxIcon from '../icon';

const root = ref<HTMLButtonElement | null>(null);
const t = useNxT(root);

const mounted = ref(false);
const dark = ref(false);
let stop: (() => void) | undefined;

onMounted(() => {
  mounted.value = true;
  dark.value = theme.resolved() === 'dark';
  stop = theme.watch((scheme) => {
    dark.value = scheme === 'dark';
  });
});

onBeforeUnmount(() => stop?.());

const flip = () => {
  dark.value = theme.toggle() === 'dark';
};
</script>

<template>
  <button
    ref="root"
    type="button"
    class="nx-button nx-theme-toggle"
    data-variant="ghost"
    data-icon-only
    :aria-pressed="mounted ? dark : undefined"
    :aria-label="t('darkMode')"
    :title="dark ? t('toLight') : t('toDark')"
    @click="flip"
  >
    <span class="nx-button-label">
      <span class="nx-theme-icons" aria-hidden="true">
        <NxIcon name="sun" class="nx-theme-sun" />
        <NxIcon name="moon" class="nx-theme-moon" />
      </span>
    </span>
  </button>
</template>
