<script setup lang="ts">
/**
 * NxFlipCard — the Vue counterpart of ui-react's `FlipCard`: turn on hover
 * (and keyboard focus), or on click / the corner button (`v-model:flipped`).
 */
import { computed, useTemplateRef } from 'vue';
import NxSeatButton from './nx-seat-button.vue';
import { useControllableModel } from './kit';
import { useNxInert } from './utils';

const props = withDefaults(
  defineProps<{
    /** Turn on hover (and keyboard focus), or on click / the corner button. */
    trigger?: 'hover' | 'click';
    /** `v-model:flipped` — controlled when bound, internal otherwise. */
    flipped?: boolean;
    /** The face shown first when uncontrolled. */
    defaultFlipped?: boolean;
    /** Accessible name of the flip button. */
    flipLabel?: string;
  }>(),
  { trigger: 'hover', defaultFlipped: false, flipLabel: 'Flip card' },
);

const emit = defineEmits<{ flippedChange: [flipped: boolean] }>();

const flipped = defineModel<boolean | undefined>('flipped');
const seat = useControllableModel(flipped, () => props.defaultFlipped);
const on = computed(() => seat.current.value);

function setOn(next: boolean) {
  seat.set(next);
  emit('flippedChange', next);
}

const click = computed(() => props.trigger === 'click');

// The face turned away cannot be reached by keyboard or read out.
const front = useTemplateRef<HTMLDivElement>('front');
const back = useTemplateRef<HTMLDivElement>('back');
useNxInert(front, () => click.value && on.value);
useNxInert(back, () => click.value && !on.value);

function onRootClick(event: MouseEvent) {
  if (!click.value) return;
  if (!(event.target as Element).closest('a, button, input, select, textarea')) setOn(!on.value);
}
</script>

<template>
  <div class="nx-flip" :data-trigger="trigger" :data-flipped="click && on ? '' : undefined" @click="onRootClick">
    <div class="nx-flip-inner">
      <div ref="front" class="nx-flip-face nx-flip-front">
        <slot name="front" />
      </div>
      <div ref="back" class="nx-flip-face nx-flip-back">
        <slot name="back" />
      </div>
    </div>
    <NxSeatButton
      v-if="click"
      icon="sliders"
      :aria-pressed="on"
      :aria-label="flipLabel"
      :style="{ position: 'absolute', insetBlockStart: 'var(--nx-space-3)', insetInlineEnd: 'var(--nx-space-3)', zIndex: 2 }"
      @click.stop="setOn(!on)"
    />
  </div>
</template>
