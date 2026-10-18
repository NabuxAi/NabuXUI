<script module lang="ts">
  /** گزینه‌های پایدار — تا اکشن فقط با تغییر وابستگی‌هایش دوباره وصل شود. */
  export type ButtonVariant = 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'gold' | 'glow' | 'inverse' | 'link';
  export type ButtonSize = 'xs' | 'sm' | 'md' | 'lg' | 'xl';
  export type ButtonStatus = 'idle' | 'loading' | 'success' | 'error';

  const MAGNETIC = {} as const;
  const NO_OPTS = undefined;
</script>

<script lang="ts">
  /**
   * Button — همتای Svelte برای Button در ui-react/components/button.tsx:
   * همان DOM، همان data-attrs؛ رویدادها callback-prop (onclick مثل ری‌اکت)
   * و magnetic/ripple از طریق اکشن nxBehavior روی هسته وصل می‌شوند.
   */
  import { magnetic as magneticBehavior, ripple as rippleBehavior, type IconName } from '@nabuxai/ui-core';
  import { nxBehavior } from '../../internal/actions';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import ButtonStatusMarks from './button-status-marks.svelte';
  import type { Snippet } from 'svelte';
  import type { HTMLAnchorAttributes, HTMLButtonAttributes } from 'svelte/elements';

  interface ButtonOwnProps {
    variant?: ButtonVariant;
    size?: ButtonSize;
    shape?: 'rounded' | 'pill' | 'square';
    /** برچسب را بدون تغییر عرض به اسپینر/تیک/ضربدر تبدیل می‌کند. */
    status?: ButtonStatus;
    /** میان‌بر status="loading". */
    loading?: boolean;
    /** درخشش هنگام هاور، یا بیرون‌خیزدن برچسب برای کپی. */
    effect?: 'shine' | 'slide';
    /** به سمت نشانگر متمایل شود. */
    magnetic?: boolean;
    /** موج از نقطهٔ فشرده‌شدن. */
    ripple?: boolean;
    /** آیکن سربدا: نام آیکن داخلی یا هر اسنیپت. */
    icon?: IconName | Snippet;
    iconEnd?: IconName | Snippet;
    /** دکمهٔ مربعی فقط-آیکن؛ aria-label بدهید. */
    iconOnly?: boolean;
    block?: boolean;
    /** رندر به‌شکل لینک (انکر ساده؛ این طعم صندلی پرایدر ندارد). */
    href?: string;
    /** متن ساده — همتای children رشته‌ای ری‌اکت؛ data-text افکت slide از همین می‌آید. */
    text?: string;
    /** محتوای دلخواه — همتای children نودی در React. */
    children?: Snippet;
    class?: string;
    style?: string;
  }

  interface Props extends Omit<HTMLButtonAttributes, keyof ButtonOwnProps>, ButtonOwnProps {
    /** فقط حالت لینک: مثل Pick انکری React. */
    target?: HTMLAnchorAttributes['target'];
    rel?: HTMLAnchorAttributes['rel'];
    download?: HTMLAnchorAttributes['download'];
  }

  type ClickEvent = Parameters<NonNullable<HTMLButtonAttributes['onclick']>>[0];

  let {
    variant = 'secondary',
    size = 'md',
    shape,
    status,
    loading,
    effect,
    magnetic,
    ripple,
    icon,
    iconEnd,
    iconOnly,
    block,
    href,
    text,
    children,
    class: className,
    style,
    type,
    disabled,
    onclick,
    ...rest
  }: Props = $props();

  const resolved: ButtonStatus = $derived(loading ? 'loading' : (status ?? 'idle'));
  const busy = $derived(resolved === 'loading');
  const hasContent = $derived(children !== undefined || text !== undefined);

  // دکمهٔ شلوغ فوکوس‌پذیر می‌ماند (disabled فوکوس را می‌اندازد) اما فشار را نادیده می‌گیرد.
  function handleClick(event: ClickEvent) {
    if (busy) {
      event.preventDefault();
      return;
    }
    onclick?.(event);
  }
</script>

{#snippet iconPart(value: IconName | Snippet)}
  {#if typeof value === 'string'}<Icon name={value} />{:else}{@render value()}{/if}
{/snippet}

{#snippet contentPart()}
  {#if children}{@render children()}{:else}{text}{/if}
{/snippet}

{#snippet label()}
  <span class="nx-button-label" data-text={effect === 'slide' ? text : undefined}>
    {#if icon}{@render iconPart(icon)}{/if}
    {#if hasContent}
      {#if iconOnly}
        <span class="nx-visually-hidden">{@render contentPart()}</span>
      {:else}
        <span class="nx-button-text">{@render contentPart()}</span>
      {/if}
    {/if}
    {#if iconEnd}{@render iconPart(iconEnd)}{/if}
  </span>
{/snippet}

{#if href}
  <!-- انکر مستقیم: SmartLink این طعم بدون پرایدر همان <a> ساده است؛ رفتارها باید روی خود عنصر بنشینند. -->
  <a
    {href}
    class={cx('nx-button', className)}
    data-variant={variant}
    data-size={size === 'md' ? undefined : size}
    data-shape={shape && shape !== 'rounded' ? shape : undefined}
    data-status={resolved === 'idle' ? undefined : resolved}
    data-effect={effect}
    data-icon-only={iconOnly ? '' : undefined}
    data-block={block ? '' : undefined}
    aria-busy={busy || undefined}
    aria-disabled={disabled || undefined}
    style={style || undefined}
    onclick={onclick as HTMLAnchorAttributes['onclick']}
    use:nxBehavior={{ behavior: magneticBehavior, options: MAGNETIC, enabled: !!magnetic }}
    use:nxBehavior={{ behavior: rippleBehavior, options: NO_OPTS, enabled: !!ripple }}
    {...rest as HTMLAnchorAttributes}
  >
    {@render label()}
    <ButtonStatusMarks />
  </a>
{:else}
  <button
    class={cx('nx-button', className)}
    data-variant={variant}
    data-size={size === 'md' ? undefined : size}
    data-shape={shape && shape !== 'rounded' ? shape : undefined}
    data-status={resolved === 'idle' ? undefined : resolved}
    data-effect={effect}
    data-icon-only={iconOnly ? '' : undefined}
    data-block={block ? '' : undefined}
    aria-busy={busy || undefined}
    style={style || undefined}
    type={type ?? 'button'}
    {disabled}
    aria-disabled={busy || undefined}
    onclick={handleClick}
    use:nxBehavior={{ behavior: magneticBehavior, options: MAGNETIC, enabled: !!magnetic }}
    use:nxBehavior={{ behavior: rippleBehavior, options: NO_OPTS, enabled: !!ripple }}
    {...rest}
  >
    {@render label()}
    <ButtonStatusMarks />
  </button>
{/if}
