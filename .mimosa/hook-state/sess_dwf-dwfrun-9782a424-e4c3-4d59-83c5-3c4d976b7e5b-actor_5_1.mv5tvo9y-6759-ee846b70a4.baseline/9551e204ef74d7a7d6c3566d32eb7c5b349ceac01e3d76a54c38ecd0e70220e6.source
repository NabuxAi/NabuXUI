/**
 * Framework-agnostic behaviours for the micro-interaction blocks.
 *
 *   confetti       a particle cannon on a plain 2D canvas: every piece carries
 *                  its own velocity, gravity, exponential air drag, spin and
 *                  life, and the palette is read from the theme's chart tokens
 *                  at fire time, so both themes throw their own party. The
 *                  loop parks off screen, stays silent under reduced motion,
 *                  and the controller dies with the element.
 *   rippleSurface  a click wave born at the exact pointer coordinates — or the
 *                  surface centre for Enter/Space — and sized to sweep the
 *                  whole surface before fading. The ink is currentColor, so
 *                  every surface ripples in its own colour.
 *   dragSpring     one semi-implicit Euler spring drives the whole drag: while
 *                  held it chases the pointer with stiff constants, and on
 *                  release it either flies into the drop target or wobbles
 *                  home with an underdamped settle. Pointer capture keeps
 *                  touch dragging alive, reduced motion teleports, and the
 *                  loop parks the moment it leaves the viewport.
 *
 * The look lives in css/blocks/micro-fx.css; these measure, integrate and
 * paint, and clean up every listener, frame and observer behind them.
 */
import { type Cleanup, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';

/* ---- Confetti cannon ------------------------------------------------------------------ */

export interface ConfettiBurst {
  /** Origin inside the host: 0–1 from the inline-start edge and from the top (default 0.5 / 0.9). */
  x?: number;
  y?: number;
  /** How many pieces this burst throws (defaults to the cannon's `count`). */
  count?: number;
  /** Colours for this burst; defaults to the theme palette read at fire time. */
  colors?: string[];
  /** Launch speed range in px/s (default 320–840). */
  speed?: [number, number];
}

export interface ConfettiOptions {
  /** Pieces per burst (default 120). */
  count?: number;
  /** Downward acceleration in px/s² (default 1500). */
  gravity?: number;
  /** Palette override; the theme's seven chart tokens when omitted. */
  colors?: string[];
}

export interface ConfettiController {
  /** Throw a burst. A no-op under reduced motion — the state change is the cue. */
  fire: (burst?: ConfettiBurst) => void;
  /** Remove the canvas and stop every loop and observer. */
  destroy: Cleanup;
}

interface Piece {
  x: number;
  y: number;
  vx: number;
  vy: number;
  r: number;
  rot: number;
  vr: number;
  color: string;
  life: number;
  age: number;
  shape: number;
}

/** The theme's party colours: the seven chart tokens, then accent, then text. */
function readPalette(el: HTMLElement): string[] {
  const style = getComputedStyle(el);
  const colors: string[] = [];
  for (let i = 1; i <= 7; i++) {
    const value = style.getPropertyValue(`--nx-chart-${i}`).trim();
    if (value) colors.push(value);
  }
  const accent = style.getPropertyValue('--nx-accent').trim();
  if (accent) colors.push(accent);
  if (colors.length === 0 && style.color) colors.push(style.color);
  return colors.length > 0 ? colors : ['#888888'];
}

/**
 * A confetti cannon mounted on `el`. The host should be positioned and clip its
 * overflow (the block CSS does both); the canvas is created and owned here.
 * `fire()` throws a fan of pieces upward from anywhere in the host — in host
 * fractions, so buttons and cards can aim from their own geometry. The loop
 * runs only while pieces are alive, the host is on screen and motion is
 * allowed; `destroy()` removes the canvas itself.
 */
export function confetti(el: HTMLElement, { count = 120, gravity = 1500, colors }: ConfettiOptions = {}): ConfettiController {
  const idle: ConfettiController = { fire: () => undefined, destroy: () => undefined };
  if (!isBrowser) return idle;

  const canvas = document.createElement('canvas');
  canvas.className = 'nx-confetti-canvas';
  canvas.setAttribute('aria-hidden', 'true');
  const positioned = getComputedStyle(el).position === 'static';
  if (positioned) el.style.position = 'relative';
  el.appendChild(canvas);
  const ctx = canvas.getContext('2d') ?? null;

  let pieces: Piece[] = [];
  let frame = 0;
  let last = 0;
  let visible = true;
  let reduced = prefersReducedMotion();

  const size = () => {
    const dpr = Math.min(2, window.devicePixelRatio || 1);
    canvas.width = Math.max(2, Math.round(canvas.clientWidth * dpr));
    canvas.height = Math.max(2, Math.round(canvas.clientHeight * dpr));
    ctx?.setTransform(dpr, 0, 0, dpr, 0, 0);
    paint();
  };

  const paint = () => {
    if (!ctx) return;
    const w = canvas.clientWidth;
    const h = canvas.clientHeight;
    ctx.clearRect(0, 0, w, h);
    for (const p of pieces) {
      ctx.save();
      // Pieces melt out on a curve rather than blinking off.
      ctx.globalAlpha = Math.max(0, 1 - (p.age / p.life) ** 2);
      ctx.translate(p.x, p.y);
      ctx.rotate(p.rot);
      ctx.fillStyle = p.color;
      const r = p.r;
      if (p.shape === 0) {
        ctx.beginPath();
        ctx.arc(0, 0, r * 0.7, 0, Math.PI * 2);
        ctx.fill();
      } else if (p.shape === 1) {
        ctx.fillRect(-r, -r * 0.6, r * 2, r * 1.2);
      } else if (p.shape === 2) {
        ctx.beginPath();
        ctx.moveTo(-r, 0);
        ctx.lineTo(0, -r * 1.3);
        ctx.lineTo(r, 0);
        ctx.lineTo(0, r * 1.3);
        ctx.closePath();
        ctx.fill();
      } else if (p.shape === 3) {
        ctx.beginPath();
        ctx.ellipse(0, 0, r * 1.4, r * 0.55, 0, 0, Math.PI * 2);
        ctx.fill();
      } else {
        ctx.fillRect(-r * 0.5, -r * 1.1, r, r * 2.2);
      }
      ctx.restore();
    }
  };

  const step = (now: number) => {
    const dt = Math.min(0.032, (now - last) / 1000) || 0.016;
    last = now;
    const h = canvas.clientHeight;
    pieces = pieces.filter((p) => {
      p.age += dt;
      p.vy += gravity * dt;
      // Exponential air drag: light pieces decelerate the whole way.
      p.vx *= Math.exp(-dt * 1.1);
      p.x += p.vx * dt;
      p.y += p.vy * dt;
      p.rot += p.vr * dt;
      return p.age <= p.life && p.y <= h + 40;
    });
    paint();
    if (pieces.length > 0 && visible && !reduced) frame = requestAnimationFrame(step);
    else frame = 0;
  };

  const run = () => {
    if (frame || reduced || !visible) return;
    last = 0;
    frame = requestAnimationFrame(step);
  };

  const fire = ({ x = 0.5, y = 0.5, count: burstCount, colors: burstColors, speed = [320, 840] }: ConfettiBurst = {}) => {
    if (reduced) return;
    // Origins are fractions of the host; the canvas may be larger than its host
    // (the button cannon bleeds outward), so place them in canvas space.
    const host = el.getBoundingClientRect();
    const view = canvas.getBoundingClientRect();
    const originX = Math.max(0, Math.min(1, x)) * host.width + (host.left - view.left);
    const originY = Math.max(0, Math.min(1, y)) * host.height + (host.top - view.top);
    const palette = burstColors?.length ? burstColors : (colors ?? readPalette(el));
    const total = burstCount ?? count;
    for (let i = 0; i < total; i++) {
      // A fan around straight-up, like a cannon and not a ring.
      const angle = -Math.PI / 2 + (Math.random() - 0.5) * Math.PI * 0.9;
      const velocity = speed[0] + Math.random() * (speed[1] - speed[0]);
      pieces.push({
        x: originX,
        y: originY,
        vx: Math.cos(angle) * velocity,
        vy: Math.sin(angle) * velocity,
        r: 3 + Math.random() * 4,
        rot: Math.random() * Math.PI * 2,
        vr: (Math.random() - 0.5) * 14,
        color: palette[i % palette.length]!,
        life: 1.6 + Math.random() * 0.9,
        age: 0,
        shape: i % 5,
      });
    }
    run();
  };

  const resize = new ResizeObserver(size);
  resize.observe(canvas);
  const watch = new IntersectionObserver((entries) => {
    visible = entries[0]?.isIntersecting ?? true;
    if (visible) run();
  });
  watch.observe(canvas);
  const stopMotion = onReducedMotionChange((value) => {
    reduced = value;
    if (reduced && frame) {
      cancelAnimationFrame(frame);
      frame = 0;
      pieces = [];
      paint();
    }
  });
  size();

  return {
    fire,
    destroy: () => {
      if (frame) cancelAnimationFrame(frame);
      frame = 0;
      pieces = [];
      resize.disconnect();
      watch.disconnect();
      stopMotion();
      canvas.remove();
      if (positioned) el.style.removeProperty('position');
    },
  };
}

/* ---- Ripple surface -------------------------------------------------------------------- */

export interface RippleSurfaceOptions {
  /** What counts as a surface inside `el` (default '[data-nx-ripple]'); `el` itself counts when it matches. */
  selector?: string;
}

/** One wave per press, even when nested surfaces each run the behaviour. */
const rippleHandled = new WeakSet<Event>();

/**
 * Click waves from the exact point of the press. The behaviour listens on `el`
 * and finds the surface under the event, so one call dresses a card and every
 * button and tab inside it — mark each surface with `data-nx-ripple`. Pointer
 * presses spawn at the pointer; Enter and Space drop the wave from the centre,
 * so keyboard activation ripples too. The ink is sized to twice the farthest
 * corner, so it always sweeps the whole surface, and is currentColor — the
 * stylesheet colours it per surface. Under reduced motion nothing spawns: the
 * state the surface represents is the cue.
 */
export function rippleSurface(el: HTMLElement, { selector = '[data-nx-ripple]' }: RippleSurfaceOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  const inks: HTMLElement[] = [];

  const spawn = (surface: HTMLElement, clientX: number, clientY: number) => {
    if (prefersReducedMotion()) return;
    const rect = surface.getBoundingClientRect();
    // Pointer positions are the one place physical left/top is correct.
    const x = clientX - rect.left;
    const y = clientY - rect.top;
    const far = Math.max(
      Math.hypot(x, y),
      Math.hypot(rect.width - x, y),
      Math.hypot(x, rect.height - y),
      Math.hypot(rect.width - x, rect.height - y),
    );
    const size = Math.ceil(far * 2 + 8);
    const ink = document.createElement('span');
    ink.className = 'nx-ripple-ink';
    ink.setAttribute('aria-hidden', 'true');
    ink.style.width = `${size}px`;
    ink.style.height = `${size}px`;
    ink.style.left = `${x}px`;
    ink.style.top = `${y}px`;
    ink.addEventListener('animationend', () => {
      ink.remove();
      const at = inks.indexOf(ink);
      if (at !== -1) inks.splice(at, 1);
    }, { once: true });
    surface.appendChild(ink);
    inks.push(ink);
  };

  const surfaceFor = (event: Event): HTMLElement | null => {
    const hit = event.target instanceof Element ? event.target.closest<HTMLElement>(selector) : null;
    // A surface above `el` (an outer wrapper) is not ours to fill.
    return hit && el.contains(hit) ? hit : null;
  };

  const onDown = (event: PointerEvent) => {
    if (event.button !== 0 || rippleHandled.has(event)) return;
    const surface = surfaceFor(event);
    if (!surface) return;
    rippleHandled.add(event);
    spawn(surface, event.clientX, event.clientY);
  };

  const onKey = (event: KeyboardEvent) => {
    if ((event.key !== 'Enter' && event.key !== ' ') || rippleHandled.has(event)) return;
    const surface = surfaceFor(event);
    if (!surface) return;
    rippleHandled.add(event);
    const rect = surface.getBoundingClientRect();
    spawn(surface, rect.left + rect.width / 2, rect.top + rect.height / 2);
  };

  el.addEventListener('pointerdown', onDown);
  el.addEventListener('keydown', onKey);
  return () => {
    el.removeEventListener('pointerdown', onDown);
    el.removeEventListener('keydown', onKey);
    for (const ink of inks) ink.remove();
    inks.length = 0;
  };
}

/* ---- Drag spring ----------------------------------------------------------------------- */

export interface DragSpringOptions {
  /** The drop target, read live so layout changes stay honest. */
  target?: () => HTMLElement | null;
  /** How close the centres must be for the target to grip, px (default 72). */
  radius?: number;
  /** The drag began. */
  onGrab?: () => void;
  /** Released inside the radius (or assigned by keyboard): it will settle onto the target. */
  onDrop?: () => void;
  /** Released outside (or released by keyboard / reset): it springs home. */
  onCancel?: () => void;
}

export interface DragSpringController {
  /** Fly onto the target — the keyboard path to the same drop. */
  assign: () => void;
  /** Spring back home — the keyboard path to the same cancel. */
  release: () => void;
  destroy: Cleanup;
}

/** Where the spring wants to be: the target's centre, or home (0, 0). */
function springTarget(el: HTMLElement, target: HTMLElement | null): { x: number; y: number } {
  if (!target) return { x: 0, y: 0 };
  const r = el.getBoundingClientRect();
  const s = target.getBoundingClientRect();
  return {
    x: s.left + s.width / 2 - (r.left + r.width / 2),
    y: s.top + s.height / 2 - (r.top + r.height / 2),
  };
}

/**
 * A draggable card on one semi-implicit Euler spring. While held, the spring
 * is stiff (k 600, c 40), so the card trails the pointer like a sticky bead;
 * on release it switches to an underdamped settle (k 170, c 12, ζ≈0.46 — two
 * or three visible overshoots) toward the drop target when the centres are
 * within `radius`, and home otherwise. The card carries `[data-dragging]` and
 * `[data-locked]`; the target carries `[data-near]`, `[data-held]` and, once
 * the spring has settled on it, `[data-settled]`. Enter and Space toggle the
 * assignment with no pointer at all, pointer capture keeps touch dragging
 * alive, reduced motion teleports, and the loop parks off screen.
 */
export function dragSpring(el: HTMLElement, { target, radius = 72, onGrab, onDrop, onCancel }: DragSpringOptions = {}): DragSpringController {
  const idle: DragSpringController = { assign: () => undefined, release: () => undefined, destroy: () => undefined };
  if (!isBrowser) return idle;

  let x = 0;
  let y = 0;
  let vx = 0;
  let vy = 0;
  let tx = 0;
  let ty = 0;
  let held = false;
  let settled = false;
  let dragging = false;
  let near = false;
  let visible = true;
  let frame = 0;
  let last = 0;
  let startX = 0;
  let startY = 0;
  let pointerId = -1;

  const reduced = () => prefersReducedMotion();
  const targetEl = () => target?.() ?? null;

  const paint = () => {
    el.style.translate = `${x.toFixed(2)}px ${y.toFixed(2)}px`;
  };

  const trackNear = () => {
    const slot = targetEl();
    if (!slot) {
      near = false;
      return;
    }
    const r = el.getBoundingClientRect();
    const s = slot.getBoundingClientRect();
    near = Math.hypot(r.left + r.width / 2 - (s.left + s.width / 2), r.top + r.height / 2 - (s.top + s.height / 2)) < radius;
    slot.toggleAttribute('data-near', near && !held);
  };

  const setHeld = (next: boolean) => {
    if (held === next) return;
    held = next;
    settled = false;
    el.toggleAttribute('data-locked', held);
    targetEl()?.toggleAttribute('data-held', held);
    if (held) onDrop?.();
    else {
      onCancel?.();
      targetEl()?.removeAttribute('data-near');
    }
  };

  /** Snap to wherever the spring wants to be — parks, resizes, reduced motion. */
  const land = () => {
    const goal = held ? springTarget(el, targetEl()) : { x: 0, y: 0 };
    x = tx = goal.x;
    y = ty = goal.y;
    vx = vy = 0;
    paint();
    if (held && !settled) confirm();
  };

  const confirm = () => {
    settled = true;
    targetEl()?.setAttribute('data-settled', '');
  };

  const run = () => {
    if (frame || reduced() || !visible) return;
    last = 0;
    frame = requestAnimationFrame(step);
  };

  const step = (now: number) => {
    const dt = Math.min(0.032, (now - last) / 1000) || 0.016;
    last = now;
    const k = dragging ? 600 : 170;
    const c = dragging ? 40 : 12;
    vx += (-k * (x - tx) - c * vx) * dt;
    vy += (-k * (y - ty) - c * vy) * dt;
    x += vx * dt;
    y += vy * dt;
    paint();
    trackNear();
    const rest = Math.hypot(x - tx, y - ty) < 0.6;
    const slow = Math.hypot(vx, vy) < 8;
    if (!dragging && rest && slow) {
      x = tx;
      y = ty;
      vx = vy = 0;
      paint();
      frame = 0;
      if (held && !settled) confirm();
      return;
    }
    frame = requestAnimationFrame(step);
  };

  const grab = (event: PointerEvent) => {
    if (held || (event.pointerType === 'mouse' && event.button !== 0)) return;
    dragging = true;
    settled = false;
    startX = event.clientX;
    startY = event.clientY;
    pointerId = event.pointerId;
    try {
      el.setPointerCapture(pointerId);
    } catch {
      /* capture is best-effort */
    }
    el.setAttribute('data-dragging', '');
    el.focus({ preventScroll: true });
    onGrab?.();
    run();
  };

  const drag = (event: PointerEvent) => {
    if (!dragging || event.pointerId !== pointerId) return;
    tx = event.clientX - startX;
    ty = event.clientY - startY;
    if (reduced()) {
      x = tx;
      y = ty;
      vx = vy = 0;
      paint();
    }
    trackNear();
  };

  const drop = (event: PointerEvent) => {
    if (!dragging || event.pointerId !== pointerId) return;
    dragging = false;
    try {
      el.releasePointerCapture(pointerId);
    } catch {
      /* already gone */
    }
    el.removeAttribute('data-dragging');
    // The drop decision: near wins, otherwise home.
    trackNear();
    setHeld(near);
    if (held) {
      const goal = springTarget(el, targetEl());
      tx = goal.x;
      ty = goal.y;
    } else {
      tx = 0;
      ty = 0;
    }
    if (reduced()) {
      land();
      return;
    }
    run();
  };

  const onKey = (event: KeyboardEvent) => {
    if (event.key !== 'Enter' && event.key !== ' ') return;
    event.preventDefault();
    if (dragging) return;
    setHeld(!held);
    if (held) {
      const goal = springTarget(el, targetEl());
      tx = goal.x;
      ty = goal.y;
    } else {
      tx = 0;
      ty = 0;
    }
    if (reduced()) {
      land();
      return;
    }
    run();
  };

  el.addEventListener('pointerdown', grab);
  el.addEventListener('pointermove', drag);
  el.addEventListener('pointerup', drop);
  el.addEventListener('pointercancel', drop);
  el.addEventListener('keydown', onKey);

  const watch = new IntersectionObserver((entries) => {
    visible = entries[0]?.isIntersecting ?? true;
    if (!visible && !dragging) {
      if (frame) cancelAnimationFrame(frame);
      frame = 0;
      land();
    } else if (visible) {
      run();
    }
  });
  watch.observe(el);
  const resize = new ResizeObserver(() => {
    // The slot moved under a settled card: recall without a wobble.
    if (!dragging && !frame) land();
  });
  resize.observe(el);

  return {
    assign: () => {
      if (dragging) return;
      setHeld(true);
      const goal = springTarget(el, targetEl());
      tx = goal.x;
      ty = goal.y;
      if (reduced()) {
        land();
        return;
      }
      run();
    },
    release: () => {
      if (dragging) return;
      setHeld(false);
      tx = 0;
      ty = 0;
      if (reduced()) {
        land();
        return;
      }
      run();
    },
    destroy: () => {
      if (frame) cancelAnimationFrame(frame);
      frame = 0;
      watch.disconnect();
      resize.disconnect();
      el.removeEventListener('pointerdown', grab);
      el.removeEventListener('pointermove', drag);
      el.removeEventListener('pointerup', drop);
      el.removeEventListener('pointercancel', drop);
      el.removeEventListener('keydown', onKey);
      el.removeAttribute('data-dragging');
      el.removeAttribute('data-locked');
      el.style.removeProperty('translate');
      const slot = targetEl();
      if (slot) {
        slot.removeAttribute('data-near');
        slot.removeAttribute('data-held');
        slot.removeAttribute('data-settled');
      }
    },
  };
}
