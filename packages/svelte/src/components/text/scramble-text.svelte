<script lang="ts">
  /**
   * ScrambleText — همتای Svelte برای ScrambleText در ui-react/components/text.tsx:
   * گلیف‌های تصادفی می‌چرخند تا هر جایگاه روی نویسهٔ واقعی بنشیند.
   */
  import { scramble, type Cleanup } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import { untrack } from 'svelte';
  import type { HTMLAttributes } from 'svelte/elements';

  interface Props extends HTMLAttributes<HTMLElement> {
    /** متن — همتای children رشته‌ای در React. */
    text: string;
    /** When to decode: on mount, when scrolled into view, or on hover. */
    trigger?: 'mount' | 'view' | 'hover';
    duration?: number;
    /** Glyphs to cycle: latin, persian, cuneiform, or your own string. */
    charset?: string;
    /** The element to render — React's polymorphic `as`. */
    as?: string;
    class?: string;
  }

  let { text, trigger = 'view', duration, charset, as = 'span', class: className, onpointerenter, ...rest }: Props = $props();

  let host: HTMLElement | undefined;
  let textEl: HTMLSpanElement | undefined;
  let stop: Cleanup = () => {};

  const run = () => {
    const el = textEl;
    if (!el) return;
    stop();
    // گزینه‌ها untracked خوانده می‌شوند تا تغییر duration/charset افکت را دوباره اجرا نکند؛ مثل deps ری‌اکت.
    stop = untrack(() => scramble(el, text, { duration, charset }));
  };

  $effect(() => {
    if (trigger === 'mount') run();
    if (trigger !== 'view' || !host) return;
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry?.isIntersecting) {
          run();
          observer.disconnect();
        }
      },
      { threshold: 0.4 },
    );
    observer.observe(host);
    return () => {
      observer.disconnect();
      stop();
    };
  });

  function handlePointerEnter(event: Parameters<NonNullable<typeof onpointerenter>>[0]) {
    if (trigger === 'hover') run();
    onpointerenter?.(event);
  }
</script>

<svelte:element this={as} class={cx('nx-scramble', className)} bind:this={host} onpointerenter={handlePointerEnter} {...rest}>
  <span class="nx-visually-hidden">{text}</span>
  <span bind:this={textEl} aria-hidden="true">{text}</span>
</svelte:element>
