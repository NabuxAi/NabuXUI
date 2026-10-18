<script setup lang="ts">
/**
 * NxRating — the Vue counterpart of ui-react's `Rating`: native radios,
 * painted and previewed by CSS (`v-model` for the controlled form).
 */
import { useId, useTemplateRef } from 'vue';
import NxIcon from './icon';
import { useControllableModel, useNxT } from './kit';

const props = withDefaults(
  defineProps<{
    name?: string;
    /** `v-model` — the chosen star count. */
    value?: number;
    /** The star count when uncontrolled. */
    defaultValue?: number;
    max?: number;
    label?: string;
    disabled?: boolean;
    size?: string;
  }>(),
  { defaultValue: 0, max: 5 },
);

const root = useTemplateRef<HTMLFieldSetElement>('root');
const t = useNxT(root);

const value = defineModel<number | undefined>('value');
const seat = useControllableModel(value, () => props.defaultValue);
const group = props.name ?? `nx-rating${useId().replace(/[^a-zA-Z0-9-]/g, '')}`;
const stars = Array.from({ length: props.max }, (_, i) => i + 1);
</script>

<template>
  <fieldset ref="root" class="nx-rating" :disabled="disabled" :style="size ? { '--nx-rating-size': size } : undefined">
    <legend class="nx-visually-hidden">{{ label ?? t('rating') }}</legend>
    <label v-for="star in stars" :key="star" class="nx-rating-star">
      <input
        class="nx-rating-input"
        type="radio"
        :name="group"
        :value="star"
        :checked="seat.current.value === star"
        @change="seat.set(star)"
        :aria-label="t('stars', { count: star, total: max })"
      />
      <NxIcon name="star" />
    </label>
  </fieldset>
</template>
