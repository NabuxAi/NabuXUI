/**
 * The shared kit this flavour's component parts are built on — the counterpart
 * of ui-react's `internal/provider.tsx` plus the small render and state helpers
 * the SFC parts need: the settings injection (locale + router link), the
 * DOM-language translator, SmartLink, a node renderer for VueNode props, the
 * controllable-model pair and the moving-highlight controller.
 */
import {
  computed,
  defineComponent,
  h,
  inject,
  onBeforeUnmount,
  onMounted,
  ref,
  toValue,
  type Component,
  type ComputedRef,
  type FunctionalComponent,
  type InjectionKey,
  type ModelRef,
  type Ref,
} from 'vue';
import { type IndicatorController, type Locale, type MessageKey, indicator, localeOf } from '@nabuxai/ui-core';
import { nxTranslate, type NxLang } from '../i18n';
import type { VueNode } from './utils';

/* ---- Settings injection ----------------------------------------------------- */

export interface NxSettings {
  /** The language the components' own words speak; defaults to the nearest `lang` attribute. */
  locale?: () => NxLang;
  /** Anything that renders an `<a>` (vue-router's RouterLink, Inertia's Link) for internal paths. */
  link?: Component;
}

export const NX_SETTINGS_KEY: InjectionKey<NxSettings> = Symbol('nabuxai-settings');

/** The language a component speaks: the injected override, else the nearest `lang` attribute. */
export function useNxLocale(el?: Ref<Element | null> | (() => Element | null)): ComputedRef<NxLang> {
  const settings = inject(NX_SETTINGS_KEY, undefined);
  return computed<NxLang>(() => {
    const override = settings?.locale?.();
    if (override) return override;
    // The flavour sells fa/en; an Arabic page lands on the closer right-to-left table.
    return localeOf(toValue(el ?? null)) === 'en' ? 'en' : 'fa';
  });
}

/** `t('close')`, `t('page', { page: 2 })` — the Vue counterpart of ui-react's `useT`. */
export function useNxT(el?: Ref<Element | null> | (() => Element | null)): (key: MessageKey, params?: Record<string, string | number>) => string {
  const lang = useNxLocale(el);
  return (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(lang.value, key, params);
}

const isExternal = (href: string) => /^(https?:)?\/\//.test(href) || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('#');

/**
 * An anchor that routes through the injected link component for internal paths
 * and stays a plain `<a>` for external, mail and in-page links.
 */
export const NxSmartLink = defineComponent({
  name: 'NxSmartLink',
  props: { href: { type: String, required: true } },
  setup(props, { attrs, slots }) {
    const settings = inject(NX_SETTINGS_KEY, undefined);
    return () => {
      const link = settings?.link;
      return link && !isExternal(props.href)
        ? h(link, { href: props.href, ...attrs }, slots)
        : h('a', { href: props.href, ...attrs }, slots);
    };
  },
});

/* ---- Rendering --------------------------------------------------------------- */

/** Paint a VueNode prop (string, number, vnode or array) inside a template. */
export const NxNode: FunctionalComponent<{ node?: VueNode }> = (props) => {
  const node = props.node;
  if (node == null) return null;
  return typeof node === 'string' || typeof node === 'number' ? String(node) : node;
};
NxNode.props = ['node'];

/* ---- State ------------------------------------------------------------------- */

/**
 * State that is controlled when the model is bound and internal otherwise —
 * the value / defaultValue / onChange convention every stateful part uses,
 * over a `defineModel` seat.
 */
export function useControllableModel<T>(model: ModelRef<T | undefined>, initial: () => T): { current: ComputedRef<T>; set: (value: T) => void } {
  const inner = ref(initial()) as Ref<T>;
  const current = computed(() => model.value ?? inner.value);
  const set = (value: T) => {
    inner.value = value;
    model.value = value;
  };
  return { current, set };
}

/* ---- The moving highlight ----------------------------------------------------- */

/** The core `indicator` on `container`, for as long as it is mounted. */
export function useNxIndicator<E extends HTMLElement>(container: Ref<E | null>): Ref<IndicatorController | null> {
  const ctrl = ref<IndicatorController | null>(null);
  onMounted(() => {
    if (container.value) ctrl.value = indicator(container.value);
  });
  onBeforeUnmount(() => {
    ctrl.value?.destroy();
    ctrl.value = null;
  });
  return ctrl;
}
