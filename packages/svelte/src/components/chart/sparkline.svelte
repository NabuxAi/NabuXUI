<script module lang="ts">
  import { reveal, type RevealOptions } from '@nabuxai/ui-core';
  import { nxB } from '../../internal/bind';

  export interface SparklineProps {
    data: number[];
    /** Colours the line: rising (good) or falling (bad). */
    trend?: 'up' | 'down';
    area?: boolean;
    class?: string;
  }

  /** گزینهٔ پایدار — تا اکشن فقط با تغییر وابستگی‌هایش دوباره وصل شود. */
  const REVEAL_ONCE: RevealOptions = { once: true };
  const nxSparklineReveal = nxB(reveal, REVEAL_ONCE);
</script>

<script lang="ts">
  /**
   * Sparkline — the Svelte counterpart of ui-react's chart family's
   * Sparkline: same DOM, same nx-* classes, same props.
   */
  import { areaPath, linePath, linearScale } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';

  let { data, trend, area = true, class: className }: SparklineProps = $props();

  const width = 120;
  const height = 40;
  const min = $derived(Math.min(...data));
  const max = $derived(Math.max(...data));
  const x = $derived(linearScale([0, Math.max(1, data.length - 1)], [2, width - 4]));
  const y = $derived(linearScale([min, max === min ? min + 1 : max], [height - 3, 3]));
  const points = $derived(data.map((v, i) => [x(i), y(v)] as const));
  const last = $derived(points[points.length - 1]);
</script>

<svg
  class={cx('nx-sparkline', 'nx-chart', className)}
  data-nx-reveal=""
  data-trend={trend}
  viewBox={`0 0 ${width} ${height}`}
  preserveAspectRatio="none"
  aria-hidden="true"
  use:nxSparklineReveal
>
  {#if area}
    <path class="nx-chart-area" d={areaPath(points, height, true)} />
  {/if}
  <path class="nx-chart-line" d={linePath(points, true)} pathLength={1} />
  {#if last}
    <circle class="nx-chart-point" cx={last[0]} cy={last[1]} r="3" data-end="" />
  {/if}
</svg>
