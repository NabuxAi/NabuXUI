/**
 * The toast store: one queue for the whole page, whichever framework draws it.
 *
 * React subscribes with useSyncExternalStore, the Livewire toaster with Alpine,
 * and anything can push: `toast.success('Saved')`, a Livewire
 * `$this->toast(...)`, an Inertia flash. Timing (auto-dismiss, pausing while the
 * stack is hovered) lives here, so every renderer behaves the same.
 */
export type ToastTone = 'neutral' | 'accent' | 'success' | 'warning' | 'danger' | 'info';

export interface ToastAction {
  label: string;
  href?: string;
  onClick?: () => void;
}

export interface ToastInput {
  id?: string;
  title: string;
  description?: string;
  tone?: ToastTone;
  /** Milliseconds before it leaves by itself; 0 keeps it until dismissed. */
  duration?: number;
  action?: ToastAction;
}

export interface Toast extends Required<Pick<ToastInput, 'title' | 'tone' | 'duration'>> {
  id: string;
  description?: string;
  action?: ToastAction;
  state: 'open' | 'closing';
  createdAt: number;
}

type Listener = (toasts: readonly Toast[]) => void;

const DEFAULT_DURATION = 5000;
const MAX_TOASTS = 5;
/** If nothing on the page animates a closing toast away, drop it anyway. */
const CLOSING_GRACE = 1200;

let counter = 0;

export class ToastStore {
  private toasts: Toast[] = [];
  private listeners = new Set<Listener>();
  private timers = new Map<string, { handle: ReturnType<typeof setTimeout> | null; remaining: number; startedAt: number }>();
  private paused = false;

  subscribe = (listener: Listener): (() => void) => {
    this.listeners.add(listener);
    return () => this.listeners.delete(listener);
  };

  /** Stable between changes, as useSyncExternalStore requires. */
  getSnapshot = (): readonly Toast[] => this.toasts;

  show = (input: ToastInput): string => {
    const id = input.id ?? `nx-toast-${(counter += 1)}`;
    const existing = this.toasts.find((t) => t.id === id);
    const toast: Toast = {
      id,
      title: input.title,
      description: input.description,
      tone: input.tone ?? 'neutral',
      duration: input.duration ?? (input.tone === 'danger' ? DEFAULT_DURATION * 1.6 : DEFAULT_DURATION),
      action: input.action,
      state: 'open',
      createdAt: Date.now(),
    };

    if (existing) {
      this.toasts = this.toasts.map((t) => (t.id === id ? toast : t));
    } else {
      this.toasts = [toast, ...this.toasts];
      const open = this.toasts.filter((t) => t.state === 'open');
      for (const extra of open.slice(MAX_TOASTS)) this.dismiss(extra.id);
    }

    this.clearTimer(id);
    if (toast.duration > 0) this.startTimer(id, toast.duration);
    this.emit();
    return id;
  };

  /** Starts the toast's exit; the renderer calls `remove` when it has animated out. */
  dismiss = (id?: string): void => {
    const targets = id ? [id] : this.toasts.map((t) => t.id);
    const closing = new Set<string>();
    for (const target of targets) {
      const toast = this.toasts.find((t) => t.id === target);
      if (!toast || toast.state === 'closing') continue;
      this.clearTimer(target);
      closing.add(target);
      setTimeout(() => this.remove(target), CLOSING_GRACE);
    }
    if (closing.size) {
      this.toasts = this.toasts.map((t) => (closing.has(t.id) ? { ...t, state: 'closing' } : t));
      this.emit();
    }
  };

  remove = (id: string): void => {
    if (!this.toasts.some((t) => t.id === id)) return;
    this.clearTimer(id);
    this.toasts = this.toasts.filter((t) => t.id !== id);
    this.emit();
  };

  /** Hold every timer while the reader is looking at the stack. */
  pause = (): void => {
    if (this.paused) return;
    this.paused = true;
    const now = Date.now();
    for (const [id, timer] of this.timers) {
      if (timer.handle) clearTimeout(timer.handle);
      this.timers.set(id, { handle: null, remaining: Math.max(0, timer.remaining - (now - timer.startedAt)), startedAt: now });
    }
  };

  resume = (): void => {
    if (!this.paused) return;
    this.paused = false;
    for (const [id, timer] of this.timers) this.startTimer(id, Math.max(timer.remaining, 1200));
  };

  private startTimer(id: string, duration: number) {
    if (this.paused) {
      this.timers.set(id, { handle: null, remaining: duration, startedAt: Date.now() });
      return;
    }
    const handle = setTimeout(() => this.dismiss(id), duration);
    this.timers.set(id, { handle, remaining: duration, startedAt: Date.now() });
  }

  private clearTimer(id: string) {
    const timer = this.timers.get(id);
    if (timer?.handle) clearTimeout(timer.handle);
    this.timers.delete(id);
  }

  private emit() {
    for (const listener of this.listeners) listener(this.toasts);
  }
}

export const toasts = new ToastStore();

type Shortcut = (title: string, options?: Omit<ToastInput, 'title' | 'tone'>) => string;

const shortcut = (tone: ToastTone): Shortcut => (title, options = {}) => toasts.show({ ...options, title, tone });

/** `toast('Saved')`, `toast.success(…)`, `toast.error(…)`, `toast.dismiss(id)`. */
export const toast = Object.assign((input: ToastInput | string) => toasts.show(typeof input === 'string' ? { title: input } : input), {
  success: shortcut('success'),
  error: shortcut('danger'),
  warning: shortcut('warning'),
  info: shortcut('info'),
  dismiss: (id?: string) => toasts.dismiss(id),
});

/**
 * Lay out a rendered toast list: index each open toast (newest first), mark the
 * front one, and write the offsets the expanded stack uses. Call after every
 * render and when a toast changes size.
 */
export function stackToasts(list: HTMLElement, gap = 12): void {
  const items = Array.from(list.querySelectorAll<HTMLElement>(':scope > .nx-toast'));
  let offset = 0;
  let index = 0;
  let frontHeight = 0;

  for (const item of items) {
    if (item.getAttribute('data-state') === 'closing') continue;
    // Natural height even while collapsed to the front toast's height.
    const height = item.scrollHeight + (item.offsetHeight - item.clientHeight);
    if (index === 0) frontHeight = height;
    item.style.setProperty('--i', String(index));
    item.style.setProperty('--nx-offset', `${offset}px`);
    item.toggleAttribute('data-front', index === 0);
    offset += height + gap;
    index += 1;
  }

  list.style.setProperty('--nx-front-height', `${frontHeight}px`);
  for (const item of items) item.style.setProperty('--nx-front-height', `${frontHeight}px`);
}
