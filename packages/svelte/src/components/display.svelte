<script module lang="ts">
  // NxDisplay — همتای Svelte برای components/display.tsx در @nabuxai/ui-react.
  // هر خانواده یک کامپوننت Svelte است (سولت تک‌کامپوننتی)، پس این فایل صندلیِ
  // خانواده است: کامپوننت‌ها را از ./display/ با همان نام‌های React صادر می‌کند.
  export { default as Avatar } from './display/avatar.svelte';
  export { default as AvatarGroup } from './display/avatar-group.svelte';
  export { default as Kbd } from './display/kbd.svelte';
  export { default as Rating } from './display/rating.svelte';
  export { default as Steps } from './display/steps.svelte';
  export { default as Marquee } from './display/marquee.svelte';
  export { default as Grid } from './display/grid.svelte';
  export { default as Bento } from './display/bento.svelte';
  export { default as BentoItem } from './display/bento-item.svelte';
  export { default as Divider } from './display/divider.svelte';

  export type { AvatarProps } from './display/avatar.svelte';
  export type { AvatarGroupProps } from './display/avatar-group.svelte';
  export type { KbdProps } from './display/kbd.svelte';
  export type { RatingProps } from './display/rating.svelte';
  export type { StepsProps } from './display/steps.svelte';
  export type { MarqueeProps } from './display/marquee.svelte';
  export type { GridProps } from './display/grid.svelte';
  export type { BentoProps } from './display/bento.svelte';
  export type { BentoItemProps } from './display/bento-item.svelte';
  export type { DividerProps } from './display/divider.svelte';
</script>
