<script lang="ts">
/**
 * NxHeader — the Vue counterpart of ui-react's `Header`: the site bar with
 * its mega menu, actions and the mobile drawer.
 */
import type { VueNode } from '../utils';
import type { NxNavItem } from './megamenu.vue';

export interface NxHeaderProps {
  /** The brand; the `brand` slot renders instead when given. */
  brand?: VueNode;
  brandHref?: string;
  items?: NxNavItem[];
  /** Buttons at the end of the bar. Mark desktop-only ones with data-desktop. */
  actions?: VueNode;
  /** Shown at the bottom of the mobile menu. */
  mobileActions?: VueNode;
  variant?: 'bar' | 'floating';
  /** Slide away while scrolling down, back on the way up. */
  hideOnScroll?: boolean;
  ariaLabel?: string;
}
</script>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { headerScroll } from '@nabuxai/ui-core';
import { NxNode, NxSmartLink, useNxT } from '../kit';
import { useNxBehaviorReactive } from '../utils';
import NxMegaMenu from './megamenu.vue';
import NxMobileMenu from './mobile-menu.vue';
import NxDrawer from '../overlay/drawer.vue';

const props = withDefaults(defineProps<NxHeaderProps>(), { brandHref: '/', items: () => [], variant: 'bar' });

const menuOpen = ref(false);

// The scroll behaviour rides the header; the hide flag can flip while mounted.
const seat = useNxBehaviorReactive<HTMLElement, { hide: boolean }>(headerScroll, reactive({ get hide() { return !!props.hideOnScroll; } }), () => true);
const t = useNxT(seat);
</script>

<template>
  <header ref="seat" class="nx-header" :data-variant="variant === 'floating' ? 'floating' : undefined">
    <div class="nx-header-inner">
      <NxSmartLink class="nx-header-brand" :href="brandHref">
        <slot name="brand"><NxNode :node="brand" /></slot>
      </NxSmartLink>
      <div v-if="items.length > 0" class="nx-header-nav">
        <NxMegaMenu :items="items" :aria-label="ariaLabel" />
      </div>
      <div v-if="$slots.actions || actions" class="nx-header-actions">
        <slot name="actions"><NxNode :node="actions" /></slot>
      </div>
      <button v-if="items.length > 0" type="button" class="nx-header-toggle" :aria-expanded="menuOpen" :aria-label="t('menu')" @click="menuOpen = true">
        <span class="nx-burger" aria-hidden="true">
          <i />
          <i />
        </span>
      </button>
    </div>
    <NxDrawer v-if="items.length > 0" v-model:open="menuOpen" :title="t('menu')">
      <NxMobileMenu :items="items" :footer="mobileActions" @navigate="menuOpen = false" />
    </NxDrawer>
  </header>
</template>
