<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { NavItem } from './nav';

  export interface HeaderProps {
    brand: string | Snippet;
    brandHref?: string;
    items?: NavItem[];
    /** Buttons at the end of the bar. Mark desktop-only ones with data-desktop. */
    actions?: string | Snippet;
    /** Shown at the bottom of the mobile menu. */
    mobileActions?: string | Snippet;
    variant?: 'bar' | 'floating';
    /** Slide away while scrolling down, back on the way up. */
    hideOnScroll?: boolean;
    'aria-label'?: string;
    class?: string;
  }
</script>

<script lang="ts">
  import type { MessageKey } from '@nabuxai/ui-core';
  import { headerScroll } from '@nabuxai/ui-core';
  import { nxTranslate } from '../../i18n';
  import { nxLang } from '../../internal/locale';
  import { cx } from '../../internal/cx';
  import SmartLink from '../../internal/smart-link.svelte';
  import Content from '../../internal/content.svelte';
  import { nxBehavior } from '../../internal/actions';
  import MegaMenu from './mega-menu.svelte';
  import MobileMenu from './mobile-menu.svelte';
  import Drawer from '../overlay/drawer.svelte';

  let {
    brand,
    brandHref = '/',
    items = [],
    actions,
    mobileActions,
    variant = 'bar',
    hideOnScroll,
    'aria-label': label,
    class: className,
  }: HeaderProps = $props();

  /** The component's own words, in the page's language. */
  const t = (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(nxLang(), key, params);

  let ref = $state<HTMLElement>();
  let menuOpen = $state(false);

  // Re-attaches when the hide flag changes, like the React seat's useBehavior
  // with its options key in the deps.
  const scrollParams = $derived({ behavior: headerScroll, options: { hide: !!hideOnScroll } });
</script>

<header
  class={cx('nx-header', className)}
  data-variant={variant === 'floating' ? 'floating' : undefined}
  bind:this={ref}
  use:nxBehavior={scrollParams}
>
  <div class="nx-header-inner">
    <SmartLink class="nx-header-brand" href={brandHref}>
      <Content of={brand} />
    </SmartLink>
    {#if items.length > 0}
      <div class="nx-header-nav">
        <MegaMenu {items} aria-label={label} />
      </div>
    {/if}
    {#if actions}
      <div class="nx-header-actions">
        <Content of={actions} />
      </div>
    {/if}
    {#if items.length > 0}
      <button
        type="button"
        class="nx-header-toggle"
        aria-expanded={menuOpen}
        aria-label={t('menu')}
        onclick={() => (menuOpen = true)}
      >
        <span class="nx-burger" aria-hidden="true">
          <i></i>
          <i></i>
        </span>
      </button>
    {/if}
  </div>
  {#if items.length > 0}
    <Drawer open={menuOpen} onOpenChange={(open) => (menuOpen = open)} title={t('menu')}>
      <MobileMenu {items} onNavigate={() => (menuOpen = false)} footer={mobileActions} />
    </Drawer>
  {/if}
</header>
