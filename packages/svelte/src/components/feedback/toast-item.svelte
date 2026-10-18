<script module lang="ts">
  import type { Toast, ToastStore } from '@nabuxai/ui-core';

  export interface ToastItemProps {
    toast: Toast;
    store: ToastStore;
    dismissLabel: string;
  }
</script>

<script lang="ts">
  /**
   * One rendered toast — private to the feedback family (the React flavour
   * keeps it unexported in feedback.tsx too): swipe-to-dismiss, the closing
   * leave, the action and the close button.
   */
  import { leave, swipe } from '@nabuxai/ui-core';
  import { TONE_ICON } from './tone-icons';
  import Icon from '../../internal/icon.svelte';

  let { toast, store, dismissLabel }: ToastItemProps = $props();

  let host = $state<HTMLLIElement>();

  $effect(() => {
    const el = host;
    if (!el) return;
    return swipe(el, { axis: 'x', threshold: 80, onSwipe: () => store.dismiss(toast.id) });
  });

  $effect(() => {
    if (toast.state !== 'closing' || !host) return;
    let alive = true;
    leave(host).then(() => alive && store.remove(toast.id));
    return () => {
      alive = false;
    };
  });
</script>

<li bind:this={host} class="nx-toast" data-tone={toast.tone} data-state={toast.state} role={toast.tone === 'danger' ? 'alert' : undefined}>
  <span class="nx-toast-icon">
    <Icon name={TONE_ICON[toast.tone] ?? 'bell'} />
  </span>
  <div class="nx-toast-body">
    <p class="nx-toast-title">{toast.title}</p>
    {#if toast.description}
      <p class="nx-toast-description">{toast.description}</p>
    {/if}
  </div>
  {#if toast.action}
    {#if toast.action.href}
      <a class="nx-button nx-toast-action" data-variant="secondary" data-size="xs" href={toast.action.href}>
        <span class="nx-button-label">{toast.action.label}</span>
      </a>
    {:else}
      <button
        type="button"
        class="nx-button nx-toast-action"
        data-variant="secondary"
        data-size="xs"
        onclick={() => {
          toast.action?.onClick?.();
          store.dismiss(toast.id);
        }}
      >
        <span class="nx-button-label">{toast.action?.label}</span>
      </button>
    {/if}
  {/if}
  <button type="button" class="nx-toast-close" aria-label={dismissLabel} onclick={() => store.dismiss(toast.id)}>
    <Icon name="x" />
  </button>
  {#if toast.duration > 0 && toast.state === 'open'}
    <span class="nx-toast-timer" aria-hidden="true" style:--nx-duration={`${toast.duration}ms`}></span>
  {/if}
</li>
