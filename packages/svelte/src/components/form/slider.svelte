<script lang="ts">
  /**
   * Slider — همتای Svelte برای Slider در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import { fieldProps, useField } from '../../internal/field';
  import type { Snippet } from 'svelte';
  import type { HTMLInputAttributes } from 'svelte/elements';

  interface Props extends Omit<HTMLInputAttributes, 'type' | 'min' | 'max' | 'step'> {
    min?: number;
    max?: number;
    step?: number;
    value?: number;
    defaultValue?: number;
    onValueChange?: (value: number) => void;
    /** Keep the value bubble visible, not only while interacting. */
    showValue?: boolean;
    formatValue?: (value: number) => string;
    /** Labels under the track's ends. */
    startLabel?: Snippet | string;
    endLabel?: Snippet | string;
    class?: string;
    style?: string;
  }

  let {
    min = 0,
    max = 100,
    step = 1,
    defaultValue,
    value = $bindable(defaultValue ?? min),
    onValueChange,
    showValue,
    formatValue,
    startLabel,
    endLabel,
    class: className,
    style,
    onchange,
    oninput,
    ...rest
  }: Props = $props();

  const field = useField();
  const wiring = $derived(fieldProps(rest, field));
  const fraction = $derived((value - min) / (max - min || 1));
  const shown = $derived(formatValue ? formatValue(value) : String(value));
  const vars = $derived([`--nx-pct: ${fraction * 100}%`, `--nx-frac: ${fraction}`, style].filter(Boolean).join('; '));

  // مثل onChange ری‌اکت که با هر حرکت اعمال می‌شود.
  function handleInput(event: Event & { currentTarget: EventTarget & HTMLInputElement }) {
    const next = Number(event.currentTarget.value);
    value = next;
    onValueChange?.(next);
    oninput?.(event);
  }
</script>

<div class={cx('nx-slider', className)} data-show-value={showValue ? '' : undefined} style={vars}>
  <input
    type="range"
    class="nx-slider-input"
    {min}
    {max}
    {step}
    {value}
    aria-valuetext={formatValue ? shown : undefined}
    oninput={handleInput}
    onchange={onchange}
    {...rest}
    {...wiring}
  />
  <output class="nx-slider-value" aria-hidden="true">{shown}</output>
  {#if startLabel || endLabel}
    <div class="nx-slider-labels" aria-hidden="true">
      <span>{#if typeof startLabel === 'function'}{@render startLabel()}{:else}{startLabel}{/if}</span>
      <span>{#if typeof endLabel === 'function'}{@render endLabel()}{:else}{endLabel}{/if}</span>
    </div>
  {/if}
</div>
