<script module lang="ts">
  // NxChart — همتای Svelte برای components/chart.tsx در @nabuxai/ui-react.
  // هر خانواده یک کامپوننت Svelte است (سولت تک‌کامپوننتی)، پس این فایل صندلیِ
  // خانواده است: کامپوننت‌ها را از ./chart/ با همان نام‌های React صادر می‌کند.
  export { default as BarChart } from './chart/bar-chart.svelte';
  export { default as AreaChart } from './chart/area-chart.svelte';
  export { default as DonutChart } from './chart/donut-chart.svelte';
  export { default as Sparkline } from './chart/sparkline.svelte';
  export { default as ProgressRing } from './chart/progress-ring.svelte';

  export type { BarChartProps } from './chart/bar-chart.svelte';
  export type { AreaChartProps } from './chart/area-chart.svelte';
  export type { DonutChartProps } from './chart/donut-chart.svelte';
  export type { SparklineProps } from './chart/sparkline.svelte';
  export type { ProgressRingProps } from './chart/progress-ring.svelte';
  export type { Series } from './chart/data-table.svelte';
</script>
