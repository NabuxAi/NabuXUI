<script lang="ts">
/**
 * NxMenu — the Vue counterpart of ui-react's `Menu`: a dropdown on the
 * native Popover API (with a data-open fallback), placed by the core,
 * roving and typeahead through its items. The trigger comes in through the
 * `trigger` slot and is augmented the way React's cloneElement does.
 */
import type { IconName } from '@nabuxai/ui-core';
import type { VueNode } from '../utils';

export type NxMenuEntry =
  | { type?: 'item'; label: VueNode; icon?: IconName; shortcut?: string; onSelect?: () => void; href?: string; tone?: 'danger'; disabled?: boolean; checked?: boolean }
  | { type: 'separator' }
  | { type: 'label'; label: VueNode };

export interface NxMenuProps {
  items: NxMenuEntry[];
  side?: 'bottom' | 'top';
  align?: 'start' | 'center' | 'end';
  ariaLabel?: string;
}
</script>

<script setup lang="ts">
import { cloneVNode, mergeProps, onMounted, ref, useId, useSlots, watchEffect, type ComponentPublicInstance } from 'vue';
import { createTypeahead, focusableItems, place, roveFocus } from '@nabuxai/ui-core';
import { NxNode, NxSmartLink } from '../kit';
import NxIcon from '../icon';
import { NxKbd } from '../display';

defineOptions({ inheritAttrs: false });

const props = withDefaults(defineProps<NxMenuProps>(), { side: 'bottom', align: 'start' });

const id = `nx-menu${useId().replace(/[:-]/g, '')}`;
const menu = ref<HTMLDivElement | null>(null);
const button = ref<HTMLElement | null>(null);
const open = ref(false);
const focusOnOpen = ref<'first' | 'last'>('first');
const typeahead = createTypeahead();
const slots = useSlots();

const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

// Declarative invoker: the browser toggles the menu and exempts the button
// from light dismiss. Set on the DOM so every spelling of the attribute works.
onMounted(() => {
  if (supportsPopover()) button.value?.setAttribute('popovertarget', id);
});

// The popover opens and light-dismisses natively; keep our view in step.
const onToggle = (event: Event) => {
  open.value = (event as ToggleEvent).newState === 'open';
};

// Place the menu beside its button when open, and land focus inside.
watchEffect(
  (onCleanup) => {
    const el = menu.value;
    const btn = button.value;
    if (!open.value || !el || !btn) return;
    onCleanup(place(btn, el, { side: props.side, align: props.align, offset: 6 }));
    const items = focusableItems(el, '[role^="menuitem"]');
    (focusOnOpen.value === 'last' ? items[items.length - 1] : items[0])?.focus();
    focusOnOpen.value = 'first';
  },
  { flush: 'post' },
);

const show = (where: 'first' | 'last') => {
  focusOnOpen.value = where;
  const el = menu.value;
  if (!el) return;
  if (supportsPopover()) el.showPopover();
  else {
    el.setAttribute('data-open', '');
    open.value = true;
  }
};

const hide = (returnFocus = true) => {
  const el = menu.value;
  if (!el) return;
  if (supportsPopover()) {
    try {
      el.hidePopover();
    } catch {
      /* already hidden */
    }
  } else {
    el.removeAttribute('data-open');
    open.value = false;
  }
  if (returnFocus) button.value?.focus();
};

const onMenuKey = (event: KeyboardEvent) => {
  const el = event.currentTarget as HTMLElement;
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

type ItemEntry = Extract<NxMenuEntry, { onSelect?: () => void }>;

const choose = (entry: ItemEntry) => {
  if (entry.disabled) return;
  entry.onSelect?.();
  hide();
};

const itemRole = (entry: ItemEntry) => (entry.checked === undefined ? 'menuitem' : 'menuitemcheckbox');

/** The trigger slot's own vnode, augmented — recomputed every render so the slot stays live. */
const triggerVNode = () => {
  const vnode = slots.trigger?.()[0];
  if (!vnode) return null;
  return cloneVNode(
    vnode,
    mergeProps(vnode.props ?? {}, {
      'aria-haspopup': 'menu',
      'aria-expanded': open.value,
      'aria-controls': id,
      ref: (el: Element | ComponentPublicInstance | null) => {
        // A seat component as the trigger hands its instance over; take the element either way.
        button.value = el instanceof HTMLElement ? el : ((el as ComponentPublicInstance | null)?.$el instanceof HTMLElement ? ((el as ComponentPublicInstance).$el as HTMLElement) : null);
      },
      onClick: () => {
        if (!supportsPopover()) (open.value ? hide(false) : show('first'));
      },
      onKeydown: (event: KeyboardEvent) => {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
          event.preventDefault();
          show(event.key === 'ArrowUp' ? 'last' : 'first');
        }
      },
    }),
  );
};
</script>

<template>
  <NxNode :node="triggerVNode()" />
  <div
    ref="menu"
    v-bind="$attrs"
    :id="id"
    class="nx-menu"
    role="menu"
    :aria-label="ariaLabel"
    popover="auto"
    @keydown="onMenuKey"
    @toggle="onToggle"
  >
    <template v-for="(entry, i) in items" :key="i">
      <div v-if="entry.type === 'separator'" class="nx-menu-separator" role="separator" />
      <div v-else-if="entry.type === 'label'" class="nx-menu-label" role="presentation">
        <NxNode :node="entry.label" />
      </div>
      <template v-else>
        <NxSmartLink
          v-if="entry.href && !entry.disabled"
          class="nx-menu-item"
          :role="itemRole(entry)"
          :aria-checked="entry.checked"
          :aria-disabled="entry.disabled || undefined"
          :data-tone="entry.tone"
          :tabindex="-1"
          :style="{ '--nx-i': i }"
          :href="entry.href"
          @click="hide(false)"
        >
          <NxIcon v-if="entry.icon" :name="entry.icon" />
          <span><NxNode :node="entry.label" /></span>
          <NxKbd v-if="entry.shortcut">{{ entry.shortcut }}</NxKbd>
        </NxSmartLink>
        <button
          v-else
          type="button"
          class="nx-menu-item"
          :role="itemRole(entry)"
          :aria-checked="entry.checked"
          :aria-disabled="entry.disabled || undefined"
          :data-tone="entry.tone"
          :tabindex="-1"
          :style="{ '--nx-i': i }"
          @click="choose(entry)"
        >
          <NxIcon v-if="entry.icon" :name="entry.icon" />
          <span><NxNode :node="entry.label" /></span>
          <NxKbd v-if="entry.shortcut">{{ entry.shortcut }}</NxKbd>
        </button>
      </template>
    </template>
  </div>
</template>
