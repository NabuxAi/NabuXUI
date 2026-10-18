<script lang="ts">
  /**
   * CopyButton — همتای Svelte برای CopyButton در ui-react/components/button.tsx.
   */
  import { copyText, type MessageKey } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import Button, { type ButtonVariant } from './button.svelte';
  import type { Snippet } from 'svelte';

  interface Props {
    /** The text that goes to the clipboard. */
    value: string;
    /** How long the check stays, ms. */
    timeout?: number;
    onCopied?: () => void;
    variant?: ButtonVariant;
    /** متن ساده دکمه؛ بدون آن فقط-آیکن می‌شود. */
    text?: string;
    children?: Snippet;
    class?: string;
    [key: string]: unknown;
  }

  let { value, timeout = 1600, onCopied, variant = 'ghost', text, children, class: className, ...rest }: Props = $props();

  const t = (key: MessageKey) => nxTranslate(nxLang(), key);
  let copied = $state(false);
  const hasContent = $derived(children !== undefined || text !== undefined);

  $effect(() => {
    if (!copied) return;
    const id = setTimeout(() => (copied = false), timeout);
    return () => clearTimeout(id);
  });

  async function handleCopy() {
    if (await copyText(value)) {
      copied = true;
      onCopied?.();
    }
  }
</script>

<Button
  {variant}
  class={cx('nx-copy', className)}
  data-copied={copied ? '' : undefined}
  iconOnly={!hasContent}
  aria-label={hasContent ? undefined : t('copy')}
  onclick={handleCopy}
  {...rest}
>
  {#snippet icon()}
    <span class="nx-copy-icons" aria-hidden="true">
      <Icon name="copy" class="nx-copy-idle" />
      <Icon name="check" class="nx-copy-done" />
    </span>
  {/snippet}
  {#if hasContent}
    {#if children}{@render children()}{:else}{text}{/if}
  {/if}
  <span class="nx-visually-hidden" aria-live="polite">{copied ? t('copied') : ''}</span>
</Button>
