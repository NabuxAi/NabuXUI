<script lang="ts">
  /**
   * Radio — همتای Svelte برای Radio در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import ChoiceShell from './choice-shell.svelte';
  import type { Snippet } from 'svelte';
  import type { HTMLInputAttributes } from 'svelte/elements';

  interface Props extends Omit<HTMLInputAttributes, 'type'> {
    label?: Snippet | string;
    description?: Snippet | string;
    invalid?: boolean;
    class?: string;
    style?: string;
  }

  let { label, description, invalid, class: className, style, ...rest }: Props = $props();
</script>

<ChoiceShell {label} {description} class={className} {style}>
  {#snippet control()}
    <!-- svelte-ignore a11y_role_supports_aria_props_implicit -->
    <!-- aria-invalid روی radio همان DOM ری‌اکت است. -->
    <input type="radio" class={cx('nx-radio', !label && !description && className)} aria-invalid={invalid || undefined} {...rest} />
  {/snippet}
</ChoiceShell>
