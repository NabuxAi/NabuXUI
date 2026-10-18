<script lang="ts">
  /**
   * Field — همتای Svelte برای Field در ui-react/components/form.tsx:
   * برچسب، راهنما و خطا را به کنترلی که به‌عنوان children می‌آید سیم‌کشی می‌کند.
   */
  import { iconSvg, nextId } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import type { NxField } from '../../internal/field';
  import { setContext } from 'svelte';
  import type { Snippet } from 'svelte';

  /** متن ساده یا محتوای دلخواه — همتای ReactNode در React. */
  type Content = string | Snippet;

  interface Props {
    label?: Content;
    hint?: Content;
    /** پیام خطا؛ کنترل را هم نامعتبر می‌کند. */
    error?: Content;
    required?: boolean;
    disabled?: boolean;
    /** id کنترل (در نبودش تولید می‌شود). */
    id?: string;
    class?: string;
    style?: string;
    /** خودِ کنترل (input، select، …) — با id سیم‌کشی می‌شود. */
    children?: Snippet;
  }

  let { label, hint, error, required, disabled, id, class: className, style, children }: Props = $props();

  // $derived تا تغییر id پیگیری شود؛ در نبود id، nextId فقط یک‌بار ارزیابی می‌شود (وابستگی فقط id است).
  const controlId = $derived(id ?? nextId('nx-field'));
  const hintId = $derived(hint ? `${controlId}-hint` : undefined);
  const errorId = $derived(error ? `${controlId}-error` : undefined);

  // بافت زمینه با گتر زنده — کنترل‌های همین خانواده با useField می‌خوانندش.
  setContext<NxField>('nx-field', {
    get id() {
      return controlId;
    },
    get hintId() {
      return hintId;
    },
    get errorId() {
      return errorId;
    },
    get invalid() {
      return !!error;
    },
    get required() {
      return required;
    },
    get disabled() {
      return disabled;
    },
  });
</script>

<div class={cx('nx-field', className)} data-invalid={error ? '' : undefined} style={style || undefined}>
  {#if label}
    <label class="nx-label" for={controlId}>
      {#if typeof label === 'function'}{@render label()}{:else}{label}{/if}
      {#if required}<span class="nx-label-required" aria-hidden="true">*</span>{/if}
    </label>
  {/if}
  {#if hint}
    <p class="nx-hint" id={hintId}>
      {#if typeof hint === 'function'}{@render hint()}{:else}{hint}{/if}
    </p>
  {/if}
  {@render children?.()}
  {#if error}
    <p class="nx-error" id={errorId}>
      {@html iconSvg('alert-circle')}
      {#if typeof error === 'function'}{@render error()}{:else}{error}{/if}
    </p>
  {/if}
</div>
