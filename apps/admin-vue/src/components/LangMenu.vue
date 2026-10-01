<script setup lang="ts">
/**
 * The language menu — the markup of the `language-menu` block CSS contract in
 * ui-core (nx-language-*): the trigger rolls the two codes, the panel is a
 * native popover placed with core `place`, and picking one calls `setLang`, so
 * the whole panel — shell included — re-renders in the new language instantly.
 */
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { type Cleanup, place, roveFocus } from '@nabuxai/ui-core';
import { lang, setLang } from '../store';
import NxIcon from './NxIcon.vue';

const props = defineProps<{
  label: string;
  options: { id: 'fa' | 'en'; name: string; short: string }[];
}>();

const panelId = 'nx-admin-language';
const open = ref(false);
const trigger = ref<HTMLButtonElement | null>(null);
const panel = ref<HTMLElement | null>(null);
let unplace: Cleanup | null = null;

function onToggle(event: Event) {
  open.value = (event as ToggleEvent).newState === 'open';
  unplace?.();
  unplace = null;
  if (open.value && trigger.value && panel.value) {
    unplace = place(trigger.value, panel.value, { side: 'bottom', align: 'end', offset: 8 });
  }
}

onMounted(() => panel.value?.addEventListener('toggle', onToggle));
onBeforeUnmount(() => {
  panel.value?.removeEventListener('toggle', onToggle);
  unplace?.();
});

// An exit is softer and faster than the enter: fold away quickly.
const pick = (id: 'fa' | 'en') => {
  setLang(id);
  window.setTimeout(() => panel.value?.matches(':popover-open') && panel.value?.hidePopover(), 150);
};

const onKey = (event: KeyboardEvent) => {
  if (panel.value) roveFocus(event, panel.value, '.nx-language-choice', { orientation: 'vertical' });
};
</script>

<template>
  <button
    ref="trigger"
    type="button"
    class="nx-language-trigger"
    aria-haspopup="listbox"
    :aria-expanded="open"
    :aria-controls="panelId"
    :popovertarget="panelId"
    :aria-label="label"
  >
    <span class="nx-language-globe"><NxIcon name="globe" /></span>
    <span class="nx-language-codes" aria-hidden="true">
      <span
        v-for="option in options"
        :key="option.id"
        class="nx-language-code"
        :data-current="option.id === lang ? '' : undefined"
      >{{ option.short }}</span>
    </span>
    <span class="nx-language-chevron"><NxIcon name="chevron-down" /></span>
  </button>
  <div :id="panelId" ref="panel" class="nx-language" popover="auto" role="listbox" :aria-label="label" @keydown="onKey">
    <ul class="nx-language-list">
      <li v-for="option in options" :key="option.id" class="nx-language-row" :data-selected="option.id === lang ? '' : undefined">
        <button type="button" class="nx-language-choice" role="option" :aria-selected="option.id === lang" @click="pick(option.id)">
          <span>{{ option.name }}</span>
          <span class="nx-language-short">{{ option.short }}</span>
          <span class="nx-language-mark"><NxIcon name="check" /></span>
        </button>
      </li>
    </ul>
  </div>
</template>
