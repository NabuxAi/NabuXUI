<script lang="ts">
  /**
   * Textarea — همتای Svelte برای Textarea در ui-react/components/form.tsx.
   */
  import { autogrow } from '@nabuxai/ui-core';
  import { nxB } from '../../internal/bind';
  import { cx } from '../../internal/cx';
  import { fieldProps, useField } from '../../internal/field';
  import type { HTMLTextareaAttributes } from 'svelte/elements';

  interface Props extends HTMLTextareaAttributes {
    invalid?: boolean;
    /** Grow with the content up to this many lines. */
    maxRows?: number;
    class?: string;
    style?: string;
  }

  let { invalid, maxRows, class: className, style, ...rest }: Props = $props();

  const field = useField();
  const wiring = $derived(fieldProps(rest, field, invalid));
  const vars = $derived([maxRows ? `--nx-textarea-max: ${maxRows}lh` : '', style].filter(Boolean).join('; ') || undefined);

  // autogrow با cleanup مستقیم — همان isBrowser گره‌خورده به چرخهٔ عمر عنصر.
  const grow = nxB(autogrow);
</script>

<textarea use:grow class={cx('nx-textarea', className)} style={vars} {...rest} {...wiring}></textarea>
