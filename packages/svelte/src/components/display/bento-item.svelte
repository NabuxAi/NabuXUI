<script module lang="ts">
  import type { HTMLAttributes } from 'svelte/elements';
  import type { Snippet } from 'svelte';

  export interface BentoItemProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
    colSpan?: number;
    rowSpan?: number;
    title?: string | Snippet;
    description?: string | Snippet;
    /** Artwork at the top of the tile (image, chart, illustration). */
    media?: Snippet;
    href?: string;
    spotlight?: boolean;
    children?: Snippet;
  }
</script>

<script lang="ts">
  /**
   * BentoItem — the Svelte counterpart of ui-react's display family's
   * BentoItem: a tile or a link, lit by the pointer when spotlighted.
   */
  import { spotlight as spotlightBehavior } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';

  let {
    colSpan = 1,
    rowSpan = 1,
    title,
    description,
    media,
    href,
    spotlight = true,
    class: className,
    style,
    children,
    ...rest
  }: BentoItemProps = $props();

  const shared = $derived(cx('nx-bento-item', spotlight && 'nx-spotlight', className));
  const vars = $derived([`--nx-col-span: ${colSpan}`, `--nx-row-span: ${rowSpan}`, style].filter(Boolean).join('; ') || undefined);
</script>

{#snippet content()}
  {#if media}
    <div class="nx-bento-media">{@render media()}</div>
  {/if}
  {#if title}
    <h3 class="nx-bento-title">{#if typeof title === 'string'}{title}{:else}{@render title()}{/if}</h3>
  {/if}
  {#if description}
    <p class="nx-bento-description">{#if typeof description === 'string'}{description}{:else}{@render description()}{/if}</p>
  {/if}
  {#if children}{@render children()}{/if}
{/snippet}

{#if href}
  <a
    href={href}
    class={shared}
    style={vars}
    use:nxBehavior={{ behavior: spotlightBehavior, options: undefined, enabled: spotlight }}
    {...rest}
  >
    {@render content()}
  </a>
{:else}
  <article
    class={shared}
    style={vars}
    use:nxBehavior={{ behavior: spotlightBehavior, options: undefined, enabled: spotlight }}
    {...rest}
  >
    {@render content()}
  </article>
{/if}
