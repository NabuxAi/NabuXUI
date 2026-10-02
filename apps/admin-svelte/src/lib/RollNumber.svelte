<script lang="ts">
  /**
   * A rolling number — the `.nx-number` markup contract from ui-core's text
   * components: each digit is a 0–9 column moved to its value (core `numberParts`
   * + `localeDigits`), so a changing figure rolls in the locale's own digits.
   * With `roll` (the default) the element is keyed to its value and wired to
   * core `reveal`, so the digits also roll once each time the figure changes.
   */
  import { localeDigits, numberParts, reveal } from '@nabuxai/ui-core';
  import { app, intlLocale } from '../store.svelte';

  let { value, format = undefined, roll = true }: { value: number; format?: Intl.NumberFormatOptions; roll?: boolean } = $props();

  const locale = $derived(app.lang);
  const digits = $derived(localeDigits(locale));
  const parts = $derived(numberParts(value, locale, format));
  const plain = $derived(new Intl.NumberFormat(intlLocale(), format).format(value));

  // The reveal runs per mounted instance; `{#key value}` remounts on a change.
  const revealOnce = (node: HTMLElement): { destroy: () => void } => {
    const stop = reveal(node, { once: true });
    return { destroy: stop };
  };
</script>

{#key value}
  <span class="nx-number" data-nx-reveal={roll ? '' : undefined} data-value={value} use:revealOnce>
    <span class="nx-visually-hidden">{plain}</span>
    <span class="nx-number-roll" aria-hidden="true">
      {#each parts as part, j (j)}
        {#if part.kind === 'digit'}
          <span class="nx-digit" style:--d={part.value} style:--nx-p={parts.length - 1 - j}>
            <span class="nx-digit-track">{#each digits as digit (digit)}{digit}{/each}</span>
          </span>
        {:else}
          <span class="nx-number-sep">{part.char}</span>
        {/if}
      {/each}
    </span>
  </span>
{/key}
