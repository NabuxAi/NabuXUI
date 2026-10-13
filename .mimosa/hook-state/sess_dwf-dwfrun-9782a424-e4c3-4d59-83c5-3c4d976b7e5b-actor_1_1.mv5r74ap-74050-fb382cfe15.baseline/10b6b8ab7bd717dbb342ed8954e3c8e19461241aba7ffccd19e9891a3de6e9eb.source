/**
 * Arrow-key movement between the items of a composite widget (tabs, menus,
 * toolbars, listboxes). Horizontal arrows follow the reading direction.
 */
import { direction } from './env';

export type Orientation = 'horizontal' | 'vertical' | 'both';

export interface RovingOptions {
  orientation?: Orientation;
  /** Wrap from the last item to the first and back. */
  loop?: boolean;
}

/**
 * Given a key and the current index among `count` items, returns the index to
 * move to, or null when the key is not a navigation key for this orientation.
 */
export function nextIndex(
  key: string,
  index: number,
  count: number,
  { orientation = 'horizontal', loop = true }: RovingOptions = {},
  dir: 1 | -1 = 1,
): number | null {
  if (count === 0) return null;
  const clamp = (i: number) => (loop ? (i + count) % count : Math.min(Math.max(i, 0), count - 1));
  const horizontal = orientation !== 'vertical';
  const vertical = orientation !== 'horizontal';

  switch (key) {
    case 'ArrowRight': return horizontal ? clamp(index + dir) : null;
    case 'ArrowLeft': return horizontal ? clamp(index - dir) : null;
    case 'ArrowDown': return vertical ? clamp(index + 1) : null;
    case 'ArrowUp': return vertical ? clamp(index - 1) : null;
    case 'Home': return 0;
    case 'End': return count - 1;
    default: return null;
  }
}

/** Items that can currently take focus: visible and not disabled. */
export function focusableItems(container: Element, selector: string): HTMLElement[] {
  return Array.from(container.querySelectorAll<HTMLElement>(selector)).filter(
    (el) => !el.hasAttribute('disabled') && el.getAttribute('aria-disabled') !== 'true' && el.offsetParent !== null,
  );
}

/**
 * Handle a keydown inside `container`: move focus among `selector` items and
 * return the newly focused element (or null if the key was not handled).
 */
export function roveFocus(event: KeyboardEvent, container: Element, selector: string, options: RovingOptions = {}): HTMLElement | null {
  const items = focusableItems(container, selector);
  const current = items.indexOf(document.activeElement as HTMLElement);
  const next = nextIndex(event.key, current < 0 ? 0 : current, items.length, options, direction(container));
  if (next === null) return null;
  event.preventDefault();
  const target = items[next]!;
  target.focus();
  return target;
}

/** Type-to-select: the next item whose text starts with the typed characters. */
export function createTypeahead(timeout = 600) {
  let buffer = '';
  let timer: ReturnType<typeof setTimeout> | undefined;

  return (key: string, items: HTMLElement[], from: number): HTMLElement | null => {
    if (key.length !== 1 || key === ' ') return null;
    buffer += key.toLocaleLowerCase();
    clearTimeout(timer);
    timer = setTimeout(() => { buffer = ''; }, timeout);
    const ordered = items.slice(from + 1).concat(items.slice(0, from + 1));
    return ordered.find((item) => (item.textContent ?? '').trim().toLocaleLowerCase().startsWith(buffer)) ?? null;
  };
}
