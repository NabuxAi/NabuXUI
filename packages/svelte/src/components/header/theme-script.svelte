<script module lang="ts">
  export interface ThemeScriptProps {
    nonce?: string;
  }
</script>

<script lang="ts">
  import { themeScript } from '@nabuxai/ui-core';

  let { nonce }: ThemeScriptProps = $props();
</script>

<!-- Render this component anywhere; svelte:head carries the script into <head>
     so the page paints in the right theme from the first frame. -->
<svelte:head>
  <script {nonce}>{themeScript}</script>
</svelte:head>
