/**
 * Behaviours of the liquid-glass components. Each takes the component's root,
 * finds its parts by class, and returns a cleanup or a small controller. React
 * and Alpine render the same markup (see css/blocks/glass.css) and call these,
 * so a Livewire page and a React page move identically.
 */
import { type Cleanup, isBrowser, prefersReducedMotion } from '../env';
import { type LiquidLensOptions, glassPane, liquidLens } from '../glass';
import { indicator } from '../indicator';

export type LensTuning = Pick<LiquidLensOptions, 'bezel' | 'curvature' | 'refraction' | 'zoom' | 'chroma' | 'frost'>;

/** How hard each component's lens bends, tuned by eye on real content. */
export const lensTunings = {
  segmented: { zoom: 1.12, bezel: 9, refraction: 5, curvature: 0.7 },
  dock: { zoom: 1.22, bezel: 10, refraction: 7, curvature: 0.65, chroma: 0.5 },
  switch: { zoom: 1.25, bezel: 8, refraction: 5, curvature: 0.7 },
  slider: { zoom: 1.4, bezel: 7, refraction: 4, curvature: 0.75 },
  tabBar: { zoom: 1.1, bezel: 10, refraction: 6, curvature: 0.7 },
  readingGlass: { zoom: 1.55, bezel: 16, refraction: 10, curvature: 0.8, chroma: 0.6 },
} satisfies Record<string, LensTuning>;

export interface GlassController {
  /** Re-read the state from the DOM (after the framework re-rendered). */
  refresh(): void;
  destroy: Cleanup;
}

const idle: GlassController = { refresh() {}, destroy() {} };

function part<T extends HTMLElement = HTMLElement>(root: HTMLElement, selector: string): T | null {
  return root.querySelector<T>(selector);
}

function pressState(root: HTMLElement): Cleanup {
  const on = (event: PointerEvent) => {
    if (event.button === 0) root.setAttribute('data-pressed', '');
  };
  const off = () => root.removeAttribute('data-pressed');
  root.addEventListener('pointerdown', on);
  root.addEventListener('pointerup', off);
  root.addEventListener('pointercancel', off);
  root.addEventListener('pointerleave', off);
  return () => {
    off();
    root.removeEventListener('pointerdown', on);
    root.removeEventListener('pointerup', off);
    root.removeEventListener('pointercancel', off);
    root.removeEventListener('pointerleave', off);
  };
}

/** Selects a radio the way a click would, so wire:model and x-model hear it. */
function choose(input: HTMLInputElement): void {
  if (input.checked) return;
  input.checked = true;
  input.dispatchEvent(new Event('input', { bubbles: true }));
  input.dispatchEvent(new Event('change', { bubbles: true }));
}

/* ---- Segmented control ------------------------------------------------------ */

export interface GlassSegmentedOptions {
  lens?: LensTuning;
  /** Bend the backdrop at the rim (Chromium). */
  refract?: boolean;
  /** Called when a drag lands on an option. Default: check that option's radio. */
  onPick?: (value: string) => void;
}

/**
 * The selection lens of `.nx-glass-seg`: follows the checked radio, swells
 * while held, can be dragged along the track and snaps to the nearest option
 * when let go. A tap without a drag reaches the radio as a normal click.
 */
export function glassSegmented(root: HTMLElement, { lens = lensTunings.segmented, refract = true, onPick }: GlassSegmentedOptions = {}): GlassController {
  const track = part(root, '.nx-glass-seg-track');
  const lensEl = part(root, ':scope > .nx-glass-seg-lens');
  if (!isBrowser || !track || !lensEl) return idle;

  const ind = indicator(root);
  const optics = liquidLens(track, { lens: lensEl, ...lens });
  const pane = refract ? glassPane(root) : () => {};
  const options = () => Array.from(root.querySelectorAll<HTMLElement>('.nx-glass-seg-option'));
  const inputOf = (option: Element) => option.querySelector<HTMLInputElement>('input');
  const checked = () => options().find((option) => inputOf(option)?.checked) ?? null;
  const refresh = () => ind.update(checked());

  let drag: { id: number; x: number; moved: boolean } | null = null;

  const nearest = (clientX: number) => {
    let best: HTMLElement | null = null;
    let distance = Infinity;
    for (const option of options()) {
      if (inputOf(option)?.disabled) continue;
      const box = option.getBoundingClientRect();
      const d = Math.abs(box.left + box.width / 2 - clientX);
      if (d < distance) {
        distance = d;
        best = option;
      }
    }
    return best;
  };

  const down = (event: PointerEvent) => {
    if (event.button > 0 || drag) return;
    drag = { id: event.pointerId, x: event.clientX, moved: false };
    root.setAttribute('data-pressed', '');
  };

  const move = (event: PointerEvent) => {
    if (!drag || drag.id !== event.pointerId) return;
    if (!drag.moved) {
      if (Math.abs(event.clientX - drag.x) < 5) return;
      // Only a real drag captures the pointer, so a plain tap still reaches the radio.
      drag.moved = true;
      root.setPointerCapture(event.pointerId);
      root.setAttribute('data-dragging', '');
    }
    const all = options();
    const first = all[0]?.getBoundingClientRect();
    const last = all[all.length - 1]?.getBoundingClientRect();
    if (!first || !last) return;
    const box = root.getBoundingClientRect();
    const origin = box.left + root.clientLeft;
    const width = parseFloat(root.style.getPropertyValue('--nx-ind-w')) || first.width;
    const min = Math.min(first.left, last.left) - origin;
    const max = Math.max(first.right, last.right) - origin - width;
    const x = Math.min(max, Math.max(min, event.clientX - origin - width / 2));
    root.style.setProperty('--nx-ind-x', `${x}px`);
    optics.sync();
  };

  const up = (event: PointerEvent) => {
    if (!drag || drag.id !== event.pointerId) return;
    const moved = drag.moved;
    drag = null;
    root.removeAttribute('data-pressed');
    if (!moved) return;
    root.removeAttribute('data-dragging');
    const target = nearest(event.clientX);
    const input = target ? inputOf(target) : null;
    if (input && !input.checked) {
      if (onPick) onPick(input.value);
      else choose(input);
    }
    refresh();
  };

  root.addEventListener('pointerdown', down);
  root.addEventListener('pointermove', move);
  root.addEventListener('pointerup', up);
  root.addEventListener('pointercancel', up);
  root.addEventListener('change', refresh);
  refresh();

  return {
    refresh,
    destroy() {
      root.removeEventListener('pointerdown', down);
      root.removeEventListener('pointermove', move);
      root.removeEventListener('pointerup', up);
      root.removeEventListener('pointercancel', up);
      root.removeEventListener('change', refresh);
      root.removeAttribute('data-pressed');
      root.removeAttribute('data-dragging');
      pane();
      optics.destroy();
      ind.destroy();
    },
  };
}

/* ---- Dock ------------------------------------------------------------------- */

export interface GlassDockOptions {
  lens?: LensTuning;
  refract?: boolean;
}

/** `.nx-glass-dock`: the magnifier glides to the item under the pointer or focus, with its name above. */
export function glassDock(root: HTMLElement, { lens = lensTunings.dock, refract = true }: GlassDockOptions = {}): Cleanup {
  const list = part(root, '.nx-glass-dock-items');
  const lensEl = part(root, ':scope > .nx-glass-dock-lens');
  const tip = part(root, ':scope > .nx-glass-dock-tip');
  if (!isBrowser || !list || !lensEl) return () => {};

  const ind = indicator(root);
  const optics = liquidLens(list, { lens: lensEl, ...lens });
  const pane = refract ? glassPane(root) : () => {};
  const pressed = pressState(root);

  const show = (event: Event) => {
    const item = (event.target as Element).closest<HTMLElement>('.nx-glass-dock-item');
    if (!item || !root.contains(item)) return;
    ind.update(item);
    root.setAttribute('data-active', '');
    if (tip) tip.textContent = item.getAttribute('aria-label') ?? '';
  };
  const hide = () => {
    if (!root.matches(':focus-within')) root.removeAttribute('data-active');
  };
  const blur = (event: FocusEvent) => {
    if (!root.contains(event.relatedTarget as Node | null)) root.removeAttribute('data-active');
  };

  root.addEventListener('pointerover', show);
  root.addEventListener('focusin', show);
  root.addEventListener('pointerleave', hide);
  root.addEventListener('focusout', blur);

  return () => {
    root.removeEventListener('pointerover', show);
    root.removeEventListener('focusin', show);
    root.removeEventListener('pointerleave', hide);
    root.removeEventListener('focusout', blur);
    root.removeAttribute('data-active');
    pressed();
    pane();
    optics.destroy();
    ind.destroy();
  };
}

/* ---- Switch ----------------------------------------------------------------- */

/** `.nx-glass-switch`: the knob bends the track while it is held (CSS turns the power up). */
export function glassSwitch(root: HTMLElement, { lens = lensTunings.switch }: { lens?: LensTuning } = {}): Cleanup {
  const track = part(root, '.nx-glass-switch-track');
  const thumb = part(root, '.nx-glass-switch-thumb');
  if (!isBrowser || !track || !thumb) return () => {};
  const optics = liquidLens(track, { lens: thumb, ...lens });
  return () => optics.destroy();
}

/* ---- Slider ----------------------------------------------------------------- */

export interface GlassSliderOptions {
  lens?: LensTuning;
  /**
   * Write --p from the input as it moves. React leaves this off and renders
   * --p from its own state, so a rejected change snaps back.
   */
  track?: boolean;
}

/** `.nx-glass-slider`: while the range is dragged the thumb turns into a magnifier. */
export function glassSlider(root: HTMLElement, { lens = lensTunings.slider, track = true }: GlassSliderOptions = {}): GlassController {
  const input = part<HTMLInputElement>(root, '.nx-glass-slider-input');
  const bed = part(root, '.nx-glass-slider-bed');
  const thumb = part(root, '.nx-glass-slider-thumb');
  if (!isBrowser || !input || !bed || !thumb) return idle;

  const optics = liquidLens(bed, { lens: thumb, ...lens });
  const place = () => {
    if (track) {
      const min = Number(input.min || 0);
      const max = Number(input.max || 100);
      const p = max > min ? (Math.min(max, Math.max(min, Number(input.value))) - min) / (max - min) : 0;
      root.style.setProperty('--p', p.toFixed(4));
    }
    optics.sync();
  };
  const stop = () => {
    root.removeAttribute('data-dragging');
    window.removeEventListener('pointerup', stop);
    window.removeEventListener('pointercancel', stop);
  };
  const start = (event: PointerEvent) => {
    if (input.disabled || event.button > 0) return;
    root.setAttribute('data-dragging', '');
    window.addEventListener('pointerup', stop);
    window.addEventListener('pointercancel', stop);
  };

  input.addEventListener('input', place);
  input.addEventListener('pointerdown', start);
  place();

  return {
    refresh: place,
    destroy() {
      stop();
      input.removeEventListener('input', place);
      input.removeEventListener('pointerdown', start);
      optics.destroy();
    },
  };
}

/* ---- Tab bar ---------------------------------------------------------------- */

export interface GlassTabBarOptions {
  lens?: LensTuning;
  refract?: boolean;
  /**
   * Shrink the bar while the page scrolls down and grow it back on the way up.
   * `true` watches the window; an element watches that scrolling container.
   */
  minimize?: boolean | HTMLElement | null;
}

/** `.nx-glass-tabbar`: the lens sits on the tab with aria-current; the bar can shrink while you read. */
export function glassTabBar(root: HTMLElement, { lens = lensTunings.tabBar, refract = true, minimize = false }: GlassTabBarOptions = {}): GlassController {
  const list = part(root, '.nx-glass-tabbar-items');
  const lensEl = part(root, ':scope > .nx-glass-tabbar-lens');
  if (!isBrowser || !list || !lensEl) return idle;

  const ind = indicator(root);
  const optics = liquidLens(list, { lens: lensEl, ...lens });
  const pane = refract ? glassPane(root) : () => {};
  const pressed = pressState(root);
  const refresh = () => ind.update(root.querySelector('.nx-glass-tab[aria-current]'));

  let unscroll: Cleanup = () => {};
  if (minimize) {
    const source = minimize instanceof HTMLElement ? minimize : null;
    const read = () => (source ? source.scrollTop : window.scrollY);
    let last = read();
    const onScroll = () => {
      const y = read();
      const delta = y - last;
      if (Math.abs(delta) < 6) return;
      root.toggleAttribute('data-compact', delta > 0 && y > 48);
      last = y;
    };
    const target: HTMLElement | Window = source ?? window;
    target.addEventListener('scroll', onScroll, { passive: true });
    unscroll = () => target.removeEventListener('scroll', onScroll);
  }

  refresh();
  return {
    refresh,
    destroy() {
      unscroll();
      pressed();
      pane();
      optics.destroy();
      ind.destroy();
    },
  };
}

/* ---- Reading glass ---------------------------------------------------------- */

export interface ReadingGlassOptions {
  lens?: LensTuning;
  /** Where the lens starts, as fractions of the area (0–1). */
  x?: number;
  y?: number;
}

/**
 * `.nx-reading-glass`: a lens over live content that can be picked up, thrown
 * (it coasts, and bounces softly off the edges) and moved with the arrow keys.
 */
export function readingGlass(host: HTMLElement, { lens = lensTunings.readingGlass, x = 0.5, y = 0.5 }: ReadingGlassOptions = {}): Cleanup {
  const content = part(host, ':scope > .nx-reading-glass-content');
  const lensEl = part(host, ':scope > .nx-reading-glass-lens');
  if (!isBrowser || !content || !lensEl) return () => {};

  const optics = liquidLens(content, { lens: lensEl, ...lens });
  const pos = { x: 0, y: 0, vx: 0, vy: 0 };
  let frame = 0;
  let held: number | null = null;
  let start = { x: 0, y: 0 };
  let samples: Array<{ x: number; y: number; t: number }> = [];

  const bounds = () => ({
    w: Math.max(0, host.clientWidth - lensEl.offsetWidth),
    h: Math.max(0, host.clientHeight - lensEl.offsetHeight),
  });

  const write = () => {
    lensEl.style.setProperty('--nx-lens-x', `${pos.x.toFixed(1)}px`);
    lensEl.style.setProperty('--nx-lens-y', `${pos.y.toFixed(1)}px`);
    optics.sync();
  };

  /** Momentum after a throw: friction, and a soft bounce off the edges. */
  const coast = () => {
    cancelAnimationFrame(frame);
    let last = performance.now();
    const step = (now: number) => {
      const dt = Math.min(32, now - last);
      last = now;
      const b = bounds();
      pos.x += pos.vx * dt;
      pos.y += pos.vy * dt;
      const friction = Math.exp(-dt / 260);
      pos.vx *= friction;
      pos.vy *= friction;
      if (pos.x < 0 || pos.x > b.w) {
        pos.x = Math.min(b.w, Math.max(0, pos.x));
        pos.vx *= -0.45;
      }
      if (pos.y < 0 || pos.y > b.h) {
        pos.y = Math.min(b.h, Math.max(0, pos.y));
        pos.vy *= -0.45;
      }
      write();
      frame = Math.hypot(pos.vx, pos.vy) > 0.02 ? requestAnimationFrame(step) : 0;
    };
    frame = requestAnimationFrame(step);
  };

  const down = (event: PointerEvent) => {
    // One finger at a time: a second touch mid-drag is ignored.
    if (event.button > 0 || held !== null) return;
    cancelAnimationFrame(frame);
    held = event.pointerId;
    lensEl.setPointerCapture(event.pointerId);
    lensEl.setAttribute('data-held', '');
    start = { x: event.clientX - pos.x, y: event.clientY - pos.y };
    samples = [{ x: event.clientX, y: event.clientY, t: event.timeStamp }];
  };

  const move = (event: PointerEvent) => {
    if (event.pointerId !== held) return;
    const b = bounds();
    // Past the edge the lens follows with friction instead of stopping dead.
    const damp = (v: number, max: number) => (v < 0 ? v * 0.3 : v > max ? max + (v - max) * 0.3 : v);
    pos.x = damp(event.clientX - start.x, b.w);
    pos.y = damp(event.clientY - start.y, b.h);
    samples = [...samples.filter((s) => event.timeStamp - s.t < 80), { x: event.clientX, y: event.clientY, t: event.timeStamp }];
    write();
  };

  const up = (event: PointerEvent) => {
    if (event.pointerId !== held) return;
    held = null;
    lensEl.removeAttribute('data-held');
    const first = samples[0];
    const last = samples[samples.length - 1];
    const dt = last && first ? last.t - first.t : 0;
    const still = prefersReducedMotion() || dt <= 0;
    pos.vx = still ? 0 : (last.x - first.x) / dt;
    pos.vy = still ? 0 : (last.y - first.y) / dt;
    coast();
  };

  // The lens lives on a 2D surface, so arrows move it where they point in any writing direction.
  const key = (event: KeyboardEvent) => {
    const size = event.shiftKey ? 40 : 8;
    const b = bounds();
    const moves: Record<string, [number, number]> = { ArrowLeft: [-size, 0], ArrowRight: [size, 0], ArrowUp: [0, -size], ArrowDown: [0, size] };
    if (event.key === 'Home') {
      pos.x = b.w / 2;
      pos.y = b.h / 2;
    } else if (moves[event.key]) {
      const [dx, dy] = moves[event.key];
      pos.x = Math.min(b.w, Math.max(0, pos.x + dx));
      pos.y = Math.min(b.h, Math.max(0, pos.y + dy));
    } else return;
    event.preventDefault();
    cancelAnimationFrame(frame);
    write();
  };

  const keepInside = () => {
    const b = bounds();
    pos.x = Math.min(b.w, Math.max(0, pos.x));
    pos.y = Math.min(b.h, Math.max(0, pos.y));
    write();
  };

  const b = bounds();
  pos.x = b.w * x;
  pos.y = b.h * y;
  write();

  lensEl.addEventListener('pointerdown', down);
  lensEl.addEventListener('pointermove', move);
  lensEl.addEventListener('pointerup', up);
  lensEl.addEventListener('pointercancel', up);
  lensEl.addEventListener('keydown', key);
  const resize = 'ResizeObserver' in window ? new ResizeObserver(keepInside) : null;
  resize?.observe(host);

  return () => {
    cancelAnimationFrame(frame);
    resize?.disconnect();
    lensEl.removeEventListener('pointerdown', down);
    lensEl.removeEventListener('pointermove', move);
    lensEl.removeEventListener('pointerup', up);
    lensEl.removeEventListener('pointercancel', up);
    lensEl.removeEventListener('keydown', key);
    optics.destroy();
  };
}
