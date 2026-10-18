<script module lang="ts">
  import type { TiltOptions } from '@nabuxai/ui-core';
  import type { HTMLAttributes } from 'svelte/elements';
  import type { IconName } from '@nabuxai/ui-core';
  import type { Snippet } from 'svelte';

  /** گزینهٔ پایدار — تا اکشن فقط با تغییر وابستگی‌هایش دوباره وصل شود. */
  const TILT: TiltOptions = {};

  export interface CardProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
    variant?: 'default' | 'glass' | 'outline' | 'gradient' | 'inverse';
    size?: 'sm' | 'md' | 'lg';
    /** Lifts on hover (links and buttons always do). */
    interactive?: boolean;
    /** A light and a lit edge that follow the pointer. */
    spotlight?: boolean;
    /** Tilts in 3D toward the pointer. */
    tilt?: boolean;
    href?: string;
    icon?: IconName | Snippet;
    title?: string | Snippet;
    description?: string | Snippet;
    footer?: string | Snippet;
    /** Heading level of the title (h3 by default). */
    titleAs?: 'h2' | 'h3' | 'h4';
    children?: Snippet;
  }
</script>

<script lang="ts">
  /**
   * Card — the Svelte counterpart of ui-react's card family's Card: an article
   * or an anchor, with the pointer-following spotlight and 3D tilt behaviours.
   */
  import { spotlight as spotlightBehavior, tilt as tiltBehavior } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';

  let {
    variant,
    size,
    interactive,
    spotlight,
    tilt,
    href,
    icon,
    title,
    description,
    footer,
    titleAs: Title = 'h3',
    class: className,
    children,
    ...rest
  }: CardProps = $props();

  const shared = $derived(cx('nx-card', className));
</script>

{#snippet content()}
  {#if icon || title || description}
    <div class="nx-card-header">
      {#if icon}
        <span class="nx-card-icon">{#if typeof icon === 'string'}<Icon name={icon as IconName} />{:else}{@render icon()}{/if}</span>
      {/if}
      {#if title}
        <svelte:element this={Title} class="nx-card-title">{#if typeof title === 'string'}{title}{:else}{@render title()}{/if}</svelte:element>
      {/if}
      {#if description}
        <p class="nx-card-description">{#if typeof description === 'string'}{description}{:else}{@render description()}{/if}</p>
      {/if}
    </div>
  {/if}
  {#if children}
    <div class="nx-card-body">{@render children()}</div>
  {/if}
  {#if footer}
    <div class="nx-card-footer">{#if typeof footer === 'string'}{footer}{:else}{@render footer()}{/if}</div>
  {/if}
{/snippet}

{#if href}
  <a
    href={href}
    class={shared}
    data-variant={variant && variant !== 'default' ? variant : undefined}
    data-size={size && size !== 'md' ? size : undefined}
    data-interactive={interactive ? '' : undefined}
    data-spotlight={spotlight ? '' : undefined}
    use:nxBehavior={{ behavior: spotlightBehavior, options: undefined, enabled: !!spotlight }}
    use:nxBehavior={{ behavior: tiltBehavior, options: TILT, enabled: !!tilt }}
    {...rest}
  >
    {@render content()}
  </a>
{:else}
  <article
    class={shared}
    data-variant={variant && variant !== 'default' ? variant : undefined}
    data-size={size && size !== 'md' ? size : undefined}
    data-interactive={interactive ? '' : undefined}
    data-spotlight={spotlight ? '' : undefined}
    use:nxBehavior={{ behavior: spotlightBehavior, options: undefined, enabled: !!spotlight }}
    use:nxBehavior={{ behavior: tiltBehavior, options: TILT, enabled: !!tilt }}
    {...rest}
  >
    {@render content()}
  </article>
{/if}
