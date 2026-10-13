/**
 * AI agent blocks (React): the reasoning trace, a streamed answer with
 * citations, tool calls, an approval card, state orbs, code, diffs, a JSON
 * tree and a terminal. Look and motion live in css/blocks/agent.css; the pure
 * logic (line diff, JSON rows, citations, tokenizer) and the DOM behaviours
 * (stickToBottom) in the core, shared with the Livewire build.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent,
  type ReactNode,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import {
  type JsonRow,
  type LogLevel,
  type StickController,
  type StreamSource,
  argsPreview,
  copyText,
  diffLines,
  diffMarker,
  diffRows,
  diffStats,
  domainOf,
  formatDuration,
  formatElapsed,
  highlightLine,
  jsonAt,
  jsonContainers,
  jsonOpenToDepth,
  jsonRows,
  jsonSearch,
  logLevelOf,
  markParts,
  parseLineRanges,
  place,
  prettyJson,
  streamParts,
  streamTokens,
  stickToBottom,
  textDirection,
  toLines,
} from '@nabuxai/ui-core';
import { cx, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale } from '../internal/provider';
import { Button, CopyButton } from '../components/button';
import { Kbd } from '../components/display';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;
const useIntl = (locale?: string) => locale ?? INTL[useLocale()];

/** The words a block says itself, English by default; pass `labels` to translate. */
function words<T extends object>(defaults: T, labels?: Partial<T>): T {
  return { ...defaults, ...(labels ?? {}) } as T;
}

/* ==== Thinking trace ========================================================= */

export type ThinkingStepStatus = 'pending' | 'running' | 'done' | 'error';

export interface ThinkingStep {
  id: string;
  label: ReactNode;
  status?: ThinkingStepStatus;
  detail?: ReactNode;
}

export interface ThinkingTraceLabels {
  thinking: string;
  /** `{time}` is replaced with the elapsed time ("12s"). */
  thought: string;
}

export interface ThinkingTraceProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  steps?: ThinkingStep[];
  /** Still reasoning: the header shimmers and the clock runs. */
  active?: boolean;
  /** When reasoning started (ms timestamp); the mount time by default. */
  startedAt?: number;
  /** Total seconds, when known (e.g. a finished trace loaded from history). */
  duration?: number;
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  /** Fold the trace away when reasoning finishes (default true). */
  autoCollapse?: boolean;
  labels?: Partial<ThinkingTraceLabels>;
  locale?: string;
  /** Free content under the steps (a summary, notes). */
  children?: ReactNode;
}

function StepMark({ status }: { status: ThinkingStepStatus }) {
  if (status === 'running') return <span className="nx-spinner" data-size="sm" aria-hidden="true" />;
  if (status === 'done') return <Icon name="check" />;
  if (status === 'error') return <Icon name="x" />;
  return <span className="nx-thinking-dot" aria-hidden="true" />;
}

export function ThinkingTrace({
  steps = [],
  active = false,
  startedAt,
  duration,
  open,
  defaultOpen,
  onOpenChange,
  autoCollapse = true,
  labels,
  locale,
  className,
  children,
  ...rest
}: ThinkingTraceProps) {
  const intl = useIntl(locale);
  const say = words<ThinkingTraceLabels>({ thinking: 'Thinking…', thought: 'Thought for {time}' }, labels);
  const id = useId();
  const [isOpen, setOpen] = useControllable(open, defaultOpen ?? active, onOpenChange);
  const start = useRef(startedAt ?? Date.now());
  if (startedAt !== undefined) start.current = startedAt;
  const [now, setNow] = useState(() => Date.now());
  const [final, setFinal] = useState<number | null>(active ? null : duration ?? null);

  useEffect(() => {
    if (!active) return;
    setFinal(null);
    setNow(Date.now());
    const timer = setInterval(() => setNow(Date.now()), 1000);
    return () => clearInterval(timer);
  }, [active]);

  // Active → done: freeze the clock and (by default) fold away.
  const wasActive = useRef(active);
  useEffect(() => {
    if (wasActive.current && !active) {
      setFinal(duration ?? (Date.now() - start.current) / 1000);
      if (autoCollapse) setOpen(false);
    }
    wasActive.current = active;
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [active]);

  const seconds = active ? (now - start.current) / 1000 : final ?? duration ?? 0;
  const time = formatElapsed(seconds, intl);
  const title = active ? say.thinking : say.thought.replace('{time}', time);

  // Steps on the first paint cascade in; later ones arrive on their own.
  const initial = useRef<Set<string> | null>(null);
  if (initial.current === null) initial.current = new Set(steps.map((step) => step.id));

  return (
    <div className={cx('nx-thinking', className)} data-state={active ? 'active' : 'done'} {...rest}>
      <button type="button" className="nx-thinking-head" aria-expanded={isOpen} aria-controls={`${id}-body`} onClick={() => setOpen(!isOpen)}>
        <span className="nx-agent-swap" aria-hidden="true">
          <Icon name="sparkles" data-on={active ? '' : undefined} />
          <Icon name="check-circle" data-on={active ? undefined : ''} />
        </span>
        <span className="nx-thinking-label">{title}</span>
        {active && <span className="nx-thinking-time">{time}</span>}
        <Icon name="chevron-down" className="nx-thinking-chevron" />
      </button>
      <span className="nx-visually-hidden" aria-live="polite">
        {active ? '' : title}
      </span>
      <div id={`${id}-body`} className="nx-agent-collapse" data-open={isOpen ? '' : undefined}>
        <div>
          {steps.length > 0 && (
            <ol className="nx-thinking-steps">
              {steps.map((step, i) => {
                const status = step.status ?? 'done';
                return (
                  <li key={step.id} className="nx-thinking-step" data-status={status} style={vars({ '--nx-i': initial.current!.has(step.id) ? i : 0 })}>
                    <span className="nx-thinking-mark">
                      <StepMark status={status} />
                    </span>
                    <span className="nx-thinking-step-label">{step.label}</span>
                    {step.detail && <p className="nx-thinking-step-detail">{step.detail}</p>}
                  </li>
                );
              })}
            </ol>
          )}
          {children}
        </div>
      </div>
    </div>
  );
}

/* ==== Streaming text ========================================================== */

export interface StreamingTextProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  /** Everything received so far; grow it as tokens arrive. */
  text: string;
  /** Tokens are still arriving: the caret blinks and new words fade in. */
  streaming?: boolean;
  /** Sources for `[n]` markers: an array (n = 1-based index) or a map by n. */
  sources?: StreamSource[] | Record<number, StreamSource>;
  /** Accessible name of a citation button. */
  citeLabel?: (n: number, source?: StreamSource) => string;
  /** The link text inside the source popover. */
  openLabel?: string;
}

const sourceOf = (sources: StreamingTextProps['sources'], n: number) => (Array.isArray(sources) ? sources[n - 1] : sources?.[n]);

export function StreamingText({ text, streaming = false, sources, citeLabel, openLabel = 'Open source', className, ...rest }: StreamingTextProps) {
  const id = useId();
  const animate = useRef(streaming);
  if (streaming) animate.current = true;
  const [cite, setCite] = useState<{ n: number; anchor: HTMLElement } | null>(null);
  const popover = useRef<HTMLDivElement>(null);

  const pieces = useMemo(() => {
    const out: Array<{ cite: number } | { token: string }> = [];
    for (const part of streamParts(text, streaming)) {
      if (part.type === 'cite') out.push({ cite: part.n });
      else for (const token of streamTokens(part.text)) out.push({ token });
    }
    return out;
  }, [text, streaming]);

  useEffect(() => {
    const el = popover.current;
    if (!el || !cite) return;
    const onToggle = (event: Event) => {
      if ((event as ToggleEvent).newState === 'closed') setCite(null);
    };
    el.addEventListener('toggle', onToggle);
    try {
      if (!el.matches(':popover-open')) el.showPopover();
    } catch {
      el.setAttribute('data-open', '');
    }
    const stop = place(cite.anchor, el, { side: 'top', align: 'center' });
    return () => {
      stop();
      el.removeEventListener('toggle', onToggle);
    };
  }, [cite]);

  const source = cite ? sourceOf(sources, cite.n) : undefined;
  const label = (n: number) => citeLabel?.(n, sourceOf(sources, n)) ?? `Source ${n}${sourceOf(sources, n) ? `: ${sourceOf(sources, n)!.title}` : ''}`;

  return (
    <div className={cx('nx-stream', className)} data-streaming={streaming ? '' : undefined} dir={textDirection(text)} {...rest}>
      <p className="nx-stream-body" aria-busy={streaming || undefined}>
        {pieces.map((piece, i) =>
          'cite' in piece ? (
            <button
              key={i}
              type="button"
              className="nx-stream-cite"
              data-fresh={animate.current ? '' : undefined}
              aria-label={label(piece.cite)}
              aria-haspopup="dialog"
              aria-expanded={cite?.n === piece.cite}
              aria-controls={`${id}-source`}
              onClick={(event) => {
                const anchor = event.currentTarget;
                setCite((current) => (current?.anchor === anchor ? null : { n: piece.cite, anchor }));
                if (cite?.anchor === anchor) popover.current?.hidePopover?.();
              }}
            >
              {piece.cite}
            </button>
          ) : (
            <span key={i} className="nx-stream-token" data-fresh={animate.current ? '' : undefined}>
              {piece.token}
            </span>
          ),
        )}
        <span className="nx-stream-caret" aria-hidden="true" />
      </p>
      <div ref={popover} id={`${id}-source`} className="nx-popover nx-stream-source" popover="auto" role="dialog" aria-label={source?.title ?? ''}>
        {source ? (
          <>
            <span className="nx-stream-source-domain">
              <Icon name="globe" />
              {domainOf(source)}
            </span>
            <p className="nx-stream-source-title" dir="auto">
              {source.title}
            </p>
            {source.snippet && (
              <p className="nx-stream-source-snippet" dir="auto">
                {source.snippet}
              </p>
            )}
            {source.url && (
              <a className="nx-stream-source-link" href={source.url} target="_blank" rel="noopener noreferrer">
                {openLabel}
              </a>
            )}
          </>
        ) : cite ? (
          <p className="nx-stream-source-title">{label(cite.n)}</p>
        ) : null}
      </div>
    </div>
  );
}

/* ==== Tool call ================================================================ */

export type ToolCallStatus = 'queued' | 'running' | 'success' | 'error';

export interface ToolCallLabels {
  input: string;
  output: string;
  queued: string;
  running: string;
  success: string;
  error: string;
}

export interface ToolCallProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  name: string;
  /** The arguments; previewed on one line and shown in full as the input. */
  args?: unknown;
  status?: ToolCallStatus;
  /** Shown as the input when it differs from `args`. */
  input?: unknown;
  output?: unknown;
  /** How long the call took, ms. */
  duration?: number;
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  labels?: Partial<ToolCallLabels>;
  locale?: string;
}

export function ToolCall({ name, args, status = 'success', input, output, duration, open, defaultOpen = false, onOpenChange, labels, locale, className, ...rest }: ToolCallProps) {
  const intl = useIntl(locale);
  const say = words<ToolCallLabels>({ input: 'Input', output: 'Output', queued: 'Queued', running: 'Running', success: 'Done', error: 'Failed' }, labels);
  const id = useId();
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const shownInput = input ?? args;

  return (
    <div className={cx('nx-tool', className)} data-status={status} {...rest}>
      <button type="button" className="nx-tool-head" aria-expanded={isOpen} aria-controls={`${id}-panels`} onClick={() => setOpen(!isOpen)}>
        <span className="nx-tool-status nx-agent-swap" aria-hidden="true">
          <span data-icon="queued" data-on={status === 'queued' ? '' : undefined}>
            <span className="nx-thinking-dot" />
          </span>
          <span data-icon="running" data-on={status === 'running' ? '' : undefined}>
            <span className="nx-spinner" />
          </span>
          <Icon name="check-circle" data-icon="success" data-on={status === 'success' ? '' : undefined} />
          <Icon name="alert-circle" data-icon="error" data-on={status === 'error' ? '' : undefined} />
        </span>
        <span className="nx-tool-name">{name}</span>
        <span className="nx-tool-args" dir="ltr">
          {argsPreview(args)}
        </span>
        {duration !== undefined && status !== 'running' && status !== 'queued' && <span className="nx-tool-time">{formatDuration(duration, intl)}</span>}
        <span className="nx-tool-state">{say[status]}</span>
        <Icon name="chevron-down" className="nx-tool-chevron" />
      </button>
      <div id={`${id}-panels`} className="nx-agent-collapse" data-open={isOpen ? '' : undefined}>
        <div>
          <div className="nx-tool-panels" dir="ltr">
            {shownInput !== undefined && (
              <div className="nx-tool-section">
                <p className="nx-tool-label">{say.input}</p>
                <pre className="nx-tool-pre">{prettyJson(shownInput)}</pre>
              </div>
            )}
            {output !== undefined && (
              <div className="nx-tool-section">
                <p className="nx-tool-label">{say.output}</p>
                <pre className="nx-tool-pre">{prettyJson(output)}</pre>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}

/* ==== Approval card ============================================================ */

export type ApprovalState = 'pending' | 'approved' | 'denied' | 'always';

export interface ApprovalCardLabels {
  approve: string;
  deny: string;
  always: string;
  approved: string;
  denied: string;
  alwaysAllowed: string;
  undo: string;
}

export interface ApprovalCardProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  title: ReactNode;
  summary?: ReactNode;
  /** The tool or command asking, shown as a chip. */
  tool?: string;
  /** More chips (scope, path, cost…). */
  meta?: ReactNode;
  value?: ApprovalState;
  defaultValue?: ApprovalState;
  onValueChange?: (state: ApprovalState) => void;
  onApprove?: () => void;
  onDeny?: () => void;
  onAlwaysAllow?: () => void;
  /** Offer "Always allow" (default true). */
  allowAlways?: boolean;
  /** Y / N / A while focus is inside the card (default true). */
  shortcuts?: boolean;
  /** Show an Undo on the resolved state. */
  undoable?: boolean;
  /** Focus the card when it mounts, so the shortcuts work at once. */
  autoFocus?: boolean;
  labels?: Partial<ApprovalCardLabels>;
  /** The details/diff preview. */
  children?: ReactNode;
}

export function ApprovalCard({
  title,
  summary,
  tool,
  meta,
  value,
  defaultValue = 'pending',
  onValueChange,
  onApprove,
  onDeny,
  onAlwaysAllow,
  allowAlways = true,
  shortcuts = true,
  undoable = false,
  autoFocus = false,
  labels,
  className,
  children,
  onKeyDown,
  ...rest
}: ApprovalCardProps) {
  const say = words<ApprovalCardLabels>(
    { approve: 'Approve', deny: 'Deny', always: 'Always allow', approved: 'Approved', denied: 'Denied', alwaysAllowed: 'Always allowed', undo: 'Undo' },
    labels,
  );
  const id = useId();
  const root = useRef<HTMLElement>(null);
  const [state, setState] = useControllable<ApprovalState>(value, defaultValue, onValueChange);
  const pending = state === 'pending';

  useEffect(() => {
    if (autoFocus) root.current?.focus({ preventScroll: true });
  }, [autoFocus]);

  const decide = (next: ApprovalState) => {
    if (!pending) return;
    setState(next);
    if (next === 'approved') onApprove?.();
    else if (next === 'denied') onDeny?.();
    else if (next === 'always') onAlwaysAllow?.();
    // Keep focus in the card once the buttons fold away.
    requestAnimationFrame(() => root.current?.focus({ preventScroll: true }));
  };

  const onKey = (event: KeyboardEvent<HTMLElement>) => {
    onKeyDown?.(event);
    if (!shortcuts || !pending || event.defaultPrevented || event.metaKey || event.ctrlKey || event.altKey) return;
    const target = event.target as HTMLElement;
    if (target.closest('input, textarea, select, [contenteditable="true"]')) return;
    const key = event.key.toLowerCase();
    const next: ApprovalState | null = key === 'y' ? 'approved' : key === 'n' ? 'denied' : key === 'a' && allowAlways ? 'always' : null;
    if (!next) return;
    event.preventDefault();
    decide(next);
  };

  const result = state === 'approved' ? say.approved : state === 'denied' ? say.denied : state === 'always' ? say.alwaysAllowed : '';

  return (
    <section
      ref={root}
      className={cx('nx-approval', className)}
      data-state={state}
      tabIndex={-1}
      aria-labelledby={`${id}-title`}
      onKeyDown={onKey}
      {...rest}
    >
      <div className="nx-approval-head">
        <span className="nx-approval-badge nx-agent-swap" aria-hidden="true">
          <Icon name="shield" data-on={pending ? '' : undefined} />
          <Icon name="check" data-on={state === 'approved' || state === 'always' ? '' : undefined} />
          <Icon name="x" data-on={state === 'denied' ? '' : undefined} />
        </span>
        <div className="nx-approval-copy">
          <h3 id={`${id}-title`} className="nx-approval-title">
            {title}
          </h3>
          {pending && summary && <p className="nx-approval-summary">{summary}</p>}
          {pending && (tool || meta) && (
            <div className="nx-approval-meta">
              {tool && (
                <span className="nx-approval-chip" dir="ltr">
                  <Icon name="zap" />
                  {tool}
                </span>
              )}
              {meta}
            </div>
          )}
        </div>
        {!pending && <span className="nx-approval-result">{result}</span>}
        {!pending && undoable && (
          <button type="button" className="nx-approval-undo" onClick={() => setState('pending')}>
            {say.undo}
          </button>
        )}
      </div>
      <span className="nx-visually-hidden" aria-live="polite">
        {result}
      </span>
      <div className="nx-agent-collapse" data-open={pending ? '' : undefined}>
        <div>
          <div className="nx-approval-body">
            {children && <div className="nx-approval-details">{children}</div>}
            <div className="nx-approval-actions">
              <Button variant="primary" size="sm" icon="check" onClick={() => decide('approved')} aria-keyshortcuts={shortcuts ? 'Y' : undefined}>
                {say.approve}
                {shortcuts && <Kbd aria-hidden="true">Y</Kbd>}
              </Button>
              <Button variant="secondary" size="sm" icon="x" onClick={() => decide('denied')} aria-keyshortcuts={shortcuts ? 'N' : undefined}>
                {say.deny}
                {shortcuts && <Kbd aria-hidden="true">N</Kbd>}
              </Button>
              <span className="nx-approval-spacer" />
              {allowAlways && (
                <Button variant="ghost" size="sm" onClick={() => decide('always')} aria-keyshortcuts={shortcuts ? 'A' : undefined}>
                  {say.always}
                  {shortcuts && <Kbd aria-hidden="true">A</Kbd>}
                </Button>
              )}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

/* ==== Thinking orbs ============================================================ */

export type OrbState = 'idle' | 'thinking' | 'speaking' | 'listening';

export interface ThinkingOrbsProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'children'> {
  state?: OrbState;
  size?: 'sm' | 'md' | 'lg';
  /** The announced state; English words by default. */
  label?: string;
}

const ORB_WORDS: Record<OrbState, string> = { idle: 'Idle', thinking: 'Thinking', speaking: 'Speaking', listening: 'Listening' };

export function ThinkingOrbs({ state = 'thinking', size = 'md', label, className, ...rest }: ThinkingOrbsProps) {
  return (
    <span className={cx('nx-orbs', className)} role="status" data-state={state} data-size={size === 'md' ? undefined : size} {...rest}>
      <i aria-hidden="true" />
      <i aria-hidden="true" />
      <i aria-hidden="true" />
      <span className="nx-visually-hidden">{label ?? ORB_WORDS[state]}</span>
    </span>
  );
}

/* ==== Code block =============================================================== */

export interface CodeBlockProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  code: string;
  language?: string;
  filename?: string;
  /** Lines to highlight: "3, 7-9" or [3, [7, 9]]. */
  highlight?: string | Array<number | [number, number]>;
  /** Colour lines that start with + / − (on by default for language "diff"). */
  diff?: boolean;
  lineNumbers?: boolean;
  startLine?: number;
  wrap?: boolean;
  defaultWrap?: boolean;
  onWrapChange?: (wrap: boolean) => void;
  /** Show the copy button (default true). */
  copy?: boolean;
  /** Body max height before it scrolls (e.g. "20rem"). */
  maxHeight?: string;
  labels?: Partial<{ wrap: string; copy: string }>;
}

export function CodeBlock({
  code,
  language = '',
  filename,
  highlight,
  diff,
  lineNumbers = true,
  startLine = 1,
  wrap,
  defaultWrap = false,
  onWrapChange,
  copy = true,
  maxHeight,
  labels,
  className,
  style,
  ...rest
}: CodeBlockProps) {
  const say = words({ wrap: 'Wrap lines', copy: 'Copy code' }, labels);
  const [wrapped, setWrapped] = useControllable(wrap, defaultWrap, onWrapChange);
  const isDiff = diff ?? language.toLowerCase() === 'diff';
  const marked = useMemo(() => parseLineRanges(highlight), [highlight]);
  const lines = useMemo(
    () =>
      toLines(code).map((raw) => {
        const mark = isDiff ? diffMarker(raw) : null;
        const text = isDiff && (mark || raw.startsWith(' ')) ? raw.slice(1) : raw;
        return { mark, text, tokens: highlightLine(text, isDiff && language.toLowerCase() === 'diff' ? 'text' : language) };
      }),
    [code, language, isDiff],
  );

  return (
    <div
      className={cx('nx-code', className)}
      dir="ltr"
      data-wrap={wrapped ? '' : undefined}
      data-diff={isDiff ? '' : undefined}
      data-numbers={lineNumbers ? undefined : 'false'}
      style={{ ...(maxHeight ? vars({ '--nx-code-height': maxHeight }) : null), ...style }}
      {...rest}
    >
      {(filename || language || copy) && (
        <div className="nx-code-head">
          {filename && (
            <span className="nx-code-file">
              <Icon name="file" />
              {filename}
            </span>
          )}
          {language && <span className="nx-code-lang">{language}</span>}
          <span className="nx-code-tools">
            <button type="button" className="nx-agent-icon-button" aria-pressed={wrapped} aria-label={say.wrap} title={say.wrap} onClick={() => setWrapped(!wrapped)}>
              <Icon name="menu" />
            </button>
            {copy && <CopyButton value={code} size="sm" aria-label={say.copy} />}
          </span>
        </div>
      )}
      <div className="nx-code-body" tabIndex={0} role="region" aria-label={filename ?? language ?? 'Code'}>
        <pre className="nx-code-pre">
          <code>
            {lines.map((line, i) => {
              const n = startLine + i;
              return (
                <span key={i} className="nx-code-line" data-highlight={marked.has(n) ? '' : undefined} data-mark={line.mark ?? undefined}>
                  <span className="nx-code-num" aria-hidden="true">
                    {n}
                  </span>
                  <span className="nx-code-sign" aria-hidden="true">
                    {line.mark === 'add' ? '+' : line.mark === 'del' ? '-' : ' '}
                  </span>
                  <span className="nx-code-text">
                    {line.tokens.map((token, t) => (token.type === 'plain' ? token.text : <span key={t} className={`nx-tok-${token.type}`}>{token.text}</span>))}
                    {'\n'}
                  </span>
                </span>
              );
            })}
          </code>
        </pre>
      </div>
    </div>
  );
}

/* ==== Diff viewer ============================================================== */

export type DiffView = 'unified' | 'split';

export interface DiffViewerLabels {
  unified: string;
  split: string;
  /** `{count}` unchanged lines. */
  expand: string;
  noChanges: string;
  /** `{added}` / `{removed}` for the accessible summary. */
  summary: string;
}

export interface DiffViewerProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  oldText: string;
  newText: string;
  filename?: string;
  view?: DiffView;
  defaultView?: DiffView;
  onViewChange?: (view: DiffView) => void;
  /** Unchanged lines kept around each change (default 3). */
  context?: number;
  maxHeight?: string;
  labels?: Partial<DiffViewerLabels>;
}

export function DiffViewer({ oldText, newText, filename, view, defaultView = 'unified', onViewChange, context = 3, maxHeight, labels, className, style, ...rest }: DiffViewerProps) {
  const say = words<DiffViewerLabels>(
    { unified: 'Unified', split: 'Split', expand: 'Expand {count} unchanged lines', noChanges: 'No changes', summary: '{added} additions, {removed} deletions' },
    labels,
  );
  const [mode, setMode] = useControllable(view, defaultView, onViewChange);
  const [expanded, setExpanded] = useState<number[]>([]);
  const lines = useMemo(() => diffLines(oldText, newText), [oldText, newText]);
  useEffect(() => setExpanded([]), [lines]);
  const stats = diffStats(lines);
  const split = mode === 'split';
  const rows = useMemo(() => diffRows(lines, { context, expanded, split }), [lines, context, expanded, split]);
  const summary = say.summary.replace('{added}', String(stats.added)).replace('{removed}', String(stats.removed));

  return (
    <div className={cx('nx-diff', className)} dir="ltr" style={{ ...(maxHeight ? vars({ '--nx-diff-height': maxHeight }) : null), ...style }} {...rest}>
      <div className="nx-diff-head">
        {filename && (
          <span className="nx-diff-file">
            <Icon name="file" />
            {filename}
          </span>
        )}
        <span className="nx-diff-stats" role="img" aria-label={summary}>
          <span className="nx-diff-stat" data-kind="add">
            +{stats.added}
          </span>
          <span className="nx-diff-stat" data-kind="del">
            −{stats.removed}
          </span>
        </span>
        <span className="nx-diff-views" role="group">
          {(['unified', 'split'] as const).map((option) => (
            <button key={option} type="button" className="nx-diff-view" aria-pressed={mode === option} onClick={() => setMode(option)}>
              {say[option]}
            </button>
          ))}
        </span>
      </div>
      <div className="nx-diff-body" tabIndex={0} role="region" aria-label={filename ?? summary}>
        {stats.added + stats.removed === 0 && <p className="nx-diff-empty">{say.noChanges}</p>}
        <div className="nx-diff-table" data-view={mode}>
          {rows.map((row) => {
            if (row.type === 'gap')
              return (
                <button key={row.key} type="button" className="nx-diff-gap" onClick={() => setExpanded((list) => [...list, row.gap])}>
                  <Icon name="chevron-down" />
                  {say.expand.replace('{count}', String(row.count))}
                </button>
              );
            if (row.type === 'line') {
              const { line } = row;
              return (
                <div key={row.key} className="nx-diff-row" data-kind={line.kind}>
                  <span className="nx-diff-num">{line.oldNo ?? ''}</span>
                  <span className="nx-diff-num">{line.newNo ?? ''}</span>
                  <span className="nx-diff-sign">{line.kind === 'add' ? '+' : line.kind === 'del' ? '-' : ' '}</span>
                  <span className="nx-diff-text">{line.text}</span>
                </div>
              );
            }
            const left = row.left;
            const right = row.right;
            return (
              <div key={row.key} className="nx-diff-row">
                <span className="nx-diff-num nx-diff-cell" data-kind={left?.kind ?? 'empty'}>
                  {left?.oldNo ?? ''}
                </span>
                <span className="nx-diff-text nx-diff-cell" data-kind={left?.kind ?? 'empty'}>
                  {left?.text ?? ''}
                </span>
                <span className="nx-diff-num nx-diff-cell" data-kind={right?.kind ?? 'empty'}>
                  {right?.newNo ?? ''}
                </span>
                <span className="nx-diff-text nx-diff-cell" data-kind={right?.kind ?? 'empty'}>
                  {right?.text ?? ''}
                </span>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}

/* ==== JSON viewer ============================================================== */

export interface JsonViewerLabels {
  search: string;
  expandAll: string;
  collapseAll: string;
  copyPath: string;
  copyValue: string;
  /** `{count}` children. */
  items: string;
  keys: string;
  /** `{count}` hits. */
  matches: string;
  expand: string;
  collapse: string;
}

export interface JsonViewerProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  data: unknown;
  /** Levels open on first paint (default 1). */
  defaultDepth?: number;
  /** Show the search box (default true). */
  searchable?: boolean;
  maxHeight?: string;
  labels?: Partial<JsonViewerLabels>;
}

function Marked({ text, query }: { text: string; query: string }) {
  return (
    <>
      {markParts(text, query).map((part, i) => (part.hit ? <mark key={i}>{part.text}</mark> : part.text))}
    </>
  );
}

export function JsonViewer({ data, defaultDepth = 1, searchable = true, maxHeight, labels, className, style, ...rest }: JsonViewerProps) {
  const say = words<JsonViewerLabels>(
    {
      search: 'Search keys and values',
      expandAll: 'Expand all',
      collapseAll: 'Collapse all',
      copyPath: 'Copy path',
      copyValue: 'Copy value',
      items: '{count} items',
      keys: '{count} keys',
      matches: '{count} found',
      expand: 'Expand',
      collapse: 'Collapse',
    },
    labels,
  );
  const [open, setOpen] = useState<Set<string>>(() => new Set(jsonOpenToDepth(data, defaultDepth - 1)));
  const [query, setQuery] = useState('');
  const [copied, setCopied] = useState<string | null>(null);
  const search = useMemo(() => jsonSearch(data, query), [data, query]);

  // A search opens the way to every hit (and leaves it open).
  useEffect(() => {
    if (search.open.size) setOpen((current) => new Set([...current, ...search.open]));
  }, [search]);

  useEffect(() => {
    if (!copied) return;
    const timer = setTimeout(() => setCopied(null), 1400);
    return () => clearTimeout(timer);
  }, [copied]);

  const rows = useMemo(() => jsonRows(data, (path) => open.has(path)), [data, open]);
  const toggle = (path: string) =>
    setOpen((current) => {
      const next = new Set(current);
      if (next.has(path)) next.delete(path);
      else next.add(path);
      return next;
    });

  const copy = async (row: JsonRow, what: 'path' | 'value') => {
    const value = jsonAt(data, row.segments);
    const text = what === 'path' ? row.id : typeof value === 'string' ? value : prettyJson(value);
    if (await copyText(text)) setCopied(`${row.id}:${what}`);
  };

  const brace = (row: JsonRow, closing: boolean) => (row.type === 'array' ? (closing ? ']' : '[') : closing ? '}' : '{');

  return (
    <div className={cx('nx-json', className)} dir="ltr" style={{ ...(maxHeight ? vars({ '--nx-json-height': maxHeight }) : null), ...style }} {...rest}>
      <div className="nx-json-bar">
        {searchable && (
          <label className="nx-json-search">
            <Icon name="search" />
            <input className="nx-json-search-input" type="search" placeholder={say.search} aria-label={say.search} value={query} onChange={(event) => setQuery(event.target.value)} />
          </label>
        )}
        {query.trim() && (
          <span className="nx-json-found" aria-live="polite">
            {say.matches.replace('{count}', String(search.hits.size))}
          </span>
        )}
        <span className="nx-code-tools">
          <button type="button" className="nx-agent-icon-button" aria-label={say.expandAll} title={say.expandAll} onClick={() => setOpen(new Set(jsonContainers(data)))}>
            <Icon name="plus" />
          </button>
          <button type="button" className="nx-agent-icon-button" aria-label={say.collapseAll} title={say.collapseAll} onClick={() => setOpen(new Set())}>
            <Icon name="minus" />
          </button>
        </span>
      </div>
      <ul className="nx-json-tree">
        {rows.map((row) => {
          const container = row.type === 'object' || row.type === 'array';
          const comma = row.last ? '' : ',';
          const key =
            row.key === null ? null : (
              <>
                <span className="nx-json-key">{typeof row.key === 'number' ? row.key : <Marked text={JSON.stringify(row.key)} query={query} />}</span>
                <span className="nx-json-punct">: </span>
              </>
            );
          if (row.close)
            return (
              <li key={row.id} className="nx-json-row" style={vars({ '--_depth': row.depth })}>
                <span className="nx-json-spacer" />
                <span className="nx-json-punct">
                  {brace(row, true)}
                  {comma}
                </span>
              </li>
            );
          return (
            <li key={row.id} className="nx-json-row" style={vars({ '--_depth': row.depth })} data-type={row.type} data-hit={search.hits.has(row.id) ? '' : undefined}>
              {container && row.count > 0 ? (
                <button
                  type="button"
                  className="nx-json-toggle"
                  aria-expanded={row.open}
                  aria-label={`${row.open ? say.collapse : say.expand} ${row.key ?? '$'}`}
                  onClick={() => toggle(row.id)}
                >
                  <Icon name="chevron-down" />
                </button>
              ) : (
                <span className="nx-json-spacer" />
              )}
              {key}
              {container ? (
                <>
                  <span className="nx-json-punct">
                    {brace(row, false)}
                    {!row.open && (row.count > 0 ? '…' : '')}
                    {!row.open && brace(row, true)}
                    {!row.open && comma}
                  </span>
                  {!row.open && row.count > 0 && <span className="nx-json-count">{(row.type === 'array' ? say.items : say.keys).replace('{count}', String(row.count))}</span>}
                </>
              ) : (
                <span className="nx-json-value" data-type={row.type}>
                  <Marked text={row.literal} query={query} />
                  <span className="nx-json-punct">{comma}</span>
                </span>
              )}
              <span className="nx-json-actions">
                <button type="button" className="nx-agent-icon-button" aria-label={`${say.copyPath} ${row.id}`} title={say.copyPath} data-copied={copied === `${row.id}:path` ? '' : undefined} onClick={() => copy(row, 'path')}>
                  <span className="nx-agent-swap">
                    <Icon name="layers" className="nx-agent-swap-idle" />
                    <Icon name="check" className="nx-agent-swap-done" />
                  </span>
                </button>
                <button type="button" className="nx-agent-icon-button" aria-label={`${say.copyValue} ${row.id}`} title={say.copyValue} data-copied={copied === `${row.id}:value` ? '' : undefined} onClick={() => copy(row, 'value')}>
                  <span className="nx-agent-swap">
                    <Icon name="copy" className="nx-agent-swap-idle" />
                    <Icon name="check" className="nx-agent-swap-done" />
                  </span>
                </button>
              </span>
            </li>
          );
        })}
      </ul>
    </div>
  );
}

/* ==== Terminal ================================================================= */

export interface TerminalLine {
  id: string;
  text: string;
  /** Guessed from an `ERROR:` / `[warn]` / `✓` prefix when omitted. */
  level?: LogLevel;
  time?: string;
}

export type TerminalFilter = 'all' | 'info' | 'warn' | 'error';

export interface TerminalLabels {
  all: string;
  info: string;
  warn: string;
  error: string;
  jump: string;
  empty: string;
  log: string;
}

export interface TerminalProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  lines: TerminalLine[];
  title?: string;
  /** The prompt symbol (default `$`); null hides the prompt line. */
  prompt?: string | null;
  /** Text after the prompt (a command being typed). */
  command?: string;
  filter?: TerminalFilter;
  defaultFilter?: TerminalFilter;
  onFilterChange?: (filter: TerminalFilter) => void;
  /** Show the level filters (default true). */
  filters?: boolean;
  height?: string;
  labels?: Partial<TerminalLabels>;
}

const levelGroup = (level: LogLevel): Exclude<TerminalFilter, 'all'> => (level === 'warn' || level === 'error' ? level : 'info');

export function Terminal({
  lines,
  title,
  prompt = '$',
  command = '',
  filter,
  defaultFilter = 'all',
  onFilterChange,
  filters = true,
  height,
  labels,
  className,
  style,
  ...rest
}: TerminalProps) {
  const say = words<TerminalLabels>({ all: 'All', info: 'Info', warn: 'Warn', error: 'Error', jump: 'Jump to latest', empty: 'No lines', log: 'Output' }, labels);
  const [current, setCurrent] = useControllable<TerminalFilter>(filter, defaultFilter, onFilterChange);
  const [pinned, setPinned] = useState(true);
  const screen = useRef<HTMLDivElement>(null);
  const stick = useRef<StickController | null>(null);

  useEffect(() => {
    if (!screen.current) return;
    const controller = stickToBottom(screen.current, { onChange: setPinned });
    stick.current = controller;
    return () => {
      controller.destroy();
      stick.current = null;
    };
  }, []);

  const painted = useRef<Set<string> | null>(null);
  if (painted.current === null) painted.current = new Set(lines.map((line) => line.id));

  const levelled = useMemo(() => lines.map((line) => ({ ...line, level: line.level ?? logLevelOf(line.text) })), [lines]);
  const counts = useMemo(() => {
    const out = { all: levelled.length, info: 0, warn: 0, error: 0 };
    for (const line of levelled) out[levelGroup(line.level)]++;
    return out;
  }, [levelled]);
  const shown = current === 'all' ? levelled : levelled.filter((line) => levelGroup(line.level) === current);

  return (
    <div className={cx('nx-terminal', className)} dir="ltr" style={{ ...(height ? vars({ '--nx-terminal-height': height }) : null), ...style }} {...rest}>
      <div className="nx-terminal-head">
        <span className="nx-terminal-lights" aria-hidden="true">
          <i />
          <i />
          <i />
        </span>
        {title && <span className="nx-terminal-title">{title}</span>}
        {filters && (
          <span className="nx-terminal-filters" role="group">
            {(['all', 'info', 'warn', 'error'] as const).map((option) => (
              <button key={option} type="button" className="nx-terminal-filter" data-level={option} aria-pressed={current === option} onClick={() => setCurrent(option)}>
                {say[option]}
                <b>{counts[option]}</b>
              </button>
            ))}
          </span>
        )}
      </div>
      <div ref={screen} className="nx-terminal-screen" role="log" aria-live="polite" aria-label={title ?? say.log} tabIndex={0}>
        {shown.length === 0 && <div className="nx-terminal-empty">{say.empty}</div>}
        {shown.map((line) => (
          <div key={line.id} className="nx-terminal-line" data-level={line.level} data-fresh={painted.current!.has(line.id) ? undefined : ''}>
            <span className="nx-terminal-time">{line.time ?? ''}</span>
            <span className="nx-terminal-level">{line.level}</span>
            <span className="nx-terminal-text">{line.text}</span>
          </div>
        ))}
        {prompt !== null && (
          <div className="nx-terminal-prompt" aria-hidden="true">
            <span className="nx-terminal-ps">{prompt}</span>
            <span>
              {command}
              <span className="nx-terminal-caret" />
            </span>
          </div>
        )}
      </div>
      <button type="button" className="nx-terminal-jump" data-show={pinned ? undefined : ''} tabIndex={pinned ? -1 : 0} aria-hidden={pinned || undefined} onClick={() => stick.current?.pin(true)}>
        <Icon name="arrow-up" />
        {say.jump}
      </button>
    </div>
  );
}

/** Fake a token stream for demos and tests (re-exported from core). */
export { simulateStream } from '@nabuxai/ui-core';
