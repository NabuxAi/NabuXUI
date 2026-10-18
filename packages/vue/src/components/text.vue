<script lang="ts">
/**
 * خانوادهٔ متنی @nabuxai/ui-vue — همتای components/text.tsx در @nabuxai/ui-react:
 * همان DOM، همان کلاس‌های nx-* و همان props (با قرارداد Vue: v-model و camelCase).
 *
 * این صندلی چند کامپوننت را از یک فایل صادر می‌کند؛ چون index.ts با
 * `export *` فقط صادرات‌های «نام‌دار» را منتقل می‌کند (و default را نه)، همهٔ
 * کامپوننت‌ها اینجا با defineComponent + setup صادر می‌شوند — همان چیزی که
 * `<script setup>` به آن کامپایل می‌شود، فقط با نام. صادرات default صندلی،
 * NxReveal است.
 */
import {
  Text,
  computed,
  defineComponent,
  h,
  onBeforeUnmount,
  onMounted,
  reactive,
  ref,
  watch,
  type PropType,
  type Ref,
  type VNode,
} from 'vue';
import {
  type Cleanup,
  type SplitBy,
  glyphs,
  isBrowser,
  localeDigits,
  localeOf,
  numberParts,
  prefersReducedMotion,
  reveal,
  scramble,
  splitText,
  textDirection,
  type RevealOptions as CoreRevealOptions,
} from '@nabuxai/ui-core';
import { useNxBehavior } from '../internal/composable';
import { INTL_LOCALES, cx } from '../internal/utils';
import type { NxLang } from '../i18n';

/** متنِ سادهٔ یک slot — همتای `children: string` در React. */
const slotText = (slot: (() => VNode[]) | undefined): string => {
  const vnodes = slot?.() ?? [];
  let out = '';
  for (const vnode of vnodes) {
    if (vnode.type === Text) out += String(vnode.children ?? '');
    else if (typeof vnode.children === 'string') out += vnode.children;
  }
  return out;
};

/** زبان صندلی: prop «lang»، وگرنه نزدیک‌ترین [lang] در DOM، وگرنه en. */
const domLang = (el: Element | null | undefined, prop?: NxLang): NxLang => {
  if (prop) return prop;
  const found = localeOf(el);
  return found === 'fa' ? 'fa' : 'en';
};

const domStyle = (extra: Record<string, string> | undefined, user: unknown): unknown[] => [extra, user];

/* ---- Reveal ----------------------------------------------------------------- */

export type RevealVariant = 'rise' | 'fade' | 'scale' | 'blur' | 'start' | 'end';

export interface RevealOptions {
  /** How it arrives. */
  variant?: RevealVariant;
  /** Stagger direct children (each gets --nx-i). */
  stagger?: boolean;
  /** Extra delay in ms. */
  delay?: number;
  /** Reveal again each time it re-enters the view. */
  repeat?: boolean;
}

/** همتای Vue برای useReveal در ui-react — روی رفرِ داده‌شده، رفتار reveal را می‌بندد. */
export function useReveal(el: Ref<Element | null | undefined>, { stagger, repeat }: Pick<RevealOptions, 'stagger' | 'repeat'> = {}, enabled = true): void {
  let cleanup: Cleanup | undefined;
  const attach = () => {
    cleanup?.();
    cleanup = undefined;
    if (enabled && el.value) cleanup = reveal(el.value, { stagger, once: !repeat });
  };
  onMounted(attach);
  watch(() => el.value, attach, { flush: 'post' });
  onBeforeUnmount(() => cleanup?.());
}

export const NxReveal = /* @__PURE__ */ defineComponent({
  name: 'NxReveal',
  inheritAttrs: false,
  props: {
    as: { type: String, default: 'div' },
    variant: { type: String as PropType<RevealVariant>, default: 'rise' },
    stagger: { type: Boolean, default: false },
    delay: { type: Number, default: undefined },
    repeat: { type: Boolean, default: false },
  },
  setup(props, { attrs, slots }) {
    // گزینه‌ها reactive نگه داشته می‌شوند تا useNxBehavior (که با JSON مقایسه
    // می‌کند) با تغییر stagger/repeat دوباره متصل شود.
    const options = reactive<{ stagger: boolean; once: boolean }>({ stagger: props.stagger, once: !props.repeat });
    watch(
      () => [props.stagger, props.repeat] as const,
      ([stagger, repeat]) => {
        options.stagger = stagger;
        options.once = !repeat;
      },
    );
    const seat = useNxBehavior<Element, CoreRevealOptions>(reveal, options);

    return () =>
      h(
        props.as,
        {
          ...attrs,
          ref: seat,
          'data-nx-reveal': props.stagger ? 'group' : props.variant,
          style: domStyle(props.delay ? { '--nx-delay': `${props.delay}ms` } : undefined, attrs.style),
        },
        slots.default?.(),
      );
  },
});

/* ---- Gradient & shimmer ----------------------------------------------------- */

export interface GradientTextProps {
  variant?: 'text' | 'brand' | 'gold';
  /** Let the gradient drift slowly. */
  animate?: boolean;
}

export const NxGradientText = /* @__PURE__ */ defineComponent({
  name: 'NxGradientText',
  inheritAttrs: false,
  props: {
    as: { type: String, default: 'span' },
    variant: { type: String as PropType<'text' | 'brand' | 'gold'>, default: 'text' },
    animate: { type: Boolean, default: false },
  },
  setup(props, { attrs, slots }) {
    return () =>
      h(
        props.as,
        {
          ...attrs,
          class: cx('nx-gradient-text', attrs.class as string | undefined),
          'data-variant': props.variant === 'text' ? undefined : props.variant,
          'data-animate': props.animate ? '' : undefined,
        },
        slots.default?.(),
      );
  },
});

export interface ShimmerTextProps {
  /** A band of light passing over (sweep) or letters rising in a wave. */
  variant?: 'sweep' | 'wave';
  /** Seconds per pass. */
  duration?: number;
}

export const NxShimmerText = /* @__PURE__ */ defineComponent({
  name: 'NxShimmerText',
  inheritAttrs: false,
  props: {
    as: { type: String, default: 'span' },
    variant: { type: String as PropType<'sweep' | 'wave'>, default: 'sweep' },
    duration: { type: Number, default: undefined },
  },
  setup(props, { attrs, slots }) {
    const text = computed(() => slotText(slots.default));
    const pieces = computed(() => (props.variant === 'wave' ? splitText(text.value, 'char') : []));

    return () => {
      const vars = props.duration ? { '--nx-shimmer-duration': `${props.duration}s` } : undefined;

      if (props.variant === 'sweep') {
        return h(props.as, { ...attrs, class: cx('nx-shimmer', attrs.class as string | undefined), style: domStyle(vars, attrs.style) }, slots.default?.());
      }

      return h(props.as, { ...attrs, class: cx('nx-shimmer', attrs.class as string | undefined), 'data-variant': 'wave', style: domStyle(vars, attrs.style) }, [
        h('span', { class: 'nx-visually-hidden' }, text.value),
        h(
          'span',
          { 'aria-hidden': 'true', dir: textDirection(text.value) },
          pieces.value.map((piece, i) => h('span', { class: 'nx-text-piece', style: { '--nx-i': String(i) } }, piece)),
        ),
      ]);
    };
  },
});

/* ---- Text reveal ---------------------------------------------------------- */

export interface TextRevealProps {
  /** Split into words or characters (Persian and Arabic always split by word). */
  by?: SplitBy;
  /** Play once when it enters the view, or brighten word by word as it scrolls. */
  trigger?: 'view' | 'scroll';
  /** Delay before the first piece, ms. */
  delay?: number;
}

export const NxTextReveal = /* @__PURE__ */ defineComponent({
  name: 'NxTextReveal',
  inheritAttrs: false,
  props: {
    as: { type: String, default: 'span' },
    by: { type: String as PropType<SplitBy>, default: 'word' },
    trigger: { type: String as PropType<'view' | 'scroll'>, default: 'view' },
    delay: { type: Number, default: undefined },
  },
  setup(props, { attrs, slots }) {
    const text = computed(() => slotText(slots.default));
    const pieces = computed(() => splitText(text.value, props.by));
    const seat = useNxBehavior<Element, CoreRevealOptions>(reveal, { once: true }, props.trigger === 'view');

    return () =>
      h(
        props.as,
        {
          ...attrs,
          ref: seat,
          class: cx('nx-text-reveal', attrs.class as string | undefined),
          'data-by': props.by,
          'data-trigger': props.trigger,
          'data-nx-reveal': props.trigger === 'view' ? '' : undefined,
          style: domStyle(props.delay ? { '--nx-delay': `${props.delay}ms` } : undefined, attrs.style),
        },
        [
          h('span', { class: 'nx-visually-hidden' }, text.value),
          // Pieces are boxes, laid out in their container's direction: give it the text's own.
          h(
            'span',
            { class: 'nx-text-pieces', 'aria-hidden': 'true', dir: textDirection(text.value) },
            pieces.value.map((piece, i) => h('span', { class: 'nx-text-piece', style: { '--nx-i': String(i) } }, piece)),
          ),
        ],
      );
  },
});

/* ---- Scramble -------------------------------------------------------------- */

export interface ScrambleTextProps {
  /** When to decode: on mount, when scrolled into view, or on hover. */
  trigger?: 'mount' | 'view' | 'hover';
  duration?: number;
  /** Glyphs to cycle: latin, persian, cuneiform, or your own string. */
  charset?: keyof typeof glyphs | string;
}

export const NxScrambleText = /* @__PURE__ */ defineComponent({
  name: 'NxScrambleText',
  inheritAttrs: false,
  props: {
    as: { type: String, default: 'span' },
    trigger: { type: String as PropType<'mount' | 'view' | 'hover'>, default: 'view' },
    duration: { type: Number, default: undefined },
    charset: { type: String, default: undefined },
  },
  setup(props, { attrs, slots }) {
    const host = ref<HTMLElement | null>(null);
    const textEl = ref<HTMLSpanElement | null>(null);
    const text = computed(() => slotText(slots.default));
    let stop: Cleanup = () => {};
    let observer: IntersectionObserver | null = null;

    const run = () => {
      if (!textEl.value) return;
      stop();
      stop = scramble(textEl.value, text.value, { duration: props.duration, charset: props.charset });
    };

    const sync = () => {
      observer?.disconnect();
      observer = null;
      if (props.trigger === 'mount') run();
      if (props.trigger !== 'view' || !host.value || typeof IntersectionObserver === 'undefined') return;
      observer = new IntersectionObserver(
        ([entry]) => {
          if (entry?.isIntersecting) {
            run();
            observer?.disconnect();
          }
        },
        { threshold: 0.4 },
      );
      observer.observe(host.value);
    };

    onMounted(sync);
    watch([text, () => props.trigger], sync);
    onBeforeUnmount(() => {
      observer?.disconnect();
      stop();
    });

    return () =>
      h(
        props.as,
        {
          ...attrs,
          ref: host,
          class: cx('nx-scramble', attrs.class as string | undefined),
          onPointerenter: [
            attrs.onPointerenter as ((event: PointerEvent) => void) | undefined,
            () => {
              if (props.trigger === 'hover') run();
            },
          ],
        },
        [h('span', { class: 'nx-visually-hidden' }, text.value), h('span', { ref: textEl, 'aria-hidden': 'true' }, text.value)],
      );
  },
});

/* ---- Rotating words ------------------------------------------------------------ */

export interface WordRotateProps {
  words: string[];
  /** Time each word stays, ms. */
  interval?: number;
}

export const NxWordRotate = /* @__PURE__ */ defineComponent({
  name: 'NxWordRotate',
  inheritAttrs: false,
  props: {
    words: { type: Array as PropType<string[]>, required: true },
    interval: { type: Number, default: 2600 },
  },
  setup(props, { attrs }) {
    const index = ref(0);
    const previous = ref<number | null>(null);
    const width = ref<number | null>(null);
    const paused = ref(false);
    const current = ref<HTMLSpanElement | null>(null);
    let id: ReturnType<typeof setInterval> | undefined;

    const sync = () => {
      if (id !== undefined) clearInterval(id);
      id = undefined;
      if (props.words.length < 2 || paused.value) return;
      id = setInterval(() => {
        if (document.hidden) return;
        previous.value = index.value;
        index.value = (index.value + 1) % props.words.length;
      }, props.interval);
    };

    onMounted(sync);
    watch([() => props.words.length, () => props.interval, paused], sync);
    onBeforeUnmount(() => {
      if (id !== undefined) clearInterval(id);
    });

    const measure = () => {
      if (current.value) width.value = current.value.scrollWidth;
    };
    onMounted(measure);
    watch([index, () => props.words], measure, { flush: 'post' });

    return () =>
      h(
        'span',
        {
          ...attrs,
          class: cx('nx-word-rotate', attrs.class as string | undefined),
          style: domStyle(width.value ? { '--nx-word-width': `${width.value}px` } : undefined, attrs.style),
          onPointerenter: () => {
            paused.value = true;
          },
          onPointerleave: () => {
            paused.value = false;
          },
        },
        [
          h('span', { class: 'nx-visually-hidden' }, props.words.join('، ')),
          previous.value !== null && previous.value !== index.value
            ? h(
                'span',
                {
                  key: `out-${previous.value}-${index.value}`,
                  class: 'nx-word-rotate-word',
                  'data-state': 'exit',
                  'aria-hidden': 'true',
                  onAnimationend: () => {
                    previous.value = null;
                  },
                },
                props.words[previous.value],
              )
            : null,
          h(
            'span',
            { key: `in-${index.value}`, ref: current, class: 'nx-word-rotate-word', 'data-state': previous.value === null && index.value === 0 ? undefined : 'enter', 'aria-hidden': 'true' },
            props.words[index.value],
          ),
        ],
      );
  },
});

/* ---- Rolling number ---------------------------------------------------------- */

export interface NumberTickerProps {
  value: number;
  /** An Intl locale; defaults to the seat's language (en-US for en, fa-IR for fa). */
  locale?: string;
  format?: Intl.NumberFormatOptions;
  /** Roll up from zero when it scrolls into view (default) or show the value at once. */
  reveal?: boolean;
  /** Roll duration, ms. */
  duration?: number;
  /** The seat's language, when no explicit `locale` is given. */
  lang?: NxLang;
}

export const NxNumberTicker = /* @__PURE__ */ defineComponent({
  name: 'NxNumberTicker',
  inheritAttrs: false,
  props: {
    value: { type: Number, required: true },
    locale: { type: String, default: undefined },
    format: { type: Object as PropType<Intl.NumberFormatOptions>, default: undefined },
    reveal: { type: Boolean, default: true },
    duration: { type: Number, default: undefined },
    lang: { type: String as PropType<NxLang>, default: undefined },
  },
  setup(props, { attrs }) {
    const seat = useNxBehavior<Element, CoreRevealOptions>(reveal, { once: true }, props.reveal);
    const intlLocale = computed(() => props.locale ?? INTL_LOCALES[domLang(seat.value, props.lang)]);
    const parts = computed(() => numberParts(props.value, intlLocale.value, props.format));
    const digits = computed(() => localeDigits(intlLocale.value));
    const text = computed(() => parts.value.map((part) => part.char).join(''));

    return () =>
      h(
        'span',
        {
          ...attrs,
          ref: seat,
          class: cx('nx-number', attrs.class as string | undefined),
          'data-nx-reveal': props.reveal ? '' : undefined,
          style: domStyle(props.duration ? { '--nx-number-duration': `${props.duration}ms` } : undefined, attrs.style),
        },
        [
          h('span', { class: 'nx-visually-hidden' }, text.value),
          h(
            'span',
            { class: 'nx-number-roll', 'aria-hidden': 'true' },
            parts.value.map((part, i) => {
              // Keyed from the right so the ones column stays the ones column as the number grows.
              const key = parts.value.length - i;
              return part.kind === 'digit'
                ? h(
                    'span',
                    { key, class: 'nx-digit', style: { '--d': String(part.value), '--nx-p': String(parts.value.length - 1 - i) } },
                    h('span', { class: 'nx-digit-track' }, digits.value.map((digit) => h('span', { key: digit }, digit))),
                  )
                : h('span', { key, class: 'nx-number-sep' }, part.char);
            }),
          ),
        ],
      );
  },
});

/* ---- Highlight ----------------------------------------------------------------- */

const MARKS = {
  underline: 'M3 13 C 45 6, 90 5, 130 8 S 185 13, 197 9',
  circle: 'M104 3 C 44 2, 3 7, 4 12 C 5 17, 58 19, 108 18 C 162 17, 197 14, 196 9 C 195 4, 150 1, 88 5',
} as const;

export interface HighlightProps {
  variant?: 'underline' | 'circle' | 'marker';
  /** Any CSS colour; the brand gold by default. */
  color?: string;
  delay?: number;
}

/** A hand-drawn underline, circle or marker that draws itself when revealed. */
export const NxHighlight = /* @__PURE__ */ defineComponent({
  name: 'NxHighlight',
  inheritAttrs: false,
  props: {
    variant: { type: String as PropType<'underline' | 'circle' | 'marker'>, default: 'underline' },
    color: { type: String, default: undefined },
    delay: { type: Number, default: undefined },
  },
  setup(props, { attrs, slots }) {
    // مثل ui-react: با کاهشِ حرکتِ کاربر، رفتار reveal بسته نمی‌شود.
    const enabled = !isBrowser || !prefersReducedMotion();
    const seat = useNxBehavior<Element, CoreRevealOptions>(reveal, { once: true }, enabled);

    return () =>
      h(
        'span',
        {
          ...attrs,
          ref: seat,
          class: cx('nx-highlight', attrs.class as string | undefined),
          'data-variant': props.variant,
          'data-nx-reveal': '',
          style: domStyle(
            { ...(props.color ? { '--nx-highlight-color': props.color } : null), ...(props.delay ? { '--nx-delay': `${props.delay}ms` } : null) },
            attrs.style,
          ),
        },
        [
          slots.default?.(),
          props.variant !== 'marker'
            ? h('svg', { class: 'nx-highlight-mark', viewBox: '0 0 200 20', preserveAspectRatio: 'none', 'aria-hidden': 'true' }, h('path', { d: MARKS[props.variant], pathLength: 1 }))
            : null,
        ],
      );
  },
});

export default NxReveal;
</script>
