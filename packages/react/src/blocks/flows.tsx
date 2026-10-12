/**
 * Real flows (React): three product journeys on one shared shell — the
 * four-step onboarding, the cart→address→payment→confirmation checkout, and
 * the email→six-digit-code sign-in. Thin over css/blocks/flows.css and the
 * shared Button / Field / Input / Select / OtpInput components; every piece
 * of state lives inside the component, so the journeys run as demos out of
 * the box and wire up to a backend through the callback props.
 *
 * The steps of a flow share one stage: the step that leaves glides toward the
 * reading start while the arriving one springs in from the end (a fade when
 * motion is reduced), and a visually-hidden status line announces the move.
 */
import {
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { type IconName } from '@nabuxai/ui-core';
import { cx, useControllable, useEvent } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale, useT } from '../internal/provider';
import { Button } from '../components/button';
import { Field, Input, OtpInput, Select, type SelectOption } from '../components/form';

/** The Intl locale: the prop, or the provider's language, mapped. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

type Pos = 'before' | 'current' | 'after';

/** Where a step sits relative to the current one. */
const posOf = (index: number, current: number): Pos => (index < current ? 'before' : index === current ? 'current' : 'after');

/** `'{count} items'` → `'3 items'`; unknown slots stay visible. */
const fill = (text: string, params: Record<string, string | number>) => text.replace(/\{(\w+)\}/g, (slot, name: string) => (name in params ? String(params[name]) : slot));

/** The drawn check every finished flow lands on. */
function FlowDoneMark() {
  return (
    <span className="nx-flow-done-ring" aria-hidden="true">
      <svg viewBox="0 0 48 48" fill="none" stroke="currentColor" strokeWidth="2.6" strokeLinecap="round" strokeLinejoin="round">
        <circle cx="24" cy="24" r="15.9" strokeWidth="2" pathLength={100} />
        <path d="M15.5 24.5l5.5 5.5 11.5-12" pathLength={100} />
      </svg>
    </span>
  );
}

/** The page's locale as an Intl tag, and a number formatter in its digits. */
function useDigits(locale?: string) {
  const language = useLocale();
  const tag = locale ?? INTL[language];
  return useMemo(() => ({ tag, number: new Intl.NumberFormat(tag) }), [tag]);
}

/** Defaults overridden word by word; the defaults' own keys stay required. */
function mergeWords<D extends object, W extends object>(defaults: D, words?: W): D & Partial<W> {
  return { ...defaults, ...words } as D & Partial<W>;
}

/* ---- Onboarding ---------------------------------------------------------------- */

export interface FlowOnboardingRole {
  id: string;
  label: string;
  note?: string;
}

export interface FlowOnboardingTopic {
  id: string;
  label: string;
}

/** The words the onboarding says itself; every one overridable per instance. */
export interface FlowOnboardingWords {
  back?: string;
  next?: string;
  finish?: string;
  /** `'Step {current} of {total}'` */
  stepOf?: string;
  pickHint?: string;
  someHint?: string;
  lastLookHint?: string;
  roleLegend?: string;
  topicsLegend?: string;
  summaryRole?: string;
  summaryTopics?: string;
  summaryReady?: string;
  doneTitle?: string;
  /** `'Tuned for {role} — …'` */
  doneText?: string;
  doneAction?: string;
}

const ONBOARDING_DEFAULTS = {
  next: 'Continue',
  finish: 'Create workspace',
  stepOf: 'Step {current} of {total}',
  pickHint: 'Pick one',
  someHint: 'One or more',
  lastLookHint: 'Last look',
  summaryRole: 'Role',
  summaryTopics: 'Interests',
  summaryReady: 'Ready',
  doneTitle: 'Your workspace is ready',
  doneText: 'Tuned for {role} — the first digest lands Monday 9:00.',
  doneAction: 'Enter the dashboard',
} as const;

export interface FlowOnboardingProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** The pill above the welcome — a product name, a promise. */
  brand?: ReactNode;
  brandIcon?: IconName | null;
  welcomeTitle?: string;
  welcomeText?: string;
  /** The checked lines under the welcome. */
  welcomePoints?: string[];
  rolesTitle?: string;
  rolesText?: string;
  roles?: FlowOnboardingRole[];
  topicsTitle?: string;
  topicsText?: string;
  topics?: FlowOnboardingTopic[];
  summaryTitle?: string;
  summaryText?: string;
  /** How many interests count as an answer (default 1). */
  minTopics?: number;
  step?: number;
  defaultStep?: number;
  onStepChange?: (step: number) => void;
  /** The workspace was created. */
  onFinish?: () => void;
  /** Leave the finished screen; defaults to walking the wizard again. */
  onRestart?: () => void;
  words?: FlowOnboardingWords;
}

/**
 * A four-step onboarding: welcome, one role card out of a radiogroup, as many
 * interest chips as you like, then the honest summary. The segmented bar
 * fills as you move, Continue stays locked until the step is answered, Back
 * works from anywhere, and the finish draws its check.
 */
export const FlowOnboarding = forwardRef<HTMLElement, FlowOnboardingProps>(function FlowOnboarding(
  {
    brand,
    brandIcon = 'sparkles',
    welcomeTitle = 'Welcome to your team’s new home',
    welcomeText = 'Four short steps and the workspace fits how you work — no credit card, no admin tickets.',
    welcomePoints = [],
    rolesTitle = 'What do you do here?',
    rolesText = 'We ask once — to choose which dashboard greets you first.',
    roles = [],
    topicsTitle = 'Tune your feed',
    topicsText = 'Pick what you want landing on your desk every Monday.',
    topics = [],
    summaryTitle = 'Last look before we build it',
    summaryText = 'Everything below is editable later — this is just the honest summary.',
    minTopics = 1,
    step,
    defaultStep = 0,
    onStepChange,
    onFinish,
    onRestart,
    words,
    className,
    ...rest
  },
  ref,
) {
  const t = useT();
  const { number } = useDigits();
  const w = mergeWords(ONBOARDING_DEFAULTS, words);
  const group = `nx-onboarding${useId().replace(/:/g, '')}`;
  const total = 4;
  const [value, setCurrent] = useControllable(step, defaultStep, onStepChange);
  const current = Math.max(0, Math.min(value, total - 1));
  const [role, setRole] = useState('');
  const [picks, setPicks] = useState<string[]>([]);
  const [done, setDone] = useState(false);

  const roleLabel = roles.find((it) => it.id === role)?.label;
  const pickedLabels = topics.filter((it) => picks.includes(it.id)).map((it) => it.label);
  const answered = current === 1 ? role !== '' : current === 2 ? picks.length >= minTopics : true;

  const titles = [welcomeTitle, rolesTitle, topicsTitle, summaryTitle];
  const stepOf = fill(w.stepOf, { current: number.format(current + 1), total: number.format(total) });

  const next = () => {
    if (!answered) return;
    if (current < total - 1) setCurrent(current + 1);
    else {
      setDone(true);
      onFinish?.();
    }
  };

  const restart = () => {
    if (onRestart) {
      onRestart();
      return;
    }
    setDone(false);
    setRole('');
    setPicks([]);
    setCurrent(0);
  };

  if (done) {
    return (
      <section ref={ref} className={cx('nx-flow', className)} {...rest}>
        <div className="nx-flow-done">
          <FlowDoneMark />
          <h3 className="nx-flow-done-title">{w.doneTitle}</h3>
          <p className="nx-flow-done-text">{fill(w.doneText, { role: roleLabel ?? '—' })}</p>
          <Button variant="primary" onClick={restart}>
            {w.doneAction}
          </Button>
        </div>
      </section>
    );
  }

  return (
    <section ref={ref} className={cx('nx-flow nx-onboarding', className)} {...rest}>
      <header className="nx-flow-top">
        <div className="nx-flow-bar" role="progressbar" aria-valuemin={1} aria-valuemax={total} aria-valuenow={current + 1} aria-valuetext={stepOf} aria-label={stepOf}>
          {Array.from({ length: total }, (_, i) => (
            <i key={i} data-on={i <= current ? '' : undefined} />
          ))}
        </div>
        <p className="nx-flow-meta">
          <span>{stepOf}</span>
          <em>{[w.pickHint, w.someHint, w.lastLookHint, w.lastLookHint][current]}</em>
        </p>
      </header>

      <div className="nx-flow-stage">
        <section className="nx-flow-step" data-pos={posOf(0, current)} aria-hidden={current === 0 ? undefined : true}>
          {brand != null && brand !== false && (
            <span className="nx-flow-brand">
              {brandIcon && <Icon name={brandIcon} />}
              {brand}
            </span>
          )}
          <h3 className="nx-flow-title">{welcomeTitle}</h3>
          <p className="nx-flow-text">{welcomeText}</p>
          {welcomePoints.length > 0 && (
            <ul className="nx-onboarding-points">
              {welcomePoints.map((point) => (
                <li key={point}>
                  <Icon name="check" />
                  {point}
                </li>
              ))}
            </ul>
          )}
        </section>

        <section className="nx-flow-step" data-pos={posOf(1, current)} aria-hidden={current === 1 ? undefined : true}>
          <h3 className="nx-flow-title">{rolesTitle}</h3>
          <p className="nx-flow-text">{rolesText}</p>
          <div className="nx-onboarding-roles" role="radiogroup" aria-label={w.roleLegend ?? rolesTitle}>
            {roles.map((it) => (
              <label key={it.id} className="nx-onboarding-role" data-on={role === it.id ? '' : undefined}>
                <input type="radio" name={group} value={it.id} checked={role === it.id} onChange={() => setRole(it.id)} />
                <b>{it.label}</b>
                {it.note && <small>{it.note}</small>}
                <span className="nx-onboarding-role-check" aria-hidden="true">
                  <Icon name="check" />
                </span>
              </label>
            ))}
          </div>
        </section>

        <section className="nx-flow-step" data-pos={posOf(2, current)} aria-hidden={current === 2 ? undefined : true}>
          <h3 className="nx-flow-title">{topicsTitle}</h3>
          <p className="nx-flow-text">{topicsText}</p>
          <div className="nx-onboarding-chips" role="group" aria-label={w.topicsLegend ?? topicsTitle}>
            {topics.map((it) => (
              <button
                key={it.id}
                type="button"
                className="nx-onboarding-chip"
                aria-pressed={picks.includes(it.id)}
                onClick={() => setPicks((list) => (list.includes(it.id) ? list.filter((p) => p !== it.id) : [...list, it.id]))}
              >
                {it.label}
              </button>
            ))}
          </div>
        </section>

        <section className="nx-flow-step" data-pos={posOf(3, current)} aria-hidden={current === 3 ? undefined : true}>
          <h3 className="nx-flow-title">{summaryTitle}</h3>
          <p className="nx-flow-text">{summaryText}</p>
          <dl className="nx-onboarding-summary">
            <div>
              <dt>{w.summaryRole}</dt>
              <dd>{roleLabel ?? '—'}</dd>
            </div>
            <div>
              <dt>{w.summaryTopics}</dt>
              <dd>{pickedLabels.length > 0 ? pickedLabels.join(' · ') : '—'}</dd>
            </div>
            <div>
              <dt>{w.summaryReady}</dt>
              <dd>{roleLabel ? pickedLabels.length >= minTopics ? '✓' : '…' : '…'}</dd>
            </div>
          </dl>
        </section>
      </div>

      <p className="nx-visually-hidden" role="status" aria-live="polite">
        {`${stepOf}: ${titles[current] ?? ''}`}
      </p>

      <footer className="nx-flow-nav">
        <Button variant="ghost" icon="arrow-left" disabled={current === 0} onClick={() => setCurrent(Math.max(0, current - 1))}>
          {w.back ?? t('back')}
        </Button>
        <Button variant="primary" iconEnd="arrow-right" disabled={!answered} onClick={next}>
          {current < total - 1 ? w.next : w.finish}
        </Button>
      </footer>
    </section>
  );
});

/* ---- Checkout ------------------------------------------------------------------ */

export interface FlowCheckoutItem {
  id: string;
  name: string;
  meta?: string;
  /** Unit price, in the whole units you format (89 for €89). */
  price: number;
  qty: number;
}

/** The words the checkout says itself; every one overridable per instance. */
export interface FlowCheckoutWords {
  cartLabel?: string;
  addressLabel?: string;
  paymentLabel?: string;
  doneLabel?: string;
  cartTitle?: string;
  emptyText?: string;
  restoreLabel?: string;
  itemCount?: string;
  freeShippingAt?: string;
  freeShipping?: string;
  toAddress?: string;
  addressTitle?: string;
  addressText?: string;
  nameLabel?: string;
  streetLabel?: string;
  cityLabel?: string;
  postcodeLabel?: string;
  countryLabel?: string;
  errName?: string;
  errStreet?: string;
  errCity?: string;
  errPostcode?: string;
  backToCart?: string;
  toPayment?: string;
  paymentTitle?: string;
  paymentText?: string;
  cardNumberLabel?: string;
  expiryLabel?: string;
  cvcLabel?: string;
  errCardNumber?: string;
  errExpiry?: string;
  errCvc?: string;
  declineText?: string;
  backToAddress?: string;
  /** `'Pay {total}'` */
  pay?: string;
  processing?: string;
  tryAgain?: string;
  doneTitle?: string;
  doneText?: string;
  /** `'Card ending {digits}'` */
  cardEnding?: string;
  backToStore?: string;
  asideTitle?: string;
  shippingLabel?: string;
  freeLabel?: string;
  /** `'VAT ({rate}%)'` */
  vatLabel?: string;
  totalLabel?: string;
  asideHint?: string;
  decreaseLabel?: string;
  increaseLabel?: string;
  removeLabel?: string;
}

const CHECKOUT_DEFAULTS = {
  cartLabel: 'Cart',
  addressLabel: 'Address',
  paymentLabel: 'Payment',
  doneLabel: 'Done',
  cartTitle: 'Your cart',
  emptyText: 'The cart is empty — everything you removed is one click away.',
  restoreLabel: 'Restore the demo cart',
  itemCount: '{count} items',
  freeShippingAt: 'Add {amount} for free shipping',
  freeShipping: 'Shipping is on us',
  toAddress: 'Continue to address',
  addressTitle: 'Shipping address',
  addressText: 'Wherever the desk is, this form checks itself before moving on.',
  nameLabel: 'Full name',
  streetLabel: 'Street and number',
  cityLabel: 'City',
  postcodeLabel: 'Postcode',
  errName: 'Full name is required',
  errStreet: 'Street and number, please',
  errCity: 'Which city ships it to?',
  errPostcode: 'Postcode looks off (3–10 characters)',
  backToCart: 'Back to cart',
  toPayment: 'Continue to payment',
  paymentTitle: 'Payment',
  paymentText: 'The number groups in fours and the expiry slashes itself while the preview follows.',
  cardNumberLabel: 'Card number',
  expiryLabel: 'Expiry',
  cvcLabel: 'CVC',
  errCardNumber: '16 digits, grouped in fours',
  errExpiry: 'MM/YY',
  errCvc: '3–4 digits on the back',
  declineText: 'Card declined. The bank said no — nothing was charged. Edit the number and try again.',
  backToAddress: 'Back to address',
  pay: 'Pay {total}',
  processing: 'Processing…',
  tryAgain: 'Try again',
  doneTitle: 'Order confirmed',
  doneText: 'Receipt is on its way to your inbox.',
  cardEnding: 'Card ending {digits}',
  backToStore: 'Back to the store',
  asideTitle: 'Order summary',
  shippingLabel: 'Shipping',
  freeLabel: 'Free',
  vatLabel: 'VAT ({rate}%)',
  totalLabel: 'Total',
  asideHint: 'Shipping is free from {amount} — the steppers recompute everything instantly.',
  decreaseLabel: 'Decrease quantity',
  increaseLabel: 'Increase quantity',
  removeLabel: 'Remove item',
} as const;

export interface FlowCheckoutProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** The cart the flow starts from (kept as the “restore” seed too). */
  items: FlowCheckoutItem[];
  onItemsChange?: (items: FlowCheckoutItem[]) => void;
  countries?: SelectOption[];
  /** ISO currency code for the money formatting (default EUR). */
  currency?: string;
  /** How much of the subtotal is VAT (0.19 = 19%). */
  vatRate?: number;
  /** Free shipping from this subtotal on (default 150). */
  freeShippingFrom?: number;
  /** The shipping fee below the free threshold (default 12). */
  shippingFlat?: number;
  /** Called with the card digits; true declines the payment honestly. */
  shouldDecline?: (digits: string) => boolean;
  /** The mock gateway's thinking time in ms (default 900). */
  processingMs?: number;
  /** Days from now the delivery estimate lands on (default 5). */
  deliveryDays?: number;
  orderPrefix?: string;
  /** A paid order; receives the order number. */
  onPaid?: (order: string) => void;
  step?: number;
  defaultStep?: number;
  onStepChange?: (step: number) => void;
  formatMoney?: (value: number) => string;
  words?: FlowCheckoutWords;
  locale?: string;
}

const POSTCODE = /^[A-Za-z0-9][A-Za-z0-9 -]{1,8}[A-Za-z0-9]$/;
const EMAIL = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

/** Group the digits in fours, as the card embosses them. */
const maskNumber = (raw: string) => raw.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();

/** MM/YY: digits first, the slash writes itself after the second. */
const maskExpiry = (raw: string) => {
  const d = raw.replace(/\D/g, '').slice(0, 4);
  return d.length > 2 ? `${d.slice(0, 2)}/${d.slice(2)}` : d;
};

/** A full-width field of the checkout grid. */
const wide = 'nx-checkout-span';

/**
 * A checkout that behaves like one: a cart with live steppers and removal, an
 * address form that validates on Continue and parks each error under its
 * field, a card form that masks while typing (with the preview following),
 * the honest declined state, a sticky summary that recounts shipping and VAT,
 * and a confirmation with a generated order number and delivery date.
 */
export const FlowCheckout = forwardRef<HTMLElement, FlowCheckoutProps>(function FlowCheckout(
  {
    items,
    onItemsChange,
    countries = [
      { value: 'DE', label: 'Germany' },
      { value: 'IE', label: 'Ireland' },
      { value: 'SG', label: 'Singapore' },
      { value: 'NL', label: 'Netherlands' },
    ],
    currency = 'EUR',
    vatRate = 0.19,
    freeShippingFrom = 150,
    shippingFlat = 12,
    shouldDecline,
    processingMs = 900,
    deliveryDays = 5,
    orderPrefix = 'NBX-',
    onPaid,
    step,
    defaultStep = 0,
    onStepChange,
    formatMoney,
    words,
    locale,
    className,
    ...rest
  },
  ref,
) {
  const { tag, number } = useDigits(locale);
  const money = useMemo(
    () => formatMoney ?? ((value: number) => new Intl.NumberFormat(tag, { style: 'currency', currency }).format(value)),
    [formatMoney, tag, currency],
  );
  const dateFmt = useMemo(() => new Intl.DateTimeFormat(tag, { day: 'numeric', month: 'long' }), [tag]);
  const w = mergeWords(CHECKOUT_DEFAULTS, words);
  const total = 4;
  const [value, setCurrent] = useControllable(step, defaultStep, onStepChange);
  const current = Math.max(0, Math.min(value, total - 1));
  const [cart, setCart] = useState<FlowCheckoutItem[]>(items);
  const [form, setForm] = useState({ name: '', street: '', city: '', postcode: '', country: countries[0]?.value ?? '' });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [card, setCard] = useState({ number: '', exp: '', cvc: '' });
  const [cardErrors, setCardErrors] = useState<Record<string, string>>({});
  const [declined, setDeclined] = useState(false);
  const [processing, setProcessing] = useState(false);
  const [order, setOrder] = useState('');
  const [delivery, setDelivery] = useState('');

  const subtotal = cart.reduce((sum, it) => sum + it.price * it.qty, 0);
  const shipping = subtotal >= freeShippingFrom || cart.length === 0 ? 0 : shippingFlat;
  const vat = Math.round(subtotal * vatRate);
  const sum = subtotal + shipping + vat;
  const count = cart.reduce((n, it) => n + it.qty, 0);

  const write = (next: FlowCheckoutItem[]) => {
    setCart(next);
    onItemsChange?.(next);
  };
  const bump = (id: string, delta: number) => write(cart.map((it) => (it.id === id ? { ...it, qty: Math.max(1, it.qty + delta) } : it)));
  const drop = (id: string) => write(cart.filter((it) => it.id !== id));

  const validateAddress = () => {
    const found: Record<string, string> = {};
    if (form.name.trim().length < 2) found.name = w.errName;
    if (form.street.trim().length < 3) found.street = w.errStreet;
    if (form.city.trim().length < 2) found.city = w.errCity;
    if (!POSTCODE.test(form.postcode.trim())) found.postcode = w.errPostcode;
    setErrors(found);
    return Object.keys(found).length === 0;
  };

  const pay = useEvent(() => {
    if (processing) return;
    const digits = card.number.replace(/\s/g, '');
    const found: Record<string, string> = {};
    if (digits.length !== 16) found.number = w.errCardNumber;
    if (!/^\d{2}\/\d{2}$/.test(card.exp)) found.exp = w.errExpiry;
    if (card.cvc.length < 3) found.cvc = w.errCvc;
    setCardErrors(found);
    if (Object.keys(found).length > 0) return;

    setProcessing(true);
    window.setTimeout(() => {
      setProcessing(false);
      if (shouldDecline?.(digits)) {
        setDeclined(true);
        return;
      }
      setDeclined(false);
      const nr = orderPrefix + Math.floor(10000 + Math.random() * 90000);
      setOrder(nr);
      setDelivery(dateFmt.format(new Date(Date.now() + deliveryDays * 86400000)));
      setCurrent(3);
      onPaid?.(nr);
    }, processingMs);
  });

  const restart = () => {
    write(items);
    setForm({ name: '', street: '', city: '', postcode: '', country: countries[0]?.value ?? '' });
    setCard({ number: '', exp: '', cvc: '' });
    setErrors({});
    setCardErrors({});
    setDeclined(false);
    setOrder('');
    setCurrent(0);
  };

  const crumbs = [w.cartLabel, w.addressLabel, w.paymentLabel, w.doneLabel];
  const stepOf = `${number.format(current + 1)} / ${number.format(total)}`;

  return (
    <section ref={ref} className={cx('nx-flow nx-checkout', className)} data-step={current} {...rest}>
      <div className="nx-checkout-grid">
        <div className="nx-checkout-stage">
          <ol className="nx-checkout-crumb" aria-label={stepOf}>
            {crumbs.map((label, i) => (
              <li key={label} data-now={i === current ? '' : undefined}>
                {label}
              </li>
            ))}
          </ol>

          <div className="nx-flow-stage">
            {/* — Cart — */}
            <section className="nx-flow-step" data-pos={posOf(0, current)} aria-hidden={current === 0 ? undefined : true}>
              <h3 className="nx-flow-title">{w.cartTitle}</h3>
              {cart.length > 0 ? (
                <div className="nx-checkout-rows">
                  {cart.map((it) => (
                    <div className="nx-checkout-row" key={it.id}>
                      <div>
                        <b>{it.name}</b>
                        {it.meta && <small>{it.meta}</small>}
                      </div>
                      <span className="nx-checkout-qty">
                        <button type="button" className="nx-checkout-qty-btn" aria-label={w.decreaseLabel} onClick={() => bump(it.id, -1)}>
                          <Icon name="minus" />
                        </button>
                        <b>{number.format(it.qty)}</b>
                        <button type="button" className="nx-checkout-qty-btn" aria-label={w.increaseLabel} onClick={() => bump(it.id, 1)}>
                          <Icon name="plus" />
                        </button>
                      </span>
                      <span className="nx-checkout-price">{money(it.price * it.qty)}</span>
                      <button type="button" className="nx-checkout-remove" aria-label={w.removeLabel} onClick={() => drop(it.id)}>
                        <Icon name="trash" />
                      </button>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="nx-checkout-empty">
                  <p className="nx-flow-text">{w.emptyText}</p>
                  <Button variant="secondary" onClick={() => write(items)}>
                    {w.restoreLabel}
                  </Button>
                </div>
              )}
              <footer className="nx-flow-nav">
                {cart.length > 0 && (
                  <p className="nx-flow-note">
                    {subtotal >= freeShippingFrom ? w.freeShipping : fill(w.freeShippingAt, { amount: money(freeShippingFrom - subtotal) })} ·{' '}
                    {fill(w.itemCount, { count: number.format(count) })}
                  </p>
                )}
                <Button variant="primary" iconEnd="arrow-right" disabled={cart.length === 0} onClick={() => setCurrent(1)}>
                  {w.toAddress}
                </Button>
              </footer>
            </section>

            {/* — Address — */}
            <section className="nx-flow-step" data-pos={posOf(1, current)} aria-hidden={current === 1 ? undefined : true}>
              <h3 className="nx-flow-title">{w.addressTitle}</h3>
              <p className="nx-flow-text">{w.addressText}</p>
              <div className="nx-checkout-fields">
                <Field className={wide} label={w.nameLabel} error={errors.name}>
                  <Input value={form.name} autoComplete="name" invalid={!!errors.name} onChange={(e) => setForm({ ...form, name: e.target.value })} />
                </Field>
                <Field className={wide} label={w.streetLabel} error={errors.street}>
                  <Input value={form.street} autoComplete="street-address" invalid={!!errors.street} onChange={(e) => setForm({ ...form, street: e.target.value })} />
                </Field>
                <Field label={w.cityLabel} error={errors.city}>
                  <Input value={form.city} autoComplete="address-level2" invalid={!!errors.city} onChange={(e) => setForm({ ...form, city: e.target.value })} />
                </Field>
                <Field label={w.postcodeLabel} error={errors.postcode}>
                  <Input value={form.postcode} dir="ltr" autoComplete="postal-code" invalid={!!errors.postcode} onChange={(e) => setForm({ ...form, postcode: e.target.value })} />
                </Field>
                <Field className={wide} label={w.countryLabel}>
                  <Select value={form.country} options={countries} onChange={(e) => setForm({ ...form, country: e.target.value })} />
                </Field>
              </div>
              <footer className="nx-flow-nav">
                <Button variant="ghost" icon="arrow-left" onClick={() => setCurrent(0)}>
                  {w.backToCart}
                </Button>
                <Button variant="primary" iconEnd="arrow-right" onClick={() => validateAddress() && setCurrent(2)}>
                  {w.toPayment}
                </Button>
              </footer>
            </section>

            {/* — Payment — */}
            <section className="nx-flow-step" data-pos={posOf(2, current)} aria-hidden={current === 2 ? undefined : true}>
              <h3 className="nx-flow-title">{w.paymentTitle}</h3>
              <p className="nx-flow-text">{w.paymentText}</p>

              <div className="nx-checkout-card" dir="ltr" aria-hidden="true">
                <span className="nx-checkout-card-chip" />
                <span className="nx-checkout-card-num">{card.number || '•••• •••• •••• ••••'}</span>
                <div className="nx-checkout-card-foot">
                  <span>{form.name.toUpperCase() || 'CARDHOLDER'}</span>
                  <span>{card.exp || 'MM/YY'}</span>
                </div>
              </div>

              <div className="nx-checkout-fields">
                <Field className={wide} label={w.cardNumberLabel} error={cardErrors.number}>
                  <Input
                    value={card.number}
                    dir="ltr"
                    inputMode="numeric"
                    placeholder="4000 0000 0000 0002"
                    invalid={!!cardErrors.number}
                    style={{ fontFamily: 'var(--nx-font-mono)', letterSpacing: '0.08em' }}
                    onChange={(e) => {
                      setCard({ ...card, number: maskNumber(e.target.value) });
                      setDeclined(false);
                    }}
                  />
                </Field>
                <Field label={w.expiryLabel} error={cardErrors.exp}>
                  <Input value={card.exp} dir="ltr" inputMode="numeric" placeholder="MM/YY" invalid={!!cardErrors.exp} onChange={(e) => setCard({ ...card, exp: maskExpiry(e.target.value) })} />
                </Field>
                <Field label={w.cvcLabel} error={cardErrors.cvc}>
                  <Input value={card.cvc} dir="ltr" inputMode="numeric" placeholder="123" invalid={!!cardErrors.cvc} onChange={(e) => setCard({ ...card, cvc: e.target.value.replace(/\D/g, '').slice(0, 4) })} />
                </Field>
              </div>

              {declined && (
                <p className="nx-checkout-decline" role="alert">
                  <Icon name="alert-triangle" />
                  <span>{w.declineText}</span>
                </p>
              )}

              <footer className="nx-flow-nav">
                <Button variant="ghost" icon="arrow-left" onClick={() => setCurrent(1)}>
                  {w.backToAddress}
                </Button>
                <Button variant="primary" loading={processing} disabled={processing} onClick={pay}>
                  {processing ? w.processing : declined ? w.tryAgain : fill(w.pay, { total: money(sum) })}
                </Button>
              </footer>
            </section>

            {/* — Confirmation — */}
            <section className="nx-flow-step" data-pos={posOf(3, current)} aria-hidden={current === 3 ? undefined : true}>
              <div className="nx-flow-done">
                <FlowDoneMark />
                <h3 className="nx-flow-done-title">{w.doneTitle}</h3>
                <p className="nx-flow-done-text">{w.doneText}</p>
                <span className="nx-checkout-order">
                  <Icon name="file" />
                  <span dir="ltr">{order}</span>
                </span>
                <p className="nx-flow-done-text">
                  {[form.city, delivery].filter(Boolean).join(' · ')}
                  {card.number ? ` · ${fill(w.cardEnding, { digits: card.number.replace(/\s/g, '').slice(-4) })}` : ''}
                </p>
                <Button variant="primary" onClick={restart}>
                  {w.backToStore}
                </Button>
              </div>
            </section>
          </div>
        </div>

        {current < 3 && (
          <aside className="nx-checkout-aside" aria-label={w.asideTitle}>
            <h3>{w.asideTitle}</h3>
            {cart.map((it) => (
              <div className="nx-checkout-aside-row" key={it.id}>
                <span>
                  {number.format(it.qty)} × {it.name}
                </span>
                <span>{money(it.price * it.qty)}</span>
              </div>
            ))}
            {cart.length === 0 && (
              <div className="nx-checkout-aside-row">
                <span>—</span>
                <span>{money(0)}</span>
              </div>
            )}
            <div className="nx-checkout-aside-row">
              <span>{w.shippingLabel}</span>
              <span data-tone={shipping === 0 ? 'free' : undefined}>{shipping === 0 ? w.freeLabel : money(shipping)}</span>
            </div>
            <div className="nx-checkout-aside-row">
              <span>{fill(w.vatLabel, { rate: number.format(Math.round(vatRate * 100)) })}</span>
              <span>{money(vat)}</span>
            </div>
            <div className="nx-checkout-aside-row" data-tone="total">
              <span>{w.totalLabel}</span>
              <span>{money(sum)}</span>
            </div>
            <p className="nx-flow-note">{fill(w.asideHint, { amount: money(freeShippingFrom) })}</p>
          </aside>
        )}
      </div>

      <p className="nx-visually-hidden" role="status" aria-live="polite">
        {`${stepOf}: ${crumbs[current] ?? ''}`}
      </p>
    </section>
  );
});

/* ---- Auth code ------------------------------------------------------------------ */

/** The words the auth code says itself; every one overridable per instance. */
export interface FlowAuthCodeWords {
  continueLabel?: string;
  emailLabel?: string;
  errEmail?: string;
  codeTitle?: string;
  /** `'Sent to {email} — …'` */
  codeLead?: string;
  /** The chip before the demo code, when `hintCode` is set. */
  hint?: string;
  verifyLabel?: string;
  wrongCode?: string;
  /** `'Resend in {s}s'` */
  resendIn?: string;
  resendLabel?: string;
  resentLabel?: string;
  backLabel?: string;
  doneTitle?: string;
  doneText?: string;
  signOutLabel?: string;
}

const AUTH_DEFAULTS = {
  continueLabel: 'Continue with code',
  errEmail: 'This email does not parse',
  codeTitle: 'Enter your code',
  codeLead: 'Sent to {email} — type it, or paste all six at once.',
  hint: 'demo code:',
  verifyLabel: 'Verify',
  wrongCode: 'That code is not right — codes live for 10 minutes',
  resendIn: 'Resend in {s}s',
  resendLabel: 'Resend code',
  resentLabel: 'Sent again ✓',
  backLabel: 'Back to sign in',
  doneTitle: 'Welcome back',
  doneText: 'Device trusted — next time we may skip the code.',
  signOutLabel: 'Sign out',
} as const;

export interface FlowAuthCodeProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** The account the code goes to; the flow starts on the email step when empty. */
  defaultEmail?: string;
  /** How many digits (default 6). */
  length?: number;
  /** The resend countdown in seconds (default 30). */
  resendAfter?: number;
  /** Show the demo-code chip for this code. */
  hintCode?: string;
  /** Judge the code: resolve true to sign in, false to shake. Without it any complete code passes. */
  verify?: (code: string) => boolean | Promise<boolean>;
  /** A resend was asked for. */
  onResend?: () => void | Promise<void>;
  onSignIn?: (email: string) => void;
  /** Leave the welcome screen; defaults to walking back to the sign-in step. */
  onSignOut?: () => void;
  words?: FlowAuthCodeWords;
  locale?: string;
}

type AuthStage = 'email' | 'code' | 'done';
const STAGE_INDEX: Record<AuthStage, number> = { email: 0, code: 1, done: 2 };

/**
 * A two-step sign-in: the email, then the six-digit code. The code's boxes
 * move the focus along as you type and a pasted code fills them all at once
 * (the shared OtpInput); a wrong code shakes, says why and clears itself, and
 * the resend link sleeps for a real countdown that tears itself down with the
 * component.
 */
export const FlowAuthCode = forwardRef<HTMLElement, FlowAuthCodeProps>(function FlowAuthCode(
  { defaultEmail = '', length = 6, resendAfter = 30, hintCode, verify, onResend, onSignIn, onSignOut, words, locale, className, ...rest },
  ref,
) {
  const t = useT();
  const { number } = useDigits(locale);
  const w = mergeWords(AUTH_DEFAULTS, words);
  const [stage, setStage] = useState<AuthStage>(defaultEmail === '' ? 'email' : 'code');
  const [email, setEmail] = useState(defaultEmail);
  const [emailError, setEmailError] = useState('');
  const [code, setCode] = useState('');
  const [bad, setBad] = useState(false);
  const [error, setError] = useState('');
  const [checking, setChecking] = useState(false);
  const [remaining, setRemaining] = useState(resendAfter);
  const [resent, setResent] = useState(false);
  const codeInput = useRef<HTMLInputElement>(null);
  const here = STAGE_INDEX[stage];

  // The countdown: one tick at a time, torn down with the component.
  useEffect(() => {
    if (stage !== 'code' || remaining === 0) return;
    const id = window.setTimeout(() => setRemaining(remaining - 1), 1000);
    return () => window.clearTimeout(id);
  }, [stage, remaining]);

  // The code step takes the focus the moment it arrives.
  useEffect(() => {
    if (stage === 'code') codeInput.current?.focus({ preventScroll: true });
  }, [stage]);

  const submitEmail = () => {
    if (!EMAIL.test(email)) {
      setEmailError(w.errEmail);
      return;
    }
    setEmailError('');
    setRemaining(resendAfter);
    setStage('code');
  };

  const check = useEvent(async () => {
    if (code.length < length || checking) return;
    setChecking(true);
    const ok = verify ? await verify(code) : true;
    setChecking(false);
    if (ok) {
      setError('');
      setBad(false);
      setStage('done');
      onSignIn?.(email);
      return;
    }
    setError(w.wrongCode);
    setBad(true);
    window.setTimeout(() => {
      setCode('');
      setBad(false);
      codeInput.current?.focus({ preventScroll: true });
    }, 320);
  });

  const resend = useEvent(async () => {
    if (remaining > 0) return;
    setRemaining(resendAfter);
    setResent(true);
    setError('');
    setBad(false);
    await onResend?.();
    codeInput.current?.focus({ preventScroll: true });
  });

  if (stage === 'done') {
    return (
      <section ref={ref} className={cx('nx-flow nx-auth-code', className)} {...rest}>
        <div className="nx-flow-done">
          <FlowDoneMark />
          <h3 className="nx-flow-done-title">{w.doneTitle}</h3>
          <p className="nx-flow-done-text">{w.doneText}</p>
          <Button
            variant="primary"
            onClick={() => {
              if (onSignOut) {
                onSignOut();
                return;
              }
              setCode('');
              setRemaining(resendAfter);
              setResent(false);
              setStage('code');
            }}
          >
            {w.signOutLabel}
          </Button>
        </div>
      </section>
    );
  }

  return (
    <section ref={ref} className={cx('nx-flow nx-auth-code', className)} {...rest}>
      <div className="nx-flow-stage">
        <form
          className="nx-flow-step"
          data-pos={posOf(STAGE_INDEX.email, here)}
          aria-hidden={stage === 'email' ? undefined : true}
          onSubmit={(event) => {
            event.preventDefault();
            submitEmail();
          }}
        >
          <span className="nx-flow-brand">
            <Icon name="lock" />
            {t('authLogin')}
          </span>
          <h3 className="nx-flow-title">{t('authLoginTitle')}</h3>
          <p className="nx-flow-text">{t('authLoginSubtitle')}</p>
          <Field label={w.emailLabel ?? t('authEmail')} error={emailError || undefined}>
            <Input type="email" dir="ltr" value={email} autoComplete="email" invalid={!!emailError} onChange={(e) => setEmail(e.target.value)} />
          </Field>
          <div className="nx-auth-code-actions">
            <Button type="submit" variant="primary" iconEnd="arrow-right">
              {w.continueLabel}
            </Button>
          </div>
        </form>

        <section className="nx-flow-step" data-pos={posOf(STAGE_INDEX.code, here)} aria-hidden={stage === 'code' ? undefined : true}>
          <span className="nx-flow-brand">
            <Icon name="mail" />
            {t('code')}
          </span>
          <h3 className="nx-flow-title">{w.codeTitle}</h3>
          <p className="nx-flow-text">{fill(w.codeLead, { email })}</p>

          {hintCode && (
            <span className="nx-auth-code-hint">
              <Icon name="info" />
              {w.hint} <b dir="ltr">{hintCode}</b>
            </span>
          )}

          <div className="nx-auth-code-boxes">
            <OtpInput ref={codeInput} length={length} value={code} invalid={bad} onValueChange={(next) => setCode(next)} />
          </div>

          {error && (
            <p className="nx-auth-code-error" role="alert">
              <Icon name="alert-circle" />
              {error}
            </p>
          )}

          <div className="nx-auth-code-actions">
            <Button variant="primary" loading={checking} disabled={code.length < length || checking} onClick={check}>
              {w.verifyLabel}
            </Button>
            <span className="nx-auth-code-resend">
              {remaining > 0 ? (
                fill(w.resendIn, { s: number.format(remaining) })
              ) : (
                <button type="button" className="nx-auth-code-link" onClick={resend}>
                  {resent ? w.resentLabel : w.resendLabel}
                </button>
              )}
            </span>
            <button
              type="button"
              className="nx-auth-code-link"
              onClick={() => {
                setCode('');
                setError('');
                setBad(false);
                setStage('email');
              }}
            >
              {w.backLabel}
            </button>
          </div>
        </section>
      </div>

      <p className="nx-visually-hidden" role="status" aria-live="polite">
        {stage === 'email' ? t('authLoginTitle') : w.codeTitle}
      </p>
    </section>
  );
});
