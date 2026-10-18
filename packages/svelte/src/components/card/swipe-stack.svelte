<script module lang="ts">
  export type { SwipeStackProps, SwipeVerdict } from './swipe-stack-for';
</script>

<script lang="ts">
  /**
   * SwipeStack — the Svelte counterpart of ui-react's card family's
   * SwipeStack: drag the top card aside (or use the buttons / the keyboard
   * path they provide) and the deck reshuffles.
   */
  import { leave, swipe } from '@nabuxai/ui-core';
  import type { SwipeStackProps, SwipeVerdict } from './swipe-stack-for';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import PlainButton from '../../internal/plain-button.svelte';

  let {
    items,
    renderItem,
    getKey = (_, i) => i,
    onSwipe,
    loop = true,
    actions = true,
    acceptLabel,
    rejectLabel,
    class: className,
  }: SwipeStackProps<unknown> = $props();

  const lang = nxLang();
  // The first deck is dealt synchronously (React's useState initializer); a
  // fresh one whenever the items themselves change (React's useEffect reset).
  // svelte-ignore state_referenced_locally
  let order = $state<number[]>(items.map((_, i) => i));
  let leaving = $state<number | null>(null);
  // Seat 0 is always the top card, whichever keyed article currently sits in it.
  let seats = $state<Array<HTMLElement | null>>([]);

  $effect(() => {
    void items;
    order = items.map((_, i) => i);
  });

  function decide(verdict: SwipeVerdict) {
    const top = order[0];
    const el = seats[0];
    if (top === undefined || leaving !== null || !el) return;
    el.style.setProperty('--nx-exit-x', verdict === 'accept' ? '1' : '-1');
    leaving = top;
    onSwipe?.(items[top]!, verdict);
    leave(el, { value: 'leaving', timeout: 700 }).then(() => {
      el.style.removeProperty('--nx-dx');
      el.style.removeProperty('--nx-dy');
      el.style.removeProperty('--nx-drag-rotate');
      el.style.removeProperty('--nx-drag-progress');
      leaving = null;
      order = loop ? [...order.slice(1), order[0]!] : order.slice(1);
    });
  }

  $effect(() => {
    void order;
    void leaving;
    const el = seats[0];
    if (!el) return;
    return swipe(el, { axis: 'x', threshold: 110, rotate: 9, onSwipe: (direction) => decide(direction === 'right' ? 'accept' : 'reject') });
  });
</script>

<div class={className}>
  <div class="nx-swipe-stack">
    {#each order.slice(0, 4) as itemIndex, depth (getKey(items[itemIndex]!, itemIndex))}
      {@const item = items[itemIndex]!}
      {@const isTop = depth === 0}
      <article
        bind:this={seats[depth]}
        class="nx-swipe-card"
        data-state={leaving === itemIndex ? 'leaving' : isTop ? 'top' : undefined}
        aria-hidden={isTop ? undefined : true}
        style:--i={depth}
      >
        {@render renderItem(item)}
        {#if isTop}
          <span class="nx-swipe-hint" data-side="accept" aria-hidden="true">{acceptLabel ?? nxTranslate(lang, 'accept')}</span>
          <span class="nx-swipe-hint" data-side="reject" aria-hidden="true">{rejectLabel ?? nxTranslate(lang, 'reject')}</span>
        {/if}
      </article>
    {/each}
  </div>
  {#if actions && order.length > 0}
    <div class="nx-swipe-actions">
      <PlainButton
        icon="x"
        variant="secondary"
        size="lg"
        shape="pill"
        label={rejectLabel ?? nxTranslate(lang, 'reject')}
        onclick={() => decide('reject')}
      />
      <PlainButton
        icon="heart"
        variant="primary"
        size="lg"
        shape="pill"
        label={acceptLabel ?? nxTranslate(lang, 'accept')}
        onclick={() => decide('accept')}
      />
    </div>
  {/if}
</div>
