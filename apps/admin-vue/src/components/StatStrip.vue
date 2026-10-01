<script setup lang="ts">
/**
 * The stats strip — the Vue shape of the `stat-strip` block from
 * docs/FRAMEWORKS.md: rolling digit columns built with core `numberParts` /
 * `localeDigits`, revealed by core `reveal` (the group staggers, each number
 * rolls once when it reaches view).
 */
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { type IconName, localeDigits, numberParts, reveal } from '@nabuxai/ui-core';
import { intlLocale, lang } from '../store';
import NxIcon from './NxIcon.vue';

export interface Stat {
  label: string;
  value: number;
  icon?: IconName;
  caption?: string;
}

const props = defineProps<{ stats: Stat[]; label: string }>();

const root = ref<HTMLElement | null>(null);
const cleanups: Array<() => void> = [];

const digits = computed(() => localeDigits(lang.value));
const rows = computed(() => props.stats.map((stat) => ({ ...stat, parts: numberParts(stat.value, lang.value) })));

onMounted(() => {
  if (!root.value) return;
  cleanups.push(reveal(root.value, { once: true, stagger: true }));
  for (const el of root.value.querySelectorAll<HTMLElement>('.nx-number[data-nx-reveal]')) {
    cleanups.push(reveal(el, { once: true }));
  }
});
onBeforeUnmount(() => cleanups.forEach((stop) => stop()));

const readOut = (value: number) => value.toLocaleString(intlLocale.value);
</script>

<template>
  <dl ref="root" class="nx-stat-strip" data-nx-reveal="group" :aria-label="label">
    <div v-for="(stat, i) in rows" :key="stat.label" class="nx-stat-strip-item" :style="{ '--nx-i': i }">
      <span v-if="stat.icon" class="nx-stat-strip-icon"><NxIcon :name="stat.icon" /></span>
      <div class="nx-stat-strip-what">
        <dt class="nx-stat-strip-label">{{ stat.label }}</dt>
        <dd class="nx-stat-strip-value">
          <span class="nx-number" data-nx-reveal :data-value="stat.value">
            <span class="nx-visually-hidden">{{ readOut(stat.value) }}</span>
            <span class="nx-number-roll" aria-hidden="true">
              <template v-for="(part, j) in stat.parts" :key="j">
                <span
                  v-if="part.kind === 'digit'"
                  class="nx-digit"
                  :style="{ '--d': part.value, '--nx-p': stat.parts.length - 1 - j }"
                >
                  <span class="nx-digit-track"><span v-for="d in digits" :key="d">{{ d }}</span></span>
                </span>
                <span v-else class="nx-number-sep">{{ part.char }}</span>
              </template>
            </span>
          </span>
          <span v-if="stat.caption" class="nx-stat-strip-caption">{{ stat.caption }}</span>
        </dd>
      </div>
    </div>
  </dl>
</template>
