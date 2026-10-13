/**
 * Framework-agnostic behaviours for the entrance blocks.
 *
 *   curtainReveal    the overture of a launch page: an eased 0–100 counter rides
 *                    two curtain panels; at one hundred the panels part to the
 *                    sides and the content beneath enters item by item — the
 *                    count only runs while the stage is on screen and parks
 *                    when it leaves
 *   curtainReplay     closes the curtains and runs the count again
 *   staggerList      indexes a list's items and flags it in view, so the CSS
 *                    can wave them in one after another (rise + blur + a hair
 *                    of skew on the soft spring)
 *   staggerReplay     pulls the wave back and sends it again
 *   splitFlap        adopts a Solari board's cells, paints its first value and
 *                    parks the mechanics off screen; setSplitFlapValue spells
 *                    a new value through the leaves in a cascade
 *
 * The motion itself lives in css/blocks/entrances.css; these measure, write
 * attributes and custom properties, and clean up after themselves.
 */
import { type Cleanup, isBrowser, markScripted, prefersReducedMotion } from '../env';
import { localeOf } from '../i18n';
import { localeDigits } from '../text';

/* ---- Curtain reveal ------------------------------------------------------------------ */

export interface CurtainRevealOptions {
  /** How long the eased count takes, in milliseconds (default 2400). */
  duration?: number;
  /** How much of the stage must be visible before the count starts, 0–1 (default 0.25). */
  threshold?: number;
  /** Called once the count completes and the curtains part. */
  onOpen?: () => void;
}

const easeOutCubic = (t: number) => 1 - (1 - t) ** 3;

/**
 * Drive a curtain overture: mount it on `.nx-curtain` (the wrapper that also
 * holds the replay button) or on the `.nx-curtain-stage` itself. While the
 * stage is in view an eased counter climbs 0–100 into `.nx-curtain-count` (in
 * the page's own digits), then the stage gains `data-open` — the stylesheet
 * parts the two `.nx-curtain-panel`s to either side and waves the
 * `.nx-curtain-item`s in on a stepped delay. Reduced motion skips the count
 * and opens the house at once; a click on any `[data-nx-curtain-replay]` in
 * the wrapper (or the `nx-curtain-replay` event, sent by `curtainReplay`)
 * closes the curtains and runs the overture again.
 */
export function curtainReveal(root: HTMLElement, { duration = 2400, threshold = 0.25, onOpen }: CurtainRevealOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  markScripted();

  const el = root.matches('.nx-curtain-stage') ? root : (root.querySelector<HTMLElement>('.nx-curtain-stage') ?? root);
  // The number itself, when the markup keeps a "%" beside it.
  const counter = el.querySelector<HTMLElement>('.nx-curtain-pct') ?? el.querySelector<HTMLElement>('.nx-curtain-count');
  const digits = localeDigits(localeOf(el));
  const paint = (pct: number) => {
    if (counter) counter.textContent = String(pct).replace(/\d/g, (d) => digits[Number(d)] ?? d);
  };

  let open = false;
  let visible = false;
  let frame = 0;
  let anchor = 0;
  let carried = 0;

  const finish = () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    open = true;
    paint(100);
    el.setAttribute('data-open', '');
    onOpen?.();
  };

  const loop = (now: number) => {
    const p = Math.min(1, carried + (now - anchor) / duration);
    paint(Math.round(easeOutCubic(p) * 100));
    if (p >= 1) {
      finish();
      return;
    }
    frame = requestAnimationFrame(loop);
  };

  const tick = () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    if (open || !visible) return;
    if (prefersReducedMotion()) {
      finish();
      return;
    }
    carried = 0;
    anchor = performance.now();
    frame = requestAnimationFrame(loop);
  };

  const replay = () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    open = false;
    carried = 0;
    paint(0);
    el.removeAttribute('data-open');
    // Two frames, so the stylesheet parks every item back in its hidden state first.
    requestAnimationFrame(() => requestAnimationFrame(tick));
  };

  const observer = new IntersectionObserver(
    (entries) => {
      visible = entries[0]?.isIntersecting ?? false;
      tick();
    },
    { threshold },
  );
  observer.observe(el);
  root.addEventListener('nx-curtain-replay', replay);

  const click = (event: Event) => {
    if (event.target instanceof Element && event.target.closest('[data-nx-curtain-replay]')) replay();
  };
  root.addEventListener('click', click);

  return () => {
    if (frame) cancelAnimationFrame(frame);
    observer.disconnect();
    root.removeEventListener('nx-curtain-replay', replay);
    root.removeEventListener('click', click);
    el.removeAttribute('data-open');
  };
}

/** Close the curtains of a curtain overture and run the count again. */
export function curtainReplay(el: Element): void {
  if (!isBrowser) return;
  el.dispatchEvent(new CustomEvent('nx-curtain-replay'));
}

/* ---- Stagger list ---------------------------------------------------------------------- */

export interface StaggerListOptions {
  /** Milliseconds between one item and the next (default 80). */
  step?: number;
  /** How much of the list must be visible before the wave starts, 0–1 (default 0.2). */
  threshold?: number;
}

/**
 * Wire a stagger list: mount it on `.nx-stagger` (the wrapper that also holds
 * the replay button) or on the `.nx-stagger-list` itself. The items
 * (`.nx-stagger-item`) are indexed (`--nx-i`) and the list gains `data-in`
 * when it scrolls into view, so the stylesheet can wave them in — a rise, a
 * soft blur and a hair of skew settling on the soft spring, each item delayed
 * by `--_step`. Clicking a `[data-nx-stagger-replay]` in the wrapper (or the
 * `nx-stagger-replay` event) pulls the wave back and sends it again; under
 * reduced motion the stylesheet never hides the items at all.
 */
export function staggerList(root: HTMLElement, { step = 80, threshold = 0.2 }: StaggerListOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  markScripted();

  const el = root.matches('.nx-stagger-list') ? root : (root.querySelector<HTMLElement>('.nx-stagger-list') ?? root);
  const items = Array.from(el.querySelectorAll<HTMLElement>('.nx-stagger-item'));
  /** Items we indexed ourselves (the markup may have set its own --nx-i; those stay). */
  const indexed = new Set<HTMLElement>();
  items.forEach((item, index) => {
    if (!item.style.getPropertyValue('--nx-i')) {
      item.style.setProperty('--nx-i', String(index));
      indexed.add(item);
    }
  });
  el.style.setProperty('--_step', `${step}ms`);

  const enter = () => {
    el.removeAttribute('data-in');
    requestAnimationFrame(() => requestAnimationFrame(() => el.setAttribute('data-in', '')));
  };

  const replay = () => {
    if (prefersReducedMotion()) {
      el.setAttribute('data-in', '');
      return;
    }
    enter();
  };

  const observer = new IntersectionObserver(
    (entries) => {
      if (entries[0]?.isIntersecting) {
        observer.disconnect();
        enter();
      }
    },
    // 0 too, so a list taller than the viewport still starts when it enters.
    { threshold: [0, threshold] },
  );
  observer.observe(el);
  root.addEventListener('nx-stagger-replay', replay);

  const click = (event: Event) => {
    if (event.target instanceof Element && event.target.closest('[data-nx-stagger-replay]')) replay();
  };
  root.addEventListener('click', click);

  return () => {
    observer.disconnect();
    root.removeEventListener('nx-stagger-replay', replay);
    root.removeEventListener('click', click);
    el.removeAttribute('data-in');
    el.style.removeProperty('--_step');
    indexed.forEach((item) => {
      item.style.removeProperty('--nx-i');
      indexed.delete(item);
    });
  };
}

/** Pull a stagger list's wave back and send it again. */
export function staggerReplay(el: Element): void {
  if (!isBrowser) return;
  el.dispatchEvent(new CustomEvent('nx-stagger-replay'));
}

/* ---- Split flap ------------------------------------------------------------------------- */

/** The charset a real Solari board can show — Latin caps, digits and a few marks. */
const FLAP_CHARSET = /[^A-Z0-9 :.\-·+]/g;

interface FlapCell {
  el: HTMLElement;
  cur: string;
  queue: string[];
  busy: boolean;
}

export interface SplitFlapOptions {
  /** The board's first value, painted without any flipping (default ''). */
  value?: string;
  /** Milliseconds between one cell and the next in a cascade (default 55). */
  step?: number;
}

/**
 * Adopt a `.nx-split-flap` board: every `.nx-split-flap-cell` becomes a
 * mechanical cell whose current glyph rides in `data-ch`, the board's first
 * value is painted straight away (no flipping), and the mechanics park while
 * the board is off screen. Use `setSplitFlapValue` to spell a new value
 * through the leaves.
 */
export function splitFlap(el: HTMLElement, { value = '', step = 55 }: SplitFlapOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  markScripted();

  const cells: FlapCell[] = Array.from(el.querySelectorAll<HTMLElement>('.nx-split-flap-cell')).map((cell) => ({
    el: cell,
    cur: cell.dataset.ch ?? ' ',
    queue: [],
    busy: false,
  }));
  (el as HTMLElement & { _nxFlap?: { cells: FlapCell[]; step: number } })._nxFlap = { cells, step };

  const paint = (cell: FlapCell, ch: string) => {
    const glyph = ch === ' ' ? '\u00a0' : ch;
    cell.el.querySelector<HTMLElement>('.nx-split-flap-top-half .nx-split-flap-g')!.textContent = glyph;
    cell.el.querySelector<HTMLElement>('.nx-split-flap-bot-half .nx-split-flap-g')!.textContent = glyph;
    cell.el.dataset.ch = ch;
  };

  const run = (cell: FlapCell) => {
    let next = cell.queue.shift();
    while (next !== undefined && next === cell.cur) next = cell.queue.shift();
    if (next === undefined) {
      cell.busy = false;
      return;
    }
    cell.busy = true;

    if (prefersReducedMotion() || el.hasAttribute('data-paused')) {
      paint(cell, next);
      cell.cur = next;
      run(cell);
      return;
    }

    const glyph = next === ' ' ? '\u00a0' : next;
    const leafT = cell.el.querySelector<HTMLElement>('.nx-split-flap-leaf-t');
    const leafB = cell.el.querySelector<HTMLElement>('.nx-split-flap-leaf-b');
    if (!leafT || !leafB || typeof leafT.animate !== 'function') {
      paint(cell, next);
      cell.cur = next;
      cell.busy = false;
      run(cell);
      return;
    }

    leafT.firstElementChild!.textContent = glyph;
    leafB.firstElementChild!.textContent = glyph;
    leafT.style.display = 'block';
    const drop = leafT.animate(
      [{ transform: 'rotateX(0deg)' }, { transform: 'rotateX(-90deg)' }],
      { duration: 130, easing: 'cubic-bezier(.5,0,.9,.45)' },
    );
    drop.onfinish = () => {
      cell.el.querySelector<HTMLElement>('.nx-split-flap-top-half .nx-split-flap-g')!.textContent = glyph;
      leafT.style.display = 'none';
      leafB.style.display = 'block';
      const settle = leafB.animate(
        [{ transform: 'rotateX(90deg)' }, { transform: 'rotateX(0deg)' }],
        { duration: 105, easing: 'cubic-bezier(.15,.65,.3,1)' },
      );
      settle.onfinish = () => {
        cell.el.querySelector<HTMLElement>('.nx-split-flap-bot-half .nx-split-flap-g')!.textContent = glyph;
        leafB.style.display = 'none';
        cell.cur = next;
        cell.busy = false;
        run(cell);
      };
    };
  };

  (el as HTMLElement & { _nxFlapRun?: (cell: FlapCell, ch: string) => void })._nxFlapRun = (cell, ch) => {
    cell.queue.push(ch);
    if (!cell.busy) run(cell);
  };

  // First paint: no mechanics, the board arrives already spelled.
  const normalized = value.toUpperCase().replace(FLAP_CHARSET, ' ');
  cells.forEach((cell, i) => {
    cell.cur = normalized[i] ?? ' ';
    paint(cell, cell.cur);
  });

  const observer = new IntersectionObserver(([entry]) => el.toggleAttribute('data-paused', !entry?.isIntersecting), {
    rootMargin: '48px',
  });
  observer.observe(el);

  return () => {
    observer.disconnect();
    el.removeAttribute('data-paused');
    cells.forEach((cell) => {
      cell.queue.length = 0;
      cell.busy = false;
      const leafT = cell.el.querySelector<HTMLElement>('.nx-split-flap-leaf-t');
      const leafB = cell.el.querySelector<HTMLElement>('.nx-split-flap-leaf-b');
      if (leafT) leafT.style.display = '';
      if (leafB) leafB.style.display = '';
      cell.el.getAnimations({ subtree: true }).forEach((animation) => animation.cancel());
    });
    delete (el as HTMLElement & { _nxFlap?: unknown })._nxFlap;
    delete (el as HTMLElement & { _nxFlapRun?: unknown })._nxFlapRun;
  };
}

/**
 * Spell a new value onto a `.nx-split-flap` board, cell by cell: each changed
 * cell flips on its two leaves a step after the one before it. Characters the
 * hardware cannot show become blanks, and the text is padded to the board's
 * width; off screen or under reduced motion every flip lands instantly.
 */
export function setSplitFlapValue(el: HTMLElement, value: string): void {
  const carrier = el as HTMLElement & { _nxFlap?: { cells: FlapCell[]; step: number }; _nxFlapRun?: (cell: FlapCell, ch: string) => void };
  const board = carrier._nxFlap;
  if (!isBrowser || !board || !board.cells.length) return;

  const text = value.toUpperCase().replace(FLAP_CHARSET, ' ').slice(0, board.cells.length).padEnd(board.cells.length, ' ');
  board.cells.forEach((cell, i) => {
    const ch = text[i] ?? ' ';
    window.setTimeout(() => carrier._nxFlapRun?.(cell, ch), board.step * i);
  });
}
