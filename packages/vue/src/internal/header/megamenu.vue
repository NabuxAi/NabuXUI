<script lang="ts">
/**
 * NxMegaMenu — the Vue counterpart of ui-react's `MegaMenu`: the desktop
 * bar whose panels slide between triggers, with a pill that follows the
 * open trigger (or the current page) and a viewport sized around the panel.
 */
import type { IconName } from '@nabuxai/ui-core';
import type { VueNode } from '../utils';

export interface NxNavChild {
  label: VueNode;
  href: string;
  description?: VueNode;
  icon?: IconName;
}

export interface NxNavItem {
  label: VueNode;
  /** A plain link… */
  href?: string;
  current?: boolean;
  /** …or a panel of links (mega menu on desktop, a sub-level on mobile). */
  children?: NxNavChild[];
  /** Columns for the panel's link grid. */
  columns?: number;
  /** Extra content in the panel beside the links (a featured card, say). */
  aside?: VueNode;
}

export interface NxMegaMenuProps {
  items: NxNavItem[];
  ariaLabel?: string;
}

export type NxMegaMotion = 'from-start' | 'from-end' | 'to-start' | 'to-end';
</script>

<script setup lang="ts">
import { onBeforeUnmount, ref, useId, watchEffect } from 'vue';
import { indicator } from '@nabuxai/ui-core';
import { NxNode, NxSmartLink } from '../kit';
import NxIcon from '../icon';

const props = defineProps<NxMegaMenuProps>();

const base = `nx-mega${useId().replace(/[:-]/g, '')}`;
const nav = ref<HTMLElement | null>(null);
const viewport = ref<HTMLDivElement | null>(null);
const active = ref<number | null>(null);
const leaving = ref<{ index: number; motion: NxMegaMotion } | null>(null);
const motion = ref<NxMegaMotion | undefined>(undefined);
const fresh = ref(true);

let ind: ReturnType<typeof indicator> | null = null;
let closeTimer: ReturnType<typeof setTimeout> | undefined;

const open = (index: number) => {
  clearTimeout(closeTimer);
  if (index === active.value) return;
  if (active.value === null) {
    fresh.value = true;
    motion.value = undefined;
    leaving.value = null;
  } else {
    // Slide from the side the pointer came from, in reading order.
    const forward = index > active.value;
    fresh.value = false;
    motion.value = forward ? 'from-end' : 'from-start';
    leaving.value = { index: active.value, motion: forward ? 'to-start' : 'to-end' };
  }
  active.value = index;
};

const close = (delay = 0) => {
  clearTimeout(closeTimer);
  closeTimer = setTimeout(() => {
    active.value = null;
    leaving.value = null;
  }, delay);
};

/** Stay open while the pointer travels between the bar and the panel. */
const hold = () => clearTimeout(closeTimer);

onBeforeUnmount(() => {
  clearTimeout(closeTimer);
  ind?.destroy();
  ind = null;
});

// The pill follows the open trigger, or goes home to the current page.
watchEffect(
  () => {
    void active.value;
    void props.items;
    if (!nav.value) return;
    ind ??= indicator(nav.value);
    const trigger = active.value === null ? nav.value.querySelector('.nx-mega-list [aria-current="page"]') : nav.value.querySelector(`#${base}-trigger-${active.value}`);
    ind.update(trigger ?? null);
  },
  { flush: 'post' },
);

// Size and place the viewport around the active panel.
watchEffect(
  () => {
    void active.value;
    void props.items;
    const view = viewport.value;
    const root = nav.value;
    if (active.value === null || !view || !root) return;
    const panel = view.querySelector<HTMLElement>(`#${base}-panel-${active.value}`);
    const trigger = root.querySelector<HTMLElement>(`#${base}-trigger-${active.value}`);
    if (!panel || !trigger) return;
    const w = panel.offsetWidth;
    const h = panel.offsetHeight;
    const navRect = root.getBoundingClientRect();
    const t = trigger.getBoundingClientRect();
    const ideal = t.left + t.width / 2 - navRect.left - w / 2;
    const min = -navRect.left + 12;
    const max = document.documentElement.clientWidth - navRect.left - w - 12;
    view.style.setProperty('--nx-mega-w', `${w}px`);
    view.style.setProperty('--nx-mega-h', `${h}px`);
    view.style.setProperty('--nx-mega-x', `${Math.round(Math.min(Math.max(ideal, min), Math.max(min, max)))}px`);
  },
  { flush: 'post' },
);

const onTriggerKey = (event: KeyboardEvent, index: number) => {
  if (event.key === 'ArrowDown' || ((event.key === 'Enter' || event.key === ' ') && active.value !== index)) {
    event.preventDefault();
    open(index);
    requestAnimationFrame(() => viewport.value?.querySelector<HTMLElement>(`#${base}-panel-${index} a`)?.focus());
  }
};

const onPanelKey = (event: KeyboardEvent) => {
  if (event.key !== 'Escape' || active.value === null) return;
  const index = active.value;
  close();
  nav.value?.querySelector<HTMLElement>(`#${base}-trigger-${index}`)?.focus();
};

const onLinkEnter = (i: number) => {
  close(120);
  ind?.update(nav.value?.querySelector(`[data-mega-link="${i}"]`) ?? null);
};

// Focus leaving the bar entirely (not just hopping between its links) closes it.
const onFocusOut = (event: FocusEvent) => {
  if (!(event.currentTarget as HTMLElement).contains(event.relatedTarget as Node | null)) close();
};
</script>

<template>
  <nav
    ref="nav"
    class="nx-mega"
    :aria-label="ariaLabel"
    @pointerleave="close(220)"
    @pointerenter="hold"
    @focusout="onFocusOut"
  >
    <ul class="nx-mega-list">
      <template v-for="(item, i) in items" :key="i">
        <li v-if="item.children">
          <button
            type="button"
            :id="`${base}-trigger-${i}`"
            class="nx-mega-trigger"
            :aria-expanded="active === i"
            :aria-controls="`${base}-panel-${i}`"
            @pointerenter="$event.pointerType === 'mouse' && open(i)"
            @click="active === i ? close() : open(i)"
            @keydown="onTriggerKey($event, i)"
          >
            <NxNode :node="item.label" />
            <NxIcon name="chevron-down" />
          </button>
        </li>
        <li v-else>
          <NxSmartLink
            class="nx-mega-link"
            :href="item.href ?? '#'"
            :aria-current="item.current ? 'page' : undefined"
            :data-mega-link="i"
            @pointerenter="onLinkEnter(i)"
          >
            <NxNode :node="item.label" />
          </NxSmartLink>
        </li>
      </template>
    </ul>
    <span class="nx-indicator" aria-hidden="true" />
    <div
      ref="viewport"
      class="nx-mega-viewport"
      :data-state="active === null ? 'closed' : 'open'"
      :data-fresh="fresh ? '' : undefined"
      @keydown="onPanelKey"
    >
      <template v-for="(item, i) in items" :key="i">
        <div
          v-if="item.children"
          :id="`${base}-panel-${i}`"
          class="nx-mega-panel"
          :hidden="i !== active && leaving?.index !== i"
          :data-motion="i === active ? motion : leaving?.index === i ? leaving.motion : undefined"
          @animationend="leaving?.index === i && (leaving = null)"
        >
        <div style="display: flex; gap: var(--nx-space-4)">
          <ul class="nx-mega-grid" :style="{ '--nx-mega-cols': item.columns ?? 2 }">
            <li v-for="(child, c) in item.children" :key="c">
              <NxSmartLink class="nx-mega-item" :href="child.href" @click="close()">
                <span class="nx-mega-item-icon" aria-hidden="true">
                  <NxIcon :name="child.icon ?? 'arrow-right'" />
                </span>
                <span class="nx-mega-item-title"><NxNode :node="child.label" /></span>
                <span v-if="child.description" class="nx-mega-item-description"><NxNode :node="child.description" /></span>
              </NxSmartLink>
            </li>
          </ul>
          <NxNode :node="item.aside" />
        </div>
        </div>
      </template>
    </div>
  </nav>
</template>
