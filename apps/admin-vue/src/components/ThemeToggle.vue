<script setup lang="ts">
/**
 * The header theme toggle — the markup of React's `ThemeToggle`: both glyphs
 * live in the button and CSS swaps them; the click writes the shared core theme
 * store (`nabu.theme`), so every flavour of the panel agrees.
 */
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { theme, translate } from '@nabuxai/ui-core';
import { lang } from '../store';
import NxIcon from './NxIcon.vue';

const dark = ref(false);
let stop: (() => void) | null = null;

onMounted(() => {
  dark.value = theme.resolved() === 'dark';
  stop = theme.watch((scheme) => (dark.value = scheme === 'dark'));
});
onBeforeUnmount(() => stop?.());

const toggle = () => {
  dark.value = theme.toggle() === 'dark';
};
</script>

<template>
  <button
    type="button"
    class="nx-button nx-theme-toggle"
    data-variant="ghost"
    data-icon-only=""
    :aria-pressed="dark"
    :aria-label="translate(lang, 'darkMode')"
    :title="dark ? translate(lang, 'toLight') : translate(lang, 'toDark')"
    @click="toggle"
  >
    <span class="nx-button-label">
      <span class="nx-theme-icons" aria-hidden="true">
        <NxIcon name="sun" class="nx-theme-sun" />
        <NxIcon name="moon" class="nx-theme-moon" />
      </span>
    </span>
  </button>
</template>
