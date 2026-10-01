<script setup lang="ts" generic="R">
/**
 * The data table — the hand-written Vue twin of the `data-table` block
 * (nx-data-table): sorting through the core `sortRows`/`nextSort` pair and the
 * FLIP glide (`snapshotRows` + `playRowFlip`) after each sort, the group reveal
 * on mount, and a keyboard-scrollable box when the table overflows. Cells are
 * the page's: `#cell-<key>` slots, headers `#head-<key>`.
 */
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import {
  type SortState,
  nextSort,
  playRowFlip,
  reveal,
  snapshotRows,
  sortRows,
} from '@nabuxai/ui-core';
import { intlLocale } from '../store';

export interface Column<R> {
  key: string;
  label: string;
  sortable?: boolean;
  sortValue?: (row: R) => unknown;
  align?: 'start' | 'end';
  width?: string;
}

const props = withDefaults(
  defineProps<{
    rows: R[];
    columns: Column<R>[];
    rowKey?: (row: R, index: number) => string;
    caption: string;
    emptyText?: string;
    defaultSort?: SortState | null;
    maxHeight?: string;
  }>(),
  { rowKey: undefined, emptyText: undefined, defaultSort: null, maxHeight: undefined },
);

const scroller = ref<HTMLDivElement | null>(null);
const body = ref<HTMLTableSectionElement | null>(null);
const sort = ref<SortState | null>(props.defaultSort);
const scrollable = ref(false);
let flight: ReturnType<typeof snapshotRows> | null = null;
let stopReveal: (() => void) | null = null;

const keyOf = (row: R, index: number) => (props.rowKey ? props.rowKey(row, index) : String(index));

const sorted = computed(() => {
  if (!sort.value) return props.rows;
  const column = props.columns.find((c) => c.key === sort.value!.key);
  const value = column?.sortValue ? (row: R) => column.sortValue!(row) : undefined;
  return sortRows(props.rows, sort.value.key, sort.value.direction, { locale: intlLocale.value, value });
});

/** The fallback cell text when the page does not provide a slot for the column. */
const cellText = (row: R, column: Column<R>) => String((row as Record<string, unknown>)[column.key] ?? '');

onMounted(() => {
  if (scroller.value) stopReveal = reveal(scroller.value, { once: true });
  checkScroll();
});
onBeforeUnmount(() => stopReveal?.());

// A table wider or taller than its box can be scrolled from the keyboard.
function checkScroll() {
  const el = scroller.value;
  if (!el || typeof ResizeObserver === 'undefined') return;
  scrollable.value = el.scrollWidth > el.clientWidth + 1 || el.scrollHeight > el.clientHeight + 1;
}

const press = (key: string) => {
  if (body.value) flight = snapshotRows(Array.from(body.value.rows));
  sort.value = nextSort(sort.value, key);
  // A sort that lands on the same key still re-renders; drop stale snapshots.
  requestAnimationFrame(() => (flight = null));
};

watch(sort, () => void nextTick(flip));

function flip() {
  if (!flight || !body.value) return;
  const before = flight;
  flight = null;
  playRowFlip(Array.from(body.value.rows), before);
}

const directionOf = (key: string) => (sort.value?.key === key ? sort.value.direction : undefined);
</script>

<template>
  <div
    ref="scroller"
    class="nx-data-table"
    data-nx-reveal=""
    :tabindex="scrollable ? 0 : undefined"
    :role="scrollable ? 'region' : undefined"
    :aria-label="scrollable ? caption : undefined"
    :style="maxHeight ? { '--nx-table-max': maxHeight } : undefined"
    @resize="checkScroll"
  >
    <table>
      <caption class="nx-visually-hidden">{{ caption }}</caption>
      <thead>
        <tr>
          <th
            v-for="column in columns"
            :key="column.key"
            scope="col"
            :aria-sort="directionOf(column.key)"
            :data-align="column.align && column.align !== 'start' ? column.align : undefined"
            :style="column.width ? { inlineSize: column.width } : undefined"
          >
            <slot :name="`head-${column.key}`" :column="column">
              <button
                v-if="column.sortable"
                type="button"
                class="nx-data-table-sort"
                :data-direction="directionOf(column.key)"
                @click="press(column.key)"
              >
                <span>{{ column.label }}</span>
                <svg class="nx-data-table-sort-icon" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12 19V5M6 11l6-6 6 6" />
                </svg>
              </button>
              <template v-else>{{ column.label }}</template>
            </slot>
          </th>
        </tr>
      </thead>
      <tbody ref="body">
        <tr v-if="sorted.length === 0">
          <td class="nx-data-table-empty" :colspan="columns.length">{{ emptyText }}</td>
        </tr>
        <tr v-for="(row, index) in sorted" :key="keyOf(row, index)" :data-key="keyOf(row, index)" :style="{ '--nx-i': index }">
          <td
            v-for="column in columns"
            :key="column.key"
            :data-align="column.align && column.align !== 'start' ? column.align : undefined"
          >
            <slot :name="`cell-${column.key}`" :row="row" :index="index">{{ cellText(row, column) }}</slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
