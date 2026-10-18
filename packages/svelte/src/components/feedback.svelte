<script module lang="ts">
  // NxFeedback — همتای Svelte برای components/feedback.tsx در @nabuxai/ui-react.
  // هر خانواده یک کامپوننت Svelte است (سولت تک‌کامپوننتی)، پس این فایل صندلیِ
  // خانواده است: کامپوننت‌ها را از ./feedback/ با همان نام‌های React صادر می‌کند.
  export { toast } from '@nabuxai/ui-core';

  export { default as Toaster } from './feedback/toaster.svelte';
  export { default as Alert } from './feedback/alert.svelte';
  export { default as Badge } from './feedback/badge.svelte';
  export { default as Tooltip } from './feedback/tooltip.svelte';
  export { default as Progress } from './feedback/progress.svelte';
  export { default as Skeleton } from './feedback/skeleton.svelte';
  export { default as Spinner } from './feedback/spinner.svelte';
  export { default as OrbitLoader } from './feedback/orbit-loader.svelte';
  export { default as DotsLoader } from './feedback/dots-loader.svelte';
  export { default as CuneiformLoader } from './feedback/cuneiform-loader.svelte';

  export { WEDGES } from './feedback/cuneiform-loader.svelte';

  export type { ToasterPosition, ToasterProps } from './feedback/toaster.svelte';
  export type { AlertProps } from './feedback/alert.svelte';
  export type { BadgeProps } from './feedback/badge.svelte';
  export type { TooltipProps, TooltipAnchorProps } from './feedback/tooltip.svelte';
  export type { ProgressProps } from './feedback/progress.svelte';
  export type { SkeletonProps } from './feedback/skeleton.svelte';
  export type { LoaderProps } from './feedback/spinner.svelte';
</script>
