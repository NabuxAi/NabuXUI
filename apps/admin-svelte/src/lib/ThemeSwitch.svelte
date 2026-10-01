<script lang="ts">
  /**
   * The three-state theme switch — the markup of the `theme-switch` block CSS
   * contract in ui-core (nx-theme-switch): light / system / dark radios with
   * the spring thumb (core `indicator`) between them, writing the shared theme
   * store.
   */
  import { onMount } from 'svelte';
  import { type Cleanup, type ThemePreference, indicator, theme } from '@nabuxai/ui-core';
  import { strings } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  let { label }: { label: string } = $props();

  let root: HTMLElement;
  let value = $state<ThemePreference>('system');
  let ind: ReturnType<typeof indicator> | null = null;

  const move = () => {
    ind?.update(root?.querySelector('.nx-theme-switch-option:has(:checked)') ?? null);
  };

  $effect(() => {
    value = theme.preference();
    return theme.watch(() => {
      value = theme.preference();
      requestAnimationFrame(move);
    });
  });

  onMount(() => {
    ind = indicator(root);
    requestAnimationFrame(move);
    return () => ind?.destroy();
  });

  const s = $derived(strings());
  const options = $derived([
    { id: 'light' as const, label: s.pages.settings.themeLight },
    { id: 'system' as const, label: s.pages.settings.themeSystem },
    { id: 'dark' as const, label: s.pages.settings.themeDark },
  ]);

  const choose = (next: ThemePreference) => {
    value = next;
    theme.set(next);
    requestAnimationFrame(move);
  };
</script>

<div bind:this={root} class="nx-theme-switch" role="radiogroup" aria-label={label} data-nx-indicator="ready">
  <span class="nx-indicator" aria-hidden="true"></span>
  {#each options as option (option.id)}
    <label class="nx-theme-switch-option" title={option.label}>
      <input
        class="nx-theme-switch-input"
        type="radio"
        name="nx-theme-switch"
        value={option.id}
        checked={value === option.id}
        onchange={() => choose(option.id)}
      />
      {#if option.id === 'system'}
        <span class="nx-theme-switch-half" aria-hidden="true"></span>
      {:else}
        <NxIcon name={option.id === 'light' ? 'sun' : 'moon'} />
      {/if}
      <span class="nx-visually-hidden">{option.label}</span>
    </label>
  {/each}
</div>
