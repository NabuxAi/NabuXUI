<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { IconName } from '@nabuxai/ui-core';

  export interface SegmentedControlProps {
    options: Array<{ value: string; label: string | Snippet; icon?: IconName; disabled?: boolean }>;
    value?: string;
    defaultValue?: string;
    onValueChange?: (value: string) => void;
    name?: string;
    size?: 'sm' | 'md';
    tone?: 'neutral' | 'accent';
    'aria-label'?: string;
    class?: string;
  }
</script>

<script lang="ts">
  import { type IndicatorController, indicator, nextId } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import Content from '../../internal/content.svelte';

  let { options, value, defaultValue, onValueChange, name, size, tone, 'aria-label': label, class: className }: SegmentedControlProps = $props();

  // svelte-ignore state_referenced_locally
  // name seeds the radio group once; defaultValue seeds the internal state once —
  // both exactly like the React seat's useId / useState.
  const group = name ?? nextId('nx-seg');
  // svelte-ignore state_referenced_locally
  let internal = $state(defaultValue ?? options[0]?.value ?? '');
  /** Controlled while `value` is passed, internal otherwise. */
  let current = $derived(value !== undefined ? value : internal);

  const setCurrent = (next: string) => {
    if (value === undefined) internal = next;
    onValueChange?.(next);
  };

  let root = $state<HTMLDivElement>();
  let ind: IndicatorController | undefined;

  $effect(() => {
    ind = indicator(root!);
    return () => {
      ind?.destroy();
      ind = undefined;
    };
  });

  $effect(() => {
    // Re-home when the selection or the option set changes, like the React
    // seat's [current, options] layout effect.
    options;
    ind?.update(root?.querySelector('.nx-segment:has(:checked)') ?? null);
  });
</script>

<div
  class={cx('nx-segmented', className)}
  role="radiogroup"
  aria-label={label}
  data-size={size === 'sm' ? 'sm' : undefined}
  data-tone={tone === 'accent' ? 'accent' : undefined}
  bind:this={root}
>
  <span class="nx-indicator" aria-hidden="true"></span>
  {#each options as option (option.value)}
    <label class="nx-segment">
      <input
        class="nx-segment-input"
        type="radio"
        name={group}
        value={option.value}
        checked={current === option.value}
        disabled={option.disabled}
        onchange={() => setCurrent(option.value)}
      />
      {#if option.icon}
        <Icon name={option.icon} />
      {/if}
      <Content of={option.label} />
    </label>
  {/each}
</div>
