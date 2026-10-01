/**
 * The panel's tiny reactive stores, shared by every SFC: the language pick
 * (one source for the dictionary, the document attributes and localStorage —
 * the same `nabuxui.admin.lang` key apps/admin uses, so the flavours agree)
 * and the hash route. Both are module-level singletons: the app mounts once.
 */
import { computed, ref } from 'vue';
import { LANG_KEY, STRINGS, readLang, type Lang, type Strings } from './lang';

/* ---- Language ------------------------------------------------------------------------------- */

export const lang = ref<Lang>(readLang());

export const s = computed<Strings>(() => STRINGS[lang.value]);

export const fa = computed(() => lang.value === 'fa');

/** The Intl tag for the current language ('fa-IR' / 'en-US'). */
export const intlLocale = computed(() => (lang.value === 'fa' ? 'fa-IR' : 'en-US'));

export const numberFmt = computed(() => new Intl.NumberFormat(intlLocale.value));

/** `tr('ذخیره', 'Save')` — the panel speaks Persian first, English second. */
export function tr(faText: string, enText: string): string {
  return lang.value === 'fa' ? faText : enText;
}

export function setLang(next: Lang): void {
  lang.value = next;
  try {
    localStorage.setItem(LANG_KEY, next);
  } catch {
    /* not persisted */
  }
}
