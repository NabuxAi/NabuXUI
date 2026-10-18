<script setup lang="ts">
/**
 * NxNumberTicker — the `nx-number` rolling odometer of ui-react's text family,
 * drawn from the same core `numberParts` / `localeDigits` so the DOM matches
 * token for token. Seat-local so the stat card stays self-contained; the text
 * family's own seat can serve the same markup.
 */
import { computed, type CSSProperties } from 'vue';
import { localeDigits, numberParts, reveal as revealBehavior } from '@nabuxai/ui-core';
import { useNxBehaviorReactive } from './utils';

const props = withDefaults(
  defineProps<{
    value: number;
    locale: string;
    format?: Intl.NumberFormatOptions;
    /** Roll up from zero when it scrolls into view (default) or show at once. */
    reveal?: boolean;
    /** Roll duration, ms. */
    duration?: number;
  }>(),
  { reveal: true },
);

const seat = useNxBehaviorReactive<HTMLElement, { once: boolean }>(revealBehavior, { once: true }, () => props.reveal !== false);

const parts = computed(() => numberParts(props.value, props.locale, props.format));
const digits = computed(() => localeDigits(props.locale));
const text = computed(() => parts.value.map((p) => p.char).join(''));
const style = computed(() => ({ ...(props.duration ? { '--nx-number-duration': `${props.duration}ms` } : null) }) as CSSProperties);
</script>

<template>
  <span ref="seat" class="nx-number" :data-nx-reveal="reveal ? '' : undefined" :style="style">
    <span class="nx-visually-hidden">{{ text }}</span>
    <span class="nx-number-roll" aria-hidden="true">
      <!-- Keyed from the right so the ones column stays the ones column as the number grows. -->
      <template v-for="(part, i) in parts" :key="parts.length - i">
        <span v-if="part.kind === 'digit'" class="nx-digit" :style="{ '--d': part.value, '--nx-p': parts.length - 1 - i }">
          <span class="nx-digit-track">
            <span v-for="digit in digits" :key="digit">{{ digit }}</span>
          </span>
        </span>
        <span v-else class="nx-number-sep">{{ part.char }}</span>
      </template>
    </span>
  </span>
</template>
