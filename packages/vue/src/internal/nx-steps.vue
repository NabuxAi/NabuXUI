<script setup lang="ts">
/**
 * NxSteps — the Vue counterpart of ui-react's `Steps`: the trail of stages,
 * with the current one marked for assistive tech.
 */
import NxIcon from './icon';

const props = withDefaults(
  defineProps<{
    steps: Array<{ title: string; description?: string }>;
    /** Index of the step in progress; earlier ones are complete. */
    current: number;
    orientation?: 'horizontal' | 'vertical';
    ariaLabel?: string;
  }>(),
  { orientation: 'horizontal' },
);

const status = (i: number) => (i < props.current ? 'complete' : i === props.current ? 'current' : 'upcoming');
</script>

<template>
  <ol class="nx-steps" :data-orientation="orientation === 'vertical' ? 'vertical' : undefined" :aria-label="ariaLabel">
    <li v-for="(step, i) in steps" :key="i" class="nx-step" :data-status="status(i)" :aria-current="status(i) === 'current' ? 'step' : undefined">
      <span class="nx-step-marker"><NxIcon v-if="status(i) === 'complete'" name="check" /></span>
      <span class="nx-step-text">
        <span class="nx-step-title">{{ step.title }}</span>
        <span v-if="step.description" class="nx-step-description">{{ step.description }}</span>
      </span>
    </li>
  </ol>
</template>
