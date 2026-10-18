<script module lang="ts">
  import type { ToastStore } from '@nabuxai/ui-core';

  export type ToasterPosition = 'bottom-end' | 'bottom-start' | 'bottom-center' | 'top-end' | 'top-start' | 'top-center';

  export interface ToasterProps {
    position?: ToasterPosition;
    /** Another store, if the page runs more than one (rare). */
    store?: ToastStore;
    /** `ripple`: a ring spreads from each toast as it lands. */
    effect?: 'ripple';
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * Toaster — the Svelte counterpart of ui-react's feedback family's Toaster.
   * Render once near the root; then call `toast(...)` from anywhere.
   */
  import { stackToasts, toasts as defaultStore, type Toast } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import ToastItem from './toast-item.svelte';

  let { position = 'bottom-end', store = defaultStore, effect: toastEffect, class: className }: ToasterProps = $props();

  const lang = nxLang();
  let items = $state<readonly Toast[]>(store.getSnapshot());
  let section = $state<HTMLElement>();
  let list = $state<HTMLOListElement>();

  $effect(() => store.subscribe((next) => (items = next)));

  // In the top layer, so toasts stay visible above open dialogs.
  $effect(() => {
    const el = section;
    try {
      if (typeof el?.showPopover === 'function') el.showPopover();
    } catch {
      /* already open */
    }
  });

  // A dialog opened after the toaster would cover it: step back on top when a toast arrives.
  $effect(() => {
    void items.length;
    const el = section;
    if (typeof el?.showPopover !== 'function' || !document.querySelector('dialog:modal')) return;
    try {
      el.hidePopover();
      el.showPopover();
    } catch {
      /* not supported */
    }
  });

  $effect(() => {
    void items;
    if (list) stackToasts(list);
  });

  $effect(() => {
    void items;
    if (!list || typeof ResizeObserver === 'undefined') return;
    const observer = new ResizeObserver(() => list && stackToasts(list));
    for (const child of Array.from(list.children)) observer.observe(child);
    return () => observer.disconnect();
  });
</script>

<section
  bind:this={section}
  class={cx('nx-toaster', className)}
  data-position={position}
  data-effect={toastEffect}
  aria-label={nxTranslate(lang, 'notifications')}
  popover="manual"
  onpointerenter={store.pause}
  onpointerleave={store.resume}
  onfocusin={store.pause}
  onfocusout={(event) => !event.currentTarget.contains(event.relatedTarget as Node | null) && store.resume()}
>
  <ol bind:this={list} class="nx-toast-list" aria-live="polite">
    {#each items as item (item.id)}
      <ToastItem toast={item} {store} dismissLabel={nxTranslate(lang, 'dismiss')} />
    {/each}
  </ol>
</section>
