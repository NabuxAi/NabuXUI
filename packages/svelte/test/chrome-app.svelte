<script lang="ts">
  /**
   * The snippet-taking seats of this run — Menu's trigger attrs, Popover's
   * trigger attrs, Dialog's trigger — wired the way an app wires them, so the
   * tests can assert on the DOM they produce.
   */
  import { Menu, type MenuEntry } from '../src/components/navigation.svelte';
  import { Dialog, Popover } from '../src/components/overlay.svelte';

  const menuItems: MenuEntry[] = [
    { label: 'Open', icon: 'external-link' },
    { type: 'separator' },
    { type: 'label', label: 'More' },
    { label: 'Delete', tone: 'danger', shortcut: '⌫' },
  ];
</script>

<Menu items={menuItems} aria-label="Actions">
  {#snippet trigger(attrs)}
    <button type="button" class="trigger" {...attrs}>Actions</button>
  {/snippet}
</Menu>

<Popover label="Notes" side="top">
  {#snippet trigger(attrs)}
    <button type="button" class="pop-trigger" {...attrs}>Notes</button>
  {/snippet}
  <p class="pop-body">Hello</p>
</Popover>

<Dialog title="Settings" description="Change things">
  <p class="dialog-body">Body</p>
</Dialog>
