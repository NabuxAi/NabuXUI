<script module lang="ts">
  import type { Snippet } from 'svelte';

  export interface AccordionItem {
    id?: string;
    title: string | Snippet;
    content: string | Snippet;
  }

  export interface AccordionProps {
    items: AccordionItem[];
    /** Only one open at a time (native exclusive <details name>). */
    single?: boolean;
    /** Ids (or indexes) open at first. */
    defaultOpen?: Array<string | number>;
    variant?: 'joined' | 'separated';
    class?: string;
  }
</script>

<script lang="ts">
  import { nextId } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Content from '../../internal/content.svelte';

  let { items, single = true, defaultOpen = [], variant = 'joined', class: className }: AccordionProps = $props();

  const group = nextId('nx-acc');
</script>

<div class={cx('nx-accordion', className)} data-variant={variant === 'separated' ? 'separated' : undefined}>
  {#each items as item, i (item.id ?? i)}
    <details
      class="nx-accordion-item"
      name={single ? group : undefined}
      open={defaultOpen.includes(item.id ?? i) || undefined}
    >
      <summary class="nx-accordion-trigger">
        <Content of={item.title} />
      </summary>
      <div class="nx-accordion-content">
        <Content of={item.content} />
      </div>
    </details>
  {/each}
</div>
