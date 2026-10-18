<script lang="ts">
  /**
   * WordRotate — همتای Svelte برای WordRotate در ui-react/components/text.tsx:
   * واژه‌ها یکی‌درمیان با انیمیشن ورود/خروج عوض می‌شوند.
   */
  import { cx } from '../../internal/cx';

  interface Props {
    words: string[];
    /** Time each word stays, ms. */
    interval?: number;
    class?: string;
    style?: string;
  }

  let { words, interval = 2600, class: className, style }: Props = $props();

  let index = $state(0);
  let previous = $state<number | null>(null);
  let width = $state<number | null>(null);
  let paused = $state(false);
  let current = $state<HTMLSpanElement | undefined>(undefined);

  $effect(() => {
    if (words.length < 2 || paused) return;
    const id = setInterval(() => {
      if (document.hidden) return;
      previous = index;
      index = (index + 1) % words.length;
    }, interval);
    return () => clearInterval(id);
  });

  $effect(() => {
    void index;
    void words;
    if (current) width = current.scrollWidth;
  });

  const vars = $derived([width ? `--nx-word-width: ${width}px` : '', style].filter(Boolean).join('; ') || undefined);
  const exiting = $derived(previous !== null && previous !== index);
</script>

<!-- svelte-ignore a11y_no_static_element_interactions -->
<!-- توقف با هاور همان DOM ری‌اکت است: span بدون نقش. -->
<span
  class={cx('nx-word-rotate', className)}
  style={vars}
  onpointerenter={() => (paused = true)}
  onpointerleave={() => (paused = false)}
>
  <span class="nx-visually-hidden">{words.join('، ')}</span>
  {#if exiting}
    {#key `out-${previous}-${index}`}
      <span class="nx-word-rotate-word" data-state="exit" aria-hidden="true" onanimationend={() => (previous = null)}>
        {words[previous ?? 0]}
      </span>
    {/key}
  {/if}
  {#key `in-${index}`}
    <span bind:this={current} class="nx-word-rotate-word" data-state={previous === null && index === 0 ? undefined : 'enter'} aria-hidden="true">
      {words[index]}
    </span>
  {/key}
</span>
