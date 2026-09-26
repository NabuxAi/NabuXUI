/**
 * Liquid glass: refraction for live DOM, in every browser.
 *
 * A lens is an SVG filter whose feDisplacementMap reads a small generated map.
 * Red and green move each pixel (128 = stay put), alpha is the lens shape.
 * Two ways to use one:
 *
 * - Lens mode (Chrome, Safari, Firefox): the filter goes on the element that
 *   holds the content, and the map is placed wherever a lens element sits on
 *   top of it. Segmented pills, dock magnifiers, switch and slider thumbs and
 *   the reading glass work this way. Text under the lens stays selectable and
 *   buttons stay clickable, because a filter only changes pixels.
 * - Pane mode (Chromium): `backdrop-filter: url()` bends whatever is behind a
 *   floating surface. Other engines keep the frosted CSS material, which is
 *   the same look without the bent rim.
 *
 * Light (rims, the specular sweep) is CSS, driven by one global --nx-light angle.
 */
import { type Cleanup, isBrowser, prefersReducedMotion } from './env';

const SVG_NS = 'http://www.w3.org/2000/svg';

const clamp = (value: number, min: number, max: number) => Math.min(max, Math.max(min, value));

/* ------------------------------------------------------------------------- */
/* The map                                                                   */
/* ------------------------------------------------------------------------- */

export interface LensShape {
  /** Lens size in CSS px. */
  width: number;
  height: number;
  /** Corner radius in px, clamped to half the short side. Default: fully round. */
  radius?: number;
  /** Width of the refracting rim in px. */
  bezel?: number;
  /** Rim profile: 0 is a straight bevel, 1 a steep rounded edge. */
  curvature?: number;
  /**
   * How far the rim bends, in px. Positive pulls what lies beyond the rim into
   * it (a thick glass edge); negative stretches the lens's own content outward.
   */
  refraction?: number;
  /** Magnification across the whole lens (1 = none). */
  zoom?: number;
  /** Map pixels per CSS px. */
  density?: number;
}

export interface LensMap {
  /** Map size in pixels. */
  width: number;
  height: number;
  /** RGBA: R/G displacement around 128, B unused (128), A the lens shape. */
  data: Uint8ClampedArray;
  /** The feDisplacementMap `scale` (CSS px) that decodes the map at full strength. */
  scale: number;
}

/**
 * Computes a displacement map for a rounded-rectangle lens. Pure (no DOM), so
 * it can be tested and run anywhere.
 */
export function lensMap(shape: LensShape): LensMap {
  const w = Math.max(1, shape.width);
  const h = Math.max(1, shape.height);
  const hw = w / 2;
  const hh = h / 2;
  const short = Math.min(hw, hh);
  const density = shape.density ?? (Math.max(w, h) <= 200 ? 2 : 1);
  const mw = Math.max(2, Math.round(w * density));
  const mh = Math.max(2, Math.round(h * density));
  const radius = clamp(shape.radius ?? short, 0, short);
  const bezel = clamp(shape.bezel ?? short * 0.5, 0.5, short);
  const power = 1 + clamp(shape.curvature ?? 0.6, 0, 1) * 2.5;
  const refraction = shape.refraction ?? bezel * 0.8;
  const zoom = shape.zoom ?? 1;
  const pull = zoom > 0 ? 1 - 1 / zoom : 0;
  // Where a straight side meets the next one inside the lens, blend the two
  // normals instead of flipping, so a wide rim has no crease on the diagonal.
  const soft = Math.max(0.5, bezel * 0.35);

  const size = mw * mh;
  const ox = new Float32Array(size);
  const oy = new Float32Array(size);
  const alpha = new Float32Array(size);
  let max = 0;

  for (let iy = 0; iy < mh; iy += 1) {
    const py = (iy + 0.5) / density - hh;
    const ay = Math.abs(py);
    const sy = py < 0 ? -1 : 1;
    for (let ix = 0; ix < mw; ix += 1) {
      const px = (ix + 0.5) / density - hw;
      const ax = Math.abs(px);
      const sx = px < 0 ? -1 : 1;
      const qx = ax - (hw - radius);
      const qy = ay - (hh - radius);
      const cx = Math.max(qx, 0);
      const cy = Math.max(qy, 0);
      const corner = Math.hypot(cx, cy);
      const distance = corner + Math.min(Math.max(qx, qy), 0) - radius;

      let nx: number;
      let ny: number;
      if (qx > 0 && qy > 0) {
        nx = cx / corner;
        ny = cy / corner;
      } else if (qx <= 0 && qy <= 0) {
        const wx = Math.exp(qx / soft);
        const wy = Math.exp(qy / soft);
        const length = Math.hypot(wx, wy);
        nx = wx / length;
        ny = wy / length;
      } else if (qx > qy) {
        nx = 1;
        ny = 0;
      } else {
        nx = 0;
        ny = 1;
      }

      const t = clamp(-distance / bezel, 0, 1);
      const rim = (1 - t) ** power;
      const i = iy * mw + ix;
      ox[i] = sx * nx * rim * refraction - px * pull;
      oy[i] = sy * ny * rim * refraction - py * pull;
      alpha[i] = clamp(0.5 - distance * density, 0, 1);
      if (alpha[i] > 0) max = Math.max(max, Math.abs(ox[i]), Math.abs(oy[i]));
    }
  }

  max = Math.max(max, 1e-3);
  const data = new Uint8ClampedArray(size * 4);
  for (let i = 0; i < size; i += 1) {
    const o = i * 4;
    data[o] = Math.round(128 + (127 * ox[i]) / max);
    data[o + 1] = Math.round(128 + (127 * oy[i]) / max);
    data[o + 2] = 128;
    data[o + 3] = Math.round(alpha[i] * 255);
  }

  // feDisplacementMap moves by scale × (C/255 − 0.5); C = 128 + 127·u, so u·max needs scale ≈ 2.008·max.
  return { width: mw, height: mh, data, scale: (max * 255) / 127 };
}

/**
 * The map of one expanding ripple: a ring that pushes content outward, then
 * pulls it back. `width` is the ring band as a fraction of the radius.
 */
export function rippleMap(size = 256, width = 0.34): LensMap {
  const n = Math.max(8, Math.round(size));
  const half = n / 2;
  const data = new Uint8ClampedArray(n * n * 4);
  for (let iy = 0; iy < n; iy += 1) {
    for (let ix = 0; ix < n; ix += 1) {
      const dx = (ix + 0.5 - half) / half;
      const dy = (iy + 0.5 - half) / half;
      const r = Math.hypot(dx, dy);
      const o = (iy * n + ix) * 4;
      const band = (r - (1 - width)) / width;
      // One full sine across the band: out, then in; zero at both edges.
      const wave = band > 0 && band < 1 ? Math.sin(band * Math.PI * 2) * Math.sin(band * Math.PI) : 0;
      const ux = r > 0 ? dx / r : 0;
      const uy = r > 0 ? dy / r : 0;
      data[o] = Math.round(128 - 127 * ux * wave);
      data[o + 1] = Math.round(128 - 127 * uy * wave);
      data[o + 2] = 128;
      data[o + 3] = Math.round(clamp((1 - r) * half, 0, 1) * 255);
    }
  }
  return { width: n, height: n, data, scale: 255 / 127 };
}

/* ------------------------------------------------------------------------- */
/* Map images (browser only)                                                 */
/* ------------------------------------------------------------------------- */

const imageCache = new Map<string, { url: string; scale: number }>();
const CACHE_LIMIT = 48;

function toImage(map: LensMap): string {
  const canvas = document.createElement('canvas');
  canvas.width = map.width;
  canvas.height = map.height;
  const context = canvas.getContext('2d');
  if (!context) return '';
  context.putImageData(new ImageData(new Uint8ClampedArray(map.data), map.width, map.height), 0, 0);
  return canvas.toDataURL('image/png');
}

/** A PNG data URL for the lens, cached by shape (maps are small but not free). */
export function lensImage(shape: LensShape): { url: string; scale: number } {
  const key = [shape.width, shape.height, shape.radius, shape.bezel, shape.curvature, shape.refraction, shape.zoom, shape.density]
    .map((value) => (value === undefined ? '' : Math.round(value * 100) / 100))
    .join('|');
  const hit = imageCache.get(key);
  if (hit) {
    imageCache.delete(key);
    imageCache.set(key, hit);
    return hit;
  }
  const map = lensMap(shape);
  const entry = { url: toImage(map), scale: map.scale };
  imageCache.set(key, entry);
  if (imageCache.size > CACHE_LIMIT) imageCache.delete(imageCache.keys().next().value as string);
  return entry;
}

let rippleImage: { url: string; scale: number } | null = null;

/* ------------------------------------------------------------------------- */
/* The filter                                                                */
/* ------------------------------------------------------------------------- */

let host: SVGSVGElement | null = null;
let filters = 0;

function defs(): SVGSVGElement {
  if (host?.isConnected) return host;
  host = document.createElementNS(SVG_NS, 'svg');
  host.setAttribute('aria-hidden', 'true');
  host.setAttribute('focusable', 'false');
  host.setAttribute('data-nx-glass-defs', '');
  host.style.cssText = 'position:absolute;inline-size:0;block-size:0;overflow:hidden;pointer-events:none';
  document.body.append(host);
  return host;
}

function node(name: string, attributes: Record<string, string | number>): SVGElement {
  const el = document.createElementNS(SVG_NS, name);
  for (const [key, value] of Object.entries(attributes)) el.setAttribute(key, String(value));
  return el;
}

export interface LensRect {
  x: number;
  y: number;
  width: number;
  height: number;
}

export interface LensFilter {
  id: string;
  /** `url(#id)`, for `filter` or `backdrop-filter`. */
  url: string;
  /** Size of the element the filter is applied to (its border box, CSS px). */
  region(width: number, height: number): void;
  /** Where the lens is, in the filtered element's border-box coordinates. */
  place(rect: LensRect): void;
  /** Swap the map image. */
  image(url: string): void;
  /** Displacement in px; 0 switches the refraction off. */
  strength(scale: number): void;
  destroy(): void;
}

export interface LensFilterOptions {
  /** Chromatic aberration, 0–1. Needs opaque content under the lens. */
  chroma?: number;
  /** Blur inside the lens, px. */
  frost?: number;
  /** Extra room around the element, so shadows are not clipped. */
  bleed?: number;
}

/**
 * Builds one lens filter in a shared hidden <svg>. Positions are CSS px from
 * the filtered element's border-box origin (userSpaceOnUse), which every
 * engine resolves the same way; bounding-box units scale the displacement
 * differently in each.
 */
export function createLensFilter({ chroma = 0, frost = 0, bleed = 32 }: LensFilterOptions = {}): LensFilter {
  filters += 1;
  const id = `nx-lens-${filters.toString(36)}`;
  const filter = node('filter', {
    id,
    filterUnits: 'userSpaceOnUse',
    primitiveUnits: 'userSpaceOnUse',
    'color-interpolation-filters': 'sRGB',
    x: -bleed,
    y: -bleed,
    width: 1,
    height: 1,
  });

  const lensParts: SVGElement[] = [];
  const add = (el: SVGElement, inLens = true) => {
    filter.append(el);
    if (inLens) lensParts.push(el);
    return el;
  };

  add(node('feFlood', { 'flood-color': 'rgb(128,128,128)', result: 'nx-neutral' }));
  const picture = add(node('feImage', { preserveAspectRatio: 'none', result: 'nx-shape' }));
  add(node('feComposite', { in: 'nx-shape', in2: 'nx-neutral', operator: 'over', result: 'nx-map' }));

  // Frost blurs only what the lens will sample (its subregion follows the lens, see setScale).
  const blur = frost > 0 ? add(node('feGaussianBlur', { in: 'SourceGraphic', stdDeviation: frost, result: 'nx-soft' }), false) : null;
  const source = blur ? 'nx-soft' : 'SourceGraphic';

  const displacements: Array<{ el: SVGElement; factor: number }> = [];
  if (chroma > 0) {
    const spread = 0.12 * clamp(chroma, 0, 1);
    const channels: Array<[string, number, string]> = [
      ['1 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 1 0', 1 + spread, 'nx-r'],
      ['0 0 0 0 0  0 1 0 0 0  0 0 0 0 0  0 0 0 1 0', 1, 'nx-g'],
      ['0 0 0 0 0  0 0 0 0 0  0 0 1 0 0  0 0 0 1 0', 1 - spread, 'nx-b'],
    ];
    for (const [values, factor, result] of channels) {
      const el = add(node('feDisplacementMap', { in: source, in2: 'nx-map', xChannelSelector: 'R', yChannelSelector: 'G', result: `${result}-d` }));
      displacements.push({ el, factor });
      add(node('feColorMatrix', { in: `${result}-d`, type: 'matrix', values, result }));
    }
    add(node('feComposite', { in: 'nx-r', in2: 'nx-g', operator: 'arithmetic', k1: 0, k2: 1, k3: 1, k4: 0, result: 'nx-rg' }));
    add(node('feComposite', { in: 'nx-rg', in2: 'nx-b', operator: 'arithmetic', k1: 0, k2: 1, k3: 1, k4: 0, result: 'nx-bent' }));
  } else {
    const el = add(node('feDisplacementMap', { in: source, in2: 'nx-map', xChannelSelector: 'R', yChannelSelector: 'G', result: 'nx-bent' }));
    displacements.push({ el, factor: 1 });
  }

  add(node('feComposite', { in: 'nx-bent', in2: 'nx-shape', operator: 'in', result: 'nx-lens' }), false);
  add(node('feComposite', { in: 'SourceGraphic', in2: 'nx-shape', operator: 'out', result: 'nx-rest' }), false);
  add(node('feComposite', { in: 'nx-lens', in2: 'nx-rest', operator: 'over' }), false);

  defs().append(filter);

  let scale = 0;
  let rect: LensRect = { x: 0, y: 0, width: 0, height: 0 };
  const setScale = () => {
    for (const { el, factor } of displacements) el.setAttribute('scale', (scale * factor).toFixed(2));
    if (blur) {
      // The lens samples up to `scale` px beyond its edge; blur exactly that area.
      const pad = scale + frost * 3;
      blur.setAttribute('x', String(rect.x - pad));
      blur.setAttribute('y', String(rect.y - pad));
      blur.setAttribute('width', String(rect.width + pad * 2));
      blur.setAttribute('height', String(rect.height + pad * 2));
    }
  };

  return {
    id,
    url: `url(#${id})`,
    region(width, height) {
      filter.setAttribute('width', String(Math.ceil(width + bleed * 2)));
      filter.setAttribute('height', String(Math.ceil(height + bleed * 2)));
    },
    place(next) {
      rect = next;
      const x = next.x.toFixed(2);
      const y = next.y.toFixed(2);
      const width = Math.max(0, next.width).toFixed(2);
      const height = Math.max(0, next.height).toFixed(2);
      for (const el of lensParts) {
        el.setAttribute('x', x);
        el.setAttribute('y', y);
        el.setAttribute('width', width);
        el.setAttribute('height', height);
      }
      if (blur) setScale();
    },
    image(url) {
      picture.setAttribute('href', url);
    },
    strength(next) {
      if (next === scale) return;
      scale = next;
      setScale();
    },
    destroy() {
      filter.remove();
    },
  };
}

/* ------------------------------------------------------------------------- */
/* Lens mode                                                                 */
/* ------------------------------------------------------------------------- */

export interface LiquidLensOptions extends Omit<LensShape, 'width' | 'height' | 'radius'>, Omit<LensFilterOptions, 'bleed'> {
  /** The element whose box is the lens. It sits above `target`, not inside it. */
  lens: HTMLElement;
}

export interface LiquidLensController {
  /** Measure the lens again now. */
  sync(): void;
  /** Measure every frame for `ms` (or until the next `sync`-only period), e.g. while dragging. */
  follow(ms?: number): void;
  destroy: Cleanup;
}

function readRadius(el: HTMLElement, width: number, height: number): number {
  const raw = getComputedStyle(el).borderTopLeftRadius;
  const value = parseFloat(raw);
  if (!Number.isFinite(value)) return 0;
  return raw.endsWith('%') ? (value / 100) * Math.min(width, height) : value;
}

/** `--nx-lens-power` (0–1) on the lens scales the refraction; CSS can transition it. */
function readPower(el: HTMLElement): number {
  const raw = getComputedStyle(el).getPropertyValue('--nx-lens-power').trim();
  if (!raw) return 1;
  const value = parseFloat(raw);
  return Number.isFinite(value) ? clamp(value, 0, 4) : 1;
}

/**
 * Bends `target`'s content wherever `options.lens` sits over it. The lens can
 * move by CSS transitions, animations or script: transitions and animations
 * are followed automatically, and callers moving it by hand call `follow()`.
 */
export function liquidLens(target: HTMLElement, options: LiquidLensOptions): LiquidLensController {
  const noop: LiquidLensController = { sync() {}, follow() {}, destroy() {} };
  if (!isBrowser) return noop;

  const { lens, chroma, frost, ...shape } = options;
  const filter = createLensFilter({ chroma, frost });
  let mapSize = '';
  let mapScale = 0;
  let frame = 0;
  let until = 0;
  let running = 0;
  let pendingMap = 0;
  let applied = false;
  // Late callbacks (fonts.ready, a queued frame) must not touch the element after destroy.
  let destroyed = false;

  const apply = (on: boolean) => {
    if (on === applied) return;
    applied = on;
    target.style.filter = on ? filter.url : '';
  };

  const makeMap = (width: number, height: number) => {
    const radius = readRadius(lens, width, height);
    const key = `${width}x${height}x${radius}`;
    if (key === mapSize) return;
    mapSize = key;
    const image = lensImage({ ...shape, width, height, radius });
    filter.image(image.url);
    mapScale = image.scale;
  };

  const sync = () => {
    if (destroyed || !target.isConnected || !lens.isConnected) return;
    const width = lens.offsetWidth;
    const height = lens.offsetHeight;
    const power = readPower(lens);
    if (!width || !height || power <= 0.001) {
      apply(false);
      return;
    }

    // The layout size shapes the map; transforms (a pressed lens growing) only
    // stretch it. While the lens is resizing, stretch the last map and draw a
    // new one once it settles.
    if (!mapSize) makeMap(width, height);
    else if (!mapSize.startsWith(`${width}x${height}x`)) {
      const moving = running > 0 || performance.now() < until;
      clearTimeout(pendingMap);
      pendingMap = window.setTimeout(() => {
        makeMap(lens.offsetWidth, lens.offsetHeight);
        sync();
      }, moving ? 90 : 0);
    }

    const t = target.getBoundingClientRect();
    const l = lens.getBoundingClientRect();
    const grow = l.width / width;
    filter.region(target.offsetWidth, target.offsetHeight);
    filter.place({ x: l.left - t.left, y: l.top - t.top, width: l.width, height: l.height });
    filter.strength(mapScale * power * grow);
    apply(true);
  };

  const tick = () => {
    frame = 0;
    sync();
    if (running > 0 || performance.now() < until) frame = requestAnimationFrame(tick);
  };

  const follow = (ms = 400) => {
    if (destroyed) return;
    until = Math.max(until, performance.now() + ms);
    if (!frame) frame = requestAnimationFrame(tick);
  };

  const onStart = (event: Event) => {
    if (event.target !== lens) return;
    running += 1;
    follow(0);
  };
  const onEnd = (event: Event) => {
    if (event.target !== lens) return;
    running = Math.max(0, running - 1);
    follow(50);
  };

  lens.addEventListener('transitionrun', onStart);
  lens.addEventListener('transitionend', onEnd);
  lens.addEventListener('transitioncancel', onEnd);
  lens.addEventListener('animationstart', onStart);
  lens.addEventListener('animationend', onEnd);
  lens.addEventListener('animationcancel', onEnd);

  const resize = 'ResizeObserver' in window ? new ResizeObserver(() => follow(60)) : null;
  resize?.observe(target);
  resize?.observe(lens);
  const onResize = () => follow(60);
  window.addEventListener('resize', onResize);
  document.fonts?.ready.then(() => follow(60)).catch(() => {});

  sync();

  return {
    sync,
    follow,
    destroy() {
      destroyed = true;
      cancelAnimationFrame(frame);
      clearTimeout(pendingMap);
      resize?.disconnect();
      window.removeEventListener('resize', onResize);
      lens.removeEventListener('transitionrun', onStart);
      lens.removeEventListener('transitionend', onEnd);
      lens.removeEventListener('transitioncancel', onEnd);
      lens.removeEventListener('animationstart', onStart);
      lens.removeEventListener('animationend', onEnd);
      lens.removeEventListener('animationcancel', onEnd);
      apply(false);
      filter.destroy();
    },
  };
}

/* ------------------------------------------------------------------------- */
/* Pane mode                                                                 */
/* ------------------------------------------------------------------------- */

/** Chromium is the engine that bends a backdrop with an SVG filter. */
export function supportsBackdropRefraction(): boolean {
  if (!isBrowser) return false;
  const data = (navigator as Navigator & { userAgentData?: { brands?: Array<{ brand: string }> } }).userAgentData;
  return !!data?.brands?.some((entry) => entry.brand === 'Chromium');
}

export type GlassPaneOptions = Omit<LensShape, 'width' | 'height' | 'radius'> & Pick<LensFilterOptions, 'chroma'>;

/**
 * Bends the backdrop behind `el` at its rim (Chromium). Writes `--nx-refract`
 * on the element; the `.nx-glass` material puts it in front of its blur.
 * Elsewhere this does nothing and the material stays frosted.
 */
export function glassPane(el: HTMLElement, options: GlassPaneOptions = {}): Cleanup {
  if (!supportsBackdropRefraction()) return () => {};
  const { chroma, ...shape } = options;
  const filter = createLensFilter({ chroma, bleed: 0 });
  let key = '';
  let timer = 0;

  const build = () => {
    const width = el.offsetWidth;
    const height = el.offsetHeight;
    if (!width || !height) return;
    const radius = readRadius(el, width, height);
    const next = `${width}x${height}x${radius}`;
    if (next === key) return;
    key = next;
    const short = Math.min(width, height) / 2;
    const image = lensImage({
      bezel: Math.min(24, short * 0.6),
      refraction: -Math.min(16, short * 0.3),
      curvature: 0.7,
      ...shape,
      width,
      height,
      radius,
      density: 1,
    });
    filter.region(width, height);
    filter.place({ x: 0, y: 0, width, height });
    filter.image(image.url);
    filter.strength(image.scale);
    el.style.setProperty('--nx-refract', filter.url);
  };

  const resize = 'ResizeObserver' in window
    ? new ResizeObserver(() => {
        clearTimeout(timer);
        timer = window.setTimeout(build, key ? 120 : 0);
      })
    : null;
  resize?.observe(el);
  build();

  return () => {
    clearTimeout(timer);
    resize?.disconnect();
    el.style.removeProperty('--nx-refract');
    filter.destroy();
  };
}

/* ------------------------------------------------------------------------- */
/* Ripples                                                                   */
/* ------------------------------------------------------------------------- */

export interface LiquidRippleOptions {
  /** Peak displacement in px. */
  strength?: number;
  /** Largest ring radius in px (default: reaches the far corner). */
  reach?: number;
  /** ms from touch to calm water. */
  duration?: number;
}

/**
 * Clicks send a ripple through `el`'s live content. Input still reaches the
 * content underneath; the ripple is only pixels.
 */
export function liquidRipple(el: HTMLElement, { strength = 18, reach, duration = 1100 }: LiquidRippleOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  rippleImage ??= (() => {
    const map = rippleMap();
    return { url: toImage(map), scale: map.scale };
  })();
  const filter = createLensFilter();
  filter.image(rippleImage.url);
  let frame = 0;

  const stop = () => {
    cancelAnimationFrame(frame);
    frame = 0;
    el.style.filter = '';
    el.removeAttribute('data-nx-rippling');
  };

  const start = (event: PointerEvent) => {
    if (prefersReducedMotion() || event.button > 0) return;
    const box = el.getBoundingClientRect();
    const x = event.clientX - box.left;
    const y = event.clientY - box.top;
    const far = reach ?? Math.hypot(Math.max(x, box.width - x), Math.max(y, box.height - y));
    const begin = performance.now();
    filter.region(el.offsetWidth, el.offsetHeight);
    el.style.filter = filter.url;
    el.setAttribute('data-nx-rippling', '');
    cancelAnimationFrame(frame);

    const step = (now: number) => {
      const t = Math.min(1, (now - begin) / duration);
      const eased = 1 - (1 - t) ** 3;
      const r = 12 + far * eased;
      filter.place({ x: x - r, y: y - r, width: r * 2, height: r * 2 });
      // The ring flattens as it spreads.
      filter.strength(rippleImage!.scale * strength * (1 - t) ** 1.6);
      if (t < 1) frame = requestAnimationFrame(step);
      else stop();
    };
    frame = requestAnimationFrame(step);
  };

  el.addEventListener('pointerdown', start);
  return () => {
    el.removeEventListener('pointerdown', start);
    stop();
    filter.destroy();
  };
}

/* ------------------------------------------------------------------------- */
/* Light                                                                     */
/* ------------------------------------------------------------------------- */

let sweeping: Animation | null = null;

/**
 * Sends the global specular light once around every glass rim on the page
 * (--nx-light on the root). Rims read the angle, so they all catch it at once.
 */
export function sweepLight({ duration = 1100 }: { duration?: number } = {}): void {
  if (!isBrowser || prefersReducedMotion()) return;
  const root = document.documentElement;
  const start = parseFloat(getComputedStyle(root).getPropertyValue('--nx-light')) || 315;
  sweeping?.cancel();
  try {
    sweeping = root.animate([{ '--nx-light': `${start}deg` }, { '--nx-light': `${start + 360}deg` }] as Keyframe[], {
      duration,
      easing: 'cubic-bezier(0.65, 0, 0.35, 1)',
    });
    sweeping.onfinish = () => {
      sweeping = null;
    };
  } catch {
    sweeping = null;
  }
}

/**
 * Turns the light toward the pointer while it is over `el` (mouse and pen
 * only), so the rims glint where you point. Leaving eases back to the page light.
 */
export function followLight(el: HTMLElement): Cleanup {
  if (!isBrowser || prefersReducedMotion()) return () => {};
  let angle = Number.NaN;
  const move = (event: PointerEvent) => {
    if (event.pointerType === 'touch') return;
    const box = el.getBoundingClientRect();
    const next = (Math.atan2(event.clientX - (box.left + box.width / 2), -(event.clientY - (box.top + box.height / 2))) * 180) / Math.PI;
    // Unwrap so a transition never spins the long way round.
    if (Number.isNaN(angle)) angle = next;
    else angle += ((((next - angle) % 360) + 540) % 360) - 180;
    el.style.setProperty('--nx-light', `${angle.toFixed(1)}deg`);
  };
  const leave = () => {
    angle = Number.NaN;
    el.style.removeProperty('--nx-light');
  };
  el.addEventListener('pointermove', move);
  el.addEventListener('pointerleave', leave);
  return () => {
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerleave', leave);
    leave();
  };
}
