<script lang="ts">
  /**
   * IconButton — همتای Svelte برای IconButton در ui-react/components/button.tsx.
   */
  import Button, { type ButtonVariant } from './button.svelte';
  import type { IconName } from '@nabuxai/ui-core';
  import type { Snippet } from 'svelte';

  interface Props {
    icon: IconName | Snippet;
    /** The accessible name — required, since there is no visible text. */
    label: string;
    variant?: ButtonVariant;
    [key: string]: unknown;
  }

  let { icon, label, variant = 'ghost', ...rest }: Props = $props();
</script>

<Button {variant} {icon} iconOnly aria-label={label} title={label} {...rest} />
