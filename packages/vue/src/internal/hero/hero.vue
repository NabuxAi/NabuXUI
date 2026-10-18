<script lang="ts">
/**
 * NxHero — the Vue counterpart of ui-react's `Hero`: the page-top banner
 * with its decorative backdrop, staggered content and tipping media.
 */
import type { CSSProperties } from 'vue';
import type { VueNode } from '../utils';
import type { NxBackdropVariant } from './backdrop.vue';

export interface NxHeroEyebrow {
  label: VueNode;
  badge?: VueNode;
  href?: string;
}

export interface NxHeroProps {
  /** A backdrop layer, or none at all. */
  backdrop?: NxBackdropVariant | false;
  /** The small pill above the title: text, an optional badge, an optional link. */
  eyebrow?: NxHeroEyebrow;
  /** A string gets the word-by-word reveal; any node is rendered as is. */
  title?: VueNode;
  subtitle?: VueNode;
  /** The row of buttons under the subtitle; the `actions` slot renders instead when given. */
  actions?: VueNode;
  /** A product shot or illustration that tips into place under the text. */
  media?: VueNode;
  align?: 'center' | 'start';
  style?: CSSProperties;
}
</script>

<script setup lang="ts">
import { useSlots } from 'vue';
import { reveal, spotlight } from '@nabuxai/ui-core';
import { NxNode, NxSmartLink } from '../kit';
import { useNxBehavior } from '../composable';
import { useNxBehaviorReactive } from '../utils';
import NxIcon from '../icon';
import { NxBadge } from '../feedback';
import NxBackdrop from './backdrop.vue';
import NxTextReveal from '../priv/text-reveal.vue';

const props = withDefaults(defineProps<NxHeroProps>(), { backdrop: 'aurora', align: 'center' });
const slots = useSlots();

// The grid backdrop carries a pointer spotlight; the others stay still.
const section = useNxBehaviorReactive<HTMLElement, undefined>(spotlight, undefined, () => props.backdrop === 'grid');
// The content staggers in once; the media tips in a beat later, when there is one.
const content = useNxBehavior<HTMLElement, { stagger: boolean; once: boolean }>(reveal, { stagger: true, once: true });
const hasMedia = () => props.media != null || !!slots.media;
const mediaSeat = useNxBehaviorReactive<HTMLElement, { once: boolean; threshold: number }>(reveal, { once: true, threshold: 0.05 }, hasMedia);
</script>

<template>
  <section ref="section" class="nx-hero" :data-align="align === 'start' ? 'start' : undefined" :style="style">
    <NxBackdrop v-if="backdrop" :variant="backdrop" />
    <div ref="content" class="nx-hero-content" data-nx-reveal="group">
      <template v-if="eyebrow">
        <NxSmartLink v-if="eyebrow.href" class="nx-hero-eyebrow" :href="eyebrow.href">
          <NxBadge v-if="eyebrow.badge" tone="accent" variant="solid"><NxNode :node="eyebrow.badge" /></NxBadge>
          <span><NxNode :node="eyebrow.label" /></span>
          <NxIcon name="arrow-right" />
        </NxSmartLink>
        <p v-else class="nx-hero-eyebrow">
          <NxBadge v-if="eyebrow.badge" tone="accent" variant="solid"><NxNode :node="eyebrow.badge" /></NxBadge>
          <span><NxNode :node="eyebrow.label" /></span>
          <NxIcon v-if="eyebrow.href" name="arrow-right" />
        </p>
      </template>
      <NxTextReveal v-if="typeof title === 'string'" as="h1" class="nx-hero-title" :delay="120" :text="title" />
      <h1 v-else-if="title != null" class="nx-hero-title"><NxNode :node="title" /></h1>
      <p v-if="subtitle" class="nx-hero-subtitle"><NxNode :node="subtitle" /></p>
      <div v-if="$slots.actions || actions" class="nx-hero-actions">
        <slot name="actions"><NxNode :node="actions" /></slot>
      </div>
      <slot />
    </div>
    <div v-if="hasMedia()" ref="mediaSeat" class="nx-hero-media" data-nx-reveal>
      <div>
        <slot name="media"><NxNode :node="media" /></slot>
      </div>
    </div>
  </section>
</template>
