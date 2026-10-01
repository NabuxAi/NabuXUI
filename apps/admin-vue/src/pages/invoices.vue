<script setup lang="ts">
/**
 * The invoice picker and the printable document. A chip filter chooses one of
 * four seeded invoices (one per status: paid, unpaid, overdue, draft);
 * switching re-mounts the NxInvoice block so its rows rise and its totals
 * roll again. The print button prints the document alone — @media print in
 * app.css folds the shell away and the block ships its own paper stylesheet.
 * Dates format through the reader's locale (fa-IR gives the Persian
 * calendar), amounts stay deterministic tomans and invoice numbers follow the
 * panel language's digits (localeId). The document's structural words (title,
 * parties, totals, statuses) come from the core i18n table via the block
 * itself; only the content words live here.
 */
import { computed, ref } from 'vue';
import { localeId } from '../lang';
import { intlLocale, lang, s } from '../store';
import ChipFilter from '../components/ChipFilter.vue';
import NxIcon from '../components/NxIcon.vue';
import NxInvoice, { type InvoiceLine, type InvoiceParty, type InvoiceStatus, type InvoiceStep } from '../components/NxInvoice.vue';

const inv = computed(() => s.value.pages.invoices);
const current = ref('inv-118');

const date = computed(() => new Intl.DateTimeFormat(intlLocale.value, { dateStyle: 'medium' }));
// Date-only ISO strings parse as UTC and drift a day in some zones; pin
// them to local midnight so the printed date is the one we wrote.
const parse = (iso: string) => new Date(`${iso}T00:00:00`);

const print = () => window.print();

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

const from = computed<InvoiceParty>(() => ({
  name: s.value.app.storeName,
  lines: [inv.value.tehran, 'billing@nabu.shop', inv.value.taxId],
}));

const invoices = computed<SeededInvoice[]>(() => [
  {
    id: 'inv-118',
    number: 'INV-2026-118',
    status: 'paid',
    issue: '2026-09-01',
    due: '2026-09-15',
    to: { name: s.value.people.maryam, lines: [inv.value.tehran, 'maryam@example.com'] },
    lines: [
      { id: 'bag', title: inv.value.itemBag, description: inv.value.itemBagDesc, quantity: 2, unitPrice: 8_400_000 },
      { id: 'scarf', title: inv.value.itemScarf, description: inv.value.itemScarfDesc, quantity: 3, unitPrice: 3_150_000 },
      { id: 'wrap', title: inv.value.itemWrap, description: inv.value.itemWrapDesc, quantity: 5, unitPrice: 180_000 },
    ],
    extraTotals: [{ label: inv.value.tax, percent: 0.09 }],
  },
  {
    id: 'inv-124',
    number: 'INV-2026-124',
    status: 'unpaid',
    issue: '2026-09-18',
    due: '2026-10-02',
    to: { name: inv.value.clientAcme, lines: [inv.value.tehran, 'accounts@acme.ir'] },
    lines: [
      { id: 'watch', title: inv.value.itemWatch, description: inv.value.itemWatchDesc, quantity: 4, unitPrice: 12_900_000 },
      { id: 'support', title: inv.value.itemSupport, description: inv.value.itemSupportDesc, quantity: 1, unitPrice: 25_000_000 },
    ],
    extraTotals: [{ label: inv.value.tax, percent: 0.09 }],
  },
  {
    id: 'inv-097',
    number: 'INV-2026-097',
    status: 'overdue',
    issue: '2026-08-05',
    due: '2026-08-20',
    to: { name: inv.value.clientRangin, lines: [inv.value.isfahan, 'hello@rangin.art'] },
    lines: [
      { id: 'print', title: inv.value.itemPrint, description: inv.value.itemPrintDesc, quantity: 3, unitPrice: 6_800_000 },
      { id: 'ship', title: inv.value.itemShip, quantity: 1, unitPrice: 1_200_000 },
    ],
    extraTotals: [
      { label: inv.value.discount, amount: -900_000 },
      { label: inv.value.tax, percent: 0.09 },
    ],
    note: inv.value.lateFeeNote,
  },
  {
    id: 'draft-41',
    number: 'DRAFT-41',
    status: 'draft',
    issue: '2026-09-26',
    to: { name: inv.value.clientInternal },
    lines: [
      { id: 'audit', title: inv.value.itemAudit, quantity: 6, unitPrice: 9_000_000 },
      { id: 'rtl', title: inv.value.itemRtl, quantity: 4, unitPrice: 7_500_000 },
    ],
    extraTotals: [],
  },
]);

const active = computed(() => invoices.value.find((doc) => doc.id === current.value) ?? invoices.value[0]!);

const items = computed(() => invoices.value.map((doc) => ({ value: doc.id, label: localeId(doc.number, lang.value) })));
</script>

<template>
  <div class="adm-invoices">
    <div class="adm-invoices-bar adm-noprint">
      <ChipFilter v-model="current" :items="items" :label="inv.title" />
      <button type="button" class="nx-button" data-variant="primary" @click="print">
        <span class="nx-button-label">
          <NxIcon name="file" />
          <span class="nx-button-text">{{ inv.print }}</span>
        </span>
      </button>
    </div>

    <!-- The key re-mounts the block per invoice, so the reveal plays again -->
    <div :key="active.id" class="adm-invoices-doc">
      <NxInvoice
        :brand="s.app.brand"
        :brand-tagline="s.app.tagline"
        :number="localeId(active.number, lang)"
        :status="active.status"
        :issue-date="date.format(parse(active.issue))"
        :due-date="active.due ? date.format(parse(active.due)) : undefined"
        :from="from"
        :to="active.to"
        :lines="active.lines"
        :extra-totals="active.extraTotals"
        :note="active.note"
        :currency="inv.currency"
        :decimals="0"
        :footer="inv.footer"
        :labels="{ caption: inv.paymentCaption }"
      />
    </div>
  </div>
</template>
