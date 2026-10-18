<script lang="ts">
/**
 * NxPagination — the Vue counterpart of ui-react's `Pagination`: numbered
 * pages with gaps, a spring pill on the current page, and buttons or links.
 */

/** Page numbers to show, with null for the gaps: 1 … 4 5 6 … 20. */
export function pageRange(page: number, count: number, siblings = 1): Array<number | null> {
  const pages = new Set([1, count, ...Array.from({ length: siblings * 2 + 1 }, (_, i) => page - siblings + i)].filter((p) => p >= 1 && p <= count));
  const sorted = [...pages].sort((a, b) => a - b);
  const out: Array<number | null> = [];
  sorted.forEach((p, i) => {
    if (i > 0 && p - sorted[i - 1]! > 1) out.push(p - sorted[i - 1]! === 2 ? p - 1 : null);
    out.push(p);
  });
  return out;
}

export interface NxPaginationProps {
  pageCount: number;
  /** …or links, for server-rendered pages. */
  hrefFor?: (page: number) => string;
  siblings?: number;
}
</script>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue';
import { NxSmartLink, useNxIndicator, useNxT } from '../kit';
import NxIcon from '../icon';

const props = withDefaults(defineProps<NxPaginationProps>(), { siblings: 1 });
const page = defineModel<number>('page', { required: true });

const root = ref<HTMLElement | null>(null);
const t = useNxT(root);
const ind = useNxIndicator(root);

const go = (target: number) => {
  page.value = target;
};

const disabled = (target: number) => target < 1 || target > props.pageCount;
const range = computed(() => pageRange(page.value, props.pageCount, props.siblings));

const sync = () => ind.value?.update(root.value?.querySelector('[aria-current="page"]') ?? null);
onMounted(sync);
watch([page, () => props.pageCount], sync, { flush: 'post' });
</script>

<template>
  <nav ref="root" class="nx-pagination" :aria-label="t('pagination')">
    <span class="nx-indicator" aria-hidden="true" />

    <!-- Previous -->
    <NxSmartLink v-if="hrefFor && !disabled(page - 1)" class="nx-page-link" :href="hrefFor(page - 1)" :aria-label="t('previous')">
      <NxIcon name="chevron-left" />
    </NxSmartLink>
    <button v-else type="button" class="nx-page-link" :disabled="disabled(page - 1)" :aria-disabled="disabled(page - 1) || undefined" :aria-label="t('previous')" @click="go(page - 1)">
      <NxIcon name="chevron-left" />
    </button>

    <template v-for="(p, i) in range" :key="p === null ? `gap-${i}` : p">
      <span v-if="p === null" class="nx-page-link" data-gap aria-hidden="true">…</span>
      <span v-else style="display: contents">
        <NxSmartLink
          v-if="hrefFor && !disabled(p)"
          class="nx-page-link"
          :href="hrefFor(p)"
          :aria-current="p === page ? 'page' : undefined"
          :aria-label="t('page', { page: p })"
        >
          {{ p }}
        </NxSmartLink>
        <button
          v-else
          type="button"
          class="nx-page-link"
          :disabled="disabled(p)"
          :aria-disabled="disabled(p) || undefined"
          :aria-current="p === page ? 'page' : undefined"
          :aria-label="t('page', { page: p })"
          @click="go(p)"
        >
          {{ p }}
        </button>
      </span>
    </template>

    <!-- Next -->
    <NxSmartLink v-if="hrefFor && !disabled(page + 1)" class="nx-page-link" :href="hrefFor(page + 1)" :aria-label="t('next')">
      <NxIcon name="chevron-right" />
    </NxSmartLink>
    <button v-else type="button" class="nx-page-link" :disabled="disabled(page + 1)" :aria-disabled="disabled(page + 1) || undefined" :aria-label="t('next')" @click="go(page + 1)">
      <NxIcon name="chevron-right" />
    </button>
  </nav>
</template>
