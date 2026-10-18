import { createApp, defineComponent, h, type VNode } from 'vue';
import { describe, expect, it } from 'vitest';
import {
  NxButton,
  NxCheckbox,
  NxChip,
  NxCopyButton,
  NxField,
  NxGradientText,
  NxInput,
  NxLikeButton,
  NxNumberTicker,
  NxOtpInput,
  NxRadioGroup,
  NxReveal,
  NxShimmerText,
  NxSlider,
  NxSwitch,
} from '../src/index';
import NxTextSeat from '../src/components/text.vue';
import NxButtonSeat from '../src/components/button.vue';
import NxFormSeat from '../src/components/form.vue';

/** سوارکردن یک درخت رندر، مثل scaffold.test.ts — بدون وابستگی تازه. */
function mount(render: () => VNode) {
  const host = document.createElement('div');
  document.body.appendChild(host);
  const app = createApp(defineComponent({ setup: () => render }));
  app.mount(host);
  return {
    host,
    unmount() {
      app.unmount();
      host.remove();
    },
  };
}

const type = (input: HTMLInputElement, value: string) => {
  input.value = value;
  input.dispatchEvent(new Event('input', { bubbles: true }));
};

const toggle = (input: HTMLInputElement) => {
  input.checked = !input.checked;
  input.dispatchEvent(new Event('change', { bubbles: true }));
};

describe('text seat', () => {
  it('defaults export the headline component (NxReveal, NxButton, NxField)', () => {
    expect(NxTextSeat).toBe(NxReveal);
    expect(NxButtonSeat).toBe(NxButton);
    expect(NxFormSeat.name).toBe('NxField');
  });

  it('reveals with the rise variant by default', () => {
    const { host, unmount } = mount(() => h(NxReveal, () => 'سلام'));
    const el = host.firstElementChild as HTMLElement;
    expect(el.tagName).toBe('DIV');
    expect(el.getAttribute('data-nx-reveal')).toBe('rise');
    expect(el.textContent).toBe('سلام');
    unmount();
  });

  it('gradient text only marks non-default variants', () => {
    const { host, unmount } = mount(() => h(NxGradientText, { variant: 'gold', animate: true }, () => 'طلایی'));
    const el = host.firstElementChild as HTMLElement;
    expect(el.className).toBe('nx-gradient-text');
    expect(el.getAttribute('data-variant')).toBe('gold');
    expect(el.hasAttribute('data-animate')).toBe(true);
    unmount();
  });

  it('splits the wave shimmer into pieces in the text direction', () => {
    const { host, unmount } = mount(() => h(NxShimmerText, { variant: 'wave' }, () => 'hi'));
    const el = host.firstElementChild as HTMLElement;
    expect(el.getAttribute('data-variant')).toBe('wave');
    const pieces = el.querySelectorAll('.nx-text-piece');
    expect(pieces.length).toBe(2);
    expect(el.querySelector('.nx-visually-hidden')?.textContent).toBe('hi');
    unmount();
  });

  it('rolls a Persian number with Persian digits', () => {
    const { host, unmount } = mount(() => h(NxNumberTicker, { value: 1234, lang: 'fa', reveal: false }));
    const el = host.firstElementChild as HTMLElement;
    expect(el.textContent).toContain('۱');
    expect(el.querySelector('.nx-digit-track')?.children.length).toBe(10);
    unmount();
  });
});

describe('button seat', () => {
  it('renders the default secondary button', () => {
    const { host, unmount } = mount(() => h(NxButton, () => 'ذخیره'));
    const el = host.firstElementChild as HTMLButtonElement;
    expect(el.tagName).toBe('BUTTON');
    expect(el.className).toBe('nx-button');
    expect(el.getAttribute('data-variant')).toBe('secondary');
    expect(el.type).toBe('button');
    expect(el.querySelector('.nx-button-text')?.textContent).toBe('ذخیره');
    unmount();
  });

  it('carries variant, size, shape and block flags', () => {
    const { host, unmount } = mount(() => h(NxButton, { variant: 'primary', size: 'lg', shape: 'pill', block: true }, () => 'x'));
    const el = host.firstElementChild as HTMLElement;
    expect(el.getAttribute('data-variant')).toBe('primary');
    expect(el.getAttribute('data-size')).toBe('lg');
    expect(el.getAttribute('data-shape')).toBe('pill');
    expect(el.hasAttribute('data-block')).toBe(true);
    unmount();
  });

  it('busy buttons ignore presses but stay focusable', () => {
    const clicks: number[] = [];
    const { host, unmount } = mount(() => h(NxButton, { loading: true, onClick: () => clicks.push(1) }, () => 'x'));
    const el = host.firstElementChild as HTMLButtonElement;
    expect(el.getAttribute('aria-busy')).toBe('true');
    expect(el.getAttribute('data-status')).toBe('loading');
    expect(el.disabled).toBe(false);
    el.click();
    expect(clicks).toEqual([]);
    unmount();
  });

  it('renders href buttons as anchors', () => {
    const { host, unmount } = mount(() => h(NxButton, { href: 'https://nabu.ai', target: '_blank' }, () => 'برو'));
    const el = host.firstElementChild as HTMLAnchorElement;
    expect(el.tagName).toBe('A');
    expect(el.getAttribute('href')).toBe('https://nabu.ai');
    expect(el.getAttribute('target')).toBe('_blank');
    unmount();
  });

  it('hides icon-only labels from sight, not from assistive tech', () => {
    const { host, unmount } = mount(() => h(NxButton, { icon: 'plus', iconOnly: true, 'aria-label': 'افزودن' }));
    const el = host.firstElementChild as HTMLElement;
    expect(el.hasAttribute('data-icon-only')).toBe(true);
    expect(el.querySelector('.nx-visually-hidden')).toBeNull();
    expect(el.querySelector('.nx-button-label svg')).not.toBeNull();
    unmount();
  });

  it('labels the copy button and likes the like button', async () => {
    const { host, unmount } = mount(() => h(NxCopyButton, { value: 'x' }));
    expect((host.firstElementChild as HTMLElement).getAttribute('aria-label')).toBe('Copy');
    unmount();

    const liked: boolean[] = [];
    const seat = mount(() => h(NxLikeButton, { onUpdate: () => {}, onLikedChange: () => {}, 'onUpdate:liked': (v: boolean) => liked.push(v) }));
    const likeEl = seat.host.firstElementChild as HTMLElement;
    expect(likeEl.getAttribute('aria-pressed')).toBe('false');
    expect(likeEl.getAttribute('aria-label')).toBe('Like');
    (likeEl as HTMLButtonElement).click();
    await Promise.resolve();
    expect(liked).toEqual([true]);
    seat.unmount();
  });
});

describe('form seat', () => {
  it('wires Field label, hint and error to the input inside', () => {
    const { host, unmount } = mount(() =>
      h(NxField, { label: 'ایمیل', hint: 'ما را نشان نمی‌دهیم', error: 'نامعتبر', required: true }, () => h(NxInput, { name: 'email' })),
    );
    const input = host.querySelector('input');
    const label = host.querySelector('label');
    expect(input?.id).toBe(label?.getAttribute('for'));
    expect(input?.getAttribute('aria-describedby')?.split(' ')).toContain(host.querySelector('.nx-hint')?.id);
    expect(input?.getAttribute('aria-describedby')?.split(' ')).toContain(host.querySelector('.nx-error')?.id);
    expect(input?.getAttribute('aria-invalid')).toBe('true');
    expect(input?.required).toBe(true);
    expect(host.firstElementChild?.hasAttribute('data-invalid')).toBe(true);
    unmount();
  });

  it('groups addons around the input', () => {
    const { host, unmount } = mount(() => h(NxInput, { startAddon: 'search', endAddon: 'kg', class: 'mine' }));
    const group = host.firstElementChild as HTMLElement;
    expect(group.className).toBe('nx-input-group mine');
    expect(group.querySelector('.nx-input-addon svg')).not.toBeNull();
    expect(group.querySelectorAll('.nx-input-addon')[1]?.textContent).toBe('kg');
    unmount();
  });

  it('v-model carries checkbox and switch presses', () => {
    const checks: boolean[] = [];
    const a = mount(() => h(NxCheckbox, { label: 'قبول', 'onUpdate:modelValue': (v: boolean) => checks.push(v) }));
    toggle(a.host.querySelector('input') as HTMLInputElement);
    expect(checks).toEqual([true]);
    a.unmount();

    const b = mount(() => h(NxSwitch, { description: 'روشن' }));
    expect((b.host.querySelector('input') as HTMLElement).getAttribute('role')).toBe('switch');
    b.unmount();
  });

  it('picks radio group values', () => {
    const picked: string[] = [];
    const { host, unmount } = mount(() =>
      h(NxRadioGroup, {
        name: 'plan',
        legend: 'طرح',
        options: [
          { value: 'free', label: 'رایگان' },
          { value: 'pro', label: 'حرفه‌ای' },
        ],
        'onUpdate:modelValue': (v: string) => picked.push(v),
      }),
    );
    const radios = host.querySelectorAll('input[type="radio"]');
    expect(radios.length).toBe(2);
    (radios[1] as HTMLInputElement).click();
    expect(picked).toEqual(['pro']);
    unmount();
  });

  it('normalises Persian digits in the one-time code', () => {
    const received: string[] = [];
    let completed = '';
    const { host, unmount } = mount(() =>
      h(NxOtpInput, {
        length: 4,
        'onUpdate:modelValue': (v: string) => received.push(v),
        onComplete: (v: string) => {
          completed = v;
        },
      }),
    );
    const input = host.querySelector('input') as HTMLInputElement;
    expect(input.getAttribute('aria-label')).toBe('Verification code');
    type(input, '۱۲a۳۴');
    expect(received.at(-1)).toBe('1234');
    expect(completed).toBe('1234');
    expect(host.querySelectorAll('.nx-otp-slot').length).toBe(4);
    unmount();
  });

  it('tracks the slider fraction', () => {
    const { host, unmount } = mount(() => h(NxSlider, { modelValue: 50, min: 0, max: 200, showValue: true }));
    const root = host.firstElementChild as HTMLElement;
    expect(root.style.getPropertyValue('--nx-pct')).toBe('25%');
    expect((root.querySelector('.nx-slider-value') as HTMLElement).textContent).toBe('50');
    expect((host.querySelector('input') as HTMLInputElement).value).toBe('50');
    unmount();
  });

  it('chips expose removal with the child name', () => {
    const { host, unmount } = mount(() => h(NxChip, { onRemove: () => {} }, () => 'گزارش.pdf'));
    expect(host.querySelector('.nx-chip')?.textContent).toContain('گزارش.pdf');
    expect(host.querySelector('.nx-chip-remove')?.getAttribute('aria-label')).toBe('Remove گزارش.pdf');
    unmount();

    const bare = mount(() => h(NxChip, () => 'بدون دکمه'));
    expect(bare.host.querySelector('.nx-chip-remove')).toBeNull();
    bare.unmount();
  });
});
