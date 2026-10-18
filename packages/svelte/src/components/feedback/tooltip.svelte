<script module lang="ts">
  import type { Snippet } from 'svelte';

  /** What the trigger snippet receives: spread it onto the described element. */
  export type TooltipAnchorProps = {
    'aria-describedby'?: string;
    onpointerenter: () => void;
    onpointerleave: () => void;
    onfocus: () => void;
    onblur: () => void;
  };

  export interface TooltipProps {
    content: string | Snippet;
    /**
     * The trigger, as a parameterized snippet — Svelte's answer to React's
     * cloneElement wiring: the markup stays yours, no wrapper element:
     *
     *   <Tooltip content="Save the document">
     *     {#snippet trigger(anchor)}
     *       <button {...anchor} onclick={save}>Save</button>
     *     {/snippet}
     *   </Tooltip>
     */
    children: Snippet<[TooltipAnchorProps]>;
    side?: 'top' | 'bottom' | 'start' | 'end';
    /** Hover delay before showing, ms (focus shows at once). */
    delay?: number;
    disabled?: boolean;
  }

  const SIDES = { top: 'top', bottom: 'bottom', start: 'inline-start', end: 'inline-end' } as const;

  export function showTop(el: HTMLElement) {
    if (typeof el.showPopover === 'function') {
      try {
        el.showPopover();
        return;
      } catch {
        /* already shown */
      }
    }
    el.setAttribute('data-open', '');
  }

  export function hideTop(el: HTMLElement) {
    el.removeAttribute('data-open');
    try {
      if (el.matches(':popover-open')) el.hidePopover();
    } catch {
      /* no popover support */
    }
  }

  export { SIDES as TOOLTIP_SIDES };
</script>

<script lang="ts">
  /**
   * Tooltip — the Svelte counterpart of ui-react's feedback family's Tooltip.
   * React clones the child element to hang its handlers on; Svelte has no
   * cloneElement, so the children snippet receives the anchor props to spread
   * onto the trigger — same DOM (no wrapper), same aria wiring.
   */
  import { place } from '@nabuxai/ui-core';

  let { content, side = 'top', delay = 350, disabled, children }: TooltipProps = $props();

  const id = $props.id();
  let tip = $state<HTMLDivElement>();
  let open = $state(false);
  let timer: ReturnType<typeof setTimeout> | undefined;

  const schedule = (next: boolean, wait: number) => {
    clearTimeout(timer);
    timer = setTimeout(() => (open = next), wait);
  };

  const anchorProps = $derived<TooltipAnchorProps>({
    'aria-describedby': disabled ? undefined : id,
    onpointerenter: () => schedule(true, delay),
    onpointerleave: () => schedule(false, 100),
    onfocus: () => schedule(true, 0),
    onblur: () => schedule(false, 0),
  });

  // Anything the timer had planned dies with the component.
  $effect(() => () => clearTimeout(timer));

  $effect(() => {
    const el = tip;
    if (!el) return;
    // The trigger is the single element the snippet renders right before us.
    const anchor = el.previousElementSibling;
    if (!open || disabled) {
      hideTop(el);
      return;
    }
    showTop(el);
    if (!(anchor instanceof HTMLElement)) return;
    const stop = place(anchor, el, { side: SIDES[side], align: 'center', offset: 8 });
    const onKey = (event: KeyboardEvent) => event.key === 'Escape' && (open = false);
    document.addEventListener('keydown', onKey);
    return () => {
      stop();
      document.removeEventListener('keydown', onKey);
    };
  });
</script>

{@render children(anchorProps)}
<div
  bind:this={tip}
  {id}
  role="tooltip"
  class="nx-tooltip"
  popover="manual"
  onpointerenter={() => clearTimeout(timer)}
  onpointerleave={() => schedule(false, 100)}
>
  {#if typeof content === 'string'}{content}{:else}{@render content()}{/if}
</div>
