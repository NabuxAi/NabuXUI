<script module lang="ts">
  export interface RouteProgressProps {
    /** loading while a page is on its way, done when it arrived, idle otherwise. */
    state: 'idle' | 'loading' | 'done';
    /** 0–1 when the real progress is known (uploads); a trickle otherwise. */
    value?: number;
  }
</script>

<script lang="ts">
  let { state, value }: RouteProgressProps = $props();
</script>

<div
  class="nx-route-progress"
  data-state={state}
  data-determinate={value !== undefined ? '' : undefined}
  aria-hidden="true"
  style={value !== undefined ? `--nx-value: ${value}` : undefined}
></div>
