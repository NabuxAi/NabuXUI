<script setup lang="ts">
/**
 * NxCard — the Vue counterpart of ui-react's `Card`: an article (or a link,
 * routed through the injected link component for internal paths) with header,
 * body and footer seats, optional spotlight and tilt behaviours.
 */
import { ref, type ComponentPublicInstance } from 'vue';
import type { IconName } from '@nabuxai/ui-core';
import { spotlight as spotlightBehavior, tilt as tiltBehavior } from '@nabuxai/ui-core';
import NxIcon from './icon';
import { NxSmartLink } from './kit';
import { useNxBehaviorOn } from './utils';

const props = withDefaults(
  defineProps<{
    variant?: 'default' | 'glass' | 'outline' | 'gradient' | 'inverse';
    size?: 'sm' | 'md' | 'lg';
    /** Lifts on hover (links and buttons always do). */
    interactive?: boolean;
    /** A light and a lit edge that follow the pointer. */
    spotlight?: boolean;
    /** Tilts in 3D toward the pointer. */
    tilt?: boolean;
    href?: string;
    icon?: IconName;
    title?: string;
    description?: string;
    /** Heading level of the title (h3 by default). */
    titleAs?: 'h2' | 'h3' | 'h4';
  }>(),
  { titleAs: 'h3' },
);

// A dynamic `:is` between a tag and a component hands the ref either the
// element or the component instance — take the element either way.
const card = ref<HTMLElement | null>(null);
const setCard = (el: Element | ComponentPublicInstance | null) => {
  card.value = el instanceof HTMLElement ? el : ((el as ComponentPublicInstance | null)?.$el instanceof HTMLElement ? ((el as ComponentPublicInstance).$el as HTMLElement) : null);
};
useNxBehaviorOn(card, spotlightBehavior, undefined, () => !!props.spotlight);
useNxBehaviorOn(card, tiltBehavior, {}, () => !!props.tilt);
</script>

<template>
  <component
    :is="href ? NxSmartLink : 'article'"
    :ref="setCard"
    class="nx-card"
    v-bind="href ? { href } : undefined"
    :data-variant="variant && variant !== 'default' ? variant : undefined"
    :data-size="size && size !== 'md' ? size : undefined"
    :data-interactive="interactive ? '' : undefined"
    :data-spotlight="spotlight ? '' : undefined"
  >
    <div v-if="icon || title || description || $slots.icon || $slots.title || $slots.description" class="nx-card-header">
      <span v-if="icon || $slots.icon" class="nx-card-icon">
        <slot name="icon"><NxIcon v-if="icon" :name="icon" /></slot>
      </span>
      <component :is="titleAs" v-if="title || $slots.title" class="nx-card-title">
        <slot name="title">{{ title }}</slot>
      </component>
      <p v-if="description || $slots.description" class="nx-card-description">
        <slot name="description">{{ description }}</slot>
      </p>
    </div>
    <div v-if="$slots.default" class="nx-card-body">
      <slot />
    </div>
    <div v-if="$slots.footer" class="nx-card-footer">
      <slot name="footer" />
    </div>
  </component>
</template>
