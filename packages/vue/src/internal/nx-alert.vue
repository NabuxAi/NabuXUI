<script setup lang="ts">
/**
 * NxAlert — the Vue counterpart of ui-react's `Alert`: a tone-coloured
 * message with an optional icon, actions and a close button that animates the
 * alert away before unmounting it (`@dismiss` for the React `onDismiss`).
 */
import { computed, ref, useTemplateRef } from 'vue';
import { type IconName, leave } from '@nabuxai/ui-core';
import NxIcon from './icon';
import { useNxT } from './kit';
import { TONE_ICON } from './loaders';

const props = withDefaults(
  defineProps<{
    tone?: 'neutral' | 'accent' | 'success' | 'warning' | 'danger' | 'info';
    variant?: 'soft' | 'outline' | 'accent-bar';
    title?: string;
    /** A built-in icon, any node (slot), or false for none. Defaults to the tone's icon. */
    icon?: IconName | false;
    /** Show a close button; the alert collapses away when pressed. */
    dismissible?: boolean;
  }>(),
  // An explicit undefined default keeps Vue's Boolean casting from turning an
  // absent `icon` into `false` (React keeps it undefined: the tone's icon).
  { tone: 'info', icon: undefined },
);

const emit = defineEmits<{ dismiss: [] }>();

const root = useTemplateRef<HTMLDivElement>('root');
const t = useNxT(root);
const gone = ref(false);

const toneIcon = computed<IconName>(() => TONE_ICON[props.tone ?? 'info']);

function close() {
  if (!root.value) return;
  leave(root.value).then(() => {
    gone.value = true;
    emit('dismiss');
  });
}
</script>

<template>
  <div v-if="!gone" ref="root" class="nx-alert" :data-tone="tone" :data-variant="variant === 'soft' ? undefined : variant">
    <span v-if="icon !== false" class="nx-alert-icon"><NxIcon :name="icon === undefined ? toneIcon : icon" /></span>
    <span v-else />
    <div class="nx-alert-content">
      <p v-if="title || $slots.title" class="nx-alert-title">
        <slot name="title">{{ title }}</slot>
      </p>
      <div v-if="$slots.default" class="nx-alert-description">
        <slot />
      </div>
      <div v-if="$slots.actions" class="nx-alert-actions">
        <slot name="actions" />
      </div>
    </div>
    <button v-if="dismissible" type="button" class="nx-alert-close" :aria-label="t('dismiss')" @click="close">
      <NxIcon name="x" />
    </button>
  </div>
</template>
