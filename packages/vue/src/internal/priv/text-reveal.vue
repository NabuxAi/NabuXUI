<script lang="ts">
/**
 * Stand-in for the text seat's `NxTextReveal` — the slice the hero uses
 * (string title, word-by-word view reveal), with exactly the DOM ui-react's
 * TextReveal renders: the visually-hidden whole, then the direction-aware
 * pieces. Swap for the sibling seat's own component once it has landed.
 */
import type { SplitBy } from '@nabuxai/ui-core';

export interface NxPrivTextRevealProps {
  /** The text to reveal, already split by the core's rules. */
  text: string;
  /** The element to render as. */
  as?: string;
  by?: SplitBy;
  trigger?: 'view' | 'scroll';
  /** Delay before the first piece, ms. */
  delay?: number;
}
</script>

<script setup lang="ts">
import { computed } from 'vue';
import { reveal, splitText, textDirection } from '@nabuxai/ui-core';
import { useNxBehavior } from '../composable';

const props = withDefaults(defineProps<NxPrivTextRevealProps>(), { as: 'span', by: 'word', trigger: 'view' });

const seat = useNxBehavior<HTMLElement, { once: boolean }>(reveal, { once: true }, props.trigger === 'view');
const pieces = computed(() => splitText(props.text, props.by));
</script>

<template>
  <component
    :is="as"
    ref="seat"
    class="nx-text-reveal"
    :data-by="by"
    :data-trigger="trigger"
    :data-nx-reveal="trigger === 'view' ? '' : undefined"
    :style="delay ? { '--nx-delay': `${delay}ms` } : undefined"
  >
    <span class="nx-visually-hidden">{{ text }}</span>
    <!-- Pieces are boxes, laid out in their container's direction: give it the text's own. -->
    <span class="nx-text-pieces" aria-hidden="true" :dir="textDirection(text)">
      <span v-for="(piece, i) in pieces" :key="i" class="nx-text-piece" :style="{ '--nx-i': i }">{{ piece }}</span>
    </span>
  </component>
</template>
