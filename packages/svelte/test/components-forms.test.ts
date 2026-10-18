// DOM-fidelity tests for the forms seats (text, button, form) — the same
// markup contract @nabuxai/ui-react renders: classes, data attributes, aria
// wiring and the busy-button press guard, checked against happy-dom.
import { mount, tick, unmount } from 'svelte';
import { describe, expect, it } from 'vitest';
import { TextReveal } from '../src/components/text.svelte';
import { Button, CopyButton, LikeButton } from '../src/components/button.svelte';
import { Field, OtpInput, PromptInput, Switch } from '../src/components/form.svelte';
import FieldInput from './fixtures/field-input.svelte';

type AnyComponent = new (...args: never[]) => unknown;

function render(Component: AnyComponent, props: Record<string, unknown>) {
  const host = document.createElement('div');
  document.body.appendChild(host);
  const instance = mount(Component as never, { target: host, props });
  return { host, done: () => (unmount(instance), host.remove()) };
}

describe('@nabuxai/ui-svelte forms seats', () => {
  it('TextReveal mirrors React: pieces, rtl direction, screen-reader copy', () => {
    const { host, done } = render(TextReveal, { text: 'سلام دنیای جدید', by: 'word', trigger: 'view', delay: 120 });
    try {
      const root = host.querySelector('.nx-text-reveal');
      expect(root?.getAttribute('data-by')).toBe('word');
      expect(root?.getAttribute('data-trigger')).toBe('view');
      expect(root?.getAttribute('data-nx-reveal')).toBe('');
      expect(root?.getAttribute('style')).toContain('--nx-delay: 120ms');
      expect(root?.querySelector('.nx-visually-hidden')?.textContent).toBe('سلام دنیای جدید');
      const pieces = root?.querySelector('.nx-text-pieces');
      expect(pieces?.getAttribute('dir')).toBe('rtl');
      expect(pieces?.getAttribute('aria-hidden')).toBe('true');
      const items = pieces?.querySelectorAll('.nx-text-piece');
      expect(items?.length).toBe(3);
      expect(items?.[0]?.getAttribute('style')).toContain('--nx-i: 0');
      expect(items?.[0]?.textContent).toBe('سلام ');
    } finally {
      done();
    }
  });

  it('Button mirrors React: data attributes, status marks, busy press guard', () => {
    let clicked = 0;
    const { host, done } = render(Button, {
      variant: 'primary',
      size: 'lg',
      shape: 'pill',
      loading: true,
      text: 'ثبت',
      onclick: () => {
        clicked += 1;
      },
    });
    try {
      const btn = host.querySelector('button.nx-button');
      expect(btn?.getAttribute('data-variant')).toBe('primary');
      expect(btn?.getAttribute('data-size')).toBe('lg');
      expect(btn?.getAttribute('data-shape')).toBe('pill');
      expect(btn?.getAttribute('data-status')).toBe('loading');
      expect(btn?.getAttribute('aria-busy')).toBe('true');
      expect(btn?.getAttribute('aria-disabled')).toBe('true');
      expect(btn?.getAttribute('type')).toBe('button');
      expect(btn?.querySelector('.nx-button-text')?.textContent).toBe('ثبت');
      const marks = btn?.querySelector('.nx-button-status');
      expect(marks?.getAttribute('aria-hidden')).toBe('true');
      expect(marks?.querySelector('.nx-spinner')).toBeTruthy();
      expect(marks?.querySelector('.nx-status-check')).toBeTruthy();
      expect(marks?.querySelector('.nx-status-cross')).toBeTruthy();
      btn?.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true }));
      expect(clicked).toBe(0); // شلوغ: فشار نادیده گرفته می‌شود
    } finally {
      done();
    }
  });

  it('Button renders icon names from the core set and anchors in href mode', () => {
    const a = render(Button, { variant: 'ghost', icon: 'check', iconOnly: true, text: 'تایید', 'aria-label': 'تایید' });
    try {
      const iconBtn = a.host.querySelector('button.nx-button[data-icon-only=""]');
      expect(iconBtn?.querySelector('.nx-button-label svg.nx-icon')).toBeTruthy();
      expect(iconBtn?.querySelector('.nx-visually-hidden')).toBeTruthy();
    } finally {
      a.done();
    }
    const b = render(Button, { href: '/away', target: '_blank', text: 'برو' });
    try {
      const anchor = b.host.querySelector('a.nx-button');
      expect(anchor?.getAttribute('href')).toBe('/away');
      expect(anchor?.getAttribute('target')).toBe('_blank');
      expect(anchor?.querySelector('button')).toBeNull();
    } finally {
      b.done();
    }
  });

  it('Field mirrors React: label, required mark, hint and error wiring', () => {
    const { host, done } = render(Field, { label: 'ایمیل', hint: 'هرگز منتشر نمی‌شود', error: 'نامعتبر است', required: true });
    try {
      const field = host.querySelector('.nx-field');
      expect(field?.getAttribute('data-invalid')).toBe('');
      const label = field?.querySelector('label.nx-label');
      const id = label?.getAttribute('for');
      expect(id).toMatch(/^nx-field-/);
      expect(label?.querySelector('.nx-label-required')?.getAttribute('aria-hidden')).toBe('true');
      expect(field?.querySelector('p.nx-hint')?.id).toBe(`${id}-hint`);
      const error = field?.querySelector('p.nx-error');
      expect(error?.id).toBe(`${id}-error`);
      expect(error?.querySelector('svg.nx-icon')).toBeTruthy();
      expect(error?.textContent).toContain('نامعتبر است');
    } finally {
      done();
    }
  });

  it('Field wires the Input nested inside it (context: id, describedby, invalid)', () => {
    const { host, done } = render(FieldInput, {});
    try {
      const input = host.querySelector('input.nx-input');
      expect(input?.id).toBe('phone');
      expect((input?.getAttribute('aria-describedby') ?? '').split(' ').sort()).toEqual(['phone-error', 'phone-hint']);
      expect(input?.getAttribute('aria-invalid')).toBe('true');
    } finally {
      done();
    }
  });

  it('OtpInput normalises Persian digits on input and fills slots', async () => {
    const { host, done } = render(OtpInput, { defaultValue: '', length: 6 });
    try {
      const input = host.querySelector('input.nx-otp-input');
      expect(input?.getAttribute('autocomplete')).toBe('one-time-code');
      // مثل ری‌اکت: فقط ورودی کاربر نرمال می‌شود، نه defaultValue.
      input!.value = '۱۲a۳۴';
      input?.dispatchEvent(new Event('input', { bubbles: true }));
      await tick();
      expect(input?.value).toBe('1234');
      const slots = host.querySelectorAll('.nx-otp-slot');
      expect(slots.length).toBe(6);
      expect(slots[0]?.getAttribute('data-filled')).toBe('');
      expect(slots[4]?.getAttribute('data-filled')).toBeNull();
    } finally {
      done();
    }
  });

  it('Switch renders role=switch; CopyButton and LikeButton carry their aria wiring', () => {
    const s = render(Switch, { label: 'اطلاع‌رسانی' });
    try {
      expect(s.host.querySelector('input[role="switch"]')?.classList.contains('nx-switch')).toBe(true);
      expect(s.host.querySelector('label.nx-choice .nx-choice-label')?.textContent).toBe('اطلاع‌رسانی');
    } finally {
      s.done();
    }
    const c = render(CopyButton, { value: 'xyz' });
    try {
      const btn = c.host.querySelector('button.nx-copy');
      expect(btn?.getAttribute('aria-label')).toBe('Copy');
      expect(btn?.querySelector('.nx-copy-icons svg.nx-copy-idle')).toBeTruthy();
    } finally {
      c.done();
    }
    const l = render(LikeButton, { count: 9 });
    try {
      const btn = l.host.querySelector('button.nx-like');
      expect(btn?.getAttribute('aria-pressed')).toBe('false');
      expect(btn?.getAttribute('aria-label')).toBe('Like');
      expect(l.host.querySelector('.nx-number')).toBeTruthy();
    } finally {
      l.done();
    }
  });

  it('PromptInput submits trimmed text on Enter and flips to stop while streaming', () => {
    const sent: string[] = [];
    const stopped: number[] = [];
    const { host, done } = render(PromptInput, {
      defaultValue: '  سلام دنیا  ',
      onSubmit: (v: string) => sent.push(v),
      onStop: () => stopped.push(1),
    });
    try {
      const area = host.querySelector('textarea.nx-prompt-textarea');
      expect(area?.getAttribute('style')).toContain('--nx-prompt-max: 12lh');
      const send = host.querySelector('button.nx-prompt-send');
      expect(send?.getAttribute('aria-label')).toBe('Send');
      area?.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true, cancelable: true }));
      expect(sent).toEqual(['سلام دنیا']);
    } finally {
      done();
    }
    const b = render(PromptInput, { defaultValue: 'x', streaming: true, onStop: () => stopped.push(1), onSubmit: (v: string) => sent.push(v) });
    try {
      const send = b.host.querySelector('button.nx-prompt-send');
      expect(send?.getAttribute('data-state')).toBe('streaming');
      expect(send?.getAttribute('aria-label')).toBe('Stop generating');
    } finally {
      b.done();
    }
    expect(sent).toEqual(['سلام دنیا']);
    expect(stopped).toEqual([]);
  });
});
