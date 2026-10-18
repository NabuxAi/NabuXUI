<script module lang="ts">
  export type BackdropVariant = 'aurora' | 'grid' | 'stars' | 'beams' | 'dots' | 'mesh' | 'stripes';

  export interface BackdropProps {
    variant?: BackdropVariant;
    class?: string;
    style?: string;
  }
</script>

<script lang="ts">
  import { cx } from '../../internal/cx';

  let { variant = 'aurora', class: className, style }: BackdropProps = $props();
</script>

<!-- A decorative backdrop, pure CSS. Place it first inside a positioned section. -->
<div class={cx('nx-backdrop', className)} data-variant={variant} aria-hidden="true" {style}>
  <i></i>
  <i></i>
  <i></i>
</div>
