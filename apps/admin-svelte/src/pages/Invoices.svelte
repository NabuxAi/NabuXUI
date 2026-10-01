<script lang="ts">
  /**
   * #/invoices — the invoice picker and the printable document. A chip filter
   * chooses one of four seeded invoices (one per status: paid, unpaid, overdue,
   * draft); switching re-mounts the Invoice block so its rows rise and its
   * totals roll again. The print button prints the document alone — the print
   * rules in app.css fold the shell away and the block ships its own paper
   * stylesheet. Dates format through the reader's locale (fa-IR gives the
   * Persian calendar) and document numbers follow the locale's digits;
   * amounts stay deterministic tomans.
   */
  import { app, intlLocale, strings } from '../store.svelte';
  import { localeId } from '../lang';
  import ChipFilter from '../lib/ChipFilter.svelte';
  import Invoice, { type InvoiceLine, type InvoiceParty, type InvoiceStatus, type InvoiceStep } from '../lib/Invoice.svelte';
  import NxIcon from '../lib/NxIcon.svelte';

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

  const s = $derived(strings());
  const inv = $derived(s.pages.invoices);

  let current = $state('inv-118');

  const dateFmt = $derived(new Intl.DateTimeFormat(intlLocale(), { dateStyle: 'medium' }));
  // Date-only ISO strings parse as UTC and drift a day in some zones; pin
  // them to local midnight so the printed date is the one we wrote.
  const parse = (iso: string) => new Date(`${iso}T00:00:00`);

  const from = $derived<InvoiceParty>({
    name: s.app.storeName,
    lines: [inv.tehran, 'billing@nabu.shop', inv.taxId],
  });

  const invoices = $derived<SeededInvoice[]>([
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
  ]);

  const active = $derived(invoices.find((doc) => doc.id === current) ?? invoices[0]!);

  const items = $derived(invoices.map((doc) => ({ value: doc.id, label: localeId(doc.number, app.lang) })));

  const print = () => window.print();
</script>

<div class="adm-invoices">
  <div class="adm-invoices-bar adm-noprint">
    <ChipFilter items={items} bind:value={current} label={inv.title} />
    <button type="button" class="nx-button" data-variant="primary" onclick={print}>
      <span class="nx-button-label">
        <NxIcon name="file" />
        <span class="nx-button-text">{inv.print}</span>
      </span>
    </button>
  </div>

  <!-- key re-mounts the block per invoice, so the reveal plays again -->
  {#key active.id}
    <div class="adm-invoices-doc">
      <Invoice
        brand={s.app.brand}
        brandTagline={s.app.tagline}
        number={localeId(active.number, app.lang)}
        status={active.status}
        issueDate={dateFmt.format(parse(active.issue))}
        dueDate={active.due ? dateFmt.format(parse(active.due)) : undefined}
        {from}
        to={active.to}
        lines={active.lines}
        extraTotals={active.extraTotals}
        note={active.note}
        currency={inv.currency}
        decimals={0}
        footer={inv.footer}
      />
    </div>
  {/key}
</div>
