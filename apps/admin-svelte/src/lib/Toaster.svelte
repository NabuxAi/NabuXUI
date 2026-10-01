<script lang="ts">
  /**
   * The toaster — the markup React's `Toaster` renders, over the core toast
   * store: a manual popover in the top layer, the list re-laid-out with
   * `stackToasts` after every change, and the CSS animating the closing state
   * away while the store drops the toast after its grace period.
   */
  import { onMount } from 'svelte';
  import { stackToasts, toasts, translate, type IconName, type Toast, type ToastTone } from '@nabuxai/ui-core';
  import { app } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  let list: HTMLOListElement;
  let section: HTMLElement;
  let items = $state<readonly Toast[]>([]);

  const TONE_ICON: Record<ToastTone, IconName> = {
    neutral: 'bell',
    accent: 'sparkles',
    success: 'check-circle',
    warning: 'alert-triangle',
    danger: 'alert-circle',
    info: 'info',
  };

  onMount(() => {
    items = toasts.getSnapshot();
    const unsubscribe = toasts.subscribe((next) => (items = next));
    try {
      section?.showPopover?.();
    } catch {
      /* already open */
    }
    return unsubscribe;
  });

  // Runs after the DOM catches up with every change of the list.
  $effect(() => {
    void items;
    if (list) stackToasts(list);
  });

  const dismiss = (id: string) => toasts.dismiss(id);
</script>

<section
  bind:this={section}
  class="nx-toaster"
  popover="manual"
  aria-label={translate(app.lang, 'notifications')}
  onpointerenter={() => toasts.pause()}
  onpointerleave={() => toasts.resume()}
  onfocusin={() => toasts.pause()}
  onfocusout={() => toasts.resume()}
>
  <ol bind:this={list} class="nx-toast-list" aria-live="polite">
    {#each items as item (item.id)}
      <li class="nx-toast" data-tone={item.tone} data-state={item.state} role={item.tone === 'danger' ? 'alert' : undefined}>
        <span class="nx-toast-icon"><NxIcon name={TONE_ICON[item.tone]} /></span>
        <div class="nx-toast-body">
          <p class="nx-toast-title">{item.title}</p>
          {#if item.description}<p class="nx-toast-description">{item.description}</p>{/if}
        </div>
        <button type="button" class="nx-toast-close" aria-label={translate(app.lang, 'dismiss')} onclick={() => dismiss(item.id)}>
          <NxIcon name="x" />
        </button>
        {#if item.duration > 0 && item.state === 'open'}
          <span class="nx-toast-timer" aria-hidden="true" style:--nx-duration="{item.duration}ms"></span>
        {/if}
      </li>
    {/each}
  </ol>
</section>
