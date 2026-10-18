<script module lang="ts">
  /** Wedges pressed into clay one after another — the Nabu scribe at work. */
  export const WEDGES = [
    { transform: 'translate(2 7)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
    { transform: 'translate(24 1) rotate(90 5 5)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
    { transform: 'translate(34 7)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
    { transform: 'translate(52 5)', d: 'M10 0L0 7L10 14L6.5 7Z' },
  ] as const;
</script>

<script lang="ts">
  /**
   * CuneiformLoader — the Svelte counterpart of ui-react's feedback family's
   * CuneiformLoader: same DOM, same nx-* classes, same props.
   */
  import type { LoaderProps } from './spinner.svelte';
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';

  let { label, size = 'md', class: className }: LoaderProps = $props();

  const lang = nxLang();
</script>

<span
  class={cx('nx-cuneiform', className)}
  role="status"
  style:--nx-loader-size={{ sm: '3rem', md: '4.5rem', lg: '6.5rem' }[size]}
>
  <svg viewBox="0 0 64 24" aria-hidden="true">
    {#each WEDGES as wedge, i (i)}
      <g transform={wedge.transform}>
        <path class="nx-wedge" d={wedge.d} style:--i={i} />
      </g>
    {/each}
  </svg>
  <span class="nx-visually-hidden">{label ?? nxTranslate(lang, 'loading')}</span>
</span>
