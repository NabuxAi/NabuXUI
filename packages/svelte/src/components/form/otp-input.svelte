<script lang="ts">
  /**
   * OtpInput — همتای Svelte برای OtpInput در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import { fieldProps, useField } from '../../internal/field';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import type { HTMLInputAttributes } from 'svelte/elements';

  interface Props extends Omit<HTMLInputAttributes, 'value' | 'maxlength' | 'size' | 'onchange' | 'oninput'> {
    length?: number;
    value?: string;
    defaultValue?: string;
    onValueChange?: (value: string) => void;
    /** Called once every slot is filled. */
    onComplete?: (value: string) => void;
    /** Draw a dash after this many slots (3 gives 123–456). */
    separatorAfter?: number;
    invalid?: boolean;
    /** Accept letters as well as digits. */
    alphanumeric?: boolean;
    class?: string;
  }

  let {
    length = 6,
    defaultValue = '',
    value = $bindable(defaultValue),
    onValueChange,
    onComplete,
    separatorAfter,
    invalid,
    alphanumeric,
    class: className,
    'aria-label': ariaLabel,
    'aria-labelledby': ariaLabelledBy,
    onfocus,
    onblur,
    ...rest
  }: Props = $props();

  const field = useField();
  const wiring = $derived(fieldProps(rest, field, invalid));

  let focused = $state(false);

  // ارقام فارسی/عربی خودشان تایپ می‌شوند؛ ذخیره ASCII.
  const normalise = (raw: string) =>
    raw
      .replace(alphanumeric ? /[^0-9a-z]/gi : /[^0-9۰-۹٠-٩]/g, '')
      .replace(/[۰-۹]/g, (d) => String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d)))
      .replace(/[٠-٩]/g, (d) => String('٠١٢٣٤٥٦٧٨٩'.indexOf(d)))
      .slice(0, length);

  function handleChange(event: Event & { currentTarget: EventTarget & HTMLInputElement }) {
    const next = normalise(event.currentTarget.value);
    value = next;
    onValueChange?.(next);
    if (next.length === length) onComplete?.(next);
  }

  const active = $derived(Math.min(value.length, length - 1));
  const ariaLabelResolved = $derived(ariaLabel ?? (ariaLabelledBy ? undefined : nxTranslate(nxLang(), 'code')));
</script>

<div class={cx('nx-otp', className)} data-invalid={invalid || wiring['aria-invalid'] ? '' : undefined}>
  <input
    class="nx-otp-input"
    {value}
    onchange={handleChange}
    oninput={handleChange}
    onfocus={(event) => {
      focused = true;
      onfocus?.(event);
    }}
    onblur={(event) => {
      focused = false;
      onblur?.(event);
    }}
    inputmode={alphanumeric ? 'text' : 'numeric'}
    autocomplete="one-time-code"
    maxlength={length}
    aria-label={ariaLabelResolved}
    spellcheck={false}
    {...rest}
    {...wiring}
  />
  <div class="nx-otp-slots" aria-hidden="true">
    {#each Array.from({ length }) as _, i (i)}
      <span style="display: contents">
        <span class="nx-otp-slot" data-filled={value[i] ? '' : undefined} data-active={focused && i === active ? '' : undefined}>
          {#if value[i]}{#key value[i] + i}<span>{value[i]}</span>{/key}{/if}
        </span>
        {#if separatorAfter && i === separatorAfter - 1 && i < length - 1}<span class="nx-otp-sep"></span>{/if}
      </span>
    {/each}
  </div>
</div>
