/**
 * The fa/en binder on the core i18n table — the two languages the Vue flavour
 * ships, over the same shared `translate` every framework flavour serves.
 */
import { translate, type Locale, type MessageKey } from '@nabuxai/ui-core';

export type NxLang = Extract<Locale, 'fa' | 'en'>;

/** The languages this flavour sells; the core table also carries `ar`. */
export const NX_LANGS = ['fa', 'en'] as const satisfies readonly NxLang[];

/** Translate a core message key in one of this flavour's languages. */
export function nxTranslate(lang: NxLang, key: MessageKey, params: Record<string, string | number> = {}): string {
  return translate(lang, key, params);
}
