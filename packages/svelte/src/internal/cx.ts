import type { ClassValue } from 'svelte/elements';

/**
 * Join class names, skipping falsy parts — the Svelte counterpart of the same
 * one-liner ui-react keeps in internal/hooks. Accepts the wide ClassValue the
 * svelte/elements attribute types hand out (arrays flatten, non-strings drop).
 */
export function cx(...parts: Array<ClassValue | false | null | undefined>): string {
  const out: string[] = [];
  const walk = (value: ClassValue | false | null | undefined): void => {
    if (!value) return;
    if (Array.isArray(value)) {
      for (const item of value) walk(item);
    } else if (typeof value === 'string' && value.length > 0) {
      out.push(value);
    }
  };
  for (const part of parts) walk(part);
  return out.join(' ');
}
