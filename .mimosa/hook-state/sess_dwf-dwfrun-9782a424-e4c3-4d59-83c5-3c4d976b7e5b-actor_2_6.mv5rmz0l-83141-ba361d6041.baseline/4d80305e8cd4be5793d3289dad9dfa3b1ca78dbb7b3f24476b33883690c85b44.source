/**
 * AI agent blocks: framework-agnostic logic and behaviour.
 *
 * Pure helpers (tested in test/agent.test.ts): a Myers line diff and the rows
 * a diff viewer draws from it, a flat JSON tree model with JS-style paths and
 * search, citation parsing for streamed answers, a tiny syntax-light tokenizer,
 * line-range parsing and duration labels. DOM behaviours: stick-to-bottom for
 * logs, the code painter the Blade code block uses, an incremental stream
 * renderer for Alpine, and a token simulator for demos. React and Alpine both
 * render the markup described in css/blocks/agent.css on top of these.
 */
import { type Cleanup, isBrowser, prefersReducedMotion } from '../env';
import { splitText, textDirection } from '../text';

/* ==== Line diff (Myers, O(ND)) ============================================= */

export type DiffKind = 'same' | 'add' | 'del';

export interface DiffLine {
  kind: DiffKind;
  text: string;
  /** 1-based line number in the old text (same + del). */
  oldNo: number | null;
  /** 1-based line number in the new text (same + add). */
  newNo: number | null;
}

/** Split text into lines; a final newline does not add an empty last line. */
export function toLines(text: string | readonly string[]): string[] {
  if (Array.isArray(text)) return [...text];
  const value = String(text).replace(/\r\n?/g, '\n');
  if (value === '') return [];
  const lines = value.split('\n');
  if (lines[lines.length - 1] === '') lines.pop();
  return lines;
}

/**
 * The shortest edit script between two sequences (Myers 1986), as a list of
 * operations in order. Common head and tail are trimmed first, so typical
 * edits of long files stay cheap.
 */
function myers(a: readonly string[], b: readonly string[]): DiffKind[] {
  let head = 0;
  while (head < a.length && head < b.length && a[head] === b[head]) head++;
  let tail = 0;
  while (tail < a.length - head && tail < b.length - head && a[a.length - 1 - tail] === b[b.length - 1 - tail]) tail++;

  const A = a.slice(head, a.length - tail);
  const B = b.slice(head, b.length - tail);
  const n = A.length;
  const m = B.length;
  const middle: DiffKind[] = [];

  if (n === 0) for (let i = 0; i < m; i++) middle.push('add');
  else if (m === 0) for (let i = 0; i < n; i++) middle.push('del');
  else {
    const max = n + m;
    const off = max + 1;
    let v: Int32Array = new Int32Array(2 * max + 3);
    const trace: Int32Array[] = [];
    let found = -1;
    outer: for (let d = 0; d <= max; d++) {
      trace.push(v.slice());
      for (let k = -d; k <= d; k += 2) {
        let x = k === -d || (k !== d && v[k - 1 + off]! < v[k + 1 + off]!) ? v[k + 1 + off]! : v[k - 1 + off]! + 1;
        let y = x - k;
        while (x < n && y < m && A[x] === B[y]) {
          x++;
          y++;
        }
        v[k + off] = x;
        if (x >= n && y >= m) {
          found = d;
          break outer;
        }
      }
    }
    // Walk the trace back from the end, emitting operations in reverse.
    let x = n;
    let y = m;
    const reversed: DiffKind[] = [];
    for (let d = found; d >= 0; d--) {
      v = trace[d]!;
      const k = x - y;
      const prevK = k === -d || (k !== d && v[k - 1 + off]! < v[k + 1 + off]!) ? k + 1 : k - 1;
      const prevX = v[prevK + off]!;
      const prevY = prevX - prevK;
      while (x > prevX && y > prevY) {
        reversed.push('same');
        x--;
        y--;
      }
      if (d > 0) reversed.push(x === prevX ? 'add' : 'del');
      x = prevX;
      y = prevY;
    }
    middle.push(...reversed.reverse());
  }

  return [...Array<DiffKind>(head).fill('same'), ...middle, ...Array<DiffKind>(tail).fill('same')];
}

/**
 * Diff two texts line by line. Inside each changed run the removed lines come
 * before the added ones, the way people read a patch.
 */
export function diffLines(before: string | readonly string[], after: string | readonly string[]): DiffLine[] {
  const a = toLines(before);
  const b = toLines(after);
  const ops = myers(a, b);
  const out: DiffLine[] = [];
  let i = 0;
  let j = 0;
  let dels: DiffLine[] = [];
  let adds: DiffLine[] = [];
  const flush = () => {
    out.push(...dels, ...adds);
    dels = [];
    adds = [];
  };
  for (const op of ops) {
    if (op === 'same') {
      flush();
      out.push({ kind: 'same', text: a[i]!, oldNo: i + 1, newNo: j + 1 });
      i++;
      j++;
    } else if (op === 'del') {
      dels.push({ kind: 'del', text: a[i]!, oldNo: i + 1, newNo: null });
      i++;
    } else {
      adds.push({ kind: 'add', text: b[j]!, oldNo: null, newNo: j + 1 });
      j++;
    }
  }
  flush();
  return out;
}

export function diffStats(lines: readonly DiffLine[]): { added: number; removed: number } {
  let added = 0;
  let removed = 0;
  for (const line of lines) {
    if (line.kind === 'add') added++;
    else if (line.kind === 'del') removed++;
  }
  return { added, removed };
}

export type DiffRow =
  | { type: 'gap'; key: string; gap: number; count: number }
  | { type: 'line'; key: string; line: DiffLine }
  | { type: 'pair'; key: string; left: DiffLine | null; right: DiffLine | null };

export interface DiffRowOptions {
  /** Unchanged lines kept around every change. */
  context?: number;
  /** Indexes of gaps the reader has opened. */
  expanded?: ReadonlySet<number> | readonly number[];
  /** Side-by-side rows (old left, new right) instead of one column. */
  split?: boolean;
}

const lineKey = (line: DiffLine) => `${line.kind}:${line.oldNo ?? ''}:${line.newNo ?? ''}`;

/**
 * The rows a diff viewer draws: unchanged runs longer than the context fold
 * into numbered gaps ("expand 14 lines"), and in split view removed and added
 * lines pair up side by side. Gap numbers are stable for a given diff.
 */
export function diffRows(lines: readonly DiffLine[], { context = 3, expanded = [], split = false }: DiffRowOptions = {}): DiffRow[] {
  const open = expanded instanceof Set ? expanded : new Set(expanded as readonly number[]);
  const visible: Array<DiffLine | { gap: number; count: number }> = [];
  const changed = lines.map((line) => line.kind !== 'same');
  const hasChange = changed.some(Boolean);
  let gap = 0;
  let i = 0;
  while (i < lines.length) {
    if (changed[i]) {
      visible.push(lines[i]!);
      i++;
      continue;
    }
    let end = i;
    while (end < lines.length && !changed[end]) end++;
    // A run of unchanged lines [i, end): keep `context` next to each change.
    const keepHead = i === 0 ? 0 : context;
    const keepTail = end === lines.length ? 0 : context;
    const hidden = hasChange ? end - i - keepHead - keepTail : 0;
    if (hidden > 1) {
      const index = gap++;
      if (open.has(index)) for (let x = i; x < end; x++) visible.push(lines[x]!);
      else {
        for (let x = i; x < i + keepHead; x++) visible.push(lines[x]!);
        visible.push({ gap: index, count: hidden });
        for (let x = end - keepTail; x < end; x++) visible.push(lines[x]!);
      }
    } else for (let x = i; x < end; x++) visible.push(lines[x]!);
    i = end;
  }

  const rows: DiffRow[] = [];
  if (!split) {
    for (const item of visible) {
      if ('gap' in item) rows.push({ type: 'gap', key: `gap-${item.gap}`, gap: item.gap, count: item.count });
      else rows.push({ type: 'line', key: lineKey(item), line: item });
    }
    return rows;
  }

  let dels: DiffLine[] = [];
  let adds: DiffLine[] = [];
  const flush = () => {
    const count = Math.max(dels.length, adds.length);
    for (let x = 0; x < count; x++) {
      const left = dels[x] ?? null;
      const right = adds[x] ?? null;
      rows.push({ type: 'pair', key: `${left ? lineKey(left) : '-'}|${right ? lineKey(right) : '-'}`, left, right });
    }
    dels = [];
    adds = [];
  };
  for (const item of visible) {
    if ('gap' in item) {
      flush();
      rows.push({ type: 'gap', key: `gap-${item.gap}`, gap: item.gap, count: item.count });
    } else if (item.kind === 'del') {
      if (adds.length) flush();
      dels.push(item);
    } else if (item.kind === 'add') adds.push(item);
    else {
      flush();
      rows.push({ type: 'pair', key: lineKey(item), left: item, right: item });
    }
  }
  flush();
  return rows;
}

/* ==== JSON tree ============================================================= */

export type JsonType = 'object' | 'array' | 'string' | 'number' | 'boolean' | 'null';
export type JsonSegment = string | number;

export function jsonType(value: unknown): JsonType {
  if (value === null || value === undefined) return 'null';
  if (Array.isArray(value)) return 'array';
  const type = typeof value;
  if (type === 'string' || type === 'number' || type === 'boolean') return type;
  if (type === 'bigint') return 'number';
  return 'object';
}

const IDENTIFIER = /^[A-Za-z_$][\w$]*$/;

/** A JS-style path from the root: `$.user.name`, `$.items[0]`, `$["first name"]`. */
export function jsonPath(segments: readonly JsonSegment[], root = '$'): string {
  let path = root;
  for (const segment of segments) {
    if (typeof segment === 'number') path += `[${segment}]`;
    else if (IDENTIFIER.test(segment)) path += `.${segment}`;
    else path += `[${JSON.stringify(segment)}]`;
  }
  return path;
}

/** The value at a path of segments (undefined when the path leads nowhere). */
export function jsonAt(value: unknown, segments: readonly JsonSegment[]): unknown {
  let node: unknown = value;
  for (const segment of segments) {
    if (node === null || typeof node !== 'object') return undefined;
    node = (node as Record<string | number, unknown>)[segment];
  }
  return node;
}

/** A leaf as it is written in JSON: strings quoted, the rest literal. */
export function jsonLiteral(value: unknown): string {
  const type = jsonType(value);
  if (type === 'string') return JSON.stringify(value);
  if (type === 'null') return 'null';
  return String(value);
}

export interface JsonRow {
  /** The path string: stable id, copy target and expansion key. */
  id: string;
  segments: JsonSegment[];
  depth: number;
  /** The key in its parent (null for the root, a number inside arrays). */
  key: JsonSegment | null;
  type: JsonType;
  /** Containers: how many children. */
  count: number;
  /** Containers: shown open (children follow, then a `close` row). */
  open: boolean;
  /** A closing bracket row of an open container. */
  close: boolean;
  /** Leaves: the literal (`"text"`, `42`, `true`, `null`). */
  literal: string;
  /** The last child of its parent (no trailing comma). */
  last: boolean;
}

function entriesOf(value: unknown): Array<[JsonSegment, unknown]> {
  if (Array.isArray(value)) return value.map((item, i) => [i, item]);
  if (value && typeof value === 'object') return Object.entries(value as Record<string, unknown>);
  return [];
}

/**
 * The tree as flat rows, depth first, honouring which containers are open.
 * Flat rows render the same with a plain loop in React and Alpine (x-for).
 */
export function jsonRows(value: unknown, isOpen: (id: string, depth: number) => boolean): JsonRow[] {
  const rows: JsonRow[] = [];
  const walk = (node: unknown, segments: JsonSegment[], key: JsonSegment | null, last: boolean) => {
    const type = jsonType(node);
    const id = jsonPath(segments);
    const depth = segments.length;
    if (type === 'object' || type === 'array') {
      const entries = entriesOf(node);
      const open = entries.length > 0 && isOpen(id, depth);
      rows.push({ id, segments, depth, key, type, count: entries.length, open, close: false, literal: '', last });
      if (open) {
        entries.forEach(([childKey, child], i) => walk(child, [...segments, childKey], childKey, i === entries.length - 1));
        rows.push({ id: `${id}#close`, segments, depth, key: null, type, count: entries.length, open: true, close: true, literal: '', last });
      }
    } else rows.push({ id, segments, depth, key, type, count: 0, open: false, close: false, literal: jsonLiteral(node), last });
  };
  walk(value, [], null, true);
  return rows;
}

/** Every container path in the tree (for "expand all"). */
export function jsonContainers(value: unknown): string[] {
  const out: string[] = [];
  const walk = (node: unknown, segments: JsonSegment[]) => {
    const type = jsonType(node);
    if (type !== 'object' && type !== 'array') return;
    out.push(jsonPath(segments));
    for (const [key, child] of entriesOf(node)) walk(child, [...segments, key]);
  };
  walk(value, []);
  return out;
}

/** Containers down to `depth` levels below the root (0 = only the root). */
export function jsonOpenToDepth(value: unknown, depth: number): string[] {
  const out: string[] = [];
  const walk = (node: unknown, segments: JsonSegment[]) => {
    const type = jsonType(node);
    if ((type !== 'object' && type !== 'array') || segments.length > depth) return;
    out.push(jsonPath(segments));
    for (const [key, child] of entriesOf(node)) walk(child, [...segments, key]);
  };
  walk(value, []);
  return out;
}

const fold = (text: string) => text.toLocaleLowerCase().replace(/[يى]/g, 'ی').replace(/ك/g, 'ک');

/**
 * Search: rows whose key or leaf value contains the query (case-folded), and
 * the containers that must open so every hit is visible.
 */
export function jsonSearch(value: unknown, query: string): { hits: Set<string>; open: Set<string> } {
  const hits = new Set<string>();
  const open = new Set<string>();
  const q = fold(query.trim());
  if (!q) return { hits, open };
  const walk = (node: unknown, segments: JsonSegment[], key: JsonSegment | null): boolean => {
    const type = jsonType(node);
    const id = jsonPath(segments);
    let found = key !== null && fold(String(key)).includes(q);
    if (type === 'object' || type === 'array') {
      let inside = false;
      for (const [childKey, child] of entriesOf(node)) if (walk(child, [...segments, childKey], childKey)) inside = true;
      if (inside) open.add(id);
      if (found) hits.add(id);
      return found || inside;
    }
    if (fold(jsonLiteral(node)).includes(q)) found = true;
    if (found) hits.add(id);
    return found;
  };
  walk(value, [], null);
  return { hits, open };
}

/** Pieces of `text` with the (case-folded) matches of `query` marked, for <mark>. */
export function markParts(text: string, query: string): Array<{ text: string; hit: boolean }> {
  const q = fold(query.trim());
  if (!q) return [{ text, hit: false }];
  const hay = fold(text);
  // Folding keeps lengths for the letters we fold, so indexes line up.
  if (hay.length !== text.length) return [{ text, hit: hay.includes(q) }];
  const parts: Array<{ text: string; hit: boolean }> = [];
  let from = 0;
  for (let at = hay.indexOf(q); at !== -1; at = hay.indexOf(q, at + q.length)) {
    if (at > from) parts.push({ text: text.slice(from, at), hit: false });
    parts.push({ text: text.slice(at, at + q.length), hit: true });
    from = at + q.length;
  }
  if (from < text.length) parts.push({ text: text.slice(from), hit: false });
  return parts.length ? parts : [{ text, hit: false }];
}

/* ==== Streaming text + citations ============================================ */

export type StreamPart = { type: 'text'; text: string } | { type: 'cite'; n: number; text: string };

/**
 * Text with inline citation markers `[1]` as parts. While streaming, a marker
 * that has not finished arriving (`… [1`) is held back instead of flashing.
 */
export function streamParts(text: string, streaming = false): StreamPart[] {
  let value = text;
  if (streaming) value = value.replace(/\[\d*$/, '');
  const parts: StreamPart[] = [];
  const pattern = /\[(\d{1,3})\]/g;
  let from = 0;
  for (let match = pattern.exec(value); match; match = pattern.exec(value)) {
    if (match.index > from) parts.push({ type: 'text', text: value.slice(from, match.index) });
    parts.push({ type: 'cite', n: Number(match[1]), text: match[0] });
    from = match.index + match[0].length;
  }
  if (from < value.length) parts.push({ type: 'text', text: value.slice(from) });
  return parts;
}

/**
 * The animatable tokens of a text part: words (never letters — joined scripts
 * like Persian must keep their joins), with runs written the other way kept
 * together by the core splitText rules.
 */
export function streamTokens(text: string): string[] {
  return splitText(text, 'word');
}

export interface StreamSource {
  title: string;
  url?: string;
  /** Shown under the title; derived from `url` when omitted. */
  domain?: string;
  snippet?: string;
}

/** `https://www.example.com/a` → `example.com`. */
export function domainOf(source: Pick<StreamSource, 'url' | 'domain'>): string {
  if (source.domain) return source.domain;
  if (!source.url) return '';
  const match = /^[a-z]+:\/\/(?:www\.)?([^/?#:]+)/i.exec(source.url);
  return match ? match[1]! : source.url;
}

export interface StreamSimulation {
  /** Token pace in ms (a little jitter is added). */
  interval?: number;
  onUpdate: (text: string, done: boolean) => void;
}

/**
 * Play `text` out token by token, the way a model streams it. Under reduced
 * motion it still streams (that is information), only without the fade.
 */
export function simulateStream(text: string, { interval = 55, onUpdate }: StreamSimulation): Cleanup {
  // Tokens: words, with a citation marker always arriving whole.
  const tokens = text.match(/\[\d+\]|\s+|[^\s[]+|\[/g) ?? [];
  let i = 0;
  let shown = '';
  let timer: ReturnType<typeof setTimeout> | undefined;
  const step = () => {
    if (i >= tokens.length) {
      onUpdate(shown, true);
      return;
    }
    shown += tokens[i++]!;
    // Spaces ride along with the next word.
    while (i < tokens.length && /^\s+$/.test(tokens[i]!)) shown += tokens[i++]!;
    onUpdate(shown, i >= tokens.length);
    if (i < tokens.length) timer = setTimeout(step, interval * (0.6 + Math.random() * 0.9));
  };
  timer = setTimeout(step, interval);
  return () => clearTimeout(timer);
}

export interface StreamRenderer {
  /** Show `text`; only pieces that were not there yet are added (and fade in). */
  update(text: string, streaming: boolean): void;
  destroy: Cleanup;
}

/**
 * The incremental renderer behind the Alpine streaming text: keeps the pieces
 * already shown in place and appends new ones, so only fresh tokens animate.
 * Citation markers become buttons (data-cite="n") the caller wires up.
 */
export function streamRenderer(container: HTMLElement, { citeLabel = (n: number) => `Source ${n}` } = {}): StreamRenderer {
  let previous: string[] = [];
  const keyOf = (part: StreamPart, token: string) => (part.type === 'cite' ? `c${part.n}` : `t${token}`);
  const build = (text: string, streaming: boolean) => {
    const pieces: Array<{ key: string; part: StreamPart; token: string }> = [];
    for (const part of streamParts(text, streaming)) {
      if (part.type === 'cite') pieces.push({ key: keyOf(part, ''), part, token: part.text });
      else for (const token of streamTokens(part.text)) pieces.push({ key: keyOf(part, token), part, token });
    }
    return pieces;
  };
  const make = (piece: { part: StreamPart; token: string }, fresh: boolean): HTMLElement => {
    if (piece.part.type === 'cite') {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'nx-stream-cite';
      button.dataset.cite = String(piece.part.n);
      button.textContent = String(piece.part.n);
      button.setAttribute('aria-label', citeLabel(piece.part.n));
      if (fresh) button.dataset.fresh = '';
      return button;
    }
    const span = document.createElement('span');
    span.className = 'nx-stream-token';
    span.textContent = piece.token;
    if (fresh) span.dataset.fresh = '';
    return span;
  };
  return {
    update(text, streaming) {
      const pieces = build(text, streaming);
      // Keep the common prefix; the last kept token may have grown ("hel" → "hello").
      let same = 0;
      while (same < pieces.length && same < previous.length && pieces[same]!.key === previous[same]) same++;
      const children = Array.from(container.querySelectorAll<HTMLElement>(':scope > .nx-stream-token, :scope > .nx-stream-cite'));
      for (let x = children.length - 1; x >= same; x--) children[x]!.remove();
      const caret = container.querySelector(':scope > .nx-stream-caret');
      for (let x = same; x < pieces.length; x++) {
        // A grown last token is replaced without a second fade.
        const fresh = !(x === same && x < previous.length);
        const node = make(pieces[x]!, fresh);
        if (caret) caret.before(node);
        else container.append(node);
      }
      previous = pieces.map((piece) => piece.key);
      container.setAttribute('dir', textDirection(text));
    },
    destroy() {
      previous = [];
    },
  };
}

/* ==== Durations ============================================================ */

/** "12s", "1m 5s" — or "۱۲ ثانیه" — in the reader's language. */
export function formatElapsed(seconds: number, locale?: string): string {
  const total = Math.max(0, Math.round(seconds));
  const unit = (value: number, kind: 'second' | 'minute') => {
    try {
      return new Intl.NumberFormat(locale, { style: 'unit', unit: kind, unitDisplay: 'narrow' }).format(value);
    } catch {
      return `${value}${kind === 'second' ? 's' : 'm'}`;
    }
  };
  if (total < 60) return unit(total, 'second');
  const minutes = Math.floor(total / 60);
  const rest = total % 60;
  return rest ? `${unit(minutes, 'minute')} ${unit(rest, 'second')}` : unit(minutes, 'minute');
}

/** "1.2s" / "340ms" for tool timings. */
export function formatDuration(ms: number, locale?: string): string {
  const format = (value: number, digits: number) => new Intl.NumberFormat(locale, { maximumFractionDigits: digits }).format(value);
  if (ms < 1000) return `${format(Math.round(ms), 0)}ms`;
  return `${format(ms / 1000, 1)}s`;
}

/* ==== Tool arguments ========================================================== */

/** A one-line preview of tool arguments: `query: "weather", limit: 5`. */
export function argsPreview(args: unknown, max = 72): string {
  let text: string;
  if (args === undefined || args === null || args === '') return '';
  if (typeof args === 'string') text = args;
  else if (typeof args === 'object' && !Array.isArray(args)) {
    text = Object.entries(args as Record<string, unknown>)
      .map(([key, value]) => `${key}: ${typeof value === 'object' && value !== null ? (Array.isArray(value) ? `[${value.length}]` : '{…}') : jsonLiteral(value)}`)
      .join(', ');
  } else text = JSON.stringify(args) ?? '';
  text = text.replace(/\s+/g, ' ').trim();
  return text.length > max ? `${text.slice(0, max - 1).trimEnd()}…` : text;
}

/** Pretty JSON for the input/output panes (strings pass through untouched). */
export function prettyJson(value: unknown): string {
  if (typeof value === 'string') return value;
  try {
    return JSON.stringify(value, null, 2) ?? String(value);
  } catch {
    return String(value);
  }
}

/* ==== Code ================================================================== */

/** `"1, 3-5"` or `[1, [3, 5]]` → {1, 3, 4, 5}. */
export function parseLineRanges(spec: string | ReadonlyArray<number | readonly [number, number]> | null | undefined): Set<number> {
  const out = new Set<number>();
  if (!spec) return out;
  const add = (from: number, to: number) => {
    if (!Number.isFinite(from) || !Number.isFinite(to)) return;
    const [a, b] = from <= to ? [from, to] : [to, from];
    for (let n = Math.max(1, Math.floor(a)); n <= Math.floor(b) && n - a < 10000; n++) out.add(n);
  };
  if (typeof spec === 'string') {
    for (const piece of spec.split(/[,\s]+/)) {
      if (!piece) continue;
      const range = /^(\d+)\s*[-–]\s*(\d+)$/.exec(piece);
      if (range) add(Number(range[1]), Number(range[2]));
      else if (/^\d+$/.test(piece)) add(Number(piece), Number(piece));
    }
  } else
    for (const item of spec) {
      if (typeof item === 'number') add(item, item);
      else add(item[0], item[1]);
    }
  return out;
}

export type CodeTokenType = 'plain' | 'comment' | 'string' | 'number' | 'keyword' | 'literal' | 'tag' | 'attr' | 'punct' | 'fn' | 'var';

export interface CodeToken {
  type: CodeTokenType;
  text: string;
}

const KEYWORDS = new Set(
  (
    'abstract and as async await break case catch class const continue def default del do echo elif else enum except export extends ' +
    'final finally fn for foreach from func function global go if impl implements import in instanceof interface is lambda let ' +
    'match mod module namespace new not of or package pass private protected pub public raise readonly return select static struct ' +
    'super switch this throw trait try type typeof use var void while with yield self'
  ).split(' '),
);
const LITERALS = new Set(['true', 'false', 'null', 'undefined', 'None', 'True', 'False', 'nil', 'NaN', 'Infinity']);
const HASH_COMMENTS = new Set(['py', 'python', 'sh', 'bash', 'shell', 'zsh', 'yaml', 'yml', 'toml', 'rb', 'ruby', 'dockerfile', 'env', 'ini', 'conf']);
const MARKUP = new Set(['html', 'xml', 'svg', 'vue', 'blade', 'jsx', 'tsx']);

/**
 * A deliberately small tokenizer: comments, strings, numbers, keywords,
 * literals, function calls, variables ($x) and markup tags. It reads one line
 * at a time, which is enough for agent output and keeps the bundle light;
 * multi-line strings and comments are coloured line by line.
 */
export function highlightLine(line: string, language = ''): CodeToken[] {
  const lang = language.toLowerCase();
  if (lang === 'text' || lang === 'plain' || lang === 'txt' || lang === 'diff' || lang === 'log') return line ? [{ type: 'plain', text: line }] : [];
  const hash = HASH_COMMENTS.has(lang) || lang === 'php';
  const markup = MARKUP.has(lang);
  const json = lang === 'json';
  const tokens: CodeToken[] = [];
  const push = (type: CodeTokenType, text: string) => {
    if (!text) return;
    const last = tokens[tokens.length - 1];
    if (last && last.type === type && type === 'plain') last.text += text;
    else tokens.push({ type, text });
  };
  let i = 0;
  while (i < line.length) {
    const rest = line.slice(i);
    let m: RegExpExecArray | null;
    if ((m = /^\/\/.*/.exec(rest)) && !json) {
      push('comment', m[0]);
    } else if (hash && (m = /^#(?![\w[{]).*|^#$/.exec(rest)) && (i === 0 || /\s/.test(line[i - 1]!))) {
      push('comment', m[0]);
    } else if ((m = /^\/\*.*?(\*\/|$)/.exec(rest)) || (markup && (m = /^<!--.*?(-->|$)/.exec(rest)))) {
      push('comment', m[0]);
    } else if ((m = /^(["'`])(?:\\.|(?!\1).)*\1?/.exec(rest))) {
      // A JSON key is a string followed by a colon.
      const after = line.slice(i + m[0].length);
      push(json && /^\s*:/.test(after) ? 'attr' : 'string', m[0]);
    } else if (markup && (m = /^<\/?[A-Za-z][\w:.-]*/.exec(rest))) {
      push('punct', m[0].startsWith('</') ? '</' : '<');
      push('tag', m[0].replace(/^<\/?/, ''));
    } else if ((m = /^(?:0x[\da-f]+|\d[\d_]*(?:\.\d+)?(?:e[+-]?\d+)?)\b/i.exec(rest)) && !/[\w$]/.test(line[i - 1] ?? '')) {
      push('number', m[0]);
    } else if ((m = /^\$[A-Za-z_]\w*/.exec(rest))) {
      push('var', m[0]);
    } else if ((m = /^[A-Za-z_][\w$]*/.exec(rest))) {
      const word = m[0];
      const after = line.slice(i + word.length);
      if (markup && /^=/.test(after) && /\s/.test(line[i - 1] ?? '')) push('attr', word);
      else if (KEYWORDS.has(word)) push('keyword', word);
      else if (LITERALS.has(word)) push('literal', word);
      else if (/^\s*\(/.test(after)) push('fn', word);
      else push('plain', word);
    } else if ((m = /^[{}()[\];,.:<>=+\-*/%!&|?^~@]+/.exec(rest))) {
      push('punct', m[0]);
    } else {
      m = /^\s+|^./.exec(rest)!;
      push('plain', m[0]);
    }
    i += m![0].length;
  }
  return tokens;
}

/** Diff markers at the start of a line in a diff-styled code block. */
export function diffMarker(line: string): 'add' | 'del' | null {
  if (/^\+(?!\+\+)/.test(line)) return 'add';
  if (/^-(?!--)/.test(line)) return 'del';
  return null;
}

/**
 * The Blade code block renders plain lines server-side; this paints the
 * tokens in place (`.nx-code-text` children), text only — never markup.
 */
export function paintCode(root: HTMLElement, language = ''): Cleanup {
  if (!isBrowser) return () => {};
  for (const cell of Array.from(root.querySelectorAll<HTMLElement>('.nx-code-text'))) {
    if (cell.dataset.painted !== undefined) continue;
    const text = cell.textContent ?? '';
    cell.textContent = '';
    for (const token of highlightLine(text, language)) {
      if (token.type === 'plain') cell.append(token.text);
      else {
        const span = document.createElement('span');
        span.className = `nx-tok-${token.type}`;
        span.textContent = token.text;
        cell.append(span);
      }
    }
    cell.dataset.painted = '';
  }
  return () => {};
}

/* ==== Stick to bottom (terminal, logs) ====================================== */

export interface StickController {
  /** Scroll to the end and follow again. */
  pin(smooth?: boolean): void;
  /** Whether new content will be followed. */
  readonly pinned: boolean;
  destroy: Cleanup;
}

/**
 * Follow the end of a scrolling log while the reader is there; let go as soon
 * as they scroll up (onChange(false)) so they can read, and follow again when
 * they come back down or press "jump to latest" (pin()).
 */
export function stickToBottom(scroller: HTMLElement, { threshold = 24, onChange }: { threshold?: number; onChange?: (pinned: boolean) => void } = {}): StickController {
  let pinned = true;
  const noop: StickController = { pin() {}, pinned: true, destroy() {} };
  if (!isBrowser) return noop;
  const atEnd = () => scroller.scrollHeight - scroller.scrollTop - scroller.clientHeight <= threshold;
  const set = (next: boolean) => {
    if (next === pinned) return;
    pinned = next;
    onChange?.(next);
  };
  let programmatic = false;
  const toEnd = (smooth = false) => {
    programmatic = true;
    scroller.scrollTo({ top: scroller.scrollHeight, behavior: smooth && !prefersReducedMotion() ? 'smooth' : 'auto' });
    requestAnimationFrame(() => {
      programmatic = false;
    });
  };
  const onScroll = () => {
    if (programmatic && pinned) return;
    set(atEnd());
  };
  // Wheel/touch up always means "let me read", even mid smooth scroll.
  const onWheel = (event: WheelEvent) => {
    if (event.deltaY < 0) {
      programmatic = false;
      set(false);
    }
  };
  scroller.addEventListener('scroll', onScroll, { passive: true });
  scroller.addEventListener('wheel', onWheel, { passive: true });
  const observer = new MutationObserver(() => {
    if (pinned) toEnd(false);
  });
  observer.observe(scroller, { childList: true, subtree: true, characterData: true });
  toEnd(false);
  return {
    pin(smooth = true) {
      set(true);
      toEnd(smooth);
    },
    get pinned() {
      return pinned;
    },
    destroy() {
      observer.disconnect();
      scroller.removeEventListener('scroll', onScroll);
      scroller.removeEventListener('wheel', onWheel);
    },
  };
}

/* ==== Terminal lines ========================================================= */

export type LogLevel = 'info' | 'warn' | 'error' | 'success' | 'debug';

const LEVEL_PREFIX: Array<[RegExp, LogLevel]> = [
  [/^\s*(?:\[?(?:error|err|fatal)\]?:?|✖|×)\s/i, 'error'],
  [/^\s*(?:\[?(?:warn|warning)\]?:?|⚠)\s/i, 'warn'],
  [/^\s*(?:\[?(?:ok|done|success)\]?:?|✓|✔)\s/i, 'success'],
  [/^\s*\[?debug\]?:?\s/i, 'debug'],
];

/** Guess a line's level from an ANSI-like prefix (`ERROR:`, `[warn]`, `✓`). */
export function logLevelOf(text: string, fallback: LogLevel = 'info'): LogLevel {
  for (const [pattern, level] of LEVEL_PREFIX) if (pattern.test(text)) return level;
  return fallback;
}
