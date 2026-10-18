<script module lang="ts">
  /** The shared choice shell — همتای ChoiceShell در ui-react/components/form.tsx (صادرهٔ عمومی نیست، مثل ری‌اکت). */
  import type { Snippet } from 'svelte';
  import type { HTMLAttributes } from 'svelte/elements';

  export interface Props extends HTMLAttributes<HTMLLabelElement> {
    control: Snippet;
    label?: Snippet | string;
    description?: Snippet | string;
    class?: string;
    style?: string;
  }
</script>

<script lang="ts">
  import { cx } from '../../internal/cx';

  let { control, label, description, class: className, style }: Props = $props();
</script>

{#if !label && !description}
  {@render control()}
{:else}
  <label class={cx('nx-choice', className)} {style}>
    {@render control()}
    <span class="nx-choice-text">
      {#if label}<span class="nx-choice-label">{#if typeof label === 'function'}{@render label()}{:else}{label}{/if}</span>{/if}
      {#if description}<span class="nx-choice-description">{#if typeof description === 'function'}{@render description()}{:else}{description}{/if}</span>{/if}
    </span>
  </label>
{/if}
