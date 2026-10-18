<script lang="ts">
/**
 * NxTabs — the Vue counterpart of ui-react's `Tabs`: roving tablist with a
 * spring indicator, automatic activation and native panel wiring.
 */
import type { IconName } from '@nabuxai/ui-core';
import type { VueNode } from '../utils';

export interface NxTabItem {
  value: string;
  label: VueNode;
  content: VueNode;
  badge?: VueNode;
  icon?: IconName;
  disabled?: boolean;
}

export interface NxTabsProps {
  items: NxTabItem[];
  defaultValue?: string;
  /** pill | underline | enclosed. */
  variant?: 'pill' | 'underline' | 'enclosed';
  ariaLabel?: string;
}
</script>

<script setup lang="ts">
import { onMounted, ref, useId, watch } from 'vue';
import { roveFocus } from '@nabuxai/ui-core';
import { NxNode, useControllableModel, useNxIndicator } from '../kit';
import NxIcon from '../icon';

const props = defineProps<NxTabsProps>();
const value = defineModel<string | undefined>('value');

const base = `nx-tabs${useId().replace(/[:-]/g, '')}`;
const { current, set } = useControllableModel(value, () => props.defaultValue ?? props.items[0]?.value ?? '');

const list = ref<HTMLDivElement | null>(null);
const ind = useNxIndicator(list);

const idSafe = (v: string) => v.replace(/[^\w-]/g, '_');

// The pill springs to the selected tab once the list has mounted, and again on every change.
onMounted(() => ind.value?.update(list.value?.querySelector(`[data-value="${CSS.escape(current.value)}"]`) ?? null));
watch([current, () => props.items], () => ind.value?.update(list.value?.querySelector(`[data-value="${CSS.escape(current.value)}"]`) ?? null), { flush: 'post', deep: true });

const onKeydown = (event: KeyboardEvent) => {
  const next = roveFocus(event, event.currentTarget as Element, '[role="tab"]');
  // Automatic activation: moving focus selects the tab.
  if (next?.dataset.value) set(next.dataset.value);
};
</script>

<template>
  <div class="nx-tabs" :data-variant="variant === 'pill' || variant === undefined ? undefined : variant">
    <div ref="list" class="nx-tab-list" role="tablist" :aria-label="ariaLabel" @keydown="onKeydown">
      <span class="nx-indicator" aria-hidden="true" />
      <button
        v-for="item in items"
        :key="item.value"
        type="button"
        role="tab"
        class="nx-tab"
        :id="`${base}-tab-${idSafe(item.value)}`"
        :data-value="item.value"
        :aria-selected="item.value === current"
        :aria-controls="`${base}-panel-${idSafe(item.value)}`"
        :tabindex="item.value === current ? 0 : -1"
        :disabled="item.disabled"
        @click="set(item.value)"
      >
        <NxIcon v-if="item.icon" :name="item.icon" />
        <NxNode :node="item.label" />
        <NxNode v-if="item.badge" :node="item.badge" />
      </button>
    </div>
    <div
      v-for="item in items"
      :key="item.value"
      class="nx-tab-panel"
      role="tabpanel"
      :id="`${base}-panel-${idSafe(item.value)}`"
      :aria-labelledby="`${base}-tab-${idSafe(item.value)}`"
      tabindex="0"
      :hidden="item.value !== current"
    >
      <NxNode :node="item.content" />
    </div>
  </div>
</template>
