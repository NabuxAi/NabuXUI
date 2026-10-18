<script setup lang="ts">
/**
 * NxAvatarGroup — the Vue counterpart of ui-react's `AvatarGroup`: a row of
 * overlapping avatars, each named by a tooltip, with the overflow folded
 * into a "+N" seat.
 */
import { computed, useTemplateRef } from 'vue';
import NxAvatar from './nx-avatar.vue';
import { NxTooltip } from './nx-tooltip';
import { useNxT } from './kit';

const props = withDefaults(
  defineProps<{
    people: Array<{ name: string; src?: string }>;
    /** Show this many; the rest become "+N". */
    max?: number;
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
    label?: string;
  }>(),
  { max: 5 },
);

const root = useTemplateRef<HTMLDivElement>('root');
const t = useNxT(root);

const shown = computed(() => props.people.slice(0, props.max));
const rest = computed(() => props.people.length - shown.value.length);
</script>

<template>
  <div ref="root" class="nx-avatar-group" role="group" :aria-label="label">
    <NxTooltip v-for="person in shown" :key="person.name" :content="person.name">
      <NxAvatar :name="person.name" :src="person.src" :size="size" :tab-index="0" />
    </NxTooltip>
    <span
      v-if="rest > 0"
      class="nx-avatar nx-avatar-more"
      :data-size="size === 'md' ? undefined : size"
      role="img"
      :aria-label="t('more', { count: rest })"
    >
      <span aria-hidden="true">+{{ rest }}</span>
    </span>
  </div>
</template>
