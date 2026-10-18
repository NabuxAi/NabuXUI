<script lang="ts">
/**
 * NxChart — همتای Vue برای components/chart.tsx در @nabuxai/ui-react.
 *
 * خانوادهٔ نمودار: NxBarChart (صندلی پیش‌فرض)، NxAreaChart، NxDonutChart،
 * NxSparkline و NxProgressRing. قطعات با <script setup> در internal نوشته شده و
 * از این صندلی صادر می‌شوند تا index.ts (export *) به همه برسد.
 */
import { NxBarChart } from '../internal/charts';

export { NxAreaChart, NxBarChart, NxDonutChart, NxProgressRing, NxSparkline } from '../internal/charts';
export type { Series } from '../internal/charts';
export default NxBarChart;
</script>
