<script lang="ts">
  /**
   * OrbitLoader — the Svelte counterpart of ui-react's feedback family's
   * OrbitLoader: same DOM, same nx-* classes, same props.
   */
  import type { LoaderProps } from './spinner.svelte';
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';

  let { label, size = 'md', class: className }: LoaderProps = $props();

  const lang = nxLang();
  const LOADER_SIZE = { sm: '2.25rem', md: '3.5rem', lg: '5rem' } as const;
  const rings = [
    { r: '0deg', c: 'var(--nx-lapis-500)', d: '1.6s' },
    { r: '60deg', c: 'var(--nx-cyan-400)', d: '2.1s' },
    { r: '120deg', c: 'var(--nx-violet-500)', d: '2.6s' },
  ];
</script>

<span class={cx('nx-orbit', className)} role="status" style:--nx-loader-size={LOADER_SIZE[size]}>
  <svg viewBox="0 0 64 64" aria-hidden="true">
    {#each rings as ring (ring.r)}
      <g class="nx-orbit-ring" style:--r={ring.r} style:--c={ring.c} style:--d={ring.d}>
        <ellipse class="nx-orbit-path" cx="32" cy="32" rx="26" ry="10" />
        <ellipse class="nx-orbit-comet" cx="32" cy="32" rx="26" ry="10" pathLength={100} />
      </g>
    {/each}
    <circle class="nx-orbit-core" cx="32" cy="32" r="4.5" />
  </svg>
  <span class="nx-visually-hidden">{label ?? nxTranslate(lang, 'loading')}</span>
</span>
