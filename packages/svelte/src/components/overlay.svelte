<script module lang="ts">
  /**
   * NxOverlay — همتای Svelte برای components/overlay.tsx در @nabuxai/ui-react.
   * سولت تک‌کامپوننتی است، پس این صندلیِ خانواده است: کامپوننت‌ها را از
   * ./overlay/ با همان نام‌هایی که همتای React صادر می‌کند بیرون می‌دهد.
   */
  export { default as Dialog, type DialogProps } from './overlay/dialog.svelte';
  export { default as Drawer } from './overlay/drawer.svelte';
  export { default as Sheet } from './overlay/sheet.svelte';
  export { default as Popover, type PopoverProps } from './overlay/popover.svelte';
  export {
    default as CommandPalette,
    type CommandItem,
    type CommandGroup,
    type CommandPaletteProps,
  } from './overlay/command-palette.svelte';
</script>
