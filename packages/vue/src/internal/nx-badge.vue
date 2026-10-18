<script setup lang="ts">
/**
 * NxBadge — the Vue counterpart of ui-react's `Badge`: a small status pill,
 * with an optional (pulsing) dot.
 */
withDefaults(
  defineProps<{
    tone?: 'neutral' | 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';
    variant?: 'soft' | 'solid' | 'outline';
    size?: 'md' | 'lg';
    /** A leading dot; `pulse` makes it breathe (for live states). */
    dot?: boolean;
    pulse?: boolean;
  }>(),
  { tone: 'neutral' },
);
</script>

<template>
  <span
    class="nx-badge"
    :data-tone="tone === 'neutral' ? undefined : tone"
    :data-variant="variant === 'soft' ? undefined : variant"
    :data-size="size === 'lg' ? 'lg' : undefined"
    :data-dot="dot || pulse ? '' : undefined"
    :data-pulse="pulse ? '' : undefined"
  >
    <slot />
  </span>
</template>
