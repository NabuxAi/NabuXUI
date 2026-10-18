<script lang="ts">
/**
 * NxMobileMenu — the Vue counterpart of ui-react's `MobileMenu`: the
 * two-level drill-down that slides inside the header's drawer, with the
 * hidden panel kept inert.
 */
import type { VueNode } from '../utils';
import type { NxNavItem } from './megamenu.vue';

export type { NxNavItem };

export interface NxMobileMenuProps {
  items: NxNavItem[];
  footer?: VueNode;
}
</script>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { NxNode, NxSmartLink, useNxT } from '../kit';
import { useNxInert } from '../utils';
import NxIcon from '../icon';

const props = defineProps<NxMobileMenuProps>();
const emit = defineEmits<{ navigate: [] }>();

const root = ref<HTMLDivElement | null>(null);
const subPanel = ref<HTMLDivElement | null>(null);
const sub = ref<number | null>(null);
const opener = ref<HTMLButtonElement | null>(null);
const t = useNxT(root);

useNxInert(root, () => sub.value !== null);
useNxInert(subPanel, () => sub.value === null);

watch(sub, (level) => {
  if (level !== null) subPanel.value?.querySelector<HTMLElement>('.nx-drilldown-back')?.focus();
  else opener.value?.focus();
});

const remember = (event: Event) => {
  opener.value = event.currentTarget as HTMLButtonElement;
};
</script>

<template>
  <div class="nx-drilldown">
    <div class="nx-drilldown-track" :style="{ '--nx-level': sub === null ? 0 : 1 }">
      <div ref="root" class="nx-drilldown-panel">
        <template v-for="(item, i) in items" :key="i">
          <button
            v-if="item.children"
            type="button"
            class="nx-drilldown-item"
            :style="{ '--nx-i': i }"
            :aria-expanded="sub === i"
            @click="remember($event); sub = i"
          >
            <span><NxNode :node="item.label" /></span>
            <NxIcon name="chevron-right" />
          </button>
          <NxSmartLink
            v-else
            class="nx-drilldown-item"
            :href="item.href ?? '#'"
            :aria-current="item.current ? 'page' : undefined"
            :style="{ '--nx-i': i }"
            @click="emit('navigate')"
          >
            <span><NxNode :node="item.label" /></span>
          </NxSmartLink>
        </template>
        <div v-if="footer" style="margin-block-start: var(--nx-space-4); display: grid; gap: var(--nx-space-2)">
          <NxNode :node="footer" />
        </div>
      </div>
      <div ref="subPanel" class="nx-drilldown-panel">
        <template v-if="sub !== null && items[sub]?.children">
          <button type="button" class="nx-drilldown-back" @click="sub = null">
            <NxIcon name="chevron-left" />
            {{ t('back') }}
          </button>
          <NxSmartLink
            v-for="(child, c) in items[sub]!.children"
            :key="c"
            class="nx-drilldown-item"
            :href="child.href"
            :style="{ '--nx-i': c }"
            @click="emit('navigate')"
          >
            <span><NxNode :node="child.label" /></span>
            <NxIcon v-if="child.icon" :name="child.icon" />
          </NxSmartLink>
        </template>
      </div>
    </div>
  </div>
</template>
