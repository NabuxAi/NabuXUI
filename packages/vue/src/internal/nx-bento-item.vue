<script setup lang="ts">
/**
 * NxBentoItem — the Vue counterpart of ui-react's `BentoItem`: one bento tile,
 * optionally a link, with media / title / description seats and the pointer
 * spotlight on by default.
 */
import { computed, ref, type ComponentPublicInstance, type CSSProperties } from 'vue';
import { spotlight as spotlightBehavior } from '@nabuxai/ui-core';
import { NxSmartLink } from './kit';
import { cx, useNxBehaviorOn } from './utils';

const props = withDefaults(
  defineProps<{
    colSpan?: number;
    rowSpan?: number;
    title?: string;
    description?: string;
    href?: string;
    spotlight?: boolean;
  }>(),
  { colSpan: 1, rowSpan: 1, spotlight: true },
);

// A dynamic `:is` between a tag and a component hands the ref either the
// element or the component instance — take the element either way.
const item = ref<HTMLElement | null>(null);
const setItem = (el: Element | ComponentPublicInstance | null) => {
  item.value = el instanceof HTMLElement ? el : ((el as ComponentPublicInstance | null)?.$el instanceof HTMLElement ? ((el as ComponentPublicInstance).$el as HTMLElement) : null);
};
useNxBehaviorOn(item, spotlightBehavior, undefined, () => props.spotlight);

const style = computed(() => ({ '--nx-col-span': props.colSpan, '--nx-row-span': props.rowSpan }) as CSSProperties);
</script>

<template>
  <component
    :is="href ? NxSmartLink : 'article'"
    :ref="setItem"
    :class="cx('nx-bento-item', spotlight && 'nx-spotlight')"
    v-bind="href ? { href } : undefined"
    :style="style"
  >
    <div v-if="$slots.media" class="nx-bento-media">
      <slot name="media" />
    </div>
    <h3 v-if="title || $slots.title" class="nx-bento-title">
      <slot name="title">{{ title }}</slot>
    </h3>
    <p v-if="description || $slots.description" class="nx-bento-description">
      <slot name="description">{{ description }}</slot>
    </p>
    <slot />
  </component>
</template>
