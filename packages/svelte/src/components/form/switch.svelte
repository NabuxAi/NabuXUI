<script lang="ts">
  /**
   * Switch — همتای Svelte برای Switch در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import { fieldProps, useField } from '../../internal/field';
  import ChoiceShell from './choice-shell.svelte';
  import type { Snippet } from 'svelte';
  import type { HTMLInputAttributes } from 'svelte/elements';

  interface Props extends Omit<HTMLInputAttributes, 'type' | 'size'> {
    label?: Snippet | string;
    description?: Snippet | string;
    size?: 'sm' | 'md' | 'lg';
    invalid?: boolean;
    onCheckedChange?: (checked: boolean) => void;
    class?: string;
    style?: string;
  }

  let { label, description, size, invalid, onCheckedChange, onchange, class: className, style, ...rest }: Props = $props();

  const field = useField();
  const wiring = $derived(fieldProps(rest, field, invalid));

  function handleChange(event: Event & { currentTarget: EventTarget & HTMLInputElement }) {
    onchange?.(event);
    onCheckedChange?.(event.currentTarget.checked);
  }
</script>

<ChoiceShell {label} {description} class={className} {style}>
  {#snippet control()}
    <input
      type="checkbox"
      role="switch"
      class={cx('nx-switch', !label && !description && className)}
      data-size={size === 'md' ? undefined : size}
      onchange={handleChange}
      {...rest}
      {...wiring}
    />
  {/snippet}
</ChoiceShell>
