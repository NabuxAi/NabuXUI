/**
 * /#/invoices — the invoice picker and the printable document. A chip filter
 * chooses one of four seeded invoices (one per status: paid, unpaid, overdue,
 * draft); switching re-mounts the Invoice block so its rows rise and its
 * totals roll again. The print button prints the document alone —
 * @media print in admin.css folds the shell away and the block ships its own
 * paper stylesheet. Dates format through the reader's locale (fa-IR gives the
 * Persian calendar), amounts stay deterministic tomans.
 */
import { useMemo, useState } from 'react';
import { Button, ChipFilter, Invoice, type InvoiceLine, type InvoiceParty, type InvoiceStep, type InvoiceStatus } from '@nabuxai/ui-react';
import { useLang, useStrings } from '../lang';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

interface SeededInvoice {
  id: string;
  number: string;
  status: InvoiceStatus;
  issue: string;
  due?: string;
  to: InvoiceParty;
  lines: InvoiceLine[];
  extraTotals: InvoiceStep[];
  note?: string;
}

export function InvoicesPage() {
  const lang = useLang();
  const s = useStrings();
  const inv = s.pages.invoices;
  const intl = INTL[lang];
  const [current, setCurrent] = useState('inv-118');

  const date = useMemo(() => new Intl.DateTimeFormat(intl, { dateStyle: 'medium' }), [intl]);
  // Date-only ISO strings parse as UTC and drift a day in some zones; pin
  // them to local midnight so the printed date is the one we wrote.
  const parse = (iso: string) => new Date(`${iso}T00:00:00`);

  const from: InvoiceParty = {
    name: s.app.storeName,
    lines: [inv.tehran, 'billing@nabu.shop', inv.taxId],
  };

  const invoices: SeededInvoice[] = [
    {
      id: 'inv-118',
      number: 'INV-2026-118',
      status: 'paid',
      issue: '2026-09-01',
      due: '2026-09-15',
      to: { name: s.people.maryam, lines: [inv.tehran, 'maryam@example.com'] },
      lines: [
        { id: 'bag', title: inv.itemBag, description: inv.itemBagDesc, quantity: 2, unitPrice: 8_400_000 },
        { id: 'scarf', title: inv.itemScarf, description: inv.itemScarfDesc, quantity: 3, unitPrice: 3_150_000 },
        { id: 'wrap', title: inv.itemWrap, description: inv.itemWrapDesc, quantity: 5, unitPrice: 180_000 },
      ],
      extraTotals: [{ label: inv.tax, percent: 0.09 }],
    },
    {
      id: 'inv-124',
      number: 'INV-2026-124',
      status: 'unpaid',
      issue: '2026-09-18',
      due: '2026-10-02',
      to: { name: inv.clientAcme, lines: [inv.tehran, 'accounts@acme.ir'] },
      lines: [
        { id: 'watch', title: inv.itemWatch, description: inv.itemWatchDesc, quantity: 4, unitPrice: 12_900_000 },
        { id: 'support', title: inv.itemSupport, description: inv.itemSupportDesc, quantity: 1, unitPrice: 25_000_000 },
      ],
      extraTotals: [{ label: inv.tax, percent: 0.09 }],
    },
    {
      id: 'inv-097',
      number: 'INV-2026-097',
      status: 'overdue',
      issue: '2026-08-05',
      due: '2026-08-20',
      to: { name: inv.clientRangin, lines: [inv.isfahan, 'hello@rangin.art'] },
      lines: [
        { id: 'print', title: inv.itemPrint, description: inv.itemPrintDesc, quantity: 3, unitPrice: 6_800_000 },
        { id: 'ship', title: inv.itemShip, quantity: 1, unitPrice: 1_200_000 },
      ],
      extraTotals: [
        { label: inv.discount, amount: -900_000 },
        { label: inv.tax, percent: 0.09 },
      ],
      note: inv.lateFeeNote,
    },
    {
      id: 'draft-41',
      number: 'DRAFT-41',
      status: 'draft',
      issue: '2026-09-26',
      to: { name: inv.clientInternal },
      lines: [
        { id: 'audit', title: inv.itemAudit, quantity: 6, unitPrice: 9_000_000 },
        { id: 'rtl', title: inv.itemRtl, quantity: 4, unitPrice: 7_500_000 },
      ],
      extraTotals: [],
    },
  ];

  const active = invoices.find((doc) => doc.id === current) ?? invoices[0]!;

  return (
    <div className="adm-invoices">
      <div className="adm-invoices-bar adm-noprint">
        <ChipFilter
          aria-label={inv.title}
          value={current}
          onValueChange={setCurrent}
          items={invoices.map((doc) => ({ value: doc.id, label: doc.number }))}
        />
        <Button variant="primary" icon="file" onClick={() => window.print()}>
          {inv.print}
        </Button>
      </div>

      {/* key re-mounts the block per invoice, so the reveal plays again */}
      <div className="adm-invoices-doc" key={active.id}>
        <Invoice
          brand={s.app.brand}
          brandTagline={s.app.tagline}
          number={active.number}
          status={active.status}
          issueDate={date.format(parse(active.issue))}
          dueDate={active.due ? date.format(parse(active.due)) : undefined}
          from={from}
          to={active.to}
          lines={active.lines}
          extraTotals={active.extraTotals}
          note={active.note}
          currency={inv.currency}
          decimals={0}
          locale={intl}
          footer={inv.footer}
          labels={{
            title: inv.docTitle,
            number: inv.numberLabel,
            issueDate: inv.issueDate,
            dueDate: inv.dueDate,
            from: inv.from,
            to: inv.to,
            item: inv.description,
            quantity: inv.qty,
            unitPrice: inv.unitPrice,
            amount: inv.amount,
            subtotal: inv.subtotal,
            total: inv.total,
            paid: inv.paid,
            unpaid: inv.unpaid,
            overdue: inv.overdue,
            draft: inv.draft,
            caption: inv.paymentCaption,
          }}
        />
      </div>
    </div>
  );
}
