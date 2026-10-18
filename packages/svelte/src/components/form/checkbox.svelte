<script lang="ts">
  /**
   * Checkbox — همتای Svelte برای Checkbox در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import { fieldProps, useField } from '../../internal/field';
  import ChoiceShell from './choice-shell.svelte';
  import type { Snippet } from 'svelte';
  import type { HTMLInputAttributes } from 'svelte/elements';

  interface Props extends Omit<HTMLInputAttributes, 'type'> {
    label?: Snippet | string;
    description?: Snippet | string;
    invalid?: boolean;
    indeterminate?: boolean;
    onCheckedChange?: (checked: boolean) => void;
    class?: string;
    style?: string;
  }

  let { label, description, invalid, indeterminate, onCheckedChange, onchange, class: className, style, ...rest }: Props = $props();

  const field = useField();
  const wiring = $derived(fieldProps(rest, field, invalid));
  let own = $state<HTMLInputElement | undefined>(undefined);

  $effect(() => {
    if (own) own.indeterminate = !!indeterminate;
  });

  function handleChange(event: Event & { currentTarget: EventTarget & HTMLInputElement }) {
    onchange?.(event);
    onCheckedChange?.(event.currentTarget.checked);
  }
</script>

<ChoiceShell {label} {description} class={className} {style}>
  {#snippet control()}
    <input
      bind:this={own}
      type="checkbox"
      class={cx('nx-checkbox', !label && !description && className)}
      onchange={handleChange}
      {...rest}
      {...wiring}
    />
  {/snippet}
</ChoiceShell>
