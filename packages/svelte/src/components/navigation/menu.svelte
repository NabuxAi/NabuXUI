<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { IconName } from '@nabuxai/ui-core';

  export type MenuEntry =
    | {
        type?: 'item';
        label: string | Snippet;
        icon?: IconName;
        shortcut?: string;
        onSelect?: () => void;
        href?: string;
        tone?: 'danger';
        disabled?: boolean;
        checked?: boolean;
      }
    | { type: 'separator' }
    | { type: 'label'; label: string | Snippet };

  export interface MenuProps {
    /**
     * The opening control, rendered as-is. Spread the attributes the snippet
     * receives onto your element — they carry the popover wiring and the
     * aria state:
     *
     *   {#snippet trigger(attrs)}
     *     <Button {...attrs}>Open</Button>
     *   {/snippet}
     */
    trigger: Snippet<[Record<string, unknown>]>;
    items: MenuEntry[];
    side?: 'bottom' | 'top';
    align?: 'start' | 'center' | 'end';
    'aria-label'?: string;
    class?: string;
  }
</script>

<script lang="ts">
  import {
    type IndicatorController,
    createTypeahead,
    focusableItems,
    nextId,
    place,
    roveFocus,
  } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import SmartLink from '../../internal/smart-link.svelte';
  import Content from '../../internal/content.svelte';
  import { Kbd } from '../display.svelte';

  let { trigger, items, side = 'bottom', align = 'start', 'aria-label': label, class: className }: MenuProps = $props();

  const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

  const id = nextId('nx-menu');
  let menu = $state<HTMLDivElement>();
  let open = $state(false);
  let focusOnOpen: 'first' | 'last' = 'first';
  const typeahead = createTypeahead();

  const button = () => document.querySelector<HTMLElement>(`[data-nx-menu-trigger="${id}"]`);

  // The popover opens and light-dismisses natively; keep our view in step.
  $effect(() => {
    const el = menu;
    if (!el) return;
    const onToggle = (event: Event) => (open = (event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  });

  // Place the open menu against its trigger and move focus inside.
  $effect(() => {
    if (!open || !menu || !side || !align) return;
    const anchor = button();
    if (!anchor) return;
    const stop = place(anchor, menu, { side, align, offset: 6 });
    const focusable = focusableItems(menu, '[role^="menuitem"]');
    (focusOnOpen === 'last' ? focusable[focusable.length - 1] : focusable[0])?.focus();
    focusOnOpen = 'first';
    return stop;
  });

  const show = (where: 'first' | 'last') => {
    focusOnOpen = where;
    const el = menu;
    if (!el) return;
    if (supportsPopover()) {
      el.showPopover();
    } else {
      el.setAttribute('data-open', '');
      open = true;
    }
  };

  const hide = (returnFocus = true) => {
    const el = menu;
    if (!el) return;
    if (supportsPopover()) {
      try {
        el.hidePopover();
      } catch {
        /* already hidden */
      }
    } else {
      el.removeAttribute('data-open');
      open = false;
    }
    if (returnFocus) button()?.focus();
  };

  const onMenuKey = (event: KeyboardEvent) => {
    const el = menu;
    if (!el) return;
    if (roveFocus(event, el, '[role^="menuitem"]', { orientation: 'vertical' })) return;
    if (event.key === 'Tab') hide(false);
    if (event.key === 'Escape') {
      event.preventDefault();
      hide();
    }
    const list = focusableItems(el, '[role^="menuitem"]');
    const match = typeahead(event.key, list, list.indexOf(document.activeElement as HTMLElement));
    match?.focus();
  };

  // Everything the trigger element needs: spread onto it via the snippet.
  const triggerAttrs = $derived({
    'data-nx-menu-trigger': id,
    'aria-haspopup': 'menu',
    'aria-expanded': open,
    'aria-controls': id,
    ...(supportsPopover()
      ? { popovertarget: id }
      : { onclick: () => (open ? hide(false) : show('first')) }),
    onkeydown: (event: KeyboardEvent) => {
      if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
        event.preventDefault();
        show(event.key === 'ArrowUp' ? 'last' : 'first');
      }
    },
  });
</script>

{@render trigger(triggerAttrs)}

<!-- svelte-ignore a11y_interactive_supports_focus -- focus lands on the menu items, not the menu itself -->
<div
  class={cx('nx-menu', className)}
  id={id}
  role="menu"
  aria-label={label}
  popover="auto"
  bind:this={menu}
  onkeydown={onMenuKey}
>
  {#each items as entry, i}
    {#if entry.type === 'separator'}
      <div class="nx-menu-separator" role="separator"></div>
    {:else if entry.type === 'label'}
      <div class="nx-menu-label" role="presentation">
        <Content of={entry.label} />
      </div>
    {:else if entry.href && !entry.disabled}
      <SmartLink
        class="nx-menu-item"
        href={entry.href}
        role={entry.checked === undefined ? 'menuitem' : 'menuitemcheckbox'}
        aria-checked={entry.checked}
        aria-disabled={entry.disabled || undefined}
        data-tone={entry.tone}
        tabindex={-1}
        style={`--nx-i: ${i}`}
        onclick={() => hide(false)}
      >
        {#if entry.icon}
          <Icon name={entry.icon} />
        {/if}
        <span>
          <Content of={entry.label} />
        </span>
        {#if entry.shortcut}
          <Kbd>{entry.shortcut}</Kbd>
        {/if}
      </SmartLink>
    {:else}
      <button
        type="button"
        class="nx-menu-item"
        role={entry.checked === undefined ? 'menuitem' : 'menuitemcheckbox'}
        aria-checked={entry.checked}
        aria-disabled={entry.disabled || undefined}
        data-tone={entry.tone}
        tabindex={-1}
        style={`--nx-i: ${i}`}
        onclick={() => {
          if (entry.disabled) return;
          entry.onSelect?.();
          hide();
        }}
      >
        {#if entry.icon}
          <Icon name={entry.icon} />
        {/if}
        <span>
          <Content of={entry.label} />
        </span>
        {#if entry.shortcut}
          <Kbd>{entry.shortcut}</Kbd>
        {/if}
      </button>
    {/if}
  {/each}
</div>
