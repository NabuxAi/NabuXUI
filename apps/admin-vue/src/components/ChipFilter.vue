<script setup lang="ts">
/**
 * The chip filter — the Vue shape of the `chip-filter` block from
 * docs/FRAMEWORKS.md: a single-select chip row whose accent springs under the
 * checked chip (core `indicator`).
 */
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { type Cleanup, indicator } from '@nabuxai/ui-core';

export interface ChipItem {
  value: string;
  label: string;
  count?: number;
}

const props = withDefaults(defineProps<{ items: ChipItem[]; modelValue?: string; label?: string }>(), {
  modelValue: undefined,
  label: undefined,
});

const emit = defineEmits<{ 'update:modelValue': [value: string] }>();

const row = ref<HTMLElement | null>(null);
let ind: ReturnType<typeof indicator> | null = null;

const move = () => ind?.update(row.value?.querySelector('.nx-chip-filter-chip:has(:checked)') ?? null);

onMounted(() => {
  if (!row.value) return;
  ind = indicator(row.value);
  move();
});
onBeforeUnmount(() => ind?.destroy());

const pick = (item: ChipItem) => {
  emit('update:modelValue', item.value);
  requestAnimationFrame(move);
};
</script>

<template>
  <div class="nx-chip-filter" role="group" :aria-label="label">
    <div ref="row" class="nx-chip-filter-row">
      <span class="nx-indicator nx-chip-filter-thumb" aria-hidden="true" />
      <label v-for="item in items" :key="item.value" class="nx-chip-filter-chip">
        <input
          class="nx-chip-filter-input"
          type="radio"
          name="nx-chip-filter"
          :value="item.value"
          :checked="item.value === modelValue"
          @change="pick(item)"
        />
        <span>{{ item.label }}</span>
        <span v-if="item.count !== undefined" class="nx-chip-filter-count">{{ item.count }}</span>
      </label>
    </div>
  </div>
</template>
