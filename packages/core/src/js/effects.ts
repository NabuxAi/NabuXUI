/**
 * One-shot effects and small page behaviours: ripple, burst, dock, header
 * scroll state, autogrow, copy, and keyboard shortcuts.
 */
import { type Cleanup, canHover, isBrowser, prefersReducedMotion } from './env';

/** A ripple from the pointer on press. */
export function ripple(el: HTMLElement): Cleanup {
  if (!isBrowser) return () => {};
  const onDown = (event: PointerEvent) => {
    if (prefersReducedMotion() || event.button !== 0) return;
    const rect = el.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height) * 2.2;
    const wave = document.createElement('span');
    wave.className = 'nx-ripple';
    wave.setAttribute('aria-hidden', 'true');
    wave.style.setProperty('--nx-ripple-size', `${size}px`);
    wave.style.setProperty('--nx-ripple-x', `${event.clientX - rect.left}px`);
    wave.style.setProperty('--nx-ripple-y', `${event.clientY - rect.top}px`);
    el.appendChild(wave);
    wave.addEventListener('animationend', () => wave.remove(), { once: true });
  };
  el.addEventListener('pointerdown', onDown);
  return () => el.removeEventListener('pointerdown', onDown);
}

/** Particles thrown out from the element's centre (the like button). */
export function burst(el: HTMLElement, { count = 10, colors }: { count?: number; colors?: string[] } = {}): void {
  if (!isBrowser || prefersReducedMotion()) return;
  const palette = colors ?? ['var(--nx-danger)', 'var(--nx-gold)', 'var(--nx-accent)', 'var(--nx-glow)'];
  const host = document.createElement('span');
  host.className = 'nx-burst';
  host.setAttribute('aria-hidden', 'true');
  for (let i = 0; i < count; i++) {
    const particle = document.createElement('span');
    particle.className = 'nx-burst-particle';
    particle.style.setProperty('--nx-angle-deg', `${(360 / count) * i + Math.random() * 20}deg`);
    particle.style.setProperty('--nx-burst-color', palette[i % palette.length]!);
    host.appendChild(particle);
  }
  if (getComputedStyle(el).position === 'static') el.style.position = 'relative';
  el.appendChild(host);
  setTimeout(() => host.remove(), 800);
}

/** Dock magnification: items swell by their distance to the pointer. */
export function dock(el: HTMLElement, { item = '.nx-dock-item', range = 140, max = 1.6 }: { item?: string; range?: number; max?: number } = {}): Cleanup {
  if (!isBrowser || !canHover() || prefersReducedMotion()) return () => {};
  let frame = 0;
  let pointerX = 0;

  const apply = () => {
    frame = 0;
    for (const node of el.querySelectorAll<HTMLElement>(item)) {
      const rect = node.getBoundingClientRect();
      const distance = Math.abs(pointerX - (rect.left + rect.width / 2));
      const influence = Math.max(0, 1 - distance / range);
      // Cosine falloff: smooth at the peak and at the edge of the range.
      const scale = 1 + (max - 1) * (0.5 - Math.cos(influence * Math.PI) / 2);
      node.style.setProperty('--nx-scale', scale.toFixed(3));
    }
  };

  const onMove = (event: PointerEvent) => {
    pointerX = event.clientX;
    if (!frame) frame = requestAnimationFrame(apply);
  };
  const onLeave = () => {
    cancelAnimationFrame(frame);
    frame = 0;
    for (const node of el.querySelectorAll<HTMLElement>(item)) node.style.setProperty('--nx-scale', '1');
  };

  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerleave', onLeave);
  return () => {
    onLeave();
    el.removeEventListener('pointermove', onMove);
    el.removeEventListener('pointerleave', onLeave);
  };
}

/** Header scroll state: [data-scrolled] past `offset`, [data-hidden] while scrolling down. */
export function headerScroll(el: HTMLElement, { offset = 8, hide = false, tolerance = 6 }: { offset?: number; hide?: boolean; tolerance?: number } = {}): Cleanup {
  if (!isBrowser) return () => {};
  let last = window.scrollY;
  let frame = 0;

  const update = () => {
    frame = 0;
    const y = window.scrollY;
    el.toggleAttribute('data-scrolled', y > offset);
    if (hide) {
      if (y > last + tolerance && y > el.offsetHeight * 2) el.setAttribute('data-hidden', '');
      else if (y < last - tolerance || y <= offset) el.removeAttribute('data-hidden');
    }
    last = y;
  };

  const onScroll = () => {
    if (!frame) frame = requestAnimationFrame(update);
  };

  update();
  window.addEventListener('scroll', onScroll, { passive: true });
  return () => {
    cancelAnimationFrame(frame);
    window.removeEventListener('scroll', onScroll);
  };
}

/** Grow a textarea with its content where `field-sizing: content` is missing. */
export function autogrow(el: HTMLTextAreaElement): Cleanup {
  if (!isBrowser || CSS.supports('field-sizing', 'content')) return () => {};
  const resize = () => {
    el.style.height = 'auto';
    el.style.height = `${el.scrollHeight + (el.offsetHeight - el.clientHeight)}px`;
  };
  resize();
  el.addEventListener('input', resize);
  return () => el.removeEventListener('input', resize);
}

/** Copy text; resolves true when the clipboard accepted it. */
export async function copyText(text: string): Promise<boolean> {
  try {
    await navigator.clipboard.writeText(text);
    return true;
  } catch {
    return false;
  }
}

/**
 * Keyboard shortcut like "mod+k" (mod is ⌘ on Apple platforms, Ctrl elsewhere).
 * Ignored while the reader is typing in a field, unless `allowInInputs`.
 */
export function hotkey(combo: string, handler: (event: KeyboardEvent) => void, { allowInInputs = false } = {}): Cleanup {
  if (!isBrowser) return () => {};
  const parts = combo.toLowerCase().split('+');
  const key = parts.pop()!;
  const apple = /mac|iphone|ipad/i.test(navigator.platform || navigator.userAgent);
  const wants = {
    meta: parts.includes('meta') || (parts.includes('mod') && apple),
    ctrl: parts.includes('ctrl') || (parts.includes('mod') && !apple),
    shift: parts.includes('shift'),
    alt: parts.includes('alt'),
  };

  const onKey = (event: KeyboardEvent) => {
    const target = event.target as HTMLElement | null;
    if (!allowInInputs && !wants.meta && !wants.ctrl && target?.closest('input, textarea, select, [contenteditable]')) return;
    if (event.key.toLowerCase() !== key) return;
    if (event.metaKey !== wants.meta || event.ctrlKey !== wants.ctrl || event.shiftKey !== wants.shift || event.altKey !== wants.alt) return;
    event.preventDefault();
    handler(event);
  };

  window.addEventListener('keydown', onKey);
  return () => window.removeEventListener('keydown', onKey);
}

/** How the "mod" key is written on this platform. */
export function modKeyLabel(): string {
  return isBrowser && /mac|iphone|ipad/i.test(navigator.platform || navigator.userAgent) ? '⌘' : 'Ctrl';
}
