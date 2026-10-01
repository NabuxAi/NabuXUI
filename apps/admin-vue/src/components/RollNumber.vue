<script setup lang="ts">
/**
 * A rolling number — the `.nx-number` markup contract from ui-core's text
 * components: each digit is a 0–9 column moved to its value (core `numberParts`
 * + `localeDigits`), so a changing figure rolls in the locale's own digits.
 * With `roll` (default) the element is wired to core `reveal`, so the digits
 * also roll once when they first reach view.
 */
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { localeDigits, numberParts, reveal } from '@nabuxai/ui-core';
import { intlLocale, lang } from '../store';

const props = withDefaults(
  defineProps<{
    value: number;
    format?: Intl.NumberFormatOptions;
    /** Wire the one-shot reveal (the digits roll into view). */
    roll?: boolean;
  }>(),
  { format: undefined, roll: true },
);

const root = ref<HTMLElement | null>(null);
let stop: (() => void) | null = null;

const digits = computed(() => localeDigits(lang.value));
const parts = computed(() => numberParts(props.value, lang.value, props.format));
const plain = computed(() => new Intl.NumberFormat(intlLocale.value, props.format).format(props.value));

onMounted(() => {
  if (root.value && props.roll) stop = reveal(root.value, { once: true });
});
onBeforeUnmount(() => stop?.());
</script>

<template>
  <span ref="root" class="nx-number" data-nx-reveal :data-value="value">
    <span class="nx-visually-hidden">{{ plain }}</span>
    <span class="nx-number-roll" aria-hidden="true">
      <template v-for="(part, j) in parts" :key="j">
        <span v-if="part.kind === 'digit'" class="nx-digit" :style="{ '--d': part.value, '--nx-p': parts.length - 1 - j }">
          <span class="nx-digit-track"><span v-for="d in digits" :key="d">{{ d }}</span></span>
        </span>
        <span v-else class="nx-number-sep">{{ part.char }}</span>
      </template>
    </span>
  </span>
</template>
