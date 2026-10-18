<script module lang="ts">
  // NxCard — همتای Svelte برای components/card.tsx در @nabuxai/ui-react.
  // هر خانواده یک کامپوننت Svelte است (سولت تک‌کامپوننتی)، پس این فایل صندلیِ
  // خانواده است: کامپوننت‌ها را از ./card/ با همان نام‌های React صادر می‌کند.
  export { default as Card } from './card/card.svelte';
  export { default as FlipCard } from './card/flip-card.svelte';
  export { default as StackCards } from './card/stack-cards.svelte';
  export { default as SwipeStack } from './card/swipe-stack.svelte';
  export { SwipeStackFor } from './card/swipe-stack-for';
  export { default as StatCard } from './card/stat-card.svelte';

  export type { CardProps } from './card/card.svelte';
  export type { FlipCardProps } from './card/flip-card.svelte';
  export type { StackCardsProps } from './card/stack-cards.svelte';
  export type { SwipeStackProps, SwipeVerdict } from './card/swipe-stack-for';
  export type { StatCardProps } from './card/stat-card.svelte';
</script>
