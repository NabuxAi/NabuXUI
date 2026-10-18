<script module lang="ts">
  import type { IconName } from '@nabuxai/ui-core';
  import type { Snippet } from 'svelte';

  export interface AlertProps {
    tone?: 'neutral' | 'accent' | 'success' | 'warning' | 'danger' | 'info';
    variant?: 'soft' | 'outline' | 'accent-bar';
    title?: string | Snippet;
    /** A built-in icon, any snippet, or false for none. Defaults to the tone's icon. */
    icon?: IconName | Snippet | false;
    actions?: Snippet;
    /** Show a close button; the alert collapses away when pressed. */
    dismissible?: boolean;
    onDismiss?: () => void;
    class?: string;
    children?: Snippet;
  }
</script>

<script lang="ts">
  /**
   * Alert — the Svelte counterpart of ui-react's feedback family's Alert:
   * same DOM, same nx-* classes, same props.
   */
  import { leave } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import Icon from '../../internal/icon.svelte';
  import { TONE_ICON } from './tone-icons';

  let { tone = 'info', variant, title, icon, actions, dismissible, onDismiss, class: className, children }: AlertProps = $props();

  const lang = nxLang();
  let host = $state<HTMLDivElement>();
  let gone = $state(false);

  function dismiss() {
    if (!host) return;
    leave(host).then(() => {
      gone = true;
      onDismiss?.();
    });
  }
</script>

{#if !gone}
  <div bind:this={host} class={cx('nx-alert', className)} data-tone={tone} data-variant={variant === 'soft' ? undefined : variant}>
    {#if icon !== false}
      <span class="nx-alert-icon">
        {#if icon === undefined}
          <Icon name={TONE_ICON[tone] ?? 'info'} />
        {:else if typeof icon === 'string'}
          <Icon name={icon as IconName} />
        {:else}
          {@render icon()}
        {/if}
      </span>
    {:else}
      <span></span>
    {/if}
    <div class="nx-alert-content">
      {#if title}
        <p class="nx-alert-title">{#if typeof title === 'string'}{title}{:else}{@render title()}{/if}</p>
      {/if}
      {#if children}
        <div class="nx-alert-description">{@render children()}</div>
      {/if}
      {#if actions}
        <div class="nx-alert-actions">{@render actions()}</div>
      {/if}
    </div>
    {#if dismissible}
      <button type="button" class="nx-alert-close" aria-label={nxTranslate(lang, 'dismiss')} onclick={dismiss}>
        <Icon name="x" />
      </button>
    {/if}
  </div>
{/if}
