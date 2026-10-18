<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface StepsProps {
    steps: Array<{ title: string | Snippet; description?: string | Snippet }>;
    /** Index of the step in progress; earlier ones are complete. */
    current: number;
    orientation?: 'horizontal' | 'vertical';
    class?: string;
    'aria-label'?: string;
  }
</script>

<script lang="ts">
  /**
   * Steps — the Svelte counterpart of ui-react's display family's Steps:
   * same DOM, same nx-* classes, same props.
   */
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';

  let { steps, current, orientation = 'horizontal', class: className, 'aria-label': label }: StepsProps = $props();
</script>

<ol class={cx('nx-steps', className)} data-orientation={orientation === 'vertical' ? 'vertical' : undefined} aria-label={label}>
  {#each steps as step, i (i)}
    {@const status = i < current ? 'complete' : i === current ? 'current' : 'upcoming'}
    <li class="nx-step" data-status={status} aria-current={status === 'current' ? 'step' : undefined}>
      <span class="nx-step-marker">{#if status === 'complete'}<Icon name="check" />{/if}</span>
      <span class="nx-step-text">
        <span class="nx-step-title">{#if typeof step.title === 'string'}{step.title}{:else}{@render step.title()}{/if}</span>
        {#if step.description}
          <span class="nx-step-description">{#if typeof step.description === 'string'}{step.description}{:else}{@render step.description()}{/if}</span>
        {/if}
      </span>
    </li>
  {/each}
</ol>
