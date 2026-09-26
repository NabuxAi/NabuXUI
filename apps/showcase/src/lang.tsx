import { createContext, useContext } from 'react';

export type Lang = 'fa' | 'en';

export const LangContext = createContext<Lang>('fa');

/** `tr('ذخیره', 'Save')` — the showcase speaks Persian first, English second. */
export function useTr() {
  const lang = useContext(LangContext);
  return (fa: string, en: string) => (lang === 'fa' ? fa : en);
}

export function useLang() {
  return useContext(LangContext);
}
