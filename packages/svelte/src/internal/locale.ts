/**
 * The language this flavour's components speak their own words in.
 *
 * ui-react reads the locale from NabuXUIProvider's context; Svelte has no
 * provider seat, so the DOM is the provider instead: the nearest `lang`
 * attribute (usually `<html lang="fa">`) decides, exactly like the core's
 * Livewire/Blade components. This flavour sells fa/en — `ar` falls back to
 * the English table the way an unconfigured React app would.
 */
import { localeOf } from '@nabuxai/ui-core';
import type { NxLang } from '../i18n';

/** The page's language, clamped to the languages this flavour carries. */
export function nxLang(): NxLang {
  return localeOf() === 'fa' ? 'fa' : 'en';
}

/** The Intl locale for a page language — the chart/stat `INTL` table's shape. */
export function nxIntlLocale(lang: NxLang = nxLang()): string {
  return lang === 'fa' ? 'fa-IR' : 'en-US';
}
