import { createContext, useContext } from 'react';

export type Lang = 'fa' | 'en';

export const LangContext = createContext<Lang>('fa');

/** The framework whose code the showcase's snippets are showing right now. */
export type Framework = 'react' | 'inertia' | 'livewire' | 'vue' | 'svelte';

/** The switcher's options, in header order; the labels stay Latin, like the code itself. */
export const FRAMEWORKS: ReadonlyArray<{ value: Framework; label: string }> = [
  { value: 'react', label: 'React' },
  { value: 'inertia', label: 'Inertia' },
  { value: 'livewire', label: 'Livewire' },
  { value: 'vue', label: 'Vue' },
  { value: 'svelte', label: 'Svelte' },
];

export interface FrameworkState {
  framework: Framework;
  setFramework: (framework: Framework) => void;
}

export const FrameworkContext = createContext<FrameworkState>({ framework: 'react', setFramework: () => undefined });

/** `tr('ذخیره', 'Save')` — the showcase speaks Persian first, English second. */
export interface Tr {
  <T extends string>(fa: T, en: T): string;
  <T extends string[]>(fa: T, en: T): string[];
}

export function useTr(): Tr {
  const lang = useContext(LangContext);
  return <T extends string | string[]>(fa: T, en: T): T =>
    (lang === 'fa' ? fa : en);
}

export function useLang() {
  return useContext(LangContext);
}

export function useFramework() {
  return useContext(FrameworkContext);
}

/** Saved pick — the readLang pattern from App.tsx: localStorage first, safe default when storage is closed. */
export function readFramework(): Framework {
  try {
    const saved = localStorage.getItem('nabuxui.showcase.framework');
    return FRAMEWORKS.some((option) => option.value === saved) ? (saved as Framework) : 'react';
  } catch {
    return 'react';
  }
}
