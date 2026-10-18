<script lang="ts">
/**
 * NxCommandPalette — the Vue counterpart of ui-react's `CommandPalette`:
 * a searchable list of commands over a dialog, with roving selection, a
 * spring indicator, hidden links for href items and a hotkey to open it.
 */
import type { IconName } from '@nabuxai/ui-core';
import type { VueNode } from '../utils';

export interface NxCommandItem {
  id: string;
  label: string;
  icon?: IconName;
  hint?: string;
  shortcut?: string;
  keywords?: string[];
  href?: string;
  onSelect?: () => void;
  disabled?: boolean;
}

export interface NxCommandGroup {
  label: string;
  items: NxCommandItem[];
}

export interface NxCommandPaletteProps {
  groups: NxCommandGroup[];
  defaultOpen?: boolean;
  /** Keyboard shortcut that toggles the palette; null for none. */
  hotkey?: string | null;
  placeholder?: string;
  emptyText?: VueNode;
}
</script>

<script setup lang="ts">
import { computed, ref, useId, watch, watchEffect } from 'vue';
import { hotkey, modKeyLabel } from '@nabuxai/ui-core';
import { NxNode, NxSmartLink, useControllableModel, useNxIndicator, useNxT } from '../kit';
import NxIcon from '../icon';
import { NxKbd } from '../display';
import NxDialog from './dialog.vue';

const props = withDefaults(defineProps<NxCommandPaletteProps>(), { defaultOpen: false, hotkey: 'mod+k' });

const open = defineModel<boolean | undefined>('open');
const { current: isOpen, set } = useControllableModel(open, () => props.defaultOpen);

const query = ref('');
const active = ref(0);
const base = `nx-cmd${useId().replace(/[:-]/g, '')}`;
const list = ref<HTMLDivElement | null>(null);
const input = ref<HTMLInputElement | null>(null);
const ind = useNxIndicator(list);
const t = useNxT(list);

// Re-bound whenever the combo or the open state changes, like the React effect's deps.
watchEffect((onCleanup) => {
  if (!props.hotkey) return;
  void isOpen.value;
  onCleanup(hotkey(props.hotkey, () => set(!isOpen.value)));
});

/** Fold the variants of a Persian or Arabic letter together so either spelling matches. */
const normalise = (text: string) =>
  text
    .toLocaleLowerCase()
    .replace(/[يى]/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[ً-ٰٟ‌]/g, '')
    .normalize('NFKC');

const filtered = computed(() => {
  const q = normalise(query.value.trim());
  return props.groups
    .map((group) => ({
      ...group,
      items: group.items.filter((item) => !q || normalise([item.label, item.hint, ...(item.keywords ?? [])].join(' ')).includes(q)),
    }))
    .filter((group) => group.items.length > 0);
});

const flat = computed(() => filtered.value.flatMap((group) => group.items));

/** Groups with each item's flat index attached, so the list and the combobox agree. */
const indexed = computed(() => {
  let index = -1;
  return filtered.value.map((group) => ({
    ...group,
    items: group.items.map((item) => {
      index += 1;
      return { item, i: index };
    }),
  }));
});

watch(isOpen, (openNow) => {
  if (!openNow) return;
  query.value = '';
  active.value = 0;
  requestAnimationFrame(() => input.value?.focus());
});

watch(query, () => {
  active.value = 0;
});

// The indicator springs to the active option, which scrolls into view.
watchEffect(
  () => {
    void active.value;
    void indexed.value;
    if (!list.value || !ind.value) return;
    const el = list.value.querySelector<HTMLElement>(`#${base}-opt-${active.value}`);
    ind.value.update(el);
    el?.scrollIntoView({ block: 'nearest' });
  },
  { flush: 'post' },
);

const choose = (item: NxCommandItem | undefined) => {
  if (!item || item.disabled) return;
  set(false);
  if (item.href) {
    list.value?.querySelector<HTMLAnchorElement>(`[data-command-link="${CSS.escape(item.id)}"]`)?.click();
  }
  item.onSelect?.();
};

const onKeydown = (event: KeyboardEvent) => {
  if (event.key === 'ArrowDown') {
    event.preventDefault();
    active.value = flat.value.length ? (active.value + 1) % flat.value.length : 0;
  } else if (event.key === 'ArrowUp') {
    event.preventDefault();
    active.value = flat.value.length ? (active.value - 1 + flat.value.length) % flat.value.length : 0;
  } else if (event.key === 'Enter') {
    event.preventDefault();
    choose(flat.value[active.value]);
  }
};

const comboLabel = computed(() => (props.hotkey ? props.hotkey.replace('mod', modKeyLabel()).replace('+', ' ').toUpperCase() : ''));
</script>

<template>
  <NxDialog :open="isOpen" hide-close class="nx-command" @update:open="(value) => value !== undefined && set(value)">
    <div class="nx-command-search">
      <NxIcon name="search" />
      <input
        ref="input"
        v-model="query"
        class="nx-command-input"
        role="combobox"
        aria-expanded="true"
        :aria-controls="`${base}-list`"
        :aria-activedescendant="flat.length ? `${base}-opt-${active}` : undefined"
        aria-autocomplete="list"
        :aria-label="t('search')"
        :placeholder="placeholder ?? t('searchPlaceholder')"
        @keydown="onKeydown"
      />
      <NxKbd>Esc</NxKbd>
    </div>
    <div :id="`${base}-list`" ref="list" class="nx-command-list" role="listbox" :aria-label="t('search')">
      <span class="nx-indicator" aria-hidden="true" />
      <p v-if="filtered.length === 0" class="nx-command-empty">
        <NxNode :node="emptyText ?? t('noResults')" />
      </p>
      <div v-for="(group, g) in indexed" :key="group.label" class="nx-command-group" role="group" :aria-labelledby="`${base}-group-${g}`">
        <div class="nx-command-group-label" :id="`${base}-group-${g}`">{{ group.label }}</div>
        <div
          v-for="{ item, i } in group.items"
          :key="item.id"
          :id="`${base}-opt-${i}`"
          class="nx-command-item"
          role="option"
          :aria-selected="i === active"
          :aria-disabled="item.disabled || undefined"
          :style="{ '--nx-i': i }"
          @pointermove="i !== active && (active = i)"
          @click="choose(item)"
        >
          <NxIcon v-if="item.icon" :name="item.icon" />
          <span>{{ item.label }}</span>
          <span v-if="item.hint" class="nx-command-item-hint">{{ item.hint }}</span>
          <NxKbd v-if="item.shortcut">{{ item.shortcut }}</NxKbd>
          <NxSmartLink v-if="item.href" :href="item.href" :data-command-link="item.id" :tabindex="-1" hidden aria-hidden="true" />
        </div>
      </div>
    </div>
    <footer class="nx-command-footer">
      <span>
        <NxKbd>↑</NxKbd>
        <NxKbd>↓</NxKbd>
        {{ t('navigate') }}
      </span>
      <span>
        <NxKbd>↵</NxKbd>
        {{ t('select') }}
      </span>
      <span>
        <NxKbd>Esc</NxKbd>
        {{ t('toClose') }}
      </span>
      <span v-if="props.hotkey" style="margin-inline-start: auto">
        <NxKbd>{{ comboLabel }}</NxKbd>
      </span>
    </footer>
  </NxDialog>
</template>
