/**
 * Alpine parts for the AI agent blocks. The Blade components render what the
 * server knows; these hold the live state around it. One pattern throughout:
 * "attributes in, state follows" — a component reads data-* attributes on its
 * root (data-active, data-steps, data-text, data-status, data-lines…) and
 * watches them, so a Livewire re-render (morph) or an outer x-bind can drive
 * it without reaching into its scope. The logic (line diff, JSON rows,
 * citations, tokenizer, stick-to-bottom) is the core's, shared with React.
 */
import {
  type DiffLine,
  type DiffRow,
  type JsonRow,
  type LogLevel,
  type StickController,
  type StreamRenderer,
  type StreamSource,
  copyText,
  diffLines,
  diffRows,
  diffStats,
  domainOf,
  formatElapsed,
  jsonAt,
  jsonContainers,
  jsonOpenToDepth,
  jsonRows,
  jsonSearch,
  logLevelOf,
  markParts,
  paintCode,
  place,
  prettyJson,
  simulateStream,
  stickToBottom,
  streamRenderer,
} from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };

/** Watch some attributes of the root; returns the disconnect. */
function follow(root: HTMLElement, names: string[], callback: () => void): () => void {
  const observer = new MutationObserver(callback);
  observer.observe(root, { attributes: true, attributeFilter: names });
  return () => observer.disconnect();
}

function parse<T>(raw: string | null, fallback: T): T {
  if (raw === null || raw === '') return fallback;
  try {
    return JSON.parse(raw) as T;
  } catch {
    return fallback;
  }
}

const flag = (raw: string | null) => raw !== null && raw !== 'false' && raw !== '0';

/* ==== Thinking trace ========================================================= */

export interface ThinkingTraceConfig {
  active: boolean;
  startedAt?: number | null;
  duration?: number | null;
  open?: boolean | null;
  autoCollapse?: boolean;
  locale?: string;
  thinking: string;
  /** With `:time`. */
  thought: string;
}

interface ThinkingStepData {
  id: string;
  label: string;
  status?: 'pending' | 'running' | 'done' | 'error';
  detail?: string | null;
}

interface ThinkingState {
  active: boolean;
  open: boolean;
  now: number;
  start: number;
  final: number | null;
  steps: ThinkingStepData[];
  initial: string[];
  timer: ReturnType<typeof setInterval> | undefined;
  stop: (() => void) | undefined;
  sync(): void;
  tick(): void;
}

export function installAgentBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxThinkingTrace', (config: ThinkingTraceConfig) => ({
    active: config.active,
    open: config.open ?? config.active,
    now: Date.now(),
    start: config.startedAt ?? Date.now(),
    final: config.active ? null : (config.duration ?? null),
    steps: [] as ThinkingStepData[],
    initial: [] as string[],
    timer: undefined as ReturnType<typeof setInterval> | undefined,
    stop: undefined as (() => void) | undefined,

    init(this: Self<ThinkingState>) {
      this.steps = parse<ThinkingStepData[]>(this.$root.getAttribute('data-steps'), []);
      this.initial = this.steps.map((step) => step.id);
      this.tick();
      this.stop = follow(this.$root, ['data-active', 'data-steps', 'data-started-at'], () => this.sync());
    },

    destroy(this: Self<ThinkingState>) {
      clearInterval(this.timer);
      this.stop?.();
    },

    sync(this: Self<ThinkingState>) {
      this.steps = parse<ThinkingStepData[]>(this.$root.getAttribute('data-steps'), this.steps);
      const started = Number(this.$root.getAttribute('data-started-at'));
      const active = flag(this.$root.getAttribute('data-active'));
      if (active && !this.active) {
        this.start = started || Date.now();
        this.final = null;
        this.open = true;
      } else if (!active && this.active) {
        this.final = (Date.now() - this.start) / 1000;
        if (config.autoCollapse !== false) this.open = false;
      }
      this.active = active;
      this.tick();
    },

    tick(this: Self<ThinkingState>) {
      clearInterval(this.timer);
      this.now = Date.now();
      if (this.active) this.timer = setInterval(() => (this.now = Date.now()), 1000);
    },

    delay(this: ThinkingState, id: string, i: number) {
      return this.initial.includes(id) ? i : 0;
    },

    get time(): string {
      const self = this as unknown as ThinkingState;
      const seconds = self.active ? (self.now - self.start) / 1000 : (self.final ?? config.duration ?? 0);
      return formatElapsed(seconds, config.locale);
    },

    get title(): string {
      const self = this as unknown as ThinkingState & { time: string };
      return self.active ? config.thinking : config.thought.replace(':time', self.time);
    },
  }));

  /* ==== Streaming text ======================================================== */

  Alpine.data('nxStreamingText', (config: { sources: StreamSource[] | Record<string, StreamSource>; simulate?: boolean; interval?: number; cite: string }) => {
    let renderer: StreamRenderer | null = null;
    let stopSim: (() => void) | null = null;
    let stopPlace: (() => void) | null = null;
    let stopFollow: (() => void) | null = null;
    const sourceOf = (n: number): StreamSource | undefined => (Array.isArray(config.sources) ? config.sources[n - 1] : config.sources?.[String(n)]);

    return {
      streaming: false,
      cite: 0,

      init(this: Self<{ streaming: boolean; render: (text: string, streaming: boolean) => void; play: () => void }>) {
        const body = this.$refs.body as HTMLElement;
        renderer = streamRenderer(body, { citeLabel: (n) => config.cite.replace(':n', String(n)) + (sourceOf(n) ? `: ${sourceOf(n)!.title}` : '') });
        const text = this.$root.getAttribute('data-text') ?? '';
        if (config.simulate) this.play();
        else this.render(text, flag(this.$root.getAttribute('data-streaming-in')));
        stopFollow = follow(this.$root, ['data-text', 'data-streaming-in'], () => {
          if (stopSim) return;
          this.render(this.$root.getAttribute('data-text') ?? '', flag(this.$root.getAttribute('data-streaming-in')));
        });
      },

      destroy() {
        stopSim?.();
        stopPlace?.();
        stopFollow?.();
        renderer?.destroy();
      },

      render(this: Self<{ streaming: boolean }>, text: string, streaming: boolean) {
        this.streaming = streaming;
        renderer?.update(text, streaming);
        (this.$refs.body as HTMLElement).setAttribute('aria-busy', streaming ? 'true' : 'false');
      },

      /** Stream the full text out token by token (demo / replay). */
      play(this: Self<{ render: (text: string, streaming: boolean) => void; close: () => void }>) {
        stopSim?.();
        this.close();
        const full = this.$root.getAttribute('data-text') ?? '';
        (this.$refs.body as HTMLElement).querySelectorAll('.nx-stream-token, .nx-stream-cite').forEach((node) => node.remove());
        renderer?.update('', true);
        this.render('', true);
        stopSim = simulateStream(full, {
          interval: config.interval ?? 55,
          onUpdate: (text, done) => {
            this.render(text, !done);
            if (done) stopSim = null;
          },
        });
      },

      /** A click on a [n] button opens the source popover against it. */
      pick(this: Self<{ cite: number; close: () => void }>, event: MouseEvent) {
        const button = (event.target as HTMLElement).closest<HTMLButtonElement>('.nx-stream-cite');
        if (!button) return;
        const n = Number(button.dataset.cite);
        const pop = this.$refs.source as HTMLElement;
        if (this.cite === n && pop.matches(':popover-open')) {
          this.close();
          return;
        }
        this.$root.querySelectorAll('.nx-stream-cite[aria-expanded="true"]').forEach((node) => node.setAttribute('aria-expanded', 'false'));
        this.cite = n;
        const source = sourceOf(n);
        const set = (ref: string, text: string) => {
          const el = this.$refs[ref];
          if (el) {
            el.textContent = text;
            el.hidden = !text;
          }
        };
        set('domain', source ? domainOf(source) : '');
        set('title', source?.title ?? config.cite.replace(':n', String(n)));
        set('snippet', source?.snippet ?? '');
        const link = this.$refs.link as HTMLAnchorElement | undefined;
        if (link) {
          link.hidden = !source?.url;
          if (source?.url) link.href = source.url;
        }
        pop.setAttribute('aria-label', source?.title ?? '');
        button.setAttribute('aria-expanded', 'true');
        button.setAttribute('aria-controls', pop.id);
        try {
          if (!pop.matches(':popover-open')) pop.showPopover();
        } catch {
          pop.setAttribute('data-open', '');
        }
        stopPlace?.();
        stopPlace = place(button, pop, { side: 'top', align: 'center' });
      },

      close(this: Self<{ cite: number; closed: () => void }>) {
        const pop = this.$refs.source as HTMLElement | undefined;
        try {
          if (pop?.matches(':popover-open')) pop.hidePopover();
        } catch {
          /* not a popover-capable browser */
        }
        pop?.removeAttribute('data-open');
        this.closed();
      },

      /** The popover closed itself (light dismiss / Escape). */
      closed(this: Self<{ cite: number }>) {
        stopPlace?.();
        stopPlace = null;
        this.cite = 0;
        this.$root.querySelectorAll('.nx-stream-cite[aria-expanded="true"]').forEach((node) => node.setAttribute('aria-expanded', 'false'));
      },
    };
  });

  /* ==== Tool call ============================================================= */

  Alpine.data('nxToolCall', (open = false) => {
    let stop: (() => void) | null = null;
    return {
      open,
      status: 'success',
      init(this: Self<{ status: string }>) {
        this.status = this.$root.getAttribute('data-status') ?? 'success';
        stop = follow(this.$root, ['data-status'], () => (this.status = this.$root.getAttribute('data-status') ?? 'success'));
      },
      destroy() {
        stop?.();
      },
    };
  });

  /* ==== Approval card ========================================================= */

  type ApprovalState = 'pending' | 'approved' | 'denied' | 'always';

  Alpine.data('nxApprovalCard', (config: { state: ApprovalState; allowAlways: boolean; shortcuts: boolean; action?: string | null; autofocus?: boolean }) => ({
    state: config.state as ApprovalState,

    init(this: Self<{ state: ApprovalState }>) {
      if (config.autofocus) this.$nextTick(() => this.$root.focus({ preventScroll: true }));
    },

    /** wire:model may hand over null before Livewire has a value: that is pending. */
    get current(): ApprovalState {
      return (this as unknown as { state: ApprovalState | null }).state || 'pending';
    },

    get pending(): boolean {
      return (this as unknown as { current: ApprovalState }).current === 'pending';
    },

    decide(this: Self<{ state: ApprovalState; pending: boolean }>, next: ApprovalState) {
      if (!this.pending && next !== 'pending') return;
      this.state = next;
      this.$dispatch('nx-approval', { state: next });
      const wire = (this as unknown as { $wire?: Wire }).$wire;
      if (config.action && wire) wire.call(config.action, next);
      if (next !== 'pending') requestAnimationFrame(() => this.$root.focus({ preventScroll: true }));
    },

    key(this: Self<{ pending: boolean; decide: (next: ApprovalState) => void }>, event: KeyboardEvent) {
      if (!config.shortcuts || !this.pending || event.defaultPrevented || event.metaKey || event.ctrlKey || event.altKey) return;
      if ((event.target as HTMLElement).closest('input, textarea, select, [contenteditable="true"]')) return;
      const key = event.key.toLowerCase();
      const next: ApprovalState | null = key === 'y' ? 'approved' : key === 'n' ? 'denied' : key === 'a' && config.allowAlways ? 'always' : null;
      if (!next) return;
      event.preventDefault();
      this.decide(next);
    },
  }));

  /* ==== Code block ============================================================ */

  Alpine.data('nxCodeBlock', (config: { language: string; wrap: boolean }) => ({
    wrap: config.wrap,
    init(this: Self<object>) {
      paintCode(this.$root, config.language);
    },
  }));

  /* ==== Diff viewer =========================================================== */

  interface DiffState {
    mode: 'unified' | 'split';
    expanded: number[];
    lines: DiffLine[];
  }

  Alpine.data('nxDiffViewer', (config: { oldText: string; newText: string; view: 'unified' | 'split'; context: number; expand: string }) => ({
    mode: config.view,
    expanded: [] as number[],
    lines: diffLines(config.oldText, config.newText),

    get rows(): DiffRow[] {
      const self = this as unknown as DiffState;
      return diffRows(self.lines, { context: config.context, expanded: self.expanded, split: self.mode === 'split' });
    },

    get stats(): { added: number; removed: number } {
      return diffStats((this as unknown as DiffState).lines);
    },

    expandLabel(count: number) {
      return config.expand.replace(':count', String(count));
    },

    sign(line: DiffLine | null) {
      return line ? (line.kind === 'add' ? '+' : line.kind === 'del' ? '-' : ' ') : '';
    },
  }));

  /* ==== JSON viewer =========================================================== */

  interface JsonState {
    open: Record<string, boolean>;
    query: string;
    copied: string | null;
    hits: Set<string>;
  }

  Alpine.data('nxJsonViewer', (config: { data: unknown; depth: number; items: string; keys: string; matches: string }) => {
    const initial: Record<string, boolean> = {};
    for (const path of jsonOpenToDepth(config.data, config.depth - 1)) initial[path] = true;
    let timer: ReturnType<typeof setTimeout> | undefined;
    return {
      open: initial,
      query: '',
      copied: null as string | null,
      hits: new Set<string>(),

      init(this: Self<JsonState>) {
        // A search opens the way to every hit.
        this.$watch('query', (query: never) => {
          const found = jsonSearch(config.data, String(query ?? ''));
          this.hits = found.hits;
          if (found.open.size) {
            const next = { ...this.open };
            for (const path of found.open) next[path] = true;
            this.open = next;
          }
        });
      },

      get rows(): JsonRow[] {
        const self = this as unknown as JsonState;
        return jsonRows(config.data, (path) => !!self.open[path]);
      },

      get found(): string {
        return config.matches.replace(':count', String((this as unknown as { hits: Set<string> }).hits.size));
      },

      toggle(this: JsonState, path: string) {
        this.open = { ...this.open, [path]: !this.open[path] };
      },

      expandAll(this: JsonState) {
        const next: Record<string, boolean> = {};
        for (const path of jsonContainers(config.data)) next[path] = true;
        this.open = next;
      },

      collapseAll(this: JsonState) {
        this.open = {};
      },

      parts(this: JsonState, text: string) {
        return markParts(text, this.query);
      },

      keyText(row: JsonRow) {
        return typeof row.key === 'number' ? String(row.key) : JSON.stringify(row.key);
      },

      brace(row: JsonRow, closing: boolean) {
        return row.type === 'array' ? (closing ? ']' : '[') : closing ? '}' : '{';
      },

      summary(row: JsonRow) {
        return (row.type === 'array' ? config.items : config.keys).replace(':count', String(row.count));
      },

      async copy(this: JsonState, row: JsonRow, what: 'path' | 'value') {
        const value = jsonAt(config.data, row.segments);
        const text = what === 'path' ? row.id : typeof value === 'string' ? value : prettyJson(value);
        if (!(await copyText(text))) return;
        this.copied = `${row.id}:${what}`;
        clearTimeout(timer);
        timer = setTimeout(() => (this.copied = null), 1400);
      },
    };
  });

  /* ==== Terminal ============================================================== */

  interface TermLine {
    id: string;
    text: string;
    level: LogLevel;
    time?: string;
    fresh?: boolean;
  }

  interface TermState {
    lines: TermLine[];
    filter: 'all' | 'info' | 'warn' | 'error';
    pinned: boolean;
    seq: number;
  }

  const group = (level: LogLevel) => (level === 'warn' || level === 'error' ? level : 'info');
  const normalize = (raw: Partial<TermLine> & { text: string }, i: number, fresh = false): TermLine => ({
    id: String(raw.id ?? `l${i}`),
    text: String(raw.text),
    level: raw.level ?? logLevelOf(String(raw.text)),
    time: raw.time,
    fresh,
  });

  Alpine.data('nxTerminal', (config: { filter: TermState['filter']; name?: string | null; script?: Array<Partial<TermLine> & { text: string; delay?: number }> | null }) => {
    let stick: StickController | null = null;
    let stop: (() => void) | null = null;
    let timers: Array<ReturnType<typeof setTimeout>> = [];
    return {
      lines: [] as TermLine[],
      filter: config.filter,
      pinned: true,
      seq: 0,

      init(this: Self<TermState & { push: (line: Partial<TermLine> & { text: string }) => void; play: () => void }>) {
        this.lines = parse<Array<Partial<TermLine> & { text: string }>>(this.$root.getAttribute('data-lines'), []).map((line, i) => normalize(line, i));
        this.$nextTick(() => {
          const screen = this.$refs.screen as HTMLElement | undefined;
          if (screen) stick = stickToBottom(screen, { onChange: (pinned) => (this.pinned = pinned) });
        });
        stop = follow(this.$root, ['data-lines'], () => {
          const known = new Set(this.lines.map((line) => line.id));
          this.lines = parse<Array<Partial<TermLine> & { text: string }>>(this.$root.getAttribute('data-lines'), []).map((line, i) => normalize(line, i, !known.has(String(line.id ?? `l${i}`))));
        });
        if (config.script?.length) this.play();
      },

      destroy() {
        stick?.destroy();
        stop?.();
        timers.forEach(clearTimeout);
      },

      /** Append a line from JS: `$dispatch('nx-terminal-push', { to: 'build', text, level })`. */
      push(this: TermState, line: Partial<TermLine> & { text: string }) {
        this.seq += 1;
        this.lines = [...this.lines, normalize({ ...line, id: line.id ?? `push-${this.seq}` }, this.lines.length, true)];
      },

      fromEvent(this: TermState & { push: (line: Partial<TermLine> & { text: string }) => void; play: () => void; clear: () => void }, detail: { to?: string; text?: string; level?: LogLevel; time?: string; action?: 'clear' | 'replay' }) {
        if (detail?.to && detail.to !== config.name) return;
        if (detail.action === 'clear') this.clear();
        else if (detail.action === 'replay') this.play();
        else if (detail.text !== undefined) this.push({ text: detail.text, level: detail.level, time: detail.time });
      },

      /** Play the scripted lines out over time (demo / replay). */
      play(this: TermState & { push: (line: Partial<TermLine> & { text: string }) => void }) {
        timers.forEach(clearTimeout);
        timers = [];
        this.lines = [];
        let at = 0;
        for (const line of config.script ?? []) {
          at += line.delay ?? 420;
          timers.push(setTimeout(() => this.push(line), at));
        }
      },

      clear(this: TermState) {
        this.lines = [];
      },

      jump() {
        stick?.pin(true);
      },

      count(this: TermState, level: TermState['filter']) {
        return level === 'all' ? this.lines.length : this.lines.filter((line) => group(line.level) === level).length;
      },

      get shown(): TermLine[] {
        const self = this as unknown as TermState;
        return self.filter === 'all' ? self.lines : self.lines.filter((line) => group(line.level) === self.filter);
      },
    };
  });
}
