<script setup lang="ts">
/**
 * The invoice document — the hand-written Vue shape of the `invoice` block
 * (nx-invoice): brand head, parties, line items that reveal one row at a time
 * (core `reveal`, staggered), totals whose digits roll (RollNumber over core
 * `numberParts`), and the payment state on a status badge whose width morphs
 * (core `fitStatusBadge`). Amounts are pure props (quantity × unit price
 * unless an explicit `amount`); the block's own words come from the core i18n
 * table (invoiceTitle, invoiceFrom…), overridable one by one via `labels`.
 */
import { computed, onBeforeUnmount, onMounted, ref, useId, watchPostEffect } from 'vue';
import { type MessageKey, fitStatusBadge, reveal, translate } from '@nabuxai/ui-core';
import { intlLocale, lang, numberFmt } from '../store';
import RollNumber from './RollNumber.vue';

export type InvoiceStatus = 'paid' | 'unpaid' | 'overdue' | 'draft';

/** The badge speaks in job statuses; an invoice maps onto them. */
const BADGE_OF: Record<InvoiceStatus, 'success' | 'queued' | 'failed' | 'canceled'> = { paid: 'success', unpaid: 'queued', overdue: 'failed', draft: 'canceled' };

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

/** The words the invoice says itself; override any of them with `labels`. */
type InvoiceWord =
  | 'title' | 'number' | 'issueDate' | 'dueDate' | 'from' | 'to'
  | 'item' | 'quantity' | 'unitPrice' | 'amount' | 'subtotal' | 'total'
  | 'paid' | 'unpaid' | 'overdue' | 'draft' | 'caption';

const WORD_KEYS: Record<InvoiceWord, MessageKey> = {
  title: 'invoiceTitle',
  number: 'invoiceNumber',
  issueDate: 'invoiceIssueDate',
  dueDate: 'invoiceDueDate',
  from: 'invoiceFrom',
  to: 'invoiceTo',
  item: 'invoiceItem',
  quantity: 'invoiceQuantity',
  unitPrice: 'invoiceUnitPrice',
  amount: 'invoiceAmount',
  subtotal: 'invoiceSubtotal',
  total: 'invoiceTotal',
  paid: 'invoicePaid',
  unpaid: 'invoiceUnpaid',
  overdue: 'invoiceOverdue',
  draft: 'invoiceDraft',
  caption: 'invoiceCaption',
};

const props = withDefaults(
  defineProps<{
    brand: string;
    brandTagline?: string;
    /** The square mark; the brand's first letter by default. */
    brandMark?: string;
    number: string;
    issueDate?: string;
    dueDate?: string;
    status?: InvoiceStatus;
    statusLabel?: string;
    from: InvoiceParty;
    to: InvoiceParty;
    lines: InvoiceLine[];
    /** Steps between the subtotal and the total (tax, discount — a negative amount). */
    extraTotals?: InvoiceStep[];
    /** The unit shown beside every figure: "$", "تومان"… */
    currency?: string;
    /** Fraction digits (0 for tomans, 2 for dollars). */
    decimals?: number;
    note?: string;
    footer?: string;
    labels?: Partial<Record<InvoiceWord, string>>;
  }>(),
  {
    brandTagline: undefined,
    brandMark: undefined,
    issueDate: undefined,
    dueDate: undefined,
    status: undefined,
    statusLabel: undefined,
    extraTotals: () => [],
    currency: undefined,
    decimals: 0,
    note: undefined,
    footer: undefined,
    labels: undefined,
  },
);

const uid = useId();
const root = ref<HTMLElement | null>(null);
const body = ref<HTMLTableSectionElement | null>(null);
const badge = ref<HTMLElement | null>(null);
let stopRoot: (() => void) | null = null;
let stopBody: (() => void) | null = null;

// The sections rise one after another; the line items one row at a time.
onMounted(() => {
  if (root.value) stopRoot = reveal(root.value, { once: true, stagger: true });
  if (body.value) stopBody = reveal(body.value, { once: true, stagger: true });
  document.fonts?.ready.then(() => fitStatusBadge(badge.value)).catch(() => {});
});
onBeforeUnmount(() => {
  stopRoot?.();
  stopBody?.();
});

const word = (key: InvoiceWord) => props.labels?.[key] ?? translate(lang.value, WORD_KEYS[key]);

const format = computed(() => ({ minimumFractionDigits: props.decimals, maximumFractionDigits: props.decimals }) as Intl.NumberFormatOptions);
const moneyFmt = computed(() => new Intl.NumberFormat(intlLocale.value, format.value));

const rows = computed(() =>
  props.lines.map((line, i) => ({ key: line.id ?? String(i), line, sum: line.amount ?? (line.quantity ?? 1) * (line.unitPrice ?? 0) })),
);
const subtotal = computed(() => rows.value.reduce((sum, row) => sum + row.sum, 0));
const steps = computed(() => props.extraTotals.map((step) => ({ step, amount: step.amount ?? subtotal.value * (step.percent ?? 0) })));
const total = computed(() => steps.value.reduce((sum, step) => sum + step.amount, subtotal.value));

const badgeStatus = computed(() => (props.status ? BADGE_OF[props.status] : undefined));
const badgeText = (state: 'success' | 'queued' | 'failed' | 'canceled') => {
  const ofStatus = (Object.keys(BADGE_OF) as InvoiceStatus[]).find((key) => BADGE_OF[key] === state);
  const label = ofStatus && ofStatus === props.status ? props.statusLabel : undefined;
  return label ?? word(ofStatus as InvoiceWord);
};

// The script writes --_w (the active layer's width) so the pill morphs between states.
watchPostEffect(() => fitStatusBadge(badge.value));

const mark = computed(() => props.brandMark ?? Array.from(props.brand)[0] ?? 'N');
</script>

<template>
  <article ref="root" class="nx-invoice" :data-status="status" data-nx-reveal="group" :aria-labelledby="`${uid}-title`">
    <header class="nx-invoice-section nx-invoice-head">
      <div class="nx-invoice-brand">
        <div class="nx-invoice-brand-row">
          <span class="nx-invoice-mark" aria-hidden="true">{{ mark }}</span>
          <span class="nx-invoice-brand-name">{{ brand }}</span>
        </div>
        <span v-if="brandTagline" class="nx-invoice-brand-tagline">{{ brandTagline }}</span>
      </div>
      <div class="nx-invoice-meta">
        <h2 class="nx-invoice-title" :id="`${uid}-title`">{{ word('title') }}</h2>
        <dl class="nx-invoice-facts">
          <div>
            <dt>{{ word('number') }}</dt>
            <dd dir="ltr">{{ number }}</dd>
          </div>
          <div v-if="issueDate">
            <dt>{{ word('issueDate') }}</dt>
            <dd>{{ issueDate }}</dd>
          </div>
          <div v-if="dueDate">
            <dt>{{ word('dueDate') }}</dt>
            <dd>{{ dueDate }}</dd>
          </div>
        </dl>
        <span v-if="badgeStatus" ref="badge" class="nx-status-badge" :data-status="badgeStatus">
          <span class="nx-visually-hidden">{{ badgeText(badgeStatus) }}</span>
          <span class="nx-status-badge-layers" aria-hidden="true">
            <span v-for="state in ['success', 'queued', 'failed', 'canceled'] as const" :key="state" class="nx-status-badge-layer" :data-for="state">
              <span class="nx-status-badge-icon">
                <span v-if="state === 'queued'" class="nx-status-badge-dots"><i /><i /><i /></span>
                <svg v-else viewBox="0 0 16 16">
                  <circle v-if="state === 'canceled'" cx="8" cy="8" r="6.25" />
                  <circle v-else class="nx-status-badge-disc" cx="8" cy="8" r="7.5" />
                  <path v-if="state === 'success'" class="nx-status-badge-mark" d="M4.9 8.3l2.1 2.1 4.1-4.4" pathLength="1" />
                  <path v-else-if="state === 'failed'" class="nx-status-badge-mark" d="M5.7 5.7l4.6 4.6M10.3 5.7l-4.6 4.6" pathLength="1" />
                  <path v-else-if="state === 'canceled'" class="nx-status-badge-mark" d="M3.8 12.2L12.2 3.8" pathLength="1" />
                </svg>
              </span>
              <span class="nx-status-badge-label">{{ badgeText(state) }}</span>
            </span>
          </span>
        </span>
      </div>
    </header>

    <div class="nx-invoice-section nx-invoice-parties">
      <div class="nx-invoice-party" data-kind="from">
        <span class="nx-invoice-party-label">{{ word('from') }}</span>
        <p class="nx-invoice-party-name">{{ from.name }}</p>
        <div v-if="from.lines?.length" class="nx-invoice-party-lines">
          <span v-for="(line, i) in from.lines" :key="i">{{ line }}</span>
        </div>
      </div>
      <div class="nx-invoice-party" data-kind="to">
        <span class="nx-invoice-party-label">{{ word('to') }}</span>
        <p class="nx-invoice-party-name">{{ to.name }}</p>
        <div v-if="to.lines?.length" class="nx-invoice-party-lines">
          <span v-for="(line, i) in to.lines" :key="i">{{ line }}</span>
        </div>
      </div>
    </div>

    <table class="nx-invoice-section nx-invoice-lines">
      <caption class="nx-visually-hidden">{{ word('title') }} {{ number }}</caption>
      <thead>
        <tr>
          <th scope="col" class="nx-invoice-index-head" aria-label="#" />
          <th scope="col">{{ word('item') }}</th>
          <th scope="col" data-align="end">{{ word('quantity') }}</th>
          <th scope="col" data-align="end">{{ word('unitPrice') }}</th>
          <th scope="col" data-align="end">{{ word('amount') }}</th>
        </tr>
      </thead>
      <tbody ref="body" data-nx-reveal="group">
        <tr v-for="(row, i) in rows" :key="row.key" class="nx-invoice-line" :style="{ '--nx-i': i }">
          <td class="nx-invoice-index">{{ numberFmt.format(i + 1) }}</td>
          <td class="nx-invoice-item">
            <span class="nx-invoice-item-title">{{ row.line.title }}</span>
            <span v-if="row.line.description" class="nx-invoice-item-description">{{ row.line.description }}</span>
          </td>
          <td data-align="end">{{ row.line.quantity !== undefined ? numberFmt.format(row.line.quantity) : '—' }}</td>
          <td data-align="end">{{ row.line.unitPrice !== undefined ? moneyFmt.format(row.line.unitPrice) : '—' }}</td>
          <td data-align="end">
            <span class="nx-invoice-amount">
              {{ moneyFmt.format(row.sum) }}
              <span v-if="currency" class="nx-invoice-currency">{{ currency }}</span>
            </span>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="nx-invoice-section nx-invoice-totals">
      <div class="nx-invoice-row">
        <span>{{ word('subtotal') }}</span>
        <span class="nx-invoice-amount">
          <RollNumber :value="subtotal" :format="format" />
          <span v-if="currency" class="nx-invoice-currency">{{ currency }}</span>
        </span>
      </div>
      <div v-for="(step, i) in steps" :key="i" class="nx-invoice-row">
        <span>{{ step.step.label }}</span>
        <span class="nx-invoice-amount">
          <RollNumber :value="step.amount" :format="format" />
          <span v-if="currency" class="nx-invoice-currency">{{ currency }}</span>
        </span>
      </div>
      <div class="nx-invoice-total">
        <span class="nx-invoice-total-label">{{ word('total') }}</span>
        <span class="nx-invoice-amount">
          <RollNumber :value="total" :format="format" />
          <span v-if="currency" class="nx-invoice-currency">{{ currency }}</span>
        </span>
      </div>
      <p v-if="note" class="nx-invoice-note">{{ note }}</p>
    </div>

    <footer class="nx-invoice-section nx-invoice-foot">
      <span>{{ footer ?? word('caption') }}</span>
      <span>{{ brand }}</span>
    </footer>
  </article>
</template>
