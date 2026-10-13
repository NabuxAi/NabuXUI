/**
 * Wizard (React): a multi-step form shell on top of the shared steps look.
 *
 * Thin over css/blocks/wizard.css. Each step is a pane — a disabled fieldset
 * unless current, so the form's own constraint validation only ever judges the
 * step in view, and a refused hop shakes the pane, lights the footer error and
 * focuses the first bad field. The panes share one viewport whose height
 * morphs (core morphShell) and glide past each other toward the reading
 * direction. The last pane is the review: the answers so far, grouped per
 * step, each group with an "edit" jump back. Step state is controlled
 * (`step` + `onStepChange`) or uncontrolled (`defaultStep`); the final submit
 * takes a promise (the button spins while it runs) or falls through as a
 * native POST with every pane re-enabled first so no answer is dropped.
 */
import {
  type CSSProperties,
  type FormEvent,
  type FormHTMLAttributes,
  type ReactNode,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { type MessageKey, morphShell, reveal } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale, useT } from '../internal/provider';
import { Button } from '../components/button';
import { NumberTicker } from '../components/text';

/** The Intl locale: the prop, or the provider's language. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

/* ---- Types ---------------------------------------------------------------------------- */

export interface WizardStep {
  /** Ties the step to its pane; defaults to its index. */
  id?: string;
  title: ReactNode;
  description?: ReactNode;
  /** The step's fields. */
  content?: ReactNode;
}

/** One answered row of the review pane. */
export interface WizardSummaryRow {
  label: string;
  value: string;
  /** True when nothing was answered — the row says so, in words. */
  empty: boolean;
}

/** One step's rows in the review pane. */
export interface WizardSummaryGroup {
  step: number;
  rows: WizardSummaryRow[];
}

/** The words the wizard says itself; every one overridable per instance. */
export type WizardWord =
  | 'review' | 'submit' | 'progress' | 'of' | 'announce'
  | 'invalid' | 'edit' | 'editStep' | 'empty' | 'yes' | 'back' | 'next';

/** Where each of them lives in the core i18n table. */
const WORD_KEYS: Record<WizardWord, MessageKey> = {
  review: 'wizardReview',
  submit: 'wizardSubmit',
  progress: 'wizardProgress',
  of: 'wizardOf',
  announce: 'wizardAnnounce',
  invalid: 'wizardInvalid',
  edit: 'wizardEdit',
  editStep: 'wizardEditStep',
  empty: 'wizardEmpty',
  yes: 'wizardYes',
  back: 'back',
  next: 'next',
};

type Words = Partial<Record<WizardWord, string>>;

export interface WizardProps extends Omit<FormHTMLAttributes<HTMLFormElement>, 'onSubmit' | 'title' | 'children'> {
  steps: WizardStep[];
  /** The heading above the steps bar. */
  heading?: ReactNode;
  subtitle?: ReactNode;
  /** Add the review pane with the summary of the answers (default). */
  review?: boolean;
  step?: number;
  defaultStep?: number;
  onStepChange?: (step: number) => void;
  /**
   * Called on the final submit (the review pane, or the last step when
   * `review` is off). A returned promise keeps the button loading until it
   * settles; without it the form submits natively (a plain POST to `action`).
   */
  onSubmit?: (event: FormEvent<HTMLFormElement>) => unknown;
  labels?: Words;
  locale?: string;
}

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** A step's title as plain text, when it is plain text (for labels and announcements). */
function textOf(title: ReactNode): string | undefined {
  return typeof title === 'string' && title.trim() !== '' ? title : typeof title === 'number' ? String(title) : undefined;
}

/* ---- The review summary: read back from the form's own controls ------------------------- */

/** A control's question: the explicit data-nx-summary label, its <label>, its aria-label, or its name. */
function labelOf(control: HTMLElement, name: string): string {
  const explicit = control.getAttribute('data-nx-summary');
  if (explicit && explicit !== 'off') return explicit;
  const label = control instanceof HTMLInputElement || control instanceof HTMLSelectElement || control instanceof HTMLTextAreaElement
    ? control.labels?.item(0)?.textContent?.trim()
    : undefined;
  return label || control.getAttribute('aria-label') || name;
}

const SKIPPED_TYPES = new Set(['hidden', 'password', 'button', 'submit', 'reset', 'image']);

/**
 * The answers so far, one group per step. Only named, enabled, visible-kinds
 * controls take part (passwords never do); radios answer as their chosen
 * label, checkbox groups as their checked labels, and a lone checkbox whose
 * own label is the question answers "yes". Values are read as text — never
 * parsed as markup.
 */
export function collectWizardSummary(
  form: HTMLFormElement,
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
    if (rows.length > 0) groups.push({ step: s, rows });
  }
  return groups;
}

/* ---- The component ---------------------------------------------------------------------- */

export function Wizard({
  steps,
  heading,
  subtitle,
  review = true,
  step,
  defaultStep = 0,
  onStepChange,
  onSubmit,
  labels,
  locale,
  className,
  style,
  ...rest
}: WizardProps) {
  const t = useT();
  const language = useLocale();
  const number = useMemo(() => new Intl.NumberFormat(locale ?? INTL[language]), [locale, language]);
  const word = (key: WizardWord) => labels?.[key] ?? t(WORD_KEYS[key]);
  const say = (key: WizardWord, params?: Record<string, string | number>) => labels?.[key] ?? t(WORD_KEYS[key], params);
  const base = `nx-wizard${useId().replace(/:/g, '')}`;
  const total = steps.length + (review ? 1 : 0);
  const [value, setCurrent] = useControllable(step, defaultStep, onStepChange);
  const current = Math.max(0, Math.min(value, Math.max(0, total - 1)));
  const atEnd = current >= total - 1;
  const [invalid, setInvalid] = useState(false);
  const [status, setStatus] = useState<'idle' | 'loading'>('idle');
  const [summary, setSummary] = useState<WizardSummaryGroup[] | null>(null);
  const root = useRef<HTMLFormElement>(null);
  const viewport = useRef<HTMLDivElement>(null);
  const measure = useRef<HTMLDivElement>(null);
  const moved = useRef(false);
  useBehavior(root, reveal, { once: true });

  // The viewport's height follows whichever pane is current (core morphShell).
  useIsoLayoutEffect(() => {
    if (!viewport.current || !measure.current) return;
    return morphShell(viewport.current, { content: measure.current, axis: 'block' });
  }, []);

  // Entering the review reads the answers back from the form's own controls.
  useEffect(() => {
    if (!review || current !== total - 1 || !root.current) return;
    setSummary(collectWizardSummary(root.current, steps.length, { empty: word('empty'), yes: word('yes') }));
  }, [current, total, review, steps.length, labels, language]);

  // Focus follows the pane that just arrived: its first control.
  useEffect(() => {
    if (!moved.current) return;
    moved.current = false;
    const pane = root.current?.querySelector<HTMLElement>('.nx-wizard-pane[data-pos="current"]');
    pane?.querySelector<HTMLElement>('input:not([type="hidden"]), select, textarea, button')?.focus({ preventScroll: true });
  }, [current]);

  /** Where a pane sits: toward the reading start, current, or toward the end. */
  const pos = (i: number) => (i < current ? 'before' : i === current ? 'current' : 'after');
  const statusOf = (i: number) => (i < current ? 'complete' : i === current ? 'current' : 'upcoming');
  const percent = Math.round((current / Math.max(1, total - 1)) * 100);
  const progressText = say('progress', { current: number.format(current + 1), total: number.format(total) });

  const go = (next: number) => {
    const clamped = Math.max(0, Math.min(next, total - 1));
    if (clamped === current) return;
    moved.current = true;
    setInvalid(false);
    setCurrent(clamped);
  };

  const submit = (event: FormEvent<HTMLFormElement>) => {
    const form = event.currentTarget;
    // Only the current pane is enabled, so the form judges the step in view.
    if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
      setInvalid(true);
      form.reportValidity(); // focuses the first offending field itself
      return;
    }
    setInvalid(false);
    if (!atEnd) {
      event.preventDefault();
      go(current + 1);
      return;
    }
    if (!onSubmit) {
      // A native POST: re-enable the panes first so every answer travels with it.
      form.querySelectorAll<HTMLFieldSetElement>('fieldset.nx-wizard-pane').forEach((pane) => {
        pane.disabled = false;
      });
      return; // untouched — the browser submits to `action`
    }
    event.preventDefault();
    setStatus('loading');
    Promise.resolve(onSubmit(event))
      .catch(() => {})
      .finally(() => setStatus('idle'));
  };

  const nextLabel = atEnd ? word('submit') : word('next');
  const announcedTitle = atEnd && review ? textOf(word('review')) : textOf(steps[current]?.title);

  return (
    <form
      ref={root}
      className={cx('nx-wizard', className)}
      data-nx-reveal=""
      noValidate
      aria-labelledby={heading !== undefined ? `${base}-title` : undefined}
      onSubmit={submit}
      {...rest}
    >
      <header className="nx-wizard-head">
        <div>
          {heading !== undefined && (
            <h2 className="nx-wizard-title" id={`${base}-title`}>
              {heading}
            </h2>
          )}
          {subtitle && <p className="nx-wizard-subtitle">{subtitle}</p>}
        </div>
        {steps.length > 0 && (
          <span className="nx-wizard-count" aria-hidden="true">
            <NumberTicker value={current + 1} reveal={false} locale={locale} />
            <span className="nx-wizard-count-sep">{word('of')}</span>
            <NumberTicker value={total} reveal={false} locale={locale} />
          </span>
        )}
      </header>

      {steps.length > 0 && (
        <div
          className="nx-wizard-progress"
          role="progressbar"
          aria-valuemin={0}
          aria-valuemax={100}
          aria-valuenow={percent}
          aria-valuetext={progressText}
        >
          <span className="nx-wizard-progress-track">
            <span className="nx-wizard-progress-fill" style={vars({ '--nx-wizard-p': percent })} />
          </span>
        </div>
      )}

      {steps.length > 0 && (
        <ol className="nx-steps nx-wizard-steps" aria-label={progressText}>
          {steps.map((s, i) => {
            const state = statusOf(i);
            const back = textOf(s.title);
            return (
              <li key={s.id ?? i} className="nx-step" data-status={state} aria-current={state === 'current' ? 'step' : undefined}>
                <button
                  type="button"
                  className="nx-wizard-step-jump"
                  disabled={i >= current}
                  aria-label={i < current && back ? say('editStep', { title: back }) : undefined}
                  onClick={() => go(i)}
                >
                  <span className="nx-step-marker">{state === 'complete' ? <Icon name="check" /> : null}</span>
                  <span className="nx-step-text">
                    <span className="nx-step-title">{s.title}</span>
                    {s.description && <span className="nx-step-description">{s.description}</span>}
                  </span>
                </button>
              </li>
            );
          })}
          {review && (
            <li className="nx-step" data-status={statusOf(total - 1)} aria-current={atEnd ? 'step' : undefined}>
              <button type="button" className="nx-wizard-step-jump" disabled>
                <span className="nx-step-marker" />
                <span className="nx-step-text">
                  <span className="nx-step-title">{word('review')}</span>
                </span>
              </button>
            </li>
          )}
        </ol>
      )}

      <div ref={viewport} className="nx-wizard-viewport">
        <div ref={measure} className="nx-wizard-measure">
          {steps.map((s, i) => (
            <fieldset
              key={s.id ?? i}
              className="nx-wizard-pane"
              data-step={i}
              data-pos={pos(i)}
              data-invalid={invalid && i === current ? '' : undefined}
              disabled={i !== current}
            >
              <legend>{textOf(s.title) ?? word('review')}</legend>
              {s.content}
            </fieldset>
          ))}
          {review && (
            <fieldset
              className="nx-wizard-pane"
              data-step="review"
              data-pos={pos(total - 1)}
              data-invalid={invalid && atEnd ? '' : undefined}
              disabled={!atEnd}
            >
              <legend>{word('review')}</legend>
              <dl className="nx-wizard-summary">
                {(summary ?? []).map((group) => {
                  const title = textOf(steps[group.step]?.title);
                  return (
                    <div className="nx-wizard-summary-group" key={group.step}>
                      <div className="nx-wizard-summary-head">
                        <dt className="nx-wizard-summary-step">{steps[group.step]?.title}</dt>
                        <button
                          type="button"
                          className="nx-wizard-summary-edit"
                          aria-label={title ? say('editStep', { title }) : undefined}
                          onClick={() => go(group.step)}
                        >
                          <Icon name="edit" />
                          <span>{word('edit')}</span>
                        </button>
                      </div>
                      <div className="nx-wizard-summary-rows">
                        {group.rows.map((row, r) => (
                          <div className="nx-wizard-summary-row" key={r}>
                            <span className="nx-wizard-summary-label">{row.label}</span>
                            <dd className="nx-wizard-summary-value" data-empty={row.empty ? '' : undefined}>
                              {row.value}
                            </dd>
                          </div>
                        ))}
                      </div>
                    </div>
                  );
                })}
              </dl>
            </fieldset>
          )}
        </div>
      </div>

      <p className="nx-visually-hidden" role="status" aria-live="polite">
        {announcedTitle
          ? say('announce', { current: number.format(current + 1), total: number.format(total), title: announcedTitle })
          : progressText}
      </p>

      <footer className="nx-wizard-foot">
        <Button
          type="button"
          variant="ghost"
          size="lg"
          className="nx-wizard-back"
          data-off={current === 0 ? 'on' : 'off'}
          onClick={() => go(current - 1)}
        >
          <Icon name="arrow-left" />
          <span>{word('back')}</span>
        </Button>
        <p className="nx-wizard-error" data-invalid={invalid ? '' : undefined} aria-live="polite">
          {invalid && (
            <>
              <Icon name="alert-circle" />
              {word('invalid')}
            </>
          )}
        </p>
        <Button type="submit" variant="primary" size="lg" className="nx-wizard-next" loading={status === 'loading'} aria-label={nextLabel}>
          <span className="nx-wizard-next-labels" aria-hidden="true">
            <span data-current={atEnd ? undefined : ''}>{word('next')}</span>
            <span data-current={atEnd ? '' : undefined}>{word('submit')}</span>
          </span>
        </Button>
      </footer>
    </form>
  );
}
