<script module lang="ts">
  export interface LoaderProps {
    /** What is loading, for screen readers. Defaults to "Loading". */
    label?: string;
    size?: 'sm' | 'md' | 'lg';
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * Spinner — the Svelte counterpart of ui-react's feedback family's Spinner.
   */
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';

  let { label, size, class: className, tone }: LoaderProps & { tone?: 'accent' } = $props();

  const lang = nxLang();
</script>

<progress class={cx('nx-spinner', className)} aria-label={label ?? nxTranslate(lang, 'loading')} data-size={size === 'md' ? undefined : size} data-tone={tone}></progress>
