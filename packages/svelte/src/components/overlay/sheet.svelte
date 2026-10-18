<script lang="ts">
  /** A Dialog rolled up from the inline-end edge — the React seat's Sheet (variant="sheet"). */
  import Dialog from './dialog.svelte';
  import type { DialogProps } from './dialog.svelte';

  let props: Omit<DialogProps, 'variant' | 'side'> = $props();
</script>

<Dialog variant="sheet" {...props} />
