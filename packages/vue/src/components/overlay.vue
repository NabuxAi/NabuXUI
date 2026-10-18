<script lang="ts">
/**
 * NxOverlay — the overlay seat of @nabuxai/ui-vue: dialogs, drawers,
 * sheets, popovers and the command palette — the Vue counterparts of
 * ui-react's components/overlay.tsx.
 */
import NxDialog from '../internal/overlay/dialog.vue';

// The seat's face for a default import; every part is a named export below.
export default NxDialog;

export { default as NxDialog, type NxDialogProps } from '../internal/overlay/dialog.vue';
export { default as NxDrawer } from '../internal/overlay/drawer.vue';
export { default as NxSheet } from '../internal/overlay/sheet.vue';
export { default as NxPopover } from '../internal/overlay/popover.vue';
export { default as NxCommandPalette, type NxCommandGroup, type NxCommandItem, type NxCommandPaletteProps } from '../internal/overlay/command.vue';
</script>
