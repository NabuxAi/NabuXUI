<script module lang="ts">
  export interface ThemeToggleProps {
    class?: string;
  }
</script>

<script lang="ts">
  import type { MessageKey } from '@nabuxai/ui-core';
  import { theme } from '@nabuxai/ui-core';
  import { nxTranslate } from '../../i18n';
  import { nxLang } from '../../internal/locale';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';

  let { class: className }: ThemeToggleProps = $props();

  /** The component's own words, in the page's language. */
  const t = (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(nxLang(), key, params);

  // Has the component mounted in the browser yet (for markup that must match SSR).
  let mounted = $state(false);
  let dark = $state(false);

  $effect(() => {
    mounted = true;
    dark = theme.resolved() === 'dark';
    return theme.watch((scheme) => (dark = scheme === 'dark'));
  });
</script>

<button
  type="button"
  class={cx('nx-button', 'nx-theme-toggle', className)}
  data-variant="ghost"
  data-icon-only=""
  aria-pressed={mounted ? dark : undefined}
  aria-label={t('darkMode')}
  title={dark ? t('toLight') : t('toDark')}
  onclick={() => (dark = theme.toggle() === 'dark')}
>
  <span class="nx-button-label">
    <span class="nx-theme-icons" aria-hidden="true">
      <Icon name="sun" class="nx-theme-sun" />
      <Icon name="moon" class="nx-theme-moon" />
    </span>
  </span>
</button>
