<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { IconName } from '@nabuxai/ui-core';

  export interface TabItem {
    value: string;
    label: string | Snippet;
    content: string | Snippet;
    badge?: string | Snippet;
    icon?: IconName;
    disabled?: boolean;
  }

  export interface TabsProps {
    items: TabItem[];
    value?: string;
    defaultValue?: string;
    onValueChange?: (value: string) => void;
    variant?: 'pill' | 'underline' | 'enclosed';
    'aria-label'?: string;
    class?: string;
  }

  /** `a b` → `a_b`: safe to embed a tab's value in id / aria wiring. */
  const idSafe = (value: string) => value.replace(/[^\w-]/g, '_');
</script>

<script lang="ts">
  import { type IndicatorController, indicator, nextId, roveFocus } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import Content from '../../internal/content.svelte';

  let { items, value, defaultValue, onValueChange, variant = 'pill', 'aria-label': label, class: className }: TabsProps = $props();

  const base = nextId('nx-tabs');
  // svelte-ignore state_referenced_locally
  // defaultValue seeds the internal state once, exactly like the React seat's useState.
  let internal = $state(defaultValue ?? items[0]?.value ?? '');
  /** Controlled while `value` is passed, internal otherwise. */
  let current = $derived(value !== undefined ? value : internal);

  const setCurrent = (next: string) => {
    if (value === undefined) internal = next;
    onValueChange?.(next);
  };

  let list = $state<HTMLDivElement>();
  let ind: IndicatorController | undefined;

  $effect(() => {
    ind = indicator(list!);
    return () => {
      ind?.destroy();
      ind = undefined;
    };
  });

  $effect(() => {
    // Re-home when the selection or the item set changes, like the React
    // seat's [current, items] layout effect.
    items;
    ind?.update(list?.querySelector(`[data-value="${CSS.escape(current)}"]`) ?? null);
  });

  const onKeyDown = (event: KeyboardEvent) => {
    const next = roveFocus(event, list!, '[role="tab"]');
    // Automatic activation: moving focus selects the tab.
    if (next?.dataset.value) setCurrent(next.dataset.value);
  };
</script>

<!-- svelte-ignore a11y_interactive_supports_focus -- the focused tab itself carries the tabindex -->
<div class={cx('nx-tabs', className)} data-variant={variant === 'pill' ? undefined : variant}>
  <div class="nx-tab-list" role="tablist" aria-label={label} bind:this={list} onkeydown={onKeyDown}>
    <span class="nx-indicator" aria-hidden="true"></span>
    {#each items as item (item.value)}
      {@const selected = item.value === current}
      <button
        type="button"
        role="tab"
        class="nx-tab"
        id={`${base}-tab-${idSafe(item.value)}`}
        data-value={item.value}
        aria-selected={selected}
        aria-controls={`${base}-panel-${idSafe(item.value)}`}
        tabindex={selected ? 0 : -1}
        disabled={item.disabled}
        onclick={() => setCurrent(item.value)}
      >
        {#if item.icon}
          <Icon name={item.icon} />
        {/if}
        <Content of={item.label} />
        {#if item.badge}
          <Content of={item.badge} />
        {/if}
      </button>
    {/each}
  </div>
  {#each items as item (item.value)}
    <div
      class="nx-tab-panel"
      role="tabpanel"
      id={`${base}-panel-${idSafe(item.value)}`}
      aria-labelledby={`${base}-tab-${idSafe(item.value)}`}
      tabindex="0"
      hidden={item.value !== current}
    >
      <Content of={item.content} />
    </div>
  {/each}
</div>
