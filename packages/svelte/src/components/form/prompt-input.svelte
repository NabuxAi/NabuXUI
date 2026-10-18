<script lang="ts">
  /**
   * PromptInput — همتای Svelte برای PromptInput در ui-react/components/form.tsx:
   * جعبهٔ فرمان با ارسال/توقف، پیوست‌ها و نوارابزار.
   */
  import { autogrow, type MessageKey } from '@nabuxai/ui-core';
  import { nxB } from '../../internal/bind';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import Chip from './chip.svelte';
  import type { Snippet } from 'svelte';
  import type { KeyboardEventHandler } from 'svelte/elements';

  interface Props {
    value?: string;
    defaultValue?: string;
    onValueChange?: (value: string) => void;
    onSubmit?: (value: string) => void;
    placeholder?: string;
    /** While a reply is streaming the send button becomes a stop button. */
    streaming?: boolean;
    onStop?: () => void;
    /** Attached files shown as chips above the text. */
    attachments?: Array<{ id: string; name: string }>;
    onRemoveAttachment?: (id: string) => void;
    /** Offer the paperclip; called with the chosen files. */
    onAttach?: (files: File[]) => void;
    /** Extra controls in the toolbar (model picker, toggles). */
    toolbar?: Snippet;
    /** An aurora glow around the composer while it has focus. */
    glow?: boolean;
    maxRows?: number;
    disabled?: boolean;
    name?: string;
    class?: string;
    'aria-label'?: string;
  }

  let {
    defaultValue = '',
    value = $bindable(defaultValue),
    onValueChange,
    onSubmit,
    placeholder,
    streaming,
    onStop,
    attachments,
    onRemoveAttachment,
    onAttach,
    toolbar,
    glow = true,
    maxRows = 12,
    disabled,
    name,
    class: className,
    'aria-label': ariaLabel,
  }: Props = $props();

  const t = (key: MessageKey) => nxTranslate(nxLang(), key);
  const grow = nxB(autogrow);
  let picker = $state<HTMLInputElement | undefined>(undefined);

  function setValue(next: string) {
    value = next;
    onValueChange?.(next);
  }

  function submit(event?: Event) {
    event?.preventDefault();
    if (streaming) {
      onStop?.();
      return;
    }
    const trimmed = value.trim();
    if (!trimmed || disabled) return;
    onSubmit?.(trimmed);
  }

  const onKeyDown: KeyboardEventHandler<HTMLTextAreaElement> = (event) => {
    if (event.key === 'Enter' && !event.shiftKey && !event.isComposing) submit(event);
  };
</script>

<form class={cx('nx-prompt', className)} data-glow={glow ? '' : undefined} onsubmit={submit}>
  {#if attachments && attachments.length > 0}
    <div class="nx-prompt-attachments">
      {#each attachments as file (file.id)}
        <Chip text={file.name} onRemove={onRemoveAttachment ? () => onRemoveAttachment(file.id) : undefined} />
      {/each}
    </div>
  {/if}
  <textarea
    use:grow
    class="nx-prompt-textarea"
    rows={1}
    {name}
    {value}
    {placeholder}
    aria-label={ariaLabel ?? placeholder}
    {disabled}
    oninput={(event) => setValue(event.currentTarget.value)}
    onkeydown={onKeyDown}
    style="--nx-prompt-max: {maxRows}lh"
  ></textarea>
  <div class="nx-prompt-toolbar">
    {#if onAttach}
      <input bind:this={picker} type="file" multiple hidden onchange={(event) => event.currentTarget.files && onAttach(Array.from(event.currentTarget.files))} />
      <button type="button" class="nx-button" data-variant="ghost" data-size="sm" data-icon-only="" aria-label={t('attach')} onclick={() => picker?.click()}>
        <span class="nx-button-label">
          <Icon name="paperclip" />
        </span>
      </button>
    {/if}
    {@render toolbar?.()}
    <span class="nx-prompt-spacer"></span>
    <button
      type="submit"
      class="nx-prompt-send"
      data-state={streaming ? 'streaming' : value.trim() ? 'ready' : undefined}
      disabled={!streaming && (!value.trim() || disabled)}
      aria-label={streaming ? t('stop') : t('send')}
    >
      <Icon name="arrow-up" class="nx-prompt-arrow" />
      <Icon name="stop" class="nx-prompt-stop" />
    </button>
  </div>
</form>
