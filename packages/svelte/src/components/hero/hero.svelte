<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { HTMLAttributes } from 'svelte/elements';
  import type { BackdropVariant } from './backdrop.svelte';

  export interface HeroProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
    backdrop?: BackdropVariant | false;
    /** The small pill above the title: text, an optional badge, an optional link. */
    eyebrow?: { label: string | Snippet; badge?: string | Snippet; href?: string };
    /** A string gets the word-by-word reveal; a snippet is rendered as is. */
    title: string | Snippet;
    subtitle?: string | Snippet;
    actions?: string | Snippet;
    /** A product shot or illustration that tips into place under the text. */
    media?: string | Snippet;
    align?: 'center' | 'start';
  }
</script>

<script lang="ts">
  import { reveal, spotlight } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import SmartLink from '../../internal/smart-link.svelte';
  import Content from '../../internal/content.svelte';
  import { nxBehavior } from '../../internal/actions';
  import { nxB } from '../../internal/bind';
  import { Badge } from '../feedback.svelte';
  import { TextReveal } from '../text.svelte';
  import Backdrop from './backdrop.svelte';

  let {
    backdrop = 'aurora',
    eyebrow,
    title,
    subtitle,
    actions,
    media,
    align = 'center',
    class: className,
    children,
    ...rest
  }: HeroProps = $props();

  // The glow follows the pointer only on the grid variant — the React seat's
  // useBehavior(section, spotlight, undefined, backdrop === 'grid').
  const spotlightParams = $derived({ behavior: spotlight, options: undefined, enabled: backdrop === 'grid' });

  // The content reveals as a staggered group; the media tips in on its own.
  const revealContent = nxB(reveal, { stagger: true, once: true });
  const revealMedia = nxB(reveal, { once: true, threshold: 0.05 });
</script>

<section
  class={cx('nx-hero', className)}
  data-align={align === 'start' ? 'start' : undefined}
  use:nxBehavior={spotlightParams}
  {...rest}
>
  {#if backdrop}
    <Backdrop variant={backdrop} />
  {/if}
  <div class="nx-hero-content" data-nx-reveal="group" use:revealContent>
    {#if eyebrow}
      {#if eyebrow.href}
        <SmartLink class="nx-hero-eyebrow" href={eyebrow.href}>
          {#if eyebrow.badge}
            <Badge tone="accent" variant="solid">
              <Content of={eyebrow.badge} />
            </Badge>
          {/if}
          <span>
            <Content of={eyebrow.label} />
          </span>
          <Icon name="arrow-right" />
        </SmartLink>
      {:else}
        <p class="nx-hero-eyebrow">
          {#if eyebrow.badge}
            <Badge tone="accent" variant="solid">
              <Content of={eyebrow.badge} />
            </Badge>
          {/if}
          <span>
            <Content of={eyebrow.label} />
          </span>
        </p>
      {/if}
    {/if}
    {#if typeof title === 'string'}
      <TextReveal as="h1" class="nx-hero-title" delay={120} text={title} />
    {:else}
      <h1 class="nx-hero-title">
        <Content of={title} />
      </h1>
    {/if}
    {#if subtitle}
      <p class="nx-hero-subtitle">
        <Content of={subtitle} />
      </p>
    {/if}
    {#if actions}
      <div class="nx-hero-actions">
        <Content of={actions} />
      </div>
    {/if}
    {@render children?.()}
  </div>
  {#if media}
    <div class="nx-hero-media" data-nx-reveal="" use:revealMedia>
      <div>
        <Content of={media} />
      </div>
    </div>
  {/if}
</section>
