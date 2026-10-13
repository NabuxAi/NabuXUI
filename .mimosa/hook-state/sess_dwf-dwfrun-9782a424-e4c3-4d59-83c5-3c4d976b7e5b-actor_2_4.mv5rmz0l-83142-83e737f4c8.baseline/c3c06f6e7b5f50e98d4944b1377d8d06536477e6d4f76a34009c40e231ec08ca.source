/**
 * Auth (React): the sign-in / sign-up / reset card.
 *
 * A two-column card over css/blocks/auth-card.css: a brand panel with a deep
 * plate, drifting aurora glows and a hairline grid (pure CSS), and a form
 * panel whose three panes — login, register, forgot — share one viewport.
 * Switching modes glides the panes past each other and morphs the card's
 * height (core morphShell); the title, subtitle, submit label and switch row
 * cross-fade between their stacked variants so nothing jumps. Fields are the
 * plain Field/Input components; the submit button is the plain Button with
 * its own loading state — no transaction machinery here.
 */
import {
  type CSSProperties,
  type FormEvent,
  type FormHTMLAttributes,
  type ReactNode,
  useEffect,
  useId,
  useRef,
  useState,
} from 'react';
import { type MessageKey, morphShell, reveal } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useT } from '../internal/provider';
import { Button } from '../components/button';
import { Checkbox, Field, Input } from '../components/form';

export type AuthMode = 'login' | 'register' | 'forgot';

/** The words the card says itself, one key per mode, field and switch row. */
export type AuthWord =
  | 'login' | 'loginTitle' | 'loginSubtitle'
  | 'register' | 'registerTitle' | 'registerSubtitle'
  | 'forgot' | 'forgotTitle' | 'forgotSubtitle'
  | 'email' | 'password' | 'name' | 'remember'
  | 'forgotLink' | 'noAccount' | 'hasAccount' | 'backToLogin';

/** Where each of them lives in the core i18n table. */
const WORD_KEYS: Record<AuthWord, MessageKey> = {
  login: 'authLogin',
  loginTitle: 'authLoginTitle',
  loginSubtitle: 'authLoginSubtitle',
  register: 'authRegister',
  registerTitle: 'authRegisterTitle',
  registerSubtitle: 'authRegisterSubtitle',
  forgot: 'authForgot',
  forgotTitle: 'authForgotTitle',
  forgotSubtitle: 'authForgotSubtitle',
  email: 'authEmail',
  password: 'authPassword',
  name: 'authName',
  remember: 'authRemember',
  forgotLink: 'authForgotLink',
  noAccount: 'authNoAccount',
  hasAccount: 'authHasAccount',
  backToLogin: 'authBackToLogin',
};

type Words = Partial<Record<AuthWord, string>>;

/** The block's own words in the provider's language, overridable per instance. */
function useWords(labels?: Words) {
  const t = useT();
  return (key: AuthWord) => labels?.[key] ?? t(WORD_KEYS[key]);
}

const MODES: AuthMode[] = ['login', 'register', 'forgot'];
const titleKey: Record<AuthMode, AuthWord> = { login: 'loginTitle', register: 'registerTitle', forgot: 'forgotTitle' };
const subtitleKey: Record<AuthMode, AuthWord> = { login: 'loginSubtitle', register: 'registerSubtitle', forgot: 'forgotSubtitle' };
const submitKey: Record<AuthMode, AuthWord> = { login: 'login', register: 'register', forgot: 'forgot' };

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** One pane of the card: what `data-pos` says in the CSS, and disabled unless
 * current — so the other panes's required fields neither validate nor submit. */
function AuthPane({ mode, current, legend, children }: { mode: AuthMode; current: AuthMode; legend: string; children: ReactNode }) {
  const pos = MODES.indexOf(mode) < MODES.indexOf(current) ? 'before' : mode === current ? 'current' : 'after';
  return (
    <fieldset className="nx-auth-card-pane" data-pane={mode} data-pos={pos} disabled={mode !== current}>
      <legend>{legend}</legend>
      {children}
    </fieldset>
  );
}

export interface AuthCardProps extends Omit<FormHTMLAttributes<HTMLFormElement>, 'onSubmit' | 'title'> {
  /** The product name on the brand panel. */
  brand?: ReactNode;
  /** The square mark (the brand's first letter by default). */
  brandMark?: ReactNode;
  /** A line under the brand name. */
  tagline?: ReactNode;
  /** The lines above the panel's foot, each with a check. */
  perks?: ReactNode[];
  mode?: AuthMode;
  defaultMode?: AuthMode;
  onModeChange?: (mode: AuthMode) => void;
  /**
   * Called on submit of any mode. A returned promise keeps the button loading
   * until it settles; without it the form submits natively (a plain POST to
   * `action`).
   */
  onSubmit?: (mode: AuthMode, event: FormEvent<HTMLFormElement>) => unknown;
  /** Extra rows inside a mode's pane (a "sign in with …" row, terms…). */
  extras?: Partial<Record<AuthMode, ReactNode>>;
  labels?: Words;
}

export function AuthCard({
  brand = 'Nabu',
  brandMark,
  tagline,
  perks,
  mode,
  defaultMode = 'login',
  onModeChange,
  onSubmit,
  extras,
  labels,
  className,
  ...rest
}: AuthCardProps) {
  const word = useWords(labels);
  const base = `nx-auth${useId().replace(/:/g, '')}`;
  const [current, setCurrent] = useControllable(mode, defaultMode, onModeChange);
  const [status, setStatus] = useState<'idle' | 'loading'>('idle');
  const root = useRef<HTMLFormElement>(null);
  const viewport = useRef<HTMLDivElement>(null);
  const measure = useRef<HTMLDivElement>(null);
  const perksList = useRef<HTMLUListElement>(null);
  const moved = useRef(false);
  const hasPerks = Array.isArray(perks) && perks.length > 0;
  useBehavior(root, reveal, { once: true });
  useBehavior(perksList, reveal, { once: true, stagger: true }, hasPerks);

  // The viewport's height follows whichever pane is current (core morphShell).
  useIsoLayoutEffect(() => {
    if (!viewport.current || !measure.current) return;
    return morphShell(viewport.current, { content: measure.current, axis: 'block' });
  }, []);

  // Focus follows the mode: the first control of the pane that just arrived.
  useEffect(() => {
    if (!moved.current) return;
    moved.current = false;
    root.current?.querySelector<HTMLElement>(`[data-pane="${current}"] input, [data-pane="${current}"] button:not(.nx-auth-card-submit)`)?.focus({ preventScroll: true });
  }, [current]);

  const go = (next: AuthMode) => {
    if (next === current) return;
    moved.current = true;
    setCurrent(next);
  };

  const submit = (event: FormEvent<HTMLFormElement>) => {
    if (status === 'loading' || !onSubmit) return; // a native POST goes through untouched
    event.preventDefault();
    const form = event.currentTarget;
    if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
      form.reportValidity();
      return;
    }
    setStatus('loading');
    Promise.resolve(onSubmit(current, event))
      .catch(() => {})
      .finally(() => setStatus('idle'));
  };

  const submitLabel = word(submitKey[current]);

  return (
    <form ref={root} className={cx('nx-auth-card', className)} data-mode={current} data-nx-reveal="" noValidate onSubmit={submit} {...rest}>
      <aside className="nx-auth-card-media" aria-hidden="true">
        <span className="nx-auth-card-glow" data-i="1" />
        <span className="nx-auth-card-glow" data-i="2" />
        <span className="nx-auth-card-glow" data-i="3" />
        <span className="nx-auth-card-gridlines" />
        <div className="nx-auth-card-brand">
          <span className="nx-auth-card-mark">{brandMark ?? (typeof brand === 'string' ? Array.from(brand)[0] : 'N')}</span>
          {brand && <p className="nx-auth-card-name">{brand}</p>}
          {tagline && <p className="nx-auth-card-tagline">{tagline}</p>}
        </div>
        {hasPerks && (
          <ul ref={perksList} className="nx-auth-card-perks" data-nx-reveal="group">
            {perks!.map((perk, i) => (
              <li key={i} style={vars({ '--nx-i': i })}>
                <Icon name="check" />
                <span>{perk}</span>
              </li>
            ))}
          </ul>
        )}
      </aside>

      <div className="nx-auth-card-body">
        <div className="nx-auth-card-head">
          <h2 id={`${base}-title`} className="nx-auth-card-title">
            {MODES.map((m) => (
              <span key={m} data-current={m === current ? '' : undefined} aria-hidden={m !== current || undefined}>
                {word(titleKey[m])}
              </span>
            ))}
          </h2>
          <p className="nx-auth-card-subtitle">
            {MODES.map((m) => (
              <span key={m} data-current={m === current ? '' : undefined} aria-hidden={m !== current || undefined}>
                {word(subtitleKey[m])}
              </span>
            ))}
          </p>
        </div>

        <div ref={viewport} className="nx-auth-card-viewport">
          <div ref={measure} className="nx-auth-card-measure">
            <AuthPane mode="login" current={current} legend={word('login')}>
              <Field label={word('email')} required>
                <Input name="email" type="email" dir="ltr" autoComplete="email" startAddon="mail" required placeholder="you@example.com" />
              </Field>
              <Field label={word('password')} required>
                <Input name="password" type="password" dir="ltr" autoComplete="current-password" startAddon="lock" required />
              </Field>
              <div className="nx-auth-card-row">
                <Checkbox name="remember" label={word('remember')} defaultChecked />
                <button type="button" className="nx-auth-card-link" onClick={() => go('forgot')}>
                  {word('forgotLink')}
                </button>
              </div>
              {extras?.login}
            </AuthPane>

            <AuthPane mode="register" current={current} legend={word('register')}>
              <Field label={word('name')} required>
                <Input name="name" autoComplete="name" startAddon="user" required />
              </Field>
              <Field label={word('email')} required>
                <Input name="email" type="email" dir="ltr" autoComplete="email" startAddon="mail" required placeholder="you@example.com" />
              </Field>
              <Field label={word('password')} required>
                <Input name="password" type="password" dir="ltr" autoComplete="new-password" startAddon="lock" required />
              </Field>
              {extras?.register}
            </AuthPane>

            <AuthPane mode="forgot" current={current} legend={word('forgot')}>
              <Field label={word('email')} hint={word('forgotSubtitle')} required>
                <Input name="email" type="email" dir="ltr" autoComplete="email" startAddon="mail" required placeholder="you@example.com" />
              </Field>
              {extras?.forgot}
            </AuthPane>
          </div>
        </div>

        <p className="nx-visually-hidden" aria-live="polite">
          {word(titleKey[current])}
        </p>

        <Button type="submit" variant="primary" size="lg" block className="nx-auth-card-submit" loading={status === 'loading'} aria-label={submitLabel}>
          <span className="nx-auth-card-submit-labels" aria-hidden="true">
            {MODES.map((m) => (
              <span key={m} data-current={m === current ? '' : undefined}>
                {word(submitKey[m])}
              </span>
            ))}
          </span>
        </Button>

        <p className="nx-auth-card-switch">
          {MODES.map((m) => (
            <span key={m} className="nx-auth-card-switch-row" data-current={m === current ? '' : undefined} aria-hidden={m !== current || undefined}>
              {m === 'login' && (
                <>
                  {word('noAccount')}
                  <button type="button" className="nx-auth-card-link" tabIndex={m === current ? undefined : -1} onClick={() => go('register')}>
                    {word('register')}
                  </button>
                </>
              )}
              {m === 'register' && (
                <>
                  {word('hasAccount')}
                  <button type="button" className="nx-auth-card-link" tabIndex={m === current ? undefined : -1} onClick={() => go('login')}>
                    {word('login')}
                  </button>
                </>
              )}
              {m === 'forgot' && (
                <button type="button" className="nx-auth-card-link" tabIndex={m === current ? undefined : -1} onClick={() => go('login')}>
                  <Icon name="arrow-left" />
                  {word('backToLogin')}
                </button>
              )}
            </span>
          ))}
        </p>
      </div>
    </form>
  );
}
