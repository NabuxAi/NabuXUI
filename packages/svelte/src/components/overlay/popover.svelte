<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface PopoverProps {
    /**
     * The toggling control, rendered as-is. Spread the attributes the snippet
     * receives onto your element — they carry the popover wiring and the
     * aria state:
     *
     *   {#snippet trigger(attrs)}
     *     <Button {...attrs}>Open</Button>
     *   {/snippet}
     */
    trigger: Snippet<[Record<string, unknown>]>;
    children?: Snippet;
    side?: 'top' | 'bottom' | 'start' | 'end';
    align?: 'start' | 'center' | 'end';
    /** Name for the panel, read when focus enters it. */
    label?: string;
    class?: string;
  }
</script>

<script lang="ts">
  import { nextId, place } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';

  let { trigger, children, side = 'bottom', align = 'center', label, class: className }: PopoverProps = $props();

  const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

  const id = nextId('nx-pop');
  let panel = $state<HTMLDivElement>();
  let open = $state(false);

  const button = () => document.querySelector<HTMLElement>(`[data-nx-pop-trigger="${id}"]`);

  // The popover opens and light-dismisses natively; keep our view in step.
  // (The popovertarget invoker travels on the trigger attrs instead of being
  // set here — Svelte spells the attribute the same everywhere.)
  $effect(() => {
    const el = panel;
    if (!el) return;
    const onToggle = (event: Event) => (open = (event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  });

  $effect(() => {
    if (!open || !panel || !side || !align) return;
    const anchor = button();
    if (!anchor) return;
    const sides = { top: 'top', bottom: 'bottom', start: 'inline-start', end: 'inline-end' } as const;
    return place(anchor, panel, { side: sides[side], align, offset: 8 });
  });

  // Everything the trigger element needs: spread onto it via the snippet.
  const triggerAttrs = $derived({
    'data-nx-pop-trigger': id,
    'aria-expanded': open,
    'aria-controls': id,
    ...(supportsPopover()
      ? { popovertarget: id }
      : {
          onclick: () => {
            const el = panel;
            el?.toggleAttribute('data-open');
            open = !!el?.hasAttribute('data-open');
          },
        }),
  });
</script>

{@render trigger(triggerAttrs)}

<div class={cx('nx-popover', className)} id={id} role="dialog" aria-label={label} popover="auto" bind:this={panel}>
  {@render children?.()}
</div>
