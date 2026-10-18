<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface MarqueeProps {
    items: Array<string | Snippet>;
    /** Seconds for one full loop. */
    duration?: number;
    reverse?: boolean;
    gap?: string;
    /** A pause button (on by default: moving content must be stoppable). */
    controls?: boolean;
    'aria-label'?: string;
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * Marquee — the Svelte counterpart of ui-react's display family's Marquee:
   * the second copy only exists for the seamless loop, so it is inert and
   * hidden; a pause button stops the movement.
   */
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import Icon from '../../internal/icon.svelte';

  let { items, duration = 40, reverse, gap, controls = true, 'aria-label': label, class: className }: MarqueeProps = $props();

  const lang = nxLang();
  let paused = $state(false);
</script>

{#snippet group(item: string | Snippet, i: number)}
  <li>{#if typeof item === 'string'}{item}{:else}{@render item()}{/if}</li>
{/snippet}

<section
  class={cx('nx-marquee', className)}
  aria-label={label}
  data-direction={reverse ? 'reverse' : undefined}
  data-paused={paused ? '' : undefined}
  style:--nx-duration={`${duration}s`}
  style:--nx-marquee-gap={gap || undefined}
>
  <div class="nx-marquee-track">
    <ul class="nx-marquee-group">
      {#each items as item, i (i)}
        {@render group(item, i)}
      {/each}
    </ul>
    <!-- The second copy only exists for the seamless loop: nobody should tab into it. -->
    <ul class="nx-marquee-group" aria-hidden="true" inert={true}>
      {#each items as item, i (i)}
        {@render group(item, i)}
      {/each}
    </ul>
  </div>
  {#if controls}
    <button
      type="button"
      class="nx-marquee-toggle"
      aria-pressed={paused}
      aria-label={paused ? nxTranslate(lang, 'play') : nxTranslate(lang, 'pause')}
      onclick={() => (paused = !paused)}
    >
      <Icon name={paused ? 'play' : 'pause'} />
    </button>
  {/if}
</section>
