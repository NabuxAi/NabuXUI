<script setup lang="ts">
/**
 * NxDotsLoader — the Vue counterpart of ui-react's `DotsLoader`: three
 * breathing dots.
 */
import { useTemplateRef } from 'vue';
import { useNxT } from './kit';

defineProps<{ label?: string }>();

const root = useTemplateRef<HTMLSpanElement>('root');
const t = useNxT(root);
</script>

<template>
  <span ref="root" class="nx-dots" role="status">
    <i />
    <i />
    <i />
    <span class="nx-visually-hidden">{{ label ?? t('loading') }}</span>
  </span>
</template>
