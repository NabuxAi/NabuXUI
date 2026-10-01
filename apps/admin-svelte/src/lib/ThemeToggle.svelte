<script lang="ts">
  /**
   * The header theme toggle — the markup of React's `ThemeToggle`: both glyphs
   * live in the button and CSS swaps them; the click writes the shared core
   * theme store (`nabu.theme`), so every flavour of the panel agrees.
   */
  import { theme, translate } from '@nabuxai/ui-core';
  import { app } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  let dark = $state(false);

  $effect(() => {
    dark = theme.resolved() === 'dark';
    return theme.watch((scheme) => (dark = scheme === 'dark'));
  });

  const toggle = () => {
    dark = theme.toggle() === 'dark';
  };

  const label = $derived(translate(app.lang, 'darkMode'));
  const titleWord = $derived(dark ? translate(app.lang, 'toLight') : translate(app.lang, 'toDark'));
</script>

<button
  type="button"
  class="nx-button nx-theme-toggle"
  data-variant="ghost"
  data-icon-only=""
  aria-pressed={dark}
  aria-label={label}
  title={titleWord}
  onclick={toggle}
>
  <span class="nx-button-label">
    <span class="nx-theme-icons" aria-hidden="true">
      <NxIcon name="sun" class="nx-theme-sun" />
      <NxIcon name="moon" class="nx-theme-moon" />
    </span>
  </span>
</button>
