<script setup lang="ts">
/**
 * NxStackCards — the Vue counterpart of ui-react's `StackCards`: overlapping
 * cards that fan out on hover. Items arrive as the `items` prop or as default
 * slot children, one per card.
 */
import { Comment, computed, useSlots, type CSSProperties, type VNode } from 'vue';
import { NxNode } from './kit';

const props = defineProps<{
  items?: Array<string | number | VNode>;
  /** How far each card overlaps the previous one (a negative margin, e.g. "-55%"). */
  overlap?: string;
  ariaLabel?: string;
}>();

const slots = useSlots();
const seatItems = computed<Array<string | number | VNode>>(() => {
  if (props.items) return props.items;
  return (slots.default?.() ?? []).filter((vnode): vnode is VNode => vnode.type !== Comment);
});

const style = computed(() => ({
  '--nx-count': seatItems.value.length,
  ...(props.overlap ? { '--nx-stack-overlap': props.overlap } : null),
}) as CSSProperties);
</script>

<template>
  <ul class="nx-stack" :aria-label="ariaLabel" :style="style">
    <li v-for="(item, i) in seatItems" :key="i" class="nx-stack-item" :style="{ '--i': i }">
      <NxNode :node="item" />
    </li>
  </ul>
</template>
