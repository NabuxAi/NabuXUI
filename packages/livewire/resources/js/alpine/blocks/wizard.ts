/**
 * Alpine part for the wizard block. The Blade component renders the head,
 * the progress bar, the steps bar, every step's pane and the review pane
 * server-side; this walks between them: the panes glide past each other while
 * the viewport's height morphs (core `morphShell` writes --nx-morph-h), a
 * "next" is refused until the step in view passes the form's own constraint
 * validation (the other panes are disabled fieldsets, so they never judge) —
 * the pane shakes, the footer error lights up and the first bad field is
 * focused — and the review pane reads the answers back from the form's own
 * controls. The final submit re-enables every pane first, so a `wire:submit`
 * (or a plain POST) carries all of the answers.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installWizardBlocks } from './alpine/blocks/wizard';
 *   installWizardBlocks(Alpine);   // x-data="nxWizard(config)"
 */
import { type Cleanup, morphShell, reveal } from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

export interface WizardStepJson {
  id: string;
  title: string;
  description?: string | null;
}

export interface WizardLabels {
  review: string;
  submit: string;
  progress: string;
  of: string;
  announce: string;
  invalid: string;
  edit: string;
  editStep: string;
  empty: string;
  yes: string;
  back: string;
  next: string;
}

export interface WizardConfig {
  steps: WizardStepJson[];
  labels: WizardLabels;
  locale?: string;
  review?: boolean;
  start?: number;
}

export interface WizardSummaryRow {
  label: string;
  value: string;
  empty: boolean;
}

export interface WizardSummaryGroup {
  step: number;
  title: string;
  rows: WizardSummaryRow[];
}

/** `:count` → the number, in the page's digits. */
const withParams = (template: string, params: Record<string, string | number>) =>
  template.replace(/:(\w+)/g, (_, name: string) => String(params[name] ?? `:${name}`));

/** A control's question: the explicit data-nx-summary label, its <label>, its aria-label, or its name. */
function labelOf(control: HTMLElement, name: string): string {
  const explicit = control.getAttribute('data-nx-summary');
  if (explicit && explicit !== 'off') return explicit;
  const label = (control as HTMLInputElement).labels?.item(0)?.textContent?.trim();
  return label || control.getAttribute('aria-label') || name;
}

const SKIPPED_TYPES = new Set(['hidden', 'password', 'button', 'submit', 'reset', 'image']);

/**
 * The answers so far, one group per step — the same rules the React side
 * follows: only named, enabled, visible-kinds controls take part (passwords
 * never do), radios answer as their chosen label, checkbox groups as their
 * checked labels, and a lone checkbox whose own label is the question answers
 * "yes". Values are read as text and put in with textContent — never markup.
 */
export function collectWizardSummary(
  form: HTMLElement,
  stepCount: number,
  words: { empty: string; yes: string },
): WizardSummaryGroup[] {
  const groups: WizardSummaryGroup[] = [];
  for (let s = 0; s < stepCount; s++) {
    const pane = form.querySelector<HTMLElement>(`.nx-wizard-pane[data-step="${s}"]`);
    if (!pane) continue;
    const byName = new Map<string, { label: string; values: string[] }>();
    pane.querySelectorAll<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>('input, select, textarea').forEach((control) => {
      const type = (control.getAttribute('type') ?? '').toLowerCase();
      if (SKIPPED_TYPES.has(type) || control.disabled || control.getAttribute('data-nx-summary') === 'off') return;
      const name = control.getAttribute('name');
      if (!name) return;
      let row = byName.get(name);
      if (!row) {
        row = { label: labelOf(control, name), values: [] };
        byName.set(name, row);
      }
      const own = labelOf(control, name);
      if (type === 'radio' && control instanceof HTMLInputElement) {
        if (control.checked) row.values = [own];
      } else if (type === 'checkbox' && control instanceof HTMLInputElement) {
        if (control.checked) row.values.push(own === row.label ? words.yes : own);
      } else if (control instanceof HTMLSelectElement) {
        row.values = Array.from(control.selectedOptions).map((option) => option.textContent?.trim() ?? '');
      } else if (type === 'file' && control instanceof HTMLInputElement) {
        row.values = Array.from(control.files ?? []).map((file) => file.name);
      } else if (control.value.trim() !== '') {
        row.values.push(control.value.trim());
      }
    });
    const rows: WizardSummaryRow[] = Array.from(byName).map(([name, row]) => {
      const answered = row.values.filter((value) => value !== '');
      return {
        label: row.label || name,
        value: answered.length > 0 ? answered.join('، ') : words.empty,
        empty: answered.length === 0,
      };
    });
    if (rows.length > 0) groups.push({ step: s, title: pane.getAttribute('data-title') ?? '', rows });
  }
  return groups;
}

export function installWizardBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxWizard', (config: WizardConfig) => {
    interface WizardSelf {
      current: number;
      invalid: boolean;
      announce: string;
      summary: WizardSummaryGroup[];
      unmorph: Cleanup;
      readonly total: () => number;
      readonly atEnd: () => boolean;
      pos: (index: number) => string;
      status: (index: number) => string;
      percent: () => number;
      progressText: () => string;
      announceFor: (index: number) => string;
      stepTitle: (index: number) => string;
      editLabel: (step: number) => string;
      goTo: (index: number) => void;
      jump: (index: number) => void;
      back: () => void;
      submit: (event: Event) => void;
      collect: () => void;
      focusPane: () => void;
      paint: () => void;
      $root: HTMLElement;
    }

    return {
      current: Math.max(0, Math.floor(config.start ?? 0)),
      /** A refused hop: the shake, the footer error, and the first bad field focused. */
      invalid: false as boolean,
      announce: '',
      summary: [] as WizardSummaryGroup[],
      unmorph: (() => {}) as Cleanup,

      total(this: Self<WizardSelf>) {
        return config.steps.length + (config.review === false ? 0 : 1);
      },

      atEnd(this: Self<WizardSelf>) {
        return this.current >= this.total() - 1;
      },

      init(this: Self<WizardSelf>) {
        reveal(this.$root, { once: true });
        this.unmorph = morphShell(this.$refs.viewport as HTMLElement, {
          content: this.$refs.measure as HTMLElement,
          axis: 'block',
        });
        this.current = Math.min(this.current, Math.max(0, this.total() - 1));
        this.announce = this.announceFor(this.current);
      },

      destroy(this: Self<{ unmorph: Cleanup }>) {
        this.unmorph();
      },

      /** Where a pane sits: toward the reading start, current, or toward the end. */
      pos(this: Self<WizardSelf>, index: number) {
        return index < this.current ? 'before' : index === this.current ? 'current' : 'after';
      },

      status(this: Self<WizardSelf>, index: number) {
        return index < this.current ? 'complete' : index === this.current ? 'current' : 'upcoming';
      },

      percent(this: Self<WizardSelf>) {
        return Math.round((this.current / Math.max(1, this.total() - 1)) * 100);
      },

      progressText(this: Self<WizardSelf>) {
        const fmt = new Intl.NumberFormat(config.locale);
        return withParams(config.labels.progress, { current: fmt.format(this.current + 1), total: fmt.format(this.total()) });
      },

      stepTitle(this: Self<WizardSelf>, index: number) {
        return index >= config.steps.length ? config.labels.review : (config.steps[index]?.title ?? '');
      },

      /** "Back to …" — the accessible name of a completed step's jump and the review's edit button. */
      editLabel(this: Self<WizardSelf>, step: number) {
        return withParams(config.labels.editStep, { title: this.stepTitle(step) });
      },

      announceFor(this: Self<WizardSelf>, index: number) {
        const fmt = new Intl.NumberFormat(config.locale);
        return withParams(config.labels.announce, {
          current: fmt.format(index + 1),
          total: fmt.format(this.total()),
          title: this.stepTitle(index),
        });
      },

      /** Walk to a pane (either direction); focus lands on the pane that arrives. */
      goTo(this: Self<WizardSelf>, index: number) {
        const next = Math.max(0, Math.min(index, this.total() - 1));
        if (next === this.current) return;
        this.invalid = false;
        this.current = next;
        this.announce = this.announceFor(next);
        this.$dispatch('nx-step-change', { step: next });
        if (config.review !== false && next === this.total() - 1) this.collect();
        this.$nextTick(() => this.focusPane());
      },

      /** A completed step's jump (from the steps bar or the review's edit button). */
      jump(this: Self<WizardSelf>, index: number) {
        if (index < this.current) this.goTo(index);
      },

      back(this: Self<WizardSelf>) {
        this.goTo(this.current - 1);
      },

      /** The submit button is always type=submit, so Enter works anywhere: route it here. */
      submit(this: Self<WizardSelf>, event: Event) {
        const form = this.$root as HTMLFormElement;
        // Only the current pane is enabled, so the form judges the step in view.
        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          this.invalid = true;
          this.announce = config.labels.invalid;
          form.reportValidity(); // focuses the first offending field itself
          return;
        }
        this.invalid = false;
        if (!this.atEnd()) {
          event.preventDefault();
          this.goTo(this.current + 1);
          return;
        }
        // The last hop: re-enable every pane so no answer is dropped by the
        // request (disabled fieldsets do not submit), then let it through —
        // a wire:submit or a plain POST goes on untouched.
        for (const pane of form.querySelectorAll<HTMLFieldSetElement>('fieldset.nx-wizard-pane')) pane.disabled = false;
        this.$dispatch('nx-submit');
      },

      /** Read the answers back from the form's own controls, as text. */
      collect(this: Self<WizardSelf>) {
        this.summary = collectWizardSummary(this.$root, config.steps.length, {
          empty: config.labels.empty,
          yes: config.labels.yes,
        });
      },

      focusPane(this: Self<WizardSelf>) {
        const pane = this.$root.querySelector<HTMLElement>('.nx-wizard-pane[data-pos="current"]');
        const control = pane?.querySelector<HTMLElement>('input:not([type="hidden"]), select, textarea, button');
        (control ?? pane)?.focus({ preventScroll: true });
      },

      /** Keep the count chip, the progress bar and the error line honest after any change. */
      paint(this: Self<WizardSelf>) {
        const root = this.$root;
        const numbers = root.querySelectorAll<HTMLElement>('.nx-wizard-count .nx-number');
        if (numbers[0]) renderNumber(numbers[0]!, this.current + 1, config.locale);
        if (numbers[1]) renderNumber(numbers[1]!, this.total(), config.locale);

        const progress = root.querySelector<HTMLElement>('.nx-wizard-progress');
        if (progress) {
          const percent = this.percent();
          progress.setAttribute('aria-valuenow', String(percent));
          progress.setAttribute('aria-valuetext', this.progressText());
          const fill = progress.querySelector<HTMLElement>('.nx-wizard-progress-fill');
          if (fill) fill.style.setProperty('--nx-wizard-p', String(percent));
        }
      },
    };
  });
}
