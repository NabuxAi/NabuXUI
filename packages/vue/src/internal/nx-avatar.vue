<script setup lang="ts">
/**
 * NxAvatar — the Vue counterpart of ui-react's `Avatar`: a person's picture,
 * or their initials when the image is missing or fails to load.
 */
import { ref } from 'vue';

const props = defineProps<{
  name: string;
  src?: string;
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
  status?: 'online' | 'busy' | 'away' | 'offline';
  shape?: 'circle' | 'square';
}>();

const failed = ref(false);

const initials = () =>
  props.name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => Array.from(part)[0])
    .join('')
    .toLocaleUpperCase();
</script>

<template>
  <span
    class="nx-avatar"
    :data-size="size === 'md' ? undefined : size"
    :data-status="status"
    :data-shape="shape === 'square' ? 'square' : undefined"
    role="img"
    :aria-label="status ? `${name} (${status})` : name"
  >
    <img v-if="src && !failed" class="nx-avatar-image" :src="src" alt="" @error="failed = true" />
    <span v-else aria-hidden="true">{{ initials() }}</span>
  </span>
</template>
