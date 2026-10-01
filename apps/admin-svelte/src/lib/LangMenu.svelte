<script lang="ts">
  /**
   * The language menu — the markup of the `language-menu` block CSS contract in
   * ui-core (nx-language-*): the trigger rolls the two codes, the panel is a
   * native popover placed with core `place`, and picking one calls `setLang`,
   * so the whole panel — shell included — re-renders in the new language
   * instantly.
   */
  import { type Cleanup, place, roveFocus } from '@nabuxai/ui-core';
  import { app, setLang } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  let {
    label,
    options,
  }: {
    label: string;
    options: { id: 'fa' | 'en'; name: string; short: string }[];
  } = $props();

  const panelId = 'nx-admin-language';

  let open = $state(false);
  let trigger: HTMLButtonElement;
  let panel: HTMLElement;
  let unplace: Cleanup | null = null;

  function onToggle() {
    open = panel.matches(':popover-open');
    unplace?.();
    unplace = null;
    if (open) unplace = place(trigger, panel, { side: 'bottom', align: 'end', offset: 8 });
  }

  // An exit is softer and faster than the enter: fold away quickly.
  const pick = (id: 'fa' | 'en') => {
    setLang(id);
    setTimeout(() => panel?.matches(':popover-open') && panel?.hidePopover(), 150);
  };

  const onKey = (event: KeyboardEvent) => {
    roveFocus(event, event.currentTarget as HTMLElement, '.nx-language-choice', { orientation: 'vertical' });
  };
</script>

<button
  bind:this={trigger}
  type="button"
  class="nx-language-trigger"
  aria-haspopup="listbox"
  aria-expanded={open}
  aria-controls={panelId}
  popovertarget={panelId}
  aria-label={label}
>
  <span class="nx-language-globe"><NxIcon name="globe" /></span>
  <span class="nx-language-codes" aria-hidden="true">
    {#each options as option (option.id)}
      <span class="nx-language-code" data-current={option.id === app.lang ? '' : undefined}>{option.short}</span>
    {/each}
  </span>
  <span class="nx-language-chevron"><NxIcon name="chevron-down" /></span>
</button>
<div bind:this={panel} id={panelId} class="nx-language" popover="auto" role="listbox" tabindex="-1" aria-label={label} ontoggle={onToggle} onkeydown={onKey}>
  <ul class="nx-language-list">
    {#each options as option (option.id)}
      <li class="nx-language-row" data-selected={option.id === app.lang ? '' : undefined}>
        <button type="button" class="nx-language-choice" role="option" aria-selected={option.id === app.lang} onclick={() => pick(option.id)}>
          <span>{option.name}</span>
          <span class="nx-language-short">{option.short}</span>
          <span class="nx-language-mark"><NxIcon name="check" /></span>
        </button>
      </li>
    {/each}
  </ul>
</div>
