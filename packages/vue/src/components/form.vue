<script lang="ts">
/**
 * خانوادهٔ فرم @nabuxai/ui-vue — همتای components/form.tsx در @nabuxai/ui-react:
 * همان DOM، همان کلاس‌های nx-* و همان props (با قرارداد Vue: v-model و camelCase).
 *
 * همهٔ کامپوننت‌ها صادرات نام‌دارند (index.ts با export * فقط نام‌دارها را
 * منتقل می‌کند)؛ صادرات default صندلی، NxField است.
 */
import {
  computed,
  defineComponent,
  getCurrentInstance,
  h,
  inject,
  onBeforeUnmount,
  onMounted,
  provide,
  ref,
  useId,
  watch,
  type ComputedRef,
  type InjectionKey,
  type PropType,
  type VNode,
} from 'vue';
import { type Cleanup, type IconName, type MessageKey, autogrow, icons, localeOf } from '@nabuxai/ui-core';
import { NxIcon } from '../internal/icon';
import { cx, type VueNode } from '../internal/utils';
import { nxTranslate, type NxLang } from '../i18n';

/** متنِ سادهٔ یک slot — همتای `children: string` در React. */
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

/** آیا والد به این رویداد گوش می‌دهد؟ (همتای `onX &&` در React props) */
const hasListener = (name: string): boolean => !!getCurrentInstance()?.vnode.props?.[name];

/** attrs بدون class/style — وقتی کلاسِ کاربر به پوسته می‌رود نه به کنترل. */
const omitClassStyle = (attrs: Record<string, unknown>): Record<string, unknown> => {
  const rest: Record<string, unknown> = {};
  for (const key of Object.keys(attrs)) {
    if (key !== 'class' && key !== 'style') rest[key] = attrs[key];
  }
  return rest;
};

const domStyle = (extra: Record<string, string> | undefined, user: unknown): unknown[] => [extra, user];

/* ---- Field: label, hint and error wired to the control inside ------------------ */

interface FieldContextValue {
  id: string;
  hintId?: string;
  errorId?: string;
  invalid: boolean;
  required?: boolean;
  disabled?: boolean;
}

const FieldKey: InjectionKey<ComputedRef<FieldContextValue>> = Symbol('nx-field');

/** اتصال id/aria به <Field> دور کننده — همتای useFieldProps در ui-react. */
function useFieldWiring(attrs: Record<string, unknown>, invalid?: () => boolean | undefined) {
  const field = inject(FieldKey, null);
  return computed<Record<string, unknown>>(() => {
    const described = [attrs['aria-describedby'], field?.value.hintId, field?.value.errorId].filter(Boolean).join(' ') || undefined;
    const nowInvalid = invalid?.() || field?.value.invalid;
    return {
      id: (attrs.id as string | undefined) ?? field?.value.id,
      'aria-describedby': described,
      'aria-invalid': nowInvalid ? true : (attrs['aria-invalid'] as boolean | undefined),
      required: (attrs.required as boolean | undefined) ?? field?.value.required,
      disabled: (attrs.disabled as boolean | undefined) ?? field?.value.disabled,
    };
  });
}

export interface FieldProps {
  label?: VueNode;
  hint?: VueNode;
  /** An error message; also marks the control invalid. */
  error?: VueNode;
  required?: boolean;
  disabled?: boolean;
  /** The control's id (generated when omitted). */
  id?: string;
}

export const NxField = /* @__PURE__ */ defineComponent({
  name: 'NxField',
  inheritAttrs: false,
  props: {
    label: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    hint: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    error: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    required: { type: Boolean, default: undefined },
    disabled: { type: Boolean, default: undefined },
    id: { type: String, default: undefined },
  },
  setup(props, { attrs, slots }) {
    const generated = useId();
    const controlId = computed(() => props.id ?? `nx-field-${generated}`);

    provide(
      FieldKey,
      computed<FieldContextValue>(() => ({
        id: controlId.value,
        hintId: props.hint ? `${controlId.value}-hint` : undefined,
        errorId: props.error ? `${controlId.value}-error` : undefined,
        invalid: !!props.error,
        required: props.required,
        disabled: props.disabled,
      })),
    );

    return () =>
      h('div', { ...attrs, class: cx('nx-field', attrs.class as string | undefined), 'data-invalid': props.error ? '' : undefined }, [
        props.label
          ? h('label', { class: 'nx-label', for: controlId.value }, [
              props.label,
              props.required ? h('span', { class: 'nx-label-required', 'aria-hidden': 'true' }, '*') : null,
            ])
          : null,
        props.hint ? h('p', { class: 'nx-hint', id: `${controlId.value}-hint` }, [props.hint]) : null,
        slots.default?.(),
        props.error ? h('p', { class: 'nx-error', id: `${controlId.value}-error` }, [h(NxIcon, { name: 'alert-circle' }), props.error]) : null,
      ]);
  },
});

/* ---- Input ----------------------------------------------------------------------- */

/** A known icon name becomes the icon; any other string or node is shown as is ("kg", "https://"). */
const addon = (value: string | VNode): VueNode => (typeof value === 'string' && value in icons ? h(NxIcon, { name: value as IconName }) : value);

export interface InputProps {
  size?: 'sm' | 'md' | 'lg';
  invalid?: boolean;
  /** Content before the text: an icon name, a unit, a prefix. */
  startAddon?: string | VNode;
  endAddon?: string | VNode;
  modelValue?: string | number;
}

export const NxInput = /* @__PURE__ */ defineComponent({
  name: 'NxInput',
  inheritAttrs: false,
  props: {
    size: { type: String as PropType<'sm' | 'md' | 'lg'>, default: undefined },
    invalid: { type: Boolean, default: undefined },
    startAddon: { type: [String, Number, Object] as PropType<string | VNode>, default: undefined },
    endAddon: { type: [String, Number, Object] as PropType<string | VNode>, default: undefined },
    modelValue: { type: [String, Number] as PropType<string | number>, default: undefined },
  },
  emits: ['update:modelValue'],
  setup(props, { attrs, emit }) {
    const wiring = useFieldWiring(attrs, () => props.invalid);
    const input = () =>
      h('input', {
        ...attrs,
        ...wiring.value,
        class: cx('nx-input', !props.startAddon && !props.endAddon && (attrs.class as string | undefined)),
        'data-size': props.size === 'md' ? undefined : props.size,
        value: props.modelValue !== undefined ? props.modelValue : attrs.value,
        onInput: [
          attrs.onInput as ((event: Event) => void) | undefined,
          (event: Event) => emit('update:modelValue', (event.target as HTMLInputElement).value),
        ],
      });

    return () => {
      if (!props.startAddon && !props.endAddon) return input();
      return h('div', { class: cx('nx-input-group', attrs.class as string | undefined), 'data-size': props.size === 'md' ? undefined : props.size }, [
        props.startAddon ? h('span', { class: 'nx-input-addon' }, [addon(props.startAddon)]) : null,
        input(),
        props.endAddon ? h('span', { class: 'nx-input-addon' }, [addon(props.endAddon)]) : null,
      ]);
    };
  },
});

export interface TextareaProps {
  invalid?: boolean;
  /** Grow with the content up to this many lines. */
  maxRows?: number;
  modelValue?: string;
}

export const NxTextarea = /* @__PURE__ */ defineComponent({
  name: 'NxTextarea',
  inheritAttrs: false,
  props: {
    invalid: { type: Boolean, default: undefined },
    maxRows: { type: Number, default: undefined },
    modelValue: { type: String, default: undefined },
  },
  emits: ['update:modelValue'],
  setup(props, { attrs, emit }) {
    const own = ref<HTMLTextAreaElement | null>(null);
    const wiring = useFieldWiring(attrs, () => props.invalid);
    let cleanup: Cleanup | undefined;
    onMounted(() => {
      if (own.value) cleanup = autogrow(own.value);
    });
    onBeforeUnmount(() => cleanup?.());

    return () =>
      h('textarea', {
        ...attrs,
        ...wiring.value,
        ref: own,
        class: cx('nx-textarea', attrs.class as string | undefined),
        style: domStyle(props.maxRows ? { '--nx-textarea-max': `${props.maxRows}lh` } : undefined, attrs.style),
        value: props.modelValue !== undefined ? props.modelValue : attrs.value,
        onInput: [
          attrs.onInput as ((event: Event) => void) | undefined,
          (event: Event) => emit('update:modelValue', (event.target as HTMLTextAreaElement).value),
        ],
      });
  },
});

export interface SelectOption {
  value: string;
  label: string;
  disabled?: boolean;
}

export interface SelectProps {
  size?: 'sm' | 'md' | 'lg';
  invalid?: boolean;
  options?: SelectOption[];
  placeholder?: string;
  modelValue?: string;
}

export const NxSelect = /* @__PURE__ */ defineComponent({
  name: 'NxSelect',
  inheritAttrs: false,
  props: {
    size: { type: String as PropType<'sm' | 'md' | 'lg'>, default: undefined },
    invalid: { type: Boolean, default: undefined },
    options: { type: Array as PropType<SelectOption[]>, default: undefined },
    placeholder: { type: String, default: undefined },
    modelValue: { type: String, default: undefined },
  },
  emits: ['update:modelValue'],
  setup(props, { attrs, slots, emit }) {
    const wiring = useFieldWiring(attrs, () => props.invalid);
    return () =>
      h(
        'select',
        {
          ...attrs,
          ...wiring.value,
          class: cx('nx-select', attrs.class as string | undefined),
          'data-size': props.size === 'md' ? undefined : props.size,
          value: props.modelValue !== undefined ? props.modelValue : attrs.value,
          onChange: [
            attrs.onChange as ((event: Event) => void) | undefined,
            (event: Event) => emit('update:modelValue', (event.target as HTMLSelectElement).value),
          ],
        },
        [
          props.placeholder ? h('option', { value: '', disabled: true }, props.placeholder) : null,
          ...(props.options ?? []).map((option) => h('option', { key: option.value, value: option.value, disabled: option.disabled || undefined }, option.label)),
          ...(slots.default?.() ?? []),
        ],
      );
  },
});

/* ---- Choices --------------------------------------------------------------------- */

/** پوستهٔ انتخاب (label + متن) — مثل ChoiceShell در ui-react. */
function choiceShell(control: VNode, label: VueNode, description: VueNode, className: string | undefined, style: unknown): VNode {
  if (!label && !description) return control;
  return h('label', { class: cx('nx-choice', className), style }, [
    control,
    h('span', { class: 'nx-choice-text' }, [
      label ? h('span', { class: 'nx-choice-label' }, [label]) : null,
      description ? h('span', { class: 'nx-choice-description' }, [description]) : null,
    ]),
  ]);
}

export interface CheckboxProps {
  label?: VueNode;
  description?: VueNode;
  invalid?: boolean;
  indeterminate?: boolean;
  modelValue?: boolean;
}

export const NxCheckbox = /* @__PURE__ */ defineComponent({
  name: 'NxCheckbox',
  inheritAttrs: false,
  props: {
    label: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    description: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    invalid: { type: Boolean, default: undefined },
    indeterminate: { type: Boolean, default: undefined },
    modelValue: { type: Boolean, default: undefined },
  },
  emits: ['update:modelValue'],
  setup(props, { attrs, emit }) {
    const own = ref<HTMLInputElement | null>(null);
    const wiring = useFieldWiring(attrs, () => props.invalid);

    const syncIndeterminate = () => {
      if (own.value) own.value.indeterminate = !!props.indeterminate;
    };
    onMounted(syncIndeterminate);
    watch(() => props.indeterminate, syncIndeterminate);

    return () => {
      const control = h('input', {
        ...attrs,
        ...wiring.value,
        ref: own,
        type: 'checkbox',
        class: cx('nx-checkbox', !props.label && !props.description && (attrs.class as string | undefined)),
        checked: props.modelValue ?? attrs.checked,
        onChange: [
          attrs.onChange as ((event: Event) => void) | undefined,
          (event: Event) => emit('update:modelValue', (event.target as HTMLInputElement).checked),
        ],
      });
      return choiceShell(control, props.label, props.description, attrs.class as string | undefined, attrs.style);
    };
  },
});

export interface SwitchProps {
  label?: VueNode;
  description?: VueNode;
  invalid?: boolean;
  size?: 'sm' | 'md' | 'lg';
  modelValue?: boolean;
}

export const NxSwitch = /* @__PURE__ */ defineComponent({
  name: 'NxSwitch',
  inheritAttrs: false,
  props: {
    label: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    description: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    invalid: { type: Boolean, default: undefined },
    size: { type: String as PropType<'sm' | 'md' | 'lg'>, default: undefined },
    modelValue: { type: Boolean, default: undefined },
  },
  emits: ['update:modelValue'],
  setup(props, { attrs, emit }) {
    const wiring = useFieldWiring(attrs, () => props.invalid);
    return () => {
      const control = h('input', {
        ...attrs,
        ...wiring.value,
        type: 'checkbox',
        role: 'switch',
        class: cx('nx-switch', !props.label && !props.description && (attrs.class as string | undefined)),
        'data-size': props.size === 'md' ? undefined : props.size,
        checked: props.modelValue ?? attrs.checked,
        onChange: [
          attrs.onChange as ((event: Event) => void) | undefined,
          (event: Event) => emit('update:modelValue', (event.target as HTMLInputElement).checked),
        ],
      });
      return choiceShell(control, props.label, props.description, attrs.class as string | undefined, attrs.style);
    };
  },
});

export interface RadioProps {
  label?: VueNode;
  description?: VueNode;
  invalid?: boolean;
}

export const NxRadio = /* @__PURE__ */ defineComponent({
  name: 'NxRadio',
  inheritAttrs: false,
  props: {
    label: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    description: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    invalid: { type: Boolean, default: undefined },
  },
  setup(props, { attrs }) {
    return () => {
      const control = h('input', {
        ...attrs,
        type: 'radio',
        class: cx('nx-radio', !props.label && !props.description && (attrs.class as string | undefined)),
        'aria-invalid': props.invalid || undefined,
      });
      return choiceShell(control, props.label, props.description, attrs.class as string | undefined, attrs.style);
    };
  },
});

export interface RadioGroupProps {
  name: string;
  legend?: VueNode;
  options: Array<{ value: string; label: VueNode; description?: VueNode; disabled?: boolean }>;
  modelValue?: string;
  defaultValue?: string;
  orientation?: 'vertical' | 'horizontal';
}

export const NxRadioGroup = /* @__PURE__ */ defineComponent({
  name: 'NxRadioGroup',
  inheritAttrs: false,
  props: {
    name: { type: String, required: true },
    legend: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    options: {
      type: Array as PropType<Array<{ value: string; label: VueNode; description?: VueNode; disabled?: boolean }>>,
      required: true,
    },
    modelValue: { type: String, default: undefined },
    defaultValue: { type: String, default: '' },
    orientation: { type: String as PropType<'vertical' | 'horizontal'>, default: 'vertical' },
  },
  emits: ['update:modelValue'],
  setup(props, { attrs, emit }) {
    const inner = ref(props.defaultValue);
    const current = computed(() => props.modelValue ?? inner.value);
    const setCurrent = (value: string) => {
      inner.value = value;
      emit('update:modelValue', value);
    };

    return () =>
      h('fieldset', { ...attrs, class: cx('nx-field', attrs.class as string | undefined), style: { border: 0, margin: 0, padding: 0 } }, [
        props.legend ? h('legend', { class: 'nx-label' }, [props.legend]) : null,
        h(
          'div',
          { style: { display: 'flex', flexDirection: props.orientation === 'vertical' ? 'column' : 'row', flexWrap: 'wrap', gap: 'var(--nx-space-3) var(--nx-space-6)' } },
          props.options.map((option) =>
            h(NxRadio, {
              key: option.value,
              name: props.name,
              value: option.value,
              label: option.label,
              description: option.description,
              disabled: option.disabled || undefined,
              checked: current.value === option.value,
              onChange: () => setCurrent(option.value),
            }),
          ),
        ),
      ]);
  },
});

/* ---- One-time code --------------------------------------------------------------- */

export interface OtpInputProps {
  length?: number;
  modelValue?: string;
  defaultValue?: string;
  /** Draw a dash after this many slots (3 gives 123–456). */
  separatorAfter?: number;
  invalid?: boolean;
  /** Accept letters as well as digits. */
  alphanumeric?: boolean;
  /** The seat's language for its own words. */
  lang?: NxLang;
}

export const NxOtpInput = /* @__PURE__ */ defineComponent({
  name: 'NxOtpInput',
  inheritAttrs: false,
  props: {
    length: { type: Number, default: 6 },
    modelValue: { type: String, default: undefined },
    defaultValue: { type: String, default: '' },
    separatorAfter: { type: Number, default: undefined },
    invalid: { type: Boolean, default: undefined },
    alphanumeric: { type: Boolean, default: undefined },
    lang: { type: String as PropType<NxLang>, default: undefined },
  },
  emits: ['update:modelValue', 'complete'],
  setup(props, { attrs, emit }) {
    const inner = ref(props.defaultValue);
    const code = computed(() => props.modelValue ?? inner.value);
    const focused = ref(false);
    const inputEl = ref<HTMLInputElement | null>(null);
    const wiring = useFieldWiring(attrs, () => props.invalid);

    const t = (key: MessageKey, params: Record<string, string | number> = {}): string => nxTranslate(props.lang ?? domLang(inputEl.value), key, params);
    const pattern = computed(() => (props.alphanumeric ? /[^0-9a-z]/gi : /[^0-9۰-۹٠-٩]/g));

    const normalise = (raw: string) =>
      raw
        .replace(pattern.value, '')
        // Persian and Arabic-Indic digits type as themselves; store ASCII.
        .replace(/[۰-۹]/g, (d) => String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d)))
        .replace(/[٠-٩]/g, (d) => String('٠١٢٣٤٥٦٧٨٩'.indexOf(d)))
        .slice(0, props.length);

    const set = (next: string) => {
      inner.value = next;
      emit('update:modelValue', next);
      if (next.length === props.length) emit('complete', next);
    };

    const active = computed(() => Math.min(code.value.length, props.length - 1));

    return () =>
      h('div', { class: cx('nx-otp', attrs.class as string | undefined), 'data-invalid': props.invalid || wiring.value['aria-invalid'] ? '' : undefined }, [
        h('input', {
          ...attrs,
          ...wiring.value,
          ref: inputEl,
          class: 'nx-otp-input',
          value: code.value,
          onInput: [
            attrs.onInput as ((event: Event) => void) | undefined,
            (event: Event) => set(normalise((event.target as HTMLInputElement).value)),
          ],
          onFocus: [attrs.onFocus as ((event: Event) => void) | undefined, () => {
            focused.value = true;
          }],
          onBlur: [attrs.onBlur as ((event: Event) => void) | undefined, () => {
            focused.value = false;
          }],
          inputmode: props.alphanumeric ? 'text' : 'numeric',
          autocomplete: 'one-time-code',
          maxlength: props.length,
          'aria-label': (attrs['aria-label'] as string | undefined) ?? (attrs['aria-labelledby'] ? undefined : t('code')),
          spellcheck: false,
        }),
        h(
          'div',
          { class: 'nx-otp-slots', 'aria-hidden': 'true' },
          Array.from({ length: props.length }, (_, i) =>
            h('span', { key: i, style: { display: 'contents' } }, [
              h(
                'span',
                {
                  class: 'nx-otp-slot',
                  'data-filled': code.value[i] ? '' : undefined,
                  'data-active': focused.value && i === active.value ? '' : undefined,
                },
                [code.value[i] ? h('span', { key: `${code.value[i]}${i}` }, code.value[i]) : null],
              ),
              props.separatorAfter && i === props.separatorAfter - 1 && i < props.length - 1 ? h('span', { class: 'nx-otp-sep' }) : null,
            ]),
          ),
        ),
      ]);
  },
});

/* ---- Slider ------------------------------------------------------------------------ */

export interface SliderProps {
  min?: number;
  max?: number;
  step?: number;
  modelValue?: number;
  defaultValue?: number;
  /** Keep the value bubble visible, not only while interacting. */
  showValue?: boolean;
  formatValue?: (value: number) => string;
  /** Labels under the track's ends. */
  startLabel?: VueNode;
  endLabel?: VueNode;
}

export const NxSlider = /* @__PURE__ */ defineComponent({
  name: 'NxSlider',
  inheritAttrs: false,
  props: {
    min: { type: Number, default: 0 },
    max: { type: Number, default: 100 },
    step: { type: Number, default: 1 },
    modelValue: { type: Number, default: undefined },
    defaultValue: { type: Number, default: undefined },
    showValue: { type: Boolean, default: undefined },
    formatValue: { type: Function as PropType<(value: number) => string>, default: undefined },
    startLabel: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    endLabel: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
  },
  emits: ['update:modelValue'],
  setup(props, { attrs, emit }) {
    const inner = ref(props.defaultValue ?? props.min);
    const current = computed(() => props.modelValue ?? inner.value);
    const wiring = useFieldWiring(attrs);
    const setCurrent = (value: number) => {
      inner.value = value;
      emit('update:modelValue', value);
    };

    const fraction = computed(() => (current.value - props.min) / (props.max - props.min || 1));
    const shown = computed(() => (props.formatValue ? props.formatValue(current.value) : String(current.value)));

    return () => {
      const controlAttrs = omitClassStyle(attrs);
      return h('div', {
        ...attrs,
        class: cx('nx-slider', attrs.class as string | undefined),
        'data-show-value': props.showValue ? '' : undefined,
        style: domStyle({ '--nx-pct': `${fraction.value * 100}%`, '--nx-frac': String(fraction.value) }, attrs.style),
      }, [
        h('input', {
          ...controlAttrs,
          ...wiring.value,
          type: 'range',
          class: 'nx-slider-input',
          min: props.min,
          max: props.max,
          step: props.step,
          value: current.value,
          'aria-valuetext': props.formatValue ? shown.value : undefined,
          onInput: [
            attrs.onInput as ((event: Event) => void) | undefined,
            (event: Event) => setCurrent(Number((event.target as HTMLInputElement).value)),
          ],
        }),
        h('output', { class: 'nx-slider-value', 'aria-hidden': 'true' }, shown.value),
        props.startLabel || props.endLabel
          ? h('div', { class: 'nx-slider-labels', 'aria-hidden': 'true' }, [h('span', [props.startLabel]), h('span', [props.endLabel])])
          : null,
      ]);
    };
  },
});

/* ---- File drop ------------------------------------------------------------------- */

export interface FileItem {
  name: string;
  size?: number;
  /** 0–100 while uploading. */
  progress?: number;
  status?: 'uploading' | 'done' | 'error';
}

export interface FileDropProps {
  /** Files to list under the drop zone (with their upload progress). */
  files?: FileItem[];
  title?: VueNode;
  hint?: VueNode;
  disabled?: boolean;
  /** The seat's language for its own words. */
  lang?: NxLang;
}

const formatSize = (bytes?: number): string => {
  if (bytes === undefined) return '';
  const units = ['B', 'KB', 'MB', 'GB'];
  let i = 0;
  let size = bytes;
  while (size >= 1024 && i < units.length - 1) {
    size /= 1024;
    i += 1;
  }
  return `${size.toFixed(size < 10 && i > 0 ? 1 : 0)} ${units[i]}`;
};

export const NxFileDrop = /* @__PURE__ */ defineComponent({
  name: 'NxFileDrop',
  inheritAttrs: false,
  props: {
    files: { type: Array as PropType<FileItem[]>, default: undefined },
    title: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    hint: { type: [String, Number, Object] as PropType<VueNode>, default: undefined },
    disabled: { type: Boolean, default: undefined },
    lang: { type: String as PropType<NxLang>, default: undefined },
  },
  emits: ['files'],
  setup(props, { attrs, emit }) {
    const dragging = ref(false);
    const labelEl = ref<HTMLLabelElement | null>(null);
    let depth = 0;

    const t = (key: MessageKey, params: Record<string, string | number> = {}): string => nxTranslate(props.lang ?? domLang(labelEl.value), key, params);

    const take = (list: FileList | null) => {
      const picked = list ? Array.from(list) : [];
      if (picked.length) emit('files', picked);
    };

    /** "Drop files here or {browse}" with the word "browse" underlined, in any language order. */
    const dropTitle = (): VueNode[] => {
      const [before, after] = t('dropFiles', { browse: '{browse}' }).split('{browse}');
      return [before, h('u', t('browse')), after];
    };

    return () =>
      h('div', { ...attrs, class: attrs.class as string | undefined }, [
        h(
          'label',
          {
            ref: labelEl,
            class: 'nx-filedrop',
            'data-dragging': dragging.value ? '' : undefined,
            onDragenter: (event: DragEvent) => {
              event.preventDefault();
              if (props.disabled) return;
              depth += 1;
              dragging.value = true;
            },
            onDragover: (event: DragEvent) => event.preventDefault(),
            onDragleave: () => {
              depth = Math.max(0, depth - 1);
              if (depth === 0) dragging.value = false;
            },
            onDrop: (event: DragEvent) => {
              event.preventDefault();
              depth = 0;
              dragging.value = false;
              if (!props.disabled) take(event.dataTransfer?.files ?? null);
            },
          },
          [
            h('input', {
              ...omitClassStyle(attrs),
              type: 'file',
              class: 'nx-visually-hidden',
              disabled: props.disabled || undefined,
              onChange: [
                attrs.onChange as ((event: Event) => void) | undefined,
                (event: Event) => take((event.target as HTMLInputElement).files),
              ],
            }),
            h('span', { class: 'nx-filedrop-icon', 'aria-hidden': 'true' }, [h(NxIcon, { name: 'upload' })]),
            h('span', { class: 'nx-filedrop-title' }, [props.title ?? dropTitle()]),
            props.hint ? h('span', { class: 'nx-filedrop-hint' }, [props.hint]) : null,
          ],
        ),
        props.files && props.files.length > 0
          ? h(
              'ul',
              { class: 'nx-file-list' },
              props.files.map((file, i) =>
                h(
                  'li',
                  { key: `${file.name}-${i}`, class: 'nx-file', 'data-status': file.status, style: { '--nx-i': String(i) } },
                  [
                    h(NxIcon, { name: 'file' }),
                    h('span', { class: 'nx-file-name' }, file.name),
                    h(
                      'span',
                      {
                        class: 'nx-file-state',
                        'aria-label': file.status ? t(file.status === 'done' ? 'uploaded' : file.status === 'error' ? 'failed' : 'uploading') : undefined,
                      },
                      [file.status === 'done' ? h(NxIcon, { name: 'check-circle' }) : file.status === 'error' ? h(NxIcon, { name: 'alert-circle' }) : null],
                    ),
                    h('span', { class: 'nx-file-meta' }, formatSize(file.size)),
                    file.status === 'uploading'
                      ? h('progress', {
                          class: 'nx-progress',
                          value: file.progress ?? 0,
                          max: 100,
                          style: { '--nx-value': String(file.progress ?? 0) },
                          'aria-label': `${t('uploading')} ${file.name}`,
                        })
                      : null,
                  ],
                ),
              ),
            )
          : null,
      ]);
  },
});

/* ---- Prompt input ------------------------------------------------------------------ */

export interface PromptInputProps {
  modelValue?: string;
  defaultValue?: string;
  placeholder?: string;
  /** While a reply is streaming the send button becomes a stop button. */
  streaming?: boolean;
  /** Attached files shown as chips above the text. */
  attachments?: Array<{ id: string; name: string }>;
  /** Offer the paperclip; called with the chosen files. */
  onAttach?: (files: File[]) => void;
  /** Extra controls in the toolbar (model picker, toggles) — the «toolbar» slot. */
  glow?: boolean;
  maxRows?: number;
  disabled?: boolean;
  name?: string;
  ariaLabel?: string;
  /** The seat's language for its own words. */
  lang?: NxLang;
}

export const NxPromptInput = /* @__PURE__ */ defineComponent({
  name: 'NxPromptInput',
  inheritAttrs: false,
  props: {
    modelValue: { type: String, default: undefined },
    defaultValue: { type: String, default: '' },
    placeholder: { type: String, default: undefined },
    streaming: { type: Boolean, default: undefined },
    attachments: { type: Array as PropType<Array<{ id: string; name: string }>>, default: undefined },
    glow: { type: Boolean, default: true },
    maxRows: { type: Number, default: 12 },
    disabled: { type: Boolean, default: undefined },
    name: { type: String, default: undefined },
    ariaLabel: { type: String, default: undefined },
    lang: { type: String as PropType<NxLang>, default: undefined },
  },
  emits: ['update:modelValue', 'submit', 'stop', 'attach', 'removeAttachment'],
  setup(props, { attrs, slots, emit }) {
    const inner = ref(props.defaultValue);
    const text = computed(() => props.modelValue ?? inner.value);
    const setText = (value: string) => {
      inner.value = value;
      emit('update:modelValue', value);
    };

    const area = ref<HTMLTextAreaElement | null>(null);
    const picker = ref<HTMLInputElement | null>(null);
    const formEl = ref<HTMLFormElement | null>(null);
    let cleanup: Cleanup | undefined;
    onMounted(() => {
      if (area.value) cleanup = autogrow(area.value);
    });
    onBeforeUnmount(() => cleanup?.());

    const t = (key: MessageKey): string => nxTranslate(props.lang ?? domLang(formEl.value), key);

    const submit = (event?: Event) => {
      event?.preventDefault();
      if (props.streaming) {
        emit('stop');
        return;
      }
      const trimmed = text.value.trim();
      if (!trimmed || props.disabled) return;
      emit('submit', trimmed);
    };

    return () =>
      h('form', { ...attrs, ref: formEl, class: cx('nx-prompt', attrs.class as string | undefined), 'data-glow': props.glow ? '' : undefined, onSubmit: submit }, [
        props.attachments && props.attachments.length > 0
          ? h(
              'div',
              { class: 'nx-prompt-attachments' },
              props.attachments.map((file) =>
                h(NxChip, { key: file.id, lang: props.lang, onRemove: () => emit('removeAttachment', file.id) }, { default: () => file.name }),
              ),
            )
          : null,
        h('textarea', {
          ref: area,
          class: 'nx-prompt-textarea',
          rows: 1,
          name: props.name,
          value: text.value,
          placeholder: props.placeholder,
          'aria-label': props.ariaLabel ?? props.placeholder,
          disabled: props.disabled || undefined,
          onInput: (event: Event) => setText((event.target as HTMLTextAreaElement).value),
          onKeydown: (event: KeyboardEvent) => {
            if (event.key === 'Enter' && !event.shiftKey && !event.isComposing) submit(event);
          },
          style: { '--nx-prompt-max': `${props.maxRows}lh` },
        }),
        h('div', { class: 'nx-prompt-toolbar' }, [
          hasListener('onAttach')
            ? [
                h('input', {
                  ref: picker,
                  type: 'file',
                  multiple: true,
                  hidden: true,
                  onChange: (event: Event) => {
                    const files = (event.target as HTMLInputElement).files;
                    if (files) emit('attach', Array.from(files));
                  },
                }),
                h(
                  'button',
                  { type: 'button', class: 'nx-button', 'data-variant': 'ghost', 'data-size': 'sm', 'data-icon-only': '', 'aria-label': t('attach'), onClick: () => picker.value?.click() },
                  [h('span', { class: 'nx-button-label' }, [h(NxIcon, { name: 'paperclip' })])],
                ),
              ]
            : null,
          slots.toolbar?.(),
          h('span', { class: 'nx-prompt-spacer' }),
          h(
            'button',
            {
              type: 'submit',
              class: 'nx-prompt-send',
              'data-state': props.streaming ? 'streaming' : text.value.trim() ? 'ready' : undefined,
              disabled: (!props.streaming && (!text.value.trim() || props.disabled)) || undefined,
              'aria-label': props.streaming ? t('stop') : t('send'),
            },
            [h(NxIcon, { name: 'arrow-up', class: 'nx-prompt-arrow' }), h(NxIcon, { name: 'stop', class: 'nx-prompt-stop' })],
          ),
        ]),
      ]);
  },
});

/* ---- Chip --------------------------------------------------------------------------- */

export interface ChipProps {
  /** The chip's text. */
  text?: string;
  /** The seat's language for its own words. */
  lang?: NxLang;
}

export const NxChip = /* @__PURE__ */ defineComponent({
  name: 'NxChip',
  inheritAttrs: false,
  props: {
    text: { type: String, default: undefined },
    lang: { type: String as PropType<NxLang>, default: undefined },
  },
  emits: ['remove'],
  setup(props, { attrs, slots, emit }) {
    const spanEl = ref<HTMLSpanElement | null>(null);
    const label = computed(() => props.text ?? slotText(slots.default));
    const t = (key: MessageKey, params: Record<string, string | number> = {}): string => nxTranslate(props.lang ?? domLang(spanEl.value), key, params);

    return () =>
      h('span', { ...attrs, ref: spanEl, class: cx('nx-chip', attrs.class as string | undefined) }, [
        h('span', slots.default?.() ?? label.value),
        hasListener('onRemove')
          ? h(
              'button',
              { type: 'button', class: 'nx-chip-remove', 'aria-label': t('remove', { name: label.value }), onClick: () => emit('remove') },
              [h(NxIcon, { name: 'x' })],
            )
          : null,
      ]);
  },
});

export default NxField;
</script>
