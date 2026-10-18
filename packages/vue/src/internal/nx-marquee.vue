<script setup lang="ts">
/**
 * NxMarquee — the Vue counterpart of ui-react's `Marquee`: a seamless loop
 * with a pause button, because moving content must be stoppable.
 */
import { Comment, computed, ref, useSlots, useTemplateRef, type CSSProperties, type VNode } from 'vue';
import NxIcon from './icon';
import { NxNode, useNxT } from './kit';
import { useNxInert } from './utils';

const props = withDefaults(
  defineProps<{
    items?: Array<string | number | VNode>;
    /** Seconds for one full loop. */
    duration?: number;
    reverse?: boolean;
    gap?: string;
    /** A pause button (on by default: moving content must be stoppable). */
    controls?: boolean;
    ariaLabel?: string;
  }>(),
  { duration: 40, controls: true },
);

const root = useTemplateRef<HTMLElement>('root');
const t = useNxT(root);

const paused = ref(false);
const copy = useTemplateRef<HTMLUListElement>('copy');
// The second copy only exists for the seamless loop: nobody should tab into it.
useNxInert(copy, () => true);

const slots = useSlots();
const seatItems = computed<Array<string | number | VNode>>(() => {
  if (props.items) return props.items;
  return (slots.default?.() ?? []).filter((vnode): vnode is VNode => vnode.type !== Comment);
});

const style = computed(
  () =>
    ({
      '--nx-duration': `${props.duration}s`,
      ...(props.gap ? { '--nx-marquee-gap': props.gap } : null),
    }) as CSSProperties,
);
</script>

<template>
  <section
    ref="root"
    class="nx-marquee"
    :aria-label="ariaLabel"
    :data-direction="reverse ? 'reverse' : undefined"
    :data-paused="paused ? '' : undefined"
    :style="style"
  >
    <div class="nx-marquee-track">
      <ul class="nx-marquee-group">
        <li v-for="(item, i) in seatItems" :key="i"><NxNode :node="item" /></li>
      </ul>
      <ul ref="copy" class="nx-marquee-group" aria-hidden="true">
        <li v-for="(item, i) in seatItems" :key="i"><NxNode :node="item" /></li>
      </ul>
    </div>
    <button
      v-if="controls"
      type="button"
      class="nx-marquee-toggle"
      :aria-pressed="paused"
      :aria-label="paused ? t('play') : t('pause')"
      @click="paused = !paused"
    >
      <NxIcon :name="paused ? 'play' : 'pause'" />
    </button>
  </section>
</template>
