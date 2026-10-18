<script lang="ts">
  /**
   * Render a ReactNode-shaped content value — a plain string, or a Svelte
   * snippet — the Svelte seat's stand-in for ui-react's `ReactNode` props.
   * Adds no element of its own, so the DOM stays the React counterpart's.
   */
  import type { Snippet } from 'svelte';

  interface Props {
    of: string | Snippet;
  }

  let { of }: Props = $props();
</script>

{#if typeof of === 'string'}
  {of}
{:else}
  {@render of()}
{/if}
