<script lang="ts">
/**
 * NxDock — the Vue counterpart of ui-react's `Dock`: the magnifying bar of
 * icons, driven by the core `dock` behaviour.
 */
import type { IconName } from '@nabuxai/ui-core';
import type { VueNode } from '../utils';

export interface NxDockItem {
  label: string;
  icon?: IconName | VueNode;
  href?: string;
  onClick?: () => void;
  current?: boolean;
}

export interface NxDockProps {
  items: NxDockItem[];
  variant?: 'glass' | 'metal';
  /** Resting icon size. */
  size?: string;
  ariaLabel?: string;
}
</script>

<script setup lang="ts">
import { dock } from '@nabuxai/ui-core';
import { NxNode, NxSmartLink } from '../kit';
import { useNxBehavior } from '../composable';
import NxIcon from '../icon';

withDefaults(defineProps<NxDockProps>(), { variant: 'glass' });

const seat = useNxBehavior<HTMLElement, Record<string, never>>(dock, {} as Record<string, never>);

/** A named built-in icon, or whatever node the item handed over instead. */
const iconName = (icon: IconName | VueNode | undefined): IconName | undefined => (typeof icon === 'string' ? (icon as IconName) : undefined);
</script>

<template>
  <nav
    ref="seat"
    class="nx-dock"
    :data-variant="variant === 'metal' ? 'metal' : undefined"
    :aria-label="ariaLabel"
    :style="size ? { '--nx-dock-size': size } : undefined"
  >
    <template v-for="item in items" :key="item.label">
      <NxSmartLink
        v-if="item.href"
        class="nx-dock-item"
        :href="item.href"
        :aria-label="item.label"
        :aria-current="item.current ? 'page' : undefined"
      >
        <NxIcon v-if="iconName(item.icon)" :name="iconName(item.icon)!" />
        <NxNode v-else :node="item.icon" />
        <span class="nx-dock-label" aria-hidden="true">{{ item.label }}</span>
      </NxSmartLink>
      <button v-else type="button" class="nx-dock-item" :aria-label="item.label" @click="item.onClick?.()">
        <NxIcon v-if="iconName(item.icon)" :name="iconName(item.icon)!" />
        <NxNode v-else :node="item.icon" />
        <span class="nx-dock-label" aria-hidden="true">{{ item.label }}</span>
      </button>
    </template>
  </nav>
</template>
