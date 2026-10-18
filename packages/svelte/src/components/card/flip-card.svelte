<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface FlipCardProps {
    front: Snippet;
    back: Snippet;
    /** Turn on hover (and keyboard focus), or on click / the corner button. */
    trigger?: 'hover' | 'click';
    flipped?: boolean;
    defaultFlipped?: boolean;
    onFlippedChange?: (flipped: boolean) => void;
    /** Accessible name of the flip button. */
    flipLabel?: string;
    class?: string;
    style?: string;
  }
</script>

<script lang="ts">
  /**
   * FlipCard — the Svelte counterpart of ui-react's card family's FlipCard:
   * the face turned away cannot be reached by keyboard or read out, so it is
   * inert while hidden.
   */
  import { cx } from '../../internal/cx';
  import PlainButton from '../../internal/plain-button.svelte';

  let { front, back, trigger = 'hover', flipped, defaultFlipped = false, onFlippedChange, flipLabel = 'Flip card', class: className, style }: FlipCardProps = $props();

  let internal = $state(defaultFlipped);
  const on = $derived(flipped !== undefined ? flipped : internal);

  function setOn(next: boolean) {
    if (flipped === undefined) internal = next;
    onFlippedChange?.(next);
  }

  const click = $derived(trigger === 'click');

  function flipOnClick(event: MouseEvent) {
    if (!(event.target as Element).closest('a, button, input, select, textarea')) setOn(!on);
  }
</script>

<div
  class={cx('nx-flip', className)}
  data-trigger={trigger}
  data-flipped={click && on ? '' : undefined}
  {style}
  onclick={click ? flipOnClick : undefined}
>
  <div class="nx-flip-inner">
    <div class="nx-flip-face nx-flip-front" {...(click && on ? { inert: true } : {})}>
      {@render front()}
    </div>
    <div class="nx-flip-face nx-flip-back" {...(click && !on ? { inert: true } : {})}>
      {@render back()}
    </div>
  </div>
  {#if click}
    <PlainButton
      icon="sliders"
      variant="secondary"
      size="sm"
      shape="pill"
      pressed={on}
      label={flipLabel}
      style="position: absolute; inset-block-start: var(--nx-space-3); inset-inline-end: var(--nx-space-3); z-index: 2"
      onclick={() => setOn(!on)}
    />
  {/if}
</div>
