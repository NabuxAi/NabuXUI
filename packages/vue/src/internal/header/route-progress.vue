<script lang="ts">
/**
 * NxRouteProgress — the Vue counterpart of ui-react's `RouteProgress`:
 * the thin bar at the top of the page, loading, done or idle.
 */
export interface NxRouteProgressProps {
  /** loading while a page is on its way, done when it arrived, idle otherwise. */
  state: 'idle' | 'loading' | 'done';
  /** 0–1 when the real progress is known (uploads); a trickle otherwise. */
  value?: number;
}
</script>

<script setup lang="ts">
defineProps<NxRouteProgressProps>();
</script>

<template>
  <div
    class="nx-route-progress"
    :data-state="state"
    :data-determinate="value !== undefined ? '' : undefined"
    aria-hidden="true"
    :style="value !== undefined ? { '--nx-value': value } : undefined"
  />
</template>
