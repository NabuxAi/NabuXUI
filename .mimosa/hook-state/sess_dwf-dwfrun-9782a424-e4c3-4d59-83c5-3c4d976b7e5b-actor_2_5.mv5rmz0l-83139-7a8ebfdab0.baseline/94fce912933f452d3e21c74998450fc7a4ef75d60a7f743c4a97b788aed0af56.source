/**
 * The moving highlight behind tabs, segmented controls, nav pills, pagination
 * and the command palette. It measures a target inside a container and writes
 * --nx-ind-x / -y / -w / -h on the container; `.nx-indicator` springs there.
 *
 * States on the container (data-nx-indicator):
 *   absent  – never measured: the indicator is hidden
 *   init    – placed for the first time (or after hiding): no transition
 *   ready   – moves between targets with a spring
 *   hidden  – no target: fades out where it is
 */
import { type Cleanup, isBrowser } from './env';

export interface IndicatorController {
  /** Move to `target` (or hide with `null`). */
  update(target: Element | null): void;
  /** Re-measure the current target (after layout changes). */
  refresh(): void;
  destroy: Cleanup;
}

export function indicator(container: HTMLElement, initial: Element | null = null): IndicatorController {
  let current: Element | null = null;
  let frame = 0;

  const place = (target: Element) => {
    const c = container.getBoundingClientRect();
    const t = target.getBoundingClientRect();
    const x = t.left - c.left - container.clientLeft + container.scrollLeft;
    const y = t.top - c.top - container.clientTop + container.scrollTop;
    container.style.setProperty('--nx-ind-x', `${x}px`);
    container.style.setProperty('--nx-ind-y', `${y}px`);
    container.style.setProperty('--nx-ind-w', `${t.width}px`);
    container.style.setProperty('--nx-ind-h', `${t.height}px`);
  };

  const update = (target: Element | null) => {
    if (!isBrowser) return;
    current = target;
    const state = container.getAttribute('data-nx-indicator');

    if (!target) {
      if (state) container.setAttribute('data-nx-indicator', 'hidden');
      return;
    }

    if (!state || state === 'hidden') {
      container.setAttribute('data-nx-indicator', 'init');
      place(target);
      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(() => {
        frame = requestAnimationFrame(() => {
          if (container.getAttribute('data-nx-indicator') === 'init') container.setAttribute('data-nx-indicator', 'ready');
        });
      });
      return;
    }

    place(target);
  };

  const refresh = () => {
    if (current && current.isConnected) place(current);
  };

  const resize = isBrowser && 'ResizeObserver' in window ? new ResizeObserver(refresh) : null;
  resize?.observe(container);
  if (isBrowser) document.fonts?.ready.then(refresh).catch(() => {});

  if (initial) update(initial);

  return {
    update,
    refresh,
    destroy() {
      cancelAnimationFrame(frame);
      resize?.disconnect();
    },
  };
}
