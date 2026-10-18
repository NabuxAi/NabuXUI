<script lang="ts">
  /**
   * Input — همتای Svelte برای Input در ui-react/components/form.tsx.
   */
  import { icons, type IconName } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import { fieldProps, useField } from '../../internal/field';
  import type { Snippet } from 'svelte';
  import type { HTMLInputAttributes } from 'svelte/elements';

  interface OwnProps {
    size?: 'sm' | 'md' | 'lg';
    invalid?: boolean;
    /** Content before the text: an icon name, a unit, a prefix (string) or any snippet. */
    startAddon?: IconName | string | Snippet;
    endAddon?: IconName | string | Snippet;
  }

  interface Props extends Omit<HTMLInputAttributes, 'size' | keyof OwnProps>, OwnProps {
    class?: string;
  }

  let { size, invalid, startAddon, endAddon, class: className, ...rest }: Props = $props();

  const field = useField();
  const wiring = $derived(fieldProps(rest, field, invalid));
</script>

{#snippet addon(value: IconName | string | Snippet)}
  {#if typeof value === 'string' && value in icons}
    <Icon name={value as IconName} />
  {:else if typeof value === 'string'}
    {value}
  {:else}
    {@render value()}
  {/if}
{/snippet}

{#if !startAddon && !endAddon}
  <input class={cx('nx-input', className)} data-size={size === 'md' ? undefined : size} {...rest} {...wiring} />
{:else}
  <div class={cx('nx-input-group', className)} data-size={size === 'md' ? undefined : size}>
    {#if startAddon}<span class="nx-input-addon">{@render addon(startAddon)}</span>{/if}
    <input class="nx-input" {...rest} {...wiring} />
    {#if endAddon}<span class="nx-input-addon">{@render addon(endAddon)}</span>{/if}
  </div>
{/if}
