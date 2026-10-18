<script lang="ts">
/**
 * NxDialog — the Vue counterpart of ui-react's `Dialog`: a native
 * `<dialog>` (modal, drawer or sheet) with the same backdrop-click and
 * Escape paths. Title, description and footer come in as props or slots;
 * the body is the default slot, and `trigger` is augmented the way React's
 * cloneElement does.
 */
import type { VueNode } from '../utils';

export interface NxDialogProps {
  defaultOpen?: boolean;
  title?: VueNode;
  description?: VueNode;
  footer?: VueNode;
  size?: 'sm' | 'md' | 'lg' | 'xl' | 'full';
  variant?: 'modal' | 'drawer' | 'sheet';
  /** Drawer edge: inline end (default) or start. */
  side?: 'start' | 'end';
  /** Close when the backdrop is clicked (default true). */
  closeOnBackdrop?: boolean;
  hideClose?: boolean;
}
</script>

<script setup lang="ts">
import { cloneVNode, mergeProps, onMounted, ref, useId, useSlots, watch } from 'vue';
import { NxNode, NxSmartLink, useControllableModel, useNxT } from '../kit';
import NxIcon from '../icon';

defineOptions({ inheritAttrs: false });

const props = withDefaults(defineProps<NxDialogProps>(), { defaultOpen: false, variant: 'modal', side: undefined, closeOnBackdrop: true });

const open = defineModel<boolean | undefined>('open');
const { current: isOpen, set } = useControllableModel(open, () => props.defaultOpen);

const base = `nx-dialog${useId().replace(/[:-]/g, '')}`;
const el = ref<HTMLDialogElement | null>(null);
const slots = useSlots();
const t = useNxT(el);

// The native element is the source of truth: our model only steers it.
watch(isOpen, () => {
  const node = el.value;
  if (!node) return;
  if (isOpen.value && !node.open) node.showModal();
  if (!isOpen.value && node.open) node.close();
});

// Escape, form[method=dialog] and .close() all end here.
const onClosed = () => set(false);

onMounted(() => {
  if (isOpen.value && el.value && !el.value.open) el.value.showModal();
});

// A click on the ::backdrop reports the dialog as target with coordinates outside it.
const onBackdrop = (event: MouseEvent) => {
  if (!props.closeOnBackdrop || event.target !== event.currentTarget) return;
  const r = (event.currentTarget as HTMLDialogElement).getBoundingClientRect();
  const inside = event.clientX >= r.left && event.clientX <= r.right && event.clientY >= r.top && event.clientY <= r.bottom;
  if (!inside) (event.currentTarget as HTMLDialogElement).close();
};

/** The trigger slot's own vnode, augmented — recomputed every render so the slot stays live. */
const triggerVNode = () => {
  const vnode = slots.trigger?.()[0];
  if (!vnode) return null;
  return cloneVNode(
    vnode,
    mergeProps(vnode.props ?? {}, {
      'aria-haspopup': 'dialog',
      onClick: () => set(true),
    }),
  );
};
</script>

<template>
  <NxNode :node="triggerVNode()" />
  <dialog
    ref="el"
    v-bind="$attrs"
    class="nx-dialog"
    :data-size="size && size !== 'md' ? size : undefined"
    :data-variant="variant === 'modal' ? undefined : variant"
    :data-side="side === 'start' ? 'start' : undefined"
    :aria-labelledby="$slots.title || title ? `${base}-title` : undefined"
    :aria-describedby="$slots.description || description ? `${base}-description` : undefined"
    @click="onBackdrop"
    @close="onClosed"
  >
    <header v-if="$slots.title || title || $slots.description || description" class="nx-dialog-header">
      <h2 v-if="$slots.title || title" class="nx-dialog-title" :id="`${base}-title`">
        <slot name="title"><NxNode :node="title" /></slot>
      </h2>
      <p v-if="$slots.description || description" class="nx-dialog-description" :id="`${base}-description`">
        <slot name="description"><NxNode :node="description" /></slot>
      </p>
    </header>
    <div class="nx-dialog-body">
      <slot />
    </div>
    <footer v-if="$slots.footer || footer" class="nx-dialog-footer">
      <slot name="footer"><NxNode :node="footer" /></slot>
    </footer>
    <button v-if="!hideClose" type="button" class="nx-dialog-close" :aria-label="t('close')" @click="el?.close()">
      <NxIcon name="x" />
    </button>
  </dialog>
</template>
