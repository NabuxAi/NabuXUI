<script module lang="ts">
  import type { Snippet } from 'svelte';
  import type { IconName } from '@nabuxai/ui-core';

  export interface CommandItem {
    id: string;
    label: string;
    icon?: IconName;
    hint?: string;
    shortcut?: string;
    keywords?: string[];
    href?: string;
    onSelect?: () => void;
    disabled?: boolean;
  }

  export interface CommandGroup {
    label: string;
    items: CommandItem[];
  }

  export interface CommandPaletteProps {
    groups: CommandGroup[];
    open?: boolean;
    defaultOpen?: boolean;
    onOpenChange?: (open: boolean) => void;
    /** Keyboard shortcut that toggles the palette; null for none. */
    hotkey?: string | null;
    placeholder?: string;
    emptyText?: string | Snippet;
  }

  /** Fold the variants of a Persian or Arabic letter together so either spelling matches. */
  const normalise = (text: string) =>
    text
      .toLocaleLowerCase()
      .replace(/[يى]/g, 'ی')
      .replace(/ك/g, 'ک')
      .replace(/[ً-ٰٟ‌]/g, '')
      .normalize('NFKC');
</script>

<script lang="ts">
  import type { MessageKey } from '@nabuxai/ui-core';
  import {
    type IndicatorController,
    hotkey as hotkeyBehavior,
    indicator,
    modKeyLabel,
    nextId,
  } from '@nabuxai/ui-core';
  import { nxTranslate } from '../../i18n';
  import { nxLang } from '../../internal/locale';
  import Icon from '../../internal/icon.svelte';
  import SmartLink from '../../internal/smart-link.svelte';
  import Content from '../../internal/content.svelte';
  import Dialog from './dialog.svelte';
  import { Kbd } from '../display.svelte';

  let { groups, open, defaultOpen = false, onOpenChange, hotkey: combo = 'mod+k', placeholder, emptyText }: CommandPaletteProps = $props();

  /** The component's own words, in the page's language. */
  const t = (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(nxLang(), key, params);

  const base = nextId('nx-cmd');

  // svelte-ignore state_referenced_locally
  // defaultOpen seeds the internal state once, exactly like the React seat's useState.
  let internal = $state(defaultOpen);
  /** Controlled while `open` is passed, internal otherwise. */
  let isOpen = $derived(open !== undefined ? open : internal);

  const setOpen = (next: boolean) => {
    if (open === undefined) internal = next;
    onOpenChange?.(next);
  };

  let query = $state('');
  let active = $state(0);
  let list = $state<HTMLDivElement>();
  let input = $state<HTMLInputElement>();
  let ind: IndicatorController | undefined;

  // Re-subscribed whenever the combo or the open state changes, so the handler
  // always sees the fresh toggle target — the React seat's [combo, isOpen] deps.
  $effect(() => {
    if (!combo) return;
    return hotkeyBehavior(combo, () => setOpen(!isOpen));
  });

  const filtered = $derived.by(() => {
    const q = normalise(query.trim());
    return groups
      .map((group) => ({
        ...group,
        items: group.items.filter((item) => !q || normalise([item.label, item.hint, ...(item.keywords ?? [])].join(' ')).includes(q)),
      }))
      .filter((group) => group.items.length > 0);
  });

  const flat = $derived(filtered.flatMap((group) => group.items));

  /** The groups with a flat index per item, for the option ids and --nx-i. */
  const numbered = $derived.by(() => {
    let index = 0;
    return filtered.map((group) => ({ group, items: group.items.map((item) => ({ item, i: index++ })) }));
  });

  $effect(() => {
    if (!isOpen) return;
    query = '';
    active = 0;
    const frame = requestAnimationFrame(() => input?.focus());
    return () => cancelAnimationFrame(frame);
  });

  $effect(() => {
    query;
    active = 0;
  });

  $effect(() => {
    if (!list) return;
    ind = indicator(list);
    return () => {
      ind?.destroy();
      ind = undefined;
    };
  });

  $effect(() => {
    if (!list || !ind) return;
    const el = list.querySelector<HTMLElement>(`#${base}-opt-${active}`);
    ind.update(el);
    el?.scrollIntoView({ block: 'nearest' });
  });

  const choose = (item: CommandItem | undefined) => {
    if (!item || item.disabled) return;
    setOpen(false);
    if (item.href) {
      list?.querySelector<HTMLAnchorElement>(`[data-command-link="${CSS.escape(item.id)}"]`)?.click();
    }
    item.onSelect?.();
  };

  const onKeyDown = (event: KeyboardEvent) => {
    if (event.key === 'ArrowDown') {
      event.preventDefault();
      active = flat.length ? (active + 1) % flat.length : 0;
    } else if (event.key === 'ArrowUp') {
      event.preventDefault();
      active = flat.length ? (active - 1 + flat.length) % flat.length : 0;
    } else if (event.key === 'Enter') {
      event.preventDefault();
      choose(flat[active]);
    }
  };
</script>

<Dialog {open} onOpenChange={setOpen} hideClose class="nx-command">
  <div class="nx-command-search">
    <Icon name="search" />
    <input
      class="nx-command-input"
      role="combobox"
      aria-expanded="true"
      aria-controls={`${base}-list`}
      aria-activedescendant={flat.length ? `${base}-opt-${active}` : undefined}
      aria-autocomplete="list"
      aria-label={t('search')}
      placeholder={placeholder ?? t('searchPlaceholder')}
      value={query}
      bind:this={input}
      oninput={(event) => (query = event.currentTarget.value)}
      onkeydown={onKeyDown}
    />
    <Kbd>Esc</Kbd>
  </div>
  <div class="nx-command-list" id={`${base}-list`} role="listbox" aria-label={t('search')} bind:this={list}>
    <span class="nx-indicator" aria-hidden="true"></span>
    {#if filtered.length === 0}
      <p class="nx-command-empty">
        {#if emptyText}
          <Content of={emptyText} />
        {:else}
          {t('noResults')}
        {/if}
      </p>
    {/if}
    {#each numbered as { group, items }, g (group.label)}
      <div class="nx-command-group" role="group" aria-labelledby={`${base}-group-${g}`}>
        <div class="nx-command-group-label" id={`${base}-group-${g}`}>{group.label}</div>
        {#each items as { item, i } (item.id)}
          <!-- svelte-ignore a11y_interactive_supports_focus, a11y_click_events_have_key_events -- the combobox input drives the keyboard -->
          <div
            id={`${base}-opt-${i}`}
            class="nx-command-item"
            role="option"
            aria-selected={i === active}
            aria-disabled={item.disabled || undefined}
            style={`--nx-i: ${i}`}
            onpointermove={() => i !== active && (active = i)}
            onclick={() => choose(item)}
          >
            {#if item.icon}
              <Icon name={item.icon} />
            {/if}
            <span>{item.label}</span>
            {#if item.hint}
              <span class="nx-command-item-hint">{item.hint}</span>
            {/if}
            {#if item.shortcut}
              <Kbd>{item.shortcut}</Kbd>
            {/if}
            {#if item.href}
              <SmartLink href={item.href} data-command-link={item.id} tabindex={-1} hidden aria-hidden="true"></SmartLink>
            {/if}
          </div>
        {/each}
      </div>
    {/each}
  </div>
  <footer class="nx-command-footer">
    <span>
      <Kbd>↑</Kbd>
      <Kbd>↓</Kbd>
      {t('navigate')}
    </span>
    <span>
      <Kbd>↵</Kbd>
      {t('select')}
    </span>
    <span>
      <Kbd>Esc</Kbd>
      {t('toClose')}
    </span>
    {#if combo}
      <span style="margin-inline-start: auto">
        <Kbd>{combo.replace('mod', modKeyLabel()).replace('+', ' ').toUpperCase()}</Kbd>
      </span>
    {/if}
  </footer>
</Dialog>
