<script lang="ts">
  /**
   * DotsLoader — the Svelte counterpart of ui-react's feedback family's
   * DotsLoader: same DOM, same nx-* classes, same props.
   */
  import type { LoaderProps } from './spinner.svelte';
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';

  let { label, class: className }: LoaderProps = $props();

  const lang = nxLang();
</script>

<span class={cx('nx-dots', className)} role="status">
  <i></i>
  <i></i>
  <i></i>
  <span class="nx-visually-hidden">{label ?? nxTranslate(lang, 'loading')}</span>
</span>
