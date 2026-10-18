<script lang="ts">
/**
 * Stand-in for the button seat's `NxButton` — the slice the pricing table
 * uses (block / variant / size / href / click / effect), with exactly the
 * DOM ui-react's Button renders for that slice: the label spans and the
 * status marks CSS switches between. Swap for the sibling seat's own
 * component once it has landed.
 */
export interface NxPrivButtonProps {
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger';
  size?: 'sm' | 'md' | 'lg';
  /** A sheen on hover. */
  effect?: 'shine' | 'slide';
  block?: boolean;
  /** Render as a link (a plain anchor; wire the router through NxSmartLink's injection). */
  href?: string;
}
</script>

<script setup lang="ts">
import { NxSmartLink } from '../kit';

defineProps<NxPrivButtonProps>();
defineEmits<{ click: [event: MouseEvent] }>();
</script>

<template>
  <NxSmartLink
    v-if="href"
    :href="href"
    class="nx-button"
    :data-variant="variant ?? 'secondary'"
    :data-size="size === 'md' || size === undefined ? undefined : size"
    :data-effect="effect"
    :data-block="block ? '' : undefined"
    @click="$emit('click', $event)"
  >
    <span class="nx-button-label">
      <span class="nx-button-text"><slot /></span>
    </span>
    <span class="nx-button-status" aria-hidden="true">
      <span class="nx-spinner" data-size="sm" />
      <svg class="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1" />
      </svg>
      <svg class="nx-icon nx-status-cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <path d="M7 7l10 10M17 7L7 17" pathLength="1" />
      </svg>
    </span>
  </NxSmartLink>
  <button
    v-else
    type="button"
    class="nx-button"
    :data-variant="variant ?? 'secondary'"
    :data-size="size === 'md' || size === undefined ? undefined : size"
    :data-effect="effect"
    :data-block="block ? '' : undefined"
    @click="$emit('click', $event)"
  >
    <span class="nx-button-label">
      <span class="nx-button-text"><slot /></span>
    </span>
    <span class="nx-button-status" aria-hidden="true">
      <span class="nx-spinner" data-size="sm" />
      <svg class="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1" />
      </svg>
      <svg class="nx-icon nx-status-cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <path d="M7 7l10 10M17 7L7 17" pathLength="1" />
      </svg>
    </span>
  </button>
</template>
