<script lang="ts">
/**
 * NxSegmentedControl — the Vue counterpart of ui-react's
 * `SegmentedControl`: native radios painted as one control, with the
 * spring indicator following the checked segment.
 */
import type { IconName } from '@nabuxai/ui-core';
import type { VueNode } from '../utils';

export interface NxSegmentOption {
  value: string;
  label: VueNode;
  icon?: IconName;
  disabled?: boolean;
}

export interface NxSegmentedControlProps {
  options: NxSegmentOption[];
  defaultValue?: string;
  /** Radio group name; generated when omitted. */
  name?: string;
  size?: 'sm' | 'md';
  tone?: 'neutral' | 'accent';
  ariaLabel?: string;
}
</script>

<script setup lang="ts">
import { onMounted, ref, useId, watch } from 'vue';
import { NxNode, useControllableModel, useNxIndicator } from '../kit';
import NxIcon from '../icon';

const props = defineProps<NxSegmentedControlProps>();
const value = defineModel<string | undefined>('value');

const group = props.name ?? `nx-seg${useId().replace(/[:-]/g, '')}`;
const { current, set } = useControllableModel(value, () => props.defaultValue ?? props.options[0]?.value ?? '');

const root = ref<HTMLDivElement | null>(null);
const ind = useNxIndicator(root);

onMounted(() => ind.value?.update(root.value?.querySelector('.nx-segment:has(:checked)') ?? null));
watch([current, () => props.options], () => ind.value?.update(root.value?.querySelector('.nx-segment:has(:checked)') ?? null), { flush: 'post', deep: true });
</script>

<template>
  <div
    ref="root"
    class="nx-segmented"
    role="radiogroup"
    :aria-label="ariaLabel"
    :data-size="size === 'sm' ? 'sm' : undefined"
    :data-tone="tone === 'accent' ? 'accent' : undefined"
  >
    <span class="nx-indicator" aria-hidden="true" />
    <label v-for="option in options" :key="option.value" class="nx-segment">
      <input
        class="nx-segment-input"
        type="radio"
        :name="group"
        :value="option.value"
        :checked="current === option.value"
        :disabled="option.disabled"
        @change="set(option.value)"
      />
      <NxIcon v-if="option.icon" :name="option.icon" />
      <NxNode :node="option.label" />
    </label>
  </div>
</template>
