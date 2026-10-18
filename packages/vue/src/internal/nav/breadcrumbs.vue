<script lang="ts">
/**
 * NxBreadcrumbs — the Vue counterpart of ui-react's `Breadcrumbs`: the
 * trail to here, the last crumb painted as the current page.
 */
import type { VueNode } from '../utils';

export interface NxBreadcrumbItem {
  label: VueNode;
  href?: string;
}

export interface NxBreadcrumbsProps {
  items: NxBreadcrumbItem[];
}
</script>

<script setup lang="ts">
import { ref } from 'vue';
import { NxNode, NxSmartLink, useNxT } from '../kit';

defineProps<NxBreadcrumbsProps>();

const root = ref<HTMLElement | null>(null);
const t = useNxT(root);
</script>

<template>
  <nav ref="root" class="nx-breadcrumbs" :aria-label="t('breadcrumb')">
    <ol>
      <li v-for="(item, i) in items" :key="i">
        <NxSmartLink v-if="item.href && i !== items.length - 1" :href="item.href">
          <NxNode :node="item.label" />
        </NxSmartLink>
        <span
          v-else
          :aria-current="i === items.length - 1 ? 'page' : undefined"
          :style="i === items.length - 1 ? { color: 'var(--nx-text)', fontWeight: 600 } : undefined"
        >
          <NxNode :node="item.label" />
        </span>
      </li>
    </ol>
  </nav>
</template>
