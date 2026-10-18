<script lang="ts">
/**
 * NxAccordion — the Vue counterpart of ui-react's `Accordion`: native
 * exclusive `<details name>` groups, joined or separated.
 */
import type { VueNode } from '../utils';

export interface NxAccordionItem {
  id?: string;
  title: VueNode;
  content: VueNode;
}

export interface NxAccordionProps {
  items: NxAccordionItem[];
  /** Only one open at a time (native exclusive <details name>). */
  single?: boolean;
  /** Ids (or indexes) open at first. */
  defaultOpen?: Array<string | number>;
  variant?: 'joined' | 'separated';
}
</script>

<script setup lang="ts">
import { useId } from 'vue';
import { NxNode } from '../kit';

const props = withDefaults(defineProps<NxAccordionProps>(), { single: true, defaultOpen: () => [], variant: 'joined' });

const group = `nx-acc${useId().replace(/[:-]/g, '')}`;

const keyOf = (item: NxAccordionItem, i: number) => item.id ?? i;
const openAt = (item: NxAccordionItem, i: number) => props.defaultOpen.includes(keyOf(item, i));
</script>

<template>
  <div class="nx-accordion" :data-variant="variant === 'separated' ? 'separated' : undefined">
    <details
      v-for="(item, i) in items"
      :key="keyOf(item, i)"
      class="nx-accordion-item"
      :name="single ? group : undefined"
      :open="openAt(item, i) || undefined"
    >
      <summary class="nx-accordion-trigger"><NxNode :node="item.title" /></summary>
      <div class="nx-accordion-content"><NxNode :node="item.content" /></div>
    </details>
  </div>
</template>
