/**
 * Footers for React: the giant wordmark over link columns, the newsletter
 * hero on its dotted ground, and the minimal one-line bar with a status dot.
 * Thin over the core CSS — these blocks carry no DOM behaviour of their own,
 * so there is nothing to attach and nothing to clean up.
 */
import {
  type FormEvent,
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useId,
  useState,
} from 'react';
import { type IconName, Icon } from '../internal/icon';
import { SmartLink } from '../internal/provider';
import { cx } from '../internal/hooks';

export interface FooterLink {
  label: ReactNode;
  href: string;
}

export interface FooterColumn {
  /** The column's micro-heading. */
  title: ReactNode;
  links: FooterLink[];
}

export interface FooterSocialLink {
  /** The network's name — the link's accessible label, or its text when no icon is given. */
  label: string;
  href: string;
  /** One of the built-in icons; without one the label itself is shown. */
  icon?: IconName;
}

/** The columns both wide footers render, as a nav. */
function FooterCols({ columns, label, part }: { columns: FooterColumn[]; label?: string; part: 'wordmark' | 'newsletter' }) {
  return (
    <nav className={`nx-footer-${part}-cols`} aria-label={label}>
      {columns.map((column, i) => (
        <div key={i} className={`nx-footer-${part}-col`}>
          <h4 className={`nx-footer-${part}-head`}>{column.title}</h4>
          <ul className={`nx-footer-${part}-list`} role="list">
            {column.links.map((link, j) => (
              <li key={j}>
                <SmartLink className={`nx-footer-${part}-link`} href={link.href}>
                  {link.label}
                </SmartLink>
              </li>
            ))}
          </ul>
        </div>
      ))}
    </nav>
  );
}

/* ---- Wordmark footer --------------------------------------------------------- */

export interface FooterWordmarkProps extends HTMLAttributes<HTMLElement> {
  /** The brand word, drawn as a giant outline that floods with the accent on hover or focus. Decorative — keep the name in `copyright` too. */
  wordmark: string;
  columns?: FooterColumn[];
  /** The closing line: ©, the name, the offices… */
  copyright?: ReactNode;
  social?: FooterSocialLink[];
  /** Accessible name of the link columns' nav. */
  navLabel?: string;
}

/** Link columns over a giant outline wordmark that floods with the accent when the footer is hovered or focused. */
export const FooterWordmark = forwardRef<HTMLElement, FooterWordmarkProps>(function FooterWordmark(
  { wordmark, columns = [], copyright, social = [], navLabel, className, ...rest },
  ref,
) {
  return (
    <footer ref={ref} className={cx('nx-footer-wordmark', className)} {...rest}>
      {columns.length > 0 && <FooterCols columns={columns} label={navLabel} part="wordmark" />}
      <p className="nx-footer-wordmark-mark" aria-hidden="true">
        {wordmark}
      </p>
      {(copyright != null || social.length > 0) && (
        <div className="nx-footer-wordmark-base">
          {copyright != null && <p className="nx-footer-wordmark-copy">{copyright}</p>}
          {social.length > 0 && (
            <ul className="nx-footer-wordmark-social" role="list">
              {social.map((item) => (
                <li key={item.href}>
                  <SmartLink
                    className="nx-footer-wordmark-social-link"
                    href={item.href}
                    aria-label={item.icon ? item.label : undefined}
                  >
                    {item.icon ? <Icon name={item.icon} /> : item.label}
                  </SmartLink>
                </li>
              ))}
            </ul>
          )}
        </div>
      )}
    </footer>
  );
});

/* ---- Newsletter footer --------------------------------------------------------- */

/** The words the signup form says itself; English unless you bring your own. */
export interface FooterNewsletterLabels {
  /** The field's accessible name. */
  email?: string;
  /** Submitted with nothing typed. */
  empty?: string;
  /** Failed the address check. */
  invalid?: string;
  /** After a successful subscribe. */
  success?: string;
  /** The reset link shown with the success note. */
  again?: string;
}

export interface FooterNewsletterProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  /** The chip above the title. */
  eyebrow?: ReactNode;
  title: ReactNode;
  description?: ReactNode;
  placeholder?: string;
  submitLabel?: ReactNode;
  /** Overrides for the form's own words (validation, success). */
  labels?: FooterNewsletterLabels;
  /** The fine print under the form. */
  note?: ReactNode;
  columns?: FooterColumn[];
  social?: FooterSocialLink[];
  /** Accessible name of the link columns' nav. */
  navLabel?: string;
  /** Called with the address once it passes validation. */
  onSubscribe?: (email: string) => void;
}

const defaultNewsletterLabels: Required<FooterNewsletterLabels> = {
  email: 'Email address',
  empty: 'Your email, first',
  invalid: 'That address doesn’t look right',
  success: 'You’re on the list — the next issue is on its way.',
  again: 'Use another address',
};

const addressLooksRight = (value: string) => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);

/** The signup form as the footer's hero: a live-validated email field whose success note takes its place. */
export const FooterNewsletter = forwardRef<HTMLElement, FooterNewsletterProps>(function FooterNewsletter(
  {
    eyebrow,
    title,
    description,
    placeholder,
    submitLabel = 'Subscribe',
    labels,
    note,
    columns = [],
    social = [],
    navLabel,
    onSubscribe,
    className,
    ...rest
  },
  ref,
) {
  const words = { ...defaultNewsletterLabels, ...labels };
  const [email, setEmail] = useState('');
  const [error, setError] = useState<'empty' | 'invalid' | null>(null);
  const [done, setDone] = useState(false);
  const errorId = useId();

  const submit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (done) return;
    const address = email.trim();
    if (address === '') {
      setError('empty');
      return;
    }
    if (!addressLooksRight(address)) {
      setError('invalid');
      return;
    }
    setError(null);
    setDone(true);
    onSubscribe?.(address);
  };

  const again = () => {
    setDone(false);
    setEmail('');
    setError(null);
  };

  return (
    <footer ref={ref} className={cx('nx-footer-newsletter', className)} {...rest}>
      <div className="nx-footer-newsletter-inner">
        {eyebrow != null && (
          <span className="nx-footer-newsletter-eyebrow">
            <i aria-hidden="true" />
            {eyebrow}
          </span>
        )}
        <h3 className="nx-footer-newsletter-title">{title}</h3>
        {description != null && <p className="nx-footer-newsletter-sub">{description}</p>}

        {done ? (
          <p className="nx-footer-newsletter-done" role="status">
            <Icon name="check-circle" />
            {words.success}
            <button type="button" className="nx-footer-newsletter-again" onClick={again}>
              {words.again}
            </button>
          </p>
        ) : (
          <form className="nx-footer-newsletter-form" noValidate onSubmit={submit}>
            <label className="nx-footer-newsletter-field" data-invalid={error ? '' : undefined}>
              <span className="nx-visually-hidden">{words.email}</span>
              <input
                type="email"
                className="nx-footer-newsletter-input"
                autoComplete="email"
                placeholder={placeholder}
                value={email}
                aria-invalid={error ? true : undefined}
                aria-describedby={error ? errorId : undefined}
                onChange={(event) => {
                  setEmail(event.target.value);
                  setError(null);
                }}
              />
              <span className="nx-footer-newsletter-error" id={errorId} role="alert">
                {error === 'empty' ? words.empty : error === 'invalid' ? words.invalid : ''}
              </span>
            </label>
            <button type="submit" className="nx-footer-newsletter-join">
              {submitLabel}
            </button>
          </form>
        )}
        {note != null && !done && <p className="nx-footer-newsletter-note">{note}</p>}
      </div>

      {(columns.length > 0 || social.length > 0) && (
        <div className="nx-footer-newsletter-bottom">
          {columns.length > 0 && <FooterCols columns={columns} label={navLabel} part="newsletter" />}
          {social.length > 0 && (
            <ul className="nx-footer-newsletter-social" role="list">
              {social.map((item) => (
                <li key={item.href}>
                  <SmartLink
                    className="nx-footer-newsletter-social-link"
                    href={item.href}
                    aria-label={item.icon ? item.label : undefined}
                  >
                    {item.icon ? <Icon name={item.icon} /> : item.label}
                  </SmartLink>
                </li>
              ))}
            </ul>
          )}
        </div>
      )}
    </footer>
  );
});

/* ---- Minimal footer ------------------------------------------------------------ */

export interface FooterMinimalStatus {
  label: ReactNode;
  href?: string;
  tone?: 'success' | 'warning' | 'danger';
}

export interface FooterMinimalProps extends HTMLAttributes<HTMLElement> {
  /** The © line. */
  copyright?: ReactNode;
  /** The pulsing dot and its label — a system-status link, usually. */
  status?: FooterMinimalStatus;
  links?: FooterLink[];
  /** Accessible name of the links' nav. */
  navLabel?: string;
  /** `center` (default) gathers the line; `spread` pushes status and links to the flanks. */
  align?: 'center' | 'spread';
  /** Extra tools at the end of the line (a theme switch, a locale picker…). */
  children?: ReactNode;
}

/** One quiet line on a hairline rule: © and the name, a pulsing status dot, a few links. */
export const FooterMinimal = forwardRef<HTMLElement, FooterMinimalProps>(function FooterMinimal(
  { copyright, status, links = [], navLabel, align = 'center', className, children, ...rest },
  ref,
) {
  const statusBody = (
    <>
      <i className="nx-footer-minimal-dot" aria-hidden="true" />
      {status?.label}
    </>
  );

  return (
    <footer
      ref={ref}
      className={cx('nx-footer-minimal', className)}
      data-align={align === 'spread' ? 'spread' : undefined}
      {...rest}
    >
      {copyright != null && <small className="nx-footer-minimal-copy">{copyright}</small>}
      {status != null &&
        (status.href ? (
          <SmartLink className="nx-footer-minimal-status" href={status.href} data-tone={status.tone === 'success' ? undefined : status.tone}>
            {statusBody}
          </SmartLink>
        ) : (
          <span className="nx-footer-minimal-status" data-tone={status.tone === 'success' ? undefined : status.tone}>
            {statusBody}
          </span>
        ))}
      {links.length > 0 && (
        <nav className="nx-footer-minimal-links" aria-label={navLabel}>
          {links.map((link, i) => (
            <SmartLink key={i} className="nx-footer-minimal-link" href={link.href}>
              {link.label}
            </SmartLink>
          ))}
        </nav>
      )}
      {children}
    </footer>
  );
});
