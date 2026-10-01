<script lang="ts">
  /**
   * The stats strip — the Svelte shape of the `stat-strip` block from
   * docs/FRAMEWORKS.md: rolling digit columns built with core `numberParts` /
   * `localeDigits`, revealed by core `reveal` (the group staggers, each number
   * rolls once when it reaches view).
   */
  import { onMount } from 'svelte';
  import { type IconName, localeDigits, numberParts, reveal } from '@nabuxai/ui-core';
  import { app } from '../store.svelte';
  import NxIcon from './NxIcon.svelte';

  export interface Stat {
    label: string;
    value: number;
    icon?: IconName;
    caption?: string;
  }

  let { stats, ariaLabel }: { stats: Stat[]; ariaLabel: string } = $props();

  let root: HTMLElement;

  const digits = $derived(localeDigits(app.lang));
  const rows = $derived(stats.map((stat) => ({ ...stat, parts: numberParts(stat.value, app.lang) })));

  onMount(() => {
    const cleanups: Array<() => void> = [];
    cleanups.push(reveal(root, { once: true, stagger: true }));
    for (const el of root.querySelectorAll<HTMLElement>('.nx-number[data-nx-reveal]')) {
      cleanups.push(reveal(el, { once: true }));
    }
    return () => cleanups.forEach((stop) => stop());
  });

  const readOut = (value: number) => value.toLocaleString(app.lang === 'fa' ? 'fa-IR' : 'en-US');
</script>

<dl bind:this={root} class="nx-stat-strip" data-nx-reveal="group" aria-label={ariaLabel}>
  {#each rows as stat, i (stat.label)}
    <div class="nx-stat-strip-item" style:--nx-i={i}>
      {#if stat.icon}<span class="nx-stat-strip-icon"><NxIcon name={stat.icon} /></span>{/if}
      <div class="nx-stat-strip-what">
        <dt class="nx-stat-strip-label">{stat.label}</dt>
        <dd class="nx-stat-strip-value">
          <span class="nx-number" data-nx-reveal data-value={stat.value}>
            <span class="nx-visually-hidden">{readOut(stat.value)}</span>
            <span class="nx-number-roll" aria-hidden="true">
              {#each stat.parts as part, j (j)}
                {#if part.kind === 'digit'}
                  <span class="nx-digit" style:--d={part.value} style:--nx-p={stat.parts.length - 1 - j}>
                    <span class="nx-digit-track">{#each digits as d (d)}{d}{/each}</span>
                  </span>
                {:else}
                  <span class="nx-number-sep">{part.char}</span>
                {/if}
              {/each}
            </span>
          </span>
          {#if stat.caption}<span class="nx-stat-strip-caption">{stat.caption}</span>{/if}
        </dd>
      </div>
    </div>
  {/each}
</dl>
