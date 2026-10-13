/**
 * Framework-agnostic behaviours for the hover-effect blocks.
 *
 *   imageTrail     a chain of cards chases the pointer across a stage: each
 *                  card lerps toward its predecessor — never the pointer — so
 *                  the chain fans open while the hand moves, and once the hand
 *                  rests the cards fade one after another on a stair
 *   glareCard      a glare halo that rotates toward the pointer (atan2 of the
 *                  angle around the centre) while the scene, the body and the
 *                  actions each ride the parallax at their own distance
 *   magneticField  every item inside the field is drawn toward the pointer
 *                  with distance falloff while it is inside the radius, and a
 *                  stiffness/damping spring carries it home with a little
 *                  overshoot when the hand leaves
 *
 * As everywhere in NabuXUI the motion itself lives in css/blocks/hover-fx.css;
 * these measure, write custom properties and attributes, park their loops off
 * screen, and clean up after themselves.
 */
import { type Cleanup, direction, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';

const clamp = (value: number, min: number, max: number) => Math.min(max, Math.max(min, value));
const park = (observer: IntersectionObserver | null) => observer?.disconnect();

/* ---- Image trail ---------------------------------------------------------------------- */

export interface ImageTrailOptions {
  /** Selector of the chain cards, in chain order (default '.nx-image-trail-card'). */
  cardSelector?: string;
  /** Elements the chain gathers around when they take keyboard focus (default '[data-trail-pin]'). */
  pinSelector?: string;
  /** How long the hand may rest before the chain fades out, ms (default 140). */
  idle?: number;
}

/**
 * The chain of `.nx-image-trail-card` elements inside a stage. The pointer
 * drags the chain: card 0 follows the pointer and every card after it lerps
 * toward its predecessor, so the fan opens with movement. `data-live` on the
 * stage fades the cards in and out (the stylesheet puts a 55ms stair on the
 * fade). Elements matching `pinSelector` gather the chain around themselves
 * on keyboard focus — a static card beside them under reduced motion, the
 * full fan otherwise. The loop parks while the stage is off screen.
 */
export function imageTrail(el: HTMLElement, { cardSelector = '.nx-image-trail-card', pinSelector = '[data-trail-pin]', idle = 140 }: ImageTrailOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  const cards = Array.from(el.querySelectorAll<HTMLElement>(cardSelector));
  if (cards.length === 0) return () => {};

  const spots = cards.map(() => ({ x: 0, y: 0 }));
  cards.forEach((card, index) => card.style.setProperty('--nx-i', String(index)));

  let frame = 0;
  let live = false;
  let visible = true;
  let reduced = prefersReducedMotion();
  let px = 0;
  let py = 0;
  let cardWidth = 0;
  let cardHeight = 0;
  let lastMove = 0;
  let holdUntil = 0;
  let fadeTimer: ReturnType<typeof setTimeout> | undefined;

  const measure = () => {
    cardWidth = cards[0]?.offsetWidth ?? 0;
    cardHeight = cards[0]?.offsetHeight ?? 0;
  };
  measure();
  px = el.clientWidth / 2;
  py = el.clientHeight / 2;
  for (const spot of spots) {
    spot.x = px;
    spot.y = py;
  }

  const setLive = (value: boolean) => {
    live = value;
    el.toggleAttribute('data-live', value);
  };

  const apply = () => {
    if (!cardWidth || !cardHeight) measure();
    let tx = px;
    let ty = py;
    for (let i = 0; i < cards.length; i++) {
      const spot = spots[i]!;
      const k = Math.max(0.06, 0.3 - i * 0.022);
      spot.x += (tx - spot.x) * k;
      spot.y += (ty - spot.y) * k;
      tx = spot.x;
      ty = spot.y;
      const rotate = clamp((px - spot.x) * 0.9, -16, 16);
      const scale = 1 - i * 0.045;
      const card = cards[i]!;
      card.style.zIndex = String(30 - i);
      card.style.transform = `translate3d(${(spot.x - cardWidth / 2).toFixed(1)}px, ${(spot.y - cardHeight / 2).toFixed(1)}px, 0) rotate(${rotate.toFixed(2)}deg) scale(${scale.toFixed(3)})`;
    }
  };

  const tick = (now: number) => {
    frame = 0;
    apply();
    if (now > Math.max(lastMove + idle, holdUntil)) {
      setLive(false);
      return;
    }
    frame = requestAnimationFrame(tick);
  };
  const run = () => {
    if (!frame && visible) frame = requestAnimationFrame(tick);
  };

  const move = (event: PointerEvent) => {
    if (reduced) return;
    const rect = el.getBoundingClientRect();
    px = event.clientX - rect.left;
    py = event.clientY - rect.top;
    lastMove = performance.now();
    setLive(true);
    run();
  };

  const stop = () => {
    setLive(false);
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
  };

  const gather = (target: HTMLElement) => {
    const rect = el.getBoundingClientRect();
    const box = target.getBoundingClientRect();
    const cx = box.left + box.width / 2 - rect.left;
    const cy = box.top + box.height / 2 - rect.top;
    clearTimeout(fadeTimer);

    if (reduced) {
      // One static card parks beside the link; the chain itself is dropped.
      const side = direction(el);
      const head = spots[0]!;
      head.x = cx + 96 * side;
      head.y = cy;
      for (let i = 1; i < spots.length; i++) {
        spots[i]!.x = head.x;
        spots[i]!.y = head.y;
      }
      px = head.x;
      py = head.y;
      apply();
      setLive(true);
      fadeTimer = setTimeout(() => setLive(false), 1700);
      return;
    }

    px = cx;
    py = cy;
    const side = direction(el);
    const count = spots.length;
    for (let i = 0; i < count; i++) {
      spots[i]!.x = cx + (i - (count - 1) / 2) * 24 * side;
      spots[i]!.y = cy - Math.sin((i / Math.max(1, count - 1)) * Math.PI) * 64;
    }
    apply();
    setLive(true);
    holdUntil = performance.now() + 1500;
    run();
  };

  const focusIn = (event: FocusEvent) => {
    const target = event.target;
    if (target instanceof Element) {
      const pin = target.closest<HTMLElement>(pinSelector);
      if (pin && el.contains(pin)) gather(pin);
    }
  };
  const focusOut = () => {
    // Let the fan settle, then fade — unless another pin takes focus first.
    clearTimeout(fadeTimer);
    fadeTimer = setTimeout(() => {
      const active = document.activeElement;
      if (!(active instanceof Element) || !active.closest(pinSelector)) setLive(false);
    }, 400);
  };

  const observer = 'IntersectionObserver' in window
    ? new IntersectionObserver((entries) => {
        visible = entries[0]?.isIntersecting ?? true;
        if (!visible) stop();
      }, { threshold: 0.05 })
    : null;
  observer?.observe(el);

  const onResize = () => {
    cardWidth = 0;
    cardHeight = 0;
  };
  const stopMotion = onReducedMotionChange((value) => {
    reduced = value;
    if (reduced) stop();
  });

  el.addEventListener('pointermove', move);
  el.addEventListener('pointerleave', stop);
  el.addEventListener('focusin', focusIn);
  el.addEventListener('focusout', focusOut);
  window.addEventListener('resize', onResize);

  return () => {
    stop();
    clearTimeout(fadeTimer);
    park(observer);
    stopMotion();
    window.removeEventListener('resize', onResize);
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerleave', stop);
    el.removeEventListener('focusin', focusIn);
    el.removeEventListener('focusout', focusOut);
    for (const card of cards) {
      card.style.removeProperty('transform');
      card.style.removeProperty('z-index');
      card.style.removeProperty('--nx-i');
    }
  };
}

/* ---- Glare card ------------------------------------------------------------------------ */

export interface GlareCardOptions {
  /** Largest tilt in degrees; the inline axis gets 1.2× this (default 10). */
  maxTilt?: number;
}

/**
 * The glare and parallax of one `.nx-glare-card`. A lerped loop (k 0.16) only
 * runs while the pointer is over the card or the card is settling back, and
 * parks while the card is off screen. It writes the private properties the
 * stylesheet reads: `--_rx/--_ry` (the tilt), `--_x/--_y` (the pointer's
 * offset from centre, −0.5…0.5, which each depth layer scales its own way),
 * `--_mx/--_my` (the halo's position in %), `--_rot` (the streak's angle from
 * atan2) and `data-lit` (the halo's presence). Keyboard focus poses the card
 * at a fixed angle, a tap sweeps the glare across once, and under reduced
 * motion nothing listens at all — the stylesheet shows a fixed soft sheen.
 */
export function glareCard(el: HTMLElement, { maxTilt = 10 }: GlareCardOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  let frame = 0;
  let live = false;
  let visible = true;
  let reduced = prefersReducedMotion();
  let tx = 0.5;
  let ty = 0.5;
  let cx = 0.5;
  let cy = 0.5;
  let sweepTimer: ReturnType<typeof setTimeout> | undefined;

  const apply = () => {
    const x = cx - 0.5;
    const y = cy - 0.5;
    const rotate = Math.atan2(y, x) * 57.29578 + 90;
    el.style.setProperty('--_rx', `${(y * -maxTilt).toFixed(2)}deg`);
    el.style.setProperty('--_ry', `${(x * maxTilt * 1.2).toFixed(2)}deg`);
    el.style.setProperty('--_x', x.toFixed(3));
    el.style.setProperty('--_y', y.toFixed(3));
    el.style.setProperty('--_mx', `${(cx * 100).toFixed(1)}%`);
    el.style.setProperty('--_my', `${(cy * 100).toFixed(1)}%`);
    el.style.setProperty('--_rot', `${rotate.toFixed(1)}deg`);
    el.toggleAttribute('data-lit', live || Math.abs(x) + Math.abs(y) > 0.01);
  };

  const tick = () => {
    frame = 0;
    cx += (tx - cx) * 0.16;
    cy += (ty - cy) * 0.16;
    apply();
    const settling = Math.abs(tx - cx) + Math.abs(ty - cy) > 0.002;
    if (!live && !settling) {
      cx = tx;
      cy = ty;
      apply();
      return;
    }
    frame = requestAnimationFrame(tick);
  };
  const run = () => {
    if (!frame && visible && !reduced) frame = requestAnimationFrame(tick);
  };
  /** Drop everything the loop ever wrote, so the reduced-motion styles own the card. */
  const clear = () => {
    for (const name of ['--_rx', '--_ry', '--_x', '--_y', '--_mx', '--_my', '--_rot']) el.style.removeProperty(name);
    el.removeAttribute('data-lit');
  };

  const move = (event: PointerEvent) => {
    if (reduced || event.pointerType === 'touch') return;
    const rect = el.getBoundingClientRect();
    tx = clamp((event.clientX - rect.left) / rect.width, 0, 1);
    ty = clamp((event.clientY - rect.top) / rect.height, 0, 1);
    live = true;
    run();
  };
  const pose = (x: number, y: number) => {
    if (reduced) return;
    tx = x;
    ty = y;
    live = true;
    run();
  };
  const rest = () => {
    if (reduced) {
      clear();
      return;
    }
    tx = 0.5;
    ty = 0.5;
    live = false;
    run();
  };
  const sweep = () => {
    if (reduced) return;
    pose(0.74, 0.28);
    clearTimeout(sweepTimer);
    sweepTimer = setTimeout(rest, 620);
  };
  const focusPose = () => pose(0.68, 0.32);

  const observer = 'IntersectionObserver' in window
    ? new IntersectionObserver((entries) => {
        visible = entries[0]?.isIntersecting ?? true;
        if (!visible) {
          live = false;
          if (frame) cancelAnimationFrame(frame);
          frame = 0;
        }
      }, { threshold: 0.05 })
    : null;
  observer?.observe(el);

  const stopMotion = onReducedMotionChange((value) => {
    reduced = value;
    if (reduced) {
      if (frame) cancelAnimationFrame(frame);
      frame = 0;
      live = false;
      clear();
    }
  });

  el.addEventListener('pointermove', move);
  el.addEventListener('pointerleave', rest);
  el.addEventListener('pointerdown', sweep);
  el.addEventListener('focus', focusPose);
  el.addEventListener('blur', rest);

  return () => {
    if (frame) cancelAnimationFrame(frame);
    clearTimeout(sweepTimer);
    park(observer);
    stopMotion();
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerleave', rest);
    el.removeEventListener('pointerdown', sweep);
    el.removeEventListener('focus', focusPose);
    el.removeEventListener('blur', rest);
    for (const name of ['--_rx', '--_ry', '--_x', '--_y', '--_mx', '--_my', '--_rot']) el.style.removeProperty(name);
    el.removeAttribute('data-lit');
  };
}

/* ---- Magnetic field --------------------------------------------------------------------- */

export interface MagneticFieldOptions {
  /** Selector of the items the field moves (default '.nx-magnetic-item'). */
  itemSelector?: string;
  /** Initial field radius in px; also tunable live through `--nx-field-r` (default 130). */
  radius?: number;
  /** Initial pull strength, 0–1; also tunable live through `--nx-field-k` (default 0.5). */
  strength?: number;
}

/**
 * One magnetic field over a set of `.nx-magnetic-item` elements. While the
 * pointer is inside the radius each item is drawn toward the hand with
 * distance falloff, capped by the item's `data-pull` (px, default 24); a
 * stiffness/damping spring (k 0.13, damping 0.8) carries every item home with
 * a little overshoot once the hand leaves. An item's `data-pose="x y"`
 * (px) overrides the pull while the item holds keyboard focus. The radius and
 * strength are read from `--nx-field-r` / `--nx-field-k` every frame, so
 * changing the properties moves the field without restarting it. Under
 * reduced motion the field is off and every item sits still.
 */
export function magneticField(el: HTMLElement, { itemSelector = '.nx-magnetic-item', radius, strength }: MagneticFieldOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  const items = Array.from(el.querySelectorAll<HTMLElement>(itemSelector)).map((item) => ({
    el: item,
    x: 0,
    y: 0,
    vx: 0,
    vy: 0,
    pull: parseFloat(item.dataset.pull ?? '') || 24,
    pose: (item.dataset.pose ?? '').split(/\s+/).filter(Boolean).map(Number),
    posed: false,
  }));
  if (items.length === 0) return () => {};

  let wroteParams = false;
  if (radius !== undefined && !el.style.getPropertyValue('--nx-field-r')) {
    el.style.setProperty('--nx-field-r', `${radius}px`);
    wroteParams = true;
  }
  if (strength !== undefined && !el.style.getPropertyValue('--nx-field-k')) {
    el.style.setProperty('--nx-field-k', String(strength));
    wroteParams = true;
  }

  let frame = 0;
  let live = false;
  let visible = true;
  let reduced = prefersReducedMotion();
  let px = -9999;
  let py = -9999;
  let fieldRadius = radius ?? 130;
  let fieldStrength = strength ?? 0.5;

  const readParams = () => {
    const r = parseFloat(el.style.getPropertyValue('--nx-field-r'));
    const k = parseFloat(el.style.getPropertyValue('--nx-field-k'));
    if (Number.isFinite(r)) fieldRadius = r;
    if (Number.isFinite(k)) fieldStrength = clamp(k, 0, 1);
  };

  const tick = () => {
    frame = 0;
    readParams();
    const field = el.getBoundingClientRect();
    let energy = 0;
    for (const item of items) {
      let tx = 0;
      let ty = 0;
      if (item.posed && item.pose.length === 2 && item.pose.every(Number.isFinite)) {
        tx = item.pose[0]!;
        ty = item.pose[1]!;
      } else {
        const box = item.el.getBoundingClientRect();
        const dx = px - (box.left + box.width / 2 - field.left);
        const dy = py - (box.top + box.height / 2 - field.top);
        const dist = Math.hypot(dx, dy);
        if (dist > 0 && dist <= fieldRadius) {
          const falloff = 1 - dist / fieldRadius;
          const pull = Math.min(item.pull, dist * fieldStrength * 0.9) * falloff;
          tx = (dx / dist) * pull;
          ty = (dy / dist) * pull;
        }
      }
      item.vx = (item.vx + (tx - item.x) * 0.13) * 0.8;
      item.vy = (item.vy + (ty - item.y) * 0.13) * 0.8;
      item.x += item.vx;
      item.y += item.vy;
      energy += Math.abs(item.vx) + Math.abs(item.vy) + Math.abs(tx - item.x) + Math.abs(ty - item.y);
      item.el.style.setProperty('--_x', `${item.x.toFixed(2)}px`);
      item.el.style.setProperty('--_y', `${item.y.toFixed(2)}px`);
    }
    if (!live && energy < 0.35) return;
    frame = requestAnimationFrame(tick);
  };
  const run = () => {
    if (!frame && visible && !reduced) frame = requestAnimationFrame(tick);
  };

  const move = (event: PointerEvent) => {
    if (reduced) return;
    const rect = el.getBoundingClientRect();
    px = event.clientX - rect.left;
    py = event.clientY - rect.top;
    live = true;
    run();
  };
  const leave = () => {
    px = -9999;
    py = -9999;
    live = false;
    run();
  };

  const focusIn = (event: FocusEvent) => {
    if (reduced) return;
    const target = event.target;
    const hit = target instanceof Element ? target.closest<HTMLElement>(itemSelector) : null;
    for (const item of items) item.posed = item.el === hit;
    run();
  };
  const focusOut = () => {
    for (const item of items) item.posed = false;
    run();
  };

  const settle = () => {
    live = false;
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    for (const item of items) {
      item.x = 0;
      item.y = 0;
      item.vx = 0;
      item.vy = 0;
      item.el.style.setProperty('--_x', '0px');
      item.el.style.setProperty('--_y', '0px');
    }
  };

  const observer = 'IntersectionObserver' in window
    ? new IntersectionObserver((entries) => {
        visible = entries[0]?.isIntersecting ?? true;
        if (!visible) settle();
      }, { threshold: 0.05 })
    : null;
  observer?.observe(el);

  const stopMotion = onReducedMotionChange((value) => {
    reduced = value;
    if (reduced) settle();
  });

  el.addEventListener('pointermove', move);
  el.addEventListener('pointerdown', move);
  el.addEventListener('pointerleave', leave);
  el.addEventListener('focusin', focusIn);
  el.addEventListener('focusout', focusOut);

  return () => {
    settle();
    park(observer);
    stopMotion();
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerdown', move);
    el.removeEventListener('pointerleave', leave);
    el.removeEventListener('focusin', focusIn);
    el.removeEventListener('focusout', focusOut);
    for (const item of items) {
      item.el.style.removeProperty('--_x');
      item.el.style.removeProperty('--_y');
    }
    if (wroteParams) {
      el.style.removeProperty('--nx-field-r');
      el.style.removeProperty('--nx-field-k');
    }
  };
}
