/**
 * Alpine part for the gantt block. The Blade component renders the time grid,
 * bars, dependency elbows, today line and the zoom pills server-side; this
 * adds the pointer drag (move a bar, resize it from either edge, rubber-band
 * past the timeline), the arrow-key moves, the zoom switch, the today jump and
 * the wire sync — the same behaviours the React component implements over the
 * same CSS.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installGanttBlocks } from './alpine/blocks/gantt';
 *   installGanttBlocks(Alpine);   // x-data="nxGantt(config)"
 */
import { reveal } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

/** The bits of Livewire's $wire these parts use (absent outside Livewire). */
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };

export type GanttZoom = 'day' | 'week' | 'month';

export interface GanttLabels {
  moved: string;
  resized: string;
  days: string;
}

export interface GanttConfig {
  zoom: GanttZoom;
  labels: GanttLabels;
  locale?: string;
  moveAction?: string | null;
  /** The timeline's first day as a UTC day number (its --nx-gantt-origin). */
  origin: number;
  /** How many days the timeline spans (its --nx-gantt-days). */
  days: number;
}

const DAY = 86_400_000;

/** `:date`/`:task` → the values, already formatted by the caller. */
const withParams = (template: string, params: Record<string, string | number>) => template.replace(/:(\w+)/g, (_, name: string) => String(params[name] ?? `:${name}`));

const clamp = (value: number, min: number, max: number) => Math.max(min, Math.min(value, max));

/** A UTC day number → 'YYYY-MM-DD'. */
const isoOf = (day: number) => new Date(day * DAY).toISOString().slice(0, 10);

/** One day, in px, from the live --nx-gantt-unit the zoom sets. */
function unitOf(root: HTMLElement): number {
  const value = getComputedStyle(root).getPropertyValue('--nx-gantt-unit').trim();
  const n = parseFloat(value);
  return value.endsWith('rem') ? n * parseFloat(getComputedStyle(document.documentElement).fontSize) : n || 24;
}

const isRtl = (el: Element) => getComputedStyle(el).direction === 'rtl';

interface DragState {
  id: string;
  title: string;
  mode: 'move' | 'start' | 'end';
  pointer: number;
  startX: number;
  origS: number;
  origN: number;
  el: HTMLElement;
  unit: number;
  rtl: boolean;
}

/** Where a drag or a nudge lands, clamped to the timeline; null when nothing moved. */
function landed(state: Pick<DragState, 'origS' | 'origN' | 'mode'>, deltaDays: number, totalDays: number) {
  const d = Math.round(deltaDays);
  if (state.mode === 'end') {
    const n = clamp(state.origN + d, 1, totalDays - state.origS);
    return n === state.origN ? null : { s: state.origS, n };
  }
  if (state.mode === 'start') {
    const s = clamp(state.origS + d, 0, state.origS + state.origN - 1);
    return s === state.origS ? null : { s, n: state.origS + state.origN - s };
  }
  const s = clamp(state.origS + d, 0, totalDays - state.origN);
  return s === state.origS ? null : { s, n: state.origN };
}

export function installGanttBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxGantt', (config: GanttConfig) => {
    // Persian dates stay Gregorian (like the PHP side) so labels line up with the bands.
    const locale = config.locale ?? 'en';
    const dates = locale.slice(0, 2).toLowerCase() === 'fa' ? `${locale}-u-ca-gregory` : locale;
    const short = new Intl.DateTimeFormat(dates, { day: 'numeric', month: 'short', timeZone: 'UTC' });
    const long = new Intl.DateTimeFormat(dates, { day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' });
    const number = new Intl.NumberFormat(locale);
    let drag: DragState | null = null;

    interface GanttSelf {
      zoom: GanttZoom;
      announce: string;
      $root: HTMLElement;
      zoomChange: (event: Event) => void;
      goToday: () => void;
      barDown: (event: PointerEvent) => void;
      barMove: (event: PointerEvent) => void;
      barUp: (event: PointerEvent) => void;
      barCancel: (event: PointerEvent) => void;
      barKey: (event: KeyboardEvent) => void;
      commit: (state: DragState, next: { s: number; n: number }, announce: boolean) => void;
    }

    return {
      zoom: config.zoom,
      announce: '',

      init(this: Self<GanttSelf>) {
        reveal(this.$root, { once: true });
      },

      /** The zoom pills: one radio group, the root's data-zoom follows the choice. */
      zoomChange(this: Self<GanttSelf>, event: Event) {
        const input = event.target as HTMLInputElement | null;
        if (input?.type !== 'radio') return;
        const value = input.value;
        if (value === 'day' || value === 'week' || value === 'month') this.zoom = value;
      },

      /** Scroll the today line into a comfortable third-of-view position. */
      goToday(this: Self<GanttSelf>) {
        const root = this.$root;
        const frame = root.querySelector<HTMLElement>('.nx-gantt-frame');
        const today = root.querySelector<HTMLElement>('.nx-gantt-today');
        if (!frame || !today) return;
        const d = Number(today.style.getPropertyValue('--d')) || 0;
        const max = frame.scrollWidth - frame.clientWidth;
        const pos = clamp(d * unitOf(root) - frame.clientWidth / 3, 0, max);
        const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        frame.scrollTo({ left: isRtl(frame) ? -pos : pos, behavior: calm ? 'auto' : 'smooth' });
      },

      /* ---- Pointer drag (delegated from the lanes; bars need no handlers of their own) ---- */

      barDown(this: Self<GanttSelf>, event: PointerEvent) {
        if (event.button !== 0 || !event.isPrimary) return;
        const bar = (event.target as HTMLElement).closest<HTMLElement>('.nx-gantt-bar');
        if (!bar || !bar.dataset.task) return;
        const lanes = this.$root.querySelector<HTMLElement>('.nx-gantt-lanes');
        if (!lanes) return;
        const handle = (event.target as HTMLElement).closest<HTMLElement>('[data-handle]');
        const mode = handle?.dataset.handle === 'start' || handle?.dataset.handle === 'end' ? handle.dataset.handle : 'move';
        lanes.setPointerCapture(event.pointerId);
        drag = {
          id: bar.dataset.task,
          title: bar.dataset.title ?? bar.dataset.task,
          mode,
          pointer: event.pointerId,
          startX: event.clientX,
          origS: Number(bar.style.getPropertyValue('--s')) || 0,
          origN: Number(bar.style.getPropertyValue('--n')) || 1,
          el: bar,
          unit: unitOf(this.$root),
          rtl: isRtl(bar),
        };
        bar.dataset.dragging = '';
      },

      barMove(this: Self<GanttSelf>, event: PointerEvent) {
        if (!drag || event.pointerId !== drag.pointer) return;
        // One drag, one finger: extra touches after the first are ignored.
        if (event.pointerType === 'touch' && !event.isPrimary) return;
        const days = ((event.clientX - drag.startX) / drag.unit) * (drag.rtl ? -1 : 1);
        const raw = drag.mode === 'end' ? drag.origS + drag.origN + days : drag.origS + days;
        const clamped =
          drag.mode === 'end'
            ? clamp(raw, drag.origS + 1, config.days - drag.origS)
            : drag.mode === 'start'
              ? clamp(raw, 0, drag.origS + drag.origN - 1)
              : clamp(raw, 0, config.days - drag.origN);
        // Rubber-band past the edges: the overshoot follows at a third of its weight.
        drag.el.style.setProperty('--over', String((raw - clamped) * 0.35));
        const snapped = Math.round(clamped);
        if (drag.mode === 'end') drag.el.style.setProperty('--dn', String(snapped - (drag.origS + drag.origN)));
        else drag.el.style.setProperty('--ds', String(snapped - drag.origS));
      },

      barUp(this: Self<GanttSelf>, event: PointerEvent) {
        if (!drag || event.pointerId !== drag.pointer) return;
        const days = ((event.clientX - drag.startX) / drag.unit) * (drag.rtl ? -1 : 1);
        const next = landed(drag, days, config.days);
        const state = drag;
        if (next) this.commit(state, next, true);
        else this.commit(state, { s: state.origS, n: state.origN }, false);
      },

      barCancel(this: Self<GanttSelf>, event: PointerEvent) {
        if (!drag || event.pointerId !== drag.pointer) return;
        this.commit(drag, { s: drag.origS, n: drag.origN }, false);
      },

      /** Arrow keys move (Shift = a week); Alt resizes the end; Escape cancels a drag. */
      barKey(this: Self<GanttSelf>, event: KeyboardEvent) {
        const bar = (event.target as HTMLElement).closest<HTMLElement>('.nx-gantt-bar');
        if (!bar || !bar.dataset.task) return;
        if (event.key === 'Escape') {
          if (drag?.el === bar) this.commit(drag, { s: drag.origS, n: drag.origN }, false);
          return;
        }
        if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
        if (drag) return;
        const rtl = isRtl(bar);
        const step = (event.key === 'ArrowRight' ? 1 : -1) * (rtl ? -1 : 1) * (event.shiftKey ? 7 : 1);
        const state: DragState = {
          id: bar.dataset.task,
          title: bar.dataset.title ?? bar.dataset.task,
          mode: event.altKey ? 'end' : 'move',
          pointer: -1,
          startX: 0,
          origS: Number(bar.style.getPropertyValue('--s')) || 0,
          origN: Number(bar.style.getPropertyValue('--n')) || 1,
          el: bar,
          unit: 0,
          rtl,
        };
        const next = landed(state, step, config.days);
        if (next) this.commit(state, next, true);
        event.preventDefault();
      },

      /** Park a bar at its new span: state stays client-side until the wire action lands.
       *  `announce` is skipped for a cancelled drag (nothing happened). */
      commit(this: Self<GanttSelf>, state: DragState, next: { s: number; n: number }, announce: boolean) {
        drag = null;
        const el = state.el;
        delete el.dataset.dragging;
        el.style.removeProperty('--ds');
        el.style.removeProperty('--dn');
        el.style.removeProperty('--over');

        // Nothing moved: a click, a cancelled drag, or a nudge into the clamp.
        const moved = next.s !== state.origS || next.n !== state.origN;
        if (!moved) return;
        el.style.setProperty('--s', String(next.s));
        el.style.setProperty('--n', String(next.n));

        const origin = config.origin;
        const sIso = isoOf(origin + next.s);
        const eIso = isoOf(origin + next.s + next.n - 1);

        // Keep the spoken truth honest: the bar's label and its hidden-table row.
        el.setAttribute('aria-label', `${state.title}, ${short.format(new Date((origin + next.s) * DAY))} – ${short.format(new Date((origin + next.s + next.n - 1) * DAY))}`);
        const row = this.$root.querySelector<HTMLElement>(`tr[data-task="${CSS.escape(state.id)}"]`);
        if (row) {
          const cell = (field: string, text: string) => {
            const node = row.querySelector<HTMLElement>(`[data-field="${field}"]`);
            if (node) node.textContent = text;
          };
          cell('start', long.format(new Date((origin + next.s) * DAY)));
          cell('end', long.format(new Date((origin + next.s + next.n - 1) * DAY)));
          cell('duration', withParams(config.labels.days, { count: number.format(next.n) }));
        }

        if (announce) {
          this.announce =
            state.mode === 'end'
              ? withParams(config.labels.resized, { task: state.title, date: short.format(new Date((origin + next.s + next.n - 1) * DAY)) })
              : withParams(config.labels.moved, { task: state.title, date: short.format(new Date((origin + next.s) * DAY)) });
        }
        this.$dispatch('nx-move', { task: state.id, start: sIso, end: eIso });

        if (config.moveAction) {
          const wire = (this as unknown as { $wire?: Wire }).$wire;
          if (wire) void wire.call(config.moveAction, state.id, sIso, eIso);
        }
      },
    };
  });
}
