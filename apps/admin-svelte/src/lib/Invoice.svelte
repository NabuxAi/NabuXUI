<script lang="ts" module>
  export type InvoiceStatus = 'paid' | 'unpaid' | 'overdue' | 'draft';
  export interface InvoiceLine {
    id?: string;
    title: string;
    description?: string;
    quantity?: number;
    unitPrice?: number;
    /** The whole row's figure, when it is not quantity × unit price. */
    amount?: number;
  }
  export interface InvoiceStep {
    label: string;
    /** A share of the subtotal (0.09 = 9% tax); `amount` wins when both are given. */
    percent?: number;
    amount?: number;
  }
  export interface InvoiceParty {
    name: string;
    /** Address, email, tax id — a line each. */
    lines?: string[];
  }

  /** The badge speaks in job statuses; an invoice maps onto them. */
  export const BADGE_OF: Record<InvoiceStatus, 'success' | 'queued' | 'failed' | 'canceled'> = { paid: 'success', unpaid: 'queued', overdue: 'failed', draft: 'canceled' };
</script>

<script lang="ts">
  /**
   * The invoice block — the hand-written Svelte shape of `nx-invoice`: a
   * printable document — brand head, parties, line items that reveal one row at
   * a time, totals whose digits roll (RollNumber), and the payment state on a
   * status badge. Amounts are pure props (quantity × unit price unless you give
   * `amount`); the words the document says come from the core i18n table.
   */
  import { onMount } from 'svelte';
  import { type Cleanup, reveal, translate } from '@nabuxai/ui-core';
  import { app, intlLocale } from '../store.svelte';
  import RollNumber from './RollNumber.svelte';

  let {
    brand,
    brandTagline = undefined,
    brandMark = undefined,
    number,
    issueDate = undefined,
    dueDate = undefined,
    status = undefined,
    statusLabel = undefined,
    from,
    to,
    lines,
    extraTotals = [],
    currency = undefined,
    decimals = 0,
    note = undefined,
    footer = undefined,
    caption = undefined,
  }: {
    brand: string;
    brandTagline?: string;
    brandMark?: string;
    number: string;
    issueDate?: string;
    dueDate?: string;
    status?: InvoiceStatus;
    statusLabel?: string;
    from: InvoiceParty;
    to: InvoiceParty;
    lines: InvoiceLine[];
    extraTotals?: InvoiceStep[];
    /** The unit shown beside every figure: "$", "تومان"… */
    currency?: string;
    decimals?: number;
    note?: string;
    footer?: string;
    /** The payment caption on the foot (the core's own word by default). */
    caption?: string;
  } = $props();

  let root: HTMLElement;
  let body: HTMLTableSectionElement;
  let stopRoot: Cleanup | null = null;
  let stopBody: Cleanup | null = null;

  const intl = $derived(intlLocale());
  const format = $derived({ minimumFractionDigits: decimals, maximumFractionDigits: decimals } as Intl.NumberFormatOptions);
  const word = (key: 'invoiceTitle' | 'invoiceNumber' | 'invoiceIssueDate' | 'invoiceDueDate' | 'invoiceFrom' | 'invoiceTo' | 'invoiceItem' | 'invoiceQuantity' | 'invoiceUnitPrice' | 'invoiceAmount' | 'invoiceSubtotal' | 'invoiceTotal' | 'invoicePaid' | 'invoiceUnpaid' | 'invoiceOverdue' | 'invoiceDraft' | 'invoiceCaption') => translate(app.lang, key);

  const rows = $derived(
    lines.map((line, i) => ({
      key: line.id ?? String(i),
      line,
      sum: line.amount ?? (line.quantity ?? 1) * (line.unitPrice ?? 0),
    })),
  );
  const subtotal = $derived(rows.reduce((sum, row) => sum + row.sum, 0));
  const steps = $derived(extraTotals.map((step) => ({ step, amount: step.amount ?? subtotal * (step.percent ?? 0) })));
  const total = $derived(steps.reduce((sum, step) => sum + step.amount, subtotal));

  const statusWord = $derived(status ? { paid: word('invoicePaid'), unpaid: word('invoiceUnpaid'), overdue: word('invoiceOverdue'), draft: word('invoiceDraft') }[status] : '');
  const badge = $derived(status ? BADGE_OF[status] : undefined);

  // The sections rise one after another; the line items one row at a time.
  onMount(() => {
    stopRoot = reveal(root, { stagger: true });
    stopBody = reveal(body, { stagger: true });
    return () => {
      stopRoot?.();
      stopBody?.();
    };
  });
</script>

<article bind:this={root} class="nx-invoice" data-status={status} data-nx-reveal="group" aria-labelledby="nx-invoice-title">
  <header class="nx-invoice-section nx-invoice-head">
    <div class="nx-invoice-brand">
      <div class="nx-invoice-brand-row">
        <span class="nx-invoice-mark" aria-hidden={brandMark ? undefined : 'true'}>{brandMark ?? Array.from(brand)[0]}</span>
        <span class="nx-invoice-brand-name">{brand}</span>
      </div>
      {#if brandTagline}<span class="nx-invoice-brand-tagline">{brandTagline}</span>{/if}
    </div>
    <div class="nx-invoice-meta">
      <h2 class="nx-invoice-title" id="nx-invoice-title">{word('invoiceTitle')}</h2>
      <dl class="nx-invoice-facts">
        <div>
          <dt>{word('invoiceNumber')}</dt>
          <dd>{number}</dd>
        </div>
        {#if issueDate}
          <div>
            <dt>{word('invoiceIssueDate')}</dt>
            <dd>{issueDate}</dd>
          </div>
        {/if}
        {#if dueDate}
          <div>
            <dt>{word('invoiceDueDate')}</dt>
            <dd>{dueDate}</dd>
          </div>
        {/if}
      </dl>
      {#if status && badge}
        {@const label = statusLabel ?? statusWord}
        <span class="nx-status-badge" data-status={badge}>
          <span class="nx-visually-hidden">{label}</span>
          <span class="nx-status-badge-layers" aria-hidden="true">
            <span class="nx-status-badge-layer" data-for={badge}>
              {#if badge === 'success' || badge === 'canceled'}
                <span class="nx-status-badge-icon">
                  <svg viewBox="0 0 16 16">
                    <circle class="nx-status-badge-disc" cx="8" cy="8" r="7.5"></circle>
                    <path class="nx-status-badge-mark" d={badge === 'success' ? 'M4.9 8.3l2.1 2.1 4.1-4.4' : 'M5.7 5.7l4.6 4.6M10.3 5.7l-4.6 4.6'} pathLength="1"></path>
                  </svg>
                </span>
              {:else if badge === 'failed'}
                <span class="nx-status-badge-icon">
                  <svg viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="6.25"></circle>
                    <path class="nx-status-badge-mark" d="M3.8 12.2L12.2 3.8" pathLength="1"></path>
                  </svg>
                </span>
              {:else if badge === 'queued'}
                <span class="nx-status-badge-icon">
                  <span class="nx-status-badge-dots">
                    <i></i>
                    <i></i>
                    <i></i>
                  </span>
                </span>
              {/if}
              <span class="nx-status-badge-label">{label}</span>
            </span>
          </span>
        </span>
      {/if}
    </div>
  </header>

  <div class="nx-invoice-section nx-invoice-parties">
    {#each [{ kind: 'from', party: from, partyLabel: word('invoiceFrom') }, { kind: 'to', party: to, partyLabel: word('invoiceTo') }] as group (group.kind)}
      <div class="nx-invoice-party" data-kind={group.kind}>
        <span class="nx-invoice-party-label">{group.partyLabel}</span>
        <p class="nx-invoice-party-name">{group.party.name}</p>
        {#if group.party.lines && group.party.lines.length > 0}
          <div class="nx-invoice-party-lines">
            {#each group.party.lines as line, i (i)}
              <span>{line}</span>
            {/each}
          </div>
        {/if}
      </div>
    {/each}
  </div>

  <table class="nx-invoice-section nx-invoice-lines">
    <caption class="nx-visually-hidden">{word('invoiceTitle')} {number}</caption>
    <thead>
      <tr>
        <th scope="col" class="nx-invoice-index-head" aria-label="#"></th>
        <th scope="col">{word('invoiceItem')}</th>
        <th scope="col" data-align="end">{word('invoiceQuantity')}</th>
        <th scope="col" data-align="end">{word('invoiceUnitPrice')}</th>
        <th scope="col" data-align="end">{word('invoiceAmount')}</th>
      </tr>
    </thead>
    <tbody bind:this={body} data-nx-reveal="group">
      {#each rows as row, i (row.key)}
        <tr class="nx-invoice-line" style:--nx-i={i}>
          <td class="nx-invoice-index">{new Intl.NumberFormat(intl).format(i + 1)}</td>
          <td class="nx-invoice-item">
            <span class="nx-invoice-item-title">{row.line.title}</span>
            {#if row.line.description}<span class="nx-invoice-item-description">{row.line.description}</span>{/if}
          </td>
          <td data-align="end">{row.line.quantity !== undefined ? new Intl.NumberFormat(intl).format(row.line.quantity) : '—'}</td>
          <td data-align="end">{row.line.unitPrice !== undefined ? new Intl.NumberFormat(intl, format).format(row.line.unitPrice) : '—'}</td>
          <td data-align="end">
            <span class="nx-invoice-amount">
              <RollNumber value={row.sum} {format} roll={false} />
              {#if currency}<span class="nx-invoice-currency">{currency}</span>{/if}
            </span>
          </td>
        </tr>
      {/each}
    </tbody>
  </table>

  <div class="nx-invoice-section nx-invoice-totals">
    <div class="nx-invoice-row">
      <span>{word('invoiceSubtotal')}</span>
      <span class="nx-invoice-amount">
        <RollNumber value={subtotal} {format} />
        {#if currency}<span class="nx-invoice-currency">{currency}</span>{/if}
      </span>
    </div>
    {#each steps as step, i (i)}
      <div class="nx-invoice-row">
        <span>{step.step.label}</span>
        <span class="nx-invoice-amount">
          <RollNumber value={step.amount} {format} />
          {#if currency}<span class="nx-invoice-currency">{currency}</span>{/if}
        </span>
      </div>
    {/each}
    <div class="nx-invoice-total">
      <span class="nx-invoice-total-label">{word('invoiceTotal')}</span>
      <span class="nx-invoice-amount">
        <RollNumber value={total} {format} />
        {#if currency}<span class="nx-invoice-currency">{currency}</span>{/if}
      </span>
    </div>
    {#if note}<p class="nx-invoice-note">{note}</p>{/if}
  </div>

  <footer class="nx-invoice-section nx-invoice-foot">
    <span>{footer ?? caption ?? word('invoiceCaption')}</span>
    <span>{brand}</span>
  </footer>
</article>
