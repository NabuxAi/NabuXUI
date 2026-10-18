<script lang="ts">
/**
 * خانوادهٔ دکمهٔ @nabuxai/ui-vue — همتای components/button.tsx در @nabuxai/ui-react:
 * همان DOM، همان کلاس‌های nx-* و همان props (با قرارداد Vue: v-model:liked و camelCase).
 *
 * همهٔ کامپوننت‌ها صادرات نام‌دارند (index.ts با export * فقط نام‌دارها را
 * منتقل می‌کند)؛ صادرات default صندلی، NxButton است.
 */
import { computed, defineComponent, h, onBeforeUnmount, ref, watch, type PropType, type VNode } from 'vue';
import {
  type IconName,
  type MagneticOptions,
  type MessageKey,
  burst,
  copyText,
  localeOf,
  magnetic as magneticBehavior,
  ripple as rippleBehavior,
} from '@nabuxai/ui-core';
import { NxIcon } from '../internal/icon';
import { cx, useNxBehaviorReactive } from '../internal/utils';
import { nxTranslate, type NxLang } from '../i18n';
import { NxNumberTicker } from './text.vue';

export type ButtonVariant = 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger' | 'gold' | 'glow' | 'inverse' | 'link';
export type ButtonSize = 'xs' | 'sm' | 'md' | 'lg' | 'xl';
export type ButtonStatus = 'idle' | 'loading' | 'success' | 'error';

/** متنِ سادهٔ یک slot — همتای `typeof children === 'string'` در React. */
const slotText = (slot: (() => VNode[]) | undefined): string => {
  const vnodes = slot?.() ?? [];
  let out = '';
  for (const vnode of vnodes) {
    if (typeof vnode.children === 'string') out += vnode.children;
    else if (Array.isArray(vnode.children)) {
      for (const child of vnode.children) {
        if (child && typeof child === 'object' && typeof (child as VNode).children === 'string') out += (child as VNode).children as string;
      }
    }
  }
  return out;
};

/** زبان صندلی: prop «lang»، وگرنه نزدیک‌ترین [lang] در DOM، وگرنه en. */
const domLang = (el: Element | null | undefined, prop?: NxLang): NxLang => {
  if (prop) return prop;
  return localeOf(el) === 'fa' ? 'fa' : 'en';
};

const renderIcon = (icon: string | VNode): VNode => (typeof icon === 'string' ? h(NxIcon, { name: icon as IconName }) : icon);

/** The status layer: all three marks are present, CSS shows the current one. */
export const NxButtonStatusMarks = /* @__PURE__ */ defineComponent({
  name: 'NxButtonStatusMarks',
  setup: () => () =>
    h('span', { class: 'nx-button-status', 'aria-hidden': 'true' }, [
      h('span', { class: 'nx-spinner', 'data-size': 'sm' }),
      h(
        'svg',
        { class: 'nx-icon nx-status-check', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2.5', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' },
        [h('path', { d: 'M5 12.5l4.5 4.5L19 7.5', pathLength: 1 })],
      ),
      h(
        'svg',
        { class: 'nx-icon nx-status-cross', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2.5', 'stroke-linecap': 'round' },
        [h('path', { d: 'M7 7l10 10M17 7L7 17', pathLength: 1 })],
      ),
    ]),
});

export const NxButton = /* @__PURE__ */ defineComponent({
  name: 'NxButton',
  inheritAttrs: false,
  props: {
    variant: { type: String as PropType<ButtonVariant>, default: 'secondary' },
    size: { type: String as PropType<ButtonSize>, default: 'md' },
    shape: { type: String as PropType<'rounded' | 'pill' | 'square'>, default: undefined },
    /** Morphs the label into a spinner, a check or a cross without changing the width. */
    status: { type: String as PropType<ButtonStatus>, default: undefined },
    /** Shorthand for status="loading". */
    loading: { type: Boolean, default: false },
    /** A sheen on hover, or the label sliding out for its copy. */
    effect: { type: String as PropType<'shine' | 'slide'>, default: undefined },
    /** Lean toward the pointer. */
    magnetic: { type: Boolean, default: false },
    /** A ripple from the press point. */
    ripple: { type: Boolean, default: false },
    /** Leading icon: a built-in icon name or any node. */
    icon: { type: [String, Object] as PropType<string | VNode>, default: undefined },
    iconEnd: { type: [String, Object] as PropType<string | VNode>, default: undefined },
    /** Square button holding only an icon; give it an aria-label. */
    iconOnly: { type: Boolean, default: false },
    block: { type: Boolean, default: false },
    /** Render as a link. */
    href: { type: String, default: undefined },
    type: { type: String as PropType<'button' | 'submit' | 'reset'>, default: undefined },
    disabled: { type: Boolean, default: undefined },
  },
  setup(props, { attrs, slots }) {
    // دو رفتار روی یک المان: هر صندلی رفر خودش را برمی‌گرداند؛ ref تابعی هر دو را پر می‌کند.
    const magneticSeat = useNxBehaviorReactive<HTMLElement, MagneticOptions>(magneticBehavior, {}, () => props.magnetic);
    const rippleSeat = useNxBehaviorReactive<HTMLElement, undefined>(rippleBehavior, undefined, () => props.ripple);
    const bothSeats = (node: unknown) => {
      const el = (node ?? null) as HTMLElement | null;
      magneticSeat.value = el;
      rippleSeat.value = el;
    };

    const resolved = computed<ButtonStatus>(() => (props.loading ? 'loading' : (props.status ?? 'idle')));
    const busy = computed(() => resolved.value === 'loading');
    const text = computed(() => slotText(slots.default));

    const label = () => {
      const content = slots.default?.();
      const has = !!content?.length;
      return h(
        'span',
        {
          class: 'nx-button-label',
          ...(props.effect === 'slide' && text.value ? { 'data-text': text.value } : {}),
        },
        [
          props.icon ? renderIcon(props.icon) : null,
          has ? (props.iconOnly ? h('span', { class: 'nx-visually-hidden' }, content) : h('span', { class: 'nx-button-text' }, content)) : null,
          props.iconEnd ? renderIcon(props.iconEnd) : null,
        ],
      );
    };

    const shared = (): Record<string, unknown> => ({
      class: cx('nx-button', attrs.class as string | undefined),
      'data-variant': props.variant,
      'data-size': props.size === 'md' ? undefined : props.size,
      'data-shape': props.shape && props.shape !== 'rounded' ? props.shape : undefined,
      'data-status': resolved.value === 'idle' ? undefined : resolved.value,
      'data-effect': props.effect,
      'data-icon-only': props.iconOnly ? '' : undefined,
      'data-block': props.block ? '' : undefined,
      'aria-busy': busy.value || undefined,
    });

    return () => {
      if (props.href) {
        return h(
          'a',
          {
            ...attrs,
            ...shared(),
            ref: bothSeats,
            href: props.href,
            'aria-disabled': props.disabled || undefined,
            onClick: attrs.onClick as ((event: Event) => void) | undefined,
          },
          [label(), h(NxButtonStatusMarks)],
        );
      }

      return h(
        'button',
        {
          ...attrs,
          ...shared(),
          ref: bothSeats,
          type: props.type ?? 'button',
          disabled: props.disabled,
          // Busy buttons stay focusable (disabled would drop focus) but ignore presses.
          'aria-disabled': busy.value || undefined,
          onClick: (event: Event) => {
            if (busy.value) {
              event.preventDefault();
              return;
            }
            (attrs.onClick as ((event: Event) => void) | undefined)?.(event);
          },
        },
        [label(), h(NxButtonStatusMarks)],
      );
    };
  },
});

export interface IconButtonProps {
  icon: string | VNode;
  /** The accessible name — required, since there is no visible text. */
  label: string;
}

export const NxIconButton = /* @__PURE__ */ defineComponent({
  name: 'NxIconButton',
  inheritAttrs: false,
  props: {
    icon: { type: [String, Object] as PropType<string | VNode>, required: true },
    label: { type: String, required: true },
    variant: { type: String as PropType<ButtonVariant>, default: 'ghost' },
  },
  setup(props, { attrs, slots }) {
    return () => h(NxButton, { ...attrs, icon: props.icon, iconOnly: true, variant: props.variant, 'aria-label': props.label, title: props.label }, slots);
  },
});

/* ---- Copy ------------------------------------------------------------------- */

export interface CopyButtonProps {
  /** The text that goes to the clipboard. */
  value: string;
  /** How long the check stays, ms. */
  timeout?: number;
  /** The seat's language for its own words. */
  lang?: NxLang;
}

export const NxCopyButton = /* @__PURE__ */ defineComponent({
  name: 'NxCopyButton',
  inheritAttrs: false,
  props: {
    value: { type: String, required: true },
    timeout: { type: Number, default: 1600 },
    variant: { type: String as PropType<ButtonVariant>, default: 'ghost' },
    lang: { type: String as PropType<NxLang>, default: undefined },
  },
  emits: ['copied'],
  setup(props, { attrs, slots, emit }) {
    const copied = ref(false);
    const root = ref<HTMLElement | null>(null);
    let timer: ReturnType<typeof setTimeout> | undefined;

    watch([copied, () => props.timeout], ([on]) => {
      if (timer !== undefined) clearTimeout(timer);
      timer = undefined;
      if (!on) return;
      timer = setTimeout(() => {
        copied.value = false;
      }, props.timeout);
    });
    onBeforeUnmount(() => {
      if (timer !== undefined) clearTimeout(timer);
    });

    const t = (key: MessageKey, params: Record<string, string | number> = {}): string => nxTranslate(props.lang ?? domLang(root.value), key, params);

    return () => {
      const content = slots.default?.();
      const has = !!content?.length;
      return h(
        NxButton,
        {
          ...attrs,
          ref: (node: unknown) => {
            root.value = ((node as { $el?: HTMLElement } | null)?.$el as HTMLElement | undefined) ?? null;
          },
          variant: props.variant,
          class: cx('nx-copy', attrs.class as string | undefined),
          'data-copied': copied.value ? '' : undefined,
          iconOnly: !has,
          'aria-label': has ? undefined : t('copy'),
          icon: h('span', { class: 'nx-copy-icons', 'aria-hidden': 'true' }, [h(NxIcon, { name: 'copy', class: 'nx-copy-idle' }), h(NxIcon, { name: 'check', class: 'nx-copy-done' })]),
          onClick: async () => {
            if (await copyText(props.value)) {
              copied.value = true;
              emit('copied');
            }
          },
        },
        {
          default: () => [
            ...(content ?? []),
            h('span', { class: 'nx-visually-hidden', 'aria-live': 'polite' }, copied.value ? t('copied') : ''),
          ],
        },
      );
    };
  },
});

/* ---- Like ----------------------------------------------------------------------- */

export interface LikeButtonProps {
  liked?: boolean;
  defaultLiked?: boolean;
  /** Count shown beside the heart (without this press). */
  count?: number;
  label?: string;
  /** The seat's language for its own words. */
  lang?: NxLang;
}

export const NxLikeButton = /* @__PURE__ */ defineComponent({
  name: 'NxLikeButton',
  inheritAttrs: false,
  props: {
    liked: { type: Boolean, default: undefined },
    defaultLiked: { type: Boolean, default: false },
    count: { type: Number, default: undefined },
    label: { type: String, default: undefined },
    variant: { type: String as PropType<ButtonVariant>, default: 'ghost' },
    lang: { type: String as PropType<NxLang>, default: undefined },
  },
  emits: ['update:liked'],
  setup(props, { attrs, slots, emit }) {
    const inner = ref(props.defaultLiked);
    const on = computed(() => props.liked ?? inner.value);
    const root = ref<HTMLElement | null>(null);
    const t = (key: MessageKey): string => nxTranslate(props.lang ?? domLang(root.value), key);

    return () => {
      const count = props.count;
      return h(
        NxButton,
        {
          ...attrs,
          ref: (node: unknown) => {
            root.value = ((node as { $el?: HTMLElement } | null)?.$el as HTMLElement | undefined) ?? null;
          },
          variant: props.variant,
          class: cx('nx-like', attrs.class as string | undefined),
          'aria-pressed': on.value,
          'aria-label': props.label ?? t('like'),
          icon: h(NxIcon, { name: 'heart', class: 'nx-like-heart' }),
          onClick: () => {
            const next = !on.value;
            inner.value = next;
            emit('update:liked', next);
            if (next && root.value) burst(root.value);
          },
        },
        count !== undefined ? { default: () => [h(NxNumberTicker, { value: count + (on.value ? 1 : 0), reveal: false })] } : slots,
      );
    };
  },
});

export default NxButton;
</script>
