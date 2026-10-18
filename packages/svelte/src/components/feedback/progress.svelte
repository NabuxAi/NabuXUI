<script module lang="ts">
  export interface ProgressProps {
    /** Omit for an indeterminate bar. */
    value?: number;
    max?: number;
    tone?: 'accent' | 'brand' | 'success' | 'warning' | 'danger';
    label?: string;
    class?: string;
    style?: string;
  }
</script>

<script lang="ts">
  /**
   * Progress — the Svelte counterpart of ui-react's feedback family's
   * Progress: same DOM, same nx-* classes, same props.
   */
  import { cx } from '../../internal/cx';

  let { value, max = 100, tone, label, class: className, style }: ProgressProps = $props();

  const pct = $derived(value === undefined ? undefined : Math.min(100, Math.max(0, (value / max) * 100)));
  const vars = $derived([pct !== undefined ? `--nx-value: ${pct}` : '', style].filter(Boolean).join('; ') || undefined);
</script>

<progress
  class={cx('nx-progress', className)}
  {value}
  {max}
  aria-label={label}
  data-tone={tone === 'accent' ? undefined : tone}
  style={vars}
></progress>
