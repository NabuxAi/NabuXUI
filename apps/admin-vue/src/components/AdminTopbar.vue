<script setup lang="ts">
/**
 * The admin topbar — the markup `AdminTopbar` renders in React: the drawer
 * invoker, the page heading, the search seat (this flavour jumps to the users
 * page, where the real search box lives), the actions slot (theme toggle,
 * language menu) and the avatar's popover menu (native popover + core `place`
 * and `roveFocus`).
 */
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { type Cleanup, type IconName, place, roveFocus, translate } from '@nabuxai/ui-core';
import { lang } from '../store';
import NxIcon from './NxIcon.vue';

export interface UserEntry {
  id: string;
  label: string;
  icon?: IconName;
  divider?: boolean;
}

export interface TopbarUser {
  name: string;
  role: string;
  menuLabel: string;
  menu: UserEntry[];
}

const props = defineProps<{
  title: string;
  subtitle?: string;
  searchPlaceholder: string;
  searchHint: string;
  user: TopbarUser;
  drawerId: string;
  drawerOpen: boolean;
}>();

const emit = defineEmits<{ search: []; menu: [id: string] }>();

const userTrigger = ref<HTMLButtonElement | null>(null);
const userPanel = ref<HTMLElement | null>(null);
const userOpen = ref(false);
const menuId = 'nx-admin-user-menu';
let unplace: Cleanup | null = null;

const initials = (name: string) =>
  name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => Array.from(part)[0])
    .join('')
    .toLocaleUpperCase();

// The avatar's menu is a native popover: the toggle event is the one source of
// truth; opening it also places it against the trigger.
onMounted(() => {
  const el = userPanel.value;
  if (!el) return;
  el.addEventListener('toggle', onToggle);
});
onBeforeUnmount(() => {
  userPanel.value?.removeEventListener('toggle', onToggle);
  unplace?.();
});

function onToggle(event: Event) {
  userOpen.value = (event as ToggleEvent).newState === 'open';
  unplace?.();
  unplace = null;
  if (userOpen.value && userTrigger.value && userPanel.value) {
    unplace = place(userTrigger.value, userPanel.value, { side: 'bottom', align: 'end', offset: 8 });
  }
}

// Closing the menu from inside returns focus to the avatar.
watch(userOpen, (open) => {
  if (open) return;
  void nextTick(() => {
    if (userPanel.value?.contains(document.activeElement)) userTrigger.value?.focus();
  });
});

const choose = (entry: UserEntry) => {
  userPanel.value?.hidePopover?.();
  emit('menu', entry.id);
};

const onMenuKey = (event: KeyboardEvent) => {
  if (userPanel.value) roveFocus(event, userPanel.value, '.nx-admin-user-item', { orientation: 'vertical' });
};

const word = (key: 'menu' | 'close') => translate(lang.value, key);
</script>

<template>
  <div class="nx-admin-topbar-start">
    <button
      type="button"
      class="nx-admin-menu"
      :popovertarget="drawerId"
      :aria-controls="drawerId"
      :aria-expanded="drawerOpen"
    >
      <NxIcon name="menu" />
      <span class="nx-visually-hidden">{{ word('menu') }}</span>
    </button>
    <div class="nx-admin-heading">
      <p class="nx-admin-title">{{ title }}</p>
      <p v-if="subtitle" class="nx-admin-subtitle">{{ subtitle }}</p>
    </div>
    <div class="nx-admin-search">
      <button type="button" class="nx-admin-search-btn" @click="emit('search')">
        <NxIcon name="search" />
        <span>{{ searchPlaceholder }}</span>
        <kbd class="nx-kbd">{{ searchHint }}</kbd>
      </button>
    </div>
    <div class="nx-admin-actions">
      <slot name="actions" />
      <button
        ref="userTrigger"
        type="button"
        class="nx-admin-user"
        aria-haspopup="menu"
        :aria-expanded="userOpen"
        :aria-controls="menuId"
        :popovertarget="menuId"
        :aria-label="user.menuLabel"
      >
        <span class="nx-avatar" role="img" :aria-label="user.name"><span aria-hidden="true">{{ initials(user.name) }}</span></span>
        <span class="nx-admin-user-text">
          <span class="nx-admin-user-name">{{ user.name }}</span>
          <span class="nx-admin-user-role">{{ user.role }}</span>
        </span>
        <NxIcon name="chevron-down" />
      </button>
      <div :id="menuId" ref="userPanel" class="nx-admin-user-menu" popover="auto" @keydown="onMenuKey">
        <div class="nx-admin-user-head">
          <p class="nx-admin-user-name">{{ user.name }}</p>
          <p class="nx-admin-user-role">{{ user.role }}</p>
        </div>
        <div role="menu" :aria-label="user.menuLabel">
          <template v-for="entry in user.menu" :key="entry.id">
            <hr v-if="entry.divider" class="nx-admin-user-divider" />
            <button v-else type="button" class="nx-admin-user-item" role="menuitem" @click="choose(entry)">
              <NxIcon v-if="entry.icon" :name="entry.icon" />
              <span>{{ entry.label }}</span>
            </button>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>
