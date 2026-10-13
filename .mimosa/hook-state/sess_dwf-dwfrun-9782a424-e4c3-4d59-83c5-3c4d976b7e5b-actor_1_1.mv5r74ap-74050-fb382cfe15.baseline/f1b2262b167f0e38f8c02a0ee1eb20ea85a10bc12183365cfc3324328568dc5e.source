/**
 * Light / dark theme.
 *
 * The resolved scheme is written three ways so every consumer agrees:
 * `data-theme` (NabuXUI's tokens), the `dark` class (Tailwind's `dark:` and the
 * Nabu Livewire apps' chrome) and `color-scheme` (the browser's own controls).
 * The preference is stored under the same key the Nabu apps already use.
 *
 * Put `themeScript` inline in <head> so the page never paints in the wrong theme.
 */
import { isBrowser } from './env';

export type ThemePreference = 'light' | 'dark' | 'system';
export type Scheme = 'light' | 'dark';

export const THEME_STORAGE_KEY = 'nabu.theme';
export const THEME_EVENT = 'nx-theme-change';

const media = () => window.matchMedia('(prefers-color-scheme: dark)');

function read(): ThemePreference {
  try {
    const stored = localStorage.getItem(THEME_STORAGE_KEY);
    return stored === 'light' || stored === 'dark' ? stored : 'system';
  } catch {
    // Storage can be refused (private mode); the system setting is the answer.
    return 'system';
  }
}

function systemScheme(): Scheme {
  return isBrowser && media().matches ? 'dark' : 'light';
}

function paint(scheme: Scheme) {
  const root = document.documentElement;
  root.dataset.theme = scheme;
  root.classList.toggle('dark', scheme === 'dark');
  root.style.colorScheme = scheme;
  document.querySelector('meta[name="color-scheme"]')?.setAttribute('content', scheme);
}

let listening = false;

export const theme = {
  preference(): ThemePreference {
    return isBrowser ? read() : 'system';
  },

  resolved(): Scheme {
    if (!isBrowser) return 'light';
    const preference = read();
    return preference === 'system' ? systemScheme() : preference;
  },

  set(preference: ThemePreference): void {
    if (!isBrowser) return;
    try {
      if (preference === 'system') localStorage.removeItem(THEME_STORAGE_KEY);
      else localStorage.setItem(THEME_STORAGE_KEY, preference);
    } catch {
      /* not persisted, still applied */
    }
    this.apply();
  },

  /**
   * Flip what the reader sees. Choosing the scheme the system already uses
   * stores nothing, so the page keeps following the system from then on.
   */
  toggle(): Scheme {
    const next: Scheme = this.resolved() === 'dark' ? 'light' : 'dark';
    this.set(next === systemScheme() ? 'system' : next);
    return next;
  },

  apply(): void {
    if (!isBrowser) return;
    const scheme = this.resolved();
    paint(scheme);
    window.dispatchEvent(new CustomEvent(THEME_EVENT, { detail: { scheme, preference: read() } }));
  },

  /** Follow system changes and other tabs; returns an unsubscribe. */
  watch(callback?: (scheme: Scheme) => void): () => void {
    if (!isBrowser) return () => {};
    const onChange = () => {
      this.apply();
    };
    const onEvent = (event: Event) => callback?.((event as CustomEvent<{ scheme: Scheme }>).detail.scheme);
    const onStorage = (event: StorageEvent) => {
      if (event.key === THEME_STORAGE_KEY) this.apply();
    };
    if (!listening) {
      media().addEventListener('change', onChange);
      window.addEventListener('storage', onStorage);
      listening = true;
    }
    window.addEventListener(THEME_EVENT, onEvent);
    return () => window.removeEventListener(THEME_EVENT, onEvent);
  },
};

/**
 * Inline this in <head> (before any stylesheet paints) to set the theme and mark
 * the page as scripted before the first frame. It is plain ES5 on purpose.
 */
export const themeScript = `(function(){var r=document.documentElement;r.classList.add('nx-js');try{var m=window.matchMedia('(prefers-color-scheme: dark)');var a=function(){var p=null;try{p=localStorage.getItem('${THEME_STORAGE_KEY}')}catch(e){}var d=p==='dark'||(p!=='light'&&m.matches);r.setAttribute('data-theme',d?'dark':'light');r.classList.toggle('dark',d);r.style.colorScheme=d?'dark':'light'};a();m.addEventListener&&m.addEventListener('change',a)}catch(e){}})();`;
