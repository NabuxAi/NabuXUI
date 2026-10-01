<script setup lang="ts">
/**
 * The admin sidebar — the same markup `AdminSidebar` renders in React
 * (nx-admin-sidebar): brand, grouped nav with the spring indicator under the
 * current item (core `indicator`), and the collapse toggle. Items are plain
 * `href="#/…"` links, so the hash router carries navigation. Mounted twice by
 * the shell: the frame copy follows `collapsed`, the drawer copy stays open.
 */
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { type IconName, type Cleanup, indicator, translate } from '@nabuxai/ui-core';
import { lang, numberFmt } from '../store';
import NxIcon from './NxIcon.vue';

export interface SidebarItem {
  id: string;
  label: string;
  icon?: IconName;
  href: string;
  badge?: number;
}

export interface SidebarGroup {
  id?: string;
  label: string;
  items: SidebarItem[];
}

const props = defineProps<{
  groups: SidebarGroup[];
  active: string;
  brand: string;
  navLabel: string;
  footerText?: string;
  collapsed: boolean;
  /** Bumping this re-measures the indicator (the drawer had no box until it opened). */
  measure?: number;
}>();

const emit = defineEmits<{ 'toggle-collapse': [] }>();

const nav = ref<HTMLElement | null>(null);
let ind: ReturnType<typeof indicator> | null = null;

const mark = Array.from(props.brand)[0] ?? 'N';

const move = () => {
  const current = props.active;
  ind?.update(current ? (nav.value?.querySelector(`[data-value="${CSS.escape(current)}"]`) ?? null) : null);
};

onMounted(() => {
  if (!nav.value) return;
  ind = indicator(nav.value);
  move();
});

onBeforeUnmount(() => ind?.destroy());

// The current item, a language switch (labels resize) and the rail collapse
// (the indicator's own ResizeObserver re-measures mid-flight) all re-aim here.
watch([() => props.active, () => props.groups, () => props.measure], () => void nextTick(move));

const word = (key: 'collapseSidebar' | 'expandSidebar') => translate(lang.value, key);
</script>

<template>
  <aside class="nx-admin-sidebar" :data-collapsed="collapsed || undefined">
    <div class="nx-admin-brand">
      <span class="nx-admin-mark" aria-hidden="true">{{ mark }}</span>
      <span class="nx-admin-brand-text">{{ brand }}</span>
    </div>
    <nav ref="nav" class="nx-admin-nav" :aria-label="navLabel">
      <span class="nx-indicator" aria-hidden="true" />
      <section v-for="group in groups" :key="group.id ?? group.label" class="nx-admin-group">
        <h3 class="nx-admin-group-label">{{ group.label }}</h3>
        <ul>
          <li v-for="item in group.items" :key="item.id">
            <a
              class="nx-admin-item"
              :href="item.href"
              :data-value="item.id"
              :data-label="item.label"
              :aria-current="item.id === active ? 'page' : undefined"
            >
              <NxIcon :name="item.icon ?? 'grid'" />
              <span class="nx-admin-label">{{ item.label }}</span>
              <span v-if="item.badge !== undefined" class="nx-admin-badge">{{ numberFmt.format(item.badge) }}</span>
            </a>
          </li>
        </ul>
      </section>
    </nav>
    <div class="nx-admin-sidebar-foot">
      <span v-if="footerText" class="nx-admin-brand-text">{{ footerText }}</span>
      <button
        type="button"
        class="nx-admin-item nx-admin-toggle"
        :aria-expanded="!collapsed"
        :data-label="collapsed ? word('expandSidebar') : word('collapseSidebar')"
        @click="emit('toggle-collapse')"
      >
        <NxIcon name="chevron-left" />
        <span class="nx-admin-label">{{ collapsed ? word('expandSidebar') : word('collapseSidebar') }}</span>
      </button>
    </div>
  </aside>
</template>
