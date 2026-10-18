/**
 * NxTooltip — the Vue counterpart of ui-react's `Tooltip`: one focusable child
 * (the default slot) described by a bubble placed with the core `place()`.
 * The child is cloned with the pointer/focus handlers and `aria-describedby`
 * (Vue's cloneVNode chains handlers, like React's cloneElement) — no wrapper
 * element, same DOM.
 */
import { cloneVNode, computed, defineComponent, h, onBeforeUnmount, ref, useId, watch, type Ref, type VNode } from 'vue';
import { place } from '@nabuxai/ui-core';
import { cx, type VueNode } from './utils';

export interface NxTooltipProps {
  content: VueNode;
  side?: 'top' | 'bottom' | 'start' | 'end';
  /** Hover delay before showing, ms (focus shows at once). */
  delay?: number;
  disabled?: boolean;
}

type Side = NonNullable<NxTooltipProps['side']>;

const SIDES: Record<Side, 'top' | 'bottom' | 'inline-start' | 'inline-end'> = {
  top: 'top',
  bottom: 'bottom',
  start: 'inline-start',
  end: 'inline-end',
};

function showTop(el: HTMLElement) {
  if (typeof el.showPopover === 'function') {
    try {
      el.showPopover();
      return;
    } catch {
      /* already shown */
    }
  }
  el.setAttribute('data-open', '');
}

function hideTop(el: HTMLElement) {
  el.removeAttribute('data-open');
  try {
    if (el.matches(':popover-open')) el.hidePopover();
  } catch {
    /* no popover support */
  }
}

export const NxTooltip = defineComponent({
  name: 'NxTooltip',
  props: {
    content: { type: [String, Number, Object], required: true },
    side: { type: String, default: 'top' },
    delay: { type: Number, default: 350 },
    disabled: { type: Boolean, default: false },
  },
  setup(props, { slots }) {
    const rawId = useId();
    const id = `nx-tip${rawId.replace(/[^a-zA-Z0-9-]/g, '')}`;
    const tip = ref<HTMLDivElement | null>(null) as Ref<HTMLDivElement | null>;
    const trigger = ref<HTMLElement | null>(null) as Ref<HTMLElement | null>;
    const timer = ref<ReturnType<typeof setTimeout> | undefined>(undefined);
    const open = ref(false);

    const schedule = (next: boolean, wait: number) => {
      clearTimeout(timer.value);
      timer.value = setTimeout(() => (open.value = next), wait);
    };
    const cancel = () => clearTimeout(timer.value);

    let stop: (() => void) | undefined;
    let onKey: ((event: KeyboardEvent) => void) | undefined;

    const settle = () => {
      stop?.();
      stop = undefined;
      if (onKey) {
        document.removeEventListener('keydown', onKey);
        onKey = undefined;
      }
    };

    watch([open, () => props.disabled, () => props.side], () => {
      const el = tip.value;
      const anchor = trigger.value;
      if (!el || !anchor) return;

      settle();
      if (!open.value || props.disabled) {
        hideTop(el);
        return;
      }
      showTop(el);
      stop = place(anchor, el, { side: SIDES[props.side as Side], align: 'center', offset: 8 });
      onKey = (event: KeyboardEvent) => event.key === 'Escape' && (open.value = false);
      document.addEventListener('keydown', onKey);
    });

    onBeforeUnmount(() => {
      clearTimeout(timer.value);
      settle();
      if (tip.value) hideTop(tip.value);
    });

    const contentNode = computed<VueNode>(() => (slots.content ? (slots.content() as VNode[]) : (props.content as VueNode)));

    return () => {
      const child = (slots.default?.() ?? [])[0] as VNode | undefined;
      if (!child) return null;

      const describedBy = child.props?.['aria-describedby'] as string | undefined;
      const anchor = cx(describedBy, props.disabled ? undefined : id) || undefined;

      const decorated = cloneVNode(child, {
        ref: trigger,
        'aria-describedby': anchor,
        onPointerenter: () => schedule(true, props.delay),
        onPointerleave: () => schedule(false, 100),
        onFocusin: () => schedule(true, 0),
        onFocusout: () => schedule(false, 0),
      });

      return [
        decorated,
        h(
          'div',
          {
            ref: tip,
            id,
            role: 'tooltip',
            class: 'nx-tooltip',
            popover: 'manual',
            onPointerenter: cancel,
            onPointerleave: () => schedule(false, 100),
          },
          [contentNode.value],
        ),
      ];
    };
  },
});
