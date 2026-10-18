<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface DialogProps {
    open?: boolean;
    defaultOpen?: boolean;
    onOpenChange?: (open: boolean) => void;
    /**
     * The opening control, rendered as-is. Spread the attributes the snippet
     * receives onto your element — they carry the aria state and the opening
     * click:
     *
     *   {#snippet trigger(attrs)}
     *     <Button {...attrs}>Open</Button>
     *   {/snippet}
     */
    trigger?: Snippet<[Record<string, unknown>]>;
    title?: string | Snippet;
    description?: string | Snippet;
    footer?: string | Snippet;
    size?: 'sm' | 'md' | 'lg' | 'xl' | 'full';
    variant?: 'modal' | 'drawer' | 'sheet';
    /** Drawer edge: inline end (default) or start. */
    side?: 'start' | 'end';
    /** Close when the backdrop is clicked (default true). */
    closeOnBackdrop?: boolean;
    hideClose?: boolean;
    class?: string;
    style?: string;
    children?: Snippet;
  }
</script>

<script lang="ts">
  import type { MessageKey } from '@nabuxai/ui-core';
  import { nextId } from '@nabuxai/ui-core';
  import { nxTranslate } from '../../i18n';
  import { nxLang } from '../../internal/locale';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import Content from '../../internal/content.svelte';

  let {
    open,
    defaultOpen = false,
    onOpenChange,
    trigger,
    title,
    description,
    footer,
    size,
    variant = 'modal',
    side,
    closeOnBackdrop = true,
    hideClose,
    class: className,
    style,
    children,
  }: DialogProps = $props();

  /** The component's own words, in the page's language. */
  const t = (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(nxLang(), key, params);

  const base = nextId('nx-dialog');

  // svelte-ignore state_referenced_locally
  // defaultOpen seeds the internal state once, exactly like the React seat's useState.
  let internal = $state(defaultOpen);
  /** Controlled while `open` is passed, internal otherwise. */
  let isOpen = $derived(open !== undefined ? open : internal);

  const setOpen = (next: boolean) => {
    if (open === undefined) internal = next;
    onOpenChange?.(next);
  };

  let ref = $state<HTMLDialogElement>();

  $effect(() => {
    const el = ref;
    if (!el) return;
    if (isOpen && !el.open) el.showModal();
    if (!isOpen && el.open) el.close();
  });

  $effect(() => {
    const el = ref;
    if (!el) return;
    // Escape, form[method=dialog] and .close() all end here.
    const onClose = () => setOpen(false);
    el.addEventListener('close', onClose);
    return () => el.removeEventListener('close', onClose);
  });

  const onBackdrop = (event: MouseEvent & { currentTarget: EventTarget & HTMLDialogElement }) => {
    if (!closeOnBackdrop || event.target !== event.currentTarget) return;
    const r = event.currentTarget.getBoundingClientRect();
    const inside = event.clientX >= r.left && event.clientX <= r.right && event.clientY >= r.top && event.clientY <= r.bottom;
    if (!inside) event.currentTarget.close();
  };

  // Everything the trigger element needs: spread onto it via the snippet.
  const triggerAttrs = $derived({
    'aria-haspopup': 'dialog',
    onclick: () => setOpen(true),
  });
</script>

{#if trigger}
  {@render trigger(triggerAttrs)}
{/if}

<dialog
  class={cx('nx-dialog', className)}
  data-size={size && size !== 'md' ? size : undefined}
  data-variant={variant === 'modal' ? undefined : variant}
  data-side={side === 'start' ? 'start' : undefined}
  aria-labelledby={title ? `${base}-title` : undefined}
  aria-describedby={description ? `${base}-description` : undefined}
  {style}
  onclick={onBackdrop}
  bind:this={ref}
>
  {#if title || description}
    <header class="nx-dialog-header">
      {#if title}
        <h2 class="nx-dialog-title" id={`${base}-title`}>
          <Content of={title} />
        </h2>
      {/if}
      {#if description}
        <p class="nx-dialog-description" id={`${base}-description`}>
          <Content of={description} />
        </p>
      {/if}
    </header>
  {/if}
  <div class="nx-dialog-body">{@render children?.()}</div>
  {#if footer}
    <footer class="nx-dialog-footer">
      <Content of={footer} />
    </footer>
  {/if}
  {#if !hideClose}
    <button type="button" class="nx-dialog-close" aria-label={t('close')} onclick={() => ref?.close()}>
      <Icon name="x" />
    </button>
  {/if}
</dialog>
