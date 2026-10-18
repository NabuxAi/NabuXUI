<script lang="ts">
  /**
   * Select — همتای Svelte برای Select در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import { fieldProps, useField } from '../../internal/field';
  import type { Snippet } from 'svelte';
  import type { HTMLSelectAttributes } from 'svelte/elements';

  export interface SelectOption {
    value: string;
    label: string;
    disabled?: boolean;
  }

  interface Props extends Omit<HTMLSelectAttributes, 'size'> {
    size?: 'sm' | 'md' | 'lg';
    invalid?: boolean;
    options?: SelectOption[];
    placeholder?: string;
    class?: string;
    /** گزینه‌های دلخواه مثل React children. */
    children?: Snippet;
  }

  let { size, invalid, options, placeholder, class: className, children, ...rest }: Props = $props();

  const field = useField();
  const wiring = $derived(fieldProps(rest, field, invalid));
</script>

<select class={cx('nx-select', className)} data-size={size === 'md' ? undefined : size} {...rest} {...wiring}>
  {#if placeholder}
    <option value="" disabled>{placeholder}</option>
  {/if}
  {#each options ?? [] as option (option.value)}
    <option value={option.value} disabled={option.disabled}>{option.label}</option>
  {/each}
  {@render children?.()}
</select>
