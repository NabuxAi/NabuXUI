/**
 * Alpine parts for the dashboards & data blocks. The Blade components render
 * the markup (server-side wherever the data allows); these add the state and
 * motion, with the same core helpers the React components use.
 *
 *   nxMultiSelect · nxHeatmap · nxDataTable · nxStatusBadge · nxMetricChart ·
 *   nxCurrencyConverter · nxWorkspaceShell · nxAnalyticsCard · nxDotMatrix ·
 *   nxBranchConnector · nxCurvedTimeline
 */
import {
  type CurrencyRates,
  type MetricGeometry,
  type SortDirection,
  type SortState,
  connect,
  convertCurrency,
  crossSwap,
  curvedTimeline,
  exchangeRate,
  fitStatusBadge,
  flipRows,
  iconSvg,
  icons,
  indicator,
  metricGeometry,
  morphPath,
  nearestIndex,
  nextSort,
  parseAmount,
  place,
  placeChartTip,
  playRowFlip,
  reveal,
  snapshotRows,
  sortRows,
} from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };
type LivewireGlobal = { hook?: (name: string, callback: (payload: { el: Element; toEl?: Element }) => void) => (() => void) | void };

/* ---- Livewire morphs ------------------------------------------------------------------------------
 * A Livewire re-render patches every element to the server's HTML, which drops what scripts wrote
 * at runtime: the reveal flag, measured paths, the moving highlight's position, a badge's width.
 * Inside these blocks (and only there) that runtime state is carried over to the incoming element,
 * so a wire:click elsewhere on the page never hides a chart or unlinks a connector.
 */
const DATA_BLOCKS = '.nx-multiselect, .nx-heatmap, .nx-data-table, .nx-status-badge, .nx-metric-chart, .nx-currency-converter, .nx-workspace, .nx-agent-card, .nx-analytics-card, .nx-dot-matrix, .nx-branch, .nx-curved-timeline, .nx-usage-card, .nx-comparison';
const RUNTIME_ATTRIBUTES = ['data-nx-revealed', 'data-nx-connected', 'data-nx-curved', 'data-nx-indicator', 'data-changed'];
const RUNTIME_PROPERTIES = ['--nx-at', '--nx-reach', '--nx-draw', '--_w', '--nx-ind-x', '--nx-ind-y', '--nx-ind-w', '--nx-ind-h', '--nx-tx', '--nx-ty'];
const MEASURED = ['d', 'viewBox', 'width', 'height'];

function keepRuntimeState({ el, toEl }: { el: Element; toEl?: Element }) {
  if (!toEl || el.nodeType !== 1 || toEl.nodeType !== 1 || !el.closest(DATA_BLOCKS)) return;
  for (const name of RUNTIME_ATTRIBUTES) if (el.hasAttribute(name) && !toEl.hasAttribute(name)) toEl.setAttribute(name, el.getAttribute(name) ?? '');
  // A table that turned itself into a scrollable region stays one.
  if (el.matches('.nx-data-table')) for (const name of ['tabindex', 'role']) if (el.hasAttribute(name) && !toEl.hasAttribute(name)) toEl.setAttribute(name, el.getAttribute(name) ?? '');
  // Paths and viewBoxes measured from the page.
  if (el.closest('[data-nx-links], [data-nx-curve-svg]')) for (const name of MEASURED) if (el.hasAttribute(name) && !toEl.hasAttribute(name)) toEl.setAttribute(name, el.getAttribute(name) ?? '');
  const from = (el as HTMLElement).style;
  const to = (toEl as HTMLElement).style;
  if (!from || !to) return;
  for (const name of RUNTIME_PROPERTIES) {
    const value = from.getPropertyValue(name);
    if (value && !to.getPropertyValue(name)) to.setProperty(name, value);
  }
}

const SVG = 'http://www.w3.org/2000/svg';
const supportsPopover = () => 'popover' in HTMLElement.prototype;
const seriesColor = (i: number) => `var(--nx-chart-${Math.min(Math.max(i, 0), 6) + 1})`;

/** Fold case, accents and the Persian/Arabic letter variants so a search matches either spelling. */
const fold = (text: string) =>
  text
    .toLocaleLowerCase()
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[يى]/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[\u064b-\u0670\u065f\u200c]/g, '');

function svgNode<K extends keyof SVGElementTagNameMap>(tag: K, attrs: Record<string, string | number>, text?: string): SVGElementTagNameMap[K] {
  const node = document.createElementNS(SVG, tag);
  for (const [name, value] of Object.entries(attrs)) node.setAttribute(name, String(value));
  // Labels are data: written as text, never parsed as markup.
  if (text !== undefined) node.textContent = text;
  return node;
}

/** Fill a chart tooltip (.nx-chart-tooltip) with a title and rows, as text. */
function fillTip(tip: HTMLElement, title: string, rows: Array<{ name: string; value: string; color?: string }>) {
  const heading = document.createElement('p');
  heading.className = 'nx-chart-tooltip-title';
  heading.textContent = title;
  tip.replaceChildren(
    heading,
    ...rows.map((row) => {
      const line = document.createElement('div');
      line.className = 'nx-chart-tooltip-row';
      if (row.color) {
        const key = document.createElement('span');
        key.className = 'nx-chart-tooltip-key';
        key.style.setProperty('--nx-series', row.color);
        line.append(key);
      }
      const value = document.createElement('strong');
      value.textContent = row.value;
      const name = document.createElement('span');
      name.textContent = row.name;
      line.append(value, name);
      return line;
    }),
  );
}

/** Arrow keys along one axis of a plot: the next index, null to leave, undefined when not handled. */
function stepIndex(event: KeyboardEvent, active: number | null, count: number, dir: 1 | -1 = 1): number | null | undefined {
  if (!count) return undefined;
  const at = active ?? -1;
  switch (event.key) {
    case 'ArrowRight':
      return Math.min(count - 1, Math.max(0, at + dir));
    case 'ArrowLeft':
      return Math.min(count - 1, Math.max(0, at === -1 ? 0 : at - dir));
    case 'Home':
      return 0;
    case 'End':
      return count - 1;
    case 'Escape':
      return null;
    default:
      return undefined;
  }
}

/** A delta pill's content: the trend icon and the signed percentage. */
function paintDelta(el: HTMLElement | undefined, delta: number | null | undefined, invert: boolean | undefined, locale?: string) {
  if (!el) return;
  el.hidden = delta === null || delta === undefined;
  if (el.hidden) return;
  const good = invert ? delta! <= 0 : delta! >= 0;
  el.setAttribute('data-trend', good ? 'up' : 'down');
  // A built-in icon (fixed markup from the core table) followed by the number as text.
  el.innerHTML = iconSvg(delta! >= 0 ? 'trend-up' : 'trend-down');
  el.append(new Intl.NumberFormat(locale, { style: 'percent', maximumFractionDigits: 1, signDisplay: 'exceptZero' }).format(delta! / 100));
}

interface MsOption {
  value: string;
  label: string;
  icon?: string | null;
  description?: string | null;
  disabled?: boolean;
}

type Chip = { key: string; state: 'idle' | 'enter' | 'exit' };

interface MetricConfig {
  labels: string[];
  metrics: Array<{ id: string; label: string; values: number[]; format?: Intl.NumberFormatOptions | null; value?: number | null; delta?: number | null; invertDelta?: boolean }>;
  active: number;
  locale?: string;
  height?: number;
}

interface Period {
  id: string;
  label: string;
  value: number;
  delta?: number | null;
  values: number[];
  labels?: string[] | null;
  caption?: string | null;
}

export function installDataBlocks(Alpine: AlpineLike): void {
  (window as unknown as { Livewire?: LivewireGlobal }).Livewire?.hook?.('morph.updating', keepRuntimeState);

  /* ---- Multi-select --------------------------------------------------------------------------
   * x-data="nxMultiSelect(options, value, max, removeLabel)" x-modelable="model": wire:model on the
   * component root binds the array of values (Livewire 3 and 4). `model` is what the outside sees;
   * anything that is not an array coming in (a property that does not exist yet) is answered
   * with the current selection instead of breaking it.
   */
  Alpine.data('nxMultiSelect', (options: MsOption[] = [], initial: string[] = [], max: number | null = null, removeLabel = 'Remove :name') => {
    const texts = options.map((o) => fold(`${o.label} ${o.description ?? ''} ${o.value}`));
    let unplace = () => {};
    let sweep: ReturnType<typeof setTimeout> | undefined;
    const labelOf = (key: string) => options.find((o) => o.value === key)?.label ?? key;

    return {
      value: [...initial] as string[],
      model: [...initial] as unknown,
      chips: initial.map((key) => ({ key, state: 'idle' })) as Chip[],
      open: false,
      query: '',
      active: -1,

      init(this: Self<{ open: boolean; value: string[]; model: unknown; query: string; show: () => void; reconcile: (v: string[]) => void; first: () => void; active: number }>) {
        const self = this;
        const trigger = this.$refs.trigger as HTMLButtonElement;
        const popover = this.$refs.popover;
        if (supportsPopover()) trigger.setAttribute('popovertarget', popover.id);
        popover.addEventListener('toggle', (event: Event) => {
          this.open = (event as ToggleEvent).newState === 'open';
        });
        this.$watch('open', (open: boolean) => {
          unplace();
          if (open) {
            unplace = place(this.$refs.field, popover, { side: 'bottom', align: 'start', offset: 6, matchWidth: true });
            this.first();
            requestAnimationFrame(() => (this.$refs.input as HTMLInputElement).focus());
          } else {
            this.query = '';
            this.active = -1;
            if (popover.contains(document.activeElement) || document.activeElement === document.body) trigger.focus();
          }
        });
        this.$watch('value', (next: string[]) => {
          this.reconcile(next);
          if (JSON.stringify(self.model) !== JSON.stringify(next)) self.model = [...next];
        });
        this.$watch('model', (next: unknown) => {
          if (!Array.isArray(next)) {
            self.model = [...this.value];
            return;
          }
          const clean = next.map(String);
          if (JSON.stringify(clean) !== JSON.stringify(this.value)) this.value = clean;
        });
        this.$watch('query', () => this.first());
      },

      destroy() {
        unplace();
        clearTimeout(sweep);
      },

      get full(): boolean {
        const self = this as unknown as { value: string[] };
        return max !== null && self.value.length >= max;
      },

      labelOf,

      /** Markup from the built-in icon table only (never from the data), for x-html. */
      iconOf(key: string) {
        const icon = options.find((o) => o.value === key)?.icon;
        return icon && icon in icons ? iconSvg(icon as keyof typeof icons) : '';
      },

      removeText(key: string) {
        return removeLabel.replace(':name', labelOf(key));
      },

      matches(this: { query: string }, i: number) {
        const q = fold(this.query.trim());
        return !q || texts[i]!.includes(q);
      },

      blocked(this: { full: boolean; value: string[] }, i: number) {
        const option = options[i];
        return !option || !!option.disabled || (this.full && !this.value.includes(option.value));
      },

      picked(this: { value: string[] }, i: number) {
        return this.value.includes(options[i]?.value ?? '\u0000');
      },

      get visible(): number[] {
        const self = this as unknown as { matches: (i: number) => boolean };
        return options.map((_, i) => i).filter((i) => self.matches(i));
      },

      first(this: { active: number; visible: number[]; blocked: (i: number) => boolean }) {
        this.active = this.visible.find((i) => !this.blocked(i)) ?? -1;
      },

      move(this: Self<{ active: number; visible: number[]; blocked: (i: number) => boolean }>, step: 1 | -1) {
        const list = this.visible;
        if (!list.length) return;
        let at = list.indexOf(this.active);
        // Nothing active yet: down starts at the top, up at the bottom.
        if (at < 0) at = step > 0 ? -1 : 0;
        for (let n = 0; n < list.length; n++) {
          at = (at + step + list.length) % list.length;
          if (!this.blocked(list[at]!)) break;
        }
        this.active = list[at]!;
        this.$nextTick(() => this.$refs.list.querySelector(`[data-index="${this.active}"]`)?.scrollIntoView({ block: 'nearest' }));
      },

      toggle(this: { value: string[]; blocked: (i: number) => boolean }, i: number) {
        if (this.blocked(i)) return;
        const v = options[i]!.value;
        this.value = this.value.includes(v) ? this.value.filter((x) => x !== v) : [...this.value, v];
      },

      remove(this: Self<{ value: string[] }>, key: string, from?: HTMLElement) {
        if (from) {
          const buttons = Array.from(this.$refs.field.querySelectorAll<HTMLButtonElement>('.nx-multiselect-chip:not([data-state="exit"]) .nx-multiselect-chip-remove'));
          const at = buttons.indexOf(from as HTMLButtonElement);
          (buttons[at + 1] ?? buttons[at - 1] ?? (this.$refs.trigger as HTMLButtonElement)).focus();
        }
        this.value = this.value.filter((x) => x !== key);
      },

      /** New values enter, removed ones collapse out before they go. */
      reconcile(this: { chips: Chip[] }, next: string[]) {
        const wanted = new Set(next);
        const known = new Set(this.chips.map((c) => c.key));
        const chips: Chip[] = this.chips.map((c) => (wanted.has(c.key) ? { key: c.key, state: c.state === 'exit' ? 'enter' : c.state } : { key: c.key, state: 'exit' }));
        for (const key of next) if (!known.has(key)) chips.push({ key, state: 'enter' });
        this.chips = chips;
        clearTimeout(sweep);
        if (chips.some((c) => c.state === 'exit')) sweep = setTimeout(() => (this.chips = this.chips.filter((c) => c.state !== 'exit')), 450);
      },

      settle(this: { chips: Chip[] }, event: TransitionEvent, key: string) {
        if (event.propertyName !== 'grid-template-columns' || event.target !== event.currentTarget) return;
        this.chips = this.chips.filter((c) => !(c.key === key && c.state === 'exit'));
      },

      show(this: Self<{ open: boolean }>) {
        const popover = this.$refs.popover;
        if (supportsPopover()) {
          try {
            popover.showPopover();
          } catch {
            /* already open */
          }
        } else {
          popover.setAttribute('data-open', '');
          this.open = true;
        }
      },

      hide(this: Self<{ open: boolean }>) {
        const popover = this.$refs.popover;
        popover.removeAttribute('data-open');
        try {
          if (popover.matches(':popover-open')) popover.hidePopover();
        } catch {
          /* no popover support */
        }
        if (!supportsPopover()) this.open = false;
      },

      /** Without the Popover API the button toggles the list itself. */
      click(this: { open: boolean; show: () => void; hide: () => void }) {
        if (!supportsPopover()) (this.open ? this.hide() : this.show());
      },

      fieldClick(this: Self<{ open: boolean }>, event: MouseEvent) {
        if (!this.open && (event.target === event.currentTarget || (event.target as Element).matches('.nx-multiselect-chips'))) (this.$refs.trigger as HTMLButtonElement).click();
      },

      triggerKey(this: { value: string[]; show: () => void; remove: (key: string) => void }, event: KeyboardEvent) {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
          event.preventDefault();
          this.show();
        } else if (event.key === 'Backspace' && this.value.length) {
          this.remove(this.value[this.value.length - 1]!);
        }
      },

      inputKey(this: { query: string; active: number; value: string[]; move: (s: 1 | -1) => void; toggle: (i: number) => void; remove: (key: string) => void; hide: () => void }, event: KeyboardEvent) {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
          event.preventDefault();
          this.move(event.key === 'ArrowDown' ? 1 : -1);
        } else if (event.key === 'Enter' || (event.key === ' ' && !this.query)) {
          event.preventDefault();
          if (this.active >= 0) this.toggle(this.active);
        } else if (event.key === 'Backspace' && !this.query && this.value.length) {
          this.remove(this.value[this.value.length - 1]!);
        } else if (event.key === 'Tab') {
          this.hide();
        } else if (event.key === 'Escape') {
          event.preventDefault();
          this.hide();
        }
      },
    };
  });

  /* ---- Heatmap: server-rendered cells; this adds the tooltip and the keyboard ---------------- */
  Alpine.data('nxHeatmap', (config: { locale?: string; unit?: string | null; format?: Intl.NumberFormatOptions | null } = {}) => {
    let active: HTMLElement | null = null;
    const fmt = new Intl.NumberFormat(config.locale, config.format ?? undefined);

    return {
      init(this: Self<object>) {
        reveal(this.$root, { once: true });
      },

      focusCell(this: Self<object>, cell: HTMLElement | null) {
        if (!cell || cell === active) return;
        active?.removeAttribute('data-active');
        active = cell;
        cell.setAttribute('data-active', '');
        const tip = this.$refs.tip;
        fillTip(tip, cell.dataset.title ?? '', [{ name: config.unit ?? '', value: fmt.format(Number(cell.dataset.value ?? 0)) }]);
        tip.setAttribute('data-open', '');
        placeChartTip(this.$refs.frame, cell, tip);
      },

      clear(this: Self<object>) {
        active?.removeAttribute('data-active');
        active = null;
        this.$refs.tip.removeAttribute('data-open');
      },

      pick(this: { focusCell: (cell: HTMLElement | null) => void }, event: PointerEvent) {
        this.focusCell((event.target as HTMLElement).closest<HTMLElement>('[data-col]'));
      },

      key(this: Self<{ focusCell: (cell: HTMLElement | null) => void; clear: () => void }>, event: KeyboardEvent) {
        const grid = this.$refs.grid;
        const cells = Array.from(grid.querySelectorAll<HTMLElement>('[data-col]'));
        if (!cells.length) return;
        const at = (col: number, row: number) => grid.querySelector<HTMLElement>(`[data-col="${col}"][data-row="${row}"]`);
        const cols = Math.max(...cells.map((c) => Number(c.dataset.col))) + 1;
        const rows = Math.max(...cells.map((c) => Number(c.dataset.row))) + 1;
        if (event.key === 'Escape') return this.clear();
        const rtl = getComputedStyle(grid).direction === 'rtl';
        const col = active ? Number(active.dataset.col) : cols - 1;
        const row = active ? Number(active.dataset.row) : 0;
        if (event.key === 'Home' || event.key === 'End') {
          event.preventDefault();
          const edge = event.key === 'Home' ? 0 : cols - 1;
          return this.focusCell(at(edge, row) ?? cells.find((c) => Number(c.dataset.col) === edge) ?? null);
        }
        const moves: Record<string, [number, number]> = { ArrowRight: [rtl ? -1 : 1, 0], ArrowLeft: [rtl ? 1 : -1, 0], ArrowDown: [0, 1], ArrowUp: [0, -1] };
        const move = moves[event.key];
        if (!move) return;
        event.preventDefault();
        if (!active) return this.focusCell(at(col, row) ?? cells[cells.length - 1]!);
        for (let c = col + move[0], r = row + move[1]; c >= 0 && c < cols && r >= 0 && r < rows; c += move[0], r += move[1]) {
          const next = at(c, r);
          if (next) return this.focusCell(next);
        }
      },
    };
  });

  /* ---- Data table ---------------------------------------------------------------------------
   * Client sorting (default): rows are server-rendered with a data-sort value per cell; pressing
   * a header reorders the <tr>s and they glide there (FLIP). With `sort-action`, the header calls
   * that Livewire method instead (method(key, direction)) and the rows glide after the morph.
   */
  Alpine.data('nxDataTable', (config: { sort?: SortState | null; action?: string | null; locale?: string } = {}) => {
    let unhook: () => void = () => {};
    let resize: ResizeObserver | null = null;

    return {
      sort: (config.sort ?? null) as SortState | null,

      init(this: Self<{ sort: SortState | null; apply: (animate: boolean) => void; paint: () => void }>) {
        reveal(this.$root, { once: true });
        if (this.sort && !config.action) this.apply(false);
        this.paint();
        // A table that overflows its box becomes a focusable region, so the keyboard can scroll it.
        const root = this.$root;
        const fit = () => {
          const scrolls = root.scrollWidth > root.clientWidth + 1 || root.scrollHeight > root.clientHeight + 1;
          if (scrolls) {
            root.setAttribute('tabindex', '0');
            root.setAttribute('role', 'region');
          } else {
            root.removeAttribute('tabindex');
            root.removeAttribute('role');
          }
        };
        fit();
        if ('ResizeObserver' in window) {
          resize = new ResizeObserver(fit);
          resize.observe(root);
        }
        // A Livewire re-render puts the server's order back: sort again, without motion.
        const livewire = (window as unknown as { Livewire?: LivewireGlobal }).Livewire;
        if (!config.action && livewire?.hook) {
          unhook =
            livewire.hook('morphed', ({ el }) => {
              if (el.contains(this.$root) && this.sort) {
                this.apply(false);
                this.paint();
              }
            }) ?? (() => {});
        }
      },

      destroy() {
        unhook();
        resize?.disconnect();
      },

      rows(this: Self<object>) {
        return Array.from((this.$refs.body as HTMLTableSectionElement).rows).filter((row) => !row.querySelector('.nx-data-table-empty'));
      },

      press(this: { sort: SortState | null; server: (next: SortState) => void; apply: (animate: boolean) => void; paint: () => void }, key: string) {
        const next = nextSort(this.sort, key);
        if (config.action) return this.server(next);
        this.sort = next;
        this.paint();
        this.apply(true);
      },

      apply(this: Self<{ sort: SortState | null; rows: () => HTMLTableRowElement[] }>, animate: boolean) {
        const body = this.$refs.body;
        const sort = this.sort;
        if (!sort) return;
        const column = Array.from(this.$root.querySelectorAll<HTMLElement>('thead th')).findIndex((th) => th.dataset.key === sort.key);
        if (column < 0) return;
        const sorted = sortRows(this.rows(), sort.key, sort.direction, { locale: config.locale, value: (row) => row.cells[column]?.dataset.sort ?? '' });
        if (animate) flipRows(body, () => body.append(...sorted));
        else body.append(...sorted);
      },

      async server(this: Self<{ sort: SortState | null; rows: () => HTMLTableRowElement[]; paint: () => void }>, next: SortState) {
        const wire = (this as unknown as { $wire?: Wire }).$wire;
        if (!wire || !config.action) return;
        const before = snapshotRows(this.rows());
        this.sort = next;
        this.paint();
        await wire.call(config.action, next.key, next.direction);
        await this.$nextTick();
        requestAnimationFrame(() => playRowFlip(this.rows(), before));
      },

      paint(this: Self<{ sort: SortState | null }>) {
        const sort = this.sort;
        for (const th of Array.from(this.$root.querySelectorAll<HTMLElement>('thead th[data-key]'))) {
          const direction: SortDirection | null = sort && sort.key === th.dataset.key ? sort.direction : null;
          if (direction) th.setAttribute('aria-sort', direction);
          else th.removeAttribute('aria-sort');
          const button = th.querySelector('.nx-data-table-sort');
          if (direction) button?.setAttribute('data-direction', direction);
          else button?.removeAttribute('data-direction');
        }
      },
    };
  });

  /* ---- Status badge: follows its data-status attribute (Livewire, x-bind or the server) ---- */
  Alpine.data('nxStatusBadge', () => {
    let observer: MutationObserver | null = null;
    let status = '';
    let width = '';

    return {
      init(this: Self<object>) {
        const badge = this.$root;
        const fit = () => {
          fitStatusBadge(badge);
          width = badge.style.getPropertyValue('--_w');
        };
        status = badge.dataset.status ?? '';
        fit();
        document.fonts?.ready.then(fit).catch(() => {});
        observer = new MutationObserver(() => {
          if ((badge.dataset.status ?? '') === status) return;
          status = badge.dataset.status ?? '';
          badge.setAttribute('data-changed', '');
          // Start from the old width even if a re-render wiped it, so the pill morphs.
          if (width && badge.style.getPropertyValue('--_w') !== width) {
            badge.style.setProperty('--_w', width);
            void badge.offsetWidth;
          }
          const label = badge.querySelector(`.nx-status-badge-layer[data-for="${status}"] .nx-status-badge-label`);
          const text = badge.querySelector(':scope > .nx-visually-hidden');
          if (label && text) text.textContent = label.textContent;
          fit();
        });
        observer.observe(badge, { attributes: true, attributeFilter: ['data-status', 'style'] });
      },

      destroy() {
        observer?.disconnect();
      },
    };
  });

  /* ---- Metric chart ---------------------------------------------------------------------------- */
  Alpine.data('nxMetricChart', (config: MetricConfig) => {
    let tabs: ReturnType<typeof indicator> | null = null;
    let resize: ResizeObserver | null = null;
    let width = 560;
    let geo: MetricGeometry | null = null;

    return {
      current: config.active ?? 0,
      hover: null as number | null,

      init(this: Self<{ current: number; hover: number | null; draw: (animate: boolean) => void; summary: () => void; show: () => void; moveTab: () => void }>) {
        tabs = indicator(this.$refs.tabs);
        this.moveTab();
        width = Math.round(this.$refs.plot.getBoundingClientRect().width) || 560;
        this.draw(false);
        this.summary();
        reveal(this.$root, { once: true });
        if ('ResizeObserver' in window) {
          resize = new ResizeObserver(([entry]) => {
            const next = Math.round(entry!.contentRect.width);
            if (next && Math.abs(next - width) > 1) {
              width = next;
              this.draw(false);
            }
          });
          resize.observe(this.$refs.plot);
        }
        this.$watch('current', () => {
          this.moveTab();
          this.draw(true);
          this.summary();
        });
        this.$watch('hover', () => this.show());
      },

      destroy() {
        tabs?.destroy();
        resize?.disconnect();
      },

      get metric() {
        return config.metrics[(this as unknown as { current: number }).current] ?? config.metrics[0]!;
      },

      moveTab(this: Self<{ current: number }>) {
        tabs?.update(this.$refs.tabs.querySelector(`[data-index="${this.current}"]`));
      },

      select(this: { current: number }, i: number) {
        this.current = i;
      },

      tabKey(this: Self<{ current: number }>, event: KeyboardEvent) {
        const dir = getComputedStyle(this.$refs.tabs).direction === 'rtl' ? -1 : 1;
        const next = stepIndex(event, this.current, config.metrics.length, dir);
        if (next === undefined || next === null) return;
        event.preventDefault();
        this.current = next;
        this.$refs.tabs.querySelector<HTMLElement>(`[data-index="${next}"]`)?.focus();
      },

      draw(this: Self<{ current: number; metric: MetricConfig['metrics'][number]; show: () => void }>, animate: boolean) {
        const metric = this.metric;
        const height = config.height ?? 200;
        const next = metricGeometry(metric.values, width, height);
        const svg = this.$refs.svg;
        svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
        svg.setAttribute('width', String(width));
        svg.setAttribute('height', String(height));

        // Ticks and gridlines: a fresh group, so it fades in with the new scale.
        const tick = new Intl.NumberFormat(config.locale, { notation: 'compact', maximumFractionDigits: 1, ...(metric.format?.style === 'currency' ? { style: 'currency', currency: metric.format.currency } : null) });
        const ticks = svgNode('g', { class: 'nx-metric-chart-ticks' });
        next.ticks.forEach((t, i) => {
          ticks.append(
            svgNode('line', { class: i === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline', x1: 48, x2: width - 12, y1: t.y, y2: t.y }),
            svgNode('text', { class: 'nx-chart-tick', x: 40, y: t.y, dy: '0.32em', 'text-anchor': 'end' }, tick.format(t.value)),
          );
        });
        // The ref'd <g> stays; its content is swapped (replacing a ref'd node would drop the ref).
        this.$refs.ticks.replaceChildren(ticks);

        const labels = svgNode('g', {});
        const every = Math.max(1, Math.ceil(config.labels.length / Math.max(2, Math.floor(width / 84))));
        config.labels.forEach((label, i) => {
          if (i % every !== 0 && i !== config.labels.length - 1) return;
          const anchor = i === 0 ? 'start' : i === config.labels.length - 1 ? 'end' : 'middle';
          labels.append(svgNode('text', { class: 'nx-chart-tick', x: next.points[i]?.[0] ?? 0, y: height - 6, 'text-anchor': anchor }, label));
        });
        this.$refs.labels.replaceChildren(...Array.from(labels.childNodes));

        const line = this.$refs.line as unknown as SVGPathElement;
        const area = this.$refs.area as unknown as SVGPathElement;
        if (animate && geo) {
          morphPath(line, next.line, { from: geo.line });
          morphPath(area, next.area, { from: geo.area, fallback: 'fade' });
        } else {
          line.setAttribute('d', next.line);
          area.setAttribute('d', next.area);
        }
        const end = next.points[next.points.length - 1];
        if (end) {
          this.$refs.end.setAttribute('cx', String(end[0]));
          this.$refs.end.setAttribute('cy', String(end[1]));
        }
        this.$refs.cross.setAttribute('y1', '12');
        this.$refs.cross.setAttribute('y2', String(next.baseline));
        geo = next;
        this.$refs.plot.setAttribute('aria-label', `${metric.label}. ${this.$refs.plot.dataset.hint ?? ''}`);
        this.show();
      },

      summary(this: Self<{ metric: MetricConfig['metrics'][number] }>) {
        const metric = this.metric;
        renderNumber(this.$refs.value, metric.value ?? metric.values[metric.values.length - 1] ?? 0, config.locale, metric.format ?? undefined);
        paintDelta(this.$refs.delta, metric.delta, metric.invertDelta, config.locale);
      },

      move(this: { hover: number | null }, event: PointerEvent) {
        if (!geo) return;
        const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
        this.hover = nearestIndex(
          geo.points.map((p) => p[0]),
          event.clientX - rect.left,
        );
      },

      key(this: { hover: number | null }, event: KeyboardEvent) {
        const next = stepIndex(event, this.hover, config.labels.length);
        if (next === undefined) return;
        event.preventDefault();
        this.hover = next;
      },

      show(this: Self<{ hover: number | null; current: number; metric: MetricConfig['metrics'][number] }>) {
        const tip = this.$refs.tip;
        const point = this.$refs.point;
        const cross = this.$refs.cross;
        const at = this.hover;
        const p = at === null || !geo ? undefined : geo.points[at];
        cross.toggleAttribute('data-active', !!p);
        point.toggleAttribute('data-active', !!p);
        if (!p || at === null) {
          tip.removeAttribute('data-open');
          return;
        }
        cross.setAttribute('x1', String(p[0]));
        cross.setAttribute('x2', String(p[0]));
        point.setAttribute('cx', String(p[0]));
        point.setAttribute('cy', String(p[1]));
        const metric = this.metric;
        fillTip(tip, config.labels[at] ?? '', [{ name: metric.label, value: new Intl.NumberFormat(config.locale, metric.format ?? undefined).format(metric.values[at] ?? 0), color: seriesColor(this.current) }]);
        tip.setAttribute('data-open', '');
        placeChartTip(this.$refs.plot, point, tip);
      },
    };
  });

  /* ---- Currency converter -----------------------------------------------------------------------
   * x-modelable="model": wire:model on the root binds { amount, from, to }. Something else coming in
   * (a property that does not exist yet) is answered with the converter's current state.
   */
  Alpine.data('nxCurrencyConverter', (config: { rates: CurrencyRates; amount: number; from: string; to: string; locale?: string; rate?: string }) => {
    const plain = (n: number) => new Intl.NumberFormat(config.locale, { maximumFractionDigits: 2 }).format(n);
    type State = { amount: number; from: string; to: string };
    const valid = (v: unknown): v is State => !!v && typeof v === 'object' && typeof (v as State).from === 'string' && typeof (v as State).to === 'string' && Number.isFinite(Number((v as State).amount));

    return {
      amount: config.amount,
      from: config.from,
      to: config.to,
      model: { amount: config.amount, from: config.from, to: config.to } as unknown,
      text: plain(config.amount),
      turns: 0,

      init(this: Self<State & { model: unknown; text: string; paint: () => void; publish: () => void }>) {
        this.$watch('text', (text: string) => {
          const amount = parseAmount(text, config.locale);
          const next = Number.isNaN(amount) ? 0 : amount;
          if (next !== this.amount) this.amount = next;
        });
        this.$watch('amount', () => {
          const typed = parseAmount(this.text, config.locale);
          if ((Number.isNaN(typed) ? 0 : typed) !== this.amount) this.text = plain(this.amount);
          this.publish();
          this.paint();
        });
        this.$watch('from', () => {
          this.publish();
          this.paint();
        });
        this.$watch('to', () => {
          this.publish();
          this.paint();
        });
        this.$watch('model', (next: unknown) => {
          if (!valid(next)) return this.publish();
          if (Number(next.amount) !== this.amount) this.amount = Number(next.amount);
          if (next.from !== this.from && next.from in config.rates) this.from = next.from;
          if (next.to !== this.to && next.to in config.rates) this.to = next.to;
        });
        this.paint();
        reveal(this.$root, { once: true });
      },

      publish(this: State & { model: unknown }) {
        const next = { amount: this.amount, from: this.from, to: this.to };
        if (JSON.stringify(next) !== JSON.stringify(this.model)) this.model = next;
      },

      get result(): number {
        const { amount, from, to } = this as unknown as State;
        const result = convertCurrency(amount, from, to, config.rates);
        return Number.isFinite(result) ? result : 0;
      },

      swap(this: Self<State & { text: string; turns: number; result: number }>) {
        const amount = Math.round(this.result * 100) / 100;
        const from = this.from;
        this.from = this.to;
        this.to = from;
        this.amount = amount;
        this.text = plain(amount);
        this.turns += 1;
        this.$nextTick(() => crossSwap(this.$refs.from, this.$refs.to));
      },

      paint(this: Self<State & { result: number }>) {
        renderNumber(this.$refs.result, this.result, config.locale, { style: 'currency', currency: this.to });
        const rate = exchangeRate(this.from, this.to, config.rates);
        const shown = Number.isFinite(rate) ? new Intl.NumberFormat(config.locale, { maximumSignificantDigits: 6 }).format(rate) : '—';
        this.$refs.rate.textContent = (config.rate ?? '1 :from = :rate :to').replace(':from', this.from).replace(':rate', shown).replace(':to', this.to);
      },
    };
  });

  /* ---- Workspace shell ---------------------------------------------------------------------------
   * x-modelable="active": wire:model on the root binds the current item's id.
   */
  Alpine.data('nxWorkspaceShell', (active = '', collapsed = false) => {
    let nav: ReturnType<typeof indicator> | null = null;

    return {
      active,
      collapsed,

      init(this: Self<{ active: string; collapsed: boolean; move: () => void }>) {
        nav = indicator(this.$refs.nav);
        this.move();
        this.$watch('active', () => this.move());
        this.$watch('collapsed', () => this.$nextTick(() => nav?.refresh()));
      },

      destroy() {
        nav?.destroy();
      },

      move(this: Self<{ active: string }>) {
        nav?.update(this.$refs.nav.querySelector(`[data-value="${CSS.escape(String(this.active ?? ''))}"]`));
      },

      select(this: Self<{ active: string }>, id: string) {
        this.active = id;
        this.$dispatch('nx-select', id);
      },
    };
  });

  /* ---- Analytics card ------------------------------------------------------------------------------ */
  Alpine.data('nxAnalyticsCard', (config: { periods: Period[]; active: string; locale?: string; format?: Intl.NumberFormatOptions | null; invertDelta?: boolean; title?: string }) => {
    const fmt = new Intl.NumberFormat(config.locale, config.format ?? undefined);

    return {
      current: config.active,
      hover: null as number | null,

      init(this: Self<{ current: string; hover: number | null; summary: () => void; show: () => void }>) {
        reveal(this.$root, { once: true });
        this.$watch('current', () => {
          this.hover = null;
          this.summary();
        });
        this.$watch('hover', () => this.show());
      },

      get period(): Period {
        const id = (this as unknown as { current: string }).current;
        return config.periods.find((p) => p.id === id) ?? config.periods[0]!;
      },

      slotStyle(this: { period: Period }, i: number) {
        const values = this.period.values;
        const max = Math.max(0, ...values) || 1;
        return `--nx-i: ${i}; --nx-v: ${i < values.length ? Math.max(0.02, (values[i] ?? 0) / max) : 0}`;
      },

      nameOf(this: { period: Period }, i: number) {
        return this.period.labels?.[i] ?? String(i + 1);
      },

      summary(this: Self<{ period: Period }>) {
        const period = this.period;
        renderNumber(this.$refs.value, period.value, config.locale, config.format ?? undefined);
        paintDelta(this.$refs.delta, period.delta, config.invertDelta, config.locale);
      },

      pick(this: { hover: number | null; period: Period }, event: PointerEvent) {
        const slot = (event.target as HTMLElement).closest<HTMLElement>('[data-index]');
        if (slot && Number(slot.dataset.index) < this.period.values.length) this.hover = Number(slot.dataset.index);
      },

      key(this: { hover: number | null; period: Period }, event: KeyboardEvent) {
        const next = stepIndex(event, this.hover, this.period.values.length);
        if (next === undefined) return;
        event.preventDefault();
        this.hover = next;
      },

      show(this: Self<{ hover: number | null; period: Period; nameOf: (i: number) => string }>) {
        const tip = this.$refs.tip;
        for (const slot of Array.from(this.$refs.bars.children)) slot.toggleAttribute('data-active', Number((slot as HTMLElement).dataset.index) === this.hover);
        if (this.hover === null) {
          tip.removeAttribute('data-open');
          return;
        }
        const bar = this.$refs.bars.querySelector(`[data-index="${this.hover}"] .nx-analytics-card-bar`);
        fillTip(tip, this.nameOf(this.hover), [{ name: config.title ?? '', value: fmt.format(this.period.values[this.hover] ?? 0) }]);
        tip.setAttribute('data-open', '');
        if (bar) placeChartTip(this.$refs.frame, bar, tip);
      },
    };
  });

  /* ---- Dot matrix chart: server-rendered dots; this adds the column highlight and tooltip -------- */
  Alpine.data('nxDotMatrix', (config: { labels: string[]; series: Array<{ name: string; values: number[] }>; locale?: string; format?: Intl.NumberFormatOptions | null }) => {
    const fmt = new Intl.NumberFormat(config.locale, config.format ?? undefined);

    return {
      active: null as number | null,

      init(this: Self<{ active: number | null; show: () => void }>) {
        reveal(this.$root, { once: true });
        this.$watch('active', () => this.show());
      },

      pick(this: { active: number | null }, event: PointerEvent) {
        const col = (event.target as HTMLElement).closest<HTMLElement>('[data-index]');
        if (col) this.active = Number(col.dataset.index);
      },

      key(this: { active: number | null }, event: KeyboardEvent) {
        const next = stepIndex(event, this.active, config.labels.length);
        if (next === undefined) return;
        event.preventDefault();
        this.active = next;
      },

      show(this: Self<{ active: number | null }>) {
        const tip = this.$refs.tip;
        for (const col of Array.from(this.$refs.plot.children)) col.toggleAttribute('data-active', Number((col as HTMLElement).dataset.index) === this.active);
        if (this.active === null) {
          tip.removeAttribute('data-open');
          return;
        }
        const at = this.active;
        fillTip(
          tip,
          config.labels[at] ?? '',
          config.series.map((s, i) => ({ name: s.name, value: fmt.format(s.values[at] ?? 0), color: seriesColor(i) })),
        );
        tip.setAttribute('data-open', '');
        const dots = this.$refs.plot.querySelector(`[data-index="${at}"] .nx-dot-matrix-dots`);
        if (dots) placeChartTip(this.$refs.frame, dots, tip);
      },
    };
  });

  /* ---- Branch connector and curved timeline: core behaviours on the server's markup ------------- */
  Alpine.data('nxBranchConnector', () => {
    let stop = () => {};
    return {
      init(this: Self<object>) {
        stop = connect(this.$root);
        reveal(this.$root, { once: true });
      },
      destroy() {
        stop();
      },
    };
  });

  Alpine.data('nxCurvedTimeline', (amplitude?: number) => {
    let stops: Array<() => void> = [];
    return {
      init(this: Self<object>) {
        stops = [curvedTimeline(this.$root, amplitude ? { amplitude } : {}), ...Array.from(this.$root.querySelectorAll('[data-nx-curve-item]'), (item) => reveal(item, { once: true }))];
      },
      destroy() {
        stops.forEach((stop) => stop());
      },
    };
  });
}
