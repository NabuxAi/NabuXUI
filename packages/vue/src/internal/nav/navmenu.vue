<script lang="ts">
/**
 * NxNavMenu — the Vue counterpart of ui-react's `NavMenu`: a pill that
 * follows the pointer (or focus) across the links and springs home to the
 * current page.
 */
import type { VueNode } from '../utils';

export interface NxNavLinkItem {
  href: string;
  label: VueNode;
  current?: boolean;
}

export interface NxNavMenuProps {
  items: NxNavLinkItem[];
  ariaLabel?: string;
}
</script>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { NxNode, NxSmartLink, useNxIndicator } from '../kit';

const props = defineProps<NxNavMenuProps>();

const nav = ref<HTMLElement | null>(null);
const ind = useNxIndicator(nav);

const home = () => nav.value?.querySelector('[aria-current="page"]') ?? null;

onMounted(() => ind.value?.update(home()));
watch(() => props.items, () => ind.value?.update(home()), { flush: 'post', deep: true });

// The pill follows whichever link the pointer or focus is over, then goes home.
const follow = (event: { target: EventTarget | null }) => {
  const link = (event.target as Element | null)?.closest('.nx-navmenu-link');
  if (link) ind.value?.update(link);
};
</script>

<template>
  <nav
    ref="nav"
    class="nx-navmenu"
    :aria-label="ariaLabel"
    @pointerover="follow"
    @focusin="follow"
    @pointerleave="ind?.update(home())"
    @focusout="ind?.update(home())"
  >
    <ul>
      <li v-for="item in items" :key="item.href">
        <NxSmartLink class="nx-navmenu-link" :href="item.href" :aria-current="item.current ? 'page' : undefined">
          <NxNode :node="item.label" />
        </NxSmartLink>
      </li>
    </ul>
    <span class="nx-indicator" aria-hidden="true" />
  </nav>
</template>
