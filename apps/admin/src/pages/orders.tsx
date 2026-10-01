/**
 * #/orders — the orders table, live: search, a status chip filter and the
 * table block's own sorting over seeded bilingual orders; every row opens the
 * order-tracking block in a dialog (the step route, the event log, the courier
 * and the tracking code). "New order" appends a genuine row, the CSV export
 * downloads exactly what is on show, and the donut beside the table splits the
 * current filter by status. Dates and totals are locale-formatted (Persian
 * digits and the Jalali calendar for fa); ids go through `localeId`.
 * Deterministic: no Math.random, no fetch.
 */
import { useState } from 'react';
import {
  Avatar,
  Button,
  ChipFilter,
  Dialog,
  DonutRing,
  EmptyState,
  Field,
  Input,
  OrderTracking,
  StatusBadge,
  DataTable,
  toast,
  type DataTableColumn,
  type JobStatus,
  type OrderEvent,
  type OrderStep,
} from '@nabuxai/ui-react';
import { AGO, NOW } from '../data';
import { localeId, useLang, useStrings, useTr } from '../lang';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;
const DAY = 86_400_000;

/** Read a number as typed: Persian and Arabic-Indic digits (۰-۹/٠-٩) count too. */
const readDigits = (raw: string) =>
  Number(
    raw
      .replace(/[۰-۹]/g, (d) => String(d.charCodeAt(0) - 0x06f0))
      .replace(/[٠-٩]/g, (d) => String(d.charCodeAt(0) - 0x0660))
      .replace(/[^0-9]/g, ''),
  );

type Status = 'new' | 'packing' | 'shipped' | 'delivered' | 'cancelled' | 'refunded';

type Order = {
  id: string;
  number: string;
  customerFa: string;
  customerEn: string;
  items: number;
  /** ms timestamp. */
  date: number;
  /** Tomans. */
  total: number;
  paid: boolean;
  status: Status;
  addressFa: string;
  addressEn: string;
};

const SEED: Order[] = [
  { id: 'o1', number: '#1248', customerFa: 'مریم رضایی', customerEn: 'Maryam Rezaei', items: 3, date: AGO.minutes18.at, total: 4_170_000, paid: true, status: 'new', addressFa: 'تهران، خیابان ولیعصر، پلاک ۱۲', addressEn: 'Tehran, Valiasr St. 12' },
  { id: 'o2', number: '#1247', customerFa: 'مینا کریمی', customerEn: 'Mina Karimi', items: 2, date: AGO.hours2.at, total: 1_660_000, paid: true, status: 'packing', addressFa: 'تهران، سعادت‌آباد، کوچهٔ سرو', addressEn: 'Tehran, Saadat Abad, Sarv Alley' },
  { id: 'o3', number: '#1246', customerFa: 'علی نیک‌پور', customerEn: 'Ali Nikpour', items: 1, date: AGO.hours5.at, total: 380_000, paid: false, status: 'packing', addressFa: 'کرج، مهرشهر، بلوار ارم', addressEn: 'Karaj, Mehrshahr, Eram Blvd' },
  { id: 'o4', number: '#1245', customerFa: 'سارا احمدی', customerEn: 'Sara Ahmadi', items: 4, date: AGO.days2.at, total: 5_040_000, paid: true, status: 'shipped', addressFa: 'اصفهان، خیابان چهارباغ بالا', addressEn: 'Isfahan, Chaharbagh Ave.' },
  { id: 'o5', number: '#1244', customerFa: 'نگار موسوی', customerEn: 'Negar Mousavi', items: 2, date: AGO.days2.at, total: 2_130_000, paid: true, status: 'shipped', addressFa: 'شیراز، بلوار زند', addressEn: 'Shiraz, Zand Blvd' },
  { id: 'o6', number: '#1243', customerFa: 'بهرام رستمی', customerEn: 'Bahram Rostami', items: 3, date: AGO.days2.at - 2 * DAY, total: 3_290_000, paid: true, status: 'delivered', addressFa: 'تبریز، خیابان امام', addressEn: 'Tabriz, Emam St.' },
  { id: 'o7', number: '#1242', customerFa: 'رضا قاسمی', customerEn: 'Reza Ghasemi', items: 1, date: AGO.days2.at - 2 * DAY, total: 180_000, paid: true, status: 'delivered', addressFa: 'تهران، میدان ونک', addressEn: 'Tehran, Vanak Sq.' },
  { id: 'o8', number: '#1241', customerFa: 'الهام صادقی', customerEn: 'Elham Sadeghi', items: 5, date: AGO.days2.at, total: 2_460_000, paid: false, status: 'cancelled', addressFa: 'مشهد، بلوار وکیل‌آباد', addressEn: 'Mashhad, Vakil Abad Blvd' },
  { id: 'o9', number: '#1240', customerFa: 'کاوه تهامی', customerEn: 'Kaveh Tahami', items: 2, date: AGO.days2.at - 2 * DAY, total: 1_250_000, paid: true, status: 'refunded', addressFa: 'رشت، خیابان مطهری', addressEn: 'Rasht, Motahhari St.' },
  { id: 'o10', number: '#1239', customerFa: 'مریم رضایی', customerEn: 'Maryam Rezaei', items: 4, date: AGO.days2.at - 2 * DAY, total: 6_280_000, paid: true, status: 'delivered', addressFa: 'تهران، خیابان ولیعصر، پلاک ۱۲', addressEn: 'Tehran, Valiasr St. 12' },
];

/** Where each status sits on the placed → paid → packed → shipped → delivered route. */
const FLOW_INDEX: Record<Status, number> = { new: 0, packing: 2, shipped: 3, delivered: 4, cancelled: 1, refunded: 4 };/** How the panel's statuses land on the badge block's five glyph states. */
const BADGE: Record<Status, JobStatus> = { new: 'queued', packing: 'running', shipped: 'running', delivered: 'success', cancelled: 'canceled', refunded: 'failed' };
const STATUS_RANK: Record<Status, number> = { new: 0, packing: 1, shipped: 2, delivered: 3, cancelled: 4, refunded: 5 };

export function OrdersPage() {
  const lang = useLang();
  const tr = useTr();
  const s = useStrings();
  const o = s.pages.orders;
  const number = new Intl.NumberFormat(INTL[lang]);
  const dateWord = new Intl.DateTimeFormat(INTL[lang], { dateStyle: 'medium' });

  const [rows, setRows] = useState<Order[]>(SEED);
  const [query, setQuery] = useState('');
  const [status, setStatus] = useState('all');
  const [tracking, setTracking] = useState<Order | null>(null);
  const [creating, setCreating] = useState(false);
  const [draft, setDraft] = useState({ customer: '', items: '1', total: '' });

  const statusLabel: Record<Status, string> = {
    new: o.statusNew,
    packing: o.statusPacking,
    shipped: o.statusShipped,
    delivered: o.statusDelivered,
    cancelled: o.statusCancelled,
    refunded: o.statusRefunded,
  };
  const customerOf = (row: Order) => (lang === 'fa' ? row.customerFa : row.customerEn);

  const needle = query.trim().toLowerCase();
  const visible = rows.filter(
    (row) =>
      (status === 'all' || row.status === status) &&
      (!needle || row.number.toLowerCase().includes(needle) || row.customerFa.toLowerCase().includes(needle) || row.customerEn.toLowerCase().includes(needle)),
  );

  const counts = {
    all: rows.length,
    ...(Object.fromEntries((['new', 'packing', 'shipped', 'delivered', 'cancelled', 'refunded'] as const).map((id) => [id, rows.filter((row) => row.status === id).length])) as Record<Status, number>),
  };

  /** A real download of what is on show (BOM first, so Persian opens right in Excel). */
  const exportCsv = () => {
    const head = [o.colId, o.colCustomer, o.colItems, o.colDate, o.colTotal, o.colPayment, o.colStatus];
    const body = visible.map((row) => [
      localeId(row.number, lang),
      customerOf(row),
      `${number.format(row.items)} ${o.itemsUnit}`,
      dateWord.format(row.date),
      `${number.format(row.total)} ${tr('تومان', 'Toman')}`,
      row.paid ? o.paid : o.unpaid,
      statusLabel[row.status],
    ]);
    const csv = `\uFEFF${[head, ...body].map((cells) => cells.map((cell) => `"${cell.replaceAll('"', '""')}"`).join(',')).join('\n')}`;
    const url = URL.createObjectURL(new Blob([csv], { type: 'text/csv;charset=utf-8' }));
    const link = document.createElement('a');
    link.href = url;
    link.download = 'nabu-orders.csv';
    link.click();
    URL.revokeObjectURL(url);
    toast(o.exportCsv);
  };

  const create = () => {
    const customer = draft.customer.trim();
    const items = Math.max(1, readDigits(draft.items) || 1);
    const total = readDigits(draft.total);
    if (!customer || !total) return;
    const nextNumber = `#${1248 + rows.length - 9}`;
    setRows((prev) => [
      { id: `o-${Date.now().toString(36)}`, number: nextNumber, customerFa: customer, customerEn: customer, items, date: NOW, total, paid: true, status: 'new', addressFa: 'تهران، خیابان ولیعصر، پلاک ۱۲', addressEn: 'Tehran, Valiasr St. 12' },
      ...prev,
    ]);
    setCreating(false);
    setDraft({ customer: '', items: '1', total: '' });
    toast(tr(`سفارش ${localeId(nextNumber, lang)} ثبت شد`, `Order ${nextNumber} was placed`));
  };

  const columns: DataTableColumn<Order>[] = [
    {
      key: 'number',
      label: o.colId,
      sortable: true,
      sortValue: (row) => row.number,
      format: (_value, row) => <strong style={{ fontWeight: 650 }}>{localeId(row.number, lang)}</strong>,
    },
    {
      key: 'customer',
      label: o.colCustomer,
      sortable: true,
      sortValue: (row) => customerOf(row),
      format: (_value, row) => (
        <span style={{ display: 'inline-flex', alignItems: 'center', gap: 'var(--nx-space-2)' }}>
          <Avatar name={customerOf(row)} size="sm" />
          <span>{customerOf(row)}</span>
        </span>
      ),
    },
    {
      key: 'items',
      label: o.colItems,
      align: 'end',
      sortable: true,
      format: (_value, row) => `${number.format(row.items)} ${o.itemsUnit}`,
    },
    {
      key: 'date',
      label: o.colDate,
      sortable: true,
      sortValue: (row) => row.date,
      format: (_value, row) => <time dateTime={new Date(row.date).toISOString()}>{dateWord.format(row.date)}</time>,
    },
    {
      key: 'total',
      label: o.colTotal,
      align: 'end',
      sortable: true,
      sortValue: (row) => row.total,
      format: (_value, row) => `${number.format(row.total)} ${tr('تومان', 'Toman')}`,
    },
    {
      key: 'paid',
      label: o.colPayment,
      sortable: true,
      sortValue: (row) => (row.paid ? 1 : 0),
      format: (_value, row) => (
        <span className="nx-badge" data-tone={row.paid ? 'success' : undefined} data-dot={row.paid ? '' : undefined}>
          {row.paid ? o.paid : o.unpaid}
        </span>
      ),
    },
    {
      key: 'status',
      label: o.colStatus,
      sortable: true,
      sortValue: (row) => STATUS_RANK[row.status],
      format: (_value, row) => <StatusBadge status={BADGE[row.status]} label={statusLabel[row.status]} size="sm" />,
    },
    {
      key: 'actions',
      label: s.common.actions,
      align: 'end',
      format: (_value, row) => (
        <Button size="sm" variant="secondary" icon="arrow-right" onClick={() => setTracking(row)}>
          {o.viewTracking}
        </Button>
      ),
    },
  ];

  // The tracking dialog: the route up to the order's status, its event log, and
  // the two facts the panel keeps per order — the code and the address.
  // Delivered and refunded orders have walked the whole route (current sits one
  // past the last step, so every marker reads complete); a step or event only
  // carries a moment that has already happened — nothing is stamped in the future.
  const flowOf = (row: Order) => (row.status === 'delivered' || row.status === 'refunded' ? 5 : FLOW_INDEX[row.status]);
  const timesOf = (row: Order) => [row.date, row.date + 2 * 3_600_000, row.date + DAY + 2 * 3_600_000, row.date + 2 * DAY, row.date + 3 * DAY];

  const trackingSteps = (row: Order): OrderStep[] => {
    const at = flowOf(row);
    const times = timesOf(row);
    const titles = [o.stepPlaced, o.stepPaid, o.stepPacked, o.stepShipped, o.stepDelivered];
    return titles.map((title, i) => ({ id: `step-${i}`, title, time: i <= at && times[i] <= NOW ? times[i] : undefined }));
  };

  const trackingEvents = (row: Order): OrderEvent[] => {
    const at = flowOf(row);
    const times = timesOf(row);
    const events: OrderEvent[] = [
      { id: 'e-placed', title: o.stepPlaced, description: tr('سفارش در فروشگاه ثبت شد', 'The order was placed in the store'), place: tr('فروشگاه نابو', 'Nabu Store'), time: row.date, tone: 'neutral' },
      { id: 'e-paid', title: o.stepPaid, description: row.paid ? tr('پرداخت کارت‌به‌کارت تأیید شد', 'The card payment was confirmed') : tr('در انتظار پرداخت', 'Awaiting payment'), place: tr('بانک نابو', 'Nabu Bank'), time: Math.min(times[1], NOW), tone: row.paid ? 'success' : 'warning' },
    ];
    if (2 <= at && times[2] <= NOW) events.push({ id: 'e-packed', title: o.stepPacked, description: tr('بسته‌بندی هدیه هم انجام شد', 'Gift wrapping was done too'), place: tr('انبار مرکزی', 'Central warehouse'), time: times[2], tone: 'info' });
    if (3 <= at && times[3] <= NOW) events.push({ id: 'e-shipped', title: o.stepShipped, description: tr('به شرکت حمل سپرده شد', 'Handed to the carrier'), place: tr('تهران، مرکز توزیع', 'Tehran distribution hub'), time: times[3], tone: 'neutral' });
    if (4 <= at && times[4] <= NOW) events.push({ id: 'e-delivered', title: o.stepDelivered, description: tr('به دست مشتری رسید', 'It reached the customer'), place: lang === 'fa' ? row.addressFa : row.addressEn, time: times[4], tone: 'success' });
    if (row.status === 'cancelled') events.push({ id: 'e-cancelled', title: o.statusCancelled, description: tr('مشتری از پرداخت منصرف شد', 'The customer walked away from the payment'), time: row.date + DAY, tone: 'danger' });
    if (row.status === 'refunded') events.push({ id: 'e-refunded', title: o.statusRefunded, description: tr('مبلغ به کارت مشتری برگشت', 'The amount went back to the customer’s card'), time: row.date + 4 * DAY, tone: 'warning' });
    return events.reverse();
  };

  const canCreate = draft.customer.trim().length > 0 && readDigits(draft.total) > 0;

  return (
    <div className="adm-dashboard">
      <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-3)', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <Input
            type="search"
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            placeholder={o.search}
            aria-label={s.common.search}
            startAddon="search"
            style={{ inlineSize: 'min(17rem, 60vw)' }}
          />
          <ChipFilter
            aria-label={o.colStatus}
            value={status}
            onValueChange={setStatus}
            items={[
              { value: 'all', label: s.common.all, count: counts.all },
              ...(['new', 'packing', 'shipped', 'delivered', 'cancelled', 'refunded'] as const).map((id) => ({ value: id, label: statusLabel[id], count: counts[id] })),
            ]}
          />
        </div>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <span className="nx-badge" data-tone="accent" data-dot="">
            {number.format(visible.length)} {o.countLabel}
          </span>
          <Button variant="secondary" icon="file" onClick={exportCsv}>
            {o.exportCsv}
          </Button>
          <Button variant="primary" icon="plus" onClick={() => setCreating(true)}>
            {o.action}
          </Button>
        </div>
      </div>

      <DataTable
        className="adm-wide"
        caption={o.title}
        captionHidden
        maxHeight="30rem"
        rows={visible}
        columns={columns}
        defaultSort={{ key: 'date', direction: 'descending' }}
        emptyText={needle || status !== 'all' ? o.noMatch : o.emptyTitle}
      />

      <div className="adm-side">
        {visible.length > 0 ? (
          <DonutRing title={o.colStatus} data={(Object.keys(statusLabel) as Status[]).map((id) => ({ label: statusLabel[id], value: visible.filter((row) => row.status === id).length })).filter((slice) => slice.value > 0)} />
        ) : (
          <EmptyState icon="zap" title={o.noMatch} size="sm" />
        )}
      </div>

      <Dialog
        open={!!tracking}
        onOpenChange={(open) => !open && setTracking(null)}
        size="lg"
        title={o.trackingTitle}
        description={tracking ? `${localeId(tracking.number, lang)} · ${customerOf(tracking)}` : undefined}
      >
        {tracking && (
          <div style={{ display: 'grid', gap: 'var(--nx-space-4)' }}>
            <OrderTracking
              number={localeId(tracking.number, lang)}
              status={BADGE[tracking.status]}
              statusLabel={statusLabel[tracking.status]}
              carrier={tr('نابو اکسپرس', 'Nabu Express')}
              steps={trackingSteps(tracking)}
              current={flowOf(tracking)}
              events={trackingEvents(tracking)}
              label={o.trackingTitle}
            />
            <dl style={{ display: 'grid', gap: 'var(--nx-space-2)', margin: 0, fontSize: 'var(--nx-text-sm)' }}>
              <div style={{ display: 'flex', gap: 'var(--nx-space-3)', flexWrap: 'wrap' }}>
                <dt style={{ color: 'var(--nx-text-muted)' }}>{o.trackingCode}:</dt>
                <dd style={{ margin: 0 }}>
                  <span dir="ltr">{`NB-${tracking.number.replace('#', '')}-IR`}</span>
                </dd>
              </div>
              <div style={{ display: 'flex', gap: 'var(--nx-space-3)', flexWrap: 'wrap' }}>
                <dt style={{ color: 'var(--nx-text-muted)' }}>{o.address}:</dt>
                <dd style={{ margin: 0 }}>{lang === 'fa' ? tracking.addressFa : tracking.addressEn}</dd>
              </div>
            </dl>
          </div>
        )}
      </Dialog>

      <Dialog
        open={creating}
        onOpenChange={setCreating}
        size="sm"
        title={o.action}
        footer={
          <>
            <Button variant="secondary" onClick={() => setCreating(false)}>
              {s.common.cancel}
            </Button>
            <Button variant="primary" icon="plus" disabled={!canCreate} onClick={create}>
              {s.common.confirm}
            </Button>
          </>
        }
      >
        <div style={{ display: 'grid', gap: 'var(--nx-space-3)' }}>
          <Field label={o.colCustomer} required>
            <Input value={draft.customer} onChange={(event) => setDraft({ ...draft, customer: event.target.value })} autoComplete="off" />
          </Field>
          <Field label={o.colItems} required>
            <Input inputMode="numeric" dir="ltr" value={draft.items} onChange={(event) => setDraft({ ...draft, items: event.target.value })} autoComplete="off" />
          </Field>
          <Field label={o.colTotal} required hint={tr('تومان', 'Toman')}>
            <Input inputMode="numeric" dir="ltr" value={draft.total} onChange={(event) => setDraft({ ...draft, total: event.target.value })} autoComplete="off" />
          </Field>
        </div>
      </Dialog>
    </div>
  );
}
