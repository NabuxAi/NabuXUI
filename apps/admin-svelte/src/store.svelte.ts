/**
 * The panel's tiny reactive state, shared by every component: the language pick
 * (one source for the dictionary, the document attributes and localStorage —
 * the same `nabuxui.admin.lang` key apps/admin uses, so the flavours agree).
 * A `.svelte.ts` module so the runes compile.
 */
import { LANG_KEY, STRINGS, readLang, type Lang, type Strings } from './lang';

export const app = $state({ lang: readLang() });

/** The chat page's live unread counts — the sidebar badge and the page share it. */
export const chat = $state({ unread: { maryam: 2, ali: 1 } as Record<string, number> });

/** The whole dictionary for the current language — call inside a template or $derived. */
export function strings(): Strings {
  return STRINGS[app.lang];
}

export const isFa = () => app.lang === 'fa';

/** `tr('ذخیره', 'Save')` — the panel speaks Persian first, English second. */
export function tr(faText: string, enText: string): string {
  return app.lang === 'fa' ? faText : enText;
}

/** The Intl tag for the current language ('fa-IR' / 'en-US'). */
export const intlLocale = () => (app.lang === 'fa' ? 'fa-IR' : 'en-US');

export const numberFmt = () => new Intl.NumberFormat(intlLocale());

export function setLang(next: Lang): void {
  app.lang = next;
  try {
    localStorage.setItem(LANG_KEY, next);
  } catch {
    /* not persisted */
  }
}
