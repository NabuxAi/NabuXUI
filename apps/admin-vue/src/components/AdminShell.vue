<script setup lang="ts">
/**
 * The admin shell frame — the markup `AdminShell` renders in React: the frame
 * grid (sidebar + main), the topbar row, the scroll area, and the mobile drawer
 * as a native popover holding a second, always-expanded copy of the sidebar.
 */
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { translate } from '@nabuxai/ui-core';
import { lang, s } from '../store';
import { route } from '../router';
import AdminSidebar, { type SidebarGroup } from './AdminSidebar.vue';
import AdminTopbar, { type TopbarUser } from './AdminTopbar.vue';
import NxIcon from './NxIcon.vue';

const props = defineProps<{
  active: string;
  title: string;
  subtitle?: string;
  groups: SidebarGroup[];
  user: TopbarUser;
}>();

const emit = defineEmits<{ search: []; menu: [id: string] }>();

const collapsed = ref(false);
const drawerOpen = ref(false);
const root = ref<HTMLElement | null>(null);
const content = ref<HTMLElement | null>(null);
const drawer = ref<HTMLElement | null>(null);
const drawerId = 'nx-admin-drawer';

function onToggle(event: Event) {
  drawerOpen.value = (event as ToggleEvent).newState === 'open';
  if (!drawerOpen.value && drawer.value?.contains(document.activeElement)) {
    root.value?.querySelector<HTMLElement>('.nx-admin-menu')?.focus();
  }
}

onMounted(() => drawer.value?.addEventListener('toggle', onToggle));
onBeforeUnmount(() => drawer.value?.removeEventListener('toggle', onToggle));

const closeDrawer = () => {
  if (drawer.value?.matches(':popover-open')) drawer.value.hidePopover();
};

// A new page starts from the top of the shell's scroll area.
watch(
  () => route.value,
  () => void nextTick(() => content.value?.scrollTo({ top: 0 })),
);

const menuWord = () => translate(lang.value, 'menu');
const closeWord = () => translate(lang.value, 'close');
</script>

<template>
  <div ref="root" class="nx-admin">
    <div class="nx-admin-frame" :data-collapsed="collapsed || undefined">
      <AdminSidebar
        :groups="groups"
        :active="active"
        :brand="s.app.brand"
        :nav-label="s.app.navLabel"
        :footer-text="s.app.tagline"
        :collapsed="collapsed"
        @toggle-collapse="collapsed = !collapsed"
      />
      <div class="nx-admin-main">
        <header class="nx-admin-topbar">
          <AdminTopbar
            :title="title"
            :subtitle="subtitle"
            :search-placeholder="s.app.searchPlaceholder"
            :search-hint="s.app.searchHint"
            :user="user"
            :drawer-id="drawerId"
            :drawer-open="drawerOpen"
            @search="emit('search')"
            @menu="(id) => emit('menu', id)"
          >
            <template #actions>
              <slot name="actions" />
            </template>
          </AdminTopbar>
        </header>
        <div ref="content" class="nx-admin-content">
          <slot />
        </div>
      </div>
    </div>
    <div :id="drawerId" ref="drawer" class="nx-admin-drawer" popover="auto" :aria-label="menuWord()">
      <button type="button" class="nx-admin-drawer-close" @click="closeDrawer">
        <NxIcon name="x" />
        <span class="nx-visually-hidden">{{ closeWord() }}</span>
      </button>
      <AdminSidebar
        :groups="groups"
        :active="active"
        :brand="s.app.brand"
        :nav-label="s.app.navLabel"
        :footer-text="s.app.tagline"
        :collapsed="false"
        :measure="drawerOpen ? 1 : 0"
      />
    </div>
  </div>
</template>
