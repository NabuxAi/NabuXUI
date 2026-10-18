<script lang="ts">
  /**
   * RadioGroup — همتای Svelte برای RadioGroup در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import Radio from './radio.svelte';
  import type { Snippet } from 'svelte';

  export interface RadioGroupOption {
    value: string;
    label: Snippet | string;
    description?: Snippet | string;
    disabled?: boolean;
  }

  interface Props {
    name: string;
    legend?: Snippet | string;
    options: RadioGroupOption[];
    value?: string;
    defaultValue?: string;
    onValueChange?: (value: string) => void;
    orientation?: 'vertical' | 'horizontal';
    class?: string;
  }

  let { name, legend, options, defaultValue = '', value = $bindable(defaultValue), onValueChange, orientation = 'vertical', class: className }: Props = $props();

  const flexDirection = $derived(orientation === 'vertical' ? 'column' : 'row');
</script>

<fieldset class={cx('nx-field', className)} style="border: 0; margin: 0; padding: 0">
  {#if legend}<legend class="nx-label">{#if typeof legend === 'function'}{@render legend()}{:else}{legend}{/if}</legend>{/if}
  <div style="display: flex; flex-direction: {flexDirection}; flex-wrap: wrap; gap: var(--nx-space-3) var(--nx-space-6)">
    {#each options as option (option.value)}
      <Radio
        {name}
        value={option.value}
        label={option.label}
        description={option.description}
        disabled={option.disabled}
        checked={value === option.value}
        onchange={() => {
          value = option.value;
          onValueChange?.(option.value);
        }}
      />
    {/each}
  </div>
</fieldset>
