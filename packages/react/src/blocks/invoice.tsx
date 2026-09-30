/**
 * The invoice block (React): a printable document — brand head, parties,
 * line items that reveal one row at a time, totals whose digits roll
 * (css/blocks/invoice.css), and the payment state on a StatusBadge.
 * Amounts are pure props (quantity × unit price unless you give `amount`);
 * there is nothing to fetch.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  useId,
  useMemo,
  useRef,
} from 'react';
import { cx } from '../internal/hooks';
import { useLocale } from '../internal/provider';
import { NumberTicker, useReveal } from '../components/text';
import { StatusBadge, type JobStatus } from './data';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

export type InvoiceStatus = 'paid' | 'unpaid' | 'overdue' | 'draft';

/** The badge speaks in job statuses; an invoice maps onto them. */
const BADGE_OF: Record<InvoiceStatus, JobStatus> = { paid: 'success', unpaid: 'queued', overdue: 'failed', draft: 'canceled' };

/** The words the invoice says itself; override any of them with `labels`. */
type InvoiceWord =
  | 'title' | 'number' | 'issueDate' | 'dueDate' | 'from' | 'to'
  | 'item' | 'quantity' | 'unitPrice' | 'amount' | 'subtotal' | 'total'
  | 'paid' | 'unpaid' | 'overdue' | 'draft' | 'caption';

const WORDS: Record<InvoiceWord, Record<'en' | 'fa' | 'ar', string>> = {
  title: { en: 'Invoice', fa: 'فاکتور', ar: 'فاتورة' },
  number: { en: 'Invoice no.', fa: 'شمارهٔ فاکتور', ar: 'رقم الفاتورة' },
  issueDate: { en: 'Issue date', fa: 'تاریخ صدور', ar: 'تاريخ الإصدار' },
  dueDate: { en: 'Due date', fa: 'موعد پرداخت', ar: 'تاريخ الاستحقاق' },
  from: { en: 'From', fa: 'صادرکننده', ar: 'من' },
  to: { en: 'Billed to', fa: 'صورتحساب برای', ar: 'إلى' },
  item: { en: 'Item', fa: 'شرح', ar: 'البند' },
  quantity: { en: 'Qty', fa: 'تعداد', ar: 'الكمية' },
  unitPrice: { en: 'Unit price', fa: 'قیمت واحد', ar: 'سعر الوحدة' },
  amount: { en: 'Amount', fa: 'مبلغ', ar: 'المبلغ' },
  subtotal: { en: 'Subtotal', fa: 'جمع جزء', ar: 'المجموع الفرعي' },
  total: { en: 'Total due', fa: 'مبلغ قابل پرداخت', ar: 'الإجمالي المستحق' },
  paid: { en: 'Paid', fa: 'پرداخت شده', ar: 'مدفوعة' },
  unpaid: { en: 'Awaiting payment', fa: 'در انتظار پرداخت', ar: 'بانتظار الدفع' },
  overdue: { en: 'Overdue', fa: 'سرسید گذشته', ar: 'متأخرة' },
  draft: { en: 'Draft', fa: 'پیش‌نویس', ar: 'مسودة' },
  caption: { en: 'Bank transfer · Acme Bank · IBAN …', fa: 'کارت به کارت · بانک … · شماره کارت …', ar: 'تحويل بنكي …' },
};

export interface InvoiceLine {
  /** Keeps its DOM node across re-renders; the index by default. */
  id?: string;
  title: ReactNode;
  description?: ReactNode;
  quantity?: number;
  unitPrice?: number;
  /** The whole row's figure, when it is not quantity × unit price. */
  amount?: number;
}

export interface InvoiceStep {
  label: ReactNode;
  /** A share of the subtotal (0.09 = 9% tax); `amount` wins when both are given. */
  percent?: number;
  amount?: number;
}

export interface InvoiceParty {
  name: ReactNode;
  /** Address, email, tax id — a line each. */
  lines?: ReactNode[];
}

export interface InvoiceProps extends Omit<HTMLAttributes<HTMLElement>, 'children'> {
  brand: ReactNode;
  brandTagline?: ReactNode;
  /** The square mark; the brand's first letter by default. */
  brandMark?: ReactNode;
  number: ReactNode;
  issueDate?: ReactNode;
  dueDate?: ReactNode;
  status?: InvoiceStatus;
  statusLabel?: string;
  from: InvoiceParty;
  to: InvoiceParty;
  lines: InvoiceLine[];
  /** Steps between the subtotal and the total (tax, discount — a negative amount). */
  extraTotals?: InvoiceStep[];
  /** The unit shown beside every figure: "$", "تومان", "USD"… */
  currency?: ReactNode;
  /** Fraction digits (0 for tomans, 2 for dollars). */
  decimals?: number;
  note?: ReactNode;
  footer?: ReactNode;
  labels?: Partial<Record<InvoiceWord, string>>;
  locale?: string;
}

export function Invoice({
  brand,
  brandTagline,
  brandMark,
  number,
  issueDate,
  dueDate,
  status,
  statusLabel,
  from,
  to,
  lines,
  extraTotals = [],
  currency,
  decimals = 0,
  note,
  footer,
  labels,
  locale,
  className,
  ...rest
}: InvoiceProps) {
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const word = (key: InvoiceWord) => labels?.[key] ?? WORDS[key][language];
  const id = useId();
  const root = useRef<HTMLElement>(null);
  const body = useRef<HTMLTableSectionElement>(null);
  // The sections rise one after another; the line items one row at a time.
  useReveal(root, { stagger: true });
  useReveal(body, { stagger: true });

  const format = useMemo(
    () => ({ minimumFractionDigits: decimals, maximumFractionDigits: decimals }) as Intl.NumberFormatOptions,
    [decimals],
  );

  const rows = useMemo(
    () =>
      lines.map((line, i) => ({
        key: line.id ?? String(i),
        line,
        sum: line.amount ?? (line.quantity ?? 1) * (line.unitPrice ?? 0),
      })),
    [lines],
  );
  const subtotal = useMemo(() => rows.reduce((sum, row) => sum + row.sum, 0), [rows]);
  const steps = useMemo(
    () => extraTotals.map((step) => ({ step, amount: step.amount ?? subtotal * (step.percent ?? 0) })),
    [extraTotals, subtotal],
  );
  const total = useMemo(() => steps.reduce((sum, step) => sum + step.amount, subtotal), [steps, subtotal]);

  /** The totals roll their digits; line amounts ride their row's reveal instead. */
  const money = (value: number, roll = true) => (
    <span className="nx-invoice-amount">
      <NumberTicker value={value} locale={intl} format={format} reveal={roll} />
      {currency && <span className="nx-invoice-currency">{currency}</span>}
    </span>
  );

  const facts: Array<[label: string, value: ReactNode] | null> = [
    [word('number'), number],
    issueDate ? [word('issueDate'), issueDate] : null,
    dueDate ? [word('dueDate'), dueDate] : null,
  ];

  return (
    <article ref={root} className={cx('nx-invoice', className)} data-status={status} data-nx-reveal="group" aria-labelledby={`${id}-title`} {...rest}>
      <header className="nx-invoice-section nx-invoice-head">
        <div className="nx-invoice-brand">
          <div className="nx-invoice-brand-row">
            <span className="nx-invoice-mark" aria-hidden={brandMark ? undefined : true}>
              {brandMark ?? (typeof brand === 'string' ? Array.from(brand)[0] : 'N')}
            </span>
            <span className="nx-invoice-brand-name">{brand}</span>
          </div>
          {brandTagline && <span className="nx-invoice-brand-tagline">{brandTagline}</span>}
        </div>
        <div className="nx-invoice-meta">
          <h2 className="nx-invoice-title" id={`${id}-title`}>
            {word('title')}
          </h2>
          {facts.some(Boolean) && (
            <dl className="nx-invoice-facts">
              {facts.map((fact) =>
                fact ? (
                  <div key={String(fact[0])}>
                    <dt>{fact[0]}</dt>
                    <dd>{fact[1]}</dd>
                  </div>
                ) : null,
              )}
            </dl>
          )}
          {status && (
            <StatusBadge
              status={BADGE_OF[status]}
              label={statusLabel}
              labels={{ success: word('paid'), queued: word('unpaid'), failed: word('overdue'), canceled: word('draft') }}
            />
          )}
        </div>
      </header>

      <div className="nx-invoice-section nx-invoice-parties">
        {([['from', from, word('from')], ['to', to, word('to')]] as const).map(([kind, party, label]) => (
          <div key={kind} className="nx-invoice-party" data-kind={kind}>
            <span className="nx-invoice-party-label">{label}</span>
            <p className="nx-invoice-party-name">{party.name}</p>
            {party.lines && party.lines.length > 0 && (
              <div className="nx-invoice-party-lines">
                {party.lines.map((line, i) => (
                  <span key={i}>{line}</span>
                ))}
              </div>
            )}
          </div>
        ))}
      </div>

      <table className="nx-invoice-section nx-invoice-lines">
        <caption className="nx-visually-hidden">
          {word('title')} {number}
        </caption>
        <thead>
          <tr>
            <th scope="col" className="nx-invoice-index-head" aria-label="#" />
            <th scope="col">{word('item')}</th>
            <th scope="col" data-align="end">
              {word('quantity')}
            </th>
            <th scope="col" data-align="end">
              {word('unitPrice')}
            </th>
            <th scope="col" data-align="end">
              {word('amount')}
            </th>
          </tr>
        </thead>
        <tbody ref={body} data-nx-reveal="group">
          {rows.map((row, i) => (
            <tr key={row.key} className="nx-invoice-line" style={vars({ '--nx-i': i })}>
              <td className="nx-invoice-index">{new Intl.NumberFormat(intl).format(i + 1)}</td>
              <td className="nx-invoice-item">
                <span className="nx-invoice-item-title">{row.line.title}</span>
                {row.line.description && <span className="nx-invoice-item-description">{row.line.description}</span>}
              </td>
              <td data-align="end">{row.line.quantity !== undefined ? new Intl.NumberFormat(intl).format(row.line.quantity) : '—'}</td>
              <td data-align="end">{row.line.unitPrice !== undefined ? new Intl.NumberFormat(intl, format).format(row.line.unitPrice) : '—'}</td>
              <td data-align="end">{money(row.sum, false)}</td>
            </tr>
          ))}
        </tbody>
      </table>

      <div className="nx-invoice-section nx-invoice-totals">
        <div className="nx-invoice-row">
          <span>{word('subtotal')}</span>
          {money(subtotal)}
        </div>
        {steps.map((step, i) => (
          <div key={i} className="nx-invoice-row">
            <span>{step.step.label}</span>
            {money(step.amount)}
          </div>
        ))}
        <div className="nx-invoice-total">
          <span className="nx-invoice-total-label">{word('total')}</span>
          {money(total)}
        </div>
        {note && <p className="nx-invoice-note">{note}</p>}
      </div>

      <footer className="nx-invoice-section nx-invoice-foot">
        <span>{footer ?? word('caption')}</span>
        <span>{brand}</span>
      </footer>
    </article>
  );
}
