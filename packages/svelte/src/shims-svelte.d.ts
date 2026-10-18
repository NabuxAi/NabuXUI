// The declaration pass (tsconfig.emit.json) runs with plain tsc, which cannot
// parse Svelte files. This wildcard ambient module — the same shape
// svelte-check's own shims give the check pass — describes every stub until
// the components grow real props. It is deliberately excluded from
// tsconfig.json so it never collides with svelte-check's shims.
declare module '*.svelte' {
  import type { Component } from 'svelte';
  const component: Component<Record<string, any>>;
  export default component;
}
