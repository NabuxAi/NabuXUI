<script lang="ts">
  /** A Dialog pinned to one edge — the React seat's Drawer (variant="drawer"). */
  import Dialog from './dialog.svelte';
  import type { DialogProps } from './dialog.svelte';

  let props: Omit<DialogProps, 'variant'> = $props();
</script>

<Dialog variant="drawer" {...props} />
