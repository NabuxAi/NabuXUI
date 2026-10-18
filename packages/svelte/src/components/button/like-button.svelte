<script lang="ts">
  /**
   * LikeButton — همتای Svelte برای LikeButton در ui-react/components/button.tsx.
   * burst روی خود دکمه می‌نشیند (ری‌اکت از ref می‌گیرد؛ اینجا currentTarget همان گره است).
   */
  import { burst } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import { NumberTicker } from '../text.svelte';
  import Button, { type ButtonVariant } from './button.svelte';

  interface Props {
    liked?: boolean;
    defaultLiked?: boolean;
    onLikedChange?: (liked: boolean) => void;
    /** Count shown beside the heart (without this press). */
    count?: number;
    label?: string;
    variant?: ButtonVariant;
    class?: string;
    [key: string]: unknown;
  }

  let { defaultLiked = false, liked = $bindable(defaultLiked ?? false), onLikedChange, count, label, variant = 'ghost', class: className, ...rest }: Props = $props();

  const t = () => nxTranslate(nxLang(), 'like');

  function toggle(event: MouseEvent) {
    const next = !liked;
    liked = next;
    onLikedChange?.(next);
    if (next) burst(event.currentTarget as HTMLElement);
  }
</script>

<Button {variant} class={cx('nx-like', className)} aria-pressed={liked} aria-label={label ?? t()} onclick={toggle} {...rest}>
  {#snippet icon()}
    <Icon name="heart" class="nx-like-heart" />
  {/snippet}
  {#if count !== undefined}
    <NumberTicker value={count + (liked ? 1 : 0)} reveal={false} />
  {/if}
</Button>
