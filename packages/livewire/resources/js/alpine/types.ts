/** The small part of Alpine's API the plugin uses (Livewire ships Alpine as window.Alpine). */
export interface DirectiveMeta {
  value: string;
  modifiers: string[];
  expression: string;
}

export interface DirectiveUtils {
  evaluate: (expression: string) => unknown;
  cleanup: (fn: () => void) => void;
}

export interface AlpineLike {
  data(name: string, factory: (...args: never[]) => object): void;
  directive(name: string, handler: (el: HTMLElement, meta: DirectiveMeta, utils: DirectiveUtils) => void): void;
  magic(name: string, fn: (el: HTMLElement) => unknown): void;
  nextTick(fn?: () => void): Promise<void>;
}

/** What Alpine puts on `this` inside an x-data object. */
export interface Magics {
  $el: HTMLElement;
  $root: HTMLElement;
  $refs: Record<string, HTMLElement>;
  $watch: (expression: string, callback: (value: never, old: never) => void) => void;
  $nextTick: (fn?: () => void) => Promise<void>;
  $dispatch: (name: string, detail?: unknown) => void;
  $id: (name: string) => string;
}
