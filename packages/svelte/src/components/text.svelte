<script module lang="ts">
  /**
   * NxText — همتای Svelte برای components/text.tsx در @nabuxai/ui-react.
   * سولت تک‌کامپوننتی است، پس این صندلیِ خانواده است: کامپوننت‌ها را از
   * ./text/ با همان نام‌هایی که همتای React صادر می‌کند بیرون می‌دهد.
   * `useReveal` ری‌اکت اینجا اکشن است: nxBehavior/nxB از internal.
   */
  export { default as Reveal, REVEAL_ONCE, type RevealVariant } from './text/reveal.svelte';
  export { default as GradientText } from './text/gradient-text.svelte';
  export { default as ShimmerText } from './text/shimmer-text.svelte';
  export { default as TextReveal } from './text/text-reveal.svelte';
  export { default as ScrambleText } from './text/scramble-text.svelte';
  export { default as WordRotate } from './text/word-rotate.svelte';
  export { default as NumberTicker } from './text/number-ticker.svelte';
  export { default as Highlight, HIGHLIGHT_MARKS } from './text/highlight.svelte';
</script>
