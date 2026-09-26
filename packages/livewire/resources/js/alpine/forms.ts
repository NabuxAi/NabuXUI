/**
 * Form controls. The Blade components render native elements that carry the
 * app's wire:model; these Alpine parts only add what the element cannot do on
 * its own (slots for the code, the value bubble, drag-and-drop, the composer).
 */
import { burst, copyText } from '@nabuxai/ui-core';
import { renderNumber } from './number';
import type { AlpineLike, Magics } from './types';

type Self<T> = T & Magics;

/** Persian and Arabic-Indic digits type as themselves; the value stores ASCII. */
const asciiDigits = (value: string) =>
  value.replace(/[۰-۹]/g, (d) => String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d))).replace(/[٠-٩]/g, (d) => String('٠١٢٣٤٥٦٧٨٩'.indexOf(d)));

export function installForms(Alpine: AlpineLike): void {
  /* ---- One-time code ---------------------------------------------------------- */
  Alpine.data('nxOtp', (length = 6, alphanumeric = false) => ({
    code: '',
    focused: false,
    length,

    init(this: Self<{ code: string; read: () => void }>) {
      this.read();
    },

    read(this: Self<{ code: string; length: number }>) {
      const input = this.$refs.input as HTMLInputElement;
      const clean = asciiDigits(input.value).replace(alphanumeric ? /[^0-9a-z]/gi : /\D/g, '').slice(0, this.length);
      if (clean !== input.value) {
        input.value = clean;
        // Let wire:model see the cleaned value.
        input.dispatchEvent(new Event('input', { bubbles: true }));
      }
      this.code = clean;
      if (clean.length === this.length) this.$dispatch('nx-complete', clean);
    },

    char(this: { code: string }, index: number) {
      return this.code[index] ?? '';
    },

    active(this: { code: string; focused: boolean; length: number }, index: number) {
      return this.focused && index === Math.min(this.code.length, this.length - 1);
    },
  }));

  /* ---- Slider -------------------------------------------------------------------- */
  Alpine.data('nxSlider', (locale?: string, format?: Intl.NumberFormatOptions) => ({
    shown: '',

    init(this: Self<{ sync: () => void }>) {
      this.sync();
      this.$refs.input.addEventListener('input', () => this.sync());
      document.addEventListener('livewire:morph.updated', () => this.sync());
    },

    sync(this: Self<{ shown: string }>) {
      const input = this.$refs.input as HTMLInputElement;
      const min = Number(input.min || 0);
      const max = Number(input.max || 100);
      const fraction = (Number(input.value) - min) / (max - min || 1);
      this.$root.style.setProperty('--nx-pct', `${fraction * 100}%`);
      this.$root.style.setProperty('--nx-frac', String(fraction));
      this.shown = new Intl.NumberFormat(locale, format).format(Number(input.value));
    },
  }));

  /* ---- File drop (Livewire uploads its files; this shows the progress) ------------------- */
  Alpine.data('nxFileDrop', () => ({
    dragging: false,
    depth: 0,
    files: [] as Array<{ name: string; size: number; progress: number; status: 'uploading' | 'done' | 'error' }>,

    init(this: Self<{ files: Array<{ name: string; size: number; progress: number; status: string }>; pick: () => void }>) {
      const input = this.$refs.input as HTMLInputElement;
      input.addEventListener('change', () => this.pick());
      input.addEventListener('livewire-upload-progress', ((event: CustomEvent<{ progress: number }>) => {
        for (const file of this.files) if (file.status === 'uploading') file.progress = event.detail.progress;
      }) as EventListener);
      input.addEventListener('livewire-upload-finish', () => {
        for (const file of this.files) if (file.status === 'uploading') Object.assign(file, { status: 'done', progress: 100 });
      });
      input.addEventListener('livewire-upload-error', () => {
        for (const file of this.files) if (file.status === 'uploading') file.status = 'error';
      });
    },

    pick(this: Self<{ files: Array<{ name: string; size: number; progress: number; status: string }> }>) {
      const input = this.$refs.input as HTMLInputElement;
      const picked = Array.from(input.files ?? []).map((file) => ({ name: file.name, size: file.size, progress: 0, status: 'uploading' }));
      this.files = input.multiple ? [...this.files.filter((f) => f.status !== 'uploading'), ...picked] : picked;
    },

    enter(this: { dragging: boolean; depth: number }, event: DragEvent) {
      event.preventDefault();
      this.depth += 1;
      this.dragging = true;
    },

    leave(this: { dragging: boolean; depth: number }) {
      this.depth = Math.max(0, this.depth - 1);
      if (this.depth === 0) this.dragging = false;
    },

    drop(this: Self<{ dragging: boolean; depth: number }>, event: DragEvent) {
      event.preventDefault();
      this.depth = 0;
      this.dragging = false;
      const input = this.$refs.input as HTMLInputElement;
      if (input.disabled || !event.dataTransfer?.files.length) return;
      input.files = event.dataTransfer.files;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    },

    size(bytes: number) {
      const units = ['B', 'KB', 'MB', 'GB'];
      let i = 0;
      let value = bytes;
      while (value >= 1024 && i < units.length - 1) {
        value /= 1024;
        i += 1;
      }
      return `${value.toFixed(value < 10 && i > 0 ? 1 : 0)} ${units[i]}`;
    },
  }));

  /* ---- Prompt input ---------------------------------------------------------------------- */
  Alpine.data('nxPrompt', (streaming: boolean = false) => ({
    streaming,
    text: '',

    init(this: Self<{ text: string }>) {
      const area = this.$refs.area as HTMLTextAreaElement;
      this.text = area.value;
      area.addEventListener('input', () => (this.text = area.value));
    },

    key(this: Self<{ streaming: boolean }>, event: KeyboardEvent) {
      if (event.key !== 'Enter' || event.shiftKey || event.isComposing) return;
      event.preventDefault();
      if (!this.streaming) (this.$root as HTMLFormElement).requestSubmit();
    },

    get ready(): boolean {
      return (this as unknown as { text: string }).text.trim().length > 0;
    },
  }));

  /* ---- Copy ----------------------------------------------------------------------------------- */
  Alpine.data('nxCopy', (value: string, timeout = 1600) => ({
    copied: false,
    async copy(this: { copied: boolean }) {
      if (!(await copyText(value))) return;
      this.copied = true;
      setTimeout(() => (this.copied = false), timeout);
    },
  }));

  /* ---- Like -------------------------------------------------------------------------------------- */
  Alpine.data('nxLike', (liked: boolean = false, count: number | null = null, locale?: string) => ({
    liked,
    count,

    init(this: Self<{ liked: boolean; count: number | null; paint: () => void }>) {
      this.$watch('liked', () => this.count !== null && this.paint());
    },

    paint(this: Self<{ liked: boolean; count: number | null }>) {
      const roll = this.$root.querySelector<HTMLElement>('.nx-number');
      if (roll && this.count !== null) renderNumber(roll, this.count + (this.liked ? 1 : 0), locale);
    },

    toggle(this: Self<{ liked: boolean }>) {
      this.liked = !this.liked;
      if (this.liked) burst(this.$root);
    },
  }));
}
