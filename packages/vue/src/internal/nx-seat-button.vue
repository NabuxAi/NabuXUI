<script setup lang="ts">
/**
 * The `nx-button` DOM the card family's flip and swipe controls render — the
 * icon-only pill button of ui-react's `Button` (secondary by default, status
 * marks always present), kept seat-local so card.vue stays self-contained.
 * Everything else (labels, aria, style, click) arrives through fallthrough.
 */
import type { IconName } from '@nabuxai/ui-core';
import NxIcon from './icon';

withDefaults(
  defineProps<{
    variant?: 'secondary' | 'primary';
    size?: 'sm' | 'lg';
    icon: IconName;
  }>(),
  { variant: 'secondary', size: 'sm' },
);
</script>

<template>
  <button type="button" class="nx-button" :data-variant="variant" :data-size="size" data-shape="pill" data-icon-only="">
    <span class="nx-button-label">
      <NxIcon :name="icon" />
    </span>
    <span class="nx-button-status" aria-hidden="true">
      <span class="nx-spinner" data-size="sm" />
      <svg class="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12.5l4.5 4.5L19 7.5" :pathLength="1" />
      </svg>
      <svg class="nx-icon nx-status-cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <path d="M7 7l10 10M17 7L7 17" :pathLength="1" />
      </svg>
    </span>
  </button>
</template>
